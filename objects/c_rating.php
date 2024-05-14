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
    public $comment;
    public $replay;
    public $enable_comment;
    public $enable_replay;
    public $reject_comment;
    public $reject_replay;
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
                    p.`comment`,
                    p.`replay`,
                    p.`enable_comment`,
                    p.`enable_replay`,
                    p.`reject_comment`,
                    p.`reject_replay`,
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
                $this->comment = $row['comment'];
                $this->replay = $row['replay']; 
                $this->enable_comment = $row['enable_comment']; 
                $this->enable_replay = $row['enable_replay']; 
                $this->reject_comment = $row['reject_comment']; 
                $this->reject_replay = $row['reject_replay']; 
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
                 p.`comment`,
                 p.`replay`,
                 p.`enable_comment`,
                 p.`enable_replay`,
                 p.`reject_comment`,
                 p.`reject_replay`,
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
                    p.enable_comment <> 0
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
                    comment=:ucomment,
                    replay=:replay,
                    enable_comment=:enable_comment,
                    enable_replay=:enable_replay,
                    reject_comment=:reject_comment,
                    reject_replay=:reject_replay,
                    createddate=:createddate
                ON DUPLICATE KEY UPDATE 
                    c_rating=:c_rating,
                    comment=:ucomment,
                    replay=:replay,
                    enable_comment=:enable_comment,
                    enable_replay=:enable_replay,
                    reject_comment=:reject_comment,
                    reject_replay=:reject_replay;";

        $stmt = $this->conn->prepare($query);
    
        $this->comment=htmlspecialchars(strip_tags($this->comment));   
    
        $stmt->bindParam(":contatto_id", $this->contatto_id);
        $stmt->bindParam(":utente_id", $this->utente_id);
        $stmt->bindParam(":c_rating", $this->c_rating);
        $stmt->bindParam(":ucomment", $this->comment);
        $stmt->bindParam(":replay", $this->replay);
        $stmt->bindParam(":enable_comment", $this->enable_comment);
        $stmt->bindParam(":enable_replay", $this->enable_replay);
        $stmt->bindParam(":reject_comment", $this->reject_comment);
        $stmt->bindParam(":reject_replay", $this->reject_replay);
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
                      comment=:comment,
                      replay=:replay,
                      enable_comment=:enable_comment,
                      enable_replay=:enable_replay,
                      reject_comment=:reject_comment,
                      reject_replay=:reject_replay
                  WHERE contatto_id = :contatto_id
                  AND utente_id= :utente_id";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->email=htmlspecialchars(strip_tags($this->comment));  
    
        // bind new values
    

        $stmt->bindParam(":c_rating", $this->c_rating);
        $stmt->bindParam(":comment", $this->comment);
        $stmt->bindParam(":replay", $this->replay);
        $stmt->bindParam(":enable_comment", $this->enable_comment);
        $stmt->bindParam(":enable_replay", $this->enable_replay);
        $stmt->bindParam(":reject_comment", $this->reject_comment);
        $stmt->bindParam(":reject_replay", $this->reject_replay);
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
    function readAll($par){
        // query to read single record
      $query = "SELECT											
                 p.`c_rating_id`,
                 p.`contatto_id`,
                 p.`utente_id`,
                 p.`c_rating`,
                 p.`comment`,
                 p.`replay`,
                 p.`enable_comment`,
                 p.`enable_replay`,
                 p.`reject_comment`,
                 p.`reject_replay`,
                 p.`createddate`,
                 p.`lastmodified`,
                 c.nome_negozio,
                 c.localita,
                 u.nickname
              FROM
                 c_rating  p
                   JOIN utente_it u USING(utente_id)
                   join `contatto_it` `c` using (contatto_id)
            ";
        if($par == 'rating')
            {
                $query .= " WHERE p.`enable_comment` = 0 ";
                $query .= " AND p.`reject_comment` = 0 ";
                $query .= " ORDER BY p.createddate DESC ";
            } 
        if($par == 'replay')
            {
                $query .= " WHERE p.`enable_comment` = 1 ";
                $query .= " AND p.`enable_replay` = 0 ";
                $query .= " AND p.`reject_replay` = 0 ";
                $query .= " ORDER BY p.createddate DESC ";
            } 
         if($par == 'reject_comment')
            {
                $query .= " WHERE p.`reject_comment` = 1 ";
                $query .= " ORDER BY p.createddate DESC ";
            } 
         if($par == 'reject_replay')
            {
                $query .= " WHERE p.`reject_replay` = 1 ";
                $query .= " ORDER BY p.createddate DESC ";
            } 
        if($par == 'dateinifine')
            {
                $query .= " WHERE p.createddate >= :dateMin";
                $query .= " AND p.createddate <= :dateMax";
                $query .= " ORDER BY p.createddate DESC ";
              
            } 
             
        // prepare query statement
        
        $stmt = $this->conn->prepare( $query );
        if($par == 'dateinifine')
            {
                 $stmt->bindParam(":dateMin", $this->date_min);
                 $stmt->bindParam(":dateMax", $this->date_max); 
            }
        $stmt->execute();
        // $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $stmt;
    }
    function autorizza_comment($c_rating_id){
        $esito;
        // delete query
        $query = "UPDATE  c_rating 
                    SET enable_comment = 1 
                 WHERE c_rating_id = :c_rating_id
               ;";
    
        // prepare query
        $stmt = $this->conn->prepare($query);  
        $stmt->bindParam(":c_rating_id",$c_rating_id);
        if($stmt->execute()){
            return  'OK ' . $c_rating_id;
        }
    
        return false;
    }
    function autorizza_replay($c_rating_id){
        $esito;
        // delete query
        $query = "UPDATE  c_rating 
                    SET enable_replay = 1 
                 WHERE c_rating_id = :c_rating_id
               ;";
    
        // prepare query
        $stmt = $this->conn->prepare($query);  
        $stmt->bindParam(":c_rating_id",$c_rating_id);
        if($stmt->execute()){
            return  'OK ' . $c_rating_id;
        }
    
        return false;
    }
   function reject_comment($c_rating_id){
        $esito;
        // delete query
        $query = "UPDATE  c_rating 
                    SET reject_comment = 1 
                 WHERE c_rating_id = :c_rating_id
               ;";
    
        // prepare query
        $stmt = $this->conn->prepare($query);  
        $stmt->bindParam(":c_rating_id",$c_rating_id);
        if($stmt->execute()){
            return  'OK ' . $c_rating_id;
        }
    
        return false;
    }
    function reject_replay($c_rating_id){
        $esito;
        // delete query
        $query = "UPDATE  c_rating 
                    SET reject_replay = 1 
                 WHERE c_rating_id = :c_rating_id
               ;";
    
        // prepare query
        $stmt = $this->conn->prepare($query);  
        $stmt->bindParam(":c_rating_id",$c_rating_id);
        if($stmt->execute()){
            return  'OK ' . $c_rating_id;
        }
    
        return false;
    }

    function addReplay(){  
        // update query
        $query = "UPDATE 
                      c_rating
                  SET
                      replay=:replay,                      
                      enable_replay=0 
                  WHERE c_rating_id = :rating_id;";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->email=htmlspecialchars(strip_tags($this->comment));  
    
        // bind new values
    
        $stmt->bindParam(":rating_id", $this->c_rating_id);     
        $stmt->bindParam(":replay", $this->replay);
    
        
        // execute the query
        if($stmt->execute()){
            return true;
        }
    
        return false;
    }
}
?>

