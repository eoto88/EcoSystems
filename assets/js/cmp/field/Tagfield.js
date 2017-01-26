/**
 * Created by eoto88 on 17/01/17.
 */
ES.Field.Tagfield = ES.Field.Combo.extend({
    values: null,
    selectize: null,

    initCmp: function(config) {
        var me = this;

        ES.apply(me, config);
        me._super(me.cmpType);
        me.appendTo(config.parent);

        var $selectize = me.getCmp().find('.fieldSelectize').selectize({
            maxItems: null,
            valueField: 'id',
            labelField: 'title',
            searchField: 'title',
            preload: true,
            hideSelected: true,
            //optgroupField: 'id_category',
            //optgroupValueField: 'id_category',
            //optgroupLabelField: 'category_title',
            plugins: ['remove_button'], // ,'optgroup_columns'
            create: false,
            load: function(query, callback) {
                var idInstance = ES.getActiveInstanceId();

                ES.ajax({
                    url: BASE_URL + "api/instances/"+ idInstance +"/params",
                    success: function(data) {
                        me.values = data.entities;
                        callback(data.entities);
                    }
                });
            }
        });

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