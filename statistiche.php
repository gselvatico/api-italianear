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
            var form = document.getElementById('myForm');
            form.action = action;
            form.target = '_blank';
        }
    </script>
    <script>
        // Funzione per ottenere l'ultimo giorno di un mese
        function getLastDayOfMonth(year, month) {
            return new Date(year, month + 1, 0).getDate();
        }

        // Funzione per aggiornare le date iniziali e finali in base alla selezione
        function updateDates() {
             var selectedMonth =  parseInt(document.getElementById("month").value);
            var selectedYear = document.getElementById("year").value;

            var startDate = new Date(selectedYear, selectedMonth, 1);
            var nextMonth = selectedMonth + 1;
            var endDate = new Date(selectedYear, nextMonth, 1);
            endDate.setDate(endDate.getDate() - 1);

            // Formattare le date come stringhe nel formato "yyyy-mm-dd"
            var formattedStartDate = formatDate(startDate);
            var formattedEndDate = formatDate(endDate);

            document.getElementById("datepicker").value = formattedStartDate;
            document.getElementById("datepickermax").value = formattedEndDate;
        }
        function formatDate(date) {
            var day = date.getDate();
            var month = date.getMonth() + 1;
            var year = date.getFullYear();

            // Aggiungi zeri iniziali se necessario
            day = day < 10 ? '0' + day : day;
            month = month < 10 ? '0' + month : month;

            return day + '/' + month + '/' + year;
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
  <label for="month">Mese:</label>
    <select id="month" onchange="updateDates()">
        <option value="0">Gennaio</option>
        <option value="1">Febbraio</option>
        <option value="2">Marzo</option>
        <option value="3">Aprile</option>
        <option value="4">Maggio</option>
        <option value="5">Giugno</option>
        <option value="6">Luglio</option>
        <option value="7">Agosto</option>
        <option value="8">Settembre</option>
        <option value="9">Ottobre</option>
        <option value="10">Novembre</option>
        <option value="11">Dicembre</option>
    </select>

    <label for="year">Anno:</label>
    <select id="year" onchange="updateDates()">
         <?php
        $currentYear = date('Y');
        for ($i = $currentYear; $i >= 2022; $i--) {
            echo "<option value=\"$i\">$i</option>";
        }
        ?>
    </select>

    <br>
    <input type="text" id="datepicker" placeholder="data iniziale" name="datamin" />
    <input type="text" id="datepickermax" placeholder="data finale" name="datamax" />
    <input type="text" name="pwd" placeholder="pwd" name=pwd" required>
    <br/>
    <!-- Pulsanti per inviare la form -->
    <button type="submit" style="width:150px"
        onclick="setFormAction('api\\stats_it\\log_contatti.php')">Log contatti
    </button>
    <br/>
    <button type="submit" style="width:150px"
        onclick="setFormAction('api\\stats_it\\log_ricerche.php')">Log Ricerche
    </button>
    <br/>
    <button type="submit" style="width:150px"
        onclick="setFormAction('api\\stats_it\\log_iscrizioni.php')">Log Iscrizioni
    </button>
    <br/>
    <button type="submit" style="width:150px"
        onclick="setFormAction('api\\stats_it\\log_iscrizioni_vers.php')">Log Iscrizioni per versione app
    </button>
    <br/>
    <button type="submit" style="width:150px"
        onclick="setFormAction('api\\stats_it\\log_iscrizioni_so.php')">Log Iscrizioni per sistema operativo
    </button>
    <br/>
    <button type="submit" style="width:150px"
        onclick="setFormAction('api\\stats_it\\log_contatti_nazione.php')">Contatti per nazione
    </button>
    <br/>
    <button type="submit" style="width:150px"
        onclick="setFormAction('api\\stats_it\\log_contatti_localita.php')">Contatti per localita
    </button>
    <br/>
    <button type="submit" style="width:150px"
        onclick="setFormAction('api\\stats_it\\contatti_vuoti.php')">Contatti vuoti
    </button>
    <br/>
    <button type="submit" style="width:150px"
        onclick="setFormAction('api\\stats_it\\contatti_per_categoria.php')">Contatti Per Categoria
    </button>
    <br/>
    <button type="submit" style="width:150px"
        onclick="setFormAction('api\\c_rating\\rating_check.php')">Autorizza Rating 
    </button>
    <br/>
    <button type="submit" style="width:150px"
        onclick="setFormAction('api\\c_rating\\rating_replay_check.php')">Autorizza Risposta al rating 
    </button>
    <br/>
    <button type="submit" style="width:150px"
        onclick="setFormAction('api\\c_rating\\ratings.php')">Tutti i Rating 
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