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

/**
 * This class contains all the function that are called using AJAX
 */
class CRM_FormAutosave_AJAX
{
    /**
     * Function for building Pledge Name combo box
     */    
    function saveFields( &$config ) 
    {
        require_once 'CRM/Utils/Type.php';
        
        $data = array();
        $form_params = '';
        $form_values = '';
        $form_emel_name   = CRM_Utils_Array::value( 'form_emel_name', $_POST );
        $form_emel_name = explode(',', $form_emel_name);
        
        foreach ($form_emel_name as $elem)
        {
            $data[$elem] = htmlentities($_POST[$elem], ENT_QUOTES);
            	
            if(strrpos($elem, "[") > 0)
    		{
      			$pos = strrpos($elem, '[');
      			$str = substr($elem, 0,$pos);

      			if(is_array($_POST[$str]))
      			{
            		foreach($_POST[$str] as $k => $val)
            		{
          				if(strrpos($elem,$k) > 0)
          				{
            				$data[$str][$k] = $val;
          				}

        			}
      			}
    		}
        }

        $json_data = json_encode($data);
        
        $form_params = '`data`,`url`,`time`';
        $form_values = "'".$json_data."', '".$_POST['form_url']."', NOW()";
        
        $form_url = parse_url($_POST['form_url']);
        parse_str($form_url['query']);
        if (isset($caseid) && $caseid)
        {
            $form_params .= ', `case_id`';
            $form_values .= ', ' . $caseid;
        }
        if (isset($atype) && $atype)
        {
            $form_params .= ', `activity_type`';
            $form_values .= ', ' . $atype;
        }
        else
        {
            if (isset($action) && isset($id))
            {
                require_once 'CRM/Activity/DAO/Activity.php';
                $activity = new CRM_Activity_DAO_Activity( );
                $activity->id                = $id;
                if ( $activity->find( true ) )
                {
                    $form_params .= ', `activity_type`';
                    $form_values .= ', ' . $activity->activity_type_id;
                }
            }
            else
            {
                if ($action == 'add' && isset($context) && $context == 'case')
                {
                    $form_params .= ', `activity_type`';
                    $form_values .= ', 13';
                }
            }
        }
        if (isset($cid) && $cid)
        {
            $form_params .= ', `contact_id`';
            $form_values .= ', ' . $cid;
        }
        $sql = "insert into `form_autosave_data` (".$form_params.") values (".$form_values.")";
        //$sql = "insert into `form_autosave_data` (`data`,`url`,`time`) values ('".htmlentities($json_data, ENT_QUOTES)."', '".$_POST['form_url']."', NOW())";

        CRM_Core_DAO::executeQuery($sql);
        
        exit();
    } 

}
