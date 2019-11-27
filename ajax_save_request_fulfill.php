<?php
/* ###############################################
 * ajax_save_request_fulfill.php
 * Utility to save/update Resource_Request_Fulfill
 * ############################################### */
if (empty($_POST['reqid']) || !is_numeric($_POST['reqid']) || empty($_POST['rid']) || !is_numeric($_POST['rid'])) { exit; }
require "db_conn.php";

$q = $conn->prepare("update Resource_Request_Fulfill set ".$_POST['fld']."=:val where req_id=:reqid and req_item_num=:rid limit 1");
$q->execute(array(":val"=>$_POST['val'],":reqid"=>$_POST['reqid'],":rid"=>$_POST['rid']));

echo "fulfillment updated";

