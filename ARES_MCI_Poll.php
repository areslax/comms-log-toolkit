<?php
/* #################
 * ARES_MCI_Poll.php
 * MCI Poll form
 * ################# */
#if (empty($_GET['admin'])) { header("Location: https://km6wka.net/ares");exit; }

require "db_conn.php";

$stationcode = (empty($_GET['stationcode'])) ? "":$_GET['stationcode'];
$sq = $conn->prepare("select l_id from Locations where l_tactical=:ltac limit 1");
$sq->execute(array(":ltac"=>$stationcode));
$r = $sq->fetchAll(PDO::FETCH_ASSOC);
$locid = $r['l_id'];
$loccode = substr($_GET['fld'],0,strpos($_GET['fld']," "));
$locname = substr($_GET['fld'],strpos($_GET['fld']," "),strlen($_GET['fld']));
$inid = (empty($_GET['inid']) || !is_numeric($_GET['inid'])) ? "":$_GET['inid'];
$stid = (empty($_GET['sid']) || !is_numeric($_GET['sid'])) ? "":$_GET['sid'];
$msgfld = (empty($_GET['rfld'])) ? "":"msg".substr($_GET['rfld'],3,strlen($_GET['rfld']));

//get patient types
$pq = $conn->prepare("select * from Patient_Types order by pt_id");
$pq->execute();
$ptypes = "<option value=0>&nbsp;</option>";
while($pr=$pq->fetch(PDO::FETCH_ASSOC)) {
	$ptypes .= "<option value=".$pr['pt_id'].">".$pr['pt_title']."</option>";
}
?>
<!doctype html>
<html lang="en">
<head>
<title>ARES MCI Poll</title>

<?php
include "common_includes.php";
?>

<script type="text/javascript">
var isme = "";
var iter = 1;
function setScribe(u) { isme = u; }

function addRow() {
	if (document.getElementById('patientinfo'+iter).value=='') {
		iter++;
		jQuery("#main_table tr:last").after("<tr id=row"+iter+" valign=top><th id=rid"+iter+">"+iter+"</th><td><select id=patienttype"+iter+" name=patienttype["+iter+"] onfocus='hiliteme("+iter+")'><?=$ptypes?></select></td><td style='padding-bottom:0px'><textarea id=patientinfo"+iter+" name=patientinfo["+iter+"] class='msg' rows=1 style='width:300px;padding:2px;' onfocus='hiliteme("+iter+")'></textarea></td><td align=center><input type=text id=transport"+iter+" name=transport["+iter+"] size=10 onfocus='hiliteme("+iter+")'></td><td align=center><input type=text id=timearrived"+iter+" name=timearrived["+iter+"] size=10 onfocus='hiliteme("+iter+")' onblur='disableblur(this);addRow();this.onBlur=\"\";document.getElementById(\"patienttype"+(iter+1)+"\").focus();'></td></tr>\n");
		init();
	}
}
function focusNext(itr) {
	jQuery("#ts"+itr).focus();
}
function disableblur(id) {
	jQuery(id).removeAttr('onblur');
}
function hiliteme(rw) {
	var rws = document.getElementsByTagName('tr');
	for(i=0;i<rws.length;i++) {
		rws[i].className='bg-wht';
	}
	document.getElementById("row"+rw).className = "bg-grn";
}
function saveData(frm) {
	var frmobj = jQuery(frm).serializeToJSON();
	frmstr = JSON.stringify(frmobj);
//saving to json text files mainly used by sticks
//on server for backup, in case no transfer to parent log
	jQuery.ajax({
		type: "POST",
		url: "ajax_save_log.php",
		data: "stationid=<?=$stationid?>&stacode=<?=$stationcode?>&locid=<?=$locid?>&loccode=<?=$loccode?>&incidentid=<?=$inid?>&rtyp=mcipoll&frmdata="+encodeURIComponent(frmstr),
		success: function(a,b,c) {
			console.log(a);
			//build msgfld string
			frmstr = frmstr.substr(2,(frmstr.length-4));
			var frmtype = frmstr.substr(0,3);
			var frmarry = frmstr.split('","');
			var msgdata = frmtype.toUpperCase()+" Poll\n";
			for(i=0;i<frmarry.length;i++) {
				msgarry = frmarry[i].split('":"');
				if (msgarry[1].length>0) {
					msgdata += msgarry[0].replace("mci","")+": "+msgarry[1]+"\n"
				}
			}
			//transfer to parent comms form
			parent.<?=$msgfld?>.innerHTML = msgdata;
			//send to api for mci poll table insert
			frm.submit();
			//hide modal
			parent.modal.style.display = "none";
			parent.modal.src = "";
		}
	});
}
jQuery(document).ready(function(){
	jQuery("#mciimmediate").focus();
});
</script>

