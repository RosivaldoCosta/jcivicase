<?php
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
$civicrm_activity = array();
$db = &JFactory::getDBO();
//error_reporting(E_ALL);
// get module settings
$show_date    = (int) $params->get( 'show_date' );
$show_filter  = (int) $params->get( 'show_filter' );
$dformat      = $params->get( 'dateformat' );
$cf           = (int) JRequest::getVar('ualog_filter_id');
$cfo          = JRequest::getVar('ualog_filter_option');
$f = "";
$user			=& JFactory::getUser();
		// get logged items
		// start filter
		    $query = "SELECT `option` FROM #__ualog GROUP BY `option` ORDER BY `option` ASC";
		    if(!is_array($foptions)) { $foptions = array(); }
/*		    echo "<form action='index.php' method='get' name='ualog_form' id='ualog_form'>";
echo "<select name='ualog_filter_id' onchange='this.form.submit();'>";
echo "<option value='0'>".JText::_('Filter by user')."</option>";
foreach($fusers AS $f)
$ps = "";
if($cf == $f->id) { $ps = ' selected="selected"'; }
echo "<option value='$f->id'$ps>".$f->name." (".$f->username.")</option>";
echo "</select>&nbsp;";

echo "<select name='ualog_filter_option' onchange='this.form.submit();'>";
echo "<option value='0'>".JText::_('Filter by component')."</option>";
foreach($foptions AS $f)
$ps = "";
if($cfo == $f) { $ps = ' selected="selected"'; }
echo "<option value='$f'$ps>".$f."</option>";
echo "</select>";
echo "</form><hr/>";
*/
	}
// CiviCRM activities
<script type="text/javascript" src="components/com_civicrm/civicrm/packages/jquery/jquery.js"></script>
<script type="text/javascript">
var cj = jQuery.noConflict();
cj.ajaxSetup({ cache : false });
cj(document).ready(function(){
var loading = '<img src="components/com_civicrm/civicrm/i/loading.gif" alt="loading" />&nbsp;Loading...';
cj('#civicrm-activity-log').html(loading);
}