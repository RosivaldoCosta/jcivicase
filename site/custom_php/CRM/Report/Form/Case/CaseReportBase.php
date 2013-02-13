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
require_once 'CRM/Case/PseudoConstant.php';
require_once 'CRM/Core/PseudoConstant.php';

class CRM_Report_Form_Case_CaseReportBase extends CRM_Report_Form {

    protected $_summary      = null;

    protected $_emailField   = false;
    
    protected $_phoneField   = false;

   protected $_caseTypes 	= null;
   protected $_caseStatus 	= null;
   protected $_caseActivityTypes = null;

   protected $_months = array(	"Jan", 	
				"Feb",
				"March",
				"Apr",
				"May",
				"Jun",
				"Jul",
				"Aug",
				"Sep",
				"Oct",
				"Nov",
				"December");

    protected $_counties = array("Caroline",
				 "Cecil",
				 "Dorchester",
				 "Kent",
				 "QA",
				 "Somerset",
				 "Talbot",
				 "Wicomico"  );

    protected $_fromDate = null;
    protected $_toDate = null;
    
    function __construct( ) {		        
    	$this->caseTypes = $_caseTypes = CRM_Case_PseudoConstant::caseType();
    	$this->caseStatus = $_caseStatus = CRM_Case_PseudoConstant::caseStatus();
	$this->activityTypes = $_caseActivityTypes = CRM_Core_PseudoConstant::activityType(true, true);
	//$this->counties = $_counties = CRM_Case_PseudoConstant::counties();

        asort($this->activityTypes);

        $this->_columns = 
            array( 'civicrm_contact' =>
                   array( 'dao'       => 'CRM_Contact_DAO_Contact',
                          'fields'    =>
                          array( 'display_name' => 
                                 array( 'title'     => ts( 'Client Name' ),
                                        'required'  => true,
                                        'no_repeat' => true ),
                                 'gender_id'         =>
                                 array( 'title'   => ts( 'Gender' ),  
                                        'default' => false ), 
                                 'birth_date'         =>
                                 array( 'title'   => ts( 'Birthdate' ),  
                                        'default' => false ), 
                                 'id'           => 
                                 array( 'no_display'=> true,
                                        'required'  => true, ), ),
                          /*'filters'   =>             
                          array( 'gender_id' =>
						array('operatorType' => CRM_Report_Form::OP_SELECT,
						      'options' => array( '' => ts('-select-'),
									    1 => ts('Male'), 
								  	    2 => ts('Female') 
									),
							'title' => ts('Gender'),
							'type' => CRM_Utils_Type::T_INT
							),
				'birth_date'  =>
                                              array( 'operatorType' => CRM_Report_Form::OP_DATE),
				'sort_name'    => 
                                 array( 'title'      => ts( 'Client Name' )  ),
                                 'id'           => 
                                 array( 'title'      => ts( 'Client ID' ),
                                        'no_display' => true ), ),*/
                          'grouping'  => 'contact-fields',
                          ),
                   'civicrm_address' =>
                   array( 'dao'       => 'CRM_Core_DAO_Address',
                          'grouping'  => 'contact-fields',
                          'fields'    =>
                          array( 'street_address'    => 
                                 array( 'default' => false ),
                                 'city'              => 
                                 array( 'default' => true ),
                                 'postal_code'       => null,
                                 'state_province_id' => 
                                 array( 'title'   => ts( 'State/Province' ), ),
                                 'country_id'        => 
                                 array( 'title'   => ts( 'Country' ),  
                                        'default' => false ), 
                                 ),
                          ),
                   'civicrm_phone' => 
                   array( 'dao'       => 'CRM_Core_DAO_Phone',
                          'fields'    =>
                          array( 'phone'  => null),
                          'grouping'  => 'contact-fields',
                          ),

                  'civicrm_activity' => 
                   array( 'dao'       => 'CRM_Activity_DAO_Activity',
                          'fields'    =>
                          array( 'id'  => array('title' => ts('Activity ID'),
                                                'no_display' => true,
                                                'required' => true,
                                               ),
                               ),
			/*'filters' =>
                         array( 'activity_date_time'  =>
                                              array( 'default'      => 'this.year',
                                                     'operatorType' => CRM_Report_Form::OP_DATE),
                                              'subject'             =>
                                              array( 'title'        => ts( 'Activity Subject' ),
                                                     'operator'     => 'like' ),
                                              'activity_type_id'    =>
                                              array( 'title'        => ts( 'Activity Type' ),
                                                     'operatorType' => CRM_Report_Form::OP_MULTISELECT,
                                                     'options'      => $this->activityTypes ),
				)*/


                          ),

                  'civicrm_case' => 
                   array( 'dao'       => 'CRM_Case_DAO_Case',
                          'fields'    =>
                          array( 'id'  => array('title' => ts('Case ID'),
                                                'required' => true,
                                               ),
				'case_type_id'  => array( 'title'      => ts( 'Case Type' ),
                                                      'default'    => false,
                                                      'type'       =>  CRM_Utils_Type::T_STRING
                                                      ),
                                 'start_date'  => array('title' => ts('Case Start'),
                                                'required' => true,
                                               ),
                                 'end_date'  => array('title' => ts('Case End'),
                                                'required' => true,
                                               ),
                               ),
                          'filters'   =>  
			   array( 'start_date' => array( 'title' => ts( 'Case Start' ),
                                                        'operatorType' => CRM_Report_Form::OP_DATE,
                                                      ),
                                 /*'end_date' => array( 'title' => ts( 'Case End' ),
                                                        'operatorType' => CRM_Report_Form::OP_DATE,
                                                      ),*/
				),
                          ),

                   );

        $this->_genders = CRM_Core_PseudoConstant::gender(); 

        parent::__construct( );
    }
    
