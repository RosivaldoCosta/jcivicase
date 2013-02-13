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

//DEVNOTE: import CONTROLLER object class
jimport( 'joomla.application.component.controller' ); 


class bookoffs_detailController extends JController
{

	/**
	 * Custom Constructor
	 */
	function __construct( $default = array())
	{
		parent::__construct( $default );

		// Register Extra tasks
		$this->registerTask( 'add', 'edit' );
		$this->registerTask( 'create_bookoff_series', 'create_bookoff_series' );
		
	}

	/** function edit
	*
	* Create a new item or edit existing item 
	* 
	* 1) set a custom VIEW layout to 'form'  
	* so expecting path is : [componentpath]/views/[$controller->_name]/'form.php';			
    * 2) show the view
    * 3) get(create) MODEL and checkout item
	*/
	function edit()
	{
		JRequest::setVar( 'view', 'bookoffs_detail' );
		JRequest::setVar( 'layout', 'form'  );
		JRequest::setVar( 'hidemainmenu', 1);


		parent::display();

		// Checkin the bookoffs
		$model = $this->getModel('bookoffs_detail');
		$model->checkout();
	}
      
	/** function save
	*
	* Save the selected item specified by id
	* and set Redirection to the list of items	
	* 		
	* @param int id - keyvalue of the item
	* @return set Redirection
	*/
	function save()
	{
	
		$post	= JRequest::get('post');
		$cid	= JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$post['id'] = $cid[0];

		$model = $this->getModel('bookoffs_detail');
	
		if ($model->store($post)) {
			$msg = JText::_( 'COM_RSAPPT_SAVE_OK' );
		} else {
			$msg = JText::_( 'COM_RSAPPT_ERROR_SAVING' ).": ".$model->getError();
			logit($model->getError(), $model->getName()); 
		}

		// Check the table in so it can be edited.... we are done with it anyway
		$model->checkin();
		$this->setRedirect( 'index.php?option=com_rsappt_pro2&controller=bookoffs',$msg );
	}

	/** function remove
	*
	* Delete all items specified by array cid
	* and set Redirection to the list of items	
	* 		
	* @param array cid - array of id
	* @return set Redirection
	*/
	function remove()
	{
		global $mainframe;

		$cid = JRequest::getVar( 'cid', array(0), 'post', 'array' );

		if (!is_array( $cid ) || count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'Select an item to delete' ) );
		}

		$model = $this->getModel('bookoffs_detail');
		if(!$model->delete($cid)) {
			echo "<script> alert('".$model->getError()."'); window.history.go(-1); </script>\n";
		} else {
			$this->setRedirect( 'index.php?option=com_rsappt_pro2&controller=bookoffs' );
		}
	}
	
	
	/** function publish
	*
	* Publish all items specified by array cid
	* and set Redirection to the list of items	
	* 		
	* @param array cid - array of id
	* @return set Redirection
	*/
	function publish()
	{
		global $mainframe;

		$cid 	= JRequest::getVar( 'cid', array(0), 'post', 'array' );

		if (!is_array( $cid ) || count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'Select an item to publish' ) );
		}

		$model = $this->getModel('bookoffs_detail');
		if(!$model->publish($cid, 1)) {
			echo "<script> alert('".$model->getError()."'); window.history.go(-1); </script>\n";
		} else {
			$this->setRedirect( 'index.php?option=com_rsappt_pro2&controller=bookoffs');
		}
	}

	/** function unpublish
	*
	* Unpublish all items specified by array cid
	* and set Redirection to the list of items
	* 			
	* @param array cid - array of id
	* @return set Redirection
	*/
	function unpublish()
	{
		global $mainframe;

		$cid 	= JRequest::getVar( 'cid', array(0), 'post', 'array' );

		if (!is_array( $cid ) || count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'Select an item to unpublish' ) );
		}

		$model = $this->getModel('bookoffs_detail');
		if(!$model->publish($cid, 0)) {
			echo "<script> alert('".$model->getError()."'); window.history.go(-1); </script>\n";
		} else {
			$this->setRedirect( 'index.php?option=com_rsappt_pro2&controller=bookoffs');
		}
	}	
	
	/** function cancel
	*
	* Check in the selected detail 
	* and set Redirection to the list of items	
	* 		
	* @return set Redirection
	*/
	function cancel()
	{
		// Checkin the detail
		$model = $this->getModel('bookoffs_detail');
		$model->checkin();
		$this->setRedirect( 'index.php?option=com_rsappt_pro2&controller=bookoffs',$msg );
	}	

	/** function orderup
	*
	* change order up
	* and set Redirection to the list of items
	* 			
	* @param array cid - array of id
	* @return set Redirection
	*/
	function orderup()
	{
		$model = $this->getModel('bookoffs_detail');
		$model->move(-1);

		$this->setRedirect( 'index.php?option=com_rsappt_pro2&controller=bookoffs');
	}


	/** function orderdown
	*
	* change order down
	* and set Redirection to the list of items
	* 			
	* @param array cid - array of id
	* @return set Redirection
	*/
	function orderdown()
	{
		$model = $this->getModel('bookoffs_detail');
		$model->move(1);

		$this->setRedirect( 'index.php?option=com_rsappt_pro2&controller=bookoffs');
	}

	/** function saveorder
	*
	* saveorder of the bookoffs items
	* 			
	* @param array cid		- array of id
	* @param array order	- array of order 
	* @return set Redirection
	*/
	function saveorder()
	{
		$cid 	= JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$order 	= JRequest::getVar( 'order', array(0), 'post', 'array' );

		$model = $this->getModel('bookoffs_detail');
		$model->saveorder($cid, $order);

		$msg = 'New ordering saved';
		$this->setRedirect( 'index.php?option=com_rsappt_pro2&controller=bookoffs', $msg );
	}
	

	function create_bookoff_series(){
		$off_date = JRequest::getVar('off_date');
		$off_date2 = JRequest::getVar('off_date2');
		$resource = JRequest::getVar('resource_id');
		$full_day = JRequest::getVar('full_day');
		$bookoff_starttime = JRequest::getVar('bookoff_starttime');
		$bookoff_endtime = JRequest::getVar('bookoff_endtime');
		$resource_desc = JRequest::getVar('description');
		$published = JRequest::getVar('published');
		
		$d1 = strtotime($off_date);
		$d2 = strtotime($off_date2);
		$database = &JFactory::getDBO();
		while($d1 <= $d2){
			$sql = "INSERT INTO #__sv_apptpro2_bookoffs (resource_id,description,off_date,full_day,bookoff_starttime,bookoff_endtime,published)".
			" VALUES(".$resource.",'".$database->getEscaped($resource_desc)."','".date("Y-m-d", $d1)."','".$full_day."','".$bookoff_starttime."','".$bookoff_endtime."',".$published.")";
			echo $sql."<br>";
			$database->setQuery( $sql );
			if (!$database->query()) {
				echo $database -> getErrorMsg();
				return false;
			}
			$d1 = $d1+86400; 
		}
		
		$this->setRedirect( 'index.php?option=com_rsappt_pro2&controller=bookoffs' );
		
	}

}

