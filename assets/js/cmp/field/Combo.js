/**
 * Created by eoto88 on 17/01/17.
 */
ES.Field.Combo = ES.Field.extend({
    initCmp: function(config) {
        var me = this;

        ES.apply(me, config);
        me._super('combo');
        me.appendTo(config.parent);
    },

    getTpl: function() {
        var me = this,
            tpl = me._super(),
            label = me.getLabel(),
            field = '<select id="{{cmpId}}" class="form-control"></select>';

        field = Handlebars.compile(label + field);

        return tpl({ field: field(me) });
    }
}, 'ES.Field.Combo');