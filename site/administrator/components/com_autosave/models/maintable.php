<?php
 
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport( 'joomla.application.component.model' );
 

class maintableModelmaintable extends JModel
{
    
    /**
    * Gets the autosave table full content
    * @return assoc array data table
    */
    function getList($limitstart, $limit, $filter_case, $filter_activity ){
	   $db =& JFactory::getDBO();
	   
	   $where = ' WHERE 1=1';
	   if($filter_activity == 'NULL'){
	   		$where .= ' AND `activity_type` is NULL ';
	   }elseif($filter_activity){
	   		$where .= ' AND `activity_type`="'.$filter_activity.'" ';
	   }
	   if($filter_case == 'NULL'){
	   		$where .= ' AND `case_id` is NULL ';
	   }elseif($filter_case){
	   		$where .= ' AND `case_id`="'.$filter_case.'" ';
	   }
 
	   $query = 'SELECT * FROM form_autosave_data '.$where.' LIMIT '.$limitstart.', '.$limit;
	   //$query = 'SELECT * FROM form_autosave_data '.$where.' LIMIT 39, 2';
	   //print_r($query); die;
	   $db->setQuery( $query );
	   $saveList = $db->loadAssocList();

	   return $saveList;
    }
    
    function getTotal($filter_case, $filter_activity){
	   $db =& JFactory::getDBO();
 
       $where = ' WHERE 1=1';
	   if($filter_activity == 'NULL'){
	   		$where .= ' AND `activity_type` is NULL ';
	   }elseif($filter_activity){
	   		$where .= ' AND `activity_type`="'.$filter_activity.'" ';
	   }
	   if($filter_case == 'NULL'){
	   		$where .= ' AND `case_id` is NULL ';
	   }elseif($filter_case){
	   		$where .= ' AND `case_id`="'.$filter_case.'" ';
	   }
	   
	   $query = 'SELECT count(id) FROM form_autosave_data '.$where;
	   $db->setQuery($query);
	   $count = $db->loadResult();

	   return $count;
    }

    function getCaseIdsList(){

     	$db =& JFactory::getDBO();
     	
		$query = 'SELECT DISTINCT case_id as value, case_id as text FROM form_autosave_data ORDER BY case_id';
		$db->setQuery( $query );
		$caseids = $db->loadObjectList();
		return $caseids;
    }
    
    function getActivitiesList(){

     	$db =& JFactory::getDBO();
     	
		$query = 'SELECT DISTINCT activity_type as value, activity_type as text FROM form_autosave_data ORDER BY activity_type+1-1';
		$db->setQuery( $query );
		$caseids = $db->loadObjectList();
		return $caseids;
    }

}