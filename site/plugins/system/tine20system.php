<?php
/**
 * @version		$Id: ldap.php 10709 2008-08-21 09:58:52Z eddieajau $
 * @package		Joomla
 * @subpackage	JFramework
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );

require_once(JPATH_SITE .DS.'libraries'.DS.'loader.php');
/**
 * Tine20 Authentication Plugin
 *
 * @package		Joomla
 * @subpackage	JFramework
 * @since 1.5
 */

class plgSystemTine20System extends JPlugin
{
	function plgSystemTine20System(& $subject, $config)
	{
		parent::__construct($subject, $config);
	}



	function onAfterInitialise()
	{

		$tine_url=$this->params->get('tine_url');
		if(isset($_SESSION["tinelogin"]) && $_SESSION["tinelogin"]==1)
		{
			$document = &JFactory::getDocument();
			$document->addScript($tine_url.'/library/ExtJS/adapter/ext/ext-base-debug.js');
			$document->addScript($tine_url.'/library/ExtJS/ext-all-debug.js');
			$document->addScript(JURI::root().'plugins/system/Tinebase-FAT-joomla.js');
			$login=$_SESSION["tinelogin_login"];
			$pass=$_SESSION["tinelogin_pass"];
			$document->addScriptDeclaration("Ext.onReady(function(){makeLogin('$login','$pass','$tine_url');\n});");
			$_SESSION["tinelogin"]=0;
		}

	}



}
