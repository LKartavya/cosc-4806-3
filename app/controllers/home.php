<?php
session_start();

class HomeController {
    public function index() {
        if (!isset($_SESSION['auth'])) {
            header('Location: /views/login.php');
            exit;
        }

        include '../views/home.php';
    }
}

