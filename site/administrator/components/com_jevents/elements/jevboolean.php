<?php

// Check to ensure this file is within the rest of the framework
defined('JPATH_BASE') or die();

class JElementJevboolean extends JElement
{
	/**
	* Element name
	*
	* @access	protected
	* @var		string
	*/
	var	$_name = 'Jevboolean';
	
	function fetchElement($name, $value, &$node, $control_name)
	{
		$options = array ();
		$options[] = JHTML::_('select.option', 0, JText::_("No"));
		$options[] = JHTML::_('select.option', 1, JText::_("Yes"));

		return JHTML::_('select.radiolist', $options, ''.$control_name.'['.$name.']', '', 'value', 'text', $value, $control_name.$name );
	}
}