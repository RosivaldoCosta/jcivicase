<?php

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

print "<h1>Setting up AdminPraise2</h1>\n";

jimport('joomla.installer.installer');
jimport('joomla.installer.helper');
$installer = new JInstaller();
$installer->_overwrite = true;
$dbo = JFactory::getDBO();

function printMessage($color, $text)
{
	print "<span style=\"color: ".$color."\">".$text."</span><br />\n";
}

function printSucceeded()
{
	printMessage("green", "Succeed");
}

function printFailed()
{
	printMessage("red", "Failed");
}

function saveRow(&$row)
{
	if(!$row->check())
	{
		printFailed();
		return;
	}

	if(!$row->store())
	{
		printFailed();
		return;
	}

	$row->checkin();
	printSucceeded();

	return $row->id;
}

function executeQuery($dbo, $query)
{
	$dbo->setQuery($query);
	$dbo->query();
	$affectedRowsCount = $dbo->getAffectedRows();
	print "<span>".$affectedRowsCount." rows affected.</span><br />\n";
}

function deleteAdminPraiseMenu($dbo)
{
	print "<span>Deleting AdminPraise Menu Items</span><br />\n";
	executeQuery($dbo, "DELETE FROM #__menu WHERE menutype = ".$dbo->quote('adminpraise-menu'));
	executeQuery($dbo, "DELETE FROM #__menu WHERE menutype = ".$dbo->quote('z-adminpraise-menu'));

	print "<span>Deleting AdminPraise Menu Type</span><br />\n";
	executeQuery($dbo, "DELETE FROM #__menu_types WHERE menutype = ".$dbo->quote('adminpraise-menu'));
	executeQuery($dbo, "DELETE FROM #__menu_types WHERE menutype = ".$dbo->quote('z-adminpraise-menu'));
}

function createMenuItem($name, $link, $type, $parentId, $ordering, $access = 0)
{
	print "<span>Creating AdminPraise Menu Item: ".$name."</span><br />\n";
	$menuRow =& JTable::getInstance('menu');
	$menuRow->menutype = 'z-adminpraise-menu';
	$menuRow->name = $name;
	$menuRow->alias = strtolower(str_replace(' ', '-', $name));
	$menuRow->link = $link;
	$menuRow->type = $type;
	$menuRow->published = 1;
	$menuRow->parent = $parentId;
	$menuRow->ordering = $ordering;
	$menuRow->access = $access;
	$menuRow->params = "menu_image=-1\n";
	return saveRow($menuRow);
}

