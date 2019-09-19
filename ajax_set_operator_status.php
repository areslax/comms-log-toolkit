<?php
/* ##############################
 * ajax_set_operator_status.php
 * Utility to set Operator status
 * ############################## */
if (empty($_POST['mid']) || !is_numeric($_POST['mid'])) { exit; }
include "db_conn.php";

$uq = $conn->prepare("update Members set m_status=:status where m_id=:mid limit 1");
$uq->execute(array(":status"=>$_POST['status'],":mid"=>$_POST['mid']));

echo "updated";
