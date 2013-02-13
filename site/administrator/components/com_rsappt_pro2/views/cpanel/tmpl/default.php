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
.icon-48-control { background-image: url(./components/com_rsappt_pro2/images/control.jpg); }
-->
}
</style>
<form action="index2.php" method="post" name="adminForm" id="adminForm" class="adminForm">

<table style="border: solid 1px #ECECEC; background-color:#FFF">
	
	<tr>
    <td width="25%" align="right">
        <table width="90%" align="center" border="0" cellspacing="0" >
          <tr>
            <td align="right" style="border: 0px;"><img src="<?php echo "./components/com_rsappt_pro2/images/ABPro_logo100.jpg"?>" /></td>
          </tr>
          <tr style="border:solid 1px #CCCCCC;">
            <td align="right">&nbsp;</td>
          </tr>
          <tr style="border:solid 1px #CCCCCC;">
            <td align="right"><a href="index.php?option=com_rsappt_pro2&amp;controller=requests"><?php echo JText::_('RS1_ADMIN_MENU_APPOINTMENTS');?></a></td>
          </tr>
          <tr>
            <td align="right"><a href="index.php?option=com_rsappt_pro2&amp;controller=categories"><?php echo JText::_('RS1_ADMIN_MENU_CATEGORIES');?></a></td>
          </tr>
          <tr>
            <td align="right"><a href="index.php?option=com_rsappt_pro2&amp;controller=resources"><?php echo JText::_('RS1_ADMIN_MENU_RESOURCES');?></a></td>
          </tr>
          <tr>
            <td align="right"><a href="index.php?option=com_rsappt_pro2&amp;controller=services"><?php echo JText::_('RS1_ADMIN_MENU_SERVICES');?></a></td>
          </tr>
          <tr>
            <td align="right"><a href="index.php?option=com_rsappt_pro2&amp;controller=timeslots"><?php echo JText::_('RS1_ADMIN_MENU_TIMESLOTS');?></a></td>
          </tr>
          <tr>
            <td align="right"><a href="index.php?option=com_rsappt_pro2&amp;controller=bookoffs"><?php echo JText::_('RS1_ADMIN_MENU_BOOKOFFS');?></a></td>
          </tr>
          <tr>
            <td align="right"><a href="index.php?option=com_rsappt_pro2&amp;controller=udfs"><?php echo JText::_('RS1_ADMIN_MENU_UDFS');?></a></td>
          </tr>
          <tr>
            <td align="right"><a href="index.php?option=com_rsappt_pro2&amp;controller=coupons"><?php echo JText::_('RS1_ADMIN_MENU_COUPONS');?></a></td>
          </tr>
          <tr>
            <td align="right"><a href="index.php?option=com_rsappt_pro2&amp;controller=config_detail"><?php echo JText::_('RS1_ADMIN_MENU_CONFIGURE');?></a></td>
          </tr>
          <tr>
            <td align="right"><a href="index.php?option=com_rsappt_pro2&amp;controller=paypal_transactions"><?php echo JText::_('RS1_ADMIN_MENU_PAYPAL');?></a></td>
          </tr>
          <tr>
            <td align="right"><a href="index.php?option=com_rsappt_pro2&amp;controller=seat_types"><?php echo JText::_('RS1_ADMIN_MENU_SEATS');?></a></td>
          </tr>
          <tr>
            <td align="right"><a href="index.php?option=com_rsappt_pro2&amp;controller=user_credit"><?php echo JText::_('RS1_ADMIN_MENU_CREDIT');?></a></td>
          </tr>
          <tr>
            <td align="right"><a href="index.php?option=com_rsappt_pro2&amp;controller=extras"><?php echo JText::_('RS1_ADMIN_MENU_EXTRAS');?></a></td>
          </tr>
          <tr>
            <td align="right"><a href="index.php?option=com_rsappt_pro2&amp;controller=edit_files"><?php echo JText::_('RS1_ADMIN_MENU_EDITFILES');?></a></td>
          </tr>
            <tr>
            <td align="right"><a href="index.php?option=com_rsappt_pro2&amp;controller=backup_restore"><?php echo JText::_('RS1_ADMIN_MENU_BACKUP');?></a></td>
          </tr>
          <tr>
            <td align="right"><a href="index.php?option=com_rsappt_pro2&amp;controller=errorlog"><?php echo JText::_('RS1_ADMIN_MENU_ERRLOG');?></a></td>
          </tr>
          <tr>
            <td align="right"><a href="index.php?option=com_rsappt_pro2&amp;controller=reminderlog"><?php echo JText::_('RS1_ADMIN_MENU_REMLOG');?></a></td>
          </tr>
<!--          <tr>
            <td align="right"><a href="index.php?option=com_rsappt_pro2&amp;controller=activitylog"><?php echo JText::_('RS1_ADMIN_MENU_ACTIVITYLOG');?></a></td>
          </tr>
