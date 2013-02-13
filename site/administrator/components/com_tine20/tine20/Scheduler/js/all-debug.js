/*!
 * Tine 2.0 USER CLIENT
 * 
 * license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * copyright   Copyright (c) 2007-2011 Metaways Infosystems GmbH (http://www.metaways.de)
 *
 * FOR MORE DETAILED LICENSE AND COPYRIGHT INFORMATION PLEASE CONSULT THE LICENSE FILE 
 * LOCATED AT: <YOUR TINE 2.0 URL>/LICENSE OR VISIT THE TINE 2.0 HOMEPAGE AT http://www.tine20.org
 */// file: /home/lkneschke/temp/tine20build/temp/tine20/Calendar/js/ParallelEventsRegistry.js
/* 
 * Tine 2.0
 * 
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Cornelius Weiss <c.weiss@metaways.de>
 * @copyright   Copyright (c) 2007-2008 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: ParallelEventsRegistry.js 13905 2010-04-18 15:11:13Z c.weiss@metaways.de $
 */

Ext.ns('Tine.Calendar');

/**
 * registry to cope with parallel events
 * 
 * @class Tine.Events.ParallelEventsRegistry
 * @constructor
 */
Tine.Calendar.ParallelEventsRegistry = function(config) {
    Ext.apply(this, config);
    
    this.dtStartTs = this.dtStart.getTime();
    this.dtEndTs = this.dtEnd.getTime();
    this.dt = this.granularity * Date.msMINUTE;
    
    // init map
    this.frameLength = Math.ceil((this.dtEndTs - this.dtStartTs) / this.dt);
    this.map = [];
}

Tine.Calendar.ParallelEventsRegistry.prototype = {
    /**
     * @cfg {Date} dtStart
     * start of range for this registry
     */
    dtStart: null,
    /**
     * @cfg {Date} dtEnd
     * end of range for this registry 
     */
    dtEnd: null,
    /**
     * @cfg {Number} granularity
     * granularity of this registry in minutes
     */
    granularity: 5,
    /**
     * @cfg {String} dtStartProperty
     */
    dtStartProperty: 'dtstart',
    /**
     * @cfg {String} dtEndProperty
     */
    dtEndProperty: 'dtend',
    
    /**
     * @private {Array} map
     * 
     * array of frames. a frames
     */
    map: null,
    /**
     * @private {Number} dtStartTs
     */
    dtStartTs: null,
    /**
     * @private {Number} dtEndTs
     */
    dtEndTs: null,
    /**
     * @private {Number} dt
     */
    dt: null,
    
    
    
    /**
     * register event
     * @param {Ext.data.Record} event
     * @param {bool} returnAffected
     * @return mixed
     */
    register: function(event, returnAffected) {
        var dtStart = event.get(this.dtStartProperty);
        var dtStartTs = dtStart.getTime();
        var dtEnd = event.get(this.dtEndProperty);
        var dtEndTs = dtEnd.getTime() - 1000;
        
        // layout helper
        event.duration = dtEndTs - dtStartTs;
        
        var startIdx = this.tsToIdx(dtStart);
        var endIdx = this.tsToIdx(dtEndTs);
        
        var position = 0;
        var frame = this.getFrame(position);
        while (! this.isEmptySlice(frame, startIdx, endIdx)) frame = this.getFrame(++position);
        
        this.registerSlice(frame, startIdx, endIdx, event);
        
        event.parallelEventRegistry = {
            registry: this,
            position: position,
            startIdx: startIdx,
            endIdx: endIdx
        };
        
        //console.info('pushed event in frame# ' + position + ' from startIdx"' + startIdx + '" to endIdx "' + endIdx + '".');
        
        if (returnAffected) {
            return this.getEvents(dtStart, dtEnd);
        }
    },
    
    /**
     * unregister event
     * 
     * @param {Ext.data.Record} event
     */
    unregister: function(event) {
        var ri =  event.parallelEventRegistry;
        
        // unregister event
        var frame = this.getFrame(ri.position);
        
        if (! this.skipIntegrityChecks) {
            for (var idx=ri.startIdx; idx<=ri.endIdx; idx++) {
                if (frame[idx] !== event) {
                    throw new Ext.Error('event is not registered at expected position');
                }
            }
        }
        
        this.unregisterSlice(frame, ri.startIdx, ri.endIdx);
        
        event.parallelEventRegistry = null;
    },
    
    /**
     * returns events of current range sorted by duration
     * 
     * @param  {Date} dtStart
     * @param  {Date} dtEnd
     * @return {Array}
     */
    getEvents: function(dtStart, dtEnd, sortByDtStart) {
        var dtStartTs = dtStart.getTime();
        var dtEndTs = dtEnd.getTime() - 1000;
        
        var startIdx = this.tsToIdx(dtStart);
        var endIdx = this.tsToIdx(dtEndTs);
        
        var events = this.getSliceInfo(startIdx, endIdx).events;
        
        // sort by duration and dtstart
        var scope = this;
        events.sort(function(a, b) {
            var d = b.duration - a.duration;
            var s = a.get(scope.dtStartProperty).getTime() - b.get(scope.dtStartProperty).getTime();
            
            return sortByDtStart ? 
                s ? s : d:
                d ? d : s;
        });
        
        return events;
    },
    
    /**
     * get number of maximal parallel events in given time span
     * 
     * @param {Date} dtStart
     * @param {Date} dtEnd
     * @return {Number}
     */
    getMaxParalles: function(dtStart, dtEnd) {
        var dtStartTs = dtStart.getTime();
        var dtEndTs = dtEnd.getTime() - 1000;
        
        var startIdx = this.tsToIdx(dtStart);
        var endIdx = this.tsToIdx(dtEndTs);
        
        return this.getSliceInfo(startIdx, endIdx).maxParallels;
    },
    
    /**
     * get position of given event
     * 
     * @param {Ext.data.Record} event
     * @return {Number}
     */
    getPosition: function(event) {
        if (! event.parallelEventRegistry) {
            throw new Ext.Error("can't compute position of a non registered event");
        }
        
        return event.parallelEventRegistry.position;
    },
    
    /**
     * @private
     * 
     * @param {Number} startIdx
     * @param {Number} endIdx
     * @return {Object}
     */
    getSliceInfo: function(startIdx, endIdx) {
        var events = [];
        var maxParallels = 1;
        for (var idx, frame, position=0; position<this.map.length; position++) {
            frame = this.map[position];
            for (idx=startIdx; idx<=endIdx; idx++) {
                if (frame[idx] && events.indexOf(frame[idx]) === -1) {
                    maxParallels = Math.max(maxParallels, position+1);
                    events.push(frame[idx]);
                }
            }
        }
        
        return {
            events: events,
            maxParallels: maxParallels
        };
    },
    
    /*************************************** frame functions **********************************/
    
    /**
     * returns frame of given position. 
     * If no frame is found on given position it will be created implicitlty
     * 
     * @private
     * @param {Number} position
     * @return {Array}
     */
    getFrame: function(position) {
        if (position > this.map.length +1) {
            throw new Ext.Error('skipping frames is not allowed');
        }
        
        if (! Ext.isArray(this.map[position])) {
            this.map[position] = new Array(this.frameLength);
        }
        
        return this.map[position];
    },
    
    /**
     * checks if a slice in a given frame is free
     * 
     * @private
     * @param {Array} frame
     * @param {Number} startIdx
     * @param {Number} endIdx
     */
    isEmptySlice: function(frame, startIdx, endIdx) {
        for (var idx=startIdx; idx<=endIdx; idx++) {
            if (frame[idx]) {
                return false;
            }
        }
        return true;
    },
    
    /**
     * registers evnet in given frame for given slice
     * 
     * @private
     * @param {Array} frame
     * @param {Number} startIdx
     * @param {Number} endIdx
     * @param {Ext.data.Record} event
     * @return this
     */
    registerSlice: function(frame, startIdx, endIdx, event) {
        for (var idx=startIdx; idx<=endIdx; idx++) {
            frame[idx] = event;
        }
    },
    
    /**
     * @private
     * @param  {Number} ts
     * @return {Number}
     */
    tsToIdx: function(ts) {
        return Math.floor((ts - this.dtStartTs) / this.dt);
    },
    
    /**
     * registers evnet in given frame for given slice 
     * 
     * @private
     * @param {Array} frame
     * @param {Number} startIdx
     * @param {Number} endIdx
     * @return this
     */
    unregisterSlice: function(frame, startIdx, endIdx) {
        for (var idx=startIdx; idx<=endIdx; idx++) {
            frame[idx] = null;
        }
        
        return this;
    }
};
// file: /home/lkneschke/temp/tine20build/temp/tine20/Calendar/js/Model.js
/* 
 * Tine 2.0
 * 
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Cornelius Weiss <c.weiss@metaways.de>
 * @copyright   Copyright (c) 2007-2008 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: Model.js 17998 2010-12-21 21:27:57Z c.weiss@metaways.de $
 */

Ext.ns('Tine.Calendar', 'Tine.Calendar.Model');

/**
 * @namespace Tine.Calendar.Model
 * @class Tine.Calendar.Model.Event
 * @extends Tine.Tinebase.data.Record
 * Event record definition
 */
Tine.Calendar.Model.Event = Tine.Tinebase.data.Record.create(Tine.Tinebase.Model.genericFields.concat([
    { name: 'id' },
    { name: 'dtend', type: 'date', dateFormat: Date.patterns.ISO8601Long },
    { name: 'transp' },
    // ical common fields
    { name: 'class' },
    { name: 'description' },
    { name: 'geo' },
    { name: 'location' },
    { name: 'organizer' },
    { name: 'priority' },
    { name: 'status_id' },
    { name: 'summary' },
    { name: 'url' },
    { name: 'uid' },
    // ical common fields with multiple appearance
    //{ name: 'attach' },
    { name: 'attendee' },
    { name: 'alarms'},
    { name: 'tags' },
    { name: 'notes'},
    //{ name: 'contact' },
    //{ name: 'related' },
    //{ name: 'resources' },
    //{ name: 'rstatus' },
    // scheduleable interface fields
    { name: 'dtstart', type: 'date', dateFormat: Date.patterns.ISO8601Long },
    { name: 'recurid' },
    // scheduleable interface fields with multiple appearance
    { name: 'exdate' },
    //{ name: 'exrule' },
    //{ name: 'rdate' },
    { name: 'rrule' },
    { name: 'is_all_day_event', type: 'bool'},
    { name: 'rrule_until', type: 'date', dateFormat: Date.patterns.ISO8601Long },
    { name: 'originator_tz' },
    // grant helper fields
    {name: 'readGrant'   , type: 'bool'},
    {name: 'editGrant'   , type: 'bool'},
    {name: 'deleteGrant' , type: 'bool'},
    {name: 'editGrant'   , type: 'bool'}
]), {
    appName: 'Calendar',
    modelName: 'Event',
    idProperty: 'id',
    titleProperty: 'summary',
    // ngettext('Event', 'Events', n); gettext('Events');
    recordName: 'Event',
    recordsName: 'Events',
    containerProperty: 'container_id',
    // ngettext('Calendar', 'Calendars', n); gettext('Calendars');
    containerName: 'Calendar',
    containersName: 'Calendars',
    
    /**
     * returns displaycontainer with orignialcontainer as fallback
     * 
     * @return {Array}
     */
    getDisplayContainer: function() {
        var displayContainer = this.get('container_id');
        var currentAccountId = Tine.Tinebase.registry.get('currentAccount').accountId;
        
        var attendeeStore = this.getAttendeeStore();
        
        attendeeStore.each(function(attender) {
            var userAccountId = attender.getUserAccountId();
            if (userAccountId == currentAccountId) {
                var container = attender.get('displaycontainer_id');
                if (container) {
                    displayContainer = container;
                }
                return false;
            }
        }, this);
        
        return displayContainer;
    },
    
    /**
     * is this event a recuring base event?
     * 
     * @return {Boolean}
     */
    isRecurBase: function() {
        return !!this.get('rrule') && !this.get('recurid');
    },
    
    /**
     * is this event a recuring exception?
     * 
     * @return {Boolean}
     */
    isRecurException: function() {
        return !!this.get('recurid') && !( this.idProperty && this.id.match(/^fakeid/));
    },
    
    /**
     * is this event an recuring event instance?
     * 
     * @return {Boolean}
     */
    isRecurInstance: function() {
        return this.id && this.id.match(/^fakeid/);
    },
    
    /**
     * returns store of attender objects
     * 
     * @param  {Array} attendeeData
     * @return {Ext.data.Store}
     */
    getAttendeeStore: function() {
        return Tine.Calendar.Model.Attender.getAttendeeStore(this.get('attendee'));
    },
    
    /**
     * returns attender record of current account if exists, else false
     */
    getMyAttenderRecord: function() {
        var currentAccountId = Tine.Tinebase.registry.get('currentAccount').accountId;
        var attendeeStore = this.getAttendeeStore();
        var myRecord = false;
        
        attendeeStore.each(function(attender) {
            var userAccountId = attender.getUserAccountId();
            if (userAccountId == currentAccountId) {
                myRecord = attender;
                return false;
            }
        }, this);
        
        return myRecord;
    }
});


/**
 * @namespace Tine.Calendar.Model
 * 
 * get default data for a new event
 * @todo: attendee according to calendar selection
 *  
 * @return {Object} default data
 * @static
 */ 
Tine.Calendar.Model.Event.getDefaultData = function() {
    var app = Tine.Tinebase.appMgr.get('Calendar');
    
    var dtstart = new Date().clearTime().add(Date.HOUR, (new Date().getHours() + 1));
    
    // if dtstart is out of current period, take start of current period
    var mainPanel = app.getMainScreen().getCenterPanel();
    var period = mainPanel.getCalendarPanel(mainPanel.activeView).getView().getPeriod();
    if (period.from.getTime() > dtstart.getTime() || period.until.getTime() < dtstart.getTime()) {
        dtstart = period.from.clearTime(true).add(Date.HOUR, 9);
    }
    
    var data = {
        summary: '',
        dtstart: dtstart,
        dtend: dtstart.add(Date.HOUR, 1),
        container_id: app.getMainScreen().getWestPanel().getContainerTreePanel().getAddCalendar(),
        transp: 'OPAQUE',
        editGrant: true,
        organizer: Tine.Tinebase.registry.get('userContact'),
        attendee: [
            Ext.apply(Tine.Calendar.Model.Attender.getDefaultData(), {
                user_type: 'user',
                user_id: Tine.Tinebase.registry.get('currentAccount'),
                status: 'ACCEPTED'
            })
        ]
    };
    
    return data;
};

Tine.Calendar.Model.Event.getFilterModel = function() {
    var app = Tine.Tinebase.appMgr.get('Calendar');
    
    return [
        {label: _('Quick search'), field: 'query', operators: ['contains']},
        {filtertype: 'tine.widget.container.filtermodel', app: app, recordClass: Tine.Calendar.Model.Event, /*defaultOperator: 'in',*/ defaultValue: {path: Tine.Tinebase.container.getMyNodePath()}},
        {filtertype: 'calendar.attendee'},
        {filtertype: 'calendar.attendeestatus'},
        {filtertype: 'addressbook.contact', field: 'organizer', label: app.i18n._('Organizer')},
        {filtertype: 'tinebase.tag', app: app}
    ];
};

/**
 * @namespace Tine.Calendar.Model
 * @class Tine.Calendar.Model.EventJsonBackend
 * @extends Tine.Tinebase.data.RecordProxy
 * 
 * JSON backend for events
 */
Tine.Calendar.Model.EventJsonBackend = Ext.extend(Tine.Tinebase.data.RecordProxy, {
    
    /**
     * Creates a recuring event exception
     * 
     * @param {Tine.Calendar.Model.Event} event
     * @param {Boolean} deleteInstance
     * @param {Boolean} deleteAllFollowing
     * @param {Object} options
     * @return {String} transaction id
     */
    createRecurException: function(event, deleteInstance, deleteAllFollowing, options) {
        options = options || {};
        options.params = options.params || {};
        options.beforeSuccess = function(response) {
            return [this.recordReader(response)];
        };
        
        var p = options.params;
        p.method = this.appName + '.createRecurException';
        p.recordData = event.data;
        p.deleteInstance = deleteInstance ? 1 : 0;
        p.deleteAllFollowing = deleteAllFollowing ? 1 : 0;
        
        return this.doXHTTPRequest(options);
    },
    
    /**
     * delete a recuring event series
     * 
     * @param {Tine.Calendar.Model.Event} event
     * @param {Object} options
     * @return {String} transaction id
     */
    deleteRecurSeries: function(event, options) {
        options = options || {};
        options.params = options.params || {};
        
        var p = options.params;
        p.method = this.appName + '.deleteRecurSeries';
        p.recordData = event.data;
        
        return this.doXHTTPRequest(options);
    },
    
    /**
     * updates a recuring event series
     * 
     * @param {Tine.Calendar.Model.Event} event
     * @param {Object} options
     * @return {String} transaction id
     */
    updateRecurSeries: function(event, options) {
        options = options || {};
        options.params = options.params || {};
        options.beforeSuccess = function(response) {
            return [this.recordReader(response)];
        };
        
        var p = options.params;
        p.method = this.appName + '.updateRecurSeries';
        p.recordData = event.data;
        
        return this.doXHTTPRequest(options);
    }
});

/*
 * default event backend
 */
if (Tine.Tinebase.widgets) {
    Tine.Calendar.backend = new Tine.Calendar.Model.EventJsonBackend({
        appName: 'Calendar',
        modelName: 'Event',
        recordClass: Tine.Calendar.Model.Event
    });
} else {
    Tine.Calendar.backend = new Tine.Tinebase.data.MemoryBackend({
        appName: 'Calendar',
        modelName: 'Event',
        recordClass: Tine.Calendar.Model.Event
    });
}

/**
 * @namespace Tine.Calendar.Model
 * @class Tine.Calendar.Model.Attender
 * @extends Tine.Tinebase.data.Record
 * Attender Record Definition
 */
Tine.Calendar.Model.Attender = Tine.Tinebase.data.Record.create([
    {name: 'id'},
    {name: 'cal_event_id'},
    {name: 'user_id', sortType:  function(user_id) {
        if (user_id && user_id.n_fileas) {
            return user_id.n_fileas;
        } else if (user_id && user_id.name) {
            return user_id.name;
        } else {
            return user_id;
        }
    }},
    {name: 'user_type'},
    {name: 'role'},
    {name: 'quantity'},
    {name: 'status'},
    {name: 'status_authkey'},
    {name: 'displaycontainer_id'}
], {
    appName: 'Calendar',
    modelName: 'Attender',
    idProperty: 'id',
    titleProperty: 'name',
    // ngettext('Attender', 'Attendee', n); gettext('Attendee');
    recordName: 'Attender',
    recordsName: 'Attendee',
    containerProperty: 'cal_event_id',
    // ngettext('Event', 'Events', n); gettext('Events');
    containerName: 'Event',
    containersName: 'Events',
    
    /**
     * gets name of attender
     * 
     * @return {String}
     *
    getName: function() {
        var user_id = this.get('user_id');
        if (! user_id) {
            return Tine.Tinebase.appMgr.get('Calendar').i18n._('No Information');
        }
        
        var userData = (typeof user_id.get == 'function') ? user_id.data : user_id;
    },
    */
    
    /**
     * returns account_id if attender is/has a user account
     * 
     * @return {String}
     */
    getUserAccountId: function() {
        var user_type = this.get('user_type');
        if (user_type == 'user' || user_type == 'groupmember') {
            var user_id = this.get('user_id');
            if (! user_id) {
                return null;
            }
            
            // we expect user_id to be a user or contact object or record
            if (typeof user_id.get == 'function') {
                if (user_id.get('contact_id')) {
                    // user_id is a account record
                    return user_id.get('accountId');
                } else {
                    // user_id is a contact record
                    return user_id.get('account_id');
                }
            } else if (user_id.hasOwnProperty('contact_id')) {
                // user_id contains account data
                return user_id.accountId;
            } else if (user_id.hasOwnProperty('account_id')) {
                // user_id contains contact data
                return user_id.account_id;
            }
            
            // this might happen if contact resolved, due to right restrictions
            return user_id;
            
        }
        return null;
    },
    
    /**
     * returns id of attender of any kind
     */
    getUserId: function() {
        var user_id = this.get('user_id');
        if (! user_id) {
            return null;
        }
        
        var userData = (typeof user_id.get == 'function') ? user_id.data : user_id;
        
        if (!userData) {
            return null;
        }
        
        if (typeof userData != 'object') {
            return userData;
        }
        
        switch (this.get('user_type')) {
            case 'user':
                if (userData.hasOwnProperty('contact_id')) {
                    // userData contains account
                    return userData.contact_id;
                } else if (userData.hasOwnProperty('account_id')) {
                    // userData contains contact
                    return userData.id;
                }
                break;
            default:
                return userData.id
                break;
        }
    }
});

/**
 * @namespace Tine.Calendar.Model
 * 
 * get default data for a new attender
 *  
 * @return {Object} default data
 * @static
 */ 
Tine.Calendar.Model.Attender.getDefaultData = function() {
    return {
        user_type: 'user',
        role: 'REQ',
        quantity: 1,
        status: 'NEEDS-ACTION'
    };
};

/**
 * @namespace Tine.Calendar.Model
 * 
 * creates store of attender objects
 * 
 * @param  {Array} attendeeData
 * @return {Ext.data.Store}
 * @static
 */ 
Tine.Calendar.Model.Attender.getAttendeeStore = function(attendeeData) {
    var attendeeStore = new Ext.data.SimpleStore({
        fields: Tine.Calendar.Model.Attender.getFieldDefinitions(),
        sortInfo: {field: 'user_id', direction: 'ASC'}
    });
    
    Ext.each(attendeeData, function(attender) {
        var record = new Tine.Calendar.Model.Attender(attender, attender.id);
        attendeeStore.addSorted(record);
    });
    
    return attendeeStore;
};

Tine.Calendar.Model.Attender.getAttendeeStatusStore = function() {
    if (! Tine.Calendar.Model.Attender.attendeeStatusStore) {
        var app = Tine.Tinebase.appMgr.get('Calendar');
        Tine.Calendar.Model.Attender.attendeeStatusStore = new Ext.data.ArrayStore({
            storeId: 'calendar.attener.status',
            idIndex: 0,  
            fields: ['id', 'status_name'],
            data: [
                ['NEEDS-ACTION', app.i18n._('No response')],
                ['ACCEPTED',     app.i18n._('Accepted')   ],
                ['DECLINED',     app.i18n._('Declined')   ],
                ['TENTATIVE',    app.i18n._('Tentative')  ]
            ]
        });
    }
    
    return Tine.Calendar.Model.Attender.attendeeStatusStore;
}

/**
 * @namespace Tine.Calendar.Model
 * @class Tine.Calendar.Model.Resource
 * @extends Tine.Tinebase.data.Record
 * Resource Record Definition
 */
Tine.Calendar.Model.Resource = Tine.Tinebase.data.Record.create(Tine.Tinebase.Model.genericFields.concat([
    {name: 'id'},
    {name: 'name'},
    {name: 'description'},
    {name: 'email'},
    {name: 'is_location', type: 'bool'},
    {name: 'tags'},
    {name: 'notes'}
]), {
    appName: 'Calendar',
    modelName: 'Resource',
    idProperty: 'id',
    titleProperty: 'name',
    // ngettext('Resource', 'Resources', n); gettext('Resources');
    recordName: 'Resource',
    recordsName: 'Resources',
    containerProperty: null
});

/* lets try it with Ext.Direct
Tine.Calendar.backend = new Tine.Calendar.Model.EventJsonBackend({
    appName: 'Calendar',
    modelName: 'Resource',
    recordClass: Tine.Calendar.Model.Resource
});
*/

// file: /home/lkneschke/temp/tine20build/temp/tine20/Calendar/js/AdminPanel.js
/*
 * Tine 2.0
 * 
 * @package     Calendar
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Philipp Schuele <p.schuele@metaways.de>
 * @copyright   Copyright (c) 2009 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id:GridPanel.js 7170 2009-03-05 10:58:55Z p.schuele@metaways.de $
 *
 */

Ext.namespace('Tine.Calendar');

/**
 * admin settings panel
 * 
 * @namespace   Tine.Calendar
 * @class       Tine.Calendar.AdminPanel
 * @extends     Ext.TabPanel
 * 
 * <p>Calendar Admin Panel</p>
 * <p><pre>
 * </pre></p>
 * 
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Philipp Schuele <p.schuele@metaways.de>
 * @copyright   Copyright (c) 2009 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id:GridPanel.js 7170 2009-03-05 10:58:55Z p.schuele@metaways.de $
 * 
 * @param       {Object} config
 * @constructor
 * Create a new Tine.Calendar.AdminPanel
 */
Tine.Calendar.AdminPanel = Ext.extend(Ext.TabPanel, {

    activeTab: 0,

    /**
     * @private
     */
    initComponent: function() {
        
        this.app = Tine.Tinebase.appMgr.get('Calendar');
        
        this.items = [new Tine.Calendar.ResourcesGridPanel({
            title: this.app.i18n._('Manage Resources'),
            disabled: !Tine.Tinebase.common.hasRight('manage_resources', 'Calendar')
        })];
        
        Tine.Calendar.AdminPanel.superclass.initComponent.call(this);
    }
});
    
/**
 * Calendar Admin Panel Popup
 * 
 * @param   {Object} config
 * @return  {Ext.ux.Window}
 */
Tine.Calendar.AdminPanel.openWindow = function (config) {
    var window = Tine.WindowFactory.getWindow({
        width: 500,
        height: 470,
        name: 'cal-mange-resources',
        contentPanelConstructor: 'Tine.Calendar.AdminPanel',
        contentPanelConstructorConfig: config
    }); 
};

// file: /home/lkneschke/temp/tine20build/temp/tine20/Calendar/js/CalendarPanel.js
/* 
 * Tine 2.0
 * 
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Cornelius Weiss <c.weiss@metaways.de>
 * @copyright   Copyright (c) 2009 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: CalendarPanel.js 18996 2011-02-01 17:31:59Z c.weiss@metaways.de $
 */
 
Date.msSECOND = 1000;
Date.msMINUTE = 60 * Date.msSECOND;
Date.msHOUR   = 60 * Date.msMINUTE;
Date.msDAY    = 24 * Date.msHOUR;
Date.msWEEK   =  7 * Date.msDAY;

Ext.ns('Tine.Calendar');

/**
 * @class Tine.Calendar.CalendarPanel
 * @namespace Tine.Calendar
 * @extends Ext.Panel
 * Calendar Panel, pooling together store, and view <br/>
 * @author Cornelius Weiss <c.weiss@metaways.de>
 * @version $Id: CalendarPanel.js 18996 2011-02-01 17:31:59Z c.weiss@metaways.de $
 */
Tine.Calendar.CalendarPanel = Ext.extend(Ext.Panel, {
    /**
     * @cfg {Tine.Calendar.someView} view
     */
    view: null,
    /**
     * @cfg {Ext.data.Store} store
     */
    store: null,
    /**
     * @cfg {Bool} border
     */
    border: false,
    /**
     * @cfg {String} loadMaskText
     * _('Loading events, please wait...')
     */
    loadMaskText: 'Loading events, please wait...',
    
    /**
     * @private
     */
    initComponent: function() {
        Tine.Calendar.CalendarPanel.superclass.initComponent.call(this);
        
        this.app = Tine.Tinebase.appMgr.get('Calendar');
        
        this.loadMaskText = this.app.i18n._hidden(this.loadMaskText);
        
        this.selModel = this.selModel || new Tine.Calendar.EventSelectionModel();
        
        this.autoScroll = false;
        this.autoWidth = false;
        
        /**
         * @event click
         * fired if an event got clicked
         * @param {Tine.Calendar.Model.Event} event
         * @param {Ext.EventObject} e
         */
        /**
         * @event contextmenu
         * fired if an event got contextmenu 
         * @param {Ext.EventObject} e
         */
        /**
         * @event dblclick
         * fired if an event got dblclicked
         * @param {Tine.Calendar.Model.Event} event
         * @param {Ext.EventObject} e
         */
        /**
         * @event changeView
         * fired if user wants to change view
         * @param {String} requested view name
         * @param {mixed} start param of requested view
         */
        /**
         * @event changePeriod
         * fired when period changed
         * @param {Object} period
         */
        this.relayEvents(this.view, ['changeView', 'changePeriod', 'click', 'dblclick', 'contextmenu']);
        
        this.store.on('beforeload', this.onBeforeLoad, this);
        this.store.on('load', this.onLoad, this);
    },
    
    /**
     * Returns selection model
     * 
     * @return {Tine.Calendar.EventSelectionModel}
     */
    getSelectionModel: function() {
        return this.selModel;
    },
    
    /**
     * Returns data store
     * 
     * @return {Ext.data.Store}
     */
    getStore: function() {
        return this.store;
    },
    
    /**
     * Retruns calendar View
     * 
     * @return {Tine.Calendar.View}
     */
    getView: function() {
        return this.view;
    },
    
    onAddEvent: function(event, checkBusyConficts) {
        this.setLoading(true);
        
        // remove temporary id
        if (event.get('id').match(/new/)) {
            event.set('id', '');
        }
        
        if (event.isRecurBase()) {
            this.loadMask.show();
        }
        
        Tine.Calendar.backend.saveRecord(event, {
            scope: this,
            success: function(createdEvent) {
                if (createdEvent.isRecurBase()) {
                    this.store.load({refresh: true});
                } else {
                    this.store.remove(event);
                    this.store.add(createdEvent);
                    this.setLoading(false);
                    this.view.getSelectionModel().select(createdEvent);
                }
            },
            failure: this.onProxyFail.createDelegate(this, [event], true)
        }, {
            checkBusyConficts: checkBusyConficts === false ? 0 : 1
        });
    },
    
    onBeforeLoad: function(store, options) {
        if (! options.refresh) {
            if (this.rendered) {
                // defer to have the loadMask centered in case of rendering actions
                this.loadMask.show.defer(50, this.loadMask);
            }
            this.store.each(this.view.removeEvent, this.view);
        }
        
        options.params = options.params || {};
        
        var filter = options.params.filter ? options.params.filter : [];
        
        filter.push({field: 'period', operator: 'within', value: this.getView().getPeriod() });
    },
    
    onLoad: function() {
        if (this.rendered) {
            this.loadMask.hide();
        }
    },
    
    onProxyFail: function(error, event) {
        this.setLoading(false);
        
        if (error.code == 901) {
            
            // resort fbInfo to combine all events of a attender
            var busyAttendee = [];
            var conflictEvents = {};
            var attendeeStore = Tine.Calendar.Model.Attender.getAttendeeStore(event.get('attendee'));
            Ext.each(error.freebusyinfo, function(fbinfo) {
                attendeeStore.each(function(a) {
                    if (a.get('user_type') == fbinfo.user_type && a.getUserId() == fbinfo.user_id) {
                        if (busyAttendee.indexOf(a) < 0) {
                            busyAttendee.push(a);
                            conflictEvents[a.id] = [];
                        }
                        conflictEvents[a.id].push(fbinfo);
                    }
                });
            }, this);
            
            // generate html for each busy attender
            var busyAttendeeHTML = '';
            Ext.each(busyAttendee, function(busyAttender) {
                // TODO refactore name handling of attendee
                //      -> attender model needs knowlege of how to get names!
                //var attenderName = a.getName();
                var attenderName = Tine.Calendar.AttendeeGridPanel.prototype.renderAttenderName.call(Tine.Calendar.AttendeeGridPanel.prototype, busyAttender.get('user_id'), false, busyAttender);
                busyAttendeeHTML += '<div class="cal-conflict-attendername">' + attenderName + '</div>';
                
                var eventInfos = [];
                Ext.each(conflictEvents[busyAttender.id], function(fbInfo) {
                    var format = 'H:i';
                    var dateFormat = Ext.form.DateField.prototype.format;
                    if (event.get('dtstart').format(dateFormat) != event.get('dtend').format(dateFormat) ||
                        Date.parseDate(fbInfo.dtstart, Date.patterns.ISO8601Long).format(dateFormat) != Date.parseDate(fbInfo.dtend, Date.patterns.ISO8601Long).format(dateFormat))
                    {
                        format = dateFormat + ' ' + format;
                    }
                    
                    var eventInfo = Date.parseDate(fbInfo.dtstart, Date.patterns.ISO8601Long).format(format) + ' - ' + Date.parseDate(fbInfo.dtend, Date.patterns.ISO8601Long).format(format);
                    if (fbInfo.event && fbInfo.event.summary) {
                        eventInfo += ' : ' + fbInfo.event.summary;
                    }
                    eventInfos.push(eventInfo);
                }, this);
                busyAttendeeHTML += '<div class="cal-conflict-eventinfos">' + eventInfos.join(', <br />') + '</div>';
                
            });
            
            this.conflictConfirmWin = Tine.widgets.dialog.MultiOptionsDialog.openWindow({
                modal: true,
                allowCancel: false,
                height: 150 + 15*error.freebusyinfo.length,
                title: this.app.i18n._('Scheduling Conflict'),
                questionText: '<div class = "cal-conflict-heading">' +
                                   this.app.i18n._('The following attendee are busy at the requested time:') + 
                               '</div>' +
                               busyAttendeeHTML,
                options: [
                    {text: this.app.i18n._('Ignore Conflict'), name: 'ignore'},
                    {text: this.app.i18n._('Edit Event'), name: 'edit'}
                ],
                scope: this,
                handler: function(option) {
                    switch (option) {
                        case 'ignore':
                            this.onAddEvent(event, false);
                            this.conflictConfirmWin.close();
                            break;
                        
                        case 'edit':
                        default:
                            this.view.getSelectionModel().select(event);
                            this.view.fireEvent('dblclick', this.view, event);
                            this.conflictConfirmWin.close();
                            break;
                    }
                }
            });
            
        } else {
            Tine.Tinebase.ExceptionHandler.handleRequestException(error);
        }
    },
    
    onUpdateEvent: function(event) {
        this.setLoading(true);
        
        if (event.isRecurBase()) {
            this.loadMask.show();
        }
        
        if (event.isRecurBase() && ! event.get('rrule').newrule) {
            Ext.MessageBox.confirm(
                this.app.i18n._('Confirm Update of Series'),
                this.app.i18n._('Do you really want to update all events of this recurring event series?'),
                function(btn) {
                    if(btn == 'yes') {
                        this.loadMask.show();
                        this.onUpdateEventAction(event);
                        this.store.load({refresh: true});
                    } else {
                        this.loadMask.show();
                        this.store.load({refresh: true});
                    }
                }, this
            );
        } else if (event.isRecurInstance()) {
            this.deleteMethodWin = Tine.widgets.dialog.MultiOptionsDialog.openWindow({
                title: this.app.i18n._('Update Event'),
                scope: this,
                options: [
                    {text: this.app.i18n._('Update nothing'), name: 'cancel'},
                    {text: this.app.i18n._('Update whole series'), name: 'series'},
                    {text: this.app.i18n._('Update this event only'), name: 'this'}
                ],
                handler: function(option) {
                    switch (option) {
                        case 'series':
                            this.loadMask.show();
                            
                            var options = {
                                scope: this,
                                success: function() {
                                    this.store.load({refresh: true});
                                },
                                failure: this.onProxyFail.createDelegate(this, [event], true)
                            };
                            
                            Tine.Calendar.backend.updateRecurSeries(event, options);
                            break;
                            
                        case 'this':
                            var options = {
                                scope: this,
                                success: function(updatedEvent) {
                                    event =  this.store.indexOf(event) != -1 ? event : this.store.getById(event.id);
                        
                                    this.store.remove(event);
                                    this.store.add(updatedEvent);
                                    this.setLoading(false);
                                    this.view.getSelectionModel().select(updatedEvent);
                                },
                                failure: this.onProxyFail.createDelegate(this, [event], true)
                            };
                            
                            Tine.Calendar.backend.createRecurException(event, false, false, options);
                                
                        default:
                            this.loadMask.show();
                            this.store.load({refresh: true});
                            break;
                    }
                }
            });
        } else {
            this.onUpdateEventAction(event);
        }
    },
    
    onUpdateEventAction: function(event) {
        Tine.Calendar.backend.saveRecord(event, {
            scope: this,
            success: function(updatedEvent) {
                //console.log('Backend returned updated event -> replace event in view');
                if (updatedEvent.isRecurBase()) {
                    this.store.load({refresh: true});
                } else {
                    event =  this.store.indexOf(event) != -1 ? event : this.store.getById(event.id);
                    
                    this.store.remove(event);
                    this.store.add(updatedEvent);
                    this.setLoading(false);
                    this.view.getSelectionModel().select(updatedEvent);
                }
            },
            failure: this.onProxyFail.createDelegate(this, [event], true)
        }, {
            checkBusyConficts: 1
        });
    },
    
    setLoading: function(bool) {
        var tbar = this.getTopToolbar();
        if (tbar && tbar.loading) {
            tbar.loading[bool ? 'disable' : 'enable']();
        }
    },
    
    /*
    onRemoveEvent: function(store, event, index) {
        console.log(event);
        console.log('A existing event has been deleted -> call backend delete'); 
    },
    */
    
    /**
     * @private
     */
    onRender: function(ct, position) {
        Tine.Calendar.CalendarPanel.superclass.onRender.apply(this, arguments);
        
        this.loadMask = new Ext.LoadMask(this.body, {msg: this.loadMaskText});
        
        var c = this.body;
        this.el.addClass('cal-panel');
        this.view.init(this);
        
        // quick add/update actions
        this.view.on('addEvent', this.onAddEvent, this);
        this.view.on('updateEvent', this.onUpdateEvent, this);
        
        this.view.on("click", this.onClick, this);
        this.view.on("dblclick", this.onDblClick, this);
        this.view.on("contextmenu", this.onContextMenu, this);
        
        c.on("keydown", this.onKeyDown, this);
        //this.relayEvents(c, ["keypress"]);
        
        this.view.render();
    },
    
    /**
     * @private
     */
    afterRender : function(){
        Tine.Calendar.CalendarPanel.superclass.afterRender.call(this);
        
        this.view.layout();
        this.view.afterRender();
        
        this.viewReady = true;
    },
    
    /**
     * @private
     */
    onResize: function(ct, position) {
        Tine.Calendar.CalendarPanel.superclass.onResize.apply(this, arguments);
        if(this.viewReady){
            this.view.layout();
        }
    },
    
    /**
     * @private
     */
    processEvent : function(name, event){
        //console.log('Tine.Calendar.CalendarPanel::processEvent "' + name + '" on envent: ' + event.id );
    },
    
    /**
     * @private
     */
    onClick : function(event, e){
        this.processEvent("click", event);
    },

    /**
     * @private
     */
    onContextMenu : function(event, e){
        this.processEvent("contextmenu", event);
    },

    /**
     * @private
     */
    onDblClick : function(event, e){
        this.processEvent("dblclick", event);
    },
    
    /**
     * @private
     */
    onKeyDown : function(e){
        this.fireEvent("keydown", e);
    }
    
});
// file: /home/lkneschke/temp/tine20build/temp/tine20/Calendar/js/EventUI.js
/* 
 * Tine 2.0
 * 
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Cornelius Weiss <c.weiss@metaways.de>
 * @copyright   Copyright (c) 2007-2008 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: EventUI.js 17601 2010-12-03 15:49:35Z c.weiss@metaways.de $
 * 
 * @note: lot to do here, i just started to move stuff from views here
 */
 
