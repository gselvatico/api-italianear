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
    function log_nazione(){
        $query = "SELECT 
            count(c.userid) n_c, 
            n.nazione,
            MAX(c.createddate) data_iscrizione 
        from contatto_it c 
        LEFT JOIN nazione_it n on n.ISO =c.nazioneiso
        where 
            c.createddate >= :dateMin 
        AND 
            DATE(c.createddate) <= :dateMax            
        group by n.nazione            
        UNION 
        SELECT count(c.userid) n_c, '','--'
        FROM  contatto_it c 
        where 
            c.createddate >= :dateMin 
        AND 
           DATE(c.createddate)<=:dateMax
        "              
        ;

        $stmt = $this->conn->prepare( $query );    
        $stmt->bindParam(":dateMin", $this->date_min);
        $stmt->bindParam(":dateMax", $this->date_max);  
        // execute query
        $stmt->execute();
        return $stmt;	  
    }
    function log_localita(){
        $query = "        SELECT 
            (@row_number := @row_number + 1)  n,
            t.n_c,
            t.localita,
            t.data_iscrizione            
        FROM
            (SELECT 
                COUNT(c.userid) AS n_c,
                c.localita,
                MAX(c.createddate) AS data_iscrizione,
                COUNT(localita) AS n
            FROM
                contatto_it c      
        where 
            c.createddate >= :dateMin 
        AND 
            DATE(c.createddate) <= :dateMax            
        group by c.localita ) as t,
        (SELECT @row_number := 0) AS r           
        UNION 
        SELECT '', count(c.userid) n_c, @row_number ,''
        FROM  contatto_it c 
        where 
            c.createddate >= :dateMin 
        AND 
           DATE(c.createddate)<=:dateMax
        "              
        ;

        $stmt = $this->conn->prepare( $query );    
        $stmt->bindParam(":dateMin", $this->date_min);
        $stmt->bindParam(":dateMax", $this->date_max);  
        // execute query
        $stmt->execute();
        return $stmt;	  
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
                        AND u.is_admin = 0
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
                       FROM log_it l
                       join utente_it u using (userid)
                  WHERE 
                  l.createddate between :dateMin AND :dateMax  AND u.is_admin = 0) n,
                (SELECT COUNT(userid)
                        FROM log_it l
                        join utente_it u using (userid)
                    WHERE 
                    l.createddate between :dateMin AND :dateMax  AND u.is_admin = 0) t;			
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
                    JOIN
	                    utente_it u using (utente_id)
                    WHERE 
                        l.createdtime between :dateMin AND :dateMax
                        AND u.is_admin = 0
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
    function log_iscrizioni(){
           
            $query = "SELECT 
                        count(u.userid) n_u,
                        count(c.userid) n_c, 
                        date(data_PRU) data_iscrizione                      
                        FROM 
                            (utente_it u                         
                        left join `contatto_it` `c` on((convert(`u`.`userID` using utf8mb4) = `c`.`userID`))) 
                        where DATE (u.data_PRU )
                        between DATE(:dateMin) AND DATE(:dateMax)                        
                        group by DATE( data_PRU)                 
                        UNION 
                        SELECT count(u.userid) n_u,count(c.userid) n_c, '--'
                        FROM (utente_it u                         
                        left join `contatto_it` `c` on((convert(`u`.`userID` using utf8mb4) = `c`.`userID`)))                      
                        where 
                            u.data_PRU >= :dateMin 
                        AND 
                            DATE(u.data_PRU)<=:dateMax
                         ORDER BY data_iscrizione DESC
                   "                  
                ;
            
            $stmt = $this->conn->prepare( $query );      
            $stmt->bindParam(":dateMin", $this->date_min);
            $stmt->bindParam(":dateMax", $this->date_max);
            
            // execute query
            $stmt->execute();
            return $stmt;	  
    } 
    function log_iscrizioni_vers(){
           
            $query = "SELECT 
                        count(u.userid) n_u,
                        LEFT(so,3) so,
                        vers,
                        date(data_PRU) data_iscrizione                      
                        FROM 
                            utente_it u                           
                        where 
                            u.data_PRU >= :dateMin 
                        AND 
                            DATE(u.data_PRU)<=:dateMax
                        group by vers,  LEFT(so,3) ,data_iscrizione
                        ORDER BY data_iscrizione DESC
                   "                  
                ;
            
            $stmt = $this->conn->prepare( $query );      
            $stmt->bindParam(":dateMin", $this->date_min);
            $stmt->bindParam(":dateMax", $this->date_max);
            
            // execute query
            $stmt->execute();
            return $stmt;	  
    } 
    function log_iscrizioni_so(){
           
            $query = "SELECT 
                        count(u.userid) n_u,
                        LEFT(so,3) so
                        FROM 
                            utente_it u                           
                        where 
                            u.data_PRU >= :dateMin 
                        AND 
                            DATE(u.data_PRU)<=:dateMax
                        group by LEFT(so,3)                        
                   ";
            
            $stmt = $this->conn->prepare( $query );      
            $stmt->bindParam(":dateMin", $this->date_min);
            $stmt->bindParam(":dateMax", $this->date_max);
            
            // execute query
            $stmt->execute();
            return $stmt;	  
    } 
    function contatti_vuoti(){
            $query = "SELECT 
                u.email,u.tipo,c.userID,c.lastmodified
            FROM 
                (utente_it u                         
                inner join `contatto_it` `c` on((convert(`u`.`userID` using utf8mb4) = `c`.`userID`))) 
            where 
                latitudine=0 and longitudine=0"                  
            ;

            $stmt = $this->conn->prepare( $query );      
            // execute query
            $stmt->execute();
            return $stmt;	  
    }
    function elimina_contatto_vuoto($user_ID){
            $esito;
            $query = "UPDATE
                 utente_it                
            SET 
                tipo = 'U'
            where 
                userID = :userID;
            DELETE                
            FROM 
                contatto_it            
            where 
                userID = :userID;
            ";    
       
             $stmt = $this->conn->prepare( $query );      
             $stmt->bindParam(":userID", $user_ID);
             $stmt->execute();            

            return  'OK '.$user_ID;	  
    }
    function log_contatto_click(){
           
            $query = "SELECT count(l.contatto_id) n_click,
                    MAX(createdtime) ultimo,                      
                                ( SELECT count(distinct l.utente_id)
                                FROM 
                                    log_contatto_it  l
                                JOIN 
                                    contatto_it c USING (contatto_id)	
                                join
                                    utente_it u using (utente_id)
                                WHERE                         
                                    c.userID = :userID
                                and   u.is_admin = 0
                            ) n_utenti
                    FROM 
                        log_contatto_it  l
					JOIN 
                        contatto_it c USING (contatto_id)
					JOIN
	                    utente_it u using (utente_id)
                    WHERE                         
                        c.userID = :userID
                       AND u.is_admin = 0                                   
                   "                  
                ;
               $stmt = $this->conn->prepare( $query );

            $stmt->bindParam(":userID", $this->userID);   
          
            $stmt->execute();
          
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if($row!=null){
            // set values to object properties
                $this->n_click = $row['n_click']; 
                $this->ultimo = $row['ultimo']; 
                $this->n_utenti = $row['n_utenti'];   
            }  
           
          return $stmt;
    }   
    function contatti_per_categoria(){
           
            $query = "SELECT COUNT(*) n,fa.categoria,ca.categoria father
                        from contatto_it co
                        JOIN categoria_it ca USING(categoria_id)
                        JOIN categoria_it fa on ca.father_id=fa.categoria_id
                        GROUP by ca.categoria_id,fa.categoria_id
                        ORDER BY n DESC;                      
                   ";
            
            $stmt = $this->conn->prepare( $query );      
            // execute query
            $stmt->execute();
            return $stmt;	  
    }     
    }
    ?>