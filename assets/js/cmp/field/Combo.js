/**
 * Created by eoto88 on 17/01/17.
 */
ES.Field.Combo = ES.Field.extend({
    cls: 'form-control',
    valField: null,
    titleField: null,
    loaded: false,

    initCmp: function(config) {
        var me = this;

        ES.apply(me, config);
        me._super(me.cmpType);
        me.appendTo(config.parent);
    },

    /**
     * @override
     * @returns String
     */
    getTpl: function() {
        var me = this,
            tpl = me._super(),
            label = me.getLabel(),
            field = '<select name="{{name}}" class="{{cls}}"></select>';

        field = Handlebars.compile(label + field);

        return tpl({
            cmpId: me.cmpId,
            field: field(me)
        });
    },

    /**
     * @averride
     * @param value
     */
    setValue: function (value) {
        var me = this,
            $select = me.getInput();

        me.value = value;

        if(me.url && ! me.loaded) {
            me.loadValues();
        } else if(me.url && me.loaded) {
            var $value = $select.find('option[value='+me.value+']');
            if($value.length) {
                $value.attr('selected', 'selected');
            }
        }
    },

    getInput: function() {
        return this.getCmp().find('select');
    },

    loadValues: function() {
        var me = this;

        ES.ajax({
            url: me.url,
            success: function(data) {
                $.each(data.entities, function(key, entity) {
                    me.addOption(entity);
                });

                me.loaded = true;

                if(me.value) {
                    me.setValue(me.value);
                }
            }
        });
    },

    addOption: function(opt) {
        var me = this,
            $select = me.getInput();

        $select.append('<option value="'+ opt[me.valField] +'">'+ opt[me.titleField] +'</option')
    }
}, 'ES.Field.Combo');