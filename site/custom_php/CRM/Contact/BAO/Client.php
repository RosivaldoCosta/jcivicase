<?php
/*
 +--------------------------------------------------------------------+
 | CiviCRM version 3.1                                                |
 +--------------------------------------------------------------------+
 | Copyright CiviCRM LLC (c) 2004-2009                                |
 +--------------------------------------------------------------------+
 | This file is a part of CiviCRM.                                    |
 |                                                                    |
 | CiviCRM is free software; you can copy, modify, and distribute it  |
 | under the terms of the GNU Affero General Public License           |
 | Version 3, 19 November 2007.                                       |
 |                                                                    |
 | CiviCRM is distributed in the hope that it will be useful, but     |
 | WITHOUT ANY WARRANTY; without even the implied warranty of         |
 | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.               |
 | See the GNU Affero General Public License for more details.        |
 |                                                                    |
 | You should have received a copy of the GNU Affero General Public   |
 | License along with this program; if not, contact CiviCRM LLC       |
 | at info[AT]civicrm[DOT]org. If you have questions about the        |
 | GNU Affero General Public License or the licensing of CiviCRM,     |
 | see the CiviCRM license FAQ at http://civicrm.org/licensing        |
 +--------------------------------------------------------------------+
*/
/**
 *
 * @package CRM
 * @copyright CiviCRM LLC (c) 2004-2009
 * $Id$
 *
 */

require_once 'CRM/Contact/BAO/Contact.php';

class CRM_Contact_BAO_Client extends CRM_Contact_BAO_Contact
{

