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
class AdminParamsViewParams extends JEventsAbstractView
{

	function edit()
	{
		JHTML::stylesheet( 'eventsadmin.css', 'administrator/components/'.JEV_COM_COMPONENT.'/assets/css/' );

		$document =& JFactory::getDocument();
		$document->setTitle(JText::_('Configuration'));
		
		// Set toolbar items for the page
		JToolBarHelper::title( JText::_( 'Configuration' ), 'jevents' );

		JToolBarHelper::save('params.save');
		JToolBarHelper::cancel('cpanel.cpanel');
		
		$model		= &$this->getModel();
		$this->params		= &$model->getParams();
		$component	= JComponentHelper::getComponent(JRequest::getCmd( 'component' ));

		JHTML::_('behavior.tooltip');
	}
	
	
}