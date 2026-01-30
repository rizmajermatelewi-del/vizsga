<?php
require_once "../config/database.php"; 
session_start();

// Admin ellenőrzés
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../public/login.php?error=4");
    exit;
}

// --- FOGLALÁS MŰVELETEK ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    try {
        if ($_POST['action'] === 'save') {
            if (!empty($_POST['id'])) {
                $stmt = $pdo->prepare("UPDATE bookings SET customer_name=?, service_id=?, booking_date=?, booking_time=?, status=? WHERE id=?");
                $stmt->execute([$_POST['customer_name'], $_POST['service_id'], $_POST['booking_date'], $_POST['booking_time'], $_POST['status'], $_POST['id']]);
            } else {
                $stmt = $pdo->prepare("INSERT INTO bookings (customer_name, service_id, booking_date, booking_time, status) VALUES (?, ?, ?, ?, 'approved')");
                $stmt->execute([$_POST['customer_name'], $_POST['service_id'], $_POST['booking_date'], $_POST['booking_time']]);
            }
        } elseif ($_POST['action'] === 'delete') {
            $stmt = $pdo->prepare("DELETE FROM bookings WHERE id=?");
            $stmt->execute([$_POST['id']]);
        }
        header("Location: calendar.php?m=" . ($_POST['m'] ?? date('n')) . "&y=" . ($_POST['y'] ?? date('Y')) . "&success=1"); 
        exit;
    } catch (PDOException $e) { $error = $e->getMessage(); }
}

// --- NAPTÁR LOGIKA ---
$month = isset($_GET['m']) ? (int)$_GET['m'] : date('n');
$year = isset($_GET['y']) ? (int)$_GET['y'] : date('Y');
$first_day = mktime(0, 0, 0, $month, 1, $year);
$days_in_month = date('t', $first_day);
$day_of_week = date('N', $first_day); // 1 (Hétfő) - 7 (Vasárnap)

$start_date = "$year-" . str_pad($month, 2, "0", STR_PAD_LEFT) . "-01";
$end_date = "$year-" . str_pad($month, 2, "0", STR_PAD_LEFT) . "-$days_in_month";

$stmt = $pdo->prepare("SELECT b.*, s.name as s_name, TIME_FORMAT(b.booking_time, '%H:%i') as t_only FROM bookings b JOIN services s ON b.service_id = s.id WHERE b.booking_date BETWEEN ? AND ? ORDER BY b.booking_time ASC");
$stmt->execute([$start_date, $end_date]);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

