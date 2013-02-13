<?php
/**
 * JEvents Component for Joomla 1.5.x
 *
 * @version     $Id: categoryController.php 1117 2008-07-06 17:20:59Z tstahl $
 * @package     JEvents
 * @copyright   Copyright (C) 2006-2008 JEvents Project Group
 * @license     GNU/GPLv2, see http://www.gnu.org/licenses/gpl-2.0.html
 * @link        http://joomlacode.org/gf/project/jevents
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.controller');

class AdminConfigController extends JController {

	/**
	 * Controler for the Config Management
	 * @param array		configuration
	 */
	function __construct($config = array())
	{
		parent::__construct($config);
		$this->registerTask( 'edit',  'edit' );
		$this->registerDefaultTask("edit");
		
	}

	function edit( )
	{
				
		// get the view
		$this->view = & $this->getView("config","html");
		
		// Set the layout
		$this->view->setLayout('edit');
		
		$this->view->display();

	}

	function save(){
		$user =& JFactory::getUser();

		//Options
		$csstxt =  JRequest::getVar(  'conf_style', '');
		$csstxt = stripslashes($csstxt);

		// put all params to new JEVConfig object
		$new_config = & new JEVConfig();

		$new_config->set('com_componentname',trim(strtolower(  JRequest::getVar( 'conf_componentname','com_jevents'))));
		$new_config->set('com_cache',					JRequest::getInt('conf_cache', 0  ));
		$new_config->set('com_starday',					JRequest::getInt('conf_starday', 0  ));
		$new_config->set('com_mailview',				JRequest::getInt('conf_mailview', '1' ));
		$new_config->set('com_print_icon_view',			JRequest::getInt('conf_print_icon_view', '1' ));
		$new_config->set('com_byview',					JRequest::getInt( 'conf_byview', '1' ));
		$new_config->set('com_earliestyear',			JRequest::getInt( 'conf_earliestyear', 1995 ));
		$new_config->set('com_latestyear',				JRequest::getInt( 'conf_latestyear', 1995 ));
		$new_config->set('com_hitsview',				JRequest::getInt( 'conf_hitsview', '1' ));
		$new_config->set('com_repeatview',				JRequest::getInt( 'conf_repeatview', '1' ));
		$new_config->set('com_showrepeats',				JRequest::getInt( 'conf_showrepeats', '1'));
		$new_config->set('com_linkcloaking',			JRequest::getInt( 'conf_linkcloaking', 0));
		$new_config->set('com_hideshowbycats',			JRequest::getInt( 'conf_hideshowbycats', 1));
		$new_config->set('com_copyright',				JRequest::getInt( 'conf_copyright', 1));
		$new_config->set('com_dateformat',				JRequest::getInt( 'conf_dateformat', 1 ));
		$new_config->set('com_calHeadline',				JRequest::getString( 'conf_calHeadline', 'comp' ));
		$new_config->set('com_calUseIconic',			JRequest::getInt( 'conf_calUseIconic', 1 ));
		$new_config->set('com_navbarcolor',				trim(  JRequest::getVar( 'conf_navbarcolor', 'green' )));
		$new_config->set('com_defColor',				trim(  JRequest::getVar( 'conf_defColor', 'category' )));
		$new_config->set('com_calSimpleEventForm',		JRequest::getInt( 'conf_calSimpleEventForm', 0));
		$new_config->set('com_calForceCatColorEventForm',JRequest::getInt( 'conf_calForceCatColorEventForm', 0));
		$new_config->set('com_blockRobots',				JRequest::getInt( 'conf_blockRobots', 1));
		
		//$new_config->set('com_legacyEvents',			JRequest::getInt( 'conf_legacyEvents', 1));
		//$new_config->set('com_legacy_tab_extra_view',	JRequest::getInt( 'conf_legacy_tab_extra_view', 0));
		//$new_config->set('com_legacy_tab_help_view',	JRequest::getInt( 'conf_legacy_tab_help_view', 0));
		//$new_config->set('com_legacy_tab_about_view',	JRequest::getInt( 'conf_legacy_tab_about_view', 0));
		$new_config->set('com_legacyEvents',			0);
		$new_config->set('com_legacy_tab_extra_view',	1);
		$new_config->set('com_legacy_tab_help_view',	1);
		$new_config->set('com_legacy_tab_about_view',	1);
		
		$new_config->set('com_show_editor_buttons',		JRequest::getInt( 'conf_show_editor_buttons', 1));
		$new_config->set('com_editor_button_exceptions', str_replace(' ', '', JRequest::getString( 'conf_editor_button_exceptions', '')));
		$new_config->set('com_single_pane_edit',		JRequest::getInt( 'conf_single_pane_edit', 0));
		$new_config->set('com_calEventListRowsPpg',		JRequest::getInt( 'conf_calEventListRowsPpg', 10 ));
		$new_config->set('com_calUseStdTime',			JRequest::getInt( 'conf_calUseStdTime', '1' ));
		$new_config->set('com_calCutTitle',				JRequest::getInt( 'conf_calCutTitle', 15 ));
		$new_config->set('com_calMaxDisplay',			JRequest::getInt( 'conf_calMaxDisplay', 15 ));
		$new_config->set('com_calDisplayStarttime',		JRequest::getInt( 'conf_calDisplayStarttime', 1 ));
		$new_config->set('com_calViewName',				trim(JRequest::getVar( 'conf_calViewName', "default" )));

		// tooltips
		$new_config->set('com_enableToolTip',			JRequest::getInt( 'conf_enableToolTip', 1 ));
		$new_config->set('com_calTTBackground',			JRequest::getInt( 'conf_calTTBackground', 1 ));
		$new_config->set('com_calTTPosX',				trim(  JRequest::getVar( 'conf_calTTPosX', 'LEFT' )));
		$new_config->set('com_calTTPosY',				trim(  JRequest::getVar( 'conf_calTTPosY', 'BELOW' )));
		$new_config->set('com_calTTShadow',				JRequest::getInt( 'conf_calTTShadow', 0 ));
		$new_config->set('com_calTTShadowX',			JRequest::getInt( 'conf_calTTShadowX', 0 ));
		$new_config->set('com_calTTShadowY',			JRequest::getInt( 'conf_calTTShadowY', 0 ));

		// rss
		$new_config->set('com_rss_cache',				JRequest::getInt( 'conf_rss_cache', 1 ));
		$new_config->set('com_rss_cache_time',			JRequest::getInt( 'conf_rss_cache_time', 3600 ));
		$new_config->set('com_rss_count',				JRequest::getInt( 'conf_rss_count', 5 ));
		$new_config->set('com_rss_live_bookmarks',		JRequest::getInt( 'conf_rss_live_bookmarks', 1 ));
		$new_config->set('com_rss_modid',				JRequest::getInt( 'conf_rss_modid', 0 ));
		$new_config->set('com_rss_title',				trim(  JRequest::getVar( 'conf_rss_title', "Powered by JEvents!" )));
		$new_config->set('com_rss_description',			trim(  JRequest::getVar( 'conf_rss_description', "JEvents Syndication for Joomla" )));
		$new_config->set('com_rss_limit_text',			JRequest::getInt( 'conf_rss_limit_text', 0 ));
		$new_config->set('com_rss_text_length',			JRequest::getInt( 'conf_rss_text_length', 20));
		// mod_cal
		$new_config->set('modcal_DispLastMonth',		trim(  JRequest::getVar( 'conf_modCalDispLastMonth', 'NO' )));
		$new_config->set('modcal_DispLastMonthDays',	JRequest::getInt( 'conf_modCalDispLastMonthDays', 0 ));
		$new_config->set('modcal_DispNextMonth',		trim(  JRequest::getVar( 'conf_modCalDispNextMonth', 'NO' )));
		$new_config->set('modcal_DispNextMonthDays',	JRequest::getInt( 'conf_modCalDispNextMonthDays', 0 ));
		$new_config->set('modcal_LinkCloaking',			trim(  JRequest::getVar( 'conf_modCalLinkCloaking', '0' )));

		// mod_latest
		$new_config->set('modlatest_MaxEvents',			min(150, abs(JRequest::getInt( 'conf_modLatestMaxEvents', 5 ))));
		$new_config->set('modlatest_Mode',				JRequest::getInt( 'conf_modLatestMode', 0 ));
		$new_config->set('modlatest_Days',				JRequest::getInt( 'conf_modLatestDays', 20 ));
		$new_config->set('modlatest_DispLinks',			JRequest::getInt( 'conf_modLatestDispLinks', '1' ));
		$new_config->set('modlatest_NoRepeat',			JRequest::getInt( 'conf_modLatestNoRepeat', '0' ));
		$new_config->set('modlatest_DispYear',			JRequest::getInt( 'conf_modLatestDispYear', '0' ));
		$new_config->set('modlatest_DisDateStyle',		JRequest::getInt( 'conf_modLatestDisDateStyle', '0' ));
		$new_config->set('modlatest_DisTitleStyle',		JRequest::getInt( 'conf_modLatestDisTitleStyle', '0' ));
		$new_config->set('modlatest_LinkToCal',			JRequest::getInt( 'conf_modLatestLinkToCal', '0' ));
		$new_config->set('modlatest_LinkCloaking',		JRequest::getInt( 'conf_modLatestLinkCloaking', '0' ));
		$new_config->set('modlatest_SortReverse',		JRequest::getInt( 'conf_modLatestSortReverse', '0' ));
		$new_config->set('modlatest_CustFmtStr',		str_replace('<br />', '\n',trim(  JRequest::getVar( 'conf_modLatestCustFmtStr', '', 'default', 'string', JREQUEST_ALLOWRAW))));
		$new_config->set('modlatest_RSS',				JRequest::getInt( 'conf_modLatestRSS', 0 ));

		// save config params
		$confmsg = $new_config->saveEventsINI();
		if (is_string($confmsg)) {
			$this->setRedirect("index.php?option=".JEV_COM_COMPONENT."&task=config.edit", $confmsg);
			return;
		}

		// restore perms
		//@chmod( $configfile, $oldPermsConfig);

		$mosmsg = JText::_('JEV_MSG_CONFIG_SAVED');
		$this->setRedirect("index.php?option=".JEV_COM_COMPONENT."&task=config.edit", $mosmsg);
	}


	/**
	 * Converts old style events into new iCal events
	 *
	 */
	function convert(){
		$user =& JFactory::getUser();

		if (strtolower($user->usertype)!="super administrator" && strtolower($user->usertype)!="administrator"){
			$this->setRedirect( "index.php?option=".JEV_COM_COMPONENT."&task=cpanel.cpanel", "Not Authorised - must be admin" );
			return;
		}

		$cfg = & JEVConfig::getInstance();
		$option = JEV_COM_COMPONENT;
		$db	=& JFactory::getDBO();

		JLoader::register('vCal',JEV_PATH."/libraries/vCal.php");
		
		/**
		 * find the categories first
		 */		
		$query = "SELECT cc.*, ec.color FROM #__categories AS cc LEFT JOIN #__events_categories AS ec ON ec.id=cc.id WHERE cc.section='com_events'";
		$db->setQuery( $query );
		$cats = $db->loadObjectList('id');
		
		// Create new categories with section com_jevents" 
		JLoader::register('JEventsCategory',JEV_ADMINPATH."/libraries/categoryClass.php");

		$query = "SELECT cc.*, ec.color FROM #__categories AS cc LEFT JOIN #__jevents_categories AS ec ON ec.id=cc.id WHERE cc.section='com_jevents'";
		$db->setQuery( $query );
		$rows = $db->loadObjectList('id');
		$jcats = array();
		foreach ($rows as $jcat) {
			$newcat = new JEventsCategory($db);
			$newcat->bind(get_object_vars($jcat));			
			$jcats[$jcat->id]=$newcat;
		}
		
		foreach ($cats as $cat) {
			// check not already mapped
			$mapped = false;
			foreach ($jcats as $jcat) {
				if ($jcat->alias==$cat->alias && $jcat->description==$cat->description && $jcat->title==$cat->title){
					$cat->newid = $jcat->id;
					$mapped=true;
					break;
				}
			}
			if (!$mapped){
				$newcat = new JEventsCategory($db);
				$newcat->bind(get_object_vars($cat));
				$newcat->id=null;
				$newcat->store();
				$cat->newid =  $newcat->id;
				$jcats[$newcat->id] = $newcat;
			}
		}
		// make sure parent field is correct
		foreach ($jcats as $key => $jcat) {
			if ($jcat->parent_id>0 && array_key_exists($jcat->parent_id,$cats)){
				$jcat->parent_id = $cats[$jcat->parent_id]->newid;
				$jcat->store();
				$jcats[$key]=$jcat;
			}
		}
		
		foreach ($cats as $cat) {
			$query = "SELECT ev.*, cc.name AS category, "
			."\n UNIX_TIMESTAMP(ev.publish_up) AS dtstart ,"
			."\n UNIX_TIMESTAMP(ev.publish_down) AS dtend "
			."\n FROM  #__events AS ev, #__categories AS cc "
			."\n WHERE ev.catid = cc.id"
			."\n AND cc.id = $cat->id";
			$db->setQuery( $query );
			$detevents = $db->loadObjectList();

			$showBR = intval( JRequest::getVar( 'showBR', '0'));
			// get vCal with HTML encoded descriptions
			$cal = new vCal("", true);

			if (count($detevents)>0){
				foreach ($detevents as $event) {				
					$cal->addEvent($event);
				}

				$output = $cal->getVCal();
				if ($showBR){
					echo "Processing cat $cat->title<br/>";
					echo $output;
					echo "<hr/>";
				}

				// Map them to the new cat id
				$catid = $cat->newid;
				$access = $cat->access;
				$state = $cat->published;
				// find the default icsfile - if none then create a new one
				$sql = "SELECT * FROM #__jevents_icsfile WHERE icaltype=2 AND isdefault=1";
				$db->setQuery($sql);
				$ics = $db->loadObject();
				if(!$ics || is_null($ics) ){
					$icsid = 0; // new
					$icsLabel = "$cat->title (imp)";
				}
				else {
					$icsid = $ics->ics_id;
					$icsLabel = $ics->label;
					if ($ics->catid==0){
						$sql = "UPDATE #__jevents_icsfile SET catid=".$cat->newid." WHERE ics_id=".$icsid;
						$db->setQuery($sql);
						$db->query();
					}
				}
				$icsFile = iCalICSFile::newICSFileFromString($output,$icsid,$catid,$access,$state,$icsLabel);
				$icsFileid = $icsFile->store($catid);

			}
		}
		$this->setRedirect( "index.php?option=".JEV_COM_COMPONENT."&task=cpanel.cpanel", "Events Migrated" );
	}
	
	
	/*
	 * Utility functiond during development and migration 
	 * TODO CHECK WHICH OF THESE MUST BE REMOVED BEFORE RELEASE!!!
	 */
	/**
	 * Drops Ical Tables
	 *
	 */
	function droptables() {
		$user =& JFactory::getUser();

		if (strtolower($user->usertype)!="super administrator"){
			$this->setRedirect( "index.php?option=".JEV_COM_COMPONENT."&task=cpanel.cpanel", "Not Authorised - must be super admin" );
			return;
		}

		$db	=& JFactory::getDBO();
		$sql="DROP TABLE #__jevents_icsfile";
		$db->setQuery($sql);
		$db->query();

		$sql="DROP TABLE #__jevents_rrule";
		$db->setQuery($sql);
		$db->query();

		$sql="DROP TABLE #__jevents_vevdetail";
		$db->setQuery($sql);
		$db->query();

		$sql="DROP TABLE #__jevents_vevent";
		$db->setQuery($sql);
		$db->query();

		$sql="DROP TABLE #__jevents_repetition";
		$db->setQuery($sql);
		$db->query();

		$sql="DROP TABLE #__jevents_exception";
		$db->setQuery($sql);
		$db->query();

		$sql="DROP TABLE #__jevents_categories";
		$db->setQuery($sql);
		$db->query();
		
		$sql="DELETE FROM  #__categories where section='com_jevents'";
		$db->setQuery($sql);
		$db->query();

		
		$this->setMessage( "Tables Dropped and recreated" );
		
		$this->dbsetup();
	}
	

	function dbsetup(){
		$db	=& JFactory::getDBO();

		$charset = ($db->hasUTF()) ? 'DEFAULT CHARACTER SET `utf8`' : '';

		
		/**
	 * create table if it doesn't exit
	 * 
	 * For now : 
	 * 
	 * I'm ignoring attach,comment, resources, transp, attendee, related to, rdate, request-status
	 * 
	 * Separate tables for rrule and exrule
	 */
		$sql = <<<SQL
CREATE TABLE IF NOT EXISTS #__jevents_vevent(
	ev_id int(12) NOT NULL auto_increment,
	icsid int(12) NOT NULL default 0,
	catid int(11) NOT NULL default 1,
	uid varchar(255) NOT NULL UNIQUE default "",
	refreshed datetime  NOT NULL default '0000-00-00 00:00:00',
	created_by int(11) unsigned NOT NULL default '0',
	created_by_alias varchar(100) NOT NULL default '',
	modified_by int(11) unsigned NOT NULL default '0',

	rawdata longtext NOT NULL default "",
	recurrence_id varchar(30) NOT NULL default "",
	
	detail_id int(12) NOT NULL default 0,
	
	state tinyint(3) NOT NULL default 1,
	access int(11) unsigned NOT NULL default 0,
	
	PRIMARY KEY  (ev_id),
	INDEX (icsid)
) TYPE=MyISAM $charset;	
SQL;
		$db->setQuery($sql);
		$db->query();
		echo $db->getErrorMsg();

		$sql = "ALTER TABLE `#__jevents_vevent` ADD created datetime  NOT NULL default '0000-00-00 00:00:00'";
		$db->setQuery( $sql );
		@$db->query();
				
		
		/**
	 * create table if it doesn't exit
	 * 
	 * For now : 
	 * 
	 * I'm ignoring attach,comment, resources, transp, attendee, related to, rdate, request-status
	 * 
	 * Separate tables for rrule and exrule
	 */
		$sql = <<<SQL
