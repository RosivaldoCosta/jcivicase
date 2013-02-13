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

//DEVNOTE: import CONTROLLER object class
jimport( 'joomla.application.component.controller' );


/**
 * rsappt_pro2  Controller
 */

class frontdeskController extends JController
{

	/**
	 * Custom Constructor
	 */
	function __construct( $default = array())
	{
		parent::__construct( $default );

		// Register Extra tasks
		$this->registerTask( 'reminders', 'send_reminders' );
		$this->registerTask( 'reminders_sms', 'send_sms_reminders' );

		$this->registerTask( 'display_manifest', 'go_display_manifest' );

		$this->registerTask( 'add_booking', 'add_booking' );
		$this->registerTask( 'process_booking_request', 'process_booking_request' );
		$this->registerTask( 'show_confirmation', 'show_confirmation' );
		$this->registerTask( 'show_in_progress', 'show_in_progress' );
		$this->registerTask( 'pp_return', 'pp_return' );


		$this->registerTask( 'do_continue', 'do_continue' );
		$this->registerTask( 'do_book_another', 'do_book_another' );

	}

	function list_bookings()
	{
		JRequest::setVar( 'view', 'frontdesk' );
		JRequest::setVar( 'layout', 'default'  );
		JRequest::setVar( 'hidemainmenu', 0);


		parent::display();

	}

	function cancel()
	{
		$frompage = JRequest::getVar('frompage');
		$frompage_item = JRequest::getVar('frompage_item');

		$this->setRedirect( 'index.php?option=com_rsappt_pro2&view='.$frompage.'&Itemid='.$frompage_item );
	}