$all_bookings = [];
foreach ($results as $row) { $all_bookings[$row['booking_date']][] = $row; }
$services = $pdo->query("SELECT * FROM services ORDER BY name ASC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ZEN SPA | Naptár</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600&family=Shippori+Mincho:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --j-bg: #f8f6f4;
            --j-dark: #2d2a26;
            --j-accent: #8e7d6a;
            --j-border: #e2ddd9;
            --j-white: #ffffff;
            --j-soft: #faf9f8;
        }

        body { background: var(--j-bg); color: var(--j-dark); font-family: 'Plus Jakarta Sans', sans-serif; }
        
        .page-header { background: var(--j-white); border-bottom: 1px solid var(--j-border); padding: 1.5rem 0; margin-bottom: 2rem; }
        h1, h4 { font-family: 'Shippori Mincho', serif; font-weight: 700; }

        /* CALENDAR STYLE */
        .calendar-container { background: var(--j-white); border: 1px solid var(--j-border); box-shadow: 0 10px 30px rgba(0,0,0,0.02); }
        .calendar-grid { display: grid; grid-template-columns: repeat(7, 1fr); }
        
        .calendar-header-item { 
            text-align: center; padding: 1rem; background: var(--j-soft); 
            border-bottom: 1px solid var(--j-border); border-right: 1px solid var(--j-border);
            font-size: 0.7rem; text-transform: uppercase; letter-spacing: 2px; font-weight: 600; color: var(--j-accent);
        }

        .day-box { 
            min-height: 140px; background: var(--j-white); border-right: 1px solid var(--j-border); border-bottom: 1px solid var(--j-border);
            padding: 10px; position: relative; transition: 0.2s;
        }
        .day-box:hover { background: var(--j-soft); }
        .day-box.empty { background: #fdfdfd; }
        .day-num { font-size: 0.8rem; color: var(--j-accent); opacity: 0.5; display: block; margin-bottom: 8px; }
        .today { background: #fdfaf6 !important; }
        .today .day-num { opacity: 1; font-weight: bold; text-decoration: underline; }

        /* BOOKING TAGS */
        .booking-tag { 
            font-size: 0.65rem; padding: 5px 8px; margin-bottom: 4px; 
            background: var(--j-dark); color: white; border: none; border-radius: 2px;
            width: 100%; text-align: left; display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
            transition: 0.2s; cursor: pointer;
        }
        .booking-tag:hover { background: var(--j-accent); transform: translateX(2px); }
        .bonus-tag { background: transparent !important; color: var(--j-accent); border: 1px dashed var(--j-accent); font-weight: 600; text-align: center; }

        /* OVERLAY PANEL */
        #bonusOverlay {
            position: fixed; top: 0; right: -420px; width: 400px; height: 100%;
            background: var(--j-white); border-left: 1px solid var(--j-border);
            z-index: 1070; transition: 0.4s cubic-bezier(0.4, 0, 0.2, 1); padding: 2.5rem;
            box-shadow: -15px 0 40px rgba(0,0,0,0.05);
        }
        #bonusOverlay.active { right: 0; }
        .overlay-backdrop { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.2); backdrop-filter: blur(2px); display: none; z-index: 1060; }

        .bonus-item { background: var(--j-soft); border: 1px solid var(--j-border); padding: 1rem; margin-bottom: 0.8rem; cursor: pointer; transition: 0.2s; }
        .bonus-item:hover { border-color: var(--j-dark); background: white; }

        /* MODAL & BUTTONS */
        .modal-content { border-radius: 0; border: none; background: var(--j-bg); }
        .form-control, .form-select { border-radius: 0; border: 1px solid var(--j-border); padding: 0.7rem; }
        .btn-japandi { background: var(--j-dark); color: white; border-radius: 0; border: none; padding: 12px 25px; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 1px; }
        .btn-japandi:hover { background: var(--j-accent); }

        @media (max-width: 992px) {
            .calendar-grid { grid-template-columns: repeat(1, 1fr); }
            .calendar-header-item { display: none; }
            .day-box { min-height: auto; border-left: 1px solid var(--j-border); }
            .empty { display: none; }
            #bonusOverlay { width: 100%; right: -100%; }
        }
    </style>
</head>
<body>

<div class="page-header">
    <div class="container d-flex justify-content-between align-items-center">
        <div>
            <h1 class="m-0">Beosztás</h1>
            <p class="small text-muted m-0 text-uppercase" style="letter-spacing: 2px;">
                <?= $year ?>. <?= date('F', $first_day) ?>
            </p>
        </div>
        <div class="d-flex gap-2">
            <a href="dashboard.php" class="btn btn-outline-dark rounded-0 px-4">Dashboard</a>
            <div class="btn-group">
                <a href="?m=<?= ($month==1?12:$month-1) ?>&y=<?= ($month==1?$year-1:$year) ?>" class="btn btn-outline-dark rounded-0"><i class="fas fa-chevron-left"></i></a>
                <a href="?m=<?= ($month==12?1:$month+1) ?>&y=<?= ($month==12?$year+1:$year) ?>" class="btn btn-outline-dark rounded-0"><i class="fas fa-chevron-right"></i></a>
            </div>
        </div>
    </div>
</div>

<div class="container pb-5">
    <div class="calendar-container">
        <div class="calendar-grid">
            <div class="calendar-header-item">Hét</div>
            <div class="calendar-header-item">Ked</div>
            <div class="calendar-header-item">Sze</div>
            <div class="calendar-header-item">Csü</div>
            <div class="calendar-header-item">Pén</div>
            <div class="calendar-header-item">Szo</div>
            <div class="calendar-header-item">Vas</div>

            <?php
            // Üres helyek kitöltése
            for ($x = 1; $x < $day_of_week; $x++) echo '<div class="day-box empty"></div>';

            // Napok kirajzolása
            for ($day = 1; $day <= $days_in_month; $day++) {
                $date_str = sprintf('%04d-%02d-%02d', $year, $month, $day);
                $is_today = ($date_str == date('Y-m-d')) ? 'today' : '';
                
                echo '<div class="day-box '.$is_today.'">';
                echo '<span class="day-num">'.$day.'</span>';
                
                if (isset($all_bookings[$date_str])) {
                    $count = count($all_bookings[$date_str]);
                    foreach ($all_bookings[$date_str] as $index => $b) {
                        if ($index < 3) {
                            echo "<button class='booking-tag' onclick='prepareEdit(".json_encode($b).")'>
                                    <strong>".$b['t_only']."</strong> ".htmlspecialchars($b['customer_name'])."
                                  </button>";
                        } elseif ($index == 3) {
                            $rem = $count - 3;
                            $json_all = htmlspecialchars(json_encode($all_bookings[$date_str]), ENT_QUOTES, 'UTF-8');
                            echo "<button class='booking-tag bonus-tag' onclick='showBonus(\"$date_str\", $json_all)'>+ $rem további</button>";
                            break;
                        }
                    }
                }
                echo '</div>';
            }
            ?>
        </div>
    </div>
