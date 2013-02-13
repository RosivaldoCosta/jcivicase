<?php
/**
 * JEvents Component for Joomla 1.5.x
 * 
 * This file based on Joomla config component Copyright (C) 2005 - 2008 Open Source Matters.
 *
 * @version     $Id: categoryController.php 1117 2008-07-06 17:20:59Z tstahl $
 * @package     JEvents
 * @copyright   Copyright (C) 2006-2008 JEvents Project Group
 * @license     GNU/GPLv2, see http://www.gnu.org/licenses/gpl-2.0.html
 * @link        http://joomlacode.org/gf/project/jevents
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport( 'joomla.application.component.model' );

/**
 * @package		Joomla
 * @subpackage	Config
 */
class AdminParamsModelParams extends JModel
{
	/**
	 * Get the params for the configuration variables
	 */
	function &getParams()
	{
		static $instance;

		if ($instance == null)
		{
			$component	= JEV_COM_COMPONENT;

			$table =& JTable::getInstance('component');
			$table->loadByOption( $component );

			// work out file path
			if ($path = JRequest::getString( 'path' )) {
				$path = JPath::clean( JPATH_SITE.DS.$path );
				JPath::check( $path );
			} else {
				$option	= preg_replace( '#\W#', '', $table->option );
				$path	= JPATH_ADMINISTRATOR.DS.'components'.DS.$option.DS.'config.xml';
			}

			if (file_exists( $path )) {
				$instance = new JParameter( $table->params, $path );
			} else {
				$instance = new JParameter( $table->params );
			}
		}
		return $instance;
	}
}