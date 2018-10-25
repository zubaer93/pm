<?php

/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

use Cake\Core\Plugin;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;

/**
 * The default class to use for all routes
 *
 * The following route classes are supplied with CakePHP and are appropriate
 * to set as the default:
 *
 * - Route
 * - InflectedRoute
 * - DashedRoute
 *
 * If no call is made to `Router::defaultRouteClass()`, the class used is
 * `Route` (`Cake\Routing\Route\Route`)
 *
 * Note that `Route` does not do any inflections on URLs which will result in
 * inconsistently cased URLs when used with `:plugin`, `:controller` and
 * `:action` markers.
 *
 */
Router::defaultRouteClass(DashedRoute::class);

Router::scope('/', function ($routes) {
    $routes->extensions(['json', 'xml']);

    $routes->connect('/', ['controller' => 'Pages', 'action' => 'display', 'home'], [
        '_name' => 'home',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/page/:name', ['controller' => 'Pages', 'action' => 'pages'], [
        'pass' => ['name'],
        '_name' => 'dynamic',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/market/prequalification', ['controller' => 'Pages', 'action' => 'prequalification'], [
        '_name' => 'market_prequalification',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/page/iframe/src', ['controller' => 'Pages', 'action' => 'getIframe'], [
            '_name' => 'iframe_link',
            'routeClass' => 'ADmad/I18n.I18nRoute'
        ]
    );
    $routes->connect('/page/notification/get', ['controller' => 'Pages', 'action' => 'getNotifications'], [
        '_name' => 'get_notification',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/financial-statements', ['controller' => 'FinancialStatement', 'action' => 'index'], [
        '_name' => 'financial_statements',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/financial-statements/:symbol', ['controller' => 'FinancialStatement', 'action' => 'symbol'], [
        'pass' => ['symbol'],
        '_name' => 'financial_statements_symbol',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/portfolio', [
        'controller' => 'Portfolio',
        'action' => 'index'
    ], [
        '_name' => 'portfolio',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/portfolio/save-preview', [
        'controller' => 'Portfolio',
        'action' => 'savePreview'
    ], [
        '_name' => 'save_preview',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/transactions', [
        'controller' => 'Portfolio',
        'action' => 'transaction'
    ], [
        '_name' => 'transaction',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/workflow/order', [
        'controller' => 'Portfolio',
        'action' => 'order'
    ], [
        '_name' => 'order',
        'routeClass' => 'ADmad/I18n.I18nRoute'

    ]);

    $routes->connect('/place-order/:id', [
        'controller' => 'Portfolio',
        'action' => 'placeOrder'
    ], [
        'pass' => ['id'],
        '_name' => 'place-order',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/transaction/cancel', [
        'controller' => 'Portfolio',
        'action' => 'transactionCancel'
    ], [
        '_name' => 'transaction_cancel',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/get-transactions-chart', [
        'controller' => 'Portfolio',
        'action' => 'transactionChart'
    ], [
        '_name' => 'get_transactions_chart',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/portfolio/get-currency-list', [
        'controller' => 'Portfolio',
        'action' => 'getCurrencyList'
    ], [
        '_name' => 'portfolio_get_currency_list',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/portfolio/get-broker-list', [
        'controller' => 'Portfolio',
        'action' => 'getBrokerList'
    ], [
        '_name' => 'portfolio_get_broker_list',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/portfolio/get-currency-rate', [
        'controller' => 'Portfolio',
        'action' => 'getCurrencyRate'
    ], [
        '_name' => 'portfolio_get_currency_rate',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/portfolio/get-company', [
        'controller' => 'Portfolio',
        'action' => 'getCompany'
    ], [
        '_name' => 'portfolio_get_company',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/transaction/edit/:id', [
        'controller' => 'Portfolio',
        'action' => 'portfolioEdit'
    ], [
        'pass' => ['id'],
        '_name' => 'portfolio_edit',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/portfolio/get-company-price', [
        'controller' => 'Portfolio',
        'action' => 'getCompanyPrice'
    ], [
        '_name' => 'portfolio_get_company_price',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/portfolio/get-broker-fee', [
        'controller' => 'Portfolio',
        'action' => 'getBrokerFee'
    ], [
        '_name' => 'portfolio_get_broker_fee',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/stocks/list/event', [
        'controller' => 'Companies',
        'action' => 'getEvents'
    ], [
        '_name' => 'event_list',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/stocks/list', [
        'controller' => 'Companies',
        'action' => 'stocksList'
    ], [
        '_name' => 'stocks_list',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/symbol/:stock', [
        'controller' => 'Companies',
        'action' => 'symbol'
    ], [
        'pass' => ['stock'],
        '_name' => 'symbol',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/options/:stock', [
        'controller' => 'Companies',
        'action' => 'options'
    ], [
        'pass' => ['stock'],
        '_name' => 'options',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/analysis/sector/:symbol', [
        'controller' => 'Analysis',
        'action' => 'sector'
    ], [
        'pass' => ['symbol'],
        '_name' => 'get_sector',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/analysis/sector/modal/:symbol', [
        'controller' => 'Analysis',
        'action' => 'sectorModal'
    ], [
        'pass' => ['symbol'],
        '_name' => 'get_modal_sector',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/analysis/industry/:symbol', [
        'controller' => 'Analysis',
        'action' => 'industry'
    ], [
        'pass' => ['symbol'],
        '_name' => 'get_industry',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/analysis/add/:symbol', [
        'controller' => 'Analysis',
        'action' => 'add'
    ], [
        'pass' => ['symbol'],
        '_name' => 'add_analysi',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/analysis/print', [
        'controller' => 'Analysis',
        'action' => 'analysisPrint'
    ], [
        '_name' => 'print_analysi',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/analysis/print-word', [
        'controller' => 'Analysis',
        'action' => 'analysisWord'
    ], [
        '_name' => 'print_analysis-doc',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/analysis/editPartial', [
        'controller' => 'Analysis',
        'action' => 'analysisEditPartial'
    ], [
        '_name' => 'analysis_partial',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/analysis/approve', [
        'controller' => 'Analysis',
        'action' => 'analysisApprove'
    ], [
        '_name' => 'analysis_approve',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/analysis/make-copy', [
        'controller' => 'Analysis',
        'action' => 'makeCopy'
    ], [
        '_name' => 'analysis_make_copy',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/analysis/share-with-team', [
        'controller' => 'Analysis',
        'action' => 'shareTeam'
    ], [
        '_name' => 'analysis_share_with_team',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/analysis/detail/:key', [
        'controller' => 'Analysis',
        'action' => 'analysisDetail'
    ], [
        'pass' => ['key'],
        '_name' => 'analysis_detail',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/analysis/add-modal', [
        'controller' => 'Analysis',
        'action' => 'addModal'
    ], [
        '_name' => 'add_modal_analysi',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/analysis/delete', [
        'controller' => 'Analysis',
        'action' => 'delete'
    ], [
        '_name' => 'delete_analysi',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/analysis/chart/:symbol', [
        'controller' => 'Analysis',
        'action' => 'getChartData'
    ], [
        'pass' => ['symbol'],
        '_name' => 'get_chart_data',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/analysis/all', [
        'controller' => 'Analysis',
        'action' => 'all'
    ], [
        'name' => 'all_analysi',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/analysis/:stock/*', [
        'controller' => 'Analysis',
        'action' => 'index'
    ], [
        'pass' => ['stock'],
        '_name' => 'analysis',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/watchlist/all', [
        'controller' => 'watchlist',
        'action' => 'showAll'
    ], [
        '_name' => 'watchlist_all',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/watchlist/delete/:id', [
        'controller' => 'watchlist',
        'action' => 'deleteWatchlist'
    ], [
        'pass' => ['id'],
        '_name' => 'watchlist_delete',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/watchlist/all/get/:group_id', [
        'controller' => 'watchlist',
        'action' => 'getWatchlistGroup'
    ], [
        'pass' => ['group_id'],
        '_name' => 'get_watchlist_group',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/watchlist/verify', [
        'controller' => 'watchlist',
        'action' => 'verifyWatchlist'
    ], [
        '_name' => 'verifyWatchlist',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/watchlist/create', [
        'controller' => 'watchlist',
        'action' => 'createWatchlist'
    ], [
        '_name' => 'createWatchlist',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/watchlist/edit', [
        'controller' => 'watchlist',
        'action' => 'editWatchlist'
    ], [
        '_name' => 'editWatchlist',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/watchlist/toggle', [
        'controller' => 'watchlist',
        'action' => 'toggleWatchList'
    ], [
        '_name' => 'toggleWatchList',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/watchlist/get', [
        'controller' => 'watchlist',
        'action' => 'getWatchlist'
    ], [
        '_name' => 'getWatchlist',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/watchlist/get-sub', [
        'controller' => 'watchlist',
        'action' => 'getSubWatchlist'
    ], [
        '_name' => 'getSubWatchlist',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/watchlist/get/all', [
        'controller' => 'watchlist',
        'action' => 'getWatchlistAll'
    ], [
        '_name' => 'getWatchlistAll',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/watchlist/stocks', [
        'controller' => 'companies',
        'action' => 'getStocksInfo'
    ], [
        '_name' => 'getStocksInfo',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/dashboard', [
        'controller' => 'Messages',
        'action' => 'dashboard'
    ], [
        '_name' => 'dashboard',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/forex', [
        'controller' => 'FX',
        'action' => 'index'
    ], [
        '_name' => 'forex',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/forex/symbol/:currency', [
        'controller' => 'FX',
        'action' => 'symbol'
    ], [
        'pass' => ['currency'],
        '_name' => 'forex_currency',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/bonds', [
        'controller' => 'Bonds',
        'action' => 'index'
    ], [
        '_name' => 'bonds',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/bonds/historial-price/:isinCode', [
        'controller' => 'Bonds',
        'action' => 'historical_price'
    ], [
        'pass' => ['isinCode'],
        '_name' => 'historical_price',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/getTrader', [
        'controller' => 'FX',
        'action' => 'getTraderJs'
    ], [
        '_name' => 'getTraderJs',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/getMessage/info', [
        'controller' => 'Messages',
        'action' => 'getMessageInfo'
    ], [
        '_name' => 'getMessageInfo',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/post/delete', [
        'controller' => 'Messages',
        'action' => 'deletePost'
    ], [
        '_name' => 'delete_post_user',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/message/read-notification', [
        'controller' => 'Messages',
        'action' => 'readNotification'
    ], [
        '_name' => 'read_notification',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/login', [
        'controller' => 'AppUsers',
        'action' => 'login'
    ], [
        '_name' => 'login',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/register', [
        'controller' => 'AppUsers',
        'action' => 'register'
    ], [
        '_name' => 'register',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/subscribe', [
        'controller' => 'AppUsers',
        'action' => 'subscribe'
    ], [
        '_name' => 'subscribe',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/subscription', [
        'controller' => 'AppUsers',
        'action' => 'subscription'
    ], [
        '_name' => 'subscription',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    //for solve after register
    $routes->connect('/login', ['controller' => 'AppUsers', 'action' => 'login']);

    $routes->connect('/profile', ['controller' => 'AppUsers', 'action' => 'profile']);

    $routes->connect('/logout', [
        'plugin' => 'CakeDC/Users',
        'controller' => 'Users',
        'action' => 'logout'
    ], [
        '_name' => 'logout',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/profile/edit/:id', [
        'controller' => 'AppUsers',
        'action' => 'editPost'
    ], [
        '_name' => 'edit',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/profile/edit/simulation/:id', [
        'controller' => 'AppUsers',
        'action' => 'editSimulation'
    ], [
        '_name' => 'edit_simulation',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/profile/charts', [
        'controller' => 'AppUsers',
        'action' => 'newsfeed'
    ], [
        '_name' => 'charts',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/profile', [
        'controller' => 'AppUsers',
        'action' => 'profile'
    ], [
        '_name' => 'profile',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/profile/comments', [
        'controller' => 'AppUsers',
        'action' => 'comment'
    ], [
        '_name' => 'comment',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/profile/private-post', [
        'controller' => 'Private',
        'action' => 'privatePost'
    ], [
        '_name' => 'private_post',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/profile/alerts', [
        'plugin' => false,
        'controller' => 'AppUsers',
        'action' => 'alerts'
    ], [
        '_name' => 'alerts',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/private/:room', [
        'controller' => 'Private',
        'action' => 'privateRoom'
    ], [
        'pass' => ['room'],
        '_name' => 'private_room',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/private/edit/:edit', [
        'controller' => 'Private',
        'action' => 'privateEdit'
    ], [
        'pass' => ['edit'],
        '_name' => 'private_edit',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/profile/private_save', [
        'controller' => 'Private',
        'action' => 'privateSave'
    ], [
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/profile/delete_private_room', [
        'controller' => 'Private',
        'action' => 'privateDeleteRoom'
    ], [
        '_name' => 'delete_private_room',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/profile/private-edit', [
        'controller' => 'Private',
        'action' => 'editPost'
    ], [
        '_name' => 'post_edit',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/profile/settings', [
        'controller' => 'AppUsers',
        'action' => 'settings'
    ], [
        '_name' => 'settings',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/simulations', [
        'controller' => 'Portfolio',
        'action' => 'simulations'
    ], [
        '_name' => 'all-simulations',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/simulations/chart', [
        'controller' => 'Portfolio',
        'action' => 'simulationsChart'
    ], [
        '_name' => 'get_chart_simulation',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/simulations/delete', [
        'controller' => 'Portfolio',
        'action' => 'simulationDelete'
    ], [
        '_name' => 'delete_simulation',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/connect', [
        'controller' => 'Connect',
        'action' => 'index'
    ], [
        '_name' => 'connect_page',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/profile/simulation', [
        'controller' => 'AppUsers',
        'action' => 'simulation'
    ], [
        '_name' => 'simulation',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/follow/user', [
        'controller' => 'AppUsers',
        'action' => 'follow'
    ], [
        '_name' => 'follow_user',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/messages', [
        'controller' => 'Messages',
        'action' => 'index'
    ], [
        '_name' => 'messages',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/message/comments', [
        'controller' => 'Messages',
        'action' => 'getPostComment'
    ], [
        '_name' => 'get_post_comments',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/message/add/comments', [
        'controller' => 'Messages',
        'action' => 'addComment'
    ], [
        '_name' => 'add_post_comments',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/message/comment/count', [
        'controller' => 'Messages',
        'action' => 'getPostCommentsCount'
    ], [
        '_name' => 'count_comment',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/refresh-messages', [
        'controller' => 'Messages',
        'action' => 'refreshMessages'
    ], [
        '_name' => 'refresh_messages',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/refresh-comment', [
        'controller' => 'Messages',
        'action' => 'refreshComment'
    ], [
        '_name' => 'refresh_comment',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/add/reting', [
        'controller' => 'Messages',
        'action' => 'addReting'
    ], [
        '_name' => 'add_reting',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/news', [
        'controller' => 'News',
        'action' => 'news'
    ], [
        '_name' => 'all_news',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/news/:slug', [
        'controller' => 'News',
        'action' => 'index'
    ], [
        'pass' => ['slug'],
        '_name' => 'news',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/fetch/:stock', [
        'controller' => 'Companies',
        'action' => 'getStock'
    ], [
        'pass' => ['stock'],
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/search', [
        'controller' => 'Companies',
        'action' => 'search',
    ], [
        '_name' => 'companies_search',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/user/:username', [
        'plugin' => false,
        'controller' => 'AppUsers',
        'action' => 'publicProfile'
    ], [
        'pass' => ['username'],
        '_name' => 'user_name',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/about-us', [
        'plugin' => false,
        'controller' => 'Pages',
        'action' => 'display',
        'about-us'
    ], [
        '_name' => 'about-us',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/contact-us', [
        'plugin' => false,
        'controller' => 'Contacts',
        'action' => 'index'
    ], [
        '_name' => 'contact-us',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/markets', [
        'plugin' => false,
        'controller' => 'Pages',
        'action' => 'display',
        'markets'
    ], [
        '_name' => 'markets',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/request-password', [
        'controller' => 'AppUsers',
        'action' => 'requestResetPassword'
    ], [
        'pass' => ['token'],
        '_name' => 'requestResetPassword',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/reset-password', [
        'controller' => 'AppUsers',
        'action' => 'resetPassword'
    ], [
        '_name' => 'resetPassword',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/validate-email', [
        'controller' => 'AppUsers',
        'action' => 'validate'
    ], [
        '_name' => 'validate',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/activation', [
        'controller' => 'AppUsers',
        'action' => 'activation'
    ], [
        '_name' => 'activation',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/ipo/interesting/:companyId', [
        'plugin' => false, 'controller' => 'Ipo', 'action' => 'interest'
    ], [
        'pass' => ['companyId'],
        '_name' => 'ipo-interesting',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/ipo/notinteresting/:interestId', [
        'plugin' => false,
        'controller' => 'Ipo',
        'action' => 'notInterest'
    ], [
        'pass' => ['interestId'],
        '_name' => 'ipo-not-interesting',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/ipo/:market', [
        'plugin' => false,
        'controller' => 'Ipo',
        'action' => 'index'
    ], [
        'pass' => ['market'],
        '_name' => 'ipo-market',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/ipo/:market/:company', [
        'plugin' => false,
        'controller' => 'Ipo',
        'action' => 'index'
    ], [
        'pass' => ['market', 'company'],
        '_name' => 'ipo-company',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/research/:market', [
        'plugin' => false,
        'controller' => 'Research',
        'action' => 'index'
    ], [
        'pass' => ['market'],
        '_name' => 'research-market',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/research/:market/:company', [
        'plugin' => false,
        'controller' => 'Research',
        'action' => 'index'
    ], [
        'pass' => ['market', 'company'],
        '_name' => 'research-company',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    /**
     * dataTable
     */
    $routes->connect('/data-table/financial-statements/:symbol', [
        'plugin' => false,
        'controller' => 'FinancialStatement',
        'action' => 'ajaxManageFinancialStatementsSearch'
    ], [
        'pass' => ['symbol'],
        '_name' => 'data_table_financial_statements',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/stocks/list-data-table', [
        'controller' => 'Companies',
        'action' => 'ajaxManageCompanySearch'
    ], [
        '_name' => 'stocks_list_data_table',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/data-table/news', [
        'plugin' => false,
        'controller' => 'News',
        'action' => 'ajaxManageNewsFrontSearch'
    ], [
        '_name' => 'data_table_news_front',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/data-table/time-and-sales', [
        'plugin' => false,
        'controller' => 'Analysis',
        'action' => 'ajaxManageAnalysisTimeAndSalesSearch'
    ], [
        '_name' => 'data_table_time_and_sales',
        'routeClass' => 'ADmad/I18n.I18nRoute'
    ]);

    $routes->connect('/data-table/private', ['controller' => 'Private', 'action' => 'ajaxManagePrivateFrontSearch'], ['_name' => 'data_table_private_front']);

    $routes->connect('/data-table/order', ['controller' => 'Portfolio', 'action' => 'orderDataTable'], ['_name' => 'data_table_order_search']);

    $routes->connect('/:controller', ['action' => 'index'], ['routeClass' => 'ADmad/I18n.I18nRoute']);

    $routes->connect('/:controller/:action/*', [], ['routeClass' => 'ADmad/I18n.I18nRoute']);

    $routes->connect('/:page', ['controller' => 'Pages', 'action' => 'error'], ['_name' => 'error']);

});

Router::connect('/editAvatar/', ['controller' => 'AppUsers', 'action' => 'editAvatar']);

Router::prefix('admin', function ($routes) {
    $routes->connect('/', ['controller' => 'Companies', 'action' => 'index'], ['_name' => 'admin_home']);
    $routes->connect('/company/stock/import/:id', ['controller' => 'Stock', 'action' => 'import'], ['pass' => ['id'], '_name' => 'import_stock']);
    $routes->connect('/company/import', ['controller' => 'Companies', 'action' => 'importAll'], ['_name' => 'import_company']);
    $routes->connect('/company/list', ['controller' => 'Companies', 'action' => 'all'], ['_name' => 'all_company']);
    $routes->connect('/company/add', ['controller' => 'Companies', 'action' => 'add'], ['_name' => 'add_company']);
    $routes->connect('/company/edit/:id', ['controller' => 'Companies', 'action' => 'edit'], ['pass' => ['id'], '_name' => 'edit_company']);
    $routes->connect('/company/delete/:id', ['controller' => 'Companies', 'action' => 'delete'], ['pass' => ['id'], '_name' => 'delete_company']);
    $routes->connect('/company/disable/:id', ['controller' => 'Companies', 'action' => 'disable'], ['pass' => ['id'], '_name' => 'disable_company']);
    $routes->connect('/company/enable/:id', ['controller' => 'Companies', 'action' => 'enable'], ['pass' => ['id'], '_name' => 'enable_company']);
    $routes->connect('/company/stock/add', ['controller' => 'Stock', 'action' => 'add'], ['_name' => 'add_stock']);
    $routes->connect('/company/stock/list', ['controller' => 'Stock', 'action' => 'all'], ['_name' => 'all_company_stock']);
    $routes->connect('/company/stock/edit/:id', ['controller' => 'Stock', 'action' => 'edit'], ['pass' => ['id'], '_name' => 'edit_company_stock']);
    $routes->connect('/company/stock/delete/:id', ['controller' => 'Stock', 'action' => 'delete'], ['pass' => ['id'], '_name' => 'delete_company_stock']);
    $routes->connect('/company/stock/info', ['controller' => 'Stock', 'action' => 'info'], ['_name' => 'stock_info']);
    $routes->connect('/company/get-company', ['controller' => 'Companies', 'action' => 'getCompany'], ['_name' => 'company_get_company']);

    $routes->connect('/news/list', ['controller' => 'News', 'action' => 'news'], ['_name' => 'news_list']);
    $routes->connect('/news/add', ['controller' => 'News', 'action' => 'add'], ['_name' => 'add_news']);
    $routes->connect('/news/edit/:id', ['controller' => 'News', 'action' => 'edit'], ['pass' => ['id'], '_name' => 'edit_news']);
    $routes->connect('/news/delete/:id', ['controller' => 'News', 'action' => 'delete'], ['pass' => ['id'], '_name' => 'delete_news']);
    $routes->connect('/news/run-schedule-manual', ['controller' => 'News', 'action' => 'runSchedulemanual'], ['_name' => 'run_schedule_manual']);

    $routes->connect('/ipo-market/list', ['controller' => 'IpoMarket', 'action' => 'all'], ['_name' => 'all_ipo_markets']);
    $routes->connect('/ipo-market/add', ['controller' => 'IpoMarket', 'action' => 'add'], ['_name' => 'add_ipo_market']);
    $routes->connect('/ipo-market/edit/:id', ['controller' => 'IpoMarket', 'action' => 'edit'], ['pass' => ['id'], '_name' => 'edit_ipo_market']);
    $routes->connect('/ipo-market/delete/:id', ['controller' => 'IpoMarket', 'action' => 'delete'], ['pass' => ['id'], '_name' => 'delete_ipo_market']);
    $routes->connect('/ipo-market/disable/:id', ['controller' => 'IpoMarket', 'action' => 'disable'], ['pass' => ['id'], '_name' => 'disable_ipo_market']);
    $routes->connect('/ipo-market/enable/:id', ['controller' => 'IpoMarket', 'action' => 'enable'], ['pass' => ['id'], '_name' => 'enable_ipo_market']);

    $routes->connect('/ipo-market/ipo-company/list', ['controller' => 'IpoCompany', 'action' => 'all'], ['_name' => 'all_ipo_companies']);
    $routes->connect('/ipo-market/ipo-company/add', ['controller' => 'IpoCompany', 'action' => 'add'], ['_name' => 'add_ipo_company']);
    $routes->connect('/ipo-market/ipo-company/edit/:id', ['controller' => 'IpoCompany', 'action' => 'edit'], ['pass' => ['id'], '_name' => 'edit_ipo_company']);
    $routes->connect('/ipo-market/ipo-company/delete/:id', ['controller' => 'IpoCompany', 'action' => 'delete'], ['pass' => ['id'], '_name' => 'delete_ipo_company']);
    $routes->connect('/ipo-market/ipo-company/disable/:id', ['controller' => 'IpoCompany', 'action' => 'disable'], ['pass' => ['id'], '_name' => 'disable_ipo_company']);
    $routes->connect('/ipo-market/ipo-company/enable/:id', ['controller' => 'IpoCompany', 'action' => 'enable'], ['pass' => ['id'], '_name' => 'enable_ipo_company']);
    $routes->connect('/ipo-market/ipo-company/interests', ['controller' => 'IpoCompany', 'action' => 'interests'], ['_name' => 'interests']);
    $routes->connect('/ipo-market/ipo-company/interests/:companyId', ['controller' => 'IpoCompany', 'action' => 'interestsfilter'], ['pass' => ['companyId'], '_name' => 'interests_filter_company']);
    $routes->connect('/ipo-market/ipo-company/interests/:companyId/:experienceId', ['controller' => 'IpoCompany', 'action' => 'interestsfilter'], ['pass' => ['companyId', 'experienceId'], '_name' => 'interests_filter_experience']);
    $routes->connect('/ipo-market/ipo-company/interests/stats', ['controller' => 'IpoCompany', 'action' => 'interestsStats'], ['_name' => 'ipo-company-stats']);

    $routes->connect('/research-market/list', ['controller' => 'ResearchMarket', 'action' => 'all'], ['_name' => 'all_research_markets']);
    $routes->connect('/research-market/add', ['controller' => 'ResearchMarket', 'action' => 'add'], ['_name' => 'add_research_market']);
    $routes->connect('/research-market/edit/:id', ['controller' => 'ResearchMarket', 'action' => 'edit'], ['pass' => ['id'], '_name' => 'edit_research_market']);
    $routes->connect('/research-market/delete/:id', ['controller' => 'ResearchMarket', 'action' => 'delete'], ['pass' => ['id'], '_name' => 'delete_research_market']);
    $routes->connect('/research-market/disable/:id', ['controller' => 'ResearchMarket', 'action' => 'disable'], ['pass' => ['id'], '_name' => 'disable_research_market']);
    $routes->connect('/research-market/enable/:id', ['controller' => 'ResearchMarket', 'action' => 'enable'], ['pass' => ['id'], '_name' => 'enable_research_market']);

    $routes->connect('/research-market/research-company/list', ['controller' => 'ResearchCompany', 'action' => 'all'], ['_name' => 'all_research_companies']);
    $routes->connect('/research-market/research-company/add', ['controller' => 'ResearchCompany', 'action' => 'add'], ['_name' => 'add_research_company']);
    $routes->connect('/research-market/research-company/edit/:id', ['controller' => 'ResearchCompany', 'action' => 'edit'], ['pass' => ['id'], '_name' => 'edit_research_company']);
    $routes->connect('/research-market/research-company/delete/:id', ['controller' => 'ResearchCompany', 'action' => 'delete'], ['pass' => ['id'], '_name' => 'delete_research_company']);
    $routes->connect('/research-market/research-company/disable/:id', ['controller' => 'ResearchCompany', 'action' => 'disable'], ['pass' => ['id'], '_name' => 'disable_research_company']);
    $routes->connect('/research-market/research-company/enable/:id', ['controller' => 'ResearchCompany', 'action' => 'enable'], ['pass' => ['id'], '_name' => 'enable_research_company']);

    $routes->connect('/post/list', ['controller' => 'PostManagement', 'action' => 'all'], ['_name' => 'all_posts']);
    $routes->connect('/post/edit/:id', ['controller' => 'PostManagement', 'action' => 'edit'], ['pass' => ['id'], '_name' => 'edit_post']);
    $routes->connect('/post/delete/:id', ['controller' => 'PostManagement', 'action' => 'delete'], ['pass' => ['id'], '_name' => 'delete_post']);
    $routes->connect('/post/add', ['controller' => 'PostManagement', 'action' => 'add'], ['_name' => 'add_post']);

    $routes->connect('/pages/list', ['controller' => 'PagesManagement', 'action' => 'all'], ['_name' => 'all_pages']);
    $routes->connect('/pages/edit/:id', ['controller' => 'PagesManagement', 'action' => 'edit'], ['pass' => ['id'], '_name' => 'edit_page']);
    $routes->connect('/pages/delete/:id', ['controller' => 'PagesManagement', 'action' => 'delete'], ['pass' => ['id'], '_name' => 'delete_page']);
    $routes->connect('/pages/add', ['controller' => 'PagesManagement', 'action' => 'add'], ['_name' => 'add_page']);
    $routes->connect('/pages/disable/:id', ['controller' => 'PagesManagement', 'action' => 'disable'], ['pass' => ['id'], '_name' => 'disable_page']);
    $routes->connect('/pages/enable/:id', ['controller' => 'PagesManagement', 'action' => 'enable'], ['pass' => ['id'], '_name' => 'enable_page']);


    $routes->connect('/users/list', ['controller' => 'Users', 'action' => 'all'], ['_name' => 'users_list']);
    $routes->connect('/users/edit/:id', ['controller' => 'Users', 'action' => 'edit'], ['pass' => ['id'], '_name' => 'edit_user']);
    $routes->connect('/users/editAvatar/:id', ['controller' => 'Users', 'action' => 'editAvatar'], ['pass' => ['id'], '_name' => 'edit_avatar_admin']);
    $routes->connect('/users/delete/:id', ['controller' => 'Users', 'action' => 'delete'], ['pass' => ['id'], '_name' => 'delete_user']);
    $routes->connect('/users/add', ['controller' => 'Users', 'action' => 'add'], ['_name' => 'add_user']);
    $routes->connect('/users/enable/:id', ['controller' => 'Users', 'action' => 'enable'], ['pass' => ['id'], '_name' => 'enable_user']);
    $routes->connect('/users/disable/:id', ['controller' => 'Users', 'action' => 'disable'], ['pass' => ['id'], '_name' => 'disable_user']);

    $routes->connect('/brokers/list', ['controller' => 'Brokers', 'action' => 'all'], ['_name' => 'brokers_list']);
    $routes->connect('/brokers/edit/:id', ['controller' => 'Brokers', 'action' => 'edit'], ['pass' => ['id'], '_name' => 'edit_broker']);
    $routes->connect('/brokers/add', ['controller' => 'Brokers', 'action' => 'add'], ['pass' => ['id'], '_name' => 'add_broker']);
    $routes->connect('/brokers/disable/:id', ['controller' => 'Brokers', 'action' => 'disable'], ['pass' => ['id'], '_name' => 'disable_broker']);
    $routes->connect('/brokers/enable/:id', ['controller' => 'Brokers', 'action' => 'enable'], ['pass' => ['id'], '_name' => 'enable_broker']);
    $routes->connect('/brokers/delete/:id', ['controller' => 'Brokers', 'action' => 'delete'], ['pass' => ['id'], '_name' => 'delete_broker']);

    $routes->connect('/partners/list', ['controller' => 'Partner', 'action' => 'all'], ['_name' => 'partners_list']);
    $routes->connect('/partners/edit/:id', ['controller' => 'Partner', 'action' => 'edit'], ['pass' => ['id'], '_name' => 'edit_partner']);
    $routes->connect('/partners/add', ['controller' => 'Partner', 'action' => 'add'], ['pass' => ['id'], '_name' => 'add_partner']);
    $routes->connect('/partners/disable/:id', ['controller' => 'Partner', 'action' => 'disable'], ['pass' => ['id'], '_name' => 'disable_partner']);
    $routes->connect('/partners/enable/:id', ['controller' => 'Partner', 'action' => 'enable'], ['pass' => ['id'], '_name' => 'enable_partner']);
    $routes->connect('/partners/delete/:id', ['controller' => 'Partner', 'action' => 'delete'], ['pass' => ['id'], '_name' => 'delete_partner']);

    $routes->connect('/financial-statement/list', ['controller' => 'FinancialStatement', 'action' => 'index'], ['_name' => 'financial_statement']);
    $routes->connect('/financial-statement/edit/:id', ['controller' => 'FinancialStatement', 'action' => 'edit'], ['pass' => ['id'], '_name' => 'edit_financial_statement']);
    $routes->connect('/financial-statement/delete/:id', ['controller' => 'FinancialStatement', 'action' => 'delete'], ['pass' => ['id'], '_name' => 'delete_financial_statement']);
    $routes->connect('/financial-statement/add', ['controller' => 'FinancialStatement', 'action' => 'add'], ['pass' => ['id'], '_name' => 'add_financial_statement']);

    $routes->connect('/company/stock/details/list', ['controller' => 'StockDetails', 'action' => 'all'], ['_name' => 'stock_details']);
    $routes->connect('/company/stock/details/edit/:id', ['controller' => 'StockDetails', 'action' => 'edit'], ['pass' => ['id'], '_name' => 'edit_stock_details']);
    $routes->connect('/company/stock/details/delete/:id', ['controller' => 'StockDetails', 'action' => 'delete'], ['pass' => ['id'], '_name' => 'delete_stock_details']);
    $routes->connect('/company/stock/details/add', ['controller' => 'StockDetails', 'action' => 'add'], ['pass' => ['id'], '_name' => 'add_stock_details']);

    $routes->connect('/company/buy-sell-broker', ['controller' => 'BuySellBroker', 'action' => 'all'], ['_name' => 'buy_sell_broker']);
    $routes->connect('/company/buy-sell-broker/edit/:id', ['controller' => 'BuySellBroker', 'action' => 'edit'], ['pass' => ['id'], '_name' => 'edit_buy_sell_broker']);
    $routes->connect('/company/buy-sell-broker/delete/:id', ['controller' => 'BuySellBroker', 'action' => 'delete'], ['pass' => ['id'], '_name' => 'delete_buy_sell_broker']);
    $routes->connect('/company/buy-sell-broker/add', ['controller' => 'BuySellBroker', 'action' => 'add'], ['pass' => ['id'], '_name' => 'add_buy_sell_broker']);

    $routes->connect('/sector/performances', ['controller' => 'SectorPerformances', 'action' => 'all'], ['_name' => 'sector_performances']);
    $routes->connect('/sector/performance/edit/:id', ['controller' => 'SectorPerformances', 'action' => 'edit'], ['pass' => ['id'], '_name' => 'edit_sector_performance']);
    $routes->connect('/sector/performance/delete/:id', ['controller' => 'SectorPerformances', 'action' => 'delete'], ['pass' => ['id'], '_name' => 'delete_sector_performance']);
    $routes->connect('/sector/performance/add', ['controller' => 'SectorPerformances', 'action' => 'add'], ['pass' => ['id'], '_name' => 'add_sector_performance']);

    $routes->connect('/order/list', ['controller' => 'Order', 'action' => 'index'], ['_name' => 'order_list']);
    $routes->connect('/order/approve/:id', ['controller' => 'Order', 'action' => 'approve'], ['pass' => ['id'], '_name' => 'order_approve']);
    $routes->connect('/order/reject/:id', ['controller' => 'Order', 'action' => 'reject'], ['pass' => ['id'], '_name' => 'order_reject']);

    $routes->connect('/event/add:', ['controller' => 'Event', 'action' => 'index'], ['_name' => 'add_event']);
    $routes->connect('/event/create', ['controller' => 'Event', 'action' => 'addEvent'], ['_name' => 'create_event']);
    $routes->connect('/event/all', ['controller' => 'Event', 'action' => 'all'], ['_name' => 'all_event']);
    $routes->connect('/event/delete/:id', ['controller' => 'Event', 'action' => 'delete'], ['pass' => ['id'], '_name' => 'delete_event']);
    $routes->connect('/event/enable/:id', ['controller' => 'Event', 'action' => 'enable'], ['pass' => ['id'], '_name' => 'enable_event']);
    $routes->connect('/event/disable/:id', ['controller' => 'Event', 'action' => 'disable'], ['pass' => ['id'], '_name' => 'disable_event']);
    $routes->connect('/event/edit/:id', ['controller' => 'Event', 'action' => 'edit'], ['pass' => ['id'], '_name' => 'edit_event']);

    $routes->connect('/settings', [
        'controller' => 'Settings',
        'action' => 'index'
    ], [
        '_name' => 'settings_index'
    ]);

    $routes->connect('/setting/edit/:id', [
        'controller' => 'Settings',
        'action' => 'edit'
    ], [
        'pass' => ['id'],
        '_name' => 'settings_edit'
    ]);

    /* Data Table start */

    $routes->connect('/data-table/news', [
        'controller' => 'News',
        'action' => 'ajaxManageNewsSearch'
    ], [
        '_name' => 'data_table_news'
    ]);

    $routes->connect('/data-table/company', [
        'controller' => 'Companies',
        'action' => 'ajaxManageCompanySearch'
    ], [
        '_name' => 'data_table_company'
    ]);

    $routes->connect('/data-table/stocks', [
        'controller' => 'Stock',
        'action' => 'ajaxStocksSearch'
    ], [
        '_name' => 'data_table_test_stocks'
    ]);

    $routes->connect('/data-table/stock_details', [
        'controller' => 'StockDetails',
        'action' => 'ajaxManageStockSearch'
    ], [
        '_name' => 'data_table_stock_details'
    ]);

    $routes->connect('/data-table/user', [
        'controller' => 'Users',
        'action' => 'ajaxManageUserSearch'
    ], [
        '_name' => 'data_table_user'
    ]);

    $routes->connect('/data-table/brokers', [
        'controller' => 'Brokers',
        'action' => 'ajaxManageBrokersSearch'
    ], [
        '_name' => 'data_table_brokers'
    ]);

    $routes->connect('/data-table/brokers/buy-sell', [
        'controller' => 'BuySellBroker',
        'action' => 'ajaxManageBuySellBrokerSearch'
    ], [
        '_name' => 'data_table_brokers_buy_sell'
    ]);

    $routes->connect('/data-table/partners', [
        'controller' => 'Partner',
        'action' => 'ajaxManagePartnerSearch'
    ], [
        '_name' => 'data_table_parters'
    ]);

    $routes->connect('/data-table/post', [
        'controller' => 'PostManagement',
        'action' => 'ajaxManagePostSearch'
    ], [
        '_name' => 'data_table_post'
    ]);

    $routes->connect('/data-table/financial-statement', [
        'controller' => 'FinancialStatement',
        'action' => 'ajaxManageFinancialStatementSearch'
    ], [
        '_name' => 'data_table_financial_statement'
    ]);

    $routes->connect('/data-table/order', [
        'controller' => 'Order',
        'action' => 'ajaxOrderSearch'
    ], [
        '_name' => 'data_table_order'
    ]);

    $routes->connect('/data-table/event', [
        'controller' => 'Event',
        'action' => 'ajaxEventSearch'
    ], [
        '_name' => 'data_table_events'
    ]);

    /*  Data Table end */
});

/**
 * Load all plugin routes. See the Plugin documentation on
 * how to customize the loading of plugin routes.
 */
Plugin::routes();
