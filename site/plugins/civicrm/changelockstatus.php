<?php
// No direct access
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');
class plgCivicrmChangelockstatus extends JPlugin
{
    function plgCivicrmChangelockstatus(& $subject, $config)
    {
	parent::__construct($subject, $config);

	// load plugin parameters
	$this->_plugin = JPluginHelper::getPlugin( 'civicrm', 'changelockstatus' );
	$this->_params = new JParameter( $this->_plugin->params );
    }

    /**
     * Example store user method
     *
     * Method is called after user data is stored in the database
     *
     * @param       array           holds the old user data
     * @param       boolean         true if a new user is stored
     */
    function civicrm_post( $op, $objectName, $objectId, &$objectRef )
    {

	jimport('civicrm.case');

	_civicrm_initialize();

	$activityCustomField = 'custom_'.$this->_params->get('activity_id_field');
	$lockstatusField = 'custom_'.$this->_params->get('lockstatus_field');
	$autolockCustomField = 'custom_'.$this->_params->get('autolock_field');
	$lockstatus_activity_type = $this->_params->get('lockstatus_activity_type');

	if(($op === 'create' && $objectName === 'Case Activity') || ($op === 'create' && $objectName === 'Activity' && $objectRef->activity_type_id==13))
	{


	    $params = array(        'activity_type_id'      => $lockstatus_activity_type,
		    'source_contact_id'     => $objectRef->source_contact_id,
//                                        'target_contact_id'     => $objectRef->target_contact_id,
		    'status_id'             => 2,
		    'medium_id'             => '1',
		    'case_id'		=> $objectRef->case_id,
		    'subject'               => 'Change Lock Status',
		    'activity_date_time'    => date('YmdHis'),
		    $activityCustomField         => $objectRef->id,
		    $lockstatusField       => '0',
		    $autolockCustomField 	=> '0');
	    if(isset($objectRef->target_contact_id))
	    {
		$params['target_contact_id'] = $objectRef->target_contact_id;
	    }

	    $new_activity = civicrm_case_activity_create( $params );
	    //echo '<pre>'.print_r($objectRef,true).'</pre>';
	    //echo '<pre>'.print_r($new_activity,true).'</pre>';

	    // If Case Activity add a record to the locked status table with locked status
	    //exit(0);

	}
	else if(($op === 'edit' && $objectName === 'Case Activity') || ($op === 'edit' && $objectName === 'Activity' && $objectRef->activity_type_id==13))
	{
	    $lockColumn = 'locked_'.$this->_params->get('lockstatus_field');
	    $activityField = 'activity_id_'.$this->_params->get('activity_id_field');
	    $autolockField = 'auto_lock_on_next_edit_'.$this->_params->get('autolock_field');
	    $lockstatus_table = 'civicrm_value_lock_status_'.$this->_params->get('lockstatus_table');

	    $db = &JFactory::getDBO();

	    $query = 'SELECT original_id FROM civicrm_activity where id='.$objectRef->id;
	    $db->setQuery($query);
	    $originalId = $db->loadResult();


	    $query = 'SELECT '.$lockColumn.' as locked,'.$autolockField.' as autolock FROM '.$lockstatus_table. ' WHERE '.$activityField.'=';

	    if($originalId)
		$query .= $originalId;
	    else
		$query .= $objectRef->id;

	    $db->setQuery($query);

	    $row = $db->loadRow();

	    //echo '<pre>'.$query.'</pre>';
	    //echo '<pre>'.print_r($row,true).'</pre>';
	    if($row[0] == 0 && $row[1] == 1)
	    {
		$params = array(        'activity_type_id'      => $lockstatus_activity_type,
			'source_contact_id'     => 91, //$objectRef->source_contact_id,
			'target_contact_id'     => $objectRef->target_contact_id,
			'status_id'             => '2',
			'medium_id'             => '1',
			'case_id'		=> $objectRef->case_id,
			'subject'               => 'Change Lock Status',
			'activity_date_time'    => date('YmdHis'),
			$activityCustomField         => $objectRef->id,
			$lockstatusField       => '1',
			$autolockCustomField	=> '1');


		$query = 'UPDATE '.$lockstatus_table . ' SET '.$lockColumn.'=1 WHERE '.$autolockField.'=1 AND '.$activityField.'=';
		if($originalId)
		    $query .= $originalId;
		else
		    $query .= $objectRef->id;

		$db->setQuery($query);

		$result = $db->loadResult();

		echo '<pre>'.print_r($query,true).'</pre>';
		echo '<pre>'.$originalId.'</pre>';
		$new_activity = civicrm_case_activity_create( $params );
		//echo '<pre>OBJECT '.print_r($objectRef,true).'</pre>';
		//exit(0);
	    }
	    /*else
				{
					$params = array(        	
					'activity_type_id'      => $lockstatus_activity_type,
                                        'source_contact_id'     => 91, //$objectRef->source_contact_id,
                                        'target_contact_id'     => $objectRef->target_contact_id,
                                        'status_id'             => '2',
                                        'medium_id'             => '1',
					'case_id'		=> $objectRef->case_id,
                                        'subject'               => 'Change Lock Status',
                                        'activity_date_time'    => date('YmdHis'),
                                         $activityCustomField         => $objectRef->id,
                                         $lockstatusField       => '0',
					 $autolockCustomField	=> '0');

				}*/


	    //echo '<pre> New activity'.print_r($new_activity,true).'</pre>';
	    //echo '<pre> ObjectRef'.print_r($objectRef,true).'</pre>';

	} else if( $op === 'create' && $objectName === 'Activity' && $objectRef->activity_type_id==35 && !$objectRef->original_id)
	{

	    $params = array(
		    'activity_type_id'      => $lockstatus_activity_type,
		    'source_contact_id'     => $objectRef->source_contact_id,
		    'target_contact_id'     => $objectRef->target_contact_id,
		    'status_id'             => 2,
		    'medium_id'             => '1',
		    'case_id'				=> $objectRef->case_id,
		    'subject'               => 'Change Lock Status',
		    'activity_date_time'    => date('YmdHis'),
		    $activityCustomField   	=> $objectRef->id,
		    $lockstatusField        => '1',
		    $autolockCustomField 	=> '1');

	    $new_activity = civicrm_case_activity_create( $params );

	}


    }


}
