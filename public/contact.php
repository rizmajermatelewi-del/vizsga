<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>ZEN SPA | Kapcsolat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600&family=Shippori+Mincho:wght@500;700&display=swap" rel="stylesheet">
    <style>
        :root { --j-bg: #f4f1ee; --j-dark: #2d2a26; --j-accent: #8e7d6a; --j-border: #e2ddd9; }
        body { background: var(--j-bg); color: var(--j-dark); font-family: 'Plus Jakarta Sans', sans-serif; }
        .contact-card { background: white; border: 1px solid var(--j-border); padding: 3rem; }
        h1 { font-family: 'Shippori Mincho', serif; margin-bottom: 2rem; }
        .info-box { border-left: 2px solid var(--j-accent); padding-left: 20px; margin-bottom: 2rem; }
        .form-control { border-radius: 0; border: 1px solid var(--j-border); padding: 12px; }
        .btn-zen { background: var(--j-dark); color: white; border-radius: 0; padding: 15px 30px; border: none; text-transform: uppercase; letter-spacing: 2px; }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="text-center mb-5">
            <a href="index.php" class="text-decoration-none text-muted small uppercase">← Vissza</a>
        </div>
        <div class="contact-card shadow-sm">
            <div class="row g-5">
                <div class="col-md-5">
                    <h1>Kapcsolat</h1>
                    <div class="info-box">
                        <h6 class="text-uppercase small fw-bold">Címünk</h6>
                        <p>1051 Budapest, Zen tér 1.</p>
                    </div>
                    <div class="info-box">
                        <h6 class="text-uppercase small fw-bold">Nyitvatartás</h6>
                        <p>Hétfő - Szombat: 08:00 - 18:00</p>
                    </div>
                    <div class="info-box">
                        <h6 class="text-uppercase small fw-bold">E-mail</h6>
                        <p>info@zenspa.hu</p>
                    </div>
                </div>
                <div class="col-md-7">
                    <form action="send_message.php" method="POST">
                        <div class="mb-3"><input type="text" class="form-control" placeholder="Név" required></div>
                        <div class="mb-3"><input type="email" class="form-control" placeholder="E-mail" required></div>
                        <div class="mb-3"><textarea class="form-control" rows="5" placeholder="Üzenet" required></textarea></div>
                        <button type="submit" class="btn-zen w-100">Üzenet küldése</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>