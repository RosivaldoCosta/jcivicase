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



 Copyright (c) 2011-2012 Campbell Consulting Studios, LLC
 ExtScheduler v1.0
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view' );

/**
 [controller]View[controller]
 */
 
class frontdeskViewFrontDesk extends JView
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
	 $context = 'front_desk.';
 
 	 parent::__construct( $config );
	}
 

   
	function display($tpl = null)
	{
		global $context;
	  	$mainframe = JFactory::getApplication();
		
		$uri	=& JFactory::getURI();
		
		// get filters
		$front_desk_view	= $mainframe->getUserStateFromRequest( $context.'front_desk_view', 'front_desk_view', "month");
		$front_desk_resource_filter	= $mainframe->getUserStateFromRequest( $context.'front_desk_resource_filter', 'front_desk_resource_filter', '');
		$front_desk_status_filter	= $mainframe->getUserStateFromRequest( $context.'front_desk_status_filter', 'front_desk_status_filter', '');
		$front_desk_user_search	= $mainframe->getUserStateFromRequest( $context.'front_desk_user_search', 'front_desk_user_search', '');

		$front_desk_cur_week_offset = $mainframe->getUserState('front_desk_cur_week_offset');
		$front_desk_cur_day = $mainframe->getUserState('front_desk_cur_day');
		$front_desk_cur_month = $mainframe->getUserState('front_desk_cur_month');
		$front_desk_cur_year = $mainframe->getUserState('front_desk_cur_year');


		//DEVNOTE:save a reference into view	
		$this->assignRef('user',		JFactory::getUser());	
		$this->assignRef('request_url',	$uri->toString());

		$frompage  = 'front_desk';
		$this->assignRef('frompage',	$frompage);
		$this->assignRef('front_desk_view', $front_desk_view);
		$this->assignRef('front_desk_resource_filter', $front_desk_resource_filter);
		$this->assignRef('front_desk_status_filter', $front_desk_status_filter);
		$this->assignRef('front_desk_user_search', $front_desk_user_search);

		$this->assignRef('front_desk_cur_week_offset', $front_desk_cur_week_offset);
		$this->assignRef('front_desk_cur_day', $front_desk_cur_day);
		$this->assignRef('front_desk_cur_month', $front_desk_cur_month);
		$this->assignRef('front_desk_cur_year', $front_desk_cur_year);


    	parent::display($tpl);
  }
}

?>
