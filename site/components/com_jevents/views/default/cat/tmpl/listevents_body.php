<?php 
defined('_JEXEC') or die('Restricted access');

$cfg	 = & JEVConfig::getInstance();

$data = $this->datamodel->getCatData( $this->catids,false, $this->limit, $this->limitstart);

$Itemid = JEVHelper::getItemid();

echo '<fieldset><legend class="ev_fieldset">' . $data['catname'] . '</legend><br />' . "\n";
echo '<table align="center" width="90%" cellspacing="0" cellpadding="5" class="ev_table">' . "\n";
$num_events = count($data['rows']);
$chdate ="";
if( $num_events > 0 ){
	echo "<tr>\n";

	for( $r = 0; $r < $num_events; $r++ ){
		$row = $data['rows'][$r];

		$event_day_month_year 	= $row->dup() . $row->mup() . $row->yup();

		if(( $event_day_month_year <> $chdate ) && $chdate <> '' ){
			echo '</ul></td></tr>' . "\n";
		}

		if( $event_day_month_year <> $chdate ){
			$date =JEventsHTML::getDateFormat( $row->yup(), $row->mup(), $row->dup(), 1 );
			echo '<tr><td class="ev_td_left">'.$date.'</td>' . "\n";
			echo '<td align="left" valign="top" class="ev_td_right"><ul class="ev_ul">' . "\n";
		}

		$listyle = 'style="border-color:'.$row->bgcolor().';"';
		echo "<li class='ev_td_li' $listyle>\n";
		$this->viewEventRowNew ( $row,'view_detail',JEV_COM_COMPONENT, $Itemid);
		echo "</li>\n";

		$chdate = $event_day_month_year;
	}
	echo "</ul></td>\n";
} else {
	echo '<tr>';
	echo '<td align="left" valign="top" class="ev_td_right">' . "\n";

	if( count($this->catids)==0 ){
		echo JText::_('JEV_EVENT_CHOOSE_CATEG') . '</td>';
	} else {
		echo JText::_('JEV_NO_EVENTFOR') . '&nbsp;<b>' . $data['catname']. '</b></td>';
	}
}

echo '</tr></table><br />' . "\n";
echo '</fieldset><br /><br />' . "\n";

// Create the pagination object
if ($data["total"]>$data["limit"]){
	$this->paginationForm($data["total"], $data["limitstart"], $data["limit"]);
}
