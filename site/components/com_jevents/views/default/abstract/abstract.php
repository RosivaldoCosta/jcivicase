<?php
/**
 * copyright (C) 2008 GWE Systems Ltd - All rights reserved
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();


// option masks
define( 'MASK_BACKTOLIST', 0x0001 );
define( 'MASK_READON',     0x0002 );
define( 'MASK_POPUP',      0x0004 );
define( 'MASK_HIDEPDF',    0x0008 );
define( 'MASK_HIDEPRINT',  0x0010 );
define( 'MASK_HIDEEMAIL',  0x0020 );
define( 'MASK_IMAGES',     0x0040 );
define( 'MASK_VOTES',      0x0080 );
define( 'MASK_VOTEFORM',   0x0100 );

define( 'MASK_HIDEAUTHOR',     0x0200 );
define( 'MASK_HIDECREATEDATE', 0x0400 );
define( 'MASK_HIDEMODIFYDATE', 0x0800 );

define( 'MASK_LINK_TITLES', 0x1000 );

// mos_content.mask masks
define( 'MASK_HIDE_TITLE', 0x0001 );
define( 'MASK_HIDE_INTRO', 0x0002 );

/**
 * HTML Abstract view class for the component frontend
 *
 * @static
 */
class JEventsDefaultView extends JEventsAbstractView
{
	var $jevlayout = null;

	function __construct($config = null)
	{
		parent::__construct($config);

		$this->jevlayout="default";	
		
		$this->addHelperPath(realpath(dirname(__FILE__)."/../helpers"));

		// attach data model
		$this->datamodel  =  new JEventsDataModel();
		$this->datamodel->setupComponentCatids();
		
		$reg = & JevRegistry::getInstance("jevents");
		$reg->setReference("jevents.datamodel",$this->datamodel);		

	}

	function getViewName(){
		return $this->jevlayout;
	}

	function loadHelper( $file = null)
	{
		if (function_exists($file)) return;
		
		// load the template script
		jimport('joomla.filesystem.path');
		$helper = JPath::find($this->_path['helper'], $this->_createFileName('helper', array('name' => $file)));

		if ($helper != false)
		{
			// include the requested template filename in the local scope
			include_once $helper;
		}
		return $helper;
	}
	
	function _header() {
		$this->loadHelper("DefaultViewHelperHeader");
		DefaultViewHelperHeader($this);
	}

	function _footer() {
		$this->loadHelper("DefaultViewHelperFooter");
		DefaultViewHelperFooter($this);
	}

	function _showNavTableBar() {
		$this->loadHelper("DefaultViewHelperShowNavTableBar");
		DefaultViewHelperShowNavTableBar($this);
	}

	function _viewNavAdminPanel(){
		$this->loadHelper("DefaultViewHelperViewNavAdminPanel");
		DefaultViewHelperViewNavAdminPanel($this);
	}

	function viewNavTableBarIconic( $today_date, $this_date, $dates, $alts, $option, $task, $Itemid ){
		$this->loadHelper("DefaultViewNavTableBarIconic");
		$var = new DefaultViewNavTableBarIconic($this, $today_date, $this_date, $dates, $alts, $option, $task, $Itemid );
	}

	function viewNavTableBar( $today_date, $this_date, $dates, $alts, $option, $task, $Itemid ){
		$this->loadHelper("DefaultViewNavTableBar");
		$var = new DefaultViewNavTableBar($this, $today_date, $this_date, $dates, $alts, $option, $task, $Itemid );
	}

	function viewEventRowNew ( $row){
		$this->loadHelper("DefaultViewEventRowNew");
		DefaultViewEventRowNew($this, $row);
	}

	function viewEventCatRowNew ( $row){
		$this->loadHelper("DefaultViewEventCatRowNew");
		DefaultViewEventCatRowNew($this, $row);
	}

	function eventIcalDialog($row, $mask){
		$this->loadHelper("DefaultEventIcalDialog");
		DefaultEventIcalDialog($this,  $row, $mask);
	}

	function eventManagementDialog($row, $mask){
		$this->loadHelper("DefaultEventManagementDialog");
		DefaultEventManagementDialog($this,  $row, $mask);
	}

	function viewEventRowAdmin($row){
		$this->loadHelper("DefaultViewEventRowAdmin");
		DefaultViewEventRowAdmin($this,  $row);
	}

	function viewNavCatText( $catid, $option, $task, $Itemid ){
		$this->loadHelper("DefaultViewNavCatText");
		DefaultViewNavCatText($this,$catid, $option, $task, $Itemid );		
	}
	
	function paginationForm($total, $limitstart, $limit){
		if ($this->loadHelper("DefaultPaginationForm")){
			DefaultPaginationForm($total, $limitstart, $limit);
		}
	}
	
	function eventsLegend(){
		$cfg = & JEVConfig::getInstance();
		$theme = JEV_CommonFunctions::getJEventsViewName();

		$modpath = JModuleHelper::getLayoutPath('mod_jevents_legend',$theme.DS."legend");
		if (!file_exists($modpath)) return;
		
		require_once($modpath);

		$viewclass = ucfirst($theme)."ModLegendView";
		$module = JModuleHelper::getModule("mod_jevents_legend",false);
		
		$params = new JParameter( $module->params );
		
		$modview = new $viewclass($params, $module->id);
		echo $modview->displayCalendarLegend("block");

		echo "<br style='clear:both'/>";
	}
}
