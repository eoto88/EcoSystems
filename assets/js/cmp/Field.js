/**
 * Created by eoto88 on 15/01/17.
 */
ES.Field = ES.Cmp.extend({
    name: null,
    value: null,
    label: null,
    obligatory: false,

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
            var lbl = me.label;
            if(me.obligatory) {
                lbl += ' <span class="obligatory">*</span>'
            }
            return '<label>'+ lbl +' :</label>';
        }
        return '';
    },

    getInput: function() {
        return this.getCmp();
    },

    getValue: function () {
        return this.getInput().val();
    },
    
    setValue: function (value) {
        var me = this,
            $input = me.getInput();

        $input.val(value);
        me.value = value;
    },

    focus: function() {
        var me = this;

        me.getInput().focus();
    }
});