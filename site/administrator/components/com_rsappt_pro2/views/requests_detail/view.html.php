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

//DEVNOTE: import VIEW object class
jimport( 'joomla.application.component.view' );


/**
 [controller]View[controller]
 */
class requests_detailVIEWrequests_detail extends JView
{
	/**
	 * Display the view
	 */
	function display($tpl = null)
	{
	
	  	$mainframe = JFactory::getApplication();

		$document = & JFactory::getDocument();
		$document->setTitle( JText::_('Appointment Booking Pro - requests') );

		$uri 	=& JFactory::getURI();
		$user 	=& JFactory::getUser();
		$model	=& $this->getModel();

		$frompage = JRequest::getVar('frompage', '');
		$frompage_item = JRequest::getVar('frompage_item', '');

    	//DEVNOTE: let's be the template 'form.php' instead of 'default.php' 
		$this->setLayout('form');

    	//DEVNOTE: prepare array 
		$lists = array();


		//get the data
		$detail	=& $this->get('data');
		//print_r($detail);
		
    	//DEVNOTE: the new record ?  Edit or Create?
		$isNew		= ($detail->id_requests < 1);

		// fail if checked out not by 'me'
		if ($model->isCheckedOut( $user->get('id') )) {
			$msg = JText::sprintf( 'DESCBEINGEDITTED', JText::_( 'THE DETAIL' ), $detail->descript );
			$mainframe->redirect( 'index.php?option='. $option, $msg );
		}

		// Set toolbar items for the page
		if($frompage != ""){
			$text = JText::_( 'VIEW' );
		} else {
			$text = $isNew ? JText::_( 'NEW' ) : JText::_( 'EDIT' );
		}
		JToolBarHelper::title(   JText::_( 'RS1_ADMIN_TOOLBAR_APPOINTMENTS_DETAIL' ).': <small><small>[ ' . $text.' ]</small></small>', 'addedit'  );
		if($frompage != ""){
			// for existing items the button is renamed `close`
			JToolBarHelper::cancel( 'cancel', 'Close' );
		} else {
			JToolBarHelper::save();
			if ($isNew)  {
				JToolBarHelper::cancel();
			} else {
				// for existing items the button is renamed `close`
				JToolBarHelper::cancel( 'cancel', 'Close' );
			}
			JToolBarHelper::help('screen.rsappt_pro.request_edit', true);
		}



		// Edit or Create?
		if (!$isNew){
			$model->checkout( $user->get('id') );
		} else{
			// initialise new record
			$detail->published = 1;
			$detail->ordering 	= 0;
		}

		// build the html select list
		$lists['published'] 		= JHTML::_('select.booleanlist',  'published', 'class="inputbox"', $detail->published );


		//DEVNOTE: Clear HTML data
		//         jimport('joomla.filter.output') -> jimport('joomla.filter.filteroutput')
		//         JOutputFilter::objectHTMLSafe ->/JFilterOutput::objectHTMLSafe 
		jimport('joomla.filter.filteroutput');	
		JFilterOutput::objectHTMLSafe( $detail, ENT_QUOTES );			

		$this->assignRef('lists',		$lists);
		$this->assignRef('detail',		$detail);
		$this->assignRef('request_url',	$uri->toString());
		$this->assignRef('frompage',	$frompage);
		$this->assignRef('frompage_item',	$frompage_item);

		parent::display($tpl);
	}
	
}

/**
 [controller]View[controller]
 */
class admin_detailVIEWrequests_detail extends JView
{
	/**
	 * Display the view
	 */
	function display($tpl = null)
	{
	
	  	$mainframe = JFactory::getApplication();

		$document = & JFactory::getDocument();
		//$document->setTitle( JText::_('Appointment Booking Pro - requests') );

		$uri 	=& JFactory::getURI();
		$user 	=& JFactory::getUser();

		require_once(JPATH_COMPONENT.DS.'models'.DS.'requests_detail.php');
		$model = new admin_detailModelrequests_detail;

		$frompage = JRequest::getVar('frompage', '');
		$frompage_item = JRequest::getVar('Item_id', '');

    	//DEVNOTE: let's be the template 'form.php' instead of 'default.php' 
		$this->setLayout('form');

    	//DEVNOTE: prepare array 
		$lists = array();


		//get the data
		$detail	=& $this->get('data');
		//print_r($detail);
		
    	//DEVNOTE: the new record ?  Edit or Create?
		$isNew		= ($detail->id_requests < 1);

		// fail if checked out not by 'me'
		if ($model->isCheckedOut( $user->get('id') )) {
			$msg = JText::sprintf( 'DESCBEINGEDITTED', JText::_( 'THE DETAIL' ), $detail->descript );
			$mainframe->redirect( 'index.php?option='. $option, $msg );
		}

		// Edit or Create?
		if (!$isNew){
			$model->checkout( $user->get('id') );
		} else{
			// initialise new record
			$detail->published = 1;
			$detail->ordering 	= 0;
		}

		// build the html select list
		$lists['published'] 		= JHTML::_('select.booleanlist',  'published', 'class="inputbox"', $detail->published );


		//DEVNOTE: Clear HTML data
		//         jimport('joomla.filter.output') -> jimport('joomla.filter.filteroutput')
		//         JOutputFilter::objectHTMLSafe ->/JFilterOutput::objectHTMLSafe 
		jimport('joomla.filter.filteroutput');	
		JFilterOutput::objectHTMLSafe( $detail, ENT_QUOTES );			

		$this->assignRef('lists',		$lists);
		$this->assignRef('detail',		$detail);
		$this->assignRef('request_url',	$uri->toString());
		$this->assignRef('user_id',		$user->id);
		$this->assignRef('frompage',	$frompage);
		$this->assignRef('frompage_item',	$frompage_item);

		parent::display($tpl);
	}
	
}	
	

?>
