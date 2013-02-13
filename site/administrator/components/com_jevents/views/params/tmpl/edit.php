<?php defined('_JEXEC') or die('Restricted access'); 
$version = JEventsVersion::getInstance();

?>
	<form action="index.php" method="post" name="adminForm" autocomplete="off">

		<fieldset class='jevconfig'>
			<legend>
				<?php echo JText::_( 'JEV_EVENTS_CONFIG' );?>
			</legend>
			[<?php	echo $version->getShortVersion();	?>&nbsp;<a href='<?php echo $version->getURL();?>'><?php	echo JText::_('JEV_CHECK_VERSION');	?> </a>]
			<?php
			$names = array();
			$groups = $this->params->getGroups();
			if (count($groups)>0){
				jimport('joomla.html.pane');
				$tabs = & JPane::getInstance('tabs');
				echo $tabs->startPane( 'configs' );
				$strings=array();
				$tips=array();
				foreach ($groups as $group=>$count) {
					/*
					if ($group == "_default") continue;
					$temp = $this->params->_xml[ $group]->children();
					foreach ($temp as $node) {
						$desc = $node->attributes("label");
						$tip = $node->attributes("description");
						if ($desc!="" && in_array($desc,$strings)) {
							echo "dup desc $desc<br/>";
						}
						$strings[] = $desc;
						if ($tip!="" && in_array($tip,$tips)) {
							echo "dup tip $desc<br/>";
						}
						$tips[] = $tip;
					}
					// data check for old config
					$temp = $this->params->_xml[ $group]->children();
					foreach ($temp as $node) {
						$name= $node->attributes("name");
						if (!array_key_exists($name,$names) && $name!="@spacer") {
							$cfg = & JEVConfig::getInstance();
							$oldvalue = $cfg->get($name,-999);
							if ($oldvalue>-999){						
								if ($oldvalue != $node->attributes('default')){
									echo "name = $name old =$oldvalue current default =".$node->attributes('default')."<br/>";
								}
							}
							$names[$name]=$node;
						}
					}
					*/
					if ($group!="_default" && $count>0){
						echo $tabs->startPanel( JText::_($group), 'config_'.str_replace(" ","_",$group));
						echo $this->params->render('params',$group);
						echo $tabs->endPanel();
					}
				}
				echo $tabs->endPane();
			}
			else {
				echo $this->params->render();
			}
	         ?>
		</fieldset>

		<input type="hidden" name="id" value="<?php echo $this->component->id;?>" />
		<input type="hidden" name="component" value="<?php echo $this->component->option;?>" />

		<input type="hidden" name="controller" value="component" />
		<input type="hidden" name="option" value="<?php echo JEV_COM_COMPONENT;?>" />
		<input type="hidden" name="task" value="" />
		<?php echo JHTML::_( 'form.token' ); ?>
	</form>