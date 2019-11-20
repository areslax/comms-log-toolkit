<?php
/* #########################
 * ARES_SMS_Group_Manage.php
 * Manage SMS Groups
 * ######################### */
require "db_conn.php";
$thisloc = "";
$searchresults = "";
//got new group form submit
if (!empty($_POST['addsmsgroup'])) {
	$togo = array("(",")"," ","-","_",".","/");
	$mids = json_encode($_POST['m_ids']);
	$q = $conn->prepare("insert into SMS_Groups (smsg_name,m_ids) values (:name,:mids)");
	$q->execute(array(':name'=>$_POST['smsg_name'],':mids'=>$mids));
	$smsgid = $conn->lastInsertId();
	header("Location: ARES_SMS_Group_Manage.php?smsgid=".$smsgid);
	exit;
}

//got update group form submit
if (!empty($_POST['updatesmsgroup'])) {
	$togo = array("(",")"," ","-","_",".","/");
	$mids = json_encode($_POST['m_ids']);
	$q = $conn->prepare("update SMS_Groups set smsg_name=:name,m_ids=:mids where smsg_id=:smsid limit 1");
	$q->execute(array(':name'=>$_POST['smsg_name'],':mids'=>$mids,':smsid'=>$_POST['smsg_id']));
	header("Location: ARES_SMS_Group_Manage.php?smsgid=".$_POST['smsg_id']);
	exit;
}

//got message to group
$sentmsg = "";
if (!empty($_POST['sendmsg']) && !empty($_POST['smsgid'])) {
	//enter into SMS_Queue
	$sendts = (empty($_POST['smsq_send_ts'])) ? date("YmdHi"):date("YmdHi",strtotime($_POST['smsq_send_ts']));
	$sentts = (empty($_POST['smsq_send_ts'])) ? $sendts:NULL;
	$q = $conn->prepare("insert into SMS_Queue (smsg_id,smsq_send_ts,smsq_sent_ts,smsq_message) values (:smsgid,:sendts,:sentts,:msg)");
	$q->execute(array(':smsgid'=>$_POST['smsgid'],':sendts'=>$sendts,':sentts'=>$sentts,':msg'=>$_POST['smsq_message']));
	//send immediately
	//otherwise will send via cronjob
	if (!empty($sentts)) {
		$ms = $conn->prepare("select m_ids from SMS_Groups where smsg_id=:smsgid limit 1");
		$ms->execute(array(':smsgid'=>$_POST['smsgid']));
		$mids = $ms->fetch(PDO::FETCH_ASSOC);
		$mids = json_decode($mids['m_ids']);
		//get member cell+carrier and send message
		$msg = @strip_tags($_POST['smsq_message']);
		$msg = @stripslashes($msg);
		$hed = "From: ares@areslax.org";
		$delim = array("-","_"," ",".","(",")");
		foreach($mids as $mid) {
			$m = $conn->prepare("select mc_data,mc_carrier,carrier_ext from Member_Contacts left outer join Mobile_Carriers on Mobile_Carriers.carrier_id=Member_Contacts.mc_carrier where m_id=".$mid." and mc_carrier is not NULL and mc_type='4' limit 1");
			$m->execute();
			$to = $m->fetchAll(PDO::FETCH_ASSOC);
			$addr = str_replace($delim,"",$to[0]['mc_data']).$to[0]['carrier_ext'];
			mail($addr,"ARES SMS Message",$msg,$hed);
		}
		$sentmsg .= "<div style='font-weight:bold;color:green;'>SMS Message sent!</div><script>setTimeout(\"location.href='ARES_SMS_Group_Manage.php?smsgid=".$_POST['smsgid']."'\",2000);</script>";
	}
	else {
		$sentmsg .= "<div style='font-weight:bold;color:blue;'>SMS Message queued for later delivery</div><script>setTimeout(\"location.href='ARES_SMS_Group_Manage.php?smsgid=".$_POST['smsgid']."'\",2000);</script>";
	}
}

