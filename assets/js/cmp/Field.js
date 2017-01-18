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

        //configs.appendTo.append('<form id="'+ me.cmpId +'"></form>');
        //$component = $("#"+ me.cmpId);
    },

    getTpl: function() {
        return Handlebars.compile('<div class=\"form-group\">{{{field}}}</div>');
    },

    getLabel: function() {
        var me = this;
        if(!ES.isEmpty(me.label)) {
            return '<label>'+ me.label +' :</label>';
        }
        return '';
    },

    getValue: function () {
        return this.value;
    },
    
    setValue: function (value) {
        this.value = value;
    }
});