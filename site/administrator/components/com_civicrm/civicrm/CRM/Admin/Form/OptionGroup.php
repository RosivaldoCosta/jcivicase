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
 * $Id$
 *
 */

require_once 'CRM/Admin/Form.php';

/**
 * This class generates form components for Option Group
 * 
 */
class CRM_Admin_Form_OptionGroup extends CRM_Admin_Form
{
    /**
     * Function to build the form
     *
     * @return None
     * @access public
     */
    public function buildQuickForm( ) 
    {
        parent::buildQuickForm( );
        if ($this->_action & CRM_Core_Action::DELETE ) { 
            return;
        }

        $this->applyFilter('__ALL__', 'trim');
        $this->add('text',
                   'name',
                   ts('Name'),
                   CRM_Core_DAO::getAttribute( 'CRM_Core_DAO_OptionGroup', 'name' ),
                   true );
        $this->addRule( 'name',
                        ts('Name already exists in Database.'),
                        'objectExists',
                        array( 'CRM_Core_DAO_OptionGroup', $this->_id ) );
        
        $this->add('text',
                   'description',
                   ts('Description'),
                   CRM_Core_DAO::getAttribute( 'CRM_Core_DAO_OptionGroup', 'description' ) );
        
        $element = $this->add( 'checkbox', 'is_active', ts('Enabled?') );
        if ( $this->_action & CRM_Core_Action::UPDATE ) {
            if ( in_array( $this->_values['name'], array( 'encounter_medium', 'case_type', 'case_status' ) ) ) {
                static $caseCount = null; 
                require_once 'CRM/Case/BAO/Case.php';
                if ( !isset( $caseCount ) ) {
                    $caseCount = CRM_Case_BAO_Case::caseCount( null, false );
                }
                
                if ( $caseCount > 0 ) {
                    $element->freeze( );
                }
               
            } 
            if ( $this->_values['is_reserved'] ) { 
                $this->freeze( array( 'name', 'description', 'is_active' ) );
            }
        }

        $this->assign('id', $this->_id);
    }

       
    /**
     * Function to process the form
     *
     * @access public
     * @return None
     */
    public function postProcess() 
    {
        CRM_Utils_System::flushCache( );
        
        $params = $this->exportValues();
        require_once 'CRM/Core/BAO/OptionGroup.php';
        if($this->_action & CRM_Core_Action::DELETE) {
            CRM_Core_BAO_OptionGroup::del($this->_id);
            CRM_Core_Session::setStatus( ts('Selected option group has been deleted.') );
        } else { 

            $params = $ids = array( );
            // store the submitted values in an array
            $params = $this->exportValues();
            
            if ($this->_action & CRM_Core_Action::UPDATE ) {
                $ids['optionGroup'] = $this->_id;
            }
            
            $optionGroup = CRM_Core_BAO_OptionGroup::add($params, $ids);
            CRM_Core_Session::setStatus( ts('The Option Group \'%1\' has been saved.', array( 1 => $optionGroup->name )) );
        }
        
    }
}


