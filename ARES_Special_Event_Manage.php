<?php
/* ##############################################
 * ARES_Special_Event_Manage.php
 * Interface for managing Special Events
 *  similar to Incidents
 *  coordinates with Net Controls in the same way
 * ############################################## */
require "db_conn.php";
$thisloc = "";
$searchresults = "";
//got new event form submit
if (!empty($_POST['addevent'])) {
	$togo = array("(",")"," ","-","_",".","/");
	$q = $conn->prepare("insert into Events_Special (ev_type,ev_name,ev_date_start,ev_date_end,ev_note) values (:typ,:nam,:date1,:date2,:note)");
	$q->execute(array(':typ'=>$_POST['ev_type'],':nam'=>$_POST['ev_name'],':date1'=>$_POST['date_start'],':date2'=>$_POST['date_end'],':note'=>$_POST['ev_note']));
	$evid = $conn->lastInsertId();
	//update common js file
	file_get_contents("https://km6wka.net/ares/scripts/generateSpecialEvents.php?eventid=".$evid);
}

//got update event form submit
if (!empty($_POST['updateevent'])) {
	$togo = array("(",")"," ","-","_",".","/");
	$q = $conn->prepare("update Events_Special set ev_type=:typ,ev_nam=:name,ev_date_start=:date1,ev_date_end=:date2,ev_note=:note where ev_id=:evid limit 1");
	$q->execute(array(':typ'=>$_POST['ev_type'],':nam'=>$_POST['ev_name'],':date1'=>$_POST['date_start'],':date2'=>$_POST['date_end'],':note'=>$_POST['ev_note'],':evid'=>$_POST['ev_id']));
	//update common js file
	file_get_contents("https://km6wka.net/ares/scripts/generateSpecialEvents.php?eventid=".$_POST['ev_id']);
}

//got new event location form submit
if (!empty($_POST['addnewloc'])) {
	$q = $conn->prepare("insert into Event_Locations (ev_id,el_name,el_note,el_gps) values (:evid,:nam,:note,:gps)");
	$q->execute(array(":evid"=>$_POST['ev_id'],":nam"=>$_POST['el_name'],":note"=>$_POST['el_note'],":gps"=>str_replace(" ","",$_POST['el_gps'])));
	file_get_contents("https://km6wka.net/ares/scripts/generateSpecialEvents.php?eventid=".$_POST['evid']);
}

