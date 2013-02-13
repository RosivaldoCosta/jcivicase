<?php defined('_JEXEC') or die('Restricted access');?> 
<div id="jevents">
<form action="index.php" method="post" name="adminForm" >
<h3><?php echo JText::_("Database now setup");?></h3>
<input type="submit" value="<?php echo "continue";?>" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="task" value="cpanel.cpanel" />
<input type="hidden" name="act" value="" />
<input type="hidden" name="option" value="<?php echo JEV_COM_COMPONENT;?>" />
</form>
</div>