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

//DEVNOTE: import MODEL object class
jimport('joomla.application.component.model');


class requests_detailModelrequests_detail extends JModel
{
		var $_id_requests = null;
		var $_data = null;
//		var $_data_MyBookings = null;
		var $_table_prefix = null;

	function __construct()
	{
		parent::__construct();
		
		//initialize class property
	  	$this->_table_prefix = '#__sv_apptpro2_';			

		$array = JRequest::getVar('cid',  0, '', 'array');
		
		$this->setId((int)$array[0]);

	}

	/**
	 * Method to set the requests identifier
	 *
	 * @access	public
	 * @param	int requests identifier
	 */
	function setId($id_requests)
	{
		// Set requests id and wipe data
		$this->_id_requests		= $id_requests;
		$this->_data	= null;
	}

	/**
	 * Method to get a requests
	 *
	 * @since 1.5
	 */
	function &getData()
	{
		// Load the requests data
		if ($this->_loadData())
		{
		//load the data nothing else	  
		}
		else  $this->_initData();
		//print_r($this->_data);	
		
   	return $this->_data;
	}
	
	/**
	 * Method to checkout/lock the requests
	 *
	 * @access	public
	 * @param	int	$uid	User ID of the user checking the article out
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function checkout($uid = null)
	{
		if ($this->_id_requests)
		{
			// Make sure we have a user id to checkout the article with
			if (is_null($uid)) {
				$user	=& JFactory::getUser();
				$uid	= $user->get('id');
			}
			// Lets get to it and checkout the thing...
			$requests = & $this->getTable();
			
			
			if(!$requests->checkout($uid, $this->_id_requests)) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}

			return true;
		}
		return false;
	}
	/**
	 * Method to checkin/unlock the requests
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function checkin()
	{
		if ($this->_id_requests)
		{
			$requests = & $this->getTable();
			if(! $requests->checkin($this->_id_requests)) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}
		return false;
	}	
	/**
	 * Tests if requests is checked out
	 *
	 * @access	public
	 * @param	int	A user id
	 * @return	boolean	True if checked out
	 * @since	1.5
	 */
	function isCheckedOut( $uid=0 )
	{
		if ($this->_loadData())
		{
			if ($uid) {
				return ($this->_data->checked_out && $this->_data->checked_out != $uid);
			} else {
				return $this->_data->checked_out;
			}
		}
	}	
		
	/**
	 * Method to load content requests data
	 *
	 * @access	private
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function _loadData()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_data))
		{
			$query = 'SELECT * FROM '.$this->_table_prefix.'requests WHERE id_requests = '. $this->_id_requests;
			$this->_db->setQuery($query);
			$this->_data = $this->_db->loadObject();
			//print_r($this->_data);
			return (boolean) $this->_data;
		}
		return true;
	}

	/**
	 * Method to initialise the requests data
	 *
	 * @access	private
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function _initData()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_data))
		{
			$detail = new stdClass();
			$detail->resource_id = null;
			$detail->user_id = null;
			$detail->name = null;
			$detail->phone = null;
			$detail->email = null;
			$detail->resource = null;
			$detail->starttime = null;
			$detail->startdate = null;
			$detail->enddate = null;
			$detail->endtime = null;
			$detail->comment = null;
			$detail->admin_comment = null;
			$detail->request_status = null;
			$detail->payment_status = null;
			$detail->show_on_calendar = null;
			$detail->calendar_category = null;
			$detail->calendar_calendar = null;
			$detail->calendar_comment = null;
			$detail->created = null;
			$detail->cancellation_id = null;
			$detail->service = null;
			$detail->txnid = null;
			$detail->sms_reminders = null;
			$detail->sms_phone = null;
			$detail->sms_dial_code = null;
			$detail->google_event_id = '';
			$detail->google_calendar_id = '';
			$detail->booking_total = 0.00;
  			$detail->booking_due = 0.00;
			$detail->coupon_code = null;
			$detail->booked_seats = 0;
			$detail->booking_language = 'eb-gb';
			$detail->credit_used = "0.00";
			$detail->checked_out = 0;
			$detail->checked_out_time = 0;
			$detail->ordering = 1;
			$detail->published = 0;
			$this->_data	= $detail;
			return (boolean) $this->_data;
		}
		return true;
	}
  	

	/**
	 * Method to store the requests text
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function store($data)
	{
		//DEVNOTE: Load table class from com_rsappt_pro2/tables/requests_detail.php	
		$row =& $this->getTable();

		// Bind the form fields to the requests table
		if (!$row->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// if new item, order last in appropriate group
		if (!$row->id_requests) {
			$where = 'id_requests = ' . $row->id_requests ;
			$row->ordering = $row->getNextOrder ( $where );
		}

		// get config info
		$sql = 'SELECT * FROM #__sv_apptpro2_config';
		$this->_db->setQuery($sql);
		$apptpro_config = NULL;
		$apptpro_config = $this->_db->loadObject();
		if ($this->_db->getErrorNum()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}	
	
		// get resource info for the selected resource
		$sql = 'SELECT * FROM #__sv_apptpro2_resources where id_resources = '.$row->resource;
		$this->_db->setQuery($sql);
		$res_detail = NULL;
		$res_detail = $this->_db->loadObject();
		if ($this->_db->getErrorNum()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
	
		// if request_status = 'accepted', check max seats not exceeded
		// first just see if this booking's seats > the resource's
	
		// max_seats = 0 = no limit
		if($res_detail->max_seats > 0 && $row->request_status == "accepted"){	
			if($row->booked_seats > $res_detail->max_seats){
				echo "<script> alert('".JText::_('RS1_ADMIN_SCRN_EXCEED_SEATS')."'); window.history.go(-1); </script>\n";
				exit();	
			}	
			// now check to see if there are other bookings and if so how many total seats are booked.
			$currentcount = getCurrentSeatCount($row->startdate, $row->starttime, $row->enddate, $row->resource, $row->id_requests);
		
			if ($currentcount + $row->booked_seats > $res_detail->max_seats){
				echo "<script> alert('".JText::_('RS1_ADMIN_SCRN_EXCEED_SEATS')."'); window.history.go(-1); </script>\n";
				exit();	
			}
		}	

		// Store the requests table to the database
		if (!$row->store()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// save seat counts if required
		$seat_type_count = JRequest::getVar( 'seat_type_count', '0' );
		if($seat_type_count > 0 ){
			// For each seat type there are two possibilities; 
			// 1. there was an entry and it needs to be updated
			// 2. there was no entry and we need a new one IF the qty is now >0
			// If the was en entry and it's qty is down to 0, do not delete it, just update 
			
			for($st =0; $st<$seat_type_count; $st++){
				$seat_type_id = JRequest::getVar( 'seat_type_id_'.$st );
				$seat_type_qty = JRequest::getVar( 'seat_'.$st );
				$request_id = $row->id_requests;
				$seat_type_org_qty = JRequest::getVar( 'seat_type_org_qty_'.$st );
				if($seat_type_org_qty != $seat_type_qty){				
					$sql = "UPDATE #__sv_apptpro2_seat_counts SET seat_type_qty=".$seat_type_qty." WHERE request_id=".$request_id." AND seat_type_id=".$seat_type_id;				
					$this->_db->setQuery($sql);
					$result = NULL;
					$result = $this->_db->query();
					if ($this->_db->getErrorNum()) {
						$this->setError($this->_db->getErrorMsg());
						return false;
					}
					if ($this->_db->getAffectedRows()==0 && $seat_type_qty>0) {
						$sql = "INSERT INTO #__sv_apptpro2_seat_counts (request_id, seat_type_id, seat_type_qty) values(".$request_id.",".$seat_type_id.",".$seat_type_qty.")";				
						$this->_db->setQuery($sql);
						$result = $this->_db->query();
						if ($this->_db->getErrorNum()) {
							$this->setError($this->_db->getErrorMsg());
							return false;
						}
					}			
				}
			}
		}		
	
		// save udf changes
		$udf_rows_count = JRequest::getVar( 'udf_rows_count', '0' );
		if($udf_rows_count > 0 ){
			for($udfr=0; $udfr < $udf_rows_count; $udfr++){
				$udf_value_id = JRequest::getVar( 'udf_id_'.$udfr);
				$udf_value = JRequest::getVar( 'udf_value_'.$udfr);
				$sql = "UPDATE #__sv_apptpro2_udfvalues SET udf_value='".$udf_value."' WHERE id=".$udf_value_id;				
				$this->_db->setQuery($sql);
				$result = NULL;
				$result = $this->_db->query();
				if ($this->_db->getErrorNum()) {
					$this->setError($this->_db->getErrorMsg());
					return false;
				}
			}
		}
	
		// calendar stuff
		if($apptpro_config->which_calendar != 'None'){
			
			// remove calendar entry
			// First delete calendar record for this request if one exists
/*			if($apptpro_config->which_calendar == "JEvents"){
				$sql = "DELETE FROM `#__events` WHERE INSTR(extra_info, '[req id:". $row->id_requests ."]')>0";
			} else if($apptpro_config->which_calendar == "JCalPro"){
				$sql = "DELETE FROM `#__jcalpro_events` WHERE INSTR(description, '[req id:". $row->id_requests ."]')>0";
			} else*/ if($apptpro_config->which_calendar == "JCalPro2"){
				$sql = "DELETE FROM `#__jcalpro2_events` WHERE INSTR(description, '[req id:". $row->id_requests ."]')>0";
			} else if($apptpro_config->which_calendar == "EventList"){
				$sql = "DELETE FROM `#__eventlist_events` WHERE INSTR(datdescription, '[req id:". $row->id_requests ."]')>0";
			} else if($apptpro_config->which_calendar == "Google" and $row->google_event_id != ""){
			
				include_once( JPATH_SITE."/components/com_rsappt_pro2/svgcal.php" );
	
				$gcal = new SVGCal;
				// login
				$result = $gcal->login($res_detail->google_user, $res_detail->google_password);
				if( $result == "ok"){
					$client = $gcal->getClient();	
						if($row->google_calendar_id == ""){
						$gcal->deleteEventById($gcal->getClient(), $row->google_event_id);
					} else {
						$result = $gcal->deleteEvent($gcal->getClient(), $row->google_event_id, $row->google_calendar_id);
						if($result != "ok"){
							echo $result;
							logIt($result, "on delete of Google Calendar event"); 
						}
					}		
				} else {
					echo $result;
					logIt($result, "on login for delete of Google Calendar event"); 
				}						
			}	
			//echo $sql;
			//exit();
			$this->_db->setQuery($sql);
			if(!$this->_db->query()){
				if ($this->_db->getErrorNum()) {
					if($this->_db->getErrorNum() != 1146){
						// ignore 1146 - table not found if component not installed
						$this->setError($this->_db->getErrorMsg());
						return false;
					}
				}
			}
			
