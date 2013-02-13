<?php

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

class JboloViewJs extends JView
{
	function display($tpl = null)
	{

		$doc = & JFactory::getDocument();
		$doc->setMimeEncoding('text/javascript');
		
		$smileys = $this->get('Smileys');
		$this->assignRef('smileys', $smileys);
		
		require_once(JPATH_COMPONENT.DS."config".DS."config.php");
		$this->assignRef('configarray', $chat_config);

		parent::display($tpl);
		
	}
}
