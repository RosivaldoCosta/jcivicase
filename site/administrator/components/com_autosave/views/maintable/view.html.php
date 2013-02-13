<?php
/*
 ****************************************************************
 Copyright (C) 2008-2010 Soft Ventures, Inc. All rights reserved.
 ****************************************************************
 * @package	Appointment Booking Pro - ABPro
 * @copyright	Copyright (C) 2008-2010 Soft Ventures, Inc. All rights reserved.
 * @license	GNU/GPL, see http://www.gnu.org/licenses/gpl-2.0.html
 *
 * ABPro is distributed WITHOUT ANY WARRANTY, or implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * This header must not be removed. Additional contributions/changes
 * may be added to this header as long as no information is deleted.
 *
 ************************************************************
 The latest version of ABPro is available to subscribers at:
 http://www.appointmentbookingpro.com/
 ************************************************************



 Copyright (c) 2011-2012 Campbell Consulting Studios, LLC
 ExtScheduler v1.0
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view' );
jimport('joomla.html.pagination');
/**
 [controller]View[controller]
 */
 
class maintableViewmaintable extends JView
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

		//$post = JRequest::get( 'post' );
		//echo '<pre>'; print_r($post); 
 		
		$limitstart = JRequest::getVar('limitstart', '0', 'POST', 'int');
		$limit = JRequest::getVar('limit', '20', 'POST', 'int');
		$filter_activity = JRequest::getVar('filter_activity', '', 'POST', 'string');
		$filter_case = JRequest::getVar('filter_case', '', 'POST', 'string');
		$mode_view = JRequest::getVar('mode_view', 'Viewing', 'POST', 'string');
		
        $model = &$this->getModel();
		$saveList = $model->getList($limitstart, $limit, $filter_case, $filter_activity);
        $this->assignRef( 'saveList', $saveList );
        
        // page navigation
		$total = $model->getTotal($filter_case, $filter_activity);
		$pageNav = new JPagination( $total, $limitstart, $limit );

        $this->assignRef( 'pageNav', $pageNav->getListFooter() );
        
        // filters
        // get case select
		$fcase[] = JHTML::_('select.option',  '0', '- Select Case Id -' );
		$fcase = array_merge( $fcase, $model->getCaseIdsList() );
		// correct handle null value
		if(is_null($fcase[1]->value)){
			$fcase[1]->value = 'NULL'; $fcase[1]->text = 'NULL';
		}
		// select 0 if no filter
		if($filter_case){$select = $filter_case;}else{$select = 0;}
		$fcase_html	= JHTML::_('select.genericlist',   $fcase, 'filter_case', 'class="inputbox" size="1" onchange="document.adminForm.limitstart.value = 0;this.form.submit();"', 'value', 'text', "$select" );
        
        // get activity select 
		$factivity[] = JHTML::_('select.option',  '0', '- Select Activity -' );
		$factivity = array_merge( $factivity, $model->getActivitiesList() );
		if(is_null($factivity[1]->value)){
			$factivity[1]->value = 'NULL'; $factivity[1]->text = 'NULL';
		}
		if($filter_activity){$select = $filter_activity;}else{$select = 0;}
		$factivity_html	= JHTML::_('select.genericlist',   $factivity, 'filter_activity', 'class="inputbox" size="1" onchange="document.adminForm.limitstart.value = 0;this.form.submit();"', 'value', 'text', "$select" );
        
        $this->assignRef( 'filter_activity', $filter_activity );
        $this->assignRef( 'filter_case', $filter_case );
        $this->assignRef( 'fcase', $fcase_html );
        $this->assignRef( 'factivity', $factivity_html );

        // Define view mode 1 - viewing, 2 - edit
        $now_mode = $mode_view;
        if($mode_view == 1){
        	$next_mode = 2;
        }elseif($mode_view == 2){
        	$next_mode = 1;
        }

        $this->assignRef( 'nowMode', $now_mode );
        $this->assignRef( 'nextMode', $next_mode );

		parent::display($tpl);
  	}
}

?>
