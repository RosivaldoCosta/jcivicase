<?php

/*
 +--------------------------------------------------------------------+
 | CiviCRM version 3.4                                                |
 +--------------------------------------------------------------------+
 | Copyright CiviCRM LLC (c) 2004-2011                                |
 +--------------------------------------------------------------------+
 | This file is a part of CiviCRM.                                    |
 |                                                                    |
 | CiviCRM is free software; you can copy, modify, and distribute it  |
 | under the terms of the GNU Affero General Public License           |
 | Version 3, 19 November 2007 and the CiviCRM Licensing Exception.   |
 |                                                                    |
 | CiviCRM is distributed in the hope that it will be useful, but     |
 | WITHOUT ANY WARRANTY; without even the implied warranty of         |
 | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.               |
 | See the GNU Affero General Public License for more details.        |
 |                                                                    |
 | You should have received a copy of the GNU Affero General Public   |
 | License and the CiviCRM Licensing Exception along                  |
 | with this program; if not, contact CiviCRM LLC                     |
 | at info[AT]civicrm[DOT]org. If you have questions about the        |
 | GNU Affero General Public License or the licensing of CiviCRM,     |
 | see the CiviCRM license FAQ at http://civicrm.org/licensing        |
 +--------------------------------------------------------------------+
*/

/**
 *
 * @package CRM
 * @copyright CiviCRM LLC (c) 2004-2011
 *
 */

/**
 * This class contains all the function that are called using AJAX (jQuery)
 */
class CRM_Activity_Page_AJAX
{

    static function getAppointments( )
    {
        $params     = $_POST;

	        $caseID     = CRM_Utils_Type::escape( $_GET['caseID'], 'Integer' );
	        $contactID  = CRM_Utils_Type::escape( $_GET['cid'], 'Integer' );
		$db = &JFactory::getDBO();

	        // get the activities related to given case
		$query = "SELECT a.subject,
			  	(Case a.activity_type_id WHEN 53 THEN 'Physician'
				WHEN 54 THEN 'Therapist'
				WHEN 43 THEN 'UCC Appointment' END) as activity_type,
				DATE_FORMAT(a.activity_date_time, '%m-%e-%Y %H:%i %p') as display_date,
                          	(Case a.status_id WHEN 1 THEN 'Rescheduled'
				WHEN 2 THEN 'Completed'
				WHEN 3 THEN 'Cancelled'
				WHEN 4 THEN 'Kept'
				WHEN 5 THEN 'No Show'
				WHEN 9 THEN 'Scheduled' END) as status,
                          	cl.modified_date as set_date,
                          	IF( a.status_id=2, cl.modified_date,
                        	IF( a.status_id=3, cl.modified_date, NULL)) as completed_date
			FROM civicrm_activity a
			INNER JOIN civicrm_case_activity ca ON a.id=ca.activity_id
			LEFT JOIN civicrm_log cl ON cl.entity_id=ca.activity_id and cl.entity_table = \'civicrm_activity\'
			LEFT JOIN civicrm_log al ON al.entity_id=a.original_id and al.entity_table = \'civicrm_activity\'
			WHERE a.is_deleted=0 and a.activity_type_id in (43,53,54) and ca.case_id=$caseID";

		require_once "CRM/Core/PseudoConstant.php";
        	$activityStatus   = CRM_Core_PseudoConstant::activityStatus( );

       		$db->setQuery($query);
               	$rows = $db->loadAssocList();

	        $page  = CRM_Utils_Array::value( 'page', $_POST );
	        $total = count($rows); //$params['total'];

	        require_once "CRM/Utils/JSON.php";
	        $selectorElements = array( 'subject','activity_type','display_date','status','set_date','completed_date' );
	        echo CRM_Utils_JSON::encodeSelector( $rows, $page, $total, $selectorElements );
        exit();
    }

