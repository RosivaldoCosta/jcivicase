<?php
/**
 * JEvents Component for Joomla 1.5.x
 *
 * @version     $Id: helper.php 1149 2008-08-19 16:58:34Z tstahl $
 * @package     JEvents
 * @copyright   Copyright (C) 2006-2008 JEvents Project Group
 * @license     GNU/GPLv2, see http://www.gnu.org/licenses/gpl-2.0.html
 * @link        http://joomlacode.org/gf/project/jevents
 */
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * Utility class that holds an instanceof an iCalICSFile and its associated collection
 * of iCalEvent
 *
 */
class jIcal {
	var $icalFile;
	var $icalEvents;

	function iCal(){
		$this->icalEvents=array();
	}
}
