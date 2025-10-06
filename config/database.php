<?php 

    $dns = 'mysql:host=localhost;dbname=libraryms;port=3307;charset=utf8mb4';
    $username = 'root';
    $password = '';
    try {
        $pdo = new PDO($dns, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die('Connection failed: ' . $e->getMessage());
    }

