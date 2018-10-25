<?php

namespace Api\Error;

use Api\Controller\ApiErrorController;
use Cake\Core\Configure;
use Cake\Error\ExceptionRenderer;
use Cake\Utility\Xml;
use Exception;
use Api\Routing\Exception\InvalidTokenException;
use Api\Routing\Exception\InvalidTokenFormatException;
use Api\Routing\Exception\MissingTokenException;

/**
 * API Exception Renderer.
 *
 * Captures and handles all unhandled exceptions. Displays valid json response.
 */
class ApiExceptionRenderer extends ExceptionRenderer
{

    /**
     * Returns error handler controller.
     *
     * @return ApiErrorController
     */
    protected function _getController()
    {
        return new ApiErrorController();
    }

    /**
     * Handles MissingTokenException.
     *
     * @param MissingTokenException $exception MissingTokenException
     *
     * @return string
     */
    public function missingToken($exception)
    {
        return $this->__prepareResponse($exception, ['customMessage' => true]);
    }

    /**
     * Handles InvalidTokenFormatException.
     *
     * @param InvalidTokenFormatException $exception InvalidTokenFormatException
     *
     * @return string
     */
    public function invalidTokenFormat($exception)
    {
        return $this->__prepareResponse($exception, ['customMessage' => true]);
    }

    /**
     * Handles InvalidTokenException.
     *
     * @param InvalidTokenException $exception InvalidTokenException
     *
     * @return string
     */
    public function invalidToken($exception)
    {
        return $this->__prepareResponse($exception, ['customMessage' => true]);
    }

    /**
     * Prepare response.
     *
     * @param Exception $exception Exception
     * @param array     $options   Array of options
     *
     * @return string
     */
    private function __prepareResponse($exception, $options = [])
    {
        $response = $this->_getController()->response;
        $code = $this->_code($exception);
        $response->statusCode($this->_code($exception));
        Configure::write('apiExceptionMessage', $exception->getMessage());

        $responseFormat = $this->_getController()->responseFormat;
        $body = [
            $responseFormat['statusKey'] => !empty($options['responseStatus']) ? $options['responseStatus'] : $responseFormat['statusNokText'],
            $responseFormat['resultKey'] => [
                $responseFormat['errorKey'] => ($code < 500) ? 'Not Found' : 'An Internal Error Has Occurred.',
            ],
        ];

        if ((isset($options['customMessage']) && $options['customMessage']) || Configure::read('ApiRequest.debug')) {
            $body[$responseFormat['resultKey']][$responseFormat['errorKey']] = $exception->getMessage();
        }

        if ('xml' === Configure::read('ApiRequest.responseType')) {
            $response->type('xml');
            $response->body(Xml::fromArray([Configure::read('ApiRequest.xmlResponseRootNode') => $body], 'tags')->asXML());
        } else {
            $response->type('json');
            $response->body(json_encode($body));
        }

        $this->controller->response = $response;
        return $response;
    }
}