Ext.ns('Tine.Calendar');

Tine.Calendar.EventUI = function(event) {
    this.event = event;
    this.domIds = [];
    this.app = Tine.Tinebase.appMgr.get('Calendar');
    this.init();
};

Tine.Calendar.EventUI.prototype = {
    addClass: function(cls) {
        Ext.each(this.getEls(), function(el){
            el.addClass(cls);
        });
    },
    
    blur: function() {
        Ext.each(this.getEls(), function(el){
            el.blur();
        });
    },
    
    clearDirty: function() {
        Ext.each(this.getEls(), function(el) {
            el.setOpacity(1, 1);
        });
    },
    
    focus: function() {
        Ext.each(this.getEls(), function(el){
            el.focus();
        });
    },
    
    /**
     * returns events dom
     * @return {Array} of Ext.Element
     */
    getEls: function() {
        var domEls = [];
        for (var i=0; i<this.domIds.length; i++) {
            var el = Ext.get(this.domIds[i]);
            if (el) {
                domEls.push(el);
            }
        }
        return domEls;
    },
    
    init: function() {
        // shortcut
        //this.colMgr = Tine.Calendar.colorMgr;
    },
    
    markDirty: function() {
        Ext.each(this.getEls(), function(el) {
            el.setOpacity(0.5, 1);
        });
    },
    
    onSelectedChange: function(state){
        if(state){
            //this.focus();
            this.addClass('cal-event-active');
            this.setStyle({'z-index': 1000});
            
        }else{
            //this.blur();
            this.removeClass('cal-event-active');
            this.setStyle({'z-index': 100});
        }
    },
    
    /**
     * removes a event from the dom
     */
    remove: function() {
        var eventEls = this.getEls();
        for (var i=0; i<eventEls.length; i++) {
            if (eventEls[i] && typeof eventEls[i].remove == 'function') {
                eventEls[i].remove();
            }
        }
        this.domIds = [];
    },
    
    removeClass: function(cls) {
        Ext.each(this.getEls(), function(el){
            el.removeClass(cls);
        });
    },
    
    render: function() {
        
    },
    
    setOpacity: function(v) {
        Ext.each(this.getEls(), function(el){
            el.setStyle(v);
        });
    },
    
    setStyle: function(style) {
        Ext.each(this.getEls(), function(el){
            el.setStyle(style);
        });
    }
    
};



Tine.Calendar.DaysViewEventUI = Ext.extend(Tine.Calendar.EventUI, {
    
    clearDirty: function() {
        Tine.Calendar.DaysViewEventUI.superclass.clearDirty.call(this);
        
        Ext.each(this.getEls(), function(el) {
            el.setStyle({'border-style': 'solid'});
        });
    },
    /**
     * get diff of resizeable
     * 
     * @param {Ext.Resizeable} rz
     */
    getRzInfo: function(rz, width, height) {
        var rzInfo = {};
        
        var event = rz.event;
        var view = event.view;
        
        // NOTE proxy might be gone after resize
        var box = rz.proxy.getBox();
        var width = width ? width: box.width;
        var height =  height? height : box.height;
        
        var originalDuration = (event.get('dtend').getTime() - event.get('dtstart').getTime()) / Date.msMINUTE;
        
        if(event.get('is_all_day_event')) {
            var dayWidth = Ext.fly(view.wholeDayArea).getWidth() / view.numOfDays;
            rzInfo.diff = Math.round((width - rz.originalWidth) / dayWidth);
            
        } else {
            rzInfo.diff = Math.round((height - rz.originalHeight) * (view.timeGranularity / view.granularityUnitHeights));
            // neglegt diffs due to borders etc.
            rzInfo.diff = Math.round(rzInfo.diff/15) * 15;
        }
        rzInfo.duration = originalDuration + rzInfo.diff;
        
        if(event.get('is_all_day_event')) {
            rzInfo.dtend = event.get('dtend').add(Date.DAY, rzInfo.diff);
        } else {
            rzInfo.dtend = event.get('dtstart').add(Date.MINUTE, rzInfo.duration);
        }
        
        return rzInfo;
    },
    
    markDirty: function() {
        Tine.Calendar.DaysViewEventUI.superclass.markDirty.call(this);
        
        Ext.each(this.getEls(), function(el) {
            el.setStyle({'border-style': 'dashed'});
        });
    },
    
    onSelectedChange: function(state){
        Tine.Calendar.DaysViewEventUI.superclass.onSelectedChange.call(this, state);
        if(state){
            this.addClass('cal-daysviewpanel-event-active');
            
        }else{
            this.removeClass('cal-daysviewpanel-event-active');
        }
    },
    
    render: function(view) {
        this.event.view = view;
        
        this.colorSet = Tine.Calendar.colorMgr.getColor(this.event);
        this.event.colorSet = this.colorSet;
        
        this.dtStart = this.event.get('dtstart');
        this.startColNum = view.getColumnNumber(this.dtStart);
        
        this.dtEnd = this.event.get('dtend');
        
        if (this.event.get('editGrant')) {
            this.extraCls = 'cal-daysviewpanel-event-editgrant';
        }
        
        // 00:00 in users timezone is a spechial case where the user expects
        // something like 24:00 and not 00:00
        if (this.dtEnd.format('H:i') == '00:00') {
            this.dtEnd = this.dtEnd.add(Date.MINUTE, -1);
        }
        this.endColNum = view.getColumnNumber(this.dtEnd);
        
        // skip dates not in our diplay range
        if (this.endColNum < 0 || this.startColNum > view.numOfDays-1) {
            return;
        }
        
        // compute status icons
        this.statusIcons = [];
        if (this.event.get('class') === 'PRIVATE') {
            this.statusIcons.push({
                status: 'private',
                text: this.app.i18n._('private classification')
            });
        }
        
        if (this.event.get('rrule')) {
            this.statusIcons.push({
                status: 'recur',
                text: this.app.i18n._('recurring event')
            });
        }
        
        if (! Ext.isEmpty(this.event.get('alarms'))) {
            this.statusIcons.push({
                status: 'alarm',
                text: this.app.i18n._('has alarm')
            });
        }
        
        var myAttenderRecord = this.event.getMyAttenderRecord();
        if (myAttenderRecord) {
            this.statusIcons.push({
                status: myAttenderRecord.get('status'),
                text: Tine.Calendar.Model.Attender.getAttendeeStatusStore().getById(myAttenderRecord.get('status')).get('status_name')
            });
        }
        
        var registry = this.event.get('is_all_day_event') ? view.parallelWholeDayEventsRegistry : view.parallelScrollerEventsRegistry;
        
        var position = registry.getPosition(this.event);
        var maxParallels = registry.getMaxParalles(this.dtStart, this.dtEnd);
        
        if (this.event.get('is_all_day_event')) {
            this.renderAllDayEvent(view, maxParallels, position);
        } else {
            this.renderScrollerEvent(view, maxParallels, position);
        }
        
        if (this.event.dirty) {
            // the event was selected before
            this.onSelectedChange(true);
        }
    },
    
    renderAllDayEvent: function(view, parallels, pos) {
        // lcocal COPY!
        var extraCls = this.extraCls;
        
        var offsetWidth = Ext.fly(view.wholeDayArea).getWidth();
        
        var width = Math.round(offsetWidth * (this.dtEnd.getTime() - this.dtStart.getTime()) / (view.numOfDays * Date.msDAY)) -5;
        var left = Math.round(offsetWidth * (this.dtStart.getTime() - view.startDate.getTime()) / (view.numOfDays * Date.msDAY));
        
        if (this.startColNum < 0) {
            width = width - Math.abs(this.startColNum) * (offsetWidth/view.numOfDays);
            left = 0;
            extraCls = extraCls + ' cal-daysviewpanel-event-cropleft';
        }
        
        if (this.endColNum > view.numOfDays) {
            width = width - Math.abs(this.endColNum - view.numOfDays) * (offsetWidth/view.numOfDays);
            extraCls = extraCls + ' cal-daysviewpanel-event-cropright';
        }
        
        var domId = Ext.id() + '-event:' + this.event.get('id');
        this.domIds.push(domId);
        
        var eventEl = view.templates.wholeDayEvent.insertFirst(view.wholeDayArea, {
            id: domId,
            summary: this.event.get('summary'),
            startTime: this.dtStart.format('H:i'),
            extraCls: extraCls,
            color: this.colorSet.color,
            bgColor: this.colorSet.light,
            textColor: this.colorSet.text,
            zIndex: 100,
            width: width  +'px',
            height: '15px',
            left: left + 'px',
            top: pos * 18 + 'px',//'1px'
            statusIcons: this.statusIcons
        }, true);
        
        if (this.event.dirty) {
            eventEl.setStyle({'border-style': 'dashed'});
            eventEl.setOpacity(0.5);
        }
        
        if (! (this.endColNum > view.numOfDays) && this.event.get('editGrant')) {
            this.resizeable = new Ext.Resizable(eventEl, {
                handles: 'e',
                disableTrackOver: true,
                dynamic: true,
                //dynamic: !!this.event.isRangeAdd,
                widthIncrement: Math.round(offsetWidth / view.numOfDays),
                minWidth: Math.round(offsetWidth / view.numOfDays),
                listeners: {
                    scope: view,
                    resize: view.onEventResize,
                    beforeresize: view.onBeforeEventResize
                }
            });
        }
        //console.log([eventEl.dom, parallels, pos])
    },
    
    renderScrollerEvent: function(view, parallels, pos) {
        var scrollerHeight = view.granularityUnitHeights * ((24 * 60)/view.timeGranularity);
        
        for (var currColNum=this.startColNum; currColNum<=this.endColNum; currColNum++) {
            
            // lcocal COPY!
            var extraCls = this.extraCls;
            
            if (currColNum < 0 || currColNum >= view.numOfDays) {
                continue;
            }
            
            var top = view.getTimeOffset(this.dtStart);
            var height = this.startColNum == this.endColNum ? view.getTimeHeight(this.dtStart, this.dtEnd) : view.getTimeOffset(this.dtEnd);
            
            if (currColNum != this.startColNum) {
                top = 0;
                extraCls = extraCls + ' cal-daysviewpanel-event-croptop';
            }
            
            if (this.endColNum != currColNum) {
                height = view.getTimeHeight(this.dtStart, this.dtStart.add(Date.DAY, 1));
                extraCls = extraCls + ' cal-daysviewpanel-event-cropbottom';
            }
            
            var domId = Ext.id() + '-event:' + this.event.get('id');
            this.domIds.push(domId);
            
            // minimal height
            if (height <= 12) {
                height = 12;
            }
            
            // minimal top
            if (top > scrollerHeight -12) {
                top = scrollerHeight -12;
            }
            
            var eventEl = view.templates.event.append(view.getDateColumnEl(currColNum), {
                id: domId,
                summary: height >= 24 ? this.event.get('summary') : '',
                startTime: (height >= 24 && top <= scrollerHeight-24) ? this.dtStart.format('H:i') : this.dtStart.format('H:i') + ' ' +  this.event.get('summary'),
                extraCls: extraCls,
                color: this.colorSet.color,
                bgColor: this.colorSet.light,
                textColor: this.colorSet.text,
                zIndex: 100,
                height: height + 'px',
                left: Math.round(pos * 90 * 1/parallels) + '%',
                width: Math.round(90 * 1/parallels) + '%',
                // max shift to 20+gap
                //left: 80 - 80/Math.sqrt(pos+1) + 10*Math.sqrt(pos) + '%',
                //width: 80/Math.sqrt(pos+1) + '%',
                top: top + 'px',
                statusIcons: this.statusIcons
            }, true);
            
            if (this.event.dirty) {
                eventEl.setStyle({'border-style': 'dashed'});
                eventEl.setOpacity(0.5);
            }
        
            if (currColNum == this.endColNum && this.event.get('editGrant')) {
                this.resizeable = new Ext.Resizable(eventEl, {
                    handles: 's',
                    disableTrackOver: true,
                    dynamic: true,
                    //dynamic: !!this.event.isRangeAdd,
                    heightIncrement: view.granularityUnitHeights/2,
                    listeners: {
                        scope: view,
                        resize: view.onEventResize,
                        beforeresize: view.onBeforeEventResize
                    }
                });
            }
        }
    }
});

Tine.Calendar.MonthViewEventUI = Ext.extend(Tine.Calendar.EventUI, {
    onSelectedChange: function(state){
        Tine.Calendar.MonthViewEventUI.superclass.onSelectedChange.call(this, state);
        if(state){
            this.addClass('cal-monthview-active');
            this.setStyle({
                'background-color': this.color,
                'color':            this.colorSet.text
            });
            
        }else{
            this.removeClass('cal-monthview-active');
            this.setStyle({
                'background-color': this.is_all_day_event ? this.bgColor : '',
                'color':            this.is_all_day_event ? '#000000' : this.color
            });
        }
    }
});
// file: /home/lkneschke/temp/tine20build/temp/tine20/Calendar/js/EventSelectionModel.js
/* 
 * Tine 2.0
 * 
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Cornelius Weiss <c.weiss@metaways.de>
 * @copyright   Copyright (c) 2007-2008 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: EventSelectionModel.js 12361 2010-01-21 11:34:23Z c.weiss@metaways.de $
 */

Ext.ns('Tine.Calendar');

/**
 * @namespace Tine.Calendar
 * @class Tine.Calendar.EventSelectionModel
 * @extends Ext.tree.MultiSelectionModel
 * Selection model for a calendar views.
 */
Tine.Calendar.EventSelectionModel = Ext.extend(Ext.tree.MultiSelectionModel, {
    init: function(view) {
        view.getTreeEl = function() {
            return view.el;
        }
        view.el.on("keydown", this.onKeyDown, this);
        view.on("click", this.onNodeClick, this);
        
        // since 3.1 it requires a mon fn
        //Tine.Calendar.EventSelectionModel.superclass.init.call(this, view);
    },
    
    /**
     * Gets the number of selected events.
     * @return {Number}
     */
    getCount: function() {
        return this.getSelectedNodes().length;
    },
    
    /**
     * Returns the first selected event.
     * @return {Record}
     */
    getSelected: function() {
        var selection = this.getSelectedEvents();
        return selection.length > 0 ? selection[0] : null;
    },
    
    /**
     * Returns an array of the selected events
     * @return {Array}
     */
    getSelectedEvents: function() {
        return this.getSelectedNodes();
    },
    
    /**
     * Select an event.
     * 
     * @param {Tine.Calendar.Model.Event} event The event to select
     * @param {EventObject} e (optional) An event associated with the selection
     * @param {Boolean} keepExisting True to retain existing selections
     * @return {Tine.Calendar.Model.Event} The selected event
     */
    select : function(event, e, keepExisting){
        if (! event || ! event.ui) {
            return event;
        }
        
        Tine.Calendar.EventSelectionModel.superclass.select.apply(this, arguments);
    },
    
    onKeyDown: Ext.emptyFn
});


// file: /home/lkneschke/temp/tine20build/temp/tine20/Calendar/js/DaysView.js
/* 
 * Tine 2.0
 * 
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Cornelius Weiss <c.weiss@metaways.de>
 * @copyright   Copyright (c) 2009 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: DaysView.js 18706 2011-01-18 11:32:04Z c.weiss@metaways.de $
 */

Ext.ns('Tine.Calendar');

/**
 * @namespace   Tine.Calendar
 * @class       Tine.Calendar.DaysView
 * @extends     Ext.util.Observable
 * Calendar view representing each day in a column
 * @author      Cornelius Weiss <c.weiss@metaways.de>
 * @version     $Id: DaysView.js 18706 2011-01-18 11:32:04Z c.weiss@metaways.de $
 * @constructor
 * @param {Object} config
 */
Tine.Calendar.DaysView = function(config){
    Ext.apply(this, config);
    Tine.Calendar.DaysView.superclass.constructor.call(this);
    
    this.addEvents(
        /**
         * @event click
         * fired if an event got clicked
         * @param {Tine.Calendar.Model.Event} event
         * @param {Ext.EventObject} e
         */
        'click',
        /**
         * @event contextmenu
         * fired if an event got contextmenu 
         * @param {Ext.EventObject} e
         */
        'contextmenu',
        /**
         * @event dblclick
         * fired if an event got dblclicked
         * @param {Tine.Calendar.Model.Event} event
         * @param {Ext.EventObject} e
         */
        'dblclick',
        /**
         * @event changeView
         * fired if user wants to change view
         * @param {String} requested view name
         * @param {mixed} start param of requested view
         */
        'changeView',
        /**
         * @event changePeriod
         * fired when period changed
         * @param {Object} period
         */
        'changePeriod',
        /**
         * @event addEvent
         * fired when a new event got inserted
         * 
         * @param {Tine.Calendar.Model.Event} event
         */
        'addEvent',
        /**
         * @event updateEvent
         * fired when an event go resised/moved
         * 
         * @param {Tine.Calendar.Model.Event} event
         */
        'updateEvent'
    );
};

