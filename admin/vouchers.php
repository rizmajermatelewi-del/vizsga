<?php
require_once "../config/database.php"; 
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') { header("Location: ../public/login.php?error=4"); exit; }


$vouchers = $pdo->query("SELECT code, amount, status FROM vouchers ORDER BY created_at DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AB MASSZÁZS | Utalványok</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600&family=Shippori+Mincho:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/admin_style.css">
</head>
<body>
<?php include "assets/admin_navbar.php"; ?>

<div class="container pb-5">
    <div class="mb-5">
        <h1 class="brand mt-0 mb-1">Utalványok</h1>
        <p class="text-muted small text-uppercase" style="letter-spacing: 2px;">Aktuális utalványkészlet és állapotok</p>
    </div>

    <div class="j-card p-0 overflow-hidden border-0 bg-transparent">
        <table class="table admin-table">
            <thead>
                <tr>
                    <th class="ps-4">UTALVÁNY KÓD</th>
                    <th>ÉRTÉK</th>
                    <th class="text-end pe-4">ÁLLAPOT</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($vouchers as $v): ?>
                <tr class="voucher-row align-middle">
                    <td class="ps-4" data-label="KÓD">
                        <span class="code-box"><?= htmlspecialchars($v['code']) ?></span>
                    </td>
                    <td data-label="ÉRTÉK">
                        <span class="fw-bold" style="color: var(--j-text);"><?= number_format($v['amount'], 0, ',', ' ') ?> Ft</span>
                    </td>
                    <td class="text-end pe-4" data-label="ÁLLAPOT">
                        <?php if($v['status'] == 'active'): ?>
                            <span class="status-indicator active">
                                <i class="fas fa-check-circle me-1"></i> AKTÍV
                            </span>
                        <?php else: ?>
                            <span class="status-indicator used">
                                <i class="fas fa-history me-1"></i> FELHASZNÁLT
                            </span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<style>

.admin-table {
    border-collapse: separate;
    border-spacing: 0 10px;
    --bs-table-bg: transparent !important;
    --bs-table-color: transparent !important;
}

.admin-table thead th {
    background: transparent;
    border: none;
    padding: 10px;
    font-size: 0.65rem;
    letter-spacing: 2px;
    color: var(--j-accent);
    text-transform: uppercase;
}

.voucher-row {
    /*background: var(--j-white);*/
    transition: background 0.3s ease;
}

.voucher-row td {
    padding: 1.2rem 10px !important;
    border-top: 1px solid var(--j-border);
    border-bottom: 1px solid var(--j-border);
}

.voucher-row td:first-child { border-left: 1px solid var(--j-border); }
.voucher-row td:last-child { border-right: 1px solid var(--j-border); }


.code-box {
    font-family: 'Monaco', monospace;
    font-size: 0.9rem;
    color: var(--j-text);
    letter-spacing: 1px;
    padding: 4px 8px;
    background: var(--j-border);
}


.status-indicator {
    font-size: 0.65rem;
    font-weight: 700;
    letter-spacing: 1px;
    padding: 5px 12px;
    display: inline-flex;
    align-items: center;
}

.text-muted {
    color: var(--j-text-muted) !important;
}

.status-indicator.active { color: #2f855a; }
.status-indicator.used { color: #a0aec0; opacity: 0.6; }


@media (max-width: 768px) {
    .admin-table thead { display: none; }
    .voucher-row { display: block; margin-bottom: 15px; border: 1px solid var(--j-border) !important; }
    .voucher-row td {
        display: flex;
        justify-content: space-between;
        padding: 1rem !important;
        border: none !important;
        border-bottom: 1px solid var(--j-soft) !important;
    }
    .voucher-row td:last-child { border-bottom: none !important; }
    .voucher-row td::before {
        content: attr(data-label);
        font-size: 0.6rem;
        color: var(--j-accent);
        font-weight: 800;
    }
}
.header-spacer {
    height: 100px;
}
   h1.brand {
    font-weight: 300;
    margin-bottom: 30px !important;
}
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>