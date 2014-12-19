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
                                <div class="col-sm-12 col-md-12 col-lg-4">
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
                                <div class="col-sm-12 col-md-6 col-lg-4">
                                    <?php echo $instance['sun_status'] ?>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-4">
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
        <div id="widget-todos" class="widget">
            <header role="heading">
                <span class="widget-icon"><i class="fa fa-check fa-fw "></i></span>
                <h2>ToDo's</h2>
                <span class="widget-expand"><i class="fa fa-chevron-down"></i></span>
            </header>
            <div class="widget-body">
                <?php
                $checkedTodos = array();
                $uncheckedTodos = array();
                if( count($toDos) ) {
                    foreach( $toDos as $toDo ) {
                        if( $toDo['checked'] ) {
                            $checkedTodos[] = $toDo;
                        } else {
                            $uncheckedTodos[] = $toDo;
                        }
                    }
                }
                ?>
                <h4>
                    <i class="fa fa-exclamation fa-fw "></i>&nbsp;
                    <?php echo __('Uncompleted tasks') ?>&nbsp;
                    (<span id="unchecked-todos-count"><?php echo count($uncheckedTodos) ?></span>)
                </h4>
                <ul id="unchecked-todos">
                    <?php
                    if( count($uncheckedTodos) ) {
                        foreach( $uncheckedTodos as $toDo ) {
                            echo '<li id="todo-' . $toDo['id_todo'] . '"><i class="fa fa-square-o check-icon"></i><span>' . $toDo['title'] . '</span></li>';
                        }
                    } else {
                        echo '<li id="no-todo"><span>'. __('No task in the to do list') .'</span></li>';
                    }
                    ?>
                </ul>
                <h4><i class="fa fa-check fa-fw "></i>&nbsp;
                    <?php echo __('Completed tasks') ?>&nbsp;
                    (<span id="checked-todos-count"><?php echo count($checkedTodos) ?></span>)
                </h4>
                <ul id="checked-todos">
                    <?php
                    if( count($checkedTodos) ) {
                        foreach( $checkedTodos as $toDo ) {
                            echo '<li id="todo-' . $toDo['id_todo'] . '"><i class="fa fa-check-square-o check-icon"></i><span>' . $toDo['title'] . '</span></li>';
                        }
                    }
                    ?>
                </ul>
            </div>
        </div>
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