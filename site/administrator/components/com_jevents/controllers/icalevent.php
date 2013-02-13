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


class AdminIcaleventController extends JController {

	var $_debug = false;
	var $queryModel = null;
	var $dataModel = null;

	var $editCopy = false;

	/**
	 * Controler for the Ical Functions
	 * @param array		configuration
	 */
	function __construct($config = array())
	{
		parent::__construct($config);
		$this->registerTask( 'list',  'overview' );
		$this->registerDefaultTask("overview");

		$cfg = & JEVConfig::getInstance();
		$this->_debug = $cfg->get('jev_debug', 0);

		$this->dataModel = new JEventsDataModel("JEventsAdminDBModel");
		$this->queryModel =& new JEventsDBModel($this->dataModel);
	}

	/**
	 * List Ical Events
	 *
	 */
	function overview( )
	{
		// get the view
		$this->view = & $this->getView("icalevent","html");

		$this->_checkValidCategories();

		$showUnpublishedICS = true;
		$showUnpublishedCategories = true;

		global  $mainframe;

		$db	=& JFactory::getDBO();

		$icsFile	= intval( $mainframe->getUserStateFromRequest("icsFile","icsFile", 0 ));
		// two distinct category filters
		$catidtop	= intval( $mainframe->getUserStateFromRequest( "catidIcalEventsTop", 'catidtop', 0 ));
		$catid		= intval( $mainframe->getUserStateFromRequest( "catidIcalEvents", 'catid', 0 ));
		if ($catidtop>0 && $catid==0){
			$catid = $catidtop;
		}
		$limit		= intval( $mainframe->getUserStateFromRequest( "viewlistlimit", 'limit', 10 ));
		$limitstart = intval( $mainframe->getUserStateFromRequest( "view{".JEV_COM_COMPONENT."}limitstart", 'limitstart', 0 ));
		$search		= $mainframe->getUserStateFromRequest( "search{".JEV_COM_COMPONENT."}", 'search', '' );
		$search		= $db->getEscaped( trim( strtolower( $search ) ) );
		$where		= array();

		if( $search ){
			$where[] = "LOWER(detail.summary) LIKE '%$search%'";
		}

		if( $catid > 0 ){
			$where[] = "ev.catid='$catid'";
		}

		if ($icsFile>0) {
			$icsFrom = ", #__jevents_icsfile as icsf ";
			$where[] = "\n icsf.ics_id = ev.icsid";
			$where[] = "\n icsf.ics_id = $icsFile";
			if (!$showUnpublishedICS){
				$where[] = "\n icsf.state=1";
			}
		}
		else {
			if (!$showUnpublishedICS){
				$icsFrom = ", #__jevents_icsfile as icsf ";
				$where[] = "\n icsf.ics_id = ev.icsid";
				$where[] = "\n icsf.state=1";
			}
			else {
				$icsFrom = "";
			}
		}

		if ($hidepast = JRequest::getInt('hidepast',1)){
			$datenow =& JFactory::getDate("-1 day");
			$where[] = "\n detail.dtend>".$datenow->toUnix();
		}
		
		// get the total number of records
		$query = "SELECT count(*)"
		. "\n FROM (#__jevents_vevent AS ev $icsFrom)"
		. "\n LEFT JOIN #__jevents_rrule as rr ON rr.eventid = ev.ev_id"
		. "\n LEFT JOIN #__jevents_vevdetail as detail ON ev.detail_id=detail.evdet_id"
		. ( count( $where ) ? "\n WHERE " . implode( ' AND ', $where ) : '' )
		;
		$db->setQuery( $query);
		$total = $db->loadResult();
		echo $db->getErrorMsg();
		if( $limit > $total ) {
			$limitstart = 0;
		}

		$query = "SELECT ev.*, ev.state as evstate, detail.*, g.name AS _groupname "
		."\n , rr.rr_id, rr.freq,rr.rinterval"//,rr.until,rr.untilraw,rr.count,rr.bysecond,rr.byminute,rr.byhour,rr.byday,rr.bymonthday"
		."\n ,MAX(rpt.endrepeat) as endrepeat"
		."\n ,MIN(rpt.startrepeat) as startrepeat"
		. "\n FROM (#__jevents_vevent as ev $icsFrom)"
		. "\n LEFT JOIN #__jevents_vevdetail as detail ON ev.detail_id=detail.evdet_id"
		. "\n LEFT JOIN #__jevents_rrule as rr ON rr.eventid = ev.ev_id"
		. "\n LEFT JOIN #__jevents_repetition as rpt ON rpt.eventid = ev.ev_id"
		. "\n LEFT JOIN #__groups AS g ON g.id = ev.access"
		. ( count( $where ) ? "\n WHERE " . implode( ' AND ', $where ) : '' )
		//. "\n WHERE ev.catid IN(".$this->queryModel->accessibleCategoryList().")"
		. "\n GROUP BY rpt.eventid"
		. "\n ORDER BY detail.dtstart ASC";
		if ($limit>0){
			$query .= "\n LIMIT $limitstart, $limit";
		}

		$db->setQuery( $query );
		$rows = $db->loadObjectList();
		foreach ($rows as $key=>$val) {
			// set state variable to the event value not the event detail value
			$rows[$key]->state = $rows[$key]->evstate;
			$groupname = $rows[$key]->_groupname;
			$rows[$key]=new jIcalEventRepeat($rows[$key]);
			$rows[$key]->_groupname = $groupname;
		}
		if( $this->_debug ){
			echo '[DEBUG]<br />';
			echo 'query:';
			echo '<pre>';
			echo $query;
			echo '-----------<br />';
			echo 'option "' . JEV_COM_COMPONENT . '"<br />';
			echo '</pre>';
			//die( 'userbreak - mic ' );
		}

		if ($db->getErrorNum()) {
			echo $db->stderr();
			return false;
		}

		// get list of categories
		$attribs = 'class="inputbox" size="1" onchange="document.adminForm.submit();"';
		$clist = JEventsHTML::buildCategorySelect( $catid, $attribs, null, $showUnpublishedCategories, false, $catidtop,"catid");

		// get list of ics Files
		$icsfiles = array();
		//$icsfiles[] =  JHTML::_('select.option', '0', "Choose ICS FILE" );
		$icsfiles[] = JHTML::_('select.option', '-1', JText::_("ALL ICS FILES"));

		$query = "SELECT ics.ics_id as value, ics.label as text FROM #__jevents_icsfile as ics ";
		if (!$showUnpublishedICS){
			$query .= " WHERE ics.state=1";
		}

		$db->setQuery( $query );
		$result = $db->loadObjectList() ;

		$icsfiles = array_merge( $icsfiles, $result);
		$icslist = JHTML::_('select.genericlist', $icsfiles, 'icsFile', 'class="inputbox" size="1" onchange="document.adminForm.submit();"', 'value', 'text', $icsFile );

		$hidepast = JRequest::getInt('hidepast',1);
		$options[] = JHTML::_('select.option', '0', JText::_('No'));
		$options[] = JHTML::_('select.option', '1', JText::_('Yes'));
		$plist = JHTML::_('select.genericlist', $options, 'hidepast', 'class="inputbox" size="1" onchange="document.adminForm.submit();"', 'value', 'text', $hidepast );

		$catData = JEV_CommonFunctions::getCategoryData();

		jimport('joomla.html.pagination');
		$pageNav = new JPagination( $total, $limitstart, $limit  );


		// Set the layout
		$this->view->setLayout('overview');

		$this->view->assign('rows',$rows);
		$this->view->assign('clist',$clist);
		$this->view->assign('plist',$plist);
		$this->view->assign('search',$search);
		$this->view->assign('icsList',$icslist);
		$this->view->assign('pageNav',$pageNav);

		$this->view->display();
	}

