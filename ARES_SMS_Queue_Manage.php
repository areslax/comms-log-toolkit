<?php
/* #####################################
 * ARES_SMS_Queue_Manage.php
 * Managing scheduled group SMS messages
 * ##################################### */
include "db_conn.php";
include "common_includes.php";

//get all un-sent scheduled messages
$q = $conn->query("select SMS_Queue.*,smsg_name from SMS_Queue join SMS_Groups on SMS_Groups.smsg_id=SMS_Queue.smsg_id where smsq_sent_ts is NULL order by smsq_id");
$q->execute();
$out = "";
while($r=$q->fetch(PDO::FETCH_ASSOC)) {
	$out .= "<tr id='row".$r['smsq_id']."'><th>".$r['smsg_name']."</th><th><textarea name=smsq_message onchange=\"saveData('".$r['smsq_id']."','smsq_message',this.value)\" style='width:300px'>".str_replace("'","\'",stripslashes($r['smsq_message']))."</textarea></th><th><input type=text name=smsq_send_ts class='datepicker' value='".date("Y-m-d H:i",strtotime($r['smsq_send_ts']))."' onchange=\"saveData('".$r['smsq_id']."','smsq_sent_ts',this.value)\"></th><th><input type=button onclick='sendNow(".$r['smsq_id'].")' value='NOW'></th><th><input type=button onclick='saveData(".$r['smsq_id'].",0,0)' value='X'></th></tr>";
}
?>
<!doctype html>
<html lang="en">
<head><title>ARES SMS Queue Management</title>
<script type="text/javascript">
function saveData(id,fld,data) {
	var datastr = "id="+id+"&fld="+fld+"&data="+data;
	jQuery.ajax({
		type: "POST",
		url: "ajax_save_sms_queue_data.php",
		data: datastr,
		success: function(a,b,c){
//			console.log(a);
			var rvis = (a=="deleted") ? "none":"table-row";
			jQuery('#row'+id).css('display',rvis);
		}
	});
}
function sendNow(qid) {
	var datastr = "qid="+qid;
	jQuery.ajax({
		type: "POST",
		url: "ajax_sms_send_now.php",
		data: datastr,
		success: function(a,b,c){
			console.log(a);
			var rvis = (a=="send ok") ? "none":"table-row";
			jQuery('#row'+qid).css('display',rvis);
		}
	});
}
</script>
</head>
<body>

<center>
<h2>Manage ARES SMS Queue</h2>
<table border=1 cellpadding=6 cellspacing=0 width=50%>
<tr><th>GROUP</th><th>MESSAGE TEXT</th><th>SCHEDULED</th><th>SEND</th><th>DELETE</th></tr>
<?=$out?>
</table>
</center>

<script type="text/javascript">
jQuery(function() {
	jQuery(".datepicker").datetimepicker();
});
</script>

</body>
</html>
