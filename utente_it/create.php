<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  

include_once '../config/database.php';
include_once '../config/apikey.php';  
include_once '../objects/utente_it.php';

// get posted data
$data = json_decode(file_get_contents("php://input"));

if ($data->api_key != ApiKey::$apiKey) {
    http_response_code(403);  
    echo json_encode(
        array("message" => "Chiave sbagliata")
    ); 
    return;
}
$database = new Database();
if(isset($data->isTest) && $data->isTest)
{
    $db = $database->getTestConnection();
}else {
    $db = $database->getConnection();  
}
  
$utente_it = new Utente_it($db);
  
// make sure data is not empty
if( !empty($data->email)&&		
	!empty($data->userID) 
	//!empty($data->data_URU)&&
	//!empty($data->vers) &&
	//!empty($data->so) &&
	//!empty($data->ndr) &&
	//!empty($data->createddate)	
)
{ // set utente_it property values
    $utente_it->userID= $data->userID;
    $utente_it->nickname= $data->nickname;
	$utente_it->email= $data->email;
	$utente_it->tipo = $data->tipo;
	$utente_it->data_PRU = date('Y-m-d H:i:s');
	$utente_it->data_URU = date('Y-m-d H:i:s');
	$utente_it->vers = $data->vers;
	$utente_it->so = $data->so;
	$utente_it->ndr = $data->ndr;
    $utente_it->tipo_reg = $data->tipo_reg;
	$utente_it->createddate = date('Y-m-d H:i:s');
  
    // create the utente_it
    if($utente_it->create()){
  
        // set response code - 201 created
        http_response_code(201);
  
        // tell the user
        echo json_encode(array("message" => "utente_it was created."));
    }
  
    // if unable to create the utente_it, tell the user
    else{
  
        // set response code - 503 service unavailable
        http_response_code(503);
  
        // tell the user
        echo json_encode(array("message" => "Unable to create utente_it."));
    }
}
  
// tell the user data is incomplete
else{
  
    // set response code - 400 bad request
    http_response_code(400);
  
    // tell the user
    echo json_encode(array("message" => "Unable to create utente_it. Data is incomplete."));
}
?>