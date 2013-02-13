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



/**
 * Filters events to restrict events for administration - used for administration of events in the frontend
 * Only show events created by the user themselves
 */

class jevJustmineFilter extends jevFilter
{
	var $label="";
	var $yesLabel = "";
	var $noLabel = "";
	var $isEventAdmin = false;

	function jevJustmineFilter($tablename, $filterfield, $isstring=true,$yesLabel="Yes", $noLabel="No"){
		$this->filterNullValue="0";
		$this->yesLabel = JText::_($yesLabel);
		$this->noLabel =  JText::_($noLabel);
		$this->filterType = "justmine";
		$this->filterLabel = JText::_("Show Only My Events?");
		parent::jevFilter($tablename, "state", $isstring);

	}

	function _createFilter($prefix=""){
		if (!$this->filterField ) return "";
		if ($this->filter_value == $this->filterNullValue) return "";
		// The default to show all events
		$user = JFactory::getUser();
		return "ev.created_by=".$user->id;	
	}

	function _createfilterHTML(){
		$filterList=array();
		$filterList["title"] = $this->filterLabel;
		$options = array();
		$options[] = JHTML::_('select.option', "0", $this->noLabel,"value","yesno");
		$options[] = JHTML::_('select.option',  "1", $this->yesLabel,"value","yesno");
		$filterList["html"] = JHTML::_('select.genericlist',$options, $this->filterType.'_fv', 'class="inputbox" size="1" onchange="form.submit();"', 'value', 'yesno', $this->filter_value );
		return $filterList;
	}

}
