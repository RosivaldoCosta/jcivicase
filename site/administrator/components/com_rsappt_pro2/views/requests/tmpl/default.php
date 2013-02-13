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

	// Get resources for dropdown list
	$database = &JFactory::getDBO();
	$database->setQuery("SELECT * FROM #__sv_apptpro2_categories WHERE published=1 ORDER BY name" );
	$cat_rows = $database -> loadObjectList();
	if ($database -> getErrorNum()) {
		echo $database -> stderr();
		return false;
	}

	//echo $categoryFilter;
	// Get resources for dropdown list
	$database = &JFactory::getDBO();
	$sql = "SELECT * FROM #__sv_apptpro2_resources WHERE published=1 ";
	if($this->filter_category != "0"){
		$sql .= " AND category_id = ".$this->filter_category. " ";
	}
	$sql .= " ORDER BY name";
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
<style type="text/css">
<!--
.icon-48-bookings { background-image: url(./components/com_rsappt_pro2/images/bookings.png); }
-->
}
</style>
<div id="testdiv1" style="VISIBILITY: hidden; POSITION: absolute; BACKGROUND-COLOR: white; layer-background-color: white"> </div>
<link href="../components/com_rsappt_pro2/calStyles.css" rel="stylesheet">
<script language="JavaScript" src="../components/com_rsappt_pro2/CalendarPopup.js"></script>
<script language="JavaScript">
	var now = new Date();
	var cal = new CalendarPopup( <?php echo $div_cal ?>);
	cal.setCssPrefix("TEST");
	cal.setWeekStartDay(<?php echo $apptpro_config->popup_week_start_day ?>);
</script>
<script>
//	function setTxnID(id){
//		document.getElementById("xid").value = id;
//		submitbutton('edit_direct_transaction');
//		return false;
//	}
	function cleardate(){
		document.getElementById("startdateFilter").value="";
		document.getElementById("enddateFilter").value="";
		submitbutton('');
		return false;		
	}
</script>

<script language="javascript" type="text/javascript">
function submitform(pressbutton){
var form = document.adminForm;
   if (pressbutton)
    {form.task.value=pressbutton;}
    
	if ((pressbutton=='add')||(pressbutton=='edit')||(pressbutton=='publish')||(pressbutton=='unpublish')
	 ||(pressbutton=='orderdown')||(pressbutton=='orderup')||(pressbutton=='saveorder')||(pressbutton=='remove') )
	 {
	  form.controller.value="requests_detail";
	 }
	try {
		form.onsubmit();
		}
	catch(e){}
	
	form.submit();
}
</script>

