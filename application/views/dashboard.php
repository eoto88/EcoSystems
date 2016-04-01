<div class="masonry row">
    <article class="col-sm-12 col-md-12 col-lg-6">
        <div id="widget-instances" class="widget">
            <header role="heading">
                <span class="widget-icon"><i class="fa fa-list-alt fa-fw "></i></span>
                <h2>Instances</h2>
                <span class="widget-expand"><i class="fa fa-chevron-down"></i></span>
            </header>
            <div class="widget-body">
                <ul>
                    <?php foreach($instances as $instance) { ?>
                        <li class="instance" data-id="<?php echo $instance['id_instance'] ?>">
                            <div class="row">
                                <div class="col-sm-12 col-md-12 col-lg-6">
                                    <div class="row">
                                        <div class="col-sm-6 col-md-6 col-lg-12">
                                            <h3><a href="<?php echo URL::base(TRUE, TRUE) .'live/'. $instance['id_instance'] ?>">
                                                <?php echo $instance['title'] ?>
                                            </a></h3>
                                        </div>
                                        <div class="col-sm-6 col-md-6 col-lg-12">
                                            <?php echo $instance['communication_status'] ?>
                                            <?php echo $instance['pump_status'] ?>
                                            <?php echo $instance['light_status'] ?>
                                            <?php echo $instance['fan_status'] ?>
                                            <?php echo $instance['heater_status'] ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-6">
                                    <?php echo $instance['temperature_status'] ?>
                                </div>
                            </div>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </article>
    <article class="col-sm-12 col-md-12 col-lg-6">
        <?php echo $widget_todos; ?>
    </article>
    <article class="col-sm-12 col-md-12 col-lg-6">
        <div id="widget-logs" class="widget">
            <header role="heading">
                <span class="widget-icon"><i class="fa fa-file-text-o fa-fw "></i></span>
                <h2><?php echo __('Logs') ?></h2>
                <span class="widget-expand"><i class="fa fa-chevron-down"></i></span>
            </header>
            <div class="widget-body">
                <ul>
                    <?php
                    if(count($logs)) {
                        foreach($logs as $log) {
                            $icon = '';
                            switch($log['type']) {
                                case 'info':
                                    $icon = '<i class="fa fa-check success"></i>';
                                    break;
                                case 'error':
                                    $icon = '<i class="fa fa-exclamation-triangle error"></i>';
                                    break;
                            }

                            echo '<li data-id="log-'. $log['id_log'] .'">'. $icon .'&nbsp;'. $log['message'] .'<span>&nbsp;-&nbsp;'. date('Y/m/d H:i:s', strtotime($log['timestamp'])) .'</span>'.'</li>';
                        }
                    } else {
                        echo '<li id="no-logs">'. __('No logs') .'</li>';
                    }
                    ?>
                </ul>
            </div>
        </div>
    </article>
</div>
<script type="text/x-handlebars-template" id="no-todo-tmpl">
    <li id="no-todo" style="opacity: 0; height: 0;"><span><?php echo __('No task in the to do list') ?></span></li>
</script>