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
	$frompage = JRequest::getVar('frompage', '');
	$fromtab = JRequest::getVar('fromtab', '');
	$bookoffs_tocopy = JRequest::getVar('bookoffs_tocopy', '');
	
	
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
	//cal.setDisabledWeekDays(0,6);

		
	function doCancel(){
		submitform("cancel");
	}		
	
	function doCopyNow(){
		if(document.getElementById('dest_resource_id[]').selectedIndex == -1){
			alert('<?php echo JText::_('RS1_ADMIN_SCRN_BOOKOFF_COPY_SELECT_DEST');?>');
			return(false);
		}
		submitform("do_copy_bookoffs");
	}
	

	
	</script>
<form action="<?php echo JRoute::_($this->request_url) ?>" method="post" name="adminForm" id="adminForm" class="adminForm">
<link href="../components/com_rsappt_pro2/sv_apptpro.css" rel="stylesheet">
<table width="100%" >
    <tr>
      <td align="left" colspan="2"> <h3><?php echo JText::_('RS1_ADMIN_SCRN_TITLE').JText::_('RS1_ADMIN_SCRN_RESOURCE_BOOKOFFS_COPY_TITLE');?></h3></td>
    </tr>
</table>
<table border="0" cellpadding="4" cellspacing="0">
   <tr>
      <td colspan="3" align="right" height="40px" 
      style="background-color:#FFFFCC; border-top:solid #333333 1px;border-bottom:solid #333333 1px">
      <a href="#" onclick="doCopyNow()"><?php echo JText::_('RS1_ADMIN_SCRN_BTN_COPYNOW');?></a>
      &nbsp;|&nbsp;&nbsp;<a href="#" onclick="doCancel()"><?php echo JText::_('RS1_ADMIN_SCRN_BTN_CANCEL');?></a>&nbsp;&nbsp;</td>
    </tr>
    <tr>
      <td>
      <?php echo JText::_('RS1_ADMIN_SCRN_BOOKOFF_COPY_INTRO');?> </td>
    </tr>
    <tr>
      <td width="322" ><?php echo JText::_('RS1_ADMIN_SCRN_BOOKOFF_COPY_DEST');?></td>
    </tr>
    <tr>
      <td ><p>
        <select name="dest_resource_id[]" id="dest_resource_id[]"  class="sv_apptpro2_request_text" style="background-color:#FFFFCC" size="10" multiple="multiple">
          <?php
				$k = 0;
				for($i=0; $i < count( $res_rows ); $i++) {
				$res_row = $res_rows[$i];
				?>
          <option value="<?php echo $res_row->id_resources; ?>"  <?php if($row->resource_id == $res_row->id_resources){echo " selected='selected' ";} ?>><?php echo stripslashes($res_row->name); ?></option>
          <?php $k = 1 - $k; 
				} ?>
        </select>
      &nbsp;</p>
      <p><?php echo JText::_('RS1_ADMIN_SCRN_BOOKOFF_COPY_SELECT_DEST');?></p></td>
    </tr>
  </table>
  <p>&nbsp;</p>
  <p>
	<input type="hidden" name="option" value="<?php echo $option; ?>" />
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="current_tab" id="current_tab" value="<?php echo $fromtab; ?>" />
    <input type="hidden" name="controller" value="admin_detail" />
    <input type="hidden" name="id" value="<?php echo $this->user_id; ?>" />
    <input type="hidden" name="user" id="user" value="<?php echo $user->id; ?>" />
    <input type="hidden" name="frompage" value="<?php echo $frompage ?>" />
    <input type="hidden" name="frompage_item" value="<?php echo $itemid ?>" />
    <input type="hidden" name="fromtab" value="<?php echo $fromtab ?>" />
	<input type="hidden" name="bookoffs_tocopy" value="<?php echo $bookoffs_tocopy; ?>" />
  <br />
      <?php if($apptpro_config->hide_logo == 'No'){ ?>
        <span style="font-size:10px"> Appointment Booking Pro Ver. 2.0 - Copyright 2008-20<?php echo date("y");?> - <a href='http://www.softventures.com' target="_blank">Soft Ventures, Inc.</a></span>
      <?php } ?>
</form>
<?php } ?>
