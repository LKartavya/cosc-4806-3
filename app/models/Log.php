<?php

class Log {
    public function add($username, $result) {
        $db = db_connect();
        $statement = $db->prepare("INSERT INTO logs (username, attempt, time) VALUES (:username, :attempt, NOW())");
        $statement->bindValue(':username', $username);
        $statement->bindValue(':attempt', $result); 
        $statement->execute();
    }
}
