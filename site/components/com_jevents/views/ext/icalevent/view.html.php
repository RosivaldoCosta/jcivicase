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
class ExtICalEvent extends JEventsExtView 
{
	
	function detail($tpl = null)
	{
		JHTML::stylesheet( 'events_css.css', 'components/'.JEV_COM_COMPONENT."/views/".$this->jevlayout."/assets/css/" );

		$document =& JFactory::getDocument();
		// TODO do this properly
		//$document->setTitle(JText::_("BROWSER TITLE"));
						
		$params = JComponentHelper::getParams(JEV_COM_COMPONENT);
		//$this->assign("introduction", $params->get("intro",""));

		$this->data = $this->datamodel->getEventData( $this->evid, $this->jevtype, $this->year, $this->month, $this->day );
		
	}	
}
