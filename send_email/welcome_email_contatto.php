<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require '../../../vendor/phpmailer/phpmailer/src/Exception.php';
require '../../../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '../../../vendor/phpmailer/phpmailer/src/SMTP.php';

include_once '../config/apikey.php';

// get posted data
$data = json_decode(file_get_contents("php://input"));
if ($data->api_key != ApiKey::$apiKey) {
	http_response_code(403);  
    echo json_encode(
        array("message" => "Chiave sbagliata")
    ); 
    return;
}

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);
// $mail->SMTPDebug = SMTP::DEBUG_SERVER;  
$mail->isSMTP();
$mail->Host = 'mail.italianear.it';
$mail->SMTPAuth = true;
$mail->Username = ApiKey::$usermail;
$mail->Password = ApiKey::$mailpwd;
$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;    
// $mail->SMTPSecure = 'ssl';
$mail->Port = 465;
$mail->CharSet = "UTF-8";


$mail->setFrom('info@italianear.it', 'Info ItaliaNear');
$mail->addAddress($data->recipient, '');
// $mail->addAddress('picobellone@gmail.com', '');
// $mail->addAddress('u.ricci@gmail.com', '');
$mail->isHTML(true);      
$mail->Subject = 'Benvenuto da ItaliaNear';
$mail->Body = "<p>Buongiorno $data->nickname,</p>
<p>Grazie per la tua registrazione! 
<p>La tua presenza fra i contatti di $data->luogo è un contributo fondamentale per la creazione della rete ItaliaNear.
<p>Nelle prossime ore verificheremo il corretto funzionamento della tua SCHEDA CONTATTO che d'ora in poi sarà visibile a tutti gli italiani che useranno la nostra APP e così quelli più vicini a te potranno facilmente raggiungerti.
<p>Assicurati perciò di mantenere sempre aggiornati i tuoi riferimenti:
<p>Nome contatto:	$data->nomecontatto</td></tr>
<table>
<tr><td>Ruolo:</td><td><b>	$data->ruolo</b></td></tr>
<tr><td>Note:</td><td><b>$data->note</b></td></tr>
<tr><td>Lavora presso:</td><td><b><b>$data->tipoattivita</b></td></tr>
<tr><td></td><td><b>$data->nomeattivita</b>	</td></tr>
<tr><td></td><td><b>$data->indirizzo</b></td></tr>
<tr><td></td><td><b>$data->luogo</b></td></tr>
<tr><td></td><td><b>$data->nazione</b></td></tr>
<tr><td>Telefono:</td><td><b>$data->prefix <b>$data->telefono</b></td></tr>
<tr><td>e-mail:	</td><td><b>$data->email</b></td></tr>
<tr><td>Sito web:</td><td><b>$data->sitoweb</b></td></tr>
</table>
<p>Ti ricordiamo inoltre di seguirci sui social e nel caso ti interessasse la pubblicazione gratuita sui nostri canali di un post con una tua foto e una sintetica descrizione della tua attività, ti invitiamo a contattarci.</p>
<p>Ecco i nostri riferimenti:</p>
<p>e-mail: info@italianear.it<br/>
Web site: https://italianear.it/</p>
<p><b>
Se non sei stato tu a registrarti per favore segnalalo subito a  info@italianear.it.<br/>
If you didn't register, please report it immediately to info@italianear.it</p>
<p>Un cordiale saluto</p
<p>Il team ItaliaNear</p>

<p><a href=\"https://www.youtube.com/channel/UCZwgxMhRcQUJwufKFy8bjlA\"> YouTube</a><br/>	
<a href=\"https://www.facebook.com/ItaliaNear.it\"> Facebook</a><br/>
<a href=\"https://twitter.com/ItaliaNear\"> Twitter</a><br/>
<a href=\"https://www.instagram.com/italianear\"> Instagram</a><br/>
<a href=\"https://www.linkedin.com/company/italianear/\"> LinkedIn</a><br/>
<a href=\"https://open.spotify.com/show/53n3PWA7xlFrVEOpAY5DU4\"> Spotify</a></p>	";
// echo json_encode(array("message" => "OK.")); 
try {
    if(!$mail->send()) {
        echo json_encode(
            array("message" => "Errore durante l\'invio dell\'email: $mail->ErrorInfo")
        );
    } else {
        echo json_encode(
            array("message" => "Email inviata correttamente")
        );        
    }
}
catch (Exception $e) {
    echo json_encode(
        array("message" => "Message could not be sent. Mailer Error: {$mail->ErrorInfo}")
    );   
}

?>