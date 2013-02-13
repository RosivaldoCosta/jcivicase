<?php
/*
 ****************************************************************
 Copyright (C) 2008-2010 Soft Ventures, Inc. All rights reserved.
 ****************************************************************
 * @package	Appointment Booking Pro - ABPro
 * @copyright	Copyright (C) 2008-2010 Soft Ventures, Inc. All rights reserved.
 * @license	GNU/GPL, see http://www.gnu.org/licenses/gpl-2.0.html
 *
 * ABPro is distributed WITHOUT ANY WARRANTY, or implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * This header must not be removed. Additional contributions/changes
 * may be added to this header as long as no information is deleted.
 *
 ************************************************************
 The latest version of ABPro is available to subscribers at:
 http://www.appointmentbookingpro.com/
 ************************************************************
*/


// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

//DEVNOTE: import MODEL object class
jimport('joomla.application.component.model');


class user_credit_detailModeluser_credit_detail extends JModel
{
		var $_id_user_credit = null;
		var $_data = null;
		var $_table_prefix = null;
		var $_credit_usage_info = null;

	function __construct()
	{
		parent::__construct();
		
		//initialize class property
	  	$this->_table_prefix = '#__sv_apptpro2_';			

		$array = JRequest::getVar('cid',  0, '', 'array');
		
		$this->setId((int)$array[0]);

	}

	/**
	 * Method to set the user_credit identifier
	 *
	 * @access	public
	 * @param	int user_credit identifier
	 */
	function setId($id_user_credit)
	{
		// Set user_credit id and wipe data
		$this->_id_user_credit		= $id_user_credit;
		$this->_data	= null;
	}

	/**
	 * Method to get a user_credit
	 *
	 * @since 1.5
	 */
	function &getData()
	{
		// Load the user_credit data
		if ($this->_loadData())
		{
		//load the data nothing else	  
		}
		else  $this->_initData();
		//print_r($this->_data);	
		
   	return $this->_data;
	}
	
	
	function &getCredit_usage_info()
	{
		// Load the user_credit data
		if ($this->_loadCredit_usage_info())
		{
		//load the data nothing else	  
		}
		//print_r($this->_data);	
		
   	return $this->_credit_usage_info;
	}
	/**
	 * Method to checkout/lock the user_credit
	 *
	 * @access	public
	 * @param	int	$uid	User ID of the user checking the article out
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function checkout($uid = null)
	{
		if ($this->_id_user_credit)
		{
			// Make sure we have a user id to checkout the article with
			if (is_null($uid)) {
				$user	=& JFactory::getUser();
				$uid	= $user->get('id');
			}
			// Lets get to it and checkout the thing...
			$user_credit = & $this->getTable();
			
			
			if(!$user_credit->checkout($uid, $this->_id_user_credit)) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}

			return true;
		}
		return false;
	}
	/**
	 * Method to checkin/unlock the user_credit
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function checkin()
	{
		if ($this->_id_user_credit)
		{
			$user_credit = & $this->getTable();
			if(! $user_credit->checkin($this->_id_user_credit)) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}
		return false;
	}	
	/**
	 * Tests if user_credit is checked out
	 *
	 * @access	public
	 * @param	int	A user id
	 * @return	boolean	True if checked out
	 * @since	1.5
	 */
	function isCheckedOut( $uid=0 )
	{
		if ($this->_loadData())
		{
			if ($uid) {
				return ($this->_data->checked_out && $this->_data->checked_out != $uid);
			} else {
				return $this->_data->checked_out;
			}
		}
	}	
		
	/**
	 * Method to load content user_credit data
	 *
	 * @access	private
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function _loadData()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_data))
		{
			$query = 'SELECT * FROM '.$this->_table_prefix.'user_credit WHERE id_user_credit = '. $this->_id_user_credit;
			$this->_db->setQuery($query);
			$this->_data = $this->_db->loadObject();
			//print_r($this->_data);
			return (boolean) $this->_data;
		}
		return true;
	}

	/**
	 * Method to initialise the user_credit data
	 *
	 * @access	private
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function _initData()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_data))
		{
			$detail = new stdClass();
			$detail->id_user_credit	= 0;
			$detail->user_id = null;
			$detail->balance = 0.00;
			$detail->checked_out = 0;
			$detail->checked_out_time = 0;
			$detail->ordering = 1;
			$detail->published = 0;
			$this->_data	= $detail;
			return (boolean) $this->_data;
		}
		return true;
	}
  	

	/**
	 * Method to store the user_credit text
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function store($data)
	{
		//DEVNOTE: Load table class from com_rsappt_pro2/tables/user_credit_detail.php	
		$row =& $this->getTable();

		// Bind the form fields to the user_credit table
		if (!$row->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// if new item, order last in appropriate group
		if (!$row->id_user_credit) {
			$where = 'id_user_credit = ' . $row->id_user_credit ;
			$row->ordering = $row->getNextOrder ( $where );
		}

		//DEVNOTE: Make sure the user_credit table is valid
		//JTable return always true but there is space to put
		//our custom check method
/*		if (!$row->check()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}*/

