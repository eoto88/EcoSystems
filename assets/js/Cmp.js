/**
 * Created by eoto88 on 15/01/17.
 */
ES.Cmp = Class.extend({
    //cmpType: null,
    //cmpId: null,

    initCmp: function(cmpType) {
        var me = this;

        me.cmpType = cmpType;
        me.cmpId = cmpType +'-'+ ES.getNextCmpId();
        return me.cmpId;
    },

    appendTo: function(cmp) {
        var me = this,
            tpl = me.getTpl(),
            $cmp = ES.getJQueryObject(cmp);

        $(tpl).appendTo($cmp);
    },

    getTpl: function() {
        return '';
    },

    getCmp: function() {
        return $("#"+ this.cmpId);
    },

    // initCmp:function(params) {
    //     debugger;
    //     $appendTo.append('<div id="'+ me.cmpId +'"</div>');
    // },

    startLoading: function() {
        // TODO
        var $cmp = $('#' + this.cmpId);
        $component.append('<div class="overlay"></div><div class="spinner"><div class="double-bounce1"></div><div class="double-bounce2"></div></div>');
    },

    stopLoading: function() {
        // TODO
        var $component = $('#' + this.cssId);
        $component.find('.overlay').remove();
        $component.find('.spinner').remove();
    }
});