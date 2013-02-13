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
include_once( JPATH_SITE."/components/com_rsappt_pro2/functions2.php" );

class SVCalendar
{

	function SVCalendar()
	{
	}
	
	var $Itemid = null;
	var $resAdmin = "";
	var $reqStatus = "";
	var $resource_filter = "";
//	var $week_view_header_date_format = "F d, Y";
	var $week_view_header_date_format = "%B %d, %Y";
	var $user_search_filter = "";
	var $startDay = 0;
	var $isMobile = false;
	var $showSeatTotals = false;
			
	function setItemid($id){
		$this->Itemid = $id;	
	}

	function setResAdmin($id){
		$this->resAdmin = $id;	
	}
	
	function setReqStatus($status){
		$this->reqStatus = $status;	
	}
	
	function setResourceFilter($res_filter){
		$this->resource_filter = $res_filter;	
	}

	function setSearchFilter($value){
		$this->user_search_filter = $value;	
	}
	
	function setWeekViewDateFormat($value){
		$this->$week_view_header_date_format = $value;	
	}

	function setWeekStartDay($value){
		$this->startDay = $value;	
	}

	function setIsMobile($value){
		$this->isMobile = $value;	
	}

	function setShowSeatTotals($value){
		$this->showSeatTotals = $value;	
	}

	function getDayNames()
	{
		return $this->dayNames;
	}
	
		
	function getDateLink($day, $month, $year)
	{
		return "";
	}
	
	
	function getCurrentMonthView()
	{
		$d = getdate(time());
		return $this->getMonthView($d["mon"], $d["year"]);
	}
	
		
	function getMonthView($month, $year)
	{
		return $this->getMonthHTML($month, $year);
	}
	
	function getWeekView($wo, $m, $y)
	{
		return $this->getWeekHTML($wo, $m, $y);
	}

	function getDayView($day)
	{
		return $this->getDayHTML($day);
	}
	

