<?php
/* ########################################
 * ajax_set_operator.php
 * Add operator to table and set PHP cookie
 * ######################################## */
include_once "db_conn.php";

//cs="+callsign+"&ncid="+document.getElementById("stationid").value+"&iid="+document.getElementById("incidentid").value+"&lid="+document.getElementById("loc"+id).value

#print_r($_POST);exit;

//get Member id
$q = $conn->prepare("select m_id from Members where m_callsign=:cs limit 1");
$q->execute(array(":cs"=>$_POST['cs']));
$r = $q->fetch(PDO::FETCH_ASSOC);
$mid = $r['m_id'];
//get Location id
$q = $conn->prepare("select l_id from Locations where l_tactical=:lid limit 1");
$q->execute(array(":lid"=>$_POST['lid']));
$r = $q->fetch(PDO::FETCH_ASSOC);
$lid = $r['l_id'];
//get Net Control id
$q = $conn->prepare("select nc_id from Net_Controls where nc_callsign=:ncid and i_id=:iid limit 1");
$q->execute(array(":ncid"=>$_POST['ncid'],":iid"=>$_POST['iid']));
$r = $q->fetch(PDO::FETCH_ASSOC);
$ncid = (empty($r['nc_id'])) ? $_POST['ncid']:$r['nc_id'];

//check for matching operator record
#$q = $conn->prepare("select o_id from Operators where m_id=:mid and i_id=:iid and l_id=:lid and nc_id=:ncid limit 1");
#$q->execute(array(":mid"=>$mid,":iid"=>$_POST['iid'],":lid"=>$lid,":ncid"=>$ncid));
$q = $conn->prepare("select o_id from Operators where m_id=:mid and i_id=:iid limit 1");
$q->execute(array(":mid"=>$mid,":iid"=>$_POST['iid']));
$r = $q->fetch(PDO::FETCH_ASSOC);
if ($r) {
	//got match, so pull o_id
	$oid = $r['o_id'];
}
else {
	//new, so insert
	$iq = $conn->prepare("insert into Operators (i_id,m_id,l_id,nc_id) values (:iid,:mid,:lid,:ncid)");
	$iq->execute(array(":iid"=>$_POST['iid'],":mid"=>$mid,":lid"=>$lid,":ncid"=>$ncid));
	$oid = $conn->lastInsertId();
}
//set PHP cookies for report checking
setcookie("oid",$oid);
setcookie("incid",$_POST['iid']);
setcookie("lid",$lid);
setcookie("ncid",$ncid);

print_r($_COOKIE);
