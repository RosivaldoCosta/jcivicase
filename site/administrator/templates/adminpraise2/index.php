<?php
/**

 * @copyright	Copyright (C) 2008 JoomlaPraise. All rights reserved.

 */
// no direct access

defined( '_JEXEC' ) or die( 'Restricted access' );



require_once(dirname(__FILE__).DS.'helper.php');

AdminPraiseHelper::checkLogin();



if(JRequest::getVar('ap_ajax') == '1')

{

	global $mainframe;

	require_once('custom'.DS.'custom.php');

	$mainframe->close();

}



// Get the current JUser object

$user = &JFactory::getUser();

$option = JAdministratorHelper::findOption();

$ap_task_set = (JRequest::getVar('ap_task') != null);

$hideBreadCrumbs = $this->params->get('hideBreadCrumbs', 0);

$hideToolbar = $this->params->get('hideToolbar', 0);

$hideRightMenu = $this->params->get('hideRightMenu', 0);

$hideFooter = $this->params->get('hideFooter', 0);



// set custom template theme for user

if( !is_null( JRequest::getCmd('templateTheme', NULL) ) ) {

    $found = "found:".JRequest::getCmd('templateTheme');

    $user->setParam('templateTheme', JRequest::getCmd('templateTheme'));

    $user->save(true);

}



if($user->getParam('templateTheme')) {

    $this->params->set('templateTheme', $user->getParam('templateTheme'));

}

$cdnUrl = "http://c498950.r50.cf2.rackcdn.com";
$cssUrl = "templates/<?php echo $this->template ?>/css/template.css";

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>" id="minwidth">

<meta http-equiv='X-UA-Compatible' content='IE=7'>	

<head>

<jdoc:include type="head" />

<link rel="stylesheet" href="<?php echo $cdnUrl?>/system.css" type="text/css" />

<link href="<?php echo $cdnUrl; ?>/template.css" rel="stylesheet" type="text/css" />

<!--[if IE 6]>

<link rel="stylesheet" href="<?php echo $cdnUrl; ?>/ie6.css" type="text/css" />

<![endif]-->

<!--[if IE 7]>

<link rel="stylesheet" href="<?php echo $cdnUrl; ?>/ie7.css" type="text/css" />

<![endif]-->

<link href="<?php echo $cdnUrl;?>/apmenu.css" rel="stylesheet" type="text/css" />

<link href="<?php echo $cdnUrl; ?>/<?php echo $this->params->get('templateTheme'); ?>.css" rel="stylesheet" type="text/css" />

<?php

	//JHTML::_('behavior.mootools');

?>

<script src="<?php echo $cdnUrl; ?>/joomla.javascript.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo $cdnUrl; ?>/mootools.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo $cdnUrl; ?>/mootabs1.2.js" type="text/javascript" charset="utf-8"></script>



<script type='text/javascript'>

/*<![CDATA[*/

	var jax_live_site = '<?php echo $mainframe->getCfg('live_site'); ?>';

	var jax_site_type = '1.5';

/*]]>*/

</script>

<?php if(JModuleHelper::isEnabled('menu')) : ?>

<script type="text/javascript" src="<?php echo $cdnUrl; ?>/menu.js"></script>

<script type="text/javascript" src="<?php echo $cdnUrl; ?>/index.js"></script>

<script type="text/javascript" src="<?php echo $cdnUrl; ?>/adminpraise.js"></script>

<?php endif; ?>	

<style type="text/css">

/* Background Color Overrides */

<?php if($this->params->get('backgroundColor')){ ?>

body{background:<?php echo $this->params->get('backgroundColor'); ?>;}

<?php } ?>

<?php if($this->params->get('headerColor')){ ?>

#ap-header{background:<?php echo $this->params->get('headerColor'); ?>;}

<?php } ?>

<?php if(($this->params->get('headerBorderColor')) && ($this->params->get('templateTheme'))=="theme6") { ?>

#ap-header{border-bottom:3px solid <?php echo $this->params->get('headerBorderColor'); ?>;}

<?php } ?>

<?php if(($this->params->get('headerBorderColor')) && ($this->params->get('templateTheme'))!="theme6") { ?>

#ap-toolbar{border-top:3px solid <?php echo $this->params->get('headerBorderColor'); ?>;}

<?php } ?>

<?php if($this->params->get('toolbarColor')){ ?>

#ap-toolbar{background:<?php echo $this->params->get('toolbarColor'); ?>;}

<?php } ?>

<?php if($this->params->get('crumbColor')){ ?>

#ap-crumbs{background:<?php echo $this->params->get('crumbColor'); ?>;}

<?php } ?>

<?php if($this->params->get('mainbodyColor')){ ?>

#ap-mainbody{background:<?php echo $this->params->get('mainbodyColor'); ?>;}

<?php } ?>

<?php if($this->params->get('contentColor')){ ?>

#ap-content{background:<?php echo $this->params->get('contentColor'); ?>;}

<?php } ?>

<?php if($this->params->get('sidebarColor')){ ?>

#ap-right{background:<?php echo $this->params->get('sidebarColor'); ?>;}

<?php } ?>

<?php if($this->params->get('smallSidebarColor')){ ?>

#sm-right{background:<?php echo $this->params->get('smallSidebarColor'); ?>;}

<?php } ?>

/* Font Color Overrides */

<?php if($this->params->get('fontColor')){ ?>

body{color:<?php echo $this->params->get('fontColor'); ?>;}

<?php } ?>

<?php if($this->params->get('headerFontColor')){ ?>

#ap-header{color:<?php echo $this->params->get('headerFontColor'); ?>;}

<?php } ?>

<?php if($this->params->get('toolbarFontColor')){ ?>

#ap-toolbar{color:<?php echo $this->params->get('toolbarFontColor'); ?>;}

<?php } ?>

