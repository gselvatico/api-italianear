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
include_once '../objects/categoria_it.php';
  
// get id of categoria_it to be edited
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
  
// prepare categoria_it object
$categoria_it = new Categoria_it($db);
  
// set ID property of categoria_it to be edited
$categoria_it->categoria_id = $data->categoria_id;
  
// set categoria_it property values
$categoria_it->categoria = $data->categoria;

  
// update the categoria_it
if($categoria_it->update()){
  
    // set response code - 200 ok
    http_response_code(200);
  
    // tell the user
    echo json_encode(array("message" => "categoria_it was updated."));
}
  
// if unable to update the categoria_it, tell the user
else{
  
    // set response code - 503 service unavailable
    http_response_code(503);
  
    // tell the user
    echo json_encode(array("message" => "Unable to update categoria_it."));
}
?>