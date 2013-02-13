<?php
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');

class JboloViewcp extends JView
{
	function display($tpl = null)
	{
		$model =& $this->getModel();
		$this->assignRef( 'isplugin',	$model->_isPlugin() );
		$this->assignRef( 'ismodule',	$model->_isModule() );
		$this->assignRef( 'getJboloStats',	$model->_getJboloStats() );
	
		$this->_setToolBar();
		parent::display($tpl);
	}
	
	function _setToolBar()
	{
		// Get the toolbar object instance
		//$bar =& JToolBar::getInstance('toolbar');
		JToolBarHelper::title( JText::_( 'MENU_TITLE1' ), 'jbolo.png' );
		//JToolBarHelper::save();
	}

}

