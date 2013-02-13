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

	setSessionStuff("resource");

	$showform= true;
	$listpage = JRequest::getVar('listpage', 'list');
//	if($listpage == 'list'){
//		$savepage = 'res_save';
//	} else {
//		$savepage = 'res_save_adv_admin';
//	}
//	$current_tab = JRequest::getVar('current_tab', '');


	$session = &JSession::getInstance($handler, $options);

	$resource = JRequest::getString( 'id', '' );
	$itemid = JRequest::getString( 'Itemid', '' );
	$option = JRequest::getString( 'option', '' );
	
	$user =& JFactory::getUser();
	if($user->guest){
		echo "<font color='red'>".JText::_('RS1_ADMIN_SCRN_NO_LOGIN')."</font>";
		$showform = false;
	} else {
		$database = &JFactory::getDBO(); 
		// get resource details
		$user =& JFactory::getUser();
		
		if($this->detail->id_resources == 0){
			// new add default res admin
			$this->detail->resource_admins = "|".$user->id."|";
		}
		
		// get config stuff
		$database = &JFactory::getDBO();
		$sql = 'SELECT * FROM #__sv_apptpro2_config';
		$database->setQuery($sql);
		$apptpro_config = NULL;
		$apptpro_config = $database -> loadObject();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
			return false;
		}

		// get categories 
		$sql = "SELECT * FROM #__sv_apptpro2_categories WHERE published = 1 Order By ordering ";
		$database->setQuery($sql);
		$cat_rows = $database -> loadObjectList();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
			return false;
		}
		
		
		$div_cal = "";
		if($apptpro_config->use_div_calendar == "Yes"){
			$div_cal = "'testdiv1'";
		}

	}	
	
