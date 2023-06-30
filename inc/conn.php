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



			 $sql = "
                CREATE TABLE IF NOT EXISTS tbl_notif_fcmkeys (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    user_ref VARCHAR(255),
                    fcm_key VARCHAR(500),
					created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                );
            ";
            $this->conn->exec($sql);

		}catch(PDOException $e){
			echo "Connection Error: " . $e->getMessage();
		}
		date_default_timezone_set('Asia/Manila');
		return $this->conn;
	}
}
?>