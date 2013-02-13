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


/**
 * Example User Plugin
 *
 * @package		Joomla
 * @subpackage	JFramework
 * @since 		1.5
 */
class plgCivicrmPostProcess extends JPlugin {

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
	function plgCivicrmPostProcess(&$subject, $config)
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
	function civicrm_postProcess( $formName, &$form )
	{

		$errors = array();

		if( $formName === 'CRM_Contact_Form_Contact')
		{

		}
		//echo $formName;
		//exit(0);

		/* Pushed to Intake plugin
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

					// save custom phone
					$set_params = array('entityID' => $contact_select_id,
									'custom_280_-1' => $values['custom_67_-1'], // Phone
                                    'custom_805_-1' => $values['custom_62_-1'], // Veteran
                                    'custom_813_-1' => $values['custom_390_-1'],  // Race
                                    'custom_67_-1' => $values['custom_67_-1'], // Phone
                                    'custom_62_-1' => $values['custom_62_-1'], // Veteran
                                    'custom_390_-1' => $values['custom_390_-1']  // Race
					               );
					CRM_Core_BAO_CustomValueTable::setValues($set_params);

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

						$client->save();

						$contact = $defaultsAlt;
						if(isset($contact['address'][1]))
						{

							$contact['address'][1]['postal_code'] 		= $values['custom_66_-1'];
							$contact['address'][1]['state_province_id']= $values['custom_65_-1'];
							//					$contact['address'][1]['country_id'] 		= $values['custom_155_-1'];
							$contact['address'][1]['city'] 			= $values['custom_64_-1'];
							$contact['address'][1]['street_address'] 	= $values['custom_1448_-1'];
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
							$contact['address'][1]['location_type_id'] 	= 1;
							$location = array(	'version'    => '3.0',
					                        'contact_id' => $contact_select_id,
					                        'address'    => $contact['address']
							);
							require_once 'api/v2/Location.php';
							$newLocation =& civicrm_location_add($location);
						}

					}
				}

			}*/
		} 
			/*if($form->_activityTypeId == IFIT_REFERRAL)
			{
				//echo dirname(__FILE__) . '/../../custom_php/CRM/Case/Form/Activity/ChangeCaseStatus';
				require_once 'CRM/Case/BAO/Case.php';
				require_once 'CRM/Utils/Date.php';
				$query = 'SELECT status_id FROM civicrm_case WHERE id=' . $form->_caseId;
				$dao = CRM_Core_DAO::executeQuery($query);
				$dao->fetch();
					
				echo $dao->status_id;
				$params = array('case_status_id' => 14,
				//'status_id' => 14,
					'current_status_id' => $dao->status_id,
					'assignee_contact_id' => $form->_assignee_contact_id,
					'target_contact_id' => $form->_defaults['target_contact_id'],
					'source_contact_id' => $form->_defaults['source_contact_qid'],
					'activity_type_id' => CHANGE_CASE_STATUS,
					'activity_date_time' => CRM_Utils_Date::processDate( $form->_defaults['activity_date_time'], $form->_defaults['activity_date_time_time'] ),
					'id' => $form->_caseId);
				//'case_id' => $form->_caseId );
				//print_r($form->_defaults);
				//exit(0);

				$activity = CRM_Activity_BAO_Activity::create( $params );
				//$case = CRM_Case_BAO_Case::create( $params );

				$query = 'UPDATE civicrm_case SET status_id=14 WHERE id='.$form->_caseId;
				$dao = CRM_Core_DAO::executeQuery($query);

				$params['activity_id'] = $activity->id;
				$params['subject'] = 'Change Case Status';
				$params['case_id'] = $form->_caseId;
				$params['status_id'] = 14;

				$case_activity = CRM_Case_BAO_Case::processCaseActivity( $params );
				$result = CRM_Case_BAO_Case::processChangeCaseStatusActivity( &$params );
				//print_r($form);
				//exit(0);

			}*/

}
