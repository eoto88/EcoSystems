var History = Class.extend({
    init: function() {
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

        this.sunlightHistoryChart = new Highcharts.Chart({
            chart: {
                renderTo: 'sunlightHistoryChart',
                marginTop: 50,
                height: 320,
                defaultSeriesType: 'spline'
            },
            title: {
                text: 'Sunlight history'
            },
            xAxis: {
                type: 'datetime',
                dateTimeLabelFormats: {
                    day: '%e %b'
                }
            },
            yAxis: {
                minPadding: 0.5,
                maxPadding: 0.5,
                minRange: 5,
                max: 15,
                min: 0,
                title: {
                    text: 'Value',
                    margin: 25
                },
                plotBands: [{// Night
                    from: 0,
                    to: 7,
                    color: 'rgba(0, 0, 0, 0.1)',
                    label: {
                        text: 'Winter',
                        style: {
                            color: '#606060'
                        }
                    }
                }, {// Day
                    from: 7,
                    to: 15,
                    color: 'rgba(255, 255, 0, 0.1)',
                    label: {
                        text: 'Summer',
                        style: {
                            color: '#606060'
                        }
                    }
                }]
            },
            series: [{
                name: 'Sunlight (%)',
                color: '#FFFF00',
                dashStyle: 'ShortDash',
                data: sunlightHistory
            }]
        });
    }
});

$(document).ready(function() {
    if( $("#temperatureHistoryChart").length == 1 && $("#sunlightHistoryChart").length == 1 ) {
        var history = new History();
    }
});