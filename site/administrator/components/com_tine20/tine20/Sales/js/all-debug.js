/*!
 * Tine 2.0 USER CLIENT
 * 
 * license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * copyright   Copyright (c) 2007-2011 Metaways Infosystems GmbH (http://www.metaways.de)
 *
 * FOR MORE DETAILED LICENSE AND COPYRIGHT INFORMATION PLEASE CONSULT THE LICENSE FILE 
 * LOCATED AT: <YOUR TINE 2.0 URL>/LICENSE OR VISIT THE TINE 2.0 HOMEPAGE AT http://www.tine20.org
 */// file: /home/lkneschke/temp/tine20build/temp/tine20/Sales/js/Models.js
/*
 * Tine 2.0
 * 
 * @package     Sales
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Philipp Schuele <p.schuele@metaways.de>
 * @copyright   Copyright (c) 2009 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: Models.js 14288 2010-05-10 11:55:56Z c.weiss@metaways.de $
 *
 */
 
Ext.namespace('Tine.Sales', 'Tine.Sales.Model');

// Product model fields
Tine.Sales.Model.ProductArray = [
    {name: 'id',            type: 'string'},
    {name: 'name',          type: 'string'},
    {name: 'description',   type: 'string'},
    {name: 'price',         type: 'float'},
    {name: 'manufacturer',  type: 'string'},
    {name: 'category',      type: 'string'},
    // tine 2.0 tags and notes
    {name: 'tags'},
    {name: 'notes'},
    // relations with other objects
    { name: 'relations'}
];

/**
 * @namespace Tine.Sales.Model
 * @class Tine.Sales.Model.Product
 * @extends Tine.Tinebase.data.Record
 * 
 * Product Record Definition
 */ 
Tine.Sales.Model.Product = Tine.Tinebase.data.Record.create(Tine.Sales.Model.ProductArray, {
    appName: 'Sales',
    modelName: 'Product',
    idProperty: 'id',
    titleProperty: 'name',
    // ngettext('Product', 'Products', n);
    recordName: 'Product',
    recordsName: 'Products',
    containerProperty: 'container_id',
    // ngettext('record list', 'record lists', n);
    containerName: 'Products',
    containersName: 'Products',
    getTitle: function() {
        return this.get('name') ? this.get('name') : false;
    }
});

/**
 * @namespace Tine.Sales.Model
 * 
 * get default data for a new product
 *  
 * @return {Object} default data
 * @static
 */ 
Tine.Sales.Model.Product.getDefaultData = function() {
    
    var data = {};
    return data;
};

/**
 * @namespace Tine.Sales.Model
 * 
 * get product filter
 *  
 * @return {Array} filter objects
 * @static
 */ 
Tine.Sales.Model.Product.getFilterModel = function() {
    var app = Tine.Tinebase.appMgr.get('Sales');
    
    return [
        {label: _('Quick search'), field: 'query', operators: ['contains']},
        {label: app.i18n._('Product name'),   field: 'name' },
        {filtertype: 'tinebase.tag', app: app}
    ];
};


// Contract model fields
Tine.Sales.Model.ContractArray = Tine.Tinebase.Model.genericFields.concat([
    // contract only fields
    { name: 'id' },
    { name: 'number' },
    { name: 'title' },
    { name: 'description' },
    { name: 'status' },
    // tine 2.0 notes field
    { name: 'notes'},
    // linked contacts/accounts
    { name: 'customers'},
    { name: 'accounts'}
]);

/**
 * @namespace Tine.Sales.Model
 * @class Tine.Sales.Model.Contract
 * @extends Tine.Tinebase.data.Record
 * 
 * Contract Record Definition
 */ 
