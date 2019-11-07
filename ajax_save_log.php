<?php
/* ##############################################
 * ajax_save_log.php
 * Utility to save various form data to the stick
 * ############################################## */
if (empty($_POST['rtyp'])) { exit; }

$rtyp = $_POST['rtyp'];
$staid = $_POST['stationid'];
$stacode = $_POST['stacode'];
$incid = $_POST['incidentid'];

setcookie('ncid',$staid);

//up to the second
$stmp = date("YmdHis");

$jdata = json_decode($_POST['frmdata']);

switch($rtyp) {
	case "mcipoll":
	$stacode = $jdata->mcilocation;
	$tmstamp = $jdata->mcitmstmp;
	$location = $staid;
	//add dump to INSERT Patients
	break;
	case "hsapoll":
	$stacode = $jdata->hsalocation;
	//add dump to UPDATE Availability
	break;
	case "resreq":
	$stacode = $jdata->reqlocation;
	//add dump to INSERT Resource_Requests
	break;
	case "relreq":
	$stacode = $jdata->rellocation;
	//add dump to INSERT Relay_Requests
	break;
	case "comms":
	$stacode = $jdata->stationid;
	break;
}

//save reports as json files, for each station/incident
$incid = (empty($incid)) ? "0":$incid;
$path_main = "reports/".$incid."_".$stacode."_".$rtyp."_log.json";
$path_stmp = "reports/".$incid."_".$stacode."_".$rtyp."_log_".$stmp.".json";

$data = $_POST['frmdata'];

//write to json file
if ($rtyp=='comms') { file_put_contents($path_main,$data); }
file_put_contents($path_stmp,$data);

echo "complete";
exit;
