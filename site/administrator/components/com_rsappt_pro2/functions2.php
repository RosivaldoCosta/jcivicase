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


function addToCalendar($req_id, $apptpro_config, $preventEcho="No"){

	if($apptpro_config->which_calendar != 'None'){
		$database = &JFactory::getDBO();
		// get request info 
		$sql = 'SELECT * FROM #__sv_apptpro2_requests where id_requests = '.$req_id;
		$database->setQuery($sql);
		$row = NULL;
		$row = $database -> loadObject();
		if ($database -> getErrorNum()) {
			if($preventEcho == "No"){
				echo $database -> stderr();
			}
			local_logIt("addToCalendar-addToCalendar-1,".$database->getErrorMsg()); 
			return false;
		}
		
		// get resource info
		$database = &JFactory::getDBO();
		$res_data = NULL;
		$sql = "SELECT * FROM #__sv_apptpro2_resources WHERE id_resources=".$row->resource;
		//echo $sql;
		//exit;
		$database->setQuery($sql);
		$res_data = $database->loadObject();
		if ($database -> getErrorNum()) {
			if($preventEcho == "No"){
				echo $database -> stderr();
			}
			local_logIt("addToCalendar-2,".$database->getErrorMsg()); 
			return false;
		}

		// remove calendar entry
		// First delete calendar record for this request if one exists
		if($apptpro_config->which_calendar == "JEvents"){
			$sql = "DELETE FROM `#__events` WHERE INSTR(extra_info, '[req id:". $row->id_requests ."]')>0";
		} else if($apptpro_config->which_calendar == "JCalPro2"){
			$sql = "DELETE FROM `#__jcalpro2_events` WHERE INSTR(description, '[req id:". $row->id_requests ."]')>0";
		} else if($apptpro_config->which_calendar == "EventList"){
			$sql = "DELETE FROM `#__eventlist_events` WHERE INSTR(datdescription, '[req id:". $row->id_requests ."]')>0";
		} else if($apptpro_config->which_calendar == "Google" and $row->google_event_id != ""){
			include_once( JPATH_SITE."/components/com_rsappt_pro2/svgcal.php" );
			$gcal = new SVGCal;
			// login
				$result = $gcal->login($res_data->google_user, $res_data->google_password);				
			if( $result == "ok"){
				$client = $gcal->getClient();	
				if($client != null){
					if($row->google_calendar_id == ""){
						$gcal->deleteEventById($client, $row->google_event_id);
					} else {
						$result2 = $gcal->deleteEvent($client, $row->google_event_id, $row->google_calendar_id);
						if($result2 != "ok"){
							if($preventEcho == "No"){
								echo $result2;
							}
							local_logIt("addToCalendar-3,".$result2); 
						}
					}
				} else {
					local_logIt("addToCalendar-4,"."$client == null error"); 
				}
			} else {
				if($preventEcho == "No"){
					echo $result;
				}
				local_logIt("addToCalendar-5,".$result); 
			}						
		}				

		//echo $sql;
		//exit();
		$database->setQuery($sql);
		if(!$database->query()){
			if ($database -> getErrorNum()) {
				if($database -> getErrorNum() != 1146){
					// ignore 1146 - table not found if component not installed
					if($preventEcho == "No"){
						echo $database -> stderr();
					}
					local_logIt("addToCalendar-6,".$database->getErrorMsg()); 
					return false;
				}
			}
		}

		if ($row->request_status == 'accepted'){
			// need cal category
			$cat_err = "";
			if($res_data->default_calendar_category == "" and $apptpro_config->which_calendar != "Google"){
				// cannot continue as we have no calendar category
				$cat_err = "No default calendar category has been specified, cannot add calendar entry!";
			} else {
				// get category id from name
				if($apptpro_config->which_calendar == "EventList"){
					$sql = "SELECT id as cat_id FROM #__eventlist_categories WHERE catname='".$res_data->default_calendar_category."'";
					$database->setQuery( $sql);
					$cat_id = null;
					$cat_id = $database->loadResult(); 
					if ($database -> getErrorNum()) {
						$cat_err = "calendar category, db errpr: ".$database -> stderr();
					}
					if($cat_id == null){
						$cat_err = "Invalid calendar category has been specified, cannot add calendar entry! <br>".$sql;
					}
				} else if($apptpro_config->which_calendar == "JEvents"){
					$sql = "SELECT id FROM #__categories WHERE title='".$res_data->default_calendar_category."' ".
					"AND section = 'com_events'";
					$database->setQuery( $sql);
					$cat_id = null;
					$cat_id = $database->loadResult(); 
					if ($database -> getErrorNum()) {
						$cat_err = "calendar category, db errpr: ".$database -> stderr();
					}
					if($cat_id == null){
						$cat_err = "Invalid calendar category has been specified, cannot add calendar entry! <br>".$sql;
					}


				} else if($apptpro_config->which_calendar == "JCalPro2"){
					$sql = "SELECT cat_id FROM #__jcalpro2_categories WHERE cat_name='".$res_data->default_calendar_category."'";
					$database->setQuery( $sql);
					$cat_id = null;
					$cat_id = $database->loadResult(); 
					if ($database -> getErrorNum()) {
						$cat_err = "calendar category, db errpr: ".$database -> stderr();
					}
					if($cat_id == null){
						$cat_err = "Invalid calendar category has been specified, cannot add calendar entry! <br>".$sql;
					}

					$sql = "SELECT cal_id FROM #__jcalpro2_calendars WHERE cal_name='".$res_data->default_calendar."'";
					$database->setQuery( $sql);
					$cal_id = null;
					$cal_id = $database->loadResult(); 
					if ($database -> getErrorNum()) {
						$cal_err = "calendar category, db errpr: ".$database -> stderr();
					}
					if($cat_id == null){
						$cal_err = "Invalid calendar category has been specified, cannot add calendar entry! <br>".$sql;
					}

				} else if($apptpro_config->which_calendar == "JCalPro"){
					$sql = "SELECT cat_id FROM #__jcalpro_categories WHERE cat_name='".$res_data->default_calendar_category."'";
					$database->setQuery( $sql);
					$cat_id = null;
					$cat_id = $database->loadResult(); 
					if ($database -> getErrorNum()) {
						$cat_err = "calendar category, db errpr: ".$database -> stderr();
					}
					if($cat_id == null){
						$cat_err = "Invalid calendar category has been specified, cannot add calendar entry! <br>".$sql;
					}
				}
			}

			if($cat_err !="" or $cal_err !=""){
				if($preventEcho == "No"){
					echo $cat_err." ".$cal_err;
				}
				local_logIt("addToCalendar-7,".$cat_err); 
			}
								
			switch ($apptpro_config->calendar_title) {
			  case 'resource.name': {
				$title_text = $res_data->name;	
				break;
			  }
			  case 'request.name': {
				$title_text = $row->name;	
				break;
			  }
 			  default: {
			    // must be a udf, get udf_value
				$sql = "SELECT udf_value FROM #__sv_apptpro2_udfvalues WHERE request_id = ".$req_id." and udf_id=".$apptpro_config->calendar_title;
				$database->setQuery( $sql);
				$title_text = $database->loadResult(); 		
			  }
			}
			if($apptpro_config->calendar_body2 != "") {
				$body_text = buildMessage($req_id, "calendar_body", "No");
			}
			stripslashes($body_text);
			stripslashes($title_text);
			$body_text = str_replace("'", "`", $body_text);
			$title_text = str_replace("'", "`", $title_text);

	
			if($apptpro_config->which_calendar == "EventList"){
				$sql = "INSERT INTO `#__eventlist_events` (`catsid`,`title`, `dates`, `enddates`,".
				"`times`,`endtimes`, `datdescription`, `published`) VALUES (".
				$cat_id.",".
				"'".$title_text."',".
				"'".$row->startdate. "',".
				"'".$row->enddate."',".
				"'".$row->starttime. "',".
				"'".$row->endtime."',".
				"'".$body_text."<BR />[req id:". $row->id_requests ."]', 1".
				")";
			} else if($apptpro_config->which_calendar == "JEvents"){
				$sql = "INSERT INTO `#__events` (`catid`,`title`,`content`,`useCatColor`,`state`,".
				"`created_by`,`created_by_alias`,`publish_up`,`publish_down`, `extra_info`) VALUES (".
				$cat_id.",".
				"'".$title_text."',".
				"'".$res_detail->name."',".
				"1, 1,".
				"'".$user->id."',".
				"'".$user->name."',".
				"'".$row->startdate. " ".$row->starttime."',".
				"'".$row->enddate. " ".$row->endtime."',".
				"'".$body_text."<BR />[req id:". $row->id_requests."]'".
				")";
			} else if($apptpro_config->which_calendar == "JCalPro"){
				// latest JCalPro stores all times a GMT (UTC-0) so we need to adjust
				// the time based on server time zone setting
				require_once( JPATH_SITE.DS.'configuration.php' );
				$CONFIG = new JConfig();
				$offset = $CONFIG->offset;
				if($apptpro_config->daylight_savings_time == "Yes" 
				   && (strtotime($row->startdate) >= strtotime($apptpro_config->dst_start_date))
				   && (strtotime($row->startdate) <= strtotime($apptpro_config->dst_end_date))){
					$offset = $offset+1;
				}
				if($offset<0){
					$offset_sign = "+";
				} else {
					$offset_sign = "-";
				}	
				$offset = abs($offset);
				
				$startdate = $row->startdate;
				$day = intval(substr($startdate, 8, 2));
				$month = intval(substr($startdate, 5, 2));
				$year = intval(substr($startdate, 0, 4));
				$sql = "INSERT INTO `#__jcalpro_events` (`title` , `cat` , `day` , `month`, `year`, ".
				"`approved` , `start_date` , `end_date` , `recur_type`, `recur_count`, `published`, `description` ) VALUES (". 
				"'".$title_text."',".
				$cat_id.",".
				$day.",".$month.",".$year.",1,".
				"'".$row->startdate. " ".$row->starttime."' ".$offset_sign." INTERVAL ".$offset." HOUR,".
				"'".$row->enddate. " ".$row->endtime."' ".$offset_sign." INTERVAL ".$offset." HOUR,".
				"'', 1, 1, ".
				"'".$body_text." <BR />[req id:". $row->id_requests."]'".
				")";
			} else if($apptpro_config->which_calendar == "JCalPro2"){
				// latest JCalPro stores all times a GMT (UTC-0) so we need to adjust
				// the time based on server time zone setting
				require_once( JPATH_SITE.DS.'configuration.php' );
				$CONFIG = new JConfig();
				$offset = $CONFIG->offset;
				if($apptpro_config->daylight_savings_time == "Yes" 
				   && (strtotime($row->startdate) >= strtotime($apptpro_config->dst_start_date))
				   && (strtotime($row->startdate) <= strtotime($apptpro_config->dst_end_date))){
					$offset = $offset+1;
				}
				if($offset<0){
					$offset_sign = "+";
				} else {
					$offset_sign = "-";
				}	
				$offset = abs($offset);
				
				$startdate = $row->startdate;
				$day = intval(substr($startdate, 8, 2));
				$month = intval(substr($startdate, 5, 2));
				$year = intval(substr($startdate, 0, 4));
				$sql = "INSERT INTO `#__jcalpro2_events` (`title` , `cal_id`, `cat` , `day` , `month`, `year`, ".
				"`approved` , `start_date` , `end_date` , `recur_type`, `recur_count`, `published`, `description` ) VALUES (". 
				"'".$title_text."',".
				$cal_id.",".
				$cat_id.",".
				$day.",".$month.",".$year.",1,".
				"'".$row->startdate. " ".$row->starttime."' ".$offset_sign." INTERVAL ".$offset." HOUR,".
				"'".$row->enddate. " ".$row->endtime."' ".$offset_sign." INTERVAL ".$offset." HOUR,".
				"'', 1, 1, ".
				"'".$body_text." <BR />[req id:". $row->id_requests."]'".
				")";
			} else if($apptpro_config->which_calendar == "Google"){			
				include_once( JPATH_SITE.DS."components".DS."com_rsappt_pro2".DS."svgcal.php" );
				include_once( JPATH_SITE.DS."configuration.php" );
				$CONFIG = new JConfig();
				$offset = $CONFIG->offset;
				if($apptpro_config->daylight_savings_time == "Yes" 
				   && (strtotime($row->startdate) >= strtotime($apptpro_config->dst_start_date))
				   && (strtotime($row->startdate) <= strtotime($apptpro_config->dst_end_date))){
					$offset = $offset+1;
				}
				$offset = local_tz_offset_to_string($offset);
				$gcal = new SVGCal;
				// login
				$result = $gcal->login($res_data->google_user, $res_data->google_password);
				if( $result != "ok"){
					if($preventEcho == "No"){
						echo $result;
					}
					local_logIt("addToCalendar-8,".$result); 
					return false;
				}		
				$gcal->setTZOffset($offset);
				// set calendar
				if($res_data->google_default_calendar_name != ""){
					try{
						$gcal->setCalID($res_data->google_default_calendar_name);
					}catch (Exception $e) { 
						if($preventEcho == "No"){
							echo $e->getMessage();
						}
						local_logIt("addToCalendar-9,".$e->getMessage()); 
						return false;
					} 				
				}	
				
				//create event
				try{
					$event_id_full = $gcal->createEvent( 
					$title_text,
					$body_text, 
					'',
					trim($row->startdate),
					trim($row->starttime),
					trim($row->enddate),
					trim($row->endtime));
				}catch (Exception $e) { 
					if($preventEcho == "No"){
						echo $e->getMessage();
					}
					local_logIt("addToCalendar-10,".$e->getMessage());
//					local_logIt("addToCalendar-10a,".trim($row->startdate));
//					local_logIt("addToCalendar-10b,".trim($row->starttime));
//					local_logIt("addToCalendar-10c,".trim($row->enddate));
//					local_logIt("addToCalendar-10d,".trim($row->endtime));
					return false;					
				} 				
					
				$event_id = substr($event_id_full, strrpos($event_id_full, "/")+1);
				// set event ID back in request
				$database = &JFactory::getDBO();
					$sql = "UPDATE #__sv_apptpro2_requests SET google_event_id = '".$event_id."', ".
					"google_calendar_id = '".$res_data->google_default_calendar_name."' where id_requests = ".$req_id;
				$database->setQuery($sql);
				if(!$database->query()){
					if($preventEcho == "No"){
						echo $database -> stderr();
					}
					local_logIt("addToCalendar-11,".$database->getErrorMsg()); 
				}
			}
	
			//echo $sql;exit;
			//logIt($sql); 
	
			$database = &JFactory::getDBO();
			$database->setQuery($sql);
			if(!$database->query()){
				if ($database -> getErrorNum()) {
					//if($database -> getErrorNum() != 1146){
						// ignore 1146 - table not found if component not installed	
						if($preventEcho == "No"){
							echo $database -> stderr();
						}
						local_logIt("addToCalendar-12,".$database->getErrorMsg()); 
						return false;
					//}
				}
			}
		}
	} //end if($apptpro_config->which_calendar != 'None')
	return true;
}

