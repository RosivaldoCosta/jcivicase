/*!
 * Tine 2.0 USER CLIENT
 * 
 * license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * copyright   Copyright (c) 2007-2011 Metaways Infosystems GmbH (http://www.metaways.de)
 *
 * FOR MORE DETAILED LICENSE AND COPYRIGHT INFORMATION PLEASE CONSULT THE LICENSE FILE 
 * LOCATED AT: <YOUR TINE 2.0 URL>/LICENSE OR VISIT THE TINE 2.0 HOMEPAGE AT http://www.tine20.org
 */// file: /home/lkneschke/temp/tine20build/temp/tine20/Timetracker/js/DurationSpinner.js
/**
 * Tine 2.0
 * 
 * @package     Timetracker
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Cornelius Weiss <c.weiss@metaways.de>
 * @copyright   Copyright (c) 2007-2008 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: DurationSpinner.js 7118 2009-03-02 13:47:17Z p.schuele@metaways.de $
 *
 */
 
Ext.ns('Tine.Timetracker');

/**
 * handles minutes to time conversions
 * @class Tine.Timetracker.DurationSpinner
 * @extends Ext.ux.form.Spinner
 */
Tine.Timetracker.DurationSpinner = Ext.extend(Ext.ux.form.Spinner,  {
    
    initComponent: function() {
        this.preventMark = false;
        this.strategy = new Ext.ux.form.Spinner.TimeStrategy({
            incrementValue : 15
        });
        
        this.format = this.strategy.format;
    },
    
    setValue: function(value) {
        if(! value.toString().match(/:/)){
            var time = new Date(0);
            var hours = Math.floor(value / 60);
            var minutes = value - hours * 60;
            
            time.setHours(hours);
            time.setMinutes(minutes);
            
            value = Ext.util.Format.date(time, this.format);
        }

        Tine.Timetracker.DurationSpinner.superclass.setValue.call(this, value);
    },
    
    validateValue: function(value) {
        var time = Date.parseDate(value, this.format);
        return Ext.isDate(time);
    },
    
    getValue: function() {
        var value = Tine.Timetracker.DurationSpinner.superclass.getValue.call(this);
        value = value.replace(',', '.');
        
        if(value && typeof value == 'string') {
        	if (value.search(/:/) != -1) {
                var parts = value.split(':');
                parts[0] = parts[0].length == 1 ? '0' + parts[0] : parts[0];
                parts[1] = parts[1].length == 1 ? '0' + parts[1] : parts[1];
                value = parts.join(':');
                
                var time = Date.parseDate(value, this.format);
                if (! time) {
                    this.markInvalid(_('Not a valid time'));
                    return;
                } else {
                    value = time.getHours() * 60 + time.getMinutes();
                }
        	} else if (value > 0) {
                if (value < 24) {
                    value = value * 60;
                }
        	} else {
                this.markInvalid(_('Not a valid time'));
                return;
            }
        }
        this.setValue(value);        
        return value;
    }
});

Ext.reg('tinedurationspinner', Tine.Timetracker.DurationSpinner);
// file: /home/lkneschke/temp/tine20build/temp/tine20/Timetracker/js/Models.js
/**
 * Tine 2.0
 * 
 * @package     Timetracker
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Cornelius Weiss <c.weiss@metaways.de>
 * @copyright   Copyright (c) 2007-2008 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: Models.js 15793 2010-08-11 09:02:52Z p.schuele@metaways.de $
 *
 */
 
Ext.ns('Tine.Timetracker', 'Tine.Timetracker.Model');

/**
 * @type {Array}
 * Timesheet model fields
 */
Tine.Timetracker.Model.TimesheetArray = Tine.Tinebase.Model.genericFields.concat([
    { name: 'id' },
    { name: 'account_id' },
    { name: 'timeaccount_id' },
    { name: 'start_date', type: 'date', dateFormat: Date.patterns.ISO8601Short},
    { name: 'start_time', type: 'date', dateFormat: Date.patterns.ISO8601Time },
    { name: 'duration' },
    { name: 'description' },
    { name: 'is_billable' },
    { name: 'is_billable_combined' }, // ts & ta is_billable
    { name: 'is_cleared' },
    { name: 'is_cleared_combined' }, // ts is_cleared & ta status == 'billed'
    { name: 'billed_in' },
    // tine 2.0 notes + tags
    { name: 'notes'},
    { name: 'tags' },
    { name: 'customfields'}
]);

/**
 * @type {Tine.Tinebase.data.Record}
 * Timesheet record definition
 */
Tine.Timetracker.Model.Timesheet = Tine.Tinebase.data.Record.create(Tine.Timetracker.Model.TimesheetArray, {
    appName: 'Timetracker',
    modelName: 'Timesheet',
    idProperty: 'id',
    titleProperty: null,
    // ngettext('Timesheet', 'Timesheets', n);
    recordName: 'Timesheet',
    recordsName: 'Timesheets',
    containerProperty: 'timeaccount_id',
    // ngettext('timesheets list', 'timesheets lists', n);
    containerName: 'timesheets list',
    containersName: 'timesheets lists',
    getTitle: function() {
        var timeaccount = this.get('timeaccount_id');
        if (timeaccount) {
            if (typeof(timeaccount.get) !== 'function') {
                timeaccount = new Tine.Timetracker.Model.Timeaccount(timeaccount);
            }
            return timeaccount.getTitle();
        }
    },
    copyOmitFields: ['billed_in', 'is_cleared']
});
Tine.Timetracker.Model.Timesheet.getDefaultData = function() { 
    return {
        account_id: Tine.Tinebase.registry.get('currentAccount'),
        duration:   '00:30',
        start_date: new Date(),
        is_billable: true,
        timeaccount_id: {account_grants: {editGrant: true}}
    };
};

/**
 * @type {Array}
 * Timeaccount model fields
 */
Tine.Timetracker.Model.TimeaccountArray = Tine.Tinebase.Model.genericFields.concat([
    { name: 'id' },
    { name: 'container_id' },
    { name: 'title' },
    { name: 'number' },
    { name: 'description' },
    { name: 'budget' },
    { name: 'budget_unit' },
    { name: 'price' },
    { name: 'price_unit' },
    { name: 'is_open' },
    { name: 'is_billable' },
    { name: 'billed_in' },
    { name: 'status' },
    { name: 'deadline' },
    { name: 'account_grants'},
    { name: 'grants'},
    // tine 2.0 notes + tags
    { name: 'notes'},
    { name: 'tags' }
]);

/**
 * @type {Tine.Tinebase.data.Record}
 * Timesheet record definition
 */
Tine.Timetracker.Model.Timeaccount = Tine.Tinebase.data.Record.create(Tine.Timetracker.Model.TimeaccountArray, {
    appName: 'Timetracker',
    modelName: 'Timeaccount',
    idProperty: 'id',
    titleProperty: 'title',
    // ngettext('Time Account', 'Time Accounts', n);
    recordName: 'Time Account',
    recordsName: 'Time Accounts',
    containerProperty: 'container_id',
    // ngettext('timeaccount list', 'timeaccount lists', n);
    containerName: 'timeaccount list',
    containersName: 'timeaccount lists',
    getTitle: function() {
        return this.get('number') ? (this.get('number') + ' ' + this.get('title')) : false;
    }
});

Tine.Timetracker.Model.Timeaccount.getDefaultData = function() { 
    return {
        is_open: 1,
        is_billable: true
    };
};

