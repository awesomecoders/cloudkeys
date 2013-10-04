<?php

class OverviewHandler extends BaseHttpHandler {

  public function get($params) {
    $this->response->display('overview');
  }

}
