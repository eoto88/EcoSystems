<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $title; ?> | EcoSystem</title>
    <meta name="description" content="">
    <meta name="robots" content="noindex, nofollow">

    <link rel="shortcut icon" href="favicon.ico" />
    <link rel="manifest" href="manifest.json" />

    <script src="<?php echo URL::base(TRUE, TRUE); ?>assets/js/vendor/modernizr-2.6.2.min.js"></script>
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,300,400,700">
    <?php foreach($styles as $file => $type) { echo HTML::style($file, array('media' => $type)), "\n";}?>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<header id="header">
    <div id="header-container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                <h1 id="logo">EcoSystems</h1>
                <span id="mobile-menu-icon"><i class="fa fa-bars" aria-hidden="true"></i></span>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                <div class="header-block header-user">
                    <a href="#" id="welcome" class="dropdown-toggle" data-toggle="dropdown">
                        <span>Welcome <?php echo $user['name']; ?></span>&nbsp;<i class="fa fa-caret-down"></i>
                    </a>
                    <ul id="dropdown-user" class="dropdown-menu js-status-update pull-right">
                        <li data-id="">
                            <a href="<?php echo URL::base(TRUE, TRUE) .'profile' ?>"><?php echo __('Profile') ?></a>
                        </li>
                        <li data-id="">
                            <a href="<?php echo URL::base(TRUE, TRUE) .'logout' ?>"><?php echo __('Logout') ?></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</header>
<aside id="left-panel" class="mobile-closed">
    <nav id="pages-menu">
        <ul>
            <li <?php echo ($current_route_name == 'dashboard') ? 'class="active"' : ''; ?>>
                <a href="<?php echo URL::base(TRUE, TRUE); ?>">
                    <i class="fa fa-lg fa-fw fa-tachometer"></i>&nbsp;
                    <span class="menu-title"><?php echo __('Dashboard'); ?></span>
                </a>
            </li>
