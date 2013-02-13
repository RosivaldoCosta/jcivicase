<?php
/**
 * copyright (C) 2008 GWE Systems Ltd - All rights reserved
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();


function JevViewCopyright() {

	global $mainframe;

	$cfg	 = & JEVConfig::getInstance();

	$version = & JEventsVersion::getInstance();

	if ($cfg->get('com_copyright', 1) == 1) {
?>
		<p align="center">
			<a href="<?php echo $version->getUrl();?>" target="_blank" style="font-size:xx-small;" title="Events Website"><?php echo $version->getLongVersion();?></a>
			&nbsp;
			<span style="color:#999999; font-size:9px;"><?php echo $version->getShortCopyright();?></span>
		</p>
		<?php
	}
}
