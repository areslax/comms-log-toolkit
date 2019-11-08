<?php
/* ##############################
 * ARES_Member_Manage.php
 * Interface for managing members
 * ############################## */
//if (empty($_GET['admin'])) { header("Location: https://km6wka.net/ares");exit; }

require "db_conn.php";
$thismem = "";
$searchresults = "";

#debug
#echo "<pre>";print_r($_POST);exit;

//got new member form submit
if (!empty($_POST['addmember'])) {
	$tdate = date("Ymd");
	$pword = md5($_POST['m_password']);
	$q = $conn->prepare("insert into Members (m_access_level,m_callsign,m_fname,m_lname,m_note,m_active,m_start_date,m_last_login,m_status,m_prestage_lid,m_password) values (:accesslevel,:callsign,:fname,:lname,:note,:active,:startdate,:lastlogin,:status,:prestage,:password)");
	try{
	 $q->execute(array(':accesslevel'=>$_POST['m_access_level'],':callsign'=>$_POST['m_callsign'],':fname'=>$_POST['m_fname'],':lname'=>$_POST['m_lname'],':note'=>$_POST['m_note'],':active'=>$_POST['m_active'],':startdate'=>$tdate,':lastlogin'=>NULL,':status'=>$_POST['m_status'],':prestage'=>$_POST['m_prestage'],':password'=>$pword));
	}catch(PDOException $e){
	 echo "oops: ".$e->getMessage();
	 exit;
	}
	if ($q->errorInfo()[0]>1) {
	 echo "err:<pre>";print_r($q->errorInfo());exit;
	}
	$mid = $conn->lastInsertId();
	if (!empty($_POST['mc_type_cell_phone']) || !empty($_POST['mc_type_home_phone'])) {
		$togo = array("(",")"," ","-","_",".","/");
		if (!empty($_POST['mc_type_cell_phone'])) {
			$pno = ltrim(str_replace($togo,'',$_POST['mc_type_cell_phone']),'1');
			$phoneno = substr($pno,0,3)."-".substr($pno,3,3)."-".substr($pno,-4,strlen($pno));
			$q = $conn->prepare("insert into Member_Contacts (m_id,mc_type,mc_data,mc_carrier) values (:mid,:type,:data,:carr)");
			$q->execute(array(':mid'=>$mid,':type'=>4,':data'=>$phoneno,':carr'=>$_POST['mc_carrier']));
		}
		if (!empty($_POST['mc_type_home_phone'])) {
                        $pno = ltrim(str_replace($togo,'',$_POST['mc_type_home_phone']),'1');
                        $phoneno = substr($pno,0,3)."-".substr($pno,3,3)."-".substr($pno,-4,strlen($pno));
                        $q = $conn->prepare("insert into Member_Contacts (m_id,mc_type,mc_data,mc_carrier) values (:mid,:type,:data,:carr)");
                        $q->execute(array(':mid'=>$mid,':type'=>2,':data'=>$phoneno,':carr'=>$_POST['mc_carrier']));
		}
	}
	if (!empty($_POST['mc_type_email'])) {
		$q = $conn->prepare("insert into Member_Contacts (m_id,mc_type,mc_data) values (:mid,:type,:data)");
		$q->execute(array(':mid'=>$mid,':type'=>1,':data'=>$_POST['mc_type_email']));
	}
	//update common js file
	system("php scripts/generateNewLocations.php");
}
$ctyps = array(0=>"",1=>"Email",2=>"Home Phone",3=>"Work Phone",4=>"Cell Phone",5=>"Account");

