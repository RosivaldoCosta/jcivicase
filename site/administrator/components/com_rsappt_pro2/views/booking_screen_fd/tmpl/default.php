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


// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

	JHTML::_('behavior.tooltip');
	jimport( 'joomla.application.helper' );

	$mainframe = JFactory::getApplication();
	$session = &JSession::getInstance($handler, $options);

	$option = JRequest::getString( 'option', '' );
	$user =& JFactory::getUser();
	$itemId = JRequest::getVar('Itemid');

	include_once( JPATH_SITE."/administrator/components/com_rsappt_pro2/sendmail_pro2.php" );
	include_once( JPATH_SITE."/components/com_rsappt_pro2/functions2.php" );

	// -----------------------------------------------------------------------
	// see if we need to switch into single-resource or single-category mode.
	$single_resource_mode = false;
	$single_resource_id = "";
	$single_category_mode = false;
	$single_category_id = "";
	$params =& $mainframe->getPageParameters('com_rsappt_pro2');
	if($params->get('res_or_cat') == 1 && $params->get('passed_id') != ""){
		// single resource mode on, set by menu parameter
		$single_resource_mode = true;
		$single_resource_id = $params->get('passed_id');
		//echo "single resource mode (menu), id=".$single_resource_id;
	}
	
	if(JRequest::getVar('res','')!=""){
		// single resource mode on, set by menu parameter
		$single_resource_mode = true;
		$single_resource_id = JRequest::getVar('res','');
		//echo "single resource mode (querystring), id=".$single_resource_id;
	}

	if($params->get('res_or_cat') == 2 && $params->get('passed_id') != ""){
		// single category mode on, set by menu parameter
		$single_category_mode = true;
		$single_category_id = $params->get('passed_id');
		//echo "single category mode (menu), id=".$single_category_id;
	}

	if(JRequest::getVar('cat','')!=""){
		// single category mode on, set by menu parameter
		$single_category_mode = true;
		$single_category_id = JRequest::getVar('cat','');
		//echo "single category mode (querystring), id=".$single_category_id;
	}
	
	// -----------------------------------------------------------------------
	// get data for dropdownlist
	$database = &JFactory::getDBO(); 

	$andClause = "";
	if(!$single_resource_mode){
		// get resource categories
		$database = &JFactory::getDBO(); 
		if($single_category_mode){
			$andClause .= " AND id = ". $single_category_id;
		} else {
			$andClause .= " AND (parent_category IS NULL OR parent_category = '') ";
		}	
		$sql = 'SELECT * FROM #__sv_apptpro2_categories WHERE published = 1 '.$andClause.' order by ordering';
		$database->setQuery($sql);
		$res_cats = $database -> loadObjectList();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
			logIt($database->getErrorMsg()); 
			return false;
		}
	
		// check for sub-categories
		$sql = 'SELECT count(*) as count FROM #__sv_apptpro2_categories WHERE published = 1 AND (parent_category IS NOT NULL AND parent_category != "") ';
		$database->setQuery($sql);
		$sub_cat_count = $database -> loadObject();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
			logIt($database->getErrorMsg()); 
			return false;
		}
		//echo $sub_cat_count->count;
		
	}
	
	// get resources
	if(count($res_cats) == 0 || $single_resource_mode){
		if($user->guest){
			$andClause = " AND access != 'registered_only' ";
		} else {
			$andClause = " AND access != 'public_only' ";
		}
		if($single_resource_mode){
			$andClause .= " AND id = ". $single_resource_id;
		}
		if($single_category_mode){
			$andClause .= " AND category_id = ".$cat;
		}

		// only resources for which user is res admin
		$andClause .= " AND resource_admins LIKE '%|".$user->id."|%' ";
		
		$sql = '(SELECT 0 as id_resources, \''.JText::_('RS1_GAD_SCRN_RESOURCE_DROPDOWN').'\' as name, \''.JText::_('RS1_GAD_SCRN_RESOURCE_DROPDOWN').'\' as description, 0 as ordering, "" as cost) UNION (SELECT id_resources,name,description,ordering,cost FROM #__sv_apptpro2_resources WHERE published=1 '.$andClause.') ORDER BY ordering';

		//echo $sql;
		$database->setQuery($sql);
		$res_rows = $database -> loadObjectList();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
			return false;
		}
	}
	
	// get config stuff
	$database = &JFactory::getDBO(); 
	$sql = 'SELECT * FROM #__sv_apptpro2_config';
	$database->setQuery($sql);
	$apptpro_config = NULL;
	$apptpro_config = $database -> loadObject();
	if ($database -> getErrorNum()) {
		echo "DB Err: ". $database -> stderr();
		return false;
	}

	// purge stale paypal bookings
	if($apptpro_config->purge_stale_paypal == "Yes"){
		purgeStalePayPalBookings($apptpro_config->minutes_to_stale);
	}

	$gridstarttime = $apptpro_config->def_gad_grid_start;
	$gridendtime = $apptpro_config->def_gad_grid_end;
	
	switch($apptpro_config->gad_grid_start_day){
		case "Today": {
			$grid_date = date("Y-m-d");
			break;
		}
		case "Tomorrow": {
			$grid_date = date("Y-m-d", strtotime("+1 day"));
			break;
		}
		case "Monday": {
			if(date("N") == 1){
				$grid_date = date("Y-m-d");
//			} else if(date("N") == 6 || date("N") == 7 ){
//				// If you are not open weekends and it is saturday or sunday skip to next monday
//				$grid_date = date("Y-m-d", strtotime("next monday"));
			} else {		
				$grid_date = date("Y-m-d", strtotime("previous monday"));
			}
			break;
		}
		case "XDays": {
			$grid_date = date("Y-m-d", strtotime("+".strval($apptpro_config->gad_grid_start_day_days)." day"));
			break;
		}
		default: {
			$grid_date = $apptpro_config->gad_grid_start_day;
			break;
		}
	}
	
	$gridwidth = $apptpro_config->gad_grid_width."px";
	$namewidth = $apptpro_config->gad_name_width."px";
	$mode = "single_day"; 
	//$mode = "single_resource";
	$griddays = intval($apptpro_config->gad_grid_num_of_days);
	if($griddays < 2){
		$griddays = 7;
	}
	

	// get udfs
	$database = &JFactory::getDBO(); 
	$sql = 'SELECT * FROM #__sv_apptpro2_udfs WHERE published=1 AND scope = "" ORDER BY ordering';
	$database->setQuery($sql);
	$udf_rows = $database -> loadObjectList();
	if ($database -> getErrorNum()) {
		echo "DB Err: ". $database -> stderr();
		return false;
	}

	$div_cal = "";
	if($apptpro_config->use_div_calendar == "Yes"){
		$div_cal = "'testdiv1'";
	}

	// get users
	$sql = 'SELECT id,name FROM #__users order by name';
	$database->setQuery($sql);
	$user_rows = $database -> loadObjectList();
	if ($database -> getErrorNum()) {
		echo $database -> stderr();
		return false;
	}

	// check to see if any extras are published, if so show extras line in PayPal totals
	$sql = 'SELECT count(*) as count FROM #__sv_apptpro2_extras WHERE published = 1';
	$database->setQuery($sql);
	$extras_row_count = $database -> loadObject();
	if ($database -> getErrorNum()) {
		echo $database -> stderr();
		logIt($database->getErrorMsg()); 
		return false;
	}
	//echo $extras_row_count->count;
	
	// get resource rates
	$database = &JFactory::getDBO(); 
	$sql = 'SELECT id_resources,rate,rate_unit FROM #__sv_apptpro2_resources';
	$database->setQuery($sql);
	$res_rates = $database -> loadObjectList();
	if ($database -> getErrorNum()) {
		echo $database -> stderr();
		return false;
	}
	$rateArrayString = "<script type='text/javascript'>".
	"var aryRates = {";
	for($i=0; $i<count($res_rates); $i++){
		$rateArrayString = $rateArrayString.$res_rates[$i]->id_resources.":".$res_rates[$i]->rate."";
		if($i<count($res_rates)-1){
			$rateArrayString = $rateArrayString.",";
		}
	}
	$rateArrayString = $rateArrayString."}</script>";
	
	$rate_unitArrayString = "<script type='text/javascript'>".
	"var aryRateUnits = {";
	for($i=0; $i<count($res_rates); $i++){
		$rate_unitArrayString = $rate_unitArrayString.$res_rates[$i]->id_resources.":'".$res_rates[$i]->rate_unit."'";
		if($i<count($res_rates)-1){
			$rate_unitArrayString = $rate_unitArrayString.",";
		}
	}
	$rate_unitArrayString = $rate_unitArrayString."}</script>";
	
	if($apptpro_config->clickatell_show_code == "Yes"){
		// get dialing codes
		$database = &JFactory::getDBO();
		$database->setQuery("SELECT * FROM #__sv_apptpro2_dialing_codes ORDER BY country" );
		$dial_rows = $database -> loadObjectList();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
			return false;
		}
	}
	
	$startdate = JText::_('RS1_INPUT_SCRN_DATE_PROMPT');

	$user =& JFactory::getUser();
	if(!$user->guest){
		// check to see id user is an admin		
		$sql = "SELECT count(*) as count FROM #__sv_apptpro2_resources WHERE published=1 AND ".
			"resource_admins LIKE '%|".$user->id."|%';";
		$database->setQuery($sql);
		$check = NULL;
		$check = $database -> loadObject();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
			return false;
		}
		if($check->count >0){
			$show_admin = true;
		}