function count_values($value_to_count, $array_to_check){
	// counts $value_to_count in $array_to_check
	$count = 0;
	if(in_array($value_to_count, $array_to_check)){
		foreach ($array_to_check as $value) {
		    if($value == $value_to_count){
				$count ++;
			}	
		}
		unset($value);		
	}
	
	return $count;		
}

function validEmail($email){
	/**
	Validate an email address.
	Provide email address (raw input)
	Returns true if the email address has the email 
	address format and the domain exists.
	*/
   $isValid = true;
   $atIndex = strrpos($email, "@");
   if (is_bool($atIndex) && !$atIndex)
   {
      $isValid = false;
   }
   else
   {
      $domain = substr($email, $atIndex+1);
      $local = substr($email, 0, $atIndex);
      $localLen = strlen($local);
      $domainLen = strlen($domain);
      if ($localLen < 1 || $localLen > 64)
      {
         // local part length exceeded
         $isValid = false;
      }
      else if ($domainLen < 1 || $domainLen > 255)
      {
         // domain part length exceeded
         $isValid = false;
      }
      else if ($local[0] == '.' || $local[$localLen-1] == '.')
      {
         // local part starts or ends with '.'
         $isValid = false;
      }
      else if (preg_match('/\\.\\./', $local))
      {
         // local part has two consecutive dots
         $isValid = false;
      }
      else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain))
      {
         // character not valid in domain part
         $isValid = false;
      }
      else if (preg_match('/\\.\\./', $domain))
      {
         // domain part has two consecutive dots
         $isValid = false;
      }
      else if(!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/',
                 str_replace("\\\\","",$local)))
      {
         // character not valid in local part unless 
         // local part is quoted
         if (!preg_match('/^"(\\\\"|[^"])+"$/',
             str_replace("\\\\","",$local)))
         {
            $isValid = false;
         }
      }
   }
   return $isValid;
}

