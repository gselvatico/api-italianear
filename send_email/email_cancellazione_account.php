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
$isContatto =  $data->is_contatto;
$testoSchedaContatto="";
$oggetto="Cancellazione account utente ItaliaNear";
 if($isContatto == "true") {       
    $testoSchedaContatto=" e la scheda contatto ad esso associata";
    $oggetto="Cancellazione account contatto ItaliaNear";
}
//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);
// $mail->SMTPDebug = SMTP::DEBUG_SERVER;  
$mail->isSMTP();
// $mail->Host = 'mail.italianear.it';
$mail->Host = 'italianear.it';
// $mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = ApiKey::$usermail;
$mail->Password = ApiKey::$mailpwd;
$mail->SMTPSecure = 'tsl';
$mail->Port = 587;
$mail->CharSet = "UTF-8";


$mail->setFrom('info@italianear.it', 'ItaliaNear');
$mail->addAddress($data->recipient, '');
$mail->addBCC('italianear@gmail.com');
// $mail->addAddress('picobellone@gmail.com', '');
// $mail->addAddress('u.ricci@gmail.com', '');
$mail->isHTML(true);      
$mail->Subject = $oggetto;
$mail->Body = "<p>Buongiorno $data->nickname,</p>
<p>Come da tua richiesta abbiamo provveduto a cancellare il tuo account$testoSchedaContatto.</p>
<p>Ricorda che, in qualsiasi momento, potrai creare un nuovo account ItaliaNear, anche con lo stesso indirizzo e-mail.</p>
<p>Per ogni eventualità ti ricordiamo i nostri riferimenti:</p>
<p>e-mail: info@italianear.it</p>
<p>Web site: https://italianear.it/</p>
<p>Un cordiale saluto</p>

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