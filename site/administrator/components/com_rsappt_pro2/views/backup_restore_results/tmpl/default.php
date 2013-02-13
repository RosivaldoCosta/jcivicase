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

$action = JRequest::setVar( 'action');

if($action == "backup"){
	require_once ( JPATH_SITE."/administrator/components/com_rsappt_pro2/backup.php" );
} else if($action == "import"){
	require_once ( JPATH_SITE."/administrator/components/com_rsappt_pro2/import.php" );
} else {
	require_once ( JPATH_SITE."/administrator/components/com_rsappt_pro2/restore.php" );
}

?>
<script language="javascript" type="text/javascript">
	function submitbutton(pressbutton) {
		submitform(pressbutton);
	}

</script>

<form action="<?php echo $this->request_url; ?>" method="post" name="adminForm" >
<link href="../components/com_rsappt_pro2/sv_apptpro.css" rel="stylesheet">

<?php
if($action == "backup"){ 
	backupnow();
} else if($action == "import"){ 
	importnow();
} else { 
	restorenow();
} ?>

<p></p>
<p></p>

  <input type="hidden" name="controller" value="backup_restore" />
  <input type="hidden" name="boxchecked" value="0" />
  <input type="hidden" name="hidemainmenu" value="0" />  
  <input type="hidden" name="task" value="" />

  <br />
  <span style="font-size:10px"> Appointment Booking Pro Ver. 2.0 - Copyright 2008-20<?php echo date("y");?> - <a href='http://www.softventures.com' target="_blank">Soft Ventures, Inc.</a></span>
</form>
