<?php
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.model');

class JboloModelHistory extends JModel
{
	//@TODO remove in future version
	/*function getHistory()
	{
		require(JPATH_COMPONENT.DS."config".DS."config.php");
		$db	=& JFactory::getDBO();
		$user =& JFactory::getUser();
		$tuser = JRequest::getVar('tuser');
		$chathistory = array();
		if (!$user->id) { return; }
		$items = '';
		$sql = "SELECT * FROM #__jbolo as c WHERE ((c.to = {$user->id} AND c.from = {$tuser}) OR (c.to = {$tuser} AND c.from = {$user->id})) AND c.message NOT LIKE '%has sent you a file. Download link%' ORDER by c.sent DESC LIMIT";
		$db->setQuery($sql);
		$chats = $db->loadObjectList();
		$me = JText::_('me');	
		$i = 0;
		$j = 0; 
		foreach ($chats as $chat) {
			$udetails = JFactory::getUser($chat->from);
			if($chat_config['chatusertitle'])
			$chatfrom= $udetails->username;//$details->name;
			else
			$chatfrom= $udetails->name;//$details->username;
			if($chat->from == $user->id)
			{
				$chatfrom = $me;
			}
			$chat->message = $this->sanitize($chat->message);

			$chathistory[$i][$j] = $chatfrom;
			$j++;
			$chathistory[$i][$j] = $chat->message;
			$i++;
			$j=0;
		}	
		return $chathistory;
	}*/
	function change_repr(&$rows)
	{
		$user =& JFactory::getUser();
		$me = JText::_('me');	

		foreach($rows as $key=>$row)
			{
					if($row->from == $user->id)
					{
						$rows[$key]->label = $me;
					}
			}
	}
	function getUserList()
	{
	require(JPATH_COMPONENT.DS."config".DS."config.php");
		$db	=& JFactory::getDBO();
		$user =& JFactory::getUser();
		$chathistory = array();
		$items = '';
		if($chat_config['chatusertitle'])
			$label= 'username';//$details->name;
			else
			$label= 'name';

	  $sql = "SELECT uid,label FROM (SELECT c.from AS uid,u.".$label." AS label FROM #__jbolo AS c LEFT JOIN #__users AS u ON u.id=c.from WHERE (c.to = {$user->id} OR c.from = {$user->id}) AND LENGTH(`to`)<6 GROUP BY c.from UNION
SELECT c.to AS uid,u.".$label." AS label FROM #__jbolo AS c LEFT JOIN #__users AS u ON u.id=c.from WHERE (c.to = {$user->id} OR c.from = {$user->id}) AND LENGTH(`to`)<6 GROUP BY c.to) AS users WHERE uid <> {$user->id} GROUP BY uid "; 
		$db->setQuery($sql);
		$userlist = $db->loadObjectList();
	
		/*foreach($listsdb as $lstdb)
		{
			foreach($lstdb as $lb)
			{
				$lists[]=$lb;	
			}
		}

		$lists = array_unique($lists);
		$i=0;
		$k=0;

		$userlist = array();
		foreach($lists as $lst)
		{
		
			if($lst!=$user->id)
			{
				$k=0;
				$user1 =& JFactory::getUser($lst);
				$userlist[$i][$k] = $lst;
				$k++;
				$userlist[$i][$k] = $user1->name;
				$i++;
			}
		}
		*/
		return $userlist;
	}
		
	function sanitize($text) {

		$text = str_replace("\n\r","\n",$text);
		$text = str_replace("\r\n","\n",$text);
		$text = str_replace("\n","<br>",$text);
//		$text = addslashes( $text );
		return $text;
	}

		
/**
   * Items total
   * @var integer
   */
  var $_total = null;
 
  /**
   * Pagination object
   * @var object
   */
  var $_pagination = null;

	
	/**
	 * Constructor that retrieves the ID from the request
	 *
	 * @access	public
	 * @return	void
	 */
	function _loadData()
	{		
		$user =& JFactory::getUser();
		if (!$user->id) { return; }
		// Lets load the content if it doesn't already exist
		if (empty($this->_data))
		{

			// Get the pagination request variables
			$limitstart	= JRequest::getVar('limitstart', 0, '', 'int');
			$limit 		= JRequest::getVar('limit', 20, '', 'int');

			$query = $this->_buildQuery();
						
			$Arows = $this->_getList($query, $limitstart, $limit);
			$this->change_repr($Arows);
			$this->_data = $Arows;
		} 
		return true;
	}

	// Get the total number of tickets
	function getData()
	{
				
		$this->_loadData();
		
		return $this->_data;
	}


  function getTotal()
  {
        // Load the content if it doesn't already exist
        if (empty($this->_total)) {
            $query = $this->_buildQuery();
            $this->_total = $this->_getListCount($query);    
        }
        return $this->_total;
  }


	function _buildQuery()
	{
		require(JPATH_COMPONENT.DS."config".DS."config.php");
		$user =& JFactory::getUser();

		if($chat_config['chatusertitle'])
			$label= 'username';//$details->name;
			else
			$label= 'name';
		$tuser = JRequest::getVar('tuser');
		 $query = "SELECT c.sent,c.from, c.to,c.message,u.".$label." as label FROM #__jbolo as c LEFT JOIN #__users AS u ON u.id=c.from WHERE ((c.to = {$user->id} AND c.from = {$tuser}) OR (c.to = {$tuser} AND c.from = {$user->id})) AND c.message NOT LIKE '%has sent you a file. Download link%' ORDER by c.sent DESC";
		return $query;

	}
	
	

}
?>
