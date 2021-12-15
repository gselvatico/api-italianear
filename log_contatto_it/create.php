<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// get database connection
include_once '../config/database.php';
  
// instantiate log_contatto_it object
include_once '../objects/log_contatto_it.php';
  
$database = new Database();
$db = $database->getConnection();
  
$log_contatto_it = new Log_contatto_it($db);
  
// get posted data
$data = json_decode(file_get_contents("php://input"));
  
// make sure data is not empty
if( !empty($data->userID) 	
)
{ // set log_contatto_it property values

    $log_contatto_it->utente_id=$data->utente_id;
    $log_contatto_it->contatto_id=$data->contatto_id;   

  
    // create the log_contatto_it
    if($log_contatto_it->create()){
  
        // set response code - 201 created
        http_response_code(201);
  
        // tell the user
        echo json_encode(array("message" => "log_contatto_it was created."));
    }
  
    // if unable to create the log_contatto_it, tell the user
    else{
  
        // set response code - 503 service unavailable
        http_response_code(503);
  
        // tell the user
        echo json_encode(array("message" => "Unable to create log_contatto_it."));
    }
}
  
// tell the user data is incomplete
else{
  
    // set response code - 400 bad request
    http_response_code(400);
  
    // tell the user
    echo json_encode(array("message" => "Unable to create log_contatto_it. Data is incomplete."));
}
?>