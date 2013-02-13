/*!
 * Tine 2.0 USER CLIENT
 * 
 * license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * copyright   Copyright (c) 2007-2011 Metaways Infosystems GmbH (http://www.metaways.de)
 *
 * FOR MORE DETAILED LICENSE AND COPYRIGHT INFORMATION PLEASE CONSULT THE LICENSE FILE 
 * LOCATED AT: <YOUR TINE 2.0 URL>/LICENSE OR VISIT THE TINE 2.0 HOMEPAGE AT http://www.tine20.org
 */// file: /home/lkneschke/temp/tine20build/temp/tine20/Felamimail/js/Model.js
/*
 * Tine 2.0
 * 
 * @package     Felamimail
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Cornelius Weiss <c.weiss@metaways.de>
 * @copyright   Copyright (c) 2007-2011 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: Model.js 18973 2011-02-01 11:36:17Z p.schuele@metaways.de $
 * 
 * TODO         think about adding a generic felamimail backend with the exception handler
 */
Ext.ns('Tine.Felamimail.Model');

/**
 * @namespace Tine.Felamimail.Model
 * @class Tine.Felamimail.Model.Message
 * @extends Tine.Tinebase.data.Record
 * 
 * Message Record Definition
 */ 
Tine.Felamimail.Model.Message = Tine.Tinebase.data.Record.create([
      { name: 'id' },
      { name: 'account_id' },
      { name: 'subject' },
      { name: 'from_email' },
      { name: 'from_name' },
      { name: 'sender' },
      { name: 'to' },
      { name: 'cc' },
      { name: 'bcc' },
      { name: 'sent',     type: 'date', dateFormat: Date.patterns.ISO8601Long },
      { name: 'received', type: 'date', dateFormat: Date.patterns.ISO8601Long },
      { name: 'flags' },
      { name: 'size' },
      { name: 'body',     defaultValue: undefined },
      { name: 'headers' },
      { name: 'content_type' },
      { name: 'body_content_type' },
      { name: 'structure' },
      { name: 'attachments' },
      { name: 'original_id' },
      { name: 'folder_id' },
      { name: 'note' }
    ], {
    appName: 'Felamimail',
    modelName: 'Message',
    idProperty: 'id',
    titleProperty: 'subject',
    // ngettext('Message', 'Messages', n);
    recordName: 'Message',
    recordsName: 'Messages',
    containerProperty: 'folder_id',
    // ngettext('Folder', 'Folders', n);
    containerName: 'Folder',
    containersName: 'Folders',
    
    /**
     * check if message has given flag
     * 
     * @param  {String} flag
     * @return {Boolean}
     */
    hasFlag: function(flag) {
        var flags = this.get('flags') || [];
        return flags.indexOf(flag) >= 0;
    },
    
    /**
     * adds given flag to message
     * 
     * @param  {String} flag
     * @return {Boolean} false if flag was already set before, else true
     */
    addFlag: function(flag) {
        if (! this.hasFlag(flag)) {
            var flags = Ext.unique(this.get('flags'));
            flags.push(flag);
            
            this.set('flags', flags);
            return true;
        }
        
        return false;
    },
    
    /**
     * check if body has been fetched
     * 
     * @return {Boolean}
     */
    bodyIsFetched: function() {
        return this.get('body') !== undefined;
    },
    
    /**
     * clears given flag from message
     * 
     * @param {String} flag
     * @return {Boolean} false if flag was not set before, else true
     */
    clearFlag: function(flag) {
        if (this.hasFlag(flag)) {
            var flags = Ext.unique(this.get('flags'));
            flags.remove(flag);
            
            this.set('flags', flags);
            return true;
        }
        
        return false;
    }
});

/**
 * get default message data
 * 
 * @return {Object}
 */
Tine.Felamimail.Model.Message.getDefaultData = function() {
    var autoAttachNote = Tine.Felamimail.registry.get('preferences').get('autoAttachNote');
    return {
        note: autoAttachNote,
        content_type: 'text/html'
    };
};

/**
 * get filtermodel for messages
 * 
 * @namespace Tine.Felamimail.Model
 * @static
 * @return {Object} filterModel definition
 */ 
Tine.Felamimail.Model.Message.getFilterModel = function() {
    var app = Tine.Tinebase.appMgr.get('Felamimail');
    
    return [
        {filtertype: 'tine.felamimail.folder.filtermodel', app: app},
        {label: app.i18n._('Subject/From'),field: 'query',         operators: ['contains']},
        {label: app.i18n._('Subject'),     field: 'subject',       operators: ['contains']},
        {label: app.i18n._('From (Email)'),field: 'from_email',    operators: ['contains']},
        {label: app.i18n._('From (Name)'), field: 'from_name',     operators: ['contains']},
        {label: app.i18n._('To'),          field: 'to',            operators: ['contains']},
        {label: app.i18n._('Cc'),          field: 'cc',            operators: ['contains']},
        {label: app.i18n._('Bcc'),         field: 'bcc',           operators: ['contains']},
        {label: app.i18n._('Flags'),       field: 'flags',         filtertype: 'tinebase.multiselect', app: app, multiselectFieldConfig: {
            valueStore: Tine.Felamimail.loadFlagsStore()
        }},
        {label: app.i18n._('Received'),    field: 'received',      valueType: 'date', pastOnly: true}
    ];
};

/**
 * @namespace Tine.Felamimail
 * @class Tine.Felamimail.messageBackend
 * @extends Tine.Tinebase.data.RecordProxy
 * 
 * Message Backend
 * 
 * TODO make clear/addFlags send filter as param instead of array of ids
 */ 
Tine.Felamimail.messageBackend = new Tine.Tinebase.data.RecordProxy({
    appName: 'Felamimail',
    modelName: 'Message',
    recordClass: Tine.Felamimail.Model.Message,
    
    /**
     * move messsages to folder
     *
     * @param  array $filterData filter data
     * @param  string $targetFolderId
     * @return  {Number} Ext.Ajax transaction id
     */
    moveMessages: function(filter, targetFolderId, options) {
        options = options || {};
        options.params = options.params || {};
        
        var p = options.params;
        
        p.method = this.appName + '.moveMessages';
        p.filterData = filter;
        p.targetFolderId = targetFolderId;
        
        options.beforeSuccess = function(response) {
            return [Tine.Felamimail.folderBackend.recordReader(response)];
        };
        
        return this.doXHTTPRequest(options);
    },
    
    /**
     * fetches body and additional headers (which are needed for the preview panel) into given message
     * 
     * @param {Message} message
     */
    fetchBody: function(message, callback) {
        return this.loadRecord(message, {
            timeout: 120000, // 2 minutes
            scope: this,
            callback: function(options, success, response) {
                var msg = this.recordReader(response);
                // NOTE: Flags from the server might be outdated, so we skip them
                Ext.copyTo(message.data, msg.data, Tine.Felamimail.Model.Message.getFieldNames().remove('flags'));
                if (Ext.isFunction(callback)){
                    callback(message);
                } else {
                    Ext.callback(callback[success ? 'success' : 'failure'], callback.scope, [message]);
                    Ext.callback(callback.callback, callback.scope, [message]);
                }
            }
        });
    },
    
    /**
     * saves a message into a folder
     * 
     * @param   {Ext.data.Record} record
     * @param   {String} folderName
     * @param   {Object} options
     * @return  {Number} Ext.Ajax transaction id
     * @success {Ext.data.Record}
     */
    saveInFolder: function(record, folderName, options) {
        options = options || {};
        options.params = options.params || {};
        options.beforeSuccess = function(response) {
            return [this.recordReader(response)];
        };
        
        var p = options.params;
        p.method = this.appName + '.saveMessageInFolder';
        p.recordData = record.data;
        p.folderName = folderName;
        
        // increase timeout as this can take a longer (5 minutes)
        options.timeout = 300000;
        
        return this.doXHTTPRequest(options);
    },

    
    /**
     * add given flags to given messages
     *
     * @param  {String/Array} ids
     * @param  {String/Array} flags
     */
    addFlags: function(ids, flags, options)
    {
        options = options || {};
        options.params = options.params || {};
        
        var p = options.params;
        
        p.method = this.appName + '.addFlags';
        p.filterData = ids;
        p.flags = flags;
        
        // increase timeout as this can take a longer (5 minutes)
        options.timeout = 300000;
        
        return this.doXHTTPRequest(options);
    },
    
    /**
     * clear given flags from given messages
     *
     * @param  {String/Array} ids
     * @param  {String/Array} flags
     */
    clearFlags: function(ids, flags, options)
    {
        options = options || {};
        options.params = options.params || {};
        
        var p = options.params;
        
        p.method = this.appName + '.clearFlags';
        p.filterData = ids;
        p.flags = flags;
        
        // increase timeout as this can take a longer (5 minutes)
        options.timeout = 300000;
        
        return this.doXHTTPRequest(options);
    },
    
    /**
     * exception handler for this proxy
     * 
     * @param {Tine.Exception} exception
     */
    handleRequestException: function(exception) {
        Tine.Felamimail.handleRequestException(exception);
    }
});


/**
 * @namespace Tine.Felamimail.Model
 * @class Tine.Felamimail.Model.Account
 * @extends Tine.Tinebase.data.Record
 * 
 * Account Record Definition
 */ 
Tine.Felamimail.Model.Account = Tine.Tinebase.data.Record.create(Tine.Tinebase.Model.genericFields.concat([
    { name: 'id' },
    { name: 'user_id' },
    { name: 'name' },
    { name: 'type' },
    { name: 'user' },
    { name: 'host' },
    { name: 'email' },
    { name: 'password' },
    { name: 'from' },
    { name: 'organization' },
    { name: 'port' },
    { name: 'ssl' },
    { name: 'imap_status', defaultValue: 'success'}, // client only {success|failure}
    { name: 'sent_folder' },
    { name: 'trash_folder' },
    { name: 'drafts_folder' },
    { name: 'templates_folder' },
    { name: 'has_children_support', type: 'bool' },
    { name: 'delimiter' },
    { name: 'display_format' },
    { name: 'ns_personal' },
    { name: 'ns_other' },
    { name: 'ns_shared' },
    { name: 'signature' },
    { name: 'smtp_port' },
    { name: 'smtp_hostname' },
    { name: 'smtp_auth' },
    { name: 'smtp_ssl' },
    { name: 'smtp_user' },
    { name: 'smtp_password' },
    { name: 'sieve_hostname' },
    { name: 'sieve_port' },
    { name: 'sieve_ssl' },
    { name: 'sieve_vacation_active', type: 'bool' },
    { name: 'all_folders_fetched', type: 'bool', defaultValue: false } // client only
]), {
    appName: 'Felamimail',
    modelName: 'Account',
    idProperty: 'id',
    titleProperty: 'name',
    // ngettext('Account', 'Accounts', n);
    recordName: 'Account',
    recordsName: 'Accounts',
    containerProperty: 'container_id',
    // ngettext('Email Accounts', 'Email Accounts', n);
    containerName: 'Email Accounts',
    containersName: 'Email Accounts',
    
    /**
     * @type Object
     */
    lastIMAPException: null,
    
    /**
     * get the last IMAP exception
     * 
     * @return {Object}
     */
    getLastIMAPException: function() {
        return this.lastIMAPException;
    },
    
    /**
     * returns sendfolder id
     * -> needed as trash is saved as globname :(
     */
    getSendFolderId: function() {
        var app = Ext.ux.PopupWindowMgr.getMainWindow().Tine.Tinebase.appMgr.get('Felamimail'),
            sendName = this.get('sent_folder'),
            accountId = this.id,
            send = sendName ? app.getFolderStore().queryBy(function(record) {
                return record.get('account_id') === accountId && record.get('globalname') === sendName;
            }, this).first() : null;
            
        return send ? send.id : null;
    },
    
    /**
     * returns trashfolder id
     * -> needed as trash is saved as globname :(
     */
    getTrashFolderId: function() {
        var app = Ext.ux.PopupWindowMgr.getMainWindow().Tine.Tinebase.appMgr.get('Felamimail'),
            trashName = this.get('trash_folder'),
            accountId = this.id,
            trash = trashName ? app.getFolderStore().queryBy(function(record) {
                return record.get('account_id') === accountId && record.get('globalname') === trashName;
            }, this).first() : null;
            
        return trash ? trash.id : null;
    },
    
    /**
     * set or clear IMAP exception and update imap_state
     * 
     * @param {Object} exception
     */
    setLastIMAPException: function(exception) {
        this.lastIMAPException = exception;
        this.set('imap_status', exception ? 'failure' : 'success');
        this.commit();
    }
});

/**
 * get default data for account
 * 
 * @return {Object}
 */
Tine.Felamimail.Model.Account.getDefaultData = function() { 
    var defaults = (Tine.Felamimail.registry.get('defaults')) 
        ? Tine.Felamimail.registry.get('defaults')
        : {};
        
    var currentUserDisplayName = Tine.Tinebase.registry.get('currentAccount').accountDisplayName;
    
    return {
        from: currentUserDisplayName,
        host: (defaults.host) ? defaults.host : '',
        port: (defaults.port) ? defaults.port : 143,
        smtp_hostname: (defaults.smtp && defaults.smtp.hostname) ? defaults.smtp.hostname : '',
        smtp_port: (defaults.smtp && defaults.smtp.port) ? defaults.smtp.port : 25,
        smtp_ssl: (defaults.smtp && defaults.smtp.ssl) ? defaults.smtp.ssl : 'none',
        sieve_port: 2000,
        sieve_ssl: 'none',
        signature: 'Sent with love from the new tine 2.0 email client ...<br/>'
            + 'Please visit <a href="http://tine20.org">http://tine20.org</a>',
        sent_folder: (defaults.sent_folder) ? defaults.sent_folder : 'Sent',
        trash_folder: (defaults.trash_folder) ? defaults.trash_folder : 'Trash'
    };
};

/**
 * @namespace Tine.Felamimail
 * @class Tine.Felamimail.accountBackend
 * @extends Tine.Tinebase.data.RecordProxy
 * 
 * Account Backend
 */ 
Tine.Felamimail.accountBackend = new Tine.Tinebase.data.RecordProxy({
    appName: 'Felamimail',
    modelName: 'Account',
    recordClass: Tine.Felamimail.Model.Account,
    
    /**
     * check accounts
     * 
     * @param   {String} ids
     * @param   {Object} options
     * @return  {Number} Ext.Ajax transaction id
     */
    checkAccounts: function(ids, options) {
        options = options || {};
        options.params = options.params || {};
        
        var p = options.params;
        
        p.method = this.appName + '.checkAccounts';
        p.ids = ids;
        
        //options.timeout = executionTime * 5000;
                
        return this.doXHTTPRequest(options);
    }
});

/**
 * @namespace Tine.Felamimail.Model
 * @class Tine.Felamimail.Model.Record
 * @extends Ext.data.Record
 * 
 * Folder Record Definition
 */ 
Tine.Felamimail.Model.Folder = Tine.Tinebase.data.Record.create([
      { name: 'id' },
      { name: 'localname' },
      { name: 'globalname' },
      { name: 'path' }, // /accountid/folderid/...
      { name: 'parent' },
      { name: 'parent_path' }, // /accountid/folderid/...
      { name: 'account_id' },
      { name: 'has_children',       type: 'bool' },
      { name: 'is_selectable',      type: 'bool' },
      { name: 'system_folder',      type: 'bool' },
      { name: 'imap_status' },
      { name: 'imap_timestamp',     type: 'date', dateFormat: Date.patterns.ISO8601Long },
      { name: 'imap_uidnext',       type: 'int' },
      { name: 'imap_uidvalidity',   type: 'int' },
      { name: 'imap_totalcount',    type: 'int' },
      { name: 'cache_status' },
      { name: 'cache_uidnext',      type: 'int' },
      { name: 'cache_recentcount',  type: 'int' },
      { name: 'cache_totalcount',   type: 'int' },
      { name: 'cache_unreadcount',  type: 'int' },
      { name: 'cache_timestamp',    type: 'date', dateFormat: Date.patterns.ISO8601Long  },
      { name: 'cache_job_actions_estimate',     type: 'int' },
      { name: 'cache_job_actions_done',         type: 'int' },
      { name: 'client_access_time', type: 'date', dateFormat: Date.patterns.ISO8601Long  } // client only {@see Tine.Felamimail.folderBackend#updateMessageCache}
], {
    // translations for system folders:
    // _('INBOX') _('Drafts') _('Sent') _('Templates') _('Junk') _('Trash')

    appName: 'Felamimail',
    modelName: 'Folder',
    idProperty: 'id',
    titleProperty: 'localname',
    // ngettext('Folder', 'Folders', n);
    recordName: 'Folder',
    recordsName: 'Folders',
    // ngettext('record list', 'record lists', n);
    containerName: 'Folder list',
    containersName: 'Folder lists',
    
    /**
     * is this folder the currently selected folder
     * 
     * @return {Boolean}
     */
    isCurrentSelection: function() {
        if (Tine.Tinebase.appMgr.get(this.appName).getMainScreen().getTreePanel()) {
            // get active node
            var node = Tine.Tinebase.appMgr.get(this.appName).getMainScreen().getTreePanel().getSelectionModel().getSelectedNode();
            if (node && node.attributes.folder_id) {
                return node.id == this.id;
            }
        }
        
        return false;
    },
    
    /**
     * is this folder an inbox?
     * 
     * @return {Boolean}
     */
    isInbox: function() {
        return Ext.util.Format.lowercase(this.get('localname')) === 'inbox';
    },
    
    /**
     * returns true if current folder needs an update
     */
    needsUpdate: function(updateInterval) {
        var timestamp = this.get('client_access_time');
        return this.get('cache_status') !== 'complete' || ! Ext.isDate(timestamp) || timestamp.getElapsed() > updateInterval;
    }
});

/**
 * @namespace Tine.Felamimail
 * @class Tine.Felamimail.folderBackend
 * @extends Tine.Tinebase.data.RecordProxy
 * 
 * Folder Backend
 */ 
Tine.Felamimail.folderBackend = new Tine.Tinebase.data.RecordProxy({
    appName: 'Felamimail',
    modelName: 'Folder',
    recordClass: Tine.Felamimail.Model.Folder,
    
    /**
     * update message cache of given folder for given execution time and sets the client_access_time
     * 
     * 
     * @param   {String} folderId
     * @param   {Number} executionTime (seconds)
     * @return  {Number} Ext.Ajax transaction id
     */
    updateMessageCache: function(folderId, executionTime, options) {
        options = options || {};
        options.params = options.params || {};
        
        var p = options.params;
        
        p.method = this.appName + '.updateMessageCache';
        p.folderId = folderId;
        p.time = executionTime;
        
        options.beforeSuccess = function(response) {
            var folder = this.recordReader(response);
            folder.set('client_access_time', new Date());
            return [folder];
        };
        
        // give 5 times more before timeout
        options.timeout = executionTime * 5000;
                
        return this.doXHTTPRequest(options);
    },
    
    /**
     * exception handler for this proxy
     * 
     * @param {Tine.Exception} exception
     */
    handleRequestException: function(exception) {
        Tine.Felamimail.handleRequestException(exception);
    }
});

/**
 * @namespace Tine.Felamimail.Model
 * @class Tine.Felamimail.Model.Vacation
 * @extends Tine.Tinebase.data.Record
 * 
 * Vacation Record Definition
 */ 
Tine.Felamimail.Model.Vacation = Tine.Tinebase.data.Record.create(Tine.Tinebase.Model.genericFields.concat([
    { name: 'id' },
    { name: 'reason' },
    { name: 'enabled', type: 'boolean'},
    { name: 'days' },
    { name: 'mime' }
]), {
    appName: 'Felamimail',
    modelName: 'Vacation',
    idProperty: 'id',
    titleProperty: 'id',
    // ngettext('Vacation', 'Vacations', n);
    recordName: 'Vacation',
    recordsName: 'Vacations',
    //containerProperty: 'container_id',
    // ngettext('record list', 'record lists', n);
    containerName: 'Vacation list',
    containersName: 'Vacation lists'    
});

/**
 * get default data for vacation
 * 
 * @return {Object}
 */
Tine.Felamimail.Model.Vacation.getDefaultData = function() { 
    return {
        days: 7,
        mime: 'multipart/alternative'
    };
};

/**
 * @namespace Tine.Felamimail
 * @class Tine.Felamimail.vacationBackend
 * @extends Tine.Tinebase.data.RecordProxy
 * 
 * Vacation Backend
 */ 
Tine.Felamimail.vacationBackend = new Tine.Tinebase.data.RecordProxy({
    appName: 'Felamimail',
    modelName: 'Vacation',
    recordClass: Tine.Felamimail.Model.Vacation,
    
    /**
     * exception handler for this proxy
     * 
     * @param {Tine.Exception} exception
     */
    handleRequestException: function(exception) {
        Tine.Felamimail.handleRequestException(exception);
    }
});

/**
 * @namespace Tine.Felamimail.Model
 * @class Tine.Felamimail.Model.Rule
 * @extends Tine.Tinebase.data.Record
 * 
 * Rule Record Definition
 */ 
Tine.Felamimail.Model.Rule = Tine.Tinebase.data.Record.create(Tine.Tinebase.Model.genericFields.concat([
    { name: 'id', sortType: function(value) {
        // should be sorted as int
        return parseInt(value, 10);
    }
    },
    { name: 'action_type' },
    { name: 'action_argument' },
    { name: 'enabled', type: 'boolean'},
    { name: 'conditions' },
    { name: 'account_id' }
]), {
    appName: 'Felamimail',
    modelName: 'Rule',
    idProperty: 'id',
    titleProperty: 'id',
    // ngettext('Rule', 'Rules', n);
    recordName: 'Rule',
    recordsName: 'Rules',
    // ngettext('record list', 'record lists', n);
    containerName: 'Rule list',
    containersName: 'Rule lists'    
});

/**
 * get default data for rules
 * 
 * @return {Object}
 */
Tine.Felamimail.Model.Rule.getDefaultData = function() { 
    return {
        enabled: true,
        conditions: [{
            test: 'address',
            header: 'from',
            comperator: 'contains',
            key: ''
        }],
        action_type: 'fileinto',
        action_argument: ''
    };
};

/**
 * @namespace Tine.Felamimail
 * @class Tine.Felamimail.rulesBackend
 * @extends Tine.Tinebase.data.RecordProxy
 * 
 * Rule Backend
 */ 
Tine.Felamimail.rulesBackend = new Tine.Tinebase.data.RecordProxy({
    appName: 'Felamimail',
    modelName: 'Rule',
    recordClass: Tine.Felamimail.Model.Rule,
    
    /**
     * searches all (lightweight) records matching filter
     * 
     * @param   {Object} filter accountId
     * @param   {Object} paging
     * @param   {Object} options
     * @return  {Number} Ext.Ajax transaction id
     * @success {Object} root:[records], totalcount: number
     */
    searchRecords: function(filter, paging, options) {
        options = options || {};
        options.params = options.params || {};
        var p = options.params;
        
        p.method = this.appName + '.get' + this.modelName + 's';
        p.accountId = filter;
        
        options.beforeSuccess = function(response) {
            return [this.jsonReader.read(response)];
        };
        
        // increase timeout as this can take a longer (1 minute)
        options.timeout = 60000;
        
        return this.doXHTTPRequest(options);
    },
    
    /**
     * save sieve rules
     *
     * @param  {String}     accountId
     * @param  {Array}      rules
     * @param  {Object}     options
     */
    saveRules: function(accountId, rules, options)
    {
        options = options || {};
        options.params = options.params || {};
        
        var p = options.params;
        
        p.method = this.appName + '.saveRules';
        p.accountId = accountId;
        p.rulesData = rules;
        
        return this.doXHTTPRequest(options);
    },

    /**
     * saves a single record
     * 
     * NOTE: Single rule records can't be saved
     * 
     * @param   {Ext.data.Record} record
     * @param   {Object} options
     * @return  {Number} Ext.Ajax transaction id
     * @success {Ext.data.Record}
     */
    saveRecord: function(record, options, additionalArguments) {
        // does nothing
    },
    
    /**
     * exception handler for this proxy
     * 
     * @param {Tine.Exception} exception
     */
    handleRequestException: function(exception) {
        Tine.Felamimail.handleRequestException(exception);
    }
});

/**
 * @namespace Tine.Felamimail.Model
 * @class Tine.Felamimail.Model.Flag
 * @extends Tine.Tinebase.data.Record
 * 
 * Flag Record Definition
 */ 
Tine.Felamimail.Model.Flag = Tine.Tinebase.data.Record.create(Tine.Tinebase.Model.genericFields.concat([
    { name: 'id' },
    { name: 'name' }
]), {
    appName: 'Felamimail',
    modelName: 'Flag',
    idProperty: 'id',
    titleProperty: 'id',
    // ngettext('Flag', 'Flags', n);
    recordName: 'Flag',
    recordsName: 'Flags',
    // ngettext('Flag list', 'Flag lists', n);
    containerName: 'Flag list',
    containersName: 'Flag lists'    
});

// file: /home/lkneschke/temp/tine20build/temp/tine20/Felamimail/js/FolderStore.js
/*
 * Tine 2.0
 * 
 * @package     Felamimail
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Philipp Schuele <p.schuele@metaways.de>
 * @copyright   Copyright (c) 2009-2010 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: FolderStore.js 17579 2010-12-03 10:51:25Z p.schuele@metaways.de $
 *
 */
 
Ext.namespace('Tine.Felamimail');

/**
 * @namespace   Tine.Felamimail
 * @class       Tine.Felamimail.FolderStore
 * @extends     Ext.data.Store
 * 
 * <p>Felamimail folder store</p>
 * <p>
 * </p>
 * 
 * @author      Philipp Schuele <p.schuele@metaways.de>
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @version     $Id: FolderStore.js 17579 2010-12-03 10:51:25Z p.schuele@metaways.de $
 * 
 * @param       {Object} config
 * 
 * @constructor
 * Create a new  Tine.Felamimail.FolderStore
 */
Tine.Felamimail.FolderStore = function(config) {
    config = config || {};
    Ext.apply(this, config);
    
    this.reader = Tine.Felamimail.folderBackend.getReader();
    this.queriesPending = [];
    this.queriesDone = [];

    Tine.Felamimail.FolderStore.superclass.constructor.call(this);
    
    this.on('load', this.onStoreLoad, this);
    this.on('add', this.onStoreAdd, this);
};

Ext.extend(Tine.Felamimail.FolderStore, Ext.data.Store, {
    
    fields: Tine.Felamimail.Model.Folder,
    proxy: Tine.Felamimail.folderBackend,
    
    /**
     * @property queriesDone
     * @type Array
     */
    queriesDone: null,
    
    /**
     * @property queriesPending
     * @type Array
     */
    queriesPending: null,
    
    /**
     * async query
     */
    asyncQuery: function(field, value, callback, args, scope, store) {
        var result = null,
            key = store.getKey(field, value);
        
        Tine.log.info('Tine.Felamimail.FolderStore.asyncQuery: ' + key);
        
        if (store.queriesDone.indexOf(key) >= 0) {
            Tine.log.debug('result already loaded -> directly query store');
            // we need regexp here because query returns all records with path that begins with the value string otherwise
            var valueReg = new RegExp(value + '$');
            result = store.query(field, valueReg);
            args.push(result);
            callback.apply(scope, args);
        } else if (store.queriesPending.indexOf(key) >= 0) {
            Tine.log.debug('result not in store yet, but async query already running -> wait a bit');
            this.asyncQuery.defer(2500, this, [field, value, callback, args, scope, store]);
        } else {
            Tine.log.debug('result is requested the first time -> fetch from server');
            var accountId = value.match(/^\/([a-z0-9]*)/i)[1],
                folderIdMatch = value.match(/[a-z0-9]+\/([a-z0-9]*)$/i),
                folderId = (folderIdMatch) ? folderIdMatch[1] : null,
                folder = folderId ? store.getById(folderId) : null;
            
            if (folderId && ! folder) {
                Tine.log.warn('folder ' + folderId + ' not found -> performing no query at all');
                callback.apply(scope, args);
                return;
            }
            
            store.queriesPending.push(key);
            store.load({
                path: value,
                params: {filter: [
                    {field: 'account_id', operator: 'equals', value: accountId},
                    {field: 'globalname', operator: 'equals', value: (folder !== null) ? folder.get('globalname') : ''}
                ]},
                callback: function () {
                    store.queriesDone.push(key);
                    store.queriesPending.remove(key);
                    
                    // query store again (it should have the new folders now) and call callback function to add nodes
                    result = store.query(field, value);
                    args.push(result);
                    callback.apply(scope, args);
                },
                add: true
            });
        }
    },
    
    /**
     * check if query has already loaded or is loading
     * 
     * @param {String} field
     * @param {String} value
     * @return {boolean}
     */
    isLoadedOrLoading: function(field, value) {
        var key = this.getKey(field, value),
            result = false;
        
        result = (this.queriesDone.indexOf(key) >= 0 || this.queriesPending.indexOf(key) >= 0);
        
        return result;
    },
    
    /**
     * get key to store query 
     * 
     * @param  {string} field
     * @param  {mixed} value
     * @return {string}
     */
    getKey: function(field, value) {
        return field + ' -> ' + value;
    },
    
    /**
     * load event handler
     * 
     * @param {Tine.Felamimail.FolderStore} store
     * @param {Tine.Felamimail.Model.Folder} records
     * @param {Object} options
     */
    onStoreLoad: function(store, records, options) {
        this.computePaths(records, options.path);
    },
    
    /**
     * add event handler
     * 
     * @param {Tine.Felamimail.FolderStore} store
     * @param {Tine.Felamimail.Model.Folder} records
     * @param {Integer} index
     */
    onStoreAdd: function(store, records, index) {
        this.computePaths(records, null);
    },

    /**
     * compute paths for folder records
     * 
     * @param {Tine.Felamimail.Model.Folder} records
     * @param {String|null} parentPath
     */
    computePaths: function(records, givenParentPath) {
        var parentPath, path;
        Ext.each(records, function(record) {
            if (givenParentPath === null) {
                var parent = this.getParentByAccountIdAndGlobalname(record.get('account_id'), record.get('parent'));
                parentPath = (parent) ? parent.get('path') : '/' + record.get('account_id');
            } else {
                parentPath = givenParentPath;
            }
            path = parentPath + '/' + record.id;
            
            if (record.get('parent_path') != parentPath || record.get('path') != path) {
                record.beginEdit();
                record.set('parent_path', parentPath);
                record.set('path', path);
                record.endEdit();
            }
        }, this);        
    },
    
    /**
     * resets the query and removes all records that match it
     * 
     * @param {String} field
     * @param {String} value
     */
    resetQueryAndRemoveRecords: function(field, value) {
        this.queriesPending.remove(this.getKey(field, value));
        var toRemove = this.query(field, value);
        toRemove.each(function(record) {
            this.remove(record);
            this.queriesDone.remove(this.getKey(field, record.get(field)));
        }, this);
    },
    
    /**
     * update folder in this store
     * 
     * NOTE: parent_path and path are computed onLoad and must be preserved
     * 
     * @param {Array/Tine.Felamimail.Model.Folder} update
     * @return {Tine.Felamimail.Model.Folder}
     */
    updateFolder: function(update) {
        if (Ext.isArray(update)) {
            Ext.each(update, function(u) {this.updateFolder.call(this, u)}, this);
            return;
        }
        
        var folder = this.getById(update.id);
        
        if (folder) {
            folder.beginEdit();
            Ext.each(Tine.Felamimail.Model.Folder.getFieldNames(), function(f) {
                if (! f.match('path')) {
                    folder.set(f, update.get(f));
                }
            }, this);
            folder.endEdit();
            folder.commit();
            return folder;
        }
    },
    
    /**
     * get by account id and globalname
     * 
     * @param {String} accountId
     * @param {String} globalname
     * @return {Tine.Felamimail.Model.Folder|null}
     */
    getParentByAccountIdAndGlobalname: function(accountId, globalname) {
        var result = this.queryBy(function(record, id) {
            if (record.get('account_id') == accountId && record.get('globalname') == globalname) {
                return true;
            }
        });
        
        return result.first() || null;
    }
});


