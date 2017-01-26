/**
 * Created by eoto88 on 15/01/17.
 */
ES.Button = ES.Cmp.extend({
    type: null,
    text: null,
    name: '',
    cls: '',

    initCmp: function(config) {
        var me = this;

        ES.apply(me, config);
        me._super('button');
        me.cls = me.name + ' btn btn-default';
        me.appendTo(config.parent);
    },

    getTpl: function() {
        var me = this;
        return '<button id="'+ me.cmpId +'" class="'+ me.cls +'" type="submit">'+ me.text +'</button>';
    }
}, 'ES.Button');