Ext.extend(Tine.Calendar.DaysView, Ext.util.Observable, {
    /**
     * @cfg {Date} startDate
     * start date
     */
    startDate: new Date(),
    /**
     * @cfg {Number} numOfDays
     * number of days to display
     */
    numOfDays: 4,
    /**
     * @cfg {String} newEventSummary
     * _('New Event')
     */
    newEventSummary: 'New Event',
    /**
     * @cfg {String} dayFormatString
     * _('{0}, the {1}. of {2}')
     */
    dayFormatString: '{0}, the {1}. of {2}',
    /**
     * @cfg {Number} timeGranularity
     * granularity of timegrid in minutes
     */
    timeGranularity: 30,
    /**
     * @cfg {Number} granularityUnitHeights
     * heights in px of a granularity unit
     */
    granularityUnitHeights: 18,
    /**
     * @cfg {Boolean} denyDragOnMissingEditGrant
     * deny drag action if edit grant for event is missing
     */
    denyDragOnMissingEditGrant: true,
    /**
     * store holding timescale
     * @type {Ext.data.Store}
     */
    timeScale: null,
    /**
     * The amount of space to reserve for the scrollbar (defaults to 19 pixels)
     * @type {Number}
     */
    scrollOffset: 19,
    /**
     * @property {bool} editing
     * @private
     */
    editing: false,
    /**
     * currently active event
     * $type {Tine.Calendar.Model.Event}
     */
    activeEvent: null,
    /**
     * @property {Ext.data.Store}
     * @private
     */
    ds: null,
    
    /**
     * updates period to display
     * @param {Array} period
     */
    updatePeriod: function(period) {
        this.toDay = new Date().clearTime();
        
        this.startDate = period.from;
        
        var tbar = this.calPanel.getTopToolbar();
        if (tbar) {
            tbar.periodPicker.update(this.startDate);
            this.startDate = tbar.periodPicker.getPeriod().from;
        }
        
        this.endDate = this.startDate.add(Date.DAY, this.numOfDays+1);
        
        //this.parallelScrollerEventsRegistry = new Tine.Calendar.ParallelEventsRegistry({dtStart: this.startDate, dtEnd: this.endDate});
        //this.parallelWholeDayEventsRegistry = new Tine.Calendar.ParallelEventsRegistry({dtStart: this.startDate, dtEnd: this.endDate});
        //this.ds.each(this.removeEvent, this);
        
        this.updateDayHeaders();
        this.onBeforeScroll();
        
        this.fireEvent('changePeriod', period);
    },
    
    /**
     * init this view
     * 
     * @param {Tine.Calendar.CalendarPanel} calPanel
     */
    init: function(calPanel) {
        this.calPanel = calPanel;
        
        this.app = Tine.Tinebase.appMgr.get('Calendar');
        
        this.newEventSummary      =  this.app.i18n._hidden(this.newEventSummary);
        this.dayFormatString      =  this.app.i18n._hidden(this.dayFormatString);
        
        this.startDate.setHours(0);
        this.startDate.setMinutes(0);
        this.startDate.setSeconds(0);
        
        this.endDate = this.startDate.add(Date.DAY, this.numOfDays+1);
        
        this.parallelScrollerEventsRegistry = new Tine.Calendar.ParallelEventsRegistry({dtStart: this.startDate, dtEnd: this.endDate});
        this.parallelWholeDayEventsRegistry = new Tine.Calendar.ParallelEventsRegistry({dtStart: this.startDate, dtEnd: this.endDate});
        
        this.initData(calPanel.store);
        
        this.initTimeScale();
        this.initTemplates();
        
        this.calPanel.on('beforehide', this.onBeforeHide, this);
        this.calPanel.on('show', this.onShow, this);
        
        Tine.Tinebase.appMgr.on('activate', this.onAppActivate, this);
    },
    
    /**
     * @private
     * @param {Ext.data.Store} ds
     */
    initData : function(ds){
        if(this.ds){
            this.ds.un("load", this.onLoad, this);
            this.ds.un("datachanged", this.onDataChange, this);
            this.ds.un("add", this.onAdd, this);
            this.ds.un("remove", this.onRemove, this);
            this.ds.un("update", this.onUpdate, this);
            this.ds.un("clear", this.onClear, this);
        }
        if(ds){
            ds.on("load", this.onLoad, this);
            ds.on("datachanged", this.onDataChange, this);
            ds.on("add", this.onAdd, this);
            ds.on("remove", this.onRemove, this);
            ds.on("update", this.onUpdate, this);
            ds.on("clear", this.onClear, this);
        }
        this.ds = ds;
    },
    
    /**
     * inits time scale
     * @private
     */
    initTimeScale: function() {
        var data = [];
        var scaleSize = Date.msDAY/(this.timeGranularity * Date.msMINUTE);
        var baseDate = this.startDate.clone();
        
        var minutes;
        for (var i=0; i<scaleSize; i++) {
            minutes = i * this.timeGranularity;
            data.push([i, minutes, minutes * Date.msMINUTE, baseDate.add(Date.MINUTE, minutes).format('H:i')]);
        }
        
        this.timeScale = new Ext.data.SimpleStore({
            fields: ['index', 'minutes', 'milliseconds', 'time'],
            data: data,
            id: 'index'
        });
    },
    
    initDropZone: function() {
        this.dd = new Ext.dd.DropZone(this.mainWrap.dom, {
            ddGroup: 'cal-event',
            
            notifyOver : function(dd, e, data) {
                var sourceEl = Ext.fly(data.sourceEl);
                sourceEl.setStyle({'border-style': 'dashed'});
                sourceEl.setOpacity(0.5);
                
                if (data.event) {
                    var event = data.event;
                    
                    // we dont support multiple dropping yet
                    data.scope.getSelectionModel().select(event);
                
                    var targetDateTime = Tine.Calendar.DaysView.prototype.getTargetDateTime.call(data.scope, e);
                    if (targetDateTime) {
                        var dtString = targetDateTime.format(targetDateTime.is_all_day_event ? Ext.form.DateField.prototype.format : 'H:i');
                        if (! event.data.is_all_day_event) {
                            Ext.fly(dd.proxy.el.query('div[class=cal-daysviewpanel-event-header-inner]')[0]).update(dtString);
                        }
                        
                        if (event.get('editGrant')) {
                            return Math.abs(targetDateTime.getTime() - event.get('dtstart').getTime()) < Date.msMINUTE ? 'cal-daysviewpanel-event-drop-nodrop' : 'cal-daysviewpanel-event-drop-ok';
                        }
                    }
                }
                
                return 'cal-daysviewpanel-event-drop-nodrop';
            },
            
            notifyOut : function() {
                //console.log('notifyOut');
                //delete this.grid;
            },
            
            notifyDrop : function(dd, e, data) {
                var v = data.scope;
                
                var targetDate = v.getTargetDateTime(e);
                
                if (targetDate) {
                    var event = data.event;
                    
                    // deny drop for missing edit grant or no time change
                    if (! event.get('editGrant') || Math.abs(targetDate.getTime() - event.get('dtstart').getTime()) < Date.msMINUTE) {
                        return false;
                    }
                    
                    event.beginEdit();
                    var originalDuration = (event.get('dtend').getTime() - event.get('dtstart').getTime()) / Date.msMINUTE;
                    
                    event.set('dtstart', targetDate);
                    
                    if (! event.get('is_all_day_event') && targetDate.is_all_day_event && event.duration < Date.msDAY) {
                        // draged from scroller -> dropped to allDay and duration less than a day
                        event.set('dtend', targetDate.add(Date.DAY, 1));
                    } else if (event.get('is_all_day_event') && !targetDate.is_all_day_event) {
                        // draged from allDay -> droped to scroller will be resetted to hone hour
                        event.set('dtend', targetDate.add(Date.HOUR, 1));
                    } else {
                        event.set('dtend', targetDate.add(Date.MINUTE, originalDuration));
                    }
                    
                    event.set('is_all_day_event', targetDate.is_all_day_event);
                    event.endEdit();
                    
                    v.fireEvent('updateEvent', event);
                }
                
                return !!targetDate;
            }
        });
    },
    
    /**
     * @private
     */
    initDragZone: function() {
        this.scroller.ddScrollConfig = {
            vthresh: this.granularityUnitHeights * 2,
            increment: this.granularityUnitHeights * 4,
            hthresh: -1,
            frequency: 500
        };
        Ext.dd.ScrollManager.register(this.scroller);
        
        // init dragables
        this.dragZone = new Ext.dd.DragZone(this.el, {
            ddGroup: 'cal-event',
            view: this,
            scroll: false,
            containerScroll: true,
            
            getDragData: function(e) {
                var selected = this.view.getSelectionModel().getSelectedEvents();
                
                var eventEl = e.getTarget('div.cal-daysviewpanel-event', 10);
                if (eventEl) {
                    var parts = eventEl.id.split(':');
                    var event = this.view.ds.getById(parts[1]);
                    
                    // don't allow dragging of dirty events
                    // don't allow dragging with missing edit grant
                    if (! event || event.dirty || (this.view.denyDragOnMissingEditGrant && ! event.get('editGrant'))) {
                        return;
                    }
                    
                    // we need to clone an event with summary in
                    var d = Ext.get(event.ui.domIds[0]).dom.cloneNode(true);
                    d.id = Ext.id();
                    
                    if (event.get('is_all_day_event')) { 
                        Ext.fly(d).setLeft(0);
                    } else {
                        var width = (Ext.fly(this.view.dayCols[0]).getWidth() * 0.9);
                        Ext.fly(d).setTop(0);
                        Ext.fly(d).setWidth(width);
                        Ext.fly(d).setHeight(this.view.getTimeHeight.call(this.view, event.get('dtstart'), event.get('dtend')));
                    }
                    
                    return {
                        scope: this.view,
                        sourceEl: eventEl,
                        event: event,
                        ddel: d,
                        selections: this.view.getSelectionModel().getSelectedEvents()
                    }
                }
            },
            
            getRepairXY: function(e, dd) {
                Ext.fly(this.dragData.sourceEl).setStyle({'border-style': 'solid'});
                Ext.fly(this.dragData.sourceEl).setOpacity(1, 1);
                
                return Ext.fly(this.dragData.sourceEl).getXY();
            }
        });
    },
    
    /**
     * renders the view
     */
    render: function() {
        this.templates.master.append(this.calPanel.body, {
            header: this.templates.header.applyTemplate({
                daysHeader: this.getDayHeaders(),
                wholeDayCols: this.getWholeDayCols()
            }),
            body: this.templates.body.applyTemplate({
                timeRows: this.getTimeRows(),
                dayColumns: this.getDayColumns()
            })
        });
        
        this.initElements();
        this.getSelectionModel().init(this);
    },
    
    /**
     * fill the events into the view
     */
    afterRender: function() {
        
        this.mainWrap.on('click', this.onClick, this);
        this.mainWrap.on('dblclick', this.onDblClick, this);
        this.mainWrap.on('contextmenu', this.onContextMenu, this);
        this.mainWrap.on('mousedown', this.onMouseDown, this);
        this.mainWrap.on('mouseup', this.onMouseUp, this);
        this.calPanel.on('resize', this.onResize, this);
        
        this.initDropZone();
        this.initDragZone();
        
        this.updatePeriod({from: this.startDate});
        
        if (this.dsLoaded) {
            this.onLoad.apply(this);
        }
        
        // scrollTo initial position
        this.isScrolling = true;
        try {
            var startTimeString = this.app.getRegistry().get('preferences').get('daysviewstarttime');
            var startTime = Date.parseDate(startTimeString, 'H:i');
            if (! Ext.isDate(startTime)) {
                throw new Ext.Error('no valid startime given');
            }
            
            this.scrollTo(startTime)
        } catch (e) {
            this.scrollTo();
        }
        
        this.layout();
        this.rendered = true;
    },
    
    scrollTo: function(time) {
        time = Ext.isDate(time) ? time : new Date();
        this.scroller.dom.scrollTop = this.getTimeOffset(time);
    },
    
    onBeforeScroll: function() {
        if (! this.isScrolling) {
            this.isScrolling = true;
            
            // walk all cols an hide hints
            Ext.each(this.dayCols, function(dayCol, idx) {
                var dayColEl  = Ext.get(dayCol),
                    aboveHint = dayColEl.down('img[class=cal-daysviewpanel-body-daycolumn-hint-above]'),
                    belowHint = dayColEl.down('img[class=cal-daysviewpanel-body-daycolumn-hint-below]');
                    
                aboveHint.setDisplayed(false);
                belowHint.setDisplayed(false);
            }, this);
        }
    },
    
    /**
     * add hint if events are outside visible area
     * 
     * @param {} e
     * @param {} t
     * @param {} o
     */
    onScroll: function(e, t, o) {
        var visibleHeight = this.scroller.dom.clientHeight,
            visibleStart  = this.scroller.dom.scrollTop,
            visibleEnd    = visibleStart + visibleHeight,
            aboveCols     = [],
            belowCols     = [];
            
        this.ds.each(function(event) {
            if (event.ui) {
                Ext.each(event.ui.domIds, function(domId) {
                    var el = Ext.get(domId),
                        box = el.getBox(false, true);
                        
                    if (box.bottom <= visibleStart) {
//                        console.log(domId + ' is above visible area');
                        aboveCols.push(el.up('div[class^=cal-daysviewpanel-body-daycolumn]'));
                    } else if (box.bottom - box.height >= visibleEnd) {
//                        console.log(domId + ' is below visible area');
                        belowCols.push(el.up('div[class^=cal-daysviewpanel-body-daycolumn]'));
                    }
                }, this);
            }
        });
        
        // walk all cols an update hints
        Ext.each(this.dayCols, function(dayCol, idx) {
            var dayColEl  = Ext.get(dayCol),
                aboveHint = dayColEl.down('img[class=cal-daysviewpanel-body-daycolumn-hint-above]'),
                belowHint = dayColEl.down('img[class=cal-daysviewpanel-body-daycolumn-hint-below]');
                
            if (aboveCols.indexOf(dayColEl) >= 0) {
                aboveHint.setTop(visibleStart + 5);
                if (!aboveHint.isVisible()) {
                    aboveHint.fadeIn({duration: 1.6});
                }
            }
            
            if (belowCols.indexOf(dayColEl) >= 0) {
                belowHint.setTop(visibleEnd - 14);
                if (!belowHint.isVisible()) {
                    belowHint.fadeIn({duration: 1.6});
                }
            }
        }, this);
        
        this.isScrolling = false;
    },
    
    onShow: function() {
        this.scroller.dom.scrollTop = this.lastScrollPos || this.getTimeOffset(new Date());
    },
    
    onBeforeHide: function() {
        this.lastScrollPos = this.scroller.dom.scrollTop;
    },
    
    /**
     * renders a single event into this daysview
     * @param {Tine.Calendar.Model.Event} event
     * 
     * @todo Add support vor Events spanning over a day boundary
     */
    insertEvent: function(event) {
        event.ui = new Tine.Calendar.DaysViewEventUI(event);
        event.ui.render(this);
    },
    
    /**
     * removes all events from dom
     */
    removeAllEvents: function() {
        var els = Ext.DomQuery.select('div[class^=cal-daysviewpanel-event]', this.mainWrap.dom);
        for (var i=0; i<els.length; i++) {
            Ext.fly(els[i]).remove();
        }
        
        this.ds.each(function(event) {
            if (event.ui) {
                event.ui.domIds = [];
            }
        });
    },
    
    /**
     * removes a event from the dom
     * @param {Tine.Calendar.Model.Event} event
     */
    removeEvent: function(event) {
        if (event == this.activeEvent) {
            this.activeEvent = null;
        }
        
        if (event.ui) {
            event.ui.remove();
        }
    },
    
    /**
     * sets currentlcy active event
     * 
     * NOTE: active != selected
     * @param {Tine.Calendar.Model.Event} event
     */
    setActiveEvent: function(event) {
        this.activeEvent = event || null;
    },
    
    /**
     * gets currentlcy active event
     * 
     * @return {Tine.Calendar.Model.Event} event
     */
    getActiveEvent: function() {
        return this.activeEvent;
    },
    
    getSelectionModel: function() {
        return this.calPanel.getSelectionModel();
    },
    
    /**
     * creates a new event directly from this view
     * @param {} event
     */
    createEvent: function(e, event) {
        
        // only add range events if mouse is down long enough
        if (this.editing || (event.isRangeAdd && ! this.mouseDown)) {
            return;
        }
        
        // insert event silently into store
        this.editing = event;
        this.ds.suspendEvents();
        this.ds.add(event);
        this.ds.resumeEvents();
        
        
        // draw event
        var registry = event.get('is_all_day_event') ? this.parallelWholeDayEventsRegistry : this.parallelScrollerEventsRegistry;
        registry.register(event);
        this.insertEvent(event);
        //this.setActiveEvent(event);
        this.layout();
        
        //var eventEls = event.ui.getEls();
        //eventEls[0].setStyle({'border-style': 'dashed'});
        //eventEls[0].setOpacity(0.5);
        
        // start sizing for range adds
        if (event.isRangeAdd) {
            // don't create events with very small duration
            event.ui.resizeable.on('resize', function() {
                if (event.get('is_all_day_event')) {
                    var keep = true;
                } else {
                    var keep = (event.get('dtend').getTime() - event.get('dtstart').getTime()) / Date.msMINUTE >= this.timeGranularity;
                }
                
                if (keep) {
                    this.startEditSummary(event);
                } else {
                    this.abortCreateEvent(event);
                }
            }, this);
            
            var rzPos = event.get('is_all_day_event') ? 'east' : 'south';
            
            if (Ext.isIE) {
                e.browserEvent = {type: 'mousedown'};
            }
            
            event.ui.resizeable[rzPos].onMouseDown.call(event.ui.resizeable[rzPos], e);
            //event.ui.resizeable.startSizing.defer(2000, event.ui.resizeable, [e, event.ui.resizeable[rzPos]]);
        } else {
            this.startEditSummary(event);
        }
    },
    
    abortCreateEvent: function(event) {
        this.ds.remove(event);
        this.editing = false;
    },
    
    startEditSummary: function(event) {
        if (event.summaryEditor) {
            return false;
        }
        
        var eventEls = event.ui.getEls();
        
        var bodyCls = event.get('is_all_day_event') ? 'cal-daysviewpanel-wholedayevent-body' : 'cal-daysviewpanel-event-body';
        event.summaryEditor = new Ext.form.TextArea({
            event: event,
            renderTo: eventEls[0].down('div[class=' + bodyCls + ']'),
            width: event.ui.getEls()[0].getWidth() -12,
            height: Math.max(12, event.ui.getEls()[0].getHeight() -18),
            style: 'background-color: transparent; background: 0: border: 0; position: absolute; top: 0px;',
            value: this.newEventSummary,
            enableKeyEvents: true,
            listeners: {
                scope: this,
                render: function(field) {
                    field.focus(true, 100);
                },
                blur: this.endEditSummary,
                specialkey: this.endEditSummary,
                keydown: this.endEditSummary
            }
            
        });
    },
    
    endEditSummary: function(field, e) {
    	var event   = field.event;
    	var summary = field.getValue();
    	
        if (! this.editing) {
            return;
        }
        
        // abort edit on ESC key
        if (e && e.getKey() == e.ESC) {
            return this.abortCreateEvent(event);
        }
        
        // only commit edit on Enter & blur
        if (e && e.getKey() != e.ENTER) {
            return;
        }
        
        if (! summary) {
            return this.abortCreateEvent(event);
        }
        
        this.editing = false;
        event.summaryEditor = false;
        
        event.set('summary', summary);
        
        this.ds.suspendEvents();
        this.ds.remove(event);
        this.ds.resumeEvents();
        
        var registry = event.get('is_all_day_event') ? this.parallelWholeDayEventsRegistry : this.parallelScrollerEventsRegistry;
        registry.unregister(event);
        this.removeEvent(event);
        
        event.dirty = true;
        this.ds.add(event);
        this.fireEvent('addEvent', event);

        //this.ds.resumeEvents();
        //this.ds.fireEvent.call(this.ds, 'add', this.ds, [event], this.ds.indexOf(event));
    },
    
    onAppActivate: function(app) {
        if (app === this.app) {
            // get Preference
//            try {
//                var startTimeString = this.app.getRegistry().get('preferences').get('daysviewstarttime');
//                var startTime = Date.parseDate(startTimeString, 'H:i');
//                if (! Ext.isDate(startTime)) {
//                    throw new Ext.Error('no valid startime given');
//                }
//                
//                this.scroller.dom.scrollTop = this.getTimeOffset(startTime);
//            } catch (e) {
//                this.scrollToNow();
//            }
        }
    },
    
    onResize: function(e) {
        // redraw whole day events
        (function(){this.ds.each(function(event) {
            if (event.get('is_all_day_event')) {
                this.removeEvent(event);
                this.insertEvent(event);
            }
        }, this)}).defer(50, this);
        
    },
    
    onClick: function(e) {
        // check for hint clicks first
        var hint = e.getTarget('img[class^=cal-daysviewpanel-body-daycolumn-hint-]', 10, true);
        if (hint) {
            this.scroller.scroll(hint.hasClass('cal-daysviewpanel-body-daycolumn-hint-above') ? 't' : 'b', 10000, true);
            return;
        }
        
        var event = this.getTargetEvent(e);
        if (event) {
            this.fireEvent('click', event, e);
        }
    },
    
    onContextMenu: function(e) {
        this.fireEvent('contextmenu', e);
    },
    
    /**
     * @private
     */
    onDblClick: function(e, target) {
        e.stopEvent();
        var event = this.getTargetEvent(e);
        var dtStart = this.getTargetDateTime(e);
        
        if (event && !event.id.match(/new/)) {
            // NOTE: prevent gone away exceptoins as we save new events on blur
            this.fireEvent('dblclick', event, e);
        } else if (dtStart && !this.editing) {
            var newId = 'cal-daysviewpanel-new-' + Ext.id();
            var dtend = dtStart.add(Date.HOUR, 1);
            if (dtStart.is_all_day_event) {
                dtend = dtend.add(Date.HOUR, 23).add(Date.SECOND, -1);
            }
            
            var event = new Tine.Calendar.Model.Event(Ext.apply(Tine.Calendar.Model.Event.getDefaultData(), {
                id: newId,
                dtstart: dtStart, 
                dtend: dtend,
                is_all_day_event: dtStart.is_all_day_event
            }), newId);
            
            this.createEvent(e, event);
            event.dirty = true;
        } else if (target.className == 'cal-daysviewpanel-dayheader-day'){
            var dayHeaders = Ext.DomQuery.select('div[class=cal-daysviewpanel-dayheader-day]', this.innerHd);
            var date = this.startDate.add(Date.DAY, dayHeaders.indexOf(target));
            this.fireEvent('changeView', 'day', date);
        }
    },
    
    /**
     * @private
     */
    onMouseDown: function(e) {
        // only care for left mouse button
        if (e.button !== 0) {
            return;
        }
        
        if (! this.editing) {
            this.focusEl.focus();
        }
        this.mouseDown = true;
        
        var targetEvent = this.getTargetEvent(e);
        if (this.editing && this.editing.summaryEditor && (targetEvent != this.editing)) {
            this.editing.summaryEditor.fireEvent('blur', this.editing.summaryEditor);
        }
        
        var dtStart = this.getTargetDateTime(e);
        if (dtStart) {
            var newId = 'cal-daysviewpanel-new-' + Ext.id();
            var event = new Tine.Calendar.Model.Event(Ext.apply(Tine.Calendar.Model.Event.getDefaultData(), {
                id: newId,
                dtstart: dtStart, 
                dtend: dtStart.is_all_day_event ? dtStart.add(Date.HOUR, 24).add(Date.SECOND, -1) : dtStart.add(Date.MINUTE, 2*this.timeGranularity/2),
                is_all_day_event: dtStart.is_all_day_event
            }), newId);
            event.isRangeAdd = true;
            event.dirty = true;
            
            e.stopEvent();
            this.createEvent.defer(100, this, [e, event]);
        }
    },
    
    /**
     * @private
     */
    onMouseUp: function() {
        this.mouseDown = false;
    },
    
    /**
     * @private
     */
    onBeforeEventResize: function(rz, e) {
        var parts = rz.el.id.split(':');
        var event = this.ds.getById(parts[1]);
        
        rz.event = event;
        rz.originalHeight = rz.el.getHeight();
        rz.originalWidth  = rz.el.getWidth();

        // NOTE: ext dosn't support move events via api
        rz.onMouseMove = rz.onMouseMove.createSequence(function() {
            var event = this.event;
            if (! event) {
                //event already gone -> late event / busy brower?
                return;
            }
            var ui = event.ui;
            var rzInfo = ui.getRzInfo(this);
            
            this.durationEl.update(rzInfo.dtend.format(event.get('is_all_day_event') ? Ext.form.DateField.prototype.format : 'H:i'));
        }, rz);
        
        event.ui.markDirty();
        
        // NOTE: Ext keeps proxy if element is not destroyed (diff !=0)
        if (! rz.durationEl) {
            rz.durationEl = rz.el.insertFirst({
                'class': 'cal-daysviewpanel-event-rzduration',
                'style': 'position: absolute; bottom: 3px; right: 2px; z-index: 1000;'
            });
        }
        rz.durationEl.update(event.get('dtend').format(event.get('is_all_day_event') ? Ext.form.DateField.prototype.format : 'H:i'));
        
        if (event) {
            this.getSelectionModel().select(event);
        } else {
            this.getSelectionModel().clearSelections();
        }
    },
    
    /**
     * @private
     */
    onEventResize: function(rz, width, height) {
        var event = rz.event;
        
        if (! event) {
            //event already gone -> late event / busy brower?
            return;
        }
        
        var rzInfo = event.ui.getRzInfo(rz, width, height);
        if (rzInfo.diff != 0) {
            event.set('dtend', rzInfo.dtend);
        }
        
        if (event.summaryEditor) {
            event.summaryEditor.setHeight(event.ui.getEls()[0].getHeight() -18);
        }
        
        // don't fire update events on rangeAdd
        if (rzInfo.diff != 0 && event != this.editing && ! event.isRangeAdd) {
            this.fireEvent('updateEvent', event);
        } else {
            event.ui.clearDirty();
        }
    },
    
    /**
     * @private
     */
    onDataChange : function(){
        //console.log('onDataChange');
        //this.refresh();
    },

    /**
     * @private
     */
    onClear : function(){
        //console.log('onClear')
        //this.refresh();
    },

    /**
     * @private
     */
    onUpdate : function(ds, event){
        // don't update events while being created
        if (event.get('id').match(/new/)) {
            return;
        }
        
        // relayout original context
        var originalRegistry = (event.modified.hasOwnProperty('is_all_day_event') ? event.modified.is_all_day_event : event.get('is_all_day_event')) ? 
            this.parallelWholeDayEventsRegistry : 
            this.parallelScrollerEventsRegistry;
        var registry = event.get('is_all_day_event') ? this.parallelWholeDayEventsRegistry : this.parallelScrollerEventsRegistry;
        var originalDtstart = event.modified.hasOwnProperty('dtstart') ? event.modified.dtstart : event.get('dtstart');
        var originalDtend = event.modified.hasOwnProperty('dtend') ? event.modified.dtend : event.get('dtend');
            
        
        
        var originalParallels = originalRegistry.getEvents(originalDtstart, originalDtend);
        for (var j=0; j<originalParallels.length; j++) {
            this.removeEvent(originalParallels[j]);
        }
        originalRegistry.unregister(event);
        
        var originalParallels = originalRegistry.getEvents(originalDtstart, originalDtend);
        for (var j=0; j<originalParallels.length; j++) {
            this.insertEvent(originalParallels[j]);
        }
        
        // relayout actual context
        var parallelEvents = registry.getEvents(event.get('dtstart'), event.get('dtend'));
        for (var j=0; j<parallelEvents.length; j++) {
            this.removeEvent(parallelEvents[j]);
        }
        
        registry.register(event);
        var parallelEvents = registry.getEvents(event.get('dtstart'), event.get('dtend'));
        for (var j=0; j<parallelEvents.length; j++) {
            this.insertEvent(parallelEvents[j]);
        }
        
        this.setActiveEvent(this.getActiveEvent());
        this.layout();
    },

    /**
     * @private
     */
    onAdd : function(ds, records, index){
        //console.log('onAdd');
        for (var i=0; i<records.length; i++) {
            var event = records[i];
            
            var registry = event.get('is_all_day_event') ? this.parallelWholeDayEventsRegistry : this.parallelScrollerEventsRegistry;
            registry.register(event);
            
            var parallelEvents = registry.getEvents(event.get('dtstart'), event.get('dtend'));
            
            for (var j=0; j<parallelEvents.length; j++) {
                this.removeEvent(parallelEvents[j]);
                this.insertEvent(parallelEvents[j]);
            }
            
            //this.setActiveEvent(event);
        }
        
        this.layout();
    },

    /**
     * @private
     */
    onRemove : function(ds, event, index, isUpdate) {
        if (!event || index == -1) {
            return;
        }
        
        if(isUpdate !== true){
            //this.fireEvent("beforeeventremoved", this, index, record);
        }
        var registry = event.get('is_all_day_event') ? this.parallelWholeDayEventsRegistry : this.parallelScrollerEventsRegistry;
        registry.unregister(event);
        this.removeEvent(event);
        this.getSelectionModel().unselect(event);
        this.layout();
    },
    
    /**
     * @private
     */
    onLoad : function() {
        if(! this.rendered){
            this.dsLoaded = true;
            return;
        }
        
        // remove all old events from dom
        this.removeAllEvents();
        
        // setup registry
        this.parallelScrollerEventsRegistry = new Tine.Calendar.ParallelEventsRegistry({dtStart: this.startDate, dtEnd: this.endDate});
        this.parallelWholeDayEventsRegistry = new Tine.Calendar.ParallelEventsRegistry({dtStart: this.startDate, dtEnd: this.endDate});
        
        // todo: sort generic?
        this.ds.fields = Tine.Calendar.Model.Event.prototype.fields;
        this.ds.sortInfo = {field: 'dtstart', direction: 'ASC'};
        this.ds.applySort();
        
        this.ds.each(function(event) {
            var registry = event.get('is_all_day_event') ? this.parallelWholeDayEventsRegistry : this.parallelScrollerEventsRegistry;
            registry.register(event);
        }, this);
        
        // put the events in
        this.ds.each(this.insertEvent, this);
        
        this.layout();
    },
    
    /**
     * print wrapper
     */
    print: function() {
        var renderer = new Tine.Calendar.Printer.DaysViewRenderer();
        renderer.print(this);
    },
    
    hex2dec: function(hex) {
        var dec = 0;
        hex = hex.toString();
        var length = hex.length, multiplier, digit;
        for (var i=0; i<length; i++) {
            
            multiplier = Math.pow(16, (Math.abs(i - hex.length)-1));
            digit = parseInt(hex.toString().charAt([i]), 10);
            if (isNaN(digit)) {
                switch (hex.toString().charAt([i]).toUpperCase()) {
                    case 'A': digit = 10;  break;
                    case 'B': digit = 11;  break;
                    case 'C': digit = 12;  break;
                    case 'D': digit = 13;  break;
                    case 'E': digit = 14;  break;
                    case 'F': digit = 15;  break;
                    default: return NaN;
                }
            }
            dec = dec + (multiplier * digit);
        }
        
        return dec;
    },
    
    getPeriod: function() {
        return {
            from: this.startDate,
            until: this.startDate.add(Date.DAY, this.numOfDays)
        };
    },
    
    /**
     * get date of a (event) target
     * 
     * @param {Ext.EventObject} e
     * @return {Date}
     */
    getTargetDateTime: function(e) {
        var target = e.getTarget();
        if (target.id.match(/^ext-gen\d+:\d+/)) {
            var parts = target.id.split(':');
            
            var date = this.startDate.add(Date.DAY, parseInt(parts[1], 10));
            date.is_all_day_event = true;
            
            if (parts[2] ) {
                var timePart = this.timeScale.getAt(parts[2]);
                date = date.add(Date.MINUTE, timePart.get('minutes'));
                date.is_all_day_event = false;
            }   
            return date;
        }
    },
    
    /**
     * gets event el of target
     * 
     * @param {Ext.EventObject} e
     * @return {Tine.Calendar.Model.Event}
     */
    getTargetEvent: function(e) {
        var target = e.getTarget();
        var el = Ext.fly(target);
        
        if (el.hasClass('cal-daysviewpanel-event') || (el = el.up('[id*=event:]', 10))) {
            var parts = el.dom.id.split(':');
            
            return this.ds.getById(parts[1]);
        }
    },
    
    getTimeOffset: function(date) {
        var d = this.granularityUnitHeights / this.timeGranularity;
        
        return Math.round(d * ( 60 * date.getHours() + date.getMinutes()));
    },
    
    getTimeHeight: function(dtStart, dtEnd) {
        var d = this.granularityUnitHeights / this.timeGranularity;
        return Math.round(d * ((dtEnd.getTime() - dtStart.getTime()) / Date.msMINUTE));
    },
    
    /**
     * fetches elements from our generated dom
     */
    initElements : function(){
        var E = Ext.Element;

        var el = this.calPanel.body.dom.firstChild;
        var cs = el.childNodes;

        this.el = new E(el);

        this.mainWrap = new E(cs[0]);
        this.mainHd = new E(this.mainWrap.dom.firstChild);

        this.innerHd = this.mainHd.dom.firstChild;
        
        this.wholeDayArea = this.innerHd.firstChild.childNodes[1];
        
        this.scroller = new E(this.mainWrap.dom.childNodes[1]);
        this.scroller.setStyle('overflow-x', 'hidden');
        this.scroller.on('scroll', this.onBeforeScroll, this);
        this.scroller.on('scroll', this.onScroll, this, {buffer: 200});
        
        this.mainBody = new E(this.scroller.dom.firstChild);
        
        this.dayCols = this.mainBody.dom.firstChild.lastChild.childNodes;

        this.focusEl = new E(this.el.dom.lastChild);
        this.focusEl.swallowEvent("click", true);
        this.focusEl.swallowEvent("dblclick", true);
        this.focusEl.swallowEvent("contextmenu", true);
    },
    
    getColumnNumber: function(date) {
        return Math.floor((date.add(Date.SECOND, 1).getTime() - this.startDate.getTime()) / Date.msDAY);
    },
    
    getDateColumnEl: function(pos) {
        return this.dayCols[pos];
    },
    
    checkWholeDayEls: function() {
        var freeIdxs = [];
        for (var i=0; i<this.wholeDayArea.childNodes.length-1; i++) {
            if(this.wholeDayArea.childNodes[i].childNodes.length === 1) {
                freeIdxs.push(i);
            }
        }
        
        for (var i=1; i<freeIdxs.length; i++) {
            Ext.fly(this.wholeDayArea.childNodes[freeIdxs[i]]).remove();
        }
    },
    
    /**
     * layouts the view
     */
    layout: function() {
        if(!this.mainBody){
            return; // not rendered
        }
        
        var g = this.calPanel;
        var c = g.body;
        var csize = c.getSize(true);
        var vw = csize.width;
        
        this.el.setSize(csize.width, csize.height);
        
        this.layoutWholeDayHeader();
        var hdHeight = this.mainHd.getHeight();
        
        var vh = csize.height - (hdHeight);

        this.scroller.setSize(vw, vh);
        // we add 2 more pixel to have spare space for our left padding
        this.innerHd.style.width = (vw + 2)+'px';
        
        // force positioning on scroll hints
        this.onScroll.defer(100, this);
    },
    
    layoutWholeDayHeader: function() {
        var headerEl = Ext.get(this.wholeDayArea);
        
        for (var i=0, bottom = headerEl.getTop(); i<this.wholeDayArea.childNodes.length -1; i++) {
            bottom = Math.max(parseInt(Ext.get(this.wholeDayArea.childNodes[i]).getBottom(), 10), bottom);
        }
        
        headerEl.setHeight(bottom - headerEl.getTop() + 10);
    },
    
    /**
     * returns HTML frament of the day headers
     */
    getDayHeaders: function() {
        var html = '';
        var width = 100/this.numOfDays;
        
        for (var i=0, date; i<this.numOfDays; i++) {
            var day = this.startDate.add(Date.DAY, i);
            html += this.templates.dayHeader.applyTemplate({
                day: String.format(this.dayFormatString, day.format('l'), day.format('j'), day.format('F')),
                height: this.granularityUnitHeights,
                width: width + '%',
                left: i * width + '%'
            });
        }
        return html;
    },
    
    /**
     * updates HTML of day headers
     */
    updateDayHeaders: function() {
        var dayHeaders = Ext.DomQuery.select('div[class=cal-daysviewpanel-dayheader-day]', this.innerHd);
        
        for (var i=0, date, isToDay, headerEl, dayColEl; i<dayHeaders.length; i++) {
            
            date = this.startDate.add(Date.DAY, i);
            isToDay = date.getTime() == this.toDay.getTime();
            
            headerEl = Ext.fly(dayHeaders[i]);
            
            headerEl.update(String.format(this.dayFormatString, date.format('l'), date.format('j'), date.format('F')));
            headerEl.parent()[(isToDay ? 'add' : 'remove') + 'Class']('cal-daysviewpanel-dayheader-today');
            Ext.fly(this.dayCols[i])[(isToDay ? 'add' : 'remove') + 'Class']('cal-daysviewpanel-body-daycolumn-today');
        }
    },
    
    /**
     * returns HTML fragment of the whole day cols
     */
    getWholeDayCols: function() {
        var html = '';
        var width = 100/this.numOfDays;
        
        var baseId = Ext.id();
        for (var i=0; i<this.numOfDays; i++) {
            html += this.templates.wholeDayCol.applyTemplate({
                //day: date.get('dateString'),
                //height: this.granularityUnitHeights,
                id: baseId + ':' + i,
                width: width + '%',
                left: i * width + '%'
            });
        };
        
        return html;
    },
    
    /**
     * gets HTML fragment of the horizontal time rows
     */
    getTimeRows: function() {
        var html = '';
        this.timeScale.each(function(time){
            var index = time.get('index');
            html += this.templates.timeRow.applyTemplate({
                cls: index%2 ? 'cal-daysviewpanel-timeRow-off' : 'cal-daysviewpanel-timeRow-on',
                height: this.granularityUnitHeights + 'px',
                top: index * this.granularityUnitHeights + 'px',
                time: index%2 ? '' : time.get('time')
            });
        }, this);
        
        return html;
    },
    
    /**
     * gets HTML fragment of the day columns
     */
    getDayColumns: function() {
        var html = '';
        var width = 100/this.numOfDays;
        
        for (var i=0; i<this.numOfDays; i++) {
            html += this.templates.dayColumn.applyTemplate({
                width: width + '%',
                left: i * width + '%',
                overRows: this.getOverRows(i)
            });
        }
        
        return html;
    },
    
    /**
     * gets HTML fragment of the time over rows
     */
    getOverRows: function(dayIndex) {
        var html = '';
        var baseId = Ext.id();
        
        this.timeScale.each(function(time){
            var index = time.get('index');
            html += this.templates.overRow.applyTemplate({
                id: baseId + ':' + dayIndex + ':' + index,
                cls: 'cal-daysviewpanel-daycolumn-row-' + (index%2 ? 'off' : 'on'),
                height: this.granularityUnitHeights + 'px'
            });
        }, this);
        
        return html;
    },
    
    /**
     * inits all tempaltes of this view
     */
    initTemplates: function() {
        var ts = this.templates || {};
    
        ts.master = new Ext.XTemplate(
            '<div class="cal-daysviewpanel" hidefocus="true">',
                '<div class="cal-daysviewpanel-viewport">',
                    '<div class="cal-daysviewpanel-header"><div class="cal-daysviewpanel-header-inner"><div class="cal-daysviewpanel-header-offset">{header}</div></div><div class="x-clear"></div></div>',
                    '<div class="cal-daysviewpanel-scroller"><div class="cal-daysviewpanel-body">{body}</div></div>',
                '</div>',
                '<a href="#" class="cal-daysviewpanel-focus" tabIndex="-1"></a>',
            '</div>'
        );
        
        ts.header = new Ext.XTemplate(
            '<div class="cal-daysviewpanel-daysheader">{daysHeader}</div>' +
            
            '<div class="cal-daysviewpanel-wholedayheader">' +
                '<div class="cal-daysviewpanel-wholedayheader-daycols">{wholeDayCols}</div>' +
            '</div>'
        );
        
        ts.dayHeader = new Ext.XTemplate(
            '<div class="cal-daysviewpanel-dayheader" style="height: {height}; width: {width}; left: {left};">' + 
                '<div class="cal-daysviewpanel-dayheader-day-wrap">' +
                    '<div class="cal-daysviewpanel-dayheader-day">{day}</div>' +
                '</div>',
            '</div>'
        );
        
        ts.wholeDayCol = new Ext.XTemplate(
            '<div class="cal-daysviewpanel-body-wholedaycolumn" style="left: {left}; width: {width};">' +
                '<div id="{id}" class="cal-daysviewpanel-body-wholedaycolumn-over">&#160;</div>' +
            '</div>'
        );
        
        ts.body = new Ext.XTemplate(
            '<div class="cal-daysviewpanel-body-inner">' +
                '{timeRows}' +
                '<div class="cal-daysviewpanel-body-daycolumns">{dayColumns}</div>' +
            '</div>'
        );
        
        ts.timeRow = new Ext.XTemplate(
            '<div class="{cls}" style="height: {height}; top: {top};">',
                '<div class="cal-daysviewpanel-timeRow-time">{time}</div>',
            '</div>'
        );
        
        ts.dayColumn = new Ext.XTemplate(
            '<div class="cal-daysviewpanel-body-daycolumn" style="left: {left}; width: {width};">',
                '<div class="cal-daysviewpanel-body-daycolumn-inner">&#160;</div>',
                '{overRows}',
                '<img src="', Ext.BLANK_IMAGE_URL, '" class="cal-daysviewpanel-body-daycolumn-hint-above" />',
                '<img src="', Ext.BLANK_IMAGE_URL, '" class="cal-daysviewpanel-body-daycolumn-hint-below" />',
            '</div>'
        );
        
        ts.overRow = new Ext.XTemplate(
            '<div class="cal-daysviewpanel-daycolumn-row" style="height: {height};">' +
                '<div id="{id}" class="{cls}" >&#160;</div>'+
            '</div>'
        );
        
        ts.event = new Ext.XTemplate(
            '<div id="{id}" class="cal-daysviewpanel-event {extraCls}" style="width: {width}; height: {height}; left: {left}; top: {top}; z-index: {zIndex}; background-color: {bgColor}; border-color: {color};">' +
                '<div class="cal-daysviewpanel-event-header" style="background-color: {color};">' +
                    '<div class="cal-daysviewpanel-event-header-inner" style="color: {textColor}; background-color: {color}; z-index: {zIndex};">{startTime}</div>' +
                    '<div class="cal-daysviewpanel-event-header-icons">' +
                        '<tpl for="statusIcons">' +
                            '<img src="', Ext.BLANK_IMAGE_URL, '" class="cal-status-icon {status}-{[parent.textColor == \'#FFFFFF\' ? \'white\' : \'black\']}" ext:qtip="{text}" />',
                        '</tpl>' +
                    '</div>' +
                '</div>' +
                '<div class="cal-daysviewpanel-event-body">{[Ext.util.Format.nl2br(Ext.util.Format.htmlEncode(values.summary))]}</div>' +
            '</div>'
        );
        
        ts.wholeDayEvent = new Ext.XTemplate(
            '<div id="{id}" class="cal-daysviewpanel-event {extraCls}" style="width: {width}; height: {height}; left: {left}; top: {top}; z-index: {zIndex}; background-color: {bgColor}; border-color: {color};">' +
                '<div class="cal-daysviewpanel-wholedayevent-body">{[Ext.util.Format.nl2br(Ext.util.Format.htmlEncode(values.summary))]}</div>' +
//                '<div class="cal-daysviewpanel-wholedayevent-icons">' +
                '<div class="cal-daysviewpanel-event-header-icons" style="background-color: {bgColor};" >' +
                    '<tpl for="statusIcons">' +
                        '<img src="', Ext.BLANK_IMAGE_URL, '" class="cal-status-icon {status}-black" ext:qtip="{text}" />',
                    '</tpl>' +
                '</div>' +
            '</div>'
        );
        
        for(var k in ts){
            var t = ts[k];
            if(t && typeof t.compile == 'function' && !t.compiled){
                t.disableFormats = true;
                t.compile();
            }
        }

        this.templates = ts;
    }
});

// file: /home/lkneschke/temp/tine20build/temp/tine20/Calendar/js/MonthView.js
/* 
 * Tine 2.0
 * 
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Cornelius Weiss <c.weiss@metaways.de>
 * @copyright   Copyright (c) 2007-2008 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: MonthView.js 17208 2010-11-19 14:32:54Z c.weiss@metaways.de $
 */

Ext.ns('Tine.Calendar');

/**
 * @namespace Tine.Calendar
 * @class Tine.Calendar.MonthView
 * @extends Ext.util.Observable
 * @constructor
 * @param {Object} config
 */
Tine.Calendar.MonthView = function(config){
    Ext.apply(this, config);
    Tine.Calendar.MonthView.superclass.constructor.call(this);
    
    this.addEvents(
        /**
         * @event click
         * fired if an event got clicked
         * @param {Tine.Calendar.Model.Event} event
         * @param {Ext.EventObject} e
         */
        'click',
        /**
         * @event contextmenu
         * fired if an event got contextmenu 
         * @param {Ext.EventObject} e
         */
        'contextmenu',
        /**
         * @event dblclick
         * fired if an event got dblclicked
         * @param {Tine.Calendar.Model.Event} event
         * @param {Ext.EventObject} e
         */
        'dblclick',
        /**
         * @event changeView
         * fired if user wants to change view
         * @param {String} requested view name
         * @param {mixed} start param of requested view
         */
        'changeView',
        /**
         * @event changePeriod
         * fired when period changed
         * @param {Object} period
         */
        'changePeriod',
        /**
         * @event addEvent
         * fired when a new event got inserted
         * 
         * @param {Tine.Calendar.Model.Event} event
         */
        'addEvent',
        /**
         * @event updateEvent
         * fired when an event go resised/moved
         * 
         * @param {Tine.Calendar.Model.Event} event
         */
        'updateEvent'
    );
};

