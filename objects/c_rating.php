<?php
class C_rating{
  
    // database connection and table name
    private $conn;
    private $table_name = "c_rating";
  
    // object properties
    public $c_rating_id;
    public $contatto_id;
    public $utente_id;
    public $c_rating;
    public $description;
    public $createddate;
    public $lastmodified;
    public $rating_count;
    public $rating_avg;
    public $rating_last;
					
  
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
        }

function readOne(){
          // query to read single record
        $query = "SELECT											
                    p.c_rating_id,
                    p.contatto_id,
                    p.utente_id,
                    p.c_rating,
                    p.description,
                    p.createddate,
                    p.lastmodified
                FROM
                    " . $this->table_name . " p    
                WHERE contatto_id = :contatto_id
                AND utente_id = :utente_id
                LIMIT 0,1";
          
            // prepare query statement
            $stmt = $this->conn->prepare( $query );

            $stmt->bindParam(":utente_id", $this->utente_id);
            $stmt->bindParam(":contatto_id", $this->contatto_id);
          
            $stmt->execute();
          
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if($row!=null){
            // set values to object properties
                $this->c_rating_id = $row['c_rating_id']; 
                $this->contatto_id = $row['contatto_id']; 
                $this->utente_id = $row['utente_id']; 
                $this->c_rating = $row['c_rating']; 
                $this->description = $row['description']; 
                $this->createddate = $row['createddate']; 
                $this->lastmodified = $row['lastmodified']; 
            }  
}

function readAvg(){
     $query = "SELECT		
                COUNT(p.c_rating_id) rating_count,
                p.contatto_id,	
                AVG(p.c_rating) rating_avg,
				MAX(p.lastmodified)  rating_last
            FROM
                " . $this->table_name . " p              
            WHERE
                p.contatto_id = ?
            GROUP BY 
                p.contatto_id";
  
    // prepare query statement
    $stmt = $this->conn->prepare( $query );
  
    // bind id of product to be updated
    $stmt->bindParam(1, $this->contatto_id);
  
    // execute query
    $stmt->execute();
  
    // get retrieved row
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if($row!=null){
    // set values to object properties
    $this->rating_count= $row['rating_count'];
	 $this->contatto_id= $row['contatto_id'];
     $this->rating_avg= $row['rating_avg'];
	 $this->rating_last= $row['rating_last'];
    }
}

function create(){  
    // query to insert record
    $query = "INSERT INTO
                " . $this->table_name . "
            SET
                contatto_id=:contatto_id,
                utente_id=:utente_id,
                c_rating=:c_rating,
                description=:description,	       
				createddate=:createddate";
    // prepare qcreateddate,uery
    $stmt = $this->conn->prepare($query);
  
    // sanitize
    $this->email=htmlspecialchars(strip_tags($this->description));   
   
    // bind values
	$stmt->bindParam(":contatto_id", $this->contatto_id);
    $stmt->bindParam(":utente_id", $this->utente_id);
	$stmt->bindParam(":c_rating", $this->c_rating);
	$stmt->bindParam(":description", $this->description);
	$stmt->bindParam(":createddate", $this->createddate);	
	
    // execute query
    if($stmt->execute()){
        return true;
    }
  
    return false;
  
}

function update(){  
    // update query
    $query = "UPDATE " . $this->table_name . "
            SET               
                c_rating=:c_rating,
                description=:description			
            WHERE contatto_id = :contatto_id
            AND utente_id= :utente_id";
  
    // prepare query statement
    $stmt = $this->conn->prepare($query);
  
    // sanitize
    $this->email=htmlspecialchars(strip_tags($this->description));  
  
    // bind new values
  

    $stmt->bindParam(":c_rating", $this->c_rating);
    $stmt->bindParam(":description", $this->description);
	$stmt->bindParam(":contatto_id", $this->contatto_id);
	$stmt->bindParam(":utente_id", $this->utente_id);
	
    // execute the query
    if($stmt->execute()){
        return true;
    }
  
    return false;
}
    
function delete(){
  
    // delete query
    $query = "DELETE FROM " . $this->table_name . 
            " WHERE contatto_id = :contatto_id
            AND utente_id = :utente_id";
  
    // prepare query
    $stmt = $this->conn->prepare($query);  
  
    // bind id of record to delete
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

