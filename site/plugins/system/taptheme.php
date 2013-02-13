<?php
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );

include_once(JPATH_SITE.'/plugins/system/taptheme/helper.php');

class plgSystemTapTheme extends JPlugin
{

	function plgSystemTapTheme(& $subject, $config) {
		parent::__construct($subject, $config);
	}

	function onAfterInitialise()
	{	
		$mainframe = &JFactory::getApplication();
		$document = &JFactory::getDocument();
		
		$isSite = $mainframe->isSite();
		
		//if ($isSite) :
			$switchTemplate = TapThemeHelper::getBrowserTemplate($this->params, $isSite);
			if ($switchTemplate) :
				$mainframe->setTemplate($switchTemplate);
			endif;
		//endif;
				
	}
	
}
