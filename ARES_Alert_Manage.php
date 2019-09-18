<?php
/* #######################################
 * ARES_Alert_Manage.php
 * Managing alerts to push to all stations
 * ####################################### */
include "db_conn.php";
include "common_includes.php";

//got new alert submit
if (!empty($_POST['aa_alert_text'])) {
	$actv = (!empty($_POST['aa_active'])) ? 1:0;
	$q = $conn->prepare("insert into Admin_Alerts(aa_alert_text,aa_active) values (:val,:act)");
	$q->execute(array(":val"=>$_POST['aa_alert_text'],":act"=>$actv));
	//redirect to GET request, to prevent addt'l POST submissions
	header("Location: ARES_Alert_Manage.php");
	exit;
}

//get all alerts
$q = $conn->query("select * from Admin_Alerts order by aa_id desc");
$q->execute();
$out = "";
while($r=$q->fetch(PDO::FETCH_ASSOC)) {
	$chkd = ($r['aa_active']=='1') ? " checked":"";
	$out .= "<tr><th>".$r['aa_id']."</th><th><input type=text name=text value='".str_replace("'","\'",stripslashes($r['aa_alert_text']))."' onchange=\"saveData('".$r['aa_id']."','aa_alert_text',this.value)\" style='width:520px'></th><th><input type=checkbox name=active".$chkd." value=1 onchange=\"chkbxs('".$r['aa_id']."',this,this.checked);saveData('".$r['aa_id']."','aa_active',this.checked)\"></th></tr>";
}
?>
<!doctype html>
<html lang="en">
<head><title>ARES Alert Management</title>
<script type="text/javascript">
function chkbxs(fid,fld,chkd) {
	var bxs = jQuery("input");
	for(i=0;i<bxs.length;i++) {
		bxs[i].checked = (bxs[i].type=="checkbox") ? 0:1;
	}
	fld.checked = chkd;
	saveData(fid,fld,chkd);
}
function saveData(id,fld,data) {
	var datastr = "id="+id+"&fld="+fld+"&data="+data;
	jQuery.ajax({
		type: "POST",
		url: "ajax_save_alert_data.php",
		data: datastr,
		success: function(a,b,c){
//			console.log(a);
		}
	});
}
</script>
</head>
<body>

<center>
<h2>Manage ARES Admin Alerts<br><span style="font-size:12px;font-weight:normal;">Check the "Active" box to show alert on all Stations<br>UN-check the "Active" box to stop displaying the alert<br>Only one alert can be "Active" at any time</span></h2>
<table border=1 cellpadding=6 cellspacing=0 width=50%>
<tr><th>ID</th><th>ALERT TEXT</th><th>ACTIVE</th></tr>
<tr><th><form method=post style="display:inline" title="Click to add this alert"><input type=submit value="+"></th><th><input type=text name=aa_alert_text style='width:520px'></th><th><input type=checkbox name=aa_active value=1 onclick="chkbxs(0,this.checked)"></form></th></tr>
<?=$out?>
</table>
</center>

</body>
</html>
