<!doctype html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Resource Request: Medical and Health FIELD/HCF</title>

<script type="text/javascript">
/*
function addNewRow() {
	var newrow = "<tr valign=top>\n";
	newrow += "<td style='width:4%;text-align:center;'><input name='req_item[]' size=2 maxlength=3 style='text-align:center;border:none;' value=1></td>\n";
	newrow += "<td style='width:5%'>\n";
	newrow += "<select name='req_item_priority[]'>\n";
	newrow += "<option value=0></option>\n";
	newrow += "<option value='Sustainment'>Sustainment</option>\n";
	newrow += "<option value='Urgent'>Urgent</option>\n";
	newrow += "<option value='Emergent'>Emergent</option>\n";
	newrow += "</select>\n";
	newrow += "</td>\n";
	newrow += "<td style='width:89%'>\n";
	newrow += "<textarea name='req_item_desc[]' style='width:98%;height:1.1em;'></textarea></td>\n";
	newrow += "<th style='width:5%'>\n";
	newrow += "<input type=number name='req_item_qty[]' style='width:40px;text-align:center;'></td>\n";
	newrow += "<td style='width:3%'>\n";
	newrow += "<input type=text name='req_item_estdu[]' style='width:90px;text-align:center;' maxlength=40></td>\n";
	newrow += "</tr>\n";
}
/**/
</script>
<style type="text/css">
* { font-family: Arial,Helvetica,sans-serif; }
INPUT { font-weight: normal; }
TABLE { border-collapse: collapse; }
TABLE, TH, TD { border: solid 1px grey; }
TABLE.noborder { border: none; }
TABLE.noborder TH { border: none; }
TABLE.noborder TD { border: none; }

