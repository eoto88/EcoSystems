/**
 * Created by eoto88 on 17/01/17.
 */
ES.Field.Iconpicker = ES.Field.Text.extend({
    realValue: null,

    initCmp: function(config) {
        var me = this;

        ES.apply(me, config);
        me.realValue = me.value;
        me.value = me.formatValue(me.value);

        me._super(me.cmpType);
        me.appendTo(config.parent);
        var $iconPicker = me.getCmp();

        $iconPicker.iconpicker({
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
    },

    getTpl: function() {
        var me = this,
            label = me.getLabel();

        me.attr += ' data-placement="bottomRight"';
        me.before = '<div class="input-group iconpicker-container">';
        me.after = '<span class="input-group-addon"><i class="'+ (me.realValue ? me.realValue : '') +'"></i></span></div>';

        var tpl = me._super();
        return tpl;
    },

    setValue: function (value) {
        var me = this,
            $input = me.getInput();

        me.realValue = value;
        me.value = me.formatValue(value);

        $input.val(value);
        me._super(value);
    },

    formatValue: function(value) {
        return value.replace('fa ', '').replace('wi ', '');
    }
}, 'ES.Field.Iconpicker');