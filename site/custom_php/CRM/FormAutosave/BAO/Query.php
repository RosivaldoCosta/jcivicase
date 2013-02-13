<?php

class CRM_FormAutosave_BAO_Query
{
/**
     * static instance to hold the table name
     *
     * @var string
     * @static
     */
    static $_tableName = 'civicrm_form_autosave';
    /**
     * static instance to hold the field values
     *
     * @var array
     * @static
     */
    static $_fields = null;
    /**
     * returns all the column names of this table
     *
     * @access public
     * @return array
     */
    function getFields()
    {
        if (!(self::$_fields)) 
        {
            self::$_fields = array(
                'id' => array(
                    'name' => 'id',
                    'type' => CRM_Utils_Type::T_INT,
                    'title' => ts('ID') ,
                    'required' => true,
                    'import' => true,
                    'where' => 'civicrm_form_autosave.id',
                    'headerPattern' => '',
                    'dataPattern' => '',
                    'export' => true,
                ) ,
                'form_autosave_data' => array(
                    'name' => 'form_autosave_data',
                    'type' => CRM_Utils_Type::T_TEXT,
                    'title' => ts('form_autosave_data') ,
                    'required' => true,
                    'import' => true,
                    'where' => 'civicrm_form_autosave.data',
                    'headerPattern' => '',
                    'dataPattern' => '',
                    'export' => true,
                ) ,
                'start_date' => array(
                    'name' => 'date',
                    'type' => CRM_Utils_Type::T_DATE + CRM_Utils_Type::T_TIME,
                    'title' => ts('Date') ,
                    'required' => true,
                ) 
            );
        }
        return self::$_fields;
    }
    
    /** 
     * build select for Pledge
     * 
     * @return void  
     * @access public  
     */
    static function select( &$query ) 
    { 
//        $query->_select['form_autosave_all'] = "civicrm_form_autosave.id as fas_id, civicrm_form_autosave.data as fas_data";
//        $query->_element['form_autosave_all'] = 1;
//        $query->_tables['form_autosave_all'] = $query->_whereTables['civicrm_form_autosave'] = 1;
    }
    
    static function where( &$query ) 
    {
//        $query->where = '';
    }
    

    static function tableNames( &$tables ) 
    {
        
    }
    
    static function from( $name, $mode, $side ) 
    {
        return $side;
    }
}