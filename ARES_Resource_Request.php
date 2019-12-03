<?php
/* ###############################################
 * ARES_Resource_Request.php
 * Resource Request form
 * Requests logged and managed by RESOURCE Command
 * Using ARES_Resource_Request_Log.php
 * ############################################### */
ini_set('display_errors','1');

$rfld = (empty($_GET['rfld'])) ? "":$_GET['rfld'];

//got form, so submit it
if (!empty($_POST['sendme'])) {
	$msgfld = (empty($_POST['rfld'])) ? "":"msg".substr($_POST['rfld'],3);
	//check if nodb (already posted to db by op)
	//only applies when coming from comms log
	if (!empty($msgfld) && (!isset($_POST['nodb']) || empty($_POST['nodb']))) {
	require "db_conn.php";
	//clean up missing elements
	//create resource request
	$q = $conn->prepare("insert into Resource_Requests (incident_name,req_date,req_name,req_agency,req_position,req_phone,req_email,req_tracking_num,req_mission_desc,req_staging_note,req_supply_notes,req_delivery_notes,req_staging_notes,req_additional_notes,req_confirm_1,req_confirm_2,req_confirm_3,req_auth,req_sig) values (:iname,:rdate,:rname,:ragency,:rpos,:rphone,:remail,:rtracking,:rmission,:rstaging,:rsupplyn,:rdeliveryn,:rstagingn,:raddtln,:rconf1,:rconf2,:rconf3,:rauth,:rsig)");
	$q->execute(array(":iname"=>$_POST['incident_name'],":rdate"=>$_POST['req_date'],":rname"=>$_POST['req_name'],":ragency"=>$_POST['req_agency'],":rpos"=>$_POST['req_position'],":rphone"=>$_POST['req_phone'],":remail"=>$_POST['req_email'],":rtracking"=>$_POST['req_tracking_num'],":rmission"=>trim(json_encode($_POST['req_mission_desc']),'"'),":rstaging"=>trim(json_encode($_POST['req_staging_note']),'"'),":rsupplyn"=>trim(json_encode($_POST['req_supply_notes']),'"'),":rdeliveryn"=>trim(json_encode($_POST['req_delivery_notes']),'"'),":rstagingn"=>trim(json_encode($_POST['req_staging_notes']),'"'),":raddtln"=>trim(json_encode($_POST['req_additional_notes']),'"'),":rconf1"=>$_POST['req_confirm_1'],":rconf2"=>$_POST['req_confirm_2'],":rconf3"=>$_POST['req_confirm_3'],":rauth"=>$_POST['req_auth'],":rsig"=>$_POST['req_sig']));
	$rid = $conn->lastInsertId();
	//insert items
	foreach($_POST['req_item_num'] as $k => $iid) {
		if (empty($_POST['req_item_priority_'.$iid]) || empty($_POST['req_item_qty_'.$iid])) { continue; }
		//what's what
		$priority = $_POST['req_item_priority_'.$iid];
		$type = $_POST['req_item_type_'.$iid];
		switch ($type) {
			case "supplies":
			$desc = trim(json_encode($_POST['supply_item_desc_'.$iid]),'"');
			$q1 = $conn->prepare("insert into Resource_Request_Items (req_id,item_num,item_priority,item_type,item_desc,pkg_class,units_per_pkg_class,qty_requested,est_duration) values (:rid,:iid,:priority,:type,:desc,:pclass,:upclass,:qtyreq,:estdu)");
			$q1->execute(array(":rid"=>$rid,":iid"=>$iid,":priority"=>$priority,":type"=>$type,":desc"=>$desc,":pclass"=>$_POST['product_class_'.$iid],":upclass"=>$_POST['items_per_product_class_'.$iid],":qtyreq"=>$_POST['req_item_qty_'.$iid],":estdu"=>$_POST['req_item_estdu_'.$iid]));
//			$itmid = $conn->lastInsertId();
			break;
			case "personnel":
			$desc = trim(json_encode($_POST['personnel_item_desc_'.$iid]),'"');
			$paid = (isset($_POST['paid_'.$iid])) ? $_POST['paid_'.$iid]:0;
			$prefs = (isset($_POST['personnel_pref_skills_'.$iid])) ? $_POST['personnel_pref_skills_'.$iid]:0;
			$q2 = $conn->prepare("insert into Resource_Request_Items (req_id,item_num,item_priority,item_type,item_desc,min_experience,req_skills,pref_skills,mobilize_date,paid,qty_requested,est_duration) values (:rid,:iid,:priority,:type,:desc,:minexp,:reqskill,:prefskill,:mdate,:paid,:qtyreq,:estdu)");
			$q2->execute(array(":rid"=>$rid,":iid"=>$iid,":priority"=>$priority,":type"=>$type,":desc"=>$desc,":minexp"=>$_POST['min_experience_'.$iid],":reqskill"=>$_POST['personnel_req_skills_'.$iid],":prefskill"=>$prefs,":mdate"=>$_POST['mobilize_date_'.$iid],":paid"=>$paid,":qtyreq"=>$_POST['req_item_qty_'.$iid],":estdu"=>$_POST['req_item_estdu_'.$iid]));
//			$itmid = $conn->lastInsertId();
			break;
			case "other":
			$desc = trim(json_encode($_POST['other_item_desc_'.$iid]),'"');
			$q3 = $conn->prepare("insert into Resource_Request_Items (req_id,item_num,item_priority,item_type,item_desc,pkg_class,qty_requested,est_duration) values (:rid,:iid,:priority,:type,:desc,:pclass,:qtyreq,:estdu)");
			$q3->execute(array(":rid"=>$rid,":iid"=>$iid,":priority"=>$priority,":type"=>$type,":desc"=>$desc,":pclass"=>$_POST['product_class_'.$iid],":qtyreq"=>$_POST['req_item_qty_'.$iid],":estdu"=>$_POST['req_item_estdu_'.$iid]));
//			$itmid = $conn->lastInsertId();
			break;
			default:
			$desc = trim(json_encode($_POST['supply_item_desc_'.$iid]),'"');
			$q4 = $conn->prepare("insert into Resource_Request_Items (req_id,item_num,item_priority,item_type,item_desc,pkg_class,units_per_pkg_class,qty_requested,est_duration) values (:rid,:iid,:priority,:type,:desc,:pclass,:upclass,:qtyreq,:estdu)");
			$q4->execute(array(":rid"=>$rid,":iid"=>$iid,":priority"=>$priority,":type"=>$type,":desc"=>$desc,":pclass"=>$_POST['product_class_'.$iid],":upclass"=>$_POST['items_per_product_class_'.$iid],":qtyreq"=>$_POST['req_item_qty_'.$iid],":estdu"=>$_POST['req_item_estdu_'.$iid]));
//			$itmid = $conn->lastInsertId();
		}
		//fulfillment init
		$fq = $conn->prepare("insert into Resource_Request_Fulfill (req_id,item_num) values (:rid,:iid)");
		$fq->execute(array(":rid"=>$rid,":iid"=>$iid));
	}
	}//end nodb
	if (!empty($msgfld)) {
	//populate original comms log message field
	$arr = "";
	$emptyi = NULL;
	$priorities = array(1=>"EMERGENT",2=>"URGENT",3=>"SUSTAINMENT");
	$minexper = array(1=>"current hospital",2=>"current clinical",3=>"current license",4=>"clinical education");
	echo "<script type='text/javascript'>\n";
	echo "var frmdata = \"";
	foreach($_POST as $k => $v) {
		$thisi = NULL;
		if (strstr($k,'req_item_priority') && empty($v)) {
			$emptyi = substr($k,strrpos($k,"_"));
		}
		$thisi = substr($k,strrpos($k,"_"));
		if (is_array($v)) {
			//item nums built into fields, so no need for this array, here
			continue;
/*
			foreach($v as $k2 => $v2) {
				$thisi = substr($k2,strrpos($k2,"_"));
				if (empty($v2) || (isset($emptyi) && $thisi==$emptyi)) { continue; }
				if (stristr($k2,'item_priority')) { $v2 = $priorities[$v2]; }
				if (stristr($k2,'min_experience')) { $v2 = $minexper[$v2]; }
				$arr .= "'".$k2."':'".$v2."',";
			}
/**/
		}
		else {
			$thisi = substr($k,strrpos($k,"_"));
			if (empty($v) || (isset($emptyi) && $thisi==$emptyi)) { continue; }
			if (strstr($k,'item_priority')) { $v = $priorities[$v]; }
			if (strstr($k,'min_experience')) { $v = $minexper[$v]; }
			if ($k=='req_item_priority_1' || $k=='req_supply_notes') { $arr .= "'===== ':'=====',"; }
			else if (strstr($k,'req_item_priority')) { $arr .= "'----- ':'-----',"; }
			$arr .= "'".$k."':'".trim(json_encode($v),'"')."',";
		}
	}
	$arr = str_replace("_"," ",str_replace("req_","",rtrim($arr,",")));
	echo $arr."\";\n";
	echo "frmdata = frmdata.substr(1,(frmdata.length-2));\n";
	echo "var frmarry = frmdata.split(\"','\");\n";
	echo "var msgdata = \"Resource Request\\n\";\n";
	echo "for(i=0;i<frmarry.length;i++) {\n";
	echo "	msgarry = frmarry[i].split(\"':'\");\n";
	echo "	if (typeof msgarry[1] !== 'undefined' && msgarry[1].length>0 && msgarry[0]!='sendme' && msgarry[0]!='rfld') {\n";
	echo "		msgdata += msgarry[0]+\": \"+msgarry[1]+\"\\n\";\n";
	echo "	}\n";
	echo "}\n";
	echo "opener.".$msgfld.".innerHTML = msgdata;\n";
	echo "opener.focus();\n";
	echo "self.close();\n";
	echo "</script>\n";
	exit;
	}//end !empty msgfld
}
?>

