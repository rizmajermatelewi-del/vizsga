<?php
$host = "localhost"; 
$db   = "vizsgaremek"; 
$user = "root"; // Ez a MySQL belső neve
$pass = "";     // Hagyd üresen! Ez nem a te admin jelszavad!

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    die("DB hiba: " . $e->getMessage()); 
}
?>