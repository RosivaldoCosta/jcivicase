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

require_once 'CRM/Report/Form.php'; //Case/CaseReportBase.php';
require_once 'CRM/Report/Form/Case/CaseReportBase.php';
require_once 'CRM/Case/PseudoConstant.php';

class CRM_Report_Form_Case_MCTTeam809 extends CRM_Report_Form_Case_CaseReportBase {

    
    function __construct( ) {		        
        parent::__construct( );

	//parent::getCustomFieldColumns($this->_columns,'UCC Appointment');
	$this->dispatchedBy = CRM_Core_OptionGroup::values('dispatched_by_20100924175113');
	$this->dispatchType = CRM_Core_OptionGroup::values('dispatched_20100924174441');
	$this->precinct = CRM_Core_OptionGroup::values('precinct_20100924174825');
	$this->locationOfCall = CRM_Core_OptionGroup::values('location_of_call_20100924174711');

	parent::getCustomFieldColumns($this->_columns,'Open Case');
	parent::getCustomFieldColumns($this->_columns,'MCT Dispatch');
	$this->_columns['mct_dispatch_details_127']['dao'] = 'CRM_Contact_DAO_Contact';
	$this->_columns['mct_dispatch_details_127']['alias'] = 'value_mct_dispatch_details_127';
	$this->_columns['mct_dispatch_details_127']['filters'] = array( 'dispatched_by_659'  =>       
									array( 'title'  => ts( 'Dispatched By' ),
                                                                        	'operatorType' => CRM_Report_Form::OP_MULTISELECT,
                                                                        	'options'      => $this->dispatchedBy,
										'type'       =>  CRM_Utils_Type::T_STRING
                                                                        ),
									'dispatched_655'	=>
									array( 'title'  => ts( 'Dispatch Type' ),
                                                                        	'operatorType' => CRM_Report_Form::OP_MULTISELECT,
                                                                        	'options'      => $this->dispatchType,
										'type'       =>  CRM_Utils_Type::T_STRING
                                                                        ),
									'arrival_time_648'	=>
									array( 'title'  => ts( 'Arrival Time' ),
                                                                        	'operatorType' => CRM_Report_Form::OP_DATE,
                                                                        ),
									'end_time_649'	=>
									array( 'title'  => ts( 'End Time' ),
                                                                        	'operatorType' => CRM_Report_Form::OP_DATE
                                                                        ),
									'dispatch_time_650'	=>
									array( 'title'  => ts( 'Dispatch Time' ),
                                                                        	'operatorType' => CRM_Report_Form::OP_DATE,
                                                                        ),
									'team_652'	=>
									array( 'title'  => ts( 'Team' )),
									'total_time_653'	=>
									array( 'title'  => ts( 'Total Time' ),
										'type'       =>  CRM_Utils_Type::T_INT
                                                                        ),
									'location_of_call_656'	=>
									array( 'title'  => ts( 'Location of Call' ),
                                                                        	'operatorType' => CRM_Report_Form::OP_MULTISELECT,
                                                                        	'options'      => $this->locationOfCall,
										'type'       =>  CRM_Utils_Type::T_STRING
                                                                        ),
									'precinct_657'	=>
									array( 'title'  => ts( 'Precinct' ),
                                                                        	'operatorType' => CRM_Report_Form::OP_MULTISELECT,
                                                                        	'options'      => $this->precinct,
										'type'       =>  CRM_Utils_Type::T_STRING
                                                                        ),
									'mileage_671'	=>
									array( 'title'  => ts( 'Mileage' ),
                                                                        	'operatorType' => CRM_Report_Form::OP_INT
                                                                        ),
									'responding_officer670'	=>
									array( 'title'  => ts( 'Responding Officer' )),
								);
	$this->_columns['mct_dispatch_notes_128']['dao'] = 'CRM_Contact_DAO_Contact';
	$this->_columns['mct_dispatch_notes_128']['alias'] = 'value_mct_dispatch_details_128';
	$this->_columns['mct_dispatch_notes_128']['filters'] = array( 'information_necessary_for_locati_661'	=>
									array( 'title'  => ts( 'Inforamtion Necessary for Locating Client' )
										),
									'environmental_assessment_662'	=>
									array( 'title'  => ts( 'Environmental Assessment' )
										),
									'additional_information_obtained__663'	=>
									array( 'title'  => ts( 'Additional Information Obtained During Visit' )
										),
									'others_involved_in_dispatch_660'	=>
									array( 'title'  => ts( 'Dispatch Type' ) )
									);
	$this->_columns['five_axis_diagnosis_27']['dao'] = 'CRM_Contact_DAO_Contact';
	$this->_columns['five_axis_diagnosis_27']['alias'] = 'value_five_axis_diagnosis_27';
	$this->_columns['five_axis_diagnosis_27']['filters'] = array( 'axis_5_107'	=>
									array( 'title'  => ts( 'Axis V' )),
									'axis_4_106'	=>
									array( 'title'  => ts( 'Axis IV' )),
									'axis_3_105'	=>
									array( 'title'  => ts( 'Axis III' )),
									'axis_2_104'	=>
									array( 'title'  => ts( 'Axis II' )),
									'axis_1_103'	=>
									array( 'title'  => ts( 'Axis I' ))
									);

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
	$views = parent::_buildViewList('views_mct_team_809_report');
        $this->assign('results', $views); 
	parent::postProcess();
    }

}
