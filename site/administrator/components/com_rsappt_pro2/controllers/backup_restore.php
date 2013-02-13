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


class backup_restoreController extends JController
{

	/**
	 * Custom Constructor
	 */
	function __construct( $default = array())
	{
		parent::__construct( $default );
		
		require_once JPATH_COMPONENT . DS . 'helpers' . DS . 'rsappt_pro2.php';
		rsappt_pro2Helper::addSubmenu('backup_restore');
		
		// Register Extra tasks
		$this->registerTask( 'add', 'edit' );
		$this->registerTask( 'backup', 'backup' );
		$this->registerTask( 'restore', 'restore' );
		$this->registerTask( 'import', 'import' );
		
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
		JRequest::setVar( 'view', 'backup_restore' );
		JRequest::setVar( 'layout', 'default'  );
		JRequest::setVar( 'hidemainmenu', 0);


		parent::display();

	}
      
	function backup()
	{
		JRequest::setVar( 'view', 'backup_restore_results' );
		JRequest::setVar( 'hidemainmenu', 1);
		JRequest::setVar( 'action', 'backup');

		parent::display();
	}


	function restore()
	{
		JRequest::setVar( 'view', 'backup_restore_results' );
		JRequest::setVar( 'hidemainmenu', 1);
		JRequest::setVar( 'action', 'restore');

		parent::display();
	}

	function import()
	{
		JRequest::setVar( 'view', 'backup_restore_results' );
		JRequest::setVar( 'hidemainmenu', 1);
		JRequest::setVar( 'action', 'import');

		parent::display();
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
		$this->setRedirect( 'index.php?option=com_rsappt_pro2&controller=backup_restore',$msg );
	}	


}