$smsg_name = "";
$searchresult = "";
//got smsgid so show group info
$smsgid = (!empty($_POST['smsgid']) && is_numeric($_POST['smsgid'])) ? $_POST['smsgid']:"";
if ((!empty($_GET['smsgid']) && $_GET['smsgid']!='undefined' && is_numeric($_GET['smsgid'])) || !empty($smsgid)) {
	$smsgid = (!empty($smsgid)) ? $smsgid:$_GET['smsgid'];
	$q = $conn->prepare("select * from SMS_Groups where smsg_id=:smsgid");
	$q->execute(array(':smsgid'=>$smsgid));
	$res = $q->fetchAll(PDO::FETCH_ASSOC);
	foreach($res as $r) {
		$smsg_name = $r['smsg_name'];
		//make group mids array
		$mids = json_decode($r['m_ids']);
		//get members
		$m_ids = "";
		$mq = $conn->query("select Member_Contacts.m_id,mc_data,mc_carrier,m_fname,m_lname,m_callsign from Member_Contacts left outer join Members on Members.m_id=Member_Contacts.m_id where mc_type='4' and mc_carrier!='' and mc_carrier is not NULL order by m_callsign");
		$mres = $mq->fetchAll(PDO::FETCH_ASSOC);
		foreach($mres as $mr) {
			$mname = $mr['m_fname']." ".$mr['m_lname'];
			$sel = (in_array($mr['m_id'],$mids)) ? " selected":"";
			$m_ids .= "<option value=".$mr['m_id'].$sel.">".strtoupper($mr['m_callsign']).": ".$mname."</option>";
		}
		$searchresult = "<form method=post>\n<input type=hidden name=updatesmsgroup value=1>\n<input type=hidden name=smsg_id value='".$smsgid."'>\n<table border=0 cellpadding=6 cellspacing=0 style='width:420px'>
		<tr><td>Group Name</td><td><input type=text name=smsg_name class='incident' onfocus='this.select();' style='width:280px;text-align:left;' placeholder='i.e. NW Operators, Admins, etc.' value='".$r['smsg_name']."'></td></tr>
		<tr valign=top><td>Group&nbsp;Members<br><span class=sm>Shift+Click or Ctrl+Click<br>to select multiple<br><br>Only shows Members with cell and carrier</span></td><td><select multiple name='m_ids[]' class='incident' style='width:280px;height:200px;text-align:left;border-color:lightgrey;'>".$m_ids."</select></td></tr>
		<tr><th colspan=2><input type=submit value='Update This Group'> &nbsp; <input type=button onclick='deleteMe(".$smsgid.")' value='Delete This Group'> &nbsp; <input type=button value='Cancel Group Changes' onclick=\"location.href='ARES_SMS_Group_Manage.php?smsgid=".$smsgid."'\"></th></tr>
		<tr><th colspan=2></form><hr><form method=post><input type=hidden name=sendmsg value=1><input type=hidden name=smsgid value='".$smsgid."'></th></tr>
		<tr><th colspan=2>".$sentmsg."SEND AN SMS MESSAGE TO: \"".$smsg_name."\"</th></tr>
		<tr valign=top><td>Text Message<br><span class='sm'>No HTML, just text</span></td><td><textarea name=smsq_message style='width:280px'></textarea></td></tr>
		<tr><td>Schedule&nbsp;Send</td><td><input type=text class='datepicker' name=smsq_send_ts style='width:200px' placeholder='Leave Empty for Send Now'></td></tr>
		<tr><th colspan=2><input type=submit value='Send This SMS Message To: \"".$smsg_name."\"'></th></tr>
		<tr><th colspan=2><hr></th></tr>\n</table>\n</form>";
	}
}
?>
<!doctype html>
<html lang="en">
<head><title>ARES SMS Group Manage</title>
<?php
include "common_includes.php";
?>

