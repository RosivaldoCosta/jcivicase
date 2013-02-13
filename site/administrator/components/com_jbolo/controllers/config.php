<?php
defined('_JEXEC') or die('Restricted access');
require_once( JPATH_COMPONENT.DS.'views'.DS.'config'.DS.'view.html.php' );

jimport('joomla.application.component.controller');

class JboloControllerConfig extends JboloController
{
	/**
	 * Saves a menu item
	 */

	function save()
	{ 
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$model	=& $this->getModel( 'config' );
		$post	= JRequest::get('post');
		// allow name only to contain html
		$model->setState( 'request', $post );
		
		if ($model->store()) {
			$msg = JText::_( 'C_SAVE_M_S' );
		} else {
			$msg = JText::_( 'C_SAVE_M_NS' );
		}
	
		switch ( $this->_task ) {
			case 'cancel':
				$this->setRedirect( JURI::base(), $msg );
				break;

			case 'save':
			default:
				$this->setRedirect( JURI::base()."index.php?option=com_jbolo&view=config", $msg );
				break;
		}
	}

}?>
