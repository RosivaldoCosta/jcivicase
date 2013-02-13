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

	$req_id = JRequest::getString( 'req_id', '' );
	$itemid = JRequest::getString( 'Itemid', '' );
	$option = JRequest::getString( 'option', '' );
	$which_message = JRequest::getString( 'which_message', 'confirmation' );

	include_once( JPATH_SITE."/components/com_rsappt_pro2/functions2.php" );

	$message = buildMessage($req_id, $which_message, "No");



?>
<script language="javascript">
	function do_continue(){
		document.getElementById("task").value="do_continue";
		document.frmRequest.submit();
	}

	function do_book_another(){
		document.getElementById("task").value="do_book_another";
		document.frmRequest.submit();
	}

</script>
<form name="frmRequest" action="<?php echo JRoute::_($this->request_url) ?>" method="post">
<link href="../components/com_rsappt_pro2/sv_apptpro.css" rel="stylesheet">

	<?php echo $message; ?>

	<p>
    <a href=# onclick="do_book_another()"><?php echo JText::_('RS1_GAD_CONFIRMATION_BOOK_ANOTHER');?></a>
    </p>


  <input type="hidden" name="option" value="<?php echo $option; ?>" />
  <input type="hidden" name="controller" value="bookingscreengad" />
  <input type="hidden" name="task" id="task" value="" />
  <input type="hidden" name="frompage_item" value="<?php echo $itemid ?>" />
  <br />
      <?php if($apptpro_config->hide_logo == 'No'){ ?>
        <span style="font-size:10px"> Appointment Booking Pro Ver. 2.0 - Copyright 2008-20<?php echo date("y");?> - <a href='http://www.softventures.com' target="_blank">Soft Ventures, Inc.</a></span>
      <?php } ?>
</form>
