/**
 * Created by eoto88 on 15/01/17.
 */
ES.Button = ES.Cmp.extend({
    type: null,
    text: null,

    initCmp: function(config) {
        var me = this;

        ES.apply(me, config);
        me._super('button');
        me.appendTo(config.parent);
    },

    getTpl: function() {
        var me = this;
        return '<button class="btn btn-default" type="submit">'+ me.text +'</button>';
    }
}, 'ES.Button');