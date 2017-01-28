/**
 * Created by eoto88 on 17/01/17.
 */
ES.Field.Tagfield = ES.Field.Combo.extend({
    values: null,
    selectize: null,
    load: null,
    groupField: null,
    groupValueField: null,
    groupLabelField: null,

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
            preload: true,
            plugins: ['remove_button'],
            create: false
        };

        if( ! ES.isEmpty(me.groupField) && ! ES.isEmpty(me.groupValueField) && ! ES.isEmpty(me.groupLabelField) ) {
            options.plugins.push('optgroup_columns');
            options.optgroupField = me.groupField;
            options.optgroupValueField = me.groupValueField;
            options.optgroupLabelField = me.groupLabelField;
        }

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
    },

    /**
     * @override
     */
    destroy: function() {
        var me = this;

        me.selectize.destroy();
        me._super();
    }
}, 'ES.Field.Tagfield');