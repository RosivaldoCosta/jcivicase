<?php
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

$_REQUEST['no_html'] = 1;
if (!isset($_SESSION['chatHistory'])) {
	$_SESSION['chatHistory'] = array();	
}

if (!isset($_SESSION['openChatBoxes'])) {
	$_SESSION['openChatBoxes'] = array();	
}

// Require the com_content helper library
require_once (JPATH_COMPONENT.DS.'controller.php');

// Create the controller
$controller = new JboloController( );

// Perform the Request task
$controller->execute(JRequest::getCmd('action'));

// Redirect if set by the controller
$controller->redirect();
