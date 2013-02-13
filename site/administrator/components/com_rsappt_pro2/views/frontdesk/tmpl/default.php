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

	JHTML::_('behavior.tooltip');
	jimport( 'joomla.application.helper' );

	$user =& JFactory::getUser();
	if($user->get('usertype') != 'Super Administrator')
	{
?><script type="text/javascript">elem = document.getElementById("submenu"); elem.parentNode.removeChild(elem);</script>
<?php
	}
	$itemid = JRequest::getString( 'Itemid', '' );
	$option = JRequest::getString( 'option', '' );


	$front_desk_view = $this->front_desk_view;
	$front_desk_resource_filter = $this->front_desk_resource_filter;
	$front_desk_status_filter = $this->front_desk_status_filter;
	$front_desk_user_search = $this->front_desk_user_search;

	$front_desk_cur_week_offset = $this->front_desk_cur_week_offset;
	$front_desk_cur_day = $this->front_desk_cur_day;
	$front_desk_cur_month = $this->front_desk_cur_month;
	$front_desk_cur_year = $this->front_desk_cur_year;


	$retore_settings = "";
	switch($front_desk_view){
		case "month":
			if($front_desk_cur_month != ""){
				$retore_settings = "'', '".$front_desk_cur_month."', '".$front_desk_cur_year."', ''";
			}
			break;
		case "week":
			if($front_desk_cur_week_offset != ""){
				$retore_settings = "'', '', '', '".$front_desk_cur_week_offset."'";
			}
			break;
		case "day":
			if($front_desk_cur_day != ""){
				$retore_settings = "'".$front_desk_cur_day."', '', '', ''";
			}
			break;
	}


	$showform= true;

	if(!$user->guest){

		$database = &JFactory::getDBO();
		// get resources
		$sql = "SELECT * FROM #__sv_apptpro2_resources ".
		"WHERE resource_admins LIKE '%|".$user->id."|%' and Published=1 ".
		"ORDER BY ordering;";
		//echo $sql;
		$database->setQuery($sql);
		$res_rows = $database -> loadObjectList();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
			return false;
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
		echo "<font color='red'>".JText::_('RS1_ADMIN_SCRN_NO_LOGIN')."</font>";

		$showform = false;
	}

	// add new booking link
	$link 	= JRoute::_( 'index.php?option=com_rsappt_pro2&controller=frontdesk&task=add_booking&frompage=frontdesk&Itemid='.$itemid);

?>

