/*!
 * Tine 2.0 USER CLIENT
 * 
 * license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * copyright   Copyright (c) 2007-2011 Metaways Infosystems GmbH (http://www.metaways.de)
 *
 * FOR MORE DETAILED LICENSE AND COPYRIGHT INFORMATION PLEASE CONSULT THE LICENSE FILE 
 * LOCATED AT: <YOUR TINE 2.0 URL>/LICENSE OR VISIT THE TINE 2.0 HOMEPAGE AT http://www.tine20.org
 */// file: /home/lkneschke/temp/tine20build/temp/tine20/Addressbook/js/Model.js
/*
 * Tine 2.0
 * 
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Cornelius Weiss <c.weiss@metaways.de>
 * @copyright   Copyright (c) 2007-2010 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: Model.js 18511 2011-01-11 13:05:37Z p.schuele@metaways.de $
 */
 
/*global Ext, Tine*/
 
Ext.ns('Tine.Addressbook.Model');

// TODO: move this into model definition and replace uscases (?) with getter fn
Tine.Addressbook.Model.ContactArray = Tine.Tinebase.Model.genericFields.concat([
    {name: 'id'},
    {name: 'tid'},
    {name: 'private'},
    {name: 'cat_id'},
    {name: 'n_family', label: 'Last Name' },//_('Last Name')
    {name: 'n_given', label: 'First Name' }, //_('First Name')
    {name: 'n_middle', label: 'Middle Name' }, //_('Middle Name')
    {name: 'n_prefix', label: 'Title' }, //_('Title')
    {name: 'n_suffix', label: 'Suffix' }, //_('Suffix')
    {name: 'n_fn', label: 'Display Name' }, //_('Display Name')
    {name: 'n_fileas' },
    {name: 'bday', label: 'Birthday', type: 'date', dateFormat: Date.patterns.ISO8601Long }, //_('Birthday')
    {name: 'org_name', label: 'Company' }, //_('Company')
    {name: 'org_unit', label: 'Unit' }, //_('Unit')
    {name: 'salutation_id', label: 'Salutation' }, //_('Salutation')
    {name: 'title', label: 'Job Title' }, //_('Job Title')
    {name: 'role', label: 'Job Role' }, //_('Job Role')
    {name: 'assistent'},
    {name: 'room', label: 'Room' }, //_('Room')
    {name: 'adr_one_street', label: 'Street (Company Address)' }, //_('Street (Company Address)')
    {name: 'adr_one_street2', label: 'Street 2 (Company Address)' }, //_('Street 2 (Company Address)')
    {name: 'adr_one_locality', label: 'City (Company Address)' }, //_('City (Company Address)')
    {name: 'adr_one_region', label: 'Region (Company Address)' }, //_('Region (Company Address)')
    {name: 'adr_one_postalcode', label: 'Postal Code (Company Address)' }, //_('Postal Code (Company Address)')
    {name: 'adr_one_countryname', label: 'Country (Company Address)' }, //_('Country (Company Address)')
    {name: 'label'},
    {name: 'adr_two_street', label: 'Street (Private Address)' }, //_('Street (Private Address)')
    {name: 'adr_two_street2', label: 'Street 2 (Private Address)' }, //_('Street 2 (Private Address)')
    {name: 'adr_two_locality', label: 'City (Private Address)' }, //_('City (Private Address)')
    {name: 'adr_two_region', label: 'Region (Private Address)' }, //_('Region (Private Address)')
    {name: 'adr_two_postalcode', label: 'Postal Code (Private Address)' }, //_('Postal Code (Private Address)')
    {name: 'adr_two_countryname', label: 'Country (Private Address)' }, //_('Country (Private Address)')
    {name: 'tel_work', label: 'Phone' }, //_('Phone')
    {name: 'tel_cell', label: 'Mobile' }, //_('Mobile')
    {name: 'tel_fax', label: 'Fax' }, //_('Fax')
    {name: 'tel_assistent' },
    {name: 'tel_car' },
    {name: 'tel_pager' },
    {name: 'tel_home', label: 'Phone (private)' }, //_('Phone (private)')
    {name: 'tel_fax_home', label: 'Fax (private)'}, //_('Fax (private)')
    {name: 'tel_cell_private', label: 'Mobile (private)' }, //_('Mobile (private)')
    {name: 'tel_other' },
    {name: 'tel_prefer'},
    {name: 'email', label: 'E-Mail' }, //_('E-Mail')
    {name: 'email_home', label: 'E-Mail (private)' }, //_('E-Mail (private)')
    {name: 'url', label: 'Web'}, //_('Web')
    {name: 'url_home', label: 'Web (private)' }, //_('Web (private)')
    {name: 'freebusy_uri'},
    {name: 'calendar_uri'},
    {name: 'note', label: 'Description' }, //_('Description')
    {name: 'tz'},
    {name: 'lon'},
    {name: 'lat'},
    {name: 'pubkey'},
    {name: 'jpegphoto'},
    {name: 'account_id'},
    {name: 'tags'},
    {name: 'notes'},
    {name: 'relations'},
    {name: 'customfields'},
    {name: 'type'}
]);

/**
 * @namespace   Tine.Addressbook.Model
 * @class       Tine.Addressbook.Model
 * @extends     Tine.Addressbook.Model.Contact
 * Model of a contact<br>
 * 
 * @author      Cornelius Weiss <c.weiss@metaways.de>
 * @version     $Id: Model.js 18511 2011-01-11 13:05:37Z p.schuele@metaways.de $
 */
Tine.Addressbook.Model.Contact = Tine.Tinebase.data.Record.create(Tine.Addressbook.Model.ContactArray, {
    appName: 'Addressbook',
    modelName: 'Contact',
    idProperty: 'id',
    titleProperty: 'n_fn',
    // ngettext('Contact', 'Contacts', n); gettext('Contacts');
    recordName: 'Contact',
    recordsName: 'Contacts',
    containerProperty: 'container_id',
    // ngettext('Addressbook', 'Addressbooks', n); gettext('Addressbooks');
    containerName: 'Addressbook',
    containersName: 'Addressbooks',
    copyOmitFields: ['account_id', 'type'],
    
    /**
     * returns true if record has an email address
     * @return {Boolean}
     */
    hasEmail: function() {
        return (
                this.get('email') &&        this.get('email') != '' 
            ||  this.get('email_home') &&   this.get('email_home') != ''
        );
    }
});

/**
 * get filtermodel of contact model
 * 
 * @namespace Tine.Addressbook.Model
 * @static
 * @return {Object} filterModel definition
 */ 
