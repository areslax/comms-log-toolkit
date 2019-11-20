<?php
/* ##########################################
 * ajax_save_sms_queue_data.php
 * Utility to save scheduled SMS message data
 * ########################################## */
if(empty($_POST['id']) || !is_numeric($_POST['id'])) { exit; }

include "db_conn.php";

//if deleting
if (empty($_POST['fld']) && empty($_POST['data'])) {
	$dq = $conn->prepare("delete from SMS_Queue where smsq_id=:id limit 1");
	$dq->execute(array(":id"=>$_POST['id']));
	echo "deleted";
	exit;
}

$fld = htmlspecialchars($_POST['fld'],ENT_QUOTES);

//do update
if (!empty($_POST['id'])) {
	$q = $conn->prepare("update SMS_Queue set ".$fld."=:val where smsq_id=:id limit 1");
	$q->execute(array(":val"=>$_POST['data'],":id"=>$_POST['id']));
	echo "updated";
}
else { echo "Boom shakalaka"; }

exit;
