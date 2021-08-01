<?php

namespace Utils;

use PDO;
use mysqli;

class Database {
	private $servername = "47.254.253.28";
	private $username = "dev";
	private $password = "Docsim123";
	private $database = "docsim";

	private $conn;

	public function connect()
	{
		$conn = new mysqli($this->servername, $this->username, $this->password, $this->database);

		// Check connection
		if (mysqli_connect_error()) {
		  	die("Connection failed: " . mysqli_connect_error());
		}

		$this->conn = $conn;
		// echo "<script>console.log('Connection successfully')</script>";
		return $conn;
	}

	public function connectPDO()
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

	public function query($sql)
	{
		return mysqli_query($this->conn, $sql);
	}

	public function queryGetResultArr($sql)
	{
		$result = mysqli_query($this->conn, $sql);
		$resultArr = array();

		if (mysqli_num_rows($result) > 0) {
		  	// output data of each row
		  	while($row = mysqli_fetch_assoc($result)) {
		    	array_push($resultArr, $row);
		  	}
		}

		return $resultArr;
	}

	public function close()
	{
		mysqli_close($this->conn);
	}
}