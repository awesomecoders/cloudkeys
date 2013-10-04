<?php

class AjaxHandler extends BaseHttpHandler {

  public function get($params) {
    $this->response->header("Content-Type", "text/json;charset=utf-8");
    $userfile = $this->session->get('username');
    if($userfile == "") {
      echo json_encode(array('error' => true));
      return;
    }

    $userdata = null;
    try {
      $userdata = S3::getObject($this->config->get('AWSS3Bucket'), $userfile);
    } catch(Exception $e) {
      echo json_encode(array('error' => true));
      return;
    }

    if($userdata !== null) {
      $data = json_decode($userdata->body, true);
      echo json_encode($data['data']);
    }
  }
}
