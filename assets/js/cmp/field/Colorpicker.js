/**
 * Created by eoto88 on 21/01/17.
 */
ES.Field.Colorpicker = ES.Field.Text.extend({

    initCmp: function(config) {
        var me = this;

        ES.apply(me, config);
        me._super(me.cmpType);
        me.appendTo(config.parent);
        var $colorPicker = me.getCmp();

        $colorPicker.find('.colorpicker-component').colorpicker();
    },

    getTpl: function() {
        var me = this;

        me.before = '<div class="input-group colorpicker-component">';
        me.after = '<span class="input-group-addon"><i></i></span></div>';

        var tpl = me._super();
        return tpl;
    }
}, 'ES.Field.Colorpicker');