function getMinute($strTimeval){
	// gets the minute of the day
	$hours = intval(substr($strTimeval,0,2));
	$mins = intval(substr($strTimeval,3,2));
	return ($hours*60)+$mins;
}

function showrow($res_detail, $grid_date, $weekday){
	//return false if this date is a bookoff or a non-work day, or limited by dates before/after for this resource
	
	//if $grid_date < now, return false. This can happen id the user uses the '<<-' button to move te grid into the past
	if(strtotime($grid_date." 23:59") < strtotime('now')){
		return false;
	}
	// bookoffs
	$database = &JFactory::getDBO(); 
	$sql = "SELECT count(*) FROM #__sv_apptpro2_bookoffs WHERE resource_id=".$res_detail->id_resources." AND off_date='".$grid_date."' ".
		" AND full_day='Yes' AND published=1";
	$database->setQuery( $sql );
	//echo $sql;
	//exit;
	if($database->loadResult()>0){
		return false;
	}
	if ($database -> getErrorNum()) {
		echo "DB Err: ". $database -> stderr();
		logIt($database->getErrorMsg()); 
		exit;
	}
	switch($weekday){
		 case '0': {
			if($res_detail->allowSunday == "No"){ return false;}
			break;
		  }
		 case '1': {
			if($res_detail->allowMonday == "No"){ return false;}
			break;
		  }
		 case '2': {
			if($res_detail->allowTuesday == "No"){ return false;}
			break;
		  }
		 case '3': {
			if($res_detail->allowWednesday == "No"){ return false;}
			break;
		  }
		 case '4': {
			if($res_detail->allowThursday == "No"){ return false;}
			break;
		  }
		 case '5': {
			if($res_detail->allowFriday == "No"){ return false;}
			break;
		  }
		 case '6': {
			if($res_detail->allowSaturday == "No"){ return false;}
			break;
		  }
	}

	if($res_detail->disable_dates_before != "Today" and	$res_detail->disable_dates_before != "Tomorrow"){
		// resource has specific dates set
		if(strtotime($grid_date." 23:59") < strtotime($res_detail->disable_dates_before)){
			return false;
		}	
	} 
	if($res_detail->disable_dates_before == "Tomorrow"){
		if(strtotime($grid_date) <= strtotime("now")){
			return false;
		}	
	}

	if($res_detail->disable_dates_before == "XDays"){
		if(strtotime($grid_date) < strtotime("+ ".strval($res_detail->disable_dates_before_days)." day")){
			return false;
		}	
	}


	if($res_detail->disable_dates_after != "Not Set" and $res_detail->disable_dates_after != "XDays"){
		// resource has specific dates set
		if(strtotime($grid_date) > strtotime($res_detail->disable_dates_after)){
			return false;
		}	
	}

	if($res_detail->disable_dates_after == "XDays"){
		if(strtotime($grid_date) >= strtotime("+ ".strval($res_detail->disable_dates_after_days)." day")){
			return false;
		}	
	}
	
	return true;

}