function createAdminPraiseMenu()
{
	print "<span>Creating AdminPraise Menu</span><br />\n";
	$menuTypeRow =& JTable::getInstance('menutypes');
	$menuTypeRow->menutype = 'z-adminpraise-menu';
	$menuTypeRow->title = 'AdminPraise Menu';
	$menuTypeRow->description = 'AdminPraise Menu';
	saveRow($menuTypeRow);

	print "<span>Creating AdminPraise Menu Items</span><br />\n";
	$ordering = 0;
	$siteMenuId = createMenuItem('Site', 'index.php', 'url', 0, $ordering++);
	createMenuItem('Config', 'index.php?option=com_config', 'url', 0, $ordering++, 2);
	$menusMenuId = createMenuItem('Menus', 'index.php?option=com_menus', 'url', 0, $ordering++);
	$sectionsMenuId = createMenuItem('Sections', 'index.php?option=com_sections&scope=content', 'url', 0, $ordering++);
	$categoriesMenuId = createMenuItem('Categories', 'index.php?option=com_categories&scope=content', 'url', 0, $ordering++);
	$articlesMenuId = createMenuItem('Articles', 'index.php?option=com_content', 'url', 0, $ordering++);
	$componentsMenuId = createMenuItem('Components', 'index.php?ap_task=list_components', 'url', 0, $ordering++);
	createMenuItem('Component Quick Launch', 'javascript:apShowQuickLaunch(this, \'apquicklaunch1\');', 'url', 0, $ordering++);
	$modulesMenuId = createMenuItem('Modules', 'index.php?option=com_modules', 'url', 0, $ordering++, 1);
	createMenuItem('Modules Quick Launch', 'javascript:apShowQuickLaunch(this, \'apquicklaunch2\');', 'url', 0, $ordering++, 1);
	createMenuItem('Plugins', 'index.php?option=com_plugins', 'url', 0, $ordering++, 1);
	$templatesMenuId = createMenuItem('Templates', 'index.php?option=com_templates', 'url', 0, $ordering++, 2);
	$installerMenuId = createMenuItem('Installer', 'index.php?option=com_installer', 'url', 0, $ordering++, 1);
	$userMenuId = createMenuItem('User', 'modules/mod_apmenu/dynamic/user.php', 'url', 0, $ordering++, 1);

	// Site
	$ordering = 0;
	createMenuItem('Global Configuration', 'index.php?option=com_config', 'url', $siteMenuId, $ordering++, 2);
	createMenuItem('Global Check-in', 'index.php?option=com_checkin', 'url', $siteMenuId, $ordering++, 1);
	createMenuItem('Separator', '', 'separator', $siteMenuId, $ordering++);
	createMenuItem('Clean Cache', 'index.php?option=com_cache', 'url', $siteMenuId, $ordering++, 1);
	createMenuItem('Purge Expired Cache', 'index.php?option=com_cache&task=purgeadmin', 'url', $siteMenuId, $ordering++, 2);
	createMenuItem('Separator', '', 'separator', $siteMenuId, $ordering++);
	createMenuItem('Media Manager', 'index.php?option=com_media', 'url', $siteMenuId, $ordering++);
	createMenuItem('User Manager', 'modules/mod_apmenu/dynamic/usermanager.php', 'url', $siteMenuId, $ordering++, 1);
	createMenuItem('Separator', '', 'separator', $siteMenuId, $ordering++);
	createMenuItem('System Info', 'index.php?option=com_admin&task=sysinfo', 'url', $siteMenuId, $ordering++);
	createMenuItem('Separator', '', 'separator', $siteMenuId, $ordering++);
	createMenuItem('Logout', 'index.php?option=com_login&task=logout', 'url', $siteMenuId, $ordering++);

	// Menus
	$ordering = 0;
	createmenuItem('Menu Manager', 'index.php?option=com_menus', 'url', $menusMenuId, $ordering++);
	createmenuItem('Menu Trash', 'index.php?option=com_trash&task=viewMenu', 'url', $menusMenuId, $ordering++);
	createmenuItem('Separator', '', 'separator', $menusMenuId, $ordering++);
	createmenuItem('Menus List', 'modules/mod_apmenu/dynamic/menus.php', 'url', $menusMenuId, $ordering++);
	createmenuItem('Separator', '', 'separator', $menusMenuId, $ordering++);
	createmenuItem('New Menu', 'index.php?option=com_menus&task=addMenu', 'url', $menusMenuId, $ordering++);

	// Sections
	$ordering = 0;
	createmenuItem('Section Manager', 'index.php?option=com_sections&scope=content', 'url', $sectionsMenuId, $ordering++);
	createmenuItem('Separator', '', 'separator', $sectionsMenuId, $ordering++);
	createmenuItem('New Section', 'index.php?option=com_sections&scope=content&task=add', 'url', $sectionsMenuId, $ordering++);

	// Categories
	$ordering = 0;
	createmenuItem('Category Manager', 'index.php?option=com_categories&scope=content', 'url', $categoriesMenuId, $ordering++);
	createmenuItem('Separator', '', 'separator', $categoriesMenuId, $ordering++);
	createmenuItem('New Category', 'index.php?option=com_categories&scope=content&task=add', 'url', $categoriesMenuId, $ordering++);

	// Articles
	$ordering = 0;
	createmenuItem('Article Manager', 'index.php?option=com_content', 'url', $articlesMenuId, $ordering++);
	createmenuItem('Article Trash', 'index.php?option=com_trash&task=viewContent', 'url', $articlesMenuId, $ordering++);
	createmenuItem('Separator', '', 'separator', $articlesMenuId, $ordering++);
	createmenuItem('Front Page Manager', 'index.php?option=com_frontpage', 'url', $articlesMenuId, $ordering++);
	createmenuItem('Separator', '', 'separator', $articlesMenuId, $ordering++);
	createmenuItem('New Article', 'index.php?option=com_content&task=add', 'url', $articlesMenuId, $ordering++);

	// Components
	$ordering = 0;
	createmenuItem('Components List', 'modules/mod_apmenu/dynamic/components.php', 'url', $componentsMenuId, $ordering++);

	// Modules
	$ordering = 0;
	createmenuItem('Site Modules', 'index.php?option=com_modules', 'url', $modulesMenuId, $ordering++, 1);
	createmenuItem('Published', 'index.php?option=com_modules&filter_state=P', 'url', $modulesMenuId, $ordering++, 1);
	createmenuItem('New Site Module', 'index.php?option=com_modules&task=add', 'url', $modulesMenuId, $ordering++, 1);
	createmenuItem('Separator', '', 'separator', $modulesMenuId, $ordering++, 1);
	createmenuItem('Admin Modules', 'index.php?option=com_modules&client=1', 'url', $modulesMenuId, $ordering++, 1);
	createmenuItem('Published', 'index.php?option=com_modules&client=1&filter_state=P', 'url', $modulesMenuId, $ordering++, 1);
	createmenuItem('New Admin Module', 'index.php?option=com_modules&client=1&task=add', 'url', $modulesMenuId, $ordering++, 1);

	// Templates
	$ordering = 0;
	createmenuItem('Admin Templates', 'index.php?option=com_templates&client=1', 'url', $templatesMenuId, $ordering++, 2);

	// Installer
	$ordering = 0;
	createmenuItem('Components', 'index.php?option=com_installer&task=manage&type=components', 'url', $installerMenuId, $ordering++, 1);
	createmenuItem('Modules', 'index.php?option=com_installer&task=manage&type=modules', 'url', $installerMenuId, $ordering++, 1);
	createmenuItem('Plugins', 'index.php?option=com_installer&task=manage&type=plugins', 'url', $installerMenuId, $ordering++, 1);
	createmenuItem('Languages', 'index.php?option=com_installer&task=manage&type=languages', 'url', $installerMenuId, $ordering++, 2);
	createmenuItem('Templates', 'index.php?option=com_installer&task=manage&type=templates', 'url', $installerMenuId, $ordering++, 1);
}

