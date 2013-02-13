<?php defined('_JEXEC') or die('Restricted access'); 

JHTML::_('behavior.tooltip');
JEVHelper::loadOverlib();

// get configuration object
require(JPATH_ADMINISTRATOR . "/components/".JEV_COM_COMPONENT."/libraries/xconfig.php");
$cfg = & JEVXConfig::getInstance();

$version = JEventsVersion::getInstance();

$pathCompAdminRef = JURI::Root() . "administrator/components/".JEV_COM_COMPONENT."/";
$pathCompAdminAbs = JPATH_ADMINISTRATOR . "/components/".JEV_COM_COMPONENT."/";

jimport('joomla.html.pane');
$tabs = & JPane::getInstance('tabs');

$configfile 	= JPATH_ADMINISTRATOR. '/components/' . JEV_COM_COMPONENT . '/events_config.ini.php';
//$cssfile 		= JPATH_BASE . '/components/' . JEV_COM_COMPONENT . '/events_css.css';
$writeConfig	= true;
//$writeCss		= true;

if( !is_writable( $configfile )){
	$writeConfig = false;
}
/*
if( !is_writable( $cssfile )){
$writeCss = false;
}
*/
// define some lists ( should be done inside the admin.php [mic])
$lists = array();
?>

<table border="0" cellpadding="0" cellspacing="0" width="95%">
	<tr>
		<td>
			&nbsp;
		</td>
		<td align="left" valign="bottom">
			<span style="font-size:150%;"><?php
			echo JText::_('JEV_EVENTS_CONFIG');
			?></span>&nbsp;[&nbsp;<?php
			echo $version->getShortVersion();
			?>&nbsp;<a href='<?php echo $version->getURL();?>'><?php
			echo JText::_('JEV_CHECK_VERSION');
			?> </a>]
		</td>
		<td align="right" valign="bottom">
			[&nbsp;
			<?php
			if( $writeConfig ){
				echo '<small style="color:green;">' . JText::_('JEV_CONFIG_WRITEABLE') ;
			}else{
				echo '<small style="color:red;">' . JText::_('JEV_CONFIG_NOT_WRITEABLE');
			}
			echo ', Modified: ' . strftime('%Y-%m-%d %H:%M:%S %Z', filemtime($configfile));
			echo '</small>&nbsp;]';
			?>
		</td>
	</tr>
</table>

<form action="index.php" method="post" name="adminForm">
    <?php
    // Include default config javascript
    $this->_defaultConfig(); ?>
    <input type="hidden" name="task" value="saveconfig" />
    <input type="hidden" name="option" value="<?php echo JEV_COM_COMPONENT;?>" />
    <input type="hidden" name="conf_componentname" value="<?php echo $cfg->get('com_componentname','com_jevents');?>" />

    <table cellspacing="0" cellpadding="4" border="0" width="100%">
        <tr>
            <td>
                <?php
                echo $tabs->startPane( 'configs' );
                echo $tabs->startPanel( JText::_('JEV_TAB_COMPONENT')." 1", 'config_com_admin' ); ?>
            	<table width="100%" border="0" cellpadding="2" cellspacing="0" class="adminForm">
					<colgroup>
						<col width="265">
					</colgroup>
		        	<tr>
		        		<td colspan="2" style="color:#993300; font-weight:bold; font-size:8pt;">
		        			<?php echo JText::_('JEV_SETT_FOR_COM'); ?>
		        		</td>
		        	</tr>
                    <tr>
                    	<td><?php echo JText::_('JEV_DATE_FORMAT'); ?></td>
                    	<td>
                    		<?php
                    		$datef[] = JHTML::_('select.option', '0', JText::_('JEV_DATE_FORMAT_FR_EN') );
                    		$datef[] = JHTML::_('select.option', '1', JText::_('JEV_DATE_FORMAT_US') );
                    		$datef[] = JHTML::_('select.option', '2', JText::_('JEV_DATE_FORMAT_GERMAN') );
                    		$tosend = JHTML::_('select.genericlist', $datef, 'conf_dateformat', '', 'value', 'text', $cfg->get('com_dateformat'));
                            echo $tosend; ?>
                        </td>
                    </tr>
                    <tr>
                    	<td><?php echo JText::_('JEV_TIME_FORMAT_12'); ?></td>
                    	<td>
                    		<?php
                    		$lists['stdTime'] = JHTML::_('select.booleanlist', 'conf_calUseStdTime', '', $cfg->get('com_calUseStdTime'));
                            echo $lists['stdTime']; ?>
                        </td>
                    </tr>
                    <tr>
                    	<td><?php echo $this->tip(JText::_('JEV_TIP_FE_SIMPLE_FORM'), JText::_('JEV_FE_SIMPLE_FORM')); ?></td>
                        <td>
                            <?php
                            $lists['formOpt'] = JHTML::_('select.booleanlist', 'conf_calSimpleEventForm', '', $cfg->get('com_calSimpleEventForm'));
                            echo $lists['formOpt']; ?>
                        </td>
                    </tr>
                    <tr>
                    	<td><?php echo JText::_('JEV_DEF_EVENT_COLOR'); ?></td>
                    	<td>
                    		<?php
                    		$defColor[] = JHTML::_('select.option', 'random',	JText::_('JEV_DEF_EC_RANDOM') );
                    		$defColor[] = JHTML::_('select.option', 'none',		JText::_('JEV_DEF_EC_NONE') );
                    		$defColor[] = JHTML::_('select.option', 'category',	JText::_('JEV_DEF_EC_CATEGORY') );

                    		//$tosend = JHTML::_('select.genericlist', $defColor, 'conf_defColor', '', 'value', 'text', $conf_defColor );
                    		$lists['defColor'] = JHTML::_('select.radiolist', $defColor, 'conf_defColor', '',  'value', 'text',$cfg->get('com_defColor') );
                            echo $lists['defColor']; ?>
                        </td>
                    </tr>
                    <tr>
                    	<td><?php echo JText::_('JEV_STOP_ROBOTS'); ?></td>
                    	<td>
                    		<?php
                    		$lists['robots'] = JHTML::_('select.booleanlist', 'conf_blockRobots', '', $cfg->get('com_blockRobots',1));
                            echo $lists['robots']; ?>
                        </td>
                    </tr>
                    <tr>
                    	<td><?php echo $this->tip(JText::_('JEV_TIP_DEF_EC_HIDE_FORCE'), JText::_('JEV_DEF_EC_HIDE_FORCE')); ?></td>
                    	<td>
                    		<?php
                    		//$lists['colCatOpt'] = JHTML::_('select.booleanlist', 'conf_calForceCatColorEventForm', '', $cfg->get('com_calForceCatColorEventForm'));
                    		//echo $lists['colCatOpt'];
                    		$evcols = array();
                    		$evcols[] = JHTML::_('select.option', '0',	JText::_('JEV_EVENT_COLS_ALLOWED') );
                    		$evcols[] = JHTML::_('select.option', '1',	JText::_('JEV_EVENT_COLS_BACKED') );
                    		$evcols[] = JHTML::_('select.option', '2',   JText::_('JEV_ALWAYS_CAT_COLOR') );

                    		$tosend = JHTML::_('select.genericlist', $evcols, 'conf_calForceCatColorEventForm', '', 'value', 'text', $cfg->get('com_calForceCatColorEventForm'));
                    		echo $tosend;
							?>
                        </td>
                    </tr>
                    <tr>
                    	<td><?php echo JText::_('JEV_USE_CACHE'); ?></td>
                    	<td>
                    		<?php
                    		$lists['cache'] = JHTML::_('select.booleanlist', 'conf_cache', '', $cfg->get('com_cache'));
                            echo $lists['cache']; ?>
                        </td>
                    </tr>
                    <!--
                    <tr>
                    	<td><?php echo $this->tip("Choose yes if you wish to continue to display old style Events - support may not continue after version 1.5","Support Legacy Events"); ?></td>
                        <td>
                            <?php
                            $legacyOpt = JHTML::_('select.booleanlist', 'conf_legacyEvents', '', $cfg->get('com_legacyEvents',1));
                            echo $legacyOpt; ?>
                        </td>
                    </tr>
                    //-->
					<tr>
						<td>
							<input class="inputbox" type="button" name="default_config" size="20" value="<?php echo JText::_('JEV_BTN_DEF_CONFIG');?>" onclick="defaultConfig_com()"/>&nbsp;
							<?php
							$tip = JText::_('JEV_TIP_BTN_DEF_CONFIG');
		        			echo $this->tip( $tip ); ?>
						</td>
					</tr>
				</table>
                
                <?php
                echo $tabs->endPanel();
                echo $tabs->startPanel( JText::_('JEV_TAB_COMPONENT')." 2", 'config_com_view' );
                ?>
            	<table width="100%" border="0" cellpadding="2" cellspacing="0" class="adminForm">
					<colgroup>
						<col width="265">
					</colgroup>
		        	<tr>
		        		<td colspan="2" style="color:#993300; font-weight:bold; font-size:8pt;">
		        			<?php echo JText::_('JEV_SETT_FOR_COM'); ?>
		        		</td>
		        	</tr>
                    <tr>
                    	<td><?php echo $this->tip(JText::_('JEV_TIP_VIEWNAME'), JText::_('JEV_VIEWNAME')); ?><br/><b>[Load those from directory names]</b></td>
                    	<td>
                    		<?php
                    		$views = array();
							foreach (JEV_CommonFunctions::getJEventsViewList() as $viewfile) {
								$views[] = JHTML::_('select.option', $viewfile, $viewfile);
							}
							sort( $views );

							/*
                    		$views = array();
                    		$views[] = JHTML::_('select.option', 'default',	JText::_('JEV_VIEW_DEFAULT') );
                    		$views[] = JHTML::_('select.option', 'alternative',	JText::_('JEV_VIEW_ALTERNATIVE') );
                    		$views[] = JHTML::_('select.option', 'ext',		JText::_('JEV_VIEW_EXT'));
                    		$views[] = JHTML::_('select.option', 'geraint',	JText::_('JEV_VIEW_GERAINT'));
							*/
                    		$tosend = JHTML::_('select.genericlist', $views, 'conf_calViewName', '', 'value', 'text', $cfg->get('com_calViewName'));
                            echo $tosend; ?>
                        </td>
                    </tr>
					<tr>
						<td><?php echo $this->tip(JText::_('JEV_HEADLINE_TIP'), JText::_('JEV_HEADLINE')); ?></td>
						<td><?php
						$headline	= array();
						$headline[]	= JHTML::_('select.option', 'comp',	JText::_('JEV_HEADLINE_COMP') );
						$headline[]	= JHTML::_('select.option', 'none',	JText::_('JEV_HEADLINE_NONE') );
						$headline[]	= JHTML::_('select.option', 'menu',	JText::_('JEV_HEADLINE_MENU') );

						$tosend = JHTML::_('select.genericlist', $headline, 'conf_calHeadline', 'comp', 'value', 'text', $cfg->get('com_calHeadline'));
						echo $tosend;
							 ?>
						</td>
					</tr>
                    <tr>
                    	<td><?php echo JText::_('JEV_ICONIC_NAVBAR'); ?></td>
                    	<td>
                    		<?php
                    		$lists['iconic'] = JHTML::_('select.booleanlist', 'conf_calUseIconic', '', $cfg->get('com_calUseIconic'));
                            echo $lists['iconic']; ?>
                        </td>
                    </tr>
                    <tr>
                    	<td><?php echo JText::_('JEV_NAV_BAR_COLOR'); ?></td>
                    	<td>
                    		<?php
                    		$navcol[] = JHTML::_('select.option', 'green',	JText::_('JEV_NAV_BAR_GREEN') );
                    		$navcol[] = JHTML::_('select.option', 'orange',	JText::_('JEV_NAV_BAR_ORANGE') );
                    		$navcol[] = JHTML::_('select.option', 'blue', 	JText::_('JEV_NAV_BAR_BLUE') );
                    		$navcol[] = JHTML::_('select.option', 'red', 	JText::_('JEV_NAV_BAR_RED') );
                    		$navcol[] = JHTML::_('select.option', 'gray', 	JText::_('JEV_NAV_BAR_GRAY') );
                    		$navcol[] = JHTML::_('select.option', 'yellow', 	JText::_('JEV_NAV_BAR_YELLOW') );

                    		$tosend = JHTML::_('select.genericlist', $navcol, 'conf_navbarcolor', '', 'value', 'text', $cfg->get('com_navbarcolor'));
                            echo $tosend; ?>
                        </td>
                    </tr>
                    <tr>
						<td><?php echo JText::_('JEV_EARLIEST_YEAR'); ?></td>
						<td>
							<input type="text" name="conf_earliestyear" size="4" maxlength="4" value="<?php echo $cfg->get('com_earliestyear',1995);?>" />
						</td>
					</tr>
                    <tr>
						<td><?php echo JText::_('JEV_LATEST_YEAR'); ?></td>
						<td>
							<input type="text" name="conf_latestyear" size="4" maxlength="4" value="<?php echo $cfg->get('com_latestyear',2015);?>" />
						</td>
					</tr>
                    <tr>
                    	<td><?php echo JText::_('JEV_FIRST_DAY'); ?></td>
                    	<td>
                    		<?php
                    		$first[] = JHTML::_('select.option', '0', JText::_('JEV_SUNDAY_FIRST') );
                    		$first[] = JHTML::_('select.option', '1', JText::_('JEV_MONDAY_FIRST') );
                    		$tosend = JHTML::_('select.genericlist', $first, 'conf_starday', '', 'value', 'text', $cfg->get('com_starday'));
                            echo $tosend; ?>
                        </td>
                    </tr>
					<tr>
						<td><?php echo $this->tip(JText::_('JEV_SHOW_PRINT_ICON_TIP'), JText::_('JEV_SHOW_PRINT_ICON')); ?></td>
						<td>
							<?php
							echo  JHTML::_('select.booleanlist', 'conf_print_icon_view', '', $cfg->get('com_print_icon_view', 1), 'Show', 'Hide');
							?>
						</td>
					</tr>
                    <tr>
                    	<td><?php echo JText::_('JEV_SHOW_CATS'); ?></td>
                    	<td>
                    		<?php
                    		echo  JHTML::_('select.booleanlist', 'conf_hideshowbycats', '', $cfg->get('com_hideshowbycats'));
                            ?>
                        </td>
                    </tr>
						<tr>
						<td><?php echo $this->tip(JText::_('JEV_HIDE_LINKS_TIP'), JText::_('JEV_HIDE_LINKS')); ?></td>
						<td>
						<?php
						echo  JHTML::_('select.booleanlist', 'conf_linkcloaking', '', $cfg->get('com_linkcloaking'));
						?>
						</td>
						</tr>
                    <tr>
                    	<td><?php echo JText::_('JEV_SHOW_COPYRIGHT'); ?></td>
                    	<td>
                    		<?php
                    		echo  JHTML::_('select.booleanlist', 'conf_copyright', '', $cfg->get('com_copyright', 1), 'Show', 'Hide');
                            ?>
                        </td>
                    </tr>
		        	<tr>
		        		<td colspan="2" style="color:#993300; font-weight:bold; font-size:8pt;">
		        			<?php echo "Settings related to Edit Event"; ?>
		        			
		        		</td>
		        	</tr>
		        	<!--
					<tr>
						<td><?php
							echo $this->tip(JText::_('JEV_SHOW_TAB_LEGACY_EVENTS_TIP'), JText::_('JEV_SHOW_TAB_LEGACY_EVENTS'));?>
						</td>
						<td><?php
						$edittabs[] = JHTML::_('select.option', 'extra',	JText::_('JEV_TAB_EXTRA') );
						$edittabs[] = JHTML::_('select.option', 'help',	JText::_('JEV_TAB_HELP') );
						$edittabs[] = JHTML::_('select.option', 'about',	JText::_('JEV_TAB_ABOUT') );
						$tosend = '<input type="checkbox" id="conf_legacy_tab_extra_view" name="conf_legacy_tab_extra_view"'
						. ($cfg->get('com_legacy_tab_extra_view', 1) ? 'checked="checked"' : '')
						. ' value="1" />'
						. '<label for="conf_legacy_tab_extra_view">' . JText::_('JEV_TAB_EXTRA') . '</label>&nbsp;&nbsp;';

						$tosend .= '<input type="checkbox" id="conf_legacy_tab_help_view" name="conf_legacy_tab_help_view"'
						. ($cfg->get('com_legacy_tab_help_view', 1) ? 'checked="checked"' : '')
						. ' value="1" />'
						. '<label for="conf_legacy_tab_help_view">' . JText::_('JEV_TAB_HELP') . '</label>&nbsp;&nbsp;';

						$tosend .= '<input type="checkbox" id="conf_legacy_tab_about_view" name="conf_legacy_tab_about_view"'
						. ($cfg->get('com_legacy_tab_about_view', 1) ? 'checked="checked"' : '')
						. ' value="1" />'
						. '<label for="conf_legacy_tab_about_view">' . JText::_('JEV_TAB_ABOUT') . '</label>&nbsp;&nbsp;';
						echo $tosend;
							?>
						</td>
					</tr>
					//-->
					<tr>
						<td><?php
							echo $this->tip(JText::_('JEV_ONE_TAB_EVENT_TIP'), JText::_('JEV_ONE_TAB_EVENT')); ?>
						</td>
						<td><?php
						echo JHTML::_('select.booleanlist', 'conf_single_pane_edit', '', $cfg->get('com_single_pane_edit', 0));
							?>
						</td>
					</tr>
					<tr>
						<td><?php
							echo $this->tip(JText::_('JEV_SHOW_EDITOR_BUTTONS_TIP'), JText::_('JEV_SHOW_EDITOR_BUTTONS')); ?>
						</td>
						<td><?php
						echo JHTML::_('select.booleanlist', 'conf_show_editor_buttons', '', $cfg->get('com_show_editor_buttons', 1), 'Show', 'Hide');
							?>
						</td>
					</tr>
					<tr>
						<td>&nbsp;<sup>|_</sup>&nbsp;&nbsp;<?php
							echo $this->tip(JText::_('JEV_EDITOR_BUTTON_EXCEPTIONS_TIP'), JText::_('JEV_EDITOR_BUTTON_EXCEPTIONS')); ?>
						</td>
						<td>
							<input type="text" size="30" name="conf_editor_button_exceptions" value="<?php echo $cfg->get('com_editor_button_exceptions', ''); ?>" />
						</td>
					</tr>
		        	<tr>
		        		<td colspan="2" style="color:#993300; font-weight:bold; font-size:8pt;">
		        			<?php echo "Settings related to Event Detail"; ?>
		        		</td>
		        	</tr>
                    <tr>
                    	<td><?php echo JText::_('JEV_VIEW_MAIL'); ?></td>
                    	<td>
                    		<?php
                    		echo  JHTML::_('select.booleanlist', 'conf_mailview', '', $cfg->get('com_mailview'), 'Show', 'Hide');
                            ?>
                        </td>
                    </tr>
                    <tr>
                    	<td><?php echo JText::_('JEV_VIEW_BY'); ?></td>
                    	<td>
							<?php
							echo  JHTML::_('select.booleanlist', 'conf_byview', '', $cfg->get('com_byview'), 'Show', 'Hide');
							?>
                        </td>
                    </tr>
                    <tr>
                    	<td><?php echo JText::_('JEV_VIEW_HITS'); ?></td>
                    	<td>
                    		<?php
                    		echo  JHTML::_('select.booleanlist', 'conf_hitsview', '', $cfg->get('com_hitsview'), 'Show', 'Hide');
                            ?>
                        </td>
                    </tr>
                    <tr>
                    	<td><?php echo JText::_('JEV_VIEW_REPEAT_TIME'); ?></td>
                    	<td>
                    		<?php
                    		echo  JHTML::_('select.booleanlist', 'conf_repeatview', '', $cfg->get('com_repeatview'), 'Show', 'Hide');
							?>
                        </td>
                    </tr>
		        	<tr>
		        		<td colspan="2" style="color:#993300; font-weight:bold; font-size:8pt;">
		        			<?php echo "Settings related to Monthly View"; ?>
		        		</td>
		        	</tr>
                    <tr>
                    	<td><?php echo $this->tip(JText::_('JEV_TIP_CUT_TITLE'), JText::_('JEV_CUT_TITLE')); ?></td>
                    	<td>
                    		<input type="text" size="2" name="conf_calCutTitle" value="<?php echo $cfg->get('com_calCutTitle'); ?>" />
                    	</td>
                    </tr>
                    <tr>
                    	<td><?php echo $this->tip(JText::_('JEV_TIP_MAX_DISPLAY'), JText::_('JEV_MAX_DISPLAY')); ?></td>
                    	<td>
                    		<input type="text" size="2" name="conf_calMaxDisplay" value="<?php echo $cfg->get('com_calMaxDisplay'); ?>" />
                    	</td>
                    </tr>
                    <tr>
                    	<td><?php echo $this->tip(JText::_('JEV_TIP_DIS_STARTTIME'), JText::_('JEV_DIS_STARTTIME')); ?></td>
                	    <td>
                            <?php 
                            $lists['dis_starttime'] = JHTML::_('select.booleanlist',  'conf_calDisplayStarttime', 'class="inputbox"', $cfg->get('com_calDisplayStarttime'), 'Show', 'Hide');
							echo $lists['dis_starttime']; ?>
                    	</td>
                    </tr>
		        	<tr>
		        		<td colspan="2" style="color:#993300; font-weight:bold; font-size:8pt;">
		        			<?php echo "Other Calendar display settings"; ?>
		        		</td>
		        	</tr>
                    <tr>
                    	<td><?php echo JText::_('JEV_VIEW_REPEAT_YEAR_LIST'); ?></td>
                    	<td>
                    		<?php
                    		echo  JHTML::_('select.booleanlist', 'conf_showrepeats', '', $cfg->get('com_showrepeats'), 'Show', 'Hide');
                            ?>
                        </td>
                    </tr>
                    <tr>
                    	<td><?php echo $this->tip(JText::_('JEV_TIP_NR_OF_LIST'), JText::_('JEV_NR_OF_LIST')); ?></td>
                    	<td>
                    		<input type="text" size="3" name="conf_calEventListRowsPpg" value="<?php echo $cfg->get('com_calEventListRowsPpg'); ?>" />
                    	</td>
                    </tr>
					<tr>
						<td>
							<input class="inputbox" type="button" name="default_config" size="20" value="<?php echo JText::_('JEV_BTN_DEF_CONFIG');?>" onclick="defaultConfig_com()"/>&nbsp;
							<?php
							$tip = JText::_('JEV_TIP_BTN_DEF_CONFIG');
		        			echo $this->tip( $tip ); ?>
						</td>
					</tr>
				</table>

                <?php
                echo $tabs->endPanel();
                echo $tabs->startPanel( JText::_('JEV_TAB_RSS'), 'config_rss' );
                ?>
		        <table width="100%" border="0" cellpadding="2" cellspacing="0" class="adminForm">
					<colgroup>
						<col width="265">
					</colgroup>
		        	<tr>
		        		<td colspan="2" style="color:#993300; font-weight:bold; font-size:8pt;">
		        			<?php echo JText::_('JEV_SETT_FOR_RSS'); ?>
		        		</td>
		        	</tr>
		        	<tr>
                    	<td><?php echo JText::_('JEV_RSS_CACHE'); ?></td>
                    	<td>
							<?php
							echo  JHTML::_('select.booleanlist', 'conf_rss_cache', '', $cfg->get('com_rss_cache'));
							?>
                        </td>
                    </tr>
		        	<tr>
		        		<td><?php echo JText::_('JEV_RSS_CTIME'); ?></td>
		        		<td>
		        			<input type=text size=6 name="conf_rss_cache_time" value="<?php echo $cfg->get('com_rss_cache_time'); ?>" />
		        		</td>
		        	</tr>
		        	<tr>
		        		<td><?php echo JText::_('JEV_RSS_LIMIT'); ?></td>
		        		<td>
		        			<input type=text size=3 name="conf_rss_count" value="<?php echo $cfg->get('com_rss_count'); ?>" />
		        		</td>
		        	</tr>
					<tr>
						<td><?php
						echo $this->tip(JText::_('JEV_RSS_LIVE_BOOKMARKS_TIP'), JText::_('JEV_RSS_LIVE_BOOKMARKS'));
						?></td>
						<td><?php
						echo  JHTML::_('select.booleanlist', 'conf_rss_live_bookmarks', '', $cfg->get('com_rss_live_bookmarks', 1));
						?></td>
					</tr>
					<tr>
						<td><?php
						echo $this->tip(JText::_('JEV_RSS_MODID_TIP'), JText::_('JEV_RSS_MODID'));
						?></td>
						<td><?php
						// get list of latest_events modules
						$modules = $this->dataModel->queryModel->getModulesByName();
						$seloptions = array();
						$seloptions[] = JHTML::_('select.option', 0, JTEXT::_('JEV_RSS_MODID_MAIN'));
						for ($i=0;$i<count($modules);$i++) {
							$seloptions[] = JHTML::_('select.option', $modules[$i]->id, $modules[$i]->title );
						}
						echo JHTML::_('select.genericlist', $seloptions, 'conf_rss_modid', '', 'value', 'text', $cfg->get('com_rss_modid', 0));
						?></td>
					</tr>
					<tr>
		        		<td><?php echo JText::_('JEV_RSS_TITLE'); ?></td>
		        		<td>
		        			<input type=text size=80 name="conf_rss_title" value="<?php echo $cfg->get('com_rss_title'); ?>" />
		        		</td>
		        	</tr>
		        	<tr>
		        		<td><?php echo JText::_('JEV_RSS_DESCRIPTION'); ?></td>
		        		<td>
                        	<textarea class="text_area" name="conf_rss_description" id="conf_rss_description" cols="50" rows="4" maxlength="240" wrap="virtual"><?php echo $cfg->get('com_rss_description'); ?></textarea>

		        		</td>
		        	</tr>
		        	<tr>
                    	<td><?php echo JText::_('JEV_RSS_LIMIT_TEXT_LENGTH'); ?></td>
                    	<td>
							<?php
							echo  JHTML::_('select.booleanlist', 'conf_rss_limit_text', '', $cfg->get('com_rss_limit_text'));
							?>
                        </td>
                    </tr>
		        	<tr>
		        		<td><?php echo JText::_('JEV_RSS_TEXT_LIMIT'); ?></td>
		        		<td>
		        			<input type=text size=3 name="conf_rss_text_length" value="<?php echo $cfg->get('com_rss_text_length'); ?>" />
		        		</td>
		        	</tr>
					<tr>
						<td>
							<input class="inputbox" type="button" name="default_config" size="20" value="<?php echo JText::_('JEV_BTN_DEF_CONFIG');?>" onclick="defaultConfig_rss()"/>&nbsp;
							<?php
							$tip = JText::_('JEV_TIP_BTN_DEF_CONFIG');
		        			echo $this->tip( $tip ); ?>
						</td>
					</tr>
		        	
                </table>
             	<?php
             	echo $tabs->endPanel();
             	echo $tabs->startPanel( JText::_('JEV_TAB_CAL_MOD'), 'config_cal_mod' );

             	$lang =& JFactory::getLanguage();
             	$langtag  = $lang->getTag();
             	$langlang = strtok($langtag, '-');
             	if( file_exists( $pathCompAdminAbs . 'help/mod_events_calendar_help_' . $langtag . '.html' )){
             		$jeventHelp = $pathCompAdminRef . 'help/mod_events_calendar_help_' . $langtag . '.html';
             	}else{
             		if( file_exists( $pathCompAdminAbs . 'help/mod_events_calendar_help_' . $langlang . '.html' )){
             			$jeventHelp = $pathCompAdminRef . 'help/mod_events_calendar_help_' . $langlang . '.html';
             		} else {
             			$jeventHelp = $pathCompAdminRef . 'help/mod_events_calendar_help_' . 'en.php';
             		}
				} ?>
		        <table width="100%" border="0" cellpadding="2" cellspacing="0" class="adminForm">
					<colgroup>
						<col width="265">
					</colgroup>
		        	<tr>
		        		<td colspan="2" style="color:#993300; font-weight:bold; font-size:8pt;">
		        			<?php echo JText::_('JEV_SETT_FOR_CAL_MOD') . '&nbsp';
		        			echo $this->help($jeventHelp) . '&nbsp';
		        			if( !file_exists( JPATH_SITE . '/modules/mod_events_cal/mod_events_cal.php' )){
		        				$tip = JText::_('JEV_MSG_MOD_NOT_INSTALLED');
		        				echo $this->jevWarning( $tip );
		        			} ?>
		        		</td>
		        	</tr>
		        	<tr>
		        		<td><?php echo JText::_('JEV_DISPLAY_LAST_MONTH'); ?></td>
		        		<td>
		        			<?php
		        			$dispLmnth[] = JHTML::_('select.option', 'NO', 		JText::_('JEV_NO') );
		        			$dispLmnth[] = JHTML::_('select.option', 'YES_stop', JText::_('JEV_DLM_YES_STOP_DAY') );
		        			$dispLmnth[] = JHTML::_('select.option', 'YES_stop_events', JText::_('JEV_DLM_YES_EVENT_SDAY') );
		        			$dispLmnth[] = JHTML::_('select.option', 'ALWAYS', 	JText::_('JEV_ALWAYS') );
		        			$dispLmnth[] = JHTML::_('select.option', 'ALWAYS_events', JText::_('JEV_DLM_ALWYS_IF_EVENTS') );

		        			$tosend = JHTML::_('select.genericlist', $dispLmnth, 'conf_modCalDispLastMonth', '', 'value', 'text', $cfg->get('modcal_DispLastMonth'));
                            echo $tosend; ?>
                        </td>
                    </tr>
                    <tr>
                    	<td><?php echo $this->tip(JText::_('JEV_TIP_DLM_STOP_DAY'), JText::_('JEV_DLM_STOP_DAY')); ?></td>
                    	<td>
                    		<input type=text size=2 name="conf_modCalDispLastMonthDays" value="<?php echo $cfg->get('modcal_DispLastMonthDays'); ?>" />
                    	</td>
                    </tr>
                    <tr>
                    	<td><?php echo JText::_('JEV_DISPLAY_NEXT_MONTH'); ?></td>
                    	<td>
                    		<?php
                    		$dispNmnth[] = JHTML::_('select.option', 'NO', 		JText::_('JEV_NO') );
                    		$dispNmnth[] = JHTML::_('select.option', 'YES_stop',	JText::_('JEV_DNM_YES_START_DAY') );
                    		$dispNmnth[] = JHTML::_('select.option', 'YES_stop_events', JText::_('JEV_DNM_YES_EVENT_SDAY') );
                    		$dispNmnth[] = JHTML::_('select.option', 'ALWAYS',	JText::_('JEV_ALWAYS') );
                    		$dispNmnth[] = JHTML::_('select.option', 'ALWAYS_events', JText::_('JEV_DNM_ALWYS_IF_EVENTS') );

                    		$tosend = JHTML::_('select.genericlist', $dispNmnth, 'conf_modCalDispNextMonth', '', 'value', 'text', $cfg->get('modcal_DispNextMonth'));
                            echo $tosend; ?>
                        </td>
                    </tr>
                    <tr>
                    	<td><?php echo $this->tip(JText::_('JEV_TIP_DNM_START_DAY'), JText::_('JEV_DNM_START_DAY')); ?></td>
                    	<td>
                    		<input type=text size=2 name="conf_modCalDispNextMonthDays" value="<?php echo $cfg->get('modcal_DispNextMonthDays'); ?>" />
                    	</td>
                    </tr>
					<tr>
						<td><?php echo $this->tip(JText::_('JEV_HIDE_LINKS_TIP'), JText::_('JEV_HIDE_LINKS')); ?></td>
						<td>
							<?php
							echo  JHTML::_('select.booleanlist', 'conf_modCalLinkCloaking', '', $cfg->get('modcal_LinkCloaking', 0));
							?>
						</td>
					</tr>
					<tr>
						<td>
							<input class="inputbox" type="button" name="default_config" size="20" value="<?php echo JText::_('JEV_BTN_DEF_CONFIG');?>" onclick="defaultConfig_cal()"/>&nbsp;
							<?php
							echo $this->tip(JText::_('JEV_TIP_BTN_DEF_CONFIG'));
		        			?>
						</td>
					</tr>

                </table>
             	<?php
             	echo $tabs->endPanel();
             	echo $tabs->startPanel( JText::_('JEV_TAB_LATEST_MOD'), 'config_latest_mod' );

             	$lang =& JFactory::getLanguage();
             	$langtag  = $lang->getTag();
             	if( file_exists( JPATH_COMPONENT_ADMINISTRATOR . '/help/' . $langtag . '/mod_events_latest.php' )){
             		$jeventHelpPopup =  JPATH_COMPONENT_ADMINISTRATOR . '/help/' . $langtag . '/mod_events_latest.php';
             	}
             	else {
             		$jeventHelpPopup =  JPATH_COMPONENT_ADMINISTRATOR . '/help/en-GB/mod_events_latest.php';
             	}
             	include($jeventHelpPopup);
				?>
		        <table width="100%" border="0" cellpadding="2" cellspacing="0" class="adminForm">
					<colgroup>
						<col width="265">
					</colgroup>
		        	<tr>
		        		<td colspan="2" style="color:#993300; font-weight:bold; font-size:8pt;">
		        			<?php echo JText::_('JEV_SETT_FOR_MOD_LATEST') . '&nbsp';
		        			// TODO find where $_cal_lang_lev_main_help is defined???
		        			echo $this->help($_cal_lang_lev_main_help, 'Module') . '&nbsp';
		        			if( !file_exists( JPATH_SITE . '/modules/mod_events_latest/mod_events_latest.php' )){
		        				$tip = JText::_('JEV_MSG_NO_MOD_LATEST');
		        				echo $this->jevWarning( $tip );
		        			} ?>
		        		</td>
		        	</tr>
		        	<tr>
		        		<td><?php echo $this->tip(JText::_('JEV_LEV_MAX_DISPLAY_TIP'), JText::_('JEV_LEV_MAX_DISPLAY')); ?></td>
		        		<td>
							<input type=text size=3 name="conf_modLatestMaxEvents" value="<?php echo $cfg->get('modlatest_MaxEvents'); ?>" />
		        		</td>
		        	</tr>
		        	<tr>
		        		<td><?php echo $this->tip(JText::_('JEV_LEV_DISPLAY_MODE_TIP'), JText::_('JEV_LEV_DISPLAY_MODE')); ?></td>
		        		<td>
		        			<?php
		        			echo JHTML::_('select.integerlist', 0,4,1, 'conf_modLatestMode',  '', $cfg->get('modlatest_Mode')); ?>
		        		</td>
		        	</tr>
		        	<tr>
		        		<td><?php echo $this->tip(JText::_('JEV_LEV_DAY_RANGE_TIP'), JText::_('JEV_LEV_DAY_RANGE')); ?></td>
		        		<td>
		        			<input type=text size=2 name="conf_modLatestDays" value="<?php echo $cfg->get('modlatest_Days'); ?>" />
		        		</td>
		        	</tr>
		        	<tr>
		        		<td><?php echo $this->tip(JText::_('JEV_LEV_REP_EV_ONCE_TIP'), JText::_('JEV_LEV_REP_EV_ONCE')); ?></td>
		        		<td>
		        			<?php
		        			$lists['NoRepeat'] = JHTML::_('select.booleanlist',  'conf_modLatestNoRepeat', '', $cfg->get('modlatest_NoRepeat'));
                            echo $lists['NoRepeat']; ?>
                        </td>
                    </tr>
                    <tr>
                    	<td><?php echo $this->tip(JText::_('JEV_LEV_EV_AS_LINK_TIP'), JText::_('JEV_LEV_EV_AS_LINK')); ?></td>
                    	<td>
                    		<?php
                    		$lists['dispLinks'] = JHTML::_('select.booleanlist', 'conf_modLatestDispLinks', '', $cfg->get('modlatest_DispLinks'));
                            echo $lists['dispLinks']; ?>
                        </td>
                    </tr>
                    <tr>
                    	<td><?php echo $this->tip(JText::_('JEV_LEV_DISPLAY_YEAR_TIP'), JText::_('JEV_LEV_DISPLAY_YEAR')); ?></td>
                    	<td>
                    		<?php
                    		$lists['dispYear'] = JHTML::_('select.booleanlist', 'conf_modLatestDispYear', '', $cfg->get('modlatest_DispYear'));
                            echo $lists['dispYear']; ?>
                        </td>
                    </tr>
                    <tr>
                    	<td><?php echo $this->tip(JText::_('JEV_LEV_CSS_DATE_FIELD_TIP'), JText::_('JEV_LEV_CSS_DATE_FIELD')); ?></td>
                        <td>
                            <?php
                            $lists['disDateStyle'] = JHTML::_('select.booleanlist', 'conf_modLatestDisDateStyle', '', $cfg->get('modlatest_DisDateStyle'));
                            echo $lists['disDateStyle']; ?>
                        </td>
                    </tr>
                    <tr>
                    	<td><?php echo $this->tip(JText::_('JEV_LEV_CSS_TITLE_FIELD_TIP'), JText::_('JEV_LEV_CSS_TITLE_FIELD')); ?></td>
                    	<td>
                    		<?php
                    		$lists['disTitleStyle'] = JHTML::_('select.booleanlist', 'conf_modLatestDisTitleStyle', '', $cfg->get('modlatest_DisTitleStyle'));
                            echo $lists['disTitleStyle']; ?>
                        </td>
                    </tr>
					<tr>
						<td><?php echo $this->tip(JText::_('JEV_LEV_LINKCAL_FIELD_TIP'), JText::_('JEV_LEV_LINKCAL_FIELD')); ?></td>
						<td><?php
						$html_options = array(
						JHTML::_('select.option', '0',	JText::_('JEV_LEV_NOLINK') ),
						JHTML::_('select.option', '1',	JText::_('JEV_LEV_FIRSTLINE') ),
						JHTML::_('select.option', '2',   JText::_('JEV_LEV_LASTLINE') )
						);
						$lists['linkToCal'] =JHTML::_('select.radiolist',$html_options,
						'conf_modLatestLinkToCal', '', 'value', 'text', $cfg->get('modlatest_LinkToCal',0));
							echo $lists['linkToCal']; ?>
						</td>
					</tr>
					<tr>
						<td><?php echo $this->tip(JText::_('JEV_LEV_HIDE_LINK_TIP'), JText::_('JEV_LEV_HIDE_LINK')); ?></td>
						<td>
							<?php
							echo  JHTML::_('select.booleanlist', 'conf_modLatestLinkCloaking', '', $cfg->get('modlatest_LinkCloaking', 0));
							?>
						</td>
					</tr>
					<tr>
						<td><?php echo $this->tip(JText::_('JEV_LEV_SORTREVERSE_TIP'), JText::_('JEV_LEV_SORTREVERSE')); ?></td>
						<td>
							<?php
							echo  JHTML::_('select.booleanlist', 'conf_modLatestSortReverse', '', $cfg->get('modlatest_SortReverse', 0));
							?>
						</td>
					</tr>

                    <tr>
                    	<td><?php echo $this->tip(JText::_('JEV_LEV_CUST_FORM_STRING_TIP'), JText::_('JEV_LEV_CUST_FORM_STRING')); ?></td>
                    	<td>
                    		<div style="float:left">
								<textarea class="text_area" name="conf_modLatestCustFmtStr" id="conf_modLatestCustFmtStr" cols="50" rows="4" wrap="virtual"><?php
								echo htmlspecialchars( str_replace('<br />', "\n", $cfg->get('modlatest_CustFmtStr')), ENT_QUOTES );
								?></textarea>
                    		</div>
							<div>
							<?php
							// TODO find where $_cal_lang_lev_custformstr_help is defined
							echo $this->help($_cal_lang_lev_custformstr_help, 'Event fields')
							. JText::_('JEV_LEV_AVAIL_FIELDS') . '<br />'
							// TODO find where $_cal_lang_lev_date_help is defined
							. $this->help($_cal_lang_lev_date_help, 'date()')
							. JText::_('JEV_LEV_FUNC_DATE') . '<br />'
							// TODO find where $_cal_lang_lev_strftime_help is defined
							. $this->help($_cal_lang_lev_strftime_help, 'strftime()')
							. JText::_('JEV_LEV_FUNC_STRFTIME');
							?>
							</div>
                    	</td>
                    </tr>
                    <tr>
                    	<td><?php echo $this->tip(JText::_('JEV_LEV_RSSLINK_TIP'), JText::_('JEV_LEV_RSSLINK_FIELD')); ?></td>
                        <td>
                            <?php
                            $lists['showRSSLink'] = JHTML::_('select.booleanlist', 'conf_modLatestRSS', '', $cfg->get('modlatest_RSS'));
                            echo $lists['showRSSLink']; ?>
                        </td>
                    </tr>
					<tr>
						<td>
							<input class="inputbox" type="button" name="default_config" size="20" value="<?php echo JText::_('JEV_BTN_DEF_CONFIG');?>" onclick="defaultConfig_latest()"/>&nbsp;
							<?php
							echo $this->tip(JText::_('JEV_TIP_BTN_DEF_CONFIG'));
		        			?>
						</td>
					</tr>
                </table>
             	<?php
             	echo $tabs->endPanel();
                echo $tabs->startPanel( JText::_('JEV_TAB_TOOLTIP'), 'tooltip' ); ?>
                <table width="100%" border="0" cellpadding="2" cellspacing="0" class="adminForm">
                	<tr>
                        <td colspan="2" style="color:#993300; font-weight:bold; font-size:8pt;">
                            <?php echo JText::_('JEV_TOOLTIP'); ?>
                        </td>
                    </tr>
		        	<tr>
		        		<td colspan="2" ><?php echo $this->tip(JText::_('JEV_ENABLETOOLTIP_TIP'), JText::_('JEV_ENABLETOOLTIP')); ?>
		        			<?php
		        			$lists['EnableToolTip'] = JHTML::_('select.booleanlist',  'conf_enableToolTip', '', $cfg->get('com_enableToolTip',1));
                            echo $lists['EnableToolTip']; ?>
                        </td>
                    </tr>
                	<tr>
                		<td width="50%">
                			<fieldset>
                				<legend><?php echo JText::_('JEV_TT_MAINWINDOW'); ?></legend>
                				<table>
                                    <tr>
                                        <td width="120"><?php echo $this->tip(JText::_('JEV_TIP_TT_BGROUND'),  JText::_('JEV_TT_BGROUND')); ?></td>
                                        <td>
                                        <?php
                                        $lists['tt_bground'] = JHTML::_('select.booleanlist',  'conf_calTTBackground', 'class="inputbox"', $cfg->get('com_calTTBackground')); ?>
                                            <?php echo $lists['tt_bground']; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><?php echo $this->tip(JText::_('JEV_TIP_TT_POSX'), JText::_('JEV_TT_POSX')); ?></td>
                                        <td>
                                            <?php
                                            $posx[] = JHTML::_('select.option', 'LEFT',      JText::_('JEV_LEFT') );
                                            $posx[] = JHTML::_('select.option', 'CENTER',    JText::_('JEV_CENTER') );
                                            $posx[] = JHTML::_('select.option', 'RIGHT',     JText::_('JEV_RIGHT') );
                                            $lists['tt_posx'] =JHTML::_('select.radiolist', $posx, 'conf_calTTPosX', '', 'value', 'text', $cfg->get('com_calTTPosX') ); ?>
                                            <?php echo $lists['tt_posx']; ?>
                                        </td>
                                    </tr>
                                    <!-- y-position of TT: above, below (NOTE: if above, HEIGHT MUST BE SET!) -->
                                    <tr>
                                        <td><?php echo $this->tip(JText::_('JEV_TIP_TT_POSY'), JText::_('JEV_TT_POSY')); ?></td>
                                        <td>
                                            <?php
                                            $posy[] = JHTML::_('select.option', 'BELOW',     JText::_('JEV_BELOW') );
                                            $posy[] = JHTML::_('select.option', 'ABOVE',     JText::_('JEV_ABOVE') );
                                            $lists['tt_posy'] =JHTML::_('select.radiolist', $posy, 'conf_calTTPosY', '', 'value', 'text' , $cfg->get('com_calTTPosY')); ?>
                                            <?php echo $lists['tt_posy']; ?>
                                        </td>
                                    </tr>
                                </table>
                            </fieldset>
                        </td>
                        <td>
                            <fieldset>
                            	<legend>
                            		<?php echo JText::_('JEV_TT_SHADOW'); ?>
                            		&nbsp;
                                    <?php
                                    echo $this->tip(JText::_('JEV_TIP_TT_SHADOW'));
                                    ?>
                            	</legend>
                                <table>
                                    <tr>
                                        <td width="120"><?php echo JText::_('JEV_TT_SHADOW'); ?></td>
                                        <td>
                                            <?php
                                            $lists['tt_shadow'] = JHTML::_('select.booleanlist',  'conf_calTTShadow', 'class="inputbox"', $cfg->get('com_calTTShadow') ); ?>
                                            <?php echo $lists['tt_shadow']; ?>
                                        </td>
                                    </tr>
                                    <!-- y-position of TT-shadow: BOOL 0=below, 1=above, [ value as standard: -10 = above ] -->
                                    <tr>
                                        <td><?php echo JText::_('JEV_TT_SHADOWX'); ?></td>
                                        <td>
                                            <?php $lists['tt_shadowx'] = JHTML::_('select.booleanlist',  'conf_calTTShadowX', 'class="inputbox"', $cfg->get('com_calTTShadowX') ); ?>
                                            <?php echo $lists['tt_shadowx']; ?>
                                        </td>
                                    </tr>
                                    <!-- y-position of TT-shadow: BOOL 0=below, 1=above, [ value as standard: -10 = above ] -->
                                    <tr>
                                        <td><?php echo JText::_('JEV_TT_SHADOWY'); ?></td>
                                        <td>
                                            <?php $lists['tt_shadowy'] = JHTML::_('select.booleanlist',  'conf_calTTShadowY', 'class="inputbox"', $cfg->get('com_calTTShadowY') ); ?>
                                            <?php echo $lists['tt_shadowy']; ?>
                                        </td>
                                    </tr>
                                </table>
                            </fieldset>
                        </td>
                    </tr>
					<tr>
						<td>
							<input class="inputbox" type="button" name="default_config" size="20" value="<?php echo JText::_('JEV_BTN_DEF_CONFIG');?>" onclick="defaultConfig_tooltip()"/>&nbsp;
							<?php
							echo $this->tip(JText::_('JEV_TIP_BTN_DEF_CONFIG'));
		        			?>
						</td>
					</tr>
                </table>
                <?php
                echo $tabs->endPanel();
		        echo $tabs->startPanel( JText::_('JEV_TAB_ABOUT'), 'about' ); ?>
                <table width="100%" border="0" cellpadding="2" cellspacing="0" class="adminForm">
                    <tr>
                        <td colspan="2">
                            <?php
                            $lang =& JFactory::getLanguage();
	                     	$langtag  = $lang->getTag();
                            $pathLang =  JPATH_COMPONENT_ADMINISTRATOR . '/help/' . $langtag . '/README.php';
                            if( file_exists( $pathLang . $langtag  )){
                            	include_once( $pathLang . $langtag );
                            }
                            else {
                            	$pathLang =  JPATH_COMPONENT_ADMINISTRATOR . '/help/en-GB/README.php';
                            	include_once( $pathLang );
                            	
							} ?>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</form>
