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
	$database->setQuery("SELECT * FROM #__sv_apptpro2_resources ORDER BY name" );
	$res_rows = $database -> loadObjectList();
	if ($database -> getErrorNum()) {
		echo $database -> stderr();
		return false;
	}

	
?>

<script language="javascript" type="text/javascript">
function submitform(pressbutton){
var form = document.adminForm;
   if (pressbutton)
    {form.task.value=pressbutton;}
    
	if ((pressbutton=='add')||(pressbutton=='edit')||(pressbutton=='publish')||(pressbutton=='unpublish')
	 ||(pressbutton=='orderdown')||(pressbutton=='orderup')||(pressbutton=='saveorder')||(pressbutton=='remove') )
	 {
	  form.controller.value="bookoffs_detail";
	 }
	try {
		form.onsubmit();
		}
	catch(e){}
	
	form.submit();
}
</script>
<style type="text/css">
<!--
.icon-48-bookoffs { background-image: url(./components/com_rsappt_pro2/images/bookoffs.jpg); }
-->
}
</style>
<form action="<?php echo $this->request_url; ?>" method="post" name="adminForm" >
    <table class="adminheading" width="100%">
        <tr>
          <th align="left" ><?php echo JText::_('RS1_ADMIN_BOOKOFFS_LIST');?> <br /></th>
            <th>
            <table class="adminheading" align="right" cellspadding="2">
            <tr>
            <td><?php echo JText::_('RS1_ADMIN_SCRN_RESID_COL_HEAD');?>:</td>
            <td><select name="resource_id" onchange="this.form.submit();" style="background-color:#FFFFCC" >
              <option value="0" <?php if($this->filter_resource == "0"){echo " selected='selected' ";} ?>><?php echo JText::_('RS1_ADMIN_SRV_LIST_SELECT_RES');?></option>
              <?php
				$k = 0;
				for($i=0; $i < count( $res_rows ); $i++) {
				$res_row = $res_rows[$i];
				?>
              <option value="<?php echo $res_row->id_resources; ?>" <?php if($this->filter_resource == $res_row->id_resources){echo " selected='selected' ";} ?>><?php echo stripslashes($res_row->name); ?></option>
              <?php $k = 1 - $k; 
				} ?>
            </select>&nbsp;&nbsp;&nbsp;&nbsp;</td>
            </tr>
           </table>
         </th> 
      </tr>
    </table>
    <?php echo JText::_('RS1_ADMIN_BOOKOFFS_LIST_INTRO');?>
    <table cellpadding="4" cellspacing="0" border="0" class="adminlist" width="100%">
   <thead>
    <tr>
      <th width="5%" align="center"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" /></th>
      <th class="title" align="center"><?php echo JHTML::_( 'grid.sort', JText::_('RS1_ADMIN_SCRN_ID_COL_HEAD'), 'id_bookoffs', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
      <th class="title" align="center"><?php echo JHTML::_( 'grid.sort', JText::_('RS1_ADMIN_SCRN_RESID_COL_HEAD'), 'resource_id', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
      <th class="title" align="center"><?php echo JHTML::_( 'grid.sort', JText::_('RS1_ADMIN_SCRN_DATEOFF_COL_HEAD'), 'off_date', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
      <th class="title" align="center"><?php echo JHTML::_( 'grid.sort', JText::_('RS1_ADMIN_SCRN_BOOKOFF_FULDAY_COL_HEAD'), 'full_day', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
      <th class="title" align="center"><?php echo JHTML::_( 'grid.sort', JText::_('RS1_ADMIN_SCRN_BOOKOFF_RANGE_COL_HEAD'), 'hours', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
      <th class="title" align="center"><?php echo JHTML::_( 'grid.sort', JText::_('RS1_ADMIN_SCRN_DESCRIPTION_COL_HEAD'), 'description', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
 	  <th width="5%" nowrap="nowrap"><?php echo JHTML::_('grid.sort', JText::_('RS1_ADMIN_SCRN_PUBLISHED_COL_HEAD'), 'published', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
 	  <th width="40%" nowrap="nowrap">&nbsp;</th>
    </tr>
  </thead>
    <?php
	$k = 0;
	for($i=0; $i < count( $this->items ); $i++) {
	$row = $this->items[$i];
	$published 	= JHTML::_('grid.published', $row, $i );
	$link 	= JRoute::_( 'index.php?option=com_rsappt_pro2&controller=bookoffs_detail&task=edit&cid[]='. $row->id_bookoffs );
	$checked 	= JHTML::_('grid.checkedout', $row, $i, 'id_bookoffs');
   ?>
    <tr class="<?php echo "row$k"; ?>">
      <td width="5%" align="center"><input type="checkbox" id="cb<?php echo $i;?>" name="cid[]" value="<?php echo $row->id_bookoffs; ?>" onclick="isChecked(this.checked);" /></td>
      <td width="5%" align="center"><a href=<?php echo $link; ?>><?php echo $row->id_bookoffs; ?></a></td>
      <td width="20%" align="center"><?php echo ($row->name == ""?"Global": stripslashes($row->name)); ?>&nbsp;</td>
      <td width="20%" align="center"><?php echo $row->off_date_display; ?>&nbsp;</td>
      <td width="10%" align="center"><?php echo $row->full_day; ?>&nbsp;</td>
      <td width="10%" align="center"><?php echo $row->hours; ?>&nbsp;</td>
      <td width="20%" align="center"><?php echo stripslashes($row->description); ?>&nbsp;</td>
	  <td align="center"><?php echo $published;?></td>
      <td align="left">&nbsp;</td>
      <?php $k = 1 - $k; ?>
    </tr>
    <?php } 

?>	<tfoot>
    	<td colspan="9"><?php echo $this->pagination->getListFooter(); ?></td>
    </tfoot>
  </table>
  <input type="hidden" name="controller" value="bookoffs" />
  <input type="hidden" name="task" value="" />
  <input type="hidden" name="boxchecked" value="0" />
  <input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
  <input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
  <input type="hidden" name="hidemainmenu" value="0" />  

  <br />
  <span style="font-size:10px"> Appointment Booking Pro Ver. 2.0 - Copyright 2008-20<?php echo date("y");?> - <a href='http://www.softventures.com' target="_blank">Soft Ventures, Inc.</a></span>
</form>
