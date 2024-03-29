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
    public $indirizzo;
	public $tel_prefix;
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
	public $categoria_father;
	public $father_id;
	public $localita;
	public $nazione;
	public $prefisso;
	public $lat_min;
	public $lat_max;
	public $lng_min;
	public $lng_max;
	public $avg_rating;
    public $n_rating;

  
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
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
					p.indirizzo,
					p.localita,
					p.nazioneiso,
					p.tel_prefix,
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
					c.categoria
					
				FROM
					" . $this->table_name . " p 
					LEFT JOIN
						categoria_it c
							ON p.categoria_id = c.categoria_id				
					INNER JOIN categoria_it f 
							ON c.father_id =f.categoria_id
							
				WHERE
					p.userID = ?
				";
	
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
			$this->localita= $row['localita'];
			$this->indirizzo= $row['indirizzo'];
			$this->nazioneiso= $row['nazioneiso'];
			$this->tel_prefix= $row['tel_prefix'];
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
			// $this->avg_rating= $row['avg_rating'];	
			// $this->n_rating= $row['n_rating'];	

			}
	}
	function readByLatLng(){
		$queryCategorie = "";

		if( $this->cat_Id !=1){
			$queryCategorie = "	AND  c.father_id IN ("
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
					p.localita,
					p.indirizzo,
					p.nazioneiso,
					p.tel_prefix,
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
					c.father_id,
					f.categoria As categoria_father,
					n.nazione					
				FROM
					" . $this->table_name . " p 
					LEFT JOIN
						categoria_it c
							ON p.categoria_id = c.categoria_id
					LEFT JOIN nazione_it n
							ON n.iso=p.nazioneiso					
					INNER JOIN categoria_it f 
							ON c.father_id =f.categoria_id					
				WHERE 
					(p.latitudine between :latMin AND :latMax) 
					AND (p.longitudine between :lngMin and :lngMax)"
					. $queryCategorie 			           
			;
		// $query =$query . $queryCategorie;
	
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
					localita=:localita,
					indirizzo=:indirizzo,
					nazioneiso=:nazioneiso,
					tel_prefix=:tel_prefix,
					telefono=:telefono,
					email_c=:email_c,
					ruolo=:ruolo,
					note=:note,
					nomecontatto=:nomecontatto,
					internet=:internet,
					latitudine=:latitudine,
					longitudine=:longitudine,
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
		$stmt->bindParam(":localita", $this->localita);
		$stmt->bindParam(":indirizzo", $this->indirizzo);
		$stmt->bindParam(":nazioneiso", $this->nazioneiso);
		$stmt->bindParam(":tel_prefix", $this->tel_prefix);
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
			return true;
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
					localita=:localita,
					indirizzo=:indirizzo,
					tel_prefix=:tel_prefix,
					telefono=:telefono,
					email_c=:email_c,
					ruolo=:ruolo,
					note=:note,
					nomecontatto=:nomecontatto,
					internet=:internet,
					latitudine=:latitudine,
					longitudine=:longitudine,
					nazioneiso=:nazioneiso,
					immagine=IF(:immagine='no_image_set',NULL,IF(LENGTH(:immagine) > 10 ,:immagine,immagine)),
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
		$stmt->bindParam(":localita", $this->localita);
		$stmt->bindParam(":indirizzo", $this->indirizzo);
		$stmt->bindParam(":tel_prefix", $this->tel_prefix);
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
	function delete(){
  
		// delete query
		$query = "DELETE FROM " . $this->table_name . " WHERE userID = ?";
	  
		// prepare query
		$stmt = $this->conn->prepare($query);  
	  
		// bind id of record to delete
		$stmt->bindParam(1, $this->userID);
	  
		// execute query
		if($stmt->execute()){
			return true;
		}
	  
		return false;
		}

}
?>