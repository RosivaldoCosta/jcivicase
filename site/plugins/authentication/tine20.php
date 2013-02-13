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
ini_set('display_errors','On');
error_reporting(E_ALL);


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

class plgAuthenticationTine20 extends JPlugin
{
	/**
	 * Constructor
	 *
	 * For php4 compatability we must not use the __constructor as a constructor for plugins
	 * because func_get_args ( void ) returns a copy of all passed arguments NOT references.
	 * This causes problems with cross-referencing necessary for the observer design pattern.
	 *
	 * @param 	object $subject The object to observe
	 * @param 	array  $config  An array that holds the plugin configuration
	 * @since 1.5
	 */
	function plgAuthenticationTine20(& $subject, $config)
	{
		parent::__construct($subject, $config);
	}

	/**
	 * This method should handle any authentication and report back to the subject
	 *
	 * @access	public
	 * @param   array 	$credentials Array holding the user credentials
	 * @param 	array   $options     Array of extra options
	 * @param	object	$response	Authentication response object
	 * @return	object	boolean
	 * @since 1.5
	 */
	function onAuthenticate( $user, $options )
	{
		return true;
	}
	function onLoginUser( $user, $options )
	{


		$username=$user["username"];
		$password=$user["password"];
		
		$_SESSION["tinelogin"]=1;
		$_SESSION["tinelogin_login"]=$username;
		$_SESSION["tinelogin_pass"]=$password;
		
		return true;
	}


	
}
