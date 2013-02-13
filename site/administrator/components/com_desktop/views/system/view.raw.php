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
 
class desktopViewDesktop extends JView
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
		$d = JFactory::getDocument();
      		$d->setMimeEncoding('text/javascript');
		
    		parent::display($tpl);
  	}
}

?>