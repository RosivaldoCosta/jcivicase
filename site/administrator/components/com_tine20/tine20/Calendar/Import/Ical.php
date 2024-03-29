<?php
/**
 * Tine 2.0
 * 
 * @package     Calendar
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Cornelius Weiss <c.weiss@metaways.de>
 * @copyright   Copyright (c) 2010 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: Ical.php 17299 2010-11-24 09:50:37Z p.schuele@metaways.de $
 */

/**
 * @see for german holidays http://www.sunbird-kalender.de/extension/kalender/
 * 
 * @todo add support for rrule exceptions
 * @todo add support for alarms
 * @todo add support for attendee / organizer
 * @todo add support for categories
 *
 */
class Calendar_Import_Ical extends Tinebase_Import_Abstract
{
    /**
     * config options
     * 
     * @var array
     */
    protected $_options = array(
        /**
         * force update of existing events 
         * @var boolean
         */
        'updateExisting'        => TRUE,
        /**
         * updates exiting events if sequence number is higher
         * @var boolean
         */
        'forceUpdateExisting'   => FALSE,
        /**
         * container the events should be imported in
         * @var string
         */
        'importContainerId'     => NULL,
    );
    
    /**
     * default timezone from VCALENDAR. If not present, users default tz will be taken
     * @var string
     */
    protected $_defaultTimezoneId;
    
    /**
     * maps tine20 propertynames to ical propertynames
     * @var array
     */
    protected $_eventPropertyMap = array(
        'summary'               => 'SUMMARY',
        'description'           => 'DESCRIPTION',
        'class'                 => 'CLASS',
        'transp'                => 'TRANSP',
        'seq'                   => 'SEQUENCE',
        'uid'                   => 'UID',
        'dtstart'               => 'DTSTART',
        'dtend'                 => 'DTEND',
        'rrule'                 => 'RRULE',
//        '' => 'DTSTAMP',
        'creation_time'         => 'CREATED',
        'last_modified_time'    => 'LAST-MODIFIED',
    );
    
    /**
     * creates a new importer from an importexport definition
     * 
     * @param  Tinebase_Model_ImportExportDefinition $_definition
     * @param  array                                 $_options
     * @return Calendar_Import_Ical
     */
    public static function createFromDefinition(Tinebase_Model_ImportExportDefinition $_definition, array $_options = array())
    {
        return new Calendar_Import_Ical(self::getOptionsArrayFromDefinition($_definition, $_options));
    }
    
    /**
     * import the data
     *
     * @param  stream $_resource 
     * @return array : 
     *  'results'           => Tinebase_Record_RecordSet, // for dryrun only
     *  'totalcount'        => int,
     *  'failcount'         => int,
     *  'duplicatecount'    => int,
     */
    public function import($_resource = NULL)
    {
        if (! $this->_options['importContainerId']) {
            throw new Tinebase_Exception_InvalidArgument('you need to define a importContainerId');
        }
        
        $result = array(
            'results'           => null,
            'totalcount'        => 0,
            'failcount'         => 0,
            'duplicatecount'    => 0,
        );
        
        $icalData = stream_get_contents($_resource);
        
        $parser = new qCal_Parser();
        $ical = $parser->parse($icalData);
        
        $events = $result['results'] = $this->_getEvents($ical);
//        print_r($events->toArray());
        
        // set container
        $events->container_id = $this->_options['importContainerId'];
        
        $cc = Calendar_Controller_Event::getInstance();
        $sendNotifications = $cc->sendNotifications(FALSE);
        
        // search uid's and remove already existing -> only in import cal?
        $existingEvents = $cc->search(new Calendar_Model_EventFilter(array(
            array('field' => 'container_id', 'operator' => 'equals', 'value' => $this->_options['importContainerId']),
            array('field' => 'uid', 'operator' => 'in', 'value' => array_unique($events->uid)),
        )), NULL);
        
        // insert one by one in a single transaction
        $existingEvents->addIndices(array('uid'));
        foreach($events as $event) {
            $existingEvent = $existingEvents->find('uid', $event->uid);
            try {
                if (! $existingEvent) {
                    $cc->create($event, FALSE);
                    $result['totalcount'] += 1;
                } else if ($this->_options['forceUpdateExisting'] || ($this->_options['updateExisting'] && $event->seq > $existingEvent->seq)) {
                    $event->id = $existingEvent->getId();
                    $event->last_modified_time = clone $existingEvent->last_modified_time;
                    $cc->update($event, FALSE);
                    $result['totalcount'] += 1;
                } else {
                    $result['duplicatecount'] += 1;
                }
            } catch (Exception $e) {
                $result['failcount'] += 1;
            }
        }
        $cc->sendNotifications($existingEvents);
        
        return $result;
    }
    
