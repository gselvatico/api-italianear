<html>
<head>
    <title>Ricerche Italianear</title>
</head>
<body>
<style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        
        table {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        th {
            text-align: left;
            }
    </style>
<?php

include_once '../config/database.php';
include_once '../config/apikey.php';
include_once '../objects/stats_it.php';


$api_key = $_GET['apiKey'];
$date_min = $_GET['datemin'];
$date_max= $_GET['datemax'];

if ($api_key != ApiKey::$apiKey) {
    http_response_code(403);  
    echo json_encode(
        array("message" => "Chiave sbagliata")
    ); 
    return;
}  
// instantiate database and stats_it object
$database = new Database();
if(isset($data->isTest) && $data->isTest)
{
    $db = $database->getTestConnection();
}else {
    $db = $database->getConnection();  
}
// initialize object
$stats_it = new Stats_it($db);
// $stats_it->loc_Id =str_replace("@","," , isset($_GET['l_id']) ? $_GET['l_id'] : die());
// $stats_it->cat_Id = isset($_GET['c_id']) ? $_GET['c_id'] : die();
$stats_it->date_min= $date_min;
$stats_it->date_max = $date_max;

$stmt = $stats_it->log_ricerche();

//******* */ i totali ************
$stats_it->totali_log_ricerche();
$totali_arr=array(
    "totaleUtenti" =>$stats_it->totaleUtenti,
    "totaleRicerche" =>$stats_it->totaleRicerche,
);    
//******** */ fine totali***********

$num =$stmt->rowCount();
  
// check if more than 0 record found
if($num>0){
  
    // stats_its array
    $stats_its_arr=array();
    $stats_its_arr["records"]=array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
  
        $stats_it_item=array(       
			"email" => $email,
			"nlog" => $nlog,
			"lastdate" => $lastdate
        );
  
        array_push($stats_its_arr["records"], $stats_it_item);
    }
  
    // set response code - 200 OK
    http_response_code(200);
  
    // show stats_its data in json format
    //echo json_encode($stats_its_arr);
    $js=json_encode($stats_its_arr);

    $data = json_decode($js, true);
    if ($data === null) {
        die('Errore nella decodifica del JSON.');
    }
    
    // Estrai l'array di record dal JSON
    $records = $data['records'];
    if (count($records) === 0) {
        echo 'Nessun dato da mostrare.';
    } else {
        echo '<h2>Statistiche delle ricerche dal '. date("d/m/Y", strtotime($date_min)).' al '.date("d/m/Y", strtotime($date_max)).' </h2>';
        // Genera la tabella HTML
        echo '<table style: font-family: Arial, sans-serif;
        font-size: 8px; >';
        echo '<thead><tr><th>Email</th><th>N. ricerche</th><th>Ultima data</th></tr></thead>';
        echo '<tbody>';
        foreach ($records as $record) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($record['email']) . '</td>';
            echo '<td>' . htmlspecialchars($record['nlog']) . '</td>';
            echo '<td>' . htmlspecialchars($record['lastdate']) . '</td>';
            echo '</tr>';
        }
        // ******i totali**********
        echo '<tr> <td >____________</td><td>_____________</td><td></td></tr>';
        echo '<tr>';
        echo '<td>' . $totali_arr['totaleUtenti'] . ' </td>';
        echo '<td>' .  $totali_arr['totaleRicerche'] . '</td>';
        echo '<td>' . '</td>';
        echo '</tr>';
        echo '</tbody>';
        echo '</table>';
        http_response_code(200);
    }

}
  
// no stats_its found will be here
else{
  
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user no products found
    echo json_encode(
        array("message" => "Nessun contatto trovato.")
    );
  //  echo $stmt;
}
?>
</body>
</html>