/*
 * Tine 2.0 USER CLIENT
 * 
 * license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * copyright   Copyright (c) 2007-2011 Metaways Infosystems GmbH (http://www.metaways.de)
 *
 * FOR MORE DETAILED LICENSE AND COPYRIGHT INFORMATION PLEASE CONSULT THE LICENSE FILE 
 * LOCATED AT: <YOUR TINE 2.0 URL>/LICENSE OR VISIT THE TINE 2.0 HOMEPAGE AT http://www.tine20.org
 */
Ext.ns("Tine.Timetracker");Tine.Timetracker.DurationSpinner=Ext.extend(Ext.ux.form.Spinner,{initComponent:function(){this.preventMark=false;this.strategy=new Ext.ux.form.Spinner.TimeStrategy({incrementValue:15});this.format=this.strategy.format},setValue:function(c){if(!c.toString().match(/:/)){var d=new Date(0);var a=Math.floor(c/60);var b=c-a*60;d.setHours(a);d.setMinutes(b);c=Ext.util.Format.date(d,this.format)}Tine.Timetracker.DurationSpinner.superclass.setValue.call(this,c)},validateValue:function(a){var b=Date.parseDate(a,this.format);return Ext.isDate(b)},getValue:function(){var a=Tine.Timetracker.DurationSpinner.superclass.getValue.call(this);a=a.replace(",",".");if(a&&typeof a=="string"){if(a.search(/:/)!=-1){var c=a.split(":");c[0]=c[0].length==1?"0"+c[0]:c[0];c[1]=c[1].length==1?"0"+c[1]:c[1];a=c.join(":");var b=Date.parseDate(a,this.format);if(!b){this.markInvalid(_("Not a valid time"));return}else{a=b.getHours()*60+b.getMinutes()}}else{if(a>0){if(a<24){a=a*60}}else{this.markInvalid(_("Not a valid time"));return}}}this.setValue(a);return a}});Ext.reg("tinedurationspinner",Tine.Timetracker.DurationSpinner);Ext.ns("Tine.Timetracker","Tine.Timetracker.Model");Tine.Timetracker.Model.TimesheetArray=Tine.Tinebase.Model.genericFields.concat([{name:"id"},{name:"account_id"},{name:"timeaccount_id"},{name:"start_date",type:"date",dateFormat:Date.patterns.ISO8601Short},{name:"start_time",type:"date",dateFormat:Date.patterns.ISO8601Time},{name:"duration"},{name:"description"},{name:"is_billable"},{name:"is_billable_combined"},{name:"is_cleared"},{name:"is_cleared_combined"},{name:"billed_in"},{name:"notes"},{name:"tags"},{name:"customfields"}]);Tine.Timetracker.Model.Timesheet=Tine.Tinebase.data.Record.create(Tine.Timetracker.Model.TimesheetArray,{appName:"Timetracker",modelName:"Timesheet",idProperty:"id",titleProperty:null,recordName:"Timesheet",recordsName:"Timesheets",containerProperty:"timeaccount_id",containerName:"timesheets list",containersName:"timesheets lists",getTitle:function(){var a=this.get("timeaccount_id");if(a){if(typeof(a.get)!=="function"){a=new Tine.Timetracker.Model.Timeaccount(a)}return a.getTitle()}},copyOmitFields:["billed_in","is_cleared"]});Tine.Timetracker.Model.Timesheet.getDefaultData=function(){return{account_id:Tine.Tinebase.registry.get("currentAccount"),duration:"00:30",start_date:new Date(),is_billable:true,timeaccount_id:{account_grants:{editGrant:true}}}};Tine.Timetracker.Model.TimeaccountArray=Tine.Tinebase.Model.genericFields.concat([{name:"id"},{name:"container_id"},{name:"title"},{name:"number"},{name:"description"},{name:"budget"},{name:"budget_unit"},{name:"price"},{name:"price_unit"},{name:"is_open"},{name:"is_billable"},{name:"billed_in"},{name:"status"},{name:"deadline"},{name:"account_grants"},{name:"grants"},{name:"notes"},{name:"tags"}]);Tine.Timetracker.Model.Timeaccount=Tine.Tinebase.data.Record.create(Tine.Timetracker.Model.TimeaccountArray,{appName:"Timetracker",modelName:"Timeaccount",idProperty:"id",titleProperty:"title",recordName:"Time Account",recordsName:"Time Accounts",containerProperty:"container_id",containerName:"timeaccount list",containersName:"timeaccount lists",getTitle:function(){return this.get("number")?(this.get("number")+" "+this.get("title")):false}});Tine.Timetracker.Model.Timeaccount.getDefaultData=function(){return{is_open:1,is_billable:true}};Tine.Timetracker.Model.Timeaccount.getFilterModel=function(){var a=Tine.Tinebase.appMgr.get("Timetracker");return[{label:_("Quick search"),field:"query",operators:["contains"]},{label:a.i18n._("Number"),field:"number"},{label:a.i18n._("Title"),field:"title"},{label:a.i18n._("Description"),field:"description",operators:["contains"]},{label:a.i18n._("Created By"),field:"created_by",valueType:"user"},{label:a.i18n._("Status"),field:"status",filtertype:"timetracker.timeaccountstatus"},{filtertype:"tinebase.tag",app:a}]};Tine.Timetracker.Model.TimeaccountGrant=Ext.data.Record.create([{name:"id"},{name:"account_id"},{name:"account_type"},{name:"account_name"},{name:"book_own",type:"boolean"},{name:"view_all",type:"boolean"},{name:"book_all",type:"boolean"},{name:"manage_billable",type:"boolean"},{name:"exportGrant",type:"boolean"},{name:"manage_all",type:"boolean"}]);Ext.ns("Tine.Timetracker");Tine.Timetracker.MainScreen=Ext.extend(Tine.widgets.MainScreen,{activeContentType:"Timesheet",westPanelXType:"tine.timetracker.treepanel"});Tine.Timetracker.TreePanel=Ext.extend(Tine.widgets.persistentfilter.PickerPanel,{filter:[{field:"model",operator:"equals",value:"Timetracker_Model_TimesheetFilter"}],initComponent:function(){this.filterMountId="Timesheet";this.root={id:"root",leaf:false,expanded:true,children:[{text:this.app.i18n._("Timesheets"),id:"Timesheet",iconCls:"TimetrackerTimesheet",expanded:true,children:[{text:this.app.i18n._("All Timesheets"),id:"alltimesheets",leaf:true}]},{text:this.app.i18n._("Timeaccounts"),id:"Timeaccount",iconCls:"TimetrackerTimeaccount",expanded:true,children:[{text:this.app.i18n._("All Timeaccounts"),id:"alltimeaccounts",leaf:true}]}]};Tine.Timetracker.TreePanel.superclass.initComponent.call(this);this.on("click",function(a){if(a.attributes.isPersistentFilter!=true){var b=a.getPath().split("/")[2];this.app.getMainScreen().activeContentType=b;this.app.getMainScreen().show()}},this)},afterRender:function(){Tine.Timetracker.TreePanel.superclass.afterRender.call(this);var a=this.app.getMainScreen().activeContentType;this.expandPath("/root/"+a+"/alltimesheets");this.selectPath("/root/"+a+"/alltimesheets")},onFilterSelect:function(){this.app.getMainScreen().activeContentType="Timesheet";this.app.getMainScreen().show();this.supr().onFilterSelect.apply(this,arguments)},getFilterPlugin:function(){if(!this.filterPlugin){var a=this;this.filterPlugin=new Tine.widgets.grid.FilterPlugin({})}return this.filterPlugin},getFavoritesPanel:function(){return this}});Ext.reg("tine.timetracker.treepanel",Tine.Timetracker.TreePanel);Tine.Timetracker.timesheetBackend=new Tine.Tinebase.data.RecordProxy({appName:"Timetracker",modelName:"Timesheet",recordClass:Tine.Timetracker.Model.Timesheet});Tine.Timetracker.timeaccountBackend=new Tine.Tinebase.data.RecordProxy({appName:"Timetracker",modelName:"Timeaccount",recordClass:Tine.Timetracker.Model.Timeaccount});Ext.ns("Tine.Timetracker");Tine.Timetracker.TimeAccountSelect=Ext.extend(Ext.form.ComboBox,{recordProxy:Tine.Timetracker.timeaccountBackend,onlyBookable:true,showClosed:false,blurOnSelect:false,defaultPaging:{start:0,limit:50},record:null,itemSelector:"div.search-item",typeAhead:false,minChars:3,pageSize:10,forceSelection:true,displayField:"displaytitle",triggerAction:"all",selectOnFocus:true,initComponent:function(){this.app=Tine.Tinebase.appMgr.get("Timetracker");this.store=new Ext.data.Store({fields:Tine.Timetracker.Model.TimeaccountArray.concat({name:"displaytitle"}),proxy:this.recordProxy,reader:this.recordProxy.getReader(),remoteSort:true,sortInfo:{field:"number",dir:"ASC"},listeners:{scope:this,beforeload:this.onStoreBeforeload}});this.tpl=new Ext.XTemplate('<tpl for="."><div class="search-item">','<span>{[this.encode(values.number)]} - {[this.encode(values.title)]}<tpl if="is_open != 1 ">&nbsp;<i>('+this.app.i18n._("closed")+")</i></tpl>","</span></div></tpl>",{encode:function(a){if(a){return Ext.util.Format.htmlEncode(a)}else{return""}}});Tine.Timetracker.TimeAccountSelect.superclass.initComponent.call(this);if(this.blurOnSelect){this.on("select",function(){this.fireEvent("blur",this)},this)}},getValue:function(){return this.record?this.record.get("id"):null},setValue:function(a){if(a){if(typeof(a.get)=="function"){this.record=a}else{if(typeof(a)=="string"){}else{this.record=new Tine.Timetracker.Model.Timeaccount(a,a.id)}}var b=this.record?this.record.getTitle():false;if(b){Tine.Timetracker.TimeAccountSelect.superclass.setValue.call(this,b)}}},onSelect:function(a){a.set("displaytitle",a.getTitle());this.record=a;Tine.Timetracker.TimeAccountSelect.superclass.onSelect.call(this,a)},onStoreBeforeload:function(a,b){b.params=b.params||{};b.params.filter=[{field:"query",operator:"contains",value:a.baseParams.query}];if(this.onlyBookable){b.params.filter.push({field:"isBookable",operator:"equals",value:1})}if(this.showClosed){b.params.filter.push({field:"showClosed",operator:"equals",value:1})}}});Tine.Timetracker.TimeAccountGridFilter=Ext.extend(Tine.widgets.grid.FilterModel,{isForeignFilter:true,foreignField:"id",ownField:"timeaccount_id",initComponent:function(){Tine.widgets.tags.TagFilter.superclass.initComponent.call(this);this.subFilterModels=[];this.app=Tine.Tinebase.appMgr.get("Timetracker");this.label=this.app.i18n._("Time Account");this.operators=["equals"]},getSubFilters:function(){var a=Tine.Timetracker.Model.Timeaccount.getFilterModel();Ext.each(a,function(b){if(b.field!="query"){this.subFilterModels.push(Tine.widgets.grid.FilterToolbar.prototype.createFilterModel.call(this,b))}},this);return this.subFilterModels},valueRenderer:function(b,a){var c=new Tine.Timetracker.TimeAccountSelect({filter:b,onlyBookable:false,showClosed:true,blurOnSelect:true,width:200,listWidth:500,id:"tw-ftb-frow-valuefield-"+b.id,value:b.data.value?b.data.value:this.defaultValue,renderTo:a});c.on("specialkey",function(f,d){if(d.getKey()==d.ENTER){this.onFiltertrigger()}},this);return c}});Tine.widgets.grid.FilterToolbar.FILTERS["timetracker.timeaccount"]=Tine.Timetracker.TimeAccountGridFilter;Tine.Timetracker.TimeAccountStatusGridFilter=Ext.extend(Tine.widgets.grid.FilterModel,{field:"timeaccount_status",valueType:"string",defaultValue:"to bill",initComponent:function(){Tine.Timetracker.TimeAccountStatusGridFilter.superclass.initComponent.call(this);this.app=Tine.Tinebase.appMgr.get("Timetracker");this.label=this.label?this.label:this.app.i18n._("Time Account - Status");this.operators=["equals"]},valueRenderer:function(b,a){var c=new Ext.form.ComboBox({filter:b,width:200,id:"tw-ftb-frow-valuefield-"+b.id,value:b.data.value?b.data.value:this.defaultValue,renderTo:a,mode:"local",forceSelection:true,blurOnSelect:true,triggerAction:"all",store:[["not yet billed",this.app.i18n._("not yet billed")],["to bill",this.app.i18n._("to bill")],["billed",this.app.i18n._("billed")]]});c.on("specialkey",function(f,d){if(d.getKey()==d.ENTER){this.onFiltertrigger()}},this);return c}});Tine.widgets.grid.FilterToolbar.FILTERS["timetracker.timeaccountstatus"]=Tine.Timetracker.TimeAccountStatusGridFilter;Ext.namespace("Tine.Timetracker");Tine.Timetracker.TimeaccountGridPanel=Ext.extend(Tine.widgets.grid.GridPanel,{recordClass:Tine.Timetracker.Model.Timeaccount,defaultSortInfo:{field:"creation_time",direction:"DESC"},gridConfig:{loadMask:true,autoExpandColumn:"title"},copyEditAction:true,initComponent:function(){this.recordProxy=Tine.Timetracker.timeaccountBackend;this.actionToolbarItems=this.getToolbarItems();this.gridConfig.cm=this.getColumnModel();this.initFilterToolbar();this.plugins=this.plugins||[];this.plugins.push(this.action_showClosedToggle,this.filterToolbar);Tine.Timetracker.TimeaccountGridPanel.superclass.initComponent.call(this);this.action_addInNewWindow.setDisabled(!Tine.Tinebase.common.hasRight("manage","Timetracker","timeaccounts"));this.action_editInNewWindow.requiredGrant="editGrant"},initFilterToolbar:function(){this.filterToolbar=new Tine.widgets.grid.FilterToolbar({app:this.app,filterModels:Tine.Timetracker.Model.Timeaccount.getFilterModel(),defaultFilter:"query",filters:[],plugins:[new Tine.widgets.grid.FilterToolbarQuickFilterPlugin()]})},getColumnModel:function(){return new Ext.grid.ColumnModel({defaults:{sortable:true,resizable:true},columns:[{id:"tags",header:this.app.i18n._("Tags"),width:50,dataIndex:"tags",sortable:false,renderer:Tine.Tinebase.common.tagsRenderer},{id:"number",header:this.app.i18n._("Number"),width:100,dataIndex:"number"},{id:"title",header:this.app.i18n._("Title"),width:350,dataIndex:"title"},{id:"status",header:this.app.i18n._("Status"),width:150,dataIndex:"status",renderer:this.statusRenderer.createDelegate(this)},{id:"budget",header:this.app.i18n._("Budget"),width:100,dataIndex:"budget"},{id:"billed_in",hidden:true,header:this.app.i18n._("Cleared in"),width:150,dataIndex:"billed_in"}]})},statusRenderer:function(a){return this.app.i18n._hidden(a)},getToolbarItems:function(){this.exportButton=new Ext.Action({text:_("Export"),iconCls:"action_export",scope:this,requiredGrant:"readGrant",disabled:true,allowMultiple:true,menu:{items:[new Tine.widgets.grid.ExportButton({text:this.app.i18n._("Export as ODS"),format:"ods",exportFunction:"Timetracker.exportTimeaccounts",gridPanel:this})]}});this.action_showClosedToggle=new Tine.widgets.grid.FilterButton({text:this.app.i18n._("Show closed"),iconCls:"action_showArchived",field:"showClosed",scale:"medium",rowspan:2,iconAlign:"top"});return[Ext.apply(new Ext.Button(this.exportButton),{scale:"medium",rowspan:2,iconAlign:"top"}),this.action_showClosedToggle]}});Ext.namespace("Tine.Timetracker");Tine.Timetracker.TimeaccountEditDialog=Ext.extend(Tine.widgets.dialog.EditDialog,{windowNamePrefix:"TimeaccountEditWindow_",appName:"Timetracker",recordClass:Tine.Timetracker.Model.Timeaccount,recordProxy:Tine.Timetracker.timeaccountBackend,loadRecord:false,tbarItems:[{xtype:"widget-activitiesaddbutton"}],updateToolbars:function(){},onRecordLoad:function(){this.getGrantsGrid();var a=this.record.get("grants")||[];this.grantsStore.loadData({results:a});Tine.Timetracker.TimeaccountEditDialog.superclass.onRecordLoad.call(this)},onRecordUpdate:function(){Tine.Timetracker.TimeaccountEditDialog.superclass.onRecordUpdate.call(this);this.record.set("grants","");var a=[];this.grantsStore.each(function(b){a.push(b.data)});this.record.set("grants",a)},getFormItems:function(){return{xtype:"tabpanel",border:false,plain:true,activeTab:0,items:[{title:this.app.i18n._("Time Account"),autoScroll:true,border:false,frame:true,layout:"border",items:[{region:"center",xtype:"columnform",labelAlign:"top",formDefaults:{xtype:"textfield",anchor:"100%",labelSeparator:"",columnWidth:0.333},items:[[{fieldLabel:this.app.i18n._("Number"),name:"number",allowBlank:false},{columnWidth:0.666,fieldLabel:this.app.i18n._("Title"),name:"title",allowBlank:false}],[{columnWidth:1,fieldLabel:this.app.i18n._("Description"),xtype:"textarea",name:"description",height:150}],[{fieldLabel:this.app.i18n._("Unit"),name:"price_unit"},{xtype:"numberfield",fieldLabel:this.app.i18n._("Unit Price"),name:"price",allowNegative:false},{fieldLabel:this.app.i18n._("Budget"),name:"budget"},{fieldLabel:this.app.i18n._("Status"),name:"is_open",xtype:"combo",mode:"local",forceSelection:true,triggerAction:"all",store:[[0,this.app.i18n._("closed")],[1,this.app.i18n._("open")]]},{fieldLabel:this.app.i18n._("Billed"),name:"status",xtype:"combo",mode:"local",forceSelection:true,triggerAction:"all",value:"not yet billed",store:[["not yet billed",this.app.i18n._("not yet billed")],["to bill",this.app.i18n._("to bill")],["billed",this.app.i18n._("billed")]]},{fieldLabel:this.app.i18n._("Cleared In"),name:"billed_in",xtype:"textfield"},{fieldLabel:this.app.i18n._("Booking deadline"),name:"deadline",xtype:"combo",mode:"local",forceSelection:true,triggerAction:"all",value:"none",store:[["none",this.app.i18n._("none")],["lastweek",this.app.i18n._("last week")]]},{hideLabel:true,boxLabel:this.app.i18n._("Timesheets are billable"),name:"is_billable",xtype:"checkbox",columnWidth:0.666}]]},{layout:"accordion",animate:true,region:"east",width:210,split:true,collapsible:true,collapseMode:"mini",header:false,margins:"0 5 0 5",border:true,items:[new Tine.widgets.activities.ActivitiesPanel({app:"Timetracker",showAddNoteForm:false,border:false,bodyStyle:"border:1px solid #B5B8C8;"}),new Tine.widgets.tags.TagPanel({app:"Timetracker",border:false,bodyStyle:"border:1px solid #B5B8C8;"})]}]},{title:this.app.i18n._("Access"),layout:"fit",items:[this.getGrantsGrid()]},new Tine.widgets.activities.ActivitiesTabPanel({app:this.appName,record_id:this.record.id,record_model:this.appName+"_Model_"+this.recordClass.getMeta("modelName")})]}},getGrantsGrid:function(){if(!this.grantsGrid){this.grantsStore=new Ext.data.JsonStore({root:"results",totalProperty:"totalcount",id:"account_id",fields:Tine.Timetracker.Model.TimeaccountGrant});var a=[new Ext.ux.grid.CheckColumn({header:this.app.i18n._("Book Own"),dataIndex:"book_own",tooltip:_("The grant to add Timesheets to this Timeaccount"),width:55}),new Ext.ux.grid.CheckColumn({header:this.app.i18n._("View All"),tooltip:_("The grant to view Timesheets of other users"),dataIndex:"view_all",width:55}),new Ext.ux.grid.CheckColumn({header:this.app.i18n._("Book All"),tooltip:_("The grant to add Timesheets for other users"),dataIndex:"book_all",width:55}),new Ext.ux.grid.CheckColumn({header:this.app.i18n._("Manage Clearing"),tooltip:_("The grant to manage clearing of Timesheets"),dataIndex:"manage_billable",width:55}),new Ext.ux.grid.CheckColumn({header:this.app.i18n._("Export"),tooltip:_("The grant to export Timesheets of Timeaccount"),dataIndex:"exportGrant",width:55}),new Ext.ux.grid.CheckColumn({header:this.app.i18n._("Manage All"),tooltip:_("Includes all other grants"),dataIndex:"manage_all",width:55})];this.grantsGrid=new Tine.widgets.account.PickerGridPanel({selectType:"both",title:this.app.i18n._("Permissions"),store:this.grantsStore,hasAccountPrefix:true,configColumns:a,selectAnyone:false,selectTypeDefault:"group",recordClass:Tine.Tinebase.Model.Grant})}return this.grantsGrid}});Tine.Timetracker.TimeaccountEditDialog.openWindow=function(a){var c=(a.record&&a.record.id)?a.record.id:0;var b=Tine.WindowFactory.getWindow({width:800,height:500,name:Tine.Timetracker.TimeaccountEditDialog.prototype.windowNamePrefix+c,contentPanelConstructor:"Tine.Timetracker.TimeaccountEditDialog",contentPanelConstructorConfig:a});return b};Ext.namespace("Tine.Timetracker");Tine.Timetracker.TimesheetGridPanel=Ext.extend(Tine.widgets.grid.GridPanel,{recordClass:Tine.Timetracker.Model.Timesheet,defaultSortInfo:{field:"start_date",direction:"DESC"},gridConfig:{loadMask:true,autoExpandColumn:"description"},copyEditAction:true,initComponent:function(){this.recordProxy=Tine.Timetracker.timesheetBackend;this.gridConfig.cm=this.getColumnModel();this.initFilterToolbar();this.initDetailsPanel();this.plugins=this.plugins||[];this.plugins.push(this.filterToolbar);this.evalGrants=!Tine.Tinebase.common.hasRight("manage","Timetracker","timeaccounts");Tine.Timetracker.TimesheetGridPanel.superclass.initComponent.call(this)},onMassUpdate:function(c,g){var b;switch(c.field){case"is_billable":case"is_cleared":b=new Ext.form.Checkbox({hideLabel:true,boxLabel:c.text,name:c.field});break;default:b=new Ext.form.TextField({fieldLabel:c.text,name:c.field})}var h=this.grid.getSelectionModel();var d=h.getSelectionFilter();var a=new Ext.FormPanel({border:false,labelAlign:"top",buttonAlign:"right",items:b,defaults:{anchor:"90%"}});var f=Tine.WindowFactory.getWindow({title:String.format(_("Update {0} records"),h.getCount()),modal:true,width:300,height:150,layout:"fit",plain:true,closeAction:"close",autoScroll:true,items:a,buttons:[{text:_("Cancel"),iconCls:"action_cancel",handler:function(){f.close()}},{text:_("Ok"),iconCls:"action_saveAndClose",scope:this,handler:function(){var i=b.name,e=b.getValue();update={},update[i]=e;f.close();this.grid.loadMask.show();if(i=="is_cleared"&&!update[i]){update.billed_in=""}if(i=="billed_in"&&update[i].length>0){update.is_cleared=true}this.recordProxy.updateRecords(d,update,{scope:this,success:function(j){this.store.load();Ext.Msg.show({title:_("Success"),msg:String.format(_("Updated {0} records"),j.count),buttons:Ext.Msg.OK,animEl:"elId",icon:Ext.MessageBox.INFO})}})}}]})},initFilterToolbar:function(){this.filterToolbar=new Tine.widgets.grid.FilterToolbar({app:this.app,allowSaving:true,filterModels:[{label:this.app.i18n._("Account"),field:"account_id",valueType:"user"},{label:this.app.i18n._("Date"),field:"start_date",valueType:"date",pastOnly:true},{label:this.app.i18n._("Description"),field:"description",defaultOperator:"contains"},{label:this.app.i18n._("Billable"),field:"is_billable",valueType:"bool",defaultValue:true},{label:this.app.i18n._("Cleared"),field:"is_cleared",valueType:"bool",defaultValue:false},{filtertype:"tinebase.tag",app:this.app},{filtertype:"timetracker.timeaccount"}].concat(this.getCustomfieldFilters()),defaultFilter:"start_date",filters:[{field:"start_date",operator:"within",value:"weekThis"},{field:"account_id",operator:"equals",value:Tine.Tinebase.registry.get("currentAccount")}]})},getColumnModel:function(){var a=[{id:"tags",header:this.app.i18n._("Tags"),width:50,dataIndex:"tags",sortable:false,renderer:Tine.Tinebase.common.tagsRenderer},{id:"start_date",header:this.app.i18n._("Date"),width:120,dataIndex:"start_date",renderer:Tine.Tinebase.common.dateRenderer},{id:"start_time",header:this.app.i18n._("Start time"),width:100,dataIndex:"start_time",hidden:true,renderer:Tine.Tinebase.common.timeRenderer},{id:"timeaccount_id",header:this.app.i18n._("Time Account"),width:500,dataIndex:"timeaccount_id",renderer:this.rendererTimeaccountId},{id:"timeaccount_closed",header:this.app.i18n._("Time Account closed"),width:100,dataIndex:"timeaccount_closed",hidden:true,renderer:this.rendererTimeaccountClosed},{id:"description",header:this.app.i18n._("Description"),width:400,dataIndex:"description",hidden:true},{id:"is_billable",header:this.app.i18n._("Billable"),width:100,dataIndex:"is_billable_combined",renderer:Tine.Tinebase.common.booleanRenderer},{id:"is_cleared",header:this.app.i18n._("Cleared"),width:100,dataIndex:"is_cleared_combined",hidden:true,renderer:Tine.Tinebase.common.booleanRenderer},{id:"billed_in",header:this.app.i18n._("Cleared in"),width:150,dataIndex:"billed_in",hidden:true},{id:"account_id",header:this.app.i18n._("Account"),width:350,dataIndex:"account_id",renderer:Tine.Tinebase.common.usernameRenderer},{id:"duration",header:this.app.i18n._("Duration"),width:150,dataIndex:"duration",renderer:Tine.Tinebase.common.minutesRenderer}].concat(this.getModlogColumns());return new Ext.grid.ColumnModel({defaults:{sortable:true,resizable:true},columns:a.concat(this.getCustomfieldColumns())})},rendererTimeaccountId:function(a){return new Tine.Timetracker.Model.Timeaccount(a).getTitle()},rendererTimeaccountClosed:function(e,c,d){var f=(d.data.timeaccount_id.is_open=="1");return Tine.Tinebase.common.booleanRenderer(!f)},initDetailsPanel:function(){this.detailsPanel=new Tine.widgets.grid.DetailsPanel({gridpanel:this,defaultTpl:new Ext.XTemplate('<div class="preview-panel-timesheet-nobreak">',"<!-- Preview timeframe -->",'<div class="preview-panel preview-panel-timesheet-left">','<div class="bordercorner_1"></div>','<div class="bordercorner_2"></div>','<div class="bordercorner_3"></div>','<div class="bordercorner_4"></div>','<div class="preview-panel-declaration"></div>','<div class="preview-panel-timesheet-leftside preview-panel-left">','<span class="preview-panel-bold">',"<br/>","<br/>","<br/>","<br/>","</span>","</div>",'<div class="preview-panel-timesheet-rightside preview-panel-left">','<span class="preview-panel-nonbold">',"<br/>","<br/>","<br/>","<br/>","</span>","</div>","</div>","<!-- Preview summary -->",'<div class="preview-panel-timesheet-right">','<div class="bordercorner_gray_1"></div>','<div class="bordercorner_gray_2"></div>','<div class="bordercorner_gray_3"></div>','<div class="bordercorner_gray_4"></div>','<div class="preview-panel-declaration"></div>','<div class="preview-panel-timesheet-leftside preview-panel-left">','<span class="preview-panel-bold">',this.app.i18n._("Total Timesheets")+"<br/>",this.app.i18n._("Billable Timesheets")+"<br/>",this.app.i18n._("Total Time")+"<br/>",this.app.i18n._("Time of Billable Timesheets")+"<br/>","</span>","</div>",'<div class="preview-panel-timesheet-rightside preview-panel-left">','<span class="preview-panel-nonbold">',"{count}<br/>","{countbillable}<br/>","{sum}<br/>","{sumbillable}<br/>","</span>","</div>","</div>","</div>"),showDefault:function(a){var b={count:this.gridpanel.store.proxy.jsonReader.jsonData.totalcount,countbillable:(this.gridpanel.store.proxy.jsonReader.jsonData.totalcountbillable)?this.gridpanel.store.proxy.jsonReader.jsonData.totalcountbillable:0,sum:Tine.Tinebase.common.minutesRenderer(this.gridpanel.store.proxy.jsonReader.jsonData.totalsum),sumbillable:Tine.Tinebase.common.minutesRenderer(this.gridpanel.store.proxy.jsonReader.jsonData.totalsumbillable)};this.defaultTpl.overwrite(a,b)},showMulti:function(c,a){var b={count:c.getCount(),countbillable:0,sum:0,sumbillable:0};c.each(function(d){b.sum=b.sum+parseInt(d.data.duration);if(d.data.is_billable_combined=="1"){b.countbillable++;b.sumbillable=b.sumbillable+parseInt(d.data.duration)}});b.sum=Tine.Tinebase.common.minutesRenderer(b.sum);b.sumbillable=Tine.Tinebase.common.minutesRenderer(b.sumbillable);this.defaultTpl.overwrite(a,b)},tpl:new Ext.XTemplate('<div class="preview-panel-timesheet-nobreak">',"<!-- Preview beschreibung -->",'<div class="preview-panel preview-panel-timesheet-left">','<div class="bordercorner_1"></div>','<div class="bordercorner_2"></div>','<div class="bordercorner_3"></div>','<div class="bordercorner_4"></div>','<div class="preview-panel-declaration"></div>','<div class="preview-panel-timesheet-description preview-panel-left" ext:qtip="{[this.encode(values.description)]}">','<span class="preview-panel-nonbold">','{[this.encode(values.description, "longtext")]}',"<br/>","</span>","</div>","</div>","<!-- Preview detail-->",'<div class="preview-panel-timesheet-right">','<div class="bordercorner_gray_1"></div>','<div class="bordercorner_gray_2"></div>','<div class="bordercorner_gray_3"></div>','<div class="bordercorner_gray_4"></div>','<div class="preview-panel-declaration"></div>','<div class="preview-panel-timesheet-leftside preview-panel-left">',"</div>",'<div class="preview-panel-timesheet-rightside preview-panel-left">','<span class="preview-panel-nonbold">',"<br/>","<br/>","<br/>","<br/>","</span>","</div>","</div>","</div>",{encode:function(c,a,b){if(c){if(a){switch(a){case"longtext":c=Ext.util.Format.ellipsis(c,150);break;default:c+=a}}var d=Ext.util.Format.htmlEncode(c);d=Ext.util.Format.nl2br(d);return d}else{return""}}})})},initActions:function(){this.actions_exportTimesheet=new Ext.Action({text:this.app.i18n._("Export Timesheets"),iconCls:"action_export",scope:this,requiredGrant:"exportGrant",disabled:true,allowMultiple:true,menu:{items:[new Tine.widgets.grid.ExportButton({text:this.app.i18n._("Export as ODS"),format:"ods",iconCls:"tinebase-action-export-ods",exportFunction:"Timetracker.exportTimesheets",gridPanel:this}),new Tine.widgets.grid.ExportButton({text:this.app.i18n._("Export as CSV"),format:"csv",iconCls:"tinebase-action-export-csv",exportFunction:"Timetracker.exportTimesheets",gridPanel:this}),new Tine.widgets.grid.ExportButton({text:this.app.i18n._("Export as ..."),iconCls:"tinebase-action-export-xls",exportFunction:"Timetracker.exportTimesheets",showExportDialog:true,gridPanel:this})]}});this.actionUpdater.addActions([this.actions_exportTimesheet]);Tine.Timetracker.TimesheetGridPanel.superclass.initActions.call(this)},getActionToolbarItems:function(){return[Ext.apply(new Ext.Button(this.actions_exportTimesheet),{scale:"medium",rowspan:2,iconAlign:"top"})]},getContextMenuItems:function(){var a=["-",this.actions_exportTimesheet,"-",{text:_("Mass Update"),iconCls:"action_edit",disabled:!Tine.Tinebase.common.hasRight("manage","Timetracker","timeaccounts"),scope:this,menu:{items:['<b class="x-ux-menu-title">'+_("Update field:")+"</b>",{text:this.app.i18n._("Billable"),field:"is_billable",scope:this,handler:this.onMassUpdate},{text:this.app.i18n._("Cleared"),field:"is_cleared",scope:this,handler:this.onMassUpdate},{text:this.app.i18n._("Cleared in"),field:"billed_in",scope:this,handler:this.onMassUpdate}]}}];return a}});Ext.namespace("Tine.Timetracker");Tine.Timetracker.TimesheetEditDialog=Ext.extend(Tine.widgets.dialog.EditDialog,{windowNamePrefix:"TimesheetEditWindow_",appName:"Timetracker",recordClass:Tine.Timetracker.Model.Timesheet,recordProxy:Tine.Timetracker.timesheetBackend,loadRecord:false,tbarItems:[{xtype:"widget-activitiesaddbutton"}],updateToolbars:function(a){this.onTimeaccountUpdate();Tine.Timetracker.TimesheetEditDialog.superclass.updateToolbars.call(this,a,"timeaccount_id")},onTimeaccountUpdate:function(f,c){var d=Tine.Tinebase.common.hasRight("manage","Timetracker","timeaccounts");var e=false;var b=false;var a=c?c.get("account_grants"):(this.record.get("timeaccount_id")?this.record.get("timeaccount_id").account_grants:{});if(a){this.getForm().findField("account_id").setDisabled(!(a.book_all||a.manage_all||d));e=!(a.manage_billable||a.manage_all||d);b=!(a.manage_all||d);this.getForm().findField("billed_in").setDisabled(!(a.manage_all||d))}if(c){e=e||c.data.is_billable=="0"||this.record.get("timeaccount_id").is_billable=="0";b=b||c.data.is_billable=="0"||this.record.get("timeaccount_id").is_billable=="0"}this.getForm().findField("is_billable").setDisabled(e);this.getForm().findField("is_cleared").setDisabled(b);if(this.record.id==0&&c){this.getForm().findField("is_billable").setValue(c.data.is_billable)}},onClearedUpdate:function(b,a){this.getForm().findField("billed_in").setDisabled(!a)},getFormItems:function(){return{xtype:"tabpanel",border:false,plain:true,activeTab:0,items:[{title:this.app.i18n._("Timesheet"),autoScroll:true,border:false,frame:true,layout:"border",items:[{region:"center",xtype:"columnform",labelAlign:"top",formDefaults:{xtype:"textfield",anchor:"100%",labelSeparator:"",columnWidth:0.333},items:[[new Tine.Timetracker.TimeAccountSelect({columnWidth:1,fieldLabel:this.app.i18n._("Time Account"),emptyText:this.app.i18n._("Select Time Account..."),loadingText:this.app.i18n._("Searching..."),allowBlank:false,name:"timeaccount_id",listeners:{scope:this,render:function(a){a.focus(false,250)},select:this.onTimeaccountUpdate}})],[{fieldLabel:this.app.i18n._("Duration"),name:"duration",allowBlank:false,xtype:"tinedurationspinner"},{fieldLabel:this.app.i18n._("Date"),name:"start_date",allowBlank:false,xtype:"datefield"},{fieldLabel:this.app.i18n._("Start"),emptyText:this.app.i18n._("not set"),name:"start_time",xtype:"timefield"}],[{columnWidth:1,fieldLabel:this.app.i18n._("Description"),emptyText:this.app.i18n._("Enter description..."),name:"description",allowBlank:false,xtype:"textarea",height:150}],[new Tine.Addressbook.SearchCombo({allowBlank:false,columnWidth:1,disabled:true,useAccountRecord:true,userOnly:true,nameField:"n_fileas",fieldLabel:this.app.i18n._("Account"),name:"account_id"}),{columnWidth:0.25,disabled:true,boxLabel:this.app.i18n._("Billable"),name:"is_billable",xtype:"checkbox"},{columnWidth:0.25,disabled:true,boxLabel:this.app.i18n._("Cleared"),name:"is_cleared",xtype:"checkbox",listeners:{scope:this,check:this.onClearedUpdate}},{columnWidth:0.5,disabled:true,fieldLabel:this.app.i18n._("Cleared In"),name:"billed_in",xtype:"textfield"}]]},{layout:"accordion",animate:true,region:"east",width:210,split:true,collapsible:true,collapseMode:"mini",header:false,margins:"0 5 0 5",border:true,items:[new Tine.widgets.activities.ActivitiesPanel({app:"Timetracker",showAddNoteForm:false,border:false,bodyStyle:"border:1px solid #B5B8C8;"}),new Tine.widgets.tags.TagPanel({app:"Timetracker",border:false,bodyStyle:"border:1px solid #B5B8C8;"})]}]},new Tine.Tinebase.widgets.customfields.CustomfieldsPanel({recordClass:this.recordClass,disabled:(Tine[this.appName].registry.get("customfields").length==0),quickHack:this}),new Tine.widgets.activities.ActivitiesTabPanel({app:this.appName,record_id:this.record.id,record_model:this.appName+"_Model_"+this.recordClass.getMeta("modelName")})]}},onRequestFailed:function(a,b){if(a.code&&a.code==902){Ext.MessageBox.alert(this.app.i18n._("Failed"),String.format(this.app.i18n._("Could not save {0}."),this.i18nRecordName)+" ( "+this.app.i18n._("Booking deadline for this Timeaccount has been exceeded.")+")")}else{Tine.Tinebase.ExceptionHandler.handleRequestException(a)}this.loadMask.hide()}});Tine.Timetracker.TimesheetEditDialog.openWindow=function(a){var c=(a.record&&a.record.id)?a.record.id:0;var b=Tine.WindowFactory.getWindow({width:800,height:500,name:Tine.Timetracker.TimesheetEditDialog.prototype.windowNamePrefix+c,contentPanelConstructor:"Tine.Timetracker.TimesheetEditDialog",contentPanelConstructorConfig:a});return b};