<form action="<?php echo $this->request_url; ?>" method="post" name="adminForm" >
<table class="adminheading" width="100%">
	<tr><th align="left" ><?php echo JText::_('RS1_ADMIN_APPT_LIST');?></th></tr>
    <tr><th>
       <table class="adminheading" align="right" cellspadding="2" border="0">
        <tr>
        <td align="left"><input type="text" id="user_search" name="user_search" size="20" title="<?php echo JText::_('RS1_ADMIN_APPT_LIST_SEARCH_HELP');?>" 
        value="<?php echo $this->filter_user_search ?>" />&nbsp;<input type="button" onclick="this.form.submit();" value="<?php echo JText::_('RS1_ADMIN_APPT_LIST_SEARCH');?>" />&nbsp;&nbsp;
		</td>
        <td>
            <?php echo JText::_('RS1_ADMIN_APPT_LIST_DATE_RANGE');?>
        </td>
        <td><input type="text" size="12" maxsize="12" name="startdateFilter" id="startdateFilter" readonly="readonly" style="background-color:#FFFFCC"
        value="<?php echo $this->filter_startdate; ?>" onchange="this.form.submit();"/>
        <a href="#" id="anchor1" onclick="cal.select(document.forms['adminForm'].startdateFilter,'anchor1','yyyy-MM-dd'); return false;"
					 name="anchor1"><img height="15" hspace="2" src="../components/com_rsappt_pro2/icon_cal.gif" width="16" border="0"></a>
        <input type="text" size="12" maxsize="12" name="enddateFilter" id="enddateFilter" readonly="readonly" style="background-color:#FFFFCC"
        	value="<?php echo $this->filter_enddate; ?>" onchange="this.form.submit();"/>
        <a href="#" id="anchor2" onclick="cal.select(document.forms['adminForm'].enddateFilter,'anchor2','yyyy-MM-dd'); return false;"
					 name="anchor2"><img height="15" hspace="2" src="../components/com_rsappt_pro2/icon_cal.gif" width="16" border="0"></a>        
        <a href="#" onclick="cleardate();"><?php echo JText::_('RS1_ADMIN_APPT_LIST_CLEAR_DATES');?></a>&nbsp;&nbsp;
        </td>
        <td><select name="categoryFilter" id="categoryFilter" onchange="this.form.submit();" style="background-color:#FFFFCC" >
              <option value="0" <?php if($this->filter_category == "0"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_APPT_LIST_SELECT_CAT');?></option>
              <?php
				$k = 0;
				for($i=0; $i < count( $cat_rows ); $i++) {
				$cat_row = $cat_rows[$i];
				?>
              <option value="<?php echo $cat_row->id_categories; ?>" <?php if($this->filter_category == $cat_row->id_categories){echo " selected='selected' ";} ?>><?php echo stripslashes($cat_row->name); ?></option>
              <?php $k = 1 - $k; 
				} ?>
            </select>&nbsp;&nbsp;
        </td>
        <td><select name="request_resourceFilter" id="request_resourceFilter" onchange="this.form.submit();" style="background-color:#FFFFCC" >
              <option value="0" <?php if($this->filter_request_resource == "0"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_APPT_LIST_SELECT_RES');?></option>
              <?php
				$k = 0;
				for($i=0; $i < count( $res_rows ); $i++) {
				$res_row = $res_rows[$i];
				?>
              <option value="<?php echo $res_row->id_resources; ?>" <?php if($this->filter_request_resource == $res_row->id_resources){echo " selected='selected' ";} ?>><?php echo stripslashes($res_row->name); ?></option>
              <?php $k = 1 - $k; 
				} ?>
            </select>&nbsp;&nbsp;
        </td>
        <td>
            <?php echo JText::_('RS1_ADMIN_APPT_LIST_STATUS');?>
        </td>
        <td>
            <select name="request_status" onchange="this.form.submit();" style="background-color:#FFFFCC" >
              <option value="all" <?php if($this->filter_request_status == "all"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_REQUEST_STATUS_ALL');?></option>
			<?php foreach($statuses as $status_row){ ?>
                <option value="<?php echo $status_row->internal_value ?>" <?php if($this->detail->request_status == $status_row->internal_value){echo " selected='selected' ";} ?>><?php echo JText::_($status_row->status);?></option>        
            <?php } ?>
            </select>&nbsp;&nbsp;
        </td>
		</tr>
       </table>
       </th> 
    </tr>
  </table>
  <table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
	<thead>
    <tr>
      <th width="3%"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" /></th>
      <th width="5%" class="title" align="center"><?php echo JHTML::_( 'grid.sort', JText::_('RS1_ADMIN_SCRN_ID_COL_HEAD'), 'id_requests', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
      <th class="title" align="left"><?php echo JHTML::_( 'grid.sort', JText::_('RS1_ADMIN_SCRN_NAME_COL_HEAD'), 'name', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
      <th class="title" align="left"><?php echo JHTML::_( 'grid.sort', JText::_('RS1_ADMIN_SCRN_EMAIL_COL_HEAD'), 'email', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
      <th class="title" align="left"><?php echo JHTML::_( 'grid.sort', JText::_('RS1_ADMIN_SCRN_CATEGORY_COL_HEAD'), 'CategoryName', $this->lists['order_Dir'], $this->lists['order'] ); ?><br /></th>
      <th class="title" align="left"><?php echo JHTML::_( 'grid.sort', JText::_('RS1_ADMIN_SCRN_RESOURCE_COL_HEAD'), 'ResourceName', $this->lists['order_Dir'], $this->lists['order'] ); ?><br /></th>
      <th class="title" align="left"><?php echo JHTML::_( 'grid.sort', JText::_('RS1_ADMIN_SCRN_SERVICE_COL_HEAD'), 'ServiceName', $this->lists['order_Dir'], $this->lists['order'] ); ?><br /></th>
      <th class="title" align="left"><?php echo JHTML::_( 'grid.sort', JText::_('RS1_ADMIN_SCRN_DATETIME_COL_HEAD'), 'startdatetime', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
      <th class="title" align="left"><?php echo JHTML::_( 'grid.sort', JText::_('RS1_ADMIN_SCRN_SEATS_COL_HEAD'), 'booked_seats', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
      <th class="title" align="left"><?php echo JHTML::_( 'grid.sort', JText::_('RS1_ADMIN_SCRN_PAYPALTXN_COL_HEAD'), 'txnid', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
      <th align="center"><?php echo JHTML::_( 'grid.sort', JText::_('RS1_ADMIN_SCRN_PAYMENT_COL_HEAD'), 'payment_status',  $this->lists['order_Dir'], $this->lists['order'] );?></th>
      <th align="center"><?php echo JHTML::_( 'grid.sort', JText::_('RS1_ADMIN_SCRN_STATUS_COL_HEAD'), 'request_status',  $this->lists['order_Dir'], $this->lists['order'] );?></th>
    </tr>
    </thead>
	<?php
	$k = 0;
	for($i=0; $i < count( $this->items ); $i++) {
	$row = $this->items[$i];
	$published 	= JHTML::_('grid.published', $row, $i );
	$link 	= JRoute::_( 'index.php?option=com_rsappt_pro2&controller=requests_detail&task=edit&cid[]='. $row->id_requests );
	$checked 	= JHTML::_('grid.checkedout', $row, $i, 'id_requests');
	$link_to_pp = JRoute::_( 'index.php?option=com_rsappt_pro2&controller=paypal_transactions_detail&task=view_txn&txnid='. $row->txnid."&frompage=requests" );

   ?>
    <tr class="<?php echo "row$k"; ?>">
      <td align="center"><input type="checkbox" id="cb<?php echo $i;?>" name="cid[]" value="<?php echo $row->id_requests; ?>" onclick="isChecked(this.checked);" /></td>
      <td align="center"><?php echo $row->id_requests; ?>&nbsp;</td>
      <td><a href=<?php echo $link; ?>><?php echo stripslashes($row->name); ?></a></td>
      <td align="left"><a href="mailto:<?php echo $row->email; ?>"><?php echo $row->email; ?></a>&nbsp;</td>
      <td align="left"><?php echo stripslashes($row->CategoryName); ?>&nbsp;</td>
      <td align="left"><?php echo stripslashes($row->ResourceName); ?>&nbsp;</td>
      <td align="left"><?php echo stripslashes($row->ServiceName); ?>&nbsp;</td>
      <td align="left"><?php echo $row->display_startdate;?>&nbsp;<?php echo $row->display_starttime; ?></td>
      <td align="center"><?php echo $row->booked_seats; ?></td>
      <td align="center"><a href="<?php echo $link_to_pp; ?>"><?php echo $row->txnid; ?></a>&nbsp;</td>
      <td align="center"><?php echo $row->payment_status; ?></td>
      <td align="center"><?php echo $row->request_status; ?></td>
      <?php $k = 1 - $k; ?>
    </tr>
    <?php } 

?>
	<tfoot>
   	<td colspan="12"><?php echo $this->pagination->getListFooter(); ?></td>
    </tfoot>
  </table>

  <input type="hidden" name="controller" value="requests" />
  <input type="hidden" name="task" value="" />
  <input type="hidden" name="boxchecked" value="0" />
  <input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
  <input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
  <input type="hidden" name="hidemainmenu" value="0" />  

  <br />
  <span style="font-size:10px"> Appointment Booking Pro Ver. 2.0 - Copyright 2008-20<?php echo date("y");?> - <a href='http://www.softventures.com' target="_blank">Soft Ventures, Inc.</a></span>
</form>
