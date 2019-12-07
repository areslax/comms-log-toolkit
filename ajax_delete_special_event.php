<?php
/* ###############################
 * ajax_delete_special_event.php
 * Utility to delete Special Event
 * ############################### */
if (empty($_POST['evid']) || !is_numeric($_POST['evid'])) { exit; }

require "db_conn.php";

$evid = $_POST['evid'];

//delete event
$q = $conn->prepare("delete from Events_Special where ev_id=:evid limit 1");
$q->execute(array(":evid"=>$evid));

//delete event locations
$q = $conn->prepare("delete from Event_Locations where ev_id=:evid");
$q->execute(array(":evid"=>$evid));

//delete event staff
$q = $conn->prepare("delete from Event_Staff where ev_id=:evid");
$q->execute(array(":evid"=>$evid));

echo "deleted event, locations and staff";