// file: /home/lkneschke/temp/tine20build/temp/tine20/Felamimail/js/FolderSelect.js
/*
 * Tine 2.0
 * 
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Philipp Schuele <p.schuele@metaways.de>
 * @copyright   Copyright (c) 2010 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: FolderSelect.js 17565 2010-12-02 17:39:08Z p.schuele@metaways.de $
 */
Ext.ns('Tine.Felamimail');

/**
 * folder select trigger field
 * 
 * @namespace   Tine.widgets.container
 * @class       Tine.Felamimail.FolderSelectTriggerField
 * @extends     Ext.form.ComboBox
 * @author      Philipp Schuele <p.schuele@metaways.de>
 * @version     $Id: FolderSelect.js 17565 2010-12-02 17:39:08Z p.schuele@metaways.de $
 * 
 */
Tine.Felamimail.FolderSelectTriggerField = Ext.extend(Ext.form.TriggerField, {
    
    triggerClass: 'x-form-search-trigger',
    account: null,
    allAccounts: false,
    
    /**
     * onTriggerClick
     * open ext window with (folder-)select panel that fires event on select
     * 
     * @param e
     */
    onTriggerClick: function(e) {
        if ((this.account && this.account.id !== 0) || this.allAccounts) {
            this.selectPanel = Tine.Felamimail.FolderSelectPanel.openWindow({
                account: this.account,
                allAccounts: this.allAccounts,
                listeners: {
                    // NOTE: scope has to be first item in listeners! @see Ext.ux.WindowFactory
                    scope: this,
                    'folderselect': this.onSelectFolder
                }
            });
        }
    },
    
    /**
     * select folder event listener
     * 
     * @param {Ext.tree.AsyncTreeNode} node
     */
    onSelectFolder: function(node) {
        this.selectPanel.close();
        this.setValue(node.attributes.globalname);
        this.el.focus();
    }
});
Ext.reg('felamimailfolderselect', Tine.Felamimail.FolderSelectTriggerField);

// file: /home/lkneschke/temp/tine20build/temp/tine20/Felamimail/js/FolderSelectPanel.js
/*
 * Tine 2.0
 * 
 * @package     Felamimail
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Philipp Schuele <p.schuele@metaways.de>
 * @copyright   Copyright (c) 2010 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: FolderSelectPanel.js 17810 2010-12-13 13:16:54Z p.schuele@metaways.de $
 *
 */
 
Ext.namespace('Tine.Felamimail');

/**
 * @namespace   Tine.Felamimail
 * @class       Tine.Felamimail.FolderSelectPanel
 * @extends     Ext.Panel
 * 
 * <p>Account/Folder Tree Panel</p>
 * <p>Tree of Accounts with folders</p>
 * <pre>
 * TODO         make it possible to preselect folder
 * TODO         use it for folder subscriptions
 * </pre>
 * 
 * @author      Philipp Schuele <p.schuele@metaways.de>
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @version     $Id: FolderSelectPanel.js 17810 2010-12-13 13:16:54Z p.schuele@metaways.de $
 * 
 * @param       {Object} config
 * @constructor
 * Create a new Tine.Felamimail.FolderSelectPanel
 */
Tine.Felamimail.FolderSelectPanel = Ext.extend(Ext.Panel, {
	
    /**
     * Panel config
     * @private
     */
    frame: true,
    border: true,
    autoScroll: true,
    bodyStyle: 'background-color:white',
    selectedNode: null,
	
    /**
     * init
     * @private
     */
    initComponent: function() {
        this.addEvents(
            /**
             * @event folderselect
             * Fired when folder is selected
             */
            'folderselect'
        );

        this.app = Tine.Tinebase.appMgr.get('Felamimail');
        
        if (! this.allAccounts) {
            this.account = this.account || this.app.getActiveAccount();
        }
        
        this.initActions();
        this.initFolderTree();
        
        Tine.Felamimail.FolderSelectPanel.superclass.initComponent.call(this);
	},
    
    /**
     * init actions
     */
    initActions: function() {
        this.action_cancel = new Ext.Action({
            text: _('Cancel'),
            minWidth: 70,
            scope: this,
            handler: this.onCancel,
            iconCls: 'action_cancel'
        });
        
        this.action_ok = new Ext.Action({
            disabled: true,
            text: _('Ok'),
            iconCls: 'action_saveAndClose',
            minWidth: 70,
            handler: this.onOk,
            scope: this
        });        
        
        this.fbar = [
            '->',
            this.action_cancel,
            this.action_ok
        ];        
    },
        
    /**
     * init folder tree
     */
    initFolderTree: function() {
        
        if (this.allAccounts) {

            this.root = new Ext.tree.TreeNode({
                text: 'default',
                draggable: false,
                allowDrop: false,
                expanded: true,
                leaf: false,
                id: 'root'
            });
        
            Tine.Felamimail.loadAccountStore().each(function(record) {
                // TODO generalize this
                var node = new Ext.tree.AsyncTreeNode({
                    id: record.data.id,
                    path: '/' + record.data.id,
                    record: record,
                    globalname: '',
                    draggable: false,
                    allowDrop: false,
                    expanded: false,
                    text: record.get('name'),
                    qtip: record.get('host'),
                    leaf: false,
                    cls: 'felamimail-node-account',
                    delimiter: record.get('delimiter'),
                    ns_personal: record.get('ns_personal'),
                    account_id: record.data.id
                });
            
                this.root.appendChild(node);
            }, this);
            
        } else {
            this.root = new Ext.tree.AsyncTreeNode({
                text: this.account.get('name'),
                draggable: false,
                allowDrop: false,
                expanded: true,
                leaf: false,
                cls: 'felamimail-node-account',
                id: this.account.id,
                path: '/' + this.account.id
            });
        }
        
        
        this.folderTree = new Ext.tree.TreePanel({
            id: 'felamimail-foldertree',
            rootVisible: ! this.allAccounts,
            store: this.store || this.app.getFolderStore(),
            // TODO use another loader/store for subscriptions
            loader: this.loader || new Tine.Felamimail.TreeLoader({
                folderStore: this.store,
                app: this.app
            }),
            root: this.root
        });
        this.folderTree.on('dblclick', this.onTreeNodeDblClick, this);
        this.folderTree.on('click', this.onTreeNodeClick, this);
        
        this.items = [this.folderTree];
    },
    
    /**
     * @private
     */
    afterRender: function() {
        Tine.Felamimail.FolderSelectPanel.superclass.afterRender.call(this);
        
        var title = (! this.allAccounts) 
            ? String.format(this.app.i18n._('Folders of account {0}'), this.account.get('name'))
            : this.app.i18n._('Folders of all accounts');
            
        this.window.setTitle(title);
    },

    /**
     * on folder select handler
     * 
     * @param {Ext.tree.AsyncTreeNode} node
     * @private
     */
    onTreeNodeDblClick: function(node) {
        this.selectedNode = node;
        this.onOk();
        return false;
    },
    
    /**
     * @private
     */
    onTreeNodeClick: function(node) {
        this.selectedNode = node;
        this.action_ok.setDisabled(false);
    },
    
    /**
     * @private
     */
    onCancel: function(){
        this.purgeListeners();
        this.window.close();
    },
    
    /**
     * @private
     */
    onOk: function() {
        if (this.selectedNode) {
            this.fireEvent('folderselect', this.selectedNode);
        }
    }
});

/**
 * Felamimail FolderSelectPanel Popup
 * 
 * @param   {Object} config
 * @return  {Ext.ux.Window}
 */
Tine.Felamimail.FolderSelectPanel.openWindow = function (config) {
    var window = Tine.WindowFactory.getWindow({
        width: 200,
        height: 300,
        modal: true,
        name: Tine.Felamimail.FolderSelectPanel.prototype.windowNamePrefix + Ext.id(),
        contentPanelConstructor: 'Tine.Felamimail.FolderSelectPanel',
        contentPanelConstructorConfig: config
    });
    return window;
};

// file: /home/lkneschke/temp/tine20build/temp/tine20/Felamimail/js/sieve/VacationEditDialog.js
/*
 * Tine 2.0
 * 
 * @package     Felamimail
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Philipp Schüle <p.schuele@metaways.de>
 * @copyright   Copyright (c) 2010-2011 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: VacationEditDialog.js 18750 2011-01-19 10:55:49Z p.schuele@metaways.de $
 *
 */
 
Ext.namespace('Tine.Felamimail.sieve');

/**
 * @namespace   Tine.Felamimail
 * @class       Tine.Felamimail.sieve.VacationEditDialog
 * @extends     Tine.widgets.dialog.EditDialog
 * 
 * <p>Sieve Filter Dialog</p>
 * <p>This dialog is editing sieve filters (vacation and rules).</p>
 * <p>
 * TODO         add signature from account?
 * </p>
 * 
 * @author      Philipp Schuele <p.schuele@metaways.de>
 * @copyright   Copyright (c) 2010 Metaways Infosystems GmbH (http://www.metaways.de)
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @version     $Id: VacationEditDialog.js 18750 2011-01-19 10:55:49Z p.schuele@metaways.de $
 * 
 * @param       {Object} config
 * @constructor
 * Create a new VacationEditDialog
 */
 Tine.Felamimail.sieve.VacationEditDialog = Ext.extend(Tine.widgets.dialog.EditDialog, {

    /**
     * @cfg {Tine.Felamimail.Model.Account}
     */
    account: null,
    
    /**
     * @private
     */
    windowNamePrefix: 'VacationEditWindow_',
    appName: 'Felamimail',
    recordClass: Tine.Felamimail.Model.Vacation,
    recordProxy: Tine.Felamimail.vacationBackend,
    loadRecord: true,
    tbarItems: [],
    evalGrants: false,
    
    /**
     * overwrite update toolbars function (we don't have record grants yet)
     * 
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
        // interrupt process flow till dialog is rendered
        if (! this.rendered) {
            this.onRecordLoad.defer(250, this);
            return;
        }
        
        // mime type is always multipart/alternative
        this.record.set('mime', 'multipart/alternative');

        this.getForm().loadRecord(this.record);
        
        var title = String.format(this.app.i18n._('Vacation Message for {0}'), this.account.get('name'));
        this.window.setTitle(title);
        
        this.loadMask.hide();
    },
        
    /**
     * returns dialog
     * 
     * NOTE: when this method gets called, all initalisation is done.
     * 
     * @return {Object}
     * @private
     * 
     */
    getFormItems: function() {
        
        this.reasonEditor = new Ext.form.HtmlEditor({
            fieldLabel: this.app.i18n._('Incoming mails will be answered with this text:'),
            name: 'reason',
            allowBlank: true,
            disabled: (this.record.get('enabled') == false),
            height: 220,
            getDocMarkup: function() {
                var markup = '<html><body></body></html>';
                return markup;
            },
            plugins: [
                new Ext.ux.form.HtmlEditor.RemoveFormat()
            ]
        });
        
        return {
            xtype: 'tabpanel',
            deferredRender: false,
            border: false,
            activeTab: 0,
            items: [{
                title: this.app.i18n._('General'),
                autoScroll: true,
                border: false,
                frame: true,
                xtype: 'columnform',
                formDefaults: {
                    anchor: '100%',
                    labelSeparator: '',
                    columnWidth: 1
                },
                items: [[
                // TODO make the radiogroup work
//                    {
//                    xtype: 'radiogroup',
//                    hideLabel: true,
//                    columns: 1,
//                    name: 'enabledGroup',
//                    items: [
//                        {
//                            boxLabel: this.app.i18n._('I am available (vacation message disabled)'),
//                            inputValue: 1,
//                            //value: 1
//                            name: 'enabled'
//                            //checked: this.record.get('enabled') /*, name: 'enabled', inputValue: true */ /* , checked: this.record.get('enabled')*/
//                        },
//                        {
//                            boxLabel: this.app.i18n._('I am not available (vacation message enabled)'),
//                            inputValue: 0,
//                            //value: 0
//                            name: 'enabled'
//                            //checked: !! this.record.get('enabled') /*, name: 'enabled', inputValue: false*/
//                        }
//                    ],
//                    listeners: {
//                        scope: this,
//                        change: function(group, radio) {
//                            //this.record.set('enabled', radio.inputValue);
//                            this.reasonEditor.setDisabled(! radio.inputValue);
//                        }
//                    }
//                },
                    {
                        fieldLabel: this.app.i18n._('Status'),
                        name: 'enabled',
                        typeAhead     : false,
                        triggerAction : 'all',
                        lazyRender    : true,
                        editable      : false,
                        mode          : 'local',
                        forceSelection: true,
                        value: 0,
                        xtype: 'combo',
                        store: [
                            [0, this.app.i18n._('I am available (vacation message disabled)')], 
                            [1, this.app.i18n._('I am not available (vacation message enabled)')]
                        ],
                        listeners: {
                            scope: this,
                            select: function (combo, record) {
                                this.reasonEditor.setDisabled(! record.data.field1);
                            }
                        }
                    },
                    this.reasonEditor
                ]]
            }, {
                title: this.app.i18n._('Advanced'),
                autoScroll: true,
                border: false,
                frame: true,
                xtype: 'columnform',
                formDefaults: {
                    anchor: '100%',
                    labelSeparator: '',
                    columnWidth: 1
                },
                items: [[{
                    fieldLabel: this.app.i18n._('Only send all X days to the same sender'),
                    name: 'days',
                    value: 7,
                    xtype: 'numberfield',
                    allowNegative: false,
                    minValue: 1
                }]]
            }]
        };
    },
    
    /**
     * generic request exception handler
     * 
     * @param {Object} exception
     */
    onRequestFailed: function(exception) {
        Tine.Felamimail.handleRequestException(exception);
        this.loadMask.hide();
    }    
});

/**
 * Felamimail Edit Popup
 * 
 * @param   {Object} config
 * @return  {Ext.ux.Window}
 */
Tine.Felamimail.sieve.VacationEditDialog.openWindow = function (config) {
    var window = Tine.WindowFactory.getWindow({
        width: 640,
        height: 480,
        name: Tine.Felamimail.sieve.VacationEditDialog.prototype.windowNamePrefix + Ext.id(),
        contentPanelConstructor: 'Tine.Felamimail.sieve.VacationEditDialog',
        contentPanelConstructorConfig: config
    });
    return window;
};

// file: /home/lkneschke/temp/tine20build/temp/tine20/Felamimail/js/sieve/RuleEditDialog.js
/*
 * Tine 2.0
 * 
 * @package     Felamimail
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Philipp Schuele <p.schuele@metaways.de>
 * @copyright   Copyright (c) 2010 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: RuleEditDialog.js 18527 2011-01-11 16:37:03Z p.schuele@metaways.de $
 *
 */
 
Ext.namespace('Tine.Felamimail.sieve');

/**
 * @namespace   Tine.Felamimail.sieve
 * @class       Tine.Felamimail.sieve.RuleEditDialog
 * @extends     Tine.widgets.dialog.EditDialog
 * 
 * <p>Sieve Filter Dialog</p>
 * <p>This dialog is editing a filter rule.</p>
 * <p>
 * </p>
 * 
 * @author      Philipp Schuele <p.schuele@metaways.de>
 * @copyright   Copyright (c) 2010 Metaways Infosystems GmbH (http://www.metaways.de)
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @version     $Id: RuleEditDialog.js 18527 2011-01-11 16:37:03Z p.schuele@metaways.de $
 * 
 * @param       {Object} config
 * @constructor
 * Create a new RuleEditDialog
 */
Tine.Felamimail.sieve.RuleEditDialog = Ext.extend(Tine.widgets.dialog.EditDialog, {
    /**
     * @cfg {Tine.Felamimail.Model.Account}
     */
    account: null,
    
    /**
     * @private
     */
    windowNamePrefix: 'RuleEditWindow_',
    appName: 'Felamimail',
    recordClass: Tine.Felamimail.Model.Rule,
    mode: 'local',
    loadRecord: true,
    tbarItems: [],
    evalGrants: false,
    
    /**
     * overwrite update toolbars function (we don't have record grants yet)
     * 
     * @private
     */
    updateToolbars: function() {

    },
    
    /**
     * @private
     */
    onRender: function(ct, position) {
        Tine.Felamimail.sieve.RuleEditDialog.superclass.onRender.call(this, ct, position);
        
        this.onChangeType.defer(250, this);
    },
    
    /**
     * Change type card layout depending on selected combo box entry and set field value
     */
    onChangeType: function() {
        var type = this.actionTypeCombo.getValue();
        
        var cardLayout = Ext.getCmp(this.idPrefix + 'CardLayout').getLayout();
        if (cardLayout !== 'card') {
            cardLayout.setActiveItem(this.idPrefix + type);
            if (this.record.get('action_type') == type) {
                var field = this.getForm().findField('action_argument_' + type);
                if (field !== null) {
                    field.setValue(this.record.get('action_argument'));
                }
            }
        }
    },
    
    /**
     * executed after record got updated from proxy
     * 
     * @private
     */
    onRecordLoad: function() {
        // interrupt process flow till dialog is rendered
        if (! this.rendered) {
            this.onRecordLoad.defer(250, this);
            return;
        }
        
        var title = this.app.i18n._('Edit Filter Rule');
        this.window.setTitle(title);
        
        this.getForm().loadRecord(this.record);
        
        this.loadMask.hide();
    },
    
    /**
     * @private
     */
    onRecordUpdate: function() {
        Tine.Felamimail.sieve.RuleEditDialog.superclass.onRecordUpdate.call(this);
        
        this.record.set('conditions', this.getConditions());
        
        var argumentField = this.getForm().findField('action_argument_' + this.actionTypeCombo.getValue()),
            argumentValue = (argumentField !== null) ? argumentField.getValue() : '';
        this.record.set('action_argument', argumentValue);
    },
    
    /**
     * get conditions and do the mapping
     * 
     * @return {Array}
     */
    getConditions: function() {
        var conditions = this.conditionsPanel.getAllFilterData();
        var result = [],
            i = 0, 
            condition,
            test,
            comperator,
            header;
            
        for (i = 0; i < conditions.length; i++) {
            // set defaults
            comperator = conditions[i].operator;
            header = conditions[i].field;
            test = 'header';

            switch (conditions[i].field) {
                case 'from':
                case 'to':
                    test = 'address';
                    break;
                case 'size':
                    test = 'size';
                    comperator = (conditions[i].operator == 'greater') ? 'over' : 'under';
                    break;
                case 'header':
                    header = conditions[i].operator;
                    comperator = 'contains';
                    break;
            }
            condition = {
                test: test,
                header: header,
                comperator: comperator,
                key: conditions[i].value
            };
            result.push(condition);            
        }
        return result;
    },
    
    /**
     * get conditions filter data (reverse of getConditions)
     * 
     * @return {Array}
     */
    getConditionsFilter: function() {
        var conditions = this.record.get('conditions');
        var result = [],
            i = 0, 
            filter,
            operator,
            field;
            
        for (i = 0; i < conditions.length; i++) {
            field = conditions[i].header;
            switch (field) {
                case 'size':
                    operator = (conditions[i].comperator == 'over') ? 'greater' : 'less';
                    break;
                case 'from':
                case 'to':
                case 'subject':
                    operator = conditions[i].comperator;
                    break;
                default:
                    operator = field;
                    field = 'header';
            }
            filter = {
                field: field,
                operator: operator,
                value: conditions[i].key
            };
            result.push(filter);            
        }
        
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
        
        this.conditionsPanel = new Tine.Felamimail.sieve.RuleConditionsPanel({
            filters: this.getConditionsFilter()
        });
        
        this.actionTypeCombo = new Ext.form.ComboBox({
            hideLabel       : true,
            name            : 'action_type',
            typeAhead       : false,
            triggerAction   : 'all',
            lazyRender      : true,
            editable        : false,
            mode            : 'local',
            forceSelection  : true,
            value           : 'discard',
            columnWidth     : 0.4,
            store: Tine.Felamimail.sieve.RuleEditDialog.getActionTypes(this.app),
            listeners: {
                scope: this,
                change: this.onChangeType,
                select: this.onChangeType
            }
        });
        
        this.idPrefix = Ext.id();
        
        return [{
            xtype: 'panel',
            layout: 'border',
            autoScroll: true,
            items: [
            {
                title: this.app.i18n._('If all of the following conditions are met:'),
                region: 'north',
                border: false,
                items: [
                    this.conditionsPanel
                ],
                xtype: 'panel',
                listeners: {
                    scope: this,
                    afterlayout: function(ct, layout) {
                        ct.suspendEvents();
                        ct.setHeight(this.conditionsPanel.getHeight()+30);
                        ct.ownerCt.layout.layout();
                        ct.resumeEvents();
                    }
                }
            }, {
                title: this.app.i18n._('Do this action:'),
                region: 'center',
                border: false,
                frame: true,
                layout: 'column',
                items: [
                    this.actionTypeCombo,
                    // TODO try to add a spacer/margin between the two input fields
                /*{
                    // spacer
                    columnWidth: 0.1,
                    layout: 'fit',
                    title: '',
                    items: []
                }, */{
                    id: this.idPrefix + 'CardLayout',
                    layout: 'card',
                    activeItem: this.idPrefix + 'fileinto',
                    border: false,
                    columnWidth: 0.5,
                    defaults: {
                        border: false
                    },
                    items: [{
                        id: this.idPrefix + 'fileinto',
                        layout: 'form',
                        items: [{
                            name: 'action_argument_fileinto',
                            xtype: 'felamimailfolderselect',
                            width: 200,
                            hideLabel: true,
                            account: this.account
                        }]
                    }, {
                        // TODO add email validator?
                        id: this.idPrefix + 'redirect',
                        layout: 'form',
                        items: [{
                            name: 'action_argument_redirect',
                            xtype: 'textfield',
                            emptyText: 'test@example.org',
                            width: 200,
                            hideLabel: true
                        }]
                    }, {
                        id: this.idPrefix + 'reject',
                        layout: 'form',
                        items: [{
                            name: 'action_argument_reject',
                            xtype: 'textarea',
                            width: 300,
                            height: 60,
                            hideLabel: true
                        }]
                    }, {
                        id: this.idPrefix + 'discard',
                        layout: 'fit',
                        items: []
                    }]
                }]
            }]
        }];
    }
});

/**
 * Felamimail Edit Popup
 * 
 * @param   {Object} config
 * @return  {Ext.ux.Window}
 */
Tine.Felamimail.sieve.RuleEditDialog.openWindow = function (config) {
    var window = Tine.WindowFactory.getWindow({
        width: 700,
        height: 300,
        name: Tine.Felamimail.sieve.RuleEditDialog.prototype.windowNamePrefix + Ext.id(),
        contentPanelConstructor: 'Tine.Felamimail.sieve.RuleEditDialog',
        contentPanelConstructorConfig: config
    });
    return window;
};

/**
 * get action types for action combo and action type renderer
 * 
 * @param {} app
 * @return {Array}
 */
Tine.Felamimail.sieve.RuleEditDialog.getActionTypes = function(app) {
    return [
        ['fileinto',    app.i18n._('Move mail to folder')],
        ['redirect',    app.i18n._('Redirect mail to address')],
        ['reject',      app.i18n._('Reject mail with this text')],
        ['discard',     app.i18n._('Discard mail')]
        //['keep',        app.i18n._('Keep mail')],
    ];
};

// file: /home/lkneschke/temp/tine20build/temp/tine20/Felamimail/js/sieve/RulesGridPanel.js
/*
 * Tine 2.0
 * 
 * @package     Felamimail
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Philipp Schuele <p.schuele@metaways.de>
 * @copyright   Copyright (c) 2010 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: RulesGridPanel.js 18826 2011-01-24 14:34:25Z p.schuele@metaways.de $
 *
 */
 
Ext.ns('Tine.Felamimail.sieve');

/**
 * @namespace Tine.Felamimail
 * @class     Tine.Felamimail.sieve.RulesGridPanel
 * @extends   Tine.widgets.grid.GridPanel
 * Rules Grid Panel <br>
 * 
 * @author      Philipp Schuele <p.schuele@metaways.de>
 * @version     $Id: RulesGridPanel.js 18826 2011-01-24 14:34:25Z p.schuele@metaways.de $
 */
