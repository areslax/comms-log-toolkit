<?php
/* #####################################
 * ajax_delete_incident_type.php
 * Utility to delete from Incident_Types
 * ##################################### */
if (empty($_POST['tid']) || !is_numeric($_POST['tid'])) { exit; }
include "db_conn.php";

$q = $conn->prepare("delete from Incident_Types where it_id=:tid limit 1");
$q->execute(array(':tid'=>$_POST['tid']));

echo "delteed";
exit;