.grey { color: grey; }
.lightblue { background-color: #ddddff; }
.lightgreen { background-color: #ddffdd; }
.lightred { background-color: #ffdddd; }
.lightyelo { background-color: #ffffcc; }
</style>

</head>
<body>

<FORM>

<!-- PAGINATE DURING PRINTING
<p style="text-align:right">Page 1 of <input name="pgcnt" type="number" min="1" max="99" size=2 maxlength=3></p-->

<table cellpadding=4 cellspacing=0 style="width:100%;">
  <tr class="lightyelo">
    <td colspan=3 style="height:36px;text-align:center;"><h2>Resource Request: Medical and Health FIELD/HCF<sup style="font-size:.5em">1</sup> to Op Area</h2></td>
    <th style="width:126px;height:36px;"><h5>RR MH (10/26/2019)</h5></th>
  </tr>

  <tr>
    <th style="width:66px;height:36px;font-size:11px;line-height:16px;" class="lightred" rowspan=5>
    <p>R<br>E<br>Q<br>U<br>E<br>S<br>T<br>O<br>R</p>
    <p>T<br>O</p>
    <p>C<br>O<br>M<br>P<br>L<br>E<br>T<br>E</p></th>

    <td style="width:76%;font-weight:bold;" valign=top>
    1. Incident Name:<div style="padding-left:2%;"><input tabindex=1 type="text" name="incident_name" style="width:97%" maxlength=220></div></td>
    <th style="font-size:11px;width:12%;" class="lightgreen" valign=top>
    2a. Request DATE:<br>
	<input tabindex=2 type=date class="datefld" name="req_date" style="text-align:center;width:90%" maxlength=10></th>
    <th style="font-size:11px;width:12%;" class="lightgreen" valign=top>
    2b. Request TIME:<br>
	<input tabindex=3 type=time class="timefld" name="req_time" style="text-align:center;width:90%;" maxlength=12></th>
  </tr>

  <tr>
    <td style="width:76%;font-weight:bold;" valign=top>
    3. Requestor Name, Agency, Position, Phone / Email:<br>
    <table class="noborder" style="width:100%;font-weight:normal;">
	<tr><td>Requestor Name:</td><td style="width:84%"><input tabindex=5 type=text name="req_name" style="width:97%"></td></tr>
	<tr><td>Agency:</td><td><input tabindex=6 type=text name="req_agency" style="width:97%" maxlength=120></td></tr>
	<tr><td>Position:</td><td><input tabindex=7 type=text name="req_position" style="width:97%" maxlength=120></td></tr>
	<tr><td>Phone:</td><td><input tabindex=8 type=text name="req_phone" style="width:30%" maxlength=40></td></tr>
	<tr><td>Email:</td><td><input tabindex=9 type=text name="req_email" style="width:30%" maxlength=40></td></tr>
    </table></td>
    <th style="font-size:11px;width:24%;padding-top:10px;" class="lightgreen" colspan=2 valign=top>
    2c. Request TRACKING NUMBER:<br><span style="font-weight:normal;font-style:italic;">(Assigned by Requesting Entity)</span><br>
	<input tabindex=4 type=text name="req_tracking_num" style="width:40px;text-align:center;" maxlength=3></b></font></td>
  </tr>

  <tr>
    <td style="font-weight:bold;" colspan=3 valign=top>
    4. Describe Mission/Tasks:<div style="padding-left:2%;"><textarea tabindex=10 name="req_mission_desc" style="width:97%"></textarea></td>
  </tr>

  <tr>
    <td style="font-weight:bold;padding-left:0px;;" colspan=3>
	<table class="noborder" cellpadding=4 style="font-size:12px;">
	<tr>
	<td style="font-size:1.5em;padding-right:20px;">5. ORDER SHEETS: <i>ATTACH ADDITIONAL</i></td>
	<th><input type=checkbox name="req_order_supplies" value=1></th><td><b>SUPPLIES</b></td>
	<th><input type=checkbox name="req_order_equipment" value=1></th><td><b>EQUIPMENT</b></td>
	<th><input type=checkbox name="req_order_personnel" value=1></th><td><b>PERSONNEL</b></td>
	<th><input type=checkbox name="req_order_other" value=1></th><td><b>OTHER</b></td></tr>
	</table>
    </td>
  </tr>

  <tr class="lightblue">
    <td style="position:relative;font-weight:bold;font-size:18px;" colspan=3>
    6. ORDER <div style="position:relative;display:inline;left:14%;font-size:1.5em;text-align:center;font-style:italic;">SUPPLY / EQUIPMENT / PERSONNEL REQUEST DETAILS</div></td>
  </tr>

  <tr>
  <td colspan=4 class="lightblue">
    <table cellpadding=4 cellspacing=0 width=100% style="background-color:white">
    <tr>
    <th style="width:4%;line-height:18px;">
	<p>I<br>T<br>E<br>M<br><br>#</th>
    <th style="width:5%;">
	Priority<sup style="font-size:.7em">2</sup><br><span style="font-weight:normal;font-style:italic;font-size:.7em;">(See Below)</span></td>
    <td style="width:89%;text-align:center;font-weight:bold;">
    <h2 style="font-size:1.6em;margin:0px;color:red;">DETAILED SPECIFIC ITEM DESCRIPTION:</h2>
    <h3 style="margin:0px;color:red;">Supplies / Equipment</h3>
    <div style="width:90%;margin:0 0 12px 10%;text-align:left;font-weight:normal">
    <b>Rx:</b> Drug Name, Dosage Form, UNIT OF USE PACK or Volume, Prod Info Sheet, In-House PO, photos, etc.<br>
    <b>Medical Supplies:</b> Item name, Size, Brand, etc.<br>
    <b>General Supplies/Equipment:</b> Food, Water, Generators, etc.
    </div>
    <h3 style="margin:0px;color:red;">Personnel</h3>
    <div style="width:90%;margin:0 0 12px 10%;text-align:left;font-weight:normal">
    <b>Type &amp; Probable Duties</b>
    Req'd License, MD, RN, PharmD, ICU/OR Experience, Hospital/Clinical Experience, etc.
    </div>
    <h3  style="margin:0px;color:red;">Other</h3>
    <div style="width:90%;margin:0 0 12px 10%;text-align:left;font-weight:normal">
    Mobile Field Hospital; Ambulance Strike Team; Alternate Care Supply Cache; Facility-Tent, Trailer, Size, etc.
    </div></td>
    <th style="width:5%">
    Quantity Requested
    <p style="margin:10px 0 0 0;font-size:.6em;color:grey;">
    New ITEM ROW is added after completing field
    </p>
    </th>
    <th style="width:3%">
    <p ALIGN="LEFT">Expected<br>
    Equipment/<br>
    Staff Duration<br>
    of Use:</b></font></td>
  </tr>

  <tr valign=top>
    <td style="width:4%;text-align:center;"><input name="req_item[]" size=2 maxlength=3 style="text-align:center;border:none;" value=1></td>
    <td style="width:5%">
      <select name="req_item_priority[]">
      <option value=0></option>
      <option value="Sustainment">Sustainment</option>
      <option value="Urgent">Urgent</option>
      <option value="Emergent">Emergent</option>
      </select>
    </td>
    <td style="width:89%;text-align:center;">
    <textarea name="req_item_desc[]" style="width:98%;height:1.1em;"></textarea></td>
    <th style="width:5%">
    <input type=number name="req_item_qty[]" style="width:40px;text-align:center;" onblur="addReqRow()"></td>
    <td style="width:3%">
    <input type=text name="req_item_estdu[]" style="width:90px;text-align:center;" maxlength=40></td>
  </tr>

  <tr valign=top>
    <td style="width:4%;text-align:center;"><input name="req_item[]" size=2 maxlength=3 style="text-align:center;border:none;" value=2></td>
    <td style="width:5%">
      <select name="req_item_priority[]">
      <option value=0></option>
      <option value="Sustainment">Sustainment</option>
      <option value="Urgent">Urgent</option>
      <option value="Emergent">Emergent</option>
      </select>
    </td>
    <td style="width:89%;text-align:center;">
    <textarea name="req_item_desc[]" style="width:98%;height:1.1em;"></textarea></td>
    <th style="width:5%">
    <input type=number name="req_item_qty[]" style="width:40px;text-align:center;" onblur="addReqRow()"></td>
    <td style="width:3%">
    <input type=text name="req_item_estdu[]" style="width:90px;text-align:center;" maxlength=40></td>
  </tr>
  
  <tr valign=top>
    <td style="width:4%;text-align:center;"><input name="req_item[]" size=2 maxlength=3 style="text-align:center;border:none;" value=3></td>
    <td style="width:5%">
      <select name="req_item_priority[]">
      <option value=0></option>
      <option value="Sustainment">Sustainment</option>
      <option value="Urgent">Urgent</option>
      <option value="Emergent">Emergent</option>
      </select>
    </td>
    <td style="text-align:center;width:89%">
    <textarea name="req_item_desc[]" style="width:98%;height:1.1em;"></textarea></td>
    <th style="width:5%">
    <input type=number name="req_item_qty[]" style="width:40px;text-align:center;" onblur="addReqRow()"></td>
    <td style="width:3%">
    <input type=text name="req_item_estdu[]" style="width:90px;text-align:center;" maxlength=40></td>
  </tr>
  </table>
  </td>
  </tr>

  <tr class="lightred">
    <th style="width:4%" rowspan=5>
    <p>R<br>E<br>V<br>I<br>E<br>W</th>
    <td style="font-weight:bold;" colspan=4>
    7. Requesting facility <u>must</u> confirm that these 3 requirements have been met prior to submission of request:
    </td>
  </tr>
  <tr class="lightred">
    <td style="font-size:.8em;font-weight:bold;" colspan=3>
    *<input type=checkbox name="req_confirm_1" value=1>
    Is the resource(s) being requested exhausted or nearly exhausted?</td>
  </tr>
  <tr class="lightred">
    <td style="font-size:.8em;font-weight:bold;" colspan=3>
    *<input type=checkbox name="req_confirm_2" value=1>
    Facility is unable to obtain resources within a reasonable time frame (based upon priority level below) from vendors, contractors, MOU/MOA's or corporate office?</td>
  </tr>
  <tr class="lightred">
    <td style="font-size:.8em;font-weight:bold;"  colspan=3>
    *<input type=checkbox name="req_confirm_3" value=1>
    Facility is unable to obtain resource from other non-traditional sources?</td>
  </tr>
  <tr class="lightred">
    <td colspan=3 style="font-weight:bold">
    <p style="margin:0 0 12px 0">8. COMMAND/MANAGEMENT REVIEW AND VERIFICATION<br>
    <span style="font-size:.9em">(NAME, POSITION, AND SIGNATURE - SIGNATURE INDICATES VERIFICATION OF NEED AND APPROVAL)</span></p>
    &nbsp;&nbsp; *<input type=text name="req_auth" style="width:40%;" maxlength=110 placeholder="Name &amp; Position"> *<input type=text name="req_sig" style="width:15%" placeholder="Signature"></td>
  </tr>
</table>
<div style="text-align:center;font-size:.8em;margin:15px 0 120px 0;padding:3px;" class="lightyelo">
<sup>1</sup> HCF = Health Care Facility&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
<sup>2</sup> Priority: &nbsp;  <b>E</b>mergent = &lt;12 hours <span class="grey">||</span> <b>U</b>rgent = &gt;12 hours <span class="grey">||</span> <b>S</b>ustainment
</div>

</FORM>

</body>
</html>
