<?php
/* ########################
 * ajax_set_net_control.php
 * Sets ncid PHP cookie
 * ######################## */
if (empty($_POST['sid'])) { exit; }

require "/var/www/html/ares/db_conn.php";

//sid,gps,iid
$q = $conn->prepare("insert into Net_Controls (nc_id,i_id,nc_callsign,nc_gps,nc_active) values (NULL,:iid,:sid,:gps,1)");
$q->execute(array(":iid"=>$_POST['iid'],":sid"=>$_POST['sid'],":gps"=>$_POST['gps']));

$ncid = $conn->lastInsertId();

setcookie('incid',$_POST['iid']);
setcookie('ncid',$ncid);
setcookie('staid',$_POST['sid']);

#$_COOKIE['ncid']=$ncid;
#$_COOKIE['staid']=$_POST['sid'];

#echo $ncid;

print_r($_COOKIE);
exit;
