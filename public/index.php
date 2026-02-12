<?php
session_start();
require_once "../config/database.php";

// Szolgáltatások lekérése
$stmt = $pdo->query("SELECT * FROM services ORDER BY name ASC");
$services = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AB MASSZÁZS</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@200;400;600&family=Shippori+Mincho:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="assets/user_style.css">
<style>
    html {
    scroll-behavior: smooth;
}
            /* === SZEKCIÓ KÖZÖS STÍLUS === */
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

        /* === HERO SZEKCIÓ === */
        .hero-zen {
            position: relative;
            background-image: url('https://images.unsplash.com/photo-1540555700478-4be289fbecef?q=80&w=2070');
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

        /* === SCROLL INDICATOR === */
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

        /* === DIVIDER === */
        .divider-zen {
            width: 30px;
            height: 2px;
            background: var(--j-gold);
            opacity: 0.5;
            margin: 0 auto;
        }

        /* === SERVICE CARD === */
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

        /* === MASTER CARD === */
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

        /* === REVIEW CARD === */
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

        /* === VOUCHER SECTION === */
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

        /* === CONTACT SECTION === */
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

        /* === FORM STYLES === */
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

        /* === TAB BUTTON STYLES === */
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

        /* === PHONE FORMAT INPUT === */
        .phone-format {
            font-family: 'Courier New', monospace;
            font-weight: 600;
            letter-spacing: 1px;
        }

        /* === TIME GRID === */
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

        /* === BG CLASSES === */
        .bg-accent-soft {
            background-color: rgba(90, 74, 42, 0.05);
        }

        body.dark-theme .bg-accent-soft {
            background-color: rgba(232, 212, 168, 0.08);
        }

        .bg-light-zen {
            background-color: var(--j-bg);
        }

        /* === REVEAL ANIMATION === */
        .reveal {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }

        .reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* === PRELOADER === */
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

        /* === BUTTON STYLES === */
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

        /* === RESPONSIVE === */
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
<body data-bs-spy="scroll" data-bs-target=".navbar" data-bs-offset="80" class="<?= (isset($_COOKIE['theme']) && $_COOKIE['theme'] == 'dark') ? 'dark-theme' : '' ?>">
<?php include 'assets/user_navbar.php'; ?>

<!-- PRELOADER -->
<div id="zen-preloader">
    <div class="zen-leaf"></div>
    <p>AB MASSZÁZS</p>
</div>
<!-- HERO SZEKCIÓ -->
<header class="hero-zen reveal">
    <div class="hero-content text-center">
        <h1 class="display-4 mb-3">ÜDVÖZÖLJÜK A CSENDBEN</h1>
        <h1 class="display-2 brand my-4">Találja meg belső békéjét</h1>
        <p class="lead opacity-75 mb-5 px-3">Japán rituálék és skandináv minimalizmus a teljes testi-lelki megújulásért.</p>
        <a href="#booking" class="btn-zen-light">IDŐPONTOT FOGLALOK</a>
    </div>
    <div class="scroll-indicator">
        <div class="scroll-line"></div>
    </div>
</header>

<!-- FILOZÓFIA SZEKCIÓ -->
<section id="philosophy" class="bg-light-zen">
    <div class="container">
        <div class="section-header reveal">
            <span class="info-label">FILOZÓFIÁNK</span>
            <h2>Japandi Élmény</h2>
            <p class="section-subtitle">A csend és a harmónia élménye</p>
        </div>
        <div class="row align-items-center g-5">
            <div class="col-lg-6 reveal">
                <img src="Text" alt="AB Masszázs" class="img-fluid" style="border: 2px solid var(--j-border);">
            </div>
            <div class="col-lg-6 reveal">
                <h3 class="brand mb-4" style="font-size: 2rem;">Harmónia a testben és a lélekben</h3>
                <p style="color: var(--j-text); line-height: 2; margin-bottom: 1.5rem; font-size: 1.05rem;">
                    Az AB Masszázs a japandi filozófia szellemében működik. A japán minimalizmust és a skandináv meghitségét ötvözzük, hogy Önnek a lehető legjobb relaxációs élményt nyújtsuk.
                </p>
                <p style="color: var(--j-muted); line-height: 2; margin-bottom: 2rem; font-size: 1.05rem;">
                    Minden kezelésnél a tudatos légzésre, a teljes testtudat elvére és a belső béke megtalálására törekszünk. Megjelenítjük az egyensúlyt, tisztaságot és szépséget.
                </p>
                
                <div class="row g-4">
                    <div class="col-6">
                        <div style="width: 80px; height: 80px; border-radius: 50%; background: var(--j-card); border: 2px solid var(--j-border); display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                            <i class="fa-solid fa-leaf" style="color: var(--j-gold); font-size: 2rem;"></i>
                        </div>
                        <h5 class="brand text-center mb-2">Természetes</h5>
                        <p class="small text-muted text-center">Csak a legjobb természetes anyagok</p>
                    </div>
                    <div class="col-6">
                        <div style="width: 80px; height: 80px; border-radius: 50%; background: var(--j-card); border: 2px solid var(--j-border); display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                            <i class="fa-solid fa-om" style="color: var(--j-gold); font-size: 2rem;"></i>
                        </div>
                        <h5 class="brand text-center mb-2">Tudatos</h5>
                        <p class="small text-muted text-center">Meditáció minden lépésben</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- SZOLGÁLTATÁSOK SZEKCIÓ -->
<section id="services" class="bg-accent-soft">
    <div class="container">
        <div class="section-header reveal">
            <span class="info-label">SZOLGÁLTATÁSAINK</span>
            <h2>Válogatott masszázsok</h2>
            <p class="section-subtitle">Javasolt kezelések és árak</p>
        </div>
        <div class="row g-4">
            <?php foreach($services as $s): ?>
            <div class="col-md-4">
                <div class="service-card text-center reveal">
                    <div>
                        <h4 class="brand"><?= htmlspecialchars($s['name']) ?></h4>
                        <div class="divider-zen mb-3"></div>
                        <p class="text-muted small mb-2"><?= $s['duration'] ?? 60 ?> perc</p>
                        <p class="price-tag"><?= number_format($s['price'], 0, ',', ' ') ?> Ft</p>
                    </div>
                    <a href="#booking" class="service-booking-link">Foglalás →</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- MESTEREINK SZEKCIÓ -->
<section id="masters">
    <div class="container">
        <div class="section-header reveal">
            <span class="info-label">SZAKÉRTELEM</span>
            <h2>Mestereink</h2>
            <p class="section-subtitle">Tapasztalt és empátiával teli masszőreink</p>
        </div>
        <div class="row g-5 justify-content-center">
            <div class="col-md-5 reveal">
                <div class="master-card">
                    <div class="master-img" style="background-image: url('text');"></div>
                    <h5 class="brand">Apostol Brigitta</h5>
                    <p class="small text-muted mb-2">Szakértő</p>
                    <div>
                        <span class="master-badge">Yumeiho</span>
                        <span class="master-badge">Svédmasszázs</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- AJÁNDÉKKÁRTYA SZEKCIÓ -->
<section id="vouchers">
    <div class="container">
        <div class="section-header reveal">
            <span class="info-label">AJÁNDÉK</span>
            <h2>Ajándékutalvány</h2>
            <p class="section-subtitle">Adjon élményt szeretteinek</p>
        </div>
        <div class="row g-5 align-items-center">
            <div class="col-lg-6 reveal">
                <div id="voucher-preview" class="text-center">
                    <p class="small text-muted mb-2" style="letter-spacing: 2px;">AJÁNDÉKUTALVÁNY</p>
                    <h3 class="brand mb-4" style="letter-spacing: 5px;">AB MASSZÁZS</h3>
                    <h2 id="p-amount">10 000 Ft</h2>
                    <p id="p-name" class="text-uppercase small fw-bold opacity-50 mt-4">VENDÉGÜNK NEVE</p>
                </div>
            </div>

            <div class="col-lg-6 reveal">
                <?php if(isset($_SESSION['user_id'])): ?>
                    <div class="voucher-form-section">
                        <h5 class="brand mb-4">Utalvány vásárlása</h5>
                        <form id="voucherForm" class="row g-3">
                            <div class="col-12"> <label class="form-label">Vásárló neve: </label>
                                
                            <strong><?= htmlspecialchars($_SESSION['user_name']) ?></strong>

                            </div>
                            <div class="col-12">
                                <label class="form-label">Kinek a részére?</label>
                                <input type="text" name="v_recipient" id="v_name" class="form-control" placeholder="A megajándékozott neve" required>
                            </div>
                            
                            <div class="col-12">
                                <label class="form-label">Az Ön email címe</label>
                                <input type="email" name="v_buyer_email" class="form-control" placeholder="email@example.com" required>
                            </div>
                            
                            <div class="col-12">
                                <label class="form-label">Az Ön telefonszáma</label>
                                <input type="tel" name="v_buyer_tel" id="v_buyer_tel" class="form-control" placeholder="+36 30 123 4567" required>
                            </div>
                            
                            <div class="col-12">
                                <label class="form-label">Utalvány értéke</label>
                                <select name="v_amount" id="v_amount" class="form-select">
                                    <option value="10000">10 000 Ft</option>
                                    <option value="25000">25 000 Ft</option>
                                    <option value="50000">50 000 Ft</option>
                                </select>
                            </div>
                            
                            <div class="col-12 pt-2">
                                <button type="submit" class="btn btn-dark w-100">Vásárlás megerősítése</button>
                            </div>
                        </form>
                    </div>
                <?php else: ?>
                    <div class="voucher-form-section text-center">
                        <i class="fa-solid fa-gift" style="font-size: 4rem; color: var(--j-gold); margin-bottom: 1.5rem; display: block;"></i>
                        <p style="color: var(--j-muted); font-size: 1.1rem;">Az ajándékutalvány vásárlásához bejelentkezés szükséges.</p>
                        <a href="login.php" class="btn btn-dark mt-4">Belépés</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
<!-- FOGLALÁS SZEKCIÓ -->
<section id="booking" class="bg-light-zen">
    <div class="container">
        <div class="section-header reveal">
            <span class="info-label">FOGLALÁS</span>
            <h2>Foglaljon időpontot</h2>
            <p class="section-subtitle">Válassza ki az Ön számára megfelelő időpontot</p>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <form id="apiBookingForm" class="row g-4 reveal">
                    <div class="col-md-6">
                        <label class="form-label">Név</label>
                        <input type="text" name="customer_name" id="c_name" class="form-control" placeholder="Az Ön teljes neve" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">E-mail</label>
                        <input type="email" name="customer_email" id="c_email" class="form-control" placeholder="email@example.com" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Telefonszám</label>
                        <input type="text" name="tel" id="c_tel" class="form-control" placeholder="+36 30 123 4567" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Kezelés kiválasztása</label>
                        <select name="service_id" id="c_service" class="form-select" required>
                            <option value="" disabled selected>Válasszon kezelést...</option>
                            <?php foreach($services as $s): ?>
                                <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Dátum kiválasztása</label>
                        <input type="text" id="booking_date" name="booking_date" class="form-control" placeholder="Kattintson a dátum kiválasztásához" required readonly>
                    </div>
                    
                    <div id="time-selection-area" class="col-12" style="display:none;">
                        <label class="form-label">Szabad időpontok</label>
                        <div class="time-grid">
                            <?php foreach(['09:00','10:00','11:00','13:00','14:00','15:00','16:00','17:00'] as $t): ?>
                                <div class="time-item">
                                    <input type="radio" class="btn-check" name="booking_time" id="t-<?= $t ?>" value="<?= $t ?>" required>
                                    <label class="time-box w-100 d-block" for="t-<?= $t ?>"><?= $t ?></label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <div class="col-12 text-center pt-3">
                        <button type="submit" class="btn btn-dark px-5">Foglalás megerősítése</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<!-- VÉLEMÉNYEK SZEKCIÓ -->
<section id="reviews" class="bg-light-zen">
    <div class="container">
        <div class="section-header reveal">
            <span class="info-label">VENDÉGEINK ÉLMÉNYEI</span>
            <h2>Masszázs élményei</h2>
            <p class="section-subtitle">Megbízható és elismert szolgáltatások</p>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="review-card p-4 h-100 reveal">
                    <div class="stars mb-3">
                        <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                    </div>
                    <p class="fst-italic opacity-75" style="font-size: 1rem; line-height: 1.6;">Csodálatos élmény volt! A legjobb masszázs, amit eddig kipróbáltam. Garantáltan visszajövök!</p>
                    <hr class="w-25 mx-auto opacity-25">
                    <h6 class="mb-1 brand" style="letter-spacing: 1px;">Kiss Márta</h6>
                    <small style="color: var(--j-muted);">2026. január</small>
                </div>
            </div>

            <div class="col-md-4">
                <div class="review-card p-4 h-100 reveal">
                    <div class="stars mb-3">
                        <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                    </div>
                    <p class="fst-italic opacity-75" style="font-size: 1rem; line-height: 1.6;">Professzionális, tiszta és kellemes légkör. A masszőr nagyon empatikus és ügyes volt.</p>
                    <hr class="w-25 mx-auto opacity-25">
                    <h6 class="mb-1 brand" style="letter-spacing: 1px;">Nagy János</h6>
                    <small style="color: var(--j-muted);">2026. január</small>
                </div>
            </div>

            <div class="col-md-4">
                <div class="review-card p-4 h-100 reveal">
                    <div class="stars mb-3">
                        <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-regular fa-star"></i>
                    </div>
                    <p class="fst-italic opacity-75" style="font-size: 1rem; line-height: 1.6;">Nagyon ajánlom! A légkör is szuper volt, relax és nyugalom. Pár hét múlva újra jövök!</p>
                    <hr class="w-25 mx-auto opacity-25">
                    <h6 class="mb-1 brand" style="letter-spacing: 1px;">Szabó Éva</h6>
                    <small style="color: var(--j-muted);">2025. december</small>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- KAPCSOLAT SZEKCIÓ -->
<section id="contact" style="padding: 0;">
    <div class="container-fluid">
        <div class="row g-0">
            <!-- TÉRKÉP - KITÖLTI A KONTÉNERT -->
            <div class="col-lg-7 p-0" style="height: 650px; overflow: hidden;">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2708.263382257527!2d19.322708676798364!3d47.250552812495776!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4741918afaf15229%3A0xa1f0e0ae13433cbc!2zSW7DoXJjcywgTcOhanVzIDEuIHUuIDEyLCAyMzY1!5e0!3m2!1shu!2shu!4v1770212748654!5m2!1shu!2shu" width="100%" height="100%" style="border:0; filter: grayscale(100%) contrast(1.1);" allowfullscreen="" loading="lazy"></iframe>
            </div>

            <!-- ELÉRHETŐSÉGEK -->
            <div class="col-lg-5 p-5" style="background: var(--j-card); display: flex; flex-direction: column; justify-content: center; height: 650px;">
                <div class="reveal">
                    <span class="info-label mb-3 d-block">ELÉRHETŐSÉG</span>
                    <h2 class="brand mb-5" style="font-size: 2rem;">Látogasson el hozzánk</h2>
                    
                    <!-- CÍM -->
                    <div class="contact-info-item">
                        <div class="contact-icon-box">
                            <i class="fa-solid fa-location-dot"></i>
                        </div>
                        <div>
                            <h5>Helyszín</h5>
                            <p>2365 Inárcs<br>Május 1 utca 12.</p>
                        </div>
                    </div>

                    <!-- TELEFON -->
                    <div class="contact-info-item">
                        <div class="contact-icon-box">
                            <i class="fa-solid fa-phone"></i>
                        </div>
                        <div>
                            <h5>Telefonszám</h5>
                            <p><a href="tel:+36301234567" class="phone-link">+36 30 123 4567</a></p>
                        </div>
                    </div>

                    <!-- EMAIL -->
                    <div class="contact-info-item">
                        <div class="contact-icon-box">
                            <i class="fa-solid fa-envelope"></i>
                        </div>
                        <div>
                            <h5>E-mail</h5>
                            <p><a href="mailto:abmasszazsinfo@gmail.com" class="email-link">abmasszazsinfo@gmail.com</a></p>
                        </div>
                    </div>

                    <!-- NYITVATARTÁS -->
                    <div>
                        <h5 class="brand mb-3">Nyitvatartás</h5>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span style="color: var(--j-muted);">Hétfő - Vasárnap</span>
                            <span class="brand" style="color: var(--j-gold); font-size: 1.1rem;">09:00 - 18:00</span>
                        </div>
                        <p class="small" style="color: var(--j-muted); margin: 0;">Szünetnap nélkül nyitva</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- KAPCSOLAT & VÉLEMÉNY SZEKCIÓ - TISZTA FORMA -->
<section id="contact-form" class="bg-light-zen" style="padding: 80px 0;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="section-header reveal mb-5">
                    <span class="info-label">KAPCSOLAT</span>
                    <h2>Írjon nekünk</h2>
                </div>

                <!-- TABULÁTOROK -->
                <div class="mb-5">
                    <div class="row g-3 text-center justify-content-center">
                        <div class="col-auto">
                            <button class="tab-btn active" data-tab="contact">
                                <i class="fa-solid fa-envelope me-2"></i>Kapcsolatfelvétel
                            </button>
                        </div>
                        <div class="col-auto">
                            <button class="tab-btn" data-tab="review">
                                <i class="fa-solid fa-star me-2"></i>Élmény megosztása
                            </button>
                        </div>
                    </div>
                </div>

                <!-- KAPCSOLAT FORM -->
                <div id="tab-contact-content" class="tab-form-content reveal">
                    <form id="contactForm" class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label">Név</label>
                            <input type="text" name="c_name" class="form-control" placeholder="Az Ön teljes neve" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">E-mail</label>
                            <input type="email" name="c_email" class="form-control" placeholder="email@example.com" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Telefonszám</label>
                            <input type="tel" name="c_tel" class="form-control phone-format" placeholder="+36 30 123 4567" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Üzenet</label>
                            <textarea name="c_message" rows="6" class="form-control" placeholder="Írja le, miben segíthetünk Önnek..." required></textarea>
                        </div>
                        <div class="col-12 text-center pt-2">
                            <button type="submit" class="btn btn-dark px-5">Üzenet küldése</button>
                        </div>
                    </form>
                </div>

                <!-- VÉLEMÉNY FORM -->
                <div id="tab-review-content" class="tab-form-content reveal" style="display: none;">
                    <form id="reviewForm" class="row g-4">
                        <div class="col-12">
                            <label class="form-label">Mely kezelést választotta?</label>
                            <select name="r_service" class="form-select" required>
                                <option value="" disabled selected>Válasszon kezelést...</option>
                                <?php foreach($services as $s): ?>
                                    <option value="<?= htmlspecialchars($s['name']) ?>"><?= htmlspecialchars($s['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Az Ön neve</label>
                            <input type="text" name="r_user_name" class="form-control" placeholder="Teljes név" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">E-mail cím</label>
                            <input type="email" name="r_user_email" class="form-control" placeholder="email@example.com" required>
                        </div>
                        
                        <div class="col-12 text-center py-3">
                            <label class="form-label mb-3 d-block">Hogy érezte magát nálunk?</label>
                            <div class="star-rating-wrapper">
                                <div class="star-rating">
                                    <input type="radio" id="fancy-s-5" name="rating" value="5" checked><label for="fancy-s-5"><i class="fa-solid fa-star"></i></label>
                                    <input type="radio" id="fancy-s-4" name="rating" value="4"><label for="fancy-s-4"><i class="fa-solid fa-star"></i></label>
                                    <input type="radio" id="fancy-s-3" name="rating" value="3"><label for="fancy-s-3"><i class="fa-solid fa-star"></i></label>
                                    <input type="radio" id="fancy-s-2" name="rating" value="2"><label for="fancy-s-2"><i class="fa-solid fa-star"></i></label>
                                    <input type="radio" id="fancy-s-1" name="rating" value="1"><label for="fancy-s-1"><i class="fa-solid fa-star"></i></label>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Vélemény</label>
                            <textarea name="r_message" rows="6" class="form-control" placeholder="Ossza meg velünk a tapasztalatait..." required></textarea>
                        </div>
                        
                        <div class="col-12 text-center pt-2">
                            <button type="submit" class="btn btn-dark px-5">Vélemény küldése</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- TOAST ÜZENETEK -->
<div class="toast-container position-fixed bottom-0 end-0 p-4" style="z-index: 9999;">
    <div id="statusToast" class="toast border-0 rounded-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-body p-3">
            <div class="d-flex align-items-center">
                <i id="toastIcon" class="fa-solid fa-leaf me-3" style="color: var(--j-gold);"></i>
                <div>
                    <strong id="toastTitle" class="d-block brand">Sikeres művelet</strong>
                    <span id="toastMessage" class="small text-muted">A feldolgozás sikeres volt.</span>
                </div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>
</div>

<!-- SCRIPTS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/hu.js"></script>
<script>
function showApiToast(title, message, isError = false) {
    const toastEl = document.getElementById('statusToast');
    const toastTitle = document.getElementById('toastTitle');
    const toastMessage = document.getElementById('toastMessage');
    const toastIcon = document.getElementById('toastIcon');
    
    if (!toastEl) return;

    if (isError) {
        toastEl.style.borderLeft = "5px solid #dc3545";
        toastIcon.className = "fa-solid fa-circle-exclamation me-3 text-danger";
        toastTitle.innerText = title || "Hiba történt";
    } else {
        toastEl.style.borderLeft = "5px solid var(--j-gold)";
        toastIcon.className = "fa-solid fa-leaf me-3";
        toastIcon.style.color = "var(--j-gold)";
        toastTitle.innerText = title || "Sikeres művelet";
    }
    
    toastMessage.innerHTML = message;
    const toast = new bootstrap.Toast(toastEl);
    toast.show();
}

document.addEventListener('DOMContentLoaded', () => {

    // --- IDŐPONTFOGLALÁS ---
    const bookingForm = document.getElementById('apiBookingForm');
    if (bookingForm) {
        bookingForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = {
                customer_name: document.getElementById('c_name').value,
                service_id: document.getElementById('c_service').value,
                email: document.getElementById('c_email').value,
                tel: document.getElementById('c_tel').value,
                booking_date: document.getElementById('booking_date').value,
                booking_time: document.querySelector('input[name="booking_time"]:checked')?.value
            };

            if(!formData.booking_time) { 
                showApiToast("Hiba", "Kérjük, válasszon időpontot!", true); 
                return; 
            }

            fetch('api.php?request=bookings', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(formData)
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    showApiToast("Sikeres foglalás", data.success);
                    bookingForm.reset();
                    document.getElementById('time-selection-area').style.display = 'none';
                } else {
                    throw new Error(data.error || "Hiba történt.");
                }
            })
            .catch(err => showApiToast("Hiba", err.message, true));
        });
    }

    // --- KAPCSOLATI ÜZENET ---
    const contactForm = document.getElementById('contactForm');
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const contactData = {
                name: this.querySelector('[name="c_name"]').value,
                email: this.querySelector('[name="c_email"]').value,
                tel: this.querySelector('[name="c_tel"]').value,
                message: this.querySelector('[name="c_message"]').value
            };

            fetch('api.php?request=messages', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(contactData)
            })
            .then(res => res.json())
            .then(data => {
                showApiToast("Üzenet elküldve", "Köszönjük! Hamarosan válaszolunk.");
                contactForm.reset();
            })
            .catch(err => showApiToast("Hiba", "Nem sikerült elküldeni.", true));
        });
    }

    // --- VÉLEMÉNYEK ---
    const reviewForm = document.getElementById('reviewForm');
    if (reviewForm) {
        reviewForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const ratingInput = this.querySelector('[name="rating"]:checked');
            
            if (!ratingInput) {
                showApiToast("Hiba", "K��rjük, értékelje munkánkat!", true);
                return;
            }

            const reviewData = {
                user_name: this.querySelector('[name="r_user_name"]').value,
                service_name: this.querySelector('[name="r_service"]').value,
                rating: ratingInput.value,
                comment: this.querySelector('[name="r_message"]').value
            };

            fetch('api.php?request=reviews', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(reviewData)
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    showApiToast("Köszönjük!", "Értékelése rögzítve.");
                    reviewForm.reset();
                    setTimeout(() => location.reload(), 1500);
                } else {
                    throw new Error(data.error);
                }
            })
            .catch(err => showApiToast("Hiba", err.message || "Hiba történt.", true));
        });
    }

    // --- VOUCHER VÁSÁRLÁS ---
    const voucherForm = document.getElementById('voucherForm');
    if (voucherForm) {
        voucherForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const voucherData = {
                recipient: this.querySelector('[name="v_recipient"]').value,
                amount: this.querySelector('[name="v_amount"]').value,
                email: this.querySelector('[name="v_buyer_email"]').value,
                tel: this.querySelector('[name="v_buyer_tel"]').value
            };

            fetch('api.php?request=vouchers', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(voucherData)
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    showApiToast("Sikeres vásárlás!", "Kód: " + data.code);
                    this.reset();
                } else {
                    throw new Error(data.error);
                }
            })
            .catch(err => showApiToast("Hiba", err.message, true));
        });
    }

    // --- NAPTÁR ---
    if (document.getElementById('booking_date')) {
        flatpickr("#booking_date", {
            locale: "hu", 
            minDate: "today", 
            dateFormat: "Y-m-d",
            onChange: function(selectedDates, dateStr) {
                document.getElementById('time-selection-area').style.display = 'block';
                
                fetch(`api.php?request=bookings&date=${dateStr}`)
                    .then(res => res.json())
                    .then(taken => {
                        document.querySelectorAll('.btn-check').forEach(s => {
                            const label = document.querySelector(`label[for="${s.id}"]`);
                            const isTaken = taken.some(t => t.startsWith(s.value));
                            s.disabled = isTaken;
                            if(label) {
                                label.style.opacity = isTaken ? "0.2" : "1";
                                label.style.textDecoration = isTaken ? "line-through" : "none";
                            }
                        });
                    });
            }
        });
    }