CREATE TABLE IF NOT EXISTS #__jevents_vevdetail(
	evdet_id int(12) NOT NULL auto_increment,

	rawdata longtext NOT NULL default "",
	dtstart int(11) NOT NULL default 0,
	dtstartraw varchar(30) NOT NULL default "",
	duration int(11) NOT NULL default 0,
	durationraw varchar(30) NOT NULL default "",
	dtend int(11) NOT NULL default 0,
	dtendraw varchar(30) NOT NULL default "",
	dtstamp varchar(30) NOT NULL default "",
	class  varchar(10) NOT NULL default "",
	categories varchar(120) NOT NULL default "",
	color varchar(20) NOT NULL default "",
	description longtext NOT NULL default "",
	geolon float NOT NULL default 0,
	geolat float NOT NULL default 0,
	location VARCHAR(120) NOT NULL default "",
	priority tinyint unsigned NOT NULL default 0,
	status varchar(20) NOT NULL default "",
	summary longtext NOT NULL default "",
	contact VARCHAR(120) NOT NULL default "",
	organizer VARCHAR(120) NOT NULL default "",
	url VARCHAR(120) NOT NULL default "",
	extra_info VARCHAR(240) NOT NULL DEFAULT '',
	created varchar(30) NOT NULL default "",
	sequence int(11) NOT NULL default 1,
	state tinyint(3) NOT NULL default 1,

	multiday tinyint(3) NOT NULL default 1,
	hits int(11) NOT NULL default 0,
	noendtime tinyint(3) NOT NULL default 0,
		
	PRIMARY KEY  (evdet_id)
) TYPE=MyISAM $charset;	
SQL;
		$db->setQuery($sql);
		$db->query();
		echo $db->getErrorMsg();

		$sql = "ALTER TABLE `#__jevents_vevdetail` ADD color varchar(20) NOT NULL default ''";
		$db->setQuery( $sql );
		@$db->query();
		
		$sql = "ALTER TABLE `#__jevents_vevdetail` ADD multiday tinyint(3) NOT NULL default 1";
		$db->setQuery( $sql );
		@$db->query();

		$sql = "ALTER TABLE `#__jevents_vevdetail` ADD noendtime tinyint(3) NOT NULL default 0";
		$db->setQuery( $sql );
		@$db->query();
		
		$sql = "ALTER TABLE `#__jevents_vevdetail` ADD hits int(11) NOT NULL default 0";
		$db->setQuery( $sql );
		@$db->query();

		$sql = <<<SQL
