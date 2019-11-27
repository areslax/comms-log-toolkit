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
		$rrashow = "req_status=1";
		$rradefault = "";
		$rracomplete = " checked";
		break;
		case "cancelled":
		$rracancelled = " checked";
		$rradefault = "";
		$rrashow = "req_status=2";
		break;
		case "notcancelled":
		$rranotcancelled = " checked";
		$rradefault = "";
		$rrashow = "req_status<2";
		break;
		case "all":
		$rraall = " checked";
		$rradefault = "";
		$rrashow = "req_status<3";
		break;
		default:
		$rrashow = "req_status<1";
	}
}
$rrashow = (!isset($_GET['rrashow']) || empty($_GET['rrashow'])) ? "req_status<1":$rrashow;

$rrishowq = "";
$rridefault = " checked";
$rricomplete = "";
$rripartialall = "";
$rripartialopen = "";
$rriopen = "";
$rricancelled = "";
$rrinotcancelled = "";
$rriall = "";
//ITEM: 0=untouched,1=partial,2=complete fulfill,3=cancelled
if (isset($_GET['rrishow'])) {
	$rrishowq = "rrishow=".$_GET['rrishow'];
	switch ($_GET['rrishow']) {
		case "complete":
		$rricomplete = " checked";
		$rridefault = "";
		$rrishow = "item_status=2";
		break;
		case "partial":
		$rripartialall = " checked";
		$rridefault = "";
		$rrishow = "item_status=1";
		break;
		case "open":
		$rriopen = " checked";
		$rridefault = "";
		$rrishow = "item_status<2";
		break;
		case "cancelled":
		$rricancelled = " checked";
		$rridefault = "";
		$rrishow = "item_status=3";
		break;
		case "notcancelled":
		$rrinotcancelled = " checked";
		$rridefault = "";
		$rrishow = "item_status<3";
		break;
		case "all":
		$rriall = " checked";
		$rridefault = "";
		$rrishow = "item_status<4";
		break;
		default:
		//untouched and partial
		$rrishow = "item_status<2";
	}
}
$rrishow = (!isset($_GET['rrishow']) || empty($_GET['rriview'])) ? "ful_filled<1":$rrishow;

//RESOURCES can order by priority or type; default=priority
$orderby = ((isset($_GET['orderby']) && $_GET['orderby']=='priority') || empty($_GET['orderby'])) ? "item_priority,item_type":"item_type,item_priority";
$hilitep = ($orderby=='item_priority,item_type') ? " class='hilite'":"";
$hilitet = ($orderby=='item_type,item_priority') ? " class='hilite'":"";

#$q = $conn->prepare("select Resource_Request_Auths.*,Resource_Request_Items.* from Resource_Request_Auths left outer join Resource_Request_Items on Resource_Request_Items.rra_id=Resource_Request_Auths.rra_id where ".$rrashow." and ".$rrishow." order by Resource_Request_Auths.rra_id,rra_timestamp,".$orderby);
$q = $conn->prepare("select * from Resource_Requests where req_status<1 order by req_id,req_date");
$q->execute();
$reqs = $q->fetchAll(PDO::FETCH_ASSOC);

