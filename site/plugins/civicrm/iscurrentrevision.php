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
jimport('joomla.error.log');

require_once('CRM/Activity/BAO/Activity.php');

ini_set('display_errors', 1);


/**
 * Example User Plugin
 *
 * @package		Joomla
 * @subpackage	JFramework
 * @since 		1.5
 */
class plgCivicrmIscurrentrevision extends JPlugin
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
    function plgCivicrmIscurrentrevision(&$subject, $config)
    {
	parent::__construct($subject, $config);
    }

    public function civicrm_caseHistory( &$activities )
    {
	//CRM_Activity_BAO_Activity::isCurrentRevision($key, __FUNCTION__);
    }

    /**
     * Example store user method
     *
     * Method is called before user data is stored in the database
     *
     * @param 	array		holds the old user data
     * @param 	boolean		true if a new user is stored
     */
    function civicrm_post( $op, $objectName, $objectId, &$objectRef)
    {
	$log = &JLog::getInstance('iscurrentrevision-'.date('Y-d-m').'.log');

	$errors = array();
	if ($op == 'create' && $objectName == 'Activity')
	{

	}
	else if ($op == 'edit' && $objectName == 'Case Activity')
	{
	    CRM_Activity_BAO_Activity::isCurrentRevision($objectRef->id);

	}

    }
}
