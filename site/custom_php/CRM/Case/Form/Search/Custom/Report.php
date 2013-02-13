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

class CRM_Case_Form_Search_Custom_Report
   extends    CRM_Contact_Form_Search_Custom_Base
   implements CRM_Contact_Form_Search_Interface {

	protected $_query;


    function __construct( &$formValues ) {
        parent::__construct( $formValues );

        $this->_columns = array( ts('Case ID') 		=> 'case_id',
				 //ts('Contact Id')   	=> 'contact_id',
                                 ts('Name')     	=> 'sort_name',
                                 ts('Street Address')  	=> 'street_address', 
                                 ts('Phone')		=> 'phone', 
                                 ts('DOB')      	=> 'dob'); 

	$params           =& CRM_Contact_BAO_Query::convertFormValues( $this->_formValues );
        $returnProperties = array( );

        foreach ( $this->_columns as $name => $field ) {
            $returnProperties[$field] = 1;
        }

        $this->_query =& new CRM_Contact_BAO_Query( $params, $returnProperties, null,
                                                    false, false, 1, false, false );
    }

    function buildForm( &$form ) {

        $form->addElement( 'text',
                    'view_name',
                    ts( 'View' ),
                    true );

        /**
         * You can define a custom title for the search form
         */
         $this->setTitle('Report Drill Down Search');
         
         /**
         * if you are using the standard template, this array tells the template what elements
         * are part of the search criteria
         */
        $form->assign( 'elements', array( 'view_name' ) );
    }

    function summary( ) {
        $summary = array(); 
        return $summary;
    }

    function all( $offset = 0, $rowcount = 0, $sort = null,
                  $includeContactIDs = false ) {
        $selectClause = "
c.id as case_id,
contact_a.id as contact_id,
contact_a.sort_name    as sort_name,
contact_a.birth_date as dob,
p.phone,
address.street_address
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
LEFT JOIN civicrm_case_contact cc ON cc.contact_id=contact_a.id
LEFT JOIN civicrm_case c ON c.id = cc.case_id
LEFT JOIN civicrm_phone p ON ( p.contact_id=contact_a.id AND p.is_primary=1 )
";
    }

    function where( $includeContactIDs = false ) {
        $params = array( );
        $where  = "contact_a.contact_type   = 'Individual'";
        $where  .= " AND contact_a.contact_sub_type   = 'Client'";
        $where  .= " AND contact_a.is_deleted = '0'";

        $count  = 1;
        $clause = array( );
        $name   = CRM_Utils_Array::value( 'sort_name',
                                          $this->_formValues );
        if ( $name != null ) {
            if ( strpos( $name, '%' ) === false ) {
                $name = "%{$name}%";
            }
            $params[$count] = array( $name, 'String' );
            $clause[] = "contact_a.sort_name LIKE %{$count}";
            $count++;
        }

	/*
        $state = CRM_Utils_Array::value( 'state_province_id',
                                         $this->_formValues );
        if ( ! $state &&
             $this->_stateID ) {
            $state = $this->_stateID;
        }

        if ( $state ) {
            $params[$count] = array( $state, 'Integer' );
            $clause[] = "state_province.id = %{$count}";
        }*/

        $caseid = CRM_Utils_Array::value( 'case_id', $this->_formValues );
	if( $caseid != null)
	{
		$clause[] = "case_id = " . $caseid;
	}

        $address = CRM_Utils_Array::value( 'address', $this->_formValues );
        if ( $address != null ) {
            if ( strpos( $address , '%' ) === false ) {
                $address = "%{$address}%";
            }
            $params[$count] = array( $address, 'String' );
            $clause[] = "street_address LIKE %{$count}";
            $count++;
        }

	/*
        $city = CRM_Utils_Array::value( 'city', $this->_formValues );
        if ( $city != null ) {
            if ( strpos( $city , '%' ) === false ) {
                $city = "%{$city}%";
            }
            $params[$count] = array( $city, 'String' );
            $clause[] = "city LIKE %{$count}";
            $count++;
        }

        $postal = CRM_Utils_Array::value( 'postal_code', $this->_formValues );
        if ( $postal_code != null ) {
            if ( strpos( $postal_code, '%' ) === false ) {
                $postal_code = "%{$post_code}%";
            }
            $params[$count] = array( $postal_code, 'String' );
            $clause[] = "postal_code LIKE %{$count}";
            $count++;
        }*/

        $phone = CRM_Utils_Array::value( 'phone', $this->_formValues );
        if ( $phone != null ) {
            if ( strpos( $phone, '%' ) === false ) {
                $phone = "%{$phone}%";
            }
            $params[$count] = array( $phone, 'String' );
            $clause[] = "phone LIKE %{$count}";
            $count++;
        }

	$dob = CRM_Utils_Date::mysqlToIso( CRM_Utils_Date::processDate( $this->_formValues['start_date'] ) );
	if($dob != null )
	{
		$clause[] = "contact_a.birth_date = '" . $dob . "'";	
	}

        if ( ! empty( $clause ) ) {
            $where .= ' AND ' . implode( ' AND ', $clause );
        }

	//echo $where;
        return $this->whereClause( $where, $params );
    }

    function templateFile( ) {
        return 'CRM/Contact/Form/Search/Custom/ClientProfile.tpl';
    }

    function setDefaultValues( ) {
        return array( );
    }

    function alterRow( &$row ) {
        //$row['sort_name'] .= ' ( altered )';
    }
    
    function setTitle( $title ) {
        if ( $title ) {
            CRM_Utils_System::setTitle( $title );
        } else {
            CRM_Utils_System::setTitle(ts('Search'));
        }
    }
}


