<?php

// legge tutti i ratings di un contatto

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


include_once '../config/database.php';
include_once '../config/apikey.php';
include_once '../objects/c_rating.php';

$data = json_decode(file_get_contents("php://input"));


if ($data->api_key != ApiKey::$apiKey) {
    http_response_code(403);  
    echo json_encode(
        array("message" => "Chiave sbagliata")
    ); 
    return;
}  
// instantiate database and c_rating object
$database = new Database();
if(isset($data->isTest) && $data->isTest)
{
    $db = $database->getTestConnection();
}else {
    $db = $database->getConnection();  
}
  
// initialize object
$c_rating = new c_rating($db);
$c_rating->contatto_id = $data->contatto_id;  
$stmt = $c_rating->read();
// $num =0;
$num =$stmt->rowCount();
  
// check if more than 0 record found
if($num>0){
  
    // c_ratings array
    $c_ratings_arr=array();
    $c_ratings_arr["records"]=array();
  
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);  
        $c_rating_item=array(
            "c_rating_id"=>$c_rating_id,
            "contatto_id" => $contatto_id,
            "utente_id" => $utente_id,
            "c_rating" => $c_rating,
            "description" => $description,
            "replay" => $replay,
            "enable_description" => $enable_description,
            "enable_replay" => $enable_replay,
			"createddate" => $createddate,
			"lastmodified" => $lastmodified,
            "nickname" => $nickname		
        );
  
        array_push($c_ratings_arr["records"], $c_rating_item);
    }
  
    // set response code - 200 OK
    http_response_code(200);
  
    // show c_ratings data in json format
    echo json_encode($c_ratings_arr);
}
  
// no c_ratings found will be here
else{
  
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user no products found
    echo json_encode(
        array("message" => "Nessun rating trovato.")
    );
  //  echo $stmt;
}