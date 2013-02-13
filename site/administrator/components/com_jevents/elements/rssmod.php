<?php

// Check to ensure this file is within the rest of the framework
defined('JPATH_BASE') or die();

class JElementRssmod extends JElement
{
	/**
	* Element name
	*
	* @access	protected
	* @var		string
	*/
	var	$_name = 'rssmod';
	
	function fetchElement($name, $value, &$node, $control_name)
	{
		$this->dataModel = new JEventsDataModel("JEventsAdminDBModel");
		// get list of latest_events modules
		$modules = $this->dataModel->queryModel->getModulesByName();
		$seloptions = array();
		$seloptions[] = JHTML::_('select.option', 0, JTEXT::_('JEV_RSS_MODID_MAIN'));
		for ($i=0;$i<count($modules);$i++) {
			$seloptions[] = JHTML::_('select.option', $modules[$i]->id, $modules[$i]->title );
		}
		return JHTML::_('select.genericlist',  $seloptions, ''.$control_name.'['.$name.']', '', 'value', 'text', $value, $control_name.$name );

	}
}