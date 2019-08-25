<?php
/* ###########################
 * product.php
 * ARESLAX API Product Methods
 * ########################### */

class Product
{
	//set up database connection and table name
	private $conn;
	private $table_name = "products";
	//object properties
	public $id;
	public $name;
	public $description;
	public $price;
	public $category_id;
	public $category_name;
	public $created;
	//constructor with $db as the database connection
	public function __construct($db)
	{
		$this->conn = $db;
	}

	function read()
	{
		//select all query
		$query = "select c.name as category_name, p.id, p.name, p.description, p.price, p.category_id, p.created from ".$this->table_name." p left join categories c on p.category_id=c.id order by p.created desc";
		//prepare
		$stmt = $this->conn->prepare($query);
		//execute
		$stmt->execute();
		return $stmt;
	}
	function create()
	{
		//insert query
		$query = "insert into ".$this->table_name." set name=:name,price=:price,description=:description,category_id=:category_id,created=:created";
		$stmt = $this->conn->prepare($query);

		// sanitize
		$this->name=htmlspecialchars(strip_tags($this->name));
		$this->price=htmlspecialchars(strip_tags($this->price));
		$this->description=htmlspecialchars(strip_tags($this->description));
		$this->category_id=htmlspecialchars(strip_tags($this->category_id));
		$this->created=htmlspecialchars(strip_tags($this->created));

		// bind values
		$stmt->bindParam(":name", $this->name);
		$stmt->bindParam(":price", $this->price);
		$stmt->bindParam(":description", $this->description);
		$stmt->bindParam(":category_id", $this->category_id);
		$stmt->bindParam(":created", $this->created);

		if ($stmt->execute())
		{
			return true;
		}
		return false;
	}
}
