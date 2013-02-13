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

	$showform= true;
	$listpage = JRequest::getVar('listpage', 'list');
	$frompage = JRequest::getVar('frompage', '');
	$fromtab = JRequest::getVar('fromtab', '');
	$timeslots_tocopy = JRequest::getVar('timeslots_tocopy', '');

	$id = JRequest::getString( 'id', '' );
	$itemid = JRequest::getString( 'Itemid', '' );
	$option = JRequest::getString( 'option', '' );

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
	
?>
<?php if($showform){?>

<div id="testdiv1" style="VISIBILITY: hidden; POSITION: absolute; BACKGROUND-COLOR: white; layer-background-color: white"> </div>
<link href="./components/com_rsappt_pro2/sv_apptpro.css" rel="stylesheet">
<link href="./components/com_rsappt_pro2/calStyles.css" rel="stylesheet">
<script language="JavaScript" src="./components/com_rsappt_pro2/script.js"></script>
<script language="JavaScript" src="./components/com_rsappt_pro2/CalendarPopup.js"></script>
<script language="JavaScript">
	var cal = new CalendarPopup(<?php echo $div_cal ?>);
	cal.setWeekStartDay(<?php echo $apptpro_config->popup_week_start_day ?>);
	cal.setCssPrefix("TEST");

		
	function doCancel(){
		submitform("cancel");
	}		
	
	function doCopyNow(){
		if(document.getElementById('dest_resource_id').selectedIndex == 0){
			alert('<?php echo JText::_('RS1_ADMIN_SCRN_SERV_RESOURCE_SELECT');?>');
			return(false);
		}
		submitform("do_copy_timeslots");
	}
	

	
	</script>
<form action="<?php echo JRoute::_($this->request_url) ?>" method="post" name="adminForm" id="adminForm" class="adminForm">
<link href="../components/com_rsappt_pro2/sv_apptpro.css" rel="stylesheet">
<table width="100%" >
    <tr>
      <td align="left" colspan="2"> <h3><?php echo JText::_('RS1_ADMIN_SCRN_TITLE').JText::_('RS1_ADMIN_SCRN_RESOURCE_TIMESLOT_COPY_TITLE');?></h3></td>
    </tr>
</table>
<table border="0" cellpadding="4" cellspacing="0">
   <tr>
      <td colspan="4" align="right" height="40px" 
      style="background-color:#FFFFCC; border-top:solid #333333 1px;border-bottom:solid #333333 1px">
      <a href="#" onclick="doCopyNow()"><?php echo JText::_('RS1_ADMIN_SCRN_BTN_COPYNOW');?></a>
      &nbsp;|&nbsp;&nbsp;<a href="#" onclick="doCancel()"><?php echo JText::_('RS1_ADMIN_SCRN_BTN_CANCEL');?></a>&nbsp;&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3">
      <?php echo JText::_('RS1_ADMIN_SCRN_TIMESLOT_COPY_INTRO');?> </td>
    </tr>
    <tr>
      <td style="border-bottom:solid #333333 1px"><?php echo JText::_('RS1_ADMIN_SCRN_TIMESLOT_COPY_DEST_RESOURCE');?></td>
      <td style="border-bottom:solid #333333 1px"><?php echo JText::_('RS1_ADMIN_SCRN_TIMESLOT_COPY_DEST_DAYS');?></td>
    </tr>
    <tr>
      <td ><select name="dest_resource_id" id="dest_resource_id" class="sv_apptpro2_request_text">
              <option value="0" ><?php echo JText::_('RS1_ADMIN_SCRN_SERV_RESOURCE_SELECT');?></option>
              <?php
				$k = 0;
				for($i=0; $i < count( $res_rows ); $i++) {
				$res_row = $res_rows[$i];
				?>
              <option value="<?php echo $res_row->id_resources; ?>"  <?php if(JRequest::getVar( 'resource_id_FilterTS' ) == $res_row->id_resources){echo " selected='selected' ";} ?>><?php echo stripslashes($res_row->name); ?></option>
              <?php $k = 1 - $k; 
				} ?>
      </select>&nbsp;</td>
      <td ><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr align="center">
            <td><?php echo JText::_('RS1_ADMIN_SCRN_SUN');?></td>
            <td><?php echo JText::_('RS1_ADMIN_SCRN_MON');?></td>
            <td><?php echo JText::_('RS1_ADMIN_SCRN_TUE');?></td>
            <td><?php echo JText::_('RS1_ADMIN_SCRN_WED');?></td>
            <td><?php echo JText::_('RS1_ADMIN_SCRN_THU');?></td>
            <td><?php echo JText::_('RS1_ADMIN_SCRN_FRI');?></td>
            <td><?php echo JText::_('RS1_ADMIN_SCRN_SAT');?></td>
          </tr>
          <tr align="center">
            <td><input type="checkbox" name="chkSunday" id="chkSunday" /></td>
            <td><input type="checkbox" name="chkMonday" id="chkMonday" /></td>
            <td><input type="checkbox" name="chkTuesday" id="chkTuesday" /></td>
            <td><input type="checkbox" name="chkWednesday" id="chkWednesday" /></td>
            <td><input type="checkbox" name="chkThursday" id="chkThursday" /></td>
            <td><input type="checkbox" name="chkFriday" id="chkFriday" /></td>
            <td><input type="checkbox" name="chkSaturday" id="chkSaturday" /></td>
            <td></td>
            <td></td>
          </tr>
        </table>
        <?php echo JText::_('RS1_ADMIN_SCRN_TIMESLOT_COPY_NOTE1');?>      </td>
    </tr>
    <tr>
      <td >&nbsp;</td>
      <td style="border-top:solid #666 1px"><table width="100%" border="0" cellspacing="2" cellpadding="2">
        <tr>
          <td><?php echo JText::_('RS1_ADMIN_SCRN_TIMESLOT_COPY_PUB_START');?>:</td>
          <td><input type="text" size="12" maxsize="10" readonly="readonly" name="new_start_publishing" id="new_start_publishing" value="" />
		        <a href="#" id="anchor1" onclick="cal.select(document.forms['adminForm'].new_start_publishing,'anchor1','yyyy-MM-dd'); return false;"
					 name="anchor1"><img height="15" hspace="2" src="./components/com_rsappt_pro2/icon_cal.gif" width="16" border="0"></a></td>
        </tr>
        <tr>
          <td><?php echo JText::_('RS1_ADMIN_SCRN_TIMESLOT_COPY_PUB_END');?>:</td>
          <td><input type="text" size="12" maxsize="10" readonly="readonly" name="new_end_publishing" id="new_end_publishing" value="" />
		        <a href="#" id="anchor2" onclick="cal.select(document.forms['adminForm'].new_end_publishing,'anchor2','yyyy-MM-dd'); return false;"
					 name="anchor2"><img height="15" hspace="2" src="./components/com_rsappt_pro2/icon_cal.gif" width="16" border="0"></a></td>
        </tr>
        <tr>
        <td colspan="2"><?php echo JText::_('RS1_ADMIN_SCRN_TIMESLOT_COPY_PUB_HELP');?></td>
      </table></td>
    </tr>

  </table>
  <br />
  <?php echo JText::_('RS1_ADMIN_SCRN_TIMESLOT_COPY_NOTE2');?>
	<input type="hidden" name="option" value="<?php echo $option; ?>" />
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="current_tab" id="current_tab" value="<?php echo $fromtab; ?>" />
    <input type="hidden" name="controller" value="admin_detail" />
    <input type="hidden" name="id" value="<?php echo $this->user_id; ?>" />
    <input type="hidden" name="user" id="user" value="<?php echo $user->id; ?>" />
    <input type="hidden" name="frompage" value="<?php echo $frompage ?>" />
    <input type="hidden" name="frompage_item" value="<?php echo $itemid ?>" />
    <input type="hidden" name="fromtab" value="<?php echo $fromtab ?>" />
	<input type="hidden" name="timeslots_tocopy" value="<?php echo $timeslots_tocopy; ?>" />
  <br />
      <?php if($apptpro_config->hide_logo == 'No'){ ?>
        <span style="font-size:10px"> Appointment Booking Pro Ver. 2.0 - Copyright 2008-20<?php echo date("y");?> - <a href='http://www.softventures.com' target="_blank">Soft Ventures, Inc.</a></span>
      <?php } ?>
</form>
<?php } ?>
