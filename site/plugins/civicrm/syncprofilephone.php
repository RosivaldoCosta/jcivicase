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
if(!defined('IFIT_REFERRAL'))
{
    define('IFIT_REFERRAL', 105);
}
if(!defined('CHANGE_CASE_STATUS'))
{
    define('CHANGE_CASE_STATUS', 16);
}
if(!defined('INTAKE'))
{
    define('INTAKE', 13);
}

/**
 * Example User Plugin
 *
 * @package		Joomla
 * @subpackage	JFramework
 * @since 		1.5
 */
class plgCivicrmSyncProfilePhone extends JPlugin
{

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
    function plgCivicrmSyncProfilePhone(&$subject, $config)
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

	///// onEdit Intake
	$errors = array();
	if( $op === 'edit' && $objectName == 'Case Activity')
	{
	    $params = array( 'id' => $objectId );
	    require_once "CRM/Activity/BAO/Activity.php";
	    CRM_Activity_BAO_Activity::retrieve( $params, $defaults );

	    if( $defaults['activity_type_id'] == 13)
	    {
		// get var from request
		require_once 'CRM/Utils/Rule.php';
		foreach($_REQUEST as $key=>$var)
		{
		    if(substr($key, 0, 10) == 'custom_67_')
		    {
			$activity_home_phone = CRM_Utils_Rule::phone($var)?$var:'';
		    } elseif (substr($key, 0, 10) == 'custom_750')
		    {
			$activity_work_phone = CRM_Utils_Rule::phone($var)?$var:'';
		    }
		}
		$session = CRM_Core_Session::singleton();

		require_once 'CRM/Core/BAO/Block.php';
		$phone_params['contact_id'] = $session->get('view.id');
		$phone_params['location_type_id'] = 1;
		$phone_params['entity_id'] = $objectId;
		$phone_params['phone'] = array();
		$phone_params['phone'][0]['phone_type_id'] = 1;
		$phone_params['phone'][0]['phone'] = $activity_home_phone;
		$phone_params['phone'][0]['location_type_id'] = 1;
		$phone_params['phone'][0]['is_primary'] = (!empty($activity_home_phone)) ? 1 : 0;
		$phone_params['phone'][1]['phone_type_id'] = 1;
		$phone_params['phone'][1]['phone'] = $activity_work_phone;
		$phone_params['phone'][1]['location_type_id'] = 2;
		$phone_params['phone'][1]['is_primary'] = (!empty($activity_work_phone) && empty($activity_home_phone)) ? 1 : 0;
		;

		$location['phone'] = CRM_Core_BAO_Block::create( 'phone', $phone_params );

	    }

	}

    }
    /**
     * Example store user method
     *
     * Method is called before user data is stored in the database
     *
     * @param 	array		holds the old user data
     * @param 	boolean		true if a new user is stored
     */
    function civicrm_postProcess( $formName, &$form )
    {

	///// onCreate Case
	$errors = array();
	if( $formName === 'CRM_Case_Form_Case')
	{
	    if( $form->_activityTypeId == 13)  // Intake/Open Case

	    {
		$contact_select_id  = false;

		$values = $form->_submitValues;
		if(isset($values['contact_select_id']))
		{
		    $contact_select_id  = $values['contact_select_id'];
		}
		else
		{
		    if(isset($form->_currentlyViewedContactId))
		    {
			$contact_select_id  = $form->_currentlyViewedContactId;
		    }
		}

		if(isset($contact_select_id))
		{
		    $params = array();
		    $contact_id = $params['id'] = $params['contact_id'] = $contact_select_id;
		    $defaultsAlt = array();
		    require_once 'CRM/Contact/BAO/Contact.php';
		    $client = CRM_Contact_BAO_Contact::retrieve( $params, $defaultsAlt, true );

		    if(isset($client))
		    {
			$phone_params = array();
			$activity_home_phone = $values['custom_67_-1'];
			$activity_work_phone = $values['custom_751_-1'];
			require_once 'CRM/Core/BAO/Block.php';
			$phone_params['contact_id'] = $contact_select_id;
			$phone_params['location_type_id'] = 1;
			$phone_params['phone'] = array();
			$phone_params['phone'][0]['phone_type_id'] = 1;
			$phone_params['phone'][0]['phone'] = $activity_home_phone;
			$phone_params['phone'][0]['location_type_id'] = 1;
			$phone_params['phone'][0]['is_primary'] = (!empty($activity_home_phone)) ? 1 : 0;
			$phone_params['phone'][1]['phone_type_id'] = 1;
			$phone_params['phone'][1]['phone'] = $activity_work_phone;
			$phone_params['phone'][1]['location_type_id'] = 2;
			$phone_params['phone'][1]['is_primary'] = (!empty($activity_work_phone) && empty($activity_home_phone)) ? 1 : 0;
			$location['phone'] = CRM_Core_BAO_Block::create( 'phone', $phone_params );
		    }
		}
	    }
	}
    }

}
