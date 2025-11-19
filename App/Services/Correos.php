<?php

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Correos{

    public function __construct() {}

    public static function enviarCorreoRegistro($destinatario, $nombre) {
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

            $plantillaHTML = file_get_contents('../Views/correoRegistro.html');
            $plantillaHTML = str_replace('{{nombre}}', $nombre, $plantillaHTML);
            $plantillaHTML = str_replace('{{link_login}}', 'http://localhost:8080/index.php?page=login', $plantillaHTML);

            $mail->Body = $plantillaHTML;
            
            $mail->send();
        } catch (Exception $e) {
            error_log($e->getMessage());
        }
    }   
}