<?php
session_start();
require_once "../config/database.php";

if (!isset($_SESSION['user_id'])) { 
    header("Location: ./login.php?error=4"); 
    exit; 
}
$user_id = $_SESSION['user_id'];

$userStmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$userStmt->execute([$user_id]);
$userData = $userStmt->fetch(PDO::FETCH_ASSOC);

if (isset($_GET['ajax_check_voucher'])) {
    header('Content-Type: application/json');
    $code = trim($_GET['code'] ?? '');
    $today = date('Y-m-d');
    
    $stmt = $pdo->prepare("SELECT amount, expiry_date, status, user_id FROM vouchers WHERE code = ?");
    $stmt->execute([$code]);
    $voucher = $stmt->fetch(PDO::FETCH_ASSOC);

    $isValid = ($voucher && $voucher['status'] === 'active' && $voucher['expiry_date'] >= $today);
    $isMine = ($voucher && $voucher['user_id'] == $user_id);

    echo json_encode([
        'valid' => $isValid,
        'is_mine' => $isMine,
        'amount' => $voucher ? number_format($voucher['amount'], 0, ',', ' ') : 0,
        'expiry' => $voucher['expiry_date'] ?? '',
        'message' => $isValid ? 'Érvényes utalvány' : 'Ez a kód már nem használható vagy lejárt'
    ]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_booking'])) {
    $b_id = $_POST['booking_id'];
    $checkStmt = $pdo->prepare("SELECT booking_date, booking_time FROM bookings WHERE id = ? AND (customer_name = ? OR phone = ?)");
    $checkStmt->execute([$b_id, $userData['username'], $userData['tel']]);
    $booking = $checkStmt->fetch();

    if ($booking) {
        $booking_datetime = strtotime($booking['booking_date'] . ' ' . $booking['booking_time']);
        if (($booking_datetime - time()) >= 86400) {
            $delStmt = $pdo->prepare("DELETE FROM bookings WHERE id = ?");
            $delStmt->execute([$b_id]);
            header("Location: user.php?status=cancelled");
            exit;
        }
    }
    header("Location: user.php?error=late_cancellation");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $old_username = $userData['username']; 
    $new_username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $tel = preg_replace('/[^\d+]/', '', $_POST['tel']); 
    $new_pass = trim($_POST['new_password']);
    $confirm_pass = trim($_POST['confirm_password']);

    try {
        $pdo->beginTransaction();
        $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ?, tel = ? WHERE id = ?");
        $stmt->execute([$new_username, $email, $tel, $user_id]);

        $updateBookings = $pdo->prepare("UPDATE bookings SET customer_name = ? WHERE customer_name = ?");
        $updateBookings->execute([$new_username, $old_username]);

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

$today = date('Y-m-d');
$upStmt = $pdo->prepare("SELECT b.*, s.name as s_name FROM bookings b 
                         JOIN services s ON b.service_id = s.id 
                         WHERE (b.customer_name = ? OR b.tel = ?) AND b.booking_date >= ? 
                         ORDER BY b.booking_date ASC");
$upStmt->execute([$userData['username'], $userData['tel'], $today]);
$upcoming = $upStmt->fetchAll(PDO::FETCH_ASSOC);

function formatPhoneNumber($tel) {
    $tel = preg_replace('/[^\d+]/', '', $tel);
    if (strlen($tel) >= 11 && str_starts_with($tel, '+36')) {
        return substr($tel, 0, 3) . ' ' . substr($tel, 3, 2) . ' ' . substr($tel, 5, 3) . ' ' . substr($tel, 8);
    }
    return $tel;
}
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
        body { background: var(--j-bg); font-family: 'Plus Jakarta Sans', sans-serif; color: var(--j-dark); overflow-x: hidden; }
        .profile-hero { padding: 80px 0 40px; }
        .zen-card { background: var(--j-card); border: none; padding: 35px; box-shadow: 10px 10px 40px rgba(0,0,0,0.02); position: relative; }
        .section-label { font-size: 0.65rem; text-transform: uppercase; letter-spacing: 3px; color: var(--j-gold); margin-bottom: 20px; display: block; }
        .ritual-upcoming { border-left: 3px solid var(--j-gold); background: #fffcf8; padding: 15px; margin-bottom: 15px; }
        .voucher-group { display: flex; align-items: stretch; height: 48px; }
        .voucher-group .form-control { border-radius: 0; border: 1px solid var(--j-border); background: transparent; height: 100%; margin: 0; }
        .voucher-group .btn-zen { border-radius: 0; height: 100%; padding: 0 25px; display: flex; align-items: center; justify-content: center; min-width: 70px; margin: 0; }
        .info-footer { margin-top: auto; padding-top: 20px; border-top: 1px solid var(--j-border); display: flex; align-items: flex-start; gap: 12px; }
        .info-footer i { color: var(--j-gold); font-size: 0.85rem; margin-top: 3px; }
        .info-footer p { font-size: 0.75rem; line-height: 1.6; color: #888; margin: 0; }
        .info-footer b { color: #666; text-transform: uppercase; letter-spacing: 1px; }
        .btn-zen { background: var(--j-dark); color: white; border: none; text-transform: uppercase; font-size: 0.7rem; letter-spacing: 2px; transition: 0.4s; font-weight: 600; cursor: pointer; }
        .btn-zen:hover { background: var(--j-gold); color: white; }
        .form-control:focus { box-shadow: none; border-color: var(--j-gold); }
        .pass-input { background-color: #f0f7ff !important; padding-right: 45px !important; }
        .toggle-eye-icon { position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; color: var(--j-gold); z-index: 10; opacity: 0.7; }
        .zen-field-group { position: relative; border-bottom: 1px solid var(--j-border); padding: 5px 0; transition: all 0.3s ease; }
        .zen-field-group:focus-within { border-bottom-color: var(--j-gold); }
        .zen-input-minimal { width: 100%; border: none !important; background: transparent !important; padding: 8px 0 !important; font-family: 'Plus Jakarta Sans', sans-serif; outline: none !important; box-shadow: none !important; font-size: 1rem; color: var(--j-dark); }
        .zen-icon-inline { font-size: 0.85rem; color: var(--j-gold); margin-right: 12px; opacity: 0.7; }
        .shake { animation: shake 0.4s cubic-bezier(.36,.07,.19,.97) both; }
        @keyframes shake { 10%, 90% { transform: translate3d(-1px, 0, 0); } 20%, 80% { transform: translate3d(2px, 0, 0); } 30%, 50%, 70% { transform: translate3d(-4px, 0, 0); } 40%, 60% { transform: translate3d(4px, 0, 0); } }
        #voucher-card { display: none; background: #eaddca; color: var(--j-dark); padding: 25px; margin-top: 20px; }
    </style>
</head>
<body>

<?php include 'assets/user_navbar.php'; ?>

<main class="container mb-5">
    <header class="profile-hero text-center text-lg-start">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <span class="section-label">Üdvözöljük,</span>
                <h1 style="font-family: 'Shippori Mincho', serif; font-size: 3.5rem;"><?= htmlspecialchars($userData['username']) ?></h1>
            </div>
            <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                <button class="btn-zen px-4 py-3" data-bs-toggle="modal" data-bs-target="#editModal">Profil kezelése</button>
            </div>
        </div>
    </header>

    <div class="row g-4 d-flex align-items-stretch">
        <div class="col-lg-7">
            <div class="zen-card h-100 d-flex flex-column">
                <span class="section-label">Közelgő masszázsok</span>
                <div class="flex-grow-1 mb-4">
                    <?php if(!empty($upcoming)): foreach($upcoming as $u): 
                        $booking_datetime = strtotime($u['booking_date'] . ' ' . $u['booking_time']);
                        $can_cancel = (($booking_datetime - time()) >= 86400);
                    ?>
                        <div class="ritual-upcoming d-flex justify-content-between align-items-center">
                            <div>
                                <div class="small opacity-50"><?= $u['booking_date'] ?> • <?= substr($u['booking_time'], 0, 5) ?></div>
                                <div class="fw-bold"><?= htmlspecialchars($u['s_name']) ?></div>
                            </div>
                            <div class="text-end">
                                <?php if($can_cancel): ?>
                                    <button class="btn btn-sm text-muted opacity-50 p-0 border-0" 
                                            onclick="confirmCancel(<?= $u['id'] ?>, '<?= htmlspecialchars($u['s_name']) ?>', '<?= $u['booking_date'] ?>')"
                                            style="font-size: 0.65rem; letter-spacing: 1px;">LEMONDÁS</button>
                                <?php else: ?>
                                    <span class="small" style="font-size: 0.65rem; color: var(--j-gold); letter-spacing: 1px;"><i class="fa-solid fa-circle-check me-1"></i> HAMAROSAN</span>
                                <?php endif; ?>
                                <i class="fa-solid fa-leaf text-gold opacity-50 ms-2"></i>
                            </div>
                        </div>
                    <?php endforeach; else: echo "<p class='small italic opacity-50'>Nincs tervezett rituálé.</p>"; endif; ?>
                </div>

                <div class="info-footer">
                    <i class="fa-solid fa-circle-info"></i>
                    <p><b>Lemondás:</b> A Te életedben is bekövetkezhet olyan váratlan esemény, ami a kezelés
                    lemondására kényszerít.
                    Ezt kérlek jelezd minél előbb!
                    Amennyiben a kezelés előtt 48 órán belül mondod le, akkor a következő
                    alkalommal 50%-os felárat számolok fel.
                    Ha a kezelés előtt 24 órán belül mondod le vagy le sem mondod, akkor a
                    következő alkalommal a teljes költség felszámolásra kerül.
                    Ha fertőző beteg vagy, ne gyere el hozzám! Illetve, ha ez a kezelés alatt derül ki,
                    az bármikor megszakítható és a teljes összeget ki kell fizetni!</p>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="d-flex flex-column h-100">
                <div class="zen-card mb-4">
                    <span class="section-label">Fiók adatok</span>
                    <div class="small mb-3"><i class="fa-solid fa-envelope fa-fw me-2 opacity-50"></i> <?= htmlspecialchars($userData['email']) ?></div>
                    <div class="small mb-3">
                        <i class="fa-solid fa-phone fa-fw me-2 opacity-50"></i> 
                        <?= !empty($userData['tel']) ? htmlspecialchars(formatPhoneNumber($userData['tel'])) : 'Nincs megadva' ?>
                    </div>
                    <div class="small"><i class="fa-solid fa-calendar-check fa-fw me-2 opacity-50"></i> Regisztráció: <?= htmlspecialchars($userData['created_at']) ?></div>
                </div>

                <div class="zen-card flex-grow-1">
                    <span class="section-label">Utalványaim & Kedvezmények</span>
                    <div class="my-vouchers mb-4">
                        <?php
                        $myVouchersStmt = $pdo->prepare("SELECT * FROM vouchers WHERE user_id = ? AND status = 'active'");
                        $myVouchersStmt->execute([$user_id]);
                        $myVouchers = $myVouchersStmt->fetchAll();
                        if ($myVouchers): foreach ($myVouchers as $v): ?>
                            <div class="d-flex justify-content-between align-items-center p-3 mb-2" style="background: #fafafa; border: 1px solid var(--j-border);">
                                <div>
                                    <small class="text-uppercase" style="letter-spacing: 1px; font-size: 0.6rem;">Kód: <?= $v['code'] ?></small>
                                    <div class="fw-bold"><?= number_format($v['amount'], 0, ',', ' ') ?> Ft</div>
                                </div>
                                <div class="text-end small"><div class="opacity-50" style="font-size: 0.7rem;">Lejárat:</div><?= $v['expiry_date'] ?></div>
                            </div>
                        <?php endforeach; else: echo "<p class='small opacity-50 italic'>Nincs aktív utalványa.</p>"; endif; ?>
                    </div>

                    <div class="voucher-check-area pt-3 border-top">
                        <label class="small text-muted mb-2">Ajándékutalvány regisztrálása</label>
                        <div class="voucher-group">
                            <input type="text" id="v_code" class="form-control" placeholder="Kód..." onkeypress="handleEnter(event)">
                            <button class="btn-zen" onclick="checkVoucher()">OK</button>
                        </div>
                        <div id="voucher-card">
                            <div class="small opacity-50" style="font-size: 0.6rem;">UTALVÁNY ÉRTÉKE</div>
                            <div class="h3 my-2" id="v_amt" style="color: var(--j-gold); font-family: 'Shippori Mincho', serif;">0 Ft</div>
                            <div class="small" id="v_exp">EXP: 0000-00-00</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<div class="modal fade" id="cancelModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-0" style="background: #fffcf8;">
            <div class="modal-body p-5 text-center">
                <span class="section-label mb-3">Időpont lemondása</span>
                <h5 id="cancelTargetName" style="font-family: 'Shippori Mincho', serif;">-</h5>
                <p id="cancelTargetDate" class="small text-muted mb-4">-</p>
                <p class="small mb-4">Biztosan lemondja a lefoglalt rituálét?<br><span class="text-danger">A folyamat nem vonható vissza.</span></p>
                <form action="user.php" method="POST">
                    <input type="hidden" name="booking_id" id="cancel_booking_id">
                    <div class="d-flex gap-3">
                        <button type="button" class="btn-zen w-100 py-3" style="background: transparent; color: var(--j-dark); border: 1px solid var(--j-border);" data-bs-dismiss="modal">Mégsem</button>
                        <button type="submit" name="delete_booking" class="btn-zen w-100 py-3" style="background: #b40808ff;">Lemondás</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 rounded-0">
            <form id="profileEditForm"> 
                <div class="modal-body p-5">
                    <h5 class="mb-4" style="font-family: 'Shippori Mincho', serif;">Profil módosítása</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="small text-muted mb-1">Felhasználónév</label>
                            <input type="text" id="edit_name" name="username" class="form-control" value="<?= htmlspecialchars($userData['username']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="small text-muted mb-1">E-mail</label>
                            <input type="email" id="edit_email" name="email" class="form-control" value="<?= htmlspecialchars($userData['email']) ?>" required>
                        </div>
                        <div class="col-md-12">
                            <label class="small text-muted mb-1">Telefonszám</label>
                            <div class="zen-field-group">
                                <div class="d-flex align-items-center">
                                    <i class="fa-solid fa-phone-flip zen-icon-inline"></i>
                                    <input type="tel" name="tel" id="edit_tel" class="zen-input-minimal" value="<?= htmlspecialchars(formatPhoneNumber($userData['tel'] ?? '+36')) ?>" maxlength="15">
                                </div>
                            </div>
                        </div>
                        <div class="col-12 text-center my-3">
    <div style="font-size: 0.75rem; color: var(--j-gold); opacity: 0.8; font-style: italic; letter-spacing: 0.5px;">
        <i class="fa-solid fa-leaf me-1"></i> 
        A jelszó mezőket csak akkor töltse ki, ha meg szeretné változtatni jelenlegi jelszavát.
    </div>
</div>
                        <div class="col-md-6">
                            <label class="small text-muted mb-1">Új jelszó</label>
                            <input type="password" id="new_password" class="form-control" placeholder="********">
                        </div>
                        <div class="col-md-6">
                            <label class="small text-muted mb-1">Megerősítés</label>
                            <input type="password" id="confirm_password" class="form-control" placeholder="********">
                        </div>

                        <div class="col-12 text-center mt-5">
                            <button type="submit" class="btn-zen px-5 py-3">Változtatások mentése</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../config/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
async function apiCall(endpoint, data) {
    try {
        const response = await fetch(`api.php?request=${endpoint}`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });
        const result = await response.json();
        if (result.error) {
            alert(result.error);
            return null;
        }
        return result;
    } catch (e) {
        console.error("Hiba történt:", e);
        return null;
    }
}

function handleEnter(event) {
    if (event.key === "Enter") {
        event.preventDefault();
        checkVoucher();
    }
}

document.querySelectorAll('.toggle-eye-icon').forEach(eye => {
    eye.addEventListener('click', function() {
        const input = document.getElementById(this.getAttribute('data-target'));
        if (input.type === 'password') {
            input.type = 'text';
            this.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            input.type = 'password';
            this.classList.replace('fa-eye-slash', 'fa-eye');
        }
    });
});

function confirmCancel(id, name, date) {
    document.getElementById('cancel_booking_id').value = id;
    document.getElementById('cancelTargetName').innerText = name;
    document.getElementById('cancelTargetDate').innerText = date;
    const modal = new bootstrap.Modal(document.getElementById('cancelModal'));
    modal.show();
}

function checkVoucher() {
    const code = document.getElementById('v_code').value;
    const input = document.getElementById('v_code');
    const card = document.getElementById('voucher-card');

    if (code.length < 5) return;

    fetch(`api.php?request=vouchers_check&code=${code}`)
        .then(r => r.json())
        .then(data => {
            input.classList.remove('is-valid', 'is-invalid', 'shake');
            if (data.valid) {
                input.classList.add('is-valid');
                document.getElementById('v_amt').innerText = data.amount + ' Ft';
                document.getElementById('v_exp').innerText = 'Lejárat: ' + data.expiry;
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
                input.classList.add('is-invalid', 'shake');
                setTimeout(() => input.classList.remove('shake'), 500);
            }
        });
}

const editTel = document.getElementById('edit_tel');
if(editTel) {
    editTel.addEventListener('input', function(e) {
        let v = e.target.value.replace(/[^\d+]/g, '');
        if (!v.startsWith('+36')) {
            if(v.startsWith('06')) v = '+36' + v.substring(2);
            else v = '+36' + v;
        }
        let formatted = v;
        if (v.length > 3) formatted = v.substring(0, 3) + ' ' + v.substring(3, 5);
        if (v.length > 5) formatted += ' ' + v.substring(5, 8);
        if (v.length > 8) formatted += ' ' + v.substring(8, 12);
        e.target.value = formatted.trim();
    });
}

const profileForm = document.getElementById('profileEditForm');
if (profileForm) {
    profileForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const pass = document.getElementById('new_password').value;
        const confirm = document.getElementById('confirm_password').value;

        if (pass !== "" && pass !== confirm) {
            alert("A két jelszó nem egyezik meg!");
            return;
        }

        const data = {
            username: document.getElementById('edit_name').value,
            email: document.getElementById('edit_email').value,
            tel: document.getElementById('edit_tel').value,
            password: pass
        };

        const res = await apiCall('update_profile', data);
        if (res) {
            alert(res.success); 
            document.querySelector('h1').innerText = data.username;
            const modal = bootstrap.Modal.getInstance(document.getElementById('editModal'));
            if(modal) modal.hide();
        }
    });
}
</script>
</body>
</html>