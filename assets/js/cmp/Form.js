/**
 * Created by eoto88 on 15/01/17.
 */
ES.Form = ES.Cmp.extend({
    fields: null,

    initCmp: function(configs) {
        var me = this;

        ES.apply(me, configs);
        me._super('form');
        me.appendTo(configs.parent);
        me.fields = [];
        $.each(configs.fields, function(key, config) {
            me.addField(config);
        });
        $.each(me.buttons, function(key, config) {
            me.addField(config, true);
        });
    },

    /**
     * @override
     * @returns {string}
     */
    getTpl: function() {
        var me = this,
            tpl = '<form id="'+ me.cmpId +'" class="es-cmp">';
        if(me.title) {
            tpl += '<header role="heading"><span class="widget-icon"><i class="fa fa-pencil fa-fw"></i></span><h2>'+ me.title +'</h2> <span class="close-form"><i class="fa fa-times"></i></span></header>';
        }
        return tpl + '<div class="form-fields"></div><div class="form-buttons"></div></form>';
    },

    populate: function(obj) {
        var me = this;

        for(var prop in obj) {
            var $field = me.getCmpFieldByName(prop);
            if($field.length) {
                var cmpField = ES.getCmp($field.attr('id'));
                cmpField.setValue(obj[prop])
            } else {
                // <debug>
                console.warn('Field not found: '+ prop)
                // </debug>
            }
        }
    },

    getCmpFieldByName: function(name) {
        var me = this,
            $field = me.getCmp().find('[name='+ name +']');

        if(ES.isEmpty($field.attr('id'))) {
            $field = $field.closest('.es-cmp');
        }

        return $field;
    },

    addField: function(config, button) {
        var me = this,
            $form = me.getCmp(),
            field = ES.create(config.cmpType);

         var $parent = (button ? $form.find('.form-buttons') : $form.find('.form-fields'))

        config.parent = $parent;
        field.initCmp(config);

        me.fields[field.name] = field;
    },

    removeField: function(fieldName) {
        var me = this,
            field = me.getFieldByName(fieldName);

        if(field) {
            var index = me.fields.indexOf(fieldName);
            if (index > -1) {
                me.fields.splice(index, 1);
            }
            field.destroy();
        } else {
            console.warn("Can't delete this field: "+ fieldName);
        }
    },

    getFieldByName: function(name) {
        var me = this;

        return me.fields[name];
    }

}, 'ES.Form');