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


class AdminIcalrepeatController extends JController {

	var $_debug = false;
	var $queryModel = null;

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
	 * List Ical Repeats
	 *
	 */
	function overview( )
	{

		$db	=& JFactory::getDBO();
		$publishedOnly=false;
		$cid = JRequest::getVar(	'cid',	array(0) );
		JArrayHelper::toInteger($cid);

		if (is_array($cid) && count($cid)>0) $id=$cid[0];
		else $id=$cid;

		// if cancelling a repeat edit then I get the event id a different way
		$evid = JRequest::getInt("evid",0);
		if ($evid>0){
			$id=$evid;
		}

		global $mainframe;
		$limit		= intval( $mainframe->getUserStateFromRequest( "viewlistlimit", 'limit', 10 ));
		$limitstart = intval( $mainframe->getUserStateFromRequest( "view{".JEV_COM_COMPONENT."}limitstart", 'limitstart', 0 ));

		$query = "SELECT count(rpt.rp_id)"
		. "\n FROM #__jevents_vevent as ev"
		. "\n LEFT JOIN #__jevents_icsfile as icsf ON icsf.ics_id=ev.icsid"
		. "\n LEFT JOIN #__jevents_repetition as rpt ON rpt.eventid = ev.ev_id"
		. "\n LEFT JOIN #__jevents_vevdetail as det ON det.evdet_id = rpt.eventdetail_id"
		. "\n LEFT JOIN #__jevents_rrule as rr ON rr.eventid = ev.ev_id"
		. "\n WHERE ev.catid IN(".$this->queryModel->accessibleCategoryList().")"
		. "\n AND ev.ev_id=".$id
		. "\n AND icsf.state=1"
		. ($publishedOnly?"\n AND ev.state=1":"")
		. "\n GROUP BY rpt.rp_id";
		$db->setQuery( $query);
		$total = $db->loadResult();

		if( $limit > $total ) {
			$limitstart = 0;
		}

		$query = "SELECT ev.*, rpt.*, rr.*, det.*"
		. "\n , YEAR(rpt.startrepeat) as yup, MONTH(rpt.startrepeat ) as mup, DAYOFMONTH(rpt.startrepeat ) as dup"
		. "\n , YEAR(rpt.endrepeat  ) as ydn, MONTH(rpt.endrepeat   ) as mdn, DAYOFMONTH(rpt.endrepeat   ) as ddn"
		. "\n , HOUR(rpt.startrepeat) as hup, MINUTE(rpt.startrepeat ) as minup, SECOND(rpt.startrepeat ) as sup"
		. "\n , HOUR(rpt.endrepeat  ) as hdn, MINUTE(rpt.endrepeat   ) as mindn, SECOND(rpt.endrepeat   ) as sdn"
		. "\n FROM #__jevents_vevent as ev"
		. "\n LEFT JOIN #__jevents_icsfile as icsf ON icsf.ics_id=ev.icsid"
		. "\n LEFT JOIN #__jevents_repetition as rpt ON rpt.eventid = ev.ev_id"
		. "\n LEFT JOIN #__jevents_vevdetail as det ON det.evdet_id = rpt.eventdetail_id"
		. "\n LEFT JOIN #__jevents_rrule as rr ON rr.eventid = ev.ev_id"
		. "\n WHERE ev.catid IN(".$this->queryModel->accessibleCategoryList().")"
		. "\n AND ev.ev_id=".$id
		. "\n AND icsf.state=1"
		. ($publishedOnly?"\n AND ev.state=1":"")
		. "\n GROUP BY rpt.rp_id"
		. "\n ORDER BY rpt.startrepeat";
		if ($limit>0){
			$query .= "\n LIMIT $limitstart, $limit";
		}

		$db->setQuery( $query );
		$icalrows = $db->loadObjectList();
		$icalcount = count($icalrows);
		for( $i = 0; $i < $icalcount ; $i++ ){
			// convert rows to jIcalEvents
			$icalrows[$i] = new jIcalEventRepeat($icalrows[$i]);
		}


		jimport('joomla.html.pagination');
		$pageNav = new JPagination( $total, $limitstart, $limit  );

		// get the view
		$this->view = & $this->getView("icalrepeat","html");

		// Set the layout
		$this->view->setLayout('overview');

		$this->view->assign('icalrows',$icalrows);
		$this->view->assign('pageNav',$pageNav);

		$this->view->display();
	}

