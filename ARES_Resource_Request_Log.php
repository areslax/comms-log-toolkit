<?php
/* ######################################
 * ARES_Resource_Request_Log.php
 * Log for use by RESOURCES Command
 * View, fulfill, track resource requests
 * All updates, etc. done via ajax
 * ###################################### */
ini_set('display_errors','1');
require "db_conn.php";

//collect ALL requests for FINANCE Command and LOGISTICS Command in their own views for review/reporting
//RESOURCES can dismiss line items and entire requests from their views
//FINANCE/LOGISTICS can dismiss entire request from their views
$rrashowq = "";
$rradefault = " checked";
$rracomplete = "";
$rranotcomplete = "";
$rracancelled = "";
$rranotcancelled = "";
$rraall = "";
//AUTH: 0=incomplete,1=complete,2=cancelled
if (isset($_GET['rrashow'])) {
	$rrashowq = "rrashow=".$_GET['rrashow'];
	switch ($_GET['rrashow']) {
		case "complete":
		$rrashow = "rra_complete=1";
		$rradefault = "";
		$rracomplete = " checked";
		break;
		case "cancelled":
		$rracancelled = " checked";
		$rradefault = "";
		$rrashow = "rra_complete=2";
		break;
		case "notcancelled":
		$rranotcancelled = " checked";
		$rradefault = "";
		$rrashow = "rra_complete<2";
		break;
		case "all":
		$rraall = " checked";
		$rradefault = "";
		$rrashow = "rra_complete<3";
		break;
		default:
		$rrashow = "rra_complete<1";
	}
}
$rrashow = (!isset($_GET['rrashow']) || empty($_GET['rrashow'])) ? "<1":$rrashow;

$rrishowq = "";
$rridefault = " checked";
$rricomplete = "";
$rripartialall = "";
$rripartialopen = "";
$rriopen = "";
$rricancelled = "";
$rrinotcancelled = "";
$rriall = "";
//ITEM: 0=untouched,1=partial fullfill - ongoing,2=partial fulfill - complete,3=complete fulfill,4=cancelled
if (isset($_GET['rrishow'])) {
	$rrishowq = "rrishow=".$_GET['rrishow'];
	switch ($_GET['rrishow']) {
		case "complete":
		$rricomplete = " checked";
		$rridefault = "";
		$rrishow = "(rri_fulfilled=2 or rri_fulfilled=3)";
		break;
		case "partialall":
		$rripartialall = " checked";
		$rridefault = "";
		$rrishow = "(rri_fulfilled=1 or rri_fulfilled=2)";
		break;
		case "partialopen":
		$rripartialopen = " checked";
		$rridefault = "";
		$rrishow = "rri_fulfilled=1";
		break;
		case "open":
		$rriopen = " checked";
		$rridefault = "";
		$rrishow = "rri_fulfilled<2";
		break;
		case "cancelled":
		$rricancelled = " checked";
		$rridefault = "";
		$rrishow = "rri_fulfilled=4";
		break;
		case "notcancelled":
		$rrinotcancelled = " checked";
		$rridefault = "";
		$rrishow = "rri_fulfilled<4";
		break;
		case "all":
		$rriall = " checked";
		$rridefault = "";
		$rrishow = "rri_fulfilled<5";
		break;
		default:
		$rrishow = "rri_fulfilled<1";
	}
}
$rrishow = (!isset($_GET['rrishow']) || empty($_GET['rriview'])) ? "rri_fulfilled<1":$rrishow;

//RESOURCES can order by priority or type; default=priority
$orderby = ((isset($_GET['orderby']) && $_GET['orderby']=='priority') || empty($_GET['orderby'])) ? "rri_priority,rri_type":"rri_type,rri_priority";
$hilitep = ($orderby=='rri_priority,rri_type') ? " class='hilite'":"";
$hilitet = ($orderby=='rri_type,rri_priority') ? " class='hilite'":"";

