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
 
class bookoffsController extends JController
{

	/**
	 * Custom Constructor
	 */
	function __construct( $default = array())
	{
		parent::__construct( $default );

		// Register Extra tasks
		$this->registerTask( 'copy', 'copy_bookoffs' );
		$this->registerTask( 'docopy_bookoff', 'do_copy_bookoffs' );
		
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
		rsappt_pro2Helper::addSubmenu('bookoffs');
		
	}
	
	
	function copy_bookoffs(){

		$cid	= JRequest::getVar( 'cid', array(0), 'post', 'array' );
		
		JRequest::setVar( 'view', 'bookoffs_copy' );
		JRequest::setVar( 'hidemainmenu', 1);
		JRequest::setVar( 'bookoffs_tocopy', implode(',', $cid));

		parent::display();

	}

	function do_copy_bookoffs(){

		$cids = JRequest::getVar( 'bookoffs_tocopy' );
		$dest_ids = JRequest::getVar('dest_resource_id');
		
		$database = &JFactory::getDBO();
		// first get source rows
		//$cids = implode( ',', $cid );
		$query = 'SELECT * FROM #__sv_apptpro2_bookoffs '
			. ' WHERE id_bookoffs IN ( '.$cids.' )';
		$database->setQuery( $query );
		$rows = $database -> loadObjectList();
		if (!$database->query()) {
			echo $database -> getErrorMsg();
			logit($database -> getErrorMsg(),"do_copy_bookoffs"); 
			return false;
		}
		//echo $query."<br>";

		//now do inserts
		$msg = "";
		foreach($rows as $row) {
			for($x=0; $x<count($dest_ids); $x++){
				$sql = "INSERT INTO #__sv_apptpro2_bookoffs (resource_id,description,off_date,full_day,bookoff_starttime,bookoff_endtime,published)".
				" VALUES(".$dest_ids[$x].",'".$database->getEscaped($row->description)."','".
				$row->off_date."','".
				$row->full_day."','".
				$row->bookoff_starttime."','".
				$row->bookoff_endtime."',".
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
			$msg = JText::_('RS1_ADMIN_TOOLBAR_BOOKOFF_COPY_OK');
		} else {
			logit($msg,"do_copy_bookoffs"); 
		}
	
		global $mainframe;
		if($option=="adv_admin"){
//			$session = &JFactory::getSession();
//			$session->set("current_tab", 2);
//			$option = "com_rsappt_pro14";
//			$mainframe->redirect(JURI::base() . "index.php?option=".$option."&page=adv_admin");
		} else {
			$this->setRedirect( 'index.php?option=com_rsappt_pro2&controller=bookoffs', $msg );
		}	

	}

	
}	
?>

