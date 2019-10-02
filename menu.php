<?php
$isadmin = (!empty($_GET['admin'])) ? "?admin=1":"";
?>

<html>
<head><title>The ARES Toolkit Menu</title>

<?php
include "common_includes.php";
?>

<script type="text/javascript">
var locs = new Array();
function addIcon() {
 addicon=window.open("addicon.php","addicon","width=1,height=1");
 setTimeout("addicon.close()",500);
}
function stopApache() {
 clearTimeout(killit);
 killme=window.open("stopkit.php","stopkit","width=1,height=1");
 setTimeout("killme.close()",500);
}
function popIRC() {
	window.open('aresircchat.php','ircwin','width=360,height=380,scrollbars=no');
}
function showMe(did) {
	var vis = (jQuery(did).attr('display','none')!=-1) ? "none":"block";
}
</script>
<style type="text/css">
* { font-family:Verdana,Arial,Helvetica,sans-serif; }
A { text-decoration:none;font-weight:bold;font-size:8pt;color:rgb(0,0,180); }
A:hover { border-bottom:1px dotted blue; }
.menuhed { padding: 3px;width: 154px;font-weight: bold; }
.ruleyelo { width:154px;border-top:1px solid rgb(220,200,0); }
</style>
</head>
<body>

<div class="ruleyelo" style="width:154px"><br /></div>

<div style="text-align:center;padding-bottom:6px;margin:0 0 12px 0;">
<img src="images/ares_logo.png" width=70 border=0 alt="The ARES Toolkit">
</div>

<?php
if (!empty($_GET['admin'])) {
?>
<div class="ruleyelo" style="width:154px"><br /></div>

<div id=menumgmt style="margin-left:10px;line-height:16px;">
 <a href="ARES_Alert_Manage.php" target="_blank" style="color:rgb(180,180,200)">Manage Admin Alerts</a><br>
 <a href="ARES_Incident_Manage.php<?=$isadmin?>" target="_blank" style="color:rgb(180,180,200)">Manage Incidents</a><br>
 <a href="ARES_Net_Control_Manage.php<?=$isadmin?>" target="_blank" style="color:rgb(180,180,200)">Manage Net Controls</a><br>
 <a href="ARES_Member_Manage.php<?=$isadmin?>" target="_blank" style="color:rgb(180,180,200)">Manage Operatorrs</a><br>
 <a href="ARES_Location_Manage.php<?=$isadmin?>" target="_blank" style="color:rgb(180,180,200)">Manage Locations</a><br>
</div>

<br />
<?php
}
?>
<div style="background-color:rgb(255,255,200)">
<div class="ruleyelo" style="width:154px"><br /></div>

<div id=menuhom style="margin-left:10px;line-height:16px;">
 <a href="frontpage.php<?=$isadmin?>" onclick="this.blur()" target="_main" style="color:rgb(180,180,200)">ARES Toolkit Home</a><br>
 <a href="https://www.freelists.org/list/arestoolkit" target="_blank" style="color:rgb(180,180,200)">Toolkit Mailing List</a><br>
 <a href="forum/index.php" target="_blank" style="color:rgb(180,180,200)">ARES Toolkit Forum</a><br>
 <a href="javascript:popIRC()" style="color:rgb(180,180,200)">ARESLAX IRC Chat</a><br><br>
</div>

<div id=menufrm style="margin-left:8px;line-height:18px;">
<?php
#if (!empty($_GET['admin'])) {
?>
 <a href="ARES_Comms_Log.php<?=$isadmin?>" target="_blank" style="font-size:13px;">ARES Comms Log</a><br>
<br>
<?php
#}
?>
 <a href="files/20190703_Amateur_Radio_MCI_Poll_Form_2011_form.pdf" class="colhead" target="_blank">ARES MCI Poll</a><br>
 <a href="files/20190703_Hospital_Status_Assessment_Form_v4.1_form.pdf" class="colhead" target="_blank">ARES HSA Poll</a><br>
 <a href="files/20190703_ARES_EVENT_Log_rev_4_form.pdf" class="colhead" target="_blank">ARES Event Log</a><br>
 <a href="files/20190703_ARES_Message_Log_rev_6_form.pdf" class="colhead" target="_blank">ARES Message Log</a><br>
 <a href="files/20190703_Resource_Request_Medical_and_Health_FIELD_to_OPAREA_2011-05-11.pdf" class="colhead" target="_blank">Resource Request</a><br>
 <a href="files/20190716_RADIOGRAM_Form-2011.pdf" class="colhead" target="_main">Radiogram Form</a><br>
 <a href="https://training.fema.gov/icsresource/icsforms.aspx" class="colhead" target="_blank">FEMA.gov: ICS Forms</a><br>
 <a href="files/20191001_LAFD_Dispatch_Form_F-27A-ACS_form.pdf" class="colhead" target="_blank">LAFD F-27A-ACS Form</a><br>
<a href="files/20190703_Hospital_Terms_Abbreviations_Ver_4.doc" class="colhead" target="_main">Hospital Abbreviations</a><br>
<br>
</div>
<div class="ruleyelo" style="width:154px"></div>
<br />

<!--
<div style="margin-left:10px;line-height:18px;">
 <a href="journal.php" onclick="this.blur()" target="_main" style="font-size:13px;">Journal</a><br />
 <a href="calendar_grid.php" onclick="this.blur()" target="_main" style="font-size:13px;">Calendar</a><br />
 <a href="budget_manager.php" onclick="this.blur()" target="_main" style="font-size:13px;">Budget Manager</a><br />
</div>

<br /><div class="ruleyelo" style="width:144px"></div>
</div>
<br />
-->

<div style="margin-left:10px;line-height:16px;">
<!-- <a href="myaccount.php" onclick="this.blur()" target="_main">My Account Online</a><br /-->
 <a href="http://www.arrllax.org/index.php?page=ares" onclick="this.blur()" target="_blank">ARESLAX Website</a><br />
 <a href="https://groups.io/g/ARESLAX" target="_blank">ARESLAX@groups.io</a><br>
 <a href="help/help_toolkit.html" onclick="this.blur()" target="_main" style="color:rgb(180,180,200)">ARES Toolkit Help</a><br>
</div>

<br /><div class="ruleyelo" style="width:154px"><br /></div>

<!-- a href="kitStopped.html" onclick="killit=setTimeout('stopApache()',500);window.top.focus();" target="_main">Shut Down the Toolkit</a><br />

<br /><div class="ruleyelo" style="width:144px"><br /></div -->

</body>
</html>
