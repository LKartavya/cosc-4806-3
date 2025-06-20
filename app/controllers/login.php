<?php
require '../app/database.php';
class Login extends Controller {
	
		public function index() {
			$data['errors'] = [];
				if ($_SERVER['REQUEST_METHOD'] == 'POST') {
					
						$username = $_POST['username'] ?? '';
						$password = $_POST['password'] ?? '';
						
						try {
							$pdo = Database::getInstance()->getConnection();
						} catch (PDOException $e) {
							die("Could not connect to the database: " . $e->getMessage());
						}
						$failed = $this->getFailedAttempts($username, $pdo);
						if ($failed['fail_count'] >= 3) {
								$last = strtotime($failed['last_attempt']);
								if (time() - $last < 60) {
										$data['errors'][] = "Too many failed attempts. Try again in 60 seconds.";
										$this->view("login/index", $data);
										return;
								}
						}
						$user = $this->loadModel("User")->find($username);
						if ($user && password_verify($password, $user->password)) {
								$_SESSION['user'] = $user;
								$this->logAttempt($username, 'good', $pdo);
								header("Location: /welcome");
								exit;
						} else {
								$this->logAttempt($username, 'bad', $pdo);
								$data['errors'][] = "Invalid username or password.";
						}
				}
				$this->view("login/index", $data);
		}		
		

	public function getFailedAttempts($username, $pdo){
			$statement = $pdo->prepare("SELECT COUNT(*) as fail_count, MAX(attempt_time) as last_attempt 
		 FROM login_attempts 
		 WHERE username = ? AND status = 'bad' 
		 AND attempt_time > (NOW() - INTERVAL 5 MINUTE)");
			$statement->bindValue(':name', $username);
			$statement->execute();
			$rows = $statement->fetch(PDO::FETCH_ASSOC);
			$failed = getFailedAttempts($username, $pdo);
			
			if ($failed['fail_count'] > 3){
				$last_attempt = strtotime($failed['last_attempt']);
				if($last_attempt > time() - (60)){
					die("Account locked for 60 seconds");
			}
		}
	}
	
	private function logAttempt($username, $status, $pdo)
	{
			$stmt = $pdo->prepare("INSERT INTO login_attempts (username, status) VALUES (?, ?)");
			$stmt->execute([$username, $status]);
	}

	
}
