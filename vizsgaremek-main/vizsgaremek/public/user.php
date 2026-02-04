<?php
session_start();
require_once "../config/database.php";

if (!isset($_SESSION['user_id'])) { header("Location: ./login.php?error=4"); exit; }
$user_id = $_SESSION['user_id'];

// --- 1. ADATOK LEKÉRÉSE (Ez legyen az első, hogy mentésnél ismerjük a régi nevet!) ---
$userStmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$userStmt->execute([$user_id]);
$userData = $userStmt->fetch(PDO::FETCH_ASSOC);

// --- 2. AJAX VOUCHER ELLENŐRZŐ ---
if (isset($_GET['ajax_check_voucher'])) {
    header('Content-Type: application/json');
    $code = trim($_GET['code'] ?? '');
    $today = date('Y-m-d');
    $stmt = $pdo->prepare("SELECT amount, expiry_date, status FROM vouchers WHERE code = ?");
    $stmt->execute([$code]);
    $voucher = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode([
        'valid' => ($voucher && $voucher['status'] === 'active' && $voucher['expiry_date'] >= $today),
        'amount' => $voucher ? number_format($voucher['amount'], 0, ',', ' ') : 0,
        'expiry' => $voucher['expiry_date'] ?? ''
    ]);
    exit;
}

// --- 3. ADATOK MENTÉSE LOGIKA ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $old_username = $userData['username']; 
    $new_username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $new_pass = trim($_POST['new_password']);
    $confirm_pass = trim($_POST['confirm_password']);

    try {
        $pdo->beginTransaction();

        // 1. Felhasználói adatok frissítése (Csak az alap mezőkkel, amik tuti léteznek)
        $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ?, phone = ? WHERE id = ?");
        $stmt->execute([$new_username, $email, $phone, $user_id]);

        // 2. Foglalások szinkronizálása az új névvel
        $updateBookings = $pdo->prepare("UPDATE bookings SET customer_name = ? WHERE customer_name = ?");
        $updateBookings->execute([$new_username, $old_username]);

        // 3. Jelszóváltás (ha megadták és egyeznek)
        if (!empty($new_pass) && $new_pass === $confirm_pass) {
            $hashed = password_hash($new_pass, PASSWORD_DEFAULT);
            $pdo->prepare("UPDATE users SET password = ? WHERE id = ?")->execute([$hashed, $user_id]);
        }

        $pdo->commit();
        $_SESSION['username'] = $new_username;

        header("Location: user.php?status=updated");
        exit;
    } catch (Exception $e) {
        $pdo->rollBack();
        die("Hiba történt: " . $e->getMessage());
    }
}

