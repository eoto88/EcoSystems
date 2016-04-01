<div class="row">
    <article class="col-sm-12 col-md-12 col-lg-6">
        <div id="widget-instances" class="widget">
            <header role="heading">
                <span class="widget-icon"><i class="fa fa-list-alt fa-fw "></i></span>
                <h2><?php echo __('Instance') ?></h2>
                <span class="widget-expand"><i class="fa fa-chevron-down"></i></span>
            </header>
            <div class="widget-body">
                <ul>
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
                                        <?php /*echo $instance['communication_status']*/ ?>
                                        <?php /*echo $instance['pump_status']*/ ?>
                                        <?php /*echo $instance['light_status']*/ ?>
                                        <?php /*echo $instance['fan_status']*/ ?>
                                        <?php /*echo $instance['heater_status']*/ ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-12 col-lg-6">
                                <?php /*echo $instance['temperature_status']*/ ?>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        <div id="widget-form" class="widget-form widget">
            <header role="heading">
                <span class="widget-icon"><i class="fa fa-pencil fa-fw"></i></span>
                <h2><?php echo __('Instance form') ?></h2>
                <span class="widget-expand"><i class="fa fa-chevron-down"></i></span>
            </header>
            <div class="widget-body">
                <?php echo $form; ?>
            </div>
        </div>
    </article>
    <article class="col-sm-12 col-md-12 col-lg-6">
        <?php
        if( isset($widget_todos) ) {
            echo $widget_todos;
        } ?>
<!--        <div class="widget-table widget">-->
<!--            <header role="heading">-->
<!--                <span class="widget-icon"><i class="fa fa-check fa-fw "></i></span>-->
<!--                <h2>--><?php //echo __('ToDo\'s') ?><!--</h2>-->
<!--            </header>-->
<!--            <div class="widget-body">-->
<!--                <table class="table table-bordered">-->
<!--                    <tr><th>--><?php //echo __('Title') ?><!--</th><th>--><?php //echo __('Interval value') ?><!--</th><th>--><?php //echo __('Time unit') ?><!--</th><th>--><?php //echo __('Last check') ?><!--</th></tr>-->
<!--                    --><?php
//                    foreach ($toDos as $toDo) {
//                        echo '<tr><td>' . $toDo['title'] .'</td><td>' . $toDo['interval_value'] .'</td><td>' . __($toDo['time_unit']) .'</td><td>' . $toDo['last_check'] .'</td></tr>';
//                    }
//                    ?>
<!--                </table>-->
<!--            </div>-->
<!--        </div>-->
    </article>
<!--    <article class="col-sm-12 col-md-12 col-lg-6">-->
<!--        <div class="widget-table widget">-->
<!--            <header role="heading">-->
<!--                <span class="widget-icon"><i class="fa fa-check fa-fw "></i></span>-->
<!--                <h2>--><?php //echo __('ToDo\'s') ?><!--</h2>-->
<!--                <span class="widget-expand"><i class="fa fa-chevron-down"></i></span>-->
<!--            </header>-->
<!--            <div class="widget-body">-->
<!--                <table class="table table-bordered">-->
<!--                    <tr>-->
<!--                        <th>--><?php //echo __('Title') ?><!--</th>-->
<!--                        <th>--><?php //echo __('Type') ?><!--</th>-->
<!--                        <th>--><?php //echo __('Code') ?><!--</th>-->
<!--                        <th>--><?php //echo __('Monitored') ?><!--</th>-->
<!--                    </tr>-->
<!--                    --><?php //foreach ($instances as $instance) { ?>
<!--                        <tr data-id="--><?php //echo $instance['id_instance']; ?><!--">-->
<!--                            <td>--><?php //echo $instance['title']; ?><!--</td>-->
<!--                            <td>--><?php //echo $instance_types[$instance['type']]; ?><!--</td>-->
<!--                            <td>--><?php //echo $instance['code']; ?><!--</td>-->
<!--                            <td>--><?php //echo $instance['monitored']; ?><!--</td>-->
<!--                        </tr>-->
<!--                    --><?php //} ?>
<!--                </table>-->
<!--            </div>-->
<!--        </div>-->
<!--    </article>-->
</div>