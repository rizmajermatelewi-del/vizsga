<?php
$host = 'db'; 
$db   = 'vizsgaremek'; 
$user = 'root';
$pass = 'root'; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("DB hiba: " . $e->getMessage());
}