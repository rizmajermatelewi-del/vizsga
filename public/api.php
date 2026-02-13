<?php
session_start();
require_once "../config/database.php";


error_reporting(E_ALL);
ini_set('display_errors', 0); 
ob_start();


require __DIR__ . '/../vendor/PHPMailer-master/src/Exception.php';
require __DIR__ . '/../vendor/PHPMailer-master/src/PHPMailer.php';
require __DIR__ . '/../vendor/PHPMailer-master/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


function sendResponse($data, $code = 200) {
    ob_clean();
    http_response_code($code);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}


function sendZEMail($to, $subject, $body) {
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

        $mail->setFrom('abmasszazsinfo@gmail.com', 'AB MASSZÁZS');
        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->Subject = $subject;


        $mail->Body = "
        <div style='background-color: #fdfcf9; padding: 40px; font-family: \"Segoe UI\", Tahoma, Geneva, Verdana, sans-serif; color: #444;'>
            <div style='max-width: 600px; margin: 0 auto; background-color: #ffffff; border: 1px solid #e8e4de; border-radius: 2px; box-shadow: 0 2px 10px rgba(0,0,0,0.02);'>
                <div style='padding: 40px; text-align: center;'>
                    <h1 style='margin: 0; font-family: serif; letter-spacing: 5px; color: #1a1a1a; font-size: 28px; font-weight: normal;'>AB MASSZÁZS</h1>
                    <div style='width: 30px; height: 1px; background-color: #c5b358; margin: 25px auto;'></div>
                </div>
                <div style='padding: 0 50px 50px 50px; line-height: 1.8; font-size: 16px; text-align: left;'>
                    $body
                </div>
                <div style='padding: 30px; background-color: #faf9f6; text-align: center; font-size: 12px; color: #999; border-top: 1px solid #f0f0f0;'>
                    <p style='letter-spacing: 1px;'>EGYENSÚLY • NYUGALOM • HARMÓNIA</p>
                    <p>© 2026 AB MASSZÁZS | <a href='#' style='color: #999; text-decoration: none;'>abmasszazs.hu</a></p>
                </div>
            </div>
        </div>";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Email küldési hiba: " . $mail->ErrorInfo);
        return false;
    }
}

$request = $_GET['request'] ?? '';
$method = $_SERVER['REQUEST_METHOD'];

switch ($request) {
    case 'login': handleLogin($method, $pdo); break;
    case 'register': handleRegistration($method, $pdo); break;
    case 'vouchers': handleVouchers($method, $pdo); break;
    case 'bookings': handleBookings($method, $pdo); break;
    case 'messages': handleMessages($method, $pdo); break;
    case 'reviews': handleReviews($method, $pdo); break;
    case 'update_profile': handleUpdateProfile($method, $pdo); break;
    default: sendResponse(["error" => "Érvénytelen kérés"], 400);
}

function handleLogin($method, $pdo) {
    if ($method === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        $email = trim($data['email'] ?? '');
        $password = $data['password'] ?? '';
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'] ?? 'user';
            sendResponse(["success" => "Üdvözöljük!", "redirect" => ($user['role'] === 'admin') ? 'admin/dashboard.php' : 'user.php']);
        } else {
            sendResponse(["error" => "Hibás adatok!"], 401);
        }
    }
}

function handleRegistration($method, $pdo) {
    if ($method === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        

        $hashed = password_hash($data['password'], PASSWORD_DEFAULT);
        

        $ins = $pdo->prepare("INSERT INTO users (username, email, tel, password, role) VALUES (?, ?, ?, ?, 'user')");
        
        if($ins->execute([$data['username'], $data['email'], $data['tel'], $hashed])) {
            

            $subject = "Üdvözöljük az AB MASSZÁZS világában!";
            $body = "
                <p>Kedves <strong>{$data['username']}</strong>!</p>
                <p>Köszönjük, hogy csatlakozott az AB MASSZÁZS közösségéhez. Fiókját sikeresen létrehoztuk.</p>
                <p>Mostantól bármikor bejelentkezhet, hogy lefoglalja következő én-időjét vagy kezelje korábbi foglalásait.</p>
                <div style='text-align: center; margin-top: 30px;'>
                    <a href='http://localhost/public/login.php' style='background-color: #1a1a1a; color: #fff; padding: 15px 30px; text-decoration: none; display: inline-block; border-radius: 2px; letter-spacing: 2px; font-size: 14px;'>BEJELENTKEZÉS</a>
                </div>
                <p style='margin-top: 40px; font-size: 13px; color: #888;'>Reméljük, hamarosan találkozunk stúdiónkban!</p>
            ";

            sendZEMail($data['email'], $subject, $body);


            sendResponse(["success" => "Sikeres regisztráció! Üdvözlő e-mail elküldve."]);
        }
    }
}