Ext.extend(Tine.Calendar.MonthView, Ext.util.Observable, {
    /**
     * @cfg {Date} startDate
     * start date
     */
    startDate: new Date().clearTime(),
    /**
     * @cfg {String} newEventSummary
     * _('New Event')
     */
    newEventSummary: 'New Event',
    /**
     * @cfg {String} calWeekString
     * _('WK')
     */
    calWeekString: 'WK',
    /**
     * @cfg String moreString
     * _('{0} more...')
     */
    moreString: '{0} more...',
    /**
     * @cfg {Array} monthNames
     * An array of textual month names which can be overriden for localization support (defaults to Date.monthNames)
     */
    monthNames : Date.monthNames,
    /**
     * @cfg {Array} dayNames
     * An array of textual day names which can be overriden for localization support (defaults to Date.dayNames)
     */
    dayNames : Date.dayNames,
    /**
     * @cfg {Number} startDay
     * Day index at which the week should begin, 0-based
     */
    startDay: Ext.DatePicker.prototype.startDay,
    /**
     * @cfg {Boolean} denyDragOnMissingEditGrant
     * deny drag action if edit grant for event is missing
     */
    denyDragOnMissingEditGrant: true,
    /**
     * @property {Tine.Calendar.Model.Event} activeEvent
     * @private
     */
    activeEvent: null,
    /**
     * @private {Date} toDay
     */
    toDay: null,
    /**
     * @private {Array} dateMesh
     */
    dateMesh: null,
    /**
     * @private {Tine.Calendar.ParallelEventsRegistry} parallelEventsRegistry
     */
    parallelEventsRegistry: null,
    
    /**
     * @private
     */
    afterRender: function() {
        this.initElements();
        
        this.getSelectionModel().init(this);
        
        this.el.on('mousedown', this.onMouseDown, this);
        this.el.on('dblclick', this.onDblClick, this);
        this.el.on('click', this.onClick, this);
        this.el.on('contextmenu', this.onContextMenu, this);
        
        this.initDragZone();
        this.initDropZone();
        
        this.updatePeriod({from: this.period.from});
        
        if (this.dsLoaded) {
            this.onLoad.apply(this);
        }
        
        this.rendered = true;
    },
    
    /**
     * @private calculates mesh of dates for month this.startDate is in
     */
    calcDateMesh: function() {
        var mesh = [];
        var d = Date.parseDate(this.startDate.format('Y-m') + '-01 00:00:00', Date.patterns.ISO8601Long);
        while(d.getDay() != this.startDay) {
            d = d.add(Date.DAY, -1);
        }
        
        while(d.getMonth() != this.startDate.add(Date.MONTH, 1).getMonth()) {
            for (var i=0; i<7; i++) {
                mesh.push(d.add(Date.DAY, i).clone());
            }
            d = d.add(Date.DAY, 7);
        }
        
        this.dateMesh = mesh;
    },
    
    /**
     * gets currentlcy active event
     * 
     * @return {Tine.Calendar.Model.Event} event
     */
    getActiveEvent: function() {
        return this.activeEvent;
    },
    
    /**
     * returns index of dateCell given date is in
     * @param {Date} date
     */
    getDayCellIndex: function(date) {
        return Math.round((date.clearTime(true).getTime() - this.dateMesh[0].getTime())/Date.msDAY);
    },
    
    /**
     * @private returns a child div in requested position
     * 
     * @param {dom} dayCell
     * @param {Number} pos
     * @return {dom}
     */
    getEventSlice: function(dayCell, pos) {
        pos = Math.abs(pos);
        
        for (var i=dayCell.childNodes.length; i<=pos; i++) {
            Ext.DomHelper.insertAfter(dayCell.lastChild, '<div class="cal-monthview-eventslice"/>');
            //console.log('inserted slice: ' + i);
        }
        
        // make sure cell is empty
        while (dayCell.childNodes[pos].innerHTML) {
            pos++;
            
            if (pos > dayCell.childNodes.length -1) {
                Ext.DomHelper.insertAfter(dayCell.lastChild, '<div class="cal-monthview-eventslice"/>');
            }
        }
        
        return dayCell.childNodes[pos];
    },
    
    /**
     * returns period of currently displayed month
     * @return {Object}
     */
    getPeriod: function() {
        // happens if month view is rendered first
        if (! this.dateMesh) {
            this.calcDateMesh();
        }
        
        return {
            from: this.dateMesh[0],
            until: this.dateMesh[this.dateMesh.length -1].add(Date.DAY, 1)
        };    
    },
    
    getSelectionModel: function() {
        return this.calPanel.selModel;
    },
    
    getTargetDateTime: function(e) {
        var target = e.getTarget('td.cal-monthview-daycell', 3);
        
        if (target) {
            var dateIdx = this.dayCells.indexOf(target);
            var date = this.dateMesh[this.dayCells.indexOf(target)];
        
            // set some default time:
            date.add(Date.HOUR, 10);
            return date;
        }
    },
    
    getTargetEvent: function(e) {
        var target = e.getTarget('div.cal-monthview-alldayevent', 10) || e.getTarget('div.cal-monthview-event', 10);
        
        if (target) {
            var parts = target.id.split(':');
            var event = this.ds.getById(parts[1]);
        }
        
        return event;
    },
    
    /**
     * @private
     * @param {Tine.Calendar.CalendarPanel} calPanel
     */
    init: function(calPanel) {
        this.calPanel = calPanel;
        
        this.app = Tine.Tinebase.appMgr.get('Calendar');
        this.newEventSummary =  this.app.i18n._hidden(this.newEventSummary);
        this.calWeekString   =  this.app.i18n._hidden(this.calWeekString);
        this.moreString      =  this.app.i18n._hidden(this.moreString);
        
        // redefine this props in case ext translations got included after this component
        this.monthNames = Date.monthNames;
        this.dayNames   = Date.dayNames;
        this.startDay   = Ext.DatePicker.prototype.startDay;
        
        this.initData(calPanel.store);
        this.initTemplates();
    },
    
    /**
     * @private
     * @param {Ext.data.Store} ds
     */
    initData : function(ds){
        if(this.ds){
            this.ds.un("load", this.onLoad, this);
            //this.ds.un("datachanged", this.onDataChange, this);
            this.ds.un("add", this.onAdd, this);
            this.ds.un("remove", this.onRemove, this);
            this.ds.un("update", this.onUpdate, this);
            //this.ds.un("clear", this.onClear, this);
        }
        if(ds){
            ds.on("load", this.onLoad, this);
           // ds.on("datachanged", this.onDataChange, this);
            ds.on("add", this.onAdd, this);
            ds.on("remove", this.onRemove, this);
            ds.on("update", this.onUpdate, this);
            //ds.on("clear", this.onClear, this);
        }
        this.ds = ds;
    },
    
    /**
     * @private
     */
    initDragZone: function() {
        this.dragZone = new Ext.dd.DragZone(this.el, {
            ddGroup: 'cal-event',
            view: this,
            scroll: false,
            
            getDragData: function(e) {
                var eventEl = e.getTarget('div.cal-monthview-alldayevent', 10) || e.getTarget('div.cal-monthview-event', 10);
                if (eventEl) {
                    var parts = eventEl.id.split(':');
                    var event = this.view.ds.getById(parts[1]);
                    
                    // don't allow dragging with missing edit grant
                    if (this.view.denyDragOnMissingEditGrant && ! event.get('editGrant')) {
                        return false;
                    }
                    
                    // we need to clone an event with summary in
                    var d = Ext.get(event.ui.domIds[0]).dom.cloneNode(true);
                    
                    var width = Ext.fly(eventEl).getWidth() * event.ui.domIds.length;
                    
                    Ext.fly(d).removeClass(['cal-monthview-alldayevent-cropleft', 'cal-monthview-alldayevent-cropright']);
                    Ext.fly(d).setWidth(width);
                    Ext.fly(d).setOpacity(0.5);
                    d.id = Ext.id();
                    
                    return {
                        scope: this.view,
                        sourceEl: eventEl,
                        event: event,
                        ddel: d,
                        selections: this.view.getSelectionModel().getSelectedEvents()
                    }
                }
            },
            
            getRepairXY: function(e, dd) {
                Ext.fly(this.dragData.sourceEl).setOpacity(1, 1);
                return Ext.fly(this.dragData.sourceEl).getXY();
            }
        });
    },
    
    initDropZone: function() {
        this.dd = new Ext.dd.DropZone(this.el.dom, {
            ddGroup: 'cal-event',
            
            notifyOver : function(dd, e, data) {
                var target = e.getTarget('td.cal-monthview-daycell', 3);
                var event = data.event;
                
                // we dont support multiple dropping yet
                if (event) {
                    data.scope.getSelectionModel().select(event);
                }
                return target && event && event.get('editGrant') ? 'cal-daysviewpanel-event-drop-ok' : 'cal-daysviewpanel-event-drop-nodrop';
            },
            
            notifyDrop : function(dd, e, data) {
                var v = data.scope;
                
                var target = e.getTarget('td.cal-monthview-daycell', 3);
                var targetDate = v.dateMesh[v.dayCells.indexOf(target)];
                
                if (targetDate) {
                   var event = data.event;
                    
                    var diff = (targetDate.getTime() - event.get('dtstart').clearTime(true).getTime()) / Date.msDAY;
                    if (! diff  || ! event.get('editGrant')) {
                        return false;
                    }
                    
                    event.beginEdit();
                    event.set('dtstart', event.get('dtstart').add(Date.DAY, diff));
                    event.set('dtend', event.get('dtend').add(Date.DAY, diff));
                    event.endEdit();
                    
                    v.fireEvent('updateEvent', event);
                }
                
                return !!targetDate;
            }
        });
    },
    
    /**
     * @private
     */
    initElements: function() {
        var E = Ext.Element;

        this.focusEl = new E(this.calPanel.body.dom.firstChild);
        this.el = new E(this.calPanel.body.dom.lastChild);
        
        this.mainHd = new E(this.el.dom.firstChild);
        this.mainBody = new E(this.el.dom.lastChild);
        
        this.dayCells = Ext.DomQuery.select('td[class=cal-monthview-daycell]', this.mainBody.dom);
    },
    
    /**
     * inits all tempaltes of this view
     */
    initTemplates: function() {
        var ts = this.templates || {};
    
        ts.allDayEvent = new Ext.XTemplate(
            '<div id="{id}" class="cal-monthview-event cal-monthview-alldayevent {extraCls}" style="background-color: {bgColor};">' +
                '<div class="cal-event-icon {iconCls} cal-monthview-event-info-{[values.showInfo ? "show" : "hide"]}">' +
                    '<div class="cal-monthview-alldayevent-summary" style="width: {width};">{[Ext.util.Format.htmlEncode(values.summary)]}</div>' +
                '</div>' +
            '</div>'
        );
        
        ts.event = new Ext.XTemplate(
            '<div id="{id}" class="cal-monthview-event {extraCls}" style="color: {color};">' +
                '<div class="cal-event-icon {iconCls}">' +
                    '<div class="cal-monthview-event-summary">{startTime} {[Ext.util.Format.htmlEncode(values.summary)]}</div>' +
                '</div>' +
            '</div>'
        );
        
        for(var k in ts){
            var t = ts[k];
            if(t && typeof t.compile == 'function' && !t.compiled){
                t.disableFormats = true;
                t.compile();
            }
        }

        this.templates = ts;
    },
    
    /**
     * @private
     * @param {Tine.Calendar.Model.Event} event
     */
    insertEvent: function(event) {
        event.ui = new Tine.Calendar.MonthViewEventUI(event);
        //event.ui.render(this);
        
        var dtStart = event.get('dtstart');
        var startCellNumber = this.getDayCellIndex(dtStart);
        
        var dtEnd = event.get('dtend');
        // 00:00 in users timezone is a spechial case where the user expects
        // something like 24:00 and not 00:00
        if (dtEnd.format('H:i') == '00:00') {
            dtEnd = dtEnd.add(Date.MINUTE, -1);
        }
        var endCellNumber = this.getDayCellIndex(dtEnd);
        
        // skip out of range events
        if (endCellNumber < 0 || startCellNumber >= this.dateMesh.length) {
            return;
        }
        
        var pos = this.parallelEventsRegistry.getPosition(event);
        
        // save some layout info
        event.ui.is_all_day_event = event.get('is_all_day_event') || startCellNumber != endCellNumber;
        event.ui.colorSet = event.colorSet = Tine.Calendar.colorMgr.getColor(event);
        event.ui.color = event.ui.colorSet.color;
        event.ui.bgColor = event.ui.colorSet.light;
        
        var data = {
            startTime: dtStart.format('H:i'),
            summary: event.get('summary'),
            color: event.ui.color,
            bgColor: event.ui.bgColor,
            width: '100%'
        };
        
        for (var i=Math.max(startCellNumber, 0); i<=Math.min(endCellNumber, this.dayCells.length-1) ; i++) {
            var col = i%7, row = Math.floor(i/7);
            
            data.id = Ext.id() + '-event:' + event.get('id');
            event.ui.domIds.push(data.id);
                
            var tmpl = this.templates.event;
            data.extraCls = event.get('editGrant') ? 'cal-monthview-event-editgrant' : '';
            
            if (event.ui.is_all_day_event) {
                tmpl = this.templates.allDayEvent;
                data.color = 'black';
                
                if (i > startCellNumber) {
                    data.extraCls += ' cal-monthview-alldayevent-cropleft';
                }
                if (i < endCellNumber) {
                    data.extraCls += ' cal-monthview-alldayevent-cropright';
                }
                
                // show icon on startCell and leftCells
                data.showInfo = i == startCellNumber || i%7 == 0;
                
                // adopt summary width NOTE: we need width in row
                if (data.showInfo && startCellNumber != endCellNumber) {
                    var cols = (row == Math.floor(endCellNumber/7) ? endCellNumber%7 : 6) - col +1;
                    data.width = 100 * cols + '%'
                }
            } 
            
            var posEl = this.getEventSlice(this.dayCells[i].lastChild, pos);
            var eventEl = tmpl.overwrite(posEl, data, true);
            
            if (event.dirty) {
                eventEl.setOpacity(0.5);
                
                // the event was selected before
                event.ui.onSelectedChange(true);
            }
        }
    },
    
    layout: function() {
        if(!this.mainBody){
            return; // not rendered
        }
        
        var g = this.calPanel;
        var c = g.body;
        var csize = c.getSize(true);
        var vw = csize.width;
        
        //this.el.setSize(csize.width, csize.height);
        var hsize = this.mainHd.getSize(true);
        
        var hdCels = this.mainHd.dom.firstChild.childNodes;
        Ext.fly(hdCels[0]).setWidth(50);
        for (var i=1; i<hdCels.length; i++) {
            Ext.get(hdCels[i]).setWidth((vw-50)/7);
        }
        
        var rowHeight = ((csize.height - hsize.height - 2) / Math.ceil(this.dateMesh.length/7));

        var calRows = this.mainBody.dom.childNodes;
        for (var i=0; i<calRows.length; i++) {
            Ext.get(calRows[i]).setHeight(rowHeight);
        }
        
        var dhsize = Ext.get(this.dayCells[0].firstChild).getSize();
        this.dayCellsHeight = rowHeight - dhsize.height;

        for (var i=0; i<this.dayCells.length; i++) {
            Ext.get(this.dayCells[i].lastChild).setSize((vw-50)/7 ,this.dayCellsHeight);
        }
        
        this.layoutDayCells();
    },
    
    /**
     * layouts the contents (sets 'more items marker')
     */
    layoutDayCells: function() {
        for (var i=0; i<this.dayCells.length; i++) {
            if (this.dayCells[i].lastChild.childNodes.length > 1) {
                this.layoutDayCell(this.dayCells[i], true, true);
            }
        }
    },
    
    /**
     * layouts a single day cell
     * 
     * @param {dom} cell
     * @param {Bool} hideOverflow
     * @param {Bool} updateHeader
     */
    layoutDayCell: function(cell, hideOverflow, updateHeader) {
        // clean empty slices
        while (cell.lastChild.childNodes.length > 1 && cell.lastChild.lastChild.innerHTML == '') {
            Ext.fly(cell.lastChild.lastChild).remove();
        }
        
        for (var j=0, height=0, hideCount=0; j<cell.lastChild.childNodes.length; j++) {
            var eventEl = Ext.get(cell.lastChild.childNodes[j]);
            height += eventEl.getHeight();
            
            eventEl[height > this.dayCellsHeight && hideOverflow ? 'hide' : 'show']();

            if (height > this.dayCellsHeight && hideOverflow) {
                hideCount++;
            }
        }
        
        if (updateHeader) {
            cell.firstChild.firstChild.innerHTML = hideCount > 0 ? String.format(this.moreString, hideCount) : '';
        }
        
        return height;
    },
    
    /**
     * @private
     */
    onAdd : function(ds, records, index){
        for (var i=0; i<records.length; i++) {
            var event = records[i];
            this.parallelEventsRegistry.register(event);
            
            var parallelEvents = this.parallelEventsRegistry.getEvents(event.get('dtstart'), event.get('dtend'));
            
            for (var j=0; j<parallelEvents.length; j++) {
                this.removeEvent(parallelEvents[j]);
                this.insertEvent(parallelEvents[j]);
            }
            
            this.setActiveEvent(event);
        }
        
        this.layoutDayCells();
    },
    
    onClick: function(e, target) {
        
        // send click event anyway
        var event = this.getTargetEvent(e);
        if (event) {
            this.fireEvent('click', event, e);
            return;
        }
        
        /** distinct click from dblClick **/
        var now = new Date().getTime();
        
        if (now - parseInt(this.lastClickTime, 10) < 300) {
            this.lastClickTime = now;
            //e.stopEvent();
            return;
        }
        
        var dateTime = this.getTargetDateTime(e);
        if (Math.abs(dateTime - now) < 100) {
            this.lastClickTime = now;
            return this.onClick.defer(400, this, [e, target]);
        }
        this.lastClickTime = now;
        /** end distinct click from dblClick **/
        
        switch(target.className) {
            case 'cal-monthview-dayheader-date':
            case 'cal-monthview-dayheader-more':
                var moreText = target.parentNode.firstChild.innerHTML;
                if (! moreText) {
                    return;
                }
                
                //e.stopEvent();
                this.zoomDayCell(target.parentNode.parentNode);
                break;
        }
    },
    
    onContextMenu: function(e) {
        this.fireEvent('contextmenu', e);
    },
    
    onDblClick: function(e, target) {
        this.lastClickTime = new Date().getTime();
        
        e.stopEvent();
        
        var event = this.getTargetEvent(e);
        if (event) {
            this.fireEvent('dblclick', event, e);
            return;
        }
        
        switch(target.className) {
            case 'cal-monthview-wkcell':
                var wkIndex = Ext.DomQuery.select('td[class=cal-monthview-wkcell]', this.mainBody.dom).indexOf(target);
                var startDate = this.dateMesh[7*wkIndex];
                this.fireEvent('changeView', 'week', startDate);
                break;
                
            case 'cal-monthview-dayheader-date':
            case 'cal-monthview-dayheader-more':
                var dateIndex = this.dayCells.indexOf(target.parentNode.parentNode);
                var date = this.dateMesh[dateIndex];
                this.fireEvent('changeView', 'day', date);
                break;
                
            case 'cal-monthview-daycell':
                var dateIndex = this.dayCells.indexOf(target);
                var date = this.dateMesh[dateIndex];
                //console.log("Create event at: " + date.format('Y-m-d'));
                break;
        }
        
        //console.log(Ext.get(target));
    },
    
    /**
     * @private
     */
    onLoad : function(){
        if(! this.rendered){
            this.dsLoaded = true;
            return;
        }
        
        this.removeAllEvents();
        
        // create parallels registry
        this.parallelEventsRegistry = new Tine.Calendar.ParallelEventsRegistry({
            dtStart: this.dateMesh[0], 
            dtEnd: this.dateMesh[this.dateMesh.length-1].add(Date.DAY, 1)/*.add(Date.SECOND, -1)*/,
            granularity: 60*24
        });
        
        // todo: sort generic?
        this.ds.fields = Tine.Calendar.Model.Event.prototype.fields;
        this.ds.sortInfo = {field: 'dtstart', direction: 'ASC'};
        this.ds.applySort();
        
        // calculate duration and parallels
        this.ds.each(function(event) {
            this.parallelEventsRegistry.register(event);
        }, this);
        
        this.ds.each(this.insertEvent, this);
        this.layoutDayCells();
    },
    
    /**
     * @private
     */
    onMouseDown: function(e, target) {
        this.focusEl.focus();
        this.mainBody.focus();
        
        // only unzoom if click is not in the area of the daypreviewbox
        if (! e.getTarget('div.cal-monthview-daypreviewbox')) {
            this.unZoom();
        }
    },
    
    /**
     * @private
     */
    onRemove : function(ds, event, index, isUpdate){
        this.parallelEventsRegistry.unregister(event);
        this.removeEvent(event);
        this.getSelectionModel().unselect(event);
    },
    
    /**
     * @private
     */
    onUpdate : function(ds, event){
        // relayout original context
        var originalDtstart = event.modified.hasOwnProperty('dtstart') ? event.modified.dtstart : event.get('dtstart');
        var originalDtend = event.modified.hasOwnProperty('dtend') ? event.modified.dtend : event.get('dtend');
            
        var originalParallels = this.parallelEventsRegistry.getEvents(originalDtstart, originalDtend);
        for (var j=0; j<originalParallels.length; j++) {
            this.removeEvent(originalParallels[j]);
        }
        this.parallelEventsRegistry.unregister(event);
        
        var originalParallels = this.parallelEventsRegistry.getEvents(originalDtstart, originalDtend);
        for (var j=0; j<originalParallels.length; j++) {
            this.insertEvent(originalParallels[j]);
        }
        
        
        // relayout actual context
        var parallelEvents = this.parallelEventsRegistry.getEvents(event.get('dtstart'), event.get('dtend'));
        for (var j=0; j<parallelEvents.length; j++) {
            this.removeEvent(parallelEvents[j]);
        }
        this.parallelEventsRegistry.register(event);
        
        var parallelEvents = this.parallelEventsRegistry.getEvents(event.get('dtstart'), event.get('dtend'));
        for (var j=0; j<parallelEvents.length; j++) {
            this.insertEvent(parallelEvents[j]);
        }
        
        event.commit(true);
        this.setActiveEvent(this.getActiveEvent());
        this.layoutDayCells();
    },
    
    /**
     * print wrapper
     */
    print: function() {
        var renderer = new Tine.Calendar.Printer.MonthViewRenderer();
        renderer.print(this);
    },
    
    /**
     * removes all events from dom
     */
    removeAllEvents: function() {
        var els = Ext.DomQuery.filter(Ext.DomQuery.select('div[class^=cal-monthview-event]', this.mainBody.dom), 'div[class=cal-monthview-eventslice]', true);
        for (var i=0; i<els.length; i++) {
            Ext.fly(els[i]).remove();
        }
        
        this.ds.each(function(event) {
            if (event.ui) {
                event.ui.domIds = [];
            }
        });
        this.layoutDayCells();
    },
    
    /**
     * removes a event from the dom
     * @param {Tine.Calendar.Model.Event} event
     */
    removeEvent: function(event) {
        if (! event) {
            return;
        }
        
        if (event == this.activeEvent) {
            this.activeEvent = null;
        }
        
        if (event.ui) {
            event.ui.remove();
        }
    },
    
    render: function() {
        var m = [
             '<a href="#" class="cal-monthviewpanel-focus" tabIndex="-1"></a>',
             '<table class="cal-monthview-inner" cellspacing="0"><thead><tr class="cal-monthview-inner-header" height="23px">',
             "<th class='cal-monthview-wkcell-header'><span >", this.calWeekString, "</span></th>"
         ];
        for(var i = 0; i < 7; i++){
            var d = this.startDay+i;
            if(d > 6){
                d = d-7;
            }
            m.push("<th class='cal-monthview-daycell'><span>", this.dayNames[d], "</span></th>");
        }
        m[m.length] = "</tr></thead><tbody><tr><td class='cal-monthview-wkcell'></td>";
        for(var i = 0; i < 42; i++) {
            if(i % 7 == 0 && i != 0){
                m[m.length] = "</tr><tr><td class='cal-monthview-wkcell'></td>";
            }
            m[m.length] = 
                '<td class="cal-monthview-daycell">' +
                    '<div class="cal-monthview-dayheader">' +
                        '<div class="cal-monthview-dayheader-more"></div>' +
                        '<div class="cal-monthview-dayheader-date"></div>' +
                    '</div>' +
                    '<div class="cal-monthview-daybody"><div class="cal-monthview-eventslice" /></div>' +
                '</td>';
        }
        m.push('</tr></tbody></table>');
        
                
        var el = this.calPanel.body.dom;
        el.className = "cal-monthview";
        el.innerHTML = m.join("");

        //container.dom.insertBefore(el, position);
        //this.calPanel.body
    },
    
    /**
     * sets currentlcy active event
     * 
     * @param {Tine.Calendar.Model.Event} event
     *
    setActiveEvent: function(event) {
        if (this.activeEvent) {
            var curEls = this.getEventEls(this.activeEvent);
            for (var i=0; i<curEls.length; i++) {
                curEls[i].removeClass('cal-monthview-active');
                if (this.activeEvent.is_all_day_event) {
                    curEls[i].setStyle({'background-color': this.activeEvent.bgColor});
                    curEls[i].setStyle({'color': '#000000'});
                } else {
                    curEls[i].setStyle({'background-color': ''});
                    curEls[i].setStyle({'color': event.color});
                }
            }
        }
        
        
        
        var els = this.getEventEls(event);
        if (event && els && els.length > 0) {
            var els = this.getEventEls(event);
            for (var i=0; i<els.length; i++) {
                els[i].addClass('cal-monthview-active');
                if (event.is_all_day_event) {
                    els[i].setStyle({'background-color': event.color});
                    els[i].setStyle({'color': '#FFFFFF'});
                } else {
                    els[i].setStyle({'background-color': event.color});
                    els[i].setStyle({'color': '#FFFFFF'});
                }
            }
            this.activeEvent = event;
        }
    },
    */
    
    /**
     * sets currentlcy active event
     * 
     * NOTE: active != selected
     * @param {Tine.Calendar.Model.Event} event
     */
    setActiveEvent: function(event) {
        this.activeEvent = event || null;
    },
    
    updatePeriod: function(period) {
        this.toDay = new Date().clearTime();
        this.startDate = period.from;
        this.calcDateMesh();
        
        var tbar = this.calPanel.getTopToolbar();
        if (tbar) {
            tbar.periodPicker.update(this.startDate);
            this.startDate = tbar.periodPicker.getPeriod().from;
        }
        
        // update dates and bg colors
        var dayHeaders = Ext.DomQuery.select('div[class=cal-monthview-dayheader-date]', this.mainBody.dom);
        for(var i=0; i<this.dateMesh.length; i++) {
            this.dayCells[i].style.background = this.dateMesh[i].getMonth() == this.startDate.getMonth() ? '#FFFFFF' : '#F9F9F9';
            if (this.dateMesh[i].getTime() == this.toDay.getTime()) {
                this.dayCells[i].style.background = '#EBF3FD';
            }
                
            dayHeaders[i].innerHTML = this.dateMesh[i].format('j');
        }
        
        // update weeks
        var wkCells = Ext.DomQuery.select('td[class=cal-monthview-wkcell]', this.mainBody.dom);
        for(var i=0; i<wkCells.length; i++) {
            if (this.dateMesh.length > i*7 +1) {
                // NOTE: '+1' is to ensure we display the ISO8601 based week where weeks always start on monday!
                wkCells[i].innerHTML = this.dateMesh[i*7 +1].getWeekOfYear();
                //Ext.fly(wkCells[i]).unselectable(); // this supresses events ;-(
            }
        }
        
        this.layout();
        this.fireEvent('changePeriod', period);
    },
    
    unZoom: function() {
        if (this.zoomCell) {
            // this prevents reopen of cell on header clicks
            this.lastClickTime = new Date().getTime();
            
            var cell = Ext.get(this.zoomCell);
            var dayBodyEl = cell.last();
            var height = cell.getHeight() - cell.first().getHeight();
            dayBodyEl.scrollTo('top');
            dayBodyEl.removeClass('cal-monthview-daypreviewbox');
            dayBodyEl.setStyle('background-color', cell.getStyle('background-color'));
            dayBodyEl.setStyle('border-top', 'none');
            dayBodyEl.setHeight(height);
            
            
            // NOTE: we need both setWidht statements, otherwise safari keeps scroller space
            for (var i=0; i<dayBodyEl.dom.childNodes.length; i++) {
                Ext.get(dayBodyEl.dom.childNodes[i]).setWidth(dayBodyEl.getWidth());
                Ext.get(dayBodyEl.dom.childNodes[i]).setWidth(dayBodyEl.first().getWidth());
            }
            
            this.layoutDayCell(this.zoomCell, true, true);
            
            this.zoomCell = false;
        }
        
    },
    
    zoomDayCell: function(cell) {
        this.zoomCell = cell;
        
        var dayBodyEl = Ext.get(cell.lastChild);
        var box = dayBodyEl.getBox();
        var bgColor = Ext.fly(cell).getStyle('background-color');
        bgColor == 'transparent' ? '#FFFFFF' : bgColor
        
        dayBodyEl.addClass('cal-monthview-daypreviewbox');
        dayBodyEl.setBox(box);
        dayBodyEl.setStyle('background-color', bgColor);
        dayBodyEl.setStyle('border-top', '1px solid ' + bgColor);
        
        var requiredHeight = this.layoutDayCell(cell, false, true) + 10;
        var availHeight = this.calPanel.el.getBottom() - box.y;
        dayBodyEl.setHeight(Math.min(requiredHeight, availHeight));
    }
});
// file: /home/lkneschke/temp/tine20build/temp/tine20/Calendar/js/PagingToolbar.js
/* 
 * Tine 2.0
 * 
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Cornelius Weiss <c.weiss@metaways.de>
 * @copyright   Copyright (c) 2007-2008 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: PagingToolbar.js 12124 2010-01-04 14:44:39Z c.weiss@metaways.de $
 */

Ext.ns('Tine.Calendar');

Tine.Calendar.PagingToolbar = Ext.extend(Ext.Toolbar, {
    /**
     * @cfg {Date} dtstart
     */
    dtStart: null,
    /**
     * @cfg {String} view
     */
    view: 'day',
    /**
     * @private periodPicker
     */
    periodPicker: null,
    
    /**
     * @private
     */
    initComponent: function() {
        this.addEvents(
            /**
             * @event change
             * Fired whenever a viewstate changes
             * @param {Tine.Calendar.PagingToolbar} this
             * @param {String} activeView
             * @param {Array} period
             */
            'change',
            /**
             * @event refresh
             * Fired when user request view freshresh
             * @param {Tine.Calendar.PagingToolbar} this
             * @param {String} activeView
             * @param {Array} period
             */
            'refresh'
        );
        if (! Ext.isDate(this.dtStart)) {
            this.dtStart = new Date();
        }
        
        this.periodPicker = new Tine.Calendar.PagingToolbar[Ext.util.Format.capitalize(this.view) + 'PeriodPicker']({
            tb: this,
            listeners: {
                scope: this,
                change: function(picker, view, period) {
                    this.dtStart = period.from.clone();
                    this.fireEvent('change', this, view, period);
                }
            }
        });
        
        Tine.Calendar.PagingToolbar.superclass.initComponent.call(this);
        this.bind(this.store);
    },
    
    /**
     * @private
     */
    onRender: function(ct, position) {
        Tine.Calendar.PagingToolbar.superclass.onRender.call(this, ct, position);
        this.prevBtn = this.addButton({
            tooltip: Ext.PagingToolbar.prototype.prevText,
            iconCls: "x-tbar-page-prev",
            handler: this.onClick.createDelegate(this, ["prev"])
        });
        this.addSeparator();
        this.periodPicker.render();
        this.addSeparator();
        this.nextBtn = this.addButton({
            tooltip: Ext.PagingToolbar.prototype.nextText,
            iconCls: "x-tbar-page-next",
            handler: this.onClick.createDelegate(this, ["next"])
        });
        this.addSeparator();
        this.todayBtn = this.addButton({
            text: Ext.DatePicker.prototype.todayText,
            iconCls: 'cal-today-action',
            handler: this.onClick.createDelegate(this, ["today"])
        });
        this.loading = this.addButton({
            tooltip: Ext.PagingToolbar.prototype.refreshText,
            iconCls: "x-tbar-loading",
            handler: this.onClick.createDelegate(this, ["refresh"])
        });
        
        this.addFill();
        
        if(this.isLoading){
            this.loading.disable();
        }
        
    },
    
    /**
     * @private
     * @param {String} which
     */
    onClick: function(which) {
        switch(which) {
            case 'today':
            case 'next':
            case 'prev':
                this.periodPicker[which]();
                this.fireEvent('change', this, this.activeView, this.periodPicker.getPeriod());
                break;
            case 'refresh':
                this.fireEvent('refresh', this, this.activeView, this.periodPicker.getPeriod());
                break;
        }
    },
    
    /**
     * returns requested period
     * @return {Array}
     */
    getPeriod: function() {
        return this.periodPicker.getPeriod();
    },
    
    // private
    beforeLoad : function(){
        this.isLoading = true;
        
        if(this.rendered && this.loading) {
            this.loading.disable();
        }
    },
    
    // private
    onLoad : function(store, r, o){
        this.isLoading = false;
        
        if(this.rendered && this.loading) {
            this.loading.enable();
        }
    },


    
    /**
     * Unbinds the paging toolbar from the specified {@link Ext.data.Store}
     * @param {Ext.data.Store} store The data store to unbind
     */
    unbind : function(store){
        store = Ext.StoreMgr.lookup(store);
        store.un("beforeload", this.beforeLoad, this);
        store.un("load", this.onLoad, this);
        //store.un("loadexception", this.onLoadError, this);
        this.store = undefined;
    },

    /**
     * Binds the paging toolbar to the specified {@link Ext.data.Store}
     * @param {Ext.data.Store} store The data store to bind
     */
    bind : function(store){
        store = Ext.StoreMgr.lookup(store);
        store.on("beforeload", this.beforeLoad, this);
        store.on("load", this.onLoad, this);
        //store.on("loadexception", this.onLoadError, this);
        this.store = store;
    },

    // private
    onDestroy : function(){
        if(this.store){
            this.unbind(this.store);
        }
        Tine.Calendar.PagingToolbar.superclass.onDestroy.call(this);
    }
});

/**
 * @class Tine.Calendar.PagingToolbar.AbstractPeriodPicker
 * @extends Ext.util.Observable
 * @constructor
 * @param {Object} config
 */
Tine.Calendar.PagingToolbar.AbstractPeriodPicker = function(config) {
    Ext.apply(this, config);
    this.addEvents(
        /**
         * @event change
         * Fired whenever a period changes
         * @param {Tine.Calendar.PagingToolbar.AbstractPeriodPicker} this
         * @param {String} corresponding view
         * @param {Array} period
         */
        'change'
    );
    Tine.Calendar.PagingToolbar.AbstractPeriodPicker.superclass.constructor.call(this);
    
    this.update(this.tb.dtStart);
    this.init();
};
Ext.extend(Tine.Calendar.PagingToolbar.AbstractPeriodPicker, Ext.util.Observable, {
    init:       function() {},
    hide:       function() {this.button.hide();},
    show:       function() {this.button.show();},
    update:     function(dtStart) {},
    render:     function() {},
    prev:       function() {},
    next:       function() {},
    today:      function() {this.update(new Date().clearTime());},
    getPeriod:  function() {}
});

/**
 * @class Tine.Calendar.PagingToolbar.DayPeriodPicker
 * @extends Tine.Calendar.PagingToolbar.AbstractPeriodPicker
 * @constructor
 */
Tine.Calendar.PagingToolbar.DayPeriodPicker = Ext.extend(Tine.Calendar.PagingToolbar.AbstractPeriodPicker, {
    init: function() {
        this.button = new Ext.Button({
            text: this.tb.dtStart.format(Ext.DatePicker.prototype.format),
            //hidden: this.tb.activeView != 'day',
            menu: new Ext.menu.DateMenu({
                listeners: {
                    scope: this,
                    select: function(field) {
                        if (typeof(field.getValue) == 'function') {
                            this.update(field.getValue());
                            this.fireEvent('change', this, 'day', this.getPeriod());
                        }
                    }
                }
            })
        });
    },
    update: function(dtStart) {
        this.dtStart = dtStart.clone();
        if (this.button && this.button.rendered) {
            this.button.setText(dtStart.format(Ext.DatePicker.prototype.format));
        }
    },
    render: function() {
        this.button = this.tb.addButton(this.button);
    },
    next: function() {
        this.dtStart = this.dtStart.add(Date.DAY, 1);
        this.update(this.dtStart);
    },
    prev: function() {
        this.dtStart = this.dtStart.add(Date.DAY, -1);
        this.update(this.dtStart);
    },
    getPeriod: function() {
        var from = Date.parseDate(this.dtStart.format('Y-m-d') + ' 00:00:00', Date.patterns.ISO8601Long);
        return {
            from: from,
            until: from.add(Date.DAY, 1)/*.add(Date.SECOND, -1)*/
        };
    }
});

/**
 * @class Tine.Calendar.PagingToolbar.WeekPeriodPicker
 * @extends Tine.Calendar.PagingToolbar.AbstractPeriodPicker
 * @constructor
 */
Tine.Calendar.PagingToolbar.WeekPeriodPicker = Ext.extend(Tine.Calendar.PagingToolbar.AbstractPeriodPicker, {
    init: function() {
        this.label = new Ext.form.Label({
            text: Tine.Tinebase.appMgr.get('Calendar').i18n._('Week'),
            style: 'padding-right: 3px'
            //hidden: this.tb.activeView != 'week'
        });
        this.field = new Ext.form.TextField({
            value: this.tb.dtStart.getWeekOfYear(),
            width: 30,
            cls: "x-tbar-page-number",
            //hidden: this.tb.activeView != 'week',
            listeners: {
                scope: this,
                specialkey: this.onSelect,
                blur: this.onSelect
            }
        });
    },
    onSelect: function(field, e) {
        if (e && e.getKey() == e.ENTER) {
            return field.blur();
        }
        var diff = field.getValue() - this.dtStart.getWeekOfYear() - parseInt(this.dtStart.getDay() < 1 ? 1 : 0, 10);
        if (diff !== 0) {
            this.update(this.dtStart.add(Date.DAY, diff * 7))
            this.fireEvent('change', this, 'week', this.getPeriod());
        }
        
    },
    update: function(dtStart) {
        //recalculate dtstart begin of week 
        var from = dtStart.add(Date.DAY, -1 * dtStart.getDay());
        if (Ext.DatePicker.prototype.startDay) {
            from = from.add(Date.DAY, Ext.DatePicker.prototype.startDay - (dtStart.getDay() == 0 ? 7 : 0));
        }
        this.dtStart = from;
        
        if (this.field && this.field.rendered) {
            // NOTE: '+1' is to ensure we display the ISO8601 based week where weeks always start on monday!
            var wkStart = dtStart.add(Date.DAY, dtStart.getDay() < 1 ? 1 : 0);
            
            this.field.setValue(parseInt(wkStart.getWeekOfYear(), 10));
        }
    },
    render: function() {
        this.tb.addField(this.label);
        this.tb.addField(this.field);
    },
    hide: function() {
        this.label.hide();
        this.field.hide();
    },
    show: function() {
        this.label.show();
        this.field.show();
    },
    next: function() {
        this.dtStart = this.dtStart.add(Date.DAY, 7);
        this.update(this.dtStart);
    },
    prev: function() {
        this.dtStart = this.dtStart.add(Date.DAY, -7);
        this.update(this.dtStart);
    },
    getPeriod: function() {
        return {
            from: this.dtStart.clone(),
            until: this.dtStart.add(Date.DAY, 7)
        };
    }/*
    getPeriod: function() {
        // period is the week current startDate is in
        var startDay = Ext.DatePicker.prototype.startDay;
        console.log(startDay);
        var diff = startDay - this.dtStart.getDay();
        console.log(diff);
        
        var from = Date.parseDate(this.dtStart.add(Date.DAY, diff).format('Y-m-d') + ' 00:00:00', Date.patterns.ISO8601Long);
        return {
            from: from,
            until: from.add(Date.DAY, 7)
        };
    }*/
});

/**
 * @class Tine.Calendar.PagingToolbar.MonthPeriodPicker
 * @extends Tine.Calendar.PagingToolbar.AbstractPeriodPicker
 * @constructor
 */
Tine.Calendar.PagingToolbar.MonthPeriodPicker = Ext.extend(Tine.Calendar.PagingToolbar.AbstractPeriodPicker, {
    init: function() {
        this.dateMenu = new Ext.menu.DateMenu({
            hideMonthPicker: Ext.DatePicker.prototype.hideMonthPicker.createSequence(function() {
                if (this.monthPickerActive) {
                    this.monthPickerActive = false;
                    
                    this.value = this.activeDate;
                    this.fireEvent('select', this, this.value);
                }
            }),
            listeners: {
                scope: this,
                select: function(field) {
                    if (typeof(field.getValue) == 'function') {
                        this.update(field.getValue());
                        this.fireEvent('change', this, 'month', this.getPeriod());
                    }
                }
            }
        });
        
        this.button = new Ext.Button({
            minWidth: 130,
            text: Ext.DatePicker.prototype.monthNames[this.tb.dtStart.getMonth()] + this.tb.dtStart.format(' Y'),
            //hidden: this.tb.activeView != 'month',
            menu: this.dateMenu,
            listeners: {
                scope: this,
                menushow: function(btn, menu) {
                    menu.picker.showMonthPicker();
                    menu.picker.monthPickerActive = true;
                },
                menuhide: function(btn, menu) {
                    menu.picker.monthPickerActive = false;
                }
            }
        });
    },
    update: function(dtStart) {
        this.dtStart = dtStart.clone();
        if (this.button && this.button.rendered) {
            var monthName = Ext.DatePicker.prototype.monthNames[dtStart.getMonth()];
            this.button.setText(monthName + dtStart.format(' Y'));
            this.dateMenu.picker.setValue(dtStart);
        }
    },
    render: function() {
        this.button = this.tb.addButton(this.button);
    },
    next: function() {
        this.dtStart = this.dtStart.add(Date.MONTH, 1);
        this.update(this.dtStart);
    },
    prev: function() {
        this.dtStart = this.dtStart.add(Date.MONTH, -1);
        this.update(this.dtStart);
    },
    getPeriod: function() {
        var from = Date.parseDate(this.dtStart.format('Y-m') + '-01 00:00:00', Date.patterns.ISO8601Long);
        return {
            from: from,
            until: from.add(Date.MONTH, 1)/*.add(Date.SECOND, -1)*/
        };
    }
});
// file: /home/lkneschke/temp/tine20build/temp/tine20/Calendar/js/EventDetailsPanel.js
/* 
 * Tine 2.0
 * 
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Cornelius Weiss <c.weiss@metaways.de>
 * @copyright   Copyright (c) 2009 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: EventDetailsPanel.js 17349 2010-11-25 10:18:01Z c.weiss@metaways.de $
 */
 
Ext.ns('Tine.Calendar');

/**
 * @class Tine.Calendar.EventDetailsPanel
 * @namespace Tine.Calendar
 * @extends Tine.widgets.grid.DetailsPanel
 * @author Cornelius Weiss <c.weiss@metaways.de>
 * @version $Id: EventDetailsPanel.js 17349 2010-11-25 10:18:01Z c.weiss@metaways.de $
 */
