<?php
require_once "../config/database.php";

$date = $_GET['date'] ?? null;
$taken_slots = [];

if ($date) {
    // Lekérjük az adott napon már lefoglalt időpontokat
    $stmt = $pdo->prepare("SELECT booking_time FROM bookings WHERE booking_date = ? AND status != 'cancelled'");
    $stmt->execute([$date]);
    $taken_slots = $stmt->fetchAll(PDO::FETCH_COLUMN);
}

// JSON formátumban küldjük vissza a JS-nek
header('Content-Type: application/json');
echo json_encode($taken_slots);