<?php
/**
 * copyright (C) 2008 GWE Systems Ltd - All rights reserved
 */

defined( 'JPATH_BASE' ) or die( 'Direct Access to this location is not allowed.' );

jimport('joomla.application.component.controller');

class AdminController extends JController   {

	function __construct($config = array())
	{
		parent::__construct($config);
		// TODO get this from config
		$this->registerDefaultTask( 'listevents' );
		//		$this->registerTask( 'show',  'showContent' );

		// Load abstract "view" class
		$cfg = & JEVConfig::getInstance();
		$theme = JEV_CommonFunctions::getJEventsViewName();
		JLoader::register('JEvents'.ucfirst($theme).'View',JEV_VIEWS."/$theme/abstract/abstract.php");
	}

	function listevents() {

		$is_event_editor = JEVHelper::isEventCreator();

		$Itemid	= JEVHelper::getItemid();

		$user =& JFactory::getUser();
		if( !$is_event_editor ){
			$returnlink = JRoute::_( 'index.php?option=' . JEV_COM_COMPONENT . '&task=month.calendar&Itemid=' . $Itemid, false );
			$this->setRedirect( $returnlink, html_entity_decode( JText::_('JEV_NOPERMISSION') ));
			return;
		}

		list($year,$month,$day) = JEVHelper::getYMD();
		$limitstart = intval( JRequest::getVar( 	'limitstart', 	0 ) );
		$limit 	= intval( JRequest::getVar( 	'limit', 		10 ) );

		$Itemid	= JEVHelper::getItemid();

		$task=$this->_task;

		$params = JComponentHelper::getParams(JEV_COM_COMPONENT);
		$adminuser = $params->get("jevadmin",-1);
		
		if( strtolower( $user->usertype ) == 'administrator' || strtolower( $user->usertype ) == 'super administrator' || $user->id==$adminuser) {
			$creator_id = 'ADMIN';
		}else{
			$creator_id = $user->id;
		}

		// get the view

		$document =& JFactory::getDocument();
		$viewType	= $document->getType();

		$cfg = & JEVConfig::getInstance();
		$theme = JEV_CommonFunctions::getJEventsViewName();

		$view = "admin";
		$this->addViewPath($this->_basePath.DS."views".DS.$theme);
		$this->view = & $this->getView($view,$viewType, $theme,
		array( 'base_path'=>$this->_basePath,
		"template_path"=>$this->_basePath.DS."views".DS.$theme.DS.$view.DS.'tmpl',
		"name"=>ucfirst($theme).ucfirst($view)));

		// Set the layout
		$this->view->setLayout('listevents');

		$this->view->assign("Itemid",$Itemid);
		$this->view->assign("limitstart",$limitstart);
		$this->view->assign("limit",$limit);
		$this->view->assign("month",$month);
		$this->view->assign("day",$day);
		$this->view->assign("year",$year);
		$this->view->assign("task",$task);
		$this->view->assign("creator_id",$creator_id);

		$this->view->display();

	}

}