Tine.Calendar.EventDetailsPanel = Ext.extend(Tine.widgets.grid.DetailsPanel, {
    border: false,
    
    /**
     * renders attendee names
     * 
     * @param {Array} attendeeData
     * @return {String}
     */
    attendeeRenderer: function(attendeeData) {
        var attendeeStore = Tine.Calendar.Model.Attender.getAttendeeStore(attendeeData);
        
        var a = [];
        attendeeStore.each(function(attender) {
            var name = Tine.Calendar.AttendeeGridPanel.prototype.renderAttenderName.call(Tine.Calendar.AttendeeGridPanel.prototype, attender.get('user_id'), false, attender);
            var status = Tine.Calendar.AttendeeGridPanel.prototype.renderAttenderStatus.call(Tine.Calendar.AttendeeGridPanel.prototype, attender.get('status'), {}, attender);
            a.push(name + ' (' + status + ')');
        });
        
        return a.join("\n");
    },
    
    /**
     * renders container name + color
     * 
     * @param {Array} container
     * @return {String} html
     */
    containerRenderer: function(container) {
        var displayContainer = this.record.getDisplayContainer();
        return this.containerTpl.apply({
            color: Tine.Calendar.colorMgr.getColor(displayContainer).color,
            name: Ext.util.Format.htmlEncode(displayContainer && displayContainer.name ? displayContainer.name : this.app.i18n._('Unknown calendar'))
        });
    },
    
    /**
     * renders datetime
     * 
     * @param {Date} dt
     * @return {String}
     */
    datetimeRenderer: function(dt) {
        return String.format(this.app.i18n._("{0} {1} o'clock"), Tine.Tinebase.common.dateRenderer(dt), dt.format('H:i'));
    },
    
    transpRenderer: function(transp) {
        return Tine.Tinebase.common.booleanRenderer(transp == 'OPAQUE');
    },
    
    /**
     * inits this component
     */
    initComponent: function() {
        this.app = Tine.Tinebase.appMgr.get('Calendar');
        
        /*
        this.defaultPanel = this.getDefaultPanel();
        this.eventDetailsPanel = this.getEventDetailsPanel();
        
        this.cardPanel = new Ext.Panel({
            layout: 'card',
            border: false,
            activeItem: 0,
            items: [
                this.defaultPanel,
                this.eventDetailsPanel
            ]
        });
        
        this.items = [
            this.cardPanel
        ];
        */
        
        // TODO generalize this
        this.containerTpl = new Ext.XTemplate(
            '<div class="x-tree-node-leaf x-unselectable file">',
                '<img class="x-tree-node-icon" unselectable="on" src="', Ext.BLANK_IMAGE_URL, '">',
                '<span style="color: {color};">&nbsp;&#9673;&nbsp</span>',
                '<span>{name}</span>',
            '</div>'
        ).compile();
        
        this.supr().initComponent.call(this);
    },
    
    /**
     * default panel w.o. data
     * 
     * @return {Ext.ux.display.DisplayPanel}
     */
    getDefaultInfosPanel: function() {
        if (! this.defaultInfosPanel) {
            this.defaultInfosPanel = new Ext.ux.display.DisplayPanel ({
                layout: 'fit',
                border: false,
                items: [{
                    layout: 'hbox',
                    border: false,
                    defaults:{margins:'0 5 0 0'},
                    layoutConfig: {
                        padding:'5',
                        align:'stretch'
                    },
                    items: [{
                        flex: 1,
                        border: false,
                        layout: 'ux.display',
                        layoutConfig: {
                            background: 'solid',
                            declaration: this.app.i18n.n_('Event', 'Events', 50)
                        }
                    }, {
                        flex: 1,
                        border: false,
                        layout: 'ux.display',
                        layoutConfig: {
                            background: 'border'
                        }
                    }]
                }]
            });
        }
        
        return this.defaultInfosPanel;
    },
    
    /**
     * main event details panel
     * 
     * @return {Ext.ux.display.DisplayPanel}
     */
    getSingleRecordPanel: function() {
        if (! this.singleRecordPanel) {
            this.singleRecordPanel = new Ext.ux.display.DisplayPanel ({
                //xtype: 'displaypanel',
                layout: 'fit',
                border: false,
                items: [{
                    layout: 'vbox',
                    border: false,
                    layoutConfig: {
                        align:'stretch'
                    },
                    items: [{
                        layout: 'hbox',
                        flex: 0,
                        height: 16,
                        border: false,
                        style: 'padding-left: 5px; padding-right: 5px',
                        layoutConfig: {
                            align:'stretch'
                        },
                        items: [{
                            flex: 1,
                            xtype: 'ux.displayfield',
                            name: 'summary',
                            style: 'padding-top: 2px',
                            cls: 'x-ux-display-header'
                            //fieldLabel: this.app.i18n._('Summary')
                        }, {
                            flex: 1,
                            xtype: 'ux.displayfield',
                            style: 'text-align: right;',
                            cls: 'x-ux-display-header',
                            name: 'container_id',
                            htmlEncode: false,
                            renderer: this.containerRenderer.createDelegate(this)
                        }]
                    }, {
                        layout: 'hbox',
                        flex: 1,
                        border: false,
                        layoutConfig: {
                            padding:'5',
                            align:'stretch'
                        },
                        defaults:{
                            margins:'0 5 0 0'
                        },
                        items: [{
                            flex: 2,
                            layout: 'ux.display',
                            labelWidth: 60,
                            layoutConfig: {
                                background: 'solid'
                            },
                            items: [{
                                // TODO try to increase padding/margin of first element
                                /*
                                style: 'margin-top: 4px',
                                labelStyle: 'margin-top: 4px',
                                */
                                xtype: 'ux.displayfield',
                                name: 'dtstart',
                                fieldLabel: this.app.i18n._('Start Time'),
                                renderer: this.datetimeRenderer.createDelegate(this)
                            }, {
                                xtype: 'ux.displayfield',
                                name: 'dtend',
                                fieldLabel: this.app.i18n._('End Time'),
                                renderer: this.datetimeRenderer.createDelegate(this)
                            }, {
                                xtype: 'ux.displayfield',
                                name: 'transp',
                                fieldLabel: this.app.i18n._('Blocking'),
                                renderer: this.transpRenderer.createDelegate(this)
                            }, {
                                xtype: 'ux.displayfield',
                                name: 'location',
                                fieldLabel: this.app.i18n._('Location')
                            }, {
                                xtype: 'ux.displayfield',
                                name: 'organizer',
                                fieldLabel: this.app.i18n._('Organizer'),
                                renderer: function(organizer) {
                                    return organizer && organizer.n_fileas ? organizer.n_fileas : '';
                                }
                            }]
                        }, {
                            flex: 2,
                            layout: 'ux.display',
                            labelAlign: 'top',
                            autoScroll: true,
                            layoutConfig: {
                                background: 'solid'
                            },
                            items: [{
                                xtype: 'ux.displayfield',
                                name: 'attendee',
                                nl2br: true,
                                fieldLabel: this.app.i18n._('Attendee'),
                                renderer: this.attendeeRenderer
                            }]
                        }, {
                            flex: 3,
                            layout: 'fit',
                            
                            border: false,
                            items: [{
                                cls: 'x-ux-display-background-border',
                                xtype: 'ux.displaytextarea',
                                name: 'description'
                            }]
                        }]
                    }]
                }]
            });
        }
        
        return this.singleRecordPanel;
    },
    
    /**
     * update event details panel
     * 
     * @param {Tine.Tinebase.data.Record} record
     * @param {Mixed} body
     */
    updateDetails: function(record, body) {
        //this.cardPanel.layout.setActiveItem(this.cardPanel.items.getKey(this.eventDetailsPanel));
        
        this.getSingleRecordPanel().loadRecord.defer(100, this.getSingleRecordPanel(), [record]);
        //return this.supr().updateDetails.apply(this, arguments);
    }
    
//    /**
//     * show default panel
//     * 
//     * @param {Mixed} body
//     */
//    showDefault: function(body) {
//        this.cardPanel.layout.setActiveItem(this.cardPanel.items.getKey(this.defaultPanel));
//    },
//    
//    /**
//     * show template for multiple rows
//     * 
//     * @param {Ext.grid.RowSelectionModel} sm
//     * @param {Mixed} body
//     */
//    showMulti: function(sm, body) {
//        //if (this.multiTpl) {
//        //    this.multiTpl.overwrite(body);
//        //}
//    }
});

// file: /home/lkneschke/temp/tine20build/temp/tine20/Calendar/js/MainScreenCenterPanel.js
/* 
 * Tine 2.0
 * 
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Cornelius Weiss <c.weiss@metaways.de>
 * @copyright   Copyright (c) 2007-2008 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: MainScreenCenterPanel.js 18996 2011-02-01 17:31:59Z c.weiss@metaways.de $
 */

/*global Ext, Tine*/

Ext.ns('Tine.Calendar');

Tine.Calendar.MainScreenCenterPanel = Ext.extend(Ext.Panel, {
    /**
     * @cfg {String} activeView
     */
    activeView: 'week',
    
    startDate: new Date().clearTime(),
    
    /**
     * $property Object view -> startdate
     */
    startDates: null,
    
    calendarPanels: {},
    
    border: false,
    layout: 'border',
    
    stateful: true,
    stateId: 'cal-mainscreen',
    stateEvents: ['changeview'],
    
    getState: function () {
        return Ext.copyTo({}, this, 'activeView');
    },
    
    applyState: Ext.emptyFn,
    
    initComponent: function () {
        this.addEvents(
        /**
         * @event changeview
         * fired if an event got clicked
         * @param {Tine.Calendar.MainScreenCenterPanel} mspanel
         * @param {String} view
         */
        'changeview');
        
        this.recordClass = Tine.Calendar.Model.Event;
        
        this.app = Tine.Tinebase.appMgr.get('Calendar');
        
        // init some translations
        this.i18nRecordName = this.app.i18n.n_hidden(this.recordClass.getMeta('recordName'), this.recordClass.getMeta('recordsName'), 1);
        this.i18nRecordsName = this.app.i18n._hidden(this.recordClass.getMeta('recordsName'));
        this.i18nContainerName = this.app.i18n.n_hidden(this.recordClass.getMeta('containerName'), this.recordClass.getMeta('containersName'), 1);
        this.i18nContainersName = this.app.i18n._hidden(this.recordClass.getMeta('containersName'));
        
        var state = Ext.state.Manager.get(this.stateId, {});
        Ext.apply(this, state);
        
        this.defaultFilters = [
            {field: 'attender', operator: 'in', value: [Ext.apply(Tine.Calendar.Model.Attender.getDefaultData(), {
                user_id: Tine.Tinebase.registry.get('currentAccount')
            })]},
            {field: 'attender_status', operator: 'notin', value: ['DECLINED']}
        ];
        this.filterToolbar = this.getFilterToolbar();
        this.filterToolbar.onFilterChange = this.refresh.createDelegate(this, [false]);
        this.filterToolbar.getAllFilterData = this.getAllFilterData.createDelegate(this);
        
        this.filterToolbar.getQuickFilterPlugin().criteriaIgnores.push(
            {field: 'period'},
            {field: 'grants'}
        );
        
        this.startDates = [];
        this.initActions();
        this.initLayout();
        
        Tine.Calendar.MainScreenCenterPanel.superclass.initComponent.call(this);
    },
    
    initActions: function () {
        this.action_editInNewWindow = new Ext.Action({
            requiredGrant: 'readGrant',
            text: this.i18nEditActionText ? this.app.i18n._hidden(this.i18nEditActionText) : String.format(Tine.Tinebase.translation._hidden('Edit {0}'), this.i18nRecordName),
            disabled: true,
            handler: this.onEditInNewWindow.createDelegate(this, ["edit"]),
            iconCls: 'action_edit'
        });
        
        this.action_addInNewWindow = new Ext.Action({
            requiredGrant: 'addGrant',
            text: this.i18nAddActionText ? this.app.i18n._hidden(this.i18nAddActionText) : String.format(Tine.Tinebase.translation._hidden('Add {0}'), this.i18nRecordName),
            handler: this.onEditInNewWindow.createDelegate(this, ["add"]),
            iconCls: 'action_add'
        });
        
        // note: unprecise plural form here, but this is hard to change
        this.action_deleteRecord = new Ext.Action({
            requiredGrant: 'deleteGrant',
            allowMultiple: true,
            singularText: this.i18nDeleteActionText ? i18nDeleteActionText[0] : String.format(Tine.Tinebase.translation.n_hidden('Delete {0}', 'Delete {0}', 1), this.i18nRecordName),
            pluralText: this.i18nDeleteActionText ? i18nDeleteActionText[1] : String.format(Tine.Tinebase.translation.n_hidden('Delete {0}', 'Delete {0}', 1), this.i18nRecordsName),
            translationObject: this.i18nDeleteActionText ? this.app.i18n : Tine.Tinebase.translation,
            text: this.i18nDeleteActionText ? this.i18nDeleteActionText[0] : String.format(Tine.Tinebase.translation.n_hidden('Delete {0}', 'Delete {0}', 1), this.i18nRecordName),
            handler: this.onDeleteRecords,
            disabled: true,
            iconCls: 'action_delete',
            scope: this
        });
        
        this.actions_print = new Ext.Action({
            requiredGrant: 'readGrant',
            text: this.app.i18n._('Print Page'),
            handler: this.onPrint,
            iconCls:'action_print',
            scope: this
        });
        
        this.showDayView = new Ext.Toolbar.Button({
            pressed: this.activeView === 'day',
            text: this.app.i18n._('Day'),
            iconCls: 'cal-day-view',
            xtype: 'tbbtnlockedtoggle',
            handler: this.changeView.createDelegate(this, ["day"]),
            enableToggle: true,
            toggleGroup: 'Calendar_Toolbar_tgViews'
        });
        this.showWeekView = new Ext.Toolbar.Button({
            pressed: this.activeView === 'week',
            text: this.app.i18n._('Week'),
            iconCls: 'cal-week-view',
            xtype: 'tbbtnlockedtoggle',
            handler: this.changeView.createDelegate(this, ["week"]),
            enableToggle: true,
            toggleGroup: 'Calendar_Toolbar_tgViews'
        });
        this.showMonthView = new Ext.Toolbar.Button({
            pressed: this.activeView === 'month',
            text: this.app.i18n._('Month'),
            iconCls: 'cal-month-view',
            xtype: 'tbbtnlockedtoggle',
            handler: this.changeView.createDelegate(this, ["month"]),
            enableToggle: true,
            toggleGroup: 'Calendar_Toolbar_tgViews'
        });
        
        this.changeViewActions = [
            this.showDayView,
            this.showWeekView,
            this.showMonthView
        ];
        
        this.recordActions = [
            this.action_editInNewWindow,
            this.action_deleteRecord
        ];
        
        this.actionUpdater = new  Tine.widgets.ActionUpdater({
            actions: this.recordActions,
            grantsProperty: false,
            containerProperty: false
        });
    },
    
    /**
     * @private
     * 
     * NOTE: Order of items matters! Ext.Layout.Border.SplitRegion.layout() does not
     *       fence the rendering correctly, as such it's impotant, so have the ftb
     *       defined after all other layout items
     */
    initLayout: function () {
        this.items = [{
            region: 'center',
            layout: 'card',
            activeItem: 0,
            border: false,
            items: [this.getCalendarPanel(this.activeView)]
        }];
        
        // add detail panel
        if (this.detailsPanel) {
            this.items.push({
                region: 'south',
                border: false,
                collapsible: true,
                collapseMode: 'mini',
                header: false,
                split: true,
                layout: 'fit',
                height: this.detailsPanel.defaultHeight ? this.detailsPanel.defaultHeight : 125,
                items: this.detailsPanel
                
            });
            //this.detailsPanel.doBind(this.activeView);
        }
        
        // add filter toolbar
        if (this.filterToolbar) {
            this.items.push({
                region: 'north',
                border: false,
                items: this.filterToolbar,
                listeners: {
                    scope: this,
                    afterlayout: function (ct) {
                    	ct.suspendEvents();
                        ct.setHeight(this.filterToolbar.getHeight());
                        ct.ownerCt.layout.layout();
                        ct.resumeEvents();
                    }
                }
            });
        }
    },
    
    changeView: function (view, startDate) {
        Tine.log.debug('Tine.Calendar.MainScreenCenterPanel::changeView(' + view + ',' + startDate + ')');
        // save current startDate
        this.startDates[this.activeView] = this.startDate.clone();
        
        if (startDate && Ext.isDate(startDate)) {
            this.startDate = startDate.clone();
        } else {
            // see if a recent startDate of that view fits
            var lastStartDate = this.startDates[view],
                currentPeriod = this.getCalendarPanel(this.activeView).getView().getPeriod();
                
            if (Ext.isDate(lastStartDate) && lastStartDate.between(currentPeriod.from, currentPeriod.until)) {
                this.startDate = this.startDates[view].clone();
            }
        }
        
        var panel = this.getCalendarPanel(view);
        var cardPanel = this.items.first();
        
        if (panel.rendered) {
            cardPanel.layout.setActiveItem(panel.id);
        } else {
            cardPanel.add(panel);
            cardPanel.layout.setActiveItem(panel.id);
            cardPanel.doLayout();
        }
        
        this.activeView = view;
        
        // move around changeViewButtons
        var rightRow = Ext.get(Ext.DomQuery.selectNode('tr[class=x-toolbar-right-row]', panel.tbar.dom));
        
        for (var i = this.changeViewActions.length - 1; i >= 0; i--) {
            rightRow.insertFirst(this.changeViewActions[i].getEl().parent().dom);
        }
        this['show' + Ext.util.Format.capitalize(view) +  'View'].toggle(true);
        
        // update actions
        this.updateEventActions();
        
        // update data
        panel.getView().updatePeriod({from: this.startDate});
        panel.getStore().load({});
        
        this.fireEvent('changeview', this, view);
    },
    
    getActionToolbar: Tine.widgets.grid.GridPanel.prototype.getActionToolbar,
    
    getActionToolbarItems: Tine.widgets.grid.GridPanel.prototype.getActionToolbarItems,
    
    /**
     * returns all filter data for current view
     */
    getAllFilterData: function () {
        var store = this.getCalendarPanel(this.activeView).getStore();
        
        var options = {};
        // NOTE: Only THIS.onStoreBeforeload is called. 
        //       The store beforeLoad event is NOT fired!
        //       This skips the period filter which comes form calendarPanel::onBeforeLoad!
        this.onStoreBeforeload(store, options);
        
        return options.params.filter;
    },
    
    getCustomfieldFilters: Tine.widgets.grid.GridPanel.prototype.getCustomfieldFilters,
    
    getFilterToolbar: Tine.widgets.grid.GridPanel.prototype.getFilterToolbar,
    
    /**
     * returns store of currently active view
     */
    getStore: function () {
        return this.getCalendarPanel(this.activeView).getStore();
    },
    
    onContextMenu: function (e) {
        e.stopEvent();
        
        var view = this.getCalendarPanel(this.activeView).getView();
        var event = view.getTargetEvent(e);
        var datetime = view.getTargetDateTime(e);
        
        var addAction, responseAction;
        if (datetime || event) {
            var dtStart = datetime || event.get('dtstart').clone();
            if (dtStart.format('H:i') === '00:00') {
                dtStart = dtStart.add(Date.HOUR, 9);
            }
            addAction = {
                text: this.i18nAddActionText ? this.app.i18n._hidden(this.i18nAddActionText) : String.format(Tine.Tinebase.translation._hidden('Add {0}'), this.i18nRecordName),
                handler: this.onEditInNewWindow.createDelegate(this, ["add", dtStart]),
                iconCls: 'action_add'
            };
            
            // assemble response action
            if (event) {
                var myAttenderRecord = event.getMyAttenderRecord();
                if (myAttenderRecord) {
                    responseAction = {
                        text: this.app.i18n._('Set my response'),
                        iconCls: 'cal-response-action-' + myAttenderRecord.get('status'),
                        menu: []
                    };
                    
                    Tine.Calendar.Model.Attender.getAttendeeStatusStore().each(function(status) {
                        responseAction.menu.push({
                            text: status.get('status_name'),
                            handler: this.setResponseStatus.createDelegate(this, [event, status.id]),
                            iconCls: 'cal-response-action-' + status.id,
                            disabled: myAttenderRecord.get('status') === status.id
                        });
                    }, this);
                }
            }

        } else {
            addAction = this.action_addInNewWindow;
        }
        
        if (event) {
            view.getSelectionModel().select(event, e, e.ctrlKey);
        } else {
            view.getSelectionModel().clearSelections();
        }
           
        var ctxMenu = new Ext.menu.Menu({
            items: this.recordActions.concat(addAction, responseAction || [])
        });
        ctxMenu.showAt(e.getXY());
    },
    
    onDeleteRecords: function () {
        var panel = this.getCalendarPanel(this.activeView);
        var selection = panel.getSelectionModel().getSelectedEvents();
        
        var containsRecurBase = false;
        var containsRecurInstance = false;
        
        Ext.each(selection, function (event) {
            event.ui.markDirty();
            if (event.isRecurInstance()) {
                containsRecurInstance = true;
            }
            if (event.isRecurBase()) {
                containsRecurBase = true;
            }
        });
        
        if (selection.length > 1 && (containsRecurBase || containsRecurInstance)) {
            Ext.Msg.show({
                title: this.app.i18n._('Please Change Selection'), 
                msg: this.app.i18n._('Your selection contains recurring events. Recuring events must be deleted seperatly!'),
                icon: Ext.MessageBox.INFO,
                buttons: Ext.Msg.OK,
                scope: this,
                fn: function () {
                    this.onDeleteRecordsConfirmFail(panel, selection);
                }
            });
            return;
        }
        
        if (selection.length === 1 && (containsRecurBase || containsRecurInstance)) {
            if (containsRecurBase) {
                Ext.MessageBox.confirm(
                    this.app.i18n._('Confirm Deletion of Series'),
                    this.app.i18n._('Do you really want to delete all events of this recurring event series?'),
                    function (btn) {
                        if (btn === 'yes') {
                            panel.loadMask.show();
                            this.onDeleteRecordsConfirmNonRecur(panel, selection);
                            this.refresh(true);
                        } else {
                            this.onDeleteRecordsConfirmFail(panel, selection);
                        }
                    }, this
                );
            } else {
                this.deleteMethodWin = Tine.widgets.dialog.MultiOptionsDialog.openWindow({
                    title: this.app.i18n._('Delete Event'),
                    scope: this,
                    height: 170,
                    options: [
                        {text: this.app.i18n._('Delete whole series'), name: 'all'},
                        {text: this.app.i18n._('Delete this and all future events'), name: 'future'},
                        {text: this.app.i18n._('Delete this event only'), name: 'this'},
                        {text: this.app.i18n._('Delete nothing'), name: 'nothing'}
                    ],
                    handler: function (option) {
                        switch (option) 
                        {
                        case 'all':
                        case 'this':
                        case 'future':
                            panel.getTopToolbar().beforeLoad();
                            if (option !== 'this') {
                                panel.loadMask.show();
                            }
                            
                            var options = {
                                scope: this,
                                success: function () {
                                    if (option === 'this') {
                                        Ext.each(selection, function (event) {
                                            panel.getStore().remove(event);
                                        });
                                        panel.getTopToolbar().onLoad();
                                    } else {
                                        this.refresh(true);
                                    }
                                    
                                },
                                failure: function () {
                                    panel.getTopToolbar().onLoad();
                                    Ext.MessageBox.alert(Tine.Tinebase.translation._hidden('Failed'), String.format(this.app.i18n.n_('Failed not delete event', 'Failed to delete the {0} events', selection.length), selection.length)); 
                                }
                            };
                            
                            if (option === 'all') {
                                Tine.Calendar.backend.deleteRecurSeries(selection[0], options);
                            } else {
                                Tine.Calendar.backend.createRecurException(selection[0], true, option === 'future', options);
                            }
                            break;
                        default:
                            this.onDeleteRecordsConfirmFail(panel, selection);
                            break;
                        }
                    }
                });
            }
            return;
        }
        
        // else
        var i18nQuestion = String.format(this.app.i18n.ngettext('Do you really want to delete this event?', 'Do you really want to delete the {0} selected events?', selection.length), selection.length);
        Ext.MessageBox.confirm(Tine.Tinebase.translation._hidden('Confirm'), i18nQuestion, function (btn) {
            if (btn === 'yes') {
                this.onDeleteRecordsConfirmNonRecur(panel, selection);
            } else {
                this.onDeleteRecordsConfirmFail(panel, selection);
            }
        }, this);
        
    },
    
    onDeleteRecordsConfirmNonRecur: function (panel, selection) {
        panel.getTopToolbar().beforeLoad();
        
        // create a copy of selection so selection changes don't affect this
        var sel = Ext.unique(selection);
                
        var options = {
            scope: this,
            success: function () {
                panel.getTopToolbar().onLoad();
                Ext.each(sel, function (event) {
                    panel.getStore().remove(event);
                });
            },
            failure: function () {
                panel.getTopToolbar().onLoad();
                Ext.MessageBox.alert(Tine.Tinebase.translation._hidden('Failed'), String.format(this.app.i18n.n_('Failed not delete event', 'Failed to delete the {0} events', selection.length), selection.length)); 
            }
        };
        
        Tine.Calendar.backend.deleteRecords(selection, options);
    },
    
    onDeleteRecordsConfirmFail: function (panel, selection) {
        Ext.each(selection, function (event) {
			event.ui.clearDirty();
        });
    },
    
    /**
     * @param {String} action add|edit
     */
    onEditInNewWindow: function (action, dtStart) {
        var event = null;
        
        if (action === 'edit') {
            var panel = this.getCalendarPanel(this.activeView);
            var selection = panel.getSelectionModel().getSelectedEvents();
            if (Ext.isArray(selection) && selection.length === 1) {
                event = selection[0];
            }
        }
        
        if (! event) {
            event = new Tine.Calendar.Model.Event(Tine.Calendar.Model.Event.getDefaultData(), 0);
            if (Ext.isDate(dtStart)) {
                event.set('dtstart', dtStart);
                event.set('dtend', dtStart.add(Date.HOUR, 1));
            }
        }
        
        Tine.Calendar.EventEditDialog.openWindow({
            record: Ext.util.JSON.encode(event.data),
            recordId: event.data.id,
            listeners: {
                scope: this,
                update: function (eventJson) {
                    //var updatedEvent = new Tine.Calendar.Model.Event(Ext.util.JSON.decode(eventJson), event.id);
                    var updatedEvent = Tine.Calendar.backend.recordReader({responseText: eventJson});
                    updatedEvent.dirty = true;
                    event.phantom = (action === 'edit');
                    
                    var panel = this.getCalendarPanel(this.activeView);
                    var store = panel.getStore();
                    
                    event = store.getById(event.id);
                    
                    store.remove(event);
                    store.add(updatedEvent);
                    
                    panel.onUpdateEvent(updatedEvent);
                }
            }
        });
    },
    
    onKeyDown: function (e) {
        if (e.ctrlKey) {
            switch (e.getKey()) 
            {
            case e.A:
                // select only current page
                //this.grid.getSelectionModel().selectAll(true);
                e.preventDefault();
                break;
            case e.E:
                if (!this.action_editInNewWindow.isDisabled()) {
                    this.onEditInNewWindow('edit');
                }
                e.preventDefault();
                break;
            case e.N:
                if (!this.action_addInNewWindow.isDisabled()) {
                    this.onEditInNewWindow('add');
                }
                e.preventDefault();
                break;    
            }
        } else if (e.getKey() === e.DELETE) {
        	if (! this.action_deleteRecord.isDisabled()) {
                this.onDeleteRecords.call(this);
            }
        }
    },
    
    onPrint: function() {
        var panel = this.getCalendarPanel(this.activeView),
            view = panel ? panel.getView() : null;
            
        if (view && Ext.isFunction(view.print)) {
            view.print();
        } else {
            Ext.Msg.alert(this.app.i18n._('Could not Print'), this.app.i18n._('Sorry, your current view does not support printing.'));
        }
    },
    
    /**
     * called before store queries for data
     */
    onStoreBeforeload: function (store, options) {
        options.params = options.params || {};
        
        // define a transaction
        this.lastStoreTransactionId = options.transactionId = Ext.id();
        
        // allways start with an empty filter set!
        // this is important for paging and sort header!
        options.params.filter = [];
        
        // note, we can't use the 'normal' plugin approach here, cause we have to deal with n stores
        //var calendarSelectionPlugin = this.app.getMainScreen().getWestPanel().getContainerTreePanel().getFilterPlugin();
        //calendarSelectionPlugin.onBeforeLoad.call(calendarSelectionPlugin, store, options);
        
        this.filterToolbar.onBeforeLoad.call(this.filterToolbar, store, options);
    },
    
    /**
     * fence against loading of wrong data set
     */
    onStoreBeforeLoadRecords: function(o, options, success) {
        return this.lastStoreTransactionId === options.transactionId;
    },
    
    /**
     * called when store loaded data
     */
    onStoreLoad: function (store, options) {
        // check if store is current store
        if (store !== this.getCalendarPanel(this.activeView).getStore()) {
            console.log('not active anymore');
            return;
        }
        
        // update filtertoolbar
        this.filterToolbar.setValue(store.proxy.jsonReader.jsonData.filter);
        
        // update tree
        Tine.Tinebase.appMgr.get('Calendar').getMainScreen().getWestPanel().getContainerTreePanel().getFilterPlugin().setValue(store.proxy.jsonReader.jsonData.filter);
    },
    
    refresh: function (refresh) {
        Tine.log.debug('Tine.Calendar.MainScreenCenterPanel::refresh(' + refresh + ')');
        var panel = this.getCalendarPanel(this.activeView);
        panel.getStore().load({
            refresh: refresh
        });
        
        // clear favorites
        Tine.Tinebase.appMgr.get('Calendar').getMainScreen().getWestPanel().getFavoritesPanel().getSelectionModel().clearSelections();
    },
    
    setResponseStatus: function(event, status) {
        var myAttenderRecord = event.getMyAttenderRecord();
        if (myAttenderRecord) {
            myAttenderRecord.set('status', status);
            event.dirty = true;
            
            var panel = this.getCalendarPanel(this.activeView);
            var store = panel.getStore();
                    
            store.remove(event);
            store.add(event);
            
            panel.onUpdateEvent(event);
        }
    },
    
    updateEventActions: function () {
        var panel = this.getCalendarPanel(this.activeView);
        var selection = panel.getSelectionModel().getSelectedEvents();
        
        this.actionUpdater.updateActions(selection);
        if (this.detailsPanel) {
            this.detailsPanel.onDetailsUpdate(panel.getSelectionModel());
        }
    },
    
    updateView: function (which) {
        Tine.log.debug('Tine.Calendar.MainScreenCenterPanel::updateView(' + which + ')');
        var panel = this.getCalendarPanel(which);
        var period = panel.getTopToolbar().getPeriod();
        
        panel.getView().updatePeriod(period);
        panel.getStore().load({});
        //this.updateMiniCal();
    },
    
    /**
     * returns requested CalendarPanel
     * 
     * @param {String} which
     * @return {Tine.Calendar.CalendarPanel}
     */
    getCalendarPanel: function (which) {
        if (! this.calendarPanels[which]) {
            var store = new Ext.data.Store({
                //autoLoad: true,
                id: 'id',
                fields: Tine.Calendar.Model.Event,
                proxy: Tine.Calendar.backend,
                reader: new Ext.data.JsonReader({}), //Tine.Calendar.backend.getReader(),
                listeners: {
                    scope: this,
                    'beforeload': this.onStoreBeforeload,
                    'beforeloadrecords' : this.onStoreBeforeLoadRecords,
                    'load': this.onStoreLoad
                }
            });
            
            var tbar = new Tine.Calendar.PagingToolbar({
                view: which,
                store: store,
                dtStart: this.startDate,
                listeners: {
                    scope: this,
                    // NOTE: only render the button once for the toolbars
                    //       the buttons will be moved on chageView later
                    render: function (tbar) {
                        for (var i = 0; i < this.changeViewActions.length; i += 1) {
                            if (! this.changeViewActions[i].rendered) {
                                tbar.addButton(this.changeViewActions[i]);
                            }
                        }
                    }
                }
            });
            
            tbar.on('change', this.updateView.createDelegate(this, [which]), this, {buffer: 200});
            tbar.on('refresh', this.refresh.createDelegate(this, [true]), this, {buffer: 200});
            
            var view;
            switch (which) {
                case 'day':
                    view = new Tine.Calendar.DaysView({
                        startDate: tbar.getPeriod().from,
                        numOfDays: 1
                    });
                    break;
                case 'week':
                    view = new Tine.Calendar.DaysView({
                        startDate: tbar.getPeriod().from,
                        numOfDays: 7
                    });
                    break;
                case 'month':
                    view = new Tine.Calendar.MonthView({
                        period: tbar.getPeriod()
                    });
                    break;
            }
            
            view.on('changeView', this.changeView, this);
            view.on('changePeriod', function (period) {
                this.startDate = period.from;
                this.startDates[which] = this.startDate.clone();
                this.updateMiniCal();
            }, this);
            
            view.on('dblclick', this.onEditInNewWindow.createDelegate(this, ["edit"]));
            view.on('contextmenu', this.onContextMenu, this);
            
            this.calendarPanels[which] = new Tine.Calendar.CalendarPanel({
                tbar: tbar,
                store: store,
                view: view
            });
            
            this.calendarPanels[which].getSelectionModel().on('selectionchange', this.updateEventActions, this);
            this.calendarPanels[which].on('keydown', this.onKeyDown, this);
            
            this.calendarPanels[which].on('render', function () {
                var defaultFavorite = Tine.widgets.persistentfilter.model.PersistentFilter.getDefaultFavorite(this.app.appName);
                var favoritesPanel  = this.app.getMainScreen().getWestPanel().getFavoritesPanel();
                // NOTE: this perfoms the initial load!
                favoritesPanel.selectFilter(defaultFavorite);
            }, this);
            
            this.calendarPanels[which].relayEvents(this, ['show', 'beforehide']);
        }
        
        return this.calendarPanels[which];
    },
    
    updateMiniCal: function () {
        var miniCal = Ext.getCmp('cal-mainscreen-minical');
        var weekNumbers = null;
        var period = this.getCalendarPanel(this.activeView).getView().getPeriod();
        
        switch (this.activeView) 
        {
        case 'week' :
            weekNumbers = [period.from.add(Date.DAY, 1).getWeekOfYear()];
            break;
        case 'month' :
            weekNumbers = [];
            var startWeek = period.from.add(Date.DAY, 1).getWeekOfYear();
            var numWeeks = Math.round((period.until.getTime() - period.from.getTime()) / Date.msWEEK);
            for (var i = 0; i < numWeeks; i += 1) {
                weekNumbers.push(startWeek + i);
            }
        	break;
        }
        miniCal.update(this.startDate, true, weekNumbers);
    }
});

// file: /home/lkneschke/temp/tine20build/temp/tine20/Calendar/js/MainScreenWestPanel.js
/* 
 * Tine 2.0
 * 
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Cornelius Weiss <c.weiss@metaways.de>
 * @copyright   Copyright (c) 2009-2010 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: MainScreenWestPanel.js 14743 2010-05-31 20:16:17Z c.weiss@metaways.de $
 */

Ext.ns('Tine.Calendar');

/**
 * Calendar west panel
 * 
 * @namespace   Tine.Calendar
 * @class       Tine.Calendar.MainScreenWestPanel
 * @extends     Tine.widgets.mainscreen.WestPanel
 * 
 * @author      Cornelius Weiss <c.weiss@metaways.de>
 * @version     $Id: MainScreenWestPanel.js 14743 2010-05-31 20:16:17Z c.weiss@metaways.de $
 * 
 * @constructor
 * @xtype       tine.calendar.mainscreenwestpanel
 */
Tine.Calendar.MainScreenWestPanel = Ext.extend(Tine.widgets.mainscreen.WestPanel, {
    
    containerTreePanelClassName: 'CalendarSelectTreePanel',
    cls: 'cal-tree',
    
    getAdditionalItems: function() {
        return [Ext.apply({
            title: this.app.i18n._('Mini Calendar'),
            forceLayout: true,
            border: false,
            layout: 'hbox',
            layoutConfig: {
                align:'middle'
            },
            defaults: {border: false},
            items: [{
                flex: 1
            }, this.getDatePicker(), {
                flex: 1
            }]
        }, this.defaults)];
    },
    
    getDatePicker: function() {
        if (! this.datePicker) {
            this.datePicker = new Ext.DatePicker({
                width: 200,
                id :'cal-mainscreen-minical',
                plugins: [new Ext.ux.DatePickerWeekPlugin({
                    weekHeaderString: Tine.Tinebase.appMgr.get('Calendar').i18n._('WK'),
                    inspectMonthPickerClick: function(btn, e) {
                        if (e.getTarget('button')) {
                            var contentPanel = Tine.Tinebase.appMgr.get('Calendar').getMainScreen().getCenterPanel();
                            contentPanel.changeView('month', this.activeDate);
                            
                            return false;
                        }
                    }
                })],
                listeners: {
                    scope: this, 
                    select: function(picker, value, weekNumber) {
                        var contentPanel = Tine.Tinebase.appMgr.get('Calendar').getMainScreen().getCenterPanel();
                        contentPanel.changeView(weekNumber ? 'week' : 'day', value);
                    }
                }
            });
        }
        
        return this.datePicker;
    }
});

Ext.reg('tine.calendar.mainscreenwestpanel', Tine.Calendar.MainScreenWestPanel);
// file: /home/lkneschke/temp/tine20build/temp/tine20/Calendar/js/Calendar.js
/* 
 * Tine 2.0
 * 
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Cornelius Weiss <c.weiss@metaways.de>
 * @copyright   Copyright (c) 2009 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: CalendarPanel.js 7900 2009-04-30 07:23:02Z c.weiss@metaways.de $
 */

Ext.namespace('Tine.Calendar');

/**
 * update app icon to reflect the current date
 */
Ext.onReady(function(){
    Ext.util.CSS.updateRule('.CalendarIconCls', 'background-image', 'url(images/view-calendar-day-' + new Date().getDate() + '.png)');
});

/**
 * @namespace   Tine.Calendar
 * @class       Tine.Calendar.Application
 * @extends     Tine.Tinebase.Application
 * Calendar Application Object <br>
 * 
 * @author      Cornelius Weiss <c.weiss@metaways.de>
 * @version     $Id: AttendeeGridPanel.js 9749 2009-08-05 09:08:34Z c.weiss@metaways.de $
 */
