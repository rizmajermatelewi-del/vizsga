<?php
require_once "../config/database.php";
$services = $pdo->query("SELECT * FROM services ORDER BY name ASC")->fetchAll();
$selected_service = $_GET['service'] ?? '';
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>ZEN SPA | Időpontfoglalás</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600&family=Shippori+Mincho:wght@500;700&display=swap" rel="stylesheet">
    <style>
        :root { --j-bg: #f4f1ee; --j-dark: #2d2a26; --j-accent: #8e7d6a; --j-border: #e2ddd9; }
        body { background: var(--j-bg); color: var(--j-dark); font-family: 'Plus Jakarta Sans', sans-serif; padding-top: 50px; }
        .booking-container { background: white; border: 1px solid var(--j-border); padding: 3rem; max-width: 900px; margin: auto; }
        h2 { font-family: 'Shippori Mincho', serif; margin-bottom: 2rem; text-align: center; }
        
        .form-label { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; font-weight: 600; color: var(--j-accent); }
        .form-control, .form-select { border-radius: 0; border: 1px solid var(--j-border); padding: 12px; }
        
        .time-slot-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(100px, 1fr)); gap: 10px; margin-top: 15px; }
        .time-slot { border: 1px solid var(--j-border); padding: 10px; text-align: center; cursor: pointer; transition: 0.3s; font-size: 0.9rem; }
        .time-slot:hover { background: var(--j-bg); }
        .time-slot.selected { background: var(--j-dark); color: white; border-color: var(--j-dark); }
        .time-slot.disabled { opacity: 0.3; cursor: not-allowed; background: #eee; }

        .btn-zen { background: var(--j-dark); color: white; border-radius: 0; padding: 15px 30px; width: 100%; text-transform: uppercase; letter-spacing: 2px; border: none; margin-top: 30px; }
    </style>
</head>
<body>

<div class="container pb-5">
    <div class="text-center mb-4">
        <a href="../index.php" class="text-decoration-none text-muted small uppercase">← Vissza a főoldalra</a>
    </div>
    
    <div class="booking-container shadow-sm">
        <h2>Időpont Foglalása</h2>
        
        <form action="process_booking.php" method="POST" id="bookingForm">
            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label">Teljes név</label>
                    <input type="text" name="customer_name" class="form-control" placeholder="Minta János" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">E-mail cím</label>
                    <input type="email" name="email" class="form-control" placeholder="janos@pelda.hu" required>
                </div>

                <div class="col-12">
                    <label class="form-label">Választott kezelés</label>
                    <select name="service_id" id="serviceSelect" class="form-select" required>
                        <option value="">Válasszon szolgáltatást...</option>
                        <?php foreach($services as $s): ?>
                            <option value="<?= $s['id'] ?>" <?= $selected_service == $s['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($s['name']) ?> (<?= number_format($s['price'], 0, ',', ' ') ?> Ft)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Dátum</label>
                    <input type="date" name="booking_date" id="dateInput" class="form-control" min="<?= date('Y-m-d') ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Szabad időpontok</label>
                    <input type="hidden" name="booking_time" id="selectedTime" required>
                    <div id="timeSlots" class="time-slot-grid">
                        <p class="small text-muted">Válasszon dátumot a szabad időpontokhoz...</p>
                    </div>
                </div>

                <div class="col-12">
                    <button type="submit" class="btn-zen">Foglalás véglegesítése</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    const dateInput = document.getElementById('dateInput');
    const timeSlotsDiv = document.getElementById('timeSlots');
    const selectedTimeInput = document.getElementById('selectedTime');

    dateInput.addEventListener('change', function() {
        const date = this.value;
        if(!date) return;

        timeSlotsDiv.innerHTML = '<p class="small">Betöltés...</p>';

        // Itt hívjuk meg a háttérben a szabad helyek ellenőrzését
        fetch(`check_slots.php?date=${date}`)
            .then(res => res.json())
            .then(slots => {
                timeSlotsDiv.innerHTML = '';
                if(slots.length === 0) {
                    timeSlotsDiv.innerHTML = '<p class="text-danger small">Nincs szabad időpont erre a napra.</p>';
                    return;
                }
                slots.forEach(slot => {
                    const div = document.createElement('div');
                    div.className = `time-slot ${slot.available ? '' : 'disabled'}`;
                    div.textContent = slot.time;
                    if(slot.available) {
                        div.onclick = function() {
                            document.querySelectorAll('.time-slot').forEach(s => s.classList.remove('selected'));
                            this.classList.add('selected');
                            selectedTimeInput.value = slot.time;
                        };
                    }
                    timeSlotsDiv.appendChild(div);
                });
            });
    });
</script>
</body>
</html>