// --- TAB SWITCHING ---
document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const tabName = this.getAttribute('data-tab');
        
        // Remove active class from all buttons
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        
        // Hide all content
        document.querySelectorAll('.tab-form-content').forEach(content => {
            content.style.display = 'none';
        });
        
        // Add active class to clicked button
        this.classList.add('active');
        
        // Show selected content
        document.getElementById(`tab-${tabName}-content`).style.display = 'block';
    });
});
    document.querySelectorAll('[name="c_tel"], [name="v_buyer_tel"]').forEach(input => {
    input.addEventListener('input', (e) => {
        let v = e.target.value.replace(/\D/g, '');
        
        if (v.startsWith('36')) {
            v = v.substring(2);
        }
        
        if (v.length > 9) v = v.substring(0, 9);
        
        if (v.length === 0) {
            e.target.value = '';
        } else if (v.length <= 2) {
            e.target.value = '+36 ' + v;
        } else if (v.length <= 5) {
            e.target.value = '+36 ' + v.substring(0, 2) + ' ' + v.substring(2);
        } else if (v.length <= 8) {
            e.target.value = '+36 ' + v.substring(0, 2) + ' ' + v.substring(2, 5) + ' ' + v.substring(5);
        } else {
            e.target.value = '+36 ' + v.substring(0, 2) + ' ' + v.substring(2, 5) + ' ' + v.substring(5, 8) + ' ' + v.substring(8);
        }
    });

    input.addEventListener('focus', (e) => {
        if (e.target.value === '') {
            e.target.value = '+36 ';
        }
    });

    input.addEventListener('blur', (e) => {
        if (e.target.value === '+36 ' || e.target.value === '+36') {
            e.target.value = '';
        }
    });
});

    // --- VOUCHER PREVIEW UPDATE ---
    const vAmountSelect = document.getElementById('v_amount');
    const vNameInput = document.getElementById('v_name');
    if (vAmountSelect) {
        vAmountSelect.addEventListener('change', () => {
            const amount = vAmountSelect.value;
            document.getElementById('p-amount').innerText = (amount / 1000) + ' 000 Ft';
        });
    }
    if (vNameInput) {
        vNameInput.addEventListener('input', () => {
            const name = vNameInput.value || 'VENDÉGÜNK NEVE';
            document.getElementById('p-name').innerText = name.toUpperCase();
        });
    }

    // --- GÖRGETÉSI ANIMÁCIÓK ---
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) entry.target.classList.add('visible');
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('.reveal').forEach(r => observer.observe(r));
});

// --- PRELOADER ---
window.addEventListener('load', () => {
    const preloader = document.getElementById('zen-preloader');
    if (preloader) {
        setTimeout(() => {
            preloader.style.opacity = '0';
            setTimeout(() => preloader.style.display = 'none', 800);
        }, 600);
    }
});

// --- TÉMA VÁLTÁSA ---
function toggleTheme() {
    const isDark = document.body.classList.toggle('dark-theme');
    localStorage.setItem('theme', isDark ? 'dark' : 'light');
}

(function() {
    if (localStorage.getItem('theme') === 'dark') {
        document.body.classList.add('dark-theme');
    }
})();
</script>

<?php include '../config/footer.php'; ?>
</body>
</html>