<?php

namespace Utils;

use PDO;

class Database {
	private $servername = "47.254.253.28";
	private $username = "dev";
	private $password = "Docsim123";
	private $database = "docsim";

	public function connect()
	{
		try {
			$conn = new PDO("mysql:host=$this->servername;dbname=$this->database", $this->username, $this->password);
			// set the PDO error mode to exception
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			echo "Connected successfully";
			return $conn;
		} catch(PDOException $e) {
		  	echo "Connection failed: " . $e->getMessage();
		  	return null;
		}
	}
}