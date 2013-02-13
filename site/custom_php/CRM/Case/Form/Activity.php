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

require_once 'CRM/Core/OptionGroup.php';
require_once 'CRM/Core/BAO/File.php';
require_once "CRM/Case/PseudoConstant.php";
require_once "CRM/Case/BAO/Case.php";
require_once 'CRM/Case/XMLProcessor/Process.php';
require_once "CRM/Activity/Form/Activity.php";
require_once 'CRM/Contact/BAO/Contact.php';
require_once 'CRM/Activity/BAO/ActivityAssignment.php';
require_once 'CRM/Utils/Hook.php';
jimport('joomla.error.log');


/**
 * This class create activities for a case
 *
 */

class CRM_Case_Form_Activity extends CRM_Activity_Form_Activity
{

    /**
     * The default variable defined
     *
     * @var int
     */
    public $_caseId;

    /**
     * The default case type variable defined
     *
     * @var int
     */
    public $_caseType;

    /**
     * The default values of an activity
     *
     * @var array
     */
    public $_defaults = array();

    /**
     * The array of releted contact info
     *
     * @var array
     */
    public $_relatedContacts;

    public $_isAutosave = false;

    /**
     * Function to build the form
     *
     * @return None
     * @access public
     */
    function preProcess( )
    {
        $this->_caseId  = CRM_Utils_Request::retrieve( 'caseid', 'Positive', $this );
        $targetId  = CRM_Utils_Request::retrieve( 'targetId', 'Positive', $this );

        $signType = CRM_Utils_Request::retrieve( 'sigtype', 'String', $this );
	if(isset($signType) && $signType === 'flash') 
		$this->assign('isFlash',true);
	else if(isset($signType) && $signType === 'npapi')
		$this->assign('isFlash',false);

        $this->_context = 'caseActivity';
        $this->_crmDir  = 'Case';
        $this->assign( 'context', $this->_context );

        $result = parent::preProcess( );
 
        $newLethality  = CRM_Utils_Request::retrieve( 'newlethality', 'Positive', $this );
        if($newLethality != null)
        {
	        $this->_newLethality  = $newLethality;
	        $this->assign( 'newLethality', $newLethality );
        }

        $isPrint  = CRM_Utils_Request::retrieve( 'print', 'Positive', $this )
        			|| 2 == CRM_Utils_Request::retrieve( 'snippet', 'Positive', $this );
        $this->_isPrint  = $isPrint;

        $this->assign( 'isPrint', $isPrint );

	// add signed forms
	if( $isPrint && $this->_activityId )
	{
		
        	CRM_Core_BAO_File::buildSignedAttachments( $this,
                                            'civicrm_activity',
                                            $this->_activityId );
		$params = array();
		$params['target_id'] = $this->_activityId;
		$signatureData = CRM_Case_BAO_Case::getSignatureData($params);
        	$this->assign( 'embedObjectData', $signatureData );
	}


        $scheduleStatusId = CRM_Core_OptionGroup::getValue('activity_status', 'Scheduled', 'name' );
        $this->assign('scheduleStatusId', $scheduleStatusId);

        if ( $this->_cdType  || $this->_addAssigneeContact || $this->_addTargetContact  ) {
            return $result;
        }

	if ($targetId )
	{
            $this->assign( 'activityId', $targetId );
	}

        if ( !$this->_caseId && $this->_activityId ) {
            $this->_caseId  = CRM_Core_DAO::getFieldValue( 'CRM_Case_DAO_CaseActivity', $this->_activityId,
                                                           'case_id', 'activity_id' );
        }

        if ( $this->_caseId ) {
            $this->assign( 'caseId', $this->_caseId );
        }

        if ( $this->_currentlyViewedContactId ) {
            $this->assign( 'contactId', $this->_currentlyViewedContactId );
        }

	// Lethality
	if( !($this->_activityTypeId == 35 ) )
   	 {
        	if ( !$this->_caseId || (!$this->_activityId && !$this->_activityTypeId) ) 
		{
			//print_r($this);
            		CRM_Core_Error::fatal('required params missing.');
        	}
	}

        $caseType  = CRM_Case_PseudoConstant::caseTypeName( $this->_caseId );
        $this->_caseType  = $caseType['name'];
        $this->assign('caseType', $this->_caseType);
        $this->_caseTypeId  = $caseType['id'];
        $this->assign('caseTypeId', $this->_caseTypeId);

        $clientName = $this->_getDisplayNameById( $this->_currentlyViewedContactId );
        $this->assign( 'client_name', $clientName );
        // set context for pushUserContext and for statusBounce
        $url = CRM_Utils_System::url( 'civicrm/contact/view/case',
                                     "reset=1&action=view&cid={$this->_currentlyViewedContactId}&id={$this->_caseId}&show=1" );

        if ( !$this->_activityId ) {
            if(isset($_POST['id'])) // @TODO ???
        		$this->_activityId = $_POST['id'];

            // check if activity count is within the limit
            $xmlProcessor  = new CRM_Case_XMLProcessor_Process( );
            $activityInst  = $xmlProcessor->getMaxInstance($this->_caseType);

            // If not bounce back and also provide activity edit link
            if ( isset( $activityInst[$this->_activityTypeName] ) ) {
                $activityCount = CRM_Case_BAO_Case::getCaseActivityCount( $this->_caseId, $this->_activityTypeId );
                if ( $activityCount >= $activityInst[$this->_activityTypeName] ) {
                    if ( $activityInst[$this->_activityTypeName] == 1 ) {
                        $atArray = array('activity_type_id' =>
                                         $this->_activityTypeId);
                        $activities =
                            CRM_Case_BAO_Case::getCaseActivity( $this->_caseId,
                                                                $atArray,
                                                                $this->_currentUserId );
                        $activities = array_keys($activities);
                        $activities = $activities[0];
                        $editUrl    =
                            CRM_Utils_System::url( 'civicrm/case/activity',
                                                   "reset=1&cid={$this->_currentlyViewedContactId}&caseid={$this->_caseId}&action=update&id={$activities}" );
                    }
                    CRM_Core_Error::statusBounce( ts("You can not add another '%1' activity to this case. %2",
                                                     array( 1 => $this->_activityTypeName,
                                                            2 => "Do you want to <a href='$editUrl'>edit the existing activity</a> ?" )),
                                                   $url);
                }
            }
        }

	$params = array( 'id' => $this->_caseId );
	$returnProperties = array( 'case_type_id', 'subject', 'status_id', 'start_date' , 'end_date' );
        CRM_Core_DAO::commonRetrieve('CRM_Case_BAO_Case', $params, $values, $returnProperties );

 	if( $values['case_status_id'] == 2)
        {
                $closedBy = CRM_Case_BAO_Case::getCaseClosedBy( $this->_caseId);
                $this->assign('closedBy', $closedBy['name']);
                $this->assign('license', $closedBy['license']);

		$values['case_closed_date'] = $values['end_date'];
		$values['case_status'] = "Closed";
                $this->assign('caseDetails', $values);

        }

	if($this->_activityTypeId == 96)
	{
		$config =& CRM_Core_Config::singleton( );
		$this->assign('sitename', $config->customTemplateFileExtension);
	}


        CRM_Utils_System::setTitle( $this->_activityTypeName );

        $session =& CRM_Core_Session::singleton( );
        $session->pushUserContext( $url );
    }