<script type="text/javascript">
function showAddNew(vis) {
	var vis = (vis=="hidden") ? "visible":"hidden";
	jQuery("#addnew").css("visibility",vis).fadeIn(500);
	jQuery("#smsg_name").focus();
}
function deleteMe(smsgid) {
	var datastr = "smsgid="+smsgid;
	jQuery.ajax({
		type: "POST",
		url: "ajax_delete_sms_group.php",
		data: datastr,
		success: function(a,b,c){
			console.log(a);
			location.href = "ARES_SMS_Group_Manage.php";
		}
	});
}
</script>
<style type="text/css">
.sm {
	font-size: 9px;
	color: grey;
}
</style>
</head>
<body>
<center>

<h2>ARES SMS Group Management</h2>

<?php
$xicon = (empty($iid)) ? "":"<img src='images/icon-delete.png' border=0 width=16 align=absmiddle alt='reset icon' title='Reset SMS Group' style='cursor:pointer;margin-left:2px;' onclick=\"location.href='ARES_SMS_Group_Manage.php'\">";
?>

<input type=text name=smsg_lookup id=smsg_lookup class='smsgroups' placeholder="To look up an SMS Group, Click here and Start Typing ..." style="width:500px;text-align:center;font-weight:bold;" value="<?=$smsg_name?>" onfocus='this.select()'><?=$xicon?>
<div id=results>
<?=$searchresult?>
</div>

<div onclick="showAddNew(document.getElementById('addnew').style.visibility)" style="padding:4px;font-size:13px;margin-top:16px;width:500px;text-align:center;background-color:lightgrey;cursor:pointer;">or Click here to Show the New SMS Group Form</div><br>
<div id=addnew style="visibility:hidden;display:none;">
<?php
//get members
$m_ids = "";
$mq = $conn->query("select Member_Contacts.m_id,mc_data,mc_carrier,m_fname,m_lname,m_callsign from Member_Contacts left outer join Members on Members.m_id=Member_Contacts.m_id where mc_type='4' and mc_carrier!='' and mc_carrier is not NULL order by m_callsign");
$mres = $mq->fetchAll(PDO::FETCH_ASSOC);
foreach($mres as $mr) {
	$mname = $mr['m_fname']." ".$mr['m_lname'];
	$m_ids .= "<option value=".$mr['m_id'].">".strtoupper($mr['m_callsign']).": ".$mname."</option>";
}
?>
<form method=post>
<input type=hidden name=addsmsgroup value=1>
<table border=0 cellpadding=6 cellspacing=0 style='width:420px'>
<tr><td>Group Name</td><td><input type=text name=smsg_name class='smsgroups' onfocus='this.select();' style='width:280px;text-align:left;' placeholder='i.e. NW Operators, Admins, etc.'></td></tr>
<tr valign=top><td>Add&nbsp;Members<br><span class=sm>Shift+Click or Ctrl+Click<br>to select multiple<br><br>Only shows Members with cell and carrier</span></td><td><select multiple name="m_ids[]" style='width:284px;height:200px;text-align:left;border-color:lightgrey;'><?=$m_ids?></select></td></tr>
<tr><th colspan=2><input type=submit value='Add This New SMS Group'></th></tr>
</table>
</form>
</div>

<script type="text/javascript">
var smsgroups = new Array(<?php
//get sms groups for lookup
$sq = $conn->query("select smsg_id,smsg_name from SMS_Groups order by smsg_name");
$sq->execute();
$sr = $sq->fetchAll(PDO::FETCH_ASSOC);
$sstr = "";
foreach($sr as $s) {
	$sstr .= "{'value':'".$s['smsg_name']."','smsgid':'".$s['smsg_id']."'},";
}
echo rtrim($sstr,",")."\n";
?>
);
//autocomplete
jQuery(function() {

	jQuery(".datepicker").datetimepicker();

	jQuery(".smsgroups").autocomplete({
		source: smsgroups,
		focus: function(event, ui) {
			event.preventDefault();
			jQuery(this).val(ui.item.label);
		},
		select: function(event, ui) {
			event.preventDefault();
			jQuery(this).val(ui.item.value);
			location.href="ARES_SMS_Group_Manage.php?admin=1&smsgid="+ui.item.smsgid;
		}
	});

});
</script>
</center>
</body>
</html>