</div>

<div class="overlay-backdrop" id="backdrop" onclick="closeBonus()"></div>
<div id="bonusOverlay">
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
        <h4 class="m-0" id="bonusTitle">Időpontok</h4>
        <button class="btn-close" onclick="closeBonus()"></button>
    </div>
    <div id="bonusContent"></div>
</div>

<div class="modal fade" id="bookingModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content p-4 shadow-lg">
            <form method="POST">
                <input type="hidden" name="action" value="save">
                <input type="hidden" name="id" id="b_id">
                <input type="hidden" name="m" value="<?= $month ?>">
                <input type="hidden" name="y" value="<?= $year ?>">
                
                <div class="modal-header border-0 p-0 mb-4">
                    <h4 class="modal-title" id="m_title">Foglalás</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                
                <div class="modal-body p-0">
                    <div class="mb-3">
                        <label class="small fw-bold text-muted mb-1 uppercase" style="letter-spacing: 1px;">Vendég Neve</label>
                        <input type="text" name="customer_name" id="b_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="small fw-bold text-muted mb-1 uppercase" style="letter-spacing: 1px;">Szolgáltatás</label>
                        <select name="service_id" id="b_service" class="form-select">
                            <?php foreach($services as $s): ?>
                                <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="small fw-bold text-muted mb-1 uppercase">Dátum</label>
                            <input type="date" name="booking_date" id="b_date" class="form-control" required>
                        </div>
                        <div class="col-6">
                            <label class="small fw-bold text-muted mb-1 uppercase">Időpont</label>
                            <input type="time" name="booking_time" id="b_time" class="form-control" required>
                        </div>
                    </div>
                    <div class="mt-3" id="status_div" style="display:none;">
                        <label class="small fw-bold text-muted mb-1 uppercase">Státusz</label>
                        <select name="status" id="b_status" class="form-select">
                            <option value="pending">Várakozik</option>
                            <option value="approved">Elfogadva</option>
                            <option value="rejected">Elutasítva</option>
                        </select>
                    </div>
                </div>
                
                <div class="modal-footer border-0 p-0 mt-4 d-flex gap-2">
                    <button type="submit" name="action" value="delete" id="delete_btn" class="btn btn-outline-danger rounded-0 px-4" style="display:none;" onclick="return confirm('Biztosan törlöd?')">Törlés</button>
                    <button type="submit" class="btn btn-japandi flex-grow-1 shadow-sm">Adatok mentése</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
const bModal = new bootstrap.Modal(document.getElementById('bookingModal'));
const bonusOverlay = document.getElementById('bonusOverlay');
const backdrop = document.getElementById('backdrop');

function showBonus(date, bookings) {
    document.getElementById('bonusTitle').innerText = date;
    let html = '';
    bookings.forEach(b => {
        html += `
            <div class="bonus-item" onclick='prepareEditFromBonus(${JSON.stringify(b)})'>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="fw-bold text-accent">${b.t_only}</span>
                    <span class="badge bg-dark fw-light" style="font-size:0.6rem;">${b.status}</span>
                </div>
                <div class="mt-2 fw-semibold">${b.customer_name}</div>
                <div class="small text-muted">${b.s_name}</div>
            </div>`;
    });
    document.getElementById('bonusContent').innerHTML = html;
    bonusOverlay.classList.add('active');
    backdrop.style.display = 'block';
}

function closeBonus() {
    bonusOverlay.classList.remove('active');
    backdrop.style.display = 'none';
}

function prepareEditFromBonus(b) {
    closeBonus();
    prepareEdit(b);
}

function prepareEdit(b) {
    document.getElementById('m_title').innerText = "Módosítás";
    document.getElementById('b_id').value = b.id;
    document.getElementById('b_name').value = b.customer_name;
    document.getElementById('b_service').value = b.service_id;
    document.getElementById('b_date').value = b.booking_date;
    document.getElementById('b_time').value = b.t_only;
    document.getElementById('b_status').value = b.status;
    
    document.getElementById('status_div').style.display = "block";
    document.getElementById('delete_btn').style.display = "inline-block";
    bModal.show();
}
</script>
</body>
</html>