    static function getCasePhoneContactActivity( )
    {
        $params     = $_POST;

	        $caseID     = CRM_Utils_Type::escape( $_GET['caseID'], 'Integer' );
	        $contactID  = CRM_Utils_Type::escape( $_GET['cid'], 'Integer' );

	        // get the activities related to given case
	        require_once "CRM/Case/BAO/Case.php";
	        $activities = CRM_Case_BAO_Case::getCasePhoneActivity( $caseID, $params, $contactID );

	        $page  = CRM_Utils_Array::value( 'page', $_POST );
	        $total = $params['total'];

	        require_once "CRM/Utils/JSON.php";
	        $selectorElements = array( 'subject','person_contacted','org','phone','display_date','reporter','type', 'links', 'class' );
	        echo CRM_Utils_JSON::encodeSelector( $activities, $page, $total, $selectorElements );
        exit();
    }

/*
    static function getCaseActivity( )
    {
        $caseID    = CRM_Utils_Type::escape( $_GET['caseID'], 'Integer' );
        $contactID = CRM_Utils_Type::escape( $_GET['cid'], 'Integer' );
        $userID    = CRM_Utils_Type::escape( $_GET['userID'], 'Integer' );
        $context   = CRM_Utils_Type::escape( CRM_Utils_Array::value( 'context', $_GET ), 'String' );

        $sortMapper  = array( 0 => 'display_date', 1 => 'ca.subject', 2 => 'ca.activity_type_id',
                              3 => 'acc.sort_name', 4 => 'cc.sort_name', 5 => 'ca.status_id'  );

        $sEcho       = CRM_Utils_Type::escape($_REQUEST['sEcho'], 'Integer');
        $offset      = isset($_REQUEST['iDisplayStart'])? CRM_Utils_Type::escape($_REQUEST['iDisplayStart'], 'Integer'):0;
        $rowCount    = isset($_REQUEST['iDisplayLength'])? CRM_Utils_Type::escape($_REQUEST['iDisplayLength'], 'Integer'):25;
        $sort        = isset($_REQUEST['iSortCol_0'] )? CRM_Utils_Array::value( CRM_Utils_Type::escape($_REQUEST['iSortCol_0'],'Integer'), $sortMapper ): null;
        $sortOrder   = isset($_REQUEST['sSortDir_0'] )? CRM_Utils_Type::escape($_REQUEST['sSortDir_0'], 'String'):'asc';

        $params    = $_POST;
        if ( $sort && $sortOrder ) {
            $params['sortname']  = $sort;
            $params['sortorder'] = $sortOrder;
        }
        $params['page'] = ($offset/$rowCount) + 1;
        $params['rp']   = $rowCount;

        // get the activities related to given case
        require_once "CRM/Case/BAO/Case.php";
        $activities = CRM_Case_BAO_Case::getCaseActivity( $caseID, $params, $contactID, $context, $userID );

        require_once "CRM/Utils/JSON.php";
        $iFilteredTotal = $iTotal =  $params['total'];
        $selectorElements = array( 'display_date', 'subject', 'type', 'with_contacts', 'reporter', 'status', 'links', 'class' );

        echo CRM_Utils_JSON::encodeDataTableSelector( $activities, $sEcho, $iTotal, $iFilteredTotal, $selectorElements );
        CRM_Utils_System::civiExit( );

    }
*/
    static function getCaseActivity( )
    {
    //ini_set('display_errors', 0);
    //error_reporting(0);
        $log		= CRM_Utils_Array::value( 'log', $_GET );
        $params     = $_POST;

        if(!$log)
        {
	        $caseID     = CRM_Utils_Type::escape( $_GET['caseID'], 'Integer' );
	        $contactID  = CRM_Utils_Type::escape( $_GET['cid'], 'Integer' );

	        // get the activities related to given case
	        require_once "CRM/Case/BAO/Case.php";
	        $activities = CRM_Case_BAO_Case::getCaseActivity( $caseID, $params, $contactID );

	        CRM_Utils_Hook::caseHistory( $activities );

	        $page  = CRM_Utils_Array::value( 'page', $_POST );
	        $total = $params['total'];

	        require_once "CRM/Utils/JSON.php";
	        //$selectorElements = array( 'display_date','assigned_date', 'completed_date',  'subject', 'type', 'reporter', 'status', 'links', 'class' );
	        $selectorElements = array( 'assigned_date','subject','display_date','reporter','status','completed_contact','completed_date', 'links', 'class' );
	        echo CRM_Utils_JSON::encodeSelector( $activities, $page, $total, $selectorElements );
        }
        else
        {
        	self::getCaseActivityCustom($params);
        }
        exit();
    }

