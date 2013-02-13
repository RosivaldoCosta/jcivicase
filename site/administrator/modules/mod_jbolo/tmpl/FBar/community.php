<?php	

global $Itemid;
	$view = JRequest::getVar('view');
	$task = JRequest::getVar('task');


	 function getItemid() 
	 {
		$db =& JFactory::getDBO();
		$Itemid = 0;
    	if ($Itemid < 1)
    	{
        	$db->setQuery("SELECT id FROM #__menu WHERE link LIKE '%index.php?option=com_community%' AND published = 1");
        	$Itemid = $db->loadResult();
        	if ($Itemid < 1) 
        	{
         	   $Itemid = 0;
        	}
    	}
	    return $Itemid;
	  }

	 function getmessageCount() 
	 {
		$me=& JFactory::getUser();
		$db =& JFactory::getDBO();
		$uncount = 0;
    	if ($uncount < 1) 
    	{
        	$db->setQuery("select count('1') as `unread_count` from #__community_msg_recepient where `is_read` = 0 and `to` =".$me->id." and `deleted` = 0");
        	$uncount = $db->loadResult();
        	if ($uncount < 1)
        	{
         	   $Itemid = 0;
        	}
    	}
	    return $uncount;
	 }

	$mt=getItemid();
	$me=& JFactory::getUser();
	$newuncount = getmessageCount();
	$newuncountnumber = getmessageCount();
	if($me->id)
	{
		$newuncount = $newuncount.'&nbsp;'.JText::_('MOD_FB_NEWMSG');
	}
	else
	{
		$newuncount = JText::_('MOD_FB_LOGIN');
	}

	$theme = intval( $params->get( 'theme', 0 ) );
	$disableactivities = intval( $params->get( 'disableactivities', 0 ) );
	$slideroptions = intval( $params->get( 'slideroptions', 2 ) );
	
	$html='';
	if($disableactivities==0)
	{
		if(! class_exists('CFactory'))
	   {
	      require_once( JPATH_BASE . DS . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'core.php');	
	   }

	CFactory::load( 'libraries' , 'activities' );
	CFactory::load( 'helpers' , 'time' );
		$act = new CActivityStream();
	
		$html = $act->getHTML( '', '', null, 10 );	
	}


echo "<script type=text/javascript>var itemid='".$mt."';
var slideroptions='".$slideroptions."'
var currentonlineid=new Array();
var currentonlinenames=new Array();
var currentonlineunames=new Array();</script>";
if($theme==1)
	echo "<script type=text/javascript>var jfb_theme=17;</script>";
else
	echo "<script type=text/javascript>var jfb_theme=0;</script>";

if($namelist==NULL)
{
	$namelist[0]=JText::_('MOD_FB_NOONLINE');
	$mtemp=& JFactory::getUser();
	if(!$mtemp->id)
	{
		$namelist[0]=JText::_('MOD_FB_LOGIN');
	}
}
else
{
	for($i=0;$i<sizeof($userlist);$i++)
		{
			$my=& JFactory::getUser($userlist[$i]);
			$piecesusers = explode(" ", $userlist[$i]);
			if(isset($piecesusers[1]))
			{
				$userlist[$i] = $piecesusers[0].$piecesusers[1];
			}
			echo "<script type=text/javascript>currentonlineid.push('".$my->id."');</script>";
			echo "<script type=text/javascript>currentonlineunames.push('".$userlist[$i]."');</script>";
		}
}

$me=& JFactory::getUser();
global $mainframe;
	if($theme==0)
	{
		$cscript[] = '<![if !(IE 6)]>';
		$cscript[] = '<link href="'.JURI::base().'modules/mod_jbolo/css/jfb_normal.css" type="text/css" rel="stylesheet"/>';
		$cscript[] = '<![endif]>';
		$themename='gradientgrey';
		$imagetype='.gif';
	}
	if($theme==1)
	{
		$cscript[] = '<![if !(IE 6)]>';
		$cscript[] = '<link href="'.JURI::base().'modules/mod_jbolo/css/jfb_black3d.css" type="text/css" rel="stylesheet"/>';
		$cscript[] = '<![endif]>';
		$themename='black3d';
		$imagetype='.png';
	}

