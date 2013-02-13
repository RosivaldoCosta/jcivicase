<?php

/*
 +--------------------------------------------------------------------+
 | CiviCRM version 3.0                                                |
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

require_once 'CRM/Report/Form/Case/Veteran.php';
require_once 'CRM/Case/PseudoConstant.php';

class CRM_Report_Form_Case_Comprehensive extends CRM_Report_Form_Case_Veteran {

    
    function __construct( ) {		        
        parent::__construct( );

	//parent::getCustomFieldColumns($this->_columns,'Open Case');

    }
    
    function preProcess( ) {
        parent::preProcess( );
    }
    
    function select( ) {
	parent::select();
    }

/*
    static function formRule( &$fields, &$files, $self ) {  
	parent::formRule($fields,$files,$self);
    }
*/

    function from( ) {
	parent::from();
    }

    function where( ) {
	$clauses = array( );
        $this->_having = '';
        foreach ( $this->_columns as $tableName => $table ) {
            if ( array_key_exists('filters', $table) ) {
                foreach ( $table['filters'] as $fieldName => $field ) {
                    $clause = null;
                    if ( $field['operatorType'] & CRM_Report_Form::OP_DATE ) {
                        $relative = CRM_Utils_Array::value( "{$fieldName}_relative", $this->_params );
                        $from     = CRM_Utils_Array::value( "{$fieldName}_from"    , $this->_params );
                        $to       = CRM_Utils_Array::value( "{$fieldName}_to"      , $this->_params );

                        $clause = $this->dateClause( $field['dbAlias'], $relative, $from, $to );
                    } else {
                        $op = CRM_Utils_Array::value( "{$fieldName}_op", $this->_params );
                        if ( $op ) {
                                // handle special case
                                if ($fieldName == 'case_id_filter') {
                                        $choice = CRM_Utils_Array::value( "{$fieldName}_value", $this->_params );
                                        if ($choice == 1) {
                                                $clause = "({$this->_aliases['civicrm_case']}.id Is Not Null)";
                                        } elseif ($choice == 2) {
                                                $clause = "({$this->_aliases['civicrm_case']}.id Is Null)";
                                        }
                                } else {
                                $clause =
                                    $this->whereClause( $field,
                                                    $op,
                                                    CRM_Utils_Array::value( "{$fieldName}_value", $this->_params ),
                                                    CRM_Utils_Array::value( "{$fieldName}_min", $this->_params ),
                                                    CRM_Utils_Array::value( "{$fieldName}_max", $this->_params ) );
                                }
                        }
                    }

                    if ( ! empty( $clause ) ) {
                        if ( CRM_Utils_Array::value( 'group', $field ) ) {
                            $clauses[ ] = $this->whereGroupClause( $clause );
                        } else {
                            $clauses[ ] = $clause;
                        }
                    }
                }
            }
        }

        if ( empty( $clauses ) ) {
            $this->_where = "WHERE ( 1 ) ";
        } else {

            $this->_where = "WHERE " . implode( ' AND ', $clauses ); 
        }

    }

    function groupBy( ) {
        $this->_groupBy = "GROUP BY {$this->_aliases['civicrm_contact']}.id, {$this->_aliases['civicrm_case']}.id";
    }
    
    function postProcess( ) {
        $views = parent::_buildViewList('views_comprehensive_report');
        $this->assign('results', $views);
	$this->beginPostProcess( );

        $sql  = $this->buildQuery( true );
        //CRM_Core_Error::debug('sql', $sql);
        $rows = $graphRows = array();
        $this->buildRows ( $sql, $rows );

        $this->formatDisplay( $rows );
        $this->doTemplateAssignment( $rows );
        $this->endPostProcess( $rows );

	//parent::postProcess();
    }

}