function fullyBooked($booking, $res_row, $apptpro_config){
	// OLD
	// if prevent dupes = yes 
	// AND # of bookings > maxdupes, return true.
/*	if($res_row->prevent_dupe_bookings == 'Yes' ||
		($res_row->prevent_dupe_bookings == 'Global' AND $apptpro_config->prevent_dupe_bookings == 'Yes')){

		$database = &JFactory::getDBO(); 
		$sql = "SELECT count(*) FROM #__sv_apptpro2_requests WHERE resource = ".$res_row->id_resources." AND startdate = '".$booking->startdate."'";
		$sql .=" AND starttime='".$booking->starttime."' AND (request_status='accepted' OR request_status='pending') ORDER BY starttime";
		$database->setQuery( $sql );
		$dupescount = $database->loadResult();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
			logIt($database->getErrorMsg()); 
			return false;
		}
		if($dupescount > $res_row->max_dupes){
			return true;
		} else {
			return false;
		}
		
	}
*/
	// NEW
	// max_seats = 0 = no limit
	if($res_row->max_seats == 0){
		return false;
	}	
	// max_seats reached?
	$database = &JFactory::getDBO(); 
	if($booking->booked_seats >= $res_row->max_seats){
		return true;
	}	
	// now check to see if there are other bookings and if so how many total seats are booked.
	$currentcount = getCurrentSeatCount($booking->startdate, $booking->starttime, $booking->endtime, $booking->resource);
	if ($currentcount >= $res_row->max_seats){
		return true;
	}

}

