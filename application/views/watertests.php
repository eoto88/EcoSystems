<div class="masonry row">
    <article class="col-sm-12 col-md-12 col-lg-6">
        <div class="widget-form widget" id="widget-waterTests">
            <header role="heading">
                <span class="widget-icon"><i class="fa fa-tint fa-fw "></i></span>
                <h2><?php echo __('Water tests') ?></h2>
            </header>
            <div class="widget-body">
                <div id="phChart"></div>
                <div id="ammoniaChart"></div>
                <div id="nitriteChart"></div>
                <div id="nitrateChart"></div>
            </div>
        </div>
    </article>
    <article class="col-sm-12 col-md-12 col-lg-6">
        <div class="widget-form widget" id="widget-form">
            <header role="heading">
                <span class="widget-icon"><i class="fa fa-pencil fa-fw "></i></span>
                <h2><?php echo __('Water test form') ?></h2>
            </header>
            <div class="widget-body">
                <?php echo $form; ?>
            </div>
        </div>
    </article>
    <div class="clearfix"></div>
</div>
<script type="application/javascript">
    var phData = <?php echo json_encode($phData); ?>;
    var ammoniaData = <?php echo json_encode($ammoniaData); ?>;
    var nitriteData = <?php echo json_encode($nitriteData); ?>;
    var nitrateData = <?php echo json_encode($nitrateData); ?>;
</script>