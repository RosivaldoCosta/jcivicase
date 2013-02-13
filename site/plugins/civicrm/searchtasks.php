<?php
// No direct access
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');
class plgCivicrmSearchTasks extends JPlugin
{
    /**
     Tabs Civicrm Plugin
     */

    public function civicrm_searchtasks($objectType, &$tasks)
    {
	$user   =& JFactory::getUser();
	$usertype = $user->get('usertype');

	if($usertype === 'Manager')
	{
	    for($i = 0; $i<count($tasks); ++$i)
	    {
		unset($tasks[$i]);
	    }

	    unset($tasks[13]);
	    unset($tasks[14]);
	    //unset($tasks[15]);
	    unset($tasks[16]);
	    unset($tasks[17]);
	    unset($tasks[19]);
	    unset($tasks[22]);
	    unset($tasks[23]);
	    unset($tasks[24]);
	    unset($tasks[21]);
	    unset($tasks[20]);

	    if($_GET['debug']) echo '<pre>'.print_r($tasks,true).'</pre>';

	} else
	{

	    //Now define the parameters like this:
	    $delete = $this->params->get( 'delete_contacts');

	    if($delete == 0)
	    {
		unset($tasks[8]); //Delete
	    } else if($delete == 1)
	    {
		$tasks[8] = array( 'title'  => ts( 'Delete Contacts'               ),
			'class'  => 'CRM_Contact_Form_Task_Delete',
			'result' => false );
	    }

	}


    }

}
?>
