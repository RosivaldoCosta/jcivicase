<?php
/*
 Copyright (c) 2011-2012 Campbell Consulting Studios, LLC
 ExtScheduler v1.0
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view' );

/**
 [controller]View[controller]
 */


class calendarViewCalendar extends JView
{
	/**
	 * Custom Constructor
	 */
	function __construct( $config = array())
	{
		parent::__construct( $config );
	}





	function get_list()
	{
		
	}
	
	
	
	function display($tpl = null)
	{

		$d = JFactory::getDocument();
		$d->setMimeEncoding('text/javascript');

		$contactUrl = JURI::base()."index.php?option=com_civicrm&task=civicrm/ajax/contacts/&context=scheduler";
		$propUrl = JURI::base()."index.php?option=com_civicrm&task=civicrm/ajax/casedetails";
		$crudUrl = JURI::base()."index.php?option=com_extscheduler&view=eventslist&format=json";
		

		$this->assignRef('contactUrl',$contactUrl);
		$this->assignRef('propUrl',$propUrl);
		$this->assignRef('crudUrl',$crudUrl);

		parent::display($tpl);
	}
}

?>
