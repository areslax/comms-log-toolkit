<?php
/* ################################
 * ARES_Incident_Manage.php
 * Interface for managing incidents
 * ################################ */
#if (empty($_GET['admin'])) { header("Location: https://km6wka.net/ares");exit; }
require "db_conn.php";
$thisloc = "";
$searchresults = "";
//got new incident form submit
if (!empty($_POST['addincident'])) {
	$togo = array("(",")"," ","-","_",".","/");
	$q = $conn->prepare("insert into Incidents (i_type,i_tactical,i_status,i_name,i_lead_id,i_gps) values (:type,:tactical,1,:name,:leadid,:gps)");
	$q->execute(array(':type'=>$_POST['i_type'],':tactical'=>$_POST['i_tactical'],':name'=>$_POST['i_name'],':leadid'=>$_POST['i_lead_id_new'],':gps'=>$_POST['i_gps']));
	$iid = $conn->lastInsertId();
	//update common js file
	file_get_contents("scripts/cron_generateNewLocations.php");
}

//got update incident form submit
if (!empty($_POST['updateincident'])) {
	$togo = array("(",")"," ","-","_",".","/");
	$q = $conn->prepare("update Incidents set i_type=:type,i_tactical=:tactical,i_status=:status,i_name=:name,i_lead_id=:leadid,i_gps=:gps where i_id=:iid limit 1");
	$q->execute(array(':type'=>$_POST['i_type'],':tactical'=>$_POST['i_tactical'],':status'=>$_POST['i_status'],':name'=>$_POST['i_name'],':leadid'=>$_POST['i_lead_id'],':gps'=>$_POST['i_gps'],':iid'=>$_POST['i_id']));
	//update common js file
	file_get_contents("scripts/cron_generateNewLocations.php");
}

######## THINK ABOUT INCIDENT LIAISONS #######

$ctyps = array(0=>"",1=>"Email",2=>"Home Phone",3=>"Work Phone",4=>"Cell Phone",5=>"Account");

//got new liaison form submit
if (!empty($_POST['addliaison'])) {
	$lid = $_POST['l_id'];
	$lic_type = $_POST['lic_type'];
	$li_active = (!empty($_POST['li_active'])) ? 1:0;
	if ($lic_type>1 && $lic_type!=5) {
		//clean up phone number before storing
		$togo = array("(",")"," ","-","_",".","/");
		$pno = ltrim(str_replace($togo,'',urldecode($_POST['lic_data'])),'1');
		$lic_data = substr($pno,0,3)."-".substr($pno,3,3)."-".substr($pno,-4,strlen($pno));
	}
	else {
		$lic_data = urldecode($_POST['lic_data']);
	}
	$q = $conn->prepare("insert into Liaisons (l_id,li_title,li_fname,li_lname,li_note,li_active) values (:lid,:title,:fname,:lname,:note,:active)");
	$q->execute(array(':lid'=>$lid,':title'=>$_POST['li_title'],':fname'=>$_POST['li_fname'],':lname'=>$_POST['li_lname'],':note'=>urldecode($_POST['li_note']),':active'=>$li_active));
	$liid = $conn->lastInsertId();

	$q = $conn->prepare("insert into Liaison_Contacts (li_id,lic_type,lic_data) values (:liid,:type,:data)");
	$q->execute(array(':liid'=>$liid,':type'=>$lic_type,':data'=>$lic_data));
}

