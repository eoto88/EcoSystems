/**
 * Created by eoto88 on 26/01/17.
 */
ES.Field.Datepicker = ES.Field.Text.extend({
    format: 'YYYY-MM-DD',

    initCmp: function(config) {
        var me = this;

        ES.apply(me, config);
        me.cls = 'datepicker'
        me._super(me.cmpType);
        if(ES.isEmpty(me.value)) {
            me.value = new Date();
        }
        me.appendTo(config.parent);
        var $datepicker = me.getInput();

        $datepicker.datetimepicker({
            format: me.format,
            icons: {
                time: 'fa fa-clock-o',
                date: 'fa fa-calendar',
                up: 'fa fa-chevron-up',
                down: 'fa fa-chevron-down',
                previous: 'fa fa-chevron-left',
                next: 'fa fa-chevron-right',
                today: 'fa fa-calendar-check-o',
                clear: 'fa fa-trash-o',
                close: 'fa fa-close'
            }
        });
    },

    getTpl: function() {
        var me = this;

        //me.attr = 'data-format="'+ me.format +'"';

        var tpl = me._super();
        return tpl;
    }
}, 'ES.Field.Datepicker');