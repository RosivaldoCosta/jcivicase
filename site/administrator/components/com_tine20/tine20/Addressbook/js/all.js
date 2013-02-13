/*
 * Tine 2.0 USER CLIENT
 * 
 * license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * copyright   Copyright (c) 2007-2011 Metaways Infosystems GmbH (http://www.metaways.de)
 *
 * FOR MORE DETAILED LICENSE AND COPYRIGHT INFORMATION PLEASE CONSULT THE LICENSE FILE 
 * LOCATED AT: <YOUR TINE 2.0 URL>/LICENSE OR VISIT THE TINE 2.0 HOMEPAGE AT http://www.tine20.org
 */
Ext.ns("Tine.Addressbook.Model");Tine.Addressbook.Model.ContactArray=Tine.Tinebase.Model.genericFields.concat([{name:"id"},{name:"tid"},{name:"private"},{name:"cat_id"},{name:"n_family",label:"Last Name"},{name:"n_given",label:"First Name"},{name:"n_middle",label:"Middle Name"},{name:"n_prefix",label:"Title"},{name:"n_suffix",label:"Suffix"},{name:"n_fn",label:"Display Name"},{name:"n_fileas"},{name:"bday",label:"Birthday",type:"date",dateFormat:Date.patterns.ISO8601Long},{name:"org_name",label:"Company"},{name:"org_unit",label:"Unit"},{name:"salutation_id",label:"Salutation"},{name:"title",label:"Job Title"},{name:"role",label:"Job Role"},{name:"assistent"},{name:"room",label:"Room"},{name:"adr_one_street",label:"Street (Company Address)"},{name:"adr_one_street2",label:"Street 2 (Company Address)"},{name:"adr_one_locality",label:"City (Company Address)"},{name:"adr_one_region",label:"Region (Company Address)"},{name:"adr_one_postalcode",label:"Postal Code (Company Address)"},{name:"adr_one_countryname",label:"Country (Company Address)"},{name:"label"},{name:"adr_two_street",label:"Street (Private Address)"},{name:"adr_two_street2",label:"Street 2 (Private Address)"},{name:"adr_two_locality",label:"City (Private Address)"},{name:"adr_two_region",label:"Region (Private Address)"},{name:"adr_two_postalcode",label:"Postal Code (Private Address)"},{name:"adr_two_countryname",label:"Country (Private Address)"},{name:"tel_work",label:"Phone"},{name:"tel_cell",label:"Mobile"},{name:"tel_fax",label:"Fax"},{name:"tel_assistent"},{name:"tel_car"},{name:"tel_pager"},{name:"tel_home",label:"Phone (private)"},{name:"tel_fax_home",label:"Fax (private)"},{name:"tel_cell_private",label:"Mobile (private)"},{name:"tel_other"},{name:"tel_prefer"},{name:"email",label:"E-Mail"},{name:"email_home",label:"E-Mail (private)"},{name:"url",label:"Web"},{name:"url_home",label:"Web (private)"},{name:"freebusy_uri"},{name:"calendar_uri"},{name:"note",label:"Description"},{name:"tz"},{name:"lon"},{name:"lat"},{name:"pubkey"},{name:"jpegphoto"},{name:"account_id"},{name:"tags"},{name:"notes"},{name:"relations"},{name:"customfields"},{name:"type"}]);Tine.Addressbook.Model.Contact=Tine.Tinebase.data.Record.create(Tine.Addressbook.Model.ContactArray,{appName:"Addressbook",modelName:"Contact",idProperty:"id",titleProperty:"n_fn",recordName:"Contact",recordsName:"Contacts",containerProperty:"container_id",containerName:"Addressbook",containersName:"Addressbooks",copyOmitFields:["account_id","type"],hasEmail:function(){return(this.get("email")&&this.get("email")!=""||this.get("email_home")&&this.get("email_home")!="")}});Tine.Addressbook.Model.Contact.getFilterModel=function(){var b=Tine.Tinebase.appMgr.get("Addressbook");var a=[["contact",b.i18n._("Contact")],["user",b.i18n._("User Account")]];return[{label:_("Quick search"),field:"query",operators:["contains"]},{filtertype:"tine.widget.container.filtermodel",app:b,recordClass:Tine.Addressbook.Model.Contact},{label:b.i18n._("First Name"),field:"n_given"},{label:b.i18n._("Last Name"),field:"n_family"},{label:b.i18n._("Company"),field:"org_name"},{label:b.i18n._("Unit"),field:"org_unit"},{label:b.i18n._("Phone"),field:"telephone",operators:["contains"]},{label:b.i18n._("Job Title"),field:"title"},{label:b.i18n._("Job Role"),field:"role"},{label:b.i18n._("Note"),field:"note"},{filtertype:"tinebase.tag",app:b},{label:b.i18n._("Street")+" ("+b.i18n._("Company Address")+")",field:"adr_one_street",defaultOperator:"equals"},{label:b.i18n._("Postal Code")+" ("+b.i18n._("Company Address")+")",field:"adr_one_postalcode",defaultOperator:"equals"},{label:b.i18n._("City")+"  ("+b.i18n._("Company Address")+")",field:"adr_one_locality"},{label:b.i18n._("Street")+" ("+b.i18n._("Private Address")+")",field:"adr_two_street",defaultOperator:"equals"},{label:b.i18n._("Postal Code")+" ("+b.i18n._("Private Address")+")",field:"adr_two_postalcode",defaultOperator:"equals"},{label:b.i18n._("City")+" ("+b.i18n._("Private Address")+")",field:"adr_two_locality"},{label:b.i18n._("Type"),defaultValue:"contact",valueType:"combo",field:"type",store:a},{label:b.i18n._("Last modified"),field:"last_modified_time",valueType:"date"},{label:b.i18n._("Last modifier"),field:"last_modified_by",valueType:"user"},{label:b.i18n._("Creation Time"),field:"creation_time",valueType:"date"},{label:b.i18n._("Creator"),field:"created_by",valueType:"user"}]};Tine.Addressbook.contactBackend=new Tine.Tinebase.data.RecordProxy({appName:"Addressbook",modelName:"Contact",recordClass:Tine.Addressbook.Model.Contact});Tine.Addressbook.Model.Salutation=Ext.data.Record.create([{name:"id"},{name:"name"},{name:"gender"},{name:"image_path"}]);Tine.Addressbook.Model.List=Tine.Tinebase.data.Record.create([{name:"id"},{name:"container_id"},{name:"created_by"},{name:"creation_time"},{name:"last_modified_by"},{name:"last_modified_time"},{name:"is_deleted"},{name:"deleted_time"},{name:"deleted_by"},{name:"name"},{name:"description"},{name:"members"},{name:"email"},{name:"type"},{name:"group_id"}],{appName:"Addressbook",modelName:"List",idProperty:"id",titleProperty:"name",recordName:"List",recordsName:"Lists",containerProperty:"container_id",containerName:"Addressbook",containersName:"Addressbooks",copyOmitFields:["group_id"]});Tine.Addressbook.getSalutationStore=function(){var a=Ext.StoreMgr.get("AddressbookSalutationStore");if(!a){a=new Ext.data.JsonStore({fields:Tine.Addressbook.Model.Salutation,baseParams:{method:"Addressbook.getSalutations"},root:"results",totalProperty:"totalcount",id:"id",remoteSort:false});if(Tine.Addressbook.registry.get("Salutations")){a.loadData(Tine.Addressbook.registry.get("Salutations"))}Ext.StoreMgr.add("AddressbookSalutationStore",a)}return a};Ext.ns("Tine.Addressbook");Tine.Addressbook.Application=Ext.extend(Tine.Tinebase.Application,{getTitle:function(){return this.i18n.ngettext("Addressbook","Addressbooks",1)}});Tine.Addressbook.MainScreen=Ext.extend(Tine.widgets.MainScreen,{activeContentType:"Contact"});Tine.Addressbook.TreePanel=function(a){Ext.apply(this,a);this.id="Addressbook_Tree";this.filterMode="filterToolbar";this.recordClass=Tine.Addressbook.Model.Contact;Tine.Addressbook.TreePanel.superclass.constructor.call(this)};Ext.extend(Tine.Addressbook.TreePanel,Tine.widgets.container.TreePanel);Tine.Addressbook.FilterPanel=function(a){Ext.apply(this,a);Tine.Addressbook.FilterPanel.superclass.constructor.call(this)};Ext.extend(Tine.Addressbook.FilterPanel,Tine.widgets.persistentfilter.PickerPanel,{filter:[{field:"model",operator:"equals",value:"Addressbook_Model_ContactFilter"}]});Ext.ns("Tine.Addressbook");Tine.Addressbook.ContactGridDetailsPanel=Ext.extend(Tine.widgets.grid.DetailsPanel,{il8n:null,felamimail:false,initComponent:function(){this.initTemplate();this.initDefaultTemplate();Tine.Addressbook.ContactGridDetailsPanel.superclass.initComponent.call(this)},afterRender:function(){Tine.Addressbook.ContactGridDetailsPanel.superclass.afterRender.apply(this,arguments);if(this.felamimail===true){this.body.on("click",this.onClick,this)}},initDefaultTemplate:function(){this.defaultTpl=new Ext.XTemplate('<div class="preview-panel-timesheet-nobreak">',"<!-- Preview contacts -->",'<div class="preview-panel preview-panel-timesheet-left">','<div class="bordercorner_1"></div>','<div class="bordercorner_2"></div>','<div class="bordercorner_3"></div>','<div class="bordercorner_4"></div>','<div class="preview-panel-declaration">'+this.il8n._("Contacts")+"</div>",'<div class="preview-panel-timesheet-leftside preview-panel-left">','<span class="preview-panel-bold">',this.il8n._("Select contact")+"<br/>","<br/>","<br/>","<br/>","</span>","</div>",'<div class="preview-panel-timesheet-rightside preview-panel-left">','<span class="preview-panel-nonbold">',"<br/>","<br/>","<br/>","<br/>","</span>","</div>","</div>","<!-- Preview xxx -->",'<div class="preview-panel-timesheet-right">','<div class="bordercorner_gray_1"></div>','<div class="bordercorner_gray_2"></div>','<div class="bordercorner_gray_3"></div>','<div class="bordercorner_gray_4"></div>','<div class="preview-panel-declaration"></div>','<div class="preview-panel-timesheet-leftside preview-panel-left">','<span class="preview-panel-bold">',"<br/>","<br/>","<br/>","<br/>","</span>","</div>",'<div class="preview-panel-timesheet-rightside preview-panel-left">','<span class="preview-panel-nonbold">',"<br/>","<br/>","<br/>","<br/>","</span>","</div>","</div>","</div>")},initTemplate:function(){this.tpl=new Ext.XTemplate('<tpl for=".">','<div class="preview-panel-adressbook-nobreak">','<div class="preview-panel-left">',"<!-- Preview image -->",'<div class="preview-panel preview-panel-left preview-panel-image">','<div class="bordercorner_1"></div>','<div class="bordercorner_2"></div>','<div class="bordercorner_3"></div>','<div class="bordercorner_4"></div>','<img src="{[this.getImageUrl(values.jpegphoto, 90, 113)]}"/>',"</div>","<!-- Preview office -->",'<div class="preview-panel preview-panel-office preview-panel-left">','<div class="bordercorner_1"></div>','<div class="bordercorner_2"></div>','<div class="bordercorner_3"></div>','<div class="bordercorner_4"></div>','<div class="preview-panel-declaration">'+this.il8n._("Company")+"</div>",'<div class="preview-panel-address preview-panel-left">','<span class="preview-panel-bold">{[this.encode(values.org_name, "mediumtext")]}{[this.encode(values.org_unit, "prefix", " / ")]}</span><br/>',"{[this.encode(values.adr_one_street)]}<br/>",'{[this.encode(values.adr_one_postalcode, " ")]}{[this.encode(values.adr_one_locality)]}<br/>','{[this.encode(values.adr_one_region, " / ")]}{[this.encode(values.adr_one_countryname, "country")]}<br/>',"</div>",'<div class="preview-panel-contact preview-panel-right">','<span class="preview-panel-symbolcompare">'+this.il8n._("Phone")+"</span>{[this.encode(values.tel_work)]}<br/>",'<span class="preview-panel-symbolcompare">'+this.il8n._("Mobile")+"</span>{[this.encode(values.tel_cell)]}<br/>",'<span class="preview-panel-symbolcompare">'+this.il8n._("Fax")+"</span>{[this.encode(values.tel_fax)]}<br/>",'<span class="preview-panel-symbolcompare">'+this.il8n._("E-Mail")+"</span>{[this.getMailLink(values.email, "+this.felamimail+")]}<br/>",'<span class="preview-panel-symbolcompare">'+this.il8n._("Web")+'</span><a href="{[this.encode(values.url, "href")]}" target="_blank">{[this.encode(values.url, "shorttext")]}</a><br/>',"</div>","</div>","</div>","<!-- Preview privat -->",'<div class="preview-panel preview-panel-privat preview-panel-left">','<div class="bordercorner_1"></div>','<div class="bordercorner_2"></div>','<div class="bordercorner_3"></div>','<div class="bordercorner_4"></div>','<div class="preview-panel-declaration">'+this.il8n._("Private")+"</div>",'<div class="preview-panel-address preview-panel-left">','<span class="preview-panel-bold">{[this.encode(values.n_fn)]}</span><br/>',"{[this.encode(values.adr_two_street)]}<br/>",'{[this.encode(values.adr_two_postalcode, " ")]}{[this.encode(values.adr_two_locality)]}<br/>','{[this.encode(values.adr_two_region, " / ")]}{[this.encode(values.adr_two_countryname, "country")]}<br/>',"</div>",'<div class="preview-panel-contact preview-panel-right">','<span class="preview-panel-symbolcompare">'+this.il8n._("Phone")+"</span>{[this.encode(values.tel_home)]}<br/>",'<span class="preview-panel-symbolcompare">'+this.il8n._("Mobile")+"</span>{[this.encode(values.tel_cell_private)]}<br/>",'<span class="preview-panel-symbolcompare">'+this.il8n._("Fax")+"</span>{[this.encode(values.tel_fax_home)]}<br/>",'<span class="preview-panel-symbolcompare">'+this.il8n._("E-Mail")+"</span>{[this.getMailLink(values.email_home, "+this.felamimail+")]}<br/>",'<span class="preview-panel-symbolcompare">'+this.il8n._("Web")+'</span><a href="{[this.encode(values.url, "href")]}" target="_blank">{[this.encode(values.url_home, "shorttext")]}</a><br/>',"</div>","</div>","<!-- Preview info -->",'<div class="preview-panel-description preview-panel-left" ext:qtip="{[this.encode(values.note)]}">','<div class="bordercorner_gray_1"></div>','<div class="bordercorner_gray_2"></div>','<div class="bordercorner_gray_3"></div>','<div class="bordercorner_gray_4"></div>','<div class="preview-panel-declaration">'+this.il8n._("Info")+"</div>",'{[this.encode(values.note, "longtext")]}',"</div>","</div>","</tpl>",{encode:function(c,a,b){if(c){if(a){switch(a){case"country":c=Locale.getTranslationData("CountryList",c);break;case"longtext":c=Ext.util.Format.ellipsis(c,135);break;case"mediumtext":c=Ext.util.Format.ellipsis(c,30);break;case"shorttext":c=Ext.util.Format.ellipsis(c,18);break;case"prefix":if(b){c=b+c}break;case"href":if(!String(c).match(/^(https?|ftps?)/)){var d=Tine.Tinebase.appMgr.get("Addressbook");return"javascript:Ext.Msg.alert('"+d.i18n._("Insecure link")+"', '"+d.i18n._("Please review this link in edit dialog.")+"');"}break;default:c+=a}}c=Ext.util.Format.htmlEncode(c);return Ext.util.Format.nl2br(c)}else{return""}},getTags:function(c){var a="";for(var b=0;b<c.length;b++){a+=c[b].name+" "}return a},getImageUrl:function(b,c,a){if(b.match(/&/)){b=Ext.ux.util.ImageURL.prototype.parseURL(b);b.width=c;b.height=a;b.ratiomode=0}return b},getMailLink:function(b,a){if(!b){return""}var c=(a===true)?"#":"mailto:"+b;var d=Ext.id()+":"+b;return'<a href="'+c+'" class="tinebase-email-link" id="'+d+'">'+Ext.util.Format.ellipsis(b,18)+"</a>"}})},onClick:function(g){var f=g.getTarget("a[class=tinebase-email-link]");if(f){var b=f.id.split(":")[1];var d=Tine.Felamimail.Model.Message.getDefaultData();d.to=[b];d.body=Tine.Felamimail.getSignature();var a=new Tine.Felamimail.Model.Message(d,0);var c=Tine.Felamimail.MessageEditDialog.openWindow({record:a})}}});Ext.ns("Tine.Addressbook");Tine.Addressbook.ContactGridPanel=Ext.extend(Tine.widgets.grid.GridPanel,{recordClass:Tine.Addressbook.Model.Contact,defaultSortInfo:{field:"n_fileas",direction:"ASC"},gridConfig:{loadMask:true,autoExpandColumn:"n_fileas",enableDragDrop:true,ddGroup:"containerDDGroup"},copyEditAction:true,felamimail:false,hasDetailsPanel:true,phoneMenu:null,initComponent:function(){this.recordProxy=Tine.Addressbook.contactBackend;if(Tine.Felamimail&&Tine.Tinebase.common.hasRight("run","Felamimail")&&Tine.Felamimail.registry.get("preferences").get("useInAdb")){this.felamimail=(Tine.Felamimail.registry.get("preferences").get("useInAdb")==1)}this.gridConfig.cm=this.getColumnModel();this.filterToolbar=this.filterToolbar||this.getFilterToolbar();if(this.hasDetailsPanel){this.detailsPanel=this.getDetailsPanel()}this.plugins=this.plugins||[];this.plugins.push(this.filterToolbar);Tine.Addressbook.ContactGridPanel.superclass.initComponent.call(this)},getColumnModel:function(){return new Ext.grid.ColumnModel({defaults:{sortable:true,hidden:true,resizable:true},columns:this.getColumns()})},getColumns:function(){return[{id:"tid",header:this.app.i18n._("Type"),dataIndex:"tid",width:30,renderer:this.contactTidRenderer.createDelegate(this),hidden:false},{id:"tags",header:this.app.i18n._("Tags"),dataIndex:"tags",width:50,renderer:Tine.Tinebase.common.tagsRenderer,sortable:false,hidden:false},{id:"n_family",header:this.app.i18n._("Last Name"),dataIndex:"n_family"},{id:"n_given",header:this.app.i18n._("First Name"),dataIndex:"n_given",width:80},{id:"n_fn",header:this.app.i18n._("Full Name"),dataIndex:"n_fn"},{id:"n_fileas",header:this.app.i18n._("Display Name"),dataIndex:"n_fileas",hidden:false},{id:"org_name",header:this.app.i18n._("Company"),dataIndex:"org_name",width:120,hidden:false},{id:"org_unit",header:this.app.i18n._("Unit"),dataIndex:"org_unit"},{id:"title",header:this.app.i18n._("Job Title"),dataIndex:"title"},{id:"role",header:this.app.i18n._("Job Role"),dataIndex:"role"},{id:"room",header:this.app.i18n._("Room"),dataIndex:"room"},{id:"adr_one_street",header:this.app.i18n._("Street"),dataIndex:"adr_one_street"},{id:"adr_one_locality",header:this.app.i18n._("City"),dataIndex:"adr_one_locality",width:150,hidden:false},{id:"adr_one_region",header:this.app.i18n._("Region"),dataIndex:"adr_one_region"},{id:"adr_one_postalcode",header:this.app.i18n._("Postalcode"),dataIndex:"adr_one_postalcode"},{id:"adr_one_countryname",header:this.app.i18n._("Country"),dataIndex:"adr_one_countryname"},{id:"adr_two_street",header:this.app.i18n._("Street (private)"),dataIndex:"adr_two_street"},{id:"adr_two_locality",header:this.app.i18n._("City (private)"),dataIndex:"adr_two_locality"},{id:"adr_two_region",header:this.app.i18n._("Region (private)"),dataIndex:"adr_two_region"},{id:"adr_two_postalcode",header:this.app.i18n._("Postalcode (private)"),dataIndex:"adr_two_postalcode"},{id:"adr_two_countryname",header:this.app.i18n._("Country (private)"),dataIndex:"adr_two_countryname"},{id:"email",header:this.app.i18n._("Email"),dataIndex:"email",width:150,hidden:false},{id:"tel_work",header:this.app.i18n._("Phone"),dataIndex:"tel_work",hidden:false},{id:"tel_cell",header:this.app.i18n._("Mobile"),dataIndex:"tel_cell",hidden:false},{id:"tel_fax",header:this.app.i18n._("Fax"),dataIndex:"tel_fax"},{id:"tel_car",header:this.app.i18n._("Car phone"),dataIndex:"tel_car"},{id:"tel_pager",header:this.app.i18n._("Pager"),dataIndex:"tel_pager"},{id:"tel_home",header:this.app.i18n._("Phone (private)"),dataIndex:"tel_home"},{id:"tel_fax_home",header:this.app.i18n._("Fax (private)"),dataIndex:"tel_fax_home"},{id:"tel_cell_private",header:this.app.i18n._("Mobile (private)"),dataIndex:"tel_cell_private"},{id:"email_home",header:this.app.i18n._("Email (private)"),dataIndex:"email_home"},{id:"url",header:this.app.i18n._("Web"),dataIndex:"url"},{id:"url_home",header:this.app.i18n._("URL (private)"),dataIndex:"url_home"},{id:"note",header:this.app.i18n._("Note"),dataIndex:"note"},{id:"tz",header:this.app.i18n._("Timezone"),dataIndex:"tz"},{id:"geo",header:this.app.i18n._("Geo"),dataIndex:"geo"},{id:"bday",header:this.app.i18n._("Birthday"),dataIndex:"bday",renderer:Tine.Tinebase.common.dateRenderer}].concat(this.getModlogColumns().concat(this.getCustomfieldColumns()))},initActions:function(){this.actions_exportContact=new Ext.Action({requiredGrant:"exportGrant",text:this.app.i18n._("Export Contact"),iconCls:"action_export",scope:this,disabled:true,allowMultiple:true,menu:{items:[new Tine.widgets.grid.ExportButton({text:this.app.i18n._("Export as PDF"),iconCls:"action_exportAsPdf",format:"pdf",exportFunction:"Addressbook.exportContacts",gridPanel:this}),new Tine.widgets.grid.ExportButton({text:this.app.i18n._("Export as CSV"),iconCls:"tinebase-action-export-csv",format:"csv",exportFunction:"Addressbook.exportContacts",gridPanel:this}),new Tine.widgets.grid.ExportButton({text:this.app.i18n._("Export as ODS"),format:"ods",iconCls:"tinebase-action-export-ods",exportFunction:"Addressbook.exportContacts",gridPanel:this}),new Tine.widgets.grid.ExportButton({text:this.app.i18n._("Export as XLS"),format:"xls",iconCls:"tinebase-action-export-xls",exportFunction:"Addressbook.exportContacts",gridPanel:this}),new Tine.widgets.grid.ExportButton({text:this.app.i18n._("Export as ..."),iconCls:"tinebase-action-export-xls",exportFunction:"Addressbook.exportContacts",showExportDialog:true,gridPanel:this})]}});this.phoneMenu=new Ext.menu.Menu({});this.actions_callContact=new Ext.Action({requiredGrant:"readGrant",hidden:!(Tine.Phone&&Tine.Tinebase.common.hasRight("run","Phone")),actionUpdater:this.updatePhoneActions,text:this.app.i18n._("Call contact"),disabled:true,iconCls:"PhoneIconCls",menu:this.phoneMenu,scope:this});this.actions_composeEmail=new Ext.Action({requiredGrant:"readGrant",hidden:!this.felamimail,text:this.app.i18n._("Compose email"),disabled:true,handler:this.onComposeEmail,iconCls:"action_composeEmail",scope:this,allowMultiple:true});this.actions_import=new Ext.Action({text:this.app.i18n._("Import contacts"),disabled:false,handler:this.onImport,iconCls:"action_import",scope:this,allowMultiple:true});this.actionUpdater.addActions([this.actions_exportContact,this.actions_callContact,this.actions_composeEmail,this.actions_import]);Tine.Addressbook.ContactGridPanel.superclass.initActions.call(this)},getActionToolbarItems:function(){return[Ext.apply(new Ext.SplitButton(this.actions_callContact),{scale:"medium",rowspan:2,iconAlign:"top",arrowAlign:"right"}),Ext.apply(new Ext.Button(this.actions_composeEmail),{scale:"medium",rowspan:2,iconAlign:"top"}),{xtype:"buttongroup",columns:1,frame:false,items:[this.actions_exportContact,this.actions_import]}]},getContextMenuItems:function(){var a=["-",this.actions_exportContact,"-",this.actions_callContact,this.actions_composeEmail];return a},updatePhoneActions:function(d,b,c){if(d.isHidden()){return}this.phoneMenu.removeAll();this.actions_callContact.setDisabled(true);if(c.length==1){var a=c[0];if(!a){return false}if(!Ext.isEmpty(a.data.tel_work)){this.phoneMenu.add({text:this.app.i18n._("Work")+" "+a.data.tel_work+"",scope:this,handler:this.onCallContact,field:"tel_work"});d.setDisabled(false)}if(!Ext.isEmpty(a.data.tel_home)){this.phoneMenu.add({text:this.app.i18n._("Home")+" "+a.data.tel_home+"",scope:this,handler:this.onCallContact,field:"tel_home"});d.setDisabled(false)}if(!Ext.isEmpty(a.data.tel_cell)){this.phoneMenu.add({text:this.app.i18n._("Cell")+" "+a.data.tel_cell+"",scope:this,handler:this.onCallContact,field:"tel_cell"});d.setDisabled(false)}if(!Ext.isEmpty(a.data.tel_cell_private)){this.phoneMenu.add({text:this.app.i18n._("Cell private")+" "+a.data.tel_cell_private+"",scope:this,handler:this.onCallContact,field:"tel_cell"});d.setDisabled(false)}}},onCallContact:function(b){var c;var a=this.grid.getSelectionModel().getSelected();if(!a){return}if(!Ext.isEmpty(a.get(b.field))){c=a.get(b.field)}else{if(!Ext.isEmpty(a.data.tel_work)){c=a.data.tel_work}else{if(!Ext.isEmpty(a.data.tel_cell)){c=a.data.tel_cell}else{if(!Ext.isEmpty(a.data.tel_cell_private)){c=a.data.tel_cell_private}else{if(!Ext.isEmpty(a.data.tel_home)){c=a.data.tel_work}}}}}Tine.Phone.dialPhoneNumber(c)},onComposeEmail:function(d){var e=this.grid.getSelectionModel().getSelections();var f=Tine.Felamimail.Model.Message.getDefaultData();f.body=Tine.Felamimail.getSignature();f.to=[];for(var c=0;c<e.length;c++){if(e[c].get("email")!=""){f.to.push(e[c].get("email"))}else{if(e[c].get("email_home")!=""){f.to.push(e[c].get("email_home"))}}}var a=new Tine.Felamimail.Model.Message(f,0);var b=Tine.Felamimail.MessageEditDialog.openWindow({record:a})},onImport:function(b){var a=Tine.widgets.dialog.ImportDialog.openWindow({appName:"Addressbook",listeners:{scope:this,update:function(c){this.loadGridData({preserveCursor:false,preserveSelection:false,preserveScroller:false,removeStrategy:"default"})}},record:new Tine.Tinebase.Model.ImportJob({container_id:Tine.Addressbook.registry.get("defaultAddressbook"),model:this.recordClass,import_definition_id:Tine.Addressbook.registry.get("defaultImportDefinition").id},0)})},contactTidRenderer:function(c,a,b){switch(b.get("type")){case"user":return"<img src='images/oxygen/16x16/actions/user-female.png' width='12' height='12' alt='contact' ext:qtip='"+this.app.i18n._("Internal Contact")+"'/>";default:return"<img src='images/oxygen/16x16/actions/user.png' width='12' height='12' alt='contact'/>"}},getDetailsPanel:function(){return new Tine.Addressbook.ContactGridDetailsPanel({gridpanel:this,il8n:this.app.i18n,felamimail:this.felamimail})}});Ext.ns("Tine.Calendar");Tine.Addressbook.ContactFilterModel=Ext.extend(Tine.widgets.grid.FilterModel,{app:null,field:"contact_id",defaultOperator:"equals",initComponent:function(){Tine.Addressbook.ContactFilterModel.superclass.initComponent.call(this);this.app=Tine.Tinebase.appMgr.get("Addressbook");this.operators=["equals"];this.label=this.label||this.app.i18n._("Contact")},valueRenderer:function(b,a){var c=new Tine.Addressbook.SearchCombo({app:this.app,filter:b,width:200,listWidth:400,listAlign:"tr-br?",id:"tw-ftb-frow-valuefield-"+b.id,value:b.data.value?b.data.value:this.defaultValue,renderTo:a,getValue:function(){return this.selectedRecord.id},onSelect:function(d){this.setValue(d);this.collapse();this.fireEvent("select",this,d);if(this.blurOnSelect){this.fireEvent("blur",this)}},setValue:function(e){this.selectedRecord=e;var d=Tine.Calendar.AttendeeGridPanel.prototype.renderAttenderUserName.call(this,e);Tine.Addressbook.SearchCombo.superclass.setValue.call(this,d)}});c.on("select",this.onFiltertrigger,this);return c}});Tine.widgets.grid.FilterToolbar.FILTERS["addressbook.contact"]=Tine.Addressbook.ContactFilterModel;Ext.ns("Tine.Addressbook");Tine.Addressbook.ContactEditDialog=Ext.extend(Tine.widgets.dialog.EditDialog,{windowNamePrefix:"ContactEditWindow_",appName:"Addressbook",recordClass:Tine.Addressbook.Model.Contact,showContainerSelector:true,getFormItems:function(){if(Tine.Tinebase.registry.get("mapPanel")&&Tine.widgets.MapPanel){this.mapPanel=new Tine.widgets.MapPanel({layout:"fit",title:this.app.i18n._("Map"),disabled:(!this.record.get("lon")||this.record.get("lon")===null)&&(!this.record.get("lat")||this.record.get("lat")===null),zoom:15})}else{this.mapPanel=new Ext.Panel({layout:"fit",title:this.app.i18n._("Map"),disabled:true,html:""})}return{xtype:"tabpanel",border:false,plain:true,activeTab:0,items:[{title:this.app.i18n.n_("Contact","Contacts",1),border:false,frame:true,layout:"border",items:[{region:"center",layout:"border",items:[{xtype:"fieldset",region:"north",autoHeight:true,title:this.app.i18n._("Personal Information"),items:[{xtype:"panel",layout:"fit",width:90,height:120,style:{position:"absolute",right:"10px",top:Ext.isGecko?"7px":"19px","z-index":100},items:[new Ext.ux.form.ImageField({name:"jpegphoto",width:90,height:120})]},{xtype:"columnform",items:[[{columnWidth:0.35,fieldLabel:this.app.i18n._("Salutation"),xtype:"combo",store:Tine.Addressbook.getSalutationStore(),name:"salutation_id",mode:"local",displayField:"name",valueField:"id",triggerAction:"all",forceSelection:true,listeners:{scope:this,select:function(d,a,b){var c=this.getForm().findField("jpegphoto");if(Ext.isEmpty(c.getValue())&&!Ext.isEmpty(a.get("image_path"))){c.setDefaultImage(a.get("image_path"))}}}},{columnWidth:0.65,fieldLabel:this.app.i18n._("Title"),name:"n_prefix",maxLength:64},{width:100,hidden:true}],[{columnWidth:0.35,fieldLabel:this.app.i18n._("First Name"),name:"n_given",maxLength:64},{columnWidth:0.3,fieldLabel:this.app.i18n._("Middle Name"),name:"n_middle",maxLength:64},{columnWidth:0.35,fieldLabel:this.app.i18n._("Last Name"),name:"n_family",maxLength:64},{width:100,hidden:true}],[{columnWidth:0.65,xtype:"mirrortextfield",fieldLabel:this.app.i18n._("Company"),name:"org_name",maxLength:64},{columnWidth:0.35,fieldLabel:this.app.i18n._("Unit"),name:"org_unit",maxLength:64},{width:100,hidden:true}],[{columnWidth:0.65,xtype:"combo",fieldLabel:this.app.i18n._("Display Name"),name:"n_fn",disabled:true},{columnWidth:0.35,fieldLabel:this.app.i18n._("Job Title"),name:"title",maxLength:64},{width:100,xtype:"extuxclearabledatefield",fieldLabel:this.app.i18n._("Birthday"),name:"bday"}]]}]},{xtype:"fieldset",region:"center",title:this.app.i18n._("Contact Information"),autoScroll:true,items:[{xtype:"columnform",items:[[{fieldLabel:this.app.i18n._("Phone"),labelIcon:"images/oxygen/16x16/apps/kcall.png",name:"tel_work",maxLength:40},{fieldLabel:this.app.i18n._("Mobile"),labelIcon:"images/oxygen/16x16/devices/phone.png",name:"tel_cell",maxLength:40},{fieldLabel:this.app.i18n._("Fax"),labelIcon:"images/oxygen/16x16/devices/printer.png",name:"tel_fax",maxLength:40}],[{fieldLabel:this.app.i18n._("Phone (private)"),labelIcon:"images/oxygen/16x16/apps/kcall.png",name:"tel_home",maxLength:40},{fieldLabel:this.app.i18n._("Mobile (private)"),labelIcon:"images/oxygen/16x16/devices/phone.png",name:"tel_cell_private",maxLength:40},{fieldLabel:this.app.i18n._("Fax (private)"),labelIcon:"images/oxygen/16x16/devices/printer.png",name:"tel_fax_home",maxLength:40}],[{fieldLabel:this.app.i18n._("E-Mail"),labelIcon:"images/oxygen/16x16/actions/kontact-mail.png",name:"email",vtype:"email",maxLength:64},{fieldLabel:this.app.i18n._("E-Mail (private)"),labelIcon:"images/oxygen/16x16/actions/kontact-mail.png",name:"email_home",vtype:"email",maxLength:64},{xtype:"mirrortextfield",fieldLabel:this.app.i18n._("Web"),labelIcon:"images/oxygen/16x16/actions/network.png",name:"url",vtype:"url",maxLength:128,listeners:{scope:this,focus:function(a){if(!a.getValue()){a.setValue("http://www.");a.selectText.defer(100,a,[7,11])}},blur:function(a){if(a.getValue()==="http://www."){a.setValue(null);a.validate()}}}}]]}]},{xtype:"tabpanel",region:"south",border:false,deferredRender:false,height:124,split:true,activeTab:0,defaults:{frame:true},items:[{title:this.app.i18n._("Company Address"),xtype:"columnform",items:[[{fieldLabel:this.app.i18n._("Street"),name:"adr_one_street",maxLength:64},{fieldLabel:this.app.i18n._("Street 2"),name:"adr_one_street2",maxLength:64},{fieldLabel:this.app.i18n._("Region"),name:"adr_one_region",maxLength:64}],[{fieldLabel:this.app.i18n._("Postal Code"),name:"adr_one_postalcode",maxLength:64},{fieldLabel:this.app.i18n._("City"),name:"adr_one_locality",maxLength:64},{xtype:"widget-countrycombo",fieldLabel:this.app.i18n._("Country"),name:"adr_one_countryname",maxLength:64}]]},{title:this.app.i18n._("Private Address"),xtype:"columnform",items:[[{fieldLabel:this.app.i18n._("Street"),name:"adr_two_street",maxLength:64},{fieldLabel:this.app.i18n._("Street 2"),name:"adr_two_street2",maxLength:64},{fieldLabel:this.app.i18n._("Region"),name:"adr_two_region",maxLength:64}],[{fieldLabel:this.app.i18n._("Postal Code"),name:"adr_two_postalcode",maxLength:64},{fieldLabel:this.app.i18n._("City"),name:"adr_two_locality",maxLength:64},{xtype:"widget-countrycombo",fieldLabel:this.app.i18n._("Country"),name:"adr_two_countryname",maxLength:64}]]}]}]},{region:"east",layout:"accordion",animate:true,width:210,split:true,collapsible:true,collapseMode:"mini",header:false,margins:"0 5 0 5",border:true,items:[new Ext.Panel({title:this.app.i18n._("Description"),iconCls:"descriptionIcon",layout:"form",labelAlign:"top",border:false,items:[{style:"margin-top: -4px; border 0px;",labelSeparator:"",xtype:"textarea",name:"note",hideLabel:true,grow:false,preventScrollbars:false,anchor:"100% 100%",emptyText:this.app.i18n._("Enter description"),requiredGrant:"editGrant"}]}),new Tine.widgets.activities.ActivitiesPanel({app:"Addressbook",showAddNoteForm:false,border:false,bodyStyle:"border:1px solid #B5B8C8;"}),new Tine.widgets.tags.TagPanel({app:"Addressbook",border:false,bodyStyle:"border:1px solid #B5B8C8;"})]}]},this.mapPanel,new Tine.widgets.activities.ActivitiesTabPanel({app:this.appName,record_id:(this.record)?this.record.id:"",record_model:this.appName+"_Model_"+this.recordClass.getMeta("modelName")}),new Tine.Tinebase.widgets.customfields.CustomfieldsPanel({recordClass:Tine.Addressbook.Model.Contact,disabled:(Tine.Addressbook.registry.get("customfields").length===0),quickHack:{record:this.record}}),this.linkPanel]}},initComponent:function(){this.linkPanel=new Tine.widgets.dialog.LinkPanel({relatedRecords:(Tine.Crm&&Tine.Tinebase.common.hasRight("run","Crm"))?{Crm_Model_Lead:{recordClass:Tine.Crm.Model.Lead,dlgOpener:Tine.Crm.LeadEditDialog.openWindow}}:{}});var b=new Ext.Action({id:"exportButton",text:Tine.Tinebase.appMgr.get("Addressbook").i18n._("Export as pdf"),handler:this.onExportContact,iconCls:"action_exportAsPdf",disabled:false,scope:this});var a=new Tine.widgets.activities.ActivitiesAddButton({});this.tbarItems=[b,a];this.supr().initComponent.apply(this,arguments)},isValid:function(){var a=this.getForm();var b=true;if(a.findField("n_family").getValue()===""&&a.findField("org_name").getValue()===""){var c=String.format(this.app.i18n._("Either {0} or {1} must be given"),this.app.i18n._("Last Name"),this.app.i18n._("Company"));a.findField("n_family").markInvalid(c);a.findField("org_name").markInvalid(c);b=false}return b&&Tine.Calendar.EventEditDialog.superclass.isValid.apply(this,arguments)},onExportContact:function(){var a=new Ext.ux.file.Download({params:{method:"Addressbook.exportContacts",_filter:this.record.id,_format:"pdf"}});a.start()},onRecordLoad:function(){if(this.rendered){var a;if(!this.record.id){if(this.forceContainer){a=this.forceContainer;this.forceContainer=null}else{a=Tine.Addressbook.registry.get("defaultAddressbook")}this.record.set("container_id","");this.record.set("container_id",a)}if(Tine.Tinebase.registry.get("mapPanel")&&Tine.widgets.MapPanel&&this.record.get("lon")&&this.record.get("lon")!==null&&this.record.get("lat")&&this.record.get("lat")!==null){this.mapPanel.setCenter(this.record.get("lon"),this.record.get("lat"))}}this.supr().onRecordLoad.apply(this,arguments);this.linkPanel.onRecordLoad(this.record)}});Tine.Addressbook.ContactEditDialog.openWindow=function(a){var c=Ext.getCmp("Addressbook_Tree")?Ext.getCmp("Addressbook_Tree").getSelectionModel().getSelectedNode():null;if(c&&c.attributes&&c.attributes.container.type){a.forceContainer=c.attributes.container}else{a.forceContainer=null}var d=(a.record&&a.record.id)?a.record.id:0;var b=Tine.WindowFactory.getWindow({width:800,height:600,name:Tine.Addressbook.ContactEditDialog.prototype.windowNamePrefix+d,contentPanelConstructor:"Tine.Addressbook.ContactEditDialog",contentPanelConstructorConfig:a});return b};Ext.ns("Tine.Addressbook");Tine.Addressbook.SearchCombo=Ext.extend(Ext.form.ComboBox,{typeAhead:false,triggerAction:"all",pageSize:10,itemSelector:"div.search-item",store:null,minChars:3,blurOnSelect:false,userOnly:false,additionalFilters:null,selectedRecord:null,nameField:"n_fn",useAccountRecord:false,initComponent:function(){this.loadingText=_("Searching...");this.initTemplate();this.initStore();Tine.Addressbook.SearchCombo.superclass.initComponent.call(this);this.on("beforequery",this.onBeforeQuery,this)},onBeforeQuery:function(c){var b=[{field:"query",operator:"contains",value:c.query}];if(this.userOnly){b.push({field:"type",operator:"equals",value:"user"})}if(this.additionalFilters!==null&&this.additionalFilters.length>0){for(var a=0;a<this.additionalFilters.length;a++){b.push(this.additionalFilters[a])}}this.store.baseParams.filter=b},onSelect:function(a){this.selectedRecord=a;this.setValue(a.get(this.nameField));this.collapse();this.fireEvent("select",this,a);if(this.blurOnSelect){this.fireEvent("blur",this)}},onSpecialkey:function(c,b){if(b.getKey()==b.ENTER){var d=c.getValue();var a=this.store.getById(d);this.onSelect(a)}},initTemplate:function(){if(!this.tpl){this.tpl=new Ext.XTemplate('<tpl for="."><div class="search-item">','<table cellspacing="0" cellpadding="2" border="0" style="font-size: 11px;" width="100%">',"<tr>",'<td width="30%"><b>{[this.encode(values.n_fileas)]}</b><br/>{[this.encode(values.org_name)]}</td>','<td width="25%">{[this.encode(values.adr_one_street)]}<br/>',"{[this.encode(values.adr_one_postalcode)]} {[this.encode(values.adr_one_locality)]}</td>",'<td width="25%">{[this.encode(values.tel_work)]}<br/>{[this.encode(values.tel_cell)]}</td>','<td width="20%">','<img width="45px" height="39px" src="{jpegphoto}" />',"</td>","</tr>","</table>","</div></tpl>",{encode:function(a){if(a){return Ext.util.Format.htmlEncode(a)}else{return""}}})}},getValue:function(){if(this.useAccountRecord){if(this.selectedRecord){return this.selectedRecord.get("account_id")}else{return this.accountId}}else{return Tine.Addressbook.SearchCombo.superclass.getValue.call(this)}},setValue:function(a){if(this.useAccountRecord){if(a){if(a.accountId){this.accountId=a.accountId;a=a.accountDisplayName}else{if(typeof(a.get)=="function"){this.accountId=a.get("id");a=a.get("name")}}}else{this.accountId=null}}Tine.Addressbook.SearchCombo.superclass.setValue.call(this,a)},initStore:function(){if(!this.store){if(!this.contactFields){this.contactFields=Tine.Addressbook.Model.ContactArray}this.store=new Ext.data.JsonStore({fields:this.contactFields,baseParams:{method:"Addressbook.searchContacts"},root:"results",totalProperty:"totalcount",id:"id",remoteSort:true,sortInfo:{field:"n_family",direction:"ASC"}});this.store.on("beforeload",function(a,b){b.params.paging={start:b.params.start,limit:b.params.limit,sort:"n_family",dir:"ASC"}},this)}return this.store}});