Tine.Timetracker.Model.Timeaccount.getFilterModel = function() {
    var app = Tine.Tinebase.appMgr.get('Timetracker');
    return [
        {label: _('Quick search'),          field: 'query',       operators: ['contains']},
        {label: app.i18n._('Number'),       field: 'number'       },
        {label: app.i18n._('Title'),        field: 'title'        },
        {label: app.i18n._('Description'),  field: 'description', operators: ['contains']},
        {label: app.i18n._('Created By'),   field: 'created_by',  valueType: 'user'},
        {label: app.i18n._('Status'),       field: 'status',      filtertype: 'timetracker.timeaccountstatus'},
        {filtertype: 'tinebase.tag', app: app}
    ];
}
/**
 * filter model for timeaccounts
 *
Tine.Timetracker.Model.TimeaccountFilter = [
    {field: 'query',        filter: Tine.Tinebase.Model.filter.Query},
    {field: 'tags',         filter: Tine.Tinebase.Model.filter.Tag, options: {appName: 'Timetracker'} },
    {field: 'description',  filter: Tine.Tinebase.Model.filter.Text, options: {operators: ['contains']} },
    {field: 'created_by',   filter: Tine.Tinebase.Model.filter.User},
    {field: 'status',       filter: Tine.Timetracker.TimeAccountStatusGridFilter}
];
*/

/**
 * Model of a grant
 */
Tine.Timetracker.Model.TimeaccountGrant = Ext.data.Record.create([
    {name: 'id'},
    {name: 'account_id'},
    {name: 'account_type'},
    {name: 'account_name'},
    {name: 'book_own',        type: 'boolean'},
    {name: 'view_all',        type: 'boolean'},
    {name: 'book_all',        type: 'boolean'},
    {name: 'manage_billable', type: 'boolean'},
    {name: 'exportGrant',     type: 'boolean'},
    {name: 'manage_all',      type: 'boolean'}
]);

// file: /home/lkneschke/temp/tine20build/temp/tine20/Timetracker/js/Timetracker.js
/*
 * Tine 2.0
 * 
 * @package     Timetracker
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Philipp Schuele <p.schuele@metaways.de>
 * @copyright   Copyright (c) 2007-2010 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: Timetracker.js 14269 2010-05-10 07:28:41Z c.weiss@metaways.de $
 */
 
Ext.ns('Tine.Timetracker');


/**
 * @namespace   Tine.Timetracker
 * @class       Tine.Timetracker.MainScreen
 * @extends     Tine.widgets.MainScreen
 * MainScreen of the Timetracker Application <br>
 * 
 * @author      Cornelius Weiss <c.weiss@metaways.de>
 * @version     $Id: Timetracker.js 14269 2010-05-10 07:28:41Z c.weiss@metaways.de $
 * 
 * @constructor
 */
Tine.Timetracker.MainScreen = Ext.extend(Tine.widgets.MainScreen, {
    activeContentType: 'Timesheet',
    westPanelXType: 'tine.timetracker.treepanel'
});

/**
 * @namespace   Tine.Timetracker
 * @class       Tine.Timetracker.TreePanel
 * @extends     Tine.widgets.persistentfilter.PickerPanel
 * 
 * @author      Philipp Schuele <p.schuele@metaways.de>
 * @version     $Id: Timetracker.js 14269 2010-05-10 07:28:41Z c.weiss@metaways.de $
 * 
 * @constructor
 * @xtype       tine.timetracker.treepanel
 */
Tine.Timetracker.TreePanel = Ext.extend(Tine.widgets.persistentfilter.PickerPanel, {
    
    filter: [{field: 'model', operator: 'equals', value: 'Timetracker_Model_TimesheetFilter'}],
    
    // quick hack to get filter saving grid working
    //recordClass: Tine.Timetracker.Model.Timesheet,
    initComponent: function() {
        this.filterMountId = 'Timesheet';
        
        this.root = {
            id: 'root',
            leaf: false,
            expanded: true,
            children: [{
                text: this.app.i18n._('Timesheets'),
                id : 'Timesheet',
                iconCls: 'TimetrackerTimesheet',
                expanded: true,
                children: [{
                    text: this.app.i18n._('All Timesheets'),
                    id: 'alltimesheets',
                    leaf: true
                }]
            }, {
                text: this.app.i18n._('Timeaccounts'),
                id: 'Timeaccount',
                iconCls: 'TimetrackerTimeaccount',
                expanded: true,
                children: [{
                    text: this.app.i18n._('All Timeaccounts'),
                    id: 'alltimeaccounts',
                    leaf: true
                }]
            }]
        };
        
    	Tine.Timetracker.TreePanel.superclass.initComponent.call(this);
        
        this.on('click', function(node) {
            if (node.attributes.isPersistentFilter != true) {
                var contentType = node.getPath().split('/')[2];
                
                this.app.getMainScreen().activeContentType = contentType;
                this.app.getMainScreen().show();
            }
        }, this);
	},
    
    /**
     * @private
     */
    afterRender: function() {
        Tine.Timetracker.TreePanel.superclass.afterRender.call(this);
        var type = this.app.getMainScreen().activeContentType;

        this.expandPath('/root/' + type + '/alltimesheets');
        this.selectPath('/root/' + type + '/alltimesheets');
    },
    
    /**
     * load grid from saved filter
     */
    onFilterSelect: function() {
        this.app.getMainScreen().activeContentType = 'Timesheet';
        this.app.getMainScreen().show();
        
        this.supr().onFilterSelect.apply(this, arguments);
    },
    
    /**
     * returns a filter plugin to be used in a grid
     */
    getFilterPlugin: function() {
        if (!this.filterPlugin) {
            var scope = this;
            this.filterPlugin = new Tine.widgets.grid.FilterPlugin({});
        }
        
        return this.filterPlugin;
    },
    
    getFavoritesPanel: function() {
        return this;
    }
});

Ext.reg('tine.timetracker.treepanel', Tine.Timetracker.TreePanel);


/**
 * default timesheets backend
 */
Tine.Timetracker.timesheetBackend = new Tine.Tinebase.data.RecordProxy({
    appName: 'Timetracker',
    modelName: 'Timesheet',
    recordClass: Tine.Timetracker.Model.Timesheet
});

/**
 * default timeaccounts backend
 */
Tine.Timetracker.timeaccountBackend = new Tine.Tinebase.data.RecordProxy({
    appName: 'Timetracker',
    modelName: 'Timeaccount',
    recordClass: Tine.Timetracker.Model.Timeaccount
});
// file: /home/lkneschke/temp/tine20build/temp/tine20/Timetracker/js/TimeaccountSelect.js
/**
 * Tine 2.0
 * 
 * @package     Timetracker
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Cornelius Weiss <c.weiss@metaways.de>
 * @copyright   Copyright (c) 2007-2008 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: TimeaccountSelect.js 15995 2010-08-24 13:56:18Z p.schuele@metaways.de $
 *
 */
 
Ext.ns('Tine.Timetracker');

