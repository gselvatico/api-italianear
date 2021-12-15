<?php
class Utente_it{
  
    // database connection and table name
    private $conn;
    private $table_name = "utente_it";
  
    // object properties
    public $utente_id;  
    public $userID;
    public $nickname;
    public $email;
    public $tipo;
    public $data_PRU;
    public $data_URU;
	public $vers;
	public $so;
	public $ndr;
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
				p.utente_id,
                p.userID,
                p.nickname,
				p.email,
				p.tipo,
				p.data_PRU,				
				p.data_URU,
				p.vers,
				p.so,
				p.ndr,
				p.createddate,
				p.lastmodified
            FROM
                " . $this->table_name . " p     
            ORDER BY
                p.createddate DESC";  
    // prepare query statement
    $stmt = $this->conn->prepare($query);
  
    // execute query
    $stmt->execute();
  
    return $stmt;
	}
function readOne(){
  
    // query to read single record
     $query = "SELECT				
				p.utente_id,
                p.userID,
                p.nickname,
				p.email,
				p.tipo,
				p.data_PRU,				
				p.data_URU,
				p.vers,
				p.so,
				p.ndr,
				p.createddate,
				p.lastmodified
            FROM
                " . $this->table_name . " p              
            WHERE
                p.userID = ?
            LIMIT
                0,1";
  
    // prepare query statement
    $stmt = $this->conn->prepare( $query );
  
    // bind id of product to be updated
    $stmt->bindParam(1, $this->userID);
  
    // execute query
    $stmt->execute();
  
    // get retrieved row
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if($row!=null){
    // set values to object properties
    $this->utente_id= $row['utente_id'];
	 $this->userID= $row['userID'];
     $this->nickname= $row['nickname'];
	 $this->email= $row['email'];
	 $this->tipo= $row['tipo'];
	 $this->data_PRU= $row['data_PRU'];		
	 $this->data_URU= $row['data_URU'];	
	 $this->vers= $row['vers'];
	 $this->so= $row['so'];
	 $this->ndr= $row['ndr'];
	 $this->createddate= $row['createddate'];
	 $this->lastmodified= $row['lastmodified'];
    }
}
	// create product
function create(){
  
    // query to insert record
    $query = "INSERT INTO
                " . $this->table_name . "
            SET
                userID=:userID,
                nickname=:nickname,
				email=:email,
				tipo=:tipo,
				data_PRU=:data_PRU,	
				data_URU=:data_URU,
				vers=:vers,
				ndr=:ndr,
				so=:so,              
				createddate=:createddate";
    // prepare qcreateddate,uery
    $stmt = $this->conn->prepare($query);
  
    // sanitize
    $this->email=htmlspecialchars(strip_tags($this->email));   
   
    // bind values
	$stmt->bindParam(":userID", $this->userID);
    $stmt->bindParam(":nickname", $this->nickname);
	$stmt->bindParam(":email", $this->email);
	$stmt->bindParam(":tipo", $this->tipo);
	$stmt->bindParam(":data_PRU", $this->data_PRU);	
	$stmt->bindParam(":data_URU", $this->data_URU);
	$stmt->bindParam(":vers", $this->vers);
    $stmt->bindParam(":so", $this->so);  
	$stmt->bindParam(":ndr", $this->ndr); 
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
                nickname=:nickname,
				email=:email,
				tipo=:tipo,				
				data_URU=:data_URU,
				vers=:vers,
				ndr=:ndr,
				so=:so				
            WHERE
                userID = :userID";
  
    // prepare query statement
    $stmt = $this->conn->prepare($query);
  
    // sanitize
    $this->email=htmlspecialchars(strip_tags($this->email));  
  
    // bind new values
  

    $stmt->bindParam(":userID", $this->userID);
    $stmt->bindParam(":nickname", $this->nickname);
	$stmt->bindParam(":email", $this->email);
	$stmt->bindParam(":tipo", $this->tipo);
	$stmt->bindParam(":data_URU", $this->data_URU);
	$stmt->bindParam(":vers", $this->vers);
    $stmt->bindParam(":so", $this->so);  
	$stmt->bindParam(":ndr", $this->ndr); 
  
    // execute the query
    if($stmt->execute()){
        return true;
    }
  
    return false;
}
}
?>

