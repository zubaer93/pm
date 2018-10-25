<?php

namespace Api\Utility;

use Cake\Core\Configure;
use Cake\Network\Request;
use Cake\Network\Response;
use Cake\ORM\TableRegistry;

/**
 * Handles the request logging.
 */
class ApiRequestLogger
{

    /**
     * Logs the request and response data into database.
     *
     * @param Request  $request  The \Cake\Network\Request object
     * @param Response $response The \Cake\Network\Response object
     */
    public static function log(Request $request, Response $response)
    {
        Configure::write('requestLogged', true);

        try {
            $apiRequests = TableRegistry::get('RestApi.ApiRequests');
            $entityData = [
                'http_method' => $request->method(),
                'endpoint' => $request->here(),
                'token' => Configure::read('accessToken'),
                'ip_address' => $request->clientIp(),
                'request_data' => json_encode($request->data),
                'response_code' => $response->statusCode(),
                'response_type' => Configure::read('ApiRequest.responseType'),
                'response_data' => $response->body(),
                'exception' => Configure::read('apiExceptionMessage'),
            ];
            $entity = $apiRequests->newEntity($entityData);
            $apiRequests->save($entity);
        } catch (\Exception $e) {
            return;
        }
    }
}
