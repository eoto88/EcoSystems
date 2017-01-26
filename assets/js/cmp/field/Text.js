/**
 * Created by eoto88 on 17/01/17.
 */
ES.Field.Text = ES.Field.extend({
    attr: '',
    cls: '',
    maxLength: null,

    initCmp: function(config) {
        var me = this;

        ES.apply(me, config);
        me._super(me.cmpType);
        me.cls += ' form-control';
        me.appendTo(config.parent);
    },

    getTpl: function() {
        var me = this,
            tpl = me._super(),
            label = me.getLabel(),
            field = '{{{before}}}<input type="text" name="{{name}}" class="{{cls}}" {{{attr}}} />{{{after}}}';

        if( ! ES.isEmpty(me.maxLength) ) {
            me.attr += ' maxlength="'+ me.maxLength +'"';
        }
        if( ! ES.isEmpty(me.value) ) {
            me.attr += ' value="'+ me.value +'"';
        }

        field = Handlebars.compile(label + field);

        return tpl({
            cmpId: me.cmpId,
            field: field(me)
        });;
    },

    getInput: function() {
        return this.getCmp().find('input');
    }
}, 'ES.Field.Text');