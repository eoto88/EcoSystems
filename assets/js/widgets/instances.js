App.WidgetInstances = App.Widget.extend({
    // TODO Improve this
    config: {
        collapsible: true,
        refreshable: true
    },

    init: function() {
        var me = this;
        me._super( 'widget-instances' );

        me.registerHelpers();

        me.createInstances();

        setInterval(function() {
            me.refreshInstances();
        }, 30000);
    },

    createInstances: function() {
        var me = this,
            $component = $('#'+ me.cssId),
            id_instance = 0;

        if( ES.isInt($component.data('single-instance')) ) {
            id_instance = $component.data('single-instance');
        }

        me.startLoading();

        me.ajax({
            url: BASE_URL + "api/instances/" + (id_instance > 0 ? id_instance : ""),
            success: function(data) {
                $component.find('.widget-body').append('<ul></ul>');

                $.each(data, function(key, instanceData) {
                    me.compileTpl({
                        tplId: 'instance-tmpl',
                        appendTo: $component.find('.widget-body ul'),
                        data: instanceData
                    });

                    var $instance = $('#'+ me.cssId).find('li[data-id='+ instanceData.id +']');

                    $instance.popover({
                        selector: '[data-toggle=popover]',
                        container: 'body',
                        placement: 'top'
                    });

                    me.onClick($instance.find('.instance-expand'), me.onInstanceExpandClick, instanceData);
                });

                me.stopLoading();
            }
        });
    },

    refresh: function() {
        // TODO Improve this...
        this.refreshInstances();
    },

    onInstanceExpandClick: function(event, element) {
        var me = event.data.context,
            deferredCalls = [],
            instanceData = event.data.entityData,
            instanceBodyData = {},
            $instance = $('#'+ me.cssId).find('li[data-id='+ instanceData.id +']'),
            $instanceBody = $instance.find('.instance-body');

        $instanceBody.slideToggle();
        $(this).find('i').toggleClass('fa-chevron-circle-down').toggleClass('fa-chevron-circle-up');

        if($instanceBody.html() == "") {
            // TODO instance.type

            if(instanceData.monitored == "1") {
                deferredCalls.push(
                    me.ajax({
                        url: BASE_URL + "api/instances/"+ instanceData.id +"/data/"
                    })
                );
            }

            if(instanceData.water_tests == "1") {
                deferredCalls.push(
                    me.ajax({
                        url: BASE_URL + "api/instances/" + instanceData.id + "/WaterTests/"
                    })
                );
            }

            if(deferredCalls.length) {
                me.asyncCalls({
                    calls: deferredCalls,
                    callback: function() {
                        if (arguments.length) {
                            for (var i = 0; i < arguments.length; i++) {
                                var entityName = arguments[i][0].entityName;

                                if (entityName == 'data') {
                                    instanceBodyData.data = arguments[i][0].entities;
                                }

                                if (entityName == 'waterTest') {
                                    instanceBodyData.waterTest = arguments[i][0].entities;
                                }
                            }

                            me.compileTpl({
                                tplId: 'instance-body-tmpl',
                                appendTo: $instance.find('.instance-body'),
                                empty: true,
                                data: instanceBodyData
                            });

                            // TODO datetime

                            if(instanceBodyData.data.humidity) {
                                me.createHumidityGage(instanceBodyData.data.humidity);
                            } else {
                                // TODO N/A
                            }

                            if(instanceBodyData.data.room_temperature) {
                                me.createRoomTemperatureGage(instanceBodyData.data.room_temperature);
                            } else {
                                // TODO N/A
                            }

                            if(instanceBodyData.data.tank_temperature) {
                                me.createTankTemperatureGage(instanceBodyData.data.tank_temperature);
                            } else {
                                // TODO N/A
                            }

                            instanceBodyData.waterTest = {};
                            instanceBodyData.waterTest.ph = 9.0;
                            if(instanceBodyData.waterTest.ph) {
                                me.createPhGage(instanceBodyData.waterTest.ph);
                            } else {
                                // TODO N/A
                            }

                            instanceBodyData.waterTest.ammonia = 9.0;
                            if(instanceBodyData.waterTest.ammonia) {
                                me.createAmmoniaGage(instanceBodyData.waterTest.ammonia);
                            } else {
                                // TODO N/A
                            }

                            instanceBodyData.waterTest.nitrite = 9.0;
                            if(instanceBodyData.waterTest.nitrite) {
                                me.createNitriteGage(instanceBodyData.waterTest.nitrite);
                            } else {
                                // TODO N/A
                            }

                            instanceBodyData.waterTest.nitrate = 9.0;
                            if(instanceBodyData.waterTest.nitrate) {
                                me.createNitrateGage(instanceBodyData.waterTest.nitrate);
                            } else {
                                // TODO N/A
                            }

                        } else {
                            // TODO What to do here?
                            // <debug>
                            console.warn('no arguments...')
                            // </debug>
                        }
                    }
                });
            } else {
                // TODO No monitoring or water tests
            }
        } else {
            $instanceBody.empty();
        }
    },

    createGage: function(params) {
        return new JustGage({
            id: params.id,
            //title: params.title,
            value: params.value,
            min: params.minVal,
            max: params.maxVal,
            decimals: params.decimals,
            symbol: params.symbol,
            valueMinFontSize: 32,
            valueFontColor: '#333',
            valueFontFamily: "Open Sans",
            minLabelMinFontSize: 14,
            maxLabelMinFontSize: 14,
            gaugeWidthScale: 0.2,
            relativeGaugeSize: true,
            pointer: true,
            pointerOptions: {
                toplength: -5,
                bottomlength: 8,
                bottomwidth: 4,
                color: '#8e8e93'
            },
            levelColors: params.levelColors,
            customSectors: params.customSectors,
            counter: true
        });
    },

    createHumidityGage: function(value) {
        var me = this;

        return me.createGage({
            id: "humidityGage",
            value: value,
            minVal: 0,
            maxVal: 100,
            decimals: 1,
            symbol: ' %',
            levelColors : [  "#e74c3c", "#2ecc71", "#e74c3c" ],
            customSectors: {
                percents: true,
                ranges: [{
                    color : "#e74c3c",
                    lo : 0,
                    hi : 50
                },{
                    color : "#2ecc71",
                    lo : 51,
                    hi : 60
                },{
                    color : "#e74c3c",
                    lo : 61,
                    hi : 50
                }]
            }
        });
    },

    createRoomTemperatureGage: function(value) {
        var me = this;

        return me.createGage({
            id: "roomTemperatureGage",
            value: value,
            minVal: 0,
            maxVal: 50,
            decimals: 1,
            symbol: ' °C',
            levelColors : [  "#e74c3c", "#2ecc71", "#e74c3c" ],
            customSectors: {
                ranges: [{
                    color : "#e74c3c",
                    lo : 0,
                    hi : 13
                },{
                    color : "#2ecc71",
                    lo : 24,
                    hi : 26
                },{
                    color : "#e74c3c",
                    lo : 27,
                    hi : 50
                }]
            }
        });
    },

    createTankTemperatureGage: function(value) {
        var me = this;

        return me.createGage({
            id: "tankTemperatureGage",
            value: value,
            minVal: 0,
            maxVal: 50,
            decimals: 1,
            symbol: ' °C',
            levelColors : [  "#e74c3c", "#2ecc71", "#e74c3c" ],
            customSectors: {
                ranges: [{
                    color : "#e74c3c",
                    lo : 0,
                    hi : 14
                },{
                    color : "#2ecc71",
                    lo : 25,
                    hi : 27
                },{
                    color : "#e74c3c",
                    lo : 28,
                    hi : 50
                }]
            }
        });
    },

    createPhGage: function(value) {
        var me = this;

        me.createGage({
            id: "phGage",
            value: value,
            minVal: 0,
            maxVal: 14,
            decimals: 1,
            symbol: '',
            levelColors : [  "#e74c3c", "#e74c3c", "#2ecc71", "#e74c3c", "#e74c3c" ],
            customSectors: {
                ranges: [{
                    color : "#e74c3c",
                    lo : 0,
                    hi : 4.9
                },{
                    color : "#e74c3c",
                    lo : 5,
                    hi : 6.4
                },{
                    color : "#2ecc71",
                    lo : 6.5,
                    hi : 7.4
                },{
                    color : "#e74c3c",
                    lo : 7.5,
                    hi : 8.5
                },{
                    color : "#e74c3c",
                    lo : 8.6,
                    hi : 14
                }]
            }
        });
    },

    createAmmoniaGage: function(value) {
        var me = this;

        return me.createGage({
            id: "ammoniaGage",
            value: value,
            minVal: 0,
            maxVal: 14,
            decimals: 1,
            symbol: '',
            levelColors : [  "#e74c3c", "#e74c3c", "#2ecc71", "#e74c3c", "#e74c3c" ],
            customSectors: {
                ranges: [{
                    color : "#e74c3c",
                    lo : 0,
                    hi : 4.9
                },{
                    color : "#e74c3c",
                    lo : 5,
                    hi : 6.4
                },{
                    color : "#2ecc71",
                    lo : 6.5,
                    hi : 7.4
                },{
                    color : "#e74c3c",
                    lo : 7.5,
                    hi : 8.5
                },{
                    color : "#e74c3c",
                    lo : 8.6,
                    hi : 14
                }]
            }
        });
    },

    createNitriteGage: function(value) {
        var me = this;

        return me.createGage({
            id: "nitriteGage",
            value: value,
            minVal: 0,
            maxVal: 14,
            decimals: 1,
            symbol: '',
            levelColors : [  "#e74c3c", "#e74c3c", "#2ecc71", "#e74c3c", "#e74c3c" ],
            customSectors: {
                ranges: [{
                    color : "#e74c3c",
                    lo : 0,
                    hi : 4.9
                },{
                    color : "#e74c3c",
                    lo : 5,
                    hi : 6.4
                },{
                    color : "#2ecc71",
                    lo : 6.5,
                    hi : 7.4
                },{
                    color : "#e74c3c",
                    lo : 7.5,
                    hi : 8.5
                },{
                    color : "#e74c3c",
                    lo : 8.6,
                    hi : 14
                }]
            }
        });
    },

    createNitrateGage: function(value) {
        var me = this;

        return me.createGage({
            id: "nitrateGage",
            value: value,
            minVal: 0,
            maxVal: 14,
            decimals: 1,
            symbol: '',
            levelColors : [  "#e74c3c", "#e74c3c", "#2ecc71", "#e74c3c", "#e74c3c" ],
            customSectors: {
                ranges: [{
                    color : "#e74c3c",
                    lo : 0,
                    hi : 4.9
                },{
                    color : "#e74c3c",
                    lo : 5,
                    hi : 6.4
                },{
                    color : "#2ecc71",
                    lo : 6.5,
                    hi : 7.4
                },{
                    color : "#e74c3c",
                    lo : 7.5,
                    hi : 8.5
                },{
                    color : "#e74c3c",
                    lo : 8.6,
                    hi : 14
                }]
            }
        });
    },

    refreshInstances: function() {
        var me = this;
        me.ajax({
            url: BASE_URL + "api/instances/",
            success: function(data) {
                $.each(data, function(key, instanceData) {
                    var $instance = $('#'+ me.cssId +' li[data-id='+ instanceData.id +']'),
                        $heartbeatSwitch = $instance.find('.heartbeat'),
                        $lightSwitch = $instance.find('.light-status'),
                        $pumpSwitch = $instance.find('.pump-status'),
                        $fanSwitch = $instance.find('.fan-status');

                    me.updateSwitch($heartbeatSwitch, instanceData.heartbeat);

                    me.updateSwitch($lightSwitch, instanceData.light_on);
                    me.updateSwitch($pumpSwitch, instanceData.pump_on);
                    me.updateSwitch($fanSwitch, instanceData.fan_on);
                });
            }
        });
    },

    updateSwitch: function($switch, onOffStatus) {
        if(onOffStatus == "1") {
            if($switch.find('.switch-wrapper').hasClass('icon-switch-off')) {
                $switch.find('.switch-wrapper').toggleClass('icon-switch-on').toggleClass('icon-switch-off');
            }
        } else {
            if($switch.find('.switch-wrapper').hasClass('icon-switch-on')) {
                $switch.find('.switch-wrapper').toggleClass('icon-switch-on').toggleClass('icon-switch-off');
            }
        }
    },

    registerHelpers: function() {
        var me = this;

        Handlebars.registerHelper('iconSwitch', function(onOffStatus, cls) {
            var onIconCls,
                offIconCls,
                title,
                content = 'Last communication: ' + this.last_communication;

            if(cls == 'heartbeat-status') {
                onIconCls = 'fa fa-check success';
                offIconCls = 'fa fa-exclamation-triangle error';
                title = 'Heartbeat status';
            } else if(cls == 'light-status') {
                onIconCls = offIconCls = 'fa fa-lightbulb-o';
                title = 'Light status';
            } else if(cls == 'pump-status') {
                onIconCls = offIconCls = 'fa fa-tint';
                title = 'Pump status';
            } else if(cls == 'fan-status') {
                onIconCls = offIconCls = 'wi wi-cloudy-gusts';
                title = 'Fan status';
            }

            var tpl = Handlebars.compile($("#icon-switch").html());

            return tpl({
                iconCls: cls,
                title: title,
                content: content,
                onOffStatus: (onOffStatus == "1" ? 'on' : 'off'),
                onIconCls: onIconCls,
                offIconCls: offIconCls
            });
        });

        Handlebars.registerHelper('dataStatus', function() {
            var title = "Time: "+ this.data.datetime,
                roomTemperature = (this.data.room_temperature ? this.data.room_temperature : '<i class="wi wi-na"></i>') +' °C',
                tankTemperature = (this.data.tank_temperature ? this.data.tank_temperature : '<i class="wi wi-na"></i>') +' °C',
                humidity = (this.data.humidity ? this.data.humidity : '<i class="wi wi-na"></i>') +' %';

            humidity = '<div class="live-status" title="'+ title +'"><span class="live-label">Humidity</span><span class="live-value humidity"><i class="wi wi-humidity"></i>'+ humidity +'</span></div>';
            roomTemperature = '<div class="live-status" title="'+ title +'"><span class="live-label">Room temperature</span><span class="live-value room-temperature"><i class="wi wi-thermometer"></i>'+ roomTemperature +'</span></div>';
            tankTemperature = '<div class="live-status" title="'+ title +'"><span class="live-label">Tank temperature</span><span class="live-value tank-temperature"><i class="wi wi-thermometer"></i>'+ tankTemperature +'</span></div>';

            return humidity + roomTemperature + tankTemperature;
        });

        Handlebars.registerHelper('waterTestStatus', function() {
            var title = (this.waterTest.date ? "Date: "+ this.waterTest.date : ''),
                ph = (this.waterTest.ph ? this.waterTest.ph : '<i class="wi wi-na"></i>'),
                phClasses = 'ph',
                ammonia = (this.waterTest.ammonia ? this.waterTest.ammonia : '<i class="wi wi-na"></i>') +' ppm',
                ammoniaClasses = 'ammonia',
                nitrite = (this.waterTest.nitrite ? this.waterTest.nitrite : '<i class="wi wi-na"></i>') +' ppm',
                nitriteClasses = 'nitrite',
                nitrate = (this.waterTest.nitrite ? this.waterTest.nitrite : '<i class="wi wi-na"></i>') +' ppm',
                nitrateClasses = 'nitrate';


            if(this.waterTest.ph > 10.0) {
                phClasses +=' very-alkaline';
            } else if (this.waterTest.ph > 7.5) {
                phClasses += ' alkaline';
            } else if(this.waterTest.ph > 6.5) {
                phClasses += ' neutral';
            } else if(this.waterTest.ph > 4.0) {
                phClasses +=' acidic';
            } else {
                phClasses += ' very-acidic';
            }

            if(this.waterTest.ammonia > 4.0) {
                ammoniaClasses +=' high-danger';
            } else if(this.waterTest.ammonia > 2.0) {
                ammoniaClasses += ' medium-danger';
            } else {
                ammoniaClasses +=' low-danger';
            }

            if(this.waterTest.nitrite > 0.8) {
                nitriteClasses +=' high-danger';
            } else if(this.waterTest.nitrite > 0.3) {
                nitriteClasses +=' medium-danger';
            } else {
                nitriteClasses +=' low-danger';
            }

            if(this.waterTest.nitrate > 50.0) {
                nitrateClasses +=' high-danger';
            } else if(this.waterTest.nitrate > 20.0) {
                nitrateClasses +=' medium-danger';
            } else {
                nitrateClasses +=' low-danger';
            }

            ph = '<div class="live-status" title="'+ title +'"><span class="live-label">pH</span><span class="live-value '+ phClasses +'"><i class="fa fa-flask"></i>'+ ph +'</span></div>';
            ammonia = '<div class="live-status" title="'+ title +'"><span class="live-label">Ammonia (NH<sub>3</sub>)</span><span class="live-value '+ ammoniaClasses +'"><i class="fa fa-flask"></i>'+ ammonia +'</span></div>';
            nitrite = '<div class="live-status" title="'+ title +'"><span class="live-label">Nitrite (NO<sub>2</sub>)</span><span class="live-value '+ nitriteClasses +'"><i class="fa fa-flask"></i>'+ nitrite +'</span></div>';
            nitrate = '<div class="live-status" title="'+ title +'"><span class="live-label">Nitrate (NO<sub>3</sub>)</span><span class="live-value '+ nitrateClasses +'"><i class="fa fa-flask"></i>'+ nitrate +'</span></div>';

            return ph + ammonia + nitrite + nitrate;
        });
    }
});