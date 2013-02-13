<?php
// No direct access
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');
class plgCivicrmSetCurrentActivity extends JPlugin
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
    function plgCivicrmSetCurrentActivity(&$subject, $config)
    {
	parent::__construct($subject, $config);
    }

    /**
     *	Post Civicrm Plugin
     */

    public function civicrm_buildForm( $formName, &$form )
    {

	if( $formName == 'CRM_Case_Form_ActivityView')
	{
	    // for hide/show link if plugin is off/on
	    $tpl = CRM_Core_Smarty::singleton();
	    $tpl->assign('changeLast', 1);

	    $setCur = CRM_Utils_Request::retrieve( 'setcur', 'Boolean', CRM_Core_DAO::$_nullObject );
	    if($setCur)
	    {

		$activityID = CRM_Utils_Request::retrieve( 'aid' , 'Integer', $this, true );
		$latestRevisionID = CRM_Utils_Request::retrieve( 'laid' , 'Integer', CRM_Core_DAO::$_nullObject );

		// previous "last revision" set 0
		$params = array('id' => $latestRevisionID);
		$params['is_current_revision'] = 0;
		$activity =& new CRM_Activity_DAO_Activity( );
		$activity->copyValues( $params );
		$activity->save( );

		// current "last revision" set 1
		$params = array('id' => $activityID);
		$params['is_current_revision'] = 1;
		$activity =& new CRM_Activity_DAO_Activity( );
		$activity->copyValues( $params );
		$activity->save( );

		// for highlite right line
		$tpl = CRM_Core_Smarty::singleton();
		$tpl->_tpl_vars['latestRevisionID'] = $activityID;

		// create new activity 'Change Current Version' (activity_type_id=109);
		$session = CRM_Core_Session::singleton();

		$activityParams = array( );
		$activityParams['source_contact_id']  = $session->get( 'userID' );
		$activityParams['activity_type_id']   = CRM_Core_OptionGroup::getValue( 'activity_type', 'Change Current Version', 'name' );

		$activity =& new CRM_Activity_DAO_Activity( );
		$activity->id = $activityID;
		$activity->find(true);
		$activityParams['subject']            = 'Change Current Version: '.$activity->subject.' '.$latestRevisionID.' to '.$activityID;
		$activityParams['activity_date_time'] = date('YmdHis');
		$activityParams['status_id']          = CRM_Core_OptionGroup::getValue( 'activity_status', 'Completed', 'name' );
		$activityParams['priority_id']        = 2;
		$activityParams['is_current_revision']= 1;
		$activityParams['is_auto']            = 0;
		$activityParams['target_contact_id'] = CRM_Utils_Request::retrieve( 'cid' , 'Integer', $this, true );

		require_once 'CRM/Case/DAO/CaseActivity.php';
		$caseActivity =  new CRM_Case_DAO_CaseActivity();
		$caseActivity->activity_id = $activityID;
		$caseActivity->find(true);
		$activityParams['case_id'] = $caseActivity->case_id;

		require_once 'CRM/Activity/BAO/Activity.php';
		$activity = CRM_Activity_BAO_Activity::create( $activityParams );

		// create case-contact
		$contactParams = array('case_id'    => $activityParams['case_id'],
			'contact_id' => $activityParams['target_contact_id']
		);
		CRM_Case_BAO_Case::addCaseToContact( $contactParams );

		// create case activity record
		$caseParams = array( 'activity_id' => $activity->id,
			'case_id'     => $activityParams['case_id'] );

		require_once 'CRM/Case/DAO/CaseActivity.php';
		$caseActivityDAO =& new CRM_Case_DAO_CaseActivity();
		$caseActivityDAO->copyValues( $caseParams );
		$caseActivityDAO->save();
		//die();
	    }
	}
    }


}
