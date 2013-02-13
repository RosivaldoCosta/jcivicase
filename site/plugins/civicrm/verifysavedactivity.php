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
ini_set('display_errors', 1);
define('IFIT_REFERRAL', 105);
define('CHANGE_CASE_STATUS', 16);
define('INTAKE', 13);


/**
 * Example User Plugin
 *
 * @package		Joomla
 * @subpackage	JFramework
 * @since 		1.5
 */
class plgCivicrmVerifysavedactivity extends JPlugin {

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
	function plgCivicrmVerifysavedactivity(&$subject, $config)
	{
		parent::__construct($subject, $config);
	}

	/**
	 * Example store user method
	 *
	 * Method is called pre user data is stored in the database
	 *
	 * @param 	array		holds the old user data
	 * @param 	boolean		true if a new user is stored
	 */
	function civicrm_post( $op, $objectName, $objectId, &$objectRef)
	{

		$errors = array();

		if( $op === 'create' && $objectName == 'Activity')
		{
			//if( $objectRef->activity_type_id == INTAKE)
			//{
				/* 	Verify the custom fields were saved properly for the activity 

				   	Grab the activity type id and the custom fields for that type and then check to make sure there is an entity_id in the custom field
					tables for this activity

					If not, sent an alert email
				*/

				
			//}

		}
		//echo $formName;
		//exit(0);

	}
}
