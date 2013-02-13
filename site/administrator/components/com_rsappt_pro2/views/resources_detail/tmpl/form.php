<?php
/*
 ****************************************************************
 Copyright (C) 2008-2010 Soft Ventures, Inc. All rights reserved.
 ****************************************************************
 * @package	Appointment Booking Pro - ABPro
 * @copyright	Copyright (C) 2008-2010 Soft Ventures, Inc. All rights reserved.
 * @license	GNU/GPL, see http://www.gnu.org/licenses/gpl-2.0.html
 *
 * ABPro is distributed WITHOUT ANY WARRANTY, or implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * This header must not be removed. Additional contributions/changes
 * may be added to this header as long as no information is deleted.
 *
 ************************************************************
 The latest version of ABPro is available to subscribers at:
 http://www.appointmentbookingpro.com/
 ************************************************************
*/

defined('_JEXEC') or die('Restricted access');

JHTML::_('behavior.tooltip');


	// get data for dropdowns
	$database = &JFactory::getDBO();
	$database->setQuery("SELECT * FROM #__users ORDER BY name" );
	$user_rows = $database -> loadObjectList();
	if ($database -> getErrorNum()) {
		echo $database -> stderr();
		return false;
	}

	$database = &JFactory::getDBO();
	$database->setQuery("SELECT * FROM #__sv_apptpro2_categories ORDER BY name" );
	$cat_rows = $database -> loadObjectList();
	if ($database -> getErrorNum()) {
		echo $database -> stderr();
		return false;
	}
	
	// get resource admins
	if (strlen($this->detail->resource_admins) > 0 ){
		$admins = str_replace("||", ",", $this->detail->resource_admins);
		$admins = str_replace("|", "", $admins);
		//echo $admins;
		//exit;
		$sql = "SELECT id as userid, name as username FROM #__users WHERE ".
  			"id IN (".$admins.")";
		$database->setQuery($sql);
		$admins_rows = $database -> loadObjectList();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
			return false;
		}
	}	

	// get config stuff
	$database = &JFactory::getDBO();
	$sql = 'SELECT * FROM #__sv_apptpro2_config';
	$database->setQuery($sql);
	$apptpro2_config = NULL;
	$apptpro2_config = $database -> loadObject();
	if ($database -> getErrorNum()) {
		echo $database -> stderr();
		return false;
	}
	$div_cal = "";
	if($apptpro2_config->use_div_calendar == "Yes"){
		$div_cal = "'testdiv1'";
	}
	
	$admin_users = "";
	
?>
<div id="testdiv1" style="VISIBILITY: hidden; POSITION: absolute; BACKGROUND-COLOR: white; layer-background-color: white"> </div>
<link href="../components/com_rsappt_pro2/calStyles.css" rel="stylesheet">
<script language="JavaScript" src="../components/com_rsappt_pro2/CalendarPopup.js"></script>
<script language="JavaScript">
	var cal = new CalendarPopup( <?php echo $div_cal ?>);
	cal.setCssPrefix("TEST");
	cal.setWeekStartDay(<?php echo $apptpro2_config->popup_week_start_day ?>);