	function edit () {	
		// get the view
		$this->view = & $this->getView("icalevent","html");

		$cid = JRequest::getVar(	'cid',	array(0) );
		JArrayHelper::toInteger($cid);
		if (is_array($cid) && count($cid)>0) $id=$cid[0];
		else $id=0;

		// front end passes the id as evid
		if ($id==0){
			$id = JRequest::getInt("evid",0);
		}

		if ($id==0 && !JEVHelper::isEventCreator()){
			JError::raiseError( 403, JText::_("ALERTNOTAUTH") );
		}
		
		$repeatId = 0;

		// iCal agid uses GUID or UUID as identifier
		if ($id>0){
			if ($repeatId==0) {
				$vevent = $this->dataModel->queryModel->getVEventById( $id);
				$row = new jIcalEventDB($vevent);
			}
			else {
				$row = $this->queryModel->listEventsById($repeatId, true, "icaldb");
			}

			if (!JEVHelper::canEditEvent($row)){
				JError::raiseError( 403, JText::_("ALERTNOTAUTH") );
			}
		}
		else {
			$vevent = new iCalEvent($db);
			$vevent->set("freq","DAILY");
			$vevent->set("description","");
			$vevent->set("summary","");
			$vevent->set("dtstart",mktime(8,0,0));
			$vevent->set("dtend",mktime(15,0,0));
			$row = new jIcalEventDB($vevent);

			// TODO - move this to class!!
			// populate with meaningful initial values
			$row->starttime("07:23");
			$row->endtime("16:47");

		}

		$db =& JFactory::getDBO();
		// get list of groups
		$query = "SELECT id AS value, name AS text"
		. "\n FROM #__groups"
		. "\n ORDER BY id"	;
		$db->setQuery( $query );
		$groups = $db->loadObjectList();

		// build the html select list
		$glist = JHTML::_('select.genericlist', $groups, 'access', 'class="inputbox" size="1"',
		'value', 'text', intval( $row->access() ) );

		// get all the raw native calendars
		$nativeCals = $this->dataModel->queryModel->getNativeIcalendars();

		// only offer a choice of native calendars if it exists!
		if (count($nativeCals)>1){
			$icalList = array();
			$icalList[] = JHTML::_('select.option', '0', JText::_('JEV_EVENT_CHOOSE_ICAL'), 'ics_id', 'label' );
			$icalList = array_merge( $icalList, $nativeCals );
			$clist = JHTML::_('select.genericlist', $icalList, 'ics_id', " onchange='preselectCategory(this);'", 'ics_id', 'label', $row->icsid() );
			$this->view->assign('clistChoice',true);
			$this->view->assign('defaultCat',0);
		}
		else {		
			if (count($nativeCals)==0 || !is_array($nativeCals)){
				JError::raiseWarning(870, JText::_("INVALID CALENDAR STRUCTURE") );
			}

			$icsid = $row->icsid()>0?$row->icsid():current($nativeCals)->ics_id;
		
			$clist = '<input type="hidden" name="ics_id" value="'.$icsid.'" />';
			$this->view->assign('clistChoice',false);
			$params = JComponentHelper::getParams(JEV_COM_COMPONENT);
			if ($params->get("defaultcat",false)){
				$this->view->assign('defaultCat',current($nativeCals)->catid);
			}
			else {
				$this->view->assign('defaultCat',0);
			}
		}
		
		// Set the layout
		$this->view->setLayout('edit');

		$this->view->assign('editCopy',$this->editCopy);
		$this->view->assign('id',$id);
		$this->view->assign('row',$row);
		$this->view->assign('nativeCals',$nativeCals);
		$this->view->assign('clist',$clist);
		$this->view->assign('repeatId',$repeatId);
		$this->view->assign('glist',$glist);
		// only those who can publish globally can set priority field
		if (JEVHelper::isEventPublisher(true)){
			$list = array();
			for ($i=0;$i<10;$i++)	{
				$list[] = JHTML::_('select.option', $i, $i, 'val', 'text' );
			}
			$priorities = JHTML::_('select.genericlist', $list, 'priority', "", 'val', 'text', $row->priority() );
			$this->view->assign('setPriority',true);
			$this->view->assign('priority',$priorities);	
		}
		else {
			$this->view->assign('setPriority',false);
		}
		$this->view->assignRef('dataModel',$this->dataModel);

		// for Admin interface only
		global $mainframe;
		$this->view->assign('with_unpublished_cat',$mainframe->isAdmin());

		$this->view->display();

	}

