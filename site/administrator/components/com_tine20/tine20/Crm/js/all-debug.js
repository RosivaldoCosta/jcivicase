/*!
 * Tine 2.0 USER CLIENT
 * 
 * license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * copyright   Copyright (c) 2007-2011 Metaways Infosystems GmbH (http://www.metaways.de)
 *
 * FOR MORE DETAILED LICENSE AND COPYRIGHT INFORMATION PLEASE CONSULT THE LICENSE FILE 
 * LOCATED AT: <YOUR TINE 2.0 URL>/LICENSE OR VISIT THE TINE 2.0 HOMEPAGE AT http://www.tine20.org
 */// file: /home/lkneschke/temp/tine20build/temp/tine20/Crm/js/Model.js
/*
 * Tine 2.0
 * 
 * @package     Crm
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Philipp Schuele <p.schuele@metaways.de>
 * @copyright   Copyright (c) 2007-2010 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: Model.js 16839 2010-10-28 10:01:36Z p.schuele@metaways.de $
 *
 */
 
Ext.namespace('Tine.Crm', 'Tine.Crm.Model');

/**
 * @namespace Tine.Crm.Model
 * @class Tine.Crm.Model.Lead
 * @extends Tine.Tinebase.data.Record
 * 
 * Lead Record Definition
 */ 
Tine.Crm.Model.Lead = Tine.Tinebase.data.Record.create(Tine.Tinebase.Model.genericFields.concat([
        {name: 'id',            type: 'int'},
        {name: 'lead_name',     type: 'string'},
        {name: 'leadstate_id',  type: 'int'},
        {name: 'leadtype_id',   type: 'int'},
        {name: 'leadsource_id', type: 'int'},
        {name: 'start',         type: 'date', dateFormat: Date.patterns.ISO8601Long},
        {name: 'description',   type: 'string'},
        {name: 'end',           type: 'date', dateFormat: Date.patterns.ISO8601Long},
        {name: 'turnover',      type: 'int'},
        {name: 'probability',   type: 'int'},
        {name: 'probableTurnover',   type: 'int'},
        {name: 'end_scheduled', type: 'date', dateFormat: Date.patterns.ISO8601Long},
        {name: 'lastread'},
        {name: 'lastreader'},
        {name: 'responsible'},
        {name: 'customer'},
        {name: 'partner'},
        {name: 'tasks'},
        {name: 'relations'},
        {name: 'products'},
        {name: 'tags'},
        {name: 'notes'}
    ]), {
    appName: 'Crm',
    modelName: 'Lead',
    idProperty: 'id',
    titleProperty: 'title',
    // ngettext('Lead', 'Leads', n);
    recordName: 'Lead',
    recordsName: 'Leads',
    containerProperty: 'container_id',
    // ngettext('lead list', 'lead lists', n); gettext('lead lists');
    containerName: 'lead list',
    containersName: 'lead lists',
    getTitle: function() {
        return this.get('lead_name') ? this.get('lead_name') : false;
    }
});

/**
 * @namespace Tine.Crm.Model
 * 
 * get default data for a new lead
 *  
 * @return {Object} default data
 * @static
 * 
 * TODO generalize default container id handling?
 */ 
Tine.Crm.Model.Lead.getDefaultData = function() {
    
    var defaults = Tine.Crm.registry.get('defaults');
    var app = Tine.Tinebase.appMgr.get('Crm');
    
    var data = {
        start: new Date().clearTime(),
        leadstate_id: defaults.leadstate_id,
        leadtype_id: defaults.leadtype_id,
        leadsource_id: defaults.leadsource_id,
        container_id: app.getMainScreen().getWestPanel().getContainerTreePanel().getSelectedContainer('addGrant', defaults.container_id),
        probability: 0,
        turnover: 0,
        relations: [{
            type: 'responsible',
            related_record: Tine.Tinebase.registry.get('userContact')
        }]
    };
    
    return data;
};

/**
 * @namespace Tine.Crm.Model
 * @class Tine.Crm.Model.Settings
 * @extends Tine.Tinebase.data.Record
 * 
 * Settings Record Definition
 * 
 * TODO         generalize this
 */ 
Tine.Crm.Model.Settings = Tine.Tinebase.data.Record.create([
        {name: 'id'},
        {name: 'defaults'},
        {name: 'leadstates'},
        {name: 'leadtypes'},
        {name: 'leadsources'},
        {name: 'default_leadstate_id',  type: 'int'},
        {name: 'default_leadtype_id',   type: 'int'},
        {name: 'default_leadsource_id', type: 'int'}
    ], {
    appName: 'Crm',
    modelName: 'Settings',
    idProperty: 'id',
    titleProperty: 'title',
    // ngettext('Settings', 'Settings', n);
    recordName: 'Settings',
    recordsName: 'Settingss',
    containerProperty: 'container_id',
    // ngettext('record list', 'record lists', n);
    containerName: 'Settings',
    containersName: 'Settings',
    getTitle: function() {
        return this.recordName;
    }
});

Tine.Crm.Model.getRandomUnusedId = function(store) {
    var result;
    do {
        result = Tine.Tinebase.common.getRandomNumber(0, 21474836);
    } while (store.getById(result) != undefined)
    
    return result;
};

// file: /home/lkneschke/temp/tine20build/temp/tine20/Crm/js/Crm.js
/*
 * Tine 2.0
 * 
 * @package     Crm
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Philipp Schuele <p.schuele@metaways.de>
 * @copyright   Copyright (c) 2007-2009 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: Crm.js 14236 2010-05-07 08:59:54Z c.weiss@metaways.de $
 *
 */
 
Ext.namespace('Tine.Crm');

/**
 * @namespace Tine.Crm
 * @class Tine.Crm.MainScreen
 * @extends Tine.widgets.MainScreen
 * MainScreen of the Crm Application <br>
 * 
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Philipp Schuele <p.schuele@metaways.de>
 * @copyright   Copyright (c) 2007-2009 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: Crm.js 14236 2010-05-07 08:59:54Z c.weiss@metaways.de $
 * @constructor
 * Constructs mainscreen of the crm application
 */
Tine.Crm.MainScreen = Tine.widgets.MainScreen;

/**
 * @namespace Tine.Crm
 * @class Tine.Crm.TreePanel
 * @extends Tine.widgets.container.TreePanel
 * Left Crm Panel including Tree<br>
 * 
 * TODO add d&d support to tree
 * 
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Philipp Schuele <p.schuele@metaways.de>
 * @copyright   Copyright (c) 2007-2009 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: Crm.js 14236 2010-05-07 08:59:54Z c.weiss@metaways.de $
 */
Tine.Crm.TreePanel = function(config) {
    Ext.apply(this, config);
    
    this.id = 'CrmTreePanel';
    this.filterMode = 'filterToolbar';
    this.recordClass = Tine.Crm.Model.Lead;
    Tine.Crm.TreePanel.superclass.constructor.call(this);
};

Ext.extend(Tine.Crm.TreePanel , Tine.widgets.container.TreePanel);

/**
 * @namespace Tine.Crm
 * @class Tine.Crm.FilterPanel
 * @extends Tine.widgets.persistentfilter.PickerPanel
 * Crm Filter Panel<br>
 * 
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Philipp Schuele <p.schuele@metaways.de>
 * @copyright   Copyright (c) 2007-2009 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: Crm.js 14236 2010-05-07 08:59:54Z c.weiss@metaways.de $
 */
Tine.Crm.FilterPanel = function(config) {
    Ext.apply(this, config);
    Tine.Crm.FilterPanel.superclass.constructor.call(this);
};

Ext.extend(Tine.Crm.FilterPanel, Tine.widgets.persistentfilter.PickerPanel, {
    filter: [{field: 'model', operator: 'equals', value: 'Crm_Model_LeadFilter'}]
});

/**
 * @namespace Tine.Crm
 * @class Tine.Crm.leadBackend
 * @extends Tine.Tinebase.data.RecordProxy
 * 
 * Lead Backend
 */ 
Tine.Crm.leadBackend = new Tine.Tinebase.data.RecordProxy({
    appName: 'Crm',
    modelName: 'Lead',
    recordClass: Tine.Crm.Model.Lead
});

/**
 * @namespace Tine.Crm
 * @class Tine.Crm.settingBackend
 * @extends Tine.Tinebase.data.RecordProxy
 * 
 * Settings Backend
 * 
 * TODO generalize this
 */ 
Tine.Crm.settingsBackend = new Tine.Tinebase.data.RecordProxy({
    appName: 'Crm',
    modelName: 'Settings',
    recordClass: Tine.Crm.Model.Settings
});

// file: /home/lkneschke/temp/tine20build/temp/tine20/Crm/js/LinkGridPanel.js
/*
 * Tine 2.0
 * 
 * @package     Crm
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Philipp Schuele <p.schuele@metaways.de>
 * @copyright   Copyright (c) 2009 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: LinkGridPanel.js 18870 2011-01-26 13:36:59Z p.schuele@metaways.de $
 *
 * TODO         add to extdoc
 */
 
Ext.ns('Tine.Crm.LinkGridPanel');

/**
 * @namespace   Tine.Crm.LinkGridPanel
 * 
 * TODO         move change contact type functions
 */
Tine.Crm.LinkGridPanel.initActions = function() {
    
    var app = Tine.Tinebase.appMgr.get(this.recordClass.getMeta('appName')); 
    if (! app) {
        return;
    }
        
    if (app.i18n) {
        var recordName = app.i18n.n_(
            this.recordClass.getMeta('recordName'), this.recordClass.getMeta('recordsName'), 1
        );
    } else {
        var recordName = this.recordClass.getMeta('recordName');
    }

    this.actionAdd = new Ext.Action({
        requiredGrant: 'editGrant',
        text: String.format(this.app.i18n._('Add new {0}'), recordName),
        tooltip: String.format(this.app.i18n._('Add new {0}'), recordName),
        iconCls: 'action_add',
        disabled: ! (this.record && this.record.get('container_id') 
            && this.record.get('container_id').account_grants 
            && this.record.get('container_id').account_grants.editGrant),
        scope: this,
        handler: function(_button, _event) {
            var editWindow = this.recordEditDialogOpener({
                listeners: {
                    scope: this,
                    'update': this.onUpdate
                }
            });
        }
    });
    
    this.actionUnlink = new Ext.Action({
        requiredGrant: 'editGrant',
        text: String.format(this.app.i18n._('Unlink {0}'), recordName),
        tooltip: String.format(this.app.i18n._('Unlink selected {0}'), recordName),
        disabled: true,
        iconCls: 'action_remove',
        onlySingle: true,
        scope: this,
        handler: function(_button, _event) {                       
            var selectedRows = this.getSelectionModel().getSelections();
            for (var i = 0; i < selectedRows.length; ++i) {
                this.store.remove(selectedRows[i]);
            }           
        }
    });
    
    this.actionEdit = new Ext.Action({
        requiredGrant: 'editGrant',
        text: String.format(this.app.i18n._('Edit {0}'), recordName),
        tooltip: String.format(this.app.i18n._('Edit selected {0}'), recordName),
        disabled: true,
        iconCls: 'actionEdit',
        onlySingle: true,
        scope: this,
        handler: function(_button, _event) {
            var selectedRows = this.getSelectionModel().getSelections();
            var record = selectedRows[0];
            // unset record id for new records
            if (record.phantom) {
                record.id = 0;
            }
            var editWindow = this.recordEditDialogOpener({
                record: record,
                listeners: {
                    scope: this,
                    'update': this.onUpdate
                }
            });
        }
    });

    // init toolbars and ctx menut / add actions
    this.bbar = [                
        this.actionAdd,
        this.actionUnlink
    ];
    
    this.actions = [
        this.actionEdit,
        this.actionUnlink
    ];
    
    if (this.otherActions) {
        this.actions = this.actions.concat(this.otherActions);
    }

    this.contextMenu = new Ext.menu.Menu({
        items: this.actions.concat(['-', this.actionAdd])
    });
    
    this.actions.push(this.actionAdd);
};

/**
 * init store
 * 
 */ 
Tine.Crm.LinkGridPanel.initStore = function() {
    
    this.store = new Ext.data.JsonStore({
        fields: (this.storeFields) ? this.storeFields : this.recordClass
    });

    // focus+select new record
    this.store.on('add', function(store, records, index) {
        (function() {
            if (this.rendered) {
                this.getView().focusRow(index);
                this.getSelectionModel().selectRow(index); 
            }
        }).defer(300, this);
    }, this);
};

/**
 * init ext grid panel
 * 
 * TODO         add grants for linked entries to disable EDIT?
 */
Tine.Crm.LinkGridPanel.initGrid = function() {
    this.cm = this.getColumnModel();
    
    this.selModel = new Ext.grid.RowSelectionModel({multiSelect:true});
    this.enableHdMenu = false;
    this.plugins = this.plugins || [];
    this.plugins.push(new Ext.ux.grid.GridViewMenuPlugin({}));

    // on selectionchange handler
    this.selModel.on('selectionchange', function(sm) {
        var rowCount = sm.getCount();
        var selectedRows = this.getSelectionModel().getSelections();
        if (selectedRows.length > 0) {
            var selectedRecord = selectedRows[0];
        }
        if (this.record && (this.record.get('container_id') && this.record.get('container_id').account_grants)) {
            for (var i=0; i < this.actions.length; i++) {
                this.actions[i].setDisabled(
                    ! this.record.get('container_id').account_grants.editGrant 
                    || (this.actions[i].initialConfig.onlySingle && rowCount != 1)
                    || (this.actions[i] == this.actionEdit && selectedRecord && selectedRecord.phantom == true)
                );
            }
        }
        
    }, this);
    
    // on rowcontextmenu handler
    this.on('rowcontextmenu', function(grid, row, e) {
        e.stopEvent();
        var selModel = grid.getSelectionModel();
        if(!selModel.isSelected(row)) {
            selModel.selectRow(row);
        }
        
        this.contextMenu.showAt(e.getXY());
    }, this);
    
    // doubleclick handler
    this.on('rowdblclick', function(grid, row, e) {
        var selectedRows = grid.getSelectionModel().getSelections();
        record = selectedRows[0];
        if (! record.phantom && this.recordEditDialogOpener != Ext.emptyFn) {
            var editWindow = this.recordEditDialogOpener({
                record: record,
                listeners: {
                    scope: this,
                    'update': this.onUpdate
                }
            });
        }
    }, this);
};

