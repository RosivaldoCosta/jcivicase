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
 
class requestsViewrequests extends JView
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
	 $context = 'requests.list.';
 
 	 parent::__construct( $config );
	}
 

	/**
	 * Display the view
	 * take data from MODEL and put them into	
	 * reference variables
	 * 
	 * Go to MODEL, execute Method getData and
	 * result save into reference variable $items	 	 	 
	 * $items		= & $this->get( 'Data');
	 * - getData gets the course list from DB	 
	 *	  
	 * variable filter_order specifies what is the order by column
	 * variable filter_order_Dir sepcifies if the ordering is [ascending,descending]	 	 	 	  
	 */
    
	function display($tpl = null)
	{
		$user = JFactory::getUser();
	 	//DEVNOTE: we need these 2 globals			 
		global $context;
	  	$mainframe = JFactory::getApplication();
		
		//DEVNOTE: set document title
		$document = & JFactory::getDocument();
		$document->setTitle( JText::_('Appointment Booking Pro - requests') );
   
   
   		//DEVNOTE: Set ToolBar title
	    JToolBarHelper::title(   JText::_( 'RS1_ADMIN_TOOLBAR_APPOINTMENTS' ), 'bookings' );
    
    	//DEVNOTE: Set toolbar items for the page
		if( $user->gid > 23 )
		{
			JToolBarHelper::deleteList( JText::_('RS1_ADMIN_TOOLBAR_APPOINTMENTS_DELETE_CONF'), 'remove', JText::_('RS1_ADMIN_TOOLBAR_APPOINTMENTS_DELETE') );
			JToolBarHelper::editListX('edit', JText::_('RS1_ADMIN_TOOLBAR_APPOINTMENTS_EDIT'));
		}
//		JToolBarHelper::customX( 'copy_booking', 'copy.png', 'copy_f2.png', JText::_('RS1_ADMIN_TOOLBAR_BOOKING_COPY'));
		JToolBarHelper::custom('reminders', 'default', '', JText::_('RS1_ADMIN_TOOLBAR_APPOINTMENTS_REM_EMAIL'));
		JToolBarHelper::custom('reminders_sms', 'default', '', JText::_('RS1_ADMIN_TOOLBAR_APPOINTMENTS_REM_SMS'));
		JToolBarHelper::custom('export', 'save', '', JText::_('RS1_ADMIN_TOOLBAR_APPOINTMENTS_EXPORT'));
		JToolBarHelper::custom('export_ics', 'save', '', JText::_('RS1_ADMIN_TOOLBAR_APPOINTMENTS_EXPORT_ICS'));
		JToolBarHelper::help('screen.rsappt_pro.requests', true);

	    //DEVNOTE: Set ToolBar title
		$uri	=& JFactory::getURI();
		
		//DEVNOTE:give me ordering from request
		$filter_order     = $mainframe->getUserStateFromRequest( $context.'filter_order',      'filter_order', 	  'id_requests' );
		$filter_order_Dir = $mainframe->getUserStateFromRequest( $context.'filter_order_Dir',  'filter_order_Dir', '' );		

		// get filters
		$filter_user_search	= $mainframe->getUserStateFromRequest( $context.'filter_user_search', 'user_search', "");
		$filter_startdate	= $mainframe->getUserStateFromRequest( $context.'filter_startdate', 'startdateFilter', date("Y-m-d"));
		$filter_enddate	= $mainframe->getUserStateFromRequest( $context.'filter_enddate', 'enddateFilter', "");
		$filter_category	= $mainframe->getUserStateFromRequest( $context.'filter_category', 'categoryFilter', "0");
		$filter_request_resource	= $mainframe->getUserStateFromRequest( $context.'filter_request_resource', 'request_resourceFilter', "0");
		$filter_request_status	= $mainframe->getUserStateFromRequest( $context.'filter_request_status', 'request_status', "all");

		//DEVNOTE:remember the actual order and column  
		$lists['order'] = $filter_order;  
		$lists['order_Dir'] = $filter_order_Dir;
  	
		//DEVNOTE:Get data from the model
		$items			= & $this->get('Data2');
		//print_r($items);
		$total			= & $this->get('Total');
		//print_r($total);
		$pagination = & $this->get( 'Pagination' );
		
		$filter_resource = & $this->get('filter_resource');
		$filter_day_number = & $this->get('filter_day_number');
				
		//DEVNOTE:save a reference into view	
		$this->assignRef('user',		JFactory::getUser());	
		$this->assignRef('lists',		$lists);    
		$this->assignRef('items',		$items); 		
		$this->assignRef('pagination',	$pagination);
		$this->assignRef('request_url',	$uri->toString());
		$this->assignRef('filter_user_search', $filter_user_search);
		$this->assignRef('filter_startdate', $filter_startdate);
		$this->assignRef('filter_enddate', $filter_enddate);
		$this->assignRef('filter_category', $filter_category);
		$this->assignRef('filter_request_resource', $filter_request_resource);
		$this->assignRef('filter_request_status', $filter_request_status);
	
		//DEVNOTE:call parent display
    parent::display($tpl);
  }
}

?>
