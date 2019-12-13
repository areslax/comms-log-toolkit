<?php
/* ####################################
 * ARES_Comms_Log.php
 * Form for quickly transcribing messages
 * Last updated: 2019-10-27
 * ###################################### */
ini_set('display_errors','0');

#echo "<pre>";print_r($_COOKIE);exit;

if (!isset($_SERVER["HTTPS"]) || $_SERVER["HTTPS"] != "on") {
	header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"], true, 301);
	exit;
}

//get existing log file
$newiter = 1;
/**/
//needs to be only for current station

if (!empty($_COOKIE['ncid'])) {
	$oid = (isset($_COOKIE['oid'])) ? $_COOKIE['oid']:"";
	$iid = (isset($_COOKIE['incid'])) ? $_COOKIE['incid']:"";
#	$lid = (isset($_COOKIE['lid'])) ? $_COOKIE['lid']:"";
	$sid = (isset($_COOKIE['staid'])) ? $_COOKIE['staid']:"";
	$ncid = (isset($_COOKIE['ncid'])) ? $_COOKIE['ncid']:"";
#echo "sid: ".$sid.", iid: ".$iid;exit;
	include "grab_report.php"; 
}
/**/
?>

<!doctype html>
<html lang="en">
<head>
<title>ARES Tactical Comms Log</title>

<?php
include "common_includes.php";
?>

<script type="text/javascript">
var isme = "";
var iter = <?=$newiter?>;
function setScribe(u) { isme = u; }

function modalDisplay(vis) {
	jQuery("#mask").css("display",vis);
	jQuery("#modal").css("display",vis);
}

