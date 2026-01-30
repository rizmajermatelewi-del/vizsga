<?php
require_once "../config/database.php";
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Adatok begyűjtése
    $service_id   = $_POST['service_id'] ?? null;
    $booking_date = $_POST['booking_date'] ?? null;
    $booking_time = $_POST['booking_time'] ?? null;
    $customer_name = htmlspecialchars($_POST['customer_name'] ?? 'Vendég');
    $user_id      = $_SESSION['user_id'] ?? null;

    // Alapvető ellenőrzés
    if (!$service_id || !$booking_date || !$booking_time) {
        $_SESSION['message'] = "Kérjük, töltsön ki minden mezőt!";
        $_SESSION['msg_type'] = "danger";
        header("Location: index.php#booking");
        exit;
    }

    try {
        // 1. Foglaltság ellenőrzése
        $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM bookings WHERE booking_date = ? AND booking_time = ? AND status != 'rejected'");
        $checkStmt->execute([$booking_date, $booking_time]);
        
        if ($checkStmt->fetchColumn() > 0) {
            $_SESSION['message'] = "Sajnáljuk, ez az időpont már foglalt.";
            $_SESSION['msg_type'] = "warning";
            header("Location: index.php#booking");
            exit;
        }

        // 2. Mentés az adatbázisba
        $stmt = $pdo->prepare("INSERT INTO bookings (user_id, service_id, customer_name, booking_date, booking_time, status, created_at) 
                               VALUES (?, ?, ?, ?, ?, 'pending', NOW())");
        
        $stmt->execute([
            $user_id, 
            $service_id, 
            $customer_name, 
            $booking_date, 
            $booking_time
        ]);

        // 3. Siker üzenet beállítása
        $_SESSION['message'] = "Foglalását rögzítettük! Hamarosan visszajelzünk.";
        $_SESSION['msg_type'] = "success";
        header("Location: index.php#booking");
        exit;

    } catch (PDOException $e) {
        // Hiba esetén részletesebb leírás a fejlesztéshez
        $_SESSION['message'] = "Adatbázis hiba: " . $e->getMessage();
        $_SESSION['msg_type'] = "danger";
        header("Location: index.php#booking");
        exit;
    }
} else {
    // Ha nem POST-tal érkeznek
    header("Location: index.php");
    exit;
}
?>