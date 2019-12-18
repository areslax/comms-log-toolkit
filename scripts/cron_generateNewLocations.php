<?php
/* #############################################
 * generateNewLocations.php
 * Pulls from db to create fresh js include file
 * ############################################# */
ini_set('display_errors','1');
require "/var/www/html/ares/db_conn.php";

$locs = "";
$locs_med = "";
$locs_pd = "";
$locs_fd = "";
$locs_psy = "";
$locs_gen = "";
$locs_reh = "";
$locs_sta = "";
$peep = "";
$peep_o = "";
$peep_n = "";
$peep_a = "";
$incids = "{label:'',value:'New Incident',iid:0},";
$inc_fire = "";
$inc_quak = "";
$inc_terr = "";
$inc_othr = "";
$inc_flod = "";
$inc_evnt = "";
$ncids = "";
$typs = "";

//get locations and dump to js string
$lq = $conn->prepare("select l_id,l_tactical,l_name,l_type from Locations order by l_name");
$lq->execute();
while($r=$lq->fetch(PDO::FETCH_ASSOC)) {
	$thisl = strtoupper($r['l_tactical'])." ".stripslashes($r['l_name']);
	$locs .= "{label:'".str_replace("'","\'",$thisl)."',value:'".$r['l_tactical']."',lid:'".$r['l_id']."'},";
	switch ($r['l_type']) {
		case "1":
		$locs_med .= "{label:'".str_replace("'","\'",$thisl)."',value:'".$r['l_tactical']."',lid:'".$r['l_id']."'},";
		break;
		case "2":
		$locs_pd .= "{label:'".str_replace("'","\'",$thisl)."',value:'".$r['l_tactical']."',lid:'".$r['l_id']."'},";
		break;
		case "3":
		$locs_fd .= "{label:'".str_replace("'","\'",$thisl)."',value:'".$r['l_tactical']."',lid:'".$r['l_id']."'},";
		break;
		case "4":
		$locs_psy .= "{label:'".str_replace("'","\'",$thisl)."',value:'".$r['l_tactical']."',lid:'".$r['l_id']."'},";
		break;
		case "5":
		$locs_gen .= "{label:'".str_replace("'","\'",$thisl)."',value:'".$r['l_tactical']."',lid:'".$r['l_id']."'},";
		break;
		case "6":
		$locs_reh .= "{label:'".str_replace("'","\'",$thisl)."',value:'".$r['l_tactical']."',lid:'".$r['l_id']."'},";
		break;
		case "7":
		$locs_sta .= "{label:'".str_replace("'","\'",$thisl)."',value:'".$r['l_tactical']."',lid:'".$r['l_id']."'},";
		break;
	}
}
$locs = rtrim($locs,",")."\n";
$locs_med = rtrim($locs_med,",")."\n";
$locs_pd = rtrim($locs_pd,",")."\n";
$locs_fd = rtrim($locs_fd,",")."\n";
$locs_psy = rtrim($locs_psy,",")."\n";
$locs_gen = rtrim($locs_gen,",")."\n";
$locs_reh = rtrim($locs_reh,",")."\n";
$locs_sta = rtrim($locs_sta,",")."\n";

//get people
$pq = $conn->prepare("select m_id,m_callsign,m_fname,m_lname,m_type from Members where m_active='1' order by m_callsign");
$pq->execute();
while($r=$pq->fetch(PDO::FETCH_ASSOC)) {
	$thisp = strtoupper($r['m_callsign'])." ".$r['m_fname']." ".$r['m_lname'];
	$peep .= "{label:'".$thisp."',value:'".$thisp."',mid:'".$r['m_id']."'},";
	switch($r['m_type']) {
		case "1":
		$peep_o .= "{label:'".$thisp."',value:'".$thisp."',mid:'".$r['m_id']."'},";
		break;
		case "2":
		$peep_n .= "{label:'".$thisp."',value:'".$thisp."',mid:'".$r['m_id']."'},";
		break;
		case "3":
		$peep_a .= "{label:'".$thisp."',value:'".$thisp."',mid:'".$r['m_id']."'},";
		break;
	}
}
$peep = rtrim($peep,",")."\n";
$peep_o = rtrim($peep_o,",")."\n";
$peep_n = rtrim($peep_n,",")."\n";
$peep_a = rtrim($peep_a,",")."\n";

