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

<p>ti scriviamo perché abbiamo rilevato un tuo tentativo di registrazione come contatto, che però non è andato a buon fine.<br/>
Se non era tua intenzione registrarti come contatto, ignora pure questo messaggio.<br/>
Altrimenti, ti preghiamo di farci sapere se qualcosa ti ha dissuaso dal completare la tua registrazione o se hai riscontrato un problema che ti ha impedito di portarla a termine<br/>
In tal caso, possiamo provvedere noi a compilare la tua scheda che poi potrai modificare, aggiornare o cancellare in completa autonomia.</p>

<p>Sarà sufficiente che tu ci fornisca, per e-mail, i seguenti dati:</p>
<p>- Nome<br/>
- Ruolo<br/>
- Tipo di attività<br/>
- Nome dell'attività<br/>
- Indirizzo<br/>
- Località<br/>
- Numero telefonico (preferibilmente WhatsApp) completo di prefisso internazionale</p>

<p>Scusandoci per l'inconveniente e ringraziandoti per la collaborazione, restiamo in attesa di un tuo riscontro.</p>
<p></p>
<p><i>Assistenza Contatti <br/>
ItaliaNear</i></p>
<img src='https://italianear.it/images/italianear_Hi-res_Icon_512.png' width='66' height='66'>      
<p>Per ogni eventualità ti ricordiamo i nostri riferimenti:</p>
<p>e-mail: info@italianear.it<br/>
Guida all'uso: https://www.italianear.it/guida.html<br/>
Web site: https://italianear.it/</p>

<p><a href=\"https://www.youtube.com/channel/UCZwgxMhRcQUJwufKFy8bjlA\"> YouTube</a><br/>	
<a href=\"https://www.facebook.com/ItaliaNear.it\"> Facebook</a><br/>
<a href=\"https://twitter.com/ItaliaNear\"> X (Twitter)</a><br/>
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