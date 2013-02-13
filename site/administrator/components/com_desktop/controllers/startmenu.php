<?php

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.controller' );


/**
 */

class startmenuController extends JController
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

	function config()
	{
		// Get the document object
                $objJDocument   = &JFactory::getDocument();
                $objURI                 = &JURI::getInstance();

                $viewName       = JRequest::getCmd('view', 'nonprofitcenter');
                $viewType       = $objJDocument->getType();

                // Get the view
                $view   = &$this->getView($viewName, $viewType);
                $model  = &$this->getModel($viewName);

                if($model)
                        $view->setModel($model, $viewName);

                // Set the default layout and view name
                $layout = JRequest::getCmd('layout', 'default');

                // Set the layout
                $view->setLayout($layout);
		
		parent::display();

	}

	function stats()
	{

	}
}
?>