	function getDaysInMonth($month, $year)
	{
		if ($month < 1 || $month > 12)
		{
			return 0;
		}
		
		$d = $this->daysInMonth[$month - 1];
		
		if ($month == 2)
		{
			// Check for leap year
			// Forget the 4000 rule, I doubt I'll be around then...
			
			if ($year%4 == 0)
			{
				if ($year%100 == 0)
				{
					if ($year%400 == 0)
					{
						$d = 29;
					}
				}
				else
				{
					$d = 29;
				}
			}
		}
		
		return $d;
	}
	
	
	/*
		------------------------------------------------------------------------------------------------
	    Generate the HTML for a given month
		------------------------------------------------------------------------------------------------
	*/
	function getMonthHTML($m, $y, $showYear = 1){
		
		$bookings = $this->getBookings($this->resAdmin, $m, $y);
		
		$s = "";
		
		$a = $this->adjustDate($m, $y);
		$month = $a[0];
		$year = $a[1];        
		
		$daysInMonth = $this->getDaysInMonth($month, $year);
		$date = getdate(mktime(12, 0, 0, $month, 1, $year));
		
		$first = $date["wday"];
		$array_monthnames = getMonthNamesArray();
		$monthName = $array_monthnames[$month - 1];
		
		$prev = $this->adjustDate($month - 1, $year);
		$next = $this->adjustDate($month + 1, $year);
		
		if ($showYear == 1)
		{
			$prevMonth = $this->getCalendarLinkOnClick($prev[0], $prev[1]);
			$nextMonth = $this->getCalendarLinkOnClick($next[0], $next[1]);
//			$prevMonth = $this->getCalendarLink($prev[0], $prev[1]);
//			$nextMonth = $this->getCalendarLink($next[0], $next[1]);
		}
		else
		{
			$prevMonth = "";
			$nextMonth = "";
		}
		
		$header = $monthName . (($showYear > 0) ? " " . $year : "");

		$array_daynames = getDayNamesArray();
		$s .= "<table width=\"98%\" align=\"center\" class=\"calendar\" cellspacing=\"1\">\n";
		$s .= "<tr>\n";
		$s .= "<td colspan=\"7\" align=\"center\">\n";
		$s .= "<table width=\"100%\">\n";
		$s .= "<tr>\n";
		$s .= "<td width=\"5%\" align=\"center\" valign=\"top\"><input type=\"button\" onclick=\"$prevMonth\" value=\"<<\"></td>\n";
		$s .= "<td align=\"center\" valign=\"top\" class=\"calendarHeader\" >$header</td>\n"; 
		$s .= "<td width=\"5%\" align=\"center\" valign=\"top\"><input type=\"button\" onclick=\"$nextMonth\" value=\">>\" ></td>\n";
		$s .= "</td>\n"; 
		$s .= "</tr>\n";
		$s .= "</table>\n";
		$s .= "</tr>\n";
		
		$s .= "<tr>\n";
		$s .= "<td width=\"14%\" align=\"center\" valign=\"top\" class=\"calendarHeaderDays\">" . $array_daynames[($this->startDay)%7] . "</td>\n";
		$s .= "<td width=\"14%\" align=\"center\" valign=\"top\" class=\"calendarHeaderDays\">" . $array_daynames[($this->startDay+1)%7] . "</td>\n";
		$s .= "<td width=\"14%\" align=\"center\" valign=\"top\" class=\"calendarHeaderDays\">" . $array_daynames[($this->startDay+2)%7] . "</td>\n";
		$s .= "<td width=\"14%\" align=\"center\" valign=\"top\" class=\"calendarHeaderDays\">" . $array_daynames[($this->startDay+3)%7] . "</td>\n";
		$s .= "<td width=\"14%\" align=\"center\" valign=\"top\" class=\"calendarHeaderDays\">" . $array_daynames[($this->startDay+4)%7] . "</td>\n";
		$s .= "<td width=\"14%\" align=\"center\" valign=\"top\" class=\"calendarHeaderDays\">" . $array_daynames[($this->startDay+5)%7] . "</td>\n";
		$s .= "<td width=\"14%\" align=\"center\" valign=\"top\" class=\"calendarHeaderDays\">" . $array_daynames[($this->startDay+6)%7] . "</td>\n";
		$s .= "</tr>\n";
		
		// We need to work out what date to start at so that the first appears in the correct column
		$d = $this->startDay + 1 - $first;
		while ($d > 1)
		{
			$d -= 7;
		}
		
		// Make sure we know when today is, so that we can use a different CSS style
		$today = getdate(time());
		
		while ($d <= $daysInMonth)
		{
			$s .= "<tr>\n";       
			
			for ($i = 0; $i < 7; $i++)
			{
				$class = ($year == $today["year"] && $month == $today["mon"] && $d == $today["mday"]) ? "calendarToday" : "calendar";
				$s .= "<td class=\"calendarCell $class\" width=\"14%\" align=\"left\" valign=\"top\">";       
//				$s .= "<td class=\"calendarCell$class\" align=\"left\" valign=\"top\">";       
				if ($d > 0 && $d <= $daysInMonth)
				{
					//$link = "javascript:goDayView('".$year."-".$month."-".$d."')";
					//$s .= (($link == "") ? "<span class=\"calendar_day_number\">".$d."</span>" : "<a href=\"".$link."\">$d</a>");
					$link = "# onclick='goDayView(\"".$year."-".$month."-".$d."\");return false;'";						
					$s .= "<a href=".$link.">".$d."</a>";
				}
				else
				{
					$s .= "&nbsp;";
				}
				// get todays bookings
				$strToday = strval($year)."-".($month<10 ? "0".
				strval($month):strval($month)) .
				"-".($d<10 ? "0".strval($d) : strval($d));
				foreach($bookings as $booking){
					if($booking->startdate == $strToday){
						
						$link 	= JRoute::_( 'index.php?option=com_rsappt_pro2&controller=admin_detail&task=edit&cid[]='. $booking->id_requests.'&frompage=frontdesk&Itemid='.$this->Itemid);
//						$link 	= 'index.php?option=com_rsappt_pro2&controller=admin_detail&task=edit&cid[]='. $booking->id_requests.'&frompage=frontdesk&Itemid='.$this->Itemid;

						$title = trim($booking->display_starttime)."-".trim($booking->display_endtime)."&nbsp;\n".$booking->resname."&nbsp;\n".$booking->ServiceName;
						$s .= "<br><a href=".$link."  title=\"".$title."\"><span class='calendar_text_".$booking->request_status."'>".$booking->display_starttime."|".stripslashes($booking->name)."</span></a>";
//						$s .= "<br><a href= # onclick='goDetail(\"".$booking->id_requests."\");return false;'  title=\"".$title."\"><span class='calendar_text_".$booking->request_status."'>".$booking->display_starttime."|".stripslashes($booking->name)."</span></a>";
					}
				}
				
				$s .= "<br>&nbsp;</td>\n";       
				$d++;
			}
			$s .= "</tr>\n";    
		}
		
		$s .= "</table>\n";
		$s .= "<input type=\"hidden\" name=\"cur_month\" id=\"cur_month\" value=\"".$month."\">";
		$s .= "<input type=\"hidden\" name=\"cur_year\" id=\"cur_year\" value=\"".$year."\">";
		
		return $s;  	
	}
	

