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
require_once "CRM/Contact/Form/Task.php";
jimport('joomla.plugin.plugin');

/**
 * This class generates form components for OpenCase Activity
 *
 */
class CRM_Case_Form_Activity_ChangeLockStatus 
{

    var $_targetActivityID;
    var $_caseID;
    var $lockstatus_field;
    var $lockstatus_table;
    var $activity_field;
    var $autolock_field;

    function preProcess( &$form )
    {
        $plugin = JPluginHelper::getPlugin( 'civicrm', 'changelockstatus' );
        $lockstatus_params = new JParameter( $plugin->params );
	$this->lockstatus_field = $lockstatus_params->get('lockstatus_field');
	$this->lockstatus_table = $lockstatus_params->get('lockstatus_table');
	$this->activity_field = $lockstatus_params->get('activity_id_field');
	$this->autolock_field = $lockstatus_params->get('autolock_field');

	$this->_targetActivityID = CRM_Utils_Request::retrieve( 'target_activity_id', 'Positive', $this);
	
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

        $defaults = array();
        // Retrieve current case status
	$query = 'select locked_'.$this->lockstatus_field.' as locked from civicrm_value_lock_status_'.$this->lockstatus_table.' where activity_id_'.$this->activity_field.'='.$this->_targetActivityID;
	$results = array();
        $queryParam = array();
        $dao = CRM_Core_DAO::executeQuery($query,$queryParam);
        $dao->fetch();
	
	$defaults['status_id'] = 2;
	$defaults['lock_status_id'] = $dao->locked;
        return $defaults;
    }

    function buildQuickForm( &$form )
    {
        require_once 'CRM/Core/OptionGroup.php';

	$acl =& JFactory::getACL();
	$user = JFactory::getUser();
	$lockStatus = array('1' => 'Locked', '0' => 'Unlocked');

        $form->add('select', 'lock_status_id',  ts( 'Lock Status' ),
                    $lockStatus , true  );

        /*$element = $currentStatus = $form->add('select', 'current_lock_status_id',  ts( 'Current Lock Status' ), $lockStatus , false);
	$currentStatus->freeze();
	*/
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
        return true;
    }

    /**
     * Function to process the form
     *
     * @access public
     * @return None
     */
    public function beginPostProcess( &$form, &$params )
    {
        //$params['id'] = $params['case_id'];
    }

    /**
     * Function to process the form
     *
     * @access public
     * @return None
     */
    public function endPostProcess(&$form, &$params, $activity )
    {

        // Set case end_date if we're closing the case. Clear end_date if we're (re)opening it.
	// since the params we need to set are very few, and we don't want rest of the
	$targetActivity = CRM_Utils_Request::retrieve( 'target_activity_id', 'Positive', $form );


	$query = "SELECT id FROM civicrm_value_lock_status_".$this->lockstatus_table ." WHERE activity_id_".$this->activity_field." =".$this->_targetActivityID;
	echo '<pre>'.$query.'</pre>';
	//echo '<pre>'.print_r($activity,true).'</pre>';exit(0);
	$results = array();
	$queryParam = array();
        $dao = CRM_Core_DAO::executeQuery($query,$queryParam);
        $dao->fetch();

	if($dao->N >= 1)
	{
		$query = "UPDATE civicrm_value_lock_status_".$this->lockstatus_table .
		 " SET locked_".$this->lockstatus_field ."=".$form->_submitValues['lock_status_id'].  
		 " ,auto_lock_on_next_edit_".$this->autolock_field ."=1".
		 " WHERE activity_id_".$this->activity_field ."=".$this->_targetActivityID;
	}
	else
	{

		$query = "INSERT INTO civicrm_value_lock_status_".$this->lockstatus_table .
		 " (entity_id,activity_id_".$this->activity_field .",locked_".$this->lockstatus_field.",auto_lock_on_next_edit_".$this->autolock_field .") VALUES (".$activity->id.",".$this->_targetActivityID.",".$form->_submitValues['lock_status_id'] .",1)";
	}

	$results = array();
	$queryParam = array();
        $dao = CRM_Core_DAO::executeQuery($query,$queryParam);
        $dao->fetch();

	//echo '<pre>'.print_r($dao,true).'</pre>';exit(0);
	// set the userContext stack
        $session =& CRM_Core_Session::singleton();
        $url = CRM_Utils_System::url('civicrm/contact/view/case', 'action=view&selectedChild=case&cid=' . $form->_currentlyViewedContactId . '&id='.$form->_caseId );
        $session->pushUserContext( $url );

	CRM_Utils_System::redirect($url);


    }
}
