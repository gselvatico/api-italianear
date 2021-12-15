<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// include database and object files
include_once '../config/database.php';
include_once '../objects/utente_it.php';
  
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare utente_it object
$utente_it = new Utente_it($db);
  
// get id of utente_it to be edited
$data = json_decode(file_get_contents("php://input"));
  
// set ID property of utente_it to be edited
$utente_it->userID = $data->userID;
  
// set utente_it property values
$utente_it->nickname = $data->nickname;
$utente_it->email = $data->email;
$utente_it->tipo = $data->tipo;
$utente_it->data_URU = $data->data_URU;
$utente_it->vers = $data->vers;
$utente_it->so = $data->so;
$utente_it->ndr = $data->ndr;
  
// update the utente_it
if($utente_it->update()){
  
    // set response code - 200 ok
    http_response_code(200);
  
    // tell the user
    echo json_encode(array("message" => "utente_it was updated."));
}
  
// if unable to update the utente_it, tell the user
else{
  
    // set response code - 503 service unavailable
    http_response_code(503);
  
    // tell the user
    echo json_encode(array("message" => "Unable to update utente_it."));
}
?>