//		$name = $user->name; 
//		$email = $user->email;
		$user_id = $user->id;

	} else {
		$show_admin = false;
		$user_id = "";
	}	

	$err = "";


?>

<div id="testdiv1" style="VISIBILITY: hidden; POSITION: absolute; BACKGROUND-COLOR: white; layer-background-color: white; z-index:100;"> </div>
<link href="<?php echo $this->baseurl;?>/components/com_rsappt_pro2/sv_apptpro.css" rel="stylesheet">
<link href="<?php echo $this->baseurl;?>/components/com_rsappt_pro2/calStyles.css" rel="stylesheet">
<script language="JavaScript" src="<?php echo $this->baseurl;?>/components/com_rsappt_pro2/date.js"></script>
<script language="JavaScript" src="<?php echo $this->baseurl;?>/components/com_rsappt_pro2/script.js"></script>
<script language="JavaScript" src="<?php echo $this->baseurl;?>/components/com_rsappt_pro2/CalendarPopup.js"></script>
<script language="JavaScript">
	var cal = new CalendarPopup(<?php echo $div_cal ?>);
	cal.setMonthNames(<?php echo "'".JText::_('RS1_JANUARY').
		"','".JText::_('RS1_FEBRUARY').
		"','".JText::_('RS1_MARCH').
		"','".JText::_('RS1_APRIL').
		"','".JText::_('RS1_MAY').
		"','".JText::_('RS1_JUNE').
		"','".JText::_('RS1_JULY').
		"','".JText::_('RS1_AUGUST').
		"','".JText::_('RS1_SEPTEMBER').
		"','".JText::_('RS1_OCTOBER').
		"','".JText::_('RS1_NOVEMBER').
		"','".JText::_('RS1_DECEMBER')."'"?>);

	cal.setDayHeaders(<?php echo "'".JText::_('RS1_SUN_HEADER').
		"','".JText::_('RS1_MON_HEADER').
		"','".JText::_('RS1_TUE_HEADER').
		"','".JText::_('RS1_WED_HEADER').
		"','".JText::_('RS1_THU_HEADER').
		"','".JText::_('RS1_FRI_HEADER').
		"','".JText::_('RS1_SAT_HEADER')."'"?>);
	
	cal.setTodayText('<?php echo JText::_('RS1_TODAY')?>');
	var now = new Date();
	// to set css for popup calendar uncomment next line and change calStyles.css
	cal.setCssPrefix("TEST");
	cal.setWeekStartDay(<?php echo $apptpro_config->popup_week_start_day ?>);
	
	// to enable bookings today, uncomment next line
	now.setDate(now.getDate()-1);  
	cal.addDisabledDates(null,formatDate(now,"yyyy-MM-dd")); 

	
	function doSubmit(pp){
	
		document.getElementById("errors").innerHTML = document.getElementById("wait_text").value
	
		// ajax validate form
		result = validateForm();
		//alert("|"+result+"|");

		if(result.indexOf('<?php echo JText::_('RS1_INPUT_SCRN_VALIDATION_OK');?>')>-1){
			document.getElementById("ppsubmit").value = pp;
		    document.body.style.cursor = "wait"; 
			document.frmRequest.task.value = "process_booking_request";
			return true;
		       
		} else {
			return false;
		}
		return false;
	}

	function checkSMS(){
		if(document.getElementById("use_sms").checked == true){
			document.getElementById("sms_reminders").value="Yes";
		} else {
			document.getElementById("sms_reminders").value="No";
		}	
	}

	function doCancel(){
		document.getElementById("task").value="cancel"
		document.frmRequest.submit();		
	}		
	
	
