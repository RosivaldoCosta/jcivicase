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

//	setSessionStuff("service");

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
	cal.setCssPrefix("TEST");
	cal.setWeekStartDay(<?php echo $apptpro_config->popup_week_start_day ?>);

		
	function doCancel(){
		submitform("cancel");
	}		
	
	function doSave(){
		if(document.getElementById('resource_id').selectedIndex == 0){
			alert('<?php echo JText::_('RS1_ADMIN_SCRN_SELECT_RESOURCE_ERR');?>');
			return(false);
		}
		if(document.getElementById('name').value == ""){
			alert('<?php echo JText::_('RS1_ADMIN_SCRN_NAME_ERR');?>');
			return(false);
		}
		submitform("save_services_detail");
	}
	

	
	</script>
<form action="<?php echo JRoute::_($this->request_url) ?>" method="post" name="adminForm" id="adminForm" class="adminForm">
<link href="../components/com_rsappt_pro2/sv_apptpro.css" rel="stylesheet">
<table width="100%" >
    <tr>
      <td align="left" colspan="2"> <h3><?php echo JText::_('RS1_ADMIN_SCRN_TITLE').JText::_('RS1_ADMIN_SCRN_RESOURCE_SERVICE_TITLE');?></h3></td>
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
        <?php echo JText::_('RS1_ADMIN_SCRN_SERVICE_DETAIL_INTRO');?><br /></td>
    </tr>
    <tr class="admin_detail_row0">
      <td width="25%"><?php echo JText::_('RS1_ADMIN_SCRN_SERVICE_DETAIL_ID');?></td>
      <td><?php echo $this->detail->id_services ?></td>
    </tr>
    <tr class="admin_detail_row1">
      <td><?php echo JText::_('RS1_ADMIN_SCRN_SERVICE_DETAIL_RESOURCE');?></td>
      <td colspan="2">
      <?php if($this->detail->resource_id == ""){ ?>
	      <select name="resource_id" id="resource_id" class="sv_apptpro_request_text">
          <option value="0" ><?php echo JText::_('RS1_ADMIN_SCRN_SERVICE_DETAIL_SEL_RESOURCE');?></option>
              <?php
				$k = 0;
				for($i=0; $i < count( $res_rows ); $i++) {
				$res_row = $res_rows[$i];
				?>
        	  <option value="<?php echo $res_row->id_resources; ?>"  <?php if($this->filter_services_resource == $res_row->id_resources){echo " selected='selected' ";} ?>><?php echo stripslashes($res_row->name); ?></option>
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
      <td><?php echo JText::_('RS1_ADMIN_SCRN_SERVICE_DETAIL_NAME');?></td>
      <td colspan="2"><input type="text" size="50" maxsize="250" name="name" id="name" class="sv_apptpro_request_text" value="<?php echo $this->detail->name; ?>" /></td>
    </tr>
    <tr class="admin_detail_row1">
      <td><?php echo JText::_('RS1_ADMIN_SCRN_SERVICE_DETAIL_DESC');?> </td>
      <td><input type="text" size="50" maxsize="250" name="description" class="sv_apptpro_request_text" value="<?php echo stripslashes($this->detail->description); ?>" /></td>
    </tr>
    <tr class="admin_detail_row0">
      <td ><?php echo JText::_('RS1_ADMIN_SCRN_SERVICE_DETAIL_RATE');?></td>
      <td ><input type="text" size="8" maxsize="10" name="service_rate" value="<?php echo $this->detail->service_rate; ?>" />
        &nbsp;&nbsp;&nbsp;<?php echo JText::_('RS1_ADMIN_SCRN_SERVICE_DETAIL_RATE_UNIT');?><select name="service_rate_unit">
          <option value="Hour" <?php if($this->detail->service_rate_unit == "Hour"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_SERVICE_DETAIL_RATE_HOUR');?></option>
          <option value="Flat" <?php if($this->detail->service_rate_unit == "Flat"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_SERVICE_DETAIL_RATE_BOOKING');?></option>
        </select>        </td>
      <td width="60%"><?php echo JText::_('RS1_ADMIN_SCRN_SERVICE_DETAIL_RATE_HELP');?></td>
    </tr>
	<tr class="admin_detail_row1">
      <td ><?php echo JText::_('RS1_ADMIN_SCRN_SERVICE_DURATION');?></td>
      <td ><input type="text" size="8" maxsize="10" name="service_duration" value="<?php echo $this->detail->service_duration; ?>" />
        &nbsp;&nbsp;&nbsp;<?php echo JText::_('RS1_ADMIN_SCRN_SERVICE_DETAIL_RATE_UNIT');?> <select name="service_duration_unit">
          <option value="Minute" <?php if($this->detail->service_duration_unit == "Minute"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_SERVICE_DURATION_MINUTE');?></option>
          <option value="Hour" <?php if($this->detail->service_duration_unit == "Hour"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_SERVICE_DURATION_HOUR');?></option>
      </select>        </td>
      <td width="55%"><?php echo JText::_('RS1_ADMIN_SCRN_SERVICE_DURATION_HELP');?></td>
    </tr>
	<tr  class="admin_detail_row0">
      <td><?php echo JText::_('RS1_ADMIN_SCRN_SERVICE_DETAIL_ORDER');?></td>
      <td><input type="text" size="5" maxsize="2" name="ordering" class="sv_apptpro_request_text" value="<?php echo $this->detail->ordering; ?>" />
        &nbsp;&nbsp;</td>
    </tr>
    <tr class="admin_detail_row1">
        <td ><?php echo JText::_('RS1_ADMIN_SCRN_SERVICE_DETAIL_PUBLISHED');?></td>
        <td>
            <select name="published" class="sv_apptpro_request_text">
            <option value="0" <?php if($this->detail->published == "0"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_NO');?></option>
            <option value="1" <?php if($this->detail->published == "1"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_YES');?></option>
            </select>        </td>
    </tr>
    <tr class="admin_detail_row0">
      <td colspan="2" >
      <p>&nbsp;</p></td>
    </tr>  
  </table>
  <input type="hidden" name="id_services" value="<?php echo $this->detail->id_services; ?>" />
  <input type="hidden" name="option" value="<?php echo $option; ?>" />
  <input type="hidden" name="controller" value="admin_detail" />
  <input type="hidden" name="id" value="<?php echo $this->user_id; ?>" />
  <input type="hidden" name="task" value="" />
  <input type="hidden" name="current_tab" id="current_tab" value="<?php echo $current_tab; ?>" />
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