<?php

class RegisterHandler extends BaseHttpHandler {

  public function get($params) {
    $this->response->display('register');
  }

  public function post($params) {
    $username = strtolower($this->request->get('username'));
    $password = $this->request->get('password');
    if($username != ""
      && $password != ""
      && $password == $this->request->get('password_repeat')) {
        if(!$this->userExists($username)) {
          $doc = array('metadata' => array(), 'data' => '');
          $doc['metadata']['version'] = '';
          $doc['metadata']['password'] = sha1($this->config->get('passwordSalt') . $password);
          S3::putObject(json_encode($doc), $this->config->get('AWSS3Bucket'), $this->createUserFilename($username), S3::ACL_PRIVATE);
          $this->response->set('created', true);
        } else {
          $this->response->set('exists', true);
        }
    }
    $this->response->display('register');
  }

  private function createUserFilename($username) {
    return sha1($this->config->get('usernameSalt') . $username);
  }

  private function userExists($username) {
    try {
      $existing = S3::getObject($this->config->get('AWSS3Bucket'), $this->createUserFilename($username));
    } catch(Exception $e) {
      if(stristr($e->getMessage(), "[NoSuchKey]")) {
        return false;
      }
    }
    return true;
  }
}
