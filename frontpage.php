<?php
$isadmin = (!empty($_GET['admin'])) ? "?admin=1":"";
?>
<html>
<head><title>The ARES Toolkit</title>

<?php
include "common_includes.php";
?>

<script type="text/javascript">
function popIRC() {
	window.open('aresircchat.php','ircwin','width=360,height=380,scrollbars=no');
}
function popCheckin() {
        window.open('ARES_Operator_Status.php','checkinwin','width=800,height=600,scrollbars=auto');
}
</script>
<style type="text/css">
* { font-family:Verdana,Arial,Helvetica,sans-serif }
A { text-decoration:none;font-weight:bold }
A:hover { border-bottom:1px dotted blue; }
BODY { padding:40px; }
.colbody {
 padding-left:10px;
 font-size:10pt;
 font-style:italic;
}
.colhead {
 font-size:12pt;
 font-weight:bold;
 color:#009;
}
.txtboxl {
 position:absolute;
 top:300px;
 left:160px;
}
.txtboxr {
 position:absolute;
 top:300px;
 left:490px;
}
.ctr { text-align:center; }
.sm { font-size: .6em;font-weight: normal; }
</style>
</head>
<body onload="self.focus()">

<p class="ctr" style="margin-top:-30px">

<img src="images/ares_logo.png" border=0 alt="The ARES Toolkit" />

</p>

<center>
<table border=0 cellpadding=0 cellspacing=0>
<tr><td align=center colspan=3 style="border-top:solid 1px #900;border-bottom:solid 1px #900">

<br>
<!-- put new entries here -->
<?php
$checkin = "";
if (!empty($_GET['admin'])) {
?>
<p style="margin:6px">
<b>MANAGE:</b>&nbsp;&nbsp;&nbsp; <a href="ARES_Alert_Manage.php<?=$isadmin?>" target="_blank" class="colhead">Alerts</a> &nbsp;|&nbsp; <a href="ARES_Incident_Manage.php<?=$isadmin?>" target="_blank" class="colhead">Incidents</a> &nbsp;|&nbsp; <a href="ARES_Net_Control_Manage.php<?=$isadmin?>" target="_blank" class="colhead">Net Controls</a> &nbsp;|&nbsp; <a href="ARES_Member_Manage.php<?=$isadmin?>" target="_blank" class="colhead">Operators</a> &nbsp;|&nbsp; <a href="ARES_Location_Manage.php<?=$isadmin?>" target="_blank" class="colhead">Locations</a><br><br>
<?php
	$checkin = "<a href='javascript:popCheckin()' class='colhead'>Operator Check In</a>&nbsp;|&nbsp;";
}
?>
<?=$checkin?><a href="ARES_Comms_Log.php<?=$isadmin?>" target="_blank" class="colhead"><big>ARES Net Control Comms Log</big></a><br><br>
<a href="ARES_Resource_Request.php">NEW: Resource Request Form</a><br><span class="sm">Right-Click or Ctrl+Click or Cmd+Click to open in<br>a new tab/window, so you can take your time</span><br><br>
<!--/p-->
<?php
if (!empty($_GET['admin']) && $_GET['admin']=='2') { ?>
<a href="ARES_SMS_Group_Manage.php" target="_blank">ARES Member SMS Messaging</a>
<br><br>
<?php
} ?>
<div style="border-top:solid 1px #900;margin:0;padding-top:20px;">
<?php
#}
?>
<a href="https://www.freelists.org/list/arestoolkit" target="_blank">ARES Toolkit Mailing List</a> | <a href="forum/index.php" target="_blank">ARES Toolkit Forum</a> &nbsp;|&nbsp; <a href="javascript:popIRC()" class="colhead">ARESLAX IRC Chat</a>
<div style="border-top:solid 1px #900;margin:20px 0 0 0;padding-top:20px;">
<div onclick="javascript:divVis('forms')" style="cursor:pointer;font-weight:bold;margin:0 0 20px 0;padding:4px;border:solid 1px lightgrey;border-radius:4px;background-color:rgb(255,255,230);">FORMS: Download or Fill &amp; Print:</div>
<div id="forms" style="display:none;margin:0 0 16px 0;">
<table border=0 cellpadding=6 cellspacing=0>
<tr valign=top><td align=center>
<a href="files/20190703_Amateur_Radio_MCI_Poll_Form_2011_form.pdf" class="colhead">ARES MCI Poll</a><br>
<a href="files/20190703_Hospital_Status_Assessment_Form_v4.1_form.pdf" class="colhead">ARES HSA Poll</a><br>
<a href="files/20190703_ARES_EVENT_Log_rev_4_form.pdf" class="colhead">ARES Event Log</a><br>
<a href="files/20190703_ARES_Message_Log_rev_6_form.pdf" class="colhead">ARES Message Log</a><br>
</td><td align=center>
<a href="files/20190703_Resource_Request_Medical_and_Health_FIELD_to_OPAREA_2011-05-11.pdf" class="colhead">Resource Request Form</a><br>
<a href="files/20190716_RADIOGRAM_Form-2011.pdf" class="colhead">Radiogram Form</a><br>
<a href="https://training.fema.gov/icsresource/icsforms.aspx" class="colhead" target="_blank">FEMA.gov: ICS Forms</a><br>
<a href="files/20191001_LAFD_Dispatch_Form_F-27A-ACS_form.pdf" class="colhead">LAFD F-27A-ACS Form</a><br>
</td></tr>
<tr><td colspan=2 align=center><a href="http://file.lacounty.gov/SDSInter/dhs/206720_DisasterOrgChart.pdf" class="colhead" target="_blank">LA County Disaster Org Chart</a></td></tr>
</table>
</div>
</div>

</td></tr>
</table>

<table border=0 cellpadding=6 cellspacing=0 style="margin-top:12px">
<tr>
<td align=center>
<!-- <a href="calendar_grid.php" class="colhead">Event Calendar</a><br />
 <span class="colbody">For managing time</span>-->

 <span class="colhead">ARESLAX</span> <a href="https://groups.io/g/ARESLAX" target="_blank" class="colhead" title="ARESLAX at groups.io">Groups.io</a> | <a href="http://www.arrllax.org/index.php?page=ares" target="_blank" class="colhead" title="ARESLAX.org at ARRL.org">.ORG</a><br />
 <span class="colbody">For managing accounts and such</span><br />
</td>
</tr>

</table>

</center>
<p class="ctr">
<!--a href="javascript:void addIcon()" style="font-size:9pt;color:#99d">Click here to add an icon to your Desktop</a-->
</p>

<?php include "footer.html"?>

<iframe id=modal style="position:fixed;top:10px;left:50%;width:800px;margin-left:-400px;height:90%;padding:10px;background-color:white;border:solid 1px black;border-radius:6px;display:none;"></iframe>

</body>
</html>
