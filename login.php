<?php
require_once 'database.php';
session_start();
require __DIR__ . '/PHPMailer-master/src/Exception.php';
require __DIR__ . '/PHPMailer-master/src/PHPMailer.php';
require __DIR__ . '/PHPMailer-master/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// E-mail küldő függvény
function sendWelcomeEmail($toEmail, $username) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'abmasszazsinfo@gmail.com'; 
        $mail->Password   = 'bfhhqbgrarmakkxh'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        $mail->CharSet    = 'UTF-8';

        $mail->setFrom('abmasszazsinfo@gmail.com', 'AB Masszázs'); 
        $mail->addAddress($toEmail, $username);

        $mail->isHTML(true);
        $mail->Subject = 'Üdvözöljük az AB Masszázsnál!';
        
        $mail->Body = "
        <div style='background-color: #fcfaf7; padding: 30px; font-family: sans-serif;'>
            <div style='max-width: 600px; margin: 0 auto; background-color: #ffffff; border: 1px solid #e0d6c5; padding: 40px; text-align: center;'>
                <h1 style='color: #463f3a; letter-spacing: 5px; font-weight: 300;'>ÜDVÖZÖLJÜK</h1>
                <h2 style='color: #8a5a44; font-weight: 400;'>Kedves $username!</h2>
                <p style='color: #666; line-height: 1.6;'>Sikeresen létrehozta profilját az AB Masszázs rendszerében.</p>
                <div style='margin-top: 30px;'>
                    <a href='http://localhost/login.php' style='background-color: #463f3a; color: white; padding: 15px 25px; text-decoration: none; border-radius: 50px; text-transform: uppercase; font-size: 12px; letter-spacing: 2px;'>Bejelentkezés</a>
                </div>
            </div>
        </div>";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

$toast_msg = '';
$toast_type = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'register') {
        $username = trim($_POST['reg_username']);
        $email = trim($_POST['reg_email']);
        $tel = preg_replace('/\D/', '', $_POST['reg_tel'] ?? '');
        $password = $_POST['reg_password'];
        $confirm = $_POST['reg_confirm'];

        if ($password !== $confirm) { header('Location: login.php?error=2'); exit; }

        try {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('INSERT INTO users (username, email, tel, password) VALUES (?, ?, ?, ?)');
            
            if ($stmt->execute([$username, $email, $tel, $hashed])) {
                sendWelcomeEmail($email, $username);
                header('Location: login.php?success=1');
                exit;
            }
        } catch (PDOException $e) { header('Location: login.php?error=3'); exit; }
    }
    
