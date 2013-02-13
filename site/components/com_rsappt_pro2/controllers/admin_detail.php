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


class admin_detailController extends JController
{

	/**
	 * Custom Constructor
	 */
	function __construct( $default = array())
	{
		parent::__construct( $default );

		// Register Extra tasks
		$this->registerTask( 'add', 'edit' );
		
		$this->registerTask( 'res_detail', 'go_res_detail' );
		$this->registerTask( 'save_res_detail', 'save_res_detail' );

		$this->registerTask( 'services_detail', 'go_services_detail' );
		$this->registerTask( 'save_services_detail', 'save_services_detail' );

		$this->registerTask( 'timeslots_detail', 'go_timeslots_detail' );
		$this->registerTask( 'save_timeslots_detail', 'save_timeslots_detail' );

		$this->registerTask( 'bookoffs_detail', 'go_bookoffs_detail' );
		$this->registerTask( 'save_bookoffs_detail', 'save_bookoffs_detail' );
		$this->registerTask( 'create_bookoff_series', 'create_bookoff_series' );

		$this->registerTask( 'paypal_transactions_detail', 'go_paypal_transactions_detail' );

		$this->registerTask( 'coupons_detail', 'go_coupons_detail' );
		$this->registerTask( 'save_coupon_detail', 'save_coupon_detail' );

		$this->registerTask( 'extras_detail', 'go_extras_detail' );
		$this->registerTask( 'save_extras_detail', 'save_extras_detail' );

		$this->registerTask( 'front_desk_add', 'front_desk_add' );

	}

	function edit()
	{
		$frompage = JRequest::getVar( 'frompage', '' );
		JRequest::setVar( 'view', 'requests_detail' );
		JRequest::setVar( 'layout', 'default'  );
		JRequest::setVar( 'hidemainmenu', 1);
		JRequest::setVar( 'listpage', $frompage);
		JRequest::setVar( 'Itemid', JRequest::getVar( 'Itemid'));

		parent::display();

	}

	function go_res_detail()
	{
		$frompage = JRequest::getVar( 'frompage', '' );
		$id = JRequest::getVar( 'cid', '' );
		JRequest::setVar( 'view', 'resources_detail' );
		JRequest::setVar( 'layout', 'default'  );
		JRequest::setVar( 'hidemainmenu', 1);
		JRequest::setVar( 'listpage', $frompage);
		JRequest::setVar( 'Itemid', JRequest::getVar( 'Itemid'));
		JRequest::setVar( 'id', $id[0]);

		parent::display();

	}


	function save()
	{
		$post	= JRequest::get('post');
		$cid	= JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$post['id'] = $cid[0];
		$frompage = JRequest::getVar('frompage');
		$frompage_item = JRequest::getVar('frompage_item');

		require_once(JPATH_COMPONENT.DS.'models'.DS.'requests_detail.php');
		$model = new admin_detailModelrequests_detail;
 		if($model == null){
			echo "model = null";
			exit;
		}
	
		if ($model->store($post)) {
			$msg = JText::_( 'COM_RSAPPT_SAVE_OK' );
		} else {
			$msg = JText::_( 'COM_RSAPPT_ERROR_SAVING' ).": ".$model->getError();
			logit($model->getError(), $model->getName()); 
		}

		// Check the table in so it can be edited.... we are done with it anyway
		$model->checkin();
		$this->setRedirect( 'index.php?option=com_rsappt_pro2&view='.$frompage.'&Itemid='.$frompage_item, $msg );
	}

