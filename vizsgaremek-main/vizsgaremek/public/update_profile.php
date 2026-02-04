<?php
require_once "../config/database.php";
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ./login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $username = htmlspecialchars(trim($_POST['username']));
    $email = htmlspecialchars(trim($_POST['email']));
    $new_pass = $_POST['new_password'] ?? '';
    $conf_pass = $_POST['confirm_password'] ?? '';

    try {
        // 1. Alapadatok frissítése
        $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
        $stmt->execute([$username, $email, $user_id]);

        // 2. Jelszómódosítás, ha ki van töltve mindkét mező
        if (!empty($new_pass)) {
            if ($new_pass === $conf_pass) {
                if (strlen($new_pass) >= 6) {
                    $hashed_pass = password_hash($new_pass, PASSWORD_DEFAULT);
                    $pwdStmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
                    $pwdStmt->execute([$hashed_pass, $user_id]);
                    $msg = "success_pwd";
                } else {
                    $msg = "error_short";
                }
            } else {
                $msg = "error_mismatch";
            }
        } else {
            $msg = "success_profile";
        }
    } catch (PDOException $e) {
        $msg = "error_db";
    }

    header("Location: user.php?status=" . $msg);
    exit;
}