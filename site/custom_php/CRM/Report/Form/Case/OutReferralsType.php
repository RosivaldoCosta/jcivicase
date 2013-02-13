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

class CRM_Report_Form_Case_OutReferralsType extends CRM_Report_Form_Case_CaseReportBase {

    
    function __construct( ) {		        
        parent::__construct( );

	//parent::getCustomFieldColumns($this->_columns,'UCC Appointment');
	//parent::getCustomFieldColumns($this->_columns,'Open Case');
	parent::getCustomFieldColumns($this->_columns,'Discharge');

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
       parent::where(); 
    }

    function groupBy( ) {
        parent::groupBy(); //$this->_groupBy = "";// "GROUP BY {$this->_aliases['civicrm_contact']}.id, {$this->_aliases['civicrm_case']}.id";
    }
    
    function postProcess( ) {
	$views = parent::_buildViewList('views_out_referrals_type_report');
        $this->assign('results', $views); 
	parent::postProcess();
    }

}