Tine.Timetracker.TimeAccountSelect = Ext.extend(Ext.form.ComboBox, {
    
    /**
     * @cfg {Ext.data.DataProxy} recordProxy
     */
    recordProxy: Tine.Timetracker.timeaccountBackend,
    /**
     * @cfg {Bool} onlyBookable
     * only show bookable TA's
     */
    onlyBookable: true,
    /**
     * @cfg {Bool} showClosed
     * also show closed TA's
     */
    showClosed: false,
    /**
     * @cfg {bool} blurOnSelect blurs combobox when item gets selected
     */
    blurOnSelect: false,
    /**
     * @cfg {Object} defaultPaging 
     */
    defaultPaging: {
        start: 0,
        limit: 50
    },
    
    /**
     * @property {Tine.Timetracker.Model.Timeaccount} record
     */
    record: null,
    
    itemSelector: 'div.search-item',
    typeAhead: false,
    minChars: 3,
    pageSize:10,
    forceSelection: true,
    displayField: 'displaytitle',
    triggerAction: 'all',
    selectOnFocus: true,
    
    /**
     * @private
     */
    initComponent: function() {
        this.app = Tine.Tinebase.appMgr.get('Timetracker');
        
        this.store = new Ext.data.Store({
            fields: Tine.Timetracker.Model.TimeaccountArray.concat({name: 'displaytitle'}),
            proxy: this.recordProxy,
            reader: this.recordProxy.getReader(),
            remoteSort: true,
            sortInfo: {field: 'number', dir: 'ASC'},
            listeners: {
                scope: this,
                //'update': this.onStoreUpdate,
                'beforeload': this.onStoreBeforeload
            }
        });
        
        this.tpl = new Ext.XTemplate(
            '<tpl for="."><div class="search-item">',
                '<span>' +
                    '{[this.encode(values.number)]} - {[this.encode(values.title)]}' +
                    '<tpl if="is_open != 1 ">&nbsp;<i>(' + this.app.i18n._('closed') + ')</i></tpl>',
                '</span>' +
                //'{[this.encode(values.description)]}' +
            '</div></tpl>',
            {
                encode: function(value) {
                     if (value) {
                        return Ext.util.Format.htmlEncode(value);
                    } else {
                        return '';
                    }
                }
            }
        );
        
        Tine.Timetracker.TimeAccountSelect.superclass.initComponent.call(this);
        
        if (this.blurOnSelect){
            this.on('select', function(){
                this.fireEvent('blur', this);
            }, this);
        }
    },
    
    getValue: function() {
        return this.record ? this.record.get('id') : null;
    },
    
    setValue: function(value) {
        if (value) {
            if (typeof(value.get) == 'function') {
                this.record = value;
                
            } else if (typeof(value) == 'string') {
                // NOTE: the string also could be the string for the display field!!!
                //console.log('id');
                
            } else {
                // we try raw data
                this.record = new Tine.Timetracker.Model.Timeaccount(value, value.id);
            }
            
            var title = this.record ? this.record.getTitle() : false;
            if (title) {
                Tine.Timetracker.TimeAccountSelect.superclass.setValue.call(this, title);
            }
        }
    },
    
    onSelect: function(record){
        record.set('displaytitle', record.getTitle());
        this.record = record;
        
        Tine.Timetracker.TimeAccountSelect.superclass.onSelect.call(this, record);
    },
        
    /**
     * @private
     */
    onStoreBeforeload: function(store, options) {
        options.params = options.params || {};
        
        options.params.filter = [
            {field: 'query', operator: 'contains', value: store.baseParams.query}
        ];
        
        if (this.onlyBookable) {
            options.params.filter.push({field: 'isBookable', operator: 'equals', value: 1 });
        }
        
        if (this.showClosed) {
            options.params.filter.push({field: 'showClosed', operator: 'equals', value: 1 });
        }
    }
});

Tine.Timetracker.TimeAccountGridFilter = Ext.extend(Tine.widgets.grid.FilterModel, {
    isForeignFilter: true,
    foreignField: 'id',
    ownField: 'timeaccount_id',
    
    /**
     * @private
     */
    initComponent: function() {
        Tine.widgets.tags.TagFilter.superclass.initComponent.call(this);
        
        this.subFilterModels = [];
        
        this.app = Tine.Tinebase.appMgr.get('Timetracker');
        this.label = this.app.i18n._("Time Account");
        this.operators = ['equals'];
    },
    
    getSubFilters: function() {
        var filterConfigs = Tine.Timetracker.Model.Timeaccount.getFilterModel();
        Ext.each(filterConfigs, function(config) {
            if (config.field != 'query') {
                this.subFilterModels.push(Tine.widgets.grid.FilterToolbar.prototype.createFilterModel.call(this, config));
            }
        }, this);
        
        return this.subFilterModels;
    },
    
    /**
     * value renderer
     * 
     * @param {Ext.data.Record} filter line
     * @param {Ext.Element} element to render to 
     */
    valueRenderer: function(filter, el) {
        // value
        var value = new Tine.Timetracker.TimeAccountSelect({
            filter: filter,
            onlyBookable: false,
            showClosed: true,
            blurOnSelect: true,
            width: 200,
            listWidth: 500,
            id: 'tw-ftb-frow-valuefield-' + filter.id,
            value: filter.data.value ? filter.data.value : this.defaultValue,
            renderTo: el
        });
        value.on('specialkey', function(field, e){
             if(e.getKey() == e.ENTER){
                 this.onFiltertrigger();
             }
        }, this);
        //value.on('select', this.onFiltertrigger, this);
        
        return value;
    }
});
Tine.widgets.grid.FilterToolbar.FILTERS['timetracker.timeaccount'] = Tine.Timetracker.TimeAccountGridFilter;

Tine.Timetracker.TimeAccountStatusGridFilter = Ext.extend(Tine.widgets.grid.FilterModel, {
	field: 'timeaccount_status',
    valueType: 'string',
    defaultValue: 'to bill',
    
    /**
     * @private
     */
    initComponent: function() {
        Tine.Timetracker.TimeAccountStatusGridFilter.superclass.initComponent.call(this);
        
        this.app = Tine.Tinebase.appMgr.get('Timetracker');
        this.label = this.label ? this.label : this.app.i18n._("Time Account - Status");
        this.operators = ['equals'];
    },
   
    /**
     * value renderer
     * 
     * @param {Ext.data.Record} filter line
     * @param {Ext.Element} element to render to 
     */
    valueRenderer: function(filter, el) {
        // value
        var value = new Ext.form.ComboBox({
            filter: filter,
            width: 200,
            id: 'tw-ftb-frow-valuefield-' + filter.id,
            value: filter.data.value ? filter.data.value : this.defaultValue,
            renderTo: el,
            mode: 'local',
            forceSelection: true,
            blurOnSelect: true,
            triggerAction: 'all',
            store: [
                ['not yet billed', this.app.i18n._('not yet billed')], 
                ['to bill', this.app.i18n._('to bill')],
                ['billed', this.app.i18n._('billed')]
            ]
        });
        value.on('specialkey', function(field, e){
             if(e.getKey() == e.ENTER){
                 this.onFiltertrigger();
             }
        }, this);
        
        return value;
    }
});
Tine.widgets.grid.FilterToolbar.FILTERS['timetracker.timeaccountstatus'] = Tine.Timetracker.TimeAccountStatusGridFilter;

// file: /home/lkneschke/temp/tine20build/temp/tine20/Timetracker/js/TimeaccountGridPanel.js
/*
 * Tine 2.0
 * 
 * @package     Timetracker
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Philipp Schuele <p.schuele@metaways.de>
 * @copyright   Copyright (c) 2007-2009 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: TimeaccountGridPanel.js 15638 2010-07-30 17:11:03Z p.schuele@metaways.de $
 *
 */
 
Ext.namespace('Tine.Timetracker');

/**
 * Timeaccount grid panel
 * 
 * @namespace   Tine.Timetracker
 * @class       Tine.Timetracker.TimeaccountGridPanel
 * @extends     Tine.widgets.grid.GridPanel
 * 
 * <p>Timeaccount Grid Panel</p>
 * <p><pre>
 * TODO         copy action needs to copy the acl too
 * </pre></p>
 * 
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Philipp Schuele <p.schuele@metaways.de>
 * @copyright   Copyright (c) 2007-2009 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: TimeaccountGridPanel.js 15638 2010-07-30 17:11:03Z p.schuele@metaways.de $
 * 
 * @param       {Object} config
 * @constructor
 * Create a new Tine.Timetracker.TimeaccountGridPanel
 */