</script>
<script language="javascript">
		window.onload = function() {
			if(document.getElementById("resources")!=null){
				if(document.getElementById("resources").options.length==2){
					document.getElementById("resources").options[1].selected=true;
					changeResource();
				} else {
					changeResource();
				}
			}
		<?php if($single_category_mode){ ?>
				document.getElementById("category_id").options[1].selected=true;
				changeCategory();		
		<?php } ?>
				
		}	
</script>
  	<?php echo $rateArrayString; ?>            
    <?php echo $rate_unitArrayString; ?>            
    
<form name="frmRequest" action="<?php echo JRoute::_($this->request_url) ?>" method="post">
<div id="sv_apptpro_request_gad">
  <table width="95%" align="center" border="0" cellspacing="2" cellpadding="2" >
	<?php if($apptpro_config->requireLogin == "Yes" && $user->guest){ echo "<tr><td colspan='8'><BR /><span class='sv_apptpro_errors'>".JText::_('RS1_INPUT_SCRN_LOGIN_REQUIRED')."</span></td></tr>";} ?> 
    <tr>
      <td colspan="4" > <h3><?php echo JText::_('RS1_FRONTDESK_BOOKING_TITLE');?></h3></td>
    </tr>
    <tr>
      <td class="sv_apptpro_request_select_user_label"><?php echo JText::_('RS1_INPUT_SCRN_SELECT_USER');?></td>
  	  <td colspan="3" valign="top"><select name="users" id="users" class="sv_apptpro_request_dropdown" onchange="changeUser();">
      		<option value="0"><?php echo JText::_('RS1_FRONTDESK_SCRN_NOT_REG');?></option>
            <?php
			$k = 0;
			for($i=0; $i < count( $user_rows ); $i++) {
			$user_row = $user_rows[$i];
			?>
                <option value="<?php echo $user_row->id; ?>"><?php echo $user_row->name; ?></option>
                <?php $k = 1 - $k; 
			} ?>
              </select> &nbsp;&nbsp;<label id="user_fetch"  class="sv_apptpro_errors">&nbsp;</label>
      </td>
    </tr>
    <tr>
      <td class="sv_apptpro_request_label"><?php echo JText::_('RS1_INPUT_SCRN_NAME');?></td>
  	  <td colspan="3" valign="top"><input name="name" type="text" id="name" class="sv_apptpro_request_text" 
      		size="40" maxlength="50" title="<?php echo JText::_('RS1_INPUT_SCRN_NAME_TOOLTIP');?>" value="<?php echo $name; ?>" />
            <input type="hidden" name="user_id" id="user_id" value="<?php echo $user_id; ?>" />      </td>
    </tr>
	<?php 
		// if cb_mapping value specified, fetch the cb data
		if($user->guest == false and $apptpro_config->phone_cb_mapping != "" and JRequest::getVar('phone', '') == ""){
			$phone = getCBdata($apptpro_config->phone_cb_mapping, $user->id);
		} else if($user->guest == false and $apptpro_config->phone_js_mapping != "" and JRequest::getVar('phone', '') == ""){
			$phone = getJSdata($apptpro_config->phone_js_mapping, $user->id);
		} else {
			$phone = JRequest::getVar('phone');
		}
	?>
    <?php if($apptpro_config->requirePhone == "Hide"){?>
	    <input name="phone" type="hidden" id="phone" value="" />
    <?php } else { ?>
    <tr>
      <td class="sv_apptpro_request_label"><?php echo JText::_('RS1_INPUT_SCRN_PHONE');?></td>
      <td valign="top"><input name="phone" type="text" id="phone" value="<?php echo $phone ?>" 
           <?php if($apptpro_config->phone_read_only == "Yes" && $apptpro_config->phone_cb_mapping != ""){echo " readonly='readonly' ";}?>
      		size="15" maxlength="20" title="<?php echo JText::_('RS1_INPUT_SCRN_PHONE_TOOLTIP');?>"
             class="sv_apptpro_request_text"/></td>
    </tr>
	<?php } ?>
    <?php if($apptpro_config->enable_clickatell == "Yes"){?>
    <tr>
      <td class="sv_apptpro_request_label" valign="top"><?php echo JText::_('RS1_INPUT_SCRN_SMS_LABEL');?></td>
      <td colspan="3" valign="top"><input type="checkbox" name="use_sms" id="use_sms" onchange="checkSMS();" class="sv_apptpro_request_text"/>&nbsp;
	  		<?php echo JText::_('RS1_INPUT_SCRN_SMS_CHK_LABEL');?>&nbsp;<br />
	      	<?php echo JText::_('RS1_INPUT_SCRN_SMS_PHONE');?>&nbsp;<input name="sms_phone" type="text" id="sms_phone" value="<?php echo JRequest::getVar('sms_phone'); ?>"  
      		size="15" maxlength="20" title="<?php echo JText::_('RS1_INPUT_SCRN_SMS_PHONE_TOOLTIP');?>"
             class="sv_apptpro_request_text"/>
             <?php if($apptpro_config->clickatell_show_code == "Yes"){ ?>
	            <select name="sms_dial_code" id="sms_dial_code" class="sv_apptpro_request_dropdown" title="<?php echo JText::_('RS1_INPUT_SCRN_SMS_CODE_TOOLTIP');?>">
              <?php
				$k = 0;
				for($i=0; $i < count( $dial_rows ); $i++) {
				$dial_row = $dial_rows[$i];
				?>
          <option value="<?php echo $dial_row->dial_code; ?>"  <?php if($row->clickatell_dialing_code == $dial_row->dial_code){echo " selected='selected' ";} ?>><?php echo $dial_row->country." - ".$dial_row->dial_code ?></option>
              <?php $k = 1 - $k; 
				} ?>
      		</select>&nbsp;
   			 <?php } else { ?>
             <input type="hidden" name="sms_dial_code" id="sms_dial_code" value="<?php echo $row->clickatell_dialing_code?>" /></td>
             <?php } ?>
             <input type="hidden" name="sms_reminders" id="sms_reminders" value="No" /></td>
    </tr>
    <?php }?>
    <?php if($apptpro_config->requireEmail == "Hide"){?>
	    <input name="email" type="hidden" id="email" value="" />
    <?php } else { ?>
    <tr>
      <td class="sv_apptpro_request_label"><?php echo JText::_('RS1_INPUT_SCRN_EMAIL');?></td>
      <td colspan="3" valign="top"><input name="email" type="text" id="email" value="<?php echo $email ?>" 
      		 title="<?php echo JText::_('RS1_INPUT_SCRN_EMAIL_TOOLTIP');?>" size="40" maxlength="50"
              class="sv_apptpro_request_text"></td>
    </tr>
	<?php } ?>
    <?php if(count($udf_rows > 0)){
		// (to be added at a later date) if logged in user, fetch udf values from last booking
		
        $k = 0;
        for($i=0; $i < count( $udf_rows ); $i++) {
        	$udf_row = $udf_rows[$i];		
			// if cb_mapping value specified, fetch the cb data
			if($user->guest == false and $udf_row->cb_mapping != "" and JRequest::getVar('user_field'.$i.'_value', '') == ""){
				$udf_value = getCBdata($udf_row->cb_mapping, $user->id);
			} else if($user->guest == false and $udf_row->js_mapping != "" and JRequest::getVar('user_field'.$i.'_value', '') == ""){
				$udf_value = getJSdata($udf_row->js_mapping, $user->id);
			} else {
				$udf_value = JRequest::getVar('user_field'.$i.'_value', '');
			}
				
        	?>
            <tr>
              <td class="sv_apptpro_request_label" valign="top"><label id="<?php echo 'user_field'.$i.'_label'; ?>"><?php echo JText::_(stripslashes($udf_row->udf_label)) ?></label>:</td>
              <td colspan="2" valign="top">
               <?php 
				if($udf_row->read_only == "Yes" && $udf_row->cb_mapping != "" && $user->guest == false){$readonly = " readonly='readonly' ";}
				else if($udf_row->js_read_only == "Yes" && $udf_row->js_mapping != "" && $user->guest == false){$readonly = " readonly='readonly' ";}
				else {$readonly ="";}
				?>
                <?php if($udf_row->udf_type == 'Textbox'){ ?>
                    <input name="user_field<?php echo $i?>_value" id="user_field<?php echo $i?>_value" type="text" value="<?php echo $udf_value; ?>" 
                    size="<?php echo $udf_row->udf_size ?>" maxlength="255" <?php echo $readonly?>
                     class="sv_apptpro_request_text" title="<?php echo JText::_(stripslashes($udf_row->udf_tooltip)) ?>"/>
                     <input type="hidden" name="user_field<?php echo $i?>_is_required" id="user_field<?php echo $i?>_is_required" value="<?php echo $udf_row->udf_required ?>" /></td>
                <?php } else if($udf_row->udf_type == 'Textarea'){ ?>
                    <textarea name="user_field<?php echo $i?>_value" id="user_field<?php echo $i?>_value" 
					<?php echo $readonly?>
                    rows="<?php echo $udf_row->udf_rows ?>" cols="<?php echo $udf_row->udf_cols ?>" 
                     class="sv_apptpro_request_text" title="<?php echo JText::_(stripslashes($udf_row->udf_tooltip)) ?>"/><?php echo $udf_value; ?></textarea>
                     <input type="hidden" name="user_field<?php echo $i?>_is_required" id="user_field<?php echo $i?>_is_required" value="<?php echo $udf_row->udf_required ?>" /></td>
                     </td>
                <?php } else if($udf_row->udf_type == 'Radio'){ 
						$aryButtons = explode(",", $udf_row->udf_radio_options);
						foreach ($aryButtons as $button){ ?>
							<input name="user_field<?php echo $i?>_value" type="radio" id="user_field<?php echo $i?>_value" 
                            <?php  
								if(strpos($button, "(d)")>-1){
									echo " checked=checked ";
									$button = str_replace("(d)","", $button);
								} ?>
							value="<?php echo stripslashes(trim($button)) ?>" title="<?php echo JText::_(stripslashes($udf_row->udf_tooltip)) ?>"/>
                            <?php echo stripslashes(trim($button))?><br />
						<?php } ?>
                <?php } else if($udf_row->udf_type == 'List'){ 
						$aryOptions = explode(",", $udf_row->udf_radio_options); ?>
						<select name="user_field<?php echo $i?>_value" id="user_field<?php echo $i?>_value" class="sv_apptpro_request_dropdown"
                        title="<?php echo JText::_(stripslashes($udf_row->udf_tooltip)) ?>"> 
                        <?php 
						foreach ($aryOptions as $option){ ?>
				            <option value="<?php echo $option; ?>"
                            <?php  
								if(strpos($option, "(d)")>-1){
									echo " selected=true ";
									$option = str_replace("(d)","", $option);
								} ?>
                                ><?php echo stripslashes($option); ?>
                            </option>
						<?php } ?>              
                        </select>                 
                <?php } else { ?>
                    <input name="user_field<?php echo $i?>_value" id="user_field<?php echo $i?>_value" type="checkbox" value="Checked" title="<?php echo JText::_(stripslashes($udf_row->udf_tooltip)) ?>"/>
                     <input type="hidden" name="user_field<?php echo $i?>_is_required" id="user_field<?php echo $i?>_is_required" value="<?php echo $udf_row->udf_required ?>" /></td>
                <?php } ?>    
                     <input type="hidden" name="user_field<?php echo $i?>_udf_id" id="user_field<?php echo $i?>_udf_id" value="<?php echo $udf_row->id_udfs ?>" /></td>
            </tr>
		    <tr>
      		<td ></td>
	      		<td colspan="3" valign="top" class="sv_apptpro_request_helptext"><?php echo JText::_(stripslashes($udf_row->udf_help)) ?></td>
            </tr>
          <?php $k = 1 - $k; 
		} ?>
    <?php }?>
	<?php if(count($res_cats) > 0 ){ ?>
    <tr>
      <td class="sv_apptpro_request_label"><?php echo JText::_('RS1_INPUT_SCRN_RESOURCE_CATEGORIES');?></td>
      <td colspan="3" valign="top"><select name="category_id" id="category_id" class="sv_apptpro_request_dropdown" onchange="changeCategory();"
      title="<?php echo JText::_('RS1_INPUT_SCRN_RESOURCE_CATEGORIES_TOOLTIP');?>">
          <option value="0"><?php echo JText::_('RS1_INPUT_SCRN_RESOURCE_CATEGORIES_PROMPT');?></option>
          <?php 
					$k = 0;
					for($i=0; $i < count( $res_cats ); $i++) {
					$res_cat = $res_cats[$i];
					?>
          <option value="<?php echo $res_cat->id_categories; ?>" <?php if($resource_id == $res_cat->id_categories ){echo " selected='selected' ";} ?>><?php echo JText::_(stripslashes($res_cat->name)); ?></option>
          <?php $k = 1 - $k; 
					} ?>
        </select>
        <div align="right"></div></td>
    </tr>
    <?php if($sub_cat_count->count > 0 ){ // there are sub cats ?>
    <tr id="subcats_row" style="visibility:hidden; display:none"><td></td><td colspan="3"><div id="subcats_div"></div></td></tr>
	<?php } ?>
    <tr>
      <td class="sv_apptpro_request_label"><?php echo JText::_('RS1_INPUT_SCRN_RESOURCE');?></td>
      <td colspan="3" valign="top"><div id="resources_div" style="visibility:hidden;">&nbsp;</div></td>
    </tr>
    <?php } else { ?>
    <tr>
      <td class="sv_apptpro_request_label"><?php echo JText::_('RS1_INPUT_SCRN_RESOURCE');?></td>
      <td colspan="3" valign="top"><select name="resources" id="resources" class="sv_apptpro_request_dropdown" onchange="changeResource()"
      title="<?php echo JText::_('RS1_INPUT_SCRN_RESOURCE_TOOLTIP');?>">
          <?php 
					$k = 0;
					for($i=0; $i < count( $res_rows ); $i++) {
					$res_row = $res_rows[$i];
					?>
          <option value="<?php echo $res_row->id_resources; ?>" <?php if($resource == $res_row->id_resources ){echo " selected='selected' ";} ?>><?php echo JText::_(stripslashes($res_row->name)); echo ($res_row->cost==""?"":" - "); echo JText::_(stripslashes($res_row->cost)); ?></option>
          <?php $k = 1 - $k; 
					} ?>
        </select></td>
    </tr>
    
    <?php } ?>
    
    <tr id="services" style="visibility:hidden; display:none">
      <td class="sv_apptpro_request_label"><?php echo JText::_('RS1_INPUT_SCRN_SERVICES');?></td>
    <td colspan="3"><div id="services_div">&nbsp;</div></td>
    </tr>
    <tr id="resource_udfs" style="visibility:hidden; display:none"><td></td><td colspan="3"><div id="resource_udfs_div"></div></td></tr>
    <tr id="resource_seat_types" style="visibility:hidden; display:none"><td colspan="4"><div id="resource_seat_types_div"></div></td></tr>
    <tr id="resource_extras" style="visibility:hidden; display:none"><td colspan="4"><div id="resource_extras_div"></div></td></tr>

    <tr id="booking_detail" class="sv_gad_user_selection" style="visibility:hidden; display:none">
      <td class="sv_apptpro_request_label"><?php echo JText::_('RS1_GAD_SCRN_DETAIL');?></td>
    <td colspan="3"><label class="sv_apptpro_selected_resource" id="selected_resource"></label>&nbsp;-&nbsp;
    <label class="sv_apptpro_selected_resource" id="selected_date"></label>&nbsp;-&nbsp;
    <label class="sv_apptpro_selected_resource" id="selected_starttime"></label>&nbsp;-&nbsp;
    <label class="sv_apptpro_selected_resource" id="selected_endtime"></label>&nbsp;&nbsp;
    <label class="sv_apptpro_errors" id="selected_resource_wait"></label>
    </td>
    </tr>
    <!-- *********************  GAD *******************************-->
    <tr>
        <td colspan='4'>        
          <table class="sv_gad_container_table" cellpadding="2" cellspacing="0" id="gad_container" style="display:none">
            <tr>
              <td><?php echo JText::_('RS1_GAD_SCRN_DATE');?> <input readonly="readonly" name="grid_date" id="grid_date" type="text" 
                  class="sv_ts_request_text" size="10" maxlength="10" value="<?php echo $grid_date ?>" onchange="changeDate();"/>
                  &nbsp;<a href="#" id="anchor1" onclick="cal.select(document.forms['frmRequest'].grid_date,'anchor1','yyyy-M-dd'); return false;"
                 name="anchor1"><img height="15" hspace="2" src="<?php echo $this->baseurl;?>/components/com_rsappt_pro2/icon_cal.gif" width="16" border="0"></a>              </td>
              <td>&nbsp; <input type="button" class="sv_grid_button" onclick="gridPrevious();" value="<<-">
              &nbsp; <input type="button" class="sv_grid_button" onclick="gridNext();" value="->>"></td>
              <?php if($apptpro_config->gad_grid_hide_startend == "Yes"){?>
              	  <td><input type="hidden" name="gridstarttime" id="gridstarttime" value="<?php echo $gridstarttime ?>"/>
	                  <input type="hidden" name="gridendtime" id="gridendtime" value="<?php echo $gridendtime ?>"/>&nbsp;</td>
              <?php } else { ?>
              <td align="right"><?php echo JText::_('RS1_GAD_SCRN_GRID_START');?>&nbsp;<select name="gridstarttime" id="gridstarttime" class="sv_apptpro_request_dropdown" onchange="changeGrid();">
                <?php 
                for($x=0; $x<25; $x+=1){
                    if($x==12){
                        echo "<option value=".$x.":00 "; if($gridstarttime == $x.":00") {echo " selected='selected' ";} echo ">".JText::_('RS1_INPUT_SCRN_NOON')."</option>";  
                    } else if($x==24){
                        echo "<option value=".$x.":00 "; if($row->gridstarttime == $x.":00") {echo " selected='selected' ";} echo ">".JText::_('RS1_INPUT_SCRN_MIDNIGHT')."</option>";  
                    } else {
                        if($apptpro_config->timeFormat == "12"){
                            $AMPM = " AM";
                            $x1 = $x;
                            if($x>12){ 
                                $AMPM = " PM";
                                $x1 = $x-12;
                            }
                        } else {
                            $AMPM = "";
                            $x1 = $x;
                        }
                        echo "<option value=".$x.":00 "; if(trim($gridstarttime) == $x.":00") {echo " selected='selected' ";} echo "> ".$x1.":00".$AMPM." </option>";  
                    }
                }
                ?>
                </select> &nbsp; <?php echo JText::_('RS1_GAD_SCRN_GRID_END');?> <select name="gridendtime" id="gridendtime" class="sv_apptpro_request_dropdown" onchange="changeGrid();">
                <?php 
                for($x=0; $x<25; $x+=1){
                    if($x==12){
                        echo "<option value=".$x.":00 "; if($gridendtime == $x.":00") {echo " selected='selected' ";} echo ">".JText::_('RS1_INPUT_SCRN_NOON')."</option>";  
                    } else if($x==24){
                        echo "<option value=".$x.":00 "; if($gridendtime == $x.":00") {echo " selected='selected' ";} echo ">".JText::_('RS1_INPUT_SCRN_MIDNIGHT')."</option>";  
                    } else {
                        if($apptpro_config->timeFormat == "12"){
                            $AMPM = " AM";
                            $x1 = $x;
                            if($x>12){ 
                                $AMPM = " PM";
                                $x1 = $x-12;
                            }
                        } else {
                            $AMPM = "";
                            $x1 = $x;
                        }
                        echo "<option value=".$x.":00 "; if($gridendtime == $x.":00") {echo " selected='selected' ";} echo "> ".$x1.":00".$AMPM." </option>";  
                    }
                }
                ?>
                </select> </td>
			<?php } ?>
            </tr>  
                      
            <tr>
              <td align="center" colspan="3" width="<?php echo $gridwidth?>">
              
              <div id="table_here"></div>              </td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
        </table>
        
        <input type="hidden" id="mode" name="mode" value="<?php echo $mode?>" />
        <input type="hidden" id="gridwidth" name="gridwidth" value="<?php echo $gridwidth?>" />
        <input type="hidden" id="grid_days" name="grid_days" value="<?php echo $griddays?>" />   
        <input type="hidden" id="namewidth" name="namewidth" value="<?php echo $namewidth?>" />        

        <input type="hidden" name="selected_resource_id" id="selected_resource_id" value="-1" />
        <input type="hidden" name="startdate" id="startdate" value="<?php echo $enddate ?>" />
        <input type="hidden" name="enddate" id="enddate" value="<?php echo $enddate ?>" />
        <input type="hidden" name="starttime" id="starttime" value="<?php echo $starttime ?>"/>
        <input type="hidden" name="endtime" id="endtime" value="<?php echo $endtime ?>"/>  
        <input type="hidden" name="sub_cat_count" id="sub_cat_count" value="<?php echo $sub_cat_count->count ?>"/>  
           
        </td>
        </tr>
    <!-- *********************  GAD *******************************-->
 <?php if($apptpro_config->enable_coupons == "Yes"){ ?>
     <tr>
        <td valign="top"><?php echo JText::_('RS1_INPUT_SCRN_COUPONS');?></td>
        <td colspan="3"><input name="coupon_code" type="text" id="coupon_code" value="" size="20" maxlength="80" 
              title="<?php echo JText::_('RS1_INPUT_SCRN_COUPON_TOOLTIP');?>" />
              <input type="button" class="button" value="<?php echo JText::_('RS1_INPUT_SCRN_COUPON_BUTTON');?>" onclick="getCoupon()" />
              <div id="coupon_info"></div>
              <input type="hidden" id="coupon_value" />
              <input type="hidden" id="coupon_units" />              
        </td>
    </tr>
 <?php } ?>

 <?php if($apptpro_config->enable_paypal != "No"){ ?>
    <tr>
      <td class="sv_apptpro_request_label" valign="top">&nbsp;</td>
      <td colspan="3" valign="top" style="height:auto">
      <div id="calcResults" style="visibility:hidden; display:none; height:auto">
        <table border="1" align="left" width="300" cellpadding="4" cellspacing="0" style="border-width:0px; border-style:hidden;">
          <tr align="center" >
            <td ><?php echo JText::_('RS1_INPUT_SCRN_RES_RATE');?></td>
            <td ><label id="res_hours_label"><?php echo JText::_('RS1_INPUT_SCRN_RES_RATE_UNITS');?></label></td>
            <td ><?php echo JText::_('RS1_INPUT_SCRN_RES_RATE_TOTAL');?></td>
          </tr>
          <tr align="right" >
            <td><?php echo JText::_('RS1_INPUT_SCRN_CURRENCY_SYMBOL');?>&nbsp;<label id="res_rate"></label></td>
            <td><label id="res_hours"></label>&nbsp;</td>
            <td><?php echo JText::_('RS1_INPUT_SCRN_CURRENCY_SYMBOL');?>&nbsp;<label id="res_total"></label></td>
          </tr>
      <?php if ($extras_row_count->count > 0 ){?>
          <tr align="right">
            <td style="border-bottom:hidden; border-bottom:0px; border-left:hidden; border-left-width:0px; border-right:hidden; border-right-width:0px;">&nbsp;            </td>
            <td style="border-bottom:hidden; border-bottom:0px; border-left:hidden; border-left-width:0px;"><?php echo JText::_('RS1_INPUT_SCRN_EXTRAS_FEE');?>:&nbsp;</td>
            <td>&nbsp;<label id="extras_fee"></label></td>
          </tr>
      <?php } ?>    
      <?php if ($apptpro_config->additional_fee != 0.00 ){?>
          <tr align="right">
            <td style="border-bottom:hidden; border-bottom:0px; border-left:hidden; border-left-width:0px; border-right:hidden; border-right-width:0px;">&nbsp;            </td>
            <td style="border-bottom:hidden; border-bottom:0px; border-left:hidden; border-left-width:0px;"><?php echo JText::_('RS1_INPUT_SCRN_RES_ADDITIONAL_FEE');?>:&nbsp;</td>
            <td>&nbsp;<label id="res_fee"></label></td>
          </tr>
      <?php } ?>    
      <?php if($apptpro_config->enable_coupons == "Yes"){ ?>
          <tr align="right">
            <td style="border-bottom:hidden; border-bottom:0px; border-left:hidden; border-left-width:0px; border-right:hidden; border-right-width:0px;">&nbsp;            </td>
            <td style="border-bottom:hidden; border-bottom:0px; border-left:hidden; border-left-width:0px;"><?php echo JText::_('RS1_INPUT_SCRN_DISCOUNT');?>:&nbsp;</td>
            <td>&nbsp;<label id="discount"></label></td>
          </tr>
	  <?php } ?>
          <tr align="right">
            <td colspan="2" style="border-bottom:hidden; border-bottom:0px; border-left:hidden; border-left-width:0px;"><span id='current_credit'></span>&nbsp;<?php echo JText::_('RS1_INPUT_SCRN_USER_CREDIT');?>:&nbsp;</td>
            <td>&nbsp;<label id="credit"></label> <input type="hidden" name="applied_credit" id="applied_credit" /></td>
          </tr>
          <tr align="right">
            <td style="border:hidden; border-width:0px; border-right-color:#FF0000">&nbsp;
                <input type="hidden" id="additionalfee" value="<?php echo $apptpro_config->additional_fee ?>" />
            	<input type="hidden" id="feerate" value="<?php echo $apptpro_config->fee_rate ?>" />
            	<input type="hidden" id="rateunit" value="<?php echo $apptpro_config->fee_rate ?>" />
                <input type="hidden" id="grand_total" name="grand_total" value="<?php echo $grand_total ?>" />			
             </td>
            <td style="border:hidden; border-width:0px; border-right-width:1px;"><?php echo JText::_('RS1_INPUT_SCRN_RES_RATE_TOTAL');?>:&nbsp;</td>
            <td style="border-top:double; border-bottom:double;"><?php echo JText::_('RS1_INPUT_SCRN_CURRENCY_SYMBOL');?>&nbsp;<label id="res_grand_total"></label></td>
          </tr>
        </table>
      </div>      
      </td>
    </tr>
<?php } ?>    
   <tr>
      <td class="sv_apptpro_request_select_user_label"><?php echo JText::_('RS1_INPUT_SCRN_BOOK_STATUS');?>&nbsp;</td>
      <td colspan="3" valign="top">
          <select id="book_as_request_status" name="book_as_request_status" class="sv_apptpro_requests_dropdown" style="font-size:12px">
