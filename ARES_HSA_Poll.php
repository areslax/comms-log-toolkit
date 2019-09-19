<?php
/* #####################################
 * ARES_HSA_Poll.php
 * HSA Status/Bed Availability Poll form
 * ##################################### */
#if (empty($_GET['admin'])) { header("Location: http://www.km6wka.net/ares");exit; }

$stationcode = (empty($_GET['stationcode'])) ? "":$_GET['stationcode'];
$loccode = substr($_GET['fld'],0,4);
$locname = substr($_GET['fld'],5,strlen($_GET['fld']));
$stationid = (empty($_GET['stationid']) || !is_numeric($_GET['stationid'])) ? "":$_GET['stationid'];
$inid = (empty($_GET['inid']) || !is_numeric($_GET['inid'])) ? "":$_GET['inid'];
$msgfld = (empty($_GET['rfld'])) ? "":"msg".substr($_GET['rfld'],3,strlen($_GET['rfld']));
?>

<!doctype html>
<html>
<head>
<title>ARES HSA Poll</title>

<?php
include "common_includes.php";
?>

<script type="text/javascript">
var svclevels = new Array();
svclevels[1] = "Green";
svclevels[2] = "Yellow";
svclevels[3] = "Red";
svclevels[4] = "Black";
function saveData(frm) {
	var frmdata = jQuery(frm).serializeToJSON();
	frmdata = JSON.stringify(frmdata);
	jQuery.ajax({
		type: "POST",
		url: "ajax_save_log.php",
		data: "stationid=<?=$stationid?>&stacode=<?=$stationcode?>&loccode=<?=$loccode?>&incidentid=<?=$inid?>&rtyp=hsapoll&frmdata="+encodeURIComponent(frmdata),
		success: function(a,b,c) {
			console.log(a);
                        //build msgfld string
                        frmdata = frmdata.substr(2,(frmdata.length-4));
                        var frmtype = frmdata.substr(0,3);
                        var frmarry = frmdata.split('","');
                        var msgdata = frmtype.toUpperCase()+" Poll\n";
                        for(i=0;i<frmarry.length;i++) {
                                msgarry = frmarry[i].split('":"');
                                if (msgarry[1].length>0) {
					msgarry[1] = (msgarry[0].substr(3,msgarry[0].length)=='svclvl') ? svclevels[msgarry[1]]:msgarry[1];
                                        msgdata += msgarry[0].substr(3,msgarry[0].length)+": "+msgarry[1]+"\n";
                                }
                        }
			parent.<?=$msgfld?>.innerHTML = msgdata;
			frm.submit();
			parent.modal.style.display = "none";
			parent.modal.src = "";
		}
	});
}
</script>

<style type="text/css">
SPAN { padding: 6px 6px 1px 3px; }
.green { background-color: green; }
.yellow { background-color: yellow; }
.red { color:white;background-color: red; }
.black { color:white;background-color: black; }
</style>

<body onload="init()">

<form id=hsalog method=post action="https://www.km6wka.net/ares/api/reports/save.php" style="margin-bottom:0px">
<input type=hidden name=hsatmstmp id=hsatmstmp value="<?=date("Y-m-d H:i:s")?>">
<input type=hidden name=hsalocation id=hsalocation value="<?=$loccode?>">
<input type=hidden name=hsaincident id=hsaincident value="<?=$inid?>">

<div style="position:absolute;top:0px;right:0px;width:12px;height:12px;border-radius:12px;border:solid 1px grey;background-color:lightgrey;text-align:center;font-size:12px;cursor:pointer;" onclick="parent.showModal('','none');" title="Close HSA Poll Popup WITHOUT Saving Poll Data">X</div>

<center>

<table border=0 cellpadding=4 cellspacing=0>
<tr>
<th style="font-size:1.3em">ARES HSA Poll<br><span title="<?=$locname?>" style='font-weight:normal;font-size:0.8em;'><?=$loccode.": ".$locname?></span></th></tr>
<tr>
<td align=center>
<p style="margin:0px 0 6px 0;font-size:0.9em;">
Time This Data Was Collected: <input type=text size=14 name=hsadatacollected value='<?=date("Ymd H:i")?>'>
</p>
<p style="margin:10px 0 10px 0">
<b>HOSPITAL SERVICE LEVEL:</b>
</p>
<table border=0 cellpadding=3 cellspacing=6><tr>
<th class='green' title="Green: Full Service"><input type=radio name=hsasvclvl value=1 onmouseup="document.getElementById('hsamedsurg').focus();"><br>GRN</th>
<th class='yellow' title="Yellow: Limited Service"><input type=radio name=hsasvclvl value=2 onmouseup="document.getElementById('hsamedsurg').focus();"><br>YLO</th>
<th class='red' title="Red: Emergency Service Only"><input type=radio name=hsasvclvl value=3 onmouseup="document.getElementById('hsamedsurg').focus();"><br>RED</th>
<th class='black' title="Black: No Service, Shelter in Place"><input type=radio name=hsasvclvl value=4 onmouseup="document.getElementById('hsamedsurg').focus();"><br>BLK</th>
</tr></table>
<br>

<p style="margin:0 0 6px 0">
<b>AVAILABLE BED COUNT:</b>
</p>
<table border=0 cellpadding=1 cellspacing=0>
<tr><th>Area</th><th>Count</th></tr>
<tr><td title="MEDD-SRGG">Med/Surg</td><td><input type=text name=hsamedsurg id=hsamedsurg value="" size=5></td></tr>
<tr><td title="TELL-EE">TELE</td><td><input type=text name=hsatele id=hsatele value="" size=5></td></tr>
<tr><td title="EYE-CEE-YOU">ICU</td><td><input type=text name=hsaicu id=hsaicu value="" size=5></td></tr>
<tr><td title="PEE-EYE-CEE-YOU">PICU</td><td><input type=text name=hsapicu id=hsapicu value="" size=5></td></tr>
<tr><td title="ENN-EYE-CEE-YOU">NICU</td><td><input type=text name=hsanicu id=hsanicu value="" size=5></td></tr>
<tr><td title="PEEDZ">Peds</td><td><input type=text name=hsapeds id=hsapeds value="" size=5></td></tr>
<tr><td title="OH-BEE-GYNE">OB/Gyn</td><td><input type=text name=hsaobgyn id=hsaobgyn value="" size=5></td></tr>
<tr><td title="TRAH-MAH">Trauma</td><td><input type=text name=hsatrauma id=hsatrauma value="" size=5></td></tr>
<tr><td title="BERN">Burn</td><td><input type=text name=hsaburn id=hsaburn value="" size=5></td></tr>
<tr><td title="EYE-SO-LAY-SHUN">Isolation&nbsp;&nbsp;</td><td><input type=text name=hsaisolation id=hsaisolation value="" size=5></td></tr>
<tr><td title="SYKE">Psych</td><td><input type=text name=hsapsych id=hsapsych value="" size=5></td></tr>
<tr><td title="OH-ARR">OR</td><td><input type=text name=hsaor id=hsaor value="" size=5></td></tr>
</table>
<br>
<button type=button style="cursor:pointer" title="Save HSA Poll Data and Close This Popup" onclick="saveData(this.form)">Save This Report</button>

</td></tr>
</table>
</center>
</form>

<script type="text/javascript">
</script>

</body>
</html>
