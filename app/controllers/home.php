<?php

class Home extends Controller {

    public function index() {
        $user = new User();
        $user->test();
    //  $user = $this->model('User');
    //  $data = $user->test();

        $this->view('home/index');
        die;
    }

}