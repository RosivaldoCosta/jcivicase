<?php

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

class JboloViewHistory extends JView
{
	function display($tpl = null)
	{
		$user= JFactory::getUser();
		
		if($user->id)
		{
			
			$userlist =& $this->get('UserList');
			$this->assignRef( 'userlist', $userlist );

			$history   =&  $this->get('Data');

			$this->assignRef( 'history',	$history );

			$total 	=& $this->get('total');
			$this->assign('total',			$total);		

			// Pagination variables		
			$limit		= JRequest::getVar('limit', 20, '', 'int');
			$limitstart	= JRequest::getVar('limitstart', 0, '', 'int');		
	
			jimport('joomla.html.pagination');
			$this->pagination = new JPagination($total, $limitstart, $limit);	

			parent::display($tpl);
		}
		else
		{
			echo JText::_("LOGIN_HISTORY");
		}
	}
}
