<?php
/**
 * @package Social Ads
 * @copyright Copyright (C) 2009 -2010 Techjoomla, Tekdi Web Solutions . All rights reserved.
 * @license GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     http://www.techjoomla.com
 */

	defined('_JEXEC') or die('Restricted access');

$img_path = $params->get('img_path');

echo '<div>';
echo "<div class='jfb_mod_icons'>
		<img id='modImg' src='".JURI::base().$img_path."' />
	</div>";
echo "<div class='jfb_mod_content' >".$params->get('mod_content','')."</div>";
if($params->get('mod_link'))
	echo "<div class='jfb_mod_link'>".$params->get('mod_link')." </div>";
echo '</div>';

?>