//got update liaison form submit
if (!empty($_POST['updateliaison'])) {
	$active = (!empty($_POST['li_active'])) ? 1:0;
	//update Liaisons table
	$q = $conn->prepare("update Liaisons set li_fname=:fname,li_lname=:lname,li_title=:title,li_note=:note,li_active=:active where li_id=:liid");
	$q->execute(array(':fname'=>$_POST['li_fname'],':lname'=>$_POST['li_lname'],':title'=>$_POST['li_title'],':note'=>$_POST['li_note'],':active'=>$active,':liid'=>$_POST['li_id']));
	//updating Liaison_Contacts data
	if (!empty($_POST['lic_types'])) {
		foreach($_POST['lic_types'] as $k => $v) {
			if ($k>1 && $k!=5) {
				//clean up phone number before storing
				$togo = array("(",")"," ","-","_",".","/");
				$pno = ltrim(str_replace($togo,'',urldecode($_POST['lic_datas'][$k])),'1');
				$lic_data = substr($pno,0,3)."-".substr($pno,3,3)."-".substr($pno,-4,strlen($pno));
			}
			else {
				$lic_data = urldecode($_POST['lic_datas'][$k]);
			}
			$q = $conn->prepare("update Liaison_Contacts set lic_type=:type,lic_data=:data where lic_id=:licid limit 1");
			$q->execute(array(':type'=>$v,':data'=>$lic_data,':licid'=>$k));
		}
	}
	//adding new Liaison_Contacts data
	if (!empty($_POST['new_lic_type'])) {
		$lic_data = $_POST['new_lic_data'];
		if ($_POST['new_lic_type']>1 && $_POST['new_lic_type']!=5) {
			//clean up phone number before storing
			$togo = array("(",")"," ","-","_",".","/");
			$pno = ltrim(str_replace($togo,'',urldecode($lic_data)),'1');
			$lic_data = substr($pno,0,3)."-".substr($pno,3,3)."-".substr($pno,-4,strlen($pno));
		}
		$q = $conn->prepare("insert into Liaison_Contacts (li_id,lic_type,lic_data) values (:liid,:type,:data)");
		$q->execute(array(':liid'=>$_POST['li_id'],':type'=>$_POST['new_lic_type'],':data'=>$lic_data));
	}
}

#### END LIAISONS ####

