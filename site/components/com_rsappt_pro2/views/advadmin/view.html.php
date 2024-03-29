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
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

//DEVNOTE: import VIEW object class
jimport( 'joomla.application.component.view' );

/**
 [controller]View[controller]
 */
 
class adminViewadvadmin extends JView
{
	/**
	 * Custom Constructor
	 */
	function __construct( $config = array())
	{
	 /** set up global variable for sorting etc.
	  * $context is used in VIEW abd in MODEL
	  **/	  
	 
 	 global $context;
	 $context = 'adv_admin.list.';
 
 	 parent::__construct( $config );
	}
 

	/**
	 * Display the view
	 * take data from MODEL and put them into	
	 * reference variables
	 * 
	 * Go to MODEL, execute Method getData and
	 * result save into reference variable $items	 	 	 
	 * $items		= & $this->get( 'Data');
	 * - getData gets the course list from DB	 
	 *	  
	 * variable filter_order specifies what is the order by column
	 * variable filter_order_Dir sepcifies if the ordering is [ascending,descending]	 	 	 	  
	 */
    
	function display($tpl = null)
	{
		global $context;
	  	$mainframe = JFactory::getApplication();
		
		//DEVNOTE: set document title
		$document = & JFactory::getDocument();
//		$document->setTitle( JText::_('Appointment Booking Pro - My Bookings') );

		require_once(JPATH_COMPONENT.DS.'models'.DS.'requests.php');
		$model = new adminModelrequests;
 		if($model == null){
			echo "model = null";
			exit;
		}
   		$this->setModel($model, true);

		require_once(JPATH_COMPONENT.DS.'models'.DS.'resources.php');
		$model_resources = new adminModelresources;
 		if($model_resources == null){
			echo "model_resources = null";
			exit;
		}
   		$this->setModel($model_resources, false);

		require_once(JPATH_COMPONENT.DS.'models'.DS.'services.php');
		$model_services = new adminModelservices;
 		if($model_services == null){
			echo "model_services = null";
			exit;
		}
   		$this->setModel($model_services, false);

		require_once(JPATH_COMPONENT.DS.'models'.DS.'timeslots.php');
		$model_timeslots = new adminModeltimeslots;
 		if($model_timeslots == null){
			echo "model_timeslots = null";
			exit;
		}
   		$this->setModel($model_timeslots, false);

		require_once(JPATH_COMPONENT.DS.'models'.DS.'bookoffs.php');
		$model_bookoffs = new adminModelbookoffs;
 		if($model_bookoffs == null){
			echo "model_bookoffs = null";
			exit;
		}
   		$this->setModel($model_bookoffs, false);

		require_once(JPATH_COMPONENT.DS.'models'.DS.'paypal_transactions.php');
		$model_paypal_transactions = new adminModelpaypal_transactions;
 		if($model_paypal_transactions == null){
			echo "model_paypal_transactions = null";
			exit;
		}
   		$this->setModel($model_paypal_transactions, false);

		require_once(JPATH_COMPONENT.DS.'models'.DS.'coupons.php');
		$model_coupons = new adminModelcoupons;
 		if($model_coupons == null){
			echo "model_coupons = null";
			exit;
		}
   		$this->setModel($model_coupons, false);

		require_once(JPATH_COMPONENT.DS.'models'.DS.'extras.php');
		$model_extras = new adminModelextras;
 		if($model_extras == null){
			echo "model_extras = null";
			exit;
		}
   		$this->setModel($model_extras, false);


		//print_r($this->_models);

		$uri	=& JFactory::getURI();

		$req_filter_order     = $mainframe->getUserStateFromRequest( $context.'req_filter_order',      'req_filter_order', 	  'id_requests' );
		$req_filter_order_Dir = $mainframe->getUserStateFromRequest( $context.'req_filter_order_Dir',  'req_filter_order_Dir', '' );		

		$res_filter_order     = $mainframe->getUserStateFromRequest( $context.'res_filter_order',      'res_filter_order', 	  'name' );
		$res_filter_order_Dir = $mainframe->getUserStateFromRequest( $context.'res_filter_order_Dir',  'res_filter_order_Dir', '' );		

		$srv_filter_order     = $mainframe->getUserStateFromRequest( $context.'srv_filter_order',      'srv_filter_order', 	  'name' );
		$srv_filter_order_Dir = $mainframe->getUserStateFromRequest( $context.'srv_filter_order_Dir',  'srv_filter_order_Dir', '' );		

		$ts_filter_order     = $mainframe->getUserStateFromRequest( $context.'ts_filter_order',      'ts_filter_order', 	  'day_number' );
		$ts_filter_order_Dir = $mainframe->getUserStateFromRequest( $context.'ts_filter_order_Dir',  'ts_filter_order_Dir', '' );		

		$bo_filter_order     = $mainframe->getUserStateFromRequest( $context.'bo_filter_order',      'bo_filter_order', 	  'off_date' );
		$bo_filter_order_Dir = $mainframe->getUserStateFromRequest( $context.'bo_filter_order_Dir',  'bo_filter_order_Dir', '' );		

		$pp_filter_order     = $mainframe->getUserStateFromRequest( $context.'pp_filter_order',      'pp_filter_order', 	  'stamp' );
		$pp_filter_order_Dir = $mainframe->getUserStateFromRequest( $context.'pp_filter_order_Dir',  'pp_filter_order_Dir', '' );		

		$coup_filter_order     = $mainframe->getUserStateFromRequest( $context.'coup_filter_order',      'coup_filter_order', 	  'coupon_code' );
		$coup_filter_order_Dir = $mainframe->getUserStateFromRequest( $context.'coup_filter_order_Dir',  'coup_filter_order_Dir', '' );		

		$ext_filter_order     = $mainframe->getUserStateFromRequest( $context.'ext_filter_order',      'ext_filter_order', 	  'extras_label' );
		$ext_filter_order_Dir = $mainframe->getUserStateFromRequest( $context.'ext_filter_order_Dir',  'ext_filter_order_Dir', '' );		

		// get filters
		$filter_user_search	= $mainframe->getUserStateFromRequest( $context.'filter_user_search', 'user_search', "");
		$filter_startdate	= $mainframe->getUserStateFromRequest( $context.'filter_startdate', 'startdateFilter', date("Y-m-d"));
		$filter_enddate	= $mainframe->getUserStateFromRequest( $context.'filter_enddate', 'enddateFilter', "");
		$filter_category	= $mainframe->getUserStateFromRequest( $context.'filter_category', 'categoryFilter', "0");
		$filter_request_resource	= $mainframe->getUserStateFromRequest( $context.'filter_request_resource', 'request_resourceFilter', "");
		$filter_request_status	= $mainframe->getUserStateFromRequest( $context.'filter_request_status', 'request_status', "");

		$filter_service_resource	= $mainframe->getUserStateFromRequest( $context.'filter_service_resource', 'service_resourceFilter', "");

		$filter_timeslots_resource	= $mainframe->getUserStateFromRequest( $context.'filter_timeslots_resource', 'timeslots_resourceFilter', "");
		$filter_day_number	= $mainframe->getUserStateFromRequest( $context.'filter_day_number', 'day_numberFilter', "1");

		$filter_bookoffs_resource	= $mainframe->getUserStateFromRequest( $context.'filter_bookoffs_resource', 'bookoffs_resourceFilter', "");

		$filter_pp_startdate	= $mainframe->getUserStateFromRequest( $context.'filter_pp_startdate', 'ppstartdateFilter', date("Y-m-d"));
		$filter_pp_enddate	= $mainframe->getUserStateFromRequest( $context.'filter_pp_enddate', 'ppenddateFilter', "");

		$lists['order_req'] = $req_filter_order;  
		$lists['order_Dir_req'] = $req_filter_order_Dir;

		$lists['order_res'] = $res_filter_order;  
		$lists['order_Dir_res'] = $res_filter_order_Dir;

		$lists['order_srv'] = $srv_filter_order;  
		$lists['order_Dir_srv'] = $srv_filter_order_Dir;

		$lists['order_ts'] = $ts_filter_order;  
		$lists['order_Dir_ts'] = $ts_filter_order_Dir;

		$lists['order_bo'] = $bo_filter_order;  
		$lists['order_Dir_bo'] = $bo_filter_order_Dir;

		$lists['order_pp'] = $pp_filter_order;  
		$lists['order_Dir_pp'] = $pp_filter_order_Dir;

		$lists['order_coup'] = $coup_filter_order;  
		$lists['order_Dir_coup'] = $coup_filter_order_Dir;

		$lists['order_ext'] = $ext_filter_order;  
		$lists['order_Dir_ext'] = $ext_filter_order_Dir;

		$items			= & $this->get('Data2');
		//print_r($items);

		$items_res		= & $this->get('Data2', 'resources' );
		//print_r($items_res);
		//exit;

		$items_srv		= & $this->get('Data2', 'services' );
		//print_r($items_srv);
		//exit;

		$items_ts		= & $this->get('Data2', 'timeslots' );
		//print_r($items_ts);
		//exit;

		$items_bo		= & $this->get('Data2', 'bookoffs' );
		//print_r($items_bo);
		//exit;

		$items_pp		= & $this->get('Data', 'paypal_transactions' );
		//print_r($items_pp);
		//exit;

		$items_coup		= & $this->get('Data2', 'coupons' );
		//print_r($items_coup);
		//exit;

		$items_ext		= & $this->get('Data', 'extras' );
		//print_r($items_ext);
		//exit;

		$total			= & $this->get('Total');
		$pagination = & $this->get( 'Pagination' );

		$filter_resource  = $mainframe->getUserStateFromRequest( $context.'filter_resource', 'filter_resource', '');
		$filter_resource = & $this->get('filter_resource');
		
		$user = JFactory::getUser();
		$frompage  = 'advadmin';
		$this->assignRef('user_id',		$user->id);
		$this->assignRef('frompage',	$frompage);

		$this->assignRef('lists',		$lists);    
		$this->assignRef('items',		$items); 		
		$this->assignRef('pagination',	$pagination);
		$this->assignRef('request_url',	$uri->toString());
		$this->assignRef('filter_user_search', $filter_user_search);
		$this->assignRef('filter_startdate', $filter_startdate);
		$this->assignRef('filter_enddate', $filter_enddate);
		$this->assignRef('filter_category', $filter_category);
		$this->assignRef('filter_request_resource', $filter_request_resource);
		$this->assignRef('filter_request_status', $filter_request_status);

		$this->assignRef('items_res',	$items_res); 		

		$this->assignRef('items_srv',	$items_srv); 		
		$this->assignRef('filter_service_resource', $filter_service_resource);

		$this->assignRef('items_ts',	$items_ts); 		
		$this->assignRef('filter_timeslots_resource', $filter_timeslots_resource);
		$this->assignRef('filter_day_number', $filter_day_number);
		
		$this->assignRef('items_bo',	$items_bo); 		
		$this->assignRef('filter_bookoffs_resource', $filter_bookoffs_resource);

		$this->assignRef('items_pp',	$items_pp); 		
		$this->assignRef('filter_pp_startdate', $filter_pp_startdate);
		$this->assignRef('filter_pp_enddate', $filter_pp_enddate);

		$this->assignRef('items_coup',	$items_coup); 		

		$this->assignRef('items_ext',	$items_ext); 		

    	parent::display($tpl);
  }
}

?>
