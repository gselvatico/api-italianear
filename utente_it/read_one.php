<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
  
// include database and object files
include_once '../config/database.php';
include_once '../objects/utente_it.php';
include_once '../config/apikey.php';

$data = json_decode(file_get_contents("php://input"));

// if ($data->api_key != ApiKey::$apiKey) {
//     http_response_code(403);  
//     echo json_encode(
//         array("message" => "Chiave sbagliata")
//     ); 
//     return;
// } 
// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare utente_it object
$utente_it = new Utente_it($db);
  
// set ID property of record to read
$utente_it->userID = isset($_GET['userID']) ? $_GET['userID'] : die();
  
// read the details of utente_it to be edited
$utente_it->readOne();
  
if($utente_it->userID!=null){
    // create array
   $utente_arr=array(
            "utente_id" =>$utente_it->utente_id,
            "userID"=>$utente_it->userID,
            "nickname"=>$utente_it->nickname,
            "email" =>$utente_it->email,
            "tipo" =>$utente_it->tipo,
			"data_PRU" =>$utente_it->data_PRU,
			"data_URU" =>$utente_it->data_URU,	        
			"vers" =>$utente_it->vers,
			"so" =>$utente_it->so,
			"ndr" =>$utente_it->ndr,
            "tipo_reg" =>$utente_it->tipo_reg,
			"createddate" =>$utente_it->createddate,
			"lastmodified" =>$utente_it->lastmodified			
        );
  
    // set response code - 200 OK
    http_response_code(200);
  
    // make it json format
    echo json_encode($utente_arr);
}
  
else{
    // set response code - 404 Not found
    http_response_code(404);  
	
    // tell the user utente_it does not exist
    echo json_encode(array("message" => "utente_it does not exist."));
}
?>
