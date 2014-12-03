<div class="row">
    <article class="col-sm-12 col-md-12 col-lg-12">
        <div class="widget-live widget">
            <header role="heading">
                <span class="widget-icon"><i class="fa fa-bar-chart-o fa-fw "></i></span>
                <h2><?php echo $instance['title'] ?></h2>
            </header>
            <div class="widget-body">
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-4">
                        <div class="row">
                            <div class="col-sm-6 col-md-6 col-lg-12">

                            </div>
                            <div class="col-sm-6 col-md-6 col-lg-12">
                                <?php echo $liveStatus['communication_status'] ?>
                                <?php echo $liveStatus['pump_status'] ?>
                                <?php echo $liveStatus['light_status'] ?>
                                <?php echo $liveStatus['fan_status'] ?>
                                <?php echo $liveStatus['heater_status'] ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-4">
                        <?php echo $liveStatus['sun_status'] ?>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-4">
                        <?php echo $liveStatus['temperature_status'] ?>
                    </div>
                </div>
            </div>
        </div>
    </article>
</div>
<div class="row">
    <article class="col-sm-12 col-md-12 col-lg-12">
        <div class="widget-live-chart widget">
            <header role="heading">
                <span class="widget-icon"><i class="fa fa-bar-chart-o fa-fw "></i></span>
                <h2><?php echo __('Temperature'); ?></h2>
            </header>
            <div class="widget-body">
                <div id="temperatureChart" class="chart"></div>
            </div>
        </div>
    </article>
</div>
<div class="row">
    <article class="col-sm-12 col-md-12 col-lg-12">
        <div class="widget-live-chart widget">
            <header role="heading">
                <span class="widget-icon"><i class="fa fa-bar-chart-o fa-fw "></i></span>
                <h2><?php echo __('Humidity'); ?></h2>
            </header>
            <div class="widget-body">
                <div id="humidityChart" class="chart"></div>
            </div>
        </div>
    </article>
</div>
<div class="row">
    <article class="col-sm-12 col-md-12 col-lg-12">
        <div class="widget-live-chart widget">
            <header role="heading">
                <span class="widget-icon"><i class="fa fa-bar-chart-o fa-fw "></i></span>
                <h2><?php echo __('Sunlight'); ?></h2>
            </header>
            <div class="widget-body">
                <div id="sunlightChart" class="chart"></div>
            </div>
        </div>
    </article>
</div>
<script type="application/javascript">
    var humidityData = <?php echo json_encode($humidityData); ?>;
    var roomTemperatureData = <?php echo json_encode($roomTemperatureData); ?>;
    var tankTemperatureData = <?php echo json_encode($tankTemperatureData); ?>;
    var sunlightData = <?php echo json_encode($sunlightData); ?>;
</script>