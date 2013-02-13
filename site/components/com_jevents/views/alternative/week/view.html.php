<?php
/**
 * copyright (C) 2008 GWE Systems Ltd - All rights reserved
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

/**
 * HTML View class for the component frontend
 *
 * @static
 */
class AlternativeWeek extends JEventsAlternativeView 
{
	function listevents($tpl = null)
	{
		JHTML::stylesheet( 'events_css.css', 'components/'.JEV_COM_COMPONENT."/views/".$this->jevlayout."/assets/css/" );
		$document =& JFactory::getDocument();						
		$params = JComponentHelper::getParams(JEV_COM_COMPONENT);

	}	

}