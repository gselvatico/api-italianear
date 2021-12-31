<?php
class Contatto_it{
  
    // database connection and table name
    private $conn;
    private $table_name = "contatto_it";
  
    // object properties
    public $contatto_id;
	public $userID;
    public $email;
    public $nome;
    public $categoria_id;
    public $nome_negozio;
    public $localita_id;
    public $indirizzo;
	public $telefono;
	public $email_c;
	public $ruolo;
	public $note;
	public $nomecontatto;
	public $internet;
	public $latitudine;
	public $longitudine;
	public $nazioneiso;
	public $immagine;
	public $dataiscrizione;
	public $vers;
	public $so;
	public $ndr;
	public $createddate;
	public $lastmodified;
	public $categoria;
	public $localita;
	public $nazione;
	public $prefisso;
	public $cat_Id;
	public $loc_Id;
	public $lat_min;
	public $lat_max;
	public $lng_min;
	public $lng_max;

  
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }	
function read(){
  
    // select all query
    $query = "SELECT				
				p.contatto_id,
				p.userID,
				p.email,
				p.nome,
				p.categoria_id,				
				p.nome_negozio,
				p.localita_id,
				p.indirizzo,
				p.nazioneiso,
				p.telefono,
				p.email_c,
				p.ruolo,
				p.note,
				p.nomecontatto,
				p.internet,
				p.latitudine,
				p.longitudine,
				p.immagine,
				p.dataiscrizione,
				p.vers,
				p.so,
				p.ndr,
				p.createddate,
				p.lastmodified,
				c.categoria,
				l.localita,
				n.nazione,
				n.prefisso
            FROM
                " . $this->table_name . " p     
                LEFT JOIN
                    categoria_it c
                    ON p.categoria_id = c.categoria_id	
				LEFT JOIN
					localita_it l
					ON p.localita_id=l.localita_id
				LEFT JOIN
					nazione_it n
					ON l.nazione_id=n.nazione_id			
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
				p.contatto_id,
				p.userID,
				p.email,
				p.nome,
				p.categoria_id,				
				p.nome_negozio,
				p.localita_id,
				p.indirizzo,
				p.nazioneiso,
				p.telefono,
				p.email_c,
				p.ruolo,
				p.note,
				p.nomecontatto,
				p.internet,
				p.latitudine,
				p.longitudine,
				p.immagine,
				p.dataiscrizione,
				p.vers,
				p.so,
				p.ndr,
				p.createddate,
				p.lastmodified,
				c.categoria,
				l.localita,
				n.nazione,
				n.prefisso
            FROM
                " . $this->table_name . " p
                LEFT JOIN
                    categoria_it c
                        ON p.categoria_id = c.categoria_id
				LEFT JOIN
					localita_it l
						ON p.localita_id=l.localita_id
				LEFT JOIN
					nazione_it n
						ON l.nazione_id=n.nazione_id
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
	 $this->contatto_id= $row['contatto_id'];
	 $this->nome= $row['nome'];
	 $this->email= $row['email'];
	 $this->nome_negozio= $row['nome_negozio'];
	 $this->categoria_id= $row['categoria_id'];			
	 $this->localita_id= $row['localita_id'];
	 $this->indirizzo= $row['indirizzo'];
	 $this->telefono= $row['telefono'];
	 $this->email_c= $row['email_c'];
	 $this->ruolo= $row['ruolo'];
	 $this->note= $row['note'];
	 $this->nomecontatto= $row['nomecontatto'];
	 $this->internet= $row['internet'];
	 $this->latitudine= $row['latitudine'];
	 $this->longitudine= $row['longitudine'];
	 $this->immagine= $row['immagine'];
	 $this->dataiscrizione= $row['dataiscrizione'];
	 $this->vers= $row['vers'];
	 $this->so= $row['so'];
	 $this->ndr= $row['ndr'];
	 $this->createddate= $row['createddate'];
	 $this->lastmodified= $row['lastmodified'];	
	 $this->categoria= $row['categoria'];	
	 $this->localita= $row['localita'];	
	 $this->nazione= $row['nazione'];	
	 $this->nazioneiso= $row['nazioneiso'];	
	 $this->prefisso= $row['prefisso'];	
	}
}
function readSelected(){
	$queryCategorie = "";

	if( $this->cat_Id !=1){
		$queryCategorie = "	AND  p.categoria_id IN ("
		. $this->cat_Id .
		")";
	} 
   
    $query = "SELECT				
				p.contatto_id,
				p.userID,
				p.email,
				p.nome,
				p.categoria_id,				
				p.nome_negozio,
				p.localita_id,
				p.indirizzo,
				p.nazioneiso,
				p.telefono,
				p.email_c,
				p.ruolo,
				p.note,
				p.nomecontatto,
				p.internet,
				p.latitudine,
				p.longitudine,
				p.immagine,
				p.dataiscrizione,
				p.vers,
				p.so,
				p.ndr,
				p.createddate,
				p.lastmodified,			
				c.categoria,
				l.localita,
				n.nazione,
				n.prefisso
            FROM
                " . $this->table_name . " p
                LEFT JOIN
                    categoria_it c
                        ON p.categoria_id = c.categoria_id
				LEFT JOIN
					localita_it l
						ON p.localita_id=l.localita_id
				LEFT JOIN
					nazione_it n
						ON l.nazione_id=n.nazione_id
            WHERE
                p.localita_id = :loc_id
		";
    $query =$query . $queryCategorie;
  
    // prepare query statement
    $stmt = $this->conn->prepare( $query );
  
   
	//$stmt->bindParam(":cat_id", $this->cat_Id);	
	$stmt->bindParam(":loc_id", $this->loc_Id);
	
    // execute query
    $stmt->execute();
	return $stmt;	  
}
function readByLatLng(){
	$queryCategorie = "";

	if( $this->cat_Id !=1){
		$queryCategorie = "	AND  p.categoria_id IN ("
		. $this->cat_Id .
		")";
	} 
   
    $query = "SELECT				
				p.contatto_id,
				p.userID,
				p.email,
				p.nome,
				p.categoria_id,				
				p.nome_negozio,
				p.localita_id,
				p.indirizzo,
				p.nazioneiso,
				p.telefono,
				p.email_c,
				p.ruolo,
				p.note,
				p.nomecontatto,
				p.internet,
				p.latitudine,
				p.longitudine,
				p.nazioneiso,
				p.immagine,
				p.dataiscrizione,
				p.vers,
				p.so,
				p.ndr,
				p.createddate,
				p.lastmodified,
				c.categoria,
				n.nazione,
				n.prefisso		
            FROM
                " . $this->table_name . " p 
                LEFT JOIN
                    categoria_it c
                        ON p.categoria_id = c.categoria_id
				LEFT JOIN nazione_it n
						ON 	n.iso=p.nazioneiso
             WHERE 
		 	    (p.latitudine between :latMin AND :latMax) 
		 		AND (p.longitudine between :lngMin and :lngMax) " 
		// 		AND  p.categoria_id = :cat_id;               
		;
    $query =$query . $queryCategorie;
  
    // prepare query statement
    $stmt = $this->conn->prepare( $query );  

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
				userID=:userID,
				email=:email,
				nome=:nome,
				categoria_id=:categoria_id,	
				nome_negozio=:nome_negozio,
				localita_id=:localita_id,
				indirizzo=:indirizzo,
				nazioneiso =:nazioneiso,
				telefono=:telefono,
				email_c=:email_c,
				ruolo=:ruolo,
				note=:note,
				nomecontatto=:nomecontatto,
				internet=:internet,
				latitudine=:latitudine,
				longitudine=:longitudine,
				nazioneiso=:nazioneiso,
				immagine=:immagine,
				dataiscrizione=:dataiscrizione,
				vers=:vers,
				ndr=:ndr,
				so=:so,              
				createddate=:createddate";
    // prepare qcreateddate,uery
    $stmt = $this->conn->prepare($query);
  
    // sanitize
    $this->nome=htmlspecialchars(strip_tags($this->nome));   
    $this->nome_negozio=htmlspecialchars(strip_tags($this->nome_negozio));
  
  
    // bind values
	$stmt->bindParam(":userID", $this->userID);
	$stmt->bindParam(":email", $this->email);
	$stmt->bindParam(":nome", $this->nome);
	$stmt->bindParam(":categoria_id", $this->categoria_id);	
	$stmt->bindParam(":nome_negozio", $this->nome_negozio);
	$stmt->bindParam(":localita_id", $this->localita_id);
	$stmt->bindParam(":indirizzo", $this->indirizzo);
	$stmt->bindParam(":nazioneiso", $this->nazioneiso);
	$stmt->bindParam(":telefono", $this->telefono);
	$stmt->bindParam(":email_c", $this->email_c);
	$stmt->bindParam(":ruolo", $this->ruolo);
	$stmt->bindParam(":note", $this->note);
	$stmt->bindParam(":nomecontatto", $this->nomecontatto);
	$stmt->bindParam(":internet", $this->internet);
	$stmt->bindParam(":latitudine", $this->latitudine);
	$stmt->bindParam(":longitudine", $this->longitudine);
	$stmt->bindParam(":immagine", $this->immagine);
	$stmt->bindParam(":dataiscrizione", $this->dataiscrizione);
	$stmt->bindParam(":vers", $this->vers);
	$stmt->bindParam(":ndr", $this->ndr);
	$stmt->bindParam(":so", $this->so);   
	$stmt->bindParam(":createddate", $this->createddate);	
	
    // execute query
    if($stmt->execute()){
		$last_id=$stmt->insert_id;
        return $last_id;
    }
  
    return false;      
}
function update(){  
    // update query
    $query = "UPDATE " . $this->table_name . "
            SET               
         		email=:email,
				nome=:nome,
				categoria_id=:categoria_id,	
				nome_negozio=:nome_negozio,
				localita_id=:localita_id,
				indirizzo=:indirizzo,
				telefono=:telefono,
				email_c=:email_c,
				ruolo=:ruolo,
				note=:note,
				nomecontatto=:nomecontatto,
				internet=:internet,
				latitudine=:latitudine,
				longitudine=:longitudine,
				nazioneiso=:nazioneiso,
				immagine=IF(LENGTH(:immagine) > 10 ,:immagine,immagine),
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
	$stmt->bindParam(":email", $this->email);
	$stmt->bindParam(":nome", $this->nome);
	$stmt->bindParam(":categoria_id", $this->categoria_id);	
	$stmt->bindParam(":nome_negozio", $this->nome_negozio);
	$stmt->bindParam(":localita_id", $this->localita_id);
	$stmt->bindParam(":indirizzo", $this->indirizzo);
	$stmt->bindParam(":telefono", $this->telefono);
	$stmt->bindParam(":email_c", $this->email_c);
	$stmt->bindParam(":ruolo", $this->ruolo);
	$stmt->bindParam(":note", $this->note);
	$stmt->bindParam(":nomecontatto", $this->nomecontatto);
	$stmt->bindParam(":internet", $this->internet);
	$stmt->bindParam(":latitudine", $this->latitudine);
	$stmt->bindParam(":longitudine", $this->longitudine);
	$stmt->bindParam(":nazioneiso", $this->nazioneiso);
	$stmt->bindParam(":immagine", $this->immagine);
	$stmt->bindParam(":vers", $this->vers);
	$stmt->bindParam(":ndr", $this->ndr);
	$stmt->bindParam(":so", $this->so);   
  
    // execute the query
    if($stmt->execute()){
        return true;
    }
  
    return false;
}
}
?>

