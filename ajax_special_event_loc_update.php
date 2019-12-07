<?php
/* #############################################
 * ajax_special_event_loc_update.php
 * Utility to update Special Event Location data
 * ############################################# */
if (empty($_POST['fld']) || empty($_POST['lid']) || !is_numeric($_POST['lid'])) { exit; }

require "db_conn.php";

if ($_POST['fld']!="delete") {
	$q = $conn->prepare("update Event_Locations set ".$_POST['fld']."=:val where el_id=:lid limit 1");
	$q->execute(array(":val"=>urldecode($_POST['val']),":lid"=>$_POST['lid']));
	$msg = "updated";
}
else {
	$q = $conn->prepare("delete from Event_Locations where el_id=:lid limit 1");
	$q->execute(array(":lid"=>$_POST['lid']));
	$msg = "deleted";
}
file_get_contents("https://km6wka.net/ares/scripts/generateSpecialEvents.php?eventid=".$_POST['evid']);
echo $msg;

