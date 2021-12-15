<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
  
// database connection will be here

// include database and object files
include_once '../config/database.php';
include_once '../objects/utente_it.php';
  
// instantiate database and utente_it object
$database = new Database();
$db = $database->getConnection();
  
// initialize object
$utente_it = new Utente_it($db);
  
// read utente_its will be here
// query utente_its
$stmt = $utente_it->read();
$num = $stmt->rowCount();
  
// check if more than 0 record found
if($num>0){
  
    // utente_its array
    $utente_its_arr=array();
    $utente_its_arr["records"]=array();
  
    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
  
        $utente_it_item=array(
            "utente_id" => $utente_id,   
            "userID" => $userID,      
            "nickname" => $nickname,
            "email" => html_entity_decode($email),            
			"tipo" => $tipo,
            "data_PRU" => $data_PRU,
			"data_URU" => $data_URU,			
			"vers" => $vers,
			"so" => $so,
			"ndr" => $ndr,
			"createddate" => $createddate,
			"lastmodified" => $lastmodified
        );
  
        array_push($utente_its_arr["records"], $utente_it_item);
    }
  
    // set response code - 200 OK
    http_response_code(200);
  
    // show utente_its data in json format
    echo json_encode($utente_its_arr);
}
  
// no utente_its found will be here
else{
  
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user no products found
    echo json_encode(
        array("message" => "No products found.")
    );
}