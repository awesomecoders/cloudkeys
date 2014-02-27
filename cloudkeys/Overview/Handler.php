<?php

class OverviewHandler extends BaseHttpHandler {

  public function get($params) {
    if(count($params) < 1) {
      $this->response->redirect('u/0/overview');
      return;
    } else {
      $user_index = $params[0];
    }

    $authorized_accounts = $this->session->get('authorized_accounts');
    $userfile = $authorized_accounts[$user_index]['userfile'];
    if($userfile == "") {
      $this->response->redirect('../../login');
      return;
    }
    try {
      S3::getObject($this->config->get('AWSS3Bucket'), $userfile);
    } catch(Exception $e) {
      $this->response->redirect('../../login');
      return;
    }

    $frontend_accounts = array_map(function($el) { return $el['name']; }, $authorized_accounts);
    $this->response->set('authorized_accounts', $frontend_accounts);
    $this->response->set('current_user_index', $user_index);

    $this->response->display('overview');
  }

}
