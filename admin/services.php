<?php
require_once "../config/database.php"; 
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') { header("Location: ../public/login.php?error=4"); exit; }

$services = $pdo->query("SELECT * FROM services ORDER BY name ASC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AB MASSZÁZS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600&family=Shippori+Mincho:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/admin_style.css">
</head>
<body>
<?php include "assets/admin_navbar.php"; ?>

<div class="container pb-5">
    <div class="d-flex justify-content-between align-items-end mb-5">
        <div>
            <h1 class="brand mt-0 mb-1">Szolgáltatások</h1>
            <p class="text-muted small text-uppercase mb-0" style="letter-spacing: 2px;">Kezelések és árak kezelése</p>
        </div>
        <button class="nav-icon-btn" style="width: auto; padding: 0 20px;" onclick="openModal()">
            <i class="fas fa-plus me-2"></i> ÚJ HOZZÁADÁSA
        </button>
    </div>

    <div class="row g-4">
        <?php foreach($services as $s): ?>
        <div class="col-md-4">
            <div class="service-card" onclick='openServiceModal(<?= json_encode($s) ?>)'>
                <div class="service-content">
                    <h4 class="brand"><?= htmlspecialchars($s['name']) ?></h4>
                    <p class="text-muted small mb-4"><?= $s['duration'] ?> perc</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="price-tag"><?= number_format($s['price'], 0, ',', ' ') ?> Ft</span>
                        <div class="edit-circle">
                            <i class="fas fa-pen small"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<div class="modal fade" id="serviceModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="p-4">
                <h3 class="brand mb-4 text-center" id="modalTitle">Szolgáltatás szerkesztése</h3>
                <form id="serviceForm">
                    <div class="mb-3">
                        <label class="small text-muted mb-1 fw-bold">MEGNEVEZÉS</label>
                        <input type="text" id="s_name" class="form-control" placeholder="Pl. Svédmasszázs" required>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="small text-muted mb-1 fw-bold">ÁR (FT)</label>
                            <input type="number" id="s_price" class="form-control" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="small text-muted mb-1 fw-bold">IDŐ (PERC)</label>
                            <input type="number" id="s_dur" class="form-control" required>
                        </div>
                    </div>
                    <div class="d-flex gap-2 pt-3">
                        <button type="submit" class="btn btn-zen flex-grow-1">Mentés mentése</button>
                        <button type="button" class="btn btn-outline-dark rounded-0 px-4" data-bs-dismiss="modal">Mégse</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    h1.brand {
    font-weight: 300;
    margin-bottom: 30px !important;
}

.service-card {
    background: var(--j-white);
    border: 1px solid var(--j-border);
    padding: 3rem 2.5rem !important;
    height: 100%;
    cursor: pointer;
    transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
    position: relative;
    color:black;
}

.service-card:hover {
    transform: translateY(-5px);
    border-color: var(--j-accent);
    box-shadow: 0 15px 35px rgba(0,0,0,0.05);
}

.service-card h4::after {
    content: '';
    display: block;
    width: 0;
    height: 2px;
    background: var(--j-accent);
    transition: 0.4s;
    margin-top: 8px;
}

.service-card:hover h4::after {
    width: 30px;
}

.price-tag {
    font-weight: 700;
    font-size: 1.1rem;
    color: var(--j-dark);
}

.edit-circle {
    width: 32px;
    height: 32px;
    background: var(--j-soft);
    color: var(--j-accent);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: 0.3s;
}

.service-card:hover .edit-circle {
    background: var(--j-accent);
    color: var(--j-white);
}


.form-control {
    border-radius: 0;
    border: 1px solid var(--j-border);
    padding: 0.8rem;
}

.form-control:focus {
    border-color: var(--j-accent);
    box-shadow: none;
}
.header-spacer {
    height: 100px;
}
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
let serviceModal;

document.addEventListener('DOMContentLoaded', function() {
    serviceModal = new bootstrap.Modal(document.getElementById('serviceModal'));
});

function openModal() {
    document.getElementById('serviceForm').reset();
    document.getElementById('modalTitle').innerText = 'Új szolgáltatás hozzáadása';
    serviceModal.show();
}

function openServiceModal(data) {
    document.getElementById('s_name').value = data.name;
    document.getElementById('s_price').value = data.price;
    document.getElementById('s_dur').value = data.duration;
    document.getElementById('modalTitle').innerText = 'Szolgáltatás szerkesztése';
    serviceModal.show();
}
</script>
</body>
</html>