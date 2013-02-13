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
	if($this->detail->expiry_date == null){
		$this->detail->expiry_date = date('Y-m-d',strtotime("+1 month"));
	}

	// get resources 
	$sql = "SELECT id_resources, name FROM #__sv_apptpro2_resources WHERE published = 1";
	$database->setQuery($sql);
	$res_rows = $database -> loadObjectList();
	if ($database -> getErrorNum()) {
		echo $database -> stderr();
		return false;
	}

	// get resource assignments 
	if (strlen($this->detail->scope) > 0 ){
		$res_assignments = str_replace("||", ",", $this->detail->scope);
		$res_assignments = str_replace("|", "", $res_assignments);
		//echo $admins;
		//exit;
		$sql = "SELECT id_resources, name FROM #__sv_apptpro2_resources WHERE ".
  			"id_resources IN (".$res_assignments.")";
		$database->setQuery($sql);
		$res_assignment_rows = $database -> loadObjectList();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
			return false;
		}
	}	

?>

<script language="javascript">
	function doAddResource(){
		var resid = document.getElementById("resources").value;
		var selected_resources = document.getElementById("selected_resources_id").value;
		var x = document.getElementById("selected_resources");
		for (i=0;i<x.length;i++){
			if(x[i].value == resid) {
				alert("Already selected");
				return false;
			}			
		}
	
		var opt = document.createElement("option");
        // Add an Option object to Drop Down/List Box
        document.getElementById("selected_resources").options.add(opt); 
        opt.text = document.getElementById("resources").options[document.getElementById("resources").selectedIndex].text;
        opt.value = document.getElementById("resources").options[document.getElementById("resources").selectedIndex].value;
		selected_resources = selected_resources + "|" + resid + "|";
		document.getElementById("selected_resources_id").value = selected_resources;
	}

	function doRemoveResource(){
		if(document.getElementById("selected_resources").selectedIndex == -1){
			alert("No Resource selected for Removal");
			return false;
		}
		var res_to_go = document.getElementById("selected_resources").options[document.getElementById("selected_resources").selectedIndex].value;
		document.getElementById("selected_resources").remove(document.getElementById("selected_resources").selectedIndex);
		
		var selected_resource = document.getElementById("selected_resources_id").value;

		selected_resource = selected_resource.replace("|" + res_to_go + "|", "");
		document.getElementById("selected_resources_id").value = selected_resource;
	}

function submitbutton(pressbutton) {
   	if (pressbutton == 'save_coupon'){
		if(document.getElementById("description").value == ""){
			alert("<?php echo JText::_('RS1_ADMIN_SCRN_COUPON_DESC_REQ');?>");
		} else if(document.getElementById("coupon_code").value == ""){
			alert("<?php echo JText::_('RS1_ADMIN_SCRN_COUPON_CODE_REQ');?>");
		} else if(document.getElementById("discount").value == ""){
			alert("<?php echo JText::_('RS1_ADMIN_SCRN_COUPON_VALUE_REQ');?>");
		} else if(document.getElementById("expiry_date").value == ""){
			alert("<?php echo JText::_('RS1_ADMIN_SCRN_COUPON_EXPIRY_REQ');?>");
		} else {
			submitform(pressbutton);
		}
	} else {
		submitform(pressbutton);
	}		
}
</script>
<form action="<?php echo JRoute::_($this->request_url) ?>" method="post" name="adminForm" id="adminForm">
<div id="testdiv1" style="VISIBILITY: hidden; POSITION: absolute; BACKGROUND-COLOR: white; layer-background-color: white"> </div>
<link href="../components/com_rsappt_pro2/calStyles.css" rel="stylesheet">
<link href="../components/com_rsappt_pro2/sv_apptpro.css" rel="stylesheet">
<script language="JavaScript" src="../components/com_rsappt_pro2/CalendarPopup.js"></script>
<script language="JavaScript">
	var now = new Date();
	var cal = new CalendarPopup( <?php echo $div_cal ?>);
	cal.setCssPrefix("TEST");
	cal.setWeekStartDay(<?php echo $apptpro_config->popup_week_start_day ?>);
