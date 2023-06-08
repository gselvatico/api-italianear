<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
  
// include database and object files
include_once '../config/database.php';
include_once '../objects/stats_it.php';
include_once '../config/apikey.php';




$userID = $_GET['userid'];
// get database connection
$database = new Database();
if(isset($data->isTest) && $data->isTest)
{
    $db = $database->getTestConnection();
}else {
    $db = $database->getConnection();  
}
  
$contatto_it = new Stats_it($db);
  
// delete the contatto_it
echo $contatto_it->elimina_contatto_vuoto($userID);
  
header("Location: contatti_vuoti.php");
exit();

?>