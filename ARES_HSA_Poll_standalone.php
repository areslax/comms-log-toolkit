<?php
/* #####################################
 * ARES_HSA_Poll_standalone.php
 * HSA Status/Bed Availability Poll form
 * For Operator use from Location
 * ##################################### */
ini_set('display_errors','1');
include "db_conn.php";

$stationcode = (empty($_GET['stationcode'])) ? "":$_GET['stationcode'];
$loccode = (empty($_GET['fld'])) ? "":substr($_GET['fld'],0,strpos($_GET['fld']," "));
$locname = (empty($_GET['fld'])) ? "":substr($_GET['fld'],strpos($_GET['fld']," "),strlen($_GET['fld']));
$stationid = (empty($_GET['stationid']) || !is_numeric($_GET['stationid'])) ? "":$_GET['stationid'];
$inid = (empty($_GET['inid']) || !is_numeric($_GET['inid'])) ? 0:$_GET['inid'];
$msgfld = (empty($_GET['rfld'])) ? "":"msg".substr($_GET['rfld'],3,strlen($_GET['rfld']));
$longloc = (empty($loccode)) ? "Type to look up Location":$loccode.": ".$locname;
$loccolor = (empty($loccode)) ? "color:lightgrey":"";

//get total bed counts
$q = $conn->prepare("select * from Location_Bed_Counts where l_id=(select l_id from Locations where l_tactical=:ltact limit 1) limit 1");
$q->execute(array(":ltact"=>$loccode));
$bedcnts = $q->fetch(PDO::FETCH_ASSOC);
?>

<!doctype html>
<html lang="en">
<head>
<title>ARES HSA Poll</title>

<?php
include "common_includes.php";
?>

<script type="text/javascript">
var svclevels = new Array();
svclevels[1] = "Green";
svclevels[2] = "Yellow";
svclevels[3] = "Orange";
svclevels[4] = "Red";
svclevels[5] = "Black";
svclevels[6] = "Grey";
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
                        var frmarry = frmdata.split('","');
                        var msgdata = "HSA Poll\n";
                        for(i=0;i<frmarry.length;i++) {
                                msgarry = frmarry[i].split('":"');
                                if (msgarry[1].length>0) {
					msgarry[1] = (msgarry[0]=='servicelevel') ? svclevels[msgarry[1]]:msgarry[1];
                                        msgdata += msgarry[0]+": "+msgarry[1]+"\n";
                                }
                        }
			//parent.<?=$msgfld?>.innerHTML = msgdata;
			frm.submit();
			//parent.modal.style.display = "none";
			//parent.modal.src = "";
		}
	});
}
var iter = 2;
function addRow(frm) {
	jQuery("#main_table tr:last").after("<tr><td><div id=extra"+iter+" onblur=\"jQuery('#hsaextra"+iter+"').attr('name',this.innerHTML)\" contenteditable=true style='height:19px;width:120px;border:solid 1px #acacac;'></div></td><td><input type=text name='hsaextra"+iter+"' id=hsaextra"+iter+" value='' size=5 onblur='addRow()'></td><td>&nbsp;</td></tr>\n");
	iter++;
}
</script>

<style type="text/css">
SPAN { padding: 6px 6px 1px 3px; }
.green { background-color: green; }
.yellow { background-color: yellow; }
.orange { background-color: orange; }
.red { color: white;background-color: red; }
.black { color: white;background-color: black; }
.grey { color: white;background-color: grey; }
</style>

<body onload="init()">

<!--form id=hsalog method=post action="https://km6wka.net/ares/api/reports/save.php" style="margin-bottom:0px"-->
<form id=hsalog method=post style="margin-bottom:0px">
<input type=hidden name=tmstmp id=hsatmstmp value="<?=date("Y-m-d H:i:s")?>">
<input type=hidden name=location id=hsalocation value="<?=$loccode?>">
<input type=hidden name=locname id=hsalocname value="<?=$locname?>">
<input type=hidden name=incident_id id=hsaincident value="<?=$inid?>">

<div style="position:absolute;top:0px;right:0px;width:12px;height:12px;border-radius:12px;border:solid 1px grey;background-color:lightgrey;text-align:center;font-size:12px;cursor:pointer;" onclick="parent.showModal('','none');" title="Close HSA Poll Popup WITHOUT Saving Poll Data">X</div>

<center>

<table border=0 cellpadding=4 cellspacing=0>
<tr>
<th style="font-size:1.3em">ARES HSA Poll<br><div contenteditable='true' class='location' style='border:solid 1px lightgrey;padding:2px;width:500px;height:20px;text-align_center;font-weight:normal;font-size:0.8em;<?=$loccolor?>' onfocus='this.style.color="black";this.innerHTML=""'><?=$longloc?></div></th></tr>
<tr>
<td align=center>
<p style="margin:0px 0 6px 0;font-size:0.9em;">
Time This Data Was Collected: <input type=text size=14 name=datacollected value='<?=date("Ymd H:i")?>'>
</p>
<p style="margin:10px 0 10px 0">
<b>HOSPITAL SERVICE LEVEL:</b>
</p>
<table border=0 cellpadding=3 cellspacing=6><tr>
<th class='green' title="Green: Full Service"><input type=radio name=servicelevel value=1 onmouseup="document.getElementById('hsamedsurg').focus();"><br>GRN</th>
<th class='yellow' title="Yellow: Limited Service"><input type=radio name=servicelevel value=2 onmouseup="document.getElementById('hsamedsurg').focus();"><br>YLO</th>
<th class='orange' title="Orange: Modified Service Level"><input type=radio name=servicelevel value=3 onmouseup="document.getElementById('hsamedsurg').focus();"><br>ORG</th>
<th class='red' title="Red: Emergency Service Only"><input type=radio name=servicelevel value=4 onmouseup="document.getElementById('hsamedsurg').focus();"><br>RED</th>
<th class='black' title="Black: No Service, Shelter in Place"><input type=radio name=servicelevel value=5 onmouseup="document.getElementById('hsamedsurg').focus();"><br>BLK</th>
<th class='grey' title="Grey: Unknown Service Level"><input type=radio name=servicelevel value=6 onmouseup="document.getElementById('hsamedsurg').focus();"><br>GRY</th>
</tr></table>
<br>