//  update event handler for related records
//  
//  TODO make this generic
//--
//Tine.Crm.LinkGridPanel.onUpdate = function(record) {
//    var response = {
//        responseText: record
//    };
//    record = this.recordProxy.recordReader(response);
//    
//    var myRecord = this.store.getById(record.id);
//    if (myRecord) {
//        myRecord.beginEdit();
//        for (var p in record.data) { 
//            myRecord.set(p, record.get(p));
//        }
//        myRecord.endEdit();
//    } else {
//        this.store.add(record);
//    }
//};

// file: /home/lkneschke/temp/tine20build/temp/tine20/Crm/js/LeadGridContactFilter.js
/*
 * Tine 2.0
 * 
 * @package     Crm
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Cornelius Weiss <c.weiss@metaways.de>
 * @copyright   Copyright (c) 2009 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id:GridPanel.js 7170 2009-03-05 10:58:55Z p.schuele@metaways.de $
 *
 */
 
Ext.namespace('Tine.Crm');

/**
 * Lead grid panel
 * 
 * @namespace   Tine.Crm
 * @class       Tine.Crm.LeadGridContactFilter
 * @extends     Tine.widgets.grid.FilterModel
 * 
 * <p>Contact Filter for Lead Grid Panel</p>
 * 
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Cornelius Weiss <c.weiss@metaways.de>
 * @copyright   Copyright (c) 2009 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id:GridPanel.js 7170 2009-03-05 10:58:55Z p.schuele@metaways.de $
 * 
 * @param       {Object} config
 * @constructor
 * Create a new Tine.Crm.LeadGridContactFilter
 */
