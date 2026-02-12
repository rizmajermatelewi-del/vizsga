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
        /* Speciális javítás a Hero láthatóságához */
        .hero-zen {
            position: relative;
            background-image: url('https://images.unsplash.com/photo-1540555700478-4be289fbecef?q=80&w=2070');
            background-size: cover;
            background-position: center;
            height: 90vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Sötétítő réteg, hogy látszódjon a fehér szöveg */
        .hero-zen::before {
            content: "";
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.4); /* 40%-os sötétítés */
            z-index: 1;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            text-align: center;
            max-width: 800px;
        }

        .btn-zen-light {
            background: transparent;
            border: 1px solid #fff;
            color: #fff;
            padding: 15px 40px;
            text-decoration: none;
            letter-spacing: 3px;
            font-size: 0.8rem;
            transition: all 0.4s ease;
            display: inline-block;
        }

        .btn-zen-light:hover {
            background: #fff;
            color: #000;
        }

        /* ÚJ: Kapcsolat és Térkép osztott rész stílusai */
        .contact-split { 
            display: flex; 
            flex-wrap: wrap; 
            background: var(--j-bg); 
            min-height: 500px;
        }
        .map-half { 
            flex: 1; 
            min-width: 300px; 
            min-height: 400px;
            filter: grayscale(1) contrast(1.1) opacity(0.8); 
        }
        .info-half { 
            flex: 1; 
            min-width: 300px; 
            padding: 80px; 
            display: flex; 
            flex-direction: column; 
            justify-content: center; 
            background-color: #fcfaf7; /* Finom csontszín Japandi háttér */
        }
        .dark-theme .info-half { background-color: #1a1a1a; }

        @media (max-width: 768px) { .info-half { padding: 40px 20px; } }
        .review-card {
        transition: transform 0.4s ease, box-shadow 0.4s ease;
        border-radius: 0; /* Japandi minimalizmus */
    }

    .review-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(184, 146, 74, 0.08) !important;
    }

    .stars i {
        font-size: 0.8rem;
        margin: 0 2px;
    }
    </style>
</head>
<body data-bs-spy="scroll" data-bs-target=".navbar" data-bs-offset="100" class="<?= (isset($_COOKIE['theme']) && $_COOKIE['theme'] == 'dark') ? 'dark-theme' : '' ?>">
<?php include 'assets/user_navbar.php'; ?>
<div id="zen-preloader">
    <div class="zen-leaf"></div>
    <p>AB MASSZÁZS</p>
</div>
<header class="hero-zen reveal">
    <div class="hero-content text-center">
        <h1 class="display-4 text-white">ÜDVÖZÖLJÜK A CSENDBEN</h1>
        <h1 class="display-2 brand text-white my-4">Találja meg belső békéjét</h1>
        <p class="lead text-white opacity-75 mb-5 px-3">Japán rituálék és skandináv minimalizmus a teljes testi-lelki megújulásért.</p>
        <a href="#booking" class="btn-zen-light">IDŐPONTOT FOGLALOK</a>
    </div>
    <div class="scroll-indicator">
        <div class="scroll-line"></div>
    </div>
</header>

