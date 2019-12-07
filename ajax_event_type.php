<?php
/* #####################################
 * ajax_event_type.php
 * Utility to manage Special Event Types
 * ##################################### */
if (empty($_POST['do'])) { exit; }

require "db_conn.php";

switch($_POST['do']) {
	case "add":
	$q = $conn->prepare("insert into Events_Special_Types (est_name) values (:nam)");
	$q->execute(array(":nam"=>$_POST['name']));
	break;
	case "update":
	$q = $conn->prepare("update Events_Special_Types set est_name=:nam where est_id=:tid limit 1");
	$q->execute(array(":nam"=>$_POST['val'],":tid"=>$_POST['tid']));
	break;
	case "delete":
	$q = $conn->prepare("delete from Events_Special_Types where est_id=:tid limit 1");
	$q->execute(array(":tid"=>$_POST['tid']));
	break;
}

echo $_POST['do']." event type complete";

