<?php
// $mail = new PHPMailer;

// $mail->SMTPDebug = 2;
// $mail->IsSMTP();
// $mail->Host = "smtp.dominioabsoluto.net";
// $mail->SMTPAuth = true;

// $mail->SMTPSecure = "tls";
// $mail->Username = "info@elpartedigital.com";
// $mail->Password = "Elparte2017!";
// $mail->Port = "587";
/**
 * This example shows making an SMTP connection without using authentication.
*/
//Import the PHPMailer class into the global namespace
use PHPMailer;
//SMTP needs accurate times, and the PHP time zone MUST be set
//This should be done in your php.ini, but this is how to do it if you don't have access to that
date_default_timezone_set('Etc/UTC');
require 'phpmailer/PHPMailerAutoload.php';
//Create a new PHPMailer instance
$mail = new PHPMailer;
//Tell PHPMailer to use SMTP
$mail->isSMTP();
//Enable SMTP debugging
// 0 = off (for production use)
// 1 = client messages
// 2 = client and server messages
//$mail->SMTPDebug = 2;
//Set the hostname of the mail server
$mail->Host = 'smtp.dominioabsoluto.net';
//$mail->Host = 'smtp.dominioabsoluto.net';
//Set the SMTP port number - likely to be 25, 465 or 587
$mail->Port = 587;
//Whether to use SMTP authentication
$mail->SMTPAuth = true;
$mail->Username = 'info@eneasp.com';
$mail->Password = 'AppsEneasp15!';
//Username to use for SMTP authentication
//$mail->Username = 'info@elpartedigital.com';
//Password to use for SMTP authentication
//$mail->Password = 'Elparte2017!';
//We don't need to set this as it's the default value
//$mail->SMTPSecure="tls";
//$mail->SMTPAutoTLS = true;
//$mail->SMTPAuth = false;
//Set who the message is to be sent from
$mail->setFrom('info@elpartedigital.com', 'Reprografia');
//Set an alternative reply-to address
$mail->addReplyTo('info@elpartedigital.com', 'Reprografia');
//Set who the message is to be sent to

$mail->addAddress('chasmaca@gmail.com', 'Joe User');     // Add a recipient
$mail->WordWrap = 50;                                 // Set word wrap to 50 characters
$mail->isHTML(true);                                  // Set email format to HTML
$mail->Subject = 'Here is the subject';
$mail->Body    = 'This is the HTML message body <b>in bold!</b>';
$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
if(!$mail->send()) {
	echo 'Message could not be sent.';
	echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
	echo 'Message has been sent';
}

?>