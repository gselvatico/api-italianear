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
    public $replay;
    public $enable_description;
    public $enable_replay;
    public $createddate;
    public $lastmodified;
    public $rating_count;
    public $rating_avg;
    public $rating_last;
    public $nickname;
					
  
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
        }

    function readOne(){
          // query to read single record
        $query = "SELECT											
                    p.`c_rating_id`,
                    p.`contatto_id`,
                    p.`utente_id`,
                    p.`c_rating`,
                    p.`description`,
                    p.`replay`,
                    p.`enable_description`,
                    p.`enable_replay`,
                    p.`createddate`,
                    p.`lastmodified`
                FROM
                    " . $this->table_name . " p    
                WHERE p.contatto_id = :contatto_id
                AND p.utente_id = :utente_id
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
                $this->enable_description = $row['enable_description']; 
                $this->enable_replay = $row['enable_replay']; 
                $this->createddate = $row['createddate']; 
                $this->lastmodified = $row['lastmodified']; 
            }  
    }
    function read(){
        // query to read single record
      $query = "SELECT											
                 p.`c_rating_id`,
                 p.`contatto_id`,
                 p.`utente_id`,
                 p.`c_rating`,
                 p.`description`,
                 p.`replay`,
                 p.`enable_description`,
                 p.`enable_replay`,
                 p.`createddate`,
                 p.`lastmodified`,
                 u.nickname
              FROM
                  " . $this->table_name . " p 
            JOIN utente_it u USING(utente_id)
            WHERE contatto_id = :contatto_id";
        
          // prepare query statement
          $stmt = $this->conn->prepare( $query );

          $stmt->bindParam(":contatto_id", $this->contatto_id);
        
          $stmt->execute();
    
          return $stmt;
  }
    function readAvg(){
        $query = "SELECT		
                    COUNT(p.c_rating_id) rating_count,
                    p.contatto_id,	
                    AVG(p.c_rating) rating_avg,
                    MAX(p.lastmodified)  rating_last
                FROM
                    c_rating p              
                WHERE
                    p.contatto_id = :contatto_id
                AND
                    p.enable_description <> 0
                GROUP BY 
                    p.contatto_id";
    
        $stmt = $this->conn->prepare( $query );
    
        $stmt->bindParam(":contatto_id", $this->contatto_id);
    
        $stmt->execute();
    
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if($row!=null){
            $this->rating_count= $row['rating_count'];
            $this->contatto_id= $row['contatto_id'];
            $this->rating_avg= $row['rating_avg'];
            $this->rating_last= $row['rating_last'];
        }
    }

    function createOrUpdate(){  
        // query to insert record
        $query = "INSERT INTO
                    c_rating
                SET
                    contatto_id=:contatto_id,
                    utente_id=:utente_id,
                    c_rating=:c_rating,
                    description=:udescription,
                    replay=:replay,
                    enable_description=:enable_description,
                    enable_replay=:enable_replay,
                    createddate=:createddate
                ON DUPLICATE KEY UPDATE 
                    c_rating=:c_rating,
                    description=:udescription,
                    replay=:replay,
                    enable_description=:enable_description,
                    enable_replay=:enable_replay;";

        $stmt = $this->conn->prepare($query);
    
        $this->description=htmlspecialchars(strip_tags($this->description));   
    
        $stmt->bindParam(":contatto_id", $this->contatto_id);
        $stmt->bindParam(":utente_id", $this->utente_id);
        $stmt->bindParam(":c_rating", $this->c_rating);
        $stmt->bindParam(":udescription", $this->description);
        $stmt->bindParam(":replay", $this->replay);
        $stmt->bindParam(":enable_description", $this->enable_description);
        $stmt->bindParam(":enable_replay", $this->enable_replay);
        $stmt->bindParam(":createddate", $this->createddate);	
        
        // execute query
        if($stmt->execute()){
            return true;
        }
    
        return false;    
    }

    function update(){  
        // update query
        $query = "UPDATE 
                      c_rating
                  SET               
                      c_rating=:c_rating,
                      description=:description,
                      replay=:replay,
                      enable_description=:enable_description,
                      enable_replay=:enable_replay		
                  WHERE contatto_id = :contatto_id
                  AND utente_id= :utente_id";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->email=htmlspecialchars(strip_tags($this->description));  
    
        // bind new values
    

        $stmt->bindParam(":c_rating", $this->c_rating);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":replay", $this->replay);
        $stmt->bindParam(":enable_description", $this->enable_description);
        $stmt->bindParam(":enable_replay", $this->enable_replay);
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