if ($action === 'login') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ?');
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['username'];
        $_SESSION['role'] = $user['role']; 

        if ($user['role'] === 'admin') {
            header('Location: dashboard.php');
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
if (isset($_GET['error'])) {
    $errors = ['1' => 'Hibás adatok!', '2' => 'A jelszavak nem egyeznek!', '3' => 'Foglalt név vagy email!', '5' => 'Hibás adatok!'];
    $toast_msg = $errors[$_GET['error']] ?? 'Hiba történt!';
    $toast_type = 'danger';
}
if (isset($_GET['success'])) {
    $toast_msg = 'Sikeres regisztráció! Most már beléphetsz.';
    $toast_type = 'success';
}
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AB MASSZÁZS | Belépés</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root { --j-bg: #ece0d1; --j-dark: #463f3a; --j-accent: #8a5a44; --j-border: #dbc1ac; }
        body { background: var(--j-bg); color: var(--j-dark); font-family: 'Segoe UI', sans-serif; display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; overflow-x: hidden; } 
        .login-card { background: #fff; padding: 2.5rem; border-radius: 20px; width: 100%; max-width: 400px; border: 1px solid var(--j-border); box-shadow: 0 10px 30px rgba(0,0,0,0.05); position: relative; }
        .nav-tabs { border: none; margin-bottom: 2rem; gap: 20px; }
        .nav-link { color: var(--j-dark) !important; opacity: 0.4; border: none !important; text-transform: uppercase; font-size: 0.8rem; letter-spacing: 2px; font-weight: 600; padding: 0; padding-bottom: 5px; }
        .nav-link.active { opacity: 1; border-bottom: 2px solid var(--j-accent) !important; background: transparent !important; }
        .input-group-zen { position: relative; border-bottom: 1px solid var(--j-border); margin-bottom: 1.5rem; display: flex; align-items: center; transition: 0.3s; }
        .input-group-zen:focus-within { border-color: var(--j-accent); background: rgba(138, 90, 68, 0.03); }
        .input-group-zen .form-control { border: none; background: transparent !important; padding: 12px 5px; flex: 1; color: var(--j-dark); }
        .input-group-zen .form-control:focus { box-shadow: none; }
        .toggle-pass { cursor: pointer; opacity: 0.5; padding: 0 10px; transition: 0.3s; font-size: 1.1rem; }
        .toggle-pass:hover { opacity: 1; color: var(--j-accent); }
        .btn-zen { background: var(--j-dark); color: #fff; border-radius: 50px; padding: 12px; width: 100%; border: none; margin-top: 1rem; text-transform: uppercase; letter-spacing: 1px; transition: 0.3s; }
        .btn-zen:hover { background: var(--j-accent); }
        .progress-ab { height: 4px; background: #f0f0f0; margin-top: -1.2rem; margin-bottom: 1.5rem; border-radius: 10px; }
        #strengthBar { height: 100%; width: 0%; transition: 0.4s; border-radius: 10px; }
        .toast-container { position: fixed; top: 20px; right: 20px; z-index: 10000; }
        .zen-toast { background: white; border-left: 4px solid var(--j-accent); min-width: 250px; padding: 15px; border-radius: 8px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); display: flex; align-items: center; justify-content: space-between; animation: slideIn 0.5s ease-out forwards; }
        .zen-toast.danger { border-left-color: #d9534f; }
        .zen-toast.success { border-left-color: #8e9775; }
        @keyframes slideIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
        @keyframes slideOut { from { transform: translateX(0); opacity: 1; } to { transform: translateX(100%); opacity: 0; } }
    </style>
</head>
<body>

<div class="toast-container" id="toastBox"></div>

<div class="login-card">
    <h2 class="text-center mb-4" style="letter-spacing: 6px; font-weight: 300; color: var(--j-dark);">AB MASSZÁZS</h2>

    <ul class="nav nav-tabs justify-content-center">
        <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-l">Belépés</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-r">Regisztráció</button></li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane fade show active" id="tab-l">
            <form method="POST">
                <input type="hidden" name="action" value="login">
                <div class="input-group-zen">
                    <input type="text" name="username" class="form-control" placeholder="FELHASZNÁLÓNÉV" required>
                </div>
                <div class="input-group-zen">
                    <input type="password" name="password" id="logPass" class="form-control" placeholder="JELSZÓ" required>
                    <i class="bi bi-eye toggle-pass" data-target="logPass"></i>
                </div>
                <button class="btn btn-zen">Belépés</button>
            </form>
        </div>

        <div class="tab-pane fade" id="tab-r">
            <form method="POST" id="regForm">
                <input type="hidden" name="action" value="register">
                <div class="input-group-zen">
                    <input type="text" name="reg_username" class="form-control" placeholder="FELHASZNÁLÓNÉV" required>
                </div>
                <div class="input-group-zen">
                    <input type="email" name="reg_email" class="form-control" placeholder="E-MAIL" required>
                </div>
                <div class="input-group-zen">
                    <input type="text" name="reg_tel" id="reg_tel" class="form-control" value="+36 " required>
                </div>
                <div class="input-group-zen mb-4">
                    <input type="password" name="reg_password" id="pass" class="form-control" placeholder="JELSZÓ" required>
                    <i class="bi bi-eye toggle-pass" data-target="pass"></i>
                </div>
                <div class="progress-ab"><div id="strengthBar"></div></div>
                <div class="input-group-zen">
                    <input type="password" name="reg_confirm" id="confirm" class="form-control" placeholder="MEGERŐSÍTÉS" required>
                    <i class="bi bi-eye toggle-pass" data-target="confirm"></i>
                </div>
                <button type="submit" class="btn btn-zen">Regisztráció</button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function showToast(message, type) {
        const box = document.getElementById('toastBox');
        const toast = document.createElement('div');
        toast.className = `zen-toast ${type}`;
        toast.innerHTML = `<span>${message}</span><i class="bi bi-x" style="cursor:pointer" onclick="this.parentElement.remove()"></i>`;
        box.appendChild(toast);
        
        setTimeout(() => {
            toast.style.animation = 'slideOut 0.5s ease-in forwards';
            setTimeout(() => toast.remove(), 500);
        }, 4000);
    }

    <?php if($toast_msg): ?>
        showToast("<?= $toast_msg ?>", "<?= $toast_type ?>");
    <?php endif; ?>

    document.querySelectorAll('.toggle-pass').forEach(icon => {
        icon.addEventListener('click', function() {
            const target = document.getElementById(this.getAttribute('data-target'));
            if (target.type === 'password') {
                target.type = 'text';
                this.classList.replace('bi-eye', 'bi-eye-slash');
            } else {
                target.type = 'password';
                this.classList.replace('bi-eye-slash', 'bi-eye');
            }
        });
    });

    document.getElementById('reg_tel').addEventListener('input', function(e) {
        let v = e.target.value.replace(/[^\d+]/g, '');
        if (!v.startsWith('+36')) v = '+36 ';
        if (v.length > 12) v = v.substring(0, 12);
        e.target.value = v;
    });

    const pass = document.getElementById('pass');
    const bar = document.getElementById('strengthBar');
    pass.addEventListener('input', () => {
        let v = pass.value, s = 0;
        if(v.length >= 6) s += 25;
        if(v.match(/[A-Z]/)) s += 25;
        if(v.match(/[0-9]/)) s += 25;
        if(v.match(/[^A-Za-z0-9]/)) s += 25;
        bar.style.width = s + '%';
        bar.style.backgroundColor = s < 50 ? '#d9b99b' : (s < 100 ? '#b5c99a' : '#8e9775');
    });

    document.getElementById('regForm').addEventListener('submit', function(e) {
        const confirm = document.getElementById('confirm');
        if (pass.value !== confirm.value) {
            e.preventDefault();
            showToast("A két jelszó nem egyezik meg!", "danger");
            confirm.closest('.input-group-zen').style.borderBottomColor = '#d9534f';
        }
    });

    window.onload = () => {
        const url = new URLSearchParams(window.location.search);
        if (url.has('error') && url.get('error') !== '1') {
            new bootstrap.Tab(document.querySelector('[data-bs-target="#tab-r"]')).show();
        } 
    };
</script>
</body>
</html>