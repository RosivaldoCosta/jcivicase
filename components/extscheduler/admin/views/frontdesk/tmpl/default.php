<?php
/*
 ****************************************************************
 Copyright (C) 2008-2010 Soft Ventures, Inc. All rights reserved.
 ****************************************************************
 * @package	Appointment Booking Pro - ABPro
 * @copyright	Copyright (C) 2008-2010 Soft Ventures, Inc. All rights reserved.
 * @license	GNU/GPL, see http://www.gnu.org/licenses/gpl-2.0.html
 *
 * ABPro is distributed WITHOUT ANY WARRANTY, or implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * This header must not be removed. Additional contributions/changes
 * may be added to this header as long as no information is deleted.
 *
 ************************************************************
 The latest version of ABPro is available to subscribers at:
 http://www.appointmentbookingpro.com/
 ************************************************************
*/


// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

	JHTML::_('behavior.tooltip');
	jimport( 'joomla.application.helper' );

	$user =& JFactory::getUser();
/*	if($user->get('usertype') != 'Super Administrator')
	{
*/
?>

<?php 

$document = &JFactory::getDocument();
$script = 'document.getElementById(\'ap-footer\').hide();';
$document->addScriptDeclaration($script);

$loaderCSS = "
#loading-mask {
     position: absolute;
     top: 0;
     left: 0;
     width: 100%;
     height: 100%;
     background: #000000;	
     z-index: 1;
}
#loading {
     position: absolute;
     top: 40%;
     left: 45%;
     z-index: 2;
}
#loading SPAN {
     background: url('http://www.ajaxblender.com/article-sources/images/loader.gif') no-repeat left center;
     padding: 5px 30px;
     display: block;
}";

//$document->addStyleDeclaration($loaderCSS);

$extpath = JURI::base()."components/com_extscheduler/ext3/";
?>
	<link rel="stylesheet" type="text/css" href="<?php echo $extpath; ?>resources/css/ext-all.css" />
<script type="text/javascript">
     document.getElementById('loading-message').innerHTML = 'Loading Core API...';
</script>
    <script type="text/javascript" src="<?php echo $extpath; ?>adapter/ext/ext-base-debug.js"></script>
<script>
     document.getElementById('loading-message').innerHTML = 'Loading ExtJS 3.0 ...';
</script>
    <script type="text/javascript" src="<?php echo $extpath; ?>src/ext-all-debug.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo $extpath; ?>resources/css/calendar.css" />
    <script type="text/javascript" src="<?php echo $extpath; ?>src/Ext.calendar.js"></script>
    <script type="text/javascript" src="<?php echo $extpath; ?>src/templates/DayHeaderTemplate.js"></script>
    <script type="text/javascript" src="<?php echo $extpath; ?>src/templates/DayBodyTemplate.js"></script>
    <script type="text/javascript" src="<?php echo $extpath; ?>src/templates/DayViewTemplate.js"></script>
    <script type="text/javascript" src="<?php echo $extpath; ?>src/templates/BoxLayoutTemplate.js"></script>
    <script type="text/javascript" src="<?php echo $extpath; ?>src/templates/MonthViewTemplate.js"></script>
    <script type="text/javascript" src="<?php echo $extpath; ?>src/dd/CalendarScrollManager.js"></script>
    <script type="text/javascript" src="<?php echo $extpath; ?>src/dd/StatusProxy.js"></script>
    <script type="text/javascript" src="<?php echo $extpath; ?>src/dd/CalendarDD.js"></script>
    <script type="text/javascript" src="<?php echo $extpath; ?>src/dd/DayViewDD.js"></script>
    <script type="text/javascript" src="<?php echo $extpath; ?>src/EventRecord.js"></script>
    <script type="text/javascript" src="<?php echo $extpath; ?>src/views/MonthDayDetailView.js"></script>
    <script type="text/javascript" src="<?php echo $extpath; ?>src/widgets/CalendarPicker.js"></script>
    <script type="text/javascript" src="<?php echo $extpath; ?>src/WeekEventRenderer.js"></script>
    <script type="text/javascript" src="<?php echo $extpath; ?>src/views/CalendarView.js"></script>
    <script type="text/javascript" src="<?php echo $extpath; ?>src/views/MonthView.js"></script>
    <script type="text/javascript" src="<?php echo $extpath; ?>src/views/DayHeaderView.js"></script>
    <script type="text/javascript" src="<?php echo $extpath; ?>src/views/DayBodyView.js"></script>
    <script type="text/javascript" src="<?php echo $extpath; ?>src/views/DayView.js"></script>
    <script type="text/javascript" src="<?php echo $extpath; ?>src/views/WeekView.js"></script>
    <script type="text/javascript" src="<?php echo $extpath; ?>src/widgets/DateRangeField.js"></script>
    <script type="text/javascript" src="<?php echo $extpath; ?>src/widgets/ReminderField.js"></script>
    <script type="text/javascript" src="<?php echo $extpath; ?>src/CalendarPanel.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo $extpath; ?>/resources/css/extensible-all.css" />
    
    <script type="text/javascript" src="<?php echo $extpath; ?>/extensible-all-debug.js"></script>
    <script type="text/javascript" src="index.php?option=com_extscheduler&view=eventeditform&format=json&controller=calendar"></script>
    <script type="text/javascript" src="index.php?option=com_extscheduler&view=eventeditwindow&format=json&controller=calendar"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo $extpath; ?>resources/css/examples.css" />
	<script type="text/javascript" src="<?php echo $extpath; ?>app/calendar-list.js"></script>
    <script type="text/javascript" src="<?php echo $extpath; ?>app/event-list.js"></script>
    <script type="text/javascript" src="index.php?option=com_extscheduler&view=calendar&format=raw&controller=calendar"></script>


<script type="text/javascript">
var myMask = new Ext.LoadMask(Ext.getBody(), {msg:"Please wait..."});
Ext.Ajax.on('beforerequest', myMask.show, myMask);
Ext.Ajax.on('requestcomplete', myMask.hide, myMask);
Ext.Ajax.on('requestexception', myMask.hide, myMask);
</script>


    <div style="display:none;">
    <div id="app-header-content">
        <div id="app-logo">
            <div class="logo-top">&#160;</div>
            <div id="logo-body">&#160;</div>
            <div class="logo-bottom">&#160;</div>
        </div>
        <h1>Ext Calendar Pro</h1>
        <span id="app-msg" class="x-hidden"></span>
    </div>
    </div>
<div id='calendar-ct'></div>

<script type="text/javascript">
		Ext.onReady(function() {
			Ext.fly('ap-footer').hide();
		});

    var updateLogoDt = function(){
        document.getElementById('logo-body').innerHTML = new Date().getDate();
    }
</script>

<div id="loading-mask"></div>
     <div id="loading">
          <span id="loading-message">Loading. Please wait...</span>
     </div>


