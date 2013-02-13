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
//ini_set('display_errors', 1);


/**
 * Example User Plugin
 *
 * @package		Joomla
 * @subpackage	JFramework
 * @since 		1.5
 */
class plgCivicrmAACRSIntake extends JPlugin
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
    function plgCivicrmAACRSIntake(&$subject, $config)
    {
	parent::__construct($subject, $config);
    }

    public function civicrm_buildForm( $formName, &$form )
    {
//echo "here $formName";
	if( ($formName == 'CRM_Case_Form_Case' || $formName == 'CRM_Case_Form_Activity') && isset($form->_subType) && $form->_subType == 13)
	{
	    $homeless_var = $this->params->get('homeless_var');
//echo " in";
//echo '<pre>' . print_r($form,true) . '</pre>'	;
	    foreach($form->_elementIndex as $k => $v)
	    {
		$pattern = '/^' . $homeless_var . '_/';
//            	$pattern = '/^custom_28_/';
//        		$pattern = '/^custom_1669_/';
		if( preg_match($pattern, $k) > 0)
		{
		    $element = $form->getElement($k) ;
		    $form->addRule($k, 'Homeless is required ', 'required');
		}
	    }
	}
    }
}
