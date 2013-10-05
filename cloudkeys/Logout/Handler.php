<?php

class LogoutHandler extends BaseHttpHandler {

  public function get($params) {
    $this->session->set('username', '');
    $this->response->redirect('overview');
  }
}