function DateAdd($interval, $number, $date) {

/*
		yyyy year 
		q quarter 
		q quarter 
		m month 
		y day of the year 
		d day 
		w weekday 
		ww week 
		h hour 
		n minute 
		s second 
*/

    $date_time_array = getdate($date);
    $hours = $date_time_array["hours"];
    $minutes = $date_time_array["minutes"];
    $seconds = $date_time_array["seconds"];
    $month = $date_time_array["mon"];
    $day = $date_time_array["mday"];
    $year = $date_time_array["year"];

    switch ($interval) {
    
        case "yyyy":
            $year+=$number;
            break;
        case "q":
            $year+=($number*3);
            break;
        case "m":
            $month+=$number;
            break;
        case "y":
        case "d":
        case "w":
            $day+=$number;
            break;
        case "ww":
            $day+=($number*7);
            break;
        case "h":
            $hours+=$number;
            break;
        case "n":
            $minutes+=$number;
            break;
        case "s":
            $seconds+=$number; 
            break;            
    }
       $timestamp= mktime($hours,$minutes,$seconds,$month,$day,$year);
    return $timestamp;
}

function getBookOffDescription($res_detail, $grid_date){
	$database = &JFactory::getDBO(); 
	$sql = "SELECT description FROM #__sv_apptpro2_bookoffs WHERE resource_id=".$res_detail->id_resources." AND off_date='".$grid_date."' AND published=1";
	$database->setQuery( $sql );
	$row = $database -> loadObject();
	//echo $sql;
	//exit;
	return $row;
}


function getDayNamesArray(){
	return array(JText::_('RS1_SUN'),
	JText::_('RS1_MON'),
	JText::_('RS1_TUE'),
	JText::_('RS1_WED'),
	JText::_('RS1_THU'),
	JText::_('RS1_FRI'),
	JText::_('RS1_SAT'),
	);
}

function getLongDayNamesArray($starting = 0){
	if($starting == 0){
		return array(JText::_('RS1_SUNDAY'),
				JText::_('RS1_MONDAY'),
				JText::_('RS1_TUESDAY'),
				JText::_('RS1_WEDNESDAY'),
				JText::_('RS1_THURSDAY'),
				JText::_('RS1_FRIDAY'),
				JText::_('RS1_SATURDAY'),
				);
	} else {
		return array(JText::_('RS1_MONDAY'),
				JText::_('RS1_TUESDAY'),
				JText::_('RS1_WEDNESDAY'),
				JText::_('RS1_THURSDAY'),
				JText::_('RS1_FRIDAY'),
				JText::_('RS1_SATURDAY'),
				JText::_('RS1_SUNDAY'),
				);
	}	

}

function getMonthNamesArray(){
	return array(JText::_('RS1_JANUARY'),
	JText::_('RS1_FEBRUARY'),
	JText::_('RS1_MARCH'),
	JText::_('RS1_APRIL'),
	JText::_('RS1_MAY'),
	JText::_('RS1_JUNE'),
	JText::_('RS1_JULY'),
	JText::_('RS1_AUGUST'),
	JText::_('RS1_SEPTEMBER'),
	JText::_('RS1_OCTOBER'),
	JText::_('RS1_NOVEMBER'),
	JText::_('RS1_DECEMBER'),
	);
}


function getCBdata($cb_field_name, $userid){

	if($cb_field_name == ""){
		return;
	}
	
	$database = &JFactory::getDBO();
	$database->setQuery("SELECT ".$cb_field_name." FROM #__comprofiler WHERE user_id = ".$userid );
	$retval = $database->loadResult();
	if ($database -> getErrorNum()) {
		if($database -> getErrorNum() != 1146){
			// ignore 1146 - table not found if component not installed
			echo $database -> stderr();
			return false;
		}
		return false;
	}
	return $retval;
	
}

function getJSdata($js_field_name, $userid){
	if($userid == ""){
		return;
	}
	$jspath = JPATH_ROOT . DS . 'components' . DS . 'com_community';
	include_once($jspath. DS . 'libraries' . DS . 'core.php');

	$JSuser =& CFactory::getUser($userid);
	return $JSuser->getInfo($js_field_name);
}


function blocked_by_bookoff($slot, $part_day_bookoffs){
	// Note: $part_day_bookoffs may be multiple ranges for single day
	// If slot starts during bookoff time return true. No test made to determine if slot ends during bookoff 
	// as bookoff range should be made on timeslot boundaries.
	foreach ($part_day_bookoffs as $part_day_bookoff){ 
		if(strtotime($slot->timeorder)+1 >= strtotime($part_day_bookoff->bookoff_starttime)
		 && strtotime($slot->timeorder)+1 <= strtotime($part_day_bookoff->bookoff_endtime)){
			return true;
		}
	}
	 
	return false;
}