#$q = $conn->prepare("select Resource_Request_Auths.*,Resource_Request_Items.* from Resource_Request_Auths left outer join Resource_Request_Items on Resource_Request_Items.rra_id=Resource_Request_Auths.rra_id where ".$rrashow." and ".$rrishow." order by Resource_Request_Auths.rra_id,rra_timestamp,".$orderby);
$q = $conn->prepare("select * from Resource_Requests order by req_id,req_date");
$q->execute();
$reqs = $q->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="en">
<head><title>Resource Request Log</title>

<?php
include "common_includes.php";
?>

<script type="text/javascript">
//fulfillment saves via ajax on update/change
function saveFulData(fld,val,reqid,rid) {
	var datastr = "fld="+fld+"&val="+val+"&reqid="+reqid+"&rid="+rid;
	jQuery.ajax({
		type: "POST",
		url: "ajax_save_request_fulfill.php",
		data: datastr,
		success: function(a,b,c){
			console.log(a);
		}
	})
}
function orderBy(ob) {
	location.href = "ARES_Resource_Request_Log.php?orderby="+ob+"&<?=$rrashowq?>&<?=$rrishowq?>";
}
</script>
<style type="text/css">
#auth_table TD {
	font-size: .8em;
}
#filter_table TD {
	font-size: .8em;
}
#request_notes TD {
	font-size: .8em;
}
.b { font-weight: bold; }
.hilite {
	background-color: yellow;
}
.log {
	background-color: lightgrey;
}
.sm {
	font-size: .7em;
	font-weight: normal;
}
.cntr {
        text-align: center;
        width: 40px;
}
.smb {
        font-size: .7em;
        font-weight: bold;
        line-height: 11px;
}
.med {
        font-size:13px;
}
/* type classes */
.equip {
	background-color: yellow;
}
.person {
	background-color: lightblue;
}
.other {
	background-color: lightgrey;
}
/* fulfillment classes */
.complete {
	background-color: lightgreen;
}
.partialopen {
	background-color: orange;
}
.open {
	background-color: #ffdddd;
}
</style>
</head>
<body>
<center>
<h2>ARES Resource Request Log</h2>

<!--table border=0 cellpadding=3 cellspacing=0 id="filter_table">
<tr>
  <th align=left>Requests:</th>
  <td><input type=radio name=rrashow<?=$rradefault?> onclick="location.href='ARES_Resource_Request_Log.php?<?=$rrishowq?>'"></td><td>Default</td>
  <td><input type=radio name=rrashow<?=$rraall?> onclick="location.href='ARES_Resource_Request_Log.php?rrashow=all&<?=$rrishowq?>'"></td><td>All&nbsp;</td>
  <td><input type=radio name=rrashow<?=$rranotcomplete?> onclick="location.href='ARES_Resource_Request_Log.php?rrashow=complete&<?=$rrishowq?>'"></td><td>Complete&nbsp;</td>
  <td><input type=radio name=rrashow<?=$rranotcancelled?> onclick="location.href='ARES_Resource_Request_Log.php?rrashow=notcancelled&<?=$rrishowq?>'"></td><td>Not Cancelled&nbsp;</td>
  <td><input type=radio name=rrashow<?=$rracancelled?> onclick="location.href='ARES_Resource_Request_Log.php?rrashow=cancelled&<?=$rrishowq?>'"></td><td>Cancelled&nbsp;</td>