<!doctype html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Resource Request: Medical and Health FIELD/HCF</title>

<?php
include "common_includes.php";
?>

<script type="text/javascript">
function checkAuth(frm) {
	var got1 = (document.getElementById('req_confirm_1').checked) ? 1:0;
	var got2 = (document.getElementById('req_confirm_2').checked) ? 1:0;
	var got3 = (document.getElementById('req_confirm_3').checked) ? 1:0;
	var gotauth = (document.getElementById('req_auth').value!='') ? 1:0;
	var gotsig = (document.getElementById('req_sig').value!='') ? 1:0;
	document.getElementsByClassName('bigbut')[1].style.backgroundColor = (got1 && got2 && got3 && gotauth && gotsig) ? "yellow":"lightgrey";
	document.getElementsByClassName('bigbut')[1].style.cursor = (got1 && got2 && got3 && gotauth && gotsig) ? "pointer":"default";
	document.getElementsByClassName('bigbut')[1].disabled = (got1 && got2 && got3 && gotauth && gotsig) ? 0:1;
	document.getElementsByClassName('bigbut')[0].style.backgroundColor = (got1 && got2 && got3 && gotauth && gotsig) ? "yellow":"lightgrey";
	document.getElementsByClassName('bigbut')[0].style.cursor = (got1 && got2 && got3 && gotauth && gotsig) ? "pointer":"default";
	document.getElementsByClassName('bigbut')[0].disabled = (got1 && got2 && got3 && gotauth && gotsig) ? 0:1;
}
var iter = 3;
function addReqRow() {
//	if (document.getElementById('supply_item_desc_'+iter).value=='') {
		iter++;
		var defaultcellcontent = "<table border=0 cellpadding=2 cellspacing=0 style='border-color:white;width:100%;'>\n<tr><th>Detailed Specific Item Description<br><div class='sm'><b>Vital characteristics, brand, specs, diagrams, and other info</b><br>(Type of Equipment, name, capabilities, output, capacity, Type of Supplies, name, size, capacity, etc.)</div></th><th class='smb' style='width:12%'>Product Class<br><span class='sm'>(Ea, Box, Cs., Pack)</span></th><th class='smb' style='width:12%'>Items per Product Class</th></tr>\n<tr><td><textarea name='supply_item_desc_"+iter+"' id='supply_item_desc"+iter+"' class='msg' style='width:100%;height:20px;'></textarea></td><th><input type=text class='cntr' name='product_class_"+iter+"'></th><th><input type=text class='cntr' name='items_per_product_class_"+iter+"'></th></tr>\n</table>";
		var newrow = "<tr id='row_"+iter+"' valign=top>\n";
		newrow += "<td style='width:4%;text-align:center;'><input type=hidden name='req_item_num[]' value="+iter+">"+iter+"</td>\n";
		newrow += "<td style='width:5%'>\n";
		newrow += "<select name='req_item_priority_"+iter+"' style='width:60px'>\n";
		newrow += "<option value=0></option>\n";
		newrow += "<option value=3>Sustainment</option>\n";
		newrow += "<option value=2>Urgent</option>\n";
		newrow += "<option value=1>Emergent</option>\n";
		newrow += "</select>\n";
		newrow += "</td>\n";
		newrow += "<td style='width:5%'>\n";
		newrow += "<select name='req_item_type_"+iter+"' onchange='customRow("+iter+",this.value)' style='width:60px'>\n";
		newrow += "<option value='supplies'>Supplies/Equipment</option>\n";
		newrow += "<option value='personnel'>Personnel</option>\n";
		newrow += "<option value='other'>Other</option>\n";
		newrow += "</select>\n";
		newrow += "</td>\n";
		newrow += "<td id='maincell_"+iter+"' style='width:89%;padding:0;'>\n";
		newrow += defaultcellcontent+"</td>\n";
		newrow += "<th style='width:5%'>\n";
		newrow += "<input type=text name='req_item_qty_"+iter+"' style='width:40px;text-align:center;' onblur='addReqRow()'></th>\n";
		newrow += "<th style='width:3%'>\n";
		newrow += "<input type=text name='req_item_estdu_"+iter+"' style='width:90px;text-align:center;' maxlength=40></th>\n";
		newrow += "</tr>\n";
		jQuery("#order_table > tbody > tr:last").after(newrow);
		init();
//	}
}
function customRow(rid,typ) {
	switch(typ) {
		case 'supplies':
		//supply/equipment (DEFAULT)
		var cellcontent = "<table border=0 cellpadding=2 cellspacing=0 style='border-color:white;width:100%;'>\n<tr><th>Detailed Specific Item Description<br><div class='sm'><b>Vital characteristics, brand, specs, diagrams, and other info</b><br>(Type of Equipment, name, capabilities, output, capacity, Type of Supplies, name, size, capacity, etc.)</div></th><th class='smb' style='width:12%'>Product Class<br><span class='sm'>(Ea, Box, Cs., Pack)</span></th><th class='smb' style='width:12%'>Items per Product Class</th></tr>\n<tr><td><textarea name='supply_item_desc_"+rid+"' id='supply_item_desc"+rid+"' class='msg' style='width:100%;height:20px;'></textarea></td><th><input type=text class='cntr' name='product_class_"+rid+"'></th><th><input type=text class='cntr' name='items_per_product_class_"+rid+"'></th></tr>\n</table>";
		break;
		case 'personnel':
		//personnel
		var cellcontent = "<table border=0 cellpadding=2 cellspacing=0 style='border-color:white;width:100%;'>\n<tr><th>Personnel Type &amp; Probable Duties<br><div class='sm'> Indicate required license types (see list below)<br>RN, MD, EMT-I, Pharmacist, LVN, EMT-P, NP, DVM, PA, RCP, MFT, DDS, LCSW, etc.</div></th><th class='smb' style='width:10%'><u>Minimum</u> Required<br>Clinical Experience<br><div class='sm'>1=current hospital<br>2=current clinical<br>3=current license<br>4=clinical education</div></th><th class='smb' style='width:10%'><u>Required</u> Skills, Training, Certs<br><div class='sm'>(e.g., PALS, Current ICU experience, Languages, ICS training, Addt'l Lic. i.e., PHN, etc.)</div></th><th class='smb' style='width:10%'><u>Preferred</u> Skills, Training, Certs</th><th class='smb' style='width:10%'>Date/Time Required<br><div class='sm'>Indicate anticipated mobilization or duty date.</div></th><th class='smb' style='width:6%'>Paid</th></tr>\n<tr><td><textarea name='personnel_item_desc_"+rid+"' id='personnel_item_desc"+rid+"' class='msg' style='width:100%;height:20px;'></textarea></td><th><input type=text class='cntr' name='min_experience_"+rid+"'></th><td><textarea name='personnel_req_skills_"+rid+"' id='personnel_req_skills"+rid+"' class='msg' style='width:100%;height:20px;'></textarea></td><td><textarea name='personnel_pref_skills_"+rid+"' id='personnel_pref_skills"+rid+"' class='msg' style='width:100%;height:20px;'></textarea></td><th><input type=text class='datepicker"+rid+" cntr' style='font-size:10px;width:80px;height:16px;' name='mobilize_date_"+rid+"'></th><th><input type=checkbox name='paid_"+rid+"' value=1></th></tr>\n</table>";
		break;
		case 'other':
		//other
		var cellcontent = "<table border=0 cellpadding=2 cellspacing=0 style='border-color:white;width:100%;'>\n<tr><th>Detailed Specific Item Description<br><div class='sm'>(Facility: Type, Tent, Trailer Size etc.)<br>(Mobile Resources: Alternate Care Supply Cache, Mobile Field Hospital, Ambulance Strike Team)</div></th><th class='smb'>Product Class<br><div class='sm'>(Ea, Cache, Team)</div></th></tr>\n<tr><td><textarea name='other_item_desc_"+rid+"' id='other_item_desc"+rid+"' class='msg' style='width:100%;height:20px;'></textarea></td><th><input type=text class='cntr' name='product_class_"+rid+"'></th></tr>\n</table>";
		break;
		default:
		var cellcontent = "<table border=0 cellpadding=2 cellspacing=0 style='border-color:white;width:100%;'>\n<tr><th>Detailed Specific Item Description<br><div class='sm'><b>Vital characteristics, brand, specs, diagrams, and other info</b><br>(Type of Equipment, name, capabilities, output, capacity, Type of Supplies, name, size, capacity, etc.)</div></th><th class='smb' style='width:12%'>Product Class<br><div class='sm'>(Ea, Box, Cs., Pack)</div></th><th class='smb' style='width:12%'>Items per Product Class</th></tr>\n<tr><td><textarea name='supply_item_desc_"+rid+"' id='supply_item_desc"+rid+"' class='msg' style='width:100%;height:20px;'></textarea></td><th><input type=text class='cntr' name='product_class_"+rid+"'></th><th><input type=text class='cntr' name='items_per_product_class_"+rid+"'></th></tr>\n</table>";
	}
	jQuery("#maincell_"+rid).html(cellcontent);
	if (typ=='personnel') {
		jQuery(".datepicker"+rid).datetimepicker();
	}
	init();
}
function checkNodb(chk) {
	jQuery(".nodb").prop("checked",chk);
}
</script>
<style type="text/css">
* { font-family: Arial,Helvetica,sans-serif; }
@media print {
	.noprint { display: none; }
}
INPUT { font-weight: normal; }
TABLE { border-collapse: collapse; }
TABLE, TH, TD { border: solid 1px grey; }
TABLE.noborder { border: none; }
TABLE.noborder TH { border: none; }
TABLE.noborder TD { border: none; }

