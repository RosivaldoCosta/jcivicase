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


/**
 * Example User Plugin
 *
 * @package		Joomla
 * @subpackage	JFramework
 * @since 		1.5
 */
class plgCivicrmValidate extends JPlugin
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
    function plgCivicrmValidate(&$subject, $config)
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
    function civicrm_validate($formName, &$fields, &$files, &$form)
    {

	/*
		$errors = array();
		if(!isset($fields['newlethality']) &&
			isset($form->_activityTypeId) &&
			$form->_activityTypeId == 13 &&
                ((isset($fields['case_type_id']) &&
			$fields['case_type_id'] == 1)  ||
                 (isset($form->_caseType) && $form->_caseType == 'operations_center'))  // edit
           ) // Intake and OPS
        {
                $suicideIdeationField = 'custom_4';
                $suicideThreatField = 'custom_116';
                $crisis_plan_group = 13;
                $crisis_plan_field = 79;
                $group = $form->_groupTree[$crisis_plan_group];
                $gfields = $group['fields'];
                $crisisField = $gfields[$crisis_plan_field];
                $crisisPlanField = $crisisField['element_name'];
                $suicideIdeation = CRM_Utils_Array::value_fuzzy($suicideIdeationField,$fields);
                $suicideThreat = CRM_Utils_Array::value_fuzzy($suicideThreatField, $fields);

                if( $suicideThreat || $suicideIdeation)
                {
                        if( $suicideThreat === 'Yes' || $suicideIdeation === 'Yes')
                        {
                                if(CRM_Utils_Array::value($crisisPlanField, $fields,'') == '')
                                {
                                        $errors[$crisisPlanField] = ts('Crisis Plan is required because Suicide Threat and Suicide Ideation were selected');
                                        $form->setElementError($crisisPlanField,"Crisis Plan is required because Suicide Threat or/and Suicide Ideation were selected");
                                }
                        }
                }
        }
	*/


	//exit(0);
	//return empty( $errors ) ? true : $errors;

	$errors = array();

	if(!isset($fields['newlethality']) && isset($form->_activityTypeId) && $form->_activityTypeId == 13
		&&
		((isset($fields['case_type_id']) && $fields['case_type_id'] == 1)   	// create
			||
			(isset($form->_caseType) && $form->_caseType == 'operations_center'))	// edit
	) // Intake and OPS

	{
	    $suicideIdeationField = 'custom_4';
	    $suicideThreatField = 'custom_116';
	    $crisis_plan_group = 13;
	    $crisis_plan_field = 79;
	    $group = $form->_groupTree[$crisis_plan_group];
	    $gfields = $group['fields'];
	    $crisisField = $gfields[$crisis_plan_field];
	    $crisisPlanField = $crisisField['element_name'];
	    //$suicideIdeation = CRM_Utils_Array::value_fuzzy($suicideIdeationField,$fields);
	    //$suicideThreat = CRM_Utils_Array::value_fuzzy($suicideThreatField, $fields);
	    $suicideIdeation = CRM_Utils_Array::value($suicideIdeationField,$fields);
	    $suicideThreat = CRM_Utils_Array::value($suicideThreatField, $fields);

	    if( $suicideThreat || $suicideIdeation)
	    {
		if( $suicideThreat === 'Yes' || $suicideIdeation === 'Yes')
		{
		    if(CRM_Utils_Array::value($crisisPlanField, $fields,'') == '')
		    {
			$errors[$crisisPlanField] = ts('Crisis Plan is required because Suicide Threat and Suicide Ideation were selected');
			$form->setElementError($crisisPlanField,"Crisis Plan is required because Suicide Threat or/and Suicide Ideation were selected");
		    }
		}
	    }
	}

	if (isset($form->_elementIndex['custom_67_1']))
	{
	    $element = $form->getElement('custom_67_1') ;
	    $pattern = '/^\(\d{3}\)\d{3}-\d{4}/';
	    if( preg_match($pattern, $element->_attributes['value']) == 0)
	    {
		$errortext = ts('%1  has the wrong format ', array( 1 => $element->_label));
		$errors[$k] = $errortext;
		$form->setElementError('custom_67_1',$errortext);
	    }
	}
	if (isset($form->_elementIndex['custom_67_-1']))
	{
	    $element = $form->getElement('custom_67_-1') ;
	    $pattern = '/^\(\d{3}\)\d{3}-\d{4}/';
	    if( preg_match($pattern, $element->_attributes['value']) == 0)
	    {
		$errortext = ts('%1  has the wrong format ', array( 1 => $element->_label));
		$errors[$k] = $errortext;
		$form->setElementError('custom_67_-1',$errortext);
	    }
	}

	if(!isset($fields['newlethality']) && (isset($form->_activityTypeId) && $form->_activityTypeId == 13 )
		|| (isset($form->_subType) && $form->_subType == 13))
	{
	    if((!isset($fields['custom_116_-1']) && !isset($fields['custom_116_-1']))
		    || (isset($fields['custom_116_-1']) && $fields['custom_116_-1'] != 'Yes' && $fields['custom_4_-1'] != 'Yes'))
	    {
		$caseTypeId = false;
		if(isset($form->_caseTypeId))
		{
		    $caseTypeId = $form->_caseTypeId;
		}
		elseif (isset($fields['case_type_id']))
		{
		    $caseTypeId = $fields['case_type_id'];
		}


		if($caseTypeId)
		{
		    $fieldsReq = array( '1' => array('custom_62_', // Veteran OPS
				    'contact', // PFirstName
				    'custom_67_', // Client Information: Phone
				    'custom_1448_', // Client Information: Address
				    'custom_51_', // Caller Name
				    'custom_57_', // Relationship
				    'activity_details', // Reason For Contact create Case
				    'details', // Reason For Contact edit Case
				    //                                            'custom_117_', // Insurance Company
				    'custom_121_', // Insurance Type
				    'custom_70_', // DOB
				    'custom_155_' // County
			    ),
			    '2' => array(     //  MST
				    'contact', // Client First Name, Client Last Name
				    'custom_1448_', // Client Address
				    'custom_67_', // Client Phone
				    'custom_51_' // Caller Name
			    )
		    );
		    if(isset($fieldsReq[$caseTypeId]))
		    {
			foreach ($fieldsReq[$caseTypeId] as $field)
			{
			    if ( $field != 'custom_70_')
			    {
				$fieldNotFound = true;
				foreach($fields as $k => $v)
				{
				    $pattern = '/^'.$field.'/';
				    if( preg_match($pattern, $k) > 0)
				    {
					if ($fields[$k] == '' && isset($form->_elementIndex[$k]) && $k != 'contact_select_id')
					{
					    $element = $form->getElement($k) ;
					    $errortext = ts('%1 is required ', array( 1 => $element->_label));
					    $errors[$k] = $errortext;
					    $form->setElementError($k,$errortext);
					}
					$fieldNotFound = false;
				    }
				    else
				    {
				    }
				}
				if ( $field == 'custom_121_' && $fieldNotFound )
				{
				    foreach($form->_elementIndex as $k => $v)
				    {
					$pattern = '/^'.$field.'/';
					if( preg_match($pattern, $k) > 0)
					{
					    $element = $form->getElement($k) ;
					    $errortext = ts('%1 is required ', array( 1 => $element->_label));
					    $errors[$k] = $errortext;
					    $form->setElementError($k,$errortext);
					}
				    }
				}
			    }
			    else
			    {
				$fieldDOB = '';
				foreach($form->_elementIndex as $k => $v)
				{
				    $pattern = '/^'.$field.'/';
				    if( preg_match($pattern, $k) > 0)
				    {
					$fieldDOB = $k;
				    }
				}
				$fieldDobYN = '';
				foreach($form->_elementIndex as $k => $v)
				{
				    $pattern = '/^custom_1495_/'; // new field: if = yes m.b. unknown DOB
				    if( preg_match($pattern, $k) > 0)
				    {
					$fieldDobYN = $k;
				    }
				}
				if( $fieldDOB != '' && $fields[$fieldDOB] == '' && $fieldDobYN != '' && (!isset($fields[$fieldDobYN]) || $fields[$fieldDobYN] == 0 )) // new field: if = yes m.b. unknown DOB

				{
				    $element = $form->getElement($fieldDOB) ;
				    $errortext = ts('%1 is required ', array( 1 => $element->_label));
				    $errors[$fieldDOB] = $errortext;
				    $form->setElementError($fieldDOB,$errortext);
				}
				else
				{
				    // must by set javascript $fields[$fieldDobYN] == 1
				}
			    }
			}
		    }
		}
	    }
	}

	if( $formName === 'CRM_Case_Form_Case')
	{
	}
	else if ($formName == 'CRM_Case_Form_Activity')
	{
	    if (isset($fields['newlethality']) && $fields['newlethality'])
	    {
		return true;
	    }
	    // Make the email Activity/Form to field mandatory
	    if(!isset($form->_activityTypeId))
	    {
		$aId = CRM_Utils_Array::value( 'id', $_GET );
		if($aId)
		{
		    $dao = new CRM_Activity_BAO_Activity();
		    $dao->id = $aId;
		    if( $dao->find( true ) )
		    {
			$aType = $dao->activity_type_id;
		    }
		    else
		    {
			$aType = CRM_Utils_Array::value( 'atype', $_GET );
		    }
		}
		else
		{
		    $aType = CRM_Utils_Array::value( 'atype', $_GET );
		}
	    }
	    else
	    {
		$aType = $form->_activityTypeId;
	    }
	    /*
        	$aTypeVouchers = array(	86, // Pharmacy Voucher
        							87, // Temporary Housing
        							88, // Motel Voucher
        							89 // Transportation Voucher
        						);
        	if(in_array($aType,$aTypeVouchers))
        	{
        	    if(!isset($fields['contact_check']))
        	    {
        	        $errors[$crisisPlanField] = ts("Send a Copy must by checked.");
        	        $form->setElementError('contact_check',"Send a Copy must by checked.");
        	    }
        	}
	    */
	}

	return empty( $errors ) ? true : $errors;

    }

}
