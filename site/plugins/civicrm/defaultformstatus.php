<?php
// No direct access
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');
class plgCivicrmDefaultformstatus extends JPlugin
{

    public function civicrm_buildForm( $formName, &$form )
    {
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

	if(!in_array('Interns',$contactGroups))
	{

	    if ($formName == 'CRM_Case_Form_Activity')
	    {
		$defaults = array();

		if(!$form->_activityId)
		{
		    // Handle setting default values for Status of Case Activities and the label
		    require_once 'CRM/Core/OptionGroup.php';
		    $caseTasks = CRM_Core_OptionGroup::values( 'case_tasks' );
		    $activityStatus = CRM_Core_PseudoConstant::activityStatus( );
		    $aType = CRM_Utils_Array::value( 'atype', $_GET );

		    if(in_array($aType, array_keys($caseTasks)))
		    {
			if (isset($form->_elementIndex['status_id']))
			{
			    $element = $form->getElement('status_id') ;
			    $element->_label = 'Level of Completion';
			    $defaults['status_id'] = array_search( 'Scheduled', $activityStatus );

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

		    $form->setDefaults($defaults);
		    //print_r($defaults);
		}

		//echo '<pre>Default****'.print_r($defaults,true).'</pre>';//exit(0);


	    }

	}
    }

}
