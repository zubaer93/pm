<?php

use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;

Router::defaultRouteClass(DashedRoute::class);

Router::plugin('Api', ['path' => '/api'], function (RouteBuilder $routes) {
    $symbol_expression = '[\w- ^ . , $ ! & ( ) * ; : < = > @ { } ~]+';  //!#$%&'()*+,-./:;<=>?@[\]^_`{|}~
    $routes->connect('/auth/login', ['controller' => 'auth', 'action' => 'login', 'allowWithoutToken' => true], ['routeClass' => 'Api.LangRoute']);


    $routes->connect('/auth/social-login', ['controller' => 'auth', 'action' => 'socialLogin', 'allowWithoutToken' => true], ['routeClass' => 'Api.LangRoute']);

    $routes->connect('/auth/logout', ['controller' => 'auth', 'action' => 'logout', 'allowWithoutToken' => true], ['routeClass' => 'Api.LangRoute']);

    $routes->connect(
        '/auth/change-password',
        ['controller' => 'auth', 'action' => 'changePassword'], ['routeClass' => 'Api.LangRoute']);


    $routes->connect('/auth/register', ['controller' => 'auth', 'action' => 'register', 'allowWithoutToken' => true], ['routeClass' => 'Api.LangRoute']);
    $routes->connect('/auth/forget-password', ['controller' => 'auth', 'action' => 'forgetPassword', 'allowWithoutToken' => true], ['routeClass' => 'Api.LangRoute']);
    $routes->connect('/auth/reset-password', ['controller' => 'auth', 'action' => 'resetPassword', 'allowWithoutToken' => true], ['routeClass' => 'Api.LangRoute']);

    $routes->connect('/auth/activate-user', ['controller' => 'auth', 'action' => 'activateUser', 'allowWithoutToken' => true], ['routeClass' => 'Api.LangRoute']);

    $routes->connect('/auth/resend-email-activation',
        ['controller' => 'auth', 'action' => 'resendEmailActivation', 'allowWithoutToken' => true],
        ['routeClass' => 'Api.LangRoute']);

    $routes->connect('/auth/refresh-token', ['controller' => 'auth', 'action' => 'refreshToken'], ['routeClass' => 'Api.LangRoute']);


    $routes->connect('/users/check-email', ['controller' => 'auth', 'action' => 'checkEmail', 'allowWithoutToken' => true], ['routeClass' => 'Api.LangRoute']);
    $routes->connect('/users/check-phone', ['controller' => 'auth', 'action' => 'checkPhone', 'allowWithoutToken' => true], ['routeClass' => 'Api.LangRoute']);

    $routes->connect('/auth/avatar', ['controller' => 'auth', 'action' => 'editAvatar'], ['routeClass' => 'Api.LangRoute']);
    $routes->connect('/auth/video', ['controller' => 'auth', 'action' => 'uploadVideo'], ['routeClass' => 'Api.LangRoute']);
    $routes->connect('/auth/get-avatar', ['controller' => 'auth', 'action' => 'getAvatar'], ['routeClass' => 'Api.LangRoute']);
    $routes->connect('/auth/get-video', ['controller' => 'auth', 'action' => 'getVideo'], ['routeClass' => 'Api.LangRoute']);

    // users route
    $routes->connect('/users/me', ['controller' => 'users', 'action' => 'me', '[method]' => ['GET', 'OPTIONS']], ['routeClass' => 'Api.LangRoute']);
    $routes->connect('/users/me', ['controller' => 'users', 'action' => 'edit', '[method]' => ['POST', 'OPTIONS']], ['routeClass' => 'Api.LangRoute']);
    $routes->connect('/users/all', ['controller' => 'users', 'action' => 'userList', '[method]' => ['OPTIONS', 'GET']], ['routeClass' => 'Api.LangRoute']);
    $routes->connect('/users/get-user', ['controller' => 'users', 'action' => 'getUser', '[method]' => ['OPTIONS', 'GET']], ['routeClass' => 'Api.LangRoute']);
    $routes->connect('/users/verify-profile', 
        ['controller' => 'users', 'action' => 'uploadDocuments', '[method]' => ['POST', 'OPTIONS']], 
        ['routeClass' => 'Api.LangRoute']);
    $routes->fallbacks(DashedRoute::class);
}
);
