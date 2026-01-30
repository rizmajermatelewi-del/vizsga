<?php
require_once "../config/database.php"; 
session_start();

// Admin ellenőrzés
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../public/login.php?error=4");
    exit;
}

// --- ADATKEZELÉS (Mentés/Törlés) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['type'] === 'booking') {
        if ($_POST['action'] === 'save') {
            $stmt = $pdo->prepare("UPDATE bookings SET customer_name=?, service_id=?, booking_date=?, booking_time=?, status=? WHERE id=?");
            $stmt->execute([$_POST['customer_name'], $_POST['service_id'], $_POST['booking_date'], $_POST['booking_time'], $_POST['status'], $_POST['id']]);

            if ($_POST['status'] === 'approved') {
                $b_id = $_POST['id'];
                $data = $pdo->query("SELECT b.*, s.name as s_name FROM bookings b JOIN services s ON b.service_id = s.id WHERE b.id = $b_id")->fetch();
                if (!empty($data['email'])) {
                    $to = $data['email'];
                    $subject = "=?UTF-8?B?".base64_encode("Időpontja jóváhagyva - ZEN SPA")."?=";
                    $message = "<html><body style='font-family: sans-serif; background-color: #f4f1ee; padding: 20px;'><div style='background: white; padding: 30px; border: 1px solid #e2ddd9; max-width: 500px; margin: auto;'><h2 style='color: #8e7d6a;'>ZEN SPA</h2><p>Kedves <strong>" . htmlspecialchars($data['customer_name']) . "</strong>!</p><p>Foglalását visszaigazoltuk.</p></div></body></html>";
                    $headers = "MIME-Version: 1.0\r\nContent-type:text/html;charset=UTF-8\r\nFrom: ZEN SPA <noreply@zenspa.hu>\r\n";
                    mail($to, $subject, $message, $headers);
                }
            }
        } elseif ($_POST['action'] === 'delete') {
            $stmt = $pdo->prepare("DELETE FROM bookings WHERE id=?");
            $stmt->execute([$_POST['id']]);
        }
    }
    header("Location: dashboard.php?success=1"); 
    exit;
}

// --- LEKÉRDEZÉSEK ---
$bookings_today = $pdo->query("SELECT COUNT(*) FROM bookings WHERE booking_date = CURDATE()")->fetchColumn();
$active_vouchers = $pdo->query("SELECT COUNT(*) FROM vouchers WHERE status = 'active'")->fetchColumn();
$services_count = $pdo->query("SELECT COUNT(*) FROM services")->fetchColumn();

$stmt = $pdo->prepare("SELECT b.*, s.name as s_name, TIME_FORMAT(b.booking_time, '%H:%i') as t_only FROM bookings b JOIN services s ON b.service_id = s.id WHERE b.booking_date >= CURDATE() ORDER BY b.booking_date ASC, b.booking_time ASC");
$stmt->execute();
$schedule = $stmt->fetchAll();

