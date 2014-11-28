<div class="row">
    <article class="col-sm-12 col-md-12 col-lg-12">
        <div class="widget-history widget">
            <header role="heading">
                <span class="widget-icon"><i class="fa fa-bar-chart-o fa-fw "></i></span>
                <h2>Bedroom</h2>
            </header>
            <div class="widget-body">
                <div id="temperatureHistoryChart" class="chart"></div>
                <div id="sunlightHistoryChart" class="chart"></div>
            </div>
        </div>
    </article>
</div>
<script type="application/javascript">
    var roomTemperatureHistory = <?php echo json_encode( $roomTemperatureHistory ); ?>;
    var tankTemperatureHistory = <?php echo json_encode( $tankTemperatureHistory ); ?>;
    var sunlightHistory = <?php echo json_encode( $sunlightHistory ); ?>;
</script>