<?php
/**
* @version		$Id: joomla.php 10709 2008-08-21 09:58:52Z eddieajau $
* @package		Joomla
* @subpackage	JFramework
* @copyright	Copyright (C) 2011 Open Source Matters. All rights reserved.
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

/**
 * Joomla Authentication plugin
 *
 * @package		Joomla
 * @subpackage	JFramework
 * @since 1.5
 */
class plgUserSingleAuth extends JPlugin
{

	/**
	 * Constructor
	 *
	 * For php4 compatability we must not use the __constructor as a constructor for plugins
	 * because func_get_args ( void ) returns a copy of all passed arguments NOT references.
	 * This causes problems with cross-referencing necessary for the observer design pattern.
	 *
	 * @param object $subject The object to observe
	 * @param array  $config  An array that holds the plugin configuration
	 * @since 1.5
	 */
	function plgUsernSingleauth(& $subject, $config) {
		parent::__construct($subject, $config);
	}

	/**
	 * This method should handle any authentication and report back to the subject
	 *
	 * @access	public
	 * @param   array 	$credentials Array holding the user credentials
	 * @param 	array   $options     Array of extra options
	 * @param	object	$response	 Authentication response object
	 * @return	boolean
	 * @since 1.5
	 */
	function onLoginUser( $user, $options = array() )
	{
		$my =& JFactory::getUser();
		$sess =& JFactory::getSession();


		// Get a database object
		$db =& JFactory::getDBO();

		$query = '
			DELETE FROM `#__session`
			WHERE userid = ' .$my->id 
			.' AND session_id !=\''.$sess->getId().'\''
			;
		$db->setQuery( $query );
		$result = $db->loadResult();
	}
}