function addRow(frm) {
	if (document.getElementById('msg'+iter).value=='') {
		iter++;
		jQuery("#main_table tr:last").after("<tr id=row"+iter+" valign=top><th id='rid"+iter+"'><input type=hidden name=ids[] value="+iter+"><input type=hidden name=msgstatus_"+iter+" id=msgstatus"+iter+" value=''>"+iter+"</th><td><input type=text size=20 name=ts_"+iter+" id=ts"+iter+" onfocus='hiliteme("+iter+")' onblur=\"getTimestamp(this.id);disableblur(this);\" placeholder='TAB to Set'></td><td><select name=rxtx_"+iter+" class='px40' onfocus='hiliteme("+iter+")' onchange='setdir(this.value,"+iter+")'><option>Rx</option><option>Tx</option><option>Comment</option></select></td><td><input type=text name=loc_"+iter+" id=loc"+iter+" class='location' onfocus=\"hiliteme("+iter+");this.select();\" placeholder='From Call' style='font-size:.8em'></td><td><select name=typ_"+iter+" id=typ"+iter+" class='typdd px80' onfocus='hiliteme("+iter+")' onchange='checkType(this[this.selectedIndex].value,\"loc"+iter+"\",0,encodeURIComponent(this.form.incidentid.value),this.form.stationid.value);checkIndex(this,this.value);'>"+typlist+"</select></td><td style='padding-bottom:0px;text-align:center;' id='msgbox_"+iter+"'><textarea name=msg_"+iter+" id=msg"+iter+" class='msg' rows=2 style='width:350px;padding:2px;' onfocus='hiliteme("+iter+")'></textarea></td><td id=reqid_"+iter+" align=center><select name=act_"+iter+" id=act"+iter+" onfocus='hiliteme("+iter+")' style='width:60px' onchange='checkAction("+iter+",this.selectedIndex)' onblur='document.getElementById(\"who"+iter+"\").focus()'><option>No<option>Yes</select><div id=actdiv"+iter+" class='actdiv'><button type=button style='display:inherit;' class='combut noprint' onclick='markDone("+iter+")'>Done</button></div></td><td><input type=text name=who_"+iter+" id=who"+iter+" size=9 style='text-align:center' placeholder='Callsign' onfocus='hiliteme("+iter+")' onblur='if(this.value==\"\"){this.value=prompt(\"What is your call sign?\");}setCallSign(0,this.value);addRow(this.form);document.getElementById(\"who"+(iter+1)+"\").value=this.value;this.onBlur=\"\";disableblur(this);document.getElementById(\"ts"+(iter+1)+"\").focus();'></td></tr>\n");
		init();
		initAuto();
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
function setdir(val,itr) {
	var dir = "Tac Call";
	switch(val) {
		case "Rx":
		dir = "From Call";
		break;
		case "Tx":
		dir = "To Call";
		break;
	}
	jQuery("#loc"+itr).attr("placeholder",dir);
}
var stationid = "";
var stationloc = "";
var eventid = "";
jQuery(document).ready(function(){
<?php if (empty($grab)) { ?>
	jQuery('#stationid').focus();
<?php } ?>
});
var sid = 0;
var iid = 0;
function saveData(frm) {
	var frmdata = jQuery(frm).serialize();
	jQuery.ajax({
		type: "POST",
		url: "api/reports/save.php",
		data: frmdata,
		success: function(a,b,c) {
			console.log(a);
		}
	});
}

var gotalert = 0;
var chkmsgs = setInterval("checkAdminAlert()",10000);
</script>
<style type="text/css">
#menu {
	margin: 0 0;
	padding-left: 0;
	list-style-type: none;
	border: none;
	background-color: none;
}
.lnk {
	padding: 2px;
	font-size: 10px;
	cursor: pointer;
}
</style>
</head>

<body onbeforeunload="return false" onload="init()">
<input type=hidden name=tmstmp id=tmstmp>

<iframe id=alertmsg></iframe>

<center>
<div style="position:fixed;top:6px;left:-22px;width:80px;text-align:center;" class="noprint">
	<input type=image src="images/icon-save.png" title="Save this Log" style="width:16px;margin-bottom:1px;" onclick="saveData(document.forms[0])"><br>
	<input type=image src="images/icon-print.png" title="Print this Log" style="width:16px;" onclick="self.print()"><br>
	<input type=image src="images/icon-new.png" title="Start a New Log" style="width:16px;" onclick="startNewLog()"><br><br>
	<div style="position:relative">
	<img id=iconloc src="images/icon-location-on.png" style="width:16px;cursor:pointer;" title="Set Contact field to Location" onclick="loclookup=locs;this.src='images/icon-location-on.png';jQuery('#iconppl').attr('src','images/icon-people.png');jQuery('#locul').css('display','none');initAuto();" onmouseover="jQuery('#pplul').css('display','none');jQuery('#contactfld').css('background-color','lightgreen');jQuery('#locul').css('display','block');" onmouseout="jQuery('#contactfld').css('background-color','white')">
	<div id=locul style="position:absolute;top:0px;left:52px;padding:2px;text-align:left;background-color:white;display:none;"></div>
	</div>
	<div style="position:relative">
	<img id=iconppl src="images/icon-people.png" style="width:16px;cursor:pointer;" title="Set Contact field to People" onclick="loclookup=people;this.src='images/icon-people-on.png';jQuery('#iconloc').attr('src','images/icon-location.png');jQuery('#pplul').css('display','none');initAuto();" onmouseover="jQuery('#locul').css('display','none');jQuery('#contactfld').css('background-color','lightgreen');jQuery('#pplul').css('display','block');" onmouseout="jQuery('#contactfld').css('background-color','white')">
	<div id=pplul style="position:absolute;top:0px;left:52px;padding:2px;text-align:left;background-color:white;display:none;"></div>
	</div>
<!--
	<button type=button style="width:70px" onclick="checkType(3,stationid,1)">EVENT</button><br>
-->
</div>
<?php
$oid = (empty($oid)) ? "1":$oid;
$stationid = (empty($stationid)) ? "":$stationid;
$stationloc = (empty($stationloc)) ? "":$stationloc;
$incidentid = (empty($incidentid)) ? "":$incidentid;
$incidentname = (empty($incidentname)) ? "":$incidentname;

$isave = (empty($stationid)) ? '<img src="images/icon-save.png" style="cursor:pointer;margin-bottom:-4px;" title="Save This Net Control" alt="Save This Net Control" width=16 border=0 onclick="setNetControl(sid,document.getElementById(\'stationgeo\').value,document.getElementById(\'incidentid\').value);this.src=\'images/icon-check.png\';this.title=\'Net Control Saved\';this.alt=\'Net Control Saved\';this.style.cursor=\'default\';this.onclick=\'\';">':'<img src="images/icon-check.png" style="margin-bottom:-4px" title="Net Control Saved" alt="Net Control Saved" width=16 border=0>';
?>
<form id=commslog method=post action="https://km6wka.net/ares/api/reports/save.php">
<input type=hidden name=o_id value=<?=$oid?>>
<table class="main_table" id="main_table" border=1 cellpadding=4 cellspacing=0 style="border-color:white">
<!-- tr><th style="font-size:11px" colspan=9>This would be used by a local, or admin net control for making a report. There is a different display for the admin management view.</th></tr -->
<tr><th id=log_date colspan=2>Tactical Comms Log</th><th align=left colspan=7><div style="display:inline;color:grey;font-size:13px;text-align:center;">Local Timestamp:<br><?=date("Y-m-d H:i")?></div><div style="margin-top:-10px;float:right;">Net&nbsp;Control:&nbsp;<input type=text size=10 name=stationid id=stationid placeholder="Station ID" class="topknot" onfocus="getGeoLoc();this.style.backgroundColor='yellow'" onblur="this.style.backgroundColor='white';sid=this.value;" value="<?=$stationid?>">&nbsp;<input type=hidden id=stationgeo><input type=hidden name=incidentid id=incidentid value="<?=$incidentid?>"><input type=text size=20 name=incidentname id=incidentname placeholder="Which Incident?" class="topknot" value="<?=$incidentname?>" onfocus="this.style.backgroundColor='white';this.select();">&nbsp;<?=$isave?></div></th></tr>
<tr><th>ID</th><th>Timestamp</th><th>Rx/Tx</th><th id=contactfld>Contact</th><th>Type</th><th>Message</th><th width=70>Req'd?</th><th><small>Logged By</small></th></tr>
<?php
if (empty($grab)) {
?>
<tr id=row0 valign=top><th id=rid0><input type=hidden name=ids[] value=0><input type=hidden name=msgstatus_0 id=msgstatus0 value=''>0</th><td><input type=text size=20 name=ts_0 id=ts0 onfocus="hiliteme(0)" onblur='getTimestamp(this.id);disableblur(this);' placeholder='TAB to Set'></td><td><select name=rxtx_0 class='px40' onfocus="hiliteme(0)" onchange="setdir(this.value,0)"><option>Rx</option><option>Tx</option><option>Comment</option></select></td><td><input type=text name=loc_0 id=loc0 class='location' onfocus="hiliteme(0);this.select();" placeholder="From Call" style="font-size:.8em"></td><td><select name=typ_0 id=typ0 class="typdd px80" onfocus="hiliteme(0)" onchange="checkType(this[this.selectedIndex].value,'loc0',0,encodeURIComponent(this.form.incidentid.value),this.form.stationid.value);checkIndex(this,this.value);"><!--$typlist--></select></td><td style="padding-bottom:0px;text-align:center;" id="msgbox_0"><textarea name=msg_0 id=msg0 class='msg' rows=2 style='width:350px;padding:2px;' onfocus="hiliteme(0)"></textarea>
</td><td id=reqtd_0 align=center><select name=act_0 id=act0 onfocus="hiliteme(0)" style="width:60px" onchange='checkAction(0,this.selectedIndex)' onblur='document.getElementById("who0").focus()'><option>No<option>Yes</select><div id=actdiv0 class='actdiv'><button type=button style='display:inherit;' class='combut noprint' onclick='markDone(0)'>Done</button></div></td><td><input type=text name=who_0 id=who0 size=9 style='text-align:center' placeholder='Callsign' onfocus="hiliteme(0)" onblur='if(this.value==""){this.value=prompt("What is your call sign?");}setCallSign(0,this.value);addRow(this.form);document.getElementById("who1").value=this.value;this.onBlur="";disableblur(this);document.getElementById("ts1").focus();'></td></tr>
<tr id=row1 valign=top><th id=rid1><input type=hidden name=ids[] value=1><input type=hidden name=msgstatus_1 id=msgstatus1 value=''>1</th><td><input type=text size=20 name=ts_1 id=ts1 onfocus="hiliteme(1)" onblur='getTimestamp(this.id);disableblur(this);' placeholder='TAB to Set'></td><td><select name=rxtx_1 class='px40' onfocus="hiliteme(1)" onchange="setdir(this.value,1)"><option>Rx</option><option>Tx</option><option>Comment</option></select></td><td><input type=text name=loc_1 id=loc1 class='location' onfocus="hiliteme(1);this.select();" placeholder="From Call" style="font-size:.8em"></td><td><select name=typ_1 id=typ1 class="typdd px80" onfocus="hiliteme(1)" onchange="checkType(this[this.selectedIndex].value,'loc1',0,encodeURIComponent(this.form.incidentid.value),this.form.stationid.value);checkIndex(this,this.value);"><!--$typlist--></select></td><td style="padding-bottom:0px;text-align:center;" id="msgbox_1"><textarea name=msg_1 id=msg1 class='msg' rows=2 style='width:350px;padding:2px;' onfocus="hiliteme(1)"></textarea></td><td id=reqtd_1 align=center><select name=act_1 id=act1 onfocus="hiliteme(1)" style='width:60px' onchange='checkAction(1,this.selectedIndex)' onblur='document.getElementById("who1").focus()'><option>No<option>Yes</select><div id=actdiv1 class='actdiv'><button type=button style='display:inherit;' class='combut noprint' onclick='markDone(1)'>Done</button></div></td><td><input type=text name=who_1 id=who1 size=9 style='text-align:center' placeholder='Callsign' onfocus="hiliteme(1)" onblur='if(this.value==""){this.value=prompt("What is your call sign?");}setCallSign(1,this.value);addRow(this.form);this.onBlur="";disableblur(this);document.getElementById("who2").value-this.value;document.getElementById("ts2").focus();';'></td></tr>
<?php
}
else {
	//got report for this operator, this location, this incident, so show it
	//also updates newiter to accomodate existing records
	echo $grab;
	echo "\n<script type='text/javascript'>init();".$tsfocus."</script>\n";
}
?>
</table>
</form>
</center>

<div id=mask style="position:fixed;top:0px;left:0px;width:100%;height:100%;background-color:lightgrey;opacity:0.4;display:none;" onclick="modalView('none')"></div>

<iframe id=modal style="position:fixed;top:10px;left:50%;width:800px;margin-left:-400px;height:90%;padding:10px;background-color:white;border:solid 1px black;border-radius:6px;display:none;"></iframe>


<script type="text/javascript">
//location and people type menus
//console.log(loc_med);
var locul = "";
locul += (loc_med.length<1) ? "<div class='lnk ui-state-disabled'>Medical</div>":"<div class='lnk' onclick=\"loclookup=loc_med;jQuery('#iconloc').attr('src','images/icon-location-on.png');jQuery('#iconppl').attr('src','images/icon-people.png');jQuery('#locul').css('display','none');initAuto();jQuery('#locul').css('display','none');\">Medical</div>";
locul += (loc_pd.length<1) ? "<div class='lnk ui-state-disabled'>Police</div>":"<div class='lnk' onclick=\"loclookup=loc_pd;jQuery('#iconloc').attr('src','images/icon-location-on.png');jQuery('#iconppl').attr('src','images/icon-people.png');jQuery('#locul').css('display','none');initAuto();jQuery('#locul').css('display','none');\">Police</div>";
locul += (loc_fd.length<1) ? "<div class='lnk ui-state-disabled'>Fire</div>":"<div class='lnk' onclick=\"loclookup=loc_fd;jQuery('#iconloc').attr('src','images/icon-location-on.png');jQuery('#iconppl').attr('src','images/icon-people.png');jQuery('#locul').css('display','none');initAuto();jQuery('#locul').css('display','none');\">Fire</div>";
locul += (loc_psy.length<1) ? "<div class='lnk ui-state-disabled'>Psych</div>":"<div class='lnk' onclick=\"loclookup=loc_psy;jQuery('#iconloc').attr('src','images/icon-location-on.png');jQuery('#iconppl').attr('src','images/icon-people.png');jQuery('#locul').css('display','none');initAuto();jQuery('#locul').css('display','none');\">Psych</div>";
locul += (loc_reh.length<1) ? "<div class='lnk ui-state-disabled'>Rehab</div>":"<div class='lnk' onclick=\"loclookup=loc_reh;jQuery('#iconloc').attr('src','images/icon-location-on.png');jQuery('#iconppl').attr('src','images/icon-people.png');jQuery('#locul').css('display','none');initAuto();jQuery('#locul').css('display','none');\">Rehab</div>";
locul += (loc_sta.length<1) ? "<div class='lnk ui-state-disabled'>Station</div>":"<div class='lnk' onclick=\"loclookup=loc_sta;jQuery('#iconloc').attr('src','images/icon-location-on.png');jQuery('#iconppl').attr('src','images/icon-people.png');jQuery('#locul').css('display','none');initAuto();jQuery('#locul').css('display','none');\">Station</div>";
jQuery("#locul").html(locul);
var pplul = "";
pplul += (people_ops.length<1) ? "<div class='lnk ui-state-disabled'>Operators</div>":"<div class='lnk' onclick=\"loclookup=people_ops;jQuery('#iconloc').attr('src','images/icon-location.png');jQuery('#iconppl').attr('src','images/icon-people-on.png');jQuery('#pplul').css('display','none');initAuto();jQuery('#pplul').css('display','none');\">Operators</div>";
pplul += (people_net.length<1) ? "<div class='lnk ui-state-disabled'>Net Controls</div>":"<div class='lnk' onclick=\"loclookup=people_net;jQuery('#iconloc').attr('src','images/icon-location.png');jQuery('#iconppl').attr('src','images/icon-people-on.png');jQuery('#pplul').css('display','none');initAuto();jQuery('#pplul').css('display','none');\">Net Controls</div>";
pplul += (people_adm.length<1) ? "<div class='lnk ui-state-disabled'>Administrators</div>":"<div class='lnk' onclick=\"loclookup=people_adm;jQuery('#iconloc').attr('src','images/icon-location.png');jQuery('#iconppl').attr('src','images/icon-people-on.png');jQuery('#pplul').css('display','none');initAuto();jQuery('#pplul').css('display','none');\">Administrators</div>";
jQuery("#pplul").html(pplul);
//console.log(locul);
//autocomplete
var loclookup = locs;
var peeplookup = people;
var locfld = new Array();
var pplfld = new Array();
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
			jQuery(this).val(ui.item.value);
			locfld[this.id]=ui.item.label;
		}
	});

	jQuery(".people").autocomplete({
		source: peeplookup,
		focus: function(event, ui) {
			event.preventDefault();
			jQuery(this).val(ui.item.label);
		},
		select: function(event, ui) {
			event.preventDefault();
			jQuery(this).val(ui.item.value);
			pplfld[this.id]=ui.item.mid;
		}
	});
	jQuery("#incidentname").autocomplete({
		source: incidents,
		focus: function(event, ui) {
			event.preventDefault();
			jQuery(this).val(ui.item.label);
		},
		select: function(event, ui) {
			event.preventDefault();
			if (ui.item.iid>0) {
				jQuery(this).val(ui.item.value);
				jQuery("#incidentid").val(ui.item.iid);
				iid = ui.item.iid;
			}
			else {
				jQuery("#modal").attr("src","ARES_New_Incident.php?gps="+gps);
				jQuery("#modal").css("height","260px");
				modalDisplay("block");
			}
		}
	});
}
function initIncident() {
	jQuery.get("ajax_check_incidents.php", function(data){
		console.log(data);
		var newincidents = JSON.parse(data);
		//check if incidents changed, and update array
		if (incidents.length!=newincidents.length) {
			jQuery("#incidentname").css("backgroundColor","yellow");
			incidents = newincidents;
			jQuery("#incidentname").autocomplete({
				source: incidents,
				focus: function(event, ui) {
					event.preventDefault();
					jQuery(this).val(ui.item.label);
				},
				select: function(event, ui) {
					event.preventDefault();
					jQuery(this).val(ui.item.value);
					jQuery("#incidentid").val(ui.item.iid);
					iid = ui.item.iid;
				}
			});//end autocomplete
			//generate new js autocomplete array file
			//so new logins will get the latest version
			//uncomment below for production
			//jQuery.get("scripts/cron_generateNewLocations.php");
		}//end if length
	});//end get1
}
jQuery(function(){
	initAuto();
	//typlist defined in scripts/ARES_common_methods.js
	var typdds = jQuery(".typdd");
	for(i=0;i<typdds.length;i++) {
		if (typdds[i].selectedIndex<1) {
			typdds[i].innerHTML = typlist;
		}
	}
	//check for new incidents every 30 secs
	//uncomment below for production
	//var chkinc = setInterval("initIncident()",30000);
});
</script>
</body>
</html>
