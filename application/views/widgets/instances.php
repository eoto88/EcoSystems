<div id="widget-instances" class="widget" <?php if(isset($id_instance)) { ?>data-single-instance="<?php echo $id_instance; ?>"<?php } ?>>
    <header role="heading">
        <span class="widget-icon"><i class="fa fa-list-alt fa-fw "></i></span>
        <h2><?php echo __('Instances'); ?></h2>
        <span class="widget-expand"><i class="fa fa-chevron-down"></i></span>
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
                {{{dataStatus}}}
            </div>
            <div class="water-test-status col-xs-6 col-md-6 col-lg-6">
                {{{waterTestStatus}}}
            </div>
        </div>
    </script>
    <script type="text/x-handlebars-template" id="icon-switch">
        <span class="icon-swicth {{iconCls}}">
            <div class="swicth swicth-wrapper icon-swicth-{{onOffStatus}}">
                <div class="swicth-container">
                    <span class="swicth-handle-on swicth-primary">
                        <i class="{{onIconCls}}"></i>
                    </span>
                    <label class="swicth-label">&nbsp;</label>
                    <span class="swicth-handle-off swicth-default">
                        <i class="{{offIconCls}}"></i>
                    </span>
                    <input type="checkbox" checked="">
                </div>
            </div>
        </span>
    </script>
</div>