    function __construct()
    {
        parent::__construct();
    }


static function buildForm_user_info( &$form )
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
				}
			}

    if(!isset($form->_contactID) && isset($form->_currentlyViewedContactId))
    {
        $form->_contactID = $form->_currentlyViewedContactId;
    }
	if(!$report)
	{
				$params = array();
				$caseid = $form->_caseID.'';

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
		require_once 'CRM/Core/BAO/CustomValueTable.php';
		$customParams = array( 'entityID' => $form->_contactID,
								'custom_280' => true, // Phone
								'custom_805' => true, // Veteran
								'custom_813' => true,  // Race
								'custom_1316' => true,  // Insurance
								'custom_808' => true,
								'custom_811' => true  // County 
								);
		$customfieldValues = CRM_Core_BAO_CustomValueTable::getValues( $customParams );
		if(isset($customfieldValues['is_error']) && $customfieldValues['is_error'] == 0)
		{
			$arrCustomValuesClient['Phone'] = $customfieldValues['custom_280'];
			if($customfieldValues['custom_805'] == 0)
			{
				$arrCustomValuesClient['Veteran'] = ts('No');
			}
			else
			{
				$arrCustomValuesClient['Veteran'] = ts('Yes');
			}

			$arrCustomValuesClient['Race'] = $customfieldValues['custom_813'];
			$arrCustomValuesClient['Insurance'] = $customfieldValues['custom_1316'];
			$arrCustomValuesClient['Age'] = $customfieldValues['custom_808'];
			$arrCustomValuesClient['County'] = $customfieldValues['custom_811'];
		}
		else
		{
			$arrCustomValuesClient['Phone'] = '';
			$arrCustomValuesClient['Veteran'] = '';
			$arrCustomValuesClient['Race'] = '';
			$arrCustomValuesClient['Age'] = '';
			$arrCustomValuesClient['County'] = '';
		}

		$groupTree['Client_Profile']['name'] = 'Client_Profile';
		$groupTree['Client_Profile']['title'] = 'Client Profile';
		$groupTree['Client_Profile']['collapse_display'] = 0;
		$groupTree['Client_Profile']['fields'] = array();

		$params = array('id' => $form->_contactID);
		$defaultsAttr = array();
		$client = CRM_Contact_BAO_Contact::retrieve( $params, $defaultsAttr, true );

		// column 1 @todo Gin
		$caseArr = array('label' => ts('Client'),'value' => $client->first_name . ' ' . $client->last_name, 'type' => 'String');
		$groupTree['Client_Profile']['fields'][0][] = $caseArr;

		$report['customGroups']['Client Information'][10]['label'] = ts('Sex');
	    	$gender =CRM_Core_PseudoConstant::gender();
		$report['customGroups']['Client Information'][10]['value'] = $gender[$client->gender_id];
		$groupTree['Client_Profile']['fields'][0][] = $report['customGroups']['Client Information'][10];

		$report['customGroups']['Client Information'][0]['label'] = ts('Veteran');
		$report['customGroups']['Client Information'][0]['value'] = $arrCustomValuesClient['Veteran'];
		$groupTree['Client_Profile']['fields'][0][] = $report['customGroups']['Client Information'][0];

		$report['customGroups']['Client Information'][0]['label'] = ts('County');
		$report['customGroups']['Client Information'][0]['value'] = $arrCustomValuesClient['County'];
		$groupTree['Client_Profile']['fields'][0][] = $report['customGroups']['Client Information'][0];

		// column 2
		$report['customGroups']['Client Information'][18]['label'] = ts('Address');
		$report['customGroups']['Client Information'][18]['value'] = isset($client->address[1]['display_text'])?$client->address[1]['display_text']:'';
		$groupTree['Client_Profile']['fields'][1][] = $report['customGroups']['Client Information'][18];

		$report['customGroups']['Client Information'][14]['label'] = ts('Race');
		$report['customGroups']['Client Information'][14]['value'] = $arrCustomValuesClient['Race'];
		$groupTree['Client_Profile']['fields'][1][] = $report['customGroups']['Client Information'][13];

		$report['customGroups']['Insurance'][4]['label'] = ts('Insurance');
		$report['customGroups']['Insurance'][4]['value'] = $arrCustomValuesClient['Insurance'];
		$groupTree['Client_Profile']['fields'][1][] = $report['customGroups']['Insurance'][4];

		$report['customGroups']['Financial'][0]['label'] = ts('Employment Status');
		$groupTree['Client_Profile']['fields'][1][] = $report['customGroups']['Financial'][0];

		// column 3
		$report['customGroups']['Client Information'][5]['label'] = ts('Phone Number');
		$report['customGroups']['Client Information'][5]['value'] = isset($client->phone[1]['phone'])?$client->phone[1]['phone']:$arrCustomValuesClient['Phone'];
		$groupTree['Client_Profile']['fields'][2][] = $report['customGroups']['Client Information'][5];

		$allDates = self::GetAllImportantDates($client);
		$report['customGroups']['Client Information'][9]['label'] = ts('Age');
		$report['customGroups']['Client Information'][9]['value'] = $arrCustomValuesClient['Age']; //$allDates['age_display'];
		$groupTree['Client_Profile']['fields'][2][] = $report['customGroups']['Client Information'][9];

		$report['customGroups']['Client Information'][8]['label'] = ts('DOB');
		$report['customGroups']['Client Information'][8]['value'] = CRM_Utils_Date::customFormat($allDates['birth_date_display'],'%m/%d/%Y');
		$groupTree['Client_Profile']['fields'][2][] = $report['customGroups']['Client Information'][8];
		if ( $client->is_deceased )
		{
			$caseArr = array('label' => ts('Deceased'),'value' => CRM_Utils_Date::customFormat($allDates['deceased_date_display'],'%m/%d/%Y'), 'type' => 'String');
			$groupTree['Client_Profile']['fields'][2][] = $caseArr;
		}

		$caseArr = array('label' => ts('Marital Status'),'value' => '', 'type' => 'String');
		$groupTree['Client_Profile']['fields'][2][] = $caseArr;

		//print_r($form);
		// column 4
		//retrieve details about case
                $case1 = new CRM_Case_BAO_Case();
                $case1->id = isset($form->_caseId)?$form->_caseId:$form->_caseID;
                $res = $case1->find(true);
                $case_status = CRM_Utils_Array::value( $case1->status_id, CRM_Case_PseudoConstant::caseStatus());

                // column 4
		
                $caseArr = array('label' => ts('Case Number'),'value' => isset($form->_caseId)?$form->_caseId:$form->_caseID, 'type' => 'String');
                $groupTree['Client_Profile']['fields'][3][] = $caseArr;
                $report['fields'][8]['label'] = ts('Status');
                $groupTree['Client_Profile']['fields'][3][] = array('label' => ts('Status'), 'value' => $case_status, 'type' => 'String');//$report['fields'][8];
                $caseTypes    = CRM_Core_OptionGroup::values( 'case_type' );
                $caseTypesId = CRM_Case_PseudoConstant::caseTypeName( isset($form->_caseId)?$form->_caseId:$form->_caseID );
                $caseArr = array('label' => ts('Case Type'),'value' => $caseTypes[$caseTypesId['id']], 'type' => 'String');
                $groupTree['Client_Profile']['fields'][3][] = $caseArr;


		/*$caseArr = array('label' => ts('Case Number'),'value' => $form->_caseId, 'type' => 'String');
		$groupTree['Client_Profile']['fields'][3][] = $caseArr;
		$report['fields'][8]['label'] = ts('Status');
		// Change case status source
		$report['fields'][8]['value'] = $form->_caseDetails['case_status']; // Case status
		$groupTree['Client_Profile']['fields'][3][] = $report['fields'][8];
		$caseTypes    = CRM_Core_OptionGroup::values( 'case_type' );
		$caseTypesId = CRM_Case_PseudoConstant::caseTypeName( $form->_caseID );
		$caseArr = array('label' => ts('Case Type'),'value' => $caseTypes[$caseTypesId['id']], 'type' => 'String');
		$groupTree['Client_Profile']['fields'][3][] = $caseArr;
		*/
		$report['fields'][6]['label'] = ts('Case Opened');
		$report['fields'][6]['value'] = CRM_Utils_Date::customFormat($report['fields'][6]['value'],'%m/%d/%Y');
		$groupTree['Client_Profile']['fields'][3][] = $report['fields'][6];

		// If Case is Closed
		if (isset($form->_caseDetails['case_status']) && $form->_caseDetails['case_status'] == 'Closed')
		{
			$caseArr = array('label' => ts('Case Closed'),'value' => CRM_Utils_Date::customFormat($form->_caseDetails['case_closed_date'],'%m/%d/%Y'), 'type' => 'String');
			$groupTree['Client_Profile']['fields'][3][] = $caseArr;
		}

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

function GetAllImportantDates($contact)
{
	// Calculating Year difference
    if ( $contact->birth_date ) {
        $birthDate = CRM_Utils_Date::customFormat( $contact->birth_date,'%Y%m%d' );
        $deceasedDate = CRM_Utils_Date::customFormat( $contact->deceased_date,'%Y%m%d' );
        if ( $birthDate < date( 'Ymd' ) ) {
        	if ( $contact->is_deceased )
        	{
            	$age =  self::calculateAgeAll( $birthDate, $deceasedDate );
        	}
        	else
        	{
				$age =  self::calculateAgeAll( $birthDate );
        	}
            $values['age']['y'] = CRM_Utils_Array::value('years',$age);
        	$values['age']['m'] = CRM_Utils_Array::value('months',$age);
        	if($values['age']['y']>0)
        	{
        		$values['age_display'] = $values['age']['y'];
        	}
        	else
        	{
        		$values['age_display'] = $values['age']['m'] . 'm';
        	}
        }
        else
        {
            $values['age_display'] = '';
        }

        list( $values['birth_date'] ) = CRM_Utils_Date::setDateDefaults( $contact->birth_date, 'birth' );
        $values['birth_date_display'] = $contact->birth_date;
    }

    if ( $contact->deceased_date ) {
        list( $values['deceased_date'] ) = CRM_Utils_Date::setDateDefaults( $contact->deceased_date, 'birth' );
        $values['deceased_date_display'] = $contact->deceased_date;
    }
    else
    {
    	$values['deceased_date_display'] = false;
    }
	return $values;
}

    /**
     * Function to calculate Age in Years if greater than one year else in months
     * (It is upgraded taking into account deceased date)
     *
     * @param date $birthDate Birth Date
     * @param date $endDate deceased date
     *
     * @return int array $results contains years or months
     * @access public
     */
    public function calculateAgeAll($birthDate, $endDate = NULL)
    {

        $results = array( );
        if ($endDate)
        {
	        $formatedEndDate  = CRM_Utils_Date::customFormat($endDate,'%Y-%m-%d');
	        $bDate      = explode('-',$formatedEndDate);
	        $endYear  = $bDate[0];
	        $endMonth = $bDate[1];
	        $endDay   = $bDate[2];
        }
        else
        {
	        $endYear  = date("Y");
	        $endMonth = date("m");
	        $endDay   = date("d");
        }

        $formatedBirthDate  = CRM_Utils_Date::customFormat($birthDate,'%Y-%m-%d');

        $bDate      = explode('-',$formatedBirthDate);
        $birthYear  = $bDate[0];
        $birthMonth = $bDate[1];
        $birthDay   = $bDate[2];
        $year_diff  = $endYear - $birthYear;

        // don't calculate age CRM-3143
        if ( $birthYear == '1902' ) {
            return $results;
        }

        switch ($year_diff) {
        case 1:
            $month = (12 - $birthMonth) + $endMonth;
            if ( $month < 12 ) {
                if ($endDay < $birthDay) {
                    $month--;
                }
                $results['months'] =  $month;
            } elseif ( $month == 12 && ($endDay < $birthDay) ) {
                $results['months'] = $month-1;
            } else {
                $results['years'] =  $year_diff;
            }
            break;
        case 0:
            $month = $endMonth - $birthMonth;
            $results['months'] = $month;
            break;
        default:
            $results['years'] = $year_diff;
            if ( ( $endMonth < $birthMonth ) || ( $endMonth == $birthMonth ) && ( $endDay < $birthDay ) ) {
                $results['years']--;
            }
        }

        return $results;
    }

}
