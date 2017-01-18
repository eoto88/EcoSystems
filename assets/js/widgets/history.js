ES.WidgetHistory = ES.Widget.extend({
    init: function() {
        var me = this;
        me._super('widget-history');

        this.temperatureHistoryChart = new Highcharts.Chart({
            chart: {
                renderTo: 'temperatureHistoryChart',
                marginTop: 50,
                height: 320,
                defaultSeriesType: 'spline'
            },
            title: {
                text: 'Temperature history'
            },
            xAxis: {
                type: 'datetime',
                dateTimeLabelFormats: {
                    day: '%e %b'
                }
            },
            yAxis: {
                minPadding: 0.2,
                maxPadding: 0.2,
                minRange: 0.5,
                title: {
                    text: 'Value',
                    margin: 25
                },
                plotBands: [{// Cold
                    from: 0,
                    to: 18,
                    color: 'rgba(68, 170, 213, 0.1)',
                    label: {
                        text: 'Cold',
                        style: {
                            color: '#606060'
                        }
                    }
                }, {// Hot
                    from: 35,
                    to: 60,
                    color: 'rgba(191, 11, 35, 0.1)',
                    label: {
                        text: 'Hot',
                        style: {
                            color: '#606060'
                        }
                    }
                }]
            },
            series: [{
                name: 'Room temperature (°C)',
                color: '#BF0B23',
                dashStyle: 'ShortDash',
                data: roomTemperatureHistory
            }, {
                name: 'Tank temperature (°C)',
                color: '#0066FF',
                dashStyle: 'ShortDash',
                data: tankTemperatureHistory
            }]
        });

        this.humidityChart = new Highcharts.Chart({
            chart: {
                renderTo: 'humidityHistoryChart',
                marginTop: 50,
                height: 320,
                defaultSeriesType: 'spline'
            },
            credits: {
                enabled: false
            },
            title: {
                text: ''
            },
            exporting: {
                enabled: false
            },
            legend: {
                verticalAlign: 'top'
            },
            scrollbar: {
                enabled: humidityHistory.length > 10
            },
            xAxis: {
                type: 'datetime',
                min: humidityHistory.length > 10 ? humidityHistory[humidityHistory.length - 11].x : null,
                max: humidityHistory.length > 10 ? humidityHistory[humidityHistory.length -1].x : null
            },
            yAxis: {
                minPadding: 0.5,
                maxPadding: 0.5,
                minRange: 5,
                max: 100,
                min: 0,
                title: {
                    text: I18n.valueInPercent,
                    margin: 25
                },
                plotBands: [{ // Low
                    from: 0,
                    to: 20,
                    color: 'rgba(191, 11, 35, 0.1)',
                    label: {
                        text: I18n.low,
                        style: {
                            color: '#606060'
                        }
                    }
                }, { // High
                    from: 50,
                    to: 100,
                    color: 'rgba(68, 170, 213, 0.1)',
                    label: {
                        text: I18n.high,
                        style: {
                            color: '#606060'
                        }
                    }
                }]
            },
            series: [{
                name: I18n.humidityPercent,
                color: '#44aad5',
                dashStyle: 'ShortDash',
                data: humidityHistory
            }]
        });
    }
});