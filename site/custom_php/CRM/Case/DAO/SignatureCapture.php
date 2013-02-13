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
class CRM_Case_DAO_SignatureCapture extends CRM_Core_DAO
{
    /**
     * static instance to hold the table name
     *
     * @var string
     * @static
     */
    static $_tableName = 'civicrm_value_signature_capture_fields_144';
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
    public $signature_data;
    public $target_id;
    public $entity_id;
    public $signature_type_1249;

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
                'target_id' => array(
                    'name' => 'target_id',
                    'type' => CRM_Utils_Type::T_INT,
                    'title' => ts('Target ID') ,
                    'required' => true,
                    'import' => true,
                    'where' => self::$_tableName.'.target_id',
                    'headerPattern' => '',
                    'dataPattern' => '',
                    'export' => true,
                ) ,
                'entity_id' => array(
                    'name' => 'entity_id',
                    'type' => CRM_Utils_Type::T_INT,
                    'title' => ts('Entity ID') ,
                    'required' => true,
                    'import' => true,
                    'where' => self::$_tableName.'.entity_id',
                    'headerPattern' => '',
                    'dataPattern' => '',
                    'export' => true,
                ) ,
		'signature_type_1249' => array(
                    'name' => 'signature_type_1249',
                    'type' => CRM_Utils_Type::T_STRING,
                    'title' => ts('Signature Type') ,
                    'required' => true,
                    'import' => true,
                    'where' => self::$_tableName.'.signature_type_1249',
                    'headerPattern' => '',
                    'dataPattern' => '',
                    'export' => true,
		),
		'signature_data' => array(
                    'name' => 'signature_data',
                    'type' => CRM_Utils_Type::T_STRING,
                    'title' => ts('Signature Data') ,
                    'required' => true,
                    'import' => true,
                    'where' => self::$_tableName.'.signature_data',
                    'headerPattern' => '',
                    'dataPattern' => '',
                    'export' => true,
		),
		'file_name_804' => array(
                    'name' => 'file_name_804',
                    'type' => CRM_Utils_Type::T_STRING,
                    'title' => ts('File Name') ,
                    'required' => true,
                    'import' => true,
                    'where' => self::$_tableName.'.file_name_804',
                    'headerPattern' => '',
                    'dataPattern' => '',
                    'export' => true,
		),
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
