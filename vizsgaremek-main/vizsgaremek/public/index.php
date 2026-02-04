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
        <span class="info-label text-white opacity-75">ÜDVÖZÖLJÜK A CSENDBEN</span>
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
                <div id="voucher-preview" class="p-5 bg-white border text-center shadow-sm">
                    <h3 class="brand mb-4">AB MASSZÁZS</h3>
                    <h2 id="p-amount" class="my-5">10 000 Ft</h2>
                    <p id="p-name" class="text-uppercase small fw-bold opacity-50" style="letter-spacing: 4px;">VENDÉGÜNK NEVE</p>
                </div>
            </div>
            <div class="col-lg-5 offset-lg-1">
                <span class="info-label">AJÁNDÉK</span>
                <h2 class="brand mb-4">Adjon élményt szeretteinek</h2>
                
                <?php if(isset($_SESSION['user_id'])): ?>
                    <form action="vouchers.php" method="POST">
                        <div class="row g-3">
                            <div class="col-12 mb-2">
                                <label class="small text-muted mb-1">Vásárló (Automatikusan kitöltve):</label>
                                <input type="text" name="v_buyer_name" class="form-control bg-light" 
                                       value="<?= htmlspecialchars($_SESSION['username'] ?? 'Bejelentkezett Felhasználó') ?>" readonly>
                            </div>
                            
                            <div class="col-12">
                                <input type="text" name="v_recipient" id="v_name" class="form-control" placeholder="KINEK A RÉSZÉRE? (A megajándékozott neve)" required>
                            </div>
                            
                            <div class="col-md-6">
                                <input type="email" name="v_buyer_email" class="form-control" placeholder="AZ ÖN EMAIL CÍME" required>
                            </div>
                            <div class="col-md-6">
                                <input type="tel" name="v_buyer_phone" class="form-control" placeholder="TELEFONSZÁM" required>
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
                <form action="booking.php" method="POST" class="row g-4">
                    <div class="col-md-6"><input type="text" name="customer_name" class="form-control" placeholder="NÉV" required></div>
                    <div class="col-md-6"><input type="text" name="phone" class="form-control" placeholder="TELEFON" required></div>
                    <div class="col-12">
                        <select name="service_id" class="form-select" required>
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
                        <button type="submit" name="submit_booking" class="btn-zen px-5">Foglalás megerősítése</button>
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
<script>
// PHP-ból érkező jelzés alapján megnyitjuk a modalt
<?php if(isset($_GET['status']) && $_GET['status'] == 'success'): ?>
    document.addEventListener('DOMContentLoaded', function() {
        var myModal = new bootstrap.Modal(document.getElementById('successModal'));
        myModal.show();
    });
<?php endif; ?>
</script>

<section id="contact-info" class="py-5">
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
        
        <?php if (isset($_GET['status']) && $_GET['status'] === 'message_sent'): ?>
            <div class="message-toast mt-4">
                <i class="fa-solid fa-check-circle me-2"></i> Üzenetét rögzítettük. Hamarosan válaszolunk.
            </div>
        <?php endif; ?>
    </div>
</section>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="zen-form-card shadow-sm">
                    <ul class="nav nav-tabs border-0 justify-content-center mb-5" id="zenTab" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-contact">Kapcsolat</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-review">Vélemény</button>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="tab-contact">
                            <form action="contact.php" method="POST" class="row g-4">
                                <div class="col-md-6">
                                    <input type="text" name="c_name" class="zen-input" placeholder="Az Ön neve" required>
                                </div>
                                <div class="col-md-6">
                                    <input type="email" name="c_email" class="zen-input" placeholder="Email cím" required>
                                </div>
                                <div class="col-12">
                                    <input type="tel" name="c_phone" class="zen-input" placeholder="Telefonszám" required>
                                </div>
                                <div class="col-12">
                                    <textarea name="c_message" rows="4" class="zen-input" placeholder="Miben segíthetünk?" required></textarea>
                                </div>
                                <div class="col-12 text-center mt-4">
                                    <button type="submit" class="btn-zen-gold">ÜZENET KÜLDÉSE</button>
                                </div>
                            </form>
                        </div>

                        <div class="tab-pane fade" id="tab-review">
                            <form action="reviews.php" method="POST" class="row g-4 text-center">
                                <div class="col-md-6">
                                    <input type="text" name="r_name" class="zen-input" placeholder="Név" required>
                                </div>
                                <div class="col-md-6">
                                    <select name="r_service" class="zen-input" required style="appearance: none;">
                                        <option value="" disabled selected>Válasszon kezelést...</option>
                                        <?php foreach($services as $s): ?>
                                            <option value="<?= htmlspecialchars($s['name']) ?>"><?= htmlspecialchars($s['name']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <p class="small text-muted mb-2">Értékelés</p>
                                    <div class="star-rating">
                                        <?php for($i=5; $i>=1; $i--): ?>
                                            <input type="radio" id="fancy-s-<?= $i ?>" name="rating" value="<?= $i ?>" <?= $i==5?'checked':'' ?>>
                                            <label for="fancy-s-<?= $i ?>"><i class="fa-solid fa-star"></i></label>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <textarea name="r_message" rows="4" class="zen-input" placeholder="Írja le az élményét..." required></textarea>
                                </div>
                                <div class="col-12 text-center mt-4">
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
    .zen-form-card {
        background: #ffffff;
        padding: 60px;
        border: 1px solid rgba(184, 146, 74, 0.1);
    }

    /* Tab navigáció */
    #zenTab .nav-link {
        background: none;
        border: none;
        color: #999;
        text-transform: uppercase;
        letter-spacing: 3px;
        font-size: 0.8rem;
        padding: 10px 30px;
        position: relative;
        transition: 0.4s;
    }

    #zenTab .nav-link.active {
        color: #1a1a1a;
        font-weight: 600;
    }

    #zenTab .nav-link.active::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 30%;
        width: 40%;
        height: 1px;
        background: #b8924a;
    }

    /* Inputok - Japandi minimalizmus */
    .zen-input {
        width: 100%;
        background: transparent;
        border: none;
        border-bottom: 1px solid rgba(0,0,0,0.08);
        padding: 12px 0;
        font-size: 0.95rem;
        transition: 0.3s;
        border-radius: 0;
        outline: none;
    }

    .zen-input:focus {
        border-bottom: 1px solid #b8924a;
    }

    /* Arany gomb */
    .btn-zen-gold {
        background: #1a1a1a;
        color: #fff;
        border: none;
        padding: 15px 45px;
        letter-spacing: 2px;
        font-size: 0.75rem;
        transition: 0.4s ease;
        cursor: pointer;
    }

    .btn-zen-gold:hover {
        background: #b8924a;
        transform: translateY(-2px);
    }

    /* Csillagok */
    .star-rating {
        display: flex;
        flex-direction: row-reverse;
        justify-content: center;
        gap: 15px;
    }
    .star-rating input { display: none; }
    .star-rating label {
        color: #e0e0e0;
        font-size: 1.5rem;
        cursor: pointer;
        transition: 0.3s;
    }
    .star-rating label:hover,
    .star-rating label:hover ~ label,
    .star-rating input:checked ~ label {
        color: #b8924a;
    }