$services_list = $pdo->query("SELECT * FROM services ORDER BY name ASC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ZEN SPA | Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600&family=Shippori+Mincho:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root { 
            --j-bg: #fdfcfb; --j-dark: #2d2a26; --j-accent: #8e7d6a; --j-border: #e2ddd9; --j-white: #ffffff; --j-text-muted: #8c8884;
        }
        body.dark-theme { 
            --j-bg: #121212; --j-dark: #fdfcfb; --j-border: #2d2d2d; --j-white: #1e1e1e; --j-text-muted: #b0aba6;
        }

        body { background: var(--j-bg); color: var(--j-dark); font-family: 'Plus Jakarta Sans', sans-serif; transition: 0.4s ease; }
        .brand { font-family: 'Shippori Mincho', serif; letter-spacing: 4px; }
        
        /* NAVBAR FIX */
        .admin-nav { background: var(--j-white); border-bottom: 1px solid var(--j-border); padding: 0 2rem; min-height: 70px; display: flex; align-items: center; }
        .nav-link { color: var(--j-dark) !important; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 2px; font-weight: 600; padding: 10px 15px; }
        .nav-link:hover, .nav-link.active { color: var(--j-accent) !important; }

        /* KÁRTYÁK */
        .j-card { background: var(--j-white); border: 1px solid var(--j-border); padding: 2.5rem; border-radius: 0; transition: 0.3s; }
        .stat-card-link { text-decoration: none; color: inherit; display: block; height: 100%; }
        .stat-card-link:hover .j-card { border-color: var(--j-accent); transform: translateY(-5px); }
        .stat-num { font-size: 3.5rem; font-family: 'Shippori Mincho', serif; color: var(--j-accent); display: block; }

        /* NAPTÁR IDŐVONAL */
        .calendar-box { background: var(--j-white); border: 1px solid var(--j-border); }
        .day-header { background: var(--j-bg); padding: 10px 20px; font-size: 0.75rem; font-weight: 700; color: var(--j-accent); text-transform: uppercase; letter-spacing: 2px; border-bottom: 1px solid var(--j-border); border-top: 1px solid var(--j-border); }
        
        .booking-row { 
            padding: 1.2rem 2rem; border-bottom: 1px solid var(--j-border); 
            display: flex; align-items: center; justify-content: space-between; 
            transition: all 0.3s ease; cursor: pointer; background: var(--j-white);
        }
        .booking-row:hover { background: var(--j-accent) !important; color: white !important; }
        .booking-row:hover .text-muted, .booking-row:hover .fa-pen-nib { color: rgba(255,255,255,0.8) !important; opacity: 1 !important; }

        /* KERESŐ */
        .search-wrapper { position: relative; margin-bottom: 3rem; }
        .search-input { width: 100%; padding: 1.2rem 3.5rem; border: 1px solid var(--j-border); border-radius: 0; background: var(--j-white); color: var(--j-dark); }
        .search-icon { position: absolute; left: 1.5rem; top: 50%; transform: translateY(-50%); color: var(--j-accent); }

        .btn-zen { background: var(--j-dark); color: var(--j-bg); border-radius: 0; border: none; padding: 12px 30px; text-transform: uppercase; letter-spacing: 2px; font-size: 0.75rem; font-weight: 600; }
        .text-muted { color: var(--j-text-muted) !important; }
    </style>
</head>
<body>

<nav class="admin-nav sticky-top shadow-sm">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <h5 class="m-0 me-5 fw-bold brand text-uppercase">Zen Admin</h5>
            <div class="d-none d-md-flex">
                <a href="dashboard.php" class="nav-link active">Dashboard</a>
                <a href="services.php" class="nav-link">Kezelések</a>
                <a href="vouchers.php" class="nav-link">Utalványok</a>
            </div>
        </div>
        <div class="d-flex align-items-center">
            <button onclick="toggleTheme()" class="btn px-3" id="themeToggle"><i class="fas fa-moon"></i></button>
            <a href="../public/logout.php" class="nav-link text-danger small"><i class="fas fa-power-off"></i></a>
        </div>
    </div>
</nav>

