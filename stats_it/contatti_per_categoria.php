<html>
<head>
    <meta name="robots" content="noindex">
    <title>Ricerche Contatti Italianear</title>
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
        /* th, td {
             padding: 5px;
        } */
        th {
            text-align: left;
            }
    </style>
    <script>
        function setFormAction(action) {
        document.getElementById('myForm').action = action;
        }
    </script>
<?php
error_reporting(0);
ini_set('display_errors', 0);
include_once '../config/database.php';
include_once '../objects/stats_it.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST["pwd"]) && !empty($_POST["pwd"])) {
        $pwd = $_POST["pwd"];
        if ($pwd!=="C£ramno") {
            die("La password non è corretta!");
          }
      //   echo "Hai selezionato la data: " . $dataMin      ;
     } else {
        die("Errore: Il campo Password non è stato inviato correttamente.");
      }  
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
$stmt = $stats_it->contatti_per_categoria();

$num =$stmt->rowCount();
  
// check if more than 0 record found
if($num>0){
  
    // stats_its array
    $stats_its_arr=array();
    $stats_its_arr["records"]=array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
  
        $stats_it_item=array(       
			"n" => $n,
			"categoria" => $categoria,
			"father" => $father            
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
        echo '<h2>Contatti per Categoria</h2>';


        echo '<form id="myForm" method="post" action="">';
        // Genera la tabella HTML
        echo '<table style: font-family: Arial, sans-serif;
        font-size: 8px; >';
        echo '<thead><tr><th>n</th><th>father</th><th>categoria</th></tr></thead>';
        echo '<tbody>';
        foreach ($records as $record) {
            $parametro=$record['user_id'];
            $cmd="'elimina_contatto.php?userid=$parametro')";
            echo '<tr>';
            echo '<td>' . htmlspecialchars($record['n']) . '</td>';
            echo '<td>' . htmlspecialchars($record['categoria']) . '</td>';
            echo '<td>' . htmlspecialchars($record['father']) . '</td>';
            echo '</tr>';
        }
        echo '</form>';
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