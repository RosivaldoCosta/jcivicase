<?php defined('_JEXEC') or die('Restricted access'); 

global $task,$catid, $mainframe;
$db	=& JFactory::getDBO();
$editor =& JFactory::getEditor();

// clean any existing cache files
$cache =& JFactory::getCache(JEV_COM_COMPONENT);
$cache->clean(JEV_COM_COMPONENT);
$action = $mainframe->isAdmin()?"index.php":JURI::root()."index.php?option=".JEV_COM_COMPONENT."&Itemid=".JEVHelper::getItemid();

// load any custom fields
$dispatcher	=& JDispatcher::getInstance();
$customfields = array();
$res = $dispatcher->trigger( 'onEditCustom' , array(&$this->row,&$customfields));

// I need $year,$month,$day So that I can return to an appropriate date after saving an event (the repetition ids have all changed so I can't go back there!!)
list($year,$month,$day) = JEVHelper::getYMD();
if (!isset($this->ev_id)){
	$this->ev_id = $this->row->id();
}

if ($this->editCopy){
	$this->old_ev_id=$this->ev_id;
	$this->ev_id=0;
	$this->repeatId=0;
	$this->rp_id=0;
	unset($this->row->_uid);
	$this->row->id(0);
}
?>
<div id="jevents" >
<form action="<?php echo $action;?>" method="post" name="adminForm" enctype='multipart/form-data'>
<?php

// get configuration object
$cfg = & JEVConfig::getInstance();
if( $cfg->get('com_calUseStdTime') == 0 ) {
	$clock24=true;
}
else $clock24=false;

JHTML::_('behavior.tooltip');
// This causes a javascript error in MSIE 7 if the scripts haven't loaded when the dom is ready!
//JHTML::_('behavior.calendar');
jimport('joomla.html.pane');
$tabs = & JPane::getInstance('tabs');


// these are needed for front end admin
?>
<input type="hidden" name="jevtype" value="<?php global $jevtype;echo $jevtype;?>" />
<div style='width:500px;'>
<?php
if ($this->editCopy){
	$repeatStyle="";
	echo "<h3>".JText::_("You are editing a copy of an Ical event")."</h3>";
}
else if ($this->repeatId==0) {
	$repeatStyle="";
	echo JText::_("You are editing an Ical event");
}
else {
	$repeatStyle="style='display:none;'";
	?>
	<h3><?php echo JText::_("You are editing an Ical Repeat");?></h3>
	<input type="hidden" name="cid[]" value="<?php echo $this->rp_id;?>" />
	<?php
}
echo "</div>";

if (isset($this->row->_uid)){
?>
<input type="hidden" name="uid" value="<?php echo $this->row->_uid;?>" />
<?php
}

// need rp_id for front end editing cancel to work note that evid is the repeat id for viewing detail ?>
<input type="hidden" name="rp_id" value="<?php echo isset($this->rp_id)?$this->rp_id:-1;?>" /> 
<input type="hidden" name="year" value="<?php echo $year;?>" /> 
<input type="hidden" name="month" value="<?php echo $month;?>" /> 
<input type="hidden" name="day" value="<?php echo $day;?>" /> 

<input type="hidden" name="state" id="state" value="<?php echo $this->row->state();?>" />
<input type="hidden" name="evid" id="evid" value="<?php echo $this->ev_id;?>" />
<input type="hidden" name="valid_dates" id="valid_dates" value="1"  />
<?php
if ($this->editCopy){
	?>
<input type="hidden" name="old_evid" id="old_evid" value="<?php echo $this->old_ev_id;?>" />
	<?php
}
?>
<script type="text/javascript" language="Javascript">

function submitbutton(pressbutton) {
	if (pressbutton.substr(0, 6) == 'cancel' || !(pressbutton == 'icalevent.save' || pressbutton == 'icalrepeat.save')) {
		if (document.adminForm['catid']){
			document.adminForm['catid'].value=0;
		}
		submitform( pressbutton );
		return;
	}
	var form = document.adminForm;
	<?php echo $editor->getContent( 'jevcontent' );  ?>
	// do field validation
	if (form.title.value == "") {
		alert ( "<?php echo html_entity_decode( JText::_('JEV_E_WARNTITLE') ); ?>" );
	}
	else if (form.ics_id.value == "0"){
		alert( "<?php echo html_entity_decode( 'MISSING ICAL SELECTION' ); ?>" );
	}
	else if (form.valid_dates.value =="0"){
		alert( "Invalid dates - please correct" );
	}
	else {
		submitform(pressbutton);
	}
}

