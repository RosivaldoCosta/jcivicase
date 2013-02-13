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



defined( '_JEXEC' ) or die( 'Restricted access' );
include( JPATH_SITE."/components/com_rsappt_pro2/functions2.php" );


	header('Content-Type: text/xml'); 
	header("Cache-Control: no-cache, must-revalidate");
	//A date in the past
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	
	
	// recives the user's selected resource and date
	$startdate = JRequest::getVar('startdate');
	$starttime = JRequest::getVar('starttime');
	$enddate = JRequest::getVar('enddate');
	$endtime = JRequest::getVar('endtime');
	$resource = JRequest::getVar('res_id');
	
	$retval = "";
	
	// chcek for conflict in this timeslot
	$database = &JFactory::getDBO(); 
	$sql = 'SELECT * FROM #__sv_apptpro2_config';
	$database->setQuery($sql);
	$apptpro_config = NULL;
	$apptpro_config = $database -> loadObject();
	if ($database -> getErrorNum()) {
		echo "DB Err: ". $database -> stderr();
		exit;
	}

	$mystartdatetime = "STR_TO_DATE('".$startdate ." ". $starttime ."', '%Y-%m-%d %T')+ INTERVAL 1 SECOND";
	$myenddatetime = "STR_TO_DATE('".$enddate ." ". $endtime ."', '%Y-%m-%d %T')- INTERVAL 1 SECOND";
	$sql = "select *, ";
		if($apptpro_config->timeFormat == '12'){							
			$sql .=" DATE_FORMAT(endtime, '%l:%i %p') as display_endtime ";
		} else {
			$sql .=" DATE_FORMAT(endtime, '%H:%i') as display_endtime ";
		}	

	$sql .=	" from #__sv_apptpro2_requests "
		." where (resource = '". $resource ."')"
		." and (request_status = 'accepted' or request_status = 'pending' )"
		." and ((". $mystartdatetime ." >= STR_TO_DATE(CONCAT(DATE_FORMAT(startdate, '%Y-%m-%d') , DATE_FORMAT(starttime, ' %T')), '%Y-%m-%d %T') and ". $mystartdatetime ." <= STR_TO_DATE(CONCAT(DATE_FORMAT(enddate, '%Y-%m-%d') , DATE_FORMAT(endtime, ' %T')), '%Y-%m-%d %T'))"
		." or (". $myenddatetime ." >= STR_TO_DATE(CONCAT(DATE_FORMAT(startdate, '%Y-%m-%d') , DATE_FORMAT(starttime, ' %T')), '%Y-%m-%d %T') and ". $myenddatetime ." <= STR_TO_DATE(CONCAT(DATE_FORMAT(enddate, '%Y-%m-%d') , DATE_FORMAT(endtime, ' %T')), '%Y-%m-%d %T'))"
		." or (STR_TO_DATE(CONCAT(DATE_FORMAT(startdate, '%Y-%m-%d') , DATE_FORMAT(starttime, ' %T')), '%Y-%m-%d %T') >= ". $mystartdatetime ." and STR_TO_DATE(CONCAT(DATE_FORMAT(startdate, '%Y-%m-%d') , DATE_FORMAT(starttime, ' %T')), '%Y-%m-%d %T') <= ". $myenddatetime .")"
		." or (STR_TO_DATE(CONCAT(DATE_FORMAT(enddate, '%Y-%m-%d') , DATE_FORMAT(endtime, ' %T')), '%Y-%m-%d %T') >= ". $mystartdatetime ." and STR_TO_DATE(CONCAT(DATE_FORMAT(enddate, '%Y-%m-%d') , DATE_FORMAT(endtime, ' %T')), '%Y-%m-%d %T') <= ". $myenddatetime ."))";
		//print $sql; exit();
	$database->setQuery($sql);
	$overlaps = $database->loadObjectList();
	if ($database -> getErrorNum()) {
		$retval = $database -> stderr();
		return false;
	}

	if (count($overlaps) > 0){
		// need to determine best starttime for new booking, either at timeslot start or after end of overlap
		// if an existing booking ends in this timeslot, make that the start time.
		foreach($overlaps as $overlap){
			if($overlap->endtime >= $starttime and $overlap->endtime <= $endtime){
				$retval = $overlap->display_endtime."|".$overlap->endtime;
			} 
		}
		
	}


	echo $retval; 

	exit;	
	

?>