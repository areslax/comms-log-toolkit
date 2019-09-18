<?php
/* ################################
 * ajax_save_alert_data.php
 * Utility to save Admin Alert data
 * ################################ */
if(empty($_POST['id']) || !is_numeric($_POST['id'])) { exit; }

include "db_conn.php";

if ($_POST['fld']=='aa_active') {
	$_POST['data'] = ($_POST['data']=='true') ? 1:0;
}
$fld = htmlspecialchars($_POST['fld'],ENT_QUOTES);

//do update
if ($_POST['id']!=='0') {
	$q = $conn->prepare("update Admin_Alerts set ".$fld."=:val where aa_id=:id limit 1");
	$q->execute(array(":val"=>$_POST['data'],":id"=>$_POST['id']));
	echo "updated";
}
else { echo "Boom shakalaka"; }
