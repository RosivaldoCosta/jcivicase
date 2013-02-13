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

//DEVNOTE: import html tooltips
JHTML::_('behavior.tooltip');

//Ordering allowed ?
$ordering = ($this->lists['order'] == 'ordering');
	$user =& JFactory::getUser();
	 
	if(!$user->guest){

		$database = &JFactory::getDBO();
		
		// check to see id user is an admin		
		$sql = "SELECT count(*) as count FROM #__sv_apptpro2_resources WHERE ".
			"resource_admins LIKE '%|".$user->id."|%';";
		$database->setQuery($sql);
		$check = NULL;
		$check = $database -> loadObject();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
			return false;
		}
		if($check->count == 0){
			echo "<font color='red'>".JText::_('RS1_ADMIN_SCRN_NOT_ADMIN')."</font>";
		}	
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

	} else{
		echo "<font color='red'>".JText::_('RS1_ADMIN_SCRN_NO_LOGIN')."</font>";
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

	// get statuses
	$sql = "SELECT * FROM #__sv_apptpro2_status ORDER BY ordering ";
	//echo $sql;
	$database->setQuery($sql);
	$statuses = $database -> loadObjectList();
	if ($database -> getErrorNum()) {
		echo "DB Err: ". $database -> stderr();
		return false;
	}
	
?>

<div id="testdiv1" style="VISIBILITY: hidden; POSITION: absolute; BACKGROUND-COLOR: white; layer-background-color: white"> </div>
<link href="<?php echo $this->baseurl;?>/components/com_rsappt_pro2/calStyles.css" rel="stylesheet">
<link href="<?php echo $this->baseurl;?>/components/com_rsappt_pro2/sv_apptpro.css" rel="stylesheet">
<script language="JavaScript" src="<?php echo $this->baseurl;?>/components/com_rsappt_pro2/CalendarPopup.js"></script>
<script language="JavaScript">
	var now = new Date();
	var cal = new CalendarPopup( <?php echo $div_cal ?>);
	cal.setCssPrefix("TEST");
	cal.setWeekStartDay(<?php echo $apptpro_config->popup_week_start_day ?>);
	cal.setMonthNames(<?php echo "'".JText::_('RS1_JANUARY').
		"','".JText::_('RS1_FEBRUARY').
		"','".JText::_('RS1_MARCH').
		"','".JText::_('RS1_APRIL').
		"','".JText::_('RS1_MAY').
		"','".JText::_('RS1_JUNE').
		"','".JText::_('RS1_JULY').
		"','".JText::_('RS1_AUGUST').
		"','".JText::_('RS1_SEPTEMBER').
		"','".JText::_('RS1_OCTOBER').
		"','".JText::_('RS1_NOVEMBER').
		"','".JText::_('RS1_DECEMBER')."'"?>);

	cal.setDayHeaders(<?php echo "'".JText::_('RS1_SUN_HEADER').
		"','".JText::_('RS1_MON_HEADER').
		"','".JText::_('RS1_TUE_HEADER').
		"','".JText::_('RS1_WED_HEADER').
		"','".JText::_('RS1_THU_HEADER').
		"','".JText::_('RS1_FRI_HEADER').
		"','".JText::_('RS1_SAT_HEADER')."'"?>);
</script>
<script>
	function cleardate(){
		document.getElementById("startdateFilter").value="";
		document.getElementById("enddateFilter").value="";
		submitbutton('');
		return false;		
	}
</script>

<form action="<?php echo JRoute::_($this->request_url) ?>" method="post" name="adminForm" >
     <table width="100%">
        <tr>
          <td colspan="2" align="left"> <h3><?php echo JText::_('RS1_ADMIN_SCRN_TITLE');?></h3></td>
          <td width="20%" align="right"><?php echo $user->name ?></td>
        </tr>
        <tr>
          <td width="15%"><!--<a href="javascript:sendReminders();"><?php echo JText::_('RS1_ADMIN_SCRN_SEND_REMINDERS');?></a>--></td>
          <td colspan="2" align="right">
			<?php echo JText::_('RS1_ADMIN_SCRN_DATEFILTER');?>&nbsp;
			<input type="text" size="12" maxsize="12" name="startdateFilter" id="startdateFilter" readonly="readonly" style="font-size:11px" 
                value="<?php echo $this->filter_startdate; ?>" onchange="this.form.submit();"/>
                <a href="#" id="anchor1" onclick="cal.select(document.forms['adminForm'].startdateFilter,'anchor1','yyyy-MM-dd'); return false;"
                             name="anchor1"><img height="15" hspace="2" src="<?php echo $this->baseurl;?>/components/com_rsappt_pro2/icon_cal.gif" width="16" border="0"></a>
                <input type="text" size="12" maxsize="12" name="enddateFilter" id="enddateFilter" readonly="readonly" style="font-size:11px" 
                    value="<?php echo $this->filter_enddate; ?>" onchange="this.form.submit();"/>
                <a href="#" id="anchor2" onclick="cal.select(document.forms['adminForm'].enddateFilter,'anchor2','yyyy-MM-dd'); return false;"
                             name="anchor2"><img height="15" hspace="2" src="<?php echo $this->baseurl;?>/components/com_rsappt_pro2/icon_cal.gif" width="16" border="0"></a>        
                <a href="#" onclick="cleardate();"><?php echo JText::_('RS1_ADMIN_SCRN_DATEFILTER_CLEAR');?></a>&nbsp;&nbsp;			
            <select name="request_resourceFilter" id="request_resourceFilter" onchange="this.form.submit();" style="font-size:11px"  >
              <option value="0" <?php if($this->filter_request_resource == "0"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_REQUEST_RESOURCE_NONE');?></option>
              <?php
				$k = 0;
				for($i=0; $i < count( $res_rows ); $i++) {
				$res_row = $res_rows[$i];
				?>
              <option value="<?php echo $res_row->id_resources; ?>" <?php if($this->filter_request_resource == $res_row->id_resources){echo " selected='selected' ";} ?>><?php echo stripslashes($res_row->name); ?></option>
              <?php $k = 1 - $k; 
				} ?>
            </select>&nbsp;&nbsp;
            <select name="request_status" onchange="this.form.submit();" style="font-size:11px"  >
              <option value="all" <?php if($this->filter_request_status == "all"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_REQUEST_STATUS_NONE');?></option>
			<?php foreach($statuses as $status_row){ ?>
                <option value="<?php echo $status_row->internal_value ?>" <?php if($this->filter_request_status == $status_row->internal_value){echo " selected='selected' ";} ?>><?php echo JText::_($status_row->status);?></option>        
            <?php } ?>
            </select>&nbsp;&nbsp;      </td>
        </tr>
    </table> 
  <table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminheading">
    <tr bgcolor="#F4F4F4" >
      <th class="title" width="3%"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" /></th>
      <th class="title" align="left"><?php echo JHTML::_( 'grid.sort', JText::_('RS1_ADMIN_SCRN_NAME_COL_HEAD'), 'name', $this->lists['order_Dir'], $this->lists['order']); ?></th>
      <th class="title" align="left"><?php echo JHTML::_( 'grid.sort', JText::_('RS1_ADMIN_SCRN_EMAIL_COL_HEAD'), 'email', $this->lists['order_Dir'], $this->lists['order']); ?></th>
      <th class="title" align="left"><?php echo JHTML::_( 'grid.sort', JText::_('RS1_ADMIN_SCRN_RESID_COL_HEAD'), 'ResourceName', $this->lists['order_Dir'], $this->lists['order']); ?></th>
      <th class="title" align="left"><?php echo JHTML::_( 'grid.sort', JText::_('RS1_ADMIN_SCRN_FROM_COL_HEAD'), 'startdatetime', $this->lists['order_Dir'], $this->lists['order']); ?></th>
      <th class="title" align="left"><?php echo JHTML::_( 'grid.sort', JText::_('RS1_ADMIN_SCRN_SERVICE_COL_HEAD'), 'ServiceName', $this->lists['order_Dir'], $this->lists['order']); ?></th>
      <th class="title" align="center"><?php echo JHTML::_( 'grid.sort', JText::_('RS1_ADMIN_SCRN_STATUS_COL_HEAD'), 'request_status', $this->lists['order_Dir'], $this->lists['order']); ?></th>
      <th class="title" align="center"><?php echo JHTML::_( 'grid.sort', JText::_('RS1_ADMIN_SCRN_PAYMENT_COL_HEAD'), 'payment_status', $req_direction, $req_ordering, "req_"  ); ?></th>
    </tr> 
	<?php
	$k = 0;
	for($i=0; $i < count( $this->items ); $i++) {
	$row = $this->items[$i];
	$link 	= JRoute::_( 'index.php?option=com_rsappt_pro2&controller=admin_detail&task=edit&cid[]='. $row->id_requests.'&frompage=admin');

	?>
    <tr class="<?php echo "row$k"; ?>">
      <td align="center"><input type="checkbox" id="cb<?php echo $i;?>" name="cid[]" value="<?php echo $row->id; ?>" onclick="isChecked(this.checked);" /></td>
      <td><a href=<?php echo $link; ?>><?php echo stripslashes($row->name); ?></a></td>
      <td align="left"><a href="mailto:<?php echo $row->email; ?>"><?php echo $row->email; ?></a></td>
      <td align="left"><?php echo JText::_(stripslashes($row->ResourceName)); ?>&nbsp;</td>
      <td align="left"><?php echo $row->display_startdate; ?>&nbsp;<?php echo $row->display_starttime; ?> </td>
      <td align="left"><?php echo JText::_(stripslashes($row->ServiceName)); ?> </td>
      <td align="center"><?php echo translated_status($row->request_status); ?></td>
      <td align="center"><?php echo translated_status($row->payment_status); ?></td>
      <?php $k = 1 - $k; ?>
    </tr>
    <?php } 

?>
<!--<tfoot>
   	<td colspan="12"><?php echo $this->pagination->getListFooter(); ?></td>
    </tfoot>
--></table>

  <input type="hidden" name="controller" value="admin" />
  <input type="hidden" name="task" value="" />
  <input type="hidden" name="boxchecked" value="0" />
  <input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
  <input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
  <input type="hidden" name="hidemainmenu" value="0" />  

  <br />
  <?php if($apptpro_config->hide_logo == 'No'){ ?>
    <span style="font-size:10px"> Appointment Booking Pro Ver. 2.0 - Copyright 2008-20<?php echo date("y");?> - <a href='http://www.softventures.com' target="_blank">Soft Ventures, Inc.</a></span>
  <?php } ?>
</form>