-->          <tr>
            <td align="right"><a href="index.php?option=com_rsappt_pro2&amp;controller=about"><?php echo JText::_('RS1_ADMIN_MENU_ABOUT');?></a></td>
          </tr>
          <tr>
            <td align="right">&nbsp;</td>
          </tr>
      </table>	</td>
    <td valign="top" align="center">
        <table width="90%" border="0" cellspacing="0" cellpadding="5" >
          <tr>
            <td valign="bottom" width="100" style="border: solid 1px #ECECEC" align="center"><a href="index.php?option=com_rsappt_pro2&amp;controller=requests"><img src="<?php echo "./components/com_rsappt_pro2/images/bookings.png"?>" /></a><br /><a href="index.php?option=com_rsappt_pro2&amp;controller=requests"><?php echo JText::_('RS1_ADMIN_MENU_APPOINTMENTS');?></a></td>
            <td valign="bottom" width="100" style="border: solid 1px #ECECEC" align="center"><a href="index.php?option=com_rsappt_pro2&amp;controller=categories"><img src="<?php echo "./components/com_rsappt_pro2/images/pad.png"?>" /></a><br /><a href="index.php?option=com_rsappt_pro2&amp;controller=categories"><?php echo JText::_('RS1_ADMIN_MENU_CATEGORIES');?></a></td>
            <td valign="bottom" width="100" style="border: solid 1px #ECECEC" align="center"><a href="index.php?option=com_rsappt_pro2&amp;controller=resources"><img src="<?php echo "./components/com_rsappt_pro2/images/resources.png"?>" /></a><br /><a href="index.php?option=com_rsappt_pro2&amp;controller=resources"><?php echo JText::_('RS1_ADMIN_MENU_RESOURCES');?></a></td>
            <td valign="bottom" width="100" style="border: solid 1px #ECECEC" align="center"><a href="index.php?option=com_rsappt_pro2&amp;controller=services"><img src="<?php echo "./components/com_rsappt_pro2/images/pad.png"?>" /></a><br /><a href="index.php?option=com_rsappt_pro2&amp;controller=services"><?php echo JText::_('RS1_ADMIN_MENU_SERVICES');?></a></td>
            <td valign="bottom" width="100" class="icon" style="border: solid 1px #ECECEC" align="center"><a href="index.php?option=com_rsappt_pro2&amp;controller=timeslots"><img src="<?php echo "./components/com_rsappt_pro2/images/timeslots.jpg"?>" /></a><br /><a href="index.php?option=com_rsappt_pro2&amp;controller=timeslots"><?php echo JText::_('RS1_ADMIN_MENU_TIMESLOTS');?></a></td>
          </tr>
          <tr>
            <td valign="bottom" style="border: solid 1px #ECECEC" align="center"><a href="index.php?option=com_rsappt_pro2&amp;controller=bookoffs"><img src="<?php echo "./components/com_rsappt_pro2/images/bookoffs.jpg"?>" /></a><br /><a href="index.php?option=com_rsappt_pro2&amp;controller=bookoffs"><?php echo JText::_('RS1_ADMIN_MENU_BOOKOFFS');?></a></td>
            <td valign="bottom" style="border: solid 1px #ECECEC" align="center"><a href="index.php?option=com_rsappt_pro2&amp;controller=udfs"><img src="<?php echo "./components/com_rsappt_pro2/images/udf.png"?>" /></a><br /><a href="index.php?option=com_rsappt_pro2&amp;controller=udfs"><?php echo JText::_('RS1_ADMIN_MENU_UDFS');?></a></td> 
            <td valign="bottom" style="border: solid 1px #ECECEC" align="center"><a href="index.php?option=com_rsappt_pro2&amp;controller=coupons"><img src="<?php echo "./components/com_rsappt_pro2/images/coupon.png"?>" /></a><br /><a href="index.php?option=com_rsappt_pro2&amp;controller=coupons"><?php echo JText::_('RS1_ADMIN_MENU_COUPONS');?></a></td>
            <td valign="bottom" style="border: solid 1px #ECECEC" align="center"><a href="index.php?option=com_rsappt_pro2&amp;controller=config_detail"><img src="<?php echo "./components/com_rsappt_pro2/images/configure.jpg"?>" /></a><br /><a href="index.php?option=com_rsappt_pro2&amp;controller=config_detail"><?php echo JText::_('RS1_ADMIN_MENU_CONFIGURE');?></a></td>
            <td valign="bottom" style="border: solid 1px #ECECEC" align="center"><a href="index.php?option=com_rsappt_pro2&amp;controller=paypal_transactions"><img src="<?php echo "./components/com_rsappt_pro2/images/pay.png"?>" /></a><br /><a href="index.php?option=com_rsappt_pro2&amp;controller=paypal_transactions"><?php echo JText::_('RS1_ADMIN_MENU_PAYPAL');?></a></td>
          </tr>
          <tr>
            <td valign="bottom" style="border: solid 1px #ECECEC" align="center"><a href="index.php?option=com_rsappt_pro2&amp;controller=seat_types"><img src="<?php echo "./components/com_rsappt_pro2/images/seats.png"?>" /></a><br /><a href="index.php?option=com_rsappt_pro2&amp;controller=seat_types"><?php echo JText::_('RS1_ADMIN_MENU_SEATS');?></a></td>
            <td valign="bottom" style="border: solid 1px #ECECEC" align="center">&nbsp;</td>
            <td valign="bottom" style="border: solid 1px #ECECEC" align="center"><a href="index.php?option=com_rsappt_pro2&amp;controller=user_credit"><img src="<?php echo "./components/com_rsappt_pro2/images/vault.png"?>" /></a><br /><a href="index.php?option=com_rsappt_pro2&amp;controller=user_credit"><?php echo JText::_('RS1_ADMIN_MENU_CREDIT');?></a></td>
            <td valign="bottom" style="border: solid 1px #ECECEC" align="center">&nbsp;</td>
            <td valign="bottom" style="border: solid 1px #ECECEC" align="center"><a href="index.php?option=com_rsappt_pro2&amp;controller=extras"><img src="<?php echo "./components/com_rsappt_pro2/images/extras.png"?>" /></a><br /><a href="index.php?option=com_rsappt_pro2&amp;controller=extras"><?php echo JText::_('RS1_ADMIN_MENU_EXTRAS');?></a></td>
          </tr>
          <tr>
            <td valign="bottom" style="border: solid 1px #ECECEC" align="center"><a href="index.php?option=com_rsappt_pro2&amp;controller=edit_files"><img src="<?php echo "./components/com_rsappt_pro2/images/log.png"?>" /></a><br /><a href="index.php?option=com_rsappt_pro2&amp;controller=edit_files"><?php echo JText::_('RS1_ADMIN_MENU_EDITFILES');?></a></td>
            <td valign="bottom" style="border: solid 1px #ECECEC" align="center"><a href="index.php?option=com_rsappt_pro2&amp;controller=backup_restore"><img src="<?php echo "./components/com_rsappt_pro2/images/backup.png"?>" /></a><br />
              <a href="index.php?option=com_rsappt_pro2&amp;controller=backup_restore"><?php echo JText::_('RS1_ADMIN_MENU_BACKUP');?></a></td>
			<td>&nbsp;</td>
            <td valign="bottom" style="border: solid 1px #ECECEC" align="center"><a href="index.php?option=com_rsappt_pro2&amp;controller=errorlog"><img src="<?php echo "./components/com_rsappt_pro2/images/log.png"?>" /></a><br /><a href="index.php?option=com_rsappt_pro2&amp;controller=errorlog"><?php echo JText::_('RS1_ADMIN_MENU_ERRLOG');?></a></td>
            <td valign="bottom" style="border: solid 1px #ECECEC" align="center"><a href="index.php?option=com_rsappt_pro2&amp;controller=reminderlog"><img src="<?php echo "./components/com_rsappt_pro2/images/log.png"?>" /></a><br /><a href="index.php?option=com_rsappt_pro2&amp;controller=reminderlog"><?php echo JText::_('RS1_ADMIN_MENU_REMLOG');?></a></td>