Tine.Timetracker.TimeaccountGridPanel = Ext.extend(Tine.widgets.grid.GridPanel, {
    // model generics
    recordClass: Tine.Timetracker.Model.Timeaccount,
    
    // grid specific
    defaultSortInfo: {field: 'creation_time', direction: 'DESC'},
    gridConfig: {
        loadMask: true,
        autoExpandColumn: 'title'
    },
    copyEditAction: true,
    
    initComponent: function() {
        this.recordProxy = Tine.Timetracker.timeaccountBackend;
        
        this.actionToolbarItems = this.getToolbarItems();
        this.gridConfig.cm = this.getColumnModel();
        this.initFilterToolbar();
        
        this.plugins = this.plugins || [];
        this.plugins.push(this.action_showClosedToggle, this.filterToolbar);        
        
        Tine.Timetracker.TimeaccountGridPanel.superclass.initComponent.call(this);
        
        this.action_addInNewWindow.setDisabled(! Tine.Tinebase.common.hasRight('manage', 'Timetracker', 'timeaccounts'));
        this.action_editInNewWindow.requiredGrant = 'editGrant';
        
    },
    
    /**
     * initialises filter toolbar
     * 
     * TODO created_by filter should be replaced by a 'responsible/organizer' filter like in tasks
     */
    initFilterToolbar: function() {
        this.filterToolbar = new Tine.widgets.grid.FilterToolbar({
            app: this.app,
            filterModels: Tine.Timetracker.Model.Timeaccount.getFilterModel(),
            defaultFilter: 'query',
            filters: [],
            plugins: [
                new Tine.widgets.grid.FilterToolbarQuickFilterPlugin()
            ]
        });
    },    
    
    /**
     * returns cm
     * 
     * @return Ext.grid.ColumnModel
     * @private
     */
    getColumnModel: function(){
        return new Ext.grid.ColumnModel({ 
            defaults: {
                sortable: true,
                resizable: true
            },
            columns: [
            {   id: 'tags', header: this.app.i18n._('Tags'), width: 50,  dataIndex: 'tags', sortable: false, renderer: Tine.Tinebase.common.tagsRenderer },
            {
                id: 'number',
                header: this.app.i18n._("Number"),
                width: 100,
                dataIndex: 'number'
            },{
                id: 'title',
                header: this.app.i18n._("Title"),
                width: 350,
                dataIndex: 'title'
            },{
                id: 'status',
                header: this.app.i18n._("Status"),
                width: 150,
                dataIndex: 'status',
                renderer: this.statusRenderer.createDelegate(this)
            },{
                id: 'budget',
                header: this.app.i18n._("Budget"),
                width: 100,
                dataIndex: 'budget'
            },{
                id: 'billed_in',
                hidden: true,
                header: this.app.i18n._("Cleared in"),
                width: 150,
                dataIndex: 'billed_in'
            }]
        });
    },
    
    /**
     * status column renderer
     * @param {string} value
     * @return {string}
     */
    statusRenderer: function(value) {
        return this.app.i18n._hidden(value);
    },
    
    /**
     * return additional tb items
     */
    getToolbarItems: function(){
        this.exportButton = new Ext.Action({
            text: _('Export'),
            iconCls: 'action_export',
            scope: this,
            requiredGrant: 'readGrant',
            disabled: true,
            allowMultiple: true,
            menu: {
                items: [
                    new Tine.widgets.grid.ExportButton({
                        text: this.app.i18n._('Export as ODS'),
                        format: 'ods',
                        exportFunction: 'Timetracker.exportTimeaccounts',
                        gridPanel: this
                    })
                    /*,
                    new Tine.widgets.grid.ExportButton({
                        text: this.app.i18n._('Export as CSV'),
                        format: 'csv',
                        exportFunction: 'Timetracker.exportTimesheets',
                        gridPanel: this
                    })
                    */
                ]
            }
        });
    	
        this.action_showClosedToggle = new Tine.widgets.grid.FilterButton({
            text: this.app.i18n._('Show closed'),
            iconCls: 'action_showArchived',
            field: 'showClosed',
            scale: 'medium',
            rowspan: 2,
            iconAlign: 'top'
        });
        
        return [
            Ext.apply(new Ext.Button(this.exportButton), {
                scale: 'medium',
                rowspan: 2,
                iconAlign: 'top'
            }),
            this.action_showClosedToggle
        ];
    }    
});

// file: /home/lkneschke/temp/tine20build/temp/tine20/Timetracker/js/TimeaccountEditDialog.js
/**
 * Tine 2.0
 * 
 * @package     Timetracker
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Cornelius Weiss <c.weiss@metaways.de>
 * @copyright   Copyright (c) 2007-2009 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: TimeaccountEditDialog.js 15210 2010-06-30 11:50:50Z c.weiss@metaways.de $
 *
 */
 
Ext.namespace('Tine.Timetracker');

Tine.Timetracker.TimeaccountEditDialog = Ext.extend(Tine.widgets.dialog.EditDialog, {
    
    /**
     * @private
     */
    windowNamePrefix: 'TimeaccountEditWindow_',
    appName: 'Timetracker',
    recordClass: Tine.Timetracker.Model.Timeaccount,
    recordProxy: Tine.Timetracker.timeaccountBackend,
    loadRecord: false,
    tbarItems: [{xtype: 'widget-activitiesaddbutton'}],
    
    /**
     * overwrite update toolbars function (we don't have record grants yet)
     */
    updateToolbars: function() {

    },
    
    onRecordLoad: function() {
        // make sure grants grid is initialised
        this.getGrantsGrid();
        
        var grants = this.record.get('grants') || [];
        this.grantsStore.loadData({results: grants});
        Tine.Timetracker.TimeaccountEditDialog.superclass.onRecordLoad.call(this);
        
    },
    
    onRecordUpdate: function() {
        Tine.Timetracker.TimeaccountEditDialog.superclass.onRecordUpdate.call(this);
        this.record.set('grants', '');
        
        var grants = [];
        this.grantsStore.each(function(_record){
            grants.push(_record.data);
        });
        
        this.record.set('grants', grants);
    },
    
    /**
     * returns dialog
     * 
     * NOTE: when this method gets called, all initalisation is done.
     */
    getFormItems: function() {
        return {
            xtype: 'tabpanel',
            border: false,
            plain:true,
            activeTab: 0,
            items:[{               
                title: this.app.i18n._('Time Account'),
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
                        fieldLabel: this.app.i18n._('Number'),
                        name: 'number',
                        allowBlank: false
                        }, {
                        columnWidth: .666,
                        fieldLabel: this.app.i18n._('Title'),
                        name: 'title',
                        allowBlank: false
                        }], [{
                        columnWidth: 1,
                        fieldLabel: this.app.i18n._('Description'),
                        xtype: 'textarea',
                        name: 'description',
                        height: 150
                        }], [{
                            fieldLabel: this.app.i18n._('Unit'),
                            name: 'price_unit'
                        }, {
                            xtype: 'numberfield',
                            fieldLabel: this.app.i18n._('Unit Price'),
                            name: 'price',
                            allowNegative: false
                            //decimalSeparator: ','
                        }, {
                            fieldLabel: this.app.i18n._('Budget'),
                            name: 'budget'
                        }, {
                            fieldLabel: this.app.i18n._('Status'),
                            name: 'is_open',
                            xtype: 'combo',
                            mode: 'local',
                            forceSelection: true,
                            triggerAction: 'all',
                            store: [[0, this.app.i18n._('closed')], [1, this.app.i18n._('open')]]
                        }, {
                            fieldLabel: this.app.i18n._('Billed'),
                            name: 'status',
                            xtype: 'combo',
                            mode: 'local',
                            forceSelection: true,
                            triggerAction: 'all',
                            value: 'not yet billed',
                            store: [
                                ['not yet billed', this.app.i18n._('not yet billed')], 
                                ['to bill', this.app.i18n._('to bill')],
                                ['billed', this.app.i18n._('billed')]
                            ]
                        }, {
                            //disabled: true,
                            //emptyText: this.app.i18n._('not cleared yet...'),
                            fieldLabel: this.app.i18n._('Cleared In'),
                            name: 'billed_in',
                            xtype: 'textfield'
                        }, {
                            fieldLabel: this.app.i18n._('Booking deadline'),
                            name: 'deadline',
                            xtype: 'combo',
                            mode: 'local',
                            forceSelection: true,
                            triggerAction: 'all',
                            value: 'none',
                            store: [
                                ['none', this.app.i18n._('none')], 
                                ['lastweek', this.app.i18n._('last week')]
                            ]
                        }, {
                            hideLabel: true,
                            boxLabel: this.app.i18n._('Timesheets are billable'),
                            name: 'is_billable',
                            xtype: 'checkbox',
                            columnWidth: .666
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
                    items: [/*new Ext.Panel({
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
                            emptyText: this.app.i18n._('Enter description')                            
                        }]
                    }),*/
                    new Tine.widgets.activities.ActivitiesPanel({
                        app: 'Timetracker',
                        showAddNoteForm: false,
                        border: false,
                        bodyStyle: 'border:1px solid #B5B8C8;'
                    }),
                    new Tine.widgets.tags.TagPanel({
                        app: 'Timetracker',
                        border: false,
                        bodyStyle: 'border:1px solid #B5B8C8;'
                    })]
                }]
            },{
                title: this.app.i18n._('Access'),
                layout: 'fit',
                items: [this.getGrantsGrid()]
            }, new Tine.widgets.activities.ActivitiesTabPanel({
                app: this.appName,
                record_id: this.record.id,
                record_model: this.appName + '_Model_' + this.recordClass.getMeta('modelName')
            })]
        };
    },
    
    getGrantsGrid: function() {
        if (! this.grantsGrid) {
            this.grantsStore =  new Ext.data.JsonStore({
                root: 'results',
                totalProperty: 'totalcount',
                //id: 'id',
                // use account_id here because that simplifies the adding of new records with the search comboboxes
                id: 'account_id',
                fields: Tine.Timetracker.Model.TimeaccountGrant
            });
            
            var columns = [
                new Ext.ux.grid.CheckColumn({
                    header: this.app.i18n._('Book Own'),
                    dataIndex: 'book_own',
                    tooltip: _('The grant to add Timesheets to this Timeaccount'),
                    width: 55
                }),
                new Ext.ux.grid.CheckColumn({
                    header: this.app.i18n._('View All'),
                    tooltip: _('The grant to view Timesheets of other users'),
                    dataIndex: 'view_all',
                    width: 55
                }),
                new Ext.ux.grid.CheckColumn({
                    header: this.app.i18n._('Book All'),
                    tooltip: _('The grant to add Timesheets for other users'),
                    dataIndex: 'book_all',
                    width: 55
                }),
                new Ext.ux.grid.CheckColumn({
                    header:this.app.i18n. _('Manage Clearing'),
                    tooltip: _('The grant to manage clearing of Timesheets'),
                    dataIndex: 'manage_billable',
                    width: 55
                }),
                new Ext.ux.grid.CheckColumn({
                    header:this.app.i18n. _('Export'),
                    tooltip: _('The grant to export Timesheets of Timeaccount'),
                    dataIndex: 'exportGrant',
                    width: 55
                }),
                new Ext.ux.grid.CheckColumn({
                    header: this.app.i18n._('Manage All'),
                    tooltip: _('Includes all other grants'),
                    dataIndex: 'manage_all',
                    width: 55
                })
            ];
            
            this.grantsGrid = new Tine.widgets.account.PickerGridPanel({
                selectType: 'both',
                title:  this.app.i18n._('Permissions'),
                store: this.grantsStore,
                hasAccountPrefix: true,
                configColumns: columns,
                selectAnyone: false,
                selectTypeDefault: 'group',
                recordClass: Tine.Tinebase.Model.Grant
            }); 
        }
        return this.grantsGrid;
    }
});