	function save(){
		if (!JEVHelper::isEventCreator()){
			JError::raiseError( 403, JText::_("ALERTNOTAUTH") );
		}

		// clean out the cache
		$cache = &JFactory::getCache('com_jevents');
		$cache->clean(JEV_COM_COMPONENT);

		// JREQUEST_ALLOWHTML requires at least Joomla 1.5 svn9979 (past 1.5 stable)
		$array = JRequest::get('request', JREQUEST_ALLOWHTML);

		$rrule = SaveIcalEvent::generateRRule($array);

		// ensure authorised
		if (isset($array["evid"]) &&  $array["evid"]>0){
			$event = $this->queryModel->getEventById( intval($array["evid"]), 1, "icaldb" );
			if (!JEVHelper::canEditEvent($event)){
				JError::raiseError( 403, JText::_("ALERTNOTAUTH") );
			}	
		}
		
		if ($event = SaveIcalEvent::save($array, $this->queryModel, $rrule)){
			
			$row = new jIcalEventDB($vevent);
			if (JEVHelper::canPublishEvent($row)){		
				$msg = JText::_("Event Saved", true);
			}
			else {
				$msg = JText::_("Event Saved Under Review", true);
			}
		}
		else {
			$msg = JText::_("Event Not Saved", true); 
		}

		global $mainframe;
		if ($mainframe->isAdmin()){
			$this->setRedirect( 'index.php?option=' . JEV_COM_COMPONENT. '&task=icalevent.list',$msg);
		}
		else {
			global $Itemid;
			list($year,$month,$day) = JEVHelper::getYMD();
			
			$params = JComponentHelper::getParams(JEV_COM_COMPONENT);
			if ($params->get("editpopup",0)) {
				ob_end_clean();
				?>
				<script type="text/javascript">window.parent.location="<?php echo JRoute::_('index.php?option=' . JEV_COM_COMPONENT. "&task=day.listevents&year=$year&month=$month&day=$day&Itemid=$Itemid",false);?>";
				alert("<?php echo $msg;?>");
				window.parent.SqueezeBox.close();
				try {
					window.parent.closedialog();
				}
				catch (e){}
				</script>
				<?php
				exit();
			}

			// I can't go back to the same repetition since its id has been lost
			$this->setRedirect( JRoute::_('index.php?option=' . JEV_COM_COMPONENT. "&task=day.listevents&year=$year&month=$month&day=$day&Itemid=$Itemid",false),$msg);
		}
	}

