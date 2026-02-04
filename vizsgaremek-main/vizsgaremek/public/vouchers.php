<?php
session_start();
require_once "../config/database.php";

/**
 * ZEN SPA - Japandi Voucher Logic v3.0
 */

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['v_amount'])) {
    
    // 1. Adatok begyűjtése (az index.php bővített mezőiből)
    $recipient_name = $_POST['v_recipient'] ?? 'Szerette';
    $buyer_email    = $_POST['v_buyer_email'] ?? null;
    $buyer_phone    = $_POST['v_buyer_phone'] ?? null;
    $amount         = intval($_POST['v_amount']);
    
    // 2. Automata User ID kezelése (Figyelem: SESSION['user_id']-t használunk!)
    $current_user_id = $_SESSION['user_id'] ?? null; 

    // 3. Generálás
    $voucher_code = "AB-26-" . strtoupper(substr(md5(uniqid()), 0, 6));
    $expiry_date  = date('Y-m-d', strtotime('+1 year'));

    try {
        /* FONTOS: Feltételezzük, hogy a 'vouchers' táblád így néz ki:
           id (auto_inc), user_id, code, recipient_name, amount, expiry_date, status, buyer_email, buyer_phone
        */
        $sql = "INSERT INTO vouchers (user_id, code, recipient_name, amount, expiry_date, status, buyer_email, buyer_phone) 
                VALUES (?, ?, ?, ?, ?, 'active', ?, ?)";
        
        $stmt = $pdo->prepare($sql);
        
        $params = [
            $current_user_id, 
            $voucher_code, 
            $recipient_name, 
            $amount, 
            $expiry_date,
            $buyer_email,
            $buyer_phone
        ];

        if ($stmt->execute($params)) {
            // Siker esetén visszairányítás a főoldalra a Modal-hoz
            header("Location: index.php?v_status=success&code=$voucher_code&name=" . urlencode($recipient_name) . "#vouchers");
            exit();
        }
    } catch (PDOException $e) {
        // Ha hibát kapsz, valószínűleg hiányoznak az oszlopok az adatbázisból!
        die("Hiba történt a rögzítéskor. Ellenőrizze az adatbázis mezőit! <br> Részletek: " . $e->getMessage());
    }
} else {
    header("Location: index.php");
    exit();
}