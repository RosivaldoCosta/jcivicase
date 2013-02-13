<?php
/**
* @version		$Id: mod_popular.php 10460 2008-06-27 10:03:09Z eddieajau $
* @package		Joomla
* @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

$civicrm_root_api = JPATH_BASE . DS . 'components/com_civicrm/civicrm';
require_once $civicrm_root_api . '/civicrm.config.php';
require_once $civicrm_root_api . '/api/v2/utils.php';
_civicrm_initialize( );
require_once 'CRM/Case/BAO/Case.php';
require_once 'CRM/Core/Permission.php';
require_once 'CRM/Utils/Recent.php';

$recent = CRM_Utils_Recent::get();
?>

<table class="adminlist">
<tr>
	<td class="title">
		<strong><?php echo JText::_( 'Recently Viewed' ); ?></strong>
	</td>
	<td class="title">
		<strong><?php echo JText::_( 'Created' ); ?></strong>
	</td>
	<td class="title">
		<strong><?php echo JText::_( 'Hits' ); ?></strong>
	</td>
</tr>
<?php
foreach ($recent as $row)
{
	//$link = 'index.php?option=com_content&amp;task=edit&amp;id='. $row->id;
	$link = $row['url'];
	?>
	<tr>
		<td>
		<a href="<?php echo $link; ?>"><?php echo $row['image_url']; ?></a>	<a href="<?php echo $link; ?>">
				<?php echo htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8');?></a>
		</td>
		<td>
			<!--<?php echo JHTML::_('date', $row->created, '%Y-%m-%d %H:%M:%S'); ?>-->
		</td>
		<td>
			<!--<?php echo $row->hits;?>-->
		</td>
	</tr>
	<?php
}
?>
</table>
