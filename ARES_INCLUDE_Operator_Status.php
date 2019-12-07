<?php
/* ######################################
 * ARES_INCLUDE_Operator_Status.php
 * Include displays Operator Status,
 * depending on which interface is viewed
 * ###################################### */
?>
<!-- ARES_INCLUDE_Operator_Status.php -->
<link href="https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300&display=swap" rel="stylesheet">
<div id=opstable style='position:fixed;top:10px;left:10px;padding:0px;background-color:white;text-align:center;'>
<div id=members>
<table id=table_memstatus border=1 cellpadding=1 cellspacing=0 style='border-color:white;width:280px;'>
<tr><th colspan=3>Operator Status</th><th style="font-family:'Open Sans Condensed',sans-serif;font-size:.8em;">LOC</th><th style="font-family:'Open Sans Condensed',sans-serif;font-size:.8em;">NC</th></tr>
<?php
$ops = array();

if (!empty($iid)) {
	//single incident
	$labl = "Involved in this Incident";
}
else if (!empty($lid)) {
	//single location
	$labl = "Deploys to this Location";
}
else if (!empty($ncid)) {
	//single net control
	$labl = "Associated with this Net Control";
}
else {
	$labl = "";
}
//all operators
//debug
//$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$oq = $conn->prepare("select Members.m_id,m_callsign,m_status,m_fname,m_lname,m_prestage_lid,Operators.i_id,i_name,Operators.l_id,l_tactical,l_name,Operators.nc_id,nc_callsign,nc_location,nc_gps,s_title,s_data,s_color from Members left outer join Operators on Operators.m_id=Members.m_id left outer join Incidents on Incidents.i_id=Operators.i_id left outer join Locations on Locations.l_id=Operators.l_id left outer join Net_Controls on Net_Controls.nc_id=Operators.nc_id left outer join Status_Codes on Status_Codes.s_id=Members.m_status");
$oq->execute();
$orow = $oq->fetchAll(PDO::FETCH_ASSOC);
foreach($orow as $o) {
	$cs = strtoupper($o['m_callsign']);
	$ops[$cs]['mid'] = $o['m_id'];
	$ops[$cs]['name'] = $o['m_lname'].", ".$o['m_fname'];
	$ops[$cs]['incident'] = $o['i_id'];
	$ops[$cs]['locationname'] = '';
	$ops[$cs]['locationgps'] = '';
	$ops[$cs]['location'] = (!empty($o['l_id'])) ? $o['l_id']:$o['m_prestage_lid'];
	if (empty($o['l_id'])) {
		$lq = $conn->prepare("select l_tactical,l_name,l_gps from Locations where l_id=:lid limit 1");
		$lq->execute(array(':lid'=>$o['m_prestage_lid']));
		$larr = $lq->fetchAll(PDO::FETCH_ASSOC);
		foreach($larr as $l) {
			$ops[$cs]['locationname'] = $l['l_tactical'].": ".$l['l_name'];
			$ops[$cs]['locationgps'] = $l['l_gps'];
		}
	}
	else {
		$ops[$cs]['locationname'] = $o['l_tactical'].": ".$o['l_name'];
		$ops[$cs]['locationgps'] = $o['l_gps'];
	}
	$ops[$cs]['netcontrol'] = $o['nc_id'];
	$ops[$cs]['netcontrolcall'] = $o['nc_callsign'].": ".$o['nc_location'];
	$ops[$cs]['netcontrolgps'] = $o['nc_gps'];
	$ops[$cs]['statusid'] = $o['m_status'];
	$ops[$cs]['status'] = str_replace(" ","&nbsp;",$o['s_title']);
	$ops[$cs]['statusclr'] = $o['s_color'];
	$ops[$cs]['statustxt'] = $o['s_data'];
	$ops[$cs]['band'] = $o['o_band'];
	$ops[$cs]['data'] = $o['o_data'];
}
ksort($ops);
foreach($ops as $cs => $data) {
	echo "<tr><td align=left title='".$data['locationname']."' style='cursor:pointer'>".$cs."</td><td align=left><a href='ARES_Member_Manage.php?mid=".$data['mid']."&admin=".$_GET['admin']."' target='_blank' title='Click to view ".$cs." Member Details'>".$data['name']."</a></td><td style='background-color:".$data['statusclr'].";cursor:pointer;' title='".$data['statustxt']."' onclick=\"showStatusEdit(".$data['mid'].",'".$cs.": ".$data['name']."',".$data['statusid'].")\">".$data['status']."</td><th cellpadding=0>";
	echo (!empty($data['locationname'])) ? "<a href='https://maps.google.com/?q=".$data['locationgps']."' target='_blank' title='".$data['locationname']."'><img src='images/icon-google-maps.svg' width=14 border=0 style='opacity:.3'></a>":((!empty($lid)) ? "<input type=checkbox title='Click to Assign to this Location'>":"");
	echo "</th><th cellpadding=0>";
	echo (!empty($ncid) && $data['netcontrol']==$ncid) ? "<a href='https://maps.google.com/?q=".$data['netcontrolgps']."' target='_blank' title='".$data['netcontrolcall']."'><img src='images/icon-google-maps.svg' width=14 border=0 style='opacity:.3'></a>":((!empty($ncid) && empty($data['netcontrol'])) ? "<input type=checkbox title='Click to Assign to this Net Control'>":((empty($ncid) && !empty($data['netcontrol'])) ? "<a href='https://maps.google.com/?q=".$data['netcontrolgps']."' target='_blank' title='".$data['netcontrolcall']."'><img src='images/icon-google-maps.svg' width=14 border=0 style='opacity:.3'></a>":""));
	echo "</th></tr>\n"; 
}
?>
</table>
<!--/div-->
<div style='margin-top:6px;font-size:11px'><a href='ARES_Operator_Status.php' target='_blank'>Operator Check In</a> | <a href='ARES_Member_Manage.php?admin=1' target='_blank'>Member Manage</a></div>
</div>
<div id="opstab" style="text-align:center;font-size:10px;color:green;cursor:pointer;margin-top:6px;padding:3px;border-radius:2px;background-color:#aaffcc;">Toggle Operator Status</div>
</div>
</div>
<!-- end ARES_INCLUDE_Operator_Status.php -->
