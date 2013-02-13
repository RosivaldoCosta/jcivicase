<?php
/*
 ****************************************************************
 Copyright (C) 2008-2009 Soft Ventures, Inc. All rights reserved.
 ****************************************************************
 * @package	Appointment Booking Pro - ABPro
 * @copyright	Copyright (C) 2008-2009 Soft Ventures, Inc. All rights reserved.
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

function rsappt_pro2BuildRoute(&$query){
	$segments = array();
	if(isset($query['view'])){
		$segments[] = $query['view'];
		unset( $query['view'] );
	}

	if(isset($query['controller'])){
		$segments[] = $query['controller'];
		unset( $query['controller'] );
	}

	if(isset($query['id'])){
		$segments[] = $query['id'];
        unset( $query['id'] );
    };

	if(isset($query['frompage'])){
		$segments[] = $query['frompage'];
		unset( $query['frompage'] );
	}
	
  	return $segments;
}

function rsappt_pro2ParseRoute(&$segments) {
	$vars = array();
	switch($segments[0]){
	   case 'frontdesk':
		   $vars['view'] = 'frontdesk';
		   break;
	   case 'advadmin':
		   $vars['view'] = 'advadmin';
		   break;
	   case 'admin':
		   $vars['view'] = 'admin';
		   $id = explode( ':', $segments[1] );
		   $vars['id'] = (int) $id[0];
		   break;
	   case 'admin_detail':
		   $vars['view'] = 'admin_detail';
		   $id = explode( ':', $segments[1] );
		   $vars['id'] = (int) $id[0];
		   break;

	}
  return $vars;
}
?>
