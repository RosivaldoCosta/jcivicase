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

	if($this->detail->id_user_credit == ""){
		// get users 
		$sql = "SELECT id, name FROM #__users WHERE ".
		" id NOT IN (select user_id from #__sv_apptpro2_user_credit)";
		$database->setQuery($sql);
		$users_rows = $database -> loadObjectList();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
			return false;
		}
	} else {
		// get users 
		$sql = "SELECT id, name FROM #__users WHERE id = ".$this->detail->user_id;
		$database->setQuery($sql);
		$users_rows = $database -> loadObjectList();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
			return false;
		}
	}

	// get activity
	if($this->detail->user_id != ""){
		$sql = "SELECT #__sv_apptpro2_user_credit_activity.*, #__users.name as operator, #__sv_apptpro2_requests.startdate, ".
			"DATE_FORMAT(#__sv_apptpro2_requests.startdate, '%b %e') as display_startdate, ".
			"DATE_FORMAT(#__sv_apptpro2_requests.starttime, '%H:%i') as display_starttime, ".
			"#__sv_apptpro2_resources.description as resource ".
			"FROM #__sv_apptpro2_user_credit_activity ".
			"  INNER JOIN #__users ON #__sv_apptpro2_user_credit_activity.operator_id = #__users.id ".
			"  LEFT OUTER JOIN #__sv_apptpro2_requests ON #__sv_apptpro2_user_credit_activity.request_id = #__sv_apptpro2_requests.id_requests ".
			"  LEFT OUTER JOIN #__sv_apptpro2_resources ON #__sv_apptpro2_requests.resource = #__sv_apptpro2_resources.id_resources ".
			"WHERE #__sv_apptpro2_user_credit_activity.user_id = ".$this->detail->user_id." ORDER BY stamp desc";
		$database->setQuery($sql);
		$activity_rows = $database -> loadObjectList();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
			return false;
		}
	}

?>
<script language="javascript">
function submitbutton(pressbutton) {
   	if (pressbutton == 'save_credit'){
		if(document.getElementById("user_id").value == "-1"){
			alert("<?php echo JText::_('RS1_ADMIN_SCRN_CREDIT_USER_REQ');?>");
		} else {
			submitform(pressbutton);
		}
	} else {
		submitform(pressbutton);
	}		
}
function setReqID(id){
	document.getElementById("xid").value = id;
	submitbutton('edit_direct_from_credit');
	return false;
}

