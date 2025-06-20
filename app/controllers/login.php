<?php

class Login extends Controller {

		public function index() {		
			$fail = isset($_GET['fail']);
			$locked = isset($_GET['locked']);
			$this->view('login/index', ['fail' => $fail, 		  'locked' => $locked]);
		}

		public function verify(){
			$username = $_REQUEST['username'];
			$password = $_REQUEST['password'];
			$user = $this->model('User');
			$user->authenticate($username, $password);
		}

	public function getFailedAttempts($username, $pdo){
			$statement = $pdo->prepare("select count * from failed_attempts WHERE username = :name;");
			$statement->bindValue(':name', $username);
			$statement->execute();
			$rows = $statement->fetch(PDO::FETCH_ASSOC);
		
	}
}
