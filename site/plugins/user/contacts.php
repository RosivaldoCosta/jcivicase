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

/**
 * Contacts User Plugin - automatically creates a new contact in the Contacts component
 *
 * @package		Joomla
 * @subpackage	JFramework
 * @since 		1.5
 */
class plgUserContacts extends JPlugin {

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
	function plgUserContacts(& $subject, $config)
	{
		parent::__construct($subject, $config);
	}

	/**
	 * Example store user method
	 *
	 * Method is called before user data is stored in the database
	 *
	 * @param 	array		holds the old user data
	 * @param 	boolean		true if a new user is stored
	 */
	function onBeforeStoreUser($user, $isnew)
	{
		global $mainframe;
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
			global $mainframe;

        		// Check for request forgeries
        		JRequest::checkToken() or jexit( 'Invalid Token' );

        		// Initialize variables
        		$db             =& JFactory::getDBO();
			JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_contact'.DS.'tables');
        		$row    =& JTable::getInstance('contact', 'Table');
        		$post = JRequest::get( 'post' );
        		//$post['misc'] = JRequest::getVar('misc', '', 'POST', 'string', JREQUEST_ALLOWHTML);
        		if (!$row->bind( $post )) {
                		JError::raiseError(500, $row->getError() );
        		}
	
			$row->catid = 1;
			$row->user_id = $user[id];

			// pre-save checks
        		if (!$row->check()) {
                		JError::raiseError(500, $row->getError() );
        		}

        		// if new item, order last in appropriate group
        		if (!$row->id) {
                		$where = "catid = 1 ";// . (int) $row->catid;
                		$row->ordering = $row->getNextOrder( $where );
        		}

        		// save the changes
        		if (!$row->store()) {
                		JError::raiseError(500, $row->getError() );
        		}
			
			if( $row->id)
			{
				$user['contact_id'] = $row->id;
			}
        		$row->checkin();
        		if ($row->default_con) {
                	$query = 'UPDATE #__contact_details'
                		. ' SET default_con = 0'
    		            	. ' WHERE id <> '. (int) $row->id
                		.' AND default_con = 1'
                		;
                	$db->setQuery( $query );
                	$db->query();
        }

			// Call a function in the external app to create the user
			// ThirdPartyApp::createUser($user['id'], $args);
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
	 * Method is called before user data is deleted from the database
	 *
	 * @param 	array		holds the user data
	 */
	function onBeforeDeleteUser($user)
	{
		global $mainframe;
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

		// Call a function in the external app to delete the user
		// ThirdPartyApp::deleteUser($user['id']);
	}

}
