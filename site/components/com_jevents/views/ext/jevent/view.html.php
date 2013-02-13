<?php
/**
 * copyright (C) 2008 GWE Systems Ltd - All rights reserved
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

// This view extends the icalevent view
include_once(dirname(__FILE__)."/../icalevent/view.html.php");

/**
 * HTML View class for the component frontend
 *
 * @static
 */
class DefaultJevent extends DefaultICalEvent 
{
	function __construct($config = null)
	{		
		parent::__construct($config);

		$this->addTemplatePath($this->_basePath.DS."views".DS.$this->jevlayout.DS."icalevent".DS.'tmpl');
	}
	
	function detail($tpl = null)
	{
		JHTML::stylesheet( 'events_css.css', 'components/'.JEV_COM_COMPONENT."/views/".$this->jevlayout."/assets/css/" );

		$document =& JFactory::getDocument();
		// TODO do this properly
		//$document->setTitle(JText::_("BROWSER TITLE"));
						
		$params = JComponentHelper::getParams(JEV_COM_COMPONENT);
		//$this->assign("introduction", $params->get("intro",""));
		

	}	
}
