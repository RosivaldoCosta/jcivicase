<?php

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

class JElementJevuser extends JElement
{
	/**
	* Element name
	*
	* @access	protected
	* @var		string
	*/
	var	$_name = 'JEVUser';

	function fetchElement($name, $value, &$node, $control_name)
	{
		$db = &JFactory::getDBO();
		$class		= $node->attributes('class');
		if (!$class) {
			$class = "inputbox";
		}

		//jimport("joomla.html.html.list");
		$params = JComponentHelper::getParams("com_jevents");
		
		if (strpos($name,"jevadmin")===0){
			$gid = $params->get('jevpublish_level',24);
		}
		else if (strpos($name,"jeveditor")===0){
			$gid = $params->get('jeveditor_level',20);
		}
		else if (strpos($name,"jevpublisher")===0){
			$gid = $params->get('jevpublish_level',21);
		}
		else {
			$gid = $params->get('jevcreator_level',19);
		}
		
		$db =& JFactory::getDBO();

		$query = 'SELECT id AS value, name AS text'
		. ' FROM #__users'
		. ' WHERE block = 0'
		. ' AND gid >= '.$gid
		. ' ORDER BY gid desc, name'
		;
		$db->setQuery( $query );
		$users[] = JHTML::_('select.option',  '0', '- '. JText::_( 'Select User' ) .' -' );
		$users = array_merge( $users, $db->loadObjectList() );

		$users = JHTML::_('select.genericlist',   $users, $control_name.'['.$name.']', 'class="'.$class.'" size="1" ', 'value', 'text', $value );

		return $users;
	}
}