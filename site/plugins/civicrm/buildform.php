<?php
// No direct access
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');
class plgCivicrmBuildForm extends JPlugin
{
    /**
     *	Post Civicrm Plugin
     */

    public function civicrm_buildForm( $formName, &$form )
    {
	$defaults = array();

	// delete double element source_contact_id
	foreach($form->_elements as $key=>$els)
	{
	    if($els->_attributes['name'] === 'source_contact_id' && $form->_elementIndex['source_contact_id'] != $key)
	    {
		unset($form->_elements[$key]);
	    }
	}
	// freeze the username on the forms
	if (isset($form->_elementIndex['source_contact_id']))
	{
	    $element = $form->getElement('source_contact_id') ;
	    $element->freeze();
	}

	if( $formName == 'CRM_Case_Form_Case')
	{
	    // Form Intake?
	    if (isset($form->_elementIndex['profiles']))
	    {
		$element = $form->getElement('profiles') ;
		foreach($element->_options as $key => $val)
		{
		    // Delete two options New Organization and New Household
		    if(isset($val['text']) && ($val['text'] == 'New Organization' || $val['text'] == 'New Household'))
		    {
			unset($element->_options[$key]);
		    }
		}
	    }

	    // The required fields for the Intake form will change based on which type of case
	    if((isset($form->_activityTypeId) && $form->_activityTypeId == 13)
		    || (isset($form->_subType) && $form->_subType == 13))
	    {
		$form->_required = array();
		if (isset($form->_elementIndex['activity_details']))
		{
		    $element = $form->getElement('activity_details') ;
		    $element->_label = 'Reason For Contact';
		}

		if (isset($form->_elementIndex['contact']))
		{
		    $element = $form->getElement('contact') ;
		    $element->freeze();
		}
	    }

	    if(isset($form->_currentlyViewedContactId))
	    {
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

		//  the client information should populate automatically based on the existing client's profile information
		if($aType == 13) // Intake

		{
		    $this->joomla_civicrm_buildForm_get_user_info( $form);
		}
	    }
	}
	else if ($formName == 'CRM_Case_Form_Activity')
	{
	    if (isset($form->_elementIndex['custom_1708_-1']))
	    {
		$element = $form->getElement('custom_1708_-1');
		$element->updateAttributes(array('style'=>"width:640px;"));
	    }
	    $report = false;
	    if( !property_exists($form, '_contactID'))
	    {
		$params = array();
		$caseid = $form->_caseId.'';
		$contactid = CRM_Case_BAO_Case::retrieveContactIdsByCaseId($caseid);
		if( count($contactid) > 0 )
		{
		    $params['id'] = $params['contact_id'] = $contactid[1];
		    $form->_contactID = $contactid[1];
		    $defaultsCont = array();
		    $client = CRM_Contact_BAO_Contact::retrieve( $params, $defaultsCont, true );
		    //$defaults['custom_108_-1'] = 'First Name'; //$client->first_name;
		    // Add DOB
		    $defaults['custom_304_-1'] = CRM_Utils_Date::customFormat($client->birth_date,'%m/%d/%Y');
		    $defaults['custom_674_-1'] = CRM_Utils_Date::customFormat($client->birth_date,'%m/%d/%Y');
		    $atArray = array('activity_type_id' => 13);
		    $activities = CRM_Case_BAO_Case::getCaseActivity( $caseid,
			    $atArray,
			    $contactid[1] );
		    $activities = array_keys($activities);
		    if( count($activities) > 0 )
		    {
			$activities = $activities[0];

			require_once 'CRM/Case/XMLProcessor/Report.php';
			$xmlProcessor = new CRM_Case_XMLProcessor_Report( );
			$report       = $xmlProcessor->getActivityInfo( $contactid[1], $activities, true );
			if(array_key_exists("customGroups",$report) && is_array($report["customGroups"]))
			{
			    if(array_key_exists("Linkage and Referrals", $report["customGroups"]) && is_array($report["customGroups"]["Linkage and Referrals"]))
			    {
				foreach($report["customGroups"]["Linkage and Referrals"] as $k=>$v)
				{
				    if($v['label'] == 'Linkage')
				    {
					$defaults['custom_330_-1'] =  $v['value'];
				    }

				    if($v['label'] == 'MH')
				    {
					//print_r($v);

					$defaults['custom_791_-1'] =  $v['value'];

				    }

				    if($v['label'] == 'NMH')
				    {
					$defaults['custom_792_-1'] =  $v['value'];

				    }
				}
			    }
			}
		    }
		}
	    }

	    // Print out Client Profile in these forms
	    $aTypeCustom = array(	35, // Lethality
		    36, // Care Plan
		    37, // Assessment And Treatment
		    38, // MCT Dispatch
		    39, // Consent For Services
		    40, // Authorization To Releas
		    47, // Discharge Summary
		    50, // Progress Note
		    51, // Individual Treatment Plan
		    82, // Informed Consent
		    84, // Privacy Practices
		    85, // Human Rights Notification
		    86, // Pharmacy Voucher
		    87, // Temporary Housing
		    88, // Motel Voucher
		    89, // Transportation Voucher
		    90, // UCC Medical Evaluation
		    92, // Medication History
		    93,  // Informed Consent for Medication
		    48  // Phone Contact
	    );
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

	    if(in_array($aType,$aTypeCustom) && $form->_newLethality === NULL )
	    {

		//$this->joomla_civicrm_buildForm_user_info( $formName, $form, $report );
		require_once 'CRM/Contact/BAO/Client.php';
		CRM_Contact_BAO_Client::buildForm_user_info( $form );
	    }

	    // Discharge Summary
	    if($aType == 47)
	    {
		if (isset($form->_elementIndex['activity_date_time']))
		{
		    $defaults['case_status_id'] = 15; //array_search( 'Recommend for Closure', CRM_Case_PseudoConstant::caseStatus());
		    $element = $form->getElement('activity_date_time') ;
		    $element->_label = 'Date Closed';
		}
	    }


	    // edit Intake
	    if(isset($form->_caseId) && ((isset($form->_activityTypeId) && $form->_activityTypeId == 13)
			    || (isset($form->_subType) && $form->_subType == 13)))
	    {
		$form->_required = array();
		if (isset($form->_elementIndex['details']))
		{
		    $element = $form->getElement('details') ;
		    $element->_label = 'Reason For Contact';
		}
	    }


	    if($form->_isPrint)
	    {
		foreach ($form->_elementIndex as $elementName => $index)
		{
		    $element = $form->getElement($elementName) ;
		    $element->freeze( );
		}
	    }

	    $form->assign( 'pdf', false );

	}
	else if ($formName == 'CRM_Activity_Form_Activity')
	{
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
	    require_once 'CRM/Core/OptionGroup.php';
	    $caseTasks = CRM_Core_OptionGroup::values( 'case_tasks' );
	    $activityStatus = CRM_Core_PseudoConstant::activityStatus( );
	    if(!$form->_activityId)
	    {
		if(in_array($aType, array_keys($caseTasks)))
		{
		    if (isset($form->_elementIndex['status_id']))
		    {
			$defaultScheduled = array(34, // How Are You
				56, // Confirm Appointment
				67 // Call Referrals
			);
			$element = $form->getElement('status_id') ;
			$element->_label = 'Level of Completion';
			if( in_array($aType, $defaultScheduled) )
			    $defaults['status_id'] = array_search( 'Completed', $activityStatus );
			else
			    $defaults['status_id'] = array_search( 'Completed', $activityStatus );
		    }
		}
		else
		{
		    $caseForms = CRM_Core_OptionGroup::values( 'case_forms' );
		    if(in_array($aType, array_keys($caseForms)))
		    {
			if (isset($form->_elementIndex['status_id']))
			{
			    $element = $form->getElement('status_id') ;
			    $element->_label = 'Level of Completion';
			    $defaults['status_id'] = array_search( 'Completed', $activityStatus );
			}
		    }
		}
	    }
	} else if ($formName == 'CRM_Tag_Form_Tag')
	{

	    $user = JFactory::getUser();
	    $templste = CRM_Core_Smarty::singleton();
	    $templste->assign('myType', $user->usertype);

	} else if($formName == 'CRM_Case_Form_CaseView')
	{

	    $config =& JFactory::getConfig( );

	    if($config->_registry['config']['data']->fromname == 'ESCRS EMR')
	    {
		//get activity id
		$data = array('activity_type_id' => '13');
		$dat = CRM_Case_BAO_Case::getCaseActivity($form->_caseID, $data, $form->_contactID);
		$activityIds = array_keys($dat);
		$activityId = $activityIds[0];

		//Get County info
		$IntakeInfo = CRM_Core_BAO_CustomGroup::getTree('Activity', $var, $activityId, '', 13, '');

		/*foreach($IntakeInfo as $key=>$field)
		{
		    if($field['name'] == 'Client_Information')
		    {
			$county = $field['fields']['155']['customValue'][1]['data'];
			// set count info into groupTree var
			$tpl = CRM_Core_Smarty::singleton();
			$tpl->_tpl_vars['userinfo_groupTree']['Client_Profile']['fields'][0][] = array('label' => 'County', 'value' => $county);
			break;
		    }
		}*/

	    }

	}

	//$form->setDefaults($defaults);

    }

