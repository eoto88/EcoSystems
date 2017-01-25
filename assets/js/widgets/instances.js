ES.WidgetInstances = ES.Widget.extend({
    // TODO Improve this
    config: {
        collapsible: true,
        refreshable: true
    },

    init: function() {
        var me = this;

        me._super( 'widget-instances' );

        me.gagesCount = 0;
        me.gages = [];

        me.registerHelpers();

        me.createInstances();

        setInterval(function() {
            me.refresh(false);
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

        ES.ajax({
            url: BASE_URL + "api/instances/" + (id_instance > 0 ? id_instance : "") + "?params=true&header=true",
            success: function(data) {
                $component.find('.widget-body').append('<ul></ul>');

                $.each(data, function(key, instanceData) {
                    instanceData.params = me.parseData(instanceData.params);
                    me.compileTpl({
                        tplId: 'instance-tmpl',
                        appendTo: $component.find('.widget-body ul'),
                        data: instanceData
                    });

                    var $instance = $('#'+ me.cssId).find('li[data-id='+ instanceData.id +']');

                    $instance.popover({
                        selector: '[data-toggle=popover]',
                        html: true,
                        container: 'body',
                        placement: 'top'
                    });

                    me.onClick($instance.find('.instance-expand'), me.onInstanceExpandClick, instanceData);
                });

                me.stopLoading();
            }
        });
    },

    parseData: function(params) {
        $.each(params, function(key, param) {
            param.data = ES.parseData(param.data);
        });
        return params;
    },

    refresh: function(loading) {
        var me = this,
            loading = (loading == undefined || !loading ? true : false);

        if(loading) {
            me.startLoading();
        }

        ES.ajax({
            url: BASE_URL + "api/instances/",
            success: function(data) {
                $.each(data, function(key, instanceData) {
                    var $instance = $('#'+ me.cssId +' li[data-id='+ instanceData.id +']'),
                        $instanceBody = $instance.find('.instance-body'),
                        $heartbeatSwitch = $instance.find('.heartbeat-status'),
                        $lightSwitch = $instance.find('.light-status'),
                        $pumpSwitch = $instance.find('.pump-status'),
                        $fanSwitch = $instance.find('.fan-status');

                    me.updateSwitch($heartbeatSwitch, instanceData.heartbeat, instanceData.last_communication);
                    me.updateSwitch($lightSwitch, instanceData.light_on, instanceData.last_communication);
                    me.updateSwitch($pumpSwitch, instanceData.pump_on, instanceData.last_communication);
                    me.updateSwitch($fanSwitch, instanceData.fan_on, instanceData.last_communication);

                    if($instanceBody.html() != "") {
                        var deferredCalls = [],
                            instanceBodyData = {};

                        if(instanceData.monitored == "1") {
                            deferredCalls.push(
                                ES.ajax({
                                    url: BASE_URL + "api/instances/"+ instanceData.id +"/data/"
                                })
                            );
                        }

                        if(instanceData.water_tests == "1") {
                            deferredCalls.push(
                                ES.ajax({
                                    url: BASE_URL + "api/instances/" + instanceData.id + "/WaterTests/"
                                })
                            );
                        }

                        // .statusGage

                        if(deferredCalls.length) {
                            me.asyncCalls({
                                calls: deferredCalls,
                                callback: function() {
                                    for (var i = 0; i < arguments.length; i++) {
                                        var entityName = arguments[i][0].entityName;

                                        if (entityName == 'data') {
                                            instanceBodyData.data = arguments[i][0].entities;
                                        }

                                        if (entityName == 'waterTest') {
                                            instanceBodyData.waterTest = arguments[i][0].entities;
                                        }
                                    }

                                    // TODO datetime

                                    if(instanceBodyData.data.room_temperature) {
                                        var gage = me.getGage($instanceBody.find('.statusGage .roomTemperature').attr('id'));
                                        if(gage.config.value != instanceBodyData.data.room_temperature) {
                                            gage.refresh(instanceBodyData.data.room_temperature);
                                        }
                                    } else {
                                        // TODO N/A
                                    }

                                    if(instanceBodyData.data.tank_temperature) {
                                        var gage = me.getGage($instanceBody.find('.statusGage .tankTemperature').attr('id'));
                                        if(gage.config.value != instanceBodyData.data.tank_temperature) {
                                            gage.refresh(instanceBodyData.data.tank_temperature);
                                        }
                                    } else {
                                        // TODO N/A
                                    }

                                    if(instanceBodyData.data.humidity) {
                                        var gage = me.getGage($instanceBody.find('.statusGage .humidity').attr('id'));
                                        if(gage.config.value != instanceBodyData.data.humidity) {
                                            gage.refresh(instanceBodyData.data.humidity);
                                        }
                                    } else {
                                        // TODO N/A
                                    }

                                    instanceBodyData.data.water_level = 70;
                                    if(instanceBodyData.data.water_level) {
                                        var gage = me.getGage($instanceBody.find('.statusGage .waterLevel').attr('id'));
                                        if(gage.config.value != instanceBodyData.data.water_level) {
                                            gage.refresh(instanceBodyData.data.water_level);
                                        }
                                    } else {
                                        // TODO N/A
                                    }

                                    if(instanceBodyData.waterTest.ph) {
                                        var gage = me.getGage($instanceBody.find('.statusGage .ph').attr('id'));
                                        if(gage.config.value != instanceBodyData.waterTest.ph) {
                                            gage.refresh(instanceBodyData.waterTest.ph);
                                        }
                                    } else {
                                        // TODO N/A
                                    }

                                    if(instanceBodyData.waterTest.ammonia) {
                                        var gage = me.getGage($instanceBody.find('.statusGage .ammonia').attr('id'));
                                        if(gage.config.value != instanceBodyData.waterTest.ammonia) {
                                            gage.refresh(instanceBodyData.waterTest.ammonia);
                                        }
                                    } else {
                                        // TODO N/A
                                    }

                                    if(instanceBodyData.waterTest.nitrite) {
                                        var gage = me.getGage($instanceBody.find('.statusGage .nitrite').attr('id'));
                                        if(gage.config.value != instanceBodyData.waterTest.nitrite) {
                                            gage.refresh(instanceBodyData.waterTest.nitrite);
                                        }
                                    } else {
                                        // TODO N/A
                                    }

                                    if(instanceBodyData.waterTest.nitrate) {
                                        var gage = me.getGage($instanceBody.find('.statusGage .nitrate').attr('id'));
                                        if(gage.config.value != instanceBodyData.waterTest.nitrate) {
                                            gage.refresh(instanceBodyData.waterTest.nitrate);
                                        }
                                    } else {
                                        // TODO N/A
                                    }
                                }
                            });
                        } else {
                            // TODO No monitoring or water tests
                        }
                    }
                });

                if(loading) {
                    me.stopLoading();
                }
            }
        });
    },

    updateSwitch: function($switch, onOffStatus, datetime) {
        if(onOffStatus == "1") {
            if($switch.find('.switch-wrapper').hasClass('icon-switch-off')) {
                $switch.find('.switch-wrapper').toggleClass('icon-switch-on').toggleClass('icon-switch-off');
            }
        } else {
            if($switch.find('.switch-wrapper').hasClass('icon-switch-on')) {
                $switch.find('.switch-wrapper').toggleClass('icon-switch-on').toggleClass('icon-switch-off');
            }
        }

        var popoverID = $switch.attr('aria-describedby');
        if( popoverID ) {
            $('#' + popoverID).find('.popover-content').html('Last communication: ' + datetime);
        }
        $switch.attr('data-content', 'Last communication: ' + datetime);
    },

    onInstanceExpandClick: function(event, element) {
        var me = event.data.context,
            instanceData = event.data.entityData,
            instanceBodyData = {},
            $instance = $('#'+ me.cssId).find('li[data-id='+ instanceData.id +']'),
            $instanceBody = $instance.find('.instance-body');

        $instanceBody.slideToggle();
        $(event.currentTarget).find('i').toggleClass('fa-chevron-circle-down').toggleClass('fa-chevron-circle-up');

        if($instanceBody.html() == "") {
            me.startLoading();
            ES.ajax({
                url: BASE_URL + "api/instances/"+ instanceData.id +"/groups?addFields=params,data&filters=header:eq:0",
                success: function(data) {
                    instanceBodyData.groups = data.entities;

                    me.compileTpl({
                        tplId: 'instance-body-tmpl',
                        appendTo: $instance.find('.instance-body'),
                        empty: true,
                        data: instanceBodyData
                    });

                    $.each(instanceBodyData.groups, function(key, group) {
                        $.each(group.params, function(key, param) {
                            if(ES.isEmpty(param.data)) {
                                var noDataParam = '<h5><i class="'+ param.icon +'"></i>'+ param.title +'</h5><i class="wi wi-na no-data"></i>';
                                $instance.find('.param.'+ param.paramAlias).append(noDataParam);
                            } else {
                                param.data = ES.parseData(param.data);

                                var symbol = '';

                                if(param.typeAlias == 'percentage') {
                                    symbol = ' %';
                                } else if(param.typeAlias == 'temperature') {
                                    symbol = ' °C';
                                }

                                me.createGage({
                                    appendTo: $instance.find('.param.'+ param.paramAlias),
                                    iconCls: param.icon,
                                    title: param.title,
                                    content: 'Datetime: '+ param.datetime,
                                    gageId: param.paramAlias,
                                    value: param.data.value,
                                    minVal: 0,
                                    maxVal: 50,
                                    decimals: 1,
                                    symbol: symbol,
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
                            }
                        });
                    });
                    me.stopLoading();
                }
            });
        } else {
            // TODO destroy gages
            $instanceBody.find('[data-toggle=popover]').popover('destroy');
            $instanceBody.empty();
        }
    },

    createGage: function(params) {
        var me =  this,
            $appendTo = ES.getJQueryObject(params.appendTo),
            gageTpl = Handlebars.compile($("#gage-tmpl").html());

        me.gagesCount++;

        $appendTo.append(gageTpl({
            gageId: "gage-"+ me.gagesCount,
            gageCls: params.gageId,
            iconCls: params.iconCls,
            title: params.title,
            content: params.content
        }));

        me.gages["gage-"+ me.gagesCount] = new JustGage({
            id: "gage-"+ me.gagesCount, // params.id, //
            //title: params.title,
            value: params.value,
            min: params.minVal,
            max: params.maxVal,
            decimals: params.decimals,
            symbol: params.symbol,
            valueMinFontSize: 26,
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

    getGage: function(id) {
        var me = this;
        return me.gages[id];
    },

    createRoomTemperatureGage: function($instance, value, datetime) {
        var me = this;

        return me.createGage({
            appendTo: $instance.find('.data-status'),
            iconCls: 'wi wi-thermometer',
            title: 'Room temperature',
            content: 'Datetime: '+ datetime,
            gageId: 'roomTemperature',
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

    createTankTemperatureGage: function($instance, value, datetime) {
        var me = this;

        return me.createGage({
            appendTo: $instance.find('.data-status'),
            iconCls: 'wi wi-thermometer',
            title: 'Tank temperature',
            content: 'Datetime: '+ datetime,
            gageId: 'tankTemperature',
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

    createHumidityGage: function($instance, value, datetime) {
        var me = this;

        return me.createGage({
            appendTo: $instance.find('.data-status'),
            iconCls: 'wi wi-humidity',
            title: 'Humidity',
            content: 'Datetime: '+ datetime,
            gageId: 'humidity',
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
                    hi : 100
                }]
            }
        });
    },

    createWaterLevelGage: function($instance, value, datetime) {
        var me = this;

        return me.createGage({
            appendTo: $instance.find('.data-status'),
            iconCls: 'wi wi-humidity',
            title: 'Water level',
            content: 'Datetime: '+ datetime,
            gageId: 'waterLevel',
            value: value,
            minVal: 0,
            maxVal: 100,
            decimals: 0,
            symbol: ' %',
            levelColors : [  "#e74c3c", "#FCD432", "#2ecc71" ],
            customSectors: {
                percents: true,
                ranges: [{
                    color : "#e74c3c",
                    lo : 0,
                    hi : 59
                },{
                    color : "#FCD432",
                    lo : 60,
                    hi : 79
                },{
                    color : "#2ecc71",
                    lo : 80,
                    hi : 100
                }]
            }
        });
    },

    createPhGage: function($instance, value, datetime) {
        var me = this;

        me.createGage({
            appendTo: $instance.find('.water-test-status'),
            iconCls: 'fa fa-flask',
            title: 'pH',
            content: 'Datetime: '+ datetime,
            gageId: 'ph',
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

    createAmmoniaGage: function($instance, value, datetime) {
        var me = this;

        return me.createGage({
            appendTo: $instance.find('.water-test-status'),
            iconCls: 'fa fa-flask',
            title: 'Ammonia (NH<sub>3</sub>)',
            content: 'Datetime: '+ datetime,
            gageId: 'ammonia',
            value: value,
            minVal: 0,
            maxVal: 7.5,
            decimals: 1,
            symbol: ' mg/L',
            levelColors : [  "#2ecc71", "#2ecc71", "#FCD432", "#e74c3c", "#e74c3c" ],
            customSectors: {
                ranges: [{
                    color : "#2ecc71",
                    lo : 0,
                    hi : 0.6
                },{
                    color : "#2ecc71",
                    lo : 0.7,
                    hi : 1.2
                },{
                    color : "#FCD432",
                    lo : 1.3,
                    hi : 2.4
                },{
                    color : "#e74c3c",
                    lo : 2.5,
                    hi : 4.9
                },{
                    color : "#e74c3c",
                    lo : 5.0,
                    hi : 7.5
                }]
            }
        });
    },

    createNitriteGage: function($instance, value, datetime) {
        var me = this;

        return me.createGage({
            appendTo: $instance.find('.water-test-status'),
            iconCls: 'fa fa-flask',
            title: 'Nitrite (NO<sub>2</sub>)',
            content: 'Datetime: '+ datetime,
            gageId: 'nitrite',
            value: value,
            minVal: 0,
            maxVal: 3.3,
            decimals: 1,
            symbol: ' mg/L',
            levelColors : [  "#2ecc71", "#2ecc71", "#FCD432", "#e74c3c", "#e74c3c" ],
            customSectors: {
                ranges: [{
                    color: "#2ecc71",
                    lo: 0,
                    hi: 0.1
                }, {
                    color: "#2ecc71",
                    lo: 0.2,
                    hi: 0.3
                }, {
                    color: "#FCD432",
                    lo: 0.4,
                    hi: 0.7
                }, {
                    color: "#e74c3c",
                    lo: 0.8,
                    hi: 1.5
                }, {
                    color: "#e74c3c",
                    lo: 1.4,
                    hi: 3.3
                }]
            }
        });
    },

    createNitrateGage: function($instance, value, datetime) {
        var me = this;

        return me.createGage({
            appendTo: $instance.find('.water-test-status'),
            iconCls: 'fa fa-flask',
            title: 'Nitrate (NO<sub>3</sub>)',
            content: 'Datetime: '+ datetime,
            gageId: 'nitrate',
            value: value,
            minVal: 0,
            maxVal: 110,
            decimals: 0,
            symbol: ' mg/L',
            levelColors : [  "#2ecc71", "#2ecc71", "#FCD432", "#e74c3c", "#e74c3c" ],
            customSectors: {
                ranges: [{
                    color : "#2ecc71",
                    lo : 0,
                    hi : 5
                },{
                    color : "#2ecc71",
                    lo : 6,
                    hi : 10
                },{
                    color : "#FCD432",
                    lo : 11,
                    hi : 20
                },{
                    color : "#e74c3c",
                    lo : 21,
                    hi : 50
                },{
                    color : "#e74c3c",
                    lo : 51,
                    hi : 110
                }]
            }
        });
    },

    registerHelpers: function() {
        var me = this;

        Handlebars.registerHelper('showInstanceHeaderParams', function(params) {
            var tpl = "";
            $.each(params, function(key, param) {
                tpl += Handlebars.helpers.iconSwitch(param);
            });
            return tpl;
        });

        Handlebars.registerHelper('showBodyParams', function(groups) {
            var tpl = "";
            $.each(groups, function(key, group) {
                tpl += '<div class="param-group col-xs-6 col-md-6 col-lg-6"><h4>'+ group.title +'</h4>';

                $.each(group.params, function(key, param) {
                    tpl += '<div class="param '+ param.paramAlias +'"></div>';
                });
                tpl += '</div>';
            });
            return tpl;
        });

        Handlebars.registerHelper('iconSwitch', function(param) {
            var onIconCls,
                offIconCls,
                cls = param.typeAlias,
                content = 'Date: ' + param.datetime;

            //if(param.typeAlias == 'heartbeat') {
            //    onIconCls = 'fa fa-check success';
            //    offIconCls = 'fa fa-exclamation-triangle error';
            //} else if(cls == 'statusOnOff') {
            //    onIconCls = offIconCls = 'fa fa-lightbulb-o';
            //} else if(cls == 'pumpStatus') {
            //    onIconCls = offIconCls = 'fa fa-tint';
            //}

            var now = new Date();
            var paramDate   = new Date(param.datetime);
            var seconds = (now.getTime() - paramDate.getTime()) / 1000;

            if(seconds >= 86359) {
                var days = Math.round((seconds / 86400) * 100) / 100;
                content += ' ('+ days +' day(s) ago)'
            } else if(seconds >= 3599) {
                var hours = Math.round((seconds / 3600) * 100) / 100;
                content += ' ('+ hours +' hour(s) ago)'
            } else if(seconds >= 59) {
                var minutes = Math.round((seconds / 60) * 100) / 100;
                content += ' ('+ minutes +' minute(s) ago)'
            } else {
                content += ' ('+ Math.round(seconds) +' second(s) ago)'
            }

            if(param.typeAlias == 'heartbeat') {
                param.data.value = (seconds < 120); // Less than 2 minutes
            }

            var tpl = Handlebars.compile($("#icon-switch-tmpl").html());

            return tpl({
                iconCls: param.typeAlias,
                title: param.title,
                content: content,
                onOffStatus: (typeof(param.data.value) === "boolean" && param.data.value ? 'on' : 'off'),
                onIconCls: param.icon,
                offIconCls: (param.typeAlias == 'heartbeat') ? 'fa fa-exclamation-triangle error' : param.icon
            });
        });
    }
});