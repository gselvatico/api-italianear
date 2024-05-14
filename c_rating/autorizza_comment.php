<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
  
// include database and object files
include_once '../config/database.php';
include_once '../objects/c_rating.php';
include_once '../config/apikey.php';




$c_rating_id = $_GET['c_rating_id'];
// get database connection
$database = new Database();
if(isset($data->isTest) && $data->isTest)
{
    $db = $database->getTestConnection();
}else {
    $db = $database->getConnection();  
}
  
$c_rating = new C_rating($db);
  
// delete the contatto_it
echo $c_rating->autorizza_comment($c_rating_id);
  
header("Location: rating_check.php");
exit();

?>