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


class config_detailController extends JController
{

	/**
	 * Custom Constructor
	 */
	function __construct( $default = array())
	{
		parent::__construct( $default );
		
		require_once JPATH_COMPONENT . DS . 'helpers' . DS . 'rsappt_pro2.php';
		rsappt_pro2Helper::addSubmenu('config');
		
		// Register Extra tasks
		$this->registerTask( 'add', 'edit' );
		
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
		JRequest::setVar( 'view', 'config_detail' );
		JRequest::setVar( 'layout', 'form'  );
		JRequest::setVar( 'hidemainmenu', 0);


		parent::display();

		// Checkin the config
		$model = $this->getModel('config_detail');
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
	
//		$post	= JRequest::get('post');
		$post	= JRequest::get('post', JREQUEST_ALLOWHTML );
		
		$cid	= JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$post['id'] = $cid[0];

		$model = $this->getModel('config_detail');
	
		if ($model->store($post)) {
			$msg = JText::_( 'COM_RSAPPT_SAVE_OK' );
		} else {
			$msg = JText::_( 'COM_RSAPPT_ERROR_SAVING' ).": ".$model->getError();
			logit($model->getError(), $model->getName()); 
		}

		// Check the table in so it can be edited.... we are done with it anyway
		$model->checkin();
		$this->setRedirect( 'index.php?option=com_rsappt_pro2&controller=config_detail&task=edit&cid[]=1',$msg );
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

		$model = $this->getModel('config_detail');
		if(!$model->delete($cid)) {
			echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
		}

		$this->setRedirect( 'index.php?option=com_rsappt_pro2&controller=config_detail' );
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

		$model = $this->getModel('config_detail');
		if(!$model->publish($cid, 1)) {
			echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
		}

		$this->setRedirect( 'index.php?option=com_rsappt_pro2&controller=config_detail',$msg );
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

		$model = $this->getModel('config_detail');
		if(!$model->publish($cid, 0)) {
			echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
		}

		$this->setRedirect( 'index.php?option=com_rsappt_pro2&controller=config_detail',$msg );
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
		$model = $this->getModel('config_detail');
		$model->checkin();
		$this->setRedirect( 'index.php?option=com_rsappt_pro2&controller=config_detail',$msg );
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
		$model = $this->getModel('config_detail');
		$model->move(-1);

		$this->setRedirect( 'index.php?option=com_rsappt_pro2&controller=config_detail');
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
		$model = $this->getModel('config_detail');
		$model->move(1);

		$this->setRedirect( 'index.php?option=com_rsappt_pro2&controller=config_detail');
	}

	/** function saveorder
	*
	* saveorder of the config items
	* 			
	* @param array cid		- array of id
	* @param array order	- array of order 
	* @return set Redirection
	*/
	function saveorder()
	{
		$cid 	= JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$order 	= JRequest::getVar( 'order', array(0), 'post', 'array' );

		$model = $this->getModel('config_detail');
		$model->saveorder($cid, $order);

		$msg = 'New ordering saved';
		$this->setRedirect( 'index.php?option=com_rsappt_pro2&controller=config_detail', $msg );
	}
	

}