<div class="container py-5">
    <div class="row g-4 mb-5 text-center">
        <div class="col-md-4">
            <a href="#calendar-view" class="stat-card-link">
                <div class="j-card">
                    <p class="small text-muted text-uppercase mb-1">Mai foglalások</p>
                    <span class="stat-num"><?= $bookings_today ?></span>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="vouchers.php" class="stat-card-link">
                <div class="j-card">
                    <p class="small text-muted text-uppercase mb-1">Aktív utalványok</p>
                    <span class="stat-num"><?= $active_vouchers ?></span>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="services.php" class="stat-card-link">
                <div class="j-card">
                    <p class="small text-muted text-uppercase mb-1">Szolgáltatások</p>
                    <span class="stat-num"><?= $services_count ?></span>
                </div>
            </a>
        </div>
    </div>

    <div class="search-wrapper">
        <i class="fas fa-search search-icon"></i>
        <input type="text" id="mainSearch" class="search-input" placeholder="Vendég vagy szolgáltatás keresése...">
    </div>

    <div class="calendar-box shadow-sm" id="calendar-view">
        <div class="p-4 border-bottom bg-white d-flex justify-content-between">
            <h5 class="m-0 brand" style="font-size: 1rem;">Napi Ütemezés</h5>
        </div>
        <div style="max-height: 600px; overflow-y: auto;">
            <?php 
            $current_date = "";
            foreach($schedule as $b): 
                if ($current_date !== $b['booking_date']): 
                    $current_date = $b['booking_date'];
                    $label = ($current_date == date('Y-m-d')) ? "MA" : date('Y. m. d.', strtotime($current_date));
            ?>
                <div class="day-header"><?= $label ?></div>
            <?php endif; ?>
            
            <div class="booking-row booking-item" onclick='editBooking(<?= json_encode($b) ?>)' data-search="<?= strtolower($b['customer_name'] . ' ' . $b['s_name']) ?>">
                <div class="d-flex align-items-center">
                    <span class="fw-bold me-4" style="color: var(--j-accent); min-width: 60px;"><?= $b['t_only'] ?></span>
                    <div>
                        <div class="fw-bold"><?= htmlspecialchars($b['customer_name']) ?></div>
                        <div class="small text-muted text-uppercase" style="font-size: 0.65rem; letter-spacing: 1px;"><?= htmlspecialchars($b['s_name']) ?></div>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <span class="badge rounded-0 py-2 px-3" style="font-size: 0.6rem; background: <?= $b['status'] == 'approved' ? '#e8f5e9; color: #2e7d32' : ($b['status'] == 'rejected' ? '#ffebee; color: #c62828' : '#fff3e0; color: #ef6c00') ?>;">
                        <?= strtoupper($b['status']) ?>
                    </span>
                    <i class="fas fa-pen-nib opacity-25"></i>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<div class="modal fade" id="bookingModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-0 border-0 shadow-lg">
            <form method="POST" class="p-4">
                <input type="hidden" name="type" value="booking">
                <input type="hidden" name="id" id="b_id">
                <h4 class="brand mb-4 text-center">Foglalás szerkesztése</h4>
                <div class="mb-3">
                    <label class="small text-muted mb-1 fw-bold">VENDÉG</label>
                    <input type="text" name="customer_name" id="b_name" class="form-control rounded-0">
                </div>
                <div class="mb-3">
                    <label class="small text-muted mb-1 fw-bold">KEZELÉS</label>
                    <select name="service_id" id="b_service" class="form-select rounded-0">
                        <?php foreach($services_list as $s): ?>
                            <option value="<?= $s['id'] ?>"><?= $s['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="row mb-3">
                    <div class="col-6"><label class="small text-muted">DÁTUM</label><input type="date" name="booking_date" id="b_date" class="form-control rounded-0"></div>
                    <div class="col-6"><label class="small text-muted">IDŐ</label><input type="time" name="booking_time" id="b_time" class="form-control rounded-0"></div>
                </div>
                <div class="mb-4">
                    <label class="small text-muted">STÁTUSZ</label>
                    <select name="status" id="b_status" class="form-select rounded-0">
                        <option value="pending">Függőben</option>
                        <option value="approved">Jóváhagyva</option>
                        <option value="rejected">Elutasítva</option>
                    </select>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" name="action" value="delete" class="btn btn-outline-danger rounded-0" onclick="return confirm('Törlés?')">Törlés</button>
                    <button type="submit" name="action" value="save" class="btn btn-zen flex-grow-1">Mentés</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function toggleTheme() {
        document.body.classList.toggle('dark-theme');
        const icon = document.querySelector('#themeToggle i');
        icon.classList.toggle('fa-moon'); icon.classList.toggle('fa-sun');
    }

    document.getElementById('mainSearch').addEventListener('input', function(e) {
        const term = e.target.value.toLowerCase();
        document.querySelectorAll('.booking-item').forEach(el => {
            el.style.display = el.getAttribute('data-search').includes(term) ? 'flex' : 'none';
        });
    });

    function editBooking(data) {
        document.getElementById('b_id').value = data.id;
        document.getElementById('b_name').value = data.customer_name;
        document.getElementById('b_service').value = data.service_id;
        document.getElementById('b_date').value = data.booking_date;
        document.getElementById('b_time').value = data.t_only;
        document.getElementById('b_status').value = data.status;
        new bootstrap.Modal(document.getElementById('bookingModal')).show();
    }
</script>
</body>
</html>