//get incidents
$iq = $conn->prepare("select Incidents.i_id,i_type,i_tactical,i_name,it_data from Incidents left outer join Incident_Types on Incident_Types.it_id=Incidents.i_type where i_status='1' order by i_name");
$iq->execute();
while($r=$iq->fetch(PDO::FETCH_ASSOC)) {
	$thisia = $r['i_name'];
        $thisib = $r['it_data'].": ".strtoupper($r['i_tactical']).": ".$r['i_name'];
        $incids .= "{label:'".$thisib."',value:'".$thisia."',iid:'".$r['i_id']."'},";
	switch ($r['i_type']) {
		case "1":
		$inc_fire .= "{label:'".$thisib."',value:'".$thisia."',iid:'".$r['i_id']."'},";
		break;
		case "2":
		$inc_quak .= "{label:'".$thisib."',value:'".$thisia."',iid:'".$r['i_id']."'},";
		break;
		case "3":
		$inc_terr .= "{label:'".$thisib."',value:'".$thisia."',iid:'".$r['i_id']."'},";
		break;
		case "5":
		$inc_othr .= "{label:'".$thisib."',value:'".$thisia."',iid:'".$r['i_id']."'},";
		break;
		case "6":
		$inc_flod .= "{label:'".$thisib."',value:'".$thisia."',iid:'".$r['i_id']."'},";
		break;
		case "7":
		$inc_evnt .= "{label:'".$thisib."',value:'".$thisia."',iid:'".$r['i_id']."'},";
		break;
	}
}
$incids = rtrim($incids,",")."\n";
$inc_fire = rtrim($inc_fire,",")."\n";
$inc_quak = rtrim($inc_quak,",")."\n";
$inc_terr = rtrim($inc_terr,",")."\n";
$inc_othr = rtrim($inc_othr,",")."\n";
$inc_flod = rtrim($inc_flod,",")."\n";

//get net controls
$ncq = $conn->prepare("select nc_id,nc_callsign,nc_location,i_name from Net_Controls left outer join Incidents on Incidents.i_id=Net_Controls.i_id where nc_active='1' order by nc_callsign");
$ncq->execute();
while($r=$ncq->fetch(PDO::FETCH_ASSOC)) {
	$thisia = $r['nc_callsign'].": ".$r['i_name'];
	if (!empty($r['nc_location'])) { $thisia .= ": ".$r['nc_location']; }
	$ncids .= "{label:'".$thisia."',value:'".$thisia."',ncid:'".$r['nc_id']."'},";
}
$ncids = rtrim($ncids,",")."\n";

//define types of entry
$typs = "'',";
$rqq = $conn->prepare("select rqt_title from Entry_Types order by rqt_id");
$rqq->execute();
while($r=$rqq->fetch(PDO::FETCH_ASSOC)) {
	$typs .= "'".$r['rqt_title']."',";
}
$typs = rtrim($typs,",")."\n";

$updated = "/* ############################
 * ARES_Locations_and_People.js
 * List of deployment locations
 * and ARESLAX scribes
 * Auto-updated: ".date("Y-m-d")."
 * ############################ */\n
";

$updated .= "var locs = new Array(\n";
$updated .= $locs;
$updated .= ");\n\n";
$updated .= "var loc_med = new Array(\n";
$updated .= $locs_med;
$updated .= ");\n\n";
$updated .= "var loc_pd = new Array(\n";
$updated .= $locs_pd;
$updated .= ");\n\n";
$updated .= "var loc_fd = new Array(\n";
$updated .= $locs_fd;
$updated .= ");\n\n";
$updated .= "var loc_psy = new Array(\n";
$updated .= $locs_psy;
$updated .= ");\n\n";
$updated .= "var loc_reh = new Array(\n";
$updated .= $locs_reh;
$updated .= ");\n\n";
$updated .= "var loc_sta = new Array(\n";
$updated .= $locs_sta;
$updated .= ");\n\n";
$updated .= "var people = new Array(\n";
$updated .= $peep;
$updated .= ");\n\n";
$updated .= "var people_ops = new Array(\n";
$updated .= $peep_o;
$updated .= ");\n\n";
$updated .= "var people_net = new Array(\n";
$updated .= $peep_n;
$updated .= ");\n\n";
$updated .= "var people_adm = new Array(\n";
$updated .= $peep_a;
$updated .= ");\n\n";
$updated .= "var incidents = new Array(\n";
$updated .= $incids;
$updated .= ");\n\n";
$updated .= "var inc_fire = new Array(\n";
$updated .= $inc_fire;
$updated .= ");\n\n";
$updated .= "var inc_quak = new Array(\n";
$updated .= $inc_quak;
$updated .= ");\n\n";
$updated .= "var inc_terr = new Array(\n";
$updated .= $inc_terr;
$updated .= ");\n\n";
$updated .= "var inc_othr = new Array(\n";
$updated .= $inc_othr;
$updated .= ");\n\n";
$updated .= "var inc_flod = new Array(\n";
$updated .= $inc_flod;
$updated .= ");\n\n";
$updated .= "var inc_evnt = new Array(\n";
$updated .= $inc_evnt;
$updated .= ");\n\n";
$updated .= "var netcontrols = new Array(\n";
$updated .= $ncids;
$updated .= ");\n\n";
$updated .= "var typs = new Array(\n";
$updated .= $typs;
$updated .= ");\n\n\n";

file_put_contents("/var/www/html/ares/scripts/ARES_Locations_and_People.js",$updated);

echo "complete";
