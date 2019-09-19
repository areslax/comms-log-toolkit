<?php
/* ################################
 * ARES_Location_Manage.php
 * Interface for managing locations
 * ################################ */
if (empty($_GET['admin'])) { header("Location: https://www.km6wka.net/ares");exit; }

require "db_conn.php";
$thisloc = "";
$searchresults = "";
//got new location form submit
if (!empty($_POST['addlocation'])) {
	$togo = array("(",")"," ","-","_",".","/");
	$pno = ltrim(str_replace($togo,'',$_POST['l_desk_phone']),'1');
	$deskphone = substr($pno,0,3)."-".substr($pno,3,3)."-".substr($pno,-4,strlen($pno));
	$q = $conn->prepare("insert into Locations (l_type,l_tactical,l_name,l_address,l_city,l_state,l_zip,l_gps,l_trauma_level,l_desk_phone) values (:type,:tactical,:name,:address,:city,:state,:zip,:gps,:trauma,:deskphone)");
	$q->execute(array(':type'=>$_POST['l_type'],':tactical'=>$_POST['l_tactical'],':name'=>$_POST['l_name'],':address'=>$_POST['l_address'],':city'=>$_POST['l_city'],':state'=>$_POST['l_state'],':zip'=>$_POST['l_zip'],':gps'=>$_POST['l_gps'],':trauma'=>$_POST['l_trauma_level'],':deskphone'=>$deskphone));
	$lid = $conn->lastInsertId();
	//update common js file
	file_get_contents("scripts/cron_generateNewLocations.php");
}

//got update location form submit
if (!empty($_POST['updatelocation'])) {
	$togo = array("(",")"," ","-","_",".","/");
	$pno = ltrim(str_replace($togo,'',$_POST['l_desk_phone']),'1');
	$deskphone = substr($pno,0,3)."-".substr($pno,3,3)."-".substr($pno,-4,strlen($pno));
	$q = $conn->prepare("update Locations set l_type=:type,l_tactical=:tactical,l_name=:name,l_address=:address,l_city=:city,l_state=:state,l_zip=:zip,l_gps=:gps,l_trauma_level=:trauma,l_desk_phone=:deskphone where l_id=:lid limit 1");
	$q->execute(array(':type'=>$_POST['l_type'],':tactical'=>$_POST['l_tactical'],':name'=>$_POST['l_name'],':address'=>$_POST['l_address'],':city'=>$_POST['l_city'],':state'=>$_POST['l_state'],':zip'=>$_POST['l_zip'],':gps'=>$_POST['l_gps'],':trauma'=>$_POST['l_trauma_level'],':deskphone'=>$deskphone,':lid'=>$_POST['l_id']));
	//update common js file
	file_get_contents("scripts/cron_generateNewLocations.php");
}

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