</script>
<script language="javascript">
	function doAddUser(){
		var userid = document.getElementById("users").value;
		var admin_users = document.getElementById("resource_admins_id").value;
		var x = document.getElementById("admins");
		for (i=0;i<x.length;i++){
			if(x[i].value == userid) {
				alert("Already selected");
				return false;
			}			
		}
	
		var opt = document.createElement("option");
        // Add an Option object to Drop Down/List Box
        document.getElementById("admins").options.add(opt); 
        opt.text = document.getElementById("users").options[document.getElementById("users").selectedIndex].text;
        opt.value = document.getElementById("users").options[document.getElementById("users").selectedIndex].value;
		admin_users = admin_users + "|" + userid + "|";
		document.getElementById("resource_admins_id").value = admin_users;
	}

	function doRemoveUser(){
		if(document.getElementById("admins").selectedIndex == -1){
			alert("No Administrator selected for Removal");
			return false;
		}
		var user_to_go = document.getElementById("admins").options[document.getElementById("admins").selectedIndex].value;
		document.getElementById("admins").remove(document.getElementById("admins").selectedIndex);
		
		var admin_users = document.getElementById("resource_admins_id").value;

		admin_users = admin_users.replace("|" + user_to_go + "|", "");
		document.getElementById("resource_admins_id").value = admin_users;
	}

	function setHidden(which_day){
		if(document.getElementById('chk'+which_day).checked==true){
			document.getElementById('allow'+which_day).value = "Yes";
		} else {
			document.getElementById('allow'+which_day).value = "No";
		}
		// ensure at least one day is checked
		if(document.getElementById('chkSunday').checked==false 
			&& document.getElementById('chkMonday').checked==false
			&& document.getElementById('chkTuesday').checked==false
			&& document.getElementById('chkWednesday').checked==false
			&& document.getElementById('chkThursday').checked==false
			&& document.getElementById('chkFriday').checked==false
			&& document.getElementById('chkSaturday').checked==false){
			alert("You cannot un-check ALL days, you must allow bookings on at least one day.");
			document.getElementById('chk'+which_day).checked=true
		}			
		return true;
	}
 
	function set_disable_before_radios(){
		if(document.getElementById('disable_dates_before').value == "Tomorrow"){
			document.getElementById('disable_dates_before_tomorrow').checked = true;
		} else {
			document.getElementById('disable_dates_before_specific').checked = true;
		}
	}
	
	function set_disable_after_radios(){
		if(document.getElementById('disable_dates_after').value == "Not Set"){
			document.getElementById('disable_dates_after_notset').checked = true;
		} else {
			document.getElementById('disable_dates_after_specific').checked = true;
		}
	}

	function setTomorrow(){
		document.getElementById('disable_dates_before').value = "Tomorrow";
	}

	function setToday(){
		document.getElementById('disable_dates_before').value = "Today";
	}

	function setNotSet(){
		document.getElementById('disable_dates_after').value = "Not Set";
	}
	
	function setAfterXDays(){
		document.getElementById('disable_dates_after').value = "XDays";
	}

	function setBeforeXDays(){
		document.getElementById('disable_dates_before').value = "XDays";
	}

</script>
<script language="javascript">
function submitbutton(pressbutton) {

   	if (pressbutton == 'save'){
		if(document.getElementById("name").value == ""){
			alert("Name is required");
		} else {
			submitform(pressbutton);
		}
	} else {
		submitform(pressbutton);
	}		
}
</script>