    function joomla_civicrm_buildDischargeForm($formName, &$form, &$defaults)
    {
	$defaults['custom_291_-1'] = "10"; //CRM_Case_BAO_Case::calculateDaysOpened($form->_caseId);

	// Get Date Closed
	$defaults['custom_290_-1'] = ''; // CRM_Case_BAO_Case::getDateClosed($form->_caseId);

	// Date Opened
	$defaults['custom_289_-1'] = CRM_Case_BAO_Case::getDateOpened($form->_caseId);

	// Age Bracket
	$params = array();
	$params['id'] = $params['contact_id'] = $form->_currentlyViewedContactId;
	$client = CRM_Contact_BAO_Contact::retrieve( $params, $defaults, true );
	$age = CRM_Utils_Date::calculateAge($client->birth_date);
	if( $age <= 12)
	    $defaults['custom_300_-1'] = 'child';
	else if ( $age >= 13 && $age <= 17)
	    $defaults['custom_300_-1'] = 'adolescent';
	else if ( $age >= 18 && $age <= 21 )
	    $defaults['custom_300_-1'] = 'trans_adult';
	else if ( $age >= 22 && $age <= 60 )
	    $defaults['custom_300_-1'] = 'adult';
	else if ( $age > 61 )
	    $defaults['custom_300_-1'] = 'geriatric';
	else
	    $defaults['custom_300_-1'] = 'unknown';



	require_once 'CRM/Case/Form/Activity/OpenCase.php';

	// Referral Source
	$defaults['custom_293_-1'] = CRM_Case_Form_Activity_OpenCase::getValue('Referral Source','Source',$form->_caseId, $form->_currentlyViewedContactId);

	// Insurance Status
	$defaults['custom_294_-1'] = CRM_Case_Form_Activity_OpenCase::getValue('Insurance','Insurance Type',$form->_caseId, $form->_currentlyViewedContactId);

	// Risk Factors
	$risk_factors = array();
	$risk_factors[] = $defaults['custom_298_-1[homeless]'] = CRM_Case_Form_Activity_OpenCase::getValue('Risk Factors','Eviction or Homeless',$form->_caseId, $form->_currentlyViewedContactId);
	$risk_factors[] = $defaults['custom_298_-1[imminent_admit]'] = CRM_Case_Form_Activity_OpenCase::getValue('Risk Factors','Inpatient Hospital',$form->_caseId, $form->_currentlyViewedContactId);
	$risk_factors[] = $defaults['custom_298_-1[incarceration]'] = CRM_Case_Form_Activity_OpenCase::getValue('Risk Factors','Incarceration',$form->_caseId, $form->_currentlyViewedContactId);
	$risk_factors[] = $defaults['custom_298_-1[future_hospitalization]'] = CRM_Case_Form_Activity_OpenCase::getValue('Risk Factors','Risk for Hospitalization',$form->_caseId, $form->_currentlyViewedContactId);
	$defaults['custom_298_-1[homeless]'] = 1;
	$defaults['custom_298_-1[imminent_admin]'] = 0;
	//print_r($risk_factors);
	//$defaults['custom_298_-1'] = CRM_Case_Form_Activity_OpenCase::getValue('Risk Factors','Insurance Type',$form->_caseId, $form->_currentlyViewedContactId);

	// CRS Action
	$actions = array();
	$actions[] = CRM_Case_Form_Activity_OpenCase::getValue('CRS Action','Information Only',$form->_caseId, $form->_currentlyViewedContactId);
	$actions[] = CRM_Case_Form_Activity_OpenCase::getValue('CRS Action','Community Ed',$form->_caseId, $form->_currentlyViewedContactId);
	$actions[] = CRM_Case_Form_Activity_OpenCase::getValue('CRS Action','Crisis Plan Only',$form->_caseId, $form->_currentlyViewedContactId);
	$actions[] = CRM_Case_Form_Activity_OpenCase::getValue('CRS Action','Called 911',$form->_caseId, $form->_currenltyViewedContactId);
	$actions[] = CRM_Case_Form_Activity_OpenCase::getValue('CRS Action','MCT Dispatch',$form->_caseId, $form->_currentlyViewedContactId);
	$actions[] = CRM_Case_Form_Activity_OpenCase::getValue('CRS Action','Notify APS or CPS',$form->_caseId, $form->_currenltyViewedContactId);
	$actions[] = CRM_Case_Form_Activity_OpenCase::getValue('CRS Action','Non-MH Referral',$form->_caseId, $form->_currentlyViewedContactId);
	$actions[] = CRM_Case_Form_Activity_OpenCase::getValue('CRS Action','MH Referral',$form->_caseId, $form->_currentlyViewedContactId);
	$actions[] = CRM_Case_Form_Activity_OpenCase::getValue('CRS Action','Operator Number',$form->_caseId, $form->_currentlyViewedContactId);
	$actions[] = CRM_Case_Form_Activity_OpenCase::getValue('CRS Action','Operator Time',$form->_caseId, $form->_currentlyViewedContactId);
	$actions[] = CRM_Case_Form_Activity_OpenCase::getValue('CRS Action','Emergency Petition',$form->_caseId, $form->_currentlyViewedContactId);
	$actions[] = CRM_Case_Form_Activity_OpenCase::getValue('CRS Action','Voluntary Admit to Emergency Room',$form->_caseId, $form->_currentlyViewedContactId);
	//print_r($actions);
	//$defaults['custom_16_-1'] = CRM_Case_Form_Activity_OpenCase::getValue('CRS Action','',$form->_caseId);

	// Lethality


	// MH Provider Linked/Admitted to

	// Urgent Care Appointments
	// Hospitalized - If Yes, was client assessed for crisis bed?


	// IHIT/IFIT Total Visits
	// COTAA Total Visits
	// With Psychiatrist

	// Vouchers - Other Assistance Provided

	// Med Voucher Provided

	$form->setDefaults($defaults);


    }


