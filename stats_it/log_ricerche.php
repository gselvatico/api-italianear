<html>
<head>
    <meta name="robots" content="noindex">
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
error_reporting(0);
ini_set('display_errors', 0);
include_once '../config/database.php';
include_once '../objects/stats_it.php';

$dataMin;
$dataMax;
// $api_key = $_GET['apiKey'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Controlla se il campo "data" è stato inviato
    if(isset($_POST["datamin"]) && !empty($_POST["datamin"])) {
      $dataMin       = $_POST["datamin"];
    //   echo "Hai selezionato la data: " . $dataMin    ;
    } else {
      echo "Errore: Il campo data iniziale non è stato inviato correttamente.";
    }
    if(isset($_POST["datamax"]) && !empty($_POST["datamax"])) {
        $dataMax = $_POST["datamax"];
      //   echo "Hai selezionato la data: " . $dataMin      ;
      } else {
        echo "Errore: Il campo data finale non è stato inviato correttamente.";
      }
    if(isset($_POST["pwd"]) && !empty($_POST["pwd"])) {
        $pwd = $_POST["pwd"];
        if ($pwd!=="C£ramno") {
            die("La password non è corretta!");
          }
      //   echo "Hai selezionato la data: " . $dataMin      ;
     } else {
        echo "Errore: Il campo Password non è stato inviato correttamente.";
      }
    
    
  }

$dateTime = DateTime::createFromFormat('d/m/Y', $dataMin);
$date_min= $dateTime->format('Y-m-d');
$dateTime = DateTime::createFromFormat('d/m/Y', $dataMax);
$date_max = $dateTime->format('Y-m-d');



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