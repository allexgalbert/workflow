<?php

//отправка почты. библиотека PHPMailer

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

function sendEmail($email, $message) {

  //Passing `true` enables exceptions
  $mail = new PHPMailer(true);

  try {

    //Enable verbose debug output
    $mail->SMTPDebug = 4;

    //Set mailer to use SMTP
    $mail->isSMTP();

    //Specify main and backup SMTP servers
    $mail->Host = 'mail.domain.com';

    //Enable SMTP authentication
    $mail->SMTPAuth = true;

    //SMTP username
    $mail->Username = 'Username';

    //SMTP password
    $mail->Password = 'Password';

    //Enable TLS encryption, `ssl` also accepted
    $mail->SMTPSecure = 'tls';

    //TCP port to connect to
    $mail->Port = 587;

    $mail->CharSet = 'UTF-8';

    $mail->setFrom('robot@domain.com');
    $mail->addAddress($email);
    $mail->addReplyTo('robot@domain.com');
    $mail->addCC('robot@domain.com');
    $mail->addBCC('robot@domain.com');

    //Set email format to HTML
    $mail->isHTML(true);

    $mail->Subject = 'Проверка(smtp) ' . date('H:i:s');
    $mail->Body = $message;
    $mail->AltBody = $message;

    $mail->send();
  } catch (Exception $e) {
    echo $mail->ErrorInfo;
  }
}

sendEmail('email@email.com', 'text');

//Настрока PHPMailer

//Письма отправляются, проверка сертификата отключена
$SMTPOptions = [
  'ssl' => [
    'verify_peer' => false,
    'verify_peer_name' => false,
    'allow_self_signed' => true,
  ]
];

//Письма отправляются, проверка сертификата включена
$SMTPOptions = [
  'ssl' => [
    'verify_peer' => true,
    'verify_peer_name' => false,
  ]
];

//Письма отправляются. Самый правильный вариант
$SMTPOptions = [
  'ssl' => [
    'verify_peer' => true,
    'peer_name' => 'smtp.domain.com',
    'verify_peer_name' => true,
  ]
];