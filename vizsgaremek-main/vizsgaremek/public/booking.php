<?php
session_start();
require_once "../config/database.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_booking'])) {
    $name = $_POST['customer_name'];
    $phone = $_POST['phone'];
    $service_id = $_POST['service_id'];
    $date = $_POST['booking_date'];
    $time = $_POST['booking_time'];
    $user_id = $_SESSION['user_id'] ?? null;

    // Csak azokat az oszlopokat hagyd meg, amik tényleg léteznek a tábládban!
$stmt = $pdo->prepare("INSERT INTO bookings (user_id, service_id, customer_name, booking_date, booking_time) VALUES (?, ?, ?, ?, ?)");

// A telefonszám ($phone) kikerül a tömbből is:
if ($stmt->execute([$user_id, $service_id, $name, $date, $time])) {
    header("Location: index.php?status=success#booking");
    exit();
}
}
?>