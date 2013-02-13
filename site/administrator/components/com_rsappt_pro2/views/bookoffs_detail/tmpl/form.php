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

	$cur_res = JRequest::getVar( 'resource_id' );
	// Get resources for dropdown list
	$database = &JFactory::getDBO();
	$database->setQuery("SELECT * FROM #__sv_apptpro2_resources ORDER BY name" );
	$res_rows = $database -> loadObjectList();
	if ($database -> getErrorNum()) {
		echo $database -> stderr();
		return false;
	}
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
<div id="testdiv1" style="VISIBILITY: hidden; POSITION: absolute; BACKGROUND-COLOR: white; layer-background-color: white"> </div>
<link href="../components/com_rsappt_pro2/calStyles.css" rel="stylesheet">
<script language="JavaScript" src="../components/com_rsappt_pro2/CalendarPopup.js"></script>
<script language="JavaScript" src="../components/com_rsappt_pro2/date.js"></script>
<script language="JavaScript">
	var now = new Date();
	var cal = new CalendarPopup( <?php echo $div_cal ?>);
	cal.setCssPrefix("TEST");
	cal.setWeekStartDay(<?php echo $apptpro_config->popup_week_start_day ?>);


function submitbutton(pressbutton) {
	var ok = "yes";
   	if (pressbutton == 'save'){
		if(document.getElementById("resource_id").selectedIndex == 0){
			alert("Please select a resource.");
			ok = "no";
		}
		if(document.getElementById("off_date").value == ""){
			alert("Please select a date.");
			ok = "no";
		}
		if(document.getElementById("off_date2")!=null){
			if(ok == "yes" && document.getElementById("off_date2").value != ""){				
				// need to create a series or bookoffs			
				// limit to max 14 days
//				if(Date.parse(document.getElementById("off_date2").value) > Date.parse(document.getElementById("off_date").value).add(14).days()){
//					alert("Maximum number of book-off days that can be created at one time is 15");
//					ok="no";
//				}
				pressbutton = 'create_bookoff_series'
			}
		}
		if(ok == "yes"){
			submitform(pressbutton);
		}
	} else {
		submitform(pressbutton);
	}		
}

function setbookoffstarttime(){
	document.getElementById("bookoff_starttime").value = document.getElementById("bookoff_starttime_hour").value + ":" + document.getElementById("bookoff_starttime_minute").value + ":00";
}
function setbookoffendtime(){
	document.getElementById("bookoff_endtime").value = document.getElementById("bookoff_endtime_hour").value + ":" + document.getElementById("bookoff_endtime_minute").value + ":00";
}
</script>
<script type="text/javascript" src="../includes/js/overlib_mini.js"></script>        