function installExtension($installer, $name)
{
	print "<span>Installing extension: ".$name."</span><br />\n";

	$package = JInstallerHelper::unpack(dirname(__FILE__).DS.'packages'.DS.$name);
	if(!$installer->install($package['dir']))
	{
		printFailed();
		return;
	}

	JFolder::delete($package['extractdir']);
	printSucceeded();
}

function enablePlugin($dbo, $name)
{
	print "<span>Enabling plugin: ".$name."</span><br />\n";
	executeQuery($dbo, "UPDATE #__plugins SET published = 1 WHERE element = ".$dbo->quote($name));
}

function deleteModuleInstances($dbo, $position)
{
	print "<span>Deleting module instances in position ".$position."</span><br />\n";
	executeQuery($dbo, "DELETE FROM #__modules WHERE position = ".$dbo->quote($position));
}

function unpublishModuleInstances($dbo, $position)
{
	print "<span>Unpublishing module instances in position ".$position."</span><br />\n";
	executeQuery($dbo, "UPDATE #__modules SET published = 0 WHERE position = ".$dbo->quote($position));
}

function createModuleInstance($name, $title, $showTitle, $content, $position, $ordering, $paramValues = array())
{
	print "<span>Creating instance of module: ".$name." (".$title.") in position ".$position."</span><br />\n";

	// get params
	$params = new JParameter('', JPATH_ADMINISTRATOR.DS.'modules'.DS.$name.DS.$name.".xml");
	$paramsString = "";
	if($params->_xml['_default'])
	{
		foreach ($params->_xml['_default']->children() as $param)  {
			if($param->_attributes['type'] != 'spacer')
			{
				$foundMatch = false;
				$paramValue = "";
				$i = 0;
				for($i = 0; $i < count($paramValues); $i++)
				{
					if(preg_match($paramValues[$i][0], $param->_attributes['name']))
					{
						$paramValue = $paramValues[$i][1];
						$foundMatch = true;
						break;
					}
				}

				if((!$foundMatch || $paramValue == null) && array_key_exists('default', $param->_attributes))
				{
					$paramValue = $param->_attributes['default'];
				}

				$paramsString .= $param->_attributes['name']."=".$paramValue."\n";
			}
		}
	}

	$moduleRow =& JTable::getInstance('module');
	$moduleRow->module = $name;
	$moduleRow->published = 1;
	$moduleRow->client_id = 1;
	$moduleRow->title = $title;
	$moduleRow->showtitle = $showTitle;
	$moduleRow->content = $content;
	$moduleRow->position = $position;
	$moduleRow->ordering = $ordering;
	$moduleRow->params = $paramsString;
	saveRow($moduleRow);
}

