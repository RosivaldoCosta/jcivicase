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
class CRM_Case_DAO_ChangeCaseStatus extends CRM_Core_DAO
{
    /**
     * static instance to hold the table name
     *
     * @var string
     * @static
     */
    static $_tableName = 'civicrm_case_change_status';
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
    static $_log = true;

    /**
     * Unique ID
     *
     * @var int unsigned
     */
    public $id;

    /**
     * Id of the status before the change 
     *
     * @var string
     */
    public $current_case_status_id;

    /**
     * Id of the status after the change 
     *
     * @var string
     */
    public $case_status_id;

    /**
     * class constructor
     *
     * @access public
     * @return civicrm_case_change_status
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
                'id' => array(
                    'name' => 'id',
                    'type' => CRM_Utils_Type::T_INT,
                    'title' => ts('ID') ,
                    'required' => true,
                    'import' => true,
                    'where' => self::$_tableName .'.id',
                    'headerPattern' => '',
                    'dataPattern' => '',
                    'export' => true,
                ) ,
                'current_case_status_id' => array(
                    'name' => 'current_case_status_id',
                    'type' => CRM_Utils_Type::T_INT,
                    'title' => ts('Current Case Status') ,
                    'required' => true,
                    'import' => true,
                    'where' => self::$_tableName.'.current_case_status_id',
                    'headerPattern' => '',
                    'dataPattern' => '',
                    'export' => true,
                ) ,
		'case_status_id' => array(
                    'name' => 'case_status_id',
                    'type' => CRM_Utils_Type::T_INT,
                    'title' => ts('Case Status') ,
                    'required' => true,
                    'import' => true,
                    'where' => self::$_tableName.'.case_status_id',
                    'headerPattern' => '',
                    'dataPattern' => '',
                    'export' => true,
		),
		'activity_id' => array(
                    'name' => 'activity_id',
                    'type' => CRM_Utils_Type::T_INT,
                    'title' => ts('Activity ID') ,
                    'required' => true,
                    'import' => true,
                    'where' => self::$_tableName.'.activity_id',
                    'headerPattern' => '',
                    'dataPattern' => '',
                    'export' => true,
		)
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
