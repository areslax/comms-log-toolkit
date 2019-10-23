<?php
/* #############################
 * ajax_set_operator_status.php
 * Utility to set Member status
 * Insert Operators if no exist
 * Update Operators if got exist
 * ############################# */
if (empty($_POST['mid']) || !is_numeric($_POST['mid'])) { exit; }
include "db_conn.php";

$uq = $conn->prepare("update Members set m_status=:status where m_id=:mid limit 1");
$uq->execute(array(":status"=>$_POST['status'],":mid"=>$_POST['mid']));

//check for existing in Operators
//TODO: add i_id, nc_id, o_band
$oid = NULL;
$cq = $conn->prepare("select o_id from Operators where m_id=:mid and l_id=:lid order by o_id desc limit 1");
$cq->execute(array(":mid"=>$_POST['mid'],":lid"=>$_POST['lid']));
if ($cq->columnCount()>0) {
	$cr = $cq->fetch(PDO::FETCH_ASSOC);
	$oid = $cr['o_id'];
}
if (empty($oid) && $_POST['status']>1) {
	//no existing and checking in
	$q = $conn->prepare("insert into Operators (m_id,l_id,o_id,o_active) values (:mid,:lid,:oid,1)");
	$oid = $conn->lastInsertId();
}
else if (!empty($oid) && $_POST['status']>1) {
	//got existing and checking in
	//won't affect existing whose status is moving up
	$q = $conn->prepare("update Operators set o_active=1 where m_id=:mid and l_id=:lid and o_id=:oid limit 1");
}
else {
	//got existing and checking out
	$q = $conn->prepare("update Operators set o_active=0 where m_id=:mid and l_id=:lid and o_id=:oid limit 1");
}
//oid = NULL if no exist || o_id if got exist
$q->execute(array(":mid"=>$_POST['mid'],":lid"=>$_POST['lid'],":oid"=>$oid));

echo "updated: ".$oid;
