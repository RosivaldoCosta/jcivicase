<?php
/**
 * GWE SYSTEMS Filter Library
 *
 * @version     $Id:  geraint $
 * @copyright   Copyright (C) 2008 GWE Systems
 * @license		GNU/GPL, see LICENSE.php
 */


// ensure this file is being included by a parent file
defined('_JEXEC') or die( 'Direct Access to this location is not allowed.' );

class jevResetFilter extends jevFilter
{
	function jevResetFilter ($contentElement){
		$this->filterNullValue=-1;
		$this->filterType="reset";
		$this->filterField = "";
		parent::jevFilter($contentElement,"");
	}

	function _createFilter(){
		return "";
	}

	/**
 * Creates javascript session memory reset action
 *
 */
	function _createfilterHTML(){
		$reset["title"]= "";
		$reset["html"] = "<input type='hidden' name='filter_reset' id='filter_reset' value='0' /><input type='button' value='".JText::_("reset")."' onclick='document.getElementById(\"filter_reset\").value=1;form.submit()' />";
		return $reset;

	}

}