?>
<?php if($showform){?>

<div id="testdiv1" style="VISIBILITY: hidden; POSITION: absolute; BACKGROUND-COLOR: white; layer-background-color: white"> </div>
<link href="./components/com_rsappt_pro2/sv_apptpro.css" rel="stylesheet">
<link href="./components/com_rsappt_pro2/calStyles.css" rel="stylesheet">
<script language="JavaScript" src="./components/com_rsappt_pro2/script.js"></script>
<script language="JavaScript" src="./components/com_rsappt_pro2/CalendarPopup.js"></script>
<script language="JavaScript">
	var cal = new CalendarPopup(<?php echo $div_cal ?>);
	cal.setCssPrefix("TEST");
	cal.setWeekStartDay(<?php echo $apptpro_config->popup_week_start_day ?>);

	function doOnload(){
		if(document.getElementById('res_id').innerHTML == ''){
			// new resource, setup some defaults..
			document.getElementById('disable_dates_before_tomorrow').checked=true;
			document.getElementById('min_lead_time').value="0";
			setTomorrow();
			document.getElementById('disable_dates_after_notset').checked=true;
			setNotSet();
			document.getElementById('timeslots').selectedIndex=1;
			//document.getElementById('prevent_dupe_bookings').selectedIndex=0;
			document.getElementById('max_seats').value='1';
			document.getElementById('display_order').value='1';
			document.getElementById('resource_admins').value="|"+document.getElementById('user').value+"|";
			document.getElementById('default_calendar_category').value="General";
		}
	}
		
	function doCancel(){
//		document.adminForm.action="index.php?option=com_rsappt_pro2&page=<?php echo $listpage;?>&Itemid=<?php echo $itemid;?>";
//		document.adminForm.submit(); 		
		submitform("cancel");
	}		
	
	function doSave(){
		if(document.getElementById('name').value == ""){
			alert('<?php echo JText::_('RS1_ADMIN_SCRN_NAME_ERR');?>');
			return(false);
		}
//		document.adminForm.action="index.php?option=com_rsappt_pro2&page=<?php echo $savepage;?>&Itemid=<?php echo $itemid;?>";
//		document.adminForm.submit();		
		submitform("save_res_detail");
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
	
	</script>
<form action="<?php echo JRoute::_($this->request_url) ?>" method="post" name="adminForm" id="adminForm" class="adminForm">
<link href="../components/com_rsappt_pro2/sv_apptpro.css" rel="stylesheet">
<table width="100%" >
    <tr>
      <td align="left" colspan="2"> <h3><?php echo JText::_('RS1_ADMIN_SCRN_TITLE').JText::_('RS1_ADMIN_SCRN_RESOURCE_DETAIL_TITLE');?></h3></td>
    </tr>
</table>
<table border="0" cellpadding="4" cellspacing="0">
   <tr>
      <td colspan="3" align="right" height="40px" 
      style="background-color:#FFFFCC; border-top:solid #333333 1px;border-bottom:solid #333333 1px">
      <a href="#" onclick="doSave()"><?php echo JText::_('RS1_ADMIN_SCRN_BTN_SAVE');?></a>
      &nbsp;|&nbsp;&nbsp;<a href="#" onclick="doCancel()"><?php echo JText::_('RS1_ADMIN_SCRN_BTN_CANCEL');?></a>&nbsp;&nbsp;</td>
    </tr>
    <tr class="admin_detail_row0">
      <td><?php echo JText::_('RS1_ADMIN_SCRN_RES_ID');?></td>
      <td><span id="res_id"><?php echo $this->detail->id_resources; ?></span>&nbsp;</td>
    </tr>
    <?php if(count($cat_rows) > 0){ ?>
    <tr class="admin_detail_row1">
      <td><?php echo JText::_('RS1_INPUT_SCRN_RESOURCE_CATEGORIES');?></td>
      <td><select name="category_id" id="category_id" class="sv_apptpro2_request_text" >
        <option value="0" ><?php echo JText::_('RS1_INPUT_SCRN_RESOURCE_CATEGORIES_PROMPT');?></option>
        <?php
				$k = 0;
				for($i=0; $i < count( $cat_rows ); $i++) {
				$cat_row = $cat_rows[$i];
				?>
        <option value="<?php echo $cat_row->id_categories; ?>"  <?php if($this->detail->category_id == $cat_row->id_categories){echo " selected='selected' ";} ?>><?php echo JText::_(stripslashes($cat_row->name)); ?></option>
        <?php $k = 1 - $k; 
				} ?>
      </select>        &nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <?php } else { ?>
      <input type="hidden" name="category_id" value="<?php echo $this->detail->category_id; ?>" />
    <?php } ?>
    <tr class="admin_detail_row0">
      <td><?php echo JText::_('RS1_ADMIN_SCRN_RES_NAME');?></td>
      <td><input type="text" size="40" maxsize="50" name="name" id="name" class="sv_apptpro2_request_text" value="<?php echo stripslashes($this->detail->name); ?>" /></td>
      <td>&nbsp;</td>
    </tr>
    <tr class="admin_detail_row1">
      <td><?php echo JText::_('RS1_ADMIN_SCRN_RES_DESC');?> </td>
      <td><input type="text" size="40" maxsize="80" name="description" class="sv_apptpro2_request_text" value="<?php echo stripslashes($this->detail->description); ?>" /></td>
      <td><?php echo JText::_('RS1_ADMIN_SCRN_RES_DESC_HELP');?></td>
    </tr>
    <tr class="admin_detail_row0">
      <td><?php echo JText::_('RS1_ADMIN_SCRN_RES_ACCESS');?></td>
      <td><select name="access" >
          <option value="everyone" <?php if($this->detail->access == "" or $this->detail->access == "everyone"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_RES_ACCESS_EVERYONE');?></option>
          <option value="registered_only" <?php if($this->detail->access == "registered_only"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_RES_ACCESS_REGISTERED');?></option>
          <option value="public_only" <?php if($this->detail->access == "public_only"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_RES_ACCESS_PUBLIC');?></option>
	      </select>&nbsp;</td>
      <td><?php echo JText::_('RS1_ADMIN_SCRN_RES_ACCESS_HELP');?>&nbsp;</td>
    </tr>
    <tr class="admin_detail_row1">
      <td><?php echo JText::_('RS1_ADMIN_SCRN_RES_COST');?></td>
      <td><input type="text" size="20" maxsize="20" name="cost" class="sv_apptpro2_request_text" value="<?php echo $this->detail->cost; ?>" />
&nbsp;&nbsp;         </td>
      <td width="50%"><?php echo JText::_('RS1_ADMIN_SCRN_RES_COST_HELP');?></td>
    </tr>
    <tr class="admin_detail_row1">
      <td ><?php echo JText::_('RS1_ADMIN_SCRN_RES_RATE');?></td>
      <td ><input type="text" size="8" maxsize="10" name="rate" value="<?php echo $this->detail->rate; ?>" />
        &nbsp;&nbsp;&nbsp;<?php echo JText::_('RS1_ADMIN_SCRN_RES_RATE_UNIT');?> <select name="rate_unit">
          <option value="Hour" <?php if($this->detail->rate_unit == "Hour"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_RES_RATE_HOUR');?></option>
          <option value="Flat" <?php if($this->detail->rate_unit == "Flat"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_RES_RATE_BOOKING');?></option>
        </select>        </td>
      <td width="50%"><?php echo JText::_('RS1_ADMIN_SCRN_RES_RATE_HELP');?></td>
    </tr>
    <tr class="admin_detail_row0">
      <td valign="top"><?php echo JText::_('RS1_ADMIN_SCRN_RES_EMAILTO');?></td>
      <td valign="top"><input type="text" size="40" maxsize="200" class="sv_apptpro2_request_text" name="resource_email" value="<?php echo $this->detail->resource_email; ?>" />
        <br /></td>
      <td width="50%"><?php echo JText::_('RS1_ADMIN_SCRN_RES_EMAILTO_HELP');?></td>
    </tr>
<!--    <tr  class="admin_detail_row0" >
      <td><?php echo JText::_('RS1_ADMIN_SCRN_RES_SEND_ICS');?> </td>
      <td><select name="send_ics" class="sv_apptpro2_request_text" >
          <option value="Yes" <?php if($this->detail->send_ics == "Yes"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_YES');?></option>
          <option value="No" <?php if($this->detail->send_ics == "No"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_NO');?></option>
        </select></td>
      <td><?php echo JText::_('RS1_ADMIN_SCRN_RES_SEND_ICS_HELP');?></td>
    </tr>  -->  
    <tr class="admin_detail_row0">
      <td valign="top"><?php echo JText::_('RS1_ADMIN_SCRN_RES_SMS_PHONE');?></td>
      <td valign="top"><input type="text" size="40" maxsize="200" name="sms_phone" class="sv_apptpro2_request_text" value="<?php echo $this->detail->sms_phone; ?>"/></td>
      <td><?php echo JText::_('RS1_ADMIN_SCRN_RES_SMS_PHONE_HELP');?></td>
    </tr>
<!--    <tr  class="admin_detail_row1" >
      <td><?php echo JText::_('RS1_ADMIN_SCRN_RES_DUPES');?> </td>
      <td><select name="prevent_dupe_bookings" id="prevent_dupe_bookings" class="sv_apptpro2_request_text">
          <option value="Global" <?php if($this->detail->prevent_dupe_bookings == "Global"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_GLOBAL');?></option>
          <option value="Yes" <?php if($this->detail->prevent_dupe_bookings == "Yes"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_YES');?></option>
          <option value="No" <?php if($this->detail->prevent_dupe_bookings == "No"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_NO');?></option>
        </select>
      &nbsp;&nbsp; <?php echo JText::_('RS1_ADMIN_SCRN_RES_MAX_DUPES');?> 
      <input type="text" name="max_dupes" id="max_dupes" size="2" maxlength="4" align="right" class="sv_apptpro2_request_text" value="<?php echo $this->detail->max_dupes; ?>" /></td>
      <td><?php echo JText::_('RS1_ADMIN_SCRN_RES_DUPES_HELP');?>      </td>
    </tr>  -->  
    <tr class="admin_detail_row1">
      <td valign="top"><?php echo JText::_('RS1_ADMIN_SCRN_MAX_SEATS');?></td>
      <td valign="top"><input type="text" size="2" maxsize="3" name="max_seats" id="max_seats" value="<?php echo $this->detail->max_seats; ?>"/></td>
      <td><?php echo JText::_('RS1_ADMIN_SCRN_MAX_SEATS_HELP');?></td>
    </tr>
<!--    <tr class="admin_detail_row0" >
      <td>Default Calendar Category:</td>
      <td><input type="text" size="25" maxsize="2" name="default_calendar_category" value="<?php echo $this->detail->default_calendar_category; ?>" />
        &nbsp;&nbsp;</td>
      <td>Enter a default calendar category for this resource. This is only applicable if you are using 'auto-accept' and a 3rd party calendar (JCalPro or JEvents)</td>  
    </tr>
    <tr class="admin_detail_row1">
      <td>Resource Administrators:</td>
      <td>
      <table width="95%" border="0" cellspacing="0" cellpadding="0">
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
            <td width="34%" valign="top" align="center"><input type="button" name="btnAddUser" id="btnAddUser" size="10" value="    Add >>   " onclick="doAddUser()" />
              <br />
              &nbsp;<br />
              <input type="button" name="btnRemoveUser" id="btnRemoveUser" size="10"  onclick="doRemoveUser()" value="<< Remove" /></td>
            <td width="33%">
            <select name="admins" id="admins" size="4" >
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
              </select>            </td>
          </tr>
        </table></td>
    <td valign="top">Select one or more users to be administrators for this resource.  When a resource administrator accesses the front-end control they see only  requests for their resources. A user can administer one or many resources and a resource can have one or many administrators.</td>
    </tr>-->
	<tr class="admin_detail_row0">
	  <td><?php echo JText::_('RS1_ADMIN_SCRN_RES_TIMESLOTS');?></td>
	  <td ><select name="timeslots" id="timeslots" class="sv_apptpro2_request_text">
        <option value="Global" <?php if($this->detail->timeslots == "Global"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_USEGLOBAL');?></option>
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
        <input type="hidden" name="allowSaturday" id="allowSaturday" value="<?php echo $this->detail->allowSaturday?>" />      </td>
      <td width="50%" valign="top"><?php echo JText::_('RS1_ADMIN_SCRN_RES_BOOKING_DAYS_HELP');?></td>
    </tr>
    <tr class="admin_detail_row1">
        <td ><?php echo JText::_('RS1_ADMIN_SCRN_RES_NON_WORK_HIDE');?></td>
        <td >
            <select name="non_work_day_hide" class="sv_apptpro2_request_text">
            <option value="No" <?php if($this->detail->non_work_day_hide == "No"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_NO');?></option>
            <option value="Yes" <?php if($this->detail->non_work_day_hide == "Yes"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_YES');?></option>
            </select>
        </td>
         <td width="50%"><?php echo JText::_('RS1_ADMIN_SCRN_RES_NON_WORK_HIDE_HELP');?></td>
    </tr>  
    <tr class="admin_detail_row1">
      <td valign="top"><?php echo JText::_('RS1_ADMIN_SCRN_RES_NON_WORK_MESSAGE');?></td>
      <td valign="top"><input type="text" size="40" maxsize="255" name="non_work_day_message" value="<?php echo $this->detail->non_work_day_message; ?>" />
        <br /></td>
      <td width="50%"><?php echo JText::_('RS1_ADMIN_SCRN_RES_NON_WORK_MESSAGE_HELP');?></td>
    </tr>
	<tr class="admin_detail_row0">
	  <td><?php echo JText::_('RS1_ADMIN_SCRN_RES_MIN_LEAD');?></td>
	  <td ><input type="text" size="2" maxlength="2" name="min_lead_time" id="min_lead_time" class="sv_apptpro2_request_text" 
      value="<?php echo $this->detail->min_lead_time; ?>"/>&nbsp;<?php echo JText::_('RS1_ADMIN_SCRN_RES_MIN_LEAD_UNITS');?>         
      </td>
	  <td valign="top"><?php echo JText::_('RS1_ADMIN_SCRN_RES_MIN_LEAD_HELP');?>&nbsp;</td>
    </tr>
	<tr class="admin_detail_row1">
	  <td><?php echo JText::_('RS1_ADMIN_SCRN_RES_DISABLE_BEFORE');?></td>
	  <td ><input type="radio" name="rdo_disable_dates_before" id="disable_dates_before_today" value="disable_dates_before_today"
      	<?php echo ($this->detail->disable_dates_before == "Today" ? "checked='checked'" : "");?> onclick="setToday();" /> 
	    <?php echo JText::_('RS1_ADMIN_SCRN_RES_TODAY');?>&nbsp;&nbsp; <br /> 
        <input type="radio" name="rdo_disable_dates_before" id="disable_dates_before_tomorrow" value="disable_dates_before_tomorrow"
      	<?php echo ($this->detail->disable_dates_before == "Tomorrow" ? "checked='checked'" : "");?> onclick="setTomorrow();" /> 
	    <?php echo JText::_('RS1_ADMIN_SCRN_RES_TOMORROW');?>&nbsp;&nbsp;        <br />
        <input type="radio" name="rdo_disable_dates_before" id="disable_dates_before_specific" value="disable_dates_before_specific" 
        <?php echo (($this->detail->disable_dates_before != "Tomorrow" AND $this->detail->disable_dates_before != "Today") ? "checked='checked'" : "");?>/>
        <?php echo JText::_('RS1_ADMIN_SCRN_RES_SPEC_DATE');?>
      <input type="text" name="disable_dates_before" id="disable_dates_before" class="sv_apptpro2_request_text" size="10" readonly="readonly" value="<?php echo $this->detail->disable_dates_before; ?>" 
      	onchange="set_disable_before_radios();" />
      <a href="#" id="anchor1" onclick="cal.select(document.forms['adminForm'].disable_dates_before,'anchor1','yyyy-MM-dd'); return false;"
					 name="anchor1"><img height="15" hspace="2" src="./components/com_rsappt_pro2/icon_cal.gif" width="16" border="0"></a></td>
	  <td valign="top"><?php echo JText::_('RS1_ADMIN_SCRN_RES_DISABLE_BEFORE_HELP');?></td>
    </tr>
	<tr class="admin_detail_row0">
	  <td><?php echo JText::_('RS1_ADMIN_SCRN_RES_DISABLE_AFTER');?></td>
	  <td ><input type="radio" name="rdo_disable_dates_after" id="disable_dates_after_notset" value="disable_dates_after_notset" 
      <?php echo ($this->detail->disable_dates_after == "Not Set" ? "checked='checked'" : "");?> onclick="setNotSet();"/> 
	    <?php echo JText::_('RS1_ADMIN_SCRN_RES_NOT_SET');?>&nbsp;&nbsp;        <br />
        <input type="radio" name="rdo_disable_dates_after" id="disable_dates_after_specific" value="disable_dates_after_specific"
        <?php echo ($this->detail->disable_dates_after != "Not Set" ? "checked='checked'" : "");?> />
        <?php echo JText::_('RS1_ADMIN_SCRN_RES_SPEC_DATE');?> 
        <input type="text" name="disable_dates_after" id="disable_dates_after"size="10" readonly="readonly" class="sv_apptpro2_request_text"  
        	value="<?php echo $this->detail->disable_dates_after; ?>"  onchange="set_disable_after_radios();"/>
        <a href="#" id="anchor2" onclick="cal.select(document.forms['adminForm'].disable_dates_after,'anchor2','yyyy-MM-dd'); return false;"
					 name="anchor2"><img height="15" hspace="2" src="./components/com_rsappt_pro2/icon_cal.gif" width="16" border="0"></a> </td>
	  <td valign="top"><?php echo JText::_('RS1_ADMIN_SCRN_RES_DISABLE_AFTER_HELP');?></td>
    </tr>
	<tr class="admin_detail_row1">
	  <td colspan="3"><?php echo JText::_('RS1_ADMIN_SCRN_RES_DISABLE_DATES');?></td>
    </tr>
	<tr  class="admin_detail_row0">
      <td><?php echo JText::_('RS1_ADMIN_SCRN_DISPLAY_ORDER');?></td>
      <td><input type="text" size="5" maxsize="2" name="ordering" id="ordering" class="sv_apptpro2_request_text" value="<?php echo $this->detail->ordering; ?>" />
        &nbsp;&nbsp;</td>
      <td><?php echo JText::_('RS1_ADMIN_SCRN_DISPLAY_ORDER_HELP');?></td>      
    </tr>
    <tr class="admin_detail_row1">
        <td ><?php echo JText::_('RS1_ADMIN_SCRN_PUBLISHED');?> </td>
        <td colspan="2">
            <select name="published" class="sv_apptpro2_request_text">
            <option value="0" <?php if($this->detail->published == "0"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_NO');?></option>
            <option value="1" <?php if($this->detail->published == "1"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_YES');?></option>
            </select>        </td>
    </tr>  
  </table>
  <input type="hidden" name="resource_admins" id="resource_admins" value="<?php echo $this->detail->resource_admins; ?>" />
  <input type="hidden" name="default_calendar_category" value="<?php echo $this->detail->default_calendar_category; ?>" />


  <input type="hidden" name="id_resources" value="<?php echo $this->detail->id_resources; ?>" />
  <input type="hidden" name="option" value="<?php echo $option; ?>" />
  <input type="hidden" name="controller" value="admin_detail" />
  <input type="hidden" name="id" value="<?php echo $this->user_id; ?>" />
  <input type="hidden" name="task" value="" />
  <input type="hidden" name="current_tab" id="current_tab" value="<?php echo $current_tab; ?>" />
  <input type="hidden" name="user" id="user" value="<?php echo $user->id; ?>" />
  <input type="hidden" name="frompage" value="<?php echo $listpage ?>" />
  <input type="hidden" name="frompage_item" value="<?php echo $itemid ?>" />
  <input type="hidden" name="fromtab" value="<?php echo $this->fromtab ?>" />
  <br />
      <?php if($apptpro_config->hide_logo == 'No'){ ?>
        <span style="font-size:10px"> Appointment Booking Pro Ver. 2.0 - Copyright 2008-20<?php echo date("y");?> - <a href='http://www.softventures.com' target="_blank">Soft Ventures, Inc.</a></span>
      <?php } ?>
</form>
<script>
	doOnload();
</script>
<?php } ?>