</tr>
<tr>
  <th align=left>Line Items:</th>
  <td><input type=radio name=rrishow<?=$rridefault?> onclick="location.href='ARES_Resource_Request_Log.php?<?=$rrashowq?>'"></td><td>Default</td>
  <td><input type=radio name=rrishow<?=$rriall?> onclick="location.href='ARES_Resource_Request_Log.php?rrishow=all&<?=$rrashowq?>'"></td><td>All&nbsp;</td>
  <td><input type=radio name=rrishow<?=$rricomplete?> onclick="location.href='ARES_Resource_Request_Log.php?rrishow=complete&<?=$rrashowq?>'"></td><td>Complete&nbsp;</td>
  <td><input type=radio name=rrishow<?=$rrinotcancelled?> onclick="location.href='ARES_Resource_Request_Log.php?rrishow=notcancelled&<?=$rrashowq?>'"></td><td>Not Cancelled&nbsp;</td>
  <td><input type=radio name=rrishow<?=$rricancelled?> onclick="location.href='ARES_Resource_Request_Log.php?rrishow=cancelled&<?=$rrashowq?>'"></td><td>Cancelled&nbsp;</td>
</tr>
<tr>
  <th>&nbsp;</th>
  <td><input type=radio name=rrishow<?=$rripartialopen?> onclick="location.href='ARES_Resource_Request_Log.php?rrishow=partialopen&<?=$rrashowq?>'"></td><td>Partial - Open&nbsp;</td>
  <td><input type=radio name=rrishow<?=$rripartialall?> onclick="location.href='ARES_Resource_Request_Log.php?rrishow=partialall&<?=$rrashowq?>'"></td><td>Partial - All&nbsp;</td>
  <td><input type=radio name=rrishow<?=$rriopen?> onclick="location.href='ARES_Resource_Request_Log.php?rrishow=open&<?=$rrashowq?>'"></td><td>Open&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
</tr>
</table-->

