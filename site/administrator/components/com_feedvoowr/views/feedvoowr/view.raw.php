<?php
/*
 Copyright (c) 2011-2012 Campbell Consulting Studios, LLC
 ExtScheduler v1.0
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view' );

class feedvoowrViewFeedvoowr extends JView
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
		$user = &JFactory::getUser();
		$d = &JFactory::getDocument();

		$this->assignRef('user',                $user);
                $this->assignRef('document',            $d);

    		parent::display($tpl);
  	}
}

?>