    	/**
     * This function sets the default values for the form. For edit/view mode
     * the default values are retrieved from the database
     *
     * @access public
     * @return None
     */
    function setDefaultValues( )
    {
        $this->_defaults = parent::setDefaultValues( );

        //return form for ajax
        if ( $this->_cdType  || $this->_addAssigneeContact || $this->_addTargetContact ) {
            return $this->_defaults;
        }
        $depart  = CRM_Utils_Request::retrieve( 'depart', 'String', $this );


	if ( empty($this->_defaults['subject']) )
	{
	  if( $depart)
             $this->_defaults['subject'] = '['.strtoupper($depart).'] ';
	  $this->_defaults['subject'] .= $this->_activityTypeName; //$this->_getDisplayNameById( $this->_currentlyViewedContactId );
	}

        return $this->_defaults;
    }

    public function buildQuickForm( )
    {
        // modify core Activity fields
        $this->_fields['source_contact_id']['label']     = ts('Reported By');

	
	//$this->_fields['status_id']['attributes']        =  array( '' => ts('- select -')) + CRM_Core_PseudoConstant::activityStatus( );
	// ****** End Intern Features ****** //

       
        if ( $this->_caseType ) {
            $xmlProcessor = new CRM_Case_XMLProcessor_Process( );
            $aTypes       = $xmlProcessor->get( $this->_caseType, 'ActivityTypes', true );
		$tasks  = CRM_Core_OptionGroup::values( 'case_tasks', false, false, false); //, $activityCondition );
		//print_r($aTypes);
		//print_r($tasks);

            // remove Open Case activity type since we're inside an existing case
            $openCaseID = CRM_Core_OptionGroup::getValue('activity_type', 'Open Case', 'name' );
            unset( $aTypes[$openCaseID] );
            asort( $aTypes );
            $this->_fields['followup_activity_type_id']['attributes'] =
                array('' => '- select activity type -') + $aTypes;
		
        }

	//echo '<pre>'.print_r($this,true).'</pre>';

        $result = parent::buildQuickForm( );
        if ( $this->_action & ( CRM_Core_Action::DELETE | CRM_Core_Action::DETACH |  CRM_Core_Action::RENEW ) ) {
            return;
        }

        if ( $this->_cdType || $this->_addAssigneeContact || $this->_addTargetContact ) {
            return $result;
        }

        $this->assign( 'urlPath', 'civicrm/case/activity' );

        $this->_relatedContacts = CRM_Case_BAO_Case::getRelatedAndGlobalContacts( $this->_caseId );
        //add case client in send a copy selector.CRM-4438.
        $this->_relatedContacts[] = CRM_Case_BAO_Case::getcontactNames( $this->_caseId );

        if ( ! empty($this->_relatedContacts) ) {
            $checkBoxes = array( );
            foreach ( $this->_relatedContacts as $id => $row ) {
                $checkBoxes[$id] = $this->addElement('checkbox', $id, null, '' );
            }

            $this->addGroup  ( $checkBoxes, 'contact_check' );
            $this->addElement( 'checkbox', 'toggleSelect', null, null,
                               array( 'onclick' => "return toggleCheckboxVals('contact_check',this);" ) );
            $this->assign    ('searchRows', $this->_relatedContacts );
        }

        $depart  = CRM_Utils_Request::retrieve( 'depart', 'String', $this );
	if($depart)
	{
		$this->assign('depart', $depart);
		$this->_depart = $depart;
	}
	//$activityCondition = " AND v.name IN ('Open Case', 'Change Case Type', 'Change Case Status', 'Change Case Start Date')";
        $tasks = CRM_Core_OptionGroup::values( 'case_tasks', false, false, false); //, $activityCondition );
        if ( array_key_exists($this->_activityTypeId, $tasks) )
	{
		$this->assign('isTask', 1);

	}

        $this->addFormRule( array( 'CRM_Case_Form_Activity', 'formRule' ), $this );

         /*   CRM_Core_BAO_File::formatAttachment( $params,
                                                 $params,
                                                 'civicrm_activity' );
	*/

    }


