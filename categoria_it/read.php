<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
  
// database connection will be here

// include database and object files
include_once '../config/database.php';
include_once '../objects/categoria_it.php';
include_once '../config/apikey.php';

$data = json_decode(file_get_contents("php://input"));

// if ($data->api_key != ApiKey::$apiKey) {
//     http_response_code(403);  
//     echo json_encode(
//         array("message" => "Chiave sbagliata")
//     ); 
//     return;
// }
// instantiate database and categoria_it object
$database = new Database();
if(isset($data->isTest) && $data->isTest)
{
    $db = $database->getTestConnection();
}else {
    $db = $database->getConnection();  
}
  
// initialize object
$categoria_it = new Categoria_it($db);


// read categoria_its will be here
// query categoria_its
$stmt = $categoria_it->read();
$num = $stmt->rowCount();
  
// check if more than 0 record found
if($num>0){
  
    // categoria_its array
    $categoria_its_arr=array();
    $categoria_its_arr["records"]=array();
  
    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
  
        $categoria_it_item=array(    
			"categoria_id" => $categoria_id,
            "father_id" => $father_id,
            "categoria" => $categoria,	        	
			"createddate" => $createddate,
			"lastmodified" => $lastmodified					
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
        array("message" => "No products found.")
    );
}