Tine.Calendar.Application = Ext.extend(Tine.Tinebase.Application, {
    /**
     * Get translated application title of the calendar application
     * 
     * @return {String}
     */
    getTitle: function() {
        return this.i18n.ngettext('Calendar', 'Calendars', 1);
    },
    
    /**
     * returns iconCls of this application
     * 
     * @param {String} target
     * @return {String}
     */
    getIconCls: function(target) {
        switch(target){
            case 'PreferencesTreePanel':
            return 'PreferencesTreePanel-CalendarIconCls';
            break;
        default:
            return 'CalendarIconCls';
            break;
        }
    }
});

/**
 * @namespace Tine.Calendar
 * @class Tine.Calendar.MainScreen
 * @extends Tine.widgets.MainScreen
 * MainScreen of the Calendar Application <br>
 * 
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Cornelius Weiss <c.weiss@metaways.de>
 * @copyright   Copyright (c) 2009 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: AttendeeGridPanel.js 9749 2009-08-05 09:08:34Z c.weiss@metaways.de $
 * @constructor
 * Constructs mainscreen of the calendar application
 */
Tine.Calendar.MainScreen = function(config) {
    Ext.apply(this, config);
    Tine.Calendar.colorMgr = new Tine.Calendar.ColorManager({});
    
    Tine.Calendar.MainScreen.superclass.constructor.apply(this, arguments);
}

Ext.extend(Tine.Calendar.MainScreen, Tine.widgets.MainScreen, {
    
    
    westPanelXType: 'tine.calendar.mainscreenwestpanel',
    
    /**
     * Get content panel of calendar application
     * 
     * @return {Tine.Calendar.MainScreenCenterPanel}
     */
    getCenterPanel: function() {
        if (! this.contentPanel) {
            this.contentPanel = new Tine.Calendar.MainScreenCenterPanel({
                detailsPanel: new Tine.Calendar.EventDetailsPanel()
            });
        }
        
        return this.contentPanel;
    },
    
    /**
     * Set toolbar panel in Tinebase.MainScreen
     */
    showNorthPanel: function() {
        if (! this.actionToolbar) {
            this.actionToolbar = this.contentPanel.getActionToolbar();
        }
        
        Tine.Tinebase.MainScreen.setActiveToolbar(this.actionToolbar, true);
    }
});


// file: /home/lkneschke/temp/tine20build/temp/tine20/Calendar/js/EventEditDialog.js
/*
 * Tine 2.0
 * 
 * @package     Calendar
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Cornelius Weiss <c.weiss@metaways.de>
 * @copyright   Copyright (c) 2009 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: EventEditDialog.js 12936 2010-02-24 09:15:34Z c.weiss@metaways.de $
 *
 */
 
Ext.ns('Tine.Calendar');

/**
 * @namespace Tine.Calendar
 * @class Tine.Calendar.EventEditDialog
 * @extends Tine.widgets.dialog.EditDialog
 * Calendar Edit Dialog <br>
 * 
 * @author      Cornelius Weiss <c.weiss@metaways.de>
 * @version     $Id: EventEditDialog.js 12936 2010-02-24 09:15:34Z c.weiss@metaways.de $
 */
Tine.Calendar.EventEditDialog = Ext.extend(Tine.widgets.dialog.EditDialog, {
    /**
     * @cfg {Number} containerId initial container id
     */
    containerId: -1,
    
    
    labelAlign: 'side',
    windowNamePrefix: 'EventEditWindow_',
    appName: 'Calendar',
    recordClass: Tine.Calendar.Model.Event,
    recordProxy: Tine.Calendar.backend,
    showContainerSelector: false,
    tbarItems: [{xtype: 'widget-activitiesaddbutton'}],
    
    mode: 'local',
    
    // note: we need up use new action updater here or generally in the widget!
    evalGrants: false,
    
    
    onResize: function() {
        Tine.Calendar.EventEditDialog.superclass.onResize.apply(this, arguments);
        this.setTabHeight.defer(100, this);
    },
    
    
    /**
     * returns dialog
     * 
     * NOTE: when this method gets called, all initalisation is done.
     * @return {Object} components this.itmes definition
     */
    getFormItems: function() { 
        return {
            xtype: 'tabpanel',
            border: false,
            plain:true,
            activeTab: 0,
            border: false,
            items:[{
                title: this.app.i18n.n_('Event', 'Events', 1),
                border: false,
                frame: true,
                layout: 'border',
                items: [{
                    region: 'center',
                    layout: 'hfit',
                    border: false,
                    items: [{
                        xtype: 'fieldset',
                        layout: 'hfit',
                        autoHeight:true,
                        title: this.app.i18n.n_('Event', 'Events', 1),
                        items: [{
                            xtype: 'columnform',
                            labelAlign: 'side',
                            labelWidth: 100,
                            formDefaults: {
                                xtype:'textfield',
                                anchor: '100%',
                                labelSeparator: '',
                                columnWidth: .6
                            },
                            items: [[{
                                columnWidth: 1,
                                fieldLabel: this.app.i18n._('Summary'),
                                name: 'summary',
                                listeners: {render: function(field){field.focus(false, 250);}},
                                allowBlank: false,
                                requiredGrant: 'editGrant'
                            }], [{
                                columnWidth: 1,
                                fieldLabel: this.app.i18n._('Location'),
                                name: 'location',
                                requiredGrant: 'editGrant'
                            }], [{
                                xtype: 'datetimefield',
                                fieldLabel: this.app.i18n._('Start Time'),
                                listeners: {scope: this, change: this.onDtStartChange},
                                name: 'dtstart',
                                requiredGrant: 'editGrant'
                            }, {
                                columnWidth: .4,
                                xtype: 'combo',
                                hideLabel: true,
                                readOnly: true,
                                hideTrigger: true,
                                disabled: true,
                                name: 'originator_tz',
                                requiredGrant: 'editGrant'
                            }], [{
                                xtype: 'datetimefield',
                                fieldLabel: this.app.i18n._('End Time'),
                                listeners: {scope: this, change: this.onDtEndChange},
                                name: 'dtend',
                                requiredGrant: 'editGrant'
                            }, {
                                columnWidth: .17,
                                xtype: 'checkbox',
                                hideLabel: true,
                                boxLabel: this.app.i18n._('whole day'),
                                listeners: {scope: this, check: this.onAllDayChange},
                                name: 'is_all_day_event',
                                requiredGrant: 'editGrant'
                            }, {
                                columnWidth: .23,
                                xtype: 'checkbox',
                                hideLabel: true,
                                boxLabel: this.app.i18n._('non-blocking'),
                                name: 'transp',
                                requiredGrant: 'editGrant',
                                //id: 'mycheckid',
                                getValue: function() {
                                    var bool = Ext.form.Checkbox.prototype.getValue.call(this);
                                    return bool ? 'TRANSPARENT' : 'OPAQUE';
                                },
                                setValue: function(value) {
                                    var bool = (value == 'TRANSPARENT' || value === true);
                                    return Ext.form.Checkbox.prototype.setValue.call(this, bool);
                                }
                            }]]
                        }]
                    },{
                        xtype: 'fieldset',
                        layout: 'hfit',
                        autoHeight:true,
                        title: Tine.Tinebase.translation._hidden('Saved in'),
                        items: [{
                            layout: 'column',
                            items: [Ext.apply(this.CalendarSelectWidget, {columnWidth: .77}), {
                                columnWidth: .23,
                                xtype: 'checkbox',
                                hideLabel: true,
                                boxLabel: this.app.i18n._('Private'),
                                name: 'class',
                                requiredGrant: 'editGrant',
                                getValue: function() {
                                    var bool = Ext.form.Checkbox.prototype.getValue.call(this);
                                    return bool ? 'PRIVATE' : 'PUBLIC';
                                },
                                setValue: function(value) {
                                    var bool = (value == 'PRIVATE' || value === true);
                                    return Ext.form.Checkbox.prototype.setValue.call(this, bool);
                                }
                            }]
                        }]
                    }, {
                        xtype: 'tabpanel',
                        deferredRender: false,
                        activeTab: 0,
                        border: false,
                        height: 235,
                        form: true,
                        items: [
                            this.attendeeGridPanel,
                            this.rrulePanel,
                            this.alarmPanel
                        ]
                    }]
                }, {
                    // activities and tags
                    region: 'east',
                    layout: 'accordion',
                    animate: true,
                    width: 200,
                    split: true,
                    collapsible: true,
                    collapseMode: 'mini',
                    header: false,
                    margins: '0 5 0 5',
                    border: true,
                    items: [
                        new Ext.Panel({
                            // @todo generalise!
                            title: this.app.i18n._('Description'),
                            iconCls: 'descriptionIcon',
                            layout: 'form',
                            labelAlign: 'top',
                            border: false,
                            items: [{
                                style: 'margin-top: -4px; border 0px;',
                                labelSeparator: '',
                                xtype:'textarea',
                                name: 'description',
                                hideLabel: true,
                                grow: false,
                                preventScrollbars:false,
                                anchor:'100% 100%',
                                emptyText: this.app.i18n._('Enter description'),
                                requiredGrant: 'editGrant'                           
                            }]
                        }),
                        new Tine.widgets.activities.ActivitiesPanel({
                            app: 'Calendar',
                            showAddNoteForm: false,
                            border: false,
                            bodyStyle: 'border:1px solid #B5B8C8;'
                        }),
                        new Tine.widgets.tags.TagPanel({
                            app: 'Calendar',
                            border: false,
                            bodyStyle: 'border:1px solid #B5B8C8;'
                        })
                    ]
                }]
            }, new Tine.widgets.activities.ActivitiesTabPanel({
                app: this.appName,
                record_id: (this.record) ? this.record.id : '',
                record_model: this.appName + '_Model_' + this.recordClass.getMeta('modelName')
            })]
        };
    },
    
    initComponent: function() {
        this.attendeeGridPanel = new Tine.Calendar.AttendeeGridPanel({});
        this.rrulePanel = new Tine.Calendar.RrulePanel({});
        this.alarmPanel = new Tine.widgets.dialog.AlarmPanel({});         
        this.attendeeStore = this.attendeeGridPanel.getStore();
        
        this.CalendarSelectWidget = new Tine.Calendar.CalendarSelectWidget(this);
        
        Tine.Calendar.EventEditDialog.superclass.initComponent.call(this);
    },
    
    /**
     * checks if form data is valid
     * 
     * @return {Boolean}
     */
    isValid: function() {
        var isValid = this.validateDtStart() && this.validateDtEnd();
        
        if (! this.rrulePanel.isValid()) {
            isValid = false;
            
            this.rrulePanel.ownerCt.setActiveTab(this.rrulePanel);
        }
        
        return isValid && Tine.Calendar.EventEditDialog.superclass.isValid.apply(this, arguments);
    },
    
    onAllDayChange: function(checkbox, isChecked) {
        var dtStartField = this.getForm().findField('dtstart');
        var dtEndField = this.getForm().findField('dtend');
        dtStartField.setDisabled(isChecked, 'time');
        dtEndField.setDisabled(isChecked, 'time');
        
        if (isChecked) {
            dtStartField.clearTime();
            var dtend = dtEndField.getValue()
            if (Ext.isDate(dtend) && dtend.format('H:i:s') != '23:59:59') {
                dtEndField.setValue(dtend.clearTime(true).add(Date.HOUR, 24).add(Date.SECOND, -1));
            }
            
        } else {
            dtStartField.undo();
            dtEndField.undo();
        }
    },
    
    onDtEndChange: function(dtEndField, newValue, oldValue) {
        this.validateDtEnd();
    },
    
    onDtStartChange: function(dtStartField, newValue, oldValue) {
        if (Ext.isDate(newValue) && Ext.isDate(oldValue)) {
            var diff = newValue.getTime() - oldValue.getTime();
            var dtEndField = this.getForm().findField('dtend');
            var dtEnd = dtEndField.getValue();
            if (Ext.isDate(dtEnd)) {
                dtEndField.setValue(dtEnd.add(Date.MILLI, diff));
            }
        }
    },
    
    onRecordLoad: function() {
        // NOTE: it comes again and again till 
        if (this.rendered) {
            this.attendeeGridPanel.onRecordLoad(this.record);
            this.rrulePanel.onRecordLoad(this.record);
            this.alarmPanel.onRecordLoad(this.record);
            this.CalendarSelectWidget.onRecordLoad(this.record);
            
            // apply grants
            if (! this.record.get('editGrant')) {
                this.getForm().items.each(function(f){
                    if(f.isFormField && f.requiredGrant !== undefined){
                        f.setDisabled(! this.record.get(f.requiredGrant));
                    }
                }, this);
            }
        }
        
        Tine.Calendar.EventEditDialog.superclass.onRecordLoad.apply(this, arguments);
    },
    
    onRecordUpdate: function() {
        Tine.Calendar.EventEditDialog.superclass.onRecordUpdate.apply(this, arguments);
        this.attendeeGridPanel.onRecordUpdate(this.record);
        this.rrulePanel.onRecordUpdate(this.record);
        this.alarmPanel.onRecordUpdate(this.record);
        this.CalendarSelectWidget.onRecordUpdate(this.record);
    },
    
    setTabHeight: function() {
        var eventTab = this.items.first().items.first();
        var centerPanel = eventTab.items.first();
        var tabPanel = centerPanel.items.last();
        tabPanel.setHeight(centerPanel.getEl().getBottom() - tabPanel.getEl().getTop());
    },
    
    validateDtEnd: function() {
        var dtStart = this.getForm().findField('dtstart').getValue();
        
        var dtEndField = this.getForm().findField('dtend');
        var dtEnd = dtEndField.getValue();
        
        if (! Ext.isDate(dtEnd)) {
            dtEndField.markInvalid(this.app.i18n._('End date is not valid'));
            return false;
        } else if (Ext.isDate(dtStart) && dtEnd.getTime() - dtStart.getTime() <= 0) {
            dtEndField.markInvalid(this.app.i18n._('End date must be after start date'));
            return false;
        } else {
            dtEndField.clearInvalid();
            return true;
        }
    },
    
    validateDtStart: function() {
        var dtStartField = this.getForm().findField('dtstart');
        var dtStart = dtStartField.getValue();
        
        if (! Ext.isDate(dtStart)) {
            dtStartField.markInvalid(this.app.i18n._('Start date is not valid'));
            return false;
        } else {
            dtStartField.clearInvalid();
            return true;
        }
        
    }
});

/**
 * Opens a new event edit dialog window
 * 
 * @return {Ext.ux.Window}
 */
Tine.Calendar.EventEditDialog.openWindow = function (config) {
    // record is JSON encoded here...
    var id = config.recordId ? config.recordId : 0;
    var window = Tine.WindowFactory.getWindow({
        width: 800,
        height: 470,
        name: Tine.Calendar.EventEditDialog.prototype.windowNamePrefix + id,
        contentPanelConstructor: 'Tine.Calendar.EventEditDialog',
        contentPanelConstructorConfig: config
    });
    return window;
};

// file: /home/lkneschke/temp/tine20build/temp/tine20/Calendar/js/AttendeeGridPanel.js
/*
 * Tine 2.0
 * 
 * @package     Calendar
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Cornelius Weiss <c.weiss@metaways.de>
 * @copyright   Copyright (c) 2009 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: AttendeeGridPanel.js 17349 2010-11-25 10:18:01Z c.weiss@metaways.de $
 *
 */
 
Ext.ns('Tine.Calendar');

/**
 * @namespace   Tine.Calendar
 * @class       Tine.Calendar.AttendeeGridPanel
 * @extends     Ext.grid.EditorGridPanel
 * @author      Cornelius Weiss <c.weiss@metaways.de>
 * @version     $Id: AttendeeGridPanel.js 17349 2010-11-25 10:18:01Z c.weiss@metaways.de $
 */
Tine.Calendar.AttendeeGridPanel = Ext.extend(Ext.grid.EditorGridPanel, {
    autoExpandColumn: 'user_id',
    clicksToEdit: 1,
    enableHdMenu: false,
    
    /**
     * @cfg {Boolean} showNamesOnly
     * true to only show types and names in the list
     */
    showNamesOnly: false,
    
    /**
     * The record currently being edited
     * 
     * @type Tine.Calendar.Model.Event
     * @property record
     */
    record: null,
    
    /**
     * id of current account
     * 
     * @type Number
     * @property currentAccountId
     */
    currentAccountId: null,
    
    /**
     * ctx menu
     * 
     * @type Ext.menu.Menu
     * @property ctxMenu
     */
    ctxMenu: null,
    
    /**
     * store to hold all attendee
     * 
     * @type Ext.data.Store
     * @property attendeeStore
     */
    attendeeStore: null,
    
    stateful: true,
    stateId: 'cal-attendeegridpanel',
    //stateEvents: ['sortchange', ],
    applyState: Ext.emptyFn,
    //applyState: function(state) {
    //    console.log(state);
    //},
    
    initComponent: function() {
        this.app = this.app ? this.app : Tine.Tinebase.appMgr.get('Calendar');
        
        this.currentAccountId = Tine.Tinebase.registry.get('currentAccount').accountId;
        
        this.title = this.hasOwnProperty('title') ? this.title : this.app.i18n._('Attendee');
        this.plugins = this.plugins || [];
        if (! this.showNamesOnly) {
            this.plugins.push(new Ext.ux.grid.GridViewMenuPlugin({}));
        }
        
        this.store = new Ext.data.SimpleStore({
            fields: Tine.Calendar.Model.Attender.getFieldDefinitions(),
            sortInfo: {field: 'user_id', direction: 'ASC'}
        });
        
        this.on('beforeedit', this.onBeforeAttenderEdit, this);
        this.on('afteredit', this.onAfterAttenderEdit, this);
        
        this.initColumns();
        
        Tine.Calendar.AttendeeGridPanel.superclass.initComponent.call(this);
    },
    
    initColumns: function() {
        this.columns = [{
            id: 'role',
            dataIndex: 'role',
            width: 70,
            sortable: true,
            hidden: this.showNamesOnly || true,
            header: this.app.i18n._('Role'),
            renderer: this.renderAttenderRole.createDelegate(this)
        },/* {
            id: 'quantity',
            dataIndex: 'quantity',
            width: 40,
            sortable: true,
            hidden: this.showNamesOnly || true,
            header: '&#160;',
            tooltip: this.app.i18n._('Quantity'),
            renderer: this.renderAttenderQuantity.createDelegate(this)
        },*/ {
            id: 'displaycontainer_id',
            dataIndex: 'displaycontainer_id',
            width: 200,
            sortable: false,
            hidden: this.showNamesOnly || true,
            header: Tine.Tinebase.translation._hidden('Saved in'),
            tooltip: this.app.i18n._('This is the calendar where the attender has saved this event in'),
            renderer: this.renderAttenderDispContainer.createDelegate(this),
            // disable for the moment, as updating calendarSelectWidget is not working in both directions
            editor2: new Tine.widgets.container.selectionComboBox({
                blurOnSelect: true,
                selectOnFocus: true,
                appName: 'Calendar',
                //startNode: 'personalOf', -> rework to startPath!
                getValue: function() {
                    if (this.selectedContainer) {
                        // NOTE: the store checks if data changed. If we don't overwrite to string, 
                        //  the check only sees [Object Object] wich of course never changes...
                        var container_id = this.selectedContainer.id;
                        this.selectedContainer.toString = function() {return container_id;};
                    }
                    return this.selectedContainer;
                },
                listeners: {
                    scope: this,
                    select: function(field, newValue) {
                        // the field is already blured, due to the extra chooser window. We need to change the value per hand
                        var selection = this.getSelectionModel().getSelectedCell();
                        if (selection) {
                            var row = selection[0];
                            this.store.getAt(row).set('displaycontainer_id', newValue);
                        }
                    }
                }
            })
        }, {
            id: 'user_type',
            dataIndex: 'user_type',
            width: 20,
            sortable: true,
            resizable: false,
            header: '&#160;',
            tooltip: this.app.i18n._('Type'),
            renderer: this.renderAttenderType.createDelegate(this),
            editor: new Ext.form.ComboBox({
                blurOnSelect  : true,
                expandOnFocus : true,
                mode          : 'local',
                store         : [
                    ['user',     this.app.i18n._('User')   ],
                    ['group',    this.app.i18n._('Group')  ],
                    ['resource', this.app.i18n._('Resource')]
                ]
            })
        }, {
            id: 'user_id',
            dataIndex: 'user_id',
            width: 300,
            sortable: true,
            header: this.app.i18n._('Name'),
            renderer: this.renderAttenderName.createDelegate(this),
            editor: true
        }, {
            id: 'status',
            dataIndex: 'status',
            width: 100,
            sortable: true,
            header: this.app.i18n._('Status'),
            hidden: this.showNamesOnly,
            renderer: this.renderAttenderStatus.createDelegate(this),
            editor: new Ext.form.ComboBox({
                blurOnSelect  : true,
                expandOnFocus : true,
                mode          : 'local',
                displayField  : 'status_name',
                valueField    : 'id',
                store         : Tine.Calendar.Model.Attender.getAttendeeStatusStore()
            })
        }];
    },
    
    onAfterAttenderEdit: function(o) {
        switch (o.field) {
            case 'user_id' :
                // detect duplicate entry
                var isDuplicate = false;
                this.store.each(function(attender) {
                    if (o.record.getUserId() == attender.getUserId()
                            && o.record.get('user_type') == attender.get('user_type')
                            && o.record != attender) {
                        var row = this.getView().getRow(this.store.indexOf(attender));
                        Ext.fly(row).highlight();
                        isDuplicate = true;
                        return false;
                    }
                }, this);
                
                if (isDuplicate) {
                    o.record.reject();
                    this.startEditing(o.row, o.column);
                } else if (o.value) {
                    var newAttender = new Tine.Calendar.Model.Attender(Tine.Calendar.Model.Attender.getDefaultData(), 'new-' + Ext.id() );
                    this.store.add([newAttender]);
                    this.startEditing(o.row +1, o.column);
                }
                break;
                
            case 'user_type' :
                this.startEditing(o.row, o.column +1);
                break;
            
            case 'container_id':
                // check if displaycontainer of owner got changed
                if (o.record == this.eventOriginator) {
                    this.record.set('container_id', '');
                    this.record.set('container_id', o.record.get('displaycontainer_id'));
                }
                break;
        }
        
    },
    
    onBeforeAttenderEdit: function(o) {
        if (o.field == 'status') {
            // allow status setting if current user has editGrant to displaycontainer
            var dispContainer = o.record.get('displaycontainer_id');
            o.cancel = ! (dispContainer && dispContainer.account_grants && dispContainer.account_grants.editGrant);
            return;
        }
        
        if (o.field == 'displaycontainer_id') {
            if (! o.value || ! o.value.account_grants || ! o.value.account_grants.deleteGrant) {
                o.cancel = true;
            }
            return;
        }
        
        // for all other fields user need editGrant
        if (! this.record.get('editGrant')) {
            o.cancel = true;
            return;
        }
        
        // don't allow to set anything besides quantity for already set attendee
        if (o.record.get('user_id')) {
            o.cancel = true;
            if (o.field == 'quantity' && o.record.get('user_type') == 'resource') {
                o.cancel = false;
            }
            return;
        }
        
        if (o.field == 'user_id') {
            // switch editor
            var colModel = o.grid.getColumnModel();
            switch(o.record.get('user_type')) {
                case 'user' :
                colModel.config[o.column].setEditor(new Tine.Addressbook.SearchCombo({
                    blurOnSelect: true,
                    selectOnFocus: true,
                    renderAttenderName: this.renderAttenderName,
                    getValue: function() {
                        return this.selectedRecord ? this.selectedRecord.data : null;
                    }
                }));
                break;
                
                case 'group':
                colModel.config[o.column].setEditor(new Tine.Tinebase.widgets.form.RecordPickerComboBox({
                    blurOnSelect: true,
                    recordClass: Tine.Addressbook.Model.List,
                    getValue: function() {
                        return this.selectedRecord ? this.selectedRecord.data : null;
                    }
                }));
                break;
                
                case 'resource':
                colModel.config[o.column].setEditor(new Tine.Tinebase.widgets.form.RecordPickerComboBox({
                    blurOnSelect: true,
                    recordClass: Tine.Calendar.Model.Resource,
                    getValue: function() {
                        return this.selectedRecord ? this.selectedRecord.data : null;
                    }
                }));
                break;
            }
            colModel.config[o.column].editor.selectedRecord = null;
        }
    },
    
    // NOTE: Ext docu seems to be wrong on arguments here
    onContextMenu: function(e, target) {
        e.preventDefault();
        var row = this.getView().findRowIndex(target);
        var attender = this.store.getAt(row);
        if (attender) {
            // don't delete 'add' row
            var attender = this.store.getAt(row);
            if (! attender.get('user_id')) {
                return;
            }
                        
            this.ctxMenu = new Ext.menu.Menu({
                items: [{
                    text: this.app.i18n._('Remove Attender'),
                    iconCls: 'action_delete',
                    scope: this,
                    disabled: !this.record.get('editGrant'),
                    handler: function() {
                        this.store.removeAt(row);
                    }
                    
                }]
            });
            this.ctxMenu.showAt(e.getXY());
        }
    },
    
    /**
     * loads this panel with data from given record
     * called by edit dialog when record is loaded
     * 
     * @param {Tine.Calendar.Model.Event} record
     */
    onRecordLoad: function(record) {
        this.record = record;
        this.store.removeAll();
        var attendee = record.get('attendee');
        Ext.each(attendee, function(attender) {
            var record = new Tine.Calendar.Model.Attender(attender, attender.id);
            this.store.addSorted(record);
            
            if (attender.displaycontainer_id  && this.record.get('container_id') && attender.displaycontainer_id.id == this.record.get('container_id').id) {
                this.eventOriginator = record;
            }
        }, this);
        
        if (record.get('editGrant')) {
            this.store.add([new Tine.Calendar.Model.Attender(Tine.Calendar.Model.Attender.getDefaultData(), 'new-' + Ext.id() )]);
        }
    },
    
    /**
     * Updates given record with data from this panel
     * called by edit dialog to get data
     * 
     * @param {Tine.Calendar.Model.Event} record
     */
    onRecordUpdate: function(record) {
        this.stopEditing(false);
        
        var attendee = [];
        this.store.each(function(attender) {
            var user_id = attender.get('user_id');
            if (user_id/* && user_id.id*/) {
                if (typeof user_id.get == 'function') {
                    attender.data.user_id = user_id.data;
                }
                
               attendee.push(attender.data);
            }
        }, this);
        
        record.set('attendee', '');
        record.set('attendee', attendee);
    },
    
    onKeyDown: function(e) {
        switch(e.getKey()) {
            
            case e.DELETE: 
                if (this.record.get('editGrant')) {
                    var selection = this.getSelectionModel().getSelectedCell();
                    
                    if (selection) {
                        var row = selection[0];
                        
                        // don't delete 'add' row
                        var attender = this.store.getAt(row);
                        if (! attender.get('user_id')) {
                            return;
                        }

                        this.store.removeAt(row);
                    }
                }
                break;
        }
    },
    
    renderAttenderName: function(name, metaData, record) {
        if (name) {
            var type = record ? record.get('user_type') : 'user';
            return this['renderAttender' + Ext.util.Format.capitalize(type) + 'Name'].apply(this, arguments);
        }
        
        // add new user:
        if (arguments[1]) {
            arguments[1].css = 'x-form-empty-field';
            return this.app.i18n._('Click here to invite another attender...');
        }
    },
    
    renderAttenderUserName: function(name) {
        if (typeof name.get == 'function' && name.get('n_fileas')) {
            return Ext.util.Format.htmlEncode(name.get('n_fileas'));
        }
        if (name.n_fileas) {
            return Ext.util.Format.htmlEncode(name.n_fileas);
        }
        if (name.accountDisplayName) {
            return Ext.util.Format.htmlEncode(name.accountDisplayName);
        }
        if (Ext.isString(name)) {
            return Ext.util.Format.htmlEncode(name);
        }
        // NOTE: this fn gets also called from other scopes
        return Tine.Tinebase.appMgr.get('Calendar').i18n._('No Information');
    },
    
    renderAttenderGroupmemberName: function(name) {
        var name = Tine.Calendar.AttendeeGridPanel.prototype.renderAttenderUserName.apply(this, arguments);
        return name + ' ' + Tine.Tinebase.appMgr.get('Calendar').i18n._('(as a group member)');
    },
    
    renderAttenderGroupName: function(name) {
        if (typeof name.getTitle == 'function') {
            return Ext.util.Format.htmlEncode(name.getTitle());
        }
        if (name.name) {
            return Ext.util.Format.htmlEncode(name.name);
        }
        if (Ext.isString(name)) {
            return Ext.util.Format.htmlEncode(name);
        }
        return Tine.Tinebase.appMgr.get('Calendar').i18n._('No Information');
    },
    
    renderAttenderResourceName: function(name) {
        if (typeof name.getTitle == 'function') {
            return Ext.util.Format.htmlEncode(name.getTitle());
        }
        if (name.name) {
            return Ext.util.Format.htmlEncode(name.name);
        }
        if (Ext.isString(name)) {
            return Ext.util.Format.htmlEncode(name);
        }
        return Tine.Tinebase.appMgr.get('Calendar').i18n._('No Information');
    },
    
    
    renderAttenderDispContainer: function(displaycontainer_id, metadata, attender) {
        metadata.attr = 'style = "overflow: none;"';
        
        if (displaycontainer_id) {
            if (displaycontainer_id.name) {
                return Ext.util.Format.htmlEncode(displaycontainer_id.name).replace(/ /g,"&nbsp;");
            } else {
                metadata.css = 'x-form-empty-field';
                return this.app.i18n._('No Information');
            }
        }
    },
    
    renderAttenderQuantity: function(quantity, metadata, attender) {
        return quantity > 1 ? quantity : '';
    },
    
    renderAttenderRole: function(role) {
        switch (role) {
            case 'REQ':
                return this.app.i18n._('Required');
                break;
            case 'OPT':
                return this.app.i18n._('Optional');
                break;
            default:
                return Ext.util.Format.htmlEncode(this.app.i18n._hidden(role));
                break;
        }
    },
    
    renderAttenderStatus: function(status, metadata, attender) {
        if (! attender.get('user_id')) {
            return '';
        }
        
        if (attender.get('displaycontainer_id')) {
            metadata.attr = 'style = "cursor:pointer;"';
        } else {
            metadata.css = 'x-form-empty-field';
        }
        
        var il8n = Tine.Tinebase.appMgr.get('Calendar').i18n;
        switch (status) {
            case 'NEEDS-ACTION':
                return il8n._('No response');
                break;
            case 'ACCEPTED':
                return il8n._('Accepted');
                break;
            case 'DECLINED':
                return il8n._('Declined');
                break;
            case 'TENTATIVE':
                return il8n._('Tentative');
                break;
            default:
                return Ext.util.Format.htmlEncode(il8n._hidden(status));
                break;
        }
    },
    
    renderAttenderType: function(type, metadata, attender) {
        switch (type) {
            case 'user':
                metadata.css = 'renderer_accountUserIcon';
                break;
            case 'group':
                metadata.css = 'renderer_accountGroupIcon';
                break;
            default:
                metadata.css = 'cal-attendee-type-' + type;
                break;
        }
        return '';
    }
});
// file: /home/lkneschke/temp/tine20build/temp/tine20/Calendar/js/AttendeeFilterModel.js
/*
 * Tine 2.0
 * 
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Cornelius Weiss <c.weiss@metaways.de>
 * @copyright   Copyright (c) 2010 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: AttendeeFilterModel.js 17485 2010-12-01 10:57:05Z p.schuele@metaways.de $
 */
Ext.ns('Tine.Calendar');

/**
 * @namespace   Tine.Calendar
 * @class       Tine.Calendar.AttendeeFilterModel
 * @extends     Tine.widgets.grid.FilterModel
 * 
 * @author      Cornelius Weiss <c.weiss@metaways.de>
 * @version     $Id: AttendeeFilterModel.js 17485 2010-12-01 10:57:05Z p.schuele@metaways.de $
 */
Tine.Calendar.AttendeeFilterModel = Ext.extend(Tine.widgets.grid.FilterModel, {
    /**
     * @property Tine.Tinebase.Application app
     */
    app: null,
    
    field: 'attender',
    defaultOperator: 'equals',
    
    /**
     * @private
     */
    initComponent: function() {
        Tine.Calendar.AttendeeFilterModel.superclass.initComponent.call(this);
        
        this.app = Tine.Tinebase.appMgr.get('Calendar');
        
        this.operators = ['in'/*, 'notin'*/];
        this.label = this.app.i18n._('Attendee');
        
        
        this.defaultValue = Ext.apply(Tine.Calendar.Model.Attender.getDefaultData(), {
            user_id: Tine.Tinebase.registry.get('currentAccount')
        });
    },
    
    /**
     * value renderer
     * 
     * @param {Ext.data.Record} filter line
     * @param {Ext.Element} element to render to 
     */
    valueRenderer: function(filter, el) {
        var value = new Tine.Calendar.AttendeeFilterModelValueField({
            app: this.app,
            filter: filter,
            width: 200,
            id: 'tw-ftb-frow-valuefield-' + filter.id,
            value: filter.data.value ? filter.data.value : this.defaultValue,
            renderTo: el
        });
        value.on('select', this.onFiltertrigger, this);
        return value;
    }
});

Tine.widgets.grid.FilterToolbar.FILTERS['calendar.attendee'] = Tine.Calendar.AttendeeFilterModel;

/**
 * @namespace   Tine.Calendar
 * @class       Tine.Calendar.AttendeeFilterModelValueField
 * @extends     Ext.ux.form.LayerCombo
 * 
 * @author      Cornelius Weiss <c.weiss@metaways.de>
 * @version     $Id: AttendeeFilterModel.js 17485 2010-12-01 10:57:05Z p.schuele@metaways.de $
 */
Tine.Calendar.AttendeeFilterModelValueField = Ext.extend(Ext.ux.form.LayerCombo, {
    hideButtons: false,
    layerAlign : 'tr-br?',
    minLayerWidth: 400,
    layerHeight: 300,
    
    lazyInit: true,
    
    formConfig: {
        labelAlign: 'left',
        labelWidth: 30
    },
    
    initComponent: function() {
        this.fakeRecord = new Tine.Calendar.Model.Event(Tine.Calendar.Model.Event.getDefaultData());
        
        this.on('beforecollapse', this.onBeforeCollapse, this);
        
        this.supr().initComponent.call(this);
    },
    
    getFormValue: function() {
        this.attendeeGridPanel.onRecordUpdate(this.fakeRecord);
        return this.fakeRecord.get('attendee');
    },
    
    getItems: function() {
        
        this.attendeeGridPanel = new Tine.Calendar.AttendeeGridPanel({
            title: this.app.i18n._('Select Attendee'),
            height: this.layerHeight || 'auto',
            showNamesOnly: true
        });
        var items = [this.attendeeGridPanel];
        
        return items;
    },
    
    /**
     * cancel collapse if ctx menu is shown
     */
    onBeforeCollapse: function() {
        
        return (!this.attendeeGridPanel.ctxMenu || this.attendeeGridPanel.ctxMenu.hidden) &&
                !this.attendeeGridPanel.editing;
    },
    
    /**
     * @param {String} value
     * @return {Ext.form.Field} this
     */
    setValue: function(value) {
        value = Ext.isArray(value) ? value : [value];
        this.fakeRecord.set('attendee', '');
        this.fakeRecord.set('attendee', value);
        this.currentValue = [];
        
        var attendeeStore = Tine.Calendar.Model.Attender.getAttendeeStore(value);
        
        var a = [];
        attendeeStore.each(function(attender) {
            this.currentValue.push(attender.data);
            var name = Tine.Calendar.AttendeeGridPanel.prototype.renderAttenderName.call(Tine.Calendar.AttendeeGridPanel.prototype, attender.get('user_id'), false, attender);
            //var status = Tine.Calendar.AttendeeGridPanel.prototype.renderAttenderStatus.call(Tine.Calendar.AttendeeGridPanel.prototype, attender.get('status'), {}, attender);
            a.push(name/* + ' (' + status + ')'*/);
        }, this);
        
        this.setRawValue(a.join(', '));
        return this;
        
    },
    
    /**
     * sets values to innerForm
     */
    setFormValue: function(value) {
        this.attendeeGridPanel.onRecordLoad(this.fakeRecord);
    }
});

// file: /home/lkneschke/temp/tine20build/temp/tine20/Calendar/js/AttendeeStatusFilterModel.js
/*
 * Tine 2.0
 * 
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Cornelius Weiss <c.weiss@metaways.de>
 * @copyright   Copyright (c) 2010 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: AttendeeStatusFilterModel.js 13501 2010-03-19 11:06:19Z c.weiss@metaways.de $
 */
