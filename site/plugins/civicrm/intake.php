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

//jimport('joomla.plugin.plugin');
//ini_set('display_errors', 1);
define('INTAKE', 13);


/**
 * Example User Plugin
 *
 * @package		Joomla
 * @subpackage	JFramework
 * @since 		1.5
 */
class plgCivicrmIntake extends JPlugin
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
    function plgCivicrmIntake(&$subject, $config)
    {
	parent::__construct($subject, $config);

	// load plugin parameters
	$this->_plugin = JPluginHelper::getPlugin( 'civicrm', 'intake' );
	$this->_params = new JParameter( $this->_plugin->params );
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
	$errors = array();

	if( $formName === 'CRM_Case_Form_Case')
	{

	    if( $form->_activityTypeId == 13)  // Intake/Open Case
	    {
		$fields_id = array(
			'phone_field',
			'veteran_field',
			'race_field',
			'phone1_field',
			'veteran1_field',
			'race1_field',
			'client_age_field',
			'financial_field',
			'insurance_type_field',
			'county_field'
		);
		$fields = array();
		foreach ($fields_id as $value)
		{
		    $arr = explode('>', $this->_params->get($value));
		    if(isset($arr[1]) && $arr[1]>0)
		    {
			foreach ($arr as $key=>$valueArr)
			{
			    $arr[$key] = (int)$valueArr;
			}
			if($arr[0]>0 && $arr[1]>0)
			    $fields[$arr[1]] = $arr[0];
		    }
		}

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
		    $set_params = array('entityID' => $contact_select_id);
		    foreach ($fields as $key=>$value)
		    {

			if(isset($values["custom_{$value}_-1"]))
			{
			    $set_params["custom_{$key}_-1"] = $values["custom_{$value}_-1"];
			} 
		    }

		    //echo '<pre>'.print_r($set_params,true).'</pre>';
		    if(count($fields) > 1)
			$errors = CRM_Core_BAO_CustomValueTable::setValues($set_params);
		    //echo '<pre>'.print_r($errors,true).'</pre>';
		    //die(__METHOD__);
		    $params = array();
		    $contact_id = $params['id'] = $params['contact_id'] = $contact_select_id;
		    $defaultsAlt = array();
		    require_once 'CRM/Contact/BAO/Contact.php';
		    $client = CRM_Contact_BAO_Contact::retrieve( $params, $defaultsAlt, true );

		    if(isset($client))
		    {

			// DOB
			$client->birth_date = CRM_Utils_Date::processDate($values['custom_70_-1'],null,false,'Ymd');
			//	Gender
			switch ($values['custom_72_-1'])
			{
			    case 'F':
				$client->gender_id = 1;
				break;
			    case 'M':
				$client->gender_id = 2;
				break;
			}

			$client->phone[0]['contact_id'] = $contact_select_id; //$values['custom_67_-1'];
			$client->phone[0]['phone'] = $values['custom_67_-1'];
			$client->phone[0]['location_type_id'] = 1;
			$client->phone[0]['phone_type_id'] = 1;

			$client->save();
			//echo '<pre>'.print_r($client,true).'</pre>';exit(0);


			$contact = $defaultsAlt;
			if(isset($contact['address'][1]))
			{

			    $contact['address'][1]['postal_code'] 		= $values['custom_66_-1'];
			    $contact['address'][1]['state_province_id']= $values['custom_65_-1'];
			    $contact['address'][1]['city'] 			= $values['custom_64_-1'];
			    $contact['address'][1]['street_address'] 	= $values['custom_1448_-1'];
			    //$contact['address'][1]['street_address'] 	= $values['custom_60_-1'];

			    $contact['phone'][0]['phone'] = $values['custom_67_-1'];

			    //	 get all ids if not present.
			    require_once 'CRM/Contact/BAO/Contact.php';
			    CRM_Contact_BAO_Contact::resolveDefaults( $contact, true );
			    // save location and address
			    require_once 'CRM/Core/BAO/Location.php';
			    $result = CRM_Core_BAO_Location::create( $contact);
			}
			else
			{

			    $contact['address'][1]['contact_id'] 		= $contact_select_id;
			    $contact['address'][1]['is_primary'] = 1;
			    $contact['address'][1]['is_billing'] = 0;
			    $contact['address'][1]['postal_code'] 		= $values['custom_66_-1'];
			    $contact['address'][1]['state_province_id']	= $values['custom_65_-1'];
			    $contact['address'][1]['city'] 				= $values['custom_64_-1'];
			    $contact['address'][1]['street_address'] 	= $values['custom_1448_-1'];
			    //$contact['address'][1]['street_address'] 	= $values['custom_60_-1'];
			    $contact['address'][1]['location_type_id'] 	= 1;

			    $contact['phone'][0]['contact_id'] = $contact_select_id; //$values['custom_67_-1'];
			    $contact['phone'][0]['phone'] = $values['custom_67_-1'];
			    $contact['phone'][0]['location_type_id'] = 1;
			    $contact['phone'][0]['phone_type_id'] = 1;
			    $contact['phone'][0]['is_primary'] = 1;

			    $location = array(	'version'    => '3.0',
				    'contact_id' => $contact_select_id,
				    'address'    => $contact['address'],
				    'phone'		=> $contact['phone']
			    );
			    require_once 'api/v2/Location.php';
			    $newLocation =& civicrm_location_add($location);
			}

		    }
		}

	    }
	}
    }
}
