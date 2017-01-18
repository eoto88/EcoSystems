/**
 * Created by eoto88 on 17/01/17.
 */
ES.Field.Text = ES.Field.extend({
    initCmp: function(configs) {
        var me = this;

        ES.apply(me, configs);
        me._super('text');
        me.appendTo(configs.parent);
    },

    getTpl: function() {
        var me = this,
            tpl = me._super(),
            label = me.getLabel(),
            field = '<input type="text" id="{{cmpId}}" name="{{name}}" class="form-control" maxlength="{{maxLength}}" />';

        field = Handlebars.compile(label + field);

        //debugger;

        return tpl({ field: field(me) });
    }
}, 'ES.Field.Text');