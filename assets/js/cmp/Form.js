/**
 * Created by eoto88 on 15/01/17.
 */
ES.Form = ES.Cmp.extend({
    initCmp: function(configs) {
        var me = this;

        me.cmpId = me._super('form');
        me.appendTo(configs.parent);
        var $form = $("#"+ me.cmpId);
        $.each(configs.fields, function(key, config) {
            var cmp = ES.create(config.cmpType);
            config.parent = $form;
            cmp.initCmp(config);
        });
    },

    /**
     * @override
     * @returns {string}
     */
    getTpl: function() {
        var me = this;
        return '<form id="'+ me.cmpId +'"></form>';
    }

}, 'ES.Form');