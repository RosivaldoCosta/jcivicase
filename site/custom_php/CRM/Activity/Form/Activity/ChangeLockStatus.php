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

/**
 * This class generates form components for OpenCase Activity
 *
 */
class CRM_Activity_Form_Activity_ChangeLockStatus extends CRM_Contact_Form_Task
{

    function preProcess( &$form )
    {
	
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
        /*$defaults['lock_status_id'] = $defaults['current_lock_status_id'] = CRM_Core_DAO::getFieldValue( 'CRM_Activity_DAO_Activity',
                                                                  $this->_activityId,
                                                                  'lock_status_id', 'id' );
	*/
	$defaults['status_id'] = 2;
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
    public function endPostProcess( &$form, &$params )
    {
        // Set case end_date if we're closing the case. Clear end_date if we're (re)opening it.
        /*if( $params['case_status_id'] ==
            CRM_Core_OptionGroup::getValue( 'case_status', 'Closed', 'name' )
            && CRM_Utils_Array::value('activity_date_time', $params) ) {
            $params['end_date'] = $params['activity_date_time'];
	    $params['subject'] = "Case Closed";

        } else if ( $params['case_status_id'] ==
                    CRM_Core_OptionGroup::getValue( 'case_status', 'Open', 'name' ) ) {
            $params['end_date'] = "null";
        }

        // FIXME: does this do anything ?
        $params['statusMsg'] = ts('Case Status changed successfully.');
	*/

	echo '<pre>'.print_r($form,true).'</pre>';
	// set the userContext stack
        $session =& CRM_Core_Session::singleton();
        $url = CRM_Utils_System::url('civicrm/contact/view/case', 'action=view&selectedChild=case&cid=' . $form->_currentlyViewedContactId . '&id='.$form->_caseId );
        //$session->pushUserContext( $url );

	//CRM_Utils_System::redirect($url);
	exit(0);


    }
}
