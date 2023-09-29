<?php

//Declaramos las variables
$operacion =  'altaSolicitud';

date_default_timezone_set("Europe/Madrid");

$jsondata = array();

if ($operacion === 'altaSolicitud') {
    envioMail('chasmaca@gmail.com','20');
   // $autorizador = htmlspecialchars($_POST["solAuth"]);
} else {
    echo 'No es alta de Solicitud';
}


/*Devolvemos el JSON con los datos de la consulta*/
header('Content-type: application/json; charset=utf-8');
echo json_encode($jsondata, JSON_FORCE_OBJECT);


function envioMail($email,$idSolicitud){
	// destinatario
	$para  = $email;
	global $nombre;
	
	$titulo = 'Se ha registrado una nueva solicitud de reprografia.';
	
	$mensaje = '<!DOCTYPE html>';
	$mensaje .= '<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">';
	$mensaje .= '<head>';
	$mensaje .= '<meta charset="utf-8">';
	$mensaje .= '<meta name="viewport" content="width=device-width">';
	$mensaje .= '<meta http-equiv="X-UA-Compatible" content="IE=edge">';
	$mensaje .= '<meta name="x-apple-disable-message-reformatting">';
	$mensaje .= '<meta name="format-detection" content="telephone=no,address=no,email=no,date=no,url=no">';
	$mensaje .= '<meta name="color-scheme" content="light">';
	$mensaje .= '<meta name="supported-color-schemes" content="light">';
	$mensaje .= '<title>REPROGRAF&Iacute;A</title>';
	$mensaje .= '<style>';
	$mensaje .= ':root {';
	$mensaje .= 'color-scheme: light;';
	$mensaje .= 'supported-color-schemes: light;';
	$mensaje .= '}';
	$mensaje .= 'html,';
	$mensaje .= 'body {';
	$mensaje .= 'margin: 0 auto !important;';
	$mensaje .= 'padding: 0 !important;';
	$mensaje .= 'height: 100% !important;';
	$mensaje .= 'width: 100% !important;';
	$mensaje .= '}';
	$mensaje .= '* {';
	$mensaje .= '-ms-text-size-adjust: 100%;';
	$mensaje .= '-webkit-text-size-adjust: 100%;';
	$mensaje .= '}';
	$mensaje .= 'div[style*="margin: 16px 0"] {';
	$mensaje .= 'margin: 0 !important;';
	$mensaje .= '}';
	$mensaje .= '#MessageViewBody, #MessageWebViewDiv{';
	$mensaje .= 'width: 100% !important;';
	$mensaje .= '}';
	$mensaje .= 'table,';
	$mensaje .= 'td {';
	$mensaje .= 'mso-table-lspace: 0pt !important;';
	$mensaje .= 'mso-table-rspace: 0pt !important;';
	$mensaje .= '}';
	$mensaje .= 'table {';
	$mensaje .= 'border-spacing: 0 !important;';
	$mensaje .= 'border-collapse: collapse !important;';
	$mensaje .= 'table-layout: fixed !important;';
	$mensaje .= 'margin: 0 auto !important;';
	$mensaje .= '}';
	$mensaje .= 'img {';
	$mensaje .= '-ms-interpolation-mode:bicubic;';
	$mensaje .= '}';
	$mensaje .= 'a {';
	$mensaje .= 'text-decoration: none;';
	$mensaje .= '}';
	$mensaje .= 'a[x-apple-data-detectors],  /* iOS */';
	$mensaje .= '.unstyle-auto-detected-links a,';
	$mensaje .= '.aBn {';
	$mensaje .= 'border-bottom: 0 !important;';
	$mensaje .= 'cursor: default !important;';
	$mensaje .= 'color: inherit !important;';
	$mensaje .= 'text-decoration: none !important;';
	$mensaje .= 'font-size: inherit !important;';
	$mensaje .= 'font-family: inherit !important;';
	$mensaje .= 'font-weight: inherit !important;';
	$mensaje .= 'line-height: inherit !important;';
	$mensaje .= '}';
	$mensaje .= '.a6S {';
	$mensaje .= 'display: none !important;';
	$mensaje .= 'opacity: 0.01 !important;';
	$mensaje .= '}';
	$mensaje .= '.im {';
	$mensaje .= 'color: inherit !important;';
	$mensaje .= '}';
	$mensaje .= 'img.g-img + div {';
	$mensaje .= 'display: none !important;';
	$mensaje .= '}';
	$mensaje .= '@media only screen and (min-device-width: 320px) and (max-device-width: 374px) {';
	$mensaje .= 'u ~ div .email-container {';
	$mensaje .= 'min-width: 320px !important;';
	$mensaje .= '}';
	$mensaje .= '}';
	$mensaje .= '@media only screen and (min-device-width: 375px) and (max-device-width: 413px) {';
	$mensaje .= 'u ~ div .email-container {';
	$mensaje .= 'min-width: 375px !important;';
	$mensaje .= '}';
	$mensaje .= '}';
	$mensaje .= '@media only screen and (min-device-width: 414px) {';
	$mensaje .= 'u ~ div .email-container {';
	$mensaje .= 'min-width: 414px !important;';
	$mensaje .= '}';
	$mensaje .= '}';
	$mensaje .= '.button-td,';
	$mensaje .= '.button-a {';
	$mensaje .= 'transition: all 100ms ease-in;';
	$mensaje .= '}';
	$mensaje .= '.button-td-primary:hover,';
	$mensaje .= '.button-a-primary:hover {';
	$mensaje .= 'background: #555555 !important;';
	$mensaje .= 'border-color: #555555 !important;';
	$mensaje .= '}';
	$mensaje .= '@media screen and (max-width: 600px) {';
	$mensaje .= '.email-container p {';
	$mensaje .= 'font-size: 17px !important;';
	$mensaje .= '}';
	$mensaje .= '}';
	$mensaje .= '</style>';
	$mensaje .= '</head>';
	$mensaje .= '<body width="100%" style="margin: 0; padding: 0 !important; mso-line-height-rule: exactly; background-color: #222222;">';
	$mensaje .= '<center role="article" aria-roledescription="email" lang="en" style="width: 100%; background-color: #222222;">';
	$mensaje .= '<div style="display: none; font-size: 1px; line-height: 1px; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden; mso-hide: all; font-family: sans-serif;">';
	$mensaje .= '&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;';
	$mensaje .= '</div>';
	$mensaje .= '<div style="max-width: 600px; margin: 0 auto;" class="email-container">';
	$mensaje .= '<table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: auto;">';
	$mensaje .= '<tr>';
	$mensaje .= '<td style="background-color: #ffffff;">';
	$mensaje .= '<img src="https://via.placeholder.com/1200x400" width="500" height="" alt="alt_text" border="0" style="width: 100%; max-width: 600px; height: auto; background: #dddddd; font-family: sans-serif; font-size: 15px; line-height: 15px; color: #555555; margin: auto; display: block;" class="g-img">';
	$mensaje .= '</td>';
	$mensaje .= '</tr>';
	$mensaje .= '<tr>';
	$mensaje .= '<td style="background-color: #ffffff;">';
	$mensaje .= '<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">';
	$mensaje .= '<tr>';
	$mensaje .= '<td style="padding: 20px; font-family: sans-serif; font-size: 15px; line-height: 20px; color: #555555;">';
	$mensaje .= '<h1 style="margin: 0 0 10px 0; font-family: sans-serif; font-size: 25px; line-height: 30px; color: #333333; font-weight: normal;">COMUNICACI&Oacute;N DE REPROGRAF&Iacute;A.</h1>';
	$mensaje .= '<p style="margin: 0;">Este es un correo autogenerado por la aplicaci&oacute;n de Reprograf&iacute;a. </p>';
	$mensaje .= '<p style="margin: 0;font-weight: bold;">POR FAVOR, NO CONTESTE ESTE MAIL.</p>';
	$mensaje .= '</td>';
	$mensaje .= '</tr>';
	$mensaje .= '<tr>';
	$mensaje .= '<td style="padding: 0 20px;">';
	$mensaje .= '<table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" style="margin: auto;">';
	$mensaje .= '<tr>';
	$mensaje .= '<td class="button-td button-td-primary" style="border-radius: 4px; background: #222222;">';
	$mensaje .= '<a class="button-a button-a-primary" href="http://www.elpartedigital.com/formularios/nuevaPassword.html?token=13" style="background: #222222; border: 1px solid #000000; font-family: sans-serif; font-size: 15px; line-height: 15px; text-decoration: none; padding: 13px 17px; color: #ffffff; display: block; border-radius: 4px;">IR A LA APLICACI&Oacute;N</a>';
	$mensaje .= '</td>';
	$mensaje .= '</tr>';
	$mensaje .= '</table>';
	$mensaje .= '</td>';
	$mensaje .= '</tr>';
	$mensaje .= '<tr>';
	$mensaje .= '<td style="padding: 20px; font-family: sans-serif; font-size: 15px; line-height: 20px; color: #555555;">';
	$mensaje .= '<h2 style="margin: 0 0 10px 0; font-family: sans-serif; font-size: 18px; line-height: 22px; color: #333333; font-weight: bold;">Ha recibido una solicitud de Reprografia en su cuenta.</h2>';
	$mensaje .= '<ul style="padding: 0; margin: 0 0 10px 0; list-style-type: disc;">';
	$mensaje .= '<li style="margin:0 0 10px 30px;" class="list-item-first">Acceda a la aplicaci&oacute;n.</li>';
	$mensaje .= '<li style="margin:0 0 10px 30px;">Acceda a su panel de solicitudes.</li>';
	$mensaje .= '<li style="margin: 0 0 0 30px;" class="list-item-last">Gestione La solicitud</li>';
	$mensaje .= '</ul>';
	$mensaje .= '<p style="margin: 0;">Una vez que se gestione la solicitud, el solicitante, recibira un correo con la confirmaci&oacute;n de la operaci&oacute;n.</p>';
	$mensaje .= '</td>';
	$mensaje .= '</tr>';
	$mensaje .= '</table>';
	$mensaje .= '</td>';
	$mensaje .= '</tr>';
	$mensaje .= '<tr>';
	$mensaje .= '<td style="padding: 0 10px 40px 10px; background-color: #ffffff;">';
	$mensaje .= '<p class="MsoNormal">';
	$mensaje .= '<span style="color:#1f497d">';
	$mensaje .= '<img border="0" width="83" height="71" style="width:.8608in;height:.7391in" id="" ';
	$mensaje .= 'src="http://www.elpartedigital.com/assets/images/logo-enea.jpg" ';
	$mensaje .= 'alt="Descripci&oacute;n: ENEA BMP">';
	$mensaje .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
	$mensaje .= '<img border="0" width="78" height="78" style="width:.8086in;height:.8086in" id=""';
	$mensaje .= 'src="http://www.elpartedigital.com/assets/images/cerramos.png" alt="Descripci&oacute;n: juntoscerramoselcirculo">';
	$mensaje .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
	$mensaje .= '<img border="0" width="78" height="78" style="width:2.8086in;height:.8086in" id=""';
	$mensaje .= 'src="http://www.elpartedigital.com/assets/images/papercut.png" alt="Descripci&oacute;n: Papercut">';
	$mensaje .= '<u></u><u></u>';
	$mensaje .= '</span>';
	$mensaje .= '</p>';
	$mensaje .= '<p class="MsoNormal">';
	$mensaje .= '<span style="color:#1f497d">';
	$mensaje .= '<img border="0" width="194" height="43" style="width:2.0173in;height:.4521in" id=""';
	$mensaje .= 'src="http://www.elpartedigital.com/assets/images/minolta.jpg" alt="Konica Minolta">';
	$mensaje .= '<img border="0" width="111" height="28" style="width:1.1565in;height:.2956in" id=""';
	$mensaje .= 'src="http://www.elpartedigital.com/assets/images/tragatoner-300x76.png" alt="Descripci&oacute;n: DF SERVER">';
	$mensaje .= '<u></u><u></u>';
	$mensaje .= '</span>';
	$mensaje .= '</p>';
	$mensaje .= '</td>';
	$mensaje .= '</tr>';
	$mensaje .= '<tr>';
	$mensaje .= '<td style="background-color: #ffffff;">';
	$mensaje .= '<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">';
	$mensaje .= '<tr>';
	$mensaje .= '<td style="padding: 20px; font-family: sans-serif; font-size: 15px; line-height: 20px; color: #555555;">';
	$mensaje .= '<p style="margin: 0;color:#4472c4;font-size:10.0pt">No imprimas este email si no es necesario. Es una buena forma de contribuir a la conservaci&oacute;n de los recursos naturales.</p>';
	$mensaje .= '<p style="margin: 0;color:#4472c4;font-size:10.0pt">Please, do not print this document if it is not needed. Thank you for contributing to the conservation of natural resources.</p>';
	$mensaje .= '<br>';
	$mensaje .= '<p style="margin: 0;">Este mensaje es &uacute;nicamente para la persona indicada. Puede contener informaci&oacute;n confidencial o legalmente privilegiada. Si usted recibe este mensaje por error, rogamos borren inmediatamente todas las copias de este de su sistema, destruya toda clase de copias impresas del mismo y nos lo notifique por correo electr&oacute;nico a la direcci&oacute;n <a href="mailto:info@eneasp.com">info@eneasp.com</a> con una copia de este mensaje. ';
	$mensaje .= 'Usted no debe, directa ni indirectamente, usar, revelar, distribuir, imprimir ni copiar ninguna parte de este mensaje si no es usted el receptor al que va destinado. ';
	$mensaje .= 'PRINT COPIADORAS se reserva, el derecho de controlar todas las comunicaciones por correo electr&oacute;nico a trav&eacute;s de sus redes. ';
	$mensaje .= 'PRINT COPIADORAS no es responsable de la calidad de la transmisi&oacute;n de la informaci&oacute;n contenida en esta comunicaci&oacute;n ni de ninguna clase de demoras en su recepci&oacute;n. ';
	$mensaje .= 'Se ha comprobado la no presencia de virus inform&aacute;ticos en este correo electr&oacute;nico. En el desafortunado caso de infecci&oacute;n PRINT COPIADORAS no acepta ninguna responsabilidad. ';
	$mensaje .= 'Todas las opiniones expresadas en este mensaje son del remitente individual, salvo que el mensaje indique otra cosa y el remitente est&eacute; autorizado a hacerlas.</p>';
	$mensaje .= '<br>';
	$mensaje .= '<p style="margin: 0;">De conformidad con lo establecido en la normativa vigente en protecci&oacute;n de datos de car&aacute;cter personal, y en especial al reglamento UE2016/679 RGPD, y ';
	$mensaje .= 'LOPDGDD 3/2018 los interesados podr&aacute;n ejercitar los derechos de rectificaci&oacute;n, limitaci&oacute;n de tratamientos, supresi&oacute;n, portabilidad y oposici&oacute;n al tratamiento de sus ';
	$mensaje .= 'datos de car&aacute;cter personal as&iacute; como al consentimiento prestado para el tratamiento de los mismos, dirigiendo su petici&oacute;n a la direcci&oacute;n arriba indicada o por email a ';
	$mensaje .= '<a href="mailto:dcuesta@eneasp.com">dcuesta@eneasp.com</a> , y la posibilidad de presentar recurso ante la AGPD en caso de entender que se han vulnerado sus derechos. ';
	$mensaje .= 'M&aacute;s informaci&oacute;n sobre pol&iacute;tica de privacidad de datos de Print Copiadoras en <a href="www.eneasp.com">www.eneasp.com</a>, apartado contacto.</p>';
	$mensaje .= '</td>';
	$mensaje .= '</tr>';
	$mensaje .= '</table>';
	$mensaje .= '</td>';
	$mensaje .= '</tr>';
	$mensaje .= '</table>';
	$mensaje .= '</div>';
	$mensaje .= '</center>';
	$mensaje .= '</body>';
	$mensaje .= '</html>';
	
	try {
		require 'datosCorreo.php';
		$mail->addAddress($para);
		//Definimos el remitente (direcci&oacute;n y, opcionalmente, nombre)
		$mail->SetFrom('chasmaca@gmail.com', 'Jesus');
		$mail->AddReplyTo('noreply@eneasp.com','El de la réplica');
		
		$mail->AddAddress('jmadrazo@viewnext.com', 'Yo mismo');
		
		$mail->Subject = 'Esto es un correo de prueba';
		$mail->Body    = $mensaje;
		$mail->AltBody = 'This is a plain-text message body';
		//Enviamos el correo
		if(!$mail->Send()) {
		echo "Error: " . $mail->ErrorInfo;
		} else {
		echo "Enviado!";
		}

		// $mail->isHTML(true);
		// $mail->Subject = $titulo;
		// $mail->Body    = $mensaje;
        // $mail->send();
        // $jsondata["success"] = true;
        // $jsondata["message"] = 'Correo envido al validador';
    } catch (Exception $e) {
		echo 'El mail no pudo ser enviado.' . $mail->ErrorInfo;
        $jsondata["success"] = false;
        $jsondata["message"] = 'El mail no pudo ser enviado.' . $mail->ErrorInfo;
    }
}


