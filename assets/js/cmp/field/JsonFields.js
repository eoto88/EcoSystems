/**
 * Created by eoto88 on 22/01/17.
 */
ES.Field.JsonFields = ES.Field.Hidden.extend({
    options: null,
    formId: null,

    initCmp: function(config) {
        var me = this;

        ES.apply(me, config);
        me._super('jsonfields');
        me.formId = config.parent.closest('.es-cmp').attr('id');
        me.appendTo(config.parent);
    },

    getTpl: function() {
        var me = this;

        if(me.options) {
            var options = ES.parseData(me.options),
                formParam = ES.getCmp(me.formId);
            $.each(options, function(key, option) {
                formParam.addField(option);
            });
        }
        return '<input type=\"hidden\" id=\"'+ this.cmpId +'\" name=\"'+ this.name +'\" />';
    },

    /**
     * @override
     */
    setValue: function(value) {
        var me = this;

        me.value = value;
    }
}, 'ES.Field.JsonFields');