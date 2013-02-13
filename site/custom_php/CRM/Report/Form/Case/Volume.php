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

class CRM_Report_Form_Case_Volume extends CRM_Report_Form_Case_CaseReportBase 
{

    function __construct( ) {		        
	parent::__construct( );
	//parent::getCustomFieldColumns($this->_columns,'IHIT Visit');
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
            LEFT JOIN civicrm_case {$this->_aliases['civicrm_case']} ON {$this->_aliases['civicrm_case']}.id = ccc.case_id
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
             
            $this->_where = "WHERE " . implode( ' AND ', $clauses );
        }
        
    }

    function groupBy( ) {
        $this->_groupBy = "";// "GROUP BY {$this->_aliases['civicrm_contact']}.id, {$this->_aliases['civicrm_case']}.id";
    }
    
    function postProcess( ) {

        $this->beginPostProcess( );

        $sql  = $this->buildQuery( true );
        $rows = $graphRows = array();
        $this->buildRows ( $sql, $rows );
        
        $this->formatDisplay( $rows );
	//$this->buildTable();
	$views = parent::_buildViewList('views_volume_of_service_report');
	$this->assign('results', $views);
	//CRM_Core_Error::debug('s', $views);             

        $this->doTemplateAssignment( $rows );
        $this->endPostProcess( $rows );	
    }

    /*function _getView($name)
    {
	$select = "select * from " . $name;

        $sql = "{$select}";
        //CRM_Core_Error::debug('sql', $sql);
        $dao = CRM_Core_DAO::executeQuery( $sql );

        if ( $dao->fetch( ) ) {
            return $dao;
        }

    }*/

    function buildTable()
    {
	$rows = array();
	$views = CRM_Core_OptionGroup::values('views_volume_report');// $activity_name, 'name' );

	foreach($views as $view => $label)
	{
        	$rows[$view] = parent::_getView($view);
	}

	$this->assign('views', $views);
	$this->assign('results', $rows);

        //CRM_Core_Error::debug('results', $rows);
        //CRM_Core_Error::debug('views', $views);
	/*
        
	$rows['view_veterans_ytd_ops_total'] = parent::_getView('view_veterans_ytd_ops_total');
        $rows['view_veterans_ytd_adult'] = parent::_getView('view_veterans_ytd_adult');                                 
        $rows['view_veterans_ytd_anxiety'] = parent::_getView('view_veterans_ytd_anxiety');                                       
        $rows['view_veterans_ytd_child'] = parent::_getView('view_veterans_ytd_child');
        $rows['view_veterans_ytd_child_abuse_physical_sexual_neglect'] = parent::_getView('view_veterans_ytd_child_abuse_physical_sexual_neglect');                   
        $rows['view_veterans_ytd_child_adol_behavior'] = parent::_getView('view_veterans_ytd_child_adol_behavior');
        $rows['view_veterans_ytd_child_with_significant_illness'] = parent::_getView('view_veterans_ytd_child_with_significant_illness');
        $rows['view_veterans_ytd_chronic_mi'] = parent::_getView('view_veterans_ytd_chronic_mi');                                    
        $rows['view_veterans_ytd_cism'] = parent::_getView('view_veterans_ytd_cism');                                                  
        $rows['view_veterans_ytd_community_provider'] = parent::_getView('view_veterans_ytd_community_provider');            
        $rows['view_veterans_ytd_community_resources'] = parent::_getView('view_veterans_ytd_community_resources');            
        $rows['view_veterans_ytd_death_by_suicide'] = parent::_getView('view_veterans_ytd_death_by_suicide');                              
        $rows['view_veterans_ytd_depression'] = parent::_getView('view_veterans_ytd_depression');
        $rows['view_veterans_ytd_detox'] = parent::_getView('view_veterans_ytd_detox');  
        $rows['view_veterans_ytd_domestic_violence'] = parent::_getView('view_veterans_ytd_domestic_violence');     
        $rows['view_veterans_ytd_elderly'] = parent::_getView('view_veterans_ytd_elderly');          
        $rows['view_veterans_ytd_elderly_with_confusion_demensia'] = parent::_getView('view_veterans_ytd_elderly_with_confusion_demensia');
        $rows['view_veterans_ytd_emergency_petition'] = parent::_getView('view_veterans_ytd_emergency_petition');    
        $rows['view_veterans_ytd_family_conflict_with_child_adol'] = parent::_getView('view_veterans_ytd_family_conflict_with_child_adol');         
        $rows['view_veterans_ytd_fatal_accident'] = parent::_getView('view_veterans_ytd_fatal_accident');       
        $rows['view_veterans_ytd_financial'] = parent::_getView('view_veterans_ytd_financial');            
        $rows['view_veterans_ytd_homeless_major_financial'] = parent::_getView('view_veterans_ytd_homeless_major_financial');                
        $rows['view_veterans_ytd_homicide_violence'] = parent::_getView('view_veterans_ytd_homicide_violence');                     
        $rows['view_veterans_ytd_hospital'] = parent::_getView('view_veterans_ytd_hospital');
        $rows['view_veterans_ytd_ihit'] = parent::_getView('view_veterans_ytd_ihit');     
        $rows['view_veterans_ytd_marital_significant_other_conflict'] = parent::_getView('view_veterans_ytd_marital_significant_other_conflict');                           
        $rows['view_veterans_ytd_mct'] = parent::_getView('view_veterans_ytd_mct');
        $rows['view_veterans_ytd_mct_total'] = parent::_getView('view_veterans_ytd_mct_total');     
        $rows['view_veterans_ytd_medical_issue_primary'] = parent::_getView('view_veterans_ytd_medical_issue_primary');          
        $rows['view_veterans_ytd_nmh_referral'] = parent::_getView('view_veterans_ytd_nmh_referral');        
        $rows['view_veterans_ytd_ops_total'] = parent::_getView('view_veterans_ytd_ops_total');            
        $rows['view_veterans_ytd_other'] = parent::_getView('view_veterans_ytd_other');          
        $rows['view_veterans_ytd_police'] = parent::_getView('view_veterans_ytd_police');
        $rows['view_veterans_ytd_runaway'] = parent::_getView('view_veterans_ytd_runaway');      
        $rows['view_veterans_ytd_sexual_assault'] = parent::_getView('view_veterans_ytd_sexual_assault');
        $rows['view_veterans_ytd_situational_crisis'] = parent::_getView('view_veterans_ytd_situational_crisis');            
        $rows['view_veterans_ytd_substance_abuse'] = parent::_getView('view_veterans_ytd_substance_abuse');          
        $rows['view_veterans_ytd_suicide_attempt'] = parent::_getView('view_veterans_ytd_suicide_attempt');
        $rows['view_veterans_ytd_suicide_ideation'] = parent::_getView('view_veterans_ytd_suicide_ideation');            
        $rows['view_veterans_ytd_teenager'] = parent::_getView('view_veterans_ytd_teenager');
        $rows['view_veterans_ytd_total'] = parent::_getView('view_veterans_ytd_total');
        $rows['view_veterans_ytd_transitional_adult'] = parent::_getView('view_veterans_ytd_transitional_adult');            
        $rows['view_veterans_ytd_ucc'] = parent::_getView('view_veterans_ytd_ucc');
        $rows['view_veterans_ytd_unknown_age'] = parent::_getView('view_veterans_ytd_unknown_age');
	*/
        
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
