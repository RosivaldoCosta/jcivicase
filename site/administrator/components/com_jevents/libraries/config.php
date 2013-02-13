<?php
/**
 * JEvents Component for Joomla 1.5.x
 *
 * @version     $Id: config.php 1117 2008-07-06 17:20:59Z tstahl $
 * @package     JEvents
 * @copyright   Copyright (C) 2006-2008 JEvents Project Group
 * @license     GNU/GPLv2, see http://www.gnu.org/licenses/gpl-2.0.html
 * @link        http://joomlacode.org/gf/project/jevents
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * convenience wrapper for config - to ensure backwards compatability
 */
class JEVConfig extends JParameter
{
	function &getInstance($inifile='') {
		return JComponentHelper::getParams("com_jevents");
	}

}
