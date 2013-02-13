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

	$editor = &JFactory::getEditor();
				 
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

	// get cb profile columns
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


	?>
<style type="text/css">
<!--
.icon-48-configure { background-image: url(./components/com_rsappt_pro2/images/configure.jpg); }
-->
}
</style>
<div id="testdiv1" style="VISIBILITY: hidden; POSITION: absolute; BACKGROUND-COLOR: white; layer-background-color: white"> </div>
<link href="../components/com_rsappt_pro2/calStyles.css" rel="stylesheet">
<script type="text/javascript" src="../administrator/components/com_rsappt_pro2/overlib_mini.js"></script>
<script language="JavaScript" src="../components/com_rsappt_pro2/CalendarPopup.js"></script>
<script language="JavaScript">
	var now = new Date();
	var cal = new CalendarPopup( <?php echo $div_cal ?>);
	cal.setCssPrefix("TEST");
	cal.setWeekStartDay(<?php echo $apptpro_config->popup_week_start_day ?>);
</script>
<table class="adminheading">
  <tr>
    <th class="config"><?php echo JText::_('RS1_ADMIN_CONFIG');?></th>
  </tr>
</table>
<form action="<?php echo JRoute::_($this->request_url) ?>" method="post" name="adminForm" id="adminForm">
<link href="../components/com_rsappt_pro2/sv_apptpro.css" rel="stylesheet">
<fieldset class="adminform">
  <?php 
  
 	// get data for dropdowns
	$database = &JFactory::getDBO();
	$database->setQuery("SELECT * FROM #__sv_apptpro2_pp_currency ORDER BY description" );
	$currency_rows = $database -> loadObjectList();
	if ($database -> getErrorNum()) {
		echo $database -> stderr();
		return false;
	}

	$sql = 'SELECT * FROM #__sv_apptpro2_udfs WHERE (udf_type="Textbox" or udf_type="List" or udf_type="Radio") and published=1 ORDER BY ordering';
	$database->setQuery($sql);
	$udf_rows = $database -> loadObjectList();
	if ($database -> getErrorNum()) {
		echo "DB Err: ". $database -> stderr();
		return false;
	}
  
   // instantiate new tab system
	jimport( 'joomla.html.pane' );
	
	$pane =& JPane::getInstance('Tabs');
	echo $pane->startPane('myPane');
	{
	// start tab pane
	//$tabs->startPane("TabPaneOne");
	echo $pane->startPanel(JText::_('RS1_ADMIN_CONFIG_TAB'), 'panel1');
	?>
  <table cellpadding="4" cellspacing="0" border="0"  >
    <?php
	$row = $rows[0];
	?>
    <tr class="admin_detail_row1">
      <td><?php echo JText::_('RS1_ADMIN_CONFIG_EMAIL_TO');?>:</td>
      <td><input type="text" size="50" maxsize="80" name="mailTO" value="<?php echo $this->detail->mailTO; ?>" />
      	<Br /><Br />
        <span style="font-size:9px"><?php echo JText::_('RS1_ADMIN_CONFIG_EMAIL_TO_HELP');?></span>
      </td>
    </tr>
    <tr class="admin_detail_row0">
      <td><?php echo JText::_('RS1_ADMIN_CONFIG_EMAIL_FROM');?>: </td>
      <td><input type="text" size="50" maxsize="255" name="mailFROM" value="<?php echo $this->detail->mailFROM; ?>" /></td>
    </tr>
    <tr class="admin_detail_row1">
      <td><?php echo JText::_('RS1_ADMIN_CONFIG_EMAIL_SUBJECT');?>: </td>
      <td><input type="text" size="30" maxsize="50" name="mailSubject" value="<?php echo $this->detail->mailSubject; ?>" /></td>
    </tr>
    <tr class="admin_detail_row0">
      <td><?php echo JText::_('RS1_ADMIN_CONFIG_HTML_EMAIL');?>: </td>
      <td><select name="html_email">
          <option value="Yes" <?php if($this->detail->html_email == "Yes"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_YES');?></option>
          <option value="No" <?php if($this->detail->html_email == "No"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_NO');?></option>
        </select>
        &nbsp;&nbsp;<img src="../administrator/components/com_rsappt_pro2/tooltip.png" border="0" style="padding-left:5px"  
            onmouseover="return overlib('<?php echo JText::_('RS1_ADMIN_CONFIG_HTML_EMAIL_HELP');?>', CAPTION, '<?php echo JText::_('RS1_ADMIN_CONFIG_HTML_EMAIL');?>', BELOW, RIGHT);" 
            onmouseout="return nd();" /> </td>
    </tr>
    <tr class="admin_detail_row1">
      <td><?php echo JText::_('RS1_ADMIN_CONFIG_LOGIN_REQUIRED');?>:</td>
      <td><select name="requireLogin">
          <option value="Yes" <?php if($this->detail->requireLogin == "Yes"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_YES');?></option>
          <option value="No" <?php if($this->detail->requireLogin == "No"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_NO');?></option>
        </select>
        &nbsp;&nbsp;

        <img src="../administrator/components/com_rsappt_pro2/tooltip.png" border="0" style="padding-left:5px" 
            onmouseover="return overlib('<?php echo JText::_('RS1_ADMIN_CONFIG_LOGIN_REQUIRED_HELP');?>', CAPTION, '<?php echo JText::_('RS1_ADMIN_CONFIG_LOGIN_REQUIRED');?>', BELOW, RIGHT);" 
            onmouseout="return nd();" /> &nbsp;</td>
    </tr>
    <tr class="admin_detail_row0">
      <td><?php echo JText::_('RS1_ADMIN_SCRN_NAME_READONLY');?>:</td>
      <td><select name="name_read_only">
          <option value="Yes" <?php if($this->detail->name_read_only == "Yes"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_YES');?></option>
          <option value="No" <?php if($this->detail->name_read_only == "No"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_NO');?></option>
        </select>
        &nbsp;&nbsp;

        <img src="../administrator/components/com_rsappt_pro2/tooltip.png" border="0" style="padding-left:5px" 
            onmouseover="return overlib('<?php echo JText::_('RS1_ADMIN_SCRN_NAME_READONLY_HELP');?>', CAPTION, '<?php echo JText::_('RS1_ADMIN_SCRN_NAME_READONLY');?>', BELOW, RIGHT);" 
            onmouseout="return nd();" /> &nbsp;</td>
    </tr>
    <tr class="admin_detail_row1">
      <td valign="top"><?php echo JText::_('RS1_ADMIN_SCRN_PHONE');?>: </td>
      <td valign="top"><select name="requirePhone">
          <option value="Yes" <?php if($this->detail->requirePhone == "Yes"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_PHONE_REQUIRED');?></option>
          <option value="No" <?php if($this->detail->requirePhone == "No"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_PHONE_OPTIONAL');?></option>
          <option value="Hide" <?php if($this->detail->requirePhone == "Hide"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_PHONE_HIDE');?></option>
        </select>
        &nbsp;&nbsp;
        <img src="../administrator/components/com_rsappt_pro2/tooltip.png" border="0" style="padding-left:5px" 
            onmouseover="return overlib('<?php echo JText::_('RS1_ADMIN_CONFIG_PHONE_REQUIRED_HELP');?>', CAPTION, '<?php echo JText::_('RS1_ADMIN_CONFIG_PHONE_REQUIRED');?>', BELOW, RIGHT);" 
            onmouseout="return nd();" />&nbsp;&nbsp;
        <table border="0" align="right">
          <tr>
            <td>        <?php echo JText::_('RS1_ADMIN_CONFIG_PHONE_CB');?>
        <select name="phone_cb_mapping" id="phone_cb_mapping" >
      	<option value=""><?php echo JText::_('RS1_ADMIN_SELECT_CB_VALUE');?></option>
          <?php
		$k = 0;
		for($i=0; $i < count( $cb_rows ); $i++) {
		$cb_row = $cb_rows[$i];
		?>
          <option value="<?php echo $cb_row->name; ?>" <?php if($this->detail->phone_cb_mapping == $cb_row->name){echo " selected='selected' ";} ?>><?php echo stripslashes($cb_row->name); ?></option>
          <?php $k = 1 - $k; 
		} ?>
        </select> <img src="../administrator/components/com_rsappt_pro2/tooltip.png" border="0" style="padding-left:5px" 
            onmouseover="return overlib('<?php echo JText::_('RS1_ADMIN_CONFIG_PHONE_CB_HELP');?>', CAPTION, '<?php echo JText::_('RS1_ADMIN_CONFIG_PHONE_FROM_CB');?>', BELOW, RIGHT);" 
            onmouseout="return nd();" />&nbsp;<?php echo JText::_('RS1_ADMIN_READ_ONLY');?> <select name="phone_read_only">
       	  <option value="Yes" <?php if($this->detail->phone_read_only == "Yes"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_YES');?></option>
       	  <option value="No" <?php if($this->detail->phone_read_only == "No"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_NO');?></option>
      </select> <img src="../administrator/components/com_rsappt_pro2/tooltip.png" border="0" style="padding-left:5px" 
            onmouseover="return overlib('<?php echo JText::_('RS1_ADMIN_READ_ONLY_HELP');?>', CAPTION, '<?php echo JText::_('RS1_ADMIN_PHONE_READ_ONLY');?>', BELOW, RIGHT);" 
            onmouseout="return nd();" /></td>
          </tr>
          <tr>
            <td>        <?php echo JText::_('RS1_ADMIN_CONFIG_PHONE_JS');?>
        <select name="phone_js_mapping" id="phone_js_mapping" >
      	<option value=""><?php echo JText::_('RS1_ADMIN_SELECT_JS_VALUE');?></option>
          <?php
		$k = 0;
		for($i=0; $i < count( $js_rows ); $i++) {
		$js_row = $js_rows[$i];
		?>
          <option value="<?php echo $js_row->fieldcode; ?>" <?php if($this->detail->phone_js_mapping == $js_row->fieldcode){echo " selected='selected' ";} ?>><?php echo stripslashes($js_row->name); ?></option>
          <?php $k = 1 - $k; 
		} ?>
        </select> <img src="../administrator/components/com_rsappt_pro2/tooltip.png" border="0" style="padding-left:5px" 
            onmouseover="return overlib('<?php echo JText::_('RS1_ADMIN_CONFIG_PHONE_JS_HELP');?>', CAPTION, '<?php echo JText::_('RS1_ADMIN_CONFIG_PHONE_FROM_JS');?>', BELOW, RIGHT);" 
            onmouseout="return nd();" />&nbsp;</td>
          </tr>
        </table> 
</td>
    </tr>
    <tr class="admin_detail_row0">
      <td><?php echo JText::_('RS1_ADMIN_SCRN_EMAIL');?>: </td>
      <td><select name="requireEmail">
          <option value="Yes" <?php if($this->detail->requireEmail == "Yes"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_EMAIL_REQUIRED');?></option>
          <option value="No" <?php if($this->detail->requireEmail == "No"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_EMAIL_OPTIONAL');?></option>
          <option value="Hide" <?php if($this->detail->requireEmail == "Hide"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_EMAIL_HIDE');?></option>
        </select>
        &nbsp;&nbsp;

        <img src="../administrator/components/com_rsappt_pro2/tooltip.png" border="0" style="padding-left:5px" 
            onmouseover="return overlib('<?php echo JText::_('RS1_ADMIN_CONFIG_EMAIL_REQUIRED_HELP');?>', CAPTION, '<?php echo JText::_('RS1_ADMIN_CONFIG_EMAIL_REQUIRED');?>', BELOW, RIGHT);" 
            onmouseout="return nd();" /> &nbsp;&nbsp;<!--  [optional] Fetch data from CB profile: 
        <select name="email_cb_mapping" id="email_cb_mapping" >
      	<option value="">Select a CB profile value</option>
          <?php
		$k = 0;
		for($i=0; $i < count( $cb_rows ); $i++) {
		$cb_row = $cb_rows[$i];
		?>
          <option value="<?php echo $cb_row->name; ?>" <?php if($this->detail->email_cb_mapping == $cb_row->name){echo " selected='selected' ";} ?>><?php echo stripslashes($cb_row->name); ?></option>
          <?php $k = 1 - $k; 
		} ?>
        </select>  <img src="../administrator/components/com_rsappt_pro2/tooltip.png" border="0" style="padding-left:5px" 
            onmouseover="return overlib('<?php echo JText::_('If you are using Community Builder and wish to populate the ABPro booking screen from a CB profile field, set the field mapping here.');?>', CAPTION, '<?php echo JText::_('EMail From CB');?>', BELOW, RIGHT);" 
            onmouseout="return nd();" />&nbsp;Read Only: <select name="email_read_only">
        	<option value="Yes" <?php if($this->detail->email_read_only == "Yes"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_YES');?></option>
        	<option value="No" <?php if($this->detail->email_read_only == "No"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_NO');?></option>
	    </select> <img src="../administrator/components/com_rsappt_pro2/tooltip.png" border="0" style="padding-left:5px" 
            onmouseover="return overlib('<?php echo JText::_('Set to Yes if you do not want the visitor to change the value.<br>Note: Changes, if allowed are <b>never</b> written back to CB. Changes are only stored in the ABPro booking.');?>', CAPTION, '<?php echo JText::_('Email Read Only');?>', BELOW, RIGHT);" 
            onmouseout="return nd();" /> --></td>
    </tr>
    <tr class="admin_detail_row1">
      <td><?php echo JText::_('RS1_ADMIN_CONFIG_HIDE_LOGO');?>: </td>
      <td><select name="hide_logo">
          <option value="Yes" <?php if($this->detail->hide_logo == "Yes"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_YES');?></option>
          <option value="No" <?php if($this->detail->hide_logo == "No"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_NO');?></option>
        </select>
        &nbsp;&nbsp;

        <img src="../administrator/components/com_rsappt_pro2/tooltip.png" border="0" style="padding-left:5px" 
            onmouseover="return overlib('<?php echo JText::_('RS1_ADMIN_CONFIG_HIDE_LOGO_HELP');?>', CAPTION, '<?php echo JText::_('RS1_ADMIN_CONFIG_HIDE_LOGO');?>', BELOW, RIGHT);" 
            onmouseout="return nd();" /> </td>
    </tr>
    <tr class="admin_detail_row0">
      <td><?php echo JText::_('RS1_ADMIN_CONFIG_USE_DIV_CAL');?>: </td>
      <td><select name="use_div_calendar">
          <option value="Yes" <?php if($this->detail->use_div_calendar == "Yes"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_YES');?></option>
          <option value="No" <?php if($this->detail->use_div_calendar == "No"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_NO');?></option>
        </select>
        &nbsp;&nbsp;

        <img src="../administrator/components/com_rsappt_pro2/tooltip.png" border="0" style="padding-left:5px" 
            onmouseover="return overlib('<?php echo JText::_('RS1_ADMIN_CONFIG_USE_DIV_CAL_HELP');?>', CAPTION, '<?php echo JText::_('RS1_ADMIN_CONFIG_USE_DIV_CAL');?>', BELOW, RIGHT);" 
            onmouseout="return nd();" /> </td>
    </tr>
    <tr class="admin_detail_row1">
      <td><?php echo JText::_('RS1_ADMIN_CONFIG_POPUP_START_DAY');?>: </td>
      <td><select name="popup_week_start_day">
          <option value="0" <?php if($this->detail->popup_week_start_day == "0"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_SUNDAY');?></option>
          <option value="1" <?php if($this->detail->popup_week_start_day == "1"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_MONDAY');?></option>
        </select>
        &nbsp;&nbsp;

        <img src="../administrator/components/com_rsappt_pro2/tooltip.png" border="0" style="padding-left:5px" 
            onmouseover="return overlib('<?php echo JText::_('RS1_ADMIN_CONFIG_POPUP_START_DAY_HELP');?>', CAPTION, '<?php echo JText::_('RS1_ADMIN_CONFIG_POPUP_START_DAY');?>', BELOW, RIGHT);" 
            onmouseout="return nd();" /> </td>
    </tr>
	<tr class="admin_detail_row0">
      <td><?php echo JText::_('RS1_ADMIN_CONFIG_LIMIT_BOOKINGS');?>: </td>
      <td><input type="text" size="3" maxsize="3" name="limit_bookings" value="<?php echo $this->detail->limit_bookings; ?>" style="text-align: center"/>
        <?php echo JText::_('RS1_ADMIN_CONFIG_IN');?>
        <input type="text" size="3" maxsize="3" name="limit_bookings_days" value="<?php echo $this->detail->limit_bookings_days; ?>"  style="text-align: center"/> 
		<?php echo JText::_('RS1_ADMIN_CONFIG_DAYS');?>
    	<img src="../administrator/components/com_rsappt_pro2/tooltip.png" border="0" style="padding-left:5px" 
            onmouseover="return overlib('<?php echo JText::_('RS1_ADMIN_CONFIG_LIMIT_BOOKINGS_HELP');?>', CAPTION, '<?php echo JText::_('RS1_ADMIN_CONFIG_LIMIT_BOOKINGS');?>', BELOW, RIGHT);" 
            onmouseout="return nd();" />    
         </td>
    </tr>
<!--    <tr class="admin_detail_row1">
      <td><?php echo JText::_('RS1_ADMIN_CONFIG_PREVENT_DUPES');?>: </td>
      <td><select name="prevent_dupe_bookings">
          <option value="Yes" <?php if($this->detail->prevent_dupe_bookings == "Yes"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_YES');?></option>
          <option value="No" <?php if($this->detail->prevent_dupe_bookings == "No"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_NO');?></option>
        </select>
        &nbsp;&nbsp;

    <img src="../administrator/components/com_rsappt_pro2/tooltip.png" border="0" style="padding-left:5px" 
            onmouseover="return overlib('<?php echo JText::_('RS1_ADMIN_CONFIG_PREVENT_DUPES_HELP');?>', CAPTION, '<?php echo JText::_('RS1_ADMIN_CONFIG_PREVENT_DUPES');?>', BELOW, RIGHT);" 
            onmouseout="return nd();" />    </tr>
-->    
	<tr class="admin_detail_row1">
      <td><?php echo JText::_('RS1_ADMIN_CONFIG_AUTO_ACCEPT');?>: </td>
      <td><select name="auto_accept">
          <option value="Yes" <?php if($this->detail->auto_accept == "Yes"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_YES');?></option>
          <option value="No" <?php if($this->detail->auto_accept == "No"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_NO');?></option>
        </select>
        &nbsp;&nbsp;

    <img src="../administrator/components/com_rsappt_pro2/tooltip.png" border="0" style="padding-left:5px" 
            onmouseover="return overlib('<?php echo JText::_('RS1_ADMIN_CONFIG_AUTO_ACCEPT_HELP');?>', CAPTION, '<?php echo JText::_('RS1_ADMIN_CONFIG_AUTO_ACCEPT');?>', BELOW, RIGHT);" 
            onmouseout="return nd();" />    </tr>
    <tr class="admin_detail_row0">
      <td><?php echo JText::_('RS1_ADMIN_CONFIG_ALLOW_CANCEL');?>: </td>
      <td><select name="allow_cancellation">
          <option value="Yes" <?php if($this->detail->allow_cancellation == "Yes"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_YES');?></option>
          <option value="No" <?php if($this->detail->allow_cancellation == "No"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_NO');?></option>
          <option value="BEO" <?php if($this->detail->allow_cancellation == "BEO"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_CANCEL_BACK_END_ONLY');?></option>
        </select>
        &nbsp;&nbsp;

    <img src="../administrator/components/com_rsappt_pro2/tooltip.png" border="0" style="padding-left:5px" 
            onmouseover="return overlib('<?php echo JText::_('RS1_ADMIN_CONFIG_ALLOW_CANCEL_HELP');?>', CAPTION, '<?php echo JText::_('RS1_ADMIN_CONFIG_ALLOW_CANCEL');?>', BELOW, RIGHT);" 
            onmouseout="return nd();" /> 
    &nbsp;<?php echo JText::_('RS1_ADMIN_CONFIG_ALLOW_CANCEL_UPTO');?> <select name="hours_before_cancel">
          <option value="0" <?php if($this->detail->hours_before_cancel == "0"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_CONFIG_0');?></option>
          <option value="1" <?php if($this->detail->hours_before_cancel == "1"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_CONFIG_1');?></option>
          <option value="2" <?php if($this->detail->hours_before_cancel == "2"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_CONFIG_2');?></option>
          <option value="4" <?php if($this->detail->hours_before_cancel == "4"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_CONFIG_4');?></option>
          <option value="6" <?php if($this->detail->hours_before_cancel == "6"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_CONFIG_6');?></option>
          <option value="8" <?php if($this->detail->hours_before_cancel == "8"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_CONFIG_8');?></option>
          <option value="12" <?php if($this->detail->hours_before_cancel == "12"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_CONFIG_12');?></option>
          <option value="24" <?php if($this->detail->hours_before_cancel == "24"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_CONFIG_24');?></option>
          <option value="48" <?php if($this->detail->hours_before_cancel == "48"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_CONFIG_48');?></option>
    </select> <?php echo JText::_('RS1_ADMIN_CONFIG_ALLOW_CANCEL_HOURS_BEFORE');?></tr>
    <tr class="admin_detail_row1">
      <td height="27" ><?php echo JText::_('RS1_ADMIN_CONFIG_ALLOW_CREDIT_REFUND');?>:</td>
      <td><select name="allow_user_credit_refunds">
          <option value="Yes" <?php if($this->detail->allow_user_credit_refunds == "Yes"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_YES');?></option>
          <option value="No" <?php if($this->detail->allow_user_credit_refunds == "No"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_NO');?></option>
        </select>
        &nbsp;&nbsp;

        <img src="../administrator/components/com_rsappt_pro2/tooltip.png" border="0" style="padding-left:5px" 
            onmouseover="return overlib('<?php echo JText::_('RS1_ADMIN_CONFIG_ALLOW_CREDIT_REFUND_HELP');?>', CAPTION, '<?php echo JText::_('RS1_ADMIN_CONFIG_ALLOW_CREDIT_REFUND');?>', ABOVE, RIGHT);" 
            onmouseout="return nd();" /> </td>
      <td>&nbsp;</td>
    </tr>
    <tr class="admin_detail_row0">
      <td height="27" ><?php echo JText::_('RS1_ADMIN_CONFIG_TIME_FORMAT');?>:</td>
      <td><select name="timeFormat">
          <option value="12" <?php if($this->detail->timeFormat == "12"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_CONFIG_12_HOUR');?></option>
          <option value="24" <?php if($this->detail->timeFormat == "24"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_CONFIG_24_HOUR');?></option>
        </select>
        &nbsp;&nbsp;

        <img src="../administrator/components/com_rsappt_pro2/tooltip.png" border="0" style="padding-left:5px" 
            onmouseover="return overlib('<?php echo JText::_('RS1_ADMIN_CONFIG_TIME_FORMAT_HELP');?>', CAPTION, '<?php echo JText::_('RS1_ADMIN_CONFIG_TIME_FORMAT');?>', ABOVE, RIGHT);" 
            onmouseout="return nd();" /> </td>
      <td>&nbsp;</td>
    </tr>
<!--    <tr class="admin_detail_row1">
      <td height="27" ><?php echo JText::_('RS1_ADMIN_CONFIG_ACTIVITY_LOGGING');?>:</td>
      <td><select name="activity_logging">
          <option value="Off" <?php if($this->detail->activity_logging == "Off"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_CONFIG_ACTIVITY_LOGGING_OFF');?></option>
          <option value="Min" <?php if($this->detail->activity_logging == "Min"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_CONFIG_ACTIVITY_LOGGING_MIN');?></option>
          <option value="Max" <?php if($this->detail->activity_logging == "Max"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_CONFIG_ACTIVITY_LOGGING_MAX');?></option>
        </select>
        &nbsp;&nbsp;

        <img src="../administrator/components/com_rsappt_pro2/tooltip.png" border="0" style="padding-left:5px" 
            onmouseover="return overlib('<?php echo JText::_('RS1_ADMIN_CONFIG_ACTIVITY_LOGGING_HELP');?>', CAPTION, '<?php echo JText::_('RS1_ADMIN_CONFIG_ACTIVITY_LOGGING');?>', ABOVE, RIGHT);" 
            onmouseout="return nd();" /> </td>
      <td>&nbsp;</td>
    </tr>-->
    <tr class="admin_detail_row1">
      <td style="border-top:solid #666666 1px" valign="top"><?php echo JText::_('RS1_ADMIN_CONFIG_HEADER_TEXT');?>: </td>
      <td style="border-top:solid #666666 1px"><textarea name="headerText" rows="3" cols="60"><?php echo stripslashes($this->detail->headerText); ?></textarea></td>
    </tr>
    <tr class="admin_detail_row0">
      <td valign="top"><?php echo JText::_('RS1_ADMIN_CONFIG_FOOTER_TEXT');?>: </td>
      <td><textarea name="footerText" rows="3" cols="60"><?php echo stripslashes($this->detail->footerText); ?></textarea></td>
    </tr>
  </table>
<?php
	echo $pane->endPanel();

	//Second Tab
	//Third Tab
	echo $pane->startPanel(JText::_('RS1_ADMIN_CONFIG_CAL_TAB'), 'panel4');
	?>
  <script language="javascript">
		function cal_pick(){
			if(document.getElementById('NoCal').checked == true){
				document.getElementById('which_calendar_id').value = "None";
				}
//			if(document.getElementById('JEvents').checked == true){
//				document.getElementById('which_calendar_id').value = "JEvents";
//				}
//			if(document.getElementById('JCalPro').checked == true){
//				document.getElementById('which_calendar_id').value = "JCalPro";
//				}
			if(document.getElementById('JCalPro2').checked == true){
				document.getElementById('which_calendar_id').value = "JCalPro2";
				}
			if(document.getElementById('EventList').checked == true){
				document.getElementById('which_calendar_id').value = "EventList";
				}
			if(document.getElementById('Google').checked == true){
				document.getElementById('which_calendar_id').value = "Google";
				}
//			if(document.getElementById('Thyme').checked == true){
//				document.getElementById('which_calendar_id').value = "Thyme";
//				}
			}
	</script>
<link href="../components/com_rsappt_pro2/calStyles.css" rel="stylesheet">
    
  <table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist" >
    <tr class="admin_detail_row0">
      <td><?php echo JText::_('RS1_ADMIN_CONFIG_CAL_INTRO');?>
        <table width="80%" border="0" cellspacing="0" cellpadding="0" align="center">
          <tr>
            <td align="center"><input name="rbcalendar" type="radio" id="NoCal" value="None"  onclick="cal_pick()" 
					<?php if($this->detail->which_calendar == 'None'){ echo 'checked="checked"';} ?>/></td>
            <td><?php echo JText::_('RS1_ADMIN_CONFIG_CAL_NONE');?></td>
          </tr>
          <tr>
            <td align="center" valign="top"><input type="radio" name="rbcalendar" id="EventList" value="EventList" onclick="cal_pick()" 
    					<?php if($this->detail->which_calendar == 'EventList'){ echo 'checked="checked"';} ?>/>            </td>
            <td><?php echo JText::_('RS1_ADMIN_CONFIG_CAL_EVENTLIST');?> <?php echo JText::_('RS1_ADMIN_CONFIG_CAL_EVENTLIST_LINK');?>
            <?php echo JText::_('RS1_ADMIN_CONFIG_CAL_EVENTLIST_HELP');?>
            </td>
          </tr>
          <tr>
            <td align="center" valign="top"><input type="radio" name="rbcalendar" id="Google" value="Google" onclick="cal_pick()" 
    					<?php if($this->detail->which_calendar == 'Google'){ echo 'checked="checked"';} ?>/>            </td>
            <td><?php echo JText::_('RS1_ADMIN_CONFIG_CAL_GOOGLE');?> <?php echo JText::_('RS1_ADMIN_CONFIG_CAL_GOOGLE_LINK');?>
            <?php echo JText::_('RS1_ADMIN_CONFIG_CAL_GOOGLE_HELP');?>
            </td>
          </tr>        
 <!--         <tr>
            <td align="center" valign="top"><input type="radio" name="rbcalendar" id="JCalPro" value="JCalPro" onclick="cal_pick()" 
    					<?php if($this->detail->which_calendar == 'JCalPro'){ echo 'checked="checked"';} ?>/>            </td>
            <td><?php echo JText::_('RS1_ADMIN_CONFIG_CAL_JCALPRO');?> <?php echo JText::_('RS1_ADMIN_CONFIG_CAL_JCALPRO_LINK');?>
            <?php echo JText::_('RS1_ADMIN_CONFIG_CAL_JCALPRO_HELP');?>
            </td>
          </tr>-->
          <tr>
            <td align="center" valign="top"><input type="radio" name="rbcalendar" id="JCalPro2" value="JCalPro2" onclick="cal_pick()" 
    					<?php if($this->detail->which_calendar == 'JCalPro2'){ echo 'checked="checked"';} ?>/>            </td>
            <td><?php echo JText::_('RS1_ADMIN_CONFIG_CAL_JCALPRO2');?> <?php echo JText::_('RS1_ADMIN_CONFIG_CAL_JCALPRO_LINK');?>
            <?php echo JText::_('RS1_ADMIN_CONFIG_CAL_JCALPRO2_HELP');?>
            </td>
          </tr>
<!--		  <tr>
            <td width="9%" align="center" valign="top"><input name="rbcalendar" type="radio" id="JEvents" value="JEvents"                  
					<?php if($this->detail->which_calendar == 'JEvents'){ echo 'checked="checked"';} ?> onclick="cal_pick()" /></td>
            <td width="79%"><?php echo JText::_('RS1_ADMIN_CONFIG_CAL_JEVENTS');?> <?php echo JText::_('RS1_ADMIN_CONFIG_CAL_JEVENTS_LINK');?>
            <?php echo JText::_('RS1_ADMIN_CONFIG_CAL_JEVENTS_HELP');?>
            </td>
          </tr>
          <tr>
            <td align="center" valign="top"><input name="rbcalendar" type="radio" id="Thyme" value="Thyme"                  
					<?php if($this->detail->which_calendar == 'Thyme'){ echo 'checked="checked"';} ?> onclick="cal_pick()" />&nbsp;</td>
            <td><?php echo JText::_('RS1_ADMIN_CONFIG_CAL_THYME');?> <?php echo JText::_('RS1_ADMIN_CONFIG_CAL_THYME_LINK');?>
            <?php echo JText::_('RS1_ADMIN_CONFIG_CAL_THYME_HELP');?>
            </td>
          </tr>
-->    	  <tr  class="admin_detail_row0" >
		      <td colspan="2"><hr><?php echo JText::_('RS1_ADMIN_CONFIG_CAL_FIELDS');?>: </td>
          </tr>
          <tr>
            <td colspan="2"><table width="100%" border="0">
              <tr>
                <td width="15%" valign="top"><?php echo JText::_('RS1_ADMIN_CONFIG_CAL_TITLE');?>:</td>
                <td colspan="2"><table border="0">
                    <tr>
                      <td width="173" valign="top"><select name="calendar_title">
                    <option value="resource.name" <?php if($this->detail->calendar_title == "resource.name"){echo " selected='selected' ";} ?>>resource.name</option>
                    <option value="request.name" <?php if($this->detail->calendar_title == "request.name"){echo " selected='selected' ";} ?>>request.name</option>
					  <?php
						$k = 0;
						for($i=0; $i < count( $udf_rows ); $i++) {
						$udf_row = $udf_rows[$i];
						?>
							  <option value="<?php echo $udf_row->id; ?>" <?php if($this->detail->calendar_title == $udf_row->id){echo " selected='selected' ";} ?>><?php echo $udf_row->udf_label?></option>
							  <?php $k = 1 - $k; 
						} ?>
                    </select></td>
                      <td ><?php echo JText::_('RS1_ADMIN_CONFIG_CAL_TITLE_HELP');?></td>
                    </tr>
                </table>              </tr>
              <tr>
                <td valign="top"><?php echo JText::_('RS1_ADMIN_CONFIG_CAL_BODY');?>:</td>
                <td><textarea name="calendar_body2" rows="4" cols="70"><?php echo stripslashes($this->detail->calendar_body2); ?></textarea>
                  &nbsp;<?php echo JText::_('RS1_ADMIN_CONFIG_CAL_BODY_HELP');?>                  </td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td valign="top"><?php echo JText::_('RS1_ADMIN_CONFIG_DST');?>:</td>
                <td><table border="0">
                    <tr>
                      <td width="173" valign="top"><select name="daylight_savings_time">
                        <option value="Yes" <?php if($this->detail->daylight_savings_time == "Yes"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_YES');?></option>
                        <option value="No" <?php if($this->detail->daylight_savings_time == "No"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_NO');?></option>
                        </select>&nbsp;</td>
                      <td ><?php echo JText::_('RS1_ADMIN_CONFIG_DST_HELP');?></td>
                    </tr>
                </table>
				</td>
                <td>&nbsp;</td>
              </tr>
            <tr class="admin_detail_row0">
              <td><?php echo JText::_('RS1_ADMIN_SCRN_DST_START_DATE');?></td>
              <td><input type="text" size="12" maxsize="10" readonly="readonly" name="dst_start_date" id="dst_start_date" value="<?php echo $this->detail->dst_start_date; ?>" />
                    <a href="#" id="anchor1" onclick="cal.select(document.forms['adminForm'].dst_start_date,'anchor1','yyyy-MM-dd'); return false;"
                             name="anchor1"><img height="15" hspace="2" src="../components/com_rsappt_pro2/icon_cal.gif" width="16" border="0"></a>
                    </td>
            </tr>
            <tr class="admin_detail_row0">
              <td><?php echo JText::_('RS1_ADMIN_SCRN_DST_END_DATE');?></td>
              <td><input type="text" size="12" maxsize="10" readonly="readonly" name="dst_end_date" id="dst_end_date" value="<?php echo $this->detail->dst_end_date; ?>" />
                        <a href="#" id="anchor2" onclick="cal.select(document.forms['adminForm'].dst_end_date,'anchor2','yyyy-MM-dd'); return false;"
                             name="anchor2"><img height="15" hspace="2" src="../components/com_rsappt_pro2/icon_cal.gif" width="16" border="0"></a>
                  &nbsp; </td>
            </tr>
            </table></td>
          </tr>
          <tr>
		      <td colspan="2"><p>&nbsp;</p>	          </td>
          </tr>
          <tr>    
              <td colspan="2">&nbsp;</td>
    </tr>
        </table>
        <p>&nbsp;</p>
        <?php echo JText::_('RS1_ADMIN_CONFIG_CAL_NOTE');?></td>
    </tr>
  </table>
  <input type="hidden" name="which_calendar" id="which_calendar_id" value=<?php echo $this->detail->which_calendar ?> />
  <?php
	echo $pane->endPanel();

	echo $pane->startPanel(JText::_('RS1_ADMIN_CONFIG_GAD_TAB'), 'panel5');
	?>
<script language="javascript">
	function set_gad_grid_start_day_radios(){		
		switch(document.getElementById('gad_grid_start_day').value)
		{
		case "Today":
		  document.getElementById('rb_gad_grid_start_day_today').checked = true;
		  break;    
		case "XDays":
		  document.getElementById('rb_gad_grid_start_day_xdays').checked = true;
		  break;    
		case "Tomorrow":
		  document.getElementById('rb_gad_grid_start_day_tomorrow').checked = true;
		  break;    
		default:
		  document.getElementById('rb_gad_grid_start_day_specific').checked = true;
		  break;    
		}
	}
	
	function setTomorrow(){
		document.getElementById('gad_grid_start_day').value = "Tomorrow";
	}

	function setMonday(){
		document.getElementById('gad_grid_start_day').value = "Monday";
	}

	function setToday(){
		document.getElementById('gad_grid_start_day').value = "Today";
	}

	function setNotSet(){
		document.getElementById('gad_grid_start_day').value = "Not Set";
	}
	
	function setXDays(){
		document.getElementById('gad_grid_start_day').value = "XDays";
	}

	
</script>
    
<link href="../components/com_rsappt_pro2/sv_apptpro.css" rel="stylesheet">
  <table border="0" cellpadding="2" cellspacing="0" >
    <tr>
      <td colspan="3">
        <?php echo JText::_('RS1_ADMIN_CONFIG_GAD_INTRO');?> </td>
    </tr>
    <tr class="admin_detail_row1">
      <td width="20%"><?php echo JText::_('RS1_ADMIN_CONFIG_GAD_GRID_GAD2');?>:</td>
      <td><select name="use_gad2" class="admin_dropdown">
            <option value="Yes" <?php if($this->detail->use_gad2 == "Yes"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_YES');?></option>
            <option value="No" <?php if($this->detail->use_gad2 == "No"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_NO');?></option>
            </select>      
      </td>
      <td width="50%"><?php echo JText::_('RS1_ADMIN_CONFIG_GAD_GRID_GAD2_HELP');?>&nbsp;</td>
    </tr>
    <tr class="admin_detail_row1">
      <td ><?php echo JText::_('RS1_ADMIN_CONFIG_GAD2_ROW_HEIGHT');?>:</td>
      <td><input type="text" size="3" maxsize="3" name="gad2_row_height" value="<?php echo $this->detail->gad2_row_height; ?>" /></td>

    </tr>
    <tr class="admin_detail_row0">
      <td width="20%"><?php echo JText::_('RS1_ADMIN_CONFIG_GAD_GRID_START_TIME');?>:</td>
      <td><select name="def_gad_grid_start" class="admin_dropdown">
      	<?php 
		for($x=0; $x<24; $x+=1){
			$x.=":00";
			echo "<option value=".$x; if($this->detail->def_gad_grid_start == $x) {echo " selected='selected' ";} echo ">".$x." </option>";  
		}
		?></select>      </td>
      <td width="50%">&nbsp;</td>
    </tr>
    <tr class="admin_detail_row1">
      <td><?php echo JText::_('RS1_ADMIN_CONFIG_GAD_GRID_END_TIME');?>:</td>
      <td><select name="def_gad_grid_end" class="admin_dropdown">
      	<?php 
		for($x=0; $x<=24; $x+=1){
			$x.=":00";
			echo "<option value=".$x; if($this->detail->def_gad_grid_end == $x) {echo " selected='selected' ";} echo ">".$x." </option>";  
		}
		?></select>	  </td>
      <td>&nbsp;</td>
    <tr class="admin_detail_row0">
      <td width="20%"><?php echo JText::_('RS1_ADMIN_CONFIG_GAD_GRID_HIDE_STARTSTOP');?>:</td>
      <td><select name="gad_grid_hide_startend">
            <option value="Yes" <?php if($this->detail->gad_grid_hide_startend == "Yes"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_YES');?></option>
            <option value="No" <?php if($this->detail->gad_grid_hide_startend == "No"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_NO');?></option>
            </select>      </td>
      <td width="50%"><?php echo JText::_('RS1_ADMIN_CONFIG_GAD_GRID_HIDE_STARTSTOP_HELP');?>&nbsp;</td>
    </tr>
	<tr class="admin_detail_row1">
	  <td><?php echo JText::_('RS1_ADMIN_CONFIG_GAD_GRID_START_DAY');?>:</td>
	  <td >
      <table width="100%" border="0" cellspacing="1" cellpadding="1">
          <tr>
            <td><input type="radio" name="rb_gad_grid_start_day" id="rb_gad_grid_start_day_today" value="rb_gad_grid_start_day_today"
                <?php echo ($this->detail->gad_grid_start_day == "Today" ? "checked='checked'" : "");?> onclick="setToday();" />&nbsp;
                  <?php echo JText::_('RS1_ADMIN_CONFIG_GAD_TODAY');?>&nbsp;</td>
          </tr>
          <tr>
            <td><input type="radio" name="rb_gad_grid_start_day" id="rb_gad_grid_start_day_tomorrow" value="rb_gad_grid_start_day_tomorrow"
                <?php echo ($this->detail->gad_grid_start_day == "Tomorrow" ? "checked='checked'" : "");?> onclick="setTomorrow();" />&nbsp;
                <?php echo JText::_('RS1_ADMIN_CONFIG_GAD_TOMORROW');?>&nbsp;</td>
          </tr>
          <tr>
            <td><input type="radio" name="rb_gad_grid_start_day" id="rb_gad_grid_start_day_monday" value="rb_gad_grid_start_day_monday"
                <?php echo ($this->detail->gad_grid_start_day == "Monday" ? "checked='checked'" : "");?> onclick="setMonday();" />&nbsp;
                <?php echo JText::_('RS1_ADMIN_CONFIG_GAD_MONDAY');?>&nbsp;</td>
          </tr>
          <tr>
            <td><input type="radio" name="rb_gad_grid_start_day" id="rb_gad_grid_start_day_xdays" value="rb_gad_grid_start_day_xdays"
                <?php echo ($this->detail->gad_grid_start_day == "XDays" ? "checked='checked'" : "");?> onclick="setXDays();" />&nbsp;
                <input type="text" size="2" name="gad_grid_start_day_days" id="gad_grid_start_day_days" value="<?php echo $this->detail->gad_grid_start_day_days?>" /> 
                <?php echo JText::_('RS1_ADMIN_CONFIG_GAD_DAYS');?>&nbsp;</td>
          </tr>
          <tr>
            <td><input type="radio" name="rb_gad_grid_start_day" id="rb_gad_grid_start_day_specific" value="rb_gad_grid_start_day_specific" 
                <?php echo (($this->detail->gad_grid_start_day != "Tomorrow" AND $this->detail->gad_grid_start_day != "Today" AND $this->detail->gad_grid_start_day != "Monday" AND $this->detail->gad_grid_start_day != "XDays")? "checked='checked'" : "");?>/>&nbsp;
                  <?php echo JText::_('RS1_ADMIN_CONFIG_GAD_SPECIFIC');?>:
                  <input type="text" name="gad_grid_start_day" id="gad_grid_start_day" size="12" readonly="readonly" value="<?php echo $this->detail->gad_grid_start_day; ?>" 
                onchange="set_gad_grid_start_day_radios();" />
                  <a href="#" id="anchor3" onclick="cal.select(document.forms['adminForm'].gad_grid_start_day,'anchor3','yyyy-MM-dd'); return false;"
                             name="anchor3"><img height="15" hspace="2" src="../components/com_rsappt_pro2/icon_cal.gif" width="16" border="0"></a>&nbsp;</td>
          </tr>
        </table>
	</td>
	  <td valign="top"><?php echo JText::_('RS1_ADMIN_CONFIG_GAD_START_DAY_HELP');?>    </td>
    </tr>
    <tr class="admin_detail_row0">
      <td ><?php echo JText::_('RS1_ADMIN_CONFIG_GAD_GRID_WIDTH');?>:</td>
      <td><input type="text" size="10" maxsize="20" name="gad_grid_width" value="<?php echo $this->detail->gad_grid_width; ?>" /></td>
      <td><?php echo JText::_('RS1_ADMIN_CONFIG_GAD_GRID_WIDTH_HELP');?></td>
    </tr>
    <tr class="admin_detail_row1">
      <td><?php echo JText::_('RS1_ADMIN_CONFIG_GAD_NAME_WIDTH');?>:</td>
      <td ><input type="text" size="10" maxsize="20" name="gad_name_width" value="<?php echo $this->detail->gad_name_width; ?>" /></td>
      <td><?php echo JText::_('RS1_ADMIN_CONFIG_GAD_NAME_WIDTH_HELP');?></td>
    </tr>
    <tr class="admin_detail_row0">
      <td><?php echo JText::_('RS1_ADMIN_CONFIG_GAD_AVAILABLE_IMAGE');?>:</td>
      <td><input type="text" size="60" maxsize="80" name="gad_available_image" value="<?php echo $this->detail->gad_available_image; ?>" />
      <td><?php echo JText::_('RS1_ADMIN_CONFIG_GAD_AVAILABLE_IMAGE_HELP');?></td>
    </tr>
    <tr class="admin_detail_row1">
      <td><?php echo JText::_('RS1_ADMIN_CONFIG_GAD_BOOKED_IMAGE');?>:</td>
      <td><input type="text" size="60" maxsize="80" name="gad_booked_image" value="<?php echo $this->detail->gad_booked_image; ?>" />
      <td><?php echo JText::_('RS1_ADMIN_CONFIG_GAD_BOOKED_IMAGE_HELP');?></td>
    </tr>
    <tr class="admin_detail_row0">
      <td><?php echo JText::_('RS1_ADMIN_CONFIG_GAD_DAYS_TO_SHOW');?>:</td>
      <td><input type="text" size="1" maxsize="2" name="gad_grid_num_of_days" value="<?php echo $this->detail->gad_grid_num_of_days; ?>" /></td>    
      <td><?php echo JText::_('RS1_ADMIN_CONFIG_GAD_DAYS_TO_SHOW_HELP');?></td>
    </tr>
    <tr class="admin_detail_row1">
      <td><?php echo JText::_('RS1_ADMIN_CONFIG_GAD_SHOW_AVAILABLE_SEATS');?>:</td>
      <td><select name="show_available_seats">
            <option value="Yes" <?php if($this->detail->show_available_seats == "Yes"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_YES');?></option>
            <option value="No" <?php if($this->detail->show_available_seats == "No"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_NO');?></option>
            </select></td>    
      <td><?php echo JText::_('RS1_ADMIN_CONFIG_GAD_SHOW_AVAILABLE_SEATS_HELP');?></td>
    </tr>
  </table>
  <?php    
	echo $pane->endPanel();
	echo $pane->startPanel(JText::_('RS1_ADMIN_CONFIG_MSG_TAB'), 'panel6');
	?>
<table border="0" cellpadding="2" cellspacing="0">
  <tr class="admin_detail_row0">
    <td colspan="3" ><?php echo JText::_('RS1_ADMIN_CONFIG_MSG_INTRO');?></td>
  </tr>
  <tr class="admin_detail_row1">
    <td width="15%" valign="top"><?php echo JText::_('RS1_ADMIN_CONFIG_MSG_BOOKING_COMPLETE');?>:</td>
    <td valign="top"><?php echo $editor->display( 'booking_succeeded',  $this->detail->booking_succeeded , '100%', '250', '75', '20' ) ;?></td>
    <td valign="top"><?php echo JText::_('RS1_ADMIN_CONFIG_MSG_BOOKING_COMPLETE_HELP');?></td>
  </tr>
  <tr class="admin_detail_row1">
    <td width="15%" valign="top"><?php echo JText::_('RS1_ADMIN_CONFIG_ICS');?>:</td>
    <td valign="top">
		<?php echo JText::_('RS1_ADMIN_CONFIG_ICS_TO_CUSTOMER');?>:&nbsp;<select name="attach_ics_customer">
            <option value="Yes" <?php if($this->detail->attach_ics_customer == "Yes"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_YES');?></option>
            <option value="No" <?php if($this->detail->attach_ics_customer == "No"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_NO');?></option>
            </select>&nbsp;&nbsp;
		<?php echo JText::_('RS1_ADMIN_CONFIG_ICS_TO_ADMIN');?>:&nbsp;<select name="attach_ics_admin">
            <option value="Yes" <?php if($this->detail->attach_ics_admin == "Yes"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_YES');?></option>
            <option value="No" <?php if($this->detail->attach_ics_admin == "No"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_NO');?></option>
            </select>&nbsp;&nbsp;
		<?php echo JText::_('RS1_ADMIN_CONFIG_ICS_TO_RESOURCE');?>:&nbsp;<select name="attach_ics_resource">
            <option value="Yes" <?php if($this->detail->attach_ics_resource == "Yes"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_YES');?></option>
            <option value="No" <?php if($this->detail->attach_ics_resource == "No"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_NO');?></option>
            </select>
            </td>
    <td valign="top"><?php echo JText::_('RS1_ADMIN_CONFIG_ICS_HELP');?></td>
  </tr>
  <tr  class="admin_detail_row1">
    <td valign="top"><?php echo JText::_('RS1_ADMIN_CONFIG_MSG_SMS_BOOKING_COMPLETE');?>:</td>
    <td valign="top"><textarea name="booking_succeeded_sms" rows="3" cols="70"><?php echo stripslashes($this->detail->booking_succeeded_sms); ?></textarea></td>
    <td valign="top"><?php echo JText::_('RS1_ADMIN_CONFIG_MSG_SMS_BOOKING_COMPLETE_HELP');?></td>
  </tr>
  <tr  class="admin_detail_row0">
    <td valign="top"><?php echo JText::_('RS1_ADMIN_CONFIG_MSG_BOOKING_INPROGRESS');?>:</td>
    <td valign="top"><?php echo $editor->display( 'booking_in_progress',  $this->detail->booking_in_progress , '100%', '250', '75', '20' ) ;?></td>
    <td valign="top"><?php echo JText::_('RS1_ADMIN_CONFIG_MSG_BOOKING_INPROGRESS_HELP');?></td>
  </tr>
  <tr  class="admin_detail_row0">
    <td valign="top"><?php echo JText::_('RS1_ADMIN_CONFIG_MSG_SMS_BOOKING_INPROGRESS');?>:</td>
    <td valign="top"><textarea name="booking_in_progress_sms" rows="3" cols="70"><?php echo stripslashes($this->detail->booking_in_progress_sms); ?></textarea></td>
    <td valign="top"><?php echo JText::_('RS1_ADMIN_CONFIG_MSG_SMS_BOOKING_INPROGRESS_HELP');?></td>
  </tr>
  <tr  class="admin_detail_row1">
    <td valign="top"><?php echo JText::_('RS1_ADMIN_CONFIG_MSG_CANCELLATION');?>: </td>
    <td valign="top"><?php echo $editor->display( 'booking_cancel',  $this->detail->booking_cancel , '100%', '250', '75', '20' ) ;?></td>

    <td valign="top"><?php echo JText::_('RS1_ADMIN_CONFIG_MSG_CANCELLATION_HELP');?></td>
  </tr>
  <tr  class="admin_detail_row1">
    <td valign="top"><?php echo JText::_('RS1_ADMIN_CONFIG_MSG_SMS_CANCELLATION');?>:</td>
    <td valign="top"><textarea name="booking_cancel_sms" rows="3" cols="70"><?php echo stripslashes($this->detail->booking_cancel_sms); ?></textarea></td>
    <td valign="top"><?php echo JText::_('RS1_ADMIN_CONFIG_MSG_SMS_CANCELLATION_HELP');?></td>
  </tr>
  <tr  class="admin_detail_row0">
    <td valign="top"><?php echo JText::_('RS1_ADMIN_CONFIG_MSG_TOO_LATE');?>:</td>
    <td valign="top"><?php echo $editor->display( 'booking_too_close_to_cancel',  $this->detail->booking_too_close_to_cancel , '100%', '150', '75', '20' ) ;?></td>
    <td valign="top"><?php echo JText::_('RS1_ADMIN_CONFIG_MSG_TOO_LATE_HELP');?></td>
  </tr>
  <tr  class="admin_detail_row1">
    <td valign="top"><?php echo JText::_('RS1_ADMIN_CONFIG_MSG_REMINDER');?>:</td>
    <td valign="top"><?php echo $editor->display( 'booking_reminder',  $this->detail->booking_reminder , '100%', '250', '75', '20' ) ;?></td>
    <td valign="top"><?php echo JText::_('RS1_ADMIN_CONFIG_MSG_REMINDER_HELP');?></td>
  </tr>
  <tr  class="admin_detail_row1">
    <td valign="top"><?php echo JText::_('RS1_ADMIN_CONFIG_MSG_SMS_REMINDER');?>:</td>
    <td valign="top"><textarea name="booking_reminder_sms" rows="3" cols="70"><?php echo stripslashes($this->detail->booking_reminder_sms); ?></textarea></td>
    <td valign="top"><?php echo JText::_('RS1_ADMIN_CONFIG_MSG_SMS_REMINDER_HELP');?></td>
  </tr>
<tr>
  <td valign="top" colspan="3"><hr /></td>
  </tr>
  <tr  class="admin_detail_row0">
    <td valign="top"><?php echo JText::_('RS1_ADMIN_CONFIG_MSG_TOKEN_INTRO');?></td>
    <td colspan="2"><table  border="0" cellpadding="4">
      <tr>
        <td><strong><?php echo JText::_('RS1_ADMIN_CONFIG_MSG_TOKEN');?></strong></td>
        <td><strong><?php echo JText::_('RS1_ADMIN_CONFIG_MSG_TOKEN_REPLACE');?></strong></td>
        <td width="5%">&nbsp; </td>
        <td><strong><?php echo JText::_('RS1_ADMIN_CONFIG_MSG_TOKEN');?></strong></td>
        <td><strong><?php echo JText::_('RS1_ADMIN_CONFIG_MSG_TOKEN_REPLACE');?></strong></td>
      </tr>
      <tr>
        <td>[resource]</td>
        <td><?php echo JText::_('RS1_ADMIN_CONFIG_MSG_TOKEN_1');?></td>
        <td>&nbsp;</td>
        <td>[resource_category]</td>
        <td><?php echo JText::_('RS1_ADMIN_CONFIG_MSG_TOKEN_2');?></td>
      </tr>
      <tr>
        <td>[requester name]</td>
        <td><?php echo JText::_('RS1_ADMIN_CONFIG_MSG_TOKEN_3');?></td>
        <td>&nbsp;</td>
        <td>[resource_service]</td>
        <td><?php echo JText::_('RS1_ADMIN_CONFIG_MSG_TOKEN_4');?></td>
      </tr>
      <tr>
        <td>[startdate]</td>
        <td><?php echo JText::_('RS1_ADMIN_CONFIG_MSG_TOKEN_5');?></td>
        <td>&nbsp;</td>
        <td>[phone]</td>
        <td><?php echo JText::_('RS1_ADMIN_CONFIG_MSG_TOKEN_6');?></td>
      </tr>
      <tr>
        <td>[starttime]</td>
        <td><?php echo JText::_('RS1_ADMIN_CONFIG_MSG_TOKEN_7');?></td>
        <td>&nbsp;</td>
        <td>[email]</td>
        <td><?php echo JText::_('RS1_ADMIN_CONFIG_MSG_TOKEN_8');?></td>
      </tr>
      <tr>
        <td>[enddate]</td>
        <td><?php echo JText::_('RS1_ADMIN_CONFIG_MSG_TOKEN_9');?></td>
        <td>&nbsp;</td>
        <td>[cancellation_id]</td>
        <td><?php echo JText::_('RS1_ADMIN_CONFIG_MSG_TOKEN_10');?></td>
      </tr>
      <tr>
        <td>[endtime]</td>
        <td><?php echo JText::_('RS1_ADMIN_CONFIG_MSG_TOKEN_11');?></td>
        <td>&nbsp;</td>
        <td>[booking_total]</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>[booking_due]</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>[coupon]</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>[booking_id]</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td colspan="5"><hr /></td>
        </tr>
      <tr>
        <td><?php echo JText::_('RS1_ADMIN_CONFIG_MSG_TOKEN_UDF_1');?></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><?php echo JText::_('RS1_ADMIN_CONFIG_MSG_TOKEN_UDF_2');?></td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td><?php echo JText::_('RS1_ADMIN_CONFIG_MSG_TOKEN_UDF_3');?></td>
        <td><?php echo JText::_('RS1_ADMIN_CONFIG_MSG_TOKEN_UDF_4');?> </td>
        <td>&nbsp;</td>
        <td><?php echo JText::_('RS1_ADMIN_CONFIG_MSG_TOKEN_UDF_5');?></td>
        <td><?php echo JText::_('RS1_ADMIN_CONFIG_MSG_TOKEN_UDF_6');?></td>
      </tr>
      <tr>
        <td colspan="5"><hr /></td>
        </tr>
      <tr>
        <td><?php echo JText::_('RS1_ADMIN_CONFIG_MSG_TOKEN_EXTRAS_1');?></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><?php echo JText::_('RS1_ADMIN_CONFIG_MSG_TOKEN_EXTRAS_2');?></td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td><?php echo JText::_('RS1_ADMIN_CONFIG_MSG_TOKEN_EXTRAS_3');?></td>
        <td><?php echo JText::_('RS1_ADMIN_CONFIG_MSG_TOKEN_EXTRAS_4');?> </td>
        <td>&nbsp;</td>
        <td><?php echo JText::_('RS1_ADMIN_CONFIG_MSG_TOKEN_EXTRAS_5');?></td>
        <td><?php echo JText::_('RS1_ADMIN_CONFIG_MSG_TOKEN_EXTRAS_6');?></td>
      </tr>
      <tr>
        <td colspan="5"><hr /></td>
        </tr>
      <tr>
      <tr>
        <td><?php echo JText::_('RS1_ADMIN_CONFIG_MSG_TOKEN_SEATS_1');?></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><?php echo JText::_('RS1_ADMIN_CONFIG_MSG_TOKEN_SEATS_2');?></td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td><?php echo JText::_('RS1_ADMIN_CONFIG_MSG_TOKEN_SEATS_3');?></td>
        <td><?php echo JText::_('RS1_ADMIN_CONFIG_MSG_TOKEN_SEATS_4');?> </td>
        <td>&nbsp;</td>
        <td><?php echo JText::_('RS1_ADMIN_CONFIG_MSG_TOKEN_SEATS_5');?></td>
        <td><?php echo JText::_('RS1_ADMIN_CONFIG_MSG_TOKEN_SEATS_6');?></td>
      </tr>

    </table>      
    <p>&nbsp;</p>      </td>
  </tr>
</table>
  <?php    
	echo $pane->endPanel();
	echo $pane->startPanel(JText::_('RS1_ADMIN_CONFIG_PAYPAL_TAB'), 'panel7');
	?>
  <table cellpadding="4" cellspacing="0" border="0"  >
    <tr class="admin_detail_row0" >
      <td><?php echo JText::_('RS1_ADMIN_CONFIG_PAYPAL_ENABLE');?>: </td>
      <td><select name="enable_paypal">
          <option value="Yes" <?php if($this->detail->enable_paypal == "Yes"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_YES');?></option>
          <option value="No" <?php if($this->detail->enable_paypal == "No"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_NO');?></option>
          <option value="Opt" <?php if($this->detail->enable_paypal == "Opt"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_CONFIG_PAYPAL_OPTIONAL');?></option>
        </select>
        &nbsp;&nbsp;

    <img src="../administrator/components/com_rsappt_pro2/tooltip.png" border="0" style="padding-left:5px" 
            onmouseover="return overlib('<?php echo JText::_('RS1_ADMIN_CONFIG_PAYPAL_ENABLE_HELP');?>', CAPTION, '<?php echo JText::_('RS1_ADMIN_CONFIG_PAYPAL_ENABLE');?>', BELOW, RIGHT);" 
            onmouseout="return nd();" />    </tr>
        <input type="hidden" name="accept_when_paid" value="Yes" />
<!--    <tr class="admin_detail_row0">
      <td><?php echo JText::_('RS1_ADMIN_CONFIG_PAYPAL_ACCEPT_PAID');?>: </td>
      <td><select name="accept_when_paid">
          <option value="Yes" <?php if($this->detail->accept_when_paid == "Yes"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_YES');?></option>
          <option value="No" <?php if($this->detail->accept_when_paid == "No"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_NO');?></option>
        </select>
        &nbsp;&nbsp;

    <img src="../administrator/components/com_rsappt_pro2/tooltip.png" border="0" style="padding-left:5px" 
            onmouseover="return overlib('<?php echo JText::_('RS1_ADMIN_CONFIG_PAYPAL_ACCEPT_PAID_HELP');?>', CAPTION, '<?php echo JText::_('RS1_ADMIN_CONFIG_PAYPAL_ACCEPT_PAID');?>', BELOW, RIGHT);" 
            onmouseout="return nd();" />    </tr>
-->    <tr class="admin_detail_row1">
      <td><?php echo JText::_('RS1_ADMIN_CONFIG_PAYPAL_FEE');?>: </td>
      <td><input type="text" id="additional_fee" name="additional_fee" size="4" maxsize="5" value="<?php echo $this->detail->additional_fee ?>" />        &nbsp;&nbsp;

    <img src="../administrator/components/com_rsappt_pro2/tooltip.png" border="0" style="padding-left:5px" 
            onmouseover="return overlib('<?php echo JText::_('RS1_ADMIN_CONFIG_PAYPAL_FEE_HELP');?>', CAPTION, '<?php echo JText::_('RS1_ADMIN_CONFIG_PAYPAL_FEE');?>', BELOW, RIGHT);" 
            onmouseout="return nd();" />&nbsp;&nbsp;
        <select name="fee_rate">
          <option value="Fixed" <?php if($this->detail->fee_rate == "Fixed"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_CONFIG_PAYPAL_FEE_FIXED');?></option>
          <option value="Percent" <?php if($this->detail->fee_rate == "Percent"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_CONFIG_PAYPAL_FEE_PERCENT');?></option>
        </select>      </td>       
    </tr>
    <tr class="admin_detail_row0">
      <td><?php echo JText::_('RS1_ADMIN_CONFIG_COUPON_ENABLE');?>: </td>
      <td><select name="enable_coupons">
          <option value="Yes" <?php if($this->detail->enable_coupons == "Yes"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_YES');?></option>
          <option value="No" <?php if($this->detail->enable_coupons == "No"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_NO');?></option>
        </select>
        &nbsp;&nbsp;

    <img src="../administrator/components/com_rsappt_pro2/tooltip.png" border="0" style="padding-left:5px" 
            onmouseover="return overlib('<?php echo JText::_('RS1_ADMIN_CONFIG_COUPON_ENABLE_HELP');?>', CAPTION, '<?php echo JText::_('RS1_ADMIN_CONFIG_COUPON_ENABLE');?>', BELOW, RIGHT);" 
            onmouseout="return nd();" />
    </tr>
    <tr class="admin_detail_row1">
      <td><?php echo JText::_('RS1_ADMIN_CONFIG_PAYPAL_BUTTON');?>:</td>
      <td><input type="text" size="70" maxsize="255" name="paypal_button_url" value="<?php echo $this->detail->paypal_button_url; ?>" />
      &nbsp;&nbsp;
      <script type="text/javascript" src="../includes/js/overlib_mini.js"></script>
    	<img src="../administrator/components/com_rsappt_pro2/tooltip.png" border="0" style="padding-left:5px" 
            onmouseover="return overlib('<?php echo JText::_('RS1_ADMIN_CONFIG_PAYPAL_BUTTON_HELP');?>', CAPTION, '<?php echo JText::_('RS1_ADMIN_CONFIG_PAYPAL_BUTTON');?>', BELOW, RIGHT);" 
            onmouseout="return nd();" />
      </td>
    </tr>
    <tr class="admin_detail_row0">
      <td><?php echo JText::_('RS1_ADMIN_CONFIG_PAYPAL_CUR_CODE');?>:</td>
      <td><select name="paypal_currency_code"> 
          <?php
			$k = 0;
			for($i=0; $i < count( $currency_rows ); $i++) {
			$currency_row = $currency_rows[$i];
			?>
				  <option value="<?php echo $currency_row->code; ?>" <?php if($this->detail->paypal_currency_code == $currency_row->code){echo " selected='selected' ";} ?>><?php echo $currency_row->code." - ".$currency_row->description; ?></option>
				  <?php $k = 1 - $k; 
			} ?>
        </select>&nbsp;</td>
    </tr>
    <tr class="admin_detail_row1">
      <td><?php echo JText::_('RS1_ADMIN_CONFIG_PAYPAL_ACCOUNT');?>:</td>
      <td><input type="text" size="70" maxsize="255" name="paypal_account" value="<?php echo $this->detail->paypal_account; ?>" />
      &nbsp;&nbsp;
      <script type="text/javascript" src="../includes/js/overlib_mini.js"></script>
    	<img src="../administrator/components/com_rsappt_pro2/tooltip.png" border="0" style="padding-left:5px" 
            onmouseover="return overlib('<?php echo JText::_('RS1_ADMIN_CONFIG_PAYPAL_ACCOUNT_HELP');?>', CAPTION, '<?php echo JText::_('RS1_ADMIN_CONFIG_PAYPAL_ACCOUNT');?>', BELOW, RIGHT);" 
            onmouseout="return nd();" />      </td>
    </tr>
    <tr class="admin_detail_row0">
      <td><?php echo JText::_('RS1_ADMIN_CONFIG_PAYPAL_SANDBOX');?>:</td>
      <td><input type="text" size="70" maxsize="255" name="paypal_sandbox_url" value="<?php echo $this->detail->paypal_sandbox_url; ?>" />
      &nbsp;&nbsp;<script type="text/javascript" src="../includes/js/overlib_mini.js"></script>
    	<img src="../administrator/components/com_rsappt_pro2/tooltip.png" border="0" style="padding-left:5px" 
            onmouseover="return overlib('<?php echo JText::_('RS1_ADMIN_CONFIG_PAYPAL_SANDBOX_HELP');?>', CAPTION, '<?php echo JText::_('RS1_ADMIN_CONFIG_PAYPAL_SANDBOX');?>', BELOW, RIGHT);" 
            onmouseout="return nd();" />       </td>
    </tr>
    <tr class="admin_detail_row1">
      <td><?php echo JText::_('RS1_ADMIN_CONFIG_PAYPAL_PROD');?>:</td>
      <td><input type="text" size="70" maxsize="255" name="paypal_production_url" value="<?php echo $this->detail->paypal_production_url; ?>" />&nbsp;</td>
    </tr>
    <tr class="admin_detail_row0">
      <td><?php echo JText::_('RS1_ADMIN_CONFIG_PAYPAL_USE_SAND');?>: </td>
      <td><select name="paypal_use_sandbox">
          <option value="Yes" <?php if($this->detail->paypal_use_sandbox == "Yes"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_YES');?></option>
          <option value="No" <?php if($this->detail->paypal_use_sandbox == "No"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_NO');?></option>
        </select>
        &nbsp;&nbsp;

   	   <img src="../administrator/components/com_rsappt_pro2/tooltip.png" border="0" style="padding-left:5px" 
            onmouseover="return overlib('<?php echo JText::_('RS1_ADMIN_CONFIG_PAYPAL_USE_SAND_HELP');?>', CAPTION, '<?php echo JText::_('RS1_ADMIN_CONFIG_PAYPAL_USE_SAND');?>', BELOW, RIGHT);" 
            onmouseout="return nd();" /><td>
    </tr>
    <tr class="admin_detail_row1">
      <td><?php echo JText::_('RS1_ADMIN_CONFIG_PAYPAL_YOUR_LOGO');?>:</td>
      <td><input type="text" size="70" maxsize="255" name="paypal_logo_url" value="<?php echo $this->detail->paypal_logo_url; ?>" />
      &nbsp;&nbsp;
      <script type="text/javascript" src="../includes/js/overlib_mini.js"></script>
    	<img src="../administrator/components/com_rsappt_pro2/tooltip.png" border="0" style="padding-left:5px" 
            onmouseover="return overlib('<?php echo JText::_('RS1_ADMIN_CONFIG_PAYPAL_YOUR_LOGO_HELP');?>', CAPTION, '<?php echo JText::_('RS1_ADMIN_CONFIG_PAYPAL_YOUR_LOGO');?>', BELOW, RIGHT);" 
            onmouseout="return nd();" />
      </td>
    </tr>
    <tr class="admin_detail_row0">
      <td><?php echo JText::_('RS1_ADMIN_CONFIG_PAYPAL_ITEM_NAME');?>:</td>
      <td><input type="text" size="70" maxsize="126" name="paypal_itemname" value="<?php echo $this->detail->paypal_itemname; ?>" />
      &nbsp;&nbsp;
      <script type="text/javascript" src="../includes/js/overlib_mini.js"></script>
    	<img src="../administrator/components/com_rsappt_pro2/tooltip.png" border="0" style="padding-left:5px" 
            onmouseover="return overlib('<?php echo JText::_('RS1_ADMIN_CONFIG_PAYPAL_ITEM_NAME_HELP');?>', CAPTION, '<?php echo JText::_('RS1_ADMIN_CONFIG_PAYPAL_ITEM_NAME');?>', BELOW, RIGHT);" 
            onmouseout="return nd();" />
      </td>
    </tr>
    <tr class="admin_detail_row1">
      <td><?php echo JText::_('RS1_ADMIN_CONFIG_PAYPAL_OPTIONAL_NAME');?> 0:</td>
      <td><input type="text" size="70" maxsize="67" name="paypal_on0" value="<?php echo $this->detail->paypal_on0; ?>" />
      &nbsp;&nbsp;
      <script type="text/javascript" src="../includes/js/overlib_mini.js"></script>
    	<img src="../administrator/components/com_rsappt_pro2/tooltip.png" border="0" style="padding-left:5px" 
            onmouseover="return overlib('<?php echo JText::_('RS1_ADMIN_CONFIG_PAYPAL_OPTIONAL_NAME_HELP');?>', CAPTION, '<?php echo JText::_('RS1_ADMIN_CONFIG_PAYPAL_OPTIONAL_NAME');?>', BELOW, RIGHT);" 
            onmouseout="return nd();" />
      </td>
    </tr>
    <tr class="admin_detail_row1">
      <td><?php echo JText::_('RS1_ADMIN_CONFIG_PAYPAL_OPTIONAL_VALUE');?> 0:</td>
      <td><input type="text" size="70" maxsize="200" name="paypal_os0" value="<?php echo $this->detail->paypal_os0; ?>" />
      &nbsp;&nbsp;
      </td>
    </tr>
    <tr class="admin_detail_row0">
      <td><?php echo JText::_('RS1_ADMIN_CONFIG_PAYPAL_OPTIONAL_NAME');?> 1:</td>
      <td><input type="text" size="70" maxsize="67" name="paypal_on1" value="<?php echo $this->detail->paypal_on1; ?>" />
      &nbsp;&nbsp;
      <script type="text/javascript" src="../includes/js/overlib_mini.js"></script>
    	<img src="../administrator/components/com_rsappt_pro2/tooltip.png" border="0" style="padding-left:5px" 
            onmouseover="return overlib('<?php echo JText::_('RS1_ADMIN_CONFIG_PAYPAL_OPTIONAL_NAME_HELP');?>', CAPTION, '<?php echo JText::_('RS1_ADMIN_CONFIG_PAYPAL_OPTIONAL_NAME');?>', BELOW, RIGHT);" 
            onmouseout="return nd();" />
      </td>
    </tr>
    <tr class="admin_detail_row0">
      <td><?php echo JText::_('RS1_ADMIN_CONFIG_PAYPAL_OPTIONAL_VALUE');?> 1:</td>
      <td><input type="text" size="70" maxsize="200" name="paypal_os1" value="<?php echo $this->detail->paypal_os1; ?>" />
      &nbsp;&nbsp;
      </td>
    </tr>
    <tr class="admin_detail_row1">
      <td><?php echo JText::_('RS1_ADMIN_CONFIG_PAYPAL_OPTIONAL_NAME');?> 2:</td>
      <td><input type="text" size="70" maxsize="67" name="paypal_on2" value="<?php echo $this->detail->paypal_on2; ?>" />
      &nbsp;&nbsp;
      <script type="text/javascript" src="../includes/js/overlib_mini.js"></script>
    	<img src="../administrator/components/com_rsappt_pro2/tooltip.png" border="0" style="padding-left:5px" 
            onmouseover="return overlib('<?php echo JText::_('RS1_ADMIN_CONFIG_PAYPAL_OPTIONAL_NAME_HELP');?>', CAPTION, '<?php echo JText::_('RS1_ADMIN_CONFIG_PAYPAL_OPTIONAL_NAME');?>', BELOW, RIGHT);" 
            onmouseout="return nd();" />
      </td>
    </tr>
    <tr class="admin_detail_row1">
      <td><?php echo JText::_('RS1_ADMIN_CONFIG_PAYPAL_OPTIONAL_VALUE');?> 2:</td>
      <td><input type="text" size="70" maxsize="200" name="paypal_os2" value="<?php echo $this->detail->paypal_os2; ?>" />
      &nbsp;&nbsp;
      </td>
    </tr>
    <tr class="admin_detail_row0">
      <td><?php echo JText::_('RS1_ADMIN_CONFIG_PAYPAL_OPTIONAL_NAME');?> 3:</td>
      <td><input type="text" size="70" maxsize="67" name="paypal_on3" value="<?php echo $this->detail->paypal_on3; ?>" />
      &nbsp;&nbsp;
      <script type="text/javascript" src="../includes/js/overlib_mini.js"></script>
    	<img src="../administrator/components/com_rsappt_pro2/tooltip.png" border="0" style="padding-left:5px" 
            onmouseover="return overlib('<?php echo JText::_('RS1_ADMIN_CONFIG_PAYPAL_OPTIONAL_NAME_HELP');?>', CAPTION, '<?php echo JText::_('RS1_ADMIN_CONFIG_PAYPAL_OPTIONAL_NAME');?>', BELOW, RIGHT);" 
            onmouseout="return nd();" />
      </td>
    </tr>
    <tr class="admin_detail_row0">
      <td><?php echo JText::_('RS1_ADMIN_CONFIG_PAYPAL_OPTIONAL_VALUE');?> 3:</td>
      <td><input type="text" size="70" maxsize="200" name="paypal_os3" value="<?php echo $this->detail->paypal_os3; ?>" />
      &nbsp;&nbsp;
      </td>
    </tr>
     <tr class="admin_detail_row1">
      <td><?php echo JText::_('RS1_ADMIN_CONFIG_PAYPAL_PURGE_STALE');?>: </td>
      <td><select name="purge_stale_paypal">
          <option value="Yes" <?php if($this->detail->purge_stale_paypal == "Yes"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_YES');?></option>
          <option value="No" <?php if($this->detail->purge_stale_paypal == "No"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_NO');?></option>
        </select>
        &nbsp;&nbsp;<?php echo JText::_('RS1_ADMIN_CONFIG_PAYPAL_PURGE_AFTER');?>&nbsp;
        <input type="text" style="text-align:center" size="3" name="minutes_to_stale" value="<?php echo $this->detail->minutes_to_stale?>" />&nbsp;
		<?php echo JText::_('RS1_ADMIN_CONFIG_PAYPAL_PURGE_MINUTES');?>

    <img src="../administrator/components/com_rsappt_pro2/tooltip.png" border="0" style="padding-left:5px" 
            onmouseover="return overlib('<?php echo JText::_('RS1_ADMIN_CONFIG_PAYPAL_PURGE_STALE_HELP');?>', CAPTION, '<?php echo JText::_('RS1_ADMIN_CONFIG_PAYPAL_PURGE_STALE');?>', BELOW, RIGHT);" 
            onmouseout="return nd();" />
    </tr>

  </table>

  <?php    
	echo $pane->endPanel();
	echo $pane->startPanel(JText::_('RS1_ADMIN_CONFIG_CLICKATELL_TAB'), 'panel8');
	
	// get dialing codes
	$database = &JFactory::getDBO();
	$database->setQuery("SELECT * FROM #__sv_apptpro2_dialing_codes ORDER BY country" );
	$dial_rows = $database -> loadObjectList();
	if ($database -> getErrorNum()) {
		echo $database -> stderr();
		return false;
	}
	
	
	?>
  <table cellpadding="4" cellspacing="0" border="0"  >
    <tr class="admin_detail_row0">
      <td colspan="3"><?php echo JText::_('RS1_ADMIN_CONFIG_CLICKATELL_INTRO');?><br />Due to strict opt-in rules of US carriers, <b>ABPro can no longer offer SMS in the USA</b></td>
    </tr>
    <tr class="admin_detail_row1">
      <td><?php echo JText::_('RS1_ADMIN_CONFIG_CLICKATELL_ENABLE');?>: </td>
      <td><select name="enable_clickatell">
          <option value="Yes" <?php if($this->detail->enable_clickatell == "Yes"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_YES');?></option>
          <option value="No" <?php if($this->detail->enable_clickatell == "No"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_NO');?></option>
        </select>
        &nbsp;&nbsp;      
      <td><?php echo JText::_('RS1_ADMIN_CONFIG_CLICKATELL_ENABLE_HELP');?></td>   
    </tr>
    <tr class="admin_detail_row0">
      <td><?php echo JText::_('RS1_ADMIN_CONFIG_CLICKATELL_USER');?>: </td>
      <td><input type="text" size="20" maxsize="50" name="clickatell_user" value="<?php echo $this->detail->clickatell_user; ?>" /></td>    
      <td><?php echo JText::_('RS1_ADMIN_CONFIG_CLICKATELL_USER_HELP');?></td>   
    </tr>
    <tr class="admin_detail_row1">
      <td><?php echo JText::_('RS1_ADMIN_CONFIG_CLICKATELL_PWD');?>: </td>
      <td><input type="password" size="20" maxsize="50" name="clickatell_password" value="<?php echo $this->detail->clickatell_password; ?>" /></td>    
      <td><?php echo JText::_('RS1_ADMIN_CONFIG_CLICKATELL_PWD_HELP');?></td>   
    </tr>
    <tr class="admin_detail_row0">
      <td><?php echo JText::_('RS1_ADMIN_CONFIG_CLICKATELL_API_ID');?>: </td>
      <td><input type="text" size="15" maxsize="50" name="clickatell_api_id" value="<?php echo $this->detail->clickatell_api_id; ?>" /></td>    
      <td><?php echo JText::_('RS1_ADMIN_CONFIG_CLICKATELL_API_ID_HELP');?></td>   
    </tr>
    <tr class="admin_detail_row1">
      <td><?php echo JText::_('RS1_ADMIN_CONFIG_CLICKATELL_DIAL_CODE');?>:</td>
      <td><select name="clickatell_dialing_code" >
              <?php
				$k = 0;
				for($i=0; $i < count( $dial_rows ); $i++) {
				$dial_row = $dial_rows[$i];
				?>
          <option value="<?php echo $dial_row->dial_code; ?>"  <?php if($this->detail->clickatell_dialing_code == $dial_row->dial_code){echo " selected='selected' ";} ?>><?php echo $dial_row->country." - ".$dial_row->dial_code ?></option>
              <?php $k = 1 - $k; 
				} ?>
      </select>&nbsp;</td>
      <td><?php echo JText::_('RS1_ADMIN_CONFIG_CLICKATELL_DIAL_CODE_HELP');?></td>   
    </tr>
    <tr class="admin_detail_row0">
      <td><?php echo JText::_('RS1_ADMIN_CONFIG_CLICKATELL_SHOW_CODE');?>:</td>
      <td><select name="clickatell_show_code">
          <option value="Yes" <?php if($this->detail->clickatell_show_code == "Yes"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_YES');?></option>
          <option value="No" <?php if($this->detail->clickatell_show_code == "No"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_NO');?></option>
        </select>
      &nbsp;&nbsp;</td>
      <td><?php echo JText::_('RS1_ADMIN_CONFIG_CLICKATELL_SHOW_CODE_HELP');?></td>
    </tr>
    <tr class="admin_detail_row1">
      <td><?php echo JText::_('RS1_ADMIN_CONFIG_CLICKATELL_ENABLE_UNICODE');?>:</td>
      <td><select name="clickatell_enable_unicode">
          <option value="Yes" <?php if($this->detail->clickatell_enable_unicode == "Yes"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_YES');?></option>
          <option value="No" <?php if($this->detail->clickatell_enable_unicode == "No"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_NO');?></option>
        </select>
      &nbsp;&nbsp;</td>
      <td><?php echo JText::_('RS1_ADMIN_CONFIG_CLICKATELL_ENABLE_UNICODE_HELP');?></td>
    </tr>
    <tr class="admin_detail_row0">
      <td colspan="3"><hr /><?php echo JText::_('RS1_ADMIN_CONFIG_CLICKATELL_FOOTER');?></td>
    </tr>
  </table>
  <?php    
	echo $pane->endPanel();
	echo $pane->startPanel(JText::_('RS1_ADMIN_CONFIG_FRONT_TAB'), 'panel7');
	?>
  <table cellpadding="4" cellspacing="0" border="0"  >
	    <tr class="admin_detail_row1">
    	  <td colspan="3"><?php echo JText::_('RS1_ADMIN_CONFIG_FE_ADV_ADMIN');?></td>
	    </tr>
        <tr class="admin_detail_row0">
          <td><?php echo JText::_('RS1_ADMIN_CONFIG_SHOW_RESOURCES');?>:</td>
          <td><select name="adv_admin_show_resources">
              <option value="Yes" <?php if($this->detail->adv_admin_show_resources == "Yes"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_YES');?></option>
              <option value="No" <?php if($this->detail->adv_admin_show_resources == "No"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_NO');?></option>
            </select>
		  </td>
          <td width="50%">&nbsp;</td>
        </tr>
        <tr class="admin_detail_row1">
          <td><?php echo JText::_('RS1_ADMIN_CONFIG_SHOW_SERVICES');?>:</td>
          <td><select name="adv_admin_show_services">
              <option value="Yes" <?php if($this->detail->adv_admin_show_services == "Yes"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_YES');?></option>
              <option value="No" <?php if($this->detail->adv_admin_show_services == "No"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_NO');?></option>
            </select>
		  </td>
          <td>&nbsp;</td>
        </tr>
        <tr class="admin_detail_row0">
          <td><?php echo JText::_('RS1_ADMIN_CONFIG_SHOW_TIMESLOTS');?>:</td>
          <td><select name="adv_admin_show_timeslots">
              <option value="Yes" <?php if($this->detail->adv_admin_show_timeslots == "Yes"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_YES');?></option>
              <option value="No" <?php if($this->detail->adv_admin_show_timeslots == "No"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_NO');?></option>
            </select>
		  </td>
          <td>&nbsp;</td>
        </tr>
        <tr class="admin_detail_row1">
          <td><?php echo JText::_('RS1_ADMIN_CONFIG_SHOW_BOOKOFFS');?>:</td>
          <td><select name="adv_admin_show_bookoffs">
              <option value="Yes" <?php if($this->detail->adv_admin_show_bookoffs == "Yes"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_YES');?></option>
              <option value="No" <?php if($this->detail->adv_admin_show_bookoffs == "No"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_NO');?></option>
            </select>
		  </td>
          <td>&nbsp;</td>
        </tr>
        <tr class="admin_detail_row0">
          <td><?php echo JText::_('RS1_ADMIN_CONFIG_SHOW_PAYPAL');?>:</td>
          <td><select name="adv_admin_show_paypal">
              <option value="Yes" <?php if($this->detail->adv_admin_show_paypal == "Yes"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_YES');?></option>
              <option value="No" <?php if($this->detail->adv_admin_show_paypal == "No"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_NO');?></option>
            </select>
		  </td>
          <td>&nbsp;</td>
        </tr>
        <tr class="admin_detail_row1">
          <td><?php echo JText::_('RS1_ADMIN_CONFIG_SHOW_COUPONS');?>:</td>
          <td><select name="adv_admin_show_coupons">
              <option value="Yes" <?php if($this->detail->adv_admin_show_coupons == "Yes"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_YES');?></option>
              <option value="No" <?php if($this->detail->adv_admin_show_coupons == "No"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_NO');?></option>
            </select>
		  </td>
          <td>&nbsp;</td>
        </tr>
        <tr class="admin_detail_row0">
          <td><?php echo JText::_('RS1_ADMIN_CONFIG_SHOW_EXTRAS');?>:</td>
          <td><select name="adv_admin_show_extras">
              <option value="Yes" <?php if($this->detail->adv_admin_show_extras == "Yes"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_YES');?></option>
              <option value="No" <?php if($this->detail->adv_admin_show_extras == "No"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_NO');?></option>
            </select>
		  </td>
          <td>&nbsp;</td>
        </tr>
	</table>
    <hr />
  <?php    
	echo $pane->endPanel();
	}
	// end tab pane
	echo $pane->endPane();

?>
</fieldset>
  <input type="hidden" name="id_config" value="<?php echo $this->detail->id_config; ?>" />
  <input type="hidden" name="task" value="" />
  <input type="hidden" name="controller" value="config_detail" />
  <br />
  <span style="font-size:10px"> Appointment Booking Pro Ver. 2.0 - Copyright 2008-20<?php echo date("y");?> - <a href='http://www.softventures.com' target="_blank">Soft Ventures, Inc.</a></span>
</form>
