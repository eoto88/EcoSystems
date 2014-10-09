
var temperatureHistoryChart;

$(document).ready(function() {
    if ($("#temperatureHistoryChart").length == 1) {
        temperatureHistoryChart = new Highcharts.Chart({
            chart: {
                renderTo: 'temperatureHistoryChart',
                marginTop: 50,
                height: 320,
                defaultSeriesType: 'spline',
            },
            title: {
                text: 'Temperature'
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

        /*sunlightChart = new Highcharts.Chart({
         chart: {
         renderTo: 'sunlightChart',
         marginTop: 50,
         height: 320,
         defaultSeriesType: 'spline'
         },
         title: {
         text: 'Sunlight'
         },
         xAxis: {
         type: 'datetime',
         maxZoom: 20 * 1000
         },
         yAxis: {
         minPadding: 0.5,
         maxPadding: 0.5,
         minRange: 5,
         max: 100,
         min: 0,
         title: {
         text: 'Value',
         margin: 25
         },
         plotBands: [{ // Night
         from: 0,
         to: 40,
         color: 'rgba(0, 0, 0, 0.1)',
         label: {
         text: 'Night',
         style: {
         color: '#606060'
         }
         }
         }, { // Day
         from: 40,
         to: 100,
         color: 'rgba(255, 255, 0, 0.1)',
         label: {
         text: 'Day',
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
         data: sunlightData
         }]
         });*/

        //setTimeout(requestChartData, 120000);
    }

    /*setInterval(function() {
        $.ajax({
            url: BASE_URL + "ajax/getLiveData",
            cache: false,
            dataType: "json"
        }).done(function(data) {
            var status;
            if (data.stillAliveStatus == "still-alive")
                status = '<i class="fa fa-check success"></i>';
            else
                status = '<i class="fa fa-exclamation-triangle error"></i>';
            $("#still_alive").html(status).attr("title", "Last communication: " + data.lastCommunication);

            changeStatus('pump', 'Pump', data.pumpStatus);
            changeStatus('light', 'Light', data.lightStatus);
            changeStatus('fan', 'Fan', data.fanStatus);
            changeStatus('heater', 'Heater', data.heaterStatus);
        });
    }, 15000);*/

    $("#tasks_list li").click(function() {
        var $todo = $(this);
        $todo.find('.check').addClass('done');
        var id = $todo.attr('id');
        id = id.replace("todo-", "");
        $.ajax({
            url: BASE_URL + "ajax/updateToDo/" + id,
            cache: false,
            dataType: "json"
        }).done(function(data) {
            $("#todo-" + data.id).animate({'height': 0, 'opacity': 0}, 500, function() {
                $(this).remove();
                if ($("#tasks_list li").length === 0) {
                    $("#tasks_list").html('<li id="no-todo">No task in the to do list</li>');
                }
            });
        });
    });
});

function changeStatus(relayId, relayName, status) {
    $("#" + relayId + "_status").attr('title', relayName + ' is ' + status);
    if (status === "on") {
        $("#" + relayId + "_status").find('.status-icon').addClass(relayId + '-on');
    } else {
        $("#" + relayId + "_status").find('.status-icon').removeClass(relayId + '-on');
    }
}

function requestChartData() {
    $.ajax({
        url: 'ajax/chartLiveData',
        cache: false,
        dataType: "json",
        success: function(point) {
            console.debug(point);
            if (point.roomTemperature.length > 0 &&
                    temperatureChart.series[0].data.length > 0 &&
                    temperatureChart.series[0].data[temperatureChart.series[0].data.length - 1].x != point.roomTemperature[0]) {
                var series = temperatureChart.series[0],
                        shift = series.data.length > 40;

                temperatureChart.series[0].addPoint(eval(point.roomTemperature), true, shift);
            }

            if (point.tankTemperature.length > 0 &&
                    temperatureChart.series[1].data.length > 0 &&
                    temperatureChart.series[1].data[temperatureChart.series[1].data.length - 1].x != point.tankTemperature[0]) {
                var series = temperatureChart.series[1],
                        shift = series.data.length > 40;

                temperatureChart.series[1].addPoint(eval(point.tankTemperature), true, shift);
            }

            if (point.sunlight.length > 0 &&
                    sunlightChart.series[0].data.length > 0 &&
                    sunlightChart.series[0].data[sunlightChart.series[0].data.length - 1].x != point.sunlight[0]) {
                var series = sunlightChart.series[0],
                        shift = series.data.length > 80;

                sunlightChart.series[0].addPoint(eval(point.sunlight), true, shift);
            }

            setTimeout(requestChartData, 120000);
        }
    });
}