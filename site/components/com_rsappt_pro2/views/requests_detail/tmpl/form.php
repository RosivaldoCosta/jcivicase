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

//	include_once( JPATH_SITE."/components/com_rsappt_pro2/functions2.php" );
//  	setSessionStuff("request");

	$showform= true;
	
	$listpage = JRequest::getVar('listpage', 'list');
	
	if($listpage == 'list'){
		$savepage = 'save';
	} else if($listpage == "front_desk"){
		setSessionStuff("front_desk");
		$savepage = 'save_front_desk';
	} else {
		$savepage = 'save_adv_admin';
	}

	$session = &JSession::getInstance($handler, $options);
	$session->set("status_filter", JRequest::getVar('filter', ''));
	$session->set("request_resourceFilter", JRequest::getVar('resourceFilter', ''));

	$request = JRequest::getString( 'id', '' );
	$itemid = JRequest::getString( 'Itemid', '' );
	$option = JRequest::getString( 'option', '' );

	$user =& JFactory::getUser();
	if($user->guest){
		echo "<font color='red'>".JText::_('RS1_ADMIN_SCRN_NO_LOGIN')."</font>";
		$showform = false;
	} else {
		$database = &JFactory::getDBO(); 
		// get request details
		$user =& JFactory::getUser();
		
		if($this->detail->id_requests==""){
			echo "<font color='red'>".JText::_('RS1_ADMIN_SCRN_NO_ACCESS')."</font>";
			$showform = false;
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

		// get udfs
		$database = &JFactory::getDBO(); 
		//$sql = 'SELECT * FROM #__sv_apptpro2_udfs WHERE published=1 ORDER BY ordering';
		$sql = "SELECT ".
		"#__sv_apptpro2_udfs.udf_label, #__sv_apptpro2_udfs.udf_type, ".
		"#__sv_apptpro2_udfvalues.udf_value, #__sv_apptpro2_udfvalues.id as value_id, ".
		"#__sv_apptpro2_udfvalues.request_id ".
		"FROM ".
		"#__sv_apptpro2_udfvalues INNER JOIN ".
		"#__sv_apptpro2_udfs ON #__sv_apptpro2_udfvalues.udf_id = ".
		"#__sv_apptpro2_udfs.id_udfs ".
		"WHERE ".
		"#__sv_apptpro2_udfvalues.request_id = ".$this->detail->id_requests. " ".
		"ORDER BY ordering ";
		//echo $sql;
		$database->setQuery($sql);
		$udf_rows = $database -> loadObjectList();
		if ($database -> getErrorNum()) {
			echo "DB Err: ". $database -> stderr();
			return false;
		}
		
		// get extras data
		$database = &JFactory::getDBO(); 
		$sql = "SELECT extras_id, extras_label, extras_qty, extras_tooltip, max_quantity FROM ".
		" #__sv_apptpro2_extras_data INNER JOIN #__sv_apptpro2_extras ".
		"   ON #__sv_apptpro2_extras_data.extras_id = #__sv_apptpro2_extras.id_extras ".
		" WHERE #__sv_apptpro2_extras_data.request_id = ".$this->detail->id_requests. " ".
		" ORDER BY ordering ";
		//echo $sql;
		$database->setQuery($sql);
		$extras_rows = $database -> loadObjectList();
		if ($database -> getErrorNum()) {
			echo "DB Err: ". $database -> stderr();
			return false;
		}
	

		// get data for dropdownlist
		
		// get seat types
		$database = &JFactory::getDBO(); 
		$sql = 'SELECT * FROM #__sv_apptpro2_seat_types WHERE published=1 ORDER BY ordering';
		$database->setQuery($sql);
		$seat_type_rows = $database -> loadObjectList();
		if ($database -> getErrorNum()) {
			echo "DB Err: ". $database -> stderr();
			return false;
		}
	
		// get seat values
		$sql = "SELECT seat_type_id, seat_type_label, seat_type_qty FROM ".
		" #__sv_apptpro2_seat_counts INNER JOIN #__sv_apptpro2_seat_types ".
		"   ON #__sv_apptpro2_seat_counts.seat_type_id = #__sv_apptpro2_seat_types.id_seat_types ".
		" WHERE #__sv_apptpro2_seat_counts.request_id = ".$this->detail->id_requests. " ".
		" ORDER BY ordering ";
		$database->setQuery($sql);
		$seat_rows = $database -> loadObjectList();
		//print_r($seat_rows);
		if ($database -> getErrorNum()) {
			echo "DB Err: ". $database -> stderr();
			return false;
		}
		
		
		//global $database;
		$sql = "(SELECT 0 as id, '".JText::_('RS1_INPUT_SCRN_RESOURCE_PROMPT')."' as name, '".
		JText::_('RS1_INPUT_SCRN_RESOURCE_PROMPT')."' as description, ".
		"0 as ordering, '' as cost) ".
		"UNION (SELECT id_resources,name,description,ordering,CONCAT(' - ', cost) as cost ".
		"FROM #__sv_apptpro2_resources ".
		"WHERE resource_admins LIKE '%|".$user->id."|%' )".
		"ORDER BY ordering;";
		//echo $sql;
		$database->setQuery($sql);
		$res_rows = $database -> loadObjectList();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
			return false;
		}
	
		$sql = "SELECT #__sv_apptpro2_services.* ".
			"FROM #__sv_apptpro2_services LEFT JOIN #__sv_apptpro2_resources ".
			"ON #__sv_apptpro2_services.resource_id = #__sv_apptpro2_resources.id_resources ".
			"WHERE #__sv_apptpro2_services.published = 1 AND #__sv_apptpro2_resources.published = 1 ".
			"AND #__sv_apptpro2_services.resource_id = ".$this->detail->resource." ORDER BY name ";	
		$database->setQuery( $sql );
		$srv_rows = $database -> loadObjectList();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
			return false;
		}
	

		if($apptpro_config->which_calendar == "JEvents"){
			$sql = "SELECT id,title FROM #__categories WHERE section = 'com_events'";
		} else if($apptpro_config->which_calendar == "JCalPro"){
			$sql = "SELECT cat_id as id, cat_name as title FROM #__jcalpro_categories";
		} else if($apptpro_config->which_calendar == "JCalPro2"){
			$sql = "SELECT cat_id as id, cat_name as title FROM #__jcalpro2_categories";
		} else if($apptpro_config->which_calendar == "Thyme"){
			$sql = "SELECT id, title FROM thyme_calendars";
		} else if($apptpro_config->which_calendar == "EventList"){
			$sql = "SELECT id, catname as title FROM #__eventlist_categories";
		}	
		$database->setQuery($sql);
		$cal_cat_rows = $database -> loadObjectList();
		if ($database -> getErrorNum()) {
			//echo $database -> stderr();
			//return false;
		}
		$noCats = "";
		if(count($cal_cat_rows)<1){
			$noCats = JText::_('RS1_ADMIN_SCRN_NO_CATS');
		}
		
	// default calendar (JCalPro 2 only)
	if($apptpro_config->which_calendar == "JCalPro2"){
		$sql = "SELECT cal_id as id, cal_name as title FROM #__jcalpro2_calendars";
	}	
	$database->setQuery($sql);
	$cal_cal_rows = $database -> loadObjectList();
	if ($database -> getErrorNum()) {
		echo $database -> stderr();
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
		//echo "user_field1_label = ".$apptpro_config->user_field1_label;
		//exit;
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
<?php if($showform){?>

<div id="testdiv1" style="VISIBILITY: hidden; POSITION: absolute; BACKGROUND-COLOR: white; layer-background-color: white"> </div>
<link href="./components/com_rsappt_pro2/sv_apptpro.css" rel="stylesheet">
<link href="./components/com_rsappt_pro2/calStyles.css" rel="stylesheet">
<script language="JavaScript" src="./components/com_rsappt_pro2/script.js"></script>
<script language="JavaScript" src="./components/com_rsappt_pro2/CalendarPopup.js"></script>
<script language="JavaScript">
	var cal = new CalendarPopup( <?php echo $div_cal ?>);
	cal.setCssPrefix("TEST");
	cal.setWeekStartDay(<?php echo $apptpro_config->popup_week_start_day ?>);
	//cal.setDisabledWeekDays(0,6);

	
	function getTomorrow(){
		var tomorrow = new Date();
		tomorrow.setDate(tomorrow.getDate()+1);
		var tomstr = '' + tomorrow.getFullYear() + "-" + (tomorrow.getMonth()+1) + "-" +tomorrow.getDate();
		//alert(tomstr);
		return(tomstr);
	}
		
	function doCancel(){
//		document.adminForm.action="<?php echo JURI::base()?>index.php?option=com_rsappt_pro2&controller=admin_detail&task=cancel";
//		document.adminForm.method="post"; 		
//		document.adminForm.submit(); 		

		submitform("cancel");
	}		
	
	function doSave(){
		if(document.getElementById('name').value == ""){
			alert('<?php echo JText::_('RS1_ADMIN_SCRN_NAME_ERR');?>');
			return(false);
		}
		submitform("save");
	}

	function calcSeatTotal(){
		if(document.getElementById("seat_type_count") != null && document.getElementById("seat_type_count").value > 0 ){
			var seat_count = 0; 
			rate = 0.00;
			for(i=0; i<parseInt(document.getElementById("seat_type_count").value); i++){
				seat_name_cost = "seat_type_cost_"+i;
				seat_name = "seat_"+i;
				group_seat_name = "seat_group_"+i;
				seat_count += parseInt(document.getElementById(seat_name).value);
			}
			document.getElementById("booked_seats_div").innerHTML = seat_count;
			document.getElementById("booked_seats").value = seat_count;
		}
	}
	
	function setstarttime(){
		document.getElementById("starttime").value = document.getElementById("starttime_hour").value + ":" + document.getElementById("starttime_minute").value + ":00";
	}
	
	function setendtime(){
		document.getElementById("endtime").value = document.getElementById("endtime_hour").value + ":" + document.getElementById("endtime_minute").value + ":00";
	}
    </script>
<form action="<?php echo JRoute::_($this->request_url) ?>" method="post" name="adminForm" id="adminForm" class="adminForm">
<table width="100%" >
    <tr>
      <td align="left" colspan="2"> <h3><?php echo JText::_('RS1_ADMIN_SCRN_TITLE').JText::_('RS1_ADMIN_SCRN_BOOKING_DETAIL_TITLE');?></h3></td>
    </tr>
</table>
  <table border="0" cellpadding="2" cellspacing="0" >
    <tr>
      <td colspan="3" align="right" height="40px" 
      style="background-color:#FFFFCC; border-top:solid #333333 1px;border-bottom:solid #333333 1px">
      <a href="#" onclick="doSave()"><?php echo JText::_('RS1_ADMIN_SCRN_BTN_SAVE');?></a>
      &nbsp;|&nbsp;&nbsp;<a href="#" onclick="doCancel()"><?php echo JText::_('RS1_ADMIN_SCRN_BTN_CANCEL');?></a>&nbsp;&nbsp;</td>
    </tr>
    <tr class="detail_row1">
      <td width="12%"><?php echo JText::_('RS1_ADMIN_SCRN_REQ_ID_COL_HEAD');?>: </td>
      <td width="40%"><?php echo $this->detail->id_requests; ?></td>
    </tr>
    <tr class="detail_row1">
      <td width="17%"><?php echo JText::_('RS1_ADMIN_SCRN_NAME');?>:<span class="style1">*</span> </td>
      <td><input type="text" size="40" maxsize="100" name="name" id="name" value="<?php echo stripslashes($this->detail->name); ?>" />
      <input type="hidden" name="user_id" value="<?php echo $this->detail->user_id; ?>" /></td>
      <td rowspan="11" valign="top"><?php echo JText::_('RS1_ADMIN_SCRN_GENERAL_INFO');?></td>
    </tr>
<!--    <tr class="detail_row0">
      <td><?php echo JText::_('RS1_ADMIN_SCRN_UNIT');?>: </td>
      <td><input type="text" size="5" maxsize="10" name="unit_number" value="<?php echo $this->detail->unit_number; ?>" /></td>
    </tr>-->
    <tr class="detail_row0">
      <td><?php echo JText::_('RS1_ADMIN_SCRN_PHONE');?>:</td>
      <td><input type="text" size="20" maxsize="20" name="phone" value="<?php echo $this->detail->phone; ?>" /></td>
    </tr>
    <tr class="detail_row1">
      <td><?php echo JText::_('RS1_ADMIN_SCRN_EMAIL');?>: </td>
      <td><input type="text" size="40" maxsize="80" name="email" value="<?php echo $this->detail->email; ?>" /></td>
    </tr>
    <tr class="detail_row0">
      <td><?php echo JText::_('RS1_ADMIN_SCRN_USE_SMS_COL_HEAD');?>:</td>
      <td><select name="sms_reminders" >
          <option value="Yes" <?php if( $this->detail->sms_reminders == "Yes"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_YES');?></option>
          <option value="No" <?php if( $this->detail->sms_reminders == "No"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_NO');?></option>
    </select>&nbsp;</td>
    </tr>
    <tr class="detail_row1">
      <td><?php echo JText::_('RS1_ADMIN_SCRN_SMS_PHONE_COL_HEAD');?>:</td>
      <td><input type="text" size="20" maxsize="20" name="sms_phone" value="<?php echo $this->detail->sms_phone; ?>" />&nbsp;</td>
    </tr>
    <tr class="detail_row0">
      <td><?php echo JText::_('RS1_ADMIN_SCRN_SMS_DIAL_CODE_COL_HEAD');?>:</td>
      <td><input type="text" size="3" maxsize="20" name="sms_dial_code" value="<?php echo $this->detail->sms_dial_code; ?>" />&nbsp;</td>
    </tr>
    <tr class="detail_row1">
      <td><?php echo JText::_('RS1_ADMIN_SCRN_RESOURCE');?>:</td>
      <td><select name="resource" id="resource" class="sv_apptpro2_requests_dropdown" onchange="changeResourceFE();">
          <?php
	$k = 0;
	for($i=0; $i < count( $res_rows ); $i++) {
	$res_row = $res_rows[$i];
	?>
          <option value="<?php echo $res_row->id; ?>" <?php if($this->detail->resource == $res_row->id){echo " selected='selected' ";} ?>><?php echo JText::_(stripslashes($res_row->name)); ?></option>
          <?php $k = 1 - $k; 
	} ?>
        </select></td>
    </tr>
    <tr class="detail_row0">
      <td><?php echo JText::_('RS1_ADMIN_SCRN_SERVICE_COL_HEAD');?>: </td>
      <td><select name="service" id="service">
          <?php
			$k = 0;
			for($i=0; $i < count( $srv_rows ); $i++) {
			$srv_row = $srv_rows[$i];
			?>
          <option value="<?php echo $srv_row->id_services; ?>" <?php if($this->detail->service == $srv_row->id_services){echo " selected='selected' ";} ?>><?php echo JText::_(stripslashes($srv_row->name)); ?></option>
          <?php $k = 1 - $k; 
			} ?>
        </select>&nbsp;</td>
    </tr>
    <tr class="detail_row1">
      <td><?php echo JText::_('RS1_ADMIN_SCRN_STARTDATE');?>: </td>
      <td><input type="text" readonly="readonly" size="10" maxsize="10" name="startdate" value="<?php echo $this->detail->startdate; ?>" />
        <a id="anchor1" onclick="cal.select(document.forms['adminForm'].startdate,'anchor1','yyyy-MM-dd'); return false;"
					 name="anchor1"><img height="15" hspace="2" src="./components/com_rsappt_pro2/icon_cal.gif" width="16" border="0"></a></td>
    </tr>
    <tr class="detail_row0">
      <td><?php echo JText::_('RS1_ADMIN_SCRN_STARTTIME');?>:</td>
        <td width="40%"><select name="starttime_hour" id="starttime_hour" onchange="setstarttime();" class="sv_apptpro2_requests_dropdown">
                <?php 
                for($x=0; $x<24; $x+=1){
                    if($x<10){
                        $x = "0".$x;
                    }
                    echo "<option value=".$x; if(substr($this->detail->starttime,0,2) == $x) {echo " selected='selected' ";} echo ">".$x." </option>";  
                }
                ?>
                </select> : 
                <select name="starttime_minute" id="starttime_minute" onchange="setstarttime();" class="admin_dropdown" >
                <?php
                for($x=0; $x<59; $x+=1){
                    if($x<10){
                        $x = "0".$x;
                    }
                    echo "<option value=".$x; if(substr($this->detail->starttime,3,2) == $x) {echo " selected='selected' ";} echo ">".$x." </option>";  
                }
                ?>
                </select>        
                 (hh:mm)
                 <input type="hidden" name="starttime" id="starttime" value="<?php echo $this->detail->starttime ?>" />              
                </td>
        
    </tr>
    <tr class="detail_row1">
      <td><?php echo JText::_('RS1_ADMIN_SCRN_ENDDATE');?>: </td>
      <td><input type="text" readonly="readonly" size="10" maxsize="10" name="enddate" value="<?php echo $this->detail->enddate; ?>" />
        <a id="anchor2" onclick="cal.select(document.forms['adminForm'].enddate,'anchor2','yyyy-MM-dd'); return false;"
					 name="anchor2"><img height="15" hspace="2" src="./components/com_rsappt_pro2/icon_cal.gif" width="16" border="0"></a></td>
    </tr>
    <tr class="detail_row0">
      <td><?php echo JText::_('RS1_ADMIN_SCRN_ENDTIME');?>:</td>
	  <td width="40%"><select name="endtime_hour" id="endtime_hour" onchange="setendtime();" class="sv_apptpro2_requests_dropdown">
      	<?php 
		for($x=0; $x<24; $x+=1){

			if($x<10){
				$x = "0".$x;
			}
			echo "<option value=".$x; if(substr($this->detail->endtime,0,2) == $x) {echo " selected='selected' ";} echo ">".$x." </option>";  
		}
		?>
        </select> : 
		<select name="endtime_minute" id="endtime_minute" onchange="setendtime();" class="admin_dropdown" >
		<?php
		for($x=0; $x<59; $x+=1){
			if($x<10){
				$x = "0".$x;
			}
			echo "<option value=".$x; if(substr($this->detail->endtime,3,2) == $x) {echo " selected='selected' ";} echo ">".$x." </option>";  
		}
		?>
        </select>        
         (hh:mm)
         <input type="hidden" name="endtime" id="endtime" value="<?php echo $this->detail->endtime ?>" />
		</td>              
    </tr>

    </tr>
    <tr class="detail_row1">
      <td width="12%"><?php echo JText::_('RS1_ADMIN_SCRN_BOOKED_SEATS');?>:</td>
      <td width="40%"><div id="booked_seats_div"><?php echo $this->detail->booked_seats; ?></div><input type="hidden" size="2" maxsize="3" name="booked_seats" id="booked_seats" value="<?php echo $this->detail->booked_seats; ?>" /></td>
    </tr>
	<?php 
	$si = 0; 
	if(count($seat_type_rows)>0){ ?>
		<tr class="detail_row1">
		  <td valign="top" ></td>
		  <td >
                <table border="0" cellpadding="2" cellspacing="1" >
	<?php foreach($seat_type_rows as $seat_type_row){ 
			$thiscount = 0;
	        for($i=0; $i < count( $seat_rows ); $i++) {
    	    	if($seat_type_row->id_seat_types == $seat_rows[$i]->seat_type_id){
					$thiscount = $seat_rows[$i]->seat_type_qty;
				}
			}  ?>

			<tr>
			  <td><?php echo JText::_($seat_type_row->seat_type_label)?>:</td>
			  <td colspan="3" valign="top">
			  <select name="seat_<?php echo $si ?>" id="seat_<?php echo $si?>" onChange="calcSeatTotal();" class="sv_apptpro2_requests_dropdown" 
				title="<?php echo $seat_type_row->seat_type_tooltip ?>"  />
				<?php for($i=0; $i<=$seat_type_row->seat_group_max; $i++){ ?>
						<option value="<?php echo $i ?>" <?php echo ($i == $thiscount?'selected':'') ?>><?php echo $i ?></option>	        
				<?php } ?>
			   </select> 
				&nbsp;
				<input type="hidden" name="seat_type_cost_<?php echo $si?>" id="seat_type_cost_<?php echo $si?>" value="<?php echo $seat_type_row->seat_type_cost ?>"/>  
				<input type="hidden" name="seat_type_id_<?php echo $si?>" id="seat_type_id_<?php echo $si?>" value="<?php echo $seat_type_row->id_seat_types ?>"/>  
				<input type="hidden" name="seat_group_<?php echo $si?>" id="seat_group_<?php echo $si?>" value="<?php echo $seat_type_row->seat_group ?>"/>  
				<input type="hidden" name="seat_type_org_qty_<?php echo $si?>" id="seat_type_org_qty_<?php echo $si?>" value="<?php echo $thiscount ?>"/>  
			  </td>
			</tr>
			<?php $si += 1; 
		} ?>
        </table></td></tr>
	<?php } ?>    

	<?php 
	$ei = 0; 
	if(count($extras_rows)>0){ ?>
        <tr class="detail_row0">
          <td width="12%"><?php echo JText::_('RS1_INPUT_SCRN_EXTRAS_LABEL');?>:</td>
          <td width="40%"></td>
        </tr>

		<tr class="detail_row0">
		  <td width="12%" valign="top" ></td>
		  <td colspan="2">
                <table border="0" cellpadding="2" cellspacing="1" >
	<?php foreach($extras_rows as $extras_row){ ?>
			<tr class="detail_row0">
			  <td><?php echo JText::_($extras_row->extras_label)?>:</td>
			  <td colspan="3" valign="top"><?php echo $extras_row->extras_qty ?>
				&nbsp;
			  </td>
			</tr>
			<?php $ei += 1; 
		} ?>
        </table></td></tr>
	<?php } ?>    
    <tr class="detail_row1">
      <td><?php echo JText::_('RS1_ADMIN_SCRN_COMMENT');?>:</td>
      <td><?php echo $this->detail->comment; ?></td>
    </tr>
    <tr class="detail_row0">
      <td colspan="2" valign="top"><u><?php echo JText::_('RS1_ADMIN_SCRN_UDF');?></u></td>
      <td></td>
    </tr>
    <?php if(count($udf_rows > 0)){?>
		<tr >
		  <td width="12%" valign="top" class="admin_detail_row0"></td>
		  <td colspan="2">
                <table border="0" cellpadding="2" cellspacing="1" >
                  <tr>
                    <td style="font-weight:bold; border-bottom:#999999 solid 1px">Label</td>
                    <td style="font-weight:bold; border-bottom:#999999 solid 1px">Value</td>
<!--                    <td style="font-weight:bold; border-bottom:#999999 solid 1px">Type</td>
-->                  </tr>
        <?php 
		$k = 0;
        for($i=0; $i < count( $udf_rows ); $i++) {
        	$udf_row = $udf_rows[$i];
        	?>
                  <tr class="admin_detail_row0">
                    <td ><?php echo JText::_(stripslashes($udf_row->udf_label))?>:</td>
                    <td ><input type="text" size="40" name=udf_value_<?php echo $i?> value='<?php echo stripslashes($udf_row->udf_value)?>'/>
                    <input type="hidden" name=udf_id_<?php echo $i?> value='<?php echo $udf_row->value_id?>'/>
                    </td>
<!--                    <td valign="top"><?php echo stripslashes($udf_row->udf_type)?></td>
-->                  </tr>
          <?php $k = 1 - $k; 
		} ?>
                </table>
          </td>
        </tr>
    <?php }?>
	
	
	<?php if($apptpro_config->user_field1_label !=""){?>
    <tr>
      <td valign="top"><?php echo $apptpro_config->user_field1_label;?>:</td>
      <td><input type="text" size="40" maxsize="80" name="user_field1_value" value="<?php echo $this->detail->user_field1_value; ?>" /></td>
      <td>&nbsp;&nbsp;(<?php echo $apptpro_config->user_field1_display;?>)</td>
    </tr>
    <?php }
	if($apptpro_config->user_field2_label !=""){?>
    <tr>
      <td valign="top"><?php echo $apptpro_config->user_field2_label;?>:</td>
      <td><input type="text" size="40" maxsize="80" name="user_field2_value" value="<?php echo $this->detail->user_field2_value; ?>" /></td>
      <td>&nbsp;&nbsp;(<?php echo $apptpro_config->user_field2_display;?>)</td>
    </tr>
    <?php }
	if($apptpro_config->user_field3_label !=""){?>
    <tr>
      <td valign="top"><?php echo $apptpro_config->user_field3_label;?>:</td>
      <td><input type="text" size="40" maxsize="80" name="user_field3_value" value="<?php echo $this->detail->user_field3_value; ?>" /></td>
      <td>&nbsp;&nbsp;(<?php echo $apptpro_config->user_field3_display;?>)</td>
    </tr>
    <?php }
	if($apptpro_config->user_field4_label !=""){?>
    <tr>
      <td valign="top"><?php echo $apptpro_config->user_field4_label;?>:</td>
      <td><input type="text" size="40" maxsize="80" name="user_field4_value" value="<?php echo $this->detail->user_field4_value; ?>" /></td>
      <td>&nbsp;&nbsp;(<?php echo $apptpro_config->user_field4_display;?>)</td>
    </tr>
    <?php }?>
    <tr>
      <td valign="top">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr class="detail_row0">
      <td style=" border-top:#999999 solid 1px"><?php echo JText::_('RS1_ADMIN_SCRN_REQUEST_STATUS');?>: </td>
      <td style=" border-top:#999999 solid 1px"><select name="request_status" class="sv_apptpro2_requests_dropdown">
		<?php foreach($statuses as $status_row){ ?>
            <option value="<?php echo $status_row->internal_value ?>" <?php if($this->detail->request_status == $status_row->internal_value){echo " selected='selected' ";} ?>><?php echo JText::_($status_row->status);?></option>        
        <?php } ?>
        </select><input type="hidden" name="old_status" value="<?php echo $this->detail->request_status;?>" /></td>
      <td style=" border-top:#999999 solid 1px" valign="top"><?php echo JText::_('RS1_ADMIN_SCRN_REQUEST_STATUS_HELP');?>&nbsp;</td>
    </tr>
    <tr class="detail_row1">
      <td><?php echo JText::_('RS1_ADMIN_SCRN_PAY_STATUS');?>:</td>
      <td><select name="payment_status" class="sv_apptpro2_requests_dropdown">
          <option value="pending" <?php if($this->detail->payment_status == "pending"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_PAY_STATUS_PENDING');?></option>
          <option value="paid" <?php if($this->detail->payment_status == "paid"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_PAY_STATUS_PAID');?></option>
          <option value="na" <?php if($this->detail->payment_status == "na"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_PAY_STATUS_NA');?></option>
        </select>      </td>
      <td><?php echo JText::_('RS1_ADMIN_SCRN_PAY_STATUS_HELP');?> </td>
    <tr class="admin_detail_row1">
      <td ><?php echo JText::_('RS1_ADMIN_SCRN_BOOKING_TOTAL');?> :</td>
      <td ><?php echo JText::_('RS1_INPUT_SCRN_CURRENCY_SYMBOL');?>&nbsp;<input type="text" size="5" maxsize="10" name="booking_total" value="<?php echo $this->detail->booking_total; ?>" align="baseline" /></td>
      <td><?php echo JText::_('RS1_ADMIN_SCRN_BOOKING_TOTAL_HELP');?></td>
    </tr>
    <tr class="admin_detail_row1">
      <td><?php echo JText::_('RS1_ADMIN_SCRN_BOOKING_DUE');?> :</td>
      <td><?php echo JText::_('RS1_INPUT_SCRN_CURRENCY_SYMBOL');?>&nbsp;<input type="text" size="5" maxsize="10" name="booking_due" value="<?php echo $this->detail->booking_due; ?>" /></td>
      <td><?php echo JText::_('RS1_ADMIN_SCRN_BOOKING_DUE_HELP');?></td>
    </tr>
    <tr class="admin_detail_row1">
      <td><?php echo JText::_('RS1_ADMIN_SCRN_CREDIT_USED');?> :</td>
      <td><?php echo JText::_('RS1_INPUT_SCRN_CURRENCY_SYMBOL');?>&nbsp;<?php echo $this->detail->credit_used; ?><input type="hidden" name="credit_used" value="<?php echo $this->detail->credit_used; ?>" /></td>
      <td><?php echo JText::_('RS1_ADMIN_SCRN_CREDIT_USED_HELP');?></td>
    </tr>
    <tr class="admin_detail_row1">
      <td><?php echo JText::_('RS1_ADMIN_SCRN_COUPON_CODE');?> :</td>
      <td><?php echo $this->detail->coupon_code; ?></td>
      <td></td>
    </tr>
    <tr class="admin_detail_row1">
      <td width="12%"><?php echo JText::_('RS1_ADMIN_SCRN_PP_TXN_DETAIL_ID');?> </td>
      <td><?php echo stripslashes($this->detail->txnid); ?></td>
      <td><?php echo JText::_('RS1_ADMIN_SCRN_PP_TXN_DETAIL_ID_HELP');?> </td>
    </tr>   
    </tr>
    <?php if($apptpro_config->which_calendar != "None"){ ?>
    <tr class="detail_row0" >
      <td><?php echo JText::_('RS1_ADMIN_SCRN_CURRENT_CALENDAR');?>:</td>
      <td><?php echo $apptpro_config->which_calendar ?></td>
      <td valign="top">&nbsp;</td>
    </tr>
    <?php if($apptpro_config->which_calendar == "Google"){ ?>
    <tr  class="admin_detail_row0">
      <td>&nbsp;</td>
      <td><?php echo $this->detail->google_event_id ?><input type="hidden" name="google_event_id" id="google_event_id" value="<?php echo $this->detail->google_event_id ?>" />
			<input type="hidden" name="google_calendar_id" value="<?php echo $this->detail->google_calendar_id; ?>" />
      </td>
    </tr>
    <?php } ?> 
    <tr class="detail_row1">
      <td><?php echo JText::_('RS1_ADMIN_SCRN_POST_TO_CALENDAR');?>:</td>
      <td>
	  <select name="show_on_calendar" class="sv_apptpro2_requests_dropdown" >
	      <option value="Yes" <?php if( $this->detail->show_on_calendar == "Yes"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_YES');?></option>
	      <option value="No" <?php if( $this->detail->show_on_calendar == "No"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SCRN_NO');?></option>
    </select></td>
      <td valign="top"><?php echo JText::_('RS1_ADMIN_SCRN_POST_TO_CALENDAR_HELP');?>&nbsp;</td>       
    </tr>
    <tr  class="detail_row0">
      <td><?php echo JText::_('RS1_ADMIN_SCRN_CALENDAR_CATEGORY');?>:</td>
      <td><select name="calendar_category"  class="sv_apptpro2_requests_dropdown">
          <?php
			$k = 0;
			for($i=0; $i < count( $cal_cat_rows ); $i++) {
			$cal_cat_row = $cal_cat_rows[$i];
			?>
          <option value="<?php echo $cal_cat_row->id; ?>"
          <?php if($cal_cat_row->id == $this->detail->calendar_category){ echo "selected";}?>
          ><?php echo $cal_cat_row->title; ?></option>
          <?php $k = 1 - $k; 
	} ?>
        </select></td>
        <td><span style="color:#FF0000"><?php echo $noCats?></span><?php echo JText::_('RS1_ADMIN_SCRN_CALENDAR_CATEGORY_HELP');?>        </td>
    </tr>
    <tr class="admin_detail_row0">
      <td width="12%"><?php echo JText::_('RS1_ADMIN_SCRN_CALENDAR_CALENDAR');?></td>
      <td valign="top"><select name="calendar_calendar" >
          <?php
			$k = 0;
			for($i=0; $i < count( $cal_cal_rows ); $i++) {
			$cal_cal_row = $cal_cal_rows[$i];
			?>
          <option value="<?php echo $cal_cal_row->id; ?>"
          <?php if($cal_cal_row->id == $this->detail->calendar_calendar){ echo "selected";}?>
          ><?php echo $cal_cal_row->title; ?></option>
          <?php $k = 1 - $k; 
	     } ?>
        </select></td>
      <td><span style="color:#FF0000"><?php echo $noCats?></span> </td>
    </tr>
   <tr class="detail_row1">
      <td valign="top"><?php echo JText::_('RS1_ADMIN_SCRN_CALENDAR_COMMENT');?>:</td>
      <td ><textarea  name="calendar_comment" class="sv_apptpro2_requests_text" rows="4" cols="40"><?php echo $this->detail->calendar_comment; ?></textarea></td>
      <td valign="top"><?php echo JText::_('RS1_ADMIN_SCRN_CALENDAR_COMMENT_HELP');?></td>
    </tr>
	<?php } // end of if($apptpro_config->which_calendar != "None") ?>
    <tr class="detail_row0">
      <td valign="top" ><?php echo JText::_('RS1_ADMIN_SCRN_ADMINCOMMENT');?>:</td>
      <td ><textarea name="admin_comment" class="sv_apptpro2_requests_text" rows="4" cols="40" ><?php echo stripslashes($this->detail->admin_comment); ?></textarea></td>
      <td valign="top" ><?php echo JText::_('RS1_ADMIN_SCRN_ADMINCOMMENT_HELP');?></td>
    </tr>
    <tr class="detail_row1">
      <td><?php echo JText::_('RS1_ADMIN_SCRN_TIMESTAMP');?>: </td>
      <td><?php echo $this->detail->created; ?></td>
      <td></td>
    </tr>
  </table>
  <input type="hidden" name="id_requests" id="id_requests" value="<?php echo $this->detail->id_requests; ?>" />
  <input type="hidden" name="option" value="<?php echo $option; ?>" />
  <input type="hidden" name="controller" value="admin_detail" />
  <input type="hidden" name="id" value="<?php echo $this->user_id; ?>" />
  <input type="hidden" name="task" value="" />
  <input type="hidden" name="seat_type_count" id="seat_type_count" value="<?php echo count($seat_type_rows) ?>"/>  
  <input type="hidden" name="udf_rows_count" id="udf_rows_count" value="<?php echo count($udf_rows) ?>"/>  
  <input type="hidden" name="frompage" value="<?php echo $listpage ?>" />
  <input type="hidden" name="frompage_item" value="<?php echo $itemid ?>" />
  <input type="hidden" name="fromtab" value="0" />
  <br /> 

  <?php if($apptpro_config->hide_logo == 'No'){ ?>
  <span style="font-size:10px"> Appointment Booking Pro Ver. 2.0 - Copyright 2008-20<?php echo date("y");?> - <a href='http://www.softventures.com' target="_blank">Soft Ventures, Inc.</a></span>
  <?php } ?>
</form>
<?php } ?>