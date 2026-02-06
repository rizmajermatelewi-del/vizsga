<?php
require_once "../config/database.php"; 
session_start();

// Csak admin férhet hozzá
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Nincs jogosultsága.']);
    exit;
}

// Beállítjuk a választ JSON-re
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['booking_id'] ?? null;
    $name = $_POST['customer_name'] ?? '';
    $service = $_POST['service_id'] ?? '';
    $date = $_POST['booking_date'] ?? '';
    $time = $_POST['booking_time'] ?? '';

    // Egyszerű validáció
    if (empty($name) || empty($service) || empty($date) || empty($time)) {
        echo json_encode(['status' => 'error', 'message' => 'Minden mezőt ki kell tölteni!']);
        exit;
    }

    try {
        if (!empty($id)) {
            // SZERKESZTÉS (Update)
            $stmt = $pdo->prepare("UPDATE bookings SET customer_name = ?, service_id = ?, booking_date = ?, booking_time = ? WHERE id = ?");
            $success = $stmt->execute([$name, $service, $date, $time, $id]);
        } else {
            // ÚJ FOGLALÁS (Insert)
            $stmt = $pdo->prepare("INSERT INTO bookings (customer_name, service_id, booking_date, booking_time, status) VALUES (?, ?, ?, ?, 'approved')");
            $success = $stmt->execute([$name, $service, $date, $time]);
        }

        if ($success) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Adatbázis hiba történt.']);
        }

    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Szerver hiba: ' . $e->getMessage()]);
    }
    exit;
}

// Ha közvetlenül nyitnák meg a fájlt
echo json_encode(['status' => 'error', 'message' => 'Érvénytelen kérés.']);