	function save_res_detail()
	{
		$post	= JRequest::get('post');
//		$cid	= JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$post['id'] = JRequest::getVar('res_id');
		$frompage = JRequest::getVar('frompage');
		$frompage_item = JRequest::getVar('frompage_item');
		$fromtab = JRequest::getVar('fromtab');

		require_once(JPATH_COMPONENT.DS.'models'.DS.'resources_detail.php');
		$model = new admin_detailModelresources_detail;
 		if($model == null){
			echo "model = null";
			exit;
		}
	
		if ($model->store($post)) {
			$msg = JText::_( 'COM_RSAPPT_SAVE_OK' );
		} else {
			$msg = JText::_( 'COM_RSAPPT_ERROR_SAVING' ).": ".$model->getError();
			logit($model->getError(), $model->getName()); 
		}

		// Check the table in so it can be edited.... we are done with it anyway
		$model->checkin();
		$this->setRedirect( 'index.php?option=com_rsappt_pro2&view='.$frompage.'&Itemid='.$frompage_item.'&current_tab='.$fromtab, $msg );
	}


	function go_services_detail()
	{
		$frompage = JRequest::getVar( 'frompage', '' );
		$id = JRequest::getVar( 'cid', '' );
		JRequest::setVar( 'view', 'services_detail' );
		JRequest::setVar( 'layout', 'default'  );
		JRequest::setVar( 'hidemainmenu', 1);
		JRequest::setVar( 'listpage', $frompage);
		JRequest::setVar( 'Itemid', JRequest::getVar( 'Itemid'));
		JRequest::setVar( 'id', $id[0]);
	
		parent::display();

	}

	function save_services_detail()
	{
		$post	= JRequest::get('post');
//		$cid	= JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$post['id'] = JRequest::getVar('srv_id');
		$frompage = JRequest::getVar('frompage');
		$frompage_item = JRequest::getVar('frompage_item');
		$fromtab = JRequest::getVar('fromtab');

		require_once(JPATH_COMPONENT.DS.'models'.DS.'services_detail.php');
		$model = new admin_detailModelservices_detail;
 		if($model == null){
			echo "model = null";
			exit;
		}
	
		if ($model->store($post)) {
			$msg = JText::_( 'COM_RSAPPT_SAVE_OK' );
		} else {
			$msg = JText::_( 'COM_RSAPPT_ERROR_SAVING' ).": ".$model->getError();
			logit($model->getError(), $model->getName()); 
		}

		// Check the table in so it can be edited.... we are done with it anyway
		$model->checkin();
		$this->setRedirect( 'index.php?option=com_rsappt_pro2&view='.$frompage.'&Itemid='.$frompage_item.'&current_tab='.$fromtab, $msg );
	}


