<?php
/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.filesystem.file');

if (!JFile::exists(JPATH_ROOT.DS.'components'.DS.'com_jbolo'.DS.'jbolo.php')) {
	return;
}

class plgSystemPlg_sys_jbolo_asset extends JPlugin{

	function onAfterDispatch ( ) {
	
		$app =& JFactory::getApplication();
		//if ($app->getName() != 'site') { return; }
		
		global $mainframe;
		$option = JRequest:: getVar('option');
		$view = JRequest:: getVar('view');
		//if ($mainframe->isAdmin()) {
		//	return; // Dont run in admin
		//}

		$document =& JFactory::getDocument();

		//If FB is selected in the backend
		jimport( 'joomla.application.module.helper' );
		
		if(JModuleHelper::getModule( 'jbolo' ))
		{
			$document->addStyleSheet(JURI::root().'components/com_jbolo/css/chat.css');
			$document->addStyleSheet(JURI::root().'components/com_jbolo/css/screen.css');
			$document->addStyleSheet(JURI::root().'components/com_jbolo/css/jbolo.css');
			$document->addStyleSheet(JURI::root().'components/com_jbolo/css/jquery-ui.css');
			if ($option=='com_jbolo' && ($view=='history' || $view=='sendfile')) {
				return; // Dont run in admin
			}

			//$document->addScript(JRoute::_('index.php?option=com_jbolo&view=js&format=raw'));
			$document->addScript(JURI::root().'index.php?option=com_jbolo&view=js&format=raw');
			$document->addScript(JURI::root().'components/com_jbolo/js/jquery-1.3.2.pack.js');
			$document->addScript(JURI::root().'components/com_jbolo/sound/soundmanager2.js');
			$document->addScript(JURI::root().'components/com_jbolo/js/jquery-ui.min.js');


			$module = JModuleHelper::getModule( 'jbolo' );
	 		$moduleParams = new JParameter( $module->params );
			$limit = intval($moduleParams->get( 'modorbar', 1 ));
			if($limit==0)
			{
				$document->addScript(JURI::root().'components/com_jbolo/js/fb_chat.js');
			}
			else
			{
				$document->addScript(JURI::root().'components/com_jbolo/js/jbolo_chat.js');
				$cscript[] = '<!--[if lte IE 7]>';
				$cscript[] = '<link href="'.JURI::root().'components/com_jbolo/css/screen_ie.css" type="text/css" rel="stylesheet"/>';
				$cscript[] = '<![endif]-->';
		
				$mainframe->addCustomHeadTag(implode("\n", $cscript));	
			}
		}
	

		return;
	}
	
}