Tine.Addressbook.Model.Contact.getFilterModel = function() {
    var app = Tine.Tinebase.appMgr.get('Addressbook');
    
    var typeStore = [['contact', app.i18n._('Contact')], ['user', app.i18n._('User Account')]];
    
    return [
        {label: _('Quick search'),                                                      field: 'query',              operators: ['contains']},
        {filtertype: 'tine.widget.container.filtermodel', app: app, recordClass: Tine.Addressbook.Model.Contact},
        {label: app.i18n._('First Name'),                                               field: 'n_given' },
        {label: app.i18n._('Last Name'),                                                field: 'n_family'},
        {label: app.i18n._('Company'),                                                  field: 'org_name'},
        {label: app.i18n._('Unit'),                                                     field: 'org_unit'},
        {label: app.i18n._('Phone'),                                                    field: 'telephone',          operators: ['contains']},
        {label: app.i18n._('Job Title'),                                                field: 'title'},
        {label: app.i18n._('Job Role'),                                                 field: 'role'},
        {label: app.i18n._('Note'),                                                     field: 'note'},
        {filtertype: 'tinebase.tag', app: app},
        //{label: app.i18n._('Birthday'),    field: 'bday', valueType: 'date'},
        {label: app.i18n._('Street') + ' (' + app.i18n._('Company Address') + ')',      field: 'adr_one_street',     defaultOperator: 'equals'},
        {label: app.i18n._('Postal Code') + ' (' + app.i18n._('Company Address') + ')', field: 'adr_one_postalcode', defaultOperator: 'equals'},
        {label: app.i18n._('City') + '  (' + app.i18n._('Company Address') + ')',       field: 'adr_one_locality'},
        {label: app.i18n._('Street') + ' (' + app.i18n._('Private Address') + ')',      field: 'adr_two_street',     defaultOperator: 'equals'},
        {label: app.i18n._('Postal Code') + ' (' + app.i18n._('Private Address') + ')', field: 'adr_two_postalcode', defaultOperator: 'equals'},
        {label: app.i18n._('City') + ' (' + app.i18n._('Private Address') + ')',        field: 'adr_two_locality'},
        {label: app.i18n._('Type'), defaultValue: 'contact', valueType: 'combo',        field: 'type',               store: typeStore},
        {label: app.i18n._('Last modified'),                                            field: 'last_modified_time', valueType: 'date'},
        {label: app.i18n._('Last modifier'),                                            field: 'last_modified_by', 	 valueType: 'user'},
        {label: app.i18n._('Creation Time'),                                            field: 'creation_time', 	 valueType: 'date'},
        {label: app.i18n._('Creator'),                                                  field: 'created_by', 		 valueType: 'user'}
    ];
};
    
/**
 * default timesheets backend
 */
Tine.Addressbook.contactBackend = new Tine.Tinebase.data.RecordProxy({
    appName: 'Addressbook',
    modelName: 'Contact',
    recordClass: Tine.Addressbook.Model.Contact
});

/**
 * salutation model
 */
Tine.Addressbook.Model.Salutation = Ext.data.Record.create([
	{name: 'id'},
	{name: 'name'},
	{name: 'gender'},
	{name: 'image_path'}
]);

/**
 * salutation model
 */
Tine.Addressbook.Model.List = Tine.Tinebase.data.Record.create([
   {name: 'id'},
   {name: 'container_id'},
   {name: 'created_by'},
   {name: 'creation_time'},
   {name: 'last_modified_by'},
   {name: 'last_modified_time'},
   {name: 'is_deleted'},
   {name: 'deleted_time'},
   {name: 'deleted_by'},
   {name: 'name'},
   {name: 'description'},
   {name: 'members'},
   {name: 'email'},
   {name: 'type'},
   {name: 'group_id'}
], {
    appName: 'Addressbook',
    modelName: 'List',
    idProperty: 'id',
    titleProperty: 'name',
    // ngettext('List', 'Lists', n); gettext('Lists');
    recordName: 'List',
    recordsName: 'Lists',
    containerProperty: 'container_id',
    // ngettext('Addressbook', 'Addressbooks', n); gettext('Addressbooks');
    containerName: 'Addressbook',
    containersName: 'Addressbooks',
    copyOmitFields: ['group_id']
});

/**
 * get salutation store
 * if available, load data from initial data
 * 
 * @return Ext.data.JsonStore with salutations
 */
Tine.Addressbook.getSalutationStore = function () {
    
    var store = Ext.StoreMgr.get('AddressbookSalutationStore');
    if (! store) {
        store = new Ext.data.JsonStore({
            fields: Tine.Addressbook.Model.Salutation,
            baseParams: {
                method: 'Addressbook.getSalutations'
            },
            root: 'results',
            totalProperty: 'totalcount',
            id: 'id',
            remoteSort: false
        });
        
        if (Tine.Addressbook.registry.get('Salutations')) {
            store.loadData(Tine.Addressbook.registry.get('Salutations'));
        }
            
        Ext.StoreMgr.add('AddressbookSalutationStore', store);
    }
    
    return store;
};

// file: /home/lkneschke/temp/tine20build/temp/tine20/Addressbook/js/Addressbook.js
﻿/*
 * Tine 2.0
 * 
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Cornelius Weiss <c.weiss@metaways.de>
 * @copyright   Copyright (c) 2007-2008 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: Addressbook.js 17758 2010-12-08 17:01:59Z p.schuele@metaways.de $
 */

Ext.ns('Tine.Addressbook');

/**
 * @namespace   Tine.Addressbook
 * @class       Tine.Addressbook.Application
 * @extends     Tine.Tinebase.Application
 * Addressbook Application Object <br>
 * 
 * @author      Cornelius Weiss <c.weiss@metaways.de>
 * @version     $Id: Addressbook.js 17758 2010-12-08 17:01:59Z p.schuele@metaways.de $
 */
Tine.Addressbook.Application = Ext.extend(Tine.Tinebase.Application, {
    
    /**
     * Get translated application title of the calendar application
     * 
     * @return {String}
     */
    getTitle: function() {
        return this.i18n.ngettext('Addressbook', 'Addressbooks', 1);
    }
});

/**
 * @namespace   Tine.Addressbook
 * @class       Tine.Addressbook.MainScreen
 * @extends     Tine.widgets.MainScreen
 * MainScreen of the Addressbook Application <br>
 * 
 * @author      Cornelius Weiss <c.weiss@metaways.de>
 * @version     $Id: Addressbook.js 17758 2010-12-08 17:01:59Z p.schuele@metaways.de $
 */
Tine.Addressbook.MainScreen = Ext.extend(Tine.widgets.MainScreen, {
    activeContentType: 'Contact'
});



Tine.Addressbook.TreePanel = function(config) {
    Ext.apply(this, config);
    
    this.id = 'Addressbook_Tree';
    this.filterMode = 'filterToolbar';
    this.recordClass = Tine.Addressbook.Model.Contact;
    Tine.Addressbook.TreePanel.superclass.constructor.call(this);
};
Ext.extend(Tine.Addressbook.TreePanel , Tine.widgets.container.TreePanel);


Tine.Addressbook.FilterPanel = function(config) {
    Ext.apply(this, config);
    Tine.Addressbook.FilterPanel.superclass.constructor.call(this);
};

Ext.extend(Tine.Addressbook.FilterPanel, Tine.widgets.persistentfilter.PickerPanel, {
    filter: [{field: 'model', operator: 'equals', value: 'Addressbook_Model_ContactFilter'}]
});

// file: /home/lkneschke/temp/tine20build/temp/tine20/Addressbook/js/ContactGridDetailsPanel.js
/**
 * Tine 2.0
 * 
 * @package     Addressbook
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Philipp Schuele <p.schuele@metaways.de>
 * @copyright   Copyright (c) 2007-2009 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id:GridPanel.js 7170 2009-03-05 10:58:55Z p.schuele@metaways.de $
 *
 * TODO         add preference for sending mails with felamimail or mailto?
 */
 
Ext.ns('Tine.Addressbook');

/**
 * the details panel (shows contact details)
 * 
 * @namespace   Tine.Addressbook
 * @class       Tine.Addressbook.ContactGridDetailsPanel
 * @extends     Tine.widgets.grid.DetailsPanel
 */