CREATE TABLE IF NOT EXISTS #__jevents_rrule (
	rr_id int(12) NOT NULL auto_increment,
	eventid int(12) NOT NULL default 1,
	freq varchar(30) NOT NULL default "",
	until int(12) NOT NULL default 1,
	untilraw varchar(30) NOT NULL default "",
	count int(6) NOT NULL default 1,
	rinterval int(6) NOT NULL default 1,
	bysecond  varchar(50) NOT NULL default "",
	byminute  varchar(50) NOT NULL default "",
	byhour  varchar(50) NOT NULL default "",
	byday  varchar(50) NOT NULL default "",
	bymonthday  varchar(50) NOT NULL default "",
	byyearday  varchar(50) NOT NULL default "",
	byweekno  varchar(50) NOT NULL default "",
	bymonth  varchar(50) NOT NULL default "",
	bysetpos  varchar(50) NOT NULL default "",
	wkst  varchar(50) NOT NULL default "",
	PRIMARY KEY  (rr_id),
	INDEX (eventid)
) TYPE=MyISAM $charset;	
SQL;
		$db->setQuery($sql);
		$db->query();
		echo $db->getErrorMsg();

		$sql = "ALTER TABLE `#__jevents_rrule` ADD INDEX eventid (eventid)";
		$db->setQuery( $sql );
		@$db->query();

		$sql = <<<SQL