Tine.Felamimail.sieve.RulesGridPanel = Ext.extend(Tine.widgets.grid.GridPanel, {
    /**
     * @cfg {Tine.Felamimail.Model.Account}
     */
    account: null,
    
    // model generics
    recordClass: Tine.Felamimail.Model.Rule,
    recordProxy: Tine.Felamimail.rulesBackend,
    
    // grid specific
    defaultSortInfo: {field: 'id', dir: 'ASC'},
    storeRemoteSort: false,
    
    // not yet
    evalGrants: false,
    usePagingToolbar: false,
    
    newRecordIcon: 'action_new_rule',
    editDialogClass: Tine.Felamimail.sieve.RuleEditDialog,
    
    initComponent: function() {
        this.app = Tine.Tinebase.appMgr.get('Felamimail');
        this.initColumns();
        
        this.editDialogConfig = {
            account: this.account
        };
        
        this.supr().initComponent.call(this);
    },
    
    /**
     * Return CSS class to apply to rows depending on enabled status
     * 
     * @param {Tine.Felamimail.Model.Rule} record
     * @param {Integer} index
     * @return {String}
     */
    getViewRowClass: function(record, index) {
        var className = '';
        
        if (! record.get('enabled')) {
            className += ' felamimail-sieverule-disabled';
        }
        
        return className;
    },
    
    /**
     * init actions with actionToolbar, contextMenu and actionUpdater
     * 
     * @private
     */
    initActions: function() {
        this.action_moveup = new Ext.Action({
            text: this.app.i18n._('Move up'),
            handler: this.onMoveRecord.createDelegate(this, ['up']),
            scope: this,
            iconCls: 'action_move_up'
        });

        this.action_movedown = new Ext.Action({
            text: this.app.i18n._('Move down'),
            handler: this.onMoveRecord.createDelegate(this, ['down']),
            scope: this,
            iconCls: 'action_move_down'
        });

        this.action_enable = new Ext.Action({
            text: this.app.i18n._('Enable'),
            handler: this.onEnableDisable.createDelegate(this, [true]),
            scope: this,
            iconCls: 'action_enable'
        });

        this.action_disable = new Ext.Action({
            text: this.app.i18n._('Disable'),
            handler: this.onEnableDisable.createDelegate(this, [false]),
            scope: this,
            iconCls: 'action_disable'
        });
        
        this.supr().initActions.call(this);
    },
    
    /**
     * enable / disable rule
     * 
     * @param {Boolean} state
     */
    onEnableDisable: function(state) {
        var selectedRows = this.grid.getSelectionModel().getSelections();
        for (var i = 0; i < selectedRows.length; i++) {
            selectedRows[i].set('enabled', state);
        }
    },
    
    /**
     * move record up or down
     * 
     * @param {String} dir (up|down)
     */
    onMoveRecord: function(dir) {
        var sm = this.grid.getSelectionModel();
            
        if (sm.getCount() == 1) {
            var selectedRows = sm.getSelections();
            record = selectedRows[0];
            
            // get next/prev record
            var index = this.store.indexOf(record),
                switchRecordIndex = (dir == 'down') ? index + 1 : index - 1,
                switchRecord = this.store.getAt(switchRecordIndex);
            
            if (switchRecord) {
                // switch ids and resort store
                var oldId = record.id;
                    switchId = switchRecord.id;

                record.set('id', Ext.id());
                record.id = Ext.id();
                switchRecord.set('id', oldId);
                switchRecord.id = oldId;
                record.set('id', switchId);
                record.id = switchId;
                
                this.store.commitChanges();
                this.store.sort('id', 'ASC');
                sm.selectRecords([record]);
            }
        }
    },

    /**
     * add custom items to action toolbar
     * 
     * @return {Object}
     */
    getActionToolbarItems: function() {
        return [
            {
                xtype: 'buttongroup',
                columns: 1,
                frame: false,
                items: [
                    this.action_moveup,
                    this.action_movedown
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
            this.action_moveup,
            this.action_movedown,
            '-',
            this.action_enable,
            this.action_disable
        ];
        
        return items;
    },
    
    /**
     * init columns
     */
    initColumns: function() {
        this.gridConfig.columns = [
        {
            id: 'id',
            header: this.app.i18n._("ID"),
            width: 40,
            sortable: false,
            dataIndex: 'id',
            hidden: true
        }, {
            id: 'conditions',
            header: this.app.i18n._("Conditions"),
            width: 200,
            sortable: false,
            dataIndex: 'conditions',
            scope: this,
            renderer: this.conditionsRenderer
        }, {
            id: 'action',
            header: this.app.i18n._("Action"),
            width: 250,
            sortable: false,
            dataIndex: 'action_type',
            scope: this,
            renderer: this.actionRenderer
        }];
    },
    
    /**
     * init layout
     */
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
    },
    
    /**
     * called before store queries for data
     */
    onStoreBeforeload: function(store, options) {
        Tine.Felamimail.sieve.RulesGridPanel.superclass.onStoreBeforeload.call(this, store, options);
        
        options.params.filter = this.account.id;
    },
    
    /**
     * action renderer
     * 
     * @param {Object} type
     * @param {Object} metadata
     * @param {Object} record
     * @return {String}
     */
    actionRenderer: function(type, metadata, record) {
        var types = Tine.Felamimail.sieve.RuleEditDialog.getActionTypes(this.app),
            result = type;
        
        for (i=0; i < types.length; i++) {
            if (types[i][0] == type) {
                result = types[i][1];
            }
        }
        
        if (record.get('action_argument') && record.get('action_argument') != '') {
            result += ' ' + record.get('action_argument');
        }
            
        return Ext.util.Format.htmlEncode(result);
    },

    /**
     * conditions renderer
     * 
     * @param {Object} value
     * @return {String}
     * 
     * TODO show more conditions?
     */
    conditionsRenderer: function(value) {
        var result = '';
        
        // show only first condition
        if (value && value.length > 0) {
            var condition = value[0]; 
            
            // get header/comperator translation
            var filterModel = Tine.Felamimail.sieve.RuleConditionsPanel.getFilterModel(this.app),
                header, 
                comperator, 
                i, 
                found = false;
            for (i=0; i < filterModel.length; i++) {
                if (condition.header == filterModel[i].field) {
                    header = filterModel[i].label;
                    if (condition.header == 'size') {
                        comperator = (condition.comperator == 'over') ? _('is greater than') : _('is less than');
                    } else {
                        comperator = _(condition.comperator);
                    }
                    found = true;
                }
            }
            
            if (found === true) {
                result = header + ' ' + comperator + ' "' + condition.key + '"';
            } else {
                result = String.format(this.app.i18n._('Header "{0}" contains "{1}"'), condition.header, condition.key);
            }
        }
        
        return Ext.util.Format.htmlEncode(result);
    },
    
    /**
     * on update after edit
     * 
     * @param {String} encodedRecordData (json encoded)
     */
    onUpdateRecord: function(encodedRecordData) {
        var newRecord = Tine.Felamimail.rulesBackend.recordReader({responseText: encodedRecordData});
        
        if (! newRecord.id) {
            var lastRecord = null,
                nextId = null;
            do {
                // get next free id
                lastRecord = this.store.getAt(this.store.getCount()-1);
                nextId = (lastRecord) ? (parseInt(lastRecord.id, 10) + 1).toString() : '1';
            } while (this.store.getById(newRecord.id));
            
            newRecord.set('id', nextId);
            newRecord.id = nextId;
        } else {
            this.store.remove(this.store.getById(newRecord.id));
        }
        
        this.store.addSorted(newRecord);
        
        // some eyecandy
        var row = this.getView().getRow(this.store.indexOf(newRecord));
        Ext.fly(row).highlight();
    },
    
    /**
     * generic delete handler
     */
    onDeleteRecords: function(btn, e) {
        var sm = this.grid.getSelectionModel();
        var records = sm.getSelections();
        Ext.each(records, function(record) {
            this.store.remove(record);
        });
    }
});

// file: /home/lkneschke/temp/tine20build/temp/tine20/Felamimail/js/sieve/RulesDialog.js
/*
 * Tine 2.0
 * 
 * @package     Felamimail
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Philipp Schüle <p.schuele@metaways.de>
 * @copyright   Copyright (c) 2010-2011 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: RulesDialog.js 18577 2011-01-13 10:24:15Z p.schuele@metaways.de $
 *
 */
 
Ext.namespace('Tine.Felamimail.sieve');

/**
 * @namespace   Tine.Felamimail
 * @class       Tine.Felamimail.sieve.RulesDialog
 * @extends     Tine.widgets.dialog.EditDialog
 * 
 * <p>Sieve Filter Dialog</p>
 * <p>This dialog is for editing sieve filters (rules).</p>
 * <p>
 * </p>
 * 
 * @author      Philipp Schüle <p.schuele@metaways.de>
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @version     $Id: RulesDialog.js 18577 2011-01-13 10:24:15Z p.schuele@metaways.de $
 * 
 * @param       {Object} config
 * @constructor
 * Create a new RulesDialog
 */
Tine.Felamimail.sieve.RulesDialog = Ext.extend(Tine.widgets.dialog.EditDialog, {

    /**
     * @cfg {Tine.Felamimail.Model.Account}
     */
    account: null,

    /**
     * @private
     */
    windowNamePrefix: 'VacationEditWindow_',
    appName: 'Felamimail',
    loadRecord: false,
    mode: 'local',
    tbarItems: [],
    evalGrants: false,
    
    //private
    initComponent: function(){
        Tine.Felamimail.sieve.RulesDialog.superclass.initComponent.call(this);
        
        this.i18nRecordName = this.app.i18n._('Sieve Filter Rules');
    },
    
    /**
     * overwrite update toolbars function (we don't have record grants yet)
     * 
     * @private
     */
    updateToolbars: Ext.emptyFn,
    
    /**
     * init record to edit
     * -> we don't have a real record here
     */
    initRecord: function() {
        this.onRecordLoad();
    },
    
    /**
     * executed after record got updated from proxy
     * -> we don't have a real record here
     * 
     * @private
     */
    onRecordLoad: function() {
        // interrupt process flow till dialog is rendered
        if (! this.rendered) {
            this.onRecordLoad.defer(250, this);
            return;
        }
        
        var title = String.format(this.app.i18n._('Sieve Filter Rules for {0}'), this.account.get('name'));
        this.window.setTitle(title);
        
        this.loadMask.hide();
    },
        
    /**
     * returns dialog
     * 
     * NOTE: when this method gets called, all initalisation is done.
     * 
     * @return {Object}
     * @private
     * 
     */
    getFormItems: function() {
        this.rulesGrid = new Tine.Felamimail.sieve.RulesGridPanel({
            account: this.account
        }); 
        
        return [this.rulesGrid];
    },
    
    /**
     * apply changes handler (get rules and send them to saveRules)
     */
    onApplyChanges: function(button, event, closeWindow) {
        var rules = [];
        this.rulesGrid.store.each(function(record) {
            rules.push(record.data);
        });
        
        this.loadMask.show();
        Tine.Felamimail.rulesBackend.saveRules(this.account.id, rules, {
            scope: this,
            success: function(record) {
                if (closeWindow) {
                    this.purgeListeners();
                    this.window.close();
                }
            },
            failure: Tine.Felamimail.handleRequestException.createSequence(function() {
                this.loadMask.hide();
            }, this),
            timeout: 150000 // 3 minutes
        });
    }
});

/**
 * Felamimail Edit Popup
 * 
 * @param   {Object} config
 * @return  {Ext.ux.Window}
 */
Tine.Felamimail.sieve.RulesDialog.openWindow = function (config) {
    var window = Tine.WindowFactory.getWindow({
        width: 800,
        height: 400,
        name: Tine.Felamimail.sieve.RulesDialog.prototype.windowNamePrefix + Ext.id(),
        contentPanelConstructor: 'Tine.Felamimail.sieve.RulesDialog',
        contentPanelConstructorConfig: config
    });
    return window;
};

// file: /home/lkneschke/temp/tine20build/temp/tine20/Felamimail/js/sieve/RuleConditionsPanel.js
/*
 * Tine 2.0
 * 
 * @package     Felamimail
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Philipp Schuele <p.schuele@metaways.de>
 * @copyright   Copyright (c) 2010 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: RuleConditionsPanel.js 18527 2011-01-11 16:37:03Z p.schuele@metaways.de $
 *
 */
 
Ext.namespace('Tine.Felamimail.sieve');

/**
 * @namespace   Tine.Felamimail.sieve
 * @class       Tine.Felamimail.sieve.RuleConditionsPanel
 * @extends     Tine.widgets.grid.FilterToolbar
 * 
 * <p>Sieve Filter Conditions Panel</p>
 * <p>
 * mapping when getting filter values:
 *  field       -> test_header or 'size'
 *  operator    -> comperator
 *  value       -> key
 * </p>
 * <p>
 * </p>
 * 
 * @author      Philipp Schuele <p.schuele@metaways.de>
 * @copyright   Copyright (c) 2010 Metaways Infosystems GmbH (http://www.metaways.de)
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @version     $Id: RuleConditionsPanel.js 18527 2011-01-11 16:37:03Z p.schuele@metaways.de $
 * 
 * @param       {Object} config
 * @constructor
 * Create a new RuleConditionsPanel
 */
Tine.Felamimail.sieve.RuleConditionsPanel = Ext.extend(Tine.widgets.grid.FilterToolbar, {
    
    defaultFilter: 'from',
    allowSaving: false,
    showSearchButton: false,
    filterFieldWidth: 160,
    
    // unused fn
    onFiltertrigger: Ext.emptyFn,
    
    initComponent: function() {
        this.app = Tine.Tinebase.appMgr.get('Felamimail');
        this.rowPrefix = '';
        
        this.filterModels = Tine.Felamimail.sieve.RuleConditionsPanel.getFilterModel(this.app);
        
        this.supr().initComponent.call(this);
    },
    
    /**
     * gets filter data (use getValue() if we don't have a store/plugins)
     * 
     * @return {Array} of filter records
     */
    getAllFilterData: function() {
        return this.getValue();
    }
});

/**
 * get rule conditions for filter model and condition renderer
 * 
 * @param {} app
 * @return {Array}
 */
Tine.Felamimail.sieve.RuleConditionsPanel.getFilterModel = function(app) {
    return [
        {label: app.i18n._('From'),     field: 'from',     operators: ['contains'], emptyText: 'test@example.org'},
        {label: app.i18n._('To'),       field: 'to',       operators: ['contains'], emptyText: 'test@example.org'},
        {label: app.i18n._('Subject'),  field: 'subject',  operators: ['contains'], emptyText: app.i18n._('Subject')},
        {label: app.i18n._('Size'),     field: 'size',     operators: ['greater', 'less'], valueType: 'number', defaultOperator: 'greater'},
        {label: app.i18n._('Header contains'),   field: 'header',   operators: ['freeform'], defaultOperator: 'freeform', 
            emptyTextOperator: app.i18n._('Header name'), emptyText: app.i18n._('Header value')}
    ];
};

// file: /home/lkneschke/temp/tine20build/temp/tine20/Felamimail/js/TreeContextMenu.js
/*
 * Tine 2.0
 * 
 * @package     Felamimail
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Philipp Schüle <p.schuele@metaways.de>
 * @copyright   Copyright (c) 2010-2011 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: TreeContextMenu.js 18973 2011-02-01 11:36:17Z p.schuele@metaways.de $
 *
 */
 
Ext.namespace('Tine.Felamimail');

/**
 * get felamimail tree panel context menus
 * this is used in Tine.Felamimail.TreePanel (with createDelegate)
 * 
 * TODO add reload account again
 * TODO add other actions again?
 * TODO use Ext.apply to get this
 */
Tine.Felamimail.setTreeContextMenus = function() {
        
    // define additional actions
    
    // inactive
    /*
    var updateCacheConfigAction = {
        text: String.format(_('Update {0} Cache'), this.app.i18n._('Message')),
        iconCls: 'action_update_cache',
        scope: this,
        handler: function() {
            Ext.Ajax.request({
                params: {
                    method: 'Felamimail.refreshFolder',
                    folderId: this.ctxNode.attributes.folder_id
                },
                scope: this,
                success: function(_result, _request){
                    if (this.ctxNode.id == this.getSelectionModel().getSelectedNode().id) {
                        // update message cache
                        //this.updateFolderStatus([this.ctxNode]);
                    }
                }
            });
        }
    };
    */

    var emptyFolderAction = {
        text: this.app.i18n._('Empty Folder'),
        iconCls: 'action_folder_emptytrash',
        scope: this,
        handler: function() {
            this.ctxNode.getUI().addClass("x-tree-node-loading");
            var folderId = this.ctxNode.attributes.folder_id;
            Ext.Ajax.request({
                params: {
                    method: 'Felamimail.emptyFolder',
                    folderId: folderId
                },
                scope: this,
                success: function(result, request){
                    var selectedNode = this.getSelectionModel().getSelectedNode(),
                        isSelectedNode = (selectedNode && this.ctxNode.id == selectedNode.id);
                        
                    if (isSelectedNode) {
                        var newRecord = Tine.Felamimail.folderBackend.recordReader(result);
                        this.app.getFolderStore().updateFolder(newRecord);
                    } else {
                        var folder = this.app.getFolderStore().getById(folderId);
                        folder.set('cache_unreadcount', 0);
                    }
                    this.ctxNode.getUI().removeClass("x-tree-node-loading");
                },
                failure: function() {
                    this.ctxNode.getUI().removeClass("x-tree-node-loading");
                },
                timeout: 120000 // 2 minutes
            });
        }
    };
    
    // we need this for adding folders to account (root level)
    var addFolderToRootAction = {
        text: this.app.i18n._('Add Folder'),
        iconCls: 'action_add',
        scope: this,
        disabled: true,
        handler: function() {
            Ext.MessageBox.prompt(String.format(_('New {0}'), this.app.i18n._('Folder')), String.format(_('Please enter the name of the new {0}:'), this.app.i18n._('Folder')), function(_btn, _text) {
                if( this.ctxNode && _btn == 'ok') {
                    if (! _text) {
                        Ext.Msg.alert(String.format(_('No {0} added'), this.app.i18n._('Folder')), String.format(_('You have to supply a {0} name!'), this.app.i18n._('Folder')));
                        return;
                    }
                    Ext.MessageBox.wait(_('Please wait'), String.format(_('Creating {0}...' ), this.app.i18n._('Folder')));
                    var parentNode = this.ctxNode;
                    
                    var params = {
                        method: 'Felamimail.addFolder',
                        name: _text
                    };
                    
                    params.parent = '';
                    params.accountId = parentNode.id;
                    
                    Ext.Ajax.request({
                        params: params,
                        scope: this,
                        success: function(_result, _request){
                            var nodeData = Ext.util.JSON.decode(_result.responseText);
                            var newNode = this.loader.createNode(nodeData);
                            parentNode.appendChild(newNode);
                            this.fireEvent('containeradd', nodeData);
                            Ext.MessageBox.hide();
                        }
                    });
                    
                }
            }, this);
        }
    };
    
    var editAccountAction = {
        text: this.app.i18n._('Edit Account'),
        iconCls: 'FelamimailIconCls',
        scope: this,
        disabled: ! Tine.Tinebase.common.hasRight('manage_accounts', 'Felamimail'),
        handler: function() {
            var record = this.accountStore.getById(this.ctxNode.attributes.account_id);
            var popupWindow = Tine.Felamimail.AccountEditDialog.openWindow({
                record: record,
                listeners: {
                    scope: this,
                    'update': function(record) {
                        var account = new Tine.Felamimail.Model.Account(Ext.util.JSON.decode(record));
                        
                        // update tree node + store
                        this.ctxNode.setText(account.get('name'));
                        this.accountStore.reload();
                        
                        // reload tree node + remove all folders of this account from store ?
                        this.folderStore.resetQueryAndRemoveRecords('parent_path', '/' + this.ctxNode.attributes.account_id);
                        this.ctxNode.reload(function(callback) {
                        });
                    }
                }
            });
        }
    };

    var editVacationAction = {
        text: this.app.i18n._('Set Vacation Message'),
        iconCls: 'action_email_replyAll',
        scope: this,
        handler: function() {
            var accountId = this.ctxNode.attributes.account_id;
            var account = this.accountStore.getById(accountId);
            var record = new Tine.Felamimail.Model.Vacation({id: accountId}, accountId);
            
            var popupWindow = Tine.Felamimail.sieve.VacationEditDialog.openWindow({
                account: account,
                record: record
            });
        }
    };
    
    var editRulesAction = {
        text: this.app.i18n._('Set Filter Rules'),
        iconCls: 'action_email_forward',
        scope: this,
        handler: function() {
            var accountId = this.ctxNode.attributes.account_id;
            var account = this.accountStore.getById(accountId);
            //var record = new Tine.Felamimail.Model.Vacation({id: accountId}, accountId);
            
            var popupWindow = Tine.Felamimail.sieve.RulesDialog.openWindow({
                account: account
                //record: record
            });
        }
    };

    // inactive
    /*
    var reloadFolderAction = {
        text: String.format(_('Reload {0}'), this.app.i18n._('Folder')),
        iconCls: 'x-tbar-loading',
        scope: this,
        handler: function() {
            if (this.ctxNode) {
                // call update folder status from felamimail app
                //this.updateFolderStatus([this.ctxNode]);
            }
        }
    };
    */
    
    var markFolderSeenAction = {
        text: this.app.i18n._('Mark Folder as read'),
        iconCls: 'action_mark_read',
        scope: this,
        handler: function() {
            if (this.ctxNode) {
                var folderId = this.ctxNode.id,
                    filter = [{
                        field: 'folder_id',
                        operator: 'equals',
                        value: folderId
                    }, {
                        field: 'flags',
                        operator: 'notin',
                        value: ['\\Seen']
                    }
                ];
                
                var selectedNode = this.getSelectionModel().getSelectedNode(),
                    isSelectedNode = (selectedNode && this.ctxNode.id == selectedNode.id);
                
                Tine.Felamimail.messageBackend.addFlags(filter, '\\Seen', {
                    callback: function() {
                        this.app = Tine.Tinebase.appMgr.get('Felamimail');
                        var folder = this.app.getFolderStore().getById(folderId);
                        folder.set('cache_unreadcount', 0);
                        if (isSelectedNode) {
                            this.app.getMainScreen().getCenterPanel().loadGridData({
                                removeStrategy: 'keepBuffered'
                            });
                        }
                    }
                });
            }
        }
    };

    var updateFolderCacheAction = {
        text: this.app.i18n._('Update Folder List'),
        iconCls: 'action_update_cache',
        scope: this,
        handler: function() {
            if (this.ctxNode) {
                var folder = this.app.getFolderStore().getById(this.ctxNode.id),
                    account = folder ? Tine.Felamimail.loadAccountStore().getById(folder.get('account_id')) :
                                       Tine.Felamimail.loadAccountStore().getById(this.ctxNode.id);
                this.ctxNode.getUI().addClass("x-tree-node-loading");
                // call update folder cache
                Ext.Ajax.request({
                    params: {
                        method: 'Felamimail.updateFolderCache',
                        accountId: account.id,
                        folderName: folder ? folder.get('globalname') : ''
                    },
                    scope: this,
                    success: function(result, request){
                        this.ctxNode.getUI().removeClass("x-tree-node-loading");
                        // clear query to query server again and reload subfolders
                        this.folderStore.resetQueryAndRemoveRecords('parent_path', (folder ? folder.get('path') : '/') + account.id);
                        this.ctxNode.reload(function(callback) {
                            this.selectInbox(account);
                        }, this);
                    },
                    failure: function() {
                        this.ctxNode.getUI().removeClass("x-tree-node-loading");
                    }
                });
            }
        }
    };
    
    // mutual config options
    var config = {
        nodeName: this.app.i18n.n_('Folder', 'Folders', 1),
        scope: this,
        backend: 'Felamimail',
        backendModel: 'Folder'
    };
    
    // system folder ctx menu
    config.actions = [markFolderSeenAction, 'add'];
    this.contextMenuSystemFolder = Tine.widgets.tree.ContextMenu.getMenu(config);
    
    // user folder ctx menu
    config.actions = [markFolderSeenAction, 'add', 'rename', 'delete'];
    this.contextMenuUserFolder = Tine.widgets.tree.ContextMenu.getMenu(config);
    
    // trash ctx menu
    config.actions = [markFolderSeenAction, 'add', emptyFolderAction];
    this.contextMenuTrash = Tine.widgets.tree.ContextMenu.getMenu(config);
    
    // account ctx menu
    this.contextMenuAccount = Tine.widgets.tree.ContextMenu.getMenu({
        nodeName: this.app.i18n.n_('Account', 'Accounts', 1),
        actions: [addFolderToRootAction, updateFolderCacheAction, editVacationAction, editRulesAction, editAccountAction, 'delete'],
        scope: this,
        backend: 'Felamimail',
        backendModel: 'Account'
    });
    
    // context menu for unselectable folders (like public/shared namespace)
    config.actions = ['add'];
    this.unselectableFolder = Tine.widgets.tree.ContextMenu.getMenu(config);
};

// file: /home/lkneschke/temp/tine20build/temp/tine20/Felamimail/js/TreeLoader.js
/*
 * Tine 2.0
 * 
 * @package     Felamimail
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Philipp Schüle <p.schuele@metaways.de>
 * @copyright   Copyright (c) 2009-2011 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: TreeLoader.js 18426 2011-01-07 14:31:58Z p.schuele@metaways.de $
 */
 
/**
 * @namespace   Tine.Felamimail
 * @class       Tine.Felamimail.TreeLoader
 * @extends     Tine.widgets.tree.Loader
 * 
 * <p>Felamimail Account/Folder Tree Loader</p>
 * <p>
 * </p>
 * 
 * @author      Philipp Schuele <p.schuele@metaways.de>
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @version     $Id: TreeLoader.js 18426 2011-01-07 14:31:58Z p.schuele@metaways.de $
 * 
 * @param       {Object} config
 * @constructor
 * Create a new Tine.Felamimail.TreeLoader
 * 
 */
Tine.Felamimail.TreeLoader = Ext.extend(Tine.widgets.tree.Loader, {
    
    /**
     * request data
     * 
     * @param {Ext.tree.TreeNode} node
     * @param {Function} callback Function to call after the node has been loaded. The
     * function is passed the TreeNode which was requested to be loaded.
     * @param (Object) scope The cope (this reference) in which the callback is executed.
     * defaults to the loaded TreeNode.
     */
    requestData : function(node, callback, scope){
        
        if(this.fireEvent("beforeload", this, node, callback) !== false) {
            var fstore = Tine.Tinebase.appMgr.get('Felamimail').getFolderStore(),
                folder = fstore.getById(node.attributes.folder_id),
                path = (folder) ? folder.get('path') : node.attributes.path;
            
            // we need to call doQuery fn from store to transparently do async request
            fstore.asyncQuery('parent_path', path, function(node, callback, scope, data) {
                if (data) {
                    node.beginUpdate();
                    data.each(function(folderRecord) {
                        var n = this.createNode(folderRecord.copy().data);
                        if (n) {
                            node.appendChild(n);
                        }
                    }, this);
                    node.endUpdate();
                }
                this.runCallback(callback, scope || node, [node]);
            }, [node, callback, scope], this, fstore);
            
        } else {
            // if the load is cancelled, make sure we notify
            // the node that we are done
            this.runCallback(callback, scope || node, []);
        }
    },
    
    /**
     * inspectCreateNode
     * 
     * @private
     */
    inspectCreateNode: function(attr) {
        var account = Tine.Felamimail.loadAccountStore().getById(attr.account_id);
        
        // NOTE cweiss 2010-06-15 this has to be precomputed on server side!
        attr.has_children = (account && account.get('has_children_support')) ? attr.has_children : true;
        if (attr.has_children == "0") {
            attr.has_children = false;
        }
        
        Ext.apply(attr, {
    		leaf: !attr.has_children,
            expandable: attr.has_children,
            cls: 'x-tree-node-collapsed',
            folder_id: attr.id,
    		folderNode: true,
            allowDrop: true,
            text: this.app.i18n._hidden(attr.localname)
    	});
        
        
        // show standard folders icons 
        if (account) {
            if (account.get('trash_folder') == attr.globalname) {
                if (attr.cache_totalcount > 0) {
                    attr.cls = 'felamimail-node-trash-full';
                } else {
                    attr.cls = 'felamimail-node-trash';
                }
            }
            if (account.get('sent_folder') == attr.globalname) {
                attr.cls = 'felamimail-node-sent';
            }
        }
        if ('INBOX' == attr.globalname) {
            attr.cls = 'felamimail-node-inbox';
        }
        if ('Drafts' == attr.globalname) {
            attr.cls = 'felamimail-node-drafts';
        }
        if ('Templates' == attr.globalname) {
            attr.cls = 'felamimail-node-templates';
        }
        if ('Junk' == attr.globalname) {
            attr.cls = 'felamimail-node-junk';
        }
    }
});

// file: /home/lkneschke/temp/tine20build/temp/tine20/Felamimail/js/TreePanel.js
/*
 * Tine 2.0
 * 
 * @package     Felamimail
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Philipp Schüle <p.schuele@metaways.de>
 * @copyright   Copyright (c) 2009-2011 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: TreePanel.js 18973 2011-02-01 11:36:17Z p.schuele@metaways.de $
 *
 */
 
Ext.namespace('Tine.Felamimail');

/**
 * @namespace   Tine.Felamimail
 * @class       Tine.Felamimail.FilterPanel
 * @extends     Tine.widgets.persistentfilter.PickerPanel
 * 
 * <p>Felamimail Favorites Panel</p>
 * 
 * @author      Philipp Schüle <p.schuele@metaways.de>
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @version     $Id: TreePanel.js 18973 2011-02-01 11:36:17Z p.schuele@metaways.de $
 * 
 * @param       {Object} config
 * @constructor
 * Create a new Tine.Felamimail.FilterPanel
 */
Tine.Felamimail.FilterPanel = Ext.extend(Tine.widgets.persistentfilter.PickerPanel, {
    filterModel: 'Felamimail_Model_MessageFilter'
});

/**
 * @namespace   Tine.Felamimail
 * @class       Tine.Felamimail.TreePanel
 * @extends     Ext.tree.TreePanel
 * 
 * <p>Account/Folder Tree Panel</p>
 * <p>Tree of Accounts with folders</p>
 * <pre>
 * low priority:
 * TODO         make inbox/drafts/templates configurable in account
 * TODO         save tree state? @see http://examples.extjs.eu/?ex=treestate
 * TODO         disable delete action in account ctx menu if user has no manage_accounts right
 * </pre>
 * 
 * @author      Philipp Schuele <p.schuele@metaways.de>
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @version     $Id: TreePanel.js 18973 2011-02-01 11:36:17Z p.schuele@metaways.de $
 * 
 * @param       {Object} config
 * @constructor
 * Create a new Tine.Felamimail.TreePanel
 * 
 */
Tine.Felamimail.TreePanel = function(config) {
    Ext.apply(this, config);
    
    this.addEvents(
        /**
         * @event containeradd
         * Fires when a folder was added
         * @param {folder} the new folder
         */
        'containeradd',
        /**
         * @event containerdelete
         * Fires when a folder got deleted
         * @param {folder} the deleted folder
         */
        'containerdelete',
        /**
         * @event containerrename
         * Fires when a folder got renamed
         * @param {folder} the renamed folder
         */
        'containerrename'
    );
        
    Tine.Felamimail.TreePanel.superclass.constructor.call(this);
};

Ext.extend(Tine.Felamimail.TreePanel, Ext.tree.TreePanel, {
	
    /**
     * @property app
     * @type Tine.Felamimail.Application
     */
    app: null,
    
    /**
     * @property accountStore
     * @type Ext.data.JsonStore
     */
    accountStore: null,
    
    /**
     * @type Ext.data.JsonStore
     */
    folderStore: null,
    
    /**
     * @cfg {String} containerName
     */
    containerName: 'Folder',
    
    /**
     * TreePanel config
     * @private
     */
	rootVisible: false,
    
    /**
     * drag n drop
     */ 
    enableDrop: true,
    ddGroup: 'mailToTreeDDGroup',
    
    /**
     * @cfg
     */
    border: false,
    recordClass: Tine.Felamimail.Model.Account,
    filterMode: 'filterToolbar',
	
    /**
     * is needed by Tine.widgets.mainscreen.WestPanel to fake container tree panel
     */
    selectContainerPath: Ext.emptyFn,
    
    /**
     * init
     * @private
     */
    initComponent: function() {
        // get folder store
        this.folderStore = Tine.Tinebase.appMgr.get('Felamimail').getFolderStore();
    	
        // init tree loader
        this.loader = new Tine.Felamimail.TreeLoader({
            folderStore: this.folderStore,
            app: this.app
        });

        // set the root node
        this.root = new Ext.tree.TreeNode({
            text: 'default',
            draggable: false,
            allowDrop: false,
            expanded: true,
            leaf: false,
            id: 'root'
        });
        
        // add account nodes
        this.initAccounts();
        
        // init drop zone
        this.dropConfig = {
            ddGroup: this.ddGroup || 'TreeDD',
            appendOnly: this.ddAppendOnly === true,
            notifyEnter : function() {this.isDropSensitive = true;}.createDelegate(this),
            notifyOut : function() {this.isDropSensitive = false;}.createDelegate(this),
            onNodeOver : function(n, dd, e, data) {
                var node = n.node;
                
                // auto node expand check (only for non-account nodes)
                if(!this.expandProcId && node.attributes.allowDrop && node.hasChildNodes() && !node.isExpanded()){
                    this.queueExpand(node);
                } else if (! node.attributes.allowDrop) {
                    this.cancelExpand();
                }
                return node.attributes.allowDrop ? 'tinebase-tree-drop-move' : false;
            },
            isValidDropPoint: function(n, dd, e, data){
                return n.node.attributes.allowDrop;
            }
        }
        
        // init selection model (multiselect)
        this.selModel = new Ext.tree.MultiSelectionModel({});
        
        // init context menu TODO use Ext.apply
        var initCtxMenu = Tine.Felamimail.setTreeContextMenus.createDelegate(this);
        initCtxMenu();
        
    	// add listeners
        this.on('beforeclick', this.onBeforeClick, this);
        this.on('click', this.onClick, this);
        this.on('contextmenu', this.onContextMenu, this);
        this.on('beforenodedrop', this.onBeforenodedrop, this);
        this.on('append', this.onAppend, this);
        this.on('containeradd', this.onFolderAdd, this);
        this.on('containerrename', this.onFolderRename, this);
        this.on('containerdelete', this.onFolderDelete, this);
        this.selModel.on('selectionchange', this.onSelectionChange, this);
        this.folderStore.on('update', this.onUpdateFolderStore, this);
        
        // call parent::initComponent
        Tine.Felamimail.TreePanel.superclass.initComponent.call(this);
	},
    
    /**
     * add accounts from registry as nodes to root node
     * @private
     */
    initAccounts: function() {
        this.accountStore = Tine.Felamimail.loadAccountStore();
        this.accountStore.each(this.addAccount, this);
        this.accountStore.on('update', this.onAccountUpdate, this);
    },
    
    /**
     * init extra tool tips
     */
    initToolTips: function() {
        this.folderTip = new Ext.ToolTip({
            target: this.getEl(),
            delegate: 'a.x-tree-node-anchor',
            renderTo: document.body,
            listeners: {beforeshow: this.updateFolderTip.createDelegate(this)}
        });
        
        this.folderProgressTip = new Ext.ToolTip({
            target: this.getEl(),
            delegate: '.felamimail-node-statusbox-progress',
            renderTo: document.body,
            listeners: {beforeshow: this.updateProgressTip.createDelegate(this)}
        });
        
        this.folderUnreadTip = new Ext.ToolTip({
            target: this.getEl(),
            delegate: '.felamimail-node-statusbox-unread',
            renderTo: document.body,
            listeners: {beforeshow: this.updateUnreadTip.createDelegate(this)}
        });
    },
    
    /**
     * called when tree selection changes
     * 
     * @param {} sm
     * @param {} node
     */
    onSelectionChange: function(sm, nodes) {
        if (this.filterMode == 'gridFilter' && this.filterPlugin) {
            this.filterPlugin.onFilterChange();
        }
        if (this.filterMode == 'filterToolbar' && this.filterPlugin) {
            
            // get filterToolbar
            var ftb = this.filterPlugin.getGridPanel().filterToolbar;
            if (! ftb.rendered) {
                this.onSelectionChange.defer(150, this, [sm, nodes]);
                return;
            }
            
            // remove path filter
            ftb.supressEvents = true;
            ftb.filterStore.each(function(filter) {
                var field = filter.get('field');
                if (field === 'path') {
                    ftb.deleteFilter(filter);
                }
            }, this);
            ftb.supressEvents = false;
            
            // set ftb filters according to tree selection
            var filter = this.getFilterPlugin().getFilter();
            ftb.addFilter(new ftb.record(filter));
        
            ftb.onFiltertrigger();
            
            // finally select the selected node, as filtertrigger clears all selections
            sm.suspendEvents();
            Ext.each(nodes, function(node) {
                sm.select(node, Ext.EventObject, true);
            }, this);
            sm.resumeEvents();
        }
    },
    
    /**
     * called on filtertrigger of filter toolbar
     * clears selection silently
     */
    onFilterChange: function() {
        var sm = this.getSelectionModel();
        
        sm.suspendEvents();
        sm.clearSelections();
        sm.resumeEvents();
    },    
    
   /**
     * returns a filter plugin to be used in a grid
     * @private
     */
    getFilterPlugin: function() {
        if (!this.filterPlugin) {
            this.filterPlugin = new Tine.widgets.tree.FilterPlugin({
                treePanel: this,
                field: 'path',
                nodeAttributeField: 'path',
                singleNodeOperator: 'in'
            });
        }
        
        return this.filterPlugin;
    },
    
    /**
     * @private
     * 
     * expand default account and select INBOX
     */
    afterRender: function() {
        Tine.Felamimail.TreePanel.superclass.afterRender.call(this);
        this.initToolTips();
        this.selectInbox();
        
        if (this.filterMode == 'filterToolbar' && this.filterPlugin) {
            this.filterPlugin.getGridPanel().filterToolbar.on('change', this.onFilterChange, this);
        }
    },
    
    /**
     * select inbox of account
     * 
     * @param {Record} account
     */
    selectInbox: function(account) {
        var accountId = (account) ? account.id : Tine.Felamimail.registry.get('preferences').get('defaultEmailAccount');
        
        this.expandPath('/root/' + accountId + '/', null, function(success, parentNode) {
            Ext.each(parentNode.childNodes, function(node) {
                if (Ext.util.Format.lowercase(node.attributes.localname) == 'inbox') {
                    node.select();
                    return false;
                }
            }, this);
        });
    },
    
    /**
     * called when an account record updates
     * 
     * @param {Ext.data.JsonStore} store
     * @param {Tine.Felamimail.Model.Account} record
     * @param {String} action
     */
    onAccountUpdate: function(store, record, action) {
        if (action === Ext.data.Record.EDIT) {
            this.updateAccountStatus(record);
        }
    },
    
    /**
     * on append node
     * 
     * render status box
     * 
     * @param {Tine.Felamimail.TreePanel} tree
     * @param {Ext.Tree.TreeNode} node
     * @param {Ext.Tree.TreeNode} appendedNode
     * @param {Number} index
     */
    onAppend: function(tree, node, appendedNode, index) {
        appendedNode.ui.render = appendedNode.ui.render.createSequence(function() {
            var app = Tine.Tinebase.appMgr.get('Felamimail'),
                folder = app.getFolderStore().getById(appendedNode.id);
                
            if (folder) {
                app.getMainScreen().getTreePanel().addStatusboxesToNodeUi(this);
                app.getMainScreen().getTreePanel().updateFolderStatus(folder);
            }
        }, appendedNode.ui);
    },
    
    /**
     * add status boxes
     * 
     * @param {Object} nodeUi
     */
    addStatusboxesToNodeUi: function(nodeUi) {
        Ext.DomHelper.insertAfter(nodeUi.elNode.lastChild, {tag: 'span', 'class': 'felamimail-node-statusbox', cn:[
            {'tag': 'img', 'src': Ext.BLANK_IMAGE_URL, 'class': 'felamimail-node-statusbox-progress'},
            {'tag': 'span', 'class': 'felamimail-node-statusbox-unread'}
        ]});
    },
    
    /**
     * on before click handler
     * - accounts are not clickable because fetching all messages of account is too expensive
     * - skip event for folders that are not selectable
     * 
     * @param {Ext.tree.AsyncTreeNode} node
     */
    onBeforeClick: function(node) {
        if (Tine.Felamimail.loadAccountStore().getById(node.id) || ! this.app.getFolderStore().getById(node.id).get('is_selectable')) {
            return false;
        }
    },
    
    /**
     * on click handler
     * 
     * - expand node
     * - update filter toolbar of grid
     * - start check mails delayed task
     * 
     * @param {Ext.tree.AsyncTreeNode} node
     * @private
     */
    onClick: function(node) {
        if (node.expandable) {
            node.expand();
        }
        
        if (node.id && node.id != '/' && node.attributes.globalname != '') {
            var folder = this.app.getFolderStore().getById(node.id);
            this.app.checkMailsDelayedTask.delay(0);
        }
    },
    
    /**
     * show context menu for folder tree
     * 
     * items:
     * - create folder
     * - rename folder
     * - delete folder
     * - ...
     * 
     * @param {} node
     * @param {} event
     * @private
     */
    onContextMenu: function(node, event) {
        this.ctxNode = node;
        
        var folder = this.app.getFolderStore().getById(node.id),
            account = folder ? Tine.Felamimail.loadAccountStore().getById(folder.get('account_id')) :
                               Tine.Felamimail.loadAccountStore().getById(node.id);
        
        if (! folder) {
            // edit/remove account
            if (account.get('ns_personal') !== 'default') {
                this.contextMenuAccount.items.each(function(item) {
                    // check account personal namespace -> disable 'add folder' if namespace is other than root 
                    if (item.iconCls == 'action_add') {
                        item.setDisabled(account.get('ns_personal') != '');
                    }
                    // disable filter rules/vacation if no sieve hostname is set
                    if (item.iconCls == 'action_email_replyAll' || item.iconCls == 'action_email_forward') {
                        item.setDisabled(account.get('sieve_hostname') == null || account.get('sieve_hostname') == '');
                    }
                });
                
                this.contextMenuAccount.showAt(event.getXY());
            }
        } else {
            if (folder.get('globalname') === account.get('trash_folder') || folder.get('globalname').match(/junk/i)) {
                this.contextMenuTrash.showAt(event.getXY());
            } else if (! folder.get('is_selectable')){
                this.unselectableFolder.showAt(event.getXY());
            } else if (folder.get('system_folder')) {
                this.contextMenuSystemFolder.showAt(event.getXY());
            } else {
                this.contextMenuUserFolder.showAt(event.getXY());
            }
        }
    },
    
    /**
     * mail(s) got dropped on node
     * 
     * @param {Object} dropEvent
     * @private
     */
    onBeforenodedrop: function(dropEvent) {
        var targetFolderId = dropEvent.target.attributes.folder_id,
            targetFolder = this.app.getFolderStore().getById(targetFolderId);
                
        this.app.getMainScreen().getCenterPanel().moveSelectedMessages(targetFolder, false);
        return true;
    },
    
    /**
     * cleanup on destruction
     */
    onDestroy: function() {
        this.folderStore.un('update', this.onUpdateFolderStore, this);
    },
    
    /**
     * folder store gets updated -> update tree nodes
     * 
     * @param {Tine.Felamimail.FolderStore} store
     * @param {Tine.Felamimail.Model.Folder} record
     * @param {String} operation
     */
    onUpdateFolderStore: function(store, record, operation) {
        if (operation === Ext.data.Record.EDIT) {
            this.updateFolderStatus(record);
        }
    },
    
    /**
     * add new folder to the store
     * 
     * @param {Object} folderData
     */
    onFolderAdd: function(folderData) {
        var recordData = Ext.copyTo({}, folderData, Tine.Felamimail.Model.Folder.getFieldNames());
        var newRecord = Tine.Felamimail.folderBackend.recordReader({responseText: Ext.util.JSON.encode(recordData)});
        
        Tine.log.debug('Added new folder:' + newRecord.get('globalname'));
        
        this.folderStore.add([newRecord]);

        // update paths in node
        var appendedNode = this.getNodeById(newRecord.id);
        appendedNode.attributes.path = newRecord.get('path');
        appendedNode.attributes.parent_path = newRecord.get('parent_path');
        
        // add unreadcount/progress/tooltip
        this.addStatusboxesToNodeUi(appendedNode.ui);
        this.updateFolderStatus(newRecord);
    },

    /**
     * rename folder in the store
     * 
     * @param {Object} folderData
     */
    onFolderRename: function(folderData) {
        var record = this.folderStore.getById(folderData.id);
        record.set('globalname', folderData.globalname);
        record.set('localname', folderData.localname);
        
        Tine.log.debug('Renamed folder:' + record.get('globalname'));
    },
        
    /**
     * remove deleted folder from the store
     * 
     * @param {Object} folderData
     */
    onFolderDelete: function(folderData) {
        this.folderStore.remove(this.folderStore.getById(folderData.id));
    },
    
    /**
     * returns tree node id the given el is child of
     * 
     * @param  {HTMLElement} el
     * @return {String}
     */
    getElsParentsNodeId: function(el) {
        return Ext.fly(el, '_treeEvents').up('div[class^=x-tree-node-el]').getAttribute('tree-node-id', 'ext');
    },
    
    /**
     * updates account status icon in this tree
     * 
     * @param {Tine.Felamimail.Model.Account} account
     */
    updateAccountStatus: function(account) {
        var imapStatus = account.get('imap_status'),
            node = this.getNodeById(account.id),
            ui = node ? node.getUI() : null,
            nodeEl = ui ? ui.getEl() : null;
            
        Tine.log.info('Account ' + account.get('name') + ' updated with imap_status: ' + imapStatus);
        if (node && node.ui.rendered) {
            var statusEl = Ext.get(Ext.DomQuery.selectNode('span[class=felamimail-node-accountfailure]', nodeEl));
            if (! statusEl) {
                // create statusEl on the fly
                statusEl = Ext.DomHelper.insertAfter(ui.elNode.lastChild, {'tag': 'span', 'class': 'felamimail-node-accountfailure'}, true);
                statusEl.on('click', function() {
                    Tine.Felamimail.folderBackend.handleRequestException(account.getLastIMAPException());
                }, this);
            }
            
            statusEl.setVisible(imapStatus === 'failure');
        }
    },
    
    /**
     * updates folder status icons/info in this tree
     * 
     * @param {Tine.Felamimail.Model.Folder} folder
     */
    updateFolderStatus: function(folder) {
        var unreadcount = folder.get('cache_unreadcount'),
            progress    = Math.round(folder.get('cache_job_actions_done') / folder.get('cache_job_actions_estimate') * 10) * 10,
            node        = this.getNodeById(folder.id),
            ui = node ? node.getUI() : null,
            nodeEl = ui ? ui.getEl() : null,
            cacheStatus = folder.get('cache_status'),
            lastCacheStatus = folder.modified ? folder.modified.cache_status : null,
            isSelected = folder.isCurrentSelection();
        
        if (node && node.ui.rendered) {
            var domNode = Ext.DomQuery.selectNode('span[class=felamimail-node-statusbox-unread]', nodeEl);
            if (domNode) {
                
                // update unreadcount + visibity
                Ext.fly(domNode).update(unreadcount).setVisible(unreadcount > 0);
                ui[unreadcount === 0 ? 'removeClass' : 'addClass']('felamimail-node-unread');
                
                // update progress
                var progressEl = Ext.get(Ext.DomQuery.selectNode('img[class^=felamimail-node-statusbox-progress]', nodeEl));
                progressEl.removeClass(['felamimail-node-statusbox-progress-pie', 'felamimail-node-statusbox-progress-loading']);
                if (! Ext.isNumber(progress)) {
                    progressEl.setStyle('background-position', 0 + 'px');
                    progressEl.addClass('felamimail-node-statusbox-progress-loading');
                } else {
                    progressEl.setStyle('background-position', progress + '%');
                    progressEl.addClass('felamimail-node-statusbox-progress-pie');
                }
                progressEl.setVisible(isSelected && cacheStatus !== 'complete' && cacheStatus !== 'disconnect' && progress !== 100 && lastCacheStatus !== 'complete');
            }
        }
    },
    
    /**
     * updates the given tip
     * @param {Ext.Tooltip} tip
     */
    updateFolderTip: function(tip) {
        var folderId = this.getElsParentsNodeId(tip.triggerElement),
            folder = this.app.getFolderStore().getById(folderId),
            account = Tine.Felamimail.loadAccountStore().getById(folderId);
            
        if (folder && !this.isDropSensitive) {
            var info = [
                '<table>',
                    '<tr>',
                        '<td>', this.app.i18n._('Total Messages:'), '</td>',
                        '<td>', folder.get('cache_totalcount'), '</td>',
                    '</tr>',
                    '<tr>',
                        '<td>', this.app.i18n._('Unread Messages:'), '</td>',
                        '<td>', folder.get('cache_unreadcount'), '</td>',
                    '</tr>',
                    '<tr>',
                        '<td>', this.app.i18n._('Name on Server:'), '</td>',
                        '<td>', folder.get('globalname'), '</td>',
                    '</tr>',
                    '<tr>',
                        '<td>', this.app.i18n._('Last update:'), '</td>',
                        '<td>', Tine.Tinebase.common.dateTimeRenderer(folder.get('client_access_time')), '</td>',
                    '</tr>',
                '</table>'
            ];
            tip.body.dom.innerHTML = info.join('');
        } else {
            return false;
        }
    },
    
    /**
     * updates the given tip
     * @param {Ext.Tooltip} tip
     */
    updateProgressTip: function(tip) {
        var folderId = this.getElsParentsNodeId(tip.triggerElement),
            folder = this.app.getFolderStore().getById(folderId),
            progress = Math.round(folder.get('cache_job_actions_done') / folder.get('cache_job_actions_estimate') * 100);
        if (! this.isDropSensitive) {
            tip.body.dom.innerHTML = String.format(this.app.i18n._('Fetching messages... ({0}% done)'), progress);
        } else {
            return false;
        }
    },
    
    /**
     * updates the given tip
     * @param {Ext.Tooltip} tip
     */
    updateUnreadTip: function(tip) {
        var folderId = this.getElsParentsNodeId(tip.triggerElement),
            folder = this.app.getFolderStore().getById(folderId),
            count = folder.get('cache_unreadcount');
            
        if (! this.isDropSensitive) {
            tip.body.dom.innerHTML = String.format(this.app.i18n.ngettext('{0} unread message', '{0} unread messages', count), count);
        } else {
            return false;
        }
    },
    
    /**
     * decrement unread count of currently selected folder
     */
    decrementCurrentUnreadCount: function() {
        var store  = Tine.Tinebase.appMgr.get('Felamimail').getFolderStore(),
            node   = this.getSelectionModel().getSelectedNode(),
            folder = node ? store.getById(node.id) : null;
            
        if (folder) {
            folder.set('cache_unreadcount', parseInt(folder.get('cache_unreadcount'), 10) -1);
            folder.commit();
        }
    },
    
    /**
     * add account record to root node
     * 
     * @param {Tine.Felamimail.Model.Account} record
     */
    addAccount: function(record) {
        
        var node = new Ext.tree.AsyncTreeNode({
            id: record.data.id,
            path: '/' + record.data.id,
            record: record,
            globalname: '',
            draggable: false,
            allowDrop: false,
            expanded: false,
            text: record.get('name'),
            qtip: record.get('host'),
            leaf: false,
            cls: 'felamimail-node-account',
            delimiter: record.get('delimiter'),
            ns_personal: record.get('ns_personal'),
            account_id: record.data.id,
            listeners: {
                scope: this,
                load: function(node) {
                    var account = Tine.Felamimail.loadAccountStore().getById(node.id);
                    this.updateAccountStatus(account);
                }
            }
        });
        
        // we don't want appending folder effects
        this.suspendEvents();
        this.root.appendChild(node);
        this.resumeEvents();
    },
    
    /**
     * get active account by checking selected node
     * @return Tine.Felamimail.Model.Account
     */
    getActiveAccount: function() {
        var result = null;
        var node = this.getSelectionModel().getSelectedNode();
        if (node) {
            var accountId = node.attributes.account_id;
            result = this.accountStore.getById(accountId);
        }
        
        return result;
    }
});

// file: /home/lkneschke/temp/tine20build/temp/tine20/Felamimail/js/GridDetailsPanel.js
/*
 * Tine 2.0
 * 
 * @package     Felamimail
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Philipp Schuele <p.schuele@metaways.de>
 * @copyright   Copyright (c) 2009-2010 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: GridDetailsPanel.js 18727 2011-01-18 18:52:38Z c.weiss@metaways.de $
 *
 */
 
Ext.namespace('Tine.Felamimail');

/**
 * @namespace   Tine.Felamimail
 * @class       Tine.Felamimail.GridDetailsPanel
 * @extends     Tine.widgets.grid.DetailsPanel
 * 
 * <p>Message Grid Details Panel</p>
 * <p>the details panel (shows message content)</p>
 * 
 * TODO         replace telephone numbers in emails with 'call contact' link
 * TODO         make only text body scrollable (headers should be always visible)
 * TODO         show image attachments inline
 * TODO         add 'download all' button
 * TODO         'from' to contact: check for duplicates
 * 
 * @author      Philipp Schuele <p.schuele@metaways.de>
 * @copyright   Copyright (c) 2009-2010 Metaways Infosystems GmbH (http://www.metaways.de)
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @version     $Id: GridDetailsPanel.js 18727 2011-01-18 18:52:38Z c.weiss@metaways.de $
 * 
 * @param       {Object} config
 * @constructor
 * Create a new Tine.Felamimail.GridDetailsPanel
 */
 Tine.Felamimail.GridDetailsPanel = Ext.extend(Tine.widgets.grid.DetailsPanel, {
    
    /**
     * config
     * @private
     */
    defaultHeight: 300,
    currentId: null,
    record: null,
    app: null,
    i18n: null,
    
    fetchBodyTransactionId: null,
    
    /**
     * init
     * @private
     */
    initComponent: function() {

        // init detail template
        this.initTemplate();
        
        // use default Tpl for default and multi view
        this.defaultTpl = new Ext.XTemplate(
            '<div class="preview-panel-felamimail">',
                '<div class="preview-panel-felamimail-body">{[values ? values.msg : ""]}</div>',
            '</div>'
        );
        
        Tine.Felamimail.GridDetailsPanel.superclass.initComponent.call(this);
    },

    /**
     * add on click event after render
     * @private
     */
    afterRender: function() {
        Tine.Felamimail.GridDetailsPanel.superclass.afterRender.apply(this, arguments);
        
        this.body.on('click', this.onClick, this);
    },
    
    /**
     * (on) update details
     * 
     * @param {Tine.Felamimail.Model.Message} record
     * @param {String} body
     * @private
     */
    updateDetails: function(record, body) {
        if (record.id === this.currentId) {
            // nothing to do
            return;
        }
        
        if (! record.bodyIsFetched()) {
            if (! this.grid || this.grid.getSelectionModel().getCount() == 1) {
                this.refetchBody(record, this.updateDetails.createDelegate(this, [record, body]), 'updateDetails');
                this.defaultTpl.overwrite(body, {msg: ''});
                this.getLoadMask().show();
            } else {
                this.getLoadMask().hide();
            }
            return;
        }
        
        if (record === this.record) {                
            this.currentId = record.id;
            this.tpl.overwrite(body, record.data);
            this.getLoadMask().hide();
            this.getEl().down('div').down('div').scrollTo('top', 0, false);
        }
    },
    
    /**
     * refetch message body
     * 
     * @param {Tine.Felamimail.Model.Message} record
     * @param {Function} callback
     * @param {String} fnName
     */
    refetchBody: function(record, callback, fnName) {
        // cancel old request first
        if (this.fetchBodyTransactionId && ! Tine.Felamimail.messageBackend.isLoading(this.fetchBodyTransactionId)) {
            Tine.log.debug('Tine.Felamimail.GridDetailsPanel::' + fnName + '() cancelling current fetchBody request.');
            Tine.Felamimail.messageBackend.abort(this.fetchBodyTransactionId);
        }
        this.fetchBodyTransactionId = Tine.Felamimail.messageBackend.fetchBody(record, callback);
    },
    
    /**
     * init single message template (this.tpl)
     * @private
     */
    initTemplate: function() {
        
        this.tpl = new Ext.XTemplate(
            '<div class="preview-panel-felamimail">',
                '<div class="preview-panel-felamimail-headers">',
                    '<b>' + this.i18n._('Subject') + ':</b> {[this.encode(values.subject)]}<br/>',
                    '<b>' + this.i18n._('From') + ':</b>',
                    ' {[this.showFrom(values.from_email, values.from_name, "' + this.i18n._('Add') + '", "' 
                        + this.i18n._('Add contact to addressbook') + '")]}<br/>',
                    '<b>' + this.i18n._('Date') + ':</b> {[this.encode(values.received)]}',
                    '{[this.showRecipients(values.headers)]}',
                    '{[this.showHeaders("' + this.i18n._('Show or hide header information') + '")]}',
                '</div>',
                '<div class="preview-panel-felamimail-attachments">{[this.showAttachments(values.attachments, values)]}</div>',
                '<div class="preview-panel-felamimail-body">{[this.showBody(values.body, values)]}</div>',
            '</div>',{
            app: this.app,
            panel: this,
            encode: function(value) {
                if (value) {
                    var encoded = Ext.util.Format.htmlEncode(value);
                    encoded = Ext.util.Format.nl2br(encoded);
                    // it should be enough to replace only 2 or more spaces
                    encoded = encoded.replace(/ /g, '&nbsp;');
                    
                    return encoded;
                } else {
                    return '';
                }
                return value;
            },
            
            showFrom: function(email, name, addText, qtip) {
                if (name === null) {
                    return '';
                }
                
                var result = this.encode(name + ' <' + email + '>');
                
                // add link with 'add to contacts'
                var id = Ext.id() + ':' + email;
                
                var nameSplit = name.match(/^"*([^,^ ]+)(,*) *(.+)/i);
                var firstname = (nameSplit && nameSplit[1]) ? nameSplit[1] : '';
                var lastname = (nameSplit && nameSplit[3]) ? nameSplit[3] : '';
                if (nameSplit && nameSplit[2] == ',') {
                    firstname = lastname;
                    lastname = nameSplit[1];
                }
                
                id += Ext.util.Format.htmlEncode(':' + Ext.util.Format.trim(firstname) + ':' + Ext.util.Format.trim(lastname));
                result += ' <span ext:qtip="' + qtip + '" id="' + id + '" class="tinebase-addtocontacts-link">[+]</span>';
                return result;
            },
            
            showBody: function(body, messageData) {
                body = body || '';
                if (body) {
                    var account = this.app.getActiveAccount();
                    if (account.get('display_format') == 'plain' || (account.get('display_format') == 'content_type' && messageData.body_content_type == 'text/plain')) {
                        var width = this.panel.body.getWidth()-25,
                            height = this.panel.body.getHeight()-90,
                            id = Ext.id();
                            
                        if (height < 0) {
                        	// sometimes the height is negative, fix this here
                            height = 500;
                        }
                            
                        body = '<textarea ' +
                            'style="width: ' + width + 'px; height: ' + height + 'px; " ' +
                            'autocomplete="off" id="' + id + '" name="body" class="x-form-textarea x-form-field x-ux-display-background-border" readonly="" >' +
                            body + '</textarea>';
                    }
                }                    
                return body;
            },
            
            showHeaders: function(qtip) {
                var result = ' <span ext:qtip="' + qtip + '" id="' + Ext.id() + ':show" class="tinebase-showheaders-link">[...]</span>';
                return result;
            },
            
            showRecipients: function(value) {
                if (value) {
                    var i18n = Tine.Tinebase.appMgr.get('Felamimail').i18n,
                        result = '';
                    for (header in value) {
                        if (value.hasOwnProperty(header) && (header == 'to' || header == 'cc' || header == 'bcc')) {
                            result += '<br/><b>' + i18n._hidden(Ext.util.Format.capitalize(header)) + ':</b> ' 
                                + Ext.util.Format.htmlEncode(value[header]);
                        }
                    }
                    return result;
                } else {
                    return '';
                }
            },
            
            showAttachments: function(attachements, messageData) {
                var result = (attachements.length > 0) ? '<b>' + this.app.i18n._('Attachments') + ':</b> ' : '';
                
                for (var i=0, id, cls; i < attachements.length; i++) {
                    result += '<span id="' + Ext.id() + ':' + i + '" class="tinebase-download-link">' 
                        + '<i>' + attachements[i].filename + '</i>' 
                        + ' (' + Ext.util.Format.fileSize(attachements[i].size) + ')</span> ';
                }
                
                return result;
            }
        });
    },
    
    /**
     * on click for attachment download / compose dlg / edit contact dlg
     * 
     * @param {} e
     * @private
     */
    onClick: function(e) {
        var selectors = [
            'span[class=tinebase-download-link]',
            'a[class=tinebase-email-link]',
            'span[class=tinebase-addtocontacts-link]',
            'span[class=tinebase-showheaders-link]'
        ];
        
        // find the correct target
        for (var i=0, target=null, selector=''; i < selectors.length; i++) {
            target = e.getTarget(selectors[i]);
            if (target) {
                selector = selectors[i];
                break;
            }
        }
        
        switch (selector) {
            case 'span[class=tinebase-download-link]':
                var idx = target.id.split(':')[1];
                    attachment = this.record.get('attachments')[idx];
                    
                if (! this.record.bodyIsFetched()) {
                    // sometimes there is bad timing and we do not have the attachments available -> refetch body
                    this.refetchBody(this.record, this.onClick.createDelegate(this, [e]), 'onClick');
                    return;
                }
                    
                // remove part id if set (that is the case in message/rfc822 attachments)
                var messageId = (this.record.id.match(/_/)) ? this.record.id.split('_')[0] : this.record.id;
                    
                if (attachment['content-type'] === 'message/rfc822') {
                    // display message
                    Tine.Felamimail.MessageDisplayDialog.openWindow({
                        record: new Tine.Felamimail.Model.Message({
                            id: messageId + '_' + attachment.partId
                        })
                    });
                    
                } else {
                    // download attachment
                    new Ext.ux.file.Download({
                        params: {
                            requestType: 'HTTP',
                            method: 'Felamimail.downloadAttachment',
                            messageId: messageId,
                            partId: attachment.partId
                        }
                    }).start();
                }
                
                break;
                
            case 'a[class=tinebase-email-link]':
                // open compose dlg
                var email = target.id.split(':')[1];
                var defaults = Tine.Felamimail.Model.Message.getDefaultData();
                defaults.to = [email];
                defaults.body = Tine.Felamimail.getSignature();
                
                var record = new Tine.Felamimail.Model.Message(defaults, 0);
                var popupWindow = Tine.Felamimail.MessageEditDialog.openWindow({
                    record: record
                });
                break;
                
            case 'span[class=tinebase-addtocontacts-link]':
                // open edit contact dlg
            
                // check if addressbook app is available
                if (! Tine.Addressbook || ! Tine.Tinebase.common.hasRight('run', 'Addressbook')) {
                    return;
                }
            
                var id = Ext.util.Format.htmlDecode(target.id);
                var parts = id.split(':');
                
                var popupWindow = Tine.Addressbook.ContactEditDialog.openWindow({
                    listeners: {
                        scope: this,
                        'load': function(editdlg) {
                            editdlg.record.set('email', parts[1]);
                            editdlg.record.set('n_given', parts[2]);
                            editdlg.record.set('n_family', parts[3]);
                        }
                    }
                });
                
                break;
                
            case 'span[class=tinebase-showheaders-link]':
                // show headers
            
                var parts = target.id.split(':');
                var targetId = parts[0];
                var action = parts[1];
                
                var html = '';
                if (action == 'show') {
                    var recordHeaders = this.record.get('headers');
                    
                    for (header in recordHeaders) {
                        if (recordHeaders.hasOwnProperty(header) && (header != 'to' || header != 'cc' || header != 'bcc')) {
                            html += '<br/><b>' + header + ':</b> ' 
                                + Ext.util.Format.htmlEncode(recordHeaders[header]);
                        }
                    }
                
                    target.id = targetId + ':' + 'hide';
                    
                } else {
                    html = ' <span ext:qtip="' + this.i18n._('Show or hide header information') + '" id="' 
                        + Ext.id() + ':show" class="tinebase-showheaders-link">[...]</span>'
                }
                
                target.innerHTML = html;
                
                break;
        }
    }
});

// file: /home/lkneschke/temp/tine20build/temp/tine20/Felamimail/js/GridPanel.js
/*
 * Tine 2.0
 * 
 * @package     Felamimail
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Philipp Schüle <p.schuele@metaways.de>
 * @copyright   Copyright (c) 2007-2011 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: GridPanel.js 18924 2011-01-28 14:25:11Z p.schuele@metaways.de $
 */
 
Ext.namespace('Tine.Felamimail');

/**
 * Message grid panel
 * 
 * @namespace   Tine.Felamimail
 * @class       Tine.Felamimail.GridPanel
 * @extends     Tine.widgets.grid.GridPanel
 * 
 * <p>Message Grid Panel</p>
 * <p><pre>
 * </pre></p>
 * 
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Philipp Schüle <p.schuele@metaways.de>
 * @version     $Id: GridPanel.js 18924 2011-01-28 14:25:11Z p.schuele@metaways.de $
 * 
 * @param       {Object} config
 * @constructor
 * Create a new Tine.Felamimail.GridPanel
 */
Tine.Felamimail.GridPanel = Ext.extend(Tine.widgets.grid.GridPanel, {
	/**
	 * record class
	 * @cfg {Tine.Felamimail.Model.Message} recordClass
	 */
    recordClass: Tine.Felamimail.Model.Message,
    
	/**
	 * message detail panel
	 * 
	 * @type Tine.Felamimail.GridDetailsPanel
	 * @property detailsPanel
	 */
    detailsPanel: null,
    
    /**
     * transaction id of current delete message request
     * @type Number
     */
    deleteTransactionId: null,
    
    /**
     * @private model cfg
     */
    evalGrants: false,
    filterSelectionDelete: true,
    
    /**
     * @private grid cfg
     */
    defaultSortInfo: {field: 'received', direction: 'DESC'},
    gridConfig: {
        //loadMask: true,
        autoExpandColumn: 'subject',
        // drag n dropfrom
        enableDragDrop: true,
        ddGroup: 'mailToTreeDDGroup'
    },
    // we don't want to update the preview panel on context menu
    updateDetailsPanelOnCtxMenu: false,
    
    /**
     * Return CSS class to apply to rows depending upon flags
     * - checks Flagged, Deleted and Seen
     * 
     * @param {Tine.Felamimail.Model.Message} record
     * @param {Integer} index
     * @return {String}
     */
    getViewRowClass: function(record, index) {
        var className = '';
        
        if (record.hasFlag('\\Flagged')) {
            className += ' flag_flagged';
        }
        if (record.hasFlag('\\Deleted')) {
            className += ' flag_deleted';
        }
        if (! record.hasFlag('\\Seen')) {
            className += ' flag_unread';
        }
        
        return className;
    },
    
    /**
     * init message grid
     * @private
     */
    initComponent: function() {
        
        this.app = Tine.Tinebase.appMgr.get('Felamimail');
        this.i18nEmptyText = this.app.i18n._('No Messages found or the cache is empty.');
        
        this.recordProxy = Tine.Felamimail.messageBackend;
        
        this.gridConfig.columns = this.getColumns();
        this.initFilterToolbar();
        this.initDetailsPanel();
        
        this.plugins = this.plugins || [];
        this.plugins.push(this.filterToolbar);
        
        this.pagingConfig = {
            doRefresh: this.doRefresh.createDelegate(this)
        };
        
        Tine.Felamimail.GridPanel.superclass.initComponent.call(this);
        this.grid.getSelectionModel().on('rowselect', this.onRowSelection, this);
        this.app.getFolderStore().on('update', this.onUpdateFolderStore, this);
    },
    
    /**
     * cleanup on destruction
     */
    onDestroy: function() {
        this.app.getFolderStore().un('update', this.onUpdateFolderStore, this);
    },
    
    /**
     * folder store gets updated -> refresh grid if new messages arrived or messages have been removed
     * 
     * @param {Tine.Felamimail.FolderStore} store
     * @param {Tine.Felamimail.Model.Folder} record
     * @param {String} operation
     */
    onUpdateFolderStore: function(store, record, operation) {
        if (operation === Ext.data.Record.EDIT && record.isModified('cache_totalcount')) {
            var tree = this.app.getMainScreen().getTreePanel(),
                selectedNodes = (tree) ? tree.getSelectionModel().getSelectedNodes() : [];
            
            // only refresh if 1 or no messages are selected
            if (this.getGrid().getSelectionModel().getCount() <= 1) {
                var refresh = false;
                for (var i = 0; i < selectedNodes.length; i++) {
                    if (selectedNodes[i].id == record.id) {
                        refresh = true;
                        break;
                    }
                }
                
                // check if folder is in filter or allinboxes are selected and updated folder is an inbox
                if (! refresh) {
                    var filters = this.filterToolbar.getValue();
                    for (var i = 0; i < filters.length; i++) {
                        if (filters[i].field == 'path' && filters[i].operator == 'in') {
                            if (filters[i].value.indexOf(record.get('path')) !== -1 || (filters[i].value.indexOf('/allinboxes') !== -1 && record.isInbox())) {
                                refresh = true;
                                break;
                            }
                        }
                    }
                }
                
                if (refresh) {
                    this.loadGridData({
                        removeStrategy: 'keepBuffered'
                    });
                }
            }
        }
    },
    
    /**
     * skip initial till we know the INBOX id
     */
    initialLoad: function() {
        var account = this.app.getActiveAccount(),
            accountId = account ? account.id : null,
            inbox = accountId ? this.app.getFolderStore().queryBy(function(record) {
                return record.get('account_id') === accountId && record.get('localname') === 'INBOX';
            }, this).first() : null;
            
        if (! inbox) {
            this.initialLoad.defer(100, this, arguments);
            return;
        }
        
        return Tine.Felamimail.GridPanel.superclass.initialLoad.apply(this, arguments);
    },
    
    /**
     * init actions with actionToolbar, contextMenu and actionUpdater
     * 
     * @private
     */
    initActions: function() {

        this.action_write = new Ext.Action({
            requiredGrant: 'addGrant',
            actionType: 'add',
            text: this.app.i18n._('Compose'),
            handler: this.onMessageCompose.createDelegate(this),
            iconCls: this.app.appName + 'IconCls'
        });

        this.action_reply = new Ext.Action({
            requiredGrant: 'readGrant',
            actionType: 'reply',
            text: this.app.i18n._('Reply'),
            handler: this.onMessageReplyTo.createDelegate(this, [false]),
            iconCls: 'action_email_reply',
            disabled: true
        });

        this.action_replyAll = new Ext.Action({
            requiredGrant: 'readGrant',
            actionType: 'replyAll',
            text: this.app.i18n._('Reply To All'),
            handler: this.onMessageReplyTo.createDelegate(this, [true]),
            iconCls: 'action_email_replyAll',
            disabled: true
        });

        this.action_forward = new Ext.Action({
            requiredGrant: 'readGrant',
            actionType: 'forward',
            text: this.app.i18n._('Forward'),
            handler: this.onMessageForward.createDelegate(this),
            iconCls: 'action_email_forward',
            disabled: true
        });

        this.action_flag = new Ext.Action({
            requiredGrant: 'readGrant',
            text: this.app.i18n._('Toggle highlighting'),
            handler: this.onToggleFlag.createDelegate(this, ['\\Flagged'], true),
            iconCls: 'action_email_flag',
            allowMultiple: true,
            disabled: true
        });
        
        this.action_markUnread = new Ext.Action({
            requiredGrant: 'readGrant',
            text: this.app.i18n._('Mark read/unread'),
            handler: this.onToggleFlag.createDelegate(this, ['\\Seen'], true),
            iconCls: 'action_mark_read',
            allowMultiple: true,
            disabled: true
        });
        
        this.action_deleteRecord = new Ext.Action({
            requiredGrant: 'deleteGrant',
            allowMultiple: true,
            singularText: this.app.i18n._('Delete'),
            pluralText: this.app.i18n._('Delete'),
            translationObject: this.i18nDeleteActionText ? this.app.i18n : Tine.Tinebase.translation,
            text: this.app.i18n._('Delete'),
            handler: this.onDeleteRecords,
            disabled: true,
            iconCls: 'action_delete',
            scope: this
        });
        
        this.action_addAccount = new Ext.Action({
            text: this.app.i18n._('Add Account'),
            handler: this.onAddAccount,
            iconCls: 'action_add',
            scope: this,
            disabled: ! Tine.Tinebase.common.hasRight('add_accounts', 'Felamimail')
        });
        this.action_printPreview = new Ext.Action({
            requiredGrant: 'readGrant',
            text: this.app.i18n._('Print Preview'),
            handler: this.onPrintPreview.createDelegate(this, []),
            disabled:true,
            iconCls:'action_printPreview',
            scope:this
        });
        this.action_print = new Ext.Action({
            requiredGrant: 'readGrant',
            text: this.app.i18n._('Print Message'),
            handler: this.onPrint.createDelegate(this, []),
            disabled:true,
            iconCls:'action_print',
            scope:this,
            menu:{
                items:[
                    this.action_printPreview
                ]
            }
        });
        this.actionUpdater.addActions([
            this.action_write,
            this.action_deleteRecord,
            this.action_reply,
            this.action_replyAll,
            this.action_forward,
            this.action_flag,
            this.action_markUnread,
            this.action_addAccount,
            this.action_print,
            this.action_printPreview
        ]);
        
        this.contextMenu = new Ext.menu.Menu({
            items: [
                this.action_reply,
                this.action_replyAll,
                this.action_forward,
                this.action_flag,
                this.action_markUnread,
                this.action_deleteRecord
            ]
        });
    },
    
    /**
     * initialises filter toolbar
     * 
     * @private
     */
    initFilterToolbar: function() {
        this.filterToolbar = new Tine.widgets.grid.FilterToolbar({
            filterModels: Tine.Felamimail.Model.Message.getFilterModel(),
             defaultFilter: 'query',
             filters: [],
             plugins: [
                new Tine.widgets.grid.FilterToolbarQuickFilterPlugin({
                    criteriaIgnores: [
                        {field: 'query',     operator: 'contains',     value: ''},
                        {field: 'id' }
                    ]
                })
             ]
        });
    },    
    
    /**
     * the details panel (shows message content)
     * 
     * @private
     */
    initDetailsPanel: function() {
        this.detailsPanel = new Tine.Felamimail.GridDetailsPanel({
            gridpanel: this,
            grid: this,
            app: this.app,
            i18n: this.app.i18n
        });
    },
    
    getActionToolbar: function() {
        if (! this.actionToolbar) {
            this.actionToolbar = new Ext.Toolbar({
                defaults: {height: 55},
                items: [{
                    xtype: 'buttongroup',
                    columns: 8,
                    items: [
                        Ext.apply(new Ext.Button(this.action_write), {
                            scale: 'medium',
                            rowspan: 2,
                            iconAlign: 'top'
                        }),
                        Ext.apply(new Ext.Button(this.action_deleteRecord), {
                            scale: 'medium',
                            rowspan: 2,
                            iconAlign: 'top'
                        }),
                        Ext.apply(new Ext.Button(this.action_reply), {
                            scale: 'medium',
                            rowspan: 2,
                            iconAlign: 'top'
                        }),
                        Ext.apply(new Ext.Button(this.action_replyAll), {
                            scale: 'medium',
                            rowspan: 2,
                            iconAlign: 'top'
                        }),
                        Ext.apply(new Ext.Button(this.action_forward), {
                            scale: 'medium',
                            rowspan: 2,
                            iconAlign: 'top'
                        }),
                        Ext.apply(new Ext.SplitButton(this.action_print), {
                            scale: 'medium',
                            rowspan: 2,
                            iconAlign:'top',
                            arrowAlign:'right'
                        }),
                        this.action_flag,
                        this.action_addAccount,
                        this.action_markUnread
                    ]
                }, this.getActionToolbarItems()]
            });
            
            if (this.filterToolbar && typeof this.filterToolbar.getQuickFilterField == 'function') {
                this.actionToolbar.add('->', this.filterToolbar.getQuickFilterField());
            }
        }
        
        return this.actionToolbar;
    },
    
    /**
     * returns cm
     * 
     * @private
     */
    getColumns: function(){
        return [{
            id: 'id',
            header: this.app.i18n._("Id"),
            width: 100,
            sortable: true,
            dataIndex: 'id',
            hidden: true
        }, {
            id: 'content_type',
            header: this.app.i18n._("Attachments"),
            width: 12,
            sortable: true,
            dataIndex: 'content_type',
            renderer: this.attachmentRenderer
        }, {
            id: 'flags',
            header: this.app.i18n._("Flags"),
            width: 24,
            sortable: true,
            dataIndex: 'flags',
            renderer: this.flagRenderer
        },{
            id: 'subject',
            header: this.app.i18n._("Subject"),
            width: 300,
            sortable: true,
            dataIndex: 'subject'
        },{
            id: 'from_email',
            header: this.app.i18n._("From (Email)"),
            width: 100,
            sortable: true,
            dataIndex: 'from_email'
        },{
            id: 'from_name',
            header: this.app.i18n._("From (Name)"),
            width: 100,
            sortable: true,
            dataIndex: 'from_name'
        },{
            id: 'sender',
            header: this.app.i18n._("Sender"),
            width: 100,
            sortable: true,
            dataIndex: 'sender',
            hidden: true
        },{
            id: 'to',
            header: this.app.i18n._("To"),
            width: 150,
            sortable: true,
            dataIndex: 'to',
            hidden: true
        },{
            id: 'sent',
            header: this.app.i18n._("Sent"),
            width: 100,
            sortable: true,
            dataIndex: 'sent',
            hidden: true,
            renderer: Tine.Tinebase.common.dateTimeRenderer
        },{
            id: 'received',
            header: this.app.i18n._("Received"),
            width: 100,
            sortable: true,
            dataIndex: 'received',
            renderer: Tine.Tinebase.common.dateTimeRenderer
        },{
            id: 'folder_id',
            header: this.app.i18n._("Folder"),
            width: 100,
            sortable: true,
            dataIndex: 'folder_id',
            hidden: true,
            renderer: this.accountAndFolderRenderer.createDelegate(this)
        },{
            id: 'size',
            header: this.app.i18n._("Size"),
            width: 80,
            sortable: true,
            dataIndex: 'size',
            hidden: true,
            renderer: Ext.util.Format.fileSize
        }];
    },
    
    /**
     * attachment column renderer
     * 
     * @param {String} value
     * @return {String}
     * @private
     */
    attachmentRenderer: function(value) {
        var result = '';
        
        if (value && value.match(/multipart\/mixed/)) {
            result = '<img class="FelamimailFlagIcon" src="images/oxygen/16x16/actions/attach.png">';
        }
        
        return result;
    },
    
    /**
     * get flag icon
     * 
     * @param {String} flags
     * @return {String}
     * @private
     * 
     * TODO  use spacer if first flag(s) is/are not set?
     */
    flagRenderer: function(value, metadata, record) {
        var icons = [],
            result = '';
            
        if (record.hasFlag('\\Answered')) {
            icons.push({src: 'images/oxygen/16x16/actions/mail-reply-sender.png', qtip: _('Answered')});
        }   
        if (record.hasFlag('Passed')) {
            icons.push({src: 'images/oxygen/16x16/actions/mail-forward.png', qtip: _('Forwarded')});
        }   
//        if (record.hasFlag('\\Recent')) {
//            icons.push({src: 'images/oxygen/16x16/actions/knewstuff.png', qtip: _('Recent')});
//        }   
        
        Ext.each(icons, function(icon) {
            result += '<img class="FelamimailFlagIcon" src="' + icon.src + '" ext:qtip="' + icon.qtip + '">';
        }, this);
        
        return result;
    },
    
    /**
     * returns account and folder globalname
     * 
     * @param {String} folderId
     * @param {Object} metadata
     * @param {Folder|Account} record
     * @return {String}
     */
    accountAndFolderRenderer: function(folderId, metadata, record) {
        var folderStore = this.app.getFolderStore(),
            account = Tine.Felamimail.loadAccountStore().getById(record.get('account_id')),
            result = (account) ? account.get('name') : record.get('account_id'),
            folder = folderStore.getById(folderId);
        
        if (! folder) {
            folder = folderStore.getById(record.id);
            if (! folder) {
                // only account
                return (result) ? result : record.get('name');
            }
        }
            
        result += '/';
        if (folder) {
            result += folder.get('globalname');
        } else {
            result += folderId;
        }
            
        return result;
    },
    
    /**
     * executed when user clicks refresh btn
     */
    doRefresh: function() {
        var tree = this.app.getMainScreen().getTreePanel(),
            node = tree ? tree.getSelectionModel().getSelectedNode() : null,
            folder = node ? this.app.getFolderStore().getById(node.id) : null,
            refresh = this.pagingToolbar.refresh;
            
        // refresh is explicit
        this.editBuffer = [];
            
        if (folder) {
            refresh.disable();
            Tine.log.info('user forced mail check for folder "' + folder.get('localname') + '"');
            this.app.checkMails(folder, function() {
                refresh.enable();
            });
        } else {
            this.filterToolbar.onFilterChange();
        }
    },
    
    /**
     * permanently delete selected messages
     */
    deleteSelectedMessages: function() {
        this.moveOrDeleteMessages(null);
    },
    
    /**
     * move selected messages to given folder
     * 
     * @param {Tine.Felamimail.Model.Folder} folder
     * @param {Boolean} toTrash
     */
    moveSelectedMessages: function(folder, toTrash) {
        if (folder.isCurrentSelection()) {
            // nothing to do ;-)
            return;
        }
        
        this.moveOrDeleteMessages(folder, toTrash);
    },
    
    /**
     * move (folder !== null) or delete selected messages 
     * 
     * @param {Tine.Felamimail.Model.Folder} folder
     * @param {Boolean} toTrash
     */
    moveOrDeleteMessages: function(folder, toTrash) {
        var sm = this.getGrid().getSelectionModel(),
            filter = sm.getSelectionFilter(),
            msgsIds = [];

        if (sm.isFilterSelect) {
            var msgs = this.getStore(),
                nextRecord = null;
        } else {
            var msgs = sm.getSelectionsCollection();
            
            if (sm.getCount() == 1 && this.getStore().getCount() > 1) {
                // select next message (or previous if it was the last)
                lastIdx = this.getStore().indexOf(msgs.last());
                nextRecord = this.getStore().getAt(lastIdx + 1);
                if (! nextRecord) {
                    nextRecord = this.getStore().getAt(lastIdx - 1);
                }
            }
        }
        
        var increaseUnreadCountInTargetFolder = 0;
        msgs.each(function(msg) {
            var isSeen = msg.hasFlag('\\Seen'),
                currFolder = this.app.getFolderStore().getById(msg.get('folder_id')),
                diff = isSeen ? 0 : 1;
                
            if (currFolder) {
                currFolder.set('cache_unreadcount', currFolder.get('cache_unreadcount') - diff);
            }
            if (folder) {
                increaseUnreadCountInTargetFolder += diff;
            }
           
            msgsIds.push(msg.id);
            this.getStore().remove(msg);    
        },  this);
        
        if (folder && increaseUnreadCountInTargetFolder > 0) {
            // update unread count of target folder (only when moving)
            folder.set('cache_unreadcount', folder.get('cache_unreadcount') + increaseUnreadCountInTargetFolder);
        }            
        
        this.deleteQueue = this.deleteQueue.concat(msgsIds);
        this.pagingToolbar.refresh.disable();
        if (nextRecord !== null) {
            sm.selectRecords([nextRecord]);
        }
        
        if (folder !== null) {
            // move
            var targetFolderId = (toTrash) ? '_trash_' : folder.id;
            this.deleteTransactionId = Tine.Felamimail.messageBackend.moveMessages(filter, targetFolderId, { 
                callback: this.onAfterDelete.createDelegate(this, [msgsIds, folder])
            }); 
        } else {
            // delete
            this.deleteTransactionId = Tine.Felamimail.messageBackend.addFlags(filter, '\\Deleted', { 
                callback: this.onAfterDelete.createDelegate(this, [msgsIds])
            });
        }
    },
    
    /**
     * executed after a msg compose
     * 
     * @param {String} composedMsg
     * @param {String} action
     * @param {Array}  [affectedMsgs]  messages affected 
     */
    onAfterCompose: function(composedMsg, action, affectedMsgs) {
        // mark send folders cache status incomplete
        composedMsg = Ext.isString(composedMsg) ? new this.recordClass(Ext.decode(composedMsg)) : composedMsg;
        
        // NOTE: if affected messages is decoded, we need to fetch the originals out of our store
        if (Ext.isString(affectedMsgs)) {
            var msgs = [],
                store = this.getStore();
            Ext.each(Ext.decode(affectedMsgs), function(msgData) {
                var msg = store.getById(msgData.id);
                if (msg) {
                    msgs.push(msg);
                }
            }, this);
            affectedMsgs = msgs;
        }
        
        var composerAccount = Tine.Felamimail.loadAccountStore().getById(composedMsg.get('account_id')),
            sendFolderId = composerAccount ? composerAccount.getSendFolderId() : null,
            sendFolder = sendFolderId ? this.app.getFolderStore().getById(sendFolderId) : null;
            
        if (sendFolder) {
            sendFolder.set('cache_status', 'incomplete');
        }
        
        if (Ext.isArray(affectedMsgs)) {
            Ext.each(affectedMsgs, function(msg) {
                if (['reply', 'forward'].indexOf(action) !== -1) {
                    msg.addFlag(action === 'reply' ? '\\Answered' : 'Passed');
                } else if (action == 'senddraft') {
                    this.deleteTransactionId = Tine.Felamimail.messageBackend.addFlags(msg.id, '\\Deleted', { 
                        callback: this.onAfterDelete.createDelegate(this, [[msg.id]])
                    });
                }
            }, this);
		}
    },
    
    /**
     * executed after msg delete
     * 
     * @param {Array} [ids]
     * @param {Tine.Felamimail.Model.Folder} [folder]
     */
    onAfterDelete: function(ids, folder) {
        this.editBuffer = this.editBuffer.diff(ids);

        if (! this.deleteTransactionId || ! Tine.Felamimail.messageBackend.isLoading(this.deleteTransactionId)) {
            this.loadGridData({
                removeStrategy: 'keepBuffered'
            });
        }
    },
    
    /**
     * compose new message handler
     */
    onMessageCompose: function() {
        Tine.Felamimail.MessageEditDialog.openWindow({
            listeners: {
                'update': this.onAfterCompose.createDelegate(this, ['compose'], 1)
            }
        });
    },
    
    /**
     * forward message(s) handler
     */
    onMessageForward: function() {
        var sm = this.getGrid().getSelectionModel(),
            msgs = sm.getSelections(),
            msgsData = [];
            
        Ext.each(msgs, function(msg) {msgsData.push(msg.data)}, this);
        
        if (sm.getCount() > 0) {
            Tine.Felamimail.MessageEditDialog.openWindow({
                forwardMsgs : Ext.encode(msgsData),
                listeners: {
                    'update': this.onAfterCompose.createDelegate(this, ['forward', msgs], 1)
                }
            });
        }
    },
    
    /**
     * reply message handler
     * 
     * @param {bool} toAll
     */
    onMessageReplyTo: function(toAll) {
        var sm = this.getGrid().getSelectionModel(),
            msg = sm.getSelected();
            
        Tine.Felamimail.MessageEditDialog.openWindow({
            replyTo : Ext.encode(msg.data),
            replyToAll: toAll,
            listeners: {
                'update': this.onAfterCompose.createDelegate(this, ['reply', [msg]], 1)
            }
        });
    },
    
    /**
     * delete messages handler
     * 
     * @return {void}
     */
    onDeleteRecords: function() {
        var account = this.app.getActiveAccount(),
            trashId = account.getTrashFolderId(),
            trash = trashId ? this.app.getFolderStore().getById(trashId) : null;
            
        return trash && !trash.isCurrentSelection() ? this.moveSelectedMessages(trash, true) : this.deleteSelectedMessages();
    },

    /**
     * called when a row gets selected
     * 
     * @param {SelectionModel} sm
     * @param {Number} rowIndex
     * @param {Tine.Felamimail.Model.Message} record
     * @param {Boolean} now
     */
    onRowSelection: function(sm, rowIndex, record, now) {
        if (! now) {
            return this.onRowSelection.defer(250, this, [sm, rowIndex, record, true]);
        }
        
        if (sm.getCount() == 1 && sm.isIdSelected(record.id) && !record.hasFlag('\\Seen')) {
            record.addFlag('\\Seen');
            Tine.Felamimail.messageBackend.addFlags(record.id, '\\Seen');
            this.app.getMainScreen().getTreePanel().decrementCurrentUnreadCount();
        }
    },
    
    /**
     * row doubleclick handler
     * 
     * - opens message edit dialog (if draft/template)
     * - opens message display dialog (everything else)
     * 
     * @param {Tine.Felamimail.GridPanel} grid
     * @param {Row} row
     * @param {Event} e
     */
    onRowDblClick: function(grid, row, e) {
        
        var record = this.grid.getSelectionModel().getSelected(),
            folder = this.app.getFolderStore().getById(record.get('folder_id')),
            account = Tine.Felamimail.loadAccountStore().getById(folder.get('account_id'));
            action = (folder.get('globalname') == account.get('drafts_folder')) ? 'senddraft' :
                     folder.get('globalname') == account.get('templates_folder') ? 'sendtemplate' : null;
        
        // check folder to determine if mail should be opened in compose dlg
        if (action !== null) {
            Tine.Felamimail.MessageEditDialog.openWindow({
                draftOrTemplate: Ext.encode(record.data),
                listeners: {
                    scope: this,
                    'update': this.onAfterCompose.createDelegate(this, [action, [record]], 1)
                }
            });
        } else {
            Tine.Felamimail.MessageDisplayDialog.openWindow({
                record: record,
                listeners: {
                    scope: this,
                    'update': this.onAfterCompose,
                    'remove': this.onRemoveInDisplayDialog
                }
            });
        }
    },
    
    /**
     * message got removed in display dialog
     * 
     * @param {} msgData
     */
    onRemoveInDisplayDialog: function (msgData) {
        var msg = this.getStore().getById(Ext.decode(msgData).id);
            folderId = msg ? msg.get('folder_id') : null,
            folder = folderId ? this.app.getFolderStore().getById(folderId) : null,
            accountId = folder ? folder.get('account_id') : null,
            account = accountId ? Tine.Felamimail.loadAccountStore().getById(accountId) : null,
            trashId = account ? account.getTrashFolderId() : null,
            trash = trashId ? this.app.getFolderStore().getById(trashId) : null;
            
        this.getStore().remove(msg);
        this.onAfterDelete(null, trash);
    },    
    
    /**
     * called when the store gets updated
     * 
     * NOTE: we only allow updateing flags BUT the actual updating is done 
     *       directly from the UI fn's to support IMAP optimised bulk actions
     */
    onStoreUpdate: function(store, record, operation) {
        if (operation === Ext.data.Record.EDIT && record.isModified('flags')) {
            record.commit()
        }
    },
    
    /**
     * key down handler
     * 
     * @param {Event} e
     */
    onKeyDown: function(e) {
        if (e.ctrlKey) {
            switch (e.getKey()) {
                case e.N:
                case e.M:
                    this.onMessageCompose();
                    e.preventDefault();
                    break;
                case e.R:
                    this.onMessageReplyTo();
                    e.preventDefault();
                    break;
                case e.L:
                    this.onMessageForward();
                    e.preventDefault();
                    break;
            }
        }
        
        Tine.Felamimail.GridPanel.superclass.onKeyDown.call(this, e);
    },
    
    /**
     * toggle flagged status of mail(s)
     * - Flagged/Seen
     * 
     * @param {Button} button
     * @param {Event} event
     * @param {String} flag
     */
    onToggleFlag: function(btn, e, flag) {
        var sm = this.getGrid().getSelectionModel(),
            filter = sm.getSelectionFilter(),
            msgs = sm.isFilterSelect ? this.getStore() : sm.getSelectionsCollection(),
            flagCount = 0;
            
        // switch all msgs to one state -> toogle most of them
        msgs.each(function(msg) {
            flagCount += msg.hasFlag(flag) ? 1 : 0;
        });
        var action = flagCount >= Math.round(msgs.getCount()/2) ? 'clear' : 'add';
        
        // mark messages in UI and add to edit buffer
        msgs.each(function(msg) {
            // update unreadcount
            if (flag === '\\Seen') {
                var isSeen = msg.hasFlag('\\Seen'),
                    folder = this.app.getFolderStore().getById(msg.get('folder_id')),
                    diff = (action === 'clear' && isSeen) ? 1 :
                           (action === 'add' && ! isSeen) ? -1 : 0;
                           
                if (folder) {
                    folder.set('cache_unreadcount', folder.get('cache_unreadcount') + diff);
                    folder.commit();
                }
            }
            
            msg[action + 'Flag'](flag);
            
            this.addToEditBuffer(msg);
        }, this);
        
        // do request
        Tine.Felamimail.messageBackend[action+ 'Flags'](filter, flag);
    },
    
    /**
     * called before store queries for data
     */
    onStoreBeforeload: function(store, options) {
        this.supr().onStoreBeforeload.apply(this, arguments);
        
        if (! Ext.isEmpty(this.deleteQueue)) {
            options.params.filter.push({field: 'id', operator: 'notin', value: this.deleteQueue});
        }
    },
    
    /**
     * add new account button
     * 
     * @param {Button} button
     * @param {Event} event
     */
    onAddAccount: function(button, event) {
        var popupWindow = Tine.Felamimail.AccountEditDialog.openWindow({
            record: null,
            listeners: {
                scope: this,
                'update': function(record) {
                    var account = new Tine.Felamimail.Model.Account(Ext.util.JSON.decode(record));
                    
                    // add to registry
                    Tine.Felamimail.registry.get('preferences').replace('defaultEmailAccount', account.id);
                    // need to do this because store could be unitialized yet
                    var registryAccounts = Tine.Felamimail.registry.get('accounts');
                    registryAccounts.results.push(account.data);
                    registryAccounts.totalcount++;
                    Tine.Felamimail.registry.replace('accounts', registryAccounts);
                    
                    // add to tree / store
                    var treePanel = this.app.getMainScreen().getTreePanel();
                    treePanel.addAccount(account);
                    treePanel.accountStore.add([account]);
                }
            }
        });        
    },
    
    /**
     * print handler
     * 
     * @param {Tine.Felamimail.GridDetailsPanel} details panel [optional]
     */
    onPrint: function(detailsPanel) {
        if (!Ext.get('felamimailPrintHelperIframe')) {
            Ext.getBody().createChild({
                id: 'felamimailPrintHelper',
                tag:'div',
                //style:'position:absolute;top:0px;width:100%;height:100%;',
                children:[{
                    tag:'iframe',
                    id: 'felamimailPrintHelperIframe'
                }]
            });
        }
        var content = this.getDetailsPanelContentForPrinting(detailsPanel || this.detailsPanel);
        Ext.get('felamimailPrintHelperIframe').dom.contentWindow.document.documentElement.innerHTML = content;
        Ext.get('felamimailPrintHelperIframe').dom.contentWindow.print();
    },
    
    /**
     * get detail panel content
     * 
     * @param {Tine.Felamimail.GridDetailsPanel} details panel
     * @return {String}
     */
    getDetailsPanelContentForPrinting: function(detailsPanel) {
        // TODO somehow we have two <div class="preview-panel-felamimail"> -> we need to fix that and get the first element found
        var detailsPanels = detailsPanel.getEl().query('.preview-panel-felamimail');
        var detailsPanelContent = (detailsPanels.length > 1) ? detailsPanels[1].innerHTML : detailsPanels[0].innerHTML;
        
        var buffer = '<html><head>';
        buffer += '<title>' + this.app.i18n._('Print Preview') + '</title>';
        buffer += '</head><body>';
        buffer += detailsPanelContent;
        buffer += '</body></html>';
        
        return buffer;
    },
    
    /**
     * print preview handler
     * 
     * @param {Tine.Felamimail.GridDetailsPanel} details panel [optional]
     */
    onPrintPreview: function(detailsPanel) {
        var content = this.getDetailsPanelContentForPrinting(detailsPanel || this.detailsPanel);
        
        var win = window.open('about:blank',this.app.i18n._('Print Preview'),'width=500,height=500,scrollbars=yes,toolbar=yes,status=yes,menubar=yes');
        win.document.open()
        win.document.write(content);
        win.document.close();
        win.focus();
    },
    
    /**
     * format headers
     * 
     * @param {Object} headers
     * @param {Bool} ellipsis
     * @param {Bool} onlyImportant
     * @return {String}
     */
    formatHeaders: function(headers, ellipsis, onlyImportant) {
        var result = '';
        for (header in headers) {
            if (headers.hasOwnProperty(header) && 
                    (! onlyImportant || header == 'from' || header == 'to' || header == 'subject' || header == 'date')) 
            {
                result += '<b>' + header + ':</b> ' 
                    + Ext.util.Format.htmlEncode(
                        (ellipsis) 
                            ? Ext.util.Format.ellipsis(headers[header], 40)
                            : headers[header]
                    ) + '<br/>';
            }
        }
        return result;
    }    
});

// file: /home/lkneschke/temp/tine20build/temp/tine20/Felamimail/js/MessageDisplayDialog.js
/*
 * Tine 2.0
 * 
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Philipp Schüle <p.schuele@metaways.de>
 * @copyright   Copyright (c) 2009-2011 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: MessageDisplayDialog.js 18698 2011-01-18 10:29:59Z p.schuele@metaways.de $
 */
 
Ext.ns('Tine.Felamimail')


Tine.Felamimail.MessageDisplayDialog = Ext.extend(Tine.Felamimail.GridDetailsPanel ,{
    /**
     * @cfg {Tine.Felamimail.Model.Message}
     */
    record: null,
    
    autoScroll: false,
    
    initComponent: function() {
        this.addEvents('remove');
        
        this.app = Tine.Tinebase.appMgr.get('Felamimail');
        this.i18n = this.app.i18n;
        
        // trick onPrint/onPrintPreview
        this.detailsPanel = this;
        
        this.initActions();
        this.initToolbar();
        
        this.supr().initComponent.apply(this, arguments);
    },
    
    /**
     * init actions
     */
    initActions: function() {
        this.action_deleteRecord = new Ext.Action({
            text: this.app.i18n._('Delete'),
            handler: this.onMessageDelete.createDelegate(this, [false]),
            iconCls: 'action_delete',
            disabled: this.record.id.match(/_/)
        });
        
        this.action_reply = new Ext.Action({
            text: this.app.i18n._('Reply'),
            handler: this.onMessageReplyTo.createDelegate(this, [false]),
            iconCls: 'action_email_reply'
        });

        this.action_replyAll = new Ext.Action({
            text: this.app.i18n._('Reply To All'),
            handler: this.onMessageReplyTo.createDelegate(this, [true]),
            iconCls: 'action_email_replyAll'
        });

        this.action_forward = new Ext.Action({
            text: this.app.i18n._('Forward'),
            handler: this.onMessageForward.createDelegate(this),
            iconCls: 'action_email_forward',
            disabled: this.record.id.match(/_/)
        });

        this.action_download = new Ext.Action({
            text: this.app.i18n._('Save'),
            handler: this.onMessageDownload.createDelegate(this),
            iconCls: 'action_email_download',
            disabled: this.record.id.match(/_/)
        });
        
        this.action_print = new Ext.Action({
            text: this.app.i18n._('Print Message'),
            handler: this.onMessagePrint.createDelegate(this.app.getMainScreen().getCenterPanel(), [this]),
            iconCls:'action_print',
            menu:{
                items:[
                    new Ext.Action({
                        text: this.app.i18n._('Print Preview'),
                        handler: this.onMessagePrintPreview.createDelegate(this.app.getMainScreen().getCenterPanel(), [this]),
                        iconCls:'action_printPreview'
                    })
                ]
            }
        });
        
    },
    
    /**
     * init toolbar
     */
    initToolbar: function() {
        // use toolbar from gridPanel
        this.tbar = new Ext.Toolbar({
            defaults: {height: 55},
            items: [{
                xtype: 'buttongroup',
                columns: 5,
                items: [
                    Ext.apply(new Ext.Button(this.action_reply), {
                        scale: 'medium',
                        rowspan: 2,
                        iconAlign: 'top'
                    }),
                    Ext.apply(new Ext.Button(this.action_replyAll), {
                        scale: 'medium',
                        rowspan: 2,
                        iconAlign: 'top'
                    }),
                    Ext.apply(new Ext.Button(this.action_forward), {
                        scale: 'medium',
                        rowspan: 2,
                        iconAlign: 'top'
                    }), 
                    Ext.apply(new Ext.SplitButton(this.action_print), {
                        scale: 'medium',
                        rowspan: 2,
                        iconAlign:'top',
                        arrowAlign:'right'
                    }), 
                    Ext.apply(new Ext.Button(this.action_download), {
                        scale: 'medium',
                        rowspan: 2,
                        iconAlign:'top',
                        arrowAlign:'right'
                    })
                ]
            }, '->', {
                xtype: 'buttongroup',
                columns: 1,
                items: [
                    Ext.apply(new Ext.Button(this.action_deleteRecord), {
                        scale: 'medium',
                        rowspan: 2,
                        iconAlign: 'top'
                    })
                ]
            }]
        });
        
    },
    
    /**
     * after render
     */
    afterRender: function() {
        this.supr().afterRender.apply(this, arguments);
        this.showMessage();

        var title = this.record.get('subject');
        if (title !== undefined) {
            // TODO make this work for attachment mails
            this.window.setTitle(title);
        }
    },
    
    /**
     * show message
     */
    showMessage: function() {
        this.layout.setActiveItem(this.getSingleRecordPanel());
        this.updateDetails(this.record, this.getSingleRecordPanel().body);
    },
    
    /**
     * executed after a msg compose
     * 
     * @param {String} composedMsg
     * @param {String} action
     * @param {Array}  [affectedMsgs]  messages affected 
     * 
     */
    onAfterCompose: function(composedMsg, action, affectedMsgs) {
        this.fireEvent('update', composedMsg, action, affectedMsgs);
    },
    
    /**
     * executed after deletion of this message
     */
    onAfterDelete: function() {
        this.fireEvent('remove', Ext.encode(this.record.data));
        this.window.close();
    },
    
    /**
     * download message
     */
    onMessageDownload: function() {
        var downloader = new Ext.ux.file.Download({
            params: {
                method: 'Felamimail.downloadMessage',
                requestType: 'HTTP',
                messageId: this.record.id
            }
        }).start();
    },
    
    /**
     * delete message handler
     */
    onMessageDelete: function(force) {
        var mainApp = Ext.ux.PopupWindowMgr.getMainWindow().Tine.Tinebase.appMgr.get('Felamimail'),
            folderId = this.record.get('folder_id'),
            folder = mainApp.getFolderStore().getById(folderId),
            accountId = folder ? folder.get('account_id') : null,
            account = Tine.Felamimail.loadAccountStore().getById(accountId),
            trashId = account ? account.getTrashFolderId() : null;
            
        this.loadMask.show();
        if (trashId) {
            var filter = [{field: 'id', operator: 'equals', value: this.record.id}];
            
            Tine.Felamimail.messageBackend.moveMessages(filter, trashId, { 
                callback: this.onAfterDelete.createDelegate(this, ['move'])
            });
        } else {
            Tine.Felamimail.messageBackend.addFlags(this.record.id, '\\Deleted', { 
                callback: this.onAfterDelete.createDelegate(this, ['flag'])
            });
        }
    },
    
    /**
     * reply message handler
     */
    onMessageReplyTo: function(toAll) {
        Tine.Felamimail.MessageEditDialog.openWindow({
            replyTo : Ext.encode(this.record.data),
            replyToAll: toAll,
            listeners: {
                'update': this.onAfterCompose.createDelegate(this, ['reply', Ext.encode([this.record.data])], 1)
            }
        });
    },
    
    /**
     * forward message handler
     */
    onMessageForward: function() {
        Tine.Felamimail.MessageEditDialog.openWindow({
            forwardMsgs : Ext.encode([this.record.data]),
            listeners: {
                'update': this.onAfterCompose.createDelegate(this, ['forward', Ext.encode([this.record.data])], 1)
            }
        });
    },
    
    onMessagePrint: Tine.Felamimail.GridPanel.prototype.onPrint,
    onMessagePrintPreview: Tine.Felamimail.GridPanel.prototype.onPrintPreview
});

Tine.Felamimail.MessageDisplayDialog.openWindow = function (config) {
    var id = (config.record && config.record.id) ? config.record.id : 0;
    var window = Tine.WindowFactory.getWindow({
        width: 800,
        height: 700,
        name: 'TineFelamimailMessageDisplayDialog_' + id,
        contentPanelConstructor: 'Tine.Felamimail.MessageDisplayDialog',
        contentPanelConstructorConfig: config
    });
    return window;
};

// file: /home/lkneschke/temp/tine20build/temp/tine20/Felamimail/js/MessageEditDialog.js
/*
 * Tine 2.0
 * 
 * @package     Felamimail
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Philipp Schüle <p.schuele@metaways.de>
 * @copyright   Copyright (c) 2009-2011 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: MessageEditDialog.js 18957 2011-01-31 13:15:16Z p.schuele@metaways.de $
 *
 */
 
Ext.namespace('Tine.Felamimail');

/**
 * @namespace   Tine.Felamimail
 * @class       Tine.Felamimail.MessageEditDialog
 * @extends     Tine.widgets.dialog.EditDialog
 * 
 * <p>Message Compose Dialog</p>
 * <p>This dialog is for composing emails with recipients, body and attachments. 
 * you can choose from which account you want to send the mail.</p>
 * <p>
 * TODO         make email note editable
 * </p>
 * 
 * @author      Philipp Schüle <p.schuele@metaways.de>
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @version     $Id: MessageEditDialog.js 18957 2011-01-31 13:15:16Z p.schuele@metaways.de $
 * 
 * @param       {Object} config
 * @constructor
 * Create a new MessageEditDialog
 */
 Tine.Felamimail.MessageEditDialog = Ext.extend(Tine.widgets.dialog.EditDialog, {
    /**
     * @cfg {Array/String} bcc
     * initial config for bcc
     */
    bcc: null,
    
    /**
     * @cfg {String} body
     */
    msgBody: '',
    
    /**
     * @cfg {Array/String} cc
     * initial config for cc
     */
    cc: null,
    
    /**
     * @cfg {Array} of Tine.Felamimail.Model.Message (optionally encoded)
     * messages to forward
     */
    forwardMsgs: null,
    
    /**
     * @cfg {String} accountId
     * the accout id this message is sent from
     */
    accountId: null,
    
    /**
     * @cfg {Tine.Felamimail.Model.Message} (optionally encoded)
     * message to reply to
     */
    replyTo: null,

    /**
     * @cfg {Tine.Felamimail.Model.Message} (optionally encoded)
     * message to use as draft/template
     */
    draftOrTemplate: null,
    
    /**
     * @cfg {Boolean} (defaults to false)
     */
    replyToAll: false,
    
    /**
     * @cfg {String} subject
     */
    subject: '',
    
    /**
     * @cfg {Array/String} to
     * initial config for to
     */
    to: null,
    
    /**
     * @private
     */
    windowNamePrefix: 'MessageEditWindow_',
    appName: 'Felamimail',
    recordClass: Tine.Felamimail.Model.Message,
    recordProxy: Tine.Felamimail.messageBackend,
    loadRecord: false,
    evalGrants: false,
    
    bodyStyle:'padding:0px',
    
    /**
     * overwrite update toolbars function (we don't have record grants)
     * @private
     */
    updateToolbars: Ext.emptyFn,
    
    /**
     * init buttons
     */
    initButtons: function() {
        this.fbar = [];
        
        this.action_send = new Ext.Action({
            text: this.app.i18n._('Send'),
            handler: this.onSaveAndClose,
            iconCls: 'FelamimailIconCls',
            disabled: false,
            scope: this
        });

        this.action_searchContacts = new Ext.Action({
            text: this.app.i18n._('Search Recipients'),
            handler: this.onSearchContacts,
            iconCls: 'AddressbookIconCls',
            disabled: false,
            scope: this
        });
        
        this.action_saveAsDraft = new Ext.Action({
            text: this.app.i18n._('Save As Draft'),
            handler: this.onSaveInFolder.createDelegate(this, ['drafts_folder']),
            iconCls: 'action_saveAsDraft',
            disabled: false,
            scope: this
        });

        this.action_saveAsTemplate = new Ext.Action({
            text: this.app.i18n._('Save As Template'),
            handler: this.onSaveInFolder.createDelegate(this, ['templates_folder']),
            iconCls: 'action_saveAsTemplate',
            disabled: false,
            scope: this
        });
        
        // TODO think about changing icon onToggle
        this.action_saveEmailNote = new Ext.Action({
            text: this.app.i18n._('Save Email Note'),
            handler: this.onToggleSaveNote,
            iconCls: 'notes_noteIcon',
            disabled: false,
            scope: this,
            enableToggle: true
        });
        this.button_saveEmailNote = Ext.apply(new Ext.Button(this.action_saveEmailNote), {
            tooltip: this.app.i18n._('Activate this toggle button to save the email text as a note attached to the recipient(s) contact(s).')
        });

        this.tbar = new Ext.Toolbar({
            defaults: {height: 55},
            items: [{
                xtype: 'buttongroup',
                columns: 5,
                items: [
                    Ext.apply(new Ext.Button(this.action_send), {
                        scale: 'medium',
                        rowspan: 2,
                        iconAlign: 'top'
                    }),
                    Ext.apply(new Ext.Button(this.action_searchContacts), {
                        scale: 'medium',
                        rowspan: 2,
                        iconAlign: 'top',
                        tooltip: this.app.i18n._('Click to search for and add recipients from the Addressbook.')
                    }),
                    this.action_saveAsDraft,
                    this.button_saveEmailNote,
                    this.action_saveAsTemplate
                ]
            }]
        });
    },
    
    /**
     * @private
     */
    initRecord: function() {
        this.decodeMsgs();
        
        if (! this.record) {
            this.record = new this.recordClass(Tine.Felamimail.Model.Message.getDefaultData(), 0);
        }
        
        this.initFrom();
        this.initRecipients();
        this.initSubject();
        this.initContent();
        
        // legacy handling:...
        // TODO add this information to attachment(s) + flags and remove this
        if (this.replyTo) {
            this.record.set('flags', '\\Answered');
            this.record.set('original_id', this.replyTo.id);
        } else if (this.forwardMsgs) {
            this.record.set('flags', 'Passed');
            this.record.set('original_id', this.forwardMsgs[0].id);
        } else if (this.draftOrTemplate) {
            this.record.set('original_id', this.draftOrTemplate.id);
        }
    },
    
    /**
     * init attachments when forwarding message
     * 
     * @param {Tine.Felamimail.Model.Message} message
     */
    initAttachements: function(message) {
        if (message.get('attachments').length > 0) {
            this.record.set('attachments', [{
                name: message.get('subject'),
                type: 'message/rfc822',
                size: message.get('size'),
                id: message.id
            }]);
        }
    },
    
    /**
     * inits body and attachments from reply/forward/template
     */
    initContent: function() {
        if (! this.record.get('body')) {
            if (! this.msgBody) {
                var message = this.getMessageFromConfig();
                          
                if (message) {
                    if (! message.bodyIsFetched()) {
                        // self callback when body needs to be fetched
                        return this.recordProxy.fetchBody(message, this.initContent.createDelegate(this));
                    }
                    
                    this.msgBody = message.get('body');
                    
                    var account = Tine.Felamimail.loadAccountStore().getById(this.record.get('account_id'));
                    if (account.get('display_format') == 'plain' || (account.get('display_format') == 'content_type' && message.get('content_type') == 'text/plain')) {
                        this.msgBody = Ext.util.Format.nl2br(this.msgBody);
                    }
                    
                    if (this.replyTo) {
                        this.msgBody = /*'<br/>' + */Ext.util.Format.htmlEncode(this.replyTo.get('from_name')) + ' ' + this.app.i18n._('wrote') + ':<br/>'
                             + '<blockquote class="felamimail-body-blockquote">' + this.msgBody + '</blockquote><br/>';
                    } else if (this.forwardMsgs && this.forwardMsgs.length === 1) {
                        this.msgBody = '<br/>-----' + this.app.i18n._('Original message') + '-----<br/>'
                            + Tine.Felamimail.GridPanel.prototype.formatHeaders(this.forwardMsgs[0].get('headers'), false, true) + '<br/><br/>'
                            + this.msgBody + '<br/>';
                        this.initAttachements(message);
                    } else if (this.draftOrTemplate) {
                        this.initAttachements(message);
                    }
                }
            }
            
            if (! this.draftOrTemplate) {
                this.msgBody += Tine.Felamimail.getSignature(this.record.get('account_id'))
            }
        
            this.record.set('body', this.msgBody);
        }
        
        delete this.msgBody;
        this.onRecordLoad();
    },
    
    /**
     * inits / sets sender of message
     */
    initFrom: function() {
        if (! this.record.get('account_id')) {
            if (! this.accountId) {
                var mainApp = Ext.ux.PopupWindowMgr.getMainWindow().Tine.Tinebase.appMgr.get('Felamimail'),
                    message = this.getMessageFromConfig(),
                    folderId = message ? message.get('folder_id') : null, 
                    folder = folderId ? mainApp.getFolderStore().getById(folderId) : null
                    accountId = folder ? folder.get('account_id') : null;
                    
                this.accountId = accountId || mainApp.getActiveAccount().id;
            }
            
            this.record.set('account_id', this.accountId);
        }
        delete this.accountId;
    },
    
    /**
     * after render
     */
    afterRender: function() {
        Tine.Felamimail.MessageEditDialog.superclass.afterRender.apply(this, arguments);
        
        this.getEl().on(Ext.EventManager.useKeydown ? 'keydown' : 'keypress', this.onKeyPress, this);
        this.recipientGrid.on('specialkey', function(field, e) {
            this.onKeyPress(e);
        }, this);
        
        this.recipientGrid.on('blur', function(editor) {
            // do not let the blur event reach the editor grid if we want the subjectField to have focus
            if (this.subjectField.hasFocus) {
                return false;
            }
        }, this);
        
        this.htmlEditor.on('keydown', function(e) {
            if (e.getKey() == e.ENTER && e.ctrlKey) {
                this.onSaveAndClose();
            }
        }, this);
    },
    
    /**
     * on key press
     * @param {} e
     * @param {} t
     * @param {} o
     */
    onKeyPress: function(e, t, o) {
        if ((e.getKey() == e.TAB || e.getKey() == e.ENTER) && ! e.shiftKey) {
            if (e.getTarget('input[name=subject]')) {
                this.htmlEditor.focus.defer(50, this.htmlEditor);
            } else if (e.getTarget('input[type=text]')) {
                this.subjectField.focus.defer(50, this.subjectField);
            }
        }
    },
    
    /**
     * returns message passed with config
     * 
     * @return {Tine.Felamimail.Model.Message}
     */
    getMessageFromConfig: function() {
        return this.replyTo ? this.replyTo : 
               this.forwardMsgs && this.forwardMsgs.length === 1 ? this.forwardMsgs[0] :
               this.draftOrTemplate ? this.draftOrTemplate : null;
    },
    
    /**
     * inits to/cc/bcc
     */
    initRecipients: function() {
        if (this.replyTo) {
            var replyTo = this.replyTo.get('headers')['reply-to'];
            
            this.to = [replyTo ? replyTo : this.replyTo.get('from_name') + ' <' + this.replyTo.get('from_email') + '>'];
                
            if (this.replyToAll) {
                this.to = this.to.concat(this.replyTo.get('to'));
                this.cc = this.replyTo.get('cc');
                
                // remove own email from to/cc
                var account = Tine.Felamimail.loadAccountStore().getById(this.record.get('account_id'));
                var emailRegexp = new RegExp(account.get('email'));
                Ext.each(['to', 'cc'], function(field) {
                    for (var i=0; i < this[field].length; i++) {
                        if (emailRegexp.test(this[field][i])) {
                            this[field].splice(i, 1);
                        }
                    }
                }, this);
            }
        }
        
        Ext.each(['to', 'cc', 'bcc'], function(field) {
            if (this.draftOrTemplate) {
                this[field] = this.draftOrTemplate.get(field);
            }
            
            if (! this.record.get(field)) {
                this[field] = Ext.isArray(this[field]) ? this[field] : Ext.isString(this[field]) ? [this[field]] : [];
                this.record.set(field, Ext.unique(this[field]));
            }
            delete this[field];
        }, this);
    },
    
    /**
     * sets / inits subject
     */
    initSubject: function() {
        if (! this.record.get('subject')) {
            if (! this.subject) {
                if (this.replyTo) {
                    // check if there is already a 'Re:' prefix
                    var replyPrefix = this.app.i18n._('Re:'),
                        signatureRegexp = new RegExp('^' + replyPrefix),
                        replySubject = (this.replyTo.get('subject')) ? this.replyTo.get('subject') : '';
                        
                    if (! replySubject.match(signatureRegexp)) {
                        this.subject = replyPrefix + ' ' +  replySubject;
                    } else {
                        this.subject = replySubject;
                    }
                } else if (this.forwardMsgs) {
                    this.subject =  this.app.i18n._('Fwd:') + ' ';
                    this.subject += this.forwardMsgs.length === 1 ?
                        this.forwardMsgs[0].get('subject') :
                        String.format(this.app.i18n._('{0} Message', '{0} Messages', this.forwardMsgs.length));
                } else if (this.draftOrTemplate) {
                    this.subject = this.draftOrTemplate.get('subject');
                }
            }
            this.record.set('subject', this.subject);
        }
        
        delete this.subject;
    },
    
    /**
     * decode this.replyTo / this.forwardMsgs from interwindow json transport
     */
    decodeMsgs: function() {
        if (Ext.isString(this.draftOrTemplate)) {
            this.draftOrTemplate = new this.recordClass(Ext.decode(this.draftOrTemplate));
        }
        
        if (Ext.isString(this.replyTo)) {
            this.replyTo = new this.recordClass(Ext.decode(this.replyTo));
        }
        
        if (Ext.isString(this.forwardMsgs)) {
            var msgs = [];
            Ext.each(Ext.decode(this.forwardMsgs), function(msg) {
                msgs.push(new this.recordClass(msg));
            }, this);
            
            this.forwardMsgs = msgs;
        }
    },
    
    /**
     * fix input fields layout
     */
    fixLayout: function() {
        
        if (! this.subjectField.rendered || ! this.accountCombo.rendered || ! this.recipientGrid.rendered) {
            return;
        }
        
        var scrollWidth = this.recipientGrid.getView().getScrollOffset();
        this.subjectField.setWidth(this.subjectField.getWidth() - scrollWidth + 1);
        this.accountCombo.setWidth(this.accountCombo.getWidth() - scrollWidth + 1);
    },
    
    /**
     * save message in folder
     * 
     * @param {String} folderField
     */
    onSaveInFolder: function (folderField) {
        this.onRecordUpdate();
        
        var account = Tine.Felamimail.loadAccountStore().getById(this.record.get('account_id'));
        var folderName = account.get(folderField);
        
        if (! folderName || folderName == '') {
            Ext.MessageBox.alert(
                this.app.i18n._('Failed'), 
                String.format(this.app.i18n._('{0} account setting empty.'), folderField)
            );
        } else if (this.isValid()) {
            this.loadMask.show();
            this.recordProxy.saveInFolder(this.record, folderName, {
                scope: this,
                success: function(record) {
                    this.fireEvent('update', Ext.util.JSON.encode(this.record.data));
                    this.purgeListeners();
                    this.window.close();
                },
                failure: this.onRequestFailed,
                timeout: 150000 // 3 minutes
            });
        } else {
            Ext.MessageBox.alert(_('Errors'), _('Please fix the errors noted.'));
        }
    },
    
    /**
     * toggle save note
     * 
     * @param {} button
     * @param {} e
     */
    onToggleSaveNote: function (button, e) {
        this.record.set('note', (! this.record.get('note')));
    },
    
    /**
     * search for contacts as recipients
     */
    onSearchContacts: function() {
        Tine.Felamimail.RecipientPickerDialog.openWindow({
            record: new this.recordClass(Ext.copyTo({}, this.record.data, ['subject', 'to', 'cc', 'bcc']), Ext.id()),
            listeners: {
                scope: this,
                'update': function(record) {
                    var messageWithRecipients = Ext.isString(record) ? new this.recordClass(Ext.decode(record)) : record;
                    this.recipientGrid.syncRecipientsToStore(['to', 'cc', 'bcc'], messageWithRecipients, true, true);
                }
            }
        });
    },
    
    /**
     * executed after record got updated from proxy
     * 
     * @private
     */
    onRecordLoad: function() {
        // interrupt process flow till dialog is rendered
        if (! this.rendered) {
            this.onRecordLoad.defer(250, this);
            return;
        }
        
        var title = this.app.i18n._('Compose email:');
        if (this.record.get('subject')) {
            title = title + ' ' + this.record.get('subject');
        }
        this.window.setTitle(title);
        
        this.getForm().loadRecord(this.record);
        this.attachmentGrid.loadRecord(this.record);
        
        if (this.record.get('note') && this.record.get('note') == '1') {
            this.button_saveEmailNote.toggle();
        }
        
        this.loadMask.hide();
    },
        
    /**
     * executed when record gets updated from form
     * - add attachments to record here
     * 
     * @private
     */
    onRecordUpdate: function() {

        this.record.data.attachments = [];
        var attachmentData = null;
        
        this.attachmentGrid.store.each(function(attachment) {
            this.record.data.attachments.push(Ext.ux.file.Uploader.file.getFileData(attachment));
        }, this);
        
        Tine.Felamimail.MessageEditDialog.superclass.onRecordUpdate.call(this);

        /*
        if (this.record.data.note) {
            // show message box with note editing textfield
            //console.log(this.record.data.note);
            Ext.Msg.prompt(
                this.app.i18n._('Add Note'),
                this.app.i18n._('Edit Email Note Text:'), 
                function(btn, text) {
                    if (btn == 'ok'){
                        record.data.note = text;
                    }
                }, 
                this,
                100, // height of input area
                this.record.data.body 
            );
        }
        */
    },
    
    /**
     * show error if request fails
     * 
     * @param {} response
     * @param {} request
     * @private
     * 
     * TODO mark field(s) invalid if for example email is incorrect
     * TODO add exception dialog on critical errors?
     */
    onRequestFailed: function(response, request) {
        Ext.MessageBox.alert(
            this.app.i18n._('Failed'), 
            String.format(this.app.i18n._('Could not send {0}.'), this.i18nRecordName) 
                + ' ( ' + this.app.i18n._('Error:') + ' ' + response.message + ')'
        ); 
        this.loadMask.hide();
    },
    
    /**
     * if 'account_id' is changed we need to update the signature
     * 
     * @param {} combo
     * @param {} newValue
     * @param {} oldValue
     */
     onFromSelect: function(combo, record, index) {
        // get new signature
        var newSignature = Tine.Felamimail.getSignature(record.id);
        var signatureRegexp = new RegExp('<br><br><span id="felamimail\-body\-signature">\-\-<br>.*</span>');
        
        // update signature
        var bodyContent = this.htmlEditor.getValue();
        bodyContent = bodyContent.replace(signatureRegexp, newSignature);
        
        this.htmlEditor.setValue(bodyContent);
    },
    
    /**
     * init attachment grid + add button to toolbar
     */
    initAttachmentGrid: function() {
        if (! this.attachmentGrid) {
        
            this.attachmentGrid = new Tine.widgets.grid.FileUploadGrid({
                fieldLabel: this.app.i18n._('Attachments'),
                hideLabel: true,
                filesProperty: 'attachments',
                // TODO     think about that -> when we deactivate the top toolbar, we lose the dropzone for files!
                //showTopToolbar: false,
                anchor: '100% 95%'
            });
            
            // add file upload button to toolbar
            this.action_addAttachment = this.attachmentGrid.getAddAction();
            this.action_addAttachment.plugins[0].dropElSelector = null;
            this.action_addAttachment.plugins[0].onBrowseButtonClick = function() {
                this.southPanel.expand();
            }.createDelegate(this);
            
            this.tbar.get(0).insert(1, Ext.apply(new Ext.Button(this.action_addAttachment), {
                scale: 'medium',
                rowspan: 2,
                iconAlign: 'top'
            }));
        }
    },
    
    /**
     * returns dialog
     * 
     * NOTE: when this method gets called, all initialisation is done.
     * 
     * @return {Object}
     * @private
     */
    getFormItems: function() {
        
        this.initAttachmentGrid();
        
        this.recipientGrid = new Tine.Felamimail.RecipientGrid({
            record: this.record,
            i18n: this.app.i18n,
            hideLabel: true
        });
        
        this.southPanel = new Ext.Panel({
            region: 'south',
            layout: 'form',
            height: 150,
            split: true,
            collapseMode: 'mini',
            header: false,
            collapsible: true,
            collapsed: (this.record.bodyIsFetched() && (! this.record.get('attachments') || this.record.get('attachments').length == 0)),
            items: [this.attachmentGrid]
        });

        this.htmlEditor = new Tine.Felamimail.ComposeEditor({
            fieldLabel: this.app.i18n._('Body'),
            flex: 1  // Take up all *remaining* vertical space
        });
        
        var accountStore = Tine.Felamimail.loadAccountStore();
        
        return {
            border: false,
            frame: true,
            layout: 'border',
            items: [
                {
                region: 'center',
                layout: {
                    align: 'stretch',  // Child items are stretched to full width
                    type: 'vbox'
                },
                listeners: {
                    'afterlayout': this.fixLayout,
                    scope: this
                },
                items: [{
                    xtype:'combo',
                    name: 'account_id',
                    ref: '../../accountCombo',
                    plugins: [ Ext.ux.FieldLabeler ],
                    fieldLabel: this.app.i18n._('From'),
                    displayField: 'name',
                    valueField: 'id',
                    editable: false,
                    triggerAction: 'all',
                    store: accountStore,
                    listeners: {
                        scope: this,
                        select: this.onFromSelect
                    }
                }, this.recipientGrid, 
                {
                    xtype:'textfield',
                    plugins: [ Ext.ux.FieldLabeler ],
                    fieldLabel: this.app.i18n._('Subject'),
                    name: 'subject',
                    ref: '../../subjectField',
                    enableKeyEvents: true,
                    listeners: {
                        scope: this,
                        // update title on keyup event
                        'keyup': function(field, e) {
                            if (! e.isSpecialKey()) {
                                this.window.setTitle(
                                    this.app.i18n._('Compose email:') + ' ' 
                                    + field.getValue()
                                );
                            }
                        }
                    }
                }, this.htmlEditor
                ]
            }, this.southPanel]
        };
    },

    /**
     * is form valid (checks if attachments are still uploading)
     * 
     * @return {Boolean}
     */
    isValid: function() {
        var result = (! this.attachmentGrid.isUploading());
        return (result && Tine.Felamimail.MessageEditDialog.superclass.isValid.call(this));
    }
        
});

/**
 * Felamimail Edit Popup
 * 
 * @param   {Object} config
 * @return  {Ext.ux.Window}
 */
Tine.Felamimail.MessageEditDialog.openWindow = function (config) {
    var window = Tine.WindowFactory.getWindow({
        width: 700,
        height: 700,
        name: Tine.Felamimail.MessageEditDialog.prototype.windowNamePrefix + Ext.id(),
        contentPanelConstructor: 'Tine.Felamimail.MessageEditDialog',
        contentPanelConstructorConfig: config
    });
    return window;
};

// file: /home/lkneschke/temp/tine20build/temp/tine20/Felamimail/js/AccountEditDialog.js
/*
 * Tine 2.0
 * 
 * @package     Felamimail
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Philipp Schuele <p.schuele@metaways.de>
 * @copyright   Copyright (c) 2009-2010 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: AccountEditDialog.js 17485 2010-12-01 10:57:05Z p.schuele@metaways.de $
 *
 */
 
Ext.namespace('Tine.Felamimail');

/**
 * @namespace   Tine.Felamimail
 * @class       Tine.Felamimail.AccountEditDialog
 * @extends     Tine.widgets.dialog.EditDialog
 * 
 * <p>Account Edit Dialog</p>
 * <p>
 * </p>
 * 
 * @author      Philipp Schuele <p.schuele@metaways.de>
 * @copyright   Copyright (c) 2009 Metaways Infosystems GmbH (http://www.metaways.de)
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @version     $Id:GridPanel.js 7170 2009-03-05 10:58:55Z p.schuele@metaways.de $
 * 
 * @param       {Object} config
 * @constructor
 * Create a new Tine.Felamimail.AccountEditDialog
 * 
 */
Tine.Felamimail.AccountEditDialog = Ext.extend(Tine.widgets.dialog.EditDialog, {
    
    /**
     * @private
     */
    windowNamePrefix: 'AccountEditWindow_',
    appName: 'Felamimail',
    recordClass: Tine.Felamimail.Model.Account,
    recordProxy: Tine.Felamimail.accountBackend,
    loadRecord: false,
    tbarItems: [],
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
     * -> only allow to change some of the fields if it is a system account
     */
    onRecordLoad: function() {
        Tine.Felamimail.AccountEditDialog.superclass.onRecordLoad.call(this);
        
        // if account type == system disable most of the input fields
        if (this.record.get('type') == 'system') {
            this.getForm().items.each(function(item) {
                // only enable some fields
                switch(item.name) {
                    case 'signature':
                        break;
                    default:
                        item.setDisabled(true);
                }
            }, this);
        }
    },    
    
    /**
     * returns dialog
     * 
     * NOTE: when this method gets called, all initalisation is done.
     * @private
     */
    getFormItems: function() {

        this.signatureEditor = new Ext.form.HtmlEditor({
            fieldLabel: this.app.i18n._('Signature'),
            name: 'signature',
            allowBlank: true,
            height: 220,
            getDocMarkup: function(){
                var markup = '<span id="felamimail\-body\-signature">'
                    + '</span>';
                return markup;
            },
            plugins: [
                new Ext.ux.form.HtmlEditor.RemoveFormat()
            ]
        });
        
        return {
            xtype: 'tabpanel',
            deferredRender: false,
            border: false,
            activeTab: 0,
            items: [{
                title: this.app.i18n._('Account'),
                autoScroll: true,
                border: false,
                frame: true,
                xtype: 'columnform',
                formDefaults: {
                    xtype:'textfield',
                    anchor: '90%',
                    labelSeparator: '',
                    maxLength: 256,
                    columnWidth: 1
                },
                items: [[{
                    fieldLabel: this.app.i18n._('Account Name'),
                    name: 'name',
                    allowBlank: false
                }, {
                    fieldLabel: this.app.i18n._('User Email'),
                    name: 'email',
                    allowBlank: false,
                    vtype: 'email'
                }, {
                    fieldLabel: this.app.i18n._('User Name (From)'),
                    name: 'from'
                }, {
                    fieldLabel: this.app.i18n._('Organization'),
                    name: 'organization'
                }, this.signatureEditor
                ]]
            }, {
                title: this.app.i18n._('IMAP'),
                autoScroll: true,
                border: false,
                frame: true,
                xtype: 'columnform',
                formDefaults: {
                    xtype:'textfield',
                    anchor: '90%',
                    labelSeparator: '',
                    maxLength: 256,
                    columnWidth: 1
                },
                items: [[{
                    fieldLabel: this.app.i18n._('Host'),
                    name: 'host',
                    allowBlank: false
                }, {
                    fieldLabel: this.app.i18n._('Port (Default: 143 / SSL: 993)'),
                    name: 'port',
                    allowBlank: false,
                    maxLength: 5,
                    xtype: 'numberfield'
                }, {
                    fieldLabel: this.app.i18n._('Secure Connection'),
                    name: 'ssl',
                    typeAhead     : false,
                    triggerAction : 'all',
                    lazyRender    : true,
                    editable      : false,
                    mode          : 'local',
                    forceSelection: true,
                    value: 'none',
                    xtype: 'combo',
                    store: [
                        ['none', this.app.i18n._('None')],
                        ['tls',  this.app.i18n._('TLS')],
                        ['ssl',  this.app.i18n._('SSL')]
                    ]
                },{
                    fieldLabel: this.app.i18n._('Username'),
                    name: 'user',
                    allowBlank: false
                }, {
                    fieldLabel: this.app.i18n._('Password'),
                    name: 'password',
                    emptyText: 'password',
                    inputType: 'password'
                }]]
            }, {               
                title: this.app.i18n._('SMTP'),
                autoScroll: true,
                border: false,
                frame: true,
                xtype: 'columnform',
                formDefaults: {
                    xtype:'textfield',
                    anchor: '90%',
                    labelSeparator: '',
                    maxLength: 256,
                    columnWidth: 1
                },
                items: [[ {
                    fieldLabel: this.app.i18n._('Host'),
                    name: 'smtp_hostname'
                }, {
                    fieldLabel: this.app.i18n._('Port (Default: 25)'),
                    name: 'smtp_port',
                    maxLength: 5,
                    xtype:'numberfield',
                    allowBlank: false
                }, {
                    fieldLabel: this.app.i18n._('Secure Connection'),
                    name: 'smtp_ssl',
                    typeAhead     : false,
                    triggerAction : 'all',
                    lazyRender    : true,
                    editable      : false,
                    mode          : 'local',
                    value: 'none',
                    xtype: 'combo',
                    store: [
                        ['none', this.app.i18n._('None')],
                        ['tls',  this.app.i18n._('TLS')],
                        ['ssl',  this.app.i18n._('SSL')]
                    ]
                }, {
                    fieldLabel: this.app.i18n._('Authentication'),
                    name: 'smtp_auth',
                    typeAhead     : false,
                    triggerAction : 'all',
                    lazyRender    : true,
                    editable      : false,
                    mode          : 'local',
                    xtype: 'combo',
                    value: 'login',
                    store: [
                        ['none',    this.app.i18n._('None')],
                        ['login',   this.app.i18n._('Login')],
                        ['plain',   this.app.i18n._('Plain')]
                    ]
                },{
                    fieldLabel: this.app.i18n._('Username (optional)'),
                    name: 'smtp_user'
                }, {
                    fieldLabel: this.app.i18n._('Password (optional)'),
                    name: 'smtp_password',
                    emptyText: 'password',
                    inputType: 'password'
                }]]
            }, {
                title: this.app.i18n._('Sieve'),
                autoScroll: true,
                border: false,
                frame: true,
                xtype: 'columnform',
                formDefaults: {
                    xtype:'textfield',
                    anchor: '90%',
                    labelSeparator: '',
                    maxLength: 256,
                    columnWidth: 1
                },
                items: [[{
                    fieldLabel: this.app.i18n._('Host'),
                    name: 'sieve_hostname',
                    maxLength: 64
                }, {
                    fieldLabel: this.app.i18n._('Port (Default: 2000)'),
                    name: 'sieve_port',
                    maxLength: 5,
                    xtype:'numberfield'
                }, {
                    fieldLabel: this.app.i18n._('Secure Connection'),
                    name: 'sieve_ssl',
                    typeAhead     : false,
                    triggerAction : 'all',
                    lazyRender    : true,
                    editable      : false,
                    mode          : 'local',
                    value: 'none',
                    xtype: 'combo',
                    store: [
                        ['none', this.app.i18n._('None')],
                        ['tls',  this.app.i18n._('TLS')]
                    ]
                }]]
            }, {
                title: this.app.i18n._('Other Settings'),
                autoScroll: true,
                border: false,
                frame: true,
                xtype: 'columnform',
                formDefaults: {
                    xtype:'textfield',
                    anchor: '90%',
                    labelSeparator: '',
                    maxLength: 256,
                    columnWidth: 1
                },
                items: [[{
                    fieldLabel: this.app.i18n._('Sent Folder Name'),
                    name: 'sent_folder',
                    xtype: 'felamimailfolderselect',
                    account: this.record,
                    maxLength: 64
                }, {
                    fieldLabel: this.app.i18n._('Trash Folder Name'),
                    name: 'trash_folder',
                    xtype: 'felamimailfolderselect',
                    account: this.record,
                    maxLength: 64
                }, {
                    fieldLabel: this.app.i18n._('Drafts Folder Name'),
                    name: 'drafts_folder',
                    xtype: 'felamimailfolderselect',
                    account: this.record,
                    maxLength: 64
                }, {
                    fieldLabel: this.app.i18n._('Templates Folder Name'),
                    name: 'templates_folder',
                    xtype: 'felamimailfolderselect',
                    account: this.record,
                    maxLength: 64
                }, {
                    fieldLabel: this.app.i18n._('Display Format'),
                    name: 'display_format',
                    typeAhead     : false,
                    triggerAction : 'all',
                    lazyRender    : true,
                    editable      : false,
                    mode          : 'local',
                    forceSelection: true,
                    value: 'html',
                    xtype: 'combo',
                    store: [
                        ['html', this.app.i18n._('HTML')],
                        ['plain',  this.app.i18n._('Plain Text')],
                        ['content_type',  this.app.i18n._('Depending on content type (experimental)')]
                    ]
                }]]
            }]
        };
    },
    
    /**
     * generic request exception handler
     * 
     * @param {Object} exception
     */
    onRequestFailed: function(exception) {
        Tine.Felamimail.handleRequestException(exception);
        this.loadMask.hide();
    }    
});

/**
 * Felamimail Account Edit Popup
 * 
 * @param   {Object} config
 * @return  {Ext.ux.Window}
 */
 Tine.Felamimail.AccountEditDialog.openWindow = function (config) {
    var window = Tine.WindowFactory.getWindow({
        width: 500,
        height: 550,
        name: Tine.Felamimail.AccountEditDialog.prototype.windowNamePrefix + Ext.id(),
        contentPanelConstructor: 'Tine.Felamimail.AccountEditDialog',
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

// file: /home/lkneschke/temp/tine20build/temp/tine20/Felamimail/js/ContactSearchCombo.js
/*
 * Tine 2.0
 * 
 * @package     Felamimail
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Philipp Schuele <p.schuele@metaways.de>
 * @copyright   Copyright (c) 2009-2010 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: ContactSearchCombo.js 16958 2010-11-05 16:05:27Z p.schuele@metaways.de $
 *
 */
 
Ext.namespace('Tine.Felamimail');

/**
 * @namespace   Tine.Felamimail
 * @class       Tine.Felamimail.ContactSearchCombo
 * @extends     Tine.Addressbook.SearchCombo
 * 
 * <p>Email Search ComboBox</p>
 * <p></p>
 * <pre></pre>
 * 
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Philipp Schuele <p.schuele@metaways.de>
 * @copyright   Copyright (c) 2009 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: ContactSearchCombo.js 16958 2010-11-05 16:05:27Z p.schuele@metaways.de $
 * 
 * @param       {Object} config
 * @constructor
 * Create a new Tine.Felamimail.ContactSearchCombo
 */
Tine.Felamimail.ContactSearchCombo = Ext.extend(Tine.Addressbook.SearchCombo, {

    /**
     * @cfg {Boolean} forceSelection
     */
    forceSelection: false,
    
    /**
     * @private
     */
    initComponent: function() {
        // add additional filter to show only contacts with email addresses
        this.additionalFilters = [{field: 'email_query', operator: 'contains', value: '@' }];
        
        this.tpl = new Ext.XTemplate(
            '<tpl for="."><div class="search-item">',
                '{[this.encode(values.n_fileas)]}',
                ' (<b>{[this.encode(values.email, values.email_home)]}</b>)',
            '</div></tpl>',
            {
                encode: function(email, email_home) {
                    if (email) {
                        return Ext.util.Format.htmlEncode(email);
                    } else if (email_home) {
                        return Ext.util.Format.htmlEncode(email_home);
                    } else {
                        return '';
                    }
                }
            }
        );
        
        Tine.Felamimail.ContactSearchCombo.superclass.initComponent.call(this);
        
        this.store.on('load', this.onStoreLoad, this);
    },
    
    /**
     * override default onSelect
     * - set email/name as value
     * 
     * @param {} record
     * @private
     */
    onSelect: function(record) {
        var value = Tine.Felamimail.getEmailStringFromContact(record);
        this.setValue(value);
        
        this.collapse();
        this.fireEvent('blur', this);
    },
    
    /**
     * on load handler of combo store
     * -> add additional record if contact has multiple email addresses
     * 
     * @param {} store
     * @param {} records
     * @param {} options
     */
    onStoreLoad: function(store, records, options) {
        var index = 0,
            newRecord,
            recordData;
            
        Ext.each(records, function(record) {
            if (record.get('email') && record.get('email_home')) {
                index++;
                recordData = Ext.copyTo({}, record.data, ['email_home', 'n_fileas']);
                newRecord = Tine.Addressbook.contactBackend.recordReader({responseText: Ext.util.JSON.encode(recordData)});
                store.insert(index, [newRecord]);
            }
            index++;
        });
    }
});
Ext.reg('felamimailcontactcombo', Tine.Felamimail.ContactSearchCombo);

// file: /home/lkneschke/temp/tine20build/temp/tine20/Felamimail/js/RecipientGrid.js
/*
 * Tine 2.0
 * 
 * @package     Felamimail
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Philipp Schuele <p.schuele@metaways.de>
 * @copyright   Copyright (c) 2009-2010 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: RecipientGrid.js 18728 2011-01-18 18:58:42Z c.weiss@metaways.de $
 *
 */
 
Ext.namespace('Tine.Felamimail');

/**
 * @namespace   Tine.Felamimail
 * @class       Tine.Felamimail.RecipientGrid
 * @extends     Ext.grid.EditorGridPanel
 * 
 * <p>Recipient Grid Panel</p>
 * <p>grid panel for to/cc/bcc recipients</p>
 * <pre>
 * </pre>
 * 
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Philipp Schuele <p.schuele@metaways.de>
 * @copyright   Copyright (c) 2009 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id:MessageEditDialog.js 7170 2009-03-05 10:58:55Z p.schuele@metaways.de $
 * 
 * @param       {Object} config
 * @constructor
 * Create a new Tine.Felamimail.RecipientGrid
 */
Tine.Felamimail.RecipientGrid = Ext.extend(Ext.grid.EditorGridPanel, {
    
    /**
     * @private
     */
    cls: 'felamimail-recipient-grid',
    
    /**
     * the message record
     * @type Tine.Felamimail.Model.Message
     * @property record
     */
    record: null,
    
    /**
     * @type Ext.Menu
     * @property contextMenu
     */
    contextMenu: null,
    
    /**
     * @type Ext.data.SimpleStore
     * @property store
     */
    store: null,
    
    /**
     * @cfg {String} autoExpandColumn
     * auto expand column of grid
     */
    autoExpandColumn: 'address',
    
    /**
     * @cfg {Number} clicksToEdit
     * clicks to edit for editor grid panel
     */
    clicksToEdit:1,
    
    /**
     * @cfg {Number} numberOfRecordsForFixedHeight
     */
    numberOfRecordsForFixedHeight: 6,

    /**
     * @cfg {Boolean} header
     * show header
     */
    header: false,
    
    /**
     * @cfg {Boolean} border
     * show border
     */
    border: false,
    
    /**
     * @cfg {Boolean} deferredRender
     * deferred rendering
     */
    deferredRender: false,
    
    forceValidation: true,
    
    enableDrop: true,
    ddGroup: 'recipientDDGroup',

    /**
     * @private
     */
    initComponent: function() {
        
        this.initStore();
        this.initColumnModel();
        this.initActions();
        this.sm = new Ext.grid.RowSelectionModel();
        
        Tine.Felamimail.RecipientGrid.superclass.initComponent.call(this);
        
        this.on('rowcontextmenu', function(grid, row, e) {
            e.stopEvent();
            var selModel = grid.getSelectionModel();
            if (!selModel.isSelected(row)) {
                selModel.selectRow(row);
            }
            
            var record = this.store.getAt(row);
            this.action_remove.setDisabled(record.get('address') == '');
            
            this.contextMenu.showAt(e.getXY());
        }, this);
            
        this.on('beforeedit', this.onBeforeEdit, this);
        this.on('afteredit', this.onAfterEdit, this);
        this.on('validateedit', this.onValidateEdit, this);
    },
    
    /**
     * init store
     * @private
     */
    initStore: function() {
        this.store = new Ext.data.SimpleStore({
            fields   : ['type', 'address']
        });
        
        // init recipients (on reply/reply to all)
        this.syncRecipientsToStore(['to', 'cc']);
        
        this.store.add(new Ext.data.Record({type: 'to', 'address': ''}));
        
        this.store.on('update', this.onUpdateStore, this);
        this.store.on('add', this.onAddStore, this);
    },
    
    /**
     * init cm
     * @private
     */
    initColumnModel: function() {
        
        var app = Tine.Tinebase.appMgr.get('Felamimail');
        
        this.searchCombo = new Tine.Felamimail.ContactSearchCombo({
            listeners: {
                scope: this,
                specialkey: function(combo, e) {
                    // jump to subject if we are in the last row and it is empty
                    var sm = this.getSelectionModel(),
                        record = sm.getSelected();
                    if ((! record || record.get('address') == '') && ! sm.hasNext()) {
                        this.fireEvent('specialkey', combo, e);
                    }
                },
                blur: function(combo) {
                    // need to update record because we relay blur event and it might not be updated otherwise
                    var value = combo.getValue();
                    if (this.activeEditor && this.activeEditor.record.get('address') != value) {
                        this.activeEditor.record.set('address', value);
                    }
                }
            }
        });
        
        this.cm = new Ext.grid.ColumnModel([
            {
                resizable: true,
                id: 'type',
                dataIndex: 'type',
                width: 104,
                menuDisabled: true,
                header: 'type',
                renderer: function(value) {
                    var result = '',
                        qtip = app.i18n._('Click here to set To/CC/BCC.');

                    switch(value) {
                        case 'to':
                            result = app.i18n._('To:');
                            break;
                        case 'cc':
                            result = app.i18n._('Cc:');
                            break;
                        case 'bcc':
                            result = app.i18n._('Bcc:');
                            break;
                    }
                    
                    return '<div qtip="' + qtip +'">' + result + '</div>';
                },
                editor: new Ext.form.ComboBox({
                    typeAhead     : false,
                    triggerAction : 'all',
                    lazyRender    : true,
                    editable      : false,
                    mode          : 'local',
                    value         : null,
                    forceSelection: true,
                    store         : [
                        ['to',  app.i18n._('To:')],
                        ['cc',  app.i18n._('Cc:')],
                        ['bcc', app.i18n._('Bcc:')]
                    ]
                })
            },{
                resizable: true,
                menuDisabled: true,
                id: 'address',
                dataIndex: 'address',
                header: 'address',
                editor: this.searchCombo
            }
        ]);
    },
    
    /**
     * init actions / ctx menu
     * @private
     */
    initActions: function() {
        this.action_remove = new Ext.Action({
            text: _('Remove'),
            handler: this.onDelete,
            iconCls: 'action_delete',
            scope: this
        });
        
        this.contextMenu = new Ext.menu.Menu({
            items:  this.action_remove
        });        
    },
    
    /**
     * start editing after render
     * @private
     */
    afterRender: function() {
        Tine.Felamimail.RecipientGrid.superclass.afterRender.call(this);
        
        if (this.store.getCount() == 1) {
            this.startEditing.defer(200, this, [0, 1]);
        }
        
        this.setFixedHeight(true);
        
        this.relayEvents(this.searchCombo, ['blur' ]);
        
        this.initDropTarget();
    },
    
    /**
     * init drop target with notifyDrop fn 
     * - adds new records from drag data to the recipient store
     */
    initDropTarget: function() {
        var dropTargetEl = this.getView().scroller.dom;
        var dropTarget = new Ext.dd.DropTarget(dropTargetEl, {
            ddGroup    : 'recipientDDGroup',
            notifyDrop : function(ddSource, e, data) {
                this.grid.addRecordsToStore(ddSource.dragData.selections);
                return true;
            },
            grid: this
        });        
    },
    
    /**
     * add records to recipient store
     * 
     * @param {Array} records
     * @param {String} type
     */
    addRecordsToStore: function(records, type) {
        if (! type) {
            var emptyRecord = this.store.getAt(this.store.findExact('address', '')),
                type = (emptyRecord) ? emptyRecord.get('type') : 'to';
        }
                        
        var hasEmail = false,
            added = false;

        Ext.each(records, function(record) {
            if (record.hasEmail()) {
                this.store.add(new Ext.data.Record({type: type, 'address': Tine.Felamimail.getEmailStringFromContact(record)}));
                added = true;
            }
        }, this);
    },
    
    /**
     * set grid to fixed height if it has more than X records
     *  
     * @param {} doLayout
     */
    setFixedHeight: function (doLayout) {
        if (this.store.getCount() > this.numberOfRecordsForFixedHeight) {
            this.setHeight(155);
        } else {
            this.setHeight(this.store.getCount()*24 + 1);
        }

        if (doLayout && doLayout === true) {
            this.ownerCt.doLayout();
        }
    },
    
    /**
     * store has been updated
     * 
     * @param {} store
     * @param {} record
     * @param {} operation
     * @private
     */
    onUpdateStore: function(store, record, operation) {
        this.syncRecipientsToRecord();
    },
    
    /**
     * on add event of store
     * 
     * @param {} store
     * @param {} records
     * @param {} index
     */
    onAddStore: function(store, records, index) {
        this.syncRecipientsToRecord();
    },
    
    /**
     * sync grid with record
     * -> update record to/cc/bcc
     */
    syncRecipientsToRecord: function() {
        // update record recipient fields
        this.record.data.to = [];
        this.record.data.cc = [];
        this.record.data.bcc = [];
        this.store.each(function(recipient){
            if (recipient.data.address != '') {
                this.record.data[recipient.data.type].push(recipient.data.address);
            }
        }, this);
    },

    /**
     * sync grid with record
     * -> update store
     * 
     * @param {Array} fields
     * @param {Tine.Felamimail.Model.Message} record
     * @param {Boolean} setHeight
     * @param {Boolean} clearStore
     */
    syncRecipientsToStore: function(fields, record, setHeight, clearStore) {
        if (clearStore) {
            this.store.removeAll(true);
        }
        
        record = record || this.record;
        
        Ext.each(fields, function(field) {
            this._addRecipients(record.get(field), field);
        }, this);
        this.store.sort('address');
        
        if (clearStore) {
            this.store.add(new Ext.data.Record({type: 'to', 'address': ''}));
        }
        
        if (setHeight && setHeight === true) {
            this.setFixedHeight(true);
        }
    },
    
    /**
     * after edit
     * 
     * @param {} o
     */
    onAfterEdit: function(o) {
        if (o.field == 'address') {
            if (o.originalValue == '') {
                // use selected type to create new row with empty address and start editing
                this.store.add(new Ext.data.Record({type: o.record.data.type, 'address': ''}));
                this.store.commitChanges();
                this.startEditing.defer(50, this, [o.row +1, o.column]);
            } else if (o.value == '') {
                this.store.remove(o.record);
            }
            this.setFixedHeight(false);
            this.ownerCt.doLayout();
            this.searchCombo.focus.defer(80, this.searchCombo);
        }
    },    
    
    onBeforeEdit: function(o) {
        Ext.fly(this.getView().getCell(o.row, o.column)).addClass('x-grid3-td-address-editing');
    },
    
    /**
     * delete handler
     */
    onDelete: function(btn, e) {
        var sm = this.getSelectionModel();
        var records = sm.getSelections();
        Ext.each(records, function(record) {
            if (record.get('address') != '') {
                this.store.remove(record);
                this.store.fireEvent('update', this.store);
            }
        }, this);
        
        this.setFixedHeight(true);
    },
    
     onValidateEdit: function(o) {
        Ext.fly(this.getView().getCell(o.row, o.column)).removeClass('x-grid3-td-address-editing');
     },
     
    /**
     * add recipients to grid store
     * 
     * @param {Array} recipients
     * @param {String} type
     * @private
     * 
     * TODO get own email address and don't add it to store
     */
    _addRecipients: function(recipients, type) {
        if (recipients) {
            recipients = Ext.unique(recipients);
            for (var i=0; i < recipients.length; i++) {
                this.store.add(new Ext.data.Record({type: type, 'address': recipients[i]}));
            }
        }
    }
});

Ext.reg('felamimailrecipientgrid', Tine.Felamimail.RecipientGrid);

// file: /home/lkneschke/temp/tine20build/temp/tine20/Felamimail/js/Felamimail.js
/*
 * Tine 2.0
 * 
 * @package     Felamimail
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Philipp Schüle <p.schuele@metaways.de>
 * @copyright   Copyright (c) 2009-2011 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: Felamimail.js 18917 2011-01-28 12:13:39Z p.schuele@metaways.de $
 *
 */
 
Ext.namespace('Tine.Felamimail');

/**
 * @namespace   Tine.Felamimail
 * @class       Tine.Felamimail.Application
 * @extends     Tine.Tinebase.Application
 * 
 * <p>Felamimail application obj</p>
 * <p>
 * </p>
 * 
 * @author      Philipp Schüle <p.schuele@metaways.de>
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @version     $Id: Felamimail.js 18917 2011-01-28 12:13:39Z p.schuele@metaways.de $
 * 
 * @param       {Object} config
 * 
 * @constructor
 * Create a new  Tine.Felamimail.Application
 */
Tine.Felamimail.Application = Ext.extend(Tine.Tinebase.Application, {
    
    /**
     * @property checkMailsDelayedTask
     * @type Ext.util.DelayedTask
     */
    checkMailsDelayedTask: null,
    
    /**
     * @property defaultAccount
     * @type Tine.Felamimail.Model.Account
     */
    defaultAccount: null,
    
    /**
     * @type Ext.data.JsonStore
     */
    folderStore: null,
    
    /**
     * @property updateInterval user defined update interval (milliseconds)
     * @type Number
     */
    updateInterval: null,
    
    /**
     * transaction id of current update message cache request
     * @type Number
     */
    updateMessageCacheTransactionId: null,
    
    /**
     * returns title (Email)
     * 
     * @return {String}
     */
    getTitle: function() {
        return this.i18n._('Email');
    },
    
    /**
     * start delayed task to init folder store / updateFolderStore
     */
    init: function() {
        Tine.log.info('initialising app');
        this.checkMailsDelayedTask = new Ext.util.DelayedTask(this.checkMails, this);
        
        this.updateInterval = parseInt(Tine.Felamimail.registry.get('preferences').get('updateInterval')) * 60000;
        Tine.log.debug('user defined update interval is "' + this.updateInterval/1000 + '" seconds');
        
        this.defaultAccount = Tine.Felamimail.registry.get('preferences').get('defaultEmailAccount');
        Tine.log.debug('default account is "' + this.defaultAccount);
        
        if (window.isMainWindow) {
            if (Tine.Tinebase.appMgr.getActive() != this && this.updateInterval) {
                var delayTime = this.updateInterval/20;
                Tine.log.debug('start preloading mails in "' + delayTime/1000 + '" seconds');
                this.checkMailsDelayedTask.delay(delayTime);
            }
            
            this.showActiveVacation();
            this.checkAccounts();
        }
    },
    
    /**
     * show notification with active vacation information
     */
    showActiveVacation: function () {
        var accountsWithActiveVacation = Tine.Felamimail.loadAccountStore().query('sieve_vacation_active', true);
        accountsWithActiveVacation.each(function(item) {
            Ext.ux.Notification.show(
                this.i18n._('Active Vacation Message'), 
                String.format(this.i18n._('Email account "{0}" has an active vacation message.'), item.get('name'))
            );
        }, this);
    },

    /**
     * checks accounts
     * 
     * TODO perhaps we could set the account status to disconnect if checkAccounts fails
     */
    checkAccounts: function () {
        // only checks system accounts atm
        var systemAccounts = Tine.Felamimail.loadAccountStore().query('type', 'system'),
            ids = [];
        systemAccounts.each(function(item) {
            ids.push(item.id);
        }, this);
        
        if (ids.length > 0) {
            Tine.Felamimail.accountBackend.checkAccounts(ids);
        }
    },
    
    /**
     * check mails
     * 
     * if no folder is given, we find next folder to update ourself
     * 
     * @param {Tine.Felamimail.Model.Folder} [folder]
     * @param {Function} [callback]
     */
    checkMails: function(folder, callback) {
        this.checkMailsDelayedTask.cancel();
        
        if (! this.getFolderStore().getCount() && this.defaultAccount) {
            this.fetchSubfolders('/' + this.defaultAccount);
            return;
        }
        
        Tine.log.info('checking mails' + (folder ? ' for folder ' + folder.get('localname') : '') + ' now: ' + new Date());
        
        // if no folder is given, see if there is a folder to check in the folderstore
        if (! folder) {
            folder = this.getNextFolderToUpdate();
        }
        
        // for testing purposes
        //console.log('update disabled');
        //return;
        
        if (folder) {
            var executionTime = folder.isCurrentSelection() ? 10 : Math.min(this.updateInterval/1000, 120);
            
            if (this.updateMessageCacheTransactionId && Tine.Felamimail.folderBackend.isLoading(this.updateMessageCacheTransactionId)) {
                var currentRequestFolder = this.folderStore.query('cache_status', 'pending').first(),
                    expectedResponseIn = Math.floor((this.updateMessageCacheTransactionExpectedResponse.getTime() - new Date().getTime())/1000);
            
                if (currentRequestFolder && (currentRequestFolder !== folder || expectedResponseIn > executionTime)) {
                    Tine.log.debug('aborting current update message request (expected response in ' + expectedResponseIn + ' seconds)');
                    Tine.Felamimail.folderBackend.abort(this.updateMessageCacheTransactionId);
                    currentRequestFolder.set('cache_status', 'incomplete');
                    currentRequestFolder.commit();
                } else {
                    Tine.log.debug('a request updating message cache for folder "' + folder.get('localname') + '" is in progress -> wait for request to return');
                    return;
                }
            }
            
            Tine.log.debug('updating message cache for folder "' + folder.get('localname') + '" with ' + executionTime + ' seconds max execution time');
            
            this.updateMessageCacheTransactionExpectedResponse = new Date().add(Date.SECOND, executionTime);
            folder.set('cache_status', 'pending');
            folder.commit();
            
            this.updateMessageCacheTransactionId = Tine.Felamimail.folderBackend.updateMessageCache(folder.id, executionTime, {
                scope: this,
                callback: callback,
                failure: this.onBackgroundRequestFail,
                success: function(folder) {
                    Tine.Felamimail.loadAccountStore().getById(folder.get('account_id')).setLastIMAPException(null);
                    this.getFolderStore().updateFolder(folder);
                    
                    if (folder.get('cache_status') === 'updating') {
                        Tine.log.debug('updating message cache for folder "' + folder.get('localname') + '" is in progress on the server (folder is locked)');
                        return this.checkMailsDelayedTask.delay(this.updateInterval);
                    }
                    this.checkMailsDelayedTask.delay(0);
                }
            });
        } else {
            var allFoldersFetched = this.fetchSubfolders();
            
            if (allFoldersFetched) {
                Tine.log.info('nothing more to do -> will check mails again in "' + this.updateInterval/1000 + '" seconds');
                if (this.updateInterval > 0) {
                    this.checkMailsDelayedTask.delay(this.updateInterval);
                }
            } else {
                this.checkMailsDelayedTask.delay(20000);
            }
        }
    },
    
    /**
     * fetch subfolders by parent path 
     * - if parentPath param is empty, it loops all accounts and account folders to find the next folders to fetch
     * 
     * @param {String} parentPath
     * @return {Boolean} true if all folders of all accounts have been fetched
     */
    fetchSubfolders: function(parentPath) {
        var folderStore = this.getFolderStore(),
            accountStore = Tine.Felamimail.loadAccountStore(),
            doQuery = true,
            allFoldersFetched = false;
        
        if (! parentPath) {
            // find first account that has unfetched folders
            var index = accountStore.findExact('all_folders_fetched', false),
                account = accountStore.getAt(index);
            
            if (account) {
                // determine the next level of folders that is not fetched
                parentPath = '/' + account.id;
                
                var recordsOfAccount = folderStore.query('account_id', account.id);
                if (recordsOfAccount.getCount() > 0) {
                    // loop account folders and find the next folder path that hasn't been queried and has children
                    var path, found = false;
                    recordsOfAccount.each(function(record) {
                        path = parentPath + '/' + record.id;
                        if (! folderStore.isLoadedOrLoading('parent_path', path) && (! account.get('has_children_support') || record.get('has_children'))) {
                            parentPath = path;
                            found = true;
                            Tine.log.debug('fetching next level of subfolders for ' + record.get('globalname'));
                            return false;
                        }
                        return true;
                    }, this);
                    
                    if (! found) {
                        Tine.log.debug('all folders of account ' + account.get('name') + ' have been fetched ...');
                        account.set('all_folders_fetched', true);
                        return false;
                    }
                } else {
                    Tine.log.debug('fetching first level of folders for account ' + account.get('name'));
                }
                
            } else {
                Tine.log.debug('all folders of all accounts have been fetched ...');
                return true;
            }
        } else {
            Tine.log.debug('no folders in store yet, fetching first level ...');
        }
        
        if (! folderStore.queriesPending || folderStore.queriesPending.length == 0) {
            folderStore.asyncQuery('parent_path', parentPath, this.checkMails.createDelegate(this, []), [], this, folderStore);
        } else {
            this.checkMailsDelayedTask.delay(0);
        }
        
        return false;
    },
    
    /**
     * get folder store
     * 
     * @return {Tine.Felamimail.FolderStore}
     */
    getFolderStore: function() {
        if (! this.folderStore) {
            Tine.log.debug('creating folder store');
            this.folderStore = new Tine.Felamimail.FolderStore({
                listeners: {
                    scope: this,
                    update: this.onUpdateFolder
                }
            });
        }
        
        return this.folderStore;
    },
    
    /**
     * gets next folder which needs to be checked for mails
     * 
     * @return {Model.Folder/null}
     */
    getNextFolderToUpdate: function() {
        var currNode = this.getMainScreen().getTreePanel().getSelectionModel().getSelectedNode(),
            currFolder = currNode ? this.getFolderStore().getById(currNode.id) : null;
        
        // current selection has highest prio!
        if (currFolder && currFolder.needsUpdate(this.updateInterval)) {
            return currFolder;
        }
        
        // check if inboxes need updates
        var inboxes = this.folderStore.queryBy(function(folder) {
            return folder.isInbox() && folder.needsUpdate(this.updateInterval);
        }, this);
        if (inboxes.getCount() > 0) {
            return inboxes.first();
        }
        
        // check for incompletes
        var incompletes = this.folderStore.queryBy(function(folder) {
            return folder.get('cache_status') !== 'complete';
        }, this);
        if (incompletes.getCount() > 0) {
            return incompletes.first();
        }
        
        // check for outdated
        var outdated = this.folderStore.queryBy(function(folder) {
            var timestamp = folder.get('client_access_time');
            if (! Ext.isDate(timestamp)) {
                return true;
            }
            // update inboxes more often than other folders
            if (folder.isInbox() && timestamp.getElapsed() > this.updateInterval) {
                return true;
            } else if (timestamp.getElapsed() > (this.updateInterval * 5)) {
                return true;
            }
            return false;
        }, this);
        if (outdated.getCount() > 0) {
            Tine.log.debug('still got ' + outdated.getCount() + ' outdated folders to update ...');
            return outdated.first();
        }
        
        // nothing to update
        return null;
    },
    
    /**
     * executed when updateMessageCache requests fail
     * 
     * NOTE: We show the credential error dlg and this only for the first error
     * 
     * @param {Object} exception
     */
    onBackgroundRequestFail: function(exception) {
        var currentRequestFolder = this.folderStore.query('cache_status', 'pending').first();
        var accountId   = currentRequestFolder.get('account_id'),
            account     = accountId ? Tine.Felamimail.loadAccountStore().getById(accountId): null,
            imapStatus  = account ? account.get('imap_status') : null;
            
        if (account) {
            account.setLastIMAPException(exception);
            
            this.getFolderStore().each(function(folder) {
                if (folder.get('account_id') === accountId) {
                    folder.set('cache_status', 'disconnect');
                    folder.commit();
                }
            }, this);
            
            if (exception.code == 912 && imapStatus !== 'failure' && Tine.Tinebase.appMgr.getActive() === this) {
                Tine.Felamimail.folderBackend.handleRequestException(exception);
            }
        }
        
        Tine.log.info('Background update failed (' + exception.message + ') for folder ' + currentRequestFolder.get('globalname') 
            + ' -> will check mails again in "' + this.updateInterval/1000 + '" seconds');
        Tine.log.debug(exception);
        this.checkMailsDelayedTask.delay(this.updateInterval);
    },
    
    /**
     * executed right before this app gets activated
     */
    onBeforeActivate: function() {
        Tine.log.info('activating felamimail now');
        // abort preloading/old actions and force fresh fetch
        this.checkMailsDelayedTask.delay(0);
    },
    
    /**
     * on update folder
     * 
     * @param {Tine.Felamimail.FolderStore} store
     * @param {Tine.Felamimail.Model.Folder} record
     * @param {String} operation
     */
    onUpdateFolder: function(store, record, operation) {
        if (operation === Ext.data.Record.EDIT && record.isModified('cache_status')) {
            Tine.log.info('Folder "' + record.get('localname') + '" updated with cache_status: ' + record.get('cache_status'));
            
            // as soon as we get a folder with status != complete we need to trigger checkmail soon!
            if (['complete', 'pending'].indexOf(record.get('cache_status')) === -1) {
                this.checkMailsDelayedTask.delay(1000);
            }
            
            // only show notifications for inbox if unreadcount changed
            if (record.isModified('cache_unreadcount')) {
                var recents = (record.get('cache_unreadcount') - record.modified.cache_unreadcount),
                    account = Tine.Felamimail.loadAccountStore().getById(record.get('account_id'));
                if (recents > 0 && record.isInbox()) {
                    Tine.log.info('show notification: ' + recents + ' new mails.');
                    var title = this.i18n._('New mails'),
                        message = String.format(this.i18n._('You got {0} new mail(s) in folder {1} ({2}).'), recents, record.get('localname'), account.get('name')); 
                    
                    if (record.isCurrentSelection()) {
                        // need to defer the notification because the new messages are not shown yet 
                        // -> improve this with a callback fn or something like that / unread count should be updated when the messages become visible, too
                        Ext.ux.Notification.show.defer(3500, this, [title, message]);
                    } else {
                        Ext.ux.Notification.show(title, message);
                    }
                }
            }
        }
    },
    
    /**
     * get active account
     * @return {Tine.Felamimail.Model.Account}
     */
    getActiveAccount: function() {
        var account = null;
            
        var treePanel = this.getMainScreen().getTreePanel();
        if (treePanel && treePanel.rendered) {
            account = treePanel.getActiveAccount();
        }
        
        if (account === null) {
            account = Tine.Felamimail.loadAccountStore().getById(Tine.Felamimail.registry.get('preferences').get('defaultEmailAccount'));
        }
        
        return account;
    },
    
    /**
     * show felamimail credentials dialog
     * 
     * @param {Tine.Felamimail.Model.Account} account
     * @param {String} username [optional]
     */
    showCredentialsDialog: function(account, username) {
        Tine.Felamimail.credentialsDialog = Tine.widgets.dialog.CredentialsDialog.openWindow({
            windowTitle: String.format(this.i18n._('IMAP Credentials for {0}'), account.get('name')),
            appName: 'Felamimail',
            credentialsId: account.id,
            i18nRecordName: this.i18n._('Credentials'),
            recordClass: Tine.Tinebase.Model.Credentials,
            record: new Tine.Tinebase.Model.Credentials({
                id: account.id,
                username: username ? username : ''
            }),
            listeners: {
                scope: this,
                'update': function(data) {
                    var folderStore = this.getFolderStore();
                    if (folderStore.queriesPending.length > 0) {
                        // reload all folders of account and try to select inbox
                        var accountId = folderStore.queriesPending[0].substring(16, 56),
                            account = Tine.Felamimail.loadAccountStore().getById(accountId),
                            accountNode = this.getMainScreen().getTreePanel().getNodeById(accountId);
                            
                        folderStore.resetQueryAndRemoveRecords('parent_path', '/' + accountId);
                        account.set('all_folders_fetched', true);
                        
                        accountNode.loading = false;
                        accountNode.reload(function(callback) {
                            Ext.each(accountNode.childNodes, function(node) {
                                if (Ext.util.Format.lowercase(node.attributes.localname) == 'inbox') {
                                    node.select();
                                    return false;
                                }
                            }, this);
                        });
                    } else {
                        this.checkMailsDelayedTask.delay(0);
                    }
                }
            }
        });
    }
});

/**
 * @namespace Tine.Felamimail
 * @class Tine.Felamimail.MainScreen
 * @extends Tine.widgets.MainScreen
 * 
 * MainScreen Definition
 */ 
Tine.Felamimail.MainScreen = Ext.extend(Tine.widgets.MainScreen, {
    /**
     * adapter fn to get folder tree panel
     * 
     * @return {Ext.tree.TreePanel}
     */
    getTreePanel: function() {
        return this.getWestPanel().getContainerTreePanel();
    }
});

/**
 * get account store
 *
 * @param {Boolean} reload
 * @return {Ext.data.JsonStore}
 */
Tine.Felamimail.loadAccountStore = function(reload) {
    
    var store = Ext.StoreMgr.get('FelamimailAccountStore');
    
    if (!store) {
        // create store (get from initial data)
        store = new Ext.data.JsonStore({
            fields: Tine.Felamimail.Model.Account,

            // initial data from http request
            data: Tine.Felamimail.registry.get('accounts'),
            autoLoad: true,
            id: 'id',
            root: 'results',
            totalProperty: 'totalcount',
            proxy: Tine.Felamimail.accountBackend,
            reader: Tine.Felamimail.accountBackend.getReader()
        });
        
        Ext.StoreMgr.add('FelamimailAccountStore', store);
    } 

    return store;
};

/**
 * get flags store
 *
 * @param {Boolean} reload
 * @return {Ext.data.JsonStore}
 */
Tine.Felamimail.loadFlagsStore = function(reload) {
    
    var store = Ext.StoreMgr.get('FelamimailFlagsStore');
    
    if (!store) {
        // create store (get from initial registry data)
        store = new Ext.data.JsonStore({
            fields: Tine.Felamimail.Model.Flag,
            data: Tine.Felamimail.registry.get('supportedFlags'),
            autoLoad: true,
            id: 'id',
            root: 'results',
            totalProperty: 'totalcount'
        });
        
        Ext.StoreMgr.add('FelamimailFlagsStore', store);
    } 

    return store;
};

/**
 * add signature (get it from default account settings)
 * 
 * @param {String} id
 * @return {String}
 */
Tine.Felamimail.getSignature = function(id) {
        
    var result = '',
        activeAccount = Tine.Tinebase.appMgr.get('Felamimail').getMainScreen().getTreePanel().getActiveAccount();
        
    id = id || (activeAccount ? activeAccount.id : 'default');
    
    if (id === 'default') {
        id = Tine.Felamimail.registry.get('preferences').get('defaultEmailAccount');
    }
    
    var defaultAccount = Tine.Felamimail.loadAccountStore().getById(id);
    var signature = (defaultAccount) ? defaultAccount.get('signature') : '';
    if (signature && signature != '') {
        signature = Ext.util.Format.nl2br(signature);
        result = '<br><br><span id="felamimail-body-signature">--<br>' + signature + '</span>';
    }
    
    return result;
};

/**
 * get email string (n_fileas <email@host.tld>) from contact
 * 
 * @param {Tine.Addressbook.Model.Contact} contact
 * @return {String}
 */
Tine.Felamimail.getEmailStringFromContact = function(contact) {
    var result = contact.get('n_fileas') + ' <';
    if (contact.get('email') != '') {
        result += contact.get('email');
    } else {
        result += contact.get('email_home');
    }
    result += '>';
    
    return result;
};

/**
 * generic exception handler for felamimail (used by folder and message backends and updateMessageCache)
 * 
 * TODO move all 902 exception handling here!
 * TODO invent requery on 902 with cred. dialog
 * 
 * @param {Tine.Exception} exception
 */
Tine.Felamimail.handleRequestException = function(exception) {
    Tine.log.warn('request exception :');
    Tine.log.warn(exception);
    
    var app = Tine.Tinebase.appMgr.get('Felamimail');
    
    switch(exception.code) {
        case 910: // Felamimail_Exception_IMAP
        case 911: // Felamimail_Exception_IMAPServiceUnavailable
            Ext.Msg.show({
               title:   app.i18n._('IMAP Error'),
               msg:     exception.message ? exception.message : app.i18n._('No connection to IMAP server.'),
               icon:    Ext.MessageBox.ERROR,
               buttons: Ext.Msg.OK
            });
            break;
            
        case 912: // Felamimail_Exception_IMAPInvalidCredentials
            var accountId   = exception.account && exception.account.id ? exception.account.id : '',
                account     = accountId ? Tine.Felamimail.loadAccountStore().getById(accountId): null,
                imapStatus  = account ? account.get('imap_status') : null;
                
            if (account) {
                account.set('all_folders_fetched', true);
                app.showCredentialsDialog(account, exception.username);
            } else {
                exception.code = 910;
                return this.handleRequestException(exception);
            }
            break;
            
        case 913: // Felamimail_Exception_IMAPFolderNotFound
            Ext.Msg.show({
               title:   app.i18n._('IMAP Error'),
               msg:     app.i18n._('One of your folders was deleted from an other client, please reload you browser'),
               icon:    Ext.MessageBox.ERROR,
               buttons: Ext.Msg.OK
            });
            break;
            
        case 404: 
        case 914: // Felamimail_Exception_IMAPMessageNotFound
            // do nothing, this exception is handled by Tine.Tinebase.ExceptionHandler.handleRequestException
            exception.code = 404;
            Tine.Tinebase.ExceptionHandler.handleRequestException(exception);
            break;
            
        case 920: // Felamimail_Exception_SMTP
            Ext.Msg.show({
               title:   app.i18n._('SMTP Error'),
               msg:     exception.message ? exception.message : app.i18n._('No connection to SMTP server.'),
               icon:    Ext.MessageBox.ERROR,
               buttons: Ext.Msg.OK
            });
            break;
            
        case 930: // Felamimail_Exception_Sieve
            Ext.Msg.show({
               title:   app.i18n._('Sieve Error'),
               msg:     exception.message ? exception.message : app.i18n._('No connection to Sieve server.'),
               icon:    Ext.MessageBox.ERROR,
               buttons: Ext.Msg.OK
            });
            break;

        case 931: // Felamimail_Exception_SievePutScriptFail
            Ext.Msg.show({
               title:   app.i18n._('Save Sieve Script Error'),
               msg:     app.i18n._('Could not save script on Sieve server.') + (exception.message ? ' (' + exception.message + ')' : ''),
               icon:    Ext.MessageBox.ERROR,
               buttons: Ext.Msg.OK
            });
            break;

        default:
            Tine.Tinebase.ExceptionHandler.handleRequestException(exception);
            break;
    }
};

// file: /home/lkneschke/temp/tine20build/temp/tine20/Felamimail/js/ComposeEditor.js
/*
 * Tine 2.0
 * 
 * @package     Felamimail
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Philipp Schuele <p.schuele@metaways.de>
 * @copyright   Copyright (c) 2010 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: ComposeEditor.js 18715 2011-01-18 15:08:11Z c.weiss@metaways.de $
 *
 */
 
Ext.namespace('Tine.Felamimail');

/**
 * @namespace   Tine.Felamimail
 * @class       Tine.Felamimail.ComposeEditor
 * @extends     Ext.form.HtmlEditor
 * 
 * <p>Compose HTML Editor</p>
 * 
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Philipp Schuele <p.schuele@metaways.de>
 * @copyright   Copyright (c) 2010 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: ComposeEditor.js 18715 2011-01-18 15:08:11Z c.weiss@metaways.de $
 * 
 * @param       {Object} config
 * @constructor
 * Create a new Tine.Felamimail.ComposeEditor
 */
Tine.Felamimail.ComposeEditor = Ext.extend(Ext.form.HtmlEditor, {
    
    cls: 'felamimail-message-body-html',
    name: 'body',
    allowBlank: true,

    // TODO get styles from head with css selector
    getDocMarkup: function(){
        var markup = '<html>'
            + '<head>'
            + '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">'
            + '<title></title>'
            + '<style type="text/css">'
                // standard css reset
                + "html,body,div,dl,dt,dd,ul,ol,li,h1,h2,h3,h4,h5,h6,pre,form,fieldset,input,p,blockquote,th,td{margin:0;padding:0;}img,body,html{border:0;}address,caption,cite,code,dfn,em,strong,th,var{font-style:normal;font-weight:normal;}ol,ul {list-style:none;}caption,th {text-align:left;}h1,h2,h3,h4,h5,h6{font-size:100%;}q:before,q:after{content:'';}"
                // small forms
                + "html,body,div,dl,dt,dd,ul,ol,li,h1,h2,h3,h4,h5,h6,pre,form,fieldset,input,p,blockquote,th,td{font-size: small;}"
                // fmail spechial
                + '.felamimail-body-blockquote {'
                    + 'margin: 5px 10px 0 3px;'
                    + 'padding-left: 10px;'
                    + 'border-left: 2px solid #000088;'
                + '} '
            + '</style>'
            + '</head>'
            + '<body style="padding: 5px 0px 0px 5px; margin: 0px">'
            + '</body></html>';

        return markup;
    },
    
    /**
     * @private
     */
    initComponent: function() {
        
        this.plugins = [
            // TODO which plugins to activate?
            //new Ext.ux.form.HtmlEditor.Word(),  
            //new Ext.ux.form.HtmlEditor.Divider(),  
            //new Ext.ux.form.HtmlEditor.Table(),  
            //new Ext.ux.form.HtmlEditor.HR(),
            new Ext.ux.form.HtmlEditor.IndentOutdent(),  
            //new Ext.ux.form.HtmlEditor.SubSuperScript(),  
            new Ext.ux.form.HtmlEditor.RemoveFormat(),
            new Ext.ux.form.HtmlEditor.EndBlockquote()
        ];
        
        Tine.Felamimail.ComposeEditor.superclass.initComponent.call(this);
    }
});

Ext.namespace('Ext.ux.form.HtmlEditor');

/**
 * @class Ext.ux.form.HtmlEditor.EndBlockquote
 * @extends Ext.util.Observable
 * 
 * plugin for htmleditor that ends blockquotes on ENTER
 * 
 * TODO move this to ux dir
 */
Ext.ux.form.HtmlEditor.EndBlockquote = Ext.extend(Ext.util.Observable , {

    // private
    init: function(cmp){
        this.cmp = cmp;
        this.cmp.on('initialize', this.onInit, this);
    },
    // private
    onInit: function(){
        Ext.EventManager.on(this.cmp.getDoc(), {
            'keydown': this.onKeydown,
            scope: this
        });
    },

    /**
     * on keydown 
     * 
     * @param {Event} e
     */
    onKeydown: function(e) {
        if (e.getKey() == e.ENTER) {
            var s = this.cmp.win.getSelection(),
                r = s.getRangeAt(0),
                doc = this.cmp.getDoc(),
                level = this.getBlockquoteLevel(s);
            
            if (level > 0) {
                e.stopEvent();
                this.cmp.win.focus();
                if (level == 1) {
                    this.cmp.execCmd('InsertHTML','<br /><blockquote class="felamimail-body-blockquote"><br />');
                    this.cmp.execCmd('outdent');
                    this.cmp.execCmd('outdent');
                } else if (level > 1) {
                    for (var i=0; i < level; i++) {
                        this.cmp.execCmd('InsertHTML','<br /><blockquote class="felamimail-body-blockquote">');
                        this.cmp.execCmd('outdent');
                        this.cmp.execCmd('outdent');
                    }
                    var br = doc.createElement('br');
                    r.insertNode(br);
                }
                this.cmp.deferFocus();
            } else if (e.ctrlKey) {
                // TODO try to move this to cmp or another plugin as we need this only to submit parent dialog with ctrl-enter
                this.cmp.fireEvent('keydown', e);
            }
        }
    },
    
    /**
     * get blockquote level helper
     * 
     * @param {Selection} s
     * @return {Integer}
     */
    getBlockquoteLevel: function(s) {
        var result = 0,
            node = s.anchorNode;
            
        while (node.nodeName == '#text' || node.tagName.toLowerCase() != 'body') {
            if (node.tagName && node.tagName.toLowerCase() == 'blockquote') {
                result++;
            }
            node = node.parentElement;
        }
        
        return result;
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

// file: /home/lkneschke/temp/tine20build/temp/tine20/Felamimail/js/ContactGrid.js
/*
 * Tine 2.0
 * 
 * @package     Felamimail
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Philipp Schüle <p.schuele@metaways.de>
 * @copyright   Copyright (c) 2010-2011 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: ContactGrid.js 18485 2011-01-10 14:52:08Z p.schuele@metaways.de $
 *
 */
 
Ext.ns('Tine.Felamimail');

/**
 * Contact grid panel
 * 
 * @namespace   Tine.Felamimail
 * @class       Tine.Felamimail.ContactGridPanel
 * @extends     Tine.Addressbook.ContactGridPanel
 * 
 * <p>Contact Grid Panel</p>
 * <p>
 * </p>
 * 
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Philipp Schüle <p.schuele@metaways.de>
 * @copyright   Copyright (c) 2010-2011 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: ContactGrid.js 18485 2011-01-10 14:52:08Z p.schuele@metaways.de $
 * 
 * @param       {Object} config
 * @constructor
 * Create a new Tine.Felamimail.ContactGridPanel
 */
Tine.Felamimail.ContactGridPanel = Ext.extend(Tine.Addressbook.ContactGridPanel, {

    hasDetailsPanel: false,
    hasFavoritesPanel: false,
    disableSelectAllPages: true,
    hasQuickSearchFilterToolbarPlugin: false,
    stateId: 'FelamimailContactGrid',
    
    gridConfig: {
        loadMask: true,
        autoExpandColumn: 'n_fileas',
        enableDragDrop: false
    },
    
    /**
     * the message record with recipients
     * @type Tine.Felamimail.Model.Message
     */
    messageRecord: null,
    
    /**
     * inits this cmp
     * @private
     */
    initComponent: function() {
        this.addEvents(
            /**
             * @event addcontacts
             * Fired when contacts are added
             */
            'addcontacts'
        );
        
        this.app = Tine.Tinebase.appMgr.get('Addressbook');
        this.filterToolbar = this.getFilterToolbar({
            filterFieldWidth: 100,
            filterValueWidth: 100
        });
        
        Tine.Felamimail.ContactGridPanel.superclass.initComponent.call(this);
        
        this.grid.on('rowdblclick', this.onRowDblClick, this);
        this.grid.on('cellclick', this.onCellClick, this);
        this.store.on('load', this.onContactStoreLoad, this);
    },
    
    /**
     * returns array with columns
     * 
     * @return {Array}
     */
    getColumns: function() {
        var columns = Tine.Felamimail.ContactGridPanel.superclass.getColumns.call(this);
        
        // hide all columns except name/company/email/email_home (?)
        Ext.each(columns, function(column) {
            if (['n_fileas', 'org_name', 'email'].indexOf(column.dataIndex) === -1) {
                column.hidden = true;
            }
        });
        
        this.radioTpl = new Ext.XTemplate('<input',
            ' name="' + this.id + '_{id}"',
            ' value="{type}"',
            ' type="radio"',
            ' autocomplete="off"',
            ' class="x-form-radio x-form-field"',
            ' {checked}',
        '>');
        
        Ext.each(['To', 'Cc', 'Bcc', 'None'], function(type) { // _('None')
            columns.push({
                header: this.app.i18n._(type),
                dataIndex: Ext.util.Format.lowercase(type),
                width: 50,
                hidden: false,
                renderer: this.typeRadioRenderer.createDelegate(this, [type], 0)
            });
            
        }, this);
            
        return columns;
    },
    
    /**
     * render type radio buttons in grid
     * 
     * @param {String} type
     * @param {String} value
     * @param {Object} metaData
     * @param {Object} record
     * @param {Number} rowIndex
     * @param {Number} colIndex
     * @param {Store} store
     * @return {String}
     */
    typeRadioRenderer: function(type, value, metaData, record, rowIndex, colIndex, store) {
        if (! record.hasEmail()) {
            return '';
        }
        
        var lowerType = Ext.util.Format.lowercase(type); 
        
        return this.radioTpl.apply({
            id: record.id, 
            type: lowerType,
            checked: lowerType === 'none' ? 'checked' : ''
        });
    },
    
    /**
     * called after a new set of Records has been loaded
     * 
     * @param  {Ext.data.Store} this.store
     * @param  {Array}          loaded records
     * @param  {Array}          load options
     * @return {Void}
     */
    onContactStoreLoad: function(store, records, options) {
        Ext.each(records, function(record) {
            Ext.each(['to', 'cc', 'bcc'], function(type) {
                if (this.messageRecord.data[type].indexOf(Tine.Felamimail.getEmailStringFromContact(record)) !== -1) {
                    this.setTypeRadio(record, type);
                }
            }, this);
        }, this);
    },
    
    /**
     * cell click handler -> update recipients in record
     * 
     * @param {Grid} grid
     * @param {Number} row
     * @param {Number} col
     * @param {Event} e
     */
    onCellClick: function(grid, row, col, e) {
        var contact = this.store.getAt(row),
            typeToSet = this.grid.getColumnModel().getDataIndex(col)
            
        if (! contact.hasEmail() && typeToSet !== 'none') {
            this.setTypeRadio(contact, 'none');
        } else {
            this.updateRecipients(contact, typeToSet);
        }
    },
    
    /**
     * update recipient
     * 
     * @param {Tine.Addressbook.Model.Contact} contact
     * @param {String} typeToSet
     */
    updateRecipients: function(contact, typeToSet) {
        var email = Tine.Felamimail.getEmailStringFromContact(contact),
            found = false;
            
        Ext.each(['to', 'cc', 'bcc'], function(type) {
            if (this.messageRecord.data[type].indexOf(email) !== -1) {
                if (type !== typeToSet) {
                    this.messageRecord.data[type].remove(email);
                } else {
                    found = true;
                }
            }
        }, this);
        
        if (! found && typeToSet !== 'none') {
            this.messageRecord.data[typeToSet].push(email);
        }
    },
    
    /**
     * update type radio buttons dom
     * 
     * @param {Array} records of type Tine.Addressbook.Model.Contact
     * @param {String} type
     */
    setTypeRadio: function(records, type) {
        var rs = [].concat(records);
        
        Ext.each(rs, function(r) {
            if (r.hasEmail() || type === 'none') {
                Ext.select('input[name=' + this.id + '_' + r.id + ']', this.grid.el).each(function(el) {
                    el.dom.checked = type === el.dom.value;
                });
                this.updateRecipients(r, type);
            }
        }, this);
    },
    
    /**
     * Return CSS class to apply to rows depending upon email set or not
     * 
     * @param {Tine.Addressbook.Model.Contact} record
     * @param {Integer} index
     * @return {String}
     */
    getViewRowClass: function(record, index) {
        var className = '';
        
        if (! record.hasEmail()) {
            className = 'felamimail-no-email';
        }
        
        return className;
    },
    
    /**
     * @private
     */
    initActions: function() {
        this.actions_addAsTo = new Ext.Action({
            requiredGrant: 'readGrant',
            text: this.app.i18n._('Add as "To"'),
            disabled: true,
            iconCls: 'action_add',
            actionUpdater: this.updateRecipientActions,
            handler: this.onAddContact.createDelegate(this, ['to']),
            allowMultiple: true,
            scope: this
        });

        this.actions_addAsCc = new Ext.Action({
            requiredGrant: 'readGrant',
            text: this.app.i18n._('Add as "Cc"'),
            disabled: true,
            iconCls: 'action_add',
            actionUpdater: this.updateRecipientActions,
            handler: this.onAddContact.createDelegate(this, ['cc']),
            allowMultiple: true,
            scope: this
        });

        this.actions_addAsBcc = new Ext.Action({
            requiredGrant: 'readGrant',
            text: this.app.i18n._('Add as "Bcc"'),
            disabled: true,
            iconCls: 'action_add',
            actionUpdater: this.updateRecipientActions,
            handler: this.onAddContact.createDelegate(this, ['bcc']),
            allowMultiple: true,
            scope: this
        });
        
        this.actions_setToNone = new Ext.Action({
            requiredGrant: 'readGrant',
            text: this.app.i18n._('Remove from recipients'),
            disabled: true,
            iconCls: 'action_delete',
            actionUpdater: this.updateRecipientActions,
            handler: this.onAddContact.createDelegate(this, ['none']),
            allowMultiple: true,
            scope: this
        });
        
        //register actions in updater
        this.actionUpdater.addActions([
            this.actions_addAsTo,
            this.actions_addAsCc,
            this.actions_addAsBcc,
            this.actions_setToNone
        ]);
    },
    
    /**
     * updates context menu
     * 
     * @param {Ext.Action} action
     * @param {Object} grants grants sum of grants
     * @param {Object} records
     */
    updateRecipientActions: function(action, grants, records) {
        if (records.length > 0) {
            var emptyEmails = true;
            for (var i=0; i < records.length; i++) {
                if (records[i].hasEmail()) {
                    emptyEmails = false;
                    break;
                }
            }
            
            action.setDisabled(emptyEmails);
        } else {
            action.setDisabled(true);
        }
    },
    
    /**
     * on add contact -> fires addcontacts event and passes rows + type
     * 
     * @param {String} type
     */
    onAddContact: function(type) {
        var selectedRows = this.grid.getSelectionModel().getSelections();
        this.setTypeRadio(selectedRows, type);
    },
    
    /**
     * row doubleclick handler
     * 
     * @param {} grid
     * @param {} row
     * @param {} e
     */
    onRowDblClick: function(grid, row, e) {
        this.onAddContact('to');
    }, 
    
    /**
     * returns rows context menu
     * 
     * @return {Ext.menu.Menu}
     */
    getContextMenu: function() {
        if (! this.contextMenu) {
            var items = [
                this.actions_addAsTo,
                this.actions_addAsCc,
                this.actions_addAsBcc,
                this.actions_setToNone
            ];
            this.contextMenu = new Ext.menu.Menu({items: items});
        }
        return this.contextMenu;
    }
});

Ext.reg('felamimailcontactgrid', Tine.Felamimail.ContactGridPanel);

// file: /home/lkneschke/temp/tine20build/temp/tine20/Felamimail/js/RecipientPickerDialog.js
/*
 * Tine 2.0
 * 
 * @package     Felamimail
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Philipp Schüle <p.schuele@metaways.de>
 * @copyright   Copyright (c) 2010-2011 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: RecipientPickerDialog.js 18486 2011-01-10 14:53:14Z p.schuele@metaways.de $
 *
 */
 
Ext.namespace('Tine.Felamimail');

/**
 * @namespace   Tine.Felamimail
 * @class       Tine.Felamimail.RecipientPickerDialog
 * @extends     Tine.widgets.dialog.EditDialog
 * 
 * <p>Message Compose Dialog</p>
 * <p>This dialog is for searching contacts in the addressbook and adding them to the recipient list in the email compose dialog.</p>
 * <p>
 * </p>
 * 
 * @author      Philipp Schuele <p.schuele@metaways.de>
 * @copyright   Copyright (c) 2010-2011 Metaways Infosystems GmbH (http://www.metaways.de)
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @version     $Id: RecipientPickerDialog.js 18486 2011-01-10 14:53:14Z p.schuele@metaways.de $
 * 
 * @param       {Object} config
 * @constructor
 * Create a new RecipientPickerDialog
 */
 Tine.Felamimail.RecipientPickerDialog = Ext.extend(Tine.widgets.dialog.EditDialog, {
    
    /**
     * @private
     */
    windowNamePrefix: 'RecipientPickerWindow_',
    appName: 'Felamimail',
    recordClass: Tine.Felamimail.Model.Message,
    recordProxy: Tine.Felamimail.messageBackend,
    loadRecord: false,
    evalGrants: false,
    mode: 'local',
    
    bodyStyle:'padding:0px',
    
    /**
     * overwrite update toolbars function (we don't have record grants)
     * @private
     */
    updateToolbars: Ext.emptyFn,
    
    /**
     * @private
     */
    onRecordLoad: function() {
        // interrupt process flow till dialog is rendered
        if (! this.rendered) {
            this.onRecordLoad.defer(250, this);
            return;
        }
        
        var subject = (this.record.get('subject') != '') ? this.record.get('subject') : this.app.i18n._('(new message)');
        this.window.setTitle(String.format(this.app.i18n._('Select recipients for "{0}"'), subject));
        
        this.loadMask.hide();
    },

    /**
     * returns dialog
     * 
     * NOTE: when this method gets called, all initialisation is done.
     * 
     * @return {Object}
     * @private
     */
    getFormItems: function() {
        var adbApp = Tine.Tinebase.appMgr.get('Addressbook');
        
        this.treePanel = new Tine.widgets.container.TreePanel({
            region: 'west',
            filterMode: 'filterToolbar',
            recordClass: Tine.Addressbook.Model.Contact,
            app: adbApp,
            width: 200,
            minSize: 100,
            maxSize: 300,
            border: false,
            enableDrop: false
        });
        
        this.contactGrid = new Tine.Felamimail.ContactGridPanel({
            region: 'center',
            messageRecord: this.record,
            app: adbApp,
            plugins: [this.treePanel.getFilterPlugin()]
        });
        
        return {
            border: false,
            layout: 'border',
            items: [{
                cls: 'tine-mainscreen-centerpanel-west',
                region: 'west',
                stateful: false,
                layout: 'border',
                split: true,
                width: 200,
                minSize: 100,
                maxSize: 300,
                border: false,
                collapsible: true,
                collapseMode: 'mini',
                header: false,
                items: [{
                    border: false,
                    region: 'center',
                    items: [{
                        xtype: 'tine.widgets.mainscreen.westpanel',
                        app: adbApp,
                        containerTreePanel: this.treePanel,
                        favoritesPanel: new Tine.widgets.persistentfilter.PickerPanel({
                            filter: [{field: 'model', operator: 'equals', value: 'Addressbook_Model_ContactFilter'}],
                            app: adbApp,
                            grid: this.contactGrid
                        })
                    }]
                }]
            }, this.contactGrid]
        };
    }
});

/**
 * Felamimail Edit Popup
 * 
 * @param   {Object} config
 * @return  {Ext.ux.Window}
 */
Tine.Felamimail.RecipientPickerDialog.openWindow = function (config) {
    var window = Tine.WindowFactory.getWindow({
        width: 1000,
        height: 600,
        name: Tine.Felamimail.RecipientPickerDialog.prototype.windowNamePrefix + Ext.id(),
        contentPanelConstructor: 'Tine.Felamimail.RecipientPickerDialog',
        contentPanelConstructorConfig: config
    });
    return window;
};

// file: /home/lkneschke/temp/tine20build/temp/tine20/Felamimail/js/FolderFilterModel.js
/*
 * Tine 2.0
 * 
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Philipp Schuele <p.schuele@metaways.de>
 * @copyright   Copyright (c) 2010 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: FolderFilterModel.js 17757 2010-12-08 16:28:28Z p.schuele@metaways.de $
 * 
 */
Ext.ns('Tine.Felamimail');

/**
 * @namespace   Tine.widgets.container
 * @class       Tine.Felamimail.FolderFilterModel
 * @extends     Tine.widgets.grid.FilterModel
 * 
 * @author      Philipp Schuele <p.schuele@metaways.de>
 * @version     $Id: FolderFilterModel.js 17757 2010-12-08 16:28:28Z p.schuele@metaways.de $
 */
Tine.Felamimail.FolderFilterModel = Ext.extend(Tine.widgets.grid.FilterModelMultiSelect, {

    /**
     * @cfg 
     */
    operators: ['in', 'notin'],
    field: 'path',
    
    /**
     * @private
     */
    initComponent: function() {
        this.label = this.app.i18n._('Folder');
        
        this.multiselectFieldConfig = {
            xtype: 'wdgt.pickergrid',
            labelField: 'path',
            layerHeight: 200,
            selectionWidget: new Tine.Felamimail.FolderSelectTriggerField({
                allAccounts: true
            }),
            recordClass: Tine.Felamimail.Model.Folder,
            valueStore: this.app.getFolderStore(),
            
            /**
             * functions
             */
            labelRenderer: Tine.Felamimail.GridPanel.prototype.accountAndFolderRenderer.createDelegate(this),
            initSelectionWidget: function() {
                this.selectionWidget.onSelectFolder = this.addRecord.createDelegate(this);
            },
            isSelectionVisible: function() {
                return this.selectionWidget.selectPanel && ! this.selectionWidget.selectPanel.isDestroyed        
            },
            getRecordText: function(value) {
                var path = (Ext.isString(value)) ? value : (value.path) ? value.path : '/' + value.id,
                    index = this.valueStore.findExact('path', path),
                    record = this.valueStore.getAt(index),
                    text = null;
                
                if (! record) {
                    // try account
                    var accountId = path.substr(1, 40);
                    record = Tine.Felamimail.loadAccountStore().getById(accountId);
                }
                if (record) {
                    this.currentValue.push(path);
                    // always copy/clone record because it can't exist in 2 different stores
                    this.store.add(record.copy());
                    text = this.labelRenderer(record.id, {}, record);
                } else {
                    text = value;
                    this.currentValue.push(value);
                }
                
                return text;
            }
        };

        Tine.Felamimail.FolderFilterModel.superclass.initComponent.call(this);
    }
});

Tine.widgets.grid.FilterToolbar.FILTERS['tine.felamimail.folder.filtermodel'] = Tine.Felamimail.FolderFilterModel;

