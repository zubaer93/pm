<?php

namespace Api\Controller;

use Cake\Event\Event;

/**
 * Api Controller
 *
 * Provides basic functionality for building REST APIs
 */
class RestApiController extends RestAppController
{

    /**
     * Before render callback.
     *
     * @param Event $event The beforeRender event.
     * @return \Cake\Network\Response|null
     */
    public function beforeRender(Event $event)
    {
        $this->viewBuilder()->setClassName('Api.Api');

        return parent::beforeRender($event);
    }
}