Ext.ns('Tine.Calendar');

/**
 * @namespace   Tine.Calendar
 * @class       Tine.Calendar.AttendeeStatusFilterModel
 * @extends     Tine.widgets.grid.FilterModel
 * 
 * @author      Cornelius Weiss <c.weiss@metaways.de>
 * @version     $Id: AttendeeStatusFilterModel.js 13501 2010-03-19 11:06:19Z c.weiss@metaways.de $
 */
Tine.Calendar.AttendeeStatusFilterModel = Ext.extend(Tine.widgets.grid.FilterModel, {
    /**
     * @property Tine.Tinebase.Application app
     */
    app: null,
    
    field: 'attender_status',
    defaultOperator: 'notin',
    
    /**
     * @private
     */
    initComponent: function() {
        this.operators = ['in', 'notin'];
        this.label = _('Attendee Status');
        
        this.defaultValue = ['DECLINED'];
        
        this.supr().initComponent.call(this);
    },
    
    /**
     * value renderer
     * 
     * @param {Ext.data.Record} filter line
     * @param {Ext.Element} element to render to 
     */
    valueRenderer: function(filter, el) {
        var value = new Tine.Calendar.AttendeeStatusFilterModelValueField({
            app: this.app,
            filter: filter,
            width: 200,
            id: 'tw-ftb-frow-valuefield-' + filter.id,
            value: filter.data.value ? filter.data.value : this.defaultValue,
            renderTo: el
        });
        value.on('specialkey', function(field, e){
             if(e.getKey() == e.ENTER){
                 this.onFiltertrigger();
             }
        }, this);
        value.on('select', this.onFiltertrigger, this);
        
        return value;
    }
});

Tine.widgets.grid.FilterToolbar.FILTERS['calendar.attendeestatus'] = Tine.Calendar.AttendeeStatusFilterModel;

/**
 * @namespace   Tine.Calendar
 * @class       Tine.Calendar.AttendeeStatusFilterModelValueField
 * @extends     Ext.ux.form.LayerCombo
 * 
 * @author      Cornelius Weiss <c.weiss@metaways.de>
 * @version     $Id: AttendeeStatusFilterModel.js 13501 2010-03-19 11:06:19Z c.weiss@metaways.de $
 */
Tine.Calendar.AttendeeStatusFilterModelValueField = Ext.extend(Ext.ux.form.LayerCombo, {
    hideButtons: false,
    formConfig: {
        labelAlign: 'left',
        labelWidth: 30
    },
    
    getFormValue: function() {
        var ids = [];
        var statusStore = Tine.Calendar.Model.Attender.getAttendeeStatusStore();
        
        var formValues = this.getInnerForm().getForm().getValues();
        for (var id in formValues) {
            if (formValues[id] === 'on' && statusStore.getById(id)) {
                ids.push(id);
            }
        }
        
        return ids;
    },
    
    getItems: function() {
        var items = [];
        
        Tine.Calendar.Model.Attender.getAttendeeStatusStore().each(function(status) {
            items.push({
                xtype: 'checkbox',
                boxLabel: status.get('status_name'),
                icon: status.get('status_icon'),
                name: status.get('id')
            });
        }, this);
        
        return items;
    },
    
    /**
     * @param {String} value
     * @return {Ext.form.Field} this
     */
    setValue: function(value) {
        value = Ext.isArray(value) ? value : [value];
        
        var statusStore = Tine.Calendar.Model.Attender.getAttendeeStatusStore();
        var statusText = [];
        this.currentValue = [];
        
        Tine.Calendar.Model.Attender.getAttendeeStatusStore().each(function(status) {
            var id = status.get('id');
            var name = status.get('status_name');
            if (value.indexOf(id) >= 0) {
                statusText.push(name);
                this.currentValue.push(id);
            }
        }, this);
        
        this.setRawValue(statusText.join(', '));
        
        return this;
    },
    
    /**
     * sets values to innerForm
     */
    setFormValue: function(value) {
        this.getInnerForm().getForm().items.each(function(item) {
            item.setValue(value.indexOf(item.name) >= 0 ? 'on' : 'off');
        }, this);
    }
});

// file: /home/lkneschke/temp/tine20build/temp/tine20/Calendar/js/CalendarSelectTreePanel.js
/* 
 * Tine 2.0
 * 
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Cornelius Weiss <c.weiss@metaways.de>
 * @copyright   Copyright (c) 2009 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: CalendarSelectTreePanel.js 18996 2011-02-01 17:31:59Z c.weiss@metaways.de $
 */

Ext.ns('Tine.Calendar');

/**
 * @namespace   Tine.Calendar
 * @class       Tine.Calendar.FilterPanel
 * @extends     Tine.widgets.persistentfilter.PickerPanel
 * 
 * <p>Calendar Favorites Panel</p>
 * 
 * @author      Cornelius Weiss <c.weiss@metaways.de>
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @version     $Id: CalendarSelectTreePanel.js 18996 2011-02-01 17:31:59Z c.weiss@metaways.de $
 * 
 * @param       {Object} config
 * @constructor
 * Create a new Tine.Calendar.FilterPanel
 */
Tine.Calendar.FilterPanel = Ext.extend(Tine.widgets.persistentfilter.PickerPanel, {
    filter: [{field: 'model', operator: 'equals', value: 'Calendar_Model_EventFilter'}],
    
    /**
     * returns filter toolbar of mainscreen center panel of app this picker panel belongs to
     */
    getFilterToolbar: function() {
        return this.app.getMainScreen().getCenterPanel().filterToolbar;
    },
    
    storeOnBeforeload: function(store, options) {
        options.params.filter = this.store.getById(this.getSelectionModel().getSelectedNode().id).get('filters');
        
        var cp = Tine.Tinebase.appMgr.get('Calendar').getMainScreen().getCenterPanel();
        var period = cp.getCalendarPanel(cp.activeView).getView().getPeriod();
        
        // remove all existing period filters
        Ext.each(options.params.filter, function(filter) {
            if (filter.field === 'period') {
                options.params.filter.remove(filter);
                return false;
            }
        }, this);
        
        options.params.filter.push({field: 'period', operator: 'within', value: period});
        
        store.un('beforeload', this.storeOnBeforeload, this);
    }
});

/**
 * @namespace Tine.Calendar
 * @class     Tine.Calendar.CalendarSelectTreePanel
 * @extends   Tine.widgets.container.TreePanel
 * 
 * Main Calendar Select Panel
 * 
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Cornelius Weiss <c.weiss@metaways.de>
 * @copyright   Copyright (c) 2009 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: CalendarSelectTreePanel.js 18996 2011-02-01 17:31:59Z c.weiss@metaways.de $
 */
Tine.Calendar.CalendarSelectTreePanel = Ext.extend(Tine.widgets.container.TreePanel, {

    recordClass: Tine.Calendar.Model.Event,
    ddGroup: 'cal-event',
    filterMode: 'filterToolbar',
    useContainerColor: true,
    
    initComponent: function() {
        this.filterPlugin = new Tine.widgets.tree.FilterPlugin({
            treePanel: this,
            /**
             * overwritten to deal with calendars special filter approach
             * 
             * @return {Ext.Panel}
             */
            getGridPanel: function() {
                return Tine.Tinebase.appMgr.get('Calendar').getMainScreen().getCenterPanel();
            }
        });
        
        this.on('beforeclick', this.onBeforeClick, this);
        this.on('containercolorset', function() {
            Tine.Tinebase.appMgr.get('Calendar').getMainScreen().getCenterPanel().refresh(true);
        });
        
        this.supr().initComponent.call(this);
    },
    
    /**
     * dissalow loading of all and otherUsers node
     * 
     * @param {Ext.tree.TreeNode} node
     * @param {Ext.EventObject} e
     * @return {Boolean}
     */
    onBeforeClick: function(node, e) {
        if (node.attributes.path.match(/^\/$|^\/personal$/)) {
            this.onClick(node, e);
            return false;
        }
    },
    
    /**
     * adopt attr
     * 
     * @param {Object} attr
     */
    onBeforeCreateNode: function(attr) {
        this.supr().onBeforeCreateNode.apply(this, arguments);
        
        if (attr.container) {
            attr.container.capabilites_private = true;
        }
    },
    
    /**
     * called when events are droped on a calendar node
     * 
     * NOTE: atm. event panels only allow d&d for single events
     * 
     * @private
     * @param  {Ext.Event} dropEvent
     * @return {Boolean}
     */
    onBeforeNodeDrop: function(dropEvent) {
        var containerData = dropEvent.target.attributes,
            selection = dropEvent.data.selections,
            mainScreenPanel = Tine.Tinebase.appMgr.get('Calendar').getMainScreen().getCenterPanel(),
            calPanel = mainScreenPanel.getCalendarPanel(mainScreenPanel.activeView),
            abort = false;
        
        // @todo move this to dragOver
        if (! containerData.account_grants.addGrant) {
            abort = true;
        }
        
        Ext.each(selection, function(event) {
            if (Tine.Tinebase.container.pathIsMyPersonalContainer(event.get('container_id').path)) {
                // origin container will only be moved for personal events with their origin in
                // a personal container of the current user
                event.set('container_id', containerData.id);
                calPanel.onUpdateEventAction(event);
                
                dropEvent.cancel = false;
                dropEvent.dropNode = [];
            } else {
                // @todo move displaycal if curruser is attender
                abort = true;
            }
        }, this);
        
        if (abort) {
            console.log('abort')
            return false;
        }
        
//        return true;
    },
    
    /**
     * returns a calendar to take for an add event action
     * 
     * @return {Tine.Model.Container}
     * 
     * TODO generalize this
     */
    getAddCalendar: function() {
        var sm = this.getSelectionModel();
        var selections =  typeof sm.getSelectedNodes == 'function' ? sm.getSelectedNodes() : [sm.getSelectedNode()];
            
        var addCalendar = Tine.Calendar.registry.get('defaultCalendar');
        
        //active calendar
        var activeNode = typeof sm.getActiveNode == 'function' ? sm.getActiveNode() : selections[0];
        if (activeNode && this.hasGrant(activeNode, 'addGrant')) {
            return activeNode.attributes.container;
        }
        
        //first container with add grant
        Ext.each(selections, function(node){
            if (node && this.hasGrant(node, 'addGrant')) {
                addCalendar = node.attributes.container;
                return false;
            }
        }, this);
        
        return addCalendar
    }
});

// file: /home/lkneschke/temp/tine20build/temp/tine20/Calendar/js/CalendarSelectWidget.js
/*
 * Tine 2.0
 * 
 * @package     Calendar
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Cornelius Weiss <c.weiss@metaways.de>
 * @copyright   Copyright (c) 2009 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: AttendeeGridPanel.js 8754 2009-06-18 08:50:02Z c.weiss@metaways.de $
 *
 */
 
Ext.ns('Tine.Calendar');

/**
 * @namespace   Tine.Calendar
 * @class       Tine.Calendar.CalendarSelectWidget
 * @extends     Ext.util.Observable
 * Calendar Selector Widget
 * @author      Cornelius Weiss <c.weiss@metaways.de>
 * @version     $Id: DaysView.js 9771 2009-08-05 17:50:15Z c.weiss@metaways.de $
 * @constructor
 * @param {Tine.Calendar.EventEditDialog} EventEditDialog
 */
Tine.Calendar.CalendarSelectWidget = function(EventEditDialog) {
    this.EventEditDialog = EventEditDialog;
    
    Tine.Calendar.CalendarSelectWidget.superclass.constructor.call(this);
};

Ext.extend(Tine.Calendar.CalendarSelectWidget, Ext.Panel, {
    layout: 'fit',

    style: 'padding-right: 5px;',
    /**
     * Calmap record definition
     * 
     * @type Function 
     * @property calMapRecord
     */
    calMapRecord: Ext.data.Record.create([
        {name: 'attender'}, {name: 'calendar'}, {name: 'user'}, {name: 'userAccountId'}, {name: 'calendarName'}, {name: 'userName'}, {name: 'editGrant'}, {name: 'isOriginal'}
    ]),
    
    /**
     * Current calendar map
     * 
     * @type Ext.data.Record 
     * @property currentCalMap
     */
    currentCalMap: null,
    
    /**
     * @cfg {String} requiredGrant
     * grant which is required to select leaf node(s)
     */
    requiredGrant: 'editGrant',
    
    /**
     * edit dialog
     * 
     * @type Tine.Calendar.EventEditDialog
     * @property EventEditDialog
     */
    EventEditDialog: null,
    
    /**
     * Calendar select combo box
     * 
     * @type Tine.widgets.container.selectionComboBox
     * @property calCombo
     */
    calCombo: null,
    
    /**
     * returns physical/originator container
     * 
     * @return {Tine.Tinebase.Model.Container}
     */
    getPhysContainer: function() {
        return this.record.get('container_id')
    },
    
    /**
     *  builds attendee -> calendar map
     */
    buildCalMapStore: function(needEditGrant) {
        
        this.calMapStore.removeAll();
        var physCal = this.record.get('container_id');
        
        this.EventEditDialog.attendeeStore.each(function(attender){
            var calendar = attender.get('displaycontainer_id');
            var user = attender.get('user_id');
            var userAccountId = attender.getUserAccountId();
            var calendarName = this.EventEditDialog.attendeeGridPanel.renderAttenderDispContainer(calendar, {});
            var userName = this.EventEditDialog.attendeeGridPanel.renderAttenderName(user, {});
            
            // check if attender is a user which is/has an useraccount
            if (userAccountId && userName) {
                // check if container is resoved
                if (calendar && calendar.name) {
                    // check that calendar is not the physCal
                    if (! (physCal && physCal.name) || physCal.id != calendar.id) {
                        if (! needEditGrant || calendar.account_grants.editGrant) {
                            this.calMapStore.add([new this.calMapRecord({
                                attender: attender,
                                calendar: calendar, 
                                user: user,
                                userAccountId: userAccountId,
                                calendarName: calendarName,
                                userName: String.format('for {0}', userName),
                                editGrant: calendar.account_grants.editGrant,
                                isOriginal: false
                            })]);
                        }
                    }
                }
            }
        }, this);
        
        // finally add physCal if acceptable
        if (physCal && physCal.name && (! needEditGrant || physCal.account_grants.editGrant)) {
            this.calMapStore.add([new this.calMapRecord({
                calendar: physCal, 
                calendarName: physCal.name,
                userName: this.app.i18n._('Originally'),
                editGrant: physCal.account_grants.editGrant,
                isOriginal: true
            })]);
        }
    },
    
    initComponent: function() {
        this.app = Tine.Tinebase.appMgr.get('Calendar');
        this.recordClass = Tine.Calendar.Model.Event;
        
        this.currentAccountId = Tine.Tinebase.registry.get('currentAccount').accountId;
        
        this.calMapStore = new Ext.data.SimpleStore({
            fields: this.calMapRecord
        });
        
        this.initTpl();
        
        this.fakeCombo = new Ext.form.ComboBox({
            mode          : 'local',
            hideMode      : 'visibility',
            style         : {'position': 'absolute', 'top': '0px', 'right': '0px'},
            store         : this.calMapStore,
            tpl           : this.attendeeListTpl,
            onSelect      : this.onCalMapSelect.createDelegate(this)
        });
        
        this.calCombo = new Tine.widgets.container.selectionComboBox({
            hideLabel: true,
            containerName: this.app.i18n.n_hidden(this.recordClass.getMeta('containerName'), this.recordClass.getMeta('containersName'), 1),
            containersName: this.app.i18n._hidden(this.recordClass.getMeta('containersName')),
            appName: this.app.appName,
            requiredGrant: this.requiredGrant,
            hideTrigger2: false,
            trigger2Class: 'cal-invitation-trigger',
            onTrigger2Click: this.fakeCombo.onTriggerClick.createDelegate(this.fakeCombo),
            allowBlank: true,
            listeners: {
                scope: this,
                beforequery: this.onBeforeCalComboQuery,
                select: this.onCalComboSelect
            }
        });
        
        this.items = [
            this.calCombo,
            this.fakeCombo
        ];
        
        this.supr().initComponent.call(this);
    },
    
    initTpl: function() {
        this.attendeeListTpl = new Ext.XTemplate(
            '<tpl for=".">' +
                '<div class="cal-calselectwidget-fakelist-item x-combo-list-item">' +
                    '<div class="cal-calselectwidget-fakelist-calendar">{calendarName}</div>' +
                    '<div class="cal-calselectwidget-fakelist-account">{userName}</div>' +
                '</div>' +
            '</tpl>'
        );
    },
    
    onAttendeUpdate: function(store, updatedAttender) {
        var userAccountId = attender.getUserAccountId();
        
        // we are only interested in attenders wich are/have a user account
        if (userAccountId) {
            // mhh weired, somtimes a container comes instead of an attender...
            if (typeof updatedAttender.get('displaycontainer_id').get == 'function') {
                // check if currently displayed container changed
                if (updatedAttender.get('displaycontainer_id').get('account_grants').account_id == this.currentCalMap.get('userAccountId')) {
                    //console.log('currently displayed non original changed');
                    this.currentCalMap.set('calendar', '');
                    this.currentCalMap.set('calendar', updatedAttender.get('displaycontainer_id'));
                    //this.calCombo.setValue(updatedAttender.get('displaycontainer_id'));
                    this.calCombo.setRawValue(updatedAttender.get('displaycontainer_id').get('name'));
                } else if (userAccountId == this.currentAccountId && updatedAttender.get('user_type') == 'user' && this.currentCalMap.get('isOriginal')) {
                    //console.log('currently displayed original changed');
                    this.currentCalMap.set('calendar', '');
                    this.currentCalMap.set('calendar', updatedAttender.get('displaycontainer_id'));
                    //this.calCombo.setValue(updatedAttender.get('displaycontainer_id'));
                    this.calCombo.setRawValue(updatedAttender.get('displaycontainer_id').get('name'));
                }
            }
        }
    },
    
    onBeforeCalComboQuery: function() {
        if(! this.currentCalMap || this.currentCalMap.get('isOriginal')) {
            this.calCombo.startPath = '/';
        } else {
            this.calCombo.startPath = '/personal/' + this.currentCalMap.get('userAccountId');
        }
    },
    
    onCalComboSelect: function() {
        var container = this.calCombo.selectedContainer;
        container.toString = function() {return container.id};
        
        if (! this.currentCalMap || this.currentCalMap.get('isOriginal')) {
            this.record.set('container_id', container);
        } else {
            this.currentCalMap.get('attender').set('displaycontainer_id', container);
        }
    },
    
    onCalMapSelect: function(record, index) {
        if (record && typeof record.get == 'function') {
            this.calCombo.setValue(record.get('calendar'));
            this.calCombo.setTrigger2Text(String.format(record.get('userName')));
            
            this.currentCalMap = record;
            
            this.fakeCombo.collapse();
            this.fakeCombo.hide();
            
            this.fakeCombo.fireEvent('select', this.fakeCombo, record, index);
        }
    },
    
    /**
     * loads this widget with data from given record
     * called by edit dialog when record is loaded
     * 
     * @param {Tine.Calendar.Model.Event} record
     */
    onRecordLoad: function(record) {
        this.record = record;
        this.buildCalMapStore(true);
        
        if (this.calMapStore.getCount() == 0) {
            // no cal with edit grant ...
            if (this.record.phantom) {
                // call setValue to add 'choose other'...
                this.calCombo.setValue('');
            } else {
                this.buildCalMapStore(false);
                
                if (this.calMapStore.getCount() > 0) {
                    this.onCalMapSelect(this.calMapStore.getAt(0));
                }
                this.calCombo.setDisabled(true);
            }
            
            // if this is a persistent event make combo non selectable
        } else if (this.calMapStore.getCount() == 1) {
            this.onCalMapSelect(this.calMapStore.getAt(0));
            this.calCombo.setTrigger2Disabled(true);
        } else {
            var mine = this.calMapStore.find('userAccountId', this.currentAccountId);
            var phys = this.calMapStore.find('isOriginal', true);
            
            var take = mine > 0 ? mine : phys;
            this.onCalMapSelect(this.calMapStore.getAt(take));
        }
    },
    
    /**
     * Updates given record with data from this widget
     * called by edit dialog to get data
     * 
     * @param {Tine.Calendar.Model.Event} record
     */
    onRecordUpdate: function(record) {
        // nothing do do here!
        //console.log('todo: onRecordUpdate');
    },
    
    onResize: function(width, height) {
        this.supr().onResize.apply(this, arguments);
        this.fakeCombo.setWidth(width);
    }
});

// file: /home/lkneschke/temp/tine20build/temp/tine20/Calendar/js/ColorManager.js
/* 
 * Tine 2.0
 * 
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Cornelius Weiss <c.weiss@metaways.de>
 * @copyright   Copyright (c) 2009 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: ColorManager.js 16080 2010-08-31 08:14:41Z c.weiss@metaways.de $
 */
 
Ext.ns('Tine.Calendar');

/**
 * @namespace Tine.Calendar
 * @class Tine.Calendar.ColorManager
 * @extends Ext.util.Observable
 * Colormanager for Coloring Calendar Events <br>
 * 
 * @constructor
 * Creates a new color manager
 * @param {Object} config
 * 
 * @author      Cornelius Weiss <c.weiss@metaways.de>
 * @copyright   Copyright (c) 2007-2008 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: ColorManager.js 16080 2010-08-31 08:14:41Z c.weiss@metaways.de $
 */
Tine.Calendar.ColorManager = function(config) {
    Ext.apply(this, config);
    
    this.colorMap = {};
    
    // allthough we don't extend component as we have nothing to render, we borrow quite some stuff from it
    this.id = this.stateId;
    Ext.ComponentMgr.register(this);
    
    this.addEvents(
        /**
         * @event beforestaterestore
         * Fires before the state of this colormanager is restored. Return false to stop the restore.
         * @param {Tine.Calendar.ColorManager} this
         * @param {Object} state The hash of state values
         */
        'beforestaterestore',
        /**
         * @event staterestore
         * Fires after the state of tthis colormanager is restored.
         * @param {Tine.Calendar.ColorManager} this
         * @param {Object} state The hash of state values
         */
        'staterestore',
        /**
         * @event beforestatesave
         * Fires before the state of this colormanager is saved to the configured state provider. Return false to stop the save.
         * @param {Tine.Calendar.ColorManager} this
         * @param {Object} state The hash of state values
         */
        'beforestatesave',
        /**
         * @event statesave
         * Fires after the state of this colormanager is saved to the configured state provider.
         * @param {Tine.Calendar.ColorManager} this
         * @param {Object} state The hash of state values
         */
        'statesave'
    );
    
    if (this.stateful) {
        this.initState();
    }
   
};

Ext.extend(Tine.Calendar.ColorManager, Ext.util.Observable, {
    /**
     * @cfg {String} schemaName
     * Name of color schema to use
     */
    schemaName: 'standard',
    
    /**
     * @cfg {String} stateId
     * State id to use
     */
    stateId: 'cal-color-mgr-containers',
    
    /**
     * @cfg {Boolean} stateful
     * Is this component statefull?
     */
    stateful: false,
    
    /**
     * current color map 
     * 
     * @type Object 
     * @propertycolorMap
     */
    colorMap: null,
    
    /**
     * pointer to current color set in color schema 
     * 
     * @type Number 
     * @property colorSchemataPointer
     */
    colorSchemataPointer: 0,
    
    /**
     * gray color set
     * 
     * @type Object 
     * @property gray
     */
    gray: {color: '#808080', light: '#EDEDED', text: '#FFFFFF', lightText: '#FFFFFF'},
    
    /**
     * color palette from Ext.ColorPalette
     * 
     * @type Array
     * @property colorPalette
     */
    colorPalette: Ext.ColorPalette.prototype.colors,
    
    /**
     * color sets for colors from colorPalette
     * 
     * @type Array 
     * @property colorSchemata
     */
    colorSchemata : {
        "000000" : {color: '#000000', light: '#8F8F8F', text: '#FFFFFF', lightText: '#FFFFFF'},
        "993300" : {color: '#993300', light: '#CEA590', text: '#FFFFFF', lightText: '#FFFFFF'},
        "333300" : {color: '#333300', light: '#A6A691', text: '#FFFFFF', lightText: '#FFFFFF'}, 
        "003300" : {color: '#003300', light: '#8FA48F', text: '#FFFFFF', lightText: '#FFFFFF'},
        "003366" : {color: '#003366', light: '#90A5B9', text: '#FFFFFF', lightText: '#FFFFFF'},
        "000080" : {color: '#000080', light: '#9090C4', text: '#FFFFFF', lightText: '#FFFFFF'},
        "333399" : {color: '#333399', light: '#A5A5CE', text: '#FFFFFF', lightText: '#FFFFFF'},
        "333333" : {color: '#333333', light: '#A6A6A6', text: '#FFFFFF', lightText: '#FFFFFF'},
        
        "800000" : {color: '#800000', light: '#C79393', text: '#FFFFFF', lightText: '#FFFFFF'},
        "FF6600" : {color: '#FF6600', light: '#F8BB92', text: '#FFFFFF', lightText: '#FFFFFF'}, // orange
        "808000" : {color: '#808000', light: '#C6C692', text: '#FFFFFF', lightText: '#FFFFFF'},
        "008000" : {color: '#008000', light: '#92C692', text: '#FFFFFF', lightText: '#FFFFFF'},
        "008080" : {color: '#008080', light: '#91C5C5', text: '#FFFFFF', lightText: '#FFFFFF'},
        "0000FF" : {color: '#0000FF', light: '#9292F8', text: '#FFFFFF', lightText: '#FFFFFF'},
        "666699" : {color: '#666699', light: '#BBBBD0', text: '#FFFFFF', lightText: '#FFFFFF'},
        "808080" : {color: '#808080', light: '#C6C6C6', text: '#FFFFFF', lightText: '#FFFFFF'},
        "FF0000" : {color: '#FF0000', light: '#F89292', text: '#FFFFFF', lightText: '#FFFFFF'}, // red
        "FF9900" : {color: '#FF9900', light: '#F8D092', text: '#FFFFFF', lightText: '#FFFFFF'},
        "99CC00" : {color: '#99CC00', light: '#D0E492', text: '#FFFFFF', lightText: '#FFFFFF'},
        "339966" : {color: '#339966', light: '#A7D0BB', text: '#FFFFFF', lightText: '#FFFFFF'},
        "33CCCC" : {color: '#33CCCC', light: '#A8E5E5', text: '#FFFFFF', lightText: '#FFFFFF'},
        "3366FF" : {color: '#3366FF', light: '#A7BBF8', text: '#FFFFFF', lightText: '#FFFFFF'}, // blue
        "800080" : {color: '#800080', light: '#C692C6', text: '#FFFFFF', lightText: '#FFFFFF'},
        "969696" : {color: '#969696', light: '#CECECE', text: '#FFFFFF', lightText: '#FFFFFF'},
        "FF00FF" : {color: '#FF00FF', light: '#F690F6', text: '#FFFFFF', lightText: '#FFFFFF'}, // purple
        "FFCC00" : {color: '#FFCC00', light: '#F7E391', text: '#FFFFFF', lightText: '#FFFFFF'},
        "FFFF00" : {color: '#FFFF00', light: '#F7F791', text: '#000000', lightText: '#000000'},
        "00FF00" : {color: '#00FF00', light: '#93F993', text: '#000000', lightText: '#000000'}, // green
        "00FFFF" : {color: '#00FFFF', light: '#93F9F9', text: '#000000', lightText: '#000000'},
        "00CCFF" : {color: '#00CCFF', light: '#93E5F9', text: '#FFFFFF', lightText: '#FFFFFF'},
        "993366" : {color: '#993366', light: '#D1A8BC', text: '#FFFFFF', lightText: '#FFFFFF'}, // violet
        "C0C0C0" : {color: '#C0C0C0', light: '#DFDFDF', text: '#FFFFFF', lightText: '#FFFFFF'},
        "FF99CC" : {color: '#FF99CC', light: '#F8F0E4', text: '#FFFFFF', lightText: '#FFFFFF'},
        "FFCC99" : {color: '#FFCC99', light: '#F8E4D0', text: '#000000', lightText: '#000000'},
        "FFFF99" : {color: '#FFFF99', light: '#F9F9D1', text: '#000000', lightText: '#000000'},
        "CCFFCC" : {color: '#CCFFCC', light: '#E5F9E5', text: '#000000', lightText: '#000000'},
        "CCFFFF" : {color: '#CCFFFF', light: '#E5F9F9', text: '#000000', lightText: '#000000'},
        "99CCFF" : {color: '#99CCFF', light: '#D0E4F8', text: '#000000', lightText: '#000000'},
        "CC99FF" : {color: '#CC99FF', light: '#E5D1F9', text: '#000000', lightText: '#000000'},
        "FFFFFF" : {color: '#DFDFDF', light: '#F8F8F8', text: '#000000', lightText: '#000000'}
    },
    
    /**
     * hack for container only support
     * 
     * @param {Tine.Calendar.Model.Evnet} event
     * @return {Object} colorset
     */
    getColor: function(event) {
        var container = null;
        
        if (typeof event.get != 'function') {
            // tree comes with containers only
            container = event;
        } else {
            container = event.get('container_id');
            if (! container || !container.type || container.type != 'shared') {
                container = event.getDisplayContainer();
            }
        }
        
        if (! container.color) {
            return this.gray;
        }
        
        return this.colorSchemata[container.color.replace('#', '')];
        //var container_id = container.id ? container.id : container;
        //return container ? this.getColorSchema(container_id) : this.gray;
    }
    
//    /**
//     * gets the next free color set
//     * 
//     * @param {String} item e.g. a calendar id
//     * @return {Object} colorset
//     */
//    getColorSchema: function(item) {
//        if (this.colorMap[item]) {
//            return this.colorSchemata[this.colorMap[item]];
//        }
//        
//        // find a 'free' schema
//        for (var i=1,cpi; i<=this.colorPalette.length; i++) {
//            // color palette index
//            cpi = (i+this.colorSchemataPointer) % this.colorPalette.length;
//            if (this.colorSchemata[cpi].color && !this.inUse(this.colorPalette[cpi])) {
//                this.colorSchemataPointer = cpi;
//                this.colorMap[item] = this.colorSchemataPointer;
//                this.saveState();
//                //console.log('assigned color ' + this.colorMap[item] + ' to item ' + item);
//                
//                return this.colorSchemata[this.colorSchemataPointer];
//            }
//        }
//
//        // no more free colors ;-(
//        this.colorSchemataPointer++;
//        this.colorMap[item] = this.colorSchemataPointer;
//        return this.colorSchemata[this.colorSchemataPointer];
//    },
//    
//    /**
//     * checkes if given color is already in use
//     * 
//     * @param {String} color
//     * @return {Boolean}
//     */
//    inUse: function(color) {
//        for (var item in this.colorMap) {
//            if (this.colorMap.hasOwnProperty(item) && this.colorMap[item] == color) {
//                //console.log(color + ' is already used');
//                return true;
//            }
//        }
//        //console.log(color + 'is not in use yet');
//        return false;
//    },
//    
//    /* state handling */
//    initState:       Ext.Component.prototype.initState,
//    getStateId:      Ext.Component.prototype.getStateId,
//    //initStateEvents: Ext.Component.prototype.initState,
//    applyState:      Ext.Component.prototype.applyState,
//    saveState:       Ext.Component.prototype.saveState,
//    getState:        function() {
//        return {
//            colorMap            : this.colorMap,
//            colorSchemataPointer: this.colorSchemataPointer
//        };
//    }
    
    
    
});

// file: /home/lkneschke/temp/tine20build/temp/tine20/Calendar/js/RrulePanel.js
/* 
 * Tine 2.0
 * 
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Cornelius Weiss <c.weiss@metaways.de>
 * @copyright   Copyright (c) 2007-2008 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: RrulePanel.js 18536 2011-01-12 08:14:06Z c.weiss@metaways.de $
 */

Ext.ns('Tine.Calendar');

Tine.Calendar.RrulePanel = Ext.extend(Ext.Panel, {
    
    /**
     * @static
     */
    wkdays: ['SU', 'MO', 'TU', 'WE', 'TH', 'FR', 'SA'],
    /**
     * @property
     */    
    activeRuleCard: null,
    
    layout: 'form',
    frame: true,
    
    initComponent: function() {
        this.app = Tine.Tinebase.appMgr.get('Calendar');
        
        this.title = this.app.i18n._('Recurrances');

        this.defaults = {
            border: false
        };
        
        this.NONEcard = new Ext.Panel({
            freq: 'NONE',
            html: this.app.i18n._('No recurring rule defined')
        });
        this.NONEcard.setRule = Ext.emptyFn;
        this.NONEcard.fillDefaults = Ext.emptyFn;
        this.NONEcard.getRule = function() {
            return null;
        };
        this.NONEcard.isValid = function() {
            return true;
        };
        
        this.DAILYcard = new Tine.Calendar.RrulePanel.DAILYcard({rrulePanel: this});
        this.WEEKLYcard = new Tine.Calendar.RrulePanel.WEEKLYcard({rrulePanel: this});
        this.MONTHLYcard = new Tine.Calendar.RrulePanel.MONTHLYcard({rrulePanel: this});
        this.YEARLYcard = new Tine.Calendar.RrulePanel.YEARLYcard({rrulePanel: this});
        
        this.ruleCards = new Ext.Panel({
            layout: 'card',
            activeItem: 0,
            items: [
                this.NONEcard,
                this.DAILYcard,
                this.WEEKLYcard,
                this.MONTHLYcard,
                this.YEARLYcard
            ]
        });

        this.idPrefix = Ext.id();
        
        this.items = [{
            xtype: 'toolbar',
            //style: 'background: 0; border: 0; padding-bottom: 5px;',
            style: 'margin-bottom: 5px;',
            
            items: [{
                id: this.idPrefix + 'tglbtn' + 'NONE',
                xtype: 'tbbtnlockedtoggle',
                enableToggle: true,
                //pressed: true,
                text: this.app.i18n._('None'),
                handler: this.onFreqChange.createDelegate(this, ['NONE']),
                toggleGroup: this.idPrefix + 'freqtglgroup'
            }, {
                id: this.idPrefix + 'tglbtn' + 'DAILY',
                xtype: 'tbbtnlockedtoggle',
                enableToggle: true,
                text: this.app.i18n._('Daily'),
                handler: this.onFreqChange.createDelegate(this, ['DAILY']),
                toggleGroup: this.idPrefix + 'freqtglgroup'
            }, {
                id: this.idPrefix + 'tglbtn' + 'WEEKLY',
                xtype: 'tbbtnlockedtoggle',
                enableToggle: true,
                text: this.app.i18n._('Weekly'),
                handler: this.onFreqChange.createDelegate(this, ['WEEKLY']),
                toggleGroup: this.idPrefix + 'freqtglgroup'
            }, {
                id: this.idPrefix + 'tglbtn' + 'MONTHLY',
                xtype: 'tbbtnlockedtoggle',
                enableToggle: true,
                text: this.app.i18n._('Monthly'),
                handler: this.onFreqChange.createDelegate(this, ['MONTHLY']),
                toggleGroup: this.idPrefix + 'freqtglgroup'
            }, {
                id: this.idPrefix + 'tglbtn' + 'YEARLY',
                xtype: 'tbbtnlockedtoggle',
                enableToggle: true,
                text: this.app.i18n._('Yearly'),
                handler: this.onFreqChange.createDelegate(this, ['YEARLY']),
                toggleGroup: this.idPrefix + 'freqtglgroup'
            }]
            
        }, {
            layout: 'form',
            style: 'padding-left: 10px;',
            items: [
                this.ruleCards
            ]
        }];
        
        Tine.Calendar.RrulePanel.superclass.initComponent.call(this);
    },
    
    isValid: function() {
        return this.activeRuleCard.isValid(this.record);
    },
    
    onFreqChange: function(freq) {
        this.ruleCards.layout.setActiveItem(this[freq + 'card']);
        this.ruleCards.layout.layout();
        this.activeRuleCard = this[freq + 'card'];
    },
    
    onRecordLoad: function(record) {
        this.record = record;
        
        if (! this.record.get('editGrant')) {
            this.items.each(function(item) {
                item.setDisabled(true);
            }, this);
        }
        
        this.rrule = this.record.get('rrule');
        
        var dtstart = this.record.get('dtstart');
        if (Ext.isDate(dtstart)) {
            var byday      = Tine.Calendar.RrulePanel.prototype.wkdays[dtstart.format('w')];
            var bymonthday = dtstart.format('j');
            var bymonth    = dtstart.format('n');
            
            this.WEEKLYcard.setRule({
                interval: 1,
                byday: byday
            });
            this.MONTHLYcard.setRule({
                interval: 1,
                byday: '1' + byday,
                bymonthday: bymonthday
            });
            this.YEARLYcard.setRule({
                byday: '1' + byday,
                bymonthday: bymonthday,
                bymonth: bymonth
            });
        }
        
        var freq = this.rrule && this.rrule.freq ? this.rrule.freq : 'NONE';
        
        var freqBtn = Ext.getCmp(this.idPrefix + 'tglbtn' + freq);
        freqBtn.toggle(true);
        
        this.activeRuleCard = this[freq + 'card'];
        this.ruleCards.activeItem = this.activeRuleCard;
        
        this.activeRuleCard.setRule(this.rrule);
    },
    
    onRecordUpdate: function(record) {
        var rrule = this.activeRuleCard.rendered ? this.activeRuleCard.getRule() : this.rrule;
        
        if (! this.rrule && rrule) {
            // mark as new rule to avoid series confirm dlg
            rrule.newrule = true;
        }
        
        record.set('rrule', '');
        record.set('rrule', rrule);
    }
});