	function remove()
	{
		global $mainframe;

		$cid = JRequest::getVar( 'cid', array(0), 'post', 'array' );

		if (!is_array( $cid ) || count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'Select an item to delete' ) );
		}

		$model = $this->getModel('admin_detail');
		if(!$model->delete($cid)) {
			echo "<script> alert('".$model->getError()."'); window.history.go(-1); </script>\n";
		} else {
			$this->setRedirect( 'index.php?option=com_rsappt_pro2&controller=admin' );
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
		$frompage = JRequest::getVar('frompage');
		$frompage_item = JRequest::getVar('frompage_item');
		$fromtab = JRequest::getVar('fromtab');
		
		// Checkin the detail
		require_once(JPATH_COMPONENT.DS.'models'.DS.'requests_detail.php');
		$model = new admin_detailModelrequests_detail;

		$model->checkin();
		$this->setRedirect( 'index.php?option=com_rsappt_pro2&view='.$frompage.'&Itemid='.$frompage_item.'&current_tab='.$fromtab );

	}	

	function go_timeslots_detail()
	{
		$frompage = JRequest::getVar( 'frompage', '' );
		$id = JRequest::getVar( 'cid', '' );
		JRequest::setVar( 'view', 'timeslots_detail' );
		JRequest::setVar( 'layout', 'default'  );
		JRequest::setVar( 'hidemainmenu', 1);
		JRequest::setVar( 'listpage', $frompage);
		JRequest::setVar( 'Itemid', JRequest::getVar( 'Itemid'));
		JRequest::setVar( 'id', $id[0]);
	
		parent::display();

	}

	function save_timeslots_detail()
	{
		$post	= JRequest::get('post');
		$post['id'] = JRequest::getVar('ts_id');
		$frompage = JRequest::getVar('frompage');
		$frompage_item = JRequest::getVar('frompage_item');
		$fromtab = JRequest::getVar('fromtab');

		require_once(JPATH_COMPONENT.DS.'models'.DS.'timeslots_detail.php');
		$model = new admin_detailModeltimeslots_detail;
 		if($model == null){
			echo "model = null";
			exit;
		}
	
		if ($model->store($post)) {
			$msg = JText::_( 'COM_RSAPPT_SAVE_OK' );
		} else {
			$msg = JText::_( 'COM_RSAPPT_ERROR_SAVING' ).": ".$model->getError();
			logit($model->getError(), $model->getName()); 
		}

		// Check the table in so it can be edited.... we are done with it anyway
		$model->checkin();
		$this->setRedirect( 'index.php?option=com_rsappt_pro2&view='.$frompage.'&Itemid='.$frompage_item.'&current_tab='.$fromtab, $msg );
	}


	function go_bookoffs_detail()
	{
		$frompage = JRequest::getVar( 'frompage', '' );
		$id = JRequest::getVar( 'cid', '' );
		JRequest::setVar( 'view', 'bookoffs_detail' );
		JRequest::setVar( 'layout', 'default'  );
		JRequest::setVar( 'hidemainmenu', 1);
		JRequest::setVar( 'listpage', $frompage);
		JRequest::setVar( 'Itemid', JRequest::getVar( 'Itemid'));
		JRequest::setVar( 'id', $id[0]);
	
		parent::display();

	}

	function save_bookoffs_detail()
	{
		$post	= JRequest::get('post');
		$post['id'] = JRequest::getVar('bo_id');
		$frompage = JRequest::getVar('frompage');
		$frompage_item = JRequest::getVar('frompage_item');
		$fromtab = JRequest::getVar('fromtab');

		require_once(JPATH_COMPONENT.DS.'models'.DS.'bookoffs_detail.php');
		$model = new admin_detailModelbookoffs_detail;
 		if($model == null){
			echo "model = null";
			exit;
		}
	
		if ($model->store($post)) {
			$msg = JText::_( 'COM_RSAPPT_SAVE_OK' );
		} else {
			$msg = JText::_( 'COM_RSAPPT_ERROR_SAVING' ).": ".$model->getError();
			logit($model->getError(), $model->getName()); 
		}

		// Check the table in so it can be edited.... we are done with it anyway
		$model->checkin();
		$this->setRedirect( 'index.php?option=com_rsappt_pro2&view='.$frompage.'&Itemid='.$frompage_item.'&current_tab='.$fromtab, $msg );
	}

	function create_bookoff_series(){

		$post	= JRequest::get('post');
		$post['id'] = JRequest::getVar('bo_id');
		$frompage = JRequest::getVar('frompage');
		$frompage_item = JRequest::getVar('frompage_item');
		$fromtab = JRequest::getVar('fromtab');

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
		$msg = JText::_( 'COM_RSAPPT_SAVE_OK' );
		$this->setRedirect( 'index.php?option=com_rsappt_pro2&view='.$frompage.'&Itemid='.$frompage_item.'&current_tab='.$fromtab, $msg );
		
	}



	function go_paypal_transactions_detail()
	{
		$frompage = JRequest::getVar( 'frompage', '' );
		$id = JRequest::getVar( 'cid', '' );
		JRequest::setVar( 'view', 'paypal_transactions_detail' );
		JRequest::setVar( 'layout', 'default'  );
		JRequest::setVar( 'hidemainmenu', 1);
		JRequest::setVar( 'listpage', $frompage);
		JRequest::setVar( 'Itemid', JRequest::getVar( 'Itemid'));
		JRequest::setVar( 'id', $id[0]);
	
		parent::display();

	}

	function go_coupons_detail()
	{
		$frompage = JRequest::getVar( 'frompage', '' );
		$id = JRequest::getVar( 'cid', '' );
		JRequest::setVar( 'view', 'coupons_detail' );
		JRequest::setVar( 'layout', 'default'  );
		JRequest::setVar( 'hidemainmenu', 1);
		JRequest::setVar( 'listpage', $frompage);
		JRequest::setVar( 'Itemid', JRequest::getVar( 'Itemid'));
		JRequest::setVar( 'id', $id[0]);
	
		parent::display();

	}

	function save_coupon_detail()
	{
		$post	= JRequest::get('post');
		$post['id'] = JRequest::getVar('coup_id');
		$frompage = JRequest::getVar('frompage');
		$frompage_item = JRequest::getVar('frompage_item');
		$fromtab = JRequest::getVar('fromtab');

		require_once(JPATH_COMPONENT.DS.'models'.DS.'coupons_detail.php');
		$model = new admin_detailModelcoupons_detail;
 		if($model == null){
			echo "model = null";
			exit;
		}
	
		if ($model->store($post)) {
			$msg = JText::_( 'COM_RSAPPT_SAVE_OK' );
		} else {
			$msg = JText::_( 'COM_RSAPPT_ERROR_SAVING' ).": ".$model->getError();
			logit($model->getError(), $model->getName()); 
		}

		// Check the table in so it can be edited.... we are done with it anyway
		$model->checkin();
		$this->setRedirect( 'index.php?option=com_rsappt_pro2&view='.$frompage.'&Itemid='.$frompage_item.'&current_tab='.$fromtab, $msg );
	}

	function go_extras_detail()
	{
		$frompage = JRequest::getVar( 'frompage', '' );
		$id = JRequest::getVar( 'cid', '' );
		JRequest::setVar( 'view', 'extras_detail' );
		JRequest::setVar( 'layout', 'default'  );
		JRequest::setVar( 'hidemainmenu', 1);
		JRequest::setVar( 'listpage', $frompage);
		JRequest::setVar( 'Itemid', JRequest::getVar( 'Itemid'));
		JRequest::setVar( 'id', $id[0]);
	
		parent::display();

	}

	function save_extras_detail()
	{
		$post	= JRequest::get('post');
		$post['id'] = JRequest::getVar('ext_id');
		$frompage = JRequest::getVar('frompage');
		$frompage_item = JRequest::getVar('frompage_item');
		$fromtab = JRequest::getVar('fromtab');

		require_once(JPATH_COMPONENT.DS.'models'.DS.'extras_detail.php');
		$model = new admin_detailModelextras_detail;
 		if($model == null){
			echo "model = null";
			exit;
		}
	
		if ($model->store($post)) {
			$msg = JText::_( 'COM_RSAPPT_SAVE_OK' );
		} else {
			$msg = JText::_( 'COM_RSAPPT_ERROR_SAVING' ).": ".$model->getError();
			logit($model->getError(), $model->getName()); 
		}

		// Check the table in so it can be edited.... we are done with it anyway
		$model->checkin();
		$this->setRedirect( 'index.php?option=com_rsappt_pro2&view='.$frompage.'&Itemid='.$frompage_item.'&current_tab='.$fromtab, $msg );
	}

	function front_desk_add()
	{
		$frompage = JRequest::getVar( 'frompage', '' );
		$id = JRequest::getVar( 'cid', '' );
		JRequest::setVar( 'view', 'front_desk_add' );
		JRequest::setVar( 'layout', 'default'  );
		JRequest::setVar( 'hidemainmenu', 1);
		JRequest::setVar( 'listpage', $frompage);
		JRequest::setVar( 'Itemid', JRequest::getVar( 'Itemid'));
		JRequest::setVar( 'id', $id[0]);
	
		parent::display();

	}

}

