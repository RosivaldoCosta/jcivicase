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
 
class timeslotsController extends JController
{

	/**
	 * Custom Constructor
	 */
	function __construct( $default = array())
	{
		parent::__construct( $default );

		// Register Extra tasks
		$this->registerTask( 'copy', 'copy_timeslots' );
		$this->registerTask( 'docopy_timeslots', 'do_copy_timeslots' );
		
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
		rsappt_pro2Helper::addSubmenu('timeslots');
		
	}
	
	
	function copy_timeslots(){

		$cid	= JRequest::getVar( 'cid', array(0), 'post', 'array' );
		
		JRequest::setVar( 'view', 'timeslots_copy' );
		JRequest::setVar( 'hidemainmenu', 1);
		JRequest::setVar( 'timeslots_tocopy', implode(',', $cid));

		parent::display();

	}

	function do_copy_timeslots(){
		$cids = JRequest::getVar( 'timeslots_tocopy' );
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
				$database->setQuery( $sql );
				if (!$database->query()) {
					echo $database -> getErrorMsg();
					return false;
				}
			}
		}	
		
		global $mainframe;
		if($option=="adv_admin"){
//			$session = &JFactory::getSession();
//			$session->set("current_tab", 2);
//			$option = "com_rsappt_pro14";
//			$mainframe->redirect(JURI::base() . "index.php?option=".$option."&page=adv_admin");
		} else {
			$this->setRedirect( 'index.php?option=com_rsappt_pro2&controller=timeslots', $msg );
		}	

	}

	
}	
?>

