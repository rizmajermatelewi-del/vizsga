<?php
require_once "../config/database.php";

$date = $_GET['date'] ?? '';

if (!$date) {
    echo json_encode([]);
    exit;
}

// 1. Lekérjük a már foglalt időpontokat az adott napra
$stmt = $pdo->prepare("SELECT booking_time FROM bookings WHERE booking_date = ? AND status != 'rejected'");
$stmt->execute([$date]);
$booked_slots = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Tisztítsuk meg az időpontokat (HH:mm formátumra)
$booked_slots = array_map(function($t) {
    return substr($t, 0, 5);
}, $booked_slots);

// 2. Generáljuk a lehetséges idősávokat (pl. óránként 08:00-tól 17:00-ig)
$available_slots = [];
$start = new DateTime('08:00');
$end = new DateTime('17:00');
$interval = new DateInterval('PT1H'); // 1 órás etapok

$current = clone $start;
while ($current <= $end) {
    $time_string = $current->format('H:i');
    
    // 3. Megnézzük, hogy az adott óra benne van-e a foglaltak között
    $is_booked = in_array($time_string, $booked_slots);
    
    // Ha a mai napot nézzük, a múltbéli órákat is tiltsuk le
    $is_past = ($date == date('Y-m-d') && $time_string < date('H:i'));

    $available_slots[] = [
        'time' => $time_string,
        'available' => !$is_booked && !$is_past
    ];
    
    $current->add($interval);
}

header('Content-Type: application/json');
echo json_encode($available_slots);