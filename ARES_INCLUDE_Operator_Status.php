<?php
/* ######################################
 * ARES_INCLUDE_Operator_Status.php
 * Include displays Operator Status,
 * depending on which interface is viewed
 * ###################################### */
?>
<!-- ARES_INCLUDE_Operator_Status.php -->
<div id=members style='position:fixed;top:10px;left:10px;padding:0px;background-color:white;text-align:center;'>
<table id=table_memstatus border=1 cellpadding=1 cellspacing=0 style='border-color:white;width:280px;'>
<tr><th colspan=4>Operator Status</th></tr>
<?php
$ops = array();
if (!empty($iid)) {
	//single incident
	$oq = $conn->prepare("select Members.m_id,m_callsign,m_status,m_fname,m_lname,i_id,l_id,nc_id,s_title from Members left outer join Operators on Operators.m_id=Members.m_id left outer join Status_Codes on Status_Codes.s_id=Members.m_status where i_id=:iid");
	$oq->execute(array(':iid'=>$iid));
	$labl = "Involved in this Incident";
}
else if (!empty($lid)) {
	//single location
        $oq = $conn->prepare("select Members.m_id,m_callsign,m_status,m_fname,m_lname,i_id,l_id,nc_id,s_title from Members left outer join Operators on Operators.m_id=Members.m_id left outer join Status_Codes on Status_Codes.s_id=Members.m_status where l_id=:lid");
        $oq->execute(array(':lid'=>$lid));
	$labl = "Deploys to this Location";
}
else if (!empty($ncid)) {
	//single net control
        $oq = $conn->prepare("select Members.m_id,m_callsign,m_status,m_fname,m_lname,i_id,l_id,nc_id,s_title from Members left outer join Operators on Operators.m_id=Members.m_id left outer join Status_Codes on Status_Codes.s_id=Members.m_status where nc_id=:ncid");
        $oq->execute(array(':ncid'=>$ncid));
	$labl = "Associated with this Net Control";
}
else {
	//all operators
	$oq = $conn->prepare("select Members.m_id,m_callsign,m_status,m_fname,m_lname,m_prestage_lid,Operators.i_id,Operators.l_id,Operators.nc_id,s_title,s_data,s_color,l_tactical,l_name,nc_callsign from Members left outer join Operators on Operators.m_id=Members.m_id left outer join Status_Codes on Status_Codes.s_id=Members.m_status left outer join Locations on Locations.l_id=Operators.l_id left outer join Net_Controls on Net_Controls.nc_id=Operators.nc_id");
	$oq->execute();
	$labl = "";
}
$orow = $oq->fetchAll(PDO::FETCH_ASSOC);
foreach($orow as $o) {
	$cs = strtoupper($o['m_callsign']);
	$ops[$cs]['mid'] = $o['m_id'];
	$ops[$cs]['name'] = $o['m_lname'].", ".$o['m_fname'];
	$ops[$cs]['incident'] = $o['i_id'];
	$ops[$cs]['location'] = (!empty($o['l_id'])) ? $o['l_id']:$o['m_prestage_lid'];
	if (empty($o['l_id'])) {
		$lq = $conn->prepare("select l_tactical,l_name from Locations where l_id=:pid limit 1");
		$lq->execute(array(':pid'=>$o['m_prestage_lid']));
		$larr = $lq->fetchAll(PDO::FETCH_ASSOC);
		foreach($larr as $l) {
			$ops[$cs]['locationname'] = $l['l_tactical'].": ".$l['l_name'];
		}
	}
	else {
		$ops[$cs]['locationname'] = $o['l_tactical'].": ".$o['l_name'];
	}
	$ops[$cs]['netcontrol'] = $o['nc_id'];
	$ops[$cs]['statusid'] = $o['m_status'];
	$ops[$cs]['status'] = $o['s_title'];
	$ops[$cs]['statusclr'] = $o['s_color'];
	$ops[$cs]['statustxt'] = $o['s_data'];
	$ops[$cs]['band'] = $o['o_band'];
	$ops[$cs]['data'] = $o['o_data'];
}
ksort($ops);
foreach($ops as $cs => $data) {
	echo "<tr><td align=left title='".$data['locationname']."' style='cursor:pointer'>".$cs."</td><td align=left><a href='ARES_Member_Manage.php?mid=".$data['mid']."&admin=".$_GET['admin']."' target='_blank' title='Click to view ".$cs." Member Details'>".$data['name']."</a></td><td style='background-color:".$data['statusclr'].";cursor:pointer;' title='".$data['statustxt']."' onclick=\"showStatusEdit(".$data['mid'].",'".$cs.": ".$data['name']."',".$data['statusid'].")\">".$data['status']."</td><th cellpadding=0><input type=checkbox title='".$labl."'></th></tr>\n";
}
?>
</table>
<div style='margin-top:6px;font-size:11px'><a href='ARES_Operator_Status.php' target='_blank'>Operator Check In</a> | <a href='ARES_Member_Manage.php?admin=1' target='_blank'>Member Manage</a></div>
</div>
<!-- end ARES_INCLUDE_Operator_Status.php -->