Tine.Addressbook.ContactGridDetailsPanel = Ext.extend(Tine.widgets.grid.DetailsPanel, {
    
    il8n: null,
    felamimail: false,
    
    /**
     * init
     */
    initComponent: function() {

        // init templates
        this.initTemplate();
        this.initDefaultTemplate();
        
        Tine.Addressbook.ContactGridDetailsPanel.superclass.initComponent.call(this);
    },

    /**
     * add on click event after render
     */
    afterRender: function() {
        Tine.Addressbook.ContactGridDetailsPanel.superclass.afterRender.apply(this, arguments);
        
        if (this.felamimail === true) {
            this.body.on('click', this.onClick, this);
        }
    },
    
    /**
     * init default template
     */
    initDefaultTemplate: function() {
        
        this.defaultTpl = new Ext.XTemplate(
            '<div class="preview-panel-timesheet-nobreak">',    
                '<!-- Preview contacts -->',
                '<div class="preview-panel preview-panel-timesheet-left">',
                    '<div class="bordercorner_1"></div>',
                    '<div class="bordercorner_2"></div>',
                    '<div class="bordercorner_3"></div>',
                    '<div class="bordercorner_4"></div>',
                    '<div class="preview-panel-declaration">' + this.il8n._('Contacts') + '</div>',
                    '<div class="preview-panel-timesheet-leftside preview-panel-left">',
                        '<span class="preview-panel-bold">',
                            this.il8n._('Select contact') + '<br/>',
                            '<br/>',
                            '<br/>',
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
                '<!-- Preview xxx -->',
                '<div class="preview-panel-timesheet-right">',
                    '<div class="bordercorner_gray_1"></div>',
                    '<div class="bordercorner_gray_2"></div>',
                    '<div class="bordercorner_gray_3"></div>',
                    '<div class="bordercorner_gray_4"></div>',
                    '<div class="preview-panel-declaration"></div>',
                    '<div class="preview-panel-timesheet-leftside preview-panel-left">',
                        '<span class="preview-panel-bold">',
                            '<br/>',
                            '<br/>',
                            '<br/>',
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
            '</div>'        
        );
    },
    
    /**
     * init single contact template (this.tpl)
     */
    initTemplate: function() {
        this.tpl = new Ext.XTemplate(
            '<tpl for=".">',
                '<div class="preview-panel-adressbook-nobreak">',
                '<div class="preview-panel-left">',                
                    '<!-- Preview image -->',
                    '<div class="preview-panel preview-panel-left preview-panel-image">',
                        '<div class="bordercorner_1"></div>',
                        '<div class="bordercorner_2"></div>',
                        '<div class="bordercorner_3"></div>',
                        '<div class="bordercorner_4"></div>',
                        '<img src="{[this.getImageUrl(values.jpegphoto, 90, 113)]}"/>',
                    '</div>',
                
                    '<!-- Preview office -->',
                    '<div class="preview-panel preview-panel-office preview-panel-left">',                
                        '<div class="bordercorner_1"></div>',
                        '<div class="bordercorner_2"></div>',
                        '<div class="bordercorner_3"></div>',
                        '<div class="bordercorner_4"></div>',
                        '<div class="preview-panel-declaration">' + this.il8n._('Company') + '</div>',
                        '<div class="preview-panel-address preview-panel-left">',
                            '<span class="preview-panel-bold">{[this.encode(values.org_name, "mediumtext")]}{[this.encode(values.org_unit, "prefix", " / ")]}</span><br/>',
                            '{[this.encode(values.adr_one_street)]}<br/>',
                            '{[this.encode(values.adr_one_postalcode, " ")]}{[this.encode(values.adr_one_locality)]}<br/>',
                            '{[this.encode(values.adr_one_region, " / ")]}{[this.encode(values.adr_one_countryname, "country")]}<br/>',
                        '</div>',
                        '<div class="preview-panel-contact preview-panel-right">',
                            '<span class="preview-panel-symbolcompare">' + this.il8n._('Phone') + '</span>{[this.encode(values.tel_work)]}<br/>',
                            '<span class="preview-panel-symbolcompare">' + this.il8n._('Mobile') + '</span>{[this.encode(values.tel_cell)]}<br/>',
                            '<span class="preview-panel-symbolcompare">' + this.il8n._('Fax') + '</span>{[this.encode(values.tel_fax)]}<br/>',
                            '<span class="preview-panel-symbolcompare">' + this.il8n._('E-Mail') 
                                + '</span>{[this.getMailLink(values.email, ' + this.felamimail + ')]}<br/>',
                            '<span class="preview-panel-symbolcompare">' + this.il8n._('Web') + '</span><a href="{[this.encode(values.url, "href")]}" target="_blank">{[this.encode(values.url, "shorttext")]}</a><br/>',
                        '</div>',
                    '</div>',
                '</div>',

                '<!-- Preview privat -->',
                '<div class="preview-panel preview-panel-privat preview-panel-left">',                
                    '<div class="bordercorner_1"></div>',
                    '<div class="bordercorner_2"></div>',
                    '<div class="bordercorner_3"></div>',
                    '<div class="bordercorner_4"></div>',
                    '<div class="preview-panel-declaration">' + this.il8n._('Private') + '</div>',
                    '<div class="preview-panel-address preview-panel-left">',
                        '<span class="preview-panel-bold">{[this.encode(values.n_fn)]}</span><br/>',
                        '{[this.encode(values.adr_two_street)]}<br/>',
                        '{[this.encode(values.adr_two_postalcode, " ")]}{[this.encode(values.adr_two_locality)]}<br/>',
                        '{[this.encode(values.adr_two_region, " / ")]}{[this.encode(values.adr_two_countryname, "country")]}<br/>',
                    '</div>',
                    '<div class="preview-panel-contact preview-panel-right">',
                        '<span class="preview-panel-symbolcompare">' + this.il8n._('Phone') + '</span>{[this.encode(values.tel_home)]}<br/>',
                        '<span class="preview-panel-symbolcompare">' + this.il8n._('Mobile') + '</span>{[this.encode(values.tel_cell_private)]}<br/>',
                        '<span class="preview-panel-symbolcompare">' + this.il8n._('Fax') + '</span>{[this.encode(values.tel_fax_home)]}<br/>',
                        '<span class="preview-panel-symbolcompare">' + this.il8n._('E-Mail') 
                            + '</span>{[this.getMailLink(values.email_home, ' + this.felamimail + ')]}<br/>',
                        '<span class="preview-panel-symbolcompare">' + this.il8n._('Web') + '</span><a href="{[this.encode(values.url, "href")]}" target="_blank">{[this.encode(values.url_home, "shorttext")]}</a><br/>',
                    '</div>',                
                '</div>',
                
                '<!-- Preview info -->',
                '<div class="preview-panel-description preview-panel-left" ext:qtip="{[this.encode(values.note)]}">',
                    '<div class="bordercorner_gray_1"></div>',
                    '<div class="bordercorner_gray_2"></div>',
                    '<div class="bordercorner_gray_3"></div>',
                    '<div class="bordercorner_gray_4"></div>',
                    '<div class="preview-panel-declaration">' + this.il8n._('Info') + '</div>',
                    '{[this.encode(values.note, "longtext")]}',
                '</div>',
                '</div>',
                //  '{[this.getTags(values.tags)]}',
            '</tpl>',
            {
                /**
                 * encode
                 */
                encode: function(value, type, prefix) {
                    //var metrics = Ext.util.TextMetrics.createInstance('previewPanel');
                    if (value) {
                        if (type) {
                            switch (type) {
                                case 'country':
                                    value = Locale.getTranslationData('CountryList', value);
                                    break;
                                case 'longtext':
                                    value = Ext.util.Format.ellipsis(value, 135);
                                    break;
                                case 'mediumtext':
                                    value = Ext.util.Format.ellipsis(value, 30);
                                    break;
                                case 'shorttext':
                                    //console.log(metrics.getWidth(value));
                                    value = Ext.util.Format.ellipsis(value, 18);
                                    break;
                                case 'prefix':
                                    if (prefix) {
                                        value = prefix + value;
                                    }
                                    break;
                                case 'href':
                                    if (! String(value).match(/^(https?|ftps?)/)) {
                                        var adb = Tine.Tinebase.appMgr.get('Addressbook');
                                        return "javascript:Ext.Msg.alert('" + adb.i18n._('Insecure link') + "', '" + adb.i18n._('Please review this link in edit dialog.') + "');";
                                    }
                                    break;
                                default:
                                    value += type;
                            }
                        }
                        value = Ext.util.Format.htmlEncode(value);
                        return Ext.util.Format.nl2br(value);
                    } else {
                        return '';
                    }
                },
                
                /**
                 * get tags
                 * 
                 * TODO make it work
                 */
                getTags: function(value) {
                    var result = '';
                    for (var i=0; i<value.length; i++) {
                        result += value[i].name + ' ';
                    }
                    return result;
                },
                
                /**
                 * get image url
                 */
                getImageUrl: function(url, width, height) {
                    if (url.match(/&/)) {
                        url = Ext.ux.util.ImageURL.prototype.parseURL(url);
                        url.width = width;
                        url.height = height;
                        url.ratiomode = 0;
                    }
                    return url;
                },

                /**
                 * get email link
                 */
                getMailLink: function(email, felamimail) {
                    if (! email) {
                        return '';
                    }
                    
                    var link = (felamimail === true) ? '#' : 'mailto:' + email;
                    var id = Ext.id() + ':' + email;
                    
                    return '<a href="' + link + '" class="tinebase-email-link" id="' + id + '">'
                        + Ext.util.Format.ellipsis(email, 18) + '</a>';
                }
            }
        );
    },
    
    /**
     * on click for compose mail
     * 
     * @param {} e
     * 
     * TODO check if account is configured?
     * TODO generalize that
     */
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
});

