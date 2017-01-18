/**
 * Created by eoto on 22/09/16.
 */
ES.WidgetWaterTests = ES.Widget.extend({
    init: function() {
        var me = this;
        me._super('widget-waterTests');

        //setInterval(function() {
        //    me.requestChartData()
        //}, 120000);

        this.phChart = new Highcharts.Chart({
            chart: {
                renderTo: 'phChart',
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
                enabled: phData.length > 10
            },
            xAxis: {
                type: 'datetime',
                min: phData.length > 10 ? phData[phData.length - 11].x : null,
                max: phData.length > 10 ? phData[phData.length -1].x : null
            },
            yAxis: {
                min: 1.0,
                max: 14.0,
                minPadding: 0,
                maxPadding: 0,
                minRange: 1,
                allowDecimals: true,
                tickInterval: 3.5,
                title: {
                    text: 'pH',
                    margin: 25
                },
                plotBands: [{ // Very acidic
                    from: 0,
                    to: 4,
                    color: 'rgba(191, 11, 35, 0.1)',
                    label: {
                        text: "Very acidic",
                        style: {
                            color: '#606060'
                        }
                    }
                },{ // Acidic
                    from: 4,
                    to: 6.5,
                    color: 'rgba(228, 127, 37, 0.1)',
                    label: {
                        text: "Acidic",
                        style: {
                            color: '#E47F25'
                        }
                    }
                },{ // Neutral
                    from: 6.5,
                    to: 7.5,
                    color: 'rgba(3, 156, 74, 0.1)',
                    label: {
                        text: "Neutral",
                        style: {
                            color: '#039C4A'
                        }
                    }
                },{ // Alkaline
                    from: 7.5,
                    to: 10,
                    color: 'rgba(0, 91, 162, 0.1)',
                    label: {
                        text: "Alkaline",
                        style: {
                            color: '#005BA2'
                        }
                    }
                },{ // Very alkaline
                    from: 10,
                    to: 14,
                    color: 'rgba(68, 170, 213, 0.1)',
                    label: {
                        text: "Very alcaline",
                        style: {
                            color: '#606060'
                        }
                    }
                }]
            },
            series: [{
                name: 'pH',
                color: '#BF0B23',
                dashStyle: 'ShortDash',
                data: phData
            }]
        });

        this.ammoniaChart = new Highcharts.Chart({
            chart: {
                renderTo: 'ammoniaChart',
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
                enabled: phData.length > 10
            },
            xAxis: {
                type: 'datetime',
                min: phData.length > 10 ? phData[phData.length - 11].x : null,
                max: phData.length > 10 ? phData[phData.length -1].x : null
            },
            yAxis: {
                min: 1.0,
                max: 8.0,
                minPadding: 0,
                maxPadding: 0,
                minRange: 1,
                allowDecimals: true,
                tickInterval: 2,
                title: {
                    text: 'Ammonia (NH3)',
                    margin: 25
                },
                plotBands: [,{ // Low
                    from: 0,
                    to: 2,
                    color: 'rgba(3, 156, 74, 0.1)',
                    label: {
                        text: "Low",
                        style: {
                            color: '#039C4A'
                        }
                    }
                },{ // Medium
                    from: 2,
                    to: 4,
                    color: 'rgba(228, 127, 37, 0.1)',
                    label: {
                        text: "Medium",
                        style: {
                            color: '#E47F25'
                        }
                    }
                },{ // High
                    from: 4,
                    to: 8,
                    color: 'rgba(191, 11, 35, 0.1)',
                    label: {
                        text: "High",
                        style: {
                            color: '#606060'
                        }
                    }
                }]
            },
            series: [{
                name: 'Ammonia (NH3)',
                color: '#E47F25',
                dashStyle: 'ShortDash',
                data: ammoniaData
            }]
        });

        this.nitriteChart = new Highcharts.Chart({
            chart: {
                renderTo: 'nitriteChart',
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
                enabled: phData.length > 10
            },
            xAxis: {
                type: 'datetime',
                min: phData.length > 10 ? phData[phData.length - 11].x : null,
                max: phData.length > 10 ? phData[phData.length -1].x : null
            },
            yAxis: {
                min: 0.0,
                max: 4.0,
                minPadding: 0,
                maxPadding: 0,
                minRange: 1,
                allowDecimals: true,
                tickInterval: 1,
                title: {
                    text: 'Nitrite (NO2)',
                    margin: 25
                },
                plotBands: [,{ // Low
                    from: 0,
                    to: 0.3,
                    color: 'rgba(3, 156, 74, 0.1)',
                    label: {
                        text: "Low",
                        style: {
                            color: '#039C4A'
                        }
                    }
                },{ // Medium
                    from: 0.3,
                    to: 0.8,
                    color: 'rgba(228, 127, 37, 0.1)',
                    label: {
                        text: "Medium",
                        style: {
                            color: '#E47F25'
                        }
                    }
                },{ // High
                    from: 0.8,
                    to: 4,
                    color: 'rgba(191, 11, 35, 0.1)',
                    label: {
                        text: "High",
                        style: {
                            color: '#606060'
                        }
                    }
                }]
            },
            series: [{
                name: 'Nitrite (NO2)',
                color: '#FF69B4',
                dashStyle: 'ShortDash',
                data: nitriteData
            }]
        });

        this.nitrateChart = new Highcharts.Chart({
            chart: {
                renderTo: 'nitrateChart',
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
                enabled: phData.length > 10
            },
            xAxis: {
                type: 'datetime',
                min: phData.length > 10 ? phData[phData.length - 11].x : null,
                max: phData.length > 10 ? phData[phData.length -1].x : null
            },
            yAxis: {
                min: 0.0,
                max: 110.0,
                minPadding: 0,
                maxPadding: 0,
                minRange: 1,
                allowDecimals: true,
                tickInterval: 10,
                title: {
                    text: 'Nitrate (NO3)',
                    margin: 25
                },
                plotBands: [,{ // Low
                    from: 0,
                    to: 20,
                    color: 'rgba(3, 156, 74, 0.1)',
                    label: {
                        text: "Low",
                        style: {
                            color: '#039C4A'
                        }
                    }
                },{ // Medium
                    from: 20,
                    to: 50,
                    color: 'rgba(228, 127, 37, 0.1)',
                    label: {
                        text: "Medium",
                        style: {
                            color: '#E47F25'
                        }
                    }
                },{ // High
                    from: 50,
                    to: 110,
                    color: 'rgba(191, 11, 35, 0.1)',
                    label: {
                        text: "High",
                        style: {
                            color: '#BF0B23'
                        }
                    }
                }]
            },
            series: [{
                name: 'Nitrate (NO3)',
                color: '#440067',
                dashStyle: 'ShortDash',
                data: nitrateData
            }]
        });
    },
    requestChartData: function() {
        var me = this;
        $.ajax({
            url: BASE_URL + 'ajax/chartLiveData/' + ES.getCurrentInstanceId(),
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