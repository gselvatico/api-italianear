<?php
class Stats_it{
  
    // database connection and table name
    private $conn;
    private $table_name = "log_it";
  
    // object properties
    public $email;
    public $nlog;
    public $lastdate;
    public $totaleUtenti;
    public $totaleRicerche;
    public $nomeContatto;
    public $nomeAttivita;
    public $localita;
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }	
        
        function log_ricerche(){
           
            $query = "SELECT				
                        u.email,
                        count(l.userid) nlog,
                        max(l.createddate) lastdate                       
                    FROM
                        " . $this->table_name . " l 
                        JOIN utente_it u using (userid)
                        WHERE 
                        l.createddate between :dateMin AND :dateMax
                        GROUP BY u.userID
                        ORDER BY max(l.createddate) DESC			
                   "                  
                ;
            
            $stmt = $this->conn->prepare( $query );      
            $stmt->bindParam(":dateMin", $this->date_min);
            $stmt->bindParam(":dateMax", $this->date_max);
            
            // execute query
            $stmt->execute();
            return $stmt;	  
        }    
        function totali_log_ricerche(){
      
            $query = "select
                 (SELECT COUNT(distinct(userid))  
                       FROM italiare_italianear.log_it l
                       join utente_it u using (userid)
                  WHERE 
                  l.createddate between :dateMin AND :dateMax) n,
                (SELECT COUNT(userid)
                        FROM italiare_italianear.log_it l
                        join utente_it u using (userid)
                    WHERE 
                    l.createddate between :dateMin AND :dateMax) t;			
                   "                  
                ;
            
            $stmt = $this->conn->prepare( $query );      
            $stmt->bindParam(":dateMin", $this->date_min);
            $stmt->bindParam(":dateMax", $this->date_max);
            
            // execute query
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if($row!=null){
                // set values to object properties
                $this->totaleUtenti= $row['n'];
                $this->totaleRicerche= $row['t'];
            }
            
        }  
        function log_contatti(){
           
            $query = "SELECT count(l.utente_id) nUtenti, 
                        c.nome nome_contatto,
                        c.nome_negozio nome_attiivita,
                        c.localita,
                        MAX(l.createdtime) ultimo
                    FROM 
                        log_contatto_it l
                    JOIN 
                        contatto_it c USING (contatto_id)
                    WHERE 
                        l.createdtime between :dateMin AND :dateMax
                    GROUP BY 
                        l.contatto_id
                    ORDER BY nUtenti DESC;                    
                   "                  
                ;
            
            $stmt = $this->conn->prepare( $query );      
            $stmt->bindParam(":dateMin", $this->date_min);
            $stmt->bindParam(":dateMax", $this->date_max);
            
            // execute query
            $stmt->execute();
            return $stmt;	  
        }  
    }
    ?>