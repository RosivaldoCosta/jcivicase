<?php
/**
* @version		$Id: mod_online.php 14401 2010-01-26 14:10:00Z louis $
* @package		Joomla
* @copyright	Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
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
$html = '<div id="recently-viewed" class="portlet ui-widget ui-widget-content ui-helper-clearfix ui-corner-all">
    <div class="portlet-header ui-widget-header">
    Recently Viewed
    </div>
    <div class="portlet-content">
    <ul>';
foreach ($recent as $v)
{
    $html .= '<li><a href="' . $v['url'] . '">' . $v['image_url'] . '</a><a href="' . $v['url'] . '">' . $v['title'] . '</a></li>';
}
$html .= '</ul>
</div></div>';
print_r($html);