function saveToDB($name,$user_id,$phone,$email,$sms_reminders,$sms_phone,$sms_dial_code,$resource,
		$service_name,$startdate,$starttime,$enddate,$endtime,$request_status,$cancel_code,$grand_total,$ammount_due,
		$coupon_code,$booked_seats,$applied_credit,$comment, $admin_comment=''){
		$lang =& JFactory::getLanguage();
		
		$database = &JFactory::getDBO();
		
		// ABPro uses a session variable to prevent duplicate booking if the user does a refresh on the confimration screen.
		// IF the user sits on thet screen for 20 minutes (or whatever your session timeout period is) then does a browser refresh
		// the session variable will be gone and ABPro cannot tell that the cached data from the browser is not new data.
		// Fortunately it seems very rare.
		// I have tried all the tricks for telling the browser not to cache the form but (from searching the Internet) there seems to be 
		// no solution that works universally.
		// If you encounter problems with people refreshing after session time out there are a couple of options:
		// 1.) Put a messge on the confimration screen, like you often see on web sites, telling the person to refrain from using the 
		//    back button or refreshing as it may cause a duplicate booking.
		// 2.) Uncomment thet code below which does a chcek to see if the booking is a duplicate. The down side of this is tat some sites
		//    allow duplictaes and this will stop them.
		
		// check for dupe caused by refresh after session timeout
//		$sql = "Select count(*) FROM #__sv_apptpro2_requests WHERE ".
//		"name = '".$database->getEscaped($name)."' ".
//		"AND resource = '".$resource."' ".
//		"AND startdate = '".$startdate."' ".
//		"AND starttime = '".$starttime."' ".
//		"AND enddate = '".$enddate."' ".
//		"AND endtime = '".$endtime."' ".
//		"AND booked_seats = '".$booked_seats."' ".
//		"AND request_status = '".$request_status."' ";
//		$database->setQuery($sql);
//		$dupe_check = $database->loadResult(); 
//		if (!$database->query()) {
//			$err = $database->getErrorMsg();
//			echo $err;
//			exit;
//		} 
//		if($dupe_check > 0){
//			echo "Duplicate booking not saved";
//			exit;
//		}

		if($ammount_due == 0.00){
			$payment_status = "paid";
		} else {
			$payment_status = "pending";
		}

		if($applied_credit == ""){
			$applied_credit = 0;
		}

		// Check again for no overlap - this was checked in validation so it will only fail if someone 
		// has booked in the time it took the validation to get back to the client and the form submit itself
		//(a second or two?)
		// get resource info for the selected resource
		$sql = 'SELECT * FROM #__sv_apptpro2_resources where id_resources = '.$resource;
		$database->setQuery($sql);
		$res_detail = NULL;
		$res_detail = $database -> loadObject();

		$mystartdatetime = "STR_TO_DATE('".$startdate ." ". $starttime ."', '%Y-%m-%d %T')+ INTERVAL 1 SECOND";
		$myenddatetime = "STR_TO_DATE('".$enddate ." ". $endtime ."', '%Y-%m-%d %T')- INTERVAL 1 SECOND";
		$sql = "select count(*) from #__sv_apptpro2_requests "
		." where (resource = '". $resource ."')"
		." and (request_status = 'accepted' or request_status = 'pending' )"
		." and ((". $mystartdatetime ." >= STR_TO_DATE(CONCAT(DATE_FORMAT(startdate, '%Y-%m-%d') , DATE_FORMAT(starttime, ' %T')), '%Y-%m-%d %T') and ". $mystartdatetime ." <= STR_TO_DATE(CONCAT(DATE_FORMAT(enddate, '%Y-%m-%d') , DATE_FORMAT(endtime, ' %T')), '%Y-%m-%d %T'))"
		." or (". $myenddatetime ." >= STR_TO_DATE(CONCAT(DATE_FORMAT(startdate, '%Y-%m-%d') , DATE_FORMAT(starttime, ' %T')), '%Y-%m-%d %T') and ". $myenddatetime ." <= STR_TO_DATE(CONCAT(DATE_FORMAT(enddate, '%Y-%m-%d') , DATE_FORMAT(endtime, ' %T')), '%Y-%m-%d %T'))"
		." or (STR_TO_DATE(CONCAT(DATE_FORMAT(startdate, '%Y-%m-%d') , DATE_FORMAT(starttime, ' %T')), '%Y-%m-%d %T') >= ". $mystartdatetime ." and STR_TO_DATE(CONCAT(DATE_FORMAT(startdate, '%Y-%m-%d') , DATE_FORMAT(starttime, ' %T')), '%Y-%m-%d %T') <= ". $myenddatetime .")"
		." or (STR_TO_DATE(CONCAT(DATE_FORMAT(enddate, '%Y-%m-%d') , DATE_FORMAT(endtime, ' %T')), '%Y-%m-%d %T') >= ". $mystartdatetime ." and STR_TO_DATE(CONCAT(DATE_FORMAT(enddate, '%Y-%m-%d') , DATE_FORMAT(endtime, ' %T')), '%Y-%m-%d %T') <= ". $myenddatetime ."))";
		//echo $sql;
		//exit();
		$database->setQuery( $sql );
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
			return -1;
		}
		$overlapcount = $database->loadResult();
		if ($overlapcount >= $res_detail->max_seats && $res_detail->max_seats > 0 ){
			echo JText::_('RS1_INPUT_SCRN_CONFLICT_ERR');
			// serious problem, bail out
			exit;
		}

		// save to db
		$sSql = "INSERT INTO #__sv_apptpro2_requests(".
		"name, ".
		"user_id, ".
		"phone, ".
		"email, ".
		"sms_reminders, ".
		"sms_phone, ".
		"sms_dial_code, ".
		"resource, ".
		"service, ".
		"startdate, ".
		"starttime, ".
		"enddate, ".
		"endtime, ".
		"request_status, ".
		"payment_status, ".
		"cancellation_id, ".
		"booking_total, ".
		"booking_due, ".
		"credit_used, ".
		"coupon_code, ".
		"booked_seats, ".
		"admin_comment, ".
		"booking_language, ".
		"comment ";
		$sSql = $sSql.") VALUES(".
		"'".$database->getEscaped($name)."',".
		"'".$user_id."',".
		"'".$database->getEscaped($phone)."',".
		"'".$database->getEscaped($email)."',".
		"'".$sms_reminders."',".
		"'".$database->getEscaped($sms_phone)."',".
		"'".$sms_dial_code."',".
		"'".$resource."',".
		"'".$database->getEscaped($service_name)."',".
		"'".$startdate."',".
		"'".$starttime."',".
		"'".$enddate."',".
		"'".$endtime."',".
		"'".$request_status."',".
		"'".$payment_status."',".
		"'".$cancel_code."',".
		$grand_total.",".
		$ammount_due.",".
		$applied_credit.",".
		"'".$coupon_code."',".
		"'".$booked_seats."',".
		"'".$database->getEscaped($admin_comment)."',".
		"'".$lang->getTag()."',".
		"'".$database->getEscaped($comment)."'";
		$sSql = $sSql.")";
		//echo $sSql;
		//exit;
		$database->setQuery($sSql);
	
		if (!$database->query()) {
			$err = $database->getErrorMsg();
			echo $err;
			exit;
		} 

		// need request id to pass through to PayPal (so PP can pass it back with IPN)
	 	$sSql = "SELECT LAST_INSERT_ID() AS last_id";
		$database->setQuery($sSql);
		$last_id = NULL;
		$last_id = $database -> loadObject();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
			return -1;
		}
		
		// if credit used..
		if(floatval($applied_credit) > 0.00){
			// adjust credit balance is
			$sql = "UPDATE #__sv_apptpro2_user_credit SET balance = balance - ".$applied_credit." WHERE user_id = ".$user_id;
			$database->setQuery($sql);
			$database->query();
			if ($database -> getErrorNum()) {
				echo $database -> stderr();
				return -1;
			}			
			
			// add credit audit
			$sql = 'INSERT INTO #__sv_apptpro2_user_credit_activity (user_id, request_id, decrease, comment, operator_id, balance) '.
			"VALUES (".$user_id.",".
			$last_id->last_id.",".
			$applied_credit.",".
			"'".JText::_('RS1_ADMIN_CREDIT_ACTIVITY_CREDIT_USED')."',".
			$user_id.",".
			"(SELECT balance from #__sv_apptpro2_user_credit WHERE user_id = ".$user_id."))";
			$database->setQuery($sql);
			$database->query();
			if ($database -> getErrorNum()) {
				echo $database -> stderr();
				return -1;
			}
			
			// if paid in full by credit, set paystatus to paid
			if(floatval($ammount_due)==0.00){
				$sql = "UPDATE #__sv_apptpro2_requests SET payment_status = 'paid' WHERE id_requests = ".$last_id->last_id;
				$database->setQuery($sql);
				$database->query();
				if ($database -> getErrorNum()) {
					echo $database -> stderr();
					return -1;
				}			
			}
		}
		
		return $last_id;		
}

