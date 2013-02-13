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
include_once( JPATH_SITE."/administrator/components/com_rsappt_pro2/functions_pro2.php" );


	header('Content-Type: text/xml');
	header("Cache-Control: no-cache, must-revalidate");
	//A date in the past
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");


	// recives the user's selected resource and date
	$cancellation_id = JRequest::getVar('cancellation_id');
	$browser = JRequest::getVar('browser');
	$userDateTime = JRequest::getVar('userDateTime');

	// is cancellation_id valid
	$database = &JFactory::getDBO();
	$sql = "SELECT * FROM #__sv_apptpro2_requests WHERE cancellation_id='".$cancellation_id."'";
	$database->setQuery($sql);
	$rows = $database->loadObjectList();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
			return false;
		}
	if (count($rows) == 0){
		echo JText::_('RS1_INPUT_SCRN_CANCEL_CODE_INVALID');
		exit;
	}
	if (count($rows) > 1){
		echo 'Error: More that one booking with that code. Cannot cancel!';
		exit;
	}
	if($rows[0]->request_status == "canceled"){
		echo JText::_('RS1_INPUT_SCRN_ALREADY_CANCELED');
		exit;
	}

	// get config info
	$sql = 'SELECT * FROM #__sv_apptpro2_config';
	$database->setQuery($sql);
	$apptpro_config = NULL;
	$apptpro_config = $database -> loadObject();
	if ($database -> getErrorNum()) {
		echo $database -> stderr();
		return false;
	}

	// is it too close to cancel?
	// compare user's time (not server time) to booking time
	// local date/time as yyyy-mm-dd hh:mm:ss
	$sql = "SELECT DATE_SUB(CONCAT(startdate, ' ', starttime), INTERVAL ".$apptpro_config->hours_before_cancel." HOUR) AS cancel_limit FROM #__sv_apptpro2_requests WHERE cancellation_id='".$cancellation_id."'";
	$database->setQuery($sql);
	$cancel_limit = $database->loadResult();
	if ($database -> getErrorNum()) {
		echo $database -> stderr();
		return false;
	}

	if(strtotime($cancel_limit) < strtotime($userDateTime)){
		// too late
		echo JText::_($apptpro_config->booking_too_close_to_cancel);
		exit;
	}

	// First delete calendar record for this request if one exists
	if($apptpro_config->which_calendar == "JEvents"){
		$sql = "DELETE FROM `#__events` WHERE INSTR(extra_info, '[req id:". $rows[0]->id_requests ."]')>0";
	} else if($apptpro_config->which_calendar == "JCalPro"){
		$sql = "DELETE FROM `#__jcalpro_events` WHERE INSTR(description, '[req id:". $rows[0]->id_requests ."]')>0";
	} else if($apptpro_config->which_calendar == "EventList"){
		$sql = "DELETE FROM `#__eventlist_events` WHERE INSTR(datdescription, '[req id:". $rows[0]->id_requests ."]')>0";
	} else if($apptpro_config->which_calendar == "JCalPro2"){
		$sql = "DELETE FROM `#__jcalpro2_events` WHERE INSTR(description, '[req id:". $rows[0]->id_requests."]')>0";
	} else if($apptpro_config->which_calendar == "Google" and $rows[0]->google_event_id != ""){
		include_once( JPATH_SITE."/components/com_rsappt_pro2/svgcal.php" );
		$gcal = new SVGCal;
		// need resource info to get which Google calender login
		$database = &JFactory::getDBO();
		$res_data = NULL;
		$sql = "SELECT * FROM #__sv_apptpro2_resources WHERE id_resources=".$rows[0]->resource;
		//echo $sql;
		//exit;
		$database->setQuery($sql);
		$res_data = $database->loadObject();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
			logIt($database->getErrorMsg());
			return false;
		}
		// login
		$result = $gcal->login($res_data->google_user, $res_data->google_password);
		if( $result == "ok"){
			$client = $gcal->getClient();
			if($rows[0]->google_calendar_id == ""){
				$gcal->deleteEventById($gcal->getClient(), $rows[0]->google_event_id);
			} else {
				$result = $gcal->deleteEvent($gcal->getClient(), $rows[0]->google_event_id, $rows[0]->google_calendar_id);
				if($result != "ok"){
					echo $result;
					logIt($result);
				}
			}
		} else {
			echo $result;
			logIt($result);
		}
	}
	//echo $sql;
	//exit();
	$database->setQuery($sql);
	if(!$database->query()){
		if ($database -> getErrorNum()) {
			if($database -> getErrorNum() != 1146){
				// ignore 1146 - table not found if component not installed
				echo $database -> stderr();
				return false;
			}
		}
	}

	// zap it
	$sql = "UPDATE #__sv_apptpro2_requests SET request_status = 'canceled', ".
	"admin_comment = CONCAT( admin_comment, ' *** Canceled by user ***') ".
	"WHERE cancellation_id='".$cancellation_id."'";
	$database->setQuery($sql);
	if(!$database->query()){
		echo $database -> stderr();
		return false;
	}


	// tell admin
	$language = JFactory::getLanguage();
	$language->load('com_rsappt_pro2', JPATH_SITE, null, true);
	$subject = JText::_('RS1_CANCELLATION_EMAIL_SUBJECT');
	$headers = 'From:'.$apptpro_config->mailFROM. "\r\n";

	//to admin..
	$to = $apptpro_config->mailTO;

	// and resource admins..
	// first get resource_admins using cacellation_id
	$sql = "SELECT #__sv_apptpro2_resources.resource_admins, #__sv_apptpro2_resources.resource_email, #__sv_apptpro2_requests.* ".
		" FROM #__sv_apptpro2_requests INNER JOIN ".
		" #__sv_apptpro2_resources ".
		" ON #__sv_apptpro2_requests.resource = #__sv_apptpro2_resources.id_resources ".
		" WHERE #__sv_apptpro2_requests.cancellation_id = '".$cancellation_id."'";
	$database->setQuery($sql);
	$req_detail = $database->loadObject();
	if ($database -> getErrorNum()) {
		echo $database -> stderr();
		return false;
	}


	// adjust credit
	$user =& JFactory::getUser();
	if($apptpro_config->allow_user_credit_refunds == "Yes" && $req_detail->credit_used > 0){
		$refund_amount =$req_detail->credit_used;
		if($req_detail->booking_total > 0 && $req_detail->payment_status == 'paid'){
			// part of booking was paid by paypal, need to add that back to user's credit total
			$refund_amount += $req_detail->booking_total;
		}
		$sql = "UPDATE #__sv_apptpro2_user_credit SET balance = balance + ".$refund_amount." WHERE user_id = ".$req_detail->user_id;
		$database->setQuery($sql);
		$database->query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
			logIt($database->getErrorMsg());
		}
		// set request.credit_used to -1 to indicate refunded and prevent multiple refunds if operator sets to canceled again.
		$sql = "UPDATE #__sv_apptpro2_requests SET credit_used = -1 WHERE id_requests = ".$req_detail->id_requests;
		$database->setQuery($sql);
		$database->query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
			logIt($database->getErrorMsg());
		}

		// add credit audit
		$sql = 'INSERT INTO #__sv_apptpro2_user_credit_activity (user_id, request_id, increase, comment, operator_id, balance) '.
		"VALUES (".$req_detail->user_id.",".
		$req_detail->id_requests.",".
		$refund_amount.",".
		"'".JText::_('RS1_ADMIN_CREDIT_ACTIVITY_REFUND_ON_CANCEL')."',".
		$user->id.",".
		"(SELECT balance from #__sv_apptpro2_user_credit WHERE user_id = ".$req_detail->user_id."))";
		$database->setQuery($sql);
		$database->query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
			logIt($database->getErrorMsg());
		}
	}



