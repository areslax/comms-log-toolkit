<?php
/* ######################################
 * ARES_Operator_Status.php
 * Interface for managing Operator Status
 * ###################################### */
ini_set('display_errors','0');
include "db_conn.php";

//get active operators, status, deploys-to
$GetMembersPS = $conn->prepare("select Members.*,Locations.l_gps,Locations.l_name,Locations.l_tactical,Operators.o_id,Operator_Shift.oshit_inout,Operator_Shift.oshit_timestamp,Net_Controls.nc_callsign,Status_Codes.* from Members left outer join Operators on Operators.m_id=Members.m_id left outer join Operator_Shift on Operator_Shift.o_id=Operators.o_id left outer join Net_Controls on Net_Controls.nc_id=Operators.nc_id join Status_Codes on Status_Codes.s_id=Members.m_status left outer join Locations on Locations.l_id=Operators.l_id where m_active=1 order by m_callsign");
$GetMembersPS->execute();

$mems = array();
while ($r=$GetMembersPS->fetch(PDO::FETCH_ASSOC)) {
	$mid = $r['m_id'];
	$mems[$mid] = array();
	$mems[$mid]['callsign'] = $r['m_callsign'];
	$mems[$mid]['fname'] = $r['m_fname'];
	$mems[$mid]['lname'] = $r['m_lname'];
	$mems[$mid]['access_level'] = $r['m_access_level'];
	$mems[$mid]['status'] = $r['m_status'];
	$mems[$mid]['status_code'] = $r['s_title'];
	$mems[$mid]['note'] = $r['m_note'];
	$mems[$mid]['prestage_lid'] = $r['m_prestage_lid'];
	//get prestage location title
	$pq = $conn->prepare("select l_name,l_gps from Locations where l_id=:lid limit 1");
	$pq->execute(array(":lid"=>$r['m_prestage_lid']));
	$pr = $pq->fetch(PDO::FETCH_ASSOC);
	$mems[$mid]['prestage_name'] = $pr['l_name'];
	$mems[$mid]['prestage_gps'] = $pr['l_gps'];
	$chkdin = (!empty($r['o_id'])) ? 1:0;
	$mems[$mid]['loc_id'] = ($chkdin && isset($r['l_id'])) ? $r['l_id']:0;
	$mems[$mid]['nc_id'] = ($chkdin && isset($r['nc_id'])) ? $r['nc_id']:0;
	$mems[$mid]['nc_callsign'] = ($chkdin && isset($r['nc_callsign'])) ? $r['nc_callsign']:0;
	$mems[$mid]['nc_gps'] = ($chkdin && isset($r['nc_gps'])) ? $r['nc_gps']:0;
	$mems[$mid]['band'] = ($chkdin && isset($r['o_band'])) ? $r['o_band']:0;
	$mems[$mid]['inout'] = ($chkdin) ? $r['oshit_inout']:0;
	$mems[$mid]['timestamp'] = ($chkdin) ? $r['oshit_timestamp']:0;
	//check if liaison, get contacts
	$mems[$mid]['liaison'] = 0;
	$GetLiaisonsPS = $conn->prepare("select Liaisons.li_id,Liaison_Contacts.* from Liaisons left outer join Liaison_Contacts on Liaison_Contacts.li_id=Liaisons.li_id where Liaisons.m_id=:mid order by lic_type");
	$GetLiaisonsPS->execute(array(":mid"=>$mid));
	//set liaison flag
	$isliaison = (count($GetLiaisonsPS) > 0) ? 1:0;
	if ($isliaison) {
		//store as json
		$mems[$mid]['liaison'] = array();
		while($lr=$GetLiaisonsPS->fetch(PDO::FETCH_ASSOC)) {
			$mems[$mid]['liaison'][] = '{"type":"'.$lr['lic_type'].'","carrier":"'.$lr['lic_carrier'].'","data":"'.$lr['lic_data'].'"}';
		}
	}
	//get contacts
	$mems[$mid]['contacts'] = array();
	$GetMemberContactsPS = $conn->prepare("select * from Member_Contacts where m_id=:mid order by mc_type");
	$GetMemberContactsPS->execute(array(":mid"=>$mid));
	//store as json
	while($mr=$GetMemberContactsPS->fetch(PDO::FETCH_ASSOC)) {
		$mems[$mid]['contacts'][] = '{"type":"'.$mr['mc_type'].'","carrier":"'.$mr['mc_carrier'].'","data":"'.$mr['mc_data'].'"}';
	}
}
//debug
//echo "<pre>";print_r($mems);exit;
//location query
$GetLocationsPS = $conn->prepare("select l_id,l_tactical,l_name,l_gps from Locations order by l_tactical");
//code query
$GetOperatorStatusPS = $conn->prepare("select * from Status_Codes order by s_id");
?>

