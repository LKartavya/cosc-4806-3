<?php

class Home extends Controller {

    public function index() {
        
        require_once 'app/models/User.php';
      
          $user = $this->model('User');
          $data = $user->test();
          
        $this->view('home/index');
        die;
    }

}