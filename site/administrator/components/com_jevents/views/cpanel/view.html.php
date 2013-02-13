<?php
/**
 * copyright (C) 2008 JEV Systems Ltd - All rights reserved
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

/**
 * HTML View class for the component
 *
 * @static
 */
class AdminCPanelViewCPanel extends JEventsAbstractView 
{
	/**
	 * Control Panel display function
	 *
	 * @param template $tpl
	 */
	function cpanel($tpl = null)
	{
		jimport('joomla.html.pane');
		
		JHTML::stylesheet( 'eventsadmin.css', 'administrator/components/'.JEV_COM_COMPONENT.'/assets/css/' );

		$document =& JFactory::getDocument();
		$document->setTitle(JText::_('JEvents') . ' :: ' .JText::_('Control Panel'));
		
		// Set toolbar items for the page
		//JToolBarHelper::preferences('com_jevents', '580', '750');
		JToolBarHelper::title( JText::_('JEvents') .' :: '. JText::_( 'Control Panel' ), 'jevents' );

		$this->_hideSubmenu();
		
		global $mainframe;
		if ($mainframe->isAdmin()){
			//JToolBarHelper::preferences(JEV_COM_COMPONENT, '580', '750');
		}
		JToolBarHelper::help( 'screen.cpanel', true);

		JSubMenuHelper::addEntry(JText::_('Control Panel'), 'index.php?option='.JEV_COM_COMPONENT, true);
		
		$params = JComponentHelper::getParams(JEV_COM_COMPONENT);
		//$section = $params->getValue("section",0);
		
		JHTML::_('behavior.tooltip');
	}	

	
}