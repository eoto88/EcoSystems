<div id="widget-instances" class="widget">
    <header role="heading">
        <span class="widget-icon"><i class="fa fa-list-alt fa-fw "></i></span>
        <h2><?php echo __('Instances'); ?></h2>
        <span class="widget-expand"><i class="fa fa-chevron-down"></i></span>
    </header>
    <div class="widget-body">
        <ul>
            <?php foreach($instances as $instance) { ?>
                <li class="instance" data-id="<?php echo $instance['id'] ?>">
                    <div class="row">
                        <div class="instance-header col-sm-12 col-md-12 col-lg-6">
                            <div class="row">
                                <div class="col-sm-6 col-md-6 col-lg-12">
                                    <h3>
                                        <a href="<?php echo URL::base(TRUE, TRUE) .'live/'. $instance['id'] ?>">
                                            <?php echo $instance['title'] ?>
                                        </a>
                                        <a class="instance-expand"><i class="fa fa-chevron-circle-down" aria-hidden="true"></i></a>
                                    </h3>
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
                        <div class="instance-body col-sm-12 col-md-12 col-lg-6">
                            <div class="row">
                                <div class="col-xs-6 col-md-6 col-lg-6">
                                    <?php echo $instance['data_status'] ?>
                                </div>
                                <div class="col-xs-6 col-md-6 col-lg-6">
                                    <?php echo $instance['water_test_status'] ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            <?php } ?>
        </ul>
    </div>
</div>