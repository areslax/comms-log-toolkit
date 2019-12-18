<?php
/* #############################################
 * generateSpecialEvents.php
 * Pulls from db to create fresh js include file
 *  for Special Events (races, etc.)
 * Requires: $_GET['eventid']
 * ############################################# */
ini_set('display_errors','1');
require "/var/www/html/ares/db_conn.php";

$locs = "";
$peep = "";
$incids = "";
$ncids = "";
$typs = "";

//get locations and dump to js string
$lq = $conn->prepare("select el_id,el_name,el_gps,el_note from Event_Locations where ev_id=:evid order by el_name");
$lq->execute(array(":evid"=>$_GET['eventid']));
while($r=$lq->fetch(PDO::FETCH_ASSOC)) {
	$thisl = stripslashes($r['el_name']);
	$locs .= "{label:'".str_replace("'","\'",$thisl)."',value:'".$r['el_gps']."',lid:'".$r['el_id']."'},";
}
$locs = rtrim($locs,",")."\n";

//get people
$pq = $conn->prepare("select es_id,es_callsign,es_fname,es_lname,es_note from Event_Staff where ev_id=:evid order by el_id,es_callsign");
$pq->execute(array(":evid"=>$_GET['eventid']));
while($r=$pq->fetch(PDO::FETCH_ASSOC)) {
	$thisp = strtoupper($r['es_callsign'])." ".$r['es_fname']." ".$r['es_lname'];
	$peep .= "{label:'".$thisp."',value:'".$thisp."',mid:'".$r['es_id']."'},";
}
$peep = rtrim($peep,",")."\n";

//get incidents (events)
$iq = $conn->prepare("select ev_id,ev_type,ev_name,ev_date_start,ev_date_end,ev_note from Events_Special where ev_id=:evid limit 1");
$iq->execute(array(":evid"=>$_GET['eventid']));
while($r=$iq->fetch(PDO::FETCH_ASSOC)) {
	$thisia = $r['ev_name'];
        $thisib = $r['ev_name'];
        $incids .= "{label:'".$thisib."',value:'".$thisia."',iid:'".$r['ev_id']."'},";
}
$incids = rtrim($incids,",")."\n";

//get net controls
$ncq = $conn->prepare("select nc_id,nc_callsign,nc_location,ev_name from Net_Controls left outer join Events_Special on Events_Special.ev_id=Net_Controls.i_id where nc_active='1' and ev_id=:evid order by nc_callsign");
$ncq->execute(array(":evid"=>$_GET['eventid']));
while($r=$ncq->fetch(PDO::FETCH_ASSOC)) {
	$thisia = $r['nc_callsign'].": ".$r['ev_name'];
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