.bigbut {
	font-size: 18px;
	font-weight: bold;
	border: ridged 4px orange;
	border-radius: 4px;
	background-color: lightgrey;
	padding: 10px 8px;
}
.grey { color: grey; }
.lightblue { background-color: #ddddff; }
.lightgreen { background-color: #ddffdd; }
.lightred { background-color: #ffdddd; }
.lightyelo { background-color: #ffffcc; }

.cntr {
	text-align: center;
	width: 60px;
}
.sm {
	font-size: 9px;
	font-weight: normal;
	line-height: 11px;
}
.smb {
	font-size: 9px;
	font-weight: bold;
	line-height: 11px;
}
.med {
	font-size:13px;
}
</style>

</head>
<body>
<?php
$nodb = (!empty($rfld)) ? "":" <span class='smb'><input type=checkbox name=nodb class='nodb' value=1 onclick='checkNodb(this.checked)'>NO DB</span> ";
$nodb1 = (!empty($rfld)) ? "":" <span class='smb'><input type=checkbox name=nodb1 class='nodb' value=1 onclick='checkNodb(this.checked)'>NO DB</span> ";
?>
<form method=post onsubmit="print()">
<input type=hidden name=sendme value=1>
<input type=hidden name=rfld value="<?=$rfld?>">

<!-- PAGINATE DURING PRINTING
<p style="text-align:right">Page 1 of <input name="pgcnt" type="number" min="1" max="99" size=2 maxlength=3></p-->

<table cellpadding=4 cellspacing=0 style="width:100%;">
  <tr class="lightyelo">
    <td colspan=2 style="position:relative;height:36px;text-align:center;"><div class="noprint" style="float:left;left:10px;padding-top:10px;"><button type=submit class="bigbut" disabled>SEND REQUEST</button><?=$nodb1?></div><h2>Resource Request: Medical and Health FIELD/HCF<sup style="font-size:.5em">1</sup> to Op Area</h2></td>
    <th style="width:126px;height:36px;"><h5>RR MH (11/26/2019)</h5></th>
  </tr>

  <tr>
    <th style="width:66px;height:36px;font-size:11px;line-height:16px;" class="lightred" rowspan=5>
    <p>R<br>E<br>Q<br>U<br>E<br>S<br>T<br>O<br>R</p>
    <p>T<br>O</p>
    <p>C<br>O<br>M<br>P<br>L<br>E<br>T<br>E</p></th>

    <td style="width:76%;font-weight:bold;" valign=top>
    1. Incident Name:<div style="padding-left:2%;"><input tabindex=1 type="text" name="incident_name" style="width:97%" maxlength=220></div></td>
    <th style="font-size:11px;width:12%;border-bottom:none;" class="lightgreen" valign=top>
    2a. Request DATE/TIME:<br>
	<input tabindex=2 type=text class="datepicker0" name="req_date" style="text-align:center;width:50%" maxlength=16></th>
    <!--th style="font-size:11px;width:12%;border-left:none;border-bottom:none;" class="lightgreen" valign=top>
    2b. Request TIME:<br>
	<input tabindex=3 type=time class="timefld" name="req_time" style="text-align:center;width:90%;" maxlength=12></th-->
  </tr>

  <tr>
    <td style="width:76%;font-weight:bold;" valign=top>
    3. Requestor Name, Agency, Position, Phone / Email:<br>
    <table class="noborder" style="width:100%;font-weight:normal;">
	<tr><td>Requestor&nbsp;Name:</td><td style="width:84%"><input tabindex=5 type=text name="req_name" style="width:97%"></td></tr>
	<tr><td>Agency:</td><td><input tabindex=6 type=text name="req_agency" style="width:97%" maxlength=120></td></tr>
	<tr><td>Position:</td><td><input tabindex=7 type=text name="req_position" style="width:97%" maxlength=120></td></tr>
	<tr><td>Phone:</td><td><input tabindex=8 type=text name="req_phone" style="width:30%" maxlength=40></td></tr>
	<tr><td>Email:</td><td><input tabindex=9 type=text name="req_email" style="width:30%" maxlength=40></td></tr>
    </table></td>
    <th style="font-size:11px;width:24%;padding-top:10px;border-top:none;" class="lightgreen" valign=top>
    2b. Request TRACKING NUMBER:<br><span class="noprint" style="font-weight:normal;font-style:italic;">(Assigned by Requesting Entity)</span><br>
	<input tabindex=4 type=text name="req_tracking_num" style="width:50%;text-align:center;"></b></font></td>
  </tr>

  <tr>
    <td style="font-weight:bold;" colspan=3 valign=top>
    4a. Describe Mission/Tasks:<div style="padding-left:2%;"><textarea tabindex=10 name="req_mission_desc" id="req_mission_desc" class="msg" style="width:97%"></textarea></td>
  </tr>

  <tr>
    <td style="font-weight:bold;" colspan=3 valign=top>
    4b. Delivery/Reporting/Staging:<div style="padding-left:2%;"><textarea tabindex=11 name="req_staging_note" id="req_staging_note" class="msg" style="width:97%"></textarea></td>
  </tr>

  <tr>
    <td style="font-weight:bold;padding-left:0px;;" colspan=3 class="noprint">
	<table class="noborder" cellpadding=4 style="font-size:12px;">
	<tr>
	<td style="font-size:1.5em;padding-right:20px;" class="grey">5. ORDER SHEETS (OBSOLETE, choose Item Type below): <i>ATTACH ADDITIONAL</i></td>
	<th><input type=checkbox name="req_order_supplies" value=1 disabled></th><td class="grey"><b>SUPPLIES</b></td>
	<th><input type=checkbox name="req_order_equipment" value=1 disabled></th><td class="grey"><b>EQUIPMENT</b></td>
	<th><input type=checkbox name="req_order_personnel" value=1 disabled></th><td class="grey"><b>PERSONNEL</b></td>
	<th><input type=checkbox name="req_order_other" value=1 disabled></th><td class="grey"><b>OTHER</b></td></tr>
	</table>
    </td>
  </tr>

  <tr class="lightblue">
    <td style="position:relative;font-weight:bold;font-size:18px;" colspan=3>
    6. ORDER <div style="position:relative;display:inline;left:14%;font-size:1.5em;text-align:center;font-style:italic;"><span class="noprint">SUPPLY / EQUIPMENT / PERSONNEL </span>REQUEST DETAILS</div></td>
  </tr>

  <tr>
  <td colspan=3 class="lightblue">
    <table id="order_table" cellpadding=4 cellspacing=0 width=100% style="background-color:white">
    <tr>
    <th style="width:4%;line-height:18px;">
	<p>I<br>T<br>E<br>M<br><br>#</th>
    <th style="width:5%;">
	Priority<sup style="font-size:.7em">2</sup><br><span style="font-weight:normal;font-style:italic;font-size:.7em;">(See Below)</span></th>
    <th style="width:5%;">
	Item<br>Type</th>
    <td style="width:89%;text-align:center;font-weight:bold;">
    <h2 style="font-size:1.6em;margin:0px;color:red;"><span class="noprint">DETAILED SPECIFIC </span>ITEM DESCRIPTIONS:</h2>
    <h3 class="noprint" style="margin:8px 0 0 0;color:red;">SUPPLIES / EQUIPMENT</h3>
    <div class="noprint" style="width:90%;margin:0 0 12px 10%;text-align:left;font-weight:normal">
    <b>Rx:</b> Drug Name, Dosage Form, UNIT OF USE PACK or Volume, Prod Info Sheet, In-House PO, photos, etc.<br>
    <b>Medical Supplies:</b> Item name, Size, Brand, etc.<br>
    <b>General Supplies/Equipment:</b> Food, Water, Generators, etc.
    </div>
    <h3 class="noprint" style="margin:0px;color:red;">PERSONNEL</h3>
    <div class="noprint" style="width:90%;margin:0 0 12px 10%;text-align:left;font-weight:normal">
    <b>Type &amp; Probable Duties:</b>
    Req'd License, MD, RN, PharmD, ICU/OR Experience, Hospital/Clinical Experience, etc.
    </div>
    <h3 class="noprint" style="margin:0px;color:red;">OTHER</h3>
    <div class="noprint" style="width:90%;margin:0 0 12px 10%;text-align:left;font-weight:normal">
    Mobile Field Hospital; Ambulance Strike Team; Alternate Care Supply Cache; Facility: Tent, Trailer, etc. +Size, etc.
    </div></td>
    <th style="width:5%">
    Quantity Requested
    <p style="margin:10px 0 0 0;font-size:.6em;color:grey;">
    New ITEM ROW is added after completing field
    </p>
    </th>
    <th style="width:3%">
    Expected<br>
    Equipment/<br>
    Staff Duration<br>
    of Use:
    <p style="margin:6px 0 0 0;font-size:.6em;;color:grey;text-align:center;">
    i.e. 14 hours,<br>2 days, etc.
    </p>
    </th>
  </tr>

  <tr id="row_1" valign=top>
    <td style="width:4%;text-align:center;"><input type=hidden name="req_item_num[]" value=1>1</td>
    <td style="width:5%">
      <select name="req_item_priority_1" style="width:60px">
      <option value=0></option>
      <option value=3>Sustainment</option>
      <option value=2>Urgent</option>
      <option value=1>Emergent</option>
      </select>
    </td>
    <td style="width:5%">
      <select name="req_item_type_1" onchange="customRow(1,this.value)" style="width:60px">
      <option value="supplies">Supplies/Equipment</option>
      <option value="personnel">Personnel</option>
      <option value="other">Other</option>
      </select>
    </td>
    <td id="maincell_1" style="width:89%;padding:0;">
    <table border=0 cellpadding=2 cellspacing=0 style="border-color:white;width:100%;">
<tr><th>Detailed Specific Item Description<br><div class="sm"><b>Vital characteristics, brand, specs, diagrams, and other info</b><br>(Type of Equipment, name, capabilities, output, capacity, Type of Supplies, name, size, capacity, etc.)</div></th><th class="smb" style="width:12%">Product Class<br><span class="sm">(Ea, Box, Cs., Pack)</span></th><th class="smb" style="width:12%">Items per Product Class</th></tr>
<tr><td><textarea name="supply_item_desc_1" id="supply_item_desc1" class="msg" style="width:100%;height:20px;"></textarea></td><th><input type=text class="cntr" name="product_class_1"></th><th><input type=text class="cntr" name="items_per_product_class_1"></th></tr>
</table></td>
    <th style="width:5%">
    <input type=text name="req_item_qty_1" style="width:40px;text-align:center;" onblur="addReqRow()"></td>
    <td style="width:3%">
    <input type=text name="req_item_estdu_1" style="width:90px;text-align:center;" maxlength=40></td>
  </tr>

  <tr id="row_2" valign=top>
    <td style="width:4%;text-align:center;"><input type=hidden name="req_item_num[]" value=2>2</td>
    <td style="width:5%">
      <select name="req_item_priority_2" style="width:60px">
      <option value=0></option>
      <option value=3>Sustainment</option>
      <option value=2>Urgent</option>
      <option value=1>Emergent</option>
      </select>
    </td>
    <td style="width:5%">
      <select name="req_item_type_2" onchange="customRow(2,this.value)" style="width:60px">
      <option value="supplies">Supplies/Equipment</option>
      <option value="personnel">Personnel</option>
      <option value="other">Other</option>
      </select>
    </td>
    <td id="maincell_2" style="width:89%;padding:0;">
    <table border=0 cellpadding=2 cellspacing=0 style="border-color:white;width:100%;">
<tr><th>Detailed Specific Item Description<br><div class="sm"><b>Vital characteristics, brand, specs, diagrams, and other info</b><br>(Type of Equipment, name, capabilities, output, capacity, Type of Supplies, name, size, capacity, etc.)</div></th><th class="smb" style="width:12%">Product Class<br><span class="sm">(Ea, Box, Cs., Pack)</span></th><th class="smb" style="width:12%">Items per Product Class</th></tr>
<tr><td><textarea name="supply_item_desc_2" id="supply_item_desc2" class="msg" style="width:100%;height:20px;"></textarea></td><th><input type=text class="cntr" name="product_class_2"></th><th><input type=text class="cntr" name="items_per_product_class_2"></th></tr>
</table></td>
    <th style="width:5%">
    <input type=text name="req_item_qty_2" style="width:40px;text-align:center;" onblur="addReqRow()"></td>
    <td style="width:3%">
    <input type=text name="req_item_estdu_2" style="width:90px;text-align:center;" maxlength=40></td>
  </tr>
  
  <tr id="row_3" valign=top>
    <td style="width:4%;text-align:center;"><input type=hidden name="req_item_num[]" value=3>3</td>
    <td style="width:5%">
      <select name="req_item_priority_3" style="width:60px">
      <option value=0></option>
      <option value=3>Sustainment</option>
      <option value=2>Urgent</option>
      <option value=1>Emergent</option>
      </select>
    </td>
    <td style="width:5%">
      <select name="req_item_type_3" onchange="customRow(3,this.value)" style="width:60px">
      <option value="supplies">Supplies/Equipment</option>
      <option value="personnel">Personnel</option>
      <option value="other">Other</option>
      </select>
    </td>
    <td id="maincell_3" style="width:89%;padding:0;">
    <table border=0 cellpadding=2 cellspacing=0 style="border-color:white;width:100%;">
<tr><th>Detailed Specific Item Description<br><div class="sm"><b>Vital characteristics, brand, specs, diagrams, and other info</b><br>(Type of Equipment, name, capabilities, output, capacity, Type of Supplies, name, size, capacity, etc.)</div></th><th class="smb" style="width:12%">Product Class<br><span class="sm">(Ea, Box, Cs., Pack)</span></th><th class="smb" style="width:12%">Items per Product Class</th></tr>
<tr><td><textarea name="supply_item_desc_3" id="supply_item_desc3" class="msg" style="width:100%;height:20px;"></textarea></td><th><input type=text class="cntr" name="product_class_3"></th><th><input type=text class="cntr" name="items_per_product_class_3"></th></tr>
</table></td>
    <th style="width:5%">
    <input type=text name="req_item_qty_3" style="width:40px;text-align:center;" onblur="addReqRow()"></td>
    <td style="width:3%">
    <input type=text name="req_item_estdu_3" style="width:90px;text-align:center;" maxlength=40></td>
  </tr>
  </table>
  </td>
  </tr>

  <tr>
  <td colspan=3 style="padding:0">
    <table border=1 cellpadding=4 cellspacing=0 width="100%" style="border-color:white" id="request_notes">
    <tr><td valign=top width="50%" class="med">Suggested Source(s) of Supply; Suitable Substitute(s); Special Delivery Comment(s):<br><textarea name="req_supply_notes" id="req_supply_notes" class="msg" style="width:100%;height:80px;"></textarea></td><td valign=top width="50%" class="med">Deliver to/Report to POC (Name/Title/Location/Tel#/Email/Radio#)<br><textarea name="req_delivery_notes" id="req_delivery_notes" class="msg" style="width:100%;height:80px;"></textarea></td></tr>
    <tr><td valign=top width="50%" class="med">Staging &amp; Deployment Details<br>(Parking/staging location? Food/water provided? Housing Provided? Items personnel should bring? Etc.):<br><textarea name="req_staging_notes" id="req_staging_notes" class="msg" style="width:100%;height:80px;"></textarea></td><td valign=top width="50%" class="med">Additional Instructions<br><br><textarea name="req_additional_notes" id="req_additional_notes" class="msg" style="width:100%;height:80px;"></textarea></td></tr>
    </table>
  </td>
  </tr> 

  <tr class="lightred">
    <th style="width:4%" rowspan=5>
    <p>R<br>E<br>V<br>I<br>E<br>W</th>
    <td style="font-weight:bold;" class="noprint" colspan=2>
    7. Requesting facility <u>must</u> confirm that these 3 requirements have been met prior to submission of request:
    </td>
  </tr>
  <tr class="lightred noprint">
    <td style="font-size:.8em;font-weight:bold;" colspan=2>
    *<input type=checkbox id=req_confirm_1 name="req_confirm_1" value=1 onclick="checkAuth(this.form)">
    Is the resource(s) being requested exhausted or nearly exhausted?</td>
  </tr>
  <tr class="lightred noprint">
    <td style="font-size:.8em;font-weight:bold;" colspan=2>
    *<input type=checkbox id=req_confirm_2 name="req_confirm_2" value=1 onclick="checkAuth(this.form)">
    Facility is unable to obtain resources within a reasonable time frame (based upon priority level below) from vendors, contractors, MOU/MOA's or corporate office?</td>
  </tr>
  <tr class="lightred noprint">
    <td style="font-size:.8em;font-weight:bold;"  colspan=2>
    *<input type=checkbox id=req_confirm_3 name="req_confirm_3" value=1 onclick="checkAuth(this.form)">
    Facility is unable to obtain resource from other non-traditional sources?</td>
  </tr>
  <tr class="lightred">
    <td colspan=2 style="position:relative;font-weight:bold">
    <div style="position:absolute;right:10px;padding-top:10px;" class="noprint"><?=$nodb?><button type=submit class="bigbut" disabled>SEND REQUEST</button></div>
    <p style="margin:0 0 12px 0">8. <span class="noprint">COMMAND/MANAGEMENT REVIEW AND </span>VERIFICATION<span class="noprint"><br>
    <span style="font-size:.9em">(NAME, POSITION, AND SIGNATURE - SIGNATURE INDICATES VERIFICATION OF NEED AND APPROVAL</span>)</span></p></span>
    &nbsp;&nbsp; *<input type=text id=req_auth name="req_auth" style="width:40%;" maxlength=110 placeholder="Name &amp; Position" onblur="checkAuth(this.form)"> *<input type=text id=req_sig name="req_sig" style="width:15%" placeholder="Signature" onblur="checkAuth(this.form)"></td>
  </tr>
</table>
<div style="text-align:center;font-size:.8em;margin:15px 0 120px 0;padding:3px;" class="lightyelo">
<sup>1</sup> HCF = Health Care Facility&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
<sup>2</sup> Priority: &nbsp;  <b>E</b>mergent = &lt;12 hours <span class="grey">||</span> <b>U</b>rgent = &gt;12 hours <span class="grey">||</span> <b>S</b>ustainment
</div>

</form>

<script type="text/javascript">
jQuery(function(){
	init();
        jQuery(".datepicker0").datetimepicker();
});
</script>
</body>
</html>