<section id="philosophy" class="container py-5 my-5">
    <div class="row align-items-center g-5">
        <div class="col-lg-6 reveal">
            <div class="philosophy-img-wrapper">
                <img src="https://images.unsplash.com/photo-1540555700478-4be289fbecef?q=80&w=2070" alt="Zen Spa" class="img-fluid zen-img">
            </div>
        </div>
        <div class="col-lg-5 offset-lg-1 reveal bg-white">
            <span class="info-label">AB MASSZÁZS</span>
            <h2 class="brand mb-4">Text</h2>
            <p class="text-secondary mb-4">Text</p>
            <div class="row g-4 mt-2">
                <div class="col-6">
                    <h5 class="brand h6">Text</h5>
                    <p class="small text-muted">Text</p>
                </div>
                <div class="col-6">
                    <h5 class="brand h6">Text</h5>
                    <p class="small text-muted">Text</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="services" class="py-5 bg-accent-soft">
    <div class="container py-5 reveal">
        <div class="text-center mb-5">
            <span class="info-label">SZOLGÁLTATÁSAINK</span>
            <h2 class="brand mt-2">Válogatott masszázsok</h2>
        </div>
        <div class="row g-4">
            <?php foreach($services as $s): ?>
            <div class="col-md-4">
                <div class="z-card text-center h-100 d-flex flex-column justify-content-between p-5">
                    <div>
                        <h4 class="brand h5 mb-3"><?= htmlspecialchars($s['name']) ?></h4>
                        <div class="divider-zen mx-auto mb-3"></div>
                        <p class="text-muted small mb-4"><?= number_format($s['price'], 0, ',', ' ') ?> Ft</p>
                    </div>
                    <a href="#booking" class="text-dark small text-decoration-none fw-bold" style="letter-spacing: 2px;">FOGLALÁS →</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section id="masters" class="py-5">
    <div class="container py-5 reveal">
        <div class="text-center mb-5">
            <span class="info-label">SZAKÉRTELEM</span>
            <h2 class="brand mt-2">Mestereink</h2>
        </div>
        <div class="row g-4 justify-content-center">
            <div class="col-md-4 text-center">
                <div class="master-img mb-4 mx-auto" style="width: 200px; height: 250px; background: url('') center/cover;"></div>
                <h5 class="brand">Név</h5>
                <p class="small text-muted px-4">Tulajdonság</p>
            </div>
            <div class="col-md-4 text-center">
                <div class="master-img mb-4 mx-auto" style="width: 200px; height: 250px; background: url('') center/cover;"></div>
                <h5 class="brand">Név</h5>
                <p class="small text-muted px-4">Tulajdonság</p>
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container text-center py-5">
        <h2 class="brand mb-5 reveal">Text</h2>
        <div class="row g-4">
            <div class="col-md-4 reveal">
                <div class="stat-circle mx-auto mb-4">01</div>
                <h4 class="brand h5">Text</h4>
                <p class="small text-muted px-4">Text</p>
            </div>
            <div class="col-md-4 reveal">
                <div class="stat-circle mx-auto mb-4">02</div>
                <h4 class="brand h5">Text</h4>
                <p class="small text-muted px-4">Text</p>
            </div>
            <div class="col-md-4 reveal">
                <div class="stat-circle mx-auto mb-4">03</div>
                <h4 class="brand h5">Text</h4>
                <p class="small text-muted px-4">Text</p>
            </div>
        </div>
    </div>
</section>

<section id="vouchers" class="py-5">
    <div class="container py-5 reveal">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6">
                <div id="voucher-preview" class="p-5 bg-white border text-center shadow-sm" style="border-radius: 15px;">
                    <h3 class="brand mb-4" style="letter-spacing: 5px;">AB MASSZÁZS</h3>
                    <h2 id="p-amount" class="my-5" style="font-weight: 300;">10 000 Ft</h2>
                    <p id="p-name" class="text-uppercase small fw-bold opacity-50" style="letter-spacing: 4px;">VENDÉGÜNK NEVE</p>
                </div>
            </div>

            <div class="col-lg-5 offset-lg-1">
                <span class="info-label">AJÁNDÉK</span>
                <h2 class="brand mb-4">Adjon élményt szeretteinek</h2>
                
                <?php if(isset($_SESSION['user_id'])): ?>
                    <form id="voucherForm" class="row g-3">
                        <div class="row g-3">
                            <div class="col-12 mb-2">
                                <label class="small text-muted mb-1">Vásárló:</label>
                                <input type="text" name="v_buyer_name" class="form-control bg-transparent border-0 p-0 fw-bold" 
                                       style="font-size: 1.1rem; color: var(--j-dark);"
                                       value="<?= htmlspecialchars($_SESSION['user_name'] ?? 'Vendégünk') ?>" readonly>
                            </div>
                            
                            <div class="col-12">
                                <input type="text" name="v_recipient" id="v_name" class="form-control" placeholder="KINEK A RÉSZÉRE? (A megajándékozott neve)" required>
                            </div>
                            
                            <div class="col-md-6">
                                <input type="email" name="v_buyer_email" class="form-control" placeholder="AZ ÖN EMAIL CÍME" required>
                            </div>
                            <div class="col-md-6">
                                <input type="tel" name="v_buyer_tel" id="v_buyer_tel" class="form-control" placeholder="TELEFONSZÁM" value="+36 " required maxlength="13">
                            </div>
                            
                            <div class="col-12">
                                <select name="v_amount" id="v_amount" class="form-select mb-4">
                                    <option value="10000">10 000 Ft</option>
                                    <option value="25000">25 000 Ft</option>
                                    <option value="50000">50 000 Ft</option>
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn-zen w-100">Vásárlás megerősítése</button>
                    </form>
                <?php else: ?>
                    <div class="p-4 border border-dashed text-center">
                        <p class="small text-muted mb-3">Az ajándékutalvány vásárlásához bejelentkezés szükséges.</p>
                        <a href="login.php" class="btn-zen btn-sm px-4">BELÉPÉS</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<script>
    // Telefonszám formázó az utalványhoz is
    const vPhone = document.getElementById('v_buyer_tel');
    if(vPhone) {
        vPhone.addEventListener('input', function(e) {
            let v = e.target.value.replace(/[^\d+]/g, '');
            if (!v.startsWith('+36')) v = '+36 ';
            if (v.length > 13) v = v.substring(0, 13);
            e.target.value = v;
        });
    }
