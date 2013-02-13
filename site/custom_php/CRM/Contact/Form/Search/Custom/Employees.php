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

require_once 'CRM/Contact/Form/Search/Custom/Base.php';

class CRM_Contact_Form_Search_Custom_Employees
  extends    CRM_Contact_Form_Search_Custom_Base
  implements CRM_Contact_Form_Search_Interface {

    protected $_query;

    function __construct( &$formValues ) {
        parent::__construct( $formValues );

        $this->normalize( );
        $this->_columns = array( ts('')        => 'contact_type'    ,
                                 ts('')        => 'contact_sub_type',
                                 ts('Name'   ) => 'sort_name'       ,
                                 ts('Address') => 'street_address'  ,
                                 ts('City'   ) => 'city'            ,
                                 ts('State'  ) => 'state_province'  ,
                                 ts('Postal' ) => 'postal_code'     ,
                                 ts('Country') => 'country'         ,
                                 ts('Email'  ) => 'email'           ,
                                 ts('Phone'  ) => 'phone'           );
    }

    /**
     * normalize the form values to make it look similar to the advanced form values
     * this prevents a ton of work downstream and allows us to use the same code for
     * multiple purposes (queries, save/edit etc)
     *
     * @return void
     * @access private
     */
    function normalize( ) {
        $contactType = CRM_Utils_Array::value( 'contact_type', $this->_formValues );
        if ( $contactType && ! is_array( $contactType ) ) {
            unset( $this->_formValues['contact_type'] );
            $this->_formValues['contact_type'][$contactType] = 1;
        }

        $group = CRM_Utils_Array::value( 'group', $this->_formValues );
        if ( $group && ! is_array( $group ) ) {
            unset( $this->_formValues['group'] );
            $this->_formValues['group'][$group] = 1;
        }

        $tag = CRM_Utils_Array::value( 'tag', $this->_formValues );
        if ( $tag && ! is_array( $tag ) ) {
            unset( $this->_formValues['tag'] );
            $this->_formValues['tag'][$tag] = 1;
        }

        return;
    }

    function buildForm( &$form ) {
        // text for sort_name
        $form->add('text', 'sort_name', ts('Name'));

        $form->assign( 'elements', array( 'sort_name' ) );
    }

    function all( $offset = 0, $rowCount = 0, $sort = null,
                  $includeContactIDs = false ) {
	$selectClause = "
contact_a.id           as contact_id  ,
contact_a.contact_type as contact_type,
contact_a.sort_name    as sort_name
";
        return $this->sql( $selectClause,
                           $offset, $rowcount, $sort,
                           $includeContactIDs, null );
    }
    
    function from( ) {
        return "
FROM      civicrm_contact contact_a
LEFT JOIN civicrm_address address ON ( address.contact_id       = contact_a.id AND
                                       address.is_primary       = 1 )
LEFT JOIN civicrm_email           ON ( civicrm_email.contact_id = contact_a.id AND
                                       civicrm_email.is_primary = 1 )
LEFT JOIN civicrm_state_province state_province ON state_province.id = address.state_province_id
";
    }

    function where( $includeContactIDs = false ) {
	$count  = 1;
        $clause = array( );
        $params = array( );
        $name   = CRM_Utils_Array::value( 'sort_name',
                                          $this->_formValues );
        $contact_type   = CRM_Utils_Array::value( 'contact_type',
                                          $this->_formValues );
        if ( $name != null ) {
            if ( strpos( $name, '%' ) === false ) {
                $name = "%{$name}%";
            }
            $params[$count] = array( $name, 'String' );
            $clause[] = "contact_a.sort_name LIKE %{$count}";
            $count++;
        }

        $where = "contact_a.contact_sub_type = 'Employee'";

        if ( ! empty( $clause ) ) {
            $where .= ' AND ' . implode( ' AND ', $clause );
        }

        return $this->whereClause( $where , $params );
    }

    function templateFile( ) {
        return 'CRM/Contact/Form/Search/Custom/Employees.tpl';
    }

}