function handleVouchers($method, $pdo) {
    if ($method === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        $code = "AB-" . strtoupper(substr(md5(uniqid()), 0, 8));
        $expiry = date('Y-m-d', strtotime('+1 year'));

        $sql = "INSERT INTO vouchers (user_id, code, recipient_name, amount, expiry_date, status, buyer_email, buyer_tel) VALUES (?, ?, ?, ?, ?, 'active', ?, ?)";
        $stmt = $pdo->prepare($sql);

        if($stmt->execute([$_SESSION['user_id'] ?? null, $code, $data['recipient'], $data['amount'], $expiry, $data['email'], $data['tel'] ?? null])) {
            

            $subject = "Az Ön ajándékutalványa - AB MASSZÁZS";
            $formattedAmount = number_format($data['amount'], 0, '', ' ') . " Ft";
            
            $body = "
                <p>Köszönjük, hogy az AB MASSZÁZS ajándékutalványát választotta!</p>
                <div style='border: 1px solid #e8e4de; padding: 40px; text-align: center; margin: 30px 0;'>
                    <p style='text-transform: uppercase; letter-spacing: 2px; font-size: 12px; color: #999; margin-bottom: 10px;'>Digitális Ajándékutalvány</p>
                    <h3 style='font-size: 28px; margin: 10px 0; color: #1a1a1a;'>$code</h3>
                    <p style='font-size: 18px; color: #c5b358; margin: 10px 0;'>Érték: $formattedAmount</p>
                    <p style='font-size: 12px; color: #999; margin-top: 20px;'>Érvényes: $expiry</p>
                </div>
                <p>Az utalvány a kód bemutatásával használható fel bármely szolgáltatásunkra.</p>
            ";

            sendZEMail($data['email'], $subject, $body);
            sendResponse(["success" => "Voucher mentve!", "code" => $code]);
        }
    }
}

function handleBookings($method, $pdo) {
    if ($method === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        try {
            $stmt = $pdo->prepare("INSERT INTO bookings (service_id, customer_name, email, tel, booking_date, booking_time, status) VALUES (?, ?, ?, ?, ?, ?, 'pending')");
            
            if($stmt->execute([$data['service_id'], $data['customer_name'], $data['email'], $data['tel'], $data['booking_date'], $data['booking_time']])) {
                

                $subject = "Időpont visszaigazolva - AB MASSZÁZS";
                $body = "
                    <h2 style='font-weight: normal; color: #1a1a1a;'>Várjuk szeretettel!</h2>
                    <p>Kedves <strong>{$data['customer_name']}</strong>!</p>
                    <p>Sikeresen rögzítettük foglalását stúdiónkba. Íme a részletek:</p>
                    <div style='background-color: #faf9f6; border-left: 2px solid #c5b358; padding: 20px; margin: 25px 0;'>
                        <p style='margin: 5px 0;'><strong>Időpont:</strong> {$data['booking_date']} | {$data['booking_time']}</p>
                        <p style='margin: 5px 0;'><strong>Helyszín:</strong> 2365 Inárcs, Május 1 utca 12.</p>
                    </div>
                    <p style='font-size: 13px; color: #888;'>Amennyiben módosítani szeretné az időpontot, kérjük, jelezze legkésőbb 24 órával a kezelés előtt.</p>
                ";

                sendZEMail($data['email'], $subject, $body);
                sendResponse(["success" => "Sikeres foglalás!"]);
            }
        } catch (Exception $e) { 
            sendResponse(["error" => "Hiba történt a foglaláskor!"], 500); 
        }
    }
}

