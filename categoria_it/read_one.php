<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
  
// include database and object files
include_once '../config/database.php';
include_once '../objects/categoria_it.php';
  
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare categoria_it object
$categoria_it = new Categoria_it($db);
  
// set ID property of record to read
$categoria_it->categoria_id = isset($_GET['categoria_id']) ? $_GET['categoria_id'] : die();
  
// read the details of categoria_it to be edited
$categoria_it->readOne();
  
if($categoria_it->categoria!=null){
    // create array
    $categoria_arr = array(
        "categoria_id" =>  $categoria_it->categoria_id,
        "father_id" =>  $categoria_it->father_id,
        "categoria" => $categoria_it->categoria
    );
  
    // set response code - 200 OK
    http_response_code(200);
  
    // make it json format
    echo json_encode($categoria_arr);
}
  
else{
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user categoria_it does not exist
    echo json_encode(array("message" => "categoria_it does not exist."));
}
?>