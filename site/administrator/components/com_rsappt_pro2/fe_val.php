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

	include_once( JPATH_SITE."/components/com_rsappt_pro2/functions2.php" );

	header('Content-Type: text/xml'); 
	header("Cache-Control: no-cache, must-revalidate");
	//A date in the past
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	
	//$err = 'Validation Failed:<br>';
	$err = JText::_('RS1_INPUT_SCRN_VALIDATION_FAILED');

	
	// recives the user's selected resource and date
	$name = JRequest::getVar('name');
	$phone = JRequest::getVar('phone');
	$email = JRequest::getVar('email');
	$udf_count = JRequest::getVar('udf_count');
	for($i=0; $i<$udf_count; $i++){
		$udf_name = "user_field".$i."_label";		
		$user_field_labels[$i] = JRequest::getVar($udf_name);
		$udf_name = "user_field".$i."_value";		
		$user_field_values[$i] = JRequest::getVar($udf_name);
		$udf_name = "user_field".$i."_is_required";		
		$user_field_required[$i] = (JRequest::getVar($udf_name) == "Yes"? "Yes": "No");
	}
	//$err = $err.print_r($user_field_values);
	//$err = $err.print_r($user_field_required);
	//$err = $err.print_r($user_field_labels);

	$resource = JRequest::getVar('resource');
	$category_id = JRequest::getVar('category_id');
	$startdate = JRequest::getVar('startdate');
	$starttime = JRequest::getVar('starttime');
	$enddate = JRequest::getVar('enddate');
	$endtime = JRequest::getVar('endtime');	
	$booked_seats = JRequest::getVar('booked_seats', 1);	
	$user_id = JRequest::getVar('user_id', "");	


	// get config info
	$database = &JFactory::getDBO(); 
	$sql = 'SELECT * FROM #__sv_apptpro2_config';
	$database->setQuery($sql);
	$apptpro_config = NULL;
	$apptpro_config = $database -> loadObject();
	if ($database -> getErrorNum()) {
		echo $database -> stderr();
		exit;
	}

	if ($name == "" ) {
		$err = $err.JText::_('RS1_INPUT_SCRN_NAME_ERR');
	}
	

	if ($apptpro_config->requirePhone == "Yes" && $phone == "" ) {
		$err = $err.JText::_('RS1_INPUT_SCRN_PHONE_ERR');
	}
