<?php
/* ##############################################
 * ajax_save_log.php
 * Utility to save various form data to the stick
 * ############################################## */
if (empty($_POST['rtyp'])) { exit; }
//up to the minute
$stmp = date("YmdHis");
$path_main = "reports/".$_POST['rtyp']."_log.json";
$path_stmp = "reports/".$_POST['staid']."_".$_POST['rtyp']."_log_".$stmp.".json";
$data = $_POST['frmdata'];
if ($_POST['rtyp']=='comms') { file_put_contents($path_main,$data); }
file_put_contents($path_stmp,$data);

echo "complete";
exit;