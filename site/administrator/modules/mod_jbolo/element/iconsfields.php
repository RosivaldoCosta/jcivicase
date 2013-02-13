<?php 
// Check to ensure this file is within the rest of the framework
defined('JPATH_BASE') or die();


class JElementiconsfields extends JElement {
	

  	var   $_name = 'iconsfields';
	
	function fetchElement($name, $value, &$node, $control_name)
	{

		$options = array();

		$options[0] = (object) array("value"=>"JS Applications", "text"=>"JS Applications");
		$options[1] = (object) array("value"=>"JS Photos","text"=>"JS Photos");
		$options[2] = (object) array("value"=>"JS Videos","text"=>"JS Videos");
		$options[3] = (object) array("value"=>"JS Groups","text"=>"JS Groups");
		$options[4] = (object) array("value"=>"JS Messages","text"=>"JS Messages");
		$options[5] = (object) array("value"=>"CB Home","text"=>"CB Home");
		$options[6] = (object) array("value"=>"CB UpdateProfile","text"=>"CB UpdateProfile");
		$options[7] = (object) array("value"=>"CB UpdateAvatar","text"=>"CB UpdateAvatar");
		$options[8] = (object) array("value"=>"PeopleTouch","text"=>"People Touch Home");


		return JHTML::_('select.genericlist', $options, $control_name.'['.$name.'][]', 'multiple="true" size="5"', 'value', 'text', $value);
	}
}
?>