Tine.Sales.Model.Contract = Tine.Tinebase.data.Record.create(Tine.Sales.Model.ContractArray, {
    appName: 'Sales',
    modelName: 'Contract',
    idProperty: 'id',
    titleProperty: 'title',
    // ngettext('Contract', 'Contracts', n);
    recordName: 'Contracts',
    recordsName: 'Contracts',
    containerProperty: 'container_id',
    // ngettext('contracts list', 'contracts lists', n);
    containerName: 'contracts list',
    containersName: 'contracts lists'
});

/**
 * @namespace Tine.Sales.Model
 * 
 * get default data for a new Contract
 *  
 * @return {Object} default data
 * @static
 */
Tine.Sales.Model.Contract.getDefaultData = function() { 
    return {
        container_id: Tine.Sales.registry.get('DefaultContainer')
    };
};



// file: /home/lkneschke/temp/tine20build/temp/tine20/Sales/js/Sales.js
/*
 * Tine 2.0
 * 
 * @package     Sales
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Philipp Schuele <p.schuele@metaways.de>
 * @copyright   Copyright (c) 2007-2009 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: Sales.js 14288 2010-05-10 11:55:56Z c.weiss@metaways.de $
 *
 */
 
Ext.namespace('Tine.Sales');

/**
 * @namespace Tine.Sales
 * @class Tine.Sales.MainScreen
 * @extends Tine.widgets.MainScreen
 * MainScreen of the Sales Application <br>
 * <pre>
 * TODO         generalize this
 * </pre>
 * 
 * @author      Philipp Schuele <p.schuele@metaways.de>
 * @version     $Id: Sales.js 14288 2010-05-10 11:55:56Z c.weiss@metaways.de $
 * 
 * @constructor
 * Constructs mainscreen of the Sales application
 */
Tine.Sales.MainScreen = Ext.extend(Tine.widgets.MainScreen, {
    activeContentType: 'Product'
});

/**
 * @namespace Tine.Sales
 * @class Tine.Sales.TreePanel
 * @extends Tine.widgets.persistentfilter.PickerPanel
 * 
 * <pre>
 * TODO         generalize this
 * </pre>
 */ 
Tine.Sales.TreePanel = Ext.extend(Tine.widgets.persistentfilter.PickerPanel,{
    
    filter: [{field: 'model', operator: 'equals', value: 'Sales_Model_ProductFilter'}],
    
    initComponent: function() {
        
        this.filterMountId = 'Product';
        
        this.root = {
            id: 'root',
            leaf: false,
            expanded: true,
            children: [{
                text: this.app.i18n._('Products'),
                id: 'Product',
                iconCls: 'SalesProduct',
                expanded: true,
                children: [{
                    text: this.app.i18n._('All Products'),
                    id: 'allproducts',
                    leaf: true
                }]
            }, {
                text: this.app.i18n._('Contracts'),
                id : 'Contract',
                iconCls: 'SalesContracts',
                expanded: true,
                children: [{
                    text: this.app.i18n._('All Contracts'),
                    id: 'allcontracts',
                    leaf: true,
                    containerType: Tine.Tinebase.container.TYPE_SHARED,
                    container: Tine.Sales.registry.get('DefaultContainer')
                }]
            }]
        };
        
        this.initContextMenu();
        
        Tine.Sales.TreePanel.superclass.initComponent.call(this);
        
        this.on('click', function(node) {
            if (node.attributes.isPersistentFilter != true) {
                var contentType = node.getPath().split('/')[2];
                
                this.app.getMainScreen().activeContentType = contentType;
                this.app.getMainScreen().show();
            }
        }, this);
        
        this.on('contextmenu', function(node, event){
            this.ctxNode = node;
            if (node.id == 'allcontracts') {
                this.contextMenu.showAt(event.getXY());
            }
        }, this);
    },
    
    /**
     * @private
     */
    initContextMenu: function() {
        this.contextMenu = Tine.widgets.tree.ContextMenu.getMenu({
            nodeName: this.app.i18n._('All Contracts'),
            actions: ['grants'],
            scope: this,
            backend: 'Tinebase_Container',
            backendModel: 'Container'
        });
    },
    
    /**
     * @private
     */
    afterRender: function() {
        Tine.Sales.TreePanel.superclass.afterRender.call(this);
        var type = this.app.getMainScreen().activeContentType;

        this.expandPath('/root/' + type + '/allproducts');
        this.selectPath('/root/' + type + '/allproducts');
    },
    
    /**
     * returns a filter plugin to be used in a grid
     * 
     * TODO     can we remove that?
     */
    getFilterPlugin: function() {
        if (!this.filterPlugin) {
            var scope = this;
            this.filterPlugin = new Tine.widgets.grid.FilterPlugin({
                getValue: function() {
                    return [
                    ];
                }
            });
        }
        
        return this.filterPlugin;
    }
});
    