</script>

<section id="reviews" class="py-5" style="background-color: #fdfcfb;">
    <div class="container">
        <div class="text-center mb-5">
            <span class="section-label" style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 3px; color: #b8924a;">Vendégeink élményei</span>
            <h2 style="font-family: 'Shippori Mincho', serif; font-size: 2.5rem;">Masszázs élményei</h2>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="review-card p-4 h-100 shadow-sm bg-white border-0 text-center">
                    <div class="stars mb-3" style="color: #b8924a;">
                        <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                    </div>
                    <p class="fst-italic opacity-75">Text</p>
                    <hr class="w-25 mx-auto opacity-25">
                    <h6 class="mb-0" style="letter-spacing: 1px;">Text</h6>
                    <small class="text-muted">Text</small>
                </div>
            </div>

            <div class="col-md-4">
                <div class="review-card p-4 h-100 shadow-sm bg-white border-0 text-center">
                    <div class="stars mb-3" style="color: #b8924a;">
                        <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                    </div>
                    <p class="fst-italic opacity-75">Text</p>
                    <hr class="w-25 mx-auto opacity-25">
                    <h6 class="mb-0" style="letter-spacing: 1px;">Text</h6>
                    <small class="text-muted">Text</small>
                </div>
            </div>

            <div class="col-md-4">
                <div class="review-card p-4 h-100 shadow-sm bg-white border-0 text-center">
                    <div class="stars mb-3" style="color: #b8924a;">
                        <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-regular fa-star"></i>
                    </div>
                    <p class="fst-italic opacity-75">Text</p>
                    <hr class="w-25 mx-auto opacity-25">
                    <h6 class="mb-0" style="letter-spacing: 1px;">Text</h6>
                    <small class="text-muted">Text</small>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="booking" class="py-5">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="text-center mb-5">
                    <span class="info-label">Foglalás</span>
                    <h2 class="brand mt-2">Text</h2>
                </div>
                <form id="apiBookingForm" class="row g-4">
    <div class="col-md-6">
        <input type="text" name="customer_name" id="c_name" class="form-control" placeholder="NÉV" required>
    </div>
    <div class="col-md-6">
        <input type="email" name="customer_email" id="c_email" class="form-control" placeholder="E-MAIL" required>
    </div>
    <div class="col-md-6">
        <input type="text" name="tel" id="c_tel" class="form-control" placeholder="TELEFON" required>
    </div>
    <div class="col-12">
        <select name="service_id" id="c_service" class="form-select" required>
            <option value="" disabled selected>VÁLASSZON KEZELÉST...</option>
            <?php foreach($services as $s): ?>
                <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-12">
        <input type="text" id="booking_date" name="booking_date" class="form-control" placeholder="DÁTUM KIVÁLASZTÁSA" required readonly>
    </div>
    
    <div id="time-selection-area" class="col-12 mt-4" style="display:none;">
        <div class="time-grid">
            <?php foreach(['09:00','10:00','11:00','13:00','14:00','15:00','16:00','17:00'] as $t): ?>
                <div class="time-item">
                    <input type="radio" class="btn-check" name="booking_time" id="t-<?= $t ?>" value="<?= $t ?>" required>
                    <label class="time-box w-100 d-block text-center" for="t-<?= $t ?>"><?= $t ?></label>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <div class="col-12 text-center mt-5">
        <button type="submit" id="confirmBooking" class="btn btn-zen-gold w-100 py-3 rounded-0 text-uppercase fw-bold letter-spacing-2">Foglalás megerősítése</button>
    </div>