//got iid so show location info
if ((!empty($_GET['iid']) && $_GET['iid']!='undefined') || !empty($iid)) {
	$iid = (!empty($iid)) ? $iid:$_GET['iid'];
	$q = $conn->prepare("select * from Incidents where i_id=:iid");
	$q->execute(array(':iid'=>$iid));
	$res = $q->fetchAll(PDO::FETCH_ASSOC);
	$a = "{\"result\":[";
	foreach($res as $r) {
		foreach($r as $key => $val) {
			$r[$key] = addslashes($val);
		}
		$thisinc = $r['i_name'];
		//get incident lead
		$lq = $conn->query("select m_callsign,m_fname,m_lname from Members where m_id='".$r['i_lead_id']."' limit 1");
		$lq->execute();
		$lr = $lq->fetchAll(PDO::FETCH_ASSOC);
		foreach($lr as $ld) {
			$thislead = strtoupper($ld['m_callsign']).": ".$ld['m_fname']." ".$ld['m_lname'];
		}
		//get incident types
		$itypes = "";
		$iq = $conn->query("select * from Incident_Types order by it_id");
		$ires = $iq->fetchAll(PDO::FETCH_ASSOC);
		foreach($ires as $ir) {
			$sel = ($r['i_type']==$ir['it_id']) ? " selected":"";
			$itypes .= "<option value=".$ir['it_id'].$sel.">".$ir['it_data'].": ".$ir['it_title']."</option>";
		}
		$gpsicon = (!empty($r['i_gps'])) ? " <a href='https://maps.google.com/?q=".$r['i_gps']."' target='_blank' title='Click to view location on Google Maps'><img src='images/icon-google-maps.svg' alt='maps icon' border=0 width=14 align=absmiddle></a>":"";
		$actchkd = (!empty($r['i_status'])) ? " checked":"";
		$mapgps = (empty($r['i_gps'])) ? 0:$r['i_gps'];
		$a = "<form method=post>\n<input type=hidden name=updateincident value=1>\n<input type=hidden name=i_id value='".$iid."'>\n<table border=0 cellpadding=6 cellspacing=0 style='width:580px'>
<tr><td>Name</td><td colspan=3><input type=text name=i_name class='incident' onfocus='this.select();' style='width:380px;text-align:left;' placeholder='i.e. Mojave Earthquake' value='".$r['i_name']."'></td></tr>
<tr><td>Type</td><td><select name=i_type style='width:100px'><option value=0></option>".$itypes."</select></td><td>GPS".$gpsicon."</td><td><input type=text name=i_gps size=21 value='".$r['i_gps']."' onclick='this.select()' placeholder='Copy & Paste from Map'><div class='tooltip' onclick='openMap()'><img src='images/icon-help.png' width=16 alt='GPS Help' style='cursor:pointer;margin:0 0 0 4px;' align=absmiddle><span class='tooltiptext'><ol style='margin-left:0px'><li>Click to Open Google Maps</li><li>Right-click Incident Location</li><li>Click \"What's Here?\"</li><li>Copy GPS from Maps, then<br>close Maps window</li><li>Paste into this field</li></ol></span></div></td></tr>
<tr><td>Tac Call</td><td><input type=text size=12 name=i_tactical value='".$r['i_tactical']."'></td><td>Incident Lead</td><td><input type=hidden name=i_lead_id id=i_lead_id value=".$r['i_lead_id']."><input type=text size=21 class='people' name=i_lead value='".$thislead."' onfocus='this.select()'></td></tr>";
		$a .= "<tr><th colspan=4><input type=checkbox name=i_status value=1".$actchkd."> Active</th></tr>\n";
		$a .= "<tr><th colspan=4 style='border-bottom:solid 1px black'><input type=submit value='Update This Incident Info'></th></tr>\n";
		$a .= "</table>\n</form>\n";
	}

/* ####### IF USING INCIDENT LIAISONS #######
   ##### MAYBE USE THIS AREA FOR DEPLOYED MEMBERS #####
	$q = $conn->prepare("select * from Liaisons where l_id=:lid");
	$q->execute(array(':lid'=>$lid));
	$res = $q->fetchAll(PDO::FETCH_ASSOC);
	$nrows = sizeOf($res);
	if ($nrows>0) {
		$a .= "<table border=0 cellpadding=6 cellspacing=0 style='width:580px'><tr style='background-color:lightblue'><th colspan=4>Liaisons</th></tr></table>\n";
	}
	foreach($res as $r) {
		foreach($r as $key => $val) {
			$r[$key] = addslashes($val);
		}
		$liid = $r['li_id'];
		$contacts = array();
		$contacts['id'] = $liid;
		$contacts['title'] = $r['li_title'];
		$contacts['fname'] = $r['li_fname'];
		$contacts['lname'] = $r['li_lname'];
		$contacts['note'] = $r['li_note'];
		$contacts['data'] = array();
		$contacts['data']['id'] = array();
		$contacts['data']['type'] = array();
		$contacts['data']['carrier'] = array();
		$contacts['data']['data'] = array();
		$q2 = $conn->prepare("select * from Liaison_Contacts where li_id=:liid order by lic_type");
		$q2->execute(array(':liid'=>$liid));
		$res2 = $q2->fetchAll(PDO::FETCH_ASSOC);
		foreach($res2 as $r2) {
			$licid = $r2['lic_id'];
			$contacts['data']['id'][] = $licid;
			$contacts['data']['type'][] = $r2['lic_type'];
			$contacts['data']['carrier'][] = $r2['lic_carrier'];
			$contacts['data']['data'][] = $r2['lic_data'];
		}

		$a .= "<form method=post>\n<input type=hidden name=updateliaison value=1>\n<input type=hidden name=li_id value='".$liid."'>\n";
		$a .= "<table border=0 cellpadding=6 cellspacing=0 style='width:580px'>\n";
		$a .= "<tr><td>Title</td><td><input type=text name=li_title size=14 style='font-weight:bold' value='".$contacts['title']."'></td><td>Note</td><td><input type=text name=li_note value='".$contacts['note']."'></td></tr>\n";
		$a .= "<tr><td>First Name</td><td><input type=text name=li_fname size=14 style='font-weight:bold' value='".$contacts['fname']."'></td><td>Last Name</td><td><input type=text name=li_lname size=14 style='font-weight:bold' value='".$contacts['lname']."'> <input type=checkbox name=li_active value=1";
		if (!empty($r['li_active'])) { $a .= " checked"; }
		$a .= " title='Liaison Active?'> <button type=button onclick=\"deleteLiaison(".$liid.")\" title='Delete This Liaison'>-</button></td></tr>\n";
		$i=0;
		foreach($contacts['data']['type'] as $k) {
			$icn = ($k>1 && $k!=5) ? " <a href='tel:".$contacts['data']['data'][$i]."' title='Click to launch your phone dialer'><img src='images/icon-phone.svg' alt='phone icon' border=0 width=14 align=absmiddle></a>":" <a href='mailto:".$contacts['data']['data'][$i]."?subject=ARES Message' title='Click to launch your email program'><img src='images/icon-email.svg' alt='email icon' border=0 width=14 align=absmiddle></a>";
			$a .= "<tr id=lcrow".$i."><td><select name=lic_types[".$contacts['data']['id'][$i]."] style='width:120px'>";
			foreach($ctyps as $t => $v) {
				$sel = ($k==$t) ? " selected":"";
				$a .= "<option value=".$t.$sel.">".$v."</option>";
			}
			$a .= "</select></td><td colspan=3><input type=text name=lic_datas[".$contacts['data']['id'][$i]."] size=44 value='".$contacts['data']['data'][$i]."'> <button type=button onclick=\"deleteMe(".$liid.",'".$contacts['data']['data'][$i]."',".$i.")\" title='Delete This Entry'>-</button>".$icn."</td></tr>\n";
			$i++;
		}
		$a .= "<tr id=lcrow".$i."><td>Add <select name=new_lic_type style='width:80px'>";
		foreach($ctyps as $k => $v) {
			$a .= "<option value=".$k.">".$v."</option>";
		}
		$a .= "</select></td><td colspan=3><input type=text name=new_lic_data size=44 placeholder='Choose Contact Type then enter data, here'></td></tr>\n";

		$a .= "<tr><th colspan=4 style='border-bottom:solid 1px black'><input type=submit value='Update ".$contacts['fname']." ".$contacts['lname']." Liaison Info'></th></tr>\n";
		$a .= "</table>\n</form>\n\n";
	}

	$a .= "<form method=post>\n<input type=hidden name=addliaison value=1>\n<input type=hidden name=l_id value='".$lid."'>\n";
	$a .= "<table border=0 cellpadding=6 cellspacing=0 style='width:580px'>\n";
	$a .= "<tr><th colspan=4 style='background-color:lightgrey'>Add New Liaison</th></tr>\n";
	$a .= "<tr><td>Title</td><td><input type=text name=li_title size=14 style='font-weight:bold'></td><td>Note</td><td><input type=text name=li_note></td></tr>\n";
	$a .= "<tr><td>First Name</td><td><input type=text name=li_fname size=14 style='font-weight:bold'></td><td>Last Name</td><td><input type=text name=li_lname size=14 style='font-weight:bold'> <input type=checkbox name=li_active value=1 checked title='Liaison Active?'></td></tr>\n";
	$a .= "<tr><td>Add <select name=lic_type style='width:80px'>";
	foreach($ctyps as $k => $v) {
		$a .= "<option value=".$k.">".$v."</option>";
	}
	$a .= "</select></td><td colspan=3><input type=text name=lic_data size=44 placeholder='Choose Contact Type then enter data, here'></td></tr>\n";

	$a .= "<tr><th colspan=4 style='border-bottom:solid 1px black'><input type=submit value='Add This Liaison'></th></tr>\n";
	$a .= "</table>\n</form>\n\n";
/* ##### END LIAISONS ##### */

	$searchresults = $a;
}

