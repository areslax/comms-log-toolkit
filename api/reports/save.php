<?php
/* ###############################
 * save.php
 * API methods to save report data
 * ############################### */
ini_set('display_errors','1');
require "../config/database.php";

class dbAct {
 
	// database connection and table name
	private $conn;
	
	public function __construct($db) {
		$this->conn = $db;
	}
 
	// get all data in one table
	public function readAll() {
		global $tablename;
		global $orderby;
		//select all data
		$q = "select * from ".$tablename." order by ".$orderby;
		$res = $this->conn->prepare($q);
		$res->execute();
 
		return $res;
	}

	//get all data for one record in one table
	public function readOne() {
		global $tablename;
		global $fldname;
		global $recid;
		//select one row
		$q = "select * from ".$tablename." where ".$fldname."=:recid limit 1";
		$res = $this->conn->prepare($q);
		$res->execute(array(":recid"=>$recid));

		return $res;
	}

	//save log
	public function saveLog() {
		$formdata = $_POST;
		$formjson = json_encode($formdata);
		//check for mci or hsa poll, resource request and skip this next part
		if (empty($formdata['mcitmstmp']) && empty($formdata['hsatmstmp']) && empty($formdata['rrtmstmp'])) {
		//check for existing report
		$q = "select r_id from Reports where r_data=:rdata limit 1";
		$res = $this->conn->prepare($q);
		$res->execute(array(":rdata"=>$formjson));
		$gots = ($res->rowCount() > 0) ? 1:0;
		if (!$gots) {
		//save entire log, if new
		$q = "insert into Reports set o_id=:oid,r_timestamp='".date("YmdHis")."',r_data=:rdata";
		$res = $this->conn->prepare($q);
		$res->execute(array(":oid"=>$formdata['o_id'],":rdata"=>$formjson));
		$rid = $this->conn->lastInsertId();
		//loop through rows
		$incs = $formdata['ids'];
		foreach($incs as $inc) {
		//no need to log empty messages
		if (empty($formdata['msg_'.$inc])) { continue; }
		//which form/poll
		$typ = $formdata['typ_'.$inc];
		$actdone = (isset($formdata['actdone_'.$inc])) ? $formdata['actdone_'.$inc]:0;
		switch($typ) {
			//save mci poll (Patients)
//			case 1:
			//save hsa poll (Availability,Locations.l_status)
//			case 2:
			//save event log (Events)
			case 3:
				//check for existing
				$eq = $this->conn->prepare("select e_id from Events where e_timestamp=:ets and e_data=:edata limit 1");
				$eq->execute(array(":ets"=>date("YmdHis",strtotime($formdata['ts_'.$inc])),":edata"=>$edata));
				if ($eq->rowCount() < 1) {
					$q = "insert into Events set r_id=:rid,e_timestamp=:ets,e_data=:edata";
					$res = $this->conn->prepare($q);
					$res->execute(array(":rid"=>$rid,":ets"=>date("YmdHis",strtotime($formdata['ts_'.$inc])),":edata"=>$formjson));
				}
				break;
			//save resource request (Requests)
//			case 4:
			//save relay messages log (Messages)
			case 5:
				$msgstatus = (empty($actdone)) ? date("YmdHis",strtotime($formdata['msgstatus_'.$inc])):date("YmdHis",strtotime($actdone));
                                //check for existing
				$tmstmp = date("YmdHis",strtotime($formdata['ts_'.$inc]));
				$rq = $this->conn->prepare("select msg_id from Messages where msg_timestamp=:mts and msg_data=:mdata limit 1");
				$rq->execute(array(":mts"=>$tmstmp,":mdata"=>$formjson));
				if ($rq->rowCount() < 1) {
					$act = ($formdata['act_'.$inc]=='Yes') ? 1:0;
					$q = "insert into Messages set r_id=:rid,msg_timestamp=:mts,msg_from=:msgfrom,msg_to=:msgto,msg_reply_req=:msgreplyreq,msg_status=:msgstatus,msg_data=:msgdata";
					$res = $this->conn->prepare($q);
					$res->execute(array(":rid"=>$rid,":mts"=>$tmstmp,":msgfrom"=>$formdata['msgfrom_'.$inc],":msgto"=>$formdata['msgto_'.$inc],":msgreplyreq"=>$act,":msgstatus"=>$msgstatus,":msgdata"=>$formjson));
					//return $res->errorInfo();
				}
				break;
		}//end switch typ
		}//end foreach incs
		}//end !gots
		}//end mci/hsa/rr check
		else {
			//these three are POSTed forms, not json strings
			//not directly associated with a report
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
$database = new dbConn();
$db = $database->getConn();

//set up to use db object
$dbdo = new dbAct($db);

if (!empty($_POST)) {
	$dbsave = $dbdo->saveLog();
	print_r($dbsave);
}

/*
//select all from a table
$tablename = "Members";
$orderby = 'm_lname';
$dset = $dbdo->readAll();
echo "dset:<pre>";
while($r=$dset->fetch(PDO::FETCH_ASSOC)) {
	print_r($r);
}

//select one from a table
$tablename = "Members";
$fldname = "m_id";
$recid = 4;
$dset = $dbdo->readOne();
echo "dset:<pre>";
while($r=$dset->fetch(PDO::FETCH_ASSOC)) {
	print_r($r);
}
/**/

?>

