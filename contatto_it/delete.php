<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// include database and object file
include_once '../config/database.php';
include_once '../config/apikey.php';
include_once '../objects/contatto_it.php';
  
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare contatto_it object
$contatto_it = new Contatto_it($db);
  
// get contatto_it id
$data = json_decode(file_get_contents("php://input"));

if ($data->api_key != ApiKey::$apiKey) {
    http_response_code(403);  
    echo json_encode(
        array("message" => "Chiave sbagliata")
    ); 
    return;
}
// set contatto_it id to be deleted
$contatto_it->userID = $data->userID;
  
// delete the contatto_it
if($contatto_it->delete()){
  
    // set response code - 200 ok
    http_response_code(200);
  
    // tell the user
    echo json_encode(array("message" => "contatto was deleted."));
}
  
// if unable to delete the contatto_it
else{
  
    // set response code - 503 service unavailable
    http_response_code(503);
  
    // tell the user
    echo json_encode(array("message" => "Unable to delete contatto_it."));
}
?>