<?php
	$user =& JFactory::getUser();
	$profilelink = "[<a href=\"" . $mainframe->getCfg('live_site') . "/administrator/index.php?option=com_users&view=user&task=edit&cid[]=" . $user->get('id') . "\">". JText::_( 'EDITYOURPROFILE' ) ."</a>]";?>

<div class="header icon-48-cpanel">
	<!--<span id="ap-cpanellogo"></span>-->
	<h1><?php
	if($this->params->get('adminTitle'))
	{
	echo $this->params->get('adminTitle');
	} else {
	echo $mainframe->getCfg( 'sitename' );
	}
	?></h1>
	<div class="ap-user">
		<span class="ap-pagetitle"><?php echo JText::_( 'CONTROLPANEL' ); ?></span>		<span class="ap-welcome"><small><?php echo JText::_( 'WELCOMEBACK' ); ?>, <?php echo $user->username; ?> <?php echo $profilelink; ?></small></span>
		<div class="clr"></div>
	</div>
</div>
<div>
	<div id="ap-cpanel">
		<div class="fifty left">
			<div class="inner">
				<div class="module">
					<jdoc:include type="modules" name="apcpleft" style="xhtml" />
				</div>
			</div>
		</div>
		<div class="fifty right">
			<div class="inner">
				<div class="module">
					<jdoc:include type="modules" name="apcpright" style="xhtml" />
				</div>
			</div>
		</div>
	</div>
	<div class="clr spacer"></div>
	<div id="ap-content-sub">
		<div class="inner">
			<div class="module">
				<jdoc:include type="modules" name="apcpbottom" style="xhtml" />
			</div>
		</div>
	<div class="clr spacer"></div>
	</div>
</div>
