<div class="masonry row">
    <article class="col-sm-12 col-md-12 col-lg-6">
        <?php echo $widget_instances; ?>
    </article>
    <article class="col-sm-12 col-md-12 col-lg-6">
        <?php echo $widget_todos; ?>
    </article>
    <article class="col-sm-12 col-md-12 col-lg-6">
        <div id="widget-logs" class="widget">
            <header role="heading">
                <span class="widget-icon"><i class="fa fa-file-text-o fa-fw "></i></span>
                <h2><?php echo __('Logs') ?></h2>
                <span class="widget-expand"><i class="fa fa-chevron-down"></i></span>
            </header>
            <div class="widget-body">
                <ul>
                    <?php
                    if(count($logs)) {
                        foreach($logs as $log) {
                            $icon = '';
                            switch($log['type']) {
                                case 'info':
                                    $icon = '<i class="fa fa-check success"></i>';
                                    break;
                                case 'error':
                                    $icon = '<i class="fa fa-exclamation-triangle error"></i>';
                                    break;
                            }

                            echo '<li data-id="log-'. $log['id'] .'">'. $icon .'&nbsp;'. $log['message'] .'<span>&nbsp;-&nbsp;'. date('Y/m/d H:i:s', strtotime($log['timestamp'])) .'</span>'.'</li>';
                        }
                    } else {
                        echo '<li id="no-logs">'. __('No logs') .'</li>';
                    }
                    ?>
                </ul>
            </div>
        </div>
    </article>
    <div class="clearfix"></div>
</div>