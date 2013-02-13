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

require_once 'CRM/Report/Form.php';
require_once 'CRM/Report/Form/Case/CaseReportBase.php';
require_once 'CRM/Case/PseudoConstant.php';

class CRM_Report_Form_Case_Veteran extends CRM_Report_Form_Case_CaseReportBase {


    
    function __construct( ) {		        
	parent::__construct( );
	parent::getCustomFieldColumns($this->_columns,'Open Case');
	/*$this->_columns['civicrm_value_client_information_10'] =
					array( 	'dao'		=> 'CRM_Contact_DAO_Contact',
						'fields'	=> 
						array( 'veteran__62'	=>
							array( 'title'	=> ts('Is Veteran'),
								'required' 	=> true,
								'default'	=> true
								),
						),
						'filter'	=>
						array( 'operatorType' 	=> CRM_Report_Form::OP_SELECT,
							'options'	=> array( '1' => ts('Yes'),
										  '0' => ts('No')
										),
							'default'	=> 1
						),
					)
				);
	*/
							

    }
    
    function preProcess( ) {
        parent::preProcess( );
    }
    
    function select( ) {
        $select = array( );
        $this->_columnHeaders = array( );
        foreach ( $this->_columns as $tableName => $table ) {
            if ( array_key_exists('fields', $table) ) {
                foreach ( $table['fields'] as $fieldName => $field ) {
                    if ( CRM_Utils_Array::value( 'required', $field ) ||
                         CRM_Utils_Array::value( $fieldName, $this->_params['fields'] ) ) {
                        if ( $tableName == 'civicrm_email' ) {
                            $this->_emailField = true;
                        } else if ( $tableName == 'civicrm_phone' ) {
                            $this->_phoneField = true;
                        }

                        $select[] = "{$field['dbAlias']} as {$tableName}_{$fieldName}";
                        $this->_columnHeaders["{$tableName}_{$fieldName}"]['type']  = CRM_Utils_Array::value( 'type', $field );
                        $this->_columnHeaders["{$tableName}_{$fieldName}"]['title'] = $field['title'];
                    }
                }
            }
        }

        $this->_select = "SELECT " . implode( ', ', $select ) . " ";
    }

    static function formRule( &$fields, &$files, $self ) {  
        $errors = $grouping = array( );
        return $errors;
    }

