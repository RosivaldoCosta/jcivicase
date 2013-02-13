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

require_once "CRM/Core/Form.php";
require_once "CRM/Custom/Form/CustomData.php";
/**
 * This class generates form components for OpenCase Activity
 * 
 */
class CRM_Case_Form_Activity_Discharge
{
    /**
     * the id of the client associated with this case
     *
     * @var int
     * @public
     */
    public $_contactID;
    
    static function preProcess( &$form ) 
    {   
        $form->_context   = CRM_Utils_Request::retrieve( 'context', 'String', $form );
        $form->_contactID = CRM_Utils_Request::retrieve( 'cid', 'Positive', $form );
        $form->_caseID = CRM_Utils_Request::retrieve( 'caseid', 'Positive', $form );
        $form->assign( 'context', $form->_context );
    }

   /**
     * This function sets the default values for the form. For edit/view mode
     * the default values are retrieved from the database
     * 
     * @access public
     * @return None
     */
    function setDefaultValues( &$form ) 
    {
	$params = array();
        $defaults = array();
	//$defaults['case_status_id'] = 15;
	
        $params['id'] = $params['contact_id'] = $this->_contactID;
        $client = CRM_Contact_BAO_Contact::retrieve( $params, $defaults, true );

	//$defaults['custom_108_-1'] = 'First Name'; //$client->first_name;
        $atArray = array('activity_type_id' => 13);
        $activities = CRM_Case_BAO_Case::getCaseActivity( $this->_caseID,
                                                                $atArray,
                                                                $this->_contactID );
        $activities = array_keys($activities);
        if( count($activities) > 0 )
        {
                $activities = $activities[0];

                require_once 'CRM/Case/XMLProcessor/Report.php';
                $xmlProcessor = new CRM_Case_XMLProcessor_Report( );
                $report       = $xmlProcessor->getActivityInfo( $this->_contactID, $activities, true );
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
        //$this->assign( 'clientDetails', $client );

        //retrieve details about case
        return $defaults;
    }

    static function buildQuickForm( &$form ) 
    {
        if ( $form->_context == 'caseActivity' ) {
            return;
        }
        if ( $form->_context == 'standalone' ) {
            require_once 'CRM/Contact/Form/NewContact.php';
            CRM_Contact_Form_NewContact::buildQuickForm( $form );
        }
        require_once 'CRM/Core/OptionGroup.php';        
        $caseType = CRM_Core_OptionGroup::values('case_type');
        $form->add('select', 'case_type_id',  ts( 'Case Type' ),  
                   $caseType , true);

        $caseStatus  = CRM_Core_OptionGroup::values('case_status');
        $form->add('select', 'case_status_id',  ts( 'Case Status' ),  
                   $caseStatus , true  );

        $form->add( 'text', 'duration', ts('Duration'),array( 'size'=> 4,'maxlength' => 8 ) );
        $form->addRule('duration', ts('Please enter the duration as number of minutes (integers only).'), 'positiveInteger');  

        require_once "CRM/Contact/BAO/Contact.php";
        if ( $form->_currentlyViewedContactId ) {
            list( $displayName ) = CRM_Contact_BAO_Contact::getDisplayAndImage( $form->_currentlyViewedContactId );
            $form->assign( 'clientName', $displayName );
        }
        
        //$form->addDate( 'start_date', ts('Case Start Date'), true, array( 'formatType' => 'activityDate') );
        
        /*$form->add('select', 'medium_id',  ts( 'Medium' ), 
                   CRM_Core_OptionGroup::values('encounter_medium'), true);
	*/

        // calling this field activity_location to prevent conflict with contact location fields
        //$form->add('text', 'activity_location', ts('Location'), CRM_Core_DAO::getAttribute( 'CRM_Activity_DAO_Activity', 'location' ) );
        
        $form->add('textarea', 'activity_details', ts('Details'), 
                   CRM_Core_DAO::getAttribute( 'CRM_Activity_DAO_Activity', 'details' ) );
        
        $form->addButtons(array( 
                                array ( 'type'      => 'upload', 
                                        'name'      => ts('Save'), 
                                        'isDefault' => true   ), 
                                array ( 'type'      => 'upload',
                                        'name'      => ts('Save and New'), 
                                        'subName'   => 'new' ), 
                                array ( 'type'      => 'cancel', 
                                        'name'      => ts('Cancel') ), 
                                ) 
                          );
    }

    /**
     * Function to process the form
     *
     * @access public
     * @return None
     */
    public function beginPostProcess( &$form, &$params ) 
    {
        if ( $form->_context == 'caseActivity' ) {
            return;
        }

        // set the contact, when contact is selected
        if ( CRM_Utils_Array::value( 'contact_select_id', $params ) ) {
            $params['contact_id'] = CRM_Utils_Array::value( 'contact_select_id', $params );
            $form->_currentlyViewedContactId = $params['contact_id'];
        }
        
        // for open case start date should be set to current date
        //$params['start_date'] = CRM_Utils_Date::processDate( $params['start_date'], date('Hi') );
        require_once 'CRM/Case/PseudoConstant.php';
        $caseStatus = CRM_Case_PseudoConstant::caseStatus( );
        // for resolved case the end date should set to now    
	print_r($caseStatus);

        if ( $params['status_id'] == array_search( 'Closed', $caseStatus ) ) {
            $params['end_date']   = $params['now'];
        }
        
        // rename activity_location param to the correct column name for activity DAO
        //$params['location'] = $params['activity_location'];
    }

    /**
     * global validation rules for the form
     *
     * @param array $values posted values of the form
     *
     * @return array list of errors to be posted back to the form
     * @static
     * @access public
     */
    static function formRule( &$values, $files, &$form ) 
    {
        if ( $form->_context == 'caseActivity' ) {
            return true;
        }

        $errors = array( );
        //check if contact is selected in standalone mode
        
        return $errors;
    }

    /**
     * Function to process the form
     *
     * @access public
     * @return None
     */
    public function endPostProcess( &$form, &$params ) 
    {
        if ( $form->_context == 'caseActivity' ) {
            return;
        }
	//print_r($params);
	
	require_once 'CRM/Case/Form/Activity/ChangeCaseStatus.php';     
	$changeCase = new CRM_Case_Form_Activity_ChangeCaseStatus(); 
	$changeCase->endPostProcess($form,$params);
	//$changeCase->run();

	/* 
        if (!$form->_currentlyViewedContactId   ||
            !$form->_currentUserId        ||
            !$params['case_id'] 
            !$params['case_type']
            ) {
            CRM_Core_Error::fatal('Required parameter missing for OpenCase - end post processing');
        }

        // 1. create case-contact
        $contactParams = array('case_id'    => $params['case_id'],
                               'contact_id' => $form->_currentlyViewedContactId
                               );
        CRM_Case_BAO_Case::addCaseToContact( $contactParams );
    
        // 2. initiate xml processor
        $xmlProcessor = new CRM_Case_XMLProcessor_Process( );
        $xmlProcessorParams = array( 'clientID'           => $form->_currentlyViewedContactId,
                                     'creatorID'          => $form->_currentUserId,
                                     'standardTimeline'   => 1,
                                     'activityTypeName'   => 'Close Case',
                                     'caseID'             => $params['case_id'],
                                     'subject'            => $params['activity_subject'],
                                     'location'           => $params['location'],
                                     'activity_date_time' => $params['start_date'],
                                     'duration'           => $params['duration'],
                                     'medium_id'          => $params['medium_id'],
                                     'details'            => $params['activity_details'],
                                     );

        if ( array_key_exists('custom', $params) && is_array($params['custom']) ) {
            $xmlProcessorParams['custom'] = $params['custom'];
        }

        $xmlProcessor->run( $params['case_type'], $xmlProcessorParams );

        // status msg
        $params['statusMsg'] = ts('Case opened successfully.');
        
        $buttonName = $this->controller->getButtonName( );
        $session =& CRM_Core_Session::singleton( ); 
        if ( $buttonName == $this->getButtonName( 'upload', 'new' ) ) {
            if ( $this->_context == 'standalone' ) {
                $session->replaceUserContext(CRM_Utils_System::url('civicrm/case/add', 
                                                                   'reset=1&action=add&context=standalone') );
            } else {
                $session->replaceUserContext(CRM_Utils_System::url('civicrm/contact/view/case', 
                                                                   "reset=1&action=add&context=case&cid={$form->_contactID}") );
            }            
        }
	*/
    }
}