/**
 * default contracts backend
 */
Tine.Sales.contractBackend = new Tine.Tinebase.data.RecordProxy({
    appName: 'Sales',
    modelName: 'Contract',
    recordClass: Tine.Sales.Model.Contract
});

/**
 * @namespace Tine.Sales
 * @class Tine.Sales.productBackend
 * @extends Tine.Tinebase.data.RecordProxy
 * 
 * Product Backend
 */ 
Tine.Sales.productBackend = new Tine.Tinebase.data.RecordProxy({
    appName: 'Sales',
    modelName: 'Product',
    recordClass: Tine.Sales.Model.Product
});

// file: /home/lkneschke/temp/tine20build/temp/tine20/Sales/js/ContractGridPanel.js
/*
 * Tine 2.0
 * 
 * @package     Sales
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Philipp Schuele <p.schuele@metaways.de>
 * @copyright   Copyright (c) 2007-2010 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: ContractGridPanel.js 16415 2010-09-27 12:38:55Z p.schuele@metaways.de $
 *
 */
 
Ext.namespace('Tine.Sales');

/**
 * Contract grid panel
 * 
 * @namespace   Tine.Sales
 * @class       Tine.Sales.ContractGridPanel
 * @extends     Tine.widgets.grid.GridPanel
 * 
 * <p>Contract Grid Panel</p>
 * <p><pre>
 * </pre></p>
 * 
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Philipp Schuele <p.schuele@metaways.de>
 * @copyright   Copyright (c) 2007-2010 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: ContractGridPanel.js 16415 2010-09-27 12:38:55Z p.schuele@metaways.de $
 * 
 * @param       {Object} config
 * @constructor
 * Create a new Tine.Sales.ContractGridPanel
 */
Tine.Sales.ContractGridPanel = Ext.extend(Tine.widgets.grid.GridPanel, {
    // model generics
    recordClass: Tine.Sales.Model.Contract,
    
    // grid specific
    defaultSortInfo: {field: 'title', dir: 'ASC'},
    gridConfig: {
        loadMask: true,
        autoExpandColumn: 'title'
    },
    
    initComponent: function() {
        this.recordProxy = Tine.Sales.contractBackend;
        
        this.gridConfig.columns = this.getColumns();
        this.initFilterToolbar();
        
        this.plugins.push(this.filterToolbar);
        
        Tine.Sales.ContractGridPanel.superclass.initComponent.call(this);
    },
    
    /**
     * initialises filter toolbar
     */
    initFilterToolbar: function() {
        this.filterToolbar = new Tine.widgets.grid.FilterToolbar({
            filterModels: [
                {label: _('Quick search'),    field: 'query',    operators: ['contains']}
                //{label: this.app.i18n._('Summary'), field: 'summary' }
            ],
            defaultFilter: 'query',
            filters: [],
            plugins: [
                new Tine.widgets.grid.FilterToolbarQuickFilterPlugin()
            ]
        });
    },    
    
    /**
     * returns cm
     * @private
     * 
     * @todo    add more columns
     */
    getColumns: function(){
        return [{
            id: 'number',
            header: this.app.i18n._("Contract number"),
            width: 100,
            sortable: true,
            dataIndex: 'number'
        },{
            id: 'title',
            header: this.app.i18n._("Title"),
            width: 200,
            sortable: true,
            dataIndex: 'title'
        },{
            id: 'status',
            header: this.app.i18n._("Status"),
            width: 100,
            sortable: true,
            dataIndex: 'status'
        }];
    }  
});
// file: /home/lkneschke/temp/tine20build/temp/tine20/Sales/js/ContractEditDialog.js
/*
 * Tine 2.0
 * 
 * @package     Sales
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Philipp Schuele <p.schuele@metaways.de>
 * @copyright   Copyright (c) 2007-2010 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: ContractEditDialog.js 16416 2010-09-27 12:39:35Z p.schuele@metaways.de $
 *
 */
 