<!--            <li --><?php //echo ($current_route_name == 'history') ? 'class="active"' : ''; ?><!-->-->
<!--                <a href="--><?php //echo URL::base(TRUE, TRUE) .'history'; ?><!--" class="require-instance-id">-->
<!--                    <i class="fa fa-lg fa-fw fa-history"></i>&nbsp;-->
<!--                    <span class="menu-title">--><?php //echo __('History'); ?><!--</span>-->
<!--                </a>-->
<!--            </li>-->
<!--            <li --><?php //echo ($current_route_name == 'water-tests') ? 'class="active"' : ''; ?><!-->-->
<!--                <a href="--><?php //echo URL::base(TRUE, TRUE) .'water-tests'; ?><!--" class="require-instance-id">-->
<!--                    <i class="fa fa-lg fa-fw fa-flask"></i>&nbsp;-->
<!--                    <span class="menu-title">--><?php //echo __('Water tests'); ?><!--</span>-->
<!--                </a>-->
<!--            </li>-->
<!--            <li --><?php //echo ($current_route_name == 'todos') ? 'class="active"' : ''; ?><!-->-->
<!--                <a href="--><?php //echo URL::base(TRUE, TRUE) .'todos'; ?><!--" class="require-instance-id">-->
<!--                    <i class="fa fa-lg fa-fw fa-check"></i>&nbsp;-->
<!--                    <span class="menu-title">--><?php //echo __('ToDo\'s'); ?><!--</span>-->
<!--                </a>-->
<!--            </li>-->
<!--            <li --><?php //echo ($current_route_name == 'instance') ? 'class="active"' : ''; ?><!-->-->
<!--                <a href="--><?php //echo URL::base(TRUE, TRUE) .'instance'; ?><!--" class="require-instance-id">-->
<!--                    <i class="fa fa-lg fa-fw fa-list-alt"></i>&nbsp;-->
<!--                    <span class="menu-title">--><?php //echo __('Instance') ?><!--</span>-->
<!--                </a>-->
<!--            </li>-->
<!--            <li  --><?php //echo ($current_route_name == 'logs') ? 'class="active"' : ''; ?><!-->-->
<!--                <a href="--><?php //echo URL::base(TRUE, TRUE) .'logs'; ?><!--">-->
<!--                    <i class="fa fa-lg fa-fw fa-file-text-o"></i>&nbsp;-->
<!--                    <span class="menu-title">--><?php //echo __('Logs'); ?><!--</span>-->
<!--                </a>-->
<!--            </li>-->
        </ul>
    </nav>
    <nav id="instances-menu">
        <h4>Instances</h4>
        <ul>
            <?php
            foreach($instances as $instance) {
                $class = ($current_instance_id === $instance['id']) ? 'active' : '';
                $instanceIcon = isset($instance['icon']) ? '<i class="'. $instance['icon'] .'"></i>' : '';
                ?>
                <li class="<?php echo $class ?>" data-id="<?php echo $instance['id'] ?>">
                    <a href="<?php echo URL::base(TRUE, TRUE) .'live/'. $instance['id'] ?>" class="instanceLink"><?php echo $instanceIcon .'&nbsp;'. $instance['title'] ?></a>
                    <ul>
                        <li>
                            <a href="<?php echo URL::base(TRUE, TRUE) .'live/'. $instance['id']; ?>">
                                <i class="fa fa-lg fa-fw fa-bar-chart-o"></i>&nbsp;
                                <span class="menu-title"><?php echo __('Live data') ?></span>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo URL::base(TRUE, TRUE) .'instance/'. $instance['id']; ?>">
                                <i class="fa fa-lg fa-fw fa-list-alt"></i>&nbsp;
                                <span class="menu-title"><?php echo __('Edit instance') ?></span>
                            </a>
                        </li>
                    </ul>
                </li>
            <?php } ?>
            <li data-id="new">
                <a href="<?php echo URL::base(TRUE, TRUE) .'instance/new' ?>"><i class="fa fa-plus-circle" aria-hidden="true"></i>Create an instance</a>
            </li>
        </ul>
    </nav>
    <span id="minify-menu"><i class="fa fa-arrow-circle-left hit"></i></span>
</aside>
<div id="main">
    <!--[if lt IE 7]>
    <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->

    <div id="content">
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <h1><i class="fa fa-lg fa-fw <?php echo $icon; ?>"></i> <?php echo $title; ?></h1>
            </div>
        </div>
        <?php echo $content; ?>
    </div>
</div>
<footer id="footer"></footer>

<script type="text/x-handlebars-template" id="dialog-tmpl">
    <div class="modal fade" id="dialog" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">{{title}}</h4>
                </div>
                <div class="modal-body">
                    {{body}}
                </div>
                {{footer}}
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
</script>

<script type="text/javascript">var BASE_URL = "<?php echo URL::base(TRUE, TRUE); ?>";</script>

<?php foreach($scripts as $file) { echo HTML::script($file), "\n"; }?>

    <script  type="text/javascript">
        <?php if( $translations && count($translations) ) { ?>
        var I18n = {
        <?php foreach($translations as $code => $text) { ?>
            <?php echo $code ?> : "<?php echo $text ?>",
        <?php } ?>
        };
        <?php } ?>

        // TODO Find a better place to put this code
        var WidgetList = {
            'widget-form': ES.WidgetForm,
            'widget-todos': ES.WidgetTodos,
            'widget-waterTests': ES.WidgetWaterTests,
            'widget-instances': ES.WidgetInstances,
            'widget-params': ES.WidgetInstanceParams,
            'widget-history': ES.WidgetHistory,
            'widget-livedata': ES.WidgetLive,
            'widget-logs': ES.WidgetLogs
        };

        $(document).ready(function() {
            $(".widget").each(function() {
                var widget = WidgetList[$(this).attr('id')];
                if( widget ) {
                    ES.factory(WidgetList[$(this).attr('id')]);
                }
            });
        });
    </script>

</body>
</html>
