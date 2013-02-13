<?php
/*
 Copyright (c) 2011-2012 Campbell Consulting Studios, LLC
 ExtScheduler v1.0
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view' );

/**
 [controller]View[controller]
 */
require_once JPATH_ROOT.'/'.'administrator/components/com_civicrm/civicrm.settings.php';
require_once JPATH_ROOT.'/'.'administrator/components/com_civicrm/civicrm/api/v2/Activity.php';


class frontdeskViewcalendarslist extends JView
{
	/**
	 * Custom Constructor
	 */
	function __construct( $config = array())
	{
		parent::__construct( $config );
	}



	function display($tpl = null)
	{

		echo '{
    "calendars":[{
        "id":53,
        "title":"Therapist",
        "color":2
    },{
        "id":54,
        "title":"Physician",
        "color":22
    }]
}';
		
		
	}
}

?>