	/*
	------------------------------------------------------------------------------------------------
	    Generate the HTML for a given week
	--------------------------------------------------------------------------------------------------
	*/
	function getWeekHTML($wo, $m, $y){
		// Show sunday - saturday 
		// $wo = week offset, +1 next week, -1 = last week
		// $day for 1 day view

		$NumDays = 7;

		$cws = null;  // currentweekstart
		$dws = null;  // displayweekstart
		
		if($this->startDay == 0){
			if(date("w")==0){
				// today is Sunday $cws = today
				$cws = strtotime("now");
			} else {
				$cws = strtotime("last Sunday");
			}
		} else {
			// get current week's Sunday ($cws = currentweekstart)
			if(date("w")==1){
				// today is Sunday $cws = today
				$cws = strtotime("now");
			} else {
				$cws = strtotime("last Monday");
			}
		}

		if($wo == 0){
			$dws = $cws;
		} else {
			$dws = strtotime(strval($wo)." week", $cws); 
		}		
		
		$bookings = $this->getBookings($this->resAdmin, '', '', date("Y-m-d", $dws), $NumDays, "week");
		
		$header = JText::_(RS1_FRONTDESK_SCRN_VIEW_WEEK);
		$prevWeek = $this->getWeekviewLinkOnClick($wo-1);
		$nextWeek = $this->getWeekviewLinkOnClick($wo+1);
	$lang =& JFactory::getLanguage();
	setlocale(LC_ALL, $lang->getTag()); 
		
		$s = "";
		$i2=1;
		$array_daynames = getLongDayNamesArray($this->startDay);
		$s .= "<table width=\"98%\" align=\"center\" border=\"0\" class=\"calendar_week_view\" cellspacing=\"0\">\n";
		$s .= "<tr class=\"calendar_week_view_header_row\">\n";
		$s .= "<td width=\"5%\" align=\"center\" ><input type=\"button\" ".($this->isMobile=="Yes"?"class=\"button_mobile\"":"")." onclick=\"$prevWeek\" value=\"<<\"></td>\n";
		$s .= "<td align=\"center\" class=\"calendarHeader".($this->isMobile=="Yes"?"_mobile\"":"")."\" >$header</td>\n"; 
		$s .= "<td width=\"5%\" align=\"center\" ><input type=\"button\" ".($this->isMobile=="Yes"?"class=\"button_mobile\"":"")." onclick=\"$nextWeek\" value=\">>\"></td>\n";
		$s .= "</tr>\n";
		for($i=0; $i<$NumDays; $i++){
			// week day
			$link = "# onclick='goDayView(\"".date("Y-m-d", strtotime(strval($i)." day", $dws))."\");return false;'";						
			$s .= "<tr>\n";
			$s .= "  <td colspan=\"3\">\n";
			$s .= "    <table class=\"week_day_table\" width=\"100%\" border=\"0\" cellspacing=\"0\">\n";
			$s .= "      <tr ".($this->isMobile=="Yes"?"class=\"calendar_list_row_mobile\"":"").">\n";

			if(WINDOWS){
				$s .= "        <td colspan=\"3\" >&nbsp;<a href=".$link.">".$array_daynames[$i]." ".iconv('ISO-8859-2', 'UTF-8',strftime($this->week_view_header_date_format, strtotime(strval($i)." day", $dws)))."</a></td>\n";
			} else {
				$s .= "        <td colspan=\"3\" >&nbsp;<a href=".$link.">".$array_daynames[$i]." ".strftime($this->week_view_header_date_format, strtotime(strval($i)." day", $dws))."</a></td>\n";
			}
			$s .= "      </tr>\n";		
			$day_to_check = date("Y-m-d", strtotime(strval($i)." day", $dws));
			$k = 0;
			foreach($bookings as $booking){
				if($booking->startdate == $day_to_check){
					$link 	= JRoute::_( 'index.php?option=com_rsappt_pro2&controller=admin_detail&task=edit&cid[]='. $booking->id_requests.'&frompage=frontdesk&Itemid='.$this->Itemid);
//					$link 	= 'index.php?option=com_rsappt_pro2&controller=admin_detail&task=edit&cid[]='. $booking->id_requests.'&frompage=frontdesk&Itemid='.$this->Itemid;

					$s .= "<tr class='week_row'>\n";
					$s .= "  <td width=\"5%\" align=\"center\"><input type=\"checkbox\" id=\"cb".$i2."\" name=\"cid[]\" value=\"".$booking->id_requests."\" /></td>\n";
					$s .= "  <td width=\"10%\" align=\"left\" width=\"10%\">".$booking->display_starttime."</td>\n";
					$s .= "  <td width=\"15%\" align=\"left\"> ".JText::_(stripslashes($booking->resname))."</td>\n";
					$s .= "  <td width=\"15%\" align=\"left\"> ".stripslashes($booking->ServiceName)."</td>";
					$s .= "  <td width=\"5%\" align=\"left\"> ".$booking->booked_seats."</td>";
					$s .= "  <td width=\"15%\"  align=\"left\"> <a href=".$link.">".stripslashes($booking->name)."</a></td>";
					$s .= "  <td width=\"30%\" align=\"left\"><a href=\"mailto:".$booking->email."\">".$booking->email."</a></td>\n";
//						$s .= "  <td align=\"center\" width=\"10%\"><span class='color_".$booking->payment_status."'>".translated_status($booking->payment_status)."</span></td>\n";
					$s .= "  <td width=\"10%\" align=\"center\" width=\"10%\"><span class='color_".$booking->request_status."'>".translated_status($booking->request_status)."</span></td>\n";
					$s .= "</tr>\n";
					$i2++;
				}
			}
		  $s .= "      </table>\n";
		  $s .= "    </td>\n";
		  $s .= "  </tr>\n";
		}
		$s .= "</table>\n";
		$s .= "<input type=\"hidden\" name=\"cur_week_offset\" id=\"cur_week_offset\" value=\"".$wo."\">";
		
		return $s;  	
	}


