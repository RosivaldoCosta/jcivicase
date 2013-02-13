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
 
class calendarViewEventeditwindow extends JView
{
	/**
	 * Custom Constructor
	 */
	function __construct( $config = array())
	{
 	 	parent::__construct( $config );
	}
 

   
	function display($tpl = null)
	{
		$this->assign('note_activity_type',"108");

    		parent::display($tpl);
  	}
}

?>