Tine.Crm.LeadGridContactFilter = Ext.extend(Tine.widgets.grid.FilterModel, {
    isForeignFilter: true,
    foreignField: 'id',
    ownField: 'contact',
    
    /**
     * @private
     */
    initComponent: function() {
        Tine.widgets.tags.TagFilter.superclass.initComponent.call(this);
        
        this.subFilterModels = [];
        
        this.app = Tine.Tinebase.appMgr.get('Crm');
        this.label = this.app.i18n._("Contact");
        this.operators = ['equals'];
    },
    
    getSubFilters: function() {
        var filterConfigs = Tine.Addressbook.Model.Contact.getFilterModel();
        
        var contactRoleFilter = new Tine.widgets.grid.FilterModel({
            label: this.app.i18n._('CRM Role'),
            field: 'relation_type',
            operators: ['equals'],
            valueRenderer: function(filter, el) {
                var value = new Tine.Crm.Contact.TypeComboBox({
                    filter: filter,
                    blurOnSelect: true,
                    width: 200,
                    listAlign: 'tr-br',
                    id: 'tw-ftb-frow-valuefield-' + filter.id,
                    value: filter.data.value ? filter.data.value : this.defaultValue,
                    renderTo: el
                });
                value.on('specialkey', function(field, e){
                     if(e.getKey() == e.ENTER){
                         this.onFiltertrigger();
                     }
                }, this);
                return value;
            }
        });
        
        this.subFilterModels.push(contactRoleFilter);
        
        Ext.each(filterConfigs, function(config) {
            this.subFilterModels.push(Tine.widgets.grid.FilterToolbar.prototype.createFilterModel.call(this, config));
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
        var value = new Tine.Addressbook.SearchCombo({
            filter: filter,
            blurOnSelect: true,
            width: 200,
            listWidth: 500,
            listAlign: 'tr-br',
            id: 'tw-ftb-frow-valuefield-' + filter.id,
            value: filter.data.value ? filter.data.value : this.defaultValue,
            renderTo: el,
            getValue: function() {
                return this.selectedRecord ? this.selectedRecord.id : null;
            }
        });
        value.on('specialkey', function(field, e){
             if(e.getKey() == e.ENTER){
                 this.onFiltertrigger();
             }
        }, this);
        
        return value;
    }
});
Tine.widgets.grid.FilterToolbar.FILTERS['crm.contact'] = Tine.Crm.LeadGridContactFilter;

// file: /home/lkneschke/temp/tine20build/temp/tine20/Crm/js/LeadGridPanel.js
/*
 * Tine 2.0
 * 
 * @package     Crm
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Philipp Schuele <p.schuele@metaways.de>
 * @copyright   Copyright (c) 2007-20010 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: LeadGridPanel.js 18080 2010-12-23 15:13:32Z p.schuele@metaways.de $
 *
 */
 
Ext.namespace('Tine.Crm');

/**
 * Lead grid panel
 * 
 * @namespace   Tine.Crm
 * @class       Tine.Crm.GridPanel
 * @extends     Tine.widgets.grid.GridPanel
 * 
 * <p>Lead Grid Panel</p>
 * <p><pre>
 * TODO         add products to grid?
 * </pre></p>
 * 
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Philipp Schuele <p.schuele@metaways.de>
 * @copyright   Copyright (c) 2007-2010 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: LeadGridPanel.js 18080 2010-12-23 15:13:32Z p.schuele@metaways.de $
 * 
 * @param       {Object} config
 * @constructor
 * Create a new Tine.Crm.GridPanel
 */
Tine.Crm.GridPanel = Ext.extend(Tine.widgets.grid.GridPanel, {
    /**
     * record class
     * @cfg {Tine.Crm.Model.Lead} recordClass
     */
    recordClass: Tine.Crm.Model.Lead,
    
    /**
     * eval grants
     * @cfg {Boolean} evalGrants
     */
    evalGrants: true,
    
    /**
     * grid specific
     * @private
     */
    defaultSortInfo: {field: 'lead_name', direction: 'DESC'},
    gridConfig: {
        loadMask: true,
        autoExpandColumn: 'title',
        // drag n drop
        enableDragDrop: true,
        ddGroup: 'containerDDGroup'
    },
     
    /**
     * inits this cmp
     * @private
     */
    initComponent: function() {
        this.recordProxy = Tine.Crm.leadBackend;
        
        this.gridConfig.cm = this.getColumnModel();
        this.filterToolbar = this.getFilterToolbar();
        
        this.plugins = this.plugins || [];
        this.plugins.push(this.filterToolbar);
        
        this.detailsPanel = new Tine.Crm.LeadGridDetailsPanel({
            grid: this
        });
        
        Tine.Crm.GridPanel.superclass.initComponent.call(this);
    },
    
    /**
     * add custom items to action toolbar
     * 
     * @return {Object}
     */
    getActionToolbarItems: function() {
        return [
            Ext.apply(new Ext.SplitButton(this.actions_exportLead), {
                scale: 'medium',
                rowspan: 2,
                iconAlign: 'top',
                arrowAlign:'right'
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
            this.actions_exportLead
        ];
        
        return items;
    },
    
    /**
     * initialises filter toolbar
     * 
     * @return Tine.widgets.grid.FilterToolbar
     * @private
     */
    getFilterToolbar: function() {
        var filters = [
            {label: _('Quick search'),  field: 'query',    operators: ['contains']},
            {filtertype: 'tine.widget.container.filtermodel', app: this.app, recordClass: Tine.Crm.Model.Lead},
            {label: this.app.i18n._('Lead name'),   field: 'lead_name' },
            {filtertype: 'crm.leadstate', app: this.app},
            {label: this.app.i18n._('Probability'), field: 'probability', valueType: 'percentage'},
            {label: this.app.i18n._('Turnover'),    field: 'turnover', valueType: 'number', defaultOperator: 'greater'},
            {filtertype: 'tinebase.tag', app: this.app},
            {label: this.app.i18n._('Last modified'),   field: 'last_modified_time', valueType: 'date'},
            {label: this.app.i18n._('Last modifier'),   field: 'last_modified_by',   valueType: 'user'},
            {label: this.app.i18n._('Creation Time'),   field: 'creation_time',      valueType: 'date'},
            {label: this.app.i18n._('Creator'),         field: 'created_by',         valueType: 'user'},
            {filtertype: 'crm.contact'},
            {filtertype: 'foreignrecord', app: this.app, foreignRecordClass: Tine.Tasks.Task, ownField: 'task'}
        ];
        
        if (Tine.Sales && Tine.Tinebase.common.hasRight('run', 'Sales')) {
            filters.push({filtertype: 'foreignrecord', 
                app: this.app,
                foreignRecordClass: Tine.Sales.Model.Product,
                ownField: 'product'
            });
        }
        
        return new Tine.widgets.grid.FilterToolbar({
            filterModels: filters,
            recordClass: this.recordClass,
            defaultFilter: 'query',
            filters: [
                {field: 'leadstate_id', operator: 'notin', value: Tine.Crm.LeadState.getClosedStatus()}
            ],
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
                sortable: true
            },
            columns: [
                {header: this.app.i18n._('Lead id'), id: 'id', dataIndex: 'id', width: 20, hidden: true},
                {header: this.app.i18n._('Tags'), id: 'tags', dataIndex: 'tags', width: 50, renderer: Tine.Tinebase.common.tagsRenderer, sortable: false},
                {header: this.app.i18n._('Lead name'), id: 'lead_name', dataIndex: 'lead_name', width: 200},
                {header: this.app.i18n._('Partner'), id: 'lead_partner', dataIndex: 'relations', width: 175, sortable: false, renderer: this.partnerRenderer},
                {header: this.app.i18n._('Customer'), id: 'lead_customer', dataIndex: 'relations', width: 175, sortable: false, renderer: this.customerRenderer},
                {header: this.app.i18n._('Leadstate'), id: 'leadstate_id', dataIndex: 'leadstate_id', sortable: false, width: 100, renderer: Tine.Crm.LeadState.Renderer},
                {header: this.app.i18n._('Probability'), id: 'probability', dataIndex: 'probability', width: 50, renderer: Ext.util.Format.percentage },
                {header: this.app.i18n._('Turnover'), id: 'turnover', dataIndex: 'turnover', width: 100, renderer: Ext.util.Format.euMoney },
                {header: this.app.i18n._('Probable Turnover'), id: 'probableTurnover', dataIndex: 'probableTurnover', width: 100, renderer: Ext.util.Format.euMoney }
            ].concat(this.getModlogColumns())
        });
    },

    /**
     * render partner contact
     * 
     * @param {Array} value
     * @return {String}
     */
    partnerRenderer: function(value) {
        return Tine.Crm.GridPanel.shortContactRenderer(value, 'PARTNER');
    },
    
    /**
     * render customer contact
     * 
     * @param {Array} value
     * @return {String}
     */
    customerRenderer: function(value) {
        return Tine.Crm.GridPanel.shortContactRenderer(value, 'CUSTOMER');
    },

    /**
     * @private
     */
    initActions: function(){
        this.actions_exportLead = new Ext.Action({
            text: this.app.i18n._('Export Lead'),
            iconCls: 'action_export',
            scope: this,
            requiredGrant: 'readGrant',
            disabled: true,
            allowMultiple: true,
            menu: {
                items: [
                    new Tine.widgets.grid.ExportButton({
                        text: this.app.i18n._('Export as PDF'),
                        iconCls: 'action_exportAsPdf',
                        format: 'pdf',
                        exportFunction: 'Crm.exportLead',
                        gridPanel: this
                    }),
                    new Tine.widgets.grid.ExportButton({
                        text: this.app.i18n._('Export as CSV'),
                        iconCls: 'tinebase-action-export-csv',
                        format: 'csv',
                        exportFunction: 'Crm.exportLead',
                        gridPanel: this
                    }),
                    new Tine.widgets.grid.ExportButton({
                        text: this.app.i18n._('Export as ODS'),
                        iconCls: 'tinebase-action-export-ods',
                        format: 'ods',
                        exportFunction: 'Crm.exportLead',
                        gridPanel: this
                    }),
                    new Tine.widgets.grid.ExportButton({
                        text: this.app.i18n._('Export as XLS'),
                        iconCls: 'tinebase-action-export-xls',
                        format: 'xls',
                        exportFunction: 'Crm.exportLead',
                        gridPanel: this
                    })
                ]
            }
        });
        
        this.actionUpdater.addActions([
            this.actions_exportLead
        ]);
        
        this.supr().initActions.call(this);
    }    
});

/**
 * contact column renderer
 * 
 * @param       {String} value
 * @param       {String} type (CUSTOMER|PARTNER)
 * @return      {String}
 * 
 * @namespace   Tine.Crm
 */
Tine.Crm.GridPanel.shortContactRenderer = function(data, type) {    

    if( Ext.isArray(data) && data.length > 0) {
        var index = 0;
        
        // get correct relation type from data (contact) array and show first matching record (org_name + n_fileas)
        while (index < data.length && data[index].type != type) {
            index++;
        }
        if (data[index]) {
            var org = (data[index].related_record.org_name !== null ) ? data[index].related_record.org_name : '';
            return '<b>' + Ext.util.Format.htmlEncode(org) + '</b><br />' + Ext.util.Format.htmlEncode(data[index].related_record.n_fileas);
        }
    }
};

// file: /home/lkneschke/temp/tine20build/temp/tine20/Crm/js/LeadGridDetailsPanel.js
/**
 * Tine 2.0
 * 
 * @package     Crm
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Philipp Schuele <p.schuele@metaways.de>
 * @copyright   Copyright (c) 2009 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id:GridPanel.js 7170 2009-03-05 10:58:55Z p.schuele@metaways.de $
 *
 */
 
Ext.namespace('Tine.Crm');

/**
 * @namespace   Tine.Crm
 * @class       Tine.Crm.LeadGridDetailsPanel
 * @extends     Tine.widgets.grid.DetailsPanel
 * 
 * <p>Lead Grid Details Panel</p>
 * <p>
 * </p>
 * 
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Philipp Schuele <p.schuele@metaways.de>
 * @copyright   Copyright (c) 2009 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id:GridPanel.js 7170 2009-03-05 10:58:55Z p.schuele@metaways.de $
 */
Tine.Crm.LeadGridDetailsPanel = Ext.extend(Tine.widgets.grid.DetailsPanel, {
    
    border: false,
    
    /**
     * renders contact names
     * 
     * @param {String} type
     * @return {String}
     * 
     * TODO add mail link
     * TODO all labels should have the same width
     */
    contactRenderer: function(type) {
        
        var relations = this.record.get('relations');
        
        var a = [];
        var fields = [{
            label: (type == 'CUSTOMER') ? this.app.i18n._('Customer') : this.app.i18n._('Partner'),
            dataField: 'n_fileas'
        }, {
            label: this.app.i18n._('Phone'),
            dataField: 'tel_work'
        }, {
            label: this.app.i18n._('Mobile'),
            dataField: 'tel_cell'
        }, {
            label: this.app.i18n._('Fax'),
            dataField: 'tel_fax'
        }, {
            label: this.app.i18n._('E-Mail'),
            dataField: 'email'
        }, {
            label: this.app.i18n._('Web'),
            dataField: 'url'
        }];
        var labelMarkup = '<label class="x-form-item x-form-item-label">';
        
        if (Ext.isArray(relations) && relations.length > 0) {
            // get correct relation type from relations (contact) array
            for (var i = 0; i < relations.length; i++) {
                if (relations[i].type == type) {
                    var data = relations[i].related_record;
                    for (var j=0; j < fields.length; j++) {
                        if (data[fields[j].dataField]) {
                            if (fields[j].dataField == 'url') {
                                a.push(labelMarkup + fields[j].label + ':</label> '
                                    + '<a href="' + Ext.util.Format.htmlEncode(data[fields[j].dataField]) + '" target="_blank">' 
                                    + Ext.util.Format.htmlEncode(data[fields[j].dataField]) + '</a>');
                            } else {
                                a.push(labelMarkup + fields[j].label + ':</label> '  + Ext.util.Format.htmlEncode(data[fields[j].dataField]));
                            }
                        }
                    }
                    a.push('');
                }
            }
        }
        
        return a.join("\n");
        
        /*
        getMailLink: function(email, felamimail) {
                    if (! email) {
                        return '';
                    }
                    
                    var link = (felamimail) ? '#' : 'mailto:' + email;
                    var id = Ext.id() + ':' + email;
                    
                    return '<a href="' + link + '" class="tinebase-email-link" id="' + id + '">'
                        + Ext.util.Format.ellipsis(email, 18); + '</a>';
                }
         */
    },
    
    /**
     * renders container name
     * 
     * @param {Array} container
     * @return {String} html
     * 
     * TODO generalize this?
     * TODO add button/link to switch to container?
     */
    containerRenderer: function(container) {
        return this.containerTpl.apply({
            name: Ext.util.Format.htmlEncode(container && container.name ? container.name : '')
        });
    },
    
    /**
     * lead state renderer
     * 
     * @param   {Number} id
     * @param   {Store} store
     * @return  {String} label
     * @return  {String} label
     */
    sourceTypeRenderer: function(id, store, definitionsLabel) {
        record = store.getById(id);
        if (record) {
            return record.data[definitionsLabel];
        } else {
            return 'undefined';
        }
    },
    
    /**
     * inits this component
     */
    initComponent: function() {
        this.app = Tine.Tinebase.appMgr.get('Crm');
        
        // define piechart stores
        this.leadstatePiechartStore = new Ext.data.JsonStore({
            fields: ['id', 'label', 'total'],
            id: 'id'
        });
        this.leadsourcePiechartStore = new Ext.data.JsonStore({
            fields: ['id', 'label', 'total'],
            id: 'id'
        });
        this.leadtypePiechartStore = new Ext.data.JsonStore({
            fields: ['id', 'label', 'total'],
            id: 'id'
        });
        
        /*
        this.defaultPanel = this.getDefaultPanel();
        this.leadDetailsPanel = this.getLeadGridDetailsPanel();
        
        this.cardPanel = new Ext.Panel({
            layout: 'card',
            border: false,
            activeItem: 0,
            items: [
                this.defaultPanel,
                this.leadDetailsPanel
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
                '<span style="color: {color};">&nbsp</span>',
                '<span>{name}</span>',
            '</div>'
        ).compile();
        
        this.supr().initComponent.call(this);
    },
    
    /**
     * default panel w.o. data
     * 
     * @return {Ext.ux.display.DisplayPanel}
     * 
     * TODO add legend?
     */
    getDefaultInfosPanel: function() {
        if (! this.defaultInfosPanel) {
            this.defaultInfosPanel = new Ext.ux.display.DisplayPanel({
                layout: 'fit',
                border: false,
                items: [{
                    layout: 'hbox',
                    border: false,
                    defaults:{
                        margins:'0 5 0 0',
                        padding: 2,
                        style: {
                            cursor: 'crosshair'
                        },
                        flex: 1,
                        layout: 'ux.display',
                        border: false
                    },
                    layoutConfig: {
                        padding:'5',
                        align:'stretch'
                    },
                    items: [{
                        layoutConfig: {
                            background: 'border',
                            declaration: this.app.i18n._('Leadstates')
                        },
                        items: [{
                            store: this.leadstatePiechartStore,
                            xtype: 'piechart',
                            dataField: 'total',
                            categoryField: 'label'
                        }]
                    }, {
                        layoutConfig: {
                            background: 'border',
                            declaration: this.app.i18n._('Leadsources')
                        },
                        items: [{
                            store: this.leadsourcePiechartStore,
                            xtype: 'piechart',
                            dataField: 'total',
                            categoryField: 'label'
                        }]
                    }, {
                        layoutConfig: {
                            background: 'border',
                            declaration: this.app.i18n._('Leadtypes')
                        },
                        items: [{
                            store: this.leadtypePiechartStore,
                            xtype: 'piechart',
                            dataField: 'total',
                            categoryField: 'label'
                        }]
                    }]
                }]
                /*
                    fieldLabel: this.app.i18n._('Leadstates'), // ??
                    xtype: 'piechart',
                    store: this.leadstatePiechartStore,
                    dataField: 'total',
                    categoryField: 'label',
                    backgroundColor: '#eeeeee' // ??
                    //extra styles get applied to the chart defaults
                    extraStyle: {
                        legend: {
                            //display: 'right',
                            display: 'top',
                            padding: 5,
                            font: {
                                family: 'Tahoma',
                                size: 8
                            }
                        }
                    } 
                */               
            });
        }
        
        return this.defaultInfosPanel;
    },
    
    /**
     * fill the piechart stores (calls loadPiechartStore() for all piecharts)
     */
    setPiechartStores: function(getFromRequest) {
        
        if (! this.getDefaultInfosPanel().isVisible()) {
            return;
        }
        
        if (getFromRequest === false) {
            var data = this.getCountFromSelection();
        } else {
            var data = {
                leadstate: this.grid.store.proxy.jsonReader.jsonData.totalleadstates,
                leadsource: this.grid.store.proxy.jsonReader.jsonData.totalleadsources,
                leadtype: this.grid.store.proxy.jsonReader.jsonData.totalleadtypes
            };
        }
        
        //console.log(data);
        
        var storesConfig = [{
            store: this.leadstatePiechartStore,
            data: data.leadstate,
            definitionsStore: Tine.Crm.LeadState.getStore(),
            definitionsLabel: 'leadstate'
        }, {
            store: this.leadsourcePiechartStore,
            data: data.leadsource,
            definitionsStore: Tine.Crm.LeadSource.getStore(),
            definitionsLabel: 'leadsource'
        }, {
            store: this.leadtypePiechartStore,
            data: data.leadtype,
            definitionsStore: Tine.Crm.LeadType.getStore(),
            definitionsLabel: 'leadtype'
        }];
        
        for (var i = 0; i < storesConfig.length; i++) {
            this.loadPiechartStore(storesConfig[i]);
        }
    },
    
    /**
     * get leadstzate/source/type count for charts from selection
     * 
     * @return {}
     */
    getCountFromSelection: function() {
      
        var result = {
            leadstate: {},
            leadsource: {},
            leadtype: {}
        };
        
        var selectedRows = this.grid.getSelectionModel().getSelections();
        for (var i = 0; i < selectedRows.length; ++i) {
            //console.log(selectedRows[i]);
            if (! result.leadstate[selectedRows[i].get('leadstate_id')]) {
                result.leadstate[selectedRows[i].get('leadstate_id')] = 1;
            } else {
                result.leadstate[selectedRows[i].get('leadstate_id')]++;
            }

            if (! result.leadsource[selectedRows[i].get('leadsource_id')]) {
                result.leadsource[selectedRows[i].get('leadsource_id')] = 1;
            } else {
                result.leadsource[selectedRows[i].get('leadsource_id')]++;
            }

            if (! result.leadtype[selectedRows[i].get('leadtype_id')]) {
                result.leadtype[selectedRows[i].get('leadtype_id')] = 1;
            } else {
                result.leadtype[selectedRows[i].get('leadtype_id')]++;
            }
        }
        
        return result;
    },
    
    /**
     * load data into piechart store
     * 
     * @param {} config
     */
    loadPiechartStore: function(config) {
        try {
            if (config.store.getCount() > 0) {
                config.store.removeAll();
            }
            
            // get records from defintion / grid store request
            var records = []; 
            if (config.data) {
                config.definitionsStore.each(function(definition) {
                    if (config.data[definition.id]) {
                        records.push(new config.store.recordType({
                            id: definition.id,
                            label: definition.get(config.definitionsLabel),
                            total: config.data[definition.id]
                        }, definition.id));
                    }
                }, this);
            }
            
            // add new records
            if (records.length > 0) {
                config.store.add(records);
            }
        } catch (e) {
            //console.log('error while setting ' + config.definitionsLabel + ' piechart data');
            //console.log(e);
            
            // some error with the piechart occurred, try it again ...
            this.loadPiechartStore.defer(500, this, [config]);
        }
    },
    
    /**
     * get panel for multi selection aggregates/information
     * 
     * @return {Ext.Panel}
     */
    getMultiRecordsPanel: function() {
        return this.getDefaultInfosPanel();
    },
    
    /**
     * main lead details panel
     * 
     * @return {Ext.ux.display.DisplayPanel}
     * 
     * TODO add tasks / products?
     * TODO add contact icons?
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
                    items: [
                        {
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
                            cls: 'x-ux-display-header',
                            //style: 'padding-top: 2px',
                            name: 'lead_name'
                        }, {
                            flex: 1,
                            xtype: 'ux.displayfield',
                            style: 'text-align: right;',
                            name: 'container_id',
                            cls: 'x-ux-display-header',
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
                        defaults:{margins:'0 5 0 0'},
                        items: [{
                            flex: 1,
                            layout: 'ux.display',
                            labelWidth: 90,
                            layoutConfig: {
                                background: 'solid',
                                declaration: this.app.i18n._('Status')
                            },
                            items: [{
                                xtype: 'ux.displayfield',
                                name: 'start',
                                fieldLabel: this.app.i18n._('Start'),
                                renderer: Tine.Tinebase.common.dateRenderer
                            }, {
                                xtype: 'ux.displayfield',
                                name: 'end_scheduled',
                                fieldLabel: this.app.i18n._('Estimated end'),
                                renderer: Tine.Tinebase.common.dateRenderer
                            }, {
                                xtype: 'ux.displayfield',
                                name: 'leadtype_id',
                                fieldLabel: this.app.i18n._('Leadtype'),
                                renderer: this.sourceTypeRenderer.createDelegate(this, [Tine.Crm.LeadType.getStore(), 'leadtype'], true)
                            }, {
                                xtype: 'ux.displayfield',
                                name: 'leadsource_id',
                                fieldLabel: this.app.i18n._('Leadsource'),
                                renderer: this.sourceTypeRenderer.createDelegate(this, [Tine.Crm.LeadSource.getStore(), 'leadsource'], true)
                            }]
                        }, {
                            flex: 1,
                            layout: 'ux.display',
                            labelAlign: 'top',
                            autoScroll: true,
                            //cls: 'contactIconPartner',
                            layoutConfig: {
                                background: 'solid'
                                //declaration: this.app.i18n._('Partner')
                            },
                            items: [{
                                xtype: 'ux.displayfield',
                                name: 'partner',
                                nl2br: true,
                                htmlEncode: false,
                                renderer: this.contactRenderer.createDelegate(this, ['PARTNER'])
                            }]
                        }, {
                            flex: 1,
                            layout: 'ux.display',
                            labelAlign: 'top',
                            autoScroll: true,
                            //cls: 'contactIconCustomer',
                            layoutConfig: {
                                background: 'solid'
                                //declaration: this.app.i18n._('Customer')
                            },
                            items: [{
                                xtype: 'ux.displayfield',
                                name: 'customer',
                                nl2br: true,
                                htmlEncode: false,
                                renderer: this.contactRenderer.createDelegate(this, ['CUSTOMER'])
                            }]
                        }, {
                            flex: 1,
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
        
        return this.singleRecordPanel
    },
    
    /**
     * update lead details panel
     * 
     * @param {Tine.Tinebase.data.Record} record
     * @param {Mixed} body
     */
    updateDetails: function(record, body) {
        this.getSingleRecordPanel().loadRecord.defer(100, this.getSingleRecordPanel(), [record]);
    },
    
    /**
     * show default panel
     * 
     * @param {Mixed} body
     */
    showDefault: function(body) {
        this.setPiechartStores.defer(500, this, [true]);
    },
    
    /**
     * show template for multiple rows
     * 
     * @param {Ext.grid.RowSelectionModel} sm
     * @param {Mixed} body
     */
    showMulti: function(sm, body) {
        this.setPiechartStores.defer(1000, this, [false]);
    }
    
    /*
    TODO move this to generic grid panel?
    onClick: function(e) {
        var target = e.getTarget('a[class=tinebase-email-link]');
        if (target) {
            var email = target.id.split(':')[1];
            var defaults = Tine.Felamimail.Model.Message.getDefaultData();
            defaults.to = [email];
            defaults.body = Tine.Felamimail.getSignature();
            
            var record = new Tine.Felamimail.Model.Message(defaults, 0);
            var popupWindow = Tine.Felamimail.MessageEditDialog.openWindow({
                record: record
            });
        }
    }
    */
});

// file: /home/lkneschke/temp/tine20build/temp/tine20/Crm/js/LeadEditDialog.js
/*
 * Tine 2.0
 * 
 * @package     Crm
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Philipp Schuele <p.schuele@metaways.de>
 * @copyright   Copyright (c) 2007-2008 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id:LeadEditDialog.js 7170 2009-03-05 10:58:55Z p.schuele@metaways.de $
 *
 */
 
Ext.namespace('Tine.Crm');

/**
 * @namespace   Tine.Crm
 * @class       Tine.Crm.LeadEditDialog
 * @extends     Tine.widgets.dialog.EditDialog
 * 
 * <p>Lead Edit Dialog</p>
 * <p>
 * TODO         simplify relation handling (move init of stores to relation grids and get data from there later?)
 * TODO         make marking of invalid fields work again
 * TODO         add export button
 * TODO         disable link grids if user has no run right for the app (adb/tasks/sales)
 * </p>
 * 
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Philipp Schuele <p.schuele@metaways.de>
 * @copyright   Copyright (c) 2007-2008 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id:LeadEditDialog.js 7170 2009-03-05 10:58:55Z p.schuele@metaways.de $
 * 
 * @param       {Object} config
 * @constructor
 * Create a new Tine.Crm.LeadEditDialog
 */
Tine.Crm.LeadEditDialog = Ext.extend(Tine.widgets.dialog.EditDialog, {
    
    /**
     * linked contacts grid
     * 
     * @type Tine.Crm.Contact.GridPanel
     * @property contactGrid
     */
    contactGrid: null,
    
    /**
     * linked tasks grid
     * 
     * @type Tine.Crm.Task.GridPanel
     * @property tasksGrid
     */
    tasksGrid: null,
    
    /**
     * @private
     */
    windowNamePrefix: 'LeadEditWindow_',
    appName: 'Crm',
    recordClass: Tine.Crm.Model.Lead,
    recordProxy: Tine.Crm.leadBackend,
    tbarItems: [{xtype: 'widget-activitiesaddbutton'}],
    evalGrants: false,
    showContainerSelector: true,

    /**
     * overwrite update toolbars function (we don't have record grants yet)
     * @private
     */
    updateToolbars: function() {

    },
    
    /**
     * executed after record got updated from proxy
     * 
     * @private
     */
    onRecordLoad: function() {

        // load contacts/tasks/products into link grid (only first time this function gets called/store is empty)
        if (this.contactGrid && this.tasksGrid && this.productsGrid 
            && this.contactGrid.store.getCount() == 0 
            && (! this.tasksGrid.store || this.tasksGrid.store.getCount() == 0) 
            && (! this.productsGrid.store || this.productsGrid.store.getCount() == 0)) {
                    
            var relations = this.splitRelations();
            //console.log(relations);
            
            this.contactGrid.store.loadData(relations.contacts, true);
            
            if (this.tasksGrid.store) {
                this.tasksGrid.store.loadData(relations.tasks, true);
            }
            if (this.productsGrid.store) {
                this.productsGrid.store.loadData(relations.products, true);
            }
        }
        
        Tine.Crm.LeadEditDialog.superclass.onRecordLoad.call(this);        
    },
    
    /**
     * executed when record gets updated from form
     * - add attachments to record here
     * 
     * @private
     */
    onRecordUpdate: function() {
        Tine.Crm.LeadEditDialog.superclass.onRecordUpdate.call(this);
        
        this.getAdditionalData();
    },
    
    /**
     * getRelationData
     * get the record relation data (switch relation and related record)
     * 
     * @param   Object record with relation data
     * @return  Object relation with record data
     */
    getRelationData: function(record) {
        var relation = null; 
        
        if (record.data.relation) {
            relation = record.data.relation;
        } else {
            // empty relation for new record
            relation = {};
        }

        // set the relation type
        if (!relation.type) {
            relation.type = record.data.relation_type.toUpperCase();
        }
        
        // do not do recursion!
        delete record.data.relation;
        //delete record.data.relation_type;
        
        // save record data        
        relation.related_record = record.data;
        
        // add remark values
        relation.remark = {};
        if (record.data.remark_price) {
            relation.remark.price = record.data.remark_price;
        }
        if (record.data.remark_description) {
            relation.remark.description = record.data.remark_description;
        }
        if (record.data.remark_quantity) {
            relation.remark.quantity = record.data.remark_quantity;
        }
        
        return relation;
    },

    /**
     * getAdditionalData
     * collects additional data (start/end dates, linked contacts, ...)
     * 
     */
    getAdditionalData: function() {
        
        // collect data of relations
        var relations = [];
        this.contactGrid.store.each(function(record) {                     
            relations.push(this.getRelationData(record));
        }, this);
        if (this.tasksGrid.store) {
            this.tasksGrid.store.each(function(record) {
                relations.push(this.getRelationData(record));
            }, this);
        }
        if (this.productsGrid.store) {
            this.productsGrid.store.each(function(record) {
                relations.push(this.getRelationData(record));
            }, this);
        }
        
        this.record.data.relations = relations;
    },
    
    /**
     * split the relations array in contacts and tasks and switch related_record and relation objects
     * 
     * @return {Array}
     */
    splitRelations: function() {
        
        var contacts = [];
        var tasks = []
        var products = []
        
        var relations = this.record.get('relations');
        
        for (var i=0; i < relations.length; i++) {
            var newLinkObject = relations[i]['related_record'];
            delete relations[i]['related_record']['relation'];
            newLinkObject.relation = relations[i];
            newLinkObject.relation_type = relations[i]['type'].toLowerCase();
    
            //console.log(newLinkObject);
            if ((newLinkObject.relation_type === 'responsible' 
              || newLinkObject.relation_type === 'customer' 
              || newLinkObject.relation_type === 'partner')) {
                contacts.push(newLinkObject);
            } else if (newLinkObject.relation_type === 'task') {                
                tasks.push(newLinkObject);
            } else if (newLinkObject.relation_type === 'product') {
                newLinkObject.remark_description = relations[i].remark.description;
                newLinkObject.remark_price = relations[i].remark.price;
                newLinkObject.remark_quantity = relations[i].remark.quantity;
                products.push(newLinkObject);
            }
        }

        return {
            contacts: contacts,
            tasks: tasks,
            products: products
        };
    },

    /**
     * returns dialog
     * 
     * NOTE: when this method gets called, all initalisation is done.
     * 
     * @return {Object}
     * @private
     */
    getFormItems: function() {
        
        this.combo_probability = new Ext.ux.PercentCombo({
            fieldLabel: this.app.i18n._('Probability'), 
            id: 'combo_probability',
            anchor:'95%',            
            name:'probability'
        });
        
        this.date_end = new Ext.ux.form.ClearableDateField({
            fieldLabel: this.app.i18n._('End'), 
            id: 'end',
            anchor: '95%'
        });
        
        this.contactGrid = new Tine.Crm.Contact.GridPanel({
            record: this.record,
            anchor: '100% 98%'
        });

        if (Tine.Tasks && Tine.Tinebase.common.hasRight('run', 'Tasks')) {
            this.tasksGrid = new Tine.Crm.Task.GridPanel({
                record: this.record
            });
        } else {
            this.tasksGrid = new Ext.Panel({
                title: this.app.i18n._('Tasks'),
                html: this.app.i18n._('You do not have the run right for the Tasks application or it is not activated.')
            })
        }
        
        if (Tine.Sales && Tine.Tinebase.common.hasRight('run', 'Sales')) {
            this.productsGrid = new Tine.Crm.Product.GridPanel({
                record: this.record
            });
        } else {
            this.productsGrid = new Ext.Panel({
                title: this.app.i18n._('Products'),
                html: this.app.i18n._('You do not have the run right for the Sales application or it is not activated.')
            })
        }
        
        return {
            xtype: 'tabpanel',
            border: false,
            plain:true,
            activeTab: 0,
            border: false,
            items:[{
                title: this.app.i18n._('Lead'),
                autoScroll: true,
                border: true,
                frame: true,
                layout: 'border',
                id: 'editCenterPanel',
                defaults: {
                    border: true,
                    frame: true            
                },
                items: [{
                    region: 'center',
                    layout: 'border',
                    items: [{
                        region: 'north',
                        height: 40,
                        layout: 'form',
                        labelAlign: 'top',
                        defaults: {
                            anchor: '100%',
                            labelSeparator: '',
                            columnWidth: 1
                        },
                        items: [{
                            xtype:'textfield',
                            hideLabel: true,
                            id: 'lead_name',
                            emptyText: this.app.i18n._('Enter short name'),
                            name:'lead_name',
                            allowBlank: false,
                            selectOnFocus: true,
                            // TODO make this work
                            listeners: {render: function(field){field.focus(false, 2000);}}
                        }]
                    }, {
                        region: 'center',
                        layout: 'form',
                        items: [ this.contactGrid ]
                    }, {
                        region: 'south',
                        height: 390,
                        split: true,
                        collapseMode: 'mini',
                        header: false,
                        collapsible: true,
                        items: [{
                            xtype: 'panel',
                            layout:'column',
                            height: 140,
                            id: 'lead_combos',
                            anchor:'100%',
                            labelAlign: 'top',
                            items: [{
                                columnWidth: 0.33,
                                items:[{
                                    layout: 'form',
                                    defaults: {
                                        valueField:'id',
                                        typeAhead: true,
                                        mode: 'local',
                                        triggerAction: 'all',
                                        editable: false,
                                        allowBlank: false,
                                        forceSelection: true,
                                        anchor:'95%',
                                        xtype: 'combo'
                                    },
                                    items: [{
                                        fieldLabel: this.app.i18n._('Leadstate'), 
                                        id:'leadstatus',
                                        name:'leadstate_id',
                                        store: Tine.Crm.LeadState.getStore(),
                                        displayField:'leadstate',
                                        lazyInit: false,
                                        value: Tine.Crm.LeadState.getStore().getAt(0).id,
                                        listeners: {
                                            'select': function(combo, record, index) {
                                                if (this.record.data.probability !== null) {
                                                    this.combo_probability.setValue(record.data.probability);
                                                }
                                                if (record.data.endslead == '1') {
                                                    this.date_end.setValue(new Date());
                                                }
                                            },
                                            scope: this
                                        }
                                    }, {
                                        fieldLabel: this.app.i18n._('Leadtype'), 
                                        id:'leadtype',
                                        name:'leadtype_id',
                                        store: Tine.Crm.LeadType.getStore(),
                                        value: Tine.Crm.LeadType.getStore().getAt(0).id,
                                        displayField:'leadtype'
                                    }, {
                                        fieldLabel: this.app.i18n._('Leadsource'), 
                                        id:'leadsource',
                                        name:'leadsource_id',
                                        store: Tine.Crm.LeadSource.getStore(),
                                        value: Tine.Crm.LeadSource.getStore().getAt(0).id,
                                        displayField:'leadsource'
                                    }]
                                }]
                            }, {
                                columnWidth: 0.33,
                                items:[{
                                    layout: 'form',
                                    border:false,
                                    items: [
                                    {
                                        xtype:'numberfield',
                                        fieldLabel: this.app.i18n._('Expected turnover'), 
                                        name: 'turnover',
                                        selectOnFocus: true,
                                        anchor: '95%',
                                        minValue: 0
                                    },  
                                        this.combo_probability
                                    ]
                                }]
                            }, {
                                columnWidth: 0.33,
                                items:[{
                                    layout: 'form',
                                    border:false,
                                    items: [
                                        new Ext.form.DateField({
                                            fieldLabel: this.app.i18n._('Start'), 
                                            allowBlank: false,
                                            id: 'start',             
                                            anchor: '95%'
                                        }),
                                        new Ext.ux.form.ClearableDateField({
                                            fieldLabel: this.app.i18n._('Estimated end'), 
                                            id: 'end_scheduled',
                                            anchor: '95%'
                                        }),
                                        this.date_end   
                                    ]
                                }]
                            }]
                        }, {
                            xtype: 'tabpanel',
                            id: 'linkPanelBottom',
                            activeTab: 0,
                            height: 250,
                            items: [
                                this.tasksGrid,
                                this.productsGrid
                            ]
                        }]
                    }] // end of center lead panel with border layout
                    }, {
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
                            new Ext.Panel({
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
                            }),
                            new Tine.widgets.activities.ActivitiesPanel({
                                app: 'Crm',
                                showAddNoteForm: false,
                                border: false,
                                bodyStyle: 'border:1px solid #B5B8C8;'
                            }),
                            new Tine.widgets.tags.TagPanel({
                                app: 'Crm',
                                border: false,
                                bodyStyle: 'border:1px solid #B5B8C8;'
                            })
                        ]} // end of accordion panel (east)
                    ] // end of lead tabpanel items
            }, new Tine.widgets.activities.ActivitiesTabPanel({
                    app: this.appName,
                    record_id: this.record.id,
                    record_model: this.appName + '_Model_' + this.recordClass.getMeta('modelName')
               }) // end of activities tabpanel
            ] // end of main tabpanel items
        }; // end of return
    } // end of getFormItems
});

/**
 * Crm Edit Popup
 * 
 * @param   {Object} config
 * @return  {Ext.ux.Window}
 */
Tine.Crm.LeadEditDialog.openWindow = function (config) {
    var id = (config.record && config.record.id) ? config.record.id : 0;
    var window = Tine.WindowFactory.getWindow({
        width: 800,
        height: 750,
        name: Tine.Crm.LeadEditDialog.prototype.windowNamePrefix + id,
        contentPanelConstructor: 'Tine.Crm.LeadEditDialog',
        contentPanelConstructorConfig: config
    });
    return window;
};

// file: /home/lkneschke/temp/tine20build/temp/tine20/Crm/js/AdminPanel.js
/*
 * Tine 2.0
 * 
 * @package     Crm
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Philipp Schuele <p.schuele@metaways.de>
 * @copyright   Copyright (c) 2009-2010 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: AdminPanel.js 17913 2010-12-17 11:18:34Z p.schuele@metaways.de $
 *
 */

Ext.namespace('Tine.Crm');

/**
 * admin settings panel
 * 
 * @namespace   Tine.Crm
 * @class       Tine.Crm.AdminPanel
 * @extends     Tine.widgets.dialog.EditDialog
 * 
 * <p>Crm Admin Panel</p>
 * <p><pre>
 * TODO         generalize this
 * TODO         revert/rollback changes onCancel
 * </pre></p>
 * 
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Philipp Schuele <p.schuele@metaways.de>
 * @copyright   Copyright (c) 2009-2010 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: AdminPanel.js 17913 2010-12-17 11:18:34Z p.schuele@metaways.de $
 * 
 * @param       {Object} config
 * @constructor
 * Create a new Tine.Crm.AdminPanel
 */
Tine.Crm.AdminPanel = Ext.extend(Tine.widgets.dialog.EditDialog, {
    /**
     * @private
     */
    //windowNamePrefix: 'LeadEditWindow_',
    appName: 'Crm',
    recordClass: Tine.Crm.Model.Settings,
    recordProxy: Tine.Crm.settingsBackend,
    evalGrants: false,
    
    /**
     * overwrite update toolbars function (we don't have record grants yet)
     * @private
     */
    updateToolbars: function() {
    },
    
    /**
     * executed after record got updated from proxy
     * 
     * @private
     */
    onRecordLoad: function() {
        if (! this.record.get('default_leadstate_id') ) {
            this.record.set('default_leadstate_id', this.record.data.defaults.leadstate_id);
            this.record.set('default_leadsource_id', this.record.data.defaults.leadsource_id);
            this.record.set('default_leadtype_id', this.record.data.defaults.leadtype_id);
        }
        
        if (this.fireEvent('load', this) !== false) {
            this.getForm().loadRecord(this.record);
            this.updateToolbars(this.record, this.recordClass.getMeta('containerProperty'));
            
            this.loadMask.hide();
        }
    },
    
    /**
     * executed when record gets updated from form
     * - add attachments to record here
     * 
     * @private
     * 
     */
    onRecordUpdate: function() {
        Tine.Crm.AdminPanel.superclass.onRecordUpdate.call(this);
        
        var defaults = {
            leadstate_id: this.record.get('default_leadstate_id'), 
            leadsource_id: this.record.get('default_leadsource_id'), 
            leadtype_id: this.record.get('default_leadtype_id')
        };
        
        this.record.set('defaults', defaults);
        
        // save leadstate / commit store
        this.record.set('leadstates', this.getFromStore(this.leadstatePanel.store));
        this.record.set('leadtypes', this.getFromStore(this.leadtypePanel.store));
        this.record.set('leadsources', this.getFromStore(this.leadsourcePanel.store));
    },
    
    /**
     * get values from store (as array)
     * 
     * @param {Ext.data.JsonStore} store
     * @return {Array}
     */
    getFromStore: function(store) {
        var result = [];
        store.each(function(record) {                     
            result.push(record.data);
        }, this);
        store.commitChanges();
        
        return result;
    },
    
    /**
     * returns dialog
     * 
     * NOTE: when this method gets called, all initalisation is done.
     * 
     * @return {Object}
     * @private
     */
    getFormItems: function() {
        
        this.leadstatePanel = new Tine.Crm.LeadState.GridPanel({
            title: this.app.i18n._('Leadstates')
        });
        
        this.leadtypePanel = new Tine.Crm.LeadType.GridPanel({
            title: this.app.i18n._('Leadtypes')
        });
        
        this.leadsourcePanel = new Tine.Crm.LeadSource.GridPanel({
            title: this.app.i18n._('Leadsources')
        });
        
        return {
            xtype: 'tabpanel',
            activeTab: 0,
            border: true,
            items: [{
                title: this.app.i18n._('Defaults'),
                autoScroll: true,
                border: false,
                frame: true,
                xtype: 'columnform',
                formDefaults: {
                    xtype:'combo',
                    anchor: '90%',
                    labelSeparator: '',
                    columnWidth: 1,
                    valueField:'id',
                    typeAhead: true,
                    mode: 'local',
                    triggerAction: 'all',
                    editable: false,
                    allowBlank: false,
                    forceSelection: true
                },
                items: [[{
                    fieldLabel: this.app.i18n._('Leadstate'), 
                    name:'default_leadstate_id',
                    store: Tine.Crm.LeadState.getStore(),
                    displayField:'leadstate',
                    lazyInit: false,
                    value: Tine.Crm.LeadState.getStore().getAt(0).id
                }, {
                    fieldLabel: this.app.i18n._('Leadsource'), 
                    name:'default_leadsource_id',
                    store: Tine.Crm.LeadSource.getStore(),
                    displayField:'leadsource',
                    lazyInit: false,
                    value: Tine.Crm.LeadSource.getStore().getAt(0).id
                }, {
                    fieldLabel: this.app.i18n._('Leadtype'), 
                    name:'default_leadtype_id',
                    store: Tine.Crm.LeadType.getStore(),
                    displayField:'leadtype',
                    lazyInit: false,
                    value: Tine.Crm.LeadType.getStore().getAt(0).id
                }]]
            }, 
                this.leadstatePanel,
                this.leadtypePanel,
                this.leadsourcePanel
            ]            
        };                
    } // end of getFormItems
});

/**
 * admin panel on update function
 * 
 * TODO         update registry without reloading the mainscreen
 */
Tine.Crm.AdminPanel.onUpdate = function() {
    // reload mainscreen to make sure registry gets updated
    window.location = window.location.href.replace(/#+.*/, '');
}

/**
 * Crm admin settings popup
 * 
 * @param   {Object} config
 * @return  {Ext.ux.Window}
 */
Tine.Crm.AdminPanel.openWindow = function (config) {
    var id = (config.record && config.record.id) ? config.record.id : 0;
    var window = Tine.WindowFactory.getWindow({
        width: 600,
        height: 400,
        name: Tine.Crm.AdminPanel.prototype.windowNamePrefix + id,
        contentPanelConstructor: 'Tine.Crm.AdminPanel',
        contentPanelConstructorConfig: config
    });
    return window;
};

Ext.namespace('Tine.Crm.Admin');

/**
 * @namespace   Tine.Crm.Admin
 * @class       Tine.Crm.Admin.QuickaddGridPanel
 * @extends     Tine.widgets.grid.QuickaddGridPanel
 * 
 * admin config option quickadd grid panel
 */
Tine.Crm.Admin.QuickaddGridPanel = Ext.extend(Tine.widgets.grid.QuickaddGridPanel, {

    /**
     * @private
     */
    initComponent: function() {
        this.app = this.app ? this.app : Tine.Tinebase.appMgr.get('Crm');

        Tine.Crm.Admin.QuickaddGridPanel.superclass.initComponent.call(this);
    }
});

// file: /home/lkneschke/temp/tine20build/temp/tine20/Crm/js/LeadState.js
/*
 * Tine 2.0
 * lead state edit dialog and model
 * 
 * @package     Crm
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Cornelius Weiss <c.weiss@metaways.de>
 * @copyright   Copyright (c) 2007-2010 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: LeadState.js 16771 2010-10-22 10:41:45Z p.schuele@metaways.de $
 *
 * TODO         don't use json store anymore?
 */

Ext.namespace('Tine.Crm', 'Tine.Crm.LeadState');

/**
 * @namespace Tine.Crm.LeadState
 * @class Tine.Crm.LeadState.Model
 * @extends Ext.data.Record
 * 
 * lead state model
 */ 
Tine.Crm.LeadState.Model = Tine.Tinebase.data.Record.create([
    {name: 'id', type: 'int'},
    {name: 'leadstate'},
    {name: 'probability', type: 'int'},
    {name: 'endslead', type: 'boolean'}
], {
    appName: 'Crm',
    modelName: 'LeadState',
    idProperty: 'id',
    titleProperty: 'leadstate',
    // ngettext('Lead State', 'Lead States', n);
    recordName: 'Lead State',
    recordsName: 'Lead States'
});

/**
 * @namespace Tine.Crm.LeadState
 * 
 * get default data for a new leadstate
 *  
 * @return {Object} default data
 * @static
 */ 
Tine.Crm.LeadState.Model.getDefaultData = function() {
    
    var data = {
        id: Tine.Crm.Model.getRandomUnusedId(Ext.StoreMgr.get('CrmLeadstateStore'))
    };
    
    return data;
};

/**
 * get lead state store
 * if available, load data from Tine.Crm.registry.get('leadstates')
 *
 * @return {Ext.data.JsonStore}
 */
Tine.Crm.LeadState.getStore = function() {
	var store = Ext.StoreMgr.get('CrmLeadstateStore');
	if (!store) {
		// create store
		store = new Ext.data.JsonStore({
            fields: Tine.Crm.LeadState.Model,
            baseParams: {
                method: 'Crm.getLeadstates',
                sort: 'leadstate',
                dir: 'ASC'
            },
            root: 'results',
            totalProperty: 'totalcount',
            id: 'id',
            remoteSort: false
        });
        
        // check if initital data available
        if (Tine.Crm.registry.get('leadstates')) {
            store.loadData(Tine.Crm.registry.get('leadstates'));
        }
        
        Ext.StoreMgr.add('CrmLeadstateStore', store);
	}
	return store;
};

Tine.Crm.LeadState.getClosedStatus = function() {
    var reqStatus = [];
        
    Tine.Crm.LeadState.getStore().each(function(status) {
        if (status.get('endslead')) {
            reqStatus.push(status.get('id'));
        }
    }, this);
    
    return reqStatus;
};
/**
 * lead state renderer
 * 
 * @param   {Number} _leadstateId
 * @return  {String} leadstate
 */
Tine.Crm.LeadState.Renderer = function(_leadstateId) {
	leadstateStore = Tine.Crm.LeadState.getStore();		
	record = leadstateStore.getById(_leadstateId);
	
	if (record) {
	   return record.data.leadstate;
	} else {
		return 'undefined';
	}
};

/**
 * @namespace   Tine.Crm.LeadState
 * @class       Tine.Crm.LeadState.GridPanel
 * @extends     Tine.Crm.Admin.QuickaddGridPanel
 * 
 * lead states grid panel
 * 
 * <p>
 * </p>
 * 
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Philipp Schuele <p.schuele@metaways.de>
 * @copyright   Copyright (c) 2009 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: LeadState.js 16771 2010-10-22 10:41:45Z p.schuele@metaways.de $
 */
Tine.Crm.LeadState.GridPanel = Ext.extend(Tine.Crm.Admin.QuickaddGridPanel, {
    
    /**
     * @private
     */
    autoExpandColumn:'leadstate',
    quickaddMandatory: 'leadstate',

    /**
     * @private
     */
    initComponent: function() {
        this.app = this.app ? this.app : Tine.Tinebase.appMgr.get('Crm');
        
        this.store = Tine.Crm.LeadState.getStore();
        this.recordClass = Tine.Crm.LeadState.Model;
        this.cm = this.getColumnModel();
        
        Tine.Crm.LeadState.GridPanel.superclass.initComponent.call(this);
    },
    
    getColumnModel: function() {
        return new Ext.grid.ColumnModel([
        { 
            id:'leadstate_id', 
            header: 'id', 
            dataIndex: 'id', 
            width: 25, 
            hidden: true 
        }, { 
            id:'leadstate', 
            header: 'entries', 
            dataIndex: 'leadstate', 
            width: 170, 
            hideable: false, 
            sortable: false,
            quickaddField: new Ext.form.TextField({
                emptyText: this.app.i18n._('Add a Leadstate...')
            }),
            editor: new Ext.form.TextField({allowBlank: false}) 
        }, { 
            id:'probability', 
            header: 'probability', 
            dataIndex: 'probability', 
            width: 100, 
            hideable: false, 
            sortable: false, 
            renderer: Ext.util.Format.percentage,
            editor: new Ext.ux.PercentCombo({
                name: 'probability',
                id: 'probability'
            }),
            quickaddField: new Ext.ux.PercentCombo({
                autoExpand: true
            })
        }, {
            header: "X Lead?",
            id:'endslead',
            dataIndex: 'endslead',
            width: 50,
            editor: new Ext.form.Checkbox({}),
            quickaddField: new Ext.form.Checkbox({
                name: 'endslead'
            }),
            renderer: Tine.Tinebase.common.booleanRenderer
        }]);
    }
});

// file: /home/lkneschke/temp/tine20build/temp/tine20/Crm/js/LeadStateFilterModel.js
/*
 * Tine 2.0
 * 
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Cornelius Weiss <c.weiss@metaways.de>
 * @copyright   Copyright (c) 2010 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: LeadStateFilterModel.js 17485 2010-12-01 10:57:05Z p.schuele@metaways.de $
 */
Ext.ns('Tine.Crm');

/**
 * @namespace   Tine.Crm
 * @class       Tine.Crm.LeadStateFilterModel
 * @extends     Tine.widgets.grid.FilterModel
 * 
 * @author      Cornelius Weiss <c.weiss@metaways.de>
 * @version     $Id: LeadStateFilterModel.js 17485 2010-12-01 10:57:05Z p.schuele@metaways.de $
 * 
 * TODO         extend Tine.widgets.grid.FilterModelMultiSelect
 */
Tine.Crm.LeadStateFilterModel = Ext.extend(Tine.widgets.grid.FilterModel, {
    /**
     * @property Tine.Tinebase.Application app
     */
    app: null,
    
    field: 'leadstate_id',
    defaultOperator: 'notin',
    
    /**
     * @private
     */
    initComponent: function() {
        this.label = this.app.i18n._('Leadstate');
        this.operators = [/*'showClosed',*/ 'in', 'notin'];
        
        /*
        this.customOperators = [
            {operator: 'showClosed',   label: this.app.i18n._('Show closed')}
        ];
        */
        
        this.defaultValue = Tine.Crm.LeadState.getClosedStatus();
        
        this.supr().initComponent.call(this);
    },
    
    /**
     * value renderer
     * 
     * @param {Ext.data.Record} filter line
     * @param {Ext.Element} element to render to 
     */
    valueRenderer: function(filter, el) {
        var value = new Tine.Crm.LeadStateFilterModelValueField({
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

Tine.widgets.grid.FilterToolbar.FILTERS['crm.leadstate'] = Tine.Crm.LeadStateFilterModel;

/**
 * @namespace   Tine.Crm
 * @class       Tine.Crm.LeadStateFilterModelValueField
 * @extends     Ext.ux.form.LayerCombo
 * 
 * @author      Cornelius Weiss <c.weiss@metaways.de>
 * @version     $Id: LeadStateFilterModel.js 17485 2010-12-01 10:57:05Z p.schuele@metaways.de $
 */
Tine.Crm.LeadStateFilterModelValueField = Ext.extend(Ext.ux.form.LayerCombo, {
    hideButtons: false,
    formConfig: {
        labelAlign: 'left',
        labelWidth: 30
    },
    
    getFormValue: function() {
        var ids = [];
        var statusStore = Tine.Crm.LeadState.getStore();
        
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
        
        Tine.Crm.LeadState.getStore().each(function(status) {
            items.push({
                xtype: 'checkbox',
                boxLabel: status.get('leadstate'),
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
        
        var statusStore = Tine.Crm.LeadState.getStore();
        var statusText = [];
        this.currentValue = [];
        
        Tine.Crm.LeadState.getStore().each(function(status) {
            var id = status.get('id');
            var name = status.get('leadstate');
            Ext.each(value, function(valueId) {
                // NOTE: no type match id's might be int or string and should match anyway!
                if (valueId == id) {
                    statusText.push(name);
                    this.currentValue.push(id);
                }
            }, this);
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

// file: /home/lkneschke/temp/tine20build/temp/tine20/Crm/js/LeadSource.js
/*
 * Tine 2.0
 * lead source edit dialog and model
 * 
 * @package     Crm
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Philipp Schuele <p.schuele@metaways.de>
 * @copyright   Copyright (c) 2007-2010 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: LeadSource.js 16771 2010-10-22 10:41:45Z p.schuele@metaways.de $
 *
 */

Ext.namespace('Tine.Crm', 'Tine.Crm.LeadSource');

/**
 * @namespace Tine.Crm.LeadSource
 * @class Tine.Crm.LeadSource.Model
 * @extends Ext.data.Record
 * 
 * lead source model
 */ 
Tine.Crm.LeadSource.Model = Tine.Tinebase.data.Record.create([
   {name: 'id', type: 'int'},
   {name: 'leadsource'}
], {
    appName: 'Crm',
    modelName: 'LeadState',
    idProperty: 'id',
    titleProperty: 'leadsource',
    // ngettext('Lead Source', 'Lead Sources', n);
    recordName: 'Lead Source',
    recordsName: 'Lead Sources'
});

/**
 * @namespace Tine.Crm.LeadSource
 * 
 * get default data for a new leadsource
 *  
 * @return {Object} default data
 * @static
 */ 
Tine.Crm.LeadSource.Model.getDefaultData = function() {
    
    var data = {
        id: Tine.Crm.Model.getRandomUnusedId(Ext.StoreMgr.get('CrmLeadSourceStore'))
    };
    
    return data;
};

/**
 * get lead source store
 * if available, load data from LeadSources
 * 
 * @return {Ext.data.JsonStore}
 */
Tine.Crm.LeadSource.getStore = function() {
    
    var store = Ext.StoreMgr.get('CrmLeadSourceStore');
    if (!store) {

        store = new Ext.data.JsonStore({
            fields: Tine.Crm.LeadSource.Model,
            baseParams: {
                method: 'Crm.getLeadsources',
                sort: 'LeadSource',
                dir: 'ASC'
            },
            root: 'results',
            totalProperty: 'totalcount',
            id: 'id',
            remoteSort: false
        });
        
        if ( Tine.Crm.registry.get('leadsources') ) {
            store.loadData(Tine.Crm.registry.get('leadsources'));
        }
            
        Ext.StoreMgr.add('CrmLeadSourceStore', store);
    }
    return store;
};


/**
 * @namespace   Tine.Crm.LeadSource
 * @class       Tine.Crm.LeadSource.GridPanel
 * @extends     Tine.Crm.Admin.QuickaddGridPanel
 * 
 * lead sources grid panel
 * 
 * <p>
 * </p>
 * 
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Philipp Schuele <p.schuele@metaways.de>
 * @copyright   Copyright (c) 2009 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: LeadSource.js 16771 2010-10-22 10:41:45Z p.schuele@metaways.de $
 */
Tine.Crm.LeadSource.GridPanel = Ext.extend(Tine.Crm.Admin.QuickaddGridPanel, {
    
    /**
     * @private
     */
    autoExpandColumn:'leadsource',
    quickaddMandatory: 'leadsource',

    /**
     * @private
     */
    initComponent: function() {
        this.app = this.app ? this.app : Tine.Tinebase.appMgr.get('Crm');
        
        this.store = Tine.Crm.LeadSource.getStore();
        this.recordClass = Tine.Crm.LeadSource.Model;
        
        Tine.Crm.LeadSource.GridPanel.superclass.initComponent.call(this);
    },
    
    getColumnModel: function() {
        return new Ext.grid.ColumnModel([
        { 
            id:'leadsource_id', 
            header: "id", 
            dataIndex: 'id', 
            width: 25, 
            hidden: true 
        }, { 
            id:'leadsource', 
            header: 'entries', 
            dataIndex: 'leadsource', 
            width: 170, 
            hideable: false, 
            sortable: false, 
            editor: new Ext.form.TextField({allowBlank: false}),
            quickaddField: new Ext.form.TextField({
                emptyText: this.app.i18n._('Add a Leadsource...')
            })
        }]);
    }
});

// file: /home/lkneschke/temp/tine20build/temp/tine20/Crm/js/LeadType.js
/*
 * Tine 2.0
 * lead type edit dialog and model
 * 
 * @package     Crm
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Philipp Schuele <p.schuele@metaways.de>
 * @copyright   Copyright (c) 2007-2010 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: LeadType.js 16771 2010-10-22 10:41:45Z p.schuele@metaways.de $
 *
 */

Ext.namespace('Tine.Crm', 'Tine.Crm.LeadType');

/**
 * @namespace Tine.Crm.LeadType
 * @class Tine.Crm.LeadType.Model
 * @extends Ext.data.Record
 * 
 * lead type model
 */ 
Tine.Crm.LeadType.Model = Tine.Tinebase.data.Record.create([
   {name: 'id', type: 'int'},
   {name: 'leadtype'}
], {
    appName: 'Crm',
    modelName: 'LeadType',
    idProperty: 'id',
    titleProperty: 'leadtype',
    // ngettext('Lead Type', 'Lead Types', n);
    recordName: 'Lead Type',
    recordsName: 'Lead Types'
});

/**
 * @namespace Tine.Crm.LeadType
 * 
 * get default data for a new leadtype
 *  
 * @return {Object} default data
 * @static
 */ 
Tine.Crm.LeadType.Model.getDefaultData = function() {
    
    var data = {
        id: Tine.Crm.Model.getRandomUnusedId(Ext.StoreMgr.get('CrmLeadTypeStore'))
    };
    
    return data;
};

/**
 * get lead type store
 * 
 * @return  {Ext.data.JsonStore}
 */
Tine.Crm.LeadType.getStore = function() {
	
	var store = Ext.StoreMgr.get('CrmLeadTypeStore');
	if (!store) {

		store = new Ext.data.JsonStore({
            fields: Tine.Crm.LeadType.Model,
            baseParams: {
                method: 'Crm.getLeadtypes',
                sort: 'LeadType',
                dir: 'ASC'
            },
            root: 'results',
            totalProperty: 'totalcount',
            id: 'id',
            remoteSort: false
        });
        
        if ( Tine.Crm.registry.get('leadtypes') ) {
        	store.loadData(Tine.Crm.registry.get('leadtypes'));
        }
        	
        Ext.StoreMgr.add('CrmLeadTypeStore', store);
	}
	return store;
};

/**
 * @namespace   Tine.Crm.LeadType
 * @class       Tine.Crm.LeadType.GridPanel
 * @extends     Tine.Crm.Admin.QuickaddGridPanel
 * 
 * lead types grid panel
 * 
 * <p>
 * </p>
 * 
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Philipp Schuele <p.schuele@metaways.de>
 * @copyright   Copyright (c) 2009 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: LeadType.js 16771 2010-10-22 10:41:45Z p.schuele@metaways.de $
 */
Tine.Crm.LeadType.GridPanel = Ext.extend(Tine.Crm.Admin.QuickaddGridPanel, {
    
    /**
     * @private
     */
    autoExpandColumn:'leadtype',
    quickaddMandatory: 'leadtype',

    /**
     * @private
     */
    initComponent: function() {
        this.app = this.app ? this.app : Tine.Tinebase.appMgr.get('Crm');
        
        this.store = Tine.Crm.LeadType.getStore();
        this.recordClass = Tine.Crm.LeadType.Model;
        
        Tine.Crm.LeadType.GridPanel.superclass.initComponent.call(this);
    },
    
    getColumnModel: function() {
        return new Ext.grid.ColumnModel([
        { 
            id:'leadtype_id', 
            header: "id", 
            dataIndex: 'id', 
            width: 25, 
            hidden: true 
        }, { 
            id:'leadtype', 
            header: 'entries', 
            dataIndex: 'leadtype', 
            width: 170, 
            hideable: false, 
            sortable: false, 
            editor: new Ext.form.TextField({allowBlank: false}),
            quickaddField: new Ext.form.TextField({
                emptyText: this.app.i18n._('Add a Leadtype...')
            })
        }]);
    }
});

// file: /home/lkneschke/temp/tine20build/temp/tine20/Addressbook/js/SearchCombo.js
/*
 * Tine 2.0
 * contacts combo box and store
 * 
 * @package     Addressbook
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Philipp Schuele <p.schuele@metaways.de>
 * @copyright   Copyright (c) 2007-2009 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: SearchCombo.js 17759 2010-12-08 17:04:10Z p.schuele@metaways.de $
 *
 */

Ext.ns('Tine.Addressbook');

/**
 * contact selection combo box
 * 
 * @namespace   Tine.Addressbook
 * @class       Tine.Addressbook.SearchCombo
 * @extends     Ext.form.ComboBox
 * 
 * <p>Contact Search Combobox</p>
 * <p><pre>
 * TODO         make this a twin trigger field with 'clear' button?
 * TODO         add switch to filter for expired/enabled/disabled user accounts
 * TODO         extend Tine.Tinebase.widgets.form.RecordPickerComboBox
 * </pre></p>
 * 
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Philipp Schuele <p.schuele@metaways.de>
 * @copyright   Copyright (c) 2007-2009 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: SearchCombo.js 17759 2010-12-08 17:04:10Z p.schuele@metaways.de $
 * 
 * @param       {Object} config
 * @constructor
 * Create a new Tine.Addressbook.SearchCombo
 */
Tine.Addressbook.SearchCombo = Ext.extend(Ext.form.ComboBox, {

    /**
     * combobox cfg
     * @private
     */
    typeAhead: false,
    triggerAction: 'all',
    pageSize: 10,
    itemSelector: 'div.search-item',
    store: null,
    minChars: 3,
    
    /**
     * @cfg {Boolean} blurOnSelect
     */
    blurOnSelect: false,
    
    /**
     * @cfg {Boolean} userOnly
     */
    userOnly: false,
    
    /**
     * @property additionalFilters
     * @type Array
     */
    additionalFilters: null,
    
    /**
     * @property selectedRecord
     * @type Tine.Addressbook.Model.Contact
     */
    selectedRecord: null,
    
    /**
     * @cfg {String} nameField
     */
    nameField: 'n_fn',

    /**
     * use account objects/records in get/setValue
     * 
     * @cfg {Boolean} legacy
     * @legacy
     * 
     * TODO remove this later
     */
    useAccountRecord: false,
    
    //private
    initComponent: function(){
        
        this.loadingText = _('Searching...');
    	
        this.initTemplate();
        this.initStore();
        
        Tine.Addressbook.SearchCombo.superclass.initComponent.call(this);        

        this.on('beforequery', this.onBeforeQuery, this);
    },
    
    /**
     * use beforequery to set query filter
     * 
     * @param {Event} qevent
     */
    onBeforeQuery: function(qevent){
        var filter = [
            {field: 'query', operator: 'contains', value: qevent.query }
        ];
        
        if (this.userOnly) {
            filter.push({field: 'type', operator: 'equals', value: 'user'});
        }
        
        if (this.additionalFilters !== null && this.additionalFilters.length > 0) {
            for (var i = 0; i < this.additionalFilters.length; i++) {
                filter.push(this.additionalFilters[i]);
            }
        }
        
        this.store.baseParams.filter = filter;
    },
    
    /**
     * on select handler
     * - this needs to be overwritten in most cases
     * 
     * @param {Tine.Addressbook.Model.Contact} record
     */
    onSelect: function(record){
        this.selectedRecord = record;
        this.setValue(record.get(this.nameField));
        this.collapse();
        
        this.fireEvent('select', this, record);
        if (this.blurOnSelect) {
            this.fireEvent('blur', this);
        }
    },
    
    /**
     * on keypressed("enter") event to add record
     * 
     * @param {Tine.Addressbook.SearchCombo} combo
     * @param {Event} event
     */ 
    onSpecialkey: function(combo, event){
        if(event.getKey() == event.ENTER){
         	var id = combo.getValue();
            var record = this.store.getById(id);
            this.onSelect(record);
        }
    },
    
    /**
     * init template
     * @private
     */
    initTemplate: function() {
        // Custom rendering Template
        // TODO move style def to css ?
        if (! this.tpl) {
            this.tpl = new Ext.XTemplate(
                '<tpl for="."><div class="search-item">',
                    '<table cellspacing="0" cellpadding="2" border="0" style="font-size: 11px;" width="100%">',
                        '<tr>',
                            '<td width="30%"><b>{[this.encode(values.n_fileas)]}</b><br/>{[this.encode(values.org_name)]}</td>',
                            '<td width="25%">{[this.encode(values.adr_one_street)]}<br/>',
                                '{[this.encode(values.adr_one_postalcode)]} {[this.encode(values.adr_one_locality)]}</td>',
                            '<td width="25%">{[this.encode(values.tel_work)]}<br/>{[this.encode(values.tel_cell)]}</td>',
                            '<td width="20%">',
                                '<img width="45px" height="39px" src="{jpegphoto}" />',
                            '</td>',
                        '</tr>',
                    '</table>',
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
        }
    },
    
    getValue: function() {
        if (this.useAccountRecord) {
            if (this.selectedRecord) {
                return this.selectedRecord.get('account_id');
            } else {
                return this.accountId;
            }
        } else {
            return Tine.Addressbook.SearchCombo.superclass.getValue.call(this);
        }
    },

    setValue: function (value) {
    	
        if (this.useAccountRecord) {
            if (value) {
                if(value.accountId) {
                    // account object
                    this.accountId = value.accountId;
                    value = value.accountDisplayName;
                } else if (typeof(value.get) == 'function') {
                    // account record
                    this.accountId = value.get('id');
                    value = value.get('name');
                }
            } else {
                this.accountId = null;
            }
        }
        Tine.Addressbook.SearchCombo.superclass.setValue.call(this, value);
    },
    
    /**
     * get contact store
     *
     * @return Ext.data.JsonStore with contacts
     * @private
     */
    initStore: function() {
        
        if (! this.store) {
            
            if (! this.contactFields) {
                this.contactFields = Tine.Addressbook.Model.ContactArray;
            }
            
            // create store
            this.store = new Ext.data.JsonStore({
                //fields: Tine.Addressbook.Model.Contact,
                fields: this.contactFields,
                baseParams: {
                    method: 'Addressbook.searchContacts'
                },
                root: 'results',
                totalProperty: 'totalcount',
                id: 'id',
                remoteSort: true,
                sortInfo: {
                    field: 'n_family',
                    direction: 'ASC'
                }            
            });
    
            // prepare filter / get paging from combo
            this.store.on('beforeload', function(store, options){
                options.params.paging = {
                    start: options.params.start,
                    limit: options.params.limit,
                    sort: 'n_family',
                    dir: 'ASC'
                };
            }, this);
        }
        
        return this.store;
    }
});

// file: /home/lkneschke/temp/tine20build/temp/tine20/Crm/js/Contact.js
/*
 * Tine 2.0
 * 
 * @package     Crm
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Philipp Schuele <p.schuele@metaways.de>
 * @copyright   Copyright (c) 2009 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: Contact.js 11699 2009-11-25 11:11:36Z c.weiss@metaways.de $
 *
 */
 
Ext.ns('Tine.Crm.Contact');

/**
 * @namespace   Tine.Crm.Contact
 * @class       Tine.Crm.Contact.Combo
 * @extends     Tine.Addressbook.SearchCombo
 * 
 * Lead Dialog Contact Search Combo
 * 
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Philipp Schuele <p.schuele@metaways.de>
 * @copyright   Copyright (c) 2007-2009 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: Contact.js 11699 2009-11-25 11:11:36Z c.weiss@metaways.de $
 */
Tine.Crm.Contact.Combo = Ext.extend(Tine.Addressbook.SearchCombo, {

    valueField: 'id',
    
    /**
     * store to hold all contacts of grid
     * 
     * @type Ext.data.Store
     * @property contactsStore
     */
    contactsStore: null,
    
    //private
    initComponent: function(){
        this.contactFields = Tine.Addressbook.Model.ContactArray;
        this.contactFields.push({name: 'relation'});   // the relation object           
        this.contactFields.push({name: 'relation_type'});
        
        Tine.Crm.Contact.Combo.superclass.initComponent.call(this);        
    },
    
    /**
     * override default onSelect
     * 
     * TODO add some logic to determine if contact is customer or partner
     */
    onSelect: function(record) {
        record.data.relation_type = (record.get('type') == 'user') ? 'responsible' : 'customer';
        
        // check if already in
        if (! this.contactsStore.getById(record.id)) {
            this.contactsStore.add([record]);
        }
            
        this.collapse();
        this.clearValue();
    }    
});

/**
 * @namespace   Tine.Crm.Contact
 * @class       Tine.Crm.Contact.GridPanel
 * @extends     Ext.grid.EditorGridPanel
 * 
 * Lead Dialog Contact Grid Panel
 * 
 * <p>
 * </p>
 * 
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Philipp Schuele <p.schuele@metaways.de>
 * @copyright   Copyright (c) 2009 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: Contact.js 11699 2009-11-25 11:11:36Z c.weiss@metaways.de $
 */
Tine.Crm.Contact.GridPanel = Ext.extend(Ext.grid.EditorGridPanel, {
    /**
     * grid config
     * @private
     */
    autoExpandColumn: 'n_fileas',
    clicksToEdit: 1,
    baseCls: 'contact-grid',
    
    /**
     * The record currently being edited
     * 
     * @type Tine.Crm.Model.Lead
     * @property record
     */
    record: null,
    
    /**
     * store to hold all contacts
     * 
     * @type Ext.data.Store
     * @property store
     */
    store: null,
    
    /**
     * @type Ext.Menu
     * @property contextMenu
     */
    contextMenu: null,

    /**
     * @type Array
     * @property otherActions
     */
    otherActions: null,
    
    /**
     * @type function
     * @property recordEditDialogOpener
     */
    recordEditDialogOpener: null,
    
    /**
     * record class
     * @cfg {Tine.Addressbook.Model.Contact} recordClass
     */
    recordClass: null,
    
    /**
     * @private
     */
    initComponent: function() {
        // init properties
        this.app = this.app ? this.app : Tine.Tinebase.appMgr.get('Crm');
        this.recordEditDialogOpener = Tine.Addressbook.ContactEditDialog.openWindow;
        this.recordClass = Tine.Addressbook.Model.Contact;

        this.storeFields = Tine.Addressbook.Model.ContactArray;
        this.storeFields.push({name: 'relation'});   // the relation object           
        this.storeFields.push({name: 'relation_type'});     
        
        // create delegates
        this.initStore = Tine.Crm.LinkGridPanel.initStore.createDelegate(this);
        this.initActions = Tine.Crm.LinkGridPanel.initActions.createDelegate(this);
        this.initGrid = Tine.Crm.LinkGridPanel.initGrid.createDelegate(this);
        //this.onUpdate = Tine.Crm.LinkGridPanel.onUpdate.createDelegate(this);

        this.initStore();
        this.initOtherActions();
        this.initActions();
        this.initGrid();

        // add contact type to "add" action
        this.actionAdd.contactType = 'customer';

        // init store stuff
        this.store.setDefaultSort('type', 'asc');   
        
        Tine.Crm.Contact.GridPanel.superclass.initComponent.call(this);
    },
    
    /**
     * init other actions and tbar (change contact type and contact search combo
     */
    initOtherActions: function() {
        this.actionChangeContactTypeCustomer = new Ext.Action({
            requiredGrant: 'editGrant',
            contactType: 'customer',
            text: this.app.i18n._('Customer'),
            tooltip: this.app.i18n._('Change type to Customer'),
            iconCls: 'contactIconCustomer',
            scope: this,
            handler: this.onChangeContactType
        }); 
        
        this.actionChangeContactTypeResponsible = new Ext.Action({
            requiredGrant: 'editGrant',
            contactType: 'responsible',
            text: this.app.i18n._('Responsible'),
            tooltip: this.app.i18n._('Change type to Responsible'),
            iconCls: 'contactIconResponsible',
            scope: this,
            handler: this.onChangeContactType
        }); 
    
        this.actionChangeContactTypePartner = new Ext.Action({
            requiredGrant: 'editGrant',
            contactType: 'partner',
            text: this.app.i18n._('Partner'),
            tooltip: this.app.i18n._('Change type to Partner'),
            iconCls: 'contactIconPartner',
            scope: this,
            handler: this.onChangeContactType
        });
        var otherActionItems = [
           this.actionChangeContactTypeCustomer,
           this.actionChangeContactTypeResponsible,
           this.actionChangeContactTypePartner
        ];
        this.otherActions = [new Ext.Action({
            text: this.app.i18n._('Change contact type'),
            requiredGrant: 'editGrant',
            disabled: true,
            menu: otherActionItems
        })];
        
        this.tbar = new Ext.Panel({
            layout: 'fit',
            items: [
                // TODO perhaps we could add an icon/button (i.e. edit-find.png) here
                new Tine.Crm.Contact.Combo({
                    contactsStore: this.store,
                    emptyText: this.app.i18n._('Search for Contacts to add ...')
                })
            ]
        });
    },
    
    /**
     * @return Ext.grid.ColumnModel
     * @private
     */
    getColumnModel: function() {
        return new Ext.grid.ColumnModel({
            defaults: {
                sortable: true
            },
            columns: [            
                {id:'id', header: "id", dataIndex: 'id', width: 25, hidden: true },
                {id:'n_fileas', header: this.app.i18n._('Name'), dataIndex: 'n_fileas', width: 200, sortable: true, renderer: 
                    function(val, meta, record) {
                        var org_name           = Ext.isEmpty(record.data.org_name) === false ? record.data.org_name : ' ';
                        var n_fileas           = Ext.isEmpty(record.data.n_fileas) === false ? record.data.n_fileas : ' ';                            
                        var formated_return = '<b>' + Ext.util.Format.htmlEncode(n_fileas) + '</b><br />' + Ext.util.Format.htmlEncode(org_name);
                        
                        return formated_return;
                    }
                },
                {id:'contact_one', header: this.app.i18n._("Address"), dataIndex: 'adr_one_locality', width: 140, sortable: false, renderer: function(val, meta, record) {
                        var adr_one_street     = Ext.isEmpty(record.data.adr_one_street) === false ? record.data.adr_one_street : ' ';
                        var adr_one_postalcode = Ext.isEmpty(record.data.adr_one_postalcode) === false ? record.data.adr_one_postalcode : ' ';
                        var adr_one_locality   = Ext.isEmpty(record.data.adr_one_locality) === false ? record.data.adr_one_locality : ' ';
                        var formated_return =  
                            Ext.util.Format.htmlEncode(adr_one_street) + '<br />' + 
                            Ext.util.Format.htmlEncode(adr_one_postalcode) + ' ' + Ext.util.Format.htmlEncode(adr_one_locality);
                    
                        return formated_return;
                    }
                },
                {id:'tel_work', header: this.app.i18n._("Data"), dataIndex: 'tel_work', width: 140, sortable: false, renderer: function(val, meta, record) {
                        var translation = new Locale.Gettext();
                        translation.textdomain('Crm');
                        var tel_work           = Ext.isEmpty(record.data.tel_work) === false ? translation._('Phone') + ': ' + record.data.tel_work : ' ';
                        var tel_cell           = Ext.isEmpty(record.data.tel_cell) === false ? translation._('Cellphone') + ': ' + record.data.tel_cell : ' ';          
                        var formated_return = tel_work + '<br/>' + tel_cell + '<br/>';
                        return formated_return;
                    }
                }, {
                    id:'relation_type', 
                    header: this.app.i18n._("Role"), 
                    dataIndex: 'relation_type', 
                    width: 60, 
                    sortable: true,
                    renderer: Tine.Crm.Contact.typeRenderer,
                    editor: new Tine.Crm.Contact.TypeComboBox({
                        autoExpand: true,
                        blurOnSelect: true,
                        listClass: 'x-combo-list-small'
                    })
                }
            ]}
        );
    },
    
    /**
     * onclick handler for changeContactType
     */
    onChangeContactType: function(_button, _event) {          
        var selectedRows = this.getSelectionModel().getSelections();
        
        for (var i = 0; i < selectedRows.length; ++i) {
            selectedRows[i].data.relation_type = _button.contactType;
        }
        
        this.store.fireEvent('dataChanged', this.store);
    },
    
    /**
     * update event handler for related contacts
     * 
     * TODO use generic function
     */
    onUpdate: function(contact) {
        var response = {
            responseText: contact
        };
        contact = Tine.Addressbook.contactBackend.recordReader(response);
        
        var myContact = this.store.getById(contact.id);
        if (myContact) {
            myContact.beginEdit();
            for (var p in contact.data) { 
                myContact.set(p, contact.get(p));
            }
            myContact.endEdit();
        } else {
            contact.data.relation_type = 'customer';
            this.store.add(contact);
        }        
    }
});

/**
 * @namespace   Tine.Crm.Contact
 * @class       Tine.Crm.Contact.TypeComboBox
 * @extends     Ext.form.ComboBox
 * 
 * Contact type selection combobox
 * 
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Philipp Schuele <p.schuele@metaways.de>
 * @copyright   Copyright (c) 2007-2009 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: Contact.js 11699 2009-11-25 11:11:36Z c.weiss@metaways.de $
 */
Tine.Crm.Contact.TypeComboBox = Ext.extend(Ext.form.ComboBox, { 
    /**
     * @cfg {bool} autoExpand Autoexpand comboBox on focus.
     */
    autoExpand: false,
    /**
     * @cfg {bool} blurOnSelect blurs combobox when item gets selected
     */
    blurOnSelect: false,
    
    displayField: 'label',
    valueField: 'relation_type',
    mode: 'local',
    triggerAction: 'all',
    lazyInit: false,
    
    //private
    initComponent: function() {
        
        var translation = new Locale.Gettext();
        translation.textdomain('Crm');
        
        Tine.Crm.Contact.TypeComboBox.superclass.initComponent.call(this);
        // allways set a default
        if(!this.value) {
            this.value = 'responsible';
        }
            
        this.store = new Ext.data.SimpleStore({
            fields: ['label', 'relation_type'],
            data: [
                    [translation._('Responsible'), 'responsible'],
                    [translation._('Customer'), 'customer'],
                    [translation._('Partner'), 'partner']
                ]
        });
        
        if (this.autoExpand) {
            this.lazyInit = false;
            this.on('focus', function(){
                this.selectByValue(this.getValue());
                this.onTriggerClick();
            });
        }
        
        if (this.blurOnSelect){
            this.on('select', function(){
                this.fireEvent('blur', this);
            }, this);
        }
    }
});
Ext.reg('leadcontacttypecombo', Tine.Crm.Contact.TypeComboBox);

/**
 * contact type renderer function
 * 
 * @param   string type
 * @return  contact type icon
 */
Tine.Crm.Contact.typeRenderer = function(type)
{
    var translation = new Locale.Gettext();
    translation.textdomain('Crm');
    
    switch ( type ) {
        case 'responsible':
            var iconClass = 'contactIconResponsible';
            var qTip = translation._('Responsible');
            break;
        case 'customer':
            var iconClass = 'contactIconCustomer';
            var qTip = translation._('Customer');
            break;
        case 'partner':
            var iconClass = 'contactIconPartner';
            var qTip = translation._('Partner');
            break;
    }
    
    var icon = '<img class="x-menu-item-icon contactIcon ' + iconClass + '" src="library/ExtJS/resources/images/default/s.gif" ext:qtip="' + qTip + '"/>';
    
    return icon;
};

// file: /home/lkneschke/temp/tine20build/temp/tine20/Crm/js/Product.js
/*
 * Tine 2.0
 * 
 * @package     Crm
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Philipp Schuele <p.schuele@metaways.de>
 * @copyright   Copyright (c) 2009 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: Product.js 18870 2011-01-26 13:36:59Z p.schuele@metaways.de $
 *
 */
 
Ext.ns('Tine.Crm.Product');

/**
 * @namespace   Tine.Crm.Product
 * @class       Tine.Crm.Product.GridPanel
 * @extends     Ext.grid.EditorGridPanel
 * 
 * Lead Dialog Products Grid Panel
 * 
 * <p>
 * TODO         allow multiple relations with 1 product or add product quantity?
 * TODO         check if we need edit/add actions again
 * TODO         make resizing work correctly
 * </p>
 * 
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Philipp Schuele <p.schuele@metaways.de>
 * @copyright   Copyright (c) 2009 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: Product.js 18870 2011-01-26 13:36:59Z p.schuele@metaways.de $
 */
Tine.Crm.Product.GridPanel = Ext.extend(Ext.grid.EditorGridPanel, {
    /**
     * grid config
     * @private
     */
    autoExpandColumn: 'name',
    clicksToEdit: 1,
    loadMask: true,
    
    /**
     * The record currently being edited
     * 
     * @type Tine.Crm.Model.Lead
     * @property record
     */
    record: null,
    
    /**
     * store to hold all contacts
     * 
     * @type Ext.data.Store
     * @property store
     */
    store: null,
    
    /**
     * @type Ext.Menu
     * @property contextMenu
     */
    contextMenu: null,

    /**
     * @type Array
     * @property otherActions
     */
    otherActions: null,
    
    /**
     * @type function
     * @property recordEditDialogOpener
     */
    recordEditDialogOpener: null,

    /**
     * record class
     * @cfg {Tine.Sales.Model.Product} recordClass
     */
    recordClass: null,
    
    /**
     * @private
     */
    initComponent: function() {
        // init properties
        this.app = this.app ? this.app : Tine.Tinebase.appMgr.get('Crm');
        this.title = this.app.i18n._('Products');
        //this.recordEditDialogOpener = Tine.Products.EditDialog.openWindow;
        this.recordEditDialogOpener = Ext.emptyFn;
        this.recordClass = Tine.Sales.Model.Product;
        
        this.storeFields = Tine.Sales.Model.ProductArray;
        this.storeFields.push({name: 'relation'});   // the relation object           
        this.storeFields.push({name: 'relation_type'});
        this.storeFields.push({name: 'remark_price'});
        this.storeFields.push({name: 'remark_description'});
        this.storeFields.push({name: 'remark_quantity'});
        
        // create delegates
        this.initStore = Tine.Crm.LinkGridPanel.initStore.createDelegate(this);
        //this.initActions = Tine.Crm.LinkGridPanel.initActions.createDelegate(this);
        this.initGrid = Tine.Crm.LinkGridPanel.initGrid.createDelegate(this);
        //this.onUpdate = Tine.Crm.LinkGridPanel.onUpdate.createDelegate(this);
        this.onUpdate = Ext.emptyFn;

        // call delegates
        this.initStore();
        this.initActions();
        this.initGrid();
        
        // init store stuff
        this.store.setDefaultSort('name', 'asc');
        
        this.on('newentry', function(productData){
            // add new product to store
            var newProduct = [productData];
            this.store.loadData(newProduct, true);
            
            return true;
        }, this);
        
        Tine.Crm.Product.GridPanel.superclass.initComponent.call(this);
    },
    
    /**
     * @return Ext.grid.ColumnModel
     * @private
     */
    getColumnModel: function() {
        return new Ext.grid.ColumnModel({
            defaults: {
                sortable: true
            },
            columns: [
            {
                header: this.app.i18n._("Product"),
                id: 'name',
                dataIndex: 'name',
                width: 150
            }, {
                header: this.app.i18n._("Description"),
                id: 'remark_description',
                dataIndex: 'remark_description',
                width: 150,
                editor: new Ext.form.TextField({
                })
            }, {
                header: this.app.i18n._("Price"),
                id: 'remark_price',
                dataIndex: 'remark_price',
                width: 150,
                editor: new Ext.form.NumberField({
                    allowBlank: false,
                    allowNegative: false,
                    // TODO hardcode separator or get it from locale?
                    decimalSeparator: ','
                }),
                renderer: Ext.util.Format.euMoney
            }, {
                header: this.app.i18n._("Quantity"),
                id: 'remark_quantity',
                dataIndex: 'remark_quantity',
                width: 50,
                editor: new Ext.form.NumberField({
                    allowBlank: false,
                    allowNegative: false
                })
            }]
        });
    },
    
    /**
     * init actions and bars
     */
    initActions: function() {
        
        var app = Tine.Tinebase.appMgr.get(this.recordClass.getMeta('appName')); 
        if (! app) {
            return;
        }        
        var recordName = app.i18n.n_(
            this.recordClass.getMeta('recordName'), this.recordClass.getMeta('recordsName'), 1
        );

        this.actionUnlink = new Ext.Action({
            requiredGrant: 'editGrant',
            text: String.format(this.app.i18n._('Unlink {0}'), recordName),
            tooltip: String.format(this.app.i18n._('Unlink selected {0}'), recordName),
            disabled: true,
            iconCls: 'action_remove',
            onlySingle: true,
            scope: this,
            handler: function(_button, _event) {                       
                var selectedRows = this.getSelectionModel().getSelections();
                for (var i = 0; i < selectedRows.length; ++i) {
                    this.store.remove(selectedRows[i]);
                }           
            }
        });
        
        // init toolbars and ctx menut / add actions
        this.bbar = [                
            this.actionUnlink
        ];
        
        this.actions = [
            this.actionUnlink
        ];
        
        this.contextMenu = new Ext.menu.Menu({
            items: this.actions
        });
        this.tbar = new Ext.Panel({
            layout: 'fit',
            items: [
                new Tine.Tinebase.widgets.form.RecordPickerComboBox({
                    anchor: '90%',
                    emptyText: this.app.i18n._('Search for Products to add ...'),
                    productsStore: this.store,
                    blurOnSelect: true,
                    recordClass: Tine.Sales.Model.Product,
                    getValue: function() {
                        return this.selectedRecord ? this.selectedRecord.data : null;
                    },
                    onSelect: function(record){
                        // check if already in?
                        if (! this.productsStore.getById(record.id)) {
                            var newRecord = new Ext.data.Record({
                                price: record.data.price,
                                remark_price: record.data.price,
                                remark_quantity: 1,
                                name: record.data.name,
                                relation_type: 'product',
                                related_id: record.id,
                                id: record.id
                            }, record.id);
                            this.productsStore.insert(0, newRecord);
                        }
                            
                        this.collapse();
                        this.clearValue();
                    }
                })
            ]
        });
    }    
});

// file: /home/lkneschke/temp/tine20build/temp/tine20/Crm/js/Task.js
/*
 * Tine 2.0
 * 
 * @package     Crm
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Philipp Schuele <p.schuele@metaways.de>
 * @copyright   Copyright (c) 2009 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: Task.js 13559 2010-03-21 00:39:26Z c.weiss@metaways.de $
 *
 */
 
Ext.ns('Tine.Crm.Task');

/**
 * @namespace   Tine.Crm
 * @class       Tine.Crm.Task.GridPanel
 * @extends     Ext.ux.grid.QuickaddGridPanel
 * 
 * Lead Dialog Tasks Grid Panel
 * 
 * <p>
 * </p>
 * 
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Philipp Schuele <p.schuele@metaways.de>
 * @copyright   Copyright (c) 2009 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: Task.js 13559 2010-03-21 00:39:26Z c.weiss@metaways.de $
 */
Tine.Crm.Task.GridPanel = Ext.extend(Ext.ux.grid.QuickaddGridPanel, {
    /**
     * grid config
     * @private
     */
    autoExpandColumn: 'summary',
    quickaddMandatory: 'summary',
    clicksToEdit: 1,
    enableColumnHide:false,
    enableColumnMove:false,
    loadMask: true,
    
    /**
     * The record currently being edited
     * 
     * @type Tine.Crm.Model.Lead
     * @property record
     */
    record: null,
    
    /**
     * store to hold all contacts
     * 
     * @type Ext.data.Store
     * @property store
     */
    store: null,
    
    /**
     * @type Ext.Menu
     * @property contextMenu
     */
    contextMenu: null,

    /**
     * @type Array
     * @property otherActions
     */
    otherActions: null,
    
    /**
     * @type function
     * @property recordEditDialogOpener
     */
    recordEditDialogOpener: null,

    /**
     * record class
     * @cfg {Tine.Addressbook.Model.Contact} recordClass
     */
    recordClass: null,
    
    /**
     * @private
     */
    initComponent: function() {
        // init properties
        this.app = this.app ? this.app : Tine.Tinebase.appMgr.get('Crm');
        this.title = this.app.i18n._('Tasks');
        this.recordEditDialogOpener = Tine.Tasks.TaskEditDialog.openWindow;
        this.recordClass = Tine.Tasks.Task;
        
        this.storeFields = Tine.Tasks.TaskArray;
        this.storeFields.push({name: 'relation'});   // the relation object           
        this.storeFields.push({name: 'relation_type'});     
        
        // create delegates
        this.initStore = Tine.Crm.LinkGridPanel.initStore.createDelegate(this);
        this.initActions = Tine.Crm.LinkGridPanel.initActions.createDelegate(this);
        this.initGrid = Tine.Crm.LinkGridPanel.initGrid.createDelegate(this);
        //this.onUpdate = Tine.Crm.LinkGridPanel.onUpdate.createDelegate(this);

        // call delegates
        this.initStore();
        this.initActions();
        this.initGrid();
        
        // init store stuff
        this.store.setDefaultSort('due', 'asc');
        
        this.view = new Ext.grid.GridView({
            autoFill: true,
            forceFit:true,
            ignoreAdd: true,
            emptyText: this.app.i18n._('No Tasks to display'),
            onLoad: Ext.emptyFn,
            listeners: {
                beforerefresh: function(v) {
                    v.scrollTop = v.scroller.dom.scrollTop;
                },
                refresh: function(v) {
                    v.scroller.dom.scrollTop = v.scrollTop;
                }
            }
        });
        
        this.on('newentry', function(taskData){
            var newTask = taskData;
            newTask.relation_type = 'task';
            
            // get first responsible person and add it to task as organizer
            var i = 0;
            while (this.record.data.relations.length > i && this.record.data.relations[i].type != 'responsible') {
                i++;
            }
            if (this.record.data.relations[i] && this.record.data.relations[i].type == 'responsible' && this.record.data.relations[i].related_record.account_id != '') {
                newTask.organizer = this.record.data.relations[i].related_record.account_id;
            }
            
            // add new task to store
            this.store.loadData([newTask], true);
            
            return true;
        }, this);
        
        // hack to get percentage editor working
        this.on('rowclick', function(grid,row,e) {
            var cell = Ext.get(grid.getView().getCell(row,1));
            var dom = cell.child('div:last');
            while (cell.first()) {
                cell = cell.first();
                cell.on('click', function(e){
                    e.stopPropagation();
                    grid.fireEvent('celldblclick', grid, row, 1, e);
                });
            }
        }, this);        
        
        Tine.Crm.Task.GridPanel.superclass.initComponent.call(this);
    },
    
    /**
     * @return Ext.grid.ColumnModel
     * @private
     */
    getColumnModel: function() {
        return new Ext.grid.ColumnModel({
            defaults: {
                sortable: true
            },
            columns: [
                 {
                    id: 'summary',
                    header: this.app.i18n._("Summary"),
                    width: 100,
                    dataIndex: 'summary',
                    quickaddField: new Ext.form.TextField({
                        emptyText: this.app.i18n._('Add a task...')
                    })
                }, {
                    id: 'due',
                    header: this.app.i18n._("Due Date"),
                    width: 55,
                    dataIndex: 'due',
                    renderer: Tine.Tinebase.common.dateRenderer,
                    editor: new Ext.ux.form.ClearableDateField({
                        //format : 'd.m.Y'
                    }),
                    quickaddField: new Ext.ux.form.ClearableDateField({
                        //value: new Date(),
                        //format : "d.m.Y"
                    })
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
                }
            ]}
        );
    },
    
    /**
     * update event handler for related tasks
     * 
     * TODO use generic function
     */
    onUpdate: function(task) {
        var response = {
            responseText: task
        };
        task = Tine.Tasks.JsonBackend.recordReader(response);
        
        var myTask = this.store.getById(task.id);
        
        if (myTask) { 
            // copy values from edited task
            myTask.beginEdit();
            for (var p in task.data) { 
                myTask.set(p, task.get(p));
            }
            myTask.endEdit();
            
        } else {
            task.data.relation_type = 'task';
            this.store.add(task);        
        }
    }
});

