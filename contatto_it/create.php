<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


include_once '../config/database.php';
include_once '../config/apikey.php';
include_once '../objects/contatto_it.php';

$database = new Database();
$db = $database->getConnection();

$contatto_it = new Contatto_it($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));
if ($data->api_key != ApiKey::$apiKey) {
	http_response_code(403);  
    echo json_encode(
        array("message" => "Chiave sbagliata")
    ); 
    return;
}
// make sure data is not empty
if (
	!empty($data->userID) &&
	!empty($data->email) &&
	!empty($data->nome) &&
	!empty($data->createddate)
) { // set contatto_it property values
	$contatto_it->userID = $data->userID;
	$contatto_it->email = $data->email;
	$contatto_it->nome = $data->nome;
	$contatto_it->categoria_id = $data->categoria_id;
	$contatto_it->nome_negozio = $data->nome_negozio;
	$contatto_it->localita = $data->localita;
	$contatto_it->indirizzo = $data->indirizzo;
	$contatto_it->telefono = $data->telefono;
	$contatto_it->email_c = $data->email_c;
	$contatto_it->ruolo = $data->ruolo;
	$contatto_it->note = $data->note;
	$contatto_it->nomecontatto = $data->nomecontatto;
	$contatto_it->internet = $data->internet;
	$contatto_it->latitudine = $data->latitudine;
	$contatto_it->longitudine = $data->longitudine;
	$contatto_it->immagine = $data->immagine;
	$contatto_it->nazioneiso = $data->nazioneiso;
	$contatto_it->dataiscrizione = $data->dataiscrizione;
	$contatto_it->vers = $data->vers;
	$contatto_it->so = $data->so;
	$contatto_it->ndr = $data->ndr;
	$contatto_it->createddate = date('Y-m-d H:i:s');

	// create the contatto_it
	$result = $contatto_it->create();
	if ($result > 0) {

		// set response code - 201 created
		http_response_code(201);
		echo $result;
		// tell the user
		//echo json_encode(array("message" => "contatto_it was created."));
	}

	// if unable to create the contatto_it, tell the user
	else {

		// set response code - 503 service unavailable
		http_response_code(503);

		// tell the user
		echo json_encode(array("Unable to create contatto_it" =>  $result));
	}
}

// tell the user data is incomplete
else {

	// set response code - 400 bad request
	http_response_code(400);

	// tell the user
	echo json_encode(array("message" => "Unable to create contatto_it. Data is incomplete."));
}