<!doctype html>
<html lang=en>
<head><title>ARES Operator Status</title>
<?php include "common_includes.php";?>
<script type="text/javascript">
var stats = new Array('white',<?php
$stats = "";
$GetOperatorStatusPS->execute();
while($sr=$GetOperatorStatusPS->fetch(PDO::FETCH_ASSOC)) {
	$stats .= "'".$sr['s_color']."',";
}
$stats = rtrim($stats,",");
echo $stats;
?>
);
function setStatus(mid,st) {
	var datastr = "mid="+mid+"&status="+st;
	jQuery.ajax({
		type: "POST",
		url: "ajax_set_operator_status.php",
		data: datastr,
		success: function(a,b,c){
			var bshow = (st>1) ? "none":"block";
			jQuery("#sbutton"+mid).css("display",bshow);
			jQuery("#row"+mid).css("background-color",stats[st]);
			jQuery("#statuscode"+mid).val(st);
		}
	});
}
function setDeploysTo(mid,lid) {
	var datastr = "mid="+mid+"&lid="+lid;
	jQuery.ajax({
		type: "POST",
		url: "ajax_set_operator_deploys_to.php",
		data: datastr,
		success: function(a,b,c){
			console.log(a);
			jQuery("#lgps"+mid).attr("href","https://maps.google.com/?q="+a);
		}
	});
}
</script>
<style type="text/css">
.hed { background-color: lightgrey; }
</style>
</head>
<body>
<center>
<?php
echo "<table border=1 cellpadding=4 cellspacing=0 style='border-color:white'>\n";
echo "<tr class='hed'><th colspan=5><h2 style='margin:0px'>Operator Status</h2></th></tr>\n";
echo "<tr class='hed'><th>Check In</th><th>Callsign</th><th>Name</th><th>Set Status</th><th>Deploys To</th></tr>\n";

foreach($mems as $mid => $data) {
	//operator status and color
	$GetOperatorStatusPS->execute();
	$rbg = "";
	$statuses = "<select name=statuscode".$mid." id=statuscode".$mid." onchange='setStatus(".$mid.",this[this.selectedIndex].value)'>";
	while($sr=$GetOperatorStatusPS->fetch(PDO::FETCH_ASSOC)) {
		$sel = ($sr['s_id'] == $data['status']) ? " selected":"";
		$rbg = (empty($sel)) ? $rbg:$sr['s_color'];
		$statuses .= "<option value=".$sr['s_id'].$sel.">".$sr['s_title']."</option>";
	}
	$statuses .= "</select>";
	$bshow = ($data['status']>1) ? "none":"block";
	$chkbut = "<button id=sbutton".$mid." type=button onclick='setStatus(".$mid.",2)' style='display:".$bshow."'>Check In</button>";
	//location
	$GetLocationsPS->execute();
	$lgps = "";
	$locs = "<select name=locations".$mid." id=locations".$mid." style='width:160px' onchange='setDeploysTo(".$mid.",this[this.selectedIndex].value)'>";
	while($lr=$GetLocationsPS->fetch(PDO::FETCH_ASSOC)) {
		$sel = ($lr['l_id'] == $data['prestage_lid']) ? " selected":"";
		$lgps = (empty($sel)) ? $lgps:$lr['l_gps'];
		$locs .= "<option value=".$lr['l_id'].$sel.">".$lr['l_tactical'].": ".$lr['l_name']."</option>";
	}
	$locs .= "</select>";
	echo "<tr id=row".$mid." style='background-color:".$rbg."'><td>".$chkbut."</td><td>".strtoupper($data['callsign'])."</td><td>".$data['fname']." ".$data['lname']."</td><td>".$statuses."</td><td>".$locs." <a id=lgps".$mid." href='https://maps.google.com/?q=".$lgps."' target='_blank' title='Click to view location on Google Maps'><img src='images/icon-google-maps.svg' alt='maps icon' border=0 width=14 align=absmiddle></a></td></tr>\n";
}

echo "</table>";
?>

</center>
