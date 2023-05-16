<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
  
// include database and object files
include_once '../config/database.php';
include_once '../config/apikey.php';
include_once '../objects/categoria_it.php';
  
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
  
// prepare categoria_it object
$categoria_it = new Categoria_it($db);

//$data = file_get_contents("php://input");
$categoria_it->lat_min= $data->lat_min;
$categoria_it->lat_max = $data->lat_max;
$categoria_it->lng_min= $data->lng_min;
$categoria_it->lng_max = $data->lng_max;


$stmt = $categoria_it->readbylatlng();
$num = $stmt->rowCount();

if($num>0){
  
    // categoria_its array
    $categoria_its_arr=array();
    $categoria_its_arr["records"]=array();  
   
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
  
        $categoria_it_item=array(    
			"categoria_id" => $categoria_id,
            "father_id" => $father_id,
			"categoria" => $categoria,
            "ncontatti" => $ncontatti
        );  
        array_push($categoria_its_arr["records"], $categoria_it_item);
    }  
    // set response code - 200 OK
    http_response_code(200);
  
    // show categoria_its data in json format
    echo json_encode($categoria_its_arr);
}
  
// no categoria_its found will be here
else{
  
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user no products found
    echo json_encode(
        array("message" => "Nessuna categoria trovata.")
    );
}
?>