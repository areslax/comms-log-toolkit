<?php
/* #################################
 * ajax_delete_sms_group.php
 * Utility to delete from SMS_Groups
 * ################################# */
if (empty($_POST['smsgid']) || !is_numeric($_POST['smsgid'])) { exit; }
include "db_conn.php";

$q = $conn->prepare("delete from SMS_Groups where smsg_id=:smsgid limit 1");
$q->execute(array(':smsgid'=>$_POST['smsgid']));

exit;
