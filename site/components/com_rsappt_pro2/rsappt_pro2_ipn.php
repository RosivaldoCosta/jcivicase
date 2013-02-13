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
	include_once( JPATH_SITE."/administrator/components/com_rsappt_pro2/sendmail_pro2.php" );
	include_once( JPATH_SITE."/components/com_rsappt_pro2/functions2.php" );

	// dev only
	//ini_set ( "SMTP", "shawmail.cg.shawcable.net" ); 

echo 'ok';

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

	$mailer =& JFactory::getMailer();
	$mailer->setSender($apptpro_config->mailFROM);
	if($apptpro_config->html_email == "Yes"){
		$mailer->IsHTML(true);
	}

	// read the post from PayPal system and add 'cmd'
	$req = 'cmd=_notify-validate';
	foreach ($_POST as $key => $value) {
	$value = urlencode(stripslashes($value));
	$req .= "&$key=$value";
	}
	// post back to PayPal system to validate
	$header = "POST /cgi-bin/webscr HTTP/1.0\r\n";
	$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
	$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";

    $header = "POST /cgi-bin/webscr HTTP/1.0\r\n";
    $header.= "User-Agent: PHP/".phpversion()."\r\n";
    $header.= "Referer: ".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].@$_SERVER['QUERY_STRING']."\r\n";
    $header.= "Server: ".$_SERVER['SERVER_SOFTWARE']."\r\n";
	if($apptpro_config->paypal_use_sandbox == "Yes"){
		$header.= "Host: www.sandbox.paypal.com:80\r\n";
	} else {
		$header.= "Host: www.paypal.com:80\r\n";
	}
    $header.= "Content-Type: application/x-www-form-urlencoded\r\n";
    $header.= "Content-Length: ".strlen($req)."\r\n";
    $header.= "Accept: */*\r\n\r\n";
	
	if($apptpro_config->paypal_use_sandbox == "Yes"){
		$fp = fsockopen ("www.sandbox.paypal.com", 80, $errno, $errstr, 30);
	} else {
		$fp = fsockopen ("www.paypal.com", 80, $errno, $errstr, 30);
	}
	
	// assign posted variables to local variables
	$item_name = $_POST['item_name'];
	$business = $_POST['business'];
	$item_number = $_POST['item_number'];
	$payment_status = $_POST['payment_status'];
	$mc_gross = $_POST['mc_gross'];
	$payment_currency = $_POST['mc_currency'];
	$txn_id = $_POST['txn_id'];
	$receiver_email = $_POST['receiver_email'];
	$receiver_id = $_POST['receiver_id'];
	$quantity = $_POST['quantity'];
	$num_cart_items = $_POST['num_cart_items'];
	$payment_date = $_POST['payment_date'];
	$first_name = $_POST['first_name'];
	$last_name = $_POST['last_name'];
	$payment_type = $_POST['payment_type'];
	$payment_status = $_POST['payment_status'];
	$payment_gross = $_POST['payment_gross'];
	$payment_fee = $_POST['payment_fee'];
	$settle_amount = $_POST['settle_amount'];
	$memo = $_POST['memo'];
	$payer_email = $_POST['payer_email'];
	$txn_type = $_POST['txn_type'];
	$payer_status = $_POST['payer_status'];
	$address_street = $_POST['address_street'];
	$address_city = $_POST['address_city'];
	$address_state = $_POST['address_state'];
	$address_zip = $_POST['address_zip'];
	$address_country = $_POST['address_country'];
	$address_status = $_POST['address_status'];
	$item_number = $_POST['item_number'];
	$tax = $_POST['tax'];
	$option_name1 = $_POST['option_name1'];
	$option_selection1 = $_POST['option_selection1'];
	$option_name2 = $_POST['option_name2'];
	$option_selection2 = $_POST['option_selection2'];
	$for_auction = $_POST['for_auction'];
	$invoice = $_POST['invoice'];
	$custom = $_POST['custom'];
	$notify_version = $_POST['notify_version'];
	$verify_sign = $_POST['verify_sign'];
	$payer_business_name = $_POST['payer_business_name'];
	$payer_id =$_POST['payer_id'];
	$mc_currency = $_POST['mc_currency'];
	$mc_fee = $_POST['mc_fee'];
	$exchange_rate = $_POST['exchange_rate'];
	$settle_currency  = $_POST['settle_currency'];
	$parent_txn_id  = $_POST['parent_txn_id'];
	$pending_reason = $_POST['pending_reason'];
	$reason_code = $_POST['reason_code'];


	if (!$fp) {
		// HTTP ERROR
	} else {
		fwrite ($fp, $header . $req);
			while (!feof($fp)) {
				$res = fgets ($fp, 1024);
				if (strcmp ($res, "VERIFIED") == 0) {
							
					$database = &JFactory::getDBO();
					
					$fecha = date("m")."/".date("d")."/".date("Y");
					$fecha = date("Y").date("m").date("d");
					
					//check if transaction ID has been processed before
					$sql = "select count(*) as txnCount from #__sv_apptpro2_paypal_transactions where txnid='".$txn_id."'";
					$rows = NULL;
					$database->setQuery($sql);
					$rows = $database -> loadObject();
					if ($database -> getErrorNum()) {
						// write to log file here
						$errsql = "insert into #__sv_apptpro2_errorlog (description) values('DB error: ".$database -> stderr()."')";
						$database->setQuery($errsql);
						$database->query();
						exit;
					}	

					if ($rows->txnCount == 0){
						// no dupe carry on..

						// get request info
						$database = &JFactory::getDBO();
						//$sql = 'SELECT * FROM #__sv_apptpro2_requests WHERE id_requests = '.$custom;
						$sql = 'SELECT #__sv_apptpro2_requests.*, #__sv_apptpro2_resources.resource_email'. 
							" FROM #__sv_apptpro2_requests LEFT JOIN #__sv_apptpro2_resources ON ".
							" #__sv_apptpro2_requests.resource =	#__sv_apptpro2_resources.id_resources ".
							" WHERE #__sv_apptpro2_requests.id_requests=".$custom;						
						$database->setQuery($sql);
						$res_request = NULL;
						$res_request = $database -> loadObject();
						if ($database -> getErrorNum()) {
							$err = $database->getErrorMsg();
							$errsql = "insert into #__sv_apptpro2_errorlog (description) values('DB error: ".$database -> stderr()."')";
							$database->setQuery($errsql);
							$database->query();
						}
											
						// goodie we got paid
						if($payment_status == "Completed"){
		
							// we need to set the appting to 'Accepted'
							$request_id = $custom; // passed to PayPal, now we get it back
							$sql = "select count(*) as requestCount from #__sv_apptpro2_requests where id_requests=".$request_id;
							$rows = NULL;
							$database->setQuery($sql);
							$rows = $database -> loadObject();
							if ($database -> getErrorNum()) {
								// write to log file here
								$errsql = "insert into #__sv_apptpro2_errorlog (description) values('DB error: ".$database -> stderr()."')";
								$database->setQuery($errsql);
								$database->query();
							}	

							if ($rows->requestCount == 0){
								// oh-oh no request by that number
								$errsql = "insert into #__sv_apptpro2_errorlog (description) values('No outstanding request number: ".$request_id."')";
								$database->setQuery($errsql);
								$database->query();
							} else {								
								// found request, update it
								
								// first check to see if status = timeout indcating IPN too slow and timeslot is no longer help for this customer
								$sql = "select request_status from #__sv_apptpro2_requests where id_requests=".$request_id;
								$database->setQuery($sql);
								$status = $database -> loadResult();
								if($status == "timeout"){
									$mailer->addRecipient(explode(",",$apptpro_config->mailTO));
									$mailer->setSubject("IPN return on timed-out booking!");
									$mailer->setBody("Booking 'timeout' before IPN. This booking had been paid but NOT accepted in ABPro as the timeslot lock had been released by the timeout, requires admin action! Booking id:".$request_id);
									if($mailer->send() != true){
										logIt("Error sending email");
									}
									$mailer=null;
									$mailer =& JFactory::getMailer();
									$mailer->setSender($apptpro_config->mailFROM);
									if($apptpro_config->html_email == "Yes"){
										$mailer->IsHTML(true);
									}
									logIt("Booking timeout before IPN, booking paid but NOT ACCEPTED, requires admin action!",$request_id);
									return;
								}
								
								if($apptpro_config->accept_when_paid == "Yes"){
									$sql = "update #__sv_apptpro2_requests set payment_status='paid', booking_due=0, txnid='".$txn_id."', request_status='accepted' where id_requests=".$request_id;
								} else {
									$sql = "update #__sv_apptpro2_requests set payment_status = 'paid', booking_due=0, txnid='".$txn_id."' where id_requests=".$request_id;
								}						
								$database->setQuery($sql);
						
								if (!$database->query()) {
									$err = $database->getErrorMsg();
									$errsql = "insert into #__sv_apptpro2_errorlog (description) values('DB error: ".$database -> stderr()."')";
									$database->setQuery($errsql);
									$database->query();

									$message = "PAYPAL TRANSACTION ERROR: Error on request update for txnid=".$txn_id.",".$database -> stderr();

									$mailer->addRecipient(explode(",", $apptpro_config->mailTO));
									$mailer->setSubject("PAYPAL TRANSACTION ERROR");
									$mailer->setBody($message);
									if($mailer->send() != true){
										logIt("Error sending email");
									}
									$mailer=null;
									$mailer =& JFactory::getMailer();
									$mailer->setSender($apptpro_config->mailFROM);
									if($apptpro_config->html_email == "Yes"){
										$mailer->IsHTML(true);
									}
									if($mailer->send() != true){
										logIt("Error sending email");
									}
									
								}

								addToCalendar($request_id, $apptpro_config); // will only add if accepted
								
							}		
							
							$strQuery = "insert into #__sv_apptpro2_paypal_transactions(paymentstatus,buyer_email,firstname,lastname,street,city,".
								"state,zipcode,country,mc_gross,mc_fee,itemnumber,itemname,os0,on0,os1,on1,quantity,custom,memo,paymenttype,".
								"paymentdate,txnid,pendingreason,reasoncode,tax,datecreation) ".
								"values (".
								"'".$database->getEscaped($payment_status).
								"','".$database->getEscaped($payer_email).
								"','".$database->getEscaped($first_name).
								"','".$database->getEscaped($last_name).
								"','".$database->getEscaped($address_street).
								"','".$database->getEscaped($address_city).
								"','".$database->getEscaped($address_state).
								"','".$database->getEscaped($address_zip).
								"','".$database->getEscaped($address_country).
								"','".$database->getEscaped($mc_gross).
								"','".$database->getEscaped($mc_fee).
								"','".$database->getEscaped($item_number).
								"','".$database->getEscaped($item_name).
								"','".$database->getEscaped($option_name1).
								"','".$database->getEscaped($option_selection1).
								"','".$database->getEscaped($option_name2).
								"','".$database->getEscaped($option_selection2).
								"','".$database->getEscaped($quantity).
								"','".$database->getEscaped($custom).
								"','".$database->getEscaped($memo).
								"','".$database->getEscaped($payment_type).
								"','".$database->getEscaped($payment_date).
								"','".$database->getEscaped($txn_id).
								"','".$database->getEscaped($pending_reason).
								"','".$database->getEscaped($reason_code).
								"','".$database->getEscaped($tax).
								"','".$fecha."')";
							$database->setQuery($strQuery);
						
							if (!$database->query()) {
								$err = $database->getErrorMsg();
								$errsql = "insert into #__sv_apptpro2_errorlog (description) values('DB error: ".$database -> stderr()."')";
								$database->setQuery($errsql);
								$database->query();
								$message = "PAYPAL TRANSACTION ERROR: Error on insert into payment info table for txnid=".$txn_id.",".$database -> stderr();

								$mailer->addRecipient(explode(",", $apptpro_config->mailTO));
								$mailer->setSubject("PAYPAL TRANSACTION ERROR");
								$mailer->setBody($message);
								if($mailer->send() != true){
									logIt("Error sending email");
								}
								$mailer=null;
								$mailer =& JFactory::getMailer();
								$mailer->setSender($apptpro_config->mailFROM);
								if($apptpro_config->html_email == "Yes"){
									$mailer->IsHTML(true);
								}
								
							}
						
						
							// send confimration email to customer
							$message = buildMessage($custom, "confirmation", "Yes");
							$subject = JText::_('RS1_PAYPAL_CONFIRMATION_EMAIL_SUBJECT');

							if($res_request->email != ""){
								$mailer->addRecipient(explode(",", $res_request->email));
								$mailer->setSubject($subject);
								$mailer->setBody($message);
								if($mailer->send() != true){
									logIt("Error sending email");
								}
								$mailer=null;
								$mailer =& JFactory::getMailer();
								$mailer->setSender($apptpro_config->mailFROM);
								if($apptpro_config->html_email == "Yes"){
									$mailer->IsHTML(true);
								}
							}
							
							if($res_request->resource_email != ""){
								$mailer->addRecipient(explode(",", $res_request->resource_email));
								$mailer->setSubject($subject);
								$mailer->setBody($message);
								if($mailer->send() != true){
									logIt("Error sending email");
								}
								$mailer=null;
								$mailer =& JFactory::getMailer();
								$mailer->setSender($apptpro_config->mailFROM);
								if($apptpro_config->html_email == "Yes"){
									$mailer->IsHTML(true);
								}
							}
							
							if($apptpro_config->mailTO != ""){
								$jv_to = $apptpro_config->mailTO;
								$mailer->addRecipient(explode(",", $jv_to));
								$mailer->setSubject($subject);
								$mailer->setBody($message);
								if($mailer->send() != true){
									logIt("Error sending email");
								}
								$mailer=null;
								$mailer =& JFactory::getMailer();
								$mailer->setSender($apptpro_config->mailFROM);
								if($apptpro_config->html_email == "Yes"){
									$mailer->IsHTML(true);
								}
							}
							
							if($apptpro_config->enable_clickatell == "Yes"){
								// SMS to resource
								$config =& JFactory::getConfig();
								$tzoffset = $config->getValue('config.offset');      
								$offsetdate = JFactory::getDate();
								$offsetdate->setOffset($tzoffset);
								$reminder_log_time_format = "%H:%M - %b %d";
								$returnCode = "";
								sendSMS($res_request->id, "confirmation", $returnCode, $toResource="Yes");			
								logReminder("New booking (ipn): ".$returnCode, $res_request->id, 0, "", $offsetdate->toFormat($reminder_log_time_format));
							}

						} else {
							// payment_status not complete??
							$sql = "insert into #__sv_apptpro2_errorlog (description) values('Payment Status, not `completed`, payment_status=".$payment_status.", txnid=".$txn_id.", request=".$custom."')";
							$database->setQuery($sql);
							$database->query();
							
							// send an email
							$message = "Payment Status, not `Completed`, payment_status=".$payment_status.", txnid=".$txn_id.", request=".$custom;
							$mailer->addRecipient(explode(",", $apptpro_config->mailTO));
							$mailer->setSubject("PAYMENT STATUS NOT COMPLETE");
							$mailer->setBody($message);
							if($mailer->send() != true){
								logIt("Error sending email");
							}
							$mailer=null;
							$mailer =& JFactory::getMailer();
							$mailer->setSender($apptpro_config->mailFROM);
							if($apptpro_config->html_email == "Yes"){
								$mailer->IsHTML(true);
							}
							
						}
					} else {
						$sql = "insert into #__sv_apptpro2_errorlog (description) values('Duplicate transaction, txnid=".$txn_id.", request=".$custom."')";
						$database->setQuery($sql);
						$database->query();

						// send an email
						$message = "Duplicate transaction, txnid=".$txn_id.", request=".$custom;
						$mailer->addRecipient(explode(",", $apptpro_config->mailTO));
						$mailer->setSubject("VERIFIED DUPLICATED TRANSACTION");
						$mailer->setBody($message);
						if($mailer->send() != true){
							logIt("Error sending email");
						}
						$mailer=null;
						$mailer =& JFactory::getMailer();
						$mailer->setSender($apptpro_config->mailFROM);
						if($apptpro_config->html_email == "Yes"){
							$mailer->IsHTML(true);
						}
					}
					
				}
				
				// if the IPN POST was 'INVALID'...do this
				
				else if (strcmp ($res, "INVALID") == 0) {
					// log for manual investigation			

					$sql = "insert into #__sv_apptpro2_errorlog (description) values('INVALID IPN, txnid=".$txn_id.", request=".$custom."')";
					$database->setQuery($sql);
					$database->query();

					$message = "INVALID IPN, txnid=".$txn_id.", request=".$custom;
					$mailer->addRecipient(explode(",", $apptpro_config->mailTO));
					$mailer->setSubject("INVALID IPN");
					$mailer->setBody($message);
					if($mailer->send() != true){
						logIt("Error sending email");
					}
					$mailer=null;
					$mailer =& JFactory::getMailer();
					$mailer->setSender($apptpro_config->mailFROM);
					if($apptpro_config->html_email == "Yes"){
						$mailer->IsHTML(true);
					}
				}
		}
		fclose ($fp);
	}
	exit;
?>