Ext.namespace('Tine.Sales');

/**
 * Contract edit dialog
 * 
 * @namespace   Tine.Sales
 * @class       Tine.Sales.ContractEditDialog
 * @extends     Tine.widgets.dialog.EditDialog
 * 
 * <p>Contract Edit Dialog</p>
 * <p><pre>
 * </pre></p>
 * 
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Philipp Schuele <p.schuele@metaways.de>
 * @copyright   Copyright (c) 2007-2010 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: ContractEditDialog.js 16416 2010-09-27 12:39:35Z p.schuele@metaways.de $
 * 
 * @param       {Object} config
 * @constructor
 * Create a new Tine.Sales.ContractGridPanel
 */
Tine.Sales.ContractEditDialog = Ext.extend(Tine.widgets.dialog.EditDialog, {
    /**
     * @private
     */
    labelAlign: 'side',
    
    /**
     * @private
     */
    windowNamePrefix: 'ContractEditWindow_',
    appName: 'Sales',
    recordClass: Tine.Sales.Model.Contract,
    recordProxy: Tine.Sales.contractBackend,
    tbarItems: [{xtype: 'widget-activitiesaddbutton'}],
    
    /**
     * reqests all data needed in this dialog
     */
    requestData: function() {
        this.loadRequest = Ext.Ajax.request({
            scope: this,
            success: function(response) {
                this.record = this.recordProxy.recordReader(response);
                this.onRecordLoad();
            },
            params: {
                method: 'Sales.getContract',
                id: this.record.id
            }
        });
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
            border: false,
            items:[
                {            	
                title: this.app.i18n.n_('Contract', 'Contract', 1),
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
                        fieldLabel: this.app.i18n._('Title'),
                        name: 'title',
                        allowBlank: false
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
                            app: 'Sales',
                            showAddNoteForm: false,
                            border: false,
                            bodyStyle: 'border:1px solid #B5B8C8;'
                        }),
                        new Tine.widgets.tags.TagPanel({
                            app: 'Sales',
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
 * Sales Edit Popup
 */
Tine.Sales.ContractEditDialog.openWindow = function (config) {
    var window = Tine.WindowFactory.getWindow({
        width: 800,
        height: 470,
        name: Tine.Sales.ContractEditDialog.prototype.windowNamePrefix + Ext.id(),
        contentPanelConstructor: 'Tine.Sales.ContractEditDialog',
        contentPanelConstructorConfig: config
    });
    return window;
};
// file: /home/lkneschke/temp/tine20build/temp/tine20/Sales/js/ProductGridPanel.js
/*
 * Tine 2.0
 * 
 * @package     Sales
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Philipp Schuele <p.schuele@metaways.de>
 * @copyright   Copyright (c) 2009 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id:GridPanel.js 7170 2009-03-05 10:58:55Z p.schuele@metaways.de $
 *
 */
 
Ext.namespace('Tine.Sales');

/**
 * Product grid panel
 * 
 * @namespace   Tine.Sales
 * @class       Tine.Sales.ProductGridPanel
 * @extends     Tine.widgets.grid.GridPanel
 * 
 * <p>Product Grid Panel</p>
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
 * Create a new Tine.Sales.ProductGridPanel
 */
Tine.Sales.ProductGridPanel = Ext.extend(Tine.widgets.grid.GridPanel, {
    /**
     * record class
     * @cfg {Tine.Sales.Model.Product} recordClass
     */
    recordClass: Tine.Sales.Model.Product,
    
    /**
     * eval grants
     * @cfg {Boolean} evalGrants
     */
    evalGrants: false,
    
    /**
     * grid specific
     * @private
     */
    defaultSortInfo: {field: 'name', direction: 'DESC'},
    gridConfig: {
        loadMask: true,
        autoExpandColumn: 'name'
    },
     
    /**
     * inits this cmp
     * @private
     */
    initComponent: function() {
        this.recordProxy = Tine.Sales.productBackend;
        
        this.actionToolbarItems = this.getToolbarItems();
        this.contextMenuItems = [
        ];

        this.gridConfig.cm = this.getColumnModel();
        this.filterToolbar = this.getFilterToolbar();
        
        this.plugins = this.plugins || [];
        this.plugins.push(this.filterToolbar);
        
        Tine.Sales.ProductGridPanel.superclass.initComponent.call(this);
        
        // actions depend on manage_products right
        this.selectionModel.on('selectionchange', function(sm) {
            var hasManageRight = Tine.Tinebase.common.hasRight('manage', 'Sales', 'products');

            if (hasManageRight) {
                Tine.widgets.actionUpdater(sm, this.actions, this.recordClass.getMeta('containerProperty'), !this.evalGrants);
                if (this.updateOnSelectionChange && this.detailsPanel) {
                    this.detailsPanel.onDetailsUpdate(sm);
                }
            } else {
                this.action_editInNewWindow.setDisabled(true);
                this.action_deleteRecord.setDisabled(true);
                this.action_tagsMassAttach.setDisabled(true);
            }
        }, this);

        this.action_addInNewWindow.setDisabled(! Tine.Tinebase.common.hasRight('manage', 'Sales', 'products'));
    },
    
    /**
     * initialises filter toolbar
     * 
     * @return Tine.widgets.grid.FilterToolbar
     * @private
     */
    getFilterToolbar: function() {
        return new Tine.widgets.grid.FilterToolbar({
            filterModels: Tine.Sales.Model.Product.getFilterModel(),
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
                sortable: true
            },
            columns: [
                {header: this.app.i18n._('Tags'), id: 'tags', dataIndex: 'tags', width: 50, renderer: Tine.Tinebase.common.tagsRenderer, sortable: false},
                {header: this.app.i18n._('Name'), id: 'name', dataIndex: 'name', width: 200},
                {header: this.app.i18n._('Manufacturer'), id: 'manufacturer', dataIndex: 'manufacturer', width: 100},
                {header: this.app.i18n._('Category'), id: 'category', dataIndex: 'category', width: 100},
                {header: this.app.i18n._('Description'), id: 'description', dataIndex: 'description', width: 150, sortable: false, hidden: true},
                {header: this.app.i18n._('Price'), id: 'price', dataIndex: 'price', width: 75, renderer: Ext.util.Format.euMoney}
            ]
        });
    },

    /**
     * return additional tb items
     * @private
     */
    getToolbarItems: function(){
        
        return [
        ];
    }    
});

// file: /home/lkneschke/temp/tine20build/temp/tine20/Sales/js/ProductEditDialog.js
/*
 * Tine 2.0
 * 
 * @package     Sales
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Philipp Schuele <p.schuele@metaways.de>
 * @copyright   Copyright (c) 2009 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: ProductEditDialog.js 18261 2011-01-03 13:26:54Z p.schuele@metaways.de $
 *
 */
 
Ext.namespace('Tine.Sales');

/**
 * Product edit dialog
 * 
 * @namespace   Tine.Sales
 * @class       Tine.Sales.ProductEditDialog
 * @extends     Tine.widgets.dialog.EditDialog
 * 
 * <p>Product Edit Dialog</p>
 * <p><pre>
 * TODO         make category a combobox + get data from settings
 * </pre></p>
 * 
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Philipp Schuele <p.schuele@metaways.de>
 * @copyright   Copyright (c) 2009 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: ProductEditDialog.js 18261 2011-01-03 13:26:54Z p.schuele@metaways.de $
 * 
 * @param       {Object} config
 * @constructor
 * Create a new Tine.Sales.ProductGridPanel
 */
Tine.Sales.ProductEditDialog = Ext.extend(Tine.widgets.dialog.EditDialog, {
    /**
     * @private
     */
    windowNamePrefix: 'ProductEditWindow_',
    appName: 'Sales',
    recordClass: Tine.Sales.Model.Product,
    recordProxy: Tine.Sales.productBackend,
    tbarItems: [{xtype: 'widget-activitiesaddbutton'}],
    evalGrants: false,
    
    /**
     * @private
     */
    initComponent: function() {
        this.linkPanel = new Tine.widgets.dialog.LinkPanel({
            relatedRecords: (Tine.Crm && Tine.Tinebase.common.hasRight('run', 'Crm')) ? {
                Crm_Model_Lead: {
                    recordClass: Tine.Crm.Model.Lead,
                    dlgOpener: Tine.Crm.LeadEditDialog.openWindow
                }
            } : {}
        });
        
        Tine.Sales.ProductEditDialog.superclass.initComponent.call(this);
    },
    
    /**
     * executed when record is loaded
     * @private
     */
    onRecordLoad: function() {
        Tine.Sales.ProductEditDialog.superclass.onRecordLoad.call(this);
        
        // update tabpanels
        this.linkPanel.onRecordLoad(this.record);
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
            border: false,
            items:[
                {            	
                title: this.app.i18n.n_('Product', 'Product', 1),
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
                        fieldLabel: this.app.i18n._('Name'),
                        name: 'name',
                        allowBlank: false
                    }], [{
                        columnWidth: 1,
                        xtype: 'numberfield',
                        fieldLabel: this.app.i18n._('Price'),
                        name: 'price',
                        allowNegative: false,
                        allowBlank: false
                        //decimalSeparator: ','
                    }], [{
                        columnWidth: 1,
                        fieldLabel: this.app.i18n._('Manufacturer'),
                        name: 'manufacturer'
                    }], [{
                        columnWidth: 1,
                        fieldLabel: this.app.i18n._('Category'),
                        name: 'category'
                    }], [{
                        columnWidth: 1,
                        fieldLabel: this.app.i18n._('Description'),
                        emptyText: this.app.i18n._('Enter description...'),
                        name: 'description',
                        xtype: 'textarea',
                        height: 150
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
                            app: 'Sales',
                            showAddNoteForm: false,
                            border: false,
                            bodyStyle: 'border:1px solid #B5B8C8;'
                        }),
                        new Tine.widgets.tags.TagPanel({
                            app: 'Sales',
                            border: false,
                            bodyStyle: 'border:1px solid #B5B8C8;'
                        })
                    ]
                }]
            }, new Tine.widgets.activities.ActivitiesTabPanel({
                app: this.appName,
                record_id: this.record.id,
                record_model: this.appName + '_Model_' + this.recordClass.getMeta('modelName')
            }), this.linkPanel
            ]
        };
    }
});

/**
 * Sales Edit Popup
 */
Tine.Sales.ProductEditDialog.openWindow = function (config) {
    var id = (config.record && config.record.id) ? config.record.id : 0;
    var window = Tine.WindowFactory.getWindow({
        width: 600,
        height: 500,
        name: Tine.Sales.ProductEditDialog.prototype.windowNamePrefix + id,
        contentPanelConstructor: 'Tine.Sales.ProductEditDialog',
        contentPanelConstructorConfig: config
    });
    return window;
};
