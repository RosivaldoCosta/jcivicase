<?php defined('_JEXEC') or die('Restricted access'); ?>
<div id="jevents">
   <?php if (isset($this->warning)){
   	?>
		<dl id="system-message">
		<dt class="notice">Message</dt>
		<dd class="notice fade">
			<ul>
				<li><?php echo $this->warning;?></li>
			</ul>
		</dd>
		</dl>   	
   	<?php
   }
   ?>
   <form action="index.php" method="post" name="adminForm" >
	<table width="90%" border="0" cellpadding="2" cellspacing="2" class="adminform">
	
		<tr>
			<td width="55%" valign="top">
				<div id="cpanel">
				<?php				

				$link = "index.php?option=$option&task=icals.list";
				$this->_quickiconButton( $link, "upload_f2.png", JText::_('JEV_ADMIN_ICAL_SUBSCRIPTIONS') );

				$link = "index.php?option=$option&task=icalevent.list";
				$this->_quickiconButton( $link, "addedit.png", JText::_('JEV_ADMIN_ICAL_EVENTS') );

				$link = "index.php?option=$option&task=categories.list";
				$this->_quickiconButton( $link, "categories.png", JText::_('JEV_INSTAL_CATS') );

				$link = "index.php?option=$option&task=user.list";
				$this->_quickiconButton( $link, "user.png", JText::_('JEV_MANAGE_USERS') );

				echo "<div style='clear:both'><div>";
				
				//$link = "index.php?option=$option&task=config.edit";
				// new version
				$link = "index.php?option=$option&task=params.edit";
				$this->_quickiconButton( $link, "config.png", JText::_('JEV_INSTAL_CONFIG') );

				$link = "index.php?option=$option&task=config.convert";
				$this->_quickiconButton( $link, "dbrestore.png", JText::_('JEV_ADMIN_CONVERT'));
								
				?>
				</div>
			</td>
			<td width="45%" valign="top">
				<div style="width: 100%;">
					<form action="index.php" method="post" name="adminForm">
					<?php

					// Tabs
					jimport('joomla.html.pane');
					$tabs = & JPane::getInstance('tabs');
					//		$tabs = new JPaneTabs(1);
					echo $tabs->startPane( 'statuspane' );

					// Prepare formating for the output of the state
					/*
					echo $tabs->startPanel( JText::_('JEV_ADMIN_STATUS'), 'jevstatus' );
					?>
					<table class="adminlist">
						<tr>
							<th colspan="4">
							<?php echo JText::_('JEV_ADMIN_COMPONENT_STATE');?>
							</th>
						</tr>
						<?php foreach ($panelStates as $key => $state ) { ?>
						<tr class="row0">
							<td><?php echo $panelStates[$key]['desc'] ?></td>
							<td><?php echo $panelStates[$key]['title'] ?></td>
							<td><?php echo $panelStates[$key]['pub'];?></td>
							<td><a href="<?php echo $panelStates[$key]['href'];?>"><img src="<?php echo  JURI::root();?>images/M_images/edit.png" border="0" alt="<?php echo JText::_('JEV_E_EDIT');?>" /></a></td>							
						</tr>
						<?php } ?>
					</table>
					<?php
					echo $tabs->endPanel();
					*/

					echo $tabs->startPanel( JText::_("Status"), 'status' );
					echo $tabs->endPanel();
					
					// Prepare formating for the output of the state
					echo $tabs->startPanel( JText::_("Advanced"), 'jevadvanced' );
					$href1 =  JURI::root()."administrator/index.php?option=$option&task=config.droptables";
					//$href3 =  JURI::root()."administrator/index.php?option=$option&task=convertExtCal";
					?>
					<h2><?php echo JText::_("JEV_ONLY_USE");?></h2>
					<a href="<?php echo $href1;?>"><?php echo JText::_("JEV_DELETE_TABLES");?></a><br/>
					<hr/>
					<?php
					$link = "index.php?option=$option&task=config.dbsetup";
					$this->_quickiconButton( $link, "dbsetup.png", JText::_('JEV_ADMIN_DBSETUP'),'/administrator/components/'.JEV_COM_COMPONENT."/assets/images/");
					?>
					<br style="clear:both"/>
					<!--
					<a href="<?php //echo $href3;?>">Convert ExtCal data to JEvents</a><br/>
					<hr/>
					//-->
					<?php 
					echo $tabs->endPanel();
					
					echo $tabs->endPane();
					?>
					</form>
				</div>
			</td>
		</tr>
  </table>
  <input type="hidden" name="task" value="cpanel" />
  <input type="hidden" name="act" value="" />
  <input type="hidden" name="option" value="<?php echo JEV_COM_COMPONENT; ?>" />
</form>
</div>
