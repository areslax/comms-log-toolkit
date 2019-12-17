<?php
/* ###############################
 * read.php
 * API methods to read report data
 * ############################### */
ini_set('display_errors','1');
require "../config/database.php";

class dbAction {
 
	// database connection and table name
	private $conn;
	
	public function __construct($db) {
		$this->conn = $db;
		/* memcached                      */
		/* required: add servers to class */
		$MC_SERVERS = array('127.0.0.1');
		$this->mc = new Memcache();
		foreach($MC_SERVERS as $server) {
			$this->mc->addServer($server);
		}
		/**/
	}
 
	//validate incoming string variables
	public function checkstr($str) {
		//only alphanumeric or underscores allowed
		return preg_match('/^[A-Za-z0-9_]+$/',$str);
	}

	// get all data in one table
	public function readAll() {
		global $tablename;
		global $orderby;
		//validate incoming variables
		if (!$this->checkstr($tablename)) { exit; }
		if (!$this->checkstr($orderby)) { exit; }
		//uses memcached data
		$res = $this->mc->get("all_".$tablename);
		if ($res===false) {
			$res = array();
			//select all data
			$q = "select * from ".$tablename." order by ".$orderby;
			$req = $this->conn->query($q);
			while($row=$req->fetch(PDO::FETCH_ASSOC)) {
				$res[] = $row;
			}
			//10 minute expiry
			$this->mc->set("all_".$tablename,$res,0,600);
		} 
		return json_encode($res);
	}

	//get all data for one record in one table
	public function readOne() {
		global $tablename;
		global $fldname;
		global $recid;
		//select one row
		$q = "select * from ".$tablename." where ".$fldname."=:recid limit 1";
		$req = $this->conn->prepare($q);
		$req->execute(array(":recid"=>$recid));

		return json_encode($req->fetch(PDO::FETCH_ASSOC));
	}

}

//connect to db and create object
$dbase = new dbConnect();
$db = $dbase->getConn();

//set up to use db object
$dbdo = new dbAction($db);

/*
//select all from a table
$tablename = "Members";
$orderby = 'm_lname';
$dset = $dbdo->readAll();
$dres = json_decode(print_r($dset));
/**/
/*
//select one from a table
$tablename = "Members";
$fldname = "m_id";
$recid = 4;
$dset = $dbdo->readOne();
$dres = json_decode(print_r($dset));
/**/

?>

