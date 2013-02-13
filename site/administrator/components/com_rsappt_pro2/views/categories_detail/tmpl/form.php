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

	// get parent categories
	$database = &JFactory::getDBO(); 
	if($this->detail->id_categories == ""){
		$sql = 'SELECT * FROM #__sv_apptpro2_categories WHERE (parent_category IS NULL OR parent_category =\'\') order by ordering';
	} else {
		$sql = 'SELECT * FROM #__sv_apptpro2_categories WHERE id_categories != '.$this->detail->id_categories.' AND (parent_category IS NULL OR parent_category =\'\') order by ordering';
	}
	$database->setQuery($sql);
	$parent_cats = $database -> loadObjectList();
	if ($database -> getErrorNum()) {
		echo $database -> stderr();
		logIt($database->getErrorMsg()); 
		return false;
	}	
?>

<link href="../components/com_rsappt_pro14/sv_apptpro.css" rel="stylesheet">
<script language="javascript">
function submitbutton(pressbutton) {
   	if (pressbutton == 'save_category'){
		if(document.getElementById("name").value == ""){
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
        <p><?php echo JText::_('RS1_ADMIN_SCRN_CAT_DETAIL_INTRO');?><br />
        </p>      </td>
    </tr>
    <tr class="admin_detail_row1">
      <td width="25%"><?php echo JText::_('RS1_ADMIN_SCRN_CAT_DETAIL_ID');?></td>
      <td><?php echo $this->detail->id_categories ?></td>
      <td>&nbsp;</td>
    </tr>
    <tr class="admin_detail_row0">
      <td><?php echo JText::_('RS1_ADMIN_SCRN_CAT_DETAIL_NAME');?></td>
      <td colspan="3"><input type="text" size="40" maxsize="50" name="name" id="name" value="<?php echo stripslashes($this->detail->name); ?>" /></td>
    </tr>
    <tr class="admin_detail_row1">
      <td><?php echo JText::_('RS1_ADMIN_SCRN_CAT_DETAIL_DESC');?></td>
      <td><input type="text" size="60" maxsize="80" name="description" value="<?php echo stripslashes($this->detail->description); ?>" /></td>
      <td>&nbsp;</td>
    </tr>
    <tr class="admin_detail_row0">
        <td ><?php echo JText::_('RS1_ADMIN_SCRN_CAT_DETAIL_PARENTS');?></td>
        <td>
            <select name="parent_category">
          	<option value=""><?php echo JText::_('RS1_ADMIN_SCRN_CAT_DETAIL_NOPARENT_PROMPT');?></option>
          <?php 
					$k = 0;
					for($i=0; $i < count( $parent_cats ); $i++) {
					$parent_cat = $parent_cats[$i];
					?>
          	<option value="<?php echo $parent_cat->id_categories; ?>" <?php if($parent_cat->id_categories == $this->detail->parent_category ){echo " selected='selected' ";} ?>><?php echo stripslashes($parent_cat->name); ?></option>
          		<?php $k = 1 - $k; 
					} ?>
          	</select>        </td>
        <td><?php echo JText::_('RS1_ADMIN_SCRN_CAT_DETAIL_PARENTS_HELP');?>&nbsp;</td>
    </tr>
   	<tr  class="admin_detail_row1">
      <td><?php echo JText::_('RS1_ADMIN_SCRN_CAT_DETAIL_ORDER');?></td>
      <td><input type="text" size="5" maxsize="2" name="ordering" value="<?php echo $this->detail->ordering; ?>" />
        &nbsp;&nbsp;</td>
      <td>&nbsp;</td>
   	</tr>
    <tr class="admin_detail_row0">
        <td ><?php echo JText::_('RS1_ADMIN_SCRN_CAT_DETAIL_PUBLISHED');?></td>
        <td>
            <select name="published">
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
  <input type="hidden" name="id_categories" value="<?php echo $this->detail->id_categories; ?>" />
  <input type="hidden" name="task" value="" />
  <input type="hidden" name="controller" value="categories_detail" />
  <br />
  <span style="font-size:10px"> Appointment Booking Pro Ver. 2.0 - Copyright 2008-20<?php echo date("y");?> - <a href='http://www.softventures.com' target="_blank">Soft Ventures, Inc.</a></span>
</form>
