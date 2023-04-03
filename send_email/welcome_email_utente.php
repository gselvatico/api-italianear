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
$mail->Username = 'italiare';
$mail->Password = 'Italo2021';
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
$mail->Body = "
Buongiorno  $data->nickname,<br/>
Grazie per la tua registrazione!<br/>
Ti saremo grati se vorrai condividere con noi la tua esperienza di utente ItaliaNear, facendoci sapere se la nostra app ti è stata utile e segnalandoci eventuali anomalie o suggerimenti per migliorarne l'utilizzo. <br/>
Ecco i nostri riferimenti:<br/>
e-mail: info@italianear.it<br/>
Web site: https://italianear.it/<br/>
<br/>
Un cordiale saluto<br/>
<br/>
Il team ItaliaNear";
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