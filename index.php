<?php
$isadmin = (!empty($_GET['admin'])) ? "?admin=1":"";
if (!isset($_SERVER["HTTPS"]) || $_SERVER["HTTPS"] != "on") {
	header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"], true, 301);
	exit;
}
?>

<!doctype html>
<html lang="en">
<head><title>ARES Toolkit</title>

<?php
include "common_includes.php";
?>

<script type="text/javascript">
var curr_callsign = "";
var gotalert = 0;
var chkmsgs = setInterval("checkAdminAlert()",10000);
</script>

</head>
<body class="container">

<iframe id=alertmsg></iframe>

<iframe class="menu" name="_menu" id="_menu" src="menu.php<?=$isadmin?>"></iframe>
<iframe class="main" name="_main" id="_main" src="frontpage.php<?=$isadmin?>"></iframe>

</body>
</html>

