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
 
class adminController extends JController
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
		
		$this->registerTask( 'publish_resource', 'publish_resource' );
		$this->registerTask( 'unpublish_resource', 'unpublish_resource' );
		$this->registerTask( 'remove_resource', 'remove_resource' );
		$this->registerTask( 'copy_resource', 'copy_resource' );


		$this->registerTask( 'res_sort', 'res_sort' );

		$this->registerTask( 'publish_service', 'publish_service' );
		$this->registerTask( 'unpublish_service', 'unpublish_service' );
		$this->registerTask( 'remove_service', 'remove_service' );
		$this->registerTask( 'copy', 'copy_services' );
		$this->registerTask( 'docopy_service', 'do_copy_services' );

		$this->registerTask( 'publish_timeslot', 'publish_timeslot' );
		$this->registerTask( 'unpublish_timeslot', 'unpublish_timeslot' );
		$this->registerTask( 'remove_timeslot', 'remove_timeslot' );
		$this->registerTask( 'copy_timeslots', 'copy_timeslots' );
		$this->registerTask( 'docopy_timeslot', 'do_copy_timeslots' );
		$this->registerTask( 'do_global_import_timeslots', 'do_global_import_timeslots' );

		$this->registerTask( 'publish_bookoff', 'publish_bookoff' );
		$this->registerTask( 'unpublish_bookoff', 'unpublish_bookoff' );
		$this->registerTask( 'remove_bookoff', 'remove_bookoff' );
		$this->registerTask( 'copy_bookoffs', 'copy_bookoffs' );
		$this->registerTask( 'docopy_bookoffs', 'do_copy_bookoffs' );

		$this->registerTask( 'publish_coupon', 'publish_coupon' );
		$this->registerTask( 'unpublish_coupon', 'unpublish_coupon' );
		$this->registerTask( 'remove_coupon', 'remove_coupon' );

		$this->registerTask( 'publish_extra', 'publish_extra' );
		$this->registerTask( 'unpublish_extra', 'unpublish_extra' );
		$this->registerTask( 'remove_extra', 'remove_extra' );

		$this->registerTask( 'ipn', 'ipn' );

	}

	function list_bookings()
	{
		JRequest::setVar( 'view', 'admin' );
		JRequest::setVar( 'layout', 'default'  );
		JRequest::setVar( 'hidemainmenu', 0);


		parent::display();

	}

	function cancel()
	{
		$frompage = JRequest::getVar('frompage');
		$frompage_item = JRequest::getVar('frompage_item');
		$fromtab = JRequest::getVar('fromtab');
		
		$this->setRedirect( 'index.php?option=com_rsappt_pro2&view='.$frompage.'&Itemid='.$frompage_item.'&current_tab='.$fromtab );
	}	

	function publish_resource()
	{
		$post	= JRequest::get('post');
		$cid	= JRequest::getVar( 'cid_res', array(0), 'post', 'array' );
		$post['id_resources'] = $cid[0];
		$frompage = JRequest::getVar('frompage');
		$frompage_item = JRequest::getVar('frompage_item');
		$current_tab = JRequest::getVar('current_tab');

		require_once(JPATH_COMPONENT.DS.'models'.DS.'resources_detail.php');
		$model = new admin_detailModelresources_detail;
 		if($model == null){
			echo "model = null";
			exit;
		}
	
		if (!$model->publish($cid)) {
			$msg = JText::_( 'COM_RSAPPT_ERROR_SAVING' ).": ".$model->getError();
			logit($model->getError(), $model->getName()); 
		}
		
		$session = &JSession::getInstance($handler, $options);
		$session->set("current_tab", $current_tab);
		
		$model->checkin();
		$this->setRedirect( 'index.php?option=com_rsappt_pro2&view='.$frompage.'&Itemid='.$frompage_item );
		
	}	

	function unpublish_resource()
	{
		$post	= JRequest::get('post');
		$cid	= JRequest::getVar( 'cid_res', array(0), 'post', 'array' );
		$post['id_resources'] = $cid[0];
		$frompage = JRequest::getVar('frompage');
		$frompage_item = JRequest::getVar('frompage_item');
		$current_tab = JRequest::getVar('current_tab');

		require_once(JPATH_COMPONENT.DS.'models'.DS.'resources_detail.php');
		$model = new admin_detailModelresources_detail;
 		if($model == null){
			echo "model = null";
			exit;
		}
	
		if (!$model->publish($cid,0)) {
			$msg = JText::_( 'COM_RSAPPT_ERROR_SAVING' ).": ".$model->getError();
			logit($model->getError(), $model->getName()); 
		}

		$model->checkin();

		$session = &JSession::getInstance($handler, $options);
		$session->set("current_tab", $current_tab);
		
		$this->setRedirect( 'index.php?option=com_rsappt_pro2&view='.$frompage.'&Itemid='.$frompage_item );
	}	

	function remove_resource()
	{
		$post	= JRequest::get('post');
		$cid	= JRequest::getVar( 'cid_res', array(0), 'post', 'array' );
		$post['id_resources'] = $cid[0];
		$frompage = JRequest::getVar('frompage');
		$frompage_item = JRequest::getVar('frompage_item');
		$current_tab = JRequest::getVar('current_tab');

		if (!is_array( $cid ) || count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'Select an item to delete' ) );
		}

		require_once(JPATH_COMPONENT.DS.'models'.DS.'resources_detail.php');
		$model = new admin_detailModelresources_detail;
 		if($model == null){
			echo "model = null";
			exit;
		}

		if(!$model->delete($cid)) {
			echo "<script> alert('".$model->getError()."'); window.history.go(-1); </script>\n";
		} else {

			$session = &JSession::getInstance($handler, $options);
			$session->set("current_tab", $current_tab);			
			$this->setRedirect( 'index.php?option=com_rsappt_pro2&view='.$frompage.'&Itemid='.$frompage_item );
		}
	}


	function copy_resource()
	{
		$post	= JRequest::get('post');
		$cid	= JRequest::getVar( 'cid_res', array(0), 'post', 'array' );
		$frompage = JRequest::getVar('frompage');
		$frompage_item = JRequest::getVar('frompage_item');
		$current_tab = JRequest::getVar('current_tab');

		if (!is_array( $cid ) || count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'Select an item to copy' ) );
		}
		
		$database = &JFactory::getDBO();
		// first get source rows
		$ids = implode( ',', $cid );
		$query = 'SELECT * FROM #__sv_apptpro2_resources '
			. ' WHERE id_resources IN ( '.$ids.' )';
		$database->setQuery( $query );
		$rows = $database -> loadObjectList();
		if (!$database->query()) {
			echo $database -> getErrorMsg();
			return false;
		}
		//echo $query."<br>";
		//now do inserts
		foreach($rows as $row) {
			$sql = "INSERT INTO #__sv_apptpro2_resources (".
				"category_id,name,description,cost,ordering,resource_email,prevent_dupe_bookings,max_dupes,resource_admins,rate,rate_unit,".
				"allowSunday,allowMonday,allowTuesday,allowWednesday,allowThursday,allowFriday,allowSaturday,timeslots,disable_dates_before,".
				"disable_dates_before_days,min_lead_time,disable_dates_after,disable_dates_after_days,published,default_calendar_category,default_calendar,".
				"sms_phone,google_user,google_password,google_default_calendar_name,access,enable_coupons,max_seats,non_work_day_message)".
			" VALUES(".
				$row->category_id.",'".
				$row->name."','".
				$row->description."','".
				$row->cost."',".
				$row->ordering.",'".
				$row->resource_email."','".
				$row->prevent_dupe_bookings."',".
				$row->max_dupes.",'".
				$row->resource_admins."','".
				$row->rate."','".
				$row->rate_unit."','".
				$row->allowSunday."','".
				$row->allowMonday."','".
				$row->allowTuesday."','".
				$row->allowWednesday."','".
				$row->allowThursday."','".
				$row->allowFriday."','".
				$row->allowSaturday."','".
				$row->timeslots."','".
				$row->disable_dates_before."',".
				$row->disable_dates_before_days.",".
				$row->min_lead_time.",'".
				$row->disable_dates_after."',".
				$row->disable_dates_after_days.",".
				$row->published.",'".
				$row->default_calendar_category."','".
				$row->default_calendar."','".
				$row->sms_phone."','".
				$row->google_user."','".
				$row->google_password."','".
				$row->google_default_calendar_name."','".
				$row->access."','".
				$row->enable_coupons."','".
				$row->max_seats."','".
				$row->non_work_day_message."'".
				")";
			//echo $sql."<br>";
			$database->setQuery( $sql );
			if (!$database->query()) {
				echo $database -> getErrorMsg();
				return false;
				}
			}
		
			$session = &JSession::getInstance($handler, $options);
			$session->set("current_tab", $current_tab);			
			$this->setRedirect( 'index.php?option=com_rsappt_pro2&view='.$frompage.'&Itemid='.$frompage_item, JText::_( 'RS1_RESOURCE_COPY_OK' ) );
	}


	function publish_service()
	{
		$post	= JRequest::get('post');
		$cid	= JRequest::getVar( 'cid_srv', array(0), 'post', 'array' );
		$post['id_services'] = $cid[0];
		$frompage = JRequest::getVar('frompage');
		$frompage_item = JRequest::getVar('frompage_item');
		$current_tab = JRequest::getVar('current_tab');

		require_once(JPATH_COMPONENT.DS.'models'.DS.'services_detail.php');
		$model = new admin_detailModelservices_detail;
 		if($model == null){
			echo "model = null";
			exit;
		}
	
		if (!$model->publish($cid)) {
			$msg = JText::_( 'COM_RSAPPT_ERROR_SAVING' ).": ".$model->getError();
			logit($model->getError(), $model->getName()); 
		}
		
		$session = &JSession::getInstance($handler, $options);
		$session->set("current_tab", $current_tab);
		
		$model->checkin();
		$this->setRedirect( 'index.php?option=com_rsappt_pro2&view='.$frompage.'&Itemid='.$frompage_item );
		
	}	

	function unpublish_service()
	{
		$post	= JRequest::get('post');
		$cid	= JRequest::getVar( 'cid_srv', array(0), 'post', 'array' );
		$post['id_services'] = $cid[0];
		$frompage = JRequest::getVar('frompage');
		$frompage_item = JRequest::getVar('frompage_item');
		$current_tab = JRequest::getVar('current_tab');

		require_once(JPATH_COMPONENT.DS.'models'.DS.'services_detail.php');
		$model = new admin_detailModelservices_detail;
 		if($model == null){
			echo "model = null";
			exit;
		}
	
		if (!$model->publish($cid,0)) {
			$msg = JText::_( 'COM_RSAPPT_ERROR_SAVING' ).": ".$model->getError();
			logit($model->getError(), $model->getName()); 
		}

		$model->checkin();

		$session = &JSession::getInstance($handler, $options);
		$session->set("current_tab", $current_tab);
		
		$this->setRedirect( 'index.php?option=com_rsappt_pro2&view='.$frompage.'&Itemid='.$frompage_item );
	}	

	function remove_service()
	{
		$post	= JRequest::get('post');
		$cid	= JRequest::getVar( 'cid_srv', array(0), 'post', 'array' );
		$post['id_services'] = $cid[0];
		$frompage = JRequest::getVar('frompage');
		$frompage_item = JRequest::getVar('frompage_item');
		$current_tab = JRequest::getVar('current_tab');

		if (!is_array( $cid ) || count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'Select an item to delete' ) );
		}

		require_once(JPATH_COMPONENT.DS.'models'.DS.'services_detail.php');
		$model = new admin_detailModelservices_detail;
 		if($model == null){
			echo "model = null";
			exit;
		}

		if(!$model->delete($cid)) {
			echo "<script> alert('".$model->getError()."'); window.history.go(-1); </script>\n";
		} else {

			$session = &JSession::getInstance($handler, $options);
			$session->set("current_tab", $current_tab);			
			$this->setRedirect( 'index.php?option=com_rsappt_pro2&view='.$frompage.'&Itemid='.$frompage_item );
		}
	}

	function copy_services(){

		$cid	= JRequest::getVar( 'cid_srv', array(0), 'post', 'array' );
		
		$frompage = JRequest::getVar( 'frompage', '' );
		$fromtab = JRequest::getVar( 'current_tab', '' );
		JRequest::setVar( 'view', 'services_copy' );
		JRequest::setVar( 'hidemainmenu', 1);
		JRequest::setVar( 'services_tocopy', implode(',', $cid));
		JRequest::setVar( 'frompage', $frompage);
		JRequest::setVar( 'fromtab', $fromtab);
		JRequest::setVar( 'Itemid', JRequest::getVar( 'Itemid'));

		parent::display();

	}

	function do_copy_services(){

		$frompage = JRequest::getVar('frompage');
		$frompage_item = JRequest::getVar('frompage_item');
		$fromtab = JRequest::getVar('fromtab');

		$cids = JRequest::getVar( 'services_tocopy' );
		$dest_ids = JRequest::getVar('dest_resource_id');
		
		$database = &JFactory::getDBO();
		// first get source rows
		//$cids = implode( ',', $cid );
		$query = 'SELECT * FROM #__sv_apptpro2_services '
			. ' WHERE id_services IN ( '.$cids.' )';
		$database->setQuery( $query );
		$rows = $database -> loadObjectList();
		if (!$database->query()) {
			echo $database -> getErrorMsg();
			return false;
		}
		//echo $query."<br>";

		//now do inserts
		$msg = "";
		foreach($rows as $row) {
			for($x=0; $x<count($dest_ids); $x++){
				$sql = "INSERT INTO #__sv_apptpro2_services (resource_id,description,name,service_duration,service_duration_unit,service_rate,service_rate_unit,ordering,published)".
				" VALUES(".$dest_ids[$x].",'".$row->description."','".$row->name."',".
				$row->service_duration.",'".
				$row->service_duration_unit."',".
				$row->service_rate.",'".
				$row->service_rate_unit."',".
				$row->ordering.",".
				$row->published.")";
				//echo $sql."<br>";
				$database->setQuery( $sql );
				if (!$database->query()) {
					$msg = $database -> getErrorMsg();
					break;
				}
			}
		}		
		if($msg == ""){
			$msg = JText::_('RS1_SERVICE_COPY_OK');
		}
	
		$this->setRedirect( 'index.php?option=com_rsappt_pro2&view='.$frompage.'&Itemid='.$frompage_item.'&current_tab='.$fromtab, $msg );

	}

	function publish_timeslot()
	{
		$post	= JRequest::get('post');
		$cid	= JRequest::getVar( 'cid_ts', array(0), 'post', 'array' );
		$post['id_timeslots'] = $cid[0];
		$frompage = JRequest::getVar('frompage');
		$frompage_item = JRequest::getVar('frompage_item');
		$current_tab = JRequest::getVar('current_tab');

		require_once(JPATH_COMPONENT.DS.'models'.DS.'timeslots_detail.php');
		$model = new admin_detailModeltimeslots_detail;
 		if($model == null){
			echo "model = null";
			exit;
		}
	
		if (!$model->publish($cid)) {
			$msg = JText::_( 'COM_RSAPPT_ERROR_SAVING' ).": ".$model->getError();
			logit($model->getError(), $model->getName()); 
		}
		
		$session = &JSession::getInstance($handler, $options);
		$session->set("current_tab", $current_tab);
		
		$model->checkin();
		$this->setRedirect( 'index.php?option=com_rsappt_pro2&view='.$frompage.'&Itemid='.$frompage_item );
		
	}	

	function unpublish_timeslot()
	{
		$post	= JRequest::get('post');
		$cid	= JRequest::getVar( 'cid_ts', array(0), 'post', 'array' );
		$post['id_timeslots'] = $cid[0];
		$frompage = JRequest::getVar('frompage');
		$frompage_item = JRequest::getVar('frompage_item');
		$current_tab = JRequest::getVar('current_tab');

		require_once(JPATH_COMPONENT.DS.'models'.DS.'timeslots_detail.php');
		$model = new admin_detailModeltimeslots_detail;
 		if($model == null){
			echo "model = null";
			exit;
		}
	
		if (!$model->publish($cid,0)) {
			$msg = JText::_( 'COM_RSAPPT_ERROR_SAVING' ).": ".$model->getError();
			logit($model->getError(), $model->getName()); 
		}

		$model->checkin();

		$session = &JSession::getInstance($handler, $options);
		$session->set("current_tab", $current_tab);
		
		$this->setRedirect( 'index.php?option=com_rsappt_pro2&view='.$frompage.'&Itemid='.$frompage_item );
	}	


	function remove_timeslot()
	{
		$post	= JRequest::get('post');
		$cid	= JRequest::getVar( 'cid_ts', array(0), 'post', 'array' );
		$post['id_timeslots'] = $cid[0];
		$frompage = JRequest::getVar('frompage');
		$frompage_item = JRequest::getVar('frompage_item');
		$current_tab = JRequest::getVar('current_tab');

		if (!is_array( $cid ) || count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'Select an item to delete' ) );
		}

		require_once(JPATH_COMPONENT.DS.'models'.DS.'timeslots_detail.php');
		$model = new admin_detailModeltimeslots_detail;
 		if($model == null){
			echo "model = null";
			exit;
		}

		if(!$model->delete($cid)) {
			echo "<script> alert('".$model->getError()."'); window.history.go(-1); </script>\n";
		} else {

			$session = &JSession::getInstance($handler, $options);
			$session->set("current_tab", $current_tab);			
			$this->setRedirect( 'index.php?option=com_rsappt_pro2&view='.$frompage.'&Itemid='.$frompage_item );
		}
	}

	function do_global_import_timeslots()
	{
		$post	= JRequest::get('post');
		$cid	= JRequest::getVar( 'cid_ts', array(0), 'post', 'array' );
		$post['id_timeslots'] = $cid[0];
		$frompage = JRequest::getVar('frompage');
		$frompage_item = JRequest::getVar('frompage_item');
		$current_tab = JRequest::getVar('current_tab');
		$new_resource_id = JRequest::getVar('timeslots_resourceFilter');

		if (!is_array( $cid ) || count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'Select an item to delete' ) );
		}

		$database = &JFactory::getDBO();
		$query = "SELECT * FROM #__sv_apptpro2_timeslots "
			." WHERE ISNULL(resource_id) OR resource_id = ''";
		$database->setQuery( $query );
		$rows = $database -> loadObjectList();
		if (!$database->query()) {
			echo $database -> getErrorMsg();
			logIt($database->getErrorMsg()); 
			return false;
		}
		//echo $query."<br>";
		//exit;
		
		//now do inserts
		foreach($rows as $row) {
			$sql = "INSERT INTO #__sv_apptpro2_timeslots (day_number,resource_id,timeslot_starttime,timeslot_endtime,published)".
			" VALUES(".$row->day_number.",".$new_resource_id.",'".$row->timeslot_starttime."','".$row->timeslot_endtime."',".$row->published.")";
			//echo $sql."<br>";
			$database->setQuery( $sql );
			if (!$database->query()) {
				echo $database -> getErrorMsg();
				return false;
			}
		}		
		$this->setRedirect( 'index.php?option=com_rsappt_pro2&view='.$frompage.'&Itemid='.$frompage_item );

	}

	function copy_timeslots(){

		$cid	= JRequest::getVar( 'cid_ts', array(0), 'post', 'array' );
		
		$frompage = JRequest::getVar( 'frompage', '' );
		$fromtab = JRequest::getVar( 'current_tab', '' );
		JRequest::setVar( 'view', 'timeslots_copy' );
		JRequest::setVar( 'hidemainmenu', 1);
		JRequest::setVar( 'timeslots_tocopy', implode(',', $cid));
		JRequest::setVar( 'frompage', $frompage);
		JRequest::setVar( 'fromtab', $fromtab);
		JRequest::setVar( 'Itemid', JRequest::getVar( 'Itemid'));

		parent::display();

	}

	function do_copy_timeslots(){
		$post	= JRequest::get('post');
		$cids = JRequest::getVar( 'timeslots_tocopy' );
		$frompage = JRequest::getVar('frompage');
		$frompage_item = JRequest::getVar('frompage_item');
		$fromtab = JRequest::getVar('fromtab');
		$new_resource_id = JRequest::getVar('dest_resource_id');

		$database = &JFactory::getDBO();
		// first get source rows
		$query = 'SELECT * FROM #__sv_apptpro2_timeslots '
			. ' WHERE id_timeslots IN ( '.$cids.' )';
		$database->setQuery( $query );
		$rows = $database -> loadObjectList();
		if (!$database->query()) {
			echo $database -> getErrorMsg();
			return false;
		}
		//echo $query."<br>";

		// make an array of days
		$i = 0;
		$daylist[] = null;
		if(JRequest::getVar('chkSunday') == "on"){
			$daylist[$i] = 0;
			$i++;  
		}
		if(JRequest::getVar('chkMonday') == "on"){
			$daylist[$i] = 1;
			$i++;  
		}
		if(JRequest::getVar('chkTuesday') == "on"){
			$daylist[$i] = 2;
			$i++;  
		}
		if(JRequest::getVar('chkWednesday') == "on"){
			$daylist[$i] = 3;
			$i++;  
		}
		if(JRequest::getVar('chkThursday') == "on"){
			$daylist[$i] = 4;
			$i++;  
		}
		if(JRequest::getVar('chkFriday') == "on"){
			$daylist[$i] = 5;
			$i++;  
		}
		if(JRequest::getVar('chkSaturday') == "on"){
			$daylist[$i] = 6;
			$i++;  
		}
		if($i==0){
			// no days selected 
			echo "<script> alert('No Days Selected'); window.history.go(-1);</script>\n";
			exit();
		}

		//now do inserts
		foreach($rows as $row) {
			for($x=0; $x<$i; $x++){
				if(JRequest::getVar("new_start_publishing", "") != ""){
					$start_pub = JRequest::getVar("new_start_publishing", "");
				} else {
					$start_pub = $row->start_publishing;
				}
				if(JRequest::getVar("new_end_publishing", "") != ""){
					$end_pub = JRequest::getVar("new_end_publishing", "");
				} else {
					$end_pub = $row->end_publishing;
				}
				
				$sql = "INSERT INTO #__sv_apptpro2_timeslots (day_number,resource_id,timeslot_starttime,timeslot_endtime,timeslot_description,start_publishing,end_publishing,published)".
				" VALUES(".$daylist[$x].",".$new_resource_id.",'".$row->timeslot_starttime."','".$row->timeslot_endtime."','".$row->timeslot_description.
									"','".$start_pub."','".$end_pub."',".$row->published.")";
				//echo $sql."<br>";
				//exit;
				$database->setQuery( $sql );
				if (!$database->query()) {
					echo $database -> getErrorMsg();
					return false;
				}
			}
		}	
		$msg = JText::_( 'RS1_TIMESLOT_COPY_OK' );
		$this->setRedirect( 'index.php?option=com_rsappt_pro2&view='.$frompage.'&Itemid='.$frompage_item.'&current_tab='.$fromtab, $msg );

	}


	function publish_bookoff()
	{
		$post	= JRequest::get('post');
		$cid	= JRequest::getVar( 'cid_bo', array(0), 'post', 'array' );
		$post['id_bookoffs'] = $cid[0];
		$frompage = JRequest::getVar('frompage');
		$frompage_item = JRequest::getVar('frompage_item');
		$current_tab = JRequest::getVar('current_tab');

		require_once(JPATH_COMPONENT.DS.'models'.DS.'bookoffs_detail.php');
		$model = new admin_detailModelbookoffs_detail;
 		if($model == null){
			echo "model = null";
			exit;
		}
	
		if (!$model->publish($cid)) {
			$msg = JText::_( 'COM_RSAPPT_ERROR_SAVING' ).": ".$model->getError();
			logit($model->getError(), $model->getName()); 
		}
		
		$session = &JSession::getInstance($handler, $options);
		$session->set("current_tab", $current_tab);
		
		$model->checkin();
		$this->setRedirect( 'index.php?option=com_rsappt_pro2&view='.$frompage.'&Itemid='.$frompage_item );
		
	}	

	function unpublish_bookoff()
	{
		$post	= JRequest::get('post');
		$cid	= JRequest::getVar( 'cid_bo', array(0), 'post', 'array' );
		$post['id_bookoffs'] = $cid[0];
		$frompage = JRequest::getVar('frompage');
		$frompage_item = JRequest::getVar('frompage_item');
		$current_tab = JRequest::getVar('current_tab');

		require_once(JPATH_COMPONENT.DS.'models'.DS.'bookoffs_detail.php');
		$model = new admin_detailModelbookoffs_detail;
 		if($model == null){
			echo "model = null";
			exit;
		}
	
		if (!$model->publish($cid,0)) {
			$msg = JText::_( 'COM_RSAPPT_ERROR_SAVING' ).": ".$model->getError();
			logit($model->getError(), $model->getName()); 
		}

		$model->checkin();

		$session = &JSession::getInstance($handler, $options);
		$session->set("current_tab", $current_tab);
		
		$this->setRedirect( 'index.php?option=com_rsappt_pro2&view='.$frompage.'&Itemid='.$frompage_item );
	}	

	function remove_bookoff()
	{
		$post	= JRequest::get('post');
		$cid	= JRequest::getVar( 'cid_bo', array(0), 'post', 'array' );
		$post['id_bookoffs'] = $cid[0];
		$frompage = JRequest::getVar('frompage');
		$frompage_item = JRequest::getVar('frompage_item');
		$current_tab = JRequest::getVar('current_tab');

		if (!is_array( $cid ) || count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'Select an item to delete' ) );
		}

		require_once(JPATH_COMPONENT.DS.'models'.DS.'bookoffs_detail.php');
		$model = new admin_detailModelbookoffs_detail;
 		if($model == null){
			echo "model = null";
			exit;
		}

		if(!$model->delete($cid)) {
			echo "<script> alert('".$model->getError()."'); window.history.go(-1); </script>\n";
		} else {

			$session = &JSession::getInstance($handler, $options);
			$session->set("current_tab", $current_tab);			
			$this->setRedirect( 'index.php?option=com_rsappt_pro2&view='.$frompage.'&Itemid='.$frompage_item );
		}
	}

	function copy_bookoffs(){

		$cid	= JRequest::getVar( 'cid_bo', array(0), 'post', 'array' );
		
		$frompage = JRequest::getVar( 'frompage', '' );
		$fromtab = JRequest::getVar( 'current_tab', '' );
		JRequest::setVar( 'view', 'bookoffs_copy' );
		JRequest::setVar( 'hidemainmenu', 1);
		JRequest::setVar( 'bookoffs_tocopy', implode(',', $cid));
		JRequest::setVar( 'frompage', $frompage);
		JRequest::setVar( 'fromtab', $fromtab);
		JRequest::setVar( 'Itemid', JRequest::getVar( 'Itemid'));

		parent::display();

	}

	function do_copy_bookoffs(){
		$post	= JRequest::get('post');
		$cids = JRequest::getVar( 'bookoffs_tocopy' );
		$frompage = JRequest::getVar('frompage');
		$frompage_item = JRequest::getVar('frompage_item');
		$fromtab = JRequest::getVar('fromtab');
		$dest_ids = JRequest::getVar('dest_resource_id');

		$database = &JFactory::getDBO();
		// first get source rows
		$query = 'SELECT * FROM #__sv_apptpro2_bookoffs '
			. ' WHERE id_bookoffs IN ( '.$cids.' )';
		$database->setQuery( $query );
		$rows = $database -> loadObjectList();
		if (!$database->query()) {
			echo $database -> getErrorMsg();
			return false;
		}
		//echo $query."<br>";

		//now do inserts
		foreach($rows as $row) {
			for($x=0; $x<count($dest_ids); $x++){
				$sql = "INSERT INTO #__sv_apptpro2_bookoffs (resource_id,description,off_date,full_day,bookoff_starttime,bookoff_endtime,published)".
				" VALUES(".$dest_ids[$x].",'".$database->getEscaped($row->description)."','".$row->off_date."','".$row->full_day."','".$row->bookoff_starttime."','".$row->bookoff_endtime."',".$row->published.")";
				//echo $sql."<br>";
				$database->setQuery( $sql );
				if (!$database->query()) {
					echo $database -> getErrorMsg();
					return false;
				}
			}
		}		
		$msg = JText::_( 'RS1_BOOKOFF_COPY_OK' );
		$this->setRedirect( 'index.php?option=com_rsappt_pro2&view='.$frompage.'&Itemid='.$frompage_item.'&current_tab='.$fromtab, $msg );

	}

	function publish_coupon()
	{
		$post	= JRequest::get('post');
		$cid	= JRequest::getVar( 'cid_coup', array(0), 'post', 'array' );
		$post['id_coupons'] = $cid[0];
		$frompage = JRequest::getVar('frompage');
		$frompage_item = JRequest::getVar('frompage_item');
		$current_tab = JRequest::getVar('current_tab');

		require_once(JPATH_COMPONENT.DS.'models'.DS.'coupons_detail.php');
		$model = new admin_detailModelcoupons_detail;
 		if($model == null){
			echo "model = null";
			exit;
		}
	
		if (!$model->publish($cid)) {
			$msg = JText::_( 'COM_RSAPPT_ERROR_SAVING' ).": ".$model->getError();
			logit($model->getError(), $model->getName()); 
		}
		
		$session = &JSession::getInstance($handler, $options);
		$session->set("current_tab", $current_tab);
		
		$model->checkin();
		$this->setRedirect( 'index.php?option=com_rsappt_pro2&view='.$frompage.'&Itemid='.$frompage_item );
		
	}	

	function unpublish_coupon()
	{
		$post	= JRequest::get('post');
		$cid	= JRequest::getVar( 'cid_coup', array(0), 'post', 'array' );
		$post['id_coupons'] = $cid[0];
		$frompage = JRequest::getVar('frompage');
		$frompage_item = JRequest::getVar('frompage_item');
		$current_tab = JRequest::getVar('current_tab');

		require_once(JPATH_COMPONENT.DS.'models'.DS.'coupons_detail.php');
		$model = new admin_detailModelcoupons_detail;
 		if($model == null){
			echo "model = null";
			exit;
		}
	
		if (!$model->publish($cid,0)) {
			$msg = JText::_( 'COM_RSAPPT_ERROR_SAVING' ).": ".$model->getError();
			logit($model->getError(), $model->getName()); 
		}

		$model->checkin();

		$session = &JSession::getInstance($handler, $options);
		$session->set("current_tab", $current_tab);
		
		$this->setRedirect( 'index.php?option=com_rsappt_pro2&view='.$frompage.'&Itemid='.$frompage_item );
	}	

	function remove_coupon()
	{
		$post	= JRequest::get('post');
		$cid	= JRequest::getVar( 'cid_coup', array(0), 'post', 'array' );
		$post['id_coupons'] = $cid[0];
		$frompage = JRequest::getVar('frompage');
		$frompage_item = JRequest::getVar('frompage_item');
		$current_tab = JRequest::getVar('current_tab');

		if (!is_array( $cid ) || count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'Select an item to delete' ) );
		}

		require_once(JPATH_COMPONENT.DS.'models'.DS.'coupons_detail.php');
		$model = new admin_detailModelcoupons_detail;
 		if($model == null){
			echo "model = null";
			exit;
		}

		if(!$model->delete($cid)) {
			echo "<script> alert('".$model->getError()."'); window.history.go(-1); </script>\n";
		} else {

			$session = &JSession::getInstance($handler, $options);
			$session->set("current_tab", $current_tab);			
			$this->setRedirect( 'index.php?option=com_rsappt_pro2&view='.$frompage.'&Itemid='.$frompage_item );
		}
	}

	function publish_extra()
	{
		$post	= JRequest::get('post');
		$cid	= JRequest::getVar( 'cid_ext', array(0), 'post', 'array' );
		$post['id_extras'] = $cid[0];
		$frompage = JRequest::getVar('frompage');
		$frompage_item = JRequest::getVar('frompage_item');
		$current_tab = JRequest::getVar('current_tab');

		require_once(JPATH_COMPONENT.DS.'models'.DS.'extras_detail.php');
		$model = new admin_detailModelextras_detail;
 		if($model == null){
			echo "model = null";
			exit;
		}
	
		if (!$model->publish($cid)) {
			$msg = JText::_( 'COM_RSAPPT_ERROR_SAVING' ).": ".$model->getError();
			logit($model->getError(), $model->getName()); 
		}
		
		$session = &JSession::getInstance($handler, $options);
		$session->set("current_tab", $current_tab);
		
		$model->checkin();
		$this->setRedirect( 'index.php?option=com_rsappt_pro2&view='.$frompage.'&Itemid='.$frompage_item );
		
	}	

	function unpublish_extra()
	{
		$post	= JRequest::get('post');
		$cid	= JRequest::getVar( 'cid_ext', array(0), 'post', 'array' );
		$post['id_extras'] = $cid[0];
		$frompage = JRequest::getVar('frompage');
		$frompage_item = JRequest::getVar('frompage_item');
		$current_tab = JRequest::getVar('current_tab');

		require_once(JPATH_COMPONENT.DS.'models'.DS.'extras_detail.php');
		$model = new admin_detailModelextras_detail;
 		if($model == null){
			echo "model = null";
			exit;
		}
	
		if (!$model->publish($cid,0)) {
			$msg = JText::_( 'COM_RSAPPT_ERROR_SAVING' ).": ".$model->getError();
			logit($model->getError(), $model->getName()); 
		}

		$model->checkin();

		$session = &JSession::getInstance($handler, $options);
		$session->set("current_tab", $current_tab);
		
		$this->setRedirect( 'index.php?option=com_rsappt_pro2&view='.$frompage.'&Itemid='.$frompage_item );
	}	

	function remove_extra()
	{
		$post	= JRequest::get('post');
		$cid	= JRequest::getVar( 'cid_ext', array(0), 'post', 'array' );
		$post['id_extras'] = $cid[0];
		$frompage = JRequest::getVar('frompage');
		$frompage_item = JRequest::getVar('frompage_item');
		$current_tab = JRequest::getVar('current_tab');

		if (!is_array( $cid ) || count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'Select an item to delete' ) );
		}

		require_once(JPATH_COMPONENT.DS.'models'.DS.'extras_detail.php');
		$model = new admin_detailModelextras_detail;
 		if($model == null){
			echo "model = null";
			exit;
		}

		if(!$model->delete($cid)) {
			echo "<script> alert('".$model->getError()."'); window.history.go(-1); </script>\n";
		} else {

			$session = &JSession::getInstance($handler, $options);
			$session->set("current_tab", $current_tab);			
			$this->setRedirect( 'index.php?option=com_rsappt_pro2&view='.$frompage.'&Itemid='.$frompage_item );
		}
	}

	function send_reminders($sms="No"){
		$frompage = JRequest::getVar('frompage');
		$frompage_item = JRequest::getVar('frompage_item');
		$fromtab = JRequest::getVar('fromtab');

		$cid	= JRequest::getVar( 'cid_req', array(0), 'post', 'array' );
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
		JRequest::setVar( 'frompage', $frompage);
		JRequest::setVar( 'fromtab', $fromtab);
		JRequest::setVar( 'Itemid', JRequest::getVar( 'Itemid'));

		parent::display();
		
	}

	function send_sms_reminders(){
		$this->send_reminders("Yes");
	}

	function ipn(){
		include_once(JPATH_SITE.'/components/com_rsappt_pro2/rsappt_pro2_ipn.php');
	}
}
?>

