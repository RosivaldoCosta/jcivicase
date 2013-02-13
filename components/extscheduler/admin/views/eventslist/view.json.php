<?php
/*
 Copyright (c) 2011-2012 Campbell Consulting Studios, LLC
 ExtScheduler v1.0
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

define(THERAPIST, 53);
define(PHYISICIAN, 54);
define(IHIT, 83);

jimport( 'joomla.application.component.view' );
jimport('civicrm.session');
jimport('civicrm.uf');
jimport('civicrm.case');
jimport('civicrm.activity');


class frontdeskVieweventslist extends JView
{
	/**
	 * Custom Constructor
	 */
	function __construct( $config = array())
	{
		parent::__construct( $config );
	}


	
	function readevents()
	{
		// TODO: Create event for onEventRead	

		require_once JPATH_ROOT.'/'.'administrator/components/com_civicrm/civicrm/api/v2/ActivityContact.php';
		$params = array('activity_type_id' => 53);
		$therapistResult = civicrm_case_activity_get($params);

		$params = array('activity_type_id' => 54);
		$physicianResult = civicrm_case_activity_get($params);

		$params = array('activity_type_id' => 83);
		$ihitResult = civicrm_case_activity_get($params);

		//$params = array('activity_type_id' => 108);
		//$noteResult = civicrm_activity_get($params);

		//echo '<pre>'.print_r($noteResult,true).'</pre>';
		$extactivities = array();
		$extactivities["events"] = array();
		$activities = array_merge($physicianResult["result"], $therapistResult["result"]);
		$activities = array_merge($activities, $ihitResult["result"]);
		//$activities = array_merge($activities, $noteResult["result"]);

		foreach($activities as $actid => $actdata)
		{
			$activity = array();
			$activity["id"] = $actdata['id'];
			$activity["start"] = $actdata["activity_date_time"];
			$end_time = strtotime($activity["start"]);
			$end_time = $end_time+$actdata["duration"]*60;
			$end_time = date("Y-m-d H:i:s",$end_time);
			$activity["end"] = $end_time;
			$activity["title"] = $actdata["subject"];
			$activity["notes"] = $actdata["details"];
			$activity["caseid"] = $actdata["case_id"];
			$activity["calendarid"] = $actdata["activity_type_id"];
			$activity["contactid"] = $actdata["contact_id"];
			$activity["status_id"] = $actdata["status_id"];
			
			$extactivities["events"][] = $activity;
			$extactivities["originalevents"][] = $actdata;
		}		

		echo json_encode($extactivities);
		
		
	}

	function edit()
	{
		// TODO: Create event for onEventAdd	

		$rows = stripcslashes($_POST["events"]);
		$rows = json_decode($rows);

		if($rows != null)
		{
		
		$date = $rows->start;
		$date = str_replace("T", " ", $date);
		$start_time = strtotime($date);
		
		$date = $rows->end;
		$date=str_replace("T", " ", $date);
		$end_time=strtotime($date);
		$duration=$end_time-$start_time;
		$status_id = $rows->status_id;

		if($duration>0)
		{
			$duration=$duration/60;
		}
		else
		{
			$duration=0;
		}

		$user =& JFactory::getUser();
                CRM_Core_BAO_UFMatch::synchronize( $user, false, 'Joomla', 'Individual', true );

                $session = CRM_Core_Session::singleton();
                $contactId = $session->get('userID');
		
		
		$params = array('duration'=>$duration,
				'source_contact_id'=> $contactId, 
				'status_id'=>$status_id,
				'activity_type_id' => $rows->calendarid,
				'details'=>$rows->notes,
				'subject' => '('. $rows->caseid .') Appointment', //$rows->title,
				'case_id' => $rows->caseid,
				'activity_date_time' => date('YmdHis',$start_time));

		if($rows->calendarid === 83)
		{
			$params['custom_821'] = $rows->visittype;
			$params['subject'] = '('.$rows->caseid.') IHIT Visit';
		}

		if( $rows->calendarid == 108)
			$act = civicrm_activity_create($params);
		else
			$act = civicrm_case_activity_create($params);

		echo"{'success':true,'message':'".json_encode($act)."'}";
		}
		
	}

	function update()
	{
		// TODO: Create event for onEventUpdate

		jimport('civicrm.activity');

		$rows=stripcslashes($_POST["events"]);
		$rows=json_decode($rows);
		

		$updateParams = array(
                'id'                  => $rows->id,
                'activity_type_id'                  => $rows->calendarid,
                );

                if(isset($rows->title))	$updateParams["subject"] = $rows->title;
                if(isset($rows->notes))	$updateParams["details"] = $rows->notes;
                if(isset($rows->status_id))	$updateParams["status_id"] = $rows->status_id;
                if(isset($rows->cid))	$updateParams["activity_type_id"] = $rows->calendarid;
                if(isset($rows->start))	$updateParams["activity_date_time"] = date('YmdHis',strtotime(str_replace("T", " ", $rows->start)));

                if(isset($rows->end))
                {
                	$duration = strtotime(str_replace("T", " ", $rows->end))-strtotime(str_replace("T", " ", $rows->start));;
                	$updateParams["duration"] = $duration/60;
                }
                

		$actUpdated =& civicrm_case_activity_update( $updateParams );
		echo"{'success':true,'message':'".json_encode($actUpdated)."'}";	
		
	}
	
	
	function delete()
	{
		$id=$_POST["events"];
		$deleteParams = array(
                'id'                  => $id,
				'activity_name'		  => "Meeting"
                );

		$actDeleted =& civicrm_activity_delete( $deleteParams );
		echo"{'success':true,'message':'".json_encode($actDeleted)."'}";	
		
	}
	
	function display($tpl = null)
	{
		$task=$_GET["task"];
		switch($task)
		{
			case 'edit':
				$this->edit();
				break;
			case 'update':
				$this->update();
				break;
			case 'delete':
				$this->delete();
				break;
			case 'readevents':
				$this->readevents();
				break;
			default:
				$this->readevents();
				break;		
		}
		
		
	}
}

?>
