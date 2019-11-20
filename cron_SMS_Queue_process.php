<?php
require "db_conn.php";
$chk = $conn->query("select smsq_id,smsq_message,smsg_id from SMS_Queue where smsq_sent_ts is NULL and smsq_send_ts<='".date("YmdHi")."'");
$chk->execute();
$res = $chk->fetchAll(PDO::FETCH_ASSOC);
if (sizeOf($res)>0) {
	foreach($res as $r) {
		$ms = $conn->prepare("select m_ids from SMS_Groups where smsg_id=:smsgid limit 1");
		$ms->execute(array(':smsgid'=>$r['smsg_id']));
		$mids = $ms->fetch(PDO::FETCH_ASSOC);
		$mids = json_decode($mids['m_ids']);
		//get member cell+carrier and send message
		$msg = @strip_tags($r['smsq_message']);
		$msg = @stripslashes($msg);
		$hed = "From: ares@areslax.org\r\n"."BCC: stupidscript@gmail.com";
		$delim = array("-","_"," ",".","(",")");
		foreach($mids as $mid) {
			$m = $conn->prepare("select mc_data,mc_carrier,carrier_ext from Member_Contacts left outer join Mobile_Carriers on Mobile_Carriers.carrier_id=Member_Contacts.mc_carrier where m_id=".$mid." and mc_carrier is not NULL and mc_type='4' limit 1");
			$m->execute();
			$to = $m->fetchAll(PDO::FETCH_ASSOC);
			$addr = str_replace($delim,"",$to[0]['mc_data']).$to[0]['carrier_ext'];
			mail($addr,"ARES SMS Message",$msg,$hed);
		}
		$uq = $conn->query("update SMS_Queue set smsq_sent_ts='".date("YmdHi")."' where smsq_id='".$r['smsq_id']."' limit 1");
		$uq->execute();
	}
}
exit;
?>
