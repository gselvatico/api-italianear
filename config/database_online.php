<?php
class Database{
    private $host = "185.81.4.196";
    private $db_name = "italiare_italianear";
    private $username = "italiare_ut_near";
    private $password = "ItA$20_21$";
 

    // get the database connection
    public function getConnection(){
  
        $this->conn = null;
  
        try{
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        }catch(PDOException $exception){
            echo "Connection error: " . $exception->getMessage();
        }
  
        return $this->conn;
    }
}
?>