<!--            <option value=""><?php echo JText::_('RS1_ADMIN_SCRN_REQUEST_STATUS_NONE');?></option>-->
            <option value="new"  class="color_new" ><?php echo JText::_('RS1_ADMIN_SCRN_REQUEST_STATUS_NEW');?></option>
            <option value="accepted" class="color_accepted" selected ><?php echo JText::_('RS1_ADMIN_SCRN_REQUEST_STATUS_ACCEPTED');?></option>
            <option value="pending" class="color_pending" ><?php echo JText::_('RS1_ADMIN_SCRN_REQUEST_STATUS_PENDING');?></option>
          </select>&nbsp;&nbsp;</td>
    </tr>
<!-- savetoDB doesn ont have paystatus	   <tr>
      <td class="sv_apptpro_request_select_user_label" ><?php echo JText::_('RS1_INPUT_SCRN_PMT_STATUS');?>&nbsp;</td>
      <td colspan="3" valign="top">
          <select name="book_as_payment_status" class="sv_apptpro_requests_dropdown" style="font-size:12px">
    	    <option value="pending" ><?php echo JText::_('RS1_ADMIN_SCRN_PAY_STATUS_PENDING');?></option>
	        <option value="paid" ><?php echo JText::_('RS1_ADMIN_SCRN_PAY_STATUS_PAID');?></option>
	        <option value="na" ><?php echo JText::_('RS1_ADMIN_SCRN_PAY_STATUS_NA');?></option>
          </select></td>
    <tr>-->
    <tr class="sv_apptpro_request_label">
      <td class="sv_apptpro_request_select_user_label" valign="top" ><?php echo JText::_('RS1_INPUT_SCRN_ADMINCOMMENT');?>:</td>
      <td colspan="3"><textarea name="admin_comment" id="admin_comment" class="sv_apptpro_request_text" rows="2" cols="60" ><?php echo stripslashes($row->admin_comment); ?></textarea></td>
    </tr>
    </tr>
      <td class="sv_apptpro_request_select_user_label" ><?php echo JText::_('RS1_INPUT_SCRN_EMAIL_CONF');?>&nbsp;</td>
      <td colspan="3" valign="top">
          <Input type="checkbox" name="chk_email_confirmation" checked="checked" value="Yes" />&nbsp;<?php echo JText::_('RS1_INPUT_SCRN_EMAIL_CONF_HELP');?></td>
    </tr>
    <tr>
      <td width="15%"></td>
      <td colspan="3"><div id="errors" class="sv_apptpro_errors"><?php echo $err ?></div></td>
	</tr>
    <tr>
      <td><input  name="cbCopyMe" type="hidden" value="yes"  /></td>
      <td colspan="3" valign="top">
          <input type="submit" class="button"  name="btnSubmit" id="btnSubmit" onclick="return doSubmit(0);" 
            value="<?php echo JText::_('RS1_FRONTDESK_SCRN_SUBMIT');?>" /> 
          <input type="button" class="btncancel"  name="cancel" id="btncancel" onclick="return doCancel();" 
            value="<?php echo JText::_('RS1_FRONTDESK_SCRN_CANCEL');?>" /> 
    </td>
    </tr>
  </table>
  </div>
  <?php if($apptpro_config->hide_logo == 'No'){ ?>
	  <span style="font-size:9px; color:#999999">powered by <a href="http://www.AppointmentBookingPro.com" target="_blank">AppointmentBookingPro.com</a> v 2.0</span>
  <?php } ?>
  <input type="hidden" id="wait_text" value="<?php echo JText::_('RS1_INPUT_SCRN_PLEASE_WAIT');?>" />
  <input type="hidden" id="select_date_text" value="<?php echo JText::_('RS1_INPUT_SCRN_DATE_PROMPT');?>" />
  <input type="hidden" id="beyond_end_of_day" value="<?php echo JText::_('RS1_INPUT_SCRN_BEYOND_EOD');?>" />
  <input type="hidden" id="udf_count" name="udf_count" value="<?php echo count($udf_rows);?>" />
  <input type="hidden" id="enable_paypal" value="<?php echo $apptpro_config->enable_paypal ?>" />
  <input type="hidden" id="flat_rate_text" name="flat_rate_text" value="<?php echo JText::_('RS1_INPUT_SCRN_RES_FLAT_RATE'); ?>" />			             
  <input type="hidden" id="non_flat_rate_text" name="non_flat_rate_text" value="<?php echo JText::_('RS1_INPUT_SCRN_RES_RATE_UNITS'); ?>" />			             
  <input type="hidden" id="ppsubmit" name="ppsubmit" value="-1" />			             
  <input type="hidden" id="screen_type" name="screen_type" value="fd_gad" />			             
  <input type="hidden" id="reg" name="reg" value="<?php echo ($user->guest?'No':'Yes')?>" />	
  <input type="hidden" id="adjusted_starttime" name="adjusted_starttime" value="" />			             
  <input type="hidden" id="timeFormat" value="<?php echo $apptpro_config->timeFormat ?>" />
  <input type="hidden" id="end_of_day" value="<?php echo $apptpro_config->def_gad_grid_end ?>" />
  <input type="hidden" name="redirect" id="redirect" value="" />
  <input type="hidden" name="fd" id="fd" value="Yes" />
  <input type="hidden" id="uc" value="<?php echo $user_credit ?>" />
  <input type="hidden" id="gad2" value="<?php echo $apptpro_config->use_gad2 ?>" />
  
  	<input type="hidden" name="option" value="<?php echo $option; ?>" />
  	<input type="hidden" name="controller" value="frontdesk" />
	<input type="hidden" name="id" value="<?php echo $user->id; ?>" />
	<input type="hidden" name="task" id="task" value="" />
	<input type="hidden" name="frompage" value="frontdesk" />
  	<input type="hidden" name="frompage_item" id="frompage_item" value="<?php echo $itemId ?>" />
  
</form>
