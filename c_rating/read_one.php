<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json; charset=UTF-8");
  
// include database and object files
include_once '../config/database.php';
include_once '../objects/c_rating.php';
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

// prepare c_rating object
$c_rating = new C_rating($db);
$c_rating->utente_id= $data->utente_id;
$c_rating->contatto_id = $data->contatto_id;
// set ID property of record to read
// $c_rating->c_rating_id = isset($_GET['c_rating_id']) ? $_GET['c_rating_id'] : die();
  
// read the details of c_rating to be edited
$c_rating->readOne();
  
 if($c_rating->c_rating_id!=null){
    // create array
   $c_rating_arr=array(
            "c_rating_id" =>$c_rating->c_rating_id,
            "contatto_id"=>$c_rating->contatto_id,
            "utente_id"=>$c_rating->utente_id,
            "c_rating" =>$c_rating->c_rating,
            "comment" =>$c_rating->comment,
			"createddate" =>$c_rating->createddate,
			"lastmodified" =>$c_rating->lastmodified			
        );
  
    // set response code - 200 OK
    http_response_code(200);
  
    // make it json format
    echo json_encode($c_rating_arr);
}
  
else{
    // set response code - 404 Not found
    http_response_code(404);  
	
    // tell the user c_rating does not exist
    echo json_encode(array("message" => "c_rating does not exist."));
}
?>