/**
 * Timetracker Edit Popup
 */
Tine.Timetracker.TimeaccountEditDialog.openWindow = function (config) {
    var id = (config.record && config.record.id) ? config.record.id : 0;
    var window = Tine.WindowFactory.getWindow({
        width: 800,
        height: 500,
        name: Tine.Timetracker.TimeaccountEditDialog.prototype.windowNamePrefix + id,
        contentPanelConstructor: 'Tine.Timetracker.TimeaccountEditDialog',
        contentPanelConstructorConfig: config
    });
    return window;
};
// file: /home/lkneschke/temp/tine20build/temp/tine20/Timetracker/js/TimesheetGridPanel.js
﻿/*
 * Tine 2.0
 * 
 * @package     Timetracker
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Philipp Schüle <p.schuele@metaways.de>
 * @copyright   Copyright (c) 2007-2011 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: TimesheetGridPanel.js 18868 2011-01-26 10:01:42Z p.schuele@metaways.de $
 *
 */
 
Ext.namespace('Tine.Timetracker');

/**
 * Timesheet grid panel
 * 
 * @namespace   Tine.Timetracker
 * @class       Tine.Timetracker.TimesheetGridPanel
 * @extends     Tine.widgets.grid.GridPanel
 * 
 * <p>Timesheet Grid Panel</p>
 * <p><pre>
 * </pre></p>
 * 
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Philipp Schüle <p.schuele@metaways.de>
 * @version     $Id: TimesheetGridPanel.js 18868 2011-01-26 10:01:42Z p.schuele@metaways.de $
 * 
 * @param       {Object} config
 * @constructor
 * Create a new Tine.Timetracker.TimesheetGridPanel
 */
