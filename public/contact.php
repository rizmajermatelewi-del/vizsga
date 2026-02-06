<?php
session_start();
require_once "../config/database.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim($_POST['c_name'] ?? '');
    $email   = trim($_POST['c_email'] ?? '');
    $phone   = trim($_POST['c_phone'] ?? '');
    $message = trim($_POST['c_message'] ?? '');

    if (!empty($name) && !empty($email) && !empty($message)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO messages (name, email, phone, message, created_at) VALUES (?, ?, ?, ?, NOW())");
            $stmt->execute([$name, $email, $phone, $message]);

            // Egységesített status kód
            header("Location: index.php?status=message_sent#contact");
exit;
        } catch (PDOException $e) {
            die("Hiba: " . $e->getMessage());
        }
    } else {
        header("Location: index.php?status=error#contact");
        exit;
    }
}