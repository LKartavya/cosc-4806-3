<?php
session_start();
require_once '../core/db_connect.php';
require_once '../models/User.php';

	class LoginController {
	public function login() {
			$username = $_POST['username'] ?? '';
			$password = $_POST['password'] ?? '';

			$user = new User();
			$result = $user->authenticate($username, $password);
			echo $result;
	}

	public function create() {
			include '../views/register.php';
	}

	public function store() {
			$username = $_POST['username'];
			$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

			$db = db_connect();
			$stmt = $db->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
			$stmt->bindValue(':username', strtolower($username));
			$stmt->bindValue(':password', $password);
			$stmt->execute();

			header('Location: /views/login.php');
			exit;
	}

}
