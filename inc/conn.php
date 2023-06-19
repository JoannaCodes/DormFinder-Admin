<?php
Class connection{
	private $host = "localhost";
	private $db = "db_dormfinder";

	private $username = "root";
	private $password = "";

	private $conn;

	public function sdm_connect(){
		$this->conn=null;
		try{
			$this->conn = new PDO('mysql:host=' . $this->host. "; dbname=" . $this->db,  $this->username, $this->password,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		}catch(PDOException $e){
			echo "Connection Error: " . $e->getMessage();
		}
		date_default_timezone_set('Asia/Manila');
		return $this->conn;
	}

	public function mysqli_connect(){
		$this->conn = new mysqli($this->host, $this->username, $this->password, $this->db);
		date_default_timezone_set('Asia/Manila');
		return $this->conn;
	}
}
?>