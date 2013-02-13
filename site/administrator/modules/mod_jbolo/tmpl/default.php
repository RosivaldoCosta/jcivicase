<?php // no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

$mtemp=& JFactory::getUser();

$params->def('persongroup', 0);
$params->def('pagegroup', 	0);
$params->def('groupselect', 	0);

require(JPATH_SITE.DS."components".DS."com_jbolo".DS."config".DS."config.php");
$jbolo = new modJboloHelper();
$currentstatus = $jbolo->getStatus();

if($currentstatus==0)
{
	$text_status= Jtext::_('INVISIBLE_TEXT');
}
elseif($currentstatus==1)
{
	$text_status= Jtext::_('AVAILABLE_TEXT');
}
elseif($currentstatus==2)
{
	$text_status= Jtext::_('AWAY_TEXT');
}
elseif($currentstatus=3)
{
	$text_status= $jbolo->getCustomStatus();
}

$iconsfields = $params->get('iconsfields');
if(!is_array($iconsfields))
{
	$icons=array();
	$icons[0]=$iconsfields;
	$iconsfields=$icons;
}
$showguestbar = intval( $params->get( 'showguestbar', 0 ) );
$modorbar = intval( $params->get( 'modorbar', 1 ) );
if($mtemp->id)
{
	if($modorbar==1)
	{
		$modchatlist= $jbolo->getList($params);
		echo $modchatlist;

		if($chat_config['community']==0)
		{
			include(JPATH_SITE.DS."modules".DS."mod_jbolo".DS."tmpl".DS."GModule".DS."community.php");
		}
		elseif($chat_config['community']==1)
		{
			include(JPATH_SITE.DS."modules".DS."mod_jbolo".DS."tmpl".DS."GModule".DS."cbuilder.php");
		}
		else
		{
			include(JPATH_SITE.DS."modules".DS."mod_jbolo".DS."tmpl".DS."GModule".DS."standalone.php");
		}
	}
}
elseif($modorbar==1)
{
	echo JText::_('MOD_OFFLINE_MSG');
}

if($mtemp->id!=0 || $showguestbar==1)
{
	if($modorbar==0)
	{
		$userlist= $jbolo->getUserNameArray();
		$newuserlist= $jbolo->getUserNameArray();
		$namelist= $jbolo->getNameArray($params);
		
		if($chat_config['community']==0)
		{
			include(JPATH_SITE.DS."modules".DS."mod_jbolo".DS."tmpl".DS."FBar".DS."community.php");
		}
		elseif($chat_config['community']==1)
		{
			include(JPATH_SITE.DS."modules".DS."mod_jbolo".DS."tmpl".DS."FBar".DS."cbuilder.php");
		}
		elseif($chat_config['community']==3)
		{
			include(JPATH_SITE.DS."modules".DS."mod_jbolo".DS."tmpl".DS."FBar".DS."peopletouch.php");
		}
		else
		{
			include(JPATH_SITE.DS."modules".DS."mod_jbolo".DS."tmpl".DS."FBar".DS."standalone.php");
		}
	}

} 
?>

