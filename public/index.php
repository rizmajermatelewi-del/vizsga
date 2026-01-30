<?php
session_start();
require_once "../config/database.php";

// Szolgáltatások lekérése az adatbázisból
$stmt = $pdo->query("SELECT * FROM services ORDER BY name ASC");
$services = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ZEN SPA | Harmonious Living</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@200;400;600&family=Shippori+Mincho:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root { 
            --j-bg: #fdfcfb; 
            --j-dark: #2d2a26; 
            --j-accent: #8e7d6a; 
            --j-border: #e2ddd9; 
            --j-soft: #f4f1ee;
        }

        body { 
            background-color: var(--j-bg); 
            color: var(--j-dark); 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            scroll-behavior: smooth; 
            letter-spacing: 0.02em;
        }

        h1, h2, h3, .brand { 
            font-family: 'Shippori Mincho', serif; 
            letter-spacing: 0.1em;
        }

        .navbar { 
            padding: 1.5rem 0;
            background: rgba(253, 252, 251, 0.95); 
            backdrop-filter: blur(15px);
            border-bottom: 1px solid var(--j-border);
        }
        
        .nav-link { 
            text-transform: uppercase; 
            font-size: 0.7rem; 
            letter-spacing: 2px; 
            font-weight: 600; 
            color: var(--j-dark) !important;
        }

        .j-card { 
            background: #fff;
            border: 1px solid var(--j-border); 
            padding: 3rem 2rem;
            transition: 0.4s;
            height: 100%;
        }

        .btn-zen { 
            background: var(--j-dark); 
            color: white; 
            border-radius: 0; 
            padding: 15px 35px; 
            text-transform: uppercase; 
            letter-spacing: 3px; 
            font-size: 0.75rem;
            border: none; 
            transition: 0.4s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-zen:hover { background: var(--j-accent); color: white; }

        .form-control, .form-select { 
            border-radius: 0; 
            border: none; 
            border-bottom: 1px solid var(--j-border); 
            background: transparent; 
            padding: 1rem 0;
        }

        /* Toast stílus */
        .toast-japandi {
            border-radius: 0;
            border: none;
            border-left: 4px solid var(--j-accent);
            background: white;
        }
        /* Sötét téma színei */
body.dark-theme {
    --j-bg: #1a1a1a;        /* Mély szürke háttér */
    --j-dark: #fdfcfb;      /* Világos szöveg */
    --j-accent: #b5a491;    /* Világosabb bézs az akcentusnak */
    --j-border: #333333;    /* Sötétebb keretek */
    --j-soft: #242424;      /* Sötétebb szekció háttér */
    color: var(--j-dark);
}

body.dark-theme .j-card, 
body.dark-theme .navbar,
body.dark-theme #voucher-preview,
body.dark-theme .toast-japandi {
    background-color: #242424 !important;
    color: var(--j-dark);
}

body.dark-theme .form-control, 
body.dark-theme .form-select {
    color: white;
    border-bottom: 1px solid #444;
}

/* Finom átmenet a váltáskor */
body {
    transition: background-color 0.5s ease, color 0.5s ease;
}
    </style>
</head>
<body>

<?php if (isset($_SESSION['message'])): ?>
    <div class="toast-container position-fixed bottom-0 end-0 p-4" style="z-index: 1100;">
        <div id="zenToast" class="toast show toast-japandi shadow-lg" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-body p-4 d-flex align-items-center">
                <i class="fas fa-leaf me-3 text-success opacity-75"></i>
                <div>
                    <strong class="d-block small text-uppercase mb-1" style="letter-spacing: 1px; color: var(--j-accent);">Értesítés</strong>
                    <span class="text-dark small"><?= $_SESSION['message'] ?></span>
                </div>
                <button type="button" class="btn-close ms-auto shadow-none" data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>
    <script>
        setTimeout(() => {
            var toastEl = document.getElementById('zenToast');
            if(toastEl) {
                var bsToast = new bootstrap.Toast(toastEl);
                bsToast.hide();
            }
        }, 5000);
    </script>
    <?php unset($_SESSION['message']); unset($_SESSION['msg_type']); ?>
<?php endif; ?>

<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
        <a class="navbar-brand brand fw-bold" href="index.php">ZEN SPA</a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item"><a class="nav-link" href="#massages">Kezelések</a></li>
                <li class="nav-item"><a class="nav-link" href="#vouchers">Voucher</a></li>
                <li class="nav-item"><a class="nav-link" href="#booking">Időpontok</a></li>
            </ul>
        </div>
        <button onclick="toggleTheme()" class="btn px-3" id="themeToggle">
    <i class="fas fa-moon"></i>
</button>
        <div class="d-flex align-items-center">
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="user.php" class="btn btn-outline-dark btn-sm rounded-0 px-3 ms-2">PROFILOM</a>
            <?php else: ?>
                <a href="login.php" class="btn btn-dark btn-sm rounded-0 px-3 ms-2">BELÉPÉS</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<section class="py-5 text-center">
    <div class="container py-5">
        <h5 class="text-uppercase mb-4" style="letter-spacing: 8px; font-size: 0.8rem; color: var(--j-accent);">Lassuljon le</h5>
        <h1 class="display-3 mb-5">A béke itt kezdődik.</h1>
        <div style="width: 1px; height: 60px; background: var(--j-accent); margin: 0 auto;"></div>
    </div>
</section>

<section id="massages" class="py-5 bg-white">
    <div class="container">
        <div class="row g-5">
            <?php foreach($services as $s): ?>
            <div class="col-md-4">
                <div class="j-card text-center">
                    <h4 class="mb-3"><?= htmlspecialchars($s['name']) ?></h4>
                    <p class="small text-muted mb-4"><?= number_format($s['price'], 0, ',', ' ') ?> Ft</p>
                    <a href="#booking" class="text-dark small text-uppercase fw-bold" style="letter-spacing: 2px; text-decoration: none;">Kiválasztom →</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section id="vouchers" class="py-5" style="background: var(--j-soft);">
    <div class="container">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6">
                <div id="voucher-preview" class="p-5 bg-white border shadow-sm text-center">
                    <h3 class="brand mb-4">ZEN SPA</h3>
                    <h2 id="p-amount" class="my-5">10 000 Ft</h2>
                    <p id="p-name" class="text-uppercase small fw-bold" style="letter-spacing: 4px;">AZ ÖN NEVE</p>
                </div>
            </div>
            <div class="col-lg-5 offset-lg-1">
                <h2 class="section-title">Ajándékozzon nyugalmat.</h2>
                <form action="process_voucher.php" method="POST" class="mt-4">
                    <input type="text" name="v_name" id="v_name" class="form-control mb-4" placeholder="KINEK A RÉSZÉRE?" required>
                    <input type="email" name="v_email" class="form-control mb-4" placeholder="ÉRTESÍTÉSI EMAIL" required>
                    <select name="v_amount" id="v_amount" class="form-select mb-5">
                        <option value="10000">10 000 Ft</option>
                        <option value="25000">25 000 Ft</option>
                        <option value="50000">50 000 Ft</option>
                    </select>
                    <button type="submit" class="btn-zen w-100">Utalvány rendelése</button>
                </form>
            </div>
        </div>
    </div>
</section>

<section id="booking" class="py-5 bg-white">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <h2 class="section-title">Időpontfoglalás</h2>
                <form action="process_booking.php" method="POST" class="row g-4 mt-4 text-start">
                    <div class="col-md-6">
                        <input type="text" name="customer_name" class="form-control" placeholder="NÉV" required>
                    </div>
                    <div class="col-md-6">
                        <input type="text" name="phone" class="form-control" placeholder="TELEFON" required>
                    </div>
                    <div class="col-12">
                        <select name="service_id" class="form-select" required>
                            <option value="" disabled selected>Válasszon kezelést...</option>
                            <?php foreach($services as $s): ?>
                                <option value="<?= $s['id'] ?>"><?= $s['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="small text-muted text-uppercase mb-1">Dátum</label>
                        <input type="date" name="booking_date" class="form-control" required min="<?= date('Y-m-d') ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="small text-muted text-uppercase mb-1">Időpont</label>
                        <input type="time" name="booking_time" class="form-control" required>
                    </div>
                    <div class="col-12 mt-5 text-center">
                        <button type="submit" class="btn-zen px-5">Foglalás megerősítése</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<footer class="py-5 text-center border-top">
    <p class="brand small opacity-50">ZEN SPA Budapest &copy; 2026</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Voucher előnézet frissítése
    const nameIn = document.getElementById('v_name');
    const amtSel = document.getElementById('v_amount');
    nameIn.addEventListener('input', () => { document.getElementById('p-name').innerText = nameIn.value || "AZ ÖN NEVE"; });
    amtSel.addEventListener('change', () => { document.getElementById('p-amount').innerText = new Intl.NumberFormat('hu-HU').format(amtSel.value) + " Ft"; });
    function toggleTheme() {
    const body = document.body;
    const icon = document.querySelector('#themeToggle i');
    
    // Osztály hozzáadása/eltávolítása
    body.classList.toggle('dark-theme');
    
    // Ikon váltása és állapot mentése
    if (body.classList.contains('dark-theme')) {
        icon.classList.replace('fa-moon', 'fa-sun');
        localStorage.setItem('theme', 'dark');
    } else {
        icon.classList.replace('fa-sun', 'fa-moon');
        localStorage.setItem('theme', 'light');
    }
}
</script>
</body>
</html>