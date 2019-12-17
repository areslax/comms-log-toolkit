<?php
/* ###############################
 * save.php
 * API methods to save report data
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

	//save log
	public function saveLog() {

		$formdata = $_POST;
		$formjson = json_encode($formdata);

		//check for mci or hsa poll, resource request and skip this next part
		if (empty($formdata['mcitmstmp']) && empty($formdata['hsatmstmp']) && empty($formdata['rrtmstmp'])) {
			//check for existing report
			$currsta = '"stationid":"'.$formdata['stationid'].'","incidentid":"'.$formdata['incidentid'].'"';
			$res = $this->conn->prepare("select r_id from Reports where r_data like ? order by r_id desc limit 1");
			$res->execute(array("%$currsta%"));
			$gots = ($res->rowCount() > 0) ? 1:0;

			if (!$gots) {
				//save entire log, if new
				$q = "insert into Reports set o_id=:oid,r_timestamp='".date("YmdHis")."',r_data=:rdata";
				$res = $this->conn->prepare($q);
				$res->execute(array(":oid"=>$formdata['o_id'],":rdata"=>$formjson));
				$rid = $this->conn->lastInsertId();
			}//end !gots insert
			else {
	        	        //update log, if station+incident exists
				while($r = $res->fetch(PDO::FETCH_ASSOC)) { $rid = $r['r_id']; }
				$q = "update Reports set r_timestamp='".date("YmdHis")."',r_data=:rdata where r_id=:rid";
				$res = $this->conn->prepare($q);
				$res->execute(array(":rdata"=>$formjson,":rid"=>$rid));
			}//end else gots update
		}//end mci/hsa/rr check
		else {
			//these three are POSTed forms, not json strings
			//should be directly associated with a report
			//mci poll
			if (!empty($formdata['mcitmstmp'])) {
				$tmstmp1 = date("YmdHis");//when reported
				$tmstmp2 = date("YmdHis",strtotime($formdata['mcidatacollected']));//when collected
				//get location id
				$q1 = $this->conn->prepare("select l_id from Locations where l_tactical=:tact limit 1");
				$q1->execute(array(":tact"=>$formdata['mcilocation']));
				$r = $q1->fetch(PDO::FETCH_ASSOC);
				//update locations
				$uq = $this->conn->prepare("update Locations set l_last_report=:tstmp,l_last_report_type='mci',l_last_report_by=:op where l_id=:lid limit 1");
				$uq->execute(array(":tstmp"=>$tmstmp1,":op"=>"1",":lid"=>$r['l_id']));
				//insert into location mci status
				$q2 = $this->conn->prepare("insert into Location_MCI_Status set l_id=:lid,ms_immediate=:imm,ms_delayed=:del,ms_minor=:min,ms_timestamp=:tstmp");
				$q2->execute(array(":lid"=>$r['l_id'],":imm"=>$formdata['mciimmediate'],":del"=>$formdata['mcidelayed'],":min"=>$formdata['mciminor'],":tstmp"=>$tmstmp1));
				//insert into patients
				foreach($formdata['patienttype'] as $pid => $ptyp) {
					if (empty($ptyp)) { continue; }
					$q3 = $this->conn->prepare("insert into Patients set r_id=0,p_timestamp=:tstmp,p_type=:ptyp,p_note=:pnote,p_data=:pdata");
					$q3->execute(array(":tstmp"=>$tmstmp2,":ptyp"=>$ptyp,":pnote"=>$formdata['patientinfo_'.$pid],":pdata"=>$formjson));
				}
			}
			//hsa poll
			else if (!empty($formdata['hsatmstmp'])) {
				$tmstmp1 = date("YmdHis");//when reported
				$tmstmp2 = date("YmdHis",strtotime($formdata['hsadatacollected']));//when collected
                                //get location id
				$q1 = $this->conn->prepare("select l_id from Locations where l_tactical=:tact limit 1");
				$q1->execute(array(":tact"=>$formdata['hsalocation']));
				$r = $q1->fetch(PDO::FETCH_ASSOC);
				//update location status
				$q2 = $this->conn->prepare("update Locations set l_status=:lstatus,l_last_report=:tstmp,l_last_report_type='hsa',l_last_report_by='1' where l_tactical=:tact limit 1");
				$q2->execute(array(":lstatus"=>$formdata['hsasvclvl'],":tstmp"=>$tmstmp1,":tact"=>$formdata['hsalocation']));
				//insert/update availability
				//check for existing
				$aq = $this->conn->prepare("select a_id from Availability where l_id=:lid and i_id=:iid limit 1");
				$aq->execute(array(":lid"=>$r['l_id'],":iid"=>$formdata['hsaincident']));
				if ($aq->rowCount() > 0) {
					$q3 = $this->conn->prepare("update Availability set a_timestamp=:tstmp,a_data=:adata,a_medsurg=:medsurg,a_tele=:tele,a_icu=:icu,a_picu=:picu,a_nicu=:nicu,a_peds=:peds,a_obgyn=:obgyn,a_trauma=:trauma,a_burn=:burn,a_iso=:iso,a_psych=:psych,a_or=:or where l_id=:lid and i_id=:iid");
				}
				else {
					$q3 = $this->conn->prepare("insert into Availability set r_id=0,l_id=:lid,i_id=:iid,a_timestamp=:tstmp,a_data=:adata,a_medsurg=:medsurg,a_tele=:tele,a_icu=:icu,a_picu=:picu,a_nicu=:nicu,a_peds=:peds,a_obgyn=:obgyn,a_trauma=:trauma,a_burn=:burn,a_iso=:iso,a_psych=:psych,a_or=:or");
				}
				$q3->execute(array("lid"=>$r['l_id'],":iid"=>$formdata['hsaincident'],":tstmp"=>$tmstmp2,":adata"=>$formjson,":medsurg"=>$formdata['hsamedsurg'],":tele"=>$formdata['hsatele'],":icu"=>$formdata['hsaicu'],":picu"=>$formdata['hsapicu'],":nicu"=>$formdata['hsanicu'],":peds"=>$formdata['hsapeds'],":obgyn"=>$formdata['hsaobgyn'],":trauma"=>$formdata['hsatrauma'],":burn"=>$formdata['hsaburn'],":iso"=>$formdata['hsaisolation'],":psych"=>$formdata['hsapsych'],":or"=>$formdata['hsaor']));
			}
			//resource request
			else if (!empty($formdata['rrtmstmp'])) {
			}
		}
	}
}

//connect to db and create object
$dbase = new dbConnect();
$db = $dbase->getConn();

//set up to use db object
$dbdo = new dbAction($db);

if (!empty($_POST)) {
	$dbsave = $dbdo->saveLog();
	print_r($dbsave);
}

?>

