<?php
/* ######################################
 * ARES_Operator_Status.php
 * Interface for managing Operator Status
 * ###################################### */
include "db_conn.php";
include "common_includes.php";

//get active operators, status, deploys-to
$GetMembersPS = $conn->prepare("select Members.*,Locations.l_gps,Locations.l_name,Locations.l_tactical,Operators.o_id,Operator_Shift.oshit_inout,Operator_Shift.oshit_timestamp,Net_Controls.nc_callsign,Status_Codes.* from Members left outer join Operators on Operators.m_id=Members.m_id left outer join Operator_Shift on Operator_Shift.o_id=Operators.o_id left outer join Net_Controls on Net_Controls.nc_id=Operators.nc_id join Status_Codes on Status_Codes.s_id=Members.m_status left outer join Locations on Locations.l_id=Operators.l_id where m_active=1 order by m_callsign");
$GetMembersPS->execute();

$mems = array();
while ($r=$GetMembersPS->fetch(PDO::FETCH_ASSOC)) {
	$mid = $r['m_id'];
	$mems[$mid] = array();
	$mems[$mid]['callsign'] = $r['m_callsign'];
	$mems[$mid]['fname'] = $r['m_fname'];
	$mems[$mid]['lname'] = $r['m_lname'];
	$mems[$mid]['access_level'] = $r['m_access_level'];
	$mems[$mid]['status'] = $r['m_status'];
	$mems[$mid]['status_code'] = $r['s_title'];
	$mems[$mid]['note'] = $r['m_note'];
	$chkdin = (!empty($r['o_id'])) ? 1:0;
	$mems[$mid]['loc_id'] = ($chkdin) ? $r['l_id']:0;
	$mems[$mid]['nc_id'] = ($chkdin) ? $r['nc_id']:0;
	$mems[$mid]['nc_callsign'] = ($chkdin) ? $r['nc_callsign']:0;
	$mems[$mid]['nc_gps'] = ($chkdin) ? $r['nc_gps']:0;
	$mems[$mid]['band'] = ($chkdin) ? $r['o_band']:0;
	$mems[$mid]['inout'] = ($chkdin) ? $r['oshit_inout']:0;
	$mems[$mid]['timestamp'] = ($chkdin) ? $r['oshit_timestamp']:0;
	//check if liaison, get contacts
	$mems[$mid]['liaison'] = 0;
	$GetLiaisonsPS = $conn->prepare("select Liaisons.li_id,Liaison_Contacts.* from Liaisons left outer join Liaison_Contacts on Liaison_Contacts.li_id=Liaisons.li_id where Liaisons.m_id=:mid order by lic_type");
	$GetLiaisonsPS->execute(array(":mid"=>$mid));
	//set liaison flag
	$isliaison = (count($GetLiaisonsPS) > 0) ? 1:0;
	if ($isliaison) {
		//store as json
		$mems[$mid]['liaison'] = array();
		while($lr=$GetLiaisonsPS->fetch(PDO::FETCH_ASSOC)) {
			$mems[$mid]['liaison'][] = '{"type":"'.$lr['lic_type'].'","carrier":"'.$lr['lic_carrier'].'","data":"'.$lr['lic_data'].'"}';
		}
	}
	//get contacts
	$mems[$mid]['contacts'] = array();
	$GetMemberContactsPS = $conn->prepare("select * from Member_Contacts where m_id=:mid order by mc_type");
	$GetMemberContactsPS->execute(array(":mid"=>$mid));
	//store as json
	while($mr=$GetMemberContactsPS->fetch(PDO::FETCH_ASSOC)) {
		$mems[$mid]['contacts'][] = '{"type":"'.$mr['mc_type'].'","carrier":"'.$mr['mc_carrier'].'","data":"'.$mr['mc_data'].'"}';
	}
}

echo "<pre>";print_r($mems);exit;
