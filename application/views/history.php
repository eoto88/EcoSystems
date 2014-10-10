<div id="temperatureHistoryChart" class="chart"></div>
<div id="sunlightHistoryChart" class="chart"></div>
<script type="application/javascript">
    var roomTemperatureHistory = <?php echo json_encode( $roomTemperatureHistory ); ?>;
    var tankTemperatureHistory = <?php echo json_encode( $tankTemperatureHistory ); ?>;
    var sunlightHistory = <?php echo json_encode( $sunlightHistory ); ?>;
</script>