/**
 * Created by eoto88 on 15/01/17.
 */
ES.Cmp = Class.extend({
    cmpType: null,
    cmpIndex: null,
    cmpId: null,
    hidden: false,

    initCmp: function(cmpType) {
        var me = this;

        me.cmpType = cmpType;
        me.cmpId = cmpType +'-'+ ES.getNextCmpId();
        ES.createdCmps[me.cmpId] = me;
        if(me.cmpIndex) {
            ES.createdCmps[me.cmpIndex] = me;
        }
        return me.cmpId;
    },

    appendTo: function(parent) {
        var me = this,
            tpl = me.getTpl(),
            $parent = ES.getJQueryObject(parent);

        $(tpl).appendTo($parent);

        if(me.hidden) {
            me.hide();
        }
    },

    getTpl: function() {
        return '';
    },

    getCmp: function() {
        return $("#"+ this.cmpId);
    },

    hide: function() {
        this.getCmp().hide();
    },

    show: function() {
        this.getCmp().show();
    },

    destroy: function() {
        this.getCmp().remove();
    }
});