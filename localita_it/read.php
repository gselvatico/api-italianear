<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
  
// database connection will be here

// include database and object files
include_once '../config/database.php';
include_once '../objects/localita_it.php';
  
// instantiate database and localita_it object
$database = new Database();
$db = $database->getConnection();
  
// initialize object
$localita_it = new Localita_it($db);
  
// read localita_its will be here
// query localita_its
$stmt = $localita_it->read();
$num = $stmt->rowCount();
  
// check if more than 0 record found
if($num>0){
  
    // localita_its array
    $localita_its_arr=array();
    $localita_its_arr["records"]=array();
  
    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
  
        $localita_it_item=array(    
			"localita_id" => $localita_id,
			"localita" => $localita,	   
			"nazione_id" => $nazione_id,				
			"createddate" => $createddate,
			"lastmodified" => $lastmodified					
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