<?php
/* ########################################
 * ajax_delete_liaison_contact.php
 * Utility to remove Liaison_Contacts entry
 * ######################################## */
if (empty($_POST['liid']) || !is_numeric($_POST['liid'])) { exit; }
include "db_conn.php";

$q = $conn->prepare("delete from Liaison_Contacts where li_id=:liid and lic_data=:data limit 1");
$q->execute(array(':liid'=>$_POST['liid'],':data'=>urldecode($_POST['data'])));

echo "deleted";

