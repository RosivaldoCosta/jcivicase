<?php
/**
 * @copyright	Copyright (C) 2008 JoomlaPraise. All rights reserved.
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo  $this->language; ?>" lang="<?php echo  $this->language; ?>" dir="<?php echo  $this->direction; ?>" id="minwidth" >
<head>
<meta name = "viewport" content = "initial-scale = 0.6">
<jdoc:include type="head" />
<script type="text/javascript" src="templates/<?php echo $this->template ?>/js/adminpraise.js"></script>
<link rel="apple-touch-icon" href="templates/<?php echo $this->template ?>/images/apple-touch-icon.png" />
<link rel="stylesheet" href="templates/system/css/system.css" type="text/css" />
<link href="templates/<?php echo $this->template ?>/css/template.css" rel="stylesheet" type="text/css" />
<link href="templates/<?php echo $this->template ?>/css/<?php echo $this->params->get('templateTheme'); ?>.css" rel="stylesheet" type="text/css" />
<link href="templates/<?php echo $this->template ?>/images/favicon.ico" rel="shortcut icon" type="image/x-icon" />
<script language="javascript" type="text/javascript">
	function setFocus() {
		document.login.username.select();
		document.login.username.focus();
	}
	apSetLoginCookie();
</script>
</head>
<body onload="javascript:setFocus()" id="login-body">

<div id="ap-loginwrap">
			<div id="ap-login">
			<!--Begin Content-->
				<div class="header icon-48-cpanel"><h1><?php echo $mainframe->getCfg( 'sitename' ); ?></h1>
					<div class="ap-user">
						<span class="ap-pagetitle">Control Panel</span>
						<span class="ap-welcome">Welcome back, <?php echo $mainframe->getCfg( 'sitename' ); ?> Admin</small></span>
						<div class="clr"></div>
					</div>
				</div>
				<div class="fifty left">
					<div class="inner">
					<jdoc:include type="message" />
						<noscript>
						<?php echo JText::_('WARNJAVASCRIPT') ?>
						</noscript>
						<div class="module">
							<h3>Login</h3>
							<jdoc:include type="component" />
							<ul class="forgot-links">
							<li class="forgot-pass">
							<a href="<?php echo $mainframe->getSiteURL(); ?>index.php?option=com_user&amp;view=reset">
							Forgot your password?</a>
							</li>
							<li class="forgot-username">
							<a href="<?php echo $mainframe->getSiteURL(); ?>index.php?option=com_user&amp;view=remind">
							Forgot your username?</a>
							</li>
							</ul>
						</div>
					</div>
				</div>
				<div class="fifty right">
					<div class="inner">
						<div class="module">
						<jdoc:include type="modules" name="aplogin" style="xhtml" />
						</div>
					</div>
				</div>
			<!--End Content-->
			<div class="clr spacer"></div>
			<div id="ap-content-sub">
			<!--Begin Content Sub-->
				<div class="inner">
					<div class="module">
						<span class="ap-helptitle"></span>
						<div class="clr"></div>
						<p></p>
					</div>
				</div>
			<!--End Content Sub-->
			<div class="clr spacer"></div>
			</div>
			<div id="ap-content-foot">
			<!--Begin Content Foot-->
				<div class="inner">
				
				</div>
			<!--End Content Foot-->
			<div class="clr"></div>
			</div>
			</div>
			<div class="clr"></div>
		</div>



</body>

</html>