Tine.Calendar.RrulePanel.AbstractCard = Ext.extend(Ext.Panel, {
    border: false,
    layout: 'form',
    labelAlign: 'side',
    autoHeight: true,
    
    getRule: function() {
        var until = this.until.getRawValue();
        until = until ? Date.parseDate(until, this.until.format) : null;
        
        
        if (Ext.isDate(until)) {
            // make sure, last recurance is included
            until = until.clearTime(true).add(Date.HOUR, 24).add(Date.SECOND, -1).format(Date.patterns.ISO8601Long);
        }
        
        var rrule = {
            freq    : this.freq,
            interval: this.interval.getValue(),
            //until   : Ext.isDate(until) ? until.format(Date.patterns.ISO8601Long) : null
            until   : until
        };
        
        return rrule;
    },
    
    onAfterUnitTriggerClick: function() {
        if (! this.until.getValue()) {
            var dtstart = this.rrulePanel.record.get('dtstart');
            this.until.menu.picker.setValue(dtstart);
        }
    },
    
    initComponent: function() {
        this.app = Tine.Tinebase.appMgr.get('Calendar');
        
        this.untilId = Ext.id();
        
        this.until = new Ext.form.DateField({
            requiredGrant : 'editGrant',
            width         : 100,
            emptyText     : this.app.i18n._('forever'),
            onTriggerClick: Ext.form.DateField.prototype.onTriggerClick.createSequence(this.onAfterUnitTriggerClick, this),
            listeners: {
                scope: this,
                // so dumb!
                render: function(f) {f.wrap.setWidth.defer(100, f.wrap, [f.initialConfig.width]);}
            }
        });
        
        /*
        this.untilCombo = new Ext.form.ComboBox({
            triggerAction : 'all',
            width: 70,
            hideLabel: true,
            value         : false,
            editable      : false,
            mode          : 'local',
            store         : [
                [false,   this.app.i18n._('Forever')  ],
                ['at',    this.app.i18n._('at')     ]
            ]
        });
        */
        var intervalPars = this.intervalString.split('{0}');
        var intervalBeforeString = intervalPars[0];
        var intervalAfterString = intervalPars[1];
        
        this.interval = new Ext.form.NumberField({
            requiredGrant : 'editGrant',
            style         : 'text-align:right;',
            //fieldLabel    : this.intervalBeforeString,
            value         : 1,
            width         : 40
        });
        
        if (! this.items) {
            this.items = [];
        }
        
        if (this.freq != 'YEARLY') {
            this.items = [{
                layout: 'column',
                items: [{
                    width: 70,
                    html: intervalBeforeString
                },
                    this.interval,
                {
                    style: 'padding-top: 2px;',
                    html: intervalAfterString
                }]
            }].concat(this.items);
        }
        
        this.items = this.items.concat({
            layout: 'column',
            style: 'padding-top: 5px;',
            items: [{
                width: 70,
                html: this.app.i18n._('Until')
            }, this.until]
                
        });
        
        Tine.Calendar.RrulePanel.AbstractCard.superclass.initComponent.call(this);
    },
    
    isValid: function(record) {
        var until = this.until.getValue();
        if (Ext.isDate(until) && Ext.isDate(record.get('dtstart'))) {
            if (until.getTime() < record.get('dtstart').getTime()) {
                this.until.markInvalid(this.app.i18n._('Until has to be after event start'));
                return false;
            }
        }
        
        return true;
    },
    
    setRule: function(rrule) {
        this.interval.setValue(rrule.interval);
        var date = Date.parseDate(rrule.until, Date.patterns.ISO8601Long);
        this.until.value = date;
    }
});

Tine.Calendar.RrulePanel.DAILYcard = Ext.extend(Tine.Calendar.RrulePanel.AbstractCard, {
    
    freq: 'DAILY',
    
    initComponent: function() {
        this.app = Tine.Tinebase.appMgr.get('Calendar');
        
        this.intervalString = this.app.i18n._('Every {0}. Day');
        
        Tine.Calendar.RrulePanel.DAILYcard.superclass.initComponent.call(this);
    }
});

Tine.Calendar.RrulePanel.WEEKLYcard = Ext.extend(Tine.Calendar.RrulePanel.AbstractCard, {
    
    freq: 'WEEKLY',
    
    getRule: function() {
        var rrule = Tine.Calendar.RrulePanel.WEEKLYcard.superclass.getRule.call(this);
        
        var bydayArray = [];
        this.byday.items.each(function(cb) {
            if (cb.checked) {
                bydayArray.push(cb.name);
            }
        }, this);
        
        rrule.byday = bydayArray.join();
        if (! rrule.byday) {
            rrule.byday = this.byDayValue;
        }
        return rrule;
    },
    
    initComponent: function() {
        this.app = Tine.Tinebase.appMgr.get('Calendar');
        
        this.intervalString = this.app.i18n._('Every {0}. Week at');
        
        var bydayItems = [];
        for (var i=0,d; i<7; i++) {
            d = (i+Ext.DatePicker.prototype.startDay)%7
            bydayItems.push({
                boxLabel: Date.dayNames[d],
                name: Tine.Calendar.RrulePanel.prototype.wkdays[d]
            })
        }
        
        this.byday = new Ext.form.CheckboxGroup({
            requiredGrant : 'editGrant',
            style: 'padding-top: 5px;',
            hideLabel: true,
            items: bydayItems
        });
        
        this.items = [this.byday];
        
        Tine.Calendar.RrulePanel.WEEKLYcard.superclass.initComponent.call(this);
    },
    
    setRule: function(rrule) {
        Tine.Calendar.RrulePanel.WEEKLYcard.superclass.setRule.call(this, rrule);
        
        if (rrule.byday) {
            this.byDayValue = rrule.byday;
            
            var bydayArray = rrule.byday.split(',');
            
            if (Ext.isArray(this.byday.items)) {
                // on initialisation items are not renderd
                Ext.each(this.byday.items, function(cb) {
                    if (bydayArray.indexOf(cb.name) != -1) {
                        cb.checked = true;
                    }
                }, this);
            } else {
                // after items are rendered
                this.byday.items.each(function(cb) {
                    if (bydayArray.indexOf(cb.name) != -1) {
                        cb.setValue(true);
                    }
                }, this);
            }
        }
    }
});

Tine.Calendar.RrulePanel.MONTHLYcard = Ext.extend(Tine.Calendar.RrulePanel.AbstractCard, {
    
    freq: 'MONTHLY',
    
    getRule: function() {
        var rrule = Tine.Calendar.RrulePanel.MONTHLYcard.superclass.getRule.call(this);
        
        if (this.bydayRadio.checked) {
            rrule.byday = this.wkNumber.getValue() + this.wkDay.getValue();
        } else {
            rrule.bymonthday = this.bymonthdayday.getValue();
        }
        
        return rrule;
    },
    
    initComponent: function() {
        this.app = Tine.Tinebase.appMgr.get('Calendar');
        
        this.intervalString = this.app.i18n._('Every {0}. Month');
        
        this.idPrefix = Ext.id();
        
        this.bydayRadio = new Ext.form.Radio({
            hideLabel: true,
            boxLabel: this.app.i18n._('at the'), 
            name: this.idPrefix + 'byRadioGroup', 
            inputValue: 'BYDAY',
            checked: true,
            listeners: {
                check: this.onByRadioCheck.createDelegate(this)
            }
        });

        this.wkNumber = new Ext.form.ComboBox({
            requiredGrant : 'editGrant',
            width: 80,
            listWidth: 80,
            triggerAction : 'all',
            hideLabel     : true,
            value         : 1,
            editable      : false,
            mode          : 'local',
            store         : [
                [1,  this.app.i18n._('first')  ],
                [2,  this.app.i18n._('second') ],
                [3,  this.app.i18n._('third')  ],
                [4,  this.app.i18n._('fourth') ],
                [-1, this.app.i18n._('last')   ]
            ]
        });
        
        var wkdayItems = [];
        for (var i=0,d; i<7; i++) {
            d = (i+Ext.DatePicker.prototype.startDay)%7
            Tine.Calendar.RrulePanel.prototype.wkdays[d];
            wkdayItems.push([Tine.Calendar.RrulePanel.prototype.wkdays[d], Date.dayNames[d]]);
        }
        
        this.wkDay = new Ext.form.ComboBox({
            requiredGrant : 'editGrant',
            width         : 100,
            listWidth     : 100,
            triggerAction : 'all',
            hideLabel     : true,
            value         : Tine.Calendar.RrulePanel.prototype.wkdays[Ext.DatePicker.prototype.startDay],
            editable      : false,
            mode          : 'local',
            store         : wkdayItems
        });
        
        this.bymonthdayRadio = new Ext.form.Radio({
            requiredGrant : 'editGrant',
            hideLabel     : true,
            boxLabel      : this.app.i18n._('at the'), 
            name          : this.idPrefix + 'byRadioGroup', 
            inputValue    : 'BYMONTHDAY',
            listeners     : {
                check: this.onByRadioCheck.createDelegate(this)
            }
        });
        
        this.bymonthdayday = new Ext.form.NumberField({
            requiredGrant : 'editGrant',
            hideLabel     : true,
            width         : 40,
            value         : 1,
            disabled      : true
        });
        
        this.items = [{
            html: '<div style="padding-top: 5px; padding-left: 5px">' +
                    '<div style="position: relative;">' +
                        '<table><tr>' +
                            '<td style="position: relative;" width="60" id="' + this.idPrefix + 'bydayradio"></td>' +
                            '<td width="100" id="' + this.idPrefix + 'bydaywknumber"></td>' +
                            '<td width="110" id="' + this.idPrefix + 'bydaywkday"></td>' +
                        '</tr></table>' +
                    '</div>' +
                    '<div style="position: relative;">' +
                        '<table><tr>' +
                            '<td width="60" id="' + this.idPrefix + 'bymonthdayradio"></td>' +
                            '<td width="40" id="' + this.idPrefix + 'bymonthdayday"></td>' +
                            '<td>.</td>' +
                         '</tr></table>' +
                    '</div>' +
                '</div>',
            listeners: {
                scope: this,
                render: this.onByRender
            }
        }];
        
        Tine.Calendar.RrulePanel.MONTHLYcard.superclass.initComponent.call(this);
    },
    
    onByRadioCheck: function(radio, checked) {
        switch(radio.inputValue) {
            case 'BYDAY':
                this.bymonthdayday.setDisabled(checked);
                break;
            case 'BYMONTHDAY':
                this.wkNumber.setDisabled(checked);
                this.wkDay.setDisabled(checked);
                break;
        }
    },
    
    onByRender: function() {
        var bybayradioel = Ext.get(this.idPrefix + 'bydayradio');
        var bybaywknumberel = Ext.get(this.idPrefix + 'bydaywknumber');
        var bybaywkdayel = Ext.get(this.idPrefix + 'bydaywkday');
        
        var bymonthdayradioel = Ext.get(this.idPrefix + 'bymonthdayradio');
        var bymonthdaydayel = Ext.get(this.idPrefix + 'bymonthdayday');
        
        if (! (bybayradioel && bymonthdayradioel)) {
            return this.onByRender.defer(100, this, arguments);
        }
        
        this.bydayRadio.render(bybayradioel);
        this.wkNumber.render(bybaywknumberel);
        this.wkNumber.wrap.setWidth(80);
        this.wkDay.render(bybaywkdayel);
        this.wkDay.wrap.setWidth(100);
        
        this.bymonthdayRadio.render(bymonthdayradioel);
        this.bymonthdayday.render(bymonthdaydayel);
    },
    
    setRule: function(rrule) {
        Tine.Calendar.RrulePanel.MONTHLYcard.superclass.setRule.call(this, rrule);
        
        if (rrule.byday) {
            this.bydayRadio.setValue(true);
            this.bymonthdayRadio.setValue(false);
            this.onByRadioCheck(this.bydayRadio, true);
            this.onByRadioCheck(this.bymonthdayRadio, false);
            
            var parts = rrule.byday.match(/([\-\d]{1,2})([A-Z]{2})/);
            this.wkNumber.setValue(parts[1]);
            this.wkDay.setValue(parts[2]);
            
        }
        
        if (rrule.bymonthday) {
            this.bydayRadio.setValue(false);
            this.bymonthdayRadio.setValue(true);
            this.onByRadioCheck(this.bydayRadio, false);
            this.onByRadioCheck(this.bymonthdayRadio, true);
            
            this.bymonthdayday.setValue(rrule.bymonthday);
        }

    }
    
});

Tine.Calendar.RrulePanel.YEARLYcard = Ext.extend(Tine.Calendar.RrulePanel.AbstractCard, {
    
    freq: 'YEARLY',
    
    getRule: function() {
        var rrule = Tine.Calendar.RrulePanel.MONTHLYcard.superclass.getRule.call(this);
        
        if (this.bydayRadio.checked) {
            rrule.byday = this.wkNumber.getValue() + this.wkDay.getValue();
        } else {
            rrule.bymonthday = this.bymonthdayday.getValue();
        }
        
        rrule.bymonth = this.bymonth.getValue();
        return rrule;
    },
    
    initComponent: function() {
        this.app = Tine.Tinebase.appMgr.get('Calendar');
        
        this.intervalString = this.app.i18n._('Every {0}. Year');
        
        this.idPrefix = Ext.id();
        
        this.bydayRadio = new Ext.form.Radio({
            requiredGrant : 'editGrant',
            hideLabel     : true,
            boxLabel      : this.app.i18n._('at the'), 
            name          : this.idPrefix + 'byRadioGroup', 
            inputValue    : 'BYDAY',
            listeners     : {
                check: this.onByRadioCheck.createDelegate(this)
            }
        });

        this.wkNumber = new Ext.form.ComboBox({
            requiredGrant : 'editGrant',
            width         : 80,
            listWidth     : 80,
            triggerAction : 'all',
            hideLabel     : true,
            value         : 1,
            editable      : false,
            mode          : 'local',
            disabled      : true,
            store         : [
                [1,  this.app.i18n._('first')  ],
                [2,  this.app.i18n._('second') ],
                [3,  this.app.i18n._('third')  ],
                [4,  this.app.i18n._('fourth') ],
                [-1, this.app.i18n._('last')   ]
            ]
        });
        
        var wkdayItems = [];
        for (var i=0,d; i<7; i++) {
            d = (i+Ext.DatePicker.prototype.startDay)%7
            Tine.Calendar.RrulePanel.prototype.wkdays[d];
            wkdayItems.push([Tine.Calendar.RrulePanel.prototype.wkdays[d], Date.dayNames[d]]);
        }
        
        this.wkDay = new Ext.form.ComboBox({
            requiredGrant : 'editGrant',
            width         : 100,
            listWidth     : 100,
            triggerAction : 'all',
            hideLabel     : true,
            value         : Tine.Calendar.RrulePanel.prototype.wkdays[Ext.DatePicker.prototype.startDay],
            editable      : false,
            mode          : 'local',
            store         : wkdayItems,
            disabled      : true
        });
        
        this.bymonthdayRadio = new Ext.form.Radio({
            requiredGrant : 'editGrant',
            hideLabel     : true,
            boxLabel      : this.app.i18n._('at the'), 
            name          : this.idPrefix + 'byRadioGroup', 
            inputValue    : 'BYMONTHDAY',
            checked       : true,
            listeners     : {
                check: this.onByRadioCheck.createDelegate(this)
            }
        });
        
        this.bymonthdayday = new Ext.form.NumberField({
            requiredGrant : 'editGrant',
            hideLabel     : true,
            width         : 40,
            value         : 1
        });
        
        var monthItems = [];
        for (var i=0; i<Date.monthNames.length; i++) {
            monthItems.push([i+1, Date.monthNames[i]]);
        }
        
        this.bymonth = new Ext.form.ComboBox({
            requiredGrant : 'editGrant',
            width         : 100,
            listWidth     : 100,
            triggerAction : 'all',
            hideLabel     : true,
            value         : 1,
            editable      : false,
            mode          : 'local',
            store         : monthItems
        });
        
        this.items = [{
            html: '<div style="padding-top: 5px;">' +
                    '<div style="position: relative;">' +
                        '<table><tr>' +
                            '<td style="position: relative;" width="65" id="' + this.idPrefix + 'bydayradio"></td>' +
                            '<td width="100" id="' + this.idPrefix + 'bydaywknumber"></td>' +
                            '<td width="110" id="' + this.idPrefix + 'bydaywkday"></td>' +
                            //'<td style="padding-left: 10px">' + this.app.i18n._('of') + '</td>' +
                        '</tr></table>' +
                    '</div>' +
                    '<div style="position: relative;">' +
                        '<table><tr>' +
                            '<td width="65" id="' + this.idPrefix + 'bymonthdayradio"></td>' +
                            '<td width="40" id="' + this.idPrefix + 'bymonthdayday"></td>' +
                            '<td>.</td>' +
                         '</tr></table>' +
                    '</div>' +
                    '<div style="position: relative;">' +
                        '<table><tr>' +
                            '<td width="48" style="padding-left: 17px">' + this.app.i18n._('of') + '</td>' +
                            '<td width="100" id="' + this.idPrefix + 'bymonth"></td>' +
                         '</tr></table>' +
                    '</div>' +
                '</div>',
            listeners: {
                scope: this,
                render: this.onByRender
            }
        }];
        Tine.Calendar.RrulePanel.YEARLYcard.superclass.initComponent.call(this);
    },
    
    onByRadioCheck: function(radio, checked) {
        switch(radio.inputValue) {
            case 'BYDAY':
                this.bymonthdayday.setDisabled(checked);
                break;
            case 'BYMONTHDAY':
                this.wkNumber.setDisabled(checked);
                this.wkDay.setDisabled(checked);
                break;
        }
    },
    
    onByRender: function() {
        var bybayradioel = Ext.get(this.idPrefix + 'bydayradio');
        var bybaywknumberel = Ext.get(this.idPrefix + 'bydaywknumber');
        var bybaywkdayel = Ext.get(this.idPrefix + 'bydaywkday');
        
        var bymonthdayradioel = Ext.get(this.idPrefix + 'bymonthdayradio');
        var bymonthdaydayel = Ext.get(this.idPrefix + 'bymonthdayday');

        var bymonthel = Ext.get(this.idPrefix + 'bymonth');
        
        if (! (bybayradioel && bymonthdayradioel)) {
            return this.onByRender.defer(100, this, arguments);
        }
        
        this.bydayRadio.render(bybayradioel);
        this.wkNumber.render(bybaywknumberel);
        this.wkNumber.wrap.setWidth(80);
        this.wkDay.render(bybaywkdayel);
        this.wkDay.wrap.setWidth(100);
        
        this.bymonthdayRadio.render(bymonthdayradioel);
        this.bymonthdayday.render(bymonthdaydayel);
        
        this.bymonth.render(bymonthel);
        this.bymonth.wrap.setWidth(100);
    },
    
    setRule: function(rrule) {
        Tine.Calendar.RrulePanel.MONTHLYcard.superclass.setRule.call(this, rrule);
        
        if (rrule.byday) {
            this.bydayRadio.setValue(true);
            this.bymonthdayRadio.setValue(false);
            this.onByRadioCheck(this.bydayRadio, true);
            this.onByRadioCheck(this.bymonthdayRadio, false);
            
            var parts = rrule.byday.match(/([\-\d]{1,2})([A-Z]{2})/);
            this.wkNumber.setValue(parts[1]);
            this.wkDay.setValue(parts[2]);
            
        }
        
        if (rrule.bymonthday) {
            this.bydayRadio.setValue(false);
            this.bymonthdayRadio.setValue(true);
            this.onByRadioCheck(this.bydayRadio, false);
            this.onByRadioCheck(this.bymonthdayRadio, true);
            
            this.bymonthdayday.setValue(rrule.bymonthday);
        }
        
        this.bymonth.setValue(rrule.bymonth);

    }
});
// file: /home/lkneschke/temp/tine20build/temp/tine20/Calendar/js/ResourcesGridPanel.js
/*
 * Tine 2.0
 * 
 * @package     Calendar
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Cornelius Weiss <c.weiss@metaways.de>
 * @copyright   Copyright (c) 2009 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: ResourcesGridPanel.js 14905 2010-06-10 05:43:36Z c.weiss@metaways.de $
 *
 */
 
Ext.ns('Tine.Calendar');

/**
 * @namespace Tine.Calendar
 * @class     Tine.Calendar.ResourcesGridPanel
 * @extends   Tine.widgets.grid.GridPanel
 * Resources Grid Panel <br>
 * 
 * @author      Cornelius Weiss <c.weiss@metaways.de>
 * @version     $Id: ResourcesGridPanel.js 14905 2010-06-10 05:43:36Z c.weiss@metaways.de $
 */
Tine.Calendar.ResourcesGridPanel = Ext.extend(Tine.widgets.grid.GridPanel, {
    // model generics
    recordClass: Tine.Calendar.Model.Resource,
    
    // grid specific
    defaultSortInfo: {field: 'name', dir: 'ASC'},
    
    // not yet
    evalGrants: false,
    
    newRecordIcon: 'cal-resource',
    
    initComponent: function() {
        this.app = Tine.Tinebase.appMgr.get('Calendar');
        
        this.gridConfig = {
        };
        
        this.gridConfig.columns = [{
            id: 'name',
            header: this.app.i18n._("Name"),
            width: 150,
            sortable: true,
            dataIndex: 'name'
        }, {
            id: 'email',
            header: this.app.i18n._("Email"),
            width: 150,
            sortable: true,
            dataIndex: 'email'
        }, new Ext.ux.grid.CheckColumn({
            header: _('Location'),
            dataIndex: 'is_location',
            width: 55
        })];
        
        this.supr().initComponent.call(this);
    },
    
    initLayout: function() {
        this.supr().initLayout.call(this);
        
        this.items.push({
            region : 'north',
            height : 55,
            border : false,
            items  : this.actionToolbar
        });
    },
    
    /**
     * preform the initial load of grid data
     */
    initialLoad: function() {
        this.store.load.defer(10, this.store, [
            typeof this.autoLoad == 'object' ?
                this.autoLoad : undefined]);
    }
});
// file: /home/lkneschke/temp/tine20build/temp/tine20/Calendar/js/ResourceEditDialog.js
/*
 * Tine 2.0
 * 
 * @package     Calendar
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Cornelius Weiss <c.weiss@metaways.de>
 * @copyright   Copyright (c) 2009 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: ResourceEditDialog.js 12361 2010-01-21 11:34:23Z c.weiss@metaways.de $
 *
 */
 
Ext.ns('Tine.Calendar');

/**
 * @namespace Tine.Calendar
 * @class     Tine.Calendar.ResourceEditDialog
 * @extends   Tine.widgets.dialog.EditDialog
 * Resources Grid Panel <br>
 * 
 * @author      Cornelius Weiss <c.weiss@metaways.de>
 * @version     $Id: ResourceEditDialog.js 12361 2010-01-21 11:34:23Z c.weiss@metaways.de $
 */
Tine.Calendar.ResourceEditDialog = Ext.extend(Tine.widgets.dialog.EditDialog, {
    
    recordClass: Tine.Calendar.Model.Resource,
    windowNamePrefix: 'ResourceEditWindow_',
    evalGrants: false,
    showContainerSelector: false,
    tbarItems: [{xtype: 'widget-activitiesaddbutton'}],
    //mode: 'local',
    
    getFormItems: function() { 
        return {
            xtype: 'tabpanel',
            border: false,
            plain:true,
            activeTab: 0,
            border: false,
            items:[
                {               
                title: this.app.i18n.n_('Resource', 'Resources', 1),
                autoScroll: true,
                border: false,
                frame: true,
                layout: 'border',
                items: [{
                    region: 'center',
                    xtype: 'columnform',
                    labelAlign: 'top',
                    formDefaults: {
                        xtype:'textfield',
                        anchor: '100%',
                        labelSeparator: '',
                        columnWidth: .333
                    },
                    items: [[{
                        xtype: 'textfield',
                        fieldLabel: this.app.i18n._('Name'),
                        allowBlank: false,
                        name: 'name'
                    }, {
                        xtype: 'textfield',
                        fieldLabel: this.app.i18n._('Email'),
                        allowBlank: false,
                        name: 'email',
                        vtype: 'email'
                    }, {
                        xtype: 'checkbox',
                        fieldLabel: this.app.i18n._('Is a location'),
                        //boxLabel: this.app.i18n._('Is a location'),
                        name: 'is_location'
                    }], [{
                        columnWidth: 1,
                        fieldLabel: this.app.i18n._('Description'),
                        emptyText: this.app.i18n._('Enter description...'),
                        name: 'description',
                        xtype: 'textarea',
                        height: 200
                    }]] 
                }, {
                    // activities and tags
                    layout: 'accordion',
                    animate: true,
                    region: 'east',
                    width: 210,
                    split: true,
                    collapsible: true,
                    collapseMode: 'mini',
                    header: false,
                    margins: '0 5 0 5',
                    border: true,
                    items: [
                        new Tine.widgets.activities.ActivitiesPanel({
                            app: 'Calendar',
                            showAddNoteForm: false,
                            border: false,
                            bodyStyle: 'border:1px solid #B5B8C8;'
                        }),
                        new Tine.widgets.tags.TagPanel({
                            app: 'Calendar',
                            border: false,
                            bodyStyle: 'border:1px solid #B5B8C8;'
                        })
                    ]
                }]
            }, new Tine.widgets.activities.ActivitiesTabPanel({
                app: this.appName,
                record_id: this.record.id,
                record_model: this.appName + '_Model_' + this.recordClass.getMeta('modelName')
            })]
        };
    }
    
                
});

/**
 * Opens a new resource edit dialog window
 * 
 * @return {Ext.ux.Window}
 */
Tine.Calendar.ResourceEditDialog.openWindow = function (config) {
    var id = (config.record && config.record.id) ? config.record.id : 0;
    var window = Tine.WindowFactory.getWindow({
        width: 800,
        height: 400,
        name: Tine.Calendar.ResourceEditDialog.prototype.windowNamePrefix + id,
        contentPanelConstructor: 'Tine.Calendar.ResourceEditDialog',
        contentPanelConstructorConfig: config
    });
    return window;
};
// file: /home/lkneschke/temp/tine20build/temp/tine20/Calendar/js/Printer/Base.js
Ext.ns('Tine.Calendar.Printer');


/**
 * @class   Tine.Calendar.Printer.BaseRenderer
 * @extends Ext.ux.Printer.BaseRenderer
 * 
 * Printig renderer for Ext.ux.printing
 */
Tine.Calendar.Printer.BaseRenderer = Ext.extend(Ext.ux.Printer.BaseRenderer, {
    stylesheetPath: 'Calendar/css/print.css',
//    generateBody: function(view) {
//        var days = [];
//        
//        // iterate days
//        for (var dayStart, dayEnd, dayEvents, i=0; i<view.numOfDays; i++) {
//            dayStart = view.startDate.add(Date.DAY, i);
//            dayEnd   = dayStart.add(Date.DAY, 1).add(Date.SECOND, -1);
//            
//            // get events in this day
//            dayEvents = view.ds.queryBy(function(event){
//                return event.data.dtstart.getTime() < dayEnd.getTime() && event.data.dtend.getTime() > dayStart.getTime();
//            });
//            
//            days.push(this.generateDay(dayStart, dayEnd, dayEvents));
//        }
//        
//        var topic = this.generateHeader(view);
//        var body  = 
//        return view.numOfDays === 1 ? days[0] : String.format('<table>{0}</table>', this.generateCalRows(days, view.numOfDays < 9 ? 2 : 7));
//    },
    
    generateCalRows: function(days, numCols, alignHorizontal) {
        var row, col, cellsHtml, idx,
            numRows = Math.ceil(days.length/numCols),
            rowsHtml = '';
        
        for (row=0; row<numRows; row++) {
            cellsHtml = '';
            //offset = row*numCols;
            
            for (col=0; col<numCols; col++) {
                idx = alignHorizontal ? row*numCols + col: col*numRows + row;
                cellsHtml += String.format('<td class="cal-print-daycell" style="vertical-align: top;">{0}</td>', days[idx] || '');
            }
            
            rowsHtml += String.format('<tr class="cal-print-dayrow" style="height: {1}mm">{0}</tr>', cellsHtml, this.paperHeight/numRows);
        }
        
        return rowsHtml;
    },
    
    generateDay: function(dayStart, dayEnd, dayEvents) {
        var dayBody = '';
        
        dayEvents.each(function(event){
            var start = event.data.dtstart.getTime() <= dayStart.getTime() ? dayStart : event.data.dtstart,
                end   = event.data.dtend.getTime() > dayEnd.getTime() ? dayEnd : event.data.dtend;
            
            dayBody += this.eventTpl.apply({
                color: event.colorSet.color,
                startTime: event.data.is_all_day_event ? '' : start.format('H:i'),
                untilseperator: event.data.is_all_day_event ? '' : '-',
                endTime: event.data.is_all_day_event ? '' : end.format('H:i'),
                summary: Ext.util.Format.htmlEncode(event.data.summary),
                duration: event.data.is_all_day_event ? Tine.Tinebase.appMgr.get('Calendar').i18n._('whole day') : 
                    Tine.Tinebase.common.minutesRenderer(Math.round((end.getTime() - start.getTime())/(1000*60)), '{0}:{1}', 'i')
            });
        }, this);
        
        var dayHeader = this.dayTpl.apply({
            dayOfMonth: dayStart.format('j'),
            weekDay: dayStart.format('l')
        });
        return String.format('<table class="cal-print-daysview-day"><tr>{0}</tr>{1}</table>', dayHeader, dayBody);
    },
    
    splitDays: function(ds, startDate, numOfDays, returnData) {
        var days = [];
        
        // iterate days
        for (var dayStart, dayEnd, dayEvents, i=0; i<numOfDays; i++) {
            dayStart = startDate.add(Date.DAY, i);
            dayEnd   = dayStart.add(Date.DAY, 1).add(Date.SECOND, -1);
            
            // get events in this day
            dayEvents = ds.queryBy(function(event){
                return event.data.dtstart.getTime() < dayEnd.getTime() && event.data.dtend.getTime() > dayStart.getTime();
            });
            
            days.push(returnData ? {
                dayStart: dayStart,
                dayEnd: dayEnd,
                dayEvents: dayEvents
            } : this.generateDay(dayStart, dayEnd, dayEvents));
        }
        
        return days;
    },
    
    dayTpl: new Ext.XTemplate(
        '<tr>',
            '<th  colspan="5">',
                '<span class="cal-print-daysview-day-dayOfMonth">{dayOfMonth}</span>',
                '<span class="cal-print-daysview-day-weekDay">{weekDay}</span>',
            '</th>', 
        '</tr>'
    ),
    
    /**
     * @property eventTpl
     * @type Ext.XTemplate
     * The XTemplate used to create the headings row. By default this just uses <th> elements, override to provide your own
     */
    eventTpl: new Ext.XTemplate(
        '<tr>',
            '<td class="cal-print-daysview-day-color"><span style="color: {color};">&#9673;</span></td>',
            '<td class="cal-print-daysview-day-starttime">{startTime}</td>',
            '<td class="cal-print-daysview-day-untilseperator">{untilseperator}</td>',
            '<td class="cal-print-daysview-day-endtime">{endTime}</td>',
            '<td class="cal-print-daysview-day-summary">{summary} (<span class="cal-print-daysview-day-duration">{duration}</span>)</td>',
        '</tr>'
    )
    
});
// file: /home/lkneschke/temp/tine20build/temp/tine20/Calendar/js/Printer/DaysView.js
Tine.Calendar.Printer.DaysViewRenderer = Ext.extend(Tine.Calendar.Printer.BaseRenderer, {
    paperHeight: 200, 
    generateBody: function(view) {
        var daysHtml = this.splitDays(view.ds, view.startDate, view.numOfDays),
            body = [];
        
        body.push('<table><tr><th class="cal-print-title">', this.getTitle(view), '</th></tr></table>');
        
        if (view.numOfDays === 1) {
            body.push(String.format('<div class="cal-print-day-singleday">{0}</div>', daysHtml[0]));
        } else if (view.numOfDays < 9) {
            if (view.numOfDays == 7 && view.startDate.format('w') == 1) {
                // iso week
                body.push(this.generateIsoWeek(daysHtml));
            } else {
                body.push(String.format('<table>{0}</table>', this.generateCalRows(daysHtml, 2)));
            }
        } else {
            body.push(String.format('<table>{0}</table>', this.generateCalRows(daysHtml, 2, true)));
        }
        
        return body.join("\n");
    },
    
    getTitle: function(view) {
        if (view.numOfDays == 1) {
            return String.format(view.dayFormatString + ' {3}', view.startDate.format('l'), view.startDate.format('j'), view.startDate.format('F'), view.startDate.format('Y'));
        } else {
            var endDate = view.startDate.add(Date.DAY, view.numOfDays -1),
                startDayOfMonth = view.startDate.format('j. '),
                startMonth = view.startDate.format('F '),
                startYear = view.startDate.format('Y '),
                endDayOfMonth = endDate.format('j. '),
                endMonth = endDate.format('F '),
                endYear = endDate.format('Y '),
                week = view.numOfDays == 7 ? String.format(view.app.i18n._('Week {0} :'), view.startDate.add(Date.DAY, 1).getWeekOfYear()) + ' ' : '';
                
                if (startYear === endYear) startYear = '';
                if (startMonth === endMonth) startMonth = '';
                
                return week + startDayOfMonth + startMonth + startYear + ' - ' + endDayOfMonth + endMonth + endYear;
        }
    },
  
    generateIsoWeek: function(daysHtml) {
        var height = this.paperHeight/4;
        return ['<table>',
            '<tr style="height: ' + height + 'mm;">',
                '<td class="cal-print-daycell" width="50%">', daysHtml[0], '</td>',
                '<td class="cal-print-daycell" width="50%">', daysHtml[3], '</td>',
            '</tr>', 
            '<tr style="height: ' + height + 'mm;">',
                '<td class="cal-print-daycell" width="50%">', daysHtml[1], '</td>',
                '<td class="cal-print-daycell" width="50%">', daysHtml[4], '</td>',
            '</tr>', 
            '<tr style="height: ' + height + 'mm;">',
                '<td class="cal-print-daycell" width="50%">', daysHtml[2], '</td>',
                '<td class="cal-print-daycell" width="50%">',
                    '<table style="padding: 0;">',
                        '<tr style="height: ' + height/2 + 'mm;">',
                            '<td class="cal-print-daycell" width="100%" style="padding: 0;">', daysHtml[5], '</td>',
                        '</tr>',
                        '<tr style="height: ' + height/2 + 'mm;">',
                            '<td class="cal-print-daycell" width="100%" style="padding: 0;">', daysHtml[6], '</td>',
                        '</tr>', 
                    '</table>',
            '</tr>', 
        '</table>'].join("\n");
    }
});
// file: /home/lkneschke/temp/tine20build/temp/tine20/Calendar/js/Printer/MonthView.js
Tine.Calendar.Printer.MonthViewRenderer = Ext.extend(Tine.Calendar.Printer.BaseRenderer, {
    paperHeight: 170,
    
    generateBody: function(view) {
        var daysHtml = this.splitDays(view.ds, view.dateMesh[0], view.dateMesh.length),
            body = [];
        
        // try to force landscape -> opera only atm...
        body.push('<style type="text/css">', 
            '@page {',
                'size:landscape',
            '}',
        '</style>');
        
        // title
        body.push('<table><tr><th class="cal-print-title">', this.getTitle(view), '</th></tr></table>');
        
        // day headers
        var dayNames = [];
        for(var i = 0; i < 7; i++){
            var d = view.startDay+i;
            if(d > 6){
                d = d-7;
            }
            dayNames.push("<td class='cal-print-monthview-daycell'><span>", view.dayNames[d], "</span></td>");
        }
        
        // body
        body.push(String.format('<br/><table class="cal-print-monthview"><tr>{0}</thead>{1}</tr>', dayNames.join("\n"), this.generateCalRows(daysHtml, 7, true)));
   
        return body.join("\n");
    },
    
    getTitle: function(view) {
        return view.dateMesh[10].format('F Y');
    },
    
    dayHeadersTpl: new Ext.XTemplate(
        '<tr>',
            '<tpl for=".">',
                '<th>\{{dataIndex}\}</th>',
            '</tpl>',
        '</tr>'
    )
});
