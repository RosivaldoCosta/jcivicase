Ext.ensible.cal.ComboBox = Ext.extend(Ext.form.ComboBox,{
  initComponent : function(){
        Ext.ensible.cal.ComboBox.superclass.initComponent.call(this);
        },
initList : function(){
        if(!this.list){
            var cls = 'x-combo-list',
                listParent = Ext.getDom(this.getListParent() || Ext.getBody());

            this.list = new Ext.Layer({
                parentEl: listParent,
                shadow: this.shadow,
                cls: [cls, this.listClass].join(' '),
                constrain:false,
                zindex: this.getZIndex(listParent)
            });

            var lw = this.listWidth || Math.max(this.wrap.getWidth(), this.minListWidth);
            this.list.setSize(lw, 0);
            this.list.swallowEvent('mousewheel');
            this.assetHeight = 0;
            if(this.syncFont !== false){
                this.list.setStyle('font-size', this.el.getStyle('font-size'));
            }
            if(this.title){
                this.header = this.list.createChild({cls:cls+'-hd', html: this.title});
                this.assetHeight += this.header.getHeight();
            }

            this.innerList = this.list.createChild({cls:cls+'-inner'});
            this.mon(this.innerList, 'mouseover', this.onViewOver, this);
            this.mon(this.innerList, 'mousemove', this.onViewMove, this);
            this.innerList.setWidth(lw - this.list.getFrameWidth('lr'));

            if(this.pageSize){
                this.footer = this.list.createChild({cls:cls+'-ft'});
                this.pageTb = new Ext.PagingToolbar({
                    store: this.store,
                    pageSize: this.pageSize,
                    renderTo:this.footer
                });
                this.assetHeight += this.footer.getHeight();
            }

            if(!this.tpl){
                /**
                * @cfg {String/Ext.XTemplate} tpl <p>The template string, or {@link Ext.XTemplate} instance to
                * use to display each item in the dropdown list. The dropdown list is displayed in a
                * DataView. See {@link #view}.</p>
                * <p>The default template string is:</p><pre><code>
                *  '&lt;tpl for=".">&lt;div class="x-combo-list-item">{' + this.displayField + '}&lt;/div>&lt;/tpl>'
                * </code></pre>
                * <p>Override the default value to create custom UI layouts for items in the list.
                * For example:</p><pre><code>
                *  '&lt;tpl for=".">&lt;div ext:qtip="{state}. {nick}" class="x-combo-list-item">{state}&lt;/div>&lt;/tpl>'
                * </code></pre>
                * <p>The template <b>must</b> contain one or more substitution parameters using field
                * names from the Combo's</b> {@link #store Store}. In the example above an
                * <pre>ext:qtip</pre> attribute is added to display other fields from the Store.</p>
                * <p>To preserve the default visual look of list items, add the CSS class name
                * <pre>x-combo-list-item</pre> to the template's container element.</p>
                * <p>Also see {@link #itemSelector} for additional details.</p>
                */
				
				
				this.tpl= new Ext.XTemplate(
'<tpl for=".">',
'<tpl if="cancelStatus==1">',
'<div class="'+cls+'-item ext-striked">{' + this.displayField + '}</div>',
'</tpl>',
'<tpl if="cancelStatus==0">',
'<div class="'+cls+'-item">{' + this.displayField + '}</div>',
'</tpl>',
'</tpl>');
				
				               
				//this.tpl = '<tpl for="."><div class="'+cls+'-item">{' + this.displayField + '}{' + this.cancelStatus + '}</div></tpl>';               
                /**
                 * @cfg {String} itemSelector
                 * <p>A simple CSS selector (e.g. div.some-class or span:first-child) that will be
                 * used to determine what nodes the {@link #view Ext.DataView} which handles the dropdown
                 * display will be working with.</p>
                 * <p><b>Note</b>: this setting is <b>required</b> if a custom XTemplate has been
                 * specified in {@link #tpl} which assigns a class other than <pre>'x-combo-list-item'</pre>
                 * to dropdown list items</b>
                 */
            }
                     /**
            * The {@link Ext.DataView DataView} used to display the ComboBox's options.
            * @type Ext.DataView
            */
            this.view = new Ext.DataView({
                applyTo: this.innerList,
                tpl: this.tpl,
                singleSelect: true,
                selectedClass: this.selectedClass,
                itemSelector: this.itemSelector || '.' + cls + '-item',
                emptyText: this.listEmptyText,
                deferEmptyText: false
            });

            this.mon(this.view, {
                containerclick : this.onViewClick,
                click : this.onViewClick,
                scope :this
            });

            this.bindStore(this.store, true);

            if(this.resizable){
                this.resizer = new Ext.Resizable(this.list,  {
                   pinned:true, handles:'se'
                });
                this.mon(this.resizer, 'resize', function(r, w, h){
                    this.maxHeight = h-this.handleHeight-this.list.getFrameWidth('tb')-this.assetHeight;
                    this.listWidth = w;
                    this.innerList.setWidth(w - this.list.getFrameWidth('lr'));
                    this.restrictHeight();
                }, this);

                this[this.pageSize?'footer':'innerList'].setStyle('margin-bottom', this.handleHeight+'px');
            }
        }
    },
     onSelect : function(record, index){
        if(this.fireEvent('beforeselect', this, record, index) !== false){
            this.setValue(record.data[this.valueField || this.displayField]);
            this.collapse();
            this.fireEvent('select', this, record, index);
            if(record.data.cancelStatus==1)Ext.fly('statusid').addClass("ext-striked");
            if(record.data.cancelStatus==0)Ext.fly('statusid').removeClass("ext-striked");
            
        }
    },
});
Ext.reg('extensible.combobox', Ext.ensible.cal.ComboBox);

