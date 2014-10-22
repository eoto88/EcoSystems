<div id="temperatureChart" class="chart"></div>
<div id="sunlightChart" class="chart"></div>
<script type="application/javascript">
    var roomTemperatureData = <?php echo json_encode($roomTemperatureData); ?>;
    var tankTemperatureData = <?php echo json_encode($tankTemperatureData); ?>;
    var sunlightData = <?php echo json_encode($sunlightData); ?>;
</script>