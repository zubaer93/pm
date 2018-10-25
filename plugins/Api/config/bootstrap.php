<?php

use Api\Middleware\RestApiMiddleware;
use Cake\Core\Plugin;
use Cake\Core\Configure;
use Cake\Event\EventManager;

EventManager::instance()->on('Server.buildMiddleware', function ($event, $middleware) {
    $middleware->add(new RestApiMiddleware());
});

/*
 * Read configuration file and inject configuration
 */
try {
    Configure::load('Api.api', 'default', true);
} catch (Exception $e) {
    // nothing
}

// Set default response format
if (!in_array(Configure::read('ApiRequest.responseType'), ['json', 'xml'])) {
    Configure::write('ApiRequest.responseType', 'json');
}
Plugin::load('Muffin/Trash');

Plugin::load('Language', ['bootstrap' => true, 'routes' => false]);