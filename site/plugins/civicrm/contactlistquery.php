<?php
/**
 * @version		$Id: example.php 11720 2009-03-27 21:27:42Z ian $
 * @package		Joomla
 * @subpackage	JFramework
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.plugin.plugin');


/**
 * Example User Plugin
 *
 * @package		Joomla
 * @subpackage	JFramework
 * @since 		1.5
 */
class plgCivicrmContactlistquery extends JPlugin
{

    /**
     * Constructor
     *
     * For php4 compatability we must not use the __constructor as a constructor for plugins
     * because func_get_args ( void ) returns a copy of all passed arguments NOT references.
     * This causes problems with cross-referencing necessary for the observer design pattern.
     *
     * @param object $subject The object to observe
     * @param 	array  $config  An array that holds the plugin configuration
     * @since 1.5
     */
    function plgCivicrmContactlistquery(&$subject, $config)
    {
	parent::__construct($subject, $config);
    }

    /**
     * Example store user method
     *
     * Method is called pre user data is stored in the database
     *
     * @param 	array		holds the old user data
     * @param 	boolean		true if a new user is stored
     */
    function civicrm_contactListQuery( &$query, $name, $context, $id)
    {
	$query = '
			SELECT DISTINCT(cc.case_id) as id, CONCAT(\'(\',cc.case_id,\') \',c.sort_name) as data
			FROM civicrm_contact c 
			INNER JOIN civicrm_case_contact cc ON c.id=cc.contact_id
			WHERE c.contact_sub_type=\'Client\' AND c.sort_name LIKE \'%'.$name.'%\'
			ORDER BY sort_name';



    }
}
