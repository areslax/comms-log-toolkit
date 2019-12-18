<?php
/* ###################################
 * utility_edit_member_status.php
 * Small popup to update Member status
 * ################################### */
if (empty($_GET['mid']) || !is_numeric($_GET['mid'])) { exit; }
include "db_conn.php";

//got form submit, so update
if (!empty($_POST['doit'])) {
	$q = $conn->prepare("update Members set m_status=:mstat where m_id=:mid limit 1");
	$q->execute(array(':mstat'=>$_POST['m_status'],':mid'=>$_POST['m_id']));
	echo "<script>top.location.href=top.location.href;</script>";
	exit;
}

//get status codes
$q = $conn->query("select * from Status_Codes order by s_id");
$q->execute();
$qs = "";
while($qr=$q->fetch(PDO::FETCH_ASSOC)) {
	$sel = ($qr['s_id']==$_GET['sid']) ? " selected":"";
	$qs .= "<option value=".$qr['s_id'].$sel.">".$qr['s_title']."</option>";
}
include "common_includes.php";
?>
<div style="position:absolute;top:0px;right:0px;width:14px;height:14px;background-color:lightgrey;font-size:16px;font-weight:bold;text-align:center;border:solid 1px black;border-radius:14px;cursor:pointer;" onclick="parent.mstatus_modal.style.display='none'" title="Click to close popup without changing Status">X</div>
<center>
<form method=POST>
<input type=hidden name=doit value=1>
<input type=hidden name=m_id value="<?=$_GET['mid']?>">
<h4 style="margin-top:10px;margin-bottom:10px;"><?=str_replace(":","<br>",urldecode($_GET['mnam']))?></h4>
<select name=m_status style="width:120px;margin-bottom:4px;"><?=$qs?></select><br>
<input type=submit value="Update Status" style="width:120px">
</form>
</center>

