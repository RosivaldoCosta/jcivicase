<?php
/**
* @version		$Id: mod_title.php 10381 2008-06-01 03:35:53Z pasamio $
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

// Get the component title div
$title = $mainframe->getCfg('sitename');

// Echo title if it exists
if (!empty($title)) {
	echo '<span style="position:relative;float:right;margin:9px 8px 0 0;overflow:hidden;padding-left:20px;" class="sitename">'.$title.'</span>';
}
