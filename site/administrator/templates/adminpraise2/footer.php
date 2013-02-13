<?php

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

print "<div id=\"ap-footer\">\n";

$modules = JModuleHelper::getModules("apfooter");
$moduleCount = count($modules);
if($moduleCount > 0)
{
	print "<div id=\"apfooter\" class=\"mootabs\">\n";

	print "<ul class=\"mootabs_title\">\n";
	for($moduleIndex = 0; $moduleIndex < $moduleCount; $moduleIndex++)
	{
		$module = $modules[$moduleIndex];
		print "<li title=\"apfooter".$module->id."\">\n";
		print $module->title;
		print "</li>\n";
	}
	print "</ul>\n";

	for($moduleIndex = 0; $moduleIndex < $moduleCount; $moduleIndex++)
	{
		$module = $modules[$moduleIndex];
		print "<div id=\"apfooter".$module->id."\" class=\"mootabs_panel\">\n";
		print JModuleHelper::renderModule($module);
		print "</div>\n";
	}

	print "</div>\n";
	print "<div class=\"clr\"></div>\n";

	print "<script type=\"text/javascript\">\n";
	print "window.addEvent('domready', function() {\n";
	print "	var mooTab = new mootabs('apfooter', {height: 'auto', width: '100%', changeTransition: 'none', mouseOverClass: 'over'});\n";
	print "});\n";
	print "</script>\n";
}

print "</div>\n";

?>