Tine.Timetracker.TimesheetGridPanel = Ext.extend(Tine.widgets.grid.GridPanel, {
    /**
     * record class
     * @cfg {Tine.Timetracker.Model.Timesheet} recordClass
     */
    recordClass: Tine.Timetracker.Model.Timesheet,
    
    /**
     * @private grid cfg
     */
    defaultSortInfo: {field: 'start_date', direction: 'DESC'},
    gridConfig: {
        loadMask: true,
        autoExpandColumn: 'description'
    },
    copyEditAction: true,
    
    /**
     * @private
     */
    initComponent: function() {
        this.recordProxy = Tine.Timetracker.timesheetBackend;
                
        this.gridConfig.cm = this.getColumnModel();
        this.initFilterToolbar();
        this.initDetailsPanel();
        
        this.plugins = this.plugins || [];
        this.plugins.push(this.filterToolbar);
        
        // only eval grants in action updater if user does not have the right to manage timeaccounts
        this.evalGrants = ! Tine.Tinebase.common.hasRight('manage', 'Timetracker', 'timeaccounts');
        
        Tine.Timetracker.TimesheetGridPanel.superclass.initComponent.call(this);
    },
    
    /**
     * onMassUpdate (Quick hack for mass update, to be generalized!!!)
     * 
     * @param {Button} btn
     * @param {Event} e
     */ 
    onMassUpdate: function(btn, e) {
        var input;
        
        switch (btn.field) {
            case 'is_billable':
            case 'is_cleared':
//                input = new Ext.form.ComboBox({
//                    fieldLabel: btn.text,
//                    name: btn.field,
//                    width: 40,
//                    mode: 'local',
//                    forceSelection: true,
//                    triggerAction: 'all',
//                    store: [
//                        [0, Locale.getTranslationData('Question', 'no').replace(/:.*/, '')], 
//                        [1, Locale.getTranslationData('Question', 'yes').replace(/:.*/, '')]
//                    ]
//                });
                    input = new Ext.form.Checkbox({
                        hideLabel: true,
                        boxLabel: btn.text,
                        name: btn.field
                    });
                break;
            default:
                input = new Ext.form.TextField({
                    fieldLabel: btn.text,
                    name: btn.field
                });
        }
        
        var sm = this.grid.getSelectionModel();
        var filter = sm.getSelectionFilter();
        
        var updateForm = new Ext.FormPanel({
            border: false,
            labelAlign: 'top',
            buttonAlign: 'right',
            items: input,
            defaults: {
                anchor: '90%'
            }
        });
        var win = Tine.WindowFactory.getWindow({
            title: String.format(_('Update {0} records'), sm.getCount()),
            modal: true,
            width: 300,
            height: 150,
            layout: 'fit',
            plain: true,
            closeAction: 'close',
            autoScroll: true,
            items: updateForm,
            buttons: [{
                text: _('Cancel'),
                iconCls: 'action_cancel',
                handler: function() {
                    win.close();
                }
            }, {
                text: _('Ok'),
                iconCls: 'action_saveAndClose',
                scope: this,
                handler: function() {
                    var field = input.name,
                        value = input.getValue();
                        update = {},
                    update[field] = value;
                    
                    win.close();
                    this.grid.loadMask.show();
                    
                    // some adjustments
                    if (field == 'is_cleared' && !update[field]) {
                        // reset billed_in field
                        update.billed_in = '';
                    }
                    if (field == 'billed_in' && update[field].length > 0) {
                        // set is cleard dynamically
                        update.is_cleared = true;
                    }
                    
                    this.recordProxy.updateRecords(filter, update, {
                        scope: this,
                        success: function(response) {
                            this.store.load();
                            
                            Ext.Msg.show({
                               title: _('Success'),
                               msg: String.format(_('Updated {0} records'), response.count),
                               buttons: Ext.Msg.OK,
                               animEl: 'elId',
                               icon: Ext.MessageBox.INFO
                            });
                        }
                    });
                }
            }]
        });
    },
    // END OF QUICK HACK
    
    /**
     * initialises filter toolbar
     * @private
     */
    initFilterToolbar: function() {
        this.filterToolbar = new Tine.widgets.grid.FilterToolbar({
            app: this.app,
            allowSaving: true,
            filterModels: [
                //{label: _('Quick search'),    field: 'query',    operators: ['contains']}, // query only searches description
                {label: this.app.i18n._('Account'),      field: 'account_id', valueType: 'user'},
                {label: this.app.i18n._('Date'),         field: 'start_date', valueType: 'date', pastOnly: true},
                {label: this.app.i18n._('Description'),  field: 'description', defaultOperator: 'contains'},
                {label: this.app.i18n._('Billable'),     field: 'is_billable', valueType: 'bool', defaultValue: true },
                {label: this.app.i18n._('Cleared'),      field: 'is_cleared',  valueType: 'bool', defaultValue: false },
                {filtertype: 'tinebase.tag', app: this.app},
                {filtertype: 'timetracker.timeaccount'}
             ].concat(this.getCustomfieldFilters()),
             defaultFilter: 'start_date',
             filters: [
                {field: 'start_date', operator: 'within', value: 'weekThis'},
                {field: 'account_id', operator: 'equals', value: Tine.Tinebase.registry.get('currentAccount')}
             ]
        });
    },    
    
    /**
     * returns cm
     * 
     * @return Ext.grid.ColumnModel
     * @private
     */
    getColumnModel: function(){
        var columns = [
            { id: 'tags',               header: this.app.i18n._('Tags'),                width: 50,  dataIndex: 'tags', sortable: false,
                renderer: Tine.Tinebase.common.tagsRenderer },
            { id: 'start_date',         header: this.app.i18n._("Date"),                width: 120, dataIndex: 'start_date',            
                renderer: Tine.Tinebase.common.dateRenderer },
            { id: 'start_time',         header: this.app.i18n._("Start time"),          width: 100, dataIndex: 'start_time',            hidden: true,            
                renderer: Tine.Tinebase.common.timeRenderer },
            { id: 'timeaccount_id',     header: this.app.i18n._("Time Account"),        width: 500, dataIndex: 'timeaccount_id',        
                renderer: this.rendererTimeaccountId },
            { id: 'timeaccount_closed', header: this.app.i18n._("Time Account closed"), width: 100, dataIndex: 'timeaccount_closed',    hidden: true,    
                renderer: this.rendererTimeaccountClosed },
            { id: 'description',        header: this.app.i18n._("Description"),         width: 400, dataIndex: 'description',           hidden: true },
            { id: 'is_billable',        header: this.app.i18n._("Billable"),            width: 100, dataIndex: 'is_billable_combined',  
                renderer: Tine.Tinebase.common.booleanRenderer },
            { id: 'is_cleared',         header: this.app.i18n._("Cleared"),             width: 100, dataIndex: 'is_cleared_combined',   hidden: true,   
                renderer: Tine.Tinebase.common.booleanRenderer },
            { id: 'billed_in',          header: this.app.i18n._("Cleared in"),          width: 150, dataIndex: 'billed_in',             hidden: true },
            { id: 'account_id',         header: this.app.i18n._("Account"),             width: 350, dataIndex: 'account_id',            
                renderer: Tine.Tinebase.common.usernameRenderer },
            { id: 'duration',           header: this.app.i18n._("Duration"),            width: 150, dataIndex: 'duration',
                renderer: Tine.Tinebase.common.minutesRenderer }
        ].concat(this.getModlogColumns());
        
        return new Ext.grid.ColumnModel({ 
            defaults: {
                sortable: true,
                resizable: true
            },
            // add custom fields
            columns: columns.concat(this.getCustomfieldColumns())
        });
    },
    
    /**
     * timeaccount renderer -> returns timeaccount title
     * 
     * @param {Array} timeaccount
     * @return {String}
     */
    rendererTimeaccountId: function(timeaccount) {
        return new Tine.Timetracker.Model.Timeaccount(timeaccount).getTitle();
    },
    
    /**
     * is timeaccount closed -> returns yes/no if timeaccount is closed
     * 
     * @param {} a
     * @param {} b
     * @param {Tine.Timetracker.Model.Timesheet} record
     * @return {String}
     */
    rendererTimeaccountClosed: function(a, b, record) {
        var isopen = (record.data.timeaccount_id.is_open == '1');
        return Tine.Tinebase.common.booleanRenderer(!isopen);
    },

    /**
     * @private
     */
    initDetailsPanel: function() {
        this.detailsPanel = new Tine.widgets.grid.DetailsPanel({
            gridpanel: this,
            
            // use default Tpl for default and multi view
            defaultTpl: new Ext.XTemplate(
                '<div class="preview-panel-timesheet-nobreak">',
                    '<!-- Preview timeframe -->',           
                    '<div class="preview-panel preview-panel-timesheet-left">',
                        '<div class="bordercorner_1"></div>',
                        '<div class="bordercorner_2"></div>',
                        '<div class="bordercorner_3"></div>',
                        '<div class="bordercorner_4"></div>',
                        '<div class="preview-panel-declaration">' /*+ this.app.i18n._('timeframe')*/ + '</div>',
                        '<div class="preview-panel-timesheet-leftside preview-panel-left">',
                            '<span class="preview-panel-bold">',
                            /*'First Entry'*/'<br/>',
                            /*'Last Entry*/'<br/>',
                            /*'Duration*/'<br/>',
                            '<br/>',
                            '</span>',
                        '</div>',
                        '<div class="preview-panel-timesheet-rightside preview-panel-left">',
                            '<span class="preview-panel-nonbold">',
                            '<br/>',
                            '<br/>',
                            '<br/>',
                            '<br/>',
                            '</span>',
                        '</div>',
                    '</div>',
                    '<!-- Preview summary -->',
                    '<div class="preview-panel-timesheet-right">',
                        '<div class="bordercorner_gray_1"></div>',
                        '<div class="bordercorner_gray_2"></div>',
                        '<div class="bordercorner_gray_3"></div>',
                        '<div class="bordercorner_gray_4"></div>',
                        '<div class="preview-panel-declaration">'/* + this.app.i18n._('summary')*/ + '</div>',
                        '<div class="preview-panel-timesheet-leftside preview-panel-left">',
                            '<span class="preview-panel-bold">',
                            this.app.i18n._('Total Timesheets') + '<br/>',
                            this.app.i18n._('Billable Timesheets') + '<br/>',
                            this.app.i18n._('Total Time') + '<br/>',
                            this.app.i18n._('Time of Billable Timesheets') + '<br/>',
                            '</span>',
                        '</div>',
                        '<div class="preview-panel-timesheet-rightside preview-panel-left">',
                            '<span class="preview-panel-nonbold">',
                            '{count}<br/>',
                            '{countbillable}<br/>',
                            '{sum}<br/>',
                            '{sumbillable}<br/>',
                            '</span>',
                        '</div>',
                    '</div>',
                '</div>'            
            ),
            
            showDefault: function(body) {
            	
				var data = {
				    count: this.gridpanel.store.proxy.jsonReader.jsonData.totalcount,
				    countbillable: (this.gridpanel.store.proxy.jsonReader.jsonData.totalcountbillable) ? this.gridpanel.store.proxy.jsonReader.jsonData.totalcountbillable : 0,
				    sum:  Tine.Tinebase.common.minutesRenderer(this.gridpanel.store.proxy.jsonReader.jsonData.totalsum),
				    sumbillable: Tine.Tinebase.common.minutesRenderer(this.gridpanel.store.proxy.jsonReader.jsonData.totalsumbillable)
			    };
                
                this.defaultTpl.overwrite(body, data);
            },
            
            showMulti: function(sm, body) {
            	
                var data = {
                    count: sm.getCount(),
                    countbillable: 0,
                    sum: 0,
                    sumbillable: 0
                };
                sm.each(function(record){
                    
                    data.sum = data.sum + parseInt(record.data.duration);
                    if (record.data.is_billable_combined == '1') {
                    	data.countbillable++;
                    	data.sumbillable = data.sumbillable + parseInt(record.data.duration);
                    }
                });
                data.sum = Tine.Tinebase.common.minutesRenderer(data.sum);
                data.sumbillable = Tine.Tinebase.common.minutesRenderer(data.sumbillable);
                
                this.defaultTpl.overwrite(body, data);
            },
            
            tpl: new Ext.XTemplate(
        		'<div class="preview-panel-timesheet-nobreak">',	
        			'<!-- Preview beschreibung -->',
        			'<div class="preview-panel preview-panel-timesheet-left">',
        				'<div class="bordercorner_1"></div>',
        				'<div class="bordercorner_2"></div>',
        				'<div class="bordercorner_3"></div>',
        				'<div class="bordercorner_4"></div>',
        				'<div class="preview-panel-declaration">' /* + this.app.i18n._('Description') */ + '</div>',
        				'<div class="preview-panel-timesheet-description preview-panel-left" ext:qtip="{[this.encode(values.description)]}">',
        					'<span class="preview-panel-nonbold">',
        					 '{[this.encode(values.description, "longtext")]}',
        					'<br/>',
        					'</span>',
        				'</div>',
        			'</div>',
        			'<!-- Preview detail-->',
        			'<div class="preview-panel-timesheet-right">',
        				'<div class="bordercorner_gray_1"></div>',
        				'<div class="bordercorner_gray_2"></div>',
        				'<div class="bordercorner_gray_3"></div>',
        				'<div class="bordercorner_gray_4"></div>',
        				'<div class="preview-panel-declaration">' /* + this.app.i18n._('Detail') */ + '</div>',
        				'<div class="preview-panel-timesheet-leftside preview-panel-left">',
        				// @todo add custom fields here
        				/*
        					'<span class="preview-panel-bold">',
        					'Ansprechpartner<br/>',
        					'Newsletter<br/>',
        					'Ticketnummer<br/>',
        					'Ticketsubjekt<br/>',
        					'</span>',
        			    */
        				'</div>',
        				'<div class="preview-panel-timesheet-rightside preview-panel-left">',
        					'<span class="preview-panel-nonbold">',
        					'<br/>',
        					'<br/>',
        					'<br/>',
        					'<br/>',
        					'</span>',
        				'</div>',
        			'</div>',
        		'</div>',{
                encode: function(value, type, prefix) {
                    if (value) {
                        if (type) {
                            switch (type) {
                                case 'longtext':
                                    value = Ext.util.Format.ellipsis(value, 150);
                                    break;
                                default:
                                    value += type;
                            }                           
                        }
                    	
                        var encoded = Ext.util.Format.htmlEncode(value);
                        encoded = Ext.util.Format.nl2br(encoded);
                        
                        return encoded;
                    } else {
                        return '';
                    }
                }
            })
        });
    },
    
    /**
     * @private
     */
    initActions: function() {
        this.actions_exportTimesheet = new Ext.Action({
            text: this.app.i18n._('Export Timesheets'),
            iconCls: 'action_export',
            scope: this,
            requiredGrant: 'exportGrant',
            disabled: true,
            allowMultiple: true,
            menu: {
                items: [
                    new Tine.widgets.grid.ExportButton({
                        text: this.app.i18n._('Export as ODS'),
                        format: 'ods',
                        iconCls: 'tinebase-action-export-ods',
                        exportFunction: 'Timetracker.exportTimesheets',
                        gridPanel: this
                    }),
                    new Tine.widgets.grid.ExportButton({
                        text: this.app.i18n._('Export as CSV'),
                        format: 'csv',
                        iconCls: 'tinebase-action-export-csv',
                        exportFunction: 'Timetracker.exportTimesheets',
                        gridPanel: this
                    }),
                    new Tine.widgets.grid.ExportButton({
                        text: this.app.i18n._('Export as ...'),
                        iconCls: 'tinebase-action-export-xls',
                        exportFunction: 'Timetracker.exportTimesheets',
                        showExportDialog: true,
                        gridPanel: this
                    })
                ]
            }
        });
        
        // register actions in updater
        this.actionUpdater.addActions([
            this.actions_exportTimesheet
        ]);
        
        Tine.Timetracker.TimesheetGridPanel.superclass.initActions.call(this);
    },
    
    /**
     * add custom items to action toolbar
     * 
     * @return {Object}
     */
    getActionToolbarItems: function() {
        return [
            Ext.apply(new Ext.Button(this.actions_exportTimesheet), {
                scale: 'medium',
                rowspan: 2,
                iconAlign: 'top'
            })
        ];
    },
    
    /**
     * add custom items to context menu
     * 
     * @return {Array}
     */
    getContextMenuItems: function() {
        var items = [
            '-',
            this.actions_exportTimesheet,
            '-', {
            text: _('Mass Update'),
            iconCls: 'action_edit',
            disabled: !Tine.Tinebase.common.hasRight('manage', 'Timetracker', 'timeaccounts'),
            scope: this,
            menu: {
                items: [
                    '<b class="x-ux-menu-title">' + _('Update field:') + '</b>',
                    {
                        text: this.app.i18n._('Billable'),
                        field: 'is_billable',
                        scope: this,
                        handler: this.onMassUpdate
                    }, {
                        text: this.app.i18n._('Cleared'),
                        field: 'is_cleared',
                        scope: this,
                        handler: this.onMassUpdate
                    }, {
                        text: this.app.i18n._('Cleared in'),
                        field: 'billed_in',
                        scope: this,
                        handler: this.onMassUpdate
                    }
                ]
            }
        }];
        
        return items;
    }
});

