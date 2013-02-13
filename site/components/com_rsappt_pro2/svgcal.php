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

	define( 'DS', DIRECTORY_SEPARATOR );
	$path = JPATH_SITE.DS."library";
	set_include_path(get_include_path() . PATH_SEPARATOR . $path);
	//echo $path;
	require_once 'Zend/Loader.php';
	Zend_Loader::loadClass('Zend_Gdata');
	Zend_Loader::loadClass('Zend_Gdata_AuthSub'); 
	Zend_Loader::loadClass('Zend_Gdata_ClientLogin');
	Zend_Loader::loadClass('Zend_Gdata_Calendar');

class SVGCal
{

	var $client = null;
	var $tzOffset = "0";
	var $cal_id = "";
	var $username = "";
	
	function login($username, $password){
	
		$service = Zend_Gdata_Calendar::AUTH_SERVICE_NAME; // predefined service name for calendar	
		try { 
			$this->client = Zend_Gdata_ClientLogin::getHttpClient($username, $password, $service);
			$this->username = $username;
		}
		catch (Zend_Gdata_App_AuthException $ae) {
		    return 'Problem authenticating Google Calendar:'.$ae->getMessage();
		}
		return "ok";	
	}


	function setTZOffset($value){
		$this->tzOffset = $value;		
	}

	function setCalID($value){
		$this->cal_id = $value;		
	}

	function getClient(){
		return $this->client;
	}

	function createEvent ($title='', $desc='', $where='',  $startDate='', $startTime='', $endDate='', $endTime=''){
		if($this->client == null){
			echo "Not Logged in";
			return -1;
		}		
		$gdataCal = new Zend_Gdata_Calendar($this->client);
		$newEvent = $gdataCal->newEventEntry();
		
		$newEvent->title = $gdataCal->newTitle($title);
		$newEvent->where = array($gdataCal->newWhere($where));
		$newEvent->content = $gdataCal->newContent("$desc");
		
		$when = $gdataCal->newWhen();
		$when->startTime = "{$startDate}T{$startTime}.000{$this->tzOffset}";
		$when->endTime = "{$endDate}T{$endTime}.000{$this->tzOffset}";
		$newEvent->when = array($when);
		
		// Upload the event to the calendar server
		// A copy of the event as it is recorded on the server is returned
	
		if($this->cal_id != ""){
			// using non-default
			$createdEvent = $gdataCal->insertEvent($newEvent, "http://www.google.com/calendar/feeds/".$this->cal_id."/private/full");
		} else {
			$createdEvent = $gdataCal->insertEvent($newEvent);
		}
		return $createdEvent->id->text;
	}

	
	function deleteEventById (Zend_Http_Client $client, $eventId) 
	{
		$event = $this->getEvent($client, $eventId);
		if($event != null){
			$event->delete();
		}
	}
	

	function deleteEvent ($client, $eventId, $cal_id='default') 
	{
		$gdataCal = new Zend_Gdata_Calendar($client);
		if ($eventOld = $this->getEvent($client, $eventId, $cal_id)) {
			try {
				$eventOld->delete();
			} catch (Zend_Exception $e) {
				return $e->getMessage();
			}
			return "ok";
		} else {
			return "event not found";
		}
	}
	
	
	function getEvent($client, $eventId, $cal_id='default'){ 
		$gdataCal = new Zend_Gdata_Calendar($client); 
		$query = $gdataCal->newEventQuery(); 
		$query->setUser($cal_id); 
		$query->setVisibility('private'); 
		$query->setProjection('full'); 
		$query->setEvent($eventId); 
		
		try { 
			$eventEntry = $gdataCal->getCalendarEventEntry($query); 
			return $eventEntry; 
		} catch (Zend_Gdata_App_Exception $e) { 
			echo $e->getMessage();
			return null; 
		} 
	} 



}
?>