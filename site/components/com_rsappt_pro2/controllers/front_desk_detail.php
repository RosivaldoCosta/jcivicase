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


class front_desk_detailController extends JController
{

	/**
	 * Custom Constructor
	 */
	function __construct( $default = array())
	{
		parent::__construct( $default );

		// Register Extra tasks	
		$this->registerTask( 'request_detail', 'go_request_detail' );
		$this->registerTask( 'save_request_detail', 'save_request_detail' );

	}

	function go_request_detail()
	{
		$frompage = JRequest::getVar( 'frompage', '' );
		JRequest::setVar( 'view', 'requests_detail' );
		JRequest::setVar( 'layout', 'default'  );
		JRequest::setVar( 'hidemainmenu', 1);
		JRequest::setVar( 'listpage', $frompage);
		JRequest::setVar( 'Itemid', JRequest::getVar( 'Itemid'));

		parent::display();

	}



	function save_request_detail()
	{
		$post	= JRequest::get('post');
		$cid	= JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$post['id'] = $cid[0];
		$frompage = JRequest::getVar('frompage');
		$frompage_item = JRequest::getVar('frompage_item');

		require_once(JPATH_COMPONENT.DS.'models'.DS.'requests_detail.php');
		$model = new front_desk_detailModelrequests_detail;
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

	
	
	function cancel()
	{
		$frompage = JRequest::getVar('frompage');
		$frompage_item = JRequest::getVar('frompage_item');
		
		// Checkin the detail
		require_once(JPATH_COMPONENT.DS.'models'.DS.'requests_detail.php');
		$model = new front_desk_detailModelrequests_detail;

		$model->checkin();
		$this->setRedirect( 'index.php?option=com_rsappt_pro2&view='.$frompage.'&Itemid='.$frompage_item );

	}	



}

