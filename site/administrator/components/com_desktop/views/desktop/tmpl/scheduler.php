/*
 * Ext JS Library 3.3.1
 * Copyright(c) 2006-2010 Sencha Inc.
 * licensing@sencha.com
 * http://www.sencha.com/license
 */


// for example purposes     
var version = '1.0';            
                                
MyDesktop.Scheduler = Ext.extend(Ext.app.Module, {
    init : function(){              
        this.launcher = {           
            text: 'Scheduler'+version,
            iconCls:'bogus',    
            handler : this.createWindow,
            scope: this,            
            windowId:windowIndex    
        }                       
    },                              
                                    
    createWindow : function(src){   
        var desktop = this.app.getDesktop();
        var win = desktop.getWindow('bogus'+src.windowId);
        if(!win){                   
            win = desktop.createWindow({
                id: 'bogus'+src.windowId,
                title:src.text,     
                width:640,          
                height:480,         
                html : '<iframe width="100%" height="100%" src="index.php?option=com_extscheduler"></iframe>',
                iconCls: 'bogus',   
                shim:false,         
                animCollapse:false, 
                constrainHeader:true
            });                     
        }                           
        win.show();                 
    }                           
});    
