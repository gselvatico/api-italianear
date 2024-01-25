<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
  
// include database and object files
include_once '../config/database.php';
include_once '../objects/stats_it.php';
include_once '../config/apikey.php';

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
  
// prepare contatto_it object
$stat_it = new Stats_it($db);
$stat_it->userID= $data->userID;
  
// delete the contatto_it
$stmt = $stat_it->log_contatto_click();

if($stmt->rowCount()>0){  
     $result_arr=array(    
			"n_click" =>$stat_it->n_click,
            "ultimo" => $stat_it->ultimo,
			"n_utenti" =>$stat_it->n_utenti
        );          
     // set response code - 200 OK
    http_response_code(200);
  
    // show categoria_its data in json format
    echo json_encode($result_arr);
}
else{
    // set response code - 404 Not found
    http_response_code(404);  
	
    // tell the user record does not exist
    echo json_encode(array("message" => "no click"));
}
exit();

?>