//get incident types
$itypes = "";
$itarry = array();
$iq = $conn->query("select * from Incident_Types order by it_id");
$ires = $iq->fetchAll(PDO::FETCH_ASSOC);
foreach($ires as $ir) {
	$itypes .= "<option value=".$ir['it_id'].$sel.">".$ir['it_data'].": ".$ir['it_title']."</option>";
	$itarry[$ir['it_id']]['title'] = $ir['it_title'];
	$itarry[$ir['it_id']]['data'] = $ir['it_data'];
}
?>
<!doctype html>
<html lang="en">
<head><title>ARES Incident Management</title>

<?php
include "common_includes.php";
?>

<script type="text/javascript">
function showIncident(iid) {
//	location.href = "ARES_Incident_Manage.php?iid="+iid;
}
function openMap() {
	window.open("https://google.com/maps/","maps","width=500;height=500;");
}
function showSearch(vis) {
	var vis1 = (vis=="hidden") ? "block":"none";
	var vis2 = (vis=="hidden") ? "visible":"hidden";
	jQuery("#searchform").css("display",vis1).fadeIn(500);
	jQuery("#i_lookup").focus();
	jQuery("#searchform").css("visibility",vis2);
}
function showAddNew(vis) {
	var vis = (vis=="hidden") ? "visible":"hidden";
	jQuery("#addnew").css("visibility",vis).fadeIn(500);
	jQuery("#i_name").focus();
}
function deleteMe(liid,data,rid) {
	var datastr = "liid="+liid+"&data="+encodeURIComponent(data);
	jQuery.ajax({
		type: "POST",
		url: "ajax_delete_liaison_contact.php",
		data: datastr,
		success: function(a,b,c){
			console.log(a);
			jQuery("#lcrow"+rid).css("display","none").fadeOut(500);
			jQuery("#lcrow"+rid).css("visibility","hidden");
		}
	});

}
function deleteLiaison(liid) {
	var doit = confirm("Really delete this liaison?\n'OK' to delete, 'Cancel' to keep");
	if (!doit) { return false; }
	var datastr = "liid="+liid;
	jQuery.ajax({
		type: "POST",
		url: "ajax_delete_liaison.php",
		data: datastr,
		success: function(a,b,c){
			console.log(a);
			location.href = location.href;
		}
	});

}
function deleteType(tid) {
	jQuery.ajax({
		type: "POST",
		url: "ajax_incident_type.php",
		data: "tid="+tid,
		success: function(a,b,c){
			console.log(a);
			location.href = location.href;
		}
	});
}
function addType() {
	var datastr = "title="+jQuery("#it_title_new").val()+"&data="+jQuery("#it_data_new").val();
	jQuery.ajax({
		type: "POST",
		url: "ajax_incident_type.php",
		data: datastr,
		success: function(a,b,c){
			console.log(a);
			location.href = location.href;
		}
	});
}
function updateType(tid,val,typ) {
	var ttyp = (typ) ? "it_title":"it_data";
	var datastr = "tid="+tid+"&val="+val+"&data="+ttyp;
	jQuery.ajax({
		type: "POST",
		url: "ajax_incident_type.php",
		data: datastr,
		success: function(a,b,c){
			console.log(a);
		}
	});
}
function showStatusEdit(mid,mnam,sid) {
	var mloc = "utility_edit_member_status.php?mid="+mid+"&mnam="+encodeURIComponent(mnam)+"&sid="+sid;
	jQuery("#mstatus_modal").attr("src",mloc);
	jQuery("#mstatus_modal").css("display","block");
}
</script>