    function getCustomFieldColumns(&$columns, $activity_name, $filters = null)
    {
	//   Get Custom fields
        $custom_val = CRM_Core_OptionGroup::getValue('activity_type', $activity_name, 'name' );
	$sql = "SELECT cg.table_name, cg.extends AS ext, cf.label, cf.column_name";
	$sql .= " FROM civicrm_custom_group cg";
	$sql .= " INNER JOIN civicrm_custom_field cf ON cg.id = cf.custom_group_id";
	$sql .= " WHERE (cg.extends='Contact' OR cg.extends='Individual' OR cg.extends_entity_column_value='".$custom_val."') AND cg.is_active=1 AND cf.is_active=1 ORDER BY cg.table_name";

	$crmDAO =& CRM_Core_DAO::executeQuery($sql);
	//CRM_Core_Error::debug('sql', $sql);             

        $curTable = '';
        $curExt = '';
        $curFields = array();
        while($crmDAO->fetch()) 
	{
        	if ($curTable == '') {
        		$curTable = $crmDAO->table_name;
        		$curExt = $crmDAO->ext;
            	} elseif ($curTable != $crmDAO->table_name) {
                	$columns[$curTable] = array('dao' => 'CRM_Contact_DAO_Contact', // dummy DAO
                                                   'fields' => $curFields,
                                                   'ext' => $curExt,                   
                                                  );
                	$curTable = $crmDAO->table_name;
                	$curExt = $crmDAO->ext;
                	$curFields = array();
        	}    	
        	
        	$curFields[$crmDAO->column_name] = array('title' => $crmDAO->label);
        }

	if (! empty($curFields)) {
            		$columns[$curTable] = array('dao' => 'CRM_Contact_DAO_Contact', // dummy DAO
                                               'fields' => $curFields,
                                               'ext' => $curExt,                   
                                              );
	}


    }

/*
function addPhoneContactFieldColumns(&$columns)
    {
        //   Get Custom fields
        $sql = "SELECT cg.table_name, cg.extends AS ext, cf.label, cf.column_name";
        $sql .= " FROM civicrm_custom_group cg";
        $sql .= " INNER JOIN civicrm_custom_field cf ON cg.id = cf.custom_group_id";
        $sql .= " WHERE (cg.title like 'Phone%') AND cg.is_active=1 AND cf.is_active=1 ORDER BY cg.table_name";

        $crmDAO =& CRM_Core_DAO::executeQuery($sql);
        //CRM_Core_Error::debug('sql', $sql);

        $curTable = '';
        $curExt = '';
        $curFields = array();
        while($crmDAO->fetch())
        {
                if ($curTable == '') {
                        $curTable = $crmDAO->table_name;
                        $curExt = $crmDAO->ext;
                } elseif ($curTable != $crmDAO->table_name) {
                        $columns[$curTable] = array('dao' => 'CRM_Contact_DAO_Contact', // dummy DAO
                                                   'fields' => $curFields,
                                                   'ext' => $curExt,
                                                  );
                        $curTable = $crmDAO->table_name;
                        $curExt = $crmDAO->ext;
                        $curFields = array();
                }

                $curFields[$crmDAO->column_name] = array('title' => $crmDAO->label);
        }

        if (! empty($curFields)) {
                        $columns[$curTable] = array('dao' => 'CRM_Contact_DAO_Contact', // dummy DAO
                                               'fields' => $curFields,
                                               'ext' => $curExt,
                                              );
        }
        $columns[$curTable]['filters']   = array( 'type_of_call_726'  =>
                                              array( 'operatorType' => CRM_Report_Form::OP_MULTISELECT,
                                                     'options' => array( 'Incoming'=>'Incoming',
                                                                        'Outgoing'=>'Outgoing'),
                                                     'default' => 'Incoming',
                                                        'title' => ts('Type of Call'),
                                                        'type' => CRM_Utils_Type::T_STRING
                                                   )
                                           );

    }
*/

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
        $errors = array( );
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
             
