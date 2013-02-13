<?php
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');

class JboloViewconfig extends JView
{
	function display($tpl = null)
	{
		$this->_setToolBar();
		$app =& JFactory::getApplication();

		parent::display($tpl);
	}
	
	function _setToolBar()
	{
		// Get the toolbar object instance
		$bar =& JToolBar::getInstance('toolbar');
		JToolBarHelper::title( JText::_( 'MENU_TITLE1' ), 'jbolo.png' );
		JToolBarHelper::save();
	}
}
