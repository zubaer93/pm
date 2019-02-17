<?php

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Routing\Router;

class SubscriptionComponent extends Component
{
    /**
     * beforeFilter method Event to be called before the beforeFilter from controllers
     *
     * @param Cake\Event\Event $event Event class
     * @return void
     */
    public function beforeFilter(Event $event)
    {
        if (!$this->__isRouteAvailable($this->_registry->getController()->request->here())) {
            $this->request->session()->write('subscribe', __('You need to upgrade your plan to see this feature'));
            return $this->_registry->getController()->redirect(['_name' => 'subscribe']);
        }
    }

    /**
     * isRouteAvailable method it will check if the user can see this url, based in their subscription.
     *
     * @param array $url Url to be checked if its available
     * @param array $options Url options
     * @return bool
     */
    private function __isRouteAvailable($url, $options = [])
    {
        $user = $this->request->session()->read('Auth.User');
        if (empty($user)) {
            $accountType = 'FREE';
        } else {
            $user = (object)$user;
            $accountType = isset($user->account_type) ? strtoupper($user->account_type) : 'FREE';
            if (empty($accountType)) {
                $accountType = 'FREE';
            }
        }

        if ($url === '/') {
            return true;
        }

        $parsedUrl = Router::url($url, $options);
        $parsedUrl = str_replace('USD', '', $parsedUrl);
        $parsedUrl = str_replace('JMD', '', $parsedUrl);
        $parsedUrl = str_replace('//', '/', $parsedUrl);
        if (preg_match('/admin/', $parsedUrl)) {
            return true;
        }

        $matchedRoute = Router::url($url, $options);
        try {
            $router = Router::parse($matchedRoute);
        } catch (\Cake\Routing\Exception\MissingRouteException $e) {
            return false;
        }
        $subscriptionsConfig = Configure::read('subscriptions.' . $accountType);

        $matchedRoute = $router['_matchedRoute'];

        $matchedRoute = str_replace('/:lang', '', $matchedRoute);

        if (empty($matchedRoute)) {
            return true;
        }

        if (!in_array($matchedRoute, $subscriptionsConfig)) {
            return false;
        }

        return true;
    }
}