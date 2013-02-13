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
require_once 'CRM/Core/DAO.php';
require_once 'CRM/Utils/Type.php';
class CRM_Case_DAO_Discharge extends CRM_Core_DAO
{
    /**
     * static instance to hold the table name
     *
     * @var string
     * @static
     */
    static $_tableName = 'civicrm_value_discharge_28';
    /**
     * static instance to hold the field values
     *
     * @var array
     * @static
     */
    static $_fields = null;
    /**
     * static instance to hold the FK relationships
     *
     * @var string
     * @static
     */
    static $_links = null;
    /**
     * static instance to hold the values that can
     * be imported / apu
     *
     * @var array
     * @static
     */
    static $_import = null;
    /**
     * static instance to hold the values that can
     * be exported / apu
     *
     * @var array
     * @static
     */
    static $_export = null;
    /**
     * static value to see if we should log any modifications to
     * this table in the civicrm_log table
     *
     * @var boolean
     * @static
     */
    static $_log = false;

    /**
     * Unique Discharge ID
     *
     * @var int unsigned
     */
    public $id;

    /**
     * Id of first case category.
     *
     * @var string
     */
    public $case_id;

    /**
     * Short name of the case.
     *
     * @var string
     */
    public $subject;

	public $first_name;
	public $last_name;
	public $Gender;
	public $DOB;
	public $Age;
	public $SSN;
	public $DaysOpen;
	public $DateClosed;
	public $DateOpened;
	public $UnabletoContactAdminClosed;
	public $CaseOpenedBy;
	public $ReferralSource;
	public $InsuranceStatus;
	public $Lethality;
	public $AgeBracket;
	public $FocalIssue;
	public $CRSAction;
	public $ReferralsGiven;
	public $MHProviderLinkedAdmittedTo;
	public $CurrentProvider;
	public $NewProvider;
	public $Outcome;
	public $PhysicianDate1;
	public $PhysicianDate2;
	public $PhysicianDate3;
	public $PhysicianDate4;
	public $TherapistDate1;
	public $TherapistDate2;
	public $TherapistDate3;
	public $TherapistDate4;
	public $Hospitalized;
	public $ClientAssessedForCrisisBed;
	public $IHITIFITTotalVisits;
	public $COTAATotalVisits;
	public $WithPsychiatrist;
	public $Hotel;
	public $TotalNumOfNights;
	public $Transportation;
	public $TotalNumberOfRides;
	public $MedVoucherProvided;
	public $NameOfMeds;
	public $DateSupplied;
	public $AmountSupplied;
	public $RateSatisfactionWithCRS;
	public $CRSStaffAccessibleAvailable;
	public $CRSResponsive;
	public $InformationProvidedCorrect;
	public $ReferralsGivenAppropriate;
	public $ClientSatisfactionScore;
	public $Comments;
	public $CompletedBy;
	public $TimestampDate;

