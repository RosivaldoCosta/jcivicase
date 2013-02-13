<?php
/**
 * copyright (C) 2008 GWE Systems Ltd - All rights reserved
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

/**
 * HTML Abstract view class for the component frontend
 *
 * @static
 */
JLoader::register('JEventsDefaultView',JEV_VIEWS."/default/abstract/abstract.php");

class JEventsAlternativeView extends JEventsDefaultView 
{
	var $jevlayout = null;

	function __construct($config = null)
	{
		parent::__construct($config);

		$this->jevlayout="alternative";	

		$this->addHelperPath(dirname(__FILE__)."/../helpers/");

	}

	function viewNavTableBarIconic( $today_date, $this_date, $dates, $alts, $option, $task, $Itemid ){
		$this->loadHelper("AlternativeViewNavTableBarIconic");
		$var = new AlternativeViewNavTableBarIconic($this, $today_date, $this_date, $dates, $alts, $option, $task, $Itemid );
	}

	function buildMonthSelect($link, $month, $year){
		$this->loadHelper("AlternativeBuildMonthSelect");
		$var = new AlternativeBuildMonthSelect($this, $link, $month, $year );
		return $var->result;
	}
}