    static function convertToCaseActivity()
    {
        $params = array( 'caseID', 'activityID', 'contactID', 'newSubject', 'targetContactIds', 'mode' );
        foreach ( $params as $param ) {
            $$param = CRM_Utils_Array::value( $param, $_POST );
        }

        if ( !$activityID || !$caseID ) {
            echo json_encode( array('error_msg' => 'required params missing.' ) );
            CRM_Utils_System::civiExit( );
        }

        require_once "CRM/Activity/DAO/Activity.php";
        $otherActivity = new CRM_Activity_DAO_Activity();
        $otherActivity->id = $activityID;
        if ( !$otherActivity->find( true ) ) {
            echo json_encode( array('error_msg' => 'activity record is missing.' ) );
            CRM_Utils_System::civiExit( );
        }
        $actDateTime = CRM_Utils_Date::isoToMysql( $otherActivity->activity_date_time );

        //create new activity record.
        $mainActivity = new CRM_Activity_DAO_Activity( );
        $mainActVals  = array( );
        CRM_Core_DAO::storeValues( $otherActivity, $mainActVals );

        //get new activity subject.
        if ( !empty( $newSubject ) ) $mainActVals['subject'] = $newSubject;

        $mainActivity->copyValues( $mainActVals );
        $mainActivity->id = null;
        $mainActivity->activity_date_time = $actDateTime;
        //make sure this is current revision.
        $mainActivity->is_current_revision = true;
        //drop all relations.
        $mainActivity->parent_id = $mainActivity->original_id = null;

        $mainActivity->save( );
        $mainActivityId = $mainActivity->id;
        require_once 'CRM/Activity/BAO/Activity.php';
        CRM_Activity_BAO_Activity::logActivityAction( $mainActivity );
        $mainActivity->free( );

        //mark previous activity as deleted.
        if ( in_array( $mode, array( 'move', 'file' ) ) ) {
            $otherActivity->activity_date_time = $actDateTime;
            $otherActivity->is_deleted = 1;
            $otherActivity->save( );
        }
        $otherActivity->free( );

        require_once "CRM/Activity/BAO/Activity.php";
        $targetContacts = array( );
        if ( !empty( $targetContactIds ) ) {
            $targetContacts = array_unique( explode( ',', $targetContactIds ) );
        }
        foreach ( $targetContacts as $key => $value ) {
            $params = array( 'activity_id' => $mainActivityId,
                             'target_contact_id' => $value );
            CRM_Activity_BAO_Activity::createActivityTarget( $params );
        }

        //attach newly created activity to case.
        require_once "CRM/Case/DAO/CaseActivity.php";
        $caseActivity = new CRM_Case_DAO_CaseActivity( );
        $caseActivity->case_id     = $caseID;
        $caseActivity->activity_id = $mainActivityId;
        $caseActivity->save( );
        $error_msg = $caseActivity->_lastError;
        $caseActivity->free( );

        // attach custom data to the new activity
        require_once 'CRM/Core/BAO/CustomValueTable.php';
        require_once 'CRM/Core/BAO/File.php';
        $customParams = $htmlType = array( );
        $customValues = CRM_Core_BAO_CustomValueTable::getEntityValues( $activityID, 'Activity' );

        if ( !empty( $customValues ) ) {
            $fieldIds = implode( ', ', array_keys( $customValues ) );
            $sql      = "SELECT id FROM civicrm_custom_field WHERE html_type = 'File' AND id IN ( {$fieldIds} )";
            $result   = CRM_Core_DAO::executeQuery( $sql );

            while ( $result->fetch( ) ) {
                $htmlType[] = $result->id;
            }

            foreach ( $customValues as $key => $value ) {
                if ( $value ) {
                    if ( in_array( $key, $htmlType ) ) {
                        $fileValues = CRM_Core_BAO_File::path( $value, $activityID );
                        $customParams["custom_{$key}_-1"] = array( 'name' => $fileValues[0],
                                                                   'path' => $fileValues[1] );
                    } else {
                        $customParams["custom_{$key}_-1"] = $value;
                    }
                }
            }

            CRM_Core_BAO_CustomValueTable::postProcess( $customParams, CRM_Core_DAO::$_nullArray,
                                                        'civicrm_activity',
                                                        $mainActivityId, 'Activity' );

        }

        // copy activity attachments ( if any )
        require_once "CRM/Core/BAO/File.php";
        CRM_Core_BAO_File::copyEntityFile( 'civicrm_activity', $activityID, 'civicrm_activity', $mainActivityId );

        echo json_encode(array('error_msg' => $error_msg));
        CRM_Utils_System::civiExit( );
    }

