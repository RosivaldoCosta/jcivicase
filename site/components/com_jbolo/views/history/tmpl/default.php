<?php defined('_JEXEC') or die('Restricted access'); 
?>
<script type="text/javascript">
function changeHistory(tuser)
{
	window.location="index.php?option=com_jbolo&tmpl=component&view=history&tuser="+tuser.value;
}
</script>
<div id="jb_history">
	<div class="chathistorytop">
		<?php	

				if(count($this->userlist)!=0)
				{
					foreach($this->userlist as $user)
			  	{
					
			  		$options[] = JHTML::_('select.option',$user->uid,$user->label,'value','text');
			 		}

				echo JHTML::_('select.genericlist', $options, 'jb_userselect', 'class="inputbox" onchange="changeHistory(this);" size="1"',  'value', 'text',JRequest::getInt("tuser")); 
			}
?>	
	</div>										
	<div class="chathistorycontent">
		<span class="chathistorytitle"><?php echo JText::_('HISTORY_TITLE'); ?></span><hr />
			
			<?php
			$date_temp = false;
				foreach($this->history as $veh) 
				{
				$date = JFactory::getDate($veh->sent);
				if(!$date_temp || $date_temp!=$date->toFormat('%e'))
				{
					$date_temp=	$date->toFormat('%e');
				?>
					<div class="chatboxmessage"><span class="chatboxmessagetime"><?php echo $date->toFormat(JText::_('HIST_DATE_FORM'));?></span></div>
				<?php
				}
				?>
					<div class="chatboxmessage"><span class="chatboxmessagefrom"><?php echo $veh->label; ?>:&nbsp;&nbsp;</span><span class="chatboxmessagecontent"><?php echo $veh->message; ?></span></div>
				<?php
				}	
				?>

	<!-- Display Pagination -->	
	 <div style="text-align:center;">
	 	<div><?php echo $this->pagination->getPagesLinks();  ?></div>
	 	<div><?php echo $this->pagination->getPagesCounter(); ?></div> 
	</div>	
	</div>
</div>
