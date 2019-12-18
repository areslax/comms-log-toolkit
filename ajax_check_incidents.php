<?php
/* ########################################
 * ajax_check_incidents.php
 * Checks for incidents and tac calls
 * Used for updating 'incidents' js array
 *  for incidentname/incidentid push update
 * ######################################## */
include "db_conn.php";

//get incidents
$iq = $conn->prepare("select Incidents.i_id,i_type,i_tactical,i_name,it_data from Incidents left outer join Incident_Types on Incident_Types.it_id=Incidents.i_type where i_status='1' order by i_name");
$iq->execute();
$incs = array();
while($r=$iq->fetch(PDO::FETCH_ASSOC)) {
	$thisia = $r['i_name'];
	$thisib = $r['it_data'].": ".strtoupper($r['i_tactical']).": ".$r['i_name'];
	$incids = array();
	$incids['label'] = $thisib;
	$incids['value'] = $thisia;
	$incids['iid'] = $r['i_id'];
	$incs[] = $incids;
}

echo json_encode($incs);
exit;
