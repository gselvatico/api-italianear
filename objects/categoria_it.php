<?php
class Categoria_it{
  
    // database connection and table name
    private $conn;
    private $table_name = "categoria_it";
  
    // object properties
    public $categoria_id;
    public $father_id;
    public $categoria;    
	public $createddate;
	public $lastmodified;
	public $lat_min;
	public $lat_max;
	public $lng_min;
	public $lng_max;
					
  
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
	// read products
function read(){
  
    // select all query
    $query = "SELECT								
				p.categoria_id,	
                p.father_id,					
				p.categoria,
				p.createddate,
				p.lastmodified
            FROM
                " . $this->table_name . " p                
            ORDER BY
                p.categoria ASC";
  
    // prepare query statement
    $stmt = $this->conn->prepare($query);
  
    // execute query
    $stmt->execute();
  
    return $stmt;
	}
function readOne(){
  
    // query to read single record
    $query = "SELECT											
				p.categoria,
                p.father_id,	
				p.createddate,
				p.lastmodified
            FROM
                " . $this->table_name . " p    
            WHERE
                p.categoria_id = ?
            LIMIT
                0,1";
  
    // prepare query statement
    $stmt = $this->conn->prepare( $query );
  
    // bind id of product to be updated
    $stmt->bindParam(1, $this->categoria_id);
  
    // execute query
    $stmt->execute();
  
    // get retrieved row
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
  
    // set values to object properties
    $this->categoria = $row['categoria'];   
}
function selectByCitta(){  
   
    $query = "SELECT											
				p.categoria_id,
                p.father_id,	
				p.categoria
            FROM
                " . $this->table_name . " p    
			LEFT JOIN contatto_it co on co.categoria_id = p.categoria_id
            WHERE				
                co.localita_id= ?	
				OR p.categoria_id=1				
			GROUP BY p.categoria_id
            ORDER BY p.categoria";
    
    // prepare query statement
    $stmt = $this->conn->prepare( $query );
  
    // bind id of product to be updated
    $stmt->bindParam(1, $this->localita_id);
  
    // execute query
    $stmt->execute();
  
	return $stmt;
    // get retrieved row
//    $row = $stmt->fetch(PDO::FETCH_ASSOC);
//  
//    // set values to object properties
//    $this->categoria_id = $row['categoria_id'];   
//	$this->categoria = $row['categoria'];   
}
function readbylatlng(){  
    $queryMaster = "";	
    $query = "SELECT											
				f.categoria_id,
                p.father_id,	
				f.categoria
            FROM
                " . $this->table_name . " p    
			LEFT JOIN contatto_it co on co.categoria_id = p.categoria_id
            INNER JOIN " . $this->table_name . " f on p.father_id =f.categoria_id			
            WHERE (co.latitudine between :latMin AND :latMax) 
            AND (co.longitudine between :lngMin and :lngMax) 
            GROUP BY f.categoria_id
            ORDER BY f.categoria";
    
    // prepare query statement
    $stmt = $this->conn->prepare( $query );
  
    // bind id of product to be updated
    $stmt->bindParam(":latMin", $this->lat_min);
    $stmt->bindParam(":latMax", $this->lat_max);
    $stmt->bindParam(":lngMin", $this->lng_min);
    $stmt->bindParam(":lngMax", $this->lng_max);
  
    // execute query
    $stmt->execute();
  
	return $stmt;
}
function create(){
  
    // query to insert record
    $query = "INSERT INTO
                " . $this->table_name . "
            SET               
				categoria=:categoria,				
				createddate=:createddate";
    // prepare qcreateddate,query
    $stmt = $this->conn->prepare($query);
  
    // sanitize
    $this->nome=htmlspecialchars(strip_tags($this->categoria));   
    $this->createddate=htmlspecialchars(strip_tags($this->createddate));
  
    // bind values
	
	$stmt->bindParam(":categoria", $this->categoria);	
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
                " . $this->table_name . "
            SET
                categoria = :categoria
            WHERE
                categoria_id = :categoria_id";
  
    // prepare query statement
    $stmt = $this->conn->prepare($query);
  
    // sanitize
    $this->categoria=htmlspecialchars(strip_tags($this->categoria));
	$this->categoria_id=trim($this->categoria_id);
  
  
    // bind new values
    $stmt->bindParam(':categoria', $this->categoria);
    $stmt->bindParam(':categoria_id', $this->categoria_id);
  
    // execute the query
    if($stmt->execute()){
        return true;
    }
  
    return false;
}
function delete(){
  
    // delete query
    $query = "DELETE FROM " . $this->table_name . " WHERE categoria_id = ?";
  
    // prepare query
    $stmt = $this->conn->prepare($query);  
  
    // bind id of record to delete
    $stmt->bindParam(1, $this->categoria_id);
  
    // execute query
    if($stmt->execute()){
        return true;
    }
  
    return false;
	}
}
?>