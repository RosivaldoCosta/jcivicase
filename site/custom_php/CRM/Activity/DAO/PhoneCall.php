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
class CRM_Activity_DAO_PhoneCall extends CRM_Core_DAO
{
    /**
     * static instance to hold the table name
     *
     * @var string
     * @static
     */
    static $_tableName = 'civicrm_value_telephone_contact_138';
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
     * Unique  Other Activity ID
     *
     * @var int unsigned
     */
    public $id;
    /**
     * Contact ID of the person scheduling or logging this Activity. Usually the authenticated user.
     *
     * @var int unsigned
     */
    public $source_contact_id;
    /**
     * Artificial FK to original transaction (e.g. contribution) IF it is not an Activity. Table can be figured out through activity_type_id, and further through component registry.
     *
     * @var int unsigned
     */
    public $source_record_id;
    /**
     * FK to civicrm_option_value.id, that has to be valid, registered activity type.
     *
     * @var int unsigned
     */
    public $activity_type_id;
    /**
     * The subject/purpose/short description of the activity.
     *
     * @var string
     */
    public $subject;
    /**
     * Date and time this activity is scheduled to occur. Formerly named scheduled_date_time.
     *
     * @var datetime
     */
    public $activity_date_time;
    /**
     * Planned or actual duration of activity expressed in minutes. Conglomerate of former duration_hours and duration_minutes.
     *
     * @var int unsigned
     */
    public $duration;
    /**
     * Location of the activity (optional, open text).
     *
     * @var string
     */
    public $location;
    /**
     * Phone ID of the number called (optional - used if an existing phone number is selected).
     *
     * @var int unsigned
     */
    public $phone_id;
    /**
     * Phone number in case the number does not exist in the civicrm_phone table.
     *
     * @var string
     */
    public $phone_number;
    /**
     * Details about the activity (agenda, notes, etc).
     *
     * @var text
     */
    public $details;
    /**
     * ID of the status this activity is currently in. Foreign key to civicrm_option_value.
     *
     * @var int unsigned
     */
    public $status_id;
    /**
     * ID of the priority given to this activity. Foreign key to civicrm_option_value.
     *
     * @var int unsigned
     */
    public $priority_id;
    /**
     * Parent meeting ID (if this is a follow-up item). This is not currently implemented
     *
     * @var int unsigned
     */
    public $parent_id;
    /**
     *
     * @var boolean
     */
    public $is_test;
    /**
     * Activity Medium, Implicit FK to civicrm_option_value where option_group = encounter_medium.
     *
     * @var int unsigned
     */
    public $medium_id;
    /**
     *
     * @var boolean
     */
    public $is_auto;
    /**
     * FK to Relationship ID
     *
     * @var int unsigned
     */
    public $relationship_id;
    /**
     *
     * @var boolean
     */
    public $is_current_revision;
    /**
     * Activity ID of the first activity record in versioning chain.
     *
     * @var int unsigned
     */
    public $original_id;
    /**
     *
     * @var boolean
     */
    public $is_deleted;

    public $case_id;
    public $person_contacted;
    public $org;
    public $call_type;
    public $next_task;
    public $staff;
    public $comments;