//	if($apptpro_config->activity_logging != "Off"){
//		LogActivity($req_detail->id_requests, "Booking cancelled by user".$ids);
//	}

	if(strlen(trim($req_detail->resource_email)) > 0 ){
		// to the resource's emailto
		$admin_ary = explode(",",$req_detail->resource_email);
		// addem
		foreach($admin_ary as $array_row){
			$headers .= 'bcc:'. $array_row."\r\n";
		}
	}

	if (strlen($req_detail->resource_admins) > 0 ){
		$admins = str_replace("||", ",", $req_detail->resource_admins);
		$admins = str_replace("|", "", $admins);
		//echo $admins;
		//exit;
		$sql = "SELECT email FROM #__users WHERE ".
			"id IN (".$admins.")";
		$database->setQuery($sql);
		$admin_rows = $database -> loadObjectList();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
			return false;
		}
		// addem
// notification are not sent to res admin anywhere else, inconsiatnt to do it now
//		foreach($admin_rows as $admin_row){
//			$headers .= 'bcc:'. $admin_row->email."\r\n";
//		}
	}


	// dev only
	//ini_set ( "SMTP", "shawmail.cg.shawcable.net" );
	//mail($to, $subject, "Cancelled by user, booking id:".$req_detail->id_requests,$headers);
	sendMail($to, $subject, "cancellation", $req_detail->id_requests, $headers);

	// SMS to resource
	$config =& JFactory::getConfig();
	$tzoffset = $config->getValue('config.offset');
	$offsetdate = JFactory::getDate();
	$offsetdate->setOffset($tzoffset);
	$reminder_log_time_format = "%H:%M - %b %d";
	$returnCode = "";
	sendSMS($req_detail->id_requests, "cancellation", $returnCode, $toResource="Yes");
	logReminder("User Cancellation of booking: ".$returnCode, $req_detail->id_requests, "'by user'", "", $offsetdate->toFormat($reminder_log_time_format));

	$message = buildMessage($req_detail->id_requests, "cancellation", "No");

	echo strip_tags($message);
	exit;


?>