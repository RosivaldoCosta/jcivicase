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

defined('_JEXEC') or die('Restricted access');

JHTML::_('behavior.tooltip');

	$showform= true;
	$listpage = JRequest::getVar('listpage', 'list');
//	if($listpage == 'list'){
//		$savepage = 'srv_save';
//	} else {
//		$savepage = 'srv_save_adv_admin';
//	}
//	$current_tab = JRequest::getVar('current_tab', '');

	$id = JRequest::getString( 'id', '' );
	$itemid = JRequest::getString( 'Itemid', '' );
	$option = JRequest::getString( 'option', '' );

//	$current_tab = JRequest::getVar('current_tab', '');
//	$resource = JRequest::getVar('resource_id_FilterTS', '');
//	// see below $day_number = JRequest::getVar('day_numberFilter', '');

	$user =& JFactory::getUser();
	if($user->guest){
		echo "<font color='red'>".JText::_('RS1_ADMIN_SCRN_NO_LOGIN')."</font>";
		$showform = false;
	} else {
		$database = &JFactory::getDBO(); 
		$user =& JFactory::getUser();

		// get resources
		$sql = "SELECT * FROM #__sv_apptpro2_resources ".
		"WHERE resource_admins LIKE '%|".$user->id."|%' and published=1 ".
		"ORDER BY ordering;";
		//echo $sql;
		$database->setQuery($sql);
		$res_rows = $database -> loadObjectList();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
			return false;
		}
	
	
//		if($id != ""){
//			$sql = "SELECT * FROM #__sv_apptpro2_timeslots ".
//				"WHERE id_timeslots = ".$id;
//			$database->setQuery($sql);
//			$row = $database -> loadObject();
//			if ($database -> getErrorNum()) {
//				echo $database -> stderr();
//				return false;
//			}
//			if($row->id==""){
//				echo "<font color='red'>".JText::_('RS1_ADMIN_SCRN_NO_ACCESS')."</font>";
//				$showform = false;
//			}
//		}
				
		// get config stuff
		$database = &JFactory::getDBO();
		$sql = 'SELECT * FROM #__sv_apptpro2_config';
		$database->setQuery($sql);
		$apptpro_config = NULL;
		$apptpro_config = $database -> loadObject();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
			return false;
		}

		$div_cal = "";
		if($apptpro_config->use_div_calendar == "Yes"){
			$div_cal = "'testdiv1'";
		}

	}	

	if(JRequest::getVar('day_numberFilter') == "all"){
		$day_number = $this->detail->day_number;
	} else {
		$day_number = JRequest::getVar('day_numberFilter');
	}
	
