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
include_once '../objects/categoria_it.php';
  
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare categoria_it object
$categoria_it = new Categoria_it($db);
  
// get categoria_it id
$data = json_decode(file_get_contents("php://input"));

if ($data->api_key != ApiKey::$apiKey) {
    http_response_code(403);  
    echo json_encode(
        array("message" => "Chiave sbagliata")
    ); 
    return;
}
// set categoria_it id to be deleted
$categoria_it->categoria_id = $data->categoria_id;
  
// delete the categoria_it
if($categoria_it->delete()){
  
    // set response code - 200 ok
    http_response_code(200);
  
    // tell the user
    echo json_encode(array("message" => "categoria_it was deleted."));
}
  
// if unable to delete the categoria_it
else{
  
    // set response code - 503 service unavailable
    http_response_code(503);
  
    // tell the user
    echo json_encode(array("message" => "Unable to delete categoria_it."));
}
?>