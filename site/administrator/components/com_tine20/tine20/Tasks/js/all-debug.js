/*!
 * Tine 2.0 USER CLIENT
 * 
 * license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * copyright   Copyright (c) 2007-2011 Metaways Infosystems GmbH (http://www.metaways.de)
 *
 * FOR MORE DETAILED LICENSE AND COPYRIGHT INFORMATION PLEASE CONSULT THE LICENSE FILE 
 * LOCATED AT: <YOUR TINE 2.0 URL>/LICENSE OR VISIT THE TINE 2.0 HOMEPAGE AT http://www.tine20.org
 */// file: /home/lkneschke/temp/tine20build/temp/tine20/Tasks/js/Tasks.js
/**
 * Tine 2.0
 * 
 * @package     Tasks
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Cornelius Weiss <c.weiss@metaways.de>
 * @copyright   Copyright (c) 2007-2011 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: Tasks.js 18484 2011-01-10 14:42:39Z p.schuele@metaways.de $
 *
 */

Ext.namespace('Tine', 'Tine.Tasks');

// default mainscreen
Tine.Tasks.MainScreen = Tine.widgets.MainScreen;

Tine.Tasks.TreePanel = function(config) {
    Ext.apply(this, config);
    
    this.id = 'TasksTreePanel';
    this.recordClass = Tine.Tasks.Task;
    
    this.filterMode = 'filterToolbar';
    Tine.Tasks.TreePanel.superclass.constructor.call(this);
};
Ext.extend(Tine.Tasks.TreePanel, Tine.widgets.container.TreePanel, {
    afterRender: function() {
        this.supr().afterRender.apply(this, arguments);
        //this.selectContainerPath(Tine.Tinebase.container.getMyNodePath());
    }
});

Tine.Tasks.FilterPanel = function(config) {
    Ext.apply(this, config);
    Tine.Tasks.FilterPanel.superclass.constructor.call(this);
};
Ext.extend(Tine.Tasks.FilterPanel, Tine.widgets.persistentfilter.PickerPanel, {
    filter: [{field: 'model', operator: 'equals', value: 'Tasks_Model_TaskFilter'}]
});

// Task model
Tine.Tasks.TaskArray = Tine.Tinebase.Model.genericFields.concat([
    { name: 'id' },
    { name: 'percent', header: 'Percent' },
    { name: 'completed', type: 'date', dateFormat: Date.patterns.ISO8601Long },
    { name: 'due', type: 'date', dateFormat: Date.patterns.ISO8601Long },
    // ical common fields
    { name: 'class' },
    { name: 'description' },
    { name: 'geo' },
    { name: 'location' },
    { name: 'organizer' },
    { name: 'originator_tz' },
    { name: 'priority' },
    { name: 'status_id' },
    { name: 'summary' },
    { name: 'url' },
    // ical common fields with multiple appearance
    { name: 'attach' },
    { name: 'attendee' },
    { name: 'tags' },
    { name: 'comment' },
    { name: 'contact' },
    { name: 'related' },
    { name: 'resources' },
    { name: 'rstatus' },
    // scheduleable interface fields
    { name: 'dtstart', type: 'date', dateFormat: Date.patterns.ISO8601Long },
    { name: 'duration', type: 'date', dateFormat: Date.patterns.ISO8601Long },
    { name: 'recurid' },
    // scheduleable interface fields with multiple appearance
    { name: 'exdate' },
    { name: 'exrule' },
    { name: 'rdate' },
    { name: 'rrule' },
    // tine 2.0 notes field
    { name: 'notes'},
    // tine 2.0 alarms field
    { name: 'alarms'},
    // relations with other objects
    { name: 'relations'}
]);

/**
 * Task record definition
 */
Tine.Tasks.Task = Tine.Tinebase.data.Record.create(Tine.Tasks.TaskArray, {
    appName: 'Tasks',
    modelName: 'Task',
    idProperty: 'id',
    titleProperty: 'summary',
    // ngettext('Task', 'Tasks', n); gettext('Tasks');
    recordName: 'Task',
    recordsName: 'Tasks',
    containerProperty: 'container_id',
    // ngettext('to do list', 'to do lists', n); gettext('to do lists');
    containerName: 'to do list',
    containersName: 'to do lists'
});

