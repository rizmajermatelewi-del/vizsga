<?php
// includes/functions.php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// A PHPMailer fájlok betöltése - módosítsd az utat, ha máshova tetted!
require __DIR__ . '/../phpmailer/src/Exception.php';
require __DIR__ . '/../phpmailer/src/PHPMailer.php';
require __DIR__ . '/../phpmailer/src/SMTP.php';

function sendZenEmail($to, $subject, $htmlContent) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.yourserver.hu'; 
        $mail->SMTPAuth   = true;
        $mail->Username   = 'info@zenspa.hu';
        $mail->Password   = 'TitkosJelszo123';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        $mail->CharSet    = 'UTF-8';

        $mail->setFrom('info@zenspa.hu', 'ZEN SPA');
        $mail->addAddress($to);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        
        // Japandi Email Sablon
        $mail->Body = "
        <div style='background-color: #fdfcfb; padding: 40px; font-family: sans-serif;'>
            <div style='max-width: 600px; margin: 0 auto; background: #ffffff; border: 1px solid #e2ddd9; padding: 50px;'>
                <h1 style='font-family: serif; letter-spacing: 3px; text-align: center; color: #2d2a26;'>ZEN SPA</h1>
                <div style='height: 1px; background: #e2ddd9; margin: 30px 0;'></div>
                <div style='color: #2d2a26; line-height: 1.6;'>
                    $htmlContent
                </div>
                <div style='margin-top: 40px; font-size: 11px; color: #8e7d6a; text-align: center; letter-spacing: 1px;'>
                    <p>1052 BUDAPEST, ZEN UTCA 1. | +36 1 234 5678</p>
                    <p>WWW.ZENSPA.HU</p>
                </div>
            </div>
        </div>";

        $mail->send();
        return true;
    } catch (Exception $e) {
        // Hiba esetén naplózhatod: error_log($mail->ErrorInfo);
        return false;
    }
}