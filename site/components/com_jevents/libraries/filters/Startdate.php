<?php
/**
 * Copyright (C) 2008 GWE Systems Ltd
 *
 * All rights reserved.
 *
*/
defined('_VALID_MOS') or defined('_JEXEC') or die( 'No Direct Access' );

// Event repeat startdate fitler
class jevStartdateFilter extends jevFilter
{

	var $dmap="";
	var $_onorbefore = false;
	var $_date = "";

	function __construct($tablename, $filterfield, $isstring=true){
		$this->fieldset=true;

		$this->valueNum=3;
		$this->filterNullValue=0;
		$this->filterNullValues[0]=0; // n/a, before, after
		$this->filterNullValues[1]=""; // the date
		$this->filterNullValues[2]=0; // true means the form is submitted
	
		$this->filterType="startdate";
		$this->filterLabel="";
		$this->dmap = "rpt";
		parent::__construct($tablename,$filterfield, true);

		$this->_date = $this->filter_values[1];
		$this->_onorbefore = $this->filter_values[0];

	}

	function _createFilter($prefix=""){
		if (!$this->filterField ) return "";
		// first time visit
		if (isset($this->filter_values[2]) && $this->filter_values[2]==0) {
			$this->filter_values = array();
			$this->filter_values[0]=1;
			// default scenario is only events starting after 2 weeeks ago			
			$fulldate = date( 'Y-m-d H:i:s',strtotime("-2 weeks"));
			$this->filter_values[1]=substr($fulldate,0,10);
			$this->filter_values[2]=1;
			return  $this->dmap.".startrepeat>='$fulldate'";
		}
		else if ($this->filter_values[0]==0){
			$this->filter_values[1]="";
		}
		else if ($this->filter_values[0]==-1 && $this->filter_values[1]==""){
			$fulldate = date( 'Y-m-d H:i:s',strtotime("+2 weeks"));
			$this->filter_values[1]=substr($fulldate,0,10);
		}
		else if ($this->filter_values[0]==1 && $this->filter_values[1]==""){
			$fulldate = date( 'Y-m-d H:i:s',strtotime("-2 weeks"));
			$this->filter_values[1]=substr($fulldate,0,10);
		}
		$filter="";

		if ($this->_date!="" && $this->_onorbefore!=0){
			$date = strtotime($this->_date);
			$fulldate = date( 'Y-m-d H:i:s',$date);
			if ($this->_onorbefore>0){
				$date = $this->dmap.".startrepeat>='$fulldate'";
			}
			else {
				$date = $this->dmap.".startrepeat<'$fulldate'";
			}
		}
		else {
			$date = "";
		}
		$filter = $date;

		return $filter;
	}

	function _createfilterHTML(){

		if (!$this->filterField) return "";

		$filterList=array();
		$filterList["title"]=JText::_("With Instances");

		$filterList["html"] = "";

		$options = array();
		$options[] = JHTML::_('select.option', '0',JText::_('When?') );
		$options[] = JHTML::_('select.option', '1',JText::_('On or after?') );
		$options[] = JHTML::_('select.option', '-1',JText::_('Before?') );
		$filterList["html"] .= JHTML::_('select.genericlist', $options, $this->filterType.'_fvs0', 'onchange="form.submit()" class="inputbox" size="1" ', 'value', 'text', $this->filter_values[0] );

		$filterList["html"] .=  JHTML::calendar($this->filter_values[1],$this->filterType.'_fvs1', $this->filterType.'_fvs1', '%Y-%m-%d',array('size'=>'12','maxlength'=>'10','onchange'=>'form.submit()'));

		$filterList["html"] .= "<input type='hidden' name='".$this->filterType."_fvs2' value='1'/>";
		
		return $filterList;
		
		
	}
}
