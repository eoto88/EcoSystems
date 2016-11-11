App.WidgetInstances = App.Widget.extend({

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

                    var $instance = $component.find('li[data-id='+ instanceData.id +']');
                    $instance.find('.instance-expand').click(function() {
                        var deferredCalls = [],
                            instanceBodyData = {},
                            $instanceBody = $instance.find('.instance-body');

                        $instanceBody.slideToggle();
                        $(this).find('i').toggleClass('fa-chevron-circle-down').toggleClass('fa-chevron-circle-up');

                        if($instanceBody.html() == "") {
                            // TODO instance.type

                            if(instanceData.monitored == "1") {

                                //var gg1 = new JustGage({
                                //    id: "gg1",
                                //    value : 72.15,
                                //    min: 0,
                                //    max: 100,
                                //    decimals: 2,
                                //    gaugeWidthScale: 0.6,
                                //    customSectors: [{
                                //        color : "#00ff00",
                                //        lo : 0,
                                //        hi : 50
                                //    },{
                                //        color : "#ff0000",
                                //        lo : 50,
                                //        hi : 100
                                //    }],
                                //    counter: true
                                //});

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
                                        } else {
                                            console.warn('no arguments...')
                                        }
                                    }
                                });
                            } else {
                                // TODO No monitoring or water tests
                            }
                        } else {
                            $instanceBody.empty();
                        }
                    });
                });

                me.stopLoading();
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
                        $heartbeat = $instance.find('.heartbeat'),
                        $heartbeat_icon = $heartbeat.find('.status-icon'),
                        $pump_status = $instance.find('.pump-status'),
                        $pump_icon = $pump_status.find('.status-icon'),
                        $light_status = $instance.find('.light-status'),
                        $light_icon = $light_status.find('.status-icon');

                    if(instanceData.heartbeat == "1") {
                        if($heartbeat_icon.hasClass('error')) {
                            $heartbeat_icon.removeClass('error fa-exclamation-triangle').addClass('success fa-check');
                        }
                    } else {
                        if($heartbeat_icon.hasClass('success')) {
                            $heartbeat_icon.removeClass('success fa-check').addClass('error fa-exclamation-triangle');
                        }
                    }
                    $heartbeat.attr('title', "Last communication: " + instanceData.last_communication);
                    if(instanceData.pump_on == "1") {
                        $pump_status.attr('title', "Pump is on");
                        $pump_icon.addClass('pump-on');
                    } else {
                        $pump_icon.removeClass('pump-on');
                        $pump_status.attr('title', "Pump is off");
                    }
                    if(instanceData.light_on == "1") {
                        $light_status.attr('title', "Light is on");
                        $light_icon.addClass('light-on');
                    } else {
                        $light_icon.removeClass('light-on');
                        $light_status.attr('title', "Light is off");
                    }

                });
            }
        //}).done(function(data) {
        //    $.each(data, function(key, instance) {
        //        var $instanceRow = $("#widget-instances li[data-id='" + instance.idInstance + "']");
        //
        //        var status;
        //        if(instance.stillAliveStatus == "still-alive")
        //            status = '<i class="fa fa-check success"></i>';
        //        else
        //            status = '<i class="fa fa-exclamation-triangle error"></i>';
        //        $instanceRow.find("#still_alive").html(status).attr("title", I18n.lastCommunication + ": " + instance.lastCommunication);
        //
        //        me.changeStatus($instanceRow, 'pump', 'Pump', instance.pumpStatus);
        //        me.changeStatus($instanceRow, 'light', 'Light', instance.lightStatus);
        //        me.changeStatus($instanceRow, 'fan', 'Fan', instance.fanStatus);
        //        me.changeStatus($instanceRow, 'heater', 'Heater', instance.heaterStatus);
        //    });
        });
    },
    changeStatus: function($instanceRow, relayId, relayName, status) {
        $("#"+ relayId +"_status").attr('title', relayName +' is '+ status);
        if(status === "on") {
            $instanceRow.find("#"+ relayId +"_status .status-icon").addClass(relayId +'-on');
        } else {
            $instanceRow.find("#"+ relayId +"_status .status-icon").removeClass(relayId +'-on');
        }
    },

    registerHelpers: function() {
        var me = this;

        Handlebars.registerHelper('iconSwitch', function(onOffStatus, cls) {
            // TODO timestamp + tooltip
            var onIconCls,
                offIconCls;

            if(cls == 'heartbeat-status') {
                onIconCls = 'fa fa-check success';
                offIconCls = 'fa fa-exclamation-triangle error';
            } else if(cls == 'light-status') {
                onIconCls = offIconCls = 'fa fa-lightbulb-o';
            } else if(cls == 'pump-status') {
                onIconCls = offIconCls = 'fa fa-tint';
            } else if(cls == 'fan-status') {
                onIconCls = offIconCls = 'wi wi-cloudy-gusts';
            }

            var tpl = Handlebars.compile($("#icon-switch").html());

            return tpl({
                iconCls: cls,
                onOffStatus: (onOffStatus == "1" ? 'on' : 'off'),
                onIconCls: onIconCls,
                offIconCls: offIconCls
            });
        });

        //Handlebars.registerHelper('relayStatus', function(relayId, relayName, status) {
        //    var icon = '',
        //        title = status == "1" ? relayName +" is on" : relayName +" is off",
        //        cls = status == "1" ? relayId +"-on" : "";
        //
        //    switch (relayId) {
        //        case 'pump':
        //            icon = 'fa fa-tint';
        //            break;
        //        case 'light':
        //            icon = 'fa fa-lightbulb-o';
        //            break;
        //        case 'fan':
        //            icon = 'wi wi-cloudy-gusts';
        //            break;
        //    }
        //
        //    return '<span class="'+ relayId +'-status" title="'+ title +'"><i class="status-icon '+ icon +' '+ cls +'"></i></span>';
        //});

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