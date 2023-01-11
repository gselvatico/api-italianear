<?php
class Log_contatto_it{
  
    // database connection and table name
    private $conn;
    private $table_name = "log_contatto_it";
  
    // object properties
    public $log_contatto_id;
    public $utente_id;
    public $contatto_id;  
    public $createddate;
  
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
        }
	
    function read(){
  
    // select all query
    $query = "SELECT								
				p.log_contatto_id,
                p.utente_id,
                p.contatto_id,
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
            utente_id=:utente_id,
            contatto_id=:contatto_id";
    // prepare qcreateddate,query
    $stmt = $this->conn->prepare($query);  
     // bind values	
	$stmt->bindParam(":utente_id", $this->utente_id);	
	$stmt->bindParam(":contatto_id", $this->contatto_id);		
    // execute query
    if($stmt->execute()){
        return true;
    }
  
    return false;
	}   
}
?>