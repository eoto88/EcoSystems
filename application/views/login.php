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
        <?php
        if ($login_messages) {
            echo '<ul id="login-error-messages" class="error-messages">';
            foreach($login_messages as $key => $value) {
                echo '<li data-fieldnameerror="'. $key .'">'. $value .'</li>';
            }
            echo '</ul>';
        }
        ?>
        <input type="hidden" name="action" value="login" />
        <div class="form-field">
            <label>Username:</label>
            <input type="text" name="username" />
        </div>
        <div class="form-field password-field">
            <label>Password:</label>
            <input type="password" id="login-password" name="password" />
            <div class="show-password-wrap">
                <input name="show-password" type="checkbox" id="show-login-password" class="chk-show-password" role="checkbox" aria-checked="false" value="1" />
                <label for="show-password" title="Show Password">Show</label>
            </div>
        </div>
        <input type="submit" value="Sign in" />&nbsp;or <a href="javascript:void(0)" id="link-create-account">Create an account</a>
    </form>
</div>

<div id="create-account">
    <form action="<?php echo URL::base(TRUE, TRUE) . 'login'; ?>" method="POST">
        <?php
        if ($create_account_messages) {
            echo '<ul id="create-account-error-messages" class="error-messages">';
            foreach($create_account_messages as $key => $value) {
                echo '<li data-fieldnameerror="'. $key .'">'. $value .'</li>';
            }
            echo '</ul>';
        }
        ?>
        <input type="hidden" name="action" value="create-account" />
        <div class="form-field">
            <label>Username:</label>
            <input type="text" name="username" />
        </div>
        <div class="form-field">
            <label>Name:</label>
            <input type="text" name="name" />
        </div>
        <div class="form-field">
            <label>Email:</label>
            <input type="text" name="email" />
        </div>
        <div class="form-field password-field">
            <label>Password:</label>
            <input type="password" id="create-account-password" name="password" />
            <div class="show-password-wrap">
                <input name="show-password" type="checkbox" id="show-create-account-password" class="chk-show-password" role="checkbox" aria-checked="false" value="1" style="-webkit-user-select: none;">
                <label for="show-password" title="Show Password">Show</label>
            </div>
        </div>
        <input type="submit" id="create-account-button" value="Create account" /> <input type="button" id="cancel-button" class="button" value="Cancel" />
    </form>
</div>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="assets/js/vendor/jquery-1.10.2.min.js"><\/script>')</script>

<?php foreach($scripts as $file) { echo HTML::script($file), "\n"; }?>

</body>
</html>