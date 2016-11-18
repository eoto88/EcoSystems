<div id="widget-instances" class="widget" <?php if(isset($id_instance)) { ?>data-single-instance="<?php echo $id_instance; ?>"<?php } ?>>
    <header role="heading">
        <span class="widget-icon"><i class="fa fa-list-alt fa-fw "></i></span>
        <h2><?php echo __('Instances'); ?></h2>
    </header>
    <div class="widget-body">
    </div>
    <script type="text/x-handlebars-template" id="instance-tmpl">
        <li class="instance" data-id="{{id}}">
            <div class="row">
                <div class="instance-header col-sm-12 col-md-12 col-lg-12">
                    <div class="row">
                        <div class="col-sm-6 col-md-6 col-lg-12">
                            <h3>
                                <a href="{{BASE_URL}}live/{{id}}">
                                    {{title}}
                                </a>
                                <a class="instance-expand">
                                    <i class="fa fa-chevron-circle-down" aria-hidden="true"></i>
                                </a>
                            </h3>
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-12">
                            {{{iconSwitch this.heartbeat "heartbeat-status"}}}&nbsp;&nbsp;
                            {{{iconSwitch this.light_on "light-status"}}}&nbsp;&nbsp;
                            {{{iconSwitch this.pump_on "pump-status"}}}&nbsp;&nbsp;
                            {{{iconSwitch this.fan_on "fan-status"}}}
                        </div>
                    </div>
                </div>
                <div class="instance-body col-sm-12 col-md-12 col-lg-12"></div>
            </div>
        </li>
    </script>
    <script type="text/x-handlebars-template" id="instance-body-tmpl">
        <div class="row">
            <div class="data-status col-xs-6 col-md-6 col-lg-6">
                <div class="statusGage">
                    <h5><i class="wi wi-humidity"></i> Humidity</h5>
                    <div id="humidityGage" class="gage"></div>
                </div>
                <div class="statusGage">
                    <h5><i class="wi wi-thermometer"></i> Room temperature</h5>
                    <div id="roomTemperatureGage" class="gage"></div>
                </div>
                <div class="statusGage">
                    <h5><i class="wi wi-thermometer"></i> Tank temperature</h5>
                    <div id="tankTemperatureGage" class="gage"></div>
                </div>
                {{{dataStatus}}}
            </div>
            <div class="water-test-status col-xs-6 col-md-6 col-lg-6">
                <div class="statusGage">
                    <h5><i class="wi wi-thermometer"></i> pH</h5>
                    <div id="phGage" class="gage"></div>
                </div>
                <div class="statusGage">
                    <h5><i class="wi wi-thermometer"></i> Ammonia (NH<sub>3</sub>)</h5>
                    <div id="ammoniaGage" class="gage"></div>
                </div>
                <div class="statusGage">
                    <h5><i class="wi wi-thermometer"></i> Nitrite (NO<sub>2</sub>)</h5>
                    <div id="nitriteGage" class="gage"></div>
                </div>
                <div class="statusGage">
                    <h5><i class="wi wi-thermometer"></i> Nitrate (NO<sub>3</sub>)</h5>
                    <div id="nitrateGage" class="gage"></div>
                </div>

                {{{waterTestStatus}}}
            </div>
        </div>
    </script>
    <script type="text/x-handlebars-template" id="icon-switch">
        <span class="icon-switch {{iconCls}}" data-toggle="popover" title="{{title}}" data-content="{{content}}">
            <div class="switch switch-wrapper icon-switch-{{onOffStatus}}">
                <div class="switch-container">
                    <span class="switch-handle-on switch-primary">
                        <i class="{{onIconCls}}"></i>
                    </span>
                    <label class="switch-label">&nbsp;</label>
                    <span class="switch-handle-off switch-default">
                        <i class="{{offIconCls}}"></i>
                    </span>
                    <input type="checkbox" checked="">
                </div>
            </div>
        </span>
    </script>
</div>