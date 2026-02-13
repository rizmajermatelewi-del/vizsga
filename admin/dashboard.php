<?php
require_once "../config/database.php"; 
session_start();


if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') { 
    header("Location: ../public/login.php?error=4"); 
    exit; 
}


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


$bookings_today = $pdo->query("SELECT COUNT(*) FROM bookings WHERE booking_date = CURDATE() AND status != 'deleted'")->fetchColumn();
$active_vouchers = $pdo->query("SELECT COUNT(*) FROM vouchers WHERE status = 'active'")->fetchColumn();
$services_count = $pdo->query("SELECT COUNT(*) FROM services")->fetchColumn();


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
    <title>AB MASSZÁZS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600&family=Shippori+Mincho:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/admin_style.css">
    <style>
        :root {
   
    --j-bg: #ffffff;          
    --j-card: #f9f7f2;        
    --j-text: #1a1a1a;        
    --j-muted: #575757;       
    --j-border: #e2e2e2;      
    --j-gold: #8b6f47;        
    --j-accent: #d4af37;      
    --j-placeholder: #a0a0a0;
    --j-boxcolor: #fff;
}

body.dark-theme {
   
    --j-bg: #0f1215;          
    --j-card: #1a1f24;        
    --j-text: #f0f0f0;        
    --j-muted: #b0b0b0;       
    --j-border: #2d343b;      
    --j-gold: #e8d4a8;        
    --j-accent: #f1c40f;
    --j-placeholder: #666666;
    --j-boxcolor: #000000ff;
}


