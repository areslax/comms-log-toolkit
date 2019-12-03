/* ##########################################
 * ARES_common_methods.js
 * Javascript methods for ARES portal scripts
 * ########################################## */

var d = new Date();
var ldate = d.getFullYear()+(d.getMonth()+1).toString().padStart(2,'0')+d.getDate().toString().padStart(2,'0')+" "+d.getHours().toString().padStart(2,'0')+":"+d.getMinutes().toString().padStart(2,'0');

var typlist = "";
jQuery.each(typs, function(key,val) {
	typlist = typlist+"<option value='"+key+"'>"+val+"</option>";
});

function startNewLog() {
	jQuery.ajax({
		type: "POST",
		url: "ajax_start_new_log.php",
		data: "doit=1",
		success: function(a,b,c){
			console.log(a);
		}
	});
	location.href = location.href;
}
//https://developers.google.com/web/fundamentals/native-hardware/user-location/
var gps = '';
function getGeoLoc(){
	// check for Geolocation support
	if (navigator.geolocation) {
		var geoSuccess = function(position) {
			// Do magic with location
			thisPos = position;
			jQuery('#stationgeo').val(thisPos.coords.latitude+","+thisPos.coords.longitude);
			gps = encodeURIComponent(thisPos.coords.latitude+","+thisPos.coords.longitude);
		};
		var geoError = function(error) {
			switch(error.code) {
				case error.TIMEOUT:
				// The user didn't accept the callout
//				alert();
				break;
			}
		};
		navigator.geolocation.getCurrentPosition(geoSuccess, geoError);
	}
}

function getTimestamp(fld) {
	var stamp = new Date();
	var day = (stamp.getDate()<10) ? "0"+String(stamp.getDate()):String(stamp.getDate());
	var mon = (stamp.getMonth()<10) ? "0"+String(stamp.getMonth()+1):String(stamp.getMonth()+1); 
	var year = String(stamp.getFullYear());
	var hrs = (stamp.getHours()<10) ? "0"+String(stamp.getHours()):String(stamp.getHours());
	var mns = (stamp.getMinutes()<10) ? "0"+String(stamp.getMinutes()):String(stamp.getMinutes());
	var sec = (stamp.getSeconds()<10) ? "0"+String(stamp.getSeconds()):String(stamp.getSeconds());
	var rightnow_fn  = year+mon+day+hrs+mns;
	var rightnow  = year+"-"+mon+"-"+day+" "+hrs+":"+mns+":"+sec;
	if (fld!='0') { document.getElementById(fld).value = rightnow; }
}
function getTimestampHTML(fld) {
	var stamp = new Date();
	var day = (stamp.getDate()<10) ? "0"+String(stamp.getDate()):String(stamp.getDate());
	var mon = (stamp.getMonth()<10) ? "0"+String(stamp.getMonth()+1):String(stamp.getMonth()+1);
	var year = String(stamp.getFullYear());
	var hrs = (stamp.getHours()<10) ? "0"+String(stamp.getHours()):String(stamp.getHours());
	var mns = (stamp.getMinutes()<10) ? "0"+String(stamp.getMinutes()):String(stamp.getMinutes());
	var sec = (stamp.getSeconds()<10) ? "0"+String(stamp.getSeconds()):String(stamp.getSeconds());
	var rightnow_fn  = year+mon+day+hrs+mns;
	var rightnow  = year+"-"+mon+"-"+day+"<br>"+hrs+":"+mns+":"+sec;
	if (fld!='0') {
		document.getElementById(fld).style.fontSize = "10px";
		document.getElementById(fld).style.textAlign = "center";
		document.getElementById(fld).innerHTML = rightnow;
	}
}
//var rightnow_fn = getTimestamp('tmstmp');
//alert(rightnow_fn);

//https://stackoverflow.com/questions/454202/creating-a-textarea-with-auto-resize
var observe;
if (window.attachEvent) {
	observe = function (element, event, handler) {
		element.attachEvent('on'+event, handler);
	};
}
else {
	observe = function (element, event, handler) {
		element.addEventListener(event, handler, false);
	};
}
//var ldate = "";
//initialize expando textareas
var iter = 0;
function init() {
	var txts = document.getElementsByClassName('msg');
//console.log(txts);return false;
	for (i=0;i<txts.length;i++) {
		init2(txts[i].id);
	}
}
function init2(fld) {
	var text = document.getElementById(fld);
	function resize () {
		text.style.height = 'auto';
		text.style.height = text.scrollHeight+'px';
	}
	/* 0-timeout to get the already changed text */
	function delayedResize () {
		window.setTimeout(resize, 0);
	}
	observe(text, 'change',  resize);
	observe(text, 'cut',	 delayedResize);
	observe(text, 'paste',   delayedResize);
	observe(text, 'drop',	delayedResize);
	observe(text, 'keydown', delayedResize);

	resize();
}

