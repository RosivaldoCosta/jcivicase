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

	// require the html view class
	jimport( 'joomla.application.helper' );

	$option = JRequest::getString( 'option', '' );
	
	$session = &JSession::getInstance($handler, $options);
	if($session->get("status_filter") != "" ){
		$filter = $session->get("status_filter");
		$session->set("status_filter", "");
	} else {
		$filter = JRequest::getString( 'status_filter', '' );
	}
	
	if($session->get("startdateFilter") != "" ){
		$startdateFilter = $session->get("startdateFilter");
		$session->set("startdateFilter", "");
	} else {
		$startdateFilter = JRequest::getVar( 'startdateFilter', date("Y-m-d"));
	}

	if($session->get("enddateFilter") != "" ){
		$enddateFilter = $session->get("enddateFilter");
		$session->set("enddateFilter", "");
	} else {
		$enddateFilter = JRequest::getVar( 'enddateFilter', '' );
	}

	// Load configuration data
	//include( JPATH_SITE . "/administrator/components/com_rsappt_pro2/config.rsappt_pro.php" );
	//include( JPATH_SITE."/administrator/components/com_rsappt_pro2/config.rsappt_pro2.php" );
//	require_once( JPATH_SITE."/components/com_rsappt_pro2/functions2.php" );
	//global $my;  
	$user =& JFactory::getUser();

	$database = &JFactory::getDBO(); 
	$sql = 'SELECT * FROM #__sv_apptpro2_config';
	$database->setQuery($sql);
	$apptpro_config = NULL;
	$apptpro_config = $database -> loadObject();
	if ($database -> getErrorNum()) {
		echo "DB Err: ". $database -> stderr();
		return false;
	}


	if($session->get("filter_order") != "" ){
		$filter_order = $session->get("filter_order");
		$session->set("filter_order", "");
	} else {
		$filter_order = JRequest::getVar( 'filter_order', 'startdatetime' );
	}
	$ordering = $filter_order;

	if($session->get("filter_order_Dir") != "" ){
		$filter_order_Dir = $session->get("filter_order_Dir");
		$session->set("filter_order_Dir", "");
	} else {
		$filter_order_Dir = JRequest::getVar( 'filter_order_Dir', 'asc' );
	}
	$direction = $filter_order_Dir;

	 
	if(!$user->guest){

		$database = &JFactory::getDBO();
		
	$lang =& JFactory::getLanguage();
	$sql = "SET lc_time_names = '".str_replace("-", "_", $lang->getTag())."';";		
	$database->setQuery($sql);
	if (!$database->query()) {
		echo $database -> getErrorMsg();
	}

		// find requests
		$sql = "SELECT #__sv_apptpro2_requests.*, #__sv_apptpro2_resources.resource_admins, ".
			"#__sv_apptpro2_resources.name as resname, ".
			"CONCAT(#__sv_apptpro2_requests.startdate,#__sv_apptpro2_requests.starttime) as startdatetime, ".
			" IF(#__sv_apptpro2_requests.startdate > curdate(),'no','yes') as expired, ";
			if($apptpro_config->timeFormat == "12"){
				$sql = $sql." DATE_FORMAT(#__sv_apptpro2_requests.startdate, '%a %b %e, %Y') as display_startdate, ".
				"DATE_FORMAT(#__sv_apptpro2_requests.starttime, '%l:%i %p') as display_starttime, ".
				"DATE_FORMAT(#__sv_apptpro2_requests.enddate, '%b %e, %Y') as display_enddate, ".
				"DATE_FORMAT(#__sv_apptpro2_requests.endtime, '%l:%i %p') as display_endtime ";
			} else {
				$sql = $sql." DATE_FORMAT(#__sv_apptpro2_requests.startdate, '%a %b %e, %Y') as display_startdate, ".
				"DATE_FORMAT(#__sv_apptpro2_requests.starttime, '%k:%i') as display_starttime, ".
				"DATE_FORMAT(#__sv_apptpro2_requests.enddate, '%b %e, %Y') as display_enddate, ".
				"DATE_FORMAT(#__sv_apptpro2_requests.endtime, '%k:%i') as display_endtime ";
			}
			$sql = $sql." FROM #__sv_apptpro2_requests INNER JOIN #__sv_apptpro2_resources ".
				"ON #__sv_apptpro2_requests.resource = #__sv_apptpro2_resources.id_resources ".
			"WHERE request_status!='deleted' AND ";
			if($filter != ""){
				$sql = $sql." request_status='".$filter."' AND ";
			}
			if($startdateFilter != ""){
				$sql = $sql." startdate>='".$startdateFilter."' AND ";
			}
			if($enddateFilter != ""){
				$sql = $sql." enddate<='".$enddateFilter."' AND ";
			}
			$sql = $sql."#__sv_apptpro2_requests.user_id = ".$user->id.
//			" AND CONCAT(#__sv_apptpro2_requests.startdate, ' ', #__sv_apptpro2_requests.starttime) >= NOW() ".
		" ORDER BY ".$ordering.' '.$direction;
		//echo $sql;	
		$database->setQuery($sql);
		$rows = NULL;
		$rows = $database -> loadObjectList();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
			return false;
		}
		
		// check for credit activity
		if($user->id != ""){
			$sql = "SELECT #__sv_apptpro2_user_credit_activity.*, #__users.name as operator, #__sv_apptpro2_requests.startdate, ";
			if($apptpro_config->timeFormat == "12"){
				$sql .= "DATE_FORMAT(#__sv_apptpro2_requests.startdate, '%b %e') as display_startdate, ".
				"DATE_FORMAT(#__sv_apptpro2_requests.starttime, '%l:%i %p') as display_starttime, ";
			} else {
				$sql .= "DATE_FORMAT(#__sv_apptpro2_requests.startdate, '%b %e') as display_startdate, ".
				"DATE_FORMAT(#__sv_apptpro2_requests.starttime, '%H:%i') as display_starttime, ";				
			}
			$sql .= "#__sv_apptpro2_resources.description as resource ".
				"FROM #__sv_apptpro2_user_credit_activity ".
				"  INNER JOIN #__users ON #__sv_apptpro2_user_credit_activity.operator_id = #__users.id ".
				"  LEFT OUTER JOIN #__sv_apptpro2_requests ON #__sv_apptpro2_user_credit_activity.request_id = #__sv_apptpro2_requests.id_requests ".
				"  LEFT OUTER JOIN #__sv_apptpro2_resources ON #__sv_apptpro2_requests.resource = #__sv_apptpro2_resources.id_resources ".
				"WHERE #__sv_apptpro2_user_credit_activity.user_id = ".$user->id." ORDER BY stamp desc LIMIT 20";
			$database->setQuery($sql);
			$activity_rows = $database -> loadObjectList();
			if ($database -> getErrorNum()) {
				echo $database -> stderr();
				return false;
			}
		}
		
		$database = &JFactory::getDBO(); 
		$sql = 'SELECT * FROM #__sv_apptpro2_config';
		$database->setQuery($sql);
		$apptpro_config = NULL;
		$apptpro_config = $database -> loadObject();
		if ($database -> getErrorNum()) {
			echo "DB Err: ". $database -> stderr();
			return false;
		}
	
		$div_cal = "";
		if($apptpro_config->use_div_calendar == "Yes"){
			$div_cal = "'testdiv1'";
		}

		// get statuses
		$sql = "SELECT * FROM #__sv_apptpro2_status WHERE internal_value!='deleted' ORDER BY ordering ";
		//echo $sql;
		$database->setQuery($sql);
		$statuses = $database -> loadObjectList();
		if ($database -> getErrorNum()) {
			echo "DB Err: ". $database -> stderr();
			return false;
		}

	} else{
		echo "<font color='red'>".JText::_('RS1_MYBOOKINGS_SCRN_NO_LOGIN')."</font>";
	}
