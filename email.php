<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'Exception.php';
require 'PHPMailer.php';
require 'SMTP.php';

function enviarFacturaPorCorreo($email, $pdfRuta) {
    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'anacasiano.kp@gmail.com';
    $mail->Password = 'pqim ijvb lfik goan';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('anacasiano.kp@gmail.com', 'Tu Nombre');
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = 'Factura de Compra';
    $mail->Body = 'Adjunto encontrarás tu factura de compra.';

    $mail->addAttachment($pdfRuta);

    if (!$mail->send()) {
        echo 'Error al enviar el correo: ' . $mail->ErrorInfo;
    } else {
        echo 'Correo enviado correctamente';
    }
}
?>