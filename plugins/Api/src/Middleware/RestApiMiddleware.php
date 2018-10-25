<?php

namespace Api\Middleware;

use Cake\Core\App;
use Cake\Core\Configure;
use Cake\Error\Middleware\ErrorHandlerMiddleware;
use Cake\Event\EventManager;
use Cake\Utility\Inflector;
use Api\Event\ApiRequestHandler;

class RestApiMiddleware extends ErrorHandlerMiddleware
{

    /**
     * Override ErrorHandlerMiddleware and add custom exception renderer
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request The request.
     * @param \Psr\Http\Message\ResponseInterface $response The response.
     * @param callable $next Callback to invoke the next middleware.
     * @return \Psr\Http\Message\ResponseInterface A response
     */
    public function __invoke($request, $response, $next)
    {

        try {
            $prefix = $this->getPrefix($request);
            if ($prefix == Configure::read('ApiRequest.prefix')) {
                Configure::write('App.namespace', Configure::read('ApiRequest.namespace'));
            }
            $params = (array)$request->getAttribute('params', []);
            if (isset($params['controller'])) {
                $controllerName = $params['controller'];
                $firstChar = substr($controllerName, 0, 1);
                if (strpos($controllerName, '\\') !== false ||
                    strpos($controllerName, '/') !== false ||
                    strpos($controllerName, '.') !== false ||
                    $firstChar === strtolower($firstChar)
                ) {
                    return $next($request, $response);
                }
                $type = 'Controller';
                if (isset($params['prefix']) && $params['prefix']) {
                    $prefix = Inflector::camelize($params['prefix']);
                    $type = 'Controller/' . $prefix;
                }
                $className = App::className($controllerName, $type, 'Controller');
                $controller = ($className) ? new $className() : null;
                if ($controller && is_subclass_of($controller, 'Api\Controller\RestApiController')) {
                    if (isset($this->renderer)) {
                        $this->renderer = 'Api\Error\ApiExceptionRenderer';
                    } else {
                        $this->exceptionRenderer = 'Api\Error\ApiExceptionRenderer';
                    }
                    EventManager::instance()->on(new ApiRequestHandler());
                }
                unset($controller);
            }

            return $next($request, $response);
        } catch (\Exception $e) {
            return $this->handleException($e, $request, $response);
        }
    }

    private function getPrefix($request)
    {
        return explode('/', $request->url)[0];
    }
}