	function close(){
		ob_end_clean();
		?>
		<script type="text/javascript">
		window.parent.SqueezeBox.close();
		try {
			window.parent.closedialog();
		}
		catch (e){}
		</script>
		<?php
		exit();
	}
	
	function publish(){
		$cid = JRequest::getVar(	'cid',	array(0) );
		JArrayHelper::toInteger($cid);
		$this->toggleICalEventPublish($cid,1);
	}

	function unpublish(){
		$cid = JRequest::getVar(	'cid',	array(0) );
		JArrayHelper::toInteger($cid);
		$this->toggleICalEventPublish($cid,0);
	}

	function toggleICalEventPublish($cid,$newstate){
		// clean out the cache
		$cache = &JFactory::getCache('com_jevents');
		$cache->clean(JEV_COM_COMPONENT);

		if (!JEVHelper::isEventPublisher()){
			JError::raiseError( 403, JText::_("ALERTNOTAUTH") );
		}

		$db	=& JFactory::getDBO();
		foreach ($cid as $id) {

			// I should be able to do this in one operation but that can come later
			$event = $this->queryModel->listEventsById( intval($id), 1, "icaldb" );
			if (!JEVHelper::canPublishEvent($event)){
				JError::raiseError( 403, JText::_("ALERTNOTAUTH") );
			}

			$sql = "UPDATE #__jevents_vevent SET state=$newstate where ev_id='".$id."'";
			$db->setQuery($sql);
			$db->query();
		}

		global $mainframe;
		if ($mainframe->isAdmin()){
			$this->setRedirect( 'index.php?option=' . JEV_COM_COMPONENT. '&task=icalevent.list',"IcalEvent  : New published state Saved");
		}
		else {
			global $Itemid;
			list($year,$month,$day) = JEVHelper::getYMD();
			$rettask = JRequest::getString("rettask","month.calendar");
			// Don't return to the event detail since we may be filtering on published state!
			//$this->setRedirect( JRoute::_('index.php?option=' . JEV_COM_COMPONENT. "&task=icalrepeat.detail&evid=$id&year=$year&month=$month&day=$day&Itemid=$Itemid",false),"IcalEvent  : New published state Saved");
			$this->setRedirect( JRoute::_('index.php?option=' . JEV_COM_COMPONENT. "&task=$rettask&year=$year&month=$month&day=$day&Itemid=$Itemid",false),"IcalEvent  : New published state Saved");
		}
	}