// file: /home/lkneschke/temp/tine20build/temp/tine20/Addressbook/js/ContactGrid.js
/*
 * Tine 2.0
 * 
 * @package     Addressbook
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Cornelius Weiss <c.weiss@metaways.de>
 * @copyright   Copyright (c) 2007-2010 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: ContactGrid.js 18809 2011-01-22 16:05:12Z p.schuele@metaways.de $
 *
 */
 
Ext.ns('Tine.Addressbook');

/**
 * Contact grid panel
 * 
 * @namespace   Tine.Addressbook
 * @class       Tine.Addressbook.ContactGridPanel
 * @extends     Tine.widgets.grid.GridPanel
 * 
 * <p>Contact Grid Panel</p>
 * 
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Cornelius Weiss <c.weiss@metaways.de>
 * @copyright   Copyright (c) 2007-2010 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: ContactGrid.js 18809 2011-01-22 16:05:12Z p.schuele@metaways.de $
 * 
 * @param       {Object} config
 * @constructor
 * Create a new Tine.Addressbook.ContactGridPanel
 */
Tine.Addressbook.ContactGridPanel = Ext.extend(Tine.widgets.grid.GridPanel, {
    /**
     * record class
     * @cfg {Tine.Addressbook.Model.Contact} recordClass
     */
    recordClass: Tine.Addressbook.Model.Contact,
    
    /**
     * grid specific
     * @private
     */ 
    defaultSortInfo: {field: 'n_fileas', direction: 'ASC'},
    gridConfig: {
        loadMask: true,
        autoExpandColumn: 'n_fileas',
        enableDragDrop: true,
        ddGroup: 'containerDDGroup'
    },
    copyEditAction: true,
    felamimail: false,
    
    /**
     * @cfg {Bool} hasDetailsPanel 
     */
    hasDetailsPanel: true,
    
    /**
     * phoneMenu
     * @type Ext.menu.Menu 
     * 
     * TODO try to disable 'activation' of toolbar button when ctx menu button is selected
     */
    phoneMenu: null,
    
    /**
     * inits this cmp
     * @private
     */
    initComponent: function() {
        this.recordProxy = Tine.Addressbook.contactBackend;
        
        // check if felamimail is installed and user has run right and wants to use felamimail in adb
        if (Tine.Felamimail && Tine.Tinebase.common.hasRight('run', 'Felamimail') && Tine.Felamimail.registry.get('preferences').get('useInAdb')) {
            this.felamimail = (Tine.Felamimail.registry.get('preferences').get('useInAdb') == 1);
        }
        this.gridConfig.cm = this.getColumnModel();
        this.filterToolbar = this.filterToolbar || this.getFilterToolbar();

        if (this.hasDetailsPanel) {
            this.detailsPanel = this.getDetailsPanel();
        }
        
        this.plugins = this.plugins || [];
        this.plugins.push(this.filterToolbar);
        
        Tine.Addressbook.ContactGridPanel.superclass.initComponent.call(this);
    },
    
    /**
     * returns column model
     * 
     * @return Ext.grid.ColumnModel
     * @private
     */
    getColumnModel: function() {
        return new Ext.grid.ColumnModel({ 
            defaults: {
                sortable: true,
                hidden: true,
                resizable: true
            },
            columns: this.getColumns()
        });
    },
    
    /**
     * returns array with columns
     * 
     * @return {Array}
     */
    getColumns: function() {
        return [
            { id: 'tid', header: this.app.i18n._('Type'), dataIndex: 'tid', width: 30, renderer: this.contactTidRenderer.createDelegate(this), hidden: false },
            { id: 'tags', header: this.app.i18n._('Tags'), dataIndex: 'tags', width: 50, renderer: Tine.Tinebase.common.tagsRenderer, sortable: false, hidden: false  },
            { id: 'n_family', header: this.app.i18n._('Last Name'), dataIndex: 'n_family' },
            { id: 'n_given', header: this.app.i18n._('First Name'), dataIndex: 'n_given', width: 80 },
            { id: 'n_fn', header: this.app.i18n._('Full Name'), dataIndex: 'n_fn' },
            { id: 'n_fileas', header: this.app.i18n._('Display Name'), dataIndex: 'n_fileas', hidden: false},
            { id: 'org_name', header: this.app.i18n._('Company'), dataIndex: 'org_name', width: 120, hidden: false },
            { id: 'org_unit', header: this.app.i18n._('Unit'), dataIndex: 'org_unit'  },
            { id: 'title', header: this.app.i18n._('Job Title'), dataIndex: 'title' },
            { id: 'role', header: this.app.i18n._('Job Role'), dataIndex: 'role' },
            { id: 'room', header: this.app.i18n._('Room'), dataIndex: 'room' },
            { id: 'adr_one_street', header: this.app.i18n._('Street'), dataIndex: 'adr_one_street' },
            { id: 'adr_one_locality', header: this.app.i18n._('City'), dataIndex: 'adr_one_locality', width: 150, hidden: false },
            { id: 'adr_one_region', header: this.app.i18n._('Region'), dataIndex: 'adr_one_region' },
            { id: 'adr_one_postalcode', header: this.app.i18n._('Postalcode'), dataIndex: 'adr_one_postalcode' },
            { id: 'adr_one_countryname', header: this.app.i18n._('Country'), dataIndex: 'adr_one_countryname' },
            { id: 'adr_two_street', header: this.app.i18n._('Street (private)'), dataIndex: 'adr_two_street' },
            { id: 'adr_two_locality', header: this.app.i18n._('City (private)'), dataIndex: 'adr_two_locality' },
            { id: 'adr_two_region', header: this.app.i18n._('Region (private)'), dataIndex: 'adr_two_region' },
            { id: 'adr_two_postalcode', header: this.app.i18n._('Postalcode (private)'), dataIndex: 'adr_two_postalcode' },
            { id: 'adr_two_countryname', header: this.app.i18n._('Country (private)'), dataIndex: 'adr_two_countryname' },
            { id: 'email', header: this.app.i18n._('Email'), dataIndex: 'email', width: 150, hidden: false },
            { id: 'tel_work', header: this.app.i18n._('Phone'), dataIndex: 'tel_work', hidden: false },
            { id: 'tel_cell', header: this.app.i18n._('Mobile'), dataIndex: 'tel_cell', hidden: false },
            { id: 'tel_fax', header: this.app.i18n._('Fax'), dataIndex: 'tel_fax' },
            { id: 'tel_car', header: this.app.i18n._('Car phone'), dataIndex: 'tel_car' },
            { id: 'tel_pager', header: this.app.i18n._('Pager'), dataIndex: 'tel_pager' },
            { id: 'tel_home', header: this.app.i18n._('Phone (private)'), dataIndex: 'tel_home' },
            { id: 'tel_fax_home', header: this.app.i18n._('Fax (private)'), dataIndex: 'tel_fax_home' },
            { id: 'tel_cell_private', header: this.app.i18n._('Mobile (private)'), dataIndex: 'tel_cell_private' },
            { id: 'email_home', header: this.app.i18n._('Email (private)'), dataIndex: 'email_home' },
            { id: 'url', header: this.app.i18n._('Web'), dataIndex: 'url' },
            { id: 'url_home', header: this.app.i18n._('URL (private)'), dataIndex: 'url_home' },
            { id: 'note', header: this.app.i18n._('Note'), dataIndex: 'note' },
            { id: 'tz', header: this.app.i18n._('Timezone'), dataIndex: 'tz' },
            { id: 'geo', header: this.app.i18n._('Geo'), dataIndex: 'geo' },
            { id: 'bday', header: this.app.i18n._('Birthday'), dataIndex: 'bday', renderer: Tine.Tinebase.common.dateRenderer }
        ].concat(this.getModlogColumns().concat(this.getCustomfieldColumns()));
    },
    
    /**
     * @private
     */
    initActions: function() {
        this.actions_exportContact = new Ext.Action({
            requiredGrant: 'exportGrant',
            text: this.app.i18n._('Export Contact'),
            iconCls: 'action_export',
            scope: this,
            disabled: true,
            allowMultiple: true,
            menu: {
                items: [
                    new Tine.widgets.grid.ExportButton({
                        text: this.app.i18n._('Export as PDF'),
                        iconCls: 'action_exportAsPdf',
                        format: 'pdf',
                        exportFunction: 'Addressbook.exportContacts',
                        gridPanel: this
                    }),
                    new Tine.widgets.grid.ExportButton({
                        text: this.app.i18n._('Export as CSV'),
                        iconCls: 'tinebase-action-export-csv',
                        format: 'csv',
                        exportFunction: 'Addressbook.exportContacts',
                        gridPanel: this
                    }),
                    new Tine.widgets.grid.ExportButton({
                        text: this.app.i18n._('Export as ODS'),
                        format: 'ods',
                        iconCls: 'tinebase-action-export-ods',
                        exportFunction: 'Addressbook.exportContacts',
                        gridPanel: this
                    }),
                    new Tine.widgets.grid.ExportButton({
                        text: this.app.i18n._('Export as XLS'),
                        format: 'xls',
                        iconCls: 'tinebase-action-export-xls',
                        exportFunction: 'Addressbook.exportContacts',
                        gridPanel: this
                    }),
                    new Tine.widgets.grid.ExportButton({
                        text: this.app.i18n._('Export as ...'),
                        iconCls: 'tinebase-action-export-xls',
                        exportFunction: 'Addressbook.exportContacts',
                        showExportDialog: true,
                        gridPanel: this
                    })
                ]
            }
        });
        
        this.phoneMenu = new Ext.menu.Menu({
        });
        this.actions_callContact = new Ext.Action({
            requiredGrant: 'readGrant',
            hidden: ! (Tine.Phone && Tine.Tinebase.common.hasRight('run', 'Phone')),
            actionUpdater: this.updatePhoneActions,
            text: this.app.i18n._('Call contact'),
            disabled: true,
            iconCls: 'PhoneIconCls',
            menu: this.phoneMenu,
            scope: this
        });
        
        this.actions_composeEmail = new Ext.Action({
            requiredGrant: 'readGrant',
            hidden: ! this.felamimail,
            text: this.app.i18n._('Compose email'),
            disabled: true,
            handler: this.onComposeEmail,
            iconCls: 'action_composeEmail',
            scope: this,
            allowMultiple: true
        });
        
        this.actions_import = new Ext.Action({
            //requiredGrant: 'addGrant',
            text: this.app.i18n._('Import contacts'),
            disabled: false,
            handler: this.onImport,
            iconCls: 'action_import',
            scope: this,
            allowMultiple: true
        });
        
        // register actions in updater
        this.actionUpdater.addActions([
            this.actions_exportContact,
            this.actions_callContact,
            this.actions_composeEmail,
            this.actions_import
        ]);
        
        Tine.Addressbook.ContactGridPanel.superclass.initActions.call(this);
    },
    
    /**
     * add custom items to action toolbar
     * 
     * @return {Object}
     */
    getActionToolbarItems: function() {
        return [
            Ext.apply(new Ext.SplitButton(this.actions_callContact), {
                scale: 'medium',
                rowspan: 2,
                iconAlign: 'top',
                arrowAlign:'right'
            }),
            Ext.apply(new Ext.Button(this.actions_composeEmail), {
                scale: 'medium',
                rowspan: 2,
                iconAlign: 'top'
            }),{
                xtype: 'buttongroup',
                columns: 1,
                frame: false,
                items: [
                    this.actions_exportContact,
                    this.actions_import
                ]
            }
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
            this.actions_exportContact,
            '-',
            this.actions_callContact,
            this.actions_composeEmail
        ];
        
        return items;
    },
    
    /**
     * updates call menu
     * 
     * @param {Ext.Action} action
     * @param {Object} grants grants sum of grants
     * @param {Object} records
     */
    updatePhoneActions: function(action, grants, records) {
        if (action.isHidden()) {
            return;
        }
        
        this.phoneMenu.removeAll();
        this.actions_callContact.setDisabled(true);
            
        if (records.length == 1) {
            var contact = records[0];
            
            if (! contact) {
                return false;
            }
            
            if(!Ext.isEmpty(contact.data.tel_work)) {
                this.phoneMenu.add({
                   text: this.app.i18n._('Work') + ' ' + contact.data.tel_work + '',
                   scope: this,
                   handler: this.onCallContact,
                   field: 'tel_work'
                });
                action.setDisabled(false);
            }
            if(!Ext.isEmpty(contact.data.tel_home)) {
                this.phoneMenu.add({
                   text: this.app.i18n._('Home') + ' ' + contact.data.tel_home + '',
                   scope: this,
                   handler: this.onCallContact,
                   field: 'tel_home'
                });
                action.setDisabled(false);
            }
            if(!Ext.isEmpty(contact.data.tel_cell)) {
                this.phoneMenu.add({
                   text: this.app.i18n._('Cell') + ' ' + contact.data.tel_cell + '',
                   scope: this,
                   handler: this.onCallContact,
                   field: 'tel_cell'
                });
                action.setDisabled(false);
            }
            if(!Ext.isEmpty(contact.data.tel_cell_private)) {
                this.phoneMenu.add({
                   text: this.app.i18n._('Cell private') + ' ' + contact.data.tel_cell_private + '',
                   scope: this,
                   handler: this.onCallContact,
                   field: 'tel_cell'
                });
                action.setDisabled(false);
            }
        }
    },
        
    /**
     * calls a contact
     * @param {Button} btn 
     */
    onCallContact: function(btn) {
        var number;

        var contact = this.grid.getSelectionModel().getSelected();
        
        if (! contact) {
            return;
        }
        
        if (!Ext.isEmpty(contact.get(btn.field))) {
            number = contact.get(btn.field);
        } else if(!Ext.isEmpty(contact.data.tel_work)) {
            number = contact.data.tel_work;
        } else if (!Ext.isEmpty(contact.data.tel_cell)) {
            number = contact.data.tel_cell;
        } else if (!Ext.isEmpty(contact.data.tel_cell_private)) {
            number = contact.data.tel_cell_private;
        } else if (!Ext.isEmpty(contact.data.tel_home)) {
            number = contact.data.tel_work;
        }

        Tine.Phone.dialPhoneNumber(number);
    },
    
    /**
     * compose an email to selected contacts
     * 
     * @param {Button} btn 
     * 
     * TODO make this work for filter selections (not only the first page)
     */
    onComposeEmail: function(btn) {
        
        var contacts = this.grid.getSelectionModel().getSelections();
        
        var defaults = Tine.Felamimail.Model.Message.getDefaultData();
        defaults.body = Tine.Felamimail.getSignature();

        defaults.to = [];
        for (var i=0; i<contacts.length; i++) {
            if (contacts[i].get('email') != '') {
                defaults.to.push(contacts[i].get('email'));
            } else if (contacts[i].get('email_home') != '') {
                defaults.to.push(contacts[i].get('email_home'));
            }
        }
        
        var record = new Tine.Felamimail.Model.Message(defaults, 0);
        var popupWindow = Tine.Felamimail.MessageEditDialog.openWindow({
            record: record
        });
    },

    /**
     * import contacts
     * 
     * @param {Button} btn 
     * 
     * TODO generalize this & the import button
     */
    onImport: function(btn) {
        var popupWindow = Tine.widgets.dialog.ImportDialog.openWindow({
            appName: 'Addressbook',
            // update grid after import
            listeners: {
                scope: this,
                'update': function(record) {
                    this.loadGridData({
                        preserveCursor:     false, 
                        preserveSelection:  false, 
                        preserveScroller:   false,
                        removeStrategy:     'default'
                    });
                }
            },
            record: new Tine.Tinebase.Model.ImportJob({
                // TODO get selected container -> if no container is selected use default container
                container_id: Tine.Addressbook.registry.get('defaultAddressbook'),
                model: this.recordClass,
                import_definition_id:  Tine.Addressbook.registry.get('defaultImportDefinition').id
            }, 0)
        });
    },
        
    /**
     * tid renderer
     * 
     * @private
     * @return {String} HTML
     */
    contactTidRenderer: function(data, cell, record) {
    	
        switch(record.get('type')) {
            case 'user':
                return "<img src='images/oxygen/16x16/actions/user-female.png' width='12' height='12' alt='contact' ext:qtip='" + this.app.i18n._("Internal Contact") + "'/>";
            default:
                return "<img src='images/oxygen/16x16/actions/user.png' width='12' height='12' alt='contact'/>";
        }
    },
    
    /**
     * returns details panel
     * 
     * @private
     * @return {Tine.Addressbook.ContactGridDetailsPanel}
     */
    getDetailsPanel: function() {
        return new Tine.Addressbook.ContactGridDetailsPanel({
            gridpanel: this,
            il8n: this.app.i18n,
            felamimail: this.felamimail
        });
    }
});

