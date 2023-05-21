<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
  
// include database and object files
include_once '../config/database.php';
include_once '../objects/contatto_it.php';
include_once '../config/apikey.php';

$data = json_decode(file_get_contents("php://input"));

if ($data->api_key != ApiKey::$apiKey) {
    http_response_code(403);  
    echo json_encode(
        array("message" => "Chiave sbagliata")
    ); 
    return;
} 
// get database connection
$database = new Database();
if(isset($data->isTest) && $data->isTest)
{
    $db = $database->getTestConnection();
}else {
    $db = $database->getConnection();  
}
  
// prepare contatto_it object
$contatto_it = new Contatto_it($db);
  
// set ID property of record to read
$contatto_it->userID = isset($_GET['userID']) ? $_GET['userID'] : die();
  
// read the details of contatto_it to be edited
$contatto_it->readOne();
  
if($contatto_it->contatto_id!=null){
    // create array
   $contatto_arr=array(
            "contatto_id" =>$contatto_it->contatto_id,
            "nome" =>$contatto_it->nome,
            "email" =>$contatto_it->email,
            "nome_negozio" =>$contatto_it->nome_negozio,
			"categoria_id" =>$contatto_it->categoria_id,	
        	"localita" =>$contatto_it->localita,
			"nazioneiso" =>$contatto_it->nazioneiso,
			"indirizzo" =>$contatto_it->indirizzo,
			"tel_prefix" =>$contatto_it->tel_prefix,
			"telefono" =>$contatto_it->telefono,
			"email_c" =>$contatto_it->email_c,
			"ruolo" =>$contatto_it->ruolo,
			"note" =>$contatto_it->note,
			"nomecontatto" =>$contatto_it->nomecontatto,
			"internet" =>$contatto_it->internet,
			"latitudine" =>$contatto_it->latitudine,
			"longitudine" =>$contatto_it->longitudine,
			"immagine" =>$contatto_it->immagine,
			"dataiscrizione" =>$contatto_it->dataiscrizione,
			"vers" =>$contatto_it->vers,
			"so" =>$contatto_it->so,
			"ndr" =>$contatto_it->ndr,
			"createddate" =>$contatto_it->createddate,
			"lastmodified" =>$contatto_it->lastmodified,
			"categoria" =>$contatto_it->categoria,
			// "avg_rating"=>$contatto_it->avg_rating,
            // "n_rating"=>$contatto_it->n_rating			
        );
  
    // set response code - 200 OK
    http_response_code(200);
  
    // make it json format
    echo json_encode($contatto_arr);
}
  
else{
    // set response code - 404 Not found
    http_response_code(404);
  
	$contatto_arr=array(
		"email" =>'test',
	);
    // tell the user contatto_it does not exist
    echo json_encode(array("message" => "contatto_it does not exist."));
}
?>
