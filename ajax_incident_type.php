<?php
/* ###########################################
 * ajax_incident_type.php
 * Utility to manage entries in Incident_Types
 * ########################################### */
if ((empty($_POST['title']) || empty($_POST['data'])) && (empty($_POST['tid']) || !is_numeric($_POST['tid']))) { exit; }

include "db_conn.php";

$prestr = (!empty($_POST['tid']) && empty($_POST['val'])) ? "delete from Incident_Types where it_id=:tid limit 1":"insert into Incident_Types (it_title,it_data) values (:title,:data)";
$exearr = (!empty($_POST['tid']) && empty($_POST['val'])) ? array(':tid'=>$_POST['tid']):array(':title'=>$_POST['title'],':data'=>$_POST['data']);
//update
$prestr = (!empty($_POST['tid']) && !empty($_POST['val'])) ? "update Incident_Types set ".$_POST['data']."=:val where it_id=:tid limit 1":$prestr;
$exearr = (!empty($_POST['tid']) && !empty($_POST['val'])) ? array(':tid'=>$_POST['tid'],':val'=>$_POST['val']):$exearr;

$q = $conn->prepare($prestr);
$q->execute($exearr);

echo (!empty($_POST['tid']) && empty($_POST['val'])) ? "deleted":((empty($_POST['val'])) ? "inserted":"updated");
exit;
