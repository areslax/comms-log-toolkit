<?php
/* #############################################
 * generateNewLocations.php
 * Pulls from db to create fresh js include file
 * ############################################# */
ini_set('display_errors','1');
require "/var/www/html/ares/db_conn.php";

$locs = "";
$peep = "";
$incids = "{label:'',value:'New Incident',iid:0},";
$ncids = "";
$typs = "";

//get locations and dump to js string
$lq = $conn->prepare("select l_id,l_tactical,l_name from Locations order by l_name");
$lq->execute();
$lr = $lq->fetchAll(PDO::FETCH_ASSOC);
foreach($lr as $r) {
	$thisl = strtoupper($r['l_tactical'])." ".stripslashes($r['l_name']);
	$locs .= "{label:'".str_replace("'","\'",$thisl)."',value:'".$r['l_tactical']."',lid:'".$r['l_id']."'},";
}
$locs = rtrim($locs,",")."\n";

//get people
$pq = $conn->prepare("select m_id,m_callsign,m_fname,m_lname from Members where m_active='1' order by m_callsign");
$pq->execute();
$pr = $pq->fetchAll(PDO::FETCH_ASSOC);
foreach($pr as $r) {
	$thisp = strtoupper($r['m_callsign'])." ".$r['m_fname']." ".$r['m_lname'];
	$peep .= "{label:'".$thisp."',value:'".$thisp."',mid:'".$r['m_id']."'},";
}
$peep = rtrim($peep,",")."\n";

//get incidents
$iq = $conn->prepare("select Incidents.i_id,i_type,i_tactical,i_name,it_data from Incidents left outer join Incident_Types on Incident_Types.it_id=Incidents.i_type where i_status='1' order by i_name");
$iq->execute();
$ir = $iq->fetchAll(PDO::FETCH_ASSOC);
foreach($ir as $r) {
	$thisia = $r['i_name'];
        $thisib = $r['it_data'].": ".strtoupper($r['i_tactical']).": ".$r['i_name'];
        $incids .= "{label:'".$thisib."',value:'".$thisia."',iid:'".$r['i_id']."'},";
}
$incids = rtrim($incids,",")."\n";

//get net controls
$ncq = $conn->prepare("select nc_id,nc_callsign,nc_location,i_name from Net_Controls left outer join Incidents on Incidents.i_id=Net_Controls.i_id where nc_active='1' order by nc_callsign");
$ncq->execute();
$ncr = $ncq->fetchAll(PDO::FETCH_ASSOC);
foreach($ncr as $r) {
	$thisia = $r['nc_callsign'].": ".$r['i_name'];
	if (!empty($r['nc_location'])) { $thisia .= ": ".$r['nc_location']; }
	$ncids .= "{label:'".$thisia."',value:'".$thisia."',ncid:'".$r['nc_id']."'},";
}
$ncids = rtrim($ncids,",")."\n";

//define types of entry
$typs = "'',";
$rqq = $conn->prepare("select rqt_title from Request_Types order by rqt_id");
$rqq->execute();
$rqr = $rqq->fetchAll(PDO::FETCH_ASSOC);
foreach($rqr as $r) {
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

$updated .= "var loclookup = new Array(\n";
$updated .= $locs;
$updated .= ");\n\n";
$updated .= "var people = new Array(\n";
$updated .= $peep;
$updated .= ");\n\n";
$updated .= "var incidents = new Array(\n";
$updated .= $incids;
$updated .= ");\n\n";
$updated .= "var netcontrols = new Array(\n";
$updated .= $ncids;
$updated .= ");\n\n";
$updated .= "var typs = new Array(\n";
$updated .= $typs;
$updated .= ");\n\n\n";

file_put_contents("/var/www/html/ares/scripts/ARES_Locations_and_People.js",$updated);

echo "complete";
