<?php
/**
 * JEvents Component for Joomla 1.5.x
 *
 * @version     $Id: events_calendar_cell.php 1128 2008-08-02 17:48:39Z tstahl $
 * @package     JEvents
 * @copyright   Copyright (C) 2006-2008 JEvents Project Group
 * @license     GNU/GPLv2, see http://www.gnu.org/licenses/gpl-2.0.html
 * @link        http://joomlacode.org/gf/project/jevents
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

include_once(JEV_VIEWS."/default/month/tmpl/calendar_cell.php");

class EventCalendarCell_ext extends EventCalendarCell_default{
	function calendarCell(&$currentDay,$year,$month,$i){
		
		$cfg = & JEVConfig::getInstance();		
		// pass $data by reference in order to update countdisplay
		global $mainframe;
		$Itemid = JEVHelper::getItemid();
		$compname = JEV_COM_COMPONENT;

		$event = $currentDay["events"][$i];
		// Event publication infomation
		$event_up   = new JEventDate( $event->startDate() );
		$event_down = new JEventDate( $event->endDate());
		// Event repeat variable initiate
		$repeat_event_type =  $event->reccurtype();

		// BAR COLOR GENERATION
		$bgeventcolor = JEV_CommonFunctions::setColor($event);

		$start_publish  = mktime( 0, 0, 0, $event->mup(), $event->dup(), $event->yup() );
		$stop_publish   = mktime( 0, 0, 0, $event->mdn(), $event->ddn(), $event->ydn() );
		$event_day      = $event->dup();
		$event_month    = $event->mup();

		$title          = $event->title();
		$id             = $event->id();


		// this file controls the events component month calendar display cell output.  It is separated from the
		// showCalendar function in the events.php file to allow users to customize this portion of the code easier.
		// The event information to be displayed within a month day on the calendar can be modified, as well as any
		// overlay window information printed with a javascript mouseover event.  Each event prints as a separate table
		// row with a single column, within the month table's cell.

		// On mouse over date formats
		// Note that the date formats for the events can be easily changed by modifying the sprintf formatting
		// string below.  These are used for the default overlay window.  As well, the strftime() function could
		// also be used instead to provide more powerful date formatting which supports locales if php function
		// set_locale() is being used.

		// define start and end
		$cellStart	= '<div class="eventfull"><div class="eventstyle" ' ;
		$cellStyle	= 'padding:0;';
		$cellEnd		= '</div></div>' . "\n";

		// add the event color as the column background color
		include_once(JPATH_ADMINISTRATOR."/components/".JEV_COM_COMPONENT."/libraries/colorMap.php");

		//$colStyle .= $bgeventcolor ? ' background-color:' . $bgeventcolor . ';' : '';
		//$colStyle .= $bgeventcolor ? 'color:'.JevMapColor($bgeventcolor) . ';' : '';

		// MSIE ignores "inherit" color for links - stupid Microsoft!!!
		//$linkStyle = $bgeventcolor ? 'style="color:'.JevMapColor($bgeventcolor) . ';"' : '';
		$linkStyle = "";

		// The title is printed as a link to the event's detail page
		$link = $this->event->viewDetailLink($year,$month,$currentDay['d0'],false);
		$link = JRoute::_($link.$this->_datamodel->getItemidLink().$this->_datamodel->getCatidsOutLink());

		// [mic] if title is too long, cut 'em for display
		$tmpTitle = $title;
		if( JString::strlen( $title ) >= $cfg->get('com_calCutTitle',50)){
			$tmpTitle = JString::substr( $title, 0, $cfg->get('com_calCutTitle',50) ) . ' ...';
		}
		$tmpTitle = JEventsHTML::special($tmpTitle);

		// [new mic] if amount of displaing events greater than defined, show only a scmall coloured icon
		// instead of full text - the image could also be "recurring dependig", which means
		// for each kind of event (one day, multi day, last day) another icon
		// in this case the dfinition must moved down to be more flexible!

		// [tstahl] add a graphic symbol for all day events?
		$tmp_start_time = ($this->start_time == $this->stop_time || $this->event->alldayevent()) ? '' : $this->start_time;

		if( $currentDay['countDisplay'] < $cfg->get('com_calMaxDisplay',5)){
			$title_event_link = "\n".'<a class="cal_titlelink" href="' . $link . '" '.$linkStyle.'>'
			. ( $cfg->get('com_calDisplayStarttime') ? $tmp_start_time : '' ) . ' ' . $tmpTitle . '</a>' . "\n";
			$cellStyle .= "border-bottom-color:$bgeventcolor;padding-left:2px;";
		}else{
			$eventIMG	= '<img align="left" src="' . JURI::root()
			. 'components/'.$compname.'/images/event.png" alt="" style="height:12px;width:8px;border:1px solid white;background-color:'.$bgeventcolor.'" />';

			$title_event_link = "\n".'<a class="cal_titlelink" href="' . $link . '">' . $eventIMG . '</a>' . "\n";
			$cellStyle .= ' float:left;width:10px;';
		}

		$cellString	= '';

		if( $cfg->get("com_enableToolTip",1)) {
			$cellString .= $this->calendarCell_popup($currentDay["cellDate"]);
		}
		// return the whole thing
		return $cellStart . ' style="' . $cellStyle . '" ' . $cellString . ">\n" . $title_event_link . $cellEnd;
	}		
		
}?>