			if ($_POST['show_on_calendar']=='Yes' and $_POST['request_status']=='accepted'){
				$this->_db->setQuery("SELECT description FROM #__sv_apptpro2_resources WHERE name = '".$_POST['resource']."'" );
				$rows = $this->_db->loadObjectList();
				if ($this->_db->getErrorNum()) {
					$this->setError($this->_db->getErrorMsg());
					return false;
				}
				$Title = $rows[0]->description; 
				
		
				// get resource name
				$res_data = NULL;
				$sql = "SELECT * FROM #__sv_apptpro2_resources WHERE id_resources=".$_POST[resource];
				//echo $sql;
				//exit;
				$this->_db->setQuery($sql);
				$res_data = $this->_db->loadObject();
				if ($this->_db->getErrorNum()) {
					$this->setError($this->_db->getErrorMsg());
					return false;
				}
		
				switch ($apptpro_config->calendar_title) {
				  case 'resource.name': {
					$title_text = stripslashes($res_data->name);	
					break;
				  }
				  case 'request.name': {
					$title_text = stripslashes($row->name);	
					break;
				  }
				  default: {
					// must be a udf, get udf_value
					$sql = "SELECT udf_value FROM #__sv_apptpro2_udfvalues WHERE request_id = ".$row->id_requests." and udf_id=".$apptpro_config->calendar_title;
					$this->_db->setQuery( $sql);
					$title_text = $this->_db->loadResult(); 		
				  }
				}
	
				$calendar_comment = "";
				if($apptpro_config->calendar_body2 != "") {
					$calendar_comment = $_POST['calendar_comment'].buildMessage($row->id_requests, "calendar_body", "No");
				}		
				stripslashes($calendar_comment);
				$calendar_comment = str_replace("'", "`", $calendar_comment);
				$user =& JFactory::getUser();
	
				if($apptpro_config->which_calendar == "EventList"){
					$sql = "INSERT INTO `#__eventlist_events` (`catsid`,`title`, `dates`, `enddates`,".
					"`times`,`endtimes`, `datdescription`, `published`) VALUES (".
					$_POST[calendar_category].",".
					"'".$this->_db->getEscaped($title_text)."',".
					"'".$_POST['startdate']. "',".
					"'".$_POST['enddate']."',".
					"'".$_POST['starttime']. "',".
					"'". $_POST['endtime']."',".
					"'".$calendar_comment."<BR />[req id:". $row->id_requests ."]', 1".
					")";
//				} else if($apptpro_config->which_calendar == "JEvents"){
//					$sql = "INSERT INTO `#__events` (`catid`,`title`,`content`,`useCatColor`,`state`,".
//					"`created_by`,`created_by_alias`,`publish_up`,`publish_down`, `extra_info`) VALUES (".
//					$_POST[calendar_category].",".
//					"'".$this->_db->getEscaped($title_text)."',".
//					"'".$res_detail->name."',".
//					"1, 1,".
//					"'".$user->id."',".
//					"'".$user->name."',".
//					"'".$_POST['startdate']. " ". $_POST['starttime']."',".
//					"'".$_POST['enddate']. " ". $_POST['endtime']."',".
//					"'".$_POST['calendar_comment']."<BR />[req id:". $row->id_requests ."]'".
//					")";
//				} else if($apptpro_config->which_calendar == "Thyme"){
//					// times stores GMT, need to adjust to local based on Joomla time zone setting
//					require_once( JPATH_SITE.DS.'configuration.php' );
//					$CONFIG = new JConfig();
//					$offset = $CONFIG->offset;
//					if($apptpro_config->daylight_savings_time == "Yes"){
//						$offset = $offset+1;
//					}
//	
//					$local_startdatetime = strtotime(JRequest::getVar('startdate')." ".JRequest::getVar('starttime'));
//					$local_enddatetime = strtotime(JRequest::getVar('enddate')." ".JRequest::getVar('endtime'));
//					$gmt_startdatetime = $local_startdatetime + ($offset*3600);	
//					$gmt_enddatetime = $local_enddatetime + ($offset*3600);	
//					$duration = $gmt_enddatetime - $gmt_startdatetime;
//	
//					//convert to min and sec
//					$convert_min = $duration/60;
//					$convert_sec = $duration % 60;//seconds
//					//convert to hours and min
//					$convert_hr = floor($convert_min/60);//hours
//					$remainder = floor($convert_min % 60);//minutes
//	
//					$sql = "INSERT INTO thyme_events (title, starttime, duration, allday, owner, calendar, added, updated, uid, freq, next, notes) ".
//					" VALUES (".
//					"'".$this->_db->getEscaped($title_text)."',".
//					$gmt_startdatetime.",".
//					"'".sprintf( '%02d:%02d:00', $convert_hr, $remainder)."',".
//					"0, 1,".
//					$_POST[calendar_category].",".				
//					time().",".
//					time().",".
//					"'".md5(uniqid(rand(), true))."',".
//					"0, 0,".
//					"'".$_POST['calendar_comment']."<BR />[req id:". $row->id_requests ."]'".				
//					")";
//				} else if($apptpro_config->which_calendar == "JCalPro"){
//					// latest JCalPro stores all times a GMT (UTC-0) so we need to adjust
//					// the time based on server time zone setting
//					require_once( JPATH_SITE.DS.'configuration.php' );
//					$CONFIG = new JConfig();
//					$offset = $CONFIG->offset;
//					if($apptpro_config->daylight_savings_time == "Yes"){
//						$offset = $offset+1;
//					}
//					if($offset<0){
//						$offset_sign = "+";
//					} else {
//						$offset_sign = "-";
//					}	
//					$offset = abs($offset);
//					
//					$startdate = $_POST['startdate'];
//					$day = intval(substr($startdate, 8, 2));
//					$month = intval(substr($startdate, 5, 2));
//					$year = intval(substr($startdate, 0, 4));
//					global $my; 
//					$sql = "INSERT INTO `#__jcalpro_events` (`title` , `cat` , `day` , `month`, `year`, ".
//					"`approved` , `start_date` , `end_date` , `recur_type`, `recur_count`, `published`, `description` ) VALUES (". 
//					"'".$this->_db->getEscaped($title_text)."',".
//					$_POST[calendar_category].",".
//					$day.",".$month.",".$year.",1,".
//					"'".$_POST['startdate']. " ". $_POST['starttime']."' ".$offset_sign." INTERVAL ".$offset." HOUR,".
//					"'".$_POST['enddate']. " ". $_POST['endtime']."' ".$offset_sign." INTERVAL ".$offset." HOUR,".
//					"'', 1, 1, ".
//					"'".$calendar_comment."<BR />[req id:". $row->id_requests ."]'".
//					")";
				} else if($apptpro_config->which_calendar == "JCalPro2"){
					// latest JCalPro stores all times a GMT (UTC-0) so we need to adjust
					// the time based on server time zone setting
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
					
					$startdate = $row->startdate;
					$day = intval(substr($startdate, 8, 2));
					$month = intval(substr($startdate, 5, 2));
					$year = intval(substr($startdate, 0, 4));
					$sql = "INSERT INTO `#__jcalpro2_events` (`title` , `cal_id`, `cat` , `day` , `month`, `year`, ".
					"`approved` , `start_date` , `end_date` , `recur_type`, `recur_count`, `published`, `description` ) VALUES (". 
					"'".$this->_db->getEscaped($title_text)."',".
					$_POST[calendar_calendar].",".
					$_POST[calendar_category].",".
					$day.",".$month.",".$year.",1,".
					"'".$row->startdate. " ".$row->starttime."' ".$offset_sign." INTERVAL ".$offset." HOUR,".
					"'".$row->enddate. " ".$row->endtime."' ".$offset_sign." INTERVAL ".$offset." HOUR,".
					"'', 1, 1, ".
					"'".$calendar_comment." <BR />[req id:". $row->id_requests ."]'".
					")";
				} else if($apptpro_config->which_calendar == "Google"){			
					include_once( JPATH_SITE."/components/com_rsappt_pro2/svgcal.php" );
					require_once( JPATH_SITE.DS.'configuration.php' );
					$CONFIG = new JConfig();
					$offset = $CONFIG->offset;
					if($apptpro_config->daylight_savings_time == "Yes"){
						$offset = $offset+1;
					}
					$offset = tz_offset_to_string($offset);
					$gcal = new SVGCal;
					// login
					$result = $gcal->login($res_detail->google_user, $res_detail->google_password);
					if( $result != "ok"){
						echo $result;
						logIt($result, "on login to add Google Calendar event"); 
					}		
					$gcal->setTZOffset($offset);
					// set calendar
					if($res_data->google_default_calendar_name != ""){
						$gcal->setCalID($res_detail->google_default_calendar_name);
					}	
					
					//create event
					$event_id_full = $gcal->createEvent( 
					$title_text,
					$calendar_comment, 
					'',
					trim($row->startdate),
					trim($row->starttime),
					trim($row->enddate),
					trim($row->endtime));
					$event_id = substr($event_id_full, strrpos($event_id_full, "/")+1);
					// set event ID back in request
						$sql = "UPDATE #__sv_apptpro2_requests SET google_event_id = '".$event_id."', ".
							"google_calendar_id = '".$res_detail->google_default_calendar_name."' where id_requests = ".$row->id_requests;
					$this->_db->setQuery($sql);
					if(!$this->_db->query()){
						$this->setError($this->_db->getErrorMsg());
					}
				}
				//echo $event_id_full."<BR>";
				//echo $sql;
				//exit();
				
				$this->_db->setQuery($sql);
				if(!$this->_db->query()){
					if ($this->_db->getErrorNum()) {
						//if($this->_db->getErrorNum() != 1146){
							// ignore 1146 - table not found if component not installed	
							$this->setError($this->_db->getErrorMsg());
						//}
					}
				}
			}
		}

		$config =& JFactory::getConfig();
		$tzoffset = $config->getValue('config.offset');      
		if($apptpro_config->daylight_savings_time == "Yes"){
			$tzoffset = $tzoffset+1;
		}
		$offsetdate = JFactory::getDate();
		$offsetdate->setOffset($tzoffset);
		$reminder_log_time_format = "%H:%M - %b %d";
		$user =& JFactory::getUser();
		
		// Messages
		// If status was not accepted and is now, send a confirmation
		if($_POST['request_status']=='accepted'){
			if(JRequest::getVar('old_status') != 'accepted'){
				// send confirmation	
				$language = JFactory::getLanguage();
				$language->load('com_rsappt_pro2', JPATH_SITE, null, true);
				$subject = JText::_('RS1_CONFIRMATION_EMAIL_SUBJECT');
				sendMail($row->email, $subject, "confirmation", $row->id_requests);	
				if($res_detail->resource_email != ""){
					sendMail($res_detail->resource_email, $subject, "confirmation", $row->id_requests, "", $apptpro_config->attach_ics_resource);	
				}
				$returnCode = "";
				sendSMS($row->id_requests, "confirmation", $returnCode, $toResource="Yes");			
				logReminder("Booking set to accepted status:".$returnCode, $row->id_requests, $user->id, $row->name, $offsetdate->toFormat($reminder_log_time_format));
			}
		}
		if($_POST['request_status']=='canceled'){
			if(JRequest::getVar('old_status') != 'canceled'){
				
				// send cancellation	
				$language = JFactory::getLanguage();
				$language->load('com_rsappt_pro2', JPATH_SITE, null, true);
				$subject = JText::_('RS1_CANCELLATION_EMAIL_SUBJECT');
				sendMail($row->email, $subject, "cancellation", $row->id_requests);			
				if($res_detail->resource_email != ""){
					sendMail($res_detail->resource_email, $subject, "cancellation", $row->id_requests);	
				}
				$returnCode = "";
				sendSMS($row->id_requests, "cancellation", $returnCode, $toResource="Yes");			
				logReminder("Booking set to cancelled status:".$returnCode, $row->id_requests, $user->id, $row->name, $offsetdate->toFormat($reminder_log_time_format));
				
				if(JRequest::getVar('old_status') == 'accepted' || JRequest::getVar('old_status') == 'pending'){
					// return credit is used and refunds allowed.			
					if($apptpro_config->allow_user_credit_refunds == "Yes" && $row->credit_used > 0){
						$refund_amount = $row->credit_used;
						if($row->booking_total > 0 && $row->payment_status == 'paid'){
							// part of booking was paid by paypal, need to add that back to user's credit total
							$refund_amount += $row->booking_total;
						}				
						$sql = "UPDATE #__sv_apptpro2_user_credit SET balance = balance + ".$refund_amount." WHERE user_id = ".$row->user_id;
						$database->setQuery($sql);
						$database->query();
						if ($database -> getErrorNum()) {
							echo $database -> stderr();
							logIt($database->getErrorMsg()); 
						}		
						// set request.credit_used to -1 to indicate refunded and prevent multiple refunds if operator sets to canceled again.
						$sql = "UPDATE #__sv_apptpro2_requests SET credit_used = -1 WHERE id = ".$row->id_requests;
						$database->setQuery($sql);
						$database->query();
						if ($database -> getErrorNum()) {
							echo $database -> stderr();
							logIt($database->getErrorMsg()); 
						}		
						
						// add credit audit
						$sql = 'INSERT INTO #__sv_apptpro2_user_credit_activity (user_id, request_id, increase, comment, operator_id, balance) '.
						"VALUES (".$row->user_id.",".
						$row->id_requests.",".
						$refund_amount.",".
						"'".JText::_('RS1_ADMIN_CREDIT_ACTIVITY_REFUND_ON_CANCEL')."',".
						$user->id.",".
						"(SELECT balance from #__sv_apptpro2_user_credit WHERE user_id = ".$row->user_id."))";
						$database->setQuery($sql);
						$database->query();
						if ($database -> getErrorNum()) {
							echo $database -> stderr();
							logIt($database->getErrorMsg()); 
						}
					}
				}
			}
		}
		return true;
	}
	
	function delete($cid = array())	{
		
		// get config info
		$sql = 'SELECT * FROM #__sv_apptpro2_config';
		$this->_db->setQuery($sql);
		$apptpro_config = NULL;
		$apptpro_config = $this->_db -> loadObject();
		if ($this->_db->getErrorNum()) {
			echo $this->_db->stderr();
			logIt($this->_db->getErrorMsg()); 
			return false;
		}
	
		foreach ($cid as $x=>$one_id) {
			// First delete calendar record for this request if one exists
			if($apptpro_config->which_calendar == "JEvents"){
				$sql = "DELETE FROM `#__events` WHERE INSTR(extra_info, '[req id:". $one_id ."]')>0";
			} else if($apptpro_config->which_calendar == "JCalPro"){
				$sql = "DELETE FROM `#__jcalpro_events` WHERE INSTR(description, '[req id:". $one_id ."]')>0";
			} else if($apptpro_config->which_calendar == "JCalPro2"){
				$sql = "DELETE FROM `#__jcalpro2_events` WHERE INSTR(description, '[req id:". $one_id ."]')>0";
			} else if($apptpro_config->which_calendar == "Thyme"){
				$sql = "DELETE FROM `thyme_events` WHERE INSTR(notes, '[req id:".$one_id."]')>0";
			} else if($apptpro_config->which_calendar == "EventList"){
				$sql = "DELETE FROM `#__eventlist_events` WHERE INSTR(datdescription, '[req id:". $one_id ."]')>0";
			} else if($apptpro_config->which_calendar == "Google" ){
				// need to get google_event_id from request
				$sql = "SELECT * FROM #__sv_apptpro2_requests WHERE id_requests = ".$one_id;
				$this->_db->setQuery($sql);
				$row = NULL;
				$row = $this->_db->loadObject();
				if($row->google_event_id != ""){
					
					include_once( JPATH_SITE."/components/com_rsappt_pro2/svgcal.php" );
					$gcal = new SVGCal;
					// login
					$sql = "SELECT * FROM #__sv_apptpro2_resources WHERE id_resources = ".$row->resource;
					$this->_db->setQuery($sql);
					$res_detail = NULL;
					$res_detail = $this->_db->loadObject();
					$result = $gcal->login($res_detail->google_user, $res_detail->google_password);
					if( $result == "ok"){
						$client = $gcal->getClient();	
							if($row->google_calendar_id == ""){
							$gcal->deleteEventById($gcal->getClient(), $row->google_event_id);
						} else {
							$result = $gcal->deleteEvent($gcal->getClient(), $row->google_event_id, $row->google_calendar_id);
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
			}	
			
			//echo $sql."<br>";
			//exit();
			$this->_db->setQuery($sql);
			if(!$this->_db->query()){
				if ($this->_db->getErrorNum()) {
					if($this->_db->getErrorNum() != 1146){
						// ignore 1146 - table not found if component not installed
						echo $this->_db->stderr();
						logIt($this->_db->getErrorMsg()); 
						return false;
					}
				}
			}
		}
		unset($one_id); // break the reference with the last element
	
	
	
		if (count($cid))
		{
			$ids = implode(',', $cid);
	
			// Delete udf_values
			$sql = "DELETE FROM `#__sv_apptpro2_udfvalues` WHERE request_id IN (".$ids.")";
			//exit();
			$this->_db->setQuery($sql);
			if(!$this->_db->query()){
				if ($this->_db->getErrorNum()) {
					if($this->_db->getErrorNum() != 1146){
						// ignore 1146 - table not found if component not installed
						echo $this->_db->stderr();
						logIt($this->_db->getErrorMsg()); 
					}
				}
			}
	
			// Delete seat_counts
			$sql = "DELETE FROM `#__sv_apptpro2_seat_counts` WHERE request_id IN (".$ids.")";
			//exit();
			$this->_db->setQuery($sql);
			if(!$this->_db->query()){
				if ($this->_db->getErrorNum()) {
					if($this->_db->getErrorNum() != 1146){
						// ignore 1146 - table not found if component not installed
						echo $this->_db->stderr();
						logIt($this->_db->getErrorMsg()); 
					}
				}
			}
	
			// paypal stuff
			$this->_db->setQuery("DELETE FROM #__sv_apptpro2_paypal_transactions \nWHERE custom IN (".$ids.")");
			if(!$this->_db->query()){
				if ($this->_db->getErrorNum()) {
					echo $this->_db->stderr();
					logIt($this->_db->getErrorMsg()); 
				}
			}
	
			$this->_db->setQuery("DELETE FROM #__sv_apptpro2_requests \nWHERE id_requests IN (".$ids.")");
		}
		if (!$this->_db->query()) {
			echo $this->_db->getErrorMsg();
			logIt($this->_db->getErrorMsg()); 
			return false;
		}

//		if($apptpro_config->activity_logging == "Max"){
//			LogActivity(0, "One or more appointments deleted, id(s) = ".$ids);
//		}
		
		return true;
		
	}

}

class admin_detailModelrequests_detail extends JModel
{
		var $_id_requests = null;
		var $_data = null;
		var $_table_prefix = null;

	function __construct()
	{
		parent::__construct();
		
		//initialize class property
	  	$this->_table_prefix = '#__sv_apptpro2_';			

		$array = JRequest::getVar('cid',  0, '', 'array');
		
		$this->setId((int)$array[0]);
	}

	/**
	 * Method to set the requests identifier
	 *
	 * @access	public
	 * @param	int requests identifier
	 */
	function setId($id_requests)
	{
		// Set requests id and wipe data
		$this->_id_requests		= $id_requests;
		$this->_data	= null;
	}

	/**
	 * Method to get a requests
	 *
	 * @since 1.5
	 */
	function &getData()
	{
		// Load the requests data
		if ($this->_loadData())
		{
		//load the data nothing else	  
		}
		else  $this->_initData();
		//print_r($this->_data);	
		
   	return $this->_data;
	}
	
	/**
	 * Method to checkout/lock the requests
	 *
	 * @access	public
	 * @param	int	$uid	User ID of the user checking the article out
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function checkout($uid = null)
	{
		if ($this->_id_requests)
		{
			// Make sure we have a user id to checkout the article with
			if (is_null($uid)) {
				$user	=& JFactory::getUser();
				$uid	= $user->get('id');
			}
			// Lets get to it and checkout the thing...
			$requests = & $this->getTable();
			
			
			if(!$requests->checkout($uid, $this->_id_requests)) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}

			return true;
		}
		return false;
	}
	/**
	 * Method to checkin/unlock the requests
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function checkin()
	{
		if ($this->_id_requests)
		{
			$requests = & $this->getTable();
			if(! $requests->checkin($this->_id_requests)) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}
		return false;
	}	
	/**
	 * Tests if requests is checked out
	 *
	 * @access	public
	 * @param	int	A user id
	 * @return	boolean	True if checked out
	 * @since	1.5
	 */
	function isCheckedOut( $uid=0 )
	{
		if ($this->_loadData())
		{
			if ($uid) {
				return ($this->_data->checked_out && $this->_data->checked_out != $uid);
			} else {
				return $this->_data->checked_out;
			}
		}
	}	
		
	/**
	 * Method to load content requests data
	 *
	 * @access	private
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function _loadData()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_data))
		{
			$query = 'SELECT * FROM '.$this->_table_prefix.'requests WHERE id_requests = '. $this->_id_requests;
			$this->_db->setQuery($query);
			$this->_data = $this->_db->loadObject();
			//print_r($this->_data);
			return (boolean) $this->_data;
		}
		return true;
	}

	/**
	 * Method to initialise the requests data
	 *
	 * @access	private
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function _initData()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_data))
		{
			$detail = new stdClass();
			$detail->resource_id = null;
			$detail->user_id = null;
			$detail->name = null;
			$detail->phone = null;
			$detail->email = null;
			$detail->resource = null;
			$detail->starttime = null;
			$detail->startdate = null;
			$detail->enddate = null;
			$detail->endtime = null;
			$detail->comment = null;
			$detail->admin_comment = null;
			$detail->request_status = null;
			$detail->payment_status = null;
			$detail->show_on_calendar = null;
			$detail->calendar_category = null;
			$detail->calendar_calendar = null;
			$detail->calendar_comment = null;
			$detail->created = null;
			$detail->cancellation_id = null;
			$detail->service = null;
			$detail->txnid = null;
			$detail->sms_reminders = "No";
			$detail->sms_phone = null;
			$detail->sms_dial_code = null;
			$detail->google_event_id = '';
			$detail->google_calendar_id = '';
			$detail->booking_total = 0.00;
  			$detail->booking_due = 0.00;
			$detail->coupon_code = null;
			$detail->booked_seats = 0;
			$detail->booking_language = 'eb-gb';
			$detail->credit_used = "0.00";
			$detail->checked_out = 0;
			$detail->checked_out_time = 0;
			$detail->ordering = 1;
			$detail->published = 0;
			$this->_data	= $detail;
			return (boolean) $this->_data;
		}
		return true;
	}
  	

	/**
	 * Method to store the requests text
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function store($data)
	{
		//DEVNOTE: Load table class from com_rsappt_pro2/tables/requests_detail.php	
		$row =& $this->getTable();

		// Bind the form fields to the requests table
		if (!$row->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// if new item, order last in appropriate group
		if (!$row->id_requests) {
			$where = 'id_requests = ' . $row->id_requests ;
			$row->ordering = $row->getNextOrder ( $where );
		}

		// get config info
		$sql = 'SELECT * FROM #__sv_apptpro2_config';
		$this->_db->setQuery($sql);
		$apptpro_config = NULL;
		$apptpro_config = $this->_db->loadObject();
		if ($this->_db->getErrorNum()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}	
	
		// get resource info for the selected resource
		$sql = 'SELECT * FROM #__sv_apptpro2_resources where id_resources = '.$row->resource;
		$this->_db->setQuery($sql);
		$res_detail = NULL;
		$res_detail = $this->_db->loadObject();
		if ($this->_db->getErrorNum()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
	
		// if request_status = 'accepted', check max seats not exceeded
		// first just see if this booking's seats > the resource's
	
		// max_seats = 0 = no limit
		if($res_detail->max_seats > 0 && $row->request_status == "accepted"){	
			if($row->booked_seats > $res_detail->max_seats){
				echo "<script> alert('".JText::_('RS1_ADMIN_SCRN_EXCEED_SEATS')."'); window.history.go(-1); </script>\n";
				exit();	
			}	
			// now check to see if there are other bookings and if so how many total seats are booked.
			$currentcount = getCurrentSeatCount($row->startdate, $row->starttime, $row->enddate, $row->resource, $row->id_requests);
		
			if ($currentcount + $row->booked_seats > $res_detail->max_seats){
				echo "<script> alert('".JText::_('RS1_ADMIN_SCRN_EXCEED_SEATS')."'); window.history.go(-1); </script>\n";
				exit();	
			}
		}	

		// Store the requests table to the database
		if (!$row->store()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// save seat counts if required
		$seat_type_count = JRequest::getVar( 'seat_type_count', '0' );
		if($seat_type_count > 0 ){
			// For each seat type there are two possibilities; 
			// 1. there was an entry and it needs to be updated
			// 2. there was no entry and we need a new one IF the qty is now >0
			// If the was en entry and it's qty is down to 0, do not delete it, just update 
			
			for($st =0; $st<$seat_type_count; $st++){
				$seat_type_id = JRequest::getVar( 'seat_type_id_'.$st );
				$seat_type_qty = JRequest::getVar( 'seat_'.$st );
				$request_id = $row->id_requests;
				$seat_type_org_qty = JRequest::getVar( 'seat_type_org_qty_'.$st );
				if($seat_type_org_qty != $seat_type_qty){				
					$sql = "UPDATE #__sv_apptpro2_seat_counts SET seat_type_qty=".$seat_type_qty." WHERE request_id=".$request_id." AND seat_type_id=".$seat_type_id;				
					$this->_db->setQuery($sql);
					$result = NULL;
					$result = $this->_db->query();
					if ($this->_db->getErrorNum()) {
						$this->setError($this->_db->getErrorMsg());
						return false;
					}
					if ($this->_db->getAffectedRows()==0 && $seat_type_qty>0) {
						$sql = "INSERT INTO #__sv_apptpro2_seat_counts (request_id, seat_type_id, seat_type_qty) values(".$request_id.",".$seat_type_id.",".$seat_type_qty.")";				
						$this->_db->setQuery($sql);
						$result = $this->_db->query();
						if ($this->_db->getErrorNum()) {
							$this->setError($this->_db->getErrorMsg());
							return false;
						}
					}			
				}
			}
		}		
	
		// save udf changes
		$udf_rows_count = JRequest::getVar( 'udf_rows_count', '0' );
		if($udf_rows_count > 0 ){
			for($udfr=0; $udfr < $udf_rows_count; $udfr++){
				$udf_value_id = JRequest::getVar( 'udf_id_'.$udfr);
				$udf_value = JRequest::getVar( 'udf_value_'.$udfr);
				$sql = "UPDATE #__sv_apptpro2_udfvalues SET udf_value='".$udf_value."' WHERE id=".$udf_value_id;				
				$this->_db->setQuery($sql);
				$result = NULL;
				$result = $this->_db->query();
				if ($this->_db->getErrorNum()) {
					$this->setError($this->_db->getErrorMsg());
					return false;
				}
			}
		}
	
		// calendar stuff
		if($apptpro_config->which_calendar != 'None'){
			
			// remove calendar entry
			// First delete calendar record for this request if one exists
/*			if($apptpro_config->which_calendar == "JEvents"){
				$sql = "DELETE FROM `#__events` WHERE INSTR(extra_info, '[req id:". $row->id_requests ."]')>0";
			} else if($apptpro_config->which_calendar == "JCalPro"){
				$sql = "DELETE FROM `#__jcalpro_events` WHERE INSTR(description, '[req id:". $row->id_requests ."]')>0";
			} else*/ if($apptpro_config->which_calendar == "JCalPro2"){
				$sql = "DELETE FROM `#__jcalpro2_events` WHERE INSTR(description, '[req id:". $row->id_requests ."]')>0";
			} else if($apptpro_config->which_calendar == "EventList"){
				$sql = "DELETE FROM `#__eventlist_events` WHERE INSTR(datdescription, '[req id:". $row->id_requests ."]')>0";
			} else if($apptpro_config->which_calendar == "Google" and $row->google_event_id != ""){
			
				include_once( JPATH_SITE."/components/com_rsappt_pro2/svgcal.php" );
	
				$gcal = new SVGCal;
				// login
				$result = $gcal->login($res_detail->google_user, $res_detail->google_password);
				if( $result == "ok"){
					$client = $gcal->getClient();	
						if($row->google_calendar_id == ""){
						$gcal->deleteEventById($gcal->getClient(), $row->google_event_id);
					} else {
						$result = $gcal->deleteEvent($gcal->getClient(), $row->google_event_id, $row->google_calendar_id);
						if($result != "ok"){
							echo $result;
							logIt($result, "on delete of Google Calendar event"); 
						}
					}		
				} else {
					echo $result;
					logIt($result, "on login for delete of Google Calendar event"); 
				}						
			}	
			//echo $sql;
			//exit();
			$this->_db->setQuery($sql);
			if(!$this->_db->query()){
				if ($this->_db->getErrorNum()) {
					if($this->_db->getErrorNum() != 1146){
						// ignore 1146 - table not found if component not installed
						$this->setError($this->_db->getErrorMsg());
						return false;
					}
				}
			}
			
			if ($_POST['show_on_calendar']=='Yes' and $_POST['request_status']=='accepted'){
				$this->_db->setQuery("SELECT description FROM #__sv_apptpro2_resources WHERE name = '".$_POST['resource']."'" );
				$rows = $this->_db->loadObjectList();
				if ($this->_db->getErrorNum()) {
					$this->setError($this->_db->getErrorMsg());
					return false;
				}
				$Title = $rows[0]->description; 
				
		
				// get resource name
				$res_data = NULL;
				$sql = "SELECT * FROM #__sv_apptpro2_resources WHERE id_resources=".$_POST[resource];
				//echo $sql;
				//exit;
				$this->_db->setQuery($sql);
				$res_data = $this->_db->loadObject();
				if ($this->_db->getErrorNum()) {
					$this->setError($this->_db->getErrorMsg());
					return false;
				}
		
				switch ($apptpro_config->calendar_title) {
				  case 'resource.name': {
					$title_text = stripslashes($res_data->name);	
					break;
				  }
				  case 'request.name': {
					$title_text = stripslashes($row->name);	
					break;
				  }
				  default: {
					// must be a udf, get udf_value
					$sql = "SELECT udf_value FROM #__sv_apptpro2_udfvalues WHERE request_id = ".$row->id_requests." and udf_id=".$apptpro_config->calendar_title;
					$this->_db->setQuery( $sql);
					$title_text = $this->_db->loadResult(); 		
				  }
				}
	
				$calendar_comment = "";
				if($apptpro_config->calendar_body2 != "") {
					$calendar_comment = $_POST['calendar_comment'].buildMessage($row->id_requests, "calendar_body", "No");
				}		
				stripslashes($calendar_comment);
				$calendar_comment = str_replace("'", "`", $calendar_comment);
				$user =& JFactory::getUser();
	
				if($apptpro_config->which_calendar == "EventList"){
					$sql = "INSERT INTO `#__eventlist_events` (`catsid`,`title`, `dates`, `enddates`,".
					"`times`,`endtimes`, `datdescription`, `published`) VALUES (".
					$_POST[calendar_category].",".
					"'".$this->_db->getEscaped($title_text)."',".
					"'".$_POST['startdate']. "',".
					"'".$_POST['enddate']."',".
					"'".$_POST['starttime']. "',".
					"'". $_POST['endtime']."',".
					"'".$calendar_comment."<BR />[req id:". $row->id_requests ."]', 1".
					")";
//				} else if($apptpro_config->which_calendar == "JEvents"){
//					$sql = "INSERT INTO `#__events` (`catid`,`title`,`content`,`useCatColor`,`state`,".
//					"`created_by`,`created_by_alias`,`publish_up`,`publish_down`, `extra_info`) VALUES (".
//					$_POST[calendar_category].",".
//					"'".$this->_db->getEscaped($title_text)."',".
//					"'".$res_detail->name."',".
//					"1, 1,".
//					"'".$user->id."',".
//					"'".$user->name."',".
//					"'".$_POST['startdate']. " ". $_POST['starttime']."',".
//					"'".$_POST['enddate']. " ". $_POST['endtime']."',".
//					"'".$_POST['calendar_comment']."<BR />[req id:". $row->id_requests ."]'".
//					")";
//				} else if($apptpro_config->which_calendar == "Thyme"){
//					// times stores GMT, need to adjust to local based on Joomla time zone setting
//					require_once( JPATH_SITE.DS.'configuration.php' );
//					$CONFIG = new JConfig();
//					$offset = $CONFIG->offset;
//					if($apptpro_config->daylight_savings_time == "Yes"){
//						$offset = $offset+1;
//					}
//	
//					$local_startdatetime = strtotime(JRequest::getVar('startdate')." ".JRequest::getVar('starttime'));
//					$local_enddatetime = strtotime(JRequest::getVar('enddate')." ".JRequest::getVar('endtime'));
//					$gmt_startdatetime = $local_startdatetime + ($offset*3600);	
//					$gmt_enddatetime = $local_enddatetime + ($offset*3600);	
//					$duration = $gmt_enddatetime - $gmt_startdatetime;
//	
//					//convert to min and sec
//					$convert_min = $duration/60;
//					$convert_sec = $duration % 60;//seconds
//					//convert to hours and min
//					$convert_hr = floor($convert_min/60);//hours
//					$remainder = floor($convert_min % 60);//minutes
//	
//					$sql = "INSERT INTO thyme_events (title, starttime, duration, allday, owner, calendar, added, updated, uid, freq, next, notes) ".
//					" VALUES (".
//					"'".$this->_db->getEscaped($title_text)."',".
//					$gmt_startdatetime.",".
//					"'".sprintf( '%02d:%02d:00', $convert_hr, $remainder)."',".
//					"0, 1,".
//					$_POST[calendar_category].",".				
//					time().",".
//					time().",".
//					"'".md5(uniqid(rand(), true))."',".
//					"0, 0,".
//					"'".$_POST['calendar_comment']."<BR />[req id:". $row->id_requests ."]'".				
//					")";
//				} else if($apptpro_config->which_calendar == "JCalPro"){
//					// latest JCalPro stores all times a GMT (UTC-0) so we need to adjust
//					// the time based on server time zone setting
//					require_once( JPATH_SITE.DS.'configuration.php' );
//					$CONFIG = new JConfig();
//					$offset = $CONFIG->offset;
//					if($apptpro_config->daylight_savings_time == "Yes"){
//						$offset = $offset+1;
//					}
//					if($offset<0){
//						$offset_sign = "+";
//					} else {
//						$offset_sign = "-";
//					}	
//					$offset = abs($offset);
//					
//					$startdate = $_POST['startdate'];
//					$day = intval(substr($startdate, 8, 2));
//					$month = intval(substr($startdate, 5, 2));
//					$year = intval(substr($startdate, 0, 4));
//					global $my; 
//					$sql = "INSERT INTO `#__jcalpro_events` (`title` , `cat` , `day` , `month`, `year`, ".
//					"`approved` , `start_date` , `end_date` , `recur_type`, `recur_count`, `published`, `description` ) VALUES (". 
//					"'".$this->_db->getEscaped($title_text)."',".
//					$_POST[calendar_category].",".
//					$day.",".$month.",".$year.",1,".
//					"'".$_POST['startdate']. " ". $_POST['starttime']."' ".$offset_sign." INTERVAL ".$offset." HOUR,".
//					"'".$_POST['enddate']. " ". $_POST['endtime']."' ".$offset_sign." INTERVAL ".$offset." HOUR,".
//					"'', 1, 1, ".
//					"'".$calendar_comment."<BR />[req id:". $row->id_requests ."]'".
//					")";
				} else if($apptpro_config->which_calendar == "JCalPro2"){
					// latest JCalPro stores all times a GMT (UTC-0) so we need to adjust
					// the time based on server time zone setting
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
					
					$startdate = $row->startdate;
					$day = intval(substr($startdate, 8, 2));
					$month = intval(substr($startdate, 5, 2));
					$year = intval(substr($startdate, 0, 4));
					$sql = "INSERT INTO `#__jcalpro2_events` (`title` , `cal_id`, `cat` , `day` , `month`, `year`, ".
					"`approved` , `start_date` , `end_date` , `recur_type`, `recur_count`, `published`, `description` ) VALUES (". 
					"'".$this->_db->getEscaped($title_text)."',".
					$_POST[calendar_calendar].",".
					$_POST[calendar_category].",".
					$day.",".$month.",".$year.",1,".
					"'".$row->startdate. " ".$row->starttime."' ".$offset_sign." INTERVAL ".$offset." HOUR,".
					"'".$row->enddate. " ".$row->endtime."' ".$offset_sign." INTERVAL ".$offset." HOUR,".
					"'', 1, 1, ".
					"'".$calendar_comment." <BR />[req id:". $row->id_requests ."]'".
					")";
				} else if($apptpro_config->which_calendar == "Google"){			
					include_once( JPATH_SITE."/components/com_rsappt_pro2/svgcal.php" );
					require_once( JPATH_SITE.DS.'configuration.php' );
					$CONFIG = new JConfig();
					$offset = $CONFIG->offset;
					if($apptpro_config->daylight_savings_time == "Yes"){
						$offset = $offset+1;
					}
					$offset = tz_offset_to_string($offset);
					$gcal = new SVGCal;
					// login
					$result = $gcal->login($res_detail->google_user, $res_detail->google_password);
					if( $result != "ok"){
						echo $result;
						logIt($result, "on login to add Google Calendar event"); 
					}		
					$gcal->setTZOffset($offset);
					// set calendar
					if($res_data->google_default_calendar_name != ""){
						try{
							$gcal->setCalID($res_detail->google_default_calendar_name);
						}catch (Zend_Gdata_App_Exception $e) { 
							echo $e->getMessage();
						} 											
					}	
					
					//create event
					try{
						$event_id_full = $gcal->createEvent( 
						$title_text,
						$calendar_comment, 
						'',
						trim($row->startdate),
						trim($row->starttime),
						trim($row->enddate),
						trim($row->endtime));
					}catch (Zend_Gdata_App_Exception $e) { 
						echo $e->getMessage();
					} 				
						
					$event_id = substr($event_id_full, strrpos($event_id_full, "/")+1);
					// set event ID back in request
						$sql = "UPDATE #__sv_apptpro2_requests SET google_event_id = '".$event_id."', ".
							"google_calendar_id = '".$res_detail->google_default_calendar_name."' where id_requests = ".$row->id_requests;
					$this->_db->setQuery($sql);
					if(!$this->_db->query()){
						$this->setError($this->_db->getErrorMsg());
					}
				}
				//echo $event_id_full."<BR>";
				//echo $sql;
				//exit();
				
				$this->_db->setQuery($sql);
				if(!$this->_db->query()){
					if ($this->_db->getErrorNum()) {
						//if($this->_db->getErrorNum() != 1146){
							// ignore 1146 - table not found if component not installed	
							$this->setError($this->_db->getErrorMsg());
						//}
					}
				}
			}
		}

		$config =& JFactory::getConfig();
		$tzoffset = $config->getValue('config.offset');      
		if($apptpro_config->daylight_savings_time == "Yes"){
			$tzoffset = $tzoffset+1;
		}
		$offsetdate = JFactory::getDate();
		$offsetdate->setOffset($tzoffset);
		$reminder_log_time_format = "%H:%M - %b %d";
		$user =& JFactory::getUser();
		
		// Messages
		// If status was not accepted and is now, send a confirmation
		if($_POST['request_status']=='accepted'){
			if(JRequest::getVar('old_status') != 'accepted'){
				// send confirmation	
				$language = JFactory::getLanguage();
				$language->load('com_rsappt_pro2', JPATH_SITE, null, true);
				$subject = JText::_('RS1_CONFIRMATION_EMAIL_SUBJECT');
				sendMail($row->email, $subject, "confirmation", $row->id_requests);	
				if($res_detail->resource_email != ""){
					sendMail($res_detail->resource_email, $subject, "confirmation", $row->id_requests);	
				}
				$returnCode = "";
				sendSMS($row->id, "confirmation", $returnCode, $toResource="Yes");			
				logReminder("Booking set to accepted status:".$returnCode, $row->id_requests, $user->id, $row->name, $offsetdate->toFormat($reminder_log_time_format));
			}
		}
		if($_POST['request_status']=='canceled'){
			if(JRequest::getVar('old_status') != 'canceled'){
				
				// send cancellation	
				$language = JFactory::getLanguage();
				$language->load('com_rsappt_pro2', JPATH_SITE, null, true);
				$subject = JText::_('RS1_CANCELLATION_EMAIL_SUBJECT');
				sendMail($row->email, $subject, "cancellation", $row->id_requests);			
				if($res_detail->resource_email != ""){
					sendMail($res_detail->resource_email, $subject, "cancellation", $row->id_requests);	
				}
				$returnCode = "";
				sendSMS($row->id, "cancellation", $returnCode, $toResource="Yes");			
				logReminder("Booking set to cancelled status:".$returnCode, $row->id_requests, $user->id, $row->name, $offsetdate->toFormat($reminder_log_time_format));
				
				if(JRequest::getVar('old_status') == 'accepted'){
					// return credit is used and refunds allowed.			
					if($apptpro_config->allow_user_credit_refunds == "Yes" && $row->credit_used > 0){
						$refund_amount = $row->credit_used;
						if($row->booking_total > 0){
							// part of booking was paid by paypal, need to add that back to user's credit total
							$refund_amount += $row->booking_total;
						}				
						$sql = "UPDATE #__sv_apptpro_user_credit SET balance = balance + ".$refund_amount." WHERE user_id = ".$row->user_id;
						$database->setQuery($sql);
						$database->query();
						if ($database -> getErrorNum()) {
							echo $database -> stderr();
							logIt($database->getErrorMsg()); 
						}		
						// set request.credit_used to -1 to indicate refunded and prevent multiple refunds if operator sets to canceled again.
						$sql = "UPDATE #__sv_apptpro_requests SET credit_used = -1 WHERE id = ".$row->id_requests;
						$database->setQuery($sql);
						$database->query();
						if ($database -> getErrorNum()) {
							echo $database -> stderr();
							logIt($database->getErrorMsg()); 
						}		
						
						// add credit audit
						$sql = 'INSERT INTO #__sv_apptpro_user_credit_activity (user_id, request_id, increase, comment, operator_id, balance) '.
						"VALUES (".$row->user_id.",".
						$row->id.",".
						$refund_amount.",".
						"'".JText::_('RS1_ADMIN_CREDIT_ACTIVITY_REFUND_ON_CANCEL')."',".
						$user->id.",".
						"(SELECT balance from #__sv_apptpro_user_credit WHERE user_id = ".$row->user_id."))";
						$database->setQuery($sql);
						$database->query();
						if ($database -> getErrorNum()) {
							echo $database -> stderr();
							logIt($database->getErrorMsg()); 
						}
					}
				}
			}
		}
		return true;
	}
	
		/**
	 * Method to (un)publish a requests
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function publish($cid = array(), $publish = 1)
	{
		$user 	=& JFactory::getUser();

		if (count( $cid ))
		{
			$cids = implode( ',', $cid );

			$query = 'UPDATE '.$this->_table_prefix.'requests'
				. ' SET published = ' . intval( $publish )
				. ' WHERE id_requests IN ( '.$cids.' )'
				. ' AND ( checked_out = 0 OR ( checked_out = ' .$user->get('id'). ' ) )'
			;

			$this->_db->setQuery( $query );
			if (!$this->_db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}

		return true;
	}
	
	/**
	 * Method to move a requests_detail
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function saveorder($cid = array(), $order)
	{
		//DEVNOTE: Load table class from com_sv_ser/tables/requests_detail.php		
		$row =& $this->getTable();
		$groupings = array();

		// update ordering values
		for( $i=0; $i < count($cid); $i++ )
		{
			$row->load( (int) $cid[$i] );

			if ($row->ordering != $order[$i])
			{
				$row->ordering = $order[$i];
				if (!$row->store()) {
					$this->setError($this->_db->getErrorMsg());
					return false;
				}
			}
		}
		return true;
	}
		
		/**
	 * Method to move a requests 
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function move($direction)
	{
	//DEVNOTE: Load table class from com_sv_ser/tables/requests_detail.php	
		$row =& $this->getTable();
	//DEVNOTE: we need to pass here id of requests detail 		
		if (!$row->load($this->_id_requests)) {
			$this->setError($this->_db->getErrorMsg());
		
			return false;
		}
  
	//DEVNOTE: call move method of JTABLE. 
  //first parameter: direction [up/down]
  //second parameter: condition
		if (!$row->move( $direction, ' published >= 0 ' )) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		return true;
	}		

	function delete($cid = array())
	{
		$result = false;


		if (count( $cid ))
		{
			$cids = implode( ',', $cid );
			$query = 'DELETE FROM '.$this->_table_prefix.'requests WHERE id_requests IN ( '.$cids.' )';
			$this->_db->setQuery( $query );
			if(!$this->_db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}

		return true;
	}
	

	// methods for front-end screens
//
//	function _loadData_MyBookings()
//	{
//		// Lets load the content if it doesn't already exist
//		if (empty($this->_data_MyBookings))
//		{
//			// get config info
//			$sql = 'SELECT * FROM #__sv_apptpro2_config';
//			$this->_db->setQuery($sql);
//			$apptpro_config = NULL;
//			$apptpro_config = $this->_db->loadObject();
//			if ($this->_db->getErrorNum()) {
//				$this->setError($this->_db->getErrorMsg());
//				return false;
//			}	
//
//			$sql = "SELECT #__sv_apptpro_requests.*, #__sv_apptpro_resources.resource_admins, ".
//			"#__sv_apptpro_resources.name as resname, ".
//			" IF(#__sv_apptpro_requests.startdate > curdate(),'no','yes') as expired, ";
//			if($apptpro_config->timeFormat == "12"){
//				$sql = $sql." DATE_FORMAT(#__sv_apptpro_requests.startdate, '%a %b %e, %Y') as display_startdate, ".
//				"DATE_FORMAT(#__sv_apptpro_requests.starttime, '%l:%i %p') as display_starttime, ".
//				"DATE_FORMAT(#__sv_apptpro_requests.enddate, '%b %e, %Y') as display_enddate, ".
//				"DATE_FORMAT(#__sv_apptpro_requests.endtime, '%l:%i %p') as display_endtime ";
//			} else {
//				$sql = $sql." DATE_FORMAT(#__sv_apptpro_requests.startdate, '%a %b %e, %Y') as display_startdate, ".
//				"DATE_FORMAT(#__sv_apptpro_requests.starttime, '%k:%i') as display_starttime, ".
//				"DATE_FORMAT(#__sv_apptpro_requests.enddate, '%b %e, %Y') as display_enddate, ".
//				"DATE_FORMAT(#__sv_apptpro_requests.endtime, '%k:%i') as display_endtime ";
//			}
//			$sql = $sql." FROM #__sv_apptpro_requests INNER JOIN #__sv_apptpro_resources ".
//				"ON #__sv_apptpro_requests.resource = #__sv_apptpro_resources.id ".
//			"WHERE request_status!='deleted' AND ";
//			if($filter != ""){
//				$sql = $sql." request_status='".$filter."' AND ";
//			}
//			$sql = $sql."#__sv_apptpro_requests.user_id = ".$user->id.
////			" AND CONCAT(#__sv_apptpro_requests.startdate, ' ', #__sv_apptpro_requests.starttime) >= NOW() ".
//		" ORDER BY ".$ordering.' '.$direction;			
//			$this->_db->setQuery($query);
//			$this->_data_MyBookings = $this->_db->loadObject();
//			//print_r($this->_data);
//			return (boolean) $this->_data_MyBookings;
//		}
//		return true;
//	}
//
}

?>
