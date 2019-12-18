<?php
/* ##############################################
 * ajax_save_log.php
 * Utility to save various form data to the stick
 * ############################################## */
if (empty($_POST['rtyp'])) { exit; }

$rtyp = $_POST['rtyp'];
$staid = (empty($_POST['stationid'])) ? 0:$_POST['stationid'];
$stacode = (empty($_POST['stacode'])) ? 0:$_POST['stacode'];
$incid = (empty($_POST['incidentid'])) ? 0:$_POST['incidentid'];

setcookie('ncid',$staid);

//up to the second
$stmp = date("YmdHis");

//fill in blanks, just in case
foreach($_POST as $p => $d) {
	$_POST[$p] = (empty($d)) ? 0:$d;
}

$edata = json_encode($_POST);
$jdata = json_decode($_POST['frmdata']);
$adata = json_decode($_POST['frmdata'],true);

$tmstmp = $jdata->tmstmp;
$collected = $jdata->datacollected;

require "db_conn.php";

//get l_id from tac call
$lq = $conn->prepare("select l_id from Locations where l_tactical=:loc limit 1");
$lq->execute(array(":loc"=>$jdata->location));
$l = $lq->fetch(PDO::FETCH_ASSOC);
$lid = $l['l_id'];

switch($rtyp) {
	case "mcipoll":
	$rcvd = date("YmdHis");
	$stacode = $jdata->location;
	$tmstamp = date("YmdHi",strtotime($jdata->tmstmp));
	$location = $staid;
	//Triage Counts
	$immediate = $jdata->immediate;
	$delayed = $jdata->delayed;
	$minor = $jdata->minor;
	$fatalities = $jdata->fatalities;
	$iq = $conn->prepare("insert into Location_MCI_Status (l_id,ms_collected,ms_immediate,ms_delayed,ms_minor,ms_fatalities,ms_timestamp) values (:lid,:tim,:imm,:del,:min,:fat,:rcvd)");
	$iq->execute(array(":lid"=>$lid,":tim"=>$tmstamp,":imm"=>$immediate,":del"=>$delayed,":min"=>$minor,":fat"=>$fatalities,":rcvd"=>$rcvd));
                if ($iq->errorInfo()[0]!='00000') {
                        echo "oops:\n";
                        print_r($iq->errorInfo());
                        exit;
                }
	$msid = $conn->lastInsertId();
	//Patients
	$patientcnt = $jdata->patientcnt;
	for($i=0;$i<=$patientcnt;$i++) {
		if (!empty($adata['patientinfo['.$i.']'])) {
			$ptype = $adata['patienttype['.$i.']'];
			$pinfo = $adata['patientinfo['.$i.']'];
			$trans = $adata['transport['.$i.']'];
			$timea = date("YmdHi",strtotime($adata['timearrived['.$i.']']));
			$pdata = '{"patient_type":"'.$ptype.'","patient_info":"'.$pinfo.'","transport":"'.$trans.'","time_arrived":"'.$timea.'"}';
			$pq = $conn->prepare("insert into Patients (l_id,ms_id,p_type,p_note,p_transport,p_arrived,p_data,p_timestamp) values (:lid,:msid,:ptype,:pinfo,:trans,:timea,:pdata,:rcvd)");
			$pq->execute(array(":lid"=>$lid,":msid"=>$msid,":ptype"=>$ptype,":pinfo"=>$pinfo,":trans"=>$trans,":timea"=>$timea,":pdata"=>$pdata,":rcvd"=>$rcvd));
		}
	}
	break;
	case "hsapoll":
	$stacode = $jdata->location;
	//Availability
	//get service level
	$servicelvls = array(1=>'green',2=>'yellow',3=>'orange',4=>'red',5=>'black',6=>'grey');
	//get extras
	$default_flds = array("tmstmp","location","locname","incident_id","datacollected","servicelevel","decon","medsurg","tele","icu","picu","nicu","peds","obgyn","trauma","burn","isolation","psych","or","vent");
	$other = array();
	foreach($jdata as $k => $v) {
		if (empty($v)) { $v=0; }
		if (!in_array($k,$default_flds) && substr($k,0,4)!='hsae') {
			$other[$k] = $v;
		}
	}
	$other = json_encode($other);
	$decon = ($jdata->decon) ? 1:0;
	//check for existing to update
	$ck = $conn->prepare("select a_id from Availability where l_id=:loc and i_id=:inc limit 1");
	$ck->execute(array(":loc"=>$lid,":inc"=>$incid));
	if ($ck->rowCount()>0) {
		$a = $ck->fetch(PDO::FETCH_ASSOC);
		$uq = $conn->prepare("update Availability set a_timestamp=:now,a_data=:data,a_servicelevel=:svclvl,a_medsurg=:medsurg,a_tele=:tele,a_icu=:icu,a_picu=:picu,a_nicu=:nicu,a_peds=:peds,a_obgyn=:obgyn,a_trauma=:trauma,a_burn=:burn,a_iso=:iso,a_psych=:psych,a_or=:opr,a_vent=:vent,a_decon=:decon,a_other=:other where a_id=:aid limit 1");
		$uq->execute(array(":aid"=>$a['a_id'],":now"=>$collected,":data"=>$edata,":svclvl"=>$servicelvls[$jdata->servicelevel],":medsurg"=>$jdata->medsurg,":tele"=>$jdata->tele,":icu"=>$jdata->icu,":picu"=>$jdata->picu,":nicu"=>$jdata->nicu,":peds"=>$jdata->peds,":obgyn"=>$jdata->obgyn,":trauma"=>$jdata->trauma,":burn"=>$jdata->burn,":iso"=>$jdata->isolation,":psych"=>$jdata->psych,":opr"=>$jdata->or,":vent"=>$jdata->vent,":decon"=>$jdata->decon,":other"=>$other));
	}
	else {
		$iq = $conn->prepare("insert into Availability (l_id,i_id,a_timestamp,a_data,a_servicelevel,a_medsurg,a_tele,a_icu,a_picu,a_nicu,a_peds,a_obgyn,a_trauma,a_burn,a_iso,a_psych,a_or,a_vent,a_decon,a_other) values (:lid,:iid,:now,:data,:svclvl,:medsurg,:tele,:icu,:picu,:nicu,:peds,:obgyn,:trauma,:burn,:iso,:psych,:opr,:vent,:decon,:other)");
		$iq->execute(array(":lid"=>$lid,":iid"=>$incid,":now"=>$collected,":data"=>$edata,":svclvl"=>$servicelvls[$jdata->servicelevel],":medsurg"=>$jdata->medsurg,":tele"=>$jdata->tele,":icu"=>$jdata->icu,":picu"=>$jdata->picu,":nicu"=>$jdata->nicu,":peds"=>$jdata->peds,":obgyn"=>$jdata->obgyn,":trauma"=>$jdata->trauma,":burn"=>$jdata->burn,":iso"=>$jdata->isolation,":psych"=>$jdata->psych,":opr"=>$jdata->or,":vent"=>$jdata->vent,":decon"=>$decon,":other"=>$other));
		if ($iq->errorInfo()[0]!='00000') {
			echo "oops:\n";
			print_r($iq->errorInfo());
			exit;
		}
	}
	break;
}

//save reports as json files, for each station/incident
$incid = (empty($incid)) ? "0":$incid;
$path_main = "reports/".$incid."_".$stacode."_".$rtyp."_log.json";
$path_stmp = "reports/".$incid."_".$stacode."_".$rtyp."_log_".$stmp.".json";

//write to json file
if ($rtyp=='comms') { file_put_contents($path_main,$edata); }
file_put_contents($path_stmp,$edata);

echo "complete";
exit;
