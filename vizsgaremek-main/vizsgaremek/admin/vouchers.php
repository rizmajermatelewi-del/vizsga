<?php
require_once "../config/database.php"; 
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') { header("Location: ../public/login.php?error=4"); exit; }

// Utalványok lekérése
$vouchers = $pdo->query("SELECT * FROM vouchers ORDER BY created_at DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>AB MASSZÁZS | Utalványok</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/admin_style.css">
</head>
<body>
<?php include "assets/admin_navbar.php"; ?>

<div class="container pb-5">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h1 class="brand mt-0 mb-1">Utalványok</h1>
            <p class="text-muted small text-uppercase" style="letter-spacing: 2px;">Aktív és felhasznált keretek kezelése</p>
        </div>
        <button class="btn-zen" onclick="openVoucherModal()">+ Új utalvány generálása</button>
    </div>

    <div class="j-card p-0 overflow-hidden shadow-sm border-0">
        <table class="table mb-0">
            <thead style="background: var(--j-soft);">
                <tr>
                    <th class="ps-4 py-3 small text-muted border-0">KÓD</th>
                    <th class="py-3 small text-muted border-0">ÉRTÉK</th>
                    <th class="py-3 small text-muted border-0">ÁLLAPOT</th>
                    <th class="py-3 small text-muted border-0 text-end pe-4">MŰVELET</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($vouchers as $v): ?>
                <tr class="booking-item align-middle">
                    <td class="ps-4">
                        <span class="code-box"><?= htmlspecialchars($v['code']) ?></span>
                    </td>
                    <td class="fw-bold"><?= number_format($v['amount'], 0, ',', ' ') ?> Ft</td>
                    <td>
                        <span class="badge rounded-0 py-2 px-3" style="font-size: 0.6rem; letter-spacing: 1px; background: <?= $v['status'] == 'active' ? 'rgba(72,187,120,0.15); color: #2f855a' : 'rgba(160,155,151,0.15); color: #4a4a4a' ?>;">
                            <?= strtoupper($v['status']) ?>
                        </span>
                    </td>
                    <td class="text-end pe-4">
                        <button class="btn-control d-inline-flex" title="Szerkesztés"><i class="fas fa-ellipsis-h"></i></button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="voucherModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="p-2">
                <h3 class="brand mb-4 text-center">Utalvány generálása</h3>
                <form id="voucherForm">
                    <div class="mb-4">
                        <label class="small text-muted mb-2 fw-bold">UTALVÁNY KÓDJA</label>
                        <div class="input-group">
                            <input type="text" id="v_code" class="form-control fw-bold text-center" style="letter-spacing: 3px;" readonly>
                            <button type="button" class="btn btn-outline-dark rounded-0 px-3" onclick="generateCode()">
                                <i class="fas fa-sync-alt"></i>
                            </button>
                        </div>
                        <small class="text-muted mt-1 d-block">A rendszer egyedi azonosítót generál.</small>
                    </div>

                    <div class="mb-4">
                        <label class="small text-muted mb-2 fw-bold">ÖSSZEG (FT)</label>
                        <input type="number" id="v_amount" class="form-control" placeholder="Pl. 15000" required>
                    </div>

                    <div class="d-flex gap-2 pt-2">
                        <button type="submit" class="btn btn-zen flex-grow-1">Létrehozás és mentés</button>
                        <button type="button" class="btn btn-outline-dark rounded-0 px-4" data-bs-dismiss="modal">Mégse</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const voucherModal = new bootstrap.Modal(document.getElementById('voucherModal'));

    function openVoucherModal() {
        generateCode();
        voucherModal.show();
    }
</script>

<style>
/* Specifikus utalvány stílusok */
.code-box {
    background: var(--j-soft);
    padding: 8px 15px;
    border: 1px dashed var(--j-accent);
    font-family: 'Monaco', 'Consolas', monospace;
    font-size: 0.9rem;
    color: var(--j-dark);
    display: inline-block;
}

.table > :not(caption) > * > * {
    padding: 1.2rem 1rem;
    border-bottom: 1px solid var(--j-border);
}

.booking-item {
    transition: all 0.3s ease;
}

.booking-item:hover {
    background-color: var(--j-soft) !important;
}
</style>

</body>
</html>