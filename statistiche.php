<html>
<head>
    <meta name="robots" content="noindex">
    <title>Ricerche Contatti Italianear</title>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        $( function() {
        $( "#datepicker" ).datepicker(
             {dateFormat: "dd/mm/yy" }
        );
        } );
        $( function() {
        $( "#datepickermax" ).datepicker(
            {dateFormat: "dd/mm/yy" }
        );
        } );
    </script>
    <script>
        function setFormAction(action) {
        document.getElementById('myForm').action = action;
        }
    </script>
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
<p>Statistiche ItaliaNear</p>
<!-- <form method="POST" action="api\stats_it\log_contatti.php">

  <input type="submit" value="Invia" />
  
</form> -->
<form id="myForm" method="post" action="">
    <!-- Contenuti della form... -->
    <input type="text" id="datepicker" placeholder="data iniziale" name="datamin" />
    <input type="text" id="datepickermax" placeholder="data finale" name="datamax" />
    <br>
    <input type="text" name="pwd" placeholder="pwd" name=pwd" required>
        
    <!-- Pulsanti per inviare la form -->
    <button type="submit" 
        onclick="setFormAction('api\\stats_it\\log_contatti.php')">Log contatti
    </button>
    <button type="submit" 
        onclick="setFormAction('api\\stats_it\\log_ricerche.php')">Log Ricerche
    </button>
  </form>

<?php
// if (isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
//     // Il codice qui dentro verrà eseguito solo se la chiave 'REQUEST_METHOD' è definita e il suo valore è "POST"
//     // ...
//     if ($_SERVER["REQUEST_METHOD"] == "POST") {
//         $dataSelezionata = $_POST["data"];
//      //   echo "Hai selezionato la data: " . $dataSelezionata;
//     }  
// }

?>
</body>
</html>