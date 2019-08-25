<?php
/* ####################
 * ARES_Event_Log.php
 * Event Log entry form
 * #################### */
#if (empty($_GET['admin'])) { header("Location: http://www.km6wka.net/ares");exit; }

if (!empty($_GET['fld'])) {
	$data = explode(":",$_GET['fld']);
	$stationcode = $data[0];
	$stationname = $data[1];
//	$stationevent = $data[2];
	$stationuser = $data[2];
}
$stationcode = (empty($stationcode)) ? "":$stationcode;
$stationname = (empty($stationname)) ? "":$stationname;
$stationevent = (empty($stationevent)) ? "":$stationevent;
$stationuser = (empty($stationuser)) ? "":$stationuser;
$focusfld = (empty($stationuser)) ? "who0":"msg0";

$stationcode = ($stationcode=="undefined") ? "":$stationcode;
$msgfld = (empty($_GET['rfld'])) ? "":substr($_GET['rfld'],3,strlen($_GET['rfld']));
?>
<!doctype html>
<html>
<head>
<title>ARES Event Log Entry</title>

<?php
include "common_includes.php";
?>

<script type="text/javascript">
function saveData(frm) {
	var frmdata = jQuery(frm).serializeToJSON();
	frmdata = JSON.stringify(frmdata);
	jQuery.ajax({
		type: "POST",
		url: "ajax_save_log.php",
		data: "staid=<?=$stationcode?>&rtyp=event&frmdata="+encodeURIComponent(frmdata),
		success: function(a,b,c) {
			console.log(a);
			parent.<?=$msgfld?>.innerHTML = frmdata;
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

<body onbeforeunload="return false" onload="init()">
<form id=evtlog>
<input type=hidden name=evttmstmp id=evttmstmp value="<?=date("Y-m-d H:i:s")?>">
<input type=hidden name=evtstation id=evtstation value="<?=$stationcode?>">
<input type=hidden name=evtevent id=evtevent value="<?=$stationevent?>">

<div style="position:absolute;top:0px;right:0px;width:12px;height:12px;border-radius:12px;border:solid 1px grey;background-color:lightgrey;text-align:center;font-size:12px;cursor:pointer;" onclick="parent.showModal('','none');" title="Close Event Log Popup WITHOUT Saving Poll Data">X</div>

<center>

<div style="font-weight:bold;font-size:1.3em;margin-bottom:20px;">ARES Event Log Entry</div>
<?# if (!empty($stationcode)) { ?>
Station ID: <input type=text title="<?=$stationname?>" class="location" value="<?=$stationcode?>"></th></tr>
<?# } else { ?>
<!--select onchange='checkType(this[this.selectedIndex].value,\"loc"+iter+"\")'>"+loclist+"</select></span></th></tr-->
<?# } ?>

<p>
<b>Entered By</b><br>
<input type=text id=who0 class="people" size=11 placeholder='Call Sign' value="<?=$stationuser?>" onblur='if(this.value==""){this.value=prompt("What is your call sign?");}setCallSign(0,this.value);this.onBlur="";disableblur(this);'>
</p>

<p>
<b>Activity/Comment</b><br>
<textarea id=msg0 class='msg' rows=2 style='width:400px;height:400px !important;padding:2px;'></textarea>
</p>

<button type=button style="cursor:pointer" title="Save Event Log Data and Close This Popup" onclick="saveData(this.form)">Save This Entry</button>


</center>
</form>

<script type="text/javascript">
if ('<?=$stationcode?>'=='undefined') { jQuery("#stationid").focus(); }
jQuery("#<?=$focusfld?>").focus();
</script>
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
			jQuery(this).val(ui.item.value);
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
			jQuery(this).val(ui.item.value);
			pplfld[this.id]=ui.item.value;
		}
	});

	var typdds = document.getElementsByClassName('typdd');
	for(i=0;i<typdds.length;i++) {
		typdds[i].innerHTML = typlist;
	}

});
</script>
</body>
</html>
