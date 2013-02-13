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

?>

<style type="text/css">
<!--
.icon-48-backup { background-image: url(./components/com_rsappt_pro2/images/backup.png); }
-->
}
</style>
<script language="javascript">
	function doBackup(){
		submitform("backup");
	}
	function doRestore(){
		submitform("restore");
	}
	function doImport(){
		submitform("import");
	}
	
</script>
<form action="<?php echo $this->request_url; ?>" method="post" name="adminForm" >
<link href="../components/com_rsappt_pro2/sv_apptpro.css" rel="stylesheet">
  <table border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td colspan="3"><p><?php echo JText::_('RS1_ADMIN_SCRN_BACKUP_INTRO');?><br/><?php echo JText::_('RS1_ADMIN_SCRN_BACKUP_INTRO_LANG');?></p>
      <p>&nbsp;</p></td>
    </tr>
  <tr>
    <td align="center" width="30%"><input type="button" name="btnBackup" id="btnBackup" value="<?php echo JText::_('RS1_ADMIN_SCRN_BACKUP_NOW');?>" onclick="doBackup();"/></td>
    <td align="center" width="30%"><input type="button" name="btnRestore" id="btnRestore" value="<?php echo JText::_('RS1_ADMIN_SCRN_BACKUP_RESTORE_NOW');?>" onclick="doRestore();"/></td>
  </tr>
  <tr>
    <td align="center"><p>
      <label><input type="checkbox" name="chkBackupErrorLog" id="chkBackupErrorLog" />
      <?php echo JText::_('RS1_ADMIN_SCRN_BACKUP_INCL_ERROR');?></label>
      <br />
      <label><input type="checkbox" name="chkBackupReminderLog" id="chkBackupReminderLog" />
      <?php echo JText::_('RS1_ADMIN_SCRN_BACKUP_INCL_REM');?></label>
      <br />
      <label><input type="checkbox" name="chkBackupLangFile" id="chkBackupLangFile" />
      <?php echo JText::_('RS1_ADMIN_SCRN_BACKUP_INCL_LANG');?></label>
    </p></td>
    <td align="center"><p>
      <label><input type="checkbox" name="chkRestoreErrorLog" id="chkRestoreErrorLog" /><?php echo JText::_('RS1_ADMIN_SCRN_BACKUP_INCL_ERROR_REST');?></label><br />
      <label><input type="checkbox" name="chkRestoreReminderLog" id="chkRestoreReminderLog" /><?php echo JText::_('RS1_ADMIN_SCRN_BACKUP_INCL_REM_REST');?></label><br />
      <label><input type="checkbox" name="chkRestoreLangFile" id="chkRestoreLangFile" /><?php echo JText::_('RS1_ADMIN_SCRN_BACKUP_INCL_LANG_REST');?></label>
    </p></td>
  </tr>
  <tr>
    <td colspan="2">
      <hr /><?php echo JText::_('RS1_ADMIN_SCRN_BACKUP_NOTE');?></td>
    
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2">
      <hr />
      <?php echo JText::_('RS1_ADMIN_SCRN_IMPORT');?>
      <input type="button" name="btnImport" id="btnImport" value="<?php echo JText::_('RS1_ADMIN_SCRN_IMPORT_NOW');?>" onclick="doImport();"/></td>
    
    <td>&nbsp;</td>
  </tr>
</table>
  <p>&nbsp;</p>
  <p>
  <input type="hidden" name="controller" value="backup_restore" />
  <input type="hidden" name="boxchecked" value="0" />
  <input type="hidden" name="hidemainmenu" value="0" />  
  <input type="hidden" name="task" value="" />
  <br />
  <span style="font-size:10px"> Appointment Booking Pro Ver. 2.0 - Copyright 2008-20<?php echo date("y");?> - <a href='http://www.softventures.com' target="_blank">Soft Ventures, Inc.</a></span>
</form>
