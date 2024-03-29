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
class bookingscreensimpleVIEWsb_confirmation extends JView
{
	/**
	 * Display the view
	 */
	function display($tpl = null)
	{
	
	  	$mainframe = JFactory::getApplication();

		$uri 	=& JFactory::getURI();
		$user 	=& JFactory::getUser();


		$frompage = JRequest::getVar('frompage', '');
		$frompage_item = JRequest::getVar('Item_id', '');


		parent::display($tpl);
	}
	
}	

?>
