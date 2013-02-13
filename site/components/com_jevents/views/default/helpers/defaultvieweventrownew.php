<?php 
defined('_JEXEC') or die('Restricted access');

function DefaultViewEventRowNew($view,$row,$args="") {

	$cfg = & JEVConfig::getInstance();

	$eventlink = $row->viewDetailLink($row->yup(),$row->mup(),$row->dup(),false);
	$eventlink = JRoute::_($eventlink.$view->datamodel->getItemidLink().$view->datamodel->getCatidsOutLink());

	// I choost not to use $row->fgcolor
	$fgcolor="inherit";
	// [mic] if title is too long, cut 'em for display
	$tmpTitle = $row->title();
	
	if( JString::strlen( $row->title() ) >= 50 ){
		$tmpTitle = JString::substr( $row->title(), 0, 50 ) . ' ...';
	}

	if (strpos(JRequest::getString("jevtask"),"day")===0 && ($row->starttime() != $row->endtime()) && !($row->alldayevent())){
		$times = $row->starttime(). '&nbsp;-&nbsp;' . $row->endtime() . '&nbsp;';
	} else {
		$times = '';
	}
	echo $times;
		?>
			<a class="ev_link_row" href="<?php echo $eventlink; ?>" <?php echo $args;?> style="font-weight:bold;color:<?php echo $fgcolor;?>;" title="<?php echo JEventsHTML::special($row->title()) ;?>"><?php echo $tmpTitle ;?></a>
			<?php
			if( $cfg->get('com_byview') == '1' ) {
				echo JText::_('JEV_BY') . '&nbsp;<i>'. $row->contactlink() .'</i>';
			}
			?>
		<?php
}