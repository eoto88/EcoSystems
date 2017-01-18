/**
 * Created by eoto88 on 17/01/17.
 */
ES.Field.Text = ES.Field.extend({
    attr: '',
    maxLength: null,

    initCmp: function(config) {
        var me = this;

        ES.apply(me, config);
        me._super('text');
        me.appendTo(config.parent);
    },

    getTpl: function() {
        var me = this,
            tpl = me._super(),
            label = me.getLabel(),
            field = '{{{before}}}<input type="text" id="{{cmpId}}" name="{{name}}" class="form-control" {{{attr}}} />{{{after}}}';

        if( ! ES.isEmpty(me.maxLength) ) {
            me.attr += ' maxlength="'+ me.maxLength +'"';
        }

        field = Handlebars.compile(label + field);

        //debugger;

        return tpl({ field: field(me) });
    }
}, 'ES.Field.Text');