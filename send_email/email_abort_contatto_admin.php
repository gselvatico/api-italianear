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
$mail->isSMTP();
$mail->Host = 'italianear.it';

$mail->SMTPAuth = true;
$mail->Username = ApiKey::$usermail;
$mail->Password = ApiKey::$mailpwd;
$mail->SMTPSecure = 'tsl';
$mail->Port = 587;
$mail->CharSet = "UTF-8";


$mail->setFrom('info@italianear.it', 'ItaliaNear');
//$mail->addAddress('italianear@gmail.com');
$mail->addAddress('picobellone@gmail.com');
$mail->isHTML(true);      
$mail->Subject = 'Annullamento scheda contatto di ItaliaNear';
$mail->Body = "Il contatto $data->nickname,</p>
<p>con email: $data->recipient</p>
<p> ha abortito la registrazione</p>
<p>Causa : $data->apiMessage</p>
<p>Questi sono i dati che aveva compilato:
<table>
<tr><td>Nome contatto:</td><td><b>	$data->nomecontatto</b></td></tr>
<tr><td>categoria id:</td><td><b>	$data->categoriaid</b></td></tr>
<tr><td>latitudine:</td><td><b>$data->latitudine</b></td></tr>
<tr><td>longitudine:</td><td><b>$data->longitudine</b></td></tr>
<tr><td>nome attivita</td><td><b>$data->nomeattivita</b>	</td></tr>
<tr><td>indirizzo</td><td><b>$data->indirizzo</b></td></tr>
<tr><td>luogo</td><td><b>$data->luogo</b></td></tr>
<tr><td>nazione</td><td><b>$data->nazione</b></td></tr>
<tr><td>Ruolo:</td><td><b>	$data->ruolo</b></td></tr>
<tr><td>Telefono:</td><td><b>$data->prefix <b>$data->telefono</b></td></tr>
<tr><td>e-mail:	</td><td><b>$data->email</b></td></tr>
<tr><td>Sito web:</td><td><b>$data->sitoweb</b></td></tr>
<tr><td>Note:</td><td><b>$data->note</b></td></tr>
</table>
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