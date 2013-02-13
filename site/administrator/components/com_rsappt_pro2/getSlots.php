<?php
/*
 ****************************************************************
 Copyright (C) 2008-2009 Soft Ventures, Inc. All rights reserved.
 ****************************************************************
 * @package	Appointment Booking Pro - ABPro
 * @copyright	Copyright (C) 2008-2009 Soft Ventures, Inc. All rights reserved.
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



defined( '_JEXEC' ) or die( 'Restricted access' );
	include( JPATH_SITE."/components/com_rsappt_pro2/functions2.php" );


	header('Content-Type: text/xml'); 
	header("Cache-Control: no-cache, must-revalidate");
	//A date in the past
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	
	// what this module does..
	// recives the user's selected resource and date
	// determine what day the date is
	// select timeslots for that day
	// select bookings for that date & resource
	// return a dataset of timeslot | availability
	// ex:
	//	08:00-09:30 | available
	//	09:30-11:00 | booked
	//	etc
	// OR
	// if caldays=yes, get the available days for a resource
	// if serv=yes, get the services for a resource
	
	
	// recives the user's selected resource and date
	$resource = JRequest::getVar('res');
	$cat = JRequest::getVar('cat');
	$startdate = JRequest::getVar('startdate');
	$browser = JRequest::getVar('browser');
	$gad = JRequest::getVar('gad');
	$reg = JRequest::getVar('reg', 'No');
	$mobile = JRequest::getVar('mobile', 'No');
	$getcoup = JRequest::getVar('getcoup', 'No');
	$coupon_code = JRequest::getVar('cc', '');
	$parent_cat_id = JRequest::getVar('cat', '');
	$service = JRequest::getVar('srv', '');

	$database = &JFactory::getDBO(); 
	$sql = 'SELECT * FROM #__sv_apptpro2_config';
	$database->setQuery($sql);
	$apptpro_config = NULL;
	$apptpro_config = $database -> loadObject();
	if ($database -> getErrorNum()) {
		echo "DB Err: ". $database -> stderr();
		exit;
	}
	

	if(JRequest::getVar('caldays') == "yes"){
		// ************************************
		// get calendar days for the resource
		// ************************************
	
		$database = &JFactory::getDBO(); 
		$sql = 'SELECT * FROM #__sv_apptpro2_resources where id_resources = '.$resource;
		$database->setQuery($sql);
		$res_detail = NULL;
		$res_detail = $database -> loadObject();
		if ($database -> getErrorNum()) {
			echo "DB Err: ". $database -> stderr();
			exit;
		}

		// clearDisabledDates added to CalendarPopup.js by rob, not in standard verison
		echo "cal.clearDisabledDates();"; 

		echo "cal.setWeekStartDay(".$apptpro_config->popup_week_start_day.");";

		// build list of days to disable on calendar
		$disableDays = "";
		if(	$res_detail->allowSunday=="No" ) $disableDays = $disableDays.($apptpro_config->popup_week_start_day==1?"6":"0");
		if(	$res_detail->allowMonday=="No" ) {
			if( $disableDays != "") $disableDays = $disableDays.",";
			$disableDays = $disableDays.($apptpro_config->popup_week_start_day==1?"0":"1");
		}
		if(	$res_detail->allowTuesday=="No" ) {
			if( $disableDays != "") $disableDays = $disableDays.",";
			$disableDays = $disableDays.($apptpro_config->popup_week_start_day==1?"1":"2");
		}
		if(	$res_detail->allowWednesday=="No" ) {
			if( $disableDays != "") $disableDays = $disableDays.",";
			$disableDays = $disableDays.($apptpro_config->popup_week_start_day==1?"2":"3");
		}
		if(	$res_detail->allowThursday=="No" ) {
			if( $disableDays != "") $disableDays = $disableDays.",";
			$disableDays = $disableDays.($apptpro_config->popup_week_start_day==1?"3":"4");
		}
		if(	$res_detail->allowFriday=="No" ) {
			if( $disableDays != "") $disableDays = $disableDays.",";
			$disableDays = $disableDays.($apptpro_config->popup_week_start_day==1?"4":"5");
		}
		if(	$res_detail->allowSaturday=="No" ) {
			if( $disableDays != "") $disableDays = $disableDays.",";
			$disableDays = $disableDays.($apptpro_config->popup_week_start_day==1?"5":"6");
		}
		
		echo "cal.setDisabledWeekDays(".$disableDays.");";
		
		// check for book-offs
		$sql = "SELECT * FROM #__sv_apptpro2_bookoffs where resource_id = ".$resource.
		" AND off_date > NOW() AND full_day='Yes' AND Published=1";
		$database->setQuery($sql);
		$bookoffs = NULL;
		$bookoffs = $database -> loadObjectList();
		if ($database -> getErrorNum()) {
			echo "DB Err: ". $database -> stderr();
			exit;
		}
		for($i=0; $i < count( $bookoffs ); $i++) {
			$bookoff = $bookoffs[$i];
			echo "cal.addDisabledDates('".$bookoff->off_date."');"; 
		}
		
		if($res_detail->disable_dates_before != "Tomorrow" AND $res_detail->disable_dates_before != "Today" AND $res_detail->disable_dates_before != "XDays"){
			// use specific date
			// cal function actually disables up to the date, not date before
			$day = strtotime($res_detail->disable_dates_before);
			$day = $day - 86400;
			echo "cal.addDisabledDates(null,'".strftime("%Y-%m-%d", $day)."');"; 
		}
		if($res_detail->disable_dates_before == "XDays"){
			echo "var now = new Date();";
			echo "now.setDate(now.getDate()+".strval($res_detail->disable_dates_before_days).");";  
			echo "cal.addDisabledDates(null,formatDate(now,'yyyy-MM-dd'));"; 
		}
		if($res_detail->disable_dates_before == "Tomorrow"){
			echo "var now = new Date();";
			echo "cal.addDisabledDates(null,formatDate(now,'yyyy-MM-dd'));"; 
		}
		if($res_detail->disable_dates_before == "Today"){
			echo "var now = new Date();";
			echo "now.setDate(now.getDate()-1);";  
			echo "cal.addDisabledDates(null,formatDate(now,'yyyy-MM-dd'));"; 
		}

		// set disable after as required
		if($res_detail->disable_dates_after != "Not Set" && $res_detail->disable_dates_after != "XDays"){
			$day = strtotime($res_detail->disable_dates_after);
			$day = $day + 86400;
			echo "cal.addDisabledDates('".strftime("%Y-%m-%d", $day)."', null);"; 
		}
		if($res_detail->disable_dates_after == "XDays"){
			$day = strtotime("now");
			$day = $day + (86400*$res_detail->disable_dates_after_days);
			echo "cal.addDisabledDates('".strftime("%Y-%m-%d", $day)."', null);"; 
		}
		
	} else if(JRequest::getVar('res') == "yes"){
		// ************************************
		// get resources for a category
		// ************************************
		$database = &JFactory::getDBO(); 
		if($reg=='No'){
			$andClause = " AND access != 'registered_only' ";
		} else {
			$andClause = " AND access != 'public_only' ";
		}
		
		$res_top_row = ($gad=="Yes"? JText::_('RS1_GAD_SCRN_RESOURCE_DROPDOWN'): JText::_('RS1_INPUT_SCRN_RESOURCE_PROMPT'));
		$sql = '(SELECT 0 as id_resources, \''.$res_top_row.'\' as name, \''.
		$res_top_row.'\' as description, 0 as ordering, "" as cost) '.
		'UNION (SELECT id_resources,name,description,ordering,cost '.
		'FROM #__sv_apptpro2_resources WHERE published=1 '.$andClause.' AND category_id = '.$cat.' ) ORDER BY ordering';
		$database->setQuery($sql);
		$res_rows = NULL;
		$res_rows = $database -> loadObjectList();
		if ($database -> getErrorNum()) {
			echo "DB Err: ". $database -> stderr();
			exit;
		}
		echo '<select name="resources" id="resources" class="sv_apptpro_request_dropdown'.($mobile=="Yes"?"_mobile":"").'" onchange="changeResource()" '.
	  	    	'title='.JText::_('RS1_INPUT_SCRN_RESOURCE_TOOLTIP').'>';
					$k = 0;
					for($i=0; $i < count( $res_rows ); $i++) {
					$res_row = $res_rows[$i];
			          	echo '<option value='.$res_row->id_resources.'>'.JText::_(stripslashes($res_row->name));  echo ($res_row->cost==""?"":" - "); echo JText::_(stripslashes($res_row->cost)).'</option>\n';
          			$k = 1 - $k; 
					} 
        echo '</select>';
		
	} else if(JRequest::getVar('getsubcats') == "yes"){
		// ************************************
		// get subcategory for a category
		// ************************************
		$database = &JFactory::getDBO(); 
		$sql = 'SELECT * FROM #__sv_apptpro2_categories WHERE parent_category = '.$parent_cat_id.'  order by ordering';
		$database->setQuery($sql);
		$res_cats = $database -> loadObjectList();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
			logIt($database->getErrorMsg()); 
			return false;
		}

		if(count($res_cats) == 0){
			echo "";
		} else {
			echo "<select name=\"sub_category_id\" id=\"sub_category_id\" class=\"sv_apptpro_request_dropdown".($mobile=="Yes"?"_mobile":"")."\" onchange=\"changeSubCategory();\" ".
	      	"title=\"".JText::_('RS1_INPUT_SCRN_RESOURCE_SUB_CATEGORIES_TOOLTIP')."\" >\n ".
          	"<option value=\"0\">".JText::_('RS1_INPUT_SCRN_RESOURCE_SUB_CATEGORIES_PROMPT')."</option>\n";
			$k = 0;
			for($i=0; $i < count( $res_cats ); $i++) {
				$res_cat = $res_cats[$i];
          		echo "<option value=\"".$res_cat->id_categories."\" >".JText::_(stripslashes($res_cat->name))."</option>\n";
          		$k = 1 - $k; 
			}
        	echo "</select>\n".
			"<div align=\"right\"></div>\n"; 
		}	


	} else if(JRequest::getVar('serv') == "yes"){
		// ************************************
		// get services for the resource
		// ************************************
	
		$database = &JFactory::getDBO(); 
		$sql = 'SELECT * FROM #__sv_apptpro2_services where published = 1 AND resource_id = '.$resource.' ORDER BY ordering' ;
		$database->setQuery($sql);
		$service_rows = NULL;
		$service_rows = $database -> loadObjectList();
		if ($database -> getErrorNum()) {
			echo "DB Err: ". $database -> stderr();
			exit;
		}
		if(count($service_rows) == 0){
			echo "<input type='hidden' id='has_services' value='no' />";
		} else {
			echo '<select name="service_name" id="service_name" class="sv_apptpro_request_dropdown'.($mobile=="Yes"?"_mobile":"").'" onchange="setDuration();calcTotal()"'.
					'title="'.JText::_('RS1_INPUT_SCRN_SERVICE_TOOLTIP').'">';
						$k = 0;
						//echo '<option value="-1">Select a Service</option>';
						for($i=0; $i < count( $service_rows ); $i++) {
						$service_row = $service_rows[$i];
							echo '<option value='.$service_row->id_services.'>'.JText::_(stripslashes($service_row->name)).'</option>';
						$k = 1 - $k; 
						} 
			echo '</select>';
		}	
	 			
		// get service rates and durations
		$database = &JFactory::getDBO(); 
//		$sql = 'SELECT id,service_rate,service_rate_unit,resource_id FROM #__sv_apptpro2_services WHERE resource_id = '.$resource;
		$sql = 'SELECT id_services,service_rate,service_rate_unit,service_duration,service_duration_unit,resource_id FROM #__sv_apptpro2_services WHERE resource_id = '.$resource;
		$database->setQuery($sql);
		$service_rates = $database -> loadObjectList();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
			return false;
		}
		$serviceRatesArrayString = "<input type='hidden' id='service_rates' value='";
		$serviceDurationsArrayString = "<input type='hidden' id='service_durations' value='";
		for($i=0; $i<count($service_rates); $i++){
			$serviceRatesArrayString = $serviceRatesArrayString.$service_rates[$i]->id_services.":".$service_rates[$i]->service_rate.":".$service_rates[$i]->service_rate_unit."";
			if($i<count($service_rates)-1){
				$serviceRatesArrayString = $serviceRatesArrayString.",";
			}

			$serviceDurationsArrayString = $serviceDurationsArrayString.$service_rates[$i]->id_services.":".$service_rates[$i]->service_duration.":".$service_rates[$i]->service_duration_unit."";
			if($i<count($service_rates)-1){
				$serviceDurationsArrayString = $serviceDurationsArrayString.",";
			}

		}
		$serviceRatesArrayString = $serviceRatesArrayString."'>";
		echo $serviceRatesArrayString."\n";
		$serviceDurationsArrayString = $serviceDurationsArrayString."'>";
		echo $serviceDurationsArrayString."\n";
			
		exit;

	} else if(JRequest::getVar('res_udfs') == "yes"){
		// ************************************
		// get udfs for the resource
		// ************************************
		$out = "<table>";
		$database = &JFactory::getDBO(); 
		$sql = 'SELECT * FROM #__sv_apptpro2_udfs WHERE published=1 AND scope LIKE \'%|'.$resource.'|%\' ORDER BY ordering';
		$database->setQuery($sql);
		$udf_rows = NULL;
		$udf_rows = $database -> loadObjectList();
		if ($database -> getErrorNum()) {
			echo "DB Err: ". $database -> stderr();
			exit;
		}
		if(count($udf_rows) == 0){
			echo "";
		} else {
			// these are respecific udfs, there may be global ones above these so we need to adjust the start count
			$sql = 'SELECT count(*) FROM #__sv_apptpro2_udfs WHERE published=1 AND scope = ""';
			$database->setQuery($sql);
			$udf_offset = $database -> loadResult();
			if ($database -> getErrorNum()) {
				echo "DB Err: ". $database -> stderr();
				exit;
			}
			
			$k = 0;
			for($i=0; $i < count( $udf_rows ); $i++) {
				$udf_row = $udf_rows[$i];
				$udf_number = $i + intval($udf_offset);
				// if cb_mapping value specified, fetch the cb data
				$user =& JFactory::getUser();
				if($user->guest == false and $udf_row->cb_mapping != "" and JRequest::getVar('user_field'.$udf_number.'_value', '') == ""){
					$udf_value = getCBdata($udf_row->cb_mapping, $user->id);
				} else {
					$udf_value = JRequest::getVar('user_field'.$udf_number.'_value', '');
				}
					
				$out .= "<tr>".
					"<td class=\"sv_apptpro_request_label\" valign=\"top\"><label id=user_field".$udf_number."_label>".stripslashes($udf_row->udf_label)."</label>:</td>".
					"<td colspan=\"2\" valign=\"top\">";
					if($udf_row->udf_type == "Textbox"){ 
						$out .= "<input name=\"user_field".$udf_number."_value\" id=\"user_field".$udf_number."_value\" type=\"text\" value=\"".$udf_value."\"". 
						"size=\"".$udf_row->udf_size."\" maxlength=\"255\"";
						if($udf_row->read_only == "Yes" && $udf_row->cb_mapping != "" && $user->guest == false){$out.=" readonly=\"readonly\" ";}
						$out .= " class=\"sv_apptpro_request_text\" title=\"".stripslashes($udf_row->udf_tooltip)."\"/>".
						"<input type=\"hidden\" name=\"user_field".$udf_number."_is_required\" id=\"user_field".$udf_number."_is_required\" value=\"".$udf_row->udf_required."\" /></td>";
					} else if($udf_row->udf_type == "Textarea"){
						$out .= "<textarea name=\"user_field".$udf_number."_value\" id=\"user_field".$udf_number."_value\""; 
						if($udf_row->read_only == "Yes" && $udf_row->cb_mapping != "" && $user->guest == false){$out.=" readonly=\"readonly\" ";}
						$out.=" rows=\"".$udf_row->udf_rows."\" cols=\"".$udf_row->udf_cols."\" ". 
						" class=\"sv_apptpro_request_text\" title=\"".stripslashes($udf_row->udf_tooltip)."\"/>".$udf_value."</textarea> ".
						" <input type=\"hidden\" name=\"user_field".$udf_number."_is_required\" id=\"user_field".$udf_number."_is_required\" value=\"".$udf_row->udf_required."\" /></td>";
					} else if($udf_row->udf_type == "Radio"){ 
						$aryButtons = explode(",", $udf_row->udf_radio_options);
						foreach ($aryButtons as $button){ 
							$out .="<input name=\"user_field".$udf_number."_value\" type=\"radio\" id=\"user_field".$udf_number."_value\""; 
							if(strpos($button, "(d)")>-1){
								$out .=	" checked=checked ";
								$button = str_replace("(d)","", $button);
							}
							$out .= " value=\"".stripslashes(trim($button))."\" title=\"".stripslashes($udf_row->udf_tooltip)."\"/> ".
							stripslashes(trim($button))."<br /> ";
						}
					} else if($udf_row->udf_type == "List"){ 
							$aryOptions = explode(",", $udf_row->udf_radio_options);
							$out .= " <select name=\"user_field".$udf_number."_value\" id=\"user_field".$udf_number."_value\" class=\"sv_apptpro_request_dropdown\" ".
							"title=\"".stripslashes($udf_row->udf_tooltip)."\"> "; 
							foreach ($aryOptions as $option){
								$out .= "<option value=\"".$option."\"";
								if(strpos($option, "(d)")>-1){
									$out .= " selected=true ";
									$option = str_replace("(d)","", $option);
								}
								$out .= ">".stripslashes($option)."</option>";
							}              
							$out .= "</select>";                 
					} else {
						$out .= "<input name=\"user_field".$udf_number."_value\" id=\"user_field".$udf_number."_value\" type=\"checkbox\" value=\"Checked\" ".
						" title=\"".stripslashes($udf_row->udf_tooltip)."\"/>".
						" <input type=\"hidden\" name=\"user_field".$udf_number."_is_required\" id=\"user_field".$udf_number."_is_required\" ".
						" value=\"".$udf_row->udf_required."\" /></td> ";
					}    
					$out .= " <input type=\"hidden\" name=\"user_field".$udf_number."_udf_id\" id=\"user_field".$udf_number."_udf_id\" ".
					"value=\"".$udf_row->id_udfs."\" /></td> ".
				"</tr>".
				"<tr>".
				"<td >  <input type=\"hidden\" id=\"res_udf_count\" name=\"res_udf_count\" value=\"".count($udf_rows)."\" /></td>".
					"<td colspan=\"3\" valign=\"top\" class=\"sv_apptpro_request_helptext\">".stripslashes($udf_row->udf_help)."</td>".
				"</tr>";

				$k = 1 - $k; 
			}	
	 	}
		if($out == "<table>"){
			$out="";
		} else {
			$out .= "</table>";
		}
		echo $out;				
		exit;

	} else if(JRequest::getVar('res_seats') == "yes"){
		// ************************************
		// get seat types for the resource
		// ************************************
		$out = "<table>\n";

		// get seat types
		$database = &JFactory::getDBO(); 
		$sql = 'SELECT * FROM #__sv_apptpro2_seat_types WHERE published=1 AND (scope = "" OR scope LIKE \'%|'.$resource.'|%\') ORDER BY ordering';
		$database->setQuery($sql);
		$seat_type_rows = $database -> loadObjectList();
		if ($database -> getErrorNum()) {
			echo "DB Err: ". $database -> stderr();
			return false;
		}

		$si = 0; 
		foreach($seat_type_rows as $seat_type_row){
			$out .= "<tr class=\"seats_block\"> \n".
				"<td class=\"sv_apptpro_request_label\">".JText::_($seat_type_row->seat_type_label).":</td>\n".
			  	"<td colspan=\"3\" valign=\"top\">\n".
			  	"<select name=\"seat_".$si."\" id=\"seat_".$si."\" onChange=\"calcTotal();\" class=\"sv_apptpro_request_dropdown".($mobile=="Yes"?"_mobile":"")."\" ". 
				"title=".JText::_($seat_type_row->seat_type_tooltip)."  />\n";
				for($i=0; $i<=$seat_type_row->seat_group_max; $i++){ 
					$out .=	"<option value=".$i.">".$i."</option>\n";	        
				}
			   $out .= "</select>\n". 
				"&nbsp;".JText::_($seat_type_row->seat_type_help)." \n".
				"<input type=\"hidden\" name=\"seat_type_cost_".$si."\" id=\"seat_type_cost_".$si."\" value=\"".$seat_type_row->seat_type_cost."\"/>\n".  
				"<input type=\"hidden\" name=\"seat_type_id_".$si."\" id=\"seat_type_id_".$si."\" value=\"".$seat_type_row->id_seat_types."\"/>\n".  
				"<input type=\"hidden\" name=\"seat_group_".$si."\" id=\"seat_group_".$si."\" value=\"".$seat_type_row->seat_group."\"/>\n".  
			  " </td>\n".
			"</tr>\n";
			$si += 1; 
		} 
		if($si>0){  
			$out .= "<tr class=\"seats_block\">\n".
			  "<td class=\"sv_apptpro_request_label\">".JText::_('RS1_INPUT_SCRN_TOTAL_SEATS').":</td>\n".
			  "<td colspan=\"3\" valign=\"top\"><div id=\"booked_seats_div\" name=\"booked_seats_div\" style=\"padding-left:5px\"></div>\n".
			  "<input type=\"hidden\" name=\"booked_seats\" id=\"booked_seats\" value=\"1\"/>  </td>\n".
			"</tr>\n";
		}

		if($out == "<table>\n"){
			$out="";
		} else {
			$out .= "</table>\n";
		}
		$out .= "<input type=\"hidden\" name=\"seat_type_count\" id=\"seat_type_count\" value=\"".count($seat_type_rows)."\">\n";
		echo $out;				
		exit;

	} else if(JRequest::getVar('extras') == "yes"){
		// ************************************
		// get extras for the resource
		// ************************************
		$out = "<table>\n";

		// get seat types
		$database = &JFactory::getDBO(); 
		$sql = 'SELECT * FROM #__sv_apptpro2_extras WHERE published=1 AND (resource_scope = "" OR resource_scope LIKE \'%|'.$resource.'|%\' ';
/*		Not implemented, too complex due to simulatuous asynchronous changing of resources and services, may a future feature
		if($service != ""){
			$sql .= ' OR service_scope LIKE \'%|'.$service.'|%\'';
		}
*/		$sql .= ") ORDER BY ordering";
		$database->setQuery($sql);
		$extras_rows = $database -> loadObjectList();
		if ($database -> getErrorNum()) {
			echo "DB Err: ". $database -> stderr();
			return false;
		}

		$si = 0; 
		if(count($extras_rows)>0){  
			$out .= "<tr class=\"extras_block\">\n".
			  "<td class=\"sv_apptpro_request_label\">".JText::_('RS1_INPUT_SCRN_EXTRAS_LABEL').":</td>\n".
			  "<td colspan=\"3\" valign=\"top\"></td>\n".
			"</tr>\n";
		}
		foreach($extras_rows as $extras_row){
			$out .= "<tr class=\"extras_block\"> \n".
				"<td class=\"sv_apptpro_request_label\">".JText::_($extras_row->extras_label).":</td>\n".
			  	"<td colspan=\"2\" valign=\"top\">\n".
			  	"<select name=\"extra_".$si."\" id=\"extra_".$si."\" onChange=\"calcTotal();\" class=\"sv_apptpro_request_dropdown".($mobile=="Yes"?"_mobile":"")."\" ". 
				"title='".JText::_($extras_row->extras_tooltip)."'  />\n";
				for($i=0; $i<=$extras_row->max_quantity; $i++){ 
					$out .=	"<option value=".$i.($i==$extras_row->default_quantity?" selected":"").">".$i."</option>\n";	        
				}
			   $out .= "</select>\n". 
				"&nbsp;".JText::_($extras_row->extras_help)." \n".
				"<input type=\"hidden\" name=\"extras_cost_".$si."\" id=\"extras_cost_".$si."\" value=\"".$extras_row->extras_cost."\"/>\n".  
				"<input type=\"hidden\" name=\"extras_cost_unit_".$si."\" id=\"extras_cost_unit_".$si."\" value=\"".$extras_row->cost_unit."\"/>\n".  
				"<input type=\"hidden\" name=\"extras_id_".$si."\" id=\"extras_id_".$si."\" value=\"".$extras_row->id_extras."\"/>\n".  
			  " </td>\n".
			"</tr>\n";
			$si += 1; 
		} 

		if($out == "<table>\n"){
			$out="";
		} else {
			$out .= "</table>\n";
		}
		$out .= "<input type=\"hidden\" name=\"extras_count\" id=\"extras_count\" value=\"".count($extras_rows)."\">\n";
		echo $out;				
		exit;

	} else if(JRequest::getVar('adminserv') == "yes"){
		// ************************************
		// get services for the resource (admin side)
		// ************************************
	
		$database = &JFactory::getDBO(); 
		$sql = 'SELECT * FROM #__sv_apptpro2_services where published = 1 AND resource_id = '.$resource;
		$database->setQuery($sql);
		$service_rows = NULL;
		$service_rows = $database -> loadObjectList();
		if ($database -> getErrorNum()) {
			echo "DB Err: ". $database -> stderr();
			exit;
		}
		if(count($service_rows) > 0){
			// new Option(text, value, defaultSelected, selected)
			$k = 0;
			for($i=0; $i < count( $service_rows ); $i++) {
				$service_row = $service_rows[$i];
					echo 'document.getElementById("service").options['.$i.']=new Option("'.stripslashes($service_row->name).'", "'.$service_row->id_services.'", false, false);';
				$k = 1 - $k; 
			} 
		}	
		exit;

	} else if(JRequest::getVar('getcoup') == "yes"){
		// ************************************
		// get coupon details
		// ************************************

		$database = &JFactory::getDBO(); 
		$sql = "SELECT *, DATE_FORMAT(expiry_date, '%Y-%m-%d') as expiry FROM #__sv_apptpro2_coupons where coupon_code = '".$coupon_code."' and published=1";
		$database->setQuery($sql);
		$coupon_detail = NULL;
		$coupon_detail = $database -> loadObject();
		if ($database -> getErrorNum()) {
			echo "DB Err: ". $database -> stderr();
			exit;
		}
		
		$coupon_refused = false;
		
		// check scope
		if($coupon_detail->scope != ""){
			// one or more resources hae been specified
			if(strpos($coupon_detail->scope, '|'.$resource.'|') === false){
				// coupon not valis for this resource
				echo JText::_('RS1_INPUT_SCRN_COUPON_INVALID_4_RESOURCE')."|0|";
				$coupon_refused = true;
			}				 			
		}
		if($coupon_detail == NULL){
			echo JText::_('RS1_INPUT_SCRN_COUPON_INVALID')."|0|";
			$coupon_refused = true;
		} else if(strtotime("now") > strtotime($coupon_detail->expiry)){
			echo JText::_('RS1_INPUT_SCRN_COUPON_EXPIRED')."|0|";
			$coupon_refused = true;
		} else {		
			// Check for Max Total Usage
			if($coupon_detail->max_total_use > 0){
				// get total useage count
				$sql = "SELECT count(*) FROM #__sv_apptpro2_requests WHERE coupon_code = '".$coupon_code."' ".
					" AND (".
					"	request_status = 'accepted' ".
					" 	OR request_status = 'attended' ".
					" 	OR request_status = 'completed' ".
					")";
				$database->setQuery($sql);
				$coupon_count = NULL;
				$coupon_count = $database -> loadResult();
				if ($database -> getErrorNum()) {
					echo "DB Err: ". $database -> stderr();
					exit;
				}
				if($coupon_count >= $coupon_detail->max_total_use){
					echo JText::_('RS1_INPUT_SCRN_COUPON_MAXED_OUT')."|0|";
					$coupon_refused = true;
				}
			}		

			// Check for Max User Usage
			$user =& JFactory::getUser();
			if($coupon_detail->max_user_use > 0 and $user->guest == false){
				// get total useage count
				$sql = "SELECT count(*) FROM #__sv_apptpro2_requests WHERE coupon_code = '".$coupon_code."' ".
					" AND user_id = ".$user->id." ".
					" AND (".
					"	request_status = 'accepted' ".
					" 	OR request_status = 'attended' ".
					" 	OR request_status = 'completed' ".
					")";
				$database->setQuery($sql);
				$coupon_count = NULL;
				$coupon_count = $database -> loadResult();
				if ($database -> getErrorNum()) {
					echo "DB Err: ". $database -> stderr();
					exit;
				}
				if($coupon_count >= $coupon_detail->max_user_use){
					echo JText::_('RS1_INPUT_SCRN_COUPON_MAXED_OUT')."|0|";
					$coupon_refused = true;
				}
			}		
			
		}
					
		if($coupon_refused == false){
			echo JText::_($coupon_detail->description)."|".$coupon_detail->discount."|".$coupon_detail->discount_unit;
		}
		exit;

	} else {
		// ************************************
		// get slots
		// ************************************
		
		// determine what day the date is
		$day = date("w", strtotime($startdate)); 
	
		$database = &JFactory::getDBO(); 
		$sql = 'SELECT * FROM #__sv_apptpro2_config';
		$database->setQuery($sql);
		$apptpro_config = NULL;
		$apptpro_config = $database -> loadObject();
		if ($database -> getErrorNum()) {
			echo "DB Err: ". $database -> stderr();
			exit;
		}
	
		$database = &JFactory::getDBO(); 
		$sql = 'SELECT * FROM #__sv_apptpro2_resources where id_resources = '.$resource;
		$database->setQuery($sql);
		$res_detail = NULL;
		$res_detail = $database -> loadObject();
		if ($database -> getErrorNum()) {
			echo "DB Err: ". $database -> stderr();
			exit;
		}
	
		// select timeslots for that day
		$database = &JFactory::getDBO();
		$sql = "SELECT *, ";
		if($apptpro_config->timeFormat == "12"){
			$sql .= "TIME_FORMAT(timeslot_starttime,'%l:%i %p') as timeslot_starttime, timeslot_starttime as timeorder, ".
			"TIME_FORMAT(timeslot_starttime,'%k:%i') as starttime_24, TIME_FORMAT(timeslot_endtime,'%k:%i') as endtime_24, ".
			"TIME_FORMAT(timeslot_endtime,'%l:%i %p') as timeslot_endtime, TIME_FORMAT(timeslot_starttime,'%H:%i') as db_starttime_24 ";
		} else {
			$sql .= "TIME_FORMAT(timeslot_starttime,'%H:%i') as timeslot_starttime, timeslot_starttime as timeorder,  ".
			"TIME_FORMAT(timeslot_starttime,'%k:%i') as starttime_24, TIME_FORMAT(timeslot_endtime,'%k:%i') as endtime_24, ".
			"TIME_FORMAT(timeslot_endtime,'%H:%i') as timeslot_endtime, TIME_FORMAT(timeslot_starttime,'%H:%i') as db_starttime_24 ";
		}	
		// does the resource use global slots?
		
		if($res_detail->timeslots == "Global"){
			$sql .=	"FROM #__sv_apptpro2_timeslots WHERE published=1 AND (resource_id is null or resource_id = 0) AND day_number = ".$day.
				" AND (start_publishing IS NULL OR start_publishing = '0000-00-00' OR '".$startdate."' >= start_publishing ) ".
				" AND (end_publishing IS NULL OR start_publishing = '0000-00-00' OR '".$startdate."' <= end_publishing ) ".
				" ORDER BY timeorder";
		} else {
			$sql .=	"FROM #__sv_apptpro2_timeslots WHERE published=1 AND resource_id = ".$resource." AND day_number = ".$day.
				" AND (start_publishing IS NULL OR start_publishing = '0000-00-00' OR '".$startdate."' >= start_publishing ) ".
				" AND (end_publishing IS NULL OR start_publishing = '0000-00-00' OR '".$startdate."' <= end_publishing ) ".
				" ORDER BY timeorder";
		} 
	
		//echo $sql;
		$database->setQuery($sql);
		$slot_rows = $database -> loadObjectList();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
			exit;
		}
	
		// select bookings for that date & resource
		$sql = "SELECT starttime FROM #__sv_apptpro2_requests WHERE resource='".$resource."' AND startdate='".$startdate."' AND (request_status='accepted' OR request_status='pending')";
		//echo $sql;
		$database->setQuery($sql);
		$booking_rows = $database -> loadResultArray ();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
			exit;
		}
		//print_r($booking_rows);
		
		
		// get get part day book-offs
		$sql = "SELECT * FROM #__sv_apptpro2_bookoffs WHERE resource_id='".$resource."' AND off_date = '".$startdate."'";
		$sql .=" AND full_day='No' AND published=1 ORDER BY bookoff_starttime";
		$database->setQuery($sql);
		$part_day_bookoffs = $database -> loadObjectList();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
			return false;
		}
		
		// get resource info for the selected resource
		$sql = 'SELECT * FROM #__sv_apptpro2_resources where id_resources = '.$resource;
		$database->setQuery($sql);
		$res_detail = NULL;
		$res_detail = $database -> loadObject();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
			exit;
		}
	
		$gotSlots = true;
		if(count( $slot_rows ) == 0) {
			$gotSlots = false;
		}
		
		/* This code was used for putting the No slots Available option into the top of the list. 
		It worked on the theory that if the count of slots = the count of bookings, there are no slots left.
		Now with max_dupes that no longer works as you can legitimatly have more bookings thatn slots.			
		if(count( $slot_rows ) == count( $booking_rows )){
			if ($res_detail->prevent_dupe_bookings == 'Yes' OR 
				($res_detail->prevent_dupe_bookings == 'Global' AND $apptpro_config->prevent_dupe_bookings == 'Yes')){
					// no dupes		
					$gotSlots = false;
			}
		}
*/
		// The problem now is that we won't know if there are no slots until we walk through the for loop below so we
		// do not know which top row to put in.
		// We will need to build the response as a big string then at the end we can tack the appropriate first option in.

		$options = "";
				
