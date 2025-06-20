<?php

$host = '3y6cb.h.filess.io';
$db = 'COSC4806_rollsettle';

function db_connect() {
    try { 
        $dbh = new PDO('mysql:host=' . DB_HOST . ';port='. DB_PORT . ';dbname=' . DB_DATABASE, DB_USER, DB_PASS);
        return $dbh;
    } catch (PDOException $e) {
        //We should set a global variable here so we know the DB is down
    }
}
