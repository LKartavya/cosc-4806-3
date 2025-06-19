<?php

class Secret extends Controller {

    public function index() {		
      $this->view('secret/index');
      die;
    }
  public function submit() {
      $username = $_POST['username'];
      $password = $_POST['password'];
      $user = $this->model('User');
      $user->create($username, $password);
      header('Location: /login');
      die;
  }

}