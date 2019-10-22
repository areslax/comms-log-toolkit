<?php
/* ################################################
 * admin_alert_page.php
 * Holds admin alert that is pushed to all stations
 * ################################################ */
if (empty($_GET['alert'])) { exit; }

$alert = htmlspecialchars(urldecode($_GET['alert']),ENT_QUOTES);

include "common_includes.php";
?>
<script type="text/javascript" src="plugins/jquery.autoScrollTextTape.min.js"></script>

<body style="background-color:yellow;">

<div id=alertwin style="position:relative;left:50%;width:60%;margin-left:-30%;color:red;font-weight:bold;font-size:32px;font-family:Arial,Helvetica,sans-serif;"><?=$alert?></div>

<script type="text/javascript">
jQuery("#alertwin").autoTextTape({
	speed: 'fast'
});
</script>
<!--embed src="alert.mp3" type="audio/mpeg" width=1 height=1 hidden=true autostart=true-->
<!--embed src="beep.mp3" type="audio/mpeg" width=1 height=1 hidden=true autostart=true-->
<embed src="bosunwhistle.mp3" type="audio/mpeg" width=1 height=1 hidden=true autostart=true>