</script>
<div class="adminform" align="left">
<?php
echo $tabs->startPane( 'jevent' );
echo $tabs->startPanel( JText::_('JEV_TAB_COMMON'), 'event' );

 ?>
    <?php 
    $native=true;
    if ( $this->row->icsid()>0){
    	$thisCal = $this->dataModel->queryModel->getIcalByIcsid( $this->row->icsid());
    	if (isset($thisCal) && $thisCal->icaltype==0){
  			// note that icaltype = 0 for imported from URL, 1 for imported from file, 2 for created natively
			echo JText::_("JEV IMPORT WARNING");
        	$native=false;
    	}
    	else if(isset($thisCal) && $thisCal->icaltype==1){
  			// note that icaltype = 0 for imported from URL, 1 for imported from file, 2 for created natively
			echo JText::_("JEV IMPORT WARNING 2");
        	$native=false;
    	}
    }
    if ($native && $this->clistChoice){
    	echo '<div style="margin-bottom:20px;">';
		?>
		<script type="text/javascript" language="Javascript">
		function preselectCategory(select){
			var lookup = new Array();
			lookup[0]=0;
			<?php
			foreach ($this->nativeCals as $nc) {
				echo 'lookup['.$nc->ics_id.']='.$nc->catid.';';
			}
			?>
			document.adminForm['catid'].value=lookup[select.value];
		}
		</script>
        <?php
    	echo JText::_("Select Ical (from raw icals)");

    	echo $this->clist;
        echo "</div>\n";
    }
    else {
    	echo $this->clist;
    }
     ?>
    <table cellpadding="5" cellspacing="2" border="0"  class="adminform" id="jevadminform">
		<tr>
        	<td align="left"><?php echo JText::_('JEV_EVENT_TITLE'); ?>:</td>
            <td>
            	<input class="inputbox" type="text" name="title" size="50" maxlength="100" value="<?php echo JEventsHtml::special($this->row->title()); ?>" />
            </td>
            <?php if ($this->setPriority){ ?>
        	<td  align="left"><?php echo JText::_('JEV_EVENT_PRIORITY'); ?>:</td>
            <td >
            	<?php echo $this->priority; ?>
            </td>
            <?php } else { ?>
            <td colspan="2">
            	<input type="hidden" name="priority" value="0" />
            </td>
            <?php } ?>
			</tr>
        <tr>
            <?php
			if ($this->repeatId==0) {
        	?>
        	<td valign="top" align="left"><?php echo JText::_('JEV_EVENT_CATEGORY'); ?></td>
            <td style="width:200px" >
            <?php 
            $catid = $this->row->catid();
            if ($catid==0 && $this->defaultCat>0){
            	$catid = $this->defaultCat;
            }
            echo JEventsHTML::buildCategorySelect($catid, '', null, $this->with_unpublished_cat, true,0,'catid') 
            ?>
            </td>
            <?php
            }
            if (isset($this->glist)) {?>
            <td align="left" class="accesslevel"><?php echo JText::_('JEV_EVENT_ACCESSLEVEL'); ?></td>
            <td class="accesslevel"><?php echo $this->glist; ?></td>
            <?php } 
            else {
           		echo "<td/><td/>\n";
            }
			if ($this->repeatId!=0) {
            	echo "<td/><td/>\n";
            }
		?>
		</tr>
         <tr>
         	<td valign="top" align="left">
            <?php echo JText::_('JEV_EVENT_ACTIVITY'); ?>
            </td>
            <td colspan="3">
            <?php
			if ($cfg->get('com_show_editor_buttons')) {
				$t_buttons = explode(',', $cfg->get('com_editor_button_exceptions'));
			} else {
				// hide all
				$t_buttons = false;
			}
			echo "<div id='jeveditor'>";
            // parameters : areaname, content, hidden field, width, height, rows, cols
            echo $editor->display( 'jevcontent',  JEventsHtml::special($this->row->content()) ,  "100%", 250, '70', '10', $t_buttons) ;
            echo "</div>";
				?>
            </td>
         </tr>
         <tr>
         	<td width="130" align="left"><?php echo JText::_('JEV_EVENT_ADRESSE'); ?></td>
            <td colspan="3">
            <?php
            $res = $dispatcher->trigger( 'onEditLocation' , array(&$this->row));
            if (count($res)==0 || !$res[0]) {
	            ?>
	            <input class="inputbox" type="text" name="location" size="80" maxlength="120" value="<?php echo JEventsHtml::special($this->row->location()); ?>" />
	            <?php
            }
            ?>
            </td>
         </tr>
         <?php
            foreach ($customfields as $key=>$val) {
         ?>
         <tr>
         	<td valign="top"  width="130" align="left"><?php echo $customfields[$key]["label"]; ?></td>
            <td colspan="3"><?php echo $customfields[$key]["input"]; ?></td>
         </tr>
         	<?php
            	
            }
         ?>
         <tr>
            <td align="left"><?php echo JText::_('JEV_EVENT_CONTACT'); ?></td>
            <td colspan="3">
            <input class="inputbox" type="text" name="contact_info" size="80" maxlength="120" value="<?php echo JEventsHtml::special($this->row->contact_info()); ?>" />
            </td>
          </tr>
            <tr>
                <td align="left" valign="top"><?php echo JText::_('JEV_EVENT_EXTRA'); ?></td>
	            <td colspan="3">
                	<textarea class="text_area" name="extra_info" id="extra_info" cols="50" rows="4" wrap="virtual"><?php echo JEventsHtml::special($this->row->extra_info()); ?></textarea>
                </td>
            </tr>
          
    </table>
	<?php
