<?php 
defined('_JEXEC') or die('Restricted access');

function DefaultEventIcalDialog($view,$row, $mask){

        ?>
        <div id="ical_dialog"  style="position:absolute;right:0px;background-color:#dedede;border:solid 1px #000000;width:200px;padding:10px;visibility:hidden">
        	<div style="width:12px!important;float:right;background-color:#ffffff;;border:solid #000000;border-width:0 0 1px 1px;text-align:center;margin:-10px;">
        		<a href="javascript:void(0)" onclick="closeical()" style="font-weight:bold;text-decoration:none;color:#000000;">x</a>
        	</div>
        	<a href="<?php echo $row->vCalExportLink(false,false);?>" style="text-decoration:none;" title="vCalendar export">
        	<?php
        	echo '<img src="'. JURI::root() . 'components/'.JEV_COM_COMPONENT.'/images/vcal.gif" style="border:0px;margin-right:1em;" alt="vCalendar export"  />';
             ?>
             All Recurrences
             </a><br/>
        	<a href="<?php echo $row->vCalExportLink(false,true);?>" style="text-decoration:none;" title="vCalendar export">
        	<?php
        	echo '<img src="'. JURI::root() . 'components/'.JEV_COM_COMPONENT.'/images/vcal.gif" alt="vCalendar export" style="border:0px;margin-right:1em;"  />';
             ?>
             Single Recurrence
             </a><br/>
        </div>
        <?php
}

