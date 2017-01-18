/**
 * Created by eoto88 on 17/01/17.
 */
ES.Field.Hidden = ES.Field.extend({
    initCmp: function() {
        var me = this;
        me.cmpId = me._super('hidden');
    },

    getTpl: function() {
        return '<input type=\"hidden\" name=\"'+ me.name +'\" />';
    }
}, 'ES.Field.Hidden');