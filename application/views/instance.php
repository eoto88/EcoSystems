<div id="widgets" class="row" data-columns>
    <article>
        <?php
        if( isset($widget_instances) ) {
            echo $widget_instances;
        } ?>
    </article>
    <article>
        <div id="widget-form" class="widget-form widget">
            <header role="heading">
                <span class="widget-icon"><i class="fa fa-pencil fa-fw"></i></span>
                <h2><?php echo __('Instance form') ?></h2>
            </header>
            <div class="widget-body">
                <?php echo $form; ?>
            </div>
        </div>
    </article>
    <article>
        <?php
        if( isset($widget_instanceParameters) ) {
            echo $widget_instanceParameters;
        } ?>
    </article>
    <article>
        <?php
        if( isset($widget_todos) ) {
            echo $widget_todos;
        } ?>
    </article>
    <article>
        Logs
        <?php
        if( isset($widget_logs) ) {
            echo $widget_logs;
        } ?>
    </article>
    <div class="clearfix"></div>
</div>