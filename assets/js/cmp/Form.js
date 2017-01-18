/**
 * Created by eoto88 on 15/01/17.
 */
ES.Form = ES.Cmp.extend({
    initCmp: function(configs) {
        var me = this;

        me.cmpId = me._super('form');
        me.appendTo(configs.parent);
        var $form = $("#"+ me.cmpId);
        $.each(configs.fields, function(key, config) {
            var cmp = ES.create(config.cmpType);
            config.parent = $form;
            cmp.initCmp(config);
            //var fieldTpl = me.createField(field);
            //$component.append(fieldTpl);
        });
    },

    getTpl: function() {
        var me = this;
        return '<form id="'+ me.cmpId +'"></form>';
    }

    //createField: function(configs) {
    //    var me = this,
    //        fieldTpl = "",
    //        label = '';
    //
    //    //debugger;
    //    var classCmp = ES.Field[ES.capitalizeFirstLetter(configs.cmpType)];
    //    if(classCmp) {
    //        var field = ES.factory(classCmp);
    //        debugger;
    //        // cmp.initCmp(item);
    //    } else {
    //        console.warn("ES.Field."+ ES.capitalizeFirstLetter(configs.cmpType) + " doesn't exist.");
    //    }
    //
    //    if(configs.label) {
    //        label = me.createLabel(configs);
    //    }
    //
    //    if(configs.cmpType == "text") {
    //        var attrs = '';
    //        if(configs.maxLength) {
    //            attrs += ' maxlength=\"'+configs.maxLength+'\"';
    //        }
    //
    //        fieldTpl = '<input type=\"text\" class=\"form-control\" name=\"'+ configs.name +'\" ' + attrs + ' />';
    //    } else if(configs.cmpType == "combobox") {
    //        var values = '';
    //        $.each(configs.values, function(key, val) {
    //            //debugger;
    //            values += '<option value=\"'+ val +'\">Percentage</option>';
    //        });
    //        fieldTpl = '<select name=\"'+ configs.name +'\" class=\"form-control\">'+ values +'</select>';
    //    } else if(configs.cmpType == "formSeparator") {
    //        fieldTpl = '<div class="formSeparator">'+ configs.title +'</div>';
    //    } else if(configs.cmpType == "button") {
    //        fieldTpl = '<input class="btn btn-default" type="submit" value="'+ configs.text +'">';
    //        //<button type="button" class="btn btn-warning">Warning</button>
    //    }
    //
    //    switch(configs.cmpType) {
    //        case 'text':
    //        case 'combobox':
    //            fieldTpl = '<div class=\"form-group\">'+ label + fieldTpl + '</div>';
    //            break;
    //    }
    //
    //    return fieldTpl;
    //},
    //
    //createLabel: function(configs) {
    //    return '<label>'+ configs.label +'</label>';
    //}

}, 'ES.Form');