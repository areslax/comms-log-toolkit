<?php
/* ##################################################
 * ajax_admin_alert.php
 * Finds administrative alert to push to all stations
 * ################################################## */
ini_set('display_errors','1');
include "db_conn.php";

//check for current message
$q = $conn->prepare("select * from Admin_Alerts where aa_active='1' order by aa_id desc limit 1");
$q->execute();
if ($q->rowCount() > 0) {
	$r = $q->fetch(PDO::FETCH_ASSOC);
	echo $r['aa_alert_text'];
}
else { 
	echo '';
}