//https://stackoverflow.com/questions/27177661/save-html-locally-with-javascript
function saveMe(incoming) {
	var htmlContent = [incoming];
	var bl = new Blob(htmlContent, {type: "text/html"});
	var a = document.createElement("a");
	a.href = URL.createObjectURL(bl);
	var stamp = new Date();
	var day = (stamp.getDate()<10) ? "0"+String(stamp.getDate()):String(stamp.getDate());
	var mon = (stamp.getMonth()<10) ? "0"+String(stamp.getMonth()+1):String(stamp.getMonth()+1); 
	var year = String(stamp.getFullYear());
	var hrs = (stamp.getHours()<10) ? "0"+String(stamp.getHours()):String(stamp.getHours());
	var mns = (stamp.getMinutes()<10) ? "0"+String(stamp.getMinutes()):String(stamp.getMinutes());
	var sec = (stamp.getSeconds()<10) ? "0"+String(stamp.getSeconds()):String(stamp.getSeconds());
	var savemeDate  = year+mon+day+hrs+mns;
	a.download = savemeDate+"_ARES_Message_Log.html";
	a.hidden = true;
	document.body.appendChild(a);
	a.innerHTML = "something random - nobody will see this, it doesn't matter what you put here";
	a.click();
}

var callsign = "";
function setCallSign(id,cs) {
	callsign = cs;
//	if (id>0) {
		document.getElementById("who"+(id+1)).value=callsign;
		var datastr = "cs="+callsign+"&ncid="+document.getElementById("stationid").value+"&iid="+document.getElementById("incidentid").value+"&lid="+document.getElementById("loc"+id).value;
		jQuery.ajax({
			//set operator and php cookie with o_id
			type: "POST",
			url: "ajax_set_operator.php",
			data: datastr,
			success: function(a,b,c){
				console.log(a);
			}
		});
//	}
}
var stationid = "";
function setNetControl(nsid,ngps,niid) {
	var datastr = "sid="+nsid+"&gps="+ngps+"&iid="+niid;
	jQuery.ajax({
		type: "POST",
		url: "ajax_set_net_control.php",
		data: datastr,
		success: function(a,b,c){
			console.log(a);
			iid = niid;
			sid = a;
		}
	});
}

function really() {
	var doit = confirm("If you reload, the entire Report will reset.\nClick 'OK' to reset the Report");
	return doit;
}

function checkAction(idn,idx) {
	if (idx=='1') {
		document.getElementById('actdiv'+idn).style.display='block';
	}
	else {
		document.getElementById('actdiv'+idn).style.display='none';
	}
}

function checkAdminAlert() {
	jQuery.ajax({
		type: "POST",
		url: "ajax_admin_alert.php",
		data: "",
		success: function(a,b,c){
//		      console.log(a);
			if (a.length>0) {
				if (!gotalert) {
					jQuery("#alertmsg").attr("src","admin_alert_page.php?alert="+encodeURIComponent(a));
					jQuery("#alertmsg").css("display","block");
					jQuery("#alertmsg").css("top","10px");
					gotalert = 1;
				}
			}
			else {
				jQuery("#alertmsg").attr("src","");
				jQuery("#alertmsg").css("top","-60px");
				jQuery("#alertmsg").css("display","none");
				gotalert = 0;
			}
		}
	});
}