/**
 * returns default account data
 * 
 * @namespace Tine.Tasks.Task
 * @static
 * @return {Object} default data
 */
Tine.Tasks.Task.getDefaultData = function() {
    var app = Tine.Tinebase.appMgr.get('Tasks');
    
    return {
        'class': 'PUBLIC',
        percent: 0,
        organizer: Tine.Tinebase.registry.get('currentAccount'),
        container_id: app.getMainScreen().getWestPanel().getContainerTreePanel().getDefaultContainerForNewRecords()
    };
};

/**
 * @namespace Tine.Tasks.Task
 * 
 * get task filter
 *  
 * @return {Array} filter objects
 * @static
 */ 
Tine.Tasks.Task.getFilterModel = function() {
    var app = Tine.Tinebase.appMgr.get('Tasks');
    
    return [
        {label: _('Quick search'),                  field: 'query',    operators: ['contains']},
        {filtertype: 'tine.widget.container.filtermodel', app: app, recordClass: Tine.Tasks.Task},
        {label: app.i18n._('Summary'),         field: 'summary' },
        {label: app.i18n._('Due Date'),        field: 'due', valueType: 'date', operators: ['within', 'before', 'after']},
        {filtertype: 'tasks.status'},
        {label: app.i18n._('Responsible'),     field: 'organizer', valueType: 'user'},
        {filtertype: 'tinebase.tag', app: app},
        {label: app.i18n._('Last modified'),   field: 'last_modified_time', valueType: 'date'},
        {label: app.i18n._('Last modifier'),   field: 'last_modified_by',   valueType: 'user'},
        {label: app.i18n._('Creation Time'),   field: 'creation_time',      valueType: 'date'},
        {label: app.i18n._('Creator'),         field: 'created_by',         valueType: 'user'}
    ];
};

/**
 * default tasks backend
 */
Tine.Tasks.JsonBackend = new Tine.Tinebase.data.RecordProxy({
    appName: 'Tasks',
    modelName: 'Task',
    recordClass: Tine.Tasks.Task
});

// file: /home/lkneschke/temp/tine20build/temp/tine20/Tasks/js/Status.js
/*
 * Tine 2.0
 * 
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Cornelius Weiss <c.weiss@metaways.de>
 * @copyright   Copyright (c) 2007-2010 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: Status.js 18432 2011-01-07 15:35:32Z c.weiss@metaways.de $
 */
Ext.ns('Tine.Tasks.status');

/**
 * @namespace   Tine.Tasks
 * @class       Tine.Tasks.status.StatusFilter
 * @extends     Tine.widgets.grid.FilterModel
 * 
 * @author      Cornelius Weiss <c.weiss@metaways.de>
 * @version     $Id: Status.js 18432 2011-01-07 15:35:32Z c.weiss@metaways.de $
 */
