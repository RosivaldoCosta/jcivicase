<?php

// Check to ensure this file is within the rest of the framework
defined('JPATH_BASE') or die();

class JElementJevview extends JElement
{
	/**
	* Element name
	*
	* @access	protected
	* @var		string
	*/
	var	$_name = 'jevview';

	function fetchElement($name, $value, &$node, $control_name)
	{
		$views = array();
		foreach (JEV_CommonFunctions::getJEventsViewList() as $viewfile) {
			$views[] = JHTML::_('select.option', $viewfile, $viewfile);
		}
		sort( $views );
		return JHTML::_('select.genericlist',  $views, ''.$control_name.'['.$name.']', '', 'value', 'text', $value, $control_name.$name );

	}
}