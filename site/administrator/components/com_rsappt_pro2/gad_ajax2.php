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
		$grid_previous = date("Y-m-d", strtotime("- 1 day", strtotime($grid_date)));
		$grid_next = date("Y-m-d", strtotime("+1 day", strtotime($grid_date)));
	} else {
		$grid_previous = date("Y-m-d", strtotime("-".$grid_days." day", strtotime($grid_date)));
		$grid_next = date("Y-m-d", strtotime("+".$grid_days." day", strtotime($grid_date)));
	}

	$sql = 'SELECT * FROM #__sv_apptpro2_config';
	$database->setQuery($sql);
	$apptpro_config = NULL;
	$apptpro_config = $database -> loadObject();
	if ($database -> getErrorNum()) {
		echo "DB Err: ". $database -> stderr();
		exit;
	}
	
	// how many colums 700px - 100 (res name) = 600/num hours btween grid start and end
	$startpoint = intval(substr($gridstarttime,0,2)); 
	$endpoint = intval(substr($gridendtime,0,2)); 

	$rowcount = $endpoint - $startpoint;
	if($rowcount <1){echo JText::_('RS1_GAD_SCRN_GRID_START_BEFORE_END'); exit;}
	$rowheight = $apptpro_config->gad2_row_height;

	$window_start_minute = $startpoint * 60;
	$window_end_minute = $endpoint * 60;
	// We need to position each timeslot withing the table row. To do this we need to divide the tabel row into px/minutes
	// Once we know how many px/min, we can place timeslots the righ number of px from the left.
//	$pxminute = ($gridheight-$nameheight)/($window_end_minute - $window_start_minute);
	$pxminute = ($rowheight*$rowcount)/($window_end_minute - $window_start_minute);
	
	// images
