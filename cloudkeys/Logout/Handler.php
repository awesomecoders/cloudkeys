<?php

class LogoutHandler extends BaseHttpHandler {

  public function get($params) {
    $this->session->clear_all();
    $this->response->redirect('overview');
  }
}
