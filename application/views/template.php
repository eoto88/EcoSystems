<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $title; ?></title>
    <meta name="description" content="">
    <meta name="robots" content="noindex, nofollow">

    <link rel="shortcut icon" href="favicon.ico" />

    <script src="<?php echo URL::base(TRUE, TRUE); ?>assets/js/vendor/modernizr-2.6.2.min.js"></script>
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,300,400,700">
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
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
    <div id="header-container"></div>
</header>
<aside id="left-panel">
    <nav>
        <ul>
            <li class="active">
                <a href="<?php echo URL::base(TRUE, TRUE); ?>"><i class="fa fa-lg fa-fw fa-tachometer"></i><?php echo __('Dashboard'); ?></a>
            </li>
            <li class="parent">
                <a href="#">
                    <i class="fa fa-lg fa-fw fa-list-alt"></i>&nbsp;<span class="menu-item-parent"><?php echo __('Instances') ?></span><b class="collapse-sign"><em class="fa fa-plus-square-o"></em></b>
                </a>
                <ul>
                    <?php foreach($instances as $instance) { ?>
                        <li id="<?php echo $instance['id_instance'] ?>">
                            <a href="<?php echo URL::base(TRUE, TRUE) .'live/'. $instance['id_instance'] ?>"><?php echo $instance['title'] ?></a>
                        </li>
                    <?php } ?>
                </ul>
            </li>
            <li>
                <a href="<?php echo URL::base(TRUE, TRUE) .'live'; ?>"><i class="fa fa-lg fa-fw fa-bar-chart-o"></i><?php echo __('Live'); ?></a>
            </li>
            <li>
                <a href="<?php echo URL::base(TRUE, TRUE) .'history'; ?>"><i class="fa fa-lg fa-fw fa-history"></i><?php echo __('History'); ?></a>
            </li>
            <li>
                <a href="<?php echo URL::base(TRUE, TRUE) .'todos'; ?>"><i class="fa fa-lg fa-fw fa-check"></i><?php echo __('ToDo\'s'); ?></a>
            </li>
            <li>
                <a id="logout" href="<?php echo URL::base(TRUE, TRUE) .'logout'; ?>"><i class="fa fa-lg fa-fw fa-sign-out"></i><?php echo __('Logout'); ?></a>
            </li>
        </ul>
    </nav>
</aside>
<div id="main">
    <!--[if lt IE 7]>
    <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->

    <div id="content">
        <?php echo $content; ?>
    </div>
</div>
<footer id="footer"></footer>


<div id="wrapper">
    <!-- Navigation -->
    <div id="page-wrapper">
        <!--nav id="headerWrapper" class="navbar navbar-default navbar-fixed-top" role="navigation">
            <header id="header" class="container">
                <div class="row">
                    <div class="col-md-4">
                        <h1><?php echo $title; ?></h1>
                    </div>
                    <div class="col-md-4">
                        <div id="tasks_wrapper">
                            <span><?php echo __('Task list'); ?> <i class="fa fa-list"></i></span>
                            <ul id="tasks_list">
                                <?php
                                if(count($toDos)) {
                                    foreach($toDos as $toDo) {
                                        echo '<li id="todo-'. $toDo['id_todo'] .'"><i class="fa fa-square-o check"></i>'. $toDo['title'] .'</li>';
                                    }
                                } else {
                                    echo '<li id="no-todo">'. __('No task in the to do list') .'</li>';
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div id="liveStatus">
                            <?php echo $communication_status; ?>
                            <?php echo $sun_status; ?>
                            <?php echo $pump_status; ?>
                            <?php echo $light_status; ?>
                            <?php echo $fan_status; ?>
                            <?php echo $heater_status; ?>
                        </div>
                        <ul id="menu" class="nav navbar-nav">
                            <li><a href="<?php echo URL::base(TRUE, TRUE); ?>"><i class="fa fa-tachometer"></i><br /><?php echo __('Dashboard'); ?></a></li>
                            <li><a href="<?php echo URL::base(TRUE, TRUE) .'history'; ?>"><i class="fa fa-history"></i><br /><?php echo __('History'); ?></a></li>
                            <li><a id="logout" href="<?php echo URL::base(TRUE, TRUE) .'logout'; ?>"><i class="fa fa-sign-out"></i><br /><?php echo __('Logout'); ?></a></li>
                        </ul>
                    </div>
                </div>
            </header>
        </nav-->
    </div>
</div>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="assets/js/vendor/jquery-1.10.2.min.js"><\/script>')</script>
<script type="text/javascript">var BASE_URL = "<?php echo URL::base(TRUE, TRUE); ?>";</script>

<?php foreach($scripts as $file) { echo HTML::script($file), "\n"; }?>

<?php if( $translations && count($translations) ) { ?>
<script  type="text/javascript">
    var translations = {
    <?php foreach($translations as $code => $text) { ?>
        <?php echo $code ?> : "<?php echo $text ?>",
    <?php } ?>
    };
    </script>
<?php } ?>

</body>
</html>