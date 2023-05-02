<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
  
// include database and object files
include_once '../config/database.php';
include_once '../objects/nazione_it.php';
  
// get database connection
$database = new Database();
if(isset($data->isTest) && $data->isTest)
{
    $db = $database->getTestConnection();
}else {
    $db = $database->getConnection();  
}
  
// prepare nazione_it object
$nazione_it = new Nazione_it($db);
  
// set ID property of record to read
$nazione_it->nazione = isset($_GET['nazione']) ? $_GET['nazione'] : die();
  
// read the details of nazione_it to be edited
$nazione_it->findByName();
  
if($nazione_it->nazione_id!=null){
    // create array
   $nazione_arr=array(
            "nazione_id" =>$nazione_it->nazione_id,
            "nazione" =>$nazione_it->nazione,
            "iso" =>$nazione_it->iso,
            "prefisso" =>$nazione_it->prefisso,           
			"createddate" =>$nazione_it->createddate,
			"lastmodified" =>$nazione_it->lastmodified,			
        );
  
    // set response code - 200 OK
    http_response_code(200);
  
    // make it json format
    echo json_encode($nazione_arr);
}
  
else{
    // set response code - 404 Not found
    http_response_code(404);
  
	$nazione_arr=array(
		"nazione" =>'Non trovata',
	);
    // tell the user nazione_it does not exist
    echo json_encode(array("message" => "$nazione_it->nazione does not exist."));
}
?>