//got lid so show location info
if ((!empty($_GET['lid']) && $_GET['lid']!='undefined') || !empty($lid)) {
	$lid = (!empty($lid)) ? $lid:$_GET['lid'];
	$q = $conn->prepare("select * from Locations where l_id=:lid");
	$q->execute(array(':lid'=>$lid));
	$res = $q->fetchAll(PDO::FETCH_ASSOC);
	$a = "{\"result\":[";
	foreach($res as $r) {
		foreach($r as $key => $val) {
			$r[$key] = addslashes($val);
		}
		$thisloc = stripslashes($r['l_tactical'].": ".$r['l_name']);
		$thisnam = str_replace("'","&apos;",stripslashes($r['l_name']));
		//get location types
		$ltypes = "";
		$lq = $conn->query("select * from Location_Types order by lt_id");
		$lq->execute();
		$larr = $lq->fetchAll(PDO::FETCH_ASSOC);
		foreach($larr as $lr) {
			$sel = ($r['l_type']==$lr['lt_id']) ? " selected":"";
			$ltypes .= "<option value=".$lr['lt_id'].$sel.">".$lr['lt_title']."</option>";
		}
		$a = "<form method=post>\n<input type=hidden name=updatelocation value=1>\n<input type=hidden name=l_id value='".$lid."'>\n<table border=0 cellpadding=6 cellspacing=0 style='width:580px'>
<tr><td>Name</td><td colspan=3><input type=text name=l_name class='location' onfocus='this.select();' style='width:380px;text-align:left;' placeholder='i.e. Huntington Memorial Hospital - Pasadena' value='".$thisnam."'></td></tr>
<tr><td>Type</td><td><select name=l_type style='width:100px'><option value=0></option>".$ltypes."</select></td><td>Address</td><td><input type=text name=l_address size=19 value='".$r['l_address']."'></td></tr>
<tr><td>Tac Call</td><td><input type=text size=12 name=l_tactical value='".$r['l_tactical']."'></td><td>City</td><td><input type=text size=19 name=l_city value='".$r['l_city']."'></td></tr>
<tr><td>Trauma Level</td><td><input type=text size=12 name=l_trauma_level value='".$r['l_trauma_level']."'></td><td>State, Zip</td><td><select name=l_state style='width:50px'><option value=CA>CA</option></select>&nbsp;<input type=text size=10 name=l_zip value='".$r['l_zip']."'></td></tr>
<tr><td>Desk Phone <a href='tel:".$r['l_desk_phone']."' title='Click to launch your phone dialer'><img src='images/icon-phone.svg' alt='phone icon' border=0 width=14 align=absmiddle></a></td><td><input type=text size=12 name=l_desk_phone value='".$r['l_desk_phone']."'></td><td>GPS <a href='https://maps.google.com/?q=".$r['l_gps']."' target='_blank' title='Click to view location on Google Maps'><img src='images/icon-google-maps.svg' alt='maps icon' border=0 width=14 align=absmiddle></a></td><td><input type=text size=21 name=l_gps value='".$r['l_gps']."'></td></tr>";
		$a .= "<tr valign=top><td>Location Notes</td><td colspan=3><textarea name='l_note' style='width:403px;height:48px' placeholder='i.e. cross street, parking, hazards in the area'>".$r['l_note']."</textarea></td></tr>\n";
		$a .= "<tr><th colspan=4 style='border-bottom:solid 1px black'><input type=submit value='Update This Location Info'></th></tr>\n";
		$a .= "</table>\n</form>\n";
	}
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

	$searchresults = $a;
}
//get location types
$ltypes = "";
$lq = $conn->query("select * from Location_Types order by lt_id");
$lq->execute();
$larr = $lq->fetchAll(PDO::FETCH_ASSOC);
foreach($larr as $lr) {
	$ltypes .= "<option value=".$lr['lt_id'].">".$lr['lt_title']."</option>";
}
?>
<!doctype html>
<html>
<head><title>ARES Location Management</title>

<?php
include "common_includes.php";
?>

