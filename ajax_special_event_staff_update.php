<?php
/* ##########################################
 * ajax_special_event_staff_update.php
 * Utility to update Special Event Staff data
 * ########################################## */
if (empty($_POST['fld']) || empty($_POST['esid']) || !is_numeric($_POST['esid'])) { exit; }
require "db_conn.php";

if ($_POST['fld']!=="delete") {
	$q = $conn->prepare("update Event_Staff set ".$_POST['fld']."=:val where es_id=:esid limit 1");
	$q->execute(array(":val"=>urldecode($_POST['val']),":esid"=>$_POST['esid']));
	$msg = "updated";
}
else {
	$q = $conn->prepare("delete from Event_Staff where es_id=:esid limit 1");
	$q->execute(array(":esid"=>$_POST['esid']));
	$msg = "deleted";
}
file_get_contents("https://km6wka.net/ares/scripts/generateSpecialEvents.php?eventid=".$_POST['evid']);
echo $msg;

