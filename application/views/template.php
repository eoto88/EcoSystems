<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $title; ?></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="shortcut icon" href="favicon.ico" />

    <script src="assets/js/vendor/modernizr-2.6.2.min.js"></script>
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
    <?php foreach($styles as $file => $type) { echo HTML::style($file, array('media' => $type)), "\n";}?>
</head>
<body>
<!--[if lt IE 7]>
<p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
<![endif]-->

<header id="header">
    <div id="header_left">
        <h1><?php echo $title; ?></h1>
    </div>
    <div id="header_right">
        <?php
        var_dump($day);
        $sun_status = "";
        if(isset($day['sunrise'])) {
            $sun_status = "Sunrise: ". $day['sunrise'];
        }
        if(isset($day['sunset'])) {
            $sun_status .= " | Sunset: ". $day['sunset'];
        }
        ?>
        <span id="still_alive" title="Last communication: 2014-07-29 21:35:24"><i class="fa fa-exclamation-triangle error"></i></span><span id="sun_status" title="<?php echo $sun_status; ?>"><i class="fa fa-sun-o"></i></span>
    </div>
    <div class="clearfix"></div>
</header>

<div id="content">
    <?php echo $content; ?>
</div>

<footer id="footer"></footer>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="assets/js/vendor/jquery-1.10.2.min.js"><\/script>')</script>

<?php foreach($scripts as $file) { echo HTML::script($file), "\n"; }?>

<!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
<script>
    (function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
        function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
        e=o.createElement(i);r=o.getElementsByTagName(i)[0];
        e.src='//www.google-analytics.com/analytics.js';
        r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
    ga('create','UA-XXXXX-X');ga('send','pageview');
</script>
</body>
</html>