$cscript[] = '<script type="text/javascript" src="'.JURI::base().'modules/mod_jbolo/js/fbar.js"></script>';
$cscript[] = '<!--[if IE 6]>';
$cscript[] = '<link href="'.JURI::base().'modules/mod_jbolo/css/jfb_ie6.css" type="text/css" rel="stylesheet"/>';
$cscript[] = '<![endif]-->';		
$mainframe->addCustomHeadTag(implode("\n", $cscript));
?>


<script type="text/javascript">
//Redirect Window for Applications
function redirectapp()
{
	window.location='<?php echo JRoute::_("index.php?option=com_community&view=apps&Itemid=$mt"); ?>';
}
</script>


<!-- Start Activities -->
<div id="jfb_actvty" class="jfb_actvty">
<div class="jfb_actop">
<div class="jfb_actitle"><?php echo JText::_('MOD_FB_ACTIVITIES'); ?></div>
<div class="jfb_clact" onClick="handler('jfb_actvty',0);">X</div>
</div>
<div class="jfb_maintest">
<div class="jfb_mainact"> 
<?php 
$my=& JFactory::getUser();
if($my->id)
{
	echo $html;
}
else
{
	echo JText::_('MOD_FB_LOGIN');
}
 ?>
 </div>
 </div>
</div>


<!--Start Chat Area-->
<div id="jfb_chatbx" class="jfb_chatbx">
<div class="jfb_chtop">
<div class="jfb_chtitle"><?php echo JText::_('MOD_FB_CHAT'); ?></div>
<div class="jfb_clchat" onClick="handler('jfb_chatbx',0);">X</div>
</div>
<?php if($mtemp->id) {?>
<div onclick=showchatdiv(); id="jfb_useroptions">
<span class="jfb_useroptions">
<?php echo JText::_('YOUR_STATUS'); ?>&nbsp;
</span>
<div style='float:left;' id=inside-ch-box-tl>
<?php echo $text_status; ?>
</div>
</div>
	<div id='ch_box_status'>
		<a style="display:block;" onclick='chat_status(1);'><?php echo Jtext::_('AVAILABLE_TEXT'); ?></a>
		<a style="display:block;" onclick='chat_status(2);'><?php echo Jtext::_('AWAY_TEXT'); ?></a>
		<a style="display:block;" onclick='chat_status(0);'><?php echo Jtext::_('INVISIBLE_TEXT'); ?></a>
		<a style="display:block;" onclick='jfb_show_prompt();'><?php echo Jtext::_('CUSTOM_TEXT'); ?></a>
	</div>	
<?php } ?>
<div class="jfb_mainchat">
<?php 
	echo $namelist;
?>
</div>
</div>


<!--Start Bar-->
<div id="jfb_nav_menu_wrapper">
<div class="jfb_wrapper">
<div class="jfb_barleft"></div>
<div class="jfb_nav_menu">
<?php if(!(in_array('JS Applications', $iconsfields))) { ?>
<div id="jfb_appinmenu" class="jfb_app" onClick="redirectapp();">
<!--img src="<?php echo JURI::base(); ?>modules/mod_jbolo/images/applications.gif" style="float:left; padding:4px 0px 0px 10px;" /-->
<?php echo JText::_('MOD_FB_APPLICATIONS'); ?>
</div>
<?php } ?>

<div class="jfb_div_spacer"></div>
<div id = "jfb_div_mod">
<?php if (!(in_array('JS Photos', $iconsfields)) && $my->id!=0 )  { ?>
<div class="jfb_icons">
<span title="<?php echo JText::_('MOD_FB_PHOTOS'); ?>">
<a href="<?php echo JRoute::_('index.php?option=com_community&view=photos&task=myphotos&userid='.$me->id.'&Itemid='.$mt); ?>">
<img src="<?php echo JURI::base().'modules/mod_jbolo/images/'.$themename.'/bar-icons/photos'.$imagetype; ?>" alt="<?php echo JText::_('MOD_FB_PHOTOS'); ?>" /></a></span> 
</div>
<?php } ?>

<?php if (!(in_array('JS Videos', $iconsfields)) && $my->id!=0 )  { ?>
<div class="jfb_icons">
<span title="<?php echo JText::_('MOD_FB_VIDEOS'); ?>">
<a href="<?php echo JRoute::_('index.php?option=com_community&view=videos&task=myvideos&userid='.$me->id.'&Itemid='.$mt); ?>">
<img src="<?php echo JURI::base().'modules/mod_jbolo/images/'.$themename.'/bar-icons/videos'.$imagetype; ?>" /></a></span>
</div>
<?php } ?>

