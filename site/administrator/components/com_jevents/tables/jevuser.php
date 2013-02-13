<?php
/**
 * JEvents Component for Joomla 1.5.x
 *
 * @version     $Id: categoryController.php 1117 2008-07-06 17:20:59Z tstahl $
 * @package     JEvents
 * @copyright   Copyright (C) 2006-2008 JEvents Project Group
 * @license     GNU/GPLv2, see http://www.gnu.org/licenses/gpl-2.0.html
 * @link        http://joomlacode.org/gf/project/jevents
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
* User Table class
*
* @subpackage	Users
* @since 1.0
*/
class TableUser extends JTable
{
	/**
	 * Primary Key
	 *
	 * @var int
	 */
	var $id = null;

	var $user_id = null;
	var $published = null;

	var $cancreate = null;
	var $canedit = null;

	var $canpublishown = null;
	var $candeleteown = null;

	var $canpublishall = null;
	var $candeleteall = null;

	var $canuploadimages = null;
	var $canuploadmovies = null;

	// extras
	var $cancreateown = null;
	var $cancreateglobal = null;
	var $eventslimit = null;
	var $extraslimit = null;

	// common limit for all extras e.g. artists or locations

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 * @since 1.0
	 */
	function __construct() {
		$db =& JFactory::getDBO();
		parent::__construct('#__jev_users', 'id', $db);
	}

	function checkTable(){
		$db =& JFactory::getDBO();
	}

	/**
	 * Overloaded check method to ensure data integrity
	 *
	 * @access public
	 * @return boolean True on success
	 * @since 1.0
	 */
	function check()
	{
		return true;
	}

	function getUsers($ids=array()){
		$where = "";
		if (is_array($ids)){
			if (count($ids)>0){
				JArrayHelper::toInteger($ids);
				$idstring = implode(",",$ids);
				$where = "WHERE tl.id in ($idstring)";
			}
		}
		else {
			$idsstring = intval($ids);
			$where = "WHERE tl.id in ($idstring)";
		}

		$db =& JFactory::getDBO();
		$sql = "SELECT tl.*, ju.name as jname, ju.username  FROM #__jev_users AS tl ";
		$sql .= " LEFT JOIN #__users as ju ON tl.user_id=ju.id ";
		$sql .= $where;

		$db->setQuery( $sql	);
		$users = $db->loadObjectList('id');
		echo $db->getErrorMsg();
		foreach ($users as $key=>$val){
			$user = new TableUser();
			$user->bind(get_object_vars($val));
			$user->jname = $val->jname;
			$user->username = $val->username;
			$users[$key]=$user;
		}
		return $users;
	}

	function getUsersByUserid($userid,$index="id"){
		if (is_array($userid)){
			JArrayHelper::toInteger($userid);
			$userids = implode(",",$userid);
		}
		else {
			$userids = intval($userid);
		}
		$db =& JFactory::getDBO();
		$sql = "SELECT tl.*, ju.name as jname, ju.username  FROM #__jev_users AS tl ";
		$sql .= " LEFT JOIN #__users as ju ON tl.user_id=ju.id ";
		$sql .= " WHERE ju.id IN ( ".$userids." )";
		$db->setQuery( $sql	);
		$users = $db->loadObjectList($index);
		echo $db->getErrorMsg();
		foreach ($users as $key=>$val){
			$user = new TableUser();
			$user->bind(get_object_vars($val));
			$user->jname = $val->jname;
			$user->username = $val->username;
			$users[$key]=$user;
		}
		return $users;
	}

	function authorisedUser($lang=0){
		$user = JFactory::getUser();
		$users = TableUser::getUsersByUserid($user->id,"langid");
		if (count($users)>0 && $lang<=0) return true;
		if (array_key_exists($lang,$users)) return $users[$lang];
		if (count($users)>0 ){
			foreach ($users as $user) {

				if ($user->langid == $lang && $user->published){
					return true;
				}
			}
		}
		return false;
	}

	function canpublishown(){
		if ($this->canpublishown){
			return true;
		}
		return false;
	}

	function candeleteown(){
		if ($this->candeleteown){
			return true;
		}
		return false;
	}

	function canpublishall(){
		if ($this->canpublishall){
			return true;
		}
		return false;
	}

	function candeleteall(){
		if ($this->candeleteall){
			return true;
		}
		return false;
	}


}