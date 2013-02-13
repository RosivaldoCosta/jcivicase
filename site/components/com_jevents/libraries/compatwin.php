<?php
/**
 * JEvents Component for Joomla 1.5.x
 *
 * @version     $Id: compatwin.php 1057 2008-04-21 18:06:33Z tstahl $
 * @package     JEvents
 * @copyright   Copyright (C) 2006-2008 JEvents Project Group
 * @license     GNU/GPLv2, see http://www.gnu.org/licenses/gpl-2.0.html
 * @link        http://joomlacode.org/gf/project/jevents
 */

/**
 * Compatibility functions for Windows 
 * 
 */
class JEV_CompatWin {

	/**
	 * Add unsupported parameters for Windows to strftime()
	 *
	 */
	function win_strftime($format='', $timestamp=null) {

		if (!$timestamp) $timestamp = time();

		global $mainframe;
		$mainframe->set('jevents.strftime', $timestamp);

		$patterns = array('/%C/', '/%D/', '/%e/', '/%g/', '/%G/',
						  '/%h/', '/%n/', '/%r/', '/%R/', '/%t/',
						  '/%T/', '/%u/', '/%V/' );

		//replace unsupported format string specifiers
		$format = preg_replace_callback($patterns,
			array('JEV_CompatWin', '_cb_strftime'),
			$format);

		return strftime($format, $timestamp);

	}

	/**
	 * Callback function
	 *
	 * @static
	 * @param mixed $pattern	array() search pattern or int date
	 */
	function  _cb_strftime($pattern) {

		// timestamp used during callback
		global $mainframe;
		$ts = $mainframe->get('jevents.strftime', time());

		switch ($pattern[0]) {
			case '%C': return sprintf("%02d", date("Y", $ts) / 100); break;
			case '%D': return '%m/%d/%y'; break;
			case '%e': return sprintf("%' 2d", date("j", $ts)); break;
			case '%g': return strftime('%y', JEV_CompatWin::_getThursdayOfWeek($ts)); break;
			case '%G': return strftime('%Y', JEV_CompatWin::_getThursdayOfWeek($ts)); break;
			case '%h': return '%b'; break;
			case '%n': return "\n"; break;
			case '%r': return '%I:%M:%S %p'; break;
			case '%R': return '%H:%M'; break;
			case '%t': return "\t"; break;
			case '%T': return '%H:%M:%S'; break;
			case '%u': return ($w = date("w", $ts)) ? $w : 7; break;
			case '%V': return JEV_CompatWin::_getWeekNumberISO8601($ts); break;
			default:   return ' unknown specifier! ';
		}
	}

	/**
	 * Calculate thursday in the same week of date
	 *
	 * @static
	 * @param int $date date
	 * @return int date
	 */
	function _getThursdayOfWeek($date) {

		$dayofweek = strftime('%w', $date);
		if ($dayofweek == 0) $dayofweek =7;
		if ($dayofweek < 4) {
			return strtotime('next thursday', $date);
		} elseif ($dayofweek > 4) {
			return strtotime('last thursday', $date);
		} else {
			return $date;
		}
	}

	/**
	 * Get week number according ISO 8601
	 *
	 * @static
	 * @param int $date date
	 * @return int weeknumber
	 */
	function _getWeekNumberISO8601($date) {

		$thursday	= JEV_CompatWin::_getThursdayOfWeek($date);
		$thursday_Y	= strftime('%Y', $thursday);
		$first_th	= JEV_CompatWin::_getThursdayOfWeek(strtotime($thursday_Y.'-01-04'));
		return ((strftime('%j', $thursday) - strftime('%j', $first_th)) / 7 + 1);

	}
}
?>