//got update member form submit
if (!empty($_POST['updatemember'])) {
	$pword = md5($_POST['m_password']);
	$m_qury = "";
	$m_qury .= "m_access_level=:accesslevel,";
	$m_qury .= "m_callsign=:callsign,";
	$m_qury .= "m_fname=:fname,";
	$m_qury .= "m_lname=:lname,";
	$m_qury .= "m_note=:note,";
	$m_qury .= "m_active=:active,";
	$m_qury .= "m_status=:status,";
	$m_qury .= "m_prestage_lid=:prestage";
	$m_qury .= (!empty($_POST['m_password'])) ? ",m_password=:pword":"";

	$qvars = array(':accesslevel'=>$_POST['m_access_level'],':callsign'=>strtolower($_POST['m_callsign']),':fname'=>$_POST['m_fname'],':lname'=>$_POST['m_lname'],':note'=>$_POST['m_note'],':active'=>$_POST['m_active'],':status'=>$_POST['m_status'],':prestage'=>$_POST['m_prestage_lid']);
	if (!empty($_POST['m_password'])) { $qvars[] = "':pword'=>".$pword; }

	$q = $conn->prepare("update Members set ".$m_qury." where m_id='".$_POST['mid']."'");
	$q->execute($qvars);

	if (!empty($_POST['mc_types'])) {
	//$t = mc_id
	foreach($_POST['mc_types'] as $t => $d) {
		if ($d!='1' && $d!=5) {
			$togo = array("(",")"," ","-","_",".","/");
			$pno = ltrim(str_replace($togo,'',$_POST['mc_datas'][$t]),'1');
			$data = substr($pno,0,3)."-".substr($pno,3,3)."-".substr($pno,-4,strlen($pno));
		}
		else {
			$data = $_POST['mc_datas'][$t];
		}
		$q2 = $conn->prepare("update Member_Contacts set mc_type=:type,mc_data=:data,mc_carrier=:carr where mc_id=:mcid");
		$q2->execute(array(':mcid'=>$t,':type'=>$d,':data'=>$data,':carr'=>$_POST['mc_carrier'][$t]));
	}
	}
	//got new contact entry
	if (!empty($_POST['new_mc_type'])) {
		$data = $_POST['new_mc_data'];
		if ($_POST['new_mc_type']>1 && $_POST['new_mc_type']<5) {
			$togo = array("(",")"," ","-","_",".","/");
			$pno = ltrim(str_replace($togo,'',$data),'1');
			$data = substr($pno,0,3)."-".substr($pno,3,3)."-".substr($pno,-4,strlen($pno));
		}
		$q3 = $conn->prepare("insert into Member_Contacts (m_id,mc_type,mc_data) values (:mid,:type,:data)");
		$q3->execute(array(':mid'=>$_POST['mid'],':type'=>$_POST['new_mc_type'],':data'=>$data));
	}
	//update common js file
	system("php scripts/generateNewLocations.php");
}
//got mid so show member info
if ((!empty($_GET['mid']) && $_GET['mid']!='undefined') || !empty($mid)) {
	$mid = (!empty($mid)) ? $mid:$_GET['mid'];
	$q = $conn->prepare("select Members.*,Locations.l_tactical,Locations.l_name from Members left outer join Locations on Locations.l_id=Members.m_prestage_lid where m_id=:mid");
	$q->execute(array(':mid'=>$mid));
	$res = $q->fetchAll(PDO::FETCH_ASSOC);
	foreach($res as $r) {
		foreach($r as $key => $val) {
			$r[$key] = addslashes($val);
		}
		$thismem = strtoupper($r['m_callsign']).": ".$r['m_fname']." ".$r['m_lname'];
		//get status codes
		$stypes = "";
		$sq = $conn->query("select * from Status_Codes order by s_id");
		$sq->execute();
		$sarr = $sq->fetchAll(PDO::FETCH_ASSOC);
		foreach($sarr as $sr) {
			$sel = ($r['m_status']==$sr['s_id']) ? " selected":"";
			$stypes .= "<option value=".$sr['s_id'].$sel.">".$sr['s_title']."</option>";
		}
		//get member types/access levels
		$mtypes = "";
		$mq = $conn->query("select * from Member_Types order by mt_id");
		$mq->execute();
		$marr = $mq->fetchAll(PDO::FETCH_ASSOC);
		foreach($marr as $mr) {
			$sel = ($r['m_access_level']==$mr['mt_id']) ? " selected":"";
			$mtypes .= "<option value=".$mr['mt_id'].$sel.">".$mr['mt_title']."</option>";
		}
		//deploys to string might be empty
		$depto = (empty($r['m_prestage_lid'])) ? "":$r['l_tactical'].": ".$r['l_name'];
		$a = "<form method=post autocomplete='off'>
<input type=hidden name=updatemember value=1>
<input type=hidden name=mid value='".$mid."'>
<table border=0 cellpadding=6 cellspacing=0>
<tr><td>Call Sign</td><td><input type=text size=8 name=m_callsign value='".$r['m_callsign']."'></td><td>Status</td><td><select name=m_status style='width:130px'><option value=0></option>".$stypes."</select></td></tr>
<tr><td>First Name</td><td><input type=text size=16 name=m_fname value='".$r['m_fname']."'></td><td>Last Name</td><td><input type=text size=16 name=m_lname value='".$r['m_lname']."'></td></tr>
<tr><td>Active</td><td><input type=checkbox name=m_active value=1";
		if ($r['m_active']) { $a .= " checked"; }
		$a .= "> <span style='font-size:10px'>".date("m/d/Y",strtotime($r['m_start_date']))."</span></td><td>Access</td><td><select name=m_access_level style='width:130px'><option value=0></option>".$mtypes."</select></td></tr>
<tr valign=top><td>Deploys To</td><td colspan=3><input type=hidden name=m_prestage_lid value='".$r['m_prestage_lid']."'><input name=m_prestage_lid_name class='location' style='width:400px;text-align:left;' value='".$depto."' onfocus='this.select()'></td></tr>
<tr valign=top><td>Notes</td><td colspan=3><textarea name=m_note style='width:400px;height:60px;'>".$r['m_note']."</textarea></td></tr>";

	}
	$q = $conn->prepare("select * from Member_Contacts where m_id=:mid");
	$q->execute(array(':mid'=>$mid));
	$res = $q->fetchAll(PDO::FETCH_ASSOC);
	$contacts = array();
	$contacts['id'] = array();
	$contacts['type'] = array();
	$contacts['carrier'] = array();
	$contacts['data'] = array();
	foreach($res as $r) {
		foreach($r as $key => $val) {
			$r[$key] = addslashes($val);
		}
		$contacts['id'][] = $r['mc_id'];
		$contacts['type'][] = $r['mc_type'];
		$contacts['carrier'][] = $r['mc_carrier'];
		$contacts['data'][] = $r['mc_data'];
	}
	$i=0;
	foreach($contacts['type'] as $k) {
		$icn = ($k>1 && $k!=5) ? " <a href='tel:".$contacts['data'][$i]."' title='Click to call using your system dialer'><img src='images/icon-phone.svg' border=0 width=14 align=absmiddle></a>":" <a href='mailto:".$contacts['data'][$i]."?subject=ARES Message' title='Click to send email'><img src='images/icon-email.svg' border=0 width=14 align=absmiddle></a>";
		$a .= "<tr id=mcrow".$i."><td><select name=mc_types[".$contacts['id'][$i]."] style='width:120px'>";
		foreach($ctyps as $t => $v) {
			$sel = ($k==$t) ? " selected":"";
			$a .= "<option value=".$t.$sel.">".$v."</option>";
		}
		$datafldsize = ($k==4) ? 24:44;
		if ($k==4) {
			//get cell carriers for sms
			$carriers = "";
			$cq = $conn->query("select * from Mobile_Carriers order by carrier_name");
			$cq->execute();
			$carr = $cq->fetchAll(PDO::FETCH_ASSOC);
			foreach($carr as $c) {
				$sel = ($contacts['carrier'][$i]==$c['carrier_id']) ? " selected":"";
				$carriers .= "<option value=".$c['carrier_id'].$sel.">".$c['carrier_name']."</option>";
			}
			if (!empty($contacts['carrier'][$i])) {
				$togo = array("(",")"," ","-","_",".","/");
				$pno = ltrim(str_replace($togo,"",$contacts['data'][$i]));
				$cq = $conn->prepare("select carrier_ext from Mobile_Carriers where carrier_id=:carr limit 1");
				$cq->execute(array(':carr'=>$contacts['carrier'][$i]));
				$carr = $cq->fetchAll(PDO::FETCH_ASSOC);
				foreach($carr as $c) {
					$thiscarr = $c['carrier_ext'];
				}
				$icn .= "<a href='mailto:".$pno.$thiscarr."' style='margin-left:4px;text-decoration:none;color:black;font-weight:bold;' title='Click to send text message'>T</a>";
			}
		}
		$carrierfld = ($k==4) ? "<select name=mc_carrier[".$contacts['id'][$i]."] style='width:160px;height:21px;'><option value=0>Carrier</option>".$carriers."</select>":"";
		$a .= "</select></td><td colspan=3><input type=text name=mc_datas[".$contacts['id'][$i]."] size=".$datafldsize." value='".$contacts['data'][$i]."'>".$carrierfld." <button type=button onclick=\"deleteMe(".$mid.",'".$contacts['data'][$i]."',".$i.")\" title='Delete This Entry'>-</button>".$icn."</td></tr>\n";
		$i++;
	}
	$a .= "<tr id=mcrow".$i."><td>Add <select name=new_mc_type style='width:80px'>";
	foreach($ctyps as $k => $v) {
		$a .= "<option value=".$k.">".$v."</option>";
	}
	$a .= "</select></td><td colspan=3><input type=text name=new_mc_data size=44 autocomplete='ac_123' placeholder='Choose Contact Type then enter data, here'></td></tr>\n";

	$a .= "<tr><td colspan=2 align=right><b>New Password</b></td><td colspan=2><input type=password size=12 name=m_password autocomplete='new-password'></td></tr>";

	$a .= "<tr><th colspan=4><input type=submit value='Update This Operator Info'> <button type=button onclick='deleteOp(".$mid.")'>Delete Operator</button></th></tr>
</table>
</form>
</div>";


	$searchresults = $a;
}
//get cell carriers for sms
$carriers = "";
$cq = $conn->query("select * from Mobile_Carriers order by carrier_name");
$cq->execute();
$carr = $cq->fetchAll(PDO::FETCH_ASSOC);
foreach($carr as $c) {
	$carriers .= "<option value=".$c['carrier_id'].">".$c['carrier_name']."</option>";
}
?>
<!doctype html>
<html lang="en">
<head><title>ARES Operator Management</title>

