<?php
/**
 * Users configuration
 */
use Cake\Core\Configure;
use Cake\Routing\Router;
use Cake\View\Helper\UrlHelper;

$config = [
    'Users' => [
        //Table used to manage users
        'table' => 'AppUsers',
        'controller' => 'AppUsers',
    ],
    'OAuth' => [
        'providers' => [
            'facebook' => [
                'options' => [
                    //'clientId' => '1926385430724832',
                    'clientId' => '277352429688595',
                    //'clientSecret' => '25432329b5fbc529a2ba13bbde2af8ad',
                    'clientSecret' => '5ab9566c491c0d70a5f4296a04c42e4f',
                ]
            ],
            'google' => [
                'options' => [
                    'clientId' => '1025658297820-q749o6te04l4nlirpc68gs2nab4r8ose.apps.googleusercontent.com',
                    'clientSecret' => '0xExt50Tf9w63NaMlYr3hYLx',
                ]
            ]
        ]
    ],
    'Auth' => [
        // Error: A named route was found for "login", but matching failed.
       // 'logoutRedirect' =>  array('_name' => 'login'),
       // 'loginAction' => array('_name' => 'login')

        'logoutRedirect' =>  '/USD/login',
        'loginAction' => '/USD/login'
    ],
];

return $config;