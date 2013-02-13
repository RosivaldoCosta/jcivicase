<?php	
//$theme = intval( $params->get( 'theme', 0 ) );
$slideroptions = intval( $params->get( 'slideroptions', 2 ) );
$disableactivities = intval( $params->get( 'disableactivities', 0 ) );

$theme = intval( $params->get( 'theme', 0 ) );
$posname = $params->get( 'posttext', 'jbolo_pos1' );

if($theme==1)
	echo "<script type=text/javascript>var jfb_theme=17;</script>";
else
	echo "<script type=text/javascript>var jfb_theme=0;</script>";


echo "<script type=text/javascript>var slideroptions='".$slideroptions."';</script>";
echo "<script type=text/javascript>var currentonlineid=new Array()</script>";
echo "<script type=text/javascript>var currentonlinenames=new Array()</script>";
echo "<script type=text/javascript>var currentonlineunames=new Array()</script>";


function getItemid() 
	 {
		$db =& JFactory::getDBO();
		$Itemid = 0;
    	if ($Itemid < 1)
    	{
        	$db->setQuery("SELECT id FROM #__menu WHERE link LIKE '%index.php?option=com_comprofiler%' AND published = 1");
        	$Itemid = $db->loadResult();
        	if ($Itemid < 1) 
        	{
         	   $Itemid = 0;
        	}
    	}
	    return $Itemid;
	  }

$mt = getItemid();

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


<!-- Start Activities -->
<div id="jfb_actvty" class="jfb_actvty">
<div class="jfb_actop">
<div class="jfb_actitle"><?php echo JText::_('MOD_FB_ACTIVITIES'); ?></div>
<div class="jfb_clact" onClick="handler('jfb_actvty',0);">X</div>
</div>
<div class="jfb_maintest">
<div class="jfb_mainact"> 
<?php 
	$o = new stdClass(); 
	$o->text = '{loadposition '.$posname.'}';
	JPluginHelper::importPlugin('content');
	$dispatcher = & JDispatcher::getInstance();
	$results = $dispatcher->trigger('onPrepareContent', array (&$o, array(), 0));
	echo $o->text;
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

<div style="height:20px; width:10px; float:left;"></div>

<?php if (!(in_array('CB Home', $iconsfields)))  { ?>
<div class="jfb_icons">
<span title="<?php echo JText::_('MOD_FB_CBHOME'); ?>">
<a href="<?php echo JRoute::_('index.php?option=com_comprofiler&Itemid='.$mt); ?>">
<img src="<?php echo JURI::base().'modules/mod_jbolo/images/'.$themename.'/bar-icons/cb16x16'.$imagetype; ?>" /></a></span> 
</div>
<?php } ?>

<?php if (!(in_array('CB UpdateProfile', $iconsfields)))  { ?>
<div class="jfb_icons">
<span title="<?php echo JText::_('MOD_FB_CBPROFILE'); ?>">
<a href="<?php echo JRoute::_('index.php?option=com_comprofiler&task=userDetails&Itemid='.$mt); ?>">
<img src="<?php echo JURI::base().'modules/mod_jbolo/images/'.$themename.'/bar-icons/updateprofile'.$imagetype; ?>" /></a></span>
</div>
<?php } ?>

<?php if (!(in_array('CB UpdateAvatar', $iconsfields)))  { ?>
<div class="jfb_icons">
<span title="<?php echo JText::_('MOD_FB_CBAVATAR'); ?>">
<a href="<?php echo JRoute::_('index.php?option=com_comprofiler&task=userAvatar&Itemid='.$mt); ?>">
<img src="<?php echo JURI::base().'modules/mod_jbolo/images/'.$themename.'/bar-icons/newavatar'.$imagetype; ?>" /></a></span>
</div>
<?php } ?>

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
