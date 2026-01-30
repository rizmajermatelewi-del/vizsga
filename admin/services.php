<?php
require_once "../config/database.php"; 
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../public/login.php?error=4");
    exit;
}

// --- MŰVELETEK KEZELÉSE (Érintetlen backend) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    try {
        if ($_POST['action'] === 'add') {
            $stmt = $pdo->prepare("INSERT INTO services (name, description, price, duration) VALUES (?, ?, ?, ?)");
            $stmt->execute([$_POST['name'], $_POST['description'], $_POST['price'], $_POST['duration']]);
        } 
        elseif ($_POST['action'] === 'update') {
            $stmt = $pdo->prepare("UPDATE services SET name = ?, description = ?, price = ?, duration = ? WHERE id = ?");
            $stmt->execute([$_POST['name'], $_POST['description'], $_POST['price'], $_POST['duration'], $_POST['id']]);
        } 
        elseif ($_POST['action'] === 'delete') {
            $stmt = $pdo->prepare("DELETE FROM services WHERE id = ?");
            $stmt->execute([$_POST['id']]);
        }
        header("Location: services.php?success=1");
        exit;
    } catch (PDOException $e) {
        $error = "Hiba történt: " . $e->getMessage();
    }
}

// --- ADATOK LEKÉRÉSE ---
$services = $pdo->query("SELECT * FROM services ORDER BY name ASC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ZEN SPA | Szolgáltatások</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600&family=Shippori+Mincho:wght@500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --japandi-bg: #f4f1ee; /* Meleg homokszín */
            --japandi-dark: #2d2a26; /* Faszén szürke - kiváló olvashatóság */
            --japandi-accent: #8e7d6a; /* Föld tónus */
            --japandi-border: #e2ddd9;
            --japandi-white: #ffffff;
        }

        body { 
            background-color: var(--japandi-bg); 
            color: var(--japandi-dark); 
            font-family: 'Plus Jakarta Sans', sans-serif;
            letter-spacing: 0.01em;
        }

        h1, h4, .modal-title { 
            font-family: 'Shippori Mincho', serif; 
            font-weight: 700;
        }

        /* Navigációs sáv stílusú fejléc */
        .page-header {
            background: var(--japandi-white);
            border-bottom: 1px solid var(--japandi-border);
            padding: 2rem 0;
            margin-bottom: 3rem;
        }

        /* Letisztult Táblázat konténer */
        .japandi-container {
            background: var(--japandi-white);
            border: 1px solid var(--japandi-border);
            padding: 0;
            overflow: hidden;
        }

        .table { margin: 0; color: var(--japandi-dark) !important; }
        .table thead th { 
            background: #faf9f8;
            color: var(--japandi-accent); 
            border-bottom: 2px solid var(--japandi-accent);
            padding: 1.2rem 1rem;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 2px;
            font-weight: 600;
        }

        .table td { 
            padding: 1.5rem 1rem; 
            border-bottom: 1px solid var(--japandi-border);
            vertical-align: middle;
        }

        .service-name { font-weight: 600; font-size: 1.05rem; margin-bottom: 2px; }
        .service-desc { color: #888; font-size: 0.85rem; font-weight: 300; }
        .price-text { font-weight: 600; color: var(--japandi-dark); }
        .duration-badge {
            background: var(--japandi-bg);
            color: var(--japandi-accent);
            border: 1px solid var(--japandi-border);
            padding: 4px 10px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        /* Gombok */
        .btn-japandi-dark {
            background: var(--japandi-dark);
            color: var(--japandi-white);
            border-radius: 0;
            border: none;
            padding: 10px 25px;
            font-size: 0.8rem;
            letter-spacing: 1px;
            text-transform: uppercase;
            transition: 0.3s;
        }
        .btn-japandi-dark:hover { background: var(--japandi-accent); color: white; }

        .btn-outline-japandi {
            border: 1px solid var(--japandi-border);
            color: var(--japandi-dark);
            border-radius: 0;
            padding: 8px 15px;
            font-size: 0.8rem;
            transition: 0.3s;
        }
        .btn-outline-japandi:hover { border-color: var(--japandi-dark); background: transparent; }

        /* Modál */
        .modal-content {
            background: var(--japandi-bg);
            border: none;
            border-radius: 0;
        }
        .form-control {
            border-radius: 0;
            border: 1px solid var(--japandi-border);
            padding: 0.8rem;
            background: var(--japandi-white);
        }
        .form-control:focus {
            box-shadow: none;
            border-color: var(--japandi-accent);
        }
        .form-label {
            font-size: 0.75rem;
            letter-spacing: 1px;
            font-weight: 600;
            color: var(--japandi-accent);
        }
    </style>
</head>
<body>

<div class="page-header">
    <div class="container d-flex justify-content-between align-items-center">
        <div>
            <h1 class="m-0">Szolgáltatások</h1>
            <p class="small text-muted m-0">Kínálat és árlista kezelése</p>
        </div>
        <div class="d-flex gap-2">
            <a href="./dashboard.php" class="btn btn-outline-japandi"><i class="fas fa-chevron-left me-2"></i>Dashboard</a>
            <button class="btn btn-japandi-dark" data-bs-toggle="modal" data-bs-target="#serviceModal" onclick="prepareAdd()">
                <i class="fas fa-plus me-2"></i>Új hozzáadása
            </button>
        </div>
    </div>
</div>

<div class="container pb-5">
    <?php if(isset($_GET['success'])): ?>
        <div class="alert alert-light border-start border-4 border-success rounded-0 mb-4 py-3">
            <i class="fas fa-check me-2 text-success"></i> A változtatások sikeresen mentve.
        </div>
    <?php endif; ?>

    <div class="japandi-container">
        <table class="table">
            <thead>
                <tr>
                    <th class="ps-4">Szolgáltatás</th>
                    <th>Ár</th>
                    <th>Idő</th>
                    <th class="text-end pe-4">Kezelés</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($services as $s): ?>
                <tr>
                    <td class="ps-4">
                        <div class="service-name"><?= htmlspecialchars($s['name']) ?></div>
                        <div class="service-desc"><?= mb_strimwidth(htmlspecialchars($s['description']), 0, 80, "...") ?></div>
                    </td>
                    <td><div class="price-text"><?= number_format($s['price'], 0, ',', ' ') ?> Ft</div></td>
                    <td><span class="duration-badge"><?= $s['duration'] ?> perc</span></td>
                    <td class="text-end pe-4">
                        <button class="btn btn-outline-japandi me-1" onclick='prepareEdit(<?= json_encode($s) ?>)'>
                            <i class="fas fa-pen-nib"></i>
                        </button>
                        <form action="services.php" method="POST" class="d-inline" onsubmit="return confirm('Biztosan törlöd?')">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?= $s['id'] ?>">
                            <button type="submit" class="btn btn-outline-japandi text-danger border-0"><i class="fas fa-trash-alt"></i></button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="serviceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content p-3 shadow-lg">
            <form action="services.php" method="POST">
                <input type="hidden" name="action" id="modal_action" value="add">
                <input type="hidden" name="id" id="service_id">
                
                <div class="modal-header border-0 pb-0">
                    <h4 class="modal-title" id="modal_title">Új szolgáltatás</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">MEGNEVEZÉS</label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Pl. Japán rituálé" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">LEÍRÁS</label>
                        <textarea name="description" id="description" class="form-control" rows="3" placeholder="Rövid összefoglaló..."></textarea>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label">ÁR (FT)</label>
                            <input type="number" name="price" id="price" class="form-control" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">IDŐ (PERC)</label>
                            <input type="number" name="duration" id="duration" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-japandi-dark w-100 py-3">ADATOK MENTÉSE</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function prepareAdd() {
    document.getElementById('modal_title').innerText = "Új szolgáltatás";
    document.getElementById('modal_action').value = "add";
    document.getElementById('service_id').value = "";
    document.getElementById('name').value = "";
    document.getElementById('description').value = "";
    document.getElementById('price').value = "";
    document.getElementById('duration').value = "";
}

function prepareEdit(s) {
    document.getElementById('modal_title').innerText = "Módosítás";
    document.getElementById('modal_action').value = "update";
    document.getElementById('service_id').value = s.id;
    document.getElementById('name').value = s.name;
    document.getElementById('description').value = s.description;
    document.getElementById('price').value = s.price;
    document.getElementById('duration').value = s.duration;
    new bootstrap.Modal(document.getElementById('serviceModal')).show();
}
</script>
</body>
</html>