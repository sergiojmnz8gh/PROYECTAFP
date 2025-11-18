<?php

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Correos{

    public function __construct() {}

    public static function enviarCorreoRegistro($destinatario) {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = getenv('MAIL_HOST') ?: 'mailhog';
            $mail->Port = getenv('MAIL_PORT') ?: 1025;
            $mail->SMTPAuth = false;
            $mail->SMTPDebug = 2;

            $mail->setFrom('admin@proyectafp.com', 'ProyectaFP');
            $mail->addAddress($destinatario ?? 'test@ejemplo.com');

            $mail->isHTML(true);
            $mail->Subject = 'Bienvenido';
            $mail->Body    = 'Usted se ha registrado correctamente en ProyectaFP.';

            $mail->send();
            echo "✅ Correo enviado correctamente. Ver MailHog en <a href='http://localhost:8025'>localhost:8025</a>";
        } catch (Exception $e) {
            echo "❌ Error al enviar el correo: {$mail->ErrorInfo}";
        }
    }   
}