function checkType(rtyp,rfld,doit,inid,sid) {
	if (rfld!='') {
	//do in_array
	//mci poll
	if (rtyp=='4') {
		showModal("ARES_MCI_Poll.php?fld="+locfld[rfld]+"&rfld="+rfld+"&inid="+inid+"&sid="+sid,"block");
	}
	//hsa poll
	if (rtyp=='3') {
		showModal("ARES_HSA_Poll.php?fld="+locfld[rfld]+"&rfld="+rfld+"&inid="+inid+"&sid="+sid,"block");
	}
	//event
	if (rtyp=='2' && doit) {
		showModal("ARES_Event_Log.php?fld="+locfld[rfld]+":"+jQuery("#eventid").val()+":"+callsign+"&rfld="+rfld+"&inid="+inid+"&sid="+sid,"block");
	}
	//resource request
	if (rtyp=='5') {
		window.open("ARES_Resource_Request.php?rfld="+rfld);
	}
	//relay request
	if (rtyp=='6') {
		var itr = rfld.substring(3,rfld.length);
		document.getElementById('msgbox_'+itr).innerHTML = "<table border=0 cellpadding=2 cellspacing=0 style='background:none;margin-bottom:-6px;'><tr valign=top style='background:none !important'><td align=left style='background:none !important'><input type=hidden name=rmtyps_"+itr+" id=rmtyps"+itr+" value=''><input type=text name=msgfrom_"+itr+" id=msgfrom"+itr+" class='people' style='width:100px' tabindex='100"+itr+"0' onfocus='hiliteme("+itr+")' placeholder='Relay From'><br><input type=text name=msgto_"+itr+" id=msgto"+itr+" tabindex='100"+itr+"1' onfocus='hiliteme("+itr+")' class='people' onchange='getRelayOpts(this.value,"+itr+");' style='width:100px' placeholder='Relay To'><p style='text-align:center;margin-top:4px;' id=sentfld_"+itr+"><button type=button id=msgbut_"+itr+" style='font-size:11px;cursor:pointer;' onclick='relayMessage("+itr+")'>SEND NOW</button></p></td><td style='background:none !important'>\n<textarea name=msg_"+itr+" id=msg"+itr+" class='msg' rows=4 style='width:183px;padding:2px;' tabindex='100"+itr+"2' onfocus='hiliteme("+itr+")'></textarea></td><td><table border=0 cellpadding=0 cellspacing=0 style='background:none !important'><tr id=butsms_"+itr+" style='display:none'><td style='padding:0px !important'><input type=checkbox name=sendvia_"+itr+"[] value=sms></td><td style='font-size:10px;text-align:left;'>SMS</td></tr><tr id=butemail_"+itr+" style='display:none'><td><input type=checkbox name=sendvia_"+itr+"[] value=email></td><td style='font-size:10px;text-align:left;'>Email</td></tr><tr id=butother_"+itr+"><td><input type=checkbox name=sendvia_"+itr+"[] value=other></td><td style='font-size:10px;text-align:left;'>Other</td></tr></table></tr></table>";
		init();
		initAuto();
	}
	//others
	else {
		var itr = rfld.substring(3,rfld.length);
		document.getElementById('msgbox_'+itr).innerHTML = "<textarea name=msg_"+itr+" id=msg"+itr+" class='msg' rows=2 style='width:350px;padding:2px;height:34px;' onfocus='hiliteme("+itr+")'></textarea>";
		init();
		initAuto();
	}
	}//end if rfld
        else {
        //do in_array
        if (rtyp=='4') {
                showModal("ARES_MCI_Poll.php?fld=","block");
        }
        if (rtyp=='3') {
                showModal("ARES_HSA_Poll.php?fld=","block");
        }
        if (rtyp=='2') {
                showModal("ARES_Event_Log.php?fld=","block");
        }
	if (rtyp=='5') {
		window.open("files/20191026_Resource_Request_Form.php");
	}
	}//end else
}

function getRelayOpts(val,itr) {
	var lookfor = val.split(" ");
	var datastr = "callsign="+lookfor[0];
	jQuery.ajax({
		type: "POST",
		url: "ajax_get_relay_opts.php",
		data: datastr,
		success: function(a,b,c){
			console.log(a);
			if (a.indexOf('sms')!=-1) {
				jQuery("#butsms_"+itr).css("display","table-row");
			}
			else {
				jQuery("#butsms_"+itr).css("display","none");
			}
			if (a.indexOf('email')!=-1) {
				jQuery("#butemail_"+itr).css("display","table-row");
			}
			else {
				jQuery("#butemail_"+itr).css("display","none");
			}
			jQuery("#rmtyps"+itr).val(a);
			jQuery("#msg"+(itr-1)).focus();
		}
	});
}

function markDone(idn) {
	document.getElementById("act"+idn).style.display = "none";
	document.getElementById("actdiv"+idn).innerHTML = "<textarea id='actdone"+idn+"' name='actdone_"+idn+"' style='resize:none;text-align:center;font-size:10px;width:80px;height:22px;background:none;border:none;'></textarea>";
	getTimestamp("actdone"+idn);
}

function relayMessage(itr) {
	//send message
	var mfrom = encodeURIComponent(jQuery("#msgfrom"+itr).val());
	var mto = encodeURIComponent(jQuery("#msgto"+itr).val());
	var msg = encodeURIComponent(jQuery("#msg"+itr).val());
	var sendvia = "";
	var sendviav = "sendvia_"+itr+"[]";
	var frmsendvia = document.getElementsByName(sendviav);
	for(i=0;i<frmsendvia.length;i++) {
		sendvia += (frmsendvia[i].checked) ? frmsendvia[i].value+":":"";
	}
	var datastr = "from="+mfrom+"&to="+mto+"&msg="+msg+"&via="+sendvia;
	jQuery.ajax({
		type: "POST",
		url: "ajax_send_relay_message.php",
		data: datastr,
		success: function(a,b,c){
			console.log(a);
			//populate SEND NOW space with timestamp
			getTimestampHTML("sentfld_"+itr);
			var sentdata = jQuery("#sentfld_"+itr).html().replace("<br>"," ");
			//update container with timestamp string
			jQuery("#msgstatus"+itr).val(sentdata);
			//disabled SEND NOW
			jQuery("#msgbut_"+itr).attr("disabled",true);
		}
	});
}

function divVis(div) {
	var vis = (document.getElementById(div).style.display=='block') ? 'none':'block';
	document.getElementById(div).style.display=vis;
}

function showModal(mloc,mdis) {
	document.getElementById("modal").src = mloc;
	document.getElementById("modal").style.display = mdis;
}


