<?php
/* ########################
 * ajax_set_net_control.php
 * Sets ncid PHP cookie
 * ######################## */
if (empty($_POST['ncid'])) { exit; }

setcookie('ncid',$_POST['ncid']);

echo "set cookie";
exit;
