<nav class="navbar navbar-expand-lg fixed-top shadow-sm" id="mainNav" style="background: rgba(255,255,255,0.95); backdrop-filter: blur(10px); padding: 1rem 0;">
    <div class="container">
        <a class="navbar-brand" href="index.php" style="font-family: 'Shippori Mincho', serif; letter-spacing: 4px; font-weight: 700; color: #2d2a26;">ZEN SPA</a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-content="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item"><a class="nav-link px-3" href="index.php">Főoldal</a></li>
                <li class="nav-item"><a class="nav-link px-3" href="index.php#services">Szolgáltatások</a></li>
                <li class="nav-item"><a class="nav-link px-3" href="contact.php">Kapcsolat</a></li>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <li class="nav-item"><a class="nav-link px-3" href="user.php">Profilom</a></li>
                    <li class="nav-item"><a class="nav-link px-3 text-danger" href="logout.php">Kilépés</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link px-3" href="login.php">Belépés</a></li>
                <?php endif; ?>
                <li class="nav-item ms-lg-3">
                    <a href="booking.php" class="btn btn-dark rounded-0 px-4 py-2 uppercase small" style="letter-spacing: 1px; font-size: 0.75rem;">Foglalás</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<style>
    .nav-link { 
        font-size: 0.75rem; 
        text-transform: uppercase; 
        letter-spacing: 2px; 
        color: #2d2a26 !important; 
        transition: 0.3s; 
    }
    .nav-link:hover { color: #8e7d6a !important; }
</style>