</style>
    </div>
</section>

<div class="modal fade" id="voucherModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius:0; background: #fcfaf7;">
            <div class="modal-body p-5 text-center">
                <div class="mb-4">
                    <i class="fas fa-gift fa-3x text-warning opacity-50"></i>
                </div>
                <h3 class="brand">Az ajándék úton van!</h3>
                <p class="text-muted small my-4">Sikeresen megvásárolta az ajándékkártyát <strong><?= htmlspecialchars($_GET['name'] ?? 'Szerette') ?></strong> részére. A visszaigazolást hamarosan küldjük.</p>
                <button type="button" class="btn-zen w-100" data-bs-dismiss="modal">Rendben</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/hu.js"></script>

<script>
    // Voucher siker visszajelzés kezelése
<?php if(isset($_GET['v_status']) && $_GET['v_status'] == 'success'): ?>
    document.addEventListener('DOMContentLoaded', function() {
        var vModal = new bootstrap.Modal(document.getElementById('voucherModal'));
        vModal.show();
    });
<?php endif; ?>
document.addEventListener('DOMContentLoaded', () => {
    // SCROLL REVEAL (Görgetési animáció) - MEGLÉVŐ
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) entry.target.classList.add('visible');
        });
    }, { threshold: 0.1 });
    document.querySelectorAll('.reveal').forEach(r => observer.observe(r));

    // VOUCHER DINAMIKUS ELŐNÉZET - MEGLÉVŐ
    const vNameInput = document.getElementById('v_name');
    const vAmountSelect = document.getElementById('v_amount');
    if(vNameInput) {
        vNameInput.addEventListener('input', (e) => {
            const pName = document.getElementById('p-name');
            if(pName) pName.innerText = e.target.value.toUpperCase() || "VENDÉGÜNK NEVE";
        });
    }
    if(vAmountSelect) {
        vAmountSelect.addEventListener('change', (e) => {
            const pAmount = document.getElementById('p-amount');
            if(pAmount) pAmount.innerText = new Intl.NumberFormat('hu-HU').format(e.target.value) + " Ft";
        });
    }
});

function toggleTheme() {
    const isDark = document.body.classList.toggle('dark-theme');
    document.cookie = `theme=${isDark ? 'dark' : 'light'};path=/;max-age=31536000`;
}

window.addEventListener('load', () => {
    const preloader = document.getElementById('zen-preloader');
    if (preloader) {
        setTimeout(() => {
            preloader.style.opacity = '0';
            setTimeout(() => preloader.style.display = 'none', 800);
        }, 600);
    }
});

<?php if(isset($_SESSION['message'])): ?>
    const modalEl = document.getElementById('statusModal');
    if (modalEl) {
        const statusModal = new bootstrap.Modal(modalEl);
        statusModal.show();
    }
    <?php unset($_SESSION['message'], $_SESSION['msg_type']); ?>
<?php endif; ?>
document.addEventListener('DOMContentLoaded', () => {
    // Naptár inicializálása (Flatpickr)
    flatpickr("#booking_date", {
        locale: "hu",
        minDate: "today",
        dateFormat: "Y-m-d",
        disableMobile: "true",
        onChange: function(selectedDates, dateStr) {
            const timeArea = document.getElementById('time-selection-area');
            const feedback = document.getElementById('date-feedback');
            
            // Megjelenítjük az idősávokat animációval
            timeArea.style.display = 'block';
            timeArea.classList.add('reveal', 'visible');
            
            // Személyes visszajelzés
            feedback.innerText = "Nagyszerű! " + dateStr + " napra az alábbi szabad időpontjaink vannak:";

            // Itt hívjuk meg az ellenőrzést, hogy melyik időpont foglalt
            fetch(`check_availability.php?date=${dateStr}`)
                .then(res => res.json())
                .then(taken => {
                    document.querySelectorAll('.btn-check').forEach(s => {
                        s.disabled = false; // Alapból mindent engedélyezünk
                        // Ha a válaszban benne van az időpont, letiltjuk a gombot
                        if(taken.includes(s.value) || taken.includes(s.value + ':00')) {
                            s.disabled = true;
                        }
                    });
                })
                .catch(err => console.log("Hiba az időpontok lekérésekor: ", err));
        }
    });
});

</script>


<?php include '../config/footer.php'; ?>
</body>
</html>