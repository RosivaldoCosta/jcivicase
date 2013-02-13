<?php 
defined('_JEXEC') or die('Restricted access');
jimport('joomla.html.pane');
require(JPATH_SITE.DS."components".DS."com_jbolo".DS."config".DS."config.php");
$document =& JFactory::getDocument();
$document->addStyleSheet(JURI::base().'components/com_jbolo/css/jbolo.css');
global $mainframe;
$uri	= $mainframe->getSiteURL();
?>
<script language="JavaScript">
function url(uri)
{
	word	= document.getElementById('purge').value;
	document.getElementById('surl').value = '<?php echo JURI::root(); ?>index2.php?option=com_jbolo&purge='+word
}
</script>
 
<form method="POST" name="adminForm" action="index.php">
	<table border="0" width="100%" class="adminlist">
		<tr>
			<td align="left" width="15%"><strong><?php echo JText::_('PURGE') ?>:</strong></td>
			<td align="left" width="25%"><?php echo JHTML::_('select.booleanlist',  'purge', '', $chat_config['purge'] ); ?></td>
			<td align="left" width="60%"><?php echo JHTML::tooltip(JText::_('Allow purging'), '', 'tooltip.png', '', ''); ?></td>
		</tr>
		<tr>
			<td><strong><?php echo JText::_('PURGE_D') ?>:</strong></td>
			<td><input type="text" name="days" width="90%" value="<?php echo $chat_config['days'] ?>" /></td>
			<td><?php echo JHTML::tooltip(JText::_('CHATS OLDER'), '', 'tooltip.png', '', ''); ?></td>
		</tr>		
		<tr>
			<td><strong><?php echo JText::_('BAD_WORDS') ?>:</strong></td>
			<td><input type="text" name="badwords" width="90%" value="<?php echo $chat_config['badwords'] ?>" /></td>
			<td><?php echo JHTML::tooltip(JText::_('BAD_WORDS_TIP'), '', 'tooltip.png', '', ''); ?></td>
		</tr>								
		<tr>
			<td><strong><?php echo JText::_('PURGE_K') ?>:</strong></td>
			<td><input type="text" id="purge" onkeyup="url();" name="key" width="90%" value="<?php echo $chat_config['key'] ?>" />
			</td> 
			<td><?php echo JHTML::tooltip(JText::_('AUTH KEY'), '', 'tooltip.png', '', ''); ?>
			&nbsp; &nbsp; &nbsp; <?php echo JText::_('URL')?>:&nbsp; &nbsp;
			<input size="100" style='border:none' id="surl" value="<?php echo JURI::root() . 'index2.php?option=com_jbolo&action=purge&purge='.$chat_config['key'] ?>" />
			</td>
		</tr>
		<tr>
			<td><strong><?php echo JText::_('CHAT_USERS') ?>:</strong></td>
			<?php
			$username	= '';
			$name		= '';
			if($chat_config['chatusertitle'])$username	= 'checked'; else $name	= 'checked'; ?>
			<td align="left" width="15%"><input type="radio" name="chatusertitle" value="1" <?php echo $username ?> /><?php echo Jtext::_("U_NAME") ?>
			 <input type="radio" name="chatusertitle" value="0" <?php echo $name ?> /><?php echo Jtext::_("NAME") ?></td>
			<td><?php echo JHTML::tooltip(JText::_('NAME USERNAME'), '', 'tooltip.png', '', ''); ?></td>
		</tr>
		<tr>
			<td><strong><?php echo JText::_('COMMUNITY') ?>:</strong></td>
			<?php
				$sa	= '';	//Stand Alone
				$cb	= '';	//Community Builder
				$js	= '';	//JomSocial
				$pt = '';	//PeopleTouch
				if($chat_config['community']==1) $cb	= 'checked'; 
				else if(($chat_config['community']==2)) $sa	= 'checked';
				else if(($chat_config['community']==3)) $pt = 'checked';
				else  $js = 'checked';
			?>
			<td align="left" width="15%">
				 <input type="radio" name="community" value="2" <?php echo $sa ?> ><?php echo Jtext::_("SA")."<br />"  ?>
				 <input type="radio" name="community" value="1" <?php echo $cb ?> ><?php echo Jtext::_("CB")."<br />" ?>
				 <input type="radio" name="community" value="0" <?php echo $js ?> ><?php echo Jtext::_("JS")."<br />"  ?>
 				 <input type="radio" name="community" value="3" <?php echo $pt ?> ><?php echo Jtext::_("PT") ?>
			</td>
			<td><?php echo JHTML::tooltip(JText::_('INTEGRATION'), '', 'tooltip.png', '', ''); ?></td>
		</tr>
		<tr>
			<td><strong><?php echo JText::_('FRIENDS_ONLY') ?>:</strong></td>
			<?php
			$frinds		= '';
			$everyone	= '';
			if($chat_config['fonly'])$frinds	= 'checked'; else $everyone	= 'checked'; ?>
			<td align="left" width="15%">
				<input type="radio" name="fonly" value="1" <?php echo $frinds ?> ><?php echo Jtext::_("F_ONLY") ?>
				<input type="radio" name="fonly" value="0" <?php echo $everyone ?> ><?php echo Jtext::_("E_ONE") ?></td>
			<td><?php echo JHTML::tooltip(JText::_('PRIVACY'), '', 'tooltip.png', '', ''); ?></td>
		</tr>
		<tr>
			<td><strong><?php echo JText::_('ENABLE_CHAT_HISTORY') ?>:</strong></td>
			<td><?php echo JHTML::_('select.booleanlist',  'chathistory', '', $chat_config['chathistory'] ); ?></td>
			<td><?php echo JHTML::tooltip(JText::_('CHAT HISTORY INFO'), '', 'tooltip.png', '', ''); ?></td>
		</tr>
		<tr>
			<td><strong><?php echo JText::_('ENABLE_SEND_FILE') ?>:</strong></td>
			<td><?php echo JHTML::_('select.booleanlist',  'sendfile', '', $chat_config['sendfile'] ); ?></td>
			<td><?php echo JHTML::tooltip(JText::_('SEND_FILE_INFO'), '', 'tooltip.png', '', ''); ?></td>
		</tr>
		<tr>
			<td><strong><?php echo JText::_('SEND_FILE_LIMIT') ?>:</strong></td>
			<td><input type="text" name="uploadlimit" width="90%" value="<?php echo $chat_config['uploadlimit'] ?>" /></td>
			<td><?php echo JHTML::tooltip(JText::_('SEND_FILE_LIMIT_INFO'), '', 'tooltip.png', '', ''); ?></td>
		</tr>
		<tr>
			<td><strong><?php echo JText::_('MAX_CHAT_REFRESH_RATE_TIME') ?>:</strong></td>
			<td><input type="text" name="maxChatHeartbeat" width="90%" value="<?php echo $chat_config['maxChatHeartbeat'] ?>" /></td>
			<td><?php echo JHTML::tooltip(JText::_('MAX_CHAT_REFRESH_RATE_TIME_TOOLTIP'), '', 'tooltip.png', '', ''); ?> <?php echo JText::_('HEART_BEAT_WARNING'); ?></td>
		</tr>
	<!--	<tr>
			<td><strong><?php echo JText::_('ENABLE_GROUP_CHAT') ?>:</strong></td>
			<td><?php echo JHTML::_('select.booleanlist',  'groupchat', '', $chat_config['groupchat'] ); ?></td>
			<td><?php echo JHTML::tooltip(JText::_('GROUP_CHAT_INFO'), '', 'tooltip.png', '', ''); ?></td>
		</tr>-->
	</table>
	<input type="hidden" name="option" value="<?php echo $option; ?>" />		
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="controller" value="config" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