?>
<?php if($showform){?>
<div id="testdiv1" style="VISIBILITY: hidden; POSITION: absolute; BACKGROUND-COLOR: white; layer-background-color: white"> </div>
<link href="./components/com_rsappt_pro2/sv_apptpro.css" rel="stylesheet">
<link href="./components/com_rsappt_pro2/calStyles.css" rel="stylesheet">
<script language="JavaScript" src="./components/com_rsappt_pro2/script.js"></script>
<script language="JavaScript" src="./components/com_rsappt_pro2/CalendarPopup.js"></script>
<script language="JavaScript">
	var cal = new CalendarPopup(<?php echo $div_cal ?>);
	cal.setCssPrefix("TEST");
	cal.setWeekStartDay(<?php echo $apptpro_config->popup_week_start_day ?>);
	cal.showYearNavigation();

	function setstarttime(){
		document.getElementById("timeslot_starttime").value = document.getElementById("timeslot_starttime_hour").value + ":" + document.getElementById("timeslot_starttime_minute").value + ":00";
	}
	function setendtime(){
		document.getElementById("timeslot_endtime").value = document.getElementById("timeslot_endtime_hour").value + ":" + document.getElementById("timeslot_endtime_minute").value + ":00";
	}
		
	function doCancel(){
		submitform("cancel");
	}		
	
	function doSave(){
		if(document.getElementById('resource_id').selectedIndex == 0){
			alert('<?php echo JText::_('RS1_ADMIN_SCRN_SELECT_RESOURCE_ERR');?>');
			return(false);
		}
		submitform("save_timeslots_detail");
	}
	

	
	</script>
<form action="<?php echo JRoute::_($this->request_url) ?>" method="post" name="adminForm" id="adminForm" class="adminForm">
<link href="../components/com_rsappt_pro2/sv_apptpro.css" rel="stylesheet">
<table width="100%" >
    <tr>
      <td align="left" colspan="2"> <h3><?php echo JText::_('RS1_ADMIN_SCRN_TITLE').JText::_('RS1_ADMIN_SCRN_RESOURCE_TIMESLOT_TITLE');?></h3></td>
    </tr>
</table>
<table border="0" cellpadding="4" cellspacing="0">
   <tr>
      <td colspan="3" align="right" height="40px" 
      style="background-color:#FFFFCC; border-top:solid #333333 1px;border-bottom:solid #333333 1px">
      <a href="#" onclick="doSave()"><?php echo JText::_('RS1_ADMIN_SCRN_BTN_SAVE');?></a>
      &nbsp;|&nbsp;&nbsp;<a href="#" onclick="doCancel()"><?php echo JText::_('RS1_ADMIN_SCRN_BTN_CANCEL');?></a>&nbsp;&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2">
        <p><?php echo JText::_('RS1_ADMIN_SCRN_TIMESLOT_DETAIL_INTRO');?><br /></td>
    </tr>
    <tr class="admin_detail_row0">
      <td width="20%"><?php echo JText::_('RS1_ADMIN_SCRN_TIMESLOT_DETAIL_ID');?></td>
      <td><?php echo $this->detail->id ?></td>
    </tr>
    <tr class="admin_detail_row1">
      <td><?php echo JText::_('RS1_ADMIN_SCRN_TIMESLOT_DETAIL_RESOURCE');?></td>
      <td colspan="2"><select name="resource_id" id="resource_id" class="sv_apptpro2_request_text" >
          <option value="0" ><?php echo JText::_('RS1_ADMIN_SCRN_SERV_RESOURCE_SELECT');?></option>
              <?php
				$k = 0;
				for($i=0; $i < count( $res_rows ); $i++) {
				$res_row = $res_rows[$i];
				?>
          <option value="<?php echo $res_row->id_resources; ?>"  <?php if($this->detail->resource_id == $res_row->id_resources){echo " selected='selected' ";} ?>><?php echo JText::_(stripslashes($res_row->name)); ?></option>
              <?php $k = 1 - $k; 
				} ?>
      </select></td>
    </tr>
    <tr class="admin_detail_row0">
      <td><?php echo JText::_('RS1_ADMIN_SCRN_TIMESLOT_DETAIL_DAY');?></td>
      <td colspan="2"><select name="day_number" class="sv_apptpro2_request_text">
          <option value="0" <?php if($this->detail->day_number == "0"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_SUNDAY');?></option>
          <option value="1" <?php if($this->detail->day_number == "1"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_MONDAY');?></option>
          <option value="2" <?php if($this->detail->day_number == "2"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_TUESDAY');?></option>
          <option value="3" <?php if($this->detail->day_number == "3"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_WEDNESDAY');?></option>
          <option value="4" <?php if($this->detail->day_number == "4"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_THURSDAY');?></option>
          <option value="5" <?php if($this->detail->day_number == "5"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_FRIDAY');?></option>
          <option value="6" <?php if($this->detail->day_number == "6"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_SATURDAY');?></option>
        </select></td>
    </tr>
    <tr class="admin_detail_row1">
      <td><?php echo JText::_('RS1_ADMIN_SCRN_TIMESLOT_DETAIL_START');?></td>
      <td><select name="timeslot_starttime_hour" id="timeslot_starttime_hour" onchange="setstarttime();" class="sv_apptpro2_request_text" <?php if($this->detail->hoursLimit == '24Hour'){ echo ' disabled ' ;} ?>>
		<?php
		for($x=0; $x<24; $x+=1){
			if($x<10){
				$x = "0".$x;
			}
			echo "<option value=".$x; if(substr($this->detail->timeslot_starttime,0,2) == $x) {echo " selected='selected' ";} echo ">".$x." </option>";  
		}
		?>
        </select> : 
		<select name="timeslot_starttime_minute" id="timeslot_starttime_minute" onchange="setstarttime();" class="sv_apptpro2_request_text" <?php if($this->detail->hoursLimit == '24Hour'){ echo ' disabled ' ;} ?>>
		<?php
		for($x=0; $x<59; $x+=1){
			if($x<10){
				$x = "0".$x;
			}
			echo "<option value=".$x; if(substr($this->detail->timeslot_starttime,3,2) == $x) {echo " selected='selected' ";} echo ">".$x." </option>";  
		}
		?>
        </select>        
         <?php echo JText::_('RS1_ADMIN_SCRN_TIMESLOT_DETAIL_HINT');?>
        <input type="hidden" name="timeslot_starttime" id="timeslot_starttime" value="<?php echo $this->detail->timeslot_starttime ?>" />      </td>
    </tr>
    <tr class="admin_detail_row0">
      <td><?php echo JText::_('RS1_ADMIN_SCRN_TIMESLOT_DETAIL_END');?></td>
      <td colspan="2"><select name="timeslot_endtime_hour" id="timeslot_endtime_hour" onchange="setendtime();" class="sv_apptpro2_request_text" <?php if($this->detail->hoursLimit == '24Hour'){ echo ' disabled ' ;} ?>>
		<?php
		for($x=0; $x<24; $x+=1){
			if($x<10){
				$x = "0".$x;
			}
			echo "<option value=".$x; if(substr($this->detail->timeslot_endtime,0,2) == $x) {echo " selected='selected' ";} echo ">".$x." </option>";  
		}
		?>
        </select>  : 
		<select name="timeslot_endtime_minute" id="timeslot_endtime_minute" onchange="setendtime();" class="sv_apptpro2_request_text" <?php if($this->detail->hoursLimit == '24Hour'){ echo ' disabled ' ;} ?>>
		<?php
		for($x=0; $x<59; $x+=1){
			if($x<10){
				$x = "0".$x;
			}
			echo "<option value=".$x; if(substr($this->detail->timeslot_endtime,3,2) == $x) {echo " selected='selected' ";} echo ">".$x." </option>";  
		}
		?>
        </select>        
         <?php echo JText::_('RS1_ADMIN_SCRN_TIMESLOT_DETAIL_HINT');?>
        <input type="hidden" name="timeslot_endtime" id="timeslot_endtime" value="<?php echo $this->detail->timeslot_endtime ?>" />      </td>
    </tr>
    <tr class="admin_detail_row1">
      <td valign="top"><?php echo JText::_('RS1_ADMIN_SCRN_TIMESLOT_DETAIL_DESC');?></td>
      <td valign="top"><input type="text" size="30" maxsize="50" name="timeslot_description" value="<?php echo $this->detail->timeslot_description; ?>" />&nbsp;</td>
    </tr>
    <tr class="admin_detail_row0">
      <td><?php echo JText::_('RS1_ADMIN_SCRN_TS_PUBSTART_DATE');?></td>
      <td><input type="text" size="12" maxsize="10" readonly="readonly" name="start_publishing" id="start_publishing" value="<?php echo $this->detail->start_publishing; ?>" />
		        <a href="#" id="anchor3785" onclick="cal.select(document.forms['adminForm'].start_publishing,'anchor3785','yyyy-MM-dd'); return false;"
					 name="anchor3785"><img height="15" hspace="2" src="./components/com_rsappt_pro2/icon_cal.gif" width="16" border="0"></a>
	  </td>
      <td><?php echo JText::_('RS1_ADMIN_SCRN_TS_PUBSTART_DATE_HELP');?></td>
    </tr>
    <tr class="admin_detail_row1">
      <td><?php echo JText::_('RS1_ADMIN_SCRN_TS_PUBEND_DATE');?></td>
      <td><input type="text" size="12" maxsize="10" readonly="readonly" name="end_publishing" id="end_publishing" value="<?php echo $this->detail->end_publishing; ?>" />
		        <a href="#" id="anchor3786" onclick="cal.select(document.forms['adminForm'].end_publishing,'anchor3786','yyyy-MM-dd'); return false;"
					 name="anchor3786"><img height="15" hspace="2" src="./components/com_rsappt_pro2/icon_cal.gif" width="16" border="0"></a>
	  </td>
      <td><?php echo JText::_('RS1_ADMIN_SCRN_TS_PUBEND_DATE_HELP');?></td>
    </tr>
    <tr class="admin_detail_row0">
        <td ><?php echo JText::_('RS1_ADMIN_SCRN_TIMESLOT_DETAIL_PUBLISHED');?></td>
        <td>
            <select name="published" class="sv_apptpro2_request_text">
            <option value="0" <?php if($this->detail->published == "0"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_NO');?></option>
            <option value="1" <?php if($this->detail->published == "1"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_YES');?></option>
            </select>        </td>
    </tr>
    <tr class="admin_detail_row1">
      <td colspan="2" ><br />
        <?php echo JText::_('RS1_ADMIN_SCRN_TIMESLOT_DETAIL_NOTES');?>
        </td>
    </tr>  
  </table>
  <input type="hidden" name="id_timeslots" value="<?php echo $this->detail->id_timeslots; ?>" />
  <input type="hidden" name="option" value="<?php echo $option; ?>" />
  <input type="hidden" name="controller" value="admin_detail" />
  <input type="hidden" name="id" value="<?php echo $this->user_id; ?>" />
  <input type="hidden" name="task" value="" />
  <input type="hidden" name="user" id="user" value="<?php echo $user->id; ?>" />
  <input type="hidden" name="frompage" value="<?php echo $listpage ?>" />
  <input type="hidden" name="frompage_item" value="<?php echo $itemid ?>" />
  <input type="hidden" name="fromtab" value="<?php echo $this->fromtab ?>" />


  <br />
      <?php if($apptpro_config->hide_logo == 'No'){ ?>
        <span style="font-size:10px"> Appointment Booking Pro Ver. 2.0 - Copyright 2008-20<?php echo date("y");?> - <a href='http://www.softventures.com' target="_blank">Soft Ventures, Inc.</a></span>
      <?php } ?>
</form>
<?php } ?>
