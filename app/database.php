<?php
function db_connect() {
$host = '3y6cb.h.filess.io';
$db = 'COSC4806_rollsettle';
$user = 'COSC4806_rollsettle';
$pass = $_ENV['DB_PASS'];
$port = "3305";

$dsn = "mysql:host=$host;dbname=$db;port=$port";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
}