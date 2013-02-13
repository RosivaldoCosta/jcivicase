<?php
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.model');
class JboloModelcp extends JModel
{
	/**
	 * Configuration data
	 * 
	 * @var object	JPagination object
	 **/	 	 	 
	var $_pagination;
	/**
	 * Constructor
	 */
	function __construct()
	{
		$mainframe	=& JFactory::getApplication();

		// Call the parents constructor
		parent::__construct();

		// Get the pagination request variables
		$limit		= $mainframe->getUserStateFromRequest( 'global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int' );
		$limitstart	= $mainframe->getUserStateFromRequest( 'com_community.limitstart', 'limitstart', 0, 'int' );

		// In case limit has been changed, adjust limitstart accordingly
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
	}

	/**
	 * Retrieves the JPagination object
	 *
	 * @return object	JPagination object	 	 
	 **/	 	
	function &getPagination()
	{
		if ($this->_pagination == null)
		{
			$this->getFields();
		}
		return $this->_pagination;
	}
	function _isPlugin()
	{
		$db	=& JFactory::getDBO();
		$query = "SELECT id FROM #__plugins WHERE element = 'plg_sys_jbolo_asset' AND published = 1";
		$db->setQuery($query);
		$userlist = $db->loadObject();
		if($userlist)
		return true;
		else
		return false;
	}

	function _isModule()
	{
		$db	=& JFactory::getDBO();
		$query = "SELECT id FROM #__modules WHERE module = 'mod_jbolo' AND published=1";
		$db->setQuery($query);
		$userlist = $db->loadObject();
		if($userlist)
		return true;
		else
		return false;
	}
	function _getJboloStats()
	{
		$db	=& JFactory::getDBO();
		$query = "SELECT js.chat_status AS status, count(js.chat_status) AS count 
		FROM #__jbolo_status AS js
		LEFT JOIN #__session AS s ON s.userid=js.joom_id 
		WHERE js.joom_id!=0 
		AND s.userid != 0
		AND s.client_id=0
		GROUP BY chat_status ";
		$db->setQuery($query);
		$user_stats = $db->loadObjectList();
		//print_r($userlist);
		return $user_stats;
		
	}
}