<?php if($showform){?>
<link href="<?php // echo $this->baseurl;?>../components/com_rsappt_pro2/sv_apptpro.css" rel="stylesheet">
<script language="JavaScript" src="<?php // echo $this->baseurl;?>../components/com_rsappt_pro2/script.js"></script>
<script language="javascript">
	window.onload = function() {
		buildFrontDeskView( <?php echo $retore_settings ?>);
	}

	function goManifest(resid, startdate, starttime, endtime){
//		document.getElementById("redirect").value="manifest";
		document.getElementById("resid").value=resid;
		document.getElementById("startdate").value=startdate;
		document.getElementById("starttime").value=starttime;
		document.getElementById("endtime").value=endtime;
		submitbutton('display_manifest');
		return false;
	}

	function toggleTotals(){
		if(document.getElementById("cur_day") != null){
			buildFrontDeskView( document.getElementById("cur_day").value);
		}
	}

	function goDayView(day){
		document.getElementById("front_desk_view").selectedIndex=0;
		buildFrontDeskView(day);
	}

	function sendReminders(which){
		if(which=="Email"){
			submitbutton('reminders');
		} else {
			submitbutton('reminders_sms');
		}
		return false;
	}

	function doSearch(){
/*		if(document.getElementById("user_search").value==""){
			alert("<?php echo JText::_('RS1_FRONTDESK_SCRN_SEARCH_HELP');?>");
			return false;
		}*/
		buildFrontDeskView();
	}


</script>
<div id="sv_apptpro2_front_desk">
<form name="adminForm" action="<?php echo JRoute::_($this->request_url) ?>" method="post">
    <table width="100%">
        <!--<tr>
          <td align="left" colspan="2"> <h3><?php echo JText::_('RS1_FRONTDESK_SCRN_TITLE');?></h3></td>
          <td align="right"><?php echo $user->name ?></td>
        </tr>
        <tr><td colspan="3"><div id="reminder_links" style="visibility:hidden; display:none; text-align:right">
			<a href="javascript:sendReminders('Email');"><?php echo JText::_('RS1_ADMIN_SCRN_SEND_REMINDERS');?></a>&nbsp;|&nbsp;
			<a href="javascript:sendReminders('SMS');"><?php echo JText::_('RS1_ADMIN_SCRN_SEND_REMINDERS_SMS');?></a>&nbsp;
        	</div></td>
        <tr>-->
         <td align="left">&nbsp;&nbsp;<select id="front_desk_view" name="front_desk_view" onchange="buildFrontDeskView()" style="font-size:11px">
            <option value="day" <?php if($front_desk_view == "day"){ echo " selected ";}?>><?php echo JText::_('RS1_FRONTDESK_SCRN_VIEW_DAY');?></option>
            <option value="week" <?php if($front_desk_view == "week"){ echo " selected ";}?>><?php echo JText::_('RS1_FRONTDESK_SCRN_VIEW_WEEK');?></option>
            <option value="month" <?php if($front_desk_view == "month"){ echo " selected ";}?>><?php echo JText::_('RS1_FRONTDESK_SCRN_VIEW_MONTH');?></option>
            </select> </td>
          <td align="right"></td>
          <td align="right"><input type="text" id="user_search" name="user_search" size="20" class="sv_apptpro2_request_text"
          	title="<?php echo JText::_('RS1_FRONTDESK_SCRN_SEARCH_HELP');?>" value="<?php echo $front_desk_user_search_filter ?>" />
            <input type="button" onclick="doSearch();" class="sv_apptpro2_request_text" value="<?php echo JText::_('RS1_FRONTDESK_SCRN_SEARCH');?>" /></td>
        </tr>
        <tr>
          <td colspan="2">&nbsp;&nbsp;<!--<a href="<?php echo $link ?>"><?php echo JText::_('RS1_FRONTDESK_SCRN_ADDNEW');?></a>--></td>
          <td align="right">
			<!--<input type="checkbox" id="showSeatTotals" name="showSeatTotals" onclick="toggleTotals();"/><?php echo JText::_('RS1_FRONTDESK_SCRN_SHOW_SEAT_TOTALS');?>&nbsp;&nbsp;&nbsp;&nbsp;-->
            <select name="resource_filter" id="resource_filter" onchange="buildFrontDeskView();" style="font-size:11px" >&nbsp;&nbsp;
            <option value=""><?php echo JText::_('RS1_ADMIN_SCRN_REQUEST_RESOURCE_NONE');?></option>
              <?php
				$k = 0;
				for($i=0; $i < count( $res_rows ); $i++) {
				$res_row = $res_rows[$i];
				?>
              <option value="<?php echo $res_row->id_resources; ?>" <?php if($front_desk_resource_filter == $res_row->id_resources){echo " selected='selected' ";} ?>><?php echo JText::_(stripslashes($res_row->name)); ?></option>
              <?php $k = 1 - $k;
				} ?>
            </select>            &nbsp;&nbsp;
          <!--<select id="status_filter" name="status_filter" onchange="buildFrontDeskView()" style="font-size:11px">
            <option value=""><?php echo JText::_('RS1_ADMIN_SCRN_REQUEST_STATUS_NONE');?></option>
			<?php foreach($statuses as $status_row){ ?>
                <option value="<?php echo $status_row->internal_value ?>" <?php if($front_desk_status_filter == $status_row->internal_value){echo " selected='selected' ";} ?> class="color_<?php echo $status_row->internal_value ?>" ><?php echo JText::_($status_row->status);?></option>
            <?php } ?>
            </select>-->&nbsp;&nbsp;
           </td>
        </tr>
    </table>

<div id="calview_here">&nbsp;</div>

<input type="hidden" name="id" id="id" value="<?php echo $row->id; ?>">
<input type="hidden" name="uid" id="uid" value="<?php echo $user->id; ?>">
<input type="hidden" id="script_path" name="script_path" value="<?php echo SCRIPTPATH?>" />
<input type="hidden" name="redirect" id="redirect" value="" />
<input type="hidden" name="listpage" id="listpage" value="frontdesk" />
<input type="hidden" name="startdate" id="startdate" value="" />
<input type="hidden" name="starttime" id="starttime" value="" />
<input type="hidden" name="endtime" id="endtime" value="" />
<input type="hidden" name="resid" id="resid" value="" />

  	<input type="hidden" name="option" value="<?php echo $option; ?>" />
  	<input type="hidden" name="controller" value="frontdesk" />
	<input type="hidden" name="id" value="<?php echo $this->user_id; ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="frompage" value="frontdesk" />
  	<input type="hidden" name="frompage_item" id="frompage_item" value="<?php echo $itemid ?>" />

  <br />
  <?php if($apptpro_config->hide_logo == 'No'){ ?>
    <span style="font-size:10px"> Appointment Booking Pro Ver. 2.0 - Copyright 2008-20<?php echo date("y");?> - <a href='http://www.softventures.com' target="_blank">Soft Ventures, Inc.</a></span>
  <?php } ?>
</form>
</div>
<?php } ?>

