/**
 * Created by eoto88 on 17/01/17.
 */
ES.Field.Tagfield = ES.Field.Combo.extend({
    values: null,
    selectize: null,
    load: null,

    initCmp: function(config) {
        var me = this;

        ES.apply(me, config);
        me._super(me.cmpType);
        me.appendTo(config.parent);

        var options = {
            maxItems: null,
            hideSelected: true,
            load: me.load,
            valueField: me.valField,
            labelField: me.titleField,
            searchField: me.titleField,
            preload:true,
            //optgroupField: 'id_category',
            //optgroupValueField: 'id_category',
            //optgroupLabelField: 'category_title',
            plugins: ['remove_button'], // ,'optgroup_columns'
            create: false
        };

        var $selectize = me.getCmp().find('.fieldSelectize').selectize(options);

        me.selectize = $selectize[0].selectize;
    },

    /**
     * @override
     * @returns String
     */
    getTpl: function() {
        var me = this;

        me.cls = "fieldSelectize";

        return me._super();
    },

    getValue: function() {
        var me = this;

        return me.selectize.getValue();
    },

    setValue: function(values) {
        var me = this;

        return me.selectize.setValue(values);
    }
}, 'ES.Field.Tagfield');