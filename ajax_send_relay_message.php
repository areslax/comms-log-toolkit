<?php
/* ############################################
 * ajax_send_relay_message.php
 * Utility to send relay message from Comms_Log
 * ############################################ */
if (empty($_POST['from']) || empty($_POST['to']) || empty($_POST['msg']) || empty($_POST['via'])) { echo "doh:";print_r($_POST);exit; }

ini_set('display_errors','1');

require "db_conn.php";

$mfrom = urldecode($_POST['from']);
$mto = urldecode($_POST['to']);
$msg = stripslashes(strip_tags(urldecode($_POST['msg'])));
$via = explode(":",urldecode($_POST['via']));
//$mfrom = 'W1EH Roozy Moabery';
//$mto = 'KM6WKA James Butler';
//$msg = 'test message';
//$via = array('sms','email','other');

$farr = explode(" ",$mfrom);//get from pieces
$tarr = explode(" ",$mto);//get to pieces

//get from user email address
$fq = $conn->prepare("select mc_data from Members left outer join Member_Contacts on Member_Contacts.m_id=Members.m_id where mc_type='1' and m_callsign=:mcall and m_fname=:mfname and m_lname=:mlname limit 1");
$fq->execute(array(":mcall"=>$farr[0],":mfname"=>$farr[1],":mlname"=>$farr[2]));
$fr = $fq->fetch(PDO::FETCH_ASSOC);

//sending header is problematic on AWS EC2 instance
//working on a fix
//$hedr = "From: ".$fr['mc_data'];

//get to user, only sms and email
$tq = $conn->prepare("select mc_type,mc_data,mc_carrier,carrier_ext from Members left outer join Member_Contacts on Member_Contacts.m_id=Members.m_id left outer join Mobile_Carriers on Mobile_Carriers.carrier_id=Member_Contacts.mc_carrier where (mc_type='1' or mc_type='4') and m_callsign=:mcall and m_fname=:mfname and m_lname=:mlname");
$tq->execute(array(":mcall"=>$tarr[0],":mfname"=>$tarr[1],":mlname"=>$tarr[2]));
$tr = $tq->fetchAll(PDO::FETCH_ASSOC);
foreach($tr as $r) {
	//got email address, and sendvia includes email
	if ($r['mc_type']=='1' && in_array('email',$via)) {
		//build email to addr
		$sendto = $r['mc_data'];
	}
	//got cell+carrier, and sendvia includes sms
	if ($r['mc_type']=='4' && !empty($r['mc_carrier']) && in_array('sms',$via)) {
		//build sms to addr
		$delim = array("-","_"," ",".","(",")");
		$sendto = str_replace($delim,"",$r['mc_data']).$r['carrier_ext'];
	}
	//send message
	$rmsg = mail($sendto,"ARES Relay from ".$mfrom,$msg);
}

echo "relayed";

