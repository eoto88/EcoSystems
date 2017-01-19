/**
 * Created by eoto88 on 15/01/17.
 */
ES.Table = ES.Cmp.extend({
    initCmp: function(configs) {
        var me = this;

        me.cmpId = me._super('table');
        me.appendTo(configs.parent);
        var $table = $("#"+ me.cmpId);

        $.each(configs.columns, function(key, config) {
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
        return '<table id="'+ me.cmpId +'"></table>';
    }

}, 'ES.Table');