</form>
            </div>
        </div>
    </div>
</section>
<section id="successModalSection" class="py-5">
<div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius:0; background: #fcfaf7;">
            <div class="modal-body p-5 text-center">
                <div class="mb-4">
                    <i class="fas fa-check-circle fa-3x text-success opacity-50"></i>
                </div>
                <h3 class="brand">Köszönjük!</h3>
                <p class="text-muted small my-4">Időpontfoglalását sikeresen rögzítettük. Hamarosan várunk szeretettel a nyugalom szigetén.</p>
                <button type="button" class="btn-zen w-100" data-bs-dismiss="modal">Rendben</button>
            </div>
        </div>
    </div>
</div>
</section>

<section id="contact" class="py-5">
    <div class="container">
        <div class="row g-0 shadow-lg" style="background: #fdfcfb;">
            <div class="col-lg-7 p-0 overflow-hidden">
                <div class="map-wrapper h-100">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2708.263382257527!2d19.322708676798364!3d47.250552812495776!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4741918afaf15229%3A0xa1f0e0ae13433cbc!2zSW7DoXJjcywgTcOhanVzIDEuIHUuIDEyLCAyMzY1!5e0!3m2!1shu!2shu!4v1770212748654!5m2!1shu!2shu" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>

            <div class="col-lg-5 p-5 d-flex flex-column justify-content-center">
                <div class="contact-content">
                    <span class="text-uppercase small" style="letter-spacing: 4px; color: #b8924a;">Elérhetőség</span>
                    <h2 class="display-6 my-4" style="font-family: 'Shippori Mincho', serif;">Látogasson el hozzánk</h2>
                    
                    <div class="info-item mb-4">
                        <i class="fa-solid fa-location-dot me-3" style="color: #b8924a;"></i>
                        <span class="text-muted">2365, Inárcs, Május 1 utca 12.</span>
                    </div>
                    
                    <div class="info-item mb-4">
                        <i class="fa-solid fa-phone me-3" style="color: #b8924a;"></i>
                        <span class="text-muted">+36 30 123 4567</span>
                    </div>
                    
                    <div class="info-item mb-5">
                        <i class="fa-solid fa-envelope me-3" style="color: #b8924a;"></i>
                        <span class="text-muted">ab.masszazs@gmail.com</span>
                    </div>

                    <hr class="opacity-10 my-4">

                    <h5 class="small text-uppercase mb-3" style="letter-spacing: 2px;">Nyitvatartás</h5>
                    <p class="text-muted mb-0">Hétfő - Vasárnap</p>
                    <p class="h5" style="color: #1a1a1a;">09:00 - 18:00</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-light-zen">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="zen-form-card">
                    <ul class="nav nav-tabs border-0 justify-content-center mb-5" id="zenTab" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-contact">Kapcsolatfelvétel</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-review">Élmény megosztása</button>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="tab-contact">
                            <form id="contactForm" class="row g-4">
                                <div class="col-md-6">
                                    <label class="small text-muted mb-1">Név</label>
                                    <input type="text" name="c_name" class="zen-input" placeholder="Az Ön neve" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="small text-muted mb-1">Email</label>
                                    <input type="email" name="c_email" class="zen-input" placeholder="pelda@email.com" required>
                                </div>
                                <div class="col-12">
                                    <label class="small text-muted mb-1">Telefonszám</label>
                                    <input type="tel" name="c_tel" class="zen-input" placeholder="+36 30 123 4567" required>
                                </div>
                                <div class="col-12">
                                    <label class="small text-muted mb-1">Üzenet</label>
                                    <textarea name="c_message" rows="4" class="zen-input" placeholder="Miben segíthetünk Önnek?" required></textarea>
                                </div>
                                <div class="col-12 text-center mt-5">
                                    <button type="submit" class="btn-zen-gold">ÜZENET KÜLDÉSE</button>
                                </div>
                            </form>
                        </div>

                        <div class="tab-pane fade" id="tab-review">
    <form id="reviewForm" class="row g-4">
        <div class="col-12">
            <label class="small text-muted mb-1">Választott szolgáltatás</label>
            <select name="r_service" class="zen-input" required>
                <option value="" disabled selected>Válasszon kezelést...</option>
                <?php foreach($services as $s): ?>
                    <option value="<?= htmlspecialchars($s['name']) ?>"><?= htmlspecialchars($s['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="col-md-6">
            <input type="text" name="r_user_name" class="zen-input" placeholder="Az Ön neve" required>
        </div>
        <div class="col-md-6">
            <input type="email" name="r_user_email" class="zen-input" placeholder="Email címe" required>
        </div>
        
        <div class="col-12 text-center py-2">
            <p class="small text-muted mb-2">Hogy érezte magát nálunk?</p>
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
            <textarea name="r_message" rows="4" class="zen-input" placeholder="Írja le élményeit..." required></textarea>
        </div>
        
        <div class="col-12 text-center">
            <button type="submit" class="btn-zen-gold">VÉLEMÉNY KÜLDÉSE</button>
        </div>
    </form>
</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    /* Kártya alapstílus */
    .zen-form-card { background: #ffffff; padding: 60px; border: 1px solid rgba(184, 146, 74, 0.1); }
    #zenTab .nav-link { background: none; border: none; color: #999; text-transform: uppercase; letter-spacing: 3px; font-size: 0.8rem; padding: 10px 30px; position: relative; transition: 0.4s; }
    #zenTab .nav-link.active { color: #1a1a1a; font-weight: 600; }
    #zenTab .nav-link.active::after { content: ''; position: absolute; bottom: 0; left: 30%; width: 40%; height: 1px; background: #b8924a; }
    .zen-input { width: 100%; background: transparent; border: none; border-bottom: 1px solid rgba(0,0,0,0.08); padding: 12px 0; font-size: 0.95rem; transition: 0.3s; border-radius: 0; outline: none; }
    .zen-input:focus { border-bottom: 1px solid #b8924a; }
    .btn-zen-gold { background: #1a1a1a; color: #fff; border: none; padding: 15px 45px; letter-spacing: 2px; font-size: 0.75rem; transition: 0.4s ease; cursor: pointer; }
    .btn-zen-gold:hover { background: #b8924a; transform: translateY(-2px); }
    .star-rating { display: flex; flex-direction: row-reverse; justify-content: center; gap: 15px; }
    .star-rating input { display: none; }
    .star-rating label { color: #e0e0e0; font-size: 1.5rem; cursor: pointer; transition: 0.3s; }
    .star-rating label:hover, .star-rating label:hover ~ label, .star-rating input:checked ~ label { color: #b8924a; }
</style>

<div class="toast-container position-fixed bottom-0 end-0 p-4" style="z-index: 9999;">
    <div id="statusToast" class="toast border-0 rounded-0" role="alert" aria-live="assertive" aria-atomic="true" style="background: #fffcf8; border-left: 4px solid #b8924a !important; box-shadow: 10px 10px 30px rgba(0,0,0,0.08);">
        <div class="toast-body p-3">
            <div class="d-flex align-items-center">
                <i id="toastIcon" class="fa-solid fa-leaf me-3" style="color: #b8924a;"></i>
                <div>
                    <strong id="toastTitle" class="d-block" style="font-family: 'Shippori Mincho', serif; color: #1a1a1a;">Sikeres művelet</strong>
                    <span id="toastMessage" class="small text-muted">A feldolgozás sikeres volt.</span>
                </div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/hu.js"></script>
<script>
/**
 * AB MASSZÁZS - Stabilizált Japandi Script Block v4.0
 * Minden funkció egy helyen: Foglalás, Kapcsolat, Vélemény, Validáció
 */

// --- 1. JAPANDI STÍLUSÚ ÉRTESÍTÉSEK (TOAST) ---
function showApiToast(title, message, isError = false) {
    const toastEl = document.getElementById('statusToast');
    const toastTitle = document.getElementById('toastTitle');
    const toastMessage = document.getElementById('toastMessage');
    const toastIcon = document.getElementById('toastIcon');
    
    if (!toastEl) return; // Biztonsági ellenőrzés

    // Stílus beállítása a branding szerint
    if (isError) {
        toastEl.style.borderLeft = "5px solid #dc3545"; // Hiba piros
        toastIcon.className = "fa-solid fa-circle-exclamation me-3 text-danger";
        toastTitle.innerText = title || "Hiba történt";
    } else {
        toastEl.style.borderLeft = "5px solid #b8924a"; // Japandi arany
        toastIcon.className = "fa-solid fa-leaf me-3";
        toastIcon.style.color = "#b8924a";
        toastTitle.innerText = title || "Sikeres művelet";
    }
    
    toastMessage.innerHTML = message;
    const toast = new bootstrap.Toast(toastEl);
    toast.show();
}

document.addEventListener('DOMContentLoaded', () => {

    // --- 2. IDŐPONTFOGLALÁS KEZELÉSE ---
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
                showApiToast("Hiba", "Kérjük, válasszon időpontot a szabad sávok közül!", true); 
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
                    if (document.getElementById('time-selection-area')) {
                        document.getElementById('time-selection-area').style.display = 'none';
                    }
                } else {
                    throw new Error(data.error || "Hiba történt a mentés során.");
                }
            })
            .catch(err => {
                console.error("Booking Error:", err);
                showApiToast("Hiba", err.message, true);
            });
        });
    }

    // --- 3. KAPCSOLATI ÜZENET KÜLDÉSE ---
    const contactForm = document.getElementById('contactForm');
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const contactData = {
                name: document.getElementById('contact_name').value,
                email: document.getElementById('contact_email').value,
                tel: document.getElementById('contact_tel').value, // SQL: phone oszlop
                message: document.getElementById('contact_message').value
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
            .catch(err => showApiToast("Hiba", "Nem sikerült elküldeni az üzenetet.", true));
        });
    }

    // --- 4. VÉLEMÉNYEK BEKÜLDÉSE (SQL SZINKRONIZÁLT) ---
    const reviewForm = document.getElementById('reviewForm');
    if (reviewForm) {
        reviewForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const ratingInput = this.querySelector('[name="rating"]:checked');
            
            if (!ratingInput) {
                showApiToast("Hiba", "Kérjük, értékelje munkánkat csillagokkal is!", true);
                return;
            }

            const reviewData = {
                user_name: this.querySelector('[name="r_user_name"]').value,
                service_name: this.querySelector('[name="r_service"]').value,
                rating: ratingInput.value,
                comment: this.querySelector('[name="r_message"]').value // SQL: comment mező
            };

            fetch('api.php?request=reviews', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(reviewData)
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    showApiToast("Köszönjük!", "Értékelését sikeresen rögzítettük.");
                    reviewForm.reset();
                    // Modal bezárása
                    const modalEl = document.getElementById('reviewModal');
                    const modal = bootstrap.Modal.getInstance(modalEl);
                    if(modal) modal.hide();
                    
                    // Frissítés, hogy látszódjon az új vélemény
                    setTimeout(() => location.reload(), 1500);
                } else {
                    throw new Error(data.error);
                }
            })
            .catch(err => showApiToast("Hiba", err.message || "Hiba történt a mentéskor.", true));
        });
    }

    // --- 5. NAPTÁR ÉS IDŐSÁVOK (FLATPICKR) ---
    if (document.getElementById('booking_date')) {
        flatpickr("#booking_date", {
            locale: "hu", 
            minDate: "today", 
            dateFormat: "Y-m-d",
            onChange: function(selectedDates, dateStr) {
                const timeArea = document.getElementById('time-selection-area');
                if(timeArea) timeArea.style.display = 'block';
                
                fetch(`api.php?request=bookings&date=${dateStr}`)
                    .then(res => res.json())
                    .then(taken => {
                        document.querySelectorAll('.btn-check').forEach(s => {
                            const label = document.querySelector(`label[for="${s.id}"]`);
                            // Ellenőrizzük, hogy az időpont (pl. 09:00) benne van-e a foglaltak között
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

    // --- 6. TELEFONSZÁM AUTOMATIKUS FORMÁZÓ (+36) ---
    const phoneFields = ['c_tel', 'contact_tel', 'v_buyer_tel'];
    phoneFields.forEach(id => {
        const input = document.getElementById(id);
        if (input) {
            input.addEventListener('input', (e) => {
                let v = e.target.value.replace(/[^\d+]/g, '');
                if (!v.startsWith('+36')) v = '+36' + v.replace(/^\+?36?/, '');
                if (v.length > 12) v = v.substring(0, 12);
                e.target.value = v;
            });
            input.addEventListener('focus', (e) => { 
                if (e.target.value === "") e.target.value = "+36"; 
            });
        }
    });

    // --- 7. GÖRGETÉSI ANIMÁCIÓK (REVEAL) ---
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) entry.target.classList.add('visible');
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('.reveal').forEach(r => observer.observe(r));
});

// --- 8. PRELOADER (ZEN ÉLMÉNY) ---
window.addEventListener('load', () => {
    const preloader = document.getElementById('zen-preloader');
    if (preloader) {
        setTimeout(() => {
            preloader.style.opacity = '0';
            setTimeout(() => preloader.style.display = 'none', 800);
        }, 600);
    }
});
const loginForm = document.getElementById('loginForm');
if (loginForm) {
    loginForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const loginData = {
            email: this.querySelector('[name="email"]').value,
            password: this.querySelector('[name="password"]').value
        };

        fetch('api.php?request=login', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(loginData)
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showApiToast("Üdvözöljük!", data.success);
                setTimeout(() => window.location.href = data.redirect, 1000);
            } else {
                showApiToast("Hiba", data.error, true);
            }
        });
    });
}
// --- 5. VOUCHER VÁSÁRLÁS ÉS ELŐNÉZET (STABILIZÁLT) ---
const voucherForm = document.getElementById('voucherForm');

if (voucherForm) {
    console.log("Voucher form detektálva, eseményfigyelő aktív."); // Ez látszódjon a konzolon betöltéskor

    voucherForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        console.log("Voucher beküldés elindítva..."); 

        const submitBtn = this.querySelector('button[type="submit"]');
        if (!submitBtn) return;

        const originalBtnText = submitBtn.innerHTML;
        
        // Gomb vizuális visszajelzése
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fa-solid fa-leaf fa-spin me-2"></i> Feldolgozás...';

        try {
            const fd = new FormData(this);
            const data = {
                recipient: fd.get('v_recipient'),
                amount:    fd.get('v_amount'),
                email:     fd.get('v_buyer_email'),
                tel:       fd.get('v_buyer_tel')
            };

            const response = await fetch('api.php?request=vouchers', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });

            if (!response.ok) throw new Error("Szerver hiba: " + response.status);

            const result = await response.json();

            if (result.success) {
                showApiToast("Sikeres vásárlás!", "Kód: " + result.code);
                this.reset();
                // Előnézet visszaállítása (ha vannak ilyen elemeid)
                if(document.getElementById('p-name')) document.getElementById('p-name').innerText = "VENDÉGÜNK NEVE";
                if(document.getElementById('p-amount')) document.getElementById('p-amount').innerText = "10 000 Ft";
            } else {
                showApiToast("Hiba", result.error || "Ismeretlen hiba", true);
            }
        } catch (err) {
            console.error("Voucher API Hiba:", err);
            showApiToast("Hiba", "Hálózati hiba: " + err.message, true);
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnText;
        }
    });
} else {
    console.error("Hiba: Nem található #voucherForm az oldalon!");
}
function toggleTheme() {
    const isDark = document.body.classList.toggle('dark-theme');
    localStorage.setItem('theme', isDark ? 'dark' : 'light');
    console.log("Téma váltva:", isDark ? "Sötét" : "Világos");
}

// Ezt minden fájlba (index.php, user.php, login.php) tedd be a script részbe:
(function() {
    if (localStorage.getItem('theme') === 'dark') {
        document.body.classList.add('dark-theme');
    }
})();
</script>
<?php include '../config/footer.php'; ?>
</body>
</html>