/*	if(!ereg("^[0-9]{3}-[0-9]{3}-[0-9]{4}$", $phone)) {
		$err = $err.JText::_('RS1_INPUT_SCRN_PHONE_ERR');
	}
*/	
	if ($apptpro_config->requireEmail == "Yes"){
		if( $email == "" ) {
			$err = $err.JText::_('RS1_INPUT_SCRN_EMAIL_ERR');
		} else if(!validEmail($email)){
			$err = $err.JText::_('RS1_INPUT_SCRN_EMAIL_ERR');
		}
	}

	for($i=0; $i<$udf_count; $i++){
		if($user_field_required[$i] == "Yes" && $user_field_values[$i] == ""){
			$err = $err.JText::_($user_field_labels[$i].JText::_('RS1_INPUT_SCRN_UDF_ERR'));
		}
	}

	if($category_id == "0" ) {
		$err = $err.JText::_('RS1_INPUT_SCRN_CATEGORY_ERR');
	} else {
	
		if ($resource == "-1" ) {
			// gad, no timeslot selected
			$err = $err.JText::_('RS1_INPUT_SCRN_TIMESLOT_PROMPT')."<BR>";
		} else {
			if ($resource == "0" ) {
				$err = $err.JText::_('RS1_INPUT_SCRN_RESOURCE_ERR')."<BR>";
			} else {
				if ($startdate == trim(JText::_('RS1_INPUT_SCRN_DATE_PROMPT')) ) {
					$err = $err.JText::_('RS1_INPUT_SCRN_DATE_PROMPT')."<BR>";
				} else {
					if ($starttime == "00" || $starttime == "00:00" || $starttime == "") {
						$err = $err.JText::_('RS1_INPUT_SCRN_TIMESLOT_PROMPT')."<BR>";
					} 		
				}
			}
		}
	}

	if($booked_seats == 0 ){
		$err = $err.JText::_('RS1_INPUT_SCRN_SEATS_ERR');
	}

	if ($resource != "0"){
		// get resource info for the selected resource
		$sql = 'SELECT * FROM #__sv_apptpro2_resources where id_resources = '.$resource;
		$database->setQuery($sql);
		$res_detail = NULL;
		$res_detail = $database -> loadObject();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
			exit;
		}
	}

	if ($resource != "0" AND $resource != "-1"){ // user has seleted a timeslot, validate it
		// get 'current time' as server time adjusted for Joomla time zone
		$CONFIG = new JConfig();
		$offset = $CONFIG->offset;
		if($apptpro_config->daylight_savings_time == "Yes"){
			$offset = $offset+1;
		}

		// Due to variations in how server time is set by some hosts determining the current, 
		// local date/time can be problematic.
		// On some servers the line below will not correctly produce the current date/time
		// If you are using same day booking and minimum lead time is not working you can try 
		// commenting out the line below and un-commenting the 3 subsequent lines.
		$current_date_time = strtotime($offset);	
		
//		$offsetdate = JFactory::getDate();
//		$offsetdate->setOffset($offset);
//		$current_date_time = $offsetdate->toUnix(true);

		$appointment_start = strtotime($startdate." ".$starttime);
		$first_allowable_start = strtotime("+".strval($res_detail->min_lead_time)." hour", $current_date_time);

//		echo "<br>".date('l jS \of F Y h:i:s A', $current_date_time)."<br>";
//		echo "<br>".date('l jS \of F Y h:i:s A',$appointment_start)."<br>";
//		echo "<br>".date('l jS \of F Y h:i:s A',$first_allowable_start )."<br>";
	
		// if booking for today, make sure resource allows that
		if($res_detail->min_lead_time == ""){ $res_detail->min_lead_time = 0; }
		if($startdate == strftime("%Y-%m-%d",$current_date_time)){
			if($res_detail->disable_dates_before != "Today"){
				$err = $err.JText::_('RS1_INPUT_SCRN_NO_CURRENT_DAY_ERR');
			}
		} 
		
		// check lead time	
		if($appointment_start <= $first_allowable_start) {
			// start time of booking is in the past or not far enough in the furture
			$err = $err.JText::_('RS1_INPUT_SCRN_TIME_PASSED_ERR');
		} 
						
		// if request_status = 'accepted', check max seats not exceeded
		// first just see if this booking's seats > the resource's
		if($resource != "-1"){
			if($res_detail->max_seats > 0 ){
				if($booked_seats > $res_detail->max_seats){
					$err = $err.JText::_('RS1_ADMIN_SCRN_EXCEED_SEATS')."<BR />";
				} else {	
					// now check to see if there are other bookings and if so how many total seats are booked.
					$currentcount = getCurrentSeatCount($startdate, $starttime, $endtime, $resource);
					if ($currentcount + $booked_seats > $res_detail->max_seats){
						$err = $err.JText::_('RS1_ADMIN_SCRN_EXCEED_SEATS')."<BR />";
					}
				}
			}
		}
		// Still need to check for no overlap
		$mystartdatetime = "STR_TO_DATE('".$startdate ." ". $starttime ."', '%Y-%m-%d %T')+ INTERVAL 1 SECOND";
		$myenddatetime = "STR_TO_DATE('".$enddate ." ". $endtime ."', '%Y-%m-%d %T')- INTERVAL 1 SECOND";
		$sql = "select count(*) from #__sv_apptpro2_requests "
		." where (resource = '". $resource ."')"
		." and (request_status = 'accepted' or request_status = 'pending' )"
		." and ((". $mystartdatetime ." >= STR_TO_DATE(CONCAT(DATE_FORMAT(startdate, '%Y-%m-%d') , DATE_FORMAT(starttime, ' %T')), '%Y-%m-%d %T') and ". $mystartdatetime ." <= STR_TO_DATE(CONCAT(DATE_FORMAT(enddate, '%Y-%m-%d') , DATE_FORMAT(endtime, ' %T')), '%Y-%m-%d %T'))"
		." or (". $myenddatetime ." >= STR_TO_DATE(CONCAT(DATE_FORMAT(startdate, '%Y-%m-%d') , DATE_FORMAT(starttime, ' %T')), '%Y-%m-%d %T') and ". $myenddatetime ." <= STR_TO_DATE(CONCAT(DATE_FORMAT(enddate, '%Y-%m-%d') , DATE_FORMAT(endtime, ' %T')), '%Y-%m-%d %T'))"
		." or (STR_TO_DATE(CONCAT(DATE_FORMAT(startdate, '%Y-%m-%d') , DATE_FORMAT(starttime, ' %T')), '%Y-%m-%d %T') >= ". $mystartdatetime ." and STR_TO_DATE(CONCAT(DATE_FORMAT(startdate, '%Y-%m-%d') , DATE_FORMAT(starttime, ' %T')), '%Y-%m-%d %T') <= ". $myenddatetime .")"
		." or (STR_TO_DATE(CONCAT(DATE_FORMAT(enddate, '%Y-%m-%d') , DATE_FORMAT(endtime, ' %T')), '%Y-%m-%d %T') >= ". $mystartdatetime ." and STR_TO_DATE(CONCAT(DATE_FORMAT(enddate, '%Y-%m-%d') , DATE_FORMAT(endtime, ' %T')), '%Y-%m-%d %T') <= ". $myenddatetime ."))";
		//print $sql; exit();
		$database->setQuery( $sql );
		if ($database -> getErrorNum()) {
			$err = $err.$database -> stderr();
		}
		$overlapcount = $database->loadResult();
//		if ($overlapcount > $res_detail->max_dupes){
		if ($overlapcount >= $res_detail->max_seats && $res_detail->max_seats > 0 ){
			$err = $err.JText::_('RS1_INPUT_SCRN_CONFLICT_ERR');
		}

		// make sure no overlap with book-offs
		$sql = "select count(*) from #__sv_apptpro2_bookoffs "
		." where (resource_id = '". $resource ."')"
		." and published = 1 and full_day = 'No' "
		." and ((". $mystartdatetime ." >= STR_TO_DATE(CONCAT(DATE_FORMAT(off_date, '%Y-%m-%d') , DATE_FORMAT(bookoff_starttime, ' %T')), '%Y-%m-%d %T') and ". $mystartdatetime ." <= STR_TO_DATE(CONCAT(DATE_FORMAT(off_date, '%Y-%m-%d') , DATE_FORMAT(bookoff_endtime, ' %T')), '%Y-%m-%d %T'))"
		." or (". $myenddatetime ." >= STR_TO_DATE(CONCAT(DATE_FORMAT(off_date, '%Y-%m-%d') , DATE_FORMAT(bookoff_starttime, ' %T')), '%Y-%m-%d %T') and ". $myenddatetime ." <= STR_TO_DATE(CONCAT(DATE_FORMAT(off_date, '%Y-%m-%d') , DATE_FORMAT(bookoff_endtime, ' %T')), '%Y-%m-%d %T'))"
		." or (STR_TO_DATE(CONCAT(DATE_FORMAT(off_date, '%Y-%m-%d') , DATE_FORMAT(bookoff_starttime, ' %T')), '%Y-%m-%d %T') >= ". $mystartdatetime ." and STR_TO_DATE(CONCAT(DATE_FORMAT(off_date, '%Y-%m-%d') , DATE_FORMAT(bookoff_starttime, ' %T')), '%Y-%m-%d %T') <= ". $myenddatetime .")"
		." or (STR_TO_DATE(CONCAT(DATE_FORMAT(off_date, '%Y-%m-%d') , DATE_FORMAT(bookoff_endtime, ' %T')), '%Y-%m-%d %T') >= ". $mystartdatetime ." and STR_TO_DATE(CONCAT(DATE_FORMAT(off_date, '%Y-%m-%d') , DATE_FORMAT(bookoff_endtime, ' %T')), '%Y-%m-%d %T') <= ". $myenddatetime ."))";
		//print $sql; exit();
		$database->setQuery( $sql );
		if ($database -> getErrorNum()) {
			$err = $err.$database -> stderr();
		}
		$overlapcount = $database->loadResult();
		if ($overlapcount >0){
			$err = $err.JText::_('RS1_INPUT_SCRN_BO_CONFLICT_ERR');
		}

		// check for limiting
		if ($apptpro_config->limit_bookings != "0"){
			if($apptpro_config->limit_bookings_days == "1"){
				$sql = "select count(*) from #__sv_apptpro2_requests ".
				" where user_id = '". $user_id ."' ".
				" AND startdate = '".$startdate."' ".
				" AND (request_status = 'accepted' or request_status = 'pending' )";
				$database->setQuery( $sql );
				if ($database -> getErrorNum()) {
					$err = $err.$database -> stderr();
				}
				$otherbookingscount = $database->loadResult();
				if ($otherbookingscount >= $apptpro_config->limit_bookings){
					$err = $err.JText::_('RS1_INPUT_SCRN_MAX_BOOKINGS_ERR');
				}
			} else {
				// if booking is inside x days window, count others
				
				if(strtotime($startdate) <= strtotime("+$apptpro_config->limit_bookings_days day")){
					// count bookings between now and $apptpro_config->limit_bookings_days
					$sql = "select count(*) from #__sv_apptpro2_requests ".
					" where user_id = '". $user_id ."' ".
					" AND startdate >= CURDATE() AND startdate <= DATE_ADD(CURDATE(),INTERVAL $apptpro_config->limit_bookings_days DAY) ".
					" AND (request_status = 'accepted' or request_status = 'pending' )";
					$database->setQuery( $sql );
					if ($database -> getErrorNum()) {
						$err = $err.$database -> stderr();
					}
					$otherbookingscount = $database->loadResult();
					if ($otherbookingscount >= $apptpro_config->limit_bookings){
						$err = $err.JText::_('RS1_INPUT_SCRN_MAX_BOOKINGS_ERR');
					}
				}
			}
		}

		// Check same person has not already booked this slot
		// Un-comment the lines below if you are running with max seats > 0 (ie multiple seats be booking)
		// AND wish to STOP a person from booking the same slot more than once.
		// See also http://www.appointmentbookingpro.com/index.php?option=com_kunena&Itemid=66&func=view&catid=3&id=4996#5012
//		$sql = "select count(*) from #__sv_apptpro2_requests ".
//			" WHERE startdate = '".$startdate."' ".
//			" AND starttime = '".$starttime."' ".
//			" AND endtime = '".$endtime."' ".
//			" AND (request_status = 'accepted' or request_status = 'pending' )";
//			" AND (".
//			" user_id = '". $user_id ."' ".
//			" OR email = '".$email."' ".
//			" OR name = '".$name."' ".
//			" )";
//		$database->setQuery( $sql );
//		if ($database -> getErrorNum()) {
//			$err = $err.$database -> stderr();
//		}
//		$sameslotcount = $database->loadResult();
//		if ($sameslotcount > 0){
//			$err = $err.JText::_('You have already booked this time slot');
//		}
		
	}
	

	if($err == JText::_('RS1_INPUT_SCRN_VALIDATION_FAILED')){
//		require_once('recaptchalib.php');
//		$privatekey = "6LewBwsAAAAAAJ8bwQvTJY4FmtgWoWSTzdpGj9wS";
//		$resp = recaptcha_check_answer ($privatekey,
//										$_SERVER["REMOTE_ADDR"],
//										JRequest::getVar('recap_chal'),
//										JRequest::getVar('recap_resp'));
//		
//		if (!$resp->is_valid) {
//			$err = $err.JText::_('RS1_INPUT_CAPTCHA_ERR');//"The reCAPTCHA wasn't entered correctly. Please re-enter.";
//		} else {
			$err = JText::_('RS1_INPUT_SCRN_VALIDATION_OK');
//		}
	}

	echo $err;
	exit;	
	

?>