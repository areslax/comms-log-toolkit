<?php
/* ###########################################
 * ajax_set_operator_deploys_to.php
 * Utility to set Operator deploys to location
 * ########################################### */
if (empty($_POST['mid']) || !is_numeric($_POST['mid'])) { exit; }
include "db_conn.php";

$uq = $conn->prepare("update Members set m_prestage_lid=:lid where m_id=:mid limit 1");
$uq->execute(array(":lid"=>$_POST['lid'],":mid"=>$_POST['mid']));

echo "updated";
