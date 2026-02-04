<?php
session_start();

// Adatbázis kapcsolat - a te beállításaiddal
try {
    $pdo = new PDO('mysql:host=localhost;dbname=vizsgaremek;charset=utf8mb4', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Hiba történt az adatbázis csatlakozásakor.');
}

$error_message = '';
$success_message = '';

// Üzenetek kezelése a GET paraméterek alapján
if (isset($_GET['error'])) {
    switch ($_GET['error']) {
        case '1': $error_message = 'Hibás felhasználónév vagy jelszó!'; break;
        case '2': $error_message = 'A jelszavak nem egyeznek!'; break;
        case '3': $error_message = 'Ez a felhasználónév már foglalt!'; break;
        case '4': $error_message = 'Kérjük, jelentkezzen be a tartalom megtekintéséhez!'; break;
    }
}
if (isset($_GET['success']) && $_GET['success'] === '1') {
    $success_message = 'Sikeres regisztráció! Most már beléphet.';
}

// POST MŰVELETEK (Login & Register)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'register') {
        $username = trim($_POST['reg_username']);
        $password = $_POST['reg_password'];
        $confirm = $_POST['reg_confirm'];

        if ($password !== $confirm) {
            header('Location: login.php?error=2'); 
            exit;
        }

        $stmt = $pdo->prepare('SELECT id FROM users WHERE username = :u');
        $stmt->execute(['u' => $username]);
        if ($stmt->fetch()) {
            header('Location: login.php?error=3'); 
            exit;
        }

        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare('INSERT INTO users (username, password, role) VALUES (:u, :p, "user")');
        $stmt->execute(['u' => $username, 'p' => $hashed]);

        header('Location: login.php?success=1'); 
        exit;
    } 
    
    elseif ($action === 'login') {
        $username = trim($_POST['username']);
        $password = $_POST['password'];

        $stmt = $pdo->prepare('SELECT id, username, password, role FROM users WHERE username = :u LIMIT 1');
        $stmt->execute(['u' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['username']; // Összhangban az index.php-val
            $_SESSION['role'] = $user['role'] ?? 'user';

            if ($_SESSION['role'] === 'admin') {
                header('Location: ../admin/dashboard.php');
            } else {
                header('Location: index.php');
            }
            exit;
        } else {
            header('Location: login.php?error=1');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ZEN SPA | Sanctuary Entry</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600&family=Shippori+Mincho:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/user_style.css">
</head>
<body>

<div class="login-card">
    <div class="brand fs-2">ZEN SPA</div>

    <?php if($error_message): ?>
        <div class="alert alert-danger text-center"><?= $error_message ?></div>
    <?php endif; ?>

    <?php if($success_message): ?>
        <div class="alert alert-success text-center"><?= $success_message ?></div>
    <?php endif; ?>

    <ul class="nav nav-tabs" id="authTab" role="tablist">
        <li class="nav-item">
            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#login-pane">Belépés</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#register-pane">Regisztráció</button>
        </li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane fade show active" id="login-pane">
            <form method="POST">
                <input type="hidden" name="action" value="login">
                <div class="mb-3">
                    <label class="small text-muted text-uppercase">Felhasználónév</label>
                    <input name="username" class="form-control" required>
                </div>
                <div class="mb-4">
                    <label class="small text-muted text-uppercase">Jelszó</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button class="btn btn-zen w-100">Bejelentkezés</button>
            </form>
        </div>

        <div class="tab-pane fade" id="register-pane">
            <form method="POST">
                <input type="hidden" name="action" value="register">
                <div class="mb-3">
                    <label class="small text-muted text-uppercase">Felhasználónév</label>
                    <input name="reg_username" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="small text-muted text-uppercase">Jelszó</label>
                    <input type="password" name="reg_password" class="form-control" required>
                </div>
                <div class="mb-4">
                    <label class="small text-muted text-uppercase">Jelszó megerősítése</label>
                    <input type="password" name="reg_confirm" class="form-control" required>
                </div>
                <button class="btn btn-zen w-100">Fiók létrehozása</button>
            </form>
        </div>
    </div>

    <div class="text-center mt-5">
        <a href="../index.php" class="text-decoration-none text-muted small uppercase" style="letter-spacing: 1px;">
            ← Vissza a főoldalra
        </a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>