<?php
require_once "../config/database.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = $_POST['amount'];
    $name = $_POST['customer_name'] ?? 'Kedves Vendégünk';
    $code = "ZEN-" . rand(100, 999) . "-" . strtoupper(substr(md5(time()), 0, 4));
    $date = date('Y.m.p.');
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>ZEN SPA Voucher</title>
    <link href="https://fonts.googleapis.com/css2?family=Shippori+Mincho:wght@500&display=swap" rel="stylesheet">
    <style>
        body { background: #f4f1ee; font-family: 'Helvetica', sans-serif; display: flex; flex-direction: column; align-items: center; padding: 50px; }
        .voucher-card {
            width: 800px; height: 400px; background: white; border: 1px solid #e2ddd9;
            display: flex; position: relative; box-shadow: 0 20px 40px rgba(0,0,0,0.05);
        }
        .left { width: 300px; background: #2d2a26; color: white; padding: 40px; display: flex; flex-direction: column; justify-content: space-between; }
        .right { flex: 1; padding: 60px; position: relative; }
        h1 { font-family: 'Shippori Mincho', serif; font-size: 2.5rem; margin: 0; color: #2d2a26; }
        .amount { font-size: 3rem; color: #8e7d6a; margin: 20px 0; font-weight: bold; }
        .code { font-family: monospace; letter-spacing: 3px; font-size: 1.2rem; background: #f8f6f4; padding: 10px; display: inline-block; }
        .footer-info { font-size: 0.8rem; color: #999; margin-top: 40px; }
        .no-print { margin-bottom: 20px; }
        @media print { .no-print { display: none; } body { padding: 0; background: white; } .voucher-card { box-shadow: none; border: 1px solid #000; } }
    </style>
</head>
<body>
    <div class="no-print">
        <button onclick="window.print()" style="padding: 15px 30px; background: #2d2a26; color: white; border: none; cursor: pointer; letter-spacing: 2px;">UTALVÁNY LETÖLTÉSE (PDF)</button>
        <a href="../index.php" style="margin-left: 20px; color: #8e7d6a;">Vissza a főoldalra</a>
    </div>

    <div class="voucher-card">
        <div class="left">
            <div>
                <h2 style="letter-spacing: 5px; margin: 0;">ZEN SPA</h2>
                <p style="font-size: 0.7rem; opacity: 0.6; text-transform: uppercase;">The Art of Stillness</p>
            </div>
            <div style="font-size: 0.8rem; opacity: 0.8;">
                Budapest, Zen tér 1.<br>www.zenspa.hu
            </div>
        </div>
        <div class="right">
            <h1>Ajándékutalvány</h1>
            <p style="color: #8e7d6a; text-transform: uppercase; letter-spacing: 2px; font-size: 0.8rem;">Felhasználható bármely kezelésünkre</p>
            <div class="amount"><?= number_format($amount, 0, ',', ' ') ?> Ft</div>
            <div class="code"><?= $code ?></div>
            <div class="footer-info">
                Érvényes: <?= date('Y.m.d', strtotime('+1 year')) ?>-ig | Kiállítva: <?= $date ?>
            </div>
        </div>
    </div>
</body>
</html>
<?php 
}
?>