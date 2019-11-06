<?php
/* ###################################
 * ARES_Net_Control_Manage.php
 * Interface for managing net controls
 * ################################### */
//if (empty($_GET['admin'])) { header("Location: https://www.km6wka.net/ares");exit; }

require "db_conn.php";
$searchresuts = "";

#debug
#echo "<pre>";print_r($_POST);exit;

//got new net control form submit
if (!empty($_POST['addnc'])) {
	$q = $conn->prepare("insert into Net_Controls (nc_lead_id,nc_parent_id,nc_callsign,nc_location,nc_gps,nc_active,nc_note) values (:leadid,:parentid,:callsign,:location,:gps,1,:note)");
	$q->execute(array(':leadid'=>$_POST['nc_lead_id'],':parentid'=>$_POST['nc_parent_id'],':callsign'=>$_POST['nc_callsign'],':location'=>$_POST['nc_location'],':gps'=>$_POST['nc_gps'],':note'=>$_POST['nc_note']));
	$ncid = $conn->lastInsertId();
	//update common js file
	file_get_contents("scripts/cron_generateNewLocations.php");
}

//got update net control form submit
if (!empty($_POST['updatenc'])) {

	$nc_qury = "";
	$nc_qury .= "nc_lead_id=:leadid,";
	$nc_qury .= "nc_parent_id=:parentid,";
	$nc_qury .= "nc_callsign=:callsign,";
	$nc_qury .= "nc_location=:location,";
	$nc_qury .= "nc_gps=:gps,";
	$nc_qury .= "nc_active=:active,";
	$nc_qury .= "nc_note=:note";
	$nc_qury .= " where nc_id=:ncid";

	foreach($_POST as $k => $v) {
		$_POST[$k] = (empty($v)) ? NULL:$v;
	}

	$qvars = array(":leadid"=>$_POST['nc_lead_id'],":parentid"=>$_POST['nc_parent_id'],":callsign"=>$_POST['nc_callsign'],":location"=>$_POST['nc_location'],":gps"=>$_POST['nc_gps'],":active"=>$_POST['nc_active'],":note"=>$_POST['nc_note'],":ncid"=>$_POST['ncid']);

	$q = $conn->prepare("update Net_Controls set ".$nc_qury);
	$q->execute($qvars);
	//update common js file
	file_get_contents("scripts/cron_generateNewLocations.php");
}

