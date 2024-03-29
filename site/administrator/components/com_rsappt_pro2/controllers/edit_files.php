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


class edit_filesController extends JController
{

	/**
	 * Custom Constructor
	 */
	function __construct( $default = array())
	{
		parent::__construct( $default );
		
		require_once JPATH_COMPONENT . DS . 'helpers' . DS . 'rsappt_pro2.php';
		rsappt_pro2Helper::addSubmenu('edit_files');
		
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
		JRequest::setVar( 'view', 'edit_files' );
		JRequest::setVar( 'layout', 'default'  );
		JRequest::setVar( 'hidemainmenu', 0);


		parent::display();

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
		$msg = "";
		$fn = JPATH_SITE."/components/com_rsappt_pro2/sv_apptpro.css";
		$cssfile = stripslashes($_POST['cssfile']);
		$fp = fopen($fn,"w") or die ("Error opening file in write mode!");
		fputs($fp,$cssfile);
		fclose($fp) or die ("Error closing file!");
	
		$lang_file_count = JRequest::getVar( lang_file_count, 0 );
		for($x=0; $x<intval($lang_file_count); $x++){
			
			$filename = "save_langfile".$x;
			$filetext = stripslashes($_POST['langfile'.$x]);
			$fn = JRequest::getVar( $filename );
			$fp = fopen($fn,"w");
			if($fp == false){ 
				$msg = "Error opening file '".$filename."' in write mode! Not saved.<br/>";
			} else {
				fputs($fp,$filetext);
				fclose($fp) or die ("Error closing file!");
			}
		}
		$this->setRedirect( 'index.php?option=com_rsappt_pro2&controller=edit_files&task=edit&cid[]=1',$msg );
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
		$model = $this->getModel('edit_files');
		$model->checkin();
		$this->setRedirect( 'index.php?option=com_rsappt_pro2&controller=edit_files',$msg );
	}	


}

