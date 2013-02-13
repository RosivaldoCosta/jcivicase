<?php
/**
 * @version		$Id: example.php 11720 2009-03-27 21:27:42Z ian $
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

jimport('joomla.plugin.plugin');

// Prepare CiviCRM envirement
$civicrm_root_api = 'components/com_civicrm';
require_once $civicrm_root_api . '/civicrm.settings.php';
require_once $civicrm_root_api . '/civicrm/CRM/Core/Config.php';
$config = & CRM_Core_Config::singleton();
require_once $civicrm_root_api . '/civicrm/api/v2/Contact.php';
//civicrm_initialize( );

        

/**
 * Example User Plugin
 *
 * @package		Joomla
 * @subpackage	JFramework
 * @since 		1.5
 */
class plgUserCiviUser extends JPlugin {

	/**
	 * Constructor
	 *
	 * For php4 compatability we must not use the __constructor as a constructor for plugins
	 * because func_get_args ( void ) returns a copy of all passed arguments NOT references.
	 * This causes problems with cross-referencing necessary for the observer design pattern.
	 *
	 * @param object $subject The object to observe
	 * @param 	array  $config  An array that holds the plugin configuration
	 * @since 1.5
	 */
	function plgUserCiviUser(& $subject, $config)
	{
		parent::__construct($subject, $config);
	}

	/**
	 * Example store user method
	 *
	 * Method is called after user data is stored in the database
	 *
	 * @param 	array		holds the new user data
	 * @param 	boolean		true if a new user is stored
	 * @param	boolean		true if user was succesfully stored in the database
	 * @param	string		message
	 */
	function onAfterStoreUser($user, $isnew, $success, $msg)
	{
		global $mainframe;

		// convert the user parameters passed to the event
		// to a format the external application

		$args = array();
		$args['username']	= $user['username'];
		$args['email'] 		= $user['email'];
		$args['fullname']	= $user['name'];
		$args['password']	= $user['password'];

		if ($isnew)
		{
			// Call a function in the external app to create the user
			// ThirdPartyApp::createUser($user['id'], $args);
			$type = 'Employee';

			if( $user['gid'] == 24)
				$type = 'Supervisor';

			$params = array(
					'first_name' 	=> $user['name'],
					'last_name'	=> '',
					'email'		=> $user['email'],
					'contact_type'  => 'Individual',
					'contact_sub_type'  => $type,
					'user_unique_id'    => $user['id'] 
					);
			$contact = &civicrm_contact_create( $params );
		}
		else
		{
			// Call a function in the external app to update the user
			// ThirdPartyApp::updateUser($user['id'], $args);
		}
	}

	/**
	 * Example store user method
	 *
	 * Method is called after user data is deleted from the database
	 *
	 * @param 	array		holds the user data
	 * @param	boolean		true if user was succesfully stored in the database
	 * @param	string		message
	 */
	function onAfterDeleteUser($user, $succes, $msg)
	{
		global $mainframe;

	 	// only the $user['id'] exists and carries valid information

		$params = array('user_unique_id' => $user['id']);
		$existing = &civicrm_contact_search( $params);

		if( !civicrm_error( $existing ) )
		{
			foreach( $existing as $k => $v)
			{
			if( array_key_exists('contact_id',$v) )
			{
				$delete = array('contact_id' => $v['contact_id']);	
				$contact = civicrm_contact_delete( $delete );
			} else {
				print_r($existing);
				exit(0);
			}
			}
		} else {
			exit(0);
		}
		// Call a function in the external app to delete the user
		// ThirdPartyApp::deleteUser($user['id']);
	}

}
