<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom sticky-top" style="padding: 1.5rem 0;">
    <div class="container">
        <div class="login-card">
<div class="brand-container" onclick="location.href='index.php';">
    <div class="brand-ab">AB</div>
    <div class="brand-subtitle">MASSZÁZS</div>
</div>
    </div>
        
        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navContent">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navContent">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item"><a class="nav-link" href="index.php#philosophy">Filozófiánk</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php#services">Masszázsok</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php#masters">Mestereink</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php#vouchers">Ajándékkártya</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php#booking">Időpont</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php#contact">Kapcsolat</a></li>
                
                <li class="nav-item ms-lg-3">
                    <button onclick="toggleTheme()" class="btn btn-link nav-link shadow-none p-0">
                        <i class="fas fa-circle-half-stroke"></i>
                    </button>
                </li>

                <?php if(isset($_SESSION['user_id'])): ?>
                    <li class="nav-item ms-lg-3">
                        <a href="user.php" class="btn btn-dark btn-sm rounded-0 px-3 py-2 text-uppercase small">
                            <i class="fas fa-user-circle me-1"></i> Profil
                        </a>
                    </li>
                    <li class="nav-item ms-lg-2">
                        <a href="logout.php" class="nav-link logout-special">
                            <i class="fas fa-sign-out-alt"></i>
                        </a>
                    </li>
                <?php else: ?>
                    <li class="nav-item ms-lg-2">
                        <a href="login.php" class="btn btn-dark btn-sm rounded-0 px-4">BELÉPÉS</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<style>
    /* Kijelentkezés ikon speciális Japandi stílusa */
    .logout-special {
        font-size: 0.9rem !important;
        color: var(--j-text) !important;
        opacity: 0.5 !important;
        transition: var(--transition) !important;
        padding-left: 15px !important;
    }
    .logout-special:hover {
        opacity: 1 !important;
        color: #a35d5d !important; /* Enyhe vöröses tónus kilépéskor */
        transform: translateX(3px) !important;
    }
    /* Aktív link finom jelzése */
    .nav-link {
        position: relative;
    }
    .nav-link::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 10px;
        width: 0;
        height: 1px;
        background: var(--j-accent);
        transition: width 0.3s ease;
    }
    .nav-link:hover::after {
        width: calc(100% - 20px);
    }
</style>