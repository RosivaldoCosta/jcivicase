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
 
class requestsController extends JController
{

	/**
	 * Custom Constructor
	 */
	function __construct( $default = array())
	{
		parent::__construct( $default );

		// Register Extra tasks
		$this->registerTask( 'export', 'export_to_csv' );
		$this->registerTask( 'export_ics', 'export_to_ics' );
		$this->registerTask( 'reminders', 'send_reminders' );
		$this->registerTask( 'reminders_sms', 'send_sms_reminders' );
		
	}
	/**
	 * Cancel operation
	 * redirect the application to the begining - index.php  	 
	 */
	function cancel()
	{
		$this->setRedirect( 'index.php' );
	}

	/**
	 * Method display
	 * 
	 * 1) create a classVIEWclass(VIEW) and a classMODELclass(Model)
	 * 2) pass MODEL into VIEW
	 * 3)	load template and render it  	  	 	 
	 */

	function display() {
		parent::display();
		
		require_once JPATH_COMPONENT . DS . 'helpers' . DS . 'rsappt_pro2.php';
		rsappt_pro2Helper::addSubmenu('requests');
		
	}
	
	
	function copy_request(){

		$cid	= JRequest::getVar( 'cid', array(0), 'post', 'array' );
		
		JRequest::setVar( 'view', 'request_copy' );
		JRequest::setVar( 'hidemainmenu', 1);
//		JRequest::setVar( 'request_tocopy', implode(',', $cid));

		parent::display();

	}

	function export_to_csv(){

		$uid = JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$sql = ' SELECT '.
				'#__sv_apptpro2_requests.*, #__sv_apptpro2_resources.name AS '.
				'ResourceName, #__sv_apptpro2_services.name AS ServiceName '.
				'FROM ('.
				'#__sv_apptpro2_requests LEFT JOIN '.
				'#__sv_apptpro2_resources ON #__sv_apptpro2_requests.resource = '.
				'#__sv_apptpro2_resources.id_resources LEFT JOIN '.
				'#__sv_apptpro2_services ON #__sv_apptpro2_requests.service = '.
				'#__sv_apptpro2_services.id_services ) '.
				" WHERE #__sv_apptpro2_requests.id_requests IN (".implode(",", $uid).")";
	
		ob_end_clean();
			
		$file_name = 'export_sv_apptpro2_requests.csv';
			
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Accept-Ranges: bytes');
		header('Content-Disposition: attachment; filename='.basename($file_name).';');
		header('Content-Type: text/plain; '._ISO);
		header('Expires: ' . gmdate('D, d M Y H:i:s') . ' GMT');
		header('Pragma: no-cache');

		$database = &JFactory::getDBO();
		$database->setQuery($sql);
		$rows = $database -> loadObjectList();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
			return false;
		}
		
		// get udfs
		$sql2 = "SELECT * FROM #__sv_apptpro2_udfs WHERE published=1";
		$sql2 .= " ORDER BY ordering";
		$database->setQuery($sql2);
		$udf_rows = $database -> loadObjectList();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
			return false;
		}
		
		$csv_save = '';
		if (!empty($rows)) {
				$comma = ',';
				$CR = "\r";
				// Make csv rows for field name
				$i=0;
				$fields = $rows[0];
				$cnt_fields = count($fields);
				$csv_fields = '';
				foreach($fields as $name=>$val) {
						$i++;
						//if ($cnt_fields<=$i) $comma = '';
						$csv_fields .= $name.$comma;
				}
				// add coluimns for udfs
				foreach($udf_rows as $udf_row) {
					$csv_fields .= $udf_row->udf_label.$comma;
				}
				// Make csv rows for data
				$csv_values = '';
				foreach($rows as $row) {
						$i=0;
						$comma = ',';
						foreach($row as $name=>$val) {
								$i++;
								//if ($cnt_fields<=$i) $comma = '';
								$csv_values .= '"'.$val.'"'.$comma;
						}
						// add udf columns data
						// get udf values for this request
						
						//$sql2 = "SELECT * FROM #__sv_apptpro2_udfvalues ";
						//$sql2 .= " WHERE request_id = ".$row->id;
						
						$sql2 = "SELECT #__sv_apptpro2_udfvalues.* FROM ".
							" #__sv_apptpro2_udfs LEFT JOIN #__sv_apptpro2_udfvalues ".
							" ON #__sv_apptpro2_udfs.id_udfs = #__sv_apptpro2_udfvalues.udf_id ".
							" AND #__sv_apptpro2_udfvalues.request_id = ".$row->id_requests .
							" ORDER BY #__sv_apptpro2_udfs.ordering";
						$database->setQuery($sql2);
						$udf_value_rows = $database -> loadObjectList();
						if ($database -> getErrorNum()) {
							echo $database -> stderr();
							return false;
						}
						foreach($udf_value_rows as $udf_value_row) {
							$csv_values .= '"'.$udf_value_row->udf_value.'"'.$comma;
						}
									
						$csv_values .= $CR;
				}
				$csv_save = $csv_fields.$CR.$csv_values;
		}
		echo $csv_save;
		die();  // no need to send anything else
	}

	function export_to_ics(){
		$uid = JRequest::getVar( 'cid', array(0), 'post', 'array' );

		$body = buildICSfile($uid);
	
		ob_end_clean();
			
		$file_name = 'export_sv_apptpro2_requests.ics';
	
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Accept-Ranges: bytes');
		header('Content-Disposition: attachment; filename='.basename($file_name).';');
		header('Content-Type: text/x-vCalendar; '._ISO);
		header('Expires: ' . gmdate('D, d M Y H:i:s') . ' GMT');
		header('Pragma: no-cache');
			
		echo $body;
		die();  // no need to send anything else
		
	}

	function send_reminders($sms="No"){
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
		
		JRequest::setVar( 'view', 'requests_reminders' );
		JRequest::setVar( 'hidemainmenu', 1);
		JRequest::setVar( 'results', $status);

		parent::display();
		
	}

	function send_sms_reminders(){
		$this->send_reminders("Yes");
	}

	
}	
?>