    /**
     * class constructor
     *
     * @access public
     * @return civicrm_value_discharge_28
     */
    function __construct()
    {
        parent::__construct();
    }
    /**
     * returns all the column names of this table
     *
     * @access public
     * @return array
     */
    function &fields()
    {
        if (!(self::$_fields)) {
            self::$_fields = array(
                'discharge_id' => array(
                    'name' => 'id',
                    'type' => CRM_Utils_Type::T_INT,
                    'title' => ts('Discharge ID') ,
                    'required' => true,
                    'import' => true,
                    'where' => 'civicrm_value_discharge_28.id',
                    'headerPattern' => '',
                    'dataPattern' => '',
                    'export' => true,
                ) ,
                'case_id' => array(
                    'name' => 'case_id',
                    'type' => CRM_Utils_Type::T_INT,
                    'title' => ts('Case ID') ,
                    'required' => true,
                    'import' => true,
                    'where' => 'civicrm_value_discharge_28.case_id',
                    'headerPattern' => '',
                    'dataPattern' => '',
                    'export' => true,
                ) ,
		'first_name' => array(
                    'name' => 'first_name',
                    'type' => CRM_Utils_Type::T_STRING,
                    'title' => ts('First Name') ,
                    'maxlength' => 128,
                    'size' => CRM_Utils_Type::HUGE,

		),
		'last_name' => array(
                    'name' => 'last_name',
                    'type' => CRM_Utils_Type::T_STRING,
                    'title' => ts('Last Name') ,
                    'maxlength' => 128,
                    'size' => CRM_Utils_Type::HUGE,
		),
		'gender'  => array(

		),
		'dob'  => array(
                    'name' => 'dob',
                    'type' => CRM_Utils_Type::T_DATE,
                    'title' => ts('Date of Birth') ,
                    'import' => true,
                    'where' => 'civicrm_value_discharge_28.dob',
                    'headerPattern' => '',
                    'dataPattern' => '',
                    'export' => true,
		),
		'age'  => array(
                    'name' => 'age',
                    'type' => CRM_Utils_Type::T_INT,
                    'title' => ts('Age') ,
                    'required' => true,
                    'import' => true,
                    'where' => 'civicrm_value_discharge_28.age',
                    'headerPattern' => '',
                    'dataPattern' => '',
                    'export' => true,
		),
		'ssn'  => array(
                    'name' => 'ssn',
                    'type' => CRM_Utils_Type::T_STRING,
                    'title' => ts('SSN') ,
                    'maxlength' => 128,
                    'size' => CRM_Utils_Type::HUGE,
		),
		'days_open'  =>  array(
                    'name' => 'days_open',
                    'type' => CRM_Utils_Type::T_INT,
                    'title' => ts('Days Open') ,
                    'required' => true,
                    'import' => true,
                    'where' => 'civicrm_value_discharge_28.days_open',
                    'headerPattern' => '',
                    'dataPattern' => '',
                    'export' => true,

		),
		'date_closed'  => array(
                    'name' => 'dateclosed',
                    'type' => CRM_Utils_Type::T_DATE,
                    'title' => ts('Date Closed') ,
                    'import' => true,
                    'where' => 'civicrm_value_discharge_28.date_closed',
                    'headerPattern' => '',
                    'dataPattern' => '',
                    'export' => true,
		),
		'date_opened'  => array(
                    'name' => 'date_opened',
                    'type' => CRM_Utils_Type::T_DATE,
                    'title' => ts('Date Opened') ,
                    'import' => true,
                    'where' => 'civicrm_value_discharge_28.date_opened',
                    'headerPattern' => '',
                    'dataPattern' => '',
                    'export' => true,
		),
		'UnabletoContactAdminClosed'  =>  array(

		),
		'case_opened_by'  =>  array(
                    'name' => 'case_opened_by',
                    'type' => CRM_Utils_Type::T_DATE,
                    'title' => ts('Case Opened By') ,
                    'import' => true,
                    'where' => 'civicrm_value_discharge_28.case_opened_by',
                    'headerPattern' => '',
                    'dataPattern' => '',
                    'export' => true,
		),
		'ReferralSource'  =>  array(

		),
		'insurance_status'  => array(

		),
		'lethality'	=> array(

		),
		'age_bracket'	=> array(

		),
		'focal_issue'	=>  array(
	
		),
		'crs_action'	=> array(

		),
		'physician_date_4'	=> array(
                    'name' => 'physician_date_4',
                    'type' => CRM_Utils_Type::T_DATE,
                    'title' => ts('Physician Date') ,
                    'import' => true,
                    'where' => 'civicrm_value_discharge_28.physician_date_4',
                    'headerPattern' => '',
                    'dataPattern' => '',
                    'export' => true,
		),
		'physician_date_3'	=> array(
                    'name' => 'physician_date_3',
                    'type' => CRM_Utils_Type::T_DATE,
                    'title' => ts('Physician Date') ,
                    'import' => true,
                    'where' => 'civicrm_value_discharge_28.physician_date_3',
                    'headerPattern' => '',
                    'dataPattern' => '',
                    'export' => true,
		),
		'physician_date_2'	=> array(
                    'name' => 'physician_date_2',
                    'type' => CRM_Utils_Type::T_DATE,
                    'title' => ts('Physician Date') ,
                    'import' => true,
                    'where' => 'civicrm_value_discharge_28.physician_date_2',
                    'headerPattern' => '',
                    'dataPattern' => '',
                    'export' => true,
		),
		'physician_date_1'	=> array(
                    'name' => 'physician_date_1',
                    'type' => CRM_Utils_Type::T_DATE,
                    'title' => ts('Physician Date') ,
                    'import' => true,
                    'where' => 'civicrm_value_discharge_28.physician_date_1',
                    'headerPattern' => '',
                    'dataPattern' => '',
                    'export' => true,
		),
		'outcome'	=> array(

		),
		'new_provider'	=> array(

		),
		'current_provider'	=> array(

		),
		'completed_date'	=> array(
                    'name' => 'completed_date',
                    'type' => CRM_Utils_Type::T_DATE,
                    'title' => ts('Completed Date') ,
                    'import' => true,
                    'where' => 'civicrm_value_discharge_28.completed_date',
                    'headerPattern' => '',
                    'dataPattern' => '',
                    'export' => true,
		),
		'CompletedBy'	=> array(

		),
		'comments'	=> array(
                    'name' => 'comments',
                    'type' => CRM_Utils_Type::T_TEXT,
                    'title' => ts('Comments') ,
                    'rows' => 8,
                    'cols' => 60,
		),
		'ClientSatisfactionScore'	=> array(

		),
		'ReferralsGivenAppropriate'	=> array(

		),
		'InformationProvidedCorrect'	=> array(

		),
		'CRSResponsive'	=> array(

		),
		'CRSStaffAccessibleAvailable'	=> array(

		),
		'RateSatisfactionWithCRS'	=> array(

		),
		'amount_supplied'	=> array(
                    'name' => 'amount_supplied',
                    'type' => CRM_Utils_Type::T_STRING,
                    'title' => ts('Amount Supplied') ,
                    'maxlength' => 128,
                    'size' => CRM_Utils_Type::HUGE,
		),
		'date_supplied'	=> array(
                    'name' => 'date_supplied',
                    'type' => CRM_Utils_Type::T_DATE,
                    'title' => ts('Date Supplied') ,
                    'import' => true,
                    'where' => 'civicrm_value_discharge_28.date_supplied',
                    'headerPattern' => '',
                    'dataPattern' => '',
                    'export' => true,
		),
		'name_of_meds'	=> array(
                    'name' => 'name_of_meds',
                    'type' => CRM_Utils_Type::T_STRING,
                    'title' => ts('Name Of Medication') ,
                    'maxlength' => 128,
                    'size' => CRM_Utils_Type::HUGE,
		),
		'med_voucher_provided'	=> array(

		),
		'total_number_of_rides'	=> array(
                    'name' => 'total_number_of_rides',
                    'type' => CRM_Utils_Type::T_INT,
                    'title' => ts('Total Number of Rides') ,
                    'required' => true,
                    'import' => true,
                    'where' => 'civicrm_value_discharge_28.total_number_of_rides',
                    'headerPattern' => '',
                    'dataPattern' => '',
                    'export' => true,
		),
		'transportation'	=> array(
                    'name' => 'transportation',
                    'type' => CRM_Utils_Type::T_BOOLEAN,
                    'title' => ts('Transportation') ,
                    'import' => true,
                    'where' => 'civicrm_value_discharge_28.transportation',
                    'headerPattern' => '',
                    'dataPattern' => '',
                    'export' => true,

		),
		'total_num_of_nights'	=> array(
                    'name' => 'total_num_of_nights',
                    'type' => CRM_Utils_Type::T_INT,
                    'title' => ts('Total Number of Nights') ,
                    'required' => true,
                    'import' => true,
                    'where' => 'civicrm_value_discharge_28.total_num_of_nights',
                    'headerPattern' => '',
                    'dataPattern' => '',
                    'export' => true,
		),
		'Hotel'	=> array(

		),
		'WithPsychiatrist'	=> array(

		),
		'COTAATotalVisits'	=> array(
                    'name' => 'cotaa_total_visits',
                    'type' => CRM_Utils_Type::T_STRING,
                    'title' => ts('COTAA Total Visits') ,
                    'maxlength' => 128,
                    'size' => CRM_Utils_Type::HUGE,
		),
		'ihit_ifit_total_visits'	=> array(
                    'name' => 'ihit_ifit_total_visits',
                    'type' => CRM_Utils_Type::T_STRING,
                    'title' => ts('IHIT/IFIT Total Visits') ,
                    'maxlength' => 128,
                    'size' => CRM_Utils_Type::HUGE,
		),
		'client_assessed_for_crisis_bed'	=> array(
                    'name' => 'client_assessed_for_crisis_bed',
                    'type' => CRM_Utils_Type::T_BOOLEAN,
                    'title' => ts('If Yes, Was Client Assessed For Crisis Bed?') ,
                    'import' => true,
                    'where' => 'civicrm_value_discharge_28.client_assessed_for_crisis_bed',
                    'headerPattern' => '',
                    'dataPattern' => '',
                    'export' => true,
		),
		'hospitalized'	=> array(
                    'name' => 'hospitalized',
                    'type' => CRM_Utils_Type::T_BOOLEAN,
                    'title' => ts('Hospitalized') ,
                    'import' => true,
                    'where' => 'civicrm_value_discharge_28.hospitalized',
                    'headerPattern' => '',
                    'dataPattern' => '',
                    'export' => true,

		),
		'therapist_date_4'	=> array(
                    'name' => 'therapist_date_4',
                    'type' => CRM_Utils_Type::T_DATE,
                    'title' => ts('Therapist Date') ,
                    'import' => true,
                    'where' => 'civicrm_value_discharge_28.therapist_date_4',
                    'headerPattern' => '',
                    'dataPattern' => '',
                    'export' => true,
		),
		'therapist_date_3'	=> array(
                    'name' => 'therapist_date_3',
                    'type' => CRM_Utils_Type::T_DATE,
                    'title' => ts('Therapist Date') ,
                    'import' => true,
                    'where' => 'civicrm_value_discharge_28.therapist_date_3',
                    'headerPattern' => '',
                    'dataPattern' => '',
                    'export' => true,
		),
		'therapist_date_2'	=> array(
                    'name' => 'therapist_date_2',
                    'type' => CRM_Utils_Type::T_DATE,
                    'title' => ts('Therapist Date') ,
                    'import' => true,
                    'where' => 'civicrm_value_discharge_28.therapist_date_2',
                    'headerPattern' => '',
                    'dataPattern' => '',
                    'export' => true,
		),
		'therapist_date_1'	=> array(
                    'name' => 'therapist_date_1',
                    'type' => CRM_Utils_Type::T_DATE,
                    'title' => ts('Therapist Date') ,
                    'import' => true,
                    'where' => 'civicrm_value_discharge_28.therapist_date_1',
                    'headerPattern' => '',
                    'dataPattern' => '',
                    'export' => true,
		),
                'subject' => array(
                    'name' => 'subject',
                    'type' => CRM_Utils_Type::T_STRING,
                    'title' => ts('Subject') ,
                    'maxlength' => 128,
                    'size' => CRM_Utils_Type::HUGE,
                ) ,
            );
        }
        return self::$_fields;
    }
    /**
     * returns the names of this table
     *
     * @access public
     * @return string
     */
    function getTableName()
    {
        return self::$_tableName;
    }
    /**
     * returns if this table needs to be logged
     *
     * @access public
     * @return boolean
     */
    function getLog()
    {
        return self::$_log;
    }
    /**
     * returns the list of fields that can be imported
     *
     * @access public
     * return array
     */
    function &import($prefix = false)
    {
        if (!(self::$_import)) {
            self::$_import = array();
            $fields = & self::fields();
            foreach($fields as $name => $field) {
                if (CRM_Utils_Array::value('import', $field)) {
                    if ($prefix) {
                        self::$_import['case'] = & $fields[$name];
                    } else {
                        self::$_import[$name] = & $fields[$name];
                    }
                }
            }
        }
        return self::$_import;
    }
    /**
     * returns the list of fields that can be exported
     *
     * @access public
     * return array
     */
    function &export($prefix = false)
    {
        if (!(self::$_export)) {
            self::$_export = array();
            $fields = & self::fields();
            foreach($fields as $name => $field) {
                if (CRM_Utils_Array::value('export', $field)) {
                    if ($prefix) {
                        self::$_export['case'] = & $fields[$name];
                    } else {
                        self::$_export[$name] = & $fields[$name];
                    }
                }
            }
        }
        return self::$_export;
    }
}