function GoToPayPal($request_id, $apptpro_config, $grand_total, $from_screen, $from_screen_itemid){

		if($apptpro_config->paypal_use_sandbox == "Yes"){
			$paypal_url = $apptpro_config->paypal_sandbox_url; 
		} else {
			$paypal_url = $apptpro_config->paypal_production_url; 
		}
		
		// check for request specific PayPal account 
		$database = &JFactory::getDBO();
		$sql = "SELECT #__sv_apptpro2_resources.paypal_account FROM #__sv_apptpro2_requests ".
		"  INNER JOIN #__sv_apptpro2_resources ON #__sv_apptpro2_requests.resource = #__sv_apptpro2_resources.id_resources ".
		" WHERE #__sv_apptpro2_requests.id_requests = ".$request_id;
		//echo $sql;
		//exit;
		$database->setQuery($sql);
		$res_paypal_account = $database->loadResult();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
			logIt($database->getErrorMsg()); 
			exit;
		}
		if($res_paypal_account == ""){
			$paypal_account = $apptpro_config->paypal_account;
		} else {
			$paypal_account = $res_paypal_account;
		}
		
		$paypal_url = $paypal_url.'?cmd=_xclick&currency_code='.$apptpro_config->paypal_currency_code.
		"&business=" .$paypal_account.
		"&return=".JURI::base().urlencode("index.php?option=com_rsappt_pro2&view=".$from_screen."&Itemid=".$from_screen_itemid."&task=pp_return"). 
		"&notify_url=".JURI::base().urlencode("index.php?option=com_rsappt_pro2&controller=admin&task=ipn"). 
		"&charset=UTF-8";
		if($apptpro_config->paypal_itemname ==""){
			$paypal_url .= "&item_name=".JText::_($res_detail->description).": ".$startdate." ".$starttime;
		} else {
			$itemname = processTokens($request_id, JText::_($apptpro_config->paypal_itemname));
			$paypal_url .= "&item_name=".$itemname;
		}
		if($apptpro_config->paypal_on0 !="" && $apptpro_config->paypal_os0 !=""){
			$on0 = processTokens($request_id, JText::_($apptpro_config->paypal_on0));
			$os0 = processTokens($request_id, JText::_($apptpro_config->paypal_os0));
			$paypal_url .= "&on0=".$on0.
			"&os0=".$os0;
		}
		if($apptpro_config->paypal_on1 !="" && $apptpro_config->paypal_os1 !=""){
			$on1 = processTokens($request_id, JText::_($apptpro_config->paypal_on1));
			$os1 = processTokens($request_id, JText::_($apptpro_config->paypal_os1));
			$paypal_url .= "&on1=".$on1.
			"&os1=".$os1;
		}
		if($apptpro_config->paypal_on2 !="" && $apptpro_config->paypal_os2 !=""){
			$on2 = processTokens($request_id, JText::_($apptpro_config->paypal_on2));
			$os2 = processTokens($request_id, JText::_($apptpro_config->paypal_os2));
			$paypal_url .= "&on2=".$on2.
			"&os2=".$os2;
		}
		if($apptpro_config->paypal_on3 !="" && $apptpro_config->paypal_os3 !=""){
			$on3 = processTokens($request_id, JText::_($apptpro_config->paypal_on3));
			$os3 = processTokens($request_id, JText::_($apptpro_config->paypal_os3));
			$paypal_url .= "&on3=".$on3.
			"&os3=".$os3;
		}
		$paypal_url .= "&amount=".$grand_total.
		"&custom=".strval($request_id);
		if($apptpro_config->paypal_logo_url != ""){
			$paypal_url .= "&image_url=".$apptpro_config->paypal_logo_url;
		}
		//echo $paypal_url;
		//exit;				
		header("Location: ".$paypal_url);
		exit;	

}



