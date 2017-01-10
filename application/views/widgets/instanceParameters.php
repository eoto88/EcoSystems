<div id="widget-params" class="widget-params widget">
    <header role="heading">
        <span class="widget-icon"><i class="fa fa-cogs fa-fw"></i></span>
        <h2><?php echo __('Instance parameters') ?></h2>
    </header>
    <div class="widget-body">
        <table width="100%">
            <tr>
                <th>Title</th>
                <th>Alias</th>
                <th>Type</th>
            </tr>
            <?php
            foreach ($instance_params as $param) {
                echo '<tr><td>' . $param['title'] .'</td><td>' . $param['alias'] .'</td><td>' . $param['type'] .'</td><td><i class="fa fa-pencil" aria-hidden="true"></i></td></tr>';
            }
            ?>
        </table>
        <?php echo $form; ?>
    </div>
</div>