<?php

class AjaxHandler extends BaseHttpHandler {

  public function get($params) {
    $user_index = $params[0];
    $this->response->header("Content-Type", "text/json;charset=utf-8");
    $this->response->header('Cache-Control', 'no-cache');
    $userdata = $this->checkLogin($user_index);
    if($userdata !== null) {
      $data = json_decode($userdata->body, true);
      $this->response->json_output(array('version' => $data['metadata']['version'], 'data' => $data['data']));
    } else {
      $this->response->json_output(array('error' => true));
    }
  }

  public function post($params) {
    $user_index = $params[0];
    $this->response->header("Content-Type", "text/json;charset=utf-8");
    $this->response->header('Cache-Control', 'no-cache');

    $userfile = $this->getUserfile($user_index);
    if($userfile === null) {
      $this->response->json_output(array('error' => true, 'type' => 'login'));
      return;
    }

    $userdata = $this->checkLogin($user_index);
    if($userdata === null) {
      $this->response->json_output(array('error' => true, 'type' => 'register'));
      return;
    }

    $data = json_decode($userdata->body, true);
    
    if($data['metadata']['version'] != $this->request->get('version')) {
      $this->response->json_output(array('error' => true, 'type' => 'version'));
      return;
    }

    if($this->request->get('checksum') != sha1($this->request->get('data'))) {
      $this->response->json_output(array('error' => true, 'type' => 'checksum'));
      return;
    }

    try {
      S3::copyObject($this->config->get('AWSS3Bucket'), $userfile, $this->config->get('AWSS3Bucket'), 'backup/' . $userfile . '.' . idate('U'));
      $data['metadata']['version'] = $this->request->get('checksum');
      $data['data'] = $this->request->get('data');
      S3::putObject(json_encode($data), $this->config->get('AWSS3Bucket'), $userfile);
    } catch(Exception $e) {
      $this->response->json_output(array('error' => true, 'message' => $e->getMessage()));
      return;
    }
    $this->response->json_output(array('version' => $data['metadata']['version'], 'data' => $data['data']));
  }

  private function getUserfile($user_index) {
    $authorized_accounts = $this->session->get('authorized_accounts');
    $userfile = $authorized_accounts[$user_index]['userfile'];

    if($userfile == "") {
      return null;
    }
    return $userfile;
  }

  private function checkLogin($user_index) {
    $userfile = $this->getUserfile($user_index);
    if($userfile === null) {
      return null;
    }

    try {
      return S3::getObject($this->config->get('AWSS3Bucket'), $userfile);
    } catch(Exception $e) {
      return null;
    }
  }
}