//got ncid so show control info
if ((!empty($_GET['ncid']) && $_GET['ncid']!='undefined') || !empty($ncid)) {
	$nc_leader = "";
	$ncid = (!empty($ncid)) ? $ncid:$_GET['ncid'];
	$q = $conn->prepare("select Net_Controls.*,i_tactical,i_name,l_tactical,l_name from Net_Controls left outer join Incidents on Incidents.i_id=Net_Controls.i_id left outer join Locations on Locations.nc_id=Net_Controls.nc_id where Net_Controls.nc_id=:ncid");
	$q->execute(array(':ncid'=>$ncid));
	$res = $q->fetchAll(PDO::FETCH_ASSOC);
	foreach($res as $r) {
		foreach($r as $key => $val) {
			$r[$key] = addslashes($val);
		}
		$thisnc = strtoupper($r['nc_callsign']).": ".$r['i_name'];
		$isactive = (!empty($r['nc_active'])) ? " checked":"";
		//get leader callsign and name
		$lq = $conn->prepare("select m_callsign,m_fname,m_lname from Members where m_id=:nclid limit 1");
		$lq->execute(array(':nclid'=>$r['nc_lead_id'])); 
		$lres = $lq->fetchAll(PDO::FETCH_ASSOC);
		foreach($lres as $lr) {
			$nc_leader = strtoupper($lr['m_callsign']).": ".$lr['m_fname']." ".$lr['m_lname'];
		}
		//get parent net control call sign
		$parentid = "";
		$pq = $conn->prepare("select * from Net_Controls where nc_id=:parentid limit 1");
		$pq->execute(array(':parentid'=>$r['nc_parent_id']));
		$pres = $pq->fetchAll(PDO::FETCH_ASSOC);
		foreach($pres as $pr) {
			$parentid = $pr['nc_callsign'];
		}
		$gpsico = (empty($r['nc_gps'])) ? "":" <a href='https://maps.google.com/?q=".$r['nc_gps']."' target='_blank' title='Click to view location on Google Maps'><img src='images/icon-google-maps.svg' alt='maps icon' border=0 width=14 align=absmiddle></a>";
		$a = "<form method=post>
<input type=hidden name=updatenc value=1>
<input type=hidden name=ncid value='".$ncid."'>
<table border=0 cellpadding=6 cellspacing=0>

<tr><td>Call Sign</td><td><input type=hidden name=nc_id value='".$r['nc_id']."'><input type=text class='netcontrol' size=10 name=nc_callsign value='".$r['nc_callsign']."'></td><td>Control Lead</td><td><input type=hidden name=nc_lead_id id=nc_lead_id value='".$r['nc_lead_id']."'><input type=text size=22 style='width:176px' class='people' name=nc_lead_name value='".$nc_leader."'></td></tr>
<tr><td>Parent Control</td><td><input type=hidden name=nc_parent_id value='".$r['nc_parent_id']."'><input type=text size=10 class='netcontrol' name=nc_parent_callsign value='".$parentid."'></td><td>Location</td><td><input type=text size=22 class='location' style='width:176px' name=nc_location value='".$r['nc_location']."'></td></tr>
<tr><td align=right>Active</td><td><input type=checkbox name=nc_active value=1".$isactive."></td><td>GPS".$gpsico."</td><td><input type=text name=nc_gps size=22  style='width:176px' value='".$r['nc_gps']."' placeholder='Copy & Paste from Map'><div class='tooltip' onclick='openMap()'><img src='images/icon-help.png' width=16 alt='GPS Help' style='cursor:pointer;margin:0 0 0 4px;' align=absmiddle><span class='tooltiptext'><ol style='margin-left:0px'><li>Click to Open Google Maps</li><li>Right-click Net Control Location</li><li>Click \"What's Here?\"</li><li>Copy GPS from Maps, then<br>close Maps window</li><li>Paste into this field</li></ol></span></div></td></tr>
<tr valign=top><td>Notes</td><td colspan=3><textarea name=nc_note style='width:400px;height:60px;'>".stripslashes($r['nc_note'])."</textarea></td></tr>";

	}

	$a .= "<tr><th colspan=4><input type=submit value='Update This Net Control Info'></th></tr>
</table>
</form>
</div>";


	$searchresults = $a;
}
?>
<!doctype html>
<html lang="en">
<head><title>ARES Net Control Management</title>

<?php
include "common_includes.php";
?>