//	$booked_insert = "<img src='".$apptpro_config->gad_booked_image."' style='padding-top:5px'>";
//	$timeslot_insert = "<img src='".$apptpro_config->gad_available_image."' border='0' ></a>";
	
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
    <div id='cell_container' style='position:relative;  width:<?php echo $gridwidth ?>px; height:<?php echo ($rowheight*($rowcount+2))+5 ?>px;'>
	<table id="sv_gad_outer_table" width="<?php echo $gridwidth?>" border="0" cellpadding="0" cellspacing="0">
              <?php 
			  	//echo "<div class='sv_gad_master_container' style='position: relative; width:".$gridwidth."px;'>";

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
				
	//*************************************************************
	//  single_day 			
	//*************************************************************

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
	
					//*************************************************************
					// draw table 			
					//*************************************************************
	
					// top row is res names
					echo "<tr height='".$rowheight."px'><td width='".$namewidth."' align='center' ><b><span id='display_grid_date' >".$display_grid_date."</span></b></td>\n";
					if(count($res_rows) >0){
						$cell_width = round(($gridwidth-$namewidth)/count($res_rows));
					} else {
						$cell_width = round(($gridwidth-$namewidth));
					}
					foreach($res_rows as $res_row){
						echo "<td width='".$cell_width."px' align='center'>".JText::_($res_row->name)."</td>\n";
					}
					echo "</tr>\n ";
					// rowcount is actually row count or number of hours to show
					for($i=0; $i<$rowcount; $i++){
	//					$rowtop = ($i)*60*$pxminute;
	//					$rowheight = 60*$pxminute;
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
						echo "<tr class='gad2_grid' valign='top' height='".$rowheight."px'><td align='center' class='gad2_row'>".$strTime."</td><td class='gad2_row' colspan='".count($res_rows)."'></td></tr>\n"; 
					}
					echo "</td></tr>"; // end of table draw
	
					//*************************************************************
					// draw end			
					//*************************************************************
						
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
					$column_index = -1;
					if(count($res_rows) > 0){
						print_r($res_rows);exit(0);
						foreach($res_rows as $res_row){

							$column_index++;
						
						
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
											
						//echo $sql;
						//exit;
						$database->setQuery($sql);
						$slot_rows = $database -> loadObjectList();
						if ($database -> getErrorNum()) {
							echo $database -> stderr();
							exit;
						}
						
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
									
									//--------------------------------------------------------------------
									// no shows
									if($slotstart_minute >= $window_end_minute || $slotend_minute <= $window_start_minute){
										// outside of window do not show
										
									//--------------------------------------------------------------------
									} else if($slotstart_minute >= $window_start_minute && $slotend_minute <= $window_end_minute){
										// starts and ends inside window
										
										$slottop = (($slotstart_minute-$window_start_minute)*$pxminute)+$rowheight;
										$slotheight = ($slotend_minute-$slotstart_minute)*$pxminute -2;
										$slotleft = ($cell_width*$column_index) + $namewidth; 
										$image_padding = (intval($slotheight) - 20)/2;
										echo "\n<div id='ts".$ts_id."' class='sv_gad_timeslot_available' style='width:".$cell_width."px; left:".$slotleft."px; top:".$slottop."px; height:".$slotheight."px; position:absolute; text-align:center;'>".
										"<a class='sv_gad_timeslot_clickable' style='padding-top:".$image_padding."px' href=# onclick='selectTimeslot(\"".
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
										"</div>"; 
										
									//--------------------------------------------------------------------
									} else if($slotend_minute > $window_end_minute && $slotstart_minute >= $window_start_minute){
										// start inside but goes beyond window

										$slottop = (($slotstart_minute-$window_start_minute)*$pxminute)+$rowheight;
										$slotheight = ($window_end_minute-$slotstart_minute)*$pxminute -2;
										$slotleft = ($cell_width*$column_index) + $namewidth; 
										$image_padding = (intval($slotheight) - 20)/2;										
										echo "\n<div id='ts".$ts_id."' class='sv_gad_timeslot_available' style='width:".$cell_width."px; left:".$slotleft."px; top:".$slottop."px; height:".$slotheight."px; position:absolute; text-align:center;'>".
										"<a class='sv_gad_timeslot_clickable' style='padding-top:".$image_padding."px' href=# onclick='selectTimeslot(\"".
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
										"</div>"; 
										
									//--------------------------------------------------------------------
									} else if($slotstart_minute < $window_start_minute && $slotend_minute <= $window_end_minute){	
										// starts before window but ends inside
										
										$slottop = $rowheight;
										$slotheight = ($slotend_minute-$window_start_minute)*$pxminute -2;
										$slotleft = ($cell_width*$column_index) + $namewidth; 
										$image_padding = (intval($slotheight) - 20)/2;										
										echo "\n<div id='ts".$ts_id."' class='sv_gad_timeslot_available' style='width:".$cell_width."px; left:".$slotleft."px; top:".$slottop."px; height:".$slotheight."px; position:absolute; text-align:center;'>".
										"<a class='sv_gad_timeslot_clickable' style='padding-top:".$image_padding."px' href=# onclick='selectTimeslot(\"".
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
										"</div>"; 
										
									//--------------------------------------------------------------------
									} else {
										
										// bigger than grid, fill'er up
										$slottop = $rowheight;
										$slotheight = ($window_end_minute - $window_start_minute)*$pxminute -2;
										$slotleft = ($cell_width*$column_index) + $namewidth; 
										$image_padding = (intval($slotheight) - 20)/2;
										echo "\n<div id='ts".$ts_id."' class='sv_gad_timeslot_available' style='width:".$cell_width."px; left:".$slotleft."px; top:".$slottop."px; height:".$slotheight."px; position:absolute; text-align:center;'>".
										"<a class='sv_gad_timeslot_clickable' style='padding-top:".$image_padding."px' href=# onclick='selectTimeslot(\"".
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
										"</div>"; 
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

												//--------------------------------------------------------------------
												// no shows
												if($bookingstart_minute >= $window_end_minute || $bookingend_minute <= $window_start_minute){
													// outside of window do not show
												
												//--------------------------------------------------------------------
												} else if($bookingstart_minute >= $window_start_minute && $bookingend_minute <= $window_end_minute){
													// starts and ends inside window
													
													$slottop = (($bookingstart_minute-$window_start_minute)*$pxminute)+$rowheight;
													$slotheight = ($bookingend_minute-$bookingstart_minute)*$pxminute -2;
													$image_padding = (intval($slotheight) - 20)/2;									
													$booked_insert = "<img src='".$apptpro_config->gad_booked_image."' style='padding-top:".$image_padding."px'>";
													
													if($booking->request_status == 'accepted'){
														echo "<div class='sv_gad_timeslot_booked' style='width:".($cell_width)."px; left:".$slotleft."px; top:".$slottop."px; height:".$slotheight."px; position:absolute; text-align:center;' >".$booked_insert."</div>"; 
													} else {
														echo "<div class='sv_gad_timeslot_pending' style='width:".($cell_width)."px; left:".$slotleft."px; top:".$slottop."px; height:".$slotheight."px; position:absolute; text-align:center;' >".$booked_insert."</div>"; 
													}																		

												//--------------------------------------------------------------------
												} else if($bookingend_minute > $window_end_minute && $bookingstart_minute >= $window_start_minute){													
													// starts inside but goes beyond window
													
													$slottop = (($bookingstart_minute-$window_start_minute)*$pxminute)+$rowheight;
													$slotheight = ($window_end_minute-$bookingstart_minute)*$pxminute -2;
													$image_padding = (intval($slotheight) - 20)/2;									
													$booked_insert = "<img src='".$apptpro_config->gad_booked_image."' style='padding-top:".$image_padding."px'>";
													
													if($booking->request_status == 'accepted'){
														echo "<div class='sv_gad_timeslot_booked'  style='width:".($cell_width)."px; left:".$slotleft."px; top:".$slottop."px; height:".$slotheight."px; position:absolute; text-align:center;' >".$booked_insert."</div>"; 
													} else {
														echo "<div class='sv_gad_timeslot_pending' style='width:".($cell_width)."px; left:".$slotleft."px; top:".$slottop."px; height:".$slotheight."px; position:absolute; text-align:center;' >".$booked_insert."</div>"; 
													}
													
												//--------------------------------------------------------------------
												} else if($bookingstart_minute < $window_start_minute && $bookingend_minute <= $window_end_minute){	
													// starts before window but ends inside
												
													$slottop = $rowheight;
													$slotheight = ($bookingend_minute-$window_start_minute)*$pxminute -2;
													$slotleft = ($cell_width*$column_index) + $namewidth; 
													$image_padding = (intval($slotheight) - 20)/2;									
													$booked_insert = "<img src='".$apptpro_config->gad_booked_image."' style='padding-top:".$image_padding."px'>";
													
													if($booking->request_status == 'accepted'){
														echo "<div class='sv_gad_timeslot_booked'  style='width:".($cell_width)."px; left:".$slotleft."px; top:".$slottop."px; height:".$slotheight."px; position:absolute; text-align:center;' >".$booked_insert."</div>"; 
													} else {
														echo "<div class='sv_gad_timeslot_pending' style='width:".($cell_width)."px; left:".$slotleft."px; top:".$slottop."px; height:".$slotheight."px; position:absolute; text-align:center;' >".$booked_insert."</div>"; 
													}
													
												//--------------------------------------------------------------------
												} else {
													// bigger than grid, fill'er up

													$slottop = $rowheight;
													$slotheight = ($window_end_minute - $window_start_minute)*$pxminute -2;
													$slotleft = ($cell_width*$column_index) + $namewidth; 
													$image_padding = (intval($slotheight) - 20)/2;									
													$booked_insert = "<img src='".$apptpro_config->gad_booked_image."' style='padding-top:".$image_padding."px'>";
													
													if($booking->request_status == 'accepted'){
														echo "<div class='sv_gad_timeslot_booked'  style='width:".($cell_width)."px; left:".$slotleft."px; top:".$slottop."px; height:".$slotheight."px; position:absolute; text-align:center;' >".$booked_insert."</div>"; 
													} else {
														echo "<div class='sv_gad_timeslot_pending' style='width:".($cell_width)."px; left:".$slotleft."px; top:".$slottop."px; height:".$slotheight."px; position:absolute; text-align:center;' >".$booked_insert."</div>"; 
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

											//--------------------------------------------------------------------
											// no shows
											if($bookingstart_minute >= $window_end_minute || $bookingend_minute <= $window_start_minute){
												// outside of window do not show
											
											//--------------------------------------------------------------------
											} else if($bookingstart_minute >= $window_start_minute && $bookingend_minute <=$window_end_minute){
												// starts and ends inside window
												
												$slottop = (($bookingstart_minute-$window_start_minute)*$pxminute)+$rowheight;
												$slotheight = ($bookingend_minute-$bookingstart_minute)*$pxminute -2;
												echo "<div class='sv_gad_timeslot_book-off'  style='width:".($cell_width)."px; left:".$slotleft."px; top:".$slottop."px; height:".$slotheight."px; position:absolute; text-align:center;' >".JText::_(stripslashes($part_day_bookoff->description))."</div>"; 
												
											//--------------------------------------------------------------------
											} else if($bookingend_minute > $window_end_minute && $bookingstart_minute >= $window_start_minute){
												// starts inside but goes beyond window
												
												$slottop = (($bookingstart_minute-$window_start_minute)*$pxminute)+$rowheight;
												$slotheight = ($window_end_minute-$bookingstart_minute)*$pxminute -2;
												echo "<div class='sv_gad_timeslot_book-off'  style='width:".($cell_width)."px; left:".$slotleft."px; top:".$slottop."px; height:".$slotheight."px; position:absolute; text-align:center;' >".JText::_(stripslashes($part_day_bookoff->description))."</div>"; 

											//--------------------------------------------------------------------
											} else if($bookingstart_minute < $window_start_minute && $bookingend_minute <= $window_end_minute){	
												// starts before window but ends inside
												
												$slottop = $rowheight;
												$slotheight = ($bookingend_minute-$bookingstart_minute)*$pxminute -2;
												$slotleft = ($cell_width*$column_index) + $namewidth; 
												echo "<div class='sv_gad_timeslot_book-off'  style='width:".($cell_width)."px; left:".$slotleft."px; top:".$slottop."px; height:".$slotheight."px; position:absolute; text-align:center;' >".JText::_(stripslashes($part_day_bookoff->description))."</div>"; 
											
											//--------------------------------------------------------------------
											} else {
												// bigger than grid, fill'er up
												
												$slottop = $rowheight;
												$slotheight = ($window_end_minute - $window_start_minute)*$pxminute -2;
												$slotleft = ($cell_width*$column_index) + $namewidth; 
												echo "<div class='sv_gad_timeslot_book-off'  style='width:".($cell_width)."px; left:".$slotleft."px; top:".$slottop."px; height:".$slotheight."px; position:absolute; text-align:center;' >".JText::_(stripslashes($part_day_bookoff->description))."</div>"; 
											}				
										}
									}
								
								}

							} else {
							// this is a no-show row, if it is a book-off, show description 
							$bo = getBookOffDescription($res_row, $grid_date);
							if($bo->description !=""){
								$slottop = $rowheight;
								$slotheight = ($window_end_minute - $window_start_minute)*$pxminute -2;
								$slotleft = ($cell_width*$column_index) + $namewidth; 
								echo "<div class='sv_gad_timeslot_book-off' style='width:".($cell_width)."px; left:".$slotleft."px; top:".$slottop."px; height:".$slotheight."px; position:absolute;  text-align:center'>".JText::_(stripslashes($bo->description))."</div>";
							} else {
								// if non-work_day message is set show it - only show for future days
								$slottop = $rowheight;
								$slotheight = ($window_end_minute - $window_start_minute)*$pxminute -2;
								$slotleft = ($cell_width*$column_index) + $namewidth; 
								if($res_row->non_work_day_message != "" && (strtotime($grid_date) >= strtotime('now'))){
									echo "<div class='sv_gad_non_work_day' style='width:".($cell_width)."px; left:".$slotleft."px; top:".$slottop."px; height:".$slotheight."px; position:absolute; text-align:center'>".JText::_(stripslashes($res_row->non_work_day_message))."</div>";
								}
							}
							
						}
							echo "</div></td></tr>";
						}
					}				

				} else {
	// **********************************************************
	// single_resource 
	// **********************************************************
					// get resource details
					$sql = "SELECT * FROM #__sv_apptpro2_resources WHERE id_resources=".$resource;
					$database->setQuery($sql);
					$res_detail = $database -> loadObject();
					if ($database -> getErrorNum()) {
						echo $database -> stderr();
						return false;
					}

				//*************************************************************
			  	// draw table 			
				//*************************************************************

				// top row is res names
				echo "<tr height='".$rowheight."px' ><td width='".$namewidth."'>&nbsp;</td>\n";
				$cell_width = round(($gridwidth-$namewidth)/$grid_days);
				for($day=0; $day<$grid_days; $day++){	
					if(WINDOWS){
						$dayname = iconv('ISO-8859-2', 'UTF-8',strftime("%a<br>%d-%b-%Y",(DateAdd("d", $day, strtotime($grid_date)))));
					} else {
						$dayname = strftime("%a<br>%d-%b-%Y",(DateAdd("d", $day, strtotime($grid_date))));
					}
					echo "<td width='".$cell_width."px' align='center'>".$dayname."</td>\n";
				}
				echo "</tr>\n ";
				// rowcount is actually row count or number of hours to show
					for($i=0; $i<$rowcount; $i++){
	//					$rowtop = ($i)*60*$pxminute;
	//					$rowheight = 60*$pxminute;
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
				echo "<tr class='gad2_grid' valign='top' height='".$rowheight."px' ><td align='center' class='gad2_row'>".$strTime."</td><td class='gad2_row' colspan='".count($res_rows)."'></td></tr>\n"; 
				}
				echo "</td></tr>"; // end of table draw

				//*************************************************************
			  	// draw end			
				//*************************************************************

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
					$column_index = -1;
					for($day=0; $day<$grid_days; $day++){	
						$lang =& JFactory::getLanguage();
						setlocale(LC_ALL, $lang->getTag()); 

						$column_index++;

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

//					$y_axis_header = "<tr><td align='center' class='sv_gad_timeslot_yaxis_header'><a href=javascript:changeMode2('".$strDate."')> ".JText::_($dayname)."</a></td><td colspan='".$rowcount."'>".
//						"<div class='sv_gad_row_wrapper' style='position: relative; width:".($gridwidth-$namewidth)."px; '>";

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
								$slotwidth = $gridwidth - $namewidth - 15;
								$slotleft = $namewidth+30;
								if($apptpro_config->show_available_seats == "Yes" && $res_detail->max_seats>1){
									$row_date = date("Y-m-d",(DateAdd("d", $day, strtotime($grid_date))));
									$currentcount = getCurrentSeatCount($row_date, $slot_row->timeslot_starttime, $slot_row->timeslot_endtime, $res_detail->id_resources);
									$timeslot_insert = strval($res_detail->max_seats - $currentcount)."</a>";
								} else {
									if($slot_row->timeslot_description != ""){
										$timeslot_insert = JText::_($slot_row->timeslot_description)."</a>";
									} else {
										$image_padding = (intval($slotheight) - 20)/2;
										$timeslot_insert = "<img src='".$apptpro_config->gad_available_image."' style='padding-top:".$image_padding."px;' border='0'></a>";
									}
								}
								$ts_id ++;
								// get start minute, end minute								
								$slotstart_minute = getMinute($slot_row->timeslot_starttime);
								$slotend_minute = getMinute($slot_row->timeslot_endtime);
								
								//--------------------------------------------------------------------
								// no shows
								if($slotstart_minute >= $window_end_minute || $slotend_minute <= $window_start_minute){
									// outside of window do not show
										
								//--------------------------------------------------------------------
								} else if($slotstart_minute >= $window_start_minute && $slotend_minute <=$window_end_minute){
									// starts and ends inside window
									
									$slottop = (($slotstart_minute-$window_start_minute)*$pxminute)+$rowheight;
									$slotheight = ($slotend_minute-$slotstart_minute)*$pxminute -2;
									$slotleft = ($cell_width*$column_index) + $namewidth; 
									$image_padding = (intval($slotheight) - 20)/2;
									$timeslot_insert = "<img src='".$apptpro_config->gad_available_image."' style='padding-top:".$image_padding."px;' border='0'></a>";
									
									echo "\n<div id='ts".$ts_id."' class='sv_gad_timeslot_available' style='width:".$cell_width."px; left:".$slotleft."px; top:".$slottop."px; height:".$slotheight."px; position:absolute; text-align:center;'>".
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
									"</div>"; 

								//--------------------------------------------------------------------
								} else if($slotend_minute > $window_end_minute && $slotstart_minute >= $window_start_minute){
									// start inside but goes beyond window

									$slottop = (($slotstart_minute-$window_start_minute)*$pxminute)+$rowheight;
									$slotheight = ($window_end_minute-$slotstart_minute)*$pxminute -2;
									$slotleft = ($cell_width*$column_index) + $namewidth; 
									$image_padding = (intval($slotheight) - 20)/2;
									$timeslot_insert = "<img src='".$apptpro_config->gad_available_image."' style='padding-top:".$image_padding."px;' border='0'></a>";
									
									echo "\n<div id='ts".$ts_id."' class='sv_gad_timeslot_available' style='width:".$cell_width."px; left:".$slotleft."px; top:".$slottop."px; height:".$slotheight."px; position:absolute; text-align:center;'>".
									"<a class='sv_gad_timeslot_clickable' href=# onclick='selectTimeslot(\"".
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
									base64_encode($slot_row->display_timeslot_endtime)."|ts".$ts_id."\",event);return false;'>".
									$timeslot_insert.
									"</div>"; 

								//--------------------------------------------------------------------
								} else if($slotstart_minute < $window_start_minute && $slotend_minute <= $window_end_minute){	
									// starts before window but ends inside

									$slottop = $rowheight;//(($slotstart_minute-$window_start_minute)*$pxminute)+$rowheight;
									$slotheight = ($slotend_minute-$window_start_minute)*$pxminute -2;
									$slotleft = ($cell_width*$column_index) + $namewidth; 
									$image_padding = (intval($slotheight) - 20)/2;
									$timeslot_insert = "<img src='".$apptpro_config->gad_available_image."' style='padding-top:".$image_padding."px;' border='0'></a>";
									echo "\n<div id='ts".$ts_id."' class='sv_gad_timeslot_available' style='width:".$cell_width."px; left:".$slotleft."px; top:".$slottop."px; height:".$slotheight."px; position:absolute; text-align:center;'>".
									"<a class='sv_gad_timeslot_clickable' href=# onclick='selectTimeslot(\"".
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
									base64_encode($slot_row->display_timeslot_endtime)."|ts".$ts_id."\",event);return false;'>".
									$timeslot_insert.
									"</div>"; 
									
								//--------------------------------------------------------------------
								} else {
									// bigger than grid, fill'er up
									$slottop = $rowheight;
									$slotheight = ($window_end_minute - $window_start_minute)*$pxminute -2;
									$slotleft = ($cell_width*$column_index) + $namewidth; 
									$image_padding = (intval($slotheight) - 20)/2;									
									echo "\n<div id='ts".$ts_id."' class='sv_gad_timeslot_available' style='width:".$cell_width."px; left:".$slotleft."px; top:".$slottop."px; height:".$slotheight."px; position:absolute; text-align:center;'>".
									"<a class='sv_gad_timeslot_clickable' style='padding-top:".$image_padding."px' href=# onclick='selectTimeslot(\"".
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
									base64_encode($slot_row->display_timeslot_endtime)."|ts".$ts_id."\",event);return false;'>".
									$timeslot_insert.
									"</div>"; 
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
										
											//--------------------------------------------------------------------
											// no shows
											if($bookingstart_minute >= $window_end_minute || $bookingend_minute <= $window_start_minute){
												// outside of window do not show
												
											//--------------------------------------------------------------------
											} else if($bookingstart_minute >= $window_start_minute && $bookingend_minute <= $window_end_minute){
												// starts and ends inside window

												$slottop = (($bookingstart_minute-$window_start_minute)*$pxminute)+$rowheight;
												$slotheight = ($bookingend_minute-$bookingstart_minute)*$pxminute -2;
												$slotleft = ($cell_width*$column_index) + $namewidth; 
												$slotleft = ($cell_width*$column_index) + $namewidth; 
												$image_padding = (intval($slotheight) - 20)/2;									
												$booked_insert = "<img src='".$apptpro_config->gad_booked_image."' style='padding-top:".$image_padding."px'>";

												if($booking->request_status == 'accepted'){
													echo "<div class='sv_gad_timeslot_booked' style='width:".($cell_width)."px; left:".$slotleft."px; top:".$slottop."px; height:".$slotheight."px; position:absolute; text-align:center;' >".$booked_insert."</div>"; 
												} else {
													echo "<div class='sv_gad_timeslot_pending' style='width:".($cell_width)."px; left:".$slotleft."px; top:".$slottop."px; height:".$slotheight."px; position:absolute; text-align:center;' >".$booked_insert."</div>"; 
												}																		

											//--------------------------------------------------------------------
											} else if($bookingend_minute > $window_end_minute && $bookingstart_minute >= $window_start_minute){													
												// starts inside but goes beyond window
												
												$slottop = (($bookingstart_minute-$window_start_minute)*$pxminute)+$rowheight;
												$slotheight = ($window_end_minute-$bookingstart_minute)*$pxminute -2;
												$slotleft = ($cell_width*$column_index) + $namewidth; 
												if($booking->request_status == 'accepted'){
													echo "<div class='sv_gad_timeslot_booked'  style='width:".($cell_width)."px; left:".$slotleft."px; top:".$slottop."px; height:".$slotheight."px; position:absolute; text-align:center;' >".$booked_insert."</div>"; 
												} else {
													echo "<div class='sv_gad_timeslot_pending' style='width:".($cell_width)."px; left:".$slotleft."px; top:".$slottop."px; height:".$slotheight."px; position:absolute; text-align:center;' >".$booked_insert."</div>"; 
												}

											//--------------------------------------------------------------------
											} else if($bookingstart_minute < $window_start_minute && $bookingend_minute <= $window_end_minute){	
												// starts before window but ends inside
											
												$slottop = $rowheight;
												$slotheight = ($bookingend_minute-$window_start_minute)*$pxminute -2;
												$slotleft = ($cell_width*$column_index) + $namewidth; 
												if($booking->request_status == 'accepted'){
													echo "<div class='sv_gad_timeslot_booked'  style='width:".($cell_width)."px; left:".$slotleft."px; top:".$slottop."px; height:".$slotheight."px; position:absolute; text-align:center;' >".$booked_insert."</div>"; 
												} else {
													echo "<div class='sv_gad_timeslot_pending' style='width:".($cell_width)."px; left:".$slotleft."px; top:".$slottop."px; height:".$slotheight."px; position:absolute; text-align:center;' >".$booked_insert."</div>"; 
												}
												
											//--------------------------------------------------------------------
											} else {
												// bigger than grid, fill'er up

												$slottop = $rowheight;
												$slotheight = ($window_end_minute - $window_start_minute)*$pxminute -2;
												$slotleft = ($cell_width*$column_index) + $namewidth; 
												if($booking->request_status == 'accepted'){
													echo "<div class='sv_gad_timeslot_booked'  style='width:".($cell_width)."px; left:".$slotleft."px; top:".$slottop."px; height:".$slotheight."px; position:absolute; text-align:center;' >".$booked_insert."</div>"; 
												} else {
													echo "<div class='sv_gad_timeslot_pending' style='width:".($cell_width)."px; left:".$slotleft."px; top:".$slottop."px; height:".$slotheight."px; position:absolute; text-align:center;' >".$booked_insert."</div>"; 
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
										
										//--------------------------------------------------------------------
										// no shows
										if($bookingstart_minute >= $window_end_minute || $bookingend_minute <= $window_start_minute){
											// outside of window do not show

										//--------------------------------------------------------------------
										} else if($bookingstart_minute >= $window_start_minute && $bookingend_minute <=$window_end_minute){
											// starts and ends inside window
											
											$slottop = (($bookingstart_minute-$window_start_minute)*$pxminute)+$rowheight;
											$slotheight = ($bookingend_minute-$bookingstart_minute)*$pxminute -2;
											$slotleft = ($cell_width*$column_index) + $namewidth; 
											echo "<div class='sv_gad_timeslot_book-off'  style='width:".($cell_width)."px; left:".$slotleft."px; top:".$slottop."px; height:".$slotheight."px; position:absolute; text-align:center;' >".JText::_(stripslashes($part_day_bookoff->description))."</div>"; 												

										//--------------------------------------------------------------------
										} else if($bookingend_minute > $window_end_minute && $bookingstart_minute >= $window_start_minute){
											// starts inside but goes beyond window
											
											$slottop = (($bookingstart_minute-$window_start_minute)*$pxminute)+$rowheight;
											$slotheight = ($window_end_minute-$bookingstart_minute)*$pxminute -2;
											$slotleft = ($cell_width*$column_index) + $namewidth; 
											echo "<div class='sv_gad_timeslot_book-off'  style='width:".($cell_width)."px; left:".$slotleft."px; top:".$slottop."px; height:".$slotheight."px; position:absolute; text-align:center;' >".JText::_(stripslashes($part_day_bookoff->description))."</div>"; 

										//--------------------------------------------------------------------
										} else if($bookingstart_minute < $window_start_minute && $bookingend_minute <= $window_end_minute){	
											// starts before window but ends inside
											
											$slottop = $rowheight;
											$slotheight = ($bookingend_minute-$bookingstart_minute)*$pxminute -2;
											$slotleft = ($cell_width*$column_index) + $namewidth; 
											echo "<div class='sv_gad_timeslot_book-off'  style='width:".($cell_width)."px; left:".$slotleft."px; top:".$slottop."px; height:".$slotheight."px; position:absolute; text-align:center;' >".JText::_(stripslashes($part_day_bookoff->description))."</div>"; 
										
										//--------------------------------------------------------------------
										} else {
											// bigger than grid, fill'er up
											
											$slottop = $rowheight;
											$slotheight = ($window_end_minute - $window_start_minute)*$pxminute -2;
											$slotleft = ($cell_width*$column_index) + $namewidth; 
											echo "<div class='sv_gad_timeslot_book-off'  style='width:".($cell_width)."px; left:".$slotleft."px; top:".$slottop."px; height:".$slotheight."px; position:absolute; text-align:center;' >".JText::_(stripslashes($part_day_bookoff->description))."</div>"; 
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
//									echo $y_axis_header;
								}
								$slottop = $rowheight;
								$slotheight = ($window_end_minute - $window_start_minute)*$pxminute -2;
								$slotleft = ($cell_width*$column_index) + $namewidth; 
								echo "<div class='sv_gad_timeslot_book-off' style='width:".($cell_width)."px; left:".$slotleft."px; top:".$slottop."px; height:".$slotheight."px; position:absolute; text-align:center'>".JText::_(stripslashes($bo->description))."</div>";
							} else {
								// if non-work_day message is set show it - future only
								if($res_detail->non_work_day_message != "" && (strtotime($grid_date) >= strtotime('now'))){
									if($res_detail->non_work_day_hide == "Yes"){
										// only show if row has $sr==true
										//echo $y_axis_header;
									}
									$slottop = $rowheight;
									$slotheight = ($window_end_minute - $window_start_minute)*$pxminute -2;
									$slotleft = ($cell_width*$column_index) + $namewidth; 
									echo "<div class='sv_gad_non_work_day' style='width:".($cell_width)."px; left:".$slotleft."px; top:".$slottop."px; height:".$slotheight."px; position:absolute; text-align:center'>".JText::_(stripslashes($res_detail->non_work_day_message))."</div>";
								}
							}
							
						}
					
					}
				
				}
				
				echo "</div>"; // end master container 
				
				//*************************************************************
			  	// legend
				//*************************************************************
				?>  
               	<tr><td class='gad2_legend'><?php echo JText::_('RS1_GAD_SCRN_GRID_LEGEND');?></td>
                    <td class='sv_gad_legend<?php echo ($mobile == "Yes"?"_mobile":"")?>' style="border-top:solid 1px #666" colspan=<?php echo $rowcount?>><span class='sv_gad_timeslot_available' >&nbsp;&nbsp;&nbsp;</span>&nbsp;-&nbsp;<?php echo JText::_('RS1_GAD_SCRN_GRID_LEGEND_AVAILABLE');?>
                    &nbsp;&nbsp;<br /><span class='sv_gad_timeslot_booked' >&nbsp;&nbsp;&nbsp;</span>&nbsp;-&nbsp;<?php echo JText::_('RS1_GAD_SCRN_GRID_LEGEND_BOOKED');?>
                    </td></tr>
				</td></tr>				
            </table>

            <input type="hidden" name="grid_previous" id="grid_previous" value="<?php echo $grid_previous ?>">
            <input type="hidden" name="grid_next" id="grid_next" value="<?php echo $grid_next ?>">
            

