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
 
	jimport( 'joomla.application.helper' );
	jimport('joomla.filter.output');

	$itemid = JRequest::getString( 'Itemid', '' );
	$option = JRequest::getString( 'option', '' );

	$filter="";
	$session = &JFactory::getSession();
	
	$filter = $this->filter_request_status;
	$resourceFilter = $this->filter_request_resource;
	$startdateFilter = $this->filter_startdate;

	if($session->get("current_tab") != "" ){
		$current_tab = $session->get("current_tab");
		$session->set("current_tab", "");
	} else {
		$current_tab = JRequest::getVar( 'current_tab', '0' );
	}

	include_once( JPATH_SITE."/components/com_rsappt_pro2/functions2.php" );
	
	//global $my;  
	$user =& JFactory::getUser();
	
	$ordering = ($this->lists['order'] == 'ordering');
	 
	if($user->guest){
		echo "<font color='red'>".JText::_('RS1_ADMIN_SCRN_NO_LOGIN')."</font>";
		$showform = false;
	} else{
		$showform = true;

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
		
		// get statuses
		$sql = "SELECT * FROM #__sv_apptpro2_status ORDER BY ordering ";
		//echo $sql;
		$database->setQuery($sql);
		$statuses = $database -> loadObjectList();
		if ($database -> getErrorNum()) {
			echo "DB Err: ". $database -> stderr();
			return false;
		}
	
		$database = &JFactory::getDBO(); 
		$sql = 'SELECT * FROM #__sv_apptpro2_config';
		$database->setQuery($sql);
		$apptpro_config = NULL;
		$apptpro_config = $database -> loadObject();
		if ($database -> getErrorNum()) {
			echo "DB Err: ". $database -> stderr();
			return false;
		}
	
		$div_cal = "";
		if($apptpro_config->use_div_calendar == "Yes"){
			$div_cal = "'testdiv1'";
		}
		
		$tab = 0;
	}	
?>
<div id="testdiv1" style="VISIBILITY: hidden; POSITION: absolute; BACKGROUND-COLOR: white; layer-background-color: white"> </div>
<link href="<?php echo $this->baseurl;?>/components/com_rsappt_pro2/sv_apptpro.css" rel="stylesheet">
<link href="<?php echo $this->baseurl;?>/components/com_rsappt_pro2/calStyles.css" rel="stylesheet">
<script language="JavaScript" src="<?php echo $this->baseurl;?>/components/com_rsappt_pro2/script.js"></script>
<script language="JavaScript" src="<?php echo $this->baseurl;?>/components/com_rsappt_pro2/CalendarPopup.js"></script>
<script language="JavaScript">
	var cal = new CalendarPopup( <?php echo $div_cal ?>);
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
	
	cal.setTodayText('<?php echo JText::_('RS1_TODAY')?>');

	// to set css for popup calendar uncomment next line and change calStyles.css
	cal.setCssPrefix("TEST");
	
</script>
<script>
	function cleardate(){
		document.getElementById("startdateFilter").value="";
		document.getElementById("enddateFilter").value="";
		document.getElementById("current_tab").value="0";
		submitbutton('');
		return false;		
	}

	function selectStartDate(){
		document.getElementById("current_tab").value="0";
		submitbutton('');
		return false;		
	}

	function selectEndDate(){
		document.getElementById("current_tab").value="0";
		submitbutton('');
		return false;		
	}
	
	function changeRequestResourceFilter(){
		document.getElementById("current_tab").value="0";
		submitbutton('');
		return false;				
	}

	function changeRequestStatusFilter(){
		document.getElementById("current_tab").value="0";
		submitbutton('');
		return false;				
	}

	function sendReminders(which){
		if(which=="Email"){
			document.getElementById("current_tab").value=document.getElementById("resources_tab").value;
			submitbutton('reminders');
		} else {
			document.getElementById("current_tab").value=document.getElementById("resources_tab").value;
			submitbutton('reminders_sms');
		}	
		return false;		
	}
	

	function doPublish(id){
		if(id != undefined){			
			document.getElementById('res_cb'+id).checked = true;
		}
		document.getElementById("current_tab").value=document.getElementById("resources_tab").value;
		submitbutton('publish_resource');
		return false;		
	}

	function doUnPublish(id){
		if(id != undefined){			
			document.getElementById('res_cb'+id).checked = true;
		}
		document.getElementById("current_tab").value = document.getElementById("resources_tab").value;
		submitbutton('unpublish_resource');
		return false;		
	}

	function selectResource(tab){
		document.getElementById("current_tab").value=tab;
		submitbutton('');
		return false;			
	}	
	
	function goResCopy(id){
		if(id != undefined){			
			document.getElementById('res_cb'+id).checked = true;
		}
		document.getElementById("current_tab").value=document.getElementById("resources_tab").value;
		submitbutton('copy_resource');
		return false;		
	}


	function doResRemove(){
		var answer = confirm(" <?php echo JText::_('RS1_ADMIN_SCRN_CONFIRM_DELETE_RESOURCE');?>")
		if (answer){
			document.getElementById("current_tab").value=document.getElementById("resources_tab").value;
			submitbutton('remove_resource');
			return false;		
		}
	}

	function doSrvPublish(id){
		if(document.getElementById('service_resourceFilter').selectedIndex != 0){
			if(id != undefined){			
				document.getElementById('srv_cb'+id).checked = true;
			}
//			document.getElementById("redirect").value="publish_service";
			document.getElementById("current_tab").value=document.getElementById("services_tab").value;
			submitbutton('publish_service');
			return false;		
		}
	}

	function doSrvUnPublish(id){
		if(document.getElementById('service_resourceFilter').selectedIndex != 0){
			if(id != undefined){			
				document.getElementById('srv_cb'+id).checked = true;
			}
			document.getElementById("current_tab").value=document.getElementById("services_tab").value;
			submitbutton('unpublish_service');
			return false;		
		}
	}

	function doSrvRemove(){
		if(document.getElementById('service_resourceFilter').selectedIndex != 0){
			var answer = confirm("<?php echo JText::_('RS1_ADMIN_SCRN_CONFIRM_DELETE_SERVICE');?>")
			if (answer){
				document.getElementById("current_tab").value=document.getElementById("services_tab").value;
				submitbutton('remove_service');
				return false;		
			}
		}
	}

	function goSrvCopy(id){
		if(document.getElementById('service_resourceFilter').selectedIndex != 0){
			document.getElementById("id").value=id;
			document.getElementById("current_tab").value=document.getElementById("services_tab").value;
			submitbutton('copy_services');
			return false;		
		}
	}

	function doBOPublish(id){
		if(document.getElementById('bookoffs_resourceFilter').selectedIndex != 0){
			if(id != undefined){			
				document.getElementById('bo_cb'+id).checked = true;
			}
			document.getElementById("current_tab").value=document.getElementById("bookoffs_tab").value;
			submitbutton('publish_bookoff');
			return false;		
		}
	}

	function doBOUnPublish(id){
		if(document.getElementById('bookoffs_resourceFilter').selectedIndex != 0){
			if(id != undefined){			
				document.getElementById('bo_cb'+id).checked = true;
			}
			document.getElementById("current_tab").value=document.getElementById("bookoffs_tab").value;
			submitbutton('unpublish_bookoff');
			return false;		
		}
	}

	function doBORemove(){
		if(document.getElementById('bookoffs_resourceFilter').selectedIndex != 0){
			var answer = confirm("<?php echo JText::_('RS1_ADMIN_SCRN_CONFIRM_DELETE_BOOKOFF');?>")
			if (answer){
				document.getElementById("current_tab").value=document.getElementById("bookoffs_tab").value;
				submitbutton('remove_bookoff');
				return false;		
			}
		}
	}

	function goBOCopy(id){
		if(document.getElementById('bookoffs_resourceFilter').selectedIndex != 0){
			document.getElementById("id").value=id;
			document.getElementById("current_tab").value=document.getElementById("bookoffs_tab").value;
			submitbutton('copy_bookoffs');
			return false;		
		}
	}
	
	function selectDay(){
			document.getElementById("current_tab").value=document.getElementById("timeslots_tab").value;
			submitbutton('');
			return false;		
	}

	function doTSPublish(id){
		if(document.getElementById('timeslots_resourceFilter').selectedIndex != 0){
			if(id != undefined){			
				document.getElementById('ts_cb'+id).checked = true;
			}
			document.getElementById("current_tab").value=document.getElementById("timeslots_tab").value;
			submitbutton('publish_timeslot');
			return false;		
		}
	}

	function doTSUnPublish(id){
		if(document.getElementById('timeslots_resourceFilter').selectedIndex != 0){
			if(id != undefined){			
				document.getElementById('ts_cb'+id).checked = true;
			}
			document.getElementById("current_tab").value=document.getElementById("timeslots_tab").value;
			submitbutton('unpublish_timeslot');
			return false;		
		}
	}

	function doTSRemove(){
		if(document.getElementById('timeslots_resourceFilter').selectedIndex != 0){
			var answer = confirm("<?php echo JText::_('RS1_ADMIN_SCRN_CONFIRM_DELETE_TIMESLOT');?>")
			if (answer){
				document.getElementById("current_tab").value=document.getElementById("timeslots_tab").value;
				submitbutton('remove_timeslot');
				return false;		
			}
		}
	}

	function doImportGlobal(){
		if(document.getElementById('timeslots_resourceFilter').selectedIndex == 0){
			alert("<?php echo JText::_('RS1_INPUT_SCRN_RESOURCE_FOR_IMPORT_PROMPT');?>");
			return false
		}
		var answer = confirm("<?php echo JText::_('RS1_ADMIN_SCRN_CONFIRM_IMPORT_GLOBAL');?>")
		if (answer){
			document.getElementById("current_tab").value=document.getElementById("timeslots_tab").value;
			submitbutton('do_global_import_timeslots');
			return false;		
		}
	}


	function goTSCopy(id){
		if(document.getElementById('timeslots_resourceFilter').selectedIndex != 0){
			document.getElementById("id").value=id;
			document.getElementById("current_tab").value=document.getElementById("timeslots_tab").value;
			submitbutton('copy_timeslots');
			return false;		
		}
	}

	
	function selectPPStartDate(){
		document.getElementById("current_tab").value=document.getElementById("paypal_tab").value;
		submitbutton('');
		return false;		
	}
		
	function selectPPEndDate(){
		document.getElementById("current_tab").value=document.getElementById("paypal_tab").value;
		submitbutton('');
		return false;		
	}
		
	function ppcleardate(){
		document.getElementById("ppstartdateFilter").value="";
		document.getElementById("ppenddateFilter").value="";
		document.getElementById("current_tab").value=document.getElementById("paypal_tab").value;
		submitbutton('');
		return false;		
	}
	
	
	function doCoupPublish(id){
		if(id != undefined){			
			document.getElementById('coup_cb'+id).checked = true;
		}
		document.getElementById("current_tab").value=document.getElementById("coupons_tab").value;
		submitbutton('publish_coupon');
		return false;		
	}

	function doCoupUnPublish(id){
		if(id != undefined){			
			document.getElementById('coup_cb'+id).checked = true;
		}
		document.getElementById("current_tab").value=document.getElementById("coupons_tab").value;
		submitbutton('unpublish_coupon');
		return false;		
	}

	
	function doCoupRemove(){
		var answer = confirm("<?php echo JText::_('RS1_ADMIN_TOOLBAR_COUPON_DEL_CONF');?>")
		if (answer){
			document.getElementById("current_tab").value=document.getElementById("coupons_tab").value;
			submitbutton('remove_coupon');
			return false;		
		}
	}

	function doExtPublish(id){
		if(id != undefined){			
			document.getElementById('ext_cb'+id).checked = true;
		}
		document.getElementById("current_tab").value=document.getElementById("extras_tab").value;
		submitbutton('publish_extra');
		return false;		
	}

	function doExtUnPublish(id){
		if(id != undefined){			
			document.getElementById('ext_cb'+id).checked = true;
		}
		document.getElementById("current_tab").value=document.getElementById("extras_tab").value;
		submitbutton('unpublish_extra');
		return false;		
	}

	
	function doExtRemove(){
		var answer = confirm("<?php echo JText::_('RS1_ADMIN_TOOLBAR_EXTRA_DEL_CONF');?>")
		if (answer){
			document.getElementById("current_tab").value=document.getElementById("extras_tab").value;
			submitbutton('remove_extra');
			return false;		
		}
	}