<table border=0 cellpadding=1 cellspacing=0>
<tr><td title="Is a Mass Decontamination Facility available?">Mass Decontamination Facility Available:</td><td><input type=checkbox name=decon id=hsadecon value=1></td></td></tr>
</table>

<p style="margin:10px 0 6px 0">
<b>AVAILABLE BED COUNT:</b>
</p>
<table id=main_table border=0 cellpadding=1 cellspacing=0>
<tr><th>Area</th><th colspan=2>Avail / Total</th></tr>
<tr><td title="MEDD-SRGG">Med/Surg</td><td><input type=text name=medsurg id=hsamedsurg value="" size=5></td><td>/ <?=$bedcnts['l_cnt_medsurg']?></td></tr>
<tr><td title="TELL-EE">TELE</td><td><input type=text name=tele id=hsatele value="" size=5></td><td>/ <?=$bedcnts['l_cnt_tele']?></td></tr>
<tr><td title="EYE-CEE-YOU">ICU</td><td><input type=text name=icu id=hsaicu value="" size=5></td><td>/ <?=$bedcnts['l_cnt_icu']?></td></tr>
<tr><td title="PEE-EYE-CEE-YOU">PICU</td><td><input type=text name=picu id=hsapicu value="" size=5></td><td>/ <?=$bedcnts['l_cnt_picu']?></td></tr>
<tr><td title="ENN-EYE-CEE-YOU">NICU</td><td><input type=text name=nicu id=hsanicu value="" size=5></td><td>/ <?=$bedcnts['l_cnt_nicu']?></td></tr>
<tr><td title="PEEDZ">Peds</td><td><input type=text name=peds id=hsapeds value="" size=5></td><td>/ <?=$bedcnts['l_cnt_peds']?></td></tr>
<tr><td title="OH-BEE-GYNE">OB/Gyn</td><td><input type=text name=obgyn id=hsaobgyn value="" size=5></td><td>/ <?=$bedcnts['l_cnt_obgyn']?></td></tr>
<tr><td title="TRAH-MAH">Trauma</td><td><input type=text name=trauma id=hsatrauma value="" size=5></td><td>/ <?=$bedcnts['l_cnt_trauma']?></td></tr>
<tr><td title="BERN">Burn</td><td><input type=text name=burn id=hsaburn value="" size=5></td><td>/ <?=$bedcnts['l_cnt_burn']?></td></tr>
<tr><td title="EYE-SO-LAY-SHUN">Isolation&nbsp;&nbsp;</td><td><input type=text name=isolation id=hsaisolation value="" size=5></td><td>/ <?=$bedcnts['l_cnt_iso']?></td></tr>
<tr><td title="SYKE">Psych</td><td><input type=text name=psych id=hsapsych value="" size=5></td><td>/ <?=$bedcnts['l_cnt_psych']?></td></tr>
<tr><td title="OH-ARR">OR</td><td><input type=text name=or id=hsaor value="" size=5></td><td>/ <?=$bedcnts['l_cnt_or']?></td></tr>
<tr><td title="Ventilator">Ventilator</td><td><input type=text name=vent id=hsavent value="" size=5></td><td>&nbsp;</td></tr>
<tr><td title=""><div id="extra0" style="height:19px;width:120px;border:solid 1px #acacac;" contenteditable=true onblur="jQuery('#hsaextra0').attr('name',this.innerHTML)"></div></td><td><input type=text name="hsaextra0" id=hsaextra0 value="" size=5 onblur="addRow()"></td><td>&nbsp;</td></tr>
<tr><td title=""><div id="extra1" style="height:19px;width:120px;border:solid 1px #acacac;" contenteditable=true onblur="jQuery('#hsaextra1').attr('name',this.innerHTML)"></div></td><td><input type=text name="hsaextra1" id=hsaextra1 value="" size=5 onblur="addRow()"></td><td>&nbsp;</td></tr>
</table>
<br>
<button type=button style="cursor:pointer" title="Save HSA Poll Data and Close This Popup" onclick="saveData(this.form)">Save This Report</button>

</td></tr>
</table>
</center>
</form>

<script type="text/javascript">
//autocomplete
var loclookup = locs;
function initAuto() {
	//sources autogenerated by scripts/cron_generateNewLocations.php
	//written to scripts/ARES_Locations_and_People.js
	jQuery(".location").autocomplete({
		source: loclookup,
		focus: function(event, ui) {
			event.preventDefault();
			jQuery(this).val(ui.item.label);
		},
		select: function(event, ui) {
			event.preventDefault();
			jQuery(this).val(ui.item.label);
			location.href="ARES_HSA_Poll_standalone.php?fld="+ui.item.label;
		}
	});

}
jQuery(function(){
	initAuto();
});
</script>

</body>
</html>