</script>
<fieldset class="adminform">
  <table border="0" cellpadding="2" cellspacing="0">
    <tr>
      <td colspan="3">
        <p><?php echo JText::_('RS1_ADMIN_SCRN_COUPON_DETAIL_INTRO');?><br />
        </p>      </td>
    </tr>
    <tr class="admin_detail_row1">
      <td><?php echo JText::_('RS1_ADMIN_SCRN_COUPON_DETAIL_ID');?>:</td>
      <td><?php echo $this->detail->id ?></td>
      <td>&nbsp;</td>
    </tr>
    <tr class="admin_detail_row0">
      <td><?php echo JText::_('RS1_ADMIN_SCRN_COUPON_DETAIL_DESC');?>:</td>
      <td><input type="text" size="40" maxsize="80" name="description" id="description" value="<?php echo stripslashes($this->detail->description); ?>" /></td>
      <td><?php echo JText::_('RS1_ADMIN_SCRN_COUPON_DETAIL_DESC_HELP');?>&nbsp;</td>
    </tr>
    <tr class="admin_detail_row1">
      <td><?php echo JText::_('RS1_ADMIN_SCRN_COUPON_DETAIL_CODE');?>:</td>
      <td><input type="text" size="20" maxsize="80" name="coupon_code" id="coupon_code" value="<?php echo stripslashes($this->detail->coupon_code); ?>" /></td>
      <td><?php echo JText::_('RS1_ADMIN_SCRN_COUPON_DETAIL_CODE_HELP');?>&nbsp;</td>
    </tr>
    <tr class="admin_detail_row1">
      <td><?php echo JText::_('RS1_ADMIN_SCRN_COUPON_DISCOUNT');?>:</td>
      <td><input type="text" size="20" maxsize="80" name="discount" id="discount" value="<?php echo stripslashes($this->detail->discount); ?>" /></td>
      <td>&nbsp;</td>
    </tr>
    <tr class="admin_detail_row1">
      <td><?php echo JText::_('RS1_ADMIN_SCRN_COUPON_UNIT');?>:</td>
        <td><select name="discount_unit" id="discount_unit">
            <option value="percent" <?php if($this->detail->discount_unit == "percent"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_COUPON_PERCENT');?></option>
            <option value="fixed" <?php if($this->detail->discount_unit == "fixed"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_COUPON_FIXED');?></option>
            </select>        </td>
        <td>&nbsp;</td>
    </tr>
    <tr class="admin_detail_row0">
      <td><?php echo JText::_('RS1_ADMIN_SCRN_COUPON_MAX_USER_USE');?>:</td>
      <td><input type="text" size="2" maxsize="3" name="max_user_use" id="max_user_use" value="<?php echo $this->detail->max_user_use ?>" /></td>
      <td><?php echo JText::_('RS1_ADMIN_SCRN_COUPON_MAX_USER_USE_HELP');?>&nbsp;</td>
    </tr>
    <tr class="admin_detail_row1">
      <td><?php echo JText::_('RS1_ADMIN_SCRN_COUPON_MAX_TOTAL_USE');?>:</td>
      <td><input type="text" size="2" maxsize="3" name="max_total_use" id="max_total_use" value="<?php echo $this->detail->max_total_use ?>" /></td>
      <td><?php echo JText::_('RS1_ADMIN_SCRN_COUPON_MAX_TOTAL_USE_HELP');?>&nbsp;</td>
    </tr>
	<tr class="admin_detail_row0">
	  <td colspan="3" ><?php echo JText::_('RS1_ADMIN_SCRN_COUPON_RESOURCES_INTRO');?></td>
    </tr>
    <tr class="admin_detail_row0">
      <td ><?php echo JText::_('RS1_ADMIN_SCRN_COUPON_RESOURCES');?></td>
      <td >
      <table width="95%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="33%"><select name="resources" id="resources">
            <?php
			$k = 0;
			for($i=0; $i < count( $res_rows ); $i++) {
			$res_row = $res_rows[$i];
			?>
                <option value="<?php echo $res_row->id_resources; ?>"><?php echo stripslashes($res_row->name); ?></option>
                <?php $k = 1 - $k; 
			} ?>
              </select></td>
            <td width="34%" valign="top" align="center"><input type="button" name="btnAddResource" id="btnAddResource" size="10" value="<?php echo JText::_('RS1_ADMIN_SCRN_COUPON_RESOURCES_ADD');?>" onclick="doAddResource()" />
              <br />
              &nbsp;<br />
              <input type="button" name="btnRemoveResource" id="btnRemoveResource" size="10"  onclick="doRemoveResource()" value="<?php echo JText::_('RS1_ADMIN_SCRN_COUPON_RESOURCES_REMOVE');?>" /></td>
            <td width="33%">
            <select name="selected_resources" id="selected_resources" size="4" >
             <?php
			$k = 0;
			for($i=0; $i < count( $res_assignment_rows ); $i++) {
			$res_assignment_row = $res_assignment_rows[$i];
			?>
                <option value="<?php echo $res_assignment_row->id_resources; ?>"><?php echo $res_assignment_row->name; ?></option>
                <?php 
				$scope = $scope."|".$res_assignment_row->id_resources."|";
				$k = 1 - $k; 
			} ?>
              </select><br /><?php echo JText::_('RS1_ADMIN_SCRN_EMPTY_ALL');?>            </td>
          </tr>
        </table></td>
    <td valign="top" width="50%"><?php echo JText::_('RS1_ADMIN_SCRN_COUPON_RESOURCES_HELP');?></td>
    </tr>
    <tr class="admin_detail_row1">
      <td><?php echo JText::_('RS1_ADMIN_SCRN_COUPON_EXPIRY_DATE');?></td>
      <td><input type="text" size="12" maxsize="10" readonly="readonly" name="expiry_date" id="expiry_date" value="<?php echo date('Y-m-d', strtotime($this->detail->expiry_date)); ?>" />
		        <a href="#" id="anchor1" onclick="cal.select(document.forms['adminForm'].expiry_date,'anchor1','yyyy-MM-dd'); return false;"
					 name="anchor1"><img height="15" hspace="2" src="../components/com_rsappt_pro2/icon_cal.gif" width="16" border="0"></a>	  </td>
      <td><?php echo JText::_('RS1_ADMIN_SCRN_COUPON_EXPIRY_DATE_HELP');?>&nbsp;</td>
    </tr>
    <tr class="admin_detail_row0">
        <td ><?php echo JText::_('RS1_ADMIN_SCRN_CAT_DETAIL_PUBLISHED');?></td>
        <td><select name="published">
            <option value="0" <?php if($this->detail->published == "0"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_NO');?></option>
            <option value="1" <?php if($this->detail->published == "1"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_YES');?></option>
            </select>        </td>
        <td>&nbsp;</td>
    </tr>
    <tr class="admin_detail_row0">
      <td colspan="3" ><br />
       
        <p>&nbsp;</p></td>
    </tr>  
  </table>

</fieldset>
  <input type="hidden" name="id_coupons" value="<?php echo $this->detail->id_coupons; ?>" />
  <input type="hidden" name="task" value="" />
  <input type="hidden" name="controller" value="coupons_detail" />
  <input type="hidden" name="scope" id="selected_resources_id" value="<?php echo $scope; ?>" />
  <br />
  <span style="font-size:10px"> Appointment Booking Pro Ver. 2.0 - Copyright 2008-20<?php echo date("y");?> - <a href='http://www.softventures.com' target="_blank">Soft Ventures, Inc.</a></span>
</form>
