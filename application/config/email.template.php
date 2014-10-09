<?php

defined('SYSPATH') or die('No direct access allowed.');

return array(
    Kohana::DEVELOPMENT => array(
        'default' => array(
            'driver' => 'smtp',
            'port' => '587',
            'hostname' => 'smtp.domain.tld',
            'username' => 'example@domain.tld',
            'password' => '123456'
        )
    ),
    Kohana::PRODUCTION => array
        (
        'default' => array(
            'driver' => 'smtp',
            'hostname' => 'smtp.domain.tld',
            'username' => 'example@domain.tld',
            'password' => '123456'
        )
    ),
);