    /**
     * class constructor
     *
     * @access public
     * @return civicrm_value_telephone_call_138
     */
    function __construct()
    {
        parent::__construct();
    }
    /**
     * return foreign links
     *
     * @access public
     * @return array
     */
    function &links()
    {
        if (!(self::$_links)) {
            self::$_links = array(
                'source_contact_id' => 'civicrm_contact:id',
                'phone_id' => 'civicrm_phone:id',
                'parent_id' => 'civicrm_activity:id',
                'relationship_id' => 'civicrm_relationship:id',
                'original_id' => 'civicrm_activity:id',
		'entity_id' 	=> 'civicrm_activity:id'
            );
        }
        return self::$_links;
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
                'id' => array(
                    'name' => 'id',
                    'type' => CRM_Utils_Type::T_INT,
                    'title' => ts('Phone Call ID') ,
                    'required' => true,
                    'import' => true,
                    'where' => 'civicrm_value_telephone_contact_138.id',
                    'headerPattern' => '',
                    'dataPattern' => '',
                    'export' => true,
                ) ,
                'entity_id' => array(
                    'name' => 'entity_id',
                    'type' => CRM_Utils_Type::T_INT,
                    'title' => ts('Entity ID') ,
                    'import' => true,
                    'where' => 'civicrm_value_telephone_contact_138.entity_id',
                    'headerPattern' => '/(activity.)?source(.contact(.id)?)?/i',
                    'dataPattern' => '',
                    'export' => true,
                    'FKClassName' => 'CRM_Activity_DAO_Activity',
                ) ,
                'person_contacted_723' => array(
                    'name' => 'person_contacted_723',
                    'type' => CRM_Utils_Type::T_INT,
                    'title' => ts('Person Contacted') ,
                    'required' => true,
                    'import' => true,
                    'where' => 'civicrm_value_telephone_contact_138.person_contacted_723',
                    'headerPattern' => '/(activity.)?type(.id$)/i',
                    'dataPattern' => '',
                    'export' => true,
                    'default' => '',
                ) ,
                'org__724' => array(
                    'name' => 'org__724',
                    'type' => CRM_Utils_Type::T_STRING,
                    'title' => ts('Organization') ,
                    'maxlength' => 255,
                    'size' => CRM_Utils_Type::HUGE,
                    'import' => true,
                    'where' => 'civicrm_value_telephone_contact_138.org__724',
                    'headerPattern' => '/(activity.)?subject/i',
                    'dataPattern' => '',
                    'export' => true,
                ) ,
                'activity_date_time' => array(
                    'name' => 'activity_date_time',
                    'type' => CRM_Utils_Type::T_DATE + CRM_Utils_Type::T_TIME,
                    'title' => ts('Activity Date') ,
                    'import' => true,
                    'where' => 'civicrm_activity.activity_date_time',
                    'headerPattern' => '/(activity.)?date(.time$)?/i',
                    'dataPattern' => '',
                    'export' => true,
                ) ,
                'type_of_call' => array(
                    'name' => 'type_of_call_726',
                    'type' => CRM_Utils_Type::T_STRING,
                ) ,
                'details' => array(
                    'name' => 'details',
                    'type' => CRM_Utils_Type::T_TEXT,
                    'title' => ts('Details') ,
                    'rows' => 8,
                    'cols' => 60,
                    'import' => true,
                    'where' => 'civicrm_activity.details',
                    'headerPattern' => '/(activity.)?detail(s)?$/i',
                    'dataPattern' => '',
                    'export' => true,
                ) ,
                'status_id' => array(
                    'name' => 'status_id',
                    'type' => CRM_Utils_Type::T_INT,
                    'title' => ts('Activity Status Label') ,
                    'import' => true,
                    'where' => 'civicrm_activity.status_id',
                    'headerPattern' => '/(activity.)?status(.label$)?/i',
                    'dataPattern' => '',
                    'export' => true,
                ) ,
                'priority_id' => array(
                    'name' => 'priority_id',
                    'type' => CRM_Utils_Type::T_INT,
                ) ,
                'parent_id' => array(
                    'name' => 'parent_id',
                    'type' => CRM_Utils_Type::T_INT,
                    'FKClassName' => 'CRM_Activity_DAO_Activity',
                ) ,
                'is_test' => array(
                    'name' => 'is_test',
                    'type' => CRM_Utils_Type::T_BOOLEAN,
                    'title' => ts('Test') ,
                    'import' => true,
                    'where' => 'civicrm_activity.is_test',
                    'headerPattern' => '/(is.)?test(.activity)?/i',
                    'dataPattern' => '',
                    'export' => true,
                ) ,
                'medium_id' => array(
                    'name' => 'medium_id',
                    'type' => CRM_Utils_Type::T_INT,
                    'title' => ts('Activity Medium') ,
                    'default' => 'UL',
                ) ,
                'is_auto' => array(
                    'name' => 'is_auto',
                    'type' => CRM_Utils_Type::T_BOOLEAN,
                    'title' => ts('Auto') ,
                ) ,
                'relationship_id' => array(
                    'name' => 'relationship_id',
                    'type' => CRM_Utils_Type::T_INT,
                    'default' => 'UL',
                    'FKClassName' => 'CRM_Contact_DAO_Relationship',
                ) ,
                'is_current_revision' => array(
                    'name' => 'is_current_revision',
                    'type' => CRM_Utils_Type::T_BOOLEAN,
                    'title' => ts('Is this activity a current revision in versioning chain?') ,
                    'import' => true,
                    'where' => 'civicrm_activity.is_current_revision',
                    'headerPattern' => '/(is.)?(current.)?(revision|version(ing)?)/i',
                    'dataPattern' => '',
                    'export' => true,
                    'default' => '',
                ) ,
                'original_id' => array(
                    'name' => 'original_id',
                    'type' => CRM_Utils_Type::T_INT,
                    'FKClassName' => 'CRM_Activity_DAO_Activity',
                ) ,
                'is_deleted' => array(
                    'name' => 'is_deleted',
                    'type' => CRM_Utils_Type::T_BOOLEAN,
                    'title' => ts('Activity is in the Trash') ,
                    'import' => true,
                    'where' => 'civicrm_activity.is_deleted',
                    'headerPattern' => '/(activity.)?(trash|deleted)/i',
                    'dataPattern' => '',
                    'export' => true,
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
                        self::$_import['activity'] = & $fields[$name];
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
                        self::$_export['activity'] = & $fields[$name];
                    } else {
                        self::$_export[$name] = & $fields[$name];
                    }
                }
            }
        }
        return self::$_export;
    }
}
