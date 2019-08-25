<?php
/* #############################
 * database.php
 * ARESLAX API Database Settings
 * ############################# */

class Database
{
	private $host = "localhost";
	private $db_name = "api_db";
	private $username = "aresdb";
	private $password = "areslax2019";
	public $conn;

	public function getConnection()
	{
		$this->conn = null;
		try
		{
			$this->conn = new PDO("mysql:host=".$this->host.";dbname=".$this->db_name,$this->username,$this->password);
			$this->conn->exec("set names utf8");
		}
		catch(PDOException $exception)
		{
			echo "Connection error: ".$exception->getMessage();
		}
		return $this->conn;
	}
}