//get priority array
$priorities = array();
$pq = $conn->query("select * from Resource_Request_Priorities order by rrp_id");
$pr = $pq->fetchAll(PDO::FETCH_ASSOC);
foreach($pr as $p) { $priorities[$p['rrp_id']] = $p['rrp_name']; }
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
			if (a=="complete" || a=="cancelled") {
				jQuery("#row_"+rid).hide(600);
			}
			else if (a=="reqcomplete" || a=="reqcancelled") {
				jQuery("#reqdiv_"+reqid).hide(600);
			}
		}
	})
}
function orderBy(ob) {
	location.href = "ARES_Resource_Request_Log.php?orderby="+ob+"&<?=$rrashowq?>&<?=$rrishowq?>";
}
</script>
<style type="text/css">
@media print {
	INPUT { background-color: white; }
	TABLE,TH,TD { border: solid 1px #efefef; }
	.med { font-size: .6em; }
	.nobg { background-color: white !important; }
	.nobord { border: none !important; }
	.noprint { display: none; }
	.pbreak { page-break-before: always; }
	.rhead { font-size: .8em; }
	/* item classes */
	.sustain { background-color: yellow !important; }
	.urgent { background-color: orange !important; }
	.emergent { background-color: red !important;color: white; }
	.supplies { background-color: white !important; }
	.personnel { background-color: white !important; }
	.other { background-color: white !important; }
}
#auth_table TD {
	font-size: .8em;
}
#filter_table TD {
	font-size: .8em;
}
#request_notes TD {
	font-size: .8em;
}
.bld { font-weight: bold; }
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
/* priority classes */
.sustainment { background-color: #aaffcc; }
.urgent { background-color: #ffdd99; }
.emergent { background-color: #ffaaaa; }
/* type classes */
.supplies { background-color: #ccccff; }
.personnel { background-color: #ffffaa; }
.other { background-color: #efefef; }
/* fulfillment classes */
.complete { background-color: lightgreen; }
.partialopen { background-color: orange; }
.open { background-color: #ffdddd; }
</style>
</head>
<body>
<center>
<h2 class="noprint">ARES Resource Request Log</h2>

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
	$pbreak = ($acnt>1) ? " class='pbreak'":"";
	$reqid = $req['req_id'];
	$req_datetime = date("m/d/Y H:i",strtotime($req['req_date']))." Tracking#".$req['req_tracking_num'];
	$requestor_info_block = "<b>".$req['req_name']."</b>: ".$req['req_position'].", ".$req['req_agency']."<br>\n";
	$requestor_info_block .= $req['req_phone']." : ".$req['req_email']."\n";
	$requestor_mission_block = $req['req_mission_desc'];
	$requestor_staging_block = $req['req_staging_note'];
	$requestor_auth_block = "".$req['req_auth']." / ".$req['req_sig'];
?>

<!-- start request div -->
<div id="reqdiv_<?=$reqid?>" style="position:relative">

<div class="noprint" style="height:4px;background-color:blue;">&nbsp;</div>

<button type=button class="med noprint" onclick="saveFulData('req_complete',1,<?=$reqid?>,1)" style="position:absolute;top:10px;left:10px;padding:1px;cursor:pointer;" title="Mark this Entire Request Complete">COMPLETE REQ #<?=$acnt?></button>
<button type=button class="med noprint" onclick="saveFulData('req_cancel',1,<?=$reqid?>,1)" style="position:absolute;top:10px;right:10px;padding:1px;cursor:pointer;" title="Cancel this Entire Request">CANCEL REQ #<?=$acnt?></button>

<h2 style="margin-top:10px;margin-bottom:2px;color:#aa0000;"<?=$pbreak?>>Request Authorization #<?=$acnt?></h2>

<table border=1 cellpadding=4 cellspacing=0 class="nobg" style="background-color:#ffffaa;border-color:white" id="auth_table">
<tr><td class='bld'>Incident</td><td><?=$req['incident_name']?></td><td class='bld'>Request Date/Time</td><td><?=$req_datetime?></td></tr>
<tr><td class='bld'>Requestor</td><td><?=$requestor_info_block?></td><td class='bld'>Authorized By</td><td><?=$requestor_auth_block?></td></tr>
<tr valign=top><td class='bld'>Mission Notes</td><td><?=$requestor_mission_block?></td><td class='bld'>Staging Notes</td><td><?=$requestor_staging_block?></td></tr>
</table>

<h2 style="margin-bottom:2px;color:#aa0000;">Request Order Sheet #<?=$acnt?></h2>

<table border=1 cellpadding=6 cellspacing=0 style="border-color:white">
<tr><th colspan=5 style="background-color:#eebbee" class="nobg"><h2>REQUEST DETAILS</h2></th><th colspan=6 class="log"><big>Fulfillment / Logistics</big><br><div class="sm">NOTE: To be completed by the Level/Entity that fills the request (OA EOC, Region, State)</div></th></tr>
<tr style="background-color:#eebbee" class="nobg"><th rowspan=2 class="smb">I<br>T<br>E<br>M</th><th class="smb" rowspan=2<?=$hilitep?> style="cursor:pointer;" title="Click to Order By Priority" onclick="orderBy('priority')">Priority</th><th rowspan=2>Item Details</th><th rowspan=2 class="smb">Qty<br>Req</th><th class="smb" rowspan=2>Expected<br>Duration<br>of Use</th><th class="log med" colspan=3>Quantity</th><th class="log med" rowspan=2>Tracking<br>or DHV<br>Mission<br>Number</th><th class="log med" rowspan=2>Estimated<br>Arrival<br><span class="sm">(Date &amp; Time)</span></th><th class="log med" rowspan=2>COST</th></tr>
<tr><th class="log smb">Approved</th><th class="log smb">Filled</th><th class="log smb">Back-<br>Ordered</th></tr>
<?php
//get lineitems for this req
$q2 = $conn->prepare("select Resource_Request_Items.*,Resource_Request_Fulfill.* from Resource_Request_Items left outer join Resource_Request_Fulfill on (Resource_Request_Fulfill.req_id=Resource_Request_Items.req_id and Resource_Request_Fulfill.item_num=Resource_Request_Items.item_num) where Resource_Request_Items.req_id=:req and item_status<3 order by item_priority");
$q2->execute(array(":req"=>$reqid));
$lineitems = $q2->fetchAll(PDO::FETCH_ASSOC);
$pclrs = array('sustainment'=>'#aaffcc','urgent'=>'#ffdd99','emergent'=>'#ffaaaa');
foreach($lineitems as $l) {
	$rid = $l['item_num'];
	$suschk = ($l['item_priority']==3) ? " selected":"";
	$urgchk = ($l['item_priority']==2) ? " selected":"";
	$emechk = ($l['item_priority']==1) ? " selected":"";
	$supchk = "";
	$perchk = "";
	$othchk = "";
	switch ($l['item_type']) {
		case 'supplies':
		//supply/equipment (DEFAULT)
		$thisrow = "<table border=1 cellpadding=2 cellspacing=0 style='border-color:white;width:100%;'>\n<tr><th class='rhead'>Supply Item Description</th><th class='smb' style='width:12%'>Pkg Class</th><th class='smb' style='width:12%'>Units per Pkg Class</th></tr>\n<tr><td class='med'>".$l['item_desc']."</td><td align=center class='med'>".$l['pkg_class']."</td><td align=center class='med'>".$l['units_per_pkg_class']."</td></tr>\n</table>";
		$supchk = " selected";
		break;
		case 'personnel':
		//personnel
		$minexp = array(1=>'current hospital',2=>'current clinical',3=>'current license',4=>'clinical educat');
		$paidchk = (!empty($l['paid'])) ? "YES":"";
		$thisrow = "<table border=1 cellpadding=2 cellspacing=0 style='border-color:white;width:100%;'>\n<tr><th class='rhead'>Personnel Description</th><th class='smb' style='width:10%'><u>Min</u> <u>Req</u><br>Exper</th><th class='smb' style='width:10%'><u>Req</u> Skills</th><th class='smb' style='width:10%'><u>Pref</u> Skills</th><th class='smb' style='width:10%'>Reqd By</th><th class='smb' style='width:6%'>Paid</th></tr>\n<tr><td class='med'>".$l['item_desc']."</td><th class='sm'>".$minexp[$l['min_experience']]."</th><td class='med'>".str_replace(",",", ",$l['req_skills'])."</td><td class='med'>".str_replace(",",", ",$l['pref_skills'])."</td><th class='med'>".$l['mobilize_date']."</th><th class='med'>".$paidchk."</th></tr>\n</table>";
		$perchk = " selected";
		break;
		case 'other':
		//other
		$thisrow = "<table border=1 cellpadding=2 cellspacing=0 style='border-color:white;width:100%;'>\n<tr><th class='rhead'>Other Item Description</th><th class='smb' width=10%>Pkg Class</th></tr>\n<tr><td class='med'>".$l['item_desc']."</td><td align=center class='med'>".$l['pkg_class']."</td></tr>\n</table>";
		$othchk = " selected";
		break;
	}
	//cancel item button
	$icancel = ($l['item_status']<3) ? "<br><button type=button class='sm noprint' onclick=\"saveFulData('ful_cancel',1,".$reqid.",".$rid.")\" style='margin-top:9px;padding:1px;cursor:pointer;' title='Cancel this line item'>X</button>":"";
	//item is complete if: click complete or filled=requested or approved=requested
	$fcomplete = ($l['item_status']!=3) ? "<br><button type=button class=\"noprint\" onclick=\"saveFulData('ful_complete',1,".$reqid.",".$rid.")\" style=\"font-size:.5em;cursor:pointer;padding:0;\" title=\"Mark this line item complete\">COMPLETE</button>":"";
?>
  <tr id="row_<?=$rid?>" valign=top class="<?=$l['item_type']?>">
    <td style="width:4%;text-align:center;" class="med"><input type=hidden name="req_item_num[]" value=<?=$rid?>><?=$rid?><?=$icancel?></td>
    <th class="med <?=$priorities[$l['item_priority']]?>" style="width:5%;"><?=strtoupper(substr($priorities[$l['item_priority']],0,8))?></th>
    <td id="maincell_<?=$rid?>" style="width:89%;padding:0;">
    <?=$thisrow?>
    </td>
    <th style="width:5%" class="med"><?=$l['qty_requested']?></th>
    <th style="width:3%" class="med"><?=$l['est_duration']?></th>
    <th class="log nobg" style="width:3%"><input type=text class="cntr" onchange="saveFulData('ful_approved',this.value,<?=$reqid?>,<?=$rid?>)" value="<?=$l['ful_approved']?>"></th>
    <th class="log nobg" style="width:3%"><input type=text class="cntr" onchange="saveFulData('ful_filled',this.value,<?=$reqid?>,<?=$rid?>)" value="<?=$l['ful_filled']?>"><?=$fcomplete?></th>
    <th class="log nobg" style="width:3%"><input type=text class="cntr" onchange="saveFulData('ful_backorder',this.value,<?=$reqid?>,<?=$rid?>)" value="<?=$l['ful_backorder']?>"></th>
    <th class="log nobg" style="width:3%"><textarea class="cntr nobord" rows=2 style="width:80px" onchange="saveFulData('ful_tracking_num',this.value,<?=$reqid?>,<?=$rid?>)"><?=$l['ful_tracking_num']?></textarea></th>
    <th class="log nobg" style="width:3%"><textarea class="datepicker cntr nobord" rows=2 style="width:80px" onchange="saveFulData('ful_est_arrival',this.value,<?=$reqid?>,<?=$rid?>)"><?=$l['ful_est_arrival']?></textarea></th>
    <th class="log nobg" style="width:3%"><input type=text class="cntr" onchange="saveFulData('ful_cost',this.value,<?=$reqid?>,<?=$rid?>)" value="<?=$l['ful_cost']?>"></th>
  </tr>

<?php
}//end line items loop
?>

</table>

<h2 style="margin-bottom:2px;color:#aa0000;">Request Notes #<?=$acnt?></h2>

<table border=1 cellpadding=4 cellspacing=0 style="background-color:yellow;border-color:white;margin-bottom:10px;" class="nobg" id="request_notes">
<tr><td valign=top width="50%"><b>Suggested Source(s) of Supply; Suitable Substitute(s); Special Delivery Comment(s):</b><div style="background-color:#ffffaa;padding:4px;margin-top:4px;" class="nobg"><?=$req['req_supply_notes']?></div></td><td valign=top width="50%"><b>Deliver to/Report to POC (Name/Title/Location/Tel#/Email/Radio#):</b><div style="background-color:#ffffaa;padding:4px;margin-top:4px;" class="nobg"><?=$req['req_delivery_notes']?></div></td></tr>
<tr><td valign=top width="50%"><b>Staging &amp; Deployment Details (Parking/staging location? Food/water provided? Housing Provided? Items personnel should bring? Etc.):</b><div style="background-color:#ffffaa;padding:4px;margin-top:4px;" class="nobg"><?=$req['req_staging_notes']?></div></td><td valign=top width="50%"><b>Additional Instructions:</b><div style="background-color:#ffffaa;padding:4px;margin-top:4px;" class="nobg"><?=$req['req_additional_notes']?></div></td></tr>
</table>

<!-- end request div -->
</div>

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
