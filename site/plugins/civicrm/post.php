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
ini_set('display_errors', 1);
define('IFIT_REFERRAL', 105);
define('CHANGE_CASE_STATUS', 16);
define('INTAKE', 13);


/**
 * Example User Plugin
 *
 * @package		Joomla
 * @subpackage	JFramework
 * @since 		1.5
 */
class plgCivicrmPost extends JPlugin
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
    function plgCivicrmPost(&$subject, $config)
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
    function civicrm_post( $op, $objectName, $objectId, &$objectRef)
    {

	$errors = array();

	if( $op === 'create' && $objectName == 'Activity')
	{
	    if( $objectRef->activity_type_id == INTAKE)
	    {
		/* create follow up activity if needed
                                $followupStatus = '';
                                //$values = $form->_submitValues;
                                if ( CRM_Utils_Array::value('followup_activity_type_id', $values) ) {
                                        $followupActivity = CRM_Activity_BAO_Activity::createFollowupActivity( $activity->id, $values);

                                        if ( $followupActivity ) {
                                                $caseParams = array( 'activity_id' => $followupActivity->id, 'case_id'     => $this->_caseId   );

                                                CRM_Case_BAO_Case::processCaseActivity( $caseParams );
                                                $followupStatus = ts("A followup activity has been scheduled.");
                                        }
                                }*/
		//print_r($objectRef);
		//echo $objectId; exit(0);
	    }

	}
	//echo $formName;
	//exit(0);

    }
}
