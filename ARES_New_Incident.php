<?php
/* ###############################
 * ARES_New_Incident.php
 * Modal for adding a new incident
 * ############################### */
//if (empty($_GET['admin'])) { header("Location: https://www.km6wka.net/ares");exit; }
require "db_conn.php";
$thisloc = "";
//got new incident form submit
if (!empty($_POST['addincident'])) {
	$togo = array("(",")"," ","-","_",".","/");
	$q = $conn->prepare("insert into Incidents (i_type,i_tactical,i_status,i_name,i_lead_id,i_gps) values (:type,:tactical,1,:name,:leadid,:gps)");
	$q->execute(array(':type'=>$_POST['i_type'],':tactical'=>$_POST['i_tactical'],':name'=>$_POST['i_name'],':leadid'=>$_POST['i_lead_id_new'],':gps'=>$_POST['i_gps']));
	$iid = $conn->lastInsertId();
	//update common js file
	file_get_contents("scripts/cron_generateNewLocations.php");
}
//get incident types
$itypes = "";
$itarry = array();
$iq = $conn->query("select * from Incident_Types order by it_id");
$ires = $iq->fetchAll(PDO::FETCH_ASSOC);
foreach($ires as $ir) {
	$itypes .= "<option value=".$ir['it_id'].$sel.">".$ir['it_data'].": ".$ir['it_title']."</option>";
	$itarry[$ir['it_id']]['title'] = $ir['it_title'];
	$itarry[$ir['it_id']]['data'] = $ir['it_data'];
}
?>
<!doctype html>
<html>
<head><title>New Incident</title>

<?php
include "common_includes.php";
?>
<style type="text/css">
#table_memstatus TD { font-size: 11px; }
</style>

<script type="text/javascript">
function openMap() {
	window.open("https://google.com/maps?q="+encodeURIComponent(gps),"width=500;height=500;");
}
</script>

</head>
<body>
<center>

<h2>New Incident</h2>

<form method=post>
<input type=hidden name=addincident value=1>
<table border=0 cellpadding=6 cellspacing=0>
<tr><td>Name</td><td colspan=3><input type=text id=i_name name=i_name class="incident" style="width:380px;text-align:left;" placeholder="i.e. Mojave Earthquake"></td></tr>
<tr><td>Type</td><td><select name=i_type style="width:100px"><option value=0></option><?=$itypes?></select></td><td>GPS</td><td><input type=text name=i_gps id=i_gps size=21 onclick="openMap();this.select();"></td></tr>
<tr><td>Tac Call</td><td><input type=text size=12 id=i_tactical name=i_tactical></td><td>Incident Lead</td><td><input type=hidden name=i_lead_id_new id=i_lead_id_new><input type=text class='people' size=21 name=i_lead></td></tr>
<tr><th colspan=4><input type=submit value="Save This New Incident"></th></tr>
</table>
</form>

</center>

<iframe id=i_modal style="position:absolute;top:10px;left:30px;width:200px;background-color:white;border:solid 1px red;border-radius:2px;display:none;"></iframe>

<script type="text/javascript">
//autocomplete
var incfld = new Array();
var pplfld = new Array();
jQuery(function() {
	jQuery(".people").autocomplete({
		source: people,
		focus: function(event, ui) {
			event.preventDefault();
			jQuery(this).val(ui.item.label);
		},
		select: function(event, ui) {
			event.preventDefault();
			jQuery(this).val(ui.item.label);
			jQuery("#i_lead_id").val(ui.item.mid);
			jQuery("#i_lead_id_new").val(ui.item.mid);
			jQuery("#i_lead").val(ui.item.value);
		}
	});

});
</script>
</body>
</html>
