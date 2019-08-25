<?php
/* ###################################
 * file_wrapper.php
 * Utility to wrap PDF files for modal
 * ################################### */
if (empty($_GET['furl'])) { exit; }

$furl = "files/".$_GET['furl'];
?>
<!doctype html>
<html>
<head><title>File Wrapper</title>
<script type="text/javascript">
function closeWin() {
	parent.modal.style.display = "none";
}
</script>
</head>
<body style="background-color:white">
<div style="position:absolute;top:0px;right:0px;width:14px;height:14px;border:solid 1px black;border-radius:14px;background-color:lightgrey;cursor:pointer;font-size:16px;font-weight:bold;font-family:Arial,Helvetica,sens-serif;text-align:center;" onclick="closeWin()">X</div>
<iframe src="<?=$furl;?>" style="position:absolute;top:18px;left:-6px;width:100%;height:484px;"></iframe>
</body>
</html>