deleteAdminPraiseMenu($dbo);
createAdminPraiseMenu();

installExtension($installer, 'plugin_ualog.zip');
installExtension($installer, 'mod_apdock_j1.5_v1.2.zip');
installExtension($installer, 'mod_apicons_j1.5_v1.2.zip');
installExtension($installer, 'mod_apmenu_j1.5_v1.3.zip');
installExtension($installer, 'mod_apquicklaunch_j1.5_v1.1.zip');
installExtension($installer, 'mod_myeditor_j1.5_v1.0.zip');
installExtension($installer, 'mod_myadmintemplate_j1.5_v1.0.zip');
installExtension($installer, 'mod_ualog.zip');
installExtension($installer, 'mod_mytheme_j1.5_v1.1.zip');

enablePlugin($dbo, 'ualog');

deleteModuleInstances($dbo, 'aphead1');
deleteModuleInstances($dbo, 'admintemplate');
deleteModuleInstances($dbo, 'templatetheme');
deleteModuleInstances($dbo, 'editor');
deleteModuleInstances($dbo, 'aphead2');
deleteModuleInstances($dbo, 'apquicklaunch');
deleteModuleInstances($dbo, 'apright1');
deleteModuleInstances($dbo, 'apright2');
deleteModuleInstances($dbo, 'apcpleft');
deleteModuleInstances($dbo, 'apcpright');
deleteModuleInstances($dbo, 'apcpbottom');
deleteModuleInstances($dbo, 'apfooter');
deleteModuleInstances($dbo, 'apdock');

unpublishModuleInstances($dbo, 'cpanel');