    static function getContactActivity( )
    {
ini_set('display_errors', 0);
error_reporting(0);
        $contactID = CRM_Utils_Type::escape( $_POST['contact_id'], 'Integer' );
        $context   = CRM_Utils_Type::escape( CRM_Utils_Array::value( 'context', $_GET ), 'String' );

        $sortMapper  = array( 0 => 'activity_type', 1 => 'subject', 2 => 'source_contact_name',
                              3 => '', 4 => '', 5 => 'activity_date_time', 6 => 'status_id'  );

        $sEcho       = CRM_Utils_Type::escape($_REQUEST['sEcho'], 'Integer');
        $offset      = isset($_REQUEST['iDisplayStart'])? CRM_Utils_Type::escape($_REQUEST['iDisplayStart'], 'Integer'):0;
        $rowCount    = isset($_REQUEST['iDisplayLength'])? CRM_Utils_Type::escape($_REQUEST['iDisplayLength'], 'Integer'):25;
        $sort        = isset($_REQUEST['iSortCol_0'] )? CRM_Utils_Array::value( CRM_Utils_Type::escape($_REQUEST['iSortCol_0'],'Integer'), $sortMapper ): null;
        $sortOrder   = isset($_REQUEST['sSortDir_0'] )? CRM_Utils_Type::escape($_REQUEST['sSortDir_0'], 'String'):'asc';

        $params    = $_POST;
        if ( $sort && $sortOrder ) {
            $params['sortBy']  = $sort . ' '. $sortOrder;
        }

        $params['page'] = ($offset/$rowCount) + 1;
        $params['rp']   = $rowCount;

        $params['contact_id'] = $contactID;
        $params['context'   ] = $context;

        // get the contact activities
        require_once 'CRM/Activity/BAO/Activity.php';
        $activities = CRM_Activity_BAO_Activity::getContactActivitySelector( $params );

        require_once "CRM/Utils/JSON.php";
        $iFilteredTotal = $iTotal =  $params['total'];
        $selectorElements = array( 'activity_type', 'subject', 'source_contact',
                                   'target_contact', 'assignee_contact',
                                   'activity_date', 'status', 'links', 'class' );

        echo CRM_Utils_JSON::encodeDataTableSelector( $activities, $sEcho, $iTotal, $iFilteredTotal, $selectorElements );
        CRM_Utils_System::civiExit( );
    }