    /**
     * converts VEVENT to an Calendar_Model_Event
     * 
     * @param   qCal_Component $vevent
     * @return  Calendar_Model_Event
     */
    protected function _getEvent(qCal_Component $vevent)
    {
        $eventData = array();
        
        // timezone
        if ($vevent->hasComponent('VTIMEZONE')) {
            $tz = array_value(0, $vevent->getComponent('VTIMEZONE'));
            $eventData['originator_tz'] = array_value(0, $tz->getProperty('TZID'))->getValue();
        } else {
            $eventData['originator_tz'] = $this->_defaultTimezoneId;
        }
        
        foreach($this->_eventPropertyMap as $tineName => $icalName) {
            if ($vevent->hasProperty($icalName)) {
                $icalValue = array_value(0, $vevent->getProperty($icalName));
                
                switch ($icalValue->getType()) {
                    case 'DATE':
                        $value = new Tinebase_DateTime($icalValue->getValue() . 'T000000', $eventData['originator_tz']);
                        
                        // events with dtstart given as date are allday events!
                        if ($tineName == 'dtstart') {
                            $eventData['is_all_day_event'] = true;
                        }
                        
                        if ($tineName == 'dtend') {
                            $value = $value->addSecond(-1);
                        } 
                        break;
                    case 'DATE-TIME':
                        $value = new Tinebase_DateTime($icalValue->getValue(), $eventData['originator_tz']);
                    case 'TEXT':
                        $value = str_replace(array('\\,', '\\n'), array(',', "\n"), $icalValue->getValue());
                        break;
                    default:
                        $value = $icalValue->getValue();
                        break;
                }
                $eventData[$tineName] = $value;
            }
        }
        
        $event = new Calendar_Model_Event($eventData);
        $event->setTimezone('UTC');
                        
        return $event;
    }
    
    /**
     * convert a VCALENDAR into a Tinebase_Record_RecordSet of Calendar_Model_Event
     * 
     * @param   qCal_Component_Vcalendar $component
     * @return  Tinebase_Record_RecordSet of Calendar_Model_Event
     */
    protected function _getEvents(qCal_Component_Vcalendar $component)
    {
        $events = new Tinebase_Record_RecordSet('Calendar_Model_Event');
        
        // do we have a generic timezone?
        if ($component->hasComponent('VTIMEZONE')) {
            $tz = array_value(0, $component->getComponent('VTIMEZONE'));
            $this->_defaultTimezoneId = array_value(0, $tz->getProperty('TZID'))->getValue();
        } else {
            $this->_defaultTimezoneId = (string) Tinebase_Core::get(Tinebase_Core::USERTIMEZONE);
        }
        
        foreach ($component->getChildren() as $children) {
            if (is_array($children)) {
                foreach ($children as $child) {
                    if ($child->getName() === 'VEVENT') {
                        $events->addRecord($this->_getEvent($child));
                    }
                }
            } else {
                if ($children->getName() === 'VEVENT') {
                    $events->addRecord($this->_getEvent($children));
                }
            }
        }
        
        return $events;
    }
}