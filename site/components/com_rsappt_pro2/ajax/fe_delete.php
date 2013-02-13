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


	header('Content-Type: text/xml'); 
	header("Cache-Control: no-cache, must-revalidate");
	//A date in the past
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	
	
	// recives the user's selected resource and date
	$cancellation_id = JRequest::getVar('cancellation_id');
	$browser = JRequest::getVar('browser');
	
	// is cancellation_id valid
	$database = &JFactory::getDBO(); 
	$sql = "SELECT id_requests FROM #__sv_apptpro2_requests WHERE cancellation_id='".$cancellation_id."'";
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
		echo 'Error: More that one booking with that code. Cannot delete!';
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

	// First delete calendar record for this request if one exists
	if($apptpro_config->which_calendar == "JEvents"){
		$sql = "DELETE FROM `#__events` WHERE INSTR(extra_info, '[req id:". $rows[0]->id_requests ."]')>0";
	} else if($apptpro_config->which_calendar == "JCalPro"){
		$sql = "DELETE FROM `#__jcalpro_events` WHERE INSTR(description, '[req id:". $rows[0]->id_requests ."]')>0";
	} else if($apptpro_config->which_calendar == "EventList"){
		$sql = "DELETE FROM `#__eventlist_events` WHERE INSTR(datdescription, '[req id:". $rows[0]->id_requests ."]')>0";
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
	$sql = "UPDATE #__sv_apptpro2_requests SET request_status = 'deleted', ".
	"admin_comment = CONCAT( admin_comment, ' *** Deleted by user ***') ". 
	"WHERE cancellation_id='".$cancellation_id."'";
	$database->setQuery($sql);
	if(!$database->query()){
		echo $database -> stderr();
		return false;
	}
	
	// tell admin
/*	$language = JFactory::getLanguage();
	$language->load('com_rsappt_pro14', JPATH_SITE, null, true);
	$subject = JText::_('RS1_CANCELLATION_EMAIL_SUBJECT');
	$headers = 'From:'.$apptpro_config->mailFROM. "\r\n";

	//to admin..
	$to = $apptpro_config->mailTO;

	// and resource admins..
	// first get resource_admins using cacellation_id
	$sql = "SELECT #__sv_apptpro2_resources.resource_admins, #__sv_apptpro2_resources.resource_email, #__sv_apptpro2_requests.* ".
		" FROM #__sv_apptpro2_requests INNER JOIN ".
		" #__sv_apptpro2_resources ".
		" ON #__sv_apptpro2_requests.resource = #__sv_apptpro2_resources.id ".
		" WHERE #__sv_apptpro2_requests.cancellation_id = '".$cancellation_id."'";
	$database->setQuery($sql);
	$req_detail = $database->loadObject();
	if ($database -> getErrorNum()) {
		echo $database -> stderr();
		return false;
	}
	if(strlen(trim($req_detail->resource_email)) > 0 ){
		// to the resource's emailto 
		$admin_ary = explode(",",$req_detail->resource_email);
		// addem
		foreach($admin_ary as $array_row){
			$headers .= 'cc:'. $array_row."\r\n";
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
		foreach($admin_rows as $admin_row){
			$headers .= 'cc:'. $admin_row->email."\r\n";
		}		
	}	
	 

	// dev only
	//ini_set ( "SMTP", "shawmail.cg.shawcable.net" ); 
	mail($to, $subject, "Cancelled by user, booking id:".$req_detail->id,$headers);			

*/
	echo JText::_('RS1_INPUT_SCRN_DELETE_MESSAGE');
	exit;	
	

?>