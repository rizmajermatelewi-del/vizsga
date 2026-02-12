<?php
$host = 'db'; 
$db   = 'ab_masszazs_db'; // Átírva 'vizsgaremek'-ről erre!
$user = 'root';
$pass = 'root'; // Docker-compose-ban megadott jelszó

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("DB hiba: " . $e->getMessage());
}