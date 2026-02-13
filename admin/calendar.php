<?php
require_once "../config/database.php"; 
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') { header("Location: ../public/login.php?error=4"); exit; }

$month = isset($_GET['m']) ? (int)$_GET['m'] : date('m');
$year = isset($_GET['y']) ? (int)$_GET['y'] : date('Y');

$first_day = date('N', strtotime("$year-$month-01"));$days_in_month = date('t', strtotime("$year-$month-01"));

$stmt = $pdo->prepare("SELECT b.*, s.name as service_name FROM bookings b LEFT JOIN services s ON b.service_id = s.id WHERE MONTH(b.booking_date) = ? AND YEAR(b.booking_date) = ? ORDER BY b.booking_time ASC");
$stmt->execute([$month, $year]);
$monthly_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

$monthly_bookings = [];
foreach ($monthly_data as $b) {
    $monthly_bookings[$b['booking_date']][] = $b;
}
$services_list = $pdo->query("SELECT * FROM services ORDER BY name ASC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AB MASSZÁZS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600&family=Shippori+Mincho:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/admin_style.css">
    <style>
        :root { --j-bg: #f8f5f2; --j-card: #ffffff; --j-accent: #8e7d6f; --j-border: #e8e2db; }
        body.japandi-bg { background-color: var(--j-bg); }

       
        .calendar-wrapper {
            max-width: 850px;
            margin:  auto;
        }

        .calendar-container {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 12px;
            

        }

        .calendar-day-label {
            text-align: center;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: var(--j-accent);
            padding-bottom: 10px;
            font-weight: 700;
        }

        .day-card {
            background: var(--j-card);
            aspect-ratio: 1.1 / 1;
            border-radius: 10px;
            padding: 12px;
            border: 1px solid var(--j-border);
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            box-shadow: 0 4px 6px rgba(0,0,0,0.02);
        }

        .day-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 15px rgba(142, 125, 111, 0.1);
            border-color: var(--j-accent);
        }

        .day-card.empty { background: transparent; border: none; cursor: default; }
        .day-card.today { border: 2px solid var(--j-accent); background: #fffcf9; }
        
        .day-num { font-size: 1rem; font-weight: 600; color: #444; }
        
        .booking-badge {
            align-self: flex-end;
            background: var(--j-accent);
            color: #fff;
            font-size: 0.65rem;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 10px;
        }

       
        #successToast {
            display: none;
            position: fixed;
            top: 90px;
            right: 20px;
            background: #2f855a;
            color: white;
            padding: 15px 25px;
            z-index: 10000;
            font-size: 0.8rem;
            letter-spacing: 2px;
            text-transform: uppercase;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

       
        .offcanvas { border-left: none !important; box-shadow: -10px 0 30px rgba(0,0,0,0.05); }
        .booking-item-card {
            background: #fcfaf8;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 10px;
            border-left: 3px solid var(--j-accent);
            cursor: pointer;
            transition: 0.2s;
        }
        .booking-item-card:hover { background: #fff; box-shadow: 0 4px 8px rgba(0,0,0,0.05); }

        @media (max-width: 768px) {
            .calendar-container { gap: 5px; }
            .day-card { padding: 5px; }
        }
        .calendar-container {
            border: 2px solid var(--j-border);
            border-radius: 12px;
            padding: 12px;
            background: transparent;
        }
    </style>
</head>
<body class="japandi-bg">
<?php include "assets/admin_navbar.php"; ?>

<div id="successToast">Sikeres mentés!</div>

<div class="container pb-5" style="margin-top:20px;">
    <div class="calendar-wrapper">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <h1 class="brand m-0" style="font-size: 3rem;"><?= date('Y. m.', strtotime("$year-$month-01")) ?></h1>
            <div class="d-flex gap-3">
                <a href="?m=<?= $month-1 == 0 ? 12 : $month-1 ?>&y=<?= $month-1 == 0 ? $year-1 : $year ?>" class="nav-icon-btn"><i class="fas fa-arrow-left"></i></a>
                <a href="?m=<?= $month+1 == 13 ? 1 : $month+1 ?>&y=<?= $month+1 == 13 ? $year+1 : $year ?>" class="nav-icon-btn"><i class="fas fa-arrow-right"></i></a>
            </div>
        </div>

        <div class="calendar-container">
            <?php 
            $days = ['Hét', 'Ked', 'Sze', 'Csü', 'Pén', 'Szo', 'Vas'];
            foreach($days as $d) echo "<div class='calendar-day-label'>$d</div>";
            
            for ($i = 1; $i < $first_day; $i++) echo '<div class="day-card empty"></div>';

            for ($day = 1; $day <= $days_in_month; $day++):
                $full_date = sprintf('%04d-%02d-%02d', $year, $month, $day);
                $bookings = $monthly_bookings[$full_date] ?? [];
                $count = count($bookings);
            ?>
                <div class="day-card <?= ($full_date == date('Y-m-d')) ? 'today' : '' ?>" 
                     onclick='openSidePanel("<?= $full_date ?>", <?= json_encode($bookings) ?>)'>
                    <span class="day-num"><?= $day ?></span>
                    <?php if($count > 0): ?>
                        <div class="booking-badge"><?= $count ?></div>
                    <?php endif; ?>
                </div>
            <?php endfor; ?>
        </div>
    </div>
</div>

<div class="offcanvas offcanvas-end" tabindex="-1" id="bookingPanel" style="width: 400px;">
    <div class="offcanvas-header p-4">
        <h3 class="brand m-0" id="panelDateLabel">Dátum</h3>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body p-4">
        <div id="panelList">
            <div id="panelBookingItems" class="mb-4"></div>
        </div>

        <div id="panelForm" style="display:none;">
            <div class="mb-4 cursor-pointer text-muted small fw-bold" onclick="hideForm()">
                <i class="fas fa-chevron-left me-1"></i> VISSZA A LISTÁHOZ
            </div>
            <form id="ajaxForm">
                <input type="hidden" name="booking_id" id="f_id">
                <input type="hidden" name="booking_date" id="f_date">
                <div class="mb-3">
                    <label class="small text-muted fw-bold">VENDÉG NEVE</label>
                    <input type="text" name="customer_name" id="f_name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="small text-muted fw-bold">SZOLGÁLTATÁS</label>
                    <select name="service_id" id="f_service" class="form-select">
                        <?php foreach($services_list as $s): ?>
                            <option value="<?= $s['id'] ?>"><?= $s['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="small text-muted fw-bold">IDŐPONT</label>
                    <input type="time" name="booking_time" id="f_time" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-zen w-100">ADATOK MENTÉSE</button>
            </form>
        </div>
    </div>
</div>

<script src="https:
<script>
const bsOffcanvas = new bootstrap.Offcanvas(document.getElementById('bookingPanel'));

function openSidePanel(date, bookings) {
    document.getElementById('panelDateLabel').innerText = date.replace(/-/g, '. ') + '.';
    document.getElementById('f_date').value = date;
    const container = document.getElementById('panelBookingItems');
    container.innerHTML = '';
    hideForm();

    if (bookings.length > 0) {
        bookings.forEach(b => {
            container.innerHTML += `
                <div class="booking-item-card" onclick='editBooking(${JSON.stringify(b)})'>
                    <div class="d-flex justify-content-between">
                        <span class="fw-bold text-accent">${b.booking_time.substring(0,5)}</span>
                        <span class="small text-muted">${b.service_name}</span>
                    </div>
                    <div class="mt-1 fw-medium">${b.customer_name}</div>
                </div>`;
        });
    } else {
        container.innerHTML = '<p class="text-muted py-5 text-center small italic">Nincs rögzített foglalás erre a napra.</p>';
    }
    bsOffcanvas.show();
}

function showForm() {
    document.getElementById('f_id').value = '';
    document.getElementById('f_name').value = '';
    document.getElementById('f_time').value = '08:00';
    document.getElementById('panelList').style.display = 'none';
    document.getElementById('panelForm').style.display = 'block';
}

function editBooking(data) {
    document.getElementById('f_id').value = data.id;
    document.getElementById('f_name').value = data.customer_name;
    document.getElementById('f_service').value = data.service_id;
    document.getElementById('f_time').value = data.booking_time.substring(0,5);
    document.getElementById('panelList').style.display = 'none';
    document.getElementById('panelForm').style.display = 'block';
}

function hideForm() {
    document.getElementById('panelList').style.display = 'block';
    document.getElementById('panelForm').style.display = 'none';
}

document.getElementById('ajaxForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const toast = document.getElementById('successToast');
    
    
fetch('../public/api.php?request=bookings', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
    },
    body: JSON.stringify({
        customer_name: document.getElementById('f_name').value,
        service_id: document.getElementById('f_service').value,
        booking_date: document.getElementById('f_date').value,
        booking_time: document.getElementById('f_time').value
    })
})
    })
    .then(r => r.json())
    .then(data => {
        if(data.status === 'success') {
            bsOffcanvas.hide(); 
            toast.style.display = 'block'; 
            
            setTimeout(() => {
                location.reload(); 
            }, 1000);
        } else {
            alert(data.message);
        }
    });
});
</script>
</body>
</html>