CREATE TABLE IF NOT EXISTS #__jevents_repetition (
	rp_id int(12) NOT NULL auto_increment,
	eventid int(12) NOT NULL default 1,
	eventdetail_id int(12) NOT NULL default 0,	
	duplicatecheck varchar(32) NOT NULL UNIQUE default "",
	startrepeat datetime  NOT NULL default '0000-00-00 00:00:00',
	endrepeat datetime  NOT NULL default '0000-00-00 00:00:00',
	PRIMARY KEY  (rp_id),
	INDEX (eventid),
	INDEX `eventstart` ( `eventid` , `startrepeat` )
) TYPE=MyISAM $charset;	
SQL;
		$db->setQuery($sql);
		$db->query();
		echo $db->getErrorMsg();
		
		$sql = "ALTER TABLE `#__jevents_repetition` ADD INDEX `eventstart` ( `eventid` , `startrepeat` )";
		$db->setQuery( $sql );
		@$db->query();

		// exception_type 0=delete, 1=other exception
		$sql = <<<SQL
CREATE TABLE IF NOT EXISTS #__jevents_exception (
	ex_id int(12) NOT NULL auto_increment,
	rp_id int(12) NOT NULL default 0,
	eventid int(12) NOT NULL default 1,
	eventdetail_id int(12) NOT NULL default 0,	
	exception_type int(2) NOT NULL default 0,
	PRIMARY KEY  (ex_id),
	KEY (eventid),
	KEY (rp_id)
) TYPE=MyISAM $charset;	
SQL;
		$db->setQuery($sql);
		$db->query();
		echo $db->getErrorMsg();

		$sql = <<<SQL
