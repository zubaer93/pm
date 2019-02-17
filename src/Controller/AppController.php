<?php

/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */

namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Utility\Hash;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link http://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{

    const SUBDOMAIN_STOCKGITTER = 'stockgitter';
    const STAGE_SUBDOMAIN_STOCKGITTER = 'stage';

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');
        $this->loadComponent('CakeDC/Users.UsersAuth');
        $this->loadComponent('Search.Prg');
        $this->loadComponent('Auth');
        $this->loadComponent('Subscription');
        $this->Auth->allow();
        $this->Auth->deny('socialLogin');
        /*
         * Enable the following components for recommended CakePHP security settings.
         * see http://book.cakephp.org/3.0/en/controllers/components/security.html
         */
        // $this->loadComponent('Security');
        if (!$this->_isAdminSite()) {
            $this->loadComponent('Csrf');
        }

        // $this->loadModel('AppUsers');
        // $user = $this->AppUsers->get('1eeedfac-4751-4640-92a2-94cdad90f0de');
        // $this->Auth->setUser($user);

        $this->_setClientGetStream();
    }

    /**
     * Before render callback.
     *
     * @param \Cake\Event\Event $event The beforeRender event.
     * @return \Cake\Network\Response|null|void
     */
    public function beforeRender(Event $event)
    {
        if (!array_key_exists('_serialize', $this->viewVars) &&
            in_array($this->response->type(), ['application/json', 'application/xml'])
        ) {
            $this->set('_serialize', true);
        }
        $this->_setTheme();
        $this->_setAuthUser();
        $this->_setCurrentLanguage();
        $this->_getDomain();

        if ($this->_isAdminSite() && !$this->_isAdmin()) {
            $this->redirect('/');
        }
    }

    /**
     * Set authUser notifications variable based on the user who logged in
     *
     * @return void
     */
    protected function _setNotifications()
    {
        if (!is_null($this->Auth->user())) {
            $this->loadModel('Notifications');
            $notifications = $this->Notifications->find('all')
                ->order(['seen ASC'])
                ->order(['created_at DESC'])
                ->where(['user_id' => $this->Auth->user()["id"]]);

            $count_unread_notifications = $this->Notifications->find('all')
                ->where(['user_id' => $this->Auth->user('id'), 'seen' => 0])->count();
            $this->set('notifications', $notifications);
            $this->set('count_unread_notifications', $count_unread_notifications);
        }
    }

    /**
     * Set authUser variable based on the user who logged in
     *
     * @return void
     */
    protected function _setAuthUser()
    {
        $this->loadComponent('CakeDC/Users.UsersAuth');
        $user = $this->Auth->user();
        $this->set('authUser', $user);

        $accountType = 'FREE';
        if (!empty($user)) {
            if (!is_object($user)) {
                $user = (object)$user;
            }
            $accountType = isset($user->account_type) ? $user->account_type : 'FREE';
        }

        $this->set('accountType', $accountType);

    }

    /**
     * Set settings variable to config the project.
     *
     * @return void
     */
    protected function _setSettings()
    {
        $this->loadModel('Settings');
        $this->set('settings', $this->Settings->find()->first());
    }

    /**
     * Sets the trending companies based on the most viewed companies
     *
     * @return void
     */
    protected function _setTrendingCompanies()
    {
        $this->loadModel('Companies');
        $trendingCompanies = $this->Companies->getTrendingCompanies($this->_getCurrentLanguage());
        $this->set('trendingCompanies', $trendingCompanies);
    }

    /**
     * Sets the theme based on the section to render
     *
     * @return void
     */
    protected function _setTheme()
    {
        $this->viewBuilder()->theme('FrontendTheme');
        if ($this->_isAdminSite()) {
            $this->viewBuilder()->theme('AdminTheme');
        }
    }

    /**
     * Sets the theme based on the section to render
     *
     * @return void
     */
    protected function _setPages()
    {
        $this->loadModel('Pages');
        $pages = $this->Pages->find('all')
            ->order(['position ASC'])
            ->where(['enable' => 0]);
        $this->set('pages', $pages);
    }

    /**
     * Set client to GetStream API
     *
     * @return void
     */
    protected function _setClientGetStream()
    {
        $client = new \GetStream\Stream\Client('7gh9cjwwbv5w', '6x9fg8hc3pwbsztzsvyj88pgeevbjfp99ujdyk7xj4nun7hnkxtrtrjthumsd2by');
        Configure::write('client_getstream', $client);
    }

    /**
     * Returns bool when the is the admin site
     *
     * @return bool
     */
    protected function _isAdminSite()
    {
        $p = $this->request->params;
        return isset($p['prefix']) && $p['prefix'] === 'admin' ||
            $p['plugin'] == 'Cms' && !($p['controller'] == 'CmsPages' && $p['action'] == 'display');
    }

    /**
     * Returns true when user is admin
     *
     * @return bool
     */
    protected function _isAdmin()
    {
        if ($this->Auth->user() && $this->Auth->user('role') == 'admin') {
            return true;
        }

        return false;
    }

    /**
     * Return the current language in the website.
     *
     * @return string
     */
    protected function _getCurrentLanguage()
    {
        return Hash::get($this->request->params, 'lang');
    }

    /**
     * Return the current language id in the website.
     *
     * @return string
     */
    protected function _getCurrentLanguageId()
    {
        $this->loadModel('Countries');
        try {
            $query = $this->Countries->find('all')
                ->where(['market' => $this->_getCurrentLanguage()])
                ->first();
            $id = $query->id;
        } catch (\Exception $e) {
            $id = '';
        }
        return $id;
    }

    /**
     * Set current language
     *
     * @return void
     */
    protected function _setCurrentLanguage()
    {
        $currentLanguage = $this->_getCurrentLanguage();
        $this->set(compact('currentLanguage'));
    }

    protected function _siteConfig()
    {
        $subDomain = $this->_getCurrentSubDomain();
        $this->loadModel('Partners');

        $subData = $this->Partners->find()->where(['subdomain' => $subDomain])
            ->where(['enable' => 0])
            ->first();

        if (!is_null($subData)) {
            $sub = true;
            $main_color = $subData->main_color;
            $main_color_border = $subData->main_border_color;
            $logo_url = \App\Model\Service\Core::getPartnerImagePath($subData["logo_path"]);
        } else {
            $sub = false;
            $main_color = '#8ab933';
            $main_color_border = 'rgba(0,0,0,0.05)';
            $logo_url = '_smarty/stockgitter_header_logo.jpg';
        }

        $this->set(compact('logo_url', 'main_color', 'main_color_border', 'sub', 'subDomain'));
    }

    /**
     *
     * @return type
     */
    protected function _getCurrentSubDomain()
    {
        $url = $this->_getUrl();
        $url = parse_url($url, PHP_URL_HOST);
        $url = strstr(str_replace("www.", "", $url), ".", true);
        $subdomain = ($url != self::SUBDOMAIN_STOCKGITTER) ? $url : null;

        $this->set(compact('subdomain'));
        return $subdomain;
    }

    protected function _getUrl()
    {
        $url = @($_SERVER["HTTPS"] != 'on') ? 'http://' . $_SERVER["SERVER_NAME"] : 'https://' . $_SERVER["SERVER_NAME"];
        $url .= $_SERVER["REQUEST_URI"];
        return $url;
    }

    public function _getDomain()
    {
        $domain = @($_SERVER["HTTPS"] != 'on') ? 'http://' . $_SERVER["SERVER_NAME"] : 'https://' . $_SERVER["SERVER_NAME"];
        $this->set('domain', $domain);
    }

    protected function getSiteUrl()
    {
        $url = @($_SERVER["HTTPS"] != 'on') ? 'http://' . $_SERVER["SERVER_NAME"] : 'https://' . $_SERVER["SERVER_NAME"];
        return $url;
    }

}
