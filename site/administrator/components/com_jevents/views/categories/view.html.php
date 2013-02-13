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
class AdminCategoriesViewCategories extends JEventsAbstractView 
{
	function overview($tpl = null)
	{
		
		JHTML::stylesheet( 'eventsadmin.css', 'administrator/components/'.JEV_COM_COMPONENT.'/assets/css/' );

		$document =& JFactory::getDocument();
		$document->setTitle(JText::_('Categories'));
		
		// Set toolbar items for the page
		JToolBarHelper::title( JText::_( 'Categories' ), 'jevents' );
	
		JToolBarHelper::publishList('categories.publish');
		JToolBarHelper::unpublishList('categories.unpublish');
		JToolBarHelper::addNew('categories.edit');
		JToolBarHelper::editList('categories.edit');
		JToolBarHelper::deleteList("delete category?",'categories.delete');
		JToolBarHelper::spacer();
		JToolBarHelper::custom( 'cpanel.cpanel', 'default.png', 'default.png', 'JEV_ADMIN_CPANEL', false );
		JToolBarHelper::help( 'screen.categories', true);

		JSubMenuHelper::addEntry(JText::_('Control Panel'), 'index.php?option='.JEV_COM_COMPONENT, true);
		
		$params = JComponentHelper::getParams(JEV_COM_COMPONENT);
		//$section = $params->getValue("section",0);
				
		JHTML::_('behavior.tooltip');
	}	


	function edit($tpl = null)
	{
		JRequest::setVar( 'hidemainmenu', 1 );
		
		JHTML::stylesheet( 'eventsadmin.css', 'administrator/components/'.JEV_COM_COMPONENT.'/assets/css/' );

		$document =& JFactory::getDocument();
		$document->setTitle(JText::_('Categories'));
		
		// Set toolbar items for the page
		JToolBarHelper::title( JText::_( 'Categories' ), 'jevents' );
	
		JToolBarHelper::save('categories.save');
		JToolBarHelper::cancel('categories.list');
		JToolBarHelper::help( 'screen.categories.edit', true);

		JSubMenuHelper::addEntry(JText::_('Control Panel'), 'index.php?option='.JEV_COM_COMPONENT, true);
		
		$params = JComponentHelper::getParams(JEV_COM_COMPONENT);
		//$section = $params->getValue("section",0);
		
		JHTML::_('behavior.tooltip');
	}	

}