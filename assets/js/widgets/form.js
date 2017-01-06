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

        // TODO
        $component.find('.fieldSelectize').selectize({
            maxItems: null,
            valueField: 'id',
            labelField: 'title',
            searchField: 'title',
            preload: true,
            hideSelected: true,
            optgroupField: 'id_category',
            optgroupValueField: 'id_category',
            optgroupLabelField: 'category_title',
            plugins: ['remove_button','optgroup_columns'],
            create: false,
            load: function(query, callback) {
                //if (!query.length) return callback();
                $.ajax({
                    url: BASE_URL + "api/params/",
                    type: 'GET',
                    error: function() {
                        callback();
                    },
                    success: function(res) {
                        callback(res.entities);
                    }
                });
            }
        });

        $component.find('.icon-picker').iconpicker({
            title: "Choose an icon", // TODO
            icons: $.merge([
                    'wi-day-sunny',
                    'wi-day-cloudy',
                    'wi-day-cloudy-gusts',
                    'wi-day-cloudy-windy',
                    'wi-day-fog',
                    'wi-day-hail',
                    'wi-day-haze',
                    'wi-day-lightning',
                    'wi-day-rain',
                    'wi-day-rain-mix',
                    'wi-day-rain-wind',
                    'wi-day-showers',
                    'wi-day-sleet',
                    'wi-day-sleet-storm',
                    'wi-day-snow',
                    'wi-day-snow-thunderstorm',
                    'wi-day-snow-wind',
                    'wi-day-sprinkle',
                    'wi-day-storm-showers',
                    'wi-day-sunny-overcast',
                    'wi-day-thunderstorm',
                    'wi-day-windy',
                    'wi-solar-eclipse',
                    'wi-hot',
                    'wi-day-cloudy-high',
                    'wi-day-light-wind',
                    'wi-night-clear',
                    'wi-night-alt-cloudy',
                    'wi-night-alt-cloudy-gusts',
                    'wi-night-alt-cloudy-windy',
                    'wi-night-alt-hail',
                    'wi-night-alt-lightning',
                    'wi-night-alt-rain',
                    'wi-night-alt-rain-mix',
                    ''],
                // https://erikflowers.github.io/weather-icons/
                $.iconpicker.defaultOptions.icons),
            fullClassFormatter: function(val){
                if(val.match(/^fa-/)){
                    return 'fa '+val;
                }else{
                    return 'wi '+val;
                }
            }
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