<style type="text/css">
SPAN { padding: 6px 6px 1px 3px; }
.green { background-color: green; }
.yellow { background-color: yellow; }
.red { color:white;background-color: red; }
.black { color:white;background-color: black; }
</style>

<body onload="init()">
<form id=mcilog method=post action="https://km6wka.net/ares/api/reports/save.php">
<input type=hidden name=mcitmstmp id=mcitmstmp value="<?=date("Y-m-d H:i:s")?>">
<input type=hidden name=mcilocation id=mcilocation value="<?=$loccode?>">
<input type=hidden name=mciincident id=mciincident value="<?=$inid?>">

<div style="position:absolute;top:0px;right:0px;width:12px;height:12px;border-radius:12px;border:solid 1px grey;background-color:lightgrey;text-align:center;font-size:12px;cursor:pointer;" onclick="parent.showModal('','none');" title="Close MCI Poll Popup WITHOUT Saving Poll Data">X</div>

<center>

<table border=0 cellpadding=4 cellspacing=0>
<tr>
<th style="font-size:1.3em">ARES MCI Poll<br>
<?# if (!empty($loccode)) { ?>
<span title="<?=$locname?>" style="font-weight:normal;font-size:0.8em;"><?=$loccode.": ".$locname?></span></th></tr>
<?# } else { ?>
<!--select onchange='checkType(this[this.selectedIndex].value,\"loc"+iter+"\")'>"+loclist+"</select></span></th></tr-->
<?# } ?>
<tr>
<td align=center>
<p style="margin:0px 0 6px 0;font-size:0.9em;">
Time This Data Was Collected: <input type=text size=14 name=mcidatacollected value='<?=date("Ymd H:i")?>'>
</p>
<p style="margin:0 0 6px 0">
<b>TRIAGE COUNTS:</b>
</p>
<span class='red' style="padding:4px 4px 6px 4px" title="IMMEDIATE: Will die, if not transported ASAP"><input type=text id=mciimmediate name=mciimmediate size=10 value="" style="text-align:center" placeholder="Immediate"></span>
<span class='yellow' style="padding:4px 4px 6px 4px" title="DELAYED: Need transport, but not Immediate"><input type=text name=mcidelayed size=10 value="" style="text-align:center" placeholder="Delayed"></span>
<span class='green' style="padding:4px 4px 6px 4px" title="MINOR: Walking Wounded"><input type=text name=mciminor size=10 value="" style="text-align:center" placeholder="Minor"></span>
<br>

<p style="margin:20px 0 6px 0">
<b>PATIENT INCOMING:</b>
</p>
<table id=main_table border=1 cellpadding=4 cellspacing=0 style="border-color:white">
<tr><th>ID</th><th>Type</th><th>Patient Info</th><th>Transport Unit</th><th>Time Arrived</th></tr>
<tr id=row0 valign=top><th id=rid0>0</th><td><select id=patienttype0 name=patienttype[0] onfocus="hiliteme(0)"><?=$ptypes?></select></td><td style="padding-bottom:0px"><textarea id=patientinfo0 name=patientinfo[0] class='msg' rows=1 style='width:300px;padding:2px;' onfocus="hiliteme(0)"></textarea></td><td align=center><input type=text id=transport0 name=transport[0] size=10 onfocus="hiliteme(0)"></td><td align=center><input type=text id=timearrived0 name=timearrived[0] size=10 onfocus="hiliteme(0)" onblur="disableblur(this);addRow();this.onBlur='';document.getElementById('patienttype1').focus();"></td></tr>
<tr id=row1 valign=top><th id=rid1>1</th><td><select id=patienttype1 name=patienttype[1] onfocus="hiliteme(1)"><?=$ptypes?></select></td><td style="padding-bottom:0px"><textarea id=patientinfo1 name=patientinfo[1] class='msg' rows=1 style='width:300px;padding:2px;' onfocus="hiliteme(1)"></textarea></td><td align=center><input type=text id=transport1 name=transport[1] size=10 onfocus="hiliteme(1)"></td><td align=center><input type=text id=timearrived1 name=timearrived[1] size=10 onfocus="hiliteme(1)" onblur="disableblur(this);addRow();this.onBlur='';document.getElementById('patienttype2').focus();"></td></tr>
</table>
<br>
<button type=button style="cursor:pointer" title="Save MCI Poll Data and Close This Popup" onclick="saveData(this.form)">Save This Report</button>

</td></tr>



</table>

</center>

</form>

<script type="text/javascript">

</script>
</body>
</html>