/*		echo '<select name="timeslots" id="timeslots" class="sv_apptpro_request_dropdown" onchange="set_starttime();">';
			if($gotSlots){
				echo "<option value=00:00,00:00 >".JText::_('RS1_INPUT_SCRN_TIMESLOT_PROMPT')."</option>";
			} else {
				echo "<option value=00:00,00:00 >".JText::_('RS1_INPUT_SCRN_NO_TIMESLOTS_AVAILABLE')."</option>";
			}
*/		
		$actual_slots_available = 0;
		for($i=0; $i < count( $slot_rows ); $i++) {
			$slot_row = $slot_rows[$i];
			$ok_to_process_slot = true;
			
			if(count($part_day_bookoffs) > 0){
				// need to check each slot to see if blocked by book-off
				if(blocked_by_bookoff($slot_row, $part_day_bookoffs)){
					$ok_to_process_slot = false;
				}
			}
			
			if($ok_to_process_slot){
				if($res_detail->max_seats != 0){ // a limit has been specified
						$currentcount = getCurrentSeatCount($startdate, $slot_row->db_starttime_24.":00", $slot_row->endtime_24.":00", $res_detail->id_resources);
						if ($currentcount >= $res_detail->max_seats){
						
							// dev only
							//echo "<option value=''>".count_values($slot_row->timeorder, $booking_rows)."</option>";
						
							// IE does not support 'disabled', do not show this slot
							if($browser != "Explorer"){
								//echo "<option value=".$slot_row->starttime_24.",".$slot_row->endtime_24." style='color:cccccc' disabled='disabled'>".$slot_row->timeslot_starttime." - ".$slot_row->timeslot_endtime."</option>";
							}
						} else {
							$options .= "<option value=".$slot_row->starttime_24.",".$slot_row->endtime_24.">".$slot_row->timeslot_starttime." - ".$slot_row->timeslot_endtime;
							if($apptpro_config->show_available_seats == "Yes"){
								$options .= "  (".strval($res_detail->max_seats - $currentcount).")";
							} 
							$options .="</option>";
							$actual_slots_available ++;
						}
				} else {
					// allow dupes
					$options .=  "<option value=".$slot_row->starttime_24.",".$slot_row->endtime_24.">".$slot_row->timeslot_starttime." - ".$slot_row->timeslot_endtime."</option>";
					$actual_slots_available ++;
				}
			}
			
			$k = 1 - $k; 
		}
		$options .= '</select>';

		echo '<select name="timeslots" id="timeslots" class="sv_apptpro_request_dropdown" onchange="set_starttime();setDuration();calcTotal()">';

		if($actual_slots_available == 0){
			echo "<option value=00:00,00:00 >".JText::_('RS1_INPUT_SCRN_NO_TIMESLOTS_AVAILABLE')."</option>";	
		} else {
			echo "<option value=00:00,00:00 >".JText::_('RS1_INPUT_SCRN_TIMESLOT_PROMPT')."</option>";		
		}		
		echo $options;
		
	}

?>