?>
<script language="JavaScript" src="./components/com_rsappt_pro2/script.js"></script>
<link href="./components/com_rsappt_pro2/sv_apptpro.css" rel="stylesheet">
<div id="testdiv1" style="VISIBILITY: hidden; POSITION: absolute; BACKGROUND-COLOR: white; layer-background-color: white"> </div>
<link href="./components/com_rsappt_pro2/sv_apptpro.css" rel="stylesheet">
<link href="./components/com_rsappt_pro2/calStyles.css" rel="stylesheet">
<script language="JavaScript" src="./components/com_rsappt_pro2/CalendarPopup.js"></script>
<script language="JavaScript">
	var cal = new CalendarPopup( <?php echo $div_cal ?>);
	cal.setWeekStartDay(<?php echo $apptpro_config->popup_week_start_day ?>);
	// to set css for popup calendar uncomment next line and change calStyles.css
	cal.setCssPrefix("TEST");
	
</script>
<script language="javascript">
	function doFilter(){
		document.adminForm.submit();
	}
	
	function call_doCancel(cancel_id, row){
		document.getElementById("cancellation_id").value = cancel_id;
		if(doCancel()){
			document.getElementById(row).innerHTML = "";
		}
	}

	function call_doDelete(delete_id, row){
		document.getElementById("cancellation_id").value = delete_id;
		if(doDelete()){
			document.getElementById(row).innerHTML = "";
		}
	}

	function cleardate(){
		document.getElementById("startdateFilter").value="";
		document.getElementById("enddateFilter").value="";
		document.adminForm.submit();
		return false;		
	}
	
</script>

