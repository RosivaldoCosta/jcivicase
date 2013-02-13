<?php
/*
 ****************************************************************
 Copyright (C) 2008-2009 Soft Ventures, Inc. All rights reserved.
 ****************************************************************
 * @package	Appointment Booking Pro - ABPro
 * @copyright	Copyright (C) 2008-2009 Soft Ventures, Inc. All rights reserved.
 * @license	GNU/GPL, see http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
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

function buildMessage($request_id, $msg_type, $paypal){
	// get config stuff
	$database = &JFactory::getDBO();
	$sql = 'SELECT * FROM #__sv_apptpro2_config';
	$database->setQuery($sql);
	$res_request_config = NULL;
	$res_request_config = $database -> loadObject();
	if ($database -> getErrorNum()) {
		logIt($database->getErrorMsg(), "", "", "");
		//return false;
	}

	if($request_id != ""){
		// get request details

		$lang =& JFactory::getLanguage();
		$langTag =  $lang->getTag();
		if($langTag == ""){
			$langTag = "en_GB";
		}
		$sql = "SET lc_time_names = '".str_replace("-", "_",$langTag)."';";
		$database->setQuery($sql);
		if (!$database->query()) {
			echo $database -> getErrorMsg();
		}

		if($res_request_config->timeFormat == '12'){
			$sql = 'SELECT *, DATE_FORMAT(startdate, "%W %M %e, %Y") as startdate, '.
			'DATE_FORMAT(starttime, "%l:%i %p") as starttime, '.
			'DATE_FORMAT(enddate, "%W %M %e, %Y") as enddate, '.
			'DATE_FORMAT(endtime, "%l:%i %p") as endtime FROM #__sv_apptpro2_requests WHERE id_requests = '.$request_id;
		} else {
			$sql = 'SELECT *, DATE_FORMAT(startdate, "%W %M %e, %Y") as startdate, '.
			'DATE_FORMAT(starttime, "%H:%i") as starttime, '.
			'DATE_FORMAT(enddate, "%W %M %e, %Y") as enddate, '.
			'DATE_FORMAT(endtime, "%H:%i") as endtime FROM #__sv_apptpro2_requests WHERE id_requests = '.$request_id;
		}
		$database->setQuery($sql);
		$request_details = NULL;
		$request_details = $database -> loadObject();
		if ($database -> getErrorNum()) {
			logIt($database->getErrorMsg(), "", "", "");
			//return false;
		}
		//print_r($request_details);	
		
		// get resource stuff
		$sql = 'SELECT * FROM #__sv_apptpro2_resources WHERE id_resources = '.$request_details->resource;
		$database->setQuery($sql);
		$resource_details = NULL;
		$resource_details = $database -> loadObject();
		if ($database -> getErrorNum()) {
			logIt($database->getErrorMsg(), "", "", "");
			//return false;
		}
		//print_r($resource_details);	

		// get resource service 
		if($request_details->service !=""){
			$sql = 'SELECT * FROM #__sv_apptpro2_services WHERE id_services = '.$request_details->service;
			$database->setQuery($sql);
			$resource_service = NULL;
			$resource_service = $database -> loadObject();
			if ($database -> getErrorNum()) {
				logIt($database->getErrorMsg(), "", "", "");
				//return false;
			}
			//print_r($resource_details);	
		}
		
		// get resource category
		if($resource_details->category_id != "" ){
			$sql = 'SELECT * FROM #__sv_apptpro2_categories WHERE id_categories = '.$resource_details->category_id;
			$database->setQuery($sql);
			$resource_category = NULL;
			$resource_category = $database -> loadObject();
			if ($database -> getErrorNum()) {
				logIt($database->getErrorMsg(), "", "", "");
				//return false;
			}
			//print_r($resource_details);	
		}
		
	} 


	if($msg_type == "confirmation" or $msg_type == "calendar_body" ){
		if($paypal == "Yes"){
			$msg_text = JText::_($res_request_config->booking_succeeded);		
		} else {
			if($msg_type == "calendar_body"){
				$msg_text = JText::_($res_request_config->calendar_body2);
			} else {
				$msg_text = JText::_($res_request_config->booking_succeeded);
			}
		}

	} else if($msg_type == "cancellation"){
		$msg_text = JText::_($res_request_config->booking_cancel);

	} else if($msg_type == "reminder"){
		$msg_text = JText::_($res_request_config->booking_reminder);
		
	} else if($msg_type == "sms_confirmation"){
		$msg_text = JText::_($res_request_config->booking_succeeded_sms);

	} else if($msg_type == "sms_reminder"){
		$msg_text = JText::_($res_request_config->booking_reminder_sms);

	} else if($msg_type == "sms_cancellation"){
		$msg_text = JText::_($res_request_config->booking_cancel_sms);

	} else if($msg_type == "sms_in_progress"){
		$msg_text = JText::_($res_request_config->booking_in_progress_sms);

	} else if($msg_type == "in_progress"){
		$msg_text = JText::_($res_request_config->booking_in_progress);

	}
	$msg_text = processTokens($request_id, $msg_text);

	return($msg_text);		

}

function processTokens($request_id, $msg_text){
	$database = &JFactory::getDBO();

	$sql = 'SELECT * FROM #__sv_apptpro2_tokens';
	$database->setQuery($sql);
	$tokens = NULL;
	$tokens = $database -> loadObjectList();
	if ($database -> getErrorNum()) {
		logIt($database->getErrorMsg(), "", "", "");
		return false;
	}
	//print_r($tokens);		

	if($request_id != ""){
		// get request details
		
		$lang =& JFactory::getLanguage();
		$langTag =  $lang->getTag();
		if($langTag == ""){
			$langTag = "en_GB";
		}
		$sql = "SET lc_time_names = '".str_replace("-", "_",$langTag)."';";
		$database->setQuery($sql);
		if (!$database->query()) {
			echo $database -> getErrorMsg();
		}
		
		if($res_request_config->timeFormat == '12'){
			$sql = 'SELECT *, DATE_FORMAT(startdate, "%W %M %e, %Y") as startdate, '.
				'DATE_FORMAT(starttime, "%l:%i %p") as starttime, '.
				'DATE_FORMAT(enddate, "%W %M %e, %Y") as enddate, '.
				'DATE_FORMAT(endtime, "%l:%i %p") as endtime FROM #__sv_apptpro2_requests WHERE id_requests = '.$request_id;
		} else {
			$sql = 'SELECT *, DATE_FORMAT(startdate, "%W %M %e, %Y") as startdate, '.
				'DATE_FORMAT(starttime, "%H:%i") as starttime, '.
				'DATE_FORMAT(enddate, "%W %M %e, %Y") as enddate, '.
				'DATE_FORMAT(endtime, "%H:%i") as endtime FROM #__sv_apptpro2_requests WHERE id_requests = '.$request_id;
		}
		$database->setQuery($sql);
		$request_details = NULL;
		$request_details = $database -> loadObject();
		if ($database -> getErrorNum()) {
			logIt($database->getErrorMsg(), "", "", "");
			//return false;
		}
		//print_r($request_details);	
		
		// get resource stuff
		$sql = 'SELECT * FROM #__sv_apptpro2_resources WHERE id_resources = '.$request_details->resource;
		$database->setQuery($sql);
		$resource_details = NULL;
		$resource_details = $database -> loadObject();
		if ($database -> getErrorNum()) {
			logIt($database->getErrorMsg(), "", "", "");
			//return false;
		}
		//print_r($resource_details);	
		
		// get resource service 
		if($request_details->service !=""){
			$sql = 'SELECT * FROM #__sv_apptpro2_services WHERE id_services = '.$request_details->service;
			$database->setQuery($sql);
			$resource_service = NULL;
			$resource_service = $database -> loadObject();
			if ($database -> getErrorNum()) {
				logIt($database->getErrorMsg(), "", "", "");
				//return false;
			}
			//print_r($resource_details);	
		}
		
		// get resource category
		if($resource_details->category_id != "" ){
			$sql = 'SELECT * FROM #__sv_apptpro2_categories WHERE id_categories = '.$resource_details->category_id;
			$database->setQuery($sql);
			$resource_category = NULL;
			$resource_category = $database -> loadObject();
			if ($database -> getErrorNum()) {
				logIt($database->getErrorMsg(), "", "", "");
				//return false;
			}
			//print_r($resource_details);	
		}
		
	} 

	$token = "";
	for($i=0; $i < count( $tokens ); $i++) {
		$token = $tokens[$i];
		if($token->db_table == "resources"){	
			$field = $token->db_field;
			$field = "return($"."resource_details->".$field.");";
			//echo $field;
			$msg_text = str_replace($token->token_text, JText::_(eval($field)), $msg_text);
		} else if($token->db_table == "config"){	
			$field = $token->db_field;
			$field = "return($"."res_request_config->".$field.");";
			//echo $field;
			$msg_text = str_replace($token->token_text, JText::_(eval($field)), $msg_text);
		} else if($token->db_table == "services"){	
			$field = $token->db_field;
			$field = "return($"."resource_service->".$field.");";
			//echo $field;
			$msg_text = str_replace($token->token_text, JText::_(eval($field)), $msg_text);
		} else if($token->db_table == "categories"){	
			$field = $token->db_field;
			$field = "return($"."resource_category->".$field.");";
			//echo $field;
			$msg_text = str_replace($token->token_text, JText::_(eval($field)), $msg_text);
		} else {
			$field = $token->db_field;
			$field = "return($"."request_details->".$field.");";
			$msg_text = str_replace($token->token_text, JText::_(eval($field)), $msg_text);
		}

	} 
	
	if($request_id != ""){
		// get udfs and values
		$sql = "SELECT ".
			"#__sv_apptpro2_udfs.udf_label, #__sv_apptpro2_udfvalues.udf_value, ".
			"#__sv_apptpro2_udfvalues.request_id ".
			"FROM ".
			"#__sv_apptpro2_udfs LEFT JOIN ".
			"#__sv_apptpro2_udfvalues ON #__sv_apptpro2_udfvalues.udf_id = ".
			"#__sv_apptpro2_udfs.id_udfs ".
			"AND ".
			"#__sv_apptpro2_udfvalues.request_id = ".$request_id;
	  
		$database->setQuery($sql);
		$udfs = NULL;
		$udfs = $database -> loadObjectList();
		if ($database -> getErrorNum()) {
			logIt($database->getErrorMsg(), "", "", "");
			return false;
		}
		if(count($udfs)>0){	
			foreach($udfs as $udf){
				$msg_text = str_replace("[".$udf->udf_label."]", JText::_($udf->udf_value), $msg_text);	
			}
	
		}
		
		// get seat_type and values
		$sql = "SELECT ".
			"seat_type_label, id_seat_types ".
			" FROM #__sv_apptpro2_seat_types WHERE published=1";

		$database->setQuery($sql);
		$seat_types = NULL;
		$seat_types = $database -> loadObjectList();
		if ($database -> getErrorNum()) {
			logIt($database->getErrorMsg(), "", "", "");
			return false;
		}

		if(count($seat_types)>0){	
			foreach($seat_types as $seat_type){
			
				$sql = "SELECT seat_type_qty FROM #__sv_apptpro2_seat_counts ".
					" WHERE ".
					"request_id = ".$request_id." AND ".
					"seat_type_id = ".$seat_type->id_seat_types;		
		
				$database->setQuery($sql);
				$seats = NULL;
				$seats = $database -> loadObject();
				if ($database -> getErrorNum()) {
					logIt($database->getErrorMsg(), "", "", "");
					return false;
				}
				if($seats->seat_type_qty == NULL){
					$seat_count = 0;
				} else {
					$seat_count = $seats->seat_type_qty;
				}
				$msg_text = str_replace("[".$seat_type->seat_type_label."]", $seat_count, $msg_text);	
			}
	
		}

		// get extras and values
		$sql = "SELECT ".
			"extras_label, id_extras ".
			" FROM #__sv_apptpro2_extras WHERE published=1";

		$database->setQuery($sql);
		$extras = NULL;
		$extras = $database -> loadObjectList();
		if ($database -> getErrorNum()) {
			logIt($database->getErrorMsg(), "", "", "");
			return false;
		}

		if(count($extras)>0){	
			foreach($extras as $extra){
			
				$sql = "SELECT extras_qty FROM #__sv_apptpro2_extras_data ".
					" WHERE ".
					"request_id = ".$request_id." AND ".
					"extras_id = ".$extra->id_extras;		
		
				$database->setQuery($sql);
				$extra_item = NULL;
				$extra_item = $database -> loadObject();
				if ($database -> getErrorNum()) {
					logIt($database->getErrorMsg(), "", "", "");
					return false;
				}
				if($extra_item->extras_qty == NULL){
					$extra_item_qty = 0;
				} else {
					$extra_item_qty = $extra_item->extras_qty;
				}
				$msg_text = str_replace("[".$extra->extras_label."]", $extra_item_qty, $msg_text);	
			}
		}	
	}	
	
	return $msg_text;
}

function sendMail($to, $subject, $type, $request_id, $cc="", $ics=""){

		// get config stuff
		$database = &JFactory::getDBO();
		$sql = 'SELECT * FROM #__sv_apptpro2_config';
		$database->setQuery($sql);
		$apptpro_config = NULL;
		$apptpro_config = $database -> loadObject();
		if ($database -> getErrorNum()) {
			logIt($database->getErrorMsg(), "", "", "");
			//return false;
		}

		$mailer =& JFactory::getMailer();
		
		$mailer->setSender($apptpro_config->mailFROM);
		
		if($cc != ""){
			$mailer->addBCC($cc);
		}
		
		if($apptpro_config->html_email == "Yes"){
			$mailer->IsHTML(true);
		}
		
		switch ($type) {
			case 'confirmation': 
				$message .= buildMessage($request_id, "confirmation", "No");			
			break;
			case 'reminder': 
				$message .= buildMessage($request_id, "reminder", "No");			
			break;
			case 'cancellation': 
				$message .= buildMessage($request_id, "cancellation", "No");			
			break;
		}
		
		$message = stripslashes($message);
			
		if($apptpro_config->html_email != "Yes"){
			$message = str_replace("<br>", "\r\n", $message);			
		}

		if($ics == "Yes"){
			$mailer->AddStringAttachment($ics, "appointment_".strval($request_id).".ics");
		}

		// dev only
		//ini_set ( "SMTP", "shawmail.cg.shawcable.net" ); 
		
		$mailer->addRecipient(explode(",", $to));
		$mailer->setSubject($subject);
		$mailer->setBody($message);
		if($mailer->send() != true){
			logIt("Error sending email: ".$mailer->ErrorInfo);
			return false;
		} else {
			return true;
		}

}

function sendSMS($request_id, $type, &$returnCode, $toResource="No"){

	// get config stuff
	$database = &JFactory::getDBO();
	$sql = 'SELECT * FROM #__sv_apptpro2_config';
	$database->setQuery($sql);
	$apptpro_config = NULL;
	$apptpro_config = $database -> loadObject();
	if ($database -> getErrorNum()) {
		logIt($database->getErrorMsg(), "", "", "");
		//return false;
	}

	if($apptpro_config->enable_clickatell == "No"){
		return false;
	}
	
	// get request deatils, c/w resource sms_phone
	$sql = "SELECT #__sv_apptpro2_requests.*, #__sv_apptpro2_resources.sms_phone as resource_sms_phone ". 
		" FROM #__sv_apptpro2_requests LEFT JOIN #__sv_apptpro2_resources ON ".
		" #__sv_apptpro2_requests.resource =	#__sv_apptpro2_resources.id_resources ".
		" WHERE #__sv_apptpro2_requests.id_requests = ".$request_id;

	$database->setQuery($sql);
	$request = NULL;
	$request = $database -> loadObject();
	if ($database -> getErrorNum()) {
		logIt($database->getErrorMsg(), "", "", "");
		return false;
	}
	
	$message = "";
	switch ($type) {
		case 'confirmation': 
			$message .= buildMessage($request_id, "sms_confirmation", "No");			
		break;
		case 'reminder': 
			$message .= buildMessage($request_id, "sms_reminder", "No");			
		break;
		case 'cancellation': 
			$message .= buildMessage($request_id, "sms_cancellation", "No");			
		break;
		case 'in_progress': 
			$message .= buildMessage($request_id, "sms_in_progress", "No");			
		break;
	}
	$message = stripslashes($message);
	if($apptpro_config->clickatell_enable_unicode == "No"){
		$message = str_replace(" ", "+", $message);	
	}
	$message = strip_tags($message);	
	if($message == ""){
		$message = "No message configured";
	}

	// build string for Clickatell
	if($apptpro_config->clickatell_user == ""){
		$returnCode = "Clickatell login information missing (user)";
		return false;
	}
	if($apptpro_config->clickatell_password == ""){
		$returnCode = "Clickatell login information missing (password)";
		return false;
	}
	if($apptpro_config->clickatell_api_id == ""){
		$returnCode = "Clickatell login information missing (api_id)";
		return false;
	}


	if($toResource == "No"){
		$sms_phone = $request->sms_phone;
		if($sms_phone == ""){
			$returnCode = "No SMS phone number supplied by user";
			return false;
		}
	} else {
		$sms_phone = $request->resource_sms_phone;
		if($sms_phone == ""){
			$returnCode = "No SMS phone number set for resource";
			return false;
		}
	}

	$sms_phone = str_replace("-", "", $sms_phone);	
	$sms_phone = str_replace("+", "", $sms_phone);	
	$sms_phone = str_replace(" ", "", $sms_phone);	
	if(strlen($sms_phone)>10){
		$sms_phone = substr($sms_phone, strlen($sms_phone)-10 );
	}
	
	// new - does not use fopen so it works with php Safe Mode = ON
	if($request->sms_dial_code == ""){
		$to=$apptpro_config->clickatell_dialing_code.$sms_phone;
	} else {
		$to=$request->sms_dial_code.$sms_phone;
	}
	$baseurl ="http://api.clickatell.com";
	
	// auth call
	$url =  $baseurl."/http/auth?user=".$apptpro_config->clickatell_user;
		$url .= "&password=".$apptpro_config->clickatell_password;
		$url .= "&api_id=".$apptpro_config->clickatell_api_id;	
	// do auth call
	//echo $url;
	//echo "<BR/>";
	$ret = file($url);
	// split our response. return string is on first line of the data returned
	$sess = split(":",$ret[0]);
	if ($sess[0] == "OK") {
		$sess_id = trim($sess[1]); // remove any whitespace
		 //echo $message;
		if($apptpro_config->clickatell_enable_unicode == "No"){
			$url = $baseurl."/http/sendmsg?session_id=".$sess_id."&to=".$to."&concat=3&text=".$message;
		} else {
			$url = $baseurl."/http/sendmsg?session_id=".$sess_id."&to=".$to."&unicode=1&concat=3&text=".utf16urlencode($message);
		}
		//echo $url;
		//echo "<BR/>";
		// do sendmsg call
		$ret = file($url);
		$send = split(":",$ret[0]);
		if ($send[0] == "ID"){
			$returnCode = $send[1];
			return true;
		} else {
			$returnCode = $send[1];
			return false;
			//echo "send message failed";
		}
	} else {
		$returnCode =  "Authentication failure: ". $ret[0];
		return false;
	}
	
}


function logReminder($desc, $request_id=-1, $user_id=-1, $name="", $local_time=""){
	$database = &JFactory::getDBO();
	$errsql = "insert into #__sv_apptpro2_reminderlog (request_id, user_id, name, description, local_time) ".
		" values(".
		$request_id.",".
		$user_id.",".
		"'".$database->getEscaped($name)."',".
		"'".$database->getEscaped($desc)."',".
		"'".$local_time."')";
	//echo $errsql;
	//exit;
	$database->setQuery($errsql);
	$database->query();
	if ($database -> getErrorNum()) {
		echo $database->getErrorMsg();
		logIt($database->getErrorMsg(), "", "", "");
		return false;
	}

}

function buildICSfile($cid, $update="No"){
	$retval = "";
	
	// get config stuff
	$database = &JFactory::getDBO();
	$sql = 'SELECT * FROM #__sv_apptpro2_config';
	$database->setQuery($sql);
	$apptpro_config = NULL;
	$apptpro_config = $database -> loadObject();
	if ($database -> getErrorNum()) {
		logIt($database->getErrorMsg(), "", "", "");
		//return false;
	}

	// need Joomla TZ for converting to UTC
	require_once( JPATH_SITE.DS.'configuration.php' );
	$CONFIG = new JConfig();
	$offset = $CONFIG->offset;
	if($apptpro_config->daylight_savings_time == "Yes"){
		$offset = $offset+1;
	}
	if($offset<0){
		$offset_sign = "+";
	} else {
		$offset_sign = "-";
	}	
	$offset = abs($offset);
	if($offset <10){
		$strOffset = $offset_sign."0".$offset.":00";
	} else {
		$strOffset = $offset_sign.strval($offset).":00";
	}

	$retval = "BEGIN:VCALENDAR\n\n";
	$retval .= "VERSION:2.0\n\n";
	$retval .= "PRODID:-//ABPro/CalendarAppointment\n\n";
	
	foreach ($cid as $one_id) {

		// get request details
		$params = JComponentHelper::getParams('com_languages');
		$sql = "SET lc_time_names = '".str_replace("-", "_", $params->get("site", 'en-GB'))."';";
		$database->setQuery($sql);
		if (!$database->query()) {
			echo $database -> getErrorMsg();
		}

		$sql = 'SELECT *, DATE_FORMAT(startdate, "%Y%m%d") as ics_startdate, '.
		'DATE_FORMAT(starttime, "T%H%i00") as ics_starttime, '.
		'DATE_FORMAT(enddate, "%Y%m%d") as ics_enddate, '.
		'DATE_FORMAT(endtime, "T%H%i00") as ics_endtime, '.
		'DATE_FORMAT(UTC_TIMESTAMP(), "%Y%m%dT%H%i00Z") as ics_newstamp, '.
		'DATE_FORMAT(CONVERT_TZ(created,"'.$strOffset.'","+00:00"),"%Y%m%dT%H%i00Z")as utc_created, '.
		'DATE_FORMAT(endtime, "%H:%i") as endtime FROM #__sv_apptpro2_requests WHERE id_requests = '.$one_id;

		$database->setQuery($sql);
		$request_details = NULL;
		$request_details = $database -> loadObject();
		if ($database -> getErrorNum()) {
			logIt($database->getErrorMsg(), "", "", "");
			//return false;
		}
		echo $sql;
		//print_r($request_details);	

		// get resource stuff
		$sql = 'SELECT * FROM #__sv_apptpro2_resources WHERE id_resources = '.$request_details->resource;
		$database->setQuery($sql);
		$resource_details = NULL;
		$resource_details = $database -> loadObject();
		if ($database -> getErrorNum()) {
			logIt($database->getErrorMsg(), "", "", "");
			//return false;
		}
		//print_r($resource_details);	

		switch ($apptpro_config->calendar_title) {
		  case 'resource.name': {
			$title_text = $resource_details->name;	
			break;
		  }
		  case 'request.name': {
			$title_text = $request_details->name;	
			break;
		  }
		  default: {
			// must be a udf, get udf_value
			$sql = "SELECT udf_value FROM #__sv_apptpro2_udfvalues WHERE request_id = ".$one_id." and udf_id=".$apptpro_config->calendar_title;
			$database->setQuery( $sql);
			$title_text = $database->loadResult(); 		
		  }
		}
		if($apptpro_config->calendar_body2 != "") {
			$body_text = buildMessage($one_id, "calendar_body", "No");
		}
		stripslashes($body_text);
		stripslashes($title_text);
		$body_text = str_replace("'", "`", $body_text);
		$title_text = str_replace("'", "`", $title_text);
		
		
		$retval .= "BEGIN:VEVENT\n\n";
		if($update == "Yes"){
			$retval .= "DTSTAMP:".$request_details->ics_newstamp."\n\n";
		} else {
			$retval .= "DTSTAMP:".$request_details->utc_created."\n\n";
		}
		$retval .= "UID:ABPRO-".$one_id."\n\n";
		$retval .= "DTSTART:".$request_details->ics_startdate.$request_details->ics_starttime."\n\n";
		$retval .= "DTEND:".$request_details->ics_enddate.$request_details->ics_endtime."\n\n";
		$retval .= "SUMMARY:".$title_text."\n\n";
		// ics is not HTML and iCal is real picky so we remove any HTML tags and change \r\n (from the editor) to \\n
		$retval .= "DESCRIPTION:".strip_tags(str_replace("\r\n", "\\n", $body_text))."\n\n";
	
		$retval .= "END:VEVENT\n\n";

	}	
	$retval .= "END:VCALENDAR";

	return $retval;
}

function utf16urlencode($str)
{
    $str = mb_convert_encoding($str, 'UTF-16', 'UTF-8');
    $out ='';
    for ($i = 0; $i < mb_strlen($str, 'UTF-16'); $i++)
    {
        $out .= bin2hex(mb_substr($str, $i, 1, 'UTF-16'));
    }
    return $out;
}
?>