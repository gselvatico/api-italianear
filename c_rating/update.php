<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// include database and object files
include_once '../config/database.php';
include_once '../config/apikey.php';
include_once '../objects/c_rating.php';
  
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare c_rating object
$c_rating = new C_rating($db);
  
// get id of c_rating to be edited
$data = json_decode(file_get_contents("php://input"));

if ($data->api_key != ApiKey::$apiKey) {
    http_response_code(403);  
    echo json_encode(
        array("message" => "Chiave sbagliata")
    ); 
    return;
}
// set ID property of c_rating to be edited
$c_rating->c_rating = $data->c_rating;
$c_rating->description = $data->description;
$c_rating->contatto_id = $data->contatto_id;
$c_rating->utente_id = $data->utente_id;

// update the c_rating
if($c_rating->update()){
  
    // set response code - 200 ok
    http_response_code(200);
  
    // tell the user
    echo json_encode(array("message" => "c_rating was updated."));
}
  
// if unable to update the c_rating, tell the user
else{
  
    // set response code - 503 service unavailable
    http_response_code(503);
  
    // tell the user
    echo json_encode(array("message" => "Unable to update c_rating."));
}
?>