<nav class="admin-nav navbar navbar-expand-lg">
    <div class="container-fluid px-4">
        <div class="nav-side-section justify-content-start">
        <a href="dashboard.php" class="brand text-decoration-none me-3" style="font-size: 1.5rem; color: var(--j-dark);">
            <span style="font-weight: 300;">AB</span><span style="font-weight: 500;">MASSZÁZS</span>
        </a>
        <a href="../public/index.php" class="nav-icon-btn" title="Vissza a weboldalra">
                <i class="fas fa-home"></i>
            </a>
        </div>

        <button class="custom-toggler d-lg-none" type="button" onclick="toggleMobileMenu()">
            <span class="bar"></span>
            <span class="bar"></span>
        </button>

        <div class="mobile-overlay" id="mobileMenu">
            <div class="admin-menu-links">
                <a class="nav-link <?= strpos($_SERVER['PHP_SELF'], 'dashboard.php') !== false ? 'active' : '' ?>" href="dashboard.php">Dashboard</a>
                <a class="nav-link <?= strpos($_SERVER['PHP_SELF'], 'calendar.php') !== false ? 'active' : '' ?>" href="calendar.php">Naptár</a>
                <a class="nav-link <?= strpos($_SERVER['PHP_SELF'], 'services.php') !== false ? 'active' : '' ?>" href="services.php">Szolgáltatások</a>
                <a class="nav-link <?= strpos($_SERVER['PHP_SELF'], 'vouchers.php') !== false ? 'active' : '' ?>" href="vouchers.php">Utalványok</a>
            </div>
            
            <div class="d-lg-none d-flex gap-4 mt-5 justify-content-center">
                <button onclick="toggleTheme()" class="nav-icon-btn"><i class="fas fa-moon"></i></button>
                <a href="../public/logout.php" class="nav-icon-btn logout-btn"><i class="fas fa-power-off"></i></a>
            </div>
        </div>

        <div class="nav-side-section justify-content-end d-none d-lg-flex">
            <div class="d-flex align-items-center gap-3">
                <button id="themeToggle" onclick="toggleTheme()" class="nav-icon-btn">
                    <i class="fas fa-moon"></i>
                </button>
                <a href="../public/logout.php" class="nav-icon-btn logout-btn">
                    <i class="fas fa-power-off"></i>
                </a>
            </div>
        </div>
    </div>
</nav>
<div class="header-spacer"></div>

<style>
    /* ALAP ELRENDEZÉS */
    .admin-nav {
        background: var(--nav-bg);
        border-bottom: 1px solid var(--j-border);
        height: 80px;
        display: flex; 
        align-items: center;
    }
    .nav-side-section { display: flex; align-items: center; }

    /* EGYEDI HAMBURGER */
    .custom-toggler {
        background: none; border: none; padding: 10px; z-index: 1200;
    }
    .bar {
        display: block; width: 22px; height: 1px; 
        background: var(--j-dark); margin: 6px 0;
        transition: 0.3s;
    }
    .custom-toggler.active .bar:nth-child(1) { transform: translateY(7px) rotate(45deg); }
    .custom-toggler.active .bar:nth-child(2) { transform: translateY(-7px) rotate(-45deg); }

    /* MOBIL OVERLAY (Lágy lenyílás) */
   @media (max-width: 991.98px) {
    .mobile-overlay {
        position: fixed;        /* ✅ MOBILON FIXED */
        top: 0;
        left: 0;
        width: 100%;
        height: 0;
        background: var(--j-white);
        overflow: hidden;
        transition: height 0.5s cubic-bezier(0.77, 0, 0.175, 1);
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        z-index: 1050;
    }

    .mobile-overlay.open { height: 100vh; }

    .admin-menu-links {
        display: flex;
        align-items: center;
        gap: 30px;
    }

    .nav-link {
        font-size: 1.2rem !important;
        opacity: 0;
        transition: 0.3s;
    }

    .mobile-overlay.open .nav-link { opacity: 1; }
}


    /* DESKTOP ELRENDEZÉS */
    @media (min-width: 992px) {
    .mobile-overlay {
        display: flex !important;
        flex-direction: row;
        position: static;   /* ✅ DESKTOPON STATIKUS */
        height: auto !important;
        inset: unset;
        z-index: auto;
    }

    .admin-menu-links {
        display: flex;
        gap: 30px;
    }
}


    /* HOVER & JAPANDI STÍLUS */
    /* ÚJ, FINOMABB HOVER STÍLUS */
/* DINAMIKUS KAPSZULA ÉS MOZGÓ VONAL */
.nav-link {
    text-decoration: none;
    color: var(--j-dark) !important;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1.2px;
    font-size: 0.7rem;
    padding: 10px 22px !important;
    border-radius: 50px;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0.7;
    background-color: transparent;
}

/* Hover: Háttér beúszik, szöveg kiemelkedik */
.nav-link:hover {
    opacity: 1;
    color: var(--j-dark) !important;
    background-color: var(--j-soft);
    transform: translateY(-2px);
}

/* A MOZGÓ VONAL/PONT LOGIKA */
.nav-link::after {
    content: '';
    position: absolute;
    bottom: 6px;
    left: 50%;
    width: 15px;              /* ⬅️ FIX SZÉLESSÉG */
    height: 2px;
    background-color: var(--j-accent);
    border-radius: 2px;
    transform: translateX(-50%) scaleX(0);
    transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    transform-origin: center;
}



/* Hoverkor és aktív állapotban a vonal kinyúlik */
.nav-link:hover::after,
.nav-link.active::after {
    transform: translateX(-50%) scaleX(1);
}

/* Aktív állapot fix háttérrel */
.nav-link.active {
    opacity: 1;
    background-color: var(--j-soft);
}
/* JAPANDI HÁZIKÓ ÉS IKON GOMBOK */
.nav-icon-btn {
    width: 42px;
    height: 42px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid var(--j-border); /* Halvány szürkés-bézs szegély */
    border-radius: 12px;
    color: var(--j-dark) !important; /* Mély sötétszürke, SOHA nem kék */
    background-color: transparent;
    text-decoration: none;
    transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
    cursor: pointer;
}

/* Hover állapot a házikónál */
.nav-icon-btn:hover {
    border-color: var(--j-accent); /* Beúszik a meleg accent szín */
    color: var(--j-accent) !important;
    background-color: var(--j-soft); /* Nagyon halvány bézs háttér */
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

/* Külön stílus a kijelentkezésnek, ha azt akarod, hogy picit látványosabb legyen */
.logout-btn:hover {
    border-color: #d68c8c; /* Tompa, Japandi-kompatibilis terrakotta/vörös */
    color: #d68c8c !important;
    background-color: #fff5f5;
}
</style>

<script>
function toggleMobileMenu() {
    const menu = document.getElementById('mobileMenu');
    const toggler = document.querySelector('.custom-toggler');
    menu.classList.toggle('open');
    toggler.classList.toggle('active');
    // Akadályozzuk meg a görgetést, ha nyitva a menü
    document.body.style.overflow = menu.classList.contains('open') ? 'hidden' : 'auto';
}

function toggleTheme() {
    document.body.classList.toggle('dark-mode');
    const isDark = document.body.classList.contains('dark-mode');
    localStorage.setItem('admin-theme', isDark ? 'dark' : 'light');
    document.querySelectorAll('.nav-icon-btn i.fa-moon, .nav-icon-btn i.fa-sun').forEach(icon => {
        icon.className = isDark ? 'fas fa-sun' : 'fas fa-moon';
    });
}
</script>