<form action="<?php echo JRoute::_($this->request_url) ?>" method="post" name="adminForm" id="adminForm">
<link href="../components/com_rsappt_pro2/sv_apptpro.css" rel="stylesheet">
<fieldset class="adminform">
  <table border="0" cellpadding="2" cellspacing="0">
    <tr>
      <td colspan="3">
        <p><?php echo JText::_('RS1_ADMIN_SCRN_BOOKOFF_DETAIL_INTRO');?><br /></td>
    </tr>
    <tr class="admin_detail_row0">
      <td width="20%"><?php echo JText::_('RS1_ADMIN_SCRN_BOOKOFF_DETAIL_ID');?></td>
      <td><?php echo $this->detail->id_bookoffs ?></td>
      <td>&nbsp;</td>
    </tr>
    <tr class="admin_detail_row1">
      <td><?php echo JText::_('RS1_ADMIN_SCRN_BO_RESOURCE');?></td>
      <td colspan="3">
      <?php if($this->detail->resource_id == ""){ ?>
	      <select name="resource_id" id="resource_id">
          <option value="0" ><?php echo JText::_('RS1_ADMIN_SCRN_BO_RESOURCE_SEL');?></option>
              <?php
				$k = 0;
				for($i=0; $i < count( $res_rows ); $i++) {
				$res_row = $res_rows[$i];
				?>
        	  <option value="<?php echo $res_row->id_resources; ?>"  <?php if($cur_res == $res_row->id_resources){echo " selected='selected' ";} ?>><?php echo stripslashes($res_row->name); ?></option>
              <?php $k = 1 - $k; 
				} ?>
    	  </select>
      <?php } else { ?>
      			<input type="hidden" name="resource_id" id="resource_id" value=<?php echo $this->detail->resource_id;?> />
              <?php
				$k = 0;
				for($i=0; $i < count( $res_rows ); $i++) {
					$res_row = $res_rows[$i];
					if($this->detail->resource_id == $res_row->id_resources){
        	  			echo stripslashes($res_row->name);
              		}
					$k = 1 - $k; 
				}     		
      		} 
			?>    
      &nbsp; </td>
    </tr>
    <tr class="admin_detail_row0">
      <td><?php echo JText::_('RS1_ADMIN_SCRN_BO_DATE');?></td>
      <td colspan="3"><input type="text" size="12" maxsize="10" readonly="readonly" name="off_date" id="off_date" value="<?php echo $this->detail->off_date; ?>" />
		        <a href="#" id="anchor1" onclick="cal.select(document.forms['adminForm'].off_date,'anchor1','yyyy-MM-dd'); return false;"
					 name="anchor1"><img height="15" hspace="2" src="../components/com_rsappt_pro2/icon_cal.gif" width="16" border="0"></a>
           <?php if($this->detail->id_bookoffs == ""){ ?>
            &nbsp;<?php echo JText::_('RS1_ADMIN_SCRN_BO_DATE_TO');?>&nbsp;<input type="text" size="12" maxsize="10" readonly="readonly" name="off_date2" id="off_date2" value="" />
		        <a href="#" id="anchor2" onclick="cal.select(document.forms['adminForm'].off_date2,'anchor2','yyyy-MM-dd'); return false;"
					 name="anchor2"><img height="15" hspace="2" src="../components/com_rsappt_pro2/icon_cal.gif" width="16" border="0"></a>
            &nbsp;<img src="../includes/js/ThemeOffice/tooltip.png" border="0" 
            onmouseover="return overlib('<?php echo JText::_('RS1_ADMIN_SCRN_BO_DATE_HELP');?>', CAPTION, '<?php echo JText::_('RS1_ADMIN_SCRN_BO_DATE');?>', BELOW, RIGHT);" 
            onmouseout="return nd();" />
            <?php } ?>       </td>
    </tr>
    <tr class="admin_detail_row1">
      <td><?php echo JText::_('RS1_ADMIN_SCRN_BO_FULLDAY');?> </td>
      <td><select name="full_day">
            <option value="Yes" <?php if($this->detail->full_day == "Yes"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_YES');?></option>
            <option value="No" <?php if($this->detail->full_day == "No"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_NO');?></option>
            </select>      </td>
      <td rowspan="4"><?php echo JText::_('RS1_ADMIN_SCRN_BO_RANGE_HELP');?></td>
    </tr>
    <tr class="admin_detail_row1">
      <td><?php echo JText::_('RS1_ADMIN_SCRN_BO_RANGE_START');?> </td>
      <td><select name="bookoff_starttime_hour" id="bookoff_starttime_hour" onchange="setbookoffstarttime();" class="sv_ts_request_dropdown" >
		<?php
		for($x=0; $x<24; $x+=1){
			if($x<10){
				$x = "0".$x;
			}
			echo "<option value=".$x; if(substr($this->detail->bookoff_starttime,0,2) == $x) {echo " selected='selected' ";} echo ">".$x." </option>";  
		}
		?>
        </select> : 
		<select name="bookoff_starttime_minute" id="bookoff_starttime_minute" onchange="setbookoffstarttime();" class="sv_ts_request_dropdown" >
		<?php
		for($x=0; $x<59; $x+=5){
			if($x<10){
				$x = "0".$x;
			}
			echo "<option value=".$x; if(substr($this->detail->bookoff_starttime,3,2) == $x) {echo " selected='selected' ";} echo ">".$x." </option>";  
		}
		?>
        </select>
		<?php echo JText::_('RS1_ADMIN_SCRN_HHMM');?>
        <input type="hidden" name="bookoff_starttime" id="bookoff_starttime" value="<?php echo $this->detail->bookoff_starttime ?>" />      </td>
    </tr>
    <tr class="admin_detail_row1">
      <td><?php echo JText::_('RS1_ADMIN_SCRN_BO_RANGE_END');?> </td>
      <td><select name="bookoff_endtime_hour" id="bookoff_endtime_hour" onchange="setbookoffendtime();" class="sv_ts_request_dropdown" >
		<?php
		for($x=0; $x<24; $x+=1){
			if($x<10){
				$x = "0".$x;
			}
			echo "<option value=".$x; if(substr($this->detail->bookoff_endtime,0,2) == $x) {echo " selected='selected' ";} echo ">".$x." </option>";  
		}
		?>
        </select> : 
		<select name="bookoff_endtime_minute" id="bookoff_endtime_minute" onchange="setbookoffendtime();" class="sv_ts_request_dropdown" >
		<?php
		for($x=0; $x<59; $x+=5){
			if($x<10){
				$x = "0".$x;
			}
			echo "<option value=".$x; if(substr($this->detail->bookoff_endtime,3,2) == $x) {echo " selected='selected' ";} echo ">".$x." </option>";  
		}
		?>
        </select>
		<?php echo JText::_('RS1_ADMIN_SCRN_HHMM');?>
        <input type="hidden" name="bookoff_endtime" id="bookoff_endtime" value="<?php echo $this->detail->bookoff_endtime ?>" />      </td>
    </tr>
    <tr class="admin_detail_row1">
      <td>&nbsp;</td>
      <td>&nbsp;</td>

    </tr>
    <tr class="admin_detail_row0">
      <td><?php echo JText::_('RS1_ADMIN_SCRN_BO_DESC');?> </td>
      <td><input type="text" size="60" maxsize="80" name="description" value="<?php echo stripslashes($this->detail->description); ?>" /></td>
      <td><?php echo JText::_('RS1_ADMIN_SCRN_BO_DESC_HELP');?>&nbsp;</td>
    </tr>
    <tr class="admin_detail_row1">
        <td ><?php echo JText::_('RS1_ADMIN_SCRN_BOOKOFF_DETAIL_PUBLISHED');?></td>
        <td>
            <select name="published">
            <option value="0" <?php if($this->detail->published == "0"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_NO');?></option>
            <option value="1" <?php if($this->detail->published == "1"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_YES');?></option>
            </select>        </td>
        <td>&nbsp;</td>
    </tr>
    <tr class="admin_detail_row0">
      <td colspan="3" ><br /></td>
    </tr>  
  </table>

</fieldset>
  <input type="hidden" name="id_bookoffs" value="<?php echo $this->detail->id_bookoffs; ?>" />
  <input type="hidden" name="task" value="" />
  <input type="hidden" name="controller" value="bookoffs_detail" />
  <br />
  <span style="font-size:10px"> Appointment Booking Pro Ver. 2.0 - Copyright 2008-20<?php echo date("y");?> - <a href='http://www.softventures.com' target="_blank">Soft Ventures, Inc.</a></span>
</form>
