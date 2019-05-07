<?php

// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
//use PHPMailer;
//use PHPMailer\Exception;
//Load Composer's autoloader
//require 'vendor/autoload.php';
//require_once './PHPMailer/src/PHPMailer.php';
//require_once './PHPMailer/src/SMTP.php';
//require_once './PHPMailer/src/Exception.php';
//require_once 'phpmailer/class.phpmailer.php';
//require_once 'phpmailer/class.smtp.php';


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '/PHPMailer/src/Exception.php';
require '/PHPMailer/src/PHPMailer.php';
require '/PHPMailer/src/SMTP.php';

class My_phpmailer {

    function send_mail($email, $assunto, $conteudo) {
        $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
        try {
            //Server settings
            $mail->SMTPDebug = 0;                                 // Enable verbose debug output
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->CharSet = "utf-8";
            $mail->Host = 'smtpi.kinghost.net';  // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = 'pinaculo@pinaculo.com.br';                 // SMTP username
            $mail->Password = 'QWE123asd';                           // SMTP password
            $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 587;                                    // TCP port to connect to
            //Recipients
            $mail->setFrom('pinaculo@pinaculo.com.br', 'Pináculo Indústria Eletrônica');
            $mail->addAddress($email);     // Add a recipient
            //Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = $assunto;
            $mail->Body = $conteudo;
            $mail->AltBody = $conteudo;

            if ($mail->send()) {
                return TRUE;
            } else {
                return FALSE;
            }
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
//        $mailer = new PHPMailer();
//        $mailer->IsSMTP();
//        $mailer->SMTPDebug = 1;
//        $mailer->CharSet = 'utf-8';
//        $mailer->Port = 587;
//        $mailer->Host = 'smtp.pinaculo.com.br';
//        $mailer->SMTPAuth = true;
//        $mailer->Username = 'pinaculo@pinaculo.com.br';
//        $mailer->Password = 'QWE123asd';
//        $mailer->FromName = 'Pináculo Indústria Eletrônica';
//        $mailer->From = 'pinaculo@pinaculo.com.br';
//        $mailer->AddAddress($email);
//        $mailer->IsHTML(true);
//        $mailer->Subject = $assunto;
//        $mailer->Body = $conteudo;
//        if ($mailer->Send()) {
//            return TRUE;
//        } else {
//            return FALSE;
//        }
    }

}

?>