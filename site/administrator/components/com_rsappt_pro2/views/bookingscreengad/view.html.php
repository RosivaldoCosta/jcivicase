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

//DEVNOTE: import VIEW object class
jimport( 'joomla.application.component.view' );

/**
 [controller]View[controller]
 */

class bookingscreengadViewbookingscreengad extends JView
{
	/**
	 * Custom Constructor
	 */
	function __construct( $config = array())
	{
	 /** set up global variable for sorting etc.
	  * $context is used in VIEW abd in MODEL
	  **/	  
	 
 	 global $context;
	 $context = 'gad_booking_screen.';

 	 parent::__construct( $config );
	}



	function display($tpl = null)
	{
		global $context;
	  	$mainframe = JFactory::getApplication();

		$uri	=& JFactory::getURI();

		$this->assignRef('user',		JFactory::getUser());
		$this->assignRef('request_url',	$uri->toString());

		$frompage  = 'gad_booking_screen';
		$this->assignRef('frompage',	$frompage);


		//DEVNOTE:call parent display
		//print_r(debug_backtrace());exit(0);
    		parent::display($tpl);
  	}
}

?>
