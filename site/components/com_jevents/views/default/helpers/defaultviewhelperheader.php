<?php 
defined('_JEXEC') or die('Restricted access');

function DefaultViewHelperHeader($view){
	global $mainframe;

	$cfg		= & JEVConfig::getInstance();
	$version	= & JEventsVersion::getInstance();
	$jevtype	= JRequest::getVar('jevtype');
	$evid		= JRequest::getInt('evid');
	$pop		= JRequest::getInt('pop', 0);

	echo "\n" . '<!-- '
	. $version->getLongVersion() . ', '
	. utf8_encode(@html_entity_decode($version->getLongCopyright(), ENT_COMPAT, 'ISO-8859-1')) . ', '
	. $version->getUrl()
	. ' -->' . "\n";

	// stop crawler and set meta tag
	if ($cfg->get("com_blockRobots", 1) == 1) {
		JEVHelper::checkRobotsMetaTag();
	}

?>
<table class="contentpaneopen" id="jevents_header">
	<tr>
	<td class="contentheading" width="100%">
	<?php 
	$t_headline = '&nbsp;';
	switch ($cfg->get('com_calHeadline', 'comp')) {
		case 'none':
			$t_headline = '&nbsp;';
			break;
		case 'menu':
			$menu2   =& JSite::getMenu();
			$menu    = $menu2->getActive();
			if (isset($menu) && isset($menu->name)) {
				$t_headline = $menu->name;
			}
			break;
		default:
			$t_headline = JText::_('JEV_EVENT_CALENDAR');
			break;
	}
	echo $t_headline;
	?>
	</td>
	<?php
	$task = JRequest::getString("jevtask");
	if ($cfg->get('com_print_icon_view', 1)){
		$print_link = 'index.php?option=' . JEV_COM_COMPONENT
		. '&task=' . $task
		. ($evid ? '&evid=' . $evid : '')
		. ($jevtype ? '&jevtype=' . $jevtype : '')
		. ($view->year ? '&year=' . $view->year : '')
		. ($view->month ? '&month=' . $view->month : '')
		. ($view->day ? '&day=' . $view->day : '')
		. $view->datamodel->getItemidLink()
		. $view->datamodel->getCatidsOutLink()
		. '&pop=1'
		. '&tmpl=component';
		$print_link = JRoute::_($print_link);

		if ($pop) { ?>
			<td width="20" class="buttonheading" align="right">
			<a href="javascript:void(0);" onclick="javascript:window.print(); return false;" title="<?php echo JText::_('JEV_CMN_PRINT'); ?>">
			<img src="<?php echo JURI::root();?>images/M_images/printButton.png" align="middle" name="image" border="0" alt="<?php echo JText::_('JEV_CMN_PRINT');?>" />
			</a>
			</td> <?php
		} else { ?>
			<td  width="20" class="buttonheading" align="right">
			<a href="javascript:void(0);" onclick="window.open('<?php echo $print_link; ?>', 'win2', 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=600,height=600,directories=no,location=no');" title="<?php echo JText::_('JEV_CMN_PRINT'); ?>"><img src="<?php echo JURI::root(); ?>images/M_images/printButton.png"  align="middle" name="image" border="0" alt="<?php echo JText::_('JEV_CMN_PRINT'); ?>" /></a>
			</td> <?php 
		}
	}
	echo '<td class="buttonheading" align="right">';
	echo '<a href="http://www.jevents.net" target="_blank">'
	. "<img src=\"" . JURI::root() . "components/".JEV_COM_COMPONENT."/views/".$view->getViewName()."/assets/images/help.gif\" border=\"0\" alt=\"help\" />"
	. "</a>";
	echo "</td>"
	?>
	</tr>
</table>
<table class="contentpaneopen" id="jevents_body">
	<tr>
	<td width="100%">
<?php
}
