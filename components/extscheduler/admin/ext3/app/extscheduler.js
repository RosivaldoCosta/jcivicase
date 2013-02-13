/*!
 * Ext JS Library 3.3.1
 * Copyright(c) 2006-2010 Sencha Inc.
 * licensing@sencha.com
 * http://www.sencha.com/license
 */
/*
 * Calendar sample code originally written by Brian Moeskau (brian@ext-calendar.com)
 * See additional calendar examples at http://ext-calendar.com
 */
App = function() {
    return {
        init : function() {
            
            Ext.BLANK_IMAGE_URL = 'http://extjs.cachefly.net/ext-3.1.0/resources/images/default/s.gif';

            // This is an example calendar store that enables the events to have
            // different colors based on CalendarId. This is not a fully-implmented
            // multi-calendar implementation, which is beyond the scope of this sample app
            this.calendarStore = new Ext.data.JsonStore({
                storeId: 'calendarStore',
		url: 'components/com_extscheduler/ext3/data/calendars.json',
                root: 'calendars',
                idProperty: 'id',
                autoLoad: true,
                fields: Ext.ensible.cal.CalendarRecord.prototype.fields.getRange(), 
		remoteSort: true,
                sortInfo: {
                    field: 'CalendarId',
                    direction: 'ASC'
                }
            });

            // A sample event store that loads static JSON from a local file. Obviously a real
            // implementation would likely be loading remote data via an HttpProxy, but the
            // underlying store functionality is the same.  Note that if you would like to 
            // provide custom data mappings for events, see EventRecord.js.
		    /*this.eventStore = new Ext.data.JsonStore({
		        id: 'eventStore',
		        root: 'evts',
		        data: eventList, // defined in event-list.js
				proxy: new Ext.data.MemoryProxy(),
		        fields: Ext.calendar.EventRecord.prototype.fields.getRange(),
		        sortInfo: {
		            field: 'StartDate',
		            direction: 'ASC'
		        }
		    });*/

		baseUrl = 'http://184.106.42.240/flexdds/administrator/index2.php?option=com_civicrm&activity_type_id=43&snippet=1&task=civicrm/ajax/activity/';

		var proxy = new Ext.data.HttpProxy({
        		disableCaching: false, // no need for cache busting when loading via Ajax
        		api: {
            			read:    baseUrl+'view',
 			        create:  baseUrl+'create',
            			update:  baseUrl+'update',
            			destroy: baseUrl+'delete'
        		},
        		listeners: {
            			exception: function(proxy, type, action, o, res, arg){
                			var msg = res.message ? res.message : Ext.decode(res.responseText).message;
                			// ideally an app would provide a less intrusive message display
                			Ext.Msg.alert('Server Error', msg);
            			}
        		}
    		});


	var reader = new Ext.data.JsonReader({
        totalProperty: 'total',
        successProperty: 'success',
        idProperty: 'id',
        root: 'rows',
        messageProperty: 'message',
        fields: Ext.ensible.cal.EventRecord.prototype.fields.getRange()
    });

    var writer = new Ext.data.JsonWriter({
        encode: true,
        writeAllFields: false
    });


	this.eventStore = new Ext.ensible.cal.EventStore({
        id: 'event-store',
        restful: true,
        proxy: proxy,
        reader: reader,
        writer: writer,
        // the view will automatically set start / end date params for you. You can
        // also pass a valid config object as specified by Ext.data.Store.load()
        // and the start / end params will be appended to it.
        autoLoad: true,

        // It's easy to provide generic CRUD messaging without having to handle events on every individual view.
        // Note that while the store provides individual add, update and remove events, those fire BEFORE the
        // remote transaction returns from the server -- they only signify that records were added to the store,
        // NOT that your changes were actually persisted correctly in the back end. The 'write' event is the best
        // option for generically messaging after CRUD persistance has succeeded.
        listeners: {
            'write': function(store, action, data, resp, rec){
                switch(action){
                    case 'create':
                        Ext.ensible.sample.msg('Add', 'Added "' + Ext.value(rec.data[Ext.ensible.cal.EventMappings.Title.name], '(No title)') + '"');
                        break;
                    case 'update':
                        Ext.ensible.sample.msg('Update', 'Updated "' + Ext.value(rec.data[Ext.ensible.cal.EventMappings.Title.name], '(No title)') + '"');
                        break;
                    case 'destroy':
                        Ext.ensible.sample.msg('Delete', 'Deleted "' + Ext.value(rec.data[Ext.ensible.cal.EventMappings.Title.name], '(No title)') + '"');
                        break;
                }
            }
        }
    });

            
            // This is the app UI layout code.  All of the calendar views are subcomponents of
            // CalendarPanel, but the app title bar and sidebar/navigation calendar are separate
            // pieces that are composed in app-specific layout code since they could be ommitted
            // or placed elsewhere within the application.
            new Ext.Viewport({
                layout: 'border',
                renderTo: 'calendar-ct',
                items: [{
                    id: 'app-header',
                    region: 'north',
                    height: 35,
                    border: false,
                    contentEl: 'app-header-content'
                },{
                    id: 'app-center',
                    title: '...', // will be updated to view date range
                    region: 'center',
                    layout: 'border',
                    items: [{
                        id:'app-west',
                        region: 'west',
                        width: 176,
                        border: false,
                        items: [{
                            xtype: 'datepicker',
                            id: 'app-nav-picker',
                            cls: 'ext-cal-nav-picker',
                            listeners: {
                                'select': {
                                    fn: function(dp, dt){
                                        App.calendarPanel.setStartDate(dt);
                                    },
                                    scope: this
                                }
                            }
                        }]
                    },{
                        xtype: 'calendarpanel',
                        eventStore: this.eventStore,
                        calendarStore: this.calendarStore,
                        border: false,
                        id:'app-calendar',
                        region: 'center',
                        activeItem: 2, // month view
                        
                        // CalendarPanel supports view-specific configs that are passed through to the 
                        // underlying views to make configuration possible without explicitly having to 
                        // create those views at this level:
                        monthViewCfg: {
                            showHeader: true,
                            showWeekLinks: true,
                            showWeekNumbers: true
                        },
                        
                        // Some optional CalendarPanel configs to experiment with:
                        //showDayView: false,
                        //showWeekView: false,
                        //showMonthView: false,
                        showNavBar: true,
                        //showTodayText: false,
                        //showTime: false,
                        //title: 'My Calendar', // the header of the calendar, could be a subtitle for the app
                        
                        // Once this component inits it will set a reference to itself as an application
                        // member property for easy reference in other functions within App.
                        initComponent: function() {
                            App.calendarPanel = this;
                            this.constructor.prototype.initComponent.apply(this, arguments);
                        },
                        
                        listeners: {
                            'eventclick': {
                                fn: function(vw, rec, el){
                                    this.showEditWindow(rec, el);
                                    this.clearMsg();
                                },
                                scope: this
                            },
                            'eventover': function(vw, rec, el){
                                //console.log('Entered evt rec='+rec.data.Title+', view='+ vw.id +', el='+el.id);
                            },
                            'eventout': function(vw, rec, el){
                                //console.log('Leaving evt rec='+rec.data.Title+', view='+ vw.id +', el='+el.id);
                            },
                            'eventadd': {
                                fn: function(cp, rec){
                                    this.showMsg('Event '+ rec.data.Title +' was added');
                                },
                                scope: this
                            },
                            'eventupdate': {
                                fn: function(cp, rec){
                                    this.showMsg('Event '+ rec.data.Title +' was updated');
                                },
                                scope: this
                            },
                            'eventdelete': {
                                fn: function(cp, rec){
                                    this.showMsg('Event '+ rec.data.Title +' was deleted');
                                },
                                scope: this
                            },
                            'eventcancel': {
                                fn: function(cp, rec){
                                    // edit canceled
                                },
                                scope: this
                            },
                            'viewchange': {
                                fn: function(p, vw, dateInfo){
                                    if(this.editWin){
                                        this.editWin.hide();
                                    };
                                    if(dateInfo !== null){
                                        // will be null when switching to the event edit form so ignore
                                        Ext.getCmp('app-nav-picker').setValue(dateInfo.activeDate);
                                        this.updateTitle(dateInfo.viewStart, dateInfo.viewEnd);
                                    }
                                },
                                scope: this
                            },
                            'dayclick': {
                                fn: function(vw, dt, ad, el){
                                    this.showEditWindow({
                                        StartDate: dt,
                                        IsAllDay: ad
                                    }, el);
                                    this.clearMsg();
                                },
                                scope: this
                            },
                            'rangeselect': {
                                fn: function(win, dates, onComplete){
                                    this.showEditWindow(dates);
                                    this.editWin.on('hide', onComplete, this, {single:true});
                                    this.clearMsg();
                                },
                                scope: this
                            },
                            'eventmove': {
                                fn: function(vw, rec){
                                    rec.commit();
                                    var time = rec.data.IsAllDay ? '' : ' \\a\\t g:i a';
                                    this.showMsg('Event '+ rec.data.Title +' was moved to '+rec.data.StartDate.format('F jS'+time));
                                },
                                scope: this
                            },
                            'eventresize': {
                                fn: function(vw, rec){
                                    rec.commit();
                                    this.showMsg('Event '+ rec.data.Title +' was updated');
                                },
                                scope: this
                            },
                            'eventdelete': {
                                fn: function(win, rec){
                                    this.eventStore.remove(rec);
                                    this.showMsg('Event '+ rec.data.Title +' was deleted');
                                },
                                scope: this
                            },
                            'initdrag': {
                                fn: function(vw){
                                    if(this.editWin && this.editWin.isVisible()){
                                        this.editWin.hide();
                                    }
                                },
                                scope: this
                            }
                        }
                    }]
                }]
            });
        },
        
        // The edit popup window is not part of the CalendarPanel itself -- it is a separate component.
        // This makes it very easy to swap it out with a different type of window or custom view, or omit
        // it altogether. Because of this, it's up to the application code to tie the pieces together.
        // Note that this function is called from various event handlers in the CalendarPanel above.
		showEditWindow : function(rec, animateTarget){
	        if(!this.editWin){
	            this.editWin = new Ext.calendar.EventEditWindow({
                    calendarStore: this.calendarStore,
					listeners: {
						'eventadd': {
							fn: function(win, rec){
								win.hide();
								rec.data.IsNew = false;
								this.eventStore.add(rec);
                                this.showMsg('Event '+ rec.data.Title +' was added');
							},
							scope: this
						},
						'eventupdate': {
							fn: function(win, rec){
								win.hide();
								rec.commit();
                                this.showMsg('Event '+ rec.data.Title +' was updated');
							},
							scope: this
						},
						'eventdelete': {
							fn: function(win, rec){
								this.eventStore.remove(rec);
								win.hide();
                                this.showMsg('Event '+ rec.data.Title +' was deleted');
							},
							scope: this
						},
                        'editdetails': {
                            fn: function(win, rec){
                                win.hide();
                                App.calendarPanel.showEditForm(rec);
                            }
                        }
					}
                });
	        }
	        this.editWin.show(rec, animateTarget);
		},
        
        // The CalendarPanel itself supports the standard Panel title config, but that title
        // only spans the calendar views.  For a title that spans the entire width of the app
        // we added a title to the layout's outer center region that is app-specific. This code
        // updates that outer title based on the currently-selected view range anytime the view changes.
        updateTitle: function(startDt, endDt){
            var p = Ext.getCmp('app-center');
            
            if(startDt.clearTime().getTime() == endDt.clearTime().getTime()){
                p.setTitle(startDt.format('F j, Y'));
            }
            else if(startDt.getFullYear() == endDt.getFullYear()){
                if(startDt.getMonth() == endDt.getMonth()){
                    p.setTitle(startDt.format('F j') + ' - ' + endDt.format('j, Y'));
                }
                else{
                    p.setTitle(startDt.format('F j') + ' - ' + endDt.format('F j, Y'));
                }
            }
            else{
                p.setTitle(startDt.format('F j, Y') + ' - ' + endDt.format('F j, Y'));
            }
        },
        
        // This is an application-specific way to communicate CalendarPanel event messages back to the user.
        // This could be replaced with a function to do "toast" style messages, growl messages, etc. This will
        // vary based on application requirements, which is why it's not baked into the CalendarPanel.
        showMsg: function(msg){
            Ext.fly('app-msg').update(msg).removeClass('x-hidden');
        },
        
        clearMsg: function(){
            Ext.fly('app-msg').update('').addClass('x-hidden');
        }
    }
}();

Ext.onReady(App.init, App);
