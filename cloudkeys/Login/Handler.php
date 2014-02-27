<?php

class LoginHandler extends BaseHttpHandler {

  public function get($params) {
    $this->response->display('login');
  }

  public function post($params) {
    $username = strtolower($this->request->get('username'));
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
        $authorized_accounts = $this->session->get('authorized_accounts', array());
        $newuser = array('name' => $username, 'userfile' => $this->createUserFilename($username));

        $user_index = null;
        if(in_array($newuser, $authorized_accounts)) {
          for($i = 0; $i < count($authorized_accounts); $i++) {
            if($authorized_accounts[$i] == $newuser) {
              $user_index = $i;
              break;
            }
          }
        } else {
          $authorized_accounts[] = $newuser;
          $user_index = count($authorized_accounts) - 1;
        }
        $this->session->set('authorized_accounts', $authorized_accounts);

        if($user_index !== null) {
          $this->response->redirect('/u/' . $user_index . '/overview');
        } else {
          $this->response->set("error", true);
        }
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
