/**
 * @class Ext.button.Split
 * @extends Ext.button.Button
 * A split button that provides a built-in dropdown arrow that can fire an event separately from the default
 * click event of the button.  Typically this would be used to display a dropdown menu that provides additional
 * options to the primary button action, but any custom handler can provide the arrowclick implementation.  Example usage:
 * <pre><code>
// display a dropdown menu:
new Ext.button.Split({
    renderTo: 'button-ct', // the container id
    text: 'Options',
    handler: optionsHandler, // handle a click on the button itself
    menu: new Ext.menu.Menu({
    items: [
            // these items will render as dropdown menu items when the arrow is clicked:
            {text: 'Item 1', handler: item1Handler},
            {text: 'Item 2', handler: item2Handler}
    ]
    })
});

// Instead of showing a menu, you provide any type of custom
// functionality you want when the dropdown arrow is clicked:
new Ext.button.Split({
    renderTo: 'button-ct',
    text: 'Options',
    handler: optionsHandler,
    arrowHandler: myCustomHandler
});
</code></pre>
 * @cfg {Function} arrowHandler A function called when the arrow button is clicked (can be used instead of click event)
 * @cfg {String} arrowTooltip The title attribute of the arrow
 * @constructor
 * Create a new menu button
 * @param {Object} config The config object
 * @xtype splitbutton
 */

Ext.define('Ext.button.Split', {

    /* Begin Definitions */

    alias: 'widget.splitbutton',

    extend: 'Ext.button.Button',
    alternateClassName: 'Ext.SplitButton',

    // private
    arrowCls      : 'split',
    split         : true,

    // private
    initComponent : function(){
        this.callParent();
        /**
         * @event arrowclick
         * Fires when this button's arrow is clicked
         * @param {MenuButton} this
         * @param {EventObject} e The click event
         */
        this.addEvents("arrowclick");
    },

     /**
     * Sets this button's arrow click handler.
     * @param {Function} handler The function to call when the arrow is clicked
     * @param {Object} scope (optional) Scope for the function passed above
     */
    setArrowHandler : function(handler, scope){
        this.arrowHandler = handler;
        this.scope = scope;
    },

    // private
    onClick : function(e, t){
        var me = this;
        
        e.preventDefault();
        if (!me.disabled) {
            if (me.overMenuTrigger) {
                if (me.menu && !me.menu.isVisible() && !me.ignoreNextClick) {
                    me.showMenu();
                }
                me.fireEvent("arrowclick", me, e);
                if (me.arrowHandler) {
                    me.arrowHandler.call(me.scope || me, me, e);
                }
            } else {
                if (me.enableToggle) {
                    me.toggle();
                }
                me.fireEvent("click", me, e);
                if (me.handler) {
                    me.handler.call(me.scope || me, me, e);
                }
            }
        }
    }
});