            $this->_where = "WHERE " . implode( ' AND ', $clauses );
        }
        
    }

    function groupBy( ) {
        $this->_groupBy = "GROUP BY {$this->_aliases['civicrm_contact']}.id, {$this->_aliases['civicrm_case']}.id";
    }
    
    function postProcess( ) {

        $this->beginPostProcess( );

        $sql  = $this->buildQuery( true );
	//CRM_Core_Error::debug('sql', $sql);             
        $rows = $graphRows = array();
        $this->buildRows ( $sql, $rows );
        
        $this->formatDisplay( $rows );
        $this->doTemplateAssignment( $rows );
        $this->endPostProcess( $rows );	
    }

    function _buildViewList($name, $type = null, $key = null)
    {
	$rows = array();
        $views = CRM_Core_OptionGroup::values($name);// $activity_name, 'name' );
	$this->assign('views', $views);
	

	//CRM_Core_Error::debug('v', $views);             
        foreach($views as $view => $label)
        {
		if( strstr($view, 'separator') == false)
                	$rows[$view] = $this->_getView($view,$key,$type);
		else
			$rows[$view] = $label;
        }
	//CRM_Core_Error::debug('v', $rows);             


	return $rows;
    }


    function _getView($name, $key = null, $type = null)
    {
        $select = "select * from " . $name;
        $from     = CRM_Utils_Array::value( "start_date_from"    , $this->_params );
        $to       = CRM_Utils_Array::value( "start_date_to"      , $this->_params );
        //CRM_Core_Error::debug('sql', $this->_params);
	//list($from, $to) = CRM_Report_Form::getFromTo(null, $from, $to);

        $sql = "{$select}";

        $dao = CRM_Core_DAO::executeQuery( $sql );
	
	if($key)
	{

		$r = array();
		while($dao->fetch())
		{
			$r[$dao->$key] =  array('Jan' => $dao->Jan,
			'Feb' => $dao->Feb,
			'Mar' => $dao->March,
			'Apr' => $dao->Apr,
			'May' => $dao->May,
			'Jun' => $dao->Jun,
			'Jul' => $dao->Jul,
			'Aug' => $dao->Aug,
			'Sep' => $dao->Sep,
			'Oct' => $dao->Oct,
			'Nov' => $dao->Nov,
			'Dec' => $dao->Dec,
			'TTL'	=> $dao->TTL,
			'AVG'	=> $dao->AVG);
		}

		/*if($key === "_totals")
		//{
			//print_r($r);
			$ttl_sql = "select TTL from view_ops_total_closed";
			$ttl_ops = CRM_Core_DAO::executeQuery( $ttl_sql );

			$ttl_sql = "select TTL from view_mct_total_closed";
			$ttl_mct = CRM_Core_DAO::executeQuery( $ttl_sql );
		}*/
		return $r;

	} 
	else if ($type === 'county') 
	{
   		 
        	if ( $dao->fetch( ) ) 
		{
           	 	return  array('Caroline' => $dao->Caroline,
			'Cecil' => $dao->Cecil,
			'Dorchester' => $dao->Dorchester,
			'Kent' => $dao->Kent,
			'QA' => $dao->QA,
			'Somerset' => $dao->Somerset,
			'Talbot' => $dao->Talbot,
			'Wicomico' => $dao->Wicomico,
			'TTL'	=>	$dao->TTL,
			'AVG'	=>	$dao->AVG);
        	//CRM_Core_Error::debug('r', $dao);
		}
	} 
	else 
	{
        	if ( $dao->fetch( ) ) 
		{
           	 	return  array('Jan' => $dao->Jan,
			'Feb' => $dao->Feb,
			'Mar' => $dao->March,
			'Apr' => $dao->Apr,
			'May' => $dao->May,
			'Jun' => $dao->Jun,
			'Jul' => $dao->Jul,
			'Aug' => $dao->Aug,
			'Sep' => $dao->Sep,
			'Oct' => $dao->Oct,
			'Nov' => $dao->Nov,
			'Dec' => $dao->Dec,
			'TTL'	=>	$dao->TTL,
			'AVG'	=>	$dao->AVG);
        	}
	}
	

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
