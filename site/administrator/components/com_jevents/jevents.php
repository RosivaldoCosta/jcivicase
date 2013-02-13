<?php
/**
 * copyright (C) 2008 GWE Systems Ltd - All rights reserved
 */

defined( 'JPATH_BASE' ) or die( 'Direct Access to this location is not allowed.' );

//error_reporting(E_ALL);

jimport('joomla.filesystem.path');

global $option;
define("JEV_COM_COMPONENT",$option);
define("JEV_COMPONENT",str_replace("com_","",$option));

include_once(JPATH_COMPONENT_ADMINISTRATOR.DS.JEV_COMPONENT.".defines.php");

$lang =& JFactory::getLanguage();
$lang->load(JEV_COM_COMPONENT, JEV_ADMINPATH);
$lang->load(JEV_COM_COMPONENT, JEV_PATH);

// disable Zend php4 compatability mode
@ini_set("zend.ze1_compatibility_mode","Off");

// Split tasl into command and task
$cmd = JRequest::getCmd('task', 'cpanel.show');

if (strpos($cmd, '.') != false) {
	// We have a defined controller/task pair -- lets split them out
	list($controllerName, $task) = explode('.', $cmd);
	
	// Define the controller name and path
	$controllerName	= strtolower($controllerName);
	$controllerPath	= JPATH_COMPONENT.DS.'controllers'.DS.$controllerName.'.php';
	$controllerName = "Admin".$controllerName;
	
	// If the controller file path exists, include it ... else lets die with a 500 error
	if (file_exists($controllerPath)) {
		require_once($controllerPath);
	} else {
		JError::raiseError(500, 'Invalid Controller');
	}
} else {
	// Base controller, just set the task 
	$controllerName = null;
	$task = $cmd;
}

JPluginHelper::importPlugin("jevents");

// Set the name for the controller and instantiate it
$controllerClass = ucfirst($controllerName).'Controller';
if (class_exists($controllerClass)) {
	$controller = new $controllerClass();
} else {
	JError::raiseError(500, 'Invalid Controller Class - '.$controllerClass );
}

$config	=& JFactory::getConfig();

// Perform the Request task
$controller->execute($task);

// Redirect if set by the controller
$controller->redirect();
