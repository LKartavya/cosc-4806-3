<?php

class User {

    public $username;
    public $password;
    public $auth = false;

    public function __construct() {
        
    }

    public function test () {
      $db = db_connect();
      $statement = $db->prepare("select * from users;");
      $statement->execute();
      $rows = $statement->fetch(PDO::FETCH_ASSOC);
      return $rows;
    }

    public function authenticate($username, $password) {
      $db = db_connect();
      $username = strtolower($username);

      $stmt = $db->prepare("SELECT * FROM users WHERE username = :name");
      $stmt->bindValue(':name', $username);
      $stmt->execute();
      $user = $stmt->fetch(PDO::FETCH_ASSOC);
      
		$username = strtolower($username);
		$db = db_connect();
        $statement = $db->prepare("select * from users WHERE username = :name;");
        $statement->bindValue(':name', $username);
        $statement->execute();
        $rows = $statement->fetch(PDO::FETCH_ASSOC);
		
  if (isset($_SESSION['failedAuth']) && $_SESSION['failedAuth'] >= 3) {
      $lastAttempt = $_SESSION['lastFailed'] ?? 0;
      if (time() - $lastAttempt < 60) {
          return "Locked out. Try again in " . (60 - (time() - $lastAttempt)) . " seconds.";
      } else {
          $_SESSION['failedAuth'] = 0;
      }
  }

  $attemptTime = date("Y-m-d H:i:s");
  if ($user && password_verify($password, $user['password'])) {
      $_SESSION['auth'] = true;
      $_SESSION['username'] = ucwords($username);
      unset($_SESSION['failedAuth']);
      $this->logAttempt($username, 'good', $attemptTime);
      header('Location: /views/home.php');
      exit;
  } else {
      $_SESSION['failedAuth'] = ($_SESSION['failedAuth'] ?? 0) + 1;
      $_SESSION['lastFailed'] = time();
      $this->logAttempt($username, 'bad', $attemptTime);
      header('Location: /views/login.php');
      exit;
  }
     


}
  private function logAttempt($username, $result, $time) {
      $db = db_connect();
      $stmt = $db->prepare("INSERT INTO log (username, attempt, time) VALUES (:username, :attempt, :time)");
      $stmt->execute([
          ':username' => $username,
          ':attempt' => $result,
          ':time' => $time
      ]);
  }
}