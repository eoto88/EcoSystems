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
                    <h3>
                        <a href="{{BASE_URL}}live/{{id}}">{{title}}&nbsp;<span class="instance-type">({{instance_type}})</span></a>
                        <a class="instance-expand"><i class="fa fa-chevron-circle-down" aria-hidden="true"></i></a>
                    </h3>
                    {{{showInstanceHeaderParams this.params}}}
                </div>
                <div class="instance-body col-sm-12 col-md-12 col-lg-12"></div>
            </div>
        </li>
    </script>
    <script type="text/x-handlebars-template" id="instance-body-tmpl">
        <div class="row">
            {{{showBodyParams this.params}}}
        </div>
    </script>
    <script type="text/x-handlebars-template" id="gage-tmpl">
        <div class="statusGage" data-toggle="popover" title="{{{title}}}" data-content="{{content}}">
            <h5><i class="{{iconCls}}"></i> {{{title}}}</h5>
            <div id="{{gageId}}" class="gage {{gageCls}}"></div>
        </div>
    </script>
    <script type="text/x-handlebars-template" id="icon-switch-tmpl">
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