		// Store the user_credit table to the database
		if (!$row->store()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// add to user_credit_activity
		$user =& JFactory::getUser();	
		if(JRequest::getVar('id_user_credit') == ""){
			// adding new entry
			$sql = 'INSERT INTO #__sv_apptpro2_user_credit_activity (user_id, increase, comment, operator_id, balance) '.
			"VALUES (".JRequest::getVar('user_id').",".
			"'".JRequest::getVar('balance')."',".
			"'".JText::_('RS1_ADMIN_CREDIT_ACTIVITY_NEW_ENTRY')."',".
			$user->id.",".
			"'".JRequest::getVar('balance')."')";
			$this->_db->setQuery($sql);
			$this->_db->query();
			if ($this->_db->getErrorNum()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}			
		} else {
			// admin edit
			$sql = 'INSERT INTO #__sv_apptpro2_user_credit_activity (user_id, comment, operator_id, balance) '.
			"VALUES (".JRequest::getVar('user_id').",".
			"'".JText::_('RS1_ADMIN_CREDIT_ACTIVITY_EDIT')." ".JRequest::getVar('balance')."',".
			$user->id.",".
			"(SELECT balance from #__sv_apptpro2_user_credit WHERE user_id = ".JRequest::getVar('user_id')."))";
			$this->_db->setQuery($sql);
			$this->_db->query();
			if ($this->_db->getErrorNum()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}			
		}


		return true;
	}
	
		/**
	 * Method to (un)publish a user_credit
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function publish($cid = array(), $publish = 1)
	{
		$user 	=& JFactory::getUser();

		if (count( $cid ))
		{
			$cids = implode( ',', $cid );

			$query = 'UPDATE '.$this->_table_prefix.'user_credit'
				. ' SET published = ' . intval( $publish )
				. ' WHERE id_user_credit IN ( '.$cids.' )'
				. ' AND ( checked_out = 0 OR ( checked_out = ' .$user->get('id'). ' ) )'
			;

			$this->_db->setQuery( $query );
			if (!$this->_db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}

		return true;
	}
	
	/**
	 * Method to move a user_credit_detail
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function saveorder($cid = array(), $order)
	{
		//DEVNOTE: Load table class from com_sv_ser/tables/user_credit_detail.php		
		$row =& $this->getTable();
		$groupings = array();

		// update ordering values
		for( $i=0; $i < count($cid); $i++ )
		{
			$row->load( (int) $cid[$i] );

			if ($row->ordering != $order[$i])
			{
				$row->ordering = $order[$i];
				if (!$row->store()) {
					$this->setError($this->_db->getErrorMsg());
					return false;
				}
			}
		}
		return true;
	}
		
		/**
	 * Method to move a user_credit 
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function move($direction)
	{
	//DEVNOTE: Load table class from com_sv_ser/tables/user_credit_detail.php	
		$row =& $this->getTable();
	//DEVNOTE: we need to pass here id of user_credit detail 		
		if (!$row->load($this->_id_user_credit)) {
			$this->setError($this->_db->getErrorMsg());
		
			return false;
		}
  
	//DEVNOTE: call move method of JTABLE. 
  //first parameter: direction [up/down]
  //second parameter: condition
		if (!$row->move( $direction, ' published >= 0 ' )) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		return true;
	}		

	function delete($cid = array())
	{
		$result = false;


		if (count( $cid ))
		{
			$cids = implode( ',', $cid );
			$query = 'DELETE FROM '.$this->_table_prefix.'user_credit WHERE id_user_credit IN ( '.$cids.' )';
			$this->_db->setQuery( $query );
			if(!$this->_db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
			$this->_db->setQuery("DELETE FROM #__sv_apptpro_user_credit_activity WHERE user_id IN (SELECT user_id FROM #__sv_apptpro_user_credit WHERE id IN ($ids))");
			if (!$this->_db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
			
		}

		return true;
	}
	
	function _loadCredit_usage_info()
	{
		//$sql = "SELECT user_id FROM #__sv_apptpro2_user_credit WHERE id_user_credit = ".$this->_id_user_credit;
		$sql = "SELECT #__sv_apptpro2_user_credit_activity.*, #__users.name as operator, #__sv_apptpro2_requests.startdate, ".
			"DATE_FORMAT(#__sv_apptpro2_requests.startdate, '%b %e') as display_startdate, ".
			"DATE_FORMAT(#__sv_apptpro2_requests.starttime, '%H:%i') as display_starttime, ".
			"#__sv_apptpro2_resources.description as resource ".
			"FROM #__sv_apptpro2_user_credit_activity ".
			"  INNER JOIN #__users ON #__sv_apptpro2_user_credit_activity.operator_id = #__users.id ".
			"  LEFT OUTER JOIN #__sv_apptpro2_requests ON #__sv_apptpro2_user_credit_activity.request_id = #__sv_apptpro2_requests.id_requests ".
			"  LEFT OUTER JOIN #__sv_apptpro2_resources ON #__sv_apptpro2_requests.resource = #__sv_apptpro2_resources.id_resources ".
			"WHERE #__sv_apptpro2_user_credit_activity.user_id = ".
			" (SELECT user_id FROM #__sv_apptpro2_user_credit WHERE id_user_credit = ".$this->_id_user_credit.") ORDER BY stamp desc";
		//echo $sql;		
		$this->_db->setQuery($sql);
		$this->_credit_usage_info = $this->_db->loadObjectList();
		//print_r("here".$this->_credit_usage_info);
		return $this->_credit_usage_info;
	}

}

?>