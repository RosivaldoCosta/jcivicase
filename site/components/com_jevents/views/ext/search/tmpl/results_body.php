<?php 
defined('_JEXEC') or die('Restricted access');

$useRegX = true;
$data = $this->datamodel->getKeywordData($this->keyword, $this->limit, $this->limitstart, $useRegX);

$Itemid = JEVHelper::getItemid();

$searchisValid =true;

$chdate	= '';
echo '<fieldset><legend class="ev_fieldset">' . JText::_('JEV_SEARCHRESULTS'). '&nbsp;:&nbsp;</legend><br />'	. "\n";
?>
<table align="center" width="90%" cellspacing="0" cellpadding="5" class="ev_table">
   <tr valign="top">
       <td colspan="2"  align="center" class="cal_td_daysnames">
            <?php echo $this->keyword  ;?>
        </td>
    </tr>
	<?php

	if( $data['num_events'] > 0 ){
		echo '<tr>';
		for( $r = 0; $r < $data['num_events']; $r++ ){
			$row = $data['rows'][$r];

			$event_day_month_year 	= $row->dup().$row->mup().$row->yup();

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
			$this->viewEventRowNew ( $row);
			$this->viewEventCatRowNew($row);
			echo "</li>\n";

			$chdate = $event_day_month_year;
		}
		echo "</ul></td>\n";
	} else {
		echo "<tr>";
		echo "<td align='left' valign='top' class='ev_td_right'>\n";
		// new by mic
		if( $searchisValid ){
			echo JText::_('JEV_NO_EVENTFOR') . '&nbsp;<b>' . $this->keyword . '</b>';
		}else{
			echo '<b>' . $this->keyword . '</b>';
			$this->keyword = '';
		}
	}
?>
	</tr>
</table>
<br />
</fieldset>
<br />
<table cellpadding="0" cellspacing="0" border="0" width="100%">
	<tr>
		<td align="center" width="100%">
			<form action="index.php" method="get" style="font-size:1;">
				<input type="hidden" name="option" value="<?php echo JEV_COM_COMPONENT; ?>" />
				<input type="hidden" name="task" value="search.results" />
				<input type="hidden" name="Itemid" value="<?php echo $this->Itemid;?>" />
				<input type="text" name="keyword" size="30" maxlength="50" class="inputbox" value="<?php echo $this->keyword;?>" />
				<br />
				<input class="button" type="submit" name="push" value="<?php echo JText::_('JEV_SEARCH_TITLE'); ?>" />
			</form>
		</td>
	</tr>
</table>

<br />
<?php
// Create the pagination object
if ($data["total"]>$data["limit"]){
	$this->paginationForm($data["total"], $data["limitstart"], $data["limit"]);
}
