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

//	header('Content-Type: text/xml'); 
	header('Content-Type: text/html; charset=utf-8'); 
	header("Cache-Control: no-cache, must-revalidate");
	header("Pragma: No-cache");
	//A date in the past
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	
	// recieve the variables from the caller
	$user =& JFactory::getUser();

	$gridstarttime = JRequest::getVar('gridstarttime');
	$gridendtime = JRequest::getVar('gridendtime');
	$category = JRequest::getVar('category');
	$mode = JRequest::getVar('mode');
	$grid_date = JRequest::getVar('grid_date');
	$gridwidth = JRequest::getVar('gridwidth');
	$namewidth = JRequest::getVar('namewidth');
	$browser = JRequest::getVar('browser');
	$reg = JRequest::getVar('reg', 'No');
	$mobile = JRequest::getVar('mobile', 'No');
	$front_desk = JRequest::getVar('fd', 'No');
	
	$resource = JRequest::getVar('resource');
	$grid_days = JRequest::getVar('grid_days');

//	$params = JComponentHelper::getParams('com_languages');
//	setlocale(LC_ALL, $params->get("site", 'en-GB'));
	$lang =& JFactory::getLanguage();
	setlocale(LC_ALL, $lang->getTag());

	$database = &JFactory::getDBO(); 
	$sql = "SET NAMES 'utf8';";
	$database->setQuery($sql);
	if (!$database->query()) {
		echo $database -> getErrorMsg();
	}
	$sql = "SET lc_time_names = '".str_replace("-", "_", $lang->getTag())."';";		
	$database->setQuery($sql);
	if (!$database->query()) {
		echo $database -> getErrorMsg();
	}

	$ts_id = 0;

	$database = &JFactory::getDBO(); 
	
	if($mode == "single_day"){
		$grid_previous = date("Y-m-d", strtotime("-1 day", strtotime($grid_date)));
		$grid_next = date("Y-m-d", strtotime("+1 day", strtotime($grid_date)));
	} else {
// This works but then the grid is out of sync with the date selector		
//		// single resource, if the resource has disable_dates_before set and it is in the future, set grid_date to that date.
//		$sql = 'SELECT * FROM #__sv_apptpro2_resources where id = '.$resource;
//		$database->setQuery($sql);
//		$res_detail = NULL;
//		$res_detail = $database -> loadObject();
//		if(strpos($res_detail->disable_dates_before, "-")>0){
//			if(strtotime($res_detail->disable_dates_before." 23:59:00") > strtotime('now')){
//				$grid_date = $res_detail->disable_dates_before;
//			}
//		}

		$grid_previous = date("Y-m-d", strtotime("-".$grid_days." day", strtotime($grid_date)));
		$grid_next = date("Y-m-d", strtotime("+".$grid_days." day", strtotime($grid_date)));
	}

	
	
	// how many colums 700px - 100 (res name) = 600/num hours btween grid start and end
	$startpoint = intval(substr($gridstarttime,0,2)); 
	$endpoint = intval(substr($gridendtime,0,2)); 
	$columncount = $endpoint - $startpoint;
	if($columncount <1){echo JText::_('RS1_GAD_SCRN_GRID_START_BEFORE_END'); exit;}
	$colwidth = ($gridwidth-$namewidth)/$columncount;
	$window_start_minute = $startpoint * 60;
	$window_end_minute = $endpoint * 60;
	// We need to position each timeslot withing the table row. To do this we need to divide the tabel row into px/minutes
	// Once we know how many px/min, we can place timeslots the righ number of px from the left.
	$pxminute = ($gridwidth-$namewidth)/($window_end_minute - $window_start_minute);
	
	$sql = 'SELECT * FROM #__sv_apptpro2_config';
	$database->setQuery($sql);
	$apptpro_config = NULL;
	$apptpro_config = $database -> loadObject();
	if ($database -> getErrorNum()) {
		echo "DB Err: ". $database -> stderr();
		exit;
	}
	// images
	$booked_insert = "<img src='".$apptpro_config->gad_booked_image."'>";
	$timeslot_insert = "<img src='".$apptpro_config->gad_available_image."' border='0'></a>";
	
	if($mode == "single_day"){
		if(WINDOWS){
			$display_grid_date = iconv('ISO-8859-2', 'UTF-8',strftime("%d-%b-%Y",strtotime($grid_date)));
		} else {
			$display_grid_date = strftime("%d-%b-%Y",(strtotime($grid_date)));
		}
	} else {
		$display_grid_date = "&nbsp;";
	}
	
	?>
	<table id="sv_gad_outer_table" width="<?php echo $gridwidth?>" border="0" cellpadding="0" cellspacing="0">
              <?php 
			  	echo "<div class='sv_gad_master_container' style='position: relative; width:".$gridwidth."px;'>";

				//*************************************************************
			  	// header row start			
				//*************************************************************
			  	echo "<tr><td width='".$namewidth."' align='center'><span id='display_grid_date' >".$display_grid_date."</span></td><td colspan='".$columncount."' >".
				"<div class='sv_gad_row_wrapper' style='position: relative; width:".($gridwidth-$namewidth)."px; '>";
				for($i=0; $i<$columncount; $i++){
					if($mobile == "Yes"){
						$left = round(($i)*60*$pxminute);
						$width = round(60*$pxminute);
					} else {
						$left = ($i)*60*$pxminute;
						$width = 60*$pxminute;
					}
					$strTime = "";
					if($apptpro_config->timeFormat == "12"){
						$timedisplay = ($i+$startpoint);
						if($timedisplay == 12){
							$strTime = JText::_('RS1_INPUT_SCRN_NOON');
						} else if($timedisplay > 12){
							$strTime = strval($timedisplay - 12);
							$strTime .= " PM";
						} else {
							$strTime = strval($timedisplay);
							$strTime .= " AM";
						}
					} else {				
						$strTime = strval($i+$startpoint);
						if($mobile != "Yes"){
							$strTime .= ":00";
						}
					}
					echo "<div class='sv_gad_timeslot_header' style='left:".$left."px; width:".$width."px; position:absolute; float:left".($mobile=="Yes"?"; font-size:11px":"")."'>&nbsp;".$strTime."</div>"; 
				}
				echo "<div></td></tr>"; // end of header row

				//*************************************************************
			  	// header row end			
				//*************************************************************
						

				//resource rows
				// **********************************************************
				// if mode = single_day we show all resources, for $grid_date
				// **********************************************************
				// Because each resource can have different timeslots on a given day, 
				// each row (or resource) will need to be a seperate table ;-(
				if($reg=='No'){
					$andClause = " AND access != 'registered_only' ";
				} else {
					$andClause = " AND access != 'public_only' ";
				}
				if($front_desk == "Yes"){
					// only resources for which user is res admin
					$andClause .= " AND resource_admins LIKE '%|".$user->id."|%' ";
				}
				
				if($mode == "single_day"){
					// get resources
					$sql = "SELECT * FROM #__sv_apptpro2_resources WHERE published=1 ".$andClause;
					if($category != 0){
						$sql .=" AND category_id = ".$category." ";
					}
					$sql .=" ORDER BY ordering";
					//echo $sql;
					
					$database->setQuery($sql);
					$res_rows = $database -> loadObjectList();
					if ($database -> getErrorNum()) {
						echo $database -> stderr();
						return false;
					}

					// get get bookings
					$sql = "SELECT * FROM #__sv_apptpro2_requests WHERE startdate = '".$grid_date."'";
					$sql .=" AND (request_status='accepted' OR request_status='pending') ORDER BY starttime";
					$database->setQuery($sql);
					$bookings = $database -> loadObjectList();
					if ($database -> getErrorNum()) {
						echo $database -> stderr();
						return false;
					}

					// get get part day book-offs
					$sql = "SELECT * FROM #__sv_apptpro2_bookoffs WHERE off_date = '".$grid_date."'";
					$sql .=" AND full_day='No' AND published=1 ORDER BY bookoff_starttime";
					$database->setQuery($sql);
					$part_day_bookoffs = $database -> loadObjectList();
					if ($database -> getErrorNum()) {
						echo $database -> stderr();
						return false;
					}


					// walk through resources, getting timeslots and bookings
					if(count($res_rows) > 0){
						foreach($res_rows as $res_row){
						
						$sql = 'SELECT * FROM #__sv_apptpro2_resources where id_resources = '.$res_row->id_resources.' ORDER BY ordering';
						$database->setQuery($sql);
						$res_detail = NULL;
						$res_detail = $database -> loadObject();
						if ($database -> getErrorNum()) {
							echo "DB Err: ". $database -> stderr();
							exit;
						}

						// get timeslots for $grid_date
						$weekday = getdate(strtotime($grid_date)); 
						$weekday["wday"];

						$sql = "SELECT *, ";
						if($apptpro_config->timeFormat == '12'){							
						$sql .=" DATE_FORMAT(timeslot_starttime, '%l:%i %p') as display_timeslot_starttime, ".
							" DATE_FORMAT(timeslot_endtime, '%l:%i %p') as display_timeslot_endtime ";						
						} else {
						$sql .=" DATE_FORMAT(timeslot_starttime, '%H:%i') as display_timeslot_starttime, ".
							" DATE_FORMAT(timeslot_endtime, '%H:%i') as display_timeslot_endtime ";						
						}	
						if($res_detail->timeslots == "Global"){
							$sql .=	"FROM #__sv_apptpro2_timeslots WHERE published=1 AND (resource_id is null or resource_id = 0) AND day_number = ".$weekday["wday"].
								" AND (start_publishing IS NULL OR start_publishing = '0000-00-00' OR '".$grid_date."' >= start_publishing ) ".
								" AND (end_publishing IS NULL OR start_publishing = '0000-00-00' OR '".$grid_date."' <= end_publishing ) ".
								" ORDER BY timeslot_starttime";
						} else {
							$sql .=	"FROM #__sv_apptpro2_timeslots WHERE published=1 AND resource_id = ".$res_row->id_resources." AND day_number = ".$weekday["wday"].
								" AND (start_publishing IS NULL OR start_publishing = '0000-00-00' OR '".$grid_date."' >= start_publishing ) ".
								" AND (end_publishing IS NULL OR start_publishing = '0000-00-00' OR '".$grid_date."' <= end_publishing ) ".
								" ORDER BY timeslot_starttime";
						} 
											
						//echo "!!".$sql;
						//exit(0);
						$database->setQuery($sql);
						$slot_rows = $database -> loadObjectList();
						if ($database -> getErrorNum()) {
							echo $database -> stderr();
							exit;
						}
						
						
						echo "<tr ><td align='center' class='sv_gad_timeslot_yaxis_header'><a href=javascript:changeMode(".$res_row->id_resources.")>".JText::_(stripslashes($res_row->name))."</a></td>".
							"<td class='sv_gad_row_wrapper' colspan='".$columncount."'>".
								"<div class='sv_gad_row_wrapper' style='position: relative; width:".($gridwidth-$namewidth)."px; '>";
//							if(showrow($res_row, $grid_date, $weekday["wday"])){
						$sr = showrow($res_row, $grid_date, $weekday["wday"]);
						if($front_desk == "Yes"){
							$sr = true;
						} 	
						if($sr == true){ 
								// Timeslots first
								foreach($slot_rows as $slot_row){
									if($apptpro_config->show_available_seats == "Yes" && $res_row->max_seats>1){
										$currentcount = getCurrentSeatCount($grid_date, $slot_row->timeslot_starttime, $slot_row->timeslot_endtime, $res_row->id_resources);
										$timeslot_insert = strval($res_row->max_seats - $currentcount)."</a>";
									} else {
										if($slot_row->timeslot_description != ""){
											$timeslot_insert = JText::_($slot_row->timeslot_description)."</a>";
										} else {
											$timeslot_insert = "<img src='".$apptpro_config->gad_available_image."' border='0'></a>";
										}
									}
									$ts_id ++;
									// get start minute, end minute								
									$slotstart_minute = getMinute($slot_row->timeslot_starttime);
									$slotend_minute = getMinute($slot_row->timeslot_endtime);
									if($slotstart_minute >= $window_start_minute && $slotend_minute <=$window_end_minute){
										if($mobile == "Yes"){
											$left = round(($slotstart_minute-$window_start_minute)*$pxminute);
											$width = round(($slotend_minute-$slotstart_minute - 2)*$pxminute);
										} else {
											$left = ($slotstart_minute-$window_start_minute)*$pxminute;
											$width = ($slotend_minute-$slotstart_minute - 2)*$pxminute;
										}
										echo "<div id='ts".$ts_id."' class='sv_gad_timeslot_available' style='left:".$left."px; width:".$width."px; position:absolute; float:left; text-align:center;'>".
										"<a class='sv_gad_timeslot_clickable' href=# onclick='selectTimeslot(\"".
										$res_row->id_resources."|".
										base64_encode(JText::_($res_row->name))."|".
										$grid_date."|";
										if(WINDOWS){
											echo base64_encode(iconv('ISO-8859-2', 'UTF-8',strftime("%a %d-%b-%Y",(DateAdd("d", $day, strtotime($grid_date))))))."|";
										} else {
											echo base64_encode(strftime("%a %d-%b-%Y",(DateAdd("d", $day, strtotime($grid_date)))))."|";
										}

										echo $slot_row->timeslot_starttime."|".
										base64_encode($slot_row->display_timeslot_starttime)."|".
										$slot_row->timeslot_endtime."|".
										base64_encode($slot_row->display_timeslot_endtime)."|ts".$ts_id."\",event);return false;'>".
										$timeslot_insert.
										"</div>\n"; 
									} else if($slotend_minute > $window_end_minute){
										// goes beyond window
										if($slotstart_minute < $window_end_minute){
											// but starts in window
											$left = ($slotstart_minute-$window_start_minute)*$pxminute;
											$width = ($window_end_minute - $slotstart_minute - 4)*$pxminute;								
											echo "<div id='ts".$ts_id."' class='sv_gad_timeslot_available' style='left:".$left."px; width:".$width."px; position:absolute; float:left; text-align:right'>>></div>\n"; 								
										} // else starts beyond window, do not show
									} else if($slotstart_minute < $window_start_minute){	
										// starts before window
										$left = 0;
										// width = full width - amount before window									
										$width = (($slotend_minute-$slotstart_minute) - ($window_start_minute - $slotstart_minute) )*$pxminute;
										echo "<div id='ts".$ts_id."' class='sv_gad_timeslot_available' style='left:".$left."px; width:".$width."px; position:absolute; float:left; text-align:left'><<</div>\n"; 
									} else {
										// bigger than grid, fill'er up
										$left = 0;
										// width = full width - amount before window									
										$width = (window_end_minute - $window_start_minute)*$pxminute;
										echo "<div id='ts".$ts_id."' class='sv_gad_timeslot_available' style='left:".$left."px; width:".$width."px; position:absolute; float:left; text-align:center'><< >></div>\n"; 									
									}									
								}
								// bookings now
								if(count($bookings) > 0){
									foreach($bookings as $booking){
										if($booking->resource == $res_row->id_resources){
											// booking is for this resource
											// has max_seats been reached?
											if(fullyBooked($booking, $res_row, $apptpro_config)){
												$bookingstart_minute = getMinute($booking->starttime);
												$bookingend_minute = getMinute($booking->endtime);
												if($bookingstart_minute >= $window_start_minute && $bookingend_minute <=$window_end_minute){
													$left = ($bookingstart_minute-$window_start_minute)*$pxminute;
													$width = ($bookingend_minute-$bookingstart_minute-2)*$pxminute;
														if($booking->request_status == 'accepted'){
															echo "<div class='sv_gad_timeslot_booked'  style='left:".$left."px; width:".$width."px; position:absolute; float:left; text-align:center;' >".$booked_insert."</div>"; 
														} else {
															echo "<div class='sv_gad_timeslot_pending' style='left:".$left."px; width:".$width."px; position:absolute; float:left; text-align:center;' >".$booked_insert."</div>"; 
														}																		
												} else if($bookingend_minute > $window_end_minute){
													// goes beyond window
													if($slotstart_minute < $window_end_minute){
														// but starts in window
														$left = ($bookingstart_minute-$window_start_minute)*$pxminute;
														$width = ($window_end_minute - $bookingstart_minute - 4)*$pxminute;								
														if($booking->request_status == 'accepted'){
															echo "<div class='sv_gad_timeslot_booked'  style='left:".$left."px; width:".$width."px; position:absolute; float:left; text-align:right'>>></div>"; 								
														} else {
															echo "<div class='sv_gad_timeslot_pending' style='left:".$left."px; width:".$width."px; position:absolute; float:left; text-align:right'>>></div>"; 								
														}																		
													} // else starts beyond window, do not show
												} else if($bookingstart_minute < $window_start_minute){	
													// starts before window
													$left = 0;
													// width = full width - amount before window									
													$width = (($bookingend_minute-$bookingstart_minute) - ($window_start_minute - $bookingstart_minute))*$pxminute;
													if($booking->request_status == 'accepted'){
														echo "<div class='sv_gad_timeslot_booked'  style='left:".$left."px; width:".$width."px; position:absolute; float:left; text-align:left'><<</div>"; 
													} else {
														echo "<div class='sv_gad_timeslot_pending' style='left:".$left."px; width:".$width."px; position:absolute; float:left; text-align:left'><<</div>"; 
													}																		
												} else {
													// bigger than grid, fill'er up
													$left = 0;
													// width = full width - amount before window									
													$width = (window_end_minute - $window_start_minute)*$pxminute;
													if($booking->request_status == 'accepted'){
														echo "<div class='sv_gad_timeslot_booked'  style='left:".$left."px; width:".$width."px; position:absolute; float:left; text-align:center'><< >></div>"; 									
													} else {
														echo "<div class='sv_gad_timeslot_pending' style='left:".$left."px; width:".$width."px; position:absolute; float:left; text-align:center'><< >></div>"; 									
													}																		
												}				
											}
										}
									}
								}
								// part day book-offs now
								if(count($part_day_bookoffs) > 0){
									foreach($part_day_bookoffs as $part_day_bookoff){
										if($part_day_bookoff->resource_id == $res_row->id_resources){
												$bookingstart_minute = getMinute($part_day_bookoff->bookoff_starttime);
												$bookingend_minute = getMinute($part_day_bookoff->bookoff_endtime);
												if($bookingstart_minute >= $window_start_minute && $bookingend_minute <=$window_end_minute){
													$left = ($bookingstart_minute-$window_start_minute)*$pxminute;
													$width = ($bookingend_minute-$bookingstart_minute-2)*$pxminute;
													echo "<div class='sv_gad_timeslot_book-off'  style='left:".$left."px; width:".$width."px; position:absolute; float:left; text-align:center;' >".JText::_(stripslashes($part_day_bookoff->description))."</div>"; 
												} else if($bookingend_minute > $window_end_minute){
													// goes beyond window
													if($slotstart_minute < $window_end_minute){
														// but starts in window
														$left = ($bookingstart_minute-$window_start_minute)*$pxminute;
														$width = ($window_end_minute - $bookingstart_minute - 4)*$pxminute;								
														echo "<div class='sv_gad_timeslot_book-off'  style='left:".$left."px; width:".$width."px; position:absolute; float:left; text-align:right'>>></div>"; 								
													} // else starts beyond window, do not show
												} else if($bookingstart_minute < $window_start_minute){	
													// starts before window
													$left = 0;
													// width = full width - amount before window									
													$width = (($bookingend_minute-$bookingstart_minute) - ($window_start_minute - $bookingstart_minute))*$pxminute;
													echo "<div class='sv_gad_timeslot_book-off'  style='left:".$left."px; width:".$width."px; position:absolute; float:left; text-align:left'><<</div>"; 
												} else {
													// bigger than grid, fill'er up
													$left = 0;
													// width = full width - amount before window									
													$width = (window_end_minute - $window_start_minute)*$pxminute;
													echo "<div class='sv_gad_timeslot_book-off'  style='left:".$left."px; width:".$width."px; position:absolute; float:left; text-align:center'><< >></div>"; 									
												}				
										}
									}
								
								}

							} else {
							// this is a no-show row, if it is a book-off, show description 
							$bo = getBookOffDescription($res_row, $grid_date);
							if($bo->description !=""){
								echo "<div class='sv_gad_timeslot_book-off' style='width:".$gridwidth ."px; text-align:center'>".JText::_(stripslashes($bo->description))."</div>";
							} else {
								// if non-work_day message is set show it - only show for future days
								if($res_row->non_work_day_message != "" && (strtotime($grid_date) >= strtotime('now'))){
									echo "<div class='sv_gad_non_work_day' style='width:".$gridwidth ."px; text-align:center'>".JText::_(stripslashes($res_row->non_work_day_message))."</div>";
								}
							}
							
						}
							echo "<div></td></tr>";
						}
					}				

				} else {
				// **********************************************************
				// if mode = single_resource we show $show_days of $resource
				// **********************************************************
					// get resource details
					$sql = "SELECT * FROM #__sv_apptpro2_resources WHERE id_resources=".$resource;
					$database->setQuery($sql);
					$res_detail = $database -> loadObject();
					if ($database -> getErrorNum()) {
						echo $database -> stderr();
						return false;
					}

					// get get bookings
					$sql = "SELECT * FROM #__sv_apptpro2_requests WHERE resource=".$resource.
					" AND (request_status='accepted' OR request_status='pending') AND startdate >= '".$grid_date."' ".
					" AND startdate <= DATE_ADD(startdate,INTERVAL ".$grid_days." DAY) ".
					" ORDER BY startdate";
					$database->setQuery($sql);
					$bookings = $database -> loadObjectList();
					if ($database -> getErrorNum()) {
						echo $database -> stderr();
						return false;
					}

					// get get part day book-offs
					$sql = "SELECT * FROM #__sv_apptpro2_bookoffs WHERE resource_id=".$resource. " AND off_date >= '".$grid_date."'";
					$sql .=" AND off_date <= DATE_ADD(off_date,INTERVAL ".$grid_days." DAY) ";
					$sql .=" AND full_day='No' AND published=1 ORDER BY off_date";
					$database->setQuery($sql);
					$part_day_bookoffs = $database -> loadObjectList();
					if ($database -> getErrorNum()) {
						echo $database -> stderr();
						return false;
					}				
					
					// walk through days, getting timeslots and bookings
					for($day=0; $day<$grid_days; $day++){	
			$lang =& JFactory::getLanguage();
			setlocale(LC_ALL, $lang->getTag()); 
			
					if($mobile == "Yes"){
						if(WINDOWS){
							$dayname = iconv('ISO-8859-2', 'UTF-8',strftime("%a %d/%b",(DateAdd("d", $day, strtotime($grid_date)))));
						} else {
							$dayname = strftime("%a %d/%b",(DateAdd("d", $day, strtotime($grid_date))));
						}
					} else {
						if(WINDOWS){
							$dayname = iconv('ISO-8859-2', 'UTF-8',strftime("%a %d-%b-%Y",(DateAdd("d", $day, strtotime($grid_date)))));
						} else {
							$dayname = strftime("%a %d-%b-%Y",(DateAdd("d", $day, strtotime($grid_date))));
						}
					}
					$weekday = date("w",(DateAdd("d", $day, strtotime($grid_date))));
					$strDate = date("Y-m-d",(DateAdd("d", $day, strtotime($grid_date))));

					$y_axis_header = "<tr><td align='center' class='sv_gad_timeslot_yaxis_header'><a href=javascript:changeMode2('".$strDate."')> ".JText::_($dayname)."</a></td><td colspan='".$columncount."'>".
						"<div class='sv_gad_row_wrapper' style='position: relative; width:".($gridwidth-$namewidth)."px; '>";

					if($res_detail->non_work_day_hide == "No"){
						// always show the row
						echo $y_axis_header;						
					}
					
//						if(showrow($res_detail, $strDate, $weekday)){
					$sr = showrow($res_detail, $strDate, $weekday);
					if($front_desk == "Yes"){
						$sr = true;
					} 	
					if($sr == true){ 
						if($res_detail->non_work_day_hide == "Yes"){
							// only show if row has $sr==true
							echo $y_axis_header;
						}
						// get timeslots for each day
						$slots_day = DateAdd("d", $day, strtotime($grid_date));						

							
							$sql = "SELECT *, ";
							if($apptpro_config->timeFormat == '12'){							
							$sql .=" DATE_FORMAT(timeslot_starttime, '%l:%i %p') as display_timeslot_starttime, ".
								" DATE_FORMAT(timeslot_endtime, '%l:%i %p') as display_timeslot_endtime ";						
							} else {
							$sql .=" DATE_FORMAT(timeslot_starttime, '%H:%i') as display_timeslot_starttime, ".
								" DATE_FORMAT(timeslot_endtime, '%H:%i') as display_timeslot_endtime ";						
							}	
							if($res_detail->timeslots == "Global"){
								$sql .=	"FROM #__sv_apptpro2_timeslots WHERE published=1 AND (resource_id is null or resource_id = 0) AND day_number = ".$weekday.
									" AND (start_publishing IS NULL OR start_publishing = '0000-00-00' OR '".$strDate."' >= start_publishing ) ".
									" AND (end_publishing IS NULL OR start_publishing = '0000-00-00' OR '".$strDate."' <= end_publishing ) ".
									" ORDER BY timeslot_starttime";
							} else {
								$sql .=	"FROM #__sv_apptpro2_timeslots WHERE published=1 AND resource_id = ".$resource." AND day_number = ".$weekday.
									" AND (start_publishing IS NULL OR start_publishing = '0000-00-00' OR '".$strDate."' >= start_publishing ) ".
									" AND (end_publishing IS NULL OR start_publishing = '0000-00-00' OR '".$strDate."' <= end_publishing ) ".
									" ORDER BY timeslot_starttime";
							} 										
							//echo $sql;
							//exit;
							$database->setQuery($sql);
							$slot_rows = $database -> loadObjectList();
							if ($database -> getErrorNum()) {
								echo $database -> stderr();
								exit;
							}
							// Timeslots first
							foreach($slot_rows as $slot_row){
								if($apptpro_config->show_available_seats == "Yes" && $res_detail->max_seats>1){
									$row_date = date("Y-m-d",(DateAdd("d", $day, strtotime($grid_date))));
									$currentcount = getCurrentSeatCount($row_date, $slot_row->timeslot_starttime, $slot_row->timeslot_endtime, $res_detail->id_resources);
									$timeslot_insert = strval($res_detail->max_seats - $currentcount)."</a>";
								} else {
									if($slot_row->timeslot_description != ""){
										$timeslot_insert = JText::_($slot_row->timeslot_description)."</a>";
									} else {
										$timeslot_insert = "<img src='".$apptpro_config->gad_available_image."' border='0'></a>";
									}
								}
								$ts_id ++;
								// get start minute, end minute								
								$slotstart_minute = getMinute($slot_row->timeslot_starttime);
								$slotend_minute = getMinute($slot_row->timeslot_endtime);
								if($slotstart_minute >= $window_start_minute && $slotend_minute <=$window_end_minute){
									if($mobile == "Yes"){
										$left = round(($slotstart_minute-$window_start_minute)*$pxminute);
										$width = round(($slotend_minute-$slotstart_minute - 2)*$pxminute);
									} else {
										$left = ($slotstart_minute-$window_start_minute)*$pxminute;
										$width = ($slotend_minute-$slotstart_minute - 2)*$pxminute;
									}
										echo "<div id='ts".$ts_id."' class='sv_gad_timeslot_available' style='left:".$left."px; width:".$width."px; position:absolute; float:left; text-align:center;'>".
//										"<a class='sv_gad_timeslot_clickable' href=javascript:selectTimeslot('".
										"<a class='sv_gad_timeslot_clickable' href=# onclick=\"selectTimeslot('".
										$res_detail->id_resources."|".
										base64_encode(JText::_($res_detail->name))."|".
										$strDate."|";
										if(WINDOWS){
											echo base64_encode(iconv('ISO-8859-2', 'UTF-8',strftime("%a %d-%b-%Y",(DateAdd("d", $day, strtotime($grid_date))))))."|";
										} else {
											echo base64_encode(strftime("%a %d-%b-%Y",(DateAdd("d", $day, strtotime($grid_date)))))."|";
										}
										echo $slot_row->timeslot_starttime."|".
										base64_encode($slot_row->display_timeslot_starttime)."|".
										$slot_row->timeslot_endtime."|".
										base64_encode($slot_row->display_timeslot_endtime)."|ts".$ts_id."', event);return false;\">".
										$timeslot_insert.
										"</div>\n"; 
								} else if($slotend_minute > $window_end_minute){
									// goes beyond window
									if($slotstart_minute < $window_end_minute){
										// but starts in window
										$left = ($slotstart_minute-$window_start_minute)*$pxminute;
										$width = ($window_end_minute - $slotstart_minute - 4)*$pxminute;								
										echo "<div id='ts".$ts_id."' class='sv_gad_timeslot_available' style='left:".$left."px; width:".$width."px; position:absolute; float:left; text-align:right'>>></div>\n"; 								
									} // else starts beyond window, do not show
								} else if($slotstart_minute < $window_start_minute){	
									// starts before window
									$left = 0;
									// width = full width - amount before window									
									$width = (($slotend_minute-$slotstart_minute) - ($window_start_minute - $slotstart_minute) )*$pxminute;
									echo "<div id='ts".$ts_id."' class='sv_gad_timeslot_available' style='left:".$left."px; width:".$width."px; position:absolute; float:left; text-align:left'><<</div>\n"; 
								} else {
									// bigger than grid, fill'er up
									$left = 0;
									// width = full width - amount before window									
									$width = (window_end_minute - $window_start_minute)*$pxminute;
									echo "<div id='ts".$ts_id."' class='sv_gad_timeslot_available' style='left:".$left."px; width:".$width."px; position:absolute; float:left; text-align:center'><< >></div>\n"; 									
								}									
							}
	
							// bookings now
							if(count($bookings) > 0){
								foreach($bookings as $booking){
									if($booking->startdate == date("Y-m-d",(DateAdd("d", $day, strtotime($grid_date))))){
										// booking is for this resource
										if(fullyBooked($booking, $res_detail, $apptpro_config)){
											$bookingstart_minute = getMinute($booking->starttime);
											$bookingend_minute = getMinute($booking->endtime);
											if($bookingstart_minute >= $window_start_minute && $bookingend_minute <=$window_end_minute){
												$left = ($bookingstart_minute-$window_start_minute)*$pxminute;
												$width = ($bookingend_minute-$bookingstart_minute-2)*$pxminute;
												if($booking->request_status == 'accepted'){
													echo "<div class='sv_gad_timeslot_booked'  style='left:".$left."px; width:".$width."px; position:absolute; float:left; text-align:center;' >".$booked_insert."</div>"; 
												} else {
													echo "<div class='sv_gad_timeslot_pending' style='left:".$left."px; width:".$width."px; position:absolute; float:left; text-align:center;' >".$booked_insert."</div>"; 
												}																		
											} else if($bookingend_minute > $window_end_minute){
												// goes beyond window
												if($slotstart_minute < $window_end_minute){
													// but starts in window
													$left = ($bookingstart_minute-$window_start_minute)*$pxminute;
													$width = ($window_end_minute - $bookingstart_minute - 4)*$pxminute;	
													if($booking->request_status == 'accepted'){
														echo "<div class='sv_gad_timeslot_booked'  style='left:".$left."px; width:".$width."px; position:absolute; float:left; text-align:right'>>></div>"; 								
													} else {
														echo "<div class='sv_gad_timeslot_pending' style='left:".$left."px; width:".$width."px; position:absolute; float:left; text-align:right'>>></div>"; 								
													}																		
												} // else starts beyond window, do not show
											} else if($bookingstart_minute < $window_start_minute){	
												// starts before window
												$left = 0;
												// width = full width - amount before window									
												$width = (($bookingend_minute-$bookingstart_minute) - ($window_start_minute - $bookingstart_minute))*$pxminute;
												if($booking->request_status == 'accepted'){
													echo "<div class='sv_gad_timeslot_booked'  style='left:".$left."px; width:".$width."px; position:absolute; float:left; text-align:left'><<</div>"; 
												} else {
													echo "<div class='sv_gad_timeslot_pending' style='left:".$left."px; width:".$width."px; position:absolute; float:left; text-align:left'><<</div>"; 
												}																		
											} else {
												// bigger than grid, fill'er up
												$left = 0;
												// width = full width - amount before window									
												$width = (window_end_minute - $window_start_minute)*$pxminute;
												if($booking->request_status == 'accepted'){
													echo "<div class='sv_gad_timeslot_booked'  style='left:".$left."px; width:".$width."px; position:absolute; float:left; text-align:center'><< >></div>"; 									
												} else {
													echo "<div class='sv_gad_timeslot_pending' style='left:".$left."px; width:".$width."px; position:absolute; float:left; text-align:center'><< >></div>"; 									
												}																		
											}				
										}
									}
								}
							}
							
							// part day book-offs now
							if(count($part_day_bookoffs) > 0){
								foreach($part_day_bookoffs as $part_day_bookoff){
									if($part_day_bookoff->off_date == date("Y-m-d",(DateAdd("d", $day, strtotime($grid_date))))){
											$bookingstart_minute = getMinute($part_day_bookoff->bookoff_starttime);
											$bookingend_minute = getMinute($part_day_bookoff->bookoff_endtime);
											if($bookingstart_minute >= $window_start_minute && $bookingend_minute <=$window_end_minute){
												$left = ($bookingstart_minute-$window_start_minute)*$pxminute;
												$width = ($bookingend_minute-$bookingstart_minute-2)*$pxminute;
												echo "<div class='sv_gad_timeslot_book-off'  style='left:".$left."px; width:".$width."px; position:absolute; float:left; text-align:center;' >".JText::_($part_day_bookoff->description)."</div>"; 
											} else if($bookingend_minute > $window_end_minute){
												// goes beyond window
												if($slotstart_minute < $window_end_minute){
													// but starts in window
													$left = ($bookingstart_minute-$window_start_minute)*$pxminute;
													$width = ($window_end_minute - $bookingstart_minute - 4)*$pxminute;	
													echo "<div class='sv_gad_timeslot_book-off'  style='left:".$left."px; width:".$width."px; position:absolute; float:left; text-align:right'>>></div>"; 								
												} // else starts beyond window, do not show
											} else if($bookingstart_minute < $window_start_minute){	
												// starts before window
												$left = 0;
												// width = full width - amount before window									
												$width = (($bookingend_minute-$bookingstart_minute) - ($window_start_minute - $bookingstart_minute))*$pxminute;
												echo "<div class='sv_gad_timeslot_book-off'  style='left:".$left."px; width:".$width."px; position:absolute; float:left; text-align:left'><<</div>"; 
											} else {
												// bigger than grid, fill'er up
												$left = 0;
												// width = full width - amount before window									
												$width = (window_end_minute - $window_start_minute)*$pxminute;
												echo "<div class='sv_gad_timeslot_book-off'  style='left:".$left."px; width:".$width."px; position:absolute; float:left; text-align:center'><< >></div>"; 									
											}				
									}
								}						
							}							
						} else {
							// this is a no-show row, if it is a book-off, show description 
							$bo = getBookOffDescription($res_detail, $strDate);
							if($bo->description !=""){
								if($res_detail->non_work_day_hide == "Yes"){
									// only show if row has $sr==true
									echo $y_axis_header;
								}
								echo "<div class='sv_gad_timeslot_book-off' style='width:".$gridwidth ."px; text-align:center'>".JText::_(stripslashes($bo->description))."</div>";
							} else {
								// if non-work_day message is set show it - future only
								if($res_detail->non_work_day_message != "" && (strtotime($grid_date) >= strtotime('now'))){
									if($res_detail->non_work_day_hide == "Yes"){
										// only show if row has $sr==true
										echo $y_axis_header;
									}
									echo "<div class='sv_gad_non_work_day' style='width:".$gridwidth ."px; text-align:center'>".JText::_(stripslashes($res_detail->non_work_day_message))."</div>";
								}
							}
							
						}
					
					}
				
				}
				
				echo "</div>"; // end master container 
				
				//*************************************************************
			  	// footer row start
				//*************************************************************
			  	echo "<tr><td width='".$namewidth."'>&nbsp;</td><td colspan='".$columncount."' >".
				"<div class='sv_gad_row_wrapper' style='position: relative; width:".($gridwidth-$namewidth)."px; '>";
				for($i=0; $i<$columncount; $i++){
					if($mobile == "Yes"){
						$left = round(($i)*60*$pxminute);
						$width = round(60*$pxminute);
					} else {
						$left = ($i)*60*$pxminute;
						$width = 60*$pxminute;
					}
					$strTime = "";
					if($apptpro_config->timeFormat == "12"){
						$timedisplay = ($i+$startpoint);
						if($timedisplay == 12){
							$strTime = JText::_('RS1_INPUT_SCRN_NOON');
						} else if($timedisplay > 12){
							$strTime = strval($timedisplay - 12);
							$strTime .= " PM";
						} else {
							$strTime = strval($timedisplay);
							$strTime .= " AM";
						}
					} else {				
						$strTime = strval($i+$startpoint);
						if($mobile != "Yes"){
							$strTime .= ":00";
						}
					}
					echo "<div class='sv_gad_timeslot_header' style='left:".$left."px; width:".$width."px; position:absolute; float:left".($mobile=="Yes"?"; font-size:11px":"")."'>&nbsp;".$strTime."</div>"; 
				}
				echo "</td></tr>";  // end of footer row
				//*************************************************************
			  	// footer row end
				//*************************************************************

				//*************************************************************
			  	// legend
				//*************************************************************
				?>  
                	<tr><td><?php echo JText::_('RS1_GAD_SCRN_GRID_LEGEND');?></td>
                    <td class='sv_gad_legend<?php echo ($mobile == "Yes"?"_mobile":"")?>' ><span class='sv_gad_timeslot_available' >&nbsp;&nbsp;&nbsp;</span>&nbsp;-&nbsp;<?php echo JText::_('RS1_GAD_SCRN_GRID_LEGEND_AVAILABLE');?>
                    &nbsp;&nbsp;<br /><span class='sv_gad_timeslot_booked' >&nbsp;&nbsp;&nbsp;</span>&nbsp;-&nbsp;<?php echo JText::_('RS1_GAD_SCRN_GRID_LEGEND_BOOKED');?>
                    </td></tr>
				</td></tr>				
            </table>
            <input type="hidden" name="grid_previous" id="grid_previous" value="<?php echo $grid_previous ?>">
            <input type="hidden" name="grid_next" id="grid_next" value="<?php echo $grid_next ?>">
            

