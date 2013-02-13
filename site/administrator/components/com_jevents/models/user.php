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

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.model' );
JLoader::import("jevuser",JPATH_COMPONENT_ADMINISTRATOR."/tables/");

/**
 * @package		Joom!Fish
 * @subpackage	User
 */
class AdminUserModelUser extends JModel
{
	/**
	 * @var string	name of the current model
	 * @access private
	 */
	var $_modelName = 'user';

	/**
	 * @var array list of current users
	 * @access private
	 */
	var $_users = null;
	
	/**
	 * default constrcutor
	 */
	function __construct() {
		parent::__construct();
		
		$app	= &JFactory::getApplication();
		$option = JRequest::getVar('option', '');
		// Get the pagination request variables
		$limit		= $app->getUserStateFromRequest( 'global.list.limit', 'limit', $app->getCfg('list_limit'), 'int' );
		$limitstart	= $app->getUserStateFromRequest( $option.'.limitstart', 'limitstart', 0, 'int' );

		// In case limit has been changed, adjust limitstart accordingly
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);		
	}
	
	
	/**
	 * return the model name
	 */
	function getName() {
		return $this->_modelName;
	}

	/**
	 * generic method to load the user related data
	 * @return array of users
	 */
	function getUsers() {
		TableUser::checkTable();
		if($this->_users == null) {
			$this->_loadUsers(); 
		}
		return $this->_users;
	}
		
	/**
	 * generic method to load the user related data
	 * @return array of users
	 */
	function getUser() {
		$cid = JRequest::getVar("cid",array(0));
		JArrayHelper::toInteger($cid);
		if (count($cid)>0){
			$id=$cid[0];
		}
		else $id=0;
		$user = new TableUser();
		if ($id>0){
			$user->load($id);
		}
		return $user;
	}

	/**
	 * Method to store user information
	 */
	function store($cid, $data) {
		$user = new TableUser();
		if ($cid>0){
			$user->load($cid);	
		}
		return $user->save($data);
	}
	
	/**
	 * Method to load the users in the system
	 * 
	 * @return void
	 */
	function _loadUsers(){
		$this->_users= TableUser::getUsers();	
	}
			
}
