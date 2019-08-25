<?php
/* ####################################
 * ARES_Comms_Log.php
 * Form for quickly transcribing messages
 * Last updated: 2019-07-31
 * ###################################### */
ini_set('display_errors','0');

//get existing log file
$newiter = 1;
$commslog = "";
/**/
//needs to be only for current station
if (file_exists("reports/comms_log.json")) {
	$commslog = ltrim(rtrim(file_get_contents("reports/comms_log.json"),"}"),"{");
	$commslog = explode('","',$commslog);
	$clog = array();
	foreach($commslog as $c) {
		$c = explode('":"',$c);
		$c[0] = trim($c[0],'"');
		$c[1] = trim($c[1],'"');
		$clog[$c[0]] = $c[1];
	}
	$rtypes = array(0=>"",1=>"MCI Poll",2=>"HSA Poll",3=>"Event",4=>"Resource Request",5=>"Relay Request",6=>"Action",7=>"Report");
	$rows = array();
	foreach($clog as $c => $v) {
		if ($c=='stationid') { $stationid = $v; }
		if ($c=='stationloc') { $stationloc = $v; }
		if ($c=='incidentid') { $incidentid = $v; }
		if ($c=='incidentname') { $incidentname = $v; }
		if (!stristr($c,"_")) { continue; }
		$thisid = substr($c,strrpos($c,"_")+1,strlen($c));
		if (!in_array($thisid,$rows)) {
			$rows[$thisid] = array();
		}
		$rows[$thisid]['ts']=$clog["ts_".$thisid];
		$rows[$thisid]['rxtx']=$clog["rxtx_".$thisid];
		$rows[$thisid]['loc']=$clog["loc_".$thisid];
		$rows[$thisid]['typ']=$clog["typ_".$thisid];
		$rows[$thisid]['msg']=$clog["msg_".$thisid];
		$rows[$thisid]['msgfrom']=$clog["msgfrom_".$thisid];
		$rows[$thisid]['msgto']=$clog["msgto_".$thisid];
		$rows[$thisid]['msgdelivered']=$clog["msgdelivered_".$thisid];
		$rows[$thisid]['act']=$clog["act_".$thisid];
		$rows[$thisid]['actdone']=$clog["actdone_".$thisid];
		$rows[$thisid]['who']=$clog["who_".$thisid];

	}
#debug
#echo "<pre>";print_r($rows);exit;
	//make into useable arrays
	foreach($rows as $k => $v) {
		foreach($v as $o => $l) {
			$$o[$k] = $l;
		}
	}
	$newiter = count($ts);
}

/**/
?>

<!doctype html>
<html>
<head>
<title>ARES Comms Log</title>

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
		jQuery("#main_table tr:last").after("<tr id=row"+iter+" valign=top><th id='rid"+iter+"'><input type=hidden name=ids[] value="+iter+">"+iter+"</th><td><input type=text size=20 name=ts_"+iter+" id=ts"+iter+" onfocus='hiliteme("+iter+")' onblur=\"getTimestamp(this.id);disableblur(this);\" placeholder='TAB to Set'></td><td><select name=rxtx_"+iter+" class='px40' onfocus='hiliteme("+iter+")'><option>Rx</option><option>Tx</option><option>Comment</option></select></td><td><input type=text name=loc_"+iter+" id=loc"+iter+" class='location' onfocus=\"hiliteme("+iter+")\"></td><td><select name=typ_"+iter+" id=typ"+iter+" class='typdd px80' onfocus='hiliteme("+iter+")' onchange='checkType(this[this.selectedIndex].value,\"loc"+iter+"\",0,encodeURIComponent(this.form.incidentid.value),this.form.stationid.value)'>"+typlist+"</select></td><td style='padding-bottom:0px;text-align:center;' id='msgbox_"+iter+"'><textarea name=msg_"+iter+" id=msg"+iter+" class='msg' rows=2 style='width:350px;padding:2px;' onfocus='hiliteme("+iter+")'></textarea></td><td align=center><select name=act_"+iter+" id=act"+iter+" onfocus='hiliteme("+iter+")' style='width:60px' onchange='checkAction("+iter+",this.selectedIndex)' onblur='document.getElementById(\"who"+iter+"\").focus()'><option>No<option>Yes</select><div id=actdiv"+iter+" class='actdiv'><button type=button style='display:inherit;' class='combut' onclick='markDone("+iter+")'>Done</button></div></td><td><input type=text name=who_"+iter+" id=who"+iter+" size=9 style='text-align:center' placeholder='Call Sign' onfocus='hiliteme("+iter+")' onblur='if(this.value==\"\"){this.value=prompt(\"What is your call sign?\");}setCallSign(0,this.value);addRow(this.form);document.getElementById(\"who"+(iter+1)+"\").value=this.value;this.onBlur=\"\";disableblur(this);document.getElementById(\"ts"+(iter+1)+"\").focus();'></td></tr>\n");
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
var stationid = "";
var stationloc = "";
var eventid = "";
jQuery(document).ready(function(){
	jQuery('#stationid').focus();
});
var sid = 0;
var iid = 0;
function saveData(frm) {
	var frmdata = jQuery(frm).serializeToJSON();
	frmdata = JSON.stringify(frmdata);
	jQuery.ajax({
		type: "POST",
		url: "ajax_save_log.php",
		data: "rtyp=comms&frmdata="+encodeURIComponent(frmdata)+"&stationid="+sid+"&incidentid="+iid,
		success: function(a,b,c) {
			console.log(a);
		}
	});
}
</script>

