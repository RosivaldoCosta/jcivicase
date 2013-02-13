<?php
/*
 Copyright (C) 2011-2012 Campbell Consulting Studio, LLC All rights reserved.
 ****************************************************************
 * @package 	ExtScheduler	
 * @copyright	Copyright (C) 2011-2012 Campbell Consulting Studio, LLC
 * @license	GNU/GPL, see http://www.gnu.org/licenses/gpl-2.0.html
 *
 *
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

require_once ( JPATH_SITE."/includes/application.php" );

$view = JRequest::getVar('view','');

if(!isset($controller) || $controller == "")
{
	$controller = 'frontdesk';
}

$controller = JRequest::getVar('controller',$controller );

//set the controller page
require_once (JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php');

// Create the controller sv_sebController
$classname  = $controller.'controller';

//create a new class of classname and set the default task:display
$controller = new $classname( array('default_task' => 'display') );

// Perform the Request task
$controller->execute( JRequest::getVar('task' ));


// Redirect if set by the controller
$controller->redirect();

?>
