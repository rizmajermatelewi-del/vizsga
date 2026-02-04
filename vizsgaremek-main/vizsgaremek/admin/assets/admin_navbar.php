<nav class="admin-nav">
    <div class="d-flex align-items-center justify-content-between w-100">
        <div class="d-flex align-items-center">
            <a href="dashboard.php" class="brand text-decoration-none me-5" style="font-size: 1.5rem; color: var(--j-dark);">
               AB MASSZÁZS<span style="color: var(--j-accent); font-weight: 300;"></span>
            </a>
            <div class="d-none d-lg-flex">
                <a class="nav-item-link <?= strpos($_SERVER['PHP_SELF'], 'dashboard.php') !== false ? 'active' : '' ?>" href="dashboard.php">Dashboard</a>
                <a class="nav-item-link <?= strpos($_SERVER['PHP_SELF'], 'calendar.php') !== false ? 'active' : '' ?>" href="calendar.php">Naptár</a>
                <a class="nav-item-link <?= strpos($_SERVER['PHP_SELF'], 'services.php') !== false ? 'active' : '' ?>" href="services.php">Szolgáltatások</a>
                <a class="nav-item-link <?= strpos($_SERVER['PHP_SELF'], 'vouchers.php') !== false ? 'active' : '' ?>" href="vouchers.php">Utalványok</a>
            </div>
        </div>
        <div class="d-flex align-items-center gap-3">
            <button id="themeToggle" onclick="toggleTheme()" class="btn-control" style="background:none; border:1px solid var(--j-border); width:40px; height:40px; border-radius:10px; cursor:pointer; color:var(--j-dark);">
                <i class="fas fa-moon"></i>
            </button>
            <a href="../public/logout.php" style="color: #e53e3e; margin-left:10px;"><i class="fas fa-power-off"></i></a>
        </div>
    </div>
</nav>
<div class="header-spacer"></div>

<script>
function toggleTheme() {
    document.body.classList.toggle('dark-mode');
    const isDark = document.body.classList.contains('dark-mode');
    localStorage.setItem('admin-theme', isDark ? 'dark' : 'light');
    document.querySelector('#themeToggle i').className = isDark ? 'fas fa-sun' : 'fas fa-moon';
}
(function() {
    if (localStorage.getItem('admin-theme') === 'dark') {
        document.body.classList.add('dark-mode');
        document.querySelector('#themeToggle i').className = 'fas fa-sun';
    }
})();
</script>