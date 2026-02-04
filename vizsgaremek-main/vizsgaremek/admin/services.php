<?php
require_once "../config/database.php"; 
session_start();
$services = $pdo->query("SELECT * FROM services ORDER BY name ASC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>AB MASSZÁZS | Szolgáltatások</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/admin_style.css">
</head>
<body>
<?php include "assets/admin_navbar.php"; ?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <h1 class="brand m-0">Szolgáltatások</h1> <button class="btn-zen" onclick="openModal()">+ Új hozzáadása</button>
    </div>
    </div>

    <div class="row g-4">
        <?php foreach($services as $s): ?>
        <div class="col-md-4">
            <div class="service-card cursor-pointer" onclick='openServiceModal(<?= json_encode($s) ?>)'>
                <h4 class="brand"><?= $s['name'] ?></h4>
                <p class="text-muted small"><?= $s['duration'] ?> perc</p>
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <span class="fw-bold fs-5"><?= number_format($s['price'], 0, ',', ' ') ?> Ft</span>
                    <i class="fas fa-edit text-accent"></i>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<div class="modal fade" id="serviceModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content p-4">
            <h3 class="brand mb-4">Szolgáltatás szerkesztése</h3>
            <form>
                <div class="mb-3"><label class="small text-muted fw-bold">NÉV</label><input type="text" id="s_name" class="form-control"></div>
                <div class="mb-3"><label class="small text-muted fw-bold">ÁR (FT)</label><input type="number" id="s_price" class="form-control"></div>
                <div class="mb-4"><label class="small text-muted fw-bold">IDŐTARTAM (PERC)</label><input type="number" id="s_dur" class="form-control"></div>
                <button type="submit" class="btn btn-zen w-100">Mentés</button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function openServiceModal(data) {
    document.getElementById('s_name').value = data.name || '';
    document.getElementById('s_price').value = data.price || '';
    document.getElementById('s_dur').value = data.duration || '';
    new bootstrap.Modal(document.getElementById('serviceModal')).show();
}
</script>
</body>
</html>