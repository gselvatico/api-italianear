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
$db = $database->getConnection();
  
// prepare localita_it object
$localita_it = new Localita_it($db);
  
// set ID property of record to read
$localita_it->nazione_id = isset($_GET['nazione_id']) ? $_GET['nazione_id'] : die();

$stmt = $localita_it->SelectByNazione();
$num = $stmt->rowCount();

if($num>0){
  
    // localita_its array
    $localita_its_arr=array();
    $localita_its_arr["records"]=array();  
   
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
  
        $localita_it_item=array(    
			"localita_id" => $localita_id,
			"localita" => $localita
        );  
        array_push($localita_its_arr["records"], $localita_it_item);
    }  
    // set response code - 200 OK
    http_response_code(200);
  
    // show localita_its data in json format
    echo json_encode($localita_its_arr);
}
  
// no localita_its found will be here
else{
  
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user no products found
    echo json_encode(
        array("message" => "No products found.")
    );
}
?>