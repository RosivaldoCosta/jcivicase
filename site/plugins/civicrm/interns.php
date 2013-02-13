<?php
// No direct access
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');
class plgCivicrmInterns extends JPlugin
{
    function plgCivicrmInterns(& $subject, $config)
    {
	parent::__construct($subject, $config);
	$options = $this->params->get('options','advanced');
    }

    /**
     * Example store user method
     *
     * Method is called before user data is stored in the database
     *
     * @param       array           holds the old user data
     * @param       boolean         true if a new user is stored
     */
    function civicrm_postProcess( $formName, &$form )
    {

	$errors = array();

	//echo $formName;
	//exit(0);

	if( $formName === 'CRM_Case_Form_Case')
	{

	    if( $form->_activityTypeId == 13)  // Intake/Open Case

	    {
		$values = $form->_submitValues;

		$session = CRM_Core_Session::singleton();
		$userId = $session->get('userID');

		require_once 'CRM/Contact/BAO/GroupContact.php';
		$contactGroupList =& CRM_Contact_BAO_GroupContact::getContactGroup( $userId, 'Added', null, false, false, false );
		$contactGroups = array();
		if ( $contactGroupList )
		{
		    foreach ( $contactGroupList as $group )
		    {
			$contactGroups[ $group['group_id'] ] =  $group['title'];
		    }
		}

		if(in_array('Interns', $contactGroups))
		{
		    $values['status_id'] = 10;

		}

	    }
	}

    }

    public function civicrm_buildForm( $formName, &$form )
    {
	$defaults = array();
	if(isset( $_GET['snippet']) && $_GET['snippet']==2)
	{
	    if(isset($form->_activityId))
	    {
		$params = array( 'id' => $form->_activityId );
		$source = array();
		CRM_Activity_BAO_Activity::retrieve( $params, $source);
		require_once 'CRM/Contact/BAO/GroupContact.php';
		$contactGroupList =& CRM_Contact_BAO_GroupContact::getContactGroup( $source['source_contact_id'], 'Added', null, false, false, false );
		$contactGroups = array();

		if ( $contactGroupList )
		{
		    foreach ( $contactGroupList as $group )
		    {
			$contactGroups[ $group['group_id'] ] =  $group['title'];
		    }
		}

		if(in_array('Interns',$contactGroups))
		{
		    $completedbyField =& $form->add( 'text', 'completedby_contact_id', ts('Completed By') );
		    $completedbyField->freeze();


		    $activities = CRM_Activity_BAO_Activity::getPriorAcitivities($form->_activityId);
		    //echo '<pre>'.print_r($activities,true).'</pre>';

		    if(isset($activities[$form->_activityId]) && $activities[$form->_activityId]['status'] == 2)
		    {
			$defaults['completedby_contact_id'] = $activities[$form->_activityId]['name'];
			//$form->_fields['completedby_contact_id']['attributes'] = array($activities[$form->_activityId]['name']);
			$form->setDefaults($defaults);
		    }
		}

	    }


	}

	if ($formName == 'CRM_Case_Form_Activity')
	{
	    $defaults = array();
	    $session = CRM_Core_Session::singleton();
	    $userId = $session->get('userID');

	    $user = &JFactory::getUser();
	    $contactGroupList =& CRM_Contact_BAO_GroupContact::getContactGroup( $userId, 'Added', null, false, false, false );
	    $contactGroups = array();
	    if ( $contactGroupList )
	    {
		foreach ( $contactGroupList as $group )
		{
		    $contactGroups[ $group['group_id'] ] =  $group['title'];
		}
	    }

	    if(in_array('Interns', $contactGroups))
	    {
		unset($form->_fields['status_id']);
		$status =& $form->add( 'select', 'status_id', ts('Level of Completion'), array('10'=>'Needs Approval',
			'9'=>'Scheduled',
			'2'=>'Completed') );

		$defaults['status_id'] = 10;
		$form->setDefaults($defaults);
		//echo '<pre>Interns****'.print_r($form->_fields,true).'</pre>';

	    } else
	    {
		if($user->get('usertype') === 'Manager')
		{
		    $options = array( '' => ts('- select -')) + CRM_Core_PseudoConstant::activityStatus( );
		    unset($options[11]);
		    $form->_fields['status_id']['attributes']        = $options;
		}
		else
		{
		    $form->_fields['status_id']['attributes']        =  array( '' => ts('- select -')) + CRM_Core_PseudoConstant::activityStatus( );
		}

	    }
	}



    }

}