    static function getCaseActivityCustom( $post )
    {
    	require_once "CRM/Case/BAO/Case.php";
		$paramsDate = array('rp' => (int)$post['rp']);
		$show_date = (int)$post['show_date'];

        $civicrm_activity = array();

        $db = &JFactory::getDBO();


        // get module settings

        $show_date    = (int) $post[ 'show_date' ];

        $show_filter  = (int) $post[ 'show_filter' ];

        $dformat      = $post[ 'dformat' ];

        $cf           = (int) $post['ualog_filter_id'];

        $cfo          = $post['ualog_filter_option'];

        $conf_name	  = $post['conf_name'];

        $f = "";

        $user			=& JFactory::getUser();
        $user_id 		= $user->get('id');
        $user_usertype 	= $user->get('usertype');
        $isOut = false;

        $session =& CRM_Core_Session::singleton( );
        $user_civicrm_id = $session->get( 'userID' );

        $allRows = array();
        // rows activities to out

        $activityTypeIDs = array();
        //// Activity type
        //// Selective types of the activities - uncomment this
        //$civicrm_activity_type = array( 'Change Case Type',
        //								'Intake',
        //								'IHIT Visit',
        //								'Change Case Status',
        //								'Intake Form'
        //							   );
        //if(isset($civicrm_activity_type) && count($civicrm_activity_type)>0)
        //{
        //	foreach ($civicrm_activity_type as $civicrm_activity_name)
        //	{
        //		if($activityTypeID = CRM_Core_OptionGroup::getValue( 'activity_type', $civicrm_activity_name, 'label' ))
        //		{
        //			$activityTypeIDs[] = $activityTypeID;
        //		}
        //		else
        //		{
        //		}
        //	}
        //	if(isset($activityTypeIDs) && count($activityTypeIDs)>0)
        //	{
        //		$paramsDate['activity_type_id'] = implode(",", $activityTypeIDs);
        //	}
        //}

        $civicrm_activity = array();

        switch ($user_usertype)
        {
        	case 'Super Administrator':
        		// Output info about activity
        		$isOut = true;

        		// get logged items
        		if($show_filter && ($cf || $cfo)) {
        		    if($cf) {
        		        $f = "\n WHERE u.id = '$cf'";
        		        if($cfo) {
        		            $f.= "\n AND l.option = ".$db->quote($cfo);
        		        }
        		    }
        		    else {
        		        if($cfo) {
        		            $f = "\n WHERE l.option = ".$db->quote($cfo);
        		        }
        		    }
        		}

        		$query = "SELECT l.*, ". $conf_name ." FROM #__ualog AS l"
        		       . "\n RIGHT JOIN #__users AS u ON u.id = l.user_id"
        		       . $f
        		       . "\n ORDER BY l.cdate DESC LIMIT ". (int)$post['rp'];
        		       $db->setQuery($query);
        		       $rows = $db->loadObjectList();

        		// CiviCRM activities
        		$civicrm_activity = CRM_Case_BAO_Case::getCasesActivityCustom( 'all', $paramsDate, $user_civicrm_id);

        		// Joomla activities
        		foreach($rows AS $row)
        		{
        		    if(!$row->action_title) {
        		        continue;
        		    }
        		    JFilterOutput::objectHTMLSafe($row);
        		    $row->action_title = JText::_($row->action_title);
        		    $row->action_title = str_replace('{user}', "<a href='index.php?option=com_users&view=user&task=edit&cid[]=$row->user_id'>$row->name</a>", $row->action_title);
        		    if($row->action_link) {
        		        $ua_link = "<a href='$row->action_link'>$row->item_title</a>";
        		        $row->action_title = str_replace('{link}', $ua_link, $row->action_title);
        		    }
        		    else {
        		        $row->action_title = str_replace('{link}', $row->item_title, $row->action_title);
        		    }
        		    $echo =   "<li>";
        		    $echo .=   $row->action_title;
        		    if($show_date) { $echo .=   "<br/><small class='small'>".date($dformat, $row->cdate)."</small>"; }
        		    $echo .=   "</li>";
        			$allRows[date('YmdHis23', $row->cdate)] = $echo;
        		}
        //fb($allRows);
        		break;

        	case 'Administrator':
        		// Output info about activity
        		$isOut = true;

        		// CiviCRM activities
        		$civicrm_activity = CRM_Case_BAO_Case::getCasesActivityCustom( 'all', $paramsDate, $user_civicrm_id);
        		break;

        	case 'Manager':
        //		require_once 'CRM/Contact/BAO/GroupContact.php';
        //		$groupList = CRM_Contact_BAO_GroupContact::getGroupList();
        //		$groupListIds = array();
        //		foreach ( array_keys($groupList) as $key => $val)
        //		{
        //			$groupListIds[$val] = 1;
        //		}
        //        $paramsGr['group'] = $groupListIds;
        //        $paramsGr['return.contact_id'] = 1;
        //        $paramsGr['offset']            = 0;
        //        $paramsGr['rowCount']          = 0;
        //        $paramsGr['sort']              = null;
        //        $paramsGr['smartGroupCache']   = false;
        //
        //        require_once 'api/v2/Contact.php';
        //        $contacts = civicrm_contact_search( $paramsGr );
        //        if(isset($contacts) && count($contacts)>0)
        //		{
        //			$listIds = implode(",", array_keys($contacts));
        //			$sql = "SELECT DISTINCT case_id FROM civicrm_relationship WHERE (contact_id_a IN ( $listIds ) OR contact_id_b IN ( $listIds )) AND case_id IS NOT NULL";
        //		}
        //
        //		// CiviCRM activities
        //		$civicrm_activity = CRM_Case_BAO_Case::getCasesActivityCustom( $sql, $paramsDate, $user_civicrm_id);
        //		break;
        	default:
        }

        if($isOut)
        {
        	// CiviCRM activities
        	foreach($civicrm_activity AS $rowc)
        	{
        		 $echo =  "<li><span class='" . $rowc['class'] . "'";
        		 $echo .=   $rowc['reporter'] . ' | ' . $rowc['type'] . " | " . $rowc['status'] . " | " . $rowc['subject'];
        	     if($show_date) { $echo .=   "<br/> <small class='small'>" . $rowc['display_date'] . "</small>"; }
        	     $echo .=   "</span></li>";
        	     $allRows[$rowc['date'].'34'] = $echo;
        	}

        	// start item output
        	echo "<ul class='bullet1'>";

        	if(!isset($allRows) || !count($allRows)) {
        	    echo "<li>".JText::_("No activity")."</li>";
        	    $rows = array();
        	}
        	else
        	{
        		krsort($allRows);
        	}

        	foreach($allRows AS $row)
        	{
        		echo $row;
        	}

        	echo "</ul>";
        }
    }

}
