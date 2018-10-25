<?php

namespace Api\Controller;

use Cake\Core\Configure;
use Cake\Event\Event;

/**
 * Api error controller
 *
 * This controller will sets configuration to render errors
 */
class ApiErrorController extends RestAppController
{

    /**
     * beforeRender callback.
     *
     * @param Event $event Event.
     * @return null
     */
    public function beforeRender(Event $event)
    {
        $this->httpStatusCode = $this->response->statusCode();

        $messageArr = $this->response->httpCodes($this->httpStatusCode);

        if (Configure::read('ApiRequest.debug') && isset($this->viewVars['error'])) {
            $this->apiResponse[$this->responseFormat['messageKey']] = $this->viewVars['error']->getMessage();
        } else {
            $this->apiResponse[$this->responseFormat['messageKey']] = !empty($messageArr[$this->httpStatusCode]) ? $messageArr[$this->httpStatusCode] : 'Unknown error!';
        }

        Configure::write('apiExceptionMessage', isset($this->viewVars['error']) ? $this->viewVars['error']->getMessage() : null);

        parent::beforeRender($event);
        $this->viewBuilder()->setClassName('Api.ApiError');

        return null;
    }
}
