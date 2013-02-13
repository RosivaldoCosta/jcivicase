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

	// Get resources for dropdown list
	$database = &JFactory::getDBO();
	$database->setQuery("SELECT * FROM #__sv_apptpro2_resources ORDER BY name" );
	$res_rows = $database -> loadObjectList();
	if ($database -> getErrorNum()) {
		echo $database -> stderr();
		return false;
	}

	if($this->detail->day_number == "all"){
		$day_number = $this->detail->day_number;
	} else {
		$day_number = $this->detail->day_number;
	}
	$resource = $this->detail->resource_id;
	
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

?>

<script language="javascript">
function submitbutton(pressbutton) {
	submitform(pressbutton);
}
</script>
<div id="testdiv1" style="VISIBILITY: hidden; POSITION: absolute; BACKGROUND-COLOR: white; layer-background-color: white"> </div>
<link href="../components/com_rsappt_pro2/calStyles.css" rel="stylesheet">
<script language="JavaScript" src="../components/com_rsappt_pro2/CalendarPopup.js"></script>
<script language="JavaScript" src="../components/com_rsappt_pro2/date.js"></script>
<script language="JavaScript">
	var now = new Date();
	var cal = new CalendarPopup( <?php echo $div_cal ?>);
	cal.addDisabledDates(null,formatDate(now,"yyyy-MM-dd")); 
	cal.showYearNavigation();
	cal.setCssPrefix("TEST");
	cal.setWeekStartDay(<?php echo $apptpro_config->popup_week_start_day ?>);
</script>
<script>
	function setstarttime(){
		document.getElementById("timeslot_starttime").value = document.getElementById("timeslot_starttime_hour").value + ":" + document.getElementById("timeslot_starttime_minute").value + ":00";
	}
	function setendtime(){
		document.getElementById("timeslot_endtime").value = document.getElementById("timeslot_endtime_hour").value + ":" + document.getElementById("timeslot_endtime_minute").value + ":00";
	}
</script>

