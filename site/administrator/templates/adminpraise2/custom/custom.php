<?php
defined('_JEXEC') or die('Direct access is not allowed');

require_once(dirname(__FILE__).DS.'custom.class.php');
require_once(dirname(__FILE__).DS.'custom.controller.php');
require_once(dirname(__FILE__).DS.'custom.html.php');

global $mainframe;

switch (JRequest::getVar('ap_task'))
{
	case 'list_components':
		APcontroller::list_components();
		break;
		
	case 'gsearch':
		APcontroller::list_search();
		$mainframe->close();
		break;	
}
?>