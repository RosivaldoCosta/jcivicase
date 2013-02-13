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

?>

<script language="javascript">
function submitbutton(pressbutton) {
	var ok = "yes";
   	if (pressbutton == 'save_service'){
		if(document.getElementById("resource_id").selectedIndex == 0){
			alert("Please select a Resource.");
			ok = "no";
		}
		if(document.getElementById("name").value == ""){
			alert("Please enter a Service Name");
			ok = "no";
		}
		if(ok == "yes"){
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
        <p><?php echo JText::_('RS1_ADMIN_SCRN_SERVICE_DETAIL_INTRO');?><br /></td>
    </tr>
    <tr class="admin_detail_row0">
      <td ><?php echo JText::_('RS1_ADMIN_SCRN_SERVICE_DETAIL_ID');?></td>
      <td><?php echo $this->detail->id_services ?></td>
    </tr>
    <tr class="admin_detail_row1">
      <td><?php echo JText::_('RS1_ADMIN_SCRN_SERVICE_DETAIL_RESOURCE');?></td>
      <td colspan="2">
      <?php if($this->detail->resource_id == ""){ ?>
	      <select name="resource_id" id="resource_id">
          <option value="0" ><?php echo JText::_('RS1_ADMIN_SCRN_SERVICE_DETAIL_SEL_RESOURCE');?></option>
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
      <td><?php echo JText::_('RS1_ADMIN_SCRN_SERVICE_DETAIL_NAME');?></td>
      <td colspan="2"><input type="text" size="60" maxsize="250" name="name" id="name" value="<?php echo $this->detail->name; ?>" /></td>
    </tr>
    <tr class="admin_detail_row1">
      <td><?php echo JText::_('RS1_ADMIN_SCRN_SERVICE_DETAIL_DESC');?></td>
      <td colspan="2"><input type="text" size="60" maxsize="250" name="description" value="<?php echo stripslashes($this->detail->description); ?>" /></td>
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
      <td colspan="2"><input type="text" size="5" maxsize="2" name="ordering" value="<?php echo $this->detail->ordering; ?>" />
        &nbsp;&nbsp;</td>
    </tr>
    <tr class="admin_detail_row1">
        <td ><?php echo JText::_('RS1_ADMIN_SCRN_SERVICE_DETAIL_PUBLISHED');?></td>
        <td colspan="2">
            <select name="published">
            <option value="0" <?php if($this->detail->published == "0"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_NO');?></option>
            <option value="1" <?php if($this->detail->published == "1"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_YES');?></option>
            </select>        </td>
    </tr>
    <tr class="admin_detail_row0">
      <td colspan="2" >
      <p>&nbsp;</p></td>
    </tr>  
  </table>

</fieldset>
  <input type="hidden" name="id_services" value="<?php echo $this->detail->id_services; ?>" />
  <input type="hidden" name="task" value="" />
  <input type="hidden" name="controller" value="services_detail" />
  <br />
  <span style="font-size:10px"> Appointment Booking Pro Ver. 2.0 - Copyright 2008-20<?php echo date("y");?> - <a href='http://www.softventures.com' target="_blank">Soft Ventures, Inc.</a></span>
</form>
