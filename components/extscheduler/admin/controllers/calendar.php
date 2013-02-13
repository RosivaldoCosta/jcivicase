<?php
/*

 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.controller' );
require_once JPATH_ROOT.'/'.'administrator/components/com_civicrm/civicrm.settings.php';
require_once JPATH_ROOT.'/'.'administrator/components/com_civicrm/civicrm/api/v2/Activity.php';


/**
 * ExtCalendar Controller
 */

class calendarController extends JController
{

	/**
	 * Custom Constructor
	 */
	function __construct( $default = array())
	{
		parent::__construct( $default );
		$this->registerTask( 'edit', 'edit' );
		

	}

	
	function edit()
	{
		$rows=$_POST["rows"];
		$rows=json_decode($rows);
		$date=$rows["start_time"];
		$start_time=strptime($date, "%Y-%m-%dT%H:%M:%S");
		$params = array('activity_type_id' => $rows["cid"],'subject' => $rows["subject"],'activity_date_time' => date('YmdHis',$start_time));
		$act = civicrm_activity_create($params);
		//rows:{"id":10000,"start_time":"2011-10-18T00:00:00","end_time":"2011-10-18T01:00:00","ad":false,"n":false,"subject":"sdfgfasdgasg","cid":1}
		//rows:{"id":10001,"start_time":"2011-10-12T00:00:00","end_time":"2011-10-12T01:00:00","ad":false,"n":false,"subject":"sdfsadf","cid":2}
	}
	
	
	function eventeditwindow()
	{
		$document = &JFactory::getDocument();
                $vType    = $document->getType();

                // Get/Create the view
                $view = &$this->getView( __FUNCTION__, $vType);

                // Set the layout
                $view->setLayout('default');

                // Display the view
                $view->display();

	}

	function saveEvent()
	{


	}

	function editEvent()
	{

	}

	function deleteEvent()
	{

	}

	function read()
	{
		$document = &JFactory::getDocument();
                $vType    = $document->getType();

                // Get/Create the view
                $view = &$this->getView( __FUNCTION__, $vType);

                // Set the layout
                $view->setLayout('default');

                // Display the view
                $view->display();
	}
	


}
?>

