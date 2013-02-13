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
 
class mybookingsController extends JController
{

	/**
	 * Custom Constructor
	 */
	function __construct( $default = array())
	{
		parent::__construct( $default );
		
	}

	function list_bookings()
	{
		JRequest::setVar( 'view', 'mybookings' );
		JRequest::setVar( 'layout', 'default'  );
		JRequest::setVar( 'hidemainmenu', 0);


		parent::display();

	}
      

	
	/** function cancel
	*
	* Check in the selected detail 
	* and set Redirection to the list of items	
	* 		
	* @return set Redirection
	*/
	function cancel()
	{
		$this->setRedirect( 'index.php?option=com_rsappt_pro2&controller=mybookings',$msg );
	}	


}

?>

