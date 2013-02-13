<?php 
defined('_JEXEC') or die('Restricted access');

function DefaultViewEventRowAdmin($view,$row, $manage=false){

	$modifylink = "";
	if (!$manage && JEVHelper::canEditEvent($row)){
		$modifylink = '<a href="' . $row->editlink(true) . '" title="' . JText::_('JEV_MODIFY') . '"><b>' . JText::_('JEV_MODIFY') . "</b></a>\n";
	}

	$deletelink = "";
	if (!$manage && JEVHelper::canDeleteEvent($row)){
		$deletelink = '<a href="' . $row->deletelink(false)."&rettask=admin.listevents" . '" title="'. JText::_('JEV_DELETE') . '"><b>' . JText::_('JEV_DELETE') . "</b></a>\n";
	}

	if (!$manage && JEVHelper::canPublishEvent($row)){
		if ($row->published()){
			$publishlink = '<a href="' . $row->unpublishlink(false)."&rettask=admin.listevents" . '" title="' . JText::_('UNPUBLISH') . '"><b>' . JText::_('UNPUBLISH') . "</b></a>\n";		
		}
		else {
			$publishlink = '<a href="' . $row->publishlink(false)."&rettask=admin.listevents" . '" title="' . JText::_('PUBLISH') . '"><b>' . JText::_('PUBLISH') . "</b></a>\n";		
		}
	}
	else {
		$publishlink = "";		
	}

	$eventlink = $row->viewDetailLink($row->yup(),$row->mup(),$row->dup(),false);
	$eventlink = JRoute::_($eventlink.$view->datamodel->getItemidLink().$view->datamodel->getCatidsOutLink());
	$border="border-color:".$row->bgcolor().";";
		?>
		
		<li class="ev_td_li" style="<?php echo $border;?>">
			<a class="<?php echo $row->state() ? 'ev_link_row' : 'ev_link_unpublished'; ?>" style="font-weight:bold;" href="<?php echo $eventlink; ?>" title="<?php echo JEventsHTML::special($row->title()) . ( $row->state() ? '' : JText::_('JEV_UNPUBLISHED') );?>"><?php echo $row->title() . ( $row->state() ? '' : JText::_('JEV_UNPUBLISHED') );?></a>
			&nbsp;<?php echo JText::_('JEV_BY');?>
			&nbsp;<i><?php echo $row->contactlink('',true);?></i>
			&nbsp;&nbsp;<?php echo $deletelink;?>
			&nbsp;&nbsp;<?php echo $modifylink;?>
			&nbsp;&nbsp;<?php echo $publishlink;?>
		</li>
		<?php
}
