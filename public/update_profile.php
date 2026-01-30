<?php
require_once "../config/database.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $new_pass = $_POST['new_password'] ?? '';

    try {
        $sql = "UPDATE users SET username = ?, email = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$username, $email, $user_id]);

        if (!empty($new_pass)) {
            $hashed = password_hash($new_pass, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->execute([$hashed, $user_id]);
        }

        $_SESSION['username'] = $username;
        
        // POP-UP ÜZENET BEÁLLÍTÁSA
        $_SESSION['message'] = "Profil adatai sikeresen frissítve.";
        $_SESSION['msg_type'] = "success";

        header("Location: user.php");
    } catch (PDOException $e) {
        $_SESSION['message'] = "Hiba történt a mentés során.";
        $_SESSION['msg_type'] = "danger";
        header("Location: user.php");
    }
    exit;
}