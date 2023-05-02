<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
  
// database connection will be here

// include database and object files
include_once '../config/database.php';
include_once '../objects/nazione_it.php';
  
// instantiate database and nazione_it object
$database = new Database();
if(isset($data->isTest) && $data->isTest)
{
    $db = $database->getTestConnection();
}else {
    $db = $database->getConnection();  
}
  
// initialize object
$nazione_it = new Nazione_it($db);
  
// read nazione_its will be here
// query nazione_its
$stmt = $nazione_it->read();
$num = $stmt->rowCount();
  
// check if more than 0 record found
if($num>0){
  
    // nazione_its array
    $nazione_its_arr=array();
    $nazione_its_arr["records"]=array();
  
    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
  
        $nazione_it_item=array(    
			"nazione_id" => $nazione_id,
			"nazione" => $nazione,	   	
            "iso" => $iso,	   	
			"createddate" => $createddate,
			"lastmodified" => $lastmodified					
        );
  
        array_push($nazione_its_arr["records"], $nazione_it_item);
    }
  
    // set response code - 200 OK
    http_response_code(200);
  
    // show nazione_its data in json format
    echo json_encode($nazione_its_arr);
}
  
// no nazione_its found will be here
else{
  
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user no products found
    echo json_encode(
        array("message" => "No products found.")
    );
}