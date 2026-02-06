<?php
require_once "../config/database.php"; 
session_start();

// Admin jogosultság ellenőrzése
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') { 
    header("Location: ../public/login.php?error=4"); 
    exit; 
}

// Mentés kezelése
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'save') {
    $stmt = $pdo->prepare("UPDATE bookings SET customer_name = ?, service_id = ?, booking_date = ?, booking_time = ?, status = ? WHERE id = ?");
    $stmt->execute([
        $_POST['customer_name'],
        $_POST['service_id'],
        $_POST['booking_date'],
        $_POST['booking_time'],
        $_POST['status'],
        $_POST['id']
    ]);
    header("Location: dashboard.php?success=1");
    exit;
}

// Statisztikák lekérése
$bookings_today = $pdo->query("SELECT COUNT(*) FROM bookings WHERE booking_date = CURDATE() AND status != 'deleted'")->fetchColumn();
$active_vouchers = $pdo->query("SELECT COUNT(*) FROM vouchers WHERE status = 'active'")->fetchColumn();
$services_count = $pdo->query("SELECT COUNT(*) FROM services")->fetchColumn();

// Ütemezés lekérése (Következő 50 bejegyzés)
$schedule = $pdo->query("SELECT b.*, s.name as s_name, TIME_FORMAT(b.booking_time, '%H:%i') as t_only 
                         FROM bookings b JOIN services s ON b.service_id = s.id 
                         WHERE b.booking_date >= CURDATE() AND b.status != 'deleted'
                         ORDER BY b.booking_date ASC, b.booking_time ASC LIMIT 50")->fetchAll();

$services_list = $pdo->query("SELECT * FROM services ORDER BY name ASC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AB MASSZÁZS | Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600&family=Shippori+Mincho:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/admin_style.css">
    <style>
        /* DASHBOARD SPECIFIKUS STÍLUSOK */
        .stat-card-link .j-card {
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            border-radius: 0;
            border: 1px solid var(--j-border);
            padding: 2rem 1.5rem;
            background: var(--j-white);
        }
        .stat-card-link:hover .j-card {
            transform: translateY(-5px);
            border-color: var(--j-accent);
            box-shadow: 0 15px 35px rgba(142, 125, 111, 0.1);
        }
        
        .booking-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.2rem 25px;
            background: var(--j-white);
            border-bottom: 1px solid var(--j-border);
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .booking-row:hover {
            background: #fdfaf7;
            padding-left: 35px;
        }

        .btn-control {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            border: 1px solid var(--j-border);
            background: transparent;
            color: var(--j-accent);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: 0.3s;
        }
        .booking-row:hover .btn-control {
            background: var(--j-accent);
            color: white;
            border-color: var(--j-accent);
        }

        /* Állapot színek */
        .status-badge {
            font-size: 0.65rem;
            letter-spacing: 1px;
            padding: 6px 12px;
            text-transform: uppercase;
            font-weight: 700;
        }

        .fancy-search-wrapper {
            margin-bottom: 2rem;
        }

        @media (max-width: 768px) {
            .booking-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
            .booking-row > .d-flex:last-child {
                width: 100%;
                justify-content: space-between;
            }
        }
    </style>
</head>
<body class="japandi-bg">

<?php include "assets/admin_navbar.php"; ?>

<div class="container pb-5">
    <div class="row g-4 mb-5 text-center">
        <div class="col-md-4">
            <a href="calendar.php" class="stat-card-link text-decoration-none">
                <div class="j-card h-100">
                    <p class="small text-muted text-uppercase mb-2" style="letter-spacing: 2px;">Mai foglalások</p>
                    <span class="stat-num d-block display-4 fw-bold" style="font-family: 'Shippori Mincho', serif; color: var(--j-dark);"><?= $bookings_today ?></span>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="vouchers.php" class="stat-card-link text-decoration-none">
                <div class="j-card h-100">
                    <p class="small text-muted text-uppercase mb-2" style="letter-spacing: 2px;">Aktív utalványok</p>
                    <span class="stat-num d-block display-4 fw-bold" style="font-family: 'Shippori Mincho', serif; color: var(--j-dark);"><?= $active_vouchers ?></span>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="services.php" class="stat-card-link text-decoration-none">
                <div class="j-card h-100">
                    <p class="small text-muted text-uppercase mb-2" style="letter-spacing: 2px;">Szolgáltatások</p>
                    <span class="stat-num d-block display-4 fw-bold" style="font-family: 'Shippori Mincho', serif; color: var(--j-dark);"><?= $services_count ?></span>
                </div>
            </a>
        </div>
    </div>

    <div class="fancy-search-wrapper">
        <div class="fancy-search-container w-100" style="max-width: 100%;">
            <i class="fas fa-search search-icon-fancy"></i>
            <input type="text" id="mainSearch" class="search-input-fancy" 
                   placeholder="Kit keresünk ma?" 
                   onkeyup="filterSchedule()">
        </div>
    </div>

    <div class="j-card p-0 overflow-hidden shadow-sm border-0 bg-white">
        <div class="p-4 border-bottom d-flex justify-content-between align-items-center" style="background: var(--j-soft);">
            <h5 class="m-0 brand fw-bold" style="color: var(--j-accent); letter-spacing: 2px;">ÜTEMEZÉS</h5>
            <span class="badge rounded-pill px-3 py-2" style="background: var(--j-white); color: var(--j-dark); border: 1px solid var(--j-border); font-size: 0.6rem; letter-spacing: 1px;">KÖVETKEZŐ 50 BEJEGYZÉS</span>
        </div>
        
        <div style="max-height: 700px; overflow-y: auto;">
            <?php 
            $current_date = "";
            foreach($schedule as $b): 
                if ($current_date !== $b['booking_date']): 
                    $current_date = $b['booking_date'];
                    $label = ($current_date == date('Y-m-d')) ? "MAI NAP" : date('Y. m. d.', strtotime($current_date));
            ?>
                <div class="day-header" style="background: var(--j-soft); padding: 12px 25px; font-size: 0.7rem; font-weight: 700; letter-spacing: 2px; color: var(--j-accent); border-bottom: 1px solid var(--j-border); border-top: 1px solid var(--j-border);">
                    <?= $label ?>
                </div>
            <?php endif; ?>
            
            <div class="booking-row booking-item" onclick='editBooking(<?= json_encode($b) ?>)' data-search="<?= strtolower($b['customer_name'] . ' ' . $b['s_name']) ?>">
                <div class="d-flex align-items-center">
                    <span class="fw-bold me-4" style="min-width: 70px; color: var(--j-accent); font-size: 1.1rem;"><?= $b['t_only'] ?></span>
                    <div>
                        <div class="fw-bold" style="font-size: 1.05rem;"><?= htmlspecialchars($b['customer_name']) ?></div>
                        <div class="small text-muted text-uppercase fw-semibold" style="font-size:0.65rem; letter-spacing: 0.5px;"><?= htmlspecialchars($b['s_name']) ?></div>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-4">
                    <?php 
                        $status_style = "background: rgba(142, 125, 111, 0.1); color: var(--j-accent);";
                        if($b['status'] == 'approved') $status_style = "background: rgba(72,187,120,0.15); color: #2f855a;";
                        if($b['status'] == 'rejected') $status_style = "background: rgba(229,62,62,0.15); color: #c53030;";
                    ?>
                    <span class="status-badge badge rounded-0" style="<?= $status_style ?>">
                        <?= strtoupper($b['status']) ?>
                    </span>
                    <button class="btn-control"><i class="fas fa-pen-nib"></i></button>
                </div>
            </div>
            <?php endforeach; ?>
            
            <?php if(empty($schedule)): ?>
                <div class="p-5 text-center text-muted">Nincs rögzített időpont a közeljövőben.</div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="modal fade" id="bookingModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 p-3">
            <div class="modal-body">
                <form method="POST">
                    <input type="hidden" name="action" value="save">
                    <input type="hidden" name="id" id="b_id">
                    
                    <h3 class="brand mb-4 text-center">Időpont módosítása</h3>
                    
                    <div class="mb-3">
                        <label class="small text-muted mb-1 fw-bold">VENDÉG NEVE</label>
                        <input type="text" name="customer_name" id="b_name" class="form-control" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="small text-muted mb-1 fw-bold">KEZELÉS TÍPUSA</label>
                        <select name="service_id" id="b_service" class="form-select">
                            <?php foreach($services_list as $s): ?>
                                <option value="<?= $s['id'] ?>"><?= $s['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="small text-muted mb-1 fw-bold">ÁLLAPOT</label>
                        <select name="status" id="b_status" class="form-select">
                            <option value="pending">PENDING</option>
                            <option value="approved">APPROVED</option>
                            <option value="rejected">REJECTED</option>
                            <option value="deleted">DELETED (Törlés)</option>
                        </select>
                    </div>

                    <div class="row mb-4">
                        <div class="col-6">
                            <label class="small text-muted mb-1 fw-bold">DÁTUM</label>
                            <input type="date" name="booking_date" id="b_date" class="form-control">
                        </div>
                        <div class="col-6">
                            <label class="small text-muted mb-1 fw-bold">IDŐPONT</label>
                            <input type="time" name="booking_time" id="b_time" class="form-control">
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2 pt-2">
                        <button type="submit" class="btn btn-zen flex-grow-1">ADATOK MENTÉSE</button>
                        <button type="button" class="btn btn-outline-dark rounded-0 px-4" data-bs-dismiss="modal">MÉGSE</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function filterSchedule() {
        let input = document.getElementById('mainSearch').value.toLowerCase();
        let items = document.getElementsByClassName('booking-item');
        
        for (let item of items) {
            let text = item.getAttribute('data-search');
            item.style.display = text.includes(input) ? "flex" : "none";
        }
    }

    function editBooking(data) {
        document.getElementById('b_id').value = data.id;
        document.getElementById('b_name').value = data.customer_name;
        document.getElementById('b_service').value = data.service_id;
        document.getElementById('b_status').value = data.status;
        document.getElementById('b_date').value = data.booking_date;
        document.getElementById('b_time').value = data.t_only;
        new bootstrap.Modal(document.getElementById('bookingModal')).show();
    }
</script>

</body>
</html>