	/*
	------------------------------------------------------------------------------------------------	
	    Generate the HTML for a singe day
	------------------------------------------------------------------------------------------------		
	*/
	function getDayHTML($day)
	{
			
		$bookings = $this->getBookings($this->resAdmin, '', '', $day, "", "day");
		
		$unix_day = strtotime($day);
		$prevDay = "buildFrontDeskView('".date("Y-m-d", strtotime('-1 day', $unix_day))."');";
		$nextDay = "buildFrontDeskView('".date("Y-m-d", strtotime('+1 day', $unix_day))."');";
	$lang =& JFactory::getLanguage();
	setlocale(LC_ALL, $lang->getTag()); 
		
		if(WINDOWS){
			$header = iconv('ISO-8859-2', 'UTF-8',strftime($this->week_view_header_date_format, strtotime($day)));
		} else {
			$header = strftime($this->week_view_header_date_format, strtotime($day));		
		}

		$s = "";
		$i=1;
		
		$array_daynames = getLongDayNamesArray();
		$s .= "<table width=\"98%\" align=\"center\" border=\"0\" class=\"calendar_week_view\" cellspacing=\"0\">\n";
		$s .= "  <tr class=\"calendar_week_view_header_row\">\n";
		$s .= "    <td width=\"5%\" align=\"center\" ><input type=\"button\" ".($this->isMobile=="Yes"?"class=\"button_mobile\"":"")." onclick=\"$prevDay\" value=\"<<\"></td>\n";
		$s .= "    <td align=\"center\" class=\"calendarHeader".($this->isMobile=="Yes"?"_mobile\"":"")."\" >$header</td>\n"; 
		$s .= "    <td width=\"5%\" align=\"center\"><input type=\"button\" ".($this->isMobile=="Yes"?"class=\"button_mobile\"":"")." onclick=\"$nextDay\" value=\">>\"></td>\n";
		$s .= "  </tr>\n";
		// week day
		$s .= "  <tr>\n";
		$s .= "    <td colspan=\"3\">\n";
		$s .= "      <table class=\"week_day_table\" width=\"100%\" border=\"0\" cellspacing=\"0\">\n";
		$k = 0;
		$seat_tally = 0;
		$current_ts = "";
		$previous_ts = "";
		$current_res = 0;
		$previous_res = 0;
		$initial_pass = true;
		foreach($bookings as $booking){
			if($this->showSeatTotals == "true"){
				if($initial_pass){
					$current_ts = $booking->display_starttime;
					$previous_ts = $current_ts;
					$current_res = $booking->res_id;
					$previous_res = $current_res;
					$initial_pass = false;
				}
				$current_ts = $booking->display_starttime;
				$current_res = $booking->res_id;
				if($current_ts == $previous_ts AND $previous_res == $current_res){
					if($booking->request_status == 'accepted'){
						$seat_tally  += $booking->booked_seats;
					}
				} else {
					//moved to next timeslot
					//write summary and move on
					$s .= "<tr class='week_row' >\n";
					$s .= "  <td colspan='4' align='right' style=\"border-bottom:solid 1px\">".JText::_('RS1_TS_TOTAL_SEATS')."&nbsp;</td>\n";
					$s .= "  <td width=\"5%\" align=\"center\" style=\"border-top:solid 1px;border-bottom:solid 1px\"> ".$seat_tally."</td>";
					$s .= "  <td colspan='3' style=\"border-bottom:solid 1px\" >&nbsp;</td>\n";
					$s .= "</tr>\n";
					$previous_ts = $current_ts;
					$seat_tally = $booking->booked_seats;				
					$previous_res = $current_res;
				}
			}
			$link 	= JRoute::_( 'index.php?option=com_rsappt_pro2&controller=admin_detail&task=edit&cid[]='. $booking->id_requests.'&frompage=frontdesk&Itemid='.$this->Itemid);
//			$link 	= 'index.php?option=com_rsappt_pro2&controller=admin_detail&task=edit&cid[]='. $booking->id_requests.'&frompage=frontdesk&Itemid='.$this->Itemid;

			$s .= "<tr class='week_row'>\n";
			$s .= "  <td align=\"center\"><input type=\"checkbox\" id=\"cb".$i."\" name=\"cid[]\" value=\"".$booking->id_requests."\" /></td>\n";
			$s .= "  <td align=\"left\" width=\"10%\"><a href=# onclick='goManifest(\"".$booking->resid."\",\"".$booking->startdate."\", \"".$booking->starttime."\", \"".$booking->endtime."\");return false;' title='".JText::_('RS1_DAY_VIEW_TIMESLOT_TOOLTIP')."'>".$booking->display_starttime."</a></td>\n";
			$s .= "  <td align=\"left\"> ".JText::_(stripslashes($booking->resname))."</td>\n";
			$s .= "  <td align=\"left\"> ".stripslashes($booking->ServiceName)."</td>\n";
			$s .= "  <td width=\"5%\" align=\"center\"> ".$booking->booked_seats."</td>\n";
			$s .= "  <td align=\"left\"> <a href=".$link." title='".JText::_('RS1_DAY_VIEW_NAME_TOOLTIP')."'>".stripslashes($booking->name)."</a></td>\n";
			$s .= "  <td align=\"left\"><a href=\"mailto:".$booking->email."\">".$booking->email."</a></td>\n";
//				$s .= "  <td align=\"center\" width=\"10%\"><span class='color_".$booking->payment_status."'>".translated_status($booking->payment_status)."</span></td>\n";
			$s .= "  <td align=\"center\" width=\"10%\"><span class='color_".$booking->request_status."'>".translated_status($booking->request_status)."</span></td>\n";
			$s .= "</tr>\n";
			$i++;
		}	
		if($this->showSeatTotals == "true"){
			$s .= "<tr class='week_row' >\n";
			$s .= "  <td colspan='4' align='right' style=\"border-bottom:solid 1px\">".JText::_('RS1_TS_TOTAL_SEATS')."&nbsp;</td>\n";
			$s .= "  <td width=\"5%\" align=\"center\" style=\"border-top:solid 1px;border-bottom:solid 1px\"> ".$seat_tally."</td>";
			$s .= "  <td colspan='3' style=\"border-bottom:solid 1px\" >&nbsp;</td>\n";
			$s .= "</tr>\n";
		}			  
	    $s .= "      </table>\n";
		$s .= "    </tr>\n";
		$s .= "</table>\n";
		$s .= "<input type=\"hidden\" name=\"cur_day\" id=\"cur_day\" value=\"".$day."\">";
		
		return $s;  	
	}
	
	
	// get  bookings
	function getBookings($resAdmin, $month, $year, $startDay="", $NumDays="", $mode="month"){
		$database = &JFactory::getDBO();
		$sql = 'SELECT * FROM #__sv_apptpro2_config';
		$database->setQuery($sql);
		$apptpro_config = NULL;
		$apptpro_config = $database -> loadObject();
		if ($database -> getErrorNum()) {
			echo "DB Err: ". $database -> stderr();
			return false;
		}
	
		$lang =& JFactory::getLanguage();
		$sql = "SET lc_time_names = '".str_replace("-", "_", $lang->getTag())."';";
		$database->setQuery($sql);
		if (!$database->query()) {
		
			echo $database -> getErrorMsg();						
		}
	
		$sql = "SELECT #__sv_apptpro2_requests.*, #__sv_apptpro2_resources.resource_admins, #__sv_apptpro2_resources.id_resources as res_id, ".
			"#__sv_apptpro2_resources.max_seats, #__sv_apptpro2_resources.name as resname, #__sv_apptpro2_services.name AS ServiceName,  ".
			"#__sv_apptpro2_resources.id_resources as resid, DATE_FORMAT(#__sv_apptpro2_requests.startdate, '%a %b %e ') as display_startdate, ";
			if($apptpro_config->timeFormat == '24'){
				$sql .=" DATE_FORMAT(#__sv_apptpro2_requests.starttime, ' %H:%i') as display_starttime, ";
				$sql .=" DATE_FORMAT(#__sv_apptpro2_requests.endtime, ' %H:%i') as display_endtime ";
			} else {
				$sql .=" DATE_FORMAT(#__sv_apptpro2_requests.starttime, ' %l:%i %p') as display_starttime, ";
				$sql .=" DATE_FORMAT(#__sv_apptpro2_requests.endtime, ' %l:%i %p') as display_endtime ";
			}			
			$sql .= " FROM ( ".
			'#__sv_apptpro2_requests LEFT JOIN '.
			'#__sv_apptpro2_resources ON #__sv_apptpro2_requests.resource = '.
			'#__sv_apptpro2_resources.id_resources LEFT JOIN '.
			'#__sv_apptpro2_services ON #__sv_apptpro2_requests.service = '.
			'#__sv_apptpro2_services.id_services ) '.
			"WHERE ";
		$sql = $sql."#__sv_apptpro2_resources.resource_admins LIKE '%|".$this->resAdmin."|%' ";
		switch($mode){
			case "month":
				$sql = $sql." AND MONTH(startdate)=".strval($month)." AND YEAR(startdate)=".strval($year);
				break;
			case "week":
				$sql = $sql." AND startdate >='".$startDay."' AND startdate <= DATE_ADD('".$startDay."',INTERVAL ".$NumDays." DAY)";
				break;
			case "day":
				$sql = $sql." AND startdate ='".$startDay."' ";
				break;
		}
		if($this->reqStatus != ""){
			$sql .= " AND request_status='".$this->reqStatus."' ";
		}			
		if($this->resource_filter != ""){
			$sql .= " AND resource=".$this->resource_filter." ";
		}
		if($this->user_search_filter != ""){
			$sql .= " AND LCASE(#__sv_apptpro2_requests.name) LIKE '%".strtolower($this->user_search_filter)."%' ";
		}
		$sql .= " AND request_status!='deleted' ";
		$sql .= " ORDER BY startdate, starttime";
		//echo $sql;	
		$database->setQuery($sql);
		$rows = NULL;
		$rows = $database -> loadObjectList();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
			return false;
		}
		return $rows;
	}
	
	
	/*
	    Adjust dates to allow months > 12 and < 0. Just adjust the years appropriately.
	    e.g. Month 14 of the year 2001 is actually month 2 of year 2002.
	*/
	function adjustDate($month, $year)
	{
		$a = array();  
		$a[0] = $month;
		$a[1] = $year;
		
		while ($a[0] > 12)
		{
			$a[0] -= 12;
			$a[1]++;
		}
		
		while ($a[0] <= 0)
		{
			$a[0] += 12;
			$a[1]--;
		}
		
		return $a;
	}
	
	/* 
	    The start day of the week. This is the day that appears in the first column
	    of the calendar. Sunday = 0.
	*/
	//var $startDay = 0;
	
	/* 
	    The start month of the year. This is the month that appears in the first slot
	    of the calendar in the year view. January = 1.
	*/
	var $startMonth = 1;
	
	
	/*
	    The number of days in each month. You're unlikely to want to change this...
	    The first entry in this array represents January.
	*/
	var $daysInMonth = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);

	function getCalendarLink($month, $year)
	{
		// Redisplay the current page, but with some parameters
		// to set the new month and year
		$s = getenv('SCRIPT_NAME');
		return "$s?month=$month&year=$year";
	}
	
	function getCalendarLinkOnClick($month, $year)
	{
		return "buildFrontDeskView('', $month, $year)";
	}
	
	function getWeekviewLinkOnClick($wo)
	{
		return "buildFrontDeskView('', '', '', $wo)";
	}
	
}

?>