// file: /home/lkneschke/temp/tine20build/temp/tine20/Addressbook/js/ContactFilterModel.js
/*
 * Tine 2.0
 * 
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Cornelius Weiss <c.weiss@metaways.de>
 * @copyright   Copyright (c) 2010 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: ContactFilterModel.js 17997 2010-12-21 21:27:22Z c.weiss@metaways.de $
 */
Ext.ns('Tine.Calendar');

/**
 * @namespace   Tine.Calendar
 * @class       Tine.Addressbook.ContactFilterModel
 * @extends     Tine.widgets.grid.FilterModel
 * 
 * @author      Cornelius Weiss <c.weiss@metaways.de>
 * @version     $Id: ContactFilterModel.js 17997 2010-12-21 21:27:22Z c.weiss@metaways.de $
 */
Tine.Addressbook.ContactFilterModel = Ext.extend(Tine.widgets.grid.FilterModel, {
    /**
     * @property Tine.Tinebase.Application app
     */
    app: null,
    
    field: 'contact_id',
    defaultOperator: 'equals',
    
    /**
     * @private
     */
    initComponent: function() {
        Tine.Addressbook.ContactFilterModel.superclass.initComponent.call(this);
        
        this.app = Tine.Tinebase.appMgr.get('Addressbook');
        
        this.operators = ['equals'/*, 'notin'*/];
        this.label = this.label || this.app.i18n._('Contact');
    },
    
    /**
     * value renderer
     * 
     * @param {Ext.data.Record} filter line
     * @param {Ext.Element} element to render to 
     */
    valueRenderer: function(filter, el) {
        var value = new Tine.Addressbook.SearchCombo({
            app: this.app,
            filter: filter,
            width: 200,
            listWidth: 400,
            listAlign : 'tr-br?',
            id: 'tw-ftb-frow-valuefield-' + filter.id,
            value: filter.data.value ? filter.data.value : this.defaultValue,
            renderTo: el,
            getValue: function() {
                return this.selectedRecord.id;
            },
            onSelect: function(record) {
                this.setValue(record);
                this.collapse();
        
                this.fireEvent('select', this, record);
                if (this.blurOnSelect) {
                    this.fireEvent('blur', this);
                }
            },
            setValue: function(value) {
                this.selectedRecord = value;
                var displayValue = Tine.Calendar.AttendeeGridPanel.prototype.renderAttenderUserName.call(this, value);
                Tine.Addressbook.SearchCombo.superclass.setValue.call(this, displayValue);
            }
        });
        value.on('select', this.onFiltertrigger, this);
        return value;
    }
});