</script>
<form action="<?php echo $this->request_url; ?>" method="post" name="adminForm">

<?php if($showform){?>
    	<table width="100%" >
			<tr>
			  <td align="left" colspan="2"> <h3><?php echo JText::_('RS1_ADMIN_SCRN_TITLE');?></h3></td>
			</tr>
		</table>
	<?php
	   // instantiate new tab system
		jimport( 'joomla.html.pane' );

		$pane =& JPane::getInstance('Tabs',array('startOffset'=>$current_tab));
		echo $pane->startPane('myPane');
		{
		// start tab pane
		//$tabs->startPane("TabPaneOne");
		echo $pane->startPanel(JText::_('RS1_ADMIN_SCRN_TAB_BOOKING'), 'panel1');

	?>
	<span style="padding-left:-20"> <!-- for IE weirdness -->
    <table class="adminheading" width="100%" style="border-bottom:1px solid #666666;">
	  <tr>
		<th align="left" ><?php echo JText::_('RS1_ADMIN_SCRN_TAB_BOOKING');?></th>
        <th align="right" >
         <a href="#" onclick="sendReminders('Email')" title="<?php echo JText::_('RS1_ADMIN_SCRN_REMINDERS_TOOLTIP');?>"><?php echo JText::_('RS1_ADMIN_SCRN_SEND_REMINDERS');?></a>
	      &nbsp;|&nbsp;&nbsp;<a href="#" onclick="sendReminders('SMS')"><?php echo JText::_('RS1_ADMIN_SCRN_SEND_REMINDERS_SMS');?></a>&nbsp;&nbsp;
        </th>
	  </tr>
	</table>
    </span>
      <table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminheading">
     		<thead>
            <tr bgcolor="#F4F4F4">
              <td align="right"  style="font-size:11px">
                <?php echo JText::_('RS1_ADMIN_SCRN_DATEFILTER');?>&nbsp;<input type="text" size="10" maxsize="10" name="startdateFilter" id="startdateFilter" readonly="readonly" style="font-size:11px"
                value="<?php echo $this->filter_startdate; ?>" onchange="selectStartDate();"/>
                <a href="#" id="anchor1" onclick="cal.select(document.forms['adminForm'].startdateFilter,'anchor1','yyyy-MM-dd'); return false;"
                             name="anchor1"><img height="15" hspace="2" src="<?php echo $this->baseurl;?>/components/com_rsappt_pro2/icon_cal.gif" width="16" border="0"></a>
                &nbsp;<input type="text" size="10" maxsize="10" name="enddateFilter" id="enddateFilter" readonly="readonly" style="font-size:11px"
                value="<?php echo $this->filter_enddate ?>" onchange="selectEndDate();"/>
                <a href="#" id="anchor2" onclick="cal.select(document.forms['adminForm'].enddateFilter,'anchor2','yyyy-MM-dd'); return false;"
                             name="anchor2"><img height="15" hspace="2" src="<?php echo $this->baseurl;?>/components/com_rsappt_pro2/icon_cal.gif" width="16" border="0"></a>
                <a href="#" onclick="cleardate();"><?php echo JText::_('RS1_ADMIN_SCRN_DATEFILTER_CLEAR');?></a>&nbsp;&nbsp;
                <select name="request_resourceFilter" id="request_resourceFilter" onchange="changeRequestResourceFilter();" style="font-size:11px" >
	              <option value="0" <?php if($this->filter_request_resource == "0"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_REQUEST_RESOURCE_NONE');?></option>
                  <?php
                    $k = 0;
                    for($i=0; $i < count( $res_rows ); $i++) {
                    $res_row = $res_rows[$i];
                    ?>
                  <option value="<?php echo $res_row->id_resources; ?>" <?php if($this->filter_request_resource == $res_row->id_resources){echo " selected='selected' ";} ?>><?php echo JText::_(stripslashes($res_row->name)); ?></option>
                  <?php $k = 1 - $k; 
                    } ?>
                </select>&nbsp;&nbsp;
                <select name="request_status" onchange="changeRequestStatusFilter();" style="font-size:11px">
                <option value=""><?php echo JText::_('RS1_ADMIN_SCRN_REQUEST_STATUS_NONE');?></option>
				<?php foreach($statuses as $status_row){ ?>
                    <option value="<?php echo $status_row->internal_value ?>" <?php if($this->filter_request_status == $status_row->internal_value){echo " selected='selected' ";} ?>><?php echo JText::_($status_row->status);?></option>        
                <?php } ?>
                </select>
              </td>
            </tr>
		</thead>
        </table>
      <table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminheading">
        <tr bgcolor="#F4F4F4">
          <th class="title" width="3%"><input type="checkbox" name="toggle" value="" onclick="checkAll2(<?php echo count($this->items); ?>, 'appt_cb');" /></th>
          <th class="title" align="left"><?php echo JHTML::_( 'grid.sort', JText::_('RS1_ADMIN_SCRN_NAME_COL_HEAD'), 'name', $this->lists['order_Dir_req'], $this->lists['order_reg'], 'req_'); ?></th>
          <th class="title" align="left"><?php echo JHTML::_( 'grid.sort', JText::_('RS1_ADMIN_SCRN_EMAIL_COL_HEAD'), 'email', $this->lists['order_Dir_req'], $this->lists['order_reg'], 'req_'); ?></th>
          <th class="title" align="left"><?php echo JHTML::_( 'grid.sort', JText::_('RS1_ADMIN_SCRN_RESID_COL_HEAD'), 'ResourceName',  $this->lists['order_Dir_req'], $this->lists['order_reg'], 'req_'); ?></th>
          <th class="title" align="left"><?php echo JHTML::_( 'grid.sort', JText::_('RS1_ADMIN_SCRN_FROM_COL_HEAD'), 'startdatetime',  $this->lists['order_Dir_req'], $this->lists['order_reg'], 'req_'); ?></th>
          <th class="title" align="left"><?php echo JHTML::_( 'grid.sort', JText::_('RS1_ADMIN_SCRN_SERVICE_COL_HEAD'), 'ServiceName',  $this->lists['order_Dir_req'], $this->lists['order_reg'], 'req_'); ?></th>
          <th class="title" align="center"><?php echo JHTML::_( 'grid.sort', JText::_('RS1_ADMIN_SCRN_STATUS_COL_HEAD'), 'request_status',  $this->lists['order_Dir_req'], $this->lists['order_reg'], 'req_'); ?></th>
          <th class="title" align="center"><?php echo JHTML::_( 'grid.sort', JText::_('RS1_ADMIN_SCRN_PAYMENT_COL_HEAD'), 'payment_status',  $this->lists['order_Dir_req'], $this->lists['order_reg'], 'req_'); ?></th>
        </tr>
        <?php
        $k = 0;
        for($i=0; $i < count( $this->items ); $i++) {
        $row = $this->items[$i];
		$link 	= JRoute::_( 'index.php?option=com_rsappt_pro2&controller=admin_detail&task=edit&cid[]='. $row->id_requests.'&frompage=advadmin&tab=0', false);

       ?>
        <tr class="<?php echo "row$k"; ?>">
          <td align="center"><input type="checkbox" id="appt_cb<?php echo $i;?>" name="cid_req[]" value="<?php echo $row->id_requests; ?>" onclick="isChecked(this.checked);" /></td>
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
<!--		<tfoot>
		   	<td colspan="12"><?php echo $this->pagination->getListFooter(); ?></td>
	    </tfoot>-->
      </table>
      <input type="hidden" name="id" id="id" value="" />
      <input type="hidden" name="option" value="<?php echo $option; ?>" />
      <input type="hidden" name="boxchecked" value="0" />
      <input type="hidden" name="reminders" id="reminders" value="" />
      <input type="hidden" name="filter" id="filter" value="" />
      <input type="hidden" name="resourceFilter" id="resourceFilter" value="" />
	  <input type="hidden" name="req_filter_order" value="<?php echo $this->lists['order_req']; ?>" />
	  <input type="hidden" name="req_filter_order_Dir" value="<?php echo $this->lists['order_Dir_req']; ?>" />
  	  <input type="hidden" name="requests_tab" value ="0" />
	<?php
		echo $pane->endPanel();
		
	if($apptpro_config->adv_admin_show_resources == "Yes"){
		$tab = $tab + 1;
		echo $pane->startPanel(JText::_('RS1_ADMIN_SCRN_TAB_RESOURCES'), 'panel2');
		
		$link_new_res = JRoute::_( 'index.php?option=com_rsappt_pro2&controller=admin_detail&task=res_detail&cid[]=&frompage=advadmin&tab='.$tab);

	?> 
	<span style="padding-left:-20"> <!-- for IE weirdness -->
	<table class="adminheading" width="100%" style="border-bottom:1px solid #666666;">
	  <tr>
		<th align="left"><?php echo JText::_('RS1_ADMIN_SCRN_TAB_RESOURCES');?></th>
        <th align="right">
        <a href="javascript:doPublish();"><?php echo JText::_('RS1_ADMIN_SCRN_PUBLISH');?></a>&nbsp;|
        <a href="javascript:doUnPublish();"><?php echo JText::_('RS1_ADMIN_SCRN_UNPUBLISH');?></a>&nbsp;|
        <a href="javascript:goResCopy();"><?php echo JText::_('RS1_ADMIN_SCRN_COPY');?></a>&nbsp;|
        <a href="javascript:doResRemove();"><?php echo JText::_('RS1_ADMIN_SCRN_REMOVE');?></a>&nbsp;|
        <a href="<?php echo $link_new_res; ?>"><?php echo JText::_('RS1_ADMIN_SCRN_NEW');?></a>
      </th>
	  </tr>
	</table>
  </span>
	  <table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
		<thead>
		<tr bgcolor="#F4F4F4">
		  <th class="title"  width="3%"><input type="checkbox" name="toggle2" value="" onclick="checkAll2(<?php echo count($this->items_res); ?>, 'res_cb',2);" /></th>
          <th class="title" align="center"><?php echo JHTML::_( 'grid.sort', JText::_('RS1_ADMIN_SCRN_ID_COL_HEAD'), 'id_resources', $this->lists['order_Dir_res'], $this->lists['order_res'], 'res_'); ?></th>
          <th class="title" align="left"><?php echo JHTML::_( 'grid.sort', JText::_('RS1_ADMIN_SCRN_NAME_COL_HEAD'), 'name', $this->lists['order_Dir_res'], $this->lists['order_res'], 'res_'); ?></th>
          <th class="title" align="left"><?php echo JHTML::_( 'grid.sort', JText::_('RS1_ADMIN_SCRN_DESCRIPTION_COL_HEAD'), 'description', $this->lists['order_Dir_res'], $this->lists['order_res'], 'res_'); ?></th>
		  <th class="title" align="center"><?php echo JText::_('RS1_ADMIN_SCRN_DAYS_COL_HEAD');?></th>
          <th class="title" align="center"><?php echo JHTML::_( 'grid.sort', JText::_('RS1_ADMIN_SCRN_TIMESLOTS_COL_HEAD'), 'timeslots', $this->lists['order_Dir_res'], $this->lists['order_res'], 'res_'); ?></th>
          <th class="title" align="center"><?php echo JHTML::_( 'grid.sort', JText::_('RS1_ADMIN_SCRN_CATEGORY_COL_HEAD'), 'cat_name', $this->lists['order_Dir_res'], $this->lists['order_res'], 'res_'); ?></th>
          <th class="title" align="center"><?php echo JHTML::_( 'grid.sort', JText::_('RS1_ADMIN_SCRN_ORDER_COL_HEAD'), 'ordering', $this->lists['order_Dir_res'], $this->lists['order_res'], 'res_'); ?></th>
          <th class="title" align="center"><?php echo JHTML::_( 'grid.sort', JText::_('RS1_ADMIN_SCRN_PUBLISHED_COL_HEAD'), 'published', $this->lists['order_Dir_res'], $this->lists['order_res'], 'res_'); ?></th>
		</tr>
		</thead>
		<?php
		$k = 0;
		for($i=0; $i < count( $this->items_res ); $i++) {
		$res_row = $this->items_res[$i];
		if($res_row->published==1){
			$published 	= "<a href='javascript:doUnPublish(".$i.")'><img src='./components/com_rsappt_pro2/tick.png'></a>";
		} else {
			$published 	= "<a href='#' OnClick='javascript:doPublish(".$i.");return false;'><img src='./components/com_rsappt_pro2/publish_x.png'></a>";
		}	
		$link 	= JRoute::_( 'index.php?option=com_rsappt_pro2&controller=admin_detail&task=res_detail&cid[]='. $res_row->id_resources.'&frompage=advadmin&tab='.$tab);
		
	   ?>
		<tr class="<?php echo "row$k"; ?>">
		  <td align="center"><input type="checkbox" id="res_cb<?php echo $i;?>" name="cid_res[]" value="<?php echo $res_row->id_resources; ?>" onclick="isChecked(this.checked);" /></td>
		  <td align="center"><?php echo $res_row->id_resources; ?>&nbsp;</td>
          <td><a href="<?php echo $link; ?>"><?php echo JText::_(stripslashes($res_row->name)); ?></a></td>
		  <td align="left"><?php echo JText::_(stripslashes($res_row->description)); ?>&nbsp;</td>
		  <td align="center">  
		  <?php 
			echo ($res_row->allowSunday=="Yes" ? JText::_('RS1_ADMIN_SCRN_SUN').' ' : '');
			echo ($res_row->allowMonday=="Yes" ? JText::_('RS1_ADMIN_SCRN_MON').' ' : '');
			echo ($res_row->allowTuesday=="Yes" ? JText::_('RS1_ADMIN_SCRN_TUE').' ' : '');
			echo ($res_row->allowWednesday=="Yes" ?JText::_('RS1_ADMIN_SCRN_WED').' ' : '');
			echo ($res_row->allowThursday=="Yes" ?JText::_('RS1_ADMIN_SCRN_THU').' ' : '');
			echo ($res_row->allowFriday=="Yes" ?JText::_('RS1_ADMIN_SCRN_FRI').' ' : '');
			echo ($res_row->allowSaturday=="Yes" ?JText::_('RS1_ADMIN_SCRN_SAT').' ' : '');
			 ?></td>
		  <td align="center"><?php echo $res_row->timeslots; ?>&nbsp;</td>
		  <td align="center"><?php echo JText::_($res_row->cat_name); ?>&nbsp;</td>
		  <td align="center"><?php echo $res_row->ordering; ?>&nbsp;</td>
		  <td align="center"><?php echo $published;?></td>
		  <?php $k = 1 - $k; ?>
		</tr>
		<?php } 
	
	?>
	  </table>
	  <input type="hidden" name="res_filter_order" value="<?php echo $this->lists['order_res']; ?>" />
  	  <input type="hidden" name="res_filter_order_Dir" value ="<?php echo $this->lists['order_Dir_res'] ?>" />
  	  <input type="hidden" name="resources_tab" id="resources_tab" value ="<?php echo $tab ?>" />

	<?php
		echo $pane->endPanel();
	}
	
	if($apptpro_config->adv_admin_show_services == "Yes"){
		$tab = $tab + 1;
		echo $pane->startPanel(JText::_('RS1_ADMIN_SCRN_TAB_SERVICES'), 'panel3');

		$link_new_srv = JRoute::_( 'index.php?option=com_rsappt_pro2&controller=admin_detail&task=services_detail&cid[]=&frompage=advadmin&tab='.$tab);
	?> 
	<span style="padding-left:-20"> 
    	
        <table class="adminheading" width="100%" style="border-bottom:1px solid #666666;">
            <tr>
              <th align="left" ><?php echo JText::_('RS1_ADMIN_SCRN_TAB_SERVICES');?> <br /> </th>
              <th align="right">
				<a href="javascript:doSrvPublish();"><?php echo JText::_('RS1_ADMIN_SCRN_PUBLISH');?></a>&nbsp;|
        		<a href="javascript:doSrvUnPublish();"><?php echo JText::_('RS1_ADMIN_SCRN_UNPUBLISH');?></a>&nbsp;|
		        <a href="javascript:goSrvCopy();"><?php echo JText::_('RS1_ADMIN_SCRN_COPY');?></a>&nbsp;|
        		<a href="javascript:doSrvRemove();"><?php echo JText::_('RS1_ADMIN_SCRN_REMOVE');?></a>&nbsp;|
		        <a href="<?php echo $link_new_srv; ?>"><?php echo JText::_('RS1_ADMIN_SCRN_NEW');?></a>
              </th>
          </tr>
  	  	</table>
    </span>
        <table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
		<thead >
		<tr bgcolor="#F4F4F4">
		  <th>&nbsp;</th>
		  <th colspan="6" align="right"><?php echo JText::_('RS1_ADMIN_SCRN_SERV_RESOURCE');?>&nbsp;<select name="service_resourceFilter" id="service_resourceFilter"
           onchange="selectResource(<?php echo $tab ?>);" style="font-size:11px;">
                  <option value="0" <?php if($resource_id_Filter == "0"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_SERV_RESOURCE_SELECT');?></option>
                  <?php
                    $k = 0;
                    for($i=0; $i < count( $res_rows ); $i++) {
                    $res_row = $res_rows[$i];
                    ?>
            <option value="<?php echo $res_row->id_resources; ?>" <?php if($this->filter_service_resource == $res_row->id_resources){echo " selected='selected' ";} ?>><?php echo JText::_(stripslashes($res_row->name)); ?></option>
                  <?php $k = 1 - $k; 
                    } ?>
          </select></th>
		  </tr>
		<tr bgcolor="#F4F4F4" >
          <th class="title" width="3%"><input type="checkbox" name="toggle3" value="" onclick="checkAll2(<?php echo count($this->items_srv); ?>, 'srv_cb',3);" /></th>
          <th class="title" align="center"><?php echo JHTML::_( 'grid.sort', JText::_('RS1_ADMIN_SCRN_ID_COL_HEAD'), 'id_services', $this->lists['order_Dir_srv'], $this->lists['order_srv'], "srv_" ); ?></th>
          <th class="title" align="left"><?php echo JHTML::_( 'grid.sort', JText::_('RS1_ADMIN_SCRN_SERVICE_COL_HEAD'), 'name',$this->lists['order_Dir_srv'], $this->lists['order_srv'], "srv_" ); ?></th>
          <th class="title" align="left"><?php echo JHTML::_( 'grid.sort', JText::_('RS1_ADMIN_SCRN_DESCRIPTION_COL_HEAD'), 'description', $this->lists['order_Dir_srv'], $this->lists['order_srv'], "srv_" ); ?></th>
          <th class="title" align="center"><?php echo JHTML::_( 'grid.sort', JText::_('RS1_ADMIN_SCRN_RESOURCE_COL_HEAD'), 'res_name', $this->lists['order_Dir_srv'], $this->lists['order_srv'], "srv_" ); ?></th>
          <th class="title" align="center"><?php echo JHTML::_( 'grid.sort', JText::_('RS1_ADMIN_SCRN_ORDER_COL_HEAD'), 'ordering', $this->lists['order_Dir_srv'], $this->lists['order_srv'], "srv_" ); ?></th>
          <th class="title" align="center"><?php echo JHTML::_( 'grid.sort', JText::_('RS1_ADMIN_SCRN_PUBLISHED_COL_HEAD'), 'published', $this->lists['order_Dir_srv'], $this->lists['order_srv'], "srv_" ); ?></th>
        </tr>
        </thead>
        <?php
        $k = 0;
        for($i=0; $i < count( $this->items_srv ); $i++) {
        $srv_row = $this->items_srv[$i];
		if($srv_row->published==1){
			$published 	= "<a href='javascript:doSrvUnPublish(".$i.")'><img src='./components/com_rsappt_pro2/tick.png'></a>";
		} else {
			$published 	= "<a href='#' OnClick='javascript:doSrvPublish(".$i.");return false;'><img src='./components/com_rsappt_pro2/publish_x.png'></a>";
		}	
		$link = JRoute::_( 'index.php?option=com_rsappt_pro2&controller=admin_detail&task=services_detail&cid[]='. $srv_row->id_services.'&frompage=advadmin&tab='.$tab);
       ?>
        <tr class="<?php echo "row$k"; ?>">
          <td align="center"><input type="checkbox" id="srv_cb<?php echo $i;?>" name="cid_srv[]" value="<?php echo $srv_row->id_services; ?>" onclick="isChecked(this.checked);" /></td>
          <td align="center"><?php echo $srv_row->id_services; ?>&nbsp;</td>
          <td><a href="<?php echo $link; ?>"><?php echo JText::_(stripslashes($srv_row->name)); ?></a></td>
          <td align="left"><?php echo JText::_(stripslashes($srv_row->description)); ?>&nbsp;</td>
          <td align="center"><?php echo JText::_($srv_row->res_name); ?>&nbsp;</td>
          <td align="center"><?php echo $srv_row->ordering; ?>&nbsp;</td>
          <td align="center"><?php echo $published;?></td>
          <?php $k = 1 - $k; ?>
        </tr>
        <?php } 
    
    ?>
      </table>
	  <input type="hidden" name="srv_filter_order" value="<?php echo $this->lists['order_srv']; ?>" />
  	  <input type="hidden" name="srv_filter_order_Dir" value ="<?php echo $this->lists['order_Dir_srv'] ?>" />
  	  <input type="hidden" name="services_tab" id="services_tab" value ="<?php echo $tab ?>" />
  
	<?php
		echo $pane->endPanel();
	}
	
	if($apptpro_config->adv_admin_show_timeslots == "Yes"){
		$tab = $tab + 1;
		echo $pane->startPanel(JText::_('RS1_ADMIN_SCRN_TAB_TIMESLOTS'), 'panel4');
		
		$daynames = array(0=>'Sunday', 1=>'Monday', 2=>'Tuesday', 3=>'Wednesday', 4=>'Thursday', 5=>'Friday', 6=>'Saturday');

		$link_new_ts = JRoute::_( 'index.php?option=com_rsappt_pro2&controller=admin_detail&task=timeslots_detail&cid[]=&frompage=advadmin&tab='.$tab);
		
	?> 
	<span style="padding-left:-20"> <!-- for IE weirdness -->
      <table class="adminheading" width="100%" style="border-bottom:1px solid #666666;">
                <tr>
                  <th align="left" ><?php echo JText::_('RS1_ADMIN_SCRN_TAB_TIMESLOTS');?> <br /> </th>
                  <th align="right">
                    <a href="javascript:doTSPublish();"><?php echo JText::_('RS1_ADMIN_SCRN_PUBLISH');?></a>&nbsp;|
                    <a href="javascript:doTSUnPublish();"><?php echo JText::_('RS1_ADMIN_SCRN_UNPUBLISH');?></a>&nbsp;|
                    <a href="javascript:goTSCopy();"><?php echo JText::_('RS1_ADMIN_SCRN_COPY');?></a>&nbsp;|
                    <a href="javascript:doImportGlobal();"><?php echo JText::_('RS1_ADMIN_SCRN_IMPORT_GLOBAL');?></a>&nbsp;|
                    <a href="javascript:doTSRemove();"><?php echo JText::_('RS1_ADMIN_SCRN_REMOVE');?></a>&nbsp;|
                    <a href="<?php echo $link_new_ts; ?>"><?php echo JText::_('RS1_ADMIN_SCRN_NEW');?></a>
					</th> 
              </tr>
      </table>
    </span>
	<table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
		<thead>
            <tr bgcolor="#F4F4F4">
                <th>
                <table class="adminheading" align="right" cellspadding="2">
                <tr>
                <td><?php echo JText::_('RS1_ADMIN_SCRN_SERV_RESOURCE');?></td>
                <td><select name="timeslots_resourceFilter" id="timeslots_resourceFilter" onchange="selectResource(<?php echo $tab ?>);" style="font-size:11px;" >
                  <option value="0" <?php if($this->filter_timeslots_resource == "0"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_SERV_RESOURCE_SELECT');?></option>
                  <?php
                    $k = 0;
                    for($i=0; $i < count( $res_rows ); $i++) {
                    $res_row = $res_rows[$i];
                    ?>
                  <option value="<?php echo $res_row->id_resources; ?>" <?php if($this->filter_timeslots_resource == $res_row->id_resources){echo " selected='selected' ";} ?>><?php echo JText::_(stripslashes($res_row->name)); ?></option>
                  <?php $k = 1 - $k; 
                    } ?>
                </select>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td>
                    <?php echo JText::_('RS1_ADMIN_SCRN_TS_DAY');?></td>
                <td>
                    <select name="day_numberFilter" id="day_numberFilter" onchange="selectDay();" style="font-size:11px;" >
                      <option value="all" <?php if($this->filter_day_number == "all"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_TS_SHOWALL');?></option>
                      <option value="0"<?php if($this->filter_day_number == "0"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_SUNDAY');?></option>
                      <option value="1"<?php if($this->filter_day_number == "1"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_MONDAY');?></option>
                      <option value="2"<?php if($this->filter_day_number == "2"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_TUESDAY');?></option>
                      <option value="3"<?php if($this->filter_day_number == "3"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_WEDNESDAY');?></option>
                      <option value="4"<?php if($this->filter_day_number == "4"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_THURSDAY');?></option>
                      <option value="5"<?php if($this->filter_day_number == "5"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_FRIDAY');?></option>
                      <option value="6"<?php if($this->filter_day_number == "6"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_SATURDAY');?></option>
                    </select>&nbsp;&nbsp;            </td>
                </tr>
               </table>
             </th> 
          </tr>
        </table>
      <table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
       <thead>
        <tr bgcolor="#F4F4F4">
          <th class="title" width="5%" align="center"><input type="checkbox" name="toggle5" value="" onclick="checkAll2(<?php echo count($this->items_ts); ?>, 'ts_cb',5);" /></th>
          <th class="title" align="center"><?php echo JHTML::_( 'grid.sort', JText::_('RS1_ADMIN_SCRN_ID_COL_HEAD'), 'id_timeslots', $this->lists['order_Dir_ts'], $this->lists['order_ts'], "ts_" ); ?></th>
          <th class="title" align="center"><?php echo JText::_('RS1_ADMIN_SCRN_RESOURCE_COL_HEAD');?></th>
          <th class="title" align="center"><?php echo JHTML::_( 'grid.sort', JText::_('RS1_ADMIN_SCRN_DAY_COL_HEAD'), 'day_number', $this->lists['order_Dir_ts'], $this->lists['order_ts'], "ts_" ); ?></th>
          <th class="title" align="center"><?php echo JHTML::_( 'grid.sort', JText::_('RS1_ADMIN_SCRN_START_COL_HEAD'), 'timeslot_starttime', $this->lists['order_Dir_ts'], $this->lists['order_ts'], "ts_" ); ?></th>
          <th class="title" align="center"><?php echo JHTML::_( 'grid.sort', JText::_('RS1_ADMIN_SCRN_END_COL_HEAD'), 'timeslot_endtime', $this->lists['order_Dir_ts'], $this->lists['order_ts'], "ts_" ); ?></th>
          <th class="title" align="center"><?php echo JHTML::_( 'grid.sort', JText::_('RS1_ADMIN_SCRN_PUBLISHED_COL_HEAD'), 'published', $this->lists['order_Dir_ts'], $this->lists['order_ts'], "ts_" ); ?></th>
        </tr>
      </thead>
        <?php
        $k = 0;
        for($i=0; $i < count( $this->items_ts ); $i++) {
        $ts_row = $this->items_ts[$i];
		if($ts_row->published==1){
			$published 	= "<a href='javascript:doTSUnPublish(".$i.")'><img src='./components/com_rsappt_pro2/tick.png'></a>";
		} else {
			$published 	= "<a href='#' OnClick='javascript:doTSPublish(".$i.");return false;'><img src='./components/com_rsappt_pro2/publish_x.png'></a>";
		}	
		$link = JRoute::_( 'index.php?option=com_rsappt_pro2&controller=admin_detail&task=timeslots_detail&cid[]='. $ts_row->id_timeslots.'&frompage=advadmin&tab='.$tab);

       ?>
        <tr class="<?php echo "row$k"; ?>">
          <td width="5%" align="center"><input type="checkbox" id="ts_cb<?php echo $i;?>" name="cid_ts[]" value="<?php echo $ts_row->id_timeslots; ?>" onclick="isChecked(this.checked);" /></td>
          <td width="5%" align="center"><a href="<?php echo $link; ?>"><?php echo stripslashes($ts_row->id_timeslots); ?></a></td>
<!--          <td width="5%" align="center"><a href="#edit_timeslot" onclick="hideMainMenu(); return listItemTask('cb<?php echo $i;?>','edit_timeslot')"><?php echo $ts_row->id; ?></a></td>-->
          <td width="20%" align="center"><?php echo ($ts_row->name == ""?"Global": JText::_(stripslashes($ts_row->name))); ?>&nbsp;</td>
          <td width="20%" align="center"><?php echo JText::_($daynames[$ts_row->day_number]); ?>&nbsp;</td>
          <td width="20%" align="center"><?php echo $ts_row->timeslot_starttime; ?>&nbsp;</td>
          <td width="20%"align="center"><?php echo $ts_row->timeslot_endtime; ?>&nbsp;</td>
          <td align="center"><?php echo $published;?></td>
          <?php $k = 1 - $k; ?>
        </tr>
        <?php } 
    
    ?>	
      </table><br /><span style="font-size:11px;">
	  <input type="hidden" name="ts_filter_order" value="<?php echo $this->lists['order_ts']; ?>" />
  	  <input type="hidden" name="ts_filter_order_Dir" value ="<?php echo $this->lists['order_Dir_ts'] ?>" />
  	  <input type="hidden" name="timeslots_tab" id="timeslots_tab" value ="<?php echo $tab ?>" />
    <?php echo JText::_('RS1_ADMIN_SCRN_TS_RESOURCE_NOTE');?></span>

   
<?php
		echo $pane->endPanel();
	}
	
	if($apptpro_config->adv_admin_show_bookoffs == "Yes"){
		$tab = $tab + 1;
		echo $pane->startPanel(JText::_('RS1_ADMIN_SCRN_TAB_BOOKOFFS'), 'panel5');

		$link_new_bo = JRoute::_( 'index.php?option=com_rsappt_pro2&controller=admin_detail&task=bookoffs_detail&cid[]=&frompage=advadmin&tab='.$tab);

	?> 
	<span style="padding-left:-20"> <!-- for IE weirdness -->
        <table class="adminheading" width="100%" style="border-bottom:1px solid #666666;">
            <tr>
              <th align="left" ><?php echo JText::_('RS1_ADMIN_SCRN_TAB_BOOKOFFS');?><br /></th>
                <th align="right">
                    <a href="javascript:doBOPublish();"><?php echo JText::_('RS1_ADMIN_SCRN_PUBLISH');?></a>&nbsp;|
                    <a href="javascript:doBOUnPublish();"><?php echo JText::_('RS1_ADMIN_SCRN_UNPUBLISH');?></a>&nbsp;|
                    <a href="javascript:goBOCopy();"><?php echo JText::_('RS1_ADMIN_SCRN_COPY');?></a>&nbsp;|
                    <a href="javascript:doBORemove();"><?php echo JText::_('RS1_ADMIN_SCRN_REMOVE');?></a>&nbsp;|
                    <a href="<?php echo $link_new_bo; ?>"><?php echo JText::_('RS1_ADMIN_SCRN_NEW');?></a>                
              </th> 
          </tr>
        </table>
      </span>
        <table cellpadding="4" cellspacing="0" border="0" class="adminlist" width="100%">
		<thead>
            <tr bgcolor="#F4F4F4">
              <th align="left" >&nbsp;</th>
              <th align="right" colspan="8"><?php echo JText::_('RS1_ADMIN_SCRN_SERV_RESOURCE');?>&nbsp;<select name="bookoffs_resourceFilter" id="bookoffs_resourceFilter" 
              onchange="selectResource(<?php echo $tab ?>);" style="font-size:11px;" >
                  <option value="0" <?php if($resource_id_Filter == "0"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_SERV_RESOURCE_SELECT');?></option>
                  <?php
                    $k = 0;
                    for($i=0; $i < count( $res_rows ); $i++) {
                    $res_row = $res_rows[$i];
                    ?>
                  <option value="<?php echo $res_row->id_resources; ?>" <?php if($this->filter_bookoffs_resource == $res_row->id_resources){echo " selected='selected' ";} ?>><?php echo JText::_(stripslashes($res_row->name)); ?></option>
                  <?php $k = 1 - $k; 
                    } ?>
              </select>&nbsp;</th>
            </tr>
        <tr bgcolor="#F4F4F4">
          <th  class="title" width="5%" align="center"><input type="checkbox" name="toggle4" value="" onclick="checkAll2(<?php echo count($this->items_bo); ?>, 'bo_cb',4);" /></th>
          <th class="title" align="center"><?php echo JHTML::_( 'grid.sort', JText::_('RS1_ADMIN_SCRN_ID_COL_HEAD'), 'id_bookoffs', $this->lists['order_Dir_bo'], $this->lists['order_bo'], "bo_" ); ?></th>
          <th class="title" align="center"><?php echo JText::_('RS1_ADMIN_SCRN_RESOURCE_COL_HEAD');?></th>
	      <th class="title" align="center"><?php echo JHTML::_( 'grid.sort', JText::_('RS1_ADMIN_SCRN_DATEOFF_COL_HEAD'), 'off_date', $this->lists['order_Dir_bo'], $this->lists['order_bo'], "bo_" ); ?></th>
    	  <th class="title" align="center"><?php echo JText::_('RS1_ADMIN_SCRN_BOOKOFF_FULDAY_COL_HEAD'); ?></th>
      	  <th class="title" align="center"><?php echo JText::_('RS1_ADMIN_SCRN_BOOKOFF_RANGE_COL_HEAD'); ?></th>
          <th class="title" align="center"><?php echo JHTML::_( 'grid.sort', JText::_('RS1_ADMIN_SCRN_DESCRIPTION_COL_HEAD'), 'description', $this->lists['order_Dir_bo'], $this->lists['order_bo'], "bo_" ); ?></th>
          <th width="15%" class="title" align="center"><?php echo JHTML::_( 'grid.sort', JText::_('RS1_ADMIN_SCRN_PUBLISHED_COL_HEAD'), 'published', $this->lists['order_Dir_bo'], $this->lists['order_bo'], "bo_" ); ?></th>
        </tr>
      </thead>
        <?php
        $k = 0;
        for($i=0; $i < count( $this->items_bo ); $i++) {
        $boff_row = $this->items_bo[$i];
		if($boff_row->published==1){
			$published 	= "<a href='javascript:doBOUnPublish(".$i.")'><img src='./components/com_rsappt_pro2/tick.png'></a>";
		} else {
			$published 	= "<a href='#' OnClick='javascript:doBOPublish(".$i.");return false;'><img src='./components/com_rsappt_pro2/publish_x.png'></a>";
		}	
		$link = JRoute::_( 'index.php?option=com_rsappt_pro2&controller=admin_detail&task=bookoffs_detail&cid[]='. $boff_row->id_bookoffs.'&frompage=advadmin&tab='.$tab);
       ?>
        <tr class="<?php echo "row$k"; ?>">
          <td width="5%" align="center"><input type="checkbox" id="bo_cb<?php echo $i;?>" name="cid_bo[]" value="<?php echo $boff_row->id_bookoffs; ?>" onclick="isChecked(this.checked);" /></td>
          <td width="5%" align="center"><a href="<?php echo $link; ?>"><?php echo stripslashes($boff_row->id_bookoffs); ?></a></td>
          <td width="20%" align="center"><?php echo ($boff_row->name == ""?"Global": JText::_(stripslashes($boff_row->name))); ?>&nbsp;</td>
          <td width="20%" align="center"><?php echo JText::_($boff_row->off_date_display); ?>&nbsp;</td>
	      <td width="10%" align="center"><?php echo $boff_row->full_day; ?>&nbsp;</td>
    	  <td width="10%" align="center"><?php echo $boff_row->hours; ?>&nbsp;</td>
          <td width="20%" align="center"><?php echo JText::_(stripslashes($boff_row->description)); ?>&nbsp;</td>
          <td align="center"><?php echo $published;?></td>
          <?php $k = 1 - $k; ?>
        </tr>
        <?php } 
    
    ?>	
      </table>

	  <input type="hidden" name="bo_filter_order" value="<?php echo $this->lists['order_bo']; ?>" />
  	  <input type="hidden" name="bo_filter_order_Dir" value ="<?php echo $this->lists['order_Dir_bo'] ?>" />
  	  <input type="hidden" name="bookoffs_tab" id="bookoffs_tab" value ="<?php echo $tab ?>" />
    
<?php    
		echo $pane->endPanel();
	}
		if($apptpro_config->adv_admin_show_paypal == "Yes"){
		$tab = $tab + 1;

		echo $pane->startPanel(JText::_('RS1_ADMIN_SCRN_TAB_PAYPAL'), 'panel6');

	?> 
	<span style="padding-left:-20"> <!-- for IE weirdness -->
        <table class="adminheading" width="100%" style="border-bottom:1px solid #666666;">
            <tr>
              <th align="left" ><?php echo JText::_('RS1_ADMIN_SCRN_TAB_PAYPAL');?><br /></th>
                <th align="right"></th> 
          </tr>
        </table>
      </span>
        <table cellpadding="4" cellspacing="0" border="0" class="adminlist" width="100%">
		<thead>
        <tr bgcolor="#F4F4F4">
          <td align="right"  style="font-size:11px" colspan="7">
            <?php echo JText::_('RS1_ADMIN_SCRN_STAMP_DATEFILTER');?>&nbsp;<input type="text" size="10" maxsize="10" name="ppstartdateFilter" id="ppstartdateFilter" readonly="readonly" style="font-size:11px"
            value="<?php echo $this->filter_pp_startdate; ?>" onchange="selectPPStartDate();"/>
            <a href="#" id="anchor1pp" onclick="cal.select(document.forms['adminForm'].ppstartdateFilter,'anchor1pp','yyyy-MM-dd'); return false;"
                         name="anchor1pp"><img height="15" hspace="2" src="<?php echo $this->baseurl;?>/components/com_rsappt_pro2/icon_cal.gif" width="16" border="0"></a>
            &nbsp;<input type="text" size="10" maxsize="10" name="ppenddateFilter" id="ppenddateFilter" readonly="readonly" style="font-size:11px"
            value="<?php echo $this->filter_pp_enddate;  ?>" onchange="selectPPEndDate();"/>
            <a href="#" id="anchor2pp" onclick="cal.select(document.forms['adminForm'].ppenddateFilter,'anchor2pp','yyyy-MM-dd'); return false;"
                         name="anchor2pp"><img height="15" hspace="2" src="<?php echo $this->baseurl;?>/components/com_rsappt_pro2/icon_cal.gif" width="16" border="0"></a>
            <!--<a href="#" onclick="ppcleardate();"><?php echo JText::_('RS1_ADMIN_SCRN_DATEFILTER_CLEAR');?></a>&nbsp;&nbsp;-->
          </td>
        </tr>
        <tr bgcolor="#F4F4F4">
          <th  class="title" width="5%" align="center"><input type="checkbox" name="toggle6" value="" onclick="checkAll2(<?php echo count($this->items_pp); ?>, 'pp_cb',6);" /></th>
          <th class="title" align="center"><?php echo JHTML::_( 'grid.sort', JText::_('RS1_ADMIN_SCRN_PP_TXN_COL_HEAD'), 'txnid', $this->lists['order_Dir_pp'], $this->lists['order_pp'], "pp_" ); ?></th>
          <th class="title" align="center"><?php echo JHTML::_( 'grid.sort', JText::_('RS1_ADMIN_SCRN_REQ_ID_COL_HEAD'), 'custom', $this->lists['order_Dir_pp'], $this->lists['order_pp'], "pp_" ); ?></th>
          <th class="title" align="left"><?php echo JHTML::_( 'grid.sort', JText::_('RS1_ADMIN_SCRN_BUYER_COL_HEAD'), 'lastname', $this->lists['order_Dir_pp'], $this->lists['order_pp'], "pp_" ); ?></th>
          <th class="title" align="left"><?php echo JHTML::_( 'grid.sort', JText::_('RS1_ADMIN_SCRN_PAY_DATE_COL_HEAD'), 'paymentdate', $this->lists['order_Dir_pp'], $this->lists['order_pp'], "pp_" ); ?></th>
          <th class="title" align="center"><?php echo JHTML::_( 'grid.sort', JText::_('RS1_ADMIN_SCRN_PAY_STATUS_COL_HEAD'), 'paymentstatus', $this->lists['order_Dir_pp'], $this->lists['order_pp'], "pp_" ); ?></th>
          <th class="title" align="left"><?php echo JHTML::_( 'grid.sort', JText::_('RS1_ADMIN_SCRN_TIMESTAMP_COL_HEAD'), 'stamp', $this->lists['order_Dir_pp'], $this->lists['order_pp'], "pp_" ); ?></th>
        </tr>
      </thead>
        <?php
        $k = 0;
        for($i=0; $i < count( $this->items_pp ); $i++) {
        $pp_row = $this->items_pp[$i];
		$link = JRoute::_( 'index.php?option=com_rsappt_pro2&controller=admin_detail&task=paypal_transactions_detail&cid[]='. $pp_row->id_paypal_transactions.'&frompage=advadmin&tab='.$tab);
       ?>
        <tr class="<?php echo "row$k"; ?>">
          <td width="5%" align="center"><input type="checkbox" id="pp_cb<?php echo $i;?>" name="cid_pp[]" value="<?php echo $pp_row->id_paypal_transactions; ?>" onclick="isChecked(this.checked);" /></td>
          <td width="5%" align="center"><a href="<?php echo $link; ?>"><?php echo stripslashes($pp_row->txnid); ?></a></td>
          <td width="20%" align="center"><?php echo $pp_row->custom; ?>&nbsp;</td>
          <td width="20%" align="left"><?php echo stripslashes($pp_row->lastname.", ".$pp_row->firstname); ?>&nbsp;</td>
          <td width="20%" align="left"><?php echo $pp_row->paymentdate; ?>&nbsp;</td>
          <td width="20%" align="center"><?php echo $pp_row->paymentstatus; ?>&nbsp;</td> <!-- from paypal, not ABPro status, cannot use translated_status -->
          <td width="20%" align="center"><?php echo $pp_row->stamp; ?>&nbsp;</td>
          <?php $k = 1 - $k; ?>
        </tr>
        <?php } 
    
    ?>	
      </table>

	  <input type="hidden" name="pp_filter_order" value="<?php echo $this->lists['order_pp'];?>" />
  	  <input type="hidden" name="pp_filter_order_Dir" value ="<?php echo $this->lists['order_Dir_pp'] ?>" />
  	  <input type="hidden" name="paypal_tab" id="paypal_tab"  value ="<?php echo $tab ?>" />
    
<?php    
		echo $pane->endPanel();
		}
		
	if($apptpro_config->adv_admin_show_coupons == "Yes"){
		$tab = $tab + 1;

		echo $pane->startPanel(JText::_('RS1_ADMIN_SCRN_TAB_COUPONS'), 'panel7');

		// get operator's resources
        $user =& JFactory::getUser();
        $sql = "SELECT CONCAT(\"|\",id_resources,\"|\") as wrapped_id FROM #__sv_apptpro2_resources WHERE resource_admins LIKE '%|".$user->id."|%' ";
		$database->setQuery($sql);
        $my_resources = $database -> loadObjectList();
        if ($database -> getErrorNum()) {
        	echo $database -> stderr();
        	logIt($database->getErrorMsg()); 
        }
		//print_r($my_resources);

		$link_new_coup = JRoute::_( 'index.php?option=com_rsappt_pro2&controller=admin_detail&task=coupons_detail&cid[]=&frompage=advadmin&tab='.$tab);

	?> 
 	<span style="padding-left:-20"> <!-- for IE weirdness -->
	<table class="adminheading" width="100%" style="border-bottom:1px solid #666666;">
	  <tr>
		<th align="left"><?php echo JText::_('RS1_ADMIN_SCRN_TAB_COUPONS');?></th>
        <th align="right">
        <a href="javascript:doCoupPublish();"><?php echo JText::_('RS1_ADMIN_SCRN_PUBLISH');?></a>&nbsp;|
        <a href="javascript:doCoupUnPublish();"><?php echo JText::_('RS1_ADMIN_SCRN_UNPUBLISH');?></a>&nbsp;|
        <a href="javascript:doCoupRemove();"><?php echo JText::_('RS1_ADMIN_SCRN_REMOVE');?></a>&nbsp;|
        <a href="<?php echo $link_new_coup; ?>"><?php echo JText::_('RS1_ADMIN_SCRN_NEW');?></a>                
      </th>
	  </tr>
	</table>
  </span>

        <table cellpadding="4" cellspacing="0" border="0" class="adminlist" width="100%">
		<thead>
        
        <tr bgcolor="#F4F4F4">
          <th  class="title" width="5%" align="center"><input type="checkbox" name="toggle7" value="" onclick="checkAll2(<?php echo count($this->items_coup); ?>, 'coup_cb',7);" /></th>
          <th class="title" align="center"><?php echo JHTML::_( 'grid.sort', JText::_('RS1_ADMIN_SCRN_ID_COL_HEAD'), 'id_coupons', $this->lists['order_Dir_coup'], $this->lists['order_coup'], "coup_" ); ?></th>
          <th class="title" align="center"><?php echo JHTML::_( 'grid.sort', JText::_('RS1_ADMIN_COUPON_DESC'), 'description', $this->lists['order_Dir_coup'], $this->lists['order_coup'], "coup_"  ); ?></th>
          <th class="title" align="center"><?php echo JHTML::_( 'grid.sort', JText::_('RS1_ADMIN_COUPON_CODE'), 'coupon_code',$this->lists['order_Dir_coup'], $this->lists['order_coup'], "coup_"  ); ?></th>
          <th class="title" align="center"><?php echo JHTML::_( 'grid.sort', JText::_('RS1_ADMIN_COUPON_VALUE'), 'discount', $this->lists['order_Dir_coup'], $this->lists['order_coup'], "coup_"  ); ?></th>
          <th class="title" align="center"><?php echo JHTML::_( 'grid.sort', JText::_('RS1_ADMIN_COUPON_EXPIRY'), 'expiry_date', $this->lists['order_Dir_coup'], $this->lists['order_coup'], "coup_"  ); ?></th>
          <th width="15%" class="title" align="center"><?php echo JHTML::_( 'grid.sort', JText::_('RS1_ADMIN_SCRN_PUBLISHED_COL_HEAD'), 'published', $this->lists['order_Dir_coup'], $this->lists['order_coup'], "coup_" ); ?></th>
        </tr>
      </thead>
        <?php
        $k = 0;
		
        for($i=0; $i < count( $this->items_coup ); $i++) {
			$coup_row = $this->items_coup[$i];
			// only show this row if the coupon is assigned to one of the operator's resources
			$show_row = false;
			foreach ($my_resources as $my_resource) {
				if(strpos($coup_row->scope, $my_resource->wrapped_id) > -1){
					$show_row = true;
				}	
			}
			if($show_row == true){
				if($coup_row->published==1){
					$published 	= "<a href='javascript:doCoupUnPublish(".$i.")'><img src='./components/com_rsappt_pro2/tick.png'></a>";
				} else {
					$published 	= "<a href='#' OnClick='javascript:doCoupPublish(".$i.");return false;'><img src='./components/com_rsappt_pro2/publish_x.png'></a>";
				}	
				$link = JRoute::_( 'index.php?option=com_rsappt_pro2&controller=admin_detail&task=coupons_detail&cid[]='. $coup_row->id_coupons.'&frompage=advadmin&tab='.$tab);

			   ?>
				<tr class="<?php echo "row$k"; ?>">
				  <td width="5%" align="center"><input type="checkbox" id="coup_cb<?php echo $i;?>" name="cid_coup[]" value="<?php echo $coup_row->id_coupons; ?>" onclick="isChecked(this.checked);" /></td>
				  <td width="5%" align="center"><a href="<?php echo $link; ?>"><?php echo stripslashes($coup_row->id_coupons); ?></a></td>
				  <td align="left"><?php echo $coup_row->description; ?>&nbsp;</td>
				  <td align="center"><?php echo $coup_row->coupon_code; ?>&nbsp;</td>
				  <td align="center"><?php echo $coup_row->discount; ?>/<?php echo $coup_row->discount_unit; ?>&nbsp;</td>
				  <td align="center"><?php echo $coup_row->expiry; ?>&nbsp;</td>
				  <td align="center"><?php echo $published;?></td>
				  <?php $k = 1 - $k; ?>
				</tr>
				<?php 
			}  // if show_row
		} // for
    
    ?>	
      </table>
		<br /><p><span style="font-size:smaller"><?php echo JText::_('RS1_ADMIN_SCRN_COUPON_NOTE2');?></span></p>
	  <input type="hidden" name="coup_filter_order" value="<?php echo  $this->lists['order_coup']; ?>" />
  	  <input type="hidden" name="coup_filter_order_Dir" value ="<?php echo  $this->lists['order_Dir_coup'];?>" />
   	  <input type="hidden" name="coupons_tab" id="coupons_tab" value ="<?php echo $tab ?>" />

<?php    
		echo $pane->endPanel();
	}
	if($apptpro_config->adv_admin_show_extras == "Yes"){
		$tab = $tab + 1;

		echo $pane->startPanel(JText::_('RS1_ADMIN_SCRN_TAB_EXTRAS'), 'panel8');

		// get operator's resources
        $user =& JFactory::getUser();
        $sql = "SELECT CONCAT(\"|\",id_resources,\"|\") as wrapped_id FROM #__sv_apptpro2_resources WHERE resource_admins LIKE '%|".$user->id."|%' ";
		$database->setQuery($sql);
        $my_resources = $database -> loadObjectList();
        if ($database -> getErrorNum()) {
        	echo $database -> stderr();
        	logIt($database->getErrorMsg()); 
        }
		//print_r($my_resources);

		$link_new_ext = JRoute::_( 'index.php?option=com_rsappt_pro2&controller=admin_detail&task=extras_detail&cid[]=&frompage=advadmin&tab='.$tab);

	?> 
 	<span style="padding-left:-20"> <!-- for IE weirdness -->
	<table class="adminheading" width="100%" style="border-bottom:1px solid #666666;">
	  <tr>
		<th align="left"><?php echo JText::_('RS1_ADMIN_SCRN_TAB_COUPONS');?></th>
        <th align="right">
        <a href="javascript:doExtPublish();"><?php echo JText::_('RS1_ADMIN_SCRN_PUBLISH');?></a>&nbsp;|
        <a href="javascript:doExtUnPublish();"><?php echo JText::_('RS1_ADMIN_SCRN_UNPUBLISH');?></a>&nbsp;|
        <a href="javascript:doExtRemove();"><?php echo JText::_('RS1_ADMIN_SCRN_REMOVE');?></a>&nbsp;|
        <a href="<?php echo $link_new_ext; ?>"><?php echo JText::_('RS1_ADMIN_SCRN_NEW');?></a>                
      </th>
	  </tr>
	</table>
  </span>

        <table cellpadding="4" cellspacing="0" border="0" class="adminlist" width="100%">
		<thead>
        
        <tr bgcolor="#F4F4F4">
          <th  class="title" width="5%" align="center"><input type="checkbox" name="toggle8" value="" onclick="checkAll2(<?php echo count($this->items_ext); ?>, 'ext_cb',8);" /></th>
          <th class="title" align="center"><?php echo JHTML::_( 'grid.sort', JText::_('RS1_ADMIN_SCRN_ID_COL_HEAD'), 'id_extras', $this->lists['order_Dir_ext'], $this->lists['order_ext'], "ext_" ); ?></th>
          <th class="title" align="center"><?php echo JHTML::_( 'grid.sort', JText::_('RS1_ADMIN_EXTRAS_LABEL'), 'extras_label', $this->lists['order_Dir_ext'], $this->lists['order_ext'], "ext_"  ); ?></th>
          <th class="title" align="center"><?php echo JHTML::_( 'grid.sort', JText::_('RS1_ADMIN_EXTRAS_COST'), 'extras_cost',$this->lists['order_Dir_ext'], $this->lists['order_ext'], "ext_"  ); ?></th>
          <th class="title" align="center"><?php echo JHTML::_( 'grid.sort', JText::_('RS1_ADMIN_EXTRAS_UNITS'), 'cost_unit', $this->lists['order_Dir_ext'], $this->lists['order_ext'], "ext_"  ); ?></th>
          <th class="title" align="center"><?php echo JHTML::_( 'grid.sort', JText::_('RS1_ADMIN_EXTRAS_ORDERING'), 'ordering', $this->lists['order_Dir_ext'], $this->lists['order_ext'], "ext_"  ); ?></th>
          <th width="15%" class="title" align="center"><?php echo JHTML::_( 'grid.sort', JText::_('RS1_ADMIN_SCRN_PUBLISHED_COL_HEAD'), 'published', $this->lists['order_Dir_ext'], $this->lists['order_couporder_ext'], "ext_" ); ?></th>
        </tr>
      </thead>
        <?php
        $k = 0;
		
        for($i=0; $i < count( $this->items_ext ); $i++) {
			$ext_row = $this->items_ext[$i];
			// only show this row if the coupon is assigned to one of the operator's resources
			$show_row = false;
			foreach ($my_resources as $my_resource) {
				if(strpos($ext_row->resource_scope, $my_resource->wrapped_id) > -1){
					$show_row = true;
				}	
			}
			if($show_row == true){
				if($ext_row->published==1){
					$published 	= "<a href='javascript:doExtUnPublish(".$i.")'><img src='./components/com_rsappt_pro2/tick.png'></a>";
				} else {
					$published 	= "<a href='#' OnClick='javascript:doExtPublish(".$i.");return false;'><img src='./components/com_rsappt_pro2/publish_x.png'></a>";
				}	
				$link = JRoute::_( 'index.php?option=com_rsappt_pro2&controller=admin_detail&task=extras_detail&cid[]='. $ext_row->id_extras.'&frompage=advadmin&tab='.$tab);

			   ?>
				<tr class="<?php echo "row$k"; ?>">
				  <td width="5%" align="center"><input type="checkbox" id="ext_cb<?php echo $i;?>" name="cid_ext[]" value="<?php echo $ext_row->id_extras; ?>" onclick="isChecked(this.checked);" /></td>
				  <td width="5%" align="center"><a href="<?php echo $link; ?>"><?php echo stripslashes($ext_row->id_extras); ?></a></td>
				  <td align="left"><?php echo $ext_row->extras_label; ?>&nbsp;</td>
				  <td align="center"><?php echo $ext_row->extras_cost; ?>&nbsp;</td>
				  <td align="center"><?php echo $ext_row->cost_unit; ?>&nbsp;</td>
				  <td align="center"><?php echo $ext_row->ordering; ?>&nbsp;</td>
				  <td align="center"><?php echo $published;?></td>
				  <?php $k = 1 - $k; ?>
				</tr>
				<?php 
			}  // if show_row
		} // for
    
    ?>	
      </table>
		<br /><p><span style="font-size:smaller"><?php echo JText::_('RS1_ADMIN_SCRN_EXTRA_NOTE2');?></span></p>
	  <input type="hidden" name="ext_filter_order" value="<?php echo  $this->lists['order_ext']; ?>" />
  	  <input type="hidden" name="ext_filter_order_Dir" value ="<?php echo  $this->lists['order_Dir_ext']; ?>" />
   	  <input type="hidden" name="extras_tab" id="extras_tab" value ="<?php echo $tab ?>" />

<?php    
		echo $pane->endPanel();
	}
		}
		// end tab pane
		echo $pane->endPane();
	
	?>
      <br />
      <?php if($apptpro_config->hide_logo == 'No'){ ?>
        <span style="font-size:10px"> Appointment Booking Pro Ver. 2.0 - Copyright 2008-20<?php echo date("y");?> - <a href='http://www.softventures.com' target="_blank">Soft Ventures, Inc.</a></span>
      <?php } ?>
<?php } ?>
  	<input type="hidden" name="option" value="<?php echo $option; ?>" />
  	<input type="hidden" name="controller" value="admin" />
	<input type="hidden" name="id" value="<?php echo $this->user_id; ?>" />
	<input type="hidden" name="task" value="" />
    <input type="hidden" name="current_tab" id="current_tab" value="<?php echo $current_tab; ?>" />
	<input type="hidden" name="frompage" value="advadmin" />
  	<input type="hidden" name="frompage_item" value="<?php echo $itemid ?>" />
      
</form>
