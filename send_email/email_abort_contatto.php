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
$mail->addAddress($data->recipient, '');
$mail->addBCC('italianear@gmail.com');
$mail->isHTML(true);      
$mail->Subject = 'Annullamento scheda contatto di ItaliaNear';
$mail->Body = "<p>Buongiorno $data->nickname,</p>

<p>ti scriviamo perché ci risulta un tuo tentativo di registrazione come contatto, che però non è andato a buon fine.<br/>
Se non era tua intenzione registrarti come contatto, ignora pure questo messaggio.<br/>
Altrimenti puoi rispondere a questa email indicandoci i tuoi dati: provvederemo noi a compilare la tua scheda contatto che poi potrai modificare, aggiornare o cancellare in completa autonomia.</p>
<p>I dati richiesti sono:</p>
<p>- Nome<br/>
- Ruolo<br/>
- Tipo di attività<br/>
- Nome dell'attività<br/>
- Indirizzo<br/>
- Località<br/>
- Numero telefonico (preferibilmente WhatsApp) completo di prefisso internazionale</p>

<p>Ringraziandoti per la collaborazione e scusandoci per l'inconveniente, ti porgiamo i nostri più cordiali saluti</p>
<p></p>
<p>Per ogni eventualità ti ricordiamo i nostri riferimenti:</p>
<p>e-mail: info@italianear.it</p>
<p>Web site: https://italianear.it/</p>

<p>Assistenza Contatti ItaliaNear</p>
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