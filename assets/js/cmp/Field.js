/**
 * Created by eoto88 on 15/01/17.
 */
ES.Field = ES.Cmp.extend({
    name: null,
    value: null,
    label: null,

    initCmp: function(cmpType) {
        var me = this;

        me._super(cmpType);
    },

    getTpl: function() {
        return Handlebars.compile('<div class=\"form-group es-cmp\" id=\"{{cmpId}}\">{{{field}}}</div>');
    },

    getLabel: function() {
        var me = this;
        if(!ES.isEmpty(me.label)) {
            return '<label>'+ me.label +' :</label>';
        }
        return '';
    },

    getInput: function() {
        return this.getCmp();
    },

    getValue: function () {
        return this.value;
    },
    
    setValue: function (value) {
        var me = this,
            $input = me.getInput();

        $input.val(value);
        me.value = value;
    }
});