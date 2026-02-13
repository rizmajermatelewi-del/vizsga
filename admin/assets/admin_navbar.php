<nav class="admin-nav navbar navbar-expand-lg">
    <div class="container-fluid px-4">
        <div class="nav-side-section justify-content-start">
        <a href="dashboard.php" class="brand text-decoration-none me-3" style="font-size: 1.5rem; color: var(--j-text);">
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
                <button onclick="toggleDarkMode()" class="nav-icon-btn"><i class="fas fa-moon"></i></button>
                <a href="../public/logout.php" class="nav-icon-btn logout-btn"><i class="fas fa-power-off"></i></a>
            </div>
        </div>

        <div class="nav-side-section justify-content-end d-none d-lg-flex">
            <div class="d-flex align-items-center gap-3">
                <button id="themeToggle" onclick="toggleDarkMode()" class="nav-icon-btn">
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
    .nav-icon-btn {
    background: transparent;
    border: 1px solid var(--j-border);
    color: var(--j-text);
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    cursor: pointer;
    text-decoration: none;
}

.nav-icon-btn:hover {
    background: var(--j-gold);
    color: white !important;
    border-color: var(--j-gold);
    transform: translateY(-2px);
}

.logout-btn:hover {
    background: #ff4757 !important;
    border-color: #ff4757 !important;
}
body.dark-theme .fa-moon::before {
    content: "\f185"; 
}
:root {
    --j-bg: #ffffff;
    --j-card: #fcfaf7;
    --j-text: #1a1a1a;
    --j-muted: #606060;
    --j-border: #e8e2d9;
    --j-gold: #8b6f47;
    --j-accent: #d4af37;
    --j-placeholder: #a0a0a0;
    --j-nav: rgba(255, 255, 255, 0.85);
    --j-invert-val: 0;
    --j-shadow: rgba(0, 0, 0, 0.08);
}
body.dark-theme {
    --j-bg: #0d1012;
    --j-card: #161a1d;
    --j-text: #f0f0f0;
    --j-muted: #b0b0b0;
    --j-border: #2d3238;
    --j-gold: #e8d4a8; 
    --j-accent: #f1c40f;
    --j-placeholder: #666666;
    --j-nav: rgba(13, 16, 18, 0.9);
    --j-invert-val: 1;
    --j-shadow: rgba(0, 0, 0, 0.4);
}
html {
    scroll-behavior: smooth;
}
body {
    background-color: var(--j-bg);
    color: var(--j-text);
    font-family: 'Shippori Mincho', serif;
    transition: background-color 0.4s ease, color 0.4s ease;
    margin: 0;
    padding-top: 70px;
}
.navbar {
    position: fixed;
    top: 0;
    width: 100%;
    z-index: 500;
    background: var(--j-nav) !important;
    backdrop-filter: blur(15px) saturate(180%);
    -webkit-backdrop-filter: blur(15px) saturate(180%);
    border-bottom: 1px solid var(--j-border);
    padding: 0.8rem 2rem;
    transition: all 0.4s ease;
    min-height: 60px;
}
.brand-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0;
    text-decoration: none !important;
}
.brand-ab {
    font-size: 1.6rem;
    font-weight: 700;
    color: black !important;
    letter-spacing: 2px;
    letter-spacing: 4px;
    line-height: 1;
    margin-bottom: 4px;
}
.brand-subtitle {
    font-size: 0.6rem;
    font-weight: 800;
    letter-spacing: 3px;
    text-transform: uppercase;
    color: var(--j-gold);
}
.nav-link {
    font-size: 0.85rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    color: var(--j-text) !important;
    padding: 0.5rem 1.2rem !important;
    opacity: 0.8;
    transition: all 0.3s ease !important;
}
.nav-link:hover {
    opacity: 1;
    color: var(--j-gold) !important;
    transform: translateY(-2px);
}
.nav-link::after {
    content: '';
    position: absolute;
    bottom: 5px;
    left: 50%;
    width: 0;
    height: 1px;
    background: var(--j-gold);
    transition: width 0.3s ease;
    transform: translateX(-50%);
}
.nav-link:hover::after {
    width: 60%;
}
.navbar-icons {
    display: flex;
    align-items: center;
    gap: 1.2rem;
}
.theme-toggle, .logout-link {
    width: 38px;
    height: 38px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: rgba(139, 111, 71, 0.1);
    color: var(--j-text) !important;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    cursor: pointer;
    border: 1px solid transparent;
}
.theme-toggle:hover {
    transform: rotate(180deg);
    border-color: var(--j-gold);
    color: var(--j-gold) !important;
}
.logout-link:hover {
    background: #ff4757;
    color: white !important;
    transform: scale(1.1);
}
.navbar-toggler-icon {
    filter: invert(var(--j-invert-val));
}
section {
    padding: 5px 0;
}
.section-header {
    text-align: center;
    margin-bottom: 60px;
}
.section-header h2 {
    font-size: 2.8rem;
    font-weight: 700;
    color: var(--j-text);
    margin-bottom: 1rem;
}
.info-label {
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 4px;
    color: var(--j-gold);
    font-weight: 800;
    display: block;
    margin-bottom: 1rem;
}
.service-card {
    background: var(--j-card);
    border: 1px solid var(--j-border);
    padding: 3rem 2rem;
    transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
    height: 100%;
    box-shadow: 0 4px 20px var(--j-shadow);
}
.service-card:hover {
    transform: translateY(-10px);
    border-color: var(--j-gold);
    box-shadow: 0 15px 40px var(--j-shadow);
}
.service-card h4 {
    color: var(--j-text);
    font-weight: 700;
    font-size: 1.4rem;
}
.price-tag {
    color: var(--j-gold);
    font-weight: 800;
    font-size: 1.8rem;
    font-family: 'Shippori Mincho', serif;
}
.master-badge {
    background: rgba(139, 111, 71, 0.12);
    color: var(--j-gold);
    padding: 0.5rem 1rem;
    border-radius: 30px;
    font-size: 0.8rem;
    font-weight: 700;
    display: inline-block;
    margin: 5px;
    cursor: pointer;
    border: 1px solid rgba(139, 111, 71, 0.2);
    transition: all 0.3s ease;
}
.master-badge:hover {
    background: var(--j-gold);
    color: var(--j-bg);
    transform: scale(1.05);
}
.fancy-pop-menu {
    display: none;
    position: fixed;
    width: 320px;
    background: var(--j-card);
    backdrop-filter: blur(20px);
    border: 1px solid var(--j-border);
    border-radius: 20px;
    padding: 25px;
    box-shadow: 0 20px 50px rgba(0,0,0,0.3);
    z-index: 2000;
    opacity: 0;
    transform: scale(0.9) translateY(10px);
    transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
}
.fancy-pop-menu.active {
    display: block;
    opacity: 1;
    transform: scale(1) translateY(0);
}
#menuTitle {
    font-size: 1.2rem;
    border-bottom: 2px solid var(--j-gold);
    padding-bottom: 8px;
    margin-bottom: 15px;
    color: var(--j-text);
}
#menuDesc {
    font-size: 0.95rem;
    line-height: 1.6;
    color: var(--j-muted);
}
.hero-zen {
    position: relative;
    height: 90vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background-size: cover;
    background-position: center;
    margin-top: 80px;
}
.hero-zen::before {
    content: "";
    position: absolute;
    inset: 0;
    background: rgba(0, 0, 0, 0.45);
    z-index: 1;
}
.btn-zen-light {
    border: 2px solid #fff;
    color: #fff;
    padding: 15px 40px;
    text-transform: uppercase;
    letter-spacing: 3px;
    font-weight: 800;
    transition: 0.4s ease;
    text-decoration: none;
    position: relative;
    z-index: 2;
}
.btn-zen-light:hover {
    background: #fff;
    color: #000;
}
.form-control {
    background-color: var(--j-card) !important;
    border: 1px solid var(--j-border) !important;
    color: var(--j-text) !important;
    padding: 14px !important;
    border-radius: 0 !important;
}
.form-control:focus {
    border-color: var(--j-gold) !important;
    box-shadow: none !important;
}
@media (max-width: 768px) {
    .navbar { padding: 0.8rem 1rem; }
    .brand-ab { font-size: 1.3rem; }
    .section-header h2 { font-size: 2rem; }
    .fancy-pop-menu { width: 90%; left: 5% !important; }
}
</style>
<script>
(function() {
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'dark') {
        document.documentElement.classList.add('dark-theme'); 
        document.body.classList.add('dark-theme');
    }
})();
function toggleDarkMode() {
    const isDark = document.body.classList.toggle('dark-theme');
    localStorage.setItem('theme', isDark ? 'dark' : 'light');
    updateThemeIcons(isDark);
}
function toggleMobileMenu() {
    const menu = document.getElementById('mobileMenu');
    const toggler = document.querySelector('.custom-toggler');
    
    if (!menu) return; 

    const isOpen = menu.classList.toggle('open');
    if (toggler) toggler.classList.toggle('active');

    if (isOpen) {
        document.body.style.height = '100vh';
        document.body.style.overflow = 'hidden';
    } else {
        document.body.style.height = '';
        document.body.style.overflow = '';
    }
}
document.addEventListener('DOMContentLoaded', () => {
    const mobileLinks = document.querySelectorAll('#mobileMenu a');
    mobileLinks.forEach(link => {
        link.addEventListener('click', () => {
            const menu = document.getElementById('mobileMenu');
            if (menu && menu.classList.contains('open')) {
                toggleMobileMenu();
            }
        });
    });
});
</script>