<?php
class Localita_it{
  
    // database connection and table name
    private $conn;
    private $table_name = "localita_it";
  
    // object properties
    public $localita_id;
    public $localita;    
	public $nazione_id;
	public $createddate;
	public $lastmodified;
					
  
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
	// read products
function read(){
  
    // select all query
    $query = "SELECT								
				p.localita_id,				
				p.localita,
				p.nazione_id,
				p.createddate,
				p.lastmodified
            FROM
                " . $this->table_name . " p                
            ORDER BY
                p.localita ASC";
  
    // prepare query statement
    $stmt = $this->conn->prepare($query);
  
    // execute query
    $stmt->execute();
  
    return $stmt;
	}
function SelectByNazione(){
  
    // select all query
    $query = "SELECT								
				p.localita_id,				
				p.localita,
				p.nazione_id,
				p.createddate,
				p.lastmodified
            FROM
                " . $this->table_name . " p 
			WHERE p.nazione_id = ?				
            ORDER BY
                p.localita ASC";
  
    // prepare query statement
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(1, $this->nazione_id);
    // execute query
    $stmt->execute();
  
    return $stmt;
	}
function create(){
  
    // query to insert record
    $query = "INSERT INTO
                " . $this->table_name . "
            SET               
				localita=:localita,				
				nazione_id=:nazione_id,		
				createddate=:createddate";
    // prepare qcreateddate,query
    $stmt = $this->conn->prepare($query);
  
    // sanitize
    $this->nome=htmlspecialchars(strip_tags($this->localita));   
    $this->createddate=htmlspecialchars(strip_tags($this->createddate));
  
    // bind values
	
	$stmt->bindParam(":localita", $this->localita);	
	$stmt->bindParam(":nazione_id", $this->nazione_id);	
	$stmt->bindParam(":createddate", $this->createddate);	
	
    // execute query
    if($stmt->execute()){
        return true;
    }
  
    return false;
	}
function FindByName(){  
        
        $query = "SELECT								
                    p.localita_id,				
                    p.localita,
                    p.nazione_id,
                    p.createddate,
                    p.lastmodified
                FROM
                    " . $this->table_name . " p 
                WHERE p.localita LIKE (?)";
      
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->localita);
        // execute query
        $stmt->execute();
      
        // get retrieved row
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if($row!=null){
    // set values to object properties
    $this->localita_id= $row['localita_id'];
	 $this->localita= $row['localita'];
     $this->nazione_id= $row['nazione_id'];
	 $this->createddate= $row['createddate'];
	 $this->lastmodified= $row['lastmodified'];
    }
    }   
}
?>