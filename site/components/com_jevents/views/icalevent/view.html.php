<?php
/**
 * copyright (C) 2008 GWE Systems Ltd - All rights reserved
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

/**
 * HTML View class for the component frontend
 *
 * @static
 */
include_once(JEV_ADMINPATH."/views/icalevent/view.html.php");

class ICalEventViewIcalEvent extends AdminIcaleventViewIcalevent 
{
	
	function __construct($config = array()){
		include_once(JPATH_ADMINISTRATOR.DS."includes".DS."toolbar.php");
		parent::__construct($config);	
		
		// TODO find the active admin template
		JHTML::stylesheet("system.css",JURI::root()."administrator/templates/system/css/");
		JHTML::stylesheet("icon.css",JURI::root()."administrator/templates/khepri/css/");
		JHTML::stylesheet("general.css",JURI::root()."administrator/templates/khepri/css/");
	}	
	
	function edit($tpl = null)
	{
		$document =& JFactory::getDocument();		
		include(JEV_ADMINLIBS."/editStrings.php");		
		$document->addScriptDeclaration($editStrings);

		JHTML::stylesheet( 'eventsadmin.css', 'administrator/components/'.JEV_COM_COMPONENT.'/assets/css/' );
		JHTML::script('editical.js','administrator/components/'.JEV_COM_COMPONENT.'/assets/js/');
		//JHTML::script('toolbarfix.js','components/'.JEV_COM_COMPONENT.'/assets/js/');
		
		$document->setTitle(JText::_('Edit ICal Event'));
		
		// Set toolbar items for the page
		JToolBarHelper::title( JText::_( 'Edit ICal Event' ), 'jevents' );
	
		$bar = & JToolBar::getInstance('toolbar');
		if ($this->id>0){
			if ($this->editCopy){
				$this->toolbarConfirmButton("icalevent.save",JText::_("save copy warning"),'save','save','Save',false);
			}
			else {
				$this->toolbarConfirmButton("icalevent.save",JText::_("save icalevent warning"),'save','save','Save',false);
			}
		}
		else {
			$this->toolbarButton("icalevent.save",'save','save','Save',false);
		}		

		$params = JComponentHelper::getParams(JEV_COM_COMPONENT);
		if ($params->get("editpopup",0)) {
			$document->addStyleDeclaration("div#toolbar-box{margin:10px 10px 0px 10px;} div#jevents {margin:0px 10px 10px 10px;} ")	;
			$this->toolbarButton("icalevent.close",'cancel','cancel','Cancel',false);
		}
		else {
			if ($this->id>0){
				$this->toolbarButton("icalevent.detail",'cancel','cancel','Cancel',false);
			}
			else {
				$this->toolbarLinkButton("month.calendar",'cancel','cancel','Cancel',false);
			}
		}
		JToolBarHelper::help( 'screen.icalevent.edit', true);		
			
		JHTML::_('behavior.tooltip');

		// I pass in the rp_id so that I can return to the repeat I was viewing before editing
		$this->assign("rp_id",JRequest::getInt("rp_id",0));
		
		$this->_adminStart();			
		parent::displaytemplate($tpl);
		$this->_adminEnd();
	}	
	
	function _adminStart(){
		
?>
	<div style="clear:both">
		<div id="toolbar-box" >
<?php
		$bar = & JToolBar::getInstance('toolbar');
		$barhtml = $bar->render();
		//$barhtml = str_replace('href="#"','href="javascript void();"',$barhtml);
		//$barhtml = str_replace('submitbutton','return submitbutton',$barhtml);
		echo $barhtml;
		global $mainframe;
		$title = $mainframe->get('JComponentTitle');
		echo $title;
?>
		</div>
<?php			
	}

	function _adminEnd(){
?>
	</div>
<?php			
	}

	
	function toolbarButton($task = '', $icon = '', $iconOver = '', $alt = '', $listSelect = true){
		include_once(JEV_ADMINPATH."libraries/jevbuttons.php");
		$bar = & JToolBar::getInstance('toolbar');

		// Add a standard button
		$bar->appendButton( 'Jev', $icon, $alt, $task, $listSelect );
		
	}
	
	function toolbarLinkButton($task = '', $icon = '', $iconOver = '', $alt = ''){
		include_once(JEV_ADMINPATH."libraries/jevbuttons.php");
		$bar = & JToolBar::getInstance('toolbar');

		// Add a standard button
		$bar->appendButton( 'Jevlink', $icon, $alt, $task, false );
		
	}

	function toolbarConfirmButton($task = '',  $msg='',  $icon = '', $iconOver = '', $alt = '', $listSelect = true){
		include_once(JEV_ADMINPATH."libraries/jevbuttons.php");
		$bar = & JToolBar::getInstance('toolbar');

		// Add a standard button
		$bar->appendButton( 'Jevconfirm', $msg, $icon, $alt, $task, $listSelect );
		
	}

	
}