<form action="<?php echo JRoute::_($this->request_url) ?>" method="post" name="adminForm" id="adminForm">
<input type="hidden" name="access" value="registered_only" />
<input type="hidden" size="2" maxsize="3" name="max_seats" value="<?php echo $this->detail->max_seats; ?>"/>
<input type="hidden" name="non_work_day_hide" value="Yes">
<input type="hidden" size="2" maxlength="2" name="min_lead_time" id="min_lead_time" class="sv_apptpro2_request_text" value="<?php echo $this->detail->min_lead_time; ?>"/>
<link href="../components/com_rsappt_pro2/sv_apptpro.css" rel="stylesheet">
<fieldset class="adminform">
<?php echo JText::_('RS1_ADMIN_SCRN_RES_INTRO');?><hr />
<table border="0" cellpadding="4" cellspacing="5">
  <tr class="admin_detail_row1">
    <td><?php echo JText::_('RS1_ADMIN_SCRN_RES_ID');?></td>
    <td colspan="2"><?php echo $this->detail->id_resources; ?>&nbsp;</td>
  </tr>
  <tr class="admin_detail_row0">
    <td><?php echo JText::_('RS1_ADMIN_SCRN_RES_NAME');?></td>
    <td><input type="text" size="40" maxsize="50" name="name" id="name" value="<?php echo stripslashes($this->detail->name); ?>" /></td>
    <td>&nbsp;</td>
  </tr>
  <tr class="admin_detail_row1">
    <td><?php echo JText::_('RS1_ADMIN_SCRN_RES_DESC');?></td>
    <td><input type="text" size="60" maxsize="80" name="description" value="<?php echo stripslashes($this->detail->description); ?>" /></td>
    <td><?php echo JText::_('RS1_ADMIN_SCRN_RES_DESC_HELP');?></td>
  </tr>
  <!--<tr class="admin_detail_row0">
    <td><?php echo JText::_('RS1_ADMIN_SCRN_RES_ACCESS');?></td>
    <td><select name="access" >
      <option value="everyone" <?php if($this->detail->access == "" or $this->detail->access == "everyone"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_RES_ACCESS_EVERYONE');?></option>
      <option value="registered_only" <?php if($this->detail->access == "registered_only"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_RES_ACCESS_REGISTERED');?></option>
      <option value="public_only" <?php if($this->detail->access == "public_only"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_RES_ACCESS_PUBLIC');?></option>
    </select>
      &nbsp;</td>
    <td><?php echo JText::_('RS1_ADMIN_SCRN_RES_ACCESS_HELP');?>&nbsp;</td>
  </tr>-->
  <tr class="admin_detail_row1">
    <td><?php echo JText::_('RS1_ADMIN_SCRN_RES_CATEGORY');?></td>
    <td><select name="category_id" >
      <option value="0" ><?php echo JText::_('RS1_ADMIN_SCRN_RES_CATEGORY_SEL');?></option>
      <?php
				$k = 0;
				for($i=0; $i < count( $cat_rows ); $i++) {
				$cat_row = $cat_rows[$i];
				?>
      <option value="<?php echo $cat_row->id_categories; ?>"  <?php if($this->detail->category_id == $cat_row->id_categories){echo " selected='selected' ";} ?>><?php echo stripslashes($cat_row->name); ?></option>
      <?php $k = 1 - $k; 
				} ?>
    </select>
      &nbsp;</td>
    <td><?php echo JText::_('RS1_ADMIN_SCRN_RES_CATEGORY_HELP');?>&nbsp;</td>
  </tr>
  <tr class="admin_detail_row0">
    <td><?php echo JText::_('RS1_ADMIN_SCRN_RES_COST');?></td>
    <td><input type="text" size="20" maxsize="20" name="cost" value="<?php echo $this->detail->cost; ?>" />
      &nbsp;&nbsp;</td>
    <td><?php echo JText::_('RS1_ADMIN_SCRN_RES_COST_HELP');?></td>
  </tr>
  <tr class="admin_detail_row1">
    <td ><?php echo JText::_('RS1_ADMIN_SCRN_RES_RATE');?></td>
    <td ><input type="text" size="8" maxsize="10" name="rate" value="<?php echo $this->detail->rate; ?>" />
      &nbsp;<?php echo JText::_('RS1_ADMIN_SCRN_RES_RATE_UNIT');?>&nbsp;
      <select name="rate_unit">
        <option value="Hour" <?php if($this->detail->rate_unit == "Hour"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_RES_RATE_HOUR');?></option>
        <option value="Flat" <?php if($this->detail->rate_unit == "Flat"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_RES_RATE_BOOKING');?></option>
      </select></td>
    <td><?php echo JText::_('RS1_ADMIN_SCRN_RES_RATE_HELP');?></td>
  </tr>
  <tr class="admin_detail_row1">
    <td><?php echo JText::_('RS1_ADMIN_SCRN_RES_PAYPAL');?></td>
    <td><input type="text" size="40" maxsize="50" name="paypal_account" id="paypal_account" value="<?php echo $this->detail->paypal_account; ?>" /></td>
    <td><?php echo JText::_('RS1_ADMIN_SCRN_RES_PAYPAL_HELP');?>&nbsp;</td>
  </tr>
  <tr class="admin_detail_row0">
    <td valign="top"><?php echo JText::_('RS1_ADMIN_SCRN_RES_EMAILTO');?></td>
    <td valign="top"><input type="text" size="60" maxsize="200" name="resource_email" value="<?php echo $this->detail->resource_email; ?>" />
      <br /></td>
    <td><?php echo JText::_('RS1_ADMIN_SCRN_RES_EMAILTO_HELP');?></td>
  </tr>
      <tr  class="admin_detail_row0" >
      <td><?php echo JText::_('RS1_ADMIN_SCRN_RES_SEND_ICS');?> </td>
      <td><select name="send_ics">
          <option value="Yes" <?php if($this->detail->send_ics == "Yes"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_YES');?></option>
          <option value="No" <?php if($this->detail->send_ics == "No"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_NO');?></option>
        </select></td>
      <td><?php echo JText::_('RS1_ADMIN_SCRN_RES_SEND_ICS_HELP');?></td>
    </tr>    

  <tr class="admin_detail_row0">
    <td valign="top"><?php echo JText::_('RS1_ADMIN_SCRN_RES_SMS_PHONE');?></td>
    <td valign="top"><input type="text" size="60" maxsize="200" name="sms_phone" value="<?php echo $this->detail->sms_phone; ?>"/></td>
    <td><?php echo JText::_('RS1_ADMIN_SCRN_RES_SMS_PHONE_HELP');?></td>
  </tr>
     <tr  class="admin_detail_row1" >
      <td><?php echo JText::_('RS1_ADMIN_SCRN_RES_DUPES');?> </td>
      <td><select name="prevent_dupe_bookings">
          <option value="Global" <?php if($this->detail->prevent_dupe_bookings == "Global"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_RES_DUPES_GLOBAL');?></option>
          <option value="Yes" <?php if($this->detail->prevent_dupe_bookings == "Yes"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_YES');?></option>
          <option value="No" <?php if($this->detail->prevent_dupe_bookings == "No"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_NO');?></option>
        </select>
      &nbsp;&nbsp; <?php echo JText::_('RS1_ADMIN_SCRN_RES_MAX_DUPES');?> 
      <input type="text" name="max_dupes" id="max_dupes" size="2" maxlength="4" align="right" value="<?php echo $this->detail->max_dupes; ?>" /></td>
      <td><?php echo JText::_('RS1_ADMIN_SCRN_RES_DUPES_HELP');?></td>
    </tr>    
  <tr class="admin_detail_row1">
    <td valign="top"><?php echo JText::_('RS1_ADMIN_SCRN_MAX_SEATS');?></td>
    <td valign="top"><input type="text" size="2" maxsize="3" name="max_seats" value="<?php echo $this->detail->max_seats; ?>"/></td>
    <td><?php echo JText::_('RS1_ADMIN_SCRN_MAX_SEATS_HELP');?></td>
  </tr>
  <tr class="admin_detail_row0" >
    <td><?php echo JText::_('RS1_ADMIN_SCRN_RES_DEF_CAL_CAT');?></td>
    <td><input type="text" size="25" maxsize="25" name="default_calendar_category" value="<?php echo $this->detail->default_calendar_category; ?>" />
      &nbsp;<?php echo JText::_('RS1_ADMIN_SCRN_RES_DEF_CAL_CAT_HINT');?></td>
    <td><?php echo JText::_('RS1_ADMIN_SCRN_RES_DEF_CAL_CAT_HELP');?></td>
  </tr>
  <tr class="admin_detail_row0" >
    <td><?php echo JText::_('RS1_ADMIN_SCRN_RES_DEF_CAL_CAL');?></td>
    <td><input type="text" size="25" maxsize="25" name="default_calendar" value="<?php echo $this->detail->default_calendar; ?>" />
      &nbsp;<?php echo JText::_('RS1_ADMIN_SCRN_RES_DEF_CAL_CAL_HINT');?></td>
    <td><?php echo JText::_('RS1_ADMIN_SCRN_RES_DEF_CAL_CAL_HELP');?></td>
  </tr>
  <tr class="admin_detail_row0" >
    <td><?php echo JText::_('RS1_ADMIN_SCRN_RES_GOOGLE_USER');?></td>
    <td><input type="text" size="35" maxsize="255" name="google_user" value="<?php echo $this->detail->google_user; ?>" />
      &nbsp;<?php echo JText::_('RS1_ADMIN_SCRN_RES_GOOGLE_USER_HINT');?></td>
    <td><?php echo JText::_('RS1_ADMIN_SCRN_RES_GOOGLE_USER_HELP');?></td>
  </tr>
  <tr class="admin_detail_row0" >
    <td><?php echo JText::_('RS1_ADMIN_SCRN_RES_GOOGLE_PASSWORD');?></td>
    <td><input type="password" size="35" maxsize="255" name="google_password" value="<?php echo $this->detail->google_password; ?>" />
      &nbsp;<?php echo JText::_('RS1_ADMIN_SCRN_RES_GOOGLE_USER_HINT');?></td>
    <td><?php echo JText::_('RS1_ADMIN_SCRN_RES_GOOGLE_PASSWORD_HELP');?></td>
  </tr>
  <tr class="admin_detail_row0" >
    <td><?php echo JText::_('RS1_ADMIN_SCRN_RES_GOOGLE_CAL_ID');?></td>
    <td><input type="text" size="50" maxsize="255" name="google_default_calendar_name" value="<?php echo $this->detail->google_default_calendar_name; ?>" />
      &nbsp;<?php echo JText::_('RS1_ADMIN_SCRN_RES_GOOGLE_CAL_ID_HINT');?></td>
    <td><?php echo JText::_('RS1_ADMIN_SCRN_RES_GOOGLE_CAL_ID_HELP');?></td>
  </tr>

  <tr class="admin_detail_row1">
    <td><?php echo JText::_('RS1_ADMIN_SCRN_RES_ADMINS');?></td>
    <td><table width="95%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="33%"><select name="users" id="users">
          <?php
			$k = 0;
			for($i=0; $i < count( $user_rows ); $i++) {
			$user_row = $user_rows[$i];
			?>
          <option value="<?php echo $user_row->id; ?>"><?php echo $user_row->name; ?></option>
          <?php $k = 1 - $k; 
			} ?>
        </select></td>
        <td width="34%" valign="top" align="center"><input type="button" name="btnAddUser" id="btnAddUser" size="10" value="<?php echo JText::_('RS1_ADMIN_SCRN_RES_ADMINS_ADD');?>" onclick="doAddUser()" />
          <br />
          &nbsp;<br />
          <input type="button" name="btnRemoveUser" id="btnRemoveUser" size="10"  onclick="doRemoveUser()" value="<?php echo JText::_('RS1_ADMIN_SCRN_RES_ADMINS_REMOVE');?>" /></td>
        <td width="33%"><select name="admins" id="admins" size="4" >
          <?php
			$k = 0;
			for($i=0; $i < count( $admins_rows ); $i++) {
			$admins_row = $admins_rows[$i];
			?>
          <option value="<?php echo $admins_row->userid; ?>"><?php echo $admins_row->username; ?></option>
          <?php 
				$admin_users = $admin_users."|".$admins_row->userid."|";
				$k = 1 - $k; 
			} ?>
        </select></td>
      </tr>
    </table></td>
    <td valign="top"><?php echo JText::_('RS1_ADMIN_SCRN_RES_ADMINS_HELP');?></td>
  </tr>
  <tr class="admin_detail_row0">
    <td><?php echo JText::_('RS1_ADMIN_SCRN_RES_TIMESLOTS');?></td>
    <td ><select name="timeslots">
      <option value="Global" <?php if($this->detail->timeslots == "Global"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_RES_USEGLOBAL');?></option>
      <option value="Specific" <?php if($this->detail->timeslots == "Specific"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_RES_SPEC');?></option>
    </select></td>
    <td valign="top"><?php echo JText::_('RS1_ADMIN_SCRN_RES_TIMESLOTS_HELP');?></td>
  </tr>
  <tr class="admin_detail_row1">
    <td width="17%"><?php echo JText::_('RS1_ADMIN_SCRN_RES_BOOKING_DAYS');?></td>
    <td width="38%" valign="bottom"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr align="center">
        <td><?php echo JText::_('RS1_ADMIN_SCRN_SUN');?></td>
        <td><?php echo JText::_('RS1_ADMIN_SCRN_MON');?></td>
        <td><?php echo JText::_('RS1_ADMIN_SCRN_TUE');?></td>
        <td><?php echo JText::_('RS1_ADMIN_SCRN_WED');?></td>
        <td><?php echo JText::_('RS1_ADMIN_SCRN_THU');?></td>
        <td><?php echo JText::_('RS1_ADMIN_SCRN_FRI');?></td>
        <td><?php echo JText::_('RS1_ADMIN_SCRN_SAT');?></td>
        <td>&nbsp;</td>
      </tr>
      <tr align="center">
        <td><input type="checkbox" name="chkSunday" id="chkSunday" onclick="setHidden('Sunday');"  <?php if($this->detail->allowSunday == "Yes"){echo "checked";} ?>/></td>
        <td><input type="checkbox" name="chkMonday" id="chkMonday" onclick="setHidden('Monday');" <?php if($this->detail->allowMonday == "Yes"){echo "checked";} ?>/></td>
        <td><input type="checkbox" name="chkTuesday" id="chkTuesday" onclick="setHidden('Tuesday');" <?php if($this->detail->allowTuesday == "Yes"){echo "checked";} ?>/></td>
        <td><input type="checkbox" name="chkWednesday" id="chkWednesday" onclick="setHidden('Wednesday');" <?php if($this->detail->allowWednesday == "Yes"){echo "checked";} ?>/></td>
        <td><input type="checkbox" name="chkThursday" id="chkThursday" onclick="setHidden('Thursday');" <?php if($this->detail->allowThursday == "Yes"){echo "checked";} ?>/></td>
        <td><input type="checkbox" name="chkFriday" id="chkFriday" onclick="setHidden('Friday');" <?php if($this->detail->allowFriday == "Yes"){echo "checked";} ?>/></td>
        <td><input type="checkbox" name="chkSaturday" id="chkSaturday" onclick="setHidden('Saturday');" <?php if($this->detail->allowSaturday == "Yes"){echo "checked";} ?>/></td>
        <td></td>
      </tr>
    </table>
      <input type="hidden" name="allowSunday" id="allowSunday" value="<?php echo $this->detail->allowSunday?>" />
      <input type="hidden" name="allowMonday" id="allowMonday" value="<?php echo $this->detail->allowMonday?>" />
      <input type="hidden" name="allowTuesday" id="allowTuesday" value="<?php echo $this->detail->allowTuesday?>" />
      <input type="hidden" name="allowWednesday" id="allowWednesday" value="<?php echo $this->detail->allowWednesday?>" />
      <input type="hidden" name="allowThursday" id="allowThursday" value="<?php echo $this->detail->allowThursday?>" />
      <input type="hidden" name="allowFriday" id="allowFriday" value="<?php echo $this->detail->allowFriday?>" />
      <input type="hidden" name="allowSaturday" id="allowSaturday" value="<?php echo $this->detail->allowSaturday?>" /></td>
    <td valign="top"><?php echo JText::_('RS1_ADMIN_SCRN_RES_BOOKING_DAYS_HELP');?></td>
  </tr>
  <tr class="admin_detail_row1">
    <td ><?php echo JText::_('RS1_ADMIN_SCRN_RES_NON_WORK_HIDE');?></td>
    <td ><select name="non_work_day_hide">
      <option value="No" <?php if($this->detail->non_work_day_hide == "No"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_NO');?></option>
      <option value="Yes" <?php if($this->detail->non_work_day_hide == "Yes"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_YES');?></option>
    </select></td>
    <td><?php echo JText::_('RS1_ADMIN_SCRN_RES_NON_WORK_HIDE_HELP');?></td>
  </tr>
  <tr class="admin_detail_row1">
    <td valign="top"><?php echo JText::_('RS1_ADMIN_SCRN_RES_NON_WORK_MESSAGE');?></td>
    <td valign="top"><input type="text" size="60" maxsize="255" name="non_work_day_message" value="<?php echo $this->detail->non_work_day_message; ?>" />
      <br /></td>
    <td><?php echo JText::_('RS1_ADMIN_SCRN_RES_NON_WORK_MESSAGE_HELP');?></td>
  </tr>
  <tr class="admin_detail_row0">
    <td><?php echo JText::_('RS1_ADMIN_SCRN_RES_MIN_LEAD');?></td>
    <td ><input type="text" size="2" maxlength="2" name="min_lead_time" id="min_lead_time" class="sv_apptpro2_request_text" 
      value="<?php echo $this->detail->min_lead_time; ?>"/>
      &nbsp; <?php echo JText::_('RS1_ADMIN_SCRN_RES_MIN_LEAD_UNITS');?></td>
    <td valign="top"><?php echo JText::_('RS1_ADMIN_SCRN_RES_MIN_LEAD_HELP');?></td>
  </tr>

  <tr class="admin_detail_row1">
    <td><?php echo JText::_('RS1_ADMIN_SCRN_RES_DISABLE_BEFORE');?></td>
    <td><table border="0" width="100%" cellpadding="0" cellspacing="0">
      <tr>
        <td><input type="radio" name="rdo_disable_dates_before" id="disable_dates_before_today" value="disable_dates_before_today"
      	<?php echo ($this->detail->disable_dates_before == "Today" ? "checked='checked'" : "");?> onclick="setToday();" />
          <?php echo JText::_('RS1_ADMIN_SCRN_RES_TODAY');?>&nbsp;</td>
      </tr>
      <tr>
        <td><input type="radio" name="rdo_disable_dates_before" id="disable_dates_before_tomorrow" value="disable_dates_before_tomorrow"
      	<?php echo ($this->detail->disable_dates_before == "Tomorrow" ? "checked='checked'" : "");?> onclick="setTomorrow();" />
          <?php echo JText::_('RS1_ADMIN_SCRN_RES_TOMORROW');?>&nbsp;</td>
      </tr>
      <tr>
        <td><input type="radio" name="rdo_disable_dates_before" id="disable_dates_before_xdays" value="disable_dates_before_xdays"
      	<?php echo ($this->detail->disable_dates_before == "XDays" ? "checked='checked'" : "");?> onclick="setBeforeXDays();" />
          <input type="text" size="2" name="disable_dates_before_days" id="disable_dates_before_days" value="<?php echo $this->detail->disable_dates_before_days?>" />
          <?php echo JText::_('RS1_ADMIN_SCRN_RES_DAYS_FROM_NOW');?>&nbsp;</td>
      </tr>
      <tr>
        <td><input type="radio" name="rdo_disable_dates_before" id="disable_dates_before_specific" value="disable_dates_before_specific" 
        <?php echo (($this->detail->disable_dates_before != "Tomorrow" AND $this->detail->disable_dates_before != "Today" AND $this->detail->disable_dates_before != "XDays")? "checked='checked'" : "");?>/>
          <?php echo JText::_('RS1_ADMIN_SCRN_RES_SPEC_DATE');?>
          <input type="text" name="disable_dates_before" id="disable_dates_before" size="10" readonly="readonly" value="<?php echo $this->detail->disable_dates_before; ?>" 
      	onchange="set_disable_before_radios();" />
          <a href="#" id="anchor1" onclick="cal.select(document.forms['adminForm'].disable_dates_before,'anchor1','yyyy-MM-dd'); return false;"
					 name="anchor1"><img height="15" hspace="2" src="../components/com_rsappt_pro2/icon_cal.gif" width="16" border="0" /></a>&nbsp;</td>
      </tr>
    </table></td>
    <td valign="top"><?php echo JText::_('RS1_ADMIN_SCRN_RES_DISABLE_BEFORE_HELP');?></td>
  </tr>
  <tr class="admin_detail_row0">
    <td><?php echo JText::_('RS1_ADMIN_SCRN_RES_DISABLE_AFTER');?></td>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td><input type="radio" name="rdo_disable_dates_after" id="disable_dates_after_notset" value="disable_dates_after_notset" 
      		<?php echo ($this->detail->disable_dates_after == "Not Set" ? "checked='checked'" : "");?> onclick="setNotSet();"/>
          <?php echo JText::_('RS1_ADMIN_SCRN_RES_NOT_SET');?>&nbsp;</td>
      </tr>
      <tr>
        <td><input type="radio" name="rdo_disable_dates_after" id="disable_dates_after_xdays" value="disable_dates_after_xdays"
	      	<?php echo ($this->detail->disable_dates_after == "XDays" ? "checked='checked'" : "");?> onclick="setAfterXDays();" />
          <input type="text" size="2" name="disable_dates_after_days" id="disable_dates_after_days" value="<?php echo $this->detail->disable_dates_after_days?>" />
          <?php echo JText::_('RS1_ADMIN_SCRN_RES_DAYS_FROM_NOW');?>&nbsp;</td>
      </tr>
      <tr>
        <td><input type="radio" name="rdo_disable_dates_after" id="disable_dates_after_specific" value="disable_dates_after_specific"
			<?php echo (($this->detail->disable_dates_after != "Not Set" && $this->detail->disable_dates_after != "XDays") ? "checked='checked'" : "");?> />
          <?php echo JText::_('RS1_ADMIN_SCRN_RES_SPEC_DATE');?>
          <input type="text" name="disable_dates_after" id="disable_dates_after"size="10" readonly="readonly"  
                value="<?php echo $this->detail->disable_dates_after; ?>"  onchange="set_disable_after_radios();"/>
          <a href="#" id="anchor2" onclick="cal.select(document.forms['adminForm'].disable_dates_after,'anchor2','yyyy-MM-dd'); return false;"
					 name="anchor2"><img height="15" hspace="2" src="../components/com_rsappt_pro2/icon_cal.gif" width="16" border="0" /></a>&nbsp;</td>
      </tr>
    </table>
    </td>
	  <td valign="top"><?php echo JText::_('RS1_ADMIN_SCRN_RES_DISABLE_AFTER_HELP');?></td>
  </tr>
  <tr class="admin_detail_row1">
    <td colspan="3"><?php echo JText::_('RS1_ADMIN_SCRN_RES_DISABLE_DATES');?></td>
  </tr>
  <tr  class="admin_detail_row0">
    <td><?php echo JText::_('RS1_ADMIN_SCRN_DISPLAY_ORDER');?></td>
    <td><input type="text" size="5" maxsize="2" name="ordering" value="<?php echo $this->detail->ordering; ?>" />
      &nbsp;&nbsp;</td>
    <td><?php echo JText::_('RS1_ADMIN_SCRN_DISPLAY_ORDER_HELP');?></td>
  </tr>
  <tr class="admin_detail_row1">
    <td ><?php echo JText::_('RS1_ADMIN_SCRN_RES_PUBLISHED');?></td>
    <td colspan="2"><select name="published">
      <option value="0" <?php if($this->detail->published == "0"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_NO');?></option>
      <option value="1" <?php if($this->detail->published == "1"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_YES');?></option>
    </select></td>
  </tr>
</table>
</fieldset>
  <input type="hidden" name="id_resources" value="<?php echo $this->detail->id_resources; ?>" />
  <input type="hidden" name="task" value="" />
  <input type="hidden" name="controller" value="resources_detail" />
  <input type="hidden" name="resource_admins" id="resource_admins_id" value="<?php echo $admin_users; ?>" />
  <br />
  <span style="font-size:10px"> Appointment Booking Pro Ver. 2.0 - Copyright 2008-20<?php echo date("y");?> - <a href='http://www.softventures.com' target="_blank">Soft Ventures, Inc.</a></span>
</form>
