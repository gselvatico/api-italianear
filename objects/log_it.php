<?php
class Log_it{
  
    // database connection and table name
    private $conn;
    private $table_name = "log_it";
  
    // object properties
    public $log_id;
    public $userID;
    public $gps;
    public $lista_categorie;
    public $ricerca_gps;
    public $lista_contatti;
    public $tipo_utente;
    public $vers;
    public $so;
    public $ndr;
    public $createddate;
  
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
	// read products
function read(){
  
    // select all query
    $query = "SELECT								
				p.log_id,
                p.userID,
                p.gps,
                p.lista_categorie,
                p.ricerca_gps,
                p.lista_contatti,
                p.tipo_utente,
                p.vers,
                p.so,
                p.ndr,
                p.createddate
            FROM
                " . $this->table_name . " p ";
  
    // prepare query statement
    $stmt = $this->conn->prepare($query);
  
    // execute query
    $stmt->execute();
  
    return $stmt;
	}

function create(){  
    // query to insert record
    $query = "INSERT INTO
                " . $this->table_name . "
            SET               
            userID=:userID,
            gps=:gps,
            lista_categorie=:lista_categorie,
            ricerca_gps=:ricerca_gps,
            lista_contatti=:lista_contatti,          
            vers=:vers,
            so=:so";
    // prepare qcreateddate,query
    $stmt = $this->conn->prepare($query);
  
     // bind values
	
	$stmt->bindParam(":userID", $this->userID);	
	$stmt->bindParam(":gps", $this->gps);	
	$stmt->bindParam(":lista_categorie", $this->lista_categorie);	
    $stmt->bindParam(":ricerca_gps", $this->ricerca_gps);	
    $stmt->bindParam(":lista_contatti", $this->lista_contatti);	
  //  $stmt->bindParam(":tipo_utente", $this->tipo_utente);	
    $stmt->bindParam(":vers", $this->vers);	
    $stmt->bindParam(":so", $this->so);	
 //    $stmt->bindParam(":ndr", $this->ndr);	
    // execute query
    if($stmt->execute()){
        return true;
    }
  
    return false;
	}   
}
?>