Tine.Tasks.status.StatusFilter = Ext.extend(Tine.widgets.grid.FilterModel, {
    /**
     * @property Tine.Tinebase.Application app
     */
    app: null,
    
    field: 'status_id',
    defaultOperator: 'notin',
    
    /**
     * @private
     */
    initComponent: function() {
        this.operators = ['in', 'notin'];
        this.label = _('Status');
        
        this.defaultValue = Tine.Tasks.status.getClosedStatus();
        
        this.supr().initComponent.call(this);
    },
    
    /**
     * value renderer
     * 
     * @param {Ext.data.Record} filter line
     * @param {Ext.Element} element to render to 
     */
    valueRenderer: function(filter, el) {
        var value = new Tine.Tasks.status.StatusFilterValueField({
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

Tine.widgets.grid.FilterToolbar.FILTERS['tasks.status'] = Tine.Tasks.status.StatusFilter;

/**
 * @namespace   Tine.Tasks
 * @class       Tine.Tasks.status.StatusFilterValueField
 * @extends     Ext.ux.form.LayerCombo
 * 
 * @author      Cornelius Weiss <c.weiss@metaways.de>
 * @version     $Id: Status.js 18432 2011-01-07 15:35:32Z c.weiss@metaways.de $
 */
Tine.Tasks.status.StatusFilterValueField = Ext.extend(Ext.ux.form.LayerCombo, {
    hideButtons: false,
    formConfig: {
        labelAlign: 'left',
        labelWidth: 30
    },
    
    getFormValue: function() {
        var ids = [];
        var statusStore = Tine.Tasks.status.getStore();
        
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
        
        Tine.Tasks.status.getStore().each(function(status) {
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
        
        var statusStore = Tine.Tasks.status.getStore();
        var statusText = [];
        this.currentValue = [];
        
        Tine.Tasks.status.getStore().each(function(status) {
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

/**
 * @namespace   Tine.Tasks
 * @class       Tine.Tasks.status.ComboBox
 * @extends     Ext.form.ComboBox
 * 
 * @author      Cornelius Weiss <c.weiss@metaways.de>
 * @version     $Id: Status.js 18432 2011-01-07 15:35:32Z c.weiss@metaways.de $
 */
Tine.Tasks.status.ComboBox = Ext.extend(Ext.form.ComboBox, {
	/**
     * @cfg {bool} autoExpand Autoexpand comboBox on focus.
     */
    autoExpand: false,
	/**
     * @cfg {bool} blurOnSelect blurs combobox when item gets selected
     */
    blurOnSelect: false,
    
	fieldLabel: 'status',
    name: 'status',
    displayField: 'i18n_status_name',
    valueField: 'id',
    mode: 'local',
    triggerAction: 'all',
    emptyText: 'Status...',
    typeAhead: true,
    selectOnFocus: true,
    editable: false,
    lazyInit: false,
    
    translation: null,
	
	//private
    initComponent: function(){
    	
        this.translation = new Locale.Gettext();
        this.translation.textdomain('Tasks');
    	
		this.store = Tine.Tasks.status.getStore();
		if (!this.value) {
			this.value = Tine.Tasks.status.getIdentifier('IN-PROCESS');
		}
		if (this.autoExpand) {
            this.lazyInit = false;
			this.on('focus', function(){
                this.onTriggerClick();
            });
		}
		if (this.blurOnSelect){
            this.on('select', function(){
                this.fireEvent('blur', this);
            }, this);
        }
        
	    Tine.Tasks.status.ComboBox.superclass.initComponent.call(this);
	},
    
    setValue2: function(value) {
        if(! value) {
            return;
        }
        Tine.Tasks.status.ComboBox.superclass.setValue.call(this, value);
    }
        
});
Ext.reg('tasksstatuscombo', Tine.Tasks.status.ComboBox);

Tine.Tasks.status.getStore = function() {
	if (!store) {
		var store = new Ext.data.JsonStore({
            fields: [ 
                { name: 'id'                                                },
                { name: 'created_by'                                        }, 
                { name: 'creation_time',      type: 'date', dateFormat: Date.patterns.ISO8601Long },
                { name: 'last_modified_by'                                  },
                { name: 'last_modified_time', type: 'date', dateFormat: Date.patterns.ISO8601Long },
                { name: 'is_deleted'                                        }, 
                { name: 'deleted_time',       type: 'date', dateFormat: Date.patterns.ISO8601Long }, 
                { name: 'deleted_by'                                        },
                { name: 'status_name'                                       },
                { name: 'i18n_status_name'                                  },
                { name: 'status_is_open',      type: 'bool'                 },
                { name: 'status_icon'                                       }
           ],
		   // initial data from http request
           data: Tine.Tasks.registry.get('AllStatus'),
           autoLoad: true,
           id: 'id'
       });
       var app = Tine.Tinebase.appMgr.get('Tasks');
       store.each(function(r) {r.set('i18n_status_name', app.i18n._hidden(r.get('status_name')));}, this);
	}
	return store;
};

Tine.Tasks.status.getClosedStatus = function() {
    var reqStatus = [];
        
    Tine.Tasks.status.getStore().each(function(status) {
        if (! status.get('status_is_open')) {
            reqStatus.push(status.get('id'));
        }
    }, this);
    
    return reqStatus;
};

Tine.Tasks.status.getIdentifier = function(statusName) {
	var index = Tine.Tasks.status.getStore().find('status_name', statusName);
	var status = Tine.Tasks.status.getStore().getAt(index);
	return status ? status.data.id : statusName;
};

Tine.Tasks.status.getStatus = function(id) {
	var status = Tine.Tasks.status.getStore().getById(id);
    return status ? status : id;
};

Tine.Tasks.status.getStatusIcon = function(id) {
    var status = Tine.Tasks.status.getStatus(id);
    if (!status) {
    	return;
    }
    return '<img class="TasksMainGridStatus" src="' + status.data.status_icon + '" ext:qtip="' + status.data.status_name + '">';
};

// file: /home/lkneschke/temp/tine20build/temp/tine20/Tasks/js/GridPanel.js
/*
 * Tine 2.0
 * 
 * @package     Tasks
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Cornelius Weiss <c.weiss@metaways.de>
 * @copyright   Copyright (c) 2007-2008 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: GridPanel.js 18079 2010-12-23 15:11:20Z p.schuele@metaways.de $
 *
 */
 
Ext.namespace('Tine.Tasks');

/**
 * Tasks grid panel
 * 
 * @namespace   Tine.Tasks
 * @class       Tine.Tasks.GridPanel
 * @extends     Tine.widgets.grid.GridPanel
 * 
 * <p>Tasks Grid Panel</p>
 * <p><pre>
 * </pre></p>
 * 
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Cornelius Weiss <c.weiss@metaways.de>
 * @copyright   Copyright (c) 2007-2008 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: GridPanel.js 18079 2010-12-23 15:11:20Z p.schuele@metaways.de $
 * 
 * @param       {Object} config
 * @constructor
 * Create a new Tine.Tasks.GridPanel
 */
Tine.Tasks.GridPanel = Ext.extend(Tine.widgets.grid.GridPanel, {
    /**
     * record class
     * @cfg {Tine.Tasks.Task} recordClass
     */
    recordClass: Tine.Tasks.Task,
    
    /**
     * @private grid cfg
     */
    defaultSortInfo: {field: 'due', dir: 'ASC'},
    gridConfig: {
        clicksToEdit: 'auto',
        quickaddMandatory: 'summary',
        autoExpandColumn: 'summary',
        // drag n drop
        enableDragDrop: true,
        ddGroup: 'containerDDGroup'
    },
    
    // specialised translations
    // ngettext('Do you really want to delete the selected task?', 'Do you really want to delete the selected tasks?', n);
    i18nDeleteQuestion: ['Do you really want to delete the selected task?', 'Do you really want to delete the selected tasks?'],
    
    /**
     * @private
     */
    initComponent: function() {
        this.recordProxy = Tine.Tasks.JsonBackend;
        
        //this.actionToolbarItems = this.getToolbarItems();
        this.gridConfig.cm = this.getColumnModel();
        this.initFilterToolbar();
        
        this.plugins = this.plugins || [];
        this.plugins.push(/*this.action_showClosedToggle,*/ this.filterToolbar);
        
        Tine.Tasks.GridPanel.superclass.initComponent.call(this);
        
        // the editGrids onEditComplete calls the focusCell after a edit operation
        // this leads to a 'flicker' effect we dont want!
        // mhh! but disabling this, breaks keynav 
        //this.grid.view.focusCell = Ext.emptyFn;
    },
    
    /**
     * initialises filter toolbar
     * @private
     */
    initFilterToolbar: function() {
        this.filterToolbar = new Tine.widgets.grid.FilterToolbar({
            recordClass: this.recordClass,
            filterModels: Tine.Tasks.Task.getFilterModel(),
            defaultFilter: 'query',
            filters: [
                {field: 'status_id', operator: 'notin', value: Tine.Tasks.status.getClosedStatus()},
                {field: 'container_id', operator: 'equals', value: {path: Tine.Tinebase.container.getMyNodePath()}}
            ],
            plugins: [
                new Tine.widgets.grid.FilterToolbarQuickFilterPlugin()
            ]
        });
    },
    
    /**
     * returns cm
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
        {   id: 'tags', header: this.app.i18n._('Tags'), width: 40,  dataIndex: 'tags', sortable: false, renderer: Tine.Tinebase.common.tagsRenderer },
        {   id: 'lead_name', header: this.app.i18n._('Lead'), dataIndex: 'relations', width: 175, sortable: false, hidden: true, renderer: this.leadRenderer },
        {
            id: 'summary',
            header: this.app.i18n._("Summary"),
            width: 400,
            dataIndex: 'summary',
            quickaddField: new Ext.form.TextField({
                emptyText: this.app.i18n._('Add a task...')
            })
        }, {
            id: 'due',
            header: this.app.i18n._("Due Date"),
            width: 60,
            dataIndex: 'due',
            renderer: Tine.Tinebase.common.dateRenderer,
            editor: new Ext.ux.form.ClearableDateField({}),
            quickaddField: new Ext.ux.form.ClearableDateField({})
        }, {
            id: 'priority',
            header: this.app.i18n._("Priority"),
            width: 45,
            dataIndex: 'priority',
            renderer: Tine.widgets.Priority.renderer,
            editor: new Tine.widgets.Priority.Combo({
                allowBlank: false,
                autoExpand: true,
                blurOnSelect: true
            }),
            quickaddField: new Tine.widgets.Priority.Combo({
                autoExpand: true
            })
        }, {
            id: 'percent',
            header: this.app.i18n._("Percent"),
            width: 50,
            dataIndex: 'percent',
            renderer: Ext.ux.PercentRenderer,
            editor: new Ext.ux.PercentCombo({
                autoExpand: true,
                blurOnSelect: true
            }),
            quickaddField: new Ext.ux.PercentCombo({
                autoExpand: true
            })
        }, {
            id: 'status_id',
            header: this.app.i18n._("Status"),
            width: 45,
            dataIndex: 'status_id',
            renderer: Tine.Tasks.status.getStatusIcon,
            editor: new Tine.Tasks.status.ComboBox({
                autoExpand: true,
                blurOnSelect: true,
                listClass: 'x-combo-list-small'
            }),
            quickaddField: new Tine.Tasks.status.ComboBox({
                autoExpand: true
            })
        }, {
            id: 'creation_time',
            header: this.app.i18n._("Creation Time"),
            hidden: true,
            width: 90,
            dataIndex: 'creation_time',
            renderer: Tine.Tinebase.common.dateTimeRenderer
        }, {
            id: 'completed',
            header: this.app.i18n._("Completed"),
            hidden: true,
            width: 90,
            dataIndex: 'completed',
            renderer: Tine.Tinebase.common.dateTimeRenderer
        }, {
            id: 'organizer',
            header: this.app.i18n._('Responsible'),
            width: 150,
            dataIndex: 'organizer',
            renderer: Tine.Tinebase.common.accountRenderer,
            quickaddField: new Tine.Addressbook.SearchCombo({
                // at the moment we support accounts only
                userOnly: true,
                nameField: 'n_fileas',
                blurOnSelect: true,
                selectOnFocus: true,
                value: Tine.Tinebase.registry.get('currentAccount').accountDisplayName,
                selectedRecord: new Tine.Addressbook.Model.Contact(Tine.Tinebase.registry.get('userContact')),
                getValue: function() {
                    if (this.selectedRecord) {
                        return this.selectedRecord.get('account_id');
                    }
                }
            })
        }]
        });
    },
    
    /**
     * return lead name for first linked Crm_Model_Lead
     * 
     * @param {Object} data
     * @return {String} lead name
     */
    leadRenderer: function(data) {    
    
        if( Ext.isArray(data) && data.length > 0) {
            var index = 0;
            // get correct relation type from data (contact) array and show first matching record (org_name + n_fileas)
            while (index < data.length && data[index].related_model != 'Crm_Model_Lead') {
                index++;
            }
            if (data[index]) {
                var name = (data[index].related_record.lead_name !== null ) ? data[index].related_record.lead_name : '';
                return Ext.util.Format.htmlEncode(name);
            }
        }
    },    

    /**
     * return additional tb items
     * @private
     */
    getToolbarItems: function(){
        this.action_showClosedToggle = new Tine.widgets.grid.FilterButton({
            text: this.app.i18n._('Show closed'),
            iconCls: 'action_showArchived',
            field: 'showClosed'
        });
        
        return [
            new Ext.Toolbar.Separator(),
            this.action_showClosedToggle
        ];
    },
    
    /**
     * Return CSS class to apply to rows depending upon due status
     * 
     * @param {Tine.Tasks.Task} record
     * @param {Integer} index
     * @return {String}
     */
    getViewRowClass: function(record, index) {
        var due = record.get('due');
        
        var className = '';
        if (due) {
            var dueDay = due.format('Y-m-d');
            var today = new Date().format('Y-m-d');

            if (dueDay == today) {
                className += 'tasks-grid-duetoday';
            } else if (dueDay < today) {
                className += 'tasks-grid-overdue';
            }
            
        }
        return className;
    }
});

// file: /home/lkneschke/temp/tine20build/temp/tine20/Tasks/js/TaskEditDialog.js
/*
 * Tine 2.0
 * 
 * @package     Tasks
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Cornelius Weiss <c.weiss@metaways.de>
 * @copyright   Copyright (c) 2007-2009 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: TaskEditDialog.js 18261 2011-01-03 13:26:54Z p.schuele@metaways.de $
 *
 */
 
Ext.namespace('Tine.Tasks');

/**
 * @namespace   Tine.Tasks
 * @class       Tine.Tasks.TaskEditDialog
 * @extends     Tine.widgets.dialog.EditDialog
 * 
 * <p>Tasks Edit Dialog</p>
 * <p>
 * TODO         refactor this: remove initRecord/containerId/relatedApp, 
 *              adopt to normal edit dialog flow and add getDefaultData to task model
 * </p>
 * 
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Cornelius Weiss <c.weiss@metaways.de>
 * @copyright   Copyright (c) 2007-2009 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: TaskEditDialog.js 18261 2011-01-03 13:26:54Z p.schuele@metaways.de $
 * 
 * @param       {Object} config
 * @constructor
 * Create a new Tine.Tasks.TaskEditDialog
 */
 Tine.Tasks.TaskEditDialog = Ext.extend(Tine.widgets.dialog.EditDialog, {
    /**
     * @cfg {Number} containerId
     */
    containerId: -1,
    
    /**
     * @cfg {String} relatedApp
     */
    relatedApp: '',
    
    /**
     * @private
     */
    labelAlign: 'side',
    
    /**
     * @private
     */
    windowNamePrefix: 'TasksEditWindow_',
    appName: 'Tasks',
    recordClass: Tine.Tasks.Task,
    recordProxy: Tine.Tasks.JsonBackend,
    showContainerSelector: true,
    tbarItems: [{xtype: 'widget-activitiesaddbutton'}],
    
    /**
     * @private
     */
    initComponent: function() {
        this.alarmPanel = new Tine.widgets.dialog.AlarmPanel({});
        this.linkPanel = new Tine.widgets.dialog.LinkPanel({
            relatedRecords: (Tine.Crm && Tine.Tinebase.common.hasRight('run', 'Crm')) ? {
                Crm_Model_Lead: {
                    recordClass: Tine.Crm.Model.Lead,
                    dlgOpener: Tine.Crm.LeadEditDialog.openWindow
                }
            } : {}
        });
        
        Tine.Tasks.TaskEditDialog.superclass.initComponent.call(this);
    },
    
    /**
     * executed when record is loaded
     * @private
     */
    onRecordLoad: function() {
        // interrupt process flow until dialog is rendered
        if (! this.rendered) {
            this.onRecordLoad.defer(250, this);
            return;
        }
        
        Tine.Tasks.TaskEditDialog.superclass.onRecordLoad.apply(this, arguments);
        this.handleCompletedDate();
        
        // update tabpanels
        this.alarmPanel.onRecordLoad(this.record);
        this.linkPanel.onRecordLoad(this.record);
    },
    
    /**
     * executed when record is updated
     * @private
     */
    onRecordUpdate: function() {
        Tine.Tasks.TaskEditDialog.superclass.onRecordUpdate.apply(this, arguments);
        this.alarmPanel.onRecordUpdate(this.record);
    },
    
    /**
     * handling for the completed field
     * @private
     */
    handleCompletedDate: function() {
        if (this.getForm().findField('status_id') === null) {
            return;
        }
        
        var status = Tine.Tasks.status.getStatus(this.getForm().findField('status_id').getValue());
        var completed = this.getForm().findField('completed');
        
        if (status.get('status_is_open')) {
            completed.setValue(null);
            completed.setDisabled(true);
        } else {
            if (! Ext.isDate(completed.getValue())){
                completed.setValue(new Date());
            }
            completed.setDisabled(false);
        }
    },
    
    /**
     * checks if form data is valid
     * 
     * @return {Boolean}
     */
    isValid: function() {
        isValid = true;
        
        var dueField = this.getForm().findField('due'),
            dueDate = dueField.getValue(),
            alarmValue = parseInt(this.alarmPanel.alarmCombo.getValue(), 10);
            
        if (Ext.isNumber(alarmValue) && ! Ext.isDate(dueDate)) {
            dueField.markInvalid(this.app.i18n._('You have to supply a due date, because an alarm ist set!'));
            
            isValid = false;
        }
        
        return isValid && Tine.Tasks.TaskEditDialog.superclass.isValid.apply(this, arguments);
    },
    
    /**
     * returns dialog
     * 
     * NOTE: when this method gets called, all initalisation is done.
     * @private
     */
    getFormItems: function() { 
        return {
            xtype: 'tabpanel',
            border: false,
            plain:true,
            activeTab: 0,
            border: false,
            items:[{
                title: this.app.i18n.n_('Task', 'Tasks', 1),
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
                        columnWidth: 1,
                        fieldLabel: this.app.i18n._('Summary'),
                        name: 'summary',
                        listeners: {render: function(field){field.focus(false, 250);}},
                        allowBlank: false
                    }], [ new Ext.ux.form.ClearableDateField({
                        fieldLabel: this.app.i18n._('Due date'),
                        name: 'due'
                    }), new Tine.widgets.Priority.Combo({
                        fieldLabel: this.app.i18n._('Priority'),
                        name: 'priority'
                    }), new Tine.Addressbook.SearchCombo({
                            emptyText: _('Add Responsible ...'),
                            userOnly: true,
                            name: 'organizer',
                            nameField: 'n_fileas',
                            useAccountRecord: true
                    })], [{
                        columnWidth: 1,
                        fieldLabel: this.app.i18n._('Notes'),
                        emptyText: this.app.i18n._('Enter description...'),
                        name: 'description',
                        xtype: 'textarea',
                        height: 200
                    }], [new Ext.ux.PercentCombo({
                        fieldLabel: this.app.i18n._('Percentage'),
                        editable: false,
                        name: 'percent'
                    }), new Tine.Tasks.status.ComboBox({
                        fieldLabel: this.app.i18n._('Status'),
                        name: 'status_id',
                        listeners: {scope: this, 'change': this.handleCompletedDate}
                    }), new Ext.form.DateField({
                        fieldLabel: this.app.i18n._('Completed'),
                        name: 'completed'
                    })]]
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
                            app: 'Tasks',
                            showAddNoteForm: false,
                            border: false,
                            bodyStyle: 'border:1px solid #B5B8C8;'
                        }),
                        new Tine.widgets.tags.TagPanel({
                            app: 'Tasks',
                            border: false,
                            bodyStyle: 'border:1px solid #B5B8C8;'
                        })
                    ]
                }]
            }, new Tine.widgets.activities.ActivitiesTabPanel({
                app: this.appName,
                record_id: (this.record) ? this.record.id : '',
                record_model: this.appName + '_Model_' + this.recordClass.getMeta('modelName')
            }), this.alarmPanel, 
                this.linkPanel
            ]
        };
    }
});

/**
 * Tasks Edit Popup
 * 
 * @param   {Object} config
 * @return  {Ext.ux.Window}
 */
Tine.Tasks.TaskEditDialog.openWindow = function (config) {
    var id = (config.record && config.record.id) ? config.record.id : 0;
    var window = Tine.WindowFactory.getWindow({
        width: 800,
        height: 490,
        name: Tine.Tasks.TaskEditDialog.prototype.windowNamePrefix + id,
        contentPanelConstructor: 'Tine.Tasks.TaskEditDialog',
        contentPanelConstructorConfig: config
    });
    return window;
};
