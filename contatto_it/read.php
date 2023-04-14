<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
  
// database connection will be here

// include database and object files
include_once '../config/database.php';
include_once '../objects/contatto_it.php';
include_once '../config/apikey.php';

$data = json_decode(file_get_contents("php://input"));

if ($data->api_key != ApiKey::$apiKey) {
    http_response_code(403);  
    echo json_encode(
        array("message" => "Chiave sbagliata")
    ); 
    return;
}
// instantiate database and contatto_it object
$database = new Database();
$db = $database->getConnection();
  
// initialize object
$contatto_it = new Contatto_it($db);
  
// read contatto_its will be here
// query contatto_its
$stmt = $contatto_it->read();
$num = $stmt->rowCount();
  
// check if more than 0 record found
if($num>0){
  
    // contatto_its array
    $contatto_its_arr=array();
    $contatto_its_arr["records"]=array();
  
    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
  
        $contatto_it_item=array(
            "contatto_id" => $contatto_id,
            "nome" => $nome,
            "email" => html_entity_decode($email),
            "nome_negozio" => $nome_negozio,
			"categoria_id" => $categoria_id,
			"dataiscrizione" => $dataiscrizione,	
        	"localita" => $localita,
			"indirizzo" => $indirizzo,
            "tel_prefix" => $tel_prefix,
			"telefono" => $telefono,
			"email_c" => $email_c,
			"ruolo" => $ruolo,
			"note" => $note,
			"nomecontatto" => $nomecontatto,
			"internet" => $internet,
			"latitudine" => $latitudine,
			"longitudine" => $longitudine,
            "immagine" => $immagine,
			"dataiscrizione" => $dataiscrizione,
			"vers" => $vers,
			"so" => $so,
			"ndr" => $ndr,
			"createddate" => $createddate,
			"lastmodified" => $lastmodified,				
			"categoria" => $categoria,
            "nazioneiso" => $nazioneiso,
            "prefisso" => $prefisso
        );
  
        array_push($contatto_its_arr["records"], $contatto_it_item);
    }
  
    // set response code - 200 OK
    http_response_code(200);
  
    // show contatto_its data in json format
    echo json_encode($contatto_its_arr);
}
  
// no contatto_its found will be here
else{
  
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user no products found
    echo json_encode(
        array("message" => "No products found.")
    );
}