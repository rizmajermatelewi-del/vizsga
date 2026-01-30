<?php
require_once "../config/database.php"; 
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../public/login.php?error=4");
    exit;
}

// --- UTALVÁNY MŰVELETEK (Mentés, Törlés, Új) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'save') {
        $stmt = $pdo->prepare("UPDATE vouchers SET code=?, amount=?, status=?, expiry_date=? WHERE id=?");
        $stmt->execute([$_POST['code'], $_POST['amount'], $_POST['status'], $_POST['expiry_date'], $_POST['id']]);
    } elseif ($_POST['action'] === 'delete') {
        $stmt = $pdo->prepare("DELETE FROM vouchers WHERE id=?");
        $stmt->execute([$_POST['id']]);
    } elseif ($_POST['action'] === 'create') {
        $stmt = $pdo->prepare("INSERT INTO vouchers (code, amount, status, expiry_date) VALUES (?, ?, ?, ?)");
        $stmt->execute([$_POST['code'], $_POST['amount'], 'active', $_POST['expiry_date']]);
    }
    header("Location: vouchers.php?success=1"); exit;
}

// Utalványok lekérése
$vouchers = $pdo->query("SELECT * FROM vouchers ORDER BY created_at DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>ZEN SPA | Utalványok Kezelése</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600&family=Shippori+Mincho:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --japandi-bg: #f4f1ee;
            --japandi-dark: #2d2a26;
            --japandi-accent: #8e7d6a;
            --japandi-border: #e2ddd9;
            --japandi-white: #ffffff;
        }

        body { background: var(--japandi-bg); color: var(--japandi-dark); font-family: 'Plus Jakarta Sans', sans-serif; }

        /* Navigáció Hover */
        .page-header { background: var(--japandi-white); border-bottom: 1px solid var(--japandi-border); padding: 0; margin-bottom: 2.5rem; }
        .nav-link { 
            color: var(--japandi-dark) !important; font-size: 0.8rem; text-transform: uppercase; 
            letter-spacing: 2px; padding: 2rem 1.5rem !important; position: relative; transition: 0.3s;
        }
        .nav-link::after {
            content: ''; position: absolute; bottom: 0; left: 50%; width: 0; height: 3px;
            background: var(--japandi-accent); transition: 0.3s ease; transform: translateX(-50%);
        }
        .nav-link:hover::after, .nav-link.active::after { width: 100%; }

        /* Kártya és Kereső */
        .japandi-card { background: var(--japandi-white); border: 1px solid var(--japandi-border); padding: 2rem; position: relative; }
        .search-input {
            width: 100%; padding: 0.8rem 1rem; border-radius: 0;
            border: 1px solid var(--japandi-border); background: var(--japandi-white);
            font-size: 0.9rem; margin-bottom: 1.5rem;
        }

        /* Lista stílus */
        .voucher-item { 
            border-bottom: 1px solid var(--japandi-border); padding: 1.2rem; 
            transition: 0.3s; cursor: pointer; display: flex; align-items: center; justify-content: space-between;
        }
        .voucher-item:hover { background: #faf9f8; transform: translateX(8px); }
        .voucher-code { font-family: 'Shippori Mincho', serif; font-weight: 700; letter-spacing: 1px; font-size: 1.1rem; }
        
        .status-badge { font-size: 0.65rem; text-transform: uppercase; letter-spacing: 1px; padding: 4px 10px; }
        .status-active { background: #e8f5e9; color: #2e7d32; }
        .status-used { background: #f5f5f5; color: #757575; }
        .status-expired { background: #ffebee; color: #c62828; }

        .btn-japandi { background: var(--japandi-dark); color: white; border-radius: 0; border: none; padding: 12px 25px; text-transform: uppercase; font-size: 0.8rem; letter-spacing: 1px; transition: 0.3s; }
        .btn-japandi:hover { background: var(--japandi-accent); }

        .modal-content { border-radius: 0; border: none; background: var(--japandi-bg); }
    </style>
</head>
<body>

<div class="page-header shadow-sm">
    <div class="container d-flex justify-content-between align-items-center">
        <h1 class="m-0" style="font-size: 1.2rem; letter-spacing: 4px; font-family: 'Shippori Mincho', serif;">ZEN SPA</h1>
        <div class="d-flex">
            <a href="dashboard.php" class="nav-link">Dashboard</a>
            <a href="calendar.php" class="nav-link">Naptár</a>
            <a href="services.php" class="nav-link">Szolgáltatások</a>
            <a href="vouchers.php" class="nav-link active">Utalványok</a>
        </div>
    </div>
</div>

<div class="container pb-5">
    <div class="d-flex justify-content-between align-items-end mb-4">
        <div>
            <h2 style="font-family: 'Shippori Mincho', serif;">Utalványok</h2>
            <p class="text-muted small mb-0">Hozzon létre újat, vagy szerkessze a meglévőket.</p>
        </div>
        <button class="btn btn-japandi" onclick="openCreateModal()">+ Új utalvány</button>
    </div>

    <div class="japandi-card shadow-sm">
        <input type="text" id="voucherSearch" class="search-input" placeholder="Keresés kód alapján...">
        
        <div id="voucherList">
            <?php foreach($vouchers as $v): ?>
            <div class="voucher-item" onclick='editVoucher(<?= json_encode($v) ?>)'>
                <div>
                    <div class="voucher-code"><?= htmlspecialchars($v['code']) ?></div>
                    <div class="small text-muted mt-1">
                        Érték: <strong><?= number_format($v['amount'], 0, '.', ' ') ?> Ft</strong> | 
                        Lejárat: <?= $v['expiry_date'] ?>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <span class="status-badge status-<?= $v['status'] ?>">
                        <?= $v['status'] == 'active' ? 'Aktív' : ($v['status'] == 'used' ? 'Felhasznált' : 'Lejárt') ?>
                    </span>
                    <i class="fas fa-chevron-right opacity-25"></i>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<div class="modal fade" id="voucherModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content p-4 shadow-lg">
            <form method="POST">
                <input type="hidden" name="id" id="v_id">
                <input type="hidden" name="action" id="v_action" value="save">
                
                <h4 id="modalTitle" class="mb-4" style="font-family: 'Shippori Mincho', serif;">Utalvány szerkesztése</h4>
                
                <div class="mb-3">
                    <label class="small fw-bold text-muted uppercase">Utalvány kód</label>
                    <input type="text" name="code" id="v_code" class="form-control rounded-0" required>
                </div>
                
                <div class="mb-3">
                    <label class="small fw-bold text-muted uppercase">Összeg (Ft)</label>
                    <input type="number" name="amount" id="v_amount" class="form-control rounded-0" required>
                </div>

                <div class="mb-3">
                    <label class="small fw-bold text-muted uppercase">Lejárati dátum</label>
                    <input type="date" name="expiry_date" id="v_expiry" class="form-control rounded-0" required>
                </div>

                <div class="mb-4" id="statusGroup">
                    <label class="small fw-bold text-muted uppercase">Állapot</label>
                    <select name="status" id="v_status" class="form-select rounded-0">
                        <option value="active">Aktív</option>
                        <option value="used">Felhasznált</option>
                        <option value="expired">Lejárt</option>
                    </select>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" name="action" value="delete" id="deleteBtn" class="btn btn-outline-danger rounded-0" onclick="return confirm('Biztosan törlöd?')">Törlés</button>
                    <button type="submit" id="saveBtn" class="btn btn-japandi flex-grow-1">Mentés</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
const vModal = new bootstrap.Modal(document.getElementById('voucherModal'));

// Szerkesztés megnyitása
function editVoucher(data) {
    document.getElementById('modalTitle').innerText = "Utalvány szerkesztése";
    document.getElementById('v_action').value = "save";
    document.getElementById('v_id').value = data.id;
    document.getElementById('v_code').value = data.code;
    document.getElementById('v_amount').value = data.amount;
    document.getElementById('v_expiry').value = data.expiry_date;
    document.getElementById('v_status').value = data.status;
    
    document.getElementById('statusGroup').style.display = "block";
    document.getElementById('deleteBtn').style.display = "block";
    vModal.show();
}

// Új létrehozása
function openCreateModal() {
    document.getElementById('modalTitle').innerText = "Új utalvány generálása";
    document.getElementById('v_action').value = "create";
    document.getElementById('v_id').value = "";
    document.getElementById('v_code').value = "ZEN-" + Math.random().toString(36).substr(2, 8).toUpperCase();
    document.getElementById('v_amount').value = "";
    document.getElementById('v_expiry').value = "";
    
    document.getElementById('statusGroup').style.display = "none";
    document.getElementById('deleteBtn').style.display = "none";
    vModal.show();
}

// Kereső funkció
document.getElementById('voucherSearch').addEventListener('input', function(e) {
    const term = e.target.value.toLowerCase();
    const items = document.querySelectorAll('.voucher-item');
    items.forEach(item => {
        const code = item.querySelector('.voucher-code').innerText.toLowerCase();
        item.style.display = code.includes(term) ? 'flex' : 'none';
    });
});
</script>
</body>
</html>