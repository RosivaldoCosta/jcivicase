<?php
/**
 * JEvents Component for Joomla 1.5.x
 *
 * @version     $Id: iCalImport.php 1150 2008-08-19 17:02:52Z tstahl $
 * @package     JEvents
 * @copyright   Copyright (C) 2006-2008 JEvents Project Group
 * @license     GNU/GPLv2, see http://www.gnu.org/licenses/gpl-2.0.html
 * @link        http://joomlacode.org/gf/project/jevents
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );


class iCalRepetition extends JTable  {

	/** @var int Primary key */
	var $rp_id		= null;
	var $eventid = null;
	var $eventdetail_id = null;
	var $startrepeat = null;
	var $endrepeat =null;

	function iCalRepetition( &$db ) {
		parent::__construct( '#__jevents_repetition', 'rp_id', $db );
	}
}