<body onbeforeunload="return false" onload="init()">
<input type=hidden name=tmstmp id=tmstmp>

<center>
<div style="position:fixed;top:10px;left:6px;width:80px;text-align:center;" class="noprint">
<?php if (!empty($_GET['isjb'])) { ?>
	<button type=button style="width:70px" onclick="saveData(document.forms[0])">SAVE</button><br>
<?php } ?>
<!--	<button type=button style="width:70px" onclick="self.print()">PRINT</button><br><br>
	<button type=button style="width:70px" onclick="checkType(3,stationid,1)">EVENT</button><br>
-->
</div>
<?php
$stationid = (empty($stationid)) ? "":$stationid;
$stationloc = (empty($stationloc)) ? "":$stationloc;
$incidentid = (empty($incidentid)) ? "":$incidentid;
$incidentname = (empty($incidentname)) ? "":$incidentname;
?>
<form id=commslog>
<table class="main_table" id="main_table" border=1 cellpadding=4 cellspacing=0 style="border-color:white">
<tr><th style="font-size:11px" colspan=9>This would be used by a local, or admin net control for making a report. There is a different display for the admin management view.</th></tr>
<tr><th id=log_date colspan=2>ARES-LAX Comms Log</th><th align=left colspan=7><span style="font-size:13px;">&nbsp;<?=date("Y-m-d H:i")?></span><div style="float:right;">Report&nbsp;From:&nbsp;<input type=text size=10 name=stationid id=stationid placeholder="Station ID" class="topknot" onfocus="this.style.backgroundColor='yellow'" onblur="this.style.backgroundColor='white';sid=this.value;" value="<?=$stationid?>">&nbsp;<input type=text size=18 name=stationloc id=stationloc placeholder="Station Location" class="topknot" value="<?=$stationloc?>">&nbsp;<input type=hidden name=incidentid id=incidentid value="<?=$incidentid?>"><input type=text size=20 name=incidentname id=incidentname placeholder="Incident Identifier" class="topknot" value="<?=$incidentname?>" onfocus="this.style.backgroundColor='white';this.select();"></div></th></tr>
<tr><th>ID</th><th>Timestamp</th><th>Rx/Tx</th><th>Contact</th><th>Type</th><th>Message</th><th width=70>Req'd?</th><th><small>Logged By</small></th></tr>
<?php
if (empty($ts)) {
?>
<tr id=row0 valign=top><th id=rid0><input type=hidden name=ids[] value=0>0</th><td><input type=text size=20 name=ts_0 id=ts0 onfocus="hiliteme(0)" onblur='getTimestamp(this.id);disableblur(this);' placeholder='TAB to Set'></td><td><select name=rxtx_0 class='px40' onfocus="hiliteme(0)"><option>Rx</option><option>Tx</option><option>Comment</option></select></td><td><input type=text name=loc_0 id=loc0 class='location' onfocus="hiliteme(0)" placeholder="Lookup"></td><td><select name=typ_0 id=typ0 class="typdd px80" onfocus="hiliteme(0)" onchange="checkType(this[this.selectedIndex].value,'loc0',0,encodeURIComponent(this.form.incidentid.value),this.form.stationid.value);"><!--$typlist--></select></td><td style="padding-bottom:0px;text-align:center;" id="msgbox_0"><textarea name=msg_0 id=msg0 class='msg' rows=2 style='width:350px;padding:2px;' onfocus="hiliteme(0)"></textarea>
</td><td align=center><select name=act_0 id=act0 onfocus="hiliteme(0)" style="width:60px" onchange='checkAction(0,this.selectedIndex)' onblur='document.getElementById("who0").focus()'><option>No<option>Yes</select><div id=actdiv0 class='actdiv'><button type=button style='display:inherit;' class='combut' onclick='markDone(0)'>Done</button></div></td><td><input type=text name=who_0 id=who0 size=9 style='text-align:center' placeholder='Call Sign' onfocus="hiliteme(0)" onblur='if(this.value==""){this.value=prompt("What is your call sign?");}setCallSign(0,this.value);addRow(this.form);document.getElementById("who1").value=this.value;this.onBlur="";disableblur(this);document.getElementById("ts1").focus();'></td></tr>
<tr id=row1 valign=top><th id=rid1><input type=hidden name=ids[] value=1>1</th><td><input type=text size=20 name=ts_1 id=ts1 onfocus="hiliteme(1)" onblur='getTimestamp(this.id);disableblur(this);' placeholder='TAB to Set'></td><td><select name=rxtx_1 class='px40' onfocus="hiliteme(1)"><option>Rx</option><option>Tx</option><option>Comment</option></select></td><td><input type=text name=loc_1 id=loc1 class='location' onfocus="hiliteme(1)"></td><td><select name=typ_1 id=typ1 class="typdd px80" onfocus="hiliteme(1)" onchange="checkType(this[this.selectedIndex].value,'loc1',0,encodeURIComponent(this.form.incidentid.value),this.form.stationid.value)"><!--$typlist--></select></td><td style="padding-bottom:0px;text-align:center;" id="msgbox_1"><textarea name=msg_1 id=msg1 class='msg' rows=2 style='width:350px;padding:2px;' onfocus="hiliteme(1)"></textarea></td><td align=center><select name=act_1 id=act1 onfocus="hiliteme(1)" style='width:60px' onchange='checkAction(1,this.selectedIndex)' onblur='document.getElementById("who1").focus()'><option>No<option>Yes</select><div id=actdiv1 class='actdiv'><button type=button style='display:inherit;' class='combut' onclick='markDone(1)'>Done</button></div></td><td><input type=text name=who_1 id=who1 size=9 style='text-align:center' placeholder='Call Sign' onfocus="hiliteme(1)" onblur='if(this.value==""){this.value=prompt("What is your call sign?");}setCallSign(1,this.value);addRow(this.form);this.onBlur="";disableblur(this);document.getElementById("who2").value-this.value;document.getElementById("ts2").focus();';'></td></tr>
<?php
}
else {
	foreach($ts as $i => $v) {
		if (!empty($v)) {
			echo "<tr id=row".$i." valign=top><th id=rid".$i.">".$i."</th><td><input type=text size=20 name=ts_".$i." id=ts".$i." onfocus='hiliteme(".$i.")' value='".$ts[$i]."'></td><td><select name=rxtx_".$i." class='px40' onfocus='hiliteme(".$i.")'><option";if($rtx[i]=='Rx'){echo" selected";}echo">Rx</option><option";if($rtx[i]=='Tx'){echo" selected";}echo">Tx</option><option";if($rtx[i]=='Comment'){echo" selected";}echo">Comment</option></select></td><td><input type=text name=loc_".$i." id=loc".$i." class='location' onfocus='hiliteme(".$i.")'></td><td><select name=typ_".$i." id=typ".$i." class='typdd px80' onfocus='hiliteme(".$i.")' onchange=\"checkType(this[this.selectedIndex].value,'loc".$i."',0,encodeURIComponent(this.form.incidentid.value),this.form.stationid.value)\"><!--$typlist--></select></td><td style='padding-bottom:0px;text-align:center;'>";
			echo "<textarea name=msg_".$i." id=msg".$i." class='msg' rows=2 style='width:300px;padding:2px;' onfocus='hiliteme(".$i.")'></textarea></td><td align=left><input type=text name=msgfrom_".$i." id=msgfrom".$i." class='people' style='width:160px' onfocus='hiliteme(".$i.")' placeholder='Relay Message From'><br><input type=text name=msgto_".$i." id=msgto".$i." onfocus='hiliteme(".$i.")' class='people' style='width:160px' placeholder='Relay Message To'></td><td align=center><input name=act_".$i." id=act".$i." onfocus='hiliteme(".$i.")' style='width:60px' value='".$act[$i]."'><div id=actdiv".$i." class='actdiv'>";
			echo "></td><td align=center><select name=act_".$i." id=act".$i." onfocus='hiliteme(".$i.")' style='width:60px' onchange='checkAction(".$i.",this.selectedIndex)' onblur=\"document.getElementById('who".$i."').focus()\"><option>No<option>Yes</select><div id=actdiv".$i." class='actdiv'>";
			if (empty($actdone[$i])) {
				echo "<button type=button style='display:inherit;' class='combut' onclick='markDone(".$i.")'>Done</button>";
			}
			else {
				echo "<textarea name='actdone_".$i."' id='actdone".$i."' style='resize:none;text-align:center;font-size:10px;width:80px;height:22px;background:none;border:none;'>".$actdone[$i]."</textarea>";
			}
			echo "</div></td><td><input type=text name=who_".$i." id=who".$i." size=9 style='text-align:center' placeholder='Call Sign' onfocus='hiliteme(".$i.")' onblur=\"if(this.value==''){this.value=prompt('What is your call sign?');}setCallSign(".$i.",this.value);addRow(this.form);document.getElementById('who".($i+1)."').value=this.value;this.onBlur='';disableblur(this);document.getElementById('ts".($i+1)."').focus();\"></td></tr>";
		}
		else {
			echo "<tr id=row".$i." valign=top><th id=rid".$i.">".$i."</th><td><input type=text size=20 name=ts_".$i." id=ts".$i." onfocus='hiliteme(".$i.")' onblur='getTimestamp(this.id);disableblur(this);' placeholder='TAB to Set'></td><td><select name=rxtx_".$i." class='px40' onfocus='hiliteme(".$i.")'><option>Rx</option><option>Tx</option><option>Comment</option></select></td><td><select name=loc_".$i." id=loc".$i." class='locdd px100' onfocus='hiliteme(".$i.")'><!--$loclist--></select></td><td><select name=typ_".$i." id=typ".$i." class='typdd px80' onfocus='hiliteme(".$i.")' onchange=\"checkType(this[this.selectedIndex].value,'loc".$i."',0,encodeURIComponent(this.form.incidentid.value),this.form.stationid.value)\"><!--$typlist--></select></td><td style='padding-bottom:0px'><textarea name=msg_".$i." id=msg".$i." class='msg' rows=2 style='width:300px;padding:2px;' onfocus='hiliteme(".$i.")'></textarea></td><td align=left><input type=text name=msgfrom_".$i." id=msgfrom".$i." style='width:160px' onfocus='hiliteme(".$i.")' placeholder='Relay Message From'><br><input type=text name=msgto_".$i." id=msgto".$i." onfocus='hiliteme(".$i.")' style='width:160px' placeholder='Relay Message To'></td><td align=center><select name=act_".$i." id=act".$i." onfocus='hiliteme(".$i.")' style='width:60px' onchange='checkAction(".$i.",this.selectedIndex)' onblur=\"document.getElementById('who".$i."').focus()\"><option>No<option>Yes</select><div id=actdiv".$i." class='actdiv'><button type=button style='display:inherit;' class='combut' onclick='markDone(".$i.")'>Done</button></div></td><td><input type=text name=who_".$i." id=who".$i." size=9 style='text-align:center' placeholder='Call Sign' onfocus='hiliteme(".$i.")' onblur=\"if(this.value==''){this.value=prompt('What is your call sign?');}setCallSign(".$i.",this.value);addRow(this.form);document.getElementById('who".($i+1)."').value=this.value;this.onBlur='';disableblur(this);document.getElementById('ts".($i+1)."').focus();\"></td></tr>\n";
		}
	}
	echo "\n<script type='text/javascript'>init();</script>\n";
}
?>
</table>
</form>
</center>

<div id=mask style="position:fixed;top:0px;left:0px;width:100%;height:100%;background-color:lightgrey;opacity:0.4;display:none;" onclick="modalView('none')"></div>

<iframe id=modal style="position:fixed;top:10px;left:50%;width:800px;margin-left:-400px;height:90%;padding:10px;background-color:white;border:solid 1px black;border-radius:6px;display:none;"></iframe>


<script type="text/javascript">
//autocomplete
var locfld = new Array();
var pplfld = new Array();
function initAuto() {
	//sources autogenerated by scripts/generateNewLocations.php
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
		source: people,
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
			jQuery(this).val(ui.item.value);
			jQuery("#incidentid").val(ui.item.iid);
			iid = ui.item.iid;
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
	var typdds = document.getElementsByClassName('typdd');
	for(i=0;i<typdds.length;i++) {
		typdds[i].innerHTML = typlist;
	}
	//check for new incidents every 30 secs
	//uncomment below for production
	//var chkinc = setInterval("initIncident()",30000);
});
</script>
</body>
</html>
