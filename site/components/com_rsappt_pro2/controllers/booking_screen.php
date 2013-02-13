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
 
class booking_screenController extends JController
{

	/**
	 * Custom Constructor
	 */
	function __construct( $default = array())
	{
		parent::__construct( $default );
		
		// Register Extra tasks	
		$this->registerTask( 'display_simple', 'go_display_simple' );
		$this->registerTask( 'display_gad', 'go_display_gad' );


	}


	function cancel()
	{
		$frompage = JRequest::getVar('frompage');
		$frompage_item = JRequest::getVar('frompage_item');
		
		$this->setRedirect( 'index.php?option=com_rsappt_pro2&view='.$frompage.'&Itemid='.$frompage_item );
	}	

	function go_display_simple()
	{
		$frompage = JRequest::getVar( 'frompage', '' );
		JRequest::setVar( 'view', 'booking_screen_simple' );
		JRequest::setVar( 'layout', 'default'  );
		JRequest::setVar( 'hidemainmenu', 1);
		JRequest::setVar( 'listpage', $frompage);
		JRequest::setVar( 'Itemid', JRequest::getVar( 'Itemid'));

		parent::display();

	}
}
?>