</head>
<body>
<center>

<h2>ARES Incident Management</h2>

<?php include "ARES_INCLUDE_Operator_Status.php"; ?>
<!--
<div id=members style='position:fixed;top:10px;left:10px;padding:0px;background-color:white;text-align:center;'>
<table id=table_memstatus border=1 cellpadding=1 cellspacing=0 style='border-color:white;'>
<tr><th colspan=4>Operator Status</th></tr>
<?php
$ops = array();
#one specific incident
#$oq = $conn->prepare("select Members.m_id,m_callsign,m_status,m_fname,m_lname,i_id,l_id,nc_id,s_title from Members left outer join Operators on Operators.m_id=Members.m_id left outer join Status_Codes on Status_Codes.s_id=Members.m_status where i_id=:iid");
#$oq->execute(array(':iid'=>$incidentid));
$oq = $conn->prepare("select Members.m_id,m_callsign,m_status,m_fname,m_lname,m_prestage_lid,Operators.i_id,Operators.l_id,Operators.nc_id,s_title,s_data,s_color,l_tactical,l_name,nc_callsign from Members left outer join Operators on Operators.m_id=Members.m_id left outer join Status_Codes on Status_Codes.s_id=Members.m_status left outer join Locations on Locations.l_id=Operators.l_id left outer join Net_Controls on Net_Controls.nc_id=Operators.nc_id");
$oq->execute();
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
	echo "<tr><td align=left title='".$data['locationname']."' style='cursor:pointer'>".$cs."</td><td align=left><a href='ARES_Member_Manage.php?mid=".$data['mid']."&admin=".$_GET['admin']."' target='_blank' title='Click to view ".$cs." Member Details'>".$data['name']."</a></td><td style='background-color:".$data['statusclr'].";cursor:pointer;' title='".$data['statustxt']."' onclick=\"showStatusEdit(".$data['mid'].",'".$cs.": ".$data['name']."',".$data['statusid'].")\">".$data['status']."</td><th cellpadding=0><input type=checkbox title='Under this Net Control'></th></tr>\n";
}
?>
</table>
<div style='margin-top:6px;font-size:11px'><a href='ARES_Operator_Status.php' target='_blank'>Operator Check In</a> | <a href='ARES_Member_Manage.php?admin=1' target='_blank'>Member Manage</a></div>
</div>
-->