var ds = new Ext.data.Store({
    proxy: new Ext.data.HttpProxy({
        url: 'index.php?option=com_civicrm&task=civicrm/ajax/contacts&context=scheduler&stype=Client' /*index.php?option=com_civicrm&task=civicrm/ajax/contactlist&context=scheduler'*/
    }),
    reader: new Ext.data.JsonReader({
        root: 'rows',
        totalProperty: 'totalCount',
        idProperty: 'cid'
    }, [
        {name: 'name', mapping: 'name'},
        {name: 'cid', mapping: 'cid'},
    ])
});
Ext.ensible.cal.EventEditForm = Ext.extend(Ext.form.FormPanel, {
    labelWidth: 65,
    labelWidthRightCol: 65,
    colWidthLeft: .6,
    colWidthRight: .4,
    title: 'Event Form',
    titleTextAdd: 'Add Appointment',
    titleTextEdit: 'Edit Appointment',
    titleLabelText: 'Title',
    datesLabelText: 'When',
    reminderLabelText: 'Reminder',
    notesLabelText: 'Notes',
    locationLabelText: 'Location',
    webLinkLabelText: 'Web Link',
    calendarLabelText: 'Calendar',
    repeatsLabelText: 'Repeats',
    saveButtonText: 'Save',
    deleteButtonText: 'Delete',
    cancelButtonText: 'Cancel',
    visitGroupText:'Visit',
    initialVisitText:'Initial',
    followUpText:'Follow Up',
    bodyStyle: 'padding:20px 20px 10px;',
    border: false,
    buttonAlign: 'center',
    autoHeight: true, // to allow for the notes field to autogrow
    
    /* // not currently supported
     * @cfg {Boolean} enableRecurrence
     * True to show the recurrence field, false to hide it (default). Note that recurrence requires
     * something on the server-side that can parse the iCal RRULE format in order to generate the
     * instances of recurring events to display on the calendar, so this field should only be enabled
     * if the server supports it.
     */
    enableRecurrence: false,
    
    // private properties:
    layout: 'column',
    cls: 'ext-evt-edit-form',
    
    // private
    initComponent: function(){
        
        this.addEvents({
            /**
             * @event eventadd
             * Fires after a new event is added
             * @param {Ext.ensible.cal.EventEditForm} this
             * @param {Ext.ensible.cal.EventRecord} rec The new {@link Ext.ensible.cal.EventRecord record} that was added
             */
            eventadd: true,
            /**
             * @event eventupdate
             * Fires after an existing event is updated
             * @param {Ext.ensible.cal.EventEditForm} this
             * @param {Ext.ensible.cal.EventRecord} rec The new {@link Ext.ensible.cal.EventRecord record} that was updated
             */
            eventupdate: true,
            /**
             * @event eventdelete
             * Fires after an event is deleted
             * @param {Ext.ensible.cal.EventEditForm} this
             * @param {Ext.ensible.cal.EventRecord} rec The new {@link Ext.ensible.cal.EventRecord record} that was deleted
             */
            eventdelete: true,
            /**
             * @event eventcancel
             * Fires after an event add/edit operation is canceled by the user and no store update took place
             * @param {Ext.ensible.cal.EventEditForm} this
             * @param {Ext.ensible.cal.EventRecord} rec The new {@link Ext.ensible.cal.EventRecord record} that was canceled
             */
            eventcancel: true
        });
                
        var ds = new Ext.data.Store({
            proxy: new Ext.data.HttpProxy({
                url: 'index.php?option=com_civicrm&task=civicrm/ajax/contacts/&context=scheduler' 
            }),
            reader: new Ext.data.JsonReader({
                root: 'rows',
                totalProperty: 'totalCount',
                idProperty: 'cid'
            }, [
                {name: 'name', mapping: 'name'},
                {name: 'cid', mapping: 'cid'},
            ])
        });
        
        this.visitField = new Ext.form.RadioGroup({
        	
        id:'visit',
        fieldLabel:this.visitGroupText+": ",
        anchor:'40%',
        name: Ext.ensible.cal.EventMappings.VisitType.name,
        items:[
               {
				xtype:'radio',
				boxLabel: this.initialVisitText,
		        	name: Ext.ensible.cal.EventMappings.VisitType.name,
				inputValue: '1'
			},
			{
				xtype:'radio',
				boxLabel: this.followUpText,
		        	name: Ext.ensible.cal.EventMappings.VisitType.name,
				inputValue: '0'
		}]
        });
        
        
      var statuses = [
                       [<?php echo $this->status['Rescheduled']; ?>, 'Rescheduled',1],
                       [<?php echo $this->status['Cancelled']; ?>, 'Cancelled',1],
                       [<?php echo $this->status['Kept']; ?>, 'Kept',0],
                       [<?php echo $this->status['No Show']; ?>, 'No Show',0],
                       [<?php echo $this->status['Scheduled']; ?>, 'Scheduled',0]
                   ];   
       this.statusField = new Ext.ensible.cal.ComboBox({
            store: new Ext.data.SimpleStore({
                 id:0,
                fields:
                    [
                        'statusId',   //numeric value is the key
                        'statusText', //the text value is the value
                        {name:'cancelStatus',type:'int'}
                     ],
                data:statuses
            }),
            valueField:'statusId',
            displayField:'statusText',
            cancelStatus:'cancelStatus',
            selectedIndex:4,
            mode:'local',
            id:'statusid',
            fieldLabel:'Status',
            allowBlank: false,
            editable: false,
            triggerAction:'all',
            name:Ext.ensible.cal.EventMappings.Status.name
        });
    
        this.detailsField = new Ext.form.TextField({
        id:'detailsid',
        hidden:true,
        fieldLabel: 'Details',
        name: Ext.ensible.cal.EventMappings.CaseId.name,
        anchor: '90%'
        
        
        });
        this.titleField = 	new Ext.form.ComboBox({
        	id: 'caseid',
        	fieldLabel: 'Client',
        	name: Ext.ensible.cal.EventMappings.CaseId.name,
                store: ds,
                displayField: 'name',
                valueField: 'cid',
                typeAhead: true,
        	queryDelay: 50,
                loadingText: 'Searching...',
        	anchor: '90%',
                pageSize: 5,
                hideTrigger:true
                });
        this.dateRangeField = new Ext.ensible.cal.DateRangeField({
            fieldLabel: this.datesLabelText,
            singleLine: true,
            anchor: '90%',
            listeners: {
                'change': this.onDateChange.createDelegate(this)
            }
        });
        this.notesField = new Ext.form.TextArea({
            fieldLabel: this.notesLabelText,
            name: Ext.ensible.cal.EventMappings.Notes.name,
            grow: true,
            growMax: 150,
            anchor: '100%'
        });
        
        var leftFields = [this.detailsField,this.titleField, this.dateRangeField,this.visitField,this.statusField], 
        rightFields = [this.notesField];
            
        if(this.enableRecurrence){
            this.recurrenceField = new Ext.ensible.cal.RecurrenceField({
                name: Ext.ensible.cal.EventMappings.RRule.name,
                fieldLabel: this.repeatsLabelText,
                anchor: '100%'
            });
            leftFields.splice(2, 0, this.recurrenceField);
        }
        
        if(this.calendarStore){
            this.calendarField = new Ext.ensible.cal.CalendarCombo({
                store: this.calendarStore,
                fieldLabel: 'Provider',
                name: Ext.ensible.cal.EventMappings.CalendarId.name,
                 listeners:{
         			scope: this,
         			'select': this.onCalendarChange
    				}
            });
            leftFields.splice(2, 0, this.calendarField);
        };
        
        this.items = [{
            id: this.id+'-left-col',
            columnWidth: this.colWidthLeft,
            layout: 'form',
            border: false,
            items: leftFields
        },{
            id: this.id+'-right-col',
            columnWidth: this.colWidthRight,
            layout: 'form',
            labelWidth: this.labelWidthRightCol || this.labelWidth,
            border: false,
            items: rightFields
        }];
        
        this.fbar = [{
            text:this.saveButtonText, scope: this, handler: this.onSave
        },{
            cls:'ext-del-btn', text:this.deleteButtonText, scope:this, handler:this.onDelete
        },{
            text:this.cancelButtonText, scope: this, handler: this.onCancel
        }];
        
        Ext.ensible.cal.EventEditForm.superclass.initComponent.call(this);
    },
    
    // private
    onDateChange: function(dateRangeField, val){
        if(this.recurrenceField){
            this.recurrenceField.setStartDate(val[0]);
        }
    },
    onCalendarChange: function(data)
    {
    var me=Ext.getCmp(this.id+'-left-col');
    if(data.value==105)
    {

	this.detailsField.show()
	this.titleField.hide();   
    }
    else
    {
    this.detailsField.hide();
	this.titleField.show() 
   
    //Remove details and add title field
    } 	
    this.doLayout();
    
        console.log(me);
    
    
    },
    // inherited docs
    loadRecord: function(rec){
        this.form.reset().loadRecord.apply(this.form, arguments);
        this.activeRecord = rec;
        this.dateRangeField.setValue(rec.data);
        
        if(this.recurrenceField){
            this.recurrenceField.setStartDate(rec.data[Ext.ensible.cal.EventMappings.StartDate.name]);
        }

        if(this.calendarStore){
            this.form.setValues({'calendar': rec.data[Ext.ensible.cal.EventMappings.CalendarId.name]});
        }

	this.statusField.value = rec.data[Ext.ensible.cal.EventMappings.Status.name];
        
        //this.isAdd = !!rec.data[Ext.ensible.cal.EventMappings.IsNew.name];
        if(rec.phantom){
            //rec.markDirty();
            this.setTitle(this.titleTextAdd);
            Ext.select('.ext-del-btn').setDisplayed(false);
        }
        else {
            this.setTitle(this.titleTextEdit);
            Ext.select('.ext-del-btn').setDisplayed(true);
        }
        this.titleField.focus();
    },
    
    // inherited docs
    /*updateRecord: function() {
        var dates = this.dateRangeField.getValue();

        this.form.updateRecord(this.activeRecord);
        this.activeRecord.set(Ext.calendar.EventMappings.StartDate.name, dates[0]);
        this.activeRecord.set(Ext.calendar.EventMappings.EndDate.name, dates[1]);
        this.activeRecord.set(Ext.calendar.EventMappings.IsAllDay.name, dates[2]);
    },*/
    
    updateRecord: function(){
        var dates = this.dateRangeField.getValue(),
            M = Ext.ensible.cal.EventMappings,
            rec = this.activeRecord,
            fs = rec.fields,
            dirty = false;
            
        rec.beginEdit();
        
        //TODO: This block is copied directly from BasicForm.updateRecord.
        // Unfortunately since that method internally calls begin/endEdit all
        // updates happen and the record dirty status is reset internally to
        // that call. We need the dirty status, plus currently the DateRangeField
        // does not map directly to the record values, so for now we'll duplicate
        // the setter logic here (we need to be able to pick up any custom-added 
        // fields generically). Need to revisit this later and come up with a better solution.
        fs.each(function(f){
            var field = this.form.findField(f.name);
            if(field){
                var value = field.getValue();
                if (value.getGroupValue) {
                    value = value.getGroupValue();
                } 
                else if (field.eachItem) {
                    value = [];
                    field.eachItem(function(item){
                        value.push(item.getValue());
                    });
                }
                rec.set(f.name, value);
            }
        }, this);
        
        rec.set(M.StartDate.name, dates[0]);
        rec.set(M.EndDate.name, dates[1]);
        rec.set(M.IsAllDay.name, dates[2]);
        
        dirty = rec.dirty;
       //delete rec.store; // make sure the record does not try to autosave
        rec.endEdit();
        
        return dirty;
    },
    
    // private
    onCancel: function(){
        this.cleanup(true);
        this.fireEvent('eventcancel', this, this.activeRecord);
    },
    
    // private
    cleanup: function(hide){
        if(this.activeRecord){
            this.activeRecord.reject();
        }
        delete this.activeRecord;
        
        if(this.form.isDirty()){
            this.form.reset();
        }
    },
    processResult: function(btn)
    {
    	if(btn=="yes")
    		{
    		if(!this.form.isValid()){
            	console.log("Form is invalid");
            	
            	return;
            }
    		if(!this.updateRecord()){
    			console.log("Cant update record");
    	    	
    			this.onCancel();
    			return;
    		}
    		console.log(this.activeRecord.phantom);

    		this.fireEvent(this.activeRecord.phantom ? 'eventadd' : 'eventupdate', this, this.activeRecord, this.animateTarget);
    		}
    },
   
    // private
    onSave: function(){
        
      //Check that event doesnt overlap other events
        
        var start = this.dateRangeField.getDT('start');
        var finish = this.dateRangeField.getDT('end');
	var calendar = this.calendarField.value;
	var dayOfYear = start.getDayOfYear();
        
       	console.log(this.dateRangeField.getValue());
       	var isOverlap = false;
	var i = 0;
       	var events = eventsStore.data.items;

	while(!isOverlap && eventsStore.data.length > i)
       	{
    	   	var eve = events[i].data;

		if(eve.CalendarId == calendar)
		{
			if(eve.StartDate.getDayOfYear() == dayOfYear)
			{
    	   		if(start.between(eve.StartDate,eve.EndDate))
    	   		{
    		   		isOverlap = true;
    		   		console.log(eve);
   			}

    	   		if(finish.between(eve.StartDate,eve.EndDate))
	   		{
    	   	   		isOverlap = true;
    		   		console.log(eve);
  	   		}

	  		if(parseInt(eve.Status,10) == <?php echo $this->status['Cancelled'];?> || 
	  			parseInt(eve.Status,10) == <?php echo $this->status['Rescheduled'];?> || 
				this.statusField.value == <?php echo $this->status['Cancelled']; ?> ||
				this.statusField.value == <?php echo $this->status['Rescheduled'];?>)
  	  		{
  			   	isOverlap = false;
  	   		}
			}

		}
		i++;
  	
       }
       	
       	if(isOverlap)
	{
	   Ext.Msg.show({
		   title:'Save Appointment?',
		   msg: 'Appointment that you want to add overlaps with another. Do you want to add it ?',
		   buttons: Ext.Msg.YESNO,
		   fn: this.processResult,
		   scope:this,
		   animEl: 'elId'
		});
	}
       	else
    	{
    	   	if(!this.form.isValid())		
		{
           		console.log("Form is invalid");
           	
           		return;
           	}

   		if(!this.updateRecord())	
		{
   			console.log("Cant update record");
   	    	
   			this.onCancel();
   			return;
   		}

   		console.log(this.activeRecord.phantom);

   		this.fireEvent(this.activeRecord.phantom ? 'eventadd' : 'eventupdate', this, this.activeRecord, this.animateTarget);
    	   
    	  }
    	
    	
    },

    // private
    onDelete: function(){
        this.fireEvent('eventdelete', this, this.activeRecord);
    }
});

Ext.reg('extensible.eventeditform', Ext.ensible.cal.EventEditForm);
