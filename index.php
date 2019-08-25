<?php
$isadmin = (!empty($_GET['admin'])) ? "?admin=1":"";
?>

<!doctype html>
<html>
<head><title>ARES Toolkit</title>
<link rel="icon" type="image/png" href="favicon.ico">
<script type="text/javascript">
var curr_callsign = "";
</script>
<style type="text/css">
.menu {
	position: absolute;
	top: 0px;
	overflow: auto;
	border: none;
	height: 100%;
	width: 170px;
}
.main {
	position: absolute;
	top: 0px;
	left: 176px;
	overflow: auto;
	border: none;
	height: 100%;
	width: 84%;
}
</style>

</head>
<body>

<iframe class="menu" name="_menu" id="_menu" src="menu.php<?=$isadmin?>"></iframe>
<iframe class="main" name="_main" id="_main" src="frontpage.php<?=$isadmin?>"></iframe>

</body>
</html>

