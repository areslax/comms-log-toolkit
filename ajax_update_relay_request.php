<?php
/* ##############################################
 * ajax_update_relay_request.php
 * Utility to update Relay_Request reply received
 * ############################################## */
if (empty($_POST['rrid']) || !is_numeric($_POST['rrid'])) { exit; }

require "db_conn.php";

$rqtmstmp = "not found";

//gather sent timestamp for grab_report
$sql1 = "select rq_id,rq_timestamp from Relay_Requests where rq_data like '%msgfrom".$_POST['rrid']."%' order by rq_id desc limit 1";
while($gr = $conn->query($sql1)) {
	$rqid = $gr['rq_id'];
	$rqtmstmp = $gr['rq_timestamp'];
	$sql2 = "update Relay_Requests set rq_status=1,rq_replied='".date("YmdHi")."' where rq_id='".$rqid."' limit 1";
	$uq = $conn->query($sql2);
}

echo $rqtmstmp;
exit;
