<?php
session_start();
require_once "../config/database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $v_id = $_SESSION['user_id'] ?? null;
    $s_id = $_POST['service_id'] ?? null;
    
    // Ezeknek az űrlapban is szerepelniük kell!
    $name = $_POST['name'] ?? ''; 
    $email = $_POST['email'] ?? ''; 
    $phone = $_POST['phone'] ?? ''; 
    $date = $_POST['date'] ?? ''; 
    $time = $_POST['time'] ?? ''; 

    try {
        $sql = "INSERT INTO bookings (user_id, service_id, customer_name, email, phone, booking_date, booking_time, status) 
                VALUES (:v_id, :s_id, :c_name, :email, :phone, :b_date, :b_time, 'pending')";

        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([
            'v_id'   => $v_id,
            's_id'   => $s_id,
            'c_name' => $name,
            'email'  => $email,
            'phone'  => $phone,
            'b_date' => $date,
            'b_time' => $time
        ]);

        if ($result) {
            header("Location: ../public/booking.php?success=1");
            exit();
        }
    } catch (PDOException $e) {
        die("Adatbázis hiba: " . $e->getMessage());
    }
}