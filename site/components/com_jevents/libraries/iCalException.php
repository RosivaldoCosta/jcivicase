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

/**
 * Class to handle event exceptions - used to RSS and iCal exports
 *
 */

class iCalException extends JTable  {

	/** @var int Primary key */
	var $ex_id		= null;
	var $rp_id		= null;
	var $eventid = null;
	var $eventdetail_id = null;
	// exception_type 0=delete, 1=other exception 
	var $exception_type = null;


	function iCalException( &$db ) {
		parent::__construct( '#__jevents_exception', 'ex_id', $db );
	}

	function loadByRepeatId($rp_id){
		
		$db =& JFactory::getDBO();
		$sql = "SELECT * FROM #__jevents_exception WHERE rp_id=".intval($rp_id);
		$db->setQuery($sql);
		$data = $db->loadObject();
		if (!$data || is_null($data)){
			return false;
		}
		else {
			$exception = new iCalException($db);
			$exception->bind(get_object_vars($data));
			return $exception;
		}
	}
}
