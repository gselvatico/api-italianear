<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// include database and object files
include_once '../config/database.php';
include_once '../objects/contatto_it.php';
  
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare contatto_it object
$contatto_it = new Contatto_it($db);
  
// get id of contatto_it to be edited
$data = json_decode(file_get_contents("php://input"));
  
// set ID property of contatto_it to be edited
$contatto_it->userID = $data->userID;
  
// set contatto_it property values
$contatto_it->email = $data->email;
$contatto_it->nome = $data->nome;
$contatto_it->categoria_id = $data->categoria_id;
$contatto_it->nome_negozio = $data->nome_negozio;
$contatto_it->localita_id = $data->localita_id;
$contatto_it->indirizzo = $data->indirizzo;
$contatto_it->nazioneiso = $data->nazioneiso;
$contatto_it->telefono = $data->telefono;
$contatto_it->email_c = $data->email_c;
$contatto_it->ruolo = $data->ruolo;
$contatto_it->note = $data->note;
$contatto_it->nomecontatto = $data->nomecontatto;
$contatto_it->internet = $data->internet;
$contatto_it->latitudine = $data->latitudine;
$contatto_it->longitudine = $data->longitudine;
$contatto_it->immagine = $data->immagine;
$contatto_it->dataiscrizione = $data->dataiscrizione;
$contatto_it->vers = $data->vers;
$contatto_it->so = $data->so;
$contatto_it->ndr = $data->ndr;
$contatto_it->cat_Id = $data->cat_Id;
$contatto_it->loc_Id = $data->loc_Id;

  
// update the contatto_it
if($contatto_it->update()){
  
    // set response code - 200 ok
    http_response_code(200);
  
    // tell the user
    echo json_encode(array("message" => "contatto_it was updated."));
}
  
// if unable to update the contatto_it, tell the user
else{
  
    // set response code - 503 service unavailable
    http_response_code(503);
  
    // tell the user
    echo json_encode(array("message" => "Unable to update contatto_it."));
}
?>