</script>
<form action="<?php echo JRoute::_($this->request_url) ?>" method="post" name="adminForm" id="adminForm">
<link href="../components/com_rsappt_pro2/sv_apptpro.css" rel="stylesheet">
<fieldset class="adminform">
  <table border="0" cellpadding="2" cellspacing="0">
    <tr>
      <td colspan="3">
        <p><?php echo JText::_('RS1_ADMIN_SCRN_CREDIT_DETAIL_INTRO');?><br /><?php echo JText::_('RS1_ADMIN_CREDIT_INTRO');?><br />
        </p>      </td>
    </tr>
    <tr class="admin_detail_row1">
      <td width="15%"><?php echo JText::_('ID');?>:</td>
      <td><?php echo $this->detail->id_user_credit ?></td>
      <td width="50%">&nbsp;</td>
    </tr>
    <tr class="admin_detail_row0">
      <td><?php echo JText::_('RS1_ADMIN_SCRN_CREDIT_ID');?>:</td>
      <td align="left"><?php echo $this->detail->user_id ?></td>
      <td width="50%">&nbsp;</td>
    </tr>
    <tr class="admin_detail_row1">
      <td align="left"><?php echo JText::_('RS1_ADMIN_SCRN_CREDIT_USER_NAME');?>:</td>
      <?php if($this->detail->id_user_credit ==""){?>
      <td>
      <select name="user_id" id="user_id">
          <option value="-1"><?php echo JText::_('RS1_ADMIN_SCRN_CREDIT_SEL_USER');?></option>
            <?php
			$k = 0;
			for($i=0; $i < count( $users_rows ); $i++) {
			$users_row = $users_rows[$i];
			?>
                <option value="<?php echo $users_row->id; ?>"<?php if( $this->detail->user_id == $users_row->id){echo " selected='selected' ";} ?>><?php echo stripslashes($users_row->name); ?></option>
                <?php $k = 1 - $k; 
			} ?>
        </select>
      </td>
		<td><?php echo JText::_('RS1_ADMIN_SCRN_CREDIT_SEL_USER_HELP');?>&nbsp;</td>
       <?php } else { ?>
       	<td colspan="2">
       		<input type="hidden" id="user_id" name="user_id" value="<?php echo $this->detail->user_id; ?>" /><?php echo stripslashes($users_rows[0]->name); ?>
        </td>
       <?php } ?>
    </tr>
    <tr class="admin_detail_row0">
      <td valign="top" align="left"><?php echo JText::_('RS1_ADMIN_SCRN_CREDIT_BALANCE');?>:</td>
      <td valign="top"><?php echo JText::_('RS1_INPUT_SCRN_CURRENCY_SYMBOL');?>&nbsp;<input type="text" size="8" maxsize="10" name="balance" id="balance" value="<?php echo stripslashes($this->detail->balance); ?>" /></td>
      <td><?php echo JText::_('RS1_ADMIN_SCRN_CREDIT_BALANCE_HELP');?>&nbsp;</td>
    </tr>
  </table><br /><br />
  <hr />
  <?php echo JText::_('RS1_ADMIN_SCRN_CREDIT_ACTIVITY_INTRO');?><br />
  <table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
	<thead>
    <tr>
      <th width="5%" class="title" align="center"><?php echo JText::_('RS1_ADMIN_SCRN_ID_COL_HEAD'); ?></th>
      <th class="title" align="left"><?php echo JText::_('RS1_ADMIN_CREDIT_ACTIVITY_COMMENT_COL_HEAD'); ?></th>
      <th class="title" align="center"><?php echo JText::_('RS1_ADMIN_CREDIT_ACTIVITY_BOOKING_COL_HEAD'); ?></th>
      <th class="title" align="center"><?php echo JText::_('RS1_ADMIN_CREDIT_ACTIVITY_INCREASE_COL_HEAD'); ?></th>
      <th class="title" align="center"><?php echo JText::_('RS1_ADMIN_CREDIT_ACTIVITY_DECREASE_COL_HEAD'); ?></th>
      <th class="title" align="center"><?php echo JText::_('RS1_ADMIN_CREDIT_ACTIVITY_BALANCE_COL_HEAD'); ?></th>
      <th width="5%" class="title" align="left"><?php echo JText::_('RS1_ADMIN_CREDIT_ACTIVITY_OPERATOR_COL_HEAD'); ?></th>
      <th class="title" align="left"><?php echo JText::_('RS1_ADMIN_CREDIT_ACTIVITY_TIMESTAMP_COL_HEAD'); ?></th>
    </tr>
    </thead>
    <?php
	$k = 0;
	for($i=0; $i < count( $activity_rows ); $i++) {
		$activity_row = $activity_rows[$i];
		$link 	= JRoute::_( 'index.php?option=com_rsappt_pro2&controller=requests_detail&task=edit&cid[]='. $activity_row->request_id."&frompage=UC&frompage_item=".$this->detail->id_user_credit );

   ?>
    <tr class="<?php echo "row$k"; ?>">
      <td align="center"><?php echo $activity_row->id; ?>&nbsp;</td>
      <td align="left"><?php echo stripslashes($activity_row->comment); ?>&nbsp;</td>
      <?php if($activity_row->request_id != ""){ ?>
<!--      <td align="center">(<?php echo $activity_row->request_id; ?>)&nbsp;-->
      <td align="center">(<a href=<?php echo $link; ?>><?php echo $activity_row->request_id; ?>)</a>&nbsp;
	  <?php echo $activity_row->display_startdate."/".$activity_row->display_starttime; ?>&nbsp;- <?php echo stripslashes($activity_row->resource); ?></td>
      <?php } else { ?>
      <td align="center">&nbsp;</td>
      <?php } ?>
      <td align="center"><?php echo $activity_row->increase; ?>&nbsp;</td>
      <td align="center"><?php echo $activity_row->decrease; ?>&nbsp;</td>
      <td align="center"><?php echo $activity_row->balance; ?>&nbsp;</td>
      <td align="center"><?php echo $activity_row->operator; ?>&nbsp;</td>
      <td align="center"><?php echo $activity_row->stamp; ?></td>
      <?php $k = 1 - $k; ?>
    </tr>
    <?php } 
?>
  </table>

</fieldset>
  <input type="hidden" name="id_user_credit" value="<?php echo $this->detail->id_user_credit; ?>" />
  <input type="hidden" name="task" value="" />
  <input type="hidden" name="controller" value="user_credit_detail" />
  <br />
  <span style="font-size:10px"> Appointment Booking Pro Ver. 2.0 - Copyright 2008-20<?php echo date("y");?> - <a href='http://www.softventures.com' target="_blank">Soft Ventures, Inc.</a></span>
</form>
