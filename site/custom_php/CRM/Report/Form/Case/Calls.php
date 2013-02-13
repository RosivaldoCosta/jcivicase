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

require_once 'CRM/Report/Form/Case/CaseReportBase.php';
require_once 'CRM/Case/PseudoConstant.php';

class CRM_Report_Form_Case_Calls extends CRM_Report_Form_Case_CaseReportBase {

    
    function __construct( ) {		        
        parent::__construct( );

	//parent::getCustomFieldColumns($this->_columns,'UCC Appointment');
	//parent::getCustomFieldColumns($this->_columns,'Open Case');
	//parent::getCustomFieldColumns($this->_columns,'Phone Contact');
	$this->addPhoneContactFieldColumns($this->_columns);

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
	$columns[$curTable]['group'] = 'contact-fields';

    }

    function where( ) {
       parent::where(); 
    }

    function groupBy( ) {
        parent::groupBy(); //$this->_groupBy = "";// "GROUP BY {$this->_aliases['civicrm_contact']}.id, {$this->_aliases['civicrm_case']}.id";
    }
    
    function postProcess( ) {
	$views = parent::_buildViewList('views_calls_report');
        $this->assign('results', $views); 
	parent::postProcess();
    }

}