    /**
     * global form rule
     *
     * @param array $fields  the input form values
     * @param array $files   the uploaded files if any
     * @param array $options additional user data
     *
     * @return true if no errors, else array of errors
     * @access public
     * @static
     */
    static function formRule( &$fields, &$files, $self )
    {
        // skip form rule if deleting
        if  ( CRM_Utils_Array::value( '_qf_Activity_next_',$fields) == 'Delete' || CRM_Utils_Array::value( '_qf_Activity_next_',$fields) == 'Restore' ) {
            return true;
        }
        
        if (isset($fields['newlethality']) && $fields['newlethality'])
        {
            return true;
        }
        return parent::formrule( $fields, $files, $self );
    }

    /**
     * Function to process the form
     *
     * @access public
     * @return None
     */
    public function postProcess()
    {

	$log = &JLog::getInstance('civicrm_case_activity.log');

        if ( $this->_action & CRM_Core_Action::DELETE ) {
            $statusMsg = null;

            //block deleting activities which affects
            //case attributes.CRM-4543
            $activityCondition = " AND v.name IN ('Open Case', 'Change Case Type', 'Change Case Status', 'Change Case Start Date')";
            $caseAttributeActivities = CRM_Core_OptionGroup::values( 'activity_type', false, false, false, $activityCondition );

            if ( !array_key_exists($this->_activityTypeId, $caseAttributeActivities) ) {
                $params = array( 'id' => $this->_activityId );
                $activityDelete = CRM_Activity_BAO_Activity::deleteActivity( $params, true );
                if ( $activityDelete ) {
                    $statusMsg = ts('The selected activity has been moved to the Trash. You can view and / or restore deleted activities by checking "Deleted Activities" from the Case Activities search filter (under Manage Case).<br />');
                }
            } else {
                $statusMsg = ts("Selected Activity cannot be deleted.");
            }

            CRM_Core_Session::setStatus( $statusMsg );
            return;
        }

        if ( $this->_action & CRM_Core_Action::RENEW ) {
            $statusMsg = null;
            $params = array( 'id' => $this->_activityId );
            $activityRestore = CRM_Activity_BAO_Activity::restoreActivity( $params );
            if ( $activityRestore ) {
                $statusMsg = ts('The selected activity has been restored.<br />');
            }
            CRM_Core_Session::setStatus( $statusMsg );
            return;
        }

        $newLethality  = CRM_Utils_Request::retrieve( 'newlethality', 'Positive', $this, false, null, 'POST');
        // store the submitted values in an array
        $params = $this->controller->exportValues( $this->_name );
      
		//print_r($params);exit(0);
        if ( $params['source_contact_id'] ) {
            $params['source_contact_id'  ] = $params['source_contact_qid'];
        }


        //set parent id if its edit mode
        if ( $parentId = CRM_Utils_Array::value( 'parent_id', $this->_defaults ) ) {
            $params['parent_id'] = $parentId;
        }


        // required for status msg
        $recordStatus = 'created';

        // store the dates with proper format
        $params['activity_date_time'] = CRM_Utils_Date::processDate( $params['activity_date_time'], $params['activity_date_time_time'] );
        $params['activity_type_id']   = $this->_activityTypeId;
        $params['target_contact_id']  = $this->_currentlyViewedContactId;

	$log->addEntry(array('comment' => __CLASS__.'::'.__FUNCTION__."::Preparing to format custom data:\n".print_r($params,true)));
        // format activity custom data
        if ( CRM_Utils_Array::value( 'hidden_custom', $params ) ) {
            if ( $this->_activityId ) {
                // unset custom fields-id from params since we want custom
                // fields to be saved for new activity.
                foreach ( $params as $key => $value ) {
                    $match = array( );
                    if ( preg_match('/^(custom_\d+_)(\d+)$/', $key, $match) ) {
                        $params[$match[1] . '-1'] = $params[$key];

                        // for autocomplete transfer hidden value instead of label
                        if ( $params[$key] && isset ( $params[$key. '_id'] ) ) {
                            $params[$match[1] . '-1_id'] = $params[$key. '_id'];
                            unset($params[$key. '_id']);
                        }
                        unset($params[$key]);
                    }
                }
            }

            // build custom data getFields array
            $customFields = CRM_Core_BAO_CustomField::getFields( 'Activity', false, false, $this->_activityTypeId );
            $customFields =
                CRM_Utils_Array::crmArrayMerge( $customFields,
                                                CRM_Core_BAO_CustomField::getFields( 'Activity', false, false,
                                                                                     null, null, true ) );
            $params['custom'] = CRM_Core_BAO_CustomField::postProcess( $params,
                                                                       $customFields,
                                                                       $this->_activityId,
                                                                       'Activity' );
            
		//echo '<pre>'.print_r(debug_backtrace(),true).'</pre>';exit(0);
		$log->addEntry(array('comment' => __CLASS__.'::'.__FUNCTION__."::finished formatting custom data: \n".print_r($params['custom'],true)));
        }

        if ( CRM_Utils_Array::value( 'assignee_contact_id', $params ) ) {
            $assineeContacts = explode( ',', $params['assignee_contact_id'] );
            $assineeContacts = array_unique( $assineeContacts );
            unset( $params['assignee_contact_id'] );
        } else {
            $params['assignee_contact_id'] = $assineeContacts = array( );
        }

        if ( isset($this->_activityId) ) {

            // activity which hasn't been modified by a user yet
            if ( $this->_defaults['is_auto'] == 1 ) {
                $params['is_auto'] = 0;
            }

            // always create a revision of an case activity. CRM-4533
            $newActParams = $params;

            // record status for status msg
            $recordStatus = 'updated';
        }

        if ( ! isset($newActParams) ) {
            // add more attachments if needed for old activity
            CRM_Core_BAO_File::formatAttachment( $params,
                                                 $params,
                                                 'civicrm_activity' );

            // call begin post process, before the activity is created/updated.
            $this->beginPostProcess( $params );
            $params['case_id'] = $this->_caseId;
            // activity create/update
            $activity = CRM_Activity_BAO_Activity::create( $params );
            // call end post process, after the activity has been created/updated.
            $this->endPostProcess( $params, $activity );

	    $activity->case_id = $this->_caseId;

            		CRM_Utils_Hook::post( 'create', 'Case Activity', $activity->id, $activity );

		$log->addEntry(array('comment' => "finished creating new activity:\n ".print_r($activity,true)));
        } else { // Update activity
            // since the params we need to set are very few, and we don't want rest of the
            // work done by bao create method , lets use dao object to make the changes
            $params = array('id' => $this->_activityId);
            $params['is_current_revision'] = 0;
            $activity =& new CRM_Activity_DAO_Activity( );
            $activity->copyValues( $params );
            $activity->save( );

		if(isset($_POST['id']))
		{
            		$params = array('id' => $_POST['id']);
            		$params['is_current_revision'] = 0;
            		$activity =& new CRM_Activity_DAO_Activity( );
            		$activity->copyValues( $params );
            		$activity->save( );
				//echo '<pre>'.print_r($activity,true).'</pre>';exit(0);
		}
            		CRM_Utils_Hook::post( 'edit', 'Case Activity', $activity->id, $activity );
        }


        // create a new version of activity if activity was found to
        // have been modified/created by user
        if ( isset($newActParams) ) {

            // set proper original_id
            if ( CRM_Utils_Array::value('original_id', $this->_defaults) ) {
                $newActParams['original_id'] = $this->_defaults['original_id'];
            } else {
                $newActParams['original_id'] = $activity->id;
            }
            //is_current_revision will be set to 1 by default.

            // add attachments if any
            CRM_Core_BAO_File::formatAttachment( $newActParams,
                                                 $newActParams,
                                                 'civicrm_activity' );

            // call begin post process, before the activity is created/updated.
            $this->beginPostProcess( $newActParams );
            $newActParams['case_id'] = $this->_caseId;

		$log->addEntry(array('comment' => "finished copying new activity params with modified values: ".print_r($newActParams,true)));
            $activity = CRM_Activity_BAO_Activity::create( $newActParams );

            // call end post process, after the activity has been created/updated.
            $this->endPostProcess( $newActParams, $activity );

            // copy files attached to old activity if any, to new one,
            // as long as users have not selected the 'delete attachment' option.
            if ( ! CRM_Utils_Array::value( 'is_delete_attachment', $newActParams ) ) {
                CRM_Core_BAO_File::copyEntityFile( 'civicrm_activity', $this->_activityId,
                                                   'civicrm_activity', $activity->id );
            }

            // copy back params to original var
            $params = $newActParams;
	
		$log->addEntry(array('comment' => "finished creating new activity with modified values: ".print_r($activity,true)));

        }


        $params['assignee_contact_id'] = $assineeContacts;
        // update existing case record if needed
        $caseParams       = $params;
        $caseParams['id'] = $this->_caseId;

        if ( CRM_Utils_Array::value('case_type_id', $caseParams ) ) {
            $caseParams['case_type_id'] = CRM_Case_BAO_Case::VALUE_SEPERATOR .
                $caseParams['case_type_id'] . CRM_Case_BAO_Case::VALUE_SEPERATOR;
        }

        if ( CRM_Utils_Array::value('case_status_id', $caseParams) ) {
            $caseParams['status_id'] = $caseParams['case_status_id'];
        }
        
        // unset params intended for activities only
        unset($caseParams['subject'], $caseParams['details'],
              $caseParams['status_id'], $caseParams['custom']);
        $case = CRM_Case_BAO_Case::create( $caseParams );

        // create case activity record
        $caseParams = array( 'activity_id' => $activity->id,
                             'case_id'     => $this->_caseId   );
        	CRM_Case_BAO_Case::processCaseActivity( $caseParams );

		$log->addEntry(array('comment' => "finished processing new case activity:\n ".print_r($caseParams,true)));

	if( $this->_activityTypeId == 96 ) //Signature Capture
	{
        $targetId  = CRM_Utils_Request::retrieve( 'targetId', 'Positive', $this, false, null, 'POST');
		$sigParams = array();
		$sigParams['signature_data'] = CRM_Utils_Request::retrieve( 'SigField', 'String', $this, false, null, 'POST' );
        $keys = array_keys ($_POST);
        $sigParams['signature_type'] = null;
        foreach ($keys as $value)
        {
            if(preg_match('/^custom_1249_/', $value))
            {
                $sigParams['signature_type'] = CRM_Utils_Request::retrieve( $value, 'String', $this, false, null, 'POST' );
                break;
            }
        }
//		$sigParams['signature_type'] = CRM_Utils_Request::retrieve( 'custom_1249_-1', 'String', $this, false, null, 'POST' );
		$sigParams['activity_id'] = $activity->id;
		$sigParams['target_id'] = CRM_Utils_Request::retrieve('targetId', 'Positive', $this);
		$sigParams['file_name_804'] = CRM_Core_BAO_File::getLastSignedAttachment( $this,
                                            'civicrm_activity',
                                            $targetId );
	//	print_r($sigParams);
	//exit(0);
        	CRM_Case_BAO_Case::processSignatureCaptureActivity( $sigParams );
	} else {

	}

	if( $this->_activityTypeId == 16) // Change Case Status
	{
		$caseParams['current_case_status_id'] = $params['current_case_status_id']; 
		$caseParams['case_status_id'] = $params['case_status_id']; 
        	CRM_Case_BAO_Case::processChangeCaseStatusActivity( $caseParams );
		
	}



        // create activity assignee records
        $assigneeParams = array( 'activity_id' => $activity->id );

        if ( !CRM_Utils_Array::crmIsEmptyArray($params['assignee_contact_id']) ) {
            //skip those assignee contacts which are already assigned
            //while sending a copy.CRM-4509.
            $activityAssigned = array_flip( $params['assignee_contact_id'] );
            $activityId       = isset($this->_activityId) ? $this->_activityId : $activity->id;
            $assigneeContacts = CRM_Activity_BAO_ActivityAssignment::getAssigneeNames( $activityId );
            $activityAssigned = array_diff_key( $activityAssigned, $assigneeContacts );

            foreach ( $params['assignee_contact_id'] as $key => $id ) {
                $assigneeParams['assignee_contact_id'] = $id;
                CRM_Activity_BAO_Activity::createActivityAssignment( $assigneeParams );
            }
            //modify assigne_contact as per newly assigned contact before sending copy. CRM-4509.
            $params['assignee_contact_id'] = $activityAssigned;
        }

        // Insert civicrm_log record for the activity (e.g. store the
        // created / edited by contact id and date for the activity)
        // Note - civicrm_log is already created by CRM_Activity_BAO_Activity::create()


        // send copy to selected contacts.
        $mailStatus = '';
        $mailToContacts = array( );

        foreach( array( 'contact_check', 'assignee_contact_id' ) as $val ) {
            if ( array_key_exists ( $val, $params ) && !CRM_Utils_array::crmIsEmptyArray($params[$val]) ) {
                if ( $val == 'contact_check' ) {
                    $mailStatus = ts("A copy of the activity has also been sent to selected contacts(s).");
                } else {
                    $this->_relatedContacts = CRM_Activity_BAO_ActivityAssignment::getAssigneeNames( $activity->id, true, false );
                    $mailStatus .= ' ' .ts("A copy of the activity has also been sent to assignee contacts(s).");
                }
                //build an associative array with unique email addresses.
                foreach( $params[$val] as $id => $dnc ) {
                    if( isset($id) && array_key_exists($id, $this->_relatedContacts) ) {
                        //if email already exists in array then append with ', ' another role only otherwise add it to array.
                        if ( $contactDetails = CRM_Utils_Array::value($this->_relatedContacts[$id]['email'], $mailToContacts) ) {
                            $caseRole = CRM_Utils_Array::value( 'role', $this->_relatedContacts[$id] );
                            $mailToContacts[$this->_relatedContacts[$id]['email']]['role'] = $contactDetails['role'].', '.$caseRole;
                        } else {
                            $mailToContacts[$this->_relatedContacts[$id]['email']] = $this->_relatedContacts[$id];
                        }
                    }
                }
            }
        }

        if ( !CRM_Utils_array::crmIsEmptyArray($mailToContacts) ) {
            //include attachments while sendig a copy of activity.
            $attachments =& CRM_Core_BAO_File::getEntityFile( 'civicrm_activity',
                                                              $activity->id );

            $result = CRM_Case_BAO_Case::sendActivityCopy( $this->_currentlyViewedContactId,
                                                           $activity->id, $mailToContacts, $attachments, $this->_caseId );

            if ( empty($result) ) {
                $mailStatus = '';
            }
        } else {
            $mailStatus = '';
        }

        // create follow up activity if needed
        $followupStatus = '';
        if ( CRM_Utils_Array::value('followup_activity_type_id', $params) ) {
            $followupActivity = CRM_Activity_BAO_Activity::createFollowupActivity( $activity->id, $params );

		

            if ( $followupActivity ) {
                $caseParams = array( 'activity_id' => $followupActivity->id, 'case_id'     => $this->_caseId   );
	
                CRM_Case_BAO_Case::processCaseActivity( $caseParams );
                $followupStatus = ts("A followup activity has been scheduled.");
            }
        }

        if ($this->_activityTypeName == 'Lethality' && $this->_newLethality == '1')
        {
			$atArray = array('activity_type_id' => 13);
			$activities = CRM_Case_BAO_Case::getCaseActivity( $params['case_id'],
																$atArray,
																$params['contact_id'] );
			$activities = array_keys($activities);
			if( count($activities) > 0 )
			{
				$session =& CRM_Core_Session::singleton( );
				$activities = max($activities);
	        	$params['statusMsg'] .= ts(' Proceed...');
                $session->replaceUserContext(CRM_Utils_System::url('civicrm/case/activity', 	// &id=517
                                                                   "reset=1&action=update&cid={$this->_currentlyViewedContactId}&caseid={$params['case_id']}&id={$activities}") );
			}

        }
        else if ($this->_activityTypeName == 'Intake' && $newLethality == '1')
        {
            $atArray = array('activity_type_id' => 13);
            $activities = CRM_Case_BAO_Case::getCaseActivity( $params['case_id'],
                                                                $atArray,
                                                                $params['contact_id'] );
            $activities = array_keys($activities);
            if( count($activities) > 0 )
            {
                $session =& CRM_Core_Session::singleton( );
                $activities = max($activities);
                $params['statusMsg'] .= ts(' Proceed...');
                $session->replaceUserContext(CRM_Utils_System::url('civicrm/case/activity',     // &id=517
                                                                   "reset=1&action=update&cid={$this->_currentlyViewedContactId}&caseid={$params['case_id']}&id={$activities}&newlethality=1") );
            }

        }
        else
        {
        	CRM_Core_Session::setStatus( ts("'%1' activity has been %2. %3 %4",
                                        array(1 => $this->_activityTypeName,
                                              2 => $recordStatus,
                                              3 => $followupStatus,
                                              4 => $mailStatus)) );
        }

    }

	function name()
	{
		return $this->_name;
	}

	static function getValue($activity_id, $groupTitle, $fieldName, $caseID, $contactID)
        {
                $atArray = array('activity_type_id' => $activity_id); // Intake
                $activities = CRM_Case_BAO_Case::getCaseActivity( $caseID, $atArray, $contactID );
                $activities = array_keys($activities);
                if( count($activities) > 0 )
                {
                       $activities = $activities[0];

                       require_once 'CRM/Case/XMLProcessor/Report.php';
                       $xmlProcessor = new CRM_Case_XMLProcessor_Report( );
                       $report       = $xmlProcessor->getActivityInfo( $contactID, $activities, true );

                        if(isset($report["customGroups"]) && is_array($report["customGroups"]))
                        {
                                //print_r($report['customGroups'][$groupTitle]);
                                foreach($report['customGroups'][$groupTitle] as $k => $v)
                                {

                                        if( $v['label'] === $fieldName) return $v['value'];
                                }
                        }
                }

		return null;


        }
}