<?php
/* ###############################################
 * ajax_save_request_fulfill.php
 * Utility to save/update Resource_Request_Fulfill
 * ############################################### */
if (empty($_POST['reqid']) || !is_numeric($_POST['reqid']) || empty($_POST['rid']) || !is_numeric($_POST['rid'])) { exit; }
require "db_conn.php";
if ($_POST['fld']!='req_cancel' && $_POST['fld']!='req_complete') {
	//update fulfillment table
	$q = $conn->prepare("update Resource_Request_Fulfill set ".$_POST['fld']."=:val where req_id=:reqid and item_num=:rid limit 1");
	$q->execute(array(":val"=>$_POST['val'],":reqid"=>$_POST['reqid'],":rid"=>$_POST['rid']));
	$msg = "updated";
}
else {
	if ($_POST['fld']=='req_complete') {
		//force mark req complete
		$q = $conn->prepare("update Resource_Requests set req_status=1 where req_id=:reqid limit 1");
		$q->execute(array(":reqid"=>$_POST['reqid']));
		$msg = "reqcomplete";
	}
	else {
		//cancel entire request
		$q = $conn->prepare("update Resource_Requests set req_status=2 where req_id=:reqid limit 1");
		$q->execute(array(":reqid"=>$_POST['reqid']));
		$msg = "reqcancelled";
	}
}
//check if complete
if ($_POST['fld']=='ful_filled' || $_POST['fld']=='ful_complete') {
	//update item status
	if ($_POST['fld']=='ful_complete') {
		//force mark complete
		$u = $conn->prepare("update Resource_Request_Items set item_status=2 where req_id=:reqid and item_num=:rid limit 1");
		$u->execute(array(":reqid"=>$_POST['reqid'],":rid"=>$_POST['rid']));
		$msg = "complete";
	}
	else {
		$q = $conn->prepare("select qty_requested from Resource_Request_Items where req_id=:reqid and item_num=:rid limit 1");
		$q->execute(array(":reqid"=>$_POST['reqid'],":rid"=>$_POST['rid']));
		$qr = $q->fetch(PDO::FETCH_ASSOC);
		$f = $conn->prepare("select ful_approved,ful_filled from Resource_Request_Fulfill where req_id=:reqid and item_num=:rid limit 1");
		$f->execute(array(":reqid"=>$_POST['reqid'],":rid"=>$_POST['rid']));
		$fr = $f->fetch(PDO::FETCH_ASSOC);
		if ($qr['qty_requested']==$_POST['val'] || $fr['ful_approved']==$_POST['val'] || $fr['ful_filled']==$qr['qty_requested']) {
			//mark complete
			$u = $conn->prepare("update Resource_Request_Items set item_status=2 where req_id=:reqid and item_num=:rid limit 1");
			$u->execute(array(":reqid"=>$_POST['reqid'],":rid"=>$_POST['rid']));
			$msg = "complete";
		}
		else {
			//mark partial filled
			$u = $conn->prepare("update Resource_Request_Items set item_status=1 where req_id=:reqid and item_num=:rid limit 1");
			$u->execute(array(":reqid"=>$_POST['reqid'],":rid"=>$_POST['rid']));
			$msg = "partial";
		}
	}
}
//check if item cancelled
if ($_POST['fld']=='ful_cancel') {
	//force mark cancelled
	$u = $conn->prepare("update Resource_Request_Items set item_status=3 where req_id=:reqid and item_num=:rid limit 1");
	$u->execute(array(":reqid"=>$_POST['reqid'],":rid"=>$_POST['rid']));
	$msg = "cancelled";
}
//check if request complete
if ($msg!="reqcomplete" && $msg!="reqcancelled") {
	$q = $conn->prepare("select item_status from Resource_Request_Items where req_id=:reqid");
	$q->execute(array(":reqid"=>$_POST['reqid']));
	$done = 1;
	while($qi=$q->fetch(PDO::FETCH_ASSOC)) {
		if ($qi['item_status']<3) { $done=0; }
	}
	if ($done) {
		$q = $conn->prepare("update Resource_Requests set req_status=1 where req_id=:reqid limit 1");
		$q->execute(array(":reqid"=>$_POST['reqid']));
		$msg = "reqcomplete";
	}
}
echo $msg;