	function edit(){
		// get the view
		$this->view = & $this->getView("icalrepeat","html");

		$db	=& JFactory::getDBO();
		$cid = JRequest::getVar(	'cid',	array(0) );
		JArrayHelper::toInteger($cid);
		if (is_array($cid) && count($cid)>0) $id=$cid[0];
		else $id=$cid;

		if (!JEVHelper::isEventCreator()){
			JError::raiseError( 403, JText::_("ALERTNOTAUTH") );
		}

		// front end passes the id as evid
		if ($id==0){
			$id = JRequest::getInt("evid",0);
		}

		if ($id==0 && !JEVHelper::isEventCreator()){
			JError::raiseError( 403, JText::_("ALERTNOTAUTH") );
		}

		$db	=& JFactory::getDBO();
		$query = "SELECT rpt.eventid"
		. "\n FROM (#__jevents_vevent as ev, #__jevents_icsfile as icsf)"
		. "\n LEFT JOIN #__jevents_repetition as rpt ON rpt.eventid = ev.ev_id"
		. "\n LEFT JOIN #__jevents_vevdetail as det ON det.evdet_id = rpt.eventdetail_id"
		. "\n LEFT JOIN #__jevents_rrule as rr ON rr.eventid = ev.ev_id"
		. "\n WHERE ev.catid IN(".$this->queryModel->accessibleCategoryList().")"
		. "\n AND rpt.rp_id=".$id
		. "\n AND icsf.ics_id=ev.icsid AND icsf.state=1";
		$db->setQuery( $query);
		$ev_id = $db->loadResult();
		if ($ev_id==0 || $id==0){
			$this->setRedirect( 'index.php?option=' . JEV_COM_COMPONENT. '&task=icalrepeat.list&cid[]='.$ev_id,"ICal repeat does not exist");
			$this->redirect();
		}

		$repeatId = $id;

		$row = $this->queryModel->listEventsById($repeatId, true, "icaldb");

		if (!JEVHelper::canEditEvent($row)){
			JError::raiseError( 403, JText::_("ALERTNOTAUTH") );
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
		}
		else {
			$icsid = $row->icsid()>0?$row->icsid():current($nativeCals)->ics_id;

			$clist = '<input type="hidden" name="ics_id" value="'.$icsid.'" />';
			$this->view->assign('clistChoice',false);
		}

		// Set the layout
		$this->view->setLayout('edit');

		$this->view->assign('ev_id',$ev_id);
		$this->view->assign('rp_id',$repeatId);
		$this->view->assign('row',$row);
		$this->view->assign('nativeCals',$nativeCals);
		$this->view->assign('clist',$clist);
		$this->view->assign('repeatId',$repeatId);
		$this->view->assign('glist',$glist);
		$this->view->assignRef('dataModel',$this->dataModel);
		$this->view->assign('editCopy',false);

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

		$option = JEV_COM_COMPONENT;
		$rp_id = intval(JRequest::getVar( "rp_id","0"));
		$cid = JRequest::getVar( "cid",array());
		if (count($cid)>0 && $rp_id==0) $rp_id=intval($cid[0]);
		if ($rp_id==0){
			$this->setRedirect( 'index.php?option=' . $option. '&task=icalrepeat.list&cid[]='.$rp_id,"1Cal rpt NOT SAVED");
			$this->redirect();
		}

		// I should be able to do this in one operation but that can come later
		$event = $this->queryModel->listEventsById( intval($rp_id), 1, "icaldb" );
		if (!JEVHelper::canEditEvent($event)){
			JError::raiseError( 403, JText::_("ALERTNOTAUTH") );
		}

		$db	=& JFactory::getDBO();
		$rpt = new iCalRepetition($db);
		$rpt->load($rp_id);

		$query = "SELECT detail_id FROM #__jevents_vevent WHERE ev_id=$rpt->eventid";
		$db->setQuery( $query);
		$eventdetailid = $db->loadResult();

		$data["UID"]				= JRequest::getVar( "uid",md5(uniqid(rand(),true)));

		$data["LOCATION"]		= JRequest::getVar( "location","");
		$data["allDayEvent"]	= JRequest::getVar( "allDayEvent","off");
		$data["CONTACT"]		= JRequest::getVar( "contact_info","");
		// allow raw HTML (mask =2)
		$data["DESCRIPTION"]	= JRequest::getVar( "jevcontent","", 'request',  'html', 2);
		$data["publish_down"]	= JRequest::getVar( "publish_down","2006-12-12");
		$data["publish_up"]		= JRequest::getVar( "publish_up","2006-12-12");
		$interval 				= JRequest::getVar( "rinterval",1);
		$data["SUMMARY"]		= JRequest::getVar( "title","");

		$ics_id				= JRequest::getVar( "ics_id",0);

		if ($data["allDayEvent"]=="on"){
			$start_time="00:00";
		}
		else $start_time			= JRequest::getVar( "start_time","08:00");
		$publishstart		= $data["publish_up"] . ' ' . $start_time . ':00';
		$data["DTSTART"]	= strtotime( $publishstart );

		if ($data["allDayEvent"]=="on"){
			$end_time="23:59";
			$publishend		= $data["publish_down"] . ' ' . $end_time . ':59';
		}
		else {
			$end_time 			= JRequest::getVar( "end_time","15:00");
			$publishend		= $data["publish_down"] . ' ' . $end_time . ':00';
		}

		$data["DTEND"]		= strtotime( $publishend );
		// iCal for whole day uses 00:00:00 on the next day JEvents uses 23:59:59 on the same day
		list ($h,$m,$s) = explode(":",$end_time . ':00');
		if (($h+$m+$s)==0 && $data["allDayEvent"]=="on" && $data["DTEND"]>$data["DTSTART"]) {
			$publishend = strftime('%Y-%m-%d 23:59:59',($data["DTEND"]-86400));
			$data["DTEND"]		= strtotime( $publishend );
		}

		// Add any custom fields into $data array
		$array = JRequest::get("post");
		foreach ($array as $key=>$value) {
			if (strpos($key,"custom_")===0){
				$data[$key]=$value;
			}
		}

		$detail = iCalEventDetail::iCalEventDetailFromData($data);

		// if we already havea unique event detail then edit this one!
		if ($eventdetailid != $rpt->eventdetail_id){
			$detail->evdet_id = $rpt->eventdetail_id;
		}

		$detail->priority =  intval(JArrayHelper::getValue( $array,  "priority",0));

		$detail->store();

		// populate rpt with data
		//$start = strtotime($data["publish_up"] . ' ' . $start_time . ':00');
		//$end = strtotime($data["publish_down"] . ' ' . $end_time . ':00');
		$start = $data["DTSTART"];
		$end = $data["DTEND"];
		$rpt->startrepeat = strftime('%Y-%m-%d %H:%M:%S',$start);
		$rpt->endrepeat = strftime('%Y-%m-%d %H:%M:%S',$end);

		$rpt->duplicatecheck = md5($rpt->eventid . $start );
		$rpt->eventdetail_id = $detail->evdet_id;
		$rpt->rp_id = $rp_id;
		$rpt->store();

		$exception = iCalException::loadByRepeatId($rp_id);
		if (!$exception){
			$exception = new iCalException($db);
			$exception->bind(get_object_vars($rpt));
		}
		$exception->exception_type = 1; // modified
		$exception->store();

		global $mainframe;
		if ($mainframe->isAdmin()){
			$this->setRedirect( 'index.php?option=' .JEV_COM_COMPONENT . '&task=icalrepeat.list&cid[]='.$rpt->eventid,"ICal rpt and new details saved");
		}
		else {
			list($year,$month,$day) = JEVHelper::getYMD();
			$params = JComponentHelper::getParams(JEV_COM_COMPONENT);
			if ($params->get("editpopup",0)) {
				$this->setMessage($msg);
				ob_end_clean();
				?>
				<script type="text/javascript">window.parent.location="<?php echo JRoute::_('index.php?option=' . JEV_COM_COMPONENT ."&task=icalrepeat.detail&evid=".$rpt->rp_id."&Itemid=".JEVHelper::getItemid()."&year=$year&month=$month&day=$day",false);?>";
				window.parent.SqueezeBox.close();
				try {
					window.parent.closedialog();
				}
				catch (e){}
				</script>
				<?php
				exit();
			}
			$this->setRedirect( 'index.php?option=' . JEV_COM_COMPONENT ."&task=icalrepeat.detail&evid=".$rpt->rp_id."&Itemid=".JEVHelper::getItemid()."&year=$year&month=$month&day=$day","ICal rpt updated");
		}

	}

