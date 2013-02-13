<?php
// No direct access
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');
class plgCivicrmIntakepdf extends JPlugin
{
    /**
     Post Civicrm Plugin
     */

    public function civicrm_buildForm( $formName, &$form )
    {

	//echo '<pre>'; print_r($form); die;
	if (isset($form->_activityTypeId) && $form->_activityTypeId == 13 && $formName == 'CRM_Case_Form_Activity' && (CRM_Utils_Array::value( 'snippet', $_GET ) == 3 || CRM_Utils_Array::value( 'snippet', $_GET ) == 10) )
	{
	    // PDF view rule
	    $form->assign( 'pdf', true );
	    $form->assign( 'aType', $form->_activityTypeId );

	    // add customDataTopUser
	    require_once 'CRM/Contact/BAO/Client.php';
	    CRM_Contact_BAO_Client::buildForm_user_info( $form );

	    // add another missing data
	    //require_once 'CRM/Core/BAO/OptionValue.php';
	    //list( $activityTypeName, $activityTypeDescription ) =
	    //				CRM_Core_BAO_OptionValue::getActivityTypeDetails( $form->_activityTypeId );
	    //$form->assign( 'activityTypeName',        $activityTypeName );
	    //$form->assign( 'activityTypeDescription', $activityTypeDescription );

	    $params = array( 'id' => $form->_activityId );
	    CRM_Activity_BAO_Activity::retrieve( $params, $defaults );
	    $form->assign( 'details', 			array('label' => 'Reason For Contact', 'html' => $defaults['details'] ));
	    $form->assign( 'source_contact_id', array('label' => 'Reported By', 'html' => $defaults['source_contact']) );
	    $form->assign( 'subject', 			array('label' => 'Subject', 'html' => $defaults['subject']) );
	    $form->assign( 'activity_date_time', array('label' => 'Date', 'html' => $defaults['activity_date_time']) );
	    $form->assign( 'rootURL', JURI::root());

	    $groupID  = CRM_Utils_Request::retrieve( 'groupID', 'Positive', $form );

	    $groupTree =& CRM_Core_BAO_CustomGroup::getTree( 'Activity',
		    $form,
		    $form->_activityId,
		    null,
		    $form->_activityTypeId);

	    $groupTree = CRM_Core_BAO_CustomGroup::formatGroupTree( $groupTree, 1, $form );
	    CRM_Core_BAO_CustomGroup::buildQuickForm( $form, $groupTree, false);

	    $defaults = array( );
	    CRM_Core_BAO_CustomGroup::setDefaults( $groupTree, $defaults);

	    // restructure columns Client_Profile
	    $tpl = CRM_Core_Smarty::singleton();
	    $tpl->_tpl_vars['userinfo_groupTree']['Client_Profile']['fields'][1][] = $tpl->_tpl_vars['userinfo_groupTree']['Client_Profile']['fields'][0][1];
	    $tpl->_tpl_vars['userinfo_groupTree']['Client_Profile']['fields'][2][] = $tpl->_tpl_vars['userinfo_groupTree']['Client_Profile']['fields'][0][2];
	    // TODO delete correctly
	    $tpl->_tpl_vars['userinfo_groupTree']['Client_Profile']['fields'][1][0]['value'] = trim(str_replace('United States', '', $tpl->_tpl_vars['userinfo_groupTree']['Client_Profile']['fields'][1][0]['value']));

	    // date format
	    // DOB
	    $date = $tpl->_tpl_vars['userinfo_groupTree']['Client_Profile']['fields'][2][2]['value'];
	    $tpl->_tpl_vars['userinfo_groupTree']['Client_Profile']['fields'][2][2]['value'] = CRM_Utils_Date::customFormat(CRM_Utils_Date::processDate($date, null, false, 'Ymd'));
	    // case opened
	    $date = $tpl->_tpl_vars['userinfo_groupTree']['Client_Profile']['fields'][3][3]['value'];
	    $tpl->_tpl_vars['userinfo_groupTree']['Client_Profile']['fields'][3][3]['value'] = CRM_Utils_Date::customFormat(CRM_Utils_Date::processDate($date, null, false, 'Ymd'));

	    /* ************* collect all group elements (radio, checkbox) and define content  *************** */
	    $tmpArr = array();
	    foreach($form->_elements as $element)
	    {

		if($element->_type == 'group')
		{
		    $val = '';
		    if(isset($element->_elements[0]) && is_object($element->_elements[0]) && $element->_elements[0]->_type == 'radio')
		    {
			$val = 'rb';
		    } elseif(isset($element->_elements[0]) && is_object($element->_elements[0]) && $element->_elements[0]->_type == 'checkbox')
		    {
			$val = 'cb';
		    }

		    foreach($element->_elements as $el)
		    {

			if(isset($defaults[$element->_name]))
			{
			    //canonize
			    //if($defaults[$element->_name] == '0'){$defaults[$element->_name] = 'No';}
			    //if($defaults[$element->_name] == '1'){$defaults[$element->_name] = 'Yes';}
			    //if(isset($defaults[$element->_name]) && $defaults[$element->_name] == 'M'){$defaults[$element->_name] = 'Male';}
			    //if(isset($defaults[$element->_name]) && $defaults[$element->_name] == 'F'){$defaults[$element->_name] = 'Female';}
			    //if(isset($defaults[$element->_name]) && $defaults[$element->_name] == 'eastside'){$defaults[$element->_name] = 'Eastside';}
			    //if(isset($defaults[$element->_name]) && $defaults[$element->_name] == 'westside'){$defaults[$element->_name] = 'Westside';}
			    //if(isset($defaults[$element->_name]) && $defaults[$element->_name] == 'unknown'){$defaults[$element->_name] = 'Unknown';}

			    //echo $element->_name.'==='.$defaults[$element->_name].'==='.$el->_text.'<br>';
			    if(isset($el->_attributes['type']) && $el->_attributes['type'] == 'checkbox')
			    {
				if(isset($defaults[$element->_name]) && array_key_exists($el->_attributes['id'], $defaults[$element->_name]) )
				{
				    $val1 = $val.'ch';
				} else
				{
				    $val1 = $val.'em';
				}
			    }
			    else
			    {
				if(isset($defaults[$element->_name]) && $defaults[$element->_name] == $el->_attributes['value'] )
				{ // $el->_text){
				    $val1 = $val.'ch';
				} else
				{
				    $val1 = $val.'em';
				}
			    }
			    $tmpArr[$element->_name][] = array('text' => $el->_text, // element label
				    'value' => $val1 );  // if check (file name for element pict)
			}
		    }
		}
	    }
//echo '<pre>'; print_r($form->_elements);  die;	
//echo '<pre>'; print_r($defaults);  die;	
	    /******************************* reorganozation key = block name, remove unneeded blocks *******************/

	    foreach($groupTree as $key => $val)
	    {
		if($form->_defaults['subject'] == 'Open MCT Case')
		{
		    if($val['name'] != 'Crisis_Plan_911_Follow_Up' && $val['name'] != 'Linkage_and_Referrals' && $val['name'] != 'Additional_Info')
		    {
			$groupTreeIdx[$val['name']]  = $val;
		    }
		} else
		{
		    $groupTreeIdx[$val['name']]  = $val;
		}
	    }



	    /********************************** fill value for checkbox/radiobutton + set value format (date, state) *********/
	    foreach($groupTreeIdx as $gtkey => $gtval)
	    {

		if($groupTreeIdx[$gtkey]['help_pre'])
		{
		    $groupTreeIdx[$gtkey]['help_pre'] = trim(strip_tags($groupTreeIdx[$gtkey]['help_pre']));
		}

		// if radio or checkbox - add full content
		if(is_array($gtval['fields']))
		{

		    foreach ($gtval['fields'] as $fieldKey => $fieldVal)
		    {

			// value formatting block
			// clean pre help text
			if($fieldVal['help_pre'])
			{
			    $groupTreeIdx[$gtkey]['fields'][$fieldKey]['help_pre'] = trim(strip_tags($fieldVal['help_pre']));
			}

			//set date to word format
			if($fieldVal['data_type'] == 'Date')
			{
			    if($fieldVal['element_value'])
			    {
				$groupTreeIdx[$gtkey]['fields'][$fieldKey]['element_value'] = CRM_Utils_Date::customFormat(CRM_Utils_Date::processDate($fieldVal['element_value'], null, false, 'Ymd'));
			    }
			}
			// set state/province to word format
			if($fieldVal['data_type'] == 'StateProvince')
			{
			    if(intval($fieldVal['element_value']) > 0)
			    {
				$fieldNum = $form->_elementIndex[$fieldVal['element_name']];
				foreach((array)$form->_elements[$fieldNum]->_options as $state)
				{
				    if($state['attr']['value'] == $fieldVal['element_value'])
				    {
					$groupTreeIdx[$gtkey]['fields'][$fieldKey]['element_value'] = $state['text'];
				    }
				}
			    }
			}

			if($fieldVal['html_type'] == 'Radio' || $fieldVal['html_type'] == 'CheckBox')
			{
			    $len = 0;
			    if(isset($tmpArr[$fieldVal['element_name']]))
			    {
				$groupTreeIdx[$gtkey]['fields'][$fieldKey]['element_value'] = $tmpArr[$fieldVal['element_name']];

			    } else
			    {
				$groupTreeIdx[$gtkey]['fields'][$fieldKey]['element_value'] = '';
			    }
			}
			//concat string
			if($fieldVal['html_type'] == 'Multi-Select')
			{
			    $str = '';
			    if(isset($defaults[$fieldVal['element_name']]) && is_array($defaults[$fieldVal['element_name']]))
			    {
				foreach ((array)$defaults[$fieldVal['element_name']] as $mSelect)
				{
				    $str .= $mSelect.' ';
				}
			    }
			    $groupTreeIdx[$gtkey]['fields'][$fieldKey]['element_value'] = $str;
			    //echo $defaults[$fieldVal['element_name']].'<br>';
			}
		    }
		}
	    }

//echo '<pre>'; print_r($groupTreeIdx);  die;
	    /***************************  various blocks formatting ******************************/

	    // trim <p> tag on help msg
	    $groupTreeIdx['Release_of_Information']['help_pre'] = trim(strip_tags($groupTreeIdx['Release_of_Information']['help_pre']));


	    // Format "Linkage and Referrals" block
	    // not for MCT intake
	    if($form->_defaults['subject'] != 'Open MCT Case')
	    {
		require_once 'CRM/Case/XMLProcessor/Report.php';
		$xmlProcessor = new CRM_Case_XMLProcessor_Report( );
		$report       = $xmlProcessor->getActivityInfo( $form->_contactId, $form->_activityId, true );
		$lr_value = array('MH' => '', 'NMH' => '');
		foreach((array)$report['customGroups']['Linkage and Referrals'] as $lr)
		{
		    if($lr['label'] == 'MH')
		    {
			$lr_value['MH'] = $lr['value'];
		    } else if($lr['label'] == 'NMH')
		    {
			$lr_value['NMH'] = $lr['value'];
		    }
		}
		foreach((array)$groupTreeIdx['Linkage_and_Referrals']['fields'] as $key=>$fld)
		{
		    if($fld['label'] == 'MH')
		    {
			$groupTreeIdx['Linkage_and_Referrals']['fields'][$key]['element_value'] = $lr_value['MH'];
		    } elseif($fld['label'] == 'NMH')
		    {
			$groupTreeIdx['Linkage_and_Referrals']['fields'][$key]['element_value'] = $lr_value['NMH'];
		    }
		}
	    }

	    //  for HTML structure columns Client_Information_Field && Client_Information
	    $ClientInfo = array('Client_Information_Field', 'Client_Information');
	    foreach($ClientInfo as $blockName)
	    {
		if(isset($blockName))
		{
		    if(is_array($groupTreeIdx[$blockName]['fields']))
		    {
			$count = count($groupTreeIdx[$blockName]['fields']);
			$groupTreeIdx[$blockName]['collumns'][1] = array_slice(array_keys($groupTreeIdx[$blockName]['fields']), 0, round($count/2));
			$groupTreeIdx[$blockName]['collumns'][2] = array_slice(array_keys($groupTreeIdx[$blockName]['fields']), round($count/2) );
		    }
		}
	    }

	    $form->assign( 'grTree', $groupTreeIdx );
//echo '<pre>'; print_r($groupTreeIdx);  die;
	}

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
	    //print_r($report['customGroups']['Client Information']);
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

    function setDateFormat($dateOld)
    {
	$date =	substr($dateOld, 0, 10);
	$date1= explode('/', $date);
	if(count($date1) == 3)
	{
	    $dateNeed =  $date[2].'-'.$date[0].'-'.$date[1];
	} else
	{
	    $dateNeed =  $date;
	}
	//$groupTreeIdx[$gtkey]['fields'][$fieldKey]['element_value'] = CRM_Utils_Date::customFormat($date);
	return CRM_Utils_Date::customFormat($date);

    }

}
