<?php 
defined('_JEXEC') or die('Restricted access');

$cfg	 = & JEVConfig::getInstance();

$data = $this->datamodel->getDayData( $this->year, $this->month, $this->day );

$cfg = & JEVConfig::getInstance();
$Itemid = JEVHelper::getItemid();
$cfg = & JEVConfig::getInstance();

echo '<fieldset><legend class="ev_fieldset">' . JText::_('JEV_EVENTSFORTHE') .'</legend><br />' . "\n";
echo '<table align="center" width="90%" cellspacing="0" cellpadding="5" class="ev_table">' . "\n";
?>
    <tr valign="top">
        <td colspan="2"  align="center" class="cal_td_daysnames">
           <!-- <div class="cal_daysnames"> -->
            <?php echo JEventsHTML::getDateFormat( $this->year, $this->month, $this->day, 0) ;?>
            <!-- </div> -->
        </td>
    </tr>
<?php
// Timeless Events First
if (count($data['hours']['timeless']['events'])>0){
	$start_time = "Timeless";

	echo '<tr><td class="ev_td_left">' . $start_time . '</td>' . "\n";
	echo '<td class="ev_td_right"><ul class="ev_ul">' . "\n";
	foreach ($data['hours']['timeless']['events'] as $row) {
		$listyle = 'style="border-color:'.$row->bgcolor().';"';
		echo "<li class='ev_td_li' $listyle>\n";

		$this->viewEventRowNew ( $row);
		echo '&nbsp;::&nbsp;';
		$this->viewEventCatRowNew($row);
		echo "</li>\n";
	}
	echo "</ul></td></tr>\n";
}

for ($h=0;$h<24;$h++){
	if (count($data['hours'][$h]['events'])>0){
		$start_time = ($cfg->get('com_calUseStdTime')== '1') ? strftime("%I:%M%p",$data['hours'][$h]['hour_start']) : strftime("%H:%M",$data['hours'][$h]['hour_start']);

		echo '<tr><td class="ev_td_left">' . $start_time . '</td>' . "\n";
		echo '<td class="ev_td_right"><ul class="ev_ul">' . "\n";
		foreach ($data['hours'][$h]['events'] as $row) {
			$listyle = 'style="border-color:'.$row->bgcolor().';"';
			echo "<li class='ev_td_li' $listyle>\n";

			$this->viewEventRowNew ( $row);
			echo '&nbsp;::&nbsp;';
			$this->viewEventCatRowNew($row);
			echo "</li>\n";
		}
		echo "</ul></td></tr>\n";
	}
}
echo '</table><br />' . "\n";
echo '</fieldset><br /><br />' . "\n";
//  $this->showNavTableText(10, 10, $num_events, $offset, '');

