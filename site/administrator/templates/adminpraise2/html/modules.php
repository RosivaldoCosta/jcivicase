<?php

// no direct access
defined('_JEXEC') or die('Restricted access');

/*
 * Module chrome that add preview information to the module
 */
function modChrome_outline($module, &$params, &$attribs)
{
	$doc =& JFactory::getDocument();
	$css  = ".mod-preview-info { padding: 2px 4px 2px 4px; border: 1px solid black; position: absolute; background-color: white; color: red;opacity: .80; filter: alpha(opacity=80); -moz-opactiy: .80; }";
	$css .= ".mod-preview-wrapper { background-color:#eee;  border: 1px dotted black; color:#700; opacity: .50; filter: alpha(opacity=50); -moz-opactiy: .50;}";
	$doc->addStyleDeclaration($css);

	?>
	<div class="mod-preview">
		<div class="mod-preview-info"><?php echo $module->position."[".$module->style."]"; ?></div>
		<div class="mod-preview-wrapper">
			<?php echo $module->content; ?>
		</div>
	</div>
	<?php
}
?>
