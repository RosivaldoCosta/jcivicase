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
 
class resourcesController extends JController
{

	/**
	 * Custom Constructor
	 */
	function __construct( $default = array())
	{
		parent::__construct( $default );

		// Register Extra tasks
		$this->registerTask( 'copy', 'copy_resources' );
		
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
		rsappt_pro2Helper::addSubmenu('resources');
		
	}


	function copy_resources(){
		$id	= JRequest::getVar( 'cid', array(0), 'post', 'array' );

		if (count( $id )){
			$database = &JFactory::getDBO();
			$msg = "";
			// first get source rows
			$ids = implode( ',', $id );
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
					$msg = $database -> getErrorMsg();
				} else {
					$msg = JText::_('RS1_ADMIN_TOOLBAR_RESOURCE_COPY_OK');
				}
				
			}
			//exit;
			global $mainframe;
			if($option=="adv_admin"){
	//			$session = &JFactory::getSession();
	//			$session->set("current_tab", 1);
	//			$option = "com_rsappt_pro14";
	//			$mainframe->redirect(JURI::base() . "index.php?option=".$option."&page=adv_admin");
			} else {
				$this->setRedirect( 'index.php?option=com_rsappt_pro2&controller=resources', $msg );
			}
		}

	}
}	
?>

