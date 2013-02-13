<?php
/**
 * @version		$Id: search.php 10752 2008-08-23 01:53:31Z eddieajau $
 * @package		Joomla
 * @subpackage	Search
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and
 * details.
 */

// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.model');

/**
 * Search Component Search Model
 *
 * @package		Joomla
 * @subpackage	Search
 * @since 1.5
 */
class JboloModelJs extends JModel
{

	function getSmileys( ) {

		$functext = "function doSmileys(text) {\n";
	
		jimport('joomla.registry.registry');
		jimport('joomla.filesystem.file');
		$smileysfile = JFile::read(JPATH_COMPONENT . DS . 'smileys.txt');
		$smileys = explode("\n", $smileysfile);
	
		return $smileys;
		
		$i = 0;
		foreach ($smileys as $smiley) {
			if (trim($smiley) == '') { continue; }
	
			$pcs = explode('=', $smiley);
			$pcs[0] = addslashes($pcs[0]);
	
			$img = 'components/com_jbolo/img/smileys/default/' . $pcs[1];
			$imgsrc = "<img src=\"{$img}\" border=\"0\" />";
			$functext .= "\ttext = text.replace('{$pcs[0]}', '{$imgsrc}');\n";
	
		}
	
		$functext .= "\n\treturn text;\n}";

	
		return $functext;	


}

}
