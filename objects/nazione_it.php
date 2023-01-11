<?php
class Nazione_it{
  
    // database connection and table name
    private $conn;
    private $table_name = "nazione_it";
  
    // object properties
    public $nazione_id;
    public $nazione;    
    public $iso;
	public $prefisso;    
	public $flag;    
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
				p.nazione_id,				
				p.nazione,		
                p.iso,
				p.flag,
				p.prefisso,
				p.createddate,
				p.lastmodified
            FROM
                " . $this->table_name . " p                
            ORDER BY
                p.nazione ASC";
  
    // prepare query statement
    $stmt = $this->conn->prepare($query);
  
    // execute query
    $stmt->execute();
  
    return $stmt;
	}
    function readActive(){
  
    // select all query
    $query = "SELECT								
				p.nazione_id,				
				p.nazione,	
                p.iso,	
				p.flag,
				p.prefisso,
				p.createddate,
				p.lastmodified
            FROM
                " . $this->table_name . " p
			JOIN localita_it using(nazione_id) 
			JOIN contatto_it using(localita_id)
			GROUP BY p.nazione_id
            ORDER BY
                p.nazione ASC";
  
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
				nazione=:nazione,	
                iso=:iso,			
				prefisso=:prefisso,
				flag=:flag,
				createddate=:createddate";
    // prepare qcreateddate,query
    $stmt = $this->conn->prepare($query);
  
    // sanitize
    $this->nome=htmlspecialchars(strip_tags($this->nazione));   
	$this->nome=htmlspecialchars(strip_tags($this->prefisso));   
    $this->createddate=htmlspecialchars(strip_tags($this->createddate));
  
    // bind values
	
	$stmt->bindParam(":nazione", $this->nazione);	
    $stmt->bindParam(":iso", $this->iso);		
	$stmt->bindParam(":prefisso", $this->prefisso);		
	$stmt->bindParam(":flag", $this->flag);		
	$stmt->bindParam(":createddate", $this->createddate);	
	
    // execute query
    if($stmt->execute()){
        return true;
    }
  
    return false;
	}
    function findByName(){  
        // select all query
        $query = "SELECT								
                    p.nazione_id,				
                    p.nazione,	
                    p.iso,	
                    p.flag,
                    p.prefisso,
                    p.createddate,
                    p.lastmodified
                FROM
                " . $this->table_name . " p        
               WHERE nazione LIKE :nazione";
      
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":nazione", $this->nazione);
        
        // execute query
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if($row!=null){
        // set values to object properties
        $this->nazione_id= $row['nazione_id'];
        $this->nazione= $row['nazione'];
        $this->iso= $row['iso'];
        $this->prefisso= $row['prefisso'];
        $this->flag= $row['flag'];	
        $this->createddate= $row['createddate'];
        $this->lastmodified= $row['lastmodified'];
        }        
       }
}
?>