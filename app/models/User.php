<?php

class User {

    public $username;
    public $password;
    public $auth = false;

    public function create($username, $password) {
        $db = db_connect();
        $username = strtolower($username);
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $statement = $db->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
        $statement->bindValue(':username', $username);
        $statement->bindValue(':password', $hashed);
        $statement->execute();
        return true;
    }
    
    public function authenticate($username, $password) {
    $username = strtolower($username);
        $db = db_connect();

        // Check lockout
        if (isset($_SESSION['lockout_until']) && time() < $_SESSION['lockout_until']) {
            // Log the locked-out attempt
            $log = new Log();
            $log->add($username, 'locked');
            header('Location: /login?locked=1');
            die;
        }

        $statement = $db->prepare("select * from users WHERE username = :name;");
        $statement->bindValue(':name', $username);
        $statement->execute();
        $rows = $statement->fetch(PDO::FETCH_ASSOC);

        $log = new Log();

        if ($rows && password_verify($password, $rows['password'])) {
            $_SESSION['auth'] = 1;
            $_SESSION['username'] = ucwords($username);
            unset($_SESSION['failedAuth']);
            unset($_SESSION['lockout_until']);
            $log->add($username, 'good');
            header('Location: /home');
            die;
        } else {
            $log->add($username, 'bad');
            if(isset($_SESSION['failedAuth'])) {
                $_SESSION['failedAuth']++;
            } else {
                $_SESSION['failedAuth'] = 1;
            }
            // Lockout after 3 failed attempts
            if ($_SESSION['failedAuth'] >= 3) {
                $_SESSION['lockout_until'] = time() + 60;
            }
            header('Location: /login?fail=1');
            die;
        }
    }

}
