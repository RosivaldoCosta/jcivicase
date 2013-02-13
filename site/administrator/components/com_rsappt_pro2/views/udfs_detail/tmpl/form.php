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

	// get cb profile columns
	$database = &JFactory::getDBO();
	$database->setQuery("SELECT * FROM #__comprofiler_fields WHERE #__comprofiler_fields.table = '#__comprofiler' and (type='text' or type='predefined') ORDER BY name" );
	$cb_rows = $database -> loadObjectList();
	if ($database -> getErrorNum()) {
		if($database -> getErrorNum() != 1146){
			// ignore 1146 - table not found if component not installed
			echo $database -> stderr();
			return false;
		}
	}

	// get js profile columns
	$database->setQuery("SELECT * FROM #__community_fields WHERE type!='group' ORDER BY name" );
	$js_rows = $database -> loadObjectList();
	if ($database -> getErrorNum()) {
		if($database -> getErrorNum() != 1146){
			// ignore 1146 - table not found if component not installed
			echo $database -> stderr();
			return false;
		}
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
<link href="../components/com_rsappt_pro14/calStyles.css" rel="stylesheet">
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

</script>
<script language="javascript">
function submitbutton(pressbutton) {
   	if (pressbutton == 'save_udf'){
		if(document.getElementById("udf_label").value == ""){
			alert("Name is required");
		} else {
			submitform(pressbutton);
		}
	} else {
		submitform(pressbutton);
	}		
}
</script>
<form action="<?php echo JRoute::_($this->request_url) ?>" method="post" name="adminForm" id="adminForm">
<link href="../components/com_rsappt_pro2/sv_apptpro.css" rel="stylesheet">
<fieldset class="adminform">
  <table border="0" cellpadding="2" cellspacing="0">
    <tr>
      <td colspan="3">
        <p><?php echo JText::_('RS1_ADMIN_SCRN_UDF_DETAIL_INTRO');?><br /></td>
    </tr>
    <tr class="admin_detail_row0">
      <td width="15%"><?php echo JText::_('RS1_ADMIN_SCRN_UDF_DETAIL_ID');?></td>
      <td colspan="2"><?php echo $this->detail->id_udfs ?></td>
    </tr>
    <tr class="admin_detail_row1">
      <td><?php echo JText::_('RS1_ADMIN_SCRN_UDF_DETAIL_SCREEN_LABEL');?></td>
      <td colspan="2"><input type="text" size="30" maxsize="40" name="udf_label" id="udf_label" value="<?php echo stripslashes($this->detail->udf_label); ?>" />&nbsp; </td>
    </tr>
    <tr class="admin_detail_row0">
      <td><?php echo JText::_('RS1_ADMIN_SCRN_UDF_DETAIL_TYPE');?></td>
      <td colspan="2"> <select name="udf_type">
              <option value="Textbox" <?php if($this->detail->udf_type == "Textbox"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_UDF_DETAIL_TEXTBOX');?></option>
              <option value="Checkbox" <?php if($this->detail->udf_type == "Checkbox"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_UDF_DETAIL_CHECKBOX');?></option>
              <option value="Radio" <?php if($this->detail->udf_type == "Radio"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_UDF_DETAIL_RADIO');?></option>
              <option value="Textarea" <?php if($this->detail->udf_type == "Textarea"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_UDF_DETAIL_TEXTAREA');?></option>
              <option value="List" <?php if($this->detail->udf_type == "List"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_UDF_DETAIL_LIST');?></option>
            </select></td>
    </tr>
	<tr  class="admin_detail_row1">
      <td><?php echo JText::_('RS1_ADMIN_SCRN_UDF_DETAIL_SIZE');?></td>
      <td colspan="2"><input type="text" size="5" maxsize="2" name="udf_size" value="<?php echo $this->detail->udf_size; ?>" />&nbsp;&nbsp;<span style="font-size:10px"><?php echo JText::_('RS1_ADMIN_SCRN_UDF_DETAIL_TYPE_HINT');?></span>
        &nbsp;&nbsp;</td>
    </tr>
	<tr  class="admin_detail_row1">
	  <td><?php echo JText::_('RS1_ADMIN_SCRN_UDF_DETAIL_ROWS');?></td>
	  <td colspan="2"><input type="text" size="5" maxsize="2" name="udf_rows" value="<?php echo $this->detail->udf_rows; ?>" />&nbsp;&nbsp;<span style="font-size:10px"><?php echo JText::_('RS1_ADMIN_SCRN_UDF_DETAIL_TYPE_HINT');?></span>
        &nbsp;&nbsp;</td>
    </tr>
	<tr  class="admin_detail_row1">
	  <td><?php echo JText::_('RS1_ADMIN_SCRN_UDF_DETAIL_COLS');?></td>
	  <td colspan="2"><input type="text" size="5" maxsize="2" name="udf_cols" value="<?php echo $this->detail->udf_cols; ?>" />&nbsp;&nbsp;<span style="font-size:10px"><?php echo JText::_('RS1_ADMIN_SCRN_UDF_DETAIL_TYPE_HINT');?></span>
        &nbsp;&nbsp;</td>
    </tr>
    <tr class="admin_detail_row0">
      <td valign="top"><?php echo JText::_('RS1_ADMIN_SCRN_UDF_DETAIL_RB_OR_LIST');?></td>
      <td colspan="2"><input type="text" size="60" maxsize="255" name="udf_radio_options" id="udf_radio_options" value="<?php echo stripslashes($this->detail->udf_radio_options); ?>" />&nbsp;       
      <span style="font-size:10px;"><br /><?php echo JText::_('RS1_ADMIN_SCRN_UDF_DETAIL_RB_OR_LIST_HELP');?></span></td>
    </tr>
    <tr class="admin_detail_row1">
      <td><?php echo JText::_('RS1_ADMIN_SCRN_UDF_DETAIL_REQ_FIELD');?></td>
      <td colspan="2"> <select name="udf_required">
        	<option value="Yes" <?php if($this->detail->udf_required == "Yes"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_YES');?></option>
        	<option value="No" <?php if($this->detail->udf_required == "No"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_NO');?></option>
	    </select> 
        &nbsp;&nbsp;<span style="font-size:10px"><?php echo JText::_('RS1_ADMIN_SCRN_UDF_DETAIL_REQ_FIELD_HELP');?></span>	</td>
    </tr>
    <tr class="admin_detail_row0">
      <td><?php echo JText::_('RS1_ADMIN_SCRN_UDF_DETAIL_HELP_TEXT');?></td>
      <td colspan="2"><input type="text" size="60" maxsize="255" name="udf_help" id="udf_help" value="<?php echo stripslashes($this->detail->udf_help); ?>" />&nbsp; </td>
    </tr>
    <tr class="admin_detail_row1">
      <td><?php echo JText::_('RS1_ADMIN_SCRN_UDF_DETAIL_TOOLTIP');?></td>
      <td colspan="2"><input type="text" size="60" maxsize="255" name="udf_tooltip" id="udf_tooltip" value="<?php echo stripslashes($this->detail->udf_tooltip); ?>" />&nbsp; </td>
    </tr>
	<tr class="admin_detail_row0">
	  <td colspan="3" ><?php echo JText::_('RS1_ADMIN_SCRN_UDF_RESOURCES_INTRO');?></td>
    </tr>
    <tr class="admin_detail_row0">
      <td ><?php echo JText::_('RS1_ADMIN_SCRN_UDF_RESOURCES');?></td>
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
            <td width="34%" valign="top" align="center"><input type="button" name="btnAddResource" id="btnAddResource" size="10" value="<?php echo JText::_('RS1_ADMIN_SCRN_UDF_RESOURCES_ADD');?>" onclick="doAddResource()" />
              <br />
              &nbsp;<br />
              <input type="button" name="btnRemoveResource" id="btnRemoveResource" size="10"  onclick="doRemoveResource()" value="<?php echo JText::_('RS1_ADMIN_SCRN_UDF_RESOURCES_REMOVE');?>" /></td>
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
              </select><br /><?php echo JText::_('RS1_ADMIN_SCRN_EMPTY_ALL');?> </td>
          </tr>
        </table></td>
    <td valign="top" width="50%"><?php echo JText::_('RS1_ADMIN_SCRN_UDF_RESOURCES_HELP');?></td>
    </tr>
	<tr class="admin_detail_row1">
	  <td colspan="3"  style="border-top: solid 1px" ><?php echo JText::_('RS1_ADMIN_SCRN_UDF_CB_HELP');?></td>
    </tr>
	<tr  class="admin_detail_row1">
	  <td><?php echo JText::_('RS1_ADMIN_SCRN_UDF_CB_FIELD');?></td>
	  <td colspan="2"><select name="cb_mapping" id="cb_mapping" >
      	<option value=""><?php echo JText::_('RS1_ADMIN_SELECT_CB_VALUE');?></option>
          <?php
		$k = 0;
		for($i=0; $i < count( $cb_rows ); $i++) {
		$cb_row = $cb_rows[$i];
		?>
          <option value="<?php echo $cb_row->name; ?>" <?php if($this->detail->cb_mapping == $cb_row->name){echo " selected='selected' ";} ?>><?php echo stripslashes($cb_row->name); ?></option>
          <?php $k = 1 - $k; 
		} ?>
        </select> &nbsp;</td>
    </tr>
	<tr  class="admin_detail_row1">
	  <td style="border-bottom: solid 1px" valign="top"><?php echo JText::_('RS1_ADMIN_READ_ONLY');?></td>
	  <td colspan="2" style="border-bottom: solid 1px"><select name="read_only">
        	<option value="Yes" <?php if($this->detail->read_only == "Yes"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_YES');?></option>
        	<option value="No" <?php if($this->detail->read_only == "No"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_NO');?></option>
	    </select> &nbsp;&nbsp;<span style="font-size:10px"><?php echo JText::_('RS1_ADMIN_READ_ONLY_HELP2');?></span>	
      </td>
    </tr>

	<tr class="admin_detail_row0">
	  <td colspan="3" ><?php echo JText::_('RS1_ADMIN_SCRN_UDF_JS_HELP');?></td>
    </tr>
	<tr  class="admin_detail_row0">
	  <td><?php echo JText::_('RS1_ADMIN_SCRN_UDF_JS_FIELD');?></td>
	  <td colspan="2"><select name="js_mapping" id="js_mapping" >
      	<option value=""><?php echo JText::_('RS1_ADMIN_SELECT_JS_VALUE');?></option>
          <?php
		$k = 0;
		for($i=0; $i < count( $js_rows ); $i++) {
		$js_row = $js_rows[$i];
		?>
          <option value="<?php echo $js_row->fieldcode; ?>" <?php if($this->detail->js_mapping == $js_row->fieldcode){echo " selected='selected' ";} ?>><?php echo stripslashes($js_row->name); ?></option>
          <?php $k = 1 - $k; 
		} ?>
        </select> &nbsp;</td>
    </tr>
	<tr  class="admin_detail_row0">
	  <td style="border-bottom: solid 1px" valign="top"><?php echo JText::_('RS1_ADMIN_READ_ONLY');?></td>
	  <td colspan="2" style="border-bottom: solid 1px"><select name="js_read_only">
        	<option value="Yes" <?php if($this->detail->js_read_only == "Yes"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_YES');?></option>
        	<option value="No" <?php if($this->detail->js_read_only == "No"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_NO');?></option>
	    </select> &nbsp;&nbsp;<span style="font-size:10px"><?php echo JText::_('RS1_ADMIN_READ_ONLY_HELP3');?></span>	
      </td>
    </tr>
	
    <tr  class="admin_detail_row1">
      <td><?php echo JText::_('RS1_ADMIN_SCRN_UDF_DETAIL_ORDER');?></td>
      <td colspan="2"><input type="text" size="5" maxsize="2" name="ordering" value="<?php echo $this->detail->ordering; ?>" />
        &nbsp;&nbsp;</td>
    </tr>
    <tr class="admin_detail_row0">
        <td ><?php echo JText::_('RS1_ADMIN_SCRN_UDF_DETAIL_PUBLISHED');?></td>
        <td colspan="2">
            <select name="published">
            <option value="0" <?php if($this->detail->published == "0"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_NO');?></option>
            <option value="1" <?php if($this->detail->published == "1"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_YES');?></option>
            </select>        </td>
    </tr>
    <tr class="admin_detail_row1">
      <td colspan="3" ><br />
        <?php echo JText::_('RS1_ADMIN_SCRN_UDF_DETAIL_NOTE');?></td>
    </tr>  
  </table>

</fieldset>
  <input type="hidden" name="id_udfs" value="<?php echo $this->detail->id_udfs; ?>" />
  <input type="hidden" name="task" value="" />
  <input type="hidden" name="controller" value="udfs_detail" />
  <input type="hidden" name="scope" id="selected_resources_id" value="<?php echo $scope; ?>" />
  <br />
  <span style="font-size:10px"> Appointment Booking Pro Ver. 2.0 - Copyright 2008-20<?php echo date("y");?> - <a href='http://www.softventures.com' target="_blank">Soft Ventures, Inc.</a></span>
</form>
