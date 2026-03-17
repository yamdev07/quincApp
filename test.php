<?php
// test-smtp.php
require __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

echo "🔍 Test de connexion SMTP Gmail...\n\n";

try {
    // Configuration SMTP
    $mail->SMTPDebug = SMTP::DEBUG_CONNECTION; // Affiche toute la conversation SMTP
    $mail->Debugoutput = function($str, $level) {
        echo "SMTP: $str\n";
    };
    
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'quincaproapp@gmail.com';
    $mail->Password   = 'ocys fpqk kswg ovdg';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;
    $mail->Timeout    = 30;

    // Destinataire
    $mail->setFrom('quincaproapp@gmail.com', 'QuincaPro');
    $mail->addAddress('quincaproapp@gmail.com'); // Envoyer à vous-même

    // Contenu
    $mail->isHTML(true);
    $mail->Subject = 'Test SMTP Gmail';
    $mail->Body    = '<h1>Test</h1><p>Ceci est un test.</p>';

    if($mail->send()) {
        echo "\n✅ SUCCÈS : Email envoyé !\n";
    } else {
        echo "\n❌ ÉCHEC : " . $mail->ErrorInfo . "\n";
    }
    
} catch (Exception $e) {
    echo "\n❌ ERREUR : " . $mail->ErrorInfo . "\n";
}