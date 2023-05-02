<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// get database connection
include_once '../config/database.php';
  
// instantiate nazione_it object
include_once '../objects/nazione_it.php';
  
$database = new Database();
if(isset($data->isTest) && $data->isTest)
{
    $db = $database->getTestConnection();
}else {
    $db = $database->getConnection();  
}
  
$nazione_it = new Nazione_it($db);
  
// get posted data
$data = json_decode(file_get_contents("php://input"));
  
// make sure data is not empty
if( !empty($data->nazione) &&			
	!empty($data->prefisso) &&		
	!empty($data->createddate)	
)
{ // set nazione_it property values
	$nazione_it->nazione= $data->nazione;	
    $nazione_it->iso= $data->iso;
	$nazione_it->prefisso= $data->prefisso;
	$nazione_it->flag= $data->flag;
	$nazione_it->createddate = date('Y-m-d H:i:s');
  
    // create the nazione_it
    if($nazione_it->create()){
  
        // set response code - 201 created
        http_response_code(201);
  
        // tell the user
        echo json_encode(array("message" => "nazione_it was created."));
    }
  
    // if unable to create the nazione_it, tell the user
    else{
  
        // set response code - 503 service unavailable
        http_response_code(503);
  
        // tell the user
        echo json_encode(array("message" => "Unable to create nazione_it."));
    }
}
  
// tell the user data is incomplete
else{
  
    // set response code - 400 bad request
    http_response_code(400);
  
    // tell the user
    echo json_encode(array("message" => "Unable to create nazione_it. Data is incomplete."));
}
?>