// file: /home/lkneschke/temp/tine20build/temp/tine20/Timetracker/js/TimesheetEditDialog.js
/**
 * Tine 2.0
 * 
 * @package     Timetracker
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Philipp Schuele <p.schuele@metaways.de>
 * @copyright   Copyright (c) 2007-2008 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: TimesheetEditDialog.js 17759 2010-12-08 17:04:10Z p.schuele@metaways.de $
 *
 */
 
Ext.namespace('Tine.Timetracker');

/**
 * Timetracker Edit Dialog
 */
Tine.Timetracker.TimesheetEditDialog = Ext.extend(Tine.widgets.dialog.EditDialog, {

    
    /**
     * @private
     */
    windowNamePrefix: 'TimesheetEditWindow_',
    appName: 'Timetracker',
    recordClass: Tine.Timetracker.Model.Timesheet,
    recordProxy: Tine.Timetracker.timesheetBackend,
    loadRecord: false,
    tbarItems: [{xtype: 'widget-activitiesaddbutton'}],
    
    /**
     * overwrite update toolbars function (we don't have record grants yet)
     */
    updateToolbars: function(record) {
        this.onTimeaccountUpdate();
    	Tine.Timetracker.TimesheetEditDialog.superclass.updateToolbars.call(this, record, 'timeaccount_id');
    },
    
    /**
     * this gets called when initializing and if a new timeaccount is chosen
     * 
     * @param {} field
     * @param {} timeaccount
     */
    onTimeaccountUpdate: function(field, timeaccount) {
    	// check for manage_timeaccounts right
    	var manageRight = Tine.Tinebase.common.hasRight('manage', 'Timetracker', 'timeaccounts');
    	
        var notBillable = false;
        var notClearable = false;

        var grants = timeaccount ? timeaccount.get('account_grants') : (this.record.get('timeaccount_id') ? this.record.get('timeaccount_id').account_grants : {});
        if (grants) {
            this.getForm().findField('account_id').setDisabled(! (grants.book_all || grants.manage_all || manageRight));
            notBillable = ! (grants.manage_billable || grants.manage_all || manageRight);
            notClearable = ! (/*grants.manage_billable ||*/ grants.manage_all || manageRight);
            this.getForm().findField('billed_in').setDisabled(! (grants.manage_all || manageRight));
        }

        if (timeaccount) {
            notBillable = notBillable || timeaccount.data.is_billable == "0" || this.record.get('timeaccount_id').is_billable == "0";
            
            // clearable depends on timeaccount is_billable as well (changed by ps / 2009-09-01, behaviour was inconsistent)
            notClearable = notClearable || timeaccount.data.is_billable == "0" || this.record.get('timeaccount_id').is_billable == "0";
        }
        
        this.getForm().findField('is_billable').setDisabled(notBillable);
        this.getForm().findField('is_cleared').setDisabled(notClearable);
        
    	if (this.record.id == 0 && timeaccount) {
    	    // set is_billable for new records according to the timeaccount setting
    	    this.getForm().findField('is_billable').setValue(timeaccount.data.is_billable);
    	}
    },

    /**
     * this gets called when initializing and if cleared checkbox is changed
     * 
     * @param {} field
     * @param {} newValue
     * 
     * @todo    add prompt later?
     */
    onClearedUpdate: function(field, checked) {
    	
        this.getForm().findField('billed_in').setDisabled(! checked);

        /*
    	if (checked && this.getForm().findField('billed_in').getValue() == '') {
    		// open modal window to type in billed in value
            Ext.Msg.prompt(
                this.app.i18n._('Billed in ...'),
                this.app.i18n._('Billed in ...'), 
                function(btn, text) {
                    if (btn == 'ok'){
                        this.getForm().findField('billed_in').setValue(text);
                    }
                },
                this
            );                		
    	} else {
    		this.getForm().findField('billed_in').setValue('');
    	}
    	*/
    },
    
    /**
     * returns dialog
     * 
     * NOTE: when this method gets called, all initalisation is done.
     */
    getFormItems: function() {    	
        return {
            xtype: 'tabpanel',
            border: false,
            plain:true,
            activeTab: 0,
            items:[
                {            	
                title: this.app.i18n._('Timesheet'),
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
                    items: [[new Tine.Timetracker.TimeAccountSelect({
                        columnWidth: 1,
                        fieldLabel: this.app.i18n._('Time Account'),
                        emptyText: this.app.i18n._('Select Time Account...'),
                        loadingText: this.app.i18n._('Searching...'),
                        allowBlank: false,
                        name: 'timeaccount_id',
                        listeners: {
                            scope: this,
                            render: function(field){field.focus(false, 250);},
                            select: this.onTimeaccountUpdate
                        }
                    })], [{
                        fieldLabel: this.app.i18n._('Duration'),
                        name: 'duration',
                        allowBlank: false,
                        xtype: 'tinedurationspinner'
                        }, {
                        fieldLabel: this.app.i18n._('Date'),
                        name: 'start_date',
                        allowBlank: false,
                        xtype: 'datefield'
                        }, {
                        fieldLabel: this.app.i18n._('Start'),
                        emptyText: this.app.i18n._('not set'),
                        name: 'start_time',
                        xtype: 'timefield'/*,
                        listeners: {
                            scope: this, 
                            'expand': function(field) {
                                if (! field.getValue()) {
                                    var now = new Date().getHours();
                                    //console.log(field.store.find('text', '18:00'));
                                    field.select(field.store.find('text', '18:00'));
                                    
                                }
                            }
                        }*/
                    }], [{
                        columnWidth: 1,
                        fieldLabel: this.app.i18n._('Description'),
                        emptyText: this.app.i18n._('Enter description...'),
                        name: 'description',
                        allowBlank: false,
                        xtype: 'textarea',
                        height: 150
                    }], [new Tine.Addressbook.SearchCombo({
                        allowBlank: false,
                        columnWidth: 1,
                        disabled: true,
                        useAccountRecord: true,
                        userOnly: true,
                        nameField: 'n_fileas',
                        fieldLabel: this.app.i18n._('Account'),
                        name: 'account_id'
                    }), {
                        columnWidth: .25,
                        //hideLabel: false,
                        disabled: true,
                        boxLabel: this.app.i18n._('Billable'),
                        name: 'is_billable',
                        xtype: 'checkbox'
                    }, {
                        columnWidth: .25,
                        //hideLabel: true,
                        disabled: true,
                        boxLabel: this.app.i18n._('Cleared'),
                        name: 'is_cleared',
                        xtype: 'checkbox',
                        listeners: {
                            scope: this,
                            check: this.onClearedUpdate
                        }                        
                    }, {
                        columnWidth: .5,
                        disabled: true,
                        //emptyText: this.app.i18n._('not cleared yet...'),
                        fieldLabel: this.app.i18n._('Cleared In'),
                        name: 'billed_in',
                        xtype: 'textfield'
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
                            app: 'Timetracker',
                            showAddNoteForm: false,
                            border: false,
                            bodyStyle: 'border:1px solid #B5B8C8;'
                        }),
                        new Tine.widgets.tags.TagPanel({
                            app: 'Timetracker',
                            border: false,
                            bodyStyle: 'border:1px solid #B5B8C8;'
                        })
                    ]
                }]
            }, new Tine.Tinebase.widgets.customfields.CustomfieldsPanel ({
                recordClass: this.recordClass,
                disabled: (Tine[this.appName].registry.get('customfields').length == 0),
                quickHack: this
            }), new Tine.widgets.activities.ActivitiesTabPanel({
                app: this.appName,
                record_id: this.record.id,
                record_model: this.appName + '_Model_' + this.recordClass.getMeta('modelName')
            })]
        };
    },
    
    /**
     * show error if request fails
     * 
     * @param {} response
     * @param {} request
     * @private
     * 
     */
    onRequestFailed: function(response, request) {
        if (response.code && response.code == 902) {
            // deadline exception
            Ext.MessageBox.alert(
                this.app.i18n._('Failed'), 
                String.format(this.app.i18n._('Could not save {0}.'), this.i18nRecordName) 
                    + ' ( ' + this.app.i18n._('Booking deadline for this Timeaccount has been exceeded.') /* + ' ' + response.message  */ + ')'
            ); 
        } else {
            // call default exception handler
            Tine.Tinebase.ExceptionHandler.handleRequestException(response);
        }
        this.loadMask.hide();
    }
});

/**
 * Timetracker Edit Popup
 */
Tine.Timetracker.TimesheetEditDialog.openWindow = function (config) {
    var id = (config.record && config.record.id) ? config.record.id : 0;
    var window = Tine.WindowFactory.getWindow({
        width: 800,
        height: 500,
        name: Tine.Timetracker.TimesheetEditDialog.prototype.windowNamePrefix + id,
        contentPanelConstructor: 'Tine.Timetracker.TimesheetEditDialog',
        contentPanelConstructorConfig: config
    });
    return window;
};
