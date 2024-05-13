<html>
<head>
    <meta name="robots" content="noindex">
    <title>Controllo Rating Contatti Italianear</title>
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
     <script>
        function setActionReject(url) {       
          window.location.href = url;
        }
    </script>
<?php
error_reporting(0);
ini_set('display_errors', 0);
include_once '../config/database.php';
include_once '../objects/c_rating.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST["pwd"]) && !empty($_POST["pwd"])) {
        $pwd = $_POST["pwd"];
        if ($pwd!=="C£ramno") {
            die("La password non è corretta!");
          }
      //   echo "Hai selezionato la data: " . $dataMin      ;
     } else {
        die("Errore: Il campo Password 22 non è stato inviato correttamente.");
      }  
  }


// instantiate database and c_rating object
$database = new Database();
if(isset($data->isTest) && $data->isTest)
{
    $db = $database->getTestConnection();
}else {
    $db = $database->getConnection();  
}

// initialize object
$c_rating = new C_rating($db);

$stmt = $c_rating->readAll('rating');

$num =$stmt->rowCount();
  
// check if more than 0 record found
if($num>0){
    $nr=0;
    // c_ratings array
    $c_ratings_arr=array();
    $c_ratings_arr["records"]=array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        
        $c_rating_item=array(       
			"c_rating_id" => $c_rating_id,
			"contatto_id" => $contatto_id,
			"utente_id" => $utente_id,
            "c_rating"=>$c_rating,
            "description"=>$description,
            "replay"=>$replay,
            "enable_description"=>$enable_description,
            "enable_replay"=>$enable_replay,
            "reject_description"=>$reject_description,
            "reject_replay"=>$reject_replay,
            "nickname"=>$nickname,
            "nome_negozio"=>$nome_negozio,
            "localita"=>$localita,
            "createddate"=>date("d/m/Y", strtotime($createddate)),
            "lastmodified"=>date("d/m/Y", strtotime($lastmodified))
        );
   
        array_push($c_ratings_arr["records"], $c_rating_item);
    }
  
    // set response code - 200 OK
    http_response_code(200);
  
    // show c_ratings data in json format
    // echo json_encode($c_ratings_arr);
    $js=json_encode($c_ratings_arr);

    $data = json_decode($js, true);
    if ($data === null) {
        die('Errore nella decodifica del JSON.');
    }
    
    // Estrai l'array di record dal JSON
    $records = $data['records'];
    // echo count($records) ;
    if (count($records) === 0) {
        echo 'Nessun dato da mostrare.';
    } else {
       
        echo '<h2>Rating da autorizzare</h2>';
        echo '<form id="myForm" method="post" action="">';
        // Genera la tabella HTML
        echo '<table  border=1  style=" border-collapse:collapse" style: font-family: Arial, sans-serif;
        font-size: 8px; >';
        echo '<thead><tr>';
        // <th>c_rating_id</th>
        // <th>contatto_id</th>
        // <th>utente_id</th>
        echo '<th>c_rating</th>
        <th>description</th>
        <th>replay</th>
        <th>autorizza description</th>
        <th>autorizza replay</th>
        <th>respingi description</th>
        <th>respingi replay</th>
        <th>nickname</th>
        <th>attività</th>
        <th>luogo</th>
        <th>data</th>
        </tr></thead>';
        echo '<tbody>';
       
        foreach ($records as $record) {
            // echo '<tr><td>'.$nr.'</td></tr>';
            $parametro=$record['c_rating_id'];
            $cmdAuth="'autorizza_description.php?c_rating_id=$parametro')";
            $cmdRej="'reject_description.php?c_rating_id=$parametro')";
            echo '<tr>';
            // echo '<td>' . $record['c_rating_id'] . '</td>';
            // echo '<td>' . $record['contatto_id'] . '</td>';
            // echo '<td>' . $record['utente_id'] . '</td>';                 
            echo '<td>' . $record['c_rating'] . '</td>';
            echo '<td>' . $record['description'] . '</td>';
            echo '<td>' . $record['replay'] . '</td>';
            echo '<td>' . $record['enable_description'] . '</td>';
            echo '<td>' . $record['enable_replay'] . '</td>';
            echo '<td>' . $record['reject_description'] . '</td>';
            echo '<td>' . $record['reject_replay'] . '</td>';
            echo '<td>' . $record['nickname'] . '</td>';   
            echo '<td>' . $record['nome_negozio'] . '</td>';
            echo '<td>' . $record['localita'] . '</td>'; 
            echo '<td>' . htmlspecialchars($record['lastmodified']) . '</td>';
            echo '<td><button type="submit" onclick="setFormAction(';
            echo $cmdAuth;
            echo '"';
            echo '>autorizza</button></td>';
            echo '<td><button type="button" onclick="setActionReject(';
            echo $cmdRej;
            echo '"';
            echo '>respingi</button></td>';
            
            echo '</tr>';
            $nr=$nr+1;
        }
        echo '</form>';
        echo '</tbody>';
        echo '</table>';
        http_response_code(200);
    }

}
  
// no c_ratings found will be here
else{
  
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user no products found
    echo json_encode(
        array("message" => "Nessun rating trovato.")
    );
  //  echo $stmt;
}

?>


</body>
</html>