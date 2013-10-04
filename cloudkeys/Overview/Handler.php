<?php

class OverviewHandler extends BaseHttpHandler {

  public function get($params) {
    $userfile = $this->session->get('username');
    if($userfile == "") {
      $this->response->redirect('login');
      return;
    }
    try {
      S3::getObject($this->config->get('AWSS3Bucket'), $userfile);
    } catch(Exception $e) {
      $this->response->redirect('login');
      return;
    }
    $this->response->display('overview');
  }

}
