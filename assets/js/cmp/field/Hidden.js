/**
 * Created by eoto88 on 17/01/17.
 */
ES.Field.Hidden = ES.Field.extend({
    initCmp: function(config) {
        var me = this;

        ES.apply(me, config);
        me._super('hidden');
        me.appendTo(config.parent);
    },

    getTpl: function() {
        return '<input type=\"hidden\" id=\"'+ this.cmpId +'\" name=\"'+ this.name +'\" />';
    }
}, 'ES.Field.Hidden');