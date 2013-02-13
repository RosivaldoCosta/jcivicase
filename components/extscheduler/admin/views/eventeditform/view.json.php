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


class calendarViewEventEditForm extends JView
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

		$status = array('Rescheduled' => '1',
				'Cancelled' => '3',
				'Kept' => '4',
				'No Show' => '5',
				'Scheduled' => '9');

		$this->assignRef('status',$status);
		parent::display($tpl);
	}
}

?>