	function delete(){
		// clean out the cache
		$cache = &JFactory::getCache('com_jevents');
		$cache->clean(JEV_COM_COMPONENT);

		if (!JEVHelper::isEventDeletor()){
			JError::raiseError( 403, JText::_("ALERTNOTAUTH") );
		}

		$cid = JRequest::getVar(	'cid',	array(0) );
		JArrayHelper::toInteger($cid);

		// front end passes the id as evid
		if (count($cid)==1 && $cid[0]==0 ){
			$cid = array(JRequest::getInt("evid",0));
		}
		
		$db	=& JFactory::getDBO();

		foreach ($cid as $key=>$id) {
			// I should be able to do this in one operation but that can come later
			$event = $this->queryModel->listEventsById( intval($id), 1, "icaldb" );
			if (!JEVHelper::canDeleteEvent($event)){
				JError::raiseWarning( 534, JText::_("JEV_NO_DELETE_ROW") );
				unset($cid[$key]);
			}
		}

		if (count($cid)>0){
			$veventidstring = implode(",",$cid);

			// TODO the ruccurences should take care of all of these??
			// This would fail if all recurrances have been 'adjusted'
			$query = "SELECT DISTINCT (eventdetail_id) FROM #__jevents_repetition WHERE eventid IN ($veventidstring)";
			$db->setQuery( $query);
			$detailids = $db->loadResultArray();
			$detailidstring = implode(",",$detailids);

			$query = "DELETE FROM #__jevents_rrule WHERE eventid IN ($veventidstring)";
			$db->setQuery( $query);
			$db->query();

			$query = "DELETE FROM #__jevents_repetition WHERE eventid IN ($veventidstring)";
			$db->setQuery( $query);
			$db->query();

			$query = "DELETE FROM #__jevents_exception WHERE eventid IN ($veventidstring)";
			$db->setQuery( $query);
			$db->query();

			if (strlen($detailidstring)>0){
				$query = "DELETE FROM #__jevents_vevdetail WHERE evdet_id IN ($detailidstring)";
				$db->setQuery( $query);
				$db->query();
			}

			$query = "DELETE FROM #__jevents_vevent WHERE ev_id IN ($veventidstring)";
			$db->setQuery( $query);
			$db->query();
		}
		global $mainframe;
		if ($mainframe->isAdmin()){
			$this->setRedirect( "index.php?option=".JEV_COM_COMPONENT."&task=icalevent.list", "ICal Event(s) deleted" );
		}
		else {
			global $Itemid;
			list($year,$month,$day) = JEVHelper::getYMD();
			$rettask = JRequest::getString("rettask","month.calendar");
			$this->setRedirect( JRoute::_('index.php?option=' . JEV_COM_COMPONENT. "&task=$rettask&year=$year&month=$month&day=$day&Itemid=$Itemid"),"IcalEvent Deleted");
		}

	}



	function _checkValidCategories(){
		// TODO switch this after migration
		$component_name = "com_jevents";

		$db	=& JFactory::getDBO();
		$query = "SELECT count(*) as count FROM #__categories"
		. "\n WHERE section='$component_name'";
		$db->setQuery($query);
		$count = intval($db->loadResult());
		if ($count<=0){
			$this->setRedirect("index.php?option=".JEV_COM_COMPONENT."&task=categories.list","You must first create at least one category");
			$this->redirect();
		}
	}

}