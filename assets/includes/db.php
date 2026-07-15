<?php
    $host = 'localhost';
    $db = 'company_booking';
    $user = 'root';
    $pass = '';
    $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";

    try {
            $pdo = new PDO($dsn, $user, $pass);
        } 
    catch (PDOException $e) {
        die("DB Error: " . $e->getMessage());
        }

    session_start();
?>