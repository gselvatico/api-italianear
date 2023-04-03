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
$inurl = dirname(__DIR__).'/italianear_Hi-res_Icon_512.png';
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
$mail->Username = 'italiare';
$mail->Password = 'Italo2021';
$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;    
// $mail->SMTPSecure = 'ssl';
$mail->Port = 465;
$mail->CharSet = "UTF-8";
// $mail->addEmbeddedImage(dirname(__DIR__).'/italianear_Hi-res_Icon_512.png',1000);
$mail->setFrom('info@italianear.it', 'Info ItaliaNear');
$mail->addAddress($data->recipient, '');
// $mail->addAddress('picobellone@gmail.com', '');
// $mail->addAddress('u.ricci@gmail.com', '');
$mail->isHTML(true);      
$mail->Subject = 'Benvenuto da ItaliaNear';
$mail->Body = "
<p>Grazie per la tua registrazione! </p>
<p>La tua presenza fra i contatti di $data->luogo è un contributo fondamentale per la creazione della rete ItaliaNear.</p>
<p>Nelle prossime ore verificheremo il corretto funzionamento della tua SCHEDA CONTATTO che d'ora in poi sarà visibile a tutti gli italiani che useranno la nostra APP e così quelli più vicini a te potranno facilmente raggiungerti.</p>
<p>Assicurati perciò di mantenere sempre aggiornati i tuoi riferimenti:</p>
<table>
<tr><td>Nome contatto:</td><td><b>$data->nomecontatto</b></td></tr>
<tr><td>ruolo:</td><td><b>$data->ruolo</b></td></tr>
<tr><td>note:</td><td><b>$data->note</b></td></tr>
<tr><td>lavora presso:</td><td><b>$data->tipoattivita</b></td></tr>
<tr><td></td><td><b>$data->nomeattivita</b></td></tr>
<tr><td></td><td><b>$data->indirizzo</b></td></tr>
<tr><td></td><td><b>$data->luogo</b></td></tr>
<tr><td></td><td><b>$data->nazione</b></td></tr>
<tr><td>telefono:</td><td><b>$data->prefix $data->telefono</b></td></tr>
<tr><td>e-mail:</td><td><b>$data->email</b></td></tr>
<tr><td>sito web:</td><td><b>$data->sitoweb</b></td></tr>
</table>
<p>Ti ricordiamo inoltre di seguirci sui social e nel caso ti interessasse la pubblicazione gratuita sui nostri canali di un post con una tua foto e una sintetica descrizione della tua attività, ti invitiamo a contattarci.</p>

<p>Ecco i nostri riferimenti:</p>

<p>e-mail: info@italianear.it</p>
<p>Web site: https://italianear.it/</p>

<p>Un cordiale saluto</p>
<img src=\"https://italianear.it/images/italianear_50x50.png\" />
<p>Il team ItaliaNear</p>
<p>
<a href=\"https://www.youtube.com/channel/UCZwgxMhRcQUJwufKFy8bjlA\">YouTube</a><br/>
<a href=\"https://www.facebook.com/ItaliaNear.it\">Facebook</a><br/>
<a href=\"https://twitter.com/ItaliaNear\">Twitter</a><br/>
<a href=\"https://www.instagram.com/italianear\">Instagram</a><br/>
<a href=\"https://www.linkedin.com/company/italianear/\">LinkedIn</a>	<br/>
<a href=\"https://open.spotify.com/show/53n3PWA7xlFrVEOpAY5DU4\">Spotify</a><br/>
</p>
";
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