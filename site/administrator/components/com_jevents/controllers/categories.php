<?php
/**
 * JEvents Component for Joomla 1.5.x
 *
 * @version     $Id: categoryController.php 1117 2008-07-06 17:20:59Z tstahl $
 * @package     JEvents
 * @copyright   Copyright (C) 2006-2008 JEvents Project Group
 * @license     GNU/GPLv2, see http://www.gnu.org/licenses/gpl-2.0.html
 * @link        http://joomlacode.org/gf/project/jevents
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.controller');

JLoader::register('JEventsCategory',JEV_ADMINPATH."/libraries/categoryClass.php");

class AdminCategoriesController extends JController {
	var $component = null;
	var $categoryTable = null;
	var $categoryClassname = null;
	var	$categoryExtrasTable = null;
	var	$categoryExtrasClassname = null;

	/**
	 * Controler for the Control Panel
	 * @param array		configuration
	 */
	function __construct($config = array())
	{
		parent::__construct($config);
		$this->registerTask( 'list',  'overview' );
		$this->registerDefaultTask("overview");

		$this->component = 	JEV_COM_COMPONENT;
		$this->categoryTable = "#__categories";
		$this->categoryClassname = "JEventsCategory";
		$this->categoryExtrasTable = "#__jevents_categories";
		$this->categoryExtrasClassname = "JEventsCategoryExtras";

	}

	/**
	 * Category Management code
	 *
	 * Author: Geraint Edwards
	 */
	/**
	 * Manage categories - show lists
	 *
	 */
	function overview( )
	{
		global $mainframe;
		// TODO fix this when database is updated
		$section_name  = "com_jevents";
		$db	=& JFactory::getDBO();
		$user =& JFactory::getUser();

		if (strtolower($user->usertype)!="super administrator" && strtolower($user->usertype)!="administrator"){
			$this->setRedirect( "index.php?option=$this->component&task=cpanel.cpanel", "Not Authorised - must be admin" );
			return;
		}

		$limit		= intval( $mainframe->getUserStateFromRequest( "cat_listlimit", 'limit', 10 ));
		$limitstart = intval( $mainframe->getUserStateFromRequest( "cat_{$this->component}limitstart", 'limitstart', 0 ));

		// get the filter
		$parent	= $mainframe->getUserStateFromRequest( 'jev_parent',	'parentid',			-1,	'int' );
		
		// get the total number of records
		$query = "SELECT count(*) FROM $this->categoryTable"
		. "\n WHERE section='$section_name'"	;
		if ($parent>=0){
			$query .= " AND parent_id=".$parent;
		}
		$db->setQuery( $query);
		$total = $db->loadResult();
		echo $db->getErrorMsg();
		if( $limit > $total ) {
			$limitstart = 0;
		}

		$db	=& JFactory::getDBO();

		$sql = "SELECT c.* , e.color, g.name AS _groupname, pc.title as parentcat, e.admin FROM $this->categoryTable as c"
		. "\n LEFT JOIN #__groups AS g ON g.id = c.access"
		. "\n LEFT JOIN $this->categoryTable as pc ON pc.id = c.parent_id"
		. "\n LEFT JOIN $this->categoryExtrasTable as e ON e.id = c.id"
		. "\n WHERE c.section='$section_name' "
		. ($parent>=0?" AND c.parent_id=".$parent : "")
		. "\n ORDER BY ordering ";
		if ($limit>0){
			$sql .= "\n LIMIT $limitstart, $limit";
		}

		$db->setQuery($sql);
		$rows = $db->loadObjectList();
				
		$cats = array();
		if ($rows){
			foreach ($rows as $row) {
				$cat = new $this->categoryClassname($db,$this->categoryTable);
				$cat->bind(get_object_vars($row));
				// extra fields
				$cat->_groupname = $row->_groupname;
											
				$cat->_parent = !is_null($row->parentcat)?$row->parentcat:"-";
				$cats[$cat->id]=$cat;
			}
		}
		
		$sql = "SELECT DISTINCT pc.id AS value, pc.title AS text FROM $this->categoryTable as pc"
		. "\n LEFT JOIN $this->categoryTable as cc on pc.id=cc.parent_id"
		. "\n WHERE pc.section='$section_name' AND cc.id IS NOT NULL"
		. "\n ORDER BY pc.ordering ";

		$db->setQuery($sql);
		$categories[] = JHTML::_('select.option', '-1', '- '.JText::_('Select Parent').' -');
		$categories[] = JHTML::_('select.option', '0', JText::_('No Parent'));
		$categories = array_merge($categories, $db->loadObjectList());
		$parents = JHTML::_('select.genericlist',  $categories, 'parentid', 'class="inputbox" size="1" onchange="document.adminForm.submit( );"', 'value', 'text', $parent);

		jimport('joomla.html.pagination');
		$pageNav = new JPagination( $total, $limitstart, $limit  );

		// get the view
		$this->view = & $this->getView("categories","html");

		// Set the layout
		$this->view->setLayout('overview');
		$this->view->assign('title'   , JText::_("Categories"));
		$this->view->assign('cats',$cats);
		$this->view->assign('parents',$parents);
		$this->view->assign('pageNav',$pageNav);

		$this->view->display();

	}

	/**
	 * Category Editing code
	 *
	 * Author: Geraint Edwards
	 * 
	 */
	function edit(){
		$cid = JRequest::getVar(	'cid',	array(0) );
		JArrayHelper::toInteger($cid);

		// TODO fix this when database is updated
		$section_name = "com_jevents";

		$user =& JFactory::getUser();

		if (strtolower($user->usertype)!="super administrator" && strtolower($user->usertype)!="administrator"){
			$this->setRedirect( "index.php?option=".JEV_COM_COMPONENT."&task=categories.list", "Not Authorised - must be super admin" );
			return;
		}

		$db	=& JFactory::getDBO();

		if (count($cid)<=0){
			$this->setRedirect( "index.php?option=".JEV_COM_COMPONENT."&task=categories.list", "Invalid Category Selection" );
			return;
		}
		else {
			$cid=$cid[0];
		}
		$cat = new $this->categoryClassname($db,$this->categoryTable);
		$cat->load($cid);

		// get categories for parent info
		$sql = "SELECT c.*, e.color, e.admin  FROM $this->categoryTable as c "
		."\n LEFT JOIN $this->categoryExtrasTable as e ON c.id=e.id"
		."\n WHERE section='$section_name' AND c.id<>$cid"
		."\n ORDER BY ordering"
		;
		$db->setQuery($sql);
		$rows = $db->loadObjectList();
		$cats = array();
		// empty row
		$emptycat = new $this->categoryClassname($db,$this->categoryTable);
		$emptycat->title=JText::_("JEV_CATEGORY_PARENT_NONE");
		$cats[0]=$emptycat;

		$params = JComponentHelper::getParams(JEV_COM_COMPONENT);

		if ($rows){
			foreach ($rows as $row) {
				$tempcat = new $this->categoryClassname($db,$this->categoryTable);
				$tempcat->bind(get_object_vars($row));
								
				$cats[$tempcat->id]=$tempcat;

			}
		}
		// reset orphans to no parents
		if (!array_key_exists($cat->parent_id,$cats)) $cat->parent_id=0;
		$plist = JHTML::_('select.genericlist', $cats, 'parent_id', 'class="inputbox" size="1"',"id","title",$cat->parent_id);

		// authorised user to select admin
		$params = JComponentHelper::getParams("com_jevents");
		$gid = $params->get('jevpublish_level',24);

		$query = 'SELECT id AS value, name AS text'
		. ' FROM #__users'
		. ' WHERE block = 0'
		. ' AND gid >= '.$gid
		. ' ORDER BY gid desc, name'
		;
		$db->setQuery( $query );
		$users[] = JHTML::_('select.option',  '0', '- '. JText::_( 'Select Admin' ) .' -' );
		$users = array_merge( $users, $db->loadObjectList() );

		$users = JHTML::_('select.genericlist',   $users, 'admin', 'class="inputbox" size="1" ', 'value', 'text', intval( $cat->getAdminId() ) );
		
		
		// get list of groups
		$query = "SELECT id AS value, name AS text"
		. "\n FROM #__groups"
		. "\n ORDER BY id"
		;
		$db->setQuery( $query );
		$groups = $db->loadObjectList();

		// build the html select list
		$glist = JHTML::_('select.genericlist', $groups, 'access', 'class="inputbox" size="1"','value', 'text', intval( $cat->access ) );

		// get the view
		$this->view = & $this->getView("categories","html");

		// Set the layout
		$this->view->setLayout('edit');
		$this->view->assign('title'   , JText::_("Categories"));
		$this->view->assign('cat',$cat);
		$this->view->assign('plist',$plist);
		$this->view->assign('glist',$glist);
		$this->view->assign('alist',$users);

		$this->view->display();
	}

	/**
	 * Category Saving code
	 *
	 * Author: Geraint Edwards
	 * 
	 */
	function save(){
		$db	=& JFactory::getDBO();
		$user =& JFactory::getUser();

		$cid = JRequest::getVar(	'cid',	array(0) );
		JArrayHelper::toInteger($cid);

		if (strtolower($user->usertype)!="super administrator" && strtolower($user->usertype)!="administrator"){
			$this->setRedirect( "index.php?option=$this->component&task=cpanel.cpanel", "Not Authorised - must be admin" );
			return;
		}

		$cat = new $this->categoryClassname($db,$this->categoryTable);

		if (!$cat->bind( JRequest::get('request', JREQUEST_ALLOWHTML))) {
			echo "<script> alert('".$cat->getError()."'); window.history.go(-1); </script>\n";
			exit();
		}

		if (!$cat->check()) {
			echo "<script> alert('".$cat->getError()."'); window.history.go(-1); </script>\n";
			exit();
		}

		if (!$cat->store()) {
			echo "<script> alert('".$cat->getError()."'); window.history.go(-1); </script>\n";
			exit();
		}
		$cat->checkin();
		$cat->reorder( "section='$cat->section'" );

		$this->setRedirect( "index.php?option=".JEV_COM_COMPONENT."&task=categories.list", JText::_('JEV_ADMIN_CATSUPDATED'));

	}

	/**
	 * Category Ordering code
	 *
	 * Author: Geraint Edwards
	 * Copyright: 2007 Geraint Edwards
	 * 
	 */
	function saveorder(){
		$user =& JFactory::getUser();
		if (strtolower($user->usertype)!="super administrator" && strtolower($user->usertype)!="administrator"){
			$this->setRedirect( "index.php?option=$this->component&task=cpanel.cpanel", "Not Authorised - must be admin" );
			return;
		}
		$cid = JRequest::getVar(	'cid',	array(0) );
		JArrayHelper::toInteger($cid);

		$db	=& JFactory::getDBO();
		$order	= JRequest::getVar(		'order', 		array(0) );
		if (count($order)!=count($cid)){
			$this->setRedirect( "index.php?option=".JEV_COM_COMPONENT."&task=cpanel.cpanel", "Category order problems" );
			return;
		}
		for ($k=0;$k<count($cid);$k++){
			$cat = new $this->categoryClassname($db,$this->categoryTable);
			$cat->load($cid[$k]);
			$cat->ordering = $order[$k];
			$cat->store();
		}
		$this->setRedirect( "index.php?option=".JEV_COM_COMPONENT."&task=categories.list", JText::_('JEV_ADMIN_CATSUPDATED'));
		return;
	}

	/**
	 * Category Deletion code
	 *
	 * Author: Geraint Edwards
	 * 
	 */	
	function delete(){
		$user =& JFactory::getUser();
		if (strtolower($user->usertype)!="super administrator" && strtolower($user->usertype)!="administrator"){
			$this->setRedirect( "index.php?option=$this->component&task=cpanel.cpanel", "Not Authorised - must be admin" );
			return;
		}
		$cid = JRequest::getVar(	'cid',	array(0) );
		JArrayHelper::toInteger($cid);
		$catids = implode(",",$cid);

		// REMEMBER TO CLEAN OUT THE MAPPING TOO!!
		$db	=& JFactory::getDBO();

		if (strlen($catids)==""){
			$this->setRedirect( "index.php?option=".JEV_COM_COMPONENT."&task=cpanel.cpanel", "Bad categories" );
			return;
		}

		// check this won't create orphan categories
		$query = "SELECT id FROM $this->categoryTable WHERE parent_id in ($catids)";
		$db->setQuery( $query );
		$kids = $db->loadObjectList();
		if (count($kids)>0){
			$this->setRedirect( "index.php?option=".JEV_COM_COMPONENT."&task=categories.list", JText::_("DELETE CREATES ORPHANS") );
			return;	
		}
		
		// check this won't create orphan events
		$query = "SELECT ev_id FROM #__jevents_vevent WHERE catid in ($catids)";
		$db->setQuery( $query );
		$kids = $db->loadObjectList();
		if (count($kids)>0){
			$this->setRedirect( "index.php?option=".JEV_COM_COMPONENT."&task=categories.list", JText::_("DELETE CREATES ORPHAN EVENTS") );
			return;	
		}
		
		// Make sure this is not the default category of the default calendar
		$query = "SELECT * FROM $this->categoryTable as cat LEFT JOIN #__jevents_icsfile as icsf ON icsf.catid=cat.id WHERE cat.id in ($catids) and icsf.isdefault=1";
		$db->setQuery( $query );
		$cals = $db->loadObjectList();		
		if (count($cals)>0){
			$this->setRedirect( "index.php?option=".JEV_COM_COMPONENT."&task=categories.list", JText::_("CANNOT DELETE DEFAULT CALENDAR CATEGORY") );
			return;	
		}
		
		$query = "DELETE FROM $this->categoryExtrasTable WHERE id in ($catids)";
		$db->setQuery( $query );
		$db->query();

		$query = "DELETE FROM $this->categoryTable WHERE id in ($catids)";
		$db->setQuery( $query );
		$db->query();

		$this->setRedirect( "index.php?option=".JEV_COM_COMPONENT."&task=categories.list", JText::_("Category(s) deleted") );
		return;
	}


	function publish(){
		$cid = JRequest::getVar(	'cid',	array(0) );
		JArrayHelper::toInteger($cid);
		$this->toggleCatPublish($cid,1);
	}

	function unpublish(){
		$cid = JRequest::getVar(	'cid',	array(0) );
		JArrayHelper::toInteger($cid);
		$this->toggleCatPublish($cid,0);
	}

	function toggleCatPublish($cid,$newstate){
		$user =& JFactory::getUser();
		if (strtolower($user->usertype)!="super administrator" && strtolower($user->usertype)!="administrator"){
			$this->setRedirect( "index.php?option=$this->component&task=cpanel.cpanel", "Not Authorised - must be admin" );
			return;
		}

		foreach ($cid as $kid) {
			if ($kid>0){
				$cat = JTable::getInstance("category");
				$cat->load($kid);
				$cat->published = $newstate;
				$cat->store();
			}
		}
		$this->setRedirect( "index.php?option=".JEV_COM_COMPONENT."&task=categories.list", JText::_('JEV_ADMIN_CATSUPDATED'));

	}

}