<?php
include "common_includes.php";
?>

<script type="text/javascript">
function showMember(mid) {
//	location.href = "ARES_Member_Manage.php?mid="+mid;
}
function showSearch(vis) {
	var vis1 = (vis=="hidden") ? "block":"none";
	var vis2 = (vis=="hidden") ? "visible":"hidden";
	jQuery("#searchform").css("display",vis1).fadeIn(500);
	jQuery("#m_lookup").focus();
	jQuery("#searchform").css("visibility",vis2);
}
function showAddNew(vis) {
	var vis = (vis=="hidden") ? "visible":"hidden";
	jQuery("#addnew").css("visibility",vis).fadeIn(900);
	jQuery("#callsign").focus();
}
function deleteOp(mid) {
	var datastr = "mid="+mid;
	jQuery.ajax({
		type: "POST",
		url: "ajax_delete_member.php",
		data: datastr,
		success: function(a,b,c){
			console.log(a);
			jQuery.get("scripts/cron_generateNewLocations.php");
			location.href = 'ARES_Member_Manage.php';
		}
	});
}
function deleteMe(mid,data,rid) {
	var datastr = "mid="+mid+"&data="+encodeURIComponent(data);
	jQuery.ajax({
		type: "POST",
		url: "ajax_delete_member_contact.php",
		data: datastr,
		success: function(a,b,c){
			console.log(a);
			jQuery("#mcrow"+rid).css("display","none").fadeOut(100);
			jQuery("#mcrow"+rid).css("visibility","hidden");
		}
	});

}
function deleteType(tid) {
	jQuery.ajax({
		type: "POST",
		url: "ajax_member_type.php",
		data: "tid="+tid,
		success: function(a,b,c){
			console.log(a);
			location.href = location.href;
		}
	});
}
function addType() {
	var datastr = "title="+jQuery("#mt_title_new").val();
	jQuery.ajax({
		type: "POST",
		url: "ajax_member_type.php",
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
		url: "ajax_member_type.php",
		data: datastr,
		success: function(a,b,c){
			console.log(a);
		}
	});
}
function deleteStatus(tid) {
	jQuery.ajax({
		type: "POST",
		url: "ajax_status_type.php",
		data: "tid="+tid,
		success: function(a,b,c){
			console.log(a);
			location.href = location.href;
		}
	});
}
function addStatus() {
	var datastr = "title="+jQuery("#stat_title_new").val();
	jQuery.ajax({
		type: "POST",
		url: "ajax_status_type.php",
		data: datastr,
		success: function(a,b,c){
			console.log(a);
			location.href = location.href;
		}
	});
}
function updateStatus(tid,val) {
	var datastr = "tid="+tid+"&val="+val;
	jQuery.ajax({
		type: "POST",
		url: "ajax_status_type.php",
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

<h2>ARES Operator Management</h2>

<div id=memcodes style='position:fixed;top:10px;right:10px;padding:8px;background-color:white;border:solid 1px grey;border-radius:3px;text-align:center;'>
<table border=0 cellpadding=3 cellspacing=0>
<tr><th colspan=3>Status Codes</th></tr>
<?php
$sq = $conn->query("select * from Status_Codes order by s_id");
$sq->execute();
$srow = $sq->fetchAll(PDO::FETCH_ASSOC);
foreach($srow as $st) {
        echo "<tr><td><input type=text size=14 name=s_title value='".$st['s_title']."' onblur='updateStatus(".$st['s_id'].",this.value)'></td><td><button type=button onclick='deleteStatus(".$st['s_id'].")'>-</button></td></tr>\n";
}
echo "<tr><td><input type=text size=14 id=stat_title_new placeholder='Add New Code'></td><td><button type=button onclick='addStatus()'>+</button></td></tr>";
?>
</table>
<hr>
<table border=0 cellpadding=3 cellspacing=0>
<tr><th colspan=3>Access Levels</th></tr>
<?php
$mq = $conn->query("select * from Member_Types order by mt_id");
$mq->execute();
$mrow = $mq->fetchAll(PDO::FETCH_ASSOC);
foreach($mrow as $mt) {
	echo "<tr><td><input type=text size=14 name=mt_title value='".$mt['mt_title']."' onblur='updateType(".$mt['mt_id'].",this.value)'></td><td><button type=button onclick='deleteType(".$mt['mt_id'].")'>-</button></td></tr>\n";
}
echo "<tr><td><input type=text size=14 id=mt_title_new placeholder='Add New Level'></td><td><button type=button onclick='addType()'>+</button></td></tr>";
?>
</table>
</div>

<input type=text name=m_lookup id=m_lookup class='people' placeholder="To look up an Operator, Click here and Start Typing ..." style="font-weight:bold;width:500px;text-align:center;" onfocus="this.select()" value="<?=$thismem?>">
<div id=results>
<?=$searchresults?>
</div>

<div onclick="showAddNew(document.getElementById('addnew').style.visibility)" style="padding:4px;font-size:13px;margin-top:16px;width:500px;text-align:center;background-color:lightgrey;cursor:pointer;">or Click here to Show the New Operator Form</div><br>
<div id=addnew style="visibility:hidden;display:none;">
<form method=post autocomplete='off'>
<input type=hidden name=addmember value=1>
<table border=0 cellpadding=6 cellspacing=0>
<tr><td>Call Sign</td><td><input type=text size=8 id=m_callsign name=m_callsign class="people"></td><td>Status</td><td><select name=m_status style="width:100px"><option value=1>Idle</option><option value=2>Checked In</option><option value=3>En Route</option><option value=4>On Location</option></select></td></tr>
<tr><td>Password</td><td><input type=password size=12 id=m_password name=m_password autocomplete="new-password"></td><td>Home Phone</td><td><input type=text size=12 id=home_phone name=mc_type_home_phone></td></tr>
<tr><td>First Name</td><td><input type=text size=12 id=m_fname name=m_fname></td><td>Cell Phone</td><td><input type=text size=12 id=cell_phone name=mc_type_cell_phone><select name=mc_carrier style="width:80px;height:21px;"><option value=0>Carrier</option><?=$carriers?></select></td></tr>
<tr><td>Last Name</td><td><input type=text size=12 id=m_lname name=m_lname></td><td>Email</td><td><input type=text size=22 id=email name=mc_type_email autocomplete="ac234"></td></tr>
<tr><td>Active</td><td><input type=checkbox name=m_active value=1 checked> <span style="font-size:10px"><?=date("m/d/Y")?></span></td><td>Access</td><td><select name=m_access_level style="width:100px"><option value=1>Operator</option><option value=2>Net Control</option><option value=3>Administrator</option></select></td></tr>
<tr valign=top><td>Deploys To</td><td colspan=3><input type=hidden name=m_prestage_lid><input name=m_prestage_lid_name class="location" style="width:400px;text-align:left;"></td></tr>
<tr valign=top><td>Notes</td><td colspan=3><textarea name=m_note style="width:400px;height:60px;"></textarea></td></tr>
<tr><th colspan=4><input type=submit value="Save This New Operator"></th></tr>
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
			location.href="ARES_Member_Manage.php?admin=1&mid="+ui.item.mid;
                }
        });

});
</script>
</body>
</html>