body {
    background-color: var(--j-bg);
    color: var(--j-text);
    transition: background-color 0.3s ease, color 0.3s ease;
}
    html {
    scroll-behavior: smooth;
}
           
        section {
            padding: 80px 0;
        }

        .section-header {
            text-align: center;
            margin-bottom: 60px;
        }

        .section-header .info-label {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 3px;
            color: var(--j-gold);
            font-weight: 800;
            display: block;
            margin-bottom: 1rem;
        }

        .section-header h2 {
            font-family: 'Shippori Mincho', serif;
            font-size: 2.8rem;
            font-weight: 700;
            color: var(--j-text);
            margin-top: 0;
        }

        .section-subtitle {
            color: var(--j-muted);
            font-size: 1.1rem;
            margin-top: 1rem;
        }

       
        .hero-zen {
            position: relative;
            background-image: url('https:
            background-size: cover;
            background-position: center;
            height: 90vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 !important;
            margin-top: 80px;
        }

        .hero-zen::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4);
            z-index: 1;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            text-align: center;
            max-width: 800px;
        }

        .hero-zen h1,
        .hero-zen h2,
        .hero-zen p {
            color: #ffffff !important;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .btn-zen-light {
            background: transparent;
            border: 2px solid #fff;
            color: #fff;
            padding: 15px 40px;
            text-decoration: none;
            letter-spacing: 3px;
            font-size: 0.85rem;
            font-weight: 800;
            transition: all 0.4s ease;
            display: inline-block;
            text-transform: uppercase;
        }

        .btn-zen-light:hover {
            background: #fff;
            color: #000;
        }

       
        .scroll-indicator {
            position: absolute;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 10;
        }

        .scroll-line {
            width: 1px;
            height: 30px;
            background: rgba(255, 255, 255, 0.5);
            animation: scroll-down 2s infinite;
        }

        @keyframes scroll-down {
            0% {
                transform: translateY(0);
                opacity: 1;
            }
            100% {
                transform: translateY(10px);
                opacity: 0;
            }
        }

       
        .divider-zen {
            width: 30px;
            height: 2px;
            background: var(--j-gold);
            opacity: 0.5;
            margin: 0 auto;
        }

       
        .service-card {
            background: var(--j-card);
            border: 2px solid var(--j-border);
            padding: 2.5rem 2rem;
            border-radius: 0;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 320px;
        }

        .service-card:hover {
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.1);
            transform: translateY(-5px);
            border-color: var(--j-gold);
        }

        .service-card h4 {
            color: var(--j-text) !important;
            font-weight: 700;
            margin-bottom: 1rem;
            font-size: 1.3rem;
        }

        .service-card p {
            color: var(--j-muted) !important;
        }

        .service-card .price-tag {
            color: var(--j-gold) !important;
            font-weight: 800;
            font-size: 1.6rem;
            margin: 1rem 0;
        }

        .service-booking-link {
            color: var(--j-text) !important;
            text-decoration: none;
            font-weight: 700;
            font-size: 0.85rem;
            letter-spacing: 2px;
            transition: all 0.3s ease;
            display: inline-block;
            margin-top: 1.5rem;
            text-transform: uppercase;
        }

        .service-booking-link:hover {
            color: var(--j-gold) !important;
            transform: translateX(5px);
        }

       
        .master-card {
            text-align: center;
            transition: all 0.3s ease;
        }

        .master-img {
            width: 200px;
            height: 250px;
            background-size: cover;
            background-position: center;
            margin: 0 auto 2rem;
            border: 2px solid var(--j-border);
            transition: all 0.3s ease;
        }

        .master-card:hover .master-img {
            border-color: var(--j-gold);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .master-card h5 {
            color: var(--j-text) !important;
            margin-bottom: 0.5rem;
            font-size: 1.3rem;
        }

        .master-card p {
            color: var(--j-muted) !important;
        }

        .master-badge {
            background: rgba(139, 111, 71, 0.1);
            color: var(--j-gold);
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            font-size: 0.75rem;
            display: inline-block;
            margin: 0 0.3rem;
            margin-top: 0.5rem;
        }

       
        .review-card {
            transition: transform 0.4s ease, box-shadow 0.4s ease;
            border-radius: 0;
            background: var(--j-card);
            border: 1px solid var(--j-border);
        }

        .review-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 40px rgba(139, 111, 71, 0.15) !important;
        }

        .stars i {
            font-size: 0.9rem;
            margin: 0 2px;
            color: var(--j-gold) !important;
        }

       
        #voucher-preview {
            border: 3px dashed var(--j-accent) !important;
            background: var(--j-card) !important;
            padding: 3rem !important;
            border-radius: 15px;
        }

        #p-amount {
            color: var(--j-gold) !important;
            font-weight: 700;
            font-size: 3.5rem;
            font-family: 'Shippori Mincho', serif;
        }

        #p-name {
            color: var(--j-muted) !important;
            font-size: 0.8rem;
            letter-spacing: 3px;
        }

        .voucher-form-section {
            background: var(--j-card);
            padding: 2.5rem;
            border: 2px solid var(--j-border);
        }

        .voucher-form-section label {
            color: var(--j-text) !important;
            font-weight: 600;
        }

       
        .contact-icon-box {
            width: 50px;
            height: 50px;
            background: rgba(139, 111, 71, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .contact-icon-box i {
            color: var(--j-gold);
            font-size: 1.3rem;
        }

        .contact-info-item {
            display: flex;
            align-items: flex-start;
            gap: 1.5rem;
            margin-bottom: 2rem;
            padding-bottom: 2rem;
            border-bottom: 1px solid var(--j-border);
        }

        .contact-info-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .contact-info-item h5 {
            font-family: 'Shippori Mincho', serif;
            font-weight: 700;
            font-size: 0.95rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: var(--j-text) !important;
            margin-bottom: 0.5rem;
        }

        .contact-info-item p {
            color: var(--j-muted) !important;
            margin: 0;
            line-height: 1.6;
        }

        .phone-link,
        .email-link {
            color: var(--j-text) !important;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .phone-link:hover,
        .email-link:hover {
            color: var(--j-gold) !important;
        }

       
        .form-control,
        .form-select {
            background: var(--j-card) !important;
            border: 2px solid var(--j-border) !important;
            color: var(--j-text) !important;
            border-radius: 0 !important;
            padding: 12px 14px !important;
            transition: all 0.3s ease !important;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--j-gold) !important;
            box-shadow: 0 0 0 3px rgba(139, 111, 71, 0.1) !important;
            color: var(--j-text) !important;
            background: var(--j-card) !important;
        }

        .form-control::placeholder {
            color: var(--j-placeholder) !important;
        }

        .form-label {
            color: var(--j-text) !important;
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

       
        .tab-btn {
            background: transparent;
            border: 2px solid var(--j-border);
            color: var(--j-text);
            padding: 12px 24px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            border-radius: 0;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.9rem;
        }

        .tab-btn:hover {
            border-color: var(--j-gold);
            color: var(--j-gold);
        }

        .tab-btn.active {
            background: var(--j-text);
            color: var(--j-bg);
            border-color: var(--j-text);
        }

        .tab-form-content {
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

       
        .phone-format {
            font-family: 'Courier New', monospace;
            font-weight: 600;
            letter-spacing: 1px;
        }

       
        .time-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
        }

        .time-box {
            background: var(--j-card);
            border: 2px solid var(--j-border);
            padding: 14px 8px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            border-radius: 0;
            color: var(--j-text);
            font-weight: 700;
            font-size: 0.95rem;
        }

        .btn-check:checked ~ .time-box {
            background: var(--j-text);
            color: var(--j-bg);
            border-color: var(--j-text);
        }

        .time-box:hover {
            border-color: var(--j-gold);
            color: var(--j-gold);
        }
       
        .bg-accent-soft {
            background-color: rgba(90, 74, 42, 0.05);
        }

        body.dark-theme .bg-accent-soft {
            background-color: rgba(232, 212, 168, 0.08);
        }

        .bg-light-zen {
            background-color: var(--j-bg);
        }

       
        .reveal {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }

        .reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }

       
        #zen-preloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: var(--j-bg);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            opacity: 1;
            transition: opacity 0.8s ease;
        }

        #zen-preloader p {
            font-family: 'Shippori Mincho', serif;
            font-size: 1.2rem;
            letter-spacing: 3px;
            color: var(--j-text);
            margin-top: 20px;
        }

        .zen-leaf {
            width: 40px;
            height: 40px;
            border: 2px solid var(--j-gold);
            border-radius: 50%;
            animation: spin 2s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

       
        .btn-dark {
            background: var(--j-text) !important;
            border: 2px solid var(--j-text) !important;
            color: var(--j-bg) !important;
            padding: 12px 30px !important;
            font-weight: 800 !important;
            text-transform: uppercase;
            letter-spacing: 2px;
            transition: all 0.3s ease !important;
            border-radius: 0 !important;
        }

        .btn-dark:hover {
            background: var(--j-gold) !important;
            border-color: var(--j-gold) !important;
            color: var(--j-bg) !important;
        }

       
        @media (max-width: 768px) {
            section {
                padding: 50px 0;
            }

            .section-header h2 {
                font-size: 2rem;
            }

            .time-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .hero-zen {
                margin-top: 80px;
            }

            #contact {
                padding: 0 !important;
            }

            #contact .col-lg-7 {
                height: 400px !important;
            }

            #contact .col-lg-5 {
                height: auto;
                padding: 3rem 1.5rem !important;
            }

            .master-img {
                width: 150px;
                height: 180px;
            }

            .tab-btn {
                padding: 10px 16px;
                font-size: 0.8rem;
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
                    <span class="stat-num d-block display-4 fw-bold" style="font-family: 'Shippori Mincho', serif; color: var(--j-muted);"><?= $bookings_today ?></span>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="vouchers.php" class="stat-card-link text-decoration-none">
                <div class="j-card h-100">
                    <p class="small text-muted text-uppercase mb-2" style="letter-spacing: 2px;">Aktív utalványok</p>
                    <span class="stat-num d-block display-4 fw-bold" style="font-family: 'Shippori Mincho', serif; color: var(--j-muted);"><?= $active_vouchers ?></span>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="services.php" class="stat-card-link text-decoration-none">
                <div class="j-card h-100">
                    <p class="small text-muted text-uppercase mb-2" style="letter-spacing: 2px;">Szolgáltatások</p>
                    <span class="stat-num d-block display-4 fw-bold" style="font-family: 'Shippori Mincho', serif; color: var(--j-muted);"><?= $services_count ?></span>
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

    <div class="j-card p-0 overflow-hidden shadow-sm border-0"  style="background: transparent;">
        <div class="p-4 border-bottom d-flex justify-content-between align-items-center" style="background: transparent;">
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
                <div class="day-header" style="background: transparent; padding: 12px 25px; font-size: 0.7rem; font-weight: 700; letter-spacing: 2px; color: var(--j-accent); border-bottom: 1px solid var(--j-border); border-top: 1px solid var(--j-border);">
                    <?= $label ?>
                </div>
            <?php endif; ?>
            
            <div class="booking-row booking-item" style="color: black;" onclick='editBooking(<?= json_encode($b) ?>)' data-search="<?= strtolower($b['customer_name'] . ' ' . $b['s_name']) ?>">
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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