<div id=inctypes style='position:fixed;top:10px;right:10px;padding:8px;background-color:white;border:solid 1px grey;border-radius:3px;text-align:center;'>
<table border=0 cellpadding=3 cellspacing=0>
<tr><th colspan=3>Incident Types</th></tr>
<?php
foreach($itarry as $it => $idata) {
	echo "<tr><td><input type=text size=8 name=it_data value='".$idata['data']."' onblur='updateType(".$it.",this.value,0)'></td><td><input type=text size=12 name=it_title value='".$idata['title']."' onblur='updateType(".$it.",this.value,1)'></td><td><button type=button onclick='deleteType(".$it.")'>-</button></td></tr>\n";
}
echo "<tr><td><input type=text size=8 id=it_data_new placeholder='Add Code'></td><td><input type=text size=12 id=it_title_new placeholder='and Type'></td><td><button type=button onclick='addType()'>+</button></td></tr>";
?>
</table>
</div>

<?php
$xicon = (empty($iid)) ? "":"<img src='images/icon-delete.png' border=0 width=16 align=absmiddle alt='reset icon' title='Reset Net Control' style='cursor:pointer;margin-left:2px;' onclick=\"location.href='ARES_Incident_Manage.php'\">";
?>

<input type=text name=i_lookup id=i_lookup class='incident' placeholder="To look up an Incident, Click here and Start Typing ..." style="width:500px;text-align:center;font-weight:bold;" value="<?=$thisinc?>" onfocus='this.select()'><?=$xicon?>
<div id=results>
<?=$searchresults?>
</div>

<div onclick="showAddNew(document.getElementById('addnew').style.visibility)" style="padding:4px;font-size:13px;margin-top:16px;width:500px;text-align:center;background-color:lightgrey;cursor:pointer;">or Click here to Show the New Incident Form</div><br>
<div id=addnew style="visibility:hidden;display:none;">
<form method=post>
<input type=hidden name=addincident value=1>
<table border=0 cellpadding=6 cellspacing=0>
<tr><td>Name</td><td colspan=3><input type=text id=i_name name=i_name class="incident" style="width:380px;text-align:left;" placeholder="i.e. Mojave Earthquake"></td></tr>
<tr><td>Type</td><td><select name=i_type style="width:100px"><option value=0></option><?=$itypes?></select></td><td>GPS</td><td><input type=text name=i_gps id=i_gps size=21 onclick="this.select()" placeholder="Copy & Paste from Map"><div class="tooltip" onclick="openMap()"><img src="images/icon-help.png" width=16 alt="GPS Help" style="cursor:pointer;margin:0 0 0 4px;" align=absmiddle><span class="tooltiptext"><ol style="margin-left:0px"><li>Click to Open Google Maps</li><li>Right-click Incident Location</li><li>Click "What's Here?"</li><li>Copy GPS from Maps, then<br>close Maps window</li><li>Paste into this field</li></ol></span></div></td></tr>
<tr><td>Tac Call</td><td><input type=text size=12 id=i_tactical name=i_tactical></td><td>Incident Lead</td><td><input type=hidden name=i_lead_id_new id=i_lead_id_new><input type=text class='people' size=21 name=i_lead></td></tr>
<tr><th colspan=4><input type=submit value="Save This New Incident"></th></tr>
</table>
</form>
</div>
</center>

<iframe id=mstatus_modal style="position:absolute;top:40px;left:30px;width:200px;background-color:white;border:solid 1px red;border-radius:2px;display:none;"></iframe>

<script type="text/javascript">
//autocomplete
var incfld = new Array();
var pplfld = new Array();
jQuery(function() {

	jQuery(".incident").autocomplete({
		source: incidents,
		focus: function(event, ui) {
			event.preventDefault();
			jQuery(this).val(ui.item.label);
		},
		select: function(event, ui) {
			event.preventDefault();
			jQuery(this).val(ui.item.value);
			incfld[this.id]=ui.item.value;
			location.href="ARES_Incident_Manage.php?admin=1&iid="+ui.item.iid;
		}
	});

	jQuery(".people").autocomplete({
		source: people,
		focus: function(event, ui) {
			event.preventDefault();
			jQuery(this).val(ui.item.label);
		},
		select: function(event, ui) {
			event.preventDefault();
			jQuery(this).val(ui.item.label);
			jQuery("#i_lead_id").val(ui.item.mid);
			jQuery("#i_lead_id_new").val(ui.item.mid);
			pplfld[this.id]=ui.item.value;
		}
	});

});
</script>
</body>
</html>