<form action="<?php echo JRoute::_($this->request_url) ?>" method="post" name="adminForm" id="adminForm">
<link href="../components/com_rsappt_pro2/sv_apptpro.css" rel="stylesheet">
<fieldset class="adminform">
  <table border="0" cellpadding="2" cellspacing="0">
    <tr>
      <td colspan="3"><?php echo JText::_('RS1_ADMIN_SCRN_TIMESLOT_DETAIL_INTRO');?></td>
    </tr>
    <tr class="admin_detail_row0">
      <td width="20%"><?php echo JText::_('RS1_ADMIN_SCRN_TIMESLOT_DETAIL_ID');?></td>
      <td><?php echo $this->detail->id_timeslots ?></td>
      <td>&nbsp;</td>
    </tr>
    <tr class="admin_detail_row1">
      <td><?php echo JText::_('RS1_ADMIN_SCRN_TIMESLOT_DETAIL_RESOURCE');?></td>
      <td ><select name="resource_id" >
          <option value="0" ><?php echo JText::_('RS1_ADMIN_SCRN_TIMESLOT_DETAIL_GLOBAL');?></option>
              <?php
				$k = 0;
				for($i=0; $i < count( $res_rows ); $i++) {
				$res_row = $res_rows[$i];
				?>
          <option value="<?php echo $res_row->id_resources; ?>"  <?php if($resource == $res_row->id_resources){echo " selected='selected' ";} ?>><?php echo stripslashes($res_row->name); ?></option>
              <?php $k = 1 - $k; 
				} ?>
      </select>
      &nbsp;</td>
      <td><?php echo JText::_('RS1_ADMIN_SCRN_TIMESLOT_DETAIL_RESOURCE_HELP');?></td>
    </tr>
    <tr class="admin_detail_row0">
      <td><?php echo JText::_('RS1_ADMIN_SCRN_TIMESLOT_DETAIL_DAY');?></td>
      <td colspan="3"><select name="day_number">
          <option value="0" <?php if($day_number == "0"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_SUNDAY');?></option>
          <option value="1" <?php if($day_number == "1"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_MONDAY');?></option>
          <option value="2" <?php if($day_number == "2"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_TUESDAY');?></option>
          <option value="3" <?php if($day_number == "3"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_WEDNESDAY');?></option>
          <option value="4" <?php if($day_number == "4"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_THURSDAY');?></option>
          <option value="5" <?php if($day_number == "5"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_FRIDAY');?></option>
          <option value="6" <?php if($day_number == "6"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_SATURDAY');?></option>
        </select></td>
    </tr>
    <tr class="admin_detail_row1">
      <td><?php echo JText::_('RS1_ADMIN_SCRN_TIMESLOT_DETAIL_START');?> </td>
      <td><select name="timeslot_starttime_hour" id="timeslot_starttime_hour" onchange="setstarttime();" class="sv_ts_request_dropdown" <?php if($this->detail->hoursLimit == '24Hour'){ echo ' disabled ' ;} ?>>
		<?php
		for($x=0; $x<24; $x+=1){
/*			if($x==12){
				echo "<option value=".$x.":00:00"; if($this->detail->timeslot_starttime == $x.":00:00") {echo " selected='selected' ";} echo ">Noon</option>";  
				echo "<option value=".$x.":15:00"; if($this->detail->timeslot_starttime == $x.":15:00") {echo " selected='selected' ";} echo ">".$x.":15 </option>";  
				echo "<option value=".$x.":30:00"; if($this->detail->timeslot_starttime == $x.":30:00") {echo " selected='selected' ";} echo ">".$x.":30 </option>";  
				echo "<option value=".$x.":45:00"; if($this->detail->timeslot_starttime == $x.":45:00") {echo " selected='selected' ";} echo ">".$x.":45 </option>";  
			} else if($x==24){
				echo "<option value=".$x.":00:00"; if($this->detail->timeslot_starttime == $x.":00:00") {echo " selected='selected' ";} echo ">Midnight</option>";  
			} else {
				if($x<10){
					$x = "0".$x;
				}
				echo "<option value=".$x.":00:00"; if($this->detail->timeslot_starttime == $x.":00:00") {echo " selected='selected' ";} echo ">".$x.":00 </option>";  
				echo "<option value=".$x.":15:00"; if($this->detail->timeslot_starttime == $x.":25:00") {echo " selected='selected' ";} echo ">".$x.":15 </option>";  
				echo "<option value=".$x.":30:00"; if($this->detail->timeslot_starttime == $x.":30:00") {echo " selected='selected' ";} echo ">".$x.":30 </option>";  
				echo "<option value=".$x.":45:00"; if($this->detail->timeslot_starttime == $x.":45:00") {echo " selected='selected' ";} echo ">".$x.":45 </option>";  
			}
*/
			if($x<10){
				$x = "0".$x;
			}
			echo "<option value=".$x; if(substr($this->detail->timeslot_starttime,0,2) == $x) {echo " selected='selected' ";} echo ">".$x." </option>";  
		}
		?>
        </select> : 
		<select name="timeslot_starttime_minute" id="timeslot_starttime_minute" onchange="setstarttime();" class="sv_ts_request_dropdown" <?php if($this->detail->hoursLimit == '24Hour'){ echo ' disabled ' ;} ?>>
		<?php
		for($x=0; $x<59; $x+=5){
			if($x<10){
				$x = "0".$x;
			}
			echo "<option value=".$x; if(substr($this->detail->timeslot_starttime,3,2) == $x) {echo " selected='selected' ";} echo ">".$x." </option>";  
		}
		?>
        </select>        
         <?php echo JText::_('RS1_ADMIN_SCRN_TIMESLOT_DETAIL_HINT');?>
        <input type="hidden" name="timeslot_starttime" id="timeslot_starttime" value="<?php echo $this->detail->timeslot_starttime ?>" />      </td>
      <td>&nbsp;</td>
    </tr>
    <tr class="admin_detail_row0">
      <td><?php echo JText::_('RS1_ADMIN_SCRN_TIMESLOT_DETAIL_END');?></td>
      <td colspan="3"><select name="timeslot_endtime_hour" id="timeslot_endtime_hour" onchange="setendtime();" class="sv_ts_request_dropdown" <?php if($this->detail->hoursLimit == '24Hour'){ echo ' disabled ' ;} ?>>
		<?php
		for($x=0; $x<24; $x+=1){
			if($x<10){
				$x = "0".$x;
			}
			echo "<option value=".$x; if(substr($this->detail->timeslot_endtime,0,2) == $x) {echo " selected='selected' ";} echo ">".$x." </option>";  
		}
		?>
        </select>  : 
		<select name="timeslot_endtime_minute" id="timeslot_endtime_minute" onchange="setendtime();" class="sv_ts_request_dropdown" <?php if($this->detail->hoursLimit == '24Hour'){ echo ' disabled ' ;} ?>>
		<?php
		for($x=0; $x<59; $x+=5){
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
      <td><?php echo JText::_('RS1_ADMIN_SCRN_TIMESLOT_DETAIL_DESC_HELP');?></td>
    </tr>
    
    <tr class="admin_detail_row0">
      <td><?php echo JText::_('RS1_ADMIN_SCRN_TS_PUBSTART_DATE');?></td>
      <td><input type="text" size="12" maxsize="10" readonly="readonly" name="start_publishing" id="start_publishing" value="<?php echo $this->detail->start_publishing; ?>" />
		        <a href="#" id="anchor3785" onclick="cal.select(document.forms['adminForm'].start_publishing,'anchor3785','yyyy-MM-dd'); return false;"
					 name="anchor3785"><img height="15" hspace="2" src="../components/com_rsappt_pro2/icon_cal.gif" width="16" border="0"></a>
	  </td>
      <td><?php echo JText::_('RS1_ADMIN_SCRN_TS_PUBSTART_DATE_HELP');?></td>
    </tr>
    <tr class="admin_detail_row1">
      <td><?php echo JText::_('RS1_ADMIN_SCRN_TS_PUBEND_DATE');?></td>
      <td><input type="text" size="12" maxsize="10" readonly="readonly" name="end_publishing" id="end_publishing" value="<?php echo $this->detail->end_publishing; ?>" />
		        <a href="#" id="anchor3786" onclick="cal.select(document.forms['adminForm'].end_publishing,'anchor3786','yyyy-MM-dd'); return false;"
					 name="anchor3786"><img height="15" hspace="2" src="../components/com_rsappt_pro2/icon_cal.gif" width="16" border="0"></a>
	  </td>
      <td><?php echo JText::_('RS1_ADMIN_SCRN_TS_PUBEND_DATE_HELP');?></td>
    </tr>
    <tr class="admin_detail_row0">
        <td ><?php echo JText::_('RS1_ADMIN_SCRN_TIMESLOT_DETAIL_PUBLISHED');?></td>
        <td>
            <select name="published">
            <option value="0" <?php if($this->detail->published == "0"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_NO');?></option>
            <option value="1" <?php if($this->detail->published == "1"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_YES');?></option>
            </select>        </td>
        <td>&nbsp;</td>
    </tr>
    <tr class="admin_detail_row0">
      <td colspan="3" ><br /><?php echo JText::_('RS1_ADMIN_SCRN_TIMESLOT_DETAIL_NOTES');?></td>
    </tr>  
  </table>

</fieldset>
  <input type="hidden" name="id_timeslots" value="<?php echo $this->detail->id_timeslots; ?>" />
  <input type="hidden" name="task" value="" />
  <input type="hidden" name="controller" value="timeslots_detail" />
  <br />
  <span style="font-size:10px"> Appointment Booking Pro Ver. 2.0 - Copyright 2008-20<?php echo date("y");?> - <a href='http://www.softventures.com' target="_blank">Soft Ventures, Inc.</a></span>
</form>
