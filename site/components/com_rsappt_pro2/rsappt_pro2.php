<?php
/*
 ****************************************************************
 Copyright (C) 2008-2010 Soft Ventures, Inc. All rights reserved.
 ****************************************************************
 * @package	Appointment Booking Pro - ABPro
 * @copyright	Copyright (C) 2008-2010 Soft Ventures, Inc. All rights reserved.
 * @license	GNU/GPL, see http://www.gnu.org/licenses/gpl-2.0.html
 *
 * ABPro is distributed WITHOUT ANY WARRANTY, or implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * This header must not be removed. Additional contributions/changes
 * may be added to this header as long as no information is deleted.
 *
 ************************************************************
 The latest version of ABPro is available to subscribers at:
 http://www.appointmentbookingpro.com/
 ************************************************************
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

require_once ( JPATH_SITE."/administrator/components/com_rsappt_pro2/functions_pro2.php" );
require_once ( JPATH_SITE."/administrator/components/com_rsappt_pro2/sendmail_pro2.php" );

//define("WINDOWS", false);

$view = JRequest::getVar('view',''); 
if($view == "advadmin"){
	$controller = "admin";
} else if($view == "month" || $view == "day" || $view == "week"){
	// term 'view' used by front_desk calendar (day/month/week)
	$controller = "ajax";
} else {
	$controller = $view; 
}
if($controller == ""){
	$controller = JRequest::getVar('controller','' ); 
}

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