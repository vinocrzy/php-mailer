<?php


use PHPMailer\PHPMailer\PHPMailer;

require 'vendor/autoload.php';
require './smtp.php';


function sendMail($to, $subject, $message,  $files, $userName, $userMail)
{
    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->IsHTML(true);
    $mail->Host = gethostname();
    $mail->Username = $GLOBALS['Username'];
    $mail->Password = $GLOBALS['Password'];
    $mail->setFrom($GLOBALS['from']);
    $mail->addAddress("wd2@voqzi.com");
    $mail->addAddress($to);
    $mail->Subject = $subject;
    $mail->Body    = $message;

    if ($files != null) {
        $mail->addAttachment($files);
    }
    if ($userMail != null && $userName != null) {
        $mail->AddReplyTo($userMail, $userName);
    }

    if ($mail->send()) {
        return true;
    } else {
        return false;
    }
}