function getDefaultCalInfo($which_calendar, $res_data, &$cat_id, &$cal_id){
	$database = &JFactory::getDBO();

	// get category id from name
	if($which_calendar == "EventList"){
		$sql = "SELECT id as cat_id FROM #__eventlist_categories WHERE catname='".$res_data->default_calendar_category."'";
		$database->setQuery( $sql);
		$cat_id = null;
		$cat_id = $database->loadResult(); 

	} else if($which_calendar == "JEvents"){
		$sql = "SELECT id FROM #__categories WHERE title='".$res_data->default_calendar_category."' ".
		"AND section = 'com_events'";
		$database->setQuery( $sql);
		$cat_id = null;
		$cat_id = $database->loadResult(); 

	} else if($which_calendar == "Thyme"){
		$sql = "SELECT id FROM thyme_calendars WHERE title='".$res_data->default_calendar_category."' ";
		$database->setQuery( $sql);
		$cat_id = null;
		$cat_id = $database->loadResult(); 

	} else if($which_calendar == "JCalPro2"){
		$sql = "SELECT cat_id FROM #__jcalpro2_categories WHERE cat_name='".$res_data->default_calendar_category."'";
		$database->setQuery( $sql);
		$cat_id = null;
		$cat_id = $database->loadResult(); 

		$sql = "SELECT cal_id FROM #__jcalpro2_calendars WHERE cal_name='".$res_data->default_calendar."'";
		$database->setQuery( $sql);
		$cal_id = null;
		$cal_id = $database->loadResult(); 

	} else if($which_calendar == "JCalPro"){
		$sql = "SELECT cat_id FROM #__jcalpro_categories WHERE cat_name='".$res_data->default_calendar_category."'";
		$database->setQuery( $sql);
		$cat_id = null;
		$cat_id = $database->loadResult(); 
	}
	return;
}

function purgeStalePayPalBookings($minutes_to_stale){
	// If a customer goes to PayPal then bails out without paying PayPal sends no IPN back and so the booking is left 'pending' and locked
	// This function is called when a user opens the bookings screen (if Purge Stale PayPal = Yes in config)
	// This function looks for bookings with status 'pending' and create timestamp + current time > Minutes to Stale in config, if found the booking
	// is set to status 'timeout' to free the timeslot for another user.
	// Note: booking timestamp and this function are both server time so no timezone adjustment is required.

	$database = &JFactory::getDBO();
		$sql = "UPDATE #__sv_apptpro2_requests SET request_status = 'timeout' ".
		" WHERE request_status = 'pending' ".
		" AND DATE_ADD(created, INTERVAL ".$minutes_to_stale." MINUTE) < NOW()";
	$database->setQuery($sql);
	if(!$database->query()){
		echo $database -> stderr();
		logIt($database->getErrorMsg()); 
	}
}

function local_logIt($err){
	$database = &JFactory::getDBO();
	$errsql = "insert into #__sv_apptpro2_errorlog (description) ".
		" values('".$database->getEscaped(substr($err,0))."')";
	$database->setQuery($errsql);
	$database->query();

}

function local_tz_offset_to_string($tzoffset){
	// converts 
	// "0"	-> "+00:00"
	// "2"	-> "+02:00"
	// "2.5"	-> "+02:30"
	// 10	-> "+10:00"
	// 10.5 -> "+10:30"
	// -2	-> "-02:00"
	// -2.5	-> "-02:30"
	// -10	-> "-10:00"
	// -10.5-> "-10:30"
	
	$valOffset = strval($tzoffset);
	if($valOffset == 0){
		return "+00:00";
	}
	$offset_hr_min = explode(".", $tzoffset);
	if($offset_hr_min[1] == "5"){
		$offset_min = ":30";
	}else{
		$offset_min = ":00";
	}	

	if($valOffset > 0){
		// + offset
		if(strval($offset_hr_min[0]) < 10){
			$offset_hour = "+0".$offset_hr_min[0];
		} else {
			$offset_hour = "+".$offset_hr_min[0];
		}
	}
	if($valOffset < 0){	
		// - offset
		if(abs(strval($offset_hr_min[0])) < 10){
			$offset_hour = substr($offset_hr_min[0],0,1)."0".substr($offset_hr_min[0],1);			
		} else {
			$offset_hour = $offset_hr_min[0];
		}
	}	
	return $offset_hour.$offset_min;
}	


?>