<script type="text/javascript">
function showLocation(lid) {
//	location.href = "ARES_Location_Manage.php?lid="+lid;
}
function showSearch(vis) {
	var vis1 = (vis=="hidden") ? "block":"none";
	var vis2 = (vis=="hidden") ? "visible":"hidden";
	jQuery("#searchform").css("display",vis1).fadeIn(500);
	jQuery("#l_lookup").focus();
	jQuery("#searchform").css("visibility",vis2);
}
function showAddNew(vis) {
	var vis = (vis=="hidden") ? "visible":"hidden";
	jQuery("#addnew").css("visibility",vis).fadeIn(500);
	jQuery("#l_name").focus();
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
		url: "ajax_location_type.php",
		data: "tid="+tid,
		success: function(a,b,c){
			console.log(a);
			location.href = location.href;
		}
	});
}
function addType() {
	var datastr = "title="+jQuery("#lt_title_new").val();
	jQuery.ajax({
		type: "POST",
		url: "ajax_location_type.php",
		data: datastr,
		success: function(a,b,c){
			console.log(a);
			location.href = location.href;
		}
	});
}
function updateType(tid,val) {
	var datastr = "tid="+tid+"&val="+val;
	jQuery.ajax({
		type: "POST",
		url: "ajax_location_type.php",
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

<h2>ARES Location Management</h2>

<div id=loctypes style='position:fixed;top:10px;right:10px;padding:8px;background-color:white;border:solid 1px grey;border-radius:3px;text-align:center;'>
<table border=0 cellpadding=3 cellspacing=0>
<tr><th colspan=3>Location Type</th></tr>
<?php
$lq = $conn->query("select * from Location_Types order by lt_id");
$lq->execute();
$lrow = $lq->fetchAll(PDO::FETCH_ASSOC);
foreach($lrow as $lt) {
	echo "<tr><td><input type=text size=14 name=lt_title value='".$lt['lt_title']."' onblur='updateType(".$lt['lt_id'].",this.value)'></td><td><button type=button onclick='deleteType(".$lt['lt_id'].")'>-</button></td></tr>\n";
}
echo "<tr><td><input type=text size=14 id=lt_title_new placeholder='Add New Type'></td><td><button type=button onclick='addType()'>+</button></td></tr>";
?>
</table>
</div>

<input type=text name=l_lookup id=l_lookup class='location' placeholder="To look up a Location, Click here and Start Typing ..." style="width:500px;text-align:center;font-weight:bold;" value="<?=$thisloc?>" onfocus='this.select()'>
<div id=results>
<?=$searchresults?>
</div>

<div onclick="showAddNew(document.getElementById('addnew').style.visibility)" style="padding:4px;font-size:13px;margin-top:16px;width:500px;text-align:center;background-color:lightgrey;cursor:pointer;">or Click here to Show the New Location Form</div><br>
<div id=addnew style="visibility:hidden;display:none;">
<form method=post>
<input type=hidden name=addlocation value=1>
<table border=0 cellpadding=6 cellspacing=0>
<tr><td>Name</td><td colspan=3><input type=text id=l_name name=l_name class="location" style="width:380px;text-align:left;" placeholder="i.e. Huntington Memorial Hospital - Pasadena"></td></tr>
<tr><td>Type</td><td><select name=l_type style="width:100px"><?=$ltypes?></select></td><td>Address</td><td><input type=text name=l_address id=l_address size=19></td></tr>
<tr><td>Tac Call</td><td><input type=text size=12 id=l_tactical name=l_tactical></td><td>City</td><td><input type=text size=19 id=l_city name=l_city></td></tr>
<tr><td>Trauma Level</td><td><input type=text size=12 id=l_trauma_level name=l_trauma_level></td><td>State, Zip</td><td><select id=l_state name=l_state style="width:50px"><option value=CA>CA</option></select>&nbsp;<input type=text size=10 id=l_zip name=l_zip></td></tr>
<tr><td>Desk Phone</td><td><input type=text size=12 id=l_desk_phone name=l_desk_phone></td><td>GPS</td><td><input type=text size=21 id=l_gps name=l_gps></td></tr>
<tr valign=top><td>Location Notes</td><td colspan=3><textarea name='l_note' style='width:394px;height:48px' placeholder='i.e. cross street, parking, hazards in the area'></textarea></td></tr>
<tr><th colspan=4><input type=submit value="Save This New Location"></th></tr>
</table>
</form>
</div>
</center>

<script type="text/javascript">
//autocomplete
var locfld = new Array();
var pplfld = new Array();
jQuery(function() {

        jQuery(".location").autocomplete({
                source: loclookup,
                focus: function(event, ui) {
                        event.preventDefault();
                        jQuery(this).val(ui.item.label);
                },
                select: function(event, ui) {
                        event.preventDefault();
                        jQuery(this).val(ui.item.label);
                        locfld[this.id]=ui.item.value;
			location.href="ARES_Location_Manage.php?lid="+ui.item.lid+"&admin=1";
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
                        pplfld[this.id]=ui.item.value;
			location.href="ARES_Member_Manage.php?mid="+ui.item.mid+"&admin=1";
                }
        });

});
</script>
</body>
</html>


