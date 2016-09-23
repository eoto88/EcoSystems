<div class="row">
    <article class="col-sm-12 col-md-12 col-lg-12">
        <?php echo $widget_instances; ?>
    </article>
</div>
<div class="row">
    <article class="col-sm-12 col-md-12 col-lg-12">
        <div class="widget-live-chart widget" id="widget-live">
            <header role="heading">
                <span class="widget-icon"><i class="fa fa-bar-chart-o fa-fw "></i></span>
                <h2><?php echo __('Live data'); ?></h2>
            </header>
            <div class="widget-body">
                <div id="temperatureChart" class="chart"></div>
                <div id="humidityChart" class="chart"></div>
            </div>
        </div>
    </article>
</div>
<script type="application/javascript">
    var humidityData = <?php echo json_encode($humidityData); ?>;
    var roomTemperatureData = <?php echo json_encode($roomTemperatureData); ?>;
    var tankTemperatureData = <?php echo json_encode($tankTemperatureData); ?>;
</script>