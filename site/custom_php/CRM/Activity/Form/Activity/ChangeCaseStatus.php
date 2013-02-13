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

/**
 * This class generates form components for OpenCase Activity
 *
 */
class CRM_Case_Form_Activity_ChangeCaseStatus
{

    static function preProcess( &$form )
    {
        if ( !isset($form->_caseId) ) {
            CRM_Core_Error::fatal(ts('Case Id not found.'));
        }
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
        $defaults['case_status_id'] = $defaults['current_case_status_id'] = CRM_Core_DAO::getFieldValue( 'CRM_Case_DAO_Case',
                                                                  $this->_caseId,
                                                                  'status_id', 'id' );
        return $defaults;
    }

    static function buildQuickForm( &$form )
    {
        require_once 'CRM/Core/OptionGroup.php';

	$acl =& JFactory::getACL();
	$user = JFactory::getUser();

        $caseStatus  = CRM_Core_OptionGroup::values('case_status');

	// TODO: Move to plugin closestatus.php

	if($user->gid == $acl->get_group_id('Manager','ARO'))
	{
             	$tmp = array();
		foreach($caseStatus as $k => $v)
		{
			if($v != 'Closed' && $k != 17) 
				$tmp[$k] = $v;
		}
		$caseStatus = $tmp;
	} else {
		$caseID     = $form->_caseId; // CRM_Utils_Type::escape( $_GET['caseID'], 'Integer' );
        	$contactID  = $form->_contactId; //CRM_Utils_Type::escape( $_GET['cid'], 'Integer' );
		$hasDischarge = false;

        	$params     = array('activity_type_id' => 47 );

        	// get the activities related to given case
	        require_once "CRM/Case/BAO/Case.php";
       	 	$activities = CRM_Case_BAO_Case::getCasesActivityCustom( $caseID, $params, $contactID );

		/*foreach($activities as $k => $v)
		{
			if($v['type'] === 'Discharge Summary')
				if($v['status'] === 'Completed')
					$hasDischarge = true;
		}*/

		if( !$activities)
		{
             				$tmp = array();
					foreach($caseStatus as $k => $v)
					{
						if($v != 'Closed')
							$tmp[$k] = $v;
					}
					$caseStatus = $tmp;
		}


	}

	// TODO: Move to plugin currentnewstatus.php

        $form->add('select', 'case_status_id',  ts( 'New Case Status' ),
                    $caseStatus , true  );

        $element = $currentStatus = $form->add('select', 'current_case_status_id',  ts( 'Current Case Status' ), $caseStatus , false);
	$currentStatus->freeze();
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
        $params['id'] = $params['case_id'];
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
        if( $params['case_status_id'] ==
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

    }
}
