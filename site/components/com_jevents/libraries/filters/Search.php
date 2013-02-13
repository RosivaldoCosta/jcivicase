<?php
/**
 * GWE SYSTEMS Filter Library
 *
 * @version     $Id:  geraint $
 * @copyright   Copyright (C) 2008 GWE Systems
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_VALID_MOS') or defined('_JEXEC') or die( 'No Direct Access' );

// searches event
class jevSearchFilter extends jevFilter
{
	function __construct($tablename, $filterfield, $isstring=true){
		$this->filterType="search";
		$this->filterLabel=JText::_("Search Event");
		$this->filterNullValue="";
		parent::__construct($tablename,$filterfield, true);
	}

	function _createFilter($prefix=""){
		if (!$this->filterField ) return "";
		if (trim($this->filter_value)==$this->filterNullValue) return "";

		$db = JFactory::getDBO();
		$text = $db->Quote( '%'.$db->getEscaped( $this->filter_value, true ).'%', false );
				
		$filter = "(det.summary LIKE $text OR det.description LIKE $text OR det.extra_info LIKE $text)";

		return $filter;
	}

	function _createJoinFilter($prefix=""){
		return "";
	}

	function _createfilterHTML(){

		if (!$this->filterField) return "";

		$db = JFactory::getDBO();
				
		$filterList=array();
		$filterList["title"]="<label class='evsearch_label' for='".$this->filterType."_fv'>".$this->filterLabel."</label>";
		$filterList["html"] = "<input type='input' name='".$this->filterType."_fv'  class='evsearch'  value='".$this->filter_value."' />".'<input class="modlocation_button" type="submit" value="'.JText::_('ok').'" />';

		return $filterList;

	}
}
