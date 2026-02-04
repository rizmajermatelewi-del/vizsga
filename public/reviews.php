<?php
session_start();
require_once "config/database.php"; // Az adatbázis kapcsolatod

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['r_name'];
    $service = $_POST['r_service'];
    $rating = $_POST['rating'];
    $message = $_POST['r_message'];
    $user_id = $_SESSION['user_id'] ?? null; // Ha nincs belépve, marad null

    try {
        $stmt = $pdo->prepare("INSERT INTO reviews (user_id, service_name, rating, comment) VALUES (?, ?, ?, ?)");
        $stmt->execute([$user_id, $service, $rating, $message]);
        
        header("Location: index.php?status=review_success#reviews");
    } catch (PDOException $e) {
        die("Hiba a mentés során: " . $e->getMessage());
    }
}