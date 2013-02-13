<?php

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

$rightTabOpen = $this->params->get('rightTabOpen', 'click');
$rightTabOpenDuration = $this->params->get('rightTabOpenDuration', '500');

?>

<script type="text/javascript">
	var apRightCollapsedCookieName = "ap_rightcollapsed";
	var apRightTabOpenDuration = <?php print $rightTabOpenDuration; ?>;

	function apToggleRight(anchor)
	{
		var apContentDiv = $('ap-content');
		var apRightTabs = $$('.aprighttab');
		var apRightDivs = $$('.aprightdiv');
		var rightShown = (anchor.parentNode.id == 'ap-collapse');

		if(rightShown)
		{
			anchor.parentNode.id = 'ap-expand';
			anchor.parentNode.parentNode.id = 'sm-right';
			apContentDiv.removeClass('ap-narrow');
			apContentDiv.addClass('ap-wide');
			apRightDivDisplay = 'none';

			var clientProfile = $('Client_Profile_');
			if(clientProfile)
			{
				clientProfile.setStyle('width','99%');
				$('activities').setStyle('width','96%');
				$$('.form-item').setStyle('width','96%');
				$$('.footer').addClass('ap-wide');
			}
		}
		else
		{
			anchor.parentNode.id = 'ap-collapse';
			anchor.parentNode.parentNode.id = 'ap-right';
			apContentDiv.removeClass('ap-wide');
			apContentDiv.addClass('ap-narrow');
			apRightDivDisplay = '';
		}

		// Hide/show all the divs
		apSetRightDivsDisplay(apRightDivDisplay);

		// Add/Remove div events
		for(i = 0; i < apRightDivs.length; i++)
		{
			if(rightShown)
			{
				apRightDivs[i].addEvent('mouseleave', apHideRightDiv);
				apRightDivs[i].setStyle('margin-top', (apRightDivs[i].getAttribute('moduleindex')*29) + 'px');
			}
			else
			{
				apRightDivs[i].removeEvent('mouseleave', apHideRightDiv);
				apRightDivs[i].setStyle('margin-top', '0px');
			}
		}

		// Add/Remove tab events
		for(i = 0; i < apRightTabs.length; i++)
		{
			if(rightShown)
			{
				apRightTabs[i].addEvent('click', apShowRightDiv);
			}
			else
			{
				apRightTabs[i].removeEvent('click', apShowRightDiv);
			}
		}

		apSetCookie(apRightCollapsedCookieName, (rightShown ? '1' : ''));
	}
	function apSetRightDivsDisplay(apRightDivDisplay)
	{
		var apRightDivs = $$('.aprightdiv');
		for(i = 0; i < apRightDivs.length; i++)
		{
			apRightDivs[i].setStyle('display', apRightDivDisplay);
		}
	}
	function apShowRightDiv()
	{
		apSetRightDivsDisplay('none');
		
		var apRightDiv = $(this.title);
		apRightDiv.setStyle('opacity', 0);
		apRightDiv.setStyle('display', '');
		apRightDiv.effect('opacity', {
			duration: apRightTabOpenDuration
		}).start(0, 1);
	}
	function apHideRightDiv(event)
	{
		this.effect('opacity', { 
			duration: apRightTabOpenDuration,
			onComplete: function(element) { 
				element.setStyle('display', 'none'); 
				element.setStyle('opacity', 1);
			}
		}).start(1, 0);
	}
</script>
<div id="ap-right">
	<div id="ap-collapse">
		<a id="apToggleRightAnchor" href="#" onclick="apToggleRight(this)"></a>
	</div>
<?php

	function renderRightTab($position, $tabNumber)
	{
		$modules = JModuleHelper::getModules($position);
		$moduleCount = count($modules);
		if($moduleCount > 0)
		{
			print "<div id=\"apright".$tabNumber."\" class=\"mootabs\">\n";

			print "<ul class=\"mootabs_title\">\n";
			for($moduleIndex = 0; $moduleIndex < $moduleCount; $moduleIndex++)
			{
				$module = $modules[$moduleIndex];
				print "<li title=\"apright".$module->id."\" class=\"aprighttab\">\n";
				print $module->title;
				print "</li>\n";
			}
			print "</ul>\n";

			for($moduleIndex = 0; $moduleIndex < $moduleCount; $moduleIndex++)
			{
				$module = $modules[$moduleIndex];
				print "<div id=\"apright".$module->id."\" moduleindex=\"".$moduleIndex."\" class=\"aprightdiv mootabs_panel\">\n";
	                        print JModuleHelper::renderModule($module);
				print "</div>\n";
			}

			print "</div>\n";
			print "<div class=\"clr\"></div>\n";

			print "<script type=\"text/javascript\">\n";
			print "window.addEvent('domready', function() {\n";
			print "	var mooTab = new mootabs('apright".$tabNumber."', {height: 'auto', width: '100%', changeTransition: 'none', mouseOverClass: 'over'});\n";
			print "});\n";
			print "</script>\n";
		}
	}

	for($tabNumber = 1; $tabNumber < 10; $tabNumber++)
	{
		renderRightTab("apright".$tabNumber, $tabNumber);
	}
	renderRightTab("cpanel", 10);

?>

</div>
<script type="text/javascript">
	window.addEvent('domready', function ()
	{
		var rightCollapsed = Boolean(apGetCookie(apRightCollapsedCookieName));
		var apContentDiv = $('ap-content');

		/*if(!rightCollapsed)
		{
			apContentDiv.setStyle('width', '70%');
		}
		else
		{*/
			apToggleRight($('apToggleRightAnchor'));
		//}

<?php
        	if($rightTabOpen == 'hover')
	        {
?>
		// Make mouseenter fire the click event
		var apRightTabs = $$('.aprighttab');
		for(i = 0; i < apRightTabs.length; i++)
		{
			apRightTabs[i].addEvent('mouseenter', function() {
				this.fireEvent('click');
			});
		}
<?php
		}
?>
	});
</script>
