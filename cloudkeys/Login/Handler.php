<?php

class LoginHandler extends BaseHttpHandler {

  public function get($params) {
    $this->response->display('login');
  }

  public function post($params) {
    $username = $this->request->get('username');
    $password = $this->request->get('password');

    $existing = null;
    try {
      $existing = S3::getObject($this->config->get('AWSS3Bucket'), $this->createUserFilename($username));
    } catch(Exception $e) {
      $this->response->set("error", true);
    }
    if($existing != null) {
      $password = sha1($this->config->get('passwordSalt') . $password);
      $data = json_decode($existing->body, true);
      if(isset($data['metadata']['password']) && $data['metadata']['password'] == $password) {
        $this->session->set('username', $this->createUserFilename($username));
        $this->response->redirect('overview');
        return;
      } else {
        $this->response->set("error", true);
      }
    }
    
    $this->response->display('login');
  }

  private function createUserFilename($username) {
    return sha1($this->config->get('usernameSalt') . $username);
  }

}
