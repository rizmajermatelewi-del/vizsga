<?php
require_once "../config/database.php"; 
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') { header("Location: ../public/login.php?error=4"); exit; }

// Aktuális hónap beállítása
$month = isset($_GET['m']) ? (int)$_GET['m'] : date('m');
$year = isset($_GET['y']) ? (int)$_GET['y'] : date('Y');

$first_day = date('N', strtotime("$year-$month-01"));
$days_in_month = cal_days_in_month(CAL_GREGORIAN, $month, $year);

// Foglalások lekérése az adott hónapra
$stmt = $pdo->prepare("SELECT booking_date, COUNT(*) as count FROM bookings WHERE MONTH(booking_date) = ? AND YEAR(booking_date) = ? GROUP BY booking_date");
$stmt->execute([$month, $year]);
$monthly_bookings = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

$services_list = $pdo->query("SELECT * FROM services ORDER BY name ASC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>AB MASSZÁZS | Naptár</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/admin_style.css">
</head>
<body>
<?php include "assets/admin_navbar.php"; ?>

<div class="container pb-5" style="margin-top:50px;">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h1 class="brand mt-0 mb-1"><?= date('Y', strtotime("$year-$month-01")) ?>. <?= strftime('%B', strtotime("$year-$month-01")) ?></h1>
            <p class="text-muted small text-uppercase" style="letter-spacing: 2px;">Kattintson egy napra az új foglaláshoz</p>
        </div>
        <div class="d-flex gap-2">
            <a href="?m=<?= $month-1 == 0 ? 12 : $month-1 ?>&y=<?= $month-1 == 0 ? $year-1 : $year ?>" class="btn-control text-decoration-none"><i class="fas fa-chevron-left"></i></a>
            <a href="?m=<?= $month+1 == 13 ? 1 : $month+1 ?>&y=<?= $month+1 == 13 ? $year+1 : $year ?>" class="btn-control text-decoration-none"><i class="fas fa-chevron-right"></i></a>
        </div>
    </div>

    <div class="calendar-grid">
        <div class="calendar-header-item">Hét</div>
        <div class="calendar-header-item">Ked</div>
        <div class="calendar-header-item">Sze</div>
        <div class="calendar-header-item">Csü</div>
        <div class="calendar-header-item">Pén</div>
        <div class="calendar-header-item">Szo</div>
        <div class="calendar-header-item">Vas</div>

        <?php 
        // Üres helyek a hónap előtt
        for ($i = 1; $i < $first_day; $i++) echo '<div class="day-box empty"></div>';

        // Napok kirajzolása
        for ($day = 1; $day <= $days_in_month; $day++):
            $full_date = sprintf('%04d-%02d-%02d', $year, $month, $day);
            $is_today = ($full_date == date('Y-m-d'));
            $count = $monthly_bookings[$full_date] ?? 0;
        ?>
            <div class="day-box <?= $is_today ? 'today' : '' ?> cursor-pointer" onclick="openNewBooking('<?= $full_date ?>')">
                <span class="day-num"><?= $day ?></span>
                <?php if ($count > 0): ?>
                    <div class="booking-badge"><?= $count ?> foglalás</div>
                <?php endif; ?>
            </div>
        <?php endfor; ?>
    </div>
</div>

<div class="modal fade" id="calendarModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="p-2">
                <form method="POST" action="dashboard.php"> <h3 class="brand mb-4 text-center">Új időpont rögzítése</h3>
                    
                    <div class="mb-3">
                        <label class="small text-muted mb-1 fw-bold">VENDÉG NEVE</label>
                        <input type="text" name="customer_name" class="form-control" placeholder="Minta János" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="small text-muted mb-1 fw-bold">KEZELÉS</label>
                        <select name="service_id" class="form-select">
                            <?php foreach($services_list as $s): ?>
                                <option value="<?= $s['id'] ?>"><?= $s['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-6">
                            <label class="small text-muted mb-1 fw-bold">DÁTUM</label>
                            <input type="date" name="booking_date" id="target_date" class="form-control" readonly>
                        </div>
                        <div class="col-6">
                            <label class="small text-muted mb-1 fw-bold">IDŐPONT</label>
                            <input type="time" name="booking_time" class="form-control" value="09:00">
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2 pt-2">
                        <button type="submit" class="btn btn-zen flex-grow-1">Foglalás mentése</button>
                        <button type="button" class="btn btn-outline-dark rounded-0 px-4" data-bs-dismiss="modal">Mégse</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function openNewBooking(date) {
        document.getElementById('target_date').value = date;
        new bootstrap.Modal(document.getElementById('calendarModal')).show();
    }
</script>

<style>
/* Naptár specifikus Japandi stílus */
.calendar-grid { 
    display: grid; 
    grid-template-columns: repeat(7, 1fr); 
    background: var(--j-border); 
    border: 1px solid var(--j-border);
    gap: 1px;
}

.calendar-header-item { 
    background: var(--j-soft); 
    padding: 15px; 
    text-align: center; 
    font-size: 0.7rem; 
    text-transform: uppercase; 
    font-weight: 700;
    color: var(--j-accent);
}

.day-box { 
    min-height: 120px; 
    background: var(--j-white); 
    padding: 15px;
    transition: all 0.3s ease;
    position: relative;
}

.day-box:not(.empty):hover {
    background: var(--j-soft);
    transform: scale(1.02);
    z-index: 10;
    box-shadow: 0 10px 20px rgba(0,0,0,0.05);
}

.day-box.today {
    background: #fdfaf7;
}

.day-box.today .day-num {
    color: var(--j-accent);
    font-weight: 800;
}

.day-num {
    font-size: 1.1rem;
    font-family: 'Shippori Mincho', serif;
    font-weight: 500;
}

.booking-badge {
    background: rgba(142, 125, 106, 0.1);
    color: var(--j-accent);
    font-size: 0.65rem;
    padding: 4px 8px;
    margin-top: 10px;
    font-weight: 600;
    text-transform: uppercase;
    display: inline-block;
}

.cursor-pointer { cursor: pointer; }
</style>

</body>
</html>