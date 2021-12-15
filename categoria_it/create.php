<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// get database connection
include_once '../config/database.php';
  
// instantiate categoria_it object
include_once '../objects/categoria_it.php';
  
$database = new Database();
$db = $database->getConnection();
  
$categoria_it = new Categoria_it($db);
  
// get posted data
$data = json_decode(file_get_contents("php://input"));
  
// make sure data is not empty
if( !empty($data->categoria) &&			
	!empty($data->createddate)	
)
{ // set categoria_it property values
	$categoria_it->categoria= $data->categoria;	
	$categoria_it->createddate = date('Y-m-d H:i:s');
  
    // create the categoria_it
    if($categoria_it->create()){
  
        // set response code - 201 created
        http_response_code(201);
  
        // tell the user
        echo json_encode(array("message" => "categoria_it was created."));
    }
  
    // if unable to create the categoria_it, tell the user
    else{
  
        // set response code - 503 service unavailable
        http_response_code(503);
  
        // tell the user
        echo json_encode(array("message" => "Unable to create categoria_it."));
    }
}
  
// tell the user data is incomplete
else{
  
    // set response code - 400 bad request
    http_response_code(400);
  
    // tell the user
    echo json_encode(array("message" => "Unable to create categoria_it. Data is incomplete."));
}
?>