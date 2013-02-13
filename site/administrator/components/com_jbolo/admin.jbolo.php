<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

// Require the base controller
require_once (JPATH_COMPONENT.DS.'controller.php');

// Require specific controller if requested
if($controller = JRequest::getWord('controller')) {
	$path = JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';
	if (file_exists($path))
		require_once $path;
	else
		$controller = '';
}

// Create the controller
$classname	= 'JboloController'.ucfirst($controller);
$controller = new $classname( );

// Perform the Request task
if (JRequest::getCmd('action'))
{
    $controller->execute(JRequest::getCmd('action'));
}
else
{
    $controller->execute(JRequest::getCmd('task'));
}

// Redirect if set by the controller
$controller->redirect();
