<?php
/* ####################################
 * grab_report.php
 * If existing report, pull and display
 * #################################### */
include_once "db_conn.php";

$rlike = '"stationid":"'.$sid.'","incidentid":"'.$iid.'"';
$grab = "";

$q = $conn->prepare("select * from Reports where r_data like ? order by r_id desc limit 1");
$q->execute(array("%$rlike%"));
if ($q->errorInfo()[0]!='00000') {
	echo "oops:\n";
	print_r($q->errorInfo());
	exit;
}
$r=$q->fetch(PDO::FETCH_ASSOC);

//define types of entry
$typs = array(" ");
$rqq = $conn->prepare("select rqt_title from Entry_Types order by rqt_id");
$rqq->execute();
while($rt=$rqq->fetch(PDO::FETCH_ASSOC)) {
        $typs[] = $rt['rqt_title'];
}

$operator = $oid;

$d = json_decode($r['r_data']);

$stationid = (isset($d->stationid)) ? $d->stationid:"";
$stationloc = (isset($d->stationloc)) ? $d->stationloc:"";
$incidentid = (isset($d->incidentid)) ? $d->incidentid:"";
$incidentname = (isset($d->incidentname)) ? $d->incidentname:"";

foreach($d->ids as $id) {

	$msgstatus = "msgstatus_".$id;
	$ts = "ts_".$id;
	$rxtx = "rxtx_".$id;
	$loc = "loc_".$id;
	$typ = "typ_".$id;
	$msg = "msg_".$id;
	$act = "act_".$id;
	$who = "who_".$id;
	//extra stuff, if relay message
	$isrelay = 0;
	if ($d->$typ == 6) {
		$isrelay = 1;
		$msgfrom = "msgfrom_".$id;
		$msgto = "msgto_".$id;
		$sendvia = "sendvia_".$id;
		$sendopts = "sendopts_".$id;
		$rmtyps = "rmtyps_".$id;
		$msgsent = "msgsent_".$id;
	}

	//if action required
	$actreqd = 0;
	$actdone = 0;
	if ($d->$act == "Yes") {
		$actreqd = 1;
		$actdone = "actdone_".$id;
	}
	//select menus
	$rxsel = (strtolower($d->$rxtx) == 'rx') ? " selected":"";
	$txsel = (strtolower($d->$rxtx) == 'tx') ? " selected":"";
	$cmsel = (strtolower($d->$rxtx) == 'comment') ? " selected":"";
	$yssel = ($actreqd) ? " selected":"";
	$nosel = (!$actreqd) ? " selected":"";
	//entry type menu
	$typlist = "";
	foreach($typs as $i => $t) {
		$sel = ($i == $d->$typ) ? " selected":"";
		$typlist .= "<option value=".$i.$sel.">".$t."</option>";
	}

	$ts = (empty($d->$msg)) ? "":$d->$ts;
	$tsfocus = 'jQuery("#ts'.($id-1).'").focus();';//(empty($ts) && empty($tsfocus)) ? 'jQuery("#ts'.$id.'").focus();':((!empty($tsfocus)) ? $tsfocus:$ts);

	$grab .= '<tr id=row'.$id.' valign=top><th id=rid'.$id.'><input type=hidden name=ids[] value='.$id.'><input type=hidden name=msgstatus_'.$id.' id=msgstatus'.$id.' value="'.$d->$msgstatus.'">'.$id.'</th><td><input type=text size=20 name=ts_'.$id.' id=ts'.$id.' onfocus="hiliteme('.$id.')"';
	if (empty($ts)) { $grab .= ' onblur="getTimestamp(this.id);disableblur(this);"'; }
	$grab .= ' placeholder="TAB to Set" value="'.$ts.'"></td><td><select name=rxtx_'.$id.' class="px40" onfocus="hiliteme('.$id.')"><option'.$rxsel.'>Rx</option><option'.$txsel.'>Tx</option><option'.$cmsel.'>Comment</option></select></td><td><input type=text name=loc_'.$id.' id=loc'.$id.' class="location" onfocus="hiliteme('.$id.')" placeholder="Tac Call" value="'.$d->$loc.'"></td><td><select name=typ_'.$id.' id=typ'.$id.' class="typdd px80" onfocus="hiliteme('.$id.')" onchange="checkType(this[this.selectedIndex].value,\'loc'.$id.'\',0,encodeURIComponent(this.form.incidentid.value),this.form.stationid.value);">'.$typlist.'</select></td><td style="padding-bottom:0px;text-align:center;" id="msgbox_'.$id.'">';
	if (!$isrelay) {
		$grab .= '<textarea name=msg_'.$id.' id=msg'.$id.' class="msg" rows=2 style="width:350px;padding:2px;" onfocus="hiliteme('.$id.')">'.$d->$msg.'</textarea>';
		$sendbut = '';
	}
	else {
		$grab .= '<table border=0 cellpadding=2 cellspacing=0 style="background:none;margin-bottom:-6px;"><tr valign=top style="background:none !important"><td align=left style="background:none !important"><input type=hidden name=msgsent_'.$id.' id=msgsent_'.$id.' value="'.$d->$msgsent.'"><input type=hidden name=rmtyps_'.$id.' id=rmtyps'.$id.' value="'.$d->$rmtyps.'"><input type=hidden name=sendopts_'.$id.' id=sendopts'.$id.' value=\''.stripslashes($d->$sendopts).'\'><input type=text name=msgfrom_'.$id.' id=msgfrom'.$id.' class="people" style="width:100px" onfocus="hiliteme('.$id.')" placeholder="Relay From" value="'.$d->$msgfrom.'"><br><input type=text name=msgto_'.$id.' id=msgto'.$id.' onfocus="hiliteme('.$id.')" class="people" onchange="getRelayOpts(this.value,'.$id.');" style="width:100px" placeholder="Relay To" value="'.$d->$msgto.'">';

		$sendbut = (empty($d->$msgsent)) ? '<p style="text-align:center;margin-top:4px;" id=sentfld_'.$id.'><button type=button id=msgbut_'.$id.' style="font-size:11px;cursor:pointer;" onclick="relayMessage('.$id.')">SEND NOW</button></p>':'<div style="margin-top:4px;text-align:center;font-size:10px;line-height:10px;">'.str_replace(" ","<br>",date("m/d/Y H:i:s",strtotime($d->$msgsent))).'</div>';

		$grab .= '</p></td><td style="background:none !important"><textarea name=msg_'.$id.' id=msg'.$id.' class="msg" rows=4 style="width:183px;padding:2px;" onfocus="hiliteme('.$id.')">'.$d->$msg.'</textarea></td><td><table border=0 cellpadding=0 cellspacing=0 style="background:none !important">';
		$rmtypa = json_decode(stripslashes($d->$sendopts),'true');
		if (in_array("sms",$rmtypa)) {
			$chkd = (in_array("sms",$d->$sendvia)) ? " checked":"";
			$grab .= '<tr id=butsms_'.$id.'><td style="padding:0px !important"><input type=checkbox'.$chkd.' name=sendvia_'.$id.'[] value=sms></td><td style="font-size:10px;text-align:left;">SMS</td></tr>';
		}
		if (in_array("email",$rmtypa)) {
			$chkd = (in_array("email",$d->$sendvia)) ? " checked":"";
			$grab .= '<tr id=butemail_'.$id.'><td><input type=checkbox'.$chkd.' name=sendvia_'.$id.'[] value=email></td><td style="font-size:10px;text-align:left;">Email</td></tr>';
		}
		$chkd = (in_array("other",$d->$sendvia)) ? " checked":"";
		$grab .= '<tr id=butother_'.$id.'><td><input type=checkbox'.$chkd.' name=sendvia_'.$id.'[] value=other></td><td style="font-size:10px;text-align:left;">Other</td></tr>';
		$grab .= "</table></td></tr></table>";
	}
	$grab .= '</td><td id=reqtd_'.$id.' align=center>';
	$showdone = (empty($d->$actdone) && $d->$act!="Yes") ? "":"display:block;";
	$hidemenu = (empty($d->$actdone)) ? "":"display:none;";
	$grab .= '<select name=act_'.$id.' id=act'.$id.' onfocus="hiliteme('.$id.')" style="width:60px;'.$hidemenu.'" onchange="checkAction('.$id.',this.selectedIndex)" onblur="document.getElementById(\'who'.$id.'\').focus()"><option'.$nosel.'>No<option'.$yssel.'>Yes</select>';
	$grab .= '<div id=actdiv'.$id.' class="actdiv" style="'.$showdone.'">';
	$donebut = '<button type=button style="display:inherit;margin-bottom:0px;" class="combut noprint" onclick="markDone('.$id.')">Done</button>';
	if (!empty($d->$actdone)) { $donebut = '<div style="font-size:10px;line-height:10px;">'.str_replace(" ","<br>",date("m/d/Y H:i:s",strtotime($d->$actdone))).'</div>'; }
	$grab .= $donebut;
	$grab .= '</div>'.$sendbut.'</td><td><input type=text name=who_'.$id.' id=who'.$id.' size=9 style="text-align:center" placeholder="Callsign" onfocus="hiliteme('.$id.')" onblur="if(this.value==\'\'){this.value=prompt(\'What is your call sign?\');}setCallSign('.$id.',this.value);addRow(this.form);document.getElementById(\'who'.($id + 1).'\').value=this.value;this.onBlur=\'\';disableblur(this);document.getElementById(\'ts'.($id +1).'\').focus();" value="'.$d->$who.'"></td></tr>';

	//set for javascript to pick up for new rows
	$newiter = $id;

}//end foreach d->ids

#echo $grab;