function handleMessages($method, $pdo) {
    if ($method === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        

        $stmt = $pdo->prepare("INSERT INTO messages (name, email, tel, message, created_at) VALUES (?, ?, ?, ?, NOW())");
        
        if($stmt->execute([$data['name'], $data['email'], $data['tel'], $data['message']])) {
            

            $subject = "Köszönjük megkeresését - AB MASSZÁZS";
            $body = "
                <p>Kedves <strong>{$data['name']}</strong>!</p>
                <p>Megkaptuk üzenetét, és köszönjük, hogy bizalmával hozzánk fordult.</p>
                <p>Munkatársunk hamarosan átnézi kérését, és a megadott elérhetőségei egyikén felveszi Önnel a kapcsolatot.</p>
                <div style='background-color: #faf9f6; padding: 20px; border-radius: 2px; margin: 25px 0; font-style: italic; color: #666;'>
                    \"Az egyensúly nem valami, amit találsz, hanem valami, amit megteremtesz.\"\r\n                </div>
                <p>Türelmét és megértését köszönjük!</p>
            ";

            sendZEMail($data['email'], $subject, $body);
            sendResponse(["success" => "Üzenetét megkaptuk, hamarosan válaszolunk!"]);
        }
    }
}

function handleReviews($method, $pdo) {
    if ($method === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        

        $stmt = $pdo->prepare("INSERT INTO reviews (user_name, service_name, rating, comment, created_at) VALUES (?, ?, ?, ?, NOW())");
        
        if($stmt->execute([$data['user_name'], $data['service_name'], $data['rating'], $data['comment']])) {
            

            $subject = "Köszönjük az értékelését! - AB MASSZÁZS";
            

            $stars = str_repeat('★', $data['rating']) . str_repeat('☆', 5 - $data['rating']);
            
            $body = "
                <p>Kedves <strong>{$data['user_name']}</strong>!</p>
                <p>Hálásak vagyunk, hogy megosztotta velünk és közösségünkkel a <strong>{$data['service_name']}</strong> kezeléssel kapcsolatos tapasztalatait.</p>
                
                <div style='background-color: #faf9f6; padding: 25px; border-radius: 2px; margin: 25px 0; text-align: center;'>
                    <p style='color: #c5b358; font-size: 20px; margin: 0;'>$stars</p>
                    <p style='font-style: italic; color: #666; margin-top: 10px;'>\"{$data['comment']}\"</p>
                </div>
                
                <p>Visszajelzése segít nekünk abban, hogy továbbra is a legmagasabb szintű nyugalmat és harmóniát biztosíthassuk vendégeinknek.</p>
                <p>Várjuk szeretettel legközelebb is!</p>
            ";


            if(isset($data['email'])) {
                sendZEMail($data['email'], $subject, $body);
            }
            
            sendResponse(["success" => "Köszönjük az értékelést!"]);
        }
    }
}

function handleUpdateProfile($method, $pdo) {
    if ($method === 'POST') {
        if (!isset($_SESSION['user_id'])) sendResponse(["error" => "Nincs bejelentkezve!"], 403);
        
        $data = json_decode(file_get_contents('php://input'), true);
        $user_id = $_SESSION['user_id'];
        
        try {

            $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ?, tel = ? WHERE id = ?");
            $stmt->execute([$data['username'], $data['email'], $data['tel'], $user_id]);
            
            $_SESSION['username'] = $data['username'];
            $passwordChanged = false;

            if (!empty($data['password'])) {
                $hashed = password_hash($data['password'], PASSWORD_DEFAULT);
                $pdo->prepare("UPDATE users SET password = ? WHERE id = ?")->execute([$hashed, $user_id]);
                $passwordChanged = true;
            }


            $subject = "Profilja megváltozott - AB MASSZÁZS";
            $body = "
                <p>Kedves <strong>{$data['username']}</strong>!</p>
                <p>Ezúton értesítjük, hogy az <strong>AB MASSZÁZS</strong> rendszerében tárolt profiladatai sikeresen frissültek.</p>
                
                <div style='background-color: #faf9f6; padding: 20px; border-radius: 2px; margin: 25px 0; font-size: 14px; color: #666;'>
                    <strong>Végrehajtott módosítások:</strong><br>
                    • Alapadatok (név, email vagy telefon) frissítése<br>
                    " . ($passwordChanged ? "• Jelszó sikeresen megváltoztatva" : "") . "
                </div>
                
                <p style='font-size: 13px;'>Amennyiben nem Ön hajtotta végre ezeket a módosításokat, kérjük, azonnal vegye fel velünk a kapcsolatot válaszlevélben!</p>
                <p>Vigyázunk adataira és nyugalmára.</p>
            ";

            sendZEMail($data['email'], $subject, $body);

            sendResponse(["success" => "Profil sikeresen frissítve!"]);
        } catch (Exception $e) { 
            sendResponse(["error" => "Hiba a frissítés során!"], 500); 
        }
    }
}