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
class AdminIcaleventViewIcalevent extends JEventsAbstractView
{
	function overview($tpl = null)
	{

		JHTML::stylesheet( 'eventsadmin.css', 'administrator/components/'.JEV_COM_COMPONENT.'/assets/css/' );

		$document =& JFactory::getDocument();
		$document->setTitle(JText::_('ICal Events'));

		// Set toolbar items for the page
		JToolBarHelper::title( JText::_( 'ICal Events' ), 'jevents' );

		JToolBarHelper::publishList('icalevent.publish');
		JToolBarHelper::unpublishList('icalevent.unpublish');
		JToolBarHelper::addNew('icalevent.edit');
		JToolBarHelper::editList('icalevent.edit');
		JToolBarHelper::deleteList('Delete Event and all repeats?','icalevent.delete');
		JToolBarHelper::spacer();
		JToolBarHelper::custom( 'cpanel.cpanel', 'default.png', 'default.png', 'JEV_ADMIN_CPANEL', false );
		JToolBarHelper::help( 'screen.ical', true);

		JSubMenuHelper::addEntry(JText::_('Control Panel'), 'index.php?option='.JEV_COM_COMPONENT, true);

		$params = JComponentHelper::getParams(JEV_COM_COMPONENT);
		//$section = $params->getValue("section",0);

		JHTML::_('behavior.tooltip');
	}

	function edit($tpl = null)
	{
		$document =& JFactory::getDocument();		
		include(JEV_LIBS."editStrings.php");		
		$document->addScriptDeclaration($editStrings);
		
		JHTML::stylesheet( 'eventsadmin.css', 'administrator/components/'.JEV_COM_COMPONENT.'/assets/css/' );
		JHTML::script('editical.js','administrator/components/'.JEV_COM_COMPONENT.'/assets/js/');

		$document->setTitle(JText::_('Edit ICal Event'));

		// Set toolbar items for the page
		JToolBarHelper::title( JText::_( 'Edit ICal Event' ), 'jevents' );

		$bar = & JToolBar::getInstance('toolbar');
		if ($this->id>0){
			if ($this->editCopy){
				$bar->appendButton( 'Confirm', "save copy warning", 'save', "Save",'icalevent.save', false, false );
			}
			else {
				$bar->appendButton( 'Confirm', "save icalevent warning", 'save', "Save",'icalevent.save', false, false );
			}
		}
		else {
			JToolBarHelper::save('icalevent.save');		
		}

		JToolBarHelper::cancel('icalevent.list');
		JToolBarHelper::help( 'screen.icalevent.edit', true);

		$this->_hideSubmenu();

		$params = JComponentHelper::getParams(JEV_COM_COMPONENT);
		//$section = $params->getValue("section",0);

		JHTML::_('behavior.tooltip');
	}



}