<!--            <td valign="bottom" style="border: solid 1px #ECECEC" align="center"><a href="index.php?option=com_rsappt_pro2&amp;controller=activitylog"><img src="<?php echo "./components/com_rsappt_pro2/images/log.png"?>" /></a><br />
            <a href="index.php?option=com_rsappt_pro2&amp;controller=activitylog"><?php echo JText::_('RS1_ADMIN_MENU_ACTIVITYLOG');?></a></td>-->
          </tr>
          <tr>
            <td valign="middle" style="border: solid 1px #ECECEC" align="center" colspan="4"><?php echo JText::_('RS1_ADMIN_CONTROL_FOOTER');?></td>
            <td valign="bottom" style="border: solid 1px #ECECEC" align="center"><a href="index.php?option=com_rsappt_pro2&amp;controller=about"><img src="<?php echo "./components/com_rsappt_pro2/images/about.png"?>" /></a><br />
            <a href="index.php?option=com_rsappt_pro2&amp;controller=about"><?php echo JText::_('RS1_ADMIN_MENU_ABOUT');?></a></td>
          </tr>
        </table>	  
        <br /></td>
    </tr>
</table>
<hr>
  <br />

  <br />
  <span style="font-size:10px"> Appointment Booking Pro Ver. 2.0 - Copyright 2008-20<?php echo date("y");?> - <a href='http://www.softventures.com' target="_blank">Soft Ventures, Inc.</a></span>
</form>