createModuleInstance('mod_apmenu', 'AdminPraise Menu', 0, '', 'aphead1', 0);
createModuleInstance('mod_mytheme', 'My Theme', 0, '', 'templatetheme', 0);
createModuleInstance('mod_myeditor', 'My Editor', 0, '', 'editor', 0);
//createModuleInstance('mod_myadmintemplate', 'My Admin Template', 0, '', 'cpanel', 0);
createModuleInstance('mod_apicons', 'AdminPraise Toolbar', 0, '', 'aphead2', 0, array(array('/toolbar\_debug\_adminaccesslevel/', '0'), array('/toolbar\_.*/', null), array('/.*link._name/', ''), array('/.*link._url/', ''), array('/.*/', '0')));
createModuleInstance('mod_apquicklaunch', 'apquicklaunch1', 0, '', 'apquicklaunch', 0);
createModuleInstance('mod_apquicklaunch', 'apquicklaunch2', 0, '', 'apquicklaunch', 0, array(array('/type/', 'modules')));
createModuleInstance('mod_apicons', 'Managers', 0, '', 'apright1', 0, array(array('/management\_.*/', null), array('/.*link._name/', ''), array('/.*link._url/', ''), array('/.*/', '0')));
createModuleInstance('mod_apicons', 'Extend', 0, '', 'apright2', 0, array(array('/extend\_.*/', null), array('/.*link._name/', ''), array('/.*link._url/', ''), array('/.*/', '0')));
createModuleInstance('mod_apicons', 'Installers', 0, '', 'apright2', 1, array(array('/installers\_.*/', null), array('/.*link._name/', ''), array('/.*link._url/', ''), array('/.*/', '0')));
//createModuleInstance('mod_custom', 'Menus', 0, '', 'apright2', 2);
createModuleInstance('mod_apicons', 'Content', 0, '', 'apright2', 3, array(array('/content\_.*/', null), array('/.*link._name/', ''), array('/.*link._url/', ''), array('/.*/', '0')));
createModuleInstance('mod_ualog', 'Recent Activity', 1, '', 'apcpleft', 0);
createModuleInstance('mod_apicons', 'Create New', 1, '', 'apcpright', 0, array(array('/createnew\_.*/', null), array('/.*link._name/', ''), array('/.*link._url/', ''), array('/.*/', '0')));
createModuleInstance('mod_apicons', 'Management', 1, '', 'apcpright', 1, array(array('/management\_.*/', null), array('/.*link._name/', ''), array('/.*link._url/', ''), array('/.*/', '0')));
createModuleInstance('mod_apicons', 'Extend', 1, '', 'apcpright', 2, array(array('/extend\_.*/', null), array('/.*link._name/', ''), array('/.*link._url/', ''), array('/.*/', '0')));
createModuleInstance('mod_custom', 'Help, Documentation, Resources, & Blogs', 1, 
	"<ul class=\"twentyfive left help\">\n".
	"	<li><a href=\"http://help.joomla.org\" target=\"_blank\">Joomla Help</a></li>\n".
	"	<li><a href=\"http://forum.joomla.org\" target=\"_blank\">Joomla Forum</a></li>\n".
	"	<li><a href=\"http://www.joomlatutorials.com\" target=\"_blank\">JoomlaTutorials</a></li>\n".
	"</ul>\n".
	"<ul class=\"twentyfive left doc\">\n".
	"	<li><a href=\"http://docs.joomla.org\" target=\"_blank\">Joomla Documentation</a></li>\n".
	"	<li><a href=\"http://api.joomla.org\" target=\"_blank\">Joomla API</a></li>\n".
	"</ul>\n".
	"<ul class=\"twentyfive left resource\">\n".
	"	<li><a href=\"http://extensions.joomla.org\" target=\"_blank\">Joomla Extensions</a></li>\n".
	"	<li><a href=\"http://www.cmsmarket.com\" target=\"_blank\">CMS Market</a></li>\n".
	"	<li><a href=\"http://start.joomlaworks.gr\" target=\"_blank\">JoomlaWorks Start</a></li>\n".
	"	<li><a href=\"http://resources.howtojoomla.net/\" target=\"_blank\">HTJ! Resources</a></li>\n".
	"</ul>\n".
	"<ul class=\"twentyfive left blog\">\n".
	"<li><a href=\"http://www.alledia.com\" target=\"_blank\">Alledia</a></li>\n".
	"	<li><a href=\"http://www.compassdesigns.net\" target=\"_blank\">Compass Designs</a></li>\n".
	"	<li><a href=\"http://www.howtojoomla.net\" target=\"_blank\">HowToJoomla!</a></li>\n".
	"	<li><a href=\"http://www.goodwebpractices.com\" target=\"_blank\">GoodWebPractices</a></li>\n".
	"</ul>\n",
	'apcpbottom', 0);
createModuleInstance('mod_logged', 'Logged in Users', 0, '', 'apfooter', 0);
createModuleInstance('mod_apdock', 'AdminPraise Dock', 0, '', 'apdock', 0);

?>