CREATE TABLE IF NOT EXISTS #__jevents_categories (
	id int(12) NOT NULL default 0 PRIMARY KEY,
	color VARCHAR(8) NOT NULL default '',
	admin int(12) NOT NULL default 0
) TYPE=MyISAM $charset;
SQL;

		$db->setQuery($sql);
		$db->query();
		echo $db->getErrorMsg();

		$sql = "ALTER TABLE `#__jevents_categories` add column admin int(12) NOT NULL default 0";
		$db->setQuery( $sql );
		@$db->query();

		// Add one category by default if none exist already
		$sql = "SELECT count(id) from #__jevents_categories";
		$db->setQuery($sql);
		$count = $db->loadResult();

		if($count==0){
			JLoader::register('JEventsCategory',JEV_ADMINPATH."/libraries/categoryClass.php");
			$cat = new JEventsCategory($db);
			$cat->bind(array("title"=>JText::_("Default"), "published"=>1, "color"=>"#CCCCFF"));
			$cat->store();
			$catid=$cat->id;
		}
		else {
			$catid= 0;
		}
		
		/**
	 * create table if it doesn't exit
	 * 
	 * For now : 
	 * 
	 * I'm ignoring attach,comment, resources, transp, attendee, related to, rdate, request-status
	 * 
	 * note that icaltype = 0 for imported from URL, 1 for imported from file, 2 for created natively
	 * Separate tables for rrule and exrule
	 */
		$sql = <<<SQL
