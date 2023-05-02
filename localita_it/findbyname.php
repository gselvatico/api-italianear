<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
  
// include database and object files
include_once '../config/database.php';
include_once '../objects/localita_it.php';
  
// get database connection
$database = new Database();
if(isset($data->isTest) && $data->isTest)
{
    $db = $database->getTestConnection();
}else {
    $db = $database->getConnection();  
}
  
// prepare localita_it object
$localita_it = new Localita_it($db);
  
// set ID property of record to read
$localita_it->localita = isset($_GET['localita']) ? $_GET['localita'] : die();
  
// read the details of localita_it to be edited
$localita_it->FindByName();
  
if($localita_it->localita_id!=null){
    // create array
   $localita_arr=array(
            "localita_id"=>$localita_it->localita_id,
            "nazione_id"=>$localita_it->nazione_id,
            "localita"=>$localita_it->localita
        );
  
    // set response code - 200 OK
    http_response_code(200);
  
    // make it json format
    echo json_encode($localita_arr);
}
  
else{
    // set response code - 404 Not found
    http_response_code(404);  
	
    // tell the user localita_it does not exist
    echo json_encode(array("message" => "localita does not exist."));
}
?>
