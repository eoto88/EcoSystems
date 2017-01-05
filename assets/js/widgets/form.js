App.WidgetForm = App.Widget.extend({
    init: function() {
        var me = this;
        me._super( 'widget-form' );

        var $component = $('#' + this.cssId);

        $component.find('form').submit(function(e) {
            var $form = $(this),
                method = 'POST',
                url = BASE_URL +'api/'+ $form.data('api-url'),
                requiresIdInstance = $form.data('requires-id-instance'),
                jsonData = $form.serializeFormJSON();

            e.preventDefault();

            if(requiresIdInstance) {
                var id_instance = ES.getCurrentInstanceId();
                url = BASE_URL +'api/instances/'+id_instance+'/'+ $form.data('api-url'); // TODO PUT   +'/'+ 1,
            }

            me.submitForm({
                form: $form,
                url: url,
                method: method,
                data: jsonData,
                success: function(data) {

                }
            });
        });

        $component.find('.form-spinner').each(function(index, spinner) {
            var initval = $(spinner).data('initval'),
                min = $(spinner).data('min'),
                max = $(spinner).data('max'),
                step = $(spinner).data('step'),
                decimals = $(spinner).data('decimals');

            $(spinner).TouchSpin({
                initval: initval,
                min: min,
                max: max,
                step: step,
                decimals: decimals,
                boostat: 5,
                maxboostedstep: 10
            });
        });

        $component.find('.form-datepicker').each(function(index, datepicker) {
            var format = $(datepicker).data('format');

            $(datepicker).datetimepicker({
                format: format,
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
        });
    }
});

(function ($) {
    $.fn.serializeFormJSON = function () {

        var o = {};
        var a = this.serializeArray();
        $.each(a, function () {
            if (o[this.name]) {
                if (!o[this.name].push) {
                    o[this.name] = [o[this.name]];
                }
                o[this.name].push(this.value || '');
            } else {
                o[this.name] = this.value || '';
            }
        });
        return o;
    };
})(jQuery);