CREATE TABLE IF NOT EXISTS #__jevents_icsfile(
	ics_id int(12) NOT NULL auto_increment,
	srcURL VARCHAR(120) NOT NULL default "",
	label varchar(30) NOT NULL UNIQUE default "",

	filename VARCHAR(120) NOT NULL default "",
	icaltype tinyint(3) NOT NULL default 0,
	isdefault tinyint(3) NOT NULL default 0,
	state tinyint(3) NOT NULL default 1,
	access int(11) unsigned NOT NULL default 0,
	catid int(11) NOT NULL default 1,
	created datetime  NOT NULL default '0000-00-00 00:00:00',
	created_by int(11) unsigned NOT NULL default '0',
	created_by_alias varchar(100) NOT NULL default '',
	modified_by int(11) unsigned NOT NULL default '0',
	refreshed datetime  NOT NULL default '0000-00-00 00:00:00',
	autorefresh tinyint(3) NOT NULL default 0,
		
	PRIMARY KEY  (ics_id)
) TYPE=MyISAM $charset;	
SQL;
		$db->setQuery($sql);
		$db->query();
		echo $db->getErrorMsg();

		// Alter table 
		$sql = "Alter table #__jevents_icsfile ADD COLUMN isdefault tinyint(3) NOT NULL default 0";
		$db->setQuery($sql);
		@$db->query();

		$sql = "Alter table #__jevents_icsfile ADD COLUMN autorefresh tinyint(3) NOT NULL default 0";
		$db->setQuery($sql);
		@$db->query();

		// Add one native calendar by default if none exist already
		$sql = "SELECT ics_id from #__jevents_icsfile WHERE icaltype=2";
		$db->setQuery($sql);
		$ics = $db->loadResult();

		if(!$ics || is_null($ics) || $ics==0 ){
			$sql = "INSERT INTO #__jevents_icsfile (label,filename,	icaltype,state,	access,	catid, isdefault) VALUES ('Default','Initial ICS File',2,1,0,$catid,1)";
			$db->setQuery($sql);
			$db->query();
			echo $db->getErrorMsg();
		}

		// 1. Make sure users table exists
		$sql = <<<SQL