<?php if($this->params->get('crumbFontColor')){ ?>

#ap-crumbs{color:<?php echo $this->params->get('crumbFontColor'); ?>;}

<?php } ?>

<?php if($this->params->get('linkColor')){ ?>

a{color:<?php echo $this->params->get('linkColor'); ?>;}

<?php } ?>

<?php if($this->params->get('linkHoverColor')){ ?>

a:hover{color:<?php echo $this->params->get('linkHoverColor'); ?>;}

<?php } ?>

<?php if($this->params->get('sidebarFontColor')){ ?>

#ap-right{color:<?php echo $this->params->get('sidebarFontColor'); ?>;}

<?php } ?>

<?php if($this->params->get('smallSidebarFontColor')){ ?>

#sm-right{color:<?php echo $this->params->get('smallSidebarFontColor'); ?>;}

<?php } ?>

<?php if($hideRightMenu) { ?>	

#ap-content{width:98% !important;}

<?php } ?>

</style>

</head>



<body>

<?php

require_once('quicklaunch.php');



?>

<div id="ap-header">


	<!--Begin Menu Module-->

	<div id="ap-menu">

		<jdoc:include type="modules" name="aphead1" />

	</div>



	<!--End Menu Module-->

	<div id="ap-headtools">


	<?php if($this->countModules('online')) { ?>

	<div id="ap-online">

		<jdoc:include type="modules" name="online" />

	</div>

	<?php }	?>

	<?php if($this->countModules('editor')) { ?>

	<div id="ap-editor">

		<jdoc:include type="modules" name="editor" />

	</div>

	<?php }	?>

	<?php if($this->countModules('templatetheme')) { ?>

        <div id="ap-templatetheme">

		<jdoc:include type="modules" name="templatetheme" />

	</div>

	<?php }	?>

    	<?php if($this->countModules('admintemplate')) { ?>

        <div id="ap-admintemplate">

		<jdoc:include type="modules" name="admintemplate" />

	</div>

	<?php }	?>
	<?php if($this->countModules('sitename')) { ?>

        <div id="ap-sitename">

		<jdoc:include type="modules" name="sitename" />

	</div>
	<?php }	?>

	<?php if($this->countModules('search')) { ?>

	<div id="ap-search">

		<jdoc:include type="modules" name="search" />

	</div>

	<?php }	?>

	</div>

</div>

<div id="ap-wrapper">

	<div id="ap-inner">

		<?php if(!$hideToolbar) { ?>

		<div id="ap-toolbar">

			<jdoc:include type="modules" name="aphead2" />

		</div>

		<?php }	?>

		<?php if(!$hideBreadCrumbs) { ?>

		<div id="ap-crumbs">

		<!--Begin Crumbs-->

		<?php

				echo "<span class=\"first\">". JText::_( 'FIRST' ) ."</span>\n";

				require_once('html'.DS.'mod_breadcrumbs'.DS.'mod_breadcrumbs.php');

				breadcrumbs(); 

		?>

		<!--End Crumbs-->

		</div>

		<?php }	?>

		<div id="ap-mainbody">

			<div id="ap-content" class="ap-narrow">

			<!--Begin Content-->

<?php

			if($option != "com_cpanel")

			{

?>

<?php

// Get the component title div

$title = $mainframe->get('JComponentTitle');



// Echo title if it exists

if (!empty($title)) {

	//echo $title;

} else {

  echo "<div class=\"header\"></div>";

}

?>

<!--

				<jdoc:include type="modules" name="title" />

--> 

<?php

			}

			if($this->countModules('toolbar') != 0 && $option != "com_cpanel")

			{

?>

				<jdoc:include type="modules" name="toolbar" />

<?php

			}

			if($option == "com_cpanel" && !$ap_task_set)

			{

?>

				<jdoc:include type="modules" name="admintop" style="xhtml" />

				<div style="clear: both"></div>

<?php

			}

			if(!JRequest::getInt('hidemainmenu'))

			{

?>

				<jdoc:include type="modules" name="submenu" style="xhtml" id="submenu-box" />

<?php

			}

?>

			<jdoc:include type="message" />

<?php 

			if($ap_task_set)

			{

				require_once('custom'.DS.'custom.php');

			}

			else if($option == "com_cpanel")

			{

				require_once('cpanel-module.php');

			}

			else

			{

?>

				<jdoc:include type="component" />

<?php

			}

			if(JRequest::getVar('adminpraise2install') == 'true')

			{

				require_once('install.adminpraise2.php');

			}

			else if($this->countModules('aphead1') == 0 && $this->countModules('aphead2') == 0)

			{

?>

				To automatically configure AdminPraise2, go <a style="color: red; font-weight: bold" href="index.php?adminpraise2install=true">here</a>.<br />

				To return to the Template Manager, go <a style="color: red; font-weight: bold" href="index.php?option=com_templates&client=1">here</a>.<br />

<?php

			}

?>

			<noscript>

				<?php echo JText::_('WARNJAVASCRIPT') ?>

			</noscript>

			<!--End Content-->

			<div class="clr spacer"></div>

			<div id="ap-content-foot">

			<!--Begin Content Foot-->

				<div class="inner">

				

				</div>

			<!--End Content Foot-->

			<div class="clr"></div>

			</div>

			</div>

<?php

			if(!$hideRightMenu)

			{

				require_once('right.php');

			}

?>

			<div class="clr"></div>

		</div>

		<div class="clr"></div>

		<?php if(!$hideFooter) {

		require_once('footer.php');

		} ?>

	</div>

</div>

<!--<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-6385293-19']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
-->

<jdoc:include type="modules" name="apdock" />



</body>



</html>
