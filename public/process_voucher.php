<?php
require_once "../config/database.php";
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST['v_name']);
    $email = filter_var($_POST['v_email'], FILTER_SANITIZE_EMAIL);
    $amount = intval($_POST['v_amount']);
    
    // 1. EGYEDI KÓD GENERÁLÁSA (Pl: ZEN-26-A1B2)
    $chars = "ABCDEFGHJKLMNPQRSTUVWXYZ23456789"; // O és 0 kihagyva a félreértések elkerülésére
    $code = "ZEN-" . date('y') . "-" . substr(str_shuffle($chars), 0, 4);
    
    // 2. LEJÁRAT (1 év múlva)
    $expiry_date = date('Y-m-d', strtotime('+1 year'));

    try {
        // 3. MENTÉS AZ ADATBÁZISBA
        $stmt = $pdo->prepare("INSERT INTO vouchers (code, amount, status, expiry_date, created_at) VALUES (?, ?, 'active', ?, NOW())");
        $stmt->execute([$code, $amount, $expiry_date]);

        // 4. JAPANDI STÍLUSÚ EMAIL KÜLDÉSE
        $to = $email;
        $subject = "=?UTF-8?B?".base64_encode("Az Ön ZEN SPA ajándékutalványa")."?=";
        
        $message = "
        <html>
        <body style='background-color: #fdfcfb; font-family: sans-serif; padding: 40px; color: #2d2a26;'>
            <div style='max-width: 600px; margin: auto; background: white; border: 1px solid #e2ddd9; padding: 40px; text-align: center;'>
                <h1 style='font-family: serif; letter-spacing: 4px; color: #8e7d6a; text-transform: uppercase;'>Zen Spa</h1>
                <div style='margin: 30px 0; border-top: 1px solid #e2ddd9; border-bottom: 1px solid #e2ddd9; padding: 20px 0;'>
                    <p style='text-transform: uppercase; font-size: 12px; letter-spacing: 2px; color: #8e7d6a;'>Ajándékutalvány</p>
                    <h2 style='font-size: 32px; margin: 10px 0;'> " . number_format($amount, 0, ',', ' ') . " Ft </h2>
                    <p style='font-family: monospace; font-size: 20px; background: #f4f1ee; padding: 10px; display: inline-block; letter-spacing: 5px;'>$code</p>
                </div>
                <p>Kedves <strong>$name</strong>,</p>
                <p style='font-size: 14px; color: #666;'>Köszönjük, hogy a nyugalmat választotta ajándékul. Az utalvány beváltható bármely kezelésünkre a megadott kód felmutatásával.</p>
                <p style='font-size: 12px; color: #8e7d6a; margin-top: 30px;'>Érvényes: $expiry_date</p>
                <hr style='border: 0; border-top: 1px solid #e2ddd9; margin: 30px 0;'>
                <p style='font-size: 11px; text-transform: uppercase; letter-spacing: 1px;'>1051 Budapest, Zen köz 1. | zenspa.hu</p>
            </div>
        </body>
        </html>";

        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8\r\n";
        $headers .= "From: ZEN SPA <noreply@zenspa.hu>\r\n";

        mail($to, $subject, $message, $headers);

        // 5. VISSZAJELZÉS A FELHASZNÁLÓNAK
$_SESSION['message'] = "Az utalványt sikeresen rögzítettük és elküldtük.";
$_SESSION['msg_type'] = "success";
header("Location: index.php#vouchers");
exit;

    } catch (PDOException $e) {
        die("Hiba történt: " . $e->getMessage());
    }
}