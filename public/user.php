<?php
require_once "../config/database.php";
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ./login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Adatok lekérése
$userStmt = $pdo->prepare("SELECT username, email, role, created_at FROM users WHERE id = ?");
$userStmt->execute([$user_id]);
$user = $userStmt->fetch();

$stmt = $pdo->prepare("SELECT b.*, s.name as s_name FROM bookings b JOIN services s ON b.service_id = s.id WHERE b.user_id = ? ORDER BY b.booking_date DESC");
$stmt->execute([$user_id]);
$bookings = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ZEN SPA | Profilom</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600&family=Shippori+Mincho:wght@500;700&display=swap" rel="stylesheet">
    <style>
        :root { --j-bg: #fdfcfb; --j-dark: #2d2a26; --j-accent: #8e7d6a; --j-border: #e2ddd9; }
        body { background: var(--j-bg); font-family: 'Plus Jakarta Sans', sans-serif; padding-top: 100px; color: var(--j-dark); }
        
        /* Navigáció - megegyezik az indexszel */
        .navbar { background: rgba(253, 252, 251, 0.95); backdrop-filter: blur(15px); border-bottom: 1px solid var(--j-border); padding: 1.5rem 0; }
        /* Navbar linkek elegáns hoverrel */
.nav-link {
    position: relative;
    transition: all 0.4s ease !important;
}

.nav-link::after {
    content: '';
    position: absolute;
    width: 0;
    height: 1px;
    bottom: 0;
    left: 50%;
    background-color: var(--j-accent);
    transition: all 0.4s ease;
    transform: translateX(-50%);
}

.nav-link:hover {
    color: var(--j-accent) !important;
    letter-spacing: 3px !important; /* Finoman kitágul a szöveg */
}

.nav-link:hover::after {
    width: 70%; /* Megjelenik az aláhúzás */
}

/* Üveg-hatás a navbarnak görgetéskor */
.navbar.scrolled {
    padding: 0.8rem 0;
    background: rgba(253, 252, 251, 0.85) !important;
    backdrop-filter: blur(20px);
}

/* Kártyák emelkedése */
.j-card {
    border: 1px solid var(--j-border);
    transition: all 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94);
}

.j-card:hover {
    transform: translateY(-10px);
    border-color: var(--j-accent);
    box-shadow: 0 20px 40px rgba(142, 125, 106, 0.05);
}

/* Gomb pulzálása */
.btn-zen {
    position: relative;
    overflow: hidden;
}

.btn-zen::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    background: rgba(255,255,255,0.1);
    border-radius: 50%;
    transform: translate(-50%, -50%);
    transition: width 0.6s ease, height 0.6s ease;
}

.btn-zen:hover::before {
    width: 300px;
    height: 300px;
}
        .user-card { background: white; border: 1px solid var(--j-border); padding: 2.5rem; border-radius: 0; }
        .section-title { font-family: 'Shippori Mincho', serif; border-bottom: 1px solid var(--j-border); padding-bottom: 1rem; margin-bottom: 1.5rem; }
        .info-label { font-size: 0.7rem; text-transform: uppercase; color: var(--j-accent); font-weight: 600; letter-spacing: 1px; }
        .status-pill { font-size: 0.65rem; padding: 5px 12px; text-transform: uppercase; border-radius: 20px; }
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

<nav class="navbar navbar-expand-lg fixed-top shadow-sm">
    <div class="container">
        <a class="navbar-brand brand fw-bold" href="index.php">ZEN SPA</a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item"><a class="nav-link" href="index.php#massages">Kezelések</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php#vouchers">Voucher</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php#booking">Időpontok</a></li>
            </ul>
        </div>
        <button onclick="toggleTheme()" class="btn px-3" id="themeToggle">
    <i class="fas fa-moon"></i>
</button>
        <div class="d-flex align-items-center">
            <a href="logout.php" class="btn btn-outline-danger btn-sm rounded-0 px-3 ms-2">KIJELENTKEZÉS</a>
        </div>
    </div>
</nav>

<div class="container mt-5 pt-4">
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="user-card shadow-sm">
                <div class="text-center mb-4">
                    <i class="fas fa-circle-user fa-5x opacity-25 mb-3" style="color: var(--j-accent);"></i>
                    <h3 class="h4 brand"><?= htmlspecialchars($user['username']) ?></h3>
                    <span class="badge bg-light text-muted border fw-normal"><?= strtoupper($user['role']) ?></span>
                </div>
                <div class="mb-3 mt-4">
                    <div class="info-label">Email cím</div>
                    <div class="text-secondary"><?= htmlspecialchars($user['email']) ?></div>
                </div>
                <div class="mb-3">
                    <div class="info-label">Tagság óta</div>
                    <div class="text-secondary"><?= date("Y. m. d.", strtotime($user['created_at'])) ?></div>
                </div>
                <button class="btn btn-dark w-100 rounded-0 py-2 mt-4" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                    <i class="fas fa-edit me-2 small"></i>Profil szerkesztése
                </button>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="user-card shadow-sm">
                <h4 class="section-title">Foglalásaim</h4>
                <?php if(empty($bookings)): ?>
                    <p class="text-muted py-4">Még nincs rögzített foglalása.</p>
                <?php else: ?>
                    <?php foreach($bookings as $b): ?>
                    <div class="d-flex justify-content-between align-items-center border-bottom py-3">
                        <div>
                            <div class="fw-bold"><?= htmlspecialchars($b['s_name']) ?></div>
                            <div class="small text-muted"><?= $b['booking_date'] ?> • <?= substr($b['booking_time'], 0, 5) ?></div>
                        </div>
                        <span class="status-pill bg-<?= $b['status'] == 'approved' ? 'success text-white' : 'warning' ?>">
                            <?= $b['status'] ?>
                        </span>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editProfileModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-0 border-0 shadow-lg">
            <form action="update_profile.php" method="POST" class="p-4">
                <h5 class="section-title">Profil módosítása</h5>
                <div class="mb-3">
                    <label class="info-label">Felhasználónév</label>
                    <input type="text" name="username" class="form-control rounded-0" value="<?= htmlspecialchars($user['username']) ?>" required>
                </div>
                <div class="mb-3">
                    <label class="info-label">Email cím</label>
                    <input type="email" name="email" class="form-control rounded-0" value="<?= htmlspecialchars($user['email']) ?>" required>
                </div>
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <button type="button" class="btn btn-link text-muted text-decoration-none" data-bs-dismiss="modal">Mégse</button>
                    <button type="submit" class="btn btn-dark rounded-0 px-4">Mentés</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js">
</script>
<script>
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