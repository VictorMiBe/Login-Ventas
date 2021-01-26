<?php

    $server = 'localhost';
    $username = 'Admin';
    $password = 'JSaMDORH8vsRBdVT';
    $database = 'ventasdos';

    try {
        $conn = new PDO("mysql:host=$server;dbname=$database;", $username, $password);
    } catch (PDOException $e) {
        die('Connection Failed: ' . $e->getMessage());
    }

?>