    /**
     * Build block Client Profile
     *
     * @param $formName
     * @param $form
     * @param $report
     *
     */
    function joomla_civicrm_buildForm_user_info( $formName, &$form, &$report )
    {
	if(!isset($form->_contactID) && isset($form->_currentlyViewedContactId))
	{
	    $form->_contactID = $form->_currentlyViewedContactId;
	}
	if(!$report)
	{
	    $params = array();
	    $caseid = $form->_caseId.'';

	    $atArray = array('activity_type_id' => 13); // Intake
	    $activities = CRM_Case_BAO_Case::getCaseActivity( $caseid, $atArray, $form->_contactID );
	    $activities = array_keys($activities);
	    if( count($activities) > 0 )
	    {
		$activities = $activities[0];

		require_once 'CRM/Case/XMLProcessor/Report.php';
		$xmlProcessor = new CRM_Case_XMLProcessor_Report( );
		$report       = $xmlProcessor->getActivityInfo( $form->_contactID, $activities, true );
	    }
	}

	if(isset($report["customGroups"]) && is_array($report["customGroups"]))
	{

	    $groupTree['Client_Profile']['name'] = 'Client_Profile';
	    $groupTree['Client_Profile']['title'] = 'Client Profile';
	    $groupTree['Client_Profile']['collapse_display'] = 0;
	    $groupTree['Client_Profile']['fields'] = array();

	    $params = array('id' => $form->_contactID);
	    $defaultsAttr = array();
	    $client = CRM_Contact_BAO_Contact::retrieve( $params, $defaultsAttr, true );

	    // column 1
	    $caseArr = array('label' => ts('Client'),'value' => $client->first_name . ' ' . $client->last_name, 'type' => 'String');
	    $groupTree['Client_Profile']['fields'][0][] = $caseArr;

	    $report['customGroups']['Client Information'][10]['label'] = ts('Sex');
	    $groupTree['Client_Profile']['fields'][0][] = $report['customGroups']['Client Information'][10];

	    $report['customGroups']['Client Information'][0]['label'] = ts('Veteran');
	    $groupTree['Client_Profile']['fields'][0][] = $report['customGroups']['Client Information'][0];
	    //print_r($report['customGroups']['Client Information']);
	    //exit(0);

		// column 2
		$report['customGroups']['Client Information'][18]['label'] = ts('Address');
		$groupTree['Client_Profile']['fields'][1][] = $report['customGroups']['Client Information'][17];
		$report['customGroups']['Client Information'][14]['label'] = ts('Race');
		$groupTree['Client_Profile']['fields'][1][] = $report['customGroups']['Client Information'][13];
		if($_GET['debug']) echo '<pre>'.print_r($report['customGroups']['Client Information'],true).'</pre>';
		$report['customGroups']['Insurance'][4]['label'] = ts('Insurance');
		$groupTree['Client_Profile']['fields'][1][] = $report['customGroups']['Insurance'][3];
		$report['customGroups']['Financial'][0]['label'] = ts('Employment Status');
		$groupTree['Client_Profile']['fields'][1][] = $report['customGroups']['Financial'][0];

	    // column 3
	    $report['customGroups']['Client Information'][5]['label'] = ts('Phone Number');
	    $groupTree['Client_Profile']['fields'][2][] = $report['customGroups']['Client Information'][5];

	    $report['customGroups']['Client Information'][9]['label'] = ts('Age');
	    $groupTree['Client_Profile']['fields'][2][] = $report['customGroups']['Client Information'][9];

	    $report['customGroups']['Client Information'][8]['label'] = ts('DOB');
	    $report['customGroups']['Client Information'][8]['value'] = CRM_Utils_Date::customFormat(CRM_Utils_Date::processDate($report['customGroups']['Client Information'][8]['value']),'%m/%d/%Y');
	    ;
	    $groupTree['Client_Profile']['fields'][2][] = $report['customGroups']['Client Information'][8];
	    $caseArr = array('label' => ts('Marital Status'),'value' => '', 'type' => 'String');
	    $groupTree['Client_Profile']['fields'][2][] = $caseArr;

	    //retrieve details about case
	    $case1 = new CRM_Case_BAO_Case();
	    $case1->id = $form->_caseId;
	    $res = $case1->find(true);
	    $case_status = CRM_Utils_Array::value( $case1->status_id, CRM_Case_PseudoConstant::caseStatus());

	    // column 4
	    $caseArr = array('label' => ts('Case Number'),'value' => $form->_caseId, 'type' => 'String');
	    $groupTree['Client_Profile']['fields'][3][] = $caseArr;
	    $report['fields'][8]['label'] = ts('Status');
	    $groupTree['Client_Profile']['fields'][3][] = array('label' => ts('Status'), 'value' => $case_status, 'type' => 'String');//$report['fields'][8];
	    $caseTypes    = CRM_Core_OptionGroup::values( 'case_type' );
	    $caseTypesId = CRM_Case_PseudoConstant::caseTypeName( $form->_caseId );
	    $caseArr = array('label' => ts('Case Type'),'value' => $caseTypes[$caseTypesId['id']], 'type' => 'String');
	    $groupTree['Client_Profile']['fields'][3][] = $caseArr;
	    $report['fields'][6]['label'] = ts('Case Opened');
	    $report['fields'][6]['value'] = CRM_Utils_Date::customFormat($report['fields'][6]['value'],'%m/%d/%Y');
	    $groupTree['Client_Profile']['fields'][3][] = $report['fields'][6];

	    $form->assign_by_ref( 'userinfo_groupTree', $groupTree );

	}
    }

    /**
     * the client information should populate automatically based on the existing
     * client's profile information
     *
     * @param $form
     *
     */
    function joomla_civicrm_buildForm_get_user_info( &$form )
    {
	require_once 'CRM/Contact/Page/AJAX.php';

	$defaults = CRM_Contact_Page_AJAX::getContactInfoProfile($form->_currentlyViewedContactId);
	$form->setDefaults($defaults);
    }

    /**
     * the client information should get
     * client's case information
     *
     * @param $element
     *
     */
    function joomla_civicrm_buildForm_get_activity_appt( $element )
    {
	$result = array();
	$result['date'] = CRM_Utils_Date::customFormat(CRM_Utils_Date::processDate($element['display_date']),'%m/%d/%Y');
	switch ($element['status'])
	{
	    case 'Kept (UCC Only)':
		$status = 'kept';
		break;
	    case 'No Show (UCC Only)':
		$status = 'no_show';
		break;
	    case 'Cancelled (UCC Only)':
		$status = 'cancelled';
		break;

	    default:
		$status = '';
		break;
	}
	$result['status'] = $status;

	return $result;
    }



}