<script type="text/javascript">
function showControl(ncid) {
//	location.href = "ARES_Net_Control_Manage.php?ncid="+ncid;
}
function openMap() {
	window.open("https://google.com/maps/","maps","width=500;height=500;");
}
function showSearch(vis) {
	var vis1 = (vis=="hidden") ? "block":"none";
	var vis2 = (vis=="hidden") ? "visible":"hidden";
	jQuery("#searchform").css("display",vis1).fadeIn(500);
	jQuery("#nc_lookup").focus();
	jQuery("#searchform").css("visibility",vis2);
}
function showAddNew(vis) {
	var vis = (vis=="hidden") ? "visible":"hidden";
	jQuery("#addnew").css("visibility",vis).fadeIn(900);
	jQuery("#callsign").focus();
}
function deleteMe(ncid,data,rid) {
	var datastr = "ncid="+ncid+"&data="+encodeURIComponent(data);
	jQuery.ajax({
		type: "POST",
		url: "ajax_delete_net_control.php",
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

<h2>ARES Net Control Management</h2>

<input type=text name=nc_lookup id=nc_lookup class='nclookup' placeholder="To look up a Net Control, Click here and Start Typing ..." style="font-weight:bold;width:500px;text-align:center;" onfocus="this.select()" value="<?=$thisnc?>">
<div id=results>
<?=$searchresults?>
</div>

<div onclick="showAddNew(document.getElementById('addnew').style.visibility)" style="padding:4px;font-size:13px;margin-top:16px;width:500px;text-align:center;background-color:lightgrey;cursor:pointer;">or Click here to Show the New Net Control Form</div><br>
<div id=addnew style="visibility:hidden;display:none;">
<form method=post>
<input type=hidden name=addnc value=1>
<input type=hidden name=ncid value='".$ncid."'>
<table border=0 cellpadding=6 cellspacing=0>
<tr><td>Call Sign</td><td><input type=hidden name=nc_id id=nc_id><input type=text class='netcontrol' size=10 name=nc_callsign value=''></td><td>Control Lead</td><td><input type=hidden name=nc_lead_id id=nc_lead_id><input type=text size=22 style="width:176px" class='people' name=nc_lead_name value=''></td></tr>
<tr><td>Parent Control</td><td><input type=hidden name=nc_parent_id id=nc_parent_id value=''><input type=text size=10 class='netcontrol' name=nc_parent_callsign value=''></td><td>Location</td><td><input type=text size=22 class='location' style='width:176px' name=nc_location value=''></td></tr>
<tr><td align=right>Active</td><td><input type=checkbox name=nc_active value=1 checked></td><td>GPS</td><td><input type=text name=nc_gps size=22 style="width:176px" value='' placeholder="Copy & Paste from Map"><div class="tooltip" onclick="openMap()"><img src="images/icon-help.png" width=16 alt="GPS Help" style="cursor:pointer;margin:0 0 0 4px;" align=absmiddle><span class="tooltiptext"><ol style="margin-left:0px"><li>Click to Open Google Maps</li><li>Right-click Net Control Location</li><li>Click "What's Here?"</li><li>Copy GPS from Maps, then<br>close Maps window</li><li>Paste into this field</li></ol></span></div></td></tr>
<tr valign=top><td>Notes</td><td colspan=3><textarea name=nc_note style='width:400px;height:60px;'></textarea></td></tr>
<tr><th colspan=4><input type=submit value="Save This New Net Control"></th></tr>
</table>
</form>
</div>
</center>

<script type="text/javascript">
//autocomplete
var netfld = new Array();
var locfld = new Array();
var pplfld = new Array();
jQuery(function() {

        jQuery(".netcontrol").autocomplete({
                source: netcontrols,
                focus: function(event, ui) {
                        event.preventDefault();
                        jQuery(this).val(ui.item.label);
                },
                select: function(event, ui) {
			event.preventDefault();
			jQuery(this).val(ui.item.label);
			if (this.name=='nc_callsign') {
				jQuery(this.form.nc_id).val(ui.item.ncid);
			}
			if (this.name=='nc_parent_name') {
				jQuery(this.form.nc_parent_id).val(ui.item.ncid);
			}
			netfld[this.id]=ui.item.value;
                }
        });

        jQuery(".nclookup").autocomplete({
                source: netcontrols,
                focus: function(event, ui) {
                        event.preventDefault();
                        jQuery(this).val(ui.item.label);
                },
                select: function(event, ui) {
                        event.preventDefault();
                        jQuery(this).val(ui.item.label);
			netfld[this.id]=ui.item.value;
			location.href = "ARES_Net_Control_Manage.php?admin=1&ncid="+ui.item.ncid;
                }
        });

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
                        jQuery(this.form.nc_lead_id).val(ui.item.mid);
                        pplfld[this.id]=ui.item.value;
                }
        });

});
</script>
</body>
</html>