// --- 4. FOGLALÁSOK LEKÉRÉSE ---
$today = date('Y-m-d');
$upStmt = $pdo->prepare("SELECT b.*, s.name as s_name FROM bookings b JOIN services s ON b.service_id = s.id 
                         WHERE (b.customer_name = ? OR b.phone = ?) AND b.booking_date >= ? ORDER BY b.booking_date ASC");
$upStmt->execute([$userData['username'], $userData['phone'], $today]);
$upcoming = $upStmt->fetchAll(PDO::FETCH_ASSOC);

$pastStmt = $pdo->prepare("SELECT b.*, s.name as s_name FROM bookings b JOIN services s ON b.service_id = s.id 
                          WHERE (b.customer_name = ? OR b.phone = ?) AND b.booking_date < ? ORDER BY b.booking_date DESC LIMIT 5");
$pastStmt->execute([$userData['username'], $userData['phone'], $today]);
$past = $pastStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AB MASSZÁZS | Profilom</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@200;400;600&family=Shippori+Mincho:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/user_style.css">
    <style>
        :root { --j-bg: #f4f1ec; --j-card: #ffffff; --j-gold: #b8924a; --j-dark: #1a1a1a; --j-border: rgba(26,26,26,0.08); }
        body { background: var(--j-bg); font-family: 'Plus Jakarta Sans', sans-serif; color: var(--j-dark); }
        .zen-toast { position: fixed; top: 20px; right: 20px; z-index: 1060; background: var(--j-dark); color: white; padding: 15px 30px; border-left: 4px solid var(--j-gold); box-shadow: 0 10px 30px rgba(0,0,0,0.1); display: none; animation: slideIn 0.5s ease forwards; }
        .profile-hero { padding: 80px 0 40px; }
        .zen-card { background: var(--j-card); border: none; padding: 35px; box-shadow: 10px 10px 40px rgba(0,0,0,0.02); margin-bottom: 25px; }
        .section-label { font-size: 0.65rem; text-transform: uppercase; letter-spacing: 3px; color: var(--j-gold); margin-bottom: 20px; display: block; }
        .ritual-upcoming { border-left: 3px solid var(--j-gold); background: #fffcf8; padding: 15px; margin-bottom: 15px; }
        .ritual-past { opacity: 0.6; border-left: 1px solid var(--j-border); padding: 10px 15px; margin-bottom: 10px; font-size: 0.9rem; }
        #voucher-card { display: none; background: linear-gradient(135deg, #1a1a1a 0%, #333 100%); color: white; padding: 25px; border-radius: 15px; position: relative; overflow: hidden; margin-top: 20px; font-family: 'Shippori Mincho', serif; }
        .btn-zen { background: var(--j-dark); color: white; border: none; padding: 12px 25px; text-transform: uppercase; font-size: 0.7rem; letter-spacing: 2px; transition: 0.4s; }
        .btn-zen:hover { background: var(--j-gold); }
        .form-control { border-radius: 0; border: 1px solid var(--j-border); padding: 12px; background: transparent; }
        @keyframes slideIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
    </style>
</head>
<body>

<?php include 'assets/user_navbar.php'; ?>
<?php if (isset($_GET['status']) && $_GET['status'] === 'updated'): ?>
<div id="zenToast" class="zen-toast">Profil sikeresen frissítve</div>
<?php endif; ?>

<main class="container">
    <header class="profile-hero text-center text-lg-start">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <span class="section-label">Üdvözöljük,</span>
                <h1 style="font-family: 'Shippori Mincho', serif; font-size: 3.5rem;"><?= htmlspecialchars($userData['username']) ?></h1>
            </div>
            <div class="col-lg-4 text-lg-end">
                <button class="btn-zen" data-bs-toggle="modal" data-bs-target="#editModal">Profil kezelése</button>
            </div>
        </div>
    </header>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="zen-card">
                <span class="section-label">Közelgő masszázsok</span>
                <?php if($upcoming): foreach($upcoming as $u): ?>
                    <div class="ritual-upcoming d-flex justify-content-between align-items-center">
                        <div>
                            <div class="small opacity-50"><?= $u['booking_date'] ?> • <?= $u['booking_time'] ?></div>
                            <div class="fw-bold"><?= $u['s_name'] ?></div>
                        </div>
                        <i class="fa-solid fa-leaf text-gold opacity-50"></i>
                    </div>
                <?php endforeach; else: echo "<p class='small italic opacity-50'>Nincs tervezett rituálé.</p>"; endif; ?>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="zen-card">
                <span class="section-label">Fiók adatok</span>
                <p class="small mb-1"><i class="fa-envelope me-2 opacity-50"></i> <?= htmlspecialchars($userData['email']) ?></p>
                <p class="small mb-1"><i class="fa-phone me-2 opacity-50"></i> <?= htmlspecialchars($userData['phone']) ?></p>
                <p class="small mb-1"><i class="fa-lock me-2 opacity-50"></i> <?= htmlspecialchars($userData['created_at']) ?></p>
            </div>
            
            <div class="zen-card">
                <span class="section-label">Voucher ellenőrzés</span>
                <div class="input-group mb-3">
                    <input type="text" id="v_code" class="form-control" placeholder="AB-26-XZ9K" aria-label="Voucher kód">
                    <button class="btn-zen" onclick="checkVoucher()">OK</button>
                </div>
                <div id="voucher-card">
                    <div class="small opacity-50">EGYENLEG</div>
                    <div class="h3 my-2" id="v_amt">0 Ft</div>
                    <div class="small" id="v_exp">EXP: 0000-00-00</div>
                </div>
            </div>
        </div>
    </div>
</main>

<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 rounded-0">
            <form method="POST">
                <div class="modal-body p-5">
                    <h5 class="mb-4" style="font-family: 'Shippori Mincho', serif;">Profil módosítása</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="small text-muted">Felhasználónév</label>
                            <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($userData['username']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="small text-muted">E-mail</label>
                            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($userData['email']) ?>" required>
                        </div>
                        <div class="col-md-12">
                            <label class="small text-muted">Telefonszám</label>
                            <input type="tel" name="phone" class="form-control" value="<?= htmlspecialchars($userData['phone'] ?? '') ?>">
                        </div>
                        <div class="col-md-12">
                            <hr>
                            <span class="small text-muted">Jelszó módosítása (ha a mezőt üresen hagyja, a jelszó nem változik)</span>
                        </div>
                        <div class="col-md-6">
                            <label class="small text-muted">Új jelszó</label>
                            <input type="password" name="new_password" class="form-control" placeholder="Hagyja üresen, ha nem módosítja">
                        </div>
                        <div class="col-md-6">
                            <label class="small text-muted">Jelszó megerősítése</label>
                            <input type="password" name="confirm_password" class="form-control">
                        </div>
                    </div>
                    <button type="submit" name="update_profile" class="btn-zen w-100 mt-4">Változtatások mentése</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../config/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function checkVoucher() {
    const code = document.getElementById('v_code').value;
    fetch(`user.php?ajax_check_voucher=1&code=${code}`)
        .then(r => r.json())
        .then(d => {
            const card = document.getElementById('voucher-card');
            if(d.valid) {
                document.getElementById('v_amt').innerText = d.amount + ' Ft';
                document.getElementById('v_exp').innerText = 'EXP: ' + d.expiry;
                card.style.display = 'block';
            } else { alert("Érvénytelen kód."); }
        });
}
window.onload = function() {
    const urlParams = new URLSearchParams(window.location.search);
    
    // Csak akkor fut le, ha a status értéke pontosan 'updated'
    if (urlParams.get('status') === 'updated') {
        const toast = document.getElementById('zenToast');
        if (toast) {
            toast.style.display = 'block';
            
            // 3 másodperc után eltüntetjük a feliratot
            setTimeout(() => { 
                toast.style.animation = 'slideOut 0.5s ease forwards';
                setTimeout(() => { toast.style.display = 'none'; }, 500);
            }, 3000);
        }

        // --- A TRÜKK: URL tisztítása ---
        // Eltávolítja a ?status=updated részt az URL-ből frissítés nélkül
        const newUrl = window.location.pathname;
        window.history.replaceState({}, document.title, newUrl);
    }
}

</script>
</body>
</html>