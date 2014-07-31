
var temperatureChart; // global
var sunlightChart;

$(document).ready(function() {
    temperatureChart = new Highcharts.Chart({
        chart: {
            renderTo: 'temperatureChart',
            marginTop: 50,
            height: 400,
            defaultSeriesType: 'spline',
        },
        title: {
            text: 'Temperature'
        },
        xAxis: {
            type: 'datetime',
            tickPixelInterval: 150,
            maxZoom: 20 * 1000
        },
        yAxis: {
            minPadding: 0.5,
            maxPadding: 0.5,
            minRange: 5,
            title: {
                text: 'Value',
                margin: 25
            },
            plotBands: [{ // Cold
                from: 0,
                to: 17,
                color: 'rgba(68, 170, 213, 0.1)',
                label: {
                    text: 'Cold',
                    style: {
                        color: '#606060'
                    }
                }
            }, { // Hot
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
            name: 'Temperature (°C)',
            color: '#BF0B23',
            dashStyle: 'ShortDash',
            data: temperatureData
        }/*,{
         name: 'Sunlight (%)',
         color: '#0066FF',
         dashStyle: 'ShortDash',
         data: sunlightData
         }*/]
    });

    sunlightChart = new Highcharts.Chart({
        chart: {
            renderTo: 'sunlightChart',
            marginTop: 50,
            height: 400,
            defaultSeriesType: 'spline',
        },
        title: {
            text: 'Sunlight'
        },
        xAxis: {
            type: 'datetime',
            tickPixelInterval: 150,
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
    });

    setTimeout(requestData, 120000);

    setInterval(function() {
        var data = {};
        data.action = "still-alive";
        $.ajax({
            url: "ajax.php",
            type: "POST",
            data: data,
            cache: false,
            dataType: "json",
        }).done(function(data) {
            var status;
            if(data.status == "still-alive")
                status = '<i class="fa fa-check success"></i>';
            else
                status = '<i class="fa fa-exclamation-triangle error"></i>';
            $("#still_alive").html(status).attr("title", "Last communication: " + data.last_answer);
        });
    }, 30000);
});

function requestData() {
    var data = {};
    data.action = 'chartsData';
    $.ajax({
        url: 'ajax.php',
        type: "POST",
        data: data,
        success: function(point) {
            if(temperatureChart.series[0].data[temperatureChart.series[0].data.length-1].x != point.temperatures[0]) {
                var series = temperatureChart.series[0],
                    shift = series.data.length > 25; // shift if the series is longer than 20

                // add the point
                temperatureChart.series[0].addPoint(eval(point.temperatures), true, shift);
            }

            /*if(temperatureChart.series[1].data[temperatureChart.series[1].data.length-1].x != point.sunlights[0]) {
             var series = temperatureChart.series[1],
             shift = series.data.length > 25; // shift if the series is longer than 20

             // add the point
             temperatureChart.series[1].addPoint(eval(point.sunlights), true, shift);
             }*/

            if(sunlightChart.series[0].data[sunlightChart.series[0].data.length-1].x != point.sunlights[0]) {
                var series = sunlightChart.series[0],
                    shift = series.data.length > 25; // shift if the series is longer than 20

                // add the point
                sunlightChart.series[0].addPoint(eval(point.sunlights), true, shift);
            }

            setTimeout(requestData, 120000);
        },
        cache: false
    });
}