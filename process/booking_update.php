<?php
session_start();
require_once "../config/database.php";

// Admin ellenőrzés ide is kell!

if (isset($_GET['id']) && isset($_GET['action'])) {
    $id = $_GET['id'];
    $action = $_GET['action'];

    try {
        if ($action == 'confirm') {
            $stmt = $pdo->prepare("UPDATE bookings SET status = 'confirmed' WHERE id = ?");
            $stmt->execute([$id]);
        } elseif ($action == 'delete') {
            $stmt = $pdo->prepare("DELETE FROM bookings WHERE id = ?");
            $stmt->execute([$id]);
        }
        
        header("Location: ../public/dashboard.php?msg=success");
    } catch (PDOException $e) {
        die("Hiba: " . $e->getMessage());
    }
}