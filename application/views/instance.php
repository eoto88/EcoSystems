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
                    <li class="instance" data-id="<?php echo $instance['id'] ?>">
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-6">
                                <div class="row">
                                    <div class="col-sm-6 col-md-6 col-lg-12">
                                        <h3><a href="<?php echo URL::base(TRUE, TRUE) .'live/'. $instance['id'] ?>">
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
    </article>
</div>