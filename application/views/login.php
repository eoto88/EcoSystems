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
    <meta name="robots" content="noindex, nofollow">

    <link rel="shortcut icon" href="favicon.ico" />

    <script src="assets/js/vendor/modernizr-2.6.2.min.js"></script>
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
    <?php foreach($styles as $file => $type) { echo HTML::style($file, array('media' => $type)), "\n";}?>
</head>
<body>
<!--[if lt IE 7]>
<p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
<![endif]-->

<div id="header">
    <div class="clearfix"></div>
    <h1><?php echo $title; ?></h1>
</div>
<div id="login">
    <form action="<?php echo URL::base(TRUE, TRUE) . 'login'; ?>" method="POST">
        Email:<br />
        <input type="text" name="email" /><br />
        Password:<br />
        <input type="password" name="password" /><br />
        <input type="submit" value="Sign in" />
    </form>
</div>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="assets/js/vendor/jquery-1.10.2.min.js"><\/script>')</script>

<?php foreach($scripts as $file) { echo HTML::script($file), "\n"; }?>

</body>
</html>