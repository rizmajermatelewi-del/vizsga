<?php
session_start();
require_once "../config/database.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $service = $_POST['r_service'];
    $rating = $_POST['rating'];
    $message = $_POST['r_message'];
    $user_id = $_SESSION['user_id'] ?? null;

    try {
        $stmt = $pdo->prepare("INSERT INTO reviews (user_id, service_name, rating, comment, created_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt->execute([$user_id, $service, $rating, $message]);
        
        // Egységesített status kód
        header("Location: index.php?status=review_success#reviews");
exit;
    } catch (PDOException $e) {
        die("Hiba a mentés során: " . $e->getMessage());
    }
}