<form action="<?php echo JRoute::_($this->request_url) ?>" method="post" name="adminForm">
    <table width="100%">
        <tr>
          <td align="left"> <h3><?php echo JText::_('RS1_MYBOOKINGS_SCRN_TITLE');?></h3></td>
          <td align="right"><?php echo $user->name ?></td>
        </tr>
        <tr>
          <td align="left"><?php echo JText::_('RS1_ADMIN_SCRN_DATEFILTER');?>&nbsp;
			<input type="text" size="10" maxsize="10" name="startdateFilter" id="startdateFilter" readonly="readonly" style="font-size:11px"
            value="<?php echo $startdateFilter; ?>" onchange="this.form.submit();"/>
            <a href="#" id="anchor1" onclick="cal.select(document.forms['adminForm'].startdateFilter,'anchor1','yyyy-MM-dd'); return false;"
                         name="anchor1"><img height="15" hspace="2" src="./components/com_rsappt_pro2/icon_cal.gif" width="16" border="0"></a>
			&nbsp;
			<input type="text" size="10" maxsize="10" name="enddateFilter" id="enddateFilter" readonly="readonly" style="font-size:11px"
            value="<?php echo $enddateFilter; ?>" onchange="this.form.submit();"/>
            <a href="#" id="anchor2" onclick="cal.select(document.forms['adminForm'].enddateFilter,'anchor2','yyyy-MM-dd'); return false;"
                         name="anchor2"><img height="15" hspace="2" src="./components/com_rsappt_pro2/icon_cal.gif" width="16" border="0"></a>
            <a href="#" onclick="cleardate();"><?php echo JText::_('RS1_ADMIN_SCRN_DATEFILTER_CLEAR');?></a>&nbsp;&nbsp;
            </td>
          	<td align="right"><select name="status_filter" onchange="doFilter()" style="font-size:11px">
            <option value=""><?php echo JText::_('RS1_ADMIN_SCRN_REQUEST_STATUS_NONE');?></option>
			<?php foreach($statuses as $status_row){ ?>
                <option value="<?php echo $status_row->internal_value ?>" <?php if($filter == $status_row->internal_value){echo " selected='selected' ";} ?>><?php echo JText::_($status_row->status);?></option>        
            <?php } ?>
            </select>
          </td>
        </tr>
        <tr>
          <td align="left"><div id="cancel_results"></div></td>
        </tr>
    </table>
  <table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
    <tr class="adminheading"  bgcolor="#F4F4F4">
      <th class="title" align="left"><?php echo JHTML::_( 'grid.sort', JText::_('RS1_MYBOOKINGS_SCRN_RESID_COL_HEAD'), 'resname', $direction, $ordering); ?></th>
      <th class="title" align="left"><?php echo JHTML::_( 'grid.sort', JText::_('RS1_MYBOOKINGS_SCRN_DATE_COL_HEAD'), 'startdatetime', $direction, $ordering); ?></th>
      <th class="title" align="left"><?php echo JHTML::_( 'grid.sort', JText::_('RS1_MYBOOKINGS_SCRN_FROM_COL_HEAD'), 'starttime', $direction, $ordering); ?></th>
      <th class="title" align="left"><?php echo JHTML::_( 'grid.sort', JText::_('RS1_MYBOOKINGS_SCRN_UNTIL_COL_HEAD'), 'endtime', $direction, $ordering); ?></th>
      <th class="title" align="center"><?php echo JHTML::_( 'grid.sort', JText::_('RS1_MYBOOKINGS_SCRN_SEATS_HEAD'), 'booked_seats', $direction, $ordering); ?></th>
      <?php //if($apptpro_config->allow_cancellation == "Yes" OR $apptpro_config->allow_cancellation == "BEO" ){?>
      <th class="title" align="left">&nbsp;</th>
	  <?php //} ?>
      <th class="title" align="center"><?php echo JHTML::_( 'grid.sort', JText::_('RS1_ADMIN_SCRN_STATUS_COL_HEAD'), 'request_status', $direction, $ordering); ?></th>
    </tr>
    <?php
	$k = 0;
	for($i=0; $i < count( $rows ); $i++) {
	$row = $rows[$i];
   ?>
    <tr class="<?php echo "row$k"; ?>">
      <td align="left"><?php echo JText::_(stripslashes($row->resname)); ?>&nbsp;</td>
      <td align="left"><?php echo $row->display_startdate; ?></td>
      <td align="left"><?php echo $row->display_starttime; ?> </td>
      <td align="left"><?php echo $row->display_endtime; ?> </td>
      <td align="center"><?php echo $row->booked_seats; ?> </td>
      <td align="left"><div id="row<?php echo $i?>">
      <?php if($row->expired == "yes"){ ?>
	          <a href="#" onclick="call_doDelete('<?php echo $row->cancellation_id; ?>', 'row<?php echo $i?>'); return false;" ><?php echo JText::_('RS1_INPUT_SCRN_DELETE_BUTTON');?></a> </div></td>
      <?php } elseif($apptpro_config->allow_cancellation == "Yes" OR $apptpro_config->allow_cancellation == "BEO" ){
				if($row->request_status != 'canceled'){?>
				  <a href="#" onclick="call_doCancel('<?php echo $row->cancellation_id; ?>', 'row<?php echo $i?>' ); return false;" ><?php echo JText::_('RS1_INPUT_SCRN_CANCEL_BUTTON');?></a> </div></td>
				<?php } else { ?> 
				  &nbsp; </div></td>
				<?php } ?>
	  <?php } else { ?>
		  &nbsp; </div></td>
	  <?php } ?>
      <td align="center"><?php echo translated_status($row->request_status); ?></td>
      <?php $k = 1 - $k; ?>
    </tr>
    <?php } 

