<?php defined('SYSPATH') OR die('No direct access allowed.');

return array(

    'driver'       => 'ES',
	'hash_method'  => 'sha256',
	'hash_key'     => NULL,
	'lifetime'     => 1209600,
    'session_type' => 'cookie',
    'session_key'  => 'auth_user'

);