CREATE TABLE IF NOT EXISTS `#__jev_users` (
	`id` int( 11 ) unsigned NOT NULL AUTO_INCREMENT ,
	`user_id` int( 11 ) NOT NULL default '0',
	`published` tinyint( 2 ) NOT NULL default '0',

	`canuploadimages` tinyint( 2 ) NOT NULL default '0',
	`canuploadmovies` tinyint( 2 ) NOT NULL default '0',

	`cancreate` tinyint( 2 ) NOT NULL default '0',
	`canedit` tinyint( 2 ) NOT NULL default '0',

	`canpublishown` tinyint( 2 ) NOT NULL default '0',
	`candeleteown` tinyint( 2 ) NOT NULL default '0',

	`canpublishall` tinyint( 2 ) NOT NULL default '0',
	`candeleteall` tinyint( 2 ) NOT NULL default '0',

	`cancreateown` tinyint( 2 ) NOT NULL default '0',
	`cancreateglobal` tinyint( 2 ) NOT NULL default '0',
	`eventslimit` int( 11 ) NOT NULL default '0',
	`extraslimit` int( 11 ) NOT NULL default '0',
	
	PRIMARY KEY ( `id` ),
	KEY `user` (`user_id`  )
);
SQL;
		$db->setQuery( $sql );
		if (!$db->query()){
			echo $db->getErrorMsg();
		}
		
		// get the view
		$this->view = & $this->getView("config","html");
		
		// Set the layout
		$this->view->setLayout('dbsetup');
		
		$this->view->display();
	}
}
