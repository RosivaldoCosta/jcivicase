<?php
/**
 * copyright (C) 2008 GWE Systems Ltd - All rights reserved
 */

defined( 'JPATH_BASE' ) or die( 'Direct Access to this location is not allowed.' );

jimport('joomla.application.component.controller');

class AdminCpanelController extends JController {
	/**
	 * Controler for the Control Panel
	 * @param array		configuration
	 */
	function __construct($config = array())
	{
		parent::__construct($config);
		$this->registerTask( 'show',  'cpanel' );
		$this->registerDefaultTask("cpanel");
	}

	function cpanel( )
	{
		// check DB
		// check the latest column addition or change
		// do this in a way that supports mysql 4 
		$db =& JFactory::getDBO();
		$sql = "SHOW COLUMNS FROM `#__jevents_vevent`";
		$db->setQuery( $sql );
		$cols = $db->loadObjectList();
		if (is_null($cols) ){
			$this->setRedirect(JRoute::_("index.php?option=".JEV_COM_COMPONENT."&task=config.dbsetup",false),JText::_("Database Table Setup Was Required"));
			$this->redirect();
			//return;			
		}
		$uptodate = false;
		
		foreach ($cols as $col) {
			if ($col->Field=="created"){
				$uptodate = true;
				break;
			}
		}
		if (!$uptodate){
			$this->setRedirect(JRoute::_("index.php?option=".JEV_COM_COMPONENT."&task=config.dbsetup",false),JText::_("Database Table Update Was Required"));
			$this->redirect();
			//return;			
		}

		// are config values setup correctyl
		$params = JComponentHelper::getParams(JEV_COM_COMPONENT);
		$jevadmin = $params->getValue("jevadmin",-1);
		if ($jevadmin==-1){
			$this->setRedirect(JRoute::_("index.php?option=".JEV_COM_COMPONENT."&task=params.edit",false),JText::_("Please check configuration and save"));
			$this->redirect();
		}
		
		// get the view
		$this->view = & $this->getView("cpanel","html");

		// get all the raw native calendars
		$this->dataModel = new JEventsDataModel("JEventsAdminDBModel");
		$nativeCals = $this->dataModel->queryModel->getNativeIcalendars();
		if (is_null($nativeCals) || count($nativeCals)==0){
			$this->view->assign("warning",JText::_("Calendars not setup properly"));
		}
		
		// Set the layout
		$this->view->setLayout('cpanel');
		$this->view->assign('title'   , JText::_("Control Panel"));

		$this->view->display();
	}


}
