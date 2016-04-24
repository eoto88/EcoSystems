App.WidgetLive = App.Widget.extend({
    init: function() {
        var me = this;
        me._super( 'widget-live' );

        setInterval(function() {
            me.requestChartData()
        }, 120000);

        this.temperatureChart = new Highcharts.Chart({
            chart: {
                renderTo: 'temperatureChart',
                marginTop: 50,
                height: 320,
                defaultSeriesType: 'spline'
            },  credits: {
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
                enabled: roomTemperatureData.length > 10
            },
            xAxis: {
                type: 'datetime',
                min: roomTemperatureData.length > 10 ? roomTemperatureData[roomTemperatureData.length - 11].x : null,
                max: roomTemperatureData.length > 10 ? roomTemperatureData[roomTemperatureData.length -1].x : null
            },
            yAxis: {
                minPadding: 0.2,
                maxPadding: 0.2,
                minRange: 0.5,
                title: {
                    text: I18n.valueInCelsius,
                    margin: 25
                },
                plotBands: [{ // Cold
                    from: 0,
                    to: 18,
                    color: 'rgba(68, 170, 213, 0.1)',
                    label: {
                        text: I18n.cold,
                        style: {
                            color: '#606060'
                        }
                    }
                }, { // Hot
                    from: 35,
                    to: 60,
                    color: 'rgba(191, 11, 35, 0.1)',
                    label: {
                        text: I18n.hot,
                        style: {
                            color: '#606060'
                        }
                    }
                }]
            },
            series: [{
                name: I18n.roomTemperature + ' (°C)',
                color: '#BF0B23',
                dashStyle: 'ShortDash',
                data: roomTemperatureData
            },{
                name: I18n.tankTemperature + '(°C)',
                color: '#0066FF',
                dashStyle: 'ShortDash',
                data: tankTemperatureData
            }]
        });

        this.humidityChart = new Highcharts.Chart({
            chart: {
                renderTo: 'humidityChart',
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
                enabled: humidityData.length > 10
            },
            xAxis: {
                type: 'datetime',
                min: humidityData.length > 10 ? humidityData[humidityData.length - 11].x : null,
                max: humidityData.length > 10 ? humidityData[humidityData.length -1].x : null
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
                data: humidityData
            }]
        });
    },
    requestChartData: function() {
        var me = this;
        $.ajax({
            url: BASE_URL + 'ajax/chartLiveData/' + getCurrentInstanceId(),
            cache: false,
            dataType: "json",
            success: function(point) {
                /*if(point.roomTemperature.length > 0 &&
                 temperatureChart.series[0].data.length > 0 &&
                 temperatureChart.series[0].data[temperatureChart.series[0].data.length-1].x != point.roomTemperature[0]) {
                 var series = temperatureChart.series[0],
                 shift = series.data.length > 40;

                 temperatureChart.series[0].addPoint(eval(point.roomTemperature), true, shift);
                 }*/

                me.addChartData(point.roomTemperature, me.temperatureChart.series[0]);

                /*if(point.tankTemperature.length > 0 &&
                 temperatureChart.series[1].data.length > 0 &&
                 temperatureChart.series[1].data[temperatureChart.series[1].data.length-1].x != point.tankTemperature[0]) {
                 var series = temperatureChart.series[1],
                 shift = series.data.length > 40;

                 temperatureChart.series[1].addPoint(eval(point.tankTemperature), true, shift);
                 }*/

                me.addChartData(point.tankTemperature, me.temperatureChart.series[1]);
            }
        });
    },
    addChartData: function(data, chartSerie) {
        if(data.length > 0 &&
            chartSerie.data.length > 0 &&
            chartSerie.data[chartSerie.data.length-1].x != data[0]) {
            var shift = chartSerie.data.length > 80;

            chartSerie.addPoint(eval(data), true, shift);
        }
    }
});