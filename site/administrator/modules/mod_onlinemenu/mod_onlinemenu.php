<?php
/**
* @version		$Id: mod_online.php 10381 2008-06-01 03:35:53Z pasamio $
* @package		Joomla
* @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// Get object DB
$db =&  JFactory::getDBO();

// Get the number of logged in users
$query = 'SELECT COUNT( session_id )'
. ' FROM #__session'
. ' WHERE guest <> 1'
;
$db->setQuery($query);
$online_num = intval( $db->loadResult() );

//Print the logged in users message

$online_users  = "<a href='" . $mainframe->getCfg('live_site') . "/administrator/index.php?option=com_users&filter_logged=1'>";
$online_users .= "<span class='loggedin-users'>";
$online_users .= $online_num . "</span></a>";

echo $online_users ;