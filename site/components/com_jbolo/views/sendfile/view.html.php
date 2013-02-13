<?php

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

class JboloViewSendfile extends JView
{
	function display($tpl = null)
	{
		$user =& JFactory::getUser();
		if($user->id) 
		{ 	parent::display($tpl);}
	}
}
