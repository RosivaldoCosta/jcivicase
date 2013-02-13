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

class JEventsGeraintView extends JEventsDefaultView 
{
	var $jevlayout = null;

	function __construct($config = null)
	{
		parent::__construct($config);

		$this->jevlayout="geraint";	

		$this->addHelperPath(dirname(__FILE__)."/../helpers/");

	}

	function viewNavTableBarIconic( $today_date, $this_date, $dates, $alts, $option, $task, $Itemid ){
		$this->loadHelper("GeraintViewNavTableBarIconic");
		$var = new GeraintViewNavTableBarIconic($this, $today_date, $this_date, $dates, $alts, $option, $task, $Itemid );
	}

}
