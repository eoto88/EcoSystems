<div id="temperatureChart"></div>
<div id="sunlightChart"></div>
<?php /*var_dump($sunlightData);*/ ?>
<script type="application/javascript">
    var roomTemperatureData = <?php echo json_encode($roomTemperatureData); ?>;
    var tankTemperatureData = <?php echo json_encode($tankTemperatureData); ?>;
    var sunlightData = <?php echo json_encode($sunlightData); ?>;
</script>