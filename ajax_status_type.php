<?php
/* ###########################################
 * ajax_status_type.php
 * Utility to manage entries in Status_Types
 * ########################################### */
if (empty($_POST['title']) && (empty($_POST['tid']) || !is_numeric($_POST['tid']))) { exit; }

include "db_conn.php";

$prestr = (!empty($_POST['tid']) && empty($_POST['val'])) ? "delete from Status_Codes where s_id=:tid limit 1":"insert into Status_Codes (s_title,s_data) values (:title,:data)";
$exearr = (!empty($_POST['tid']) && empty($_POST['val'])) ? array(':tid'=>$_POST['tid']):array(':title'=>$_POST['title'],':data'=>$_POST['data']);
//update
$prestr = (!empty($_POST['tid']) && !empty($_POST['val'])) ? "update Status_Codes set s_title=:val where s_id=:tid limit 1":$prestr;
$exearr = (!empty($_POST['tid']) && !empty($_POST['val'])) ? array(':tid'=>$_POST['tid'],':val'=>$_POST['val']):$exearr;

$q = $conn->prepare($prestr);
$q->execute($exearr);

echo (!empty($_POST['tid']) && empty($_POST['val'])) ? "deleted":((empty($_POST['val'])) ? "inserted":"updated");
exit;