?>
  </table>

<?php if(count($activity_rows)>0){ ?>

<br /><br /><br />
<hr />
  <?php echo JText::_('RS1_ADMIN_SCRN_CREDIT_ACTIVITY_INTRO');?><br />
  <table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
	<thead>
    <tr>
      <th class="title" align="left"><?php echo JText::_('RS1_ADMIN_CREDIT_ACTIVITY_COMMENT_COL_HEAD'); ?></th>
      <th class="title" align="center"><?php echo JText::_('RS1_ADMIN_CREDIT_ACTIVITY_BOOKING_COL_HEAD'); ?></th>
      <th class="title" align="center"><?php echo JText::_('RS1_ADMIN_CREDIT_ACTIVITY_INCREASE_COL_HEAD'); ?></th>
      <th class="title" align="center"><?php echo JText::_('RS1_ADMIN_CREDIT_ACTIVITY_DECREASE_COL_HEAD'); ?></th>
      <th class="title" align="center"><?php echo JText::_('RS1_ADMIN_CREDIT_ACTIVITY_BALANCE_COL_HEAD'); ?></th>
      <th width="5%" class="title" align="left"><?php echo JText::_('RS1_ADMIN_CREDIT_ACTIVITY_OPERATOR_COL_HEAD'); ?></th>
      <th class="title" align="left"><?php echo JText::_('RS1_ADMIN_CREDIT_ACTIVITY_TIMESTAMP_COL_HEAD'); ?></th>
    </tr>
    </thead>
    <?php
	$k = 0;
	for($i=0; $i < count( $activity_rows ); $i++) {
	$activity_row = $activity_rows[$i];
   ?>
    <tr class="<?php echo "row$k"; ?>">
      <td align="left"><?php echo stripslashes($activity_row->comment); ?>&nbsp;</td>
      <?php if($activity_row->request_id != ""){ ?>
      <td align="left"><?php echo $activity_row->display_startdate." / ".$activity_row->display_starttime; ?>&nbsp;- <?php echo JText::_(stripslashes($activity_row->resource)); ?></td>
      <?php } else { ?>
      <td align="center">&nbsp;</td>
      <?php } ?>
      <td align="center"><?php echo $activity_row->increase; ?>&nbsp;</td>
      <td align="center"><?php echo $activity_row->decrease; ?>&nbsp;</td>
      <td align="center"><?php echo $activity_row->balance; ?>&nbsp;</td>
      <td align="center"><?php echo $activity_row->operator; ?>&nbsp;</td>
      <td align="center"><?php echo $activity_row->stamp; ?></td>
      <?php $k = 1 - $k; ?>
    </tr>
    <?php } ?>
  </table>
<?php } ?>

  <p></p><p class="row0"><?php 
  if($apptpro_config->allow_cancellation == "Yes" OR $apptpro_config->allow_cancellation == "BEO" ){
  	//echo JText::_('RS1_MYBOOKINGS_SCRN_CANCEL_HOWTO');
	}?></p>
  <input type="hidden" name="option" value="<?php echo $option; ?>" />
  <input type="hidden" name="task" value="" />
  <input type="hidden" name="view" id="view" value="mybookings" />
  <input type="hidden" name="boxchecked" value="0" />
  <input type="hidden" name="cancellation_id" id="cancellation_id"  />
  <input type="hidden" id="wait_text" value="<?php echo JText::_('RS1_INPUT_SCRN_PLEASE_WAIT');?>" />
  <input type="hidden" name="filter_order" value="<?php echo $ordering ?>" />
  <input type="hidden" name="filter_order_Dir" value ="<?php echo $direction ?>" />
  <br />
  <?php if($apptpro_config->hide_logo == 'No'){ ?>
    <span style="font-size:10px"> Appointment Booking Pro Ver. 2.0 - Copyright 2008-20<?php echo date("y");?> - <a href='http://www.softventures.com' target="_blank">Soft Ventures, Inc.</a></span>
  <?php } ?>
</form>