if (!$cfg->get('com_single_pane_edit', 0)) {
	echo $tabs->endPanel();
	echo $tabs->startPanel( JText::_('JEV_TAB_CALENDAR'), 'calendar' );
}

    ?>
   <div style="clear:both;">
    <fieldset class="jev_sed"><legend><?php echo JText::_("Start, End, Duration");?></legend>
    <span>
		<span ><?php echo JText::_('JEV_EVENT_ALLDAY');?></span>
		<span><input type="checkbox" id='allDayEvent' name='allDayEvent' <?php echo $this->row->alldayevent()?"checked='checked'":"";?> onclick="toggleAllDayEvent();" />
		</span>
    </span>
	<span style="margin:20px" class='checkbox12h'>
		<span style="font-weight:bold"><?php echo JText::_("12 Hour");?></span>
		<span><input type="checkbox" id='view12Hour' name='view12Hour' <?php echo !$clock24 ?"checked='checked'":"";?> onclick="toggleView12Hour();" value="1"/>
		</span>
	</span>
    <div>
        <fieldset><legend><?php echo JText::_('JEV_EVENT_STARTDATE'); ?></legend>
        <div style="float:left">
			<?php
			echo JHTML::calendar($this->row->startDate(), 'publish_up', 'publish_up', '%Y-%m-%d',
			array('size'=>'12',
			'maxlength'=>'10',
			'onchange'=>'checkDates(this);fixRepeatDates();'));
			?>
         </div>
         <div style="float:left;margin-left:20px!important;">
            <?php echo JText::_('JEV_EVENT_STARTTIME')."&nbsp;"; ?>
			<span id="start_24h_area" style="display:inline">
            <input class="inputbox" type="text" name="start_time" id="start_time" size="8" <?php echo $this->row->alldayevent()?"disabled":"";?> maxlength="8" value="<?php echo $this->row->startTime();?>" onchange="checkTime(this);"/>
			</span>
			<span id="start_12h_area" style="display:inline">
           	<input class="inputbox" type="text" name="start_12h" id="start_12h" size="8" maxlength="8" <?php echo $this->row->alldayevent()?"disabled":"";?> value="" onchange="check12hTime(this);" />
      		<input type="radio" name="start_ampm" id="startAM" value="none" checked="checked" onclick="toggleAMPM('startAM');" <?php echo $this->row->alldayevent()?"disabled":"";?> /><?php echo JText::_("am");?>
      		<input type="radio" name="start_ampm" id="startPM" value="none" onclick="toggleAMPM('startPM');" <?php echo $this->row->alldayevent()?"disabled":"";?> /><?php echo JText::_("pm");?>
			</span>
         </div>
         </fieldset>
     </div>
    <div>
        <fieldset><legend><?php echo JText::_('JEV_EVENT_ENDDATE'); ?></legend>
        <div style="float:left">
				<?php
				echo JHTML::calendar($this->row->endDate(), 'publish_down', 'publish_down', '%Y-%m-%d',
				array('size'=>'12',
				'maxlength'=>'10',
				'onchange'=>'checkDates(this);'));
			?>
         </div>
         <div style="float:left;margin-left:20px!important">
             <?php echo JText::_('JEV_EVENT_ENDTIME')."&nbsp;"; ?>
			<span id="end_24h_area" style="display:inline">
           	<input class="inputbox" type="text" name="end_time" id="end_time" size="8" maxlength="8" <?php echo ($this->row->alldayevent() || $this->row->noendtime())?"disabled":"";?> value="<?php echo $this->row->endTime();?>" onchange="checkTime(this);" />
			</span>
			<span id="end_12h_area" style="display:inline">
           	<input class="inputbox" type="text" name="end_12h" id="end_12h" size="8" maxlength="8" <?php echo ($this->row->alldayevent() || $this->row->noendtime())?"disabled":"";?> value="" onchange="check12hTime(this);" />
      		<input type="radio" name="end_ampm" id="endAM" value="none" checked="checked" onclick="toggleAMPM('endAM');" <?php echo ($this->row->alldayevent() || $this->row->noendtime())?"disabled":"";?> /><?php echo JText::_("am");?>
      		<input type="radio" name="end_ampm" id="endPM" value="none" onclick="toggleAMPM('endPM');" <?php echo ($this->row->alldayevent() || $this->row->noendtime())?"disabled":"";?> /><?php echo JText::_("pm");?>
			</span>
		    <span style="margin-left:10px">
				<span><input type="checkbox" id='noendtime' name='noendtime' <?php echo $this->row->noendtime()?"checked='checked'":"";?> onclick="toggleNoEndTime();" value="1" />
				<span ><?php echo JText::_('JEV_EVENT_NOENDTIME');?></span>
				</span>
		    </span>
         </div>
         </fieldset>
     </div>
    <div id="jevmultiday" style="display:<?php echo $this->row->endDate()>$this->row->startDate()?"block":"none";?>">
        <fieldset><legend><?php echo JText::_('JEV_EVENT_MULTIDAY'); ?></legend>
            <?php echo JText::_('JEV_EVENT_MULTIDAY_LONG')."&nbsp;"; ?>
      		<input type="radio" name="multiday" value="1" <?php echo $this->row->multiday()?'checked="checked"':'';?> /><?php echo JText::_("JEV_YES");?>
      		<input type="radio" name="multiday" value="0" <?php echo $this->row->multiday()?'':'checked="checked"';?> /><?php echo JText::_("JEV_NO");?>
         </fieldset>
     </div>
     </fieldset>
     </div>
     <div <?php echo $repeatStyle;?>>
	 <!-- REPEAT FREQ -->
     <div style="clear:both;">
		<fieldset><legend><?php echo JText::_('JEV_EVENT_REPEATTYPE'); ?></legend>
        <table border="0" cellspacing="2">
        	<tr>                                	
            <td ><input type="radio" name="freq" id="NONE" value="none" checked="checked" onclick="toggleFreq('NONE');" /><label for='NONE'><?php echo JText::_("no repeat");?></label></td>
            <td ><input type="radio" name="freq" id="DAILY" value="DAILY" onclick="toggleFreq('DAILY');" /><label for='DAILY'><?php echo JText::_("daily");?></label></td>
            <td ><input type="radio" name="freq" id="WEEKLY" value="WEEKLY" onclick="toggleFreq('WEEKLY');" /><label for='WEEKLY'><?php echo JText::_("weekly");?></label></td>
            <td ><input type="radio" name="freq" id="MONTHLY" value="MONTHLY" onclick="toggleFreq('MONTHLY');" /><label for='MONTHLY'><?php echo JText::_("monthly");?></label></td>
            <td ><input type="radio" name="freq" id="YEARLY" value="YEARLY" onclick="toggleFreq('YEARLY');" /><label for='YEARLY'><?php echo JText::_("yearly");?></label></td>
            </tr>
		</table>
        </fieldset>
	</div>			
   <!-- END REPEAT FREQ-->
   <div style="clear:both;display:none" id="interval_div">
   		<div style="float:left">
   		<fieldset><legend><?php echo JText::_("Repeat Interval") ?></legend>
            <input class="inputbox" type="text" name="rinterval" id="rinterval" size="2" maxlength="2" value="<?php echo $this->row->interval();?>" onchange="checkInterval();" /><span id='interval_label' style="margin-left:1em"></span>
   		</fieldset>
   		</div>
   		<div style="float:left;margin-left:20px!important"  id="cu_count" >
   		<fieldset><legend><input type="radio" name="countuntil" value="count" id="cuc" checked="checked" onclick="toggleCountUntil('cu_count');" /><?php echo JText::_("Repeat Count") ?></legend>
            <input class="inputbox" type="text" name="count" id="count" size="2" maxlength="2" value="<?php echo $this->row->count();?>" onchange="checkInterval();" /><span id='count_label' style="margin-left:1em"><?php echo JText::_("repeats");?></span>
   		</fieldset>
   		</div>
   		<div style="float:left;margin-left:20px!important;" id="cu_until">
   		<fieldset style="background-color:#dddddd"><legend><input type="radio" name="countuntil" value="until" id="cuu" onclick="toggleCountUntil('cu_until');" /><?php echo JText::_("Repeat Until"); ?></legend>
			<?php echo JHTML::calendar(strftime("%Y-%m-%d",$this->row->until()), 'until', 'until', '%Y-%m-%d',
										array('size'=>'12','maxlength'=>'10'));?>

   		</fieldset>
   		</div>
   </div>
   <div style="clear:both;">
   <div  style="float:left;display:none;margin-right:1em;" id="byyearday">
   		<fieldset><legend><input type="radio" name="whichby" id="jevbyd" value="byd"  onclick="toggleWhichBy('byyearday');" /><?php echo JText::_("By Year Day"); ?></legend>
   			<?php echo JText::_("Comma separated list");?>
            <input class="inputbox" type="text" name="byyearday" size="20" maxlength="20" value="<?php echo $this->row->byyearday();?>" onchange="checkInterval();" />
   			<br/><?php echo JText::_("Count back year");?><input type="checkbox" name="byd_direction"  onclick="fixRepeatDates();" <?php echo $this->row->getByDirectionChecked("byyearday");?>/>
   		</fieldset>
   </div>
   <div  style="float:left;display:none;margin-right:1em;" id="bymonth">
   		<fieldset><legend><input type="radio" name="whichby"  id="jevbm" value="bm"  onclick="toggleWhichBy('bymonth');" /><?php echo JText::_("By Month"); ?></legend>
   			<?php echo JText::_("Comma separated list");?>
            <input class="inputbox" type="text" name="bymonth" size="30" maxlength="20" value="<?php echo $this->row->bymonth();?>" onchange="checkInterval();" />
        </fieldset>
   </div>
   <div  style="float:left;display:none;margin-right:1em;" id="byweekno">
   		<fieldset><legend><input type="radio" name="whichby"  id="jevbwn" value="bwn"  onclick="toggleWhichBy('byweekno');" /><?php echo JText::_("By Week No"); ?></legend>
   			<?php echo JText::_("Comma separated list");?>
            <input class="inputbox" type="text" name="byweekno" size="20" maxlength="20" value="<?php echo $this->row->byweekno();?>" onchange="checkInterval();" />
   			<br/>Count back from year end<input type="checkbox" name="bwn_direction"  <?php echo $this->row->getByDirectionChecked("byweekno");?> />
        </fieldset>
   </div>
   <div  style="float:left;display:none;margin-right:1em;" id="bymonthday">
   		<fieldset><legend><input type="radio" name="whichby"  id="jevbmd" value="bmd"  onclick="toggleWhichBy('bymonthday');" /><?php echo JText::_("By Month Day"); ?></legend>
   			<?php echo JText::_("Comma separated list");?>
            <input class="inputbox" type="text" name="bymonthday" size="30" maxlength="20" value="<?php echo $this->row->bymonthday();?>" onchange="checkInterval();" />
   			<br/><?php echo JText::_("Count back");?><input type="checkbox" name="bmd_direction"   <?php echo $this->row->getByDirectionChecked("bymonthday");?>/>
        </fieldset>
   </div>
   <div  style="float:left;display:none;margin-right:1em;" id="byday">
   		<fieldset><legend><input type="radio" name="whichby"  id="jevbd" value="bd"  onclick="toggleWhichBy('byday');" /><?php echo JText::_("By Day"); ?></legend>           			
            <?php 
            JEventsHTML::buildWeekDaysCheck( $this->row->getByDay_days(), '' ,"weekdays");
            ?>
            <div id="weekofmonth">
   			<?php
   			JEventsHTML::buildWeeksCheck( $this->row->getByDay_weeks(), "" ,"weeknums");
            ?>
   			<br/><?php echo JText::_("Count back");?><input type="checkbox" name="bd_direction" <?php echo $this->row->getByDirectionChecked("byday");?> />
            </div>
   		</fieldset>
   </div>
   <div  style="float:left;display:none;margin-right:1em;" id="bysetpos">
   		<fieldset><legend><?php echo "NOT YET SUPPORTED" ?></legend>
   		</fieldset>
   </div>
   </div>
   <div style="clear:both;"></div>