    function from( ) {
        $this->_from = "
        FROM civicrm_contact {$this->_aliases['civicrm_contact']}
            LEFT JOIN civicrm_address {$this->_aliases['civicrm_address']} 
                   ON ({$this->_aliases['civicrm_contact']}.id = {$this->_aliases['civicrm_address']}.contact_id AND 
                      {$this->_aliases['civicrm_address']}.is_primary = 1 )
            LEFT JOIN civicrm_case_contact ccc ON ccc.contact_id = {$this->_aliases['civicrm_contact']}.id
            LEFT JOIN civicrm_case {$this->_aliases['civicrm_case']} ON {$this->_aliases['civicrm_case']}.id = ccc.case_id AND {$this->_aliases['civicrm_case']}.is_deleted=0
            LEFT JOIN civicrm_case_activity cca ON cca.case_id = {$this->_aliases['civicrm_case']}.id
            LEFT JOIN civicrm_activity {$this->_aliases['civicrm_activity']} ON ({$this->_aliases['civicrm_activity']}.id = cca.activity_id AND {$this->_aliases['civicrm_activity']}.is_current_revision=1) 
        ";
            
		foreach($this->_columns as $t => $c) {
			if (substr($t, 0, 13) == 'civicrm_value') {
                $this->_from .= " LEFT JOIN $t {$this->_aliases[$t]} ON {$this->_aliases[$t]}.entity_id = ";
                $this->_from .= ($c['ext'] == 'Activity') ? "{$this->_aliases['civicrm_activity']}.id" : "{$this->_aliases['civicrm_contact']}.id";
			}
		}
        		
        if ( $this->_emailField ) {
            $this->_from .= "
            LEFT JOIN  civicrm_email {$this->_aliases['civicrm_email']} 
                   ON ({$this->_aliases['civicrm_contact']}.id = {$this->_aliases['civicrm_email']}.contact_id AND
                      {$this->_aliases['civicrm_email']}.is_primary = 1) ";
        }

        if ( $this->_phoneField ) {
            $this->_from .= "
            LEFT JOIN civicrm_phone {$this->_aliases['civicrm_phone']} 
                   ON {$this->_aliases['civicrm_contact']}.id = {$this->_aliases['civicrm_phone']}.contact_id AND 
                      {$this->_aliases['civicrm_phone']}.is_primary = 1 ";
        }   
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
             
            $this->_where = "WHERE " . implode( ' AND ', $clauses ) . ' AND value_client_information_10_civireport.veteran__62 = 1';
        }
        
    }

    function groupBy( ) {
        $this->_groupBy = "";// "GROUP BY {$this->_aliases['civicrm_contact']}.id, {$this->_aliases['civicrm_case']}.id";
    }
    
    function postProcess( ) {
        //CRM_Core_Error::debug('results', $this->_params);
	$views = parent::_buildViewList('views_veteran_report');
	$this->assign('results', $views);
	parent::postProcess();
    }

    function alterDisplay( &$rows ) {
        // custom code to alter rows
        $entryFound = false;
        foreach ( $rows as $rowNum => $row ) {
            // make count columns point to detail report
            // convert display name to links
            if ( array_key_exists('civicrm_contact_display_name', $row) && 
                 array_key_exists('civicrm_contact_id', $row) ) {
                $url = CRM_Utils_System::url( 'civicrm/contact/view', 
                                              'reset=1&cid=' . $row['civicrm_contact_id'],
                                              $this->_absoluteUrl );
                $rows[$rowNum]['civicrm_contact_display_name_link' ] = $url;
                $rows[$rowNum]['civicrm_contact_display_name_hover'] = ts("View Contact details for this contact.");
                $entryFound = true;
            }

            // handle gender
            if ( array_key_exists('civicrm_contact_gender_id', $row) ) {
                if ( $value = $row['civicrm_contact_gender_id'] ) {
                    $rows[$rowNum]['civicrm_contact_gender_id'] = $this->_genders[$value];
                }
                $entryFound = true;
            }

            // handle country
            if ( array_key_exists('civicrm_address_country_id', $row) ) {
                if ( $value = $row['civicrm_address_country_id'] ) {
                    $rows[$rowNum]['civicrm_address_country_id'] = CRM_Core_PseudoConstant::country( $value, false );
                }
                $entryFound = true;
            }
            if ( array_key_exists('civicrm_address_state_province_id', $row) ) {
                if ( $value = $row['civicrm_address_state_province_id'] ) {
                    $rows[$rowNum]['civicrm_address_state_province_id'] = CRM_Core_PseudoConstant::stateProvince( $value, false );
                }
                $entryFound = true;
            }

			// handle custom fields
			foreach($row as $k => $r) {
				if (substr($k, 0, 13) == 'civicrm_value') {
					if ( $r || $r=='0' ) {
						if ($newval = $this->getCustomFieldLabel( $k, $r )) {
							$rows[$rowNum][$k] = $newval;
						}
					}
					$entryFound = true;
				}
			}

            // skip looking further in rows, if first row itself doesn't 
            // have the column we need
            if ( !$entryFound ) {
                break;
            }
        }
    }
    
    function getCustomFieldLabel( $fname, $val ) 
    {
        $query = "
SELECT v.label
  FROM civicrm_custom_group cg INNER JOIN civicrm_custom_field cf ON cg.id = cf.custom_group_id
  INNER JOIN civicrm_option_group g ON cf.option_group_id = g.id
  INNER JOIN civicrm_option_value v ON g.id = v.option_group_id
  WHERE CONCAT(cg.table_name, '_', cf.column_name) = %1 AND v.value = %2";
        $params = array( 1 => array( $fname, 'String' ),
                         2 => array( $val, 'String' ),
                       );
        return CRM_Core_DAO::singleValueQuery( $query, $params );
    }
}
