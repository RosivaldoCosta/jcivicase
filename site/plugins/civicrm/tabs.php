<?php
// No direct access
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');
class plgCivicrmTabs extends JPlugin
{
    /**
     Tabs Civicrm Plugin
     */

    public function civicrm_tabs(&$tabs, $contactID)
    {
	$params  = array( 'id' => $contactID );
	$details = array( );
	$contact = CRM_Core_DAO::commonRetrieve( 'CRM_Contact_DAO_Contact',
		$params,
		$details,
		array('contact_type', 'contact_sub_type') );
	if( $contact->contact_sub_type === 'Client')
	{
	    require_once 'CRM/Core/DAO/Plan.php';
	    $object = null;
	    eval( '$object =& new CRM_Core_DAO_Plan( );');
	    $object->entity_table = 'civicrm_contact';
	    $object->entity_id    = $contactID;
	    $object->orderBy( 'modified_date desc' );
	    $object->find( );

	    $tabs[] = array( 'id'    => 'plans',
		    'url'   => CRM_Utils_System::url('civicrm/contact/view/plan',"reset=1&snippet=1&force=1&cid=$contactID"),
		    'title' => 'Care Plans',
		    'weight' => 15,
		    'count' => $object->N );

	}

	// clean Tags for Direct Care (display only Suicidal, and count only it)
	$user = JFactory::getUser();
	if($user->usertype == 'Manager')
	{

	    $i = 0;
	    foreach($tabs as $tab)
	    {

		if($tab['id'] == 'tag')
		{
		    if($tab['count'] > 0)
		    {
			// check if Suicidal count
			require_once 'CRM/Core/DAO/EntityTag.php';
			$entityTag =& new CRM_Core_BAO_EntityTag();
			$entityTag->contact_id = $contactID;
			$entityTag->tag_id = 2;
			$entityTag->find();
			if($entityTag->fetch())
			{
			    $tabs[$i]['count'] = 1;
			} else
			{
			    $tabs[$i]['count'] = 0;
			}
		    }
		}
		$i++;
	    }
	}


    }

}
?>
