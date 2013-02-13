<?php
// No direct access
defined('_JEXEC') or die;

/**
 * Relationships tab in profile, hide the employees
 */
jimport('joomla.plugin.plugin');
class plgCivicrmRelationship extends JPlugin
{

    /**
     * Hide relation line
     */
    public function civicrm_pageRun( &$page )
    {

	$template =& CRM_Core_Smarty::singleton( );
	//echo '<pre>';print_r($template->_tpl_vars['currentRelationships']);
	if(isset($template->_tpl_vars['currentRelationships']) && count($template->_tpl_vars['currentRelationships']))
	{
	    foreach($template->_tpl_vars['currentRelationships'] as $idx => $relation)
	    {
		if($relation['civicrm_relationship_type_id'] == 10)
		    unset($template->_tpl_vars['currentRelationships'][$idx]);
	    }
	}
    }

    /**
     * Hide count near title
     */
    public function civicrm_tabs(&$tabs, $contactID)
    {

	$i = 0;
	foreach($tabs as $tab)
	{

	    if($tab['id'] == 'rel')
	    {
		if($tab['count'] > 0)
		{

		    require_once 'CRM/Contact/DAO/Relationship.php';
		    $query = '
			            	SELECT 
			            		count(civicrm_relationship.id) as cnt
			            	FROM 
			            		civicrm_relationship 
			            	WHERE 
			            		civicrm_relationship.relationship_type_id <> 10 
			            	AND 
			            		civicrm_relationship.contact_id_a = '.$contactID; 
		    $relationship =& new CRM_Contact_DAO_Relationship( );
		    $relationship->query($query);
		    // print_r($relationship); die;

		    $relationshipCount = 0;
		    while ( $relationship->fetch() )
		    {
			$tabs[$i]['count'] = (int)$relationship->cnt;
		    }
		}
	    }
	    $i++;
	}
    }

}
?>
