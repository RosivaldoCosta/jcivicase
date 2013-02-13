<?php
/**
 * JEvents Component for Joomla 1.5.x
 *
 * @version     $Id: commonqueries.php 1117 2008-07-06 17:20:59Z tstahl $
 * @package     JEvents
 * @copyright   Copyright (C) 2006-2008 JEvents Project Group
 * @license     GNU/GPLv2, see http://www.gnu.org/licenses/gpl-2.0.html
 * @link        http://joomlacode.org/gf/project/jevents
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

// load language constants
JEVHelper::loadLanguage('front');

class JEventsDBModel {
	var $cfg = null;
	var $datamodel = null;
	var $legacyEvents = null;

	function JEventsDBModel(&$datamodel){
		$this->cfg = & JEVConfig::getInstance();
		// TODO - remove legacy code
		$this->legacyEvents = 0;

		$this->datamodel =& $datamodel;

	}

	function accessibleCategoryList($aid=null, $catids=null, $catidList=null) {

		global $mainframe;

		$db	=& JFactory::getDBO();
		if (is_null($aid)) {
			$aid = $this->datamodel->aid;
		}
		if (is_null($catids)) {
			$catids = $this->datamodel->catids;
		}
		if (is_null($catidList)) {
			$catidList = $this->datamodel->catidList;
		}

		$cfg = & JEVConfig::getInstance();
		$sectionname = JEV_COM_COMPONENT;

		static $instances;

		if (!$instances) {
			$instances = array();
		}

		// calculate unique index identifier
		$index = $aid . '+' . $catidList;
		// if catidList = 0 then the result is the same as a blank so slight time saving
		if (is_null($catidList) || $catidList==0) {
			$index = $aid . '+';
		}
		$where = "";

		if (!array_key_exists($index,$instances)) {
			if (count($catids)>0 && !is_null($catidList) && $catidList!="0") {
				$where = ' AND (c.id IN (' . $catidList .') OR p.id IN (' . $catidList .')  OR gp.id IN (' . $catidList .') OR ggp.id IN (' . $catidList .'))';
			}

			$q_published = $mainframe->isAdmin() ? "\n AND c.published >= 0" : "\n AND c.published = 1";
			$query = "SELECT c.id"
			. "\n FROM #__categories AS c"
			. ' LEFT JOIN #__categories AS p ON p.id=c.parent_id'
			. ' LEFT JOIN #__categories AS gp ON gp.id=p.parent_id '
			. ' LEFT JOIN #__categories AS ggp ON ggp.id=gp.parent_id '
			. "\n WHERE c.access <= $aid"
			. $q_published
			. "\n AND c.section = '".$sectionname."'"
			. "\n " . $where;
			;


			$db->setQuery($query);
			$catlist =  $db->loadResultArray();

			$instances[$index] = implode(',', array_merge(array(-1), $catlist));
		}
		return $instances[$index];
	}

	function getCategoryInfo($catids=null,$aid=null){
		global $mainframe;
		$db	=& JFactory::getDBO();
		if (is_null($aid)) {
			$aid = $this->datamodel->aid;
		}
		if (is_null($catids)) {
			$catids = $this->datamodel->catids;
		}

		$catidList = implode(",", $catids);

		$cfg = & JEVConfig::getInstance();
		$sectionname = JEV_COM_COMPONENT;

		static $instances;

		if (!$instances) {
			$instances = array();
		}

		// calculate unique index identifier
		$index = $aid . '+' . $catidList;
		$where = null;

		if (!array_key_exists($index,$instances)) {
			if (count($catids)>0 && $catidList!="0" && strlen($catidList)!="") {
				$where = ' AND c.id IN (' . $catidList .') ';
			}

			$q_published = $mainframe->isAdmin() ? "\n AND c.published >= 0" : "\n AND c.published = 1";
			$query = "SELECT c.*"
			. "\n FROM #__categories AS c"
			. "\n WHERE c.access <= $aid"
			. $q_published
			. "\n AND c.section = '".$sectionname."'"
			. "\n " . $where;
			;

			$db->setQuery($query);
			$catlist =  $db->loadObjectList('id');

			$instances[$index] =  $catlist;
		}
		return $instances[$index];

	}

	function getChildCategories($catids=null,$levels=1,$aid=null){
		global $mainframe;
		$db	=& JFactory::getDBO();
		if (is_null($aid)) {
			$aid = $this->datamodel->aid;
		}
		if (is_null($catids)) {
			$catids = $this->datamodel->catids;
		}

		$catidList = implode(",", $catids);

		$cfg = & JEVConfig::getInstance();
		$sectionname = JEV_COM_COMPONENT;

		static $instances;

		if (!$instances) {
			$instances = array();
		}

		// calculate unique index identifier
		$index = $aid . '+' . $catidList;
		$where = null;

		if (!array_key_exists($index,$instances)) {
			if (count($catids)>0 && $catidList!="0"  && strlen($catidList)!="") {
				$where = ' AND (p.id IN (' . $catidList .') '.($levels>1?' OR gp.id IN (' . $catidList .')':'').($levels>2?' OR ggp.id IN (' . $catidList .')':'').')';
			}
			// TODO check if this should also check abncestry based on $levels
			$where .= ' AND p.id IS NOT NULL ';

			$q_published = $mainframe->isAdmin() ? "\n AND c.published >= 0" : "\n AND c.published = 1";
			$query = "SELECT c.*"
			. "\n FROM #__categories AS c"
			. ' LEFT JOIN #__categories AS p ON p.id=c.parent_id'
			. ($levels>1?' LEFT JOIN #__categories AS gp ON gp.id=p.parent_id ':'')
			. ($levels>2?' LEFT JOIN #__categories AS ggp ON ggp.id=gp.parent_id ':'')
			. "\n WHERE c.access <= $aid"
			. $q_published
			. "\n AND c.section = '".$sectionname."'"
			. "\n " . $where;
			;


			$db->setQuery($query);
			$catlist =  $db->loadObjectList('id');

			$instances[$index] =  $catlist;
		}
		return $instances[$index];

	}

	function listEvents( $startdate, $enddate, $order=""){
		if (!$this->legacyEvents) {
			return array();
		}
	}

	function _cachedlistEvents($query){
		$db	=& JFactory::getDBO();
		$db->setQuery( $query );
		$rows = $db->loadObjectList();
		$rowcount = count($rows);
		if ($rowcount>0) {
			usort( $rows, array('JEventsDBModel','sortEvents') );
		}

		for( $i = 0; $i < $rowcount; $i++ ){
			$rows[$i] = new jEventCal($rows[$i]);
		}
		return $rows;
	}

	// Allow the passing of filters directly into this function for use in 3rd party extensions etc.
	function listIcalEvents($startdate,$enddate, $order="", $filters = false, $extrafields="", $extratables="", $limit=""){
		$user =& JFactory::getUser();
		$db	=& JFactory::getDBO();
		$lang =& JFactory::getLanguage();
		$langtag = $lang->getTag();

		if (strpos($startdate,"-")===false) {
			$startdate = strftime('%Y-%m-%d 00:00:00',$startdate);
			$enddate = strftime('%Y-%m-%d 23:59:59',$enddate);
		}

		// process the new plugins
		// get extra data and conditionality from plugins
		$extrawhere =array();
		$extrajoin = array();

		if (!$filters){
			$filters = jevFilterProcessing::getInstance(array("published","justmine","category"));
			$filters->setWhereJoin($extrawhere,$extrajoin);

			$extrafields = "";  // must have comma prefix
			$extratables = "";  // must have comma prefix

			$dispatcher	=& JDispatcher::getInstance();
			$dispatcher->trigger('onListIcalEvents', array (& $extrafields, & $extratables, & $extrawhere, & $extrajoin));
		}
		else {
			$filters->setWhereJoin($extrawhere,$extrajoin);
		}
		$extrajoin = ( count( $extrajoin  ) ?  " \n LEFT JOIN ". implode( " \n LEFT JOIN ", $extrajoin ) : '' );
		$extrawhere = ( count( $extrawhere ) ? ' AND '. implode( ' AND ', $extrawhere ) : '' );

		// This version picks the details from the details table
		$query = "SELECT ev.*, rpt.*, rr.*, det.*, ev.state as published $extrafields"
		. "\n , YEAR(rpt.startrepeat) as yup, MONTH(rpt.startrepeat ) as mup, DAYOFMONTH(rpt.startrepeat ) as dup"
		. "\n , YEAR(rpt.endrepeat  ) as ydn, MONTH(rpt.endrepeat   ) as mdn, DAYOFMONTH(rpt.endrepeat   ) as ddn"
		. "\n , HOUR(rpt.startrepeat) as hup, MINUTE(rpt.startrepeat ) as minup, SECOND(rpt.startrepeat ) as sup"
		. "\n , HOUR(rpt.endrepeat  ) as hdn, MINUTE(rpt.endrepeat   ) as mindn, SECOND(rpt.endrepeat   ) as sdn"
		. "\n FROM #__jevents_vevent as ev"
		. "\n LEFT JOIN #__jevents_icsfile as icsf ON icsf.ics_id=ev.icsid "
		. "\n LEFT JOIN #__jevents_repetition as rpt ON rpt.eventid = ev.ev_id"
		. "\n LEFT JOIN #__jevents_vevdetail as det ON det.evdet_id = rpt.eventdetail_id"
		. "\n LEFT JOIN #__jevents_rrule as rr ON rr.eventid = ev.ev_id"
		. $extrajoin
		. "\n WHERE ev.catid IN(".$this->accessibleCategoryList().")"
		. "\n AND ((rpt.startrepeat >= '$startdate%' AND rpt.startrepeat <= '$enddate%')"
		. "\n OR (rpt.endrepeat >= '$startdate%' AND rpt.endrepeat <= '$enddate%')"
		. "\n OR (rpt.startrepeat >= '$startdate%' AND rpt.endrepeat <= '$enddate%')"
		. "\n OR (rpt.startrepeat <= '$startdate%' AND rpt.endrepeat >= '$enddate%'))"
		. $extrawhere
		. "\n AND ev.access <= ".$user->aid
		. "  AND icsf.state=1 AND icsf.access <= ".$user->aid
		// published state is not handled by filter
		//. "\n AND ev.state=1"
		. "\n GROUP BY rpt.rp_id";

		if ($order !="") {
			$query .= " ORDER BY ".$order;
		}
		if ($limit !="") {
			$query .= " LIMIT ".$limit;
		}

		$cache=& JFactory::getCache(JEV_COM_COMPONENT);
		return $cache->call('JEventsDBModel::_cachedlistIcalEvents', $query, $langtag );
	}

	function _cachedlistIcalEvents($query){
		$db	=& JFactory::getDBO();
		$db->setQuery( $query );
		//echo $db->explain();
		//echo $db->_sql;

		$icalrows = $db->loadObjectList();

		$icalcount = count($icalrows);
		for( $i = 0; $i < $icalcount ; $i++ ){
			// convert rows to jIcalEvents
			$icalrows[$i] = new jIcalEventRepeat($icalrows[$i]);
		}
		return $icalrows;
	}

	function listEventsByDateNEW( $select_date ){
		return $this->listEvents($select_date." 00:00:00",$select_date." 23:59:59");
	}

	function listIcalEventsByDay($targetdate){
		// targetdate is midnight at start of day - but just in case
		list ($y,$m,$d) =	explode(":",strftime( '%Y:%m:%d',$targetdate));
		$startdate 	= mktime( 0, 0, 0, $m, $d, $y );
		$enddate 	= mktime( 23, 59, 59, $m, $d, $y );
		return $this->listIcalEvents($startdate,$enddate);
	}

	function listEventsByWeekNEW( $weekstart, $weekend){
		return $this->listEvents($weekstart, $weekend);
	}

	function listIcalEventsByWeek( $weekstart, $weekend){
		return $this->listIcalEvents( $weekstart, $weekend);
	}

	function listEventsByMonthNew( $year, $month, $order){
		$db	=& JFactory::getDBO();

		$month = str_pad($month, 2, '0', STR_PAD_LEFT);
		$select_date 		= $year.'-'.$month.'-01 00:00:00';
		$select_date_fin 	= $year.'-'.$month.'-'.date('t',mktime(0,0,0,($month+1),0,$year)).' 23:59:59';

		return $this->listEvents($select_date,$select_date_fin,$order);
	}

	function listIcalEventsByMonth( $year, $month){
		$startdate 	= mktime( 0, 0, 0,  $month,  1, $year );
		$enddate 	= mktime( 23, 59, 59,  $month, date( 't', $startdate), $year );
		return $this->listIcalEvents($startdate,$enddate,"");
	}

	function listEventsByYearNEW( $year, $limitstart=0, $limit=0 ) {
		if (!$this->legacyEvents) {
			return array();
		}
	}

	// Allow the passing of filters directly into this function for use in 3rd party extensions etc.
	function listIcalEventsByYear( $year, $limitstart, $limit, $showrepeats = true, $order="", $filters = false, $extrafields="", $extratables="") {
		$startdate 	= mktime( 0, 0, 0, 1, 1, $year );
		$enddate 	= mktime( 23, 59, 59, 12, 31, $year );
		$order = "rpt.startrepeat asc";

		$user =& JFactory::getUser();
		$db	=& JFactory::getDBO();
		$lang =& JFactory::getLanguage();
		$langtag = $lang->getTag();

		if (strpos($startdate,"-")===false) {
			$startdate = strftime('%Y-%m-%d 00:00:00',$startdate);
			$enddate = strftime('%Y-%m-%d 23:59:59',$enddate);
		}

		// process the new plugins
		// get extra data and conditionality from plugins
		$extrawhere =array();
		$extrajoin = array();

		if (!$filters){
			$filters = jevFilterProcessing::getInstance(array("published","justmine","category"));
			$filters->setWhereJoin($extrawhere,$extrajoin);

			$extrafields = "";  // must have comma prefix
			$extratables = "";  // must have comma prefix

			$dispatcher	=& JDispatcher::getInstance();
			$dispatcher->trigger('onListIcalEvents', array (& $extrafields, & $extratables, & $extrawhere, & $extrajoin));
		}
		else {
			$filters->setWhereJoin($extrawhere,$extrajoin);
		}
		$extrajoin = ( count( $extrajoin  ) ?  " \n LEFT JOIN ". implode( " \n LEFT JOIN ", $extrajoin ) : '' );
		$extrawhere = ( count( $extrawhere ) ? ' AND '. implode( ' AND ', $extrawhere ) : '' );

		// This version picks the details from the details table
		$query = "SELECT ev.*, rpt.*, rr.*, det.*, ev.state as published $extrafields"
		. "\n , YEAR(rpt.startrepeat) as yup, MONTH(rpt.startrepeat ) as mup, DAYOFMONTH(rpt.startrepeat ) as dup"
		. "\n , YEAR(rpt.endrepeat  ) as ydn, MONTH(rpt.endrepeat   ) as mdn, DAYOFMONTH(rpt.endrepeat   ) as ddn"
		. "\n , HOUR(rpt.startrepeat) as hup, MINUTE(rpt.startrepeat ) as minup, SECOND(rpt.startrepeat ) as sup"
		. "\n , HOUR(rpt.endrepeat  ) as hdn, MINUTE(rpt.endrepeat   ) as mindn, SECOND(rpt.endrepeat   ) as sdn"
		. "\n FROM #__jevents_vevent as ev"
		. "\n LEFT JOIN #__jevents_icsfile as icsf ON icsf.ics_id=ev.icsid "
		. "\n LEFT JOIN #__jevents_repetition as rpt ON rpt.eventid = ev.ev_id"
		. "\n LEFT JOIN #__jevents_vevdetail as det ON det.evdet_id = rpt.eventdetail_id"
		. "\n LEFT JOIN #__jevents_rrule as rr ON rr.eventid = ev.ev_id"
		. $extrajoin
		. "\n WHERE ev.catid IN(".$this->accessibleCategoryList().")"
		. "\n AND ((rpt.startrepeat >= '$startdate%' AND rpt.startrepeat <= '$enddate%')"
		. "\n OR (rpt.endrepeat >= '$startdate%' AND rpt.endrepeat <= '$enddate%')"
		. "\n OR (rpt.startrepeat >= '$startdate%' AND rpt.endrepeat <= '$enddate%')"
		. "\n OR (rpt.startrepeat <= '$startdate%' AND rpt.endrepeat >= '$enddate%'))"
		. $extrawhere
		. "\n AND ev.access <= ".$user->aid
		. "  AND icsf.state=1 AND icsf.access <= ".$user->aid
		// published state is not handled by filter
		//. "\n AND ev.state=1"
		. ($showrepeats?"\n GROUP BY rpt.rp_id":"\n GROUP BY ev.ev_id");

		if ($order !="") {
			$query .= " ORDER BY ".$order;
		}
		if ($limit !="" && $limit!=0) {
			$query .= " LIMIT ".($limitstart!=""?$limitstart.",":"").$limit;
		}

		$cache=& JFactory::getCache(JEV_COM_COMPONENT);
		return $cache->call('JEventsDBModel::_cachedlistIcalEvents', $query, $langtag );
	}
	
	function countIcalEventsByYear( $year,$showrepeats = true) {
		$startdate 	= mktime( 0, 0, 0, 1, 1, $year );
		$enddate 	= mktime( 23, 59, 59, 12, 31, $year );
		return count($this->listIcalEventsByYear($year,"","",$showrepeats));
	}

	function listEventsById( $evid, $includeUnpublished=0, $jevtype="unspecified" ) {
		$user =& JFactory::getUser();
		$db	=& JFactory::getDBO();

		$frontendPublish = JEVHelper::isEventPublisher();
		
		if ($jevtype=="jevent"){
			if( $frontendPublish && $includeUnpublished ){
				$query = "SELECT *"
				. "\n FROM #__events"
				. "\n WHERE #__events.catid IN(".$this->accessibleCategoryList().")"
				. "\n AND #__events.access <= ".$user->aid
				. "\n AND #__events.id = '$evid'"
				;
			}else{
				$query = "SELECT *"
				. "\n FROM #__events"
				. "\n WHERE #__events.catid IN(".$this->accessibleCategoryList().")"
				. "\n AND #__events.access <= ".$user->aid
				. "\n AND #__events.id = '$evid'"
				. "\n AND #__events.state = '1'"
				;
			}
		}
		else if ($jevtype=="icaldb"){
			// process the new plugins
			// get extra data and conditionality from plugins
			$extrafields = "";  // must have comma prefix
			$extratables = "";  // must have comma prefix
			$extrawhere =array();
			$extrajoin = array();
			$dispatcher	=& JDispatcher::getInstance();
			$dispatcher->trigger('onListEventsById', array (& $extrafields, & $extratables, & $extrawhere, & $extrajoin));
			$extrajoin = ( count( $extrajoin  ) ?  " \n LEFT JOIN ".implode( " \n LEFT JOIN ", $extrajoin ) : '' );
			$extrawhere = ( count( $extrawhere ) ? ' AND '. implode( ' AND ', $extrawhere ) : '' );

			$query = "SELECT ev.*, ev.state as published, rpt.*, rr.*, det.* $extrafields"
			. "\n , YEAR(rpt.startrepeat) as yup, MONTH(rpt.startrepeat ) as mup, DAYOFMONTH(rpt.startrepeat ) as dup"
			. "\n , YEAR(rpt.endrepeat  ) as ydn, MONTH(rpt.endrepeat   ) as mdn, DAYOFMONTH(rpt.endrepeat   ) as ddn"
			. "\n , HOUR(rpt.startrepeat) as hup, MINUTE(rpt.startrepeat ) as minup, SECOND(rpt.startrepeat ) as sup"
			. "\n , HOUR(rpt.endrepeat  ) as hdn, MINUTE(rpt.endrepeat   ) as mindn, SECOND(rpt.endrepeat   ) as sdn"
			. "\n FROM (#__jevents_vevent as ev $extratables)"
			. "\n LEFT JOIN #__jevents_repetition as rpt ON rpt.eventid = ev.ev_id"
			. "\n LEFT JOIN #__jevents_vevdetail as det ON det.evdet_id = rpt.eventdetail_id"
			. "\n LEFT JOIN #__jevents_rrule as rr ON rr.eventid = ev.ev_id"
			. $extrajoin
			. "\n WHERE ev.catid IN(".$this->accessibleCategoryList().")"
			. $extrawhere
			. "\n AND rpt.rp_id = '$evid'";
		}
		else {
			die("invalid jevtype in listEventsById - more changes needed");
		}

		$db->setQuery( $query );
		//echo $db->_sql;
		$rows = $db->loadObjectList();

		// iCal agid uses GUID or UUID as identifier
		if( $rows ){
			if (strtolower($jevtype)=="icaldb"){
				$row = new jIcalEventRepeat($rows[0]);
			}
			else if (strtolower($jevtype)=="jevent"){
				$row = new jEventCal($rows[0]);
			}
		}else{
			$row=null;
		}

		return $row;
	}

	/**
	 * Get Event by ID (not repeat Id) result is based on first repeat
	 *
	 * @param event_id $evid
	 * @param boolean $includeUnpublished
	 * @param string $jevtype
	 * @return jeventcal (or desencent)
	 */
	function getEventById( $evid, $includeUnpublished=0, $jevtype="unspecified" ) {
		$user =& JFactory::getUser();
		$db	=& JFactory::getDBO();

		$frontendPublish = JEVHelper::isEventPublisher();

		if ($jevtype=="jevent"){
			if( $frontendPublish && $includeUnpublished ){
				$query = "SELECT *"
				. "\n FROM #__events"
				. "\n WHERE #__events.catid IN(".$this->accessibleCategoryList().")"
				. "\n AND #__events.access <= ".$user->aid
				. "\n AND #__events.id = '$evid'"
				;
			}else{
				$query = "SELECT *"
				. "\n FROM #__events"
				. "\n WHERE #__events.catid IN(".$this->accessibleCategoryList().")"
				. "\n AND #__events.access <= ".$user->aid
				. "\n AND #__events.id = '$evid'"
				. "\n AND #__events.state = '1'"
				;
			}
		}
		else if ($jevtype=="icaldb"){
			// process the new plugins
			// get extra data and conditionality from plugins
			$extrafields = "";  // must have comma prefix
			$extratables = "";  // must have comma prefix
			$extrawhere ="";
			$extrajoin = "";
			$dispatcher	=& JDispatcher::getInstance();
			$dispatcher->trigger('onListEventsById', array (& $extrafields, & $extratables, & $extrawhere, & $extrajoin));

			$query = "SELECT ev.*, rpt.*, rr.*, det.* $extrafields"
			. "\n , YEAR(rpt.startrepeat) as yup, MONTH(rpt.startrepeat ) as mup, DAYOFMONTH(rpt.startrepeat ) as dup"
			. "\n , YEAR(rpt.endrepeat  ) as ydn, MONTH(rpt.endrepeat   ) as mdn, DAYOFMONTH(rpt.endrepeat   ) as ddn"
			. "\n , HOUR(rpt.startrepeat) as hup, MINUTE(rpt.startrepeat ) as minup, SECOND(rpt.startrepeat ) as sup"
			. "\n , HOUR(rpt.endrepeat  ) as hdn, MINUTE(rpt.endrepeat   ) as mindn, SECOND(rpt.endrepeat   ) as sdn"
			. "\n FROM (#__jevents_vevent as ev $extratables)"
			. "\n LEFT JOIN #__jevents_repetition as rpt ON rpt.eventid = ev.ev_id"
			. "\n LEFT JOIN #__jevents_vevdetail as det ON det.evdet_id = rpt.eventdetail_id"
			. "\n LEFT JOIN #__jevents_rrule as rr ON rr.eventid = ev.ev_id"
			. $extrajoin
			. "\n WHERE ev.catid IN(".$this->accessibleCategoryList().")"
			. $extrawhere
			. "\n AND ev.ev_id = '$evid'"
			. "\n LIMIT 1";
		}
		else {
			die("invalid jevtype in listEventsById - more changes needed");
		}

		$db->setQuery( $query );
		//echo $db->_sql;
		$rows = $db->loadObjectList();

		// iCal agid uses GUID or UUID as identifier
		if( $rows ){
			if (strtolower($jevtype)=="icaldb"){
				$row = new jIcalEventRepeat($rows[0]);
			}
			else if (strtolower($jevtype)=="jevent"){
				$row = new jEventCal($rows[0]);
			}
		}else{
			$row=null;
		}

		return $row;
	}

	function listEventsByCreator( $creator_id, $limitstart, $limit ){
		if (!$this->legacyEvents) {
			return array();
		}
	}

	function listIcalEventsByCreator ( $creator_id, $limitstart, $limit ){
		$user =& JFactory::getUser();
		$db	=& JFactory::getDBO();

		$cfg = & JEVConfig::getInstance();

		$rows_per_page = $limit;

		if( empty( $limitstart) || !$limitstart ){
			$limitstart = 0;
		}

		$limit = "LIMIT $limitstart, $rows_per_page";

		$frontendPublish = JEVHelper::isEventPublisher();

		$adminCats = JEVHelper::categoryAdmin();
		
		$where = '';	
		if( $creator_id == 'ADMIN' ){
			$where = "";
		}
		else if ( $adminCats && count($adminCats)>0){
			//$adminCats = " OR (ev.state=0 AND ev.catid IN(".implode(",",$adminCats)."))";
			$adminCats = " OR ev.catid IN(".implode(",",$adminCats).")";
			$where = " AND ( ev.created_by = ".$user->id. $adminCats. ")";
		}
		else {
			$where = " AND ev.created_by = '$creator_id' ";
		}

		// State is manged by plugin
		/*		
		$state = "\n AND ev.state=1";
		if ($frontendPublish){
			$state = "";
		}
		*/
		
		$extrawhere =array();
		$extrajoin = array();
		$filters = jevFilterProcessing::getInstance(array("published","justmine","category","startdate"));
		$filters->setWhereJoin($extrawhere,$extrajoin);
		$extrajoin = ( count( $extrajoin  ) ?  " \n LEFT JOIN ". implode( " \n LEFT JOIN ", $extrajoin ) : '' );
		$extrawhere = ( count( $extrawhere ) ? ' AND '. implode( ' AND ', $extrawhere ) : '' );
		
		$query = "SELECT ev.*, rr.*, det.*, ev.state as published, count(rpt.rp_id) as rptcount"
		. "\n , YEAR(dtstart) as yup, MONTH(dtstart ) as mup, DAYOFMONTH(dtstart ) as dup"
		. "\n , YEAR(dtend  ) as ydn, MONTH(dtend   ) as mdn, DAYOFMONTH(dtend   ) as ddn"
		. "\n , HOUR(dtstart) as hup, MINUTE(dtstart) as minup, SECOND(dtstart   ) as sup"
		. "\n , HOUR(dtend  ) as hdn, MINUTE(dtend  ) as mindn, SECOND(dtend     ) as sdn"
		. "\n FROM #__jevents_vevent as ev"
		. "\n LEFT JOIN #__jevents_icsfile as icsf ON icsf.ics_id=ev.icsid"
		. "\n LEFT JOIN #__jevents_repetition as rpt ON rpt.eventid = ev.ev_id"
		. "\n LEFT JOIN #__jevents_rrule as rr ON rr.eventid = ev.ev_id"
		. "\n LEFT JOIN #__jevents_vevdetail as det ON det.evdet_id = ev.detail_id"
		. $extrajoin
		. "\n WHERE ev.catid IN(".$this->accessibleCategoryList().")"
		. $extrawhere
		. $where
		. "\n AND icsf.state=1"
		. "\n GROUP BY ev.ev_id"
		. "\n ORDER BY dtstart ASC"
		. "\n $limit";

		$db->setQuery( $query );
		$icalrows = $db->loadObjectList();
		echo $db->getErrorMsg();
		$icalcount = count($icalrows);
		for( $i = 0; $i < $icalcount ; $i++ ){
			// convert rows to jIcalEvents
			$icalrows[$i] = new jIcalEventDB($icalrows[$i]);
		}
		return $icalrows;
	}

	function listIcalEventRepeatsByCreator ( $creator_id, $limitstart, $limit ){
		$user =& JFactory::getUser();
		$db	=& JFactory::getDBO();

		$cfg = & JEVConfig::getInstance();

		$rows_per_page = $limit;

		if( empty( $limitstart) || !$limitstart ){
			$limitstart = 0;
		}

		$limit = "LIMIT $limitstart, $rows_per_page";

		$where = '';

		if( $creator_id <> 'ADMIN' ){
			$where = " AND created_by = '$creator_id' ";
		}

		$frontendPublish = JEVHelper::isEventPublisher();

		if( $frontendPublish ){
			// TODO fine a single query way of doing this !!!
			$query = "SELECT MIN(rpt.rp_id) as rp_id"
			. "\n FROM #__jevents_vevent as ev "
			. "\n LEFT JOIN #__jevents_icsfile as icsf ON icsf.ics_id=ev.icsid"
			. "\n LEFT JOIN #__jevents_repetition as rpt ON rpt.eventid = ev.ev_id"
			. "\n LEFT JOIN #__jevents_rrule as rr ON rr.eventid = ev.ev_id"
			. "\n WHERE ev.catid IN(".$this->accessibleCategoryList().")"
			//		. "\n AND ev.created_by = ".$user->id
			. "\n  AND icsf.state=1"
			. "\n GROUP BY ev.ev_id";

			$db->setQuery( $query );
			$rplist =  $db->loadResultArray();

			$rplist = implode(',', array_merge(array(-1), $rplist));

			$query = "SELECT ev.*, rpt.*, rr.*, det.*"
			. "\n , YEAR(rpt.startrepeat) as yup, MONTH(rpt.startrepeat ) as mup, DAYOFMONTH(rpt.startrepeat ) as dup"
			. "\n , YEAR(rpt.endrepeat  ) as ydn, MONTH(rpt.endrepeat   ) as mdn, DAYOFMONTH(rpt.endrepeat   ) as ddn"
			. "\n , HOUR(rpt.startrepeat) as hup, MINUTE(rpt.startrepeat ) as minup, SECOND(rpt.startrepeat ) as sup"
			. "\n , HOUR(rpt.endrepeat  ) as hdn, MINUTE(rpt.endrepeat   ) as mindn, SECOND(rpt.endrepeat   ) as sdn"
			. "\n FROM #__jevents_vevent as ev "
			. "\n LEFT JOIN #__jevents_icsfile as icsf ON icsf.ics_id=ev.icsid"
			. "\n LEFT JOIN #__jevents_repetition as rpt ON rpt.eventid = ev.ev_id"
			. "\n AND rpt.eventid = ev.ev_id"
			. "\n AND rpt.rp_id IN($rplist)"
			. "\n LEFT JOIN #__jevents_rrule as rr ON rr.eventid = ev.ev_id"
			. "\n LEFT JOIN #__jevents_vevdetail as det ON det.evdet_id = rpt.eventdetail_id"
			. "\n WHERE ev.catid IN(".$this->accessibleCategoryList().")"
			//	. "\n AND ev.created_by = ".$user->id
			. "\n  AND icsf.state=1"
			. "\n GROUP BY rpt.rp_id";

		}
		else {
			// TODO fine a single query way of doing this !!!
			$query = "SELECT MIN(rpt.rp_id) as rp_id"
			. "\n FROM #__jevents_vevent as ev "
			. "\n LEFT JOIN #__jevents_icsfile as icsf ON icsf.ics_id=ev.icsid"
			. "\n LEFT JOIN #__jevents_repetition as rpt ON rpt.eventid = ev.ev_id"
			. "\n LEFT JOIN #__jevents_rrule as rr ON rr.eventid = ev.ev_id"
			. "\n WHERE ev.catid IN(".$this->accessibleCategoryList().")"
			. "\n AND ev.state=1"
			. "\n AND icsf.state=1"
			. "\n AND ev.created_by = ".$user->id
			. "\n GROUP BY ev.ev_id";

			$db->setQuery( $query );
			$rplist =  $db->loadResultArray();

			$rplist = implode(',', array_merge(array(-1), $rplist));

			$query = "SELECT ev.*, rpt.*, rr.*, det.*"
			. "\n , YEAR(rpt.startrepeat) as yup, MONTH(rpt.startrepeat ) as mup, DAYOFMONTH(rpt.startrepeat ) as dup"
			. "\n , YEAR(rpt.endrepeat  ) as ydn, MONTH(rpt.endrepeat   ) as mdn, DAYOFMONTH(rpt.endrepeat   ) as ddn"
			. "\n , HOUR(rpt.startrepeat) as hup, MINUTE(rpt.startrepeat ) as minup, SECOND(rpt.startrepeat ) as sup"
			. "\n , HOUR(rpt.endrepeat  ) as hdn, MINUTE(rpt.endrepeat   ) as mindn, SECOND(rpt.endrepeat   ) as sdn"
			. "\n LEFT JOIN #__jevents_icsfile as icsf ON icsf.ics_id=ev.icsid"
			. "\n LEFT JOIN #__jevents_repetition as rpt ON rpt.eventid = ev.ev_id"
			. "\n AND rpt.rp_id IN($rplist)"
			. "\n LEFT JOIN #__jevents_rrule as rr ON rr.eventid = ev.ev_id"
			. "\n LEFT JOIN #__jevents_rrule as rr ON rr.eventid = ev.ev_id"
			. "\n LEFT JOIN #__jevents_vevdetail as det ON det.evdet_id = rpt.eventdetail_id"
			. "\n WHERE ev.catid IN(".$this->accessibleCategoryList().")"
			. "\n AND ev.created_by = ".$user->id
			. "\n AND ev.state=1"
			. "\n AND icsf.state=1"
			. "\n GROUP BY rpt.rp_id";

		}
		$db->setQuery( $query );
		$icalrows = $db->loadObjectList();
		$icalcount = count($icalrows);
		for( $i = 0; $i < $icalcount ; $i++ ){
			// convert rows to jIcalEvents
			$icalrows[$i] = new jIcalEventDB($icalrows[$i]);
		}
		return $icalrows;
	}

	function countEventsByCreator($creator_id){
		if (!$this->legacyEvents) {
			return 0;
		}
	}

	function countIcalEventsByCreator($creator_id){
		$user =& JFactory::getUser();
		$db	=& JFactory::getDBO();

		$adminCats = JEVHelper::categoryAdmin();
		$where = '';	
		if( $creator_id == 'ADMIN' ){
			$where = "";
		}
		else if ( $adminCats && count($adminCats)>0){
			//$adminCats = " OR (ev.state=0 AND ev.catid IN(".implode(",",$adminCats)."))";
			$adminCats = " OR ev.catid IN(".implode(",",$adminCats).")";
			$where = " AND ( ev.created_by = ".$user->id. $adminCats. ")";
		}
		else {
			$where = " AND ev.created_by = '$creator_id' ";
		}

		// State is managed by plugin
		/*
		$frontendPublish = JEVHelper::isEventPublisher();
		$state = "\n AND ev.state=1";
		if ($frontendPublish){
			$state = "";
		}
		*/

		$extrawhere =array();
		$extrajoin = array();
		$filters = jevFilterProcessing::getInstance(array("published","justmine","category","startdate"));
		$filters->setWhereJoin($extrawhere,$extrajoin);
		$extrajoin = ( count( $extrajoin  ) ?  " \n LEFT JOIN ". implode( " \n LEFT JOIN ", $extrajoin ) : '' );
		$extrawhere = ( count( $extrawhere ) ? ' AND '. implode( ' AND ', $extrawhere ) : '' );
		
		$query = "SELECT MIN(rpt.rp_id) as rp_id"
		. "\n FROM #__jevents_vevent as ev "
		. "\n LEFT JOIN #__jevents_icsfile as icsf ON icsf.ics_id=ev.icsid"
		. "\n LEFT JOIN #__jevents_repetition as rpt ON rpt.eventid = ev.ev_id"
		. "\n LEFT JOIN #__jevents_rrule as rr ON rr.eventid = ev.ev_id"
		. $extrajoin
		. "\n WHERE ev.catid IN(".$this->accessibleCategoryList().")"
		. $extrawhere
		. $where
		. "\n AND icsf.state=1"
		. "\n GROUP BY ev.ev_id";

		$db->setQuery( $query );
		$rplist =  $db->loadResultArray();
		return count($rplist);

	}

	function listEventsByCat( $catids, $limitstart, $limit ){
		if (!$this->legacyEvents) {
			return array();
		}
	}

	// Allow the passing of filters directly into this function for use in 3rd party extensions etc.
	function listIcalEventsByCat ($catids, $showrepeats = false, $total=0, $limitstart=0, $limit=0, $order=" ORDER BY rpt.startrepeat asc", $filters = false, $extrafields="", $extratables="") {
		$db	=& JFactory::getDBO();

		// Use catid in accessibleCategoryList to pick up offsping too!
		$aid = null;
		$catidlist = implode(",",$catids);
				
		// process the new plugins
		// get extra data and conditionality from plugins
		$extrafields = "";  // must have comma prefix
		$extratables = "";  // must have comma prefix
		$extrawhere =array();
		$extrajoin = array();

		if (!$filters){
			$filters = jevFilterProcessing::getInstance(array("published","justmine","category"));
			$filters->setWhereJoin($extrawhere,$extrajoin);

			$extrafields = "";  // must have comma prefix
			$extratables = "";  // must have comma prefix

			$dispatcher	=& JDispatcher::getInstance();
			$dispatcher->trigger('onListIcalEvents', array (& $extrafields, & $extratables, & $extrawhere, & $extrajoin));
		}
		else {
			$filters->setWhereJoin($extrawhere,$extrajoin);
		}
		$extrajoin = ( count( $extrajoin  ) ?  " \n LEFT JOIN ". implode( " \n LEFT JOIN ", $extrajoin ) : '' );
		$extrawhere = ( count( $extrawhere ) ? ' AND '. implode( ' AND ', $extrawhere ) : '' );

		if ($limit>0 || $limitstart>0){
			if( empty( $limitstart) || !$limitstart ){
				$limitstart = 0;
			}

			$rows_per_page = $limit;
			$limit = " LIMIT $limitstart, $rows_per_page";
		}
		else {
			$limit = "";
		}

		if ($showrepeats){
			$query = "SELECT ev.*, rpt.*, rr.*, det.* $extrafields"
			. "\n , YEAR(rpt.startrepeat) as yup, MONTH(rpt.startrepeat ) as mup, DAYOFMONTH(rpt.startrepeat ) as dup"
			. "\n , YEAR(rpt.endrepeat  ) as ydn, MONTH(rpt.endrepeat   ) as mdn, DAYOFMONTH(rpt.endrepeat   ) as ddn"
			. "\n , HOUR(rpt.startrepeat) as hup, MINUTE(rpt.startrepeat ) as minup, SECOND(rpt.startrepeat ) as sup"
			. "\n , HOUR(rpt.endrepeat  ) as hdn, MINUTE(rpt.endrepeat   ) as mindn, SECOND(rpt.endrepeat   ) as sdn"
			. "\n FROM #__jevents_vevent as ev"
			. "\n LEFT JOIN #__jevents_icsfile as icsf ON icsf.ics_id=ev.icsid"
			. "\n LEFT JOIN #__jevents_repetition as rpt ON rpt.eventid = ev.ev_id"
			. "\n LEFT JOIN #__jevents_rrule as rr ON rr.eventid = ev.ev_id"
			. "\n LEFT JOIN #__jevents_vevdetail as det ON det.evdet_id = rpt.eventdetail_id"
			. $extrajoin
			//. "\n WHERE ev.catid IN(".$this->accessibleCategoryList($aid,$catids,$catidlist).")"
			. "\n WHERE ev.catid IN(".$this->accessibleCategoryList().")"
			. $extrawhere
			//. "\n AND ev.state=1"
			. "\n  AND icsf.state=1"
			. "\n GROUP BY rpt.rp_id"
			. $order
			. $limit;
		}
		else {
			// TODO fine a single query way of doing this !!!
			$query = "SELECT MIN(rpt.rp_id) as rp_id FROM #__jevents_repetition as rpt "
			. "\n LEFT JOIN #__jevents_vevent as ev ON rpt.eventid = ev.ev_id"
			. "\n LEFT JOIN #__jevents_rrule as rr ON rr.eventid = ev.ev_id"
			. "\n LEFT JOIN #__jevents_vevdetail as det ON det.evdet_id = rpt.eventdetail_id"
			. "\n LEFT JOIN #__jevents_icsfile as icsf  ON icsf.ics_id=ev.icsid"
			. $extrajoin
			//. "\n WHERE ev.catid IN(".$this->accessibleCategoryList($aid,$catids,$catidlist).")"
			. "\n WHERE ev.catid IN(".$this->accessibleCategoryList().")"
			. $extrawhere
			//. "\n AND ev.state=1"
			. "\n  AND icsf.state=1"
			. "\n GROUP BY ev.ev_Id";

			$db->setQuery( $query );

			$rplist =  $db->loadResultArray();

			$rplist = implode(',', array_merge(array(-1), $rplist));

			$query = "SELECT ev.*, rpt.*, rr.*, det.* $extrafields"
			. "\n , YEAR(rpt.startrepeat) as yup, MONTH(rpt.startrepeat ) as mup, DAYOFMONTH(rpt.startrepeat ) as dup"
			. "\n , YEAR(rpt.endrepeat  ) as ydn, MONTH(rpt.endrepeat   ) as mdn, DAYOFMONTH(rpt.endrepeat   ) as ddn"
			. "\n , HOUR(rpt.startrepeat) as hup, MINUTE(rpt.startrepeat ) as minup, SECOND(rpt.startrepeat ) as sup"
			. "\n , HOUR(rpt.endrepeat  ) as hdn, MINUTE(rpt.endrepeat   ) as mindn, SECOND(rpt.endrepeat   ) as sdn"
			. "\n FROM #__jevents_vevent as ev "
			. "\n LEFT JOIN #__jevents_icsfile as icsf ON icsf.ics_id=ev.icsid"
			. "\n LEFT JOIN #__jevents_repetition as rpt ON rpt.eventid = ev.ev_id"
			. "\n AND rpt.rp_id IN($rplist)"
			. "\n LEFT JOIN #__jevents_rrule as rr ON rr.eventid = ev.ev_id"
			. "\n LEFT JOIN #__jevents_vevdetail as det ON det.evdet_id = rpt.eventdetail_id"
			. $extrajoin
			//. "\n WHERE ev.catid IN(".$this->accessibleCategoryList($aid,$catids,$catidlist).")"
			. "\n WHERE ev.catid IN(".$this->accessibleCategoryList().")"
			. $extrawhere
			//. "\n AND ev.state=1"
			. "\n  AND icsf.state=1"
			. "\n GROUP BY rpt.rp_id"
			. $order
			. $limit;
		}

		$db->setQuery( $query );
		$icalrows = $db->loadObjectList();
		//echo $db->explain();
		//echo $db->_sql;
		$icalcount = count($icalrows);
		for( $i = 0; $i < $icalcount ; $i++ ){
			// convert rows to jIcalEvents
			$icalrows[$i] = new jIcalEventRepeat($icalrows[$i]);
		}
		return $icalrows;

	}

	function countEventsByCat( $catid){
		$user =& JFactory::getUser();
		$aid = intval( $user->aid );
		if( !$catid ){
			// no category selected
			$query = "SELECT *"
			. "\n FROM #__events"
			. "\n WHERE #__events.catid IN(".$this->accessibleCategoryList().")"
			. "\n AND #__events.access <= $aid"
			. "\n AND #__events.state = '1'"
			;
		}else {
			// category selected
			$query = "SELECT *"
			. "\n FROM #__events"
			. "\n WHERE #__events.catid IN(".$this->accessibleCategoryList().")"
			. "\n AND #__events.catid = '$catid'"
			. "\n AND #__events.access <= $aid"
			. "\n AND #__events.state = '1'"
			;
		}
		$db =& JFactory::getDBO();
		$db->setQuery( $query );
		$counter	= $db->loadObjectList();
		return count($counter);
	}

	function countIcalEventsByCat( $catids, $showrepeats = false){
		$db	=& JFactory::getDBO();

		// Use catid in accessibleCategoryList to pick up offsping too!
		$aid = null;
		$catidlist = implode(",",$catids);

		// process the new plugins
		// get extra data and conditionality from plugins
		$extrafields = "";  // must have comma prefix
		$extratables = "";  // must have comma prefix
		$extrawhere =array();
		$extrajoin = array();

		$filters = jevFilterProcessing::getInstance(array("published","justmine","category"));
		$filters->setWhereJoin($extrawhere,$extrajoin);

		$extrafields = "";  // must have comma prefix
		$extratables = "";  // must have comma prefix

		$dispatcher	=& JDispatcher::getInstance();
		$dispatcher->trigger('onListIcalEvents', array (& $extrafields, & $extratables, & $extrawhere, & $extrajoin));

		$extrajoin = ( count( $extrajoin  ) ?  " \n LEFT JOIN ". implode( " \n LEFT JOIN ", $extrajoin ) : '' );
		$extrawhere = ( count( $extrawhere ) ? ' AND '. implode( ' AND ', $extrawhere ) : '' );

		// Get the count
		if ($showrepeats){
			$query = "SELECT count(det.evdet_id) as cnt"
			. "\n FROM #__jevents_vevent as ev "
			. "\n LEFT JOIN #__jevents_icsfile as icsf ON icsf.ics_id=ev.icsid"
			. "\n LEFT JOIN #__jevents_repetition as rpt ON rpt.eventid = ev.ev_id"
			. "\n LEFT JOIN #__jevents_rrule as rr ON rr.eventid = ev.ev_id"
			. "\n LEFT JOIN #__jevents_vevdetail as det ON det.evdet_id = rpt.eventdetail_id"
			. $extrajoin
			//. "\n WHERE ev.catid IN(".$this->accessibleCategoryList($aid,$catids,$catidlist).")"
			. "\n WHERE ev.catid IN(".$this->accessibleCategoryList().")"
			. "\n AND icsf.state=1"
			. $extrawhere;
			//. "\n AND ev.state=1"
		}
		else {
			// TODO fine a single query way of doing this !!!
			$query = "SELECT MIN(rpt.rp_id) as rp_id FROM #__jevents_repetition as rpt "
			. "\n LEFT JOIN #__jevents_vevent as ev ON rpt.eventid = ev.ev_id"
			. "\n LEFT JOIN #__jevents_rrule as rr ON rr.eventid = ev.ev_id"
			. "\n LEFT JOIN #__jevents_vevdetail as det ON det.evdet_id = rpt.eventdetail_id"
			. "\n LEFT JOIN #__jevents_icsfile as icsf  ON icsf.ics_id=ev.icsid "
			. $extrajoin
			//. "\n WHERE ev.catid IN(".$this->accessibleCategoryList($aid,$catids,$catidlist).")"
			. "\n WHERE ev.catid IN(".$this->accessibleCategoryList().")"
			. $extrawhere
			//. "\n AND ev.state=1"
			. "\n AND icsf.state=1"
			. "\n GROUP BY ev.ev_id";

			$db->setQuery( $query );

			$rplist =  $db->loadResultArray();

			$rplist = implode(',', array_merge(array(-1), $rplist));

			$query = "SELECT count(det.evdet_id) as cnt"
			. "\n FROM #__jevents_vevent as ev "
			. "\n LEFT JOIN #__jevents_icsfile as icsf ON icsf.ics_id=ev.icsid"
			. "\n LEFT JOIN #__jevents_repetition as rpt ON rpt.eventid = ev.ev_id"
			. "\n AND rpt.rp_id IN($rplist)"
			. "\n LEFT JOIN #__jevents_rrule as rr ON rr.eventid = ev.ev_id"
			. "\n LEFT JOIN #__jevents_vevdetail as det ON det.evdet_id = rpt.eventdetail_id"
			. $extrajoin
			//. "\n WHERE ev.catid IN(".$this->accessibleCategoryList($aid,$catids,$catidlist).")"
			. "\n WHERE ev.catid IN(".$this->accessibleCategoryList().")"
			. "\n AND icsf.state=1"
			. $extrawhere;
			//. "\n AND ev.state=1"
		}

		$db->setQuery( $query );
		//echo $db->_sql;
		$total =  intval($db->loadResult());
		return $total;
	}

	function listEventsByKeyword( $keyword, $order, &$limit, &$limitstart, &$total, $useRegX=false ){
		$user =& JFactory::getUser();
		$db	=& JFactory::getDBO();

		$rows_per_page = $limit;
		if( empty( $limitstart ) || !$limitstart ){
			$limitstart = 0;
		}

		$limitstring = "LIMIT $limitstart, $rows_per_page";

		if( !$order ){
			$order = 'publish_up';
		}

		$order 	= preg_replace( "/[\t ]+/", '', $order );
		$orders = explode( ",", $order );

		// this function adds #__events. to the beginning of each ordering field
		function app_db( $strng ){
			return '#__events.' . $strng;
		}

		$order = implode( ',', array_map( 'app_db', $orders ));

		$total = 0;

		// Now Search Icals
		$query = "SELECT count( distinct det.evdet_id)"
		. "\n FROM #__jevents_vevent as ev"
		. "\ LEFT JOIN #__jevents_icsfile as icsf ON AND icsf.ics_id=ev.icsid"
		. "\n LEFT JOIN #__jevents_repetition as rpt ON rpt.eventid = ev.ev_id"
		. "\n LEFT JOIN #__jevents_vevdetail as det ON det.evdet_id = rpt.eventdetail_id"
		. "\n WHERE ev.catid IN(".$this->accessibleCategoryList().")"
 		. "\n AND icsf.state=1 AND icsf.access <= ".$user->aid
 		. "\n AND\n";
		$query .= ( $useRegX ) ? "(det.summary RLIKE '$keyword' OR det.description RLIKE '$keyword')\n" :
		"MATCH (det.summary, det.description) AGAINST ('$keyword' IN BOOLEAN MODE)\n";
		$query .= "AND ev.state = '1'";
		$db->setQuery( $query );
		$total += intval($db->loadResult());

		if ($total<$limitstart){
			$limitstart = 0;
		}

		$rows = array();

		// Now Search Icals
		$query = "SELECT ev.*, rpt.*, det.*"
		. "\n , YEAR(rpt.startrepeat) as yup, MONTH(rpt.startrepeat ) as mup, DAYOFMONTH(rpt.startrepeat ) as dup"
		. "\n , YEAR(rpt.endrepeat  ) as ydn, MONTH(rpt.endrepeat   ) as mdn, DAYOFMONTH(rpt.endrepeat   ) as ddn"
		. "\n , HOUR(rpt.startrepeat) as hup, MINUTE(rpt.startrepeat ) as minup, SECOND(rpt.startrepeat ) as sup"
		. "\n , HOUR(rpt.endrepeat  ) as hdn, MINUTE(rpt.endrepeat   ) as mindn, SECOND(rpt.endrepeat   ) as sdn"
		. "\n FROM #__jevents_vevent as ev"
		. "\n LEFT JOIN #__jevents_icsfile as icsf ON icsf.ics_id=ev.icsid"
		. "\n LEFT JOIN #__jevents_repetition as rpt ON rpt.eventid = ev.ev_id"
		. "\n LEFT JOIN #__jevents_vevdetail as det ON det.evdet_id = rpt.eventdetail_id"
		. "\n WHERE ev.catid IN(".$this->accessibleCategoryList().")"
		. "\n  AND icsf.state=1 AND icsf.access <= ".$user->aid
		;

		$query .= " AND ";
		$query .= ( $useRegX ) ? "(det.summary RLIKE '$keyword' OR det.description RLIKE '$keyword')\n" :
		"MATCH (det.summary, det.description) AGAINST ('$keyword' IN BOOLEAN MODE)\n";
		$query .= "AND ev.state = '1'"
		;
		$query .= " \n GROUP BY det.evdet_id  $limitstring";

		$db->setQuery( $query );
		$icalrows = $db->loadObjectList();
		$num_events = count( $icalrows );

		for( $i = 0; $i < $num_events; $i++ ){
			// convert rows to jevents
			$icalrows[$i] = new jIcalEventRepeat($icalrows[$i]);
		}

		$rows = array_merge($rows,$icalrows);
		usort( $rows, array('JEventsDBModel', 'sortJointEvents' ));

		if (count($rows)>$limit){
			$rows = array_slice($rows,0,$limit);
		}
		return $rows;
	}


	function sortEvents( $a, $b ){

		list( $adate, $atime ) = split( ' ', $a->publish_up );
		list( $bdate, $btime ) = split( ' ', $b->publish_up );
		return strcmp( $atime, $btime );
	}

	function sortJointEvents( $a, $b ){
		$adatetime = $a->getUnixStartTime();
		$bdatetime = $b->getUnixStartTime();
		if ($adatetime==$bdatetime) return 0;
		return ($adatetime>$bdatetime)?-1:1;
	}

	function findMatchingRepeat($uid, $year, $month, $day){
		$start = $year.'/'.$month.'/'.$day.' 00:00:00';
		$end = $year.'/'.$month.'/'.$day.' 23:59:59';

		$db	=& JFactory::getDBO();
		$query = "SELECT ev.*, rpt.* "
		. "\n FROM #__jevents_vevent as ev"
		. "\n LEFT JOIN #__jevents_repetition as rpt ON rpt.eventid = ev.ev_id"
		. "\n AND rpt.startrepeat>=".$db->Quote($start)." AND rpt.startrepeat<=".$db->Quote($end)
		. "\n WHERE ev.uid = ".$db->Quote($uid);

		$db->setQuery( $query );
		//echo $db->_sql;
		$rows = $db->loadObjectList();
		if (count($rows)>0){
			return $rows[0]->rp_id;
		}

	}
}