Tine.widgets.grid.FilterToolbar.FILTERS['addressbook.contact'] = Tine.Addressbook.ContactFilterModel;


// file: /home/lkneschke/temp/tine20build/temp/tine20/Addressbook/js/ContactEditDialog.js
/*
 * Tine 2.0
 * 
 * @package     Addressbook
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Cornelius Weiss <c.weiss@metaways.de>
 * @copyright   Copyright (c) 2009-2010 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: ContactEditDialog.js 18524 2011-01-11 15:08:23Z p.schuele@metaways.de $
 *
 */

/*global Ext, Tine*/

Ext.ns('Tine.Addressbook');

/**
 * @namespace   Tine.Addressbook
 * @class       Tine.Addressbook.ContactEditDialog
 * @extends     Tine.widgets.dialog.EditDialog
 * Addressbook Edit Dialog <br>
 * 
 * @author      Cornelius Weiss <c.weiss@metaways.de>
 * @version     $Id: ContactEditDialog.js 18524 2011-01-11 15:08:23Z p.schuele@metaways.de $
 */
Tine.Addressbook.ContactEditDialog = Ext.extend(Tine.widgets.dialog.EditDialog, {
    
    windowNamePrefix: 'ContactEditWindow_',
    appName: 'Addressbook',
    recordClass: Tine.Addressbook.Model.Contact,
    showContainerSelector: true,
    
    getFormItems: function () {
        
        if (Tine.Tinebase.registry.get('mapPanel') && Tine.widgets.MapPanel) {
            this.mapPanel = new Tine.widgets.MapPanel({
                layout: 'fit',
                title: this.app.i18n._('Map'),
                disabled: (! this.record.get('lon') || this.record.get('lon') === null) && (! this.record.get('lat') || this.record.get('lat') === null),
                zoom: 15        
            });
        } else {
            this.mapPanel = new Ext.Panel({
                layout: 'fit',
                title: this.app.i18n._('Map'),
                disabled: true,
                html: ''
            });
        }
        
        return {
            xtype: 'tabpanel',
            border: false,
            plain: true,
            activeTab: 0,
            items: [{
                title: this.app.i18n.n_('Contact', 'Contacts', 1),
                border: false,
                frame: true,
                layout: 'border',
                items: [{
                    region: 'center',
                    layout: 'border',
                    items: [{
                        xtype: 'fieldset',
                        region: 'north',
                        autoHeight: true,
                        title: this.app.i18n._('Personal Information'),
                        items: [{
                            xtype: 'panel',
                            layout: 'fit',
                            width: 90,
                            height: 120,
                            style: {
                                position: 'absolute',
                                right: '10px',
                                top: Ext.isGecko ? '7px' : '19px',
                                'z-index': 100
                            },
                            items: [new Ext.ux.form.ImageField({
                                name: 'jpegphoto',
                                width: 90,
                                height: 120
                            })]
                        }, {
                            xtype: 'columnform',
                            items: [[{
                                columnWidth: 0.35,
                                fieldLabel: this.app.i18n._('Salutation'),
                                xtype: 'combo',
                                store: Tine.Addressbook.getSalutationStore(),
                                name: 'salutation_id',
                                mode: 'local',
                                displayField: 'name',
                                valueField: 'id',
                                triggerAction: 'all',
                                forceSelection: true,
                                listeners: {
                                	scope: this,
                                	'select': function (combo, record, index) {
                                		var jpegphoto = this.getForm().findField('jpegphoto');
                                		
                                		// set new empty photo depending on chosen salutation only if user doesn't have own image
                                		if (Ext.isEmpty(jpegphoto.getValue()) && ! Ext.isEmpty(record.get('image_path'))) {
                                			jpegphoto.setDefaultImage(record.get('image_path'));
                                		}
                                	}
                                }
                            }, {
                                columnWidth: 0.65,
                                fieldLabel: this.app.i18n._('Title'), 
                                name: 'n_prefix',
                                maxLength: 64
                            }, {
                                width: 100,
                                hidden: true
                            }], [{
                                columnWidth: 0.35,
                                fieldLabel: this.app.i18n._('First Name'), 
                                name: 'n_given',
                                maxLength: 64
                            }, {
                                columnWidth: 0.30,
                                fieldLabel: this.app.i18n._('Middle Name'), 
                                name: 'n_middle',
                                maxLength: 64
                            }, {
                                columnWidth: 0.35,
                                fieldLabel: this.app.i18n._('Last Name'), 
                                name: 'n_family',
                                maxLength: 64
                            }, {
                                width: 100,
                                hidden: true
                            }], [{
                                columnWidth: 0.65,
                                xtype: 'mirrortextfield',
                                fieldLabel: this.app.i18n._('Company'), 
                                name: 'org_name',
                                maxLength: 64
                            }, {
                                columnWidth: 0.35,
                                fieldLabel: this.app.i18n._('Unit'), 
                                name: 'org_unit',
                                maxLength: 64
                            }, {
                                width: 100,
                                hidden: true
                            }], [{
                                columnWidth: 0.65,
                                xtype: 'combo',
                                fieldLabel: this.app.i18n._('Display Name'),
                                name: 'n_fn',
                                disabled: true
                            }, {
                                columnWidth: 0.35,
                                fieldLabel: this.app.i18n._('Job Title'),
                                name: 'title',
                                maxLength: 64
                            }, {
                                width: 100,
                                xtype: 'extuxclearabledatefield',
                                fieldLabel: this.app.i18n._('Birthday'),
                                name: 'bday'
                            }]/* move to seperate tab, [{
                                columnWidth: .4,
                                fieldLabel: this.app.i18n._('Suffix'), 
                                name:'n_suffix'
                            }, {
                                columnWidth: .4,
                                fieldLabel: this.app.i18n._('Job Role'), 
                                name:'role'
                            }, {
                                columnWidth: .2,
                                fieldLabel: this.app.i18n._('Room'), 
                                name:'room'
                            }]*/]
                        }]
                    }, {
                        xtype: 'fieldset',
                        region: 'center',
                        title: this.app.i18n._('Contact Information'),
                        autoScroll: true,
                        items: [{
                            xtype: 'columnform',
                            items: [[{
                                fieldLabel: this.app.i18n._('Phone'), 
                                labelIcon: 'images/oxygen/16x16/apps/kcall.png',
                                name: 'tel_work',
                                maxLength: 40
                            }, {
                                fieldLabel: this.app.i18n._('Mobile'),
                                labelIcon: 'images/oxygen/16x16/devices/phone.png',
                                name: 'tel_cell',
                                maxLength: 40
                            }, {
                                fieldLabel: this.app.i18n._('Fax'), 
                                labelIcon: 'images/oxygen/16x16/devices/printer.png',
                                name: 'tel_fax',
                                maxLength: 40
                            }], [{
                                fieldLabel: this.app.i18n._('Phone (private)'),
                                labelIcon: 'images/oxygen/16x16/apps/kcall.png',
                                name: 'tel_home',
                                maxLength: 40
                            }, {
                                fieldLabel: this.app.i18n._('Mobile (private)'),
                                labelIcon: 'images/oxygen/16x16/devices/phone.png',
                                name: 'tel_cell_private',
                                maxLength: 40
                            }, {
                                fieldLabel: this.app.i18n._('Fax (private)'), 
                                labelIcon: 'images/oxygen/16x16/devices/printer.png',
                                name: 'tel_fax_home',
                                maxLength: 40
                            }], [{
                                fieldLabel: this.app.i18n._('E-Mail'), 
                                labelIcon: 'images/oxygen/16x16/actions/kontact-mail.png',
                                name: 'email',
                                vtype: 'email',
                                maxLength: 64
                            }, {
                                fieldLabel: this.app.i18n._('E-Mail (private)'), 
                                labelIcon: 'images/oxygen/16x16/actions/kontact-mail.png',
                                name: 'email_home',
                                vtype: 'email',
                                maxLength: 64
                            }, {
                                xtype: 'mirrortextfield',
                                fieldLabel: this.app.i18n._('Web'),
                                labelIcon: 'images/oxygen/16x16/actions/network.png',
                                name: 'url',
                                vtype: 'url',
                                maxLength: 128,
                                listeners: {
                                    scope: this,
                                    focus: function (field) {
                                        if (! field.getValue()) {
                                            field.setValue('http://www.');
                                            field.selectText.defer(100, field, [7, 11]);
                                        }
                                    },
                                    blur: function (field) {
                                        if (field.getValue() === 'http://www.') {
                                            field.setValue(null);
                                            field.validate();
                                        }
                                    }
                                }
                            }]]
                        }]
                    }, {
                        xtype: 'tabpanel',
                        region: 'south',
                        border: false,
                        deferredRender: false,
                        height: 124,
                        split: true,
                        activeTab: 0,
                        defaults: {
                            frame: true
                        },
                        items: [{
                            title: this.app.i18n._('Company Address'),
                            xtype: 'columnform',
                            items: [[{
                                fieldLabel: this.app.i18n._('Street'), 
                                name: 'adr_one_street',
                                maxLength: 64
                            }, {
                                fieldLabel: this.app.i18n._('Street 2'), 
                                name: 'adr_one_street2',
                                maxLength: 64
                            }, {
                                fieldLabel: this.app.i18n._('Region'),
                                name: 'adr_one_region',
                                maxLength: 64
                            }], [{
                                fieldLabel: this.app.i18n._('Postal Code'), 
                                name: 'adr_one_postalcode',
                                maxLength: 64
                            }, {
                                fieldLabel: this.app.i18n._('City'),
                                name: 'adr_one_locality',
                                maxLength: 64
                            }, {
                                xtype: 'widget-countrycombo',
                                fieldLabel: this.app.i18n._('Country'),
                                name: 'adr_one_countryname',
                                maxLength: 64
                            }]]
                        }, {
                            title: this.app.i18n._('Private Address'),
                            xtype: 'columnform',
                            items: [[{
                                fieldLabel: this.app.i18n._('Street'), 
                                name: 'adr_two_street',
                                maxLength: 64
                            }, {
                                fieldLabel: this.app.i18n._('Street 2'), 
                                name: 'adr_two_street2',
                                maxLength: 64
                            }, {
                                fieldLabel: this.app.i18n._('Region'),
                                name: 'adr_two_region',
                                maxLength: 64
                            }], [{
                                fieldLabel: this.app.i18n._('Postal Code'), 
                                name: 'adr_two_postalcode',
                                maxLength: 64
                            }, {
                                fieldLabel: this.app.i18n._('City'),
                                name: 'adr_two_locality',
                                maxLength: 64
                            }, {
                                xtype: 'widget-countrycombo',
                                fieldLabel: this.app.i18n._('Country'),
                                name: 'adr_two_countryname',
                                maxLength: 64
                            }]]
                        }]
                    }]
                }, {
                    // activities and tags
                    region: 'east',
                    layout: 'accordion',
                    animate: true,
                    width: 210,
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
                                xtype: 'textarea',
                                name: 'note',
                                hideLabel: true,
                                grow: false,
                                preventScrollbars: false,
                                anchor: '100% 100%',
                                emptyText: this.app.i18n._('Enter description'),
                                requiredGrant: 'editGrant'                           
                            }]
                        }),
                        new Tine.widgets.activities.ActivitiesPanel({
                            app: 'Addressbook',
                            showAddNoteForm: false,
                            border: false,
                            bodyStyle: 'border:1px solid #B5B8C8;'
                        }),
                        new Tine.widgets.tags.TagPanel({
                            app: 'Addressbook',
                            border: false,
                            bodyStyle: 'border:1px solid #B5B8C8;'
                        })
                    ]
                }]
            }, this.mapPanel,
            new Tine.widgets.activities.ActivitiesTabPanel({
                app: this.appName,
                record_id: (this.record) ? this.record.id : '',
                record_model: this.appName + '_Model_' + this.recordClass.getMeta('modelName')
            }),
            new Tine.Tinebase.widgets.customfields.CustomfieldsPanel({
                recordClass: Tine.Addressbook.Model.Contact,
                disabled: (Tine.Addressbook.registry.get('customfields').length === 0),
                quickHack: {record: this.record}
            }), this.linkPanel
            ]
        };
    },
    
    /**
     * init component
     */
    initComponent: function () {
        
        this.linkPanel = new Tine.widgets.dialog.LinkPanel({
            relatedRecords: (Tine.Crm && Tine.Tinebase.common.hasRight('run', 'Crm')) ? {
                Crm_Model_Lead: {
                    recordClass: Tine.Crm.Model.Lead,
                    dlgOpener: Tine.Crm.LeadEditDialog.openWindow
                }
            } : {}
        });
        
        // export lead handler for edit contact dialog
        var exportContactButton = new Ext.Action({
            id: 'exportButton',
            text: Tine.Tinebase.appMgr.get('Addressbook').i18n._('Export as pdf'),
            handler: this.onExportContact,
            iconCls: 'action_exportAsPdf',
            disabled: false,
            scope: this
        });
        var addNoteButton = new Tine.widgets.activities.ActivitiesAddButton({});  
        this.tbarItems = [exportContactButton, addNoteButton];
        
        this.supr().initComponent.apply(this, arguments);    
    },
    
    /**
     * checks if form data is valid
     * 
     * @return {Boolean}
     */
    isValid: function () {
        var form = this.getForm();
        var isValid = true;
        
        // you need to fill in one of: n_given n_family org_name
        // @todo required fields should depend on salutation ('company' -> org_name, etc.) 
        //       and not required fields should be disabled (n_given, n_family, etc.) 
        if (form.findField('n_family').getValue() === '' && form.findField('org_name').getValue() === '') {
            var invalidString = String.format(this.app.i18n._('Either {0} or {1} must be given'), this.app.i18n._('Last Name'), this.app.i18n._('Company'));
            
            form.findField('n_family').markInvalid(invalidString);
            form.findField('org_name').markInvalid(invalidString);
            
            isValid = false;
        }
        
        return isValid && Tine.Calendar.EventEditDialog.superclass.isValid.apply(this, arguments);
    },
    
    /**
     * export pdf handler
     */
    onExportContact: function () {
        var downloader = new Ext.ux.file.Download({
            params: {
                method: 'Addressbook.exportContacts',
                _filter: this.record.id,
                _format: 'pdf'
            }
        });
        downloader.start();
    },
    
    onRecordLoad: function () {
        // NOTE: it comes again and again till 
        if (this.rendered) {
            var container;
        	        	
            // handle default container
            if (! this.record.id) {
                if (this.forceContainer) {
                    container = this.forceContainer;
                    // only force initially!
                    this.forceContainer = null;
                } else {
                    container = Tine.Addressbook.registry.get('defaultAddressbook');
                }
                
                this.record.set('container_id', '');
                this.record.set('container_id', container);
            }
            
            if (Tine.Tinebase.registry.get('mapPanel') && Tine.widgets.MapPanel && this.record.get('lon') && this.record.get('lon') !== null && this.record.get('lat') && this.record.get('lat') !== null) {
                this.mapPanel.setCenter(this.record.get('lon'), this.record.get('lat'));
            }
        }
        
        this.supr().onRecordLoad.apply(this, arguments);
        
        this.linkPanel.onRecordLoad(this.record);
    }
});

/**
 * Opens a new contact edit dialog window
 * 
 * @return {Ext.ux.Window}
 */
Tine.Addressbook.ContactEditDialog.openWindow = function (config) {
    // if a container is selected in the tree, take this as default container
    var treeNode = Ext.getCmp('Addressbook_Tree') ? Ext.getCmp('Addressbook_Tree').getSelectionModel().getSelectedNode() : null;
    if (treeNode && treeNode.attributes && treeNode.attributes.container.type) {
        config.forceContainer = treeNode.attributes.container;
    } else {
        config.forceContainer = null;
    }
    
    var id = (config.record && config.record.id) ? config.record.id : 0;
    var window = Tine.WindowFactory.getWindow({
        width: 800,
        height: 600,
        name: Tine.Addressbook.ContactEditDialog.prototype.windowNamePrefix + id,
        contentPanelConstructor: 'Tine.Addressbook.ContactEditDialog',
        contentPanelConstructorConfig: config
    });
    return window;
};

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

