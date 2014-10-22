<div class="row">
    <div class="col-sm-12 col-md-12 col-lg-12">
        <h1><i class="fa fa-lg fa-fw fa-tachometer"></i> <?php echo __('Dashboard') ?></h1>
    </div>
</div>
<div class="row">
    <article class="col-sm-12 col-md-12 col-lg-6">
        <div class="widget-instances widget">
            <header role="heading">
                <span class="widget-icon"><i class="fa fa-list-alt fa-fw "></i></span>
                <h2>Instances</h2>
            </header>
            <div class="widget-body">
                <ul>
                    <?php foreach($instances as $instance) { ?>
                        <li class="instance" data-id="<?php echo $instance['id_instance'] ?>">
                            <div class="row">
                                <div class="col-sm-12 col-md-12 col-lg-4">
                                    <div class="row">
                                        <div class="col-sm-6 col-md-6 col-lg-12">
                                            <a href="<?php echo URL::base(TRUE, TRUE) .'live/'. $instance['id_instance'] ?>">
                                                <?php echo $instance['title'] ?>
                                            </a><br /><br />
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
        <div class="widget-todos widget">
            <header role="heading">
                <span class="widget-icon"><i class="fa fa-list-alt fa-fw "></i></span>
                <h2>ToDo's</h2>
            </header>
            <div class="widget-body">
                <ul>
                    <?php
                    if(count($toDos)) {
                        foreach($toDos as $toDo) {
                            echo '<li id="todo-'. $toDo['id_todo'] .'"><i class="fa fa-square-o check"></i><span>'. $toDo['title'] .'</span></li>';
                        }
                    } else {
                        echo '<li id="no-todo">'. __('No task in the to do list') .'</li>';
                    }
                    ?>
                </ul>
            </div>
        </div>
    </article>
</div>