	function send_reminders($sms="No"){

		$frompage = JRequest::getVar('frompage');
		$frompage_item = JRequest::getVar('frompage_item');
		$fromtab = JRequest::getVar('fromtab');

		$cid	= JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$reminder_log_time_format = "%H:%M - %b %d";
		$database = &JFactory::getDBO();

		if (!is_array($cid) || count($cid) < 1) {
			echo "<script> alert('Select an item for reminder'); window.history.go(-1);</script>\n";
			exit();
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

		if (count($cid))
		{
			$ids = implode(',', $cid);
			// get request details
			$sql = "SELECT #__sv_apptpro2_requests.*, DATE_FORMAT(#__sv_apptpro2_requests.startdate, '%W %M %e, %Y') as display_startdate, ".
				"DATE_FORMAT(#__sv_apptpro2_requests.starttime, ' %l:%i %p') as display_starttime ,".
				"#__sv_apptpro2_resources.name AS resource_name ".
				"FROM (#__sv_apptpro2_requests INNER JOIN #__sv_apptpro2_resources ".
				" ON  #__sv_apptpro2_requests.resource = #__sv_apptpro2_resources.id_resources )".
				" WHERE #__sv_apptpro2_requests.id_requests IN ($ids)";
			$database->setQuery($sql);
			$requests = NULL;
			$requests = $database -> loadObjectList();
			if ($database -> getErrorNum()) {
				echo $database -> stderr();
				return false;
			}

			// need current local time based on server time adjusted by Joomla time zone setting
			$config =& JFactory::getConfig();
			$tzoffset = $config->getValue('config.offset');
			if($apptpro_config->daylight_savings_time == "Yes"){
				$tzoffset = $tzoffset+1;
			}
			$offsetdate = JFactory::getDate();
			$offsetdate->setOffset($tzoffset);

			$status = '';
			$language = JFactory::getLanguage();
			$language->load('com_rsappt_pro2', JPATH_SITE, null, true);
			$subject = JText::_('RS1_REMINDER_EMAIL_SUBJECT');

			$k = 0;
			for($i=0; $i < count( $requests ); $i++) {
				$request = $requests[$i];
				$err = "";
				if($request->email == ""){
					// no email address
					$err .= "No email address, ";
				} else if($request->request_status != "accepted"){
					// is not 'accepted'?
					$err .= "Request status not 'Accepted', ";
				} else if(strtotime($request->startdate." ".$request->starttime) < time()){
					// in the past
					$err .= "Request start date/time has passed, ";
				}
				if($request->user_id != ""){
					$user = $request->user_id;
				} else {
					$user="-1";
				}
				if($err != ""){
					$line = "Recipient: ". $request->email ." - ". $err." *** NO REMINDER SENT *** ";
					logReminder($line, $request->id_requests, $user, $request->name, $offsetdate->toFormat($reminder_log_time_format));
					$status .= $line."<br>";
				} else {
					if($sms=="No"){
						if(sendMail($request->email, $subject, "reminder", $request->id_requests)){
							$line = "Recipient: ". $request->email . ", ".stripslashes($request->name). ", ".stripslashes($request->resource_name).", ".$request->display_starttime. ", ".$request->display_startdate." - Ok";
							logReminder($line, $request->id_requests, $user, $request->name, $offsetdate->toFormat($reminder_log_time_format));
							$status .= $line."<br>";
						} else {
							$line = "Recipient: ". $request->email . ", ".stripslashes($request->name). ", ".stripslashes($request->resource_name).", ".$request->display_starttime. ", ".$request->display_startdate." - Failed";
							logReminder($line, $request->id_requests, $user, $request->name, $offsetdate->toFormat($reminder_log_time_format));
							$status .= $line."<br>";
						}
					} else {
						if($apptpro_config->enable_clickatell == "Yes"){
							if($apptpro_config->clickatell_what_to_send == "Reminders" || $apptpro_config->clickatell_what_to_send == "All"){
								$returnCode = "";
								if(sendSMS($request->id_requests, "reminder", $returnCode )){
									$line = "SMS to Recipient: ".stripslashes($request->name). " - Ok - Return Code: ".$returnCode;
									logReminder($line, $request->id_requests, $user, $request->name, $offsetdate->toFormat($reminder_log_time_format));
									$status .= $line."<br>";
								} else {
									$line = "SMS to Recipient: ".stripslashes($request->name). " - Failed - Return Code: ".$returnCode;
									logReminder($line, $request->id_requests, $user, $request->name, $offsetdate->toFormat($reminder_log_time_format));
									$status .= $line."<br>";
								}
							}
						} else {
							logReminder("SMS currently disabled, no SMS reminder sent", $request->id_requests, $user, $request->name, $offsetdate->toFormat($reminder_log_time_format));
							$status = "SMS currently disabled, no SMS reminder sent.";
						}
					}
				}
			}
		}

		JRequest::setVar( 'view', 'requests_reminders_fd' );
		JRequest::setVar( 'hidemainmenu', 1);
		JRequest::setVar( 'results', $status);
		JRequest::setVar( 'frompage', $frompage);
		JRequest::setVar( 'fromtab', $fromtab);
		JRequest::setVar( 'Itemid', JRequest::getVar( 'Itemid'));

		parent::display();

	}

	function send_sms_reminders(){
		$this->send_reminders("Yes");
	}

	function go_display_manifest()
	{
		$frompage = JRequest::getVar( 'frompage', '' );
		JRequest::setVar( 'view', 'display_manifest' );
		JRequest::setVar( 'layout', 'default'  );
		JRequest::setVar( 'hidemainmenu', 1);
		JRequest::setVar( 'listpage', $frompage);
		JRequest::setVar( 'Itemid', JRequest::getVar( 'Itemid'));

		parent::display();

	}

	function add_booking()
	{
		$frompage = JRequest::getVar( 'frompage', '' );
		JRequest::setVar( 'view', 'booking_screen_fd' );
		JRequest::setVar( 'layout', 'default'  );
		JRequest::setVar( 'hidemainmenu', 1);
		JRequest::setVar( 'listpage', $frompage);
		JRequest::setVar( 'Itemid', JRequest::getVar( 'Itemid'));

		parent::display();

	}

	function process_booking_request(){

		$frompage = JRequest::getVar('frompage');
		$frompage_item = JRequest::getVar('frompage_item');

		include_once( JPATH_SITE."/components/com_rsappt_pro2/functions2.php" );

	//=========================================================================
	//	require_once('recaptchalib.php');
	//	$privatekey = "...";
	//	$resp = recaptcha_check_answer ($privatekey,
	//									$_SERVER["REMOTE_ADDR"],
	//									$_POST["recaptcha_challenge_field"],
	//									$_POST["recaptcha_response_field"]);
	//
	//	if (!$resp->is_valid) {
	//	  die ("The reCAPTCHA wasn't entered correctly. Go back and try it again.");// .
	//	}
	//=========================================================================

		$paypal_submit = JRequest::getVar('ppsubmit', '0');

		$name = JRequest::getVar('name');
		$user_id = JRequest::getVar('user_id');
		$unit_number = JRequest::getVar('unitnumber');
		$phone = JRequest::getVar('phone');
		$email = JRequest::getVar('email');
		$sms_reminders = JRequest::getVar('sms_reminders');
		$sms_phone = JRequest::getVar('sms_phone');
		$sms_dial_code = JRequest::getVar('sms_dial_code');
		$resource = JRequest::getVar('resource');
		$service_name = JRequest::getVar('service_name');
		$startdate = JRequest::getVar('startdate');
		$starttime = JRequest::getVar('starttime');
		$enddate = JRequest::getVar('enddate');
		$endtime = JRequest::getVar('endtime');
		$comment = JRequest::getVar('comment');
		$copyme = JRequest::getVar('cbCopyMe');
		$str_udf_count = JRequest::getVar('udf_count', "0");
		$str_res_udf_count = JRequest::getVar('res_udf_count', "0");
		$int_udf_count = intval($str_udf_count) + intval($str_res_udf_count);

		$applied_credit = JRequest::getVar('applied_credit', 0.00);
		$grand_total = JRequest::getVar('grand_total', 0);
		$ammount_due = $grand_total;
		$coupon_code = JRequest::getVar('coupon_code','');
		$booked_seats = JRequest::getVar('booked_seats', 1);
		$seat_type_count = JRequest::getVar('seat_type_count', -1);
		$extras_count = JRequest::getVar('extras_count', -1);
		$admin_comment = JRequest::getVar('admin_comment', '');

		if($resource == ""){
			$resource = $_POST['selected_resource_id'];
		}

//		if($resource == "" or $resource < 1){
//			// should never happen
//			die("Fatal Error: No Resource, please use the back button and enter your request again.");
//		}

		// get config info
		$database = &JFactory::getDBO();
		$sql = 'SELECT * FROM #__sv_apptpro2_config';
		$database->setQuery($sql);
		$apptpro_config = NULL;
		$apptpro_config = $database -> loadObject();
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
			return false;
		}

		if ($err == ""){

		/* -----------------------------------------------------------------------------------
//		/*		Save order to database
		/* -------------------------------------------------------------------------------------*/

		$request_status = JRequest::getVar('book_as_request_status');
		$payment_status = JRequest::getVar('book_as_payment_status');
		if($payment_status == "paid"){
			$ammount_due = 0.00;
		}

  		// save to db
 		$last_id = NULL;
		$cancel_code = md5(uniqid(rand(), true));
		$last_id = saveToDB($name, $user_id ,$phone, $email, $sms_reminders, $sms_phone, $sms_dial_code, $resource,
			$service_name, $startdate, $starttime, $enddate, $endtime, $request_status, $cancel_code, $grand_total,
			$ammount_due, $coupon_code, $booked_seats, $applied_credit, $comment, $admin_comment);

		if($last_id == -1){
			exit;
		}

		if($apptpro_config->which_calendar != 'None' and $apptpro_config->which_calendar != "Google"){
			// need to set request to resource's defaults
			$cat_id = NULL;
			$cal_id = NULL;
			getDefaultCalInfo($apptpro_config->which_calendar, $res_detail, $cat_id, $cal_id);
			if($cat_id != NULL){
				if($apptpro_config->which_calendar == "JCalPro2"){
					$sql = "UPDATE #__sv_apptpro2_requests SET calendar_category=".strval($cat_id).", ".
					"calendar_calendar = ".strval($cal_id)." WHERE id_requests = ".$last_id->last_id;
				} else {
					$sql = "UPDATE #__sv_apptpro2_requests SET calendar_category=".strval($cat_id)." WHERE id_requests = ".$last_id->last_id;
				}
				$database->setQuery($sql);
				$database->query();
			}
		}

		// add seat counts to seat_counts table if in use
		if($seat_type_count > 0){
			for($stci=0; $stci<$seat_type_count; $stci++){

				$seat_type_id = JRequest::getVar('seat_type_id_'.$stci,"?");
				$seat_type_qty = JRequest::getVar('seat_'.$stci, 0);
				if($seat_type_qty > 0){
					$sSql = sprintf("INSERT INTO #__sv_apptpro2_seat_counts (seat_type_id, request_id, seat_type_qty) VALUES(%d, %d, '%s')",
							$seat_type_id,
							$last_id->last_id,
							$database->getEscaped($seat_type_qty));
					//echo $sSql;

					$database->setQuery($sSql);
					if (!$database->query()) {
						$err = $database->getErrorMsg();
						echo $err;
						exit;
					}
				}
			}
		}

		// add extras to extras_data table if in use
		if($extras_count > 0){
			for($ei=0; $ei<$extras_count; $ei++){

				$extras_id = JRequest::getVar('extras_id_'.$ei,"?");
				$extras_qty = JRequest::getVar('extra_'.$ei, 0);
				if($extras_qty > 0){
					$sSql = sprintf("INSERT INTO #__sv_apptpro2_extras_data (extras_id, request_id, extras_qty) VALUES(%d, %d, '%s')",
							$extras_id,
							$last_id->last_id,
							$database->getEscaped($extras_qty));
					//echo $sSql;

					$database->setQuery($sSql);
					if (!$database->query()) {
						$err = $database->getErrorMsg();
						echo $err;
						exit;
					}
				}
			}
		}


		// add udf values to udf_values table
		//echo "str_udf_count=".$str_udf_count;
		//echo "int_udf_count=".$int_udf_count;
		if($int_udf_count > 0){
			for($i=0; $i<$int_udf_count; $i++){

				$udf_value = JRequest::getVar('user_field'.$i.'_value');
				$sSql = sprintf("INSERT INTO #__sv_apptpro2_udfvalues (udf_id, request_id, udf_value) VALUES(%d, %d, '%s')",
						$_POST['user_field'.$i.'_udf_id'],
						$last_id->last_id,
						$database->getEscaped($udf_value));
				//echo $sSql;

				$database->setQuery($sSql);
				if (!$database->query()) {
					$err = $database->getErrorMsg();
					echo $err;
					exit;
				}
			}
		}


		// if "accepted", add to calendar
		if($request_status == "accepted"){
			addToCalendar($last_id->last_id, $apptpro_config);
		}

		if($paypal_submit == "1" && floatval($grand_total) > 0){
			/* -----------------------------------------------------------------------------------
//			/*		go to PayPal
			/* -------------------------------------------------------------------------------------*/

			if($apptpro_config->enable_paypal == "Yes" || $apptpro_config->enable_paypal == "Opt"){
				GoToPayPal($last_id->last_id, $apptpro_config, $grand_total, "bookingscreengad", $frompage_item);
			}
			// for paypal, messages are sent from ipn

		} else {

			// dev only
			ini_set ( "SMTP", "shawmail.cg.shawcable.net" );

			// send form
			$mailer =& JFactory::getMailer();
			$mailer->setSender($apptpro_config->mailFROM);

			if($request_status == "accepted"){
				$message .= buildMessage(strval($last_id->last_id), "confirmation", "No");
			} else {
				$message .= buildMessage(strval($last_id->last_id), "in_progress", "No");
			}

			if($apptpro_config->html_email != "Yes"){
				$message = str_replace("<br>", "\r\n", $message);
			}

			$array = array($last_id->last_id);
			$ics = buildICSfile($array);

			// email to customer
			if(JRequest::getVar('email') != "" && JRequest::getVar('chk_email_confirmation') == "Yes"){
				$to = JRequest::getVar('email');

				if($apptpro_config->html_email == "Yes"){
					$mailer->IsHTML(true);
				}

				if($apptpro_config->attach_ics_customer == "Yes"){
					$mailer->AddStringAttachment($ics, "appointment_".strval($last_id->last_id).".ics");
				}

				$mailer->addRecipient($to);
				$mailer->setSubject(JText::_($apptpro_config->mailSubject));
				$mailer->setBody($message);
				if($mailer->send() != true){
					logIt("Error sending email: ".$mailer->ErrorInfo);
				}
				// reset for next
				$mailer = null;
				$mailer =& JFactory::getMailer();
				$mailer->setSender($apptpro_config->mailFROM);

			}

			// email to admin
				$to = $apptpro_config->mailTO;

				if($apptpro_config->html_email == "Yes"){
					$mailer->IsHTML(true);
				}

				if($apptpro_config->attach_ics_admin == "Yes"){
					$mailer->AddStringAttachment($ics, "appointment_".strval($last_id->last_id).".ics");
				}

				$mailer->addRecipient(explode(",", $to));
				$mailer->setSubject(JText::_($apptpro_config->mailSubject));
				$mailer->setBody($message);
				if($mailer->send() != true){
					logIt("Error sending email: ".$mailer->ErrorInfo);
				}

				// reset for next
				$mailer = null;
				$mailer =& JFactory::getMailer();
				$mailer->setSender($apptpro_config->mailFROM);

			// email to resource
			if($res_detail->resource_email != ""){
				$to = $res_detail->resource_email;

				if($apptpro_config->html_email == "Yes"){
					$mailer->IsHTML(true);
				}

				if($apptpro_config->attach_ics_resource == "Yes"){
					$mailer->AddStringAttachment($ics, "appointment_".strval($last_id->last_id).".ics");
				}

				$mailer->addRecipient(explode(",", $to));
				$mailer->setSubject(JText::_($apptpro_config->mailSubject));
				$mailer->setBody($message);
				if($mailer->send() != true){
					logIt("Error sending email: ".$mailer->ErrorInfo);
				}
			}

			// SMS to resource
			if($apptpro_config->enable_clickatell == "Yes"){
				$config =& JFactory::getConfig();
				$tzoffset = $config->getValue('config.offset');
				$offsetdate = JFactory::getDate();
				$offsetdate->setOffset($tzoffset);
				$reminder_log_time_format = "%H:%M - %b %d";
				$user =& JFactory::getUser();
				if(!$user->guest){
					$bookingUser = $user->id;
				} else {
					$bookingUser = -1;
				}
				$returnCode = "";

				if($request_status == "accepted"){
					sendSMS($last_id->last_id, "confirmation", $returnCode, $toResource="Yes");
				} else {
					sendSMS($last_id->last_id, "in_progress", $returnCode, $toResource="Yes");
				}
				logReminder("New booking: ".$returnCode, $last_id->last_id, $bookingUser, $name, $offsetdate->toFormat($reminder_log_time_format));
			}

			if($request_status == "accepted"){
				$next_view="show_confirmation";
			} else {
				$next_view="show_in_progress";
			}

			$this->setRedirect( 'index.php?option=com_rsappt_pro2&view=frontdesk&Itemid='.$frompage_item.'&task='.$next_view.'&req_id='.$last_id->last_id );
			}
		}


	}


	function show_confirmation()
	{
		$frompage = JRequest::getVar( 'frompage', '' );
		JRequest::setVar( 'view', 'fd_confirmation' );
		JRequest::setVar( 'frompage', $frompage);
		JRequest::setVar( 'Itemid', JRequest::getVar( 'Itemid'));
		JRequest::setVar( 'req_id', JRequest::getVar( 'req_id'));

		parent::display();
	}


	function pp_return(){
		echo "pp_return";
	}

	function do_continue(){
		$frompage_item = JRequest::getVar('frompage_item');
		$this->setRedirect( 'index.php?option=com_rsappt_pro2&view=frontdesk&Itemid='.$frompage_item );
	}

	function do_book_another(){
		$frompage_item = JRequest::getVar('frompage_item');
		$this->setRedirect( 'index.php?option=com_rsappt_pro2&view=frontdesk&task=add_booking&Itemid='.$frompage_item );
	}

}
?>

