<?php
/* ##########################################################
 * ajax_get_relay_opts.php
 * Utility to see what relay options are available for member
 * ########################################################## */
if (empty($_POST['callsign'])) { exit; }
include "db_conn.php";

$cs = $_POST['callsign'];

//get member contact options
$q = $conn->prepare("select Members.m_id,Member_Contacts.mc_type from Members left outer join Member_Contacts on Member_Contacts.m_id=Members.m_id where m_callsign=:callsign");
$q->execute(array(':callsign'=>$cs));
$r1 = $q->fetchAll(PDO::FETCH_ASSOC);
$res = "";
foreach($r1 as $r) {
	switch($r['mc_type']) {
		case 4:
		$res .= "sms.";
		break;
		case 1:
		$res .= "email.";
		break;
	}
}
echo $res;
exit;
