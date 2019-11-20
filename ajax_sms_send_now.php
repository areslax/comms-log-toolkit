<?php
/* ##############################################
 * ajax_sms_send_now.php
 * Utility to send queued SMS message immediately
 * ############################################## */
//got message to group
if (!empty($_POST['qid']) && is_numeric($_POST['qid'])) {
	require "db_conn.php";
	//send immediately
	$ms = $conn->prepare("select SMS_Queue.*,m_ids from SMS_Queue join SMS_Groups on SMS_Groups.smsg_id=SMS_Queue.smsg_id where smsq_id=:qid limit 1");
	$ms->execute(array(':qid'=>$_POST['qid']));
	$mids = $ms->fetch(PDO::FETCH_ASSOC);
	$mids = json_decode($mids['m_ids']);
	//get member cell+carrier and send message
	$msg = @strip_tags($mids['smsq_message']);
	$msg = @stripslashes($msg);
	$hed = "From: ares@areslax.org";
	$delim = array("-","_"," ",".","(",")");
	foreach($mids as $mid) {
		$m = $conn->prepare("select mc_data,mc_carrier,carrier_ext from Member_Contacts left outer join Mobile_Carriers on Mobile_Carriers.carrier_id=Member_Contacts.mc_carrier where m_id=".$mid." and mc_carrier is not NULL and mc_type='4' limit 1");
		$m->execute();
		$to = $m->fetchAll(PDO::FETCH_ASSOC);
		$addr = str_replace($delim,"",$to[0]['mc_data']).$to[0]['carrier_ext'];
		mail($addr,"ARES SMS Message",$msg,$hed);
	}
	$uq = $conn->prepare("update SMS_Queue set smsq_sent_ts='".date("YmdHi")."' where smsq_id=:qid limit 1");
	$uq->execute(array(":qid"=>$_POST['qid']));
	echo "send ok";
}

