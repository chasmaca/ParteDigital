<?php
require('phpmailer/class.phpmailer.php');
require("phpmailer/class.smtp.php");

date_default_timezone_set('Etc/UTC');
$mail = new PHPMailer();
$mail->isSMTP();
$mail->SMTPDebug = 2;

$mail->Host = 'smtp.dominioabsoluto.net';
$mail->Port = 587;
$mail->SMTPAuth = true;
$mail->Username = 'info@elpartedigital.com';
$mail->Password = 'Viva2019!';
$mail->setFrom('info@elpartedigital.com', 'Reprografia');
$mail->addReplyTo('noreply@elpartedigital.com', 'Reprografia');
?>
