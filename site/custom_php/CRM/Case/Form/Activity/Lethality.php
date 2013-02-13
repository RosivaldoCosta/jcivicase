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
 * This class generates form components for Lethality Activity
 * 
 */
class CRM_Case_Form_Activity_Lethality
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
        /*if ( $form->_context == 'caseActivity' ) {
            return;
        }
	*/
        $form->_context   = CRM_Utils_Request::retrieve( 'context', 'String', $form );
        $form->_contactID = CRM_Utils_Request::retrieve( 'cid', 'Positive', $form );
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
        $defaults = array( );

        
        return $defaults;
    }

    static function buildQuickForm( &$form ) 
    {
        if ( $form->_context == 'standalone' ) {
            require_once 'CRM/Contact/Form/NewContact.php';
            CRM_Contact_Form_NewContact::buildQuickForm( $form );
        }

        
        $form->addButtons(array( 
                                array ( 'type'      => 'upload', 
                                        'name'      => ts('Save'), 
                                        'isDefault' => true   ), 
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
        if ( isset( $values['contact_select_id'] ) && !$values['contact_select_id'] ) {
            $errors['contact'] = ts('Please select a valid contact or create new contact');
        }
        
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
       
    }

	static function getValue($groupTitle, $fieldName, $caseID, $contactID)
        {
                $atArray = array('activity_type_id' => 35); // Intake
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


        }

}
