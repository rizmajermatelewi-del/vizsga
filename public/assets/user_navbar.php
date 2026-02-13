<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom sticky-top" style="padding: 1.5rem 0;">
    <div class="container">
        <div class="login-card">
            <div class="brand-container" onclick="location.href='index.php';" style="cursor: pointer;">
                <div class="brand-ab">AB</div>
                <div class="brand-subtitle">MASSZÁZS</div>
            </div>
        </div>
        
        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navContent">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navContent">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item">
                    <a class="nav-link" href="index.php#philosophy">Filozófiánk</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php#services">Masszázsok</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php#masters">Mestereink</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php#vouchers">Ajándékkártya</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php#booking">Időpont</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php#contact">Kapcsolat</a>
                </li>
                  <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
    <li class="nav-item">
        <a class="nav-link text-danger fw-bold" href="../admin/dashboard.php">
            <i class="fas fa-user-shield"></i> Vezérlőpult
        </a>
    </li>
<?php endif; ?>
                <li class="nav-item ms-lg-3">
                    <button onclick="toggleTheme()" class="btn btn-link nav-link shadow-none p-0 theme-toggle" title="Téma váltása">
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
                        <a href="logout.php" class="nav-link logout-link" title="Kijelentkezés">
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
    .brand-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 2px;
        transition: transform 0.3s ease;
    }

    .brand-container:hover {
        transform: scale(1.05);
    }

    .brand-ab {
        font-family: 'Shippori Mincho', serif;
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--j-text) !important;
        letter-spacing: 2px;
    }

    .brand-subtitle {
        font-size: 0.65rem;
        font-weight: 800;
        letter-spacing: 2px;
        text-transform: uppercase;
    }

    .nav-link {
        position: relative;
        font-weight: 600;
        padding: 0.5rem 1rem 0.8rem 1rem !important;
        transition: all 0.3s ease !important;
    }

    .nav-link::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        width: 0;
        height: 2px;
        background: var(--j-gold);
        transition: all 0.3s ease;
        transform: translateX(-50%);
    }

    .nav-link:hover::after {
        width: 100%;
    }

    .theme-toggle {
        font-size: 1.2rem !important;
        color: var(--j-text) !important;
        opacity: 0.7;
        transition: all 0.3s ease !important;
    }

    .theme-toggle:hover {
        opacity: 1;
        color: var(--j-gold) !important;
        transform: rotate(180deg);
    }

    .theme-toggle:focus {
        outline: 2px solid var(--j-gold);
        outline-offset: 2px;
        border-radius: 4px;
    }

    .logout-link {
        font-size: 1rem !important;
        color: var(--j-text) !important;
        opacity: 0.6 !important;
        transition: all 0.3s ease !important;
    }

    .logout-link:hover {
        opacity: 1 !important;
        color: var(--j-gold) !important;
        transform: translateX(3px) !important;
    }

    .logout-link:focus {
        outline: 2px solid var(--j-gold);
        outline-offset: 2px;
    }

    body.dark-theme .navbar {
        background-color: var(--j-nav) !important;
        border-bottom-color: var(--j-border) !important;
    }

    .login-card {
        padding: 0;
    }
</style>