<?php

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

$quickLaunchOpen = $this->params->get('quickLaunchOpen', 'click');
$quickLaunchOpenDuration = $this->params->get('quickLaunchOpenDuration', '500');

$modules = JModuleHelper::getModules("apquicklaunch");
$moduleCount = count($modules);
if($moduleCount > 0)
{
	for($moduleIndex = 0; $moduleIndex < $moduleCount; $moduleIndex++)
	{
		$module = $modules[$moduleIndex];
		print "<div class=\"apquicklaunch\" id=\"apquicklaunch".$module->title."\" style=\"display: none\">\n";
		print JModuleHelper::renderModule($module);
		print "</div>\n";
	}
}

?>

<script type="text/javascript">
	var apQuickLaunchOpenDuration = <?php print $quickLaunchOpenDuration; ?>;

<?php
	if($quickLaunchOpen == 'hover')
	{
?>
	window.addEvent('domready', function() {
		$$('a').each(function(anchor) {
			if(anchor.onclick && anchor.onclick.toString().indexOf('apShowQuickLaunch(') > -1)
			{
				anchor.addEvent('mouseenter', anchor.onclick);
			}
		});
	});
<?php
	}
?>

</script>
