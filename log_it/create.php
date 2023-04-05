<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// get database connection
include_once '../config/database.php';
  
// instantiate log_it object
include_once '../objects/log_it.php';
include_once '../config/apikey.php';

$data = json_decode(file_get_contents("php://input"));

if ($data->api_key != ApiKey::$apiKey) {
    http_response_code(403);  
    echo json_encode(
        array("message" => "Chiave sbagliata")
    ); 
    return;
}
$database = new Database();
$db = $database->getConnection();
  
$log_it = new Log_it($db);
  
// make sure data is not empty
if( !empty($data->userID) 	
)
{ // set log_it property values

    $log_it->userID=$data->userID;
    $log_it->gps=$data->gps;
    $log_it->lista_categorie=$data->lista_categorie;
    $log_it->ricerca_gps=$data->ricerca_gps;
    $log_it->lista_contatti=$data->lista_contatti;
    $log_it->vers=$data->vers;
    $log_it->so=$data->so;
  
    // create the log_it
    if($log_it->create()){
  
        // set response code - 201 created
        http_response_code(201);
  
        // tell the user
        echo json_encode(array("message" => "log_it was created."));
    }
  
    // if unable to create the log_it, tell the user
    else{
  
        // set response code - 503 service unavailable
        http_response_code(503);
  
        // tell the user
        echo json_encode(array("message" => "Unable to create log_it."));
    }
}
  
// tell the user data is incomplete
else{
  
    // set response code - 400 bad request
    http_response_code(400);
  
    // tell the user
    echo json_encode(array("message" => "Unable to create log_it. Data is incomplete."));
}
?>