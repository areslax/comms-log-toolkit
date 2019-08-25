<?php
/* ####################################################
 * ajax_delete_liaison.php
 * Utility to delete from Liaisons and Liaison_Contacts
 * #################################################### */
if (empty($_POST['liid']) || !is_numeric($_POST['liid'])) { exit; }
include "db_conn.php";

$q = $conn->prepare("delete from Liaisons where li_id=:liid limit 1");
$q->execute(array(':liid'=>$_POST['liid']));

$q = $conn->prepare("delete from Liaison_Contacts where li_id=:liid limit 1");
$q->execute(array(':liid'=>$_POST['liid']));