	// experimentaal code disabled for the time being
	function savefuture(){
		// experimentaal code disabled for the time being
		JError::raiseError( 403, JText::_("ALERTNOTAUTH") );

		if (!JEVHelper::isEventCreator()){
			JError::raiseError( 403, JText::_("ALERTNOTAUTH") );
		}

		// clean out the cache
		$cache = &JFactory::getCache('com_jevents');
		$cache->clean(JEV_COM_COMPONENT);

		echo "<pre>";
		$rpid = JRequest::getInt("rp_id",0);
		$event = $this->queryModel->listEventsById( $rpid, 1, "icaldb" );
		$data = array();
		foreach (get_object_vars($event) as $key=>$val) {
			if (strpos($key,"_")==0){
				$data[substr($key,1)]=$val;
			}
		}
		echo var_export($data,true);

		echo "\n\n";

		// Change the underlying event repeat rule details  !!
		$query = "SELECT * FROM #__jevents_rrule WHERE rr_id=".$event->_rr_id;
		$db =& JFactory::getDBO();
		$db->setQuery( $query);
		$this->rrule = null;
		$this->rrule = $db->loadObject();
		$this->rrule = iCalRRule::iCalRRuleFromDB(get_object_vars($this->rrule));


		echo var_export($this->rrule,true);

		// TODO *** I must save the modified repeat rule

		// Create the copy of the event and reset the repeat rule to the new values
		foreach ($this->rrule->data as $key=>$val){
			$key = "_".$key;
			$event->$key = $val;
		}
		$event->eventid=0;
		$event->ev_id=0;

		// create copy of rrule by resetting id and saving
		$this->rrule->rr_id=0;
		$this->rrule->eventid=0;
		if (intval($this->rrule->until)>0){
			// The until date is in the future so no need to do anything here
		}
		else {
			// count prior matching repetition
			$query = "SELECT count (startrepeat) FROM #__jevents_repetition WHERE eventid=".$repeatdata->eventid . " AND startrepeat<'".$repeatdata->startrepeat;
			$db->setQuery( $query);
			$countrepeat = $db->loadResult();
			$this->rrule->count -= $countrepeat ;
		}
		//$this->rrule->store();
		$event->_rr_id=$this->rrule->rr_id;

		//I must copy the event detail and its id too
		$eventDetail = new iCalEventDetail($db);
		$eventDetail->load($event->_eventdetail_id);
		$eventDetail->evdet_id = 0;
		$eventDetail->store();

		$event->_detail_id=$eventDetail->evdet_id;
		$event->_eventdetail_id=$eventDetail->evdet_id;

		// TODO I must now regenerate the repetitions
		$event->_rp_id=0;

		$event->store();
		echo "</pre>";

		// TODO I must store a copy of the event id in the rrule table
		exit();

		// now delete exising current and future repeats - this resets the rrule for the truncated event
		$this->_deleteFuture();

		global $mainframe;
		if ($mainframe->isAdmin()){
			$this->setRedirect( 'index.php?option=' .JEV_COM_COMPONENT . '&task=icalrepeat.list&cid[]='.$rpt->eventid,"ICal rpt and new details saved");
		}
		else {
			list($year,$month,$day) = JEVHelper::getYMD();
			$rettask = JRequest::getString("rettask","month.calendar");
			$this->setRedirect( 'index.php?option=' . JEV_COM_COMPONENT ."&task=$rettask&evid=".$rpt->rp_id."&Itemid=".JEVHelper::getItemid()."&year=$year&month=$month&day=$day","ICal rpt updated");
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

	function delete(){
		// clean out the cache
		$cache = &JFactory::getCache('com_jevents');
		$cache->clean(JEV_COM_COMPONENT);

		if (!JEVHelper::isEventCreator()){
			JError::raiseError( 403, JText::_("ALERTNOTAUTH") );
		}

		$cid = JRequest::getVar(	'cid',	array(0) );
		JArrayHelper::toInteger($cid);

		$db =& JFactory::getDBO();
		foreach ($cid as $id){

			// I should be able to do this in one operation but that can come later
			$event = $this->queryModel->listEventsById( intval($id), 1, "icaldb" );
			if (!JEVHelper::canDeleteEvent($event)){
				JError::raiseError( 403, JText::_("ALERTNOTAUTH") );
			}

			$query = "SELECT * FROM #__jevents_repetition WHERE rp_id=$id";
			$db->setQuery( $query);
			$data = null;
			$data = $db->loadObject();

			$query = "SELECT detail_id FROM #__jevents_vevent WHERE ev_id=$data->eventid";
			$db->setQuery( $query);
			$eventdetailid = $db->loadResult();

			// only remove the detail id if its different for this repetition i.e. not the global one!
			if ($eventdetailid != $data->eventdetail_id){
				$query = "DELETE FROM #__jevents_vevdetail WHERE evdet_id = ".$data->eventdetail_id;
				$db->setQuery( $query);
				$db->query();
			}

			// create exception based on deleted repetition
			$rp_id = $id;
			$exception = iCalException::loadByRepeatId($rp_id);
			if (!$exception){
				$exception = new iCalException($db);
				$exception->bind(get_object_vars($data));
			}
			$exception->exception_type = 0; // deleted
			$exception->store();

			$query = "DELETE FROM #__jevents_repetition WHERE rp_id=$id";
			$db->setQuery( $query);
			$db->query();
		}
		global $mainframe;
		if ($mainframe->isAdmin()){
			$this->setRedirect( "index.php?option=".JEV_COM_COMPONENT."&task=icalrepeat.list&cid[]=".$data->eventid, "ICal Repeat deleted" );
		}
		else {
			global $Itemid;
			list($year,$month,$day) = JEVHelper::getYMD();
			$this->setRedirect( JRoute::_('index.php?option=' . JEV_COM_COMPONENT. "&task=day.listevents&year=$year&month=$month&day=$day&Itemid=$Itemid"),"IcalEvent Saved");
		}
	}

	function deletefuture(){

		// clean out the cache
		$cache = &JFactory::getCache('com_jevents');
		$cache->clean(JEV_COM_COMPONENT);

		if (!JEVHelper::isEventCreator()){
			JError::raiseError( 403, JText::_("ALERTNOTAUTH") );
		}

		$this->_deleteFuture();

		global $mainframe;
		if ($mainframe->isAdmin()){
			$this->setRedirect( "index.php?option=".JEV_COM_COMPONENT."&task=icalrepeat.list&cid[]=".$repeatdata->eventid, "ICal Repeats deleted" );
		}
		else {
			global $Itemid;
			list($year,$month,$day) = JEVHelper::getYMD();
			$this->setRedirect( JRoute::_('index.php?option=' . JEV_COM_COMPONENT. "&task=day.listevents&year=$year&month=$month&day=$day&Itemid=$Itemid"),"Ical Repeats Deleted");
		}
	}

	function _deleteFuture(){
		$cid = JRequest::getVar(	'cid',	array(0) );
		JArrayHelper::toInteger($cid);

		$db =& JFactory::getDBO();
		foreach ($cid as $id){

			// I should be able to do this in one operation but that can come later
			$event = $this->queryModel->listEventsById( intval($id), 1, "icaldb" );
			if (!JEVHelper::canDeleteEvent($event)){
				JError::raiseError( 403, JText::_("ALERTNOTAUTH") );
			}

			$query = "SELECT * FROM #__jevents_repetition WHERE rp_id=$id";
			$db->setQuery( $query);
			$repeatdata = null;
			$repeatdata = $db->loadObject();
			if (is_null($repeatdata)){
				JError::raiseError( 4777, JText::_("No such event") );
				return;
			}

			$query = "SELECT detail_id FROM #__jevents_vevent WHERE ev_id=$repeatdata->eventid";
			$db->setQuery( $query);
			$eventdetailid = $db->loadResult();

			// Find detail ids for future repetitions that don't match the global event detail
			$query = "SELECT eventdetail_id FROM #__jevents_repetition WHERE eventid=".$repeatdata->eventid . " AND startrepeat>='".$repeatdata->startrepeat."' AND eventdetail_id<>".$eventdetailid;
			$db->setQuery( $query);
			$detailids = $db->loadResultArray();

			// Change the underlying event repeat rule details  !!
			$query = "SELECT * FROM #__jevents_rrule WHERE eventid=$repeatdata->eventid";
			$db->setQuery( $query);
			$this->rrule = null;
			$this->rrule = $db->loadObject();
			$this->rrule = iCalRRule::iCalRRuleFromDB(get_object_vars($this->rrule));
			if (intval($this->rrule->until)>0){
				// Find latest matching repetition
				$query = "SELECT max(startrepeat) FROM #__jevents_repetition WHERE eventid=".$repeatdata->eventid . " AND startrepeat<'".$repeatdata->startrepeat."'";
				$db->setQuery( $query);
				$lastrepeat = $db->loadResult();
				$this->rrule->until = strtotime($lastrepeat);
			}
			else {
				// Find latest matching repetition
				$query = "SELECT count (startrepeat) FROM #__jevents_repetition WHERE eventid=".$repeatdata->eventid . " AND startrepeat<'".$repeatdata->startrepeat;
				$db->setQuery( $query);
				$countrepeat = $db->loadResult();
				$this->rrule->count = $countrepeat ;
			}
			$this->rrule->store();

			if (!is_null($detailids) && count($detailids)>0){
				$query = "DELETE FROM #__jevents_vevdetail WHERE evdet_id IN (".implode(",",$detailids).")";
				$db->setQuery( $query);
				$db->query();
			}

			// I also need to clean out associated custom data
			$dispatcher	=& JDispatcher::getInstance();
			// just incase we don't have jevents plugins registered yet
			JPluginHelper::importPlugin("jevents");
			$res = $dispatcher->trigger( 'onDeleteEventDetails' , array(implode(",",$detailids)));

			$query = "DELETE FROM #__jevents_repetition WHERE eventid=".$repeatdata->eventid . " AND startrepeat>='".$repeatdata->startrepeat."'";
			$db->setQuery( $query);
			$db->query();
		}

	}
}