//got new event staff form submit
if (!empty($_POST['addnewstaff'])) {
	$q = $conn->prepare("insert into Event_Staff (ev_id,el_id,es_fname,es_lname,es_callsign,es_note) values (:evid,:elid,:fnam,:lnam,:call,:note");
	$q->execute(array(":evid"=>$_POST['ev_id'],":elid"=>$_POST['el_id'],":fnam"=>$_POST['es_fname'],":lname"=>$_POST['lname']." ".$_POST['callsign'],":call"=>$_POST['es_callsign'],":note"=>$_POST['es_note']));
	file_get_contents("https://km6wka.net/ares/scripts/generateSpecialEvents.php?eventid=".$_POST['evid']);
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

//got evid so show event info
if ((!empty($_GET['eventid']) && $_GET['eventid']!='undefined') || !empty($evid)) {
	$evid = (!empty($evid)) ? $evid:$_GET['eventid'];
	//dump locations in to array for dropdowns
	$lq = $conn->prepare("select * from Event_Locations where ev_id=:evid order by el_id");
	$lq->execute(array(':evid'=>$evid));
	$elocs = array();
	while($lr=$lq->fetch(PDO::FETCH_ASSOC)) {
		$elocs[$lr['el_id']]['name'] = $lr['el_name'];
		$elocs[$lr['el_id']]['gps'] = $lr['el_gps'];
		$elocs[$lr['el_id']]['note'] = $lr['el_note'];
	}
	//get event data
	$q = $conn->prepare("select * from Events_Special where ev_id=:evid");
	$q->execute(array(':evid'=>$evid));
	$a = "{\"result\":[";
	while($r=$q->fetch(PDO::FETCH_ASSOC)) {
		foreach($r as $key => $val) {
			$r[$key] = addslashes($val);
		}
		$thisinc = $r['ev_name'];
		//get event types
		$etypes = "";
		$iq = $conn->query("select * from Events_Special_Types order by est_id");
		while($ir=$iq->fetch(PDO::FETCH_ASSOC)) {
			$sel = ($r['ev_type']==$ir['est_id']) ? " selected":"";
			$etypes .= "<option value=".$ir['est_id'].$sel.">".$ir['est_name']."</option>";
		}
		$a = "<form method=post>\n<input type=hidden name=updateevent value=1>\n<input type=hidden name=ev_id value='".$evid."'>\n<table border=0 cellpadding=6 cellspacing=0 style='width:610px'>
<tr><td>Name</td><td colspan=3><input type=text name=ev_name class='incident' onfocus='this.select();' style='width:456px;text-align:left;' placeholder='i.e. Baker to Vegas' value='".$r['ev_name']."'></td></tr>
<tr><td>Type</td><td><select name=ev_type style='width:80px'><option value=0></option>".$etypes."</select></td><td>Occurs</td><td><input type=text name=date_start size=16 value='".date("Y/m/d H:i",strtotime($r['ev_date_start']))."' onclick='this.select()' class='datepicker' placeholder='Start'>-<input type=text name=date_end size=16 value='".date("Y/m/d H:i",strtotime($r['ev_date_end']))."' onclick='this.select()' class='datepicker' placeholder='End'></td></tr>";
		$a .= "<tr><th colspan=4><input type=submit value='Update This Special Event Info'> <button type=button onclick='deleteMe(".$evid.")' style='color:red'>Delete This Event</button></th></tr>\n";
		$a .= "</table>\n</form>\n";

		//get event locations
		$lq = $conn->prepare("select * from Event_Locations where ev_id=:evid order by el_name");
		$lq->execute(array(":evid"=>$evid));
		$gotlocs = ($lq->rowCount()>0) ? 1:0;
		$a .= "<h3 style='cursor:pointer;background-color:lightgrey;width:610px;height:30px;padding:6px 0 0 0;line-height:12px;' id=lochead title='Click to Toggle Event Locations'>Event Locations<br><span style='font-size:10px;font-weight:normal;'>Click to Toggle Event Locations</span></h3>\n";
		$a .= "<table id=eventlocations border=0 cellpadding=6 cellspacing=0 style='margin-top:-20px;width:610px;display:none;'>\n";
		//add new location
		$a .= "<tr>\n<td width='100%' colspan=4>\n";
		$a .= "<h4 align=center>Add New Event Location</h4>\n";
		$a .= "<form method=post><input type=hidden name=addnewloc value=1><input type=hidden name=ev_id value=".$evid.">\n";
		$a .= "<table border=0 cellpadding=2 cellspacing=0 width='100%'>\n";
		$a .= "<tr valign=top>";
		$a .= "<td>Loc Name</td><td><input type=text name=el_name></td>";
		$a .= "<td rowspan=2>Loc Note</td><td rowspan=2><textarea name=el_note rows=3></textarea></td>";
		$a .= "</tr>\n";
		$a .= "<tr><td>Loc GPS</td><td><input type=text name=el_gps placeholder='Copy/Paste from Map'><div class='tooltip' onclick='openMap()'><img src='images/icon-help.png' width=16 alt='GPS Help' style='cursor:pointer;margin:0 0 0 4px;' align=absmiddle><span class='tooltiptext'><ol style='margin-left:0px'><li>Click to Open Google Maps</li><li>Right-click desired Location</li><li>Click \"What's Here?\"</li><li>Copy GPS from Maps, then<br>close Maps window</li><li>Paste into this field</li></ol></span></div></td></tr>";
		$a .= "<tr><th colspan=4><input type=submit value='Add This New Event Location'></th></tr>\n";
		$a .= "</table>\n</form>\n\n";
		$a .= "<h4 align=center>Current Event Locations</h4>\n";
		$a .= "</td></tr>\n";
		while($ld=$lq->fetch(PDO::FETCH_ASSOC)) {
			$lid = $ld['el_id'];
			$a .= "<tr id=eloc".$lid." valign=top>";
			$a .= "<td>Loc Name</td><td><input type=text id=el_name".$lid." value='".$ld['el_name']."' onchange=\"updateLoc('el_name',".$lid.",".$evid.")\"> <a href='https://maps.google.com/?q=".$ld['el_gps']."' target='_blank' title='Click to view location on Google Maps'><img src='images/icon-google-maps.svg' alt='maps icon' border=0 width=14 align=absmiddle></a><br><button type=button style='font-size:9px'onclick=\"updateLoc('delete',".$lid.",".$evid.")\">REMOVE THIS LOCATION</button></td>";
			$a .= "<td>Loc Note</td><td><textarea id=el_note".$lid." rows=3 onchange=\"updateLoc('el_note',".$lid.",".$evid.")\">".$ld['el_note']."</textarea></td>";
			$a .= "</tr>\n";
		}
		$a .= "</table>\n</td></tr>\n";
		//end event locations


		if ($gotlocs) {
		//get event staff only if locations
		$sq = $conn->prepare("select Event_Staff.*,el_name,el_gps,el_note from Event_Staff left outer join Event_Locations on Event_Locations.el_id=Event_Staff.el_id where Event_Staff.ev_id=:evid order by el_id,es_callsign");
		$sq->execute(array(":evid"=>$evid));
		$a .= "<h3 style='cursor:pointer;background-color:lightgrey;width:610px;height:30px;padding:6px 0 0 0;line-height:12px;' id=staffhead title='Click to Toggle Event Staff'>Event Staff Assignments<br><span style='font-size:10px;font-weight:normal;'>Click to Toggle Event Staff</span></h3>\n";
		$a .= "<table id=eventstaff border=0 cellpadding=6 cellspacing=0 style='margin-top:-20px;width:610px;border-bottom:solid 1px black;display:none;'>\n";
		//add new event staff
		//get event locs
		$lgps = "'',";
		$lmenu = "<option value=0></option>";
		foreach($elocs as $lid => $ldata) {
			$lmenu .= "<option value='".$lid."'>".$ldata['name'].": ".$ldata['note']."</option>";
			$lgps .= "'".$ldata['gps']."',";
		}
		$lgps = rtrim($lgps,",");
		$a .= "<tr>\n<td width='100%'>\n";
		$a .= "<h4 align=center>Add New Event Staff Member</h4>\n";
		$a .= "<script type='text/javascript'>var lgps=new Array(".$lgps.");</script>\n";
		$a .= "<form method=post><input type=hidden name=addnewstaff value=1><input type=hidden name=ev_id value=".$evid.">\n";
		$a .= "<table border=0 cellpadding=2 cellspacing=0 width='100%'>\n";
		$a .= "<tr><td>First Name</td><td><input type=text name=es_fname style='width:160px'></td><td>Callsign</td><td><input type=text name=callsign></td></tr>\n";
		$a .= "<tr><td>Last Name</td><td><input type=text name=lname style='width:160px'></td><td>Tactical</td><td><input type=text name=es_callsign></td></tr>\n";
		$a .= "<tr valign=top><td>Staff Note</td><td><textarea name=es_note style='width:160px'></textarea></td><td>Location</td><td><select name=el_id onchange=\"getGPS('newloc',this.value)\" style='width:140px'>".$lmenu."</select> <a id=newloc href='https://maps.google.com/?q=' target='_blank' title='Click to view location on Google Maps'><img src='images/icon-google-maps.svg' alt='maps icon' border=0 width=14 align=absmiddle></a></td></tr>\n";
		$a .= "<tr><th colspan=4><input type=submit value='Add This New Staff Member'></th></tr>\n";
		$a .= "</table>\n";
		$a .= "</form>\n";
		$a .= "<h4 align=center>Current Event Staff Members</h4>\n";
                $a .= "</td></tr>\n";
		//show existing event staff for edit/delete
		while($sr=$sq->fetch(PDO::FETCH_ASSOC)) {
			$esid = $sr['es_id'];
			$lname = trim(substr($sr['es_lname'],0,strrpos($sr['es_lname']," ")));
			$calls = trim(substr($sr['es_lname'],strrpos($sr['es_lname']," ")));
			//get staff loc
			$elgps = "";
			$lmenu = "<option value=0></option>";
			foreach($elocs as $lid => $ldata) {
				$sel = ($lid==$sr['el_id']) ? " selected":"";
				if (!empty($sel)) { $elgps = $ldata['gps']; }
				$lmenu .= "<option value='".$lid."'".$sel.">".$ldata['name'].": ".$ldata['note']."</option>";
			}
			$a .= "<tr id=esrow".$esid.">\n<td width='100%'>\n";
			$a .= "<table border=0 cellpadding=2 cellspacing=0 width='100%'>\n";
			$a .= "<tr><td>First Name</td><td><input type=text id=es_fname".$esid." style='width:160px' value='".$sr['es_fname']."' onchange=\"updateStaff('es_fname',".$esid.",".$evid.");\"></td><td>Callsign</td><td><input type=text id=callsign".$esid." value='".$calls."' onchange=\"updateStaff('lname',".$esid.",".$evid.");\"></td></tr>\n";
			$a .= "<tr><td>Last Name</td><td><input type=text id=lname".$esid." style='width:160px' value='".$lname."' onchange=\"updateStaff('lname',".$esid.",".$evid.");\"></td><td>Tactical</td><td><input type=text id=es_callsign".$esid." value='".$sr['es_callsign']."' onchange=\"updateStaff('es_callsign',".$esid.",".$evid.");\"></td></tr>\n";
			$a .= "<tr valign=top><td>Staff Note<br><button type=button onclick=\"updateStaff('delete',".$esid.",".$evid.")\" style='font-size:9px'>REMOVE ".$sr['es_fname']."</button></td><td><textarea id=es_note".$esid." rows=3 style='width:160px' onchange=\"updateStaff('es_note',".$esid.",".$evid.");\">".$sr['es_note']."</textarea></td><td>Location</td><td><select id=el_id".$esid." onchange=\"updateStaff('el_id',".$esid.",".$evid.");getGPS('loc".$esid."',this.value)\" style='width:140px'>".$lmenu."</select> <a id=loc".$esid." href='https://maps.google.com/?q=".$elgps."' target='_blank' title='Click to view location on Google Maps'><img src='images/icon-google-maps.svg' alt='maps icon' border=0 width=14 align=absmiddle></a></td></tr>\n";
			$a .= "</table>\n";
			$a .= "</td></tr>\n";
		}
		$a .= "</table>\n";
		//end get staff
		}//end count lq>0
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
		$a .= " title='Liaison Active?'> <button type=button onclick=\"deleteLiaison(".$liid.")\" title='Delete This Liaison' style='color:red'>-</button></td></tr>\n";
		$i=0;
		foreach($contacts['data']['type'] as $k) {
			$icn = ($k>1 && $k!=5) ? " <a href='tel:".$contacts['data']['data'][$i]."' title='Click to launch your phone dialer'><img src='images/icon-phone.svg' alt='phone icon' border=0 width=14 align=absmiddle></a>":" <a href='mailto:".$contacts['data']['data'][$i]."?subject=ARES Message' title='Click to launch your email program'><img src='images/icon-email.svg' alt='email icon' border=0 width=14 align=absmiddle></a>";
			$a .= "<tr id=lcrow".$i."><td><select name=lic_types[".$contacts['data']['id'][$i]."] style='width:120px'>";
			foreach($ctyps as $t => $v) {
				$sel = ($k==$t) ? " selected":"";
				$a .= "<option value=".$t.$sel.">".$v."</option>";
			}
			$a .= "</select></td><td colspan=3><input type=text name=lic_datas[".$contacts['data']['id'][$i]."] size=44 value='".$contacts['data']['data'][$i]."'> <button type=button onclick=\"deleteMe(".$liid.",'".$contacts['data']['data'][$i]."',".$i.")\" title='Delete This Entry' style='color:red'>-</button>".$icn."</td></tr>\n";
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

//get event types
$etypes = "";
$etarry = array();
$iq = $conn->query("select * from Events_Special_Types order by est_id");
while($ir=$iq->fetch(PDO::FETCH_ASSOC)) {
	$etypes .= "<option value=".$ir['est_id'].$sel.">".$ir['est_name']."</option>";
	$etarry[$ir['est_id']]['name'] = $ir['est_name'];
}
?>
<!doctype html>
<html lang="en">
<head><title>ARES Special Event Management</title>

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
//lgps will be populated when there is an event id, below
function getGPS(lnk,lid) {
	jQuery("#"+lnk).attr("href","https://google.com/maps/?q="+lgps[lid]);
}
function updateStaff(fld,esid,evid) {
	if (fld=='lname') {
		var esfld = "es_lname";
		var valu = jQuery("#lname"+esid).val()+" "+jQuery("#callsign"+esid).val();
	}
	else {
		var esfld = fld;
		var valu = (fld!="delete") ? jQuery("#"+fld+esid).val():0;
	}
	var datastr = "fld="+esfld+"&val="+encodeURIComponent(valu)+"&esid="+esid+"&evid="+evid;
	jQuery.ajax({
		type: "POST",
		url: "ajax_special_event_staff_update.php",
		data: datastr,
		success: function(a,b,c){
			console.log(a);
			if (a=="deleted") {
				jQuery("#esrow"+esid).hide();
			}
		}
	});
}
function updateLoc(fld,lid,evid) {
	var valu = (fld!="delete") ? jQuery("#"+fld+lid).val():0;
	var datastr = "fld="+fld+"&val="+encodeURIComponent(valu)+"&lid="+lid+"&evid="+evid;
	jQuery.ajax({
		type: "POST",
		url: "ajax_special_event_loc_update.php",
		data: datastr,
		success: function(a,b,c){
			console.log(a);
			if (a=="deleted") {
				jQuery("#eloc"+lid).hide();
			}
		}
	});
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
	jQuery("#ev_name").focus();
}
function deleteMe(evid) {
	var doit = confirm("Really delete Event, Event Locations and Event Staff?");
	if (!doit) { return false; }
	var datastr = "evid="+evid;
	jQuery.ajax({
		type: "POST",
		url: "ajax_delete_special_event.php",
		data: datastr,
		success: function(a,b,c){
			console.log(a);
			location.href = "ARES_Special_Event_Manage.php";
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
function deleteType(estid) {
	var datastr = "tid="+estid+"&do=delete";
	jQuery.ajax({
		type: "POST",
		url: "ajax_event_type.php",
		data: datastr,
		success: function(a,b,c){
			console.log(a);
			location.href = location.href;
		}
	});
}
function addType() {
	var datastr = "name="+jQuery("#est_type_new").val()+"&do=add";
	jQuery.ajax({
		type: "POST",
		url: "ajax_event_type.php",
		data: datastr,
		success: function(a,b,c){
			console.log(a);
			location.href = location.href;
		}
	});
}
function updateType(tid,val) {
	var datastr = "tid="+tid+"&val="+val+"&do=update";
	jQuery.ajax({
		type: "POST",
		url: "ajax_event_type.php",
		data: datastr,
		success: function(a,b,c){
			console.log(a);
		}
	});
}
</script>

</head>
<body>
<center>

<h2>ARES Special Event Management</h2>

<?php /* include "ARES_INCLUDE_Operator_Status.php?eventid=".$evid; */ ?>

<div id=evtypes style='position:fixed;top:10px;right:10px;padding:8px;background-color:white;border:solid 1px grey;border-radius:3px;text-align:center;'>
<table border=0 cellpadding=3 cellspacing=0>
<tr><th colspan=3>Event Types</th></tr>
<?php
foreach($etarry as $it => $idata) {
	echo "<tr><td><input type=text size=12 name=est_name value='".$idata['name']."' onblur='updateType(".$it.",this.value)'></td><td><button type=button onclick='deleteType(".$it.")' style='color:red'>-</button></td></tr>\n";
}
echo "<tr><td><input type=text size=12 id=est_type_new placeholder='Add Type'></td><td><button type=button onclick='addType()' style='color:green'>+</button></td></tr>";
?>
</table>
</div>

<?php
$xicon = (empty($evid)) ? "":"<img src='images/icon-delete.png' border=0 width=16 align=absmiddle alt='reset icon' title='Reset Events' style='cursor:pointer;margin-left:2px;' onclick=\"location.href='ARES_Special_Event_Manage.php'\">";
?>

<input type=text name=i_lookup id=i_lookup class='incident' placeholder="To look up a Special Event, Click here and Start Typing ..." style="width:500px;text-align:center;font-weight:bold;" value="<?=$thisinc?>" onfocus='this.select()'><?=$xicon?>
<div id=results>
<?=$searchresults?>
</div>
<?php
$etypes = "";
foreach($ires as $ir) {
	$etypes .= "<option value=".$ir['est_id'].">".$ir['est_name']."</option>";
}
?>
<div onclick="showAddNew(document.getElementById('addnew').style.visibility)" style="padding:4px;font-size:13px;margin-top:16px;width:500px;text-align:center;background-color:lightgrey;cursor:pointer;">or Click here to Show the New Special Event Form</div><br>
<div id=addnew style="visibility:hidden;display:none;">
<form method=post>
<input type=hidden name=addevent value=1>
<table border=0 cellpadding=6 cellspacing=0>
<tr><td>Name</td><td colspan=3><input type=text id=ev_name name=ev_name class="incident" style="width:430px;text-align:left;" placeholder="i.e. Baker to Vegas"></td></tr>
<tr><td>Type</td><td><select name=ev_type style="width:80px"><option value=0></option><?=$etypes?></select></td><td>Occurs</td><td><input type=text name=date_state id=date_start size=16 onclick="this.select()" class="datepicker" placeholder="Start">-<input type=text name=date_end id=date_end size=16 onclick="this.select()" class="datepicker" placeholder="End"></td></tr>
<tr><th colspan=4><input type=submit value="Save This New Special Event"></th></tr>
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
			location.href="ARES_Special_Event_Manage.php?admin=1&eventid="+ui.item.iid;
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
jQuery("#lochead").click(function(){
	jQuery("#eventlocations").slideToggle(600);
});
jQuery("#staffhead").click(function(){
	jQuery("#eventstaff").slideToggle(600);
});
jQuery(function(){
//	jQuery("#members").slideToggle(2000);
	jQuery(".datepicker").datetimepicker();
});
</script>
</body>
</html>