</div>
<script type="text/javascript" language="Javascript">
// make the correct frequency visible
function setupRepeats(){
	<?php
	if ($this->row->id()!=0 && $this->row->freq()){
		?>
		var freq = "<?php echo strtoupper($this->row->freq());?>";
		document.getElementById(freq).checked=true;
		toggleFreq(freq, true);
		var by = "<?php
		if ($this->row->byyearday(true)!="") echo "jevbyd";
		else if ($this->row->bymonth(true)!="") echo "jevbm";
		else if ($this->row->bymonthday(true)!="") echo "jevbmd";
		else if ($this->row->byweekno(true)!="") echo "jevbwn";
		else if ($this->row->byday(true)!="") echo "jevbd";
		// default repeat is by day
		else echo "jevbd";
		?>";
		document.getElementById(by).checked=true;
		var by = "<?php
		if ($this->row->byyearday(true)!="") echo "byyearday";
		else if ($this->row->bymonth(true)!="") echo "bymonth";
		else if ($this->row->bymonthday(true)!="") echo "bymonthday";
		else if ($this->row->byweekno(true)!="") echo "byweekno";
		else if ($this->row->byday(true)!="") echo "byday";
		?>";
		toggleWhichBy(by);
		var cu = "cu_<?php
		if ($this->row->rawuntil()!="") echo "until";
		else echo "count";
		?>";
		document.getElementById(cu=="cu_until"?"cuu":"cuc").checked=true;
		toggleCountUntil(cu);
		<?php
	}
	?>
}
//if (window.attachEvent) window.attachEvent("onload",setupRepeats);
//else window.onload=setupRepeats;
//setupRepeats();
window.setTimeout("setupRepeats()", 500);
// move to 12h fields
set12hTime(document.adminForm.start_time);
set12hTime(document.adminForm.end_time);
// toggle unvisible time fields
toggleView12Hour();
</script>
<?php
echo $tabs->endPanel();

// Plugins CAN BE LAYERED IN HERE
global $params;
// append array to extratabs keys content, title, paneid
$extraTabs = array();
$dispatcher->trigger( 'onEventEdit' , array(&$extraTabs,&$this->row,&$params), true );
if (count($extraTabs)>0) {
	foreach ($extraTabs as $extraTab) {
		echo $tabs->startPanel( $extraTab['title'], $extraTab['paneid'] );
		echo  $extraTab['content'];
		echo $tabs->endPanel();
	}
}

echo $tabs->endPane();
?>
</div>
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="task" value="icalevent.edit" />
<input type="hidden" name="option" value="<?php echo JEV_COM_COMPONENT;?>" />
</form>
</div>