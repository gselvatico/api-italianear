<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  

include_once '../config/database.php';
include_once '../config/apikey.php';  
include_once '../objects/c_rating.php';
  
$database = new Database();
$db = $database->getConnection();
  
$c_rating = new C_rating($db);
  
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
if( !empty($data->contatto_id)&&		
	!empty($data->utente_id) &&
    !empty($data->c_rating)
	//!empty($data->vers) &&
	//!empty($data->so) &&
	//!empty($data->ndr) &&
	//!empty($data->createddate)	
)
{ // set c_rating property values
    $c_rating->contatto_id= $data->contatto_id;
    $c_rating->utente_id= $data->utente_id;
	$c_rating->c_rating= $data->c_rating;
	$c_rating->description = $data->description;
	$c_rating->createddate = date('Y-m-d H:i:s');
  
    // create the c_rating
    if($c_rating->create()){
  
        // set response code - 201 created
        http_response_code(201);
  
        // tell the user
        echo json_encode(array("message" => "c_rating was created."));
    }
  
    // if unable to create the c_rating, tell the user
    else{
  
        // set response code - 503 service unavailable
        http_response_code(503);
  
        // tell the user
        echo json_encode(array("message" => "Unable to create c_rating."));
    }
}
  
// tell the user data is incomplete
else{
  
    // set response code - 400 bad request
    http_response_code(400);
  
    // tell the user
    echo json_encode(array("message" => "Unable to create c_rating. Data is incomplete."));
}
?>