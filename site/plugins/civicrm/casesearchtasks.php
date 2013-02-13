<?php
// No direct access
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');
class plgCivicrmcaseSearchTasks extends JPlugin
{
    /**
     Tabs Civicrm Plugin
     */

    public function civicrm_casesearchtasks($objectType, &$tasks)
    {
	//Now define the parameters like this:
	$delete = $this->params->get( 'delete_contacts');

	if($_GET['debug']) echo '<pre>'.print_r($tasks,true).'</pre>';
	if($delete == 0)
	{

	    unset($tasks[1]); //Delete
	} else if($delete == 1)
	{
	    $tasks[1] = array( 'title'  => ts( 'Delete Cases'               ),
		    'class'  => 'CRM_Case_Form_Task_Delete',
		    'result' => false );
	}


    }

}
?>