<?php if (!(in_array('JS Groups', $iconsfields)) && $my->id!=0 )  { ?>
<div class="jfb_icons">
<span title="<?php echo JText::_('MOD_FB_GROUPS'); ?>">

<a href="<?php echo JRoute::_('index.php?option=com_community&view=groups&task=mygroups&userid='.$me->id.'&Itemid='.$mt); ?>">
<img src="<?php echo JURI::base().'modules/mod_jbolo/images/'.$themename.'/bar-icons/groups'.$imagetype; ?>" /></a></span>
</div>
<?php } ?>

<?php if (!(in_array('JS Messages', $iconsfields)) && $my->id!=0 )  { ?>
<div class="jfb_icons">
<span title=<?php echo $newuncount; ?>>
<a href="<?php echo JRoute::_('index.php?option=com_community&view=inbox&Itemid='.$mt); ?>">
<?php if($newuncountnumber>0) { ?>
<img src="<?php echo JURI::base().'modules/mod_jbolo/images/'.$themename.'/bar-icons/mailinboxred'.$imagetype; ?>" />
<?php }else{ ?>
<img src="<?php echo JURI::base().'modules/mod_jbolo/images/'.$themename.'/bar-icons/mailinbox'.$imagetype; ?>" />
<?php }?>
</a>
</span>
</div>
<?php } ?>



<?php
//jimport( 'joomla.application.module.helper' );
if(JModuleHelper::getModule('customjbolo'))
	{
	
		$module = &JModuleHelper::getModule('customjbolo');
		$moduleParams = new JParameter($module->params);
		$param = $moduleParams->get('img_path');
		//print_r($param);die('hey!!');

		// code to render the module at the position='junk'
		$document = &JFactory::getDocument();
		$renderer	= $document->loadRenderer('modules');
		$position	= 'jfb_icons';
		$options	= array('style' => 'raw');
		?>
		<div id = "jfb_mod" style="display:none"> 
		<?php echo $renderer->render($position, $options, null);?>
		</div>
<?php } ?>

</div> <!-- #jfb_div_mod closed-->

<?php if($disableactivities==0){?>
<div class="jfb_normal" id="jfb_actactive" onClick="handler('jfb_actvty',1);">
<div id="jfb_acttext">
<?php echo JText::_('MOD_FB_ACTIVITIES'); ?>
</div>
</div>
<?php }?>

<div class="jfb_normal" id="jfb_chatactive" onClick="handler('jfb_chatbx',1);">
<div class="jfb_chattext">
<?php echo JText::_('MOD_FB_CHAT'); ?>  
(<span id="jfb_chatnums">0</span>)
</div>
</div>

<?php if($disableactivities==1){?>
<div  style="width:124px !important; float:right !important; display:block !important;">&nbsp;
</div>
<?php }?>

<a href=javascript:void(0) class='jfb_chatbuttons' id="jfb_previous" onclick="sliderp();">
<img src="<?php echo JURI::base(); ?>modules/mod_jbolo/images/rightarrow.gif" style="padding: 6px;" />
<img id='jfb_imgp' style='display:none; position:absolute; margin-top:-35px;' src="<?php echo JURI::base(); ?>modules/mod_jbolo/images/newnotification.gif" />
</a>


<div id=jfb_stage>
<div id=jfb_myList>
</div>
</div>


<a href=javascript:void(0) class='jfb_chatbuttons' id="jfb_next" onclick="slidern();">
<img src="<?php echo JURI::base(); ?>modules/mod_jbolo/images/leftarrow.gif" style="padding: 6px;" />
<img id='jfb_imgn' style='display:none; position:absolute; margin-top:-35px;' src="<?php echo JURI::base(); ?>modules/mod_jbolo/images/newnotification.gif" />
</a>

<div class="jfb_clear"></div>
</div>
<div class="jfb_barright"></div>
</div>
</div>


<!--[if IE 6]>
<script type="text/javascript">
winW = document.body.offsetWidth-20;
document.getElementById('jfb_nav_menu_wrapper').style.width=winW;
</script>
<![endif]-->
