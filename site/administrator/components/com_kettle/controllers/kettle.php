<?php

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.controller' );


/**
 */

class kettleController extends JController
{

	/**
	 * Custom Constructor
	 */
	function __construct( $default = array())
	{
		parent::__construct( $default );


	}


	function display()
	{
		parent::display();

	}

	function run()
	{
		global $mainframe;
	

		// load all available remote calls
		$name = JRequest::getVar('name', null, '', 'string');

		JPluginHelper::importPlugin('kettle', $name);
		
		if(isset($name))
		{
        
				$args = array();
				$plugin = $mainframe->triggerEvent( 'onRun',$args); //, array(null) );
		}
		
	}

}
?>

