<?php
/* #######################################
 * ajax_delete_member_contact.php
 * Utility to remove Member_Contacts entry
 * ####################################### */
if (empty($_POST['mid']) || !is_numeric($_POST['mid'])) { exit; }
include "db_conn.php";

$q = $conn->prepare("delete from Member_Contacts where m_id=:mid and mc_data=:data limit 1");
$q->execute(array(':mid'=>$_POST['mid'],':data'=>urldecode($_POST['data'])));

echo "deleted";