<?php
//loop through individual active request groups
$acnt=0;
foreach($reqs as $req) {
	$acnt++;
	echo "<div style='background-color:blue;height:4px;width:100%;margin-top:10px;'>&nbsp;</div>";
	$reqid = $req['req_id'];
	$req_datetime = date("m/d/Y H:i",strtotime($req['req_date']))." Tracking#".$req['req_tracking_num'];
	$requestor_info_block = "<b>".$req['req_name']."</b>: ".$req['req_position'].", ".$req['req_agency']."<br>\n";
	$requestor_info_block .= $req['req_phone']." : ".$req['req_email']."\n";
	$requestor_mission_block = $req['req_mission_desc'];
	$requestor_staging_block = $req['req_staging_note'];
	$requestor_auth_block = "".$req['req_auth']." / ".$req['req_sig'];
?>

<h2 style="margin-top:4px;margin-bottom:2px;color:#aa0000;">Request Authorization #<?=$acnt?></h2>

<table border=1 cellpadding=4 cellspacing=0 style="background-color:#ffffaa;border-color:white" id="auth_table">
<tr><td class='b'>Incident</td><td><?=$req['incident_name']?></td><td class='b'>Request Date/Time</td><td><?=$req_datetime?></td></tr>
<tr><td class='b'>Requestor</td><td><?=$requestor_info_block?></td><td class='b'>Authorized By</td><td><?=$requestor_auth_block?></td></tr>
<tr valign=top><td class='b'>Mission Notes</td><td><?=$requestor_mission_block?></td><td class='b'>Staging Notes</td><td><?=$requestor_staging_block?></td></tr>
</table>

<h2 style="margin-bottom:2px;color:#aa0000;">Request Order Sheet #<?=$acnt?></h2>

<table border=1 cellpadding=6 cellspacing=0 style="border-color:white">
<tr><th colspan=5 style="background-color:#eebbee"><h2>REQUEST DETAILS</h2></th><th colspan=6 class="log"><big>Fulfillment / Logistics</big><br><span class="sm">NOTE: To be completed by the Level/Entity that fills the request (OA EOC, Region, State)</span></th></tr>
<tr style="background-color:#eebbee"><th rowspan=2 class="smb">I<br>T<br>E<br>M</th><th class="smb" rowspan=2<?=$hilitep?> style="cursor:pointer;" title="Click to Order By Priority" onclick="orderBy('priority')">Priority</th><th rowspan=2>Item Details</th><th rowspan=2 class="smb">Qty<br>Req</th><th class="smb" rowspan=2>Expected<br>Duration<br>of Use</th><th class="log med" colspan=3>Quantity</th><th class="log med" rowspan=2>Tracking<br>or DHV<br>Mission<br>Number</th><th class="log med" rowspan=2>Estimated<br>Arrival<br><span class="sm">(Date &amp; Time)</span></th><th class="log med" rowspan=2>COST</th></tr>
<tr><th class="log smb">Approved</th><th class="log smb">Filled</th><th class="log smb">Back-<br>Ordered</th></tr>
<?php
//get lineitems for this req
$q2 = $conn->prepare("select Resource_Request_Items.*,Resource_Request_Fulfill.* from Resource_Request_Items left outer join Resource_Request_Fulfill on (Resource_Request_Fulfill.req_id=Resource_Request_Items.req_id and Resource_Request_Fulfill.req_item_num=Resource_Request_Items.item_num) where Resource_Request_Items.req_id=:req");
$q2->execute(array(":req"=>$reqid));
$lineitems = $q2->fetchAll(PDO::FETCH_ASSOC);
$pclrs = array('sustainment'=>'#aaffcc','urgent'=>'#ffdd99','emergent'=>'#ffaaaa');
$tclrs = array('supplies'=>'#ccccff','personnel'=>'#ffffaa','other'=>'#efefef');
foreach($lineitems as $l) {
	//supply/equipment: desc,class,# per class
	//personnel: type+duties,min.experience,req.skills,pref.skills,req.by date,paid/unpaid
	//other: desc,product unit
	$rid = $l['item_num'];
	$suschk = ($l['item_priority']=='sustainment') ? " selected":"";
	$urgchk = ($l['item_priority']=='urgent') ? " selected":"";
	$emechk = ($l['item_priority']=='emergent') ? " selected":"";
	$supchk = "";
	$perchk = "";
	$othchk = "";
	switch ($l['item_type']) {
		case 'supplies':
		//supply/equipment (DEFAULT)
		$thisrow = "<table border=1 cellpadding=2 cellspacing=0 style='border-color:white;width:100%;'>\n<tr><th>Supply Item Description</th><th class='smb' style='width:12%'>Package Class</th><th class='smb' style='width:12%'>Units per Pkg Class</th></tr>\n<tr><td class='med'>".$l['item_desc']."</td><td align=center class='med'>".$l['pkg_class']."</td><td align=center class='med'>".$l['units_per_pkg_class']."</td></tr>\n</table>";
		$supchk = " selected";
		break;
		case 'personnel':
		//personnel
		$minexp = array(1=>'current hospital',2=>'current clinical',3=>'current license',4=>'clinical educat');
		$paidchk = (!empty($l['paid'])) ? "YES":"";
		$thisrow = "<table border=1 cellpadding=2 cellspacing=0 style='border-color:white;width:100%;'>\n<tr><th>Personnel Description</th><th class='smb' style='width:10%'><u>Min</u> <u>Req</u><br>Exper</th><th class='smb' style='width:10%'><u>Req</u> Skills</th><th class='smb' style='width:10%'><u>Pref</u> Skills</th><th class='smb' style='width:10%'>Required By</th><th class='smb' style='width:6%'>Paid</th></tr>\n<tr><td class='med'>".$l['item_desc']."</td><th class='sm'>".$minexp[$l['min_experience']]."</th><td class='med'>".str_replace(",",", ",$l['req_skills'])."</td><td class='med'>".str_replace(",",", ",$l['pref_skills'])."</td><th class='med'>".$l['mobilize_date']."</th><th class='med'>".$paidchk."</th></tr>\n</table>";
		$perchk = " selected";
		break;
		case 'other':
		//other
		$thisrow = "<table border=1 cellpadding=2 cellspacing=0 style='border-color:white;width:100%;'>\n<tr><th>Other Item Description</th><th class='smb' width=10%>Package Class</th></tr>\n<tr><td class='med'>".$l['item_desc']."</td><td align=center class='med'>".$l['pkg_class']."</td></tr>\n</table>";
		$othchk = " selected";
		break;
	}
?>
  <tr id="row_1" valign=top style="background-color:<?=$tclrs[$l['item_type']]?>">
    <td style="width:4%;text-align:center;" class="med"><input type=hidden name="req_item_num[]" value=<?=$rid?>><?=$rid?><br><button type=button class='sm'i style='margin-top:9px;cursor:pointer;' title='Cancel this line item'>X</button></td>
    <th class="med" style="width:5%;background-color:<?=$pclrs[$l['item_priority']]?>"><?=strtoupper(substr($l['item_priority'],0,8))?></th>
    <td id="maincell_<?=$rid?>" style="width:89%;padding:0;">
    <?=$thisrow?>
    </td>
    <th style="width:5%" class="med"><?=$l['qty_requested']?></th>
    <th style="width:3%" class="med"><?=$l['est_duration']?></th>
    <th class="log" style="width:3%"><input type=text class="cntr" onchange="saveFulData('ful_approved',this.value,<?=$reqid?>,<?=$rid?>)" value="<?=$l['ful_approved']?>"></th>
    <th class="log" style="width:3%"><input type=text class="cntr" onchange="saveFulData('ful_filled',this.value,<?=$reqid?>,<?=$rid?>)" value="<?=$l['ful_filled']?>"></th>
    <th class="log" style="width:3%"><input type=text class="cntr" onchange="saveFulData('ful_backorder',this.value,<?=$reqid?>,<?=$rid?>)" value="<?=$l['ful_backorder']?>"></th>
    <th class="log" style="width:3%"><textarea class="cntr" rows=2 style="width:80px" onchange="saveFulData('ful_tracking_num',this.value,<?=$reqid?>,<?=$rid?>)"><?=$l['ful_tracking_num']?></textarea></th>
    <th class="log" style="width:3%"><textarea class="datepicker cntr" rows=2 style="width:80px" onchange="saveFulData('ful_est_arrival',this.value,<?=$reqid?>,<?=$rid?>)"><?=$l['ful_est_arrival']?></textarea></th>
    <th class="log" style="width:3%"><input type=text class="cntr" onchange="saveFulData('ful_cost',this.value,<?=$reqid?>,<?=$rid?>)" value="<?=$l['ful_cost']?>"></th>
  </tr>

<?php
}//end line items loop
?>

</table>

<h2 style="margin-bottom:2px;color:#aa0000;">Request Notes #<?=$acnt?></h2>

<table border=1 cellpadding=4 cellspacing=0 style="background-color:yellow;border-color:white" id="request_notes">
<tr><td valign=top width="50%"><b>Suggested Source(s) of Supply; Suitable Substitute(s); Special Delivery Comment(s):</b><div style="background-color:#ffffaa;padding:4px;margin-top:4px;"><?=$req['req_supply_notes']?></div></td><td valign=top width="50%"><b>Deliver to/Report to POC (Name/Title/Location/Tel#/Email/Radio#):</b><div style="background-color:#ffffaa;padding:4px;margin-top:4px;"><?=$req['req_delivery_notes']?></div></td></tr>
<tr><td valign=top width="50%"><b>Staging &amp; Deployment Details (Parking/staging location? Food/water provided? Housing Provided? Items personnel should bring? Etc.):</b><div style="background-color:#ffffaa;padding:4px;margin-top:4px;"><?=$req['req_staging_notes']?></div></td><td valign=top width="50%"><b>Additional Instructions:</b><div style="background-color:#ffffaa;padding:4px;margin-top:4px;"><?=$req['req_additional_notes']?></div></td></tr>
</table>

<?php
}//end foreach reqs loop
?>

</center>
<br><br><br><br>
<script type="text/javascript">
jQuery(function(){
	jQuery(".datepicker").datetimepicker();
});
</script>
</body>
</html>
