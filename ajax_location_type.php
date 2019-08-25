<?php
/* ###########################################
 * ajax_location_type.php
 * Utility to manage entries in Location_Types
 * ########################################### */
if (empty($_POST['title']) && (empty($_POST['tid']) || !is_numeric($_POST['tid']))) { exit; }

include "db_conn.php";

$prestr = (!empty($_POST['tid']) && empty($_POST['val'])) ? "delete from Location_Types where lt_id=:tid limit 1":"insert into Location_Types (lt_title) values (:title)";
$exearr = (!empty($_POST['tid']) && empty($_POST['val'])) ? array(':tid'=>$_POST['tid']):array(':title'=>$_POST['title']);
//update
$prestr = (!empty($_POST['tid']) && !empty($_POST['val'])) ? "update Location_Types set lt_title=:val where lt_id=:tid limit 1":$prestr;
$exearr = (!empty($_POST['tid']) && !empty($_POST['val'])) ? array(':tid'=>$_POST['tid'],':val'=>$_POST['val']):$exearr;

$q = $conn->prepare($prestr);
$q->execute($exearr);

echo (!empty($_POST['tid']) && empty($_POST['val'])) ? "deleted":((empty($_POST['val'])) ? "inserted":"updated");
exit;
