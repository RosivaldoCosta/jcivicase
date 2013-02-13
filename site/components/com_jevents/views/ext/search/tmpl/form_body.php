<?php 
defined('_JEXEC') or die('Restricted access');
?>

<table cellpadding="0" cellspacing="0" border="0" width="100%">
	<tr>
		<td align="center" width="100%">
			<form action="index.php" method="get" style="font-size:1;">
				<input type="hidden" name="option" value="<?php echo JEV_COM_COMPONENT; ?>" />
				<input type="hidden" name="task" value="search.results" />
				<input type="hidden" name="Itemid" value="<?php echo $this->Itemid;?>" />
				<input type="text" name="keyword" size="30" maxlength="50" class="inputbox" value="<?php echo $this->keyword;?>" />
				<br />
				<input class="button" type="submit" name="push" value="<?php echo JText::_('JEV